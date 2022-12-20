<?php
/**
 * Admin Startbootstrap
 * Manage NEWS (articles)
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
$module_page = 'news';
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
$table          = _AM_DB_PREFIX . "sb_news";
$table_category = _AM_DB_PREFIX . "sb_news_category";
$table_settings = _AM_DB_PREFIX . "sb_news_settings";
$text           = "Article";

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
		$sb_table_header = ['Date', 'Titre', 'Catégorie', 'Shortcode', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query    = "SELECT t1.*, t2.title AS catname
					 FROM $table AS t1
					 LEFT JOIN $table_category AS t2 ON (t1.catid = t2.id)";
		$request2 = $sbsql->query($query);
		$result2  = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('allnew', $result2);
		
		// ----------------------------------------
		// --- News infos
		// ----------------------------------------
		$query_news_total        = "SELECT id FROM $table";
		$query_news_active       = "SELECT id FROM $table WHERE active = '1'";
		$query_categories_total  = "SELECT id FROM $table_category";
		$query_categories_active = "SELECT id FROM $table_category WHERE active = '1'";
		$request_news_total    = $sbsql->query($query_news_total);
		$numrows_news_total    = $sbsql->numrows();
		$sbsmarty->assign('total_news', $numrows_news_total);
		$request_news_active   = $sbsql->query($query_news_active);
		$numrows_news_active   = $sbsql->numrows();
		$sbsmarty->assign('total_news_active', $numrows_news_active);
		$request_categories_total = $sbsql->query($query_categories_total);
		$numrows_categories_total = $sbsql->numrows();
		$sbsmarty->assign('total_categories', $numrows_categories_total);
		$request_categories_active  = $sbsql->query($query_categories_active);
		$numrows_categories_active  = $sbsql->numrows();
		$sbsmarty->assign('total_categories_active', $numrows_categories_active);
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

	case "categorydel":
	case "category":
		// Action DELETE category
		if ($action == 'categorydel') {
			$get_id   = intval($_GET['id']);
			// -------------------------------------
			// --- Check if News article in CATEGORY
			$query_article   = "SELECT * FROM $table WHERE catid = $get_id";
			$request_article = $sbsql->query($query_article);
			$numrows_article = $sbsql->numrows($request_article);
			// -------------------------------------
			if ($numrows_article > 0) {
				$sb_msg_error = 'Cette catégorie contient des articles !!<br>Vous devez supprimer les articles contenus dans cette catégorie avant !!';		
			} else {
				$query_5 = "DELETE FROM $table_category WHERE id = '$get_id'";
				$request  = $sbsql->query($query_5);
				
				if ($request)
					$sb_msg_valid = 'Catégorie supprimée avec succès';
				else
					$sb_msg_error = 'Error: Write Error (DEL)!';
			}
		}

		// Initialisation
		$sb_table_header = ['Tri', 'Titre', 'Shortcode (lastest item)', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query_4  = "SELECT * FROM $table_category";
		$request2 = $sbsql->query($query_4);
		$result2  = $sbsql->toarray($request2);
		
		$sbsmarty->assign('allcat', true);
		$sbsmarty->assign('allcategory', $result2);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query_4;
			if (isset($action) && $action == 'categorydel') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query_5 . "\n" . 'Numrows: ' . $query_article;
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}
		// -------------
		
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
			$id                = intval($_POST['id']);
			$catid             = '';
			foreach($_POST['catid'] as $val) { $catid .= "$val|"; }
			$catid             = rtrim($catid, '|');
			// --- Titre
			$title_fr          = $sbsanitize->displayText($_POST['title_fr'], 'UTF-8', 1, 0);
			$title             = "[fr]".$title_fr."[/fr]";
			if ($getMultilang) {
				$title_en      = $sbsanitize->displayText($_POST['title_en'], 'UTF-8', 1, 0);
				$title        .= "[en]".$title_en."[/en]";				
			}
			// --- Sous Titre
			$subtitle_fr          = $sbsanitize->displayText($_POST['subtitle_fr'], 'UTF-8', 1, 0);
			$subtitle             = "[fr]".$subtitle_fr."[/fr]";
			if ($getMultilang) {
				$subtitle_en      = $sbsanitize->displayText($_POST['subtitle_en'], 'UTF-8', 1, 0);
				$subtitle        .= "[en]".$subtitle_en."[/en]";				
			}
			// --- Desc Short
			$desc_short_fr     = $sbsanitize->displayText($_POST['desc_short_fr'], 'UTF-8', 1, 0);
			$desc_short        = "[fr]".$desc_short_fr."[/fr]";
			if ($getMultilang) {
				$desc_short_en = $sbsanitize->displayText($_POST['desc_short_en'], 'UTF-8', 1, 0);
				$desc_short   .= "[en]".$desc_short_en."[/en]";				
			}
			// --- Desc Full
			$desc_full_fr      = $sbsanitize->displayText($_POST['desc_full_fr'], 'UTF-8', 1, 0);
			$desc_full         = "[fr]".$desc_full_fr."[/fr]";
			if ($getMultilang) {
				$desc_full_en  = $sbsanitize->displayText($_POST['desc_full_en'], 'UTF-8', 1, 0);
				$desc_full    .= "[en]".$desc_full_en."[/en]";				
			}
			list($day, $month, $year) = explode("/", trim($_POST['date']));
			$date              = $sbsanitize->displayText($year.'-'.$month.'-'.$day, 'UTF-8', 1, 0);
			$photo             = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$active            = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

	
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (catid,title,subtitle,desc_short,desc_full,image,date,active)
						  VALUES ('$catid','$title','$subtitle','$desc_short','$desc_full','$photo','$date','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$catid = $title_fr = $desc_short_fr = $desc_full_fr = $title_en = $desc_short_en = $desc_full_en = $photo = $date = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET catid = '$catid'
																,title = '$title'
																,subtitle = '$subtitle'
																,desc_short = '$desc_short'
																,desc_full = '$desc_full'
																,image = '$photo'
																,date = '$date'
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
			$catid = $title_fr = $desc_short_fr = $desc_full_fr = $title_en = $desc_short_en = $desc_full_en = $photo = $date = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id            = intval($_GET['id']);
			$query_1       = "SELECT * FROM $table WHERE id = $id";
			$requestQ      = $sbsql->query($query_1);
			$assoc         = $sbsql->assoc($requestQ);
			$catid         = $assoc['catid'];
			$title_fr      = $sbsanitize->displayLang(utf8_encode($assoc['title']));
			$subtitle_fr   = $sbsanitize->displayLang(utf8_encode($assoc['subtitle']));
			$desc_short_fr = $sbsanitize->displayLang(utf8_encode($assoc['desc_short']));
			$desc_full_fr  = $sbsanitize->displayLang(utf8_encode($assoc['desc_full']));
			// ----------------------------
			$title_en      = $sbsanitize->displayLang(utf8_encode($assoc['title']), 'en');
			$subtitle_en   = $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), 'en');
			$desc_short_en = $sbsanitize->displayLang(utf8_encode($assoc['desc_short']), 'en');
			$desc_full_en  = $sbsanitize->displayLang(utf8_encode($assoc['desc_full']), 'en');
			$date          = $assoc['date'];
			$photo         = utf8_encode($assoc['image']);
			$active        = $assoc['active'];			

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
		// --- DATE
		// ----------------------------
		$date = ($date == '') ? date('Y-m-d') : $date;
		$sbform->addDate('Date', array('id'=>'date', 'name'=>'date', 'value'=>strftime("%d/%m/%Y", strtotime($date))), true);
		// -----------------------------------
		// --- CATEGORIES
		// -----------------------------------
		// --- Extraction de toutes les categories
		$sbsql->free();
		$query_cat   = "SELECT id, title FROM $table_category ORDER BY title ASC";
		$request_cat = $sbsql->query($query_cat);
		$categories  = $sbsql->toarray($request_cat);
		// --- Initialisation
		$tab_category = array();
		// --- Tableau des categories
		$array_category = explode("|", $catid);
		$i = 0;
		foreach($categories as $row) {
			$tab_category[$i]['text']    = $sbsanitize->displayLang(utf8_encode($row['title']));
			$tab_category[$i]['name']    = 'catid[]';
			$tab_category[$i]['value']   = $row['id'];
			$tab_category[$i]['checked'] = (in_array($row['id'], $array_category)) ? '1' : '0';
			$i++;
		}
		$sbform->addCheckbox('Catégorie(s)', $tab_category, '', false, '<br>');
		// ----------------------------
		// --- Photo
		// ----------------------------
		$sbform->addInput('text', 'Image', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo", "medias"=>"", 'icon' => 'photo', 'style' => 'width: 100% !important'), false);
		// ----------------------------
		// --- Titre
		// ----------------------------
		$title_fr_title = ($getMultilang) ? 'Titre (FR)' : 'Titre' ;
		$sbform->addInput('text', "$title_fr_title", array ('name' => 'title_fr', 'value' => "$title_fr", 'placeholder' => "Titre de l'article"), true);
		if ($getMultilang)
			$sbform->addInput('text', 'Titre (EN)', array ('name' => 'title_en', 'value' => "$title_en", 'placeholder' => "Titre de votre article (EN)"), false);
		// ----------------------------
		// --- Sous Titre
		// ----------------------------
		$subtitle_fr_title = ($getMultilang) ? 'Sous Titre (FR)' : 'Sous Titre' ;
		$sbform->addInput('text', "$subtitle_fr_title", array ('name' => 'subtitle_fr', 'value' => "$subtitle_fr", 'placeholder' => "Sous Titre de l'article"), false);
		if ($getMultilang)
			$sbform->addInput('text', 'Sous Titre (EN)', array ('name' => 'subtitle_en', 'value' => "$subtitle_en", 'placeholder' => "Sous Titre de votre article (EN)"), false);
		// ----------------------------
		// --- Description SHORT
		// ----------------------------
		$desc_short_fr_title = ($getMultilang) ? 'Intro (FR)' : 'Intro' ;
		$sbform->addTextareaHTML("$desc_short_fr_title", $desc_short_fr, array('id' => 'desc_short_fr', 'name' => 'desc_short_fr'), true);
		if ($getMultilang)
			$sbform->addTextareaHTML('Intro (EN)', $desc_short_en, array('id' => 'desc_short_en', 'name' => 'desc_short_en'), false);
		// ----------------------------
		// --- Description FULL
		// ----------------------------
		$desc_full_fr_title = ($getMultilang) ? 'Article (FR)' : 'Article' ;
		$sbform->addTextareaHTML("$desc_full_fr_title", $desc_full_fr, array('id' => 'desc_full_fr', 'name' => 'desc_full_fr'), true);
		if ($getMultilang)
			$sbform->addTextareaHTML('Article (EN)', $desc_full_en, array('id' => 'desc_full_en', 'name' => 'desc_full_en'), false);
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
			$id         = intval($_POST['id']);
			// --- Titre
			$title_fr     = $sbsanitize->displayText($_POST['title_fr'], 'UTF-8', 1, 0);
			$title        = "[fr]".$title_fr."[/fr]";
			if ($getMultilang) {
				$title_en = $sbsanitize->displayText($_POST['title_en'], 'UTF-8', 1, 0);
				$title   .= "[en]".$title_en."[/en]";				
			}
			// --- Sous Titre
			$subtitle_fr  = $sbsanitize->displayText($_POST['subtitle_fr'], 'UTF-8', 1, 0);
			$subtitle     = "[fr]".$subtitle_fr."[/fr]";
			if ($getMultilang) {
				$subtitle_en = $sbsanitize->displayText($_POST['subtitle_en'], 'UTF-8', 1, 0);
				$subtitle   .= "[en]".$subtitle_en."[/en]";				
			}
			$photo      = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$active     = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

			// ADD or EDIT
			if ($formType == 'categoryadd') {
				// INSERT DATAS
				$query = "INSERT INTO $table_category (title,subtitle,photo,sort,active)
						  VALUES ('$title','$subtitle','$photo','0','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$title_fr = $subtitle_fr = $photo = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Catégorie ajoutée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'categoryedit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_category SET title = '$title'
													,subtitle = '$subtitle'
													,photo = '$photo'
													,active = '$active'
													WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
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
			$title_fr = $subtitle_fr = $photo = $active = '';
		}
		// --------------------------------
		if ($formType == 'categoryedit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id          = intval($_GET['id']);
			$query_1     = "SELECT * FROM $table_category WHERE id = $id";
			$requestQ    = $sbsql->query($query_1);
			$assoc       = $sbsql->assoc($requestQ);
			$title_fr    = $sbsanitize->displayLang(utf8_encode($assoc['title']));
			$subtitle_fr = $sbsanitize->displayLang(utf8_encode($assoc['subtitle']));
			// ----------------------------
			$title_en    = $sbsanitize->displayLang(utf8_encode($assoc['title']), 'en');
			$subtitle_en = $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), 'en');
			// ----------------------------
			$photo       = utf8_encode($assoc['photo']);
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
		$active = ($active == '') ? '1' : $active;
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Titre
		// ----------------------------
		$title_fr_title = ($getMultilang) ? 'Titre (FR)' : 'Titre' ;
		$sbform->addInput('text', "$title_fr_title", array ('name' => 'title_fr', 'value' => "$title_fr", 'placeholder' => "Titre de la catégorie"), true);
		if ($getMultilang)
			$sbform->addInput('text', 'Titre (EN)', array ('name' => 'title_en', 'value' => "$title_en", 'placeholder' => "Titre de votre catégorie (EN)"), true);
		// ----------------------------
		// --- Sous Titre
		// ----------------------------
		$subtitle_fr_title = ($getMultilang) ? 'Sous Titre (FR)' : 'Sous Titre' ;
		$sbform->addInput('text', "$subtitle_fr_title", array ('name' => 'subtitle_fr', 'value' => "$subtitle_fr", 'placeholder' => "Sous Titre de la catégorie"), false);
		if ($getMultilang)
			$sbform->addInput('text', 'Sous Titre (EN)', array ('name' => 'subtitle_en', 'value' => "$subtitle_en", 'placeholder' => "Sous Titre de votre catégorie (EN)"), false);
		// ----------------------------
		// --- Photo
		// ----------------------------
		$sbform->addInput('text', 'Photo', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo", "medias"=>"", 'icon' => 'photo', 'style' => 'width: 100% !important'), false);
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

	case "sort":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$formName        = "sort_form";
		$formType        = "sort";
		$btn_add_edit    = "Valider";
		$legend_add_edit = "Trier les catégories";
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
				$query_sort  = "UPDATE $table_category SET sort = $tri WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = "Les catégories ont été trié avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$sid           = intval($_GET['sid']);
		$query_3       = "SELECT * FROM $table_category ORDER BY sort ASC";
		$requestQ      = $sbsql->query($query_3);
		$sort_array    = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$active = ($sort['active']) ? $sbsanitize->displayLang(utf8_encode($sort['title'])) : "<span style='color: red;'>".$sbsanitize->displayLang(utf8_encode($sort['title']))."</span>";
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = $active;
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_3 . "\n" . 'Form Type = '.$formType);
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&sid=" . $sid;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		//$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les noms de <span style='color: red;'>photos en rouge</span> sont des catégories en statut non visible");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('sid', $sid);
		$sbsmarty->assign('sort', true);
	break;

	case "tpl_list":
	case "tpl_single":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$formName        = "ace_form";
		$formType        = $action;
		$btn_add_edit    = "Valider";
		$legend_add_edit = "Modification du TEMPLATE ($action) pour la catégorie &laquo; <span style='color: red; font-weight: bold;'>%s</span> &raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id      = intval($_POST['id']);
			$content = $sbsanitize->htmlEntitiesDecode($_POST['code_hidden'], 'UTF-8', 1, 0);
			
			// --- EDIT
			if ($id > 0) {

				// UPDATE DATAS
				$query = "UPDATE $table_category SET $action = '$content' WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Template ('.$action.') modifié avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		if (!$_POST['form_submit'] && !$_POST['id']) {
			// --- Get Cat ID
			$catid = intval($_GET['id']);
			// --- Recuperation des donnees
			$query_1   = "SELECT title, $action FROM $table_category WHERE id = '$catid'";
			$requestQ  = $sbsql->query($query_1);
			$assoc     = $sbsql->assoc($requestQ);
			$id        = $catid;
			$content   = $assoc[$action];
			$title_fr  = $sbsanitize->displayLang($assoc['title']); // Legende

			$sbsmarty->assign('assoc', $query_1);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_1 . "\n" . 'Form Type = '.$formType);						
		} else {
			// --- Get Cat ID
			$catid = intval($_GET['id']);
			// --- Recuperation des donnees
			$query_1   = "SELECT title, $action FROM $table_category WHERE id = '$catid'";
			$requestQ  = $sbsql->query($query_1);
			$assoc     = $sbsql->assoc($requestQ);
			$title_fr  = $sbsanitize->displayLang($assoc['title']); // Legende
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --------------------------------
		// Editor ACE (LIST OU SINGLE)
		// --------------------------------
		$sbform->addAnything('<div id="code" style="height: 500px; width: 100%;">' . $sbsanitize->htmlSpecialChars($content) . '</div><p></p>');
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('hidden', '', array('name' => 'code_hidden', 'value' => ""));
		$sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm();
		// --------------------------------
	break;

	case "settings":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = "edit_form";
		$formType        = "settings";
		$btn_add_edit    = "Modifier";
		$legend_add_edit = "Modifier les paramètres des articles";
		$id              = 1; // Paramètres défaut
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id                  = intval($_POST['id']);
			$catid               = '';
			foreach($_POST['catid'] as $val) { $catid .= "$val|"; }
			$catid               = rtrim($catid, '|');
			$catid_module_show   = intval($_POST['catid_module_show']);
			$item_per_page       = intval($_POST['item_per_page']);
			$module_start        = intval($_POST['module_start']);
			$breadcrumb          = intval($_POST['breadcrumb']);
			$title_h1            = intval($_POST['title_h1']);
			$title_h2            = intval($_POST['title_h2']);
			$theme_view_cat      = $sbsanitize->displayText($_POST['theme_view_cat']);
			$theme_view_list     = $sbsanitize->displayText($_POST['theme_view_list']);
			$theme_view_single   = $sbsanitize->displayText($_POST['theme_view_single']);
			$other_news          = intval($_POST['other_news']);
			$other_news_per_page = intval($_POST['other_news_per_page']);
			$other_news_title    = $sbsanitize->displayText($_POST['other_news_title']);
			$other_news_type     = $sbsanitize->displayText($_POST['other_news_type']);
			$news_next_prev      = $sbsanitize->displayText($_POST['news_next_prev']);
			$comments            = $sbsanitize->displayText($_POST['comments']);
			$comments_user       = $sbsanitize->displayText($_POST['comments_user']);
			
			// EDIT
			if ($formType == 'settings' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_settings SET catid = '$catid'
													,catid_module_show = '$catid_module_show'
													,item_per_page = '$item_per_page'
													,module_start = '$module_start'
													,breadcrumb = '$breadcrumb'
													,title_h1 = '$title_h1'
													,title_h2 = '$title_h2'
													,theme_view_cat = '$theme_view_cat'
													,theme_view_list = '$theme_view_list'
													,theme_view_single = '$theme_view_single'
													,other_news = '$other_news'
													,other_news_per_page = '$other_news_per_page'
													,other_news_title = '$other_news_title'
													,other_news_type = '$other_news_type'
													,news_next_prev = '$news_next_prev'
													,comments = '$comments'
													,comments_user = '$comments_user'
													WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Paramètres modifiés avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		if ($formType == 'settings' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$query_1              = "SELECT * FROM $table_settings WHERE id = $id";
			$requestQ             = $sbsql->query($query_1);
			$assoc                = $sbsql->assoc($requestQ);
			$catid                = $assoc['catid'];
			$catid_module_show    = $assoc['catid_module_show'];
			$item_per_page        = $assoc['item_per_page'];
			$module_start         = $assoc['module_start'];
			$breadcrumb           = $assoc['breadcrumb'];
			$title_h1             = $assoc['title_h1'];
			$title_h2             = $assoc['title_h2'];
			$theme_view_cat       = $assoc['theme_view_cat'];
			$theme_view_list      = $assoc['theme_view_list'];
			$theme_view_single    = $assoc['theme_view_single'];
			$other_news           = $assoc['other_news'];
			$other_news_per_page  = $assoc['other_news_per_page'];
			$other_news_title     = utf8_encode($assoc['other_news_title']);
			$other_news_type      = utf8_encode($assoc['other_news_type']);
			$news_next_prev       = utf8_encode($assoc['news_next_prev']);
			$comments             = utf8_encode($assoc['comments']);
			$comments_user        = utf8_encode($assoc['comments_user']);
			
			$sbsmarty->assign('assoc', $query_1);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_1 . "\n" . 'Form Type = '.$formType);						
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
		// -----------------------------------
		// --- THEME VIEW (liste des catégories)
		// -----------------------------------
		$sbform->openSelect("Choix d'une VIEW pour le module (Liste des catégories)", array("id"=>"theme_view_cat", "name"=>"theme_view_cat"), true);
		if ($theme_view_cat == '') $sbform->addOption('Choisissez une view', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($theme['view']); $i++) {
			if ($theme['view'][$i] == $theme_view_cat)
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i], "selected"=>""));
		else
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Choix d'une VIEW pour le module hors visualisation par une PAGE pour la liste des catégories");
		// -----------------------------------
		// --- THEME VIEW (liste des articles)
		// -----------------------------------
		$sbform->openSelect("Choix d'une VIEW pour le module (Liste des articles)", array("id"=>"theme_view_list", "name"=>"theme_view_list"), true);
		if ($theme_view_list == '') $sbform->addOption('Choisissez une view', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($theme['view']); $i++) {
			if ($theme['view'][$i] == $theme_view_list)
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i], "selected"=>""));
		else
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Choix d'une VIEW pour le module hors visualisation par une PAGE pour la liste des articles");
		// -----------------------------------
		// --- THEME VIEW (article)
		// -----------------------------------
		$sbform->openSelect("Choix d'une VIEW pour le module (article)", array("id"=>"theme_view_single", "name"=>"theme_view_single"), true);
		if ($theme_view_single == '') $sbform->addOption('Choisissez une view', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($theme['view']); $i++) {
			if ($theme['view'][$i] == $theme_view_single)
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i], "selected"=>""));
		else
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Choix d'une VIEW pour le module hors visualisation par une PAGE pour un article");
		// ----------------------------
		// --- Article par page
		// ----------------------------
		$sbform->addInput('text', 'Article par page', array ('name' => 'item_per_page', 'value' => "$item_per_page", 'placeholder' => "Article par page", "icon" => "file-text-o", "style" => "width: 150px !important"), true, false, "Nombre d'articles par page dans une catégorie");
		// -----------------------------------
		// --- Gestionnaire de commentaires (Service)
		// -----------------------------------
		$comments_external = ['disqus'];
		$sbform->openSelect("Choix d'un gestionnaire de commentaires", array("id"=>"comments", "name"=>"comments"), false);
		$sbform->addOption('Choisissez un gestionnaire de commentaire', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($comments_external); $i++) {
			if ($comments_external[$i] == $comments)
				$sbform->addOption($comments_external[$i], array ("value"=>$comments_external[$i], "selected"=>""));
		else
				$sbform->addOption($comments_external[$i], array ("value"=>$comments_external[$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Choix d'un gestionnaire de commentaires externe");
		// ----------------------------
		// --- Gestionnaire de commentaires (User)
		// ----------------------------
		$sbform->addInput('text', "Nom d'utilisateur du service de gestionnaire extene", array ('name' => 'comments_user', 'value' => "$comments_user", 'placeholder' => "Utilisateur", "icon" => "comment"), false, false, "Nom (shortname) utilisé pour vous connecter au service de gestionnaire de commentaires externe");
		// ----------------------------
		// --- Démarrage du module
		// ----------------------------
		$module_start = ($module_start == '') ? '0' : $module_start;
		$sbform->addRadioYN('Démarrage du module', true, array('id'=>'module_start', 'name'=>'module_start', 'checked'=>"$module_start"), 'Catégories spécifiques visibles (Liste des articles)', 'Liste des catégories (Par vignettes)', "Comportement du démarrage du module", '<br>');
		// ----------------------------
		// --- Liste des catégories visibles
		// ----------------------------
		$query_cat   = "SELECT * FROM $table_category WHERE active = '1' ORDER BY title ASC";
		$request_cat = $sbsql->query($query_cat);
		$categories  = $sbsql->toarray($request_cat);
		// Initialisation
		$tab_categories = array();
		// --- Tableau des categories
		$array_category = explode("|", $catid);
		for($i = 0; $i < count($categories); ++$i) {
			$tab_categories[$i]['text']    = $sbsanitize->displayLang($categories[$i]['title']);
			$tab_categories[$i]['name']    = 'catid[]';
			$tab_categories[$i]['value']   = $categories[$i]['id'];
			$tab_categories[$i]['checked'] = (in_array($categories[$i]['id'], $array_category)) ? '1' : '0';
		}
		$sbform->addCheckbox('Catégories spécifiques visibles', $tab_categories, '', false, '<br />', "Les catégories choisies seront visibles au démarrage du module si vous avez choisi \"catégories spécifiques visibles\" à l'option \"Démarrage du module\"");
		// ----------------------------
		// --- Comportement des catégories visibles
		// ----------------------------
		$sbform->openSelect("Comportement des catégories spécifiques visibles", array("id"=>"catid_module_show", "name"=>"catid_module_show", "" => ""), false);
		$sbform->addOption('Choisissez une catégorie', array ("value"=>"", "selected"=>""));
		foreach($categories as $row) {
			if ($row['id'] == $catid_module_show)
				$sbform->addOption($sbsanitize->displayLang($row['title']), array ("value"=>$row['id'], "selected"=>""));
		else
				$sbform->addOption($sbsanitize->displayLang($row['title']), array ("value"=>$row['id']));
		}
		$sbform->closeSelect("La catégorie choisie déterminera le comportement de l'affichage des catégories spécifiques visibles. Option valable si vous avez choisi \"catégories spécifiques visibles\" à l'option \"Démarrage du module\"");
		// ----------------------------
		// --- Breadcrumb (fil d'ariane)
		// ----------------------------
		$breadcrumb = ($breadcrumb) ? '1' : '0';
		$sbform->addRadioYN("fil d'ariane (breadcrumb)", true, array('id'=>'breadcrumb', 'name'=>'breadcrumb', 'checked'=>"$breadcrumb"), 'activé', 'désactivé', "Affichage du fil d'ariane");
		// ----------------------------
		// --- Titre H1
		// ----------------------------
		$title_h1 = ($title_h1) ? '1' : '0';
		$sbform->addRadioYN("Titre H1", true, array('id'=>'title_h1', 'name'=>'title_h1', 'checked'=>"$title_h1"), 'activé', 'désactivé', "Affichage du titre H1");
		// ----------------------------
		// --- Titre H2
		// ----------------------------
		$title_h2 = ($title_h2) ? '1' : '0';
		$sbform->addRadioYN("Titre H2", true, array('id'=>'title_h2', 'name'=>'title_h2', 'checked'=>"$title_h2"), 'activé', 'désactivé', "Affichage du titre H2");
		// -----------------------------------
		// --- Comportement NEXT PREV
		// --- Arrow / title
		// -----------------------------------
		$news_next_prev_arr = ['arrow' => 'Flêche suivante précédente'
							  ,'title' => 'Titres des articles suivants précédents'
							  ];
		$sbform->openSelect("Choix du comportement NEXT PREV", array("id"=>"news_next_prev", "name"=>"news_next_prev"), true);
		if ($news_next_prev == '') $sbform->addOption('Choisissez le comportement', array ("value"=>"", "selected"=>""));
		foreach($news_next_prev_arr as $key => $val) {
			if ($key == $news_next_prev)
				$sbform->addOption($val, array ("value"=>$key, "selected"=>""));
		else
				$sbform->addOption($val, array ("value"=>$key));
		}
		// --- Close Select
		$sbform->closeSelect("Choix du comportement de la barre NEXT PREV (Suivant article / Précédent article)");
		// ----------------------------
		// --- Autres articles
		// ----------------------------
		$other_news = ($other_news) ? '1' : '0';
		$sbform->addRadioYN("Autres articles", true, array('id'=>'other_news', 'name'=>'other_news', 'checked'=>"$other_news"), 'activé', 'désactivé', "Affichage des autres articles disponibles dans la même catégorie (Visible dans un article)");
		// ----------------------------
		// --- Autres articles par page
		// ----------------------------
		$sbform->addInput('text', 'Autres articles disponibles par page', array ('name' => 'other_news_per_page', 'value' => "$other_news_per_page", 'placeholder' => "Autres articles par page", "icon" => "file", "style" => "width: 150px !important"), true, false, "Nombre d'autres articles par page dans un article");
		// ----------------------------
		// --- Autres articles TITRE
		// ----------------------------
		$sbform->addInput('text', 'Autres articles (TITRE)', array ('name' => 'other_news_title', 'value' => "$other_news_title", 'placeholder' => "TITRE Autres articles"), false, false, "Titre du bloc Autres Articles");
		// -----------------------------------
		// --- Autres articles (choix des articles)
		// --- Random / latest / first
		// -----------------------------------
		$other_news_type_arr = ['random' => 'Aléatoire (RANDOM)'
							   ,'latest' => 'Les plus récents (DESC)'
							   ,'first'  => 'Les plus anciens (ASC)'
							   ];
		$sbform->openSelect("Choix des autres articles à voir", array("id"=>"other_news_type", "name"=>"other_news_type"), true);
		if ($other_news_type	 == '') $sbform->addOption('Choisissez les articles à voir', array ("value"=>"", "selected"=>""));
		foreach($other_news_type_arr as $key => $val) {
			if ($key == $other_news_type)
				$sbform->addOption($val, array ("value"=>$key, "selected"=>""));
		else
				$sbform->addOption($val, array ("value"=>$key));
		}
		// --- Close Select
		$sbform->closeSelect("Choix d'une VIEW pour le module hors visualisation par une PAGE pour la liste des articles");
		// --------------------------------
		// --- Hiddens / Buttons
		// --------------------------------
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// Radio onchange
		$sbsmarty->assign('radio_change', true);
		// --------------------------------
	break;
	
	case "settingscategory":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = "edit_form";
		$formType        = "settingscategory";
		$btn_add_edit    = "Modifier";
		$legend_add_edit = "Modifier les paramètres de la catégorie &quot;<span style=\"color: red;\">%s</span>&quot;";
		$id              = intval($_GET['id']);
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id                  = intval($_POST['id']);
			$module_show         = $sbsanitize->displayText($_POST['module_show'], 'UTF-8', 1, 0);
			$module_show_masonry = intval($_POST['module_show_masonry']);
			
			// EDIT
			if ($formType == 'settingscategory' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_category SET module_show = '$module_show'
																		 ,module_show_masonry = '$module_show_masonry'
																		 WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Paramètres modifiés avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		if ($formType == 'settingscategory' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$query_1             = "SELECT * FROM $table_category WHERE id = $id";
			$requestQ            = $sbsql->query($query_1);
			$assoc               = $sbsql->assoc($requestQ);
			$module_show         = utf8_encode($assoc['module_show']);
			$module_show_masonry = $assoc['module_show_masonry'];
			$title_fr            = $sbsanitize->displayLang($assoc['title']);
			$sbsmarty->assign('assoc', $query_1);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_1 . "\n" . 'Form Type = '.$formType);						
		} else {
			$title_fr = $_POST['title_fr'];
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		// ----------------------------
		// --- Liste des vues du module (default: normal)
		// ----------------------------
		$module_show_array = ['normal' => 'Vue normal (full width)'
							 ,'float' => 'Vue flottante'
							 ,'masonry' => 'Vue brique (Masonry JS)'
							 ,'masonrycss' => 'Vue brique (Masonry CSS)'
							  ];
		$sbform->openSelect("Affichage des articles", array("id"=>"module_show", "name"=>"module_show", "" => ""), true);
		foreach($module_show_array as $key => $val) {
			if ($key == $module_show)
				$sbform->addOption($val, array ("value"=>$key, "selected"=>""));
		else
				$sbform->addOption($val, array ("value"=>$key));
		}
		$sbform->closeSelect();
		// ----------------------------
		// --- Vue Masonry (pixels)
		// ----------------------------
		$sbform->addInput('text', 'Vue brique (Masonry JS)', array ('name' => 'module_show_masonry', 'value' => "$module_show_masonry", 'placeholder' => "Vue brique (masonry JS) en pixels", "icon" => "building-o", "icon2" => "px"), true, false, "Taille en pixels des colonnes en vue brique (Masonry).<br>Indiquez uniquement la valeur numérique, ne pas mettre <b>'px'</b>.<br>Ne fonctionnera que si vous choisissez l'option 'Vue brique (Masonry JS)'");
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		if (!$_POST['form_submit']) $sbform->addInput('hidden', '', array('name' => 'title_fr', 'value' => "$title_fr"));
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
$sbsmarty->assign('page_title', 'Articles');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($sbsanitize->displayLang($title_fr), 'UTF-8', 0, 1)));

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
