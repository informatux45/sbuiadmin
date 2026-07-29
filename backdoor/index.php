<?php
/**
 * Admin Startbootstrap
 * Main file (engine)
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */
 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date dans le passé
 
// ----------------------
// Session Initialization
// This sends a persistent cookie that lasts a day
// ----------------------
// Point 1 (audit sécurité) : cookie_secure calculé (pas codé en dur à 1) -
// sbconfig.php n'est pas encore chargé ici, même détection locale que sur
// index.php racine.
$sb_is_https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_start([
    'cookie_lifetime' => 86400,
    'cookie_secure'   => $sb_is_https,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

// Point 1 (audit sécurité) : les cookies "Se souvenir de moi" utilisaient
// l'ancienne syntaxe setcookie(nom, valeur, expiration, chemin) - aucun
// des flags secure/httponly/samesite (portée uniquement par la syntaxe
// tableau, indépendante de ceux passés à session_start() ci-dessus) n'y
// était donc appliqué. Repris ici avec les mêmes flags que la session.
function sbSetAuthCookie($name, $value, $expire) {
    global $sb_is_https;
    setcookie($name, $value, [
        'expires'  => $expire,
        'path'     => '/',
        'secure'   => $sb_is_https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}
 
// ----------------------
// Global defined
// ----------------------
defined('SBUIADMIN_PATH') or define('SBUIADMIN_PATH', dirname(__FILE__));
$PORT = (!in_array($_SERVER['SERVER_PORT'], [80, 443])) ? ":$_SERVER[SERVER_PORT]" : '';
defined('SBUIADMIN_URL') or define('SBUIADMIN_URL', $_SERVER['SERVER_NAME'].(isset($_SERVER['SERVER_PORT']) ? ":".$_SERVER['SERVER_PORT'] : "").dirname($_SERVER["REQUEST_URI"].'?').'/');
defined('SBUIADMIN_BASE') or define('SBUIADMIN_BASE', basename(__FILE__));
defined('SBUIADMIN_NAME') or define('SBUIADMIN_NAME', 'SBuiadmin');
defined('SBUIADMIN_ID') or define('SBUIADMIN_ID', 'sbuiadmin');
// ----------------------

// ----------------------
// Global include
// ----------------------
include 'inc/' . SBUIADMIN_ID . '-header.php';
// ----------------------

// ----------------------
// Define Globals
// ----------------------
global $sbdebug, $sbsmarty, $sbsql, $sbsanitize, $sbusers, $sbform, $sbpage, $sbmedias;
// ----------------------

// ----------------------
// Check INSTALLATION
// ----------------------
$sbuiadmin_install_dir  = SBUIADMIN_PATH . '/install.php';
$sbuiadmin_install_url  = SBUIADMIN_URL . 'install.php';
//$sbuiadmin_install_lock = SBUIADMIN_PATH . '/install/installer/data/';
$sbuiadmin_install_lock = SBUIADMIN_PATH . '/install/';
$sbuiadmin_htaccess     = SBUIADMIN_PATH . '/htaccess';
$sbuiadmin_dot_htaccess = SBUIADMIN_PATH . '/.htaccess';
// ----------------------
if (file_exists($sbuiadmin_install_dir)) {
	// --- Check if install is done or not
	//if (!file_exists($sbuiadmin_install_lock . 'installer.lock')) {
	if (!file_exists($sbuiadmin_install_lock . 'config.inc.php')) {
		header("Status: 301 Moved Permanently", false, 301);
		header("Location: http://$sbuiadmin_install_url");
		exit();
	} else {
		// --- Warning Page Home Admin
		$sbsmarty->assign('sb_warning_install_file', true);
	}
} else {
	$sbsmarty->assign('sb_warning_install_file', false);
}
if (file_exists($sbuiadmin_htaccess)) {
	// --- Rename htaccess TO .htaccess
	$sbuiadmin_copy_htaccess = copy($sbuiadmin_htaccess, $sbuiadmin_dot_htaccess);
	if ($sbuiadmin_copy_htaccess) {
		// --- Access to Admin and remove install file
		unlink($sbuiadmin_htaccess);
	}
}
if (is_dir(SBUIADMIN_PATH . '/install')) {
	$sbsmarty->assign('sb_warning_installer_lock', true);
}
// ----------------------

// ----------------------
// --- CKEditor Behavior
// ----------------------
$query_ckeditor_behavior   = "SELECT content FROM " . _AM_DB_PREFIX . "sb_config WHERE config = 'toolbarck'";
$request_ckeditor_behavior = $sbsql->query($query_ckeditor_behavior);
$ckeditor_behavior         = $sbsql->assoc($request_ckeditor_behavior);
defined('SBUIADMIN_CKEDITOR_BEHAVIOR') or define('SBUIADMIN_CKEDITOR_BEHAVIOR', (trim($ckeditor_behavior['content']) == 1) ? true : false);

// ----------------------
// --- Settings file init
// ----------------------
$sb_link_settings = file(_AM_SETTINGS_FILE);
$sbsmarty->assign('sb_url_customer', trim($sb_link_settings[15]));
$sbsmarty->assign('sb_toast_duration', (isset($sb_link_settings[35]) && trim($sb_link_settings[35]) != '') ? trim($sb_link_settings[35]) : 7);

// ----------------------
// Identification / Authentification
// ----------------------
// --- Initialization
$publickey  = $sbsanitize->sTrim($sb_link_settings[19]);
$privatekey = $sbsanitize->sTrim($sb_link_settings[20]);
$sbsmarty->assign('grecaptcha_publickey', $publickey);

// --- Random background
$sbsmarty->assign('sb_random_bg', rand(1, 10));

// --- Random background video
$sb_background = ["sM8BCNLo2pE"
				 ,"es86J41Du-Y"
				 ,"NY6xjlmFG7g"
				 ,"bmYcOEhIHjY"
				 ,"kyu_m1LYmaE"
				 ,"0l3uuAQCgRQ"
				 ,"LXBIv9XuXq0"
				 ,"8p0RJSp-xkw"
				 ,"5k4Y9FGKFTU"];
shuffle($sb_background);
$rand_video    = array_rand($sb_background, 2);
$sbsmarty->assign('sb_random_bg_video', $sb_background[$rand_video[1]]);

// ----------------------
// --- Initialisation
// ----------------------
$cookie_remember = 'sbuiadmin_remember';
$cookie_lifetime = intval(sbGetConfig("cookie-lifetime"));
$rememberme      = (isset($_POST['remember']) && $_POST['remember'] == 'longtime') ? 'yes' : 'no';
$sbsmarty->assign('sbuiadmin_access_code', false);

// ----------------------
// Validation du cookie du user
// s'il avait choisi de se loguer
// pour une duree determinee
// ✓ Remember me
// ----------------------
// Point 1 (audit sécurité, 2026-07-29) : jeton sélecteur/validateur
// (sbusers->verifyRememberToken(), sb_users_remember_tokens) au lieu du
// mot de passe chiffré stocké tel quel dans le cookie - un seul cookie
// désormais (sbuiadmin_user_name/_password/_method fusionnés).
global $_COOKIE;
// --- Automatic Login ---
if ( (!isset($_SESSION['sbuiadmin_user_name']) || $_SESSION['sbuiadmin_user_name'] == NULL) && isset($_COOKIE[$cookie_remember]) && $_COOKIE[$cookie_remember] != '') {
	// ------------------
	// --- COOKIE Auth (Remember me)
	// ------------------
	$sb_remember_result = $sbusers->verifyRememberToken($_COOKIE[$cookie_remember]);
	if ($sb_remember_result === false) {
		// Jeton invalide/expiré/déjà utilisé : nettoyage silencieux, on
		// retombe sur le formulaire de connexion normal - un cookie
		// expiré n'est pas une tentative de connexion ratée, pas de log
		// d'échec ici.
		sbSetAuthCookie($cookie_remember, '', time() - 3600);
	} else {
		$sbuiadmin_user_name = trim($sbsanitize->stopXSS($sb_remember_result['username']));
		// --- Check if User is active
		if (!$sbusers->checkUserIsActive($sbuiadmin_user_name)) {
			// --- User is no more active
			$sbsmarty->assign('sbuiadmin_access_code', 'E4');
			$sbuiadmin_type = 'error';
			$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_USER_ERROR, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
			$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
			// --- Destroy COOKIE (le jeton lui-même est déjà supprimé par
			// verifyRememberToken(), à usage unique)
		    sbSetAuthCookie($cookie_remember, '', time() - 3600);
		    // --- Set sessions to NULL
			$_SESSION = array();
		    $_SESSION['sbuiadmin_user_name'] = NULL;
			// --- Smarty display
			$sbsmarty->display('system/login.tpl');
			exit;
		} else {
			// Point 1 (audit sécurité) : même régénération que pour la
			// connexion par formulaire - la ré-authentification via le
			// cookie "Se souvenir de moi" établit tout autant une session
			// authentifiée fraîche.
			session_regenerate_id(true);
			$_SESSION['sbuiadmin_user_name'] = $sbuiadmin_user_name;
			// Jeton précédent déjà supprimé (usage unique) - on en émet un
			// nouveau pour que "Se souvenir de moi" reste valide tant que
			// l'utilisateur revient avant expiration.
			$sb_new_cookie = $sbusers->createRememberToken($sb_remember_result['user_id'], $cookie_lifetime);
			if ($sb_new_cookie !== false) {
				sbSetAuthCookie($cookie_remember, $sb_new_cookie, time() + $cookie_lifetime);
			}
		}
	}
}

if (isset($_SESSION['sbuiadmin_user_name']) && $_SESSION['sbuiadmin_user_name'] != NULL) {
	// ------------------
	// --- SESSION Auth
	// ------------------
	// Point 1 (audit sécurité, 2026-07-29) : ne revérifie plus le mot de
	// passe à chaque requête (l'ancien code déchiffrait et comparait le
	// mot de passe stocké en session sur CHAQUE page admin - coûteux, et
	// de toute façon sans effet réel : un échec ici ne bloquait rien,
	// $sbuiadmin_user_type et le reste de la page continuaient quand même
	// avec la session existante). Une session PHP valide (cookie
	// httponly/secure/samesite, ID régénéré à la connexion) est la
	// preuve d'authentification suffisante - pratique standard. Seul le
	// statut "actif" est encore réévalué à chaque requête : un compte
	// désactivé doit être éjecté immédiatement, pas seulement à sa
	// prochaine connexion.
	$sbuiadmin_user_name = trim($sbsanitize->stopXSS($_SESSION['sbuiadmin_user_name']));
	if (!$sbusers->checkUserIsActive($sbuiadmin_user_name)) {
		// --- User is no more active
		$sbsmarty->assign('sbuiadmin_access_code', 'E4');
		$sbuiadmin_type = 'error';
		$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_USER_ERROR, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
		$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
		$sbsmarty->display('system/login.tpl');
		exit;
	}
}
if ((isset($_POST['username']) && $_POST['username']) && (isset($_POST['password']) && $_POST['password'])) {
	// ------------------
	// --- Anti-flood: rate-limit login attempts by IP, before checking
	// credentials at all (Utilisateurs > IP(s) bloquée(s) > Paramètres IP
	// bloquée(s)). No-ops on its own if Memcache isn't reachable.
	// ------------------
	if (_AM_FLOOD_ENABLED) {
		$sbflood = new flood();
		$sbflood->floodCheck('LOGIN');
	}
	// ------------------
	// --- Form auth
	// ------------------
	// Point 1 (audit sécurité, 2026-07-29) : mot de passe transmis en clair
	// à login() (password_verify() ne fonctionne pas sur un chiffré) -
	// jamais stocké nulle part sous cette forme, ni en session ni en
	// cookie (voir le jeton "Se souvenir de moi" plus bas).
	$sbuiadmin_user_name     = trim($sbsanitize->stopXSS($_POST['username']));
	$sbuiadmin_user_password = $_POST['password'];
	// --- Check User
	if ($sbusers->login($sbuiadmin_user_name, $sbuiadmin_user_password)) {
		// --- Check if User is active
		if (!$sbusers->checkUserIsActive($sbuiadmin_user_name)) {
			// --- User is no more active
			$sbsmarty->assign('sbuiadmin_access_code', 'E4');
			$sbuiadmin_type = 'error';
			$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_USER_ERROR, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
			$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
			$sbsmarty->display('system/login.tpl');
			exit;
		} else {
			if (_AM_CAPTCHA_MODE == true) {
				// --- Check Google Recaptcha
				if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
					function getCurlData($url) {
						$curl = curl_init();
						curl_setopt($curl, CURLOPT_URL, $url);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($curl, CURLOPT_TIMEOUT, 10);
						curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
						$curlData = curl_exec($curl);
						curl_close($curl);
						return $curlData;
					}
					
					// --- Get verify response data
					$google_url = "https://www.google.com/recaptcha/api/siteverify";
					$ip         = $_SERVER['REMOTE_ADDR'];
					$url        = $google_url . "?secret=" . $privatekey . "&response=" . $_POST['g-recaptcha-response'] . "&remoteip=" . $ip;
					$response   = getCurlData($url);
					$response   = json_decode($response); // Don't add TRUE setting in json_decode
					
					if ($response->success === false) {
						// --- Error Google Recaptcha
						$sbsmarty->assign('sbuiadmin_access_code', 'E1');
						$sbuiadmin_type = 'error';
						$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
						$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
						$sbsmarty->display('system/login.tpl');
						exit;						
					}
					
				} else {
					// --- Error Google Recaptcha
					$sbsmarty->assign('sbuiadmin_access_code', 'E1');
					$sbuiadmin_type = 'error';
					$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
					$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
					$sbsmarty->display('system/login.tpl');
					exit;
				}
			}
			// ------------------
			// --- Acces autorise
			// ------------------
			// Update Access Log
			$sbuiadmin_type = 'login';
			$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_GRANTED, $sbuiadmin_user_name, $_SERVER["REMOTE_ADDR"]);
			$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event, $sbuiadmin_user_name);
			// Update LoginTime
			$sbusers->updateAccessUserLogin($sbuiadmin_user_name, false, time());
			// Point 1 (audit sécurité) : régénère l'ID de session à chaque
			// authentification réussie (jamais fait avant - uniquement au
			// logout) pour empêcher la fixation de session (un ID connu/
			// imposé avant connexion ne doit plus être valide après).
			session_regenerate_id(true);
			// Assign SESSION - Point 1 (audit sécurité) : le mot de passe
			// (en clair ou sous quelque forme que ce soit) n'est plus
			// jamais stocké en session - la revérifier à chaque requête
			// n'est plus nécessaire (voir le bloc "SESSION Auth" plus haut).
			$_SESSION['sbuiadmin_user_name'] = $sbuiadmin_user_name;
			// Cookie is Remember me Checked - jeton sélecteur/validateur
			// (Point 1) au lieu du mot de passe stocké dans le cookie.
			if ($rememberme == 'yes') {
				$sb_user_id = intval($sbusers->getUserInfo($sbuiadmin_user_name, 'id'));
				$sb_new_cookie = $sbusers->createRememberToken($sb_user_id, $cookie_lifetime);
				if ($sb_new_cookie !== false) {
					sbSetAuthCookie($cookie_remember, $sb_new_cookie, time() + $cookie_lifetime);
				}
			}
			
		}
	} else {
		// ------------------
		// --- Failed auth
		// ------------------
		$sbsmarty->assign('sbuiadmin_access_code', 'E2');
		$sbuiadmin_type = 'error';
		$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_NOGRANTED, $_SERVER["REMOTE_ADDR"]);
		$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event);
		$sbsmarty->display('system/login.tpl');
		exit;
	}
}
if (!isset($_SESSION['sbuiadmin_user_name']) || $_SESSION['sbuiadmin_user_name'] == NULL) {
	// ------------------
	// --- SESSION Auth
	// ------------------
	// --- No session
	$sbsmarty->assign('uiadmin_access_code', 'E3');
	$sbuiadmin_type = 'error';
	$sbuiadmin_event = sprintf(SBUIADMIN_MSG_LOG_ACCESS_MISSING, $_SERVER["REMOTE_ADDR"]);
	$sbusers->updateAccessLog($sbuiadmin_type, $sbuiadmin_event);
	$sbsmarty->display('system/login.tpl');
	exit;
}
if (isset($_GET['ac']) && $_GET['ac'] == 'logout') {
	// ------------------
	// --- Logout required
	// ------------------
	// Update LastLogin
	$sbusers->updateAccessUserLogin($sbuiadmin_user_name, true);
	// Start SESSION
	session_start();
	// --- Destroy COOKIE + jeton "Se souvenir de moi" en base (Point 1) -
	// une déconnexion explicite doit aussi invalider ce jeton, sinon il
	// reste utilisable pour se reconnecter automatiquement malgré la
	// déconnexion volontaire.
	if (isset($_COOKIE[$cookie_remember]) && $_COOKIE[$cookie_remember] != '') {
		$sbusers->deleteRememberTokenBySelector($_COOKIE[$cookie_remember]);
	}
	sbSetAuthCookie($cookie_remember, '', time() - 3600);
	// --- Set sessions to NULL
	$_SESSION = array();
	session_unset();
	session_destroy();
	session_write_close();
	sbSetAuthCookie(session_name(), '', 0);
	session_regenerate_id(true);
	header("Location: " . trim($sb_link_settings[15]));
	exit();
}
// ----------------------
// Get Global Infos
// ----------------------
global $sbadministrators, $sb_admin_pages;
$sbuiadmin_user_type = (in_array(trim($_SESSION['sbuiadmin_user_name']), $sbadministrators)) ? 'admin' : 'user';
$sbsmarty->assign('sbuiadmin_user_name', $_SESSION['sbuiadmin_user_name']);
$sbsmarty->assign('sbuiadmin_user_type', $sbuiadmin_user_type);
$sbsmarty->assign('sbuiadmin_user_email', $sbusers->getUserInfo($_SESSION['sbuiadmin_user_name'], 'email'));
$sbsmarty->assign('sbuiadmin_user_last_login', date("d/m/Y H:i", $sbusers->getUserInfo($_SESSION['sbuiadmin_user_name'], 'lastlogin')));
// --- Lien "Profil" du dropdown compte (navigation.tpl, Points 11/19)
$sbsmarty->assign('sbuiadmin_user_id', sbGetCurrentUserId());

