<?php

/**
 * 
 * Please read the LICENSE file
 * @author Vadim V. Gabriel <vadimg88@gmail.com> http://www.vadimg.co.il/
 * @package Class Installer
 * @version 1.0.0a
 * @license GNU Lesser General Public License
 * 
 */

# Define root path
define('ROOT_PATH', dirname(__FILE__));

# Define Admin Path
define('ADMIN_PATH', str_replace('/install', '/', ROOT_PATH));

# Define Installer Path
define('INSTALLER_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'installer');

# Define templates path
if(!defined('TMPL_PATH'))
{
    define('TMPL_PATH', INSTALLER_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR);
}

# Define base url
define('BASE_URL', 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);

# Error reporting
ini_set('display_errors', true);
error_reporting(E_ALL ^ E_WARNING ^ E_USER_WARNING ^ E_NOTICE ^ E_USER_NOTICE);

# Default step
$_POST['step'] = isset($_POST['step']) ? $_POST['step'] : 'index';

# Sanitize class (for installer)
define('SBMAGIC_PATH', '');
require_once(ADMIN_PATH . 'core/Smarty.class.php');
require_once(ADMIN_PATH . 'inc/class/sbmagic-sql.php');
require_once(ADMIN_PATH . 'inc/class/sbmagic-sanitize.php');
$sbmagic   = new sanitize();

require_once(INSTALLER_PATH . '/Installer.php');
$installer = new Installer();
$installer->display();

# Done!