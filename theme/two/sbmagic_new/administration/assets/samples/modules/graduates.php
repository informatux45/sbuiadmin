<?php
/*********************************
 * Software Dollar Agency
 * Admin Startbootstrap
 * Module GRADUATES
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
$module_page = 'graduates';
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
$table = "acf_graduates";

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			$query[2] = "DELETE FROM $table WHERE id = '$get_id'";
			$request  = $sbsql->query($query[2]);
			
			if ($request)
				$sb_msg_valid = 'Meilleur élève supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header_graduates = array('Meilleurs élèves', 'Père & Mère', 'Actions');
		$sbsmarty->assign('sb_table_header_graduates', $sb_table_header_graduates);
		
		// Contents table
		$query[0] = "SELECT * FROM $table";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('graduates_all', $result2);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query[0];
			if (isset($action) && $action == 'del') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query[2];
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
		$legend_add_edit = ($action == 'add') ? "Ajouter un meilleur élève" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id            = intval($_POST['id']);
			$horsename     = $sbsanitize->displayText($_POST['horsename'], 'UTF-8', 1, 0);
			$sire_dam_info = $sbsanitize->displayText($_POST['sire_dam_info'], 'UTF-8', 1, 0);
			$breeder       = $sbsanitize->displayText($_POST['breeder'], 'UTF-8', 1, 0);
			$sale          = $sbsanitize->displayText($_POST['sale'], 'UTF-8', 1, 0);
			$photo         = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$active        = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			$perf_1        = $sbsanitize->displayText($_POST['perf_1'], 'UTF-8', 1, 0);
			$video_1       = $sbsanitize->displayText($_POST['video_1'], 'UTF-8', 1, 0);
			$perf_2        = $sbsanitize->displayText($_POST['perf_2'], 'UTF-8', 1, 0);
			$video_2       = $sbsanitize->displayText($_POST['video_2'], 'UTF-8', 1, 0);
			$perf_3        = $sbsanitize->displayText($_POST['perf_3'], 'UTF-8', 1, 0);
			$video_3       = $sbsanitize->displayText($_POST['video_3'], 'UTF-8', 1, 0);
			$perf_4        = $sbsanitize->displayText($_POST['perf_4'], 'UTF-8', 1, 0);
			$video_4       = $sbsanitize->displayText($_POST['video_4'], 'UTF-8', 1, 0);
			$perf_5        = $sbsanitize->displayText($_POST['perf_5'], 'UTF-8', 1, 0);
			$video_5       = $sbsanitize->displayText($_POST['video_5'], 'UTF-8', 1, 0);
			
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table VALUES ('','$horsename','$sire_dam_info','$photo','$perf_1','$video_1','$perf_2','$video_2','$perf_3','$video_3','$perf_4','$video_4','$perf_5','$video_5','$breeder','$sale','$active','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$horsename = $sire_dam_info = $photo = $perf_1 = $video_1 = $perf_2 = $video_2 = $perf_3 = $video_3 = $perf_4 = $video_4 = $perf_5 = $video_5 = $breeder = $sale = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Meilleur élève ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {

				// UPDATE DATAS
				$query = "UPDATE $table SET name = '$horsename'
											 ,sire_dam_info = '$sire_dam_info'
											 ,photo = '$photo'
											 ,perf_1 = '$perf_1'
											 ,video_1 = '$video_1'
											 ,perf_2 = '$perf_2'
											 ,video_2 = '$video_2'
											 ,perf_3 = '$perf_3'
											 ,video_3 = '$video_3'
											 ,perf_4 = '$perf_4'
											 ,video_4 = '$video_4'
											 ,perf_5 = '$perf_5'
											 ,video_5 = '$video_5'
											 ,breeder = '$breeder'
											 ,sale = '$sale'
											 ,active = '$active'
											 WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Meilleur élève modifié avec succès';
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
			$horsename = $sire_dam_info = $photo = $perf_1 = $video_1 = $perf_2 = $video_2 = $perf_3 = $video_3 = $perf_4 = $video_4 = $perf_5 = $video_5 = $breeder = $sale = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id            = intval($_GET['id']);
			$query[1]      = "SELECT * FROM $table WHERE id = $id";
			$requestQ      = $sbsql->query($query[1]);
			$assoc         = $sbsql->assoc($requestQ);
			$horsename     = utf8_encode($assoc['name']);
			$sire_dam_info = utf8_encode($assoc['sire_dam_info']);
			$breeder       = utf8_encode($assoc['breeder']);
			$sale          = utf8_encode($assoc['sale']);
			$photo         = $assoc['photo'];
			$active        = $assoc['active'];
			for($i = 1; $i < 6; $i++) {
				$perf_{$i}  = utf8_encode($assoc['perf_'.$i]);
				$video_{$i} = $assoc['video_'.$i];
			}
			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		}
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		$sbform->addInput('text', 'Nom', array ('name' => 'horsename', 'value' => "$horsename", 'placeholder' => "Cheval"), true);
		$sbform->addInput('text', 'Origine', array ('name' => 'sire_dam_info', 'value' => "$sire_dam_info", 'placeholder' => "père & mère & père de mère"));
		$sbform->addInput('text', 'Entraîneur', array ('name' => 'breeder', 'value' => "$breeder", 'placeholder' => "Entraîneur"));
		$sbform->addInput('text', 'Propriétaire', array ('name' => 'sale', 'value' => "$sale", 'placeholder' => "Propriétaire"));
		// --------------------------------	
		// --- Photo
		// --------------------------------	
		$sbform->addInput('text', 'Photo', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo", "medias"=>"", 'icon' => 'photo'));
		// --------------------------------
		// Performances / videos
		// --------------------------------	
		for($i = 1; $i < 6; $i++) {
			$sbform->addInput('text', "Performance ($i)", array("name"=>"perf_$i", "value"=>$perf_{$i}, "placeholder"=>"Titre performance $i"));
			$sbform->addInput('text', "Vidéo URL ($i)", array("name"=>"video_$i", "value"=>$video_{$i}, "placeholder"=>"Vidéo $i ( http:// )", 'icon' => 'video-camera'));
		}
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

	case "sort":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$formName        = "sort_form";
		$formType        = "sort";
		$btn_add_edit    = "Valider";
		$legend_add_edit = "Trier les meilleurs élèves";
		// --------------------------------
		if ($_POST['drag']) {
			// --------------------------------
			// --- Control form submit --------
			// --------------------------------
			$sb_toSort = $_POST['drag'];
			
			// reorganizes the order of elements
			$sql_error = 0;
			for ($i = 0; $i < count($sb_toSort); $i++) {  
				$query_sort  = "UPDATE $table SET sort = $i WHERE id = " . $sb_toSort[$i];
				$result_sort = $sbsql->query($query_sort);
				if (!$result_sort) {
					// --- Error Database
					$sql_error++;
				}
				if (_AM_SITE_DEBUG) $sbsmarty->append('sbdebugsql', $query_sort);
			}
			// Check result
			if ($sql_error < 1) {
				// --- Message SUCCES
				$sb_msg_valid = 'Les meilleurs élèves ont été trié avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$id            = intval($_GET['id']);
		$query[3]      = "SELECT * FROM $table WHERE active = '1' ORDER BY sort ASC";
		$requestQ      = $sbsql->query($query[3]);
		$sort_array    = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = utf8_encode($sort['name']);
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[3]	 . "\n" . 'Form Type = '.$formType);
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('graduates_sort', true);
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
$sbsmarty->assign('page_title', 'Nos meilleurs élèves');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($horsename, 'UTF-8', 0, 1)));

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