// ----------------------
// Check if user ADMIN is always in DB
// ----------------------
$sbsmarty->assign('sb_warning_admin_user', ( ($sbusers->getUserInfo('admin', 'username') == 'admin') ? true : false ) );

// ----------------------
// Get Global Configuration
// ----------------------
// --- Link CUSTOMER WEBSITE
if (trim($sb_link_settings[24]) == '1') {
	$table           = _AM_DB_PREFIX . "sb_config";
	$query           = "SELECT config, content FROM $table WHERE config = 'coming-soon-url'";
	$request         = $sbsql->query($query);
	$assoc           = $sbsql->object($request);
	$sb_url_customer = trim($sb_link_settings[15]) . '?d=' . trim($assoc->content);
} else {
	$sb_url_customer = trim($sb_link_settings[15]);
}
$sbsmarty->assign('sb_url_customer', $sb_url_customer);
// --- Sandbox Activation (option globale ET droit "voir" de l'utilisateur -
// ce lien est codé en dur dans main_menu.tpl, hors boucle de sbGetMenuModule())
$sbsmarty->assign('sb_sandbox', (trim($sb_link_settings[16]) == 1 && sbHasRight('sandbox', 'view')) ? 1 : 0);
// --- CMS Activation
$sbsmarty->assign('sb_cms', trim($sb_link_settings[17]));

