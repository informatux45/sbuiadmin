<?php
/* ******************************* *
 * Header File                     *
 * ------------------------------- *
 * @link http://www.dollar.fr/     *
 * @package THEME ONE              *
 * @package ADMIN SBMAGIC          *
 * @file UTF-8                     *
 * © DOLLAR.FR                     *
 * ******************************* */

// ------------------------
// DEGUB Mode
// ------------------------
if (SBDEBUG) {
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	ini_set('display_errors', 1);
} else {
	error_reporting(0);
	ini_set('display_errors', 0);
}
 
// ------------------------ 
// Functions
// ------------------------
if (file_exists(SB_ADMIN_DIR . "inc" . DIRECTORY_SEPARATOR . "sbmagic-functions.php"))
	require_once(SB_ADMIN_DIR . "inc" . DIRECTORY_SEPARATOR . "sbmagic-functions.php");
 
// ------------------------
// Langage
// ------------------------
$sblang = (SBLANG && $_SESSION['lang'] != 'en') ? SBLANG : 'en_US';
include_once(SB_PATH . "datas" . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . $sblang . ".php");

// ------------------------
// Template core config
// ------------------------
if (!SBSMARTYBC) {
	// --- Smarty Class
	sb_global_include(SB_SMARTY_DIR . 'Smarty.class.php');
	$sbsmarty = new Smarty();
} else {
	// --- SmartyBC - Backwards Compatibility Wrapper
	// --- TODO: SmartyBC allows: {php} and {include_php} 
	sb_global_include(SB_SMARTY_DIR . 'SmartyBC.class.php');
	$sbsmarty = new SmartyBC();
}


// ----------------------
// CLASSES by array
// ----------------------
$sbmagic_classes = array('sql', 'sanitize', 'page');
foreach ($sbmagic_classes as $sbmagic_class) {
    sb_global_include(SB_ADMIN_DIR . "inc" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR . SBMAGICID . "-" . $sbmagic_class . ".php");
}

$sbsql      = new sql();
$sbsanitize = new sanitize();
$sbpage     = new page();

// ----------------------
// Smarty Configuration
// ----------------------
$sbsmarty->setTemplateDir(array('theme' => SB_THEME_DIR . "tpls" . DIRECTORY_SEPARATOR
							   ,'modules' => SB_MODULES_DIR . DIRECTORY_SEPARATOR
						  ));
$sbsmarty->setCompileDir(SB_PATH . "datas" . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . "tpls_c" . DIRECTORY_SEPARATOR);
$sbsmarty->setConfigDir(SB_PATH . "datas" . DIRECTORY_SEPARATOR . "configs" . DIRECTORY_SEPARATOR);
$sbsmarty->setCacheDir(SB_PATH . "datas" . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR);
// ------------------
$sbsmarty->force_compile = SBSMARTYFORCECOMPILE;
// ------------------
$sbsmarty->debugging = SBSMARTYDEBUG;
// ------------------
$sbsmarty->caching = SBSMARTYCACHING;
$sbsmarty->cache_lifetime = SBSMARTYCACHELIFETIME;
// ------------------

// ------------------------ 
// Functions CMS
// ------------------------
$sb_functions_path = SB_PATH . "inc" . DIRECTORY_SEPARATOR . "functions.php";
if (file_exists($sb_functions_path)) require_once($sb_functions_path);

// ------------------------ 
// Custom Functions CMS
// ------------------------
$sb_custom_path = SB_PATH . "inc" . DIRECTORY_SEPARATOR . "cmscustom.php";
if (file_exists($sb_custom_path)) require_once($sb_custom_path);

// ------------------------ 
// Get ALL functions Files Module CMS
// ------------------------ 
$sb_path_functions_file = SB_MODULES_DIR;
$sb_path_functions_dir  = new DirectoryIterator($sb_path_functions_file);
foreach ($sb_path_functions_dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
		$functions_path = $sb_path_functions_file . $fileinfo->getFilename() . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php';
		if (file_exists($functions_path))
			include_once($functions_path);
    }
}

?>