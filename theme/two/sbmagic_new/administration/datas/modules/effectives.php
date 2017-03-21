<?php
/**
 * Admin Startbootstrap
 * EFFECTIVES Module
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
$module_page = 'effectives';
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
$table            = _AM_DB_PREFIX . "sb_effectives";
$table_category   = _AM_DB_PREFIX . "sb_effectives_category";
$table_settings   = _AM_DB_PREFIX . "sb_effectives_settings";
$table_medias     = _AM_DB_PREFIX . "sb_effectives_medias";
$table_production = _AM_DB_PREFIX . "sb_effectives_production";
$table_slider     = _AM_DB_PREFIX . "sb_slider";
$text             = "Effectif";

// -----------------------
// Get Effectives Option
// -----------------------
$query_settings      = "SELECT * FROM $table_settings";
$request_settings    = $sbsql->query($query_settings);
$effectives_settings = $sbsql->assoc($request_settings);


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
		$sb_table_header = ['Date', 'Nom', 'Catégorie', 'Shortcode', 'Actions'];
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
		$query_effectives_total    = "SELECT id FROM $table";
		$query_effectives_active   = "SELECT id FROM $table WHERE active = '1'";
		$query_categories_total    = "SELECT id FROM $table_category";
		$query_categories_active   = "SELECT id FROM $table_category WHERE active = '1'";
		$request_effectives_total  = $sbsql->query($query_effectives_total);
		$numrows_effectives_total  = $sbsql->numrows();
		$sbsmarty->assign('total_effectives', $numrows_effectives_total);
		$request_effectives_active = $sbsql->query($query_effectives_active);
		$numrows_effectives_active = $sbsql->numrows();
		$sbsmarty->assign('total_effectives_active', $numrows_effectives_active);
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
			// --- Check if Effectives in CATEGORY
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
	
	case "mediasdel":
	case "medias":
		// --- Initialization
		$get_id  = intval($_GET['id']);
		// Action DELETE medias
		if ($action == 'mediasdel') {
			$get_mid = intval($_GET['mid']);
			$query_medias = "DELETE FROM $table_medias WHERE id = '$get_mid'";
			$request      = $sbsql->query($query_medias);
			
			if ($request)
				$sb_msg_valid = 'Media supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = ['Tri', 'Titre', 'Effectif', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[4] = "SELECT t1.*, t2.name AS effective_name
					 FROM $table_medias AS t1
					 LEFT JOIN $table AS t2 ON (t1.eid = t2.id)
					 WHERE t1.eid = $get_id";
		$request2  = $sbsql->query($query[4]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('allmedias', $result2);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query[4];
			if (isset($action) && $action == 'mediasdel') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query[4] . "\n";
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}
		// -------------
		
	break;

	case "productiondel":
	case "production":
		// Action DELETE
		if ($action == 'productiondel') {
			$get_id   = intval($_GET['id']);
			$query[2] = "DELETE FROM $table_production WHERE id = '$get_id'";
			$request  = $sbsql->query($query[2]);
			
			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = ['Tri', 'Nom', 'Effectif', 'Actions'];
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$get_eid  = intval($_GET['eid']);
		$query[0] = "SELECT t1.*, t2.name AS effective_name
					 FROM $table_production AS t1
					 LEFT JOIN $table AS t2 ON (t1.eid = t2.id)
					 WHERE t1.eid = $get_eid";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('allproduction', $result2);
		
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
			$id                 = intval($_POST['id']);
			$catid              = intval($_POST['catid']);
			$name               = $sbsanitize->displayText($_POST['name'], 'UTF-8', 1, 0);
			$subtitle1          = $sbsanitize->displayText($_POST['subtitle1'], 'UTF-8', 1, 0);
			$subtitle2          = $sbsanitize->displayText($_POST['subtitle2'], 'UTF-8', 1, 0);
			$chrono             = $sbsanitize->displayText($_POST['chrono'], 'UTF-8', 1, 0);
			$status             = $sbsanitize->displayText($_POST['status'], 'UTF-8', 1, 0);
			$origine            = $sbsanitize->displayText($_POST['origine'], 'UTF-8', 1, 0);
			$pedigree           = $sbsanitize->displayText($_POST['pedigree'], 'UTF-8', 1, 0);
			$pedigree_extend    = $sbsanitize->displayText($_POST['pedigree_extend'], 'UTF-8', 1, 0);
			$pedigree_desc      = $sbsanitize->displayText($_POST['pedigree_desc'], 'UTF-8', 1, 0);			
			list($day, $month, $year) = explode("/", trim($_POST['date']));
			$date               = $sbsanitize->displayText($year.'-'.$month.'-'.$day, 'UTF-8', 1, 0);
			$sire               = $sbsanitize->displayText($_POST['sire'], 'UTF-8', 1, 0);
			$dam                = $sbsanitize->displayText($_POST['dam'], 'UTF-8', 1, 0);
			$sire_dam           = $sbsanitize->displayText($_POST['sire_dam'], 'UTF-8', 1, 0);
			$sex                = $sbsanitize->displayText($_POST['sex'], 'UTF-8', 1, 0);
			$winnings           = $sbsanitize->displayText($_POST['winnings'], 'UTF-8', 1, 0);
			$size               = $sbsanitize->displayText($_POST['size'], 'UTF-8', 1, 0);
			$projection         = $sbsanitize->displayText($_POST['projection'], 'UTF-8', 1, 0);
			$breeder            = $sbsanitize->displayText($_POST['breeder'], 'UTF-8', 1, 0);
			$owner              = $sbsanitize->displayText($_POST['owner'], 'UTF-8', 1, 0);
			// --- Colour (Robe)
			$colour_fr          = $sbsanitize->displayText($_POST['colour_fr'], 'UTF-8', 1, 0);
			$colour             = "[fr]".$colour_fr."[/fr]";
			if ($getMultilang) {
				$colour_en      = $sbsanitize->displayText($_POST['colour_en'], 'UTF-8', 1, 0);
				$colour        .= "[en]".$colour_en."[/en]";				
			}
			// --- Description
			$description_fr     = $sbsanitize->displayText($_POST['description_fr'], 'UTF-8', 1, 0);
			$description        = "[fr]".$description_fr."[/fr]";
			if ($getMultilang) {
				$description_en = $sbsanitize->displayText($_POST['description_en'], 'UTF-8', 1, 0);
				$description   .= "[en]".$description_en."[/en]";				
			}
			// --- Description Extend
			$description_extend_fr = $sbsanitize->displayText($_POST['description_extend_fr'], 'UTF-8', 1, 0);
			$description_extend    = "[fr]".$description_extend_fr."[/fr]";
			if ($getMultilang) {
				$description_extend_en = $sbsanitize->displayText($_POST['description_extend_en'], 'UTF-8', 1, 0);
				$description_extend   .= "[en]".$description_extend_en."[/en]";				
			}
			$photo              = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$headpage           = $sbsanitize->displayText($_POST['headpage'], 'UTF-8', 1, 0);
			$active             = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

	
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (catid,photo,name,subtitle1,subtitle2,chrono,status,origine,pedigree,pedigree_extend,pedigree_desc,date,sire,dam,sire_dam,sex,winnings,size,projection,colour,breeder,owner,description,description_extend,headpage,active,sort)
						  VALUES ('$catid','$photo','$name','$subtitle1','$subtitle2','$chrono','$status','$origine','$pedigree','$pedigree_extend','$pedigree_desc','$date','$sire','$dam','$sire_dam','$sex','$winnings','$size','$projection','$colour','$breeder','$owner','$description','$description_extend','$headpage','$active','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$catid = $photo = $name = $subtitle1 = $subtitle2 = $chrono = $status = $origine = $pedigree = $pedigree_extend = $pedigree_desc = $date = $sire = $dam = $sire_dam = $sex = $winnings = $size = $projection = $colour_fr = $colour_en = $breeder = $owner = $description_fr = $description_en = $description_extend_fr = $description_extend_en = $headpage = $active = '';
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
										   ,subtitle1 = '$subtitle1'
										   ,subtitle2 = '$subtitle2'
										   ,photo = '$photo'
										   ,chrono = '$chrono'
										   ,status = '$status'
										   ,origine = '$origine'
										   ,pedigree = '$pedigree'
										   ,pedigree_extend = '$pedigree_extend'
										   ,pedigree_desc = '$pedigree_desc'
										   ,date = '$date'
										   ,sire = '$sire'
										   ,dam = '$dam'
										   ,sire_dam = '$sire_dam'
										   ,sex = '$sex'
										   ,winnings = '$winnings'
										   ,size = '$size'
										   ,projection = '$projection'
										   ,colour = '$colour'
										   ,breeder = '$breeder'
										   ,owner = '$owner'
										   ,description = '$description'
										   ,description_extend = '$description_extend'
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
			$catid = $photo = $name = $subtitle1 = $subtitle2 = $chrono = $status = $origine = $pedigree = $pedigree_extend = $pedigree_desc = $date = $sire = $dam = $sire_dam = $sex = $winnings = $size = $projection = $colour_fr = $colour_en = $breeder = $owner = $description_fr = $description_en = $description_extend_fr = $description_extend_en = $headpage = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id                    = intval($_GET['id']);
			$query[1]              = "SELECT * FROM $table WHERE id = $id";
			$requestQ              = $sbsql->query($query[1]);
			$assoc                 = $sbsql->assoc($requestQ);
			$catid                 = intval($assoc['catid']);
			$name                  = $sbsanitize->displayLang(utf8_encode($assoc['name']));
			$subtitle1             = $sbsanitize->displayLang(utf8_encode($assoc['subtitle1']));
			$subtitle2             = $sbsanitize->displayLang(utf8_encode($assoc['subtitle2']));
			$chrono                = $sbsanitize->displayLang(utf8_encode($assoc['chrono']));
			$status                = $sbsanitize->displayLang(utf8_encode($assoc['status']));
			$origine               = $sbsanitize->displayLang(utf8_encode($assoc['origine']));
			$pedigree              = $sbsanitize->displayLang(utf8_encode($assoc['pedigree']));
			$pedigree_extend       = $sbsanitize->displayLang(utf8_encode($assoc['pedigree_extend']));
			$pedigree_desc         = $sbsanitize->displayLang(utf8_encode($assoc['pedigree_desc']));
			$sire                  = $sbsanitize->displayLang(utf8_encode($assoc['sire']));
			$dam                   = $sbsanitize->displayLang(utf8_encode($assoc['dam']));
			$sire_dam              = $sbsanitize->displayLang(utf8_encode($assoc['sire_dam']));
			$sex                   = $sbsanitize->displayLang(utf8_encode($assoc['sex']));
			$winnings              = $sbsanitize->displayLang(utf8_encode($assoc['winnings']));
			$size                  = $sbsanitize->displayLang(utf8_encode($assoc['size']));
			$projection            = $sbsanitize->displayLang(utf8_encode($assoc['projection']));
			$breeder               = $sbsanitize->displayLang(utf8_encode($assoc['breeder']));
			$owner                 = $sbsanitize->displayLang(utf8_encode($assoc['owner']));
			// ----------------------------			
			$colour_fr             = $sbsanitize->displayLang(utf8_encode($assoc['colour']));
			$description_fr        = $sbsanitize->displayLang(utf8_encode($assoc['description']));
			$description_extend_fr = $sbsanitize->displayLang(utf8_encode($assoc['description_extend']));
			// ----------------------------
			$colour_en             = $sbsanitize->displayLang(utf8_encode($assoc['colour']), 'en');
			$description_en        = $sbsanitize->displayLang(utf8_encode($assoc['description']), 'en');
			$description_extend_en = $sbsanitize->displayLang(utf8_encode($assoc['description_extend']), 'en');
			// ----------------------------
			$headpage              = $assoc['headpage'];
			$photo                 = utf8_encode($assoc['photo']);
			$date                  = $assoc['date'];
			$active                = $assoc['active'];			

			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		}
		
		// --- Recuperation des donnees
		$query_settings_help   = "SELECT effectives_help FROM $table_settings WHERE id = '1'";
		$request_settings_help = $sbsql->query($query_settings_help);
		$assoc_settings_help   = $sbsql->assoc($request_settings_help);
		$effectives_help       = $assoc_settings_help['effectives_help'];
		$sbsmarty->assign('effectives_help', $sbsanitize->sTrim($effectives_help));
		
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
		// ----------------------------
		// --- Nom
		// ----------------------------
		$sbform->addInput('text', 'Nom', array ('name' => 'name', 'value' => "$name", 'placeholder' => "Nom"), true);
		// ----------------------------
		// --- Sous Titre 1
		// ----------------------------
		if ($effectives_settings['subtitle1'])
			$sbform->addInput('text', 'Sous Titre (1)', array ('name' => 'subtitle1', 'value' => "$subtitle1", 'placeholder' => "Sous Titre 1"), false);
		// ----------------------------
		// -- Sous Titre 2
		// ----------------------------		
		if ($effectives_settings['subtitle2'])
			$sbform->addTextarea('Sous Titre (2)', $subtitle2, array('id' => 'subtitle2', 'name' => 'subtitle2', 'style' => 'height: 150px !important;'), false);
		// ----------------------------
		// --- Photo
		// ----------------------------
		if ($effectives_settings['photo'])
			$sbform->addInput('text', 'Photo', array ('id'=>'inputEffective', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo", "medias"=>"", 'icon' => 'photo'), false);
		// ----------------------------
		// --- Chrono
		// ----------------------------
		if ($effectives_settings['chrono'])
			$sbform->addInput('text', 'Chrono', array ('name' => 'chrono', 'value' => "$chrono", 'placeholder' => "Chrono"), false);
		// ----------------------------
		// --- Status
		// ----------------------------
		if ($effectives_settings['status'])
			$sbform->addInput('text', 'Statut', array ('name' => 'status', 'value' => "$status", 'placeholder' => "Statut"), false);
		// ----------------------------
		// --- Origine (Pays)
		// ----------------------------
		if ($effectives_settings['origine'])
			$sbform->addInput('text', 'Pays', array ('name' => 'origine', 'value' => "$origine", 'placeholder' => "Pays"), false);
		// ----------------------------
		// --- Pedigree
		// ----------------------------
		if ($effectives_settings['pedigree'])
			$sbform->addInput('text', 'Pedigree', array ('id'=>'inputPedigree', 'name' => 'pedigree', 'value' => "$pedigree", 'placeholder' => "Pedigree (PDF)", "medias"=>"", "extension" => "pdf", 'icon' => 'file-pdf-o'), false);
		// ----------------------------
		// --- Pedigree (Extend)
		// ----------------------------
		if ($effectives_settings['pedigree_extend'])
			$sbform->addInput('text', 'Pedigree (Extend)', array ('id'=>'inputPedigreeExtend', 'name' => 'pedigree_extend', 'value' => "$pedigree_extend", 'placeholder' => "Pedigree Extend (PDF)", "medias"=>"", "extension" => "pdf", 'icon' => 'file-pdf-o'), false);
		// ----------------------------
		// --- Pedigree DESC
		// ----------------------------
		if ($effectives_settings['pedigree_desc'])
			$sbform->addTextareaHTML("Pedigree (description)", $pedigree_desc, array('id' => 'pedigree_desc', 'name' => 'pedigree_desc'), false);
		// ----------------------------
		// --- Date Of Birth (DOB)
		// ----------------------------
		$date = ($date == '') ? date('Y-m-d') : $date;
		if ($effectives_settings['date'])
			$sbform->addDate('Date Of Birth', array('id'=>'date', 'name'=>'date', 'value'=>strftime("%d/%m/%Y", strtotime($date)), false));
		// ----------------------------
		// --- Sire
		// ----------------------------
		if ($effectives_settings['sire'])
			$sbform->addInput('text', 'Père', array ('name' => 'sire', 'value' => "$sire", 'placeholder' => "Père"), false);
		// ----------------------------
		// --- Dam
		// ----------------------------
		if ($effectives_settings['dam'])
			$sbform->addInput('text', 'Mère', array ('name' => 'dam', 'value' => "$dam", 'placeholder' => "Mère"), false);
		// ----------------------------
		// --- Sire Dam
		// ----------------------------
		if ($effectives_settings['sire_dam'])
			$sbform->addInput('text', 'Père de mère', array ('name' => 'sire_dam', 'value' => "$sire_dam", 'placeholder' => "Père de mère"), false);
		// -----------------------------------
		// --- Sex
		// -----------------------------------
		if ($effectives_settings['sex']) {
			$sex_array = array('F' => 'Femelle'
							  ,'M' => 'Mâle'
							  ,'H' => 'Hongre');
			$sbform->openSelect("Sexe", array("id"=>"sex", "name"=>"sex"), false);
			$sbform->addOption('Choisissez le sexe', array ("value"=>"", "selected"=>""));
			foreach($sex_array as $key => $val) {
				if ($key == $sex)
					$sbform->addOption($val, array ("value"=>$key, "selected"=>""));
			else
					$sbform->addOption($val, array ("value"=>$key));
			}
			// --- Close Select
			$sbform->closeSelect();
		}
		// ----------------------------
		// --- Winnings (Gains)
		// ----------------------------
		if ($effectives_settings['winnings'])
			$sbform->addInput('text', 'Gains', array ('name' => 'winnings', 'value' => "$winnings", 'placeholder' => "Gains"), false);
		// ----------------------------
		// --- Size (Taille)
		// ----------------------------
		if ($effectives_settings['size'])
			$sbform->addInput('text', 'Taille', array ('name' => 'size', 'value' => "$size", 'placeholder' => "Taille"), false);
		// ----------------------------
		// --- Projection (Saillie)
		// ----------------------------
		if ($effectives_settings['projection'])
			$sbform->addInput('text', 'Saillie', array ('name' => 'projection', 'value' => "$projection", 'placeholder' => "Saillie"), false);
		// ----------------------------
		// --- Robe
		// ----------------------------
		if ($effectives_settings['colour']) {
			$colour_fr_title = ($getMultilang) ? 'Robe (FR)' : 'Robe' ;
			$sbform->addInput('text', "$colour_fr_title", array ('name' => 'colour_fr', 'value' => "$colour_fr", 'placeholder' => "Robe"), false);
			if ($getMultilang)
				$sbform->addInput('text', 'Robe (EN)', array ('name' => 'colour_en', 'value' => "$colour_en", 'placeholder' => "Robe (EN)"), false);
		}
		// ----------------------------
		// --- Breeder (Eleveur)
		// ----------------------------
		if ($effectives_settings['breeder'])
			$sbform->addInput('text', 'Eleveur', array ('name' => 'breeder', 'value' => "$breeder", 'placeholder' => "Eleveur"), false);
		// ----------------------------
		// --- Owner (Propriétaire)
		// ----------------------------
		if ($effectives_settings['owner'])
			$sbform->addInput('text', 'Propriétaire', array ('name' => 'owner', 'value' => "$owner", 'placeholder' => "Propriétaire"), false);
		// ----------------------------
		// --- Description
		// ----------------------------
		if ($effectives_settings['description']) {
			$description_fr_title = ($getMultilang) ? 'Description (FR)' : 'Description' ;
			$sbform->addTextareaHTML("$description_fr_title", $description_fr, array('id' => 'description_fr', 'name' => 'description_fr'), false);
			if ($getMultilang)
				$sbform->addTextareaHTML('Description (EN)', $description_en, array('id' => 'description_en', 'name' => 'description_en'), false);
		}
		// ----------------------------
		// --- Description Extend
		// ----------------------------
		if ($effectives_settings['description_extend']) {
			$description_extend_fr_title = ($getMultilang) ? 'Description Extend (FR)' : 'Description Extend' ;
			$sbform->addTextareaHTML("$description_extend_fr_title", $description_extend_fr, array('id' => 'description_extend_fr', 'name' => 'description_extend_fr'), false);
			if ($getMultilang)
				$sbform->addTextareaHTML('Description Extend (EN)', $description_extend_en, array('id' => 'description_extend_en', 'name' => 'description_extend_en'), false);
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
		$sbsmarty->assign('id', $catid);
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

	case "productionadd":
	case "productionedit":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = ($action == 'productionadd') ? "productionadd_form" : "productionedit_form";
		$formType        = ($action == 'productionadd' || $_POST['form_submit'] == 'productionadd_form') ? "productionadd" : "productionedit";
		$btn_add_edit    = ($action == 'productionadd') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'productionadd') ? "Ajouter une production" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id                 = intval($_POST['id']);
			$eid                = intval($_POST['eid']);
			$name               = $sbsanitize->displayText($_POST['name'], 'UTF-8', 1, 0);
			$sex                = $sbsanitize->displayText($_POST['sex'], 'UTF-8', 1, 0);
			list($day, $month, $year) = explode("/", trim($_POST['date']));
			$date               = $sbsanitize->displayText($year.'-'.$month.'-'.$day, 'UTF-8', 1, 0);
			$colour             = $sbsanitize->displayText($_POST['colour'], 'UTF-8', 1, 0);
			$group_bold         = intval($_POST['group_bold']);
			$dam                = $sbsanitize->displayText($_POST['dam'], 'UTF-8', 1, 0);
			$sire_dam           = $sbsanitize->displayText($_POST['sire_dam'], 'UTF-8', 1, 0);
			// --- Performance
			$performance_fr     = $sbsanitize->displayText($_POST['performance_fr'], 'UTF-8', 1, 0);
			$performance        = "[fr]".$performance_fr."[/fr]";
			if ($getMultilang) {
				$performance_en = $sbsanitize->displayText($_POST['performance_en'], 'UTF-8', 1, 0);
				$performance   .= "[en]".$performance_en."[/en]";				
			}
			$photo              = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$video              = $sbsanitize->displayText($_POST['video'], 'UTF-8', 1, 0);
			$pedigree           = $sbsanitize->displayText($_POST['pedigree'], 'UTF-8', 1, 0);
			$active             = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

	
			// ADD or EDIT
			if ($formType == 'productionadd') {
				// INSERT DATAS
				$query = "INSERT INTO $table_production (eid,name,sex,date,colour,group_bold,dam,sire_dam,performance,photo,video,pedigree,active,sort)
						  VALUES ('$eid','$name','$sex','$date','$colour','$group_bold','$dam','$sire_dam','$performance','$photo','$video','$pedigree','$active','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$eid = $name = $sex = $date = $colour = $group_bold = $dam = $sire_dam = $performance = $performance_fr = $performance_en = $photo = $video = $pedigree = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Production ajoutée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'productionedit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_production SET eid = '$eid'
										   ,name = '$name'
										   ,sex = '$sex'
										   ,date = '$date'
										   ,colour = '$colour'
										   ,group_bold = '$group_bold'
										   ,dam = '$dam'
										   ,sire_dam = '$sire_dam'
										   ,performance = '$performance'
										   ,photo = '$photo'
										   ,video = '$video'
										   ,pedigree = '$pedigree'
										   ,active = '$active'
										   WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Production modifiée avec succès';
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
			$eid = $name = $sex = $date = $colour = $group_bold = $dam = $sire_dam = $performance = $performance_fr = $performance_en = $photo = $video = $pedigree = $active = '';
		}
		// --------------------------------
		if ($formType == 'productionedit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id             = intval($_GET['id']);
			$query[1]       = "SELECT * FROM $table_production WHERE id = $id";
			$requestQ       = $sbsql->query($query[1]);
			$assoc          = $sbsql->assoc($requestQ);
			$eid            = intval($assoc['eid']);
			$name           = $sbsanitize->displayLang(utf8_encode($assoc['name']));
			$sex            = $sbsanitize->displayLang(utf8_encode($assoc['sex']));
			$date           = $assoc['date'];
			$colour         = $sbsanitize->displayLang(utf8_encode($assoc['colour']));
			$group_bold     = intval($assoc['group_bold']);
			$dam            = $sbsanitize->displayLang(utf8_encode($assoc['dam']));
			$sire_dam       = $sbsanitize->displayLang(utf8_encode($assoc['sire_dam']));
			// ----------------------------
			$performance_fr = $sbsanitize->displayLang(utf8_encode($assoc['performance']));
			// ----------------------------
			$performance_en = $sbsanitize->displayLang(utf8_encode($assoc['performance']), 'en');
			// ----------------------------
			$photo          = utf8_encode($assoc['photo']);
			$video          = utf8_encode($assoc['video']);
			$pedigree       = utf8_encode($assoc['pedigree']);
			$active         = $assoc['active'];			

			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id . "&eid=" . $eid;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Liste des effectifs
		// ----------------------------
		$query_effectives   = "SELECT * FROM $table ORDER BY name ASC";
		$request_effectives = $sbsql->query($query_effectives);
		$effectives  = $sbsql->toarray($request_effectives);
		$sbform->openSelect("Effectif", array("id"=>"eid", "name"=>"eid"), true);
		$sbform->addOption('Choisissez un effectif', array ("value"=>"", "selected"=>""));
		foreach($effectives as $row) {
			if ($row['id'] == $eid)
				$sbform->addOption($sbsanitize->displayLang($row['name']), array ("value"=>$row['id'], "selected"=>""));
		else
				$sbform->addOption($sbsanitize->displayLang($row['name']), array ("value"=>$row['id']));
		}
		$sbform->closeSelect();
		// ----------------------------
		// --- Nom (PERMANENT)
		// ----------------------------
		$sbform->addInput('text', 'Nom', array ('name' => 'name', 'value' => "$name", 'placeholder' => "Nom"), true);
		// ----------------------------
		// --- Nom en gras (PERMANENT)
		// ----------------------------
		$group_bold = ($group_bold) ? '1' : '0';
		$sbform->addRadioYN('Gagnant de groupe', true, array('id'=>'group_bold', 'name'=>'group_bold', 'checked'=>"$group_bold"), 'Oui', 'Non', "Mettra le nom en gras");
		// ----------------------------
		// --- Sexe
		// ----------------------------
		if ($effectives_settings['production_sex']) {
			$sex_array = array('F' => 'Femelle'
							  ,'M' => 'Mâle'
							  ,'H' => 'Hongre');
			$sbform->openSelect("Sexe", array("id"=>"sex", "name"=>"sex"), false);
			$sbform->addOption('Choisissez le sexe', array ("value"=>"", "selected"=>""));
			foreach($sex_array as $key => $val) {
				if ($key == $sex)
					$sbform->addOption($val, array ("value"=>$key, "selected"=>""));
			else
					$sbform->addOption($val, array ("value"=>$key));
			}
			// --- Close Select
			$sbform->closeSelect();
		}
		// ----------------------------
		// --- Date
		// ----------------------------
		if ($effectives_settings['production_date']) {
			$date = ($date == '') ? date('Y-m-d') : $date;
			$sbform->addDate('Date', array('id'=>'date', 'name'=>'date', 'value'=>strftime("%d/%m/%Y", strtotime($date)), false));
		}
		// ----------------------------
		// --- Colour
		// ----------------------------
		if ($effectives_settings['production_colour'])
			$sbform->addInput('text', 'Robe', array ('name' => 'colour', 'value' => "$colour", 'placeholder' => "Robe"), false);
		// ----------------------------
		// --- Dam
		// ----------------------------
		if ($effectives_settings['production_dam'])
			$sbform->addInput('text', 'Mère', array ('name' => 'dam', 'value' => "$dam", 'placeholder' => "Mère"), false);
		// ----------------------------
		// --- Sire Dam
		// ----------------------------
		if ($effectives_settings['production_sire_dam'])
			$sbform->addInput('text', 'Père de mère', array ('name' => 'sire_dam', 'value' => "$sire_dam", 'placeholder' => "Père de mère"), false);
		// ----------------------------
		// --- Performance (PERMANENT)
		// ----------------------------
		$performance_fr_title = ($getMultilang) ? 'Performance (FR)' : 'Performance' ;
		$sbform->addTextareaHTML("$performance_fr_title", $performance_fr, array('id' => 'performance_fr', 'name' => 'performance_fr'), true);
		if ($getMultilang)
			$sbform->addTextareaHTML('Performance (EN)', $performance_en, array('id' => 'performance_en', 'name' => 'performance_en'), false);
		// ----------------------------
		// --- Photo
		// ----------------------------
		if ($effectives_settings['production_photo'])
			$sbform->addInput('text', 'Photo', array ('id'=>'inputEffective', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo", "medias"=>"", 'icon' => 'photo'), false);
		// ----------------------------
		// --- Video
		// ----------------------------
		if ($effectives_settings['production_video'])
			$sbform->addInput('text', 'Vidéo Youtube', array ('name' => 'video', 'value' => "$video", 'placeholder' => "Vidéo", "icon" => "youtube"), false, false, "ID de votre vidéo Youtube uniquement ( https://www.youtube.com/watch?v=<strong style='color: red;'>_pVCS8HbrmI</strong> )");
		// ----------------------------
		// --- Pedigree
		// ----------------------------
		if ($effectives_settings['production_pedigree'])
			$sbform->addInput('text', 'Pedigree', array ('id'=>'inputPedigree', 'name' => 'pedigree', 'value' => "$pedigree", 'placeholder' => "Pedigree (PDF)", "medias"=>"", "extension" => "pdf", 'icon' => 'file-pdf-o'), false);
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'productionedit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
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

	case "sortmedias":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$formName        = "sortmedias_form";
		$formType        = "sortmedias";
		$btn_add_edit    = "Valider";
		$legend_add_edit = "Trier les medias";
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
				$query_sort  = "UPDATE $table_medias SET sort = $tri WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = "Les medias ont été trié avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$eid           = intval($_GET['eid']);
		$query[3]      = "SELECT * FROM $table_medias WHERE eid = '$eid' ORDER BY sort ASC";
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
		$formAction = $module_url . "&a=" . $formType . "&eid=" . $eid;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		//$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les noms de <span style='color: red;'>medias en rouge</span> sont des medias en statut non visible");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('eid', $eid);
		$sbsmarty->assign('sort', true);
	break;
	
	case "sortproduction":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$formName        = "sortproduction_form";
		$formType        = "sortproduction";
		$btn_add_edit    = "Valider";
		$legend_add_edit = "Trier les productions";
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
				$query_sort  = "UPDATE $table_production SET sort = $tri WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = "Les productions ont été trié avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$eid           = intval($_GET['eid']);
		$query[3]      = "SELECT * FROM $table_production WHERE eid = '$eid' ORDER BY sort ASC";
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
		$formAction = $module_url . "&a=" . $formType . "&eid=" . $eid;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		//$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les noms de <span style='color: red;'>production en rouge</span> sont des productions en statut non visible");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('eid', $eid);
		$sbsmarty->assign('sort', true);
	break;
	
	case "sorteffectives":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$formName        = "sorteffectives_form";
		$formType        = "sorteffectives";
		$btn_add_edit    = "Valider";
		$legend_add_edit = "Trier les " . strtolower($text) . 's';
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
		$id            = intval($_GET['id']);
		$query[3]      = "SELECT * FROM $table WHERE catid = '$id' ORDER BY sort ASC";
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
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		//$active = ($active) ? '1' : '0';
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les noms d'<span style='color: red;'>".$text."s en rouge</span> sont des ".$text."s en statut non visible");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('id', $id);
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
			//$content = $sbsanitize->htmlEntitiesDecode($_POST['code_hidden'], 'UTF-8', 1, 0);
			$content = $sbsanitize->displayText($_POST['code_hidden'], 'UTF-8', 1, 0);
			
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
			//$content   = $assoc[$action];
			$content   = utf8_encode($assoc[$action]);
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
		$sbform->addAnything('<div id="code" style="height: 500px; width: 100%;">' . $content . '</div><p></p>');
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
			$effectives_per_page      = intval($_POST['effectives_per_page']);
			$module_start             = intval($_POST['module_start']);
			$breadcrumb               = intval($_POST['breadcrumb']);
			$title_h1                 = intval($_POST['title_h1']);
			$title_h2                 = intval($_POST['title_h2']);
			$title_description        = $sbsanitize->displayText($_POST['title_description'], 'UTF-8', 1, 0);
			$title_description_extend = $sbsanitize->displayText($_POST['title_description_extend'], 'UTF-8', 1, 0);
			$title_pedigree           = $sbsanitize->displayText($_POST['title_pedigree'], 'UTF-8', 1, 0);
			$title_production         = $sbsanitize->displayText($_POST['title_production'], 'UTF-8', 1, 0);
			$title_medias             = $sbsanitize->displayText($_POST['title_medias'], 'UTF-8', 1, 0);
			$chrono                   = intval($_POST['chrono']);
			$status                   = intval($_POST['status']);
			$photo                    = intval($_POST['photo']);
			$origine                  = intval($_POST['origine']);
			$pedigree                 = intval($_POST['pedigree']);
			$pedigree_extend          = intval($_POST['pedigree_extend']);
			$pedigree_desc            = intval($_POST['pedigree_desc']);
			$date                     = intval($_POST['date']);
			$sire                     = intval($_POST['sire']);
			$dam                      = intval($_POST['dam']);
			$sire_dam                 = intval($_POST['sire_dam']);
			$sex                      = intval($_POST['sex']);
			$winnings                 = intval($_POST['winnings']);
			$size                     = intval($_POST['size']);
			$projection               = intval($_POST['projection']);
			$colour                   = intval($_POST['colour']);
			$breeder                  = intval($_POST['breeder']);
			$owner                    = intval($_POST['owner']);
			$description              = intval($_POST['description']);
			$description_extend       = intval($_POST['description_extend']);
			$subtitle1                = intval($_POST['subtitle1']);
			$subtitle2                = intval($_POST['subtitle2']);
			$production_sex           = intval($_POST['production_sex']);
			$production_date          = intval($_POST['production_date']);
			$production_colour        = intval($_POST['production_colour']);
			$production_dam           = intval($_POST['production_dam']);
			$production_sire_dam      = intval($_POST['production_sire_dam']);
			$production_photo         = intval($_POST['production_photo']);
			$production_video         = intval($_POST['production_video']);
			$production_pedigree      = intval($_POST['production_pedigree']);
			$theme_view_cat           = $sbsanitize->displayText($_POST['theme_view_cat']);
			$theme_view_list          = $sbsanitize->displayText($_POST['theme_view_list']);
			$theme_view_single        = $sbsanitize->displayText($_POST['theme_view_single']);
			$effectives_help          = $sbsanitize->displayText($_POST['effectives_help'], 'UTF-8', 1, 0);
			
			// EDIT
			if ($formType == 'settings' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_settings SET catid = '$catid'
													,effectives_per_page = '$effectives_per_page'
													,module_start = '$module_start'
													,breadcrumb = '$breadcrumb'
													,title_h1 = '$title_h1'
													,title_h2 = '$title_h2'
													,title_description = '$title_description'
													,title_description_extend = '$title_description_extend'
													,title_pedigree = '$title_pedigree'
													,title_production = '$title_production'
													,title_medias = '$title_medias'
													,chrono = '$chrono'
													,status = '$status'
													,origine = '$origine'
													,photo = '$photo'
													,pedigree = '$pedigree'
													,pedigree_extend = '$pedigree_extend'
													,pedigree_desc = '$pedigree_desc'
													,date = '$date'
													,sire = '$sire'
													,dam = '$dam'
													,sire_dam = '$sire_dam'
													,sex = '$sex'
													,winnings = '$winnings'
													,size = '$size'
													,projection = '$projection'
													,colour = '$colour'
													,breeder = '$breeder'
													,owner = '$owner'
													,description = '$description'
													,description_extend = '$description_extend'
													,subtitle1 = '$subtitle1'
													,subtitle2 = '$subtitle2'
													,production_sex = '$production_sex'
													,production_date = '$production_date'
													,production_colour = '$production_colour'
													,production_dam = '$production_dam'
													,production_sire_dam = '$production_sire_dam'
													,production_photo = '$production_photo'
													,production_video = '$production_video'
													,production_pedigree = '$production_pedigree'
													,theme_view_cat = '$theme_view_cat'
													,theme_view_list = '$theme_view_list'
													,theme_view_single = '$theme_view_single'
													,effectives_help = '$effectives_help'
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
			$effectives_per_page      = $assoc['effectives_per_page'];
			$module_start             = $assoc['module_start'];
			$breadcrumb               = $assoc['breadcrumb'];
			$title_h1                 = $assoc['title_h1'];
			$title_h2                 = $assoc['title_h2'];
			$title_description        = utf8_encode($assoc['title_description']);
			$title_description_extend = utf8_encode($assoc['title_description_extend']);
			$title_pedigree           = utf8_encode($assoc['title_pedigree']);
			$title_production         = utf8_encode($assoc['title_production']);
			$title_medias             = utf8_encode($assoc['title_medias']);
			$chrono                   = $assoc['chrono'];
			$status                   = $assoc['status'];
			$photo                    = $assoc['photo'];
			$origine                  = $assoc['origine'];
			$pedigree                 = $assoc['pedigree'];
			$pedigree_extend          = $assoc['pedigree_extend'];
			$pedigree_desc            = $assoc['pedigree_desc'];
			$date                     = $assoc['date'];
			$sire                     = $assoc['sire'];
			$dam                      = $assoc['dam'];
			$sire_dam                 = $assoc['sire_dam'];
			$sex                      = $assoc['sex'];
			$winnings                 = $assoc['winnings'];
			$size                     = $assoc['size'];
			$projection               = $assoc['projection'];
			$colour                   = $assoc['colour'];
			$breeder                  = $assoc['breeder'];
			$owner                    = $assoc['owner'];
			$description              = $assoc['description'];
			$description_extend       = $assoc['description_extend'];
			$subtitle1                = $assoc['subtitle1'];
			$subtitle2                = $assoc['subtitle2'];
			$production_sex           = $assoc['production_sex'];
			$production_date          = $assoc['production_date'];
			$production_colour        = $assoc['production_colour'];
			$production_dam           = $assoc['production_dam'];
			$production_sire_dam      = $assoc['production_sire_dam'];
			$production_photo         = $assoc['production_photo'];
			$production_video         = $assoc['production_video'];
			$production_pedigree      = $assoc['production_pedigree'];
			$theme_view_cat           = $assoc['theme_view_cat'];
			$theme_view_list          = $assoc['theme_view_list'];
			$theme_view_single        = $assoc['theme_view_single'];
			$effectives_help          = utf8_encode($assoc['effectives_help']);
			
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
		// --- THEME VIEW (liste des effectifs)
		// -----------------------------------
		$sbform->openSelect("Choix d'une VIEW pour le module (Liste des effectifs)", array("id"=>"theme_view_list", "name"=>"theme_view_list"), true);
		if ($theme_view_list == '') $sbform->addOption('Choisissez une view', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($theme['view']); $i++) {
			if ($theme['view'][$i] == $theme_view_list)
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i], "selected"=>""));
		else
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Choix d'une VIEW pour le module hors visualisation par une PAGE pour la liste des effectifs");
		// -----------------------------------
		// --- THEME VIEW (article)
		// -----------------------------------
		$sbform->openSelect("Choix d'une VIEW pour le module (effectif)", array("id"=>"theme_view_single", "name"=>"theme_view_single"), true);
		if ($theme_view_single == '') $sbform->addOption('Choisissez une view', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($theme['view']); $i++) {
			if ($theme['view'][$i] == $theme_view_single)
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i], "selected"=>""));
		else
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Choix d'une VIEW pour le module hors visualisation par une PAGE pour un effectif");
		// ----------------------------
		// --- Démarrage du module
		// ----------------------------
		$module_start = ($module_start == '') ? '0' : $module_start;
		$sbform->addRadioYN('Démarrage du module', true, array('id'=>'module_start', 'name'=>'module_start', 'checked'=>"$module_start"), 'Catégorie spécifique', 'Liste des catégories', "Comportement du démarrage du module", '<br>');
		// ----------------------------
		// --- Article par page
		// ----------------------------
		$sbform->addInput('text', 'Effectifs par page', array ('name' => 'effectives_per_page', 'value' => "$effectives_per_page", 'placeholder' => "Effectifs par page", "icon" => "file-text-o", "style" => "width: 150px !important"), true, false, "Nombre d'effectifs par page dans une catégorie");
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
		// --- Titres Administrables (description, pedigree, production, medias)
		// ----------------------------		
		$sbform->addInput('text', 'Titre DESCRIPTION (TPL SINGLE)', array ('name' => 'title_description', 'value' => "$title_description", 'placeholder' => "Titre de la description"), false);
		$sbform->addInput('text', 'Titre DESCRIPTION EXTEND (TPL SINGLE)', array ('name' => 'title_description_extend', 'value' => "$title_description_extend", 'placeholder' => "Titre de la description extend"), false);
		$sbform->addInput('text', 'Titre PEDIGREE (TPL SINGLE)', array ('name' => 'title_pedigree', 'value' => "$title_pedigree", 'placeholder' => "Titre du pedigree"), false);
		$sbform->addInput('text', 'Titre PRODUCTION (TPL SINGLE)', array ('name' => 'title_production', 'value' => "$title_production", 'placeholder' => "Titre de la production"), false);
		$sbform->addInput('text', 'Titre MEDIAS (TPL SINGLE)', array ('name' => 'title_medias', 'value' => "$title_medias", 'placeholder' => "Titre des medias"), false);
		// ----------------------------
		// -- Effectifs (Aide)
		// ----------------------------		
		$sbform->addTextareaHTML('Aide (Ajouter un effectif)', $effectives_help, array('id' => 'effectives_help', 'name' => 'effectives_help'), false);
		// ----------------------------
		// --- Break
		// ----------------------------
		$sbform->addBreak("Effectifs (formulaire)");
		// ----------------------------
		// --- Effectifs fields
		// ----------------------------
		$chrono             = ($chrono) ? '1' : '0';
		$status             = ($status) ? '1' : '0';
		$photo              = ($photo) ? '1' : '0';
		$origine            = ($origine) ? '1' : '0';
		$pedigree           = ($pedigree) ? '1' : '0';
		$pedigree_extend    = ($pedigree_extend) ? '1' : '0';
		$pedigree_desc      = ($pedigree_desc) ? '1' : '0';
		$date               = ($date) ? '1' : '0';
		$sire               = ($sire) ? '1' : '0';
		$dam                = ($dam) ? '1' : '0';
		$sire_dam           = ($sire_dam) ? '1' : '0';
		$sex                = ($sex) ? '1' : '0';
		$winnings           = ($winnings) ? '1' : '0';
		$size               = ($size) ? '1' : '0';
		$projection         = ($projection) ? '1' : '0';
		$colour             = ($colour) ? '1' : '0';
		$breeder            = ($breeder) ? '1' : '0';
		$owner              = ($owner) ? '1' : '0';
		$description        = ($description) ? '1' : '0';
		$description_extend = ($description_extend) ? '1' : '0';
		$subtitle1          = ($subtitle1) ? '1' : '0';
		$subtitle2          = ($subtitle2) ? '1' : '0';
		
		$sbform->addRadioYN("Chrono", true, array('id'=>'chrono', 'name'=>'chrono', 'checked'=>"$chrono"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'CHRONO'");
		$sbform->addRadioYN("Statut", true, array('id'=>'status', 'name'=>'status', 'checked'=>"$status"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'STATUT'");
		$sbform->addRadioYN("Photo", true, array('id'=>'photo', 'name'=>'photo', 'checked'=>"$photo"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PHOTO'");
		$sbform->addRadioYN("Pays", true, array('id'=>'origine', 'name'=>'origine', 'checked'=>"$origine"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PAYS'");

		$sbform->addRadioYN("Pedigree", true, array('id'=>'pedigree', 'name'=>'pedigree', 'checked'=>"$pedigree"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PEDIGREE'");
		$sbform->addRadioYN("Pedigree Extend", true, array('id'=>'pedigree_extend', 'name'=>'pedigree_extend', 'checked'=>"$pedigree_extend"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PEDIGREE EXTEND'");
		$sbform->addRadioYN("Pedigree (Description)", true, array('id'=>'pedigree_desc', 'name'=>'pedigree_desc', 'checked'=>"$pedigree_desc"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PEDIGREE (DESCRIPTION)'");
		$sbform->addRadioYN("Date", true, array('id'=>'date', 'name'=>'date', 'checked'=>"$date"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'DATE'");
		$sbform->addRadioYN("Père", true, array('id'=>'sire', 'name'=>'sire', 'checked'=>"$sire"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PERE'");
		$sbform->addRadioYN("Mère", true, array('id'=>'dam', 'name'=>'dam', 'checked'=>"$dam"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'MERE'");
		$sbform->addRadioYN("Père de mère", true, array('id'=>'sire_dam', 'name'=>'sire_dam', 'checked'=>"$sire_dam"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PERE DE MERE'");
		$sbform->addRadioYN("Sexe", true, array('id'=>'sex', 'name'=>'sex', 'checked'=>"$sex"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'SEXE'");
		$sbform->addRadioYN("Gains", true, array('id'=>'winnings', 'name'=>'winnings', 'checked'=>"$winnings"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'GAINS'");
		$sbform->addRadioYN("Taille", true, array('id'=>'size', 'name'=>'size', 'checked'=>"$size"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'TAILLE'");
		$sbform->addRadioYN("Saillie", true, array('id'=>'projection', 'name'=>'projection', 'checked'=>"$projection"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'SAILLIE'");
		$sbform->addRadioYN("Robe", true, array('id'=>'colour', 'name'=>'colour', 'checked'=>"$colour"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'ROBE'");
		$sbform->addRadioYN("Eleveur", true, array('id'=>'breeder', 'name'=>'breeder', 'checked'=>"$breeder"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'ELEVEUR'");
		$sbform->addRadioYN("Propriétaire", true, array('id'=>'owner', 'name'=>'owner', 'checked'=>"$owner"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PROPRIETAIRE'");
		$sbform->addRadioYN("Description", true, array('id'=>'description', 'name'=>'description', 'checked'=>"$description"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'DESCRIPTION'");
		$sbform->addRadioYN("Description (Extend)", true, array('id'=>'description_extend', 'name'=>'description_extend', 'checked'=>"$description_extend"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'DESCRIPTION (EXTEND)'");
		$sbform->addRadioYN("Sous Titre 1", true, array('id'=>'subtitle1', 'name'=>'subtitle1', 'checked'=>"$subtitle1"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'SOUS TITRE 1'");
		$sbform->addRadioYN("Sous Titre 2", true, array('id'=>'subtitle2', 'name'=>'subtitle2', 'checked'=>"$subtitle2"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'SOUS TITRE 2'");
		// ----------------------------
		// --- Break
		// ----------------------------
		$sbform->addBreak("Production (formulaire)");
		// ----------------------------
		// --- Sexe (Production)
		// ----------------------------
		$production_sex = ($production_sex) ? '1' : '0';
		$sbform->addRadioYN("Sexe", true, array('id'=>'production_sex', 'name'=>'production_sex', 'checked'=>"$production_sex"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'SEXE'");
		// ----------------------------
		// --- Dob (Production)
		// ----------------------------
		$production_date = ($production_date) ? '1' : '0';
		$sbform->addRadioYN("Date", true, array('id'=>'production_date', 'name'=>'production_date', 'checked'=>"$production_date"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'DATE'");
		// ----------------------------
		// --- Robe (Production)
		// ----------------------------
		$production_colour = ($production_colour) ? '1' : '0';
		$sbform->addRadioYN("Robe", true, array('id'=>'production_colour', 'name'=>'production_colour', 'checked'=>"$production_colour"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'ROBE'");
		// ----------------------------
		// --- Mère (Production)
		// ----------------------------
		$production_dam = ($production_dam) ? '1' : '0';
		$sbform->addRadioYN("Mère", true, array('id'=>'production_dam', 'name'=>'production_dam', 'checked'=>"$production_dam"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'MERE'");
		// ----------------------------
		// --- Père de Mère (Production)
		// ----------------------------
		$production_sire_dam = ($production_sire_dam) ? '1' : '0';
		$sbform->addRadioYN("Père de Mère", true, array('id'=>'production_sire_dam', 'name'=>'production_sire_dam', 'checked'=>"$production_sire_dam"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PER DE MERE'");
		// ----------------------------
		// --- Photo (Production)
		// ----------------------------
		$production_photo = ($production_photo) ? '1' : '0';
		$sbform->addRadioYN("Photo", true, array('id'=>'production_photo', 'name'=>'production_photo', 'checked'=>"$production_photo"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PHOTO'");
		// ----------------------------
		// --- Vidéo (Production)
		// ----------------------------
		$production_video = ($production_video) ? '1' : '0';
		$sbform->addRadioYN("Vidéo", true, array('id'=>'production_video', 'name'=>'production_video', 'checked'=>"$production_video"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'VIDEO'");
		// ----------------------------
		// --- Pedigree (Production)
		// ----------------------------
		$production_pedigree = ($production_pedigree) ? '1' : '0';
		$sbform->addRadioYN("Pedigree", true, array('id'=>'production_pedigree', 'name'=>'production_pedigree', 'checked'=>"$production_pedigree"), 'activé', 'désactivé', "[ADMIN] Affichage du champs 'PEDIGREE'");
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
		$sbform->openSelect("Affichage des effectifs", array("id"=>"module_show", "name"=>"module_show", "" => ""), true);
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

	case "mediasadd":
	case "mediasedit":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = ($action == 'mediasadd') ? "mediasadd_form" : "mediasedit_form";
		$formType        = ($action == 'mediasadd' || $_POST['form_submit'] == 'mediasadd_form') ? "mediasadd" : "mediasedit";
		$btn_add_edit    = ($action == 'mediasadd') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'mediasadd') ? "Ajouter un media" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id         = intval($_POST['id']);
			$eid        = intval($_POST['eid']);
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
			$type       = $sbsanitize->displayText($_POST['type'], 'UTF-8', 1, 0);
			switch($type) {
				case "video":   $url = $sbsanitize->displayText($_POST['video'], 'UTF-8', 1, 0); break;
				case "youtube": $url = $sbsanitize->displayText(basename(parse_url($_POST['youtube'], PHP_URL_PATH)), 'UTF-8', 1, 0); break;
				case "photo":   $url = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0); break;
				case "pdf":     $url = $sbsanitize->displayText($_POST['pdf'], 'UTF-8', 1, 0); break;
			}
			$active     = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

			// ADD or EDIT
			if ($formType == 'mediasadd') {
				// INSERT DATAS
				$query = "INSERT INTO $table_medias (title,subtitle,url,type,eid,active,sort)
						  VALUES ('$title','$subtitle','$url','$type','$eid','$active','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$eid = $title = $title_fr = $title_en = $subtitle = $subtitle_fr = $subtitle_en = $url = $type = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Media ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'mediasedit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_medias SET eid = '$eid'
												  ,title = '$title'
												  ,subtitle = '$subtitle'
												  ,url = '$url'
												  ,type = '$type'
												  ,active = '$active'
												  WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Media modifié avec succès';
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
			$eid = $title = $title_fr = $title_en = $subtitle = $subtitle_fr = $subtitle_en = $url = $type = $active = '';
		}
		// --------------------------------
		if ($formType == 'mediasedit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id          = intval($_GET['id']);
			$query[1]    = "SELECT * FROM $table_medias WHERE id = $id";
			$requestQ    = $sbsql->query($query[1]);
			$assoc       = $sbsql->assoc($requestQ);
			$eid         = intval($assoc['eid']);
			$title_fr    = $sbsanitize->displayLang(utf8_encode($assoc['title']));
			$subtitle_fr = $sbsanitize->displayLang(utf8_encode($assoc['subtitle']));
			// ----------------------------
			$title_en    = $sbsanitize->displayLang(utf8_encode($assoc['title']), 'en');
			$subtitle_en = $sbsanitize->displayLang(utf8_encode($assoc['subtitle']), 'en');
			// ----------------------------
			$active      = $assoc['active'];
			// ----------------------------			
			$type        = $assoc['type'];
			// ----------------------------
			switch($type) {
				case "video":   $video   = utf8_encode($assoc['url']); break;
				case "youtube": $youtube = utf8_encode($assoc['url']); break;
				case "photo":   $photo   = utf8_encode($assoc['url']); break;
				case "pdf":     $pdf     = utf8_encode($assoc['url']); break;
			}
			// ----------------------------
			
			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		}
		
		$name = $title_fr; // Legende
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id . "&eid=" .$eid;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active == '') ? '1' : $active;
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Liste des effectifs
		// ----------------------------
		$query_effectives   = "SELECT * FROM $table ORDER BY name ASC";
		$request_effectives = $sbsql->query($query_effectives);
		$effectives  = $sbsql->toarray($request_effectives);
		$sbform->openSelect("Effectif", array("id"=>"eid", "name"=>"eid"), true);
		$sbform->addOption('Choisissez un effectif', array ("value"=>"", "selected"=>""));
		foreach($effectives as $row) {
			if ($row['id'] == $eid)
				$sbform->addOption($sbsanitize->displayLang($row['name']), array ("value"=>$row['id'], "selected"=>""));
		else
				$sbform->addOption($sbsanitize->displayLang($row['name']), array ("value"=>$row['id']));
		}
		$sbform->closeSelect();
		// ----------------------------
		// --- Titre
		// ----------------------------
		$title_fr_title = ($getMultilang) ? 'Titre (FR)' : 'Titre' ;
		$sbform->addInput('text', "$title_fr_title", array ('name' => 'title_fr', 'value' => "$title_fr", 'placeholder' => "Titre du media"), true);
		if ($getMultilang)
			$sbform->addInput('text', 'Titre (EN)', array ('name' => 'title_en', 'value' => "$title_en", 'placeholder' => "Titre du media (EN)"), true);
		// ----------------------------
		// --- Sous Titre
		// ----------------------------
		$subtitle_fr_title = ($getMultilang) ? 'Sous Titre (FR)' : 'Sous Titre' ;
		$sbform->addInput('text', "$subtitle_fr_title", array ('name' => 'subtitle_fr', 'value' => "$subtitle_fr", 'placeholder' => "Sous Titre du media"), false);
		if ($getMultilang)
			$sbform->addInput('text', 'Sous Titre (EN)', array ('name' => 'subtitle_en', 'value' => "$subtitle_en", 'placeholder' => "Sous Titre du media (EN)"), false);
		// ----------------------------
		// --- Liste des médias
		// ----------------------------
		//$medias_array = ['video' => 'Vidéo'
		//				,'youtube' => 'Vidéo YOUTUBE'
		//				,'photo' => 'Photo'
		//				,'pdf' => 'Fichier PDF'
		//				];
		$medias_array = ['youtube' => 'Vidéo YOUTUBE'
						,'photo' => 'Photo'
						,'pdf' => 'Fichier PDF'
						];
		//$sbform->openSelect("Type de médias", array("id"=>"type", "name"=>"type", "onchange"=>"sbEnabledInput(this.options[this.selectedIndex].value)"), false);
		$sbform->openSelect("Type de médias", array("id"=>"type", "name"=>"type"), false);
		$sbform->addOption('Choisissez un type de médias', array ("value"=>"", "selected"=>""));
		foreach($medias_array as $key => $val) {
			if ($key == $type)
				$sbform->addOption($sbsanitize->displayText($val, 'UTF-8', 1, 0), array ("value"=>$key, "selected"=>""));
			else
				$sbform->addOption($sbsanitize->displayText($val, 'UTF-8', 1, 0), array ("value"=>$key));
		}
		$sbform->closeSelect();
		// ----------------------------
		// --- Video
		// ----------------------------
		//$sbform->addInput('text', 'Vidéo', array ('name' => 'video', 'value' => "$video", 'placeholder' => "Vidéo", "icon" => "video-camera", "disabled" => ""), false);
		// ----------------------------
		// --- Youtube
		// ----------------------------
		$sbform->addInput('text', 'Vidéo Youtube', array ('name' => 'youtube', 'value' => "$youtube", 'placeholder' => "Vidéo Youtube", "icon" => "youtube"), false, false, "ID de votre vidéo Youtube uniquement ( https://www.youtube.com/watch?v=<strong style='color: red;'>_pVCS8HbrmI</strong> )");	
		// ----------------------------
		// --- Photo
		// ----------------------------
		$sbform->addInput('text', 'Photo', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo", "medias"=>"", 'icon' => 'photo'), false);
		// ----------------------------
		// --- Pdf
		// ----------------------------
		$sbform->addInput('text', 'Pdf', array ('id'=>'inputPdf', 'name' => 'pdf', 'value' => "$pdf", 'placeholder' => "Pdf", "medias"=>"", "extension" => "pdf", 'icon' => 'file-pdf-o'), false);
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'mediasedit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
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