<?php
/**
 * Admin Startbootstrap
 * Configuration > Mise à jour (depuis GitHub, versions signées)
 *
 * Réservé au groupe admin. Les actions passent en AJAX (?p=update&ajax=...),
 * en POST avec le jeton de la page, sauf « autocheck » (vérification
 * quotidienne lancée par le tableau de bord, sans effet hors délai).
 * Voir inc/sbuiadmin-update.php.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
global $sbsmarty, $sbuiadmin_user_type, $sbusers;

$module_page = 'update';
$sbsmarty->assign('module_page', $module_page);
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
$sb_msg_error = false;
$sb_msg_valid = false;

$sb_is_ajax = isset($_GET['ajax']);
if ($sbuiadmin_user_type != 'admin') {
	if ($sb_is_ajax) { http_response_code(403); header('Content-Type: application/json'); echo json_encode(array('error' => 'Réservé aux administrateurs')); exit; }
	return; // index.php affiche la 404 (page des administrateurs)
}

require_once SBUIADMIN_PATH . '/inc/' . SBUIADMIN_ID . '-update.php';
if (empty($_SESSION['sbupd_token'])) $_SESSION['sbupd_token'] = bin2hex(random_bytes(16));

// ---------------------------------------------------------------
// AJAX
// ---------------------------------------------------------------
if ($sb_is_ajax) {
	header('Content-Type: application/json; charset=utf-8');
	header('Cache-Control: no-store, max-age=0');
	$sb_action = (string) $_GET['ajax'];
	try {
		if ($sb_action === 'autocheck') {
			// Tableau de bord : au plus une vérification par 24 h
			$st = sbUpdCheck(false);
			echo json_encode(array('available' => sbUpdIsAvailable($st), 'version' => $st['latest']['version'] ?? '', 'current' => $st['current']));
			exit;
		}
		if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['sbupd_token'], (string) ($_POST['token'] ?? ''))) {
			throw new RuntimeException('Jeton invalide : rechargez la page');
		}
		$_SESSION['sbupd_state'] = sbUpdStateFile();
		// Mise à jour et retour arrière : mot de passe redemandé (une session
		// volée ou un poste resté ouvert ne suffit pas), tentatives comptées
		if ($sb_action === 'run' || $sb_action === 'rollback') {
			$sb_user = (string) $_SESSION['sbuiadmin_user_name'];
			if (sbLoginLocked($sb_user)) throw new RuntimeException('Trop de tentatives échouées : réessayez dans quelques minutes');
			if (!$sbusers->login($sb_user, (string) ($_POST['password'] ?? ''))) {
				sbLoginFailed($sb_user);
				sbUpdLog('Mot de passe refusé avant ' . ($sb_action === 'run' ? 'une mise à jour' : 'un retour arrière'), $sb_user);
				throw new RuntimeException('Mot de passe incorrect');
			}
		}
		switch ($sb_action) {
			case 'check':
				$st = sbUpdCheck(true);
				$out = array('available' => sbUpdIsAvailable($st), 'state' => $st);
				break;
			case 'prepare':
				$out = sbUpdPrepare();
				break;
			case 'run':
				// La copie dure : libérer la session (update-status.php la lit en parallèle)
				$sb_confirm = !empty($_POST['confirm']);
				session_write_close();
				$out = sbUpdRun($sb_confirm);
				break;
			case 'rollback':
				$sb_backup = (string) ($_POST['backup'] ?? '');
				$sb_accept = !empty($_POST['accept_data_loss']);
				session_write_close();
				$out = sbUpdRollback($sb_backup, $sb_accept);
				break;
			case 'cancel':
				sbUpdCancel();
				$out = array('status' => 'cancelled');
				break;
			default:
				throw new RuntimeException('Action inconnue');
		}
		echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	} catch (Throwable $e) {
		http_response_code(409);
		echo json_encode(array('error' => $e->getMessage()), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
	exit;
}

// ---------------------------------------------------------------
// Page
// ---------------------------------------------------------------
$sb_upd_state = sbUpdState();
$sb_upd_job      = null;
$sb_upd_storage  = null;
$sb_upd_selftest = '';
$sb_upd_rollback = null;
try {
	$sb_upd_storage = sbUpdStorageInfo();
	$sb_upd_selftest = sbUpdSelfTest();
	$_SESSION['sbupd_state'] = sbUpdStateFile();
	$job = sbUpdLoadJob();
	if ($job) $sb_upd_job = array('status' => $job['status'], 'to' => $job['to'] ?? '', 'step' => $job['step'] ?? '', 'error' => $job['error'] ?? '');
	$sb_upd_rollback = sbUpdRollbackInfo();
	if ($sb_upd_rollback) {
		$sb_b = sbUpdWorkDir() . '/' . $sb_upd_rollback['name'];
		$sb_upd_rollback['date_fr'] = date('d/m/Y H:i', filemtime($sb_b));
		$sb_upd_rollback['size_mo'] = round(filesize($sb_b) / 1048576, 1);
		$sb_upd_rollback['migrations_str'] = implode(', ', (array) ($sb_upd_rollback['migrations'] ?? array()));
	}
	sbUpdDbVersion(); // initialise db_version si absente
} catch (Throwable $e) {
	$sb_msg_error = $e->getMessage();
}
$sb_upd_history = json_decode(sbSetting('update_history'), true);

$sbsmarty->assign('sb_upd_state', $sb_upd_state);
$sbsmarty->assign('sb_upd_available', sbUpdIsAvailable($sb_upd_state));
// Notes de version (GitHub, hors signature) : échappées, puis **gras** et `code`
$sb_upd_notes = htmlspecialchars((string) ($sb_upd_state['latest']['notes'] ?? ''), ENT_QUOTES, 'UTF-8');
$sb_upd_notes = preg_replace(array('/\*\*(.+?)\*\*/', '/`([^`]+)`/'), array('<strong>$1</strong>', '<code>$1</code>'), $sb_upd_notes);
$sbsmarty->assign('sb_upd_notes_html', nl2br($sb_upd_notes));
$sbsmarty->assign('sb_upd_job', $sb_upd_job);
$sbsmarty->assign('sb_upd_storage', $sb_upd_storage);
$sbsmarty->assign('sb_upd_selftest', $sb_upd_selftest);
$sbsmarty->assign('sb_upd_rollback', $sb_upd_rollback);
$sbsmarty->assign('sb_upd_db_version', sbSetting('db_version'));
$sbsmarty->assign('sb_upd_history', is_array($sb_upd_history) ? $sb_upd_history : array());
$sbsmarty->assign('sb_upd_token', $_SESSION['sbupd_token']);
// 0 après une mise à jour ou un retour arrière : nouvelle vérification forcée
$sbsmarty->assign('sb_upd_last_check', $sb_upd_state['last_check'] ? date('d/m/Y H:i', $sb_upd_state['last_check']) : (is_array($sb_upd_history) && $sb_upd_history ? 'à refaire (après la dernière opération)' : 'jamais'));
$sbsmarty->assign('sb_upd_ready', class_exists('ZipArchive') && function_exists('curl_init') && function_exists('sodium_crypto_sign_verify_detached'));

$sbsmarty->assign('page_title', 'Mise à jour');
$sbsmarty->assign('legend_add_edit', '');
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);
