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

/*****************************************************************************/
/** Below are constants that you can use to customize how SBUIADMIN operates */ 
/*****************************************************************************/

# Change the administrative panel folder name
# Don't miss to change the htaccess file in administration
defined ('SBADMIN') OR define('SBADMIN', 'backdoor');

# Get files configuration (theme / general)
$sb_theme_config    = file(dirname(__FILE__) . DIRECTORY_SEPARATOR . SBADMIN . DIRECTORY_SEPARATOR .'inc' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'theme.txt');
$sb_settings_config = file(dirname(__FILE__) . DIRECTORY_SEPARATOR . SBADMIN . DIRECTORY_SEPARATOR .'inc' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'settings.txt');

# Default max width of images
defined ('SBIMAGEWIDTH') OR define('SBIMAGEWIDTH', '1024');

# Define SBUIADMIN ID Files
defined ('SBUIADMINID') OR define('SBUIADMINID', 'sbuiadmin');

# Turn on debug mode
# Default: false
defined ('SBDEBUG') OR define('SBDEBUG', (trim($sb_settings_config[25]) == 1 ? true : false));

# Language (default fr_FR)
defined ('SBLANG') OR define('SBLANG', 'fr_FR');
defined ('SBLANG_CODE') OR define('SBLANG_CODE', 'UTF-8');
defined ('SBLANG_REST') OR define('SBLANG_REST', 'fra');
	
# Set PHP locale
# http://php.net/manual/en/function.setlocale.php
# Ex: setlocale(LC_ALL, 'fr_FR.UTF-8', 'fra');
setlocale(LC_ALL, SBLANG.".".SBLANG_CODE, SBLANG_REST);

# Define default timezone of server, accepts php timezone string
# valid timeszones can be found here http://www.php.net/manual/en/timezones.php
defined ('SBTIMEZONE') OR define('SBTIMEZONE', 'Europe/Paris');
date_default_timezone_set(SBTIMEZONE);

# Set email from address
defined ('SBFROMEMAIL') OR define('SBFROMEMAIL', 'noreply@mysite.fr');

# Theme directory
defined ('SBTHEME') OR define('SBTHEME', trim($sb_theme_config[0]));

# Module activated onto index page
# False, if you don't have module for index page
# Overriden by module page if a homepage is created
defined('SBMODULEINDEX') OR define('SBMODULEINDEX', false);

# Backwards Compatibility Wrapper (Smarty)
defined('SBSMARTYBC') OR define('SBSMARTYBC', true);

# Define force compile TPL Smarty
# Don't let this option to TRUE in production
# Default: true
defined('SBSMARTYFORCECOMPILE') OR define('SBSMARTYFORCECOMPILE', (trim($sb_settings_config[27]) == 1 ? true : false));

# Enable caching smarty TPL
# Default: false
defined('SBSMARTYCACHING') OR define('SBSMARTYCACHING', (trim($sb_settings_config[29]) == 1 ? true : false));

# Define lifetime of cache Smarty
# Only available if SMARTY CACHING is true
# Default: 120
defined('SBSMARTYCACHELIFETIME') OR define('SBSMARTYCACHELIFETIME', (isset($sb_settings_config[30]) && trim($sb_settings_config[30]) > 0 ? trim($sb_settings_config[30]) : 120));

# Enable Smarty Debug
# Default: false
defined('SBSMARTYDEBUG') OR define('SBSMARTYDEBUG', (trim($sb_settings_config[26]) == 1 ? true : false));

# Enable access to classes/files/functions Admin
defined('SBUIADMIN_PATH') OR define('SBUIADMIN_PATH', true);

# Enable rewrite url
# Default: false
defined('SBREWRITEURL') OR define('SBREWRITEURL', (trim($sb_settings_config[28]) == 1 ? true : false));

# Enable maintenance mode (Coming soon)
defined('SBMAINTENANCE') OR define('SBMAINTENANCE', (trim($sb_settings_config[24]) == 1 ? true : false));

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
$sb_safe_pages_cms = ['index','user','news','pages','shop','account','download','gallery','search'];

