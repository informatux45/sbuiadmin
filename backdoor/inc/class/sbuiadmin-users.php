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
		// Initialisation
		$query  = "SELECT username, password FROM " . _AM_DB_PREFIX . "sb_users WHERE username = '$username'";
        $result = $this->query($query);
		$infos  = $this->assoc($result);
		// Check if user exists
		if ($result) {
			// Passwords
			$password_db    = $this->decrypt($infos['password']);
			$password_login = $this->decrypt($password);
			// Check passwords
			if ($password_db !== $password_login)
				return false;
			else
				return true;

		} else {
			// User unknown
			return false;
		}
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
        $query_user = "SELECT active FROM " . _AM_DB_PREFIX . "sb_users WHERE username = '$username'";
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
		$sql = "INSERT INTO " . _AM_DB_PREFIX . "sb_logaccess
				(`logaccess_type`, `logaccess_date`, `logaccess_user`, `logaccess_event`)
				VALUES ('$sbuiadmin_type', UNIX_TIMESTAMP(), '$sbuiadmin_user', '$sbuiadmin_event')";
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
		if ($sbuiadmin_user != '' && $lastlogin == false) {
			$sql = "UPDATE " . _AM_DB_PREFIX . "sb_users SET logintime = '$time' WHERE username = '$sbuiadmin_user'";
			$result = $this->query($sql);
			if (!$result)
				return false;
			else
				return true;
		} elseif ($sbuiadmin_user != '' && $lastlogin) {
			$sql = "UPDATE " . _AM_DB_PREFIX . "sb_users SET lastlogin = logintime WHERE username = '$sbuiadmin_user'";
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
		$sbuiadmin_user = $sbsanitize->stopXSS($sbuiadmin_user);
		
        $sql       = "SELECT $field FROM " . _AM_DB_PREFIX . "sb_users WHERE username = '$sbuiadmin_user'";
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
			$decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv2);
			return $decrypted;
		}
	}
	
}

?>