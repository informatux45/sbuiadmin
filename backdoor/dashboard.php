<?php
/**
 * Admin Startbootstrap
 * Manage DASHBOARD
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
// Start Session
// -----------------------
session_start();

// -----------------------
// Module URL
// -----------------------
$module_page = 'dashboard';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

/* ----------------------------- *
// SB Dashboard File
 * Referentiel du fichier DASHBOARD
 * -----------------------------
 * 0  - Tables SQL
 * 1  - Status 1 Table
 * 2  - Status 1 Titre
 * 3  - Status 1 Lien
 * 4  - Status 1 Icone
 * 5  - Status 1 Table colonne
 * 6  - Status 2 Table
 * 7  - Status 2 Titre
 * 8  - Status 2 Lien
 * 9  - Status 2 Icone
 * 10 - Status 2 Table colonne
 * 11 - Status 3 Table
 * 12 - Status 3 Titre
 * 13 - Status 3 Lien
 * 14 - Status 3 Icone
 * 15 - Status 3 Table colonne
 * 16 - Status 4 Table
 * 17 - Status 4 Titre
 * 18 - Status 4 Lien
 * 19 - Status 4 Icone
 * 20 - Status 4 Table colonne
 * ---------------------------- */
$sb_dashboard_file = _AM_DASHBOARD_FILE;

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
$legend_add_edit = "Modifier votre dashboard";
// --------------------------------
// --- Control form submit --------
// --------------------------------
if ($_POST['form_submit']) {

	// Injection des données
	$sb_output_file  = $sbsanitize->displayText($_POST['tables'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status1_table'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status1_title'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status1_link'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status1_icon'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status1_tbcol'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status2_table'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status2_title'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status2_link'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status2_icon'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status2_tbcol'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status3_table'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status3_title'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status3_link'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status3_icon'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status3_tbcol'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status4_table'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status4_title'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status4_link'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status4_icon'], 'UTF-8', 1, 0) . "\n";
	$sb_output_file .= $sbsanitize->displayText($_POST['status4_tbcol'], 'UTF-8', 1, 0) . "\n";
	
	// Locker le fichier pour qu'une seule personne a la fois ecrive dedans
	$result_edit = file_put_contents($sb_dashboard_file, $sb_output_file, FILE_USE_INCLUDE_PATH | LOCK_EX);
									 
		// Result Edit
		if ($result_edit) {
			// --- On ne vide pas les champs du formulaire
			// -------------------------------------------
			// --- Message SUCCES
			$sb_msg_valid = 'Dashboard modifié avec succès';
		} else {
			// --- Message ERROR
			$sb_msg_error = 'Error: Write Error (EDIT)!';
		}
}

// --------------------------------
// --- Ouverture du fichier
$sb_dashboard = file($sb_dashboard_file);
// --- Initialisation
$sb_dashboard_tables        = trim($sb_dashboard[0]);
$sb_dashboard_status1_table = trim($sb_dashboard[1]);
$sb_dashboard_status1_title = trim($sb_dashboard[2]);
$sb_dashboard_status1_link  = trim($sb_dashboard[3]);
$sb_dashboard_status1_icon  = trim($sb_dashboard[4]);
$sb_dashboard_status1_tbcol = trim($sb_dashboard[5]);
$sb_dashboard_status2_table = trim($sb_dashboard[6]);
$sb_dashboard_status2_title = trim($sb_dashboard[7]);
$sb_dashboard_status2_link  = trim($sb_dashboard[8]);
$sb_dashboard_status2_icon  = trim($sb_dashboard[9]);
$sb_dashboard_status2_tbcol = trim($sb_dashboard[10]);
$sb_dashboard_status3_table = trim($sb_dashboard[11]);
$sb_dashboard_status3_title = trim($sb_dashboard[12]);
$sb_dashboard_status3_link  = trim($sb_dashboard[13]);
$sb_dashboard_status3_icon  = trim($sb_dashboard[14]);
$sb_dashboard_status3_tbcol = trim($sb_dashboard[15]);
$sb_dashboard_status4_table = trim($sb_dashboard[16]);
$sb_dashboard_status4_title = trim($sb_dashboard[17]);
$sb_dashboard_status4_link  = trim($sb_dashboard[18]);
$sb_dashboard_status4_icon  = trim($sb_dashboard[19]);
$sb_dashboard_status4_tbcol = trim($sb_dashboard[20]);

// --- Debug SQL
if (_AM_SITE_DEBUG) $sbsmarty->assign('file_content', $sb_dashboard);						
// --------------------------------		
// --- Define variables
$formAction = $module_url . "&a=" . $formType;
// --- Form construct
$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
// --- Tables
$sbform->addInput('text', 'Tables SQL', array ('name' => 'tables', 'value' => "$sb_dashboard_tables", 'placeholder' => "Tables SQL"), true, false, "Nom exact de vos tables SQL concernant les modules dans votre administration séparés par des virgules sans espace");
// -----------------------------------
// All TABLES
// -----------------------------------
$sb_tables = explode(",", $sb_dashboard_tables);
// -----------------------------------
// --- Affichage du Select - STATUS 1
// -----------------------------------
$sbform->addBreak('STATUS 1');
$sbform->openSelect("Dashboard", array("id"=>"status1_table", "name"=>"status1_table"));
if ($sb_dashboard_status1_table == '') $sbform->addOption('Choisissez une table', array ("value"=>"", "selected"=>""));
for($i = 0; $i < count($sb_tables); $i++) {
	if ($sb_tables[$i] == $sb_dashboard_status1_table)
		$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i], "selected"=>""));
