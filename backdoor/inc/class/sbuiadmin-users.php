<?php
/** *****************************************************************************
*                        INFORMATUX user class (UTF8)                           *
/** *****************************************************************************
* @author     Patrice BOUTHIER <contact[at]informatux.com>                      *
* @copyright  1996-2016 INFORMATUX                                              *
* @link       http://www.informatux.com/                                        *
* @since      1.0                                                               *
* @version    CVS: 1.8                                                          *
* ----------------------------------------------------------------------------- *
* Copyright (c) 2011, INFORMATUX Solutions and web development                  *
* All rights reserved.                                                          *
***************************************************************************** **/

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');


class user extends sql {
	
    function login($username, $password) {
		// Point 1 (audit sécurité, 2026-07-29) : $username venait de
		// stopXSS() (ne protège pas l'apostrophe) - injection SQL possible
		// depuis le formulaire de connexion lui-même, accessible sans
		// authentification.
		$username_esc = $this->escape_string($username);
		$query  = "SELECT id, username, password FROM " . _AM_DB_PREFIX . "sb_users WHERE username = '$username_esc'";
        $result = $this->query($query);
		$infos  = $this->assoc($result);
		// $result (retour mysqli_query) est vrai même pour 0 ligne trouvée -
		// vérifier $infos, pas $result, pour un "utilisateur inconnu" correct.
		if (!$infos || !isset($infos['password']) || $infos['password'] === '') {
			return false;
		}

		$stored = $infos['password'];

		// Format actuel : mots de passe hachés (password_hash(), à sens
		// unique - ne remplace PAS decrypt()/encrypt() ci-dessous tant que
		// tous les comptes n'ont pas été confirmés migrés, voir leur docblock).
		if (password_verify($password, $stored)) {
			if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
				$this->rehashPassword($infos['id'], $password);
			}
			return true;
		}

		// Ancien format (chiffrement réversible, pré-migration) - filet de
		// compatibilité UNIQUEMENT : si ça matche, on bascule silencieusement
		// ce compte vers password_hash() (aucune action requise de
		// l'utilisateur). $stored qui n'est pas un ancien format valide
		// (ex: déjà un hash password_hash() qui vient d'échouer ci-dessus)
		// fait tomber ici sans risque - decrypt() renvoie alors une valeur
		// qui ne matchera jamais $password. @ : decrypt() émet un warning
		// PHP sur un $stored qui n'a pas la forme attendue (explode('::', ...)
		// sur une valeur qui n'en contient pas), sans gravité ici.
		$legacy_plain = @$this->decrypt($stored);
		if ($legacy_plain !== false && $legacy_plain !== '' && hash_equals($legacy_plain, $password)) {
			$this->rehashPassword($infos['id'], $password);
			return true;
		}

