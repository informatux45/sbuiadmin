<?php
/**
 * Plugin Name: SBUIADMIN USER
 * Description: Gestion des utilisateurs
 * File: FR Language
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 /** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'fr_FR.php') { 
	die('You cannot load this page directly.');
};

define('_CMS_USER_NO_IE',				"Veuillez utiliser un navigateur Internet autre que Internet Explorer");
define('_CMS_USER_FORM_USERNAME',		"Username");
define('_CMS_USER_FORM_PASSWORD',		"Password");
define('_CMS_USER_FORM_REMEMBER_ME',	"Se souvenir de moi");
define('_CMS_USER_FORM_FORGOT_PWD',		"Mot de passe oublié ?");
define('_CMS_USER_TITLE',				"Login");
define('_CMS_USER_MSG_ERROR_E1',		"Captcha incorrect");
define('_CMS_USER_MSG_ERROR_E2',		"Login incorrect");
define('_CMS_USER_MSG_ERROR_E3',		"Login manquant");
define('_CMS_USER_MSG_ERROR_E4',		"Compte désactivé");
define('_CMS_USER_ICON_TEXT_LOGIN',		"Login");
define('_CMS_USER_ICON_TEXT_PROFILE',	"Profil");
define('_CMS_USER_ICON_TEXT_LOGOUT',	"Déconnexion");

?>