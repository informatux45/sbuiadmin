<?php
 /**
 * Admin Startbootstrap
 * CONTACT Module
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
$module_page = 'contact';
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
$table           = _AM_DB_PREFIX . "sb_contact";
$table_cmsconfig = _AM_DB_PREFIX . "sb_config"; 
$text        = "Formulaire de contact";
$text_s      = "Formulaires de contact";

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
		$sb_table_header = array('Nom', 'Shortcode', 'Actions');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query    = "SELECT * FROM $table";
		$request2 = $sbsql->query($query);
		$result2  = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('allcontact', $result2);

		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query;
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
		$legend_add_edit = ($action == 'add') ? "Ajouter un " . strtolower($text) : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id          = intval($_POST['id']);
			$title       = $sbsanitize->displayText($_POST['title'], 'UTF-8', 1, 0);
			$senders     = $sbsanitize->displayText($_POST['senders'], 'UTF-8', 1, 0);
			$recipients  = $sbsanitize->displayText($_POST['recipients'], 'UTF-8', 1, 0);
			// --- Email subject
			$subject_fr = $sbsanitize->displayText($_POST['subject_fr'], 'UTF-8', 1, 0);
			$subject    = "[fr]".$subject_fr."[/fr]";
			if ($getMultilang) {
				$subject_en = $sbsanitize->displayText($_POST['subject_en'], 'UTF-8', 1, 0);				
				$subject   .= "[en]".$subject_en."[/en]";
			}
			$contactform = $sbsanitize->displayText($_POST['contactform'], 'UTF-8', 1, 0);
			$active      = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);

	
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				$query = "INSERT INTO $table (title,recipients,subject,form,active)
						  VALUES ('$title','$recipients','$subject','$contactform','$active')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$title = $recipients = $subject = $subject_fr = $subject_en = $contactform = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {
				// UPDATE DATAS
				$query = "UPDATE $table SET title = '$title'
										   ,recipients = '$recipients'
										   ,subject = '$subject'
										   ,form = '$contactform'
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
			$title = $recipients = $subject = $subject_fr = $subject_en = $contactform = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id          = intval($_GET['id']);
			$query_1     = "SELECT * FROM $table WHERE id = $id";
			$requestQ    = $sbsql->query($query_1);
			$assoc       = $sbsql->assoc($requestQ);
			$title       = $sbsanitize->displayLang(utf8_encode($assoc['title']));
			$recipients  = $assoc['recipients'];
			$contactform = $assoc['form'];
			$active      = $assoc['active'];
			$subject_fr  = $sbsanitize->displayLang(utf8_encode($assoc['subject']));
			// ---------------------------
			$subject_en  = $sbsanitize->displayLang(utf8_encode($assoc['subject']), 'en');

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
		// Nom du formulaire
		// ----------------------------
		$sbform->addInput('text', 'Nom du '.strtolower($text), array ('name' => 'title', 'value' => "$title", 'placeholder' => "Nom de votre " . strtolower($text)), true);
		// --------------------------------
		// Emails (Destinataires)
		// --------------------------------	
		$sbform->addInput('text', "Email(s) des destinataires", array ('name' => 'recipients', 'value' => "$recipients", 'placeholder' => "Email(s) des destinataires", 'icon' => 'envelope-o'), false, false, "Email(s) séparés par des virgules à qui sera expédié le ou les messages du site.<br>Les destinataires par défaut sont : <b style='color: darkgray;'>".sbGetConfig('email_to')."</b>");
		// --------------------------------
		// Sujet FR / EN
		// --------------------------------
		$subject_fr_title = ($getMultilang) ? 'Sujet (FR)' : 'Sujet' ;
		$sbform->addInput('text', "$subject_fr_title", array ('name' => 'subject_fr', 'value' => "$subject_fr", "icon" => "pencil", 'placeholder' => "Sujet de l'email"), false, false, "Sujet de l'email qui sera envoyé depuis votre formulaire.<br>Le sujet par défaut est : <b style='color: darkgray;'>".sbGetConfig('email_subject', 'fr')."</b>");
		if ($getMultilang)
			$sbform->addInput('text', 'Sujet (EN)', array ('name' => 'subject_en', 'value' => "$subject_en", "icon" => "pencil", 'placeholder' => "Sujet de l'email (EN)"), false, false, "Sujet de l'email qui sera envoyé depuis votre formulaire.<br>Le sujet par défaut est : <b style='color: darkgray;'>".sbGetConfig('email_subject', 'en')."</b>");
		// --------------------------------
		// Formulaire (construct)
		// --------------------------------
		$sbform->addAnything('<p>&nbsp;</p>
							 <p class="help-block">L\'usage du RECAPTCHA INVISIBLE ne requiert pas le bouton SUBMIT car il devient le bouton de soumission de votre formulaire.</p>
							 <button class="btn btn-danger btn-xs" type="button" onclick="javascript:sbInsertText(\'contactform\', \'[TEXT name=name/required=required]\')">NAME</button>
							 &nbsp;&nbsp;
							 <button class="btn btn-danger btn-xs" type="button" onclick="javascript:sbInsertText(\'contactform\', \'[TEXT name=email/required=required]\')">EMAIL</button>
							 &nbsp;&nbsp;
							 <button class="btn btn-primary btn-xs" type="button" onclick="javascript:sbInsertText(\'contactform\', \'[TEXT name=your-name/required=required]\')">TEXT</button>
							 &nbsp;&nbsp;
							 <button class="btn btn-primary btn-xs" type="button" onclick="javascript:sbInsertText(\'contactform\', \'[TXTAREA name=your-name/required=required]\')">TXTAREA</button>
							 &nbsp;&nbsp;
							 <button class="btn btn-primary btn-xs" type="button" onclick="javascript:sbInsertText(\'contactform\', \'[SELECT name=selection/options=choisissez un choix|choix1|choix2|choix3|choix4/value=0|10|20|30|40/required=required]\')">SELECT</button>
							 &nbsp;&nbsp;
							 <button class="btn btn-danger btn-xs" type="button" onclick="javascript:sbInsertText(\'contactform\', \'[RECAPTCHA]\')">RECAPTCHA</button>
							 &nbsp;&nbsp;
							 <button class="btn btn-danger btn-xs" type="button" onclick="javascript:sbInsertText(\'contactform\', \'[RECAPTCHA_INVISIBLE name=go/value=Envoyer]\')">RECAPTCHA_INVISIBLE</button>
							 &nbsp;&nbsp;
							 <button class="btn btn-primary btn-xs" type="button" onclick="javascript:sbInsertText(\'contactform\', \'[SUBMIT name=go/value=Envoyer]\')">SUBMIT</button>');
		$sbform->addTextarea('', $contactform, array('id' => 'contactform', 'name' => 'contactform', 'style' => 'height: 400px !important; background: url(img/form-bg-textarea.png) repeat-y; font: normal 12px verdana; line-height: 25px; padding: 2px 10px; border: 2px solid #ddd; border-left: 0px; background-attachment: local;'), false, "Les boutons rouges ont caractère d'obligation. Si vous les omettez, le formulaire de contact ne fonctionnera pas correctement.");
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

	case "settings":
		// --- Initialization
		$formName        = "editcontact_form";
		$formType        = "settings";
		$btn_add_edit    = "Modifier";
		$legend_add_edit = "Modifier les paramètres généraux";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$email_to         = $sbsanitize->displayText($_POST['email_to'], 'UTF-8', 1, 0);
			// --- Email subject
			$email_subject_fr = $sbsanitize->displayText($_POST['email_subject_fr'], 'UTF-8', 1, 0);
			$email_subject    = "[fr]".$email_subject_fr."[/fr]";
			if ($getMultilang) {
				$email_subject_en = $sbsanitize->displayText($_POST['email_subject_en'], 'UTF-8', 1, 0);				
				$email_subject   .= "[en]".$email_subject_en."[/en]";
			}
			$email_publickey  = $sbsanitize->displayText($_POST['email_publickey'], 'UTF-8', 1, 0);
			$email_privatekey = $sbsanitize->displayText($_POST['email_privatekey'], 'UTF-8', 1, 0);
			
			// --- EDIT
			// UPDATE DATAS
			$query_email_to         = "UPDATE $table_cmsconfig SET content = '$email_to' WHERE config = 'email_to'";
			$query_email_subject    = "UPDATE $table_cmsconfig SET content = '$email_subject' WHERE config = 'email_subject'";
			$query_email_publickey  = "UPDATE $table_cmsconfig SET content = '$email_publickey' WHERE config = 'email_publickey'";
			$query_email_privatekey = "UPDATE $table_cmsconfig SET content = '$email_privatekey' WHERE config = 'email_privatekey'";
			
			$result_edit_email_to         = $sbsql->query($query_email_to);
			$result_edit_email_subject    = $sbsql->query($query_email_subject);
			$result_edit_email_publickey  = $sbsql->query($query_email_publickey);
			$result_edit_email_privatekey = $sbsql->query($query_email_privatekey);
			if ($result_edit_email_to && $result_edit_email_publickey && $result_edit_email_privatekey && $result_edit_email_subject) {
				// --- Message SUCCES
				$sb_msg_valid = 'Configuration EMAIL modifiée avec succès';
			} else {
				// --- Message ERROR
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_email_to . "\n" . $query_email_publickey . "\n" . $result_edit_email_privatekey . "\n" . $result_edit_email_subject . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		// --- Recuperation des donnees
		// --------------------------------
		$queryT   = "SELECT content FROM $table_cmsconfig WHERE config = 'email_to'";
		$queryP   = "SELECT content FROM $table_cmsconfig WHERE config = 'email_publickey'";
		$queryS   = "SELECT content FROM $table_cmsconfig WHERE config = 'email_privatekey'";
		$queryA   = "SELECT content FROM $table_cmsconfig WHERE config = 'email_subject'";
		$requestT = $sbsql->query($queryT);
		$requestP = $sbsql->query($queryP);
		$requestS = $sbsql->query($queryS);
		$requestA = $sbsql->query($queryA);
		$assocT   = $sbsql->assoc($requestT);
		$assocP   = $sbsql->assoc($requestP);
		$assocS   = $sbsql->assoc($requestS);
		$assocA   = $sbsql->assoc($requestA);
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $queryT . "\n" . $queryP . "\n" . $queryS . "\n" . 'Form Type = '.$formType);						
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&op=" . $formType . "&a=" . $action;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --------------------------------
		// Emails
		// --------------------------------	
		$sbform->addInput('text', 'Email(s)', array ('name' => 'email_to', 'value' => "{$assocT[content]}", 'placeholder' => "Email(s)", 'icon' => 'envelope-o'), false, false, "Email(s) séparés par des virgules à qui sera expédié le ou les messages du site");
		// --------------------------------
		// Sujet (Général)
		// --------------------------------
		$email_subject_title = ($getMultilang) ? 'Sujet (FR)' : 'Sujet' ;
		$sbform->addInput('text', "$email_subject_title", array ('name' => 'email_subject_fr', 'value' => "{$sbsanitize->displayLang(utf8_encode($assocA[content]))}", 'placeholder' => "Email(s)", 'icon' => 'pencil'), false, false, "Sujet principal de vos formulaires (si un formulaire possède un sujet, celui-ci sera utilisé en priorité)");
		if ($getMultilang) {
			$sbform->addInput('text', 'Sujet (EN)', array ('name' => 'email_subject_en', 'value' => "{$sbsanitize->displayLang(utf8_encode($assocA[content]), 'en')}", 'placeholder' => "Email(s)", 'icon' => 'pencil'), false, false, "Sujet (EN) principal de vos formulaires (si un formulaire possède un sujet, celui-ci sera utilisé en priorité)");
		}
		// --------------------------------
		// Google RECAPTCHA Keys
		// --------------------------------	
		$sbform->addInput('text', "[GOOGLE RECAPTCHA] <span style='color: red;'>Clé du site</span>", array ('name' => 'email_publickey', 'value' => "{$assocP[content]}", 'placeholder' => "clé reCAPTCHA publique", 'icon' => '0Publique'), false, false, "Clé dans le code HTML que vous proposez à vos utilisateurs");
		$sbform->addInput('text', "[GOOGLE RECAPTCHA] <span style='color: red;'>Clé secrète</span>", array ('name' => 'email_privatekey', 'value' => "{$assocS[content]}", 'placeholder' => "clé reCAPTCHA privée", 'icon' => '0Secrète'), false, false, "Clé pour toute communication entre votre site et Google. Veillez à ne pas la divulguer, car il s'agit d'une clé secrète");
		// --------------------------------			
		// --- Hiddens / Buttons
		// --------------------------------	
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		// --------------------------------	
		// --- Close Form
		// --------------------------------	
		$sbform->closeForm();
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
$sbsmarty->assign('page_title', 'Formulaires de contact');
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