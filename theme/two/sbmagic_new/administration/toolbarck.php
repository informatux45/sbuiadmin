<?php
/**
 * Admin Startbootstrap
 * Manage TOOLBAR CKEDITOR
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
// Start Session
// -----------------------
session_start();

// -----------------------
// Module URL
// -----------------------
$module_page = 'toolbarck';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBMAGIC_URL . SBMAGIC_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
// -----------------------
$table = _AM_DB_PREFIX . 'sb_config';
// -----------------------
 
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
$action = $_GET['a'];
switch($action) {
	default:
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = "edit_form";
		$formType        = "edit";
		$btn_add_edit    = "Modifier";
		$legend_add_edit = "Modifier le comportement TOOLBAR de CKEDITOR";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$active      = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			$form_config = $_POST['form_config'];
			// --- EDIT
			// UPDATE / INSERT DATAS
			if ($form_config == 'toolbarck')
				$query  = "UPDATE $table SET content = '$active' WHERE config = 'toolbarck'";
			else
				$query = "INSERT INTO $table (config,content) VALUES ('toolbarck','$content')";
			// --- Result
			$result = $sbsql->query($query);
			if ($result) {
				// --- Message SUCCES
				$sb_msg_valid = 'Le comportement de la TOOLBAR CKEditor a été modifié avec succès';
			} else {
				$sb_msg_error = 'Error: Write Error (ADD)!';
			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		// --- Recuperation des donnees
		// --------------------------------
		$query = "SELECT config, content FROM $table WHERE config = 'toolbarck'";
		$request = $sbsql->query($query);
		$assoc   = $sbsql->assoc($request);
		// --------------------------------
		$active = intval($assoc['content']);
		// --------------------------------

		// --- Debug SQL
		if (_AM_SITE_DEBUG && !$_POST['form_submit']) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Form Type = '.$formType);

		// --------------------------------		
		// --- Define variables
		$formAction = $module_url;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé', "Si désactivé, les éditeurs CKEditor conserveront leurs paramétrages initiaux (BASIC, SIMPLE, FULL, CUSTOM).");
		// --- Hiddens / Buttons
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('hidden', '', array('name' => 'form_config', 'value' => $assoc['config']));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --- Close Form
		$sbform->closeForm ();
		
	break;

}

// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Configuration de la TOOLBAR de CKEDITOR');
$sbsmarty->assign('legend_add_edit', $sbsanitize->displayText($legend_add_edit, 'UTF-8', 1, 0));

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

// ----------------------
// CLOSE SQL
// ----------------------
$sbsql->close();
?>