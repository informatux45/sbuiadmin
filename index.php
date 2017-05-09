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
session_start();

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
$sbmagic_install_dir  = SB_ADMIN_DIR . 'install.php';
$sbmagic_install_url  = SB_ADMIN_URL . 'install.php';
$sbmagic_install_lock = SB_ADMIN_DIR . 'install/installer/data/';
// ----------------------
if (file_exists($sbmagic_install_dir)) {
	// --- Check if install is done or not
	if (!file_exists($sbmagic_install_lock . 'installer.lock')) {
		header("Status: 301 Moved Permanently", false, 301);
		header("Location: $sbmagic_install_url");
		exit();
	}
}

// ----------------------
// Get Global Configuration
// ----------------------
$sbsmarty->assign('sb_site_title', _AM_SITE_TITLE);

// ----------------------
// Maintenance Site
// ----------------------
if (SBMAINTENANCE) {
	$param['id'] = 'coming-soon-url';
	$getDevUrl   = (insert_sbGetConfig($param)) ? insert_sbGetConfig($param) : 'DevProgress';
	if ((!isset($_GET['d']) || $_GET['d'] != $getDevUrl) && (!isset($_SESSION['dev_in_progress']) && $_SESSION['dev_in_progress'] != 'SBmagicCMS')) {
		$moved_301_maintenance = SB_URL . "coming-soon/";
		header("Status: 301 Moved Permanently", false, 301);
		header("Location: " . str_replace('//coming', '/coming', $moved_301_maintenance));
		exit();
	} else {
		$_SESSION['dev_in_progress'] = 'SBuiadminCMS';
	}
}

// ----------------------
// Get Global Infos
// ----------------------
if (!empty($_SESSION['sbmagic_user_name'])) {
	global $sbadministrators, $sb_admin_pages;
	$sbmagic_user_type = (in_array(trim($_SESSION['sbmagic_user_name']), $sbadministrators)) ? 'admin' : 'user';
	$sbsmarty->assign('sbmagic_user_name', $_SESSION['sbmagic_user_name']);
	$sbsmarty->assign('sbmagic_user_type', $sbmagic_user_type);
	$sbsmarty->assign('sbmagic_user_email', $sbusers->getUserInfo($_SESSION['sbmagic_user_name'], 'email'));
	$sbsmarty->assign('sbmagic_user_last_login', date("d/m/Y H:i", $sbusers->getUserInfo($_SESSION['sbmagic_user_name'], 'lastlogin')));
}
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
// --- Search for safe page
// --------------------------------
if (in_array($sb_get_page, $sb_safe_pages_cms) || in_array($sb_get_page, $sb_safe_modules_cms)) {
	
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
		$sbsmarty->display("index.tpl");
		
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
		$sbsmarty->display("index.tpl");
		
	} else {
		// ---------------------------------------		
		// --- Get Statistics
		// ---------------------------------------
		//sbGetStats('NOT FOUND');
		// -----------------------------------
		// --- Page doesn't exist
		// -----------------------------------
		$sbsmarty->display("404.tpl");			
	}
	
} else {
	// --- Get Statistics
	//sbGetStats('NOT FOUND');
	// --- Unsafe Pages / Modules
	$sbsmarty->display("404.tpl");
}
// --------------------------------
?>