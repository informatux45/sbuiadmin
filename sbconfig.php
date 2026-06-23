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
$sb_settings_config = file($_sb_config_base . 'settings.txt');

if ($sb_theme_config === false || $sb_settings_config === false) {
    die('Configuration files not found or unreadable.');
}

// Helpers to safely read settings.txt values
function _sbcfg(array $cfg, int $i, string $default = ''): string {
    return isset($cfg[$i]) ? trim($cfg[$i]) : $default;
}
function _sbcfgbool(array $cfg, int $i): bool {
    return _sbcfg($cfg, $i) === '1';
}

// Named indices for settings.txt (no more magic numbers)
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
defined('SBDEBUG') OR define('SBDEBUG', _sbcfgbool($sb_settings_config, CFG_DEBUG));

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
defined('SBSMARTYFORCECOMPILE') OR define('SBSMARTYFORCECOMPILE', _sbcfgbool($sb_settings_config, CFG_SMARTY_FORCE));

# Enable caching smarty TPL
# Default: false
defined('SBSMARTYCACHING') OR define('SBSMARTYCACHING', _sbcfgbool($sb_settings_config, CFG_SMARTY_CACHING));

# Define lifetime of cache Smarty
# Only available if SMARTY CACHING is true
# Default: 120
defined('SBSMARTYCACHELIFETIME') OR define('SBSMARTYCACHELIFETIME', (int)(_sbcfg($sb_settings_config, CFG_SMARTY_CACHE_LIFE) ?: 120));

# Enable Smarty Debug
# Default: false
defined('SBSMARTYDEBUG') OR define('SBSMARTYDEBUG', _sbcfgbool($sb_settings_config, CFG_SMARTY_DEBUG));

# Enable access to classes/files/functions Admin
defined('SBUIADMIN_PATH') OR define('SBUIADMIN_PATH', true);

# Enable rewrite url
# Default: false
defined('SBREWRITEURL') OR define('SBREWRITEURL', _sbcfgbool($sb_settings_config, CFG_REWRITE_URL));

# Enable maintenance mode (Coming soon)
defined('SBMAINTENANCE') OR define('SBMAINTENANCE', _sbcfgbool($sb_settings_config, CFG_MAINTENANCE));

# Define Subdirectory Site
# if is visible in your url
# Default: false
# Ex: http://site.com/dir/
defined('SBSITESUBDIRECTORY') OR define('SBSITESUBDIRECTORY', '');

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
defined('_AM_SITE_TITLE') OR define('_AM_SITE_TITLE', _sbcfg($sb_settings_config, CFG_SITE_TITLE));

// Search if there is a socket
$_db_host_raw = _sbcfg($sb_settings_config, CFG_DB_HOST);
if (strpos($_db_host_raw, ':') !== false) {
    // Define socket
    [$db_host, $db_socket] = explode(':', $_db_host_raw, 2);
    defined('_AM_DB_HOST')   OR define('_AM_DB_HOST',   $db_host);
    defined('_AM_DB_SOCKET') OR define('_AM_DB_SOCKET', $db_socket);
} else {
    // No socket
    defined('_AM_DB_HOST')   OR define('_AM_DB_HOST',   $_db_host_raw);
    defined('_AM_DB_SOCKET') OR define('_AM_DB_SOCKET', false);
}

defined('_AM_DB_PORT')    OR define('_AM_DB_PORT',    3306);
defined('_AM_DB_NAME')    OR define('_AM_DB_NAME',    _sbcfg($sb_settings_config, CFG_DB_NAME));
defined('_AM_DB_USER')    OR define('_AM_DB_USER',    _sbcfg($sb_settings_config, CFG_DB_USER));
defined('_AM_DB_PWD')     OR define('_AM_DB_PWD',     _sbcfg($sb_settings_config, CFG_DB_PWD));
defined('_AM_MEDIAS_DIR') OR define('_AM_MEDIAS_DIR', _sbcfg($sb_settings_config, CFG_MEDIAS_DIR));
defined('_AM_MEDIAS_URL') OR define('_AM_MEDIAS_URL', _sbcfg($sb_settings_config, CFG_MEDIAS_URL));
defined('_AM_GC_PUBLIC')  OR define('_AM_GC_PUBLIC',  _sbcfg($sb_settings_config, CFG_GC_PUBLIC));
defined('_AM_GC_PRIVATE') OR define('_AM_GC_PRIVATE', _sbcfg($sb_settings_config, CFG_GC_PRIVATE));
defined('_AM_DB_PREFIX')  OR define('_AM_DB_PREFIX',  _sbcfg($sb_settings_config, CFG_DB_PREFIX));

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
defined('SB_URL')  OR define('SB_URL',  _sbcfg($sb_settings_config, CFG_SITE_URL));

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

unset($_sb_config_base, $_db_host_raw, $_sb_https, $_sb_protocol);
