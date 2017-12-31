<?php
/*********************************
 * Software Dollar Agency
 * Admin Startbootstrap
 * Module foal
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
defined('SBUIADMIN_PATH') or die('Are you crazy!');

 
// -----------------------
// Module URL
// -----------------------
$module_page = 'foal';
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
$table_mare = "acf_poulinage_mare";
$table_foal = "acf_poulinage_product";

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			$query[1] = "DELETE FROM $table_foal WHERE id = '$get_id'";
			$request  = $sbsql->query($query[1]);
			
			if ($request)
				$sb_msg_valid = 'Produit supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = array('Mère', 'Terme', 'Date de naissance', 'Père', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT t1.*, t2.id as t2id, t2.name FROM $table_foal AS t1, $table_mare AS t2 WHERE t1.mare = t2.id";
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
		$legend_add_edit = ($action == 'add') ? "Ajouter un produit" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id           = intval($_POST['id']);
			$active       = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			$mare         = intval($_POST['mare']);
			$stallion_n_1 = $sbsanitize->displayText($_POST['stallion_n_1'], 'UTF-8', 1, 0);
			$term         = $sbsanitize->displayText($_POST['term'], 'UTF-8', 1, 0);
			$birth        = $sbsanitize->displayText($_POST['birth'], 'UTF-8', 1, 0);
			$sex          = $sbsanitize->displayText($_POST['sex'], 'UTF-8', 1, 0);
			$colour       = $sbsanitize->displayText($_POST['colour'], 'UTF-8', 1, 0);
			$stallion_n   = $sbsanitize->displayText($_POST['stallion_n'], 'UTF-8', 1, 0);
			$photo        = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$video        = $sbsanitize->displayText($_POST['video'], 'UTF-8', 1, 0);
			$pdf          = $sbsanitize->displayText($_POST['pdf'], 'UTF-8', 1, 0);

			
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table_foal VALUES ('','$mare','$stallion_n_1','$term','$birth','$sex','$colour','$stallion_n','$photo','$video','$pdf','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$mare = $stallion_n_1 = $term = $birth = $sex = $colour = $stallion_n = $photo = $video = $pdf = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Produit ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {

				// UPDATE DATAS
				$query = "UPDATE $table_foal SET mare = '$mare'
											 ,stallion_n = '$stallion_n'
											 ,term = '$term'
											 ,birth = '$birth'
											 ,sex = '$sex'
											 ,colour = '$colour'
											 ,stallion_n_1 = '$stallion_n_1'
											 ,photo = '$photo'
											 ,video = '$video'
											 ,pdf = '$pdf'
											 ,active = '$active'
											 WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Produit modifié avec succès';
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
			$mare = $stallion_n_1 = $term = $birth = $sex = $colour = $stallion_n = $photo = $video = $pdf = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id           = intval($_GET['id']);
			$query[2]     = "SELECT * FROM $table_foal WHERE id = $id";
			$requestQ     = $sbsql->query($query[2]);
			$assoc        = $sbsql->assoc($requestQ);
			$mare         = intval($assoc['mare']);
			$stallion_n   = utf8_encode($assoc['stallion_n']);
			$term         = utf8_encode($assoc['term']);
			$birth        = utf8_encode($assoc['birth']);
			$sex          = utf8_encode($assoc['sex']);
			$colour       = utf8_encode($assoc['colour']);
			$stallion_n_1 = utf8_encode($assoc['stallion_n_1']);
			$photo        = $assoc['photo'];
			$video        = $assoc['video'];
			$pdf          = $assoc['pdf'];
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
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// --------------------------------
		// --- Nom de la mère
		// --------------------------------
		// --- Extraction de toutes les juments
		$sbsql->free();
		$query[3] = "SELECT id,name FROM $table_mare ORDER BY name ASC";
		$mares    = $sbsql->query($query[3]);
		$mares    = $sbsql->toarray($mares);

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[3]);	

		// --- Affichage du Select
		$sbform->openSelect("Nom de la mère", array("id"=>"mare", "name"=>"mare"), true);
		if (!is_numeric($mare)) $sbform->addOption('Choisissez une jument', array ("value"=>"", "selected"=>""));
		foreach($mares as $row) {
			if ($mare == $row["id"])
				$sbform->addOption($row["name"], array ("value"=>$row["id"], "selected"=>""));
		else
				$sbform->addOption($row["name"], array ("value"=>$row["id"]));
		}
		// --- Close Select
		$sbform->closeSelect();
		// --------------------------------
		$sbform->addInput('text', 'Etalon N '.$saillie_n, array ('name' => 'stallion_n', 'value' => "$stallion_n", 'placeholder' => "Etalon N"));
		$sbform->addInput('text', 'Etalon N-1 '.$saillie_n_1, array ('name' => 'stallion_n_1', 'value' => "$stallion_n_1", 'placeholder' => "Etalon N-1"));
		$sbform->addDate('Date du terme', array('id'=>'term', 'name'=>'term', 'value'=>$term));
		$sbform->addDate('Date de naissance', array('id'=>'birth', 'name'=>'birth', 'value'=>$birth));
		$sbform->addInput('text', 'Sexe', array ('name' => 'sex', 'value' => "$sex", 'placeholder' => "Sexe du poulain"));
		$sbform->addInput('text', 'Robe', array ('name' => 'colour', 'value' => "$colour", 'placeholder' => "Robe du poulain"));
		// ----------------------------
		// --- Photo
		// ----------------------------
		$sbform->addInput('text', 'Photo', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo du poulain", "medias"=>"", 'icon' => 'photo'));
		// ----------------------------
		// -- Video
		// ----------------------------
		$sbform->addInput('text', 'Vidéo (URL)', array ('id' => 'video', 'name' => 'video', 'value' => "$country", 'placeholder' => "URL de la vidéo ( http:// )", 'icon' => 'video-camera'));
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