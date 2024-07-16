<?php
/**
 * Plugin Name: SBUIADMIN USER
 * Description: Gestion des utilisateurs
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

# Define some important stuff
define('MODULEFILE', basename(__FILE__, ".php"));
define('MODULENAME', 'User');
define('MODULEVERSION','0.1.1');

# Include Module Common Infos
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'common.php' );
global $module, $sbsmarty, $sbsanitize, $sbsql, $sbusers, $sbpage;

# Include Module Common Infos
$sblang_user = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_user . '.php' );
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php' );

# Initialization
global $sb_settings_config;
$publickey      = $sbsanitize->sTrim($sb_settings_config[19]);
$privatekey     = $sbsanitize->sTrim($sb_settings_config[20]);
$captcha_active = $sbsanitize->sTrim($sb_settings_config[22]);
$sbsmarty->assign('grecaptcha_publickey', $publickey);
if (!empty($publickey) && !empty($privatekey) && $captcha_active) {
	defined('_CMS_USER_CAPTCHA_MODE') OR define('_CMS_USER_CAPTCHA_MODE', "true");
}

# Define TPL to show (view)
if (!isset($_GET['op'])) {
	$op       = 'index';
	$template = 'index';
} else {
	$op       = $sbsanitize->addSlashes($_GET['op']);
	$id       = intval($_GET['id']);
	$template = 'display_'.$op;
}
$module['template_main'] = MODULEFILE . '_' . $template . '.tpl';

# -------------------------

// --------------------------
// --- Switch with Op GET
// --------------------------
switch($op) {
	default: // Show form login (user)
		// ----------------------
		// check if POST (Login)
		// ----------------------
		if (isset($_POST['username']) && isset($_POST['password'])) {
			// ------------------
			// --- Form auth
			// ------------------
			$sbuiadmin_user_name     = trim($sbsanitize->stopXSS($_POST['username']));
			$sbuiadmin_user_password = trim($sbusers->encrypt($_POST['password']));
			if ($sbusers->login($sbuiadmin_user_name, $sbuiadmin_user_password)) {
				if (!$sbusers->checkUserIsActive($sbuiadmin_user_name)) {
					// --- User is no more active
					$sbsmarty->assign('sbuiadmin_access_code', 'E4');
					$sbuiadmin_type = 'fronterror';
					$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_USER_ERROR, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
					$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
				} else {
					if (_CMS_USER_CAPTCHA_MODE == "true") {
						// --- Check Google Recaptcha
						if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
							function getCurlData($url) {
								$curl = curl_init();
								curl_setopt($curl, CURLOPT_URL, $url);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($curl, CURLOPT_TIMEOUT, 10);
								curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
								$curlData = curl_exec($curl);
								curl_close($curl);
								return $curlData;
							}
							
							// --- Get verify response data
							$google_url = "https://www.google.com/recaptcha/api/siteverify";
							$ip         = $_SERVER['REMOTE_ADDR'];
							$url        = $google_url . "?secret=" . $privatekey . "&response=" . $_POST['g-recaptcha-response'] . "&remoteip=" . $ip;
							$response   = getCurlData($url);
							$response   = json_decode($response); // Don't add TRUE setting in json_decode
							
							if ($response->success === false) {
								// --- Error Google Recaptcha
								$sbsmarty->assign('sbuiadmin_access_code', 'E1');
								$sbuiadmin_type = 'fronterror';
								$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
								$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
							} else {
								// --- Success Google Recaptcha
								// ------------------
								// --- Acces autorise
								// ------------------
								// Update Access Log
								$sbuiadmin_type = 'frontlogin';
								$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_GRANTED, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
								$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
								// Update LoginTime
								$sbusers->updateAccessUserLogin($sbuiadmin_user_name, false, time());
								// Assign SESSION
								$_SESSION['sbuiadmin_user_name']     = $sbuiadmin_user_name;
								$_SESSION['sbuiadmin_user_password'] = $sbuiadmin_user_password;
							}
							
						} else {
							// --- Error Google Recaptcha
							$sbsmarty->assign('sbuiadmin_access_code', 'E1');
							$sbuiadmin_type = 'fronterror';
							$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
							$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
						}
					} else {
						// ------------------
						// --- Acces autorise
						// ------------------
						// Update Access Log
						$sbuiadmin_type = 'frontlogin';
						$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_GRANTED, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
						$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
						// Update LoginTime
						$sbusers->updateAccessUserLogin($sbuiadmin_user_name, false, time());
						// Assign SESSION
						$_SESSION['sbuiadmin_user_name']     = $sbuiadmin_user_name;
						$_SESSION['sbuiadmin_user_password'] = $sbuiadmin_user_password;
					}
				}
			} else {
				// ------------------
				// --- Failed auth
				// ------------------
				$sbsmarty->assign('sbuiadmin_access_code', 'E2');
				$sbuiadmin_type = 'fronterror';
				$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_NOGRANTED, $_SERVER["REMOTE_ADDR"]);
				$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event);
			}
		}
		
		// --------------------------
		// --- Logout
		// --------------------------
		if (isset($_GET['ac']) && $_GET['ac'] == 'logout') {
			// ------------------
			// --- Logout required
			// ------------------
			// Update LastLogin
			$sbusers->updateAccessUserLogin($_SESSION['sbuiadmin_user_name'], true);
			session_start();
			session_unset();
			session_destroy();
			session_write_close();
			setcookie(session_name(),'',0,'/');
			session_regenerate_id(true);
			header("Location: " . SB_URL);
		}

		// --------------------------
		// --- Assign Title Page
		// --------------------------
		$sb_user_title = _CMS_USER_TITLE;
		// --- Assign user page title
		$sbsmarty->assign('sb_pages_title', $sb_user_title);
		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = 'index';

	break;
	
}

// --------------------------
// --- Assign:
// --- Module view
// --- Page active
// --------------------------
//$module['module_main'] = $assoc['module_view'];

// --------------------------
// --- Add Template BLOCKS (depends on the theme view choosen)
// --------------------------
$module['template_main_blocks'] = MODULEFILE . '_index_blocks.tpl';

?>