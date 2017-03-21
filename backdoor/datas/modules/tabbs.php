<?php
/**
 * Admin Startbootstrap
 * TABBS Module
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
$module_page = 'tabbs';
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
$table     = _AM_DB_PREFIX . "sb_tabbs";
$table_tab = _AM_DB_PREFIX . "sb_tabbs_tab";
$text      = "Tabbs";

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE tabbs
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			// -------------------------------------
			// --- Check if tab in TABBS
			$query_tab   = "SELECT * FROM $table_tab WHERE tid = $get_id";
			$request_tab = $sbsql->query($query_tab);
			$numrows_tab = $sbsql->numrows($request_tab);
			// -------------------------------------
			if ($numrows_tab > 0) {
				$sb_msg_error = 'Ce TABBS contient des onglets !!<br>Vous devez supprimer les onglets contenus dans ce TABBS avant !!';		
			} else {
				$query[2] = "DELETE FROM $table WHERE id = '$get_id'";
				$request  = $sbsql->query($query[2]);
				
				if ($request)
					$sb_msg_valid = $text . ' supprimé avec succès';
				else
					$sb_msg_error = 'Error: Write Error (DEL)!';
			}
		}

		// Initialisation
		$sb_table_header = ['Nom', 'Shortcode', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT * FROM $table";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', $result2);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query[0];
			if (isset($action) && $action == 'del') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query[2];
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}
		
	break;

	case "deltab":
	case "alltabs":
		// Action DELETE
		if ($action == 'deltab') {
			$get_id   = intval($_GET['id']);
			$query[7] = "DELETE FROM $table_tab WHERE id = '$get_id'";
			$request  = $sbsql->query($query[7]);
			
			if ($request)
				$sb_msg_valid = 'Onglet supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = ['Titre', 'Tabbs', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT t1.*, t2.name AS catname
					 FROM $table_tab AS t1
					 LEFT JOIN $table AS t2 ON (t1.tid = t2.id)";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('alltabs', $result2);
		
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
		$legend_add_edit = ($action == 'add') ? "Ajouter un " . strtolower($text) : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id     = intval($_POST['id']);
			$name   = $sbsanitize->displayText($_POST['name'], 'UTF-8', 1, 0);
			$active = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

	
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (name,active)
						  VALUES ('$name', '$active')";
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
		// Nom du TABBS
		// --------------------------------		
		$sbform->addInput('text', 'Nom', array ('name' => 'name', 'value' => "$name", 'placeholder' => "Nom du TABBS"), true);
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

	case "tabadd":
	case "tabedit":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = ($action == 'tabadd') ? "add_form" : "edit_form";
		$formType        = ($action == 'tabadd' || $_POST['form_submit'] == 'add_form') ? "tabadd" : "tabedit";
		$btn_add_edit    = ($action == 'tabadd') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'tabadd') ? "Ajouter un onglet" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id           = intval($_POST['id']);
			$tid          = intval($_POST['tid']);
			// --- Titre
			$title_fr     = $sbsanitize->displayText($_POST['title_fr'], 'UTF-8', 1, 0);
			$title        = "[fr]".$title_fr."[/fr]";
			if ($getMultilang) {
				$title_en = $sbsanitize->displayText($_POST['title_en'], 'UTF-8', 1, 0);
				$title   .= "[en]".$title_en."[/en]";				
			}
			// --- Content
			$content_fr   = $sbsanitize->displayText($_POST['content_fr'], 'UTF-8', 1, 0);
			$content      = "[fr]".$content_fr."[/fr]";
			if ($getMultilang) {
				$content_en = $sbsanitize->displayText($_POST['content_en'], 'UTF-8', 1, 0);
				$content   .= "[en]".$content_en."[/en]";				
			}
			$active       = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

			// ADD or EDIT
			if ($formType == 'tabadd') {
				// INSERT DATAS
				$query = "INSERT INTO $table_tab (tid,title,content,active,sort)
						  VALUES ('$tid','$title','$content','$active','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$tid = $title_fr = $content_fr = $title_en = $content_en = $photo = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Onglet ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'tabedit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_tab SET tid = '$tid'
											   ,title = '$title'
											   ,content = '$content'
											   ,active = '$active'
											   WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Onglet modifié avec succès';
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
			$tid = $title_fr = $content_fr = $title_en = $content_en = $photo = $active = '';
		}
		// --------------------------------
		if ($formType == 'tabedit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id         = intval($_GET['id']);
			$query[1]   = "SELECT * FROM $table_tab WHERE id = $id";
			$requestQ   = $sbsql->query($query[1]);
			$assoc      = $sbsql->assoc($requestQ);
			$tid        = intval($assoc['tid']);
			$title_fr   = $sbsanitize->displayLang(utf8_encode($assoc['title']));
			$content_fr = $sbsanitize->displayLang(utf8_encode($assoc['content']));
			// ----------------------------
			$title_en   = $sbsanitize->displayLang(utf8_encode($assoc['title']), 'en');
			$content_en = $sbsanitize->displayLang(utf8_encode($assoc['content']), 'en');
			// ----------------------------
			$active     = $assoc['active'];

			$name       = $sbsanitize->displayLang(utf8_encode($assoc['title'])); // Legende
			
			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		} else {
			$name = $sbsanitize->displayLang(utf8_encode($_POST['title_fr'])); // Legende
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active == '') ? '1' : $active;
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Liste des TABBS
		// ----------------------------
		$query_tabbs   = "SELECT * FROM $table";
		$request_tabbs = $sbsql->query($query_tabbs);
		$tabbs         = $sbsql->toarray($request_tabbs);
		$sbform->openSelect("Tabbs", array("id"=>"tid", "name"=>"tid"), true);
		if ($formType == 'tabadd') $sbform->addOption('Choisissez un tabbs', array ("value"=>"", "selected"=>""));
		foreach($tabbs as $row) {
			if ($row['id'] == $tid)
				$sbform->addOption($sbsanitize->displayLang($row['name']), array ("value"=>$row['id'], "selected"=>""));
		else
				$sbform->addOption($sbsanitize->displayLang($row['name']), array ("value"=>$row['id']));
		}
		$sbform->closeSelect();
		// ----------------------------
		// --- Titre
		// ----------------------------
		$title_fr_title = ($getMultilang) ? 'Titre (FR)' : 'Titre' ;
		$sbform->addInput('text', "$title_fr_title", array ('name' => 'title_fr', 'value' => "$title_fr", 'placeholder' => "Titre de votre onglet"), true);
		if ($getMultilang)
			$sbform->addInput('text', 'Titre (EN)', array ('name' => 'title_en', 'value' => "$title_en", 'placeholder' => "Titre de votre onglet (EN)"), true);
		// ----------------------------
		// --- Contenu
		// ----------------------------
		$content_fr_title = ($getMultilang) ? 'Contenu (FR)' : 'Contenu' ;
		$sbform->addTextareaHTML("$content_fr_title", $content_fr, array('id' => 'content_fr', 'name' => 'content_fr'), true);
		if ($getMultilang)
			$sbform->addTextareaHTML("Contenu (EN)", $content_en, array('id' => 'content_en', 'name' => 'content_en'), true);
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'tabedit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
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
		$legend_add_edit = "Trier les onglets";
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
				$query_sort  = "UPDATE $table_tab SET sort = $tri WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = "Les onglets ont été trié avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$tid           = intval($_GET['tid']);
		$query[3]      = "SELECT * FROM $table_tab WHERE tid = '$tid' ORDER BY sort ASC";
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
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les noms d'<span style='color: red;'>onglets en rouge</span> sont des onglets en statut non visible");
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
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($sbsanitize->displayLang($name), 'UTF-8', 0, 1)));

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