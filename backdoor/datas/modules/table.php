<?php
/**
 * Admin Startbootstrap
 * TABLE Module
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
$module_page = 'table';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBMAGIC_URL . SBMAGIC_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
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
$table           = _AM_DB_PREFIX . "sb_table";
$table_structure = _AM_DB_PREFIX . "sb_table_structure";
$table_datas     = _AM_DB_PREFIX . "sb_table_datas";
$text            = "Tableau";

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE table
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			$query[2] = "DELETE FROM $table WHERE id = '$get_id'";
			$request  = $sbsql->query($query[2]);
			// --- Supression de la structure
			$query_del_structure   = "DELETE FROM $table_structure WHERE tid = '$get_id'";
			$request_del_structure = $sbsql->query($query_del_structure);
			// --- Suppression des données
			$query_del_datas   = "DELETE FROM $table_datas WHERE tid = '$get_id'";
			$request_del_datas = $sbsql->query($query_del_datas);
			
			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès (ses données et sa structure également)';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = ['Nom', 'Shortcode', "Type d'affichage", 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT * FROM $table";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('alltable', $result2);
		
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
		$legend_add_edit = ($action == 'add') ? "Ajouter un " . strtolower($text) : "Modifier le " . strtolower($text);
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id     = intval($_POST['id']);
			$name   = $sbsanitize->displayText($_POST['name'], 'UTF-8', 1, 0);
			$type   = $sbsanitize->displayText($_POST['type'], 'UTF-8', 1, 0);
			$active = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
	
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (name,type,active)
						  VALUES ('$name', '$type', '$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$name = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET name = '$name'
										   ,active = '$active'
										   ,type = '$type'
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
			$name = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id']);
			$query[1] = "SELECT * FROM $table WHERE id = $id";
			$requestQ = $sbsql->query($query[1]);
			$assoc    = $sbsql->assoc($requestQ);
			$name     = utf8_encode($assoc['name']);
			$type     = utf8_encode($assoc['type']);
			$active   = $assoc['active'];

			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// --------------------------------
		// --- Nom du TABBS
		// --------------------------------		
		$sbform->addInput('text', 'Nom', array ('name' => 'name', 'value' => "$name", 'placeholder' => "Nom du tableau"), true);
		// -----------------------------------
		// --- Type d'affichage
		// -----------------------------------
		$table_type = array('option1','option2');
		$sbform->openSelect("Votre selection", array("id"=>"type", "name"=>"type"));
		if ($type == '') $sbform->addOption("Choisissez un type d'affichage", array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($table_type); $i++) {
			if ($table_type[$i] == $type)
				$sbform->addOption($table_type[$i], array ("value"=>$table_type[$i], "selected"=>""));
		else
				$sbform->addOption($table_type[$i], array ("value"=>$table_type[$i]));
		}
		// --- Close Select
		$sbform->closeSelect();
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

	case "editfield":
	case "delfield":
		// Action DELETE Fields table (structure)
		if ($action == 'delfield') {
			$get_id   = intval($_GET['id']);
			$query[2] = "DELETE FROM $table_structure WHERE id = '$get_id'";
			$request  = $sbsql->query($query[2]);
			
			if ($request)
				$sb_msg_valid = 'Colonne de tableau supprimée avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
				
			$get_id = false;
		}
		
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = "edit_form";
		$formType        = "editfield";
		$btn_add_edit    = ($_GET['id'] > 0 && $action != 'delfield') ? "Modifier" : "Ajouter";
		$legend_add_edit = "Ajouter / Modifier les colonnes du ".strtolower($text)." &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// Contents table
		$tid             = intval($_GET['tid']); // Table ID
		$query_table     = "SELECT * FROM $table WHERE id = '$tid'";
		$request_table   = $sbsql->query($query_table);
		$result_table    = $sbsql->object($request_table);
		$tabname         = $result_table->name;
		$sbsmarty->assign('tabname', $tabname);

		if ($_POST['form_submit']) {

			// Injection des données
			$id           = intval($_POST['id']);
			$title        = $sbsanitize->displayText($_POST['title'], 'UTF-8', 1, 0);
			$field_type   = $sbsanitize->displayText($_POST['field_type'], 'UTF-8', 1, 0);
			$field_target = $sbsanitize->displayText($_POST['field_target'], 'UTF-8', 1, 0);
			$active       = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

			// ADD or EDIT
			if ($formType == 'editfield' && !$id) {
				// INSERT DATAS
				$query = "INSERT INTO $table_structure (tid,title,field_type,field_target,active,sort)
						  VALUES ('$tid','$title','$field_type','$field_target','$active','99')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$tid = $title = $field_type = $field_target = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Colonne du tableau ajoutée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'editfield' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_structure SET title = '$title'
													 ,field_type = '$field_type'
													 ,field_target = '$field_target'
													 ,active = '$active'
													 WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Colonne du tableau modifiée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		}
		// --------------------------------
		if ($formType == 'editfield' && !$_POST['form_submit'] && $_GET['id']) {
			// --- Recuperation des donnees
			$id           = intval($_GET['id']);
			$query[1]     = "SELECT * FROM $table_structure WHERE id = $id";
			$requestQ     = $sbsql->query($query[1]);
			$assoc        = $sbsql->assoc($requestQ);
			$tid          = intval($assoc['tid']);
			$title        = $sbsanitize->displayLang(utf8_encode($assoc['title']));
			$field_type   = utf8_encode($assoc['field_type']);
			$field_target = utf8_encode($assoc['field_target']);
			$active       = $assoc['active'];

			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		} else {
			// --- Vider les champs du formulaire
			$tid = $title = $field_type = $field_target = $active = '';
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&tid=" . intval($_GET['tid']);
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active == '') ? '1' : $active;
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Titre
		// ----------------------------
		$sbform->addInput('text', "Titre", array ('name' => 'title', 'value' => "$title", 'placeholder' => "Titre de la colonne"), true);
		// ----------------------------
		// --- Liste des types de champs
		// ----------------------------
		$table_type = ['date', 'text', 'textarea', 'textareaHTML', 'photo', 'link_image', 'link_video', 'link_file'];
		$sbform->openSelect("Type de champs", array("id"=>"field_type", "name"=>"field_type"), true);
		if ($field_type == '') $sbform->addOption('Choisissez un type de champs', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($table_type); $i++) {
			if ($table_type[$i] == $field_type)
				$sbform->addOption($table_type[$i], array ("value"=>$table_type[$i], "selected"=>""));
		else
				$sbform->addOption($table_type[$i], array ("value"=>$table_type[$i]));
		}
		$sbform->closeSelect();
		// ----------------------------
		// --- Liste des targets de champs
		// ----------------------------
		$table_target = ['blank', 'lightbox', 'lightbox_fancy'];
		$sbform->openSelect("Target de champs", array("id"=>"field_target", "name"=>"field_target"), false);
		if ($field_target == '') $sbform->addOption('Choisissez une target de champs', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($table_target); $i++) {
			if ($table_target[$i] == $field_target)
				$sbform->addOption($table_target[$i], array ("value"=>$table_target[$i], "selected"=>""));
		else
				$sbform->addOption($table_target[$i], array ("value"=>$table_target[$i]));
		}
		$sbform->closeSelect();
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('hidden', '', array('name' => 'tid', 'value' => "{$_GET[tid]}"));
		if ($formType == 'editfield' && $id > 0 && $action != 'delfield') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		
		// --------------------------------		
		// Contents table
		// --------------------------------
		// Initialisation
		$sb_table_header = ['Tri', 'Titre', 'Type', 'Target', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		// --------------------------------
		$query_structure   = "SELECT * FROM $table_structure WHERE tid = '" . intval($_GET[tid]) . "' ORDER BY sort ASC";
		$request_structure = $sbsql->query($query_structure);
		$result_structure  = $sbsql->toarray($request_structure);
		
		if (!empty(array_filter($result_structure)))
			$sbsmarty->assign('allstructure', $result_structure);
		else
			$sbsmarty->assign('allstructureempty', true);
			
		$sbsmarty->assign('sort', true);
	break;

	case "editdatas":
	case "deldatas":
		// Action DELETE Fields table (structure)
		if ($action == 'deldatas') {
			$get_id   = intval($_GET['id']);
			$query[2] = "DELETE FROM $table_datas WHERE id = '$get_id'";
			$request  = $sbsql->query($query[2]);
			
			if ($request)
				$sb_msg_valid = 'Ligne de donnée supprimée avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
				
			$get_id = false;
		}
		
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = "edit_form";
		$formType        = "editdatas";
		$btn_add_edit    = ($_GET['id'] > 0 && $action != 'deldatas') ? "Modifier" : "Ajouter";
		$legend_add_edit = "Ajouter / Modifier les données du $text (<span style='color: red;'>%s</span>)";
		// Contents table
		$tid             = intval($_GET['tid']); // Table ID
		$query_table     = "SELECT * FROM $table WHERE id = '$tid'";
		$request_table   = $sbsql->query($query_table);
		$result_table    = $sbsql->object($request_table);
		$tabname         = $result_table->name;
		$sbsmarty->assign('tabname', $tabname);

		if ($_POST['form_submit']) {
			// Initialize Array / JSON
			$kv      = array();
			$content = "";
			// Loop $_POST (form submit)			
			foreach($_POST as $key => $val) {
				if ($key != 'form_submit') {
					if ($key == 'id')
						$id = intval($val);
					if ($key == 'tid')
						$tid = intval($val);					
					
					if ($key != 'tid')
						$kv[] = [ "i" => $key
								 ,"v" => $sbsanitize->displayText($val, 'UTF-8', 1, 0, 0, 1)
								];
				}
			}
			// JSON ENCODE
			$content = json_encode($kv, JSON_FORCE_OBJECT);

			// ADD or EDIT
			if ($formType == 'editdatas' && !$id) {
				// INSERT DATAS
				$query = "INSERT INTO $table_datas (tid,content,sort)
						  VALUES ('$tid','$content','99')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					foreach($_POST as $key => $val) {
						if ($key != 'form_submit')
							$key = "";
					}
					// --- Message SUCCESS
					$sb_msg_valid = 'Données du tableau ajoutées avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'editdatas' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_datas SET content = '$content'
												  WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Données du tableau modifiées avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
			// --- Vider les champs du formulaire
			$tid = $title_fr = $title_en = $field_type = $field_target = $active = '';
			
		} else {
			// Si AJOUT (First time)
			// --- Vider les champs du formulaire
			$tid = $title_fr = $title_en = $field_type = $field_target = $active = '';
		}
		// --------------------------------
		if ($formType == 'editdatas' && !$_POST['form_submit'] && $_GET['id']) {
			// --- Recuperation des donnees
			$id           = intval($_GET['id']);
			$query[1]     = "SELECT * FROM $table_datas WHERE id = $id ORDER BY sort ASC";
			$requestQ     = $sbsql->query($query[1]);
			$assoc        = $sbsql->assoc($requestQ);
			$tid          = intval($assoc['tid']);
			$title_fr     = $sbsanitize->displayLang(utf8_encode($assoc['title']));
			// ----------------------------
			$title_en     = $sbsanitize->displayLang(utf8_encode($assoc['title']), 'en');
			// ----------------------------
			$field_type   = utf8_encode($assoc['field_type']);
			$field_target = utf8_encode($assoc['field_target']);
			$active       = $assoc['active'];

			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		} else {
			$name = $sbsanitize->displayLang(utf8_encode($_POST['title_fr'])); // Legende
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&tid=" . intval($_GET['tid']);
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$query_structure   = "SELECT * FROM $table_structure WHERE tid = '".intval($_GET['tid'])."' ORDER BY sort ASC";
		$request_structure = $sbsql->query($query_structure);
		$result_structure  = $sbsql->toarray($request_structure);

		if (!$_POST['form_submit'] && $_GET['id']) $structure_datas = sbTableEditdatas($result_structure, intval($_GET['id']));
		
		foreach($result_structure as $key => $val) {
			$title   = $sbsanitize->displayLang($val['title']);
			$input   = $sbsanitize->rewriteString($sbsanitize->displayLang($val['title']), true);
			$value   = (!$_POST['form_submit'] && $_GET['id']) ? $structure_datas[$input] : "";
			$active  = ($val['active']) ? "" : "<strong style='color: red;'>Ce champs ne sera pas affiché sur votre site car il est désactivé !</strong>";
			$style   = ($val['active']) ? "" : "background-color: yellow;";
			// Switch to form type
			switch($val['field_type']) {
				case "photo": // Form Medias
					$sbform->addInput('text', $title, array ('id' => $input, 'name' => $input, 'value' => "$value", "medias"=>"", 'icon' => 'photo', "style" => "$style"), false, false, $active);
				break;
				case "text": // Form Text
					$sbform->addInput('text', $title, array ('id' => $input, 'name' => $input, 'value' => "$value", "style" => "$style"), false, false, $active);
				break;
				case "date": // Form Date
					$value = ($value == '') ? date('Y-m-d') : $value;
					$sbform->addDate($title, array('id' => $input, 'name' => $input, 'value'=>"$value", "style" => "$style"), false);
				break;
				case "textarea": // Form Textarea
					$sbform->addTextarea($title, $value, array('id' => $input, 'name' => $input, "style" => "height: 150px !important; $style"), false, $active);
				break;
				case "textareaHTML": // Form textareaHTML
					$sbform->addTextareaHTML($title, $value, array('id' => $input, 'name' => $input, "style" => "$style"), false, $active);
				break;
				case "link_file": // Link ICON file (pdf)
					$sbform->addInput('text', $title, array ('id' => $input, 'name' => $input, 'value' => "$value", 'icon' => 'file-pdf-o', "style" => "$style"), false, false, $active);
				break;
				case "link_video": // Link ICON video
					$sbform->addInput('text', $title, array ('id' => $input, 'name' => $input, 'value' => "$value", 'icon' => 'video-camera', "style" => "$style"), false, false, $active);
				break;
				case "link_image": // Link ICON image (medias)
					$sbform->addInput('text', $title, array ('id' => $input, 'name' => $input, 'value' => "$value", 'icon' => 'picture-o', "style" => "$style"), false, false, $active);
				break;
			}
			
		}
		
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('hidden', '', array('name' => 'tid', 'value' => "{$_GET[tid]}"));
		if ($formType == 'editdatas' && $id > 0 && $action != 'deldatas') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		
		// --------------------------------		
		// Contents table
		// --------------------------------
		// Initialisation
		$sb_table_header[] = "tri";
		foreach($result_structure as $row) {
			$sb_table_header[] = ($row['active']) ? $sbsanitize->displayLang($row['title']) : "<span style='color: red; background-color: yellow;'>".$sbsanitize->displayLang($row['title'])."</span>";
		}
		$sb_table_header[] = "actions";
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		// --------------------------------
		// --- SQL Request (DATAS)
		// --------------------------------
		$query_datas   = "SELECT * FROM $table_datas WHERE tid = '" . intval($_GET['tid']) . "' ORDER BY sort ASC";
		$request_datas = $sbsql->query($query_datas);
		$result_datas  = $sbsql->toarray($request_datas);
		// --------------------------------
		// --- SQL Request (JSON)
		// --------------------------------
		// --- SQL Request
		//$query_structure   = "SELECT * FROM $table_structure WHERE tid = '$tid' AND active = 1 ORDER BY sort ASC";
		$query_structure   = "SELECT * FROM $table_structure WHERE tid = '" . intval($_GET['tid']) . "' ORDER BY sort ASC";
		$request_structure = $sbsql->query($query_structure);
		$result_structure  = $sbsql->toarray($request_structure);
		$sbsmarty->assign('structure', $result_structure);
		// --------------------------------
		// Check if array is empty
		if (!empty(array_filter($result_datas)))
			$sbsmarty->assign('alldatas', $result_datas);
		else
			$sbsmarty->assign('alldatasempty', true);
			
		$sbsmarty->assign('sort', true);
	break;

	case "sortstructure":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$tid             = intval($_GET['tid']);
		$query[1]        = "SELECT name FROM $table WHERE id = '$tid'";
		$request_tblname = $sbsql->query($query[1]);
		$table_infos     = $sbsql->object($request_tblname);
		$formName        = "sort_form";
		$formType        = "sortstructure";
		$btn_add_edit    = "Valider le tri des colonnes";
		$legend_add_edit = sprintf("Trier les colonnes du tableau &laquo; <span style='color: red;'>%s</span> &raquo;", $table_infos->name);
		// --------------------------------
		if ($_POST['drag']) {
			// --------------------------------
			// --- Control form submit --------
			// --------------------------------
			$sb_toSort = $_POST['drag'];
			
			// reorganizes the order of elements
			$sql_error = 0;
			for ($i = 0; $i < count($sb_toSort); $i++) {
				$tri = $i + 1;
				$query_sort  = "UPDATE $table_structure SET sort = $tri WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = "Les colonnes ont été trié avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$query[3]      = "SELECT * FROM $table_structure WHERE tid = '$tid' ORDER BY sort ASC";
		$requestQ      = $sbsql->query($query[3]);
		$sort_array    = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$active = ($sort['active']) ? $sbsanitize->displayLang(utf8_encode($sort['title'])) : "<span style='color: red;'>".$sbsanitize->displayLang(utf8_encode($sort['title']))."</span>";
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = $active;
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[3]	 . "\n" . 'Form Type = '.$formType);
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&tid=" . $tid;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		//$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les noms des <span style='color: red;'>colonnes en rouge</span> sont des colonnes en statut non visible");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('tid', $tid);
		$sbsmarty->assign('sort', true);
	break;

	case "sortdatas":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$tid             = intval($_GET['tid']);
		$query[1]        = "SELECT name FROM $table WHERE id = '$tid'";
		$request_tblname = $sbsql->query($query[1]);
		$table_infos     = $sbsql->object($request_tblname);
		$formName        = "sort_form";
		$formType        = "sortdatas";
		$btn_add_edit    = "Valider le tri des données";
		$legend_add_edit = sprintf("Trier les données du tableau &laquo; <span style='color: red;'>%s</span> &raquo;", $table_infos->name);
		// --------------------------------
		if ($_POST['drag']) {
			// --------------------------------
			// --- Control form submit --------
			// --------------------------------
			$sb_toSort = $_POST['drag'];
			
			// reorganizes the order of elements
			$sql_error = 0;
			for ($i = 0; $i < count($sb_toSort); $i++) {
				$tri = $i + 1;
				$query_sort  = "UPDATE $table_datas SET sort = $tri WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = "Les données ont été trié avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$query[3]      = "SELECT * FROM $table_datas WHERE tid = '$tid' ORDER BY sort ASC";
		$requestQ      = $sbsql->query($query[3]);
		$sort_array    = $sbsql->toarray($requestQ);
		//foreach($sort_array as $sort) {
		//	$active = ($sort['active']) ? $sbsanitize->displayLang(utf8_encode($sort['title'])) : "<span style='color: red;'>".$sbsanitize->displayLang(utf8_encode($sort['title']))."</span>";
		//	$sort_id          = $sort['id'];
		//	$toSort[$sort_id] = $active;
		//}
		foreach($sort_array as $sort) {
			$datas            = json_decode($sort['content'], true);
			$active           = $sbsanitize->displayText($datas[0]['v']);
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = $active;
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[3]	 . "\n" . 'Form Type = '.$formType);
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&tid=" . $tid;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		//$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('tid', $tid);
		$sbsmarty->assign('sort', true);
	break;	

}

// ----------------------------------------------------------
// ----------------------------------------------------------
// ----------------------------------------------------------
// ----------------------------------------------------------
// ----------------------- FUNCTIONS ------------------------
// ----------------------------------------------------------
// ----------------------------------------------------------
// ----------------------------------------------------------
// ----------------------------------------------------------

/**
* Get array from table
*
* @param $structure	array
* @param $id		integer
* @return array
*/
function sbTableEditdatas($structure, $id) {
	global $sbsql, $sbsanitize;
	$table_datas = _AM_DB_PREFIX . "sb_table_datas";
	$query       = "SELECT * FROM $table_datas WHERE id = '$id'";
	$request     = $sbsql->query($query);
	$datas       = $sbsql->assoc($request);

	$columns = [];
	foreach($structure as $col) {
		$columns[] = $sbsanitize->rewriteString($sbsanitize->displayLang($col['title']), true);
	}
	
	for($i = 0; $i < count($columns); $i++) {
		// Get the right value if column is unknown 
		$data_arr[$i] = ' ';

		foreach(json_decode($datas['content'], true) as $k => $val) {
			// Check if column is JSON Datas Format
			if ($columns[$i] == $val['i']) {
				// Json table
				$input            = $val['i'];
				$value            = preg_replace("/<br \/>/"," ", $sbsanitize->displayText($val['v']));
				$data_arr[$input] = $value;
			}
		}
	}
	
	return $data_arr;
}

function insert_jsondata($param) {
	global $sbsanitize;
	$datas    = $param['datas'];
	$data_arr = "";
	foreach(json_decode($datas, true) as $k => $val) {
		// Json table
		$input     = $val['i'];
		$value     = preg_replace("/<br \/>/"," ", $sbsanitize->displayText($val['v']));
		$data_arr .= '<td>' . $value . '</td>';
	}
	return $data_arr;
}

// ---------------------------------------------------
// ---------------------------------------------------
// IMPORTANT: Don't remove these lines
// ---------------------------------------------------
// ---------------------------------------------------
// ----------------------------------------
// ASSIGN Page TITLE - Modify this |
// ----------------------------------------
$sbsmarty->assign('page_title', $text);
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($sbsanitize->displayLang($tabname), 'UTF-8', 0, 1)));

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