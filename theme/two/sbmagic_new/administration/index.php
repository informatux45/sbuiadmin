<?php
/**
 * Admin Startbootstrap
 * Main file (engine)
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date dans le passé
 
// ----------------------
// Session Initialization
// ----------------------
ini_set('session.gc_probability',	1);
ini_set('session.gc_divisor',		50); 
ini_set('session.save_handler',		'files');
ini_set('session.name',				'sbmagic_session');
ini_set('session.auto_start',		1);
ini_set("session.gc_maxlifetime",	14440); // 4 hours
ini_set('session.use_cookies',		'1');
ini_set('session.use_only_cookies',	'0');
session_set_cookie_params(14440); // 4 hours
session_start();
 
// ----------------------
// Global defined
// ----------------------
defined('SBMAGIC_PATH') or define('SBMAGIC_PATH', dirname(__FILE__));
defined('SBMAGIC_URL') or define('SBMAGIC_URL', $_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/');
defined('SBMAGIC_BASE') or define('SBMAGIC_BASE', basename(__FILE__));
defined('SBMAGIC_NAME') or define('SBMAGIC_NAME', 'SBMagic');
defined('SBMAGIC_ID') or define('SBMAGIC_ID', 'sbmagic');
// ----------------------

// ----------------------
// Global include
// ----------------------
include 'inc/sbmagic-header.php';
// ----------------------

// ----------------------
// Define Globals
// ----------------------
global $sbdebug, $sbsmarty, $sbsanitize, $sbusers, $sbform, $sbpage, $sbmedias;
// ----------------------

// ----------------------
// Check INSTALLATION
// ----------------------
$sbmagic_install_dir  = SBMAGIC_PATH . '/install.php';
$sbmagic_install_url  = SBMAGIC_URL . 'install.php';
$sbmagic_install_lock = SBMAGIC_PATH . '/install/installer/data/';
$sbmagic_htaccess     = SBMAGIC_PATH . '/htaccess';
$sbmagic_dot_htaccess = SBMAGIC_PATH . '/.htaccess';
// ----------------------
if (file_exists($sbmagic_install_dir)) {
	// --- Check if install is done or not
	if (!file_exists($sbmagic_install_lock . 'installer.lock')) {
		header("Status: 301 Moved Permanently", false, 301);
		header("Location: http://$sbmagic_install_url");
		exit();
	} else {
		// --- Warning Page Home Admin
		$sbsmarty->assign('sb_warning_install_file', true);
	}
}
if (file_exists($sbmagic_htaccess)) {
	// --- Rename htaccess TO .htaccess
	$sbmagic_copy_htaccess = @copy($sbmagic_htaccess, $sbmagic_dot_htaccess);
	if ($sbmagic_copy_htaccess) {
		// --- Access to Admin and remove install file
		@unlink($sbmagic_htaccess);
	}
}
if (is_dir(SBMAGIC_PATH . '/install')) {
	$sbsmarty->assign('sb_warning_installer_lock', true);
}
// ----------------------

// ----------------------
// --- CKEditor Behavior
// ----------------------
$query_ckeditor_behavior   = "SELECT content FROM " . _AM_DB_PREFIX . "sb_config WHERE config = 'toolbarck'";
$request_ckeditor_behavior = $sbsql->query($query_ckeditor_behavior);
$ckeditor_behavior         = $sbsql->assoc($request_ckeditor_behavior);
defined('SBMAGIC_CKEDITOR_BEHAVIOR') or define('SBMAGIC_CKEDITOR_BEHAVIOR', (trim($ckeditor_behavior['content']) == 1) ? true : false);

// ----------------------
// --- Settings file init
// ----------------------
$sb_link_settings  = file(_AM_SETTINGS_FILE);

// ----------------------
// Identification / Authentification
// ----------------------
// --- Initialization
$publickey  = $sbsanitize->sTrim($sb_link_settings[19]);
$privatekey = $sbsanitize->sTrim($sb_link_settings[20]);
$sbsmarty->assign('grecaptcha_publickey', $publickey);

// --- Random background
$sbsmarty->assign('sb_random_bg', rand(1, 10));

if (isset($_SESSION['sbmagic_user_name']) && isset($_SESSION['sbmagic_user_password'])) {
	// ------------------
	// --- SESSION Auth
	// ------------------
	// --- Check Session
	$sbmagic_user_name     = trim($sbsanitize->stopXSS($_SESSION['sbmagic_user_name']));
	$sbmagic_user_password = $_SESSION['sbmagic_user_password'];
	if ($sbusers->login($sbmagic_user_name, $sbmagic_user_password)) {
		if (!$sbusers->checkUserIsActive($sbmagic_user_name)) {
			// --- User is no more active
			$sbsmarty->assign('sbmagic_access_code', 'E4');
			$sbmagic_type = 'error';
			$sbmagic_event = sprintf(SBMAGIC_MSG_LOG_ACCESS_USER_ERROR, $sbmagic_user_name, $_SERVER["REMOTE_ADDR"]);
			$sbusers->updateAccessLog($sbmagic_type, $sbmagic_event, $sbmagic_user_name);
			$sbsmarty->display('system/login.tpl');
			exit;
		}
	}
}
if (($_POST['username'] && $_POST['password'])) {
	// ------------------
	// --- Form auth
	// ------------------
	$sbmagic_user_name     = trim($sbsanitize->stopXSS($_POST['username']));
	$sbmagic_user_password = trim($sbusers->encrypt($_POST['password']));
	if ($sbusers->login($sbmagic_user_name, $sbmagic_user_password)) {
		if (!$sbusers->checkUserIsActive($sbmagic_user_name)) {
			// --- User is no more active
			$sbsmarty->assign('sbmagic_access_code', 'E4');
			$sbmagic_type = 'error';
			$sbmagic_event = sprintf(SBMAGIC_MSG_LOG_ACCESS_USER_ERROR, $sbmagic_user_name, $_SERVER["REMOTE_ADDR"]);
			$sbusers->updateAccessLog($sbmagic_type, $sbmagic_event, $sbmagic_user_name);
			$sbsmarty->display('system/login.tpl');
			exit;
		} else {
			if (_AM_CAPTCHA_MODE == true) {
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
						$sbsmarty->assign('sbmagic_access_code', 'E1');
						$sbmagic_type = 'error';
						$sbmagic_event = sprintf(SBMAGIC_MSG_LOG_ACCESS_CAPTCHA_ERROR, $sbmagic_user_name, $_SERVER["REMOTE_ADDR"]);
						$sbusers->updateAccessLog($sbmagic_type, $sbmagic_event, $sbmagic_user_name);
						$sbsmarty->display('system/login.tpl');
						exit;						
					}
					
				} else {
					// --- Error Google Recaptcha
					$sbsmarty->assign('sbmagic_access_code', 'E1');
					$sbmagic_type = 'error';
					$sbmagic_event = sprintf(SBMAGIC_MSG_LOG_ACCESS_CAPTCHA_ERROR, $sbmagic_user_name, $_SERVER["REMOTE_ADDR"]);
					$sbusers->updateAccessLog($sbmagic_type, $sbmagic_event, $sbmagic_user_name);
					$sbsmarty->display('system/login.tpl');
					exit;
				}
			}
			// ------------------
			// --- Acces autorise
			// ------------------
			// Update Access Log
			$sbmagic_type = 'login';
			$sbmagic_event = sprintf(SBMAGIC_MSG_LOG_ACCESS_GRANTED, $sbmagic_user_name, $_SERVER["REMOTE_ADDR"]);
			$sbusers->updateAccessLog($sbmagic_type, $sbmagic_event, $sbmagic_user_name);
			// Update LoginTime
			$sbusers->updateAccessUserLogin($sbmagic_user_name, false, time());
			// Assign SESSION
			$_SESSION['sbmagic_user_name']     = $sbmagic_user_name;
			$_SESSION['sbmagic_user_password'] = $sbmagic_user_password;
			
		}
	} else {
		// ------------------
		// --- Failed auth
		// ------------------
		$sbsmarty->assign('sbmagic_access_code', 'E2');
		$sbmagic_type = 'error';
		$sbmagic_event = sprintf(SBMAGIC_MSG_LOG_ACCESS_NOGRANTED, $_SERVER["REMOTE_ADDR"]);
		$sbusers->updateAccessLog($sbmagic_type, $sbmagic_event);
		$sbsmarty->display('system/login.tpl');
		exit;
	}
}
if (!isset($_SESSION['sbmagic_user_name']) && !isset($_SESSION['sbmagic_user_password'])) {
	// ------------------
	// --- SESSION Auth
	// ------------------
	// --- No session
	$sbsmarty->assign('uiadmin_access_code', 'E3');
	$sbmagic_type = 'error';
	$sbmagic_event = sprintf(SBMAGIC_MSG_LOG_ACCESS_MISSING, $_SERVER["REMOTE_ADDR"]);
	$sbusers->updateAccessLog($sbmagic_type, $sbmagic_event);
	$sbsmarty->display('system/login.tpl');
	exit;
}
if ($_GET['ac'] == 'logout') {
	// ------------------
	// --- Logout required
	// ------------------
	// Update LastLogin
	$sbusers->updateAccessUserLogin($sbmagic_user_name, true);
	session_start();
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	session_regenerate_id(true);
	header("Location: " . _AM_SITE_URL);
}
// ----------------------
// Get Global Infos
// ----------------------
global $sbadministrators, $sb_admin_pages;
$sbmagic_user_type = (in_array(trim($_SESSION['sbmagic_user_name']), $sbadministrators)) ? 'admin' : 'user';
$sbsmarty->assign('sbmagic_user_name', $_SESSION['sbmagic_user_name']);
$sbsmarty->assign('sbmagic_user_type', $sbmagic_user_type);
$sbsmarty->assign('sbmagic_user_email', $sbusers->getUserInfo($_SESSION['sbmagic_user_name'], 'email'));
$sbsmarty->assign('sbmagic_user_last_login', date("d/m/Y h:i", $sbusers->getUserInfo($_SESSION['sbmagic_user_name'], 'lastlogin')));

// ----------------------
// Check if user ADMIN is always in DB
// ----------------------
if ($sbusers->getUserInfo('admin', 'username') == 'admin') {
	$sbsmarty->assign('sb_warning_admin_user', true);
}

// ----------------------
// Get Global Configuration
// ----------------------
// --- Link CUSTOMER WEBSITE
$sbsmarty->assign('sb_url_customer', trim($sb_link_settings[15]));
// --- Sandbox Activation
$sbsmarty->assign('sb_sandbox', trim($sb_link_settings[16]));
// --- CMS Activation
$sbsmarty->assign('sb_cms', trim($sb_link_settings[17]));

// ----------------------
// Get Main Menu
// ----------------------
$sb_main_menu_admin = sbGetMenuModule('admin');
$sbsmarty->assign('sb_main_menu_admin', $sb_main_menu_admin);
// ----------------------
$sb_main_menu = sbGetMenuModule('main');
$sbsmarty->assign('sb_main_menu', $sb_main_menu);
// ----------------------

// ----------------------
// Define Safe Pages
// ----------------------
// --- Get "p" in URL
$sb_get_page = (isset($_GET['p'])) ? $_GET['p'] : 'index';
// ----------------------

// --------------------------------
// --- Search for safe page
// --------------------------------
if (in_array($sb_get_page, $sb_safe_pages) || in_array($sb_get_page, $sb_safe_modules)) {
	// Check if module or system
	$sb_path_file_sys_mod = (file_exists("$sb_get_page.php")) ? '' : 'datas/modules/';
	
	// --- Control if PHP file exist
	if (file_exists("{$sb_path_file_sys_mod}{$sb_get_page}.php") && $sb_get_page != 'index') {
		// Yes, so include
		sb_global_include("{$sb_path_file_sys_mod}{$sb_get_page}.php");
	} else {
		// No, so show error message
		if (_AM_SITE_DEBUG && $sb_get_page != 'index') echo "Fichier php '{$sb_path_file_sys_mod}{$sb_get_page}' inexistant !";
	}
	// Display template page
	// Check if non admin and authorized page
	if (in_array($sb_get_page, $sb_admin_pages) && $sbmagic_user_type != 'admin')
		$sbsmarty->display("404.tpl");
	else {
		if ($sb_path_file_sys_mod == '') {
			// System
			if ($sb_get_page == 'index') {
				$sbsmarty->assign('module_page', $sb_get_page);
				// Traitement page DASHBOARD (INDEX ADMIN)
				$sb_dashboard_file = _AM_DASHBOARD_FILE;
				// --- Ouverture du fichier
				$sb_dashboard = file($sb_dashboard_file);
				// --- Initialisation
				$sbsmarty->assign('sb_dashboard_table', trim($sb_dashboard[0]));
				// --- Table 1
				$sbsmarty->assign('sb_dashboard_status1_table', trim($sb_dashboard[1]));
				$sbsmarty->assign('sb_dashboard_status1_title', trim($sb_dashboard[2]));
				$sbsmarty->assign('sb_dashboard_status1_link', trim($sb_dashboard[3]));
				$sbsmarty->assign('sb_dashboard_status1_icon', trim($sb_dashboard[4]));
				$sbsmarty->assign('sb_dashboard_status1_tbcol', trim($sb_dashboard[5]));
				$query[1] = "SELECT {$sb_dashboard[5]} FROM {$sb_dashboard[1]} ORDER BY {$sb_dashboard[5]} DESC";
				$request1  = $sbsql->query($query[1]);
				$result1   = $sbsql->toarray($request1);
				$sbsmarty->assign('sb_dashboard_status1_cpt', $sbsql->numrows());
				$sbsmarty->assign('sb_dashboard_status1_all', $result1);
				// --- Table 2
				$sbsmarty->assign('sb_dashboard_status2_table', trim($sb_dashboard[6]));
				$sbsmarty->assign('sb_dashboard_status2_title', trim($sb_dashboard[7]));
				$sbsmarty->assign('sb_dashboard_status2_link', trim($sb_dashboard[8]));
				$sbsmarty->assign('sb_dashboard_status2_icon', trim($sb_dashboard[9]));
				$sbsmarty->assign('sb_dashboard_status2_tbcol', trim($sb_dashboard[10]));
				$query[2] = "SELECT {$sb_dashboard[10]} FROM {$sb_dashboard[6]} ORDER BY {$sb_dashboard[10]} DESC";
				$request2  = $sbsql->query($query[2]);
				$result2   = $sbsql->toarray($request2);
				$sbsmarty->assign('sb_dashboard_status2_cpt', $sbsql->numrows());
				$sbsmarty->assign('sb_dashboard_status2_all', $result2);
				// --- Table 3
				$sbsmarty->assign('sb_dashboard_status3_table', trim($sb_dashboard[11]));
				$sbsmarty->assign('sb_dashboard_status3_title', trim($sb_dashboard[12]));
				$sbsmarty->assign('sb_dashboard_status3_link', trim($sb_dashboard[13]));
				$sbsmarty->assign('sb_dashboard_status3_icon', trim($sb_dashboard[14]));
				$sbsmarty->assign('sb_dashboard_status3_tbcol', trim($sb_dashboard[15]));
				$query[3] = "SELECT {$sb_dashboard[15]} FROM {$sb_dashboard[11]} ORDER BY {$sb_dashboard[15]} DESC";
				if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $sb_dashboard[15]);
				$request3  = $sbsql->query($query[3]);
				$result3   = $sbsql->toarray($request3);
				$sbsmarty->assign('sb_dashboard_status3_cpt', $sbsql->numrows());
				$sbsmarty->assign('sb_dashboard_status3_all', $result3);
				// --- Table 4
				$sbsmarty->assign('sb_dashboard_status4_table', trim($sb_dashboard[16]));
				$sbsmarty->assign('sb_dashboard_status4_title', trim($sb_dashboard[17]));
				$sbsmarty->assign('sb_dashboard_status4_link', trim($sb_dashboard[18]));
				$sbsmarty->assign('sb_dashboard_status4_icon', trim($sb_dashboard[19]));
				$sbsmarty->assign('sb_dashboard_status4_tbcol', trim($sb_dashboard[20]));
				$query[4] = "SELECT {$sb_dashboard[20]} FROM {$sb_dashboard[16]} ORDER BY {$sb_dashboard[20]} DESC";
				$request4  = $sbsql->query($query[4]);
				$result4   = $sbsql->toarray($request4);
				$sbsmarty->assign('sb_dashboard_status4_cpt', $sbsql->numrows());
				$sbsmarty->assign('sb_dashboard_status4_all', $result4);
				// --- Users (cpt)
				$query[5] = "SELECT id FROM " . _AM_DB_PREFIX . "sb_users";
				$request5  = $sbsql->query($query[5]);
				$result5   = $sbsql->numrows($request5);
				$sbsmarty->assign('sb_users_cpt', $result5);
				
			}
			$sbsmarty->display("system/$sb_get_page.tpl");
		} else {
			// Modules
			$sbsmarty->display("$sb_get_page.tpl");
		}
	}
} else {
	// --- Unsafe page
	$sbsmarty->display("404.tpl");
}
// --------------------------------
?>