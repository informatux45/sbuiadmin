<?php
/**
 * Admin Startbootstrap
 * SANDBOX
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
// Module URL
// -----------------------
$module_page = 'sandbox';
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

$table = _AM_DB_PREFIX ."sb_sandbox";

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
				$sb_msg_valid = 'Enregistrement supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = array('Nom', 'Telephone', 'Email', 'Entreprise', 'Pays', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT * FROM $table";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('allsandbox', $result2);
		
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
		$legend_add_edit = ($action == 'add') ? "Ajouter un enregistrement" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
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
					$sb_msg_valid = 'Enregistrement ajouté avec succès';
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
					$sb_msg_valid = 'Enregistrement modifié avec succès';
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
		// ----------------------------
		// --- Radio Y - N
		// ----------------------------
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif (Radio YN)', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Input TEXT
		// ----------------------------
		$sbform->addInput('text', 'Nom (Input TEXT)', array ('name' => 'yourname', 'value' => "$yourname", 'placeholder' => "Votre nom"), true);
		// ----------------------------
		// --- Input TEXT with icon / icon2
		// ----------------------------
		$sbform->addInput('text', 'Nom (2 icônes)', array ('name' => 'montant', 'value' => "$montant", 'placeholder' => "Votre montant", 'icon' => 'euro', 'icon2' => '.00'), true);
		// ----------------------------
		// --- Input TEXT with text instead of icon
		// ----------------------------
		$sbform->addInput('text', "Texte à la place de l'icône", array ('name' => 'seo_url', 'value' => "$seo_url", 'placeholder' => "Votre url", 'icon' => '0Url du site'), false);
		// ----------------------------
		// --- BREAK
		// ----------------------------
		$sbform->addBreak('Separation');
		// ----------------------------
		// --- Input COUNTRY
		// ----------------------------		
		$sbform->addCountry('Pays', array('id' => 'country', 'name' => 'country', 'value' => $country, 'style' => 'width: auto;'), true, 'Choisissez un pays');
		// ----------------------------
		// --- Input DATE (calendar)
		// ----------------------------
		$sbform->addDate('Date de naissance (Calendar)', array('id'=>'dob', 'name'=>'dob', 'value'=>$birth), true);
		
		$sbform->addColor ('Couleur (Color PICKER)', array('id' => 'color', 'name' => 'name'), false);
		// ----------------------------
		// --- Pdf only (width popup medias)
		// You can add more exts separate by coma
		// ex: "extension" => "pdf"
		// ex: "extension" => "pdf,xml,gif"
		// ----------------------------
		$sbform->addInput('text', 'Pdf only (Width popup MEDIAS)', array ('id'=>'inputPdf', 'name' => 'pdf', 'value' => "$photo", 'placeholder' => "Votre pdf", "medias"=>"", "extension" => "pdf", 'icon' => 'file-pdf-o'), false);
		// ----------------------------
		// --- Photo (width popup medias)
		// ----------------------------
		$sbform->addInput('text', 'Photo (Width popup MEDIAS)', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Votre photo", "medias"=>"", 'icon' => 'photo'), false);
		// ----------------------------
		// --- Photo (width popup medias in SUBDIR)
		// ----------------------------
		$sbform->addInput('text', 'Photo (Width popup MEDIAS in SUBDIR)', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Votre photo", "medias"=>"", 'icon' => 'photo', "dir" => _AM_MEDIAS_DIR . "/new", "subdir" => "new"), false);
		// ----------------------------
		// --- Photo (width popup medias in SUBDIR AND Limit files to display)
		// ----------------------------
		$sbform->addInput('text', 'Photo (Width popup MEDIAS in SUBDIR AND Limit files display to 10)', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Votre photo", "medias"=>"", 'icon' => 'photo', "dir" => _AM_MEDIAS_DIR . "/new", "subdir" => "new", "limitfiles" => 10), false);
		// ----------------------------
		// -- Video (without popup media)
		// ----------------------------
		$sbform->addInput('text', 'Vidéo (URL) - (Without popup MEDIAS)', array ('id' => 'video', 'name' => 'video', 'value' => "$country", 'placeholder' => "URL de votre vidéo ( http:// )", 'icon' => 'video-camera'), true);
		// ----------------------------
		// -- Input CHECKBOX
		// ----------------------------
		$tab_check = array();
		$tab_check[0]['text']    = 'Option 1';
		$tab_check[0]['name']    = 'option_one';
		$tab_check[0]['checked'] = ($option_one == 1) ? '1' : '0';
		$tab_check[1]['text']    = 'Option 2';
		$tab_check[1]['name']    = 'option_two';
		$tab_check[1]['checked'] = ($option_two == 1) ? '1' : '0';
		$tab_check[2]['text']    = 'Option 3';
		$tab_check[2]['name']    = 'option_three';
		$tab_check[2]['checked'] = ($option_three == 1) ? '1' : '0';
		$sbform->addCheckbox('Toutes vos options', $tab_check, '', false, '<br />');
		// ----------------------------
		// -- Input RADIO
		// ----------------------------
		$tab_type  = [];
		$tab_types = array( ['id' => '1', 'title' => 'Option 1'],
						    ['id' => '2', 'title' => 'Option 2'],
						    ['id' => '3', 'title' => 'Option 3'],
						   );
		foreach($tab_types as $key => $val) {
			if ($type == $val['id'])
				$type_checked = $val['id'];
			$tab_type[$key]['text']  = $val['title'];
			$tab_type[$key]['value'] = $val['id'];
		}
		$sbform->addRadio ('Choisissez un type', $tab_type, array('id'=>'type', 'name'=>'type', 'checked'=>"$type_checked"), true, '<br>');
		// -----------------------------------
		// --- Affichage du Select - STATUS 2
		// -----------------------------------
		$sb_tables = array('Selection 1','Selection 2','Selection 3');
		$sbform->openSelect("Votre selection", array("id"=>"selection", "name"=>"selection"));
		if ($selection_table == '') $sbform->addOption('Choisissez une table', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($sb_tables); $i++) {
			if ($sb_tables[$i] == $selection_table)
				$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i], "selected"=>""));
		else
				$sbform->addOption($sb_tables[$i], array ("value"=>$sb_tables[$i]));
		}
		// --- Close Select
		$sbform->closeSelect();
		// ----------------------------
		// -- Input TEXTAREA
		// ----------------------------		
		$sbform->addTextarea('Editeur (TEXTAREA)', $comment, array('id' => 'comment', 'name' => 'comment', 'style' => 'height: 150px !important;'), false);
		// ----------------------------
		// -- Input TEXTAREA HTML (Ckeditor FULL)
		// ----------------------------		
		$sbform->addTextareaHTML('Editeur HTML (CKEDITOR Full)', $comment_ckeditor1, array('id' => 'comment_editor1', 'name' => 'comment_editor1'), false);
		// --- Basic
		$sbform->addTextareaHTML('Editeur HTML (CKEDITOR Basic)', $comment_ckeditor2, array('id' => 'comment_editor2', 'name' => 'comment_editor2'), false, 'basic');
		// --- Simple
		$sbform->addTextareaHTML('Editeur HTML (CKEDITOR Simple)', $comment_ckeditor3, array('id' => 'comment_editor3', 'name' => 'comment_editor3'), false, 'simple');
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
		$legend_add_edit = "Trier les enregistrements";
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
				$sb_msg_valid = 'Les enregistrements ont été trié avec succès';
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
			$toSort[$sort_id] = utf8_encode($sort['nom']);
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
		$sbsmarty->assign('sort', true);
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
$sbsmarty->assign('page_title', 'SANDBOX');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($horsename, 'UTF-8', 0, 1)));

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

// ----------------------
// CLOSE SQL (if open)
// ----------------------
$sbsql->close();

?>
