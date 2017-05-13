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
if (basename($_SERVER['PHP_SELF']) == 'sbconfig.php') { 
	die('You cannot load this page directly.');
}; 

/***************************************************************************/
/** Below are constants that you can use to customize how SBMAGIC operates */ 
/***************************************************************************/

# Default max width of images
define('SBIMAGEWIDTH', '1024');

# Change the administrative panel folder name
# Don't miss to change the htaccess file in administration
define('SBADMIN', 'backdoor');

# Define SBMAGIC ID Files
define('SBMAGICID', 'sbuiadmin');

# Turn on debug mode
define('SBDEBUG', true);

# Language (default fr_FR)
define('SBLANG', 'fr_FR');
	
# Set PHP locale
# http://php.net/manual/en/function.setlocale.php
setlocale(LC_ALL, SBLANG);

# Define default timezone of server, accepts php timezone string
# valid timeszones can be found here http://www.php.net/manual/en/timezones.php
define('SBTIMEZONE', 'Europe/Paris');
date_default_timezone_set(SBTIMEZONE);

# Set email from address
define('SBFROMEMAIL', 'noreply@mysite.fr');

# Theme directory
$sb_theme_config = file(dirname(__FILE__) . DIRECTORY_SEPARATOR . SBADMIN . DIRECTORY_SEPARATOR .'inc' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'theme.txt');
define('SBTHEME', trim($sb_theme_config[0]));

# Module activated onto index page
# False, if you don't have module for index page
# Overriden by module page if a homepage is created
define('SBMODULEINDEX', false);

# Backwards Compatibility Wrapper (Smarty)
define('SBSMARTYBC', true);

# Define force compile TPL Smarty
# Don't let this option to TRUE in production
define('SBSMARTYFORCECOMPILE', true);

# Enable caching smarty TPL
define('SBSMARTYCACHING', false);

# Define lifetime of cache Smarty
# Only available if CACHING smarty is true
define('SBSMARTYCACHELIFETIME', 120);

# Enable Smarty Debug
define('SBSMARTYDEBUG', false);

# Enable access to classes/files/functions Admin
define('SBMAGIC_PATH', true);

# Enable rewrite url
define('SBREWRITEURL', false);

# Enable maintenance mode (Coming soon)
define('SBMAINTENANCE', true);

# Define Subdirectory Site
# if is visible in your url
# Default: false
# Ex: http://site.com/dir/
define('SBSITESUBDIRECTORY', ''); // Obsolete !

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
$sb_safe_pages_cms   = ['index','user','news','pages'];

// ------------------------ 
// --- Database
// ------------------------ 
$sb_settings_config = file(dirname(__FILE__) . DIRECTORY_SEPARATOR . SBADMIN . DIRECTORY_SEPARATOR .'inc' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'settings.txt');
define('_AM_SITE_TITLE',	trim($sb_settings_config[0]));
define('_AM_DB_HOST',		trim($sb_settings_config[2]));
define('_AM_DB_NAME',		trim($sb_settings_config[3]));
define('_AM_DB_USER',		trim($sb_settings_config[4]));
define('_AM_DB_PWD',		trim($sb_settings_config[5]));
define('_AM_MEDIAS_DIR',	trim($sb_settings_config[6]));
define('_AM_MEDIAS_URL',	trim($sb_settings_config[13]));
define('_AM_DB_PREFIX',		trim($sb_settings_config[21]));

// ------------------------ 
// --- Protocol
// ------------------------ 
defined('SB_PROTOCOL') OR define('SB_PROTOCOL', $_SERVER['REQUEST_SCHEME'] . '://');

// ------------------------ 
// --- Globals
// ------------------------ 
defined('SB_DEFAULT_PROTOCOL') OR define('SB_DEFAULT_PROTOCOL', SB_PROTOCOL);
defined('SB_PATH') OR define('SB_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
defined('SB_BASE') OR define('SB_BASE', basename(__FILE__));
defined('SB_URL') OR define('SB_URL', trim($sb_settings_config[15]));

// ------------------------ 
// --- Theme
// ------------------------ 
defined('SB_THEME_URL') OR define('SB_THEME_URL', SB_URL . "theme" . DIRECTORY_SEPARATOR . SBTHEME . DIRECTORY_SEPARATOR );
defined('SB_THEME_DIR') OR define('SB_THEME_DIR', SB_PATH . "theme" . DIRECTORY_SEPARATOR . SBTHEME . DIRECTORY_SEPARATOR );

// ------------------------ 
// --- Modules
// ------------------------ 
defined('SB_MODULES_URL') OR define('SB_MODULES_URL', SB_URL . "datas" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR );
defined('SB_MODULES_DIR') OR define('SB_MODULES_DIR', SB_PATH . "datas" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR );

// ------------------------ 
// --- Administration
// ------------------------ 
defined('SB_ADMIN_URL') OR define('SB_ADMIN_URL', SB_URL . SBADMIN . DIRECTORY_SEPARATOR );
defined('SB_ADMIN_DIR') OR define('SB_ADMIN_DIR', SB_PATH . SBADMIN . DIRECTORY_SEPARATOR );

// ------------------------ 
// --- Smarty (Core)
// ------------------------ 
defined('SB_SMARTY_DIR') OR define('SB_SMARTY_DIR', SB_ADMIN_DIR . "core" . DIRECTORY_SEPARATOR );

// ------------------------ 
// --- Settings (Admin)
// ------------------------ 
defined('SB_SETTINGS_FILE') OR define('SB_SETTINGS_FILE', SB_ADMIN_DIR . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "settings.txt");

?>