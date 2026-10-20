<?php
/**
 * Module sentinelle : journal et inspection des requêtes
 * (chargé par backdoor/inc/sbuiadmin-sentinelle.php, sur le site et dans
 * l'administration, avant les modules). Accès base par sbSettingsDb() :
 * disponible avant $sbsql.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

/** Types d'événements connus : libellé du journal */
function sentinelle_types() {
	return array(
		'login_failed'  => 'Connexion refusée',
		'login_locked'  => 'Connexion verrouillée',
		'captcha_failed' => 'ALTCHA invalide',
		'twofa_failed'  => 'Code 2FA invalide',
		'csrf_failed'   => 'Jeton CSRF invalide',
		'forbidden'     => 'Accès refusé (droits)',
		'ip_blocked'    => 'IP bloquée',
		'flood'         => 'Anti-flood',
	);
}

/** Types toujours enregistrés, même répétés (chaque tentative compte) */
function sentinelle_types_every() {
	return array('login_failed', 'twofa_failed', 'captcha_failed');
}

/** Mode : observe (journal seulement) ou protect (les filtres bloquent) */
function sentinelle_mode() {
	return sbSetting('sentinelle_mode', 'observe') === 'protect' ? 'protect' : 'observe';
}

/** Durée de conservation du journal, en jours (1 à 3650) */
function sentinelle_retention() {
	$days = (int) sbSetting('sentinelle_retention', '90');
	return max(1, min(3650, $days ?: 90));
}

/** IP de confiance (jamais bloquées par Sentinelle) : plages normalisées */
function sentinelle_trusted() {
	list($ranges) = sbClientIpParseTrusted(sbSetting('sentinelle_trusted'));
	return $ranges;
}

/** $ip est-elle de confiance ? */
function sentinelle_is_trusted($ip = null) {
	return sbClientIpIsTrusted($ip ?? sbClientIp(), sentinelle_trusted());
}

/** Connexion et nom de la table du journal, ou null */
function sentinelle_db() {
	$db = function_exists('sbSettingsDb') ? sbSettingsDb() : null;
	if (!($db instanceof mysqli)) return null;
	$c = function_exists('sbDbConfig') ? sbDbConfig() : array();
	return array($db, str_replace('`', '', (string) ($c['prefix'] ?? '') . 'sb_sentinelle_log'));
}

/** Texte coupé à $max caractères (UTF-8), sans caractères de contrôle */
function sentinelle_cut($text, $max) {
	$text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', (string) $text);
	return mb_substr($text, 0, $max, 'UTF-8');
}

/**
 * Enregistre un événement. Un même type pour une même IP n'est noté qu'une
 * fois par 10 secondes (sauf sentinelle_types_every()) : un robot qui insiste
 * ne remplit pas la base.
 */
function sentinelle_event($type, $severity = 'warning', $detail = '', $action = 'logged', $user = '') {
	$dbt = sentinelle_db();
	if (!$dbt) return false;
	list($db, $table) = $dbt;
	$type     = preg_match('/^[a-z0-9_]{1,32}$/', $type) ? $type : 'other';
	$severity = in_array($severity, array('info', 'warning', 'critical'), true) ? $severity : 'warning';
	$action   = in_array($action, array('logged', 'blocked', 'banned'), true) ? $action : 'logged';
	$ip       = substr(sbClientIp(), 0, 45);
	$now      = date('Y-m-d H:i:s');

	if (!in_array($type, sentinelle_types_every(), true)) {
		$since = date('Y-m-d H:i:s', time() - 10);
		$stmt = mysqli_prepare($db, "SELECT id FROM `$table` WHERE ip = ? AND type = ? AND created_at >= ? LIMIT 1");
		if (!$stmt) return false;
		mysqli_stmt_bind_param($stmt, 'sss', $ip, $type, $since);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		$dup = mysqli_stmt_num_rows($stmt) > 0;
		mysqli_stmt_close($stmt);
		if ($dup) return true;
	}

	$context = $GLOBALS['sentinelle_context']
		?? ((defined('SBADMIN') && strpos((string) ($_SERVER['SCRIPT_NAME'] ?? ''), '/' . SBADMIN . '/') !== false) ? 'admin' : 'front');
	$method  = sentinelle_cut($_SERVER['REQUEST_METHOD'] ?? '', 10);
	$uri     = sentinelle_cut($_SERVER['REQUEST_URI'] ?? '', 255);
	$agent   = sentinelle_cut($_SERVER['HTTP_USER_AGENT'] ?? '', 255);
	$user    = sentinelle_cut($user, 64);
	$detail  = sentinelle_cut($detail, 2000);
	$stmt = mysqli_prepare($db, "INSERT INTO `$table` (created_at, ip, type, severity, action, context, method, uri, user_agent, user, detail) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	if (!$stmt) return false;
	mysqli_stmt_bind_param($stmt, 'sssssssssss', $now, $ip, $type, $severity, $action, $context, $method, $uri, $agent, $user, $detail);
	$ok = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	// Purge des lignes trop anciennes, de temps en temps
	if ($ok && mt_rand(1, 100) === 1) sentinelle_purge_old();
	return $ok;
}

/** Supprime les lignes plus anciennes que la durée de conservation */
function sentinelle_purge_old() {
	$dbt = sentinelle_db();
	if (!$dbt) return false;
	list($db, $table) = $dbt;
	$limit = date('Y-m-d H:i:s', time() - sentinelle_retention() * 86400);
	$stmt = mysqli_prepare($db, "DELETE FROM `$table` WHERE created_at < ?");
	if (!$stmt) return false;
	mysqli_stmt_bind_param($stmt, 's', $limit);
	$ok = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	return $ok;
}

/**
 * Inspection de la requête ('front' ou 'admin'). Lot 1 : retient le
 * contexte des événements ; les filtres d'attaques viendront s'y brancher.
 */
function sentinelle_guard($context) {
	$GLOBALS['sentinelle_context'] = $context === 'admin' ? 'admin' : 'front';
}
