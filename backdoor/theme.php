<?php
/**
 * Admin Startbootstrap
 * Manage THEME
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
$module_page = 'theme';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBMAGIC_URL . SBMAGIC_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

/* ----------------------------- *
// SB Theme File
 * Referentiel du fichier THEME
 * -----------------------------
 * 0  - Theme name
 * ---------------------------- */
$sb_theme_file = _AM_THEME_FILE;

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
$legend_add_edit = "Modifier votre theme (client)";
// --------------------------------
// --- Control form submit --------
// --------------------------------
if ($_POST['form_submit']) {

	// Injection des données
	$sb_output_file  = $sbsanitize->displayText($_POST['theme_view'], 'UTF-8', 1, 0) . "\n";
	
	// Locker le fichier pour qu'une seule personne a la fois ecrive dedans
	$result_edit = file_put_contents($sb_theme_file, $sb_output_file, FILE_USE_INCLUDE_PATH | LOCK_EX);
									 
		// Result Edit
		if ($result_edit) {
			// --- On ne vide pas les champs du formulaire
			// -------------------------------------------
			// --- Message SUCCES
			$sb_msg_valid = 'Thème modifié avec succès';
		} else {
			// --- Message ERROR
			$sb_msg_error = 'Error: Write Error (EDIT)!';
		}
}

// --------------------------------
// --- Ouverture du fichier
$sb_theme = file($sb_theme_file);
// --- Initialisation
$sb_theme_name = trim($sb_theme[0]);


// --- Debug SQL
if (_AM_SITE_DEBUG) $sbsmarty->assign('file_content', $sb_theme);						
// --------------------------------		
// --- Define variables
$formAction = $module_url . "&a=" . $formType;
// --- Form construct
$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
// -----------------------------------
// --- Affichage du Select - THEME Name
// -----------------------------------
$sb_themes = sbGetThemesFront(SB_PATH . "theme");
$sbform->openSelect("Thème de votre site web", array("id"=>"theme_view", "name"=>"theme_view"));
for($i = 0; $i < count($sb_themes); $i++) {
	if ($sb_themes[$i] == $sb_theme_name)
		$sbform->addOption($sb_themes[$i], array ("value"=>$sb_themes[$i], "selected"=>""));
else
		$sbform->addOption($sb_themes[$i], array ("value"=>$sb_themes[$i]));
}
// --- Close Select
$sbform->closeSelect();
// --- Hiddens / Buttons
$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
$sbform->addInput('reset', '', array('value' => "Reset"));
// --- Close Form
$sbform->closeForm ();


// ----------------------
// ASSIGN Settings
// ----------------------
$sb_theme_view = str_replace(SBADMIN, '', SB_THEME_URL) . 'screenshot-index.jpg';
$sbsmarty->assign('sb_theme_view', trim($sb_theme_view));

// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Thème');

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);
$sbsmarty->assign('sb_page', $sbpage);

// ----------------------
// CLOSE SQL
// ----------------------
// $sbsql->close();
?>