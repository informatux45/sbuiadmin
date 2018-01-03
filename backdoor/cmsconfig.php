<?php
/**
 * Admin Startbootstrap
 * Manage CMS CONFIG
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
$module_page = 'cmsconfig';
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
$table = _AM_DB_PREFIX . "sb_config";

// -----------------------
// Get Multilang Option
// -----------------------
$getMultilang = (sbGetConfig('multilang')) ? sbGetConfig('multilang') : false;

// --------------------------------
// Initialize Form
// --------------------------------
$formName        = "edit_form";
$formType        = "edit";
$btn_add_edit    = "Modifier";
$legend_add_edit = "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";

$action = ($_GET['op'] == '') ? 'headerfooter' : $_GET['op'];
switch($action) {

	case "css":
	case "javascript":
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id      = intval($_POST['id']);
			$content = $sbsanitize->displayText($_POST['code_hidden'], 'UTF-8', 1, 0);
			
			// --- EDIT
			if ($id > 0) {

				// UPDATE DATAS
				$query = "UPDATE $table SET content = '$content'
										   WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Code modifié avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit'] && !$_POST['id']) {
			// --- Recuperation des donnees
			$query[1]  = "SELECT * FROM $table WHERE config = '$action'";
			$requestQ  = $sbsql->query($query[1]);
			$assoc     = $sbsql->assoc($requestQ);
			$id        = intval($assoc['id']);
			$content   = utf8_encode($assoc['content']);

			$sbsmarty->assign('assoc', $query[1]);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query[1]	 . "\n" . 'Form Type = '.$formType);						
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&op=" . $action . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --------------------------------
		// Editor ACE (JS / CSS)
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
	
	default:
	case "headerfooter":
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			// --- Content Header
			$content_header_fr = $sbsanitize->displayText($_POST['header_fr'], 'UTF-8', 1, 0);
			$content_header    = "[fr]".$content_header_fr."[/fr]";
			if ($getMultilang) {
				$content_header_en = $sbsanitize->displayText($_POST['header_en'], 'UTF-8', 1, 0);				
				$content_header   .= "[en]".$content_header_en."[/en]";
			}
			// --- Content Footer
			$content_footer_fr = $sbsanitize->displayText($_POST['footer_fr'], 'UTF-8', 1, 0);
			$content_footer    = "[fr]".$content_footer_fr."[/fr]";
			if ($getMultilang) {
				$content_footer_en = $sbsanitize->displayText($_POST['footer_en'], 'UTF-8', 1, 0);				
				$content_footer   .= "[en]".$content_footer_en."[/en]";
			}
			
			// --- EDIT
			// UPDATE DATAS
			$query_header = "UPDATE $table SET content = '$content_header' WHERE config = 'header'";
			$query_footer = "UPDATE $table SET content = '$content_footer' WHERE config = 'footer'";
			
			$result_edit_header = $sbsql->query($query_header);
			$result_edit_footer = $sbsql->query($query_footer);
			if ($result_edit_header && $result_edit_footer) {
				// --- Message SUCCES
				$sb_msg_valid = 'Code modifié avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_header . "\n" . $query_footer . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		// --- Recuperation des donnees
		// --------------------------------
		$queryH   = "SELECT content FROM $table WHERE config = 'header'";
		$queryF   = "SELECT content FROM $table WHERE config = 'footer'";
		$requestH = $sbsql->query($queryH);
		$requestF = $sbsql->query($queryF);
		$assocH   = $sbsql->assoc($requestH);
		$assocF   = $sbsql->assoc($requestF);
		$contentH = utf8_encode($assocH['content']);

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $queryH . "\n" . $queryF . "\n" . 'Form Type = '.$formType);						
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&op=" . $action;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --------------------------------
		// Editeur HEADER code
		// --------------------------------
		$header_fr_title = ($getMultilang) ? 'Header CODE (FR)' : 'Header CODE' ;
		$sbform->addTextareaHtml("$header_fr_title", $sbsanitize->displayLang(utf8_encode($assocH['content'])), array('id' => 'header_fr', 'name' => 'header_fr', 'style' => 'height: 250px !important;'), false);
		if ($getMultilang) {
			$sbform->addTextareaHtml('Header CODE (EN)', $sbsanitize->displayLang(utf8_encode($assocH['content']), 'en'), array('id' => 'header_en', 'name' => 'header_en', 'style' => 'height: 250px !important;'), false);			
		}
		// --------------------------------
		// Editeur FOOTER code
		// --------------------------------
		$footer_fr_title = ($getMultilang) ? 'Footer CODE (FR)' : 'Footer CODE' ;
		$sbform->addTextareaHtml("$footer_fr_title", $sbsanitize->displayLang(utf8_encode($assocF['content'])), array('id' => 'footer_fr', 'name' => 'footer_fr', 'style' => 'height: 250px !important;'), false);
		if ($getMultilang) {
			$sbform->addTextareaHtml('Footer CODE (EN)', $sbsanitize->displayLang(utf8_encode($assocF['content']), 'en'), array('id' => 'footer_en', 'name' => 'footer_en', 'style' => 'height: 250px !important;'), false);			
		}
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
	
	case "comingsoon":
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$coming_soon_url         = $sbsanitize->displayText($_POST['coming_soon_url'], 'UTF-8', 1, 0);
			$coming_soon_title       = $sbsanitize->displayText($_POST['coming_soon_title'], 'UTF-8', 1, 0);
			$coming_soon_title2      = $sbsanitize->displayText($_POST['coming_soon_title2'], 'UTF-8', 1, 0);
			$coming_soon_text        = $sbsanitize->displayText($_POST['coming_soon_text'], 'UTF-8', 1, 0);
			$coming_soon_tel         = $sbsanitize->displayText($_POST['coming_soon_tel'], 'UTF-8', 1, 0);
			$coming_soon_address     = $sbsanitize->displayText($_POST['coming_soon_address'], 'UTF-8', 1, 0);
			$coming_soon_email       = $sbsanitize->displayText($_POST['coming_soon_email'], 'UTF-8', 1, 0);
			$coming_soon_facebook    = $sbsanitize->displayText($_POST['coming_soon_facebook'], 'UTF-8', 1, 0);
			$coming_soon_twitter     = $sbsanitize->displayText($_POST['coming_soon_twitter'], 'UTF-8', 1, 0);	 
			$coming_soon_youtube     = $sbsanitize->displayText($_POST['coming_soon_youtube'], 'UTF-8', 1, 0);
			$coming_soon_type        = $sbsanitize->displayText($_POST['coming_soon_type'], 'UTF-8', 1, 0);
			$coming_soon_image       = $sbsanitize->displayText($_POST['coming_soon_image'], 'UTF-8', 1, 0);
			$coming_soon_video       = $sbsanitize->displayText($_POST['coming_soon_video'], 'UTF-8', 1, 0);
			$coming_soon_dark        = ($_POST['coming_soon_dark'] === "on") ? '1' : '0';
			$coming_soon_date        = $sbsanitize->displayText($_POST['coming_soon_date'], 'UTF-8', 1, 0);
			$coming_soon_google_plus = $sbsanitize->displayText($_POST['coming_soon_google_plus'], 'UTF-8', 1, 0);
			
			// --- EDIT
			// UPDATE DATAS
			$query_url          = "UPDATE $table SET content = '$coming_soon_url' WHERE config = 'coming-soon-url'";
			$query_title        = "UPDATE $table SET content = '$coming_soon_title' WHERE config = 'coming-soon-title'";
			$query_title2       = "UPDATE $table SET content = '$coming_soon_title2' WHERE config = 'coming-soon-title2'";
			$query_text         = "UPDATE $table SET content = '$coming_soon_text' WHERE config = 'coming-soon-text'";
			$query_tel          = "UPDATE $table SET content = '$coming_soon_tel' WHERE config = 'coming-soon-tel'";
			$query_address      = "UPDATE $table SET content = '$coming_soon_address' WHERE config = 'coming-soon-address'";
			$query_email        = "UPDATE $table SET content = '$coming_soon_email' WHERE config = 'coming-soon-email'";
			$query_facebook     = "UPDATE $table SET content = '$coming_soon_facebook' WHERE config = 'coming-soon-facebook'";
			$query_twitter      = "UPDATE $table SET content = '$coming_soon_twitter' WHERE config = 'coming-soon-twitter'";
			$query_youtube      = "UPDATE $table SET content = '$coming_soon_youtube' WHERE config = 'coming-soon-youtube'";
			$query_type         = "UPDATE $table SET content = '$coming_soon_type' WHERE config = 'coming-soon-type'";
			$query_image        = "UPDATE $table SET content = '$coming_soon_image' WHERE config = 'coming-soon-image'";
			$query_video        = "UPDATE $table SET content = '$coming_soon_video' WHERE config = 'coming-soon-video'";
			$query_dark         = "UPDATE $table SET content = '$coming_soon_dark' WHERE config = 'coming-soon-dark'";
			$query_date         = "UPDATE $table SET content = '$coming_soon_date' WHERE config = 'coming-soon-date'";
			$query_google_plus  = "UPDATE $table SET content = '$coming_soon_google_plus' WHERE config = 'coming-soon-google-plus'";
			
			$result_url         = $sbsql->query($query_url);			
			$result_title       = $sbsql->query($query_title);
			$result_title2      = $sbsql->query($query_title2);
			$result_text        = $sbsql->query($query_text);
			$result_tel         = $sbsql->query($query_tel);
			$result_address     = $sbsql->query($query_address);
			$result_email       = $sbsql->query($query_email);
			$result_facebook    = $sbsql->query($query_facebook);
			$result_twitter     = $sbsql->query($query_twitter);
			$result_youtube     = $sbsql->query($query_youtube);
			$result_type        = $sbsql->query($query_type);
			$result_image       = $sbsql->query($query_image);
			$result_video       = $sbsql->query($query_video);
			$result_dark        = $sbsql->query($query_dark);
			$result_date        = $sbsql->query($query_date);
			$result_google_plus = $sbsql->query($query_google_plus);
			
			if ($result_url && $result_title && $result_title && $result_text && $result_tel && $result_address && $result_email && $result_facebook  && $result_twitter && $result_youtube && $result_type && $result_image && $result_video && $result_dark && $result_date && $result_google_plus) {
				// --- Message SUCCES
				$sb_msg_valid = 'Coming soon modifié avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}
			
		}
		
		// --------------------------------
		// --- Recuperation des donnees
		// --------------------------------
		$query = "SELECT config, content FROM $table WHERE config = 'coming-soon-url'
																			 OR config = 'coming-soon-title'
																			 OR config = 'coming-soon-title2'
																			 OR config = 'coming-soon-text'
																			 OR config = 'coming-soon-tel'
																			 OR config = 'coming-soon-address'
																			 OR config = 'coming-soon-email'
																			 OR config = 'coming-soon-facebook'
																			 OR config = 'coming-soon-twitter'
																			 OR config = 'coming-soon-youtube'
																			 OR config = 'coming-soon-type'
																			 OR config = 'coming-soon-image'
																			 OR config = 'coming-soon-video'
																			 OR config = 'coming-soon-dark'
																			 OR config = 'coming-soon-date'
																			 OR config = 'coming-soon-google-plus'
																			 ";
		$request = $sbsql->query($query);
		$assoc   = $sbsql->toarray($request);
		// --------------------------------
		foreach($assoc as $row) {
			$cs[$row['config']] = utf8_encode($row['content']);
		}
		
		// --------------------------------

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Form Type = '.$formType);						
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&op=" . $action;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --------------------------------
		// Selection du type de Coming Soon
		// --------------------------------
		$sb_type = ['image','video'];
		$sbform->openSelect("Choisissez un type de coming soon", array("id"=>"coming_soon_type", "name"=>"coming_soon_type"), true);
		for($i = 0; $i < count($sb_type); $i++) {
			if ($sb_type[$i] == $cs['coming-soon-type'])
				$sbform->addOption($sb_type[$i], array ("value"=>$sb_type[$i], "selected"=>""));
		else
				$sbform->addOption($sb_type[$i], array ("value"=>$sb_type[$i]));
		}
		// --- Close Select
		$sbform->closeSelect();
		// --------------------------------
		// Image / video
		// --------------------------------
		$sbform->addInput('text', 'Photo', array ('id'=>'inputPhoto', 'name' => 'coming_soon_image', 'value' => $cs['coming-soon-image'], 'placeholder' => "Photo (background)", "medias"=>"", 'icon' => 'photo'), false, false, 'Image commune à tous les types de Coming soon. Si vous ne choisissez pas de photo, il y en a une par défaut pour chaque type.');
		$sbform->addInput('text', 'ID Youtube', array ('name' => 'coming_soon_video', 'value' => $cs['coming-soon-video'], 'placeholder' => "ID Youtube"), false, false, "N'indiquez que la partie en <span style='color: red;'>rouge</span>.<br>Si vous n'indiquez aucun ID vidéo, il y en une par défaut.<br>Ex: https://www.youtube.com/watch?v=<span style='color: red; font-weight: bold;'>PF0L3gvSVcg</span>");
		// ----------------------------
		// Date du lancement
		// ----------------------------
		$sbform->addDate('Date du lancement du site (supposée)', array('id'=>'coming_soon_date', 'name'=>'coming_soon_date', 'value'=>$cs['coming-soon-date']), true);
		// --------------------------------
		// Titre
		// --------------------------------
		$sbform->addInput('text', 'Titre', array ('name' => 'coming_soon_title', 'value' => $cs['coming-soon-title'], 'placeholder' => "Titre"), true);
		// --------------------------------
		// Titre 2
		// --------------------------------	
		$sbform->addInput('text', 'Titre 2', array ('name' => 'coming_soon_title2', 'value' => $cs['coming-soon-title2'], 'placeholder' => "2e Titre"), false);
		// --------------------------------
		// Url
		// --------------------------------	
		$sbform->addInput('text', 'Url', array ('name' => 'coming_soon_url', 'value' => $cs['coming-soon-url'], 'placeholder' => "Url d'accès"), true, false, "Ex: http://votresite.com/?d=<span style='color: red; font-weight: bold;'>DeVeLop</span><br><b>N'indiquez que la partie en <span style='color: red;'>rouge</span> ;-)</b>");
		// --------------------------------
		// Telephone
		// --------------------------------	
		$sbform->addInput('text', 'Téléphone', array ('name' => 'coming_soon_tel', 'value' => $cs['coming-soon-tel'], 'placeholder' => "Téléphone", 'icon' => 'phone'), false);
		// --------------------------------
		// Adresse
		// --------------------------------	
		$sbform->addInput('text', 'Adresse', array ('name' => 'coming_soon_address', 'value' => $cs['coming-soon-address'], 'placeholder' => "Adresse", 'icon' => 'home'), false);
		// --------------------------------
		// Email
		// --------------------------------	
		$sbform->addInput('text', 'Email', array ('name' => 'coming_soon_email', 'value' => $cs['coming-soon-email'], 'placeholder' => "Email", 'icon' => 'envelope'), false);
		// --------------------------------
		// Facebook
		// --------------------------------	
		$sbform->addInput('text', 'Facebook', array ('name' => 'coming_soon_facebook', 'value' => $cs['coming-soon-facebook'], 'placeholder' => "Lien Facebook", 'icon' => 'facebook'), false);
		// --------------------------------
		// Twitter
		// --------------------------------	
		$sbform->addInput('text', 'Twitter', array ('name' => 'coming_soon_twitter', 'value' => $cs['coming-soon-twitter'], 'placeholder' => "Lien Twitter", 'icon' => 'twitter'), false);
		// --------------------------------
		// Youtube
		// --------------------------------	
		$sbform->addInput('text', 'Youtube', array ('name' => 'coming_soon_youtube', 'value' => $cs['coming-soon-youtube'], 'placeholder' => "Lien Youtube", 'icon' => 'youtube-play'), false);
		// --------------------------------
		// Google +
		// --------------------------------	
		$sbform->addInput('text', 'Google +', array ('name' => 'coming_soon_google_plus', 'value' => $cs['coming-soon-google-plus'], 'placeholder' => "Lien Google +", 'icon' => 'google-plus'), false);
		// --------------------------------
		// --------------------------------
		// A propos (Qui sommes nous)
		// --------------------------------	
		$sbform->addTextareaHTML('A propos (Qui sommes nous)', $cs['coming-soon-text'], array('id' => 'coming_soon_text', 'name' => 'coming_soon_text'), false, 'basic');
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

	case "multilang":
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$active = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			
			// --- EDIT
			// UPDATE DATAS
			$query_active = "UPDATE $table SET content = '$active' WHERE config = 'multilang'";
			$result_active   = $sbsql->query($query_active);
			if ($result_active) {
				// --- Message SUCCES
				$sb_msg_valid = 'Multilangue modifié avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_active . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		// --- Recuperation des donnees
		// --------------------------------
		$query = "SELECT content FROM $table WHERE config = 'multilang'";
		$request = $sbsql->query($query);
		$assoc   = $sbsql->assoc($request);
		// --------------------------------
		$active = intval($assoc['content']);
		// --------------------------------

		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Form Type = '.$formType);						
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&op=" . $action;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé', "Si désactivé, la langue 'FR' uniquement sera disponible");
		// --------------------------------
		// Langue(s)
		// --------------------------------
		$sbform->addInput('text', 'Langue(s)', array ('name' => 'lang', 'value' => "fr,en", 'placeholder' => "Langue(s)", 'disabled' => 'disabled'), false, false, "Ajouter dans les langues séparées par des virgules sans espaces");
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

	case "plugins":
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$plugins  = ($_POST['jquery'] === "on") ? "jquery|" : "";
			$plugins .= ($_POST['lightbox'] === "on") ? "lightbox|" : "";
			$plugins .= ($_POST['fancybox'] === "on") ? "fancybox|" : "";
			$plugins .= ($_POST['checkboxcss'] === "on") ? "checkboxcss|" : "";
			$plugins .= ($_POST['appear'] === "on") ? "appear|" : "";
			$plugins = rtrim($plugins, "|");
			
			// --- EDIT
			// UPDATE DATAS
			$query  = "UPDATE $table SET content = '$plugins' WHERE config = 'plugins'";
			$result = $sbsql->query($query);
			if ($result) {
				// --- Message SUCCES
				$sb_msg_valid = 'Plugins modifiés avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		// --- Recuperation des donnees
		// --------------------------------
		$query = "SELECT content FROM $table WHERE config = 'plugins'";
		$request = $sbsql->query($query);
		$assoc   = $sbsql->assoc($request);
		// --------------------------------
		$plugins = explode("|", $assoc['content']);
		// --------------------------------

		// --- Debug SQL
		if (_AM_SITE_DEBUG && !$_POST['form_submit']) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Form Type = '.$formType);						
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&op=" . $action;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		// --------------------------------
		// --- Plugin JQUERY Latest
		// --------------------------------
		$tab_jquery = array();
		$tab_jquery[0]['text']    = 'Activé<br><br>'.sbHowToPlugins('Comment utiliser le plugin JQUERY', 'jquery/howto.html', 'zero', 'JQUERY');
		$tab_jquery[0]['name']    = 'jquery';
		$tab_jquery[0]['checked'] = (in_array("jquery", $plugins)) ? '1' : '0';
		$config_blocks = ($tab_jquery[0]['checked'] == '1') ? 'config_blocks_active' : 'config_blocks';
		$sbform->addAnything("<div class='$config_blocks'");
		$sbform->addCheckbox('JQUERY (Latest)', $tab_jquery, '', false, '<br />');
		$sbform->addAnything("</div>");
		// --------------------------------
		// --- Plugin LIGHTBOX
		// --------------------------------
		$tab_lightbox = array();
		$tab_lightbox[0]['text']    = 'Activé<br><br>'.sbHowToPlugins('Comment utiliser le plugin LIGHTBOX', 'lightbox/howto.html', 'One', 'LIGHTBOX');
		$tab_lightbox[0]['name']    = 'lightbox';
		$tab_lightbox[0]['checked'] = (in_array("lightbox", $plugins)) ? '1' : '0';
		$config_blocks = ($tab_lightbox[0]['checked'] == '1') ? 'config_blocks_active' : 'config_blocks';
		$sbform->addAnything("<div class='$config_blocks'");
		$sbform->addCheckbox('LIGHTBOX', $tab_lightbox, '', false, '<br />');
		$sbform->addAnything('</div>');
		// --------------------------------
		// --- Plugin FANCYBOX
		// --------------------------------
		$tab_fancybox = array();
		$tab_fancybox[0]['text']    = 'Activé<br><br>'.sbHowToPlugins('Comment utiliser le plugin FANCYBOX', 'fancybox/howto.html', 'Two', 'FANCYBOX');
		$tab_fancybox[0]['name']    = 'fancybox';
		$tab_fancybox[0]['checked'] = (in_array("fancybox", $plugins)) ? '1' : '0';
		$config_blocks = ($tab_fancybox[0]['checked']) ? 'config_blocks_active' : 'config_blocks';
		$sbform->addAnything("<div class='$config_blocks'");
		$sbform->addCheckbox('FANCYBOX', $tab_fancybox, '', false, '<br />');
		$sbform->addAnything('</div>');
		// --------------------------------
		// --- Plugin CHECKBOXCSS
		// --------------------------------
		$tab_checkboxcss = array();
		$tab_checkboxcss[0]['text']    = 'Activé<br><br>'.sbHowToPlugins('Comment utiliser le plugin CHECKBOXCSS', 'checkboxcss/howto.html', 'Three', 'CHECKBOXCSS');
		$tab_checkboxcss[0]['name']    = 'checkboxcss';
		$tab_checkboxcss[0]['checked'] = (in_array("checkboxcss", $plugins)) ? '1' : '0';
		$config_blocks = ($tab_checkboxcss[0]['checked']) ? 'config_blocks_active' : 'config_blocks';
		$sbform->addAnything("<div class='$config_blocks'");
		$sbform->addCheckbox('CHECKBOXCSS', $tab_checkboxcss, '', false, '<br />');
		$sbform->addAnything('</div>');
		// --------------------------------
		// --- Plugin APPEAR / DISAPPEAR
		// --------------------------------
		$tab_appear = array();
		$tab_appear[0]['text']    = 'Activé<br><br>'.sbHowToPlugins('Comment utiliser le plugin APPEAR / DISAPPEAR', 'appear/howto.html', 'Four', 'APPEAR / DISAPPEAR');
		$tab_appear[0]['name']    = 'appear';
		$tab_appear[0]['checked'] = (in_array("appear", $plugins)) ? '1' : '0';
		$config_blocks = ($tab_appear[0]['checked']) ? 'config_blocks_active' : 'config_blocks';
		$sbform->addAnything("<div class='$config_blocks'");
		$sbform->addCheckbox('APPEAR / DISAPPEAR', $tab_appear, '', false, '<br />');
		$sbform->addAnything('</div><div class="config_blocks"></div>');
		// --------------------------------
		// --- Hiddens / Buttons
		// --------------------------------
		$sbform->addAnything('<div style="clear: both;">&nbsp;</div>');
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
	
	case "fonts":
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$fonts = $sbsanitize->displayText($_POST['fonts'], 'UTF-8', 1, 0);
			
			// --- EDIT
			// UPDATE DATAS
			$query  = "UPDATE $table SET content = '$fonts' WHERE config = 'fonts'";
			$result = $sbsql->query($query);
			if ($result) {
				// --- Message SUCCES
				$sb_msg_valid = 'Fonts modifiées avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		// --- Recuperation des donnees
		// --------------------------------
		$query   = "SELECT content FROM $table WHERE config = 'fonts'";
		$request = $sbsql->query($query);
		$assoc   = $sbsql->assoc($request);
		$fonts   = utf8_encode($assoc['content']);
		// --------------------------------

		// --- Debug SQL
		if (_AM_SITE_DEBUG && !$_POST['form_submit']) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Form Type = '.$formType);						
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&op=" . $action;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		// --------------------------------
		// --- Code to insert
		// --------------------------------
		$sbform->addTextarea('Code GOOGLE FONTS', $fonts, array('id' => 'fonts', 'name' => 'fonts', 'style' => 'height: 200px !important;'), false, "Vous pouvez insérer le code à l'aide :<br>- du GOOGLE FONT SELECTOR<br>- ou bien depuis l'interface de <a href='https://fonts.google.com/' target='_blank'>Google Fonts</a><p><button type='button' class='btn btn-outline btn-warning btn-sm'>Plus vous insèrerez de Google Fonts et plus le chargement de vos pages sera ralenti !</button></p><br>");
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
	
	case "seo":
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$seo_keywords                 = $sbsanitize->displayText($_POST['seo_keywords'], 'UTF-8', 1, 0);
			$seo_description              = $sbsanitize->displayText($_POST['seo_description'], 'UTF-8', 1, 0);
			$seo_robots                   = $sbsanitize->displayText($_POST['seo_robots'], 'UTF-8', 1, 0);
			$seo_rating                   = $sbsanitize->displayText($_POST['seo_rating'], 'UTF-8', 1, 0);
			$seo_author                   = $sbsanitize->displayText($_POST['seo_author'], 'UTF-8', 1, 0);
			$seo_copyright                = $sbsanitize->displayText($_POST['seo_copyright'], 'UTF-8', 1, 0);
			$seo_generator                = $sbsanitize->displayText($_POST['seo_generator'], 'UTF-8', 1, 0);
			$seo_google_site_verification = $sbsanitize->displayText($_POST['seo_google_site_verification'], 'UTF-8', 1, 0);
			$seo_google_analytics         = $sbsanitize->displayText($_POST['seo_google_analytics'], 'UTF-8', 1, 0);
			
			// --- EDIT
			// UPDATE DATAS
			$query_keywords                 = "UPDATE $table SET content = '$seo_keywords' WHERE config = 'seo-keywords'";
			$query_description              = "UPDATE $table SET content = '$seo_description' WHERE config = 'seo-description'";
			$query_robots                   = "UPDATE $table SET content = '$seo_robots' WHERE config = 'seo-robots'";
			$query_rating                   = "UPDATE $table SET content = '$seo_rating' WHERE config = 'seo-rating'";
			$query_author                   = "UPDATE $table SET content = '$seo_author' WHERE config = 'seo-author'";
			$query_copyright                = "UPDATE $table SET content = '$seo_copyright' WHERE config = 'seo-copyright'";
			$query_generator                = "UPDATE $table SET content = '$seo_generator' WHERE config = 'seo-generator'";
			$query_google_site_verification = "UPDATE $table SET content = '$seo_google_site_verification' WHERE config = 'seo-google-site-verification'";
			$query_google_analytics         = "UPDATE $table SET content = '$seo_google_analytics' WHERE config = 'seo-google-analytics'";
			
			$result_keywords                 = $sbsql->query($query_keywords);			
			$result_description              = $sbsql->query($query_description);
			$result_robots                   = $sbsql->query($query_robots);
			$result_rating                   = $sbsql->query($query_rating);
			$result_author                   = $sbsql->query($query_author);
			$result_copyright                = $sbsql->query($query_copyright);
			$result_generator                = $sbsql->query($query_generator);
			$result_google_site_verification = $sbsql->query($query_google_site_verification);
			$result_google_analytics         = $sbsql->query($query_google_analytics );
			
			
			if ($result_keywords && $result_description && $result_robots && $result_rating && $result_author && $result_copyright && $result_generator && $result_google_site_verification && $result_google_analytics) {
				// --- Message SUCCES
				$sb_msg_valid = 'SEO modifié avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		}

		// --------------------------------
		// --- Recuperation des donnees
		// --------------------------------
		$query = "SELECT config, content FROM $table WHERE config LIKE 'seo-%'
														";
		$request = $sbsql->query($query);
		$assoc   = $sbsql->toarray($request);
		// --------------------------------
		foreach($assoc as $row) {
			$cs[$row['config']] = utf8_encode($row['content']);
		}
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG && !$_POST['form_submit']) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Form Type = '.$formType);						
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&op=" . $action;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		// --------------------------------
		// SEO Keywords / Description
		// --------------------------------
		$sbform->addTextarea('SEO Keywords', $cs['seo-keywords'], array('id' => 'seo_keywords', 'name' => 'seo_keywords', 'style' => 'height: 70px !important;'), false, "Les mots clés doivent être séparés par des virgules");
		$sbform->addTextarea('SEO Description', $cs['seo-description'], array('id' => 'seo_description', 'name' => 'seo_description', 'style' => 'height: 120px !important;'), false, "Google limite la META DESCRIPTION à 155 caractères aujourd'hui... Jusqu'à changement...");
		// -----------------------------------
		// Meta Tag (Robots)
		// -----------------------------------
		$sb_meta_robots = ['Index, Follow' => 'index,follow'
						  ,'No Index, Follow' => 'noindex,follow'
						  ,'Index, No Follow' => 'index,nofollow'
						  ,'No Index, No Follow' => 'noindex,nofollow'
						  ];
		$sbform->openSelect("Meta Robots", array("id"=>"seo_robots", "name"=>"seo_robots"));
		foreach($sb_meta_robots as $key => $val) {
			if ($val == $cs['seo-robots'])
				$sbform->addOption($key, array ("value"=>$val, "selected"=>""));
		else
				$sbform->addOption($key, array ("value"=>$val));
		}
		// --- Close Select
		$sbform->closeSelect("La Méta Robots déclarent aux moteurs de recherche quel contenu indexer");
		// -----------------------------------
		// Meta Tag (Rating)
		// -----------------------------------
		$sb_meta_rating = ['General' => 'general'
						  ,'14 years' => '14 years'
						  ,'Restricted' => 'restricted'
						  ,'Mature' => 'mature'
						  ];
		$sbform->openSelect("Meta Rating", array("id"=>"seo_rating", "name"=>"seo_rating"));
		foreach($sb_meta_rating as $key => $val) {
			if ($val == $cs['seo-rating'])
				$sbform->addOption($key, array ("value"=>$val, "selected"=>""));
		else
				$sbform->addOption($key, array ("value"=>$val));
		}
		// --- Close Select
		$sbform->closeSelect("La Méta Rating définit la moyenne d'âge des visiteurs et le contenu de votre site");
		// -----------------------------------
		// Meta Tag (Author)
		// -----------------------------------
		$sbform->addInput('text', 'Meta Author', array ('name' => 'seo_author', 'value' => "{$cs['seo-author']}", 'placeholder' => "Meta Author"), false, false, "La Méta Author définit le nom de l'auteur de votre site");
		// -----------------------------------
		// Meta Tag (Copyright)
		// -----------------------------------
		$sbform->addInput('text', 'Meta Copyright', array ('name' => 'seo_copyright', 'value' => "{$cs['seo-copyright']}", 'placeholder' => "Meta Copyright"), false, false, "La Méta Copyright définit les droits d'auteur de votre site");
		// -----------------------------------
		// Meta Tag (Generator)
		// -----------------------------------
		$sbform->addInput('text', 'Meta Generator', array ('name' => 'seo_generator', 'value' => "{$cs['seo-generator']}", 'placeholder' => "Meta Generator"), false, false, "La Méta Generator définit le logiciel moteur de votre site");
		// -----------------------------------
		// Meta Tag (Google Verify v1)
		// -----------------------------------
		$sbform->addInput('text', 'Meta Google Site Verification', array ('name' => 'seo_google_site_verification', 'value' => "{$cs['seo-google-site-verification']}", 'placeholder' => "Meta Google Site Verify"), false, false, "La Méta Google Site Verify vous permet de confirmer que vous êtes le propriétaire d'un site en ajoutant un code fournit par Google. Indiquer seulement le code fournit sous cette forme <span style='color: red'>+nxGUDJ4QpAZ5l9Bsjdi102tLVC21AIh5d1Nl23908vVuFHs34=</span>");
		// -----------------------------------
		// Meta Tag (Google Analytics)
		// -----------------------------------
		$sbform->addInput('text', 'Meta Google Analytics', array ('name' => 'seo_google_analytics', 'value' => "{$cs['seo-google-analytics']}", 'placeholder' => "Meta Google Analytics"), false, false, "La Méta Google Google Analytics vous permet d'effectuer le suivi du trafic de votre site sur l'outil fournit par Google Analytics. Indiquer seulement le code fournit en <span style='color: red'>rouge</span> sous cette forme UA-<span style='color: red'>xxxxxxx-x</span>");
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
	
}

$sbsmarty->assign('cmsconfig_help', "Ce code sera éxécuté sur votre site dès lors que vous aurez placé le tag suivant dans le HEADER de votre code, plus justement à la fin de la balise HEADER pour que votre code s'éxécute après ceux du thème :
									 <br><br><span style='text-align: center; font-weight: bold; color: green; display: block;'>{insert name=\"sbGetHeaders\"}</span>
									 <br><i><u>Exemple :</u></i>
									 <br><br>
									 <div id='code_sample' style='font-size: 12px;'>
									 &lt;!DOCTYPE html&gt;
									 <br>&nbsp;&lt;html dir=\"ltr\" lang=\"en-US\"&gt;
									 <br>&nbsp;&nbsp;&nbsp;&nbsp;&lt;head&gt;
									 <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;...
									 <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong style='color: green;'>{insert name=\"sbGetHeaders\"}</strong>
									 <br>&nbsp;&nbsp;&nbsp;&nbsp;&lt;/head&gt;
									 <br>&nbsp;&nbsp;&lt;body&gt;
									 </div><br>");

$sbsmarty->assign('cmsconfig_plugins_help', "Les plugins s'activeront sur votre site dès lors que vous aurez placé le tag suivant dans le FOOTER de votre code après tous les scripts à éxécuter :
									 <br><br><span style='text-align: center; font-weight: bold; color: green; display: block;'>{insert name=\"sbGetPlugins\"}</span>
									 <br>");

$sbsmarty->assign('cmsconfig_headerfooter_help', "Le code du <strong>HEADER</strong> sera éxécuté sur votre site dès lors que vous aurez placé le tag suivant où bon vous semble dans vos templates de votre thème :
												  <br><br><span style='text-align: center; font-weight: bold; color: green; display: block;'>{insert name=\"sbGetConfig\" id=\"header\"}</span>
												  <br><br>Le code du <strong>FOOTER</strong> sera éxécuté sur votre site dès lors que vous aurez placé le tag suivant où bon vous semble dans vos templates de votre thème :
												  <br><br><span style='text-align: center; font-weight: bold; color: green; display: block;'>{insert name=\"sbGetConfig\" id=\"footer\"}</span>
												  <br><br>");

$sbsmarty->assign('cmsconfig_email_help', "<img src='img/google-recaptcha.png' alt='API reCAPTCHA' />
										   <br><br>Vous devez créer une entrée dans l'<a href='https://www.google.com/recaptcha/admin' target='_blank'>API de Google reCAPTCHA</a> pour pouvoir utiliser vos formulaires de site.
										   <br><br><img src='img/google-recaptcha-in-action.png' alt='Google reCAPTCHA in action' />
										   <br><br>
										   ");

$sbsmarty->assign('cmsconfig_comingsoon_help', "Le mode \"Coming Soon\" activé permet de construire son site sans que vos visiteurs puissent accéder à son contenu, ils seront redirigés vers une page d'attente ou de maintenance.<br><br>Ce mode peut également être utilisé lorsque vous effectuez une modification importante à votre site web.<br><br>L'url pour que vous puissiez accéder à votre site lorsque celui-ci est fermé au public sera celle-ci :<br><div style='text-align: center;'><span style='font-weight: bold; color: rgb(255, 102, 0);'><a target='_blank' href='".trim($sb_link_settings[15])."?d=".$cs['coming-soon-url']."'>".trim($sb_link_settings[15])."?d=".$cs['coming-soon-url']."</a></span><br></div><br>Vous pourrez voir votre site le temps de la session de votre serveur (par défaut).<br><br>Pour activer / désactiver le COMING SOON, placez la constante SBMAINTENANCE à <b>true</b> ou <b>false</b> dans le fichier de configuration de votre CMS SBUIADMIN.");

$sbsmarty->assign('cmsconfig_multilang_help',	"L'option multilangue désactivé n'affichera que la langue FR sur votre site web.<br>Dans l'administration, dans la gestion des pages et des blocs ne s'afficheront également que les champs FR.<br><br>Si vous activez l'option multilangue, les langues définies dans le champs 'langue(s)' vous permettront d'avoir ces langues sur votre site web.<br>Dans l'administration, dans la gestion des pages et des blocs s'afficheront les blocs supplémentaires des langues disponibles.<br><br><img style='width: 100%;' alt=''src='img/multilang.jpg'>");

function sbHowToPlugins($title, $htmlpage, $id, $button = '') {
	return '<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#'.$id.'" onclick="javascript:return false;">Howto '.$button.'</button>
			<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="'.$id.'Label" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="'.$id.'Label">'.$title.'</h4>
						</div>
						<div class="modal-body" style="text-align: left;">'.file_get_contents('../plugins/'.$htmlpage).'</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>';
}

// ---------------------------------------------------
// ---------------------------------------------------
// IMPORTANT: Don't remove these lines
// ---------------------------------------------------
// ---------------------------------------------------
// ----------------------------------------
// ASSIGN Page TITLE - Modify this |
// ----------------------------------------
$sbsmarty->assign('page_title', 'CMS Configuration');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($action, 'UTF-8', 0, 1)));
// --- Assign Action for ACE JS loading
$sbsmarty->assign('action_edit', $action);

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