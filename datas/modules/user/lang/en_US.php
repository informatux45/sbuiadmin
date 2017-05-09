<?php
/**
 * Plugin Name: SBUIADMIN USER
 * Description: Gestion des utilisateurs
 * File: US Language
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 /** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'en_US.php') { 
	die('You cannot load this page directly.');
};

define('_CMS_USER_NO_IE',				"Please use a browser different than Internet Explorer");
define('_CMS_USER_FORM_USERNAME',		"Username");
define('_CMS_USER_FORM_PASSWORD',		"Password");
define('_CMS_USER_FORM_REMEMBER_ME',	"Remember me");
define('_CMS_USER_FORM_FORGOT_PWD',		"Forgot your password?");
define('_CMS_USER_TITLE',				"Login");
define('_CMS_USER_MSG_ERROR_E1',		"Incorrect Captcha");
define('_CMS_USER_MSG_ERROR_E2',		"Incorrect Login");
define('_CMS_USER_MSG_ERROR_E3',		"Missing Login");
define('_CMS_USER_MSG_ERROR_E4',		"Account Disabled");

?>