<?php
/**
 * Admin Startbootstrap
 * Manage PAGES (Free pages)
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
$module_page = 'pages';
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
$table        = _AM_DB_PREFIX . "sb_pages";
$table_sort   = _AM_DB_PREFIX . "sb_blocs_sort";
$table_slider = _AM_DB_PREFIX . "sb_slider";

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
				$sb_msg_valid = 'Page supprimée avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = array('Titre', 'Theme View', 'Module View', 'Url', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT * FROM $table";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('allpage', $result2);
		
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
		$legend_add_edit = ($action == 'add') ? "Ajouter une page" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id              = intval($_POST['id']);
			// --- Menu
			$menu_fr         = $sbsanitize->displayText($_POST['menu_fr'], 'UTF-8', 1, 0);
			$menu            = "[fr]".$menu_fr."[/fr]";			
			if ($getMultilang) {
				$menu_en = $sbsanitize->displayText($_POST['menu_en'], 'UTF-8', 1, 0);				
				$menu   .= "[en]".$menu_en."[/en]";
			}
			// --- Titre
			$title_fr        = $sbsanitize->displayText($_POST['title_fr'], 'UTF-8', 1, 0);
			$title           = "[fr]".$title_fr."[/fr]";
			if ($getMultilang) {
				$title_en = $sbsanitize->displayText($_POST['title_en'], 'UTF-8', 1, 0);
				$title   .= "[en]".$title_en."[/en]";				
			}
			// --- Content
			$content_fr      = $sbsanitize->displayText($_POST['content_fr'], 'UTF-8', 1, 0);
			$content         = "[fr]".$content_fr."[/fr]";
			if ($getMultilang) {
				$content_en = $sbsanitize->displayText($_POST['content_en'], 'UTF-8', 1, 0);
				$content   .= "[en]".$content_en."[/en]";				
			}
			$seo_url         = $sbsanitize->displayText($_POST['seo_url'], 'UTF-8', 1, 0);
			if (trim($seo_url) != '') {
				$seo_url = trim(strtolower($sbsanitize->rewriteString($_POST['seo_url'])));
			} else {
				// Check if seo url is homepage
				if (strtolower($sbsanitize->rewriteString($title_fr)) == 'accueil' ||
					strtolower($sbsanitize->rewriteString($title_fr)) == 'home' ||
					strtolower($sbsanitize->rewriteString($title_fr)) == 'homepage')
					$seo_url = "";
				else
					$seo_url = strtolower($sbsanitize->rewriteString($title_fr));
			}
			$seo_keywords    = $sbsanitize->displayText($_POST['seo_keywords'], 'UTF-8', 1, 0);
			$seo_description = $sbsanitize->displayText($_POST['seo_description'], 'UTF-8', 1, 0);
			$theme_view      = $sbsanitize->displayText($_POST['theme_view'], 'UTF-8', 1, 0);
			$module_view     = $sbsanitize->displayText($_POST['module_view'], 'UTF-8', 1, 0);
			$headpage        = $sbsanitize->displayText($_POST['headpage'], 'UTF-8', 1, 0);
			$active          = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			$url_custom      = '';
	
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (menu,title,content,seo_url,url_custom,seo_keywords,seo_description,active,theme_view,module_view,headpage,sort)
						  VALUES ('$menu','$title','$content','$seo_url','$url_custom','$seo_keywords','$seo_description','$active','$theme_view','$module_view','$headpage','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$menu_fr = $menu_en = $title_fr = $title_en = $content_fr = $content_en = $seo_url = $seo_keywords = $seo_description = $theme_view = $headpage = $module_view = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Page ajoutée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET menu = '$menu'
										   ,title = '$title'
										   ,content = '$content'
										   ,seo_url = '$seo_url'
										   ,seo_keywords = '$seo_keywords'
										   ,seo_description = '$seo_description'
										   ,active = '$active'
										   ,theme_view = '$theme_view'
										   ,module_view = '$module_view'
										   ,headpage = '$headpage'
										   WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Page modifiée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . ' - CS: ' . $cs . "\n" . 'Submit Form Type = '.$formType);
			
		} else {
			// Si AJOUT (First time)
			// --- Vider les champs du formulaire
			$menu_fr = $menu_en = $title_fr = $title_en = $content_fr = $content_en = $seo_url = $seo_keywords = $seo_description = $active = $theme_view = $headpage = $module_view = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id              = intval($_GET['id']);
			$query[1]        = "SELECT * FROM $table WHERE id = $id";
			$requestQ        = $sbsql->query($query[1]);
			$assoc           = $sbsql->assoc($requestQ);
			$menu_fr         = $sbsanitize->displayLang(utf8_encode($assoc['menu']));
			$title_fr        = $sbsanitize->displayLang(utf8_encode($assoc['title']));
			$content_fr      = $sbsanitize->displayLang(utf8_encode($assoc['content']));
			// ---------------------------
			$menu_en         = $sbsanitize->displayLang(utf8_encode($assoc['menu']), 'en');
			$title_en        = $sbsanitize->displayLang(utf8_encode($assoc['title']), 'en');
			$content_en      = $sbsanitize->displayLang(utf8_encode($assoc['content']), 'en');
			// ---------------------------
			$seo_url         = utf8_encode($assoc['seo_url']);
			$seo_keywords    = utf8_encode($assoc['seo_keywords']);
			$seo_description = utf8_encode($assoc['seo_description']);
			$theme_view      = $assoc['theme_view'];
			$module_view     = $assoc['module_view'];
			$headpage        = $assoc['headpage'];
			$active          = $assoc['active'];

			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		}
		
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
			$sbsmarty->assign('theme_headpage',  $slider_array);
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
		// --- Input TEXT with icon / icon2
		// ----------------------------
		$sbform->addInput('text', 'URL de votre page', array ('name' => 'seo_url', 'value' => "$seo_url", 'placeholder' => "Url de la page", 'icon' => '0'.$seo_url_cms), false);
		// -----------------------------------
		// --- THEME VIEW
		// -----------------------------------
		$sbform->openSelect("Choix du template (VIEW)", array("id"=>"theme_view", "name"=>"theme_view"), true);
		if ($theme_view == '') $sbform->addOption('Choisissez un template', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($theme['view']); $i++) {
			if ($theme['view'][$i] == $theme_view)
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i], "selected"=>""));
		else
				$sbform->addOption($theme['view'][$i], array ("value"=>$theme['view'][$i]));
		}
		// --- Close Select
		$sbform->closeSelect();
		// -----------------------------------
		// --- MODULE VIEW
		// -----------------------------------
		// --- Get module
		$result_modules_dir = sbGetModulesPage();
		$sbform->openSelect("Choix du module (VIEW)", array("id"=>"module_view", "name"=>"module_view"), false);
		if ($module_view == '')
			$sbform->addOption('Pas de module pour cette page', array ("value"=>"", "selected"=>""));
		else
			$sbform->addOption('Pas de module pour cette page', array ("value"=>""));
		for($i = 0; $i < count($result_modules_dir); $i++) {
			if ($result_modules_dir[$i] == $module_view)
				$sbform->addOption($result_modules_dir[$i], array ("value"=>$result_modules_dir[$i], "selected"=>""));
			else
				$sbform->addOption($result_modules_dir[$i], array ("value"=>$result_modules_dir[$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Le choix du module peut être défini par défaut pour la page d'accueil uniquement dans la configuration fichier (SBCONFIG => SBMODULEINDEX)<br>Si vous choisissez un module pour la page d'accueil, la configuration fichier ne sera plus active");
		// -----------------------------------
		// --- VARIOUS HTML CONTENT FILES
		// -----------------------------------
		// --- Get various page
		$result_various_dir = sbGetVariousPage('page'); // Type 'page' or 'block'
		$sbform->openSelect("Choix de contenu HTML additionnel", array("id"=>"various_view", "name"=>"various_view"), false);
		if ($various_view == '')
			$sbform->addOption('Pas de contenu HTML additionnel pour cette page', array ("value"=>"", "selected"=>""));
		else
			$sbform->addOption('Pas de contenu HTML additionnel pour cette page', array ("value"=>""));
		for($i = 0; $i < count($result_various_dir); $i++) {
			if ($result_various_dir[$i] == $various_view)
				$sbform->addOption($result_various_dir[$i], array ("value"=>$result_various_dir[$i], "selected"=>""));
			else
				$sbform->addOption($result_various_dir[$i], array ("value"=>$result_various_dir[$i]));
		}
		// --- Close Select
		$sbform->closeSelect("En sélectionnant un fichier HTML vous pouvez ajouter du contenu additionnel à votre page.<br>Celui-ci s'ajoutera à la fin du contenu dynamique.");
		// --------------------------------
		// Menu FR / EN
		// --------------------------------
		$menu_fr_title = ($getMultilang) ? 'Menu (FR)' : 'Menu' ;
		$sbform->addInput('text', "$menu_fr_title", array ('name' => 'menu_fr', 'value' => "$menu_fr", 'placeholder' => "Titre de votre menu"), true);
		if ($getMultilang)
			$sbform->addInput('text', 'Menu (EN)', array ('name' => 'menu_en', 'value' => "$menu_en", 'placeholder' => "Titre de votre menu (EN)"), true);
		// --------------------------------
		// Title FR / EN
		// --------------------------------
		$title_fr_title = ($getMultilang) ? 'Titre (FR)' : 'Titre' ;
		$sbform->addInput('text', "$title_fr_title", array ('name' => 'title_fr', 'value' => "$title_fr", 'placeholder' => "Titre de votre page"), true, false, "Titre : Les termes \"Accueil\", \"Home\", \"Homepage\" font de cette page la page d'accueil");
		if ($getMultilang)
			$sbform->addInput('text', 'Titre (EN)', array ('name' => 'title_en', 'value' => "$title_en", 'placeholder' => "Titre de votre page (EN)"), true);
		// --------------------------------
		// SEO Keywords / Description
		// --------------------------------
		$sbform->addTextarea('SEO Keywords', $seo_keywords, array('id' => 'seo_keywords', 'name' => 'seo_keywords', 'style' => 'height: 70px !important;'), false, "Les mots clés doivent être séparés par des virgules");
		$sbform->addTextarea('SEO Description', $seo_description, array('id' => 'seo_description', 'name' => 'seo_description', 'style' => 'height: 120px !important;'), false, "Google limite la META DESCRIPTION à 155 caractères aujourd'hui... Jusqu'à changement...");
		// ----------------------------
		// --- Editeur Contenu FR / EN
		// ----------------------------
		$content_fr_title = ($getMultilang) ? 'Contenu (FR)' : 'Contenu' ;
		$sbform->addTextareaHTML("$content_fr_title", $content_fr, array('id' => 'content_fr', 'name' => 'content_fr'), true);
		if ($getMultilang)
			$sbform->addTextareaHTML('Contenu (EN)', $content_en, array('id' => 'content_en', 'name' => 'content_en'), false);
		//$sbform->addPageBuilder('', $src = '', $model = '', $arrArgs = array (), $isRequired = false, $toolbar = 'simple', $helpDsc = '');
		$sbform->addInput('hidden', '', array('name' => 'src', 'id' => 'scr', 'value' => "$src"));
		$sbform->addInput('hidden', '', array('name' => 'model', 'id' => 'model', 'value' => "$model"));
		// --------------------------------
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'edit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('hidden', '', array('name' => 'headpage', 'id' => 'headpage', 'value' => "$headpage"));
		$sbform->addInput('submit', '', array('id' => 'submitform', 'value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
	break;

	case "addcustom":
	case "editcustom":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = ($action == 'addcustom') ? "add_form" : "edit_form";
		$formType        = ($action == 'addcustom' || $_POST['form_submit'] == 'add_form') ? "addcustom" : "editcustom";
		$btn_add_edit    = ($action == 'addcustom') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'addcustom') ? "Ajouter une page (Custom)" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo; (Custom)";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id          = intval($_POST['id']);
			// --- Menu
			$menu_fr     = $sbsanitize->displayText($_POST['menu_fr'], 'UTF-8', 1, 0);
			$menu        = "[fr]".$menu_fr."[/fr]";			
			if ($getMultilang) {
				$menu_en = $sbsanitize->displayText($_POST['menu_en'], 'UTF-8', 1, 0);				
				$menu   .= "[en]".$menu_en."[/en]";
			}
			$url_custom  = $sbsanitize->displayText($_POST['url_custom'], 'UTF-8', 1, 0);
			$active      = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
	
			// ADD or EDIT
			if ($formType == 'addcustom') {
				// INSERT DATAS
				$query = "INSERT INTO $table (menu,title,url_custom,active,sort)
						  VALUES ('$menu','$menu','$url_custom','$active','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$menu_fr = $menu_en = $url_custom = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Page (Custom) ajoutée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'editcustom' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET menu = '$menu'
										   ,title = '$menu'
										   ,url_custom = '$url_custom'
										   ,active = '$active'
										   WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Page (Custom) modifiée avec succès';
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
			$menu_fr = $menu_en = $url_custom = $active = '';
		}
		// --------------------------------
		if ($formType == 'editcustom' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id              = intval($_GET['id']);
			$query[1]        = "SELECT * FROM $table WHERE id = $id";
			$requestQ        = $sbsql->query($query[1]);
			$assoc           = $sbsql->assoc($requestQ);
			$menu_fr         = $sbsanitize->displayLang(utf8_encode($assoc['menu']));
			// ---------------------------
			$menu_en         = $sbsanitize->displayLang(utf8_encode($assoc['menu']), 'en');
			// ---------------------------
			$url_custom      = utf8_encode($assoc['url_custom']);
			$active          = $assoc['active'];

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
		// Menu FR / EN
		// --------------------------------
		$menu_fr_title = ($getMultilang) ? 'Menu (FR)' : 'Menu' ;
		$sbform->addInput('text', "$menu_fr_title", array ('name' => 'menu_fr', 'value' => "$menu_fr", 'placeholder' => "Titre de votre menu"), true);
		if ($getMultilang)
			$sbform->addInput('text', 'Menu (EN)', array ('name' => 'menu_en', 'value' => "$menu_en", 'placeholder' => "Titre de votre menu (EN)"), true);
		// ----------------------------
		// --- Input TEXT with icon / icon2
		// ----------------------------
		$sbform->addInput('text', 'URL de votre page (Custom)', array ('name' => 'url_custom', 'value' => "$url_custom", 'placeholder' => "http://"), false);
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'editcustom') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
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
$sbsmarty->assign('page_title', 'Pages');
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