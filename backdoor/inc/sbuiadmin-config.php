<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Configuration
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');

// ----------------------------------------
// Don't remove this setting              -
// ----------------------------------------
// --- Admin Settings File
defined('_AM_SETTINGS_FILE') OR define('_AM_SETTINGS_FILE', SBUIADMIN_PATH . '/inc/admin/settings.txt');
$sb_settings_config = file(_AM_SETTINGS_FILE);
// --- Admin Dashboard File
defined('_AM_DASHBOARD_FILE') OR define('_AM_DASHBOARD_FILE', SBUIADMIN_PATH . '/inc/admin/dashboard.txt');
// --- Front Theme File
defined('_AM_THEME_FILE') OR define('_AM_THEME_FILE', SBUIADMIN_PATH . '/inc/admin/theme.txt');
// ----------------------------------------
// ----------------------------------------
// ----------------------------------------

// ------------------------------------------
// --- Defined Safe Pages
$sb_safe_pages   = ['index','sandbox','settings','cache','server','dashboard','theme','themeinfos','session','database','explorer','users','logaccess','menu','pages','blocs','medias','transfert','cmsconfig','slider','news','contact','tabbs','toggle','gallery','gmaps','table','toolbarck'];
// --- Defined Safe Modules
$sb_safe_modules = explode(",", trim($sb_settings_config[8]));
// ------------------------------------------
// --- Debug
defined('_AM_SITE_DEBUG') OR define('_AM_SITE_DEBUG', (trim($sb_settings_config[9]) == 1) ? true : false);
defined('_AM_SITE_DEBUG_FORM') OR define('_AM_SITE_DEBUG_FORM', (trim($sb_settings_config[10]) == 1) ? true : false);
defined('_AM_SMARTY_DEBUGGING') OR define('_AM_SMARTY_DEBUGGING', (trim($sb_settings_config[11]) == 1) ? true : false);
// ------------------------------------------
// DEGUB Mode
if (_AM_SITE_DEBUG) {
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	ini_set('display_errors', 1);
} else {
	error_reporting(0);
	ini_set('display_errors', 0);
}
// ------------------------------------------
// CAPTCHA Mode
defined('_AM_CAPTCHA_MODE') OR define('_AM_CAPTCHA_MODE', (trim($sb_settings_config[22]) == 1) ? true : false);
// ------------------------------------------
// UPGRADE Mode
defined('_AM_UPGRADE_MODE') OR define('_AM_UPGRADE_MODE', (trim($sb_settings_config[23]) == 1) ? true : false);
// ------------------------------------------
// --- Smarty CONFIG
defined('_AM_SMARTY_FORCE_COMPILE') OR define('_AM_SMARTY_FORCE_COMPILE', true);
defined('_AM_SMARTY_CACHING') OR define('_AM_SMARTY_CACHING', false);
defined('_AM_SMARTY_CACHE_LIFETIME') OR define('_AM_SMARTY_CACHE_LIFETIME', 120);
defined('_AM_SMARTY_BACKWARDS_COMPATIBILITY') OR define('_AM_SMARTY_BACKWARDS_COMPATIBILITY', true); // Don't change this ;-)
// ------------------------------------------
// --- MySQL Config (Host Client)
// Search if there is a socket
if (strpos(trim($sb_settings_config[2]), ":") !== false) {
	// Define socket
	list($db_host, $db_socket) = explode(":", trim($sb_settings_config[2]));
	defined('_AM_DB_HOST') OR define('_AM_DB_HOST',	$db_host);
	defined('_AM_DB_SOCKET') OR define('_AM_DB_SOCKET',	$db_socket);
} else {
	// Pas de socket
	defined('_AM_DB_HOST') OR define('_AM_DB_HOST', trim($sb_settings_config[2]));
	defined('_AM_DB_SOCKET') OR define('_AM_DB_SOCKET', false);
}
defined('_AM_DB_PORT') OR define('_AM_DB_PORT', 3306);
defined('_AM_DB_NAME') OR define('_AM_DB_NAME', trim($sb_settings_config[3]));
defined('_AM_DB_USER') OR define('_AM_DB_USER', trim($sb_settings_config[4]));
defined('_AM_DB_PWD') OR define('_AM_DB_PWD', trim($sb_settings_config[5]));
defined('_AM_DB_PREFIX') OR define('_AM_DB_PREFIX', trim($sb_settings_config[21]));