// ----------------------
// Get Main Menu
// ----------------------
$sb_main_menu_admin = sbGetMenuModule('admin');
$sbsmarty->assign('sb_main_menu_admin', $sb_main_menu_admin);
// ----------------------
$sb_main_menu = sbGetMenuModule('main');
$sbsmarty->assign('sb_main_menu', $sb_main_menu);
// ----------------------

// ----------------------
// Define Safe Pages
// ----------------------
// --- Get "p" in URL
$sb_get_page = (isset($_GET['p'])) ? $_GET['p'] : 'index';
// ----------------------

// ----------------------
// Initialize Debug SQL / smarty variables
// ----------------------
$sbsmarty->assign([
	 'sbdebugsql' => false
	,'sbodump' => false
	,'file_content' => false
	,'sb_msg_error' => false
	,'sb_msg_valid' => false
	,'page_title' => false
	,'sort' => false
	,'all' => false
	,'sbtranfer_media' => false
	,'sb_table_header' => false
]);

// --------------------------------
// --- Search for safe page
// --------------------------------
if (in_array($sb_get_page, $sb_safe_pages) || in_array($sb_get_page, $sb_safe_modules)) {
	// Check if module or system
	$sb_path_file_sys_mod = (file_exists("$sb_get_page.php")) ? '' : 'datas/modules/';
	
	// --- Control if PHP file exists
	$controlIfPhpFileExists = $sb_path_file_sys_mod.$sb_get_page.".php";
	if (file_exists($controlIfPhpFileExists) && $sb_get_page != 'index') {
		// --- Droits granulaires (voir/ajouter/modifier/supprimer) : vérifié
		// AVANT d'exécuter le contrôleur du module, pour bloquer l'accès
		// direct par URL (connaître index.php?p=faq ne suffisait jusqu'ici
		// qu'à contourner le masquage cosmétique du menu). Voir inc/sbuiadmin-rights.php.
		if (!sbHasRight($sb_get_page)) {
			$sbsmarty->display("403.tpl");
			exit;
		}

		// Yes, so include
		sb_global_include($controlIfPhpFileExists);

		// $sb_link_settings (lu tout en haut du fichier, avant de savoir quel
		// module va s'exécuter) est relu ici pour sb_toast_duration : si le
		// module qui vient de s'exécuter (ex: settings.php) vient de modifier
		// settings.txt, la page affichée dans CETTE MÊME réponse doit refléter
		// la nouvelle valeur, pas l'ancienne lue avant l'écriture.
		$sb_link_settings = file(_AM_SETTINGS_FILE);
		$sbsmarty->assign('sb_toast_duration', (isset($sb_link_settings[35]) && trim($sb_link_settings[35]) != '') ? trim($sb_link_settings[35]) : 7);
	} else {
		// No, so show error message
		if (_AM_SITE_DEBUG && $sb_get_page != 'index') echo "Fichier php '$controlIfPhpFileExists' inexistant !";
	}
	
	// Display template page
	// Check if non admin and authorized page
	if (in_array($sb_get_page, $sb_admin_pages) && $sbuiadmin_user_type != 'admin')
		$sbsmarty->display("404.tpl");
	else {
		if ($sb_path_file_sys_mod == '') {
			// System
			if ($sb_get_page == 'index') {
				$sbsmarty->assign('module_page', $sb_get_page);

				// --- Date du jour en français (indépendant de la locale système)
				$sb_fr_days   = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
				$sb_fr_months = array('', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
				$sb_now       = time();
				$sbsmarty->assign('sb_dashboard_date_fr', $sb_fr_days[date('w', $sb_now)] . ' ' . date('j', $sb_now) . ' ' . $sb_fr_months[(int)date('n', $sb_now)] . ' ' . date('Y', $sb_now));

				// --------------------------------
				// --- Widgets du dashboard (table sb_dashboard_widgets) -
				// remplace l'ancien mécanisme positionnel (fichier plat
				// inc/admin/dashboard.txt, 4 emplacements fixes, SQL non
				// échappé). Chaque ligne = une tuile KPI + une carte
				// "éléments récents", avec tendance (7 derniers jours vs 7
				// précédents) et graphique en option si une colonne date a
				// été configurée pour ce widget (voir dashboard.php).
				// --------------------------------
				$sb_dashboard_widgets_query = $sbsql->query("SELECT * FROM " . _AM_DB_PREFIX . "sb_dashboard_widgets WHERE active = 1 ORDER BY position ASC");
				$sb_dashboard_widgets_rows  = $sbsql->toarray($sb_dashboard_widgets_query);

				$sb_dashboard_schema  = sbGetDbSchema();
				$sb_dashboard_widgets = array();

				foreach ($sb_dashboard_widgets_rows as $sb_widget) {
					$sb_widget_type = isset($sb_widget['type']) ? $sb_widget['type'] : 'table';

					// --- system / weather / html / text : pas de source SQL
					// arbitraire, la tuile ne montre qu'une valeur calculée
					// (ou du contenu figé) - aucune liste "Récent" associée.
					if ($sb_widget_type == 'system') {
						$sb_dashboard_widgets[] = array(
							'id'    => $sb_widget['id'],
							'type'  => 'system',
							'title' => $sb_widget['title'],
							'link'  => $sb_widget['link'],
							'icon'  => $sb_widget['icon'],
							'color' => $sb_widget['color'],
							'value' => sbGetSystemWidgetValue($sb_widget['widget_key']),
						);
						continue;
					}

					if ($sb_widget_type == 'weather') {
						$sb_weather = sbGetWeatherWidgetValue($sb_widget['location']);
						$sb_dashboard_widgets[] = array(
							'id'      => $sb_widget['id'],
							'type'    => 'weather',
							'title'   => $sb_widget['title'],
							'link'    => $sb_widget['link'],
							'color'   => $sb_widget['color'],
							// Icône/valeur déduites de la météo du moment, pas
							// du champ "icon" du formulaire (voir dashboard.php).
							'icon'    => $sb_weather ? $sb_weather['icon'] : 'question-circle-o',
							'value'   => $sb_weather ? $sb_weather['temp'] . '°C' : 'Météo indisponible',
							'city'    => $sb_weather ? $sb_weather['city'] : explode('|', $sb_widget['location'])[0],
							'label'   => $sb_weather ? $sb_weather['label'] : '',
						);
						continue;
					}

					if ($sb_widget_type == 'html' || $sb_widget_type == 'text') {
						$sb_dashboard_widgets[] = array(
							'id'      => $sb_widget['id'],
							'type'    => $sb_widget_type,
							'title'   => $sb_widget['title'],
							'link'    => $sb_widget['link'],
							'icon'    => $sb_widget['icon'],
							'color'   => $sb_widget['color'],
							'content' => $sb_widget['content'],
						);
						continue;
					}

					if ($sb_widget_type == 'rss') {
						// "location"/"value_column" réutilisés (URL du flux /
						// nombre d'articles) - voir dashboard.php.
						$sb_dashboard_widgets[] = array(
							'id'    => $sb_widget['id'],
							'type'  => 'rss',
							'title' => $sb_widget['title'],
							'link'  => $sb_widget['link'],
							'icon'  => $sb_widget['icon'],
							'color' => $sb_widget['color'],
							'items' => sbGetRssWidgetValue($sb_widget['location'], intval($sb_widget['value_column']) ?: 5),
						);
						continue;
					}

					if ($sb_widget_type == 'iframe') {
						$sb_dashboard_widgets[] = array(
							'id'    => $sb_widget['id'],
							'type'  => 'iframe',
							'title' => $sb_widget['title'],
							'link'  => $sb_widget['link'],
							'icon'  => $sb_widget['icon'],
							'color' => $sb_widget['color'],
							'src'   => $sb_widget['location'],
						);
						continue;
					}

					if ($sb_widget_type == 'logs') {
						// "location"/"value_column" réutilisés (nom de fichier
						// dans backdoor/logs/ / nombre de lignes) - voir
						// dashboard.php et sbTailLogFile().
						$sb_dashboard_widgets[] = array(
							'id'    => $sb_widget['id'],
							'type'  => 'logs',
							'title' => $sb_widget['title'],
							'link'  => $sb_widget['link'],
							'icon'  => $sb_widget['icon'],
							'color' => $sb_widget['color'],
							'lines' => sbTailLogFile($sb_widget['location'], intval($sb_widget['value_column']) ?: 15),
						);
						continue;
					}

					if ($sb_widget_type == 'logaccess') {
						$sb_dashboard_widgets[] = array(
							'id'    => $sb_widget['id'],
							'type'  => 'logaccess',
							'title' => $sb_widget['title'],
							'link'  => $sb_widget['link'],
							'icon'  => $sb_widget['icon'],
							'color' => $sb_widget['color'],
							'items' => sbGetLastLoginsWidgetValue(intval($sb_widget['value_column']) ?: 10),
						);
						continue;
					}

					// --- type "table" (défaut/historique) : re-vérifié à la
					// lecture (pas seulement à l'écriture), si la
					// table/colonne a disparu depuis, on ignore
					// silencieusement ce widget plutôt que d'exécuter du SQL
					// sur un identifiant fantôme.
					if (!isset($sb_dashboard_schema[$sb_widget['table_name']])
						|| !array_key_exists($sb_widget['value_column'], $sb_dashboard_schema[$sb_widget['table_name']])) {
						continue;
					}

					$sb_full_table = _AM_DB_PREFIX . $sb_widget['table_name'];
					$sb_value_col  = $sb_widget['value_column'];
					$sb_date_col   = $sb_widget['date_column'];
					$sb_has_date   = ($sb_date_col != '' && array_key_exists($sb_date_col, $sb_dashboard_schema[$sb_widget['table_name']]));

					$sb_item = array(
						'id'    => $sb_widget['id'],
						'type'  => 'table',
						'title' => $sb_widget['title'],
						'link'  => $sb_widget['link'],
						'icon'  => $sb_widget['icon'],
						'color' => $sb_widget['color'],
						'cpt'   => 0,
						'all'   => array(),
						'trend' => null,
						'chart' => null,
					);

					// --- Compteur total + 10 plus récents
					$sb_order_col = $sb_has_date ? $sb_date_col : $sb_value_col;
					$sb_count_row = $sbsql->assoc($sbsql->query("SELECT COUNT(*) AS cpt FROM `$sb_full_table`"));
					$sb_item['cpt'] = ($sb_count_row) ? intval($sb_count_row['cpt']) : 0;

					$sb_list_request = $sbsql->query("SELECT `$sb_value_col` AS val FROM `$sb_full_table` ORDER BY `$sb_order_col` DESC LIMIT 10");
					$sb_item['all']  = $sbsql->toarray($sb_list_request);

					// --- Tendance + graphique (seulement si colonne date valide)
					if ($sb_has_date) {
						$sb_col_type     = $sb_dashboard_schema[$sb_widget['table_name']][$sb_date_col];
						$sb_is_real_date = (bool) preg_match('/^(date|datetime|timestamp)/i', $sb_col_type);
						$sb_is_int_ts    = (bool) preg_match('/^(int|bigint|mediumint|smallint)/i', $sb_col_type);

						if ($sb_is_real_date || $sb_is_int_ts) {
							if ($sb_is_int_ts) {
								$sb_bound_7    = 'UNIX_TIMESTAMP(NOW() - INTERVAL 7 DAY)';
								$sb_bound_14   = 'UNIX_TIMESTAMP(NOW() - INTERVAL 14 DAY)';
								$sb_group_expr = "FROM_UNIXTIME(`$sb_date_col`, '%Y-%m-%d')";
							} else {
								$sb_bound_7    = 'NOW() - INTERVAL 7 DAY';
								$sb_bound_14   = 'NOW() - INTERVAL 14 DAY';
								$sb_group_expr = "DATE(`$sb_date_col`)";
							}

							$sb_current_row  = $sbsql->assoc($sbsql->query("SELECT COUNT(*) AS cpt FROM `$sb_full_table` WHERE `$sb_date_col` >= $sb_bound_7"));
							$sb_previous_row = $sbsql->assoc($sbsql->query("SELECT COUNT(*) AS cpt FROM `$sb_full_table` WHERE `$sb_date_col` >= $sb_bound_14 AND `$sb_date_col` < $sb_bound_7"));

							$sb_current  = ($sb_current_row) ? intval($sb_current_row['cpt']) : 0;
							$sb_previous = ($sb_previous_row) ? intval($sb_previous_row['cpt']) : 0;

							if ($sb_current > $sb_previous) $sb_direction = 'up';
							elseif ($sb_current < $sb_previous) $sb_direction = 'down';
							else $sb_direction = 'flat';

							$sb_percent = ($sb_previous > 0)
								? round((($sb_current - $sb_previous) / $sb_previous) * 100)
								: (($sb_current > 0) ? 100 : 0);

							$sb_item['trend'] = array(
								'direction' => $sb_direction,
								'percent'   => abs($sb_percent),
								'current'   => $sb_current,
								'previous'  => $sb_previous,
							);

							// --- Graphique : répartition par jour sur les 14 derniers jours
							if ($sb_widget['show_chart']) {
								$sb_chart_request = $sbsql->query(
									"SELECT $sb_group_expr AS jour, COUNT(*) AS cpt FROM `$sb_full_table`
									 WHERE `$sb_date_col` >= $sb_bound_14
									 GROUP BY jour ORDER BY jour ASC"
								);
								$sb_chart_rows   = $sbsql->toarray($sb_chart_request);
								$sb_chart_by_day = array();
								foreach ($sb_chart_rows as $sb_crow) {
									$sb_chart_by_day[$sb_crow['jour']] = intval($sb_crow['cpt']);
								}

								$sb_chart_labels = array();
								$sb_chart_values = array();
								for ($sb_d = 13; $sb_d >= 0; $sb_d--) {
									$sb_day_key         = date('Y-m-d', strtotime("-$sb_d days"));
									$sb_chart_labels[]  = date('d/m', strtotime("-$sb_d days"));
									$sb_chart_values[]  = isset($sb_chart_by_day[$sb_day_key]) ? $sb_chart_by_day[$sb_day_key] : 0;
								}

								$sb_item['chart'] = array('labels' => $sb_chart_labels, 'values' => $sb_chart_values);
							}
						}
					}

					$sb_dashboard_widgets[] = $sb_item;
				}

				$sbsmarty->assign('sb_dashboard_widgets', $sb_dashboard_widgets);

				// --- Users (cpt) - hors mécanisme widgets, ligne KPI admin fixe (index.tpl)
				$query_5  = "SELECT id FROM " . _AM_DB_PREFIX . "sb_users";
				$request5 = $sbsql->query($query_5);
				$result5  = $sbsql->numrows($request5);
				$sbsmarty->assign('sb_users_cpt', $result5);

			}
			$sbsmarty->display("system/$sb_get_page.tpl");
		} else {
			// Modules
			$sbsmarty->display("$sb_get_page.tpl");
		}
	}
} else {
	// --- Unsafe page
	$sbsmarty->display("404.tpl");
}
// --------------------------------
?>
