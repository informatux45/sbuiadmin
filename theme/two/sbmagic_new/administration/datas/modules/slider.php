<?php
/**
 * Admin Startbootstrap
 * SLIDER Module
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
$module_page = 'slider';
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
$table       = _AM_DB_PREFIX . "sb_slider";
$table_photo = _AM_DB_PREFIX . "sb_slider_photos";
$text        = "Slider";

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
		$sb_table_header = array('Nom', 'Type', 'Nbre images', 'Shortcode', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT t1.*, COUNT(*) AS cpt_img
					 FROM $table AS t1
					 LEFT JOIN $table_photo AS t2 ON (t1.id = t2.sid) GROUP BY t1.id";
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

	case "delphoto":
	case "photo":
		// Action DELETE photo
		if ($action == 'delphoto') {
			$get_id   = intval($_GET['id']);
			$query[5] = "DELETE FROM $table_photo WHERE id = '$get_id'";
			$request  = $sbsql->query($query[5]);
			
			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sid = $_GET['sid'];
		$sb_table_header = array('Tri', 'Photo', 'Name', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[4] = "SELECT * FROM $table_photo WHERE sid = '$sid'";
		$request2  = $sbsql->query($query[4]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('allphoto', $result2);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query[4];
			if (isset($action) && $action == 'del') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query[5];
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}
		// -------------
		$sbsmarty->assign('sid', $sid);
		
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
			$id                  = intval($_POST['id']);
			$title               = $sbsanitize->displayText($_POST['title'], 'UTF-8', 1, 0);
			$responsive          = $sbsanitize->displayText($_POST['responsive'], 'UTF-8', 1, 0);
			$jquery              = $sbsanitize->displayText($_POST['jquery'], 'UTF-8', 1, 0);
			$auto                = $sbsanitize->displayText($_POST['auto'], 'UTF-8', 1, 0);
			$pause               = $sbsanitize->displayText($_POST['pause'], 'UTF-8', 1, 0);
			$speed               = $sbsanitize->displayText($_POST['speed'], 'UTF-8', 1, 0);
			$randomstart         = $sbsanitize->displayText($_POST['randomstart'], 'UTF-8', 1, 0);
			$mode                = $sbsanitize->displayText($_POST['mode'], 'UTF-8', 1, 0);
			$preloadimages       = $sbsanitize->displayText($_POST['preloadimages'], 'UTF-8', 1, 0);
			$controls            = $sbsanitize->displayText($_POST['controls'], 'UTF-8', 1, 0);
			$autocontrols        = $sbsanitize->displayText($_POST['autocontrols'], 'UTF-8', 1, 0);
			$autohover           = $sbsanitize->displayText($_POST['autohover'], 'UTF-8', 1, 0);
			$captions            = $sbsanitize->displayText($_POST['captions'], 'UTF-8', 1, 0);
			$adaptiveheight      = $sbsanitize->displayText($_POST['adaptiveheight'], 'UTF-8', 1, 0);
			$adaptiveheightspeed = $sbsanitize->displayText($_POST['adaptiveheightspeed'], 'UTF-8', 1, 0);
			$slidemargin         = $sbsanitize->displayText($_POST['slidemargin'], 'UTF-8', 1, 0);
			$video               = $sbsanitize->displayText($_POST['video'], 'UTF-8', 1, 0);
			$usecss              = $sbsanitize->displayText($_POST['usecss'], 'UTF-8', 1, 0);
			$pager               = $sbsanitize->displayText($_POST['pager'], 'UTF-8', 1, 0);
			$pagertype           = $sbsanitize->displayText($_POST['pagertype'], 'UTF-8', 1, 0);
			$active              = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

	
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (title,jquery,responsive,auto,pause,speed,randomstart,mode,preloadimages,controls,autocontrols,autohover,captions,adaptiveheight,adaptiveheightspeed,slidemargin,video,usecss,pager,pagertype,active)
						  VALUES ('$title','$jquery','$responsive','$auto','$pause','$speed','$randomstart','$mode','$preloadimages','$controls','$autocontrols','$autohover','$captions','$adaptiveheight','$adaptiveheightspeed','$slidemargin','$video','$usecss','$pager','$pagertype','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$title = $jquery = $responsive = $auto = $pause = $speed = $randomstart = $mode = $preloadimages = $controls = $autocontrols = $autohover = $captions = $adaptiveheight = $adaptiveheightspeed = $slidemargin = $video = $usecss = $pager = $pagertype = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET title = '$title'
										   ,jquery = '$jquery'
										   ,responsive = '$responsive'
										   ,auto = '$auto'
										   ,pause = '$pause'
										   ,speed = '$speed'
										   ,randomstart = '$randomstart'
										   ,mode = '$mode'
										   ,preloadimages = '$preloadimages'
										   ,controls = '$controls'
										   ,autocontrols = '$autocontrols'
										   ,autohover = '$autohover'
										   ,captions = '$captions'
										   ,adaptiveheight = '$adaptiveheight'
										   ,adaptiveheightspeed = '$adaptiveheightspeed'
										   ,slidemargin = '$slidemargin'
										   ,video = '$video'
										   ,usecss = '$usecss'
										   ,pager = '$pager'
										   ,pagertype = '$pagertype'
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
			$title = $jquery = $responsive = $auto = $pause = $speed = $randomstart = $mode = $preloadimages = $controls = $autocontrols = $autohover = $captions = $adaptiveheight = $adaptiveheightspeed = $slidemargin = $video = $usecss = $pager = $pagertype = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id                  = intval($_GET['id']);
			$query[1]            = "SELECT * FROM $table WHERE id = $id";
			$requestQ            = $sbsql->query($query[1]);
			$assoc               = $sbsql->assoc($requestQ);
			$title               = $sbsanitize->displayText($assoc['title'], 'UTF-8', 1, 0);
			$jquery              = $assoc['jquery'];
			$responsive          = $assoc['responsive'];
			$auto                = $assoc['auto'];
			$pause               = $assoc['pause'];
			$speed               = $assoc['speed'];
			$randomstart         = $assoc['randomstart'];
			$mode                = $assoc['mode'];
			$preloadimages       = $assoc['preloadimages'];
			$controls            = $assoc['controls'];
			$autocontrols        = $assoc['autocontrols'];
			$autohover           = $assoc['autohover'];
			$captions            = $assoc['captions'];
			$adaptiveheight      = $assoc['adaptiveheight'];
			$adaptiveheightspeed = $assoc['adaptiveheightspeed'];
			$slidemargin         = $assoc['slidemargin'];
			$video               = $assoc['video'];
			$usecss              = $assoc['usecss'];
			$pager               = $assoc['pager'];
			$pagertype           = $assoc['pagertype'];
			$active              = $assoc['active'];			

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
		// --- Nom du slider
		// ----------------------------
		$sbform->addInput('text', 'Nom du slider', array ('name' => 'title', 'value' => "$title", 'placeholder' => "Nom de votre slider"), true);
		// ----------------------------
		// --- Jquery
		// ----------------------------
		$jquery = ($jquery == '') ? '1' : $jquery;
		$sbform->addRadioYN('Jquery', true, array('id'=>'jquery', 'name'=>'jquery', 'checked'=>"$jquery"), 'activé', 'désactivé', "Enable or disable loading of jquery library main script");
		// ----------------------------
		// --- Responsive (Enable or disable auto resize of the slider. Useful if you need to use fixed width sliders.)
		// ----------------------------
		$responsive = ($responsive == '') ? '1' : $responsive;
		$sbform->addRadioYN('Responsive', true, array('id'=>'responsive', 'name'=>'responsive', 'checked'=>"$responsive"), 'activé', 'désactivé', "Enable or disable auto resize of the slider. Useful if you need to use fixed width sliders.");
		// ----------------------------
		// --- Auto (Slides will start automatically transition)
		// ----------------------------
		$auto = ($auto == '') ? '1' : $auto;
		$sbform->addRadioYN('Auto', true, array('id'=>'auto', 'name'=>'auto', 'checked'=>"$auto"), 'activé', 'désactivé', "Slides will start automatically transition");
		// ----------------------------
		// --- Pause (The amount of time (in ms) between each auto transition)
		// ----------------------------
		$pause = ($pause == '') ? '4000' : $pause;
		$sbform->addInput('text', 'Pause', array ('name' => 'pause', 'value' => "$pause", 'placeholder' => "", 'icon' => 'pause', 'icon2' => 'ms', 'style' => 'width: 100px !important;'), true, false, "The amount of time (in ms) between each auto transition");
		// ----------------------------
		// --- Speed (Slide transition duration (in ms))
		// ----------------------------
		$speed = ($speed == '') ? '500' : $speed;
		$sbform->addInput('text', 'Speed', array ('name' => 'speed', 'value' => "$speed", 'placeholder' => "", 'icon' => 'road', 'icon2' => 'ms', 'style' => 'width: 100px !important;'), true, false, "Slide transition duration (in ms)");
		// ----------------------------
		// --- Pager (If true, a pager will be added)
		// ----------------------------
		$pager = ($pager == '') ? '1' : $pager;
		$sbform->addRadioYN('Pager', true, array('id'=>'pager', 'name'=>'pager', 'checked'=>"$pager"), 'activé', 'désactivé', "If true, a pager will be added");
		// -----------------------------------
		// --- Pager Type (If 'full', a pager link will be generated for each slide. If 'short', a x / y pager will be used (ex. 1 / 5))
		// -----------------------------------
		$pagertype_view = array('full', 'short');
		$sbform->openSelect("Pager Type", array("id"=>"pagertype", "name"=>"pagertype"), true);
		if ($pagertype == '') $sbform->addOption('Choisissez un type de pager', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($pagertype_view); $i++) {
			if ($pagertype_view[$i] == $pagertype)
				$sbform->addOption($pagertype_view[$i], array ("value"=>$pagertype_view[$i], "selected"=>""));
		else
				$sbform->addOption($pagertype_view[$i], array ("value"=>$pagertype_view[$i]));
		}
		// --- Close Select
		$sbform->closeSelect("If 'full', a pager link will be generated for each slide. If 'short', a x / y pager will be used (ex. 1 / 5)");
		// -----------------------------------
		// ----------------------------
		// --- Random Start (Start slider on a random slide)
		// ----------------------------
		$randomstart = ($randomstart == '') ? '0' : $randomstart;
		$sbform->addRadioYN('Random Start', true, array('id'=>'randomstart', 'name'=>'randomstart', 'checked'=>"$randomstart"), 'activé', 'désactivé', "Start slider on a random slide");
		// -----------------------------------
		// --- Mode (Type of transition between slides)
		// -----------------------------------
		$mode_view = array('horizontal', 'vertical', 'fade');
		$sbform->openSelect("Transition Mode", array("id"=>"mode", "name"=>"mode"), true);
		if ($mode == '') $sbform->addOption('Choisissez un type de transition', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($mode_view); $i++) {
			if ($mode_view[$i] == $mode)
				$sbform->addOption($mode_view[$i], array ("value"=>$mode_view[$i], "selected"=>""));
		else
				$sbform->addOption($mode_view[$i], array ("value"=>$mode_view[$i]));
		}
		// --- Close Select
		$sbform->closeSelect("Type of transition between slides");
		// -----------------------------------
		// --- Preload Images (If 'all', preloads all images before starting the slider. If 'visible', preloads only images in the initially visible slides before starting the slider (tip: use 'visible' if all slides are identical dimensions))
		// -----------------------------------
		$preloadimages_view = array('all', 'visible');
		$sbform->openSelect("Chargement des images", array("id"=>"preloadimages", "name"=>"preloadimages"), true);
		if ($preloadimages == '') $sbform->addOption('Choisissez un type de chargement', array ("value"=>"", "selected"=>""));
		for($i = 0; $i < count($preloadimages_view); $i++) {
			if ($preloadimages_view[$i] == $preloadimages)
				$sbform->addOption($preloadimages_view[$i], array ("value"=>$preloadimages_view[$i], "selected"=>""));
			else
				$sbform->addOption($preloadimages_view[$i], array ("value"=>$preloadimages_view[$i]));
		}
		// --- Close Select
		$sbform->closeSelect("If 'all', preloads all images before starting the slider.<br>If 'visible', preloads only images in the initially visible slides before starting the slider (tip: use 'visible' if all slides are identical dimensions)");
		// ----------------------------
		// --- Controls (If true, "Next" / "Prev" controls will be added)
		// ----------------------------
		$controls = ($controls == '') ? '1' : $controls;
		$sbform->addRadioYN('Controls', true, array('id'=>'controls', 'name'=>'controls', 'checked'=>"$controls"), 'activé', 'désactivé', "If true, 'Next'/ 'Prev' controls will be added");
		// ----------------------------
		// --- Auto Controls (If true, "Start" / "Stop" controls will be added)
		// ----------------------------
		$autocontrols = ($autocontrols == '') ? '0' : $autocontrols;
		$sbform->addRadioYN('Auto Controls', true, array('id'=>'autocontrols', 'name'=>'autocontrols', 'checked'=>"$autocontrols"), 'activé', 'désactivé', "If true, 'Start' / 'Stop' controls will be added");
		// ----------------------------
		// --- Auto Hover (Auto show will pause when mouse hovers over slider)
		// ----------------------------
		$autohover = ($autohover == '') ? '0' : $autohover;
		$sbform->addRadioYN('Auto Hover', true, array('id'=>'autohover', 'name'=>'autohover', 'checked'=>"$autohover"), 'activé', 'désactivé', "Auto show will pause when mouse hovers over slider");
		// ----------------------------
		// --- Captions (Include image captions. Captions are derived from the image's title attribute)
		// ----------------------------
		$captions = ($captions == '') ? '0' : $captions;
		$sbform->addRadioYN('Captions', true, array('id'=>'captions', 'name'=>'captions', 'checked'=>"$captions"), 'activé', 'désactivé', "Include image captions. Captions are derived from the image's title attribute");
		// ----------------------------
		// --- Adaptive Height (Dynamically adjust slider height based on each slide's height)
		// ----------------------------
		$adaptiveheight = ($adaptiveheight == '') ? '0' : $adaptiveheight;
		$sbform->addRadioYN('Adaptive Height', true, array('id'=>'adaptiveheight', 'name'=>'adaptiveheight', 'checked'=>"$adaptiveheight"), 'activé', 'désactivé', "Dynamically adjust slider height based on each slide's height");
		// ----------------------------
		// --- Adaptive Height Speed (Slide height transition duration (in ms). Note: only used if adaptiveHeight: true)
		// ----------------------------
		$adaptiveheightspeed = ($adaptiveheightspeed == '') ? '500' : $adaptiveheightspeed;
		$sbform->addInput('text', 'Adaptive Height Speed', array ('name' => 'adaptiveheightspeed', 'value' => "$adaptiveheightspeed", 'placeholder' => "", 'icon' => 'road', 'icon2' => 'ms', 'style' => 'width: 100px !important;'), true, false, "Slide height transition duration (in ms). Note: only used if adaptiveHeight: true");
		// ----------------------------
		// --- Slide Margin (Margin between each slide)
		// ----------------------------
		$slidemargin = ($slidemargin == '') ? '0' : $slidemargin;
		$sbform->addInput('text', 'Slide Margin', array ('name' => 'slidemargin', 'value' => "$slidemargin", 'placeholder' => "", 'icon' => 'arrows-h', 'icon2' => 'px', 'style' => 'width: 100px !important;'), true, false, "Margin between each slide");
		// ----------------------------
		// --- Video (If any slides contain video, set this to true.)
		// --- Dependance: plugins/jquery.fitvids.js
		// --- Documentation: See http://fitvidsjs.com/ for more info
		// ----------------------------
		$video = ($video == '') ? '0' : $video;
		$sbform->addRadioYN('Vidéo', true, array('id'=>'video', 'name'=>'video', 'checked'=>"$video"), 'activé', 'désactivé', "If any slides contain video, set this to true.");
		// ----------------------------
		// --- Use CSS (If true, CSS transitions will be used for horizontal and vertical slide animations (this uses native hardware acceleration). If false, jQuery animate() will be used.)
		// ----------------------------
		$usecss = ($usecss == '') ? '1' : $usecss;
		$sbform->addRadioYN('Use CSS', true, array('id'=>'usecss', 'name'=>'usecss', 'checked'=>"$usecss"), 'activé', 'désactivé', "If true, CSS transitions will be used for horizontal and vertical slide animations (this uses native hardware acceleration).<br>If false, jQuery animate() will be used.");
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

	case "photoadd":
	case "photoedit":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = ($action == 'photoadd') ? "add_form" : "edit_form";
		$formType        = ($action == 'photoadd' || $_POST['form_submit'] == 'add_form') ? "photoadd" : "photoedit";
		$btn_add_edit    = ($action == 'photoadd') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'photoadd') ? "Ajouter une photo / vidéo" : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id     = intval($_POST['id']);
			$sid    = intval($_POST['sid']);
			$title  = $sbsanitize->displayText($_POST['title'], 'UTF-8', 1, 0);
			$photo  = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$video  = $sbsanitize->displayText($_POST['video'], 'UTF-8', 1, 0);
			$type   = $sbsanitize->displayText($_POST['type'], 'UTF-8', 1, 0);
			$active = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			$media  = ($type == 'video') ? $video : $photo;

	
			// ADD or EDIT
			if ($formType == 'photoadd') {
				// INSERT DATAS
				$query = "INSERT INTO $table_photo (title,sid,photo,type,active,sort)
						  VALUES ('$title','$sid','$media','$type','$active','0')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$title = $photo = $video = $type = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = 'Media ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'photoedit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table_photo SET title = '$title'
												 ,photo = '$media'
												 ,type = '$type' 
												 ,active = '$active'
												 ,sid = '$sid'
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
			$title = $photo = $video = $type = $active = '';
		}
		// --------------------------------
		if ($formType == 'photoedit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id']);
			$query[1] = "SELECT * FROM $table_photo WHERE id = $id";
			$requestQ = $sbsql->query($query[1]);
			$assoc    = $sbsql->assoc($requestQ);
			$sid      = $assoc['sid'];
			$title    = $sbsanitize->displayLang(utf8_encode($assoc['title']), 'UTF-8', 1, 0);
			$type     = $sbsanitize->displayLang(utf8_encode($assoc['type']), 'UTF-8', 1, 0);
			if ($type == 'video') {
				$photo = '';
				$video = $sbsanitize->displayLang(utf8_encode($assoc['photo']), 'UTF-8', 1, 0);
			} else {
				$video = '';
				$photo = $sbsanitize->displayLang(utf8_encode($assoc['photo']), 'UTF-8', 1, 0);				
			}
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
		$active = ($active == '') ? '1' : $active;
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// -----------------------------------
		// --- Choix du slider
		// -----------------------------------
		$query_slider   = "SELECT id, title FROM $table";
		$request_slider = $sbsql->query($query_slider);
		$sliders        = $sbsql->toarray($request_slider);
		$sbform->openSelect("Choix du slider", array("id"=>"sid", "name"=>"sid"), true);
		$sbform->addOption('Choisissez un slider', array ("value"=>"", "selected"=>""));
		foreach($sliders as $row) {
			if ($row['id'] == $sid)
				$sbform->addOption($sbsanitize->displayLang(utf8_encode($row['title'])), array ("value"=>$row['id'], "selected"=>""));
			else
				$sbform->addOption($sbsanitize->displayLang(utf8_encode($row['title'])), array ("value"=>$row['id']));
		}
		// --- Close Select
		$sbform->closeSelect();
		// ----------------------------
		// --- Nom du slider
		// ----------------------------
		$sbform->addInput('text', 'Nom de la photo', array ('name' => 'title', 'value' => "$title", 'placeholder' => "Nom de votre photo"), true);
		// -----------------------------------
		// --- Type de media
		// -----------------------------------
		$slider_type = ['photo' => 'Photo'
					   ,'video' => 'Vidéo'
						];
		$sbform->openSelect("Type de média", array("id"=>"type", "name"=>"type"), true);
		$sbform->addOption('Choisissez un type de média', array ("value"=>"", "selected"=>""));
		foreach($slider_type as $key => $val) {
			if ($key == $type)
				$sbform->addOption($val, array ("value"=>$key, "selected"=>""));
			else
				$sbform->addOption($val, array ("value"=>$key));
		}
		// --- Close Select
		$sbform->closeSelect();
		// ----------------------------
		// --- Photo
		// ----------------------------
		$sbform->addInput('text', 'Photo', array ('id'=>'inputPhoto', 'name' => 'photo', 'value' => "$photo", 'placeholder' => "Photo", "medias"=>"", 'icon' => 'photo', 'style' => 'width: 100% !important'), false);
		// ----------------------------
		// -- Vidéo
		// ----------------------------		
		$sbform->addTextarea('Vidéo', $video, array('id' => 'video', 'name' => 'video', 'style' => 'height: 150px !important;'), false, "Code &lt;iframe&gt; permettant la visualisation de la vidéo sur votre site.<br>Ex: &lt;iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/_pVCS8HbrmI?autoplay=1&hl=fr&loop=1&controls=0&playlist=_pVCS8HbrmI\" frameborder=\"0\" allowfullscreen&gt;&lt;/iframe&gt;");
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'photoedit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		if ($formType == 'photoedit') $sbsmarty->assign('sid', $sid);
		// --------------------------------
	break;

	case "sort":
		// --------------------------------
		// Initialize Form SORT
		// --------------------------------
		$formName        = "sort_form";
		$formType        = "sort";
		$btn_add_edit    = "Valider";
		$legend_add_edit = "Trier les photos";
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
				$query_sort  = "UPDATE $table_photo SET sort = $tri WHERE id = " . $sb_toSort[$i];
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
				$sb_msg_valid = "Les photos ont été trié avec succès";
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (SORT)!';
			}
		}
		
		// --- Recuperation des donnees
		$sid           = intval($_GET['sid']);
		$query[3]      = "SELECT * FROM $table_photo WHERE sid = '$sid' ORDER BY sort ASC";
		$requestQ      = $sbsql->query($query[3]);
		$sort_array    = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$title  = $sbsanitize->displayText($sort['title'], 'UTF-8', 1, 0, 0, 0, 0, 1);
			$active = ($sort['active']) ? $title : "<span style='color: red;'>" . $title . "</span>";
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = "<img src='"._AM_MEDIAS_DIR . "/" . $sort['photo']."' style='max-width: 150px; max-height: 25px; margin-top: -3px' />&nbsp;&nbsp;&nbsp;" . $active;
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
		$sbform->addSortable($toSort, "Tri par glisser/déposer (drag'n drop) puis Valider<br>Les noms de <span style='color: red;'>photos en rouge</span> sont des photos en statut non visible");
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm ();
		// --------------------------------
		$sbsmarty->assign('sid', $sid);
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
$sbsmarty->assign('page_title', 'Sliders');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($title, 'UTF-8', 0, 1)));

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