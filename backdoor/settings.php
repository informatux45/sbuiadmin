<?php
/**
 * Admin Startbootstrap
 * Manage SETTINGS
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
// Start Session
// -----------------------
session_start();

// -----------------------
// Module URL
// -----------------------
$module_page = 'settings';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

/* ----------------------------- *
// SB Settings File
 * Referentiel du fichier SETTINGS
 * -----------------------------
 * 0  - Nom du client
 * 1  - Administrateurs
 * 2  - Database Host
 * 3  - Database Name
 * 4  - Database User
 * 5  - Database Password
 * 6  - Repertoire uploads (DIR)
 * 7  - Upload max
 * 8  - Modules autorises
 * 9  - Debug General
 * 10 - Debug Formulaire
 * 11 - Debug Smarty
 * 12 - Extensions autorisees (upload)
 * 13 - Repertoire uploads (URL)
 * 14 - Uploads simultanes (limit)
 * 15 - URL du site client
 * 16 - Sandbox
 * 17 - Cms
 * 18 - Image Scaling Max Size (Medias upload)
 * 19 - Google Recaptcha (Clé du site publique)
 * 20 - Google Recaptcha (Clé secrète)
 * 21 - DB prefix
 * 22 - Captcha Mode
 * 23 - Upgrade Mode
 * 24 - Coming soon
 * 25 - Debug General Front
 * 26 - Debug Smarty Front
 * 27 - Smarty Force Compile
 * 28 - Rewrite Url
 * 29 - Smarty Caching
 * 30 - Smarty Caching Lifetime
 * ---------------------------- */