		return false;
    }


	/**
	 * Bascule un compte vers password_hash() (Point 1) - appelé UNIQUEMENT
	 * juste après une vérification de mot de passe déjà réussie ci-dessus,
	 * jamais avant.
	 */
	function rehashPassword($user_id, $plain_password) {
		$user_id  = intval($user_id);
		$new_hash = $this->escape_string(password_hash($plain_password, PASSWORD_DEFAULT));
		$this->query("UPDATE " . _AM_DB_PREFIX . "sb_users SET password = '$new_hash' WHERE id = $user_id");
	}


	/**
	 * "Se souvenir de moi" (Point 1) - jeton sélecteur/validateur au lieu
	 * du mot de passe chiffré stocké en cookie. Le sélecteur sert de clé
	 * de recherche rapide (indexée, non secrète) ; seul le hash du
	 * validateur (haute entropie, sha256 suffit - pas un mot de passe) est
	 * stocké, jamais le validateur lui-même. Retourne "selector:validator"
	 * (valeur brute du cookie) ou false en cas d'échec.
	 * @return string|false
	 */
	function createRememberToken($user_id, $lifetime) {
		$user_id   = intval($user_id);
		$selector  = bin2hex(random_bytes(9));
		$validator = bin2hex(random_bytes(33));
		$hash      = hash('sha256', $validator);
		$expires   = time() + intval($lifetime);

		$query = "INSERT INTO " . _AM_DB_PREFIX . "sb_users_remember_tokens (user_id, selector, validator_hash, expires) VALUES ($user_id, '$selector', '$hash', $expires)";
		if (!$this->query($query)) return false;

		return $selector . ':' . $validator;
	}

	/**
	 * Vérifie un cookie "selector:validator" et retourne les infos du
	 * compte correspondant si valide (et pas expiré), false sinon. Le
	 * jeton est TOUJOURS supprimé ici (à usage unique, qu'il soit valide
	 * ou non) - c'est à l'appelant d'émettre un jeton de remplacement
	 * (createRememberToken()) une fois les vérifications additionnelles
	 * passées (ex: compte toujours actif) - pas fait automatiquement ici
	 * pour ne jamais réémettre un jeton à un compte qui va être rejeté.
	 * @return array{user_id:int,username:string}|false
	 */
	function verifyRememberToken($cookie_value) {
		if (strpos((string)$cookie_value, ':') === false) return false;
		list($selector, $validator) = explode(':', $cookie_value, 2);
		$selector_esc = $this->escape_string($selector);

		$query = "SELECT t.id, t.user_id, t.validator_hash, t.expires, u.username
					FROM " . _AM_DB_PREFIX . "sb_users_remember_tokens t
					INNER JOIN " . _AM_DB_PREFIX . "sb_users u ON u.id = t.user_id
					WHERE t.selector = '$selector_esc'";
		$result = $this->query($query);
		$row    = $this->assoc($result);

		if (!$row) return false;
		$this->deleteRememberTokenById($row['id']);

		if (intval($row['expires']) < time()) return false;
		if (!hash_equals($row['validator_hash'], hash('sha256', $validator))) {
			// Sélecteur valide mais mauvais validateur : signe possible de
			// vol de cookie. Le jeton est déjà supprimé ci-dessus.
			return false;
		}

		return array('user_id' => intval($row['user_id']), 'username' => $row['username']);
	}

	/**
	 * Supprime un jeton "Se souvenir de moi" à partir de la valeur brute du
	 * cookie (ex: à la déconnexion).
	 */
	function deleteRememberTokenBySelector($cookie_value) {
		if (strpos((string)$cookie_value, ':') === false) return;
		list($selector) = explode(':', $cookie_value, 2);
		$selector_esc = $this->escape_string($selector);
		$this->query("DELETE FROM " . _AM_DB_PREFIX . "sb_users_remember_tokens WHERE selector = '$selector_esc'");
	}

	function deleteRememberTokenById($id) {
		$id = intval($id);
		$this->query("DELETE FROM " . _AM_DB_PREFIX . "sb_users_remember_tokens WHERE id = $id");
	}
	
	
    function checkUser($password, $captcha) {
        if (isset($_SESSION['sbuiadmin_user_name']) || $_SESSION['sbuiadmin_user_name'] != '') {
            if (!$this->login($_SESSION['sbuiadmin_user_name'], $password, $crypt)) {
                return false;
            } elseif (_AM_CAPTCHA_MODE == 0) {
                return true;
            } else {
                if ($_SESSION['captchaResult'] == $captcha) return true;
                else return false;
            }
        } else {
            return false;
        }
    }
	
	
    function checkUserIsActive($username) {
        $username_esc = $this->escape_string($username);
        $query_user = "SELECT active FROM " . _AM_DB_PREFIX . "sb_users WHERE username = '$username_esc'";
        $result_user = $this->query($query_user);
        $user_infos = $this->assoc($result_user);
        if ($user_infos['active'] == '0') {
            return false;
        } else {
            return true;
        }
    }
	
	
    function checkIsAdmin() {
        if (isset($_SESSION['sbuiadmin_user_name']) || $_SESSION['sbuiadmin_user_name'] != '') return true;
        else return false;
    }
	
	
	/**
	* Update Access Log
	* @return bool
	*/
	function updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user = 'admin') {
		// --- Update the Access Log file if exist
		global $sbsanitize;
	        $_sbuiadmin_event = $sbsanitize->displayText($sbuiadmin_event, 'UTF-8', $entities = 1, $decode_entities = 0, $html = 0, $br = 0, $clickable = 0, $xss = 1);
	        $_sbuiadmin_user  = $sbsanitize->displayText($sbuiadmin_user, 'UTF-8', $entities = 1, $decode_entities = 0, $html = 0, $br = 0, $clickable = 0, $xss = 1);
		$sql = "INSERT INTO " . _AM_DB_PREFIX . "sb_logaccess
				(`logaccess_type`, `logaccess_date`, `logaccess_user`, `logaccess_event`)
				VALUES ('$sbuiadmin_type', UNIX_TIMESTAMP(), '$_sbuiadmin_user', '$_sbuiadmin_event')";
		$result = $this->query($sql);
		if (!$result)
			return false;
		else
			return true;
	}


	/**
	* Update Acces Login / Last login Time User
	* @return bool
	*/
	function updateAccessUserLogin($sbuiadmin_user, $lastlogin = false, $time = false) {
		// --- Update the Access User logintime
		$sbuiadmin_user_esc = $this->escape_string($sbuiadmin_user);
		if ($sbuiadmin_user != '' && $lastlogin == false) {
			$sql = "UPDATE " . _AM_DB_PREFIX . "sb_users SET logintime = '$time' WHERE username = '$sbuiadmin_user_esc'";
			$result = $this->query($sql);
			if (!$result)
				return false;
			else
				return true;
		} elseif ($sbuiadmin_user != '' && $lastlogin) {
			$sql = "UPDATE " . _AM_DB_PREFIX . "sb_users SET lastlogin = logintime WHERE username = '$sbuiadmin_user_esc'";
			$result = $this->query($sql);
			if (!$result)
				return false;
			else
				return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Get User Infos
	 */
	function getUserInfo($sbuiadmin_user, $field = '') {
		global $sbsanitize;
		// --- Initialization
		$field        = $sbsanitize->stopXSS($field);
		$sbuiadmin_user_esc = $this->escape_string($sbsanitize->stopXSS($sbuiadmin_user));

        $sql       = "SELECT $field FROM " . _AM_DB_PREFIX . "sb_users WHERE username = '$sbuiadmin_user_esc'";
        $result    = $this->query($sql);
        $user_info = $this->assoc($result);
        if (isset($user_info[$field])) {
            return $user_info[$field];
        } else {
            return false;
        }
	}
	
		
	/**
	 * Returns an encrypted & utf8-encoded
	 */
	function encrypt($text, $key = '(D$9=h!S2olla$rS3+huY!NX', $iv = "fYAhHeXm", $bit_check = 32, $tag = "informatux") {
		// Check if php version smaller than 7.1.0
		if (version_compare(phpversion(), '7.1.0', '<')) {
			// All method
			$text_num = str_split($text, $bit_check);
			$text_num = $bit_check-strlen($text_num[count($text_num)-1]);
			
			for ($i=0; $i<$text_num; $i++) {
				$text = $text . chr($text_num);
			}
			
			$cipher = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');
			mcrypt_generic_init($cipher, $key, $iv);
			
			$decrypted = mcrypt_generic($cipher, $text);
			mcrypt_generic_deinit($cipher);
			
			return base64_encode($decrypted);
		} else {
			/* New method for php version 7.1 minimum
			 * $cipher     = "aes-128-gcm";
			 * $ivlen      = openssl_cipher_iv_length($cipher);
			 * $iv2        = openssl_random_pseudo_bytes($ivlen);
			 * $ciphertext = openssl_encrypt($text, $cipher, $key, $options=0, $iv2, $tag);
			 */
			// --- Remove the base64 encoding from our key
			$encryption_key = base64_decode($key);
			// --- Generate an initialization vector
			$iv2 = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
			// --- Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
			$encrypted = openssl_encrypt($text, 'aes-256-cbc', $encryption_key, 0, $iv2);
			// --- The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
			$encrypted_text = base64_encode($encrypted . '::' . $iv2);

			return $encrypted_text;		
		}


	}
	

	/**
	 * Returns decrypted original string
	 */	
	function decrypt($encrypted_text, $key = '(D$9=h!S2olla$rS3+huY!NX', $iv = "fYAhHeXm", $bit_check = 32, $tag = "informatux") {
		// Check if php version smaller than 7.1.0
		if (version_compare(phpversion(), '7.1.0', '<')) {
			$cipher = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');
			mcrypt_generic_init($cipher, $key, $iv);
	
			$decrypted = mdecrypt_generic($cipher,base64_decode($encrypted_text));
			mcrypt_generic_deinit($cipher);
	
			$last_char = substr($decrypted,-1);
	
			for($i=0; $i<$bit_check-1; $i++) {
				if(chr($i) == $last_char) {
					$decrypted = substr($decrypted, 0, strlen($decrypted)-$i);
					break;
				}
			}
			return $decrypted;
		} else {
			// New method for php version 7.2 minimum
			//$cipher     = "aes-128-gcm"; // Or "AES-256-CFB"
			//$ivlen      = openssl_cipher_iv_length($cipher);
			//$iv2        = openssl_random_pseudo_bytes($ivlen);
			////store $cipher, $iv, and $tag for decryption later
			//$decrypted = openssl_decrypt ($encrypted_text, $cipher, $key, $options=0, $iv2, $tag);
			// Remove the base64 encoding from our key
			$encryption_key = base64_decode($key);
			// To decrypt, split the encrypted data from our IV - our unique separator used was "::"
			list($encrypted_data, $iv2) = explode('::', base64_decode($encrypted_text), 2);
			$decrypted = @openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv2);
			return $decrypted;
		}
	}
	
}

?>
