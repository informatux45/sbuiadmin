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
$module['tables']['config']  = "sb_config";
$module['tables']['contact'] = "sb_contact";
// -------------------------------------------------

# Include Globals
global $sbsmarty, $sbsanitize, $sbsql, $sbpage;

# --------------------------------------------------
# Settings for Recaptcha AND Global email settings
# --------------------------------------------------
// --- SQL Request (all config)
$query   = "SELECT config, content FROM {$module['tables']['config']} WHERE config = 'email_to' OR config = 'email_subject' OR config = 'email_publickey' OR config = 'email_privatekey'";
$request = $sbsql->query($query);
$result  = $sbsql->toarray($request);
foreach($result as $val) {
	switch($val['config']) {
		case "email_to": $email_to = $sbsanitize->sTrim($val['content']); break;
		case "email_subject": $subject = $sbsanitize->displayLang(utf8_encode($val['content'])); break;
		case "email_publickey": $publickey = $sbsanitize->sTrim($val['content']); break;
		case "email_privatekey": $privatekey = $sbsanitize->sTrim($val['content']); break;
	}
}

$sbsmarty->assign('grecaptcha_publickey', $publickey);
# --------------------------------------------------

# Define TPL to show (view)
$op       = $sbsanitize->addSlashes($_REQUEST['op']);
$id       = intval($_REQUEST['id']);
$template = 'index';
# TPL View MAIN
$module['template_main'] = MODULEFILE . '_' . $template . '.tpl';

// --------------------------
// --- Switch with Op GET
// --------------------------
$op = $_GET['op'];
switch($op) {
	default: // Show form
		
		// --- Check if form is submitted
		if(isset($_POST['submit']) && !empty($_POST['submit'])) {
			// --- Check Google Recaptcha
			if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
				function getCurlData($url) {
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_TIMEOUT, 10);
					curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
					$curlData = curl_exec($curl);
					curl_close($curl);
					return $curlData;
				}
				
				// --- Get verify response data
				$google_url = "https://www.google.com/recaptcha/api/siteverify";
				$ip         = $_SERVER['REMOTE_ADDR'];
				$url        = $google_url . "?secret=" . $privatekey . "&response=" . $_POST['g-recaptcha-response'] . "&remoteip=" . $ip;
				$response   = getCurlData($url);
				$response   = json_decode($response); // Don't add TRUE setting in json_decode
				
				if ($response->success === true) {
					// --- Initialization
					$htmlContent = '<h1>Contact site ' . _AM_SITE_TITLE . '</h1>';
					// --- Get Contact form submission $_POST
					foreach($_POST as $k => $v) {
						if ($k == 'name')  $name  = $v;
						if ($k == 'email') $email = $v;
						// --- Increase html content
						if ($k != 'g-recaptcha-response' && $k != 'submit') $htmlContent .= '<p><b>'.str_replace("-", " ", $k).' :</b> '.$sbsanitize->nl2Br($v).'</p>';
					}

					// --- Always set content-type when sending HTML email
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					// --- More headers
					$headers .= 'From:'.$name.' <'.$email.'>' . "\r\n";
					// --- Send email
					@mail($email_to, $subject, $htmlContent, $headers);
					
					$succMsg = _CMS_CONTACT_FORM_SUCCESS;
					// --- Empty form fields
					$sbsmarty->assign('sendmailok', 'ok');
					
				} else {
					$errMsg = _CMS_CONTACT_FORM_ERROR_CAPTCHA . '(error-codes: '.$responseData->error.')';
				}
			} else {
				$errMsg = _CMS_CONTACT_FORM_ERROR_CAPTCHA_EMPTY;
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