else
		$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i]));
}
// --- Close Select
$sbform->closeSelect();
// --- Table colonne
$sbform->addInput('text', 'Table colonne', array ('name' => 'status1_tbcol', 'value' => "$sb_dashboard_status1_tbcol", 'placeholder' => "Champs de votre table à afficher"), false, false, "Champs de la table que vous souhaitez afficher");
// --- Titre
$sbform->addInput('text', 'Titre', array ('name' => 'status1_title', 'value' => "$sb_dashboard_status1_title", 'placeholder' => "Titre"));
// --- Lien
$sbform->addInput('text', 'Lien (relatif de préférence)', array ('name' => 'status1_link', 'value' => "$sb_dashboard_status1_link", 'placeholder' => "Lien"));
// --- Icon
$sbform->addInput('text', "Icon <a title='Font-awesome' class='fa fa-question-circle sb-question-icon' target='_blank' href='./assets/samples/icons.html'></a>", array ('name' => 'status1_icon', 'value' => "$sb_dashboard_status1_icon", 'placeholder' => "Icône"), false, false, "Indiquer le nom en rouge de la font-awesome (ex: fa-<span style='color: red;'>comments</span>)");
// -----------------------------------
// --- Affichage du Select - STATUS 2
// -----------------------------------
$sbform->addBreak('STATUS 2');
$sbform->openSelect("Dashboard", array("id"=>"status2_table", "name"=>"status2_table"));
if ($sb_dashboard_status2_table == '') $sbform->addOption('Choisissez une table', array ("value"=>"", "selected"=>""));
for($i = 0; $i < count($sb_tables); $i++) {
	if ($sb_tables[$i] == $sb_dashboard_status2_table)
		$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i], "selected"=>""));
else
		$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i]));
}
// --- Close Select
$sbform->closeSelect();
// --- Table colonne
$sbform->addInput('text', 'Table colonne', array ('name' => 'status2_tbcol', 'value' => "$sb_dashboard_status2_tbcol", 'placeholder' => "Champs de votre table à afficher"), false, false, "Champs de la table que vous souhaitez afficher");
// --- Titre
$sbform->addInput('text', 'Titre', array ('name' => 'status2_title', 'value' => "$sb_dashboard_status2_title", 'placeholder' => "Titre"));
// --- Lien
$sbform->addInput('text', 'Lien (relatif de préférence)', array ('name' => 'status2_link', 'value' => "$sb_dashboard_status2_link", 'placeholder' => "Lien"));
// --- Icon
$sbform->addInput('text', "Icon <a title='Font-awesome' class='fa fa-question-circle sb-question-icon' target='_blank' href='./assets/samples/icons.html'></a>", array ('name' => 'status2_icon', 'value' => "$sb_dashboard_status2_icon", 'placeholder' => "Icône"), false, false, "Indiquer le nom en rouge de la font-awesome (ex: fa-<span style='color: red;'>comments</span>)");
// -----------------------------------
// --- Affichage du Select - STATUS 3
// -----------------------------------
$sbform->addBreak('STATUS 3');
$sbform->openSelect("Dashboard", array("id"=>"status3_table", "name"=>"status3_table"));
if ($sb_dashboard_status3_table == '') $sbform->addOption('Choisissez une table', array ("value"=>"", "selected"=>""));
for($i = 0; $i < count($sb_tables); $i++) {
	if ($sb_tables[$i] == $sb_dashboard_status3_table)
		$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i], "selected"=>""));
