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

# Anti-robot ALTCHA (même réglage que la connexion à l'administration)
$sb_user_altcha = (sbSetting('altcha_login', '1') === '1');
$sbsmarty->assign('sb_altcha_widget', $sb_user_altcha ? sbAltchaWidget() : '');

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
			// Mot de passe en clair pour login() : depuis la migration vers
			// password_hash() (Point 1), un chiffré ne matche plus jamais et
			// cette connexion échouait toujours. Jamais stocké en session.
			$sbuiadmin_user_password = $_POST['password'];
			if (sbLoginLocked($sbuiadmin_user_name)) {
				// --- Blocage temporaire : refus sans vérifier le mot de passe
				$sbsmarty->assign('sbuiadmin_access_code', 'E5');
				$sbuiadmin_type = 'fronterror';
				$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_LOCKED, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
				$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
			} elseif ($sb_user_altcha && !sbAltchaVerify()) {
				// --- ALTCHA, avant toute vérification du mot de passe
				$sbsmarty->assign('sbuiadmin_access_code', 'E1');
				$sbuiadmin_type = 'fronterror';
				$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]) . ' (' . $GLOBALS['sb_altcha_error'] . ')';
				$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
			} elseif ($sbusers->login($sbuiadmin_user_name, $sbuiadmin_user_password)) {
				if (!$sbusers->checkUserIsActive($sbuiadmin_user_name)) {
					// --- User is no more active
					$sbsmarty->assign('sbuiadmin_access_code', 'E4');
					$sbuiadmin_type = 'fronterror';
					$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_USER_ERROR, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
					$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
				} else {
					// ------------------
					// --- Acces autorise
					// ------------------
					sbLoginSucceeded($sbuiadmin_user_name);
					// Update Access Log
					$sbuiadmin_type = 'frontlogin';
					$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_GRANTED, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
					$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
					// Update LoginTime
					$sbusers->updateAccessUserLogin($sbuiadmin_user_name, false, time());
					// Assign SESSION
					session_regenerate_id(true);
					$_SESSION['sbuiadmin_user_name']     = $sbuiadmin_user_name;
					$_SESSION['sbuiadmin_user_password'] = $sbusers->getPasswordHash($sbuiadmin_user_name); // hash, jamais le mot de passe
					unset($_SESSION['sb2fa_ok'], $_SESSION['sb2fa']); // le back-office redemandera le code 2FA
				}
			} else {
				// ------------------
				// --- Failed auth
				// ------------------
				sbLoginFailed($sbuiadmin_user_name);
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
