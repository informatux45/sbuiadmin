<?php
/* ******************************* *
 * Header File                     *
 * ------------------------------- *
 * @link http://informatux.com/    *
 * @package SBUIADMIN              *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */

// ------------------------
// DEGUB Mode
// ------------------------
if (SBDEBUG) {
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	ini_set('display_errors', 1);
} else {
	error_reporting(0);
	ini_set('display_errors', 0);
}
 
// ------------------------ 
// Functions
// ------------------------
if (file_exists(SB_ADMIN_DIR . "inc" . DIRECTORY_SEPARATOR . "sbuiadmin-functions.php"))
	require_once(SB_ADMIN_DIR . "inc" . DIRECTORY_SEPARATOR . "sbuiadmin-functions.php");
 
// ------------------------
// Langage
// ------------------------
$sblang = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once(SB_PATH . "datas" . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . $sblang . ".php");

// ------------------------
// Template core config
// ------------------------
// --- Smarty Class
include(SB_SMARTY_DIR . 'Smarty.class.php');
$sbsmarty = new Smarty();

// ----------------------
// CLASSES by array
// ----------------------
$sbuiadmin_classes = array('sql', 'sanitize', 'users', 'page', 'flood');
foreach ($sbuiadmin_classes as $sbuiadmin_class) {
	$class_file = SB_ADMIN_DIR . "inc" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . SBUIADMINID . "-" . $sbuiadmin_class . ".php";
	if (file_exists($class_file))
	    include( $class_file );
}

$sbsql      = new sql();
$sbsanitize = new sanitize();
$sbusers    = new user();
$sbpage     = new page();
if (class_exists('Memcache') && extension_loaded('memcache') && function_exists('memcache_connect')) $sbflood = new flood();


// ----------------------
// Smarty Configuration
// ----------------------
$sbsmarty->setTemplateDir(array('theme' => SB_THEME_DIR . "tpls" . DIRECTORY_SEPARATOR
							   ,'modules' => SB_MODULES_DIR . DIRECTORY_SEPARATOR
						  ));
$sbsmarty->setCompileDir(SB_PATH . "datas" . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . "tpls_c" . DIRECTORY_SEPARATOR);
$sbsmarty->setConfigDir(SB_PATH . "datas" . DIRECTORY_SEPARATOR . "configs" . DIRECTORY_SEPARATOR);
$sbsmarty->setCacheDir(SB_PATH . "datas" . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR);
// ------------------
$sbsmarty->force_compile = SBSMARTYFORCECOMPILE;
// ------------------
$sbsmarty->debugging = SBSMARTYDEBUG;
// ------------------
$sbsmarty->caching = SBSMARTYCACHING;
$sbsmarty->cache_lifetime = SBSMARTYCACHELIFETIME;
// ------------------
$sbsmarty->error_reporting = E_ALL & ~E_NOTICE & ~E_WARNING;
// ------------------

// ------------------------ 
// Functions CMS
// ------------------------
$sb_functions_path = SB_PATH . "inc" . DIRECTORY_SEPARATOR . "functions.php";
if (file_exists($sb_functions_path)) require_once($sb_functions_path);

// ------------------------ 
// Custom Functions CMS
// ------------------------
$sb_custom_path = SB_PATH . "inc" . DIRECTORY_SEPARATOR . "cmscustom.php";
if (file_exists($sb_custom_path)) require_once($sb_custom_path);

// ----------------------
// Anti flood PROTECTION
// ----------------------
// --- Site installé : SBADMIN/install.php est toujours livré depuis 4.16 (simple
// --- redirection vers l'installeur), le tester désactivait ce contrôle partout
if (!function_exists('sbSiteInstalled') || sbSiteInstalled()) {
	// --- Check if Blocked IP
	$user_ip = sbGetUserIP();
	$is_ip_blocked = sbIsBlockedIP($user_ip);
	if (isset($is_ip_blocked) && $is_ip_blocked == $user_ip) sbForbiddenRedirect();
	// --- Check FLOOD : seulement si l'anti-flood est activé (Utilisateurs >
	// --- IP bloquées > Paramètres), comme à la connexion admin. Il tournait
	// --- dès que Memcache était présent : deux pages dans la même seconde
	// --- bloquaient l'IP du visiteur pour la durée réglée (24 h par défaut).
	if (sbSettingBool('flood_enabled') && isset($sbflood)) {
		$sbflood->floodCheck();
	}
}

// ------------------------
// Functions Files Module CMS : un module présent mais non installé n'est
// pas chargé, ses codes courts s'affichent vides (voir sbGetShortcode())
// ------------------------
foreach (sbModulesLoadable() as $sb_module_name) {
	$functions_path = SB_MODULES_DIR . $sb_module_name . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php';
	if (file_exists($functions_path))
		include_once($functions_path);
}
unset($sb_module_name);

// ------------------------
// Modificateurs Smarty (fonctions PHP déclarées, voir sbSmartyRegisterModifiers())
// ------------------------
if (function_exists('sbSmartyRegisterModifiers')) sbSmartyRegisterModifiers($sbsmarty);

?>
