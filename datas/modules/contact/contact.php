<?php
/**
 * Plugin Name: SBUIADMIN CONTACT
 * Description: Gestionnaire de formulaire de contact
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

# Define some important stuff
define('MODULEFILE', basename(__FILE__, ".php"));
define('MODULENAME', 'Contact');
define('MODULEVERSION','0.1.1');

# Include Module Infos
$sblang_contact = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_contact . '.php' );
include_once( SB_MODULES_DIR . MODULEFILE . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php' );

// -------------------------------------------------
// --- Global MODULE
// -------------------------------------------------
$module['name']        = 'SbMagic CONTACT';
$module['dirname']     = basename(dirname(__FILE__));
$module['version']     = MODULEVERSION;
$module['description'] = "Gestionnaire de formulaires de contact";
$module['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$module['tables']['config']  = _AM_DB_PREFIX . "sb_config";
$module['tables']['contact'] = "sb_contact";
// -------------------------------------------------

# Include Globals
global $sbsmarty, $sbsanitize, $sbsql, $sbpage;

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

# Anti-robot ALTCHA (une seule API : inc/sbuiadmin-altcha.php)
$sbsmarty->assign('sb_altcha_widget', sbAltchaWidget());

# Define TPL to show (view)
$op       = $sbsanitize->addSlashes($_REQUEST['op'] ?? '');
$id       = intval($_REQUEST['id'] ?? 0);
$template = 'index';
# TPL View MAIN
$module['template_main'] = MODULEFILE . '_' . $template . '.tpl';

// --------------------------
// --- Switch with Op GET
// --------------------------
$op = $_GET['op'] ?? '';
switch($op) {
	default: // Show form
		
		$name = $email = $errMsg = $succMsg = '';
		// --- Check if form is submitted
		if(isset($_POST['submit']) && !empty($_POST['submit'])) {
			// --- Anti-robot ALTCHA (une seule API : inc/sbuiadmin-altcha.php)
			if (sbAltchaVerify()) {
				$errMsg = sbContactSend(0, $email_to, $subject, 'Contact site ' . _AM_SITE_TITLE, array('submit'));
				if ($errMsg === '') {
					$succMsg = _CMS_CONTACT_FORM_SUCCESS;
					// --- Empty form fields
					$sbsmarty->assign('sendmailok', 'ok');
				}
			} else {
				$errMsg = ($GLOBALS['sb_altcha_error'] == 'missing') ? _CMS_CONTACT_FORM_ERROR_CAPTCHA_EMPTY : _CMS_CONTACT_FORM_ERROR_CAPTCHA;
			}
		} else {
			$errMsg = '';
			$succMsg = '';
		}
		
		if ($errMsg != '' || $succMsg != '') {
			$sbsmarty->assign('errMsg', $errMsg);
			$sbsmarty->assign('succMsg', $succMsg);
		}
		
		// --------------------------
		// --- Choose theme view
		// --------------------------
		$module['theme_main'] = 'index';
		// --------------------------
		// --- Add Template BLOCKS (depends on the theme view choosen)
		// --------------------------
		$module['template_main_blocks'] = MODULEFILE . '_' . $template . '_blocks.tpl';
	break;
}


?>