// ------------------------------------------
// ---------------- MEDIAS ------------------
// ------------ MEDIAS UPLOADER -------------
// ------ Pour l'affichage des medias -------
// ------- Pour l'upload des medias ---------
// ------------------------------------------
// --- Scan multiple directories for all files, no sub-dirs
// --- Chemin relatif (obligatoirement), pas d'absolu !!!
// --- Ne pas mettre le "/" à la fin
$sbfiles_medias_dirs_allowed = trim($sb_settings_config[6]);
// --- Pour vos formulaires ;-)
defined('_AM_MEDIAS_DIR') OR define('_AM_MEDIAS_DIR', trim($sb_settings_config[6]));
defined('_AM_MEDIAS_URL') OR define('_AM_MEDIAS_URL', trim($sb_settings_config[13]));
// --- Array of allowed extensions
//$sbfiles_medias_exts_allowed = array("jpg","jpeg","bmp","png","pdf", "xml", "txt", "mp4");
$sbfiles_medias_exts_allowed = explode(",", trim($sb_settings_config[12]));
// --- Define item Limit (Multiple uploads simultaneously)
defined('_AM_MEDIAS_ITEM_LIMIT') OR define('_AM_MEDIAS_ITEM_LIMIT', trim($sb_settings_config[14]));
// --- Define size Limit for your customers
// Usage :
// ==> 10KB
// ==> 10.5KB
// ==> 2MB
// ==> 2.5MB
// ==> 1GB
// ==> 1TB
defined('_AM_MEDIAS_SIZE_LIMIT') OR define('_AM_MEDIAS_SIZE_LIMIT', trim($sb_settings_config[7]));
// --- Define scaling image max (Combined width AND height)
// unit of measuring: pixels
// Usage :
// ==> 1024
defined('_AM_MEDIAS_SCALING_SIXE_MAX') OR define('_AM_MEDIAS_SCALING_SIXE_MAX', trim($sb_settings_config[18]));
// ------------------------------------------

// ------------------------------------------
// --- Users identified like Adminitrators
// Administrators are allowed to access to:
// . Manage USERS
// . Manage DATABASE
// . Manage SETTINGS
$sbadministrators = explode(",", trim($sb_settings_config[1]));

// -----------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//                      DON'T CHANGE ANYTHING AFTER THIS LINE
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// -----------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------
// --- Protocol
defined('_AM_SITE_PROTOCOL') OR define('_AM_SITE_PROTOCOL', $_SERVER['REQUEST_SCHEME'] . '://');
// --- Smarty DIR
defined('_AM_SMARTY_DIR') OR define('_AM_SMARTY_DIR', SBUIADMIN_PATH .'/core/');
// --- Site DIR
defined('_AM_SITE_DIR') OR define('_AM_SITE_DIR', SBUIADMIN_PATH . '/');
// --- Site URL
defined('_AM_SITE_URL') OR define('_AM_SITE_URL', _AM_SITE_PROTOCOL . SBUIADMIN_URL);
// --- Site UPLOAD DIR
defined('_AM_SITE_IMG_DIR') OR define('_AM_SITE_IMG_DIR', SBUIADMIN_PATH . '/upload/');
// --- Site UPLOAD URL
defined('_AM_SITE_IMG_URL') OR define('_AM_SITE_IMG_URL',_AM_SITE_PROTOCOL . SBUIADMIN_URL . 'upload/');
// --- Site LANG / DIR / URL
defined('_AM_SITE_LANG') OR define('_AM_SITE_LANG', 'french');
defined('_AM_SITE_LANG_DIR') OR define('_AM_SITE_LANG_DIR', SBUIADMIN_PATH . '/lang/');
defined('_AM_SITE_LANG_URL') OR define('_AM_SITE_LANG_URL', _AM_SITE_PROTOCOL . SBUIADMIN_URL . 'lang/');
// --- Customer name
defined('_AM_SITE_CUSTOMER_NAME') OR define('_AM_SITE_CUSTOMER_NAME', trim($sb_settings_config[0]));
// ------------------------------------------
// --- Defined Safe Pages Admins Only
$sb_admin_pages = array('sandbox','settings','server','dashboard','theme','cache','toolbarck','database','explorer','users');
// --- Server Config
$sb_version_php = explode('-',PHP_VERSION);
defined('_AM_SERVER_PHP_VERSION_ID') OR define('_AM_SERVER_PHP_VERSION_ID', $sb_version_php[0]);

?>
