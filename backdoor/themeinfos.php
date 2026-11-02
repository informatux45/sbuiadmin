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
defined('SBUIADMIN_PATH') or die('Are you crazy!');

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
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
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
if (!empty($_POST['form_submit'])) {

	// Injection des données
	$theme_infos_tel         = $sbsanitize->displayText($_POST['theme_infos_tel'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_address     = $sbsanitize->displayText($_POST['theme_infos_address'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_email       = $sbsanitize->displayText($_POST['theme_infos_email'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_facebook    = $sbsanitize->displayText($_POST['theme_infos_facebook'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_twitter     = $sbsanitize->displayText($_POST['theme_infos_twitter'] ?? '', 'UTF-8', 1, 0);	 
	$theme_infos_google_plus = $sbsanitize->displayText($_POST['theme_infos_google_plus'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_pinterest   = $sbsanitize->displayText($_POST['theme_infos_pinterest'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_instagram   = $sbsanitize->displayText($_POST['theme_infos_instagram'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_skype       = $sbsanitize->displayText($_POST['theme_infos_skype'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_viadeo      = $sbsanitize->displayText($_POST['theme_infos_viadeo'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_linkedin    = $sbsanitize->displayText($_POST['theme_infos_linkedin'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_vimeo       = $sbsanitize->displayText($_POST['theme_infos_vimeo'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_youtube     = $sbsanitize->displayText($_POST['theme_infos_youtube'] ?? '', 'UTF-8', 1, 0);
	$theme_infos_github      = $sbsanitize->displayText($_POST['theme_infos_github'] ?? '', 'UTF-8', 1, 0);
	
	// --- EDIT
	// UPDATE DATAS
	$sb_theme_infos_ok = true;
	foreach (array('tel', 'address', 'email', 'facebook', 'twitter', 'google_plus', 'pinterest', 'instagram', 'skype', 'viadeo', 'linkedin', 'vimeo', 'youtube', 'github') as $sb_info) {
		$sb_theme_infos_ok = sbDb()->update('sb_config', array('content' => ${'theme_infos_' . $sb_info}), 'config = ?', array('theme_infos_' . $sb_info)) !== false && $sb_theme_infos_ok;
	}
	
	if ($sb_theme_infos_ok) {
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
$query = "SELECT config, content FROM {sb_config} WHERE config IN ('theme_infos_tel', 'theme_infos_address', 'theme_infos_email', 'theme_infos_facebook', 'theme_infos_twitter', 'theme_infos_youtube', 'theme_infos_pinterest', 'theme_infos_instagram', 'theme_infos_skype', 'theme_infos_viadeo', 'theme_infos_linkedin', 'theme_infos_vimeo', 'theme_infos_google_plus', 'theme_infos_github')";
$assoc = sbDb()->all($query) ?: array();
// --------------------------------
$cs = [];
foreach($assoc as $row) {
	$cs[$row['config']] = sb_utf8_encode($row['content']);
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
$sbform->addInput('text', 'Téléphone', array ('name' => 'theme_infos_tel', 'value' => $cs['theme_infos_tel'], 'placeholder' => "Téléphone", 'icon' => 'phone'), false);
// --------------------------------
// Email
// --------------------------------
$sbform->addInput('text', 'Email', array ('name' => 'theme_infos_email', 'value' => $cs['theme_infos_email'], 'placeholder' => "Email", 'icon' => 'envelope'), false);
// --------------------------------
// Adresse / CP / Ville
// --------------------------------	
$sbform->addTextarea('Adresse complète', $cs['theme_infos_address'], array('id' => 'theme_infos_address', 'name' => 'theme_infos_address'), false);
// --------------------------------
$sbform->addBreak('Réseaux sociaux');
// --------------------------------
// Github
// --------------------------------	
$sbform->addInput('text', 'Github', array ('name' => 'theme_infos_github', 'value' => $cs['theme_infos_github'], 'placeholder' => "Lien Github", 'icon' => 'github'), false);
// --------------------------------
// Facebook
// --------------------------------	
$sbform->addInput('text', 'Facebook', array ('name' => 'theme_infos_facebook', 'value' => $cs['theme_infos_facebook'], 'placeholder' => "Lien Facebook", 'icon' => 'facebook'), false);
// --------------------------------
// Twitter
// --------------------------------	
$sbform->addInput('text', 'Twitter', array ('name' => 'theme_infos_twitter', 'value' => $cs['theme_infos_twitter'], 'placeholder' => "Lien Twitter", 'icon' => 'twitter'), false);
// --------------------------------
// Google +
// --------------------------------	
$sbform->addInput('text', 'Google +', array ('name' => 'theme_infos_google_plus', 'value' => $cs['theme_infos_google_plus'], 'placeholder' => "Lien Google +", 'icon' => 'google-plus'), false);
// --------------------------------
// Pinterest
// --------------------------------	
$sbform->addInput('text', 'Pinterest', array ('name' => 'theme_infos_pinterest', 'value' => $cs['theme_infos_pinterest'], 'placeholder' => "Lien Pinterest", 'icon' => 'pinterest'), false);
// --------------------------------
// Instagram
// --------------------------------	
$sbform->addInput('text', 'Instagram', array ('name' => 'theme_infos_instagram', 'value' => $cs['theme_infos_instagram'], 'placeholder' => "Lien Instagram", 'icon' => 'instagram'), false);
// --------------------------------
// Linkedin
// --------------------------------	
$sbform->addInput('text', 'Linkedin', array ('name' => 'theme_infos_linkedin', 'value' => $cs['theme_infos_linkedin'], 'placeholder' => "Lien Linkedin", 'icon' => 'linkedin'), false);
// --------------------------------
// Viadeo
// --------------------------------	
$sbform->addInput('text', 'Viadeo', array ('name' => 'theme_infos_viadeo', 'value' => $cs['theme_infos_viadeo'], 'placeholder' => "Lien Viadeo", 'icon' => 'viadeo'), false);
// --------------------------------
// Skype
// --------------------------------	
$sbform->addInput('text', 'Skype', array ('name' => 'theme_infos_skype', 'value' => $cs['theme_infos_skype'], 'placeholder' => "Lien Skype", 'icon' => 'skype'), false);
// --------------------------------
// Youtube
// --------------------------------	
$sbform->addInput('text', 'Youtube', array ('name' => 'theme_infos_youtube', 'value' => $cs['theme_infos_youtube'], 'placeholder' => "Lien Youtube", 'icon' => 'youtube'), false);
// --------------------------------
// Vimeo
// --------------------------------	
$sbform->addInput('text', 'Vimeo', array ('name' => 'theme_infos_vimeo', 'value' => $cs['theme_infos_vimeo'], 'placeholder' => "Lien Vimeo", 'icon' => 'vimeo'), false);
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
$sbsmarty->assign('legend_add_edit', sprintf((string) ($legend_add_edit ?? ''), $sbsanitize->displayText($action ?? '', 'UTF-8', 0, 1)));
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
