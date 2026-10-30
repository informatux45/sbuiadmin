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
defined('SBUIADMIN_PATH') or die('Are you crazy!');

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
$sbdb  = sbDb();
$text  = "Utilisateur";
$sbsmarty->assign('sb_form_submit_value', 'Modifier');

$action = $_GET['a'] ?? '';
// PHP 8 : variables lues par certaines branches seulement
$avatar = $centres_interet = $fonction = $id = $infos_complementaires = $nom = $prenom = $profession = $query = $query_2 = $telephone = $username = '';
// Compte modifié ou supprimé : l'alerte « admin/admin » (index.php) sera
// recalculée au prochain affichage du tableau de bord
if (!empty($_POST) || $action == 'del') unset($_SESSION['sb_warning_admin_default']);
switch($action) {
	case "del":
	default:
		// Action DELETE
		if ($action == 'del') {
			$get_id   = intval($_GET['id'] ?? 0);
			$query_2  = "DELETE FROM $table WHERE id = $get_id";
			$request  = $sbdb->delete('sb_users', 'id = ?', array($get_id)) !== false;
			
			if ($request)
				$sb_msg_valid = $text . ' supprimé avec succès';
			else
				$sb_msg_error = 'Error: Write Error (DEL)!';
		}

		// Initialisation
		$sb_table_header =  array('Avatar', 'Groupe', 'Nom', 'Email', 'Dernière connexion', 'action');
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
		$formType        = ($action == 'add' || ($_POST['form_submit'] ?? '') == 'add_form') ? "add" : "edit";
		$btn_add_edit    = ($action == 'add') ? "Ajouter" : "Modifier";
		$legend_add_edit = ($action == 'add') ? "Ajouter un " . strtolower($text) : "Modifier &laquo;&nbsp;<span style='color: red;'>%s</span>&nbsp;&raquo;";
		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if (!empty($_POST['form_submit'])) {

			// Injection des données
			$id       = intval($_POST['id'] ?? 0);
			$username = $sbsanitize->displayText($_POST['sbusername'] ?? '', 'UTF-8', 1, 0);
			// Valeur BRUTE : seul son hash est stocké. displayText() (entités
			// HTML) donnait un hash de "&amp;" quand login() vérifie "&" -
			// tout mot de passe avec & < > ' " ou accent ne marchait jamais.
			$password = isset($_POST['sbpassword']) ? (string)$_POST['sbpassword'] : '';
			$email    = $sbsanitize->displayText($_POST['email'] ?? '', 'UTF-8', 1, 0);
			$active   = $sbsanitize->displayText($_POST['active'] ?? '', 'UTF-8', 1, 0);

			// Libre-service : sans "modifier" sur users, chacun peut quand même
			// changer SON PROPRE mot de passe (voir sbClassifyAction()) - mais
			// uniquement celui-ci, le reste de la fiche (y compris les nouveaux
			// champs profil ci-dessous) est ignoré même si le POST contenait
			// d'autres valeurs (formulaire déjà réduit au mot de passe côté
			// rendu, voir plus bas - ceci est le garde-fou côté serveur).
			$self_password_only = ($formType == 'edit' && $id == sbGetCurrentUserId() && !sbHasRight('users', 'edit'));

			// Champs profil (Point 12) - non lus/utilisés en libre-service mot
			// de passe seul. Avatar : même mécanisme "photo" que Slider/News/
			// Pages (picker de la Médiathèque, sous-dossier avatars) - $avatar
			// n'est qu'un nom de fichier choisi/déjà présent dans ce dossier,
			// pas un upload natif géré ici.
			if (!$self_password_only) {
				$prenom                = $sbsanitize->displayText($_POST['prenom'] ?? '', 'UTF-8', 1, 0);
				$nom                   = $sbsanitize->displayText($_POST['nom'] ?? '', 'UTF-8', 1, 0);
				$telephone             = $sbsanitize->displayText($_POST['telephone'] ?? '', 'UTF-8', 1, 0);
				$fonction              = $sbsanitize->displayText($_POST['fonction'] ?? '', 'UTF-8', 1, 0);
				$profession            = $sbsanitize->displayText($_POST['profession'] ?? '', 'UTF-8', 1, 0);
				$centres_interet       = $sbsanitize->displayText($_POST['centres_interet'] ?? '', 'UTF-8', 1, 0);
				$infos_complementaires = $sbsanitize->displayText($_POST['infos_complementaires'] ?? '', 'UTF-8', 1, 0);
				$avatar                = $sbsanitize->displayText($_POST['avatar'] ?? '', 'UTF-8', 1, 0);
			}

			// ADD or EDIT
			if ($formType == 'add') {
				// INSERT DATAS
				// --- Mot de passe haché (password_hash()), jamais chiffré
				$result_add = $sbdb->insert('sb_users', array(
					'username' => $username, 'password' => $sbusers->hashPassword($password), 'email' => $email, 'active' => $active,
					'logintime' => '0', 'lastlogin' => '0', 'menu' => ' ', 'groupe' => 'admin',
					'prenom' => $prenom, 'nom' => $nom, 'telephone' => $telephone, 'fonction' => $fonction, 'profession' => $profession,
					'centres_interet' => $centres_interet, 'infos_complementaires' => $infos_complementaires, 'avatar' => $avatar,
				)) !== false;
				$query = $sbdb->lastQuery()[0];
				if ($result_add) {
					// --- Vider les champs du formulaire
					$username = $password = $email = $active = '';
					$prenom = $nom = $telephone = $fonction = $profession = $centres_interet = $infos_complementaires = $avatar = '';
					// --- Message SUCCESS
					$sb_msg_valid = $text . ' ajouté avec succès';
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (ADD)!';
				}

			} elseif ($formType == 'edit' && $id > 0) {

				// Calculé AVANT l'UPDATE : après, le hash en session ne
				// correspond plus et sbGetCurrentUserId() renverrait 0.
				$sb_editing_self = ($id == sbGetCurrentUserId());
				$sb_new_hash     = ($password != '') ? $sbusers->hashPassword($password) : '';

				if ($self_password_only) {
					if ($password != '') {
						$result_edit = $sbdb->update('sb_users', array('password' => $sb_new_hash), 'id = ?', array($id)) !== false;
						$query = $sbdb->lastQuery()[0];
						if ($result_edit) {
							// Mot de passe changé : jetons "Se souvenir de moi"
							// révoqués, et toutes les AUTRES sessions de ce compte
							// tombent (leur hash ne correspond plus) - seule celle-ci
							// reçoit le nouveau hash.
							$sbusers->revokeRememberTokens($id);
							$_SESSION['sbuiadmin_user_password'] = $sb_new_hash;
							$sb_msg_valid = 'Mot de passe modifié avec succès';
						} else {
							$sb_msg_error = 'Error: Write Error (EDIT)!';
						}
					} else {
						$sb_msg_error = 'Veuillez saisir un mot de passe';
					}
				} else {
					// UPDATE DATAS (mot de passe seulement s'il est saisi)
					$sb_fields = array(
						'username' => $username, 'email' => $email, 'active' => $active,
						'prenom' => $prenom, 'nom' => $nom, 'telephone' => $telephone, 'fonction' => $fonction, 'profession' => $profession,
						'centres_interet' => $centres_interet, 'infos_complementaires' => $infos_complementaires, 'avatar' => $avatar,
					);
					if ($password != '') $sb_fields['password'] = $sb_new_hash;
					$result_edit = $sbdb->update('sb_users', $sb_fields, 'id = ?', array($id)) !== false;
					$query = $sbdb->lastQuery()[0];
					if ($result_edit && $sb_new_hash != '') {
						// Mot de passe changé : jetons "Se souvenir de moi"
						// révoqués, et toutes les sessions de ce compte tombent
						// (leur hash ne correspond plus) - sauf la mienne si je
						// modifie mon propre compte.
						$sbusers->revokeRememberTokens($id);
						if ($sb_editing_self) $_SESSION['sbuiadmin_user_password'] = $sb_new_hash;
					}
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
			$prenom = $nom = $telephone = $fonction = $profession = $centres_interet = $infos_complementaires = $avatar = '';
		}
		// --------------------------------
		if ($formType == 'edit' && empty($_POST['form_submit'])) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id'] ?? 0);
			$query_1  = "SELECT * FROM $table WHERE id = $id";
			$assoc    = $sbdb->row("SELECT * FROM {sb_users} WHERE id = ?", array($id)) ?: array();
			$username               = sb_utf8_encode($assoc['username']);
			$password               = sb_utf8_encode($assoc['password']);
			$email                  = sb_utf8_encode($assoc['email']);
			$active                 = $assoc['active'];
			$prenom                 = sb_utf8_encode($assoc['prenom']);
			$nom                    = sb_utf8_encode($assoc['nom']);
			$telephone              = sb_utf8_encode($assoc['telephone']);
			$fonction               = sb_utf8_encode($assoc['fonction']);
			$profession             = sb_utf8_encode($assoc['profession']);
			$centres_interet        = sb_utf8_encode($assoc['centres_interet']);
			$infos_complementaires  = sb_utf8_encode($assoc['infos_complementaires']);
			$avatar                 = $assoc['avatar'];
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
			$sbform->addInput('password', 'Mot de passe', array ('name' => 'sbpassword', 'value' => ''), $self_password_only, false, "Si ce champs n'est pas rempli, il ne sera pas mis à jour.");
		else
			$sbform->addInput('password', 'Mot de passe', array ('name' => 'sbpassword', 'value' => ''), true);
		// ----------------------------
		// --- Email
		// ----------------------------
		if (!$self_password_only) {
			$sbform->addInput('text', 'Email', array ('name' => 'email', 'value' => "$email"), true);
		}
		// ----------------------------
		// --- Champs profil (Point 12)
		// ----------------------------
		if (!$self_password_only) {
			$sbform->addInput('text', 'Prénom', array('name' => 'prenom', 'value' => "$prenom"));
			$sbform->addInput('text', 'Nom', array('name' => 'nom', 'value' => "$nom"));
			$sbform->addInput('text', 'Téléphone', array('name' => 'telephone', 'value' => "$telephone"));
			$sbform->addInput('text', 'Fonction / poste', array('name' => 'fonction', 'value' => "$fonction"));
			$sbform->addInput('text', 'Profession', array('name' => 'profession', 'value' => "$profession"));
			$sbform->addTextarea('Centres d\'intérêt', $centres_interet, array('id' => 'centres_interet', 'name' => 'centres_interet', 'style' => 'height: 90px !important;'));
			$sbform->addTextarea('Infos complémentaires', $infos_complementaires, array('id' => 'infos_complementaires', 'name' => 'infos_complementaires', 'style' => 'height: 90px !important;'));
			// --- Avatar : même picker "photo" que Slider/News/Pages (Médiathèque,
			// sous-dossier "avatars") - repli sur Gravatar si vide, voir sbGetUserAvatar()
			$sbform->addInput('text', 'Photo de profil', array('id' => 'inputAvatar', 'name' => 'avatar', 'value' => "$avatar", 'placeholder' => 'Photo de profil', 'medias' => '', 'icon' => 'photo', 'dir' => _AM_AVATARS_URL, 'subdir' => 'avatars', 'style' => 'width: 100% !important'), false, false, 'Gravatar (associé à l\'email) utilisé par défaut si aucune photo n\'est choisie.');
		}
		// --- Hiddens / Buttons
		$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => "$formName"));
		if ($formType == 'edit') {
			$sbform->addInput('hidden', '', array('name' => 'id', 'value' => "$id"));
			$sbform->addInput('hidden', '', array('name' => 'edit_user', 'value' => "edit"));
		}
		// Le formulaire est maintenant assez long pour que le bouton du
		// panneau "Actions" (fixe, en haut à droite) sorte du champ de
		// vision une fois qu'on a défilé jusqu'en bas - un vrai bouton de
		// soumission à côté de "Reset" évite d'avoir à remonter.
		$sbform->addInput('submit', '', array('value' => "$btn_add_edit"));
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
		if (!empty($_POST['form_submit'])) {

			// Injection des données
			$id = intval($_POST['id'] ?? 0);

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
			$username     = sb_utf8_encode((string) $sbdb->value("SELECT username FROM {sb_users} WHERE id = ?", array($id)));

			// --- Debug SQL
			if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', 'sbSaveRightsMatrix(' . $id . ', $_POST)' . "\n" . 'Submit Form Type = '.$formType);

		}
		
		// --------------------------------
		if ($formType == 'menu' && empty($_POST['form_submit'])) {
			// --- Recuperation des donnees
			$id       = intval($_GET['id'] ?? 0);
			$query_1  = "SELECT * FROM $table WHERE id = $id";
			$username = sb_utf8_encode((string) $sbdb->value("SELECT username FROM {sb_users} WHERE id = ?", array($id)));
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
			$get_id   = intval($_GET['id'] ?? 0);
			$query_2  = "DELETE FROM $table_blockedip WHERE id = $get_id";
			$request  = $sbdb->delete('sb_blocked_ip', 'id = ?', array($get_id)) !== false;
			
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

		// --------------------------------
		// --- Control form submit --------
		// --------------------------------
		if (!empty($_POST['form_submit'])) {
			// Proxy ou CDN devant le site (inc/sbuiadmin-clientip.php)
			$sb_proxy_mode = (string) ($_POST['proxy_mode'] ?? 'none');
			if (!in_array($sb_proxy_mode, array('none', 'cloudflare', 'custom'), true)) $sb_proxy_mode = 'none';
			list($sb_proxy_ranges, $sb_proxy_bad) = sbClientIpParseTrusted(is_string($_POST['proxy_trusted'] ?? null) ? $_POST['proxy_trusted'] : '');
			if ($sb_proxy_bad) {
				$sb_msg_error = 'Proxys de confiance : adresse ou plage invalide (' . htmlspecialchars(implode(', ', array_slice($sb_proxy_bad, 0, 5))) . '). Rien n\'a été enregistré.';
			} elseif ($sb_proxy_mode === 'custom' && !$sb_proxy_ranges) {
				$sb_msg_error = 'Proxys de confiance : indiquez au moins une adresse ou une plage pour « Autre proxy ». Rien n\'a été enregistré.';
			} else {
				// Réglages en base (inc/sbuiadmin-settings.php)
				$result_edit = sbSettingsSave(array(
					'flood_enabled'     => (isset($_POST['flood_enabled']) && $_POST['flood_enabled'] === "on") ? '1' : '0',
					'flood_expiration'  => $sbsanitize->displayText($_POST['flood_expiration'] ?? '', 'UTF-8', 1, 0),
					'flood_login_delay' => $sbsanitize->displayText($_POST['flood_login_delay'] ?? '', 'UTF-8', 1, 0),
					'proxy_mode'        => $sb_proxy_mode,
					'proxy_trusted'     => implode("\n", $sb_proxy_ranges),
				));

				if ($result_edit) {
					$sb_msg_valid = 'Configuration modifiée avec succès';
				} else {
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}
			}
		}

		// --------------------------------
		// --- Réglages (base de données)
		$sb_config_flood_enabled     = sbSetting('flood_enabled');
		$sb_config_flood_expiration  = sbSetting('flood_expiration');
		$sb_config_flood_login_delay = sbSetting('flood_login_delay');
		$sb_config_proxy_mode        = sbClientIpMode();
		$sb_config_proxy_trusted     = sbSetting('proxy_trusted');

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
		// Proxy / CDN : adresse vue par le serveur et en-têtes reçus, pour aider au choix
		$sb_proxy_seen = sbClientIpDetectProxy($_SERVER);
		$sb_proxy_help = "Adresse de connexion vue par le serveur : " . htmlspecialchars((string) ($_SERVER['REMOTE_ADDR'] ?? '?')) . " ; adresse retenue pour vous : " . htmlspecialchars(sbClientIp()) . ".";
		if ($sb_proxy_seen === 'cloudflare') $sb_proxy_help .= " <strong>Cloudflare détecté devant le site : choisissez « Cloudflare ».</strong>";
		elseif ($sb_proxy_seen === 'proxy') $sb_proxy_help .= " Un en-tête X-Forwarded-For est reçu : si votre hébergeur place un proxy devant le site, choisissez « Autre proxy » et indiquez son adresse.";
		$sb_proxy_help .= " Sans proxy déclaré, seule l'adresse de connexion est utilisée (blocage d'IP, verrouillage de la connexion, journaux) : les en-têtes transmis par un proxy se falsifient.";
		$sbform->addRadio('Proxy ou CDN devant le site', array(
			array('text' => 'Aucun', 'value' => 'none'),
			array('text' => 'Cloudflare', 'value' => 'cloudflare'),
			array('text' => 'Autre proxy', 'value' => 'custom'),
		), array('name' => 'proxy_mode', 'checked' => $sb_config_proxy_mode), false, '&nbsp;', $sb_proxy_help);
		$sbform->addTextarea('Proxys de confiance', htmlspecialchars($sb_config_proxy_trusted), array('name' => 'proxy_trusted', 'rows' => '4', 'placeholder' => "10.0.0.5\n192.168.1.0/24"), false, "Une adresse IPv4/IPv6 ou une plage CIDR par ligne. « Autre proxy » : obligatoire, X-Forwarded-For lu seulement si la connexion vient de ces adresses. « Cloudflare » : ajoutées aux plages publiées par Cloudflare (déjà intégrées).");
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
$sbsmarty->assign('legend_add_edit', sprintf((string) ($legend_add_edit ?? ''), $sbsanitize->displayText($username, 'UTF-8', 0, 1)));

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

?>
