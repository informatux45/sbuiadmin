<?php
/**
 * Admin Startbootstrap
 * Manage USERS
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
//defined('SBUIADMIN_PATH') or die('Are you crazy!');

// -----------------------
// Module URL
// -----------------------
$module_page = 'users';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// --------------------------------------------
// --------------------------------------------
// --------------------------------------------
// --------------------------------------------
// --------------------------------------------

$table = _AM_DB_PREFIX . "sb_users";
$table_blockedip = _AM_DB_PREFIX . "sb_blocked_ip";
$text  = "Utilisateur";
$sbsmarty->assign('sb_form_submit_value', 'Modifier');

$action = $_GET['a'];
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id   = intval($_GET['id']);
			$query_2  = "DELETE FROM $table WHERE id = '$get_id'";
			$request  = $sbsql->query($query_2);
			
			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header =  array('Gravatar', 'Groupe', 'Nom', 'Email', 'Dernière connexion', 'action');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query     = "SELECT * FROM $table";
		$request2  = $sbsql->query($query);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('alluser', $result2);
		
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
			$id       = intval($_POST['id']);
			$username = $sbsanitize->displayText($_POST['sbusername'], 'UTF-8', 1, 0);
			$password = $sbsanitize->displayText($_POST['sbpassword'], 'UTF-8', 1, 0);
			$email    = $sbsanitize->displayText($_POST['email'], 'UTF-8', 1, 0);
			$active   = $sbsanitize->displayText($_POST['active'], 'UTF-8', 1, 0);
			
			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				// --- Encrypt password
				$password = $sbusers->encrypt($password);
				$query = "INSERT INTO $table (`username`, `password`, `email`, `active`, `logintime`, `lastlogin`, `menu`, `groupe`)
						  VALUES ('$username','$password','$email','$active','0','0',' ','admin')";
				$result_add = $sbsql->query($query);
				if ($result_add) {
					// --- Vider les champs du formulaire
					$username = $password = $email = $active = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {

				// UPDATE DATAS
				if ($password == '') { 
					// Request without password
					$query = "UPDATE $table SET username = '$username'
																	,email = '$email'
																	,active = '$active'
																	WHERE id = '$id'";					
				} else {
					// --- Encrypt password
					$password = $sbusers->encrypt($password);
					$query = "UPDATE $table SET username = '$username'
																	,password = '$password'
																	,email = '$email'
																	,active = '$active'
																	WHERE id = '$id'";
				}
											 
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
			$username = $password = $email = $active = '';
		}
		// --------------------------------
		if ($formType == 'edit' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id']);
			$query_1  = "SELECT * FROM $table WHERE id = $id";
			$requestQ = $sbsql->query($query_1);
			$assoc    = $sbsql->assoc($requestQ);
			$username = utf8_encode($assoc['username']);
			$password = utf8_encode($assoc['password']);
			$email    = utf8_encode($assoc['email']);
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
		// ----------------------------
		// --- Actif / Desactive
		// ----------------------------
		$active = ($active) ? '1' : '0';
		$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
		// ----------------------------
		// --- Nom de l'utilisateur (login)
		// ----------------------------
		$sbform->addInput('text', 'Utilisateur', array ('name' => 'sbusername', 'value' => "$username"), true);
		// ----------------------------
		// --- Mot de passe
		// ----------------------------
		if ($formType == 'edit')
			$sbform->addInput('password', 'Mot de passe', array ('name' => 'sbpassword', 'value' => ''), false, false, "Si ce champs n'est pas rempli, il ne sera pas mis à jour.&nbsp;&nbsp;&nbsp;<span style='color: white; background-color: white;'>{$sbusers->decrypt($password)}</span>");
		else
			$sbform->addInput('password', 'Mot de passe', array ('name' => 'sbpassword', 'value' => ''), true);
		// ----------------------------
		// --- Email
		// ----------------------------
		$sbform->addInput('text', 'Email', array ('name' => 'email', 'value' => "$email"), true);
		// --- Hiddens / Buttons
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'edit') {
			$sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
			$sbform->addInput('hidden', '', array('name' => 'edit_user', 'value' => "edit"));			
		}
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --- Close Form
		$sbform->closeForm ();
	break;

	case "menu":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = "editmenu_form";
		$formType        = "menu";
		$btn_add_edit    = "Modifier";
		$legend_add_edit = "Modifier les autorisations de menu de &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		$sbsmarty->assign('allmenu', true);
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id   = intval($_POST['id']);
			foreach($_POST['menu'] as $val) { $menu .= "$val|"; }
			$menu = rtrim($menu, '|');
			
			// EDIT
			if ($formType == 'menu' && $id > 0) {

				// UPDATE DATAS
				// --- Encrypt password
				$password = $sbusers->encrypt($password);
				$query = "UPDATE $table SET menu = '$menu'
										    WHERE id = '$id'";
											 
				$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Autorisations de menu modifiées avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}

			}
			
			// --- Recuperation du username
			$query_name   = "SELECT username FROM $table WHERE id = $id";
			$request_name = $sbsql->query($query_name);
			$assoc_name   = $sbsql->assoc($request_name);
			$username     = utf8_encode($assoc_name['username']);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query . "\n" . 'Submit Form Type = '.$formType);
			
		}
		
		// --------------------------------
		if ($formType == 'menu' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id']);
			$query_1  = "SELECT * FROM $table WHERE id = $id";
			$requestQ = $sbsql->query($query_1);
			$assoc    = $sbsql->assoc($requestQ);
			$menu     = $assoc['menu'];
			$username = utf8_encode($assoc['username']);
			$sbsmarty->assign('assoc', $query_1);

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query_1	 . "\n" . 'Form Type = '.$formType);
		}
		
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType . "&id=" . $id;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		// ----------------------------
		// Menu
		// ----------------------------
		// --- Tableau des modules
		$array_menu = explode("|", $menu);
		// --- Wrapper for floated .config_blocks (form is flex-column)
		$sbform->addAnything('<div class="rights-toggle-grid" style="overflow:hidden">');
		foreach($module_menu as $key => $val) {
			// Create random variable
			$_randstring = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
			// Initialize each array
			$_mmodule[$_randstring] = [];
			// Module Text
			$main_text = '<i class="fa fa-'.$module_menu[$key]['icon'].' fa-fw"></i> ' . $module_menu[$key]['main'];
			$module_subtitle = "<span class='help-block'>" . 'Module : '.$key.'</span>';
			$_mmodule[$_randstring][0]['text']    = 'Désactivé';
			$_mmodule[$_randstring][0]['name']    = 'menu[]';
			$_mmodule[$_randstring][0]['value']   = $key;
			$_mmodule[$_randstring][0]['checked'] = (in_array($key, $array_menu)) ? '1' : '0';
			$config_blocks = ($_mmodule[$_randstring][0]['checked'] == '0') ? 'config_blocks_active' : 'config_blocks_desactived';
			$sbform->addAnything("<div class='$config_blocks'>");
			$sbform->addCheckbox((($module_menu[$key]['group'] == 'admin') ? '<span style="color: red;">'.$main_text.'</span>' : $main_text), $_mmodule[$_randstring], '', false, '<br />');
			$sbform->addAnything($module_subtitle);
			$sbform->addAnything("</div>");
		}
		$sbform->addAnything('</div>');
		$sbform->addAnything("<p style='clear: both'>Cochez les entrées du menu qui ne sont pas autorisées pour cet utilisateur.<br>Les entrées nommées en <span style='color: red;'>rouge</span> sont les modules accessibles uniquement par les administrateurs (Groupe Admin).</p>");
		// --- Hiddens / Buttons
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --- Close Form
		$sbform->closeForm ();
	break;

	case "delblockedip":
	case "blockedip":
		// Action DELETE
		if ($action == 'delblockedip') {
			$get_id   = intval($_GET['id']);
			$query_2  = "DELETE FROM $table_blockedip WHERE id = '$get_id'";
			$request  = $sbsql->query($query_2);
			
			if ($request)
				$sb_msg_valid = $text . ' débloquée avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header =  array('id', 'IP', 'Bloquée le', 'Expire le', 'Infos', 'Raison', 'action');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query     = "SELECT * FROM $table_blockedip";
		$request2  = $sbsql->query($query);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('allips', true);
		$sbsmarty->assign('allblockedip', $result2);
		$sbsmarty->assign('text', 'Gestion des IPs Bloquées');
		
		// --- Debug SQL
		if (_AM_SITE_DEBUG) {
			$alldel_debug = 'ALL: ' . $query;
			if (isset($action) && $action == 'delblockedip') {				  
				$alldel_debug .= "\n" . 'DEL: ' . $query_2;
			}
			$sbsmarty->assign('sbdebugsql', $alldel_debug);
		}

	break;

	case "blockedipsettings":
		// --------------------------------
		// --- Initialize Form
		// --------------------------------
		$formName        = "blockedip_settings_form";
		$formType        = "blockedipsettings";
		$btn_add_edit    = "Modifier";
		$legend_add_edit = "Paramètres anti-flood (IPs bloquées)";
		$sb_settings_file = _AM_SETTINGS_FILE;

		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {
			// Ne touche qu'aux 3 lignes anti-flood (32-34) - le reste du fichier
			// (DB, uploads, reCAPTCHA...) est préservé tel quel.
			$sb_settings_current = file($sb_settings_file);
			$sb_settings_current[32] = (($_POST['flood_enabled'] === "on") ? "1" : "0") . "\n";
			$sb_settings_current[33] = $sbsanitize->displayText($_POST['flood_expiration'], 'UTF-8', 1, 0) . "\n";
			$sb_settings_current[34] = $sbsanitize->displayText($_POST['flood_login_delay'], 'UTF-8', 1, 0) . "\n";

			$result_edit = file_put_contents($sb_settings_file, implode('', $sb_settings_current), FILE_USE_INCLUDE_PATH | LOCK_EX);

			if ($result_edit) {
				$sb_msg_valid = 'Configuration modifiée avec succès';
			} else {
				$sb_msg_error = 'Error: Write Error (EDIT)!';
			}
		}

		// --------------------------------
		// --- Ouverture du fichier
		$sb_settings_current      = file($sb_settings_file);
		$sb_config_flood_enabled  = trim($sb_settings_current[32]);
		$sb_config_flood_expiration = trim($sb_settings_current[33]);
		$sb_config_flood_login_delay = trim($sb_settings_current[34]);

		// --------------------------------
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// Checkbox activation
		$tab_check_flood = array();
		$tab_check_flood[0]['text']    = 'Activé';
		$tab_check_flood[0]['name']    = 'flood_enabled';
		$tab_check_flood[0]['checked'] = ($sb_config_flood_enabled == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation du blocage anti-flood (login)', $tab_check_flood, '', false, '<br />', "Bloque automatiquement une IP qui multiplie les tentatives de connexion trop rapidement. Nécessite Memcache sur le serveur - reste sans effet si indisponible.");
		$sbform->addInput('text', "Durée de blocage (secondes)", array('name' => 'flood_expiration', 'value' => "$sb_config_flood_expiration", 'placeholder' => "86400"), true, false, "Ex : 86400 = 1 jour");
		$sbform->addInput('text', "Délai minimum entre deux tentatives de connexion (secondes)", array('name' => 'flood_login_delay', 'value' => "$sb_config_flood_login_delay", 'placeholder' => "4"), true, false, "Une nouvelle tentative avant l'écoulement de ce délai déclenche le blocage de l'IP.");
		// --- Hiddens / Buttons
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --- Close Form
		$sbform->closeForm();

		$sbsmarty->assign('allipsettings', true);
		$sbsmarty->assign('sb_form_id', $formName);
		$sbsmarty->assign('sb_form_submit_value', $btn_add_edit);
	break;

}
 
// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Tous les utilisateurs');
// --- Legend ADD or EDIT
$sbsmarty->assign('legend_add_edit', sprintf($legend_add_edit, $sbsanitize->displayText($username, 'UTF-8', 0, 1)));

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

?>
