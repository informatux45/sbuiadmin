<?php
/**
 * Module stats : messages du traceur JS (inc/stats.js), en POST.
 *
 * Point d'entrée léger : configuration et base seulement (ni Smarty, ni
 * session, ni anti-flood : un visiteur envoie 2 ou 3 messages par page,
 * l'anti-flood le bloquerait). Sentinelle inspecte la requête comme les
 * autres (IP bannies, rafales).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	http_response_code(405);
	header('Allow: POST');
	exit;
}
if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 4096) {
	http_response_code(413);
	exit;
}

require_once(__DIR__ . '/../../../sbconfig.php');
error_reporting(0);
ini_set('display_errors', '0');

$code = 404;
try {
	if (function_exists('sbSiteInstalled') && sbSiteInstalled()) {
		require_once(SB_ADMIN_DIR . 'inc' . DIRECTORY_SEPARATOR . 'sbuiadmin-modules.php');
		if (sbModuleActive('stats')) {
			if (function_exists('sbSentinelleGuard')) sbSentinelleGuard('front');
			require_once(__DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'tracker.php');
			$db = stats_db();
			$ip = function_exists('sbClientIp') ? sbClientIp() : (string) ($_SERVER['REMOTE_ADDR'] ?? '');
			if ($db && !stats_excluded($_SERVER, $ip)) $code = stats_beacon($db, $_POST, $_SERVER, $ip);
			elseif ($db) $code = 204;
		}
	}
} catch (Throwable $e) {
	$code = 500;
}
http_response_code($code);
