<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Blocage temporaire des tentatives de connexion
 *
 * Trop d'échecs sur un identifiant, ou depuis une adresse IP, dans une
 * fenêtre de temps : la connexion est refusée sans même vérifier le mot de
 * passe, pendant la durée de blocage. Les codes de double authentification
 * erronés comptent comme des échecs. Réglages > Blocage des tentatives.
 *
 * Complète l'anti-flood (Utilisateurs > IP bloquées), qui ne fait que
 * limiter la cadence des requêtes et dépend de Memcache : celui-ci compte
 * les échecs, en base.
 *
 *  - sbLoginLocked($login)    : blocage en cours ? (identifiant ou IP)
 *  - sbLoginFailed($login)    : enregistre un échec
 *  - sbLoginSucceeded($login) : efface les échecs de ce couple identifiant/IP
 *
 * Chargé par sbconfig.php (site) et inc/sbuiadmin-config.php (admin).
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (defined('SBUIADMIN_LOGINLOCK_LOADED')) return;
define('SBUIADMIN_LOGINLOCK_LOADED', true);

/** Réglages bornés : fenêtre et durée en secondes, seuils */
function sbLoginLockParams() {
	$int = function ($name, $default, $min, $max) {
		$v = sbSetting($name);
		return (ctype_digit($v) && (int) $v >= $min && (int) $v <= $max) ? (int) $v : $default;
	};
	return array(
		'enabled'   => sbSetting('login_lock_enabled', '1') === '1',
		'window'    => 60 * $int('login_lock_window', 15, 1, 1440),
		'duration'  => 60 * $int('login_lock_duration', 15, 1, 1440),
		'max_login' => $int('login_lock_max_login', 10, 1, 1000),
		'max_ip'    => $int('login_lock_max_ip', 20, 1, 10000),
	);
}

/** Adresse du visiteur : REMOTE_ADDR seulement (les en-têtes X-Forwarded-For se falsifient) */
function sbLoginLockIp() {
	return isset($_SERVER['REMOTE_ADDR']) ? substr((string) $_SERVER['REMOTE_ADDR'], 0, 45) : '';
}

function sbLoginLockNormalize($login) {
	$login = trim((string) $login);
	$login = function_exists('mb_strtolower') ? mb_strtolower($login, 'UTF-8') : strtolower($login);
	return function_exists('mb_substr') ? mb_substr($login, 0, 191, 'UTF-8') : substr($login, 0, 191);
}

/** Connexion + table (créée au besoin), ou null */
function sbLoginLockDb() {
	static $ready = null;
	$link = sbSettingsDb();
	if (!$link) return null;
	if ($ready === null) {
		$ready = (bool) @mysqli_query($link, "CREATE TABLE IF NOT EXISTS `" . sbLoginLockTable() . "` (
			`id` int unsigned NOT NULL AUTO_INCREMENT,
			`login` varchar(191) NOT NULL,
			`ip` varchar(45) NOT NULL,
			`created` int unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `login_created` (`login`, `created`),
			KEY `ip_created` (`ip`, `created`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	}
	return $ready ? $link : null;
}

function sbLoginLockTable() {
	$c = sbDbConfig();
	return $c['prefix'] . 'sb_login_attempts';
}

/**
 * Fin du blocage d'après une liste d'horodatages d'échecs : bloqué jusqu'à
 * (échec qui atteint le seuil dans la fenêtre) + durée.
 * @param int[] $times triés par ordre croissant
 * @return int horodatage de fin de blocage (0 = aucun)
 */
function sbLoginLockUntil(array $times, $max, $window, $duration) {
	$until = 0;
	for ($i = $max - 1, $n = count($times); $i < $n; $i++) {
		if ($times[$i] - $times[$i - $max + 1] <= $window) $until = max($until, $times[$i] + $duration);
	}
	return $until;
}

/** Horodatages des échecs récents pour une colonne (login ou ip) */
function sbLoginLockTimes($link, $column, $value, $since, $limit) {
	$stmt = mysqli_prepare($link, "SELECT `created` FROM `" . sbLoginLockTable() . "` WHERE `$column` = ? AND `created` >= ? ORDER BY `created` ASC LIMIT " . (int) $limit);
	if (!$stmt) return array();
	mysqli_stmt_bind_param($stmt, 'si', $value, $since);
	mysqli_stmt_execute($stmt);
	$created = null;
	mysqli_stmt_bind_result($stmt, $created);
	$times = array();
	while (mysqli_stmt_fetch($stmt)) $times[] = (int) $created;
	mysqli_stmt_close($stmt);
	return $times;
}

/**
 * Blocage en cours pour cet identifiant ou pour l'IP du visiteur ?
 * @return int secondes restantes (0 = pas de blocage)
 */
function sbLoginLocked($login) {
	$p = sbLoginLockParams();
	if (!$p['enabled']) return 0;
	$link = sbLoginLockDb();
	if (!$link) return 0;
	$now   = time();
	$since = $now - $p['window'] - $p['duration'];
	$until = 0;
	$login = sbLoginLockNormalize($login);
	if ($login !== '') {
		$until = sbLoginLockUntil(sbLoginLockTimes($link, 'login', $login, $since, 5000), $p['max_login'], $p['window'], $p['duration']);
	}
	$ip = sbLoginLockIp();
	if ($ip !== '') {
		$until = max($until, sbLoginLockUntil(sbLoginLockTimes($link, 'ip', $ip, $since, 20000), $p['max_ip'], $p['window'], $p['duration']));
	}
	return ($until > $now) ? $until - $now : 0;
}

/** Enregistre un échec (mauvais mot de passe, mauvais code 2FA...) */
function sbLoginFailed($login) {
	$p = sbLoginLockParams();
	if (!$p['enabled']) return;
	$link = sbLoginLockDb();
	if (!$link) return;
	$t     = sbLoginLockTable();
	$now   = time();
	$login = sbLoginLockNormalize($login);
	$ip    = sbLoginLockIp();
	$stmt  = mysqli_prepare($link, "INSERT INTO `$t` (`login`, `ip`, `created`) VALUES (?, ?, ?)");
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 'ssi', $login, $ip, $now);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
	// Ménage : au-delà d'une journée, plus aucun échec ne sert
	@mysqli_query($link, "DELETE FROM `$t` WHERE `created` < " . ($now - 86400 - $p['window'] - $p['duration']));
}

/** Connexion réussie : efface les échecs de ce couple identifiant/IP */
function sbLoginSucceeded($login) {
	$link = sbLoginLockDb();
	if (!$link) return;
	$login = sbLoginLockNormalize($login);
	$ip    = sbLoginLockIp();
	$stmt  = mysqli_prepare($link, "DELETE FROM `" . sbLoginLockTable() . "` WHERE `login` = ? AND `ip` = ?");
	if ($stmt) {
		mysqli_stmt_bind_param($stmt, 'ss', $login, $ip);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}
}
