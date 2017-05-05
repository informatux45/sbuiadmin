<?php
/**
 * Admin Startbootstrap
 * Manage BLOCKS (CMS)
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
$module_page = 'blocs';
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
// Seo Url CMS
// -----------------------
$seo_url_cms = str_replace(SBADMIN.'/','',SB_URL);
$sbsmarty->assign('seo_url_cms', $seo_url_cms);

// -----------------------
// Get Multilang Option
// -----------------------
$getMultilang = (sbGetConfig('multilang')) ? sbGetConfig('multilang') : false;

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------
$table       = _AM_DB_PREFIX . "sb_blocs";
$table_pages = _AM_DB_PREFIX . "sb_pages";
$table_sort  = _AM_DB_PREFIX . "sb_blocs_sort";
$text        = "bloc";

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			$query[2] = "DELETE FROM $table WHERE id = '$get_id'";
			$request  = $sbsql->query($query[2]);
			
			// --- Delete bloc sort too ;-)
			$query[4] = "DELETE FROM $table_sort WHERE bloc_id = '$get_id'";
			$request4 = $sbsql->query($query[4]);			
			
			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = array('Nom', 'Titre', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT * FROM $table";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		// --- Extract all pages
		$query_pages    = "SELECT * FROM $table_pages";
		$request_pages  = $sbsql->query($query_pages);
		$result_pages   = $sbsql->toarray($request_pages);
		// --- Extract all modules
		$result_modules = sbGetModulesPage();
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('allbloc', $result2);
		$sbsmarty->assign('all_pages', $result_pages);
		$sbsmarty->assign('all_modules', $result_modules);		

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
		$legend_add_edit = ($action == 'add') ? "Ajouter un $text" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id       = intval($_POST['id']);
			foreach($_POST['pages'] as $val) { $page .= "$val|"; }
			$pages    = rtrim($page, '|');
			foreach($_POST['modules'] as $val) { $module .= "$val|"; }
			$modules    = rtrim($module, '|');
			$name     = $sbsanitize->displayText($_POST['name'], 'UTF-8', 1, 0);
			// --- Titre
			$title_fr = $sbsanitize->displayText($_POST['title_fr'], 'UTF-8', 1, 0);
			$title    = "[fr]".$title_fr."[/fr]";
			if ($getMultilang) {
				$title_en = $sbsanitize->displayText($_POST['title_en'], 'UTF-8', 1, 0);
				$title   .= "[en]".$title_en."[/en]";				
			}
			// --- Titre
			$content_fr = $sbsanitize->displayText($_POST['content_fr'], 'UTF-8', 1, 0);
			$content    = "[fr]".$content_fr."[/fr]";
			if ($getMultilang) {
				$content_en = $sbsanitize->displayText($_POST['content_en'], 'UTF-8', 1, 0);
				$content   .= "[en]".$content_en."[/en]";				
			}
			// ---------
			$position = $sbsanitize->displayText($_POST['position'], 'UTF-8', 1, 0);
			$active   = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (pages_id,modules_id,name,title,content,position,active,sort)
						  VALUES ('$pages','$modules','$name','$title','$content','$position','$active','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --------------------------------------------------
					// --- Vider les champs du formulaire
					// --------------------------------------------------
					$pages = $modules = $name_fr = $name_en = $title_fr = $title_en = $content = $active = '';
					// ------------------------------------------------------
					// --- Ajouter les blocs dans leurs pages respectives ---
					// ------------------------------------------------------
					$bloc_id = $sbsql->lastinsertid();
					foreach($_POST['pages'] as $page_id) {
						$query_bloc_sort  = "INSERT INTO $table_sort (page_id,bloc_id,sort) VALUES ('$page_id','$bloc_id','0')";
						$result_bloc_sort = $sbsql->query($query_bloc_sort);
					}
					// -------------------------------------------------
					// --- Message SUCCESS
					// -------------------------------------------------
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {

					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {

				// UPDATE DATAS
				$query = "UPDATE $table SET pages_id = '$pages'
										   ,modules_id = '$modules'
										   ,name = '$name'
										   ,title = '$title'
										   ,content = '$content'
										   ,position = '$position'
										   ,active = '$active'
										   WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --------------------------------------------------
					// --- On ne vide pas les champs du formulaire
					// ---------------------------------------------------------
					// --- 1. Supprimer toutes les entrées de la DB          ---
					// --- 2. Ajouter les blocs dans leurs pages respectives ---
					// ---------------------------------------------------------
					// --- Bloc id
					$bloc_id = $id;
					// --- Delete blocs infos in DB
					$query_bloc_delete   = "DELETE FROM $table_sort WHERE bloc_id = '$id'";
					$request_bloc_delete = $sbsql->query($query_bloc_delete);
					// --- Pages sort
					foreach($_POST['pages'] as $page_id) {
						$query_bloc_sort  = "INSERT INTO $table_sort (page_id,bloc_id,sort) VALUES ('$page_id','$bloc_id','0')";
						$result_bloc_sort = $sbsql->query($query_bloc_sort);
					}
					// --- Modules sort
					foreach($_POST['modules'] as $module_id) {
						$query_bloc_sort_mod  = "INSERT INTO $table_sort (module_id,bloc_id,sort) VALUES ('$module_id','$bloc_id','0')";
						$result_bloc_sort_mod = $sbsql->query($query_bloc_sort_mod);
					}
					// -------------------------------------------------
					// --- Message SUCCES
					// --------------------------------------------------
					$sb_msg_valid = $text . ' modifié avec succès';
				} else {
					// --------------------------------------------------
					// --- Message ERROR
					// --------------------------------------------------
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		} else {
			// Si AJOUT (First time)
			// --- Vider les champs du formulaire
			$pages = $modules = $name_fr = $name_en = $title_fr = $title_en = $content = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id         = intval($_GET['id']);
			$query[1]   = "SELECT * FROM $table WHERE id = $id";
			$requestQ   = $sbsql->query($query[1]);
			$assoc      = $sbsql->assoc($requestQ);
			$pages      = $assoc['pages_id'];
			$modules    = $assoc['modules_id'];
			$name       = $sbsanitize->displayLang(utf8_encode($assoc['name']));
			// ----------------------------
			$title_fr   = $sbsanitize->displayLang(utf8_encode($assoc['title']));
			$title_en   = $sbsanitize->displayLang(utf8_encode($assoc['title']), 'en');
			// ----------------------------
			$content_fr = $sbsanitize->displayLang(utf8_encode($assoc['content']));
			$content_en = $sbsanitize->displayLang(utf8_encode($assoc['content']), 'en');
			// ----------------------------
			$position   = $assoc['position'];
			$active     = $assoc['active'];

			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		}
		
		// --------------------------------
		// --- Get INFOS CMS Theme / Modules
		// --------------------------------
		// --- Include Theme Config
		include_once(SB_THEME_DIR . 'config.php');
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Nom du bloc
		// ----------------------------
		$sbform->addInput('text', "Nom du bloc", array ('name' => 'name', 'value' => "$name", 'placeholder' => "Nom de votre bloc"), true, false, "Nom de votre bloc visible pour l'administration uniquement");
		// ----------------------------
		// --- Titre du bloc
		// ----------------------------
		$title_fr_title = ($getMultilang) ? 'Titre (FR)' : 'Titre';
		$sbform->addInput('text', "$title_fr_title", array ('name' => 'title_fr', 'value' => "$title_fr", 'placeholder' => "Titre de votre bloc"), false, false, "Titre de votre bloc côté site web.<br>Si le titre n'est pas rempli, il n'apparaîtra pas sur votre site web.");
		if ($getMultilang)
			$sbform->addInput('text', 'Titre (EN)', array ('name' => 'title_en', 'value' => "$title_en", 'placeholder' => "Titre de votre bloc"), false);
		// -----------------------------------
		// --- Choix de la page pour afficher le bloc
		// -----------------------------------
		$sbsql->free();
		$query_pages = "SELECT * FROM $table_pages";
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
		$sbform->addCheckbox('Pages', $tab_pages, '', false, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', "Le bloc sera visible sur les pages que vous cochez s'il est actif.<br>Si vous ajoutez ou retirez des pages à votre bloc, n'oubliez pas de trier celui-ci car l'ordre s'en trouvera modifié.");
		// -----------------------------------
		// --- Choix du module pour afficher le bloc
		// -----------------------------------
		// --- Get module
		$allmodules = sbGetModulesPage();
		// --- Initialisation
		$tab_modules = array();
		// --- Tableau des types du bien
		$array_modules = explode("|", $modules);
		$i = 0;
		foreach($allmodules as $key => $val) {
			$tab_modules[$i]['text']    = $val;
			$tab_modules[$i]['name']    = 'modules[]';
			$tab_modules[$i]['value']   = $val;
			$tab_modules[$i]['checked'] = (in_array($val, $array_modules)) ? '1' : '0';
			$i++;
		}
		$sbform->addCheckbox('Modules', $tab_modules, '', false, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', "Le bloc sera visible sur les modules que vous cochez s'il est actif.<br>Si vous ajoutez ou retirez des modules à votre bloc, n'oubliez pas de trier celui-ci car l'ordre s'en trouvera modifié.");
		// -----------------------------------
		// --- Position du bloc dans la page
		// -----------------------------------
		$sbform->openSelect("Position du bloc", array("id"=>"position", "name"=>"position", 'disabled' => 'disabled'));
		$sbform->addOption('Choisissez une position', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($sb_position); $i++) {
			if ($sb_position[$i] == $position)
				$sbform->addOption($sb_position[$i], array ("value"=>$sb_position[$i], "selected"=>""));
		else
				$sbform->addOption($sb_position[$i], array ("value"=>$sb_position[$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Position du bloc dans votre page (peut varier selon la disposition du votre theme)");
		// -----------------------------------
		// --- Contenu du bloc
		// -----------------------------------
		$contenu_fr_title = ($getMultilang) ? 'Contenu (FR)' : 'Contenu';
		$sbform->addTextareaHTML("$contenu_fr_title", $content_fr, array('id' => 'content_fr', 'name' => 'content_fr'), true);
		if ($getMultilang)
			$sbform->addTextareaHTML('Contenu (EN)', $content_en, array('id' => 'content_en', 'name' => 'content_en'), true);
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
		$legend_add_edit = "Trier les " . strtolower($text) . 's de pages';
		$selected_page   = intval($_GET['pid']);
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
				$query_sort  = "UPDATE $table_sort SET sort = $tri WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = "Les {$text}s ont été trié avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$id            = intval($_GET['id']);
		$query[3]      = "SELECT t1.*, t2.name AS blocname
						  FROM $table_sort AS t1
						  LEFT JOIN $table AS t2 ON (t1.bloc_id = t2.id)
						  WHERE t1.page_id = $selected_page
						  ORDER BY t1.sort ASC ";
		$requestQ      = $sbsql->query($query[3]);
		$sort_array    = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = utf8_encode($sort['blocname']);
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[3]	 . "\n" . 'Form Type = '.$formType);
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&pid=" . $selected_page;
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
	
	case "sortmod":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$formName        = "sortmod_form";
		$formType        = "sortmod";
		$btn_add_edit    = "Valider";
		$legend_add_edit = "Trier les " . strtolower($text) . 's de modules';
		$selected_page   = $_GET['pid'];
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
				$query_sort  = "UPDATE $table_sort SET sort = $tri WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = "Les {$text}s ont été trié avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$id            = intval($_GET['id']);
		$query[3]      = "SELECT t1.*, t2.name AS blocname
						  FROM $table_sort AS t1
						  LEFT JOIN $table AS t2 ON (t1.bloc_id = t2.id)
						  WHERE t1.module_id = '$selected_page'
						  ORDER BY t1.sort ASC ";
		$requestQ      = $sbsql->query($query[3]);
		$sort_array    = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = utf8_encode($sort['blocname']);
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[3]	 . "\n" . 'Form Type = '.$formType);
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&pid=" . $selected_page;
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
$sbsmarty->assign('page_title', 'Blocs');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($title_fr, 'UTF-8', 0, 1)));

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