$sb_settings_file = _AM_SETTINGS_FILE;

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------
$action = $_GET['a'];
switch($action) {
	default:
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = "edit_form";
		$formType        = "edit";
		$btn_add_edit    = "Modifier";
		$legend_add_edit = "Modifier votre configuration générale";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$sb_output_file  = $sbsanitize->displayText($_POST['customer_name'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['administrators'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['dbhost'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['dbname'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['dbuser'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['dbpwd'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['diruploads'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['upload_max'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['modules'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= ($_POST['debug_general'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= ($_POST['debug_form'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= ($_POST['debug_smarty'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['upload_exts'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['urluploads'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['upload_limit'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['url_customer'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= ($_POST['sandbox'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= ($_POST['cms'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['scaling_maxsize'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['recaptcha_public'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['recaptcha_secret'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['dbprefix'], 'UTF-8', 1, 0) . "\n";
			$sb_output_file .= ($_POST['captcha_mode'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= ($_POST['upgrade_mode'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= ($_POST['coming_soon'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= ($_POST['debug_general_front'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= ($_POST['debug_smarty_front'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= ($_POST['smarty_force_tpls'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= ($_POST['rewrite_url'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= ($_POST['smarty_caching'] === "on") ? "1"."\n" : "0"."\n";
			$sb_output_file .= $sbsanitize->displayText($_POST['smarty_caching_time'], 'UTF-8', 1, 0) . "\n";
			
			// Locker le fichier pour qu'une seule personne a la fois ecrive dedans
			$result_edit = file_put_contents($sb_settings_file, $sb_output_file, FILE_USE_INCLUDE_PATH | LOCK_EX);
											 
				//$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Configuration modifiée avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}
		}
		
		// --------------------------------
		// --- Ouverture du fichier
		$sb_settings = file($sb_settings_file);
		// --- Initialisation
		$sb_config_customer_name       = $sb_settings[0];
		$sb_config_administrators      = $sb_settings[1];
		$sb_config_dbhost              = $sb_settings[2];
		$sb_config_dbname              = $sb_settings[3];
		$sb_config_dbuser              = $sb_settings[4];
		$sb_config_dbpwd               = $sb_settings[5];
		$sb_config_diruploads          = $sb_settings[6];
		$sb_config_upload_max          = $sb_settings[7];
		$sb_config_modules             = $sb_settings[8];
		$sb_config_debug_general       = $sb_settings[9];
		$sb_config_debug_form          = $sb_settings[10];
		$sb_config_debug_smarty        = $sb_settings[11];
		$sb_config_upload_exts         = $sb_settings[12];
		$sb_config_urluploads          = $sb_settings[13];
		$sb_config_upload_limit        = $sb_settings[14];
		$sb_config_url_customer        = $sb_settings[15];
		$sb_config_sandbox             = $sb_settings[16];
		$sb_config_cms                 = $sb_settings[17];
		$sb_config_scaling_maxsize     = $sb_settings[18];
		$sb_config_recaptcha_public    = $sb_settings[19];
		$sb_config_recaptcha_secret    = $sb_settings[20];
		$sb_config_dbprefix            = $sb_settings[21];
		$sb_config_captcha_mode        = $sb_settings[22];
		$sb_config_upgrade_mode        = $sb_settings[23];
		$sb_config_coming_soon         = $sb_settings[24];
		$sb_config_debug_general_front = $sb_settings[25];
		$sb_config_debug_smarty_front  = $sb_settings[26];
		$sb_config_smarty_force_tpls   = $sb_settings[27];
		$sb_config_rewrite_url         = $sb_settings[28];
		$sb_config_smarty_caching      = $sb_settings[29];
		$sb_config_smarty_caching_time = $sb_settings[30];
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) $sbsmarty->assign('file_content', $sb_settings);						
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$sbform->addInput('text', 'Nom du client', array ('name' => 'customer_name', 'value' => "$sb_config_customer_name", 'placeholder' => "Nom de votre client"), true, false, "Visible dans la barre d'administration");
		$sbform->addInput('text', 'URL du site client', array ('name' => 'url_customer', 'value' => "$sb_config_url_customer", 'placeholder' => "URL du site de votre client (http://...)"), true, false, "Ajoute un lien sur le nom de votre client (header)");
		$sbform->addInput('text', 'Administrateurs', array ('name' => 'administrators', 'value' => "$sb_config_administrators", 'placeholder' => "Nom des administrateurs"), true, false, "Login des admins séparés par des virgules sans espace");
		$sbform->addInput('text', 'DB Host', array ('name' => 'dbhost', 'value' => "$sb_config_dbhost", 'placeholder' => "Database Host"), true, false);
		$sbform->addInput('text', 'DB Name', array ('name' => 'dbname', 'value' => "$sb_config_dbname", 'placeholder' => "Database Name"), true, false);
		$sbform->addInput('text', 'DB User', array ('name' => 'dbuser', 'value' => "$sb_config_dbuser", 'placeholder' => "Database User"), true, false);
		$sbform->addInput('text', 'DB Password', array ('name' => 'dbpwd', 'value' => "$sb_config_dbpwd", 'placeholder' => "Database Password"), true, false);
		$sbform->addInput('text', 'DB Prefix', array ('name' => 'dbprefix', 'value' => "$sb_config_dbprefix", 'placeholder' => "Database Prefix table"), false, false);		
		$sbform->addInput('text', 'Répertoire d\'upload', array ('name' => 'diruploads', 'value' => "$sb_config_diruploads", 'placeholder' => "Répertoire de l'upload"), true, false, "ex:  <strong>../votre_repertoire</strong>  -  s'il se trouve juste en dessous de l'arborescence du répertoire d'administration<br>Chemin relatif (obligatoirement), pas d'absolu !!! - Ne pas mettre le  <span style='color: red;'>/</span>  à la fin");
		$sbform->addInput('text', 'URL d\'upload', array ('name' => 'urluploads', 'value' => "$sb_config_urluploads", 'placeholder' => "URL de l'upload (http://...)"), true, false, "Ne pas mettre le  <span style='color: red;'>/</span>  à la fin");
		$sbform->addInput('text', 'Taille max. autorisée pour l\'upload des médias (client)', array ('name' => 'upload_max', 'value' => "$sb_config_upload_max", 'placeholder' => "Répertoire de l'upload"), true, false, "Usage : 10KB, 10.5KB, 2MB, 2.5MB, 1GB, 1TB");
		// Upload exts Allowed
		$sbform->addInput('text', 'Extensions autorisées', array ('name' => 'upload_exts', 'value' => "$sb_config_upload_exts", 'placeholder' => "Extensions autorisées"), true, false, "Extensions autorisées à l'upload dans votre administration séparés par des virgules sans espace");
		$sbform->addInput('text', "Nombre d'uploads simultanés", array ('name' => 'upload_limit', 'value' => "$sb_config_upload_limit", 'placeholder' => "Nombre d'uploads simultanés"), true, false);
		$sbform->addInput('text', "Taille maximum autorisée", array ('name' => 'scaling_maxsize', 'value' => "$sb_config_scaling_maxsize", 'placeholder' => "Taille maximum autorisée en pixels"), true, false, "Taille en pixels maximum autorisée pour vos médias (largeur et hauteur)<br>Ex : <b>1024</b> (CORRECT) --- Ex : <b>1024px</b> (INCORRECT)");
		$sbform->addInput('text', 'Modules', array ('name' => 'modules', 'value' => "$sb_config_modules", 'placeholder' => "Nom de vos modules"), false, false, "Nom de vos modules autorisés dans votre administration séparés par des virgules sans espace");
		$sbform->addInput('text', "Google Recaptcha (Clé publique)", array ('name' => 'recaptcha_public', 'value' => "$sb_config_recaptcha_public", 'placeholder' => "Clé publique"), false, false, "Clé du site dans le code HTML que vous proposez à vos utilisateurs");
		$sbform->addInput('text', 'Google Recaptcha (Clé secrète)', array ('name' => 'recaptcha_secret', 'value' => "$sb_config_recaptcha_secret", 'placeholder' => "Clé secrète"), false, false, "Clé pour toute communication entre votre site et Google. Veillez à ne pas la divulguer, car il s'agit d'une clé secrète.");
		// Checkbox des modes debug
		$tab_check = array();
		$tab_check[0]['text']    = 'Debug général (ADMIN)';
		$tab_check[0]['name']    = 'debug_general';
		$tab_check[0]['checked'] = ($sb_config_debug_general == 1) ? '1' : '0';
		$tab_check[1]['text']    = 'Debug formulaire (ADMIN)';
		$tab_check[1]['name']    = 'debug_form';
		$tab_check[1]['checked'] = ($sb_config_debug_form == 1) ? '1' : '0';
		$tab_check[2]['text']    = 'Debug Smarty (ADMIN)';
		$tab_check[2]['name']    = 'debug_smarty';
		$tab_check[2]['checked'] = ($sb_config_debug_smarty == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation des différents modes de DEBUG (ADMINISTRATION)', $tab_check, '', false, '<br />', "Activation des modes DEBUG Inline, Formulaires, Smarty (core)");
		// --------------------------------------------------
		$tab_check_7 = array();
		$tab_check_7[0]['text']    = 'Debug général (FRONT)';
		$tab_check_7[0]['name']    = 'debug_general_front';
		$tab_check_7[0]['checked'] = ($sb_config_debug_general_front == 1) ? '1' : '0';
		$tab_check_7[1]['text']    = 'Debug Smarty (FRONT)';
		$tab_check_7[1]['name']    = 'debug_smarty_front';
		$tab_check_7[1]['checked'] = ($sb_config_debug_smarty_front == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation des différents modes de DEBUG (FRONT)', $tab_check_7, '', false, '<br />', "Activation des modes DEBUG (FRONT) Inline, Smarty (core)");
		// Checkbox du Sandbox
		$tab_check_2 = array();
		$tab_check_2[0]['text']    = 'Sandbox';
		$tab_check_2[0]['name']    = 'sandbox';
		$tab_check_2[0]['checked'] = ($sb_config_sandbox == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation du SANDBOX', $tab_check_2, '', false, '<br />', "Exemples de tableau / CRUD pour monter vos propres modules<br>Visible dans le menu principal");
		// Checkbox du CMS
		$tab_check_3 = array();
		$tab_check_3[0]['text']    = 'Activé';
		$tab_check_3[0]['name']    = 'cms';
		$tab_check_3[0]['checked'] = ($sb_config_cms == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation du CMS', $tab_check_3, '', false, '<br />', "Permet d'afficher la gestion du menu<br>Activer SBUIADMIN en CMS (si coché) ou en Administration Autonome (si non coché)");
		// Checkbox du mode CAPTCHA
		$tab_check_4 = array();
		$tab_check_4[0]['text']    = 'Activé';
		$tab_check_4[0]['name']    = 'captcha_mode';
		$tab_check_4[0]['checked'] = ($sb_config_captcha_mode == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation du mode CAPTCHA (Login)', $tab_check_4, '', false, '<br />', "Permet d'activer le captcha lors du login");
		// Checkbox du mode UPGRADE
		$tab_check_5 = array();
		$tab_check_5[0]['text']    = 'Activé';
		$tab_check_5[0]['name']    = 'upgrade_mode';
		$tab_check_5[0]['checked'] = ($sb_config_upgrade_mode == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation du mode UPGRADE (Admin)', $tab_check_5, '', false, '<br />', "Permet d'activer le mode UPGRADE de l'administration");
		// Checkbox du mode COMING SOON
		$tab_check_6 = array();
		$tab_check_6[0]['text']    = 'Activé';
		$tab_check_6[0]['name']    = 'coming_soon';
		$tab_check_6[0]['checked'] = ($sb_config_coming_soon == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation du mode COMING SOON (Maintenance)', $tab_check_6, '', false, '<br />', "Permet d'activer le mode COMING SOON (Maintenance du site)<br>Ouvert uniquement aux administrateurs ou par url spécifique (<a href='"._AM_SITE_URL."index.php?p=cmsconfig&op=comingsoon'>configuration</a>)");
		// Checkbox du mode REWRITE URL
		$tab_check_8 = array();
		$tab_check_8[0]['text']    = 'Activé';
		$tab_check_8[0]['name']    = 'rewrite_url';
		$tab_check_8[0]['checked'] = ($sb_config_rewrite_url == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation du Rewrite URL', $tab_check_8, '', false, '<br />', "Permet d'activer la réécriture d'adresse (URLs courtes)");
		// Smarty force compile tpls
		$tab_check_9 = array();
		$tab_check_9[0]['text']    = 'Smarty Force Compile (FRONT)';
		$tab_check_9[0]['name']    = 'smarty_force_tpls';
		$tab_check_9[0]['checked'] = ($sb_config_smarty_force_tpls == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation de la compilation des templates Smarty', $tab_check_9, '', false, '<br />', "Désactiver ce mode quand le site est en production !");
		// Smarty cache
		$tab_check_10[0]['text']    = 'Smarty Cache Templates';
		$tab_check_10[0]['name']    = 'smarty_caching';
		$tab_check_10[0]['checked'] = ($sb_config_smarty_caching == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation du cache des templates Smarty', $tab_check_10, '', false, '<br />', "Si le cache est activé, choisir la durée du cache des templates");
		// Smarty Lifetime
		$sb_options_lifetime = ['30'     => '30 secondes'
							   ,'60'     => '1 minute'
							   ,'300'    => '5 minutes'
							   ,'1800'   => '30 minutes'
							   ,'3600'   => '1 heure'
							   ,'18000'  => '5 heures'
							   ,'86400'  => '1 jour'
							   ,'259200' => '3 jours'
							   ,'604800' => '1 semaine'
								];
		$sbform->openSelect("Durée du cache Smarty", array("id"=>"smarty_caching_time", "name"=>"smarty_caching_time"));
		$sbform->addOption('Choisissez une durée de cache', array ("value"=>"", "selected"=>""));
		foreach($sb_options_lifetime as $key => $value) {
			if ($key == $sb_config_smarty_caching_time)
				$sbform->addOption($value, array ("value" => $key, "selected"=>""));
		else
				$sbform->addOption($value, array ("value" => $key));
		}
		// --- Close Select
		$sbform->closeSelect("Choisissez la durée en secondes pendant laquelle un cache de template est valide.<br>Une fois cette durée dépassée, le cache est regénéré.");
		// --- Hiddens / Buttons
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --- Close Form
		$sbform->closeForm ();
		
	break;

}


// ----------------------
// ASSIGN Settings
// ----------------------
$sbsmarty->assign('sb_config_customer_name', trim($sb_config_customer_name));
$sbsmarty->assign('sb_config_administrators', trim($sb_config_administrators));
$sbsmarty->assign('sb_config_dbhost', trim($sb_config_dbhost));
$sbsmarty->assign('sb_config_dbname', trim($sb_config_dbname));
$sbsmarty->assign('sb_config_dbuser', trim($sb_config_dbuser));
$sbsmarty->assign('sb_config_dbpwd', trim($sb_config_dbpwd));
$sbsmarty->assign('sb_config_diruploads', trim($sb_config_diruploads));
$sbsmarty->assign('sb_config_urluploads', trim($sb_config_urluploads));
$sbsmarty->assign('sb_config_upload_max', trim($sb_config_upload_max));
$sbsmarty->assign('sb_config_modules', trim($sb_config_modules));
$sbsmarty->assign('sb_config_debug_general', trim($sb_config_debug_general));
$sbsmarty->assign('sb_config_debug_form', trim($sb_config_debug_form));
$sbsmarty->assign('sb_config_debug_smarty', trim($sb_config_debug_smarty));
$sbsmarty->assign('sb_config_upload_exts', trim($sb_config_upload_exts));
$sbsmarty->assign('sb_config_upload_limit', trim($sb_config_upload_limit));
$sbsmarty->assign('sb_config_url_customer', trim($sb_config_url_customer));
$sbsmarty->assign('sb_config_sandbox', trim($sb_config_sandbox));
$sbsmarty->assign('sb_config_cms', trim($sb_config_cms));
$sbsmarty->assign('sb_config_scaling_maxsize', trim($sb_config_scaling_maxsize));
$sbsmarty->assign('sb_config_recaptcha_public', trim($sb_config_recaptcha_public));
$sbsmarty->assign('sb_config_recaptcha_secret', trim($sb_config_recaptcha_secret));
$sbsmarty->assign('sb_config_dbprefix', trim($sb_config_dbprefix));
$sbsmarty->assign('sb_config_captcha_mode', trim($sb_config_captcha_mode));
$sbsmarty->assign('sb_config_upgrade_mode', trim($sb_config_upgrade_mode));
$sbsmarty->assign('sb_config_coming_soon', trim($sb_config_coming_soon));
$sbsmarty->assign('sb_config_debug_general_front', trim($sb_config_debug_general_front));
$sbsmarty->assign('sb_config_debug_smarty_front', trim($sb_config_debug_smarty_front));
$sbsmarty->assign('sb_config_smarty_force_tpls', trim($sb_config_smarty_force_tpls));
$sbsmarty->assign('sb_config_rewrite_url', trim($sb_config_rewrite_url));
$sbsmarty->assign('sb_config_smarty_caching', trim($sb_config_smarty_caching));
$sbsmarty->assign('sb_config_smarty_caching_time', trim($sb_config_smarty_caching_time));

// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Configuration');

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
// $sbsql->close();
?>
