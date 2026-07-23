<?php
/**
 * Admin Startbootstrap
 * Manage FAQs (client)
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
$module_page = 'faq';
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
$table      = _AM_DB_PREFIX . "sb_faq";
$table_cat  = _AM_DB_PREFIX . "sb_faq_category";
$text       = "FAQ";
$text_s     = "FAQs";

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			$query_2 = "DELETE FROM $table WHERE id = '$get_id'";
			$request  = $sbsql->query($query_2);

			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = ['Tri', 'ID', 'Catégorie', 'Question', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);

		// Contents table
		$query_0 = "SELECT t1.*, t2.name AS category_name
		            FROM $table AS t1
					LEFT JOIN $table_cat AS t2 ON (t1.category = t2.id)
					ORDER BY t1.sort ASC";
		$request0  = $sbsql->query($query_0);
		$result0   = $sbsql->toarray($request0);

		$sbsmarty->assign('all', true);
		$sbsmarty->assign('allfaq', $result0);

		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query_0;
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
		$legend_add_edit = ($action == 'add') ? "Ajouter une question" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id       = intval($_POST['id']);
			$question = $sbsanitize->displayText($_POST['question'], 'UTF-8', 1, 0); // Question
			$response = $sbsanitize->displayText($_POST['response'], 'UTF-8', 1, 0); // Reponse
			$category = $sbsanitize->displayText($_POST['category'], 'UTF-8', 1, 0); // Categorie
			$active   = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (category,question,response,active)
						  VALUES ('$category','$question','$response','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$category = $question = $response = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajoutée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET category = '$category'
											,question = '$question'
				                            ,response = '$response'
											,active = '$active'
											WHERE id = '$id'";

				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = $text . ' modifiée avec succès';
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
			$category = $question = $response = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id          = intval($_GET['id']);
			$query_1     = "SELECT * FROM $table WHERE id = $id";
			$requestQ    = $sbsql->query($query_1);
			$assoc       = $sbsql->assoc($requestQ);
			$category    = $assoc['category'];
			$question    = $sbsanitize->displayLang(utf8_encode($assoc['question']));
			$response    = $sbsanitize->displayLang(utf8_encode($assoc['response']));
			$active      = $assoc['active'];

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
		// --- Question
		// ----------------------------
		$sbform->addInput('text', "Question", array ('name' => 'question', 'value' => "$question", 'placeholder' => "Question"), true);
		// ----------------------------
		// --- Categorie
		// ----------------------------
		$query_category   = "SELECT * FROM $table_cat WHERE active = '1' ORDER BY name ASC";
		$request_category = $sbsql->query($query_category);
		$categories       = $sbsql->toarray($request_category);
		$sbform->openSelect("Catégorie", array("id"=>"category", "name"=>"category", "style" => "width: 500px;"), true);
		if ($category == '' || $category > 0) $sbform->addOption('Choisissez une catégorie', array ("value"=>"", "selected"=>""));
		foreach($categories as $row) {
			if ($row['id'] == $category)
				$sbform->addOption($row['name'], array ("value"=>$row['id'], "selected"=>""));
		else
				$sbform->addOption($row['name'], array ("value"=>$row['id']));
		}
		// --- Close Select
		$sbform->closeSelect();
		// ----------------------------
		// --- Reponse
		// ----------------------------
		$sbform->addTextareaHtml("Réponse", $response, array('id' => 'response', 'name' => 'response'), true);
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
		$legend_add_edit = "Trier les questions";
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
				$query_sort  = "UPDATE $table SET sort = $tri WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = "Les questions ont été triées avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}

		// --- Recuperation des donnees
		$query_3      = "SELECT * FROM $table ORDER BY sort ASC";
		$requestQ      = $sbsql->query($query_3);
		$sort_array    = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$active = ($sort['active']) ? $sbsanitize->displayLang(utf8_encode($sort['question'])) : "<span style='color: red;'>".$sbsanitize->displayLang(utf8_encode($sort['question']))."</span>";
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = $active;
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_3 . "\n" . 'Form Type = '.$formType);

		// --------------------------------
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les questions <span style='color: red;'>en rouge</span> sont des questions en statut non visible");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------
		// --- Close Form
		// --------------------------------
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('sort', true);
	break;

	case "categorydel":
	case "category":
		// Action DELETE category
		if ($action == 'categorydel') {
			$get_id   = intval($_GET['id']);
			// -------------------------------------
			// --- Check if question uses this category
			$query_faq   = "SELECT id FROM $table WHERE category = $get_id";
			$request_faq = $sbsql->query($query_faq);
			$numrows_faq = $sbsql->numrows($request_faq);
			// -------------------------------------
			if ($numrows_faq > 0) {
				$sb_msg_error = 'Cette catégorie contient des questions !!<br>Vous devez supprimer ou déplacer les questions de cette catégorie avant !!';
			} else {
				$query_5 = "DELETE FROM $table_cat WHERE id = '$get_id'";
				$request  = $sbsql->query($query_5);

				if ($request)
					$sb_msg_valid = 'Catégorie supprimée avec succès';
				else
					$sb_msg_error = 'Error: Write Error (DEL)!';
			}
		}

		// Initialisation
		$sb_table_header = ['Nom', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);

		// Contents table
		$query_4  = "SELECT * FROM $table_cat ORDER BY name ASC";
		$request2 = $sbsql->query($query_4);
		$result2  = $sbsql->toarray($request2);

		$sbsmarty->assign('allcat', true);
		$sbsmarty->assign('allcategory', $result2);

		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query_4;
			if (isset($action) && $action == 'categorydel') {
				$alldel_debug .= "\n" . 'DEL: ' . $query_5;
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}

	break;

	case "categoryadd":
	case "categoryedit":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = ($action == 'categoryadd') ? "add_form" : "edit_form";
		$formType        = ($action == 'categoryadd' || $_POST['form_submit'] == 'add_form') ? "categoryadd" : "categoryedit";
		$btn_add_edit    = ($action == 'categoryadd') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'categoryadd') ? "Ajouter une catégorie" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id     = intval($_POST['id']);
			$name   = $sbsanitize->displayText($_POST['name'], 'UTF-8', 1, 0);
			$active = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

			// ADD or EDIT
			if ($formType == 'categoryadd') {
				// INSERT DATAS
				$query = "INSERT INTO $table_cat (name,active)
						  VALUES ('$name','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$name = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Catégorie ajoutée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'categoryedit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_cat SET name = '$name'
											    ,active = '$active'
											    WHERE id = '$id'";

				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- Message SUCCES
					$sb_msg_valid = 'Catégorie modifiée avec succès';
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
		if ($formType == 'categoryedit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id']);
			$query_1  = "SELECT * FROM $table_cat WHERE id = $id";
			$requestQ = $sbsql->query($query_1);
			$assoc    = $sbsql->assoc($requestQ);
			$name     = $sbsanitize->displayLang(utf8_encode($assoc['name']));
			$active   = $assoc['active'];

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
		$sbform->addInput('text', "Nom", array ('name' => 'name', 'value' => "$name", 'placeholder' => "Nom de la catégorie"), true);
		// --------------------------------
		// --- Hiddens / Buttons
		// --------------------------------
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'categoryedit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --------------------------------
		// --- Close Form
		// --------------------------------
		$sbform->closeForm ();
		// --------------------------------
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
$sbsmarty->assign('page_title', $text_s);
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($sbsanitize->displayLang(isset($question) ? $question : (isset($name) ? $name : '')), 'UTF-8', 0, 1)));

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
