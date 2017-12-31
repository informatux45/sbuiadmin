<?php
/**
 * Admin Startbootstrap
 * Manage MENUS
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
$module_page = 'menu';
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

$table       = _AM_DB_PREFIX . "sb_menu";
$table_pages = _AM_DB_PREFIX . "sb_pages";
$text        = "Menu";

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
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = array('Titre', 'Tag Smarty', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT * FROM $table";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('allmenu', $result2);
		
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
			$tag    = $sbsanitize->displayText($_POST['tag'], 'UTF-8', 1, 0);
			foreach($_POST['pages'] as $val) { $page .= "$val|"; }
			$pages  = rtrim($page, '|');
			$active = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (name,tag,pages,active)
						  VALUES ('$name','$tag','$pages','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$name = $tag = $pages = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {

				// UPDATE DATAS
				$query = "UPDATE $table SET name = '$name'
										   ,tag = '$tag'
										   ,pages = '$pages'
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
			$name = $tag = $pages = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id']);
			$query[1] = "SELECT * FROM $table WHERE id = $id";
			$requestQ = $sbsql->query($query[1]);
			$assoc    = $sbsql->assoc($requestQ);
			$name     = $sbsanitize->displayLang(utf8_encode($assoc['name']));
			$tag      = $assoc['tag'];
			$pages    = $assoc['pages'];
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
		// Mom du menu
		// --------------------------------
		$sbform->addInput('text', 'Titre', array ('name' => 'name', 'value' => "$name", 'placeholder' => "Titre de votre menu"), true);
		// --------------------------------
		// Tag Smarty
		// --------------------------------
		$sbform->addInput('text', 'Tag Smarty', array ('name' => 'tag', 'value' => "$tag", 'placeholder' => "Tag Smarty de votre menu"), true, false, "Aucun caractère accentués, espaces, autres...<br>Fonction a inséré dans votre template :<br><strong style='color: #bbb'>{insert name='sbGetMenuCms' mclass='<span style='color: red;'>menu</span>' mid='<span style='color: red;'>nav</span>' mtag='<span style='color: red;'>$tag</span>' mlang=\"`\$smarty.session.lang`\"}</strong>");
		// ----------------------------
		// Pages
		// ----------------------------
		$sbsql->free();
		$query_pages = "SELECT * FROM $table_pages WHERE active = 1 ORDER BY id DESC";
		$allpages    = $sbsql->query($query_pages);
		$allpages    = $sbsql->toarray($allpages);
		// --- Initialisation
		$tab_pages = array();
		// --- Tableau des types du bien
		$array_pages = explode("|", $pages);
		$i = 0;
		foreach($allpages as $row) {
			$tab_pages[$i]['text']    = $sbsanitize->displayLang(utf8_encode($row['title']));
			$tab_pages[$i]['name']    = 'pages[]';
			$tab_pages[$i]['value']   = $row['id'];
			$tab_pages[$i]['checked'] = (in_array($row['id'], $array_pages)) ? '1' : '0';
			$i++;
		}
		$sbform->addCheckbox('Pages du menu', $tab_pages, '', false, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', "Si vous ajoutez ou retirez des pages à votre menu, n'oubliez pas de trier celui-ci car l'ordre s'en trouvera modifié.");
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
		$legend_add_edit = "Trier le " . strtolower($text);
		// --------------------------------
		if ($_POST['drag']) {
			// --------------------------------
			// --- Control form submit --------
			// --------------------------------
			$sb_toSort = $_POST['drag'];
			$id        = intval($_GET['id']);

			// reorganizes the order of elements
			for($i = 0; $i < count($sb_toSort); $i++) {
				$sb_pages_sorted .= $sb_toSort[$i] . "|";
			}
			$sb_pages_sorted = substr($sb_pages_sorted, 0, -1);

			$query_sort  = "UPDATE $table SET pages = '$sb_pages_sorted' WHERE id = '$id'";
			$result_sort = $sbsql->query($query_sort);
			if (!$result_sort) {
				// --- Error Database
				$sb_msg_error = 'Error: Write Error (SORT)!';
			} else {
				// --- Message SUCCES
				$sb_msg_valid = 'Le ' . strtolower($text) . ' a été trié avec succès';
			}
			if (_AM_SITE_DEBUG) $sbsmarty->append('sbdebugsql', $query_sort);
		}
		
		// --- Recuperation des donnees
		$id            = intval($_GET['id']);
		$query[3]      = "SELECT * FROM $table WHERE id = '$id'";
		$requestQ      = $sbsql->query($query[3]);
		$sort_array    = $sbsql->assoc($requestQ);
		$assoc_pages   = explode("|", $sort_array['pages']);
		foreach($assoc_pages as $sort) {
			$query[4] = "SELECT menu FROM $table_pages WHERE id = '$sort'";
			$requestP = $sbsql->query($query[4]);
			$one_page = $sbsql->assoc($requestP);
			$toSort[$sort] = $sbsanitize->displayLang($one_page['menu']);
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[3]	 . "\n" . 'Form Type = '.$formType);
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider.<br>Cela modifiera l'ordre d'affichage de vos entrées de ".strtolower($text).".");
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
$sbsmarty->assign('page_title', 'Menus');
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