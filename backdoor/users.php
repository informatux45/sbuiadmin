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
//defined('SBMAGIC_PATH') or die('Are you crazy!');

// -----------------------
// Module URL
// -----------------------
$module_page = 'users';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBMAGIC_URL . SBMAGIC_BASE . '?p=' . $module_page;
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

$table       = _AM_DB_PREFIX . "sb_users";
$text        = "Utilisateur";

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
		$sb_table_header =  array('Gravatar', 'Nom', 'Email', 'Dernière connexion', 'action');
		$sbsmarty->assign('sb_table_header', $sb_table_header);
		
		// Contents table
		$query[0] = "SELECT * FROM $table";
		$request2  = $sbsql->query($query[0]);
		$result2   = $sbsql->toarray($request2);
		
		$sbsmarty->assign('all', true);
		$sbsmarty->assign('alluser', $result2);
		
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
			$query[1] = "SELECT * FROM $table WHERE id = $id";
			$requestQ = $sbsql->query($query[1]);
			$assoc    = $sbsql->assoc($requestQ);
			$username = utf8_encode($assoc['username']);
			$password = utf8_encode($assoc['password']);
			$email    = utf8_encode($assoc['email']);
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
			$query[1] = "SELECT * FROM $table WHERE id = $id";
			$requestQ = $sbsql->query($query[1]);
			$assoc    = $sbsql->assoc($requestQ);
			$menu     = $assoc['menu'];
			$username = utf8_encode($assoc['username']);
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
		// Menu
		// ----------------------------
		// --- Tableau des modules
		$array_menu = explode("|", $menu);
		foreach($module_menu as $key => $val) {
			// Create random variable
			$_randstring = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
			// Initialize each array
			$_{$_randstring} = [];
			// Module Text
			$main_text = '<i class="fa fa-'.$module_menu[$key]['icon'].' fa-fw"></i> ' . $module_menu[$key]['main'] . '<br>' . "&nbsp;&nbsp;&nbsp;&nbsp;<span class='help-block'>" . 'Module : '.$key.'</span>';
			$_{$_randstring}[0]['text']    = 'Désactivé';
			$_{$_randstring}[0]['name']    = 'menu[]';
			$_{$_randstring}[0]['value']   = $key;
			$_{$_randstring}[0]['checked'] = (in_array($key, $array_menu)) ? '1' : '0';
			$config_blocks = ($_{$_randstring}[0]['checked'] == '0') ? 'config_blocks_active' : 'config_blocks_desactived';
			$sbform->addAnything("<div class='$config_blocks'");
			$sbform->addCheckbox((($module_menu[$key]['group'] == 'admin') ? '<span style="color: red;">'.$main_text.'</span>' : $main_text), $_{$_randstring}, '', false, '<br />');
			$sbform->addAnything("</div>");
		}
		$sbform->addAnything("<p style='clear: both'>Cochez les entrées du menu qui ne sont pas autorisées pour cet utilisateur.<br>Les entrées nommées en <span style='color: red;'>rouge</span> sont les modules accessibles uniquement par les administrateurs (Groupe Admin).</p>");
		// --- Hiddens / Buttons
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --- Close Form
		$sbform->closeForm ();
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