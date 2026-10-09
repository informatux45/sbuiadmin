<?php
/**
 * Admin Startbootstrap
 * Avancement d'une mise à jour en cours (JSON)
 *
 * Interrogé par la page Configuration > Mise à jour pendant que la requête
 * de mise à jour copie les fichiers. Volontairement autonome : ne charge
 * AUCUN fichier du CMS (ils sont en train d'être remplacés), seulement la
 * session. Réservé à l'administrateur qui a lancé l'opération : jeton de
 * session + chemin de l'état mémorisé en session par update.php.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Robots-Tag: noindex, nofollow');

require_once(__DIR__ . '/../inc/sbsession.php');
session_start(array('read_and_close' => true));

$sb_token = isset($_SESSION['sbupd_token']) ? (string) $_SESSION['sbupd_token'] : '';
$sb_state = isset($_SESSION['sbupd_state']) ? (string) $_SESSION['sbupd_state'] : '';
if ($sb_token === '' || $sb_state === '' || empty($_SESSION['sbuiadmin_user_name']) || empty($_SESSION['sb2fa_ok'])
	|| !hash_equals($sb_token, (string) ($_GET['t'] ?? ''))) {
	http_response_code(403);
	echo json_encode(array('error' => 'forbidden'));
	exit;
}

$job = @json_decode((string) @file_get_contents($sb_state), true);
if (!is_array($job)) {
	echo json_encode(array('status' => 'none'));
	exit;
}
$total = isset($job['total']) ? (int) $job['total'] : (isset($job['write']) ? count($job['write']) : 0);
echo json_encode(array(
	'status' => (string) ($job['status'] ?? ''),
	'phase'  => (string) ($job['phase'] ?? ''),
	'step'   => (string) ($job['step'] ?? ''),
	'done'   => (int) ($job['done'] ?? 0),
	'total'  => $total,
	'to'     => (string) ($job['to'] ?? ''),
));