else
		$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i]));
}
// --- Close Select
$sbform->closeSelect();
// --- Table colonne
$sbform->addInput('text', 'Table colonne', array ('name' => 'status3_tbcol', 'value' => "$sb_dashboard_status3_tbcol", 'placeholder' => "Champs de votre table à afficher"), false, false, "Champs de la table que vous souhaitez afficher");
// --- Titre
$sbform->addInput('text', 'Titre', array ('name' => 'status3_title', 'value' => "$sb_dashboard_status3_title", 'placeholder' => "Titre"));
// --- Lien
$sbform->addInput('text', 'Lien (relatif de préférence)', array ('name' => 'status3_link', 'value' => "$sb_dashboard_status3_link", 'placeholder' => "Lien"));
// --- Icon
$sbform->addInput('text', "Icon <a title='Font-awesome' class='fa fa-question-circle sb-question-icon' target='_blank' href='./assets/samples/icons.html'></a>", array ('name' => 'status3_icon', 'value' => "$sb_dashboard_status3_icon", 'placeholder' => "Icône"), false, false, "Indiquer le nom en rouge de la font-awesome (ex: fa-<span style='color: red;'>comments</span>)");
// -----------------------------------
// --- Affichage du Select - STATUS 4
// -----------------------------------
$sbform->addBreak('STATUS 4');
$sbform->openSelect("Dashboard", array("id"=>"status4_table", "name"=>"status4_table"));
if ($sb_dashboard_status4_table == '')
	$sbform->addOption('Choisissez une table', array ("value"=>"", "selected"=>""));
else
	$sbform->addOption('Choisissez une table', array ("value"=>""));
for($i = 0; $i < count($sb_tables); $i++) {
	if ($sb_tables[$i] == $sb_dashboard_status4_table)
		$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i], "selected"=>""));
else
		$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i]));
}
// --- Close Select
$sbform->closeSelect();
// --- Table colonne
$sbform->addInput('text', 'Table colonne', array ('name' => 'status4_tbcol', 'value' => "$sb_dashboard_status4_tbcol", 'placeholder' => "Champs de votre table à afficher"), false, false, "Champs de la table que vous souhaitez afficher");
// --- Titre
$sbform->addInput('text', 'Titre', array ('name' => 'status4_title', 'value' => "$sb_dashboard_status4_title", 'placeholder' => "Titre"));
// --- Lien
$sbform->addInput('text', 'Lien (relatif de préférence)', array ('name' => 'status4_link', 'value' => "$sb_dashboard_status4_link", 'placeholder' => "Lien"));
// --- Icon
$sbform->addInput('text', "Icon <a title='Font-awesome' class='fa fa-question-circle sb-question-icon' target='_blank' href='./assets/samples/icons.html'></a>", array ('name' => 'status4_icon', 'value' => "$sb_dashboard_status4_icon", 'placeholder' => "Icône"), false, false, "Indiquer le nom en rouge de la font-awesome (ex: fa-<span style='color: red;'>comments</span>)");
// --- Hiddens / Buttons
$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
$sbform->addInput('reset', '', array('value' => "Reset"));
// --- Close Form
$sbform->closeForm ();


// ----------------------
// ASSIGN Settings
// ----------------------
$sbsmarty->assign('sb_dashboard_tables', trim($sb_dashboard_tables));
$sbsmarty->assign('sb_dashboard_status1_table', trim($sb_dashboard_status1_table));
$sbsmarty->assign('sb_dashboard_status1_title', trim($sb_dashboard_status1_title));
$sbsmarty->assign('sb_dashboard_status1_link', trim($sb_dashboard_status1_link));
$sbsmarty->assign('sb_dashboard_status1_icon', trim($sb_dashboard_status1_icon));
$sbsmarty->assign('sb_dashboard_status1_tbcol', trim($sb_dashboard_status1_tbcol));
$sbsmarty->assign('sb_dashboard_status2_table', trim($sb_dashboard_status2_table));
$sbsmarty->assign('sb_dashboard_status2_title', trim($sb_dashboard_status2_title));
$sbsmarty->assign('sb_dashboard_status2_link', trim($sb_dashboard_status2_link));
$sbsmarty->assign('sb_dashboard_status2_icon', trim($sb_dashboard_status2_icon));
$sbsmarty->assign('sb_dashboard_status2_tbcol', trim($sb_dashboard_status2_tbcol));
$sbsmarty->assign('sb_dashboard_status3_table', trim($sb_dashboard_status3_table));
$sbsmarty->assign('sb_dashboard_status3_title', trim($sb_dashboard_status3_title));
$sbsmarty->assign('sb_dashboard_status3_link', trim($sb_dashboard_status3_link));
$sbsmarty->assign('sb_dashboard_status3_icon', trim($sb_dashboard_status3_icon));
$sbsmarty->assign('sb_dashboard_status3_tbcol', trim($sb_dashboard_status3_tbcol));
$sbsmarty->assign('sb_dashboard_status4_table', trim($sb_dashboard_status4_table));
$sbsmarty->assign('sb_dashboard_status4_title', trim($sb_dashboard_status4_title));
$sbsmarty->assign('sb_dashboard_status4_link', trim($sb_dashboard_status4_link));
$sbsmarty->assign('sb_dashboard_status4_icon', trim($sb_dashboard_status4_icon));
$sbsmarty->assign('sb_dashboard_status4_tbcol', trim($sb_dashboard_status4_tbcol));

// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Dashboard');

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