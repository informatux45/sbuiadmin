<?php
/* ******************************* *
 * Main File                       *
 * ------------------------------- *
 * @link http://informatux.com/    *
 * @package SBUIADMIN              *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */
 
// ----------------------
// SESSION Initialisation
// ----------------------
ini_set("session.gc_maxlifetime", 24*3600); // 1 day (24 hours)
// Options disponibles depuis PHP 7.0
// Point 1 (audit sécurité) : cookie_secure calculé (pas codé en dur à 1) -
// sbconfig.php n'est pas encore chargé à ce stade, donc pas d'accès à
// $_sb_https ; même détection reprise ici en local pour ne jamais envoyer
// le cookie de session en clair sur HTTPS, sans casser les accès HTTP
// (dev local, etc.) en forçant un cookie_secure qui ne serait alors jamais
// renvoyé par le navigateur.
$sb_is_https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
// --- Nom de la session : DOIT etre pose avant session_start(), sinon ce
// --- point d'entree repose sa propre session sous PHPSESSID et perd tout
// --- ce que les autres y ont mis. Voir inc/sbsession.php.
require_once(__DIR__ . '/inc/sbsession.php');
session_start([
    'cookie_lifetime' => 86400,
    'gc_maxlifetime'  => 86400,
    'cookie_secure'   => $sb_is_https,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);
ob_start();

// ----------------------
// SESSION Language
// ----------------------
$langue = $langue = (isset($_REQUEST['lang'])) ? $_REQUEST['lang'] : false;
if (!isset($langue)) {
	if ($_SESSION['lang'] != '') {
		// SESSION INCHANGEE
	} else {
		$langue = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$langue = strtolower(substr(chop($langue[0]),0,2));
		if ($langue != "en") {
			$langue = "fr";
		} else {
			$langue = "en";
		}
		$_SESSION['lang'] = $langue;
	}
} else {
	if ($langue != "en" && $langue != "fr") {
		$langue = "fr";
	}
	$_SESSION['lang'] = $langue;
}

// ----------------------
// Global defined
// ----------------------
include_once('sbconfig.php');

// ----------------------
// Global include
// ----------------------
include_once('header.php');
// ----------------------

// ----------------------
// Define Globals
// ----------------------
global $sbsmarty, $sbsanitize, $sbpage;
// ----------------------

// ----------------------
// Check INSTALLATION
// ----------------------
$sbuiadmin_install_dir  = SB_ADMIN_DIR . 'install.php';
$sbuiadmin_install_url  = SB_ADMIN_URL . 'install.php';
// ----------------------
if (file_exists($sbuiadmin_install_dir)) {
	// --- Installation faite ? install/ supprimé, ou verrouillé en fin
	// --- d'installation : voir sbSiteInstalled() (SBADMIN/inc/sbuiadmin-settings.php)
	if (!sbSiteInstalled()) {
		header("Location: $sbuiadmin_install_url", true, 302);
		exit();
	}
}

// ----------------------
// Get Global Configuration
// ----------------------
$sbsmarty->assign('sb_site_title', _AM_SITE_TITLE);

// ----------------------
// Maintenance Site (Configuration > Générale, page : CMS Config > Maintenance)
// Page affichée à l'adresse demandée (503, sans redirection ni cache) :
// à la réouverture, un rafraîchissement suffit. Passent : ?d=<code d'accès>
// et les utilisateurs connectés au back-office. Voir inc/maintenance.php.
// ----------------------
// Aperçu de la page (CMS Config > Maintenance), même site ouvert :
// utilisateurs du back-office seulement
if (isset($_GET['maintenance']) && $_GET['maintenance'] === 'apercu') {
	require_once(__DIR__ . '/inc/maintenance.php');
	if (sbMaintenanceBackofficeUser()) sbMaintenancePage();
}
if (SBMAINTENANCE) {
	require_once(__DIR__ . '/inc/maintenance.php');
	$param['id'] = 'coming-soon-url';
	$getDevUrl   = (insert_sbGetConfig($param)) ? (string) insert_sbGetConfig($param) : 'DevProgress';
	if (!sbMaintenanceBypass($getDevUrl)) {
		sbMaintenancePage(); // termine le script
	}
}

// ----------------------
// Get Global Infos
// ----------------------
// Session admin dont le mot de passe a changé depuis (hash différent) :
// on retire seulement les clés admin, le reste de la session front reste.
if (!empty($_SESSION['sbuiadmin_user_name']) && !$sbusers->checkSessionHash($_SESSION['sbuiadmin_user_name'], isset($_SESSION['sbuiadmin_user_password']) ? $_SESSION['sbuiadmin_user_password'] : '')) {
	unset($_SESSION['sbuiadmin_user_name'], $_SESSION['sbuiadmin_user_password']);
}
// Session admin pas encore passée par la double authentification (code
// e-mail, backdoor/inc/sbuiadmin-2fa.php) : pas traitée comme connectée ici.
// Fichier de secours backdoor/inc/admin/2fa-disabled : 2FA désactivée.
$sb_admin_2fa_ok = file_exists(SB_ADMIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . '2fa-disabled')
	|| (!empty($_SESSION['sb2fa_ok']) && !empty($_SESSION['sbuiadmin_user_name']) && hash_equals((string)$_SESSION['sb2fa_ok'], (string)$_SESSION['sbuiadmin_user_name']));
if (!empty($_SESSION['sbuiadmin_user_name']) && $sb_admin_2fa_ok) {
	$sbadministrators = explode(",", sbSetting('administrators'));
	$sbuiadmin_user_type = (in_array(trim($_SESSION['sbuiadmin_user_name']), $sbadministrators)) ? 'admin' : 'user';
	$sbsmarty->assign('sbuiadmin_user_name', $_SESSION['sbuiadmin_user_name']);
	$sbsmarty->assign('sbuiadmin_user_type', $sbuiadmin_user_type);
	$sbsmarty->assign('sbuiadmin_user_email', $sbusers->getUserInfo($_SESSION['sbuiadmin_user_name'], 'email'));
	$sbsmarty->assign('sbuiadmin_user_last_login', date("d/m/Y H:i", $sbusers->getUserInfo($_SESSION['sbuiadmin_user_name'], 'lastlogin')));
}
// ----------------------
// ----------------------
// ----------------------

// ----------------------
// Get CMS Theme infos
// ----------------------
$query_theme_infos   = "SELECT config, content FROM " . _AM_DB_PREFIX . "sb_config WHERE config LIKE 'theme_infos_%'";
$request_theme_infos = $sbsql->query($query_theme_infos);
$result_theme_infos  = $sbsql->toarray($request_theme_infos);
foreach($result_theme_infos as $row_theme_infos) {
	$theme_config = $row_theme_infos['config'];
	$sb_theme_infos[$theme_config] = $row_theme_infos['content'];
}
$sbsmarty->assign('sb_theme_infos', $sb_theme_infos);
// ----------------------
// ----------------------
// ----------------------

// ----------------------
// Define Safe Pages
// ----------------------
// --- Check if rewrite URL
if (SBREWRITEURL) {
	// --- Define if PAGES or MODULES
	$sb_rewrite_url = sbRewriteUrl(SBSITESUBDIRECTORY);
	$sb_get_page    = ($sb_rewrite_url) ? $sbsanitize->stopXSS($sb_rewrite_url) : 'index';
} else {
	// --- Get "p" in URL
	$sb_get_page    = (isset($_GET['p'])) ? $sbsanitize->stopXSS($_GET['p']) : 'index';
}
// ----------------------

// --------------------------------
// --- Smarty cache_id : une entrée de cache distincte par URL, sinon le
// --- cache Smarty (settings.txt) renvoie la même page (celle mise en
// --- cache en premier) pour toutes les URLs du site une fois activé.
// --------------------------------
$sb_smarty_cache_id = md5($sb_get_page . '|' . ($_SERVER['REQUEST_URI'] ?? ''));

// --------------------------------
// --- Search for safe page
// --------------------------------
if (in_array($sb_get_page, $sb_safe_pages_cms) || in_array($sb_get_page, $sb_safe_modules_cms ?? [])) {
	
	// ---------------------------------------
	// --- Assign modules / pages paths
	// ---------------------------------------
	// --- Check if is MODULE or PAGE who call a module
	if ($sb_get_page == 'index' || $sb_get_page == 'pages') {
		// Pages VIEW
		$sb_call_path  = SB_MODULES_DIR . "pages" . DIRECTORY_SEPARATOR . "pages.php";
	} else {
		// Module VIEW
		$sb_call_path  = SB_MODULES_DIR . $sb_get_page . DIRECTORY_SEPARATOR . $sb_get_page . ".php";
		// Global Module Infos
		global $module;
	}
	
	// ---------------------------------------
	// --- Include PAGES Module (everytime)
	// ---------------------------------------
	$include_page_path = include_once($sb_call_path);

	// ---------------------------------------
	// Check if display is possible
	// ---------------------------------------	
	if (!$include_page_path) {
		// --- Get Statistics
		//sbGetStats('NOT FOUND');
		// --- Unsafe Pages / Modules
		http_response_code(404); // page inexistante : un 200 ici fait indexer la 404 par Google
		$sbsmarty->display("404.tpl");
		exit;
	}
	
	// ---------------------------------------
	// Global Page Infos
	// ---------------------------------------
	global $modpage;
	
	// ---------------------------------------
	// --- Check if PAGE exist
	// ---------------------------------------
	if (isset($modpage['page_active']) && $modpage['page_active']) {  // PAGES WITH OR WITHOUT MODULE VIEW
		// ---------------------------------------
		// Assign Module View TPL (Main)
		// ---------------------------------------
		if (isset($modpage['dirname']) && isset($modpage['template_main'])) {
			$sbsmarty->assign("page_view", $modpage['dirname'] . DIRECTORY_SEPARATOR . 'tpls' . DIRECTORY_SEPARATOR . $modpage['template_main'] );
		} else {
			$sbsmarty->assign("page_view", false);
		}
		// ---------------------------------------
		// Assign Module View BLOCKS TPL (Main)
		// ---------------------------------------
		if (isset($modpage['dirname']) && isset($modpage['template_main_blocks'])) {
			$sbsmarty->assign("page_view_blocks", $modpage['dirname'] . DIRECTORY_SEPARATOR . 'tpls' . DIRECTORY_SEPARATOR . $modpage['template_main_blocks'] );
		} else {
			$sbsmarty->assign("page_view_blocks", false);
		}
		// ---------------------------------------
		// Check if a module is choosen by the created page
		// Or the config file (SBCONFIG)
		// ---------------------------------------
		if ((isset($modpage['module_main']) && $modpage['module_main'] != '') || (SBMODULEINDEX)) {
			// -----------------------------------
			// --- Define module to load
			// -----------------------------------
			$get_module_view = ($modpage['module_main'] != '') ? $modpage['module_main'] : SBMODULEINDEX;
			// -----------------------------------
			// Include Module File
			// -----------------------------------
			include_once(SB_MODULES_DIR . $get_module_view . DIRECTORY_SEPARATOR . $get_module_view . ".php");
			// Module View
			if (isset($module['dirname']) && isset($module['template_main'])) {
				$sbsmarty->assign("module_view", $module['dirname'] . DIRECTORY_SEPARATOR . 'tpls' . DIRECTORY_SEPARATOR . $module['template_main'] );
			} else {
				$sbsmarty->assign("module_view", false);
			}
			// Module Blocks View
			if (isset($module['dirname']) && isset($module['template_main_blocks'])) {
				$sbsmarty->assign("module_view_blocks", $module['dirname'] . DIRECTORY_SEPARATOR . 'tpls' . DIRECTORY_SEPARATOR . $module['template_main_blocks'] );
			} else {
				$sbsmarty->assign("module_view_blocks", false);
			}

		} else {
			// -----------------------------------
			// No Module view
			// -----------------------------------
			$sbsmarty->assign("module_view", false);
			$sbsmarty->assign("module_view_blocks", false);
		}			
		// ---------------------------------------
		// Define TPL View (Specify in your Theme)
		// ---------------------------------------
		if (file_exists(SB_THEME_DIR . 'config.php')) {
			// --- Include config.php Theme file
			include_once(SB_THEME_DIR . 'config.php');
			global $theme;
			// --- Check if view exist in config theme
			if (in_array($modpage['theme_main'], $theme['view'])) {
				$mod_theme_view = $modpage['theme_main'];
			} else {
				$mod_theme_view = false;			
			}
		} else {
			// Can't check if view theme main exist :-(
			$mod_theme_view = (isset($modpage['theme_main'])) ? $modpage['theme_main'] : false;
		}
		$sbsmarty->assign("theme_view", $mod_theme_view);
		// ---------------------------------------		
		// --- Get Statistics
		// ---------------------------------------
		//global $sb_pages_title;
		//sbGetStats($sb_pages_title);
		// ---------------------------------------
		// Assign Index TPL Theme
		// ---------------------------------------		
		$sbsmarty->display("index.tpl", $sb_smarty_cache_id);

	} elseif (isset($module['template_main']) && $module['template_main'] != '') { // MODULE STANDALONE
		// ---------------------------------------
		// Assign Module View TPL (Main)
		// ---------------------------------------
		if (isset($module['dirname']) && isset($module['template_main'])) {
			$sbsmarty->assign("module_view", $module['dirname'] . DIRECTORY_SEPARATOR . 'tpls' . DIRECTORY_SEPARATOR . $module['template_main']);
		} else {
			$sbsmarty->assign("module_view", false);
		}
	
		// ---------------------------------------
		// Assign Module View BLOCKS TPL (Main)
		// ---------------------------------------
		if (isset($module['dirname']) && isset($module['template_main_blocks'])) {
			$sbsmarty->assign("module_view_blocks", $module['dirname'] . DIRECTORY_SEPARATOR . 'tpls' . DIRECTORY_SEPARATOR . $module['template_main_blocks'] );
		} else {
			$sbsmarty->assign("module_view_blocks", false);
		}
		
		// ---------------------------------------
		// Define TPL View (Specify in your Theme)
		// ---------------------------------------
		if (file_exists(SB_THEME_DIR . 'config.php')) {
			// --- Include config.php Theme file
			include_once(SB_THEME_DIR . 'config.php');
			global $theme;
			// --- Check if view exist in config theme
			if (in_array($module['theme_main'], $theme['view'])) {
				$mod_theme_view = $module['theme_main'];
			} else {
				$mod_theme_view = false;			
			}
		} else {
			// Can't check if view theme main exist :-(
			$mod_theme_view = (isset($module['theme_main'])) ? $module['theme_main'] : false;
		}
		$sbsmarty->assign("theme_view", $mod_theme_view);
		// ---------------------------------------		
		// --- Get Statistics
		// ---------------------------------------
		//global $sb_pages_title;
		//sbGetStats($sb_pages_title);
		// ---------------------------------------
		// Assign Index TPL Theme
		// ---------------------------------------		
		$sbsmarty->display("index.tpl", $sb_smarty_cache_id);

	} else {
		// ---------------------------------------
		// --- Get Statistics
		// ---------------------------------------
		//sbGetStats('NOT FOUND');
		// -----------------------------------
		// --- Page doesn't exist
		// -----------------------------------
		http_response_code(404); // page inexistante : un 200 ici fait indexer la 404 par Google
		$sbsmarty->display("404.tpl");			
	}
	
} else {
	// --- Get Statistics
	//sbGetStats('NOT FOUND');
	// --- Unsafe Pages / Modules
	http_response_code(404); // page inexistante : un 200 ici fait indexer la 404 par Google
	$sbsmarty->display("404.tpl");
}
// --------------------------------
ob_end_flush();
// --------------------------------
?>