// ------------------------ 
// --- Database
// ------------------------ 
defined('_AM_SITE_TITLE') OR define('_AM_SITE_TITLE',   trim($sb_settings_config[0]));
// Search if there is a socket
if (strpos(trim($sb_settings_config[2]), ":") !== false) {
	// Define socket
	list($db_host, $db_socket) = explode(":", trim($sb_settings_config[2]));
	defined('_AM_DB_HOST') OR define('_AM_DB_HOST',	$db_host);
	defined('_AM_DB_SOCKET') OR define('_AM_DB_SOCKET',	$db_socket);
} else {
	// Pas de socket
	defined('_AM_DB_HOST') OR define('_AM_DB_HOST',	trim($sb_settings_config[2]));
	defined('_AM_DB_SOCKET') OR define('_AM_DB_SOCKET', false);
}
defined('_AM_DB_PORT') OR define('_AM_DB_PORT', 3306);
defined('_AM_DB_NAME') OR define('_AM_DB_NAME', trim($sb_settings_config[3]));
defined('_AM_DB_USER') OR define('_AM_DB_USER', trim($sb_settings_config[4]));
defined('_AM_DB_PWD') OR define('_AM_DB_PWD', trim($sb_settings_config[5]));
defined('_AM_MEDIAS_DIR') OR define('_AM_MEDIAS_DIR', trim($sb_settings_config[6]));
defined('_AM_MEDIAS_URL') OR define('_AM_MEDIAS_URL', trim($sb_settings_config[13]));
defined('_AM_GC_PUBLIC') OR define('_AM_GC_PUBLIC', trim($sb_settings_config[19]));
defined('_AM_GC_PRIVATE') OR define('_AM_GC_PRIVATE', trim($sb_settings_config[20]));
defined('_AM_DB_PREFIX') OR define('_AM_DB_PREFIX', trim($sb_settings_config[21]));

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
defined('SB_THEME_URL') OR define('SB_THEME_URL', SB_URL . "theme/" . SBTHEME . "/" );
defined('SB_THEME_DIR') OR define('SB_THEME_DIR', SB_PATH . "theme" . DIRECTORY_SEPARATOR . SBTHEME . DIRECTORY_SEPARATOR );

// ------------------------ 
// --- Modules
// ------------------------ 
defined('SB_MODULES_URL') OR define('SB_MODULES_URL', SB_URL . "datas/modules/" );
defined('SB_MODULES_DIR') OR define('SB_MODULES_DIR', SB_PATH . "datas" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR );

// ------------------------ 
// --- Various HTML Content
// ------------------------ 
defined('SB_VARIOUS_URL') OR define('SB_VARIOUS_URL', SB_THEME_URL . "inc/" );
defined('SB_VARIOUS_DIR') OR define('SB_VARIOUS_DIR', SB_THEME_DIR . "inc" . DIRECTORY_SEPARATOR );

// ------------------------ 
// --- Administration
// ------------------------ 
defined('SB_ADMIN_URL') OR define('SB_ADMIN_URL', SB_URL . SBADMIN . "/" );
defined('SB_ADMIN_DIR') OR define('SB_ADMIN_DIR', SB_PATH . SBADMIN . DIRECTORY_SEPARATOR );

// ------------------------ 
// --- Smarty (Core)
// ------------------------ 
defined('SB_SMARTY_DIR') OR define('SB_SMARTY_DIR', SB_ADMIN_DIR . "core" . DIRECTORY_SEPARATOR );

// ------------------------ 
// --- Settings (Admin)
// ------------------------ 
defined('SB_SETTINGS_FILE') OR define('SB_SETTINGS_FILE', SB_ADMIN_DIR . "inc" . DIRECTORY_SEPARATOR . "admin" . DIRECTORY_SEPARATOR . "settings.txt");

?>
