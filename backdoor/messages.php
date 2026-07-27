<?php
/**
 * Admin Startbootstrap
 * Manage MESSAGES (messagerie interne 1-à-1 entre utilisateurs admin)
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
session_start();

// -----------------------
// Module URL
// -----------------------
$module_page = 'messages';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);

// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

$table       = _AM_DB_PREFIX . "sb_messages";
$table_users = _AM_DB_PREFIX . "sb_users";
$sb_me       = sbGetCurrentUserId();

// --------------------------------
// --- AJAX : compteur de messages non lus (pollé sur TOUTES les pages,
// --- pas seulement celle-ci, pour le badge topbar - voir navigation.tpl)
// --------------------------------
if (isset($_GET['ajax']) && $_GET['ajax'] == 'unread') {
	$request_unread = $sbsql->query("SELECT COUNT(*) AS cpt FROM $table WHERE recipient_id = $sb_me AND read_at = 0");
	$row_unread     = $sbsql->assoc($request_unread);

	header('Content-Type: application/json');
	echo json_encode(array('count' => ($row_unread) ? intval($row_unread['cpt']) : 0));
	exit;
}

// --------------------------------
// --- AJAX : nouveaux messages d'un fil depuis un timestamp donné
// --- (polling "temps réel" pendant qu'un fil est ouvert)
// --------------------------------
if (isset($_GET['ajax']) && $_GET['ajax'] == 'thread') {
	$sb_with  = intval($_GET['with']);
	$sb_since = intval($_GET['since']);
	$sb_thread_rows = array();

	if ($sb_with > 0) {
		// Marque lu au passage (le poller tourne tant que le fil est ouvert)
		$sbsql->query("UPDATE $table SET read_at = " . time() . " WHERE sender_id = $sb_with AND recipient_id = $sb_me AND read_at = 0");

		$request_thread = $sbsql->query("SELECT * FROM $table
			WHERE ((sender_id = $sb_me AND recipient_id = $sb_with) OR (sender_id = $sb_with AND recipient_id = $sb_me))
			AND created_at > $sb_since
			ORDER BY created_at ASC");
		$sb_thread_rows = $sbsql->toarray($request_thread);
	}

	$sb_messages_out = array();
	foreach ($sb_thread_rows as $sb_row) {
		$sb_messages_out[] = array(
			'id'         => intval($sb_row['id']),
			'message'    => $sbsanitize->displayText($sb_row['message'], 'UTF-8', 0, 1),
			'created_at' => intval($sb_row['created_at']),
			'mine'       => (intval($sb_row['sender_id']) == $sb_me),
		);
	}

	header('Content-Type: application/json');
	echo json_encode(array('messages' => $sb_messages_out));
	exit;
}

// --------------------------------
// --- AJAX : envoi d'un message
// --------------------------------
if (isset($_GET['ajax']) && $_GET['ajax'] == 'send' && $_SERVER['REQUEST_METHOD'] == 'POST') {
	$sb_with  = intval($_GET['with']);
	$sb_body  = $sbsanitize->displayText(file_get_contents('php://input'), 'UTF-8', 0, 1);
	$sb_body  = trim($sb_body);

	header('Content-Type: application/json');

	// Destinataire valide (existe, différent de soi-même) et message non vide
	$request_check = $sbsql->query("SELECT id FROM $table_users WHERE id = $sb_with");
	$sb_dest_ok     = ($sb_with > 0 && $sb_with != $sb_me && $sbsql->numrows() > 0);

	if ($sb_dest_ok && $sb_body !== '') {
		$sb_body_escaped = $sbsql->escape_string($sb_body);
		$sb_now = time();
		$sbsql->query("INSERT INTO $table (sender_id, recipient_id, message, created_at, read_at)
			VALUES ($sb_me, $sb_with, '$sb_body_escaped', $sb_now, 0)");

		echo json_encode(array(
			'ok'      => true,
			'message' => array(
				'id'         => $sbsql->lastinsertid(),
				'message'    => $sbsanitize->displayText($sb_body, 'UTF-8', 0, 1),
				'created_at' => $sb_now,
				'mine'       => true,
			),
		));
	} else {
		echo json_encode(array('ok' => false));
	}
	exit;
}

// --------------------------------
// --- Vue normale : liste des utilisateurs (pour le sélecteur "New chat")
// --------------------------------
$request_users = $sbsql->query("SELECT id, username, email FROM $table_users ORDER BY username ASC");
$sb_all_users  = $sbsql->toarray($request_users);

$sb_users_by_id  = array();
$sb_pickable_users = array();
foreach ($sb_all_users as $sb_user) {
	$sb_users_by_id[$sb_user['id']] = $sb_user;
	if ($sb_user['id'] != $sb_me) $sb_pickable_users[] = $sb_user;
}

// --------------------------------
// --- Vue normale : fil ouvert (?with=<id>) - marque lu à l'ouverture,
// --- AVANT de construire la liste des conversations juste en dessous,
// --- pour que son badge de non-lus reflète l'état à jour dès ce même
// --- chargement de page (plutôt que de n'apparaître à jour qu'au
// --- rechargement suivant).
// --------------------------------
$sb_with      = isset($_GET['with']) ? intval($_GET['with']) : 0;
$sb_with_user = ($sb_with > 0 && isset($sb_users_by_id[$sb_with])) ? $sb_users_by_id[$sb_with] : false;
$sb_thread    = array();

if ($sb_with_user) {
	$sbsql->query("UPDATE $table SET read_at = " . time() . " WHERE sender_id = $sb_with AND recipient_id = $sb_me AND read_at = 0");

	$request_thread = $sbsql->query("SELECT * FROM $table
		WHERE (sender_id = $sb_me AND recipient_id = $sb_with) OR (sender_id = $sb_with AND recipient_id = $sb_me)
		ORDER BY created_at ASC");
	$sb_thread_rows = $sbsql->toarray($request_thread);

	foreach ($sb_thread_rows as $sb_row) {
		$sb_thread[] = array(
			'id'         => intval($sb_row['id']),
			'message'    => $sbsanitize->displayText($sb_row['message'], 'UTF-8', 0, 1),
			'created_at' => intval($sb_row['created_at']),
			'mine'       => (intval($sb_row['sender_id']) == $sb_me),
		);
	}
}

// --------------------------------
// --- Vue normale : liste des conversations, dérivée en PHP (pas de
// --- GROUP BY fragile) - la 1ère occurrence par correspondant rencontrée
// --- dans ce tri DESC est la plus récente.
// --------------------------------
$request_conv = $sbsql->query("SELECT * FROM $table WHERE sender_id = $sb_me OR recipient_id = $sb_me ORDER BY created_at DESC");
$sb_conv_rows = $sbsql->toarray($request_conv);

$sb_conversations = array();
foreach ($sb_conv_rows as $sb_row) {
	$sb_other_id = ($sb_row['sender_id'] == $sb_me) ? $sb_row['recipient_id'] : $sb_row['sender_id'];

	if (!isset($sb_conversations[$sb_other_id])) {
		$sb_conversations[$sb_other_id] = array(
			'other_id'     => $sb_other_id,
			'other_user'   => isset($sb_users_by_id[$sb_other_id]) ? $sb_users_by_id[$sb_other_id] : array('username' => '(supprimé)', 'email' => ''),
			'last_message' => $sbsanitize->displayText($sb_row['message'], 'UTF-8', 0, 1),
			'last_time'    => intval($sb_row['created_at']),
			'unread'       => 0,
		);
	}
	if ($sb_row['recipient_id'] == $sb_me && $sb_row['read_at'] == 0) {
		$sb_conversations[$sb_other_id]['unread']++;
	}
}
$sb_conversations = array_values($sb_conversations);

$sbsmarty->assign('page_title', 'Messages');
$sbsmarty->assign('sb_messages_conversations', $sb_conversations);
$sbsmarty->assign('sb_messages_pickable_users', $sb_pickable_users);
$sbsmarty->assign('sb_messages_with', $sb_with);
$sbsmarty->assign('sb_messages_with_user', $sb_with_user);
$sbsmarty->assign('sb_messages_thread', $sb_thread);
$sbsmarty->assign('sb_messages_current_user_id', $sb_me);
$sbsmarty->assign('sb_emoji_list', array(
	'😀','😂','😊','😍','😎','🤔','😢','😭','😡','👍','👎','👏',
	'🙏','💪','🎉','🔥','❤️','💯','✅','❌','🚀','👀','😴','🤷',
	'😅','🙌','👋','🤝','💡','⚠️','📌','⏰',
));
