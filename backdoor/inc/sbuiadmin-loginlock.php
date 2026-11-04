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

/** Adresse du visiteur : REMOTE_ADDR, ou celle transmise par un proxy déclaré (sbClientIp) */
function sbLoginLockIp() {
	if (function_exists('sbClientIp')) return substr(sbClientIp(), 0, 45);
	return isset($_SERVER['REMOTE_ADDR']) ? substr((string) $_SERVER['REMOTE_ADDR'], 0, 45) : '';
}

function sbLoginLockNormalize($login) {
	$login = trim((string) $login);
	$login = function_exists('mb_strtolower') ? mb_strtolower($login, 'UTF-8') : strtolower($login);
	return function_exists('mb_substr') ? mb_substr($login, 0, 191, 'UTF-8') : substr($login, 0, 191);
}

/** Connexion (sbDb) + table (créée au besoin), ou null */
function sbLoginLockDb() {
	static $ready = null;
	$db = function_exists('sbDb') ? sbDb() : null;
	if (!$db) return null;
	if ($ready === null) {
		try {
			sbdbschema::runScript($db, "CREATE TABLE IF NOT EXISTS `" . sbLoginLockTable() . "` (
				`id` int unsigned NOT NULL AUTO_INCREMENT,
				`login` varchar(191) NOT NULL,
				`ip` varchar(45) NOT NULL,
				`created` int unsigned NOT NULL,
				PRIMARY KEY (`id`),
				KEY `login_created` (`login`, `created`),
				KEY `ip_created` (`ip`, `created`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
			$ready = true;
		} catch (RuntimeException $e) {
			$ready = false;
		}
	}
	return $ready ? $db : null;
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
function sbLoginLockTimes($db, $column, $value, $since, $limit) {
	if (!in_array($column, array('login', 'ip'), true)) return array();
	$rows = $db->column('SELECT `created` FROM {sb_login_attempts} WHERE `' . $column . '` = ? AND `created` >= ? ORDER BY `created` ASC LIMIT ' . (int) $limit, array((string) $value, (int) $since));
	return array_map('intval', $rows ?: array());
}

/**
 * Blocage en cours pour cet identifiant ou pour l'IP du visiteur ?
 * @return int secondes restantes (0 = pas de blocage)
 */
function sbLoginLocked($login) {
	$p = sbLoginLockParams();
	if (!$p['enabled']) return 0;
	$db = sbLoginLockDb();
	if (!$db) return 0;
	$now   = time();
	$since = $now - $p['window'] - $p['duration'];
	$until = 0;
	$login = sbLoginLockNormalize($login);
	if ($login !== '') {
		$until = sbLoginLockUntil(sbLoginLockTimes($db, 'login', $login, $since, 5000), $p['max_login'], $p['window'], $p['duration']);
	}
	$ip = sbLoginLockIp();
	if ($ip !== '') {
		$until = max($until, sbLoginLockUntil(sbLoginLockTimes($db, 'ip', $ip, $since, 20000), $p['max_ip'], $p['window'], $p['duration']));
	}
	return ($until > $now) ? $until - $now : 0;
}

/** Enregistre un échec (mauvais mot de passe, mauvais code 2FA...) */
function sbLoginFailed($login) {
	$p = sbLoginLockParams();
	if (!$p['enabled']) return;
	$db = sbLoginLockDb();
	if (!$db) return;
	$now   = time();
	$db->insert('sb_login_attempts', array('login' => sbLoginLockNormalize($login), 'ip' => sbLoginLockIp(), 'created' => $now));
	// Ménage : au-delà d'une journée, plus aucun échec ne sert
	$db->delete('sb_login_attempts', '`created` < ?', array($now - 86400 - $p['window'] - $p['duration']));
}

/** Connexion réussie : efface les échecs de ce couple identifiant/IP */
function sbLoginSucceeded($login) {
	$db = sbLoginLockDb();
	if (!$db) return;
	$db->delete('sb_login_attempts', '`login` = ? AND `ip` = ?', array(sbLoginLockNormalize($login), sbLoginLockIp()));
}

/** Appel AJAX (ajax=… ou X-Requested-With) : sans session, réponse 401 en JSON, rien au journal */
function sbIsAjaxRequest() {
	return isset($_GET['ajax']) || strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest';
}

/** Vrai envoi du formulaire de connexion (champ vide compris) : seul cas journalisé en « mot de passe manquant » */
function sbLoginFormPosted() {
	return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && (isset($_POST['username']) || isset($_POST['password']));
}
