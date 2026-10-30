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
// --- Nom de la session : DOIT etre pose avant session_start(), sinon ce
// --- point d'entree repose sa propre session sous PHPSESSID et perd tout
// --- ce que les autres y ont mis. Voir inc/sbsession.php.
require_once(__DIR__ . '/../inc/sbsession.php');
if (session_status() !== PHP_SESSION_ACTIVE) session_start(); // inclus par index.php : session déjà ouverte

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
$sbdb        = sbDb();
// Identité prise par un administrateur (point 73) : lecture seule, rien
// envoyé au nom de l'utilisateur, ses messages restent non lus
$sb_readonly = sbImpersonating();

// --------------------------------
// --- AJAX : compteur de messages non lus (pollé sur TOUTES les pages,
// --- pas seulement celle-ci, pour le badge topbar - voir navigation.tpl)
// --------------------------------
if (isset($_GET['ajax']) && $_GET['ajax'] == 'unread') {
	$row_unread = $sbdb->row("SELECT COUNT(*) AS cpt FROM {sb_messages} WHERE recipient_id = ? AND read_at = 0", array($sb_me));

	header('Content-Type: application/json');
	echo json_encode(array('count' => ($row_unread) ? intval($row_unread['cpt']) : 0));
	exit;
}

// --------------------------------
// --- AJAX : nouveaux messages d'un fil depuis un timestamp donné
// --- (polling "temps réel" pendant qu'un fil est ouvert)
// --------------------------------
if (isset($_GET['ajax']) && $_GET['ajax'] == 'thread') {
	$sb_with  = intval($_GET['with'] ?? 0);
	$sb_since = intval($_GET['since'] ?? 0);
	$sb_thread_rows = array();

	if ($sb_with > 0) {
		// Marque lu au passage (le poller tourne tant que le fil est ouvert)
		if (!$sb_readonly) $sbdb->update('sb_messages', array('read_at' => time()), 'sender_id = ? AND recipient_id = ? AND read_at = 0', array($sb_with, $sb_me));

		$sb_thread_rows = $sbdb->all("SELECT * FROM {sb_messages}
			WHERE ((sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?))
			AND created_at > ?
			ORDER BY created_at ASC", array($sb_me, $sb_with, $sb_with, $sb_me, $sb_since)) ?: array();
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
	$sb_with  = intval($_GET['with'] ?? 0);
	$sb_body  = $sbsanitize->displayText(file_get_contents('php://input'), 'UTF-8', 0, 1);
	$sb_body  = trim($sb_body);

	header('Content-Type: application/json');

	// Destinataire valide (existe, différent de soi-même) et message non vide
	$sb_dest_ok = ($sb_with > 0 && $sb_with != $sb_me && $sbdb->value("SELECT id FROM {sb_users} WHERE id = ?", array($sb_with)) !== null);

	if ($sb_dest_ok && $sb_body !== '' && !$sb_readonly) {
		$sb_now = time();
		$sb_new_id = $sbdb->insert('sb_messages', array('sender_id' => $sb_me, 'recipient_id' => $sb_with, 'message' => $sb_body, 'created_at' => $sb_now, 'read_at' => 0));

		echo json_encode(array(
			'ok'      => true,
			'message' => array(
				'id'         => (int) $sb_new_id,
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
	if (!$sb_readonly) $sbdb->update('sb_messages', array('read_at' => time()), 'sender_id = ? AND recipient_id = ? AND read_at = 0', array($sb_with, $sb_me));

	$sb_thread_rows = $sbdb->all("SELECT * FROM {sb_messages}
		WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?)
		ORDER BY created_at ASC", array($sb_me, $sb_with, $sb_with, $sb_me)) ?: array();

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
$sb_conv_rows = $sbdb->all("SELECT * FROM {sb_messages} WHERE sender_id = ? OR recipient_id = ? ORDER BY created_at DESC", array($sb_me, $sb_me)) ?: array();

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
