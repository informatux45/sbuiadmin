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
defined('SBMAGIC_PATH') or die('Are you crazy!');


class user extends sql {
	
    function login($username, $password) {
        // Query SQL
		$query = "SELECT username, password FROM " . _AM_DB_PREFIX . "sb_users WHERE username = '$username' AND password = '$password'";
        $result = $this->query($query);
        if (!$result) {
            return false;
        } else {
            if ($this->numrows() > 0) return true;
            else return false;
        }
    }
	
	
    function checkUser($password, $captcha) {
        if (isset($_SESSION['sbmagic_user_name']) || $_SESSION['sbmagic_user_name'] != '') {
            if (!$this->login($_SESSION['sbmagic_user_name'], $password, $crypt)) {
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
        if (isset($_SESSION['sbmagic_user_name']) || $_SESSION['sbmagic_user_name'] != '') return true;
        else return false;
    }
	
	
	/**
	* Update Access Log
	* @return bool
	*/
	function updateAccessLog($sbmagic_type, $sbmagic_event, $sbmagic_user = 'admin') {
		// --- Update the Access Log file if exist
		$sql = "INSERT INTO " . _AM_DB_PREFIX . "sb_logaccess
				(`logaccess_type`, `logaccess_date`, `logaccess_user`, `logaccess_event`)
				VALUES ('$sbmagic_type', UNIX_TIMESTAMP(), '$sbmagic_user', '$sbmagic_event')";
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
	function updateAccessUserLogin($sbmagic_user, $lastlogin = false, $time = false) {
		// --- Update the Access User logintime
		if ($sbmagic_user != '' && $lastlogin == false) {
			$sql = "UPDATE " . _AM_DB_PREFIX . "sb_users SET logintime = '$time' WHERE username = '$sbmagic_user'";
			$result = $this->query($sql);
			if (!$result)
				return false;
			else
				return true;
		} elseif ($sbmagic_user != '' && $lastlogin) {
			$sql = "UPDATE " . _AM_DB_PREFIX . "sb_users SET lastlogin = logintime WHERE username = '$sbmagic_user'";
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
	function getUserInfo($sbmagic_user, $field = '') {
		global $sbsanitize;
		// --- Initialization
		$field        = $sbsanitize->stopXSS($field);
		$sbmagic_user = $sbsanitize->stopXSS($sbmagic_user);
		
        $sql       = "SELECT $field FROM " . _AM_DB_PREFIX . "sb_users WHERE username = '$sbmagic_user'";
        $result    = $this->query($sql);
        $user_info = $this->assoc($result);
        if ($user_info[$field]) {
            return $user_info[$field];
        } else {
            return false;
        }
	}
	
		
	/**
	 * Returns an encrypted & utf8-encoded
	 */
	function encrypt($text, $key = '(D$9=h!S2olla$rS3+huY!NX', $iv = "fYAhHeXm", $bit_check = 32) {
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
	}
	

	/**
	 * Returns decrypted original string
	 */	
	function decrypt($encrypted_text, $key = '(D$9=h!S2olla$rS3+huY!NX', $iv = "fYAhHeXm", $bit_check = 32){
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
	}
	
}

?>