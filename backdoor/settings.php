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
// --- Nom de la session : DOIT etre pose avant session_start(), sinon ce
// --- point d'entree repose sa propre session sous PHPSESSID et perd tout
// --- ce que les autres y ont mis. Voir inc/sbsession.php.
require_once(__DIR__ . '/../inc/sbsession.php');
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

// Liste sûre des types d'upload (inc/sbuiadmin-config.php), rappelée dans l'aide
global $sbfiles_medias_exts_safe;

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
 * 19 - (ancien Google Recaptcha, clé publique - remplacé par ALTCHA)
 * 20 - (ancien Google Recaptcha, clé secrète - remplacé par ALTCHA)
 * 21 - DB prefix
 * 22 - (ancien mode captcha - remplacé par altcha_login)
 * 23 - Upgrade Mode
 * 24 - Coming soon
 * 25 - Debug General Front
 * 26 - Debug Smarty Front
 * 27 - Smarty Force Compile
 * 28 - Rewrite Url
 * 29 - Smarty Caching
 * 30 - Smarty Caching Lifetime
 * 31 - Médias par page (module Médias)
 * 32 - Anti-flood (login) activé      \
 * 33 - Anti-flood : durée de blocage   > gérées par users.php (action blockedipsettings), pas ce formulaire
 * 34 - Anti-flood : délai min. entre 2 tentatives /
 * 35 - Durée d'affichage des toasts (secondes)
 * 36 - Modules utilisant le Page Builder (liste séparée par virgules de
 *      clés "module.champ", voir sbModuleUsesPageBuilder())
 * ---------------------------- */

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

			// Réglages en base (inc/sbuiadmin-settings.php). Les accès à la
			// base ne se modifient plus ici (sbdbconfig.php) ; l'anti-flood
			// est enregistré par users.php (blockedipsettings).
			$sb_text = function ($k) use ($sbsanitize) { return $sbsanitize->displayText(isset($_POST[$k]) ? $_POST[$k] : '', 'UTF-8', 1, 0); };
			$sb_on   = function ($k) { return (isset($_POST[$k]) && $_POST[$k] === "on") ? '1' : '0'; };
			$sb_new_settings = array(
				'customer_name'         => $sb_text('customer_name'),
				'administrators'        => $sb_text('administrators'),
				'medias_dir'            => $sb_text('diruploads'),
				'upload_size_limit'     => $sb_text('upload_max'),
				'modules'               => $sb_text('modules'),
				'debug_admin'           => $sb_on('debug_general'),
				'debug_form'            => $sb_on('debug_form'),
				'debug_smarty_admin'    => $sb_on('debug_smarty'),
				'upload_exts'           => $sb_text('upload_exts'),
				'medias_url'            => $sb_text('urluploads'),
				'upload_item_limit'     => $sb_text('upload_limit'),
				'site_url'              => $sb_text('url_customer'),
				'sandbox'               => $sb_on('sandbox'),
				'cms'                   => $sb_on('cms'),
				'scaling_maxsize'       => $sb_text('scaling_maxsize'),
				'altcha_login'          => $sb_on('altcha_login'),
				'altcha_cost'           => $sb_text('altcha_cost'),
				'altcha_counter'        => $sb_text('altcha_counter'),
				'altcha_expire'         => $sb_text('altcha_expire'),
				'login_lock_enabled'    => $sb_on('login_lock_enabled'),
				'login_lock_window'     => $sb_text('login_lock_window'),
				'login_lock_duration'   => $sb_text('login_lock_duration'),
				'login_lock_max_login'  => $sb_text('login_lock_max_login'),
				'login_lock_max_ip'     => $sb_text('login_lock_max_ip'),
				'upgrade_mode'          => $sb_on('upgrade_mode'),
				'maintenance'           => $sb_on('coming_soon'),
				'debug_front'           => $sb_on('debug_general_front'),
				'debug_smarty_front'    => $sb_on('debug_smarty_front'),
				'smarty_force_compile'  => $sb_on('smarty_force_tpls'),
				'rewrite_url'           => $sb_on('rewrite_url'),
				'smarty_caching'        => $sb_on('smarty_caching'),
				'smarty_cache_lifetime' => $sb_text('smarty_caching_time'),
				'medias_per_page'       => $sb_text('medias_per_page'),
				'toast_duration'        => $sb_text('toast_duration'),
			);
			// Clés HMAC d'ALTCHA (chiffrées en base, jamais renvoyées au
			// navigateur) : champ laissé vide = valeur inchangée ; case
			// « régénérer » = deux nouvelles clés tirées au sort (les défis en
			// cours deviennent invalides).
			foreach (array('altcha_hmac_secret', 'altcha_hmac_key_secret') as $sb_altcha_key) {
				if (isset($_POST[$sb_altcha_key]) && trim((string) $_POST[$sb_altcha_key]) !== '') {
					$sb_new_settings[$sb_altcha_key] = trim((string) $_POST[$sb_altcha_key]);
				}
				if ($sb_on('altcha_regenerate') === '1') {
					$sb_new_settings[$sb_altcha_key] = bin2hex(random_bytes(32));
				}
			}
			// Le champ est desactivé (disabled) si le multilangue est actif
			// (voir plus bas, formulaire) - un champ disabled n'est jamais
			// soumis par le navigateur : on garde alors la valeur enregistrée.
			if (!sbGetConfig('multilang')) {
				// sbGetTagifyDatas() retourne false si vide
				$sb_pagebuilder_modules = sbGetTagifyDatas($_POST['pagebuilder_modules']);
				$sb_new_settings['pagebuilder_modules'] = ($sb_pagebuilder_modules !== false ? $sb_pagebuilder_modules : '');
			}

			$result_edit = sbSettingsSave($sb_new_settings);
											 
				//$result_edit = $sbsql->query($query);
				if ($result_edit) {
					// --- On ne vide pas les champs du formulaire
					// -------------------------------------------
					// --- Message SUCCES
					$sb_msg_valid = 'Configuration modifiée avec succès';
					// --- Répercute la taille max. d'upload sur les limites PHP serveur
					// (.htaccess pour mod_php, .user.ini pour PHP-FPM/CGI)
					$sb_upload_max_bytes = sbToByteSize($sbsanitize->displayText($_POST['upload_max'], 'UTF-8', 1, 0));
					if ($sb_upload_max_bytes) {
						sbSyncUploadLimits($sb_upload_max_bytes);
					}
				} else {
					// --- Message ERROR
					$sb_msg_error = 'Error: Write Error (EDIT)!';
				}
		}
		
		// --------------------------------
		// --- Réglages (base de données)
		// --- Initialisation
		$sb_config_customer_name       = sbSetting('customer_name');
		$sb_config_administrators      = sbSetting('administrators');
		$sb_config_diruploads          = sbSetting('medias_dir');
		$sb_config_upload_max          = sbSetting('upload_size_limit');
		$sb_config_modules             = sbSetting('modules');
		$sb_config_debug_general       = sbSetting('debug_admin');
		$sb_config_debug_form          = sbSetting('debug_form');
		$sb_config_debug_smarty        = sbSetting('debug_smarty_admin');
		$sb_config_upload_exts         = sbSetting('upload_exts');
		$sb_config_urluploads          = sbSetting('medias_url');
		$sb_config_upload_limit        = sbSetting('upload_item_limit');
		$sb_config_url_customer        = sbSetting('site_url');
		$sb_config_sandbox             = sbSetting('sandbox');
		$sb_config_cms                 = sbSetting('cms');
		$sb_config_scaling_maxsize     = sbSetting('scaling_maxsize');
		$sb_config_altcha_login        = sbSetting('altcha_login', '1');
		$sb_config_altcha_cost         = sbSetting('altcha_cost');
		$sb_config_altcha_counter      = sbSetting('altcha_counter');
		$sb_config_altcha_expire       = sbSetting('altcha_expire');
		$sb_config_lock_enabled        = sbSetting('login_lock_enabled', '1');
		$sb_config_lock_window         = sbSetting('login_lock_window');
		$sb_config_lock_duration       = sbSetting('login_lock_duration');
		$sb_config_lock_max_login      = sbSetting('login_lock_max_login');
		$sb_config_lock_max_ip         = sbSetting('login_lock_max_ip');
		$sb_config_upgrade_mode        = sbSetting('upgrade_mode');
		$sb_config_coming_soon         = sbSetting('maintenance');
		$sb_config_debug_general_front = sbSetting('debug_front');
		$sb_config_debug_smarty_front  = sbSetting('debug_smarty_front');
		$sb_config_smarty_force_tpls   = sbSetting('smarty_force_compile');
		$sb_config_rewrite_url         = sbSetting('rewrite_url');
		$sb_config_smarty_caching      = sbSetting('smarty_caching');
		$sb_config_smarty_caching_time = sbSetting('smarty_cache_lifetime');
		$sb_config_medias_per_page     = sbSetting('medias_per_page');
		$sb_config_toast_duration      = sbSetting('toast_duration', '7') ?: 7;
		$sb_config_pagebuilder_modules = sbSetting('pagebuilder_modules');

		// --- Debug SQL
		// (l'ancien dump de debug du fichier exposait les accès à la base)						
		// --------------------------------		
		// --- Define variables
		$formAction = $module_url . "&a=" . $formType;
		// --- Form construct
		$sbform->openForm(array('action' => "$formAction", 'name' => "$formName", 'id' => "$formName", 'reloadpage' => "$formAction", 'submitpage' => "$formAction"));
		// --- Add inputs and more
		$sbform->addInput('text', 'Nom du client', array ('name' => 'customer_name', 'value' => "$sb_config_customer_name", 'placeholder' => "Nom de votre client"), true, false, "Visible dans la barre d'administration");
		$sbform->addInput('text', 'URL du site client', array ('name' => 'url_customer', 'value' => "$sb_config_url_customer", 'placeholder' => "URL du site de votre client (http://...)"), true, false, "Ajoute un lien sur le nom de votre client (header)");
		$sbform->addBreak('Les administrateurs');
		$sbform->addInput('text', 'Administrateurs', array ('name' => 'administrators', 'value' => "$sb_config_administrators", 'placeholder' => "Nom des administrateurs"), true, false, "Login des admins séparés par des virgules sans espace");
		$sbform->addBreak('Base de données');
		// Accès base : sbdbconfig.php (ou variables d'environnement), plus
		// modifiables ni affichés ici.
		$sb_db_cfg = sbDbConfig();
		if ($sb_db_cfg['source'] === 'env') {
			$sb_db_where = "Variables d'environnement du serveur (SBUIADMIN_DB_*)";
		} elseif ($sb_db_cfg['source'] === 'legacy') {
			$sb_db_where = "<strong style='color: red;'>Encore dans settings.txt</strong> : sbdbconfig.php n'a pu être écrit";
		} elseif ($sb_db_cfg['source'] !== 'none') {
			$sb_db_where = '<code>' . htmlspecialchars($sb_db_cfg['source'], ENT_QUOTES, 'UTF-8') . '</code>'
				. (sbDbConfigIsInsideSite($sb_db_cfg['source'])
					? " <span style='color: var(--warning);'>(dans le dossier du site : protégé, mais un emplacement au-dessus du site serait plus sûr)</span>"
					: " <span style='color: var(--success);'>(hors du dossier du site)</span>");
		} else {
			$sb_db_where = "<strong style='color: red;'>Introuvable</strong>";
		}
		$sb_db_html  = "<div class='form-group'><p>Les accès à la base ne sont plus modifiables depuis l'administration. Emplacement : " . $sb_db_where . "</p>";
		$sb_db_html .= "<p>Hôte, base, utilisateur, mot de passe, préfixe : " . (($sb_db_cfg['host'] !== '' && $sb_db_cfg['name'] !== '') ? "définis ✓" : "<strong style='color: red;'>manquants</strong>") . " · Clé de chiffrement des secrets : " . (sbSecretKey() ? "définie ✓" : "<strong style='color: red;'>absente</strong>") . "</p>";
		foreach ($GLOBALS['sb_settings_warnings'] as $sb_db_warning) {
			$sb_db_html .= "<p style='color: red;'>" . htmlspecialchars($sb_db_warning, ENT_QUOTES, 'UTF-8') . "</p>";
		}
		$sb_db_html .= "</div>";
		$sbform->addAnything($sb_db_html);
		$sbform->addBreak('Configuration médias');
		$sbform->addInput('text', 'Répertoire d\'upload', array ('name' => 'diruploads', 'value' => "$sb_config_diruploads", 'placeholder' => "Répertoire de l'upload"), true, false, "ex:  <strong>../votre_repertoire</strong>  -  s'il se trouve juste en dessous de l'arborescence du répertoire d'administration<br>Chemin relatif (obligatoirement), pas d'absolu !!! - Ne pas mettre le  <span style='color: red;'>/</span>  à la fin");
		$sbform->addInput('text', 'URL d\'upload', array ('name' => 'urluploads', 'value' => "$sb_config_urluploads", 'placeholder' => "URL de l'upload (http://...)"), true, false, "Ne pas mettre le  <span style='color: red;'>/</span>  à la fin");
		$sbform->addInput('text', 'Taille max. autorisée pour l\'upload des médias (client)', array ('name' => 'upload_max', 'value' => "$sb_config_upload_max", 'placeholder' => "Répertoire de l'upload"), true, false, "Usage : 10KB, 10.5KB, 2MB, 2.5MB, 1GB, 1TB");
		// Upload exts Allowed
		$sbform->addInput('text', 'Extensions autorisées', array ('name' => 'upload_exts', 'value' => "$sb_config_upload_exts", 'placeholder' => "Extensions autorisées"), true, false, "Extensions autorisées à l'upload dans votre administration, séparées par des virgules.<br>Seules celles-ci sont acceptées : <b>" . implode(", ", $sbfiles_medias_exts_safe) . "</b> (les autres sont ignorées pour sécurité)");
		$sbform->addInput('text', "Nombre d'uploads simultanés", array ('name' => 'upload_limit', 'value' => "$sb_config_upload_limit", 'placeholder' => "Nombre d'uploads simultanés"), true, false);
		$sbform->addInput('text', "Taille maximum autorisée", array ('name' => 'scaling_maxsize', 'value' => "$sb_config_scaling_maxsize", 'placeholder' => "Taille maximum autorisée en pixels"), true, false, "Taille en pixels maximum autorisée pour vos médias (largeur et hauteur)<br>Ex : <b>1024</b> (CORRECT) --- Ex : <b>1024px</b> (INCORRECT)");
		$sbform->addInput('text', 'Médias par page', array ('name' => 'medias_per_page', 'value' => "$sb_config_medias_per_page", 'placeholder' => "Nombre de médias par page"), true, false, "Nombre d'images affichées par page dans le module Médias");
		$sbform->addBreak('Modules');
		$sbform->addInput('text', 'Modules', array ('name' => 'modules', 'value' => "$sb_config_modules", 'placeholder' => "Nom de vos modules"), false, false, "Nom de vos modules autorisés dans votre administration séparés par des virgules sans espace");
		$sbform->addBreak('Page Builder');
		$sb_pagebuilder_whitelist = sbGetPageBuilderModulesList();
		$sb_pagebuilder_selected  = array();
		foreach (explode(',', $sb_config_pagebuilder_modules) as $sb_pb_key) {
			$sb_pb_key = trim($sb_pb_key);
			if ($sb_pb_key !== '' && array_key_exists($sb_pb_key, $sb_pagebuilder_whitelist)) {
				$sb_pagebuilder_selected[] = array('value' => $sb_pb_key);
			}
		}
		// Incompatible avec le multilangue pour l'instant : seul le champ FR
		// bascule sur le Page Builder (EN reste toujours CKEditor, décision
		// pour éviter le chantier de support multi-instance du Page
		// Builder dans une même page - voir feedback_pagebuilder_debugging_lessons).
		// Champ désactivé + valeur préservée telle quelle côté sauvegarde
		// (voir plus haut) tant que le multilangue reste actif.
		$sb_multilang_enabled = sbGetConfig('multilang');
		$sb_pagebuilder_help  = "Le(s) module(s) sélectionné(s) utiliseront le Page Builder à la place de CKEditor pour leur champ de contenu principal.";
		$sb_pagebuilder_args  = array('name' => 'pagebuilder_modules', 'value' => json_encode($sb_pagebuilder_selected, JSON_UNESCAPED_UNICODE));
		if ($sb_multilang_enabled) {
			$sb_pagebuilder_args['disabled'] = 'disabled';
			$sb_pagebuilder_help .= " <strong style='color: red;'>Cette fonctionnalité ne peut pas être utilisée tant que le multilangue est activé.</strong>";
		}
		$sbform->addTagifyWhitelist('Modules utilisant le Page Builder', $sb_pagebuilder_whitelist, $sb_pagebuilder_args, false, $sb_pagebuilder_help);
		$sbform->addBreak('Anti-robot (ALTCHA)');
		// ALTCHA : preuve de travail calculée par le navigateur, sans service
		// extérieur (inc/sbuiadmin-altcha.php). Clés générées au premier usage.
		$sb_altcha_ok = sbAltchaAvailable();
		$sb_altcha_secrets_set = (sbSetting('altcha_hmac_secret') !== '' && sbSetting('altcha_hmac_key_secret') !== '');
		$sbform->addAnything("<div class='form-group'><p>ALTCHA protège la connexion (administration et module user) et tous les formulaires du module contact. Aucune donnée n'est envoyée à un service extérieur. "
			. ($sb_altcha_ok
				? "<span style='color: var(--success);'>Opérationnel ✓</span>"
				: "<strong style='color: red;'>Indisponible</strong> (PHP 8.1+, <code>vendor/altcha-org</code> et la base sont requis) : les formulaires ne sont pas protégés.")
			. "</p></div>");
		$tab_check_altcha = array();
		$tab_check_altcha[0]['text']    = 'Activé';
		$tab_check_altcha[0]['name']    = 'altcha_login';
		$tab_check_altcha[0]['checked'] = ($sb_config_altcha_login == 1) ? '1' : '0';
		$sbform->addCheckbox('ALTCHA à la connexion', $tab_check_altcha, '', false, '<br />', "Administration et module user. Le module contact l'exige toujours.");
		$sb_secret_placeholder = ($sb_altcha_secrets_set ? "•••••••• enregistrée (vide = inchangée)" : "générée automatiquement au premier usage");
		$sbform->addInput('password', 'Clé HMAC (signature des défis)', array ('name' => 'altcha_hmac_secret', 'value' => '', 'placeholder' => $sb_secret_placeholder, 'autocomplete' => 'new-password'), false, false, "Secrète, chiffrée en base. Laisser vide pour la conserver.");
		$sbform->addInput('password', 'Clé HMAC (signature des solutions)', array ('name' => 'altcha_hmac_key_secret', 'value' => '', 'placeholder' => $sb_secret_placeholder, 'autocomplete' => 'new-password'), false, false, "Secrète, chiffrée en base. Permet de vérifier une réponse sans refaire le calcul.");
		$tab_check_altcha_regen = array();
		$tab_check_altcha_regen[0]['text']    = 'Régénérer les deux clés';
		$tab_check_altcha_regen[0]['name']    = 'altcha_regenerate';
		$tab_check_altcha_regen[0]['checked'] = '0';
		$sbform->addCheckbox('Nouvelles clés', $tab_check_altcha_regen, '', false, '<br />', "Tire au sort deux nouvelles clés. Les vérifications en cours dans les navigateurs devront être refaites.");
		$sbform->addInput('text', 'Coût (itérations PBKDF2 par essai)', array ('name' => 'altcha_cost', 'value' => "$sb_config_altcha_cost", 'placeholder' => "2000"), false, false, "Défaut : 2000 (de 100 à 100000)");
		$sbform->addInput('text', 'Difficulté (nombre maximum d\'essais)', array ('name' => 'altcha_counter', 'value' => "$sb_config_altcha_counter", 'placeholder' => "5000"), false, false, "Défaut : 5000, environ 1 seconde sur un ordinateur (de 10 à 1000000). Le navigateur fait en moyenne les trois quarts de ce nombre d'essais : plus la valeur est haute, plus la vérification est longue pour un visiteur (et coûteuse pour un robot).");
		$sbform->addInput('text', 'Validité d\'un défi (secondes)', array ('name' => 'altcha_expire', 'value' => "$sb_config_altcha_expire", 'placeholder' => "600"), false, false, "Défaut : 600 (10 minutes, de 60 à 86400). Chaque défi n'est accepté qu'une fois.");
		$sbform->addBreak('Blocage des tentatives de connexion');
		$tab_check_lock = array();
		$tab_check_lock[0]['text']    = 'Activé';
		$tab_check_lock[0]['name']    = 'login_lock_enabled';
		$tab_check_lock[0]['checked'] = ($sb_config_lock_enabled == 1) ? '1' : '0';
		$sbform->addCheckbox('Blocage temporaire', $tab_check_lock, '', false, '<br />', "Trop d'échecs (mot de passe ou code de double authentification) : la connexion est refusée sans vérifier le mot de passe. Administration et module user. Complète l'anti-flood (Utilisateurs &gt; IP bloquées), qui limite seulement la cadence et dépend de Memcache.");
		$sbform->addInput('text', 'Fenêtre de comptage (minutes)', array ('name' => 'login_lock_window', 'value' => "$sb_config_lock_window", 'placeholder' => "15"), false, false, "Défaut : 15. Les échecs sont comptés sur cette durée.");
		$sbform->addInput('text', 'Durée du blocage (minutes)', array ('name' => 'login_lock_duration', 'value' => "$sb_config_lock_duration", 'placeholder' => "15"), false, false, "Défaut : 15, à partir du dernier échec qui a atteint le seuil.");
		$sbform->addInput('text', 'Échecs maximum par identifiant', array ('name' => 'login_lock_max_login', 'value' => "$sb_config_lock_max_login", 'placeholder' => "10"), false, false, "Défaut : 10.");
		$sbform->addInput('text', 'Échecs maximum par adresse IP', array ('name' => 'login_lock_max_ip', 'value' => "$sb_config_lock_max_ip", 'placeholder' => "20"), false, false, "Défaut : 20 (tous identifiants confondus).");
		$sbform->addBreak('Debug');
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
		$sbform->addBreak('Fonctionnalités');
		$sbform->addInput('text', "Durée d'affichage des toasts (secondes)", array ('name' => 'toast_duration', 'value' => "$sb_config_toast_duration", 'placeholder' => "7"), true, false, "Notifications flottantes (haut droite) après une action réussie ou en erreur.");
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
		$sbform->addBreak('Performance (Smarty)');
		// Smarty force compile tpls
		$tab_check_9 = array();
		$tab_check_9[0]['text']    = 'Smarty Force Compile (FRONT)';
		$tab_check_9[0]['name']    = 'smarty_force_tpls';
		$tab_check_9[0]['checked'] = ($sb_config_smarty_force_tpls == 1) ? '1' : '0';
		$sbform->addCheckbox('Activation de la compilation des templates Smarty', $tab_check_9, '', false, '<br />', "Désactiver ce mode quand le site est en production !");
		// Smarty cache
		$tab_check_10[0]['text']    = 'Smarty Cache Templates (FRONT)';
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
		$sbform->openSelect("Durée du cache Smarty (FRONT)", array("id"=>"smarty_caching_time", "name"=>"smarty_caching_time"));
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
$sbsmarty->assign('sb_config_altcha_login', trim($sb_config_altcha_login));
$sbsmarty->assign('sb_config_lock_enabled', trim($sb_config_lock_enabled));
$sbsmarty->assign('sb_config_upgrade_mode', trim($sb_config_upgrade_mode));
$sbsmarty->assign('sb_config_coming_soon', trim($sb_config_coming_soon));
$sbsmarty->assign('sb_config_debug_general_front', trim($sb_config_debug_general_front));
$sbsmarty->assign('sb_config_debug_smarty_front', trim($sb_config_debug_smarty_front));
$sbsmarty->assign('sb_config_smarty_force_tpls', trim($sb_config_smarty_force_tpls));
$sbsmarty->assign('sb_config_rewrite_url', trim($sb_config_rewrite_url));
$sbsmarty->assign('sb_config_smarty_caching', trim($sb_config_smarty_caching));
$sbsmarty->assign('sb_config_smarty_caching_time', trim($sb_config_smarty_caching_time));
$sbsmarty->assign('sb_config_medias_per_page', trim($sb_config_medias_per_page));
$sbsmarty->assign('sb_config_toast_duration', trim($sb_config_toast_duration));

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
