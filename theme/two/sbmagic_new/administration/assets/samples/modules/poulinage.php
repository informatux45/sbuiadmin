<?php
/*********************************
 * Software Dollar Agency
 * Admin Startbootstrap
 * Module poulinage
 * 
 * @link http://www.dollar.fr/ 
 * 
 * @package ADMIN DOLLAR
 * @file UTF-8
 * © DOLLAR.FR
 ********************************/

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBMAGIC_PATH') or die('Are you crazy!');

 
// -----------------------
// Module URL
// -----------------------
$module_page = 'poulinage';
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
$table_mare = "acf_poulinage_mare";
$table_foal = "acf_poulinage_product";

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			$query[1] = "DELETE FROM $table_mare WHERE id = '$get_id'";
			$request  = $sbsql->query($query[1]);
			
			if ($request)
				$sb_msg_valid = 'Jument supprimée avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = array('Juments', 'Date', 'Pays', 'Père', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT * FROM $table_mare";
		$request  = $sbsql->query($query[0]);
		$result   = $sbsql->toarray($request);
		
		$sbsmarty->assign('all', $result);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query[0];
			if (isset($action) && $action == 'del') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query[1];
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
		$legend_add_edit = ($action == 'add') ? "Ajouter une jument" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id           = intval($_POST['id']);
			$name         = $sbsanitize->displayText($_POST['name'], 'UTF-8', 1, 0);
			$sire         = $sbsanitize->displayText($_POST['sire'], 'UTF-8', 1, 0);
			$sire_dam     = $sbsanitize->displayText($_POST['sire_dam'], 'UTF-8', 1, 0);
			$dob          = $sbsanitize->displayText($_POST['dob'], 'UTF-8', 1, 0);
			$country      = $sbsanitize->displayText($_POST['country'], 'UTF-8', 1, 0);
			$photo        = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$video        = $sbsanitize->displayText($_POST['video'], 'UTF-8', 1, 0);
			$pedigree     = $sbsanitize->displayText($_POST['pedigree'], 'UTF-8', 1, 0);
			$comment_mare = $sbsanitize->displayText($_POST['comment_mare'], 'UTF-8', 1, 0);
			$comment_prod = $sbsanitize->displayText($_POST['comment_prod'], 'UTF-8', 1, 0);
			$active       = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table_mare VALUES ('','$name','$sire','$sire_dam','$dob','$country','$photo','$video','$pedigree','$comment_mare','$comment_prod','$active','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$name = $sire = $sire_dam = $dob = $country = $photo = $video = $pedigree = $comment_mare = $comment_prod = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Jument ajoutée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {

				// UPDATE DATAS
				$query = "UPDATE $table_mare SET name = '$name'
											 ,sire = '$sire'
											 ,sire_dam = '$sire_dam'
											 ,dob = '$dob'
											 ,country = '$country'
											 ,photo = '$photo'
											 ,video = '$video'
											 ,pedigree = '$pedigree'
											 ,comment_mare = '$comment_mare'
											 ,comment_prod = '$comment_prod'
											 ,active = '$active'
											 WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Jument modifiée avec succès';
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
			$name = $sire = $sire_dam = $dob = $country = $photo = $video = $pedigree = $comment_mare = $comment_prod = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id           = intval($_GET['id']);
			$query[2]     = "SELECT * FROM $table_mare WHERE id = $id";
			$requestQ     = $sbsql->query($query[2]);
			$assoc        = $sbsql->assoc($requestQ);
			$name         = utf8_encode($assoc['name']);
			$sire         = utf8_encode($assoc['sire']);
			$sire_dam     = utf8_encode($assoc['sire_dam']);
			$dob          = $assoc['dob'];
			$country      = utf8_encode($assoc['country']);
			$photo        = $assoc['photo'];
			$video        = $assoc['video'];
			$pedigree     = $assoc['pedigree'];
			$comment_mare = utf8_encode($assoc['comment_mare']);
			$comment_prod = utf8_encode($assoc['comment_prod']);
			$active       = $assoc['active'];

			$sbsmarty->assign('assoc', $query[2]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[2]	 . "\n" . 'Form Type = '.$formType);						
		}
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activée', 'désactivée');
		$sbform->addInput('text', 'Nom', array ('name' => 'name', 'value' => "$name", 'placeholder' => "Nom de la jument"), true);
		$sbform->addInput('text', 'Père', array ('name' => 'sire', 'value' => "$sire", 'placeholder' => "Père de la jument"));
		$sbform->addInput('text', 'Père de mère', array ('name' => 'sire_dam', 'value' => "$sire_dam", 'placeholder' => "Père de mère"));
		$sbform->addInput('text', 'Année de naissance', array ('name' => 'dob', 'value' => "$dob", 'placeholder' => "Année de naissance"));
		$sbform->addInput('text', 'Pays de naissance', array ('name' => 'country', 'value' => "$country", 'placeholder' => "Pays de naissance"));
		// ----------------------------
		// --- Photo
		// ----------------------------
		$sbform->addInput('text', 'Photo', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo de la jument", "medias"=>"", 'icon' => 'photo'));
		// ----------------------------
		// -- Video
		// ----------------------------
		$sbform->addInput('text', 'Vidéo', array ('id' => 'video', 'name' => 'video', 'value' => "$video", 'placeholder' => "URL vidéo ( http:// )", 'icon' => 'video-camera'));
		// ----------------------------
		// --- Pedigree
		// ----------------------------
		$sbform->addInput('text', 'Pedigree', array ('id'=>'inputPedigree', 'name' => 'pedigree', 'value' => "$pedigree", 'placeholder' => "Pedigree", "medias"=>"", 'icon' => 'file-pdf-o'));
		// ----------------------------
		$sbform->addTextarea('Commentaire (Jument)', $comment_mare, array('name' => 'comment_mare', 'style' => 'height: 150px !important;'), false);
		$sbform->addTextarea('Commentaire (Production)', $comment_prod, array('name' => 'comment_prod', 'style' => 'height: 150px !important;'), false);
		// ----------------------------
		// --- Hiddens / Buttons
		// ----------------------------
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'edit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// ----------------------------
		// --- Close Form
		// ----------------------------
		$sbform->closeForm ();
		// ----------------------------
		
	break;

}


// ---------------------------------------------------
// ---------------------------------------------------
// IMPORTANT: Don't remove these lines
// ---------------------------------------------------
// ---------------------------------------------------
// ----------------------------------------
// ASSIGN Page TITLE - Modify this |
// ----------------------------------------
$sbsmarty->assign('page_title', 'Poulinage');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($name, 'UTF-8', 0, 1)));

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