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
// Ancien fichier positionnel : vidé une fois migré en base (gardé pour la
// migration automatique, voir inc/sbuiadmin-settings.php)
defined('_AM_SETTINGS_FILE') OR define('_AM_SETTINGS_FILE', SBUIADMIN_PATH . '/inc/admin/settings.txt');
// --- Réglages : table sb_config, accès base dans sbdbconfig.php
require_once(__DIR__ . '/sbuiadmin-phpaudit.php'); // inactif sans inc/admin/php-audit.txt
require_once(__DIR__ . '/sbuiadmin-settings.php');
// --- Anti-robot ALTCHA et blocage des tentatives de connexion
require_once(__DIR__ . '/sbuiadmin-altcha.php');
require_once(__DIR__ . '/sbuiadmin-loginlock.php');
// Ancien tableau positionnel, pour le code tiers qui le lirait encore
$sb_settings_config = sbSettingsLegacyArray();
// --- Admin Dashboard File
defined('_AM_DASHBOARD_FILE') OR define('_AM_DASHBOARD_FILE', SBUIADMIN_PATH . '/inc/admin/dashboard.txt');
// --- Front Theme File
defined('_AM_THEME_FILE') OR define('_AM_THEME_FILE', SBUIADMIN_PATH . '/inc/admin/theme.txt');
// ----------------------------------------
// ----------------------------------------
// ----------------------------------------

// ------------------------------------------
// --- Defined Safe Pages
$sb_safe_pages = ['index','sandbox','settings','cache','server','dashboard','theme','themeinfos','session','users','logaccess','menu','pages','blocs','medias','transfert','cmsconfig','slider','news','contact','tabbs','toggle','download','gallery','gmaps','table','toolbarck','faq','messages','profile','boutique'];
// --- Defined Safe Modules
$sb_safe_modules = explode(",", sbSetting('modules'));
// ------------------------------------------
// --- Debug
defined('_AM_SITE_DEBUG') OR define('_AM_SITE_DEBUG', (sbSetting('debug_admin') == 1) ? true : false);
defined('_AM_SITE_DEBUG_FORM') OR define('_AM_SITE_DEBUG_FORM', (sbSetting('debug_form') == 1) ? true : false);
defined('_AM_SMARTY_DEBUGGING') OR define('_AM_SMARTY_DEBUGGING', (sbSetting('debug_smarty_admin') == 1) ? true : false);
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
// ALTCHA à la connexion (administration et module user)
defined('_AM_ALTCHA_LOGIN') OR define('_AM_ALTCHA_LOGIN', sbSetting('altcha_login', '1') === '1');
// ------------------------------------------
// UPGRADE Mode
defined('_AM_UPGRADE_MODE') OR define('_AM_UPGRADE_MODE', (sbSetting('upgrade_mode') == 1) ? true : false);
// ------------------------------------------
// --- Smarty CONFIG
defined('_AM_SMARTY_FORCE_COMPILE') OR define('_AM_SMARTY_FORCE_COMPILE', true);
defined('_AM_SMARTY_CACHING') OR define('_AM_SMARTY_CACHING', false);
defined('_AM_SMARTY_CACHE_LIFETIME') OR define('_AM_SMARTY_CACHE_LIFETIME', 120);
// ------------------------------------------
// --- MySQL Config (Host Client)
// sbdbconfig.php (ou variables d'environnement SBUIADMIN_DB_*)
$sb_db_config = sbDbConfig();
list($sb_db_host, $sb_db_port, $sb_db_socket) = sbDbHostParts($sb_db_config['host']);
defined('_AM_DB_HOST') OR define('_AM_DB_HOST', $sb_db_host);
defined('_AM_DB_SOCKET') OR define('_AM_DB_SOCKET', $sb_db_socket);
defined('_AM_DB_PORT') OR define('_AM_DB_PORT', $sb_db_port);
defined('_AM_DB_NAME') OR define('_AM_DB_NAME', $sb_db_config['name']);
defined('_AM_DB_USER') OR define('_AM_DB_USER', $sb_db_config['user']);
defined('_AM_DB_PWD') OR define('_AM_DB_PWD', $sb_db_config['password']);
defined('_AM_DB_PREFIX') OR define('_AM_DB_PREFIX', $sb_db_config['prefix']);
unset($sb_db_config, $sb_db_host, $sb_db_port, $sb_db_socket);

