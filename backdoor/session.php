<?php
/**
 * Admin Startbootstrap
 * Manage SESSION
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
$module_page = 'session';
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
$legend_add_edit = "Modifier les infos des sessions utilisateurs";
$table           = _AM_DB_PREFIX . "sb_config";
// --------------------------------
// --- Control form submit --------
// --------------------------------
if ($_POST['form_submit']) {

	// Injection des données
	$session_cookie_lifetime = intval($_POST['session_cookie_lifetime']);
	
	// --- EDIT
	// UPDATE DATAS
	$query_session = "UPDATE $table SET content = '$session_cookie_lifetime' WHERE config = 'cookie-lifetime'";
	$result_session = $sbsql->query($query_session);
	
	if ($result_session) {
		// --- Message SUCCES
		$sb_msg_valid = 'Infos des sessions utilisateurs modifiées avec succès';
	} else {
		// --- Message ERROR
		$sb_msg_error = 'Error: Write Error (EDIT)!';
	}
	
}

// --------------------------------
// --- Recuperation des donnees
// --------------------------------
$query   = "SELECT config, content FROM $table WHERE config = 'cookie-lifetime'";
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
$sbform->addBreak('Session');
// --------------------------------
// Temps de session
// --------------------------------	
$sb_cookie_lifetime = array('1 heure'    => 3600,
							'2 heures'   => 7200,
							'1 jour'     => 86400,
							'2 jours'    => 172800,
							'1 semaine'  => 604800,
							'2 semaines' => 1209600,
							'1 mois'     => 2419200
							);
$sbform->openSelect("Durée d'une session", array("id"=>"session_cookie_lifetime", "name"=>"session_cookie_lifetime", "style"=>"width: 300px !important;"), true);
if ($cs['cookie-lifetime'] == '') $sbform->addOption('Choisissez une durée de session', array ("value"=>"", "selected"=>""));
foreach($sb_cookie_lifetime as $key => $value) {
	if ($value == $cs['cookie-lifetime'])
		$sbform->addOption($key, array ("value" => $value, "selected"=>""));
	else
		$sbform->addOption($key, array ("value" => $value));
}
// --- Close Select
$sbform->closeSelect('Un cookie est créé dès lors que vos utilisateurs coche la case "Se souvenir de moi".<br>Paramètre Cookie Lifetime.');
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
$sbsmarty->assign('page_title', 'Infos des sessions utilisateurs');
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
