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
	
    public function login($username, $password) {
		// Point 1 (audit sécurité, 2026-07-29) : $username venait de
		// stopXSS() (ne protège pas l'apostrophe) - injection SQL possible
		// depuis le formulaire de connexion lui-même, accessible sans
		// authentification.
		$infos = $this->db()->row("SELECT id, username, password FROM {sb_users} WHERE username = ?", array((string) $username));
		if (!$infos || !isset($infos['password']) || $infos['password'] === '') {
			return false;
		}

		$stored = $infos['password'];

		// Mots de passe hachés uniquement (password_hash()). L'ancien
		// chiffrement réversible (encrypt()/decrypt(), clé codée en dur) a
		// été supprimé le 2026-10-01 : les comptes encore à l'ancien format
		// passent par backdoor/migrate-passwords.php.
		if ((string)$password === '') return false;
		if (password_verify((string)$password, $stored)) {
			if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
				$this->rehashPassword($infos['id'], $password);
			}
			return true;
		}

		// Filet : jusqu'au 2026-10-01, users.php hachait displayText() du
		// mot de passe (entités HTML) au lieu de la saisie brute. Si c'est
		// cette forme qui matche, on rehache la saisie brute.
		$password_entities = htmlentities((string)$password, ENT_QUOTES, 'UTF-8');
		if ($password_entities !== $password && password_verify($password_entities, $stored)) {
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
	private function rehashPassword($user_id, $plain_password) {
		$this->db()->update('sb_users', array('password' => $this->hashPassword($plain_password)), 'id = ?', array(intval($user_id)));
	}


	/**
	 * Hash à stocker en base pour un mot de passe saisi EN CLAIR (valeur
	 * brute, sans displayText()/stopXSS() : c'est ce que login() vérifie).
	 */
	public function hashPassword($plain_password) {
		return password_hash((string)$plain_password, PASSWORD_DEFAULT);
	}


	/**
	 * Hash stocké en base pour ce compte, false si le compte est inconnu.
	 */
	public function getPasswordHash($username) {
		$hash = $this->db()->value("SELECT password FROM {sb_users} WHERE username = ?", array((string) $username));
		return is_string($hash) ? $hash : false;
	}


	/**
	 * La session garde le hash en vigueur à la connexion
	 * ($_SESSION['sbuiadmin_user_password']). Si le mot de passe change en
	 * base, le hash change aussi et TOUTES les sessions ouvertes sur ce
	 * compte tombent à leur requête suivante.
	 */
	public function checkSessionHash($username, $session_hash) {
		$hash = $this->getPasswordHash($username);
		return ($hash !== false && $hash !== '' && (string)$session_hash !== '' && hash_equals($hash, (string)$session_hash));
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
	public function createRememberToken($user_id, $lifetime) {
		$user_id   = intval($user_id);
		$selector  = bin2hex(random_bytes(9));
		$validator = bin2hex(random_bytes(33));
		$hash      = hash('sha256', $validator);
		$expires   = time() + intval($lifetime);

		$row = array('user_id' => $user_id, 'selector' => $selector, 'validator_hash' => $hash, 'expires' => $expires);
		if ($this->db()->insert('sb_users_remember_tokens', $row) === false) return false;

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
	public function verifyRememberToken($cookie_value) {
		if (strpos((string)$cookie_value, ':') === false) return false;
		list($selector, $validator) = explode(':', $cookie_value, 2);
		$row = $this->db()->row("SELECT t.id, t.user_id, t.validator_hash, t.expires, u.username
					FROM {sb_users_remember_tokens} t
					INNER JOIN {sb_users} u ON u.id = t.user_id
					WHERE t.selector = ?", array($selector));

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
	public function deleteRememberTokenBySelector($cookie_value) {
		if (strpos((string)$cookie_value, ':') === false) return;
		list($selector) = explode(':', $cookie_value, 2);
		$this->db()->delete('sb_users_remember_tokens', 'selector = ?', array($selector));
	}

	/**
	 * Révoque tous les jetons "Se souvenir de moi" d'un compte - à appeler
	 * à chaque changement de mot de passe.
	 */
	public function revokeRememberTokens($user_id) {
		$this->db()->delete('sb_users_remember_tokens', 'user_id = ?', array(intval($user_id)));
	}

	private function deleteRememberTokenById($id) {
		$this->db()->delete('sb_users_remember_tokens', 'id = ?', array(intval($id)));
	}
	
	
    private function checkUser($password, $captcha) {
        if (isset($_SESSION['sbuiadmin_user_name']) || $_SESSION['sbuiadmin_user_name'] != '') {
            if (!$this->login($_SESSION['sbuiadmin_user_name'], $password)) {
                return false;
            } else {
                // Ancien captcha maison (session captchaResult) retiré : les
                // formulaires sont protégés par ALTCHA (sbAltchaVerify())
                return true;
            }
        } else {
            return false;
        }
    }
	
	
    // Compte inconnu : inactif (avant la 4.73 : actif, avec une alerte PHP)
    public function checkUserIsActive($username) {
        $active = $this->db()->value("SELECT active FROM {sb_users} WHERE username = ?", array((string) $username));
        return $active !== null && $active !== false && $active != '0';
    }
	
	
    private function checkIsAdmin() {
        if (isset($_SESSION['sbuiadmin_user_name']) || $_SESSION['sbuiadmin_user_name'] != '') return true;
        else return false;
    }
	
	
	/**
	* Update Access Log
	* @return bool
	*/
	public function updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user = 'admin') {
		// --- Update the Access Log file if exist
		global $sbsanitize;
		// htmlentities(ENT_QUOTES) neutralise l'apostrophe mais pas
		// l'antislash final ("admin\" casse la requête) : échapper aussi
		// pour le SQL. Le nom vient du formulaire de login, sans session.
	        $_sbuiadmin_event = $sbsanitize->displayText($sbuiadmin_event, 'UTF-8', $entities = 1, $decode_entities = 0, $html = 0, $br = 0, $clickable = 0, $xss = 1);
	        $_sbuiadmin_user  = $sbsanitize->displayText($sbuiadmin_user, 'UTF-8', $entities = 1, $decode_entities = 0, $html = 0, $br = 0, $clickable = 0, $xss = 1);
		return $this->db()->insert('sb_logaccess', array(
			'logaccess_type'  => (string) $sbuiadmin_type,
			'logaccess_date'  => time(),
			'logaccess_user'  => $_sbuiadmin_user,
			'logaccess_event' => $_sbuiadmin_event,
		)) !== false;
	}


	/**
	* Purge Access Log : lignes plus anciennes que la durée de conservation
	* (Configuration > Fonctionnalités), sauf l'historique des mises à jour
	* @return bool
	*/
	public function purgeAccessLog() {
		$days = function_exists('sbLogaccessRetention') ? sbLogaccessRetention() : 180;
		return $this->db()->delete('sb_logaccess', "`logaccess_type` <> 'update' AND `logaccess_date` < ?", array(time() - intval($days) * 86400)) !== false;
	}


	/**
	* Purge du journal au plus une fois par jour (date dans logaccess_lastpurge),
	* appelée à chaque requête par index.php et backdoor/index.php
	* @return bool purge faite
	*/
	public function purgeAccessLogDaily() {
		if (!function_exists('sbSettingsSave')) return false;
		$today = date('Y-m-d');
		if (sbSetting('logaccess_lastpurge') === $today) return false;
		// Date posée d'abord : une seule tentative par jour, même en échec
		if (!sbSettingsSave(array('logaccess_lastpurge' => $today))) return false;
		return $this->purgeAccessLog();
	}


	/**
	* Update Acces Login / Last login Time User
	* @return bool
	*/
	public function updateAccessUserLogin($sbuiadmin_user, $lastlogin = false, $time = false) {
		// --- Update the Access User logintime
		if ($sbuiadmin_user != '' && $lastlogin == false) {
			return $this->db()->update('sb_users', array('logintime' => (string) $time), 'username = ?', array((string) $sbuiadmin_user)) !== false;
		} elseif ($sbuiadmin_user != '' && $lastlogin) {
			return $this->db()->run("UPDATE {sb_users} SET lastlogin = logintime WHERE username = ?", array((string) $sbuiadmin_user)) !== false;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Get User Infos
	 */
	public function getUserInfo($sbuiadmin_user, $field = '') {
		global $sbsanitize;
		// --- Initialization
		$db = $this->db();
		// Nom de colonne : seulement une vraie colonne de sb_users
		if (!is_string($field) || !in_array($field, $db->columns('sb_users'), true)) return false;
		$value = $db->value("SELECT " . $db->ident($field) . " FROM {sb_users} WHERE username = ?", array($sbsanitize->stopXSS($sbuiadmin_user)));
		return ($value === null || $value === false) ? false : $value;
	}

}

?>
