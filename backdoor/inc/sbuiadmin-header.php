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

/**
 * Fatal error handler: logs the error, then shows a debug dump (dev) or a
 * custom 500 page (production) depending on _AM_SITE_DEBUG.
 */
function __fatalHandler() {
    $error = error_get_last();

    // Check if it's a core/fatal error
    if ($error !== null && in_array($error['type'], [
        E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING,
        E_COMPILE_ERROR, E_COMPILE_WARNING, E_RECOVERABLE_ERROR
    ])) {
        error_log(sprintf(
            'SBUIADMIN fatal error: %s in %s:%d',
            $error['message'], $error['file'], $error['line']
        ));

        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        http_response_code(500);

        if (defined('_AM_SITE_DEBUG') && _AM_SITE_DEBUG) {
            echo "<pre>Fatal Error:\n";
            var_dump($error);
            echo "</pre>";
        } elseif (defined('SBUIADMIN_PATH') && is_readable(SBUIADMIN_PATH . '/500.html')) {
            $page = file_get_contents(SBUIADMIN_PATH . '/500.html');
            $message = !empty($error['message']) ? $error['message'] : 'Aucun détail disponible.';
            echo str_replace('__ERROR_MESSAGE__', htmlspecialchars($message, ENT_QUOTES, 'UTF-8'), $page);
        } else {
            echo '<h1>500 - Internal Server Error</h1>';
        }
        die; // Terminate script execution
    }
}
register_shutdown_function('__fatalHandler');

// ----------------------
// INCLUDES by array
// ----------------------
$sbuiadmin_files = array('config', 'functions', 'rights');
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
$sbuiadmin_classes = array('sql', 'sanitize', 'users', 'medias', 'form', 'page', 'pagination', 'upgrade', 'flood');
foreach ($sbuiadmin_classes as $sbuiadmin_class) {
    sb_global_include(SBUIADMIN_PATH . '/inc/class/' . SBUIADMIN_ID . '-' . $sbuiadmin_class . '.php');
}

$sbsql      = new sql();
$sbsanitize = new sanitize();
$sbusers    = new user();
$sbform     = new form();
$sbpage     = new page();
$sbmedias   = new medias();

// bridge.css is edited frequently during development - .htaccess sends
// max-age=3600 on .css, so a plain URL can serve a stale copy through any
// intermediate proxy/CDN for up to an hour even after a browser cache
// clear. Tie the URL to the file's own mtime so it only changes when the
// file actually does.
$sbsmarty->assign('bridge_css_version', @filemtime(SBUIADMIN_PATH . '/assets/adminator/bridge.css'));

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
