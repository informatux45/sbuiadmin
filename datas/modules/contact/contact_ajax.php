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
$module['tables']['config']  = "sb_config";
$module['tables']['contact'] = "sb_contact";
// -------------------------------------------------

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

# --------------------------------------------------
# Define TPL to show (view)
# --------------------------------------------------
$op       = $sbsanitize->addSlashes($_REQUEST['op']);
$id       = intval($_REQUEST['id']);
$template = 'index';
# TPL View MAIN
//$module['template_main'] = MODULEFILE . '_' . $template . '.tpl';

# --------------------------------------------------
# Settings for email settings (if exists)
# SQL Request
# --------------------------------------------------
$query_contact   = "SELECT recipients, subject FROM {$module['tables']['contact']} WHERE id = '$id'";
$request_contact = $sbsql->query($query_contact);
$result_contact  = $sbsql->assoc($request_contact);
if ($result_contact['recipients'] != '') $email_to = $sbsanitize->sTrim($result_contact['recipients']);
if ($sbsanitize->displayLang(utf8_encode($result_contact['subject'])) != '') $subject = $sbsanitize->displayLang(utf8_encode($result_contact['subject']));
# --------------------------------------------------

// --------------------------
// --- Switch with Op GET
// --------------------------
$op = $_GET['op'];
switch($op) {
	default: // Show form
		
		// --- Check if form is submitted
		if(isset($_POST['submitform']) && !empty($_POST['submitform'])) {
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
						if ($k != 'g-recaptcha-response' && $k != 'submitform') $htmlContent .= '<p><b>'.str_replace("-", " ", $k).' :</b> '.$sbsanitize->nl2Br($v).'</p>';
					}

					// --- Always set content-type when sending HTML email
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
					// --- More headers
					$headers .= 'From:'.$name.' <'.$email.'>' . "\r\n";
					// --- Send email
					@mail($email_to, $subject, $htmlContent, $headers);
					
					echo '<div class="box success-ajax-msg"><i class="fa fa-check green response-ajax-msg" aria-hidden="true"></i> ' . _CMS_CONTACT_FORM_SUCCESS . '</div>';

				} else {
					echo '<div class="box error-ajax-msg"><i class="fa fa-times red response-ajax-msg" aria-hidden="true"></i> ' . _CMS_CONTACT_FORM_ERROR_CAPTCHA . '</div>';
				}
			} else {
				echo '<div class="box error-ajax-msg"><i class="fa fa-times red response-ajax-msg" aria-hidden="true"></i> ' . _CMS_CONTACT_FORM_ERROR_CAPTCHA_EMPTY . '</div>';
			}
		} else {
			echo '<div class="box error-ajax-msg"><i class="fa fa-times red response-ajax-msg" aria-hidden="true"></i> Form Submit Error </div>';
		}
		
	break;
}


?>