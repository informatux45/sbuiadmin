<?php
/**
 * Plugin Name: SbMagic CONTACT
 * Description: Gestionnaire de formulaire de contact
 * File: FR Language
 * Agency: Agence DOLLAR
 * Agency URI: //dollar.fr
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 /** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'fr_FR.php') { 
	die('You cannot load this page directly.');
};

define('_CMS_CONTACT_FIELD_NAME',					'Nom');
define('_CMS_CONTACT_FIELD_EMAIL',					'Email');
define('_CMS_CONTACT_FIELD_MESSAGE',				'Message');
define('_CMS_CONTACT_FIELD_OBJECT',					'Objet');
define('_CMS_CONTACT_FIELD_PHONE',					'Téléphone');
define('_CMS_CONTACT_BUTTON_SEND',					'Envoyer');
// -------------------------------------------------------------------------
define('_CMS_CONTACT_FORM_SUCCESS',					"Votre message a été envoyé, nous vous contacterons très prochainement");
define('_CMS_CONTACT_FORM_ERROR_CAPTCHA',			"Vérification captcha échouée, essayez à nouveau");
define('_CMS_CONTACT_FORM_ERROR_CAPTCHA_EMPTY',		"La vérifcation reCAPTCHA doit être validée");
define('_CMS_CONTACT_NOFORM',						'Formulaire non disponible !');
define('_CMS_CONTACT_FORM_NOTFOUND',				"Formulaire inexistant !");
define('_CMS_CONTACT_FORM_INACTIVE',				"Formulaire désactivé !");

?>