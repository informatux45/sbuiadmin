<?php
/* ******************************* *
 * Configuration File              *
 * ------------------------------- *
 * @link http://informatux.com/    *
 * @package SBUIADMIN              *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */

/** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) === 'sbconfig.php') {
    die('You cannot load this page directly.');
}

/*****************************************************************************/
/** Below are constants that you can use to customize how SBUIADMIN operates */
/*****************************************************************************/

# Change the administrative panel folder name
# Don't miss to change the htaccess file in administration
defined('SBADMIN') OR define('SBADMIN', 'backdoor');

# Get files configuration (theme / general)
$_sb_config_base    = dirname(__FILE__) . DIRECTORY_SEPARATOR . SBADMIN . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
$sb_theme_config    = file($_sb_config_base . 'theme.txt');

if ($sb_theme_config === false) {
    die('Configuration files not found or unreadable.');
}

# Réglages : en base (table sb_settings), accès base dans sbdbconfig.php.
# Voir SBADMIN/inc/sbuiadmin-settings.php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . SBADMIN . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'sbuiadmin-settings.php');
// Ancien tableau positionnel, pour le code tiers qui le lirait encore
$sb_settings_config = sbSettingsLegacyArray();

// Helpers to safely read positional config files (theme.txt)
function _sbcfg(array $cfg, int $i, string $default = ''): string {
    return isset($cfg[$i]) ? trim($cfg[$i]) : $default;
}
function _sbcfgbool(array $cfg, int $i): bool {
    return _sbcfg($cfg, $i) === '1';
}

// Anciennes positions de settings.txt (réglages désormais en base, voir sbSetting())
const CFG_SITE_TITLE        = 0;
const CFG_DB_HOST           = 2;
const CFG_DB_NAME           = 3;
const CFG_DB_USER           = 4;
const CFG_DB_PWD            = 5;
const CFG_MEDIAS_DIR        = 6;
const CFG_MEDIAS_URL        = 13;
const CFG_SITE_URL          = 15;
const CFG_GC_PUBLIC         = 19;
const CFG_GC_PRIVATE        = 20;
const CFG_DB_PREFIX         = 21;
const CFG_MAINTENANCE       = 24;
const CFG_DEBUG             = 25;
const CFG_SMARTY_DEBUG      = 26;
const CFG_SMARTY_FORCE      = 27;
const CFG_REWRITE_URL       = 28;
const CFG_SMARTY_CACHING    = 29;
const CFG_SMARTY_CACHE_LIFE = 30;

# Default max width of images
defined('SBIMAGEWIDTH') OR define('SBIMAGEWIDTH', 1024);

# Define SBUIADMIN ID Files
defined('SBUIADMINID') OR define('SBUIADMINID', 'sbuiadmin');

# Turn on debug mode
# Default: false
defined('SBDEBUG') OR define('SBDEBUG', sbSettingBool('debug_front'));

# Language (default fr_FR)
defined('SBLANG')      OR define('SBLANG',      'fr_FR');
defined('SBLANG_CODE') OR define('SBLANG_CODE', 'UTF-8');
defined('SBLANG_REST') OR define('SBLANG_REST', 'fra');

# Set PHP locale
# http://php.net/manual/en/function.setlocale.php
# Ex: setlocale(LC_ALL, 'fr_FR.UTF-8', 'fra');
setlocale(LC_ALL, SBLANG . '.' . SBLANG_CODE, SBLANG_REST);

# Define default timezone of server, accepts php timezone string
# valid timezones can be found here http://www.php.net/manual/en/timezones.php
defined('SBTIMEZONE') OR define('SBTIMEZONE', 'Europe/Paris');
date_default_timezone_set(SBTIMEZONE);

# Set email from address
defined('SBFROMEMAIL') OR define('SBFROMEMAIL', 'noreply@mysite.fr');

# Theme directory
defined('SBTHEME') OR define('SBTHEME', _sbcfg($sb_theme_config, 0));

# Module activated onto index page
# False, if you don't have module for index page
# Overriden by module page if a homepage is created
defined('SBMODULEINDEX') OR define('SBMODULEINDEX', false);

# Backwards Compatibility Wrapper (Smarty)
defined('SBSMARTYBC') OR define('SBSMARTYBC', true);

# Define force compile TPL Smarty
# Don't let this option to TRUE in production
# Default: true
defined('SBSMARTYFORCECOMPILE') OR define('SBSMARTYFORCECOMPILE', sbSettingBool('smarty_force_compile'));

# Enable caching smarty TPL
# Default: false
defined('SBSMARTYCACHING') OR define('SBSMARTYCACHING', sbSettingBool('smarty_caching'));

# Define lifetime of cache Smarty
# Only available if SMARTY CACHING is true
# Default: 120
defined('SBSMARTYCACHELIFETIME') OR define('SBSMARTYCACHELIFETIME', (int)(sbSetting('smarty_cache_lifetime') ?: 120));

# Enable Smarty Debug
# Default: false
defined('SBSMARTYDEBUG') OR define('SBSMARTYDEBUG', sbSettingBool('debug_smarty_front'));

# Enable access to classes/files/functions Admin
defined('SBUIADMIN_PATH') OR define('SBUIADMIN_PATH', true);

