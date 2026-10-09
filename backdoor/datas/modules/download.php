<?php
/**
 * Admin Startbootstrap
 * Manage DOWNLOAD (downloads file)
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
// Module URL
// -----------------------
$module_page = 'download';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Include Config CMS
// -----------------------
include_once('../sbconfig.php');
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// -----------------------
// Get Multilang Option
// -----------------------
$getMultilang = (sbGetConfig('multilang')) ? sbGetConfig('multilang') : false;

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------
$table = _AM_DB_PREFIX . "sb_download";
$text  = "Telechargement";

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id  = intval($_GET['id']);
			$query_2 = "DELETE FROM $table WHERE id = '$get_id'";
			$request = $sbsql->query($query_2);
			
			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = ['Titre', 'Taille', 'Téléchargé', 'Shortcode', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query    = "SELECT * FROM $table";
		$request2 = $sbsql->query($query);
		$result2  = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('alldownload', $result2);
		
		// ----------------------------------------
		// --- Download infos
		// ----------------------------------------
		$query_download_total   = "SELECT SUM(downloaded) AS total_of_download FROM $table";
		$request_download_total = $sbsql->query($query_download_total);
		$numrows_download_total = $sbsql->assoc($request_download_total);
		$sbsmarty->assign('total_downloads', $numrows_download_total['total_of_download']);
		// ----------------------------------------
		$query_download_active   = "SELECT id FROM $table WHERE active = '1'";
		$request_download_active = $sbsql->query($query_download_active);
		$numrows_download_active = $sbsql->numrows();
		$sbsmarty->assign('total_download_active', $numrows_download_active);
		// ----------------------------------------
		$query_download_inactive   = "SELECT id FROM $table WHERE active = '0'";
		$request_download_inactive = $sbsql->query($query_download_inactive);
		$numrows_download_inactive = $sbsql->numrows();
		$sbsmarty->assign('total_download_inactive', $numrows_download_inactive);
		// ----------------------------------------
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query;
			if (isset($action) && $action == 'del') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query_2;
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}
		
	break;
	
	case "add":
	case "edit":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = ($action == 'add') ? "add_form" : "edit_form";
		$formType        = ($action == 'add' || $_POST['form_submit'] == 'add_form') ? "add" : "edit";
		$btn_add_edit    = ($action == 'add') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'add') ? "Ajouter un " . strtolower($text) : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id             = intval($_POST['id']);
			// --- Titre
			$title_fr       = $sbsanitize->displayText($_POST['title_fr'], 'UTF-8', 1, 0);
			$title          = "[fr]".$title_fr."[/fr]";
			if ($getMultilang) {
				$title_en   = $sbsanitize->displayText($_POST['title_en'], 'UTF-8', 1, 0);
				$title     .= "[en]".$title_en."[/en]";				
			}
			// --- Desc Full
			$description_fr = $sbsanitize->displayText($_POST['description_fr'], 'UTF-8', 1, 0);
			$description    = "[fr]".$description_fr."[/fr]";
			if ($getMultilang) {
				$description_en = $sbsanitize->displayText($_POST['description_en'], 'UTF-8', 1, 0);
				$description   .= "[en]".$description_en."[/en]";				
			}
			$size           = $sbsanitize->displayText($_POST['size'], 'UTF-8', 1, 0);
			$filename       = $sbsanitize->displayText($_POST['filename'], 'UTF-8', 1, 0);
			$active         = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

	
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$randkey = sbGenerateRandKey();
				$query   = "INSERT INTO $table (title,description,size,filename,randkey,active)
						    VALUES ('$title','$description','$size','$filename','$randkey','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$title_fr = $description_fr = $title_en = $description_en = $filename = $size = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET title = '$title'
										   ,description = '$description'
										   ,size = '$size'
										   ,filename = '$filename'
										   ,active = '$active'
										WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = $text . ' modifié avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		} else {
			// Si AJOUT (First time)
			// --- Vider les champs du formulaire
			$title_fr = $description_fr = $title_en = $description_en = $filename = $downloaded = $size = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id             = intval($_GET['id']);
			$query_1        = "SELECT * FROM $table WHERE id = $id";
			$requestQ       = $sbsql->query($query_1);
			$assoc          = $sbsql->assoc($requestQ);
			// ----------------------------
			$title_fr       = $sbsanitize->displayLang(sb_utf8_encode($assoc['title']));
			$description_fr = $sbsanitize->displayLang(sb_utf8_encode($assoc['description']));
			// ----------------------------
			$title_en       = $sbsanitize->displayLang(sb_utf8_encode($assoc['title']), 'en');
			$description_en = $sbsanitize->displayLang(sb_utf8_encode($assoc['description']), 'en');
			// ----------------------------
			$filename       = sb_utf8_encode($assoc['filename']);
			$size           = sb_utf8_encode($assoc['size']);
			$active         = $assoc['active'];			

			$sbsmarty->assign('assoc', $query_1);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_1 . "\n" . 'Form Type = '.$formType);						
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- File only (width popup medias)
		// You can add more exts separate by coma
		// ex: "extension" => "pdf"
		// ex: "extension" => "pdf,xml,gif"
		// ----------------------------
		$sbform->addInput('text', 'Fichiers compressés (ZIP, TAR.GZ, RAR, ...)', array ('id'=>'inputFiles', 'name' => 'filename', 'value' => "$filename", 'placeholder' => "Fichier à télécharger", "medias"=>"", "extension" => "zip,rar,gz", 'icon' => 'upload'), true);
		// ----------------------------
		// --- Taille
		// ----------------------------
		$sbform->addInput('text', "Taille du fichier", array ('name' => 'size', 'value' => "$size", 'placeholder' => "Ex: 2.5 MB", 'icon' => 'compress'), false, false, "Si vous indiquez une taille, celle-ci sera affichée directement sur le bouton, il n'y aura pas de calcul de la taille du fichier");
		// ----------------------------
		// --- Titre
		// ----------------------------
		$title_fr_title = ($getMultilang) ? 'Nom du fichier (FR)' : 'Nom du fichier' ;
		$sbform->addInput('text', "$title_fr_title", array ('name' => 'title_fr', 'value' => "$title_fr", 'placeholder' => "Nom du fichier"), true);
		if ($getMultilang)
			$sbform->addInput('text', "$title_fr_title (EN)", array ('name' => 'title_en', 'value' => "$title_en", 'placeholder' => "Nom du fichier (EN)"), true);
		// ----------------------------
		// --- Description
		// ----------------------------
		$description_fr_title = ($getMultilang) ? 'Description (FR)' : 'Description' ;
		$sbform->addTextareaHTML("$description_fr_title", $description_fr, array('id' => 'description_fr', 'name' => 'description_fr'), false);
		if ($getMultilang)
			$sbform->addTextareaHTML("$description_fr_title (EN)", $description_en, array('id' => 'description_en', 'name' => 'description_en'), false);
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'edit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
	break;

	// --------------------------------------------------------------------
	// Pas de case "settings" ici : le module Téléchargements n'a aucune table
	// de réglages. La version d'origine en portait un, copié tel quel depuis
	// le module Actualités ($table_settings, catid, other_news... jamais
	// définis) : il n'était atteignable par aucun lien et aurait planté.
	// --------------------------------------------------------------------
	
}


// ---------------------------------------------------
// ---------------------------------------------------
// IMPORTANT: Don't remove these lines
// ---------------------------------------------------
// ---------------------------------------------------
// ----------------------------------------
// ASSIGN Page TITLE - Modify this |
// ----------------------------------------
$sbsmarty->assign('page_title', 'Téléchargements');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf((string) ($legend_add_edit ?? ''), $sbsanitize->displayText($sbsanitize->displayLang($title_fr), 'UTF-8', 0, 1)));

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);
// --- Second submit Button
$sbsmarty->assign('sb_form_id', $formName);
$sbsmarty->assign('sb_form_submit_value', $btn_add_edit);

// ----------------------
// CLOSE SQL
// ----------------------
$sbsql->close();

?>