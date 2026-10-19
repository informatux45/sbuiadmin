<?php
/**
 * Plugin Name: SBUIADMIN CONTACT AJAX
 * Description: Gestionnaire de formulaire de contact
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//          Header CACHE         -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   // Date du passé
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
 
 // ---------------------------
// SESSION Initialisation
// ---------------------------
// --- Nom de la session : DOIT etre pose avant session_start(), sinon ce
// --- point d'entree repose sa propre session sous PHPSESSID et perd tout
// --- ce que les autres y ont mis. Voir inc/sbsession.php.
require_once(__DIR__ . '/../../../inc/sbsession.php');
session_start();

// ----------------------------------------- 
// --- Load Default Include for AJAX Request
// -----------------------------------------
include_once('../../../sbconfig.php');
include_once('../../../header.php');
global $sbsmarty, $sbsanitize, $sbsql, $sbpage;

// ---------------------------
// Define some important stuff
// ---------------------------
define('MODULEFILE', basename(__FILE__, "_ajax.php"));
define('MODULENAME', 'Contact');
define('MODULEVERSION','0.1.1');

// ---------------------------
// Security Check
// ---------------------------
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

// ---------------------------
// Include Module Common Infos
// ---------------------------
$sblang_contact = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_contact . '.php' );
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php' );

// ---------------------------
// Tables SQL
// ---------------------------
$module['tables']['config']  = _AM_DB_PREFIX . "sb_config";
$module['tables']['contact'] = "sb_contact";
// -------------------------------------------------

# --------------------------------------------------
# Global email settings
# --------------------------------------------------
// --- SQL Request (all config)
$email_to = $subject = '';
$query   = "SELECT config, content FROM {$module['tables']['config']} WHERE config = 'email_to' OR config = 'email_subject'";
$request = $sbsql->query($query);
$result  = $sbsql->toarray($request);
foreach($result as $val) {
	switch($val['config']) {
		case "email_to": $email_to = $sbsanitize->sTrim($val['content']); break;
		case "email_subject": $subject = $sbsanitize->displayLang(sb_utf8_encode($val['content'])); break;
	}
}

# --------------------------------------------------

# --------------------------------------------------
# Define TPL to show (view)
# --------------------------------------------------
$op       = $sbsanitize->addSlashes($_REQUEST['op'] ?? '');
$id       = intval($_REQUEST['id'] ?? 0);
$template = 'index';
# TPL View MAIN
//$module['template_main'] = MODULEFILE . '_' . $template . '.tpl';

# --------------------------------------------------
# Settings for email settings (if exists)
# SQL Request
# --------------------------------------------------
$query_contact   = "SELECT recipients, subject, active FROM " . _AM_DB_PREFIX . $module['tables']['contact'] . " WHERE id = '$id'";
$request_contact = $sbsql->query($query_contact);
$result_contact  = $sbsql->assoc($request_contact);
// --- Formulaire inexistant ou désactivé : rien n'est envoyé ni enregistré
if (!$result_contact || !$result_contact['active']) {
	http_response_code(404);
	echo '<div class="box error-ajax-msg"><i class="fa-solid fa-xmark red response-ajax-msg" aria-hidden="true"></i> ' . (!$result_contact ? _CMS_CONTACT_FORM_NOTFOUND : _CMS_CONTACT_FORM_INACTIVE) . '</div>';
	exit;
}
if ($result_contact['recipients'] != '') $email_to = $sbsanitize->sTrim($result_contact['recipients']);
if ($sbsanitize->displayLang(sb_utf8_encode($result_contact['subject'])) != '') $subject = $sbsanitize->displayLang(sb_utf8_encode($result_contact['subject']));
# --------------------------------------------------

// --------------------------
// --- Switch with Op GET
// --------------------------
$op = $_GET['op'] ?? '';
switch($op) {
	default: // Show form
		
		$name = $email = '';
		// --- Check if form is submitted
		if(isset($_POST['submitform']) && !empty($_POST['submitform'])) {
			// --- Anti-robot ALTCHA (une seule API : inc/sbuiadmin-altcha.php)
			if (sbAltchaVerify()) {
				$errMsg = sbContactSend($id, $email_to, $subject, 'Contact site ' . _AM_SITE_TITLE, array('submitform', 'submit'));
				if ($errMsg === '') {
					echo '<div class="box success-ajax-msg"><i class="fa-solid fa-check green response-ajax-msg" aria-hidden="true"></i> ' . _CMS_CONTACT_FORM_SUCCESS . '</div>';
				} else {
					// --- Erreur : le formulaire n'est pas vidé (voir .fail() de shortcode_sbcontactajax)
					http_response_code(422);
					echo '<div class="box error-ajax-msg"><i class="fa-solid fa-xmark red response-ajax-msg" aria-hidden="true"></i> ' . $errMsg . '</div>';
				}

			} else {
				echo '<div class="box error-ajax-msg"><i class="fa-solid fa-xmark red response-ajax-msg" aria-hidden="true"></i> ' . (($GLOBALS['sb_altcha_error'] == 'missing') ? _CMS_CONTACT_FORM_ERROR_CAPTCHA_EMPTY : _CMS_CONTACT_FORM_ERROR_CAPTCHA) . '</div>';
			}
		} else {
			echo '<div class="box error-ajax-msg"><i class="fa-solid fa-xmark red response-ajax-msg" aria-hidden="true"></i> Form Submit Error </div>';
		}
		
	break;
}


?>
