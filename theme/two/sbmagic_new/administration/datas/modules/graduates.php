<?php
/**
 * Admin Startbootstrap
 * GRADUATES Module
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

// -----------------------
// Get Multilang Option
// -----------------------
$getMultilang = (sbGetConfig('multilang')) ? sbGetConfig('multilang') : false;

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------
$table            = _AM_DB_PREFIX . "sb_graduates";
$table_category   = _AM_DB_PREFIX . "sb_graduates_category";
$table_settings   = _AM_DB_PREFIX . "sb_graduates_settings";
$table_slider     = _AM_DB_PREFIX . "sb_slider";
$text             = "Graduate";

// -----------------------
// Get Graduates Option
// -----------------------
$query_settings      = "SELECT * FROM $table_settings";
$request_settings    = $sbsql->query($query_settings);
$graduates_settings = $sbsql->assoc($request_settings);


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
		$sb_table_header = ['Nom', 'Catégorie', 'Shortcode', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT t1.*, t2.title AS catname
					 FROM $table AS t1
					 LEFT JOIN $table_category AS t2 ON (t1.catid = t2.id)";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', $result2);
		
		// ----------------------------------------
		// --- News infos
		// ----------------------------------------
		$query_graduates_total     = "SELECT id FROM $table";
		$query_graduates_active    = "SELECT id FROM $table WHERE active = '1'";
		$query_categories_total    = "SELECT id FROM $table_category";
		$query_categories_active   = "SELECT id FROM $table_category WHERE active = '1'";
		$request_graduates_total   = $sbsql->query($query_graduates_total);
		$numrows_graduates_total   = $sbsql->numrows();
		$sbsmarty->assign('total_graduates', $numrows_graduates_total);
		$request_graduates_active  = $sbsql->query($query_graduates_active);
		$numrows_graduates_active  = $sbsql->numrows();
		$sbsmarty->assign('total_graduates_active', $numrows_graduates_active);
		$request_categories_total  = $sbsql->query($query_categories_total);
		$numrows_categories_total  = $sbsql->numrows();
		$sbsmarty->assign('total_categories', $numrows_categories_total);
		$request_categories_active = $sbsql->query($query_categories_active);
		$numrows_categories_active = $sbsql->numrows();
		$sbsmarty->assign('total_categories_active', $numrows_categories_active);
		// ----------------------------------------
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query[0];
			if (isset($action) && $action == 'del') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query[2];
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
			// --- Check if graduates in CATEGORY
			$query_category   = "SELECT * FROM $table WHERE catid = $get_id";
			$request_category = $sbsql->query($query_category);
			$numrows_category = $sbsql->numrows($request_category);
			// -------------------------------------
			if ($numrows_category > 0) {
				$sb_msg_error = 'Cette catégorie contient des '.strtolower($text).'s !!<br>Vous devez supprimer les '.strtolower($text).'s contenus dans cette catégorie avant !!';		
			} else {
				$query[5] = "DELETE FROM $table_category WHERE id = '$get_id'";
				$request  = $sbsql->query($query[5]);
				
				if ($request)
					$sb_msg_valid = 'Catégorie supprimée avec succès';
				else
					$sb_msg_error = 'Error: Write Error (DEL)!';
			}
		}

		// Initialisation
		$sb_table_header = ['Tri', 'Titre', 'Sous Titre', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[4] = "SELECT * FROM $table_category";
		$request2  = $sbsql->query($query[4]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('allcategory', $result2);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query[4];
			if (isset($action) && $action == 'categorydel') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query[5] . "\n" . 'Numrows: ' . $query_category;
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
			$id            = intval($_POST['id']);
			$catid         = intval($_POST['catid']);
			$photo         = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$name          = $sbsanitize->displayText($_POST['name'], 'UTF-8', 1, 0);
			$sire_dam_info = $sbsanitize->displayText($_POST['sire_dam_info'], 'UTF-8', 1, 0);
			$breeder       = $sbsanitize->displayText($_POST['breeder'], 'UTF-8', 1, 0);
			$owner         = $sbsanitize->displayText($_POST['owner'], 'UTF-8', 1, 0);
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
			$perf_6        = $sbsanitize->displayText($_POST['perf_6'], 'UTF-8', 1, 0);
			$video_6       = $sbsanitize->displayText($_POST['video_6'], 'UTF-8', 1, 0);
			$perf_7        = $sbsanitize->displayText($_POST['perf_7'], 'UTF-8', 1, 0);
			$video_7       = $sbsanitize->displayText($_POST['video_7'], 'UTF-8', 1, 0);
			$perf_8        = $sbsanitize->displayText($_POST['perf_8'], 'UTF-8', 1, 0);
			$video_8       = $sbsanitize->displayText($_POST['video_8'], 'UTF-8', 1, 0);
			$perf_9        = $sbsanitize->displayText($_POST['perf_9'], 'UTF-8', 1, 0);
			$video_9       = $sbsanitize->displayText($_POST['video_9'], 'UTF-8', 1, 0);
			$perf_10       = $sbsanitize->displayText($_POST['perf_10'], 'UTF-8', 1, 0);
			$video_10      = $sbsanitize->displayText($_POST['video_10'], 'UTF-8', 1, 0);
			$headpage      = $sbsanitize->displayText($_POST['headpage'], 'UTF-8', 1, 0);
			$active        = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

	
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (catid,photo,name,sire_dam_info,breeder,owner,perf_1,video_1,perf_2,video_2,perf_3,video_3,perf_4,video_4,perf_5,video_5,perf_6,video_6,perf_7,video_7,perf_8,video_8,perf_9,video_9,perf_10,video_10,headpage,active,sort)
						  VALUES ('$catid','$photo','$name','$sire_dam_info','$breeder','$owner','$perf_1','$video_1','$perf_2','$video_2','$perf_3','$video_3','$perf_4','$video_4','$perf_5','$video_5','$perf_6','$video_6','$perf_7','$video_7','$perf_8','$video_8','$perf_9','$video_9','$perf_10','$video_10','$headpage','$active','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$catid = $photo = $name = $sire_dam_info = $breeder = $owner = $perf_1 = $video_1 = $perf_2 = $video_2 = $perf_3 = $video_3 = $perf_4 = $video_4 = $perf_5 = $video_5 = $perf_6 = $video_6 = $perf_7 = $video_7 = $perf_8 = $video_8 = $perf_9 = $video_9 = $perf_10 = $video_10 = $headpage = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET catid = '$catid'
										   ,name = '$name'
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
										   ,perf_6 = '$perf_6'
										   ,video_6 = '$video_6'
										   ,perf_7 = '$perf_7'
										   ,video_7 = '$video_7'
										   ,perf_8 = '$perf_8'
										   ,video_8 = '$video_8'
										   ,perf_9 = '$perf_9'
										   ,video_9 = '$video_9'
										   ,perf_10 = '$perf_10'
										   ,video_10 = '$video_10'
										   ,breeder = '$breeder'
										   ,owner = '$owner'
										   ,headpage = '$headpage'
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
			$catid = $photo = $name = $sire_dam_info = $breeder = $owner = $perf_1 = $video_1 = $perf_2 = $video_2 = $perf_3 = $video_3 = $perf_4 = $video_4 = $perf_5 = $video_5 = $perf_6 = $video_6 = $perf_7 = $video_7 = $perf_8 = $video_8 = $perf_9 = $video_9 = $perf_10 = $video_10 = $headpage = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id            = intval($_GET['id']);
			$query[1]      = "SELECT * FROM $table WHERE id = $id";
			$requestQ      = $sbsql->query($query[1]);
			$assoc         = $sbsql->assoc($requestQ);
			$catid         = intval($assoc['catid']);
			$name          = $sbsanitize->displayLang(utf8_encode($assoc['name']));
			$sire_dam_info = utf8_encode($assoc['sire_dam_info']);
			$breeder       = utf8_encode($assoc['breeder']);
			$owner         = utf8_encode($assoc['owner']);
			$photo         = $assoc['photo'];
			$headpage      = $assoc['headpage'];
			$active        = $assoc['active'];
			for($i = 1; $i < 11; $i++) {
				$perf_{$i}  = utf8_encode($assoc['perf_'.$i]);
				$video_{$i} = $assoc['video_'.$i];
			}

			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		}
		
		// --- Recuperation des donnees
		$query_settings_help   = "SELECT graduates_help FROM $table_settings WHERE id = '1'";
		$request_settings_help = $sbsql->query($query_settings_help);
		$assoc_settings_help   = $sbsql->assoc($request_settings_help);
		$graduates_help        = $assoc_settings_help['graduates_help'];
		$sbsmarty->assign('graduates_help', $sbsanitize->sTrim($graduates_help));
		
		// --------------------------------
		// --- Get INFOS CMS Theme / Modules
		// --------------------------------
		// --- Include Theme Config
		include_once(SB_THEME_DIR . 'config.php');
		// --- Assign theme headpage
		if (!empty($theme['headpage'])) {
			$query_slider   = "SELECT * FROM $table_slider WHERE active = '1' ORDER BY title ASC";
			$request_slider = $sbsql->query($query_slider);
			$slider_array   = $sbsql->toarray($request_slider);
			$sbsmarty->assign('theme_headpage', $slider_array);
			$sbsmarty->assign('show_headpage', true);
			$sbsmarty->assign('headpage', $headpage); // If edit
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
		// --- Liste des catégorie
		// ----------------------------
		$query_cat   = "SELECT * FROM $table_category ORDER BY title ASC";
		$request_cat = $sbsql->query($query_cat);
		$categories  = $sbsql->toarray($request_cat);
		$sbform->openSelect("Catégories", array("id"=>"catid", "name"=>"catid", "" => ""), true);
		$sbform->addOption('Choisissez une catégorie', array ("value"=>"", "selected"=>""));
		foreach($categories as $row) {
			if ($row['id'] == $catid)
				$sbform->addOption($sbsanitize->displayLang($row['title']), array ("value"=>$row['id'], "selected"=>""));
		else
				$sbform->addOption($sbsanitize->displayLang($row['title']), array ("value"=>$row['id']));
		}
		$sbform->closeSelect();
		// --------------------------------
		// Infos
		// --------------------------------		
		$sbform->addInput('text', 'Nom', array ('name' => 'name', 'value' => "$name", 'placeholder' => "Cheval"), true);
		$sbform->addInput('text', 'Origine', array ('name' => 'sire_dam_info', 'value' => "$sire_dam_info", 'placeholder' => "Père & Mère & Père de Mère"));
		$sbform->addInput('text', 'Entraîneur', array ('name' => 'breeder', 'value' => "$breeder", 'placeholder' => "Entraîneur"));
		$sbform->addInput('text', 'Propriétaire', array ('name' => 'owner', 'value' => "$owner", 'placeholder' => "Propriétaire"));
		// --------------------------------	
		// --- Photo
		// --------------------------------	
		$sbform->addInput('text', 'Photo', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo", "medias"=>"", 'icon' => 'photo'));
		// --------------------------------
		// Performances / videos
		// --------------------------------	
		for($i = 1; $i < 11; $i++) {
			$sbform->addInput('text', "Performance ($i)", array("name"=>"perf_$i", "value"=>$perf_{$i}, "placeholder"=>"Titre performance $i"));
			$sbform->addInput('text', "Vidéo URL ($i)", array("name"=>"video_$i", "value"=>$video_{$i}, "placeholder"=>"Vidéo $i ( http:// )", 'icon' => 'video-camera'));
		}
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'edit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('hidden', '', array('name' => 'headpage', 'id' => 'headpage', 'value' => "$headpage"));
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
				$query = "INSERT INTO $table_category (title,subtitle,photo,sort)
						  VALUES ('$title','$subtitle','$photo','0')";
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
			$query[1]    = "SELECT * FROM $table_category WHERE id = $id";
			$requestQ    = $sbsql->query($query[1]);
			$assoc       = $sbsql->assoc($requestQ);
			$title_fr    = $sbsanitize->displayLang(utf8_encode($assoc['title']));
			$subtitle_fr = $sbsanitize->displayLang(utf8_encode($assoc['subtitle']));
			// ----------------------------
			$title_en    = $sbsanitize->displayLang(utf8_encode($assoc['title']), 'en');
			$subtitle_en = $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), 'en');
			// ----------------------------
			$photo       = utf8_encode($assoc['photo']);
			$active      = $assoc['active'];

			$name        = $sbsanitize->displayLang(utf8_encode($assoc['title'])); // Legende
			
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
		$query[3]      = "SELECT * FROM $table_category ORDER BY sort ASC";
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
		$formAction = $module_url . "&a=" . $formType . "&sid=" . $sid;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		//$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les noms de <span style='color: red;'>catégories en rouge</span> sont des catégories en statut non visible");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('sid', $sid);
		$sbsmarty->assign('sort', true);
	break;
	
	case "sortgraduates":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$formName        = "sortgraduates_form";
		$formType        = "sortgraduates";
		$btn_add_edit    = "Valider";
		$legend_add_edit = "Trier les " . strtolower($text) . 's';
		$catid           = intval($_REQUEST['catid']);
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
				$sb_msg_valid = "Les ".$text."s ont été trié avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$query[3]      = "SELECT * FROM $table WHERE catid = '$catid' ORDER BY sort ASC";
		$requestQ      = $sbsql->query($query[3]);
		$sort_array    = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$active = ($sort['active']) ? $sbsanitize->displayLang(utf8_encode($sort['name'])) : "<span style='color: red;'>".$sbsanitize->displayLang(utf8_encode($sort['name']))."</span>";
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = $active;
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[3]	 . "\n" . 'Form Type = '.$formType);
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&catid=" . $catid;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		//$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les noms de <span style='color: red;'>".$text."s en rouge</span> sont des ".$text."s en statut non visible");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
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
			$query[1]  = "SELECT title, $action FROM $table_category WHERE id = '$catid'";
			$requestQ  = $sbsql->query($query[1]);
			$assoc     = $sbsql->assoc($requestQ);
			$id        = $catid;
			$content   = $assoc[$action];
			$name      = $sbsanitize->displayLang($assoc['title']);

			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
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
		$legend_add_edit = "Modifier les paramètres des " . strtolower($text) . 's';
		$id              = 1; // Paramètres défaut
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id                       = intval($_POST['id']);
			$catid                    = intval($_POST['catid']);
			$graduates_per_page       = intval($_POST['graduates_per_page']);
			$module_start             = intval($_POST['module_start']);
			$breadcrumb               = intval($_POST['breadcrumb']);
			$title_h1                 = intval($_POST['title_h1']);
			$title_h2                 = intval($_POST['title_h2']);
			$theme_view_cat           = $sbsanitize->displayText($_POST['theme_view_cat']);
			$theme_view_list          = $sbsanitize->displayText($_POST['theme_view_list']);
			$theme_view_single        = $sbsanitize->displayText($_POST['theme_view_single']);
			$graduates_help           = $sbsanitize->displayText($_POST['graduates_help']);
			
			// EDIT
			if ($formType == 'settings' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_settings SET catid = '$catid'
													,graduates_per_page = '$graduates_per_page'
													,module_start = '$module_start'
													,breadcrumb = '$breadcrumb'
													,title_h1 = '$title_h1'
													,title_h2 = '$title_h2'
													,theme_view_cat = '$theme_view_cat'
													,theme_view_list = '$theme_view_list'
													,theme_view_single = '$theme_view_single'
													,graduates_help = '$graduates_help'
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
			$query[1]                 = "SELECT * FROM $table_settings WHERE id = $id";
			$requestQ                 = $sbsql->query($query[1]);
			$assoc                    = $sbsql->assoc($requestQ);
			$catid                    = $assoc['catid'];
			$graduates_per_page       = $assoc['graduates_per_page'];
			$module_start             = $assoc['module_start'];
			$breadcrumb               = $assoc['breadcrumb'];
			$title_h1                 = $assoc['title_h1'];
			$title_h2                 = $assoc['title_h2'];
			$theme_view_cat           = $assoc['theme_view_cat'];
			$theme_view_list          = $assoc['theme_view_list'];
			$theme_view_single        = $assoc['theme_view_single'];
			$graduates_help          = utf8_encode($assoc['graduates_help']);
			
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
		// --- THEME VIEW (liste des graduates)
		// -----------------------------------
		$sbform->openSelect("Choix d'une VIEW pour le module (Liste des graduates)", array("id"=>"theme_view_list", "name"=>"theme_view_list"), true);
		if ($theme_view_list == '') $sbform->addOption('Choisissez une view', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($theme['view']); $i++) {
			if ($theme['view'][$i] == $theme_view_list)
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i], "selected"=>""));
		else
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Choix d'une VIEW pour le module hors visualisation par une PAGE pour la liste des graduates");
		// -----------------------------------
		// --- THEME VIEW (article)
		// -----------------------------------
		$sbform->openSelect("Choix d'une VIEW pour le module (graduates)", array("id"=>"theme_view_single", "name"=>"theme_view_single"), true);
		if ($theme_view_single == '') $sbform->addOption('Choisissez une view', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($theme['view']); $i++) {
			if ($theme['view'][$i] == $theme_view_single)
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i], "selected"=>""));
		else
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Choix d'une VIEW pour le module hors visualisation par une PAGE pour un graduate");
		// ----------------------------
		// --- Démarrage du module
		// ----------------------------
		$module_start = ($module_start == '') ? '0' : $module_start;
		$sbform->addRadioYN('Démarrage du module', true, array('id'=>'module_start', 'name'=>'module_start', 'checked'=>"$module_start"), 'Catégorie spécifique', 'Liste des catégories', "Comportement du démarrage du module", '<br>');
		// ----------------------------
		// --- Article par page
		// ----------------------------
		$sbform->addInput('text', 'Graduates par page', array ('name' => 'graduates_per_page', 'value' => "$graduates_per_page", 'placeholder' => "Graduates par page", "icon" => "file-text-o", "style" => "width: 150px !important"), true, false, "Nombre de graduates par page dans une catégorie");
		// ----------------------------
		// --- Liste des catégorie
		// ----------------------------
		$query_cat   = "SELECT * FROM $table_category ORDER BY title ASC";
		$request_cat = $sbsql->query($query_cat);
		$categories  = $sbsql->toarray($request_cat);
		$sbform->openSelect("Catégories", array("id"=>"catid", "name"=>"catid", "" => ""), false);
		$sbform->addOption('Choisissez une catégorie', array ("value"=>"", "selected"=>""));
		foreach($categories as $row) {
			if ($row['id'] == $catid)
				$sbform->addOption($sbsanitize->displayLang($row['title']), array ("value"=>$row['id'], "selected"=>""));
		else
				$sbform->addOption($sbsanitize->displayLang($row['title']), array ("value"=>$row['id']));
		}
		$sbform->closeSelect("La catégorie choisie sera visible au démarrage du module si vous avez choisi \"catégorie spécifique\" à l'option \"Démarrage du module\"");
		// ----------------------------
		// --- Breadcrumb (fil d'ariane)
		// ----------------------------
		$breadcrumb = ($breadcrumb) ? '1' : '0';
		$sbform->addRadioYN("fil d'ariane (breadcrumb)", true, array('id'=>'breadcrumb', 'name'=>'breadcrumb', 'checked'=>"$breadcrumb"), 'activé', 'désactivé', "Affichage du fil d'ariane");
		// ----------------------------
		// --- Titre H1
		// ----------------------------
		$title_h1 = ($title_h1) ? '1' : '0';
		$sbform->addRadioYN("Titre H1", true, array('id'=>'title_h1', 'name'=>'title_h1', 'checked'=>"$title_h1"), 'activé', 'désactivé', "Affichage du titre H1 (Titre du module)");
		// ----------------------------
		// --- Titre H2
		// ----------------------------
		$title_h2 = ($title_h2) ? '1' : '0';
		$sbform->addRadioYN("Titre H2", true, array('id'=>'title_h2', 'name'=>'title_h2', 'checked'=>"$title_h2"), 'activé', 'désactivé', "Affichage du titre H2 (Titre de la catégorie)");
		// ----------------------------
		// -- Graduates (Aide)
		// ----------------------------		
		$sbform->addTextareaHTML('Aide (Ajouter un graduate)', $graduates_help, array('id' => 'graduates_help', 'name' => 'graduates_help'), false);
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
		// --------------------------------
	break;

	case "settingscategory":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = "edit_form";
		$formType        = "settingscategory";
		$btn_add_edit    = "Modifier";
		$legend_add_edit = "Modifier les paramètres de la catégorie '<span style=\"color: red;\">%s</span>'";
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
					$sb_msg_valid = 'Paramètres de la catégorie modifiés avec succès';
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
			$query[1]            = "SELECT * FROM $table_category WHERE id = $id";
			$requestQ            = $sbsql->query($query[1]);
			$assoc               = $sbsql->assoc($requestQ);
			$catid               = $assoc['catid'];
			$module_show         = utf8_encode($assoc['module_show']);
			$module_show_masonry = $assoc['module_show_masonry'];
			
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
		// ----------------------------
		// --- Liste des vues du module (default: normal)
		// ----------------------------
		$module_show_array = ['normal' => 'Vue normal (full width)'
							 ,'float' => 'Vue flottante'
							 ,'masonry' => 'Vue brique (Masonry JS)'
							 ,'masonrycss' => 'Vue brique (Masonry CSS)'
							  ];
		$sbform->openSelect("Affichage des graduates", array("id"=>"module_show", "name"=>"module_show", "" => ""), true);
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
		$sbform->addInput('text', 'Vue brique (Masonry JS)', array ('name' => 'module_show_masonry', 'value' => "$module_show_masonry", 'placeholder' => "Vue brique (masonry JS) en pixels", "icon" => "building-o", "icon2" => "px"), true, false, "Taille (largeur) en pixels des colonnes en vue brique (Masonry JS).<br>Indiquez uniquement la valeur numérique, ne pas mettre <b>'px'</b>.<br>Ne fonctionnera que si vous choisissez l'option 'Vue brique (Masonry JS)'");
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
		// --------------------------------

		$name = $sbsanitize->displayLang($assoc['title']);
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
$sbsmarty->assign('page_title', $text . 's');
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