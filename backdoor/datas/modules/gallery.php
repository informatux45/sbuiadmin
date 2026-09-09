<?php
/**
 * Admin Startbootstrap
 * GALLERY Module
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
$module_page = 'gallery';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
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
$table       = _AM_DB_PREFIX . "sb_gallery";
$table_photo = _AM_DB_PREFIX . "sb_gallery_photos";
$text        = "Galerie";

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
				$sb_msg_valid = $text . ' supprimée avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header = array('Nom', 'Nbre d\'images', 'Shortcode', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query     = "SELECT t1.*, COUNT(t2.id) AS cpt_img
					  FROM $table AS t1
					  LEFT JOIN $table_photo AS t2 ON (t1.id = t2.gid) GROUP BY t1.id";
		$request2  = $sbsql->query($query);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('allgallery', $result2);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query;
			if (isset($action) && $action == 'del') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query_2;
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}
		
	break;

	case "delphoto":
	case "photo":
		// Action DELETE photo
		if ($action == 'delphoto') {
			$get_id  = intval($_GET['id']);
			$query_5 = "DELETE FROM $table_photo WHERE id = '$get_id'";
			$request = $sbsql->query($query_5);
			
			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		// --- intval() obligatoire : $gid part directement dans le WHERE ci-dessous
		$gid = isset($_GET['gid']) ? intval($_GET['gid']) : 0;
		$sb_table_header = array('Tri', 'Photo', 'Name', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query_4  = "SELECT * FROM $table_photo WHERE gid = '$gid'";
		$request2 = $sbsql->query($query_4);
		$result2  = $sbsql->toarray($request2);
		
		$sbsmarty->assign('allphoto', $result2);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query_4;
			if (isset($action) && $action == 'del') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query_5;
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}
		// -------------
		$sbsmarty->assign('gid', $gid);
		
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
			$id         = intval($_POST['id']);
			$title      = $sbsanitize->displayText($_POST['title'], 'UTF-8', 1, 0);
			$css        = $sbsanitize->displayText($_POST['css_hidden'], 'UTF-8', 1, 0);
			$javascript = $sbsanitize->displayText($_POST['javascript_hidden'], 'UTF-8', 1, 0);
			$template   = $sbsanitize->displayText($_POST['template_hidden'], 'UTF-8', 1, 0);
			$active     = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

			// --------------------------------------------------------
			// GARDE-FOU : css / javascript / template sont recopiés dans
			// leurs champs cachés par le JavaScript des éditeurs ACE au
			// moment du submit. Si ce JS n'a pas tourné, les trois champs
			// arrivent vides et l'UPDATE effacerait le gabarit de la
			// galerie sans avertissement.
			// Le même JS pose code_ready=1 : sa présence distingue des
			// champs vidés VOULUS d'un JS qui n'a pas tourné.
			// --------------------------------------------------------
			$editor_ran   = (isset($_POST['code_ready']) && $_POST['code_ready'] === '1');
			$editors_void = ($sbsanitize->sTrim($css) === '' && $sbsanitize->sTrim($javascript) === '' && $sbsanitize->sTrim($template) === '');

			if ($formType == 'edit' && $id > 0 && !$editor_ran && $editors_void) {

				// --- Relecture pour réafficher l'existant plutôt que du vide
				$query          = "SELECT title, css, javascript, template, active FROM $table WHERE id = '$id'";
				$requestCurrent = $sbsql->query($query);
				$assocCurrent   = $sbsql->assoc($requestCurrent);
				$css            = isset($assocCurrent['css'])        ? $assocCurrent['css']        : '';
				$javascript     = isset($assocCurrent['javascript']) ? $assocCurrent['javascript'] : '';
				$template       = isset($assocCurrent['template'])   ? $assocCurrent['template']   : '';
				// --- Message ERROR (aucune écriture)
				$sb_msg_error   = "Les éditeurs de code n'ont pas répondu : la galerie n'a pas été modifiée. Rechargez la page et réessayez.";

			// ADD or EDIT
			} elseif ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (title,css,javascript,template,active)
						  VALUES ('$title','$css','$javascript','$template','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$title = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET title = '$title'
				                           ,css = '$css'
										   ,javascript = '$javascript'
										   ,template = '$template'
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
			$title = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id         = intval($_GET['id']);
			$query_1    = "SELECT * FROM $table WHERE id = $id";
			$requestQ   = $sbsql->query($query_1);
			$assoc      = $sbsql->assoc($requestQ);
			$title      = sb_utf8_encode($assoc['title']);
			$css        = sb_utf8_encode($assoc['css']);
			$javascript = sb_utf8_encode($assoc['javascript']);
			$template   = sb_utf8_encode($assoc['template']);
			$active     = $assoc['active'];			

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
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Nom du slider
		// ----------------------------
		$sbform->addInput('text', 'Nom de la galerie', array ('name' => 'title', 'value' => "$title", 'placeholder' => "Nom de votre galerie"), true);
		// --------------------------------
		// ACE Editors (CSS, JS, TEMPLATE)
		// --------------------------------
		$sbform->addAnything('<div class="form-group"><label for="" class="form_required">Code CSS</label><div id="css" style="height: 300px; width: 100%;">' . $css . '</div></div><p></p><div class="form-group"><label for="" class="form_required">Code JAVASCRIPT</label><div id="javascript" style="height: 300px; width: 100%;">' . $javascript . '</div></div><p></p><div class="form-group"><label for="" class="form_required">TEMPLATE HTML (Smarty autorisé)</label><div id="template" style="height: 300px; width: 100%;">' . $template . '</div></div><p></p>');
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'edit') $sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('hidden', '', array('name' => 'css_hidden', 'value' => ""));
		$sbform->addInput('hidden', '', array('name' => 'javascript_hidden', 'value' => ""));
		$sbform->addInput('hidden', '', array('name' => 'template_hidden', 'value' => ""));
		// --- Marqueur posé par le JS des éditeurs ACE (voir gallery.tpl) :
		// --- distingue trois champs vidés volontairement d'un JS qui n'a pas
		// --- tourné. Voir le garde-fou de la branche de soumission ci-dessus.
		$sbform->addInput('hidden', '', array('name' => 'code_ready', 'value' => ""));
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
			$gid    = intval($_POST['gid']);
			$title  = $sbsanitize->displayText($_POST['title'], 'UTF-8', 1, 0);
			$photo  = $sbsanitize->displayText($_POST['photo'], 'UTF-8', 1, 0);
			$video  = $sbsanitize->displayText($_POST['video'], 'UTF-8', 1, 0);
			$type   = $sbsanitize->displayText($_POST['type'], 'UTF-8', 1, 0);
			$active = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			$media  = ($type == 'video') ? $video : $photo;

	
			// ADD or EDIT
			if ($formType == 'photoadd') {
				// INSERT DATAS
				$query = "INSERT INTO $table_photo (title,gid,photo,type,active,sort)
						  VALUES ('$title','$gid','$media','$type','$active','0')";
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
												 ,gid = '$gid'
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
			$query_1  = "SELECT * FROM $table_photo WHERE id = $id";
			$requestQ = $sbsql->query($query_1);
			$assoc    = $sbsql->assoc($requestQ);
			$gid      = $assoc['gid'];
			$title    = $sbsanitize->displayLang(sb_utf8_encode($assoc['title']), 'UTF-8', 1, 0);
			$type     = $sbsanitize->displayLang(sb_utf8_encode($assoc['type']), 'UTF-8', 1, 0);
			if ($type == 'video') {
				$photo = '';
				$video = $sbsanitize->displayLang(sb_utf8_encode($assoc['photo']), 'UTF-8', 1, 0);
			} else {
				$video = '';
				$photo = $sbsanitize->displayLang(sb_utf8_encode($assoc['photo']), 'UTF-8', 1, 0);				
			}
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
		$active = ($active == '') ? '1' : $active;
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// -----------------------------------
		// --- Choix du slider
		// -----------------------------------
		$query_gallery   = "SELECT id, title FROM $table";
		$request_gallery = $sbsql->query($query_gallery);
		$galleries       = $sbsql->toarray($request_gallery);
		$sbform->openSelect("Choix de la galerie", array("id"=>"gid", "name"=>"gid"), true);
		$sbform->addOption('Choisissez une galerie', array ("value"=>"", "selected"=>""));
		foreach($galleries as $row) {
			if ($row['id'] == $gid)
				$sbform->addOption($sbsanitize->displayLang(sb_utf8_encode($row['title'])), array ("value"=>$row['id'], "selected"=>""));
			else
				$sbform->addOption($sbsanitize->displayLang(sb_utf8_encode($row['title'])), array ("value"=>$row['id']));
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
		$gallery_type = ['photo' => 'Photo'
					   ,'video' => 'Vidéo'
						];
		$sbform->openSelect("Type de média", array("id"=>"type", "name"=>"type"), true);
		$sbform->addOption('Choisissez un type de média', array ("value"=>"", "selected"=>""));
		foreach($gallery_type as $key => $val) {
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
		if ($formType == 'photoedit') $sbsmarty->assign('gid', $gid);
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
			// --- (array) : count() sur une valeur non-tableau (drag=foo) leve une
			// --- TypeError fatale sous PHP 8.
			$sb_toSort = (array)$_POST['drag'];
			
			// reorganizes the order of elements
			$sql_error = 0;
			for ($i = 0; $i < count($sb_toSort); $i++) {
				$tri = $i + 1;
				// --- intval() : $_POST['drag'] est fourni par le navigateur, il part
				// --- sinon brut dans le WHERE (les actions de tri ne passent pas par
				// --- le jeton CSRF de openForm(), voir index.php).
				$query_sort  = "UPDATE $table_photo SET sort = $tri WHERE id = " . intval($sb_toSort[$i]);
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
		$gid        = intval($_GET['gid']);
		$query_3    = "SELECT * FROM $table_photo WHERE gid = '$gid' ORDER BY sort ASC";
		$requestQ   = $sbsql->query($query_3);
		$sort_array = $sbsql->toarray($requestQ);
		foreach($sort_array as $sort) {
			$title  = $sbsanitize->displayText($sort['title'], 'UTF-8', 1, 0, 0, 0, 0, 1);
			$active = ($sort['active']) ? $title : "<span style='color: red;'>" . $title . "</span>";
			$sort_id          = $sort['id'];
			$toSort[$sort_id] = "<img src='"._AM_MEDIAS_DIR . "/" . $sort['photo']."' style='max-width: 150px; max-height: 25px; margin-top: -3px' />&nbsp;&nbsp;&nbsp;" . $active;
		}

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_3 . "\n" . 'Form Type = '.$formType);
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&gid=" . $gid;
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
		$sbsmarty->assign('gid', $gid);
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
$sbsmarty->assign('page_title', 'Galeries');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($title, 'UTF-8', 0, 1)));

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