// ------------------------------------------
// ---------------- MEDIAS ------------------
// ------------ MEDIAS UPLOADER -------------
// ------ Pour l'affichage des medias -------
// ------- Pour l'upload des medias ---------
// ------------------------------------------
// --- Scan multiple directories for all files, no sub-dirs
// --- Chemin relatif (obligatoirement), pas d'absolu !!!
// --- Ne pas mettre le "/" à la fin
$sbfiles_medias_dirs_allowed = sbSetting('medias_dir');
// --- Pour vos formulaires ;-)
defined('_AM_MEDIAS_DIR') OR define('_AM_MEDIAS_DIR', sbSetting('medias_dir'));
defined('_AM_MEDIAS_URL') OR define('_AM_MEDIAS_URL', sbSetting('medias_url'));
// --- Array of allowed extensions
//$sbfiles_medias_exts_allowed = array("jpg","jpeg","bmp","png","pdf", "xml", "txt", "mp4");
// --- Le réglage ne peut que restreindre cette liste sûre : c'est elle que
// --- server/php/sbUploadServer.php applique, l'encart des médias et Fine
// --- Uploader affichent donc exactement ce que le serveur accepte.
$sbfiles_medias_exts_safe    = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'mp4', 'm4v', 'zip', 'gz');
$sbfiles_medias_exts_setting = array_values(array_unique(array_filter(array_map(function ($e) { return strtolower(trim($e)); }, explode(",", sbSetting('upload_exts'))), 'strlen')));
$sbfiles_medias_exts_allowed = array_values(array_intersect($sbfiles_medias_exts_setting, $sbfiles_medias_exts_safe));
$sbfiles_medias_exts_refused = array_values(array_diff($sbfiles_medias_exts_setting, $sbfiles_medias_exts_safe));
// --- Define item Limit (Multiple uploads simultaneously)
defined('_AM_MEDIAS_ITEM_LIMIT') OR define('_AM_MEDIAS_ITEM_LIMIT', sbSetting('upload_item_limit'));
// --- Define size Limit for your customers
// Usage :
// ==> 10KB
// ==> 10.5KB
// ==> 2MB
// ==> 2.5MB
// ==> 1GB
// ==> 1TB
defined('_AM_MEDIAS_SIZE_LIMIT') OR define('_AM_MEDIAS_SIZE_LIMIT', sbSetting('upload_size_limit'));
// --- Define scaling image max (Combined width AND height)
// unit of measuring: pixels
// Usage :
// ==> 1024
defined('_AM_MEDIAS_SCALING_SIXE_MAX') OR define('_AM_MEDIAS_SCALING_SIXE_MAX', sbSetting('scaling_maxsize'));
// --- Define number of media items shown per page (Medias listing pagination)
defined('_AM_MEDIAS_PER_PAGE') OR define('_AM_MEDIAS_PER_PAGE', sbSetting('medias_per_page'));
// ------------------------------------------

// ------------------------------------------
// ------- Anti-flood (login) ---------------
// ------------------------------------------
// --- Master switch: kept OFF by default the first time this ships, since
// it's brand new and depends on Memcache being reachable - turn on from
// Utilisateurs > IP(s) bloquée(s) > Paramètres IP bloquées once verified.
defined('_AM_FLOOD_ENABLED') OR define('_AM_FLOOD_ENABLED', sbSetting('flood_enabled') == 1);
// --- How long a blocked IP stays blocked (seconds)
defined('_AM_FLOOD_EXPIRATION') OR define('_AM_FLOOD_EXPIRATION', (int)sbSetting('flood_expiration'));
// --- Minimum delay allowed between two login attempts from the same IP (seconds)
defined('_AM_FLOOD_LOGIN_DELAY') OR define('_AM_FLOOD_LOGIN_DELAY', (int)sbSetting('flood_login_delay'));
// ------------------------------------------

// ------------------------------------------
// --- Users identified like Adminitrators
// Administrators are allowed to access to:
// . Manage USERS
// . Manage DATABASE
// . Manage SETTINGS
$sbadministrators = explode(",", sbSetting('administrators'));

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
defined('_AM_SITE_IMG_DIR') OR define('_AM_SITE_IMG_DIR', SBUIADMIN_PATH . '/img/');
// --- Site UPLOAD URL
defined('_AM_SITE_IMG_URL') OR define('_AM_SITE_IMG_URL',_AM_SITE_PROTOCOL . SBUIADMIN_URL . 'img/');
// --- Avatars utilisateurs : sous-dossier "avatars" de la Médiathèque
// existante (_AM_MEDIAS_DIR = "../upload", réglage medias_dir),
// choisi via l'input photo standard (addInput('text', ..., icon=>'photo',
// medias=>'', subdir=>'avatars')) plutôt qu'un upload maison.
defined('_AM_AVATARS_DIR') OR define('_AM_AVATARS_DIR', SBUIADMIN_PATH . '/../upload/avatars/');
defined('_AM_AVATARS_URL') OR define('_AM_AVATARS_URL', '../upload/avatars');
// --- Site LANG / DIR / URL
defined('_AM_SITE_LANG') OR define('_AM_SITE_LANG', 'french');
defined('_AM_SITE_LANG_DIR') OR define('_AM_SITE_LANG_DIR', SBUIADMIN_PATH . '/lang/');
defined('_AM_SITE_LANG_URL') OR define('_AM_SITE_LANG_URL', _AM_SITE_PROTOCOL . SBUIADMIN_URL . 'lang/');
// --- Customer name
defined('_AM_SITE_CUSTOMER_NAME') OR define('_AM_SITE_CUSTOMER_NAME', sbSetting('customer_name'));
// ------------------------------------------
// --- Defined Safe Pages Admins Only
$sb_admin_pages = array('sandbox','settings','server','dashboard','theme','cache','toolbarck','users');
// --- Server Config
$sb_version_php = explode('-',PHP_VERSION);
defined('_AM_SERVER_PHP_VERSION_ID') OR define('_AM_SERVER_PHP_VERSION_ID', $sb_version_php[0]);

?>
