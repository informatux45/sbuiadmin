<?php
/**
 * Plugin Name: SbMagic CONTACT
 * Description: Gestionnaire de formulaire de contact
 * File: EN Language (US)
 * Agency: Agence DOLLAR
 * Agency URI: //dollar.fr
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 /** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'en_US.php') { 
	die('You cannot load this page directly.');
};

define('_CMS_CONTACT_FIELD_NAME',					'Name');
define('_CMS_CONTACT_FIELD_EMAIL',					'Email');
define('_CMS_CONTACT_FIELD_MESSAGE',				'Message');
define('_CMS_CONTACT_FIELD_OBJECT',					'Object');
define('_CMS_CONTACT_FIELD_PHONE',					'Phone');
define('_CMS_CONTACT_BUTTON_SEND',					'Send Message');
// -------------------------------------------------------------------------
define('_CMS_CONTACT_FORM_SUCCESS',					"Your contact request have submitted successfully");
define('_CMS_CONTACT_FORM_ERROR_CAPTCHA',			"Robot verification failed, please try again");
define('_CMS_CONTACT_FORM_ERROR_CAPTCHA_EMPTY',		"Please click on the reCAPTCHA box");
define('_CMS_CONTACT_NOFORM',						'Form not available!');
define('_CMS_CONTACT_FORM_NOTFOUND',				"Form not found!");
define('_CMS_CONTACT_FORM_INACTIVE',				"Form inactive!");

?>