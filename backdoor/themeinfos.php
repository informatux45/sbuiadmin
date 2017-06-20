<?php
/**
 * Admin Startbootstrap
 * Manage THEME infos
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBMAGIC_PATH') or die('Are you crazy!');

// -----------------------
// Include Config CMS
// -----------------------
include_once('../sbconfig.php');

// -----------------------
// Module URL
// -----------------------
$module_page = 'themeinfos';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBMAGIC_URL . SBMAGIC_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------

// --------------------------------
// Initialize Form
// --------------------------------
$formName        = "edit_form";
$formType        = "edit";
$btn_add_edit    = "Modifier";
$legend_add_edit = "Modifier les infos de votre thème";
$table           = _AM_DB_PREFIX . "sb_config";
// --------------------------------
// --- Control form submit --------
// --------------------------------
if ($_POST['form_submit']) {

	// Injection des données
	$theme_infos_tel         = $sbsanitize->displayText($_POST['theme_infos_tel'], 'UTF-8', 1, 0);
	$theme_infos_address     = $sbsanitize->displayText($_POST['theme_infos_address'], 'UTF-8', 1, 0);
	$theme_infos_email       = $sbsanitize->displayText($_POST['theme_infos_email'], 'UTF-8', 1, 0);
	$theme_infos_facebook    = $sbsanitize->displayText($_POST['theme_infos_facebook'], 'UTF-8', 1, 0);
	$theme_infos_twitter     = $sbsanitize->displayText($_POST['theme_infos_twitter'], 'UTF-8', 1, 0);	 
	$theme_infos_google_plus = $sbsanitize->displayText($_POST['theme_infos_google_plus'], 'UTF-8', 1, 0);
	$theme_infos_pinterest   = $sbsanitize->displayText($_POST['theme_infos_pinterest'], 'UTF-8', 1, 0);
	$theme_infos_instagram   = $sbsanitize->displayText($_POST['theme_infos_instagram'], 'UTF-8', 1, 0);
	$theme_infos_skype       = $sbsanitize->displayText($_POST['theme_infos_skype'], 'UTF-8', 1, 0);
	$theme_infos_viadeo      = $sbsanitize->displayText($_POST['theme_infos_viadeo'], 'UTF-8', 1, 0);
	$theme_infos_linkedin    = $sbsanitize->displayText($_POST['theme_infos_linkedin'], 'UTF-8', 1, 0);
	$theme_infos_vimeo       = $sbsanitize->displayText($_POST['theme_infos_vimeo'], 'UTF-8', 1, 0);
	$theme_infos_youtube     = $sbsanitize->displayText($_POST['theme_infos_youtube'], 'UTF-8', 1, 0);
	
	// --- EDIT
	// UPDATE DATAS
	$query_tel         = "UPDATE $table SET content = '$theme_infos_tel' WHERE config = 'theme-infos-tel'";
	$query_address     = "UPDATE $table SET content = '$theme_infos_address' WHERE config = 'theme-infos-address'";
	$query_email       = "UPDATE $table SET content = '$theme_infos_email' WHERE config = 'theme-infos-email'";
	$query_facebook    = "UPDATE $table SET content = '$theme_infos_facebook' WHERE config = 'theme-infos-facebook'";
	$query_twitter     = "UPDATE $table SET content = '$theme_infos_twitter' WHERE config = 'theme-infos-twitter'";
	$query_google_plus = "UPDATE $table SET content = '$theme_infos_google_plus' WHERE config = 'theme-infos-google-plus'";
	$query_pinterest   = "UPDATE $table SET content = '$theme_infos_pinterest' WHERE config = 'theme-infos-pinterest'";
	$query_instagram   = "UPDATE $table SET content = '$theme_infos_instagram' WHERE config = 'theme-infos-instagram'";
	$query_skype       = "UPDATE $table SET content = '$theme_infos_skype' WHERE config = 'theme-infos-skype'";
	$query_viadeo      = "UPDATE $table SET content = '$theme_infos_viadeo' WHERE config = 'theme-infos-viadeo'";
	$query_linkedin    = "UPDATE $table SET content = '$theme_infos_linkedin' WHERE config = 'theme-infos-linkedin'";
	$query_vimeo       = "UPDATE $table SET content = '$theme_infos_vimeo' WHERE config = 'theme-infos-vimeo'";
	$query_youtube     = "UPDATE $table SET content = '$theme_infos_youtube' WHERE config = 'theme-infos-youtube'";
	
	$result_tel         = $sbsql->query($query_tel);
	$result_address     = $sbsql->query($query_address);
	$result_email       = $sbsql->query($query_email);
	$result_facebook    = $sbsql->query($query_facebook);
	$result_twitter     = $sbsql->query($query_twitter);
	$result_google_plus = $sbsql->query($query_google_plus);
	$result_pinterest   = $sbsql->query($query_pinterest);
	$result_instagram   = $sbsql->query($query_instagram);
	$result_skype       = $sbsql->query($query_skype);
	$result_viadeo      = $sbsql->query($query_viadeo);
	$result_linkedin    = $sbsql->query($query_linkedin);
	$result_vimeo       = $sbsql->query($query_vimeo);
	$result_youtube     = $sbsql->query($query_youtube);
	
	if ($result_tel && $result_address && $result_email && $result_facebook  && $result_twitter && $result_youtube && $result_pinterest && $result_instagram && $result_skype && $result_viadeo && $result_linkedin && $result_vimeo && $result_google_plus) {
		// --- Message SUCCES
		$sb_msg_valid = 'Infos du thème modifiées avec succès';
	} else {
		// --- Message ERROR
		$sb_msg_error = 'Error: Write Error (EDIT)!';
	}
	
}

// --------------------------------
// --- Recuperation des donnees
// --------------------------------
$query = "SELECT config, content FROM $table WHERE config = 'theme-infos-tel'
												OR config = 'theme-infos-address'
												OR config = 'theme-infos-email'
												OR config = 'theme-infos-facebook'
												OR config = 'theme-infos-twitter'
												OR config = 'theme-infos-youtube'
												OR config = 'theme-infos-pinterest'
												OR config = 'theme-infos-instagram'
												OR config = 'theme-infos-skype'
												OR config = 'theme-infos-viadeo'
												OR config = 'theme-infos-linkedin'
												OR config = 'theme-infos-vimeo'
												OR config = 'theme-infos-google-plus'
												";
$request = $sbsql->query($query);
$assoc   = $sbsql->toarray($request);
// --------------------------------
foreach($assoc as $row) {
	$cs[$row['config']] = utf8_encode($row['content']);
}

// --------------------------------

// --- Debug SQL
if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Form Type = '.$formType);

// --------------------------------		
// --- Define variables
$formAction = $module_url;
// --- Form construct
$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
// --------------------------------
$sbform->addBreak('Coordonnées');
// --------------------------------
// Telephone
// --------------------------------	
$sbform->addInput('text', 'Téléphone', array ('name' => 'theme_infos_tel', 'value' => $cs['theme-infos-tel'], 'placeholder' => "Téléphone", 'icon' => 'phone'), false);
// --------------------------------
// Email
// --------------------------------
$sbform->addInput('text', 'Email', array ('name' => 'theme_infos_email', 'value' => $cs['theme-infos-email'], 'placeholder' => "Email", 'icon' => 'envelope'), false);
// --------------------------------
// Adresse / CP / Ville
// --------------------------------	
$sbform->addTextarea('Adresse complète', $cs['theme-infos-address'], array('id' => 'theme_infos_address', 'name' => 'theme_infos_address'), false);
// --------------------------------
$sbform->addBreak('Réseaux sociaux');
// --------------------------------
// Facebook
// --------------------------------	
$sbform->addInput('text', 'Facebook', array ('name' => 'theme_infos_facebook', 'value' => $cs['theme-infos-facebook'], 'placeholder' => "Lien Facebook", 'icon' => 'facebook'), false);
// --------------------------------
// Twitter
// --------------------------------	
$sbform->addInput('text', 'Twitter', array ('name' => 'theme_infos_twitter', 'value' => $cs['theme-infos-twitter'], 'placeholder' => "Lien Twitter", 'icon' => 'twitter'), false);
// --------------------------------
// Google +
// --------------------------------	
$sbform->addInput('text', 'Google +', array ('name' => 'theme_infos_google_plus', 'value' => $cs['theme-infos-google-plus'], 'placeholder' => "Lien Google +", 'icon' => 'google-plus'), false);
// --------------------------------
// Pinterest
// --------------------------------	
$sbform->addInput('text', 'Pinterest', array ('name' => 'theme_infos_pinterest', 'value' => $cs['theme-infos-pinterest'], 'placeholder' => "Lien Pinterest", 'icon' => 'pinterest'), false);
// --------------------------------
// Instagram
// --------------------------------	
$sbform->addInput('text', 'Instagram', array ('name' => 'theme_infos_instagram', 'value' => $cs['theme-infos-instagram'], 'placeholder' => "Lien Instagram", 'icon' => 'instagram'), false);
// --------------------------------
// Linkedin
// --------------------------------	
$sbform->addInput('text', 'Linkedin', array ('name' => 'theme_infos_linkedin', 'value' => $cs['theme-infos-linkedin'], 'placeholder' => "Lien Linkedin", 'icon' => 'linkedin'), false);
// --------------------------------
// Viadeo
// --------------------------------	
$sbform->addInput('text', 'Viadeo', array ('name' => 'theme_infos_viadeo', 'value' => $cs['theme-infos-viadeo'], 'placeholder' => "Lien Viadeo", 'icon' => 'viadeo'), false);
// --------------------------------
// Skype
// --------------------------------	
$sbform->addInput('text', 'Skype', array ('name' => 'theme_infos_skype', 'value' => $cs['theme-infos-skype'], 'placeholder' => "Lien Skype", 'icon' => 'skype'), false);
// --------------------------------
// Youtube
// --------------------------------	
$sbform->addInput('text', 'Youtube', array ('name' => 'theme_infos_youtube', 'value' => $cs['theme-infos-youtube'], 'placeholder' => "Lien Youtube", 'icon' => 'youtube'), false);
// --------------------------------
// Vimeo
// --------------------------------	
$sbform->addInput('text', 'Vimeo', array ('name' => 'theme_infos_vimeo', 'value' => $cs['theme-infos-vimeo'], 'placeholder' => "Lien Vimeo", 'icon' => 'vimeo'), false);
// --------------------------------
// --- Hiddens / Buttons
// --------------------------------	
$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
// --------------------------------	
// --- Close Form
// --------------------------------	
$sbform->closeForm();
// --------------------------------

// ----------------------
// ASSIGN Settings Theme in progress
// ----------------------
$sb_theme_view = str_replace(SBADMIN, '', SB_THEME_URL) . 'screenshot-index.jpg';
$sbsmarty->assign('sb_theme_view', trim($sb_theme_view));

// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Infos de votre thème');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($action, 'UTF-8', 0, 1)));
// --- Second submit Button
$sbsmarty->assign('sb_form_id', $formName);
$sbsmarty->assign('sb_form_submit_value', $btn_add_edit);

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);
$sbsmarty->assign('sb_page', $sbpage);

// ----------------------
// CLOSE SQL
// ----------------------
$sbsql->close();
?>