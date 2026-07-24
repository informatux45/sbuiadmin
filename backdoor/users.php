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

				// Libre-service : sans "modifier" sur users, chacun peut quand
				// même changer SON PROPRE mot de passe (voir sbClassifyAction()) -
				// mais uniquement celui-ci, le reste de la fiche est ignoré même
				// si le POST contenait d'autres valeurs (formulaire déjà réduit
				// au mot de passe côté rendu, voir plus bas - ceci est le
				// garde-fou côté serveur).
				$self_password_only = ($id == sbGetCurrentUserId() && !sbHasRight('users', 'edit'));

				if ($self_password_only) {
					if ($password != '') {
						$password = $sbusers->encrypt($password);
						$query = "UPDATE $table SET password = '$password' WHERE id = '$id'";
						$result_edit = $sbsql->query($query);
						if ($result_edit) {
							$sb_msg_valid = 'Mot de passe modifié avec succès';
						} else {
							$sb_msg_error = 'Error: Write Error (EDIT)!';
						}
					} else {
						$sb_msg_error = 'Veuillez saisir un mot de passe';
					}
				} else {
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
		// Libre-service : sans "modifier" sur users, on ne modifie QUE sa propre
		// fiche - et seul le mot de passe est proposé (voir sbClassifyAction() +
		// le garde-fou serveur équivalent plus haut dans ce case).
		$self_password_only = ($formType == 'edit' && $id > 0 && $id == sbGetCurrentUserId() && !sbHasRight('users', 'edit'));
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		if ($self_password_only) {
			$sbform->addAnything('<p>Vous ne pouvez modifier que votre mot de passe. Contactez un administrateur pour le reste de votre fiche.</p>');
		} else {
			// ----------------------------
			// --- Actif / Desactive
			// ----------------------------
			$active = ($active) ? '1' : '0';
			$sbform->addRadioYN('Actif', true, array('id'=>'active', 'name'=>'active', 'checked'=>"$active"), 'activé', 'désactivé');
			// ----------------------------
			// --- Nom de l'utilisateur (login)
			// ----------------------------
			$sbform->addInput('text', 'Utilisateur', array ('name' => 'sbusername', 'value' => "$username"), true);
		}
		// ----------------------------
		// --- Mot de passe
		// ----------------------------
		if ($formType == 'edit')
			$sbform->addInput('password', 'Mot de passe', array ('name' => 'sbpassword', 'value' => ''), $self_password_only, false, "Si ce champs n'est pas rempli, il ne sera pas mis à jour.&nbsp;&nbsp;&nbsp;<span style='color: white; background-color: white;'>{$sbusers->decrypt($password)}</span>");
		else
			$sbform->addInput('password', 'Mot de passe', array ('name' => 'sbpassword', 'value' => ''), true);
		// ----------------------------
		// --- Email
		// ----------------------------
		if (!$self_password_only) {
			$sbform->addInput('text', 'Email', array ('name' => 'email', 'value' => "$email"), true);
		}
		// --- Hiddens / Buttons
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'edit') {
			$sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
			$sbform->addInput('hidden', '', array('name' => 'edit_user', 'value' => "edit"));
		}
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --- Close Form
		$sbform->closeForm ();
		// --- Bouton "Actions" de la colonne droite (shared-panel-actions.tpl)
		$sbsmarty->assign('sb_form_id', $formName);
		$sbsmarty->assign('sb_form_submit_value', $btn_add_edit);
	break;

	case "menu":
		// --------------------------------
		// Initialize Form
		// --------------------------------
		$formName        = "editmenu_form";
		$formType        = "menu";
		$btn_add_edit    = "Modifier";
		$legend_add_edit = "Droits d'accès de &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if ($_POST['form_submit']) {

			// Injection des données
			$id = intval($_POST['id']);

			// EDIT (droits granulaires par module, voir inc/sbuiadmin-rights.php)
			if ($formType == 'menu' && $id > 0) {

				$result_edit = sbSaveRightsMatrix($id, $_POST);
				if ($result_edit) {
					// --- Message SUCCES
					$sb_msg_valid = 'Droits d\'accès modifiés avec succès';
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
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', 'sbSaveRightsMatrix(' . $id . ', $_POST)' . "\n" . 'Submit Form Type = '.$formType);

		}
		
		// --------------------------------
		if ($formType == 'menu' && !$_POST['form_submit']) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id']);
			$query_1  = "SELECT * FROM $table WHERE id = $id";
			$requestQ = $sbsql->query($query_1);
			$assoc    = $sbsql->assoc($requestQ);
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
		// Droits d'accès (voir/ajouter/modifier/supprimer par module),
		// groupés comme le menu principal (Configuration / Modules)
		// ----------------------------
		$rights_matrix = sbGetRightsMatrix($id);
		$rights_groups = sbGetRightsGroups();
		foreach ($rights_groups as $section_title => $section_modules) {
			if (empty($section_modules)) continue;
			$sbform->addAnything('<h3 style="margin:20px 0 8px">' . $section_title . '</h3>');
			$sbform->addAnything('<div style="overflow-x:auto"><table class="data-table"><thead><tr>');
			$sbform->addAnything('<th>Module</th><th>Voir</th><th>Ajouter</th><th>Modifier</th><th>Supprimer</th>');
			$sbform->addAnything('</tr></thead><tbody>');
			foreach ($section_modules as $key => $label) {
				$rights      = $rights_matrix[$key];
				$view_checked = ($rights['can_view'] == 1);
				$sbform->addAnything('<tr class="data-row" data-rights-row><td>' . $rights['label'] . '</td>');
				foreach (array('view', 'add', 'edit', 'delete') as $right) {
					$checked  = ($rights['can_' . $right] == 1) ? ' checked' : '';
					// "Ajouter/Modifier/Supprimer" n'ont aucun effet sans "Voir" (sbCheckRightRow
					// bloque tout dès que can_view=0) - grisées tant que "Voir" est décochée
					// (toggle JS dans users.tpl, valeur préservée à la soumission).
					$disabled = ($right != 'view' && !$view_checked) ? ' disabled' : '';
					$sbform->addAnything('<td><label class="check"><input type="checkbox" name="rights[' . $key . '][' . $right . ']" value="1" data-right="' . $right . '"' . $checked . $disabled . '><span class="box"></span></label></td>');
				}
				$sbform->addAnything('</tr>');
			}
			$sbform->addAnything('</tbody></table></div>');
		}
		// --- Hiddens / Buttons
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		$sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
		$sbform->addInput('reset', '', array('value' => "Reset"));
		// --- Close Form
		$sbform->closeForm ();
		// --- Bouton "Actions" de la colonne droite (shared-panel-actions.tpl)
		$sbsmarty->assign('sb_form_id', $formName);
		$sbsmarty->assign('sb_form_submit_value', $btn_add_edit);
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
