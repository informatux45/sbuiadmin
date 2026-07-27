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
// --- Visibilité du module Messages (droits granulaires) - conditionne
// l'icône topbar (navigation.tpl) et l'entrée "Communications" du menu
// principal (main_menu.tpl), en plus du gate déjà fait par le routeur.
// ------------------
$sb_current_user_id   = sbGetCurrentUserId();
$sb_can_view_messages = sbHasRight('messages', 'view');
$sbsmarty->assign('sb_can_view_messages', $sb_can_view_messages);
// ------------------

// ------------------
// --- Avatar de l'utilisateur connecté (Point 12) - utilisé par
// navigation.tpl (sidebar + topbar) via sbGetUserAvatar(), qui retombe
// sur Gravatar si aucune photo n'a été uploadée.
// ------------------
$sbuiadmin_user_avatar = '';
if ($sb_current_user_id > 0) {
	$sb_avatar_result      = $sbsql->query("SELECT avatar FROM " . _AM_DB_PREFIX . "sb_users WHERE id = " . $sb_current_user_id);
	$sb_avatar_row         = $sbsql->assoc($sb_avatar_result);
	$sbuiadmin_user_avatar = ($sb_avatar_row) ? $sb_avatar_row['avatar'] : '';
}
$sbsmarty->assign('sbuiadmin_user_avatar', $sbuiadmin_user_avatar);
// ------------------

// ------------------
// --- Messages non lus (badge topbar) - assigné sur CHAQUE page pour que
// le badge soit correct dès le premier rendu, avant tout polling JS.
// ------------------
$sb_unread_messages = 0;
if ($sb_can_view_messages) {
	$sb_unread_result = $sbsql->query("SELECT COUNT(*) AS cpt FROM " . _AM_DB_PREFIX . "sb_messages WHERE recipient_id = " . $sb_current_user_id . " AND read_at = 0");
	$sb_unread_row    = $sbsql->assoc($sb_unread_result);
	$sb_unread_messages = ($sb_unread_row) ? intval($sb_unread_row['cpt']) : 0;
}
$sbsmarty->assign('sb_unread_messages', $sb_unread_messages);
// ------------------

// ------------------
// --- Aperçu des messages non lus (dropdown topbar) - jusqu'à 5
// conversations les plus récentes ayant au moins un message non lu.
// ------------------
$sb_messages_preview = array();
if ($sb_can_view_messages) {
	$sb_msg_table_prev = _AM_DB_PREFIX . "sb_messages";

	$sb_preview_result = $sbsql->query("SELECT * FROM $sb_msg_table_prev WHERE sender_id = $sb_current_user_id OR recipient_id = $sb_current_user_id ORDER BY created_at DESC LIMIT 300");
	$sb_preview_rows   = $sbsql->toarray($sb_preview_result);

	$sb_preview_conv = array();
	foreach ($sb_preview_rows as $sb_prow) {
		$sb_other_id = ($sb_prow['sender_id'] == $sb_current_user_id) ? $sb_prow['recipient_id'] : $sb_prow['sender_id'];
		if (!isset($sb_preview_conv[$sb_other_id])) {
			$sb_preview_conv[$sb_other_id] = array(
				'other_id'     => $sb_other_id,
				'last_message' => $sb_prow['message'],
				'last_time'    => intval($sb_prow['created_at']),
				'unread'       => 0,
			);
		}
		if ($sb_prow['recipient_id'] == $sb_current_user_id && $sb_prow['read_at'] == 0) {
			$sb_preview_conv[$sb_other_id]['unread']++;
		}
	}

	// Ne garder que les conversations avec des non-lus, 5 plus récentes
	$sb_preview_conv = array_slice(array_values(array_filter($sb_preview_conv, function ($c) {
		return $c['unread'] > 0;
	})), 0, 5);

	if (!empty($sb_preview_conv)) {
		$sb_other_ids_list = implode(',', array_map('intval', array_column($sb_preview_conv, 'other_id')));
		$sb_preview_users_result = $sbsql->query("SELECT id, username FROM " . _AM_DB_PREFIX . "sb_users WHERE id IN ($sb_other_ids_list)");
		$sb_preview_users_by_id  = array();
		foreach ($sbsql->toarray($sb_preview_users_result) as $sb_pu) {
			$sb_preview_users_by_id[$sb_pu['id']] = $sb_pu['username'];
		}

		$sb_pi = 0;
		foreach ($sb_preview_conv as &$sb_pc) {
			$sb_pc['username']     = isset($sb_preview_users_by_id[$sb_pc['other_id']]) ? $sb_preview_users_by_id[$sb_pc['other_id']] : '(supprimé)';
			$sb_pc['initials']     = strtoupper(substr($sb_pc['username'], 0, 2));
			$sb_pc['message']      = $sbsanitize->displayText($sb_pc['last_message'], 'UTF-8', 0, 1);
			$sb_pc['avatar_class'] = 'a' . (($sb_pi % 3) + 1);
			$sb_pi++;

			$sb_pdiff = time() - $sb_pc['last_time'];
			if ($sb_pdiff < 3600)       $sb_pc['time_label'] = max(1, intdiv($sb_pdiff, 60)) . ' MIN';
			elseif ($sb_pdiff < 86400)  $sb_pc['time_label'] = intdiv($sb_pdiff, 3600) . ' H';
			else                        $sb_pc['time_label'] = intdiv($sb_pdiff, 86400) . ' J';
		}
		unset($sb_pc);
	}

	$sb_messages_preview = $sb_preview_conv;
}
$sbsmarty->assign('sb_messages_preview', $sb_messages_preview);
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
