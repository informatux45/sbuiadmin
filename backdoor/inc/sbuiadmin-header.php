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
defined('SBUIADMIN_PATH') or die('Are you crazy!');

// ----------------------
// INCLUDES by array
// ----------------------
$sbuiadmin_files = array('config', 'functions');
foreach ($sbuiadmin_files as $sbuiadmin_file) {
    require_once(SBUIADMIN_PATH . '/inc/' . SBUIADMIN_ID . '-' . $sbuiadmin_file . '.php');
}

// ----------------------
// INCLUDE Version
// ---------------------- 
sb_global_include(SBUIADMIN_PATH . '/inc/admin/version.php');

// ----------------------
// INCLUDE Debug Class
// ---------------------- 
require(SBUIADMIN_PATH . '/inc/debug/kint.phar');

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// ADMIN Lang
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
sb_global_include(_AM_SITE_LANG_DIR . _AM_SITE_LANG . '.php');

// ------------------------------------------------------
// --- Smarty Class
// ------------------------------------------------------
sb_global_include(_AM_SMARTY_DIR . 'Smarty.class.php');
$sbsmarty = new Smarty();

// ----------------------
// CLASSES by array
// ----------------------
$sbuiadmin_classes = array('sql', 'sanitize', 'users', 'medias', 'form', 'page', 'upgrade');
foreach ($sbuiadmin_classes as $sbuiadmin_class) {
    sb_global_include(SBUIADMIN_PATH . '/inc/class/' . SBUIADMIN_ID . '-' . $sbuiadmin_class . '.php');
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
		$sbsmarty->assign('sbuiadmin_upgrade_core', $sbupgrade->server_version);
		ob_flush(); // the buffer contents are discarded
		$sbsmarty->assign('sbuiadmin_upgrade_core_filelist', $sbupgrade->print_updated_files_list());
		ob_flush(); // the buffer contents are discarded
	} else {
		$sbsmarty->assign('sbuiadmin_upgrade_core', false);
		ob_flush(); // the buffer contents are discarded
	}
} else {
    $sbsmarty->assign('sbuiadmin_upgrade_core', false);
    $sbsmarty->assign('sbuiadmin_upgrade_core_filelist', false);
}
$sbsmarty->assign('sbuiadmin_upgrade_modules', false);
// ------------------

// ------------------
$sbsmarty->setTemplateDir(array('sys' => _AM_SMARTY_DIR . 'tpls/tpl/'
							   ,'mod' => SBUIADMIN_PATH . '/datas/modules/tpls/'
						  ));
$sbsmarty->setCompileDir(SBUIADMIN_PATH . '/datas/cache/tpls_c/');
$sbsmarty->setConfigDir(_AM_SMARTY_DIR . 'configs/');
$sbsmarty->setCacheDir(SBUIADMIN_PATH . '/datas/cache/core/');
// ------------------
$sbsmarty->force_compile = _AM_SMARTY_FORCE_COMPILE;
// ------------------
$sbsmarty->debugging = _AM_SMARTY_DEBUGGING;
// ------------------
$sbsmarty->caching = _AM_SMARTY_CACHING;
$sbsmarty->cache_lifetime = _AM_SMARTY_CACHE_LIFETIME;
// ------------------
?>