# Enable rewrite url
# Default: false
defined('SBREWRITEURL') OR define('SBREWRITEURL', sbSettingBool('rewrite_url'));

# Enable maintenance mode (Coming soon)
defined('SBMAINTENANCE') OR define('SBMAINTENANCE', sbSettingBool('maintenance'));

# Define Subdirectory Site
# if is visible in your url
# Default: auto-détecté depuis l'URL du site (réglage site_url)
# Ex: http://site.com/dir/ => 'dir'
defined('SBSITESUBDIRECTORY') OR define('SBSITESUBDIRECTORY', trim((string) parse_url(sbSetting('site_url'), PHP_URL_PATH), '/'));

# Defined Safe Modules created by you (developer)
//$sb_safe_modules_cms = ['your_new_module','your_new_module2'];

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//                      DON'T CHANGE ANYTHING AFTER THIS LINE
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

// ------------------------
// --- Defined Safe Pages
// ------------------------
$sb_safe_pages_cms = ['index', 'user', 'news', 'pages', 'shop', 'account', 'download', 'gallery', 'search'];

// ------------------------
// --- Database
// ------------------------
defined('_AM_SITE_TITLE') OR define('_AM_SITE_TITLE', sbSetting('customer_name'));

// Accès base : sbdbconfig.php (ou variables d'environnement SBUIADMIN_DB_*)
$_sb_db = sbDbConfig();
[$_db_host, $_db_port, $_db_socket] = sbDbHostParts($_sb_db['host']);
defined('_AM_DB_HOST')   OR define('_AM_DB_HOST',   $_db_host);
defined('_AM_DB_SOCKET') OR define('_AM_DB_SOCKET', $_db_socket);
defined('_AM_DB_PORT')   OR define('_AM_DB_PORT',   $_db_port);
defined('_AM_DB_NAME')   OR define('_AM_DB_NAME',   $_sb_db['name']);
defined('_AM_DB_USER')   OR define('_AM_DB_USER',   $_sb_db['user']);
defined('_AM_DB_PWD')    OR define('_AM_DB_PWD',    $_sb_db['password']);
defined('_AM_MEDIAS_DIR') OR define('_AM_MEDIAS_DIR', sbSetting('medias_dir'));
defined('_AM_MEDIAS_URL') OR define('_AM_MEDIAS_URL', sbSetting('medias_url'));
defined('_AM_GC_PUBLIC')  OR define('_AM_GC_PUBLIC',  sbSetting('recaptcha_public'));
defined('_AM_GC_PRIVATE') OR define('_AM_GC_PRIVATE', sbSetting('recaptcha_secret'));
defined('_AM_DB_PREFIX')  OR define('_AM_DB_PREFIX',  $_sb_db['prefix']);

// ------------------------
// --- Protocol (reverse proxy / CLI compatible)
// ------------------------
$_sb_https    = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
$_sb_protocol = $_sb_https ? 'https' : 'http';
defined('SB_PROTOCOL') OR define('SB_PROTOCOL', $_sb_protocol . '://');

// ------------------------
// --- Globals
// ------------------------
defined('SB_DEFAULT_PROTOCOL') OR define('SB_DEFAULT_PROTOCOL', SB_PROTOCOL);
defined('SB_PATH') OR define('SB_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
defined('SB_BASE') OR define('SB_BASE', basename(__FILE__));
defined('SB_URL')  OR define('SB_URL',  sbSetting('site_url'));

// ------------------------
// --- Theme
// ------------------------
defined('SB_THEME_URL') OR define('SB_THEME_URL', SB_URL . 'theme/' . SBTHEME . '/');
defined('SB_THEME_DIR') OR define('SB_THEME_DIR', SB_PATH . 'theme' . DIRECTORY_SEPARATOR . SBTHEME . DIRECTORY_SEPARATOR);

// ------------------------
// --- Modules
// ------------------------
defined('SB_MODULES_URL') OR define('SB_MODULES_URL', SB_URL . 'datas/modules/');
defined('SB_MODULES_DIR') OR define('SB_MODULES_DIR', SB_PATH . 'datas' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR);

// ------------------------
// --- Various HTML Content
// ------------------------
defined('SB_VARIOUS_URL') OR define('SB_VARIOUS_URL', SB_THEME_URL . 'inc/');
defined('SB_VARIOUS_DIR') OR define('SB_VARIOUS_DIR', SB_THEME_DIR . 'inc' . DIRECTORY_SEPARATOR);

// ------------------------
// --- Administration
// ------------------------
defined('SB_ADMIN_URL') OR define('SB_ADMIN_URL', SB_URL . SBADMIN . '/');
defined('SB_ADMIN_DIR') OR define('SB_ADMIN_DIR', SB_PATH . SBADMIN . DIRECTORY_SEPARATOR);

// ------------------------
// --- Smarty (Core)
// ------------------------
defined('SB_SMARTY_DIR') OR define('SB_SMARTY_DIR', SB_ADMIN_DIR . 'core' . DIRECTORY_SEPARATOR);

// ------------------------
// --- Settings (Admin)
// ------------------------
defined('SB_SETTINGS_FILE') OR define('SB_SETTINGS_FILE', SB_ADMIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'settings.txt');

unset($_sb_config_base, $_sb_db, $_db_host, $_db_port, $_db_socket, $_sb_https, $_sb_protocol);
