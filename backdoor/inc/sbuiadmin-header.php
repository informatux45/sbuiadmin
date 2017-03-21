<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Headers
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
defined('SBMAGIC_PATH') or die('Are you crazy!');
 
// ----------------------
// INCLUDES by array
// ----------------------
$sbmagic_files = array('config', 'functions');
foreach ($sbmagic_files as $sbmagic_file) {
    require_once(SBMAGIC_PATH . '/inc/' . SBMAGIC_ID . '-' . $sbmagic_file . '.php');
}

// ----------------------
// INCLUDE Version
// ---------------------- 
sb_global_include(SBMAGIC_PATH . '/inc/admin/version.php');

// ----------------------
// INCLUDE Debug Class
// ---------------------- 
sb_global_include(SBMAGIC_PATH . '/inc/debug/Kint.class.php');

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ADMIN Lang
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
sb_global_include(_AM_SITE_LANG_DIR . _AM_SITE_LANG . '.php');


// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Template core config
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
if (!_AM_SMARTY_BACKWARDS_COMPATIBILITY) {
	// ------------------------------------------------------
	// --- Smarty Class
	// ------------------------------------------------------
	sb_global_include(_AM_SMARTY_DIR . 'Smarty.class.php');
	$sbsmarty = new Smarty();
} else {
	// ------------------------------------------------------
	// --- SmartyBC - Backwards Compatibility Wrapper
	// --- TODO: SmartyBC allows: {php} and {include_php} 
	// ------------------------------------------------------
	sb_global_include(_AM_SMARTY_DIR . 'SmartyBC.class.php');
	$sbsmarty = new SmartyBC();
}


// ----------------------
// CLASSES by array
// ----------------------
$sbmagic_classes = array('sql', 'sanitize', 'users', 'medias', 'form', 'page', 'upgrade');
foreach ($sbmagic_classes as $sbmagic_class) {
    sb_global_include(SBMAGIC_PATH . '/inc/class/' . SBMAGIC_ID . '-' . $sbmagic_class . '.php');
}

$sbsql      = new sql();
$sbsanitize = new sanitize();
$sbusers    = new user();
$sbform     = new form();
$sbpage     = new page();
$sbmedias   = new medias();

// ------------------
// --- Check for upgrade (CORE)
// ------------------
if (_AM_UPGRADE_MODE) {
	$sb_upgrade_server  = "http://dev.sbuiadmin.fr/update";
	$sb_upgrade_version = _AM_START_VERSION;
	$sbupgrade  = new upgrade($sb_upgrade_server, $sb_upgrade_version);
	ob_flush(); // the buffer contents are discarded
	if ($sbupgrade->check_for_updates()) {
		$sbsmarty->assign('sbmagic_upgrade_core', $sbupgrade->server_version);
		ob_flush(); // the buffer contents are discarded
		$sbsmarty->assign('sbmagic_upgrade_core_filelist', $sbupgrade->print_updated_files_list());
		ob_flush(); // the buffer contents are discarded
	} else {
		$sbsmarty->assign('sbmagic_upgrade_core', false);
		ob_flush(); // the buffer contents are discarded
	}
}
// ------------------

// ------------------
$sbsmarty->setTemplateDir(array('sys' => _AM_SMARTY_DIR . 'tpls/tpl/'
							   ,'mod' => SBMAGIC_PATH . '/datas/modules/tpls/'
						  ));
$sbsmarty->setCompileDir(SBMAGIC_PATH . '/datas/cache/tpls_c/');
$sbsmarty->setConfigDir(_AM_SMARTY_DIR . 'configs/');
$sbsmarty->setCacheDir(SBMAGIC_PATH . '/datas/cache/core/');
// ------------------
$sbsmarty->force_compile = _AM_SMARTY_FORCE_COMPILE;
// ------------------
$sbsmarty->debugging = _AM_SMARTY_DEBUGGING;
// ------------------
$sbsmarty->caching = _AM_SMARTY_CACHING;
$sbsmarty->cache_lifetime = _AM_SMARTY_CACHE_LIFETIME;
// ------------------

// ------------------

// ------------------
?>