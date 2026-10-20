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
		'ip_banned'     => 'IP bannie',
		'dos'           => 'Rafale de requêtes',
		'fake_bot'      => 'Faux robot',
		'auto_ban'      => 'Bannissement automatique',
		'integrity'     => 'Intégrité des fichiers',
	) + sentinelle_rules();
}

/** Filtres d'attaques (lot 2) : type => libellé */
function sentinelle_rules() {
	return array(
		'sqli'          => 'Injection SQL',
		'xss'           => 'Script injecté (XSS)',
		'traversal'     => 'Remontée de dossier (../)',
		'inclusion'     => 'Inclusion de fichier',
		'null_byte'     => 'Caractère nul',
		'contamination' => 'Variables globales visées',
		'scanner'       => 'Scanner de failles',
		'bad_agent'     => "Outil d'attaque",
		'bad_upload'    => 'Fichier dangereux envoyé',
	);
}

/**
 * Réglage d'un filtre : off (inactif), log (noté, jamais bloqué) ou
 * on (selon le mode : noté en observation, bloqué en protection)
 */
function sentinelle_rule_action($rule) {
	static $map = null, $raw = null;
	if ($map === null || $raw !== sbSetting('sentinelle_rules')) {
		$map = array();
		$raw = sbSetting('sentinelle_rules');
		foreach (explode(',', $raw) as $pair) {
			$kv = explode('=', trim($pair), 2);
			if (count($kv) === 2 && in_array($kv[1], array('off', 'log', 'on'), true)) $map[$kv[0]] = $kv[1];
		}
	}
	return $map[$rule] ?? 'on';
}

/** Paramètres jamais inspectés (réglage sentinelle_exclude : un nom par ligne) */
function sentinelle_excluded() {
	$out = array();
	foreach (preg_split('/[\s,;]+/', sbSetting('sentinelle_exclude')) as $name) {
		if ($name !== '' && preg_match('/^[A-Za-z0-9_\-\[\]]{1,64}$/', $name)) $out[$name] = true;
	}
	return $out;
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

/** Connexion et nom de la table du journal (ou de $table), ou null */
function sentinelle_db($table = 'sb_sentinelle_log') {
	$db = function_exists('sbSettingsDb') ? sbSettingsDb() : null;
	if (!($db instanceof mysqli)) return null;
	$c = function_exists('sbDbConfig') ? sbDbConfig() : array();
	return array($db, str_replace('`', '', (string) ($c['prefix'] ?? '') . $table));
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
	if ($ok) sentinelle_alert_maybe($severity);
	if ($ok) sentinelle_autoban_maybe($ip, $type);
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
 * Inspection de la requête ('front' ou 'admin') : filtres d'attaques.
 * Exemptés : IP de confiance, utilisateurs connectés à l'administration.
 * Un seul événement par requête (le plus grave) ; en mode protection, un
 * filtre réglé sur « on » répond 403 et arrête la page.
 */
function sentinelle_guard($context) {
	$context = $context === 'admin' ? 'admin' : 'front';
	$GLOBALS['sentinelle_context'] = $context;
	sentinelle_digest_maybe();
	if ($context === 'admin') sentinelle_integrity_maybe();
	if (sentinelle_is_trusted() || sentinelle_backoffice_user()) return;
	// IP bannie (réglage délibéré : bloquée quel que soit le mode)
	$ban = sentinelle_banned(sbClientIp());
	if ($ban) {
		sentinelle_ban_hit((int) $ban['id']);
		sentinelle_event('ip_banned', 'info', 'Bannie : ' . $ban['ip_range'] . ($ban['reason'] !== '' ? ' (' . $ban['reason'] . ')' : ''), 'blocked');
		sentinelle_block();
	}
	// Rafales de requêtes (une minute glissante par paliers)
	$rate = (int) sbSetting('sentinelle_rate', '120');
	if ($rate > 0) {
		$ip = sbClientIp();
		$bot = sentinelle_bot_check($ip, (string) ($_SERVER['HTTP_USER_AGENT'] ?? ''));
		if ($bot === false) sentinelle_event('fake_bot', 'warning', 'Se dit robot d\'un moteur, DNS non conforme : ' . sentinelle_excerpt((string) ($_SERVER['HTTP_USER_AGENT'] ?? '')), 'logged');
		if ($bot !== true && sentinelle_counter('rate|' . $ip, 60) > $rate) {
			$protect = sentinelle_mode() === 'protect';
			sentinelle_event('dos', 'warning', 'Plus de ' . $rate . ' requêtes en une minute', $protect ? 'blocked' : 'logged');
			if ($protect) {
				$duration = sentinelle_autoban_duration($ip);
				if (!sentinelle_banned($ip) && sentinelle_ban_add($ip, $duration, 'Automatique : plus de ' . $rate . ' requêtes par minute', 'auto') === '') {
					sentinelle_event('auto_ban', 'critical', 'Bannie ' . ($duration >= 86400 ? round($duration / 86400) . ' jour(s)' : round($duration / 60) . ' min') . ' : plus de ' . $rate . ' requêtes par minute', 'banned');
				}
				sentinelle_block();
			}
		}
	}
	$hit = sentinelle_inspect($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES, $context, sentinelle_excluded());
	if (!$hit) return;
	list($rule, $severity, $detail) = $hit;
	$action = sentinelle_rule_action($rule);
	if ($action === 'off') return;
	$block = $action === 'on' && sentinelle_mode() === 'protect';
	sentinelle_event($rule, $severity, $detail, $block ? 'blocked' : 'logged');
	if ($block) sentinelle_block();
}

/** Utilisateur connecté à l'administration (double authentification validée si active) */
function sentinelle_backoffice_user() {
	if (session_status() !== PHP_SESSION_ACTIVE) return false;
	$user = (string) ($_SESSION['sbuiadmin_user_name'] ?? '');
	if ($user === '') return false;
	if (function_exists('sbSettingBool') && sbSettingBool('twofa_enabled')
		&& !is_file(SB_PATH . (defined('SBADMIN') ? SBADMIN : 'backdoor') . '/inc/admin/2fa-disabled')) {
		return !empty($_SESSION['sb2fa_ok']) && hash_equals((string) $_SESSION['sb2fa_ok'], $user);
	}
	return true;
}

/** Réponse à une requête bloquée : 403, sans détail */
function sentinelle_block() {
	if (!headers_sent()) {
		http_response_code(403);
		header('Content-Type: text/html; charset=UTF-8');
		header('Cache-Control: no-store');
	}
	echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><meta name="robots" content="noindex">'
		. '<meta name="viewport" content="width=device-width, initial-scale=1"><title>Requête refusée</title></head>'
		. '<body style="font-family:system-ui,sans-serif;text-align:center;padding:12vh 16px;color:#1e293b">'
		. '<h1 style="font-size:28px">Requête refusée</h1><p>Cette demande a été bloquée par la protection du site.</p></body></html>';
	exit;
}

/** Valeur normalisée pour la recherche : décodée (URL, entités), minuscules */
function sentinelle_normalize($value) {
	$v = (string) $value;
	for ($i = 0; $i < 2; $i++) {
		$d = rawurldecode($v);
		if ($d === $v) break;
		$v = $d;
	}
	$v = html_entity_decode($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	return strtolower($v);
}

/** Paramètres à plat : chemin => valeur (profondeur 10, 500 valeurs, 64 Ko par valeur) */
function sentinelle_flatten($data, $prefix, array &$out, $depth = 0) {
	if (!is_array($data) || $depth > 10) return;
	foreach ($data as $k => $v) {
		if (count($out) >= 500) return;
		$path = $prefix === '' ? (string) $k : $prefix . '[' . $k . ']';
		if (is_array($v)) sentinelle_flatten($v, $path, $out, $depth + 1);
		else $out[] = array((string) $k, $path, substr((string) $v, 0, 65536));
	}
}

/** Extrait lisible d'une valeur pour le journal */
function sentinelle_excerpt($value) {
	$v = preg_replace('/\s+/', ' ', (string) $value);
	return mb_strlen($v, 'UTF-8') > 120 ? mb_substr($v, 0, 120, 'UTF-8') . '…' : $v;
}

/** Signatures : règle => array(gravité, motifs sur la valeur normalisée) */
function sentinelle_signatures() {
	return array(
		'inclusion' => array('critical', array(
			'~\b(php|expect|phar|zip|glob|input|zlib|compress\.zlib)://~',
			'~\bdata://~',
			'~(^|[/\\\\])(etc/(passwd|shadow|hosts)|proc/self/(environ|cmdline)|windows/win\.ini)\b~',
		)),
		'sqli' => array('critical', array(
			'~\bunion\b(\s|/\*.*?\*/|\+)+(all(\s|/\*.*?\*/|\+)+)?select\b~s',
			'~\b(sleep|benchmark|pg_sleep|extractvalue|updatexml)\s*\(~',
			'~\bwaitfor\s+delay\s+\'~',
			'~\binformation_schema\b~',
			'~\bload_file\s*\(~',
			'~\binto\s+(out|dump)file\b~',
			'~[\'"]\s*(or|and|\|\||&&)\s*[\'"]?\w+[\'"]?\s*(=|like)\s*[\'"]?\w+~',
			'~\b(or|and)\s+\d+\s*=\s*\d+\s*(--|#|/\*)~',
			'~;\s*(drop|truncate|alter|delete\s+from|insert\s+into|update\s+\w+\s+set)\b~',
			'~/\*!\d*~',
		)),
		'xss' => array('warning', array(
			'~<\s*script\b~',
			'~<\s*(iframe|object|embed|svg|math|base|meta)\b[^>]*>~',
			'~<[a-z][a-z0-9]*\b[^>]*[\s/"\']on[a-z]{3,}\s*=~',
			'~\bjavascript\s*:~',
			'~\bvbscript\s*:~',
			'~\bdocument\s*\.\s*(cookie|domain|write)\b~',
			'~\bsrcdoc\s*=~',
		)),
		'traversal' => array('warning', array(
			'~(^|[/\\\\=])\.\.[/\\\\]~',
		)),
	);
}

/** Chemins sondés par les scanners (sur le site) */
function sentinelle_scanner_paths() {
	return '~(^|/)(wp-login\.php|wp-admin/|wp-content/|wp-includes/|xmlrpc\.php|\.env(\.|$)|\.git/|\.svn/|\.aws/|\.ds_store'
		. '|phpmyadmin|pma/|adminer(-[\d.]+)?\.php|phpinfo\.php|vendor/phpunit/|cgi-bin/|boaform/|actuator/|solr/admin'
		. '|(shell|c99|r57|wso|alfa)\.php|config\.php\.(bak|old|save)|\.(sql|bak|old|swp)$)~i';
}

/** Navigateurs déclarés par des outils d'attaque */
function sentinelle_bad_agents() {
	return '~(sqlmap|nikto|nmap|masscan|acunetix|netsparker|wpscan|nuclei|dirbuster|gobuster|feroxbuster|ffuf|zgrab|havij|w3af|openvas|nessus|jaeles|commix|xsstrike)~i';
}

/**
 * Inspecte une requête (testable : tableaux passés en paramètres)
 * @return array|null array(règle, gravité, détail) du filtre le plus grave
 */
function sentinelle_inspect(array $server, array $get, array $post, array $cookie, array $files, $context, array $excluded = array()) {
	$found = array();
	$add = function ($rule, $severity, $detail) use (&$found) {
		if (sentinelle_rule_action($rule) !== 'off') $found[] = array($rule, $severity, $detail);
	};

	// Variables globales visées par les noms de paramètres
	foreach (array('GET' => $get, 'POST' => $post, 'COOKIE' => $cookie) as $src => $data) {
		foreach (array_keys($data) as $k) {
			if (preg_match('/^(GLOBALS|_SESSION|_SERVER|_COOKIE|_ENV|_FILES|_REQUEST|_GET|_POST|HTTP_\w+_VARS)$/', (string) $k)) {
				$add('contamination', 'critical', "$src $k");
				break;
			}
		}
	}

	// Outils d'attaque
	$agent = (string) ($server['HTTP_USER_AGENT'] ?? '');
	if ($agent !== '' && preg_match(sentinelle_bad_agents(), $agent, $m)) $add('bad_agent', 'warning', 'Navigateur : ' . sentinelle_excerpt($agent));

	// Adresse demandée (chemin brut : %00, ../, scanners)
	$uri = (string) ($server['REQUEST_URI'] ?? '');
	$path = (string) parse_url('http://x' . (strpos($uri, '/') === 0 ? $uri : '/' . $uri), PHP_URL_PATH);
	if ($context === 'front' && $path !== '' && preg_match(sentinelle_scanner_paths(), rawurldecode($path))) $add('scanner', 'warning', 'Adresse : ' . sentinelle_excerpt($path));
	if (stripos($uri, '%00') !== false) $add('null_byte', 'critical', 'Adresse : ' . sentinelle_excerpt($uri));

	// Valeurs des paramètres
	$values = array(); $gv = $cv = $pv = array();
	sentinelle_flatten($get, '', $gv);
	foreach ($gv as $e) $values[] = array('GET', $e);
	sentinelle_flatten($cookie, '', $cv);
	foreach ($cv as $e) $values[] = array('COOKIE', $e);
	sentinelle_flatten($post, '', $pv);
	foreach ($pv as $e) $values[] = array('POST', $e);
	$sigs = sentinelle_signatures();
	foreach ($values as $item) {
		list($src, list($key, $name, $raw)) = $item;
		if (isset($excluded[$key]) || isset($excluded[$name])) continue;
		// Mots de passe, jetons, secrets : jamais inspectés ni recopiés dans le journal
		if (preg_match('/pass|pwd|secret|token|altcha|csrf/i', $key)) continue;
		if (strpos($raw, "\0") !== false) { $add('null_byte', 'critical', "$src $name"); continue; }
		$v = sentinelle_normalize($raw);
		foreach ($sigs as $rule => list($severity, $patterns)) {
			// Remontée de dossier : adresse et cookies seulement (un texte envoyé peut citer « ../ »)
			if ($rule === 'traversal' && $src === 'POST') continue;
			foreach ($patterns as $re) {
				if (preg_match($re, $v)) { $add($rule, $severity, "$src $name = " . sentinelle_excerpt($raw)); continue 3; }
			}
		}
	}

	// Fichiers envoyés : extension exécutable ou code PHP caché
	$list = array();
	foreach ($files as $field => $f) {
		if (!is_array($f) || !isset($f['name'])) continue;
		$names = (array) $f['name']; $tmps = (array) ($f['tmp_name'] ?? array());
		array_walk_recursive($names, function ($n, $i) use (&$list, $tmps, $field) { $list[] = array($field, (string) $n, (string) ($tmps[$i] ?? '')); });
	}
	foreach ($list as list($field, $name, $tmp)) {
		if (preg_match('/\.(php\d?|phtml|pht|phar|phps|cgi|pl|asp|aspx|jsp|sh)(\.|$)/i', $name) || strpos($name, "\0") !== false) {
			$add('bad_upload', 'critical', "Fichier $field : " . sentinelle_excerpt($name));
			continue;
		}
		if ($tmp !== '' && is_file($tmp) && is_readable($tmp)) {
			$head = (string) @file_get_contents($tmp, false, null, 0, 262144);
			if (preg_match('/<\?(php|=)/i', $head)) $add('bad_upload', 'critical', "Code PHP dans le fichier $field : " . sentinelle_excerpt($name));
		}
	}

	if (!$found) return null;
	$rank = array('critical' => 3, 'warning' => 2, 'info' => 1);
	usort($found, function ($a, $b) use ($rank) { return $rank[$b[1]] <=> $rank[$a[1]]; });
	return $found[0];
}

// -----------------------------------------------------------------------
// Bannissements (table sb_sentinelle_ban) : adresse ou plage CIDR,
// durée (expires_at) ou permanent (NULL)
// -----------------------------------------------------------------------

/** Bannissements en cours (lus une fois par requête) */
function sentinelle_bans_active($reset = false) {
	static $rows = null;
	if ($reset) $rows = null;
	if ($rows !== null) return $rows;
	$rows = array();
	$dbt = sentinelle_db('sb_sentinelle_ban');
	if (!$dbt) return $rows;
	list($db, $table) = $dbt;
	$res = @mysqli_query($db, "SELECT * FROM `$table` WHERE expires_at IS NULL OR expires_at > '" . date('Y-m-d H:i:s') . "' ORDER BY id DESC");
	while ($res && ($r = mysqli_fetch_assoc($res))) $rows[] = $r;
	return $rows;
}

/** Bannissement qui couvre $ip, ou null */
function sentinelle_banned($ip) {
	foreach (sentinelle_bans_active() as $ban) {
		if (sbClientIpInRange($ip, $ban['ip_range'])) return $ban;
	}
	return null;
}

/** Compte une tentative d'une IP bannie */
function sentinelle_ban_hit($id) {
	$dbt = sentinelle_db('sb_sentinelle_ban');
	if (!$dbt) return;
	list($db, $table) = $dbt;
	$stmt = mysqli_prepare($db, "UPDATE `$table` SET hits = hits + 1, last_hit = ? WHERE id = ?");
	if (!$stmt) return;
	$now = date('Y-m-d H:i:s');
	mysqli_stmt_bind_param($stmt, 'si', $now, $id);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

/**
 * Bannit une adresse ou une plage (remplace un bannissement de la même plage)
 * @param int $duration secondes, 0 = permanent
 * @return string '' si fait, sinon le motif du refus
 */
function sentinelle_ban_add($range, $duration, $reason = '', $source = 'manual') {
	$range = sbClientIpNormalizeRange($range);
	if ($range === '') return 'Adresse ou plage invalide';
	$parts = explode('/', $range);
	if (isset($parts[1]) && (int) $parts[1] < (strpos($parts[0], ':') !== false ? 32 : 16)) return 'Plage trop large (au plus /16 en IPv4, /32 en IPv6)';
	foreach (sentinelle_trusted() as $t) {
		if (sbClientIpInRange(explode('/', $t)[0], $range) || sbClientIpInRange($parts[0], $t)) return 'Cette plage contient une IP de confiance';
	}
	if (sbClientIpInRange(sbClientIp(), $range) && $source === 'manual') return 'Cette plage contient votre propre adresse';
	$dbt = sentinelle_db('sb_sentinelle_ban');
	if (!$dbt) return 'Base indisponible';
	list($db, $table) = $dbt;
	$now = date('Y-m-d H:i:s');
	$expires = (int) $duration > 0 ? date('Y-m-d H:i:s', time() + (int) $duration) : null;
	$reason = sentinelle_cut($reason, 255);
	$source = $source === 'auto' ? 'auto' : 'manual';
	$stmt = mysqli_prepare($db, "DELETE FROM `$table` WHERE ip_range = ?");
	if ($stmt) { mysqli_stmt_bind_param($stmt, 's', $range); mysqli_stmt_execute($stmt); mysqli_stmt_close($stmt); }
	$stmt = mysqli_prepare($db, "INSERT INTO `$table` (ip_range, reason, source, created_at, expires_at, hits) VALUES (?, ?, ?, ?, ?, 0)");
	if (!$stmt) return 'Base indisponible';
	mysqli_stmt_bind_param($stmt, 'sssss', $range, $reason, $source, $now, $expires);
	$ok = mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	sentinelle_bans_active(true);
	return $ok ? '' : 'Enregistrement impossible';
}

/** Lève un bannissement */
function sentinelle_ban_remove($id) {
	$dbt = sentinelle_db('sb_sentinelle_ban');
	if (!$dbt) return false;
	list($db, $table) = $dbt;
	$stmt = mysqli_prepare($db, "DELETE FROM `$table` WHERE id = ?");
	if (!$stmt) return false;
	mysqli_stmt_bind_param($stmt, 'i', $id);
	$ok = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0;
	mysqli_stmt_close($stmt);
	sentinelle_bans_active(true);
	return $ok;
}

// -----------------------------------------------------------------------
// Alertes par e-mail : au plus une toutes les 15 minutes (événements de la
// gravité réglée ou plus), résumé quotidien en option. Envoyées après la
// réponse au visiteur (register_shutdown_function).
// -----------------------------------------------------------------------

/** Destinataire des alertes, ou '' */
function sentinelle_alert_email() {
	$to = sbSetting('sentinelle_alert_email');
	return filter_var($to, FILTER_VALIDATE_EMAIL) ? $to : '';
}

/** Une alerte est-elle due pour cette gravité ? (programme l'envoi) */
function sentinelle_alert_maybe($severity) {
	$level = sbSetting('sentinelle_alert_level', 'critical');
	$rank = array('info' => 1, 'warning' => 2, 'critical' => 3);
	if (!isset($rank[$level]) || sentinelle_alert_email() === '' || ($rank[$severity] ?? 0) < $rank[$level]) return false;
	$last = (int) sbSetting('sentinelle_alert_last', '0');
	if (time() - $last < 900 || !empty($GLOBALS['sentinelle_alert_pending'])) return false;
	$GLOBALS['sentinelle_alert_pending'] = true;
	// Noté tout de suite : deux requêtes simultanées n'envoient pas deux alertes
	if (function_exists('sbSettingsSave')) sbSettingsSave(array('sentinelle_alert_last' => (string) time()));
	register_shutdown_function('sentinelle_deferred', 'sentinelle_send_alert', array($last, $level));
	return true;
}

/** Résumé quotidien dû ? (une fois par jour, après 7 h) */
function sentinelle_digest_maybe() {
	if (sbSetting('sentinelle_digest') !== '1' || sentinelle_alert_email() === '' || (int) date('G') < 7) return false;
	$today = date('Y-m-d');
	if (sbSetting('sentinelle_digest_last') === $today || !empty($GLOBALS['sentinelle_digest_pending'])) return false;
	$GLOBALS['sentinelle_digest_pending'] = true;
	if (function_exists('sbSettingsSave')) sbSettingsSave(array('sentinelle_digest_last' => $today));
	register_shutdown_function('sentinelle_deferred', 'sentinelle_send_digest', array());
	return true;
}

/** Analyse d'intégrité quotidienne (visite de l'administration, après la réponse) */
function sentinelle_integrity_maybe() {
	if (sbSetting('sentinelle_integrity_auto', '1') !== '1' || !defined('SBUIADMIN_PATH') || !is_dir((string) SBUIADMIN_PATH)) return false;
	$today = date('Y-m-d');
	if (sbSetting('sentinelle_integrity_last') === $today || !empty($GLOBALS['sentinelle_integrity_pending'])) return false;
	$GLOBALS['sentinelle_integrity_pending'] = true;
	if (function_exists('sbSettingsSave')) sbSettingsSave(array('sentinelle_integrity_last' => $today));
	register_shutdown_function('sentinelle_deferred', 'sentinelle_integrity_deferred', array());
	return true;
}

function sentinelle_integrity_deferred() {
	sentinelle_finish_response();
	@set_time_limit(120);
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'integrity.php';
	sentinelle_integrity_run();
}

/** Événements depuis $since (gravité >= $level), les plus récents d'abord */
function sentinelle_events_since($since, $level = 'info', $limit = 50) {
	$dbt = sentinelle_db();
	if (!$dbt) return array();
	list($db, $table) = $dbt;
	$levels = array('info' => "('info','warning','critical')", 'warning' => "('warning','critical')", 'critical' => "('critical')");
	$stmt = mysqli_prepare($db, "SELECT * FROM `$table` WHERE created_at >= ? AND severity IN " . ($levels[$level] ?? $levels['info']) . " ORDER BY id DESC LIMIT " . (int) $limit);
	if (!$stmt) return array();
	mysqli_stmt_bind_param($stmt, 's', $since);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	$rows = array();
	while ($res && ($r = mysqli_fetch_assoc($res))) $rows[] = $r;
	mysqli_stmt_close($stmt);
	return $rows;
}

/** Tableau HTML des événements (échappé) */
function sentinelle_events_html(array $rows) {
	$types = sentinelle_types();
	$h = '<table cellpadding="6" cellspacing="0" border="1" style="border-collapse:collapse;font-family:sans-serif;font-size:13px">'
		. '<tr style="background:#f1f5f9"><th>Date</th><th>IP</th><th>Événement</th><th>Gravité</th><th>Action</th><th>Adresse</th></tr>';
	foreach ($rows as $r) {
		$h .= '<tr><td>' . htmlspecialchars($r['created_at']) . '</td><td>' . htmlspecialchars($r['ip']) . '</td><td>'
			. htmlspecialchars($types[$r['type']] ?? $r['type']) . '</td><td>' . htmlspecialchars($r['severity']) . '</td><td>'
			. htmlspecialchars($r['action']) . '</td><td>' . htmlspecialchars(mb_substr($r['method'] . ' ' . $r['uri'], 0, 120, 'UTF-8')) . '</td></tr>';
	}
	return $h . '</table>';
}

/** Envoi d'un e-mail (PHPMailer du site, sinon mail()) */
function sentinelle_mail($subject, $html) {
	$to = sentinelle_alert_email();
	if ($to === '') return false;
	$site = defined('_AM_SITE_TITLE') && _AM_SITE_TITLE !== '' ? _AM_SITE_TITLE : (string) ($_SERVER['SERVER_NAME'] ?? 'Site');
	if (function_exists('sb2faSender')) $from = sb2faSender();
	elseif (defined('SBFROMEMAIL') && filter_var(SBFROMEMAIL, FILTER_VALIDATE_EMAIL)) $from = SBFROMEMAIL;
	else $from = 'noreply@' . preg_replace('/^www\./', '', (string) ($_SERVER['SERVER_NAME'] ?? 'localhost'));
	$subject = '[Sentinelle] ' . $site . ' : ' . $subject;
	try {
		$mail = function_exists('sbMailer') ? sbMailer() : null;
		if ($mail) {
			$mail->setFrom($from, $site);
			$mail->addAddress($to);
			$mail->Subject = $subject;
			$mail->msgHTML($html);
			return (bool) $mail->send();
		}
	} catch (Throwable $e) {
		return false;
	}
	$headers = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\nFrom: " . $from . "\r\n";
	return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $html, $headers);
}

/** Envoi différé (fin de requête) : une erreur n'y est jamais fatale */
function sentinelle_deferred($fn, array $args) {
	try {
		call_user_func_array($fn, $args);
	} catch (Throwable $e) {
	}
}

/** Libère le visiteur avant un envoi lent */
function sentinelle_finish_response() {
	if (function_exists('fastcgi_finish_request')) @fastcgi_finish_request();
}

/** Alerte : événements de la gravité réglée depuis la dernière alerte */
function sentinelle_send_alert($last, $level) {
	sentinelle_finish_response();
	$since = date('Y-m-d H:i:s', max((int) $last, time() - 86400));
	$rows = sentinelle_events_since($since, $level, 50);
	if (!$rows) return false;
	$html = '<p>Sentinelle a relevé ' . count($rows) . ' événement(s) ' . ($level === 'critical' ? 'critique(s)' : 'de sécurité')
		. ' depuis le ' . htmlspecialchars($since) . ' (mode ' . (sentinelle_mode() === 'protect' ? 'protection' : 'observation') . ').</p>'
		. sentinelle_events_html($rows) . '<p>Détail : Sentinelle &gt; Journal de sécurité, dans l\'administration.</p>';
	return sentinelle_mail(count($rows) . ' événement(s) de sécurité', $html);
}

/** Résumé de la veille */
function sentinelle_send_digest() {
	sentinelle_finish_response();
	$from = date('Y-m-d 00:00:00', time() - 86400);
	$dbt = sentinelle_db();
	if (!$dbt) return false;
	list($db, $table) = $dbt;
	$to = date('Y-m-d 00:00:00');
	$res = mysqli_query($db, "SELECT type, severity, COUNT(*) AS n, COUNT(DISTINCT ip) AS ips FROM `$table` WHERE created_at >= '$from' AND created_at < '$to' GROUP BY type, severity ORDER BY n DESC");
	$types = sentinelle_types();
	$lines = ''; $total = 0;
	while ($res && ($r = mysqli_fetch_assoc($res))) {
		$total += (int) $r['n'];
		$lines .= '<tr><td>' . htmlspecialchars($types[$r['type']] ?? $r['type']) . '</td><td>' . htmlspecialchars($r['severity']) . '</td><td>' . (int) $r['n'] . '</td><td>' . (int) $r['ips'] . '</td></tr>';
	}
	$html = '<p>Résumé de la journée du ' . date('d/m/Y', time() - 86400) . ' : <strong>' . $total . '</strong> événement(s).</p>'
		. ($total ? '<table cellpadding="6" cellspacing="0" border="1" style="border-collapse:collapse;font-family:sans-serif;font-size:13px"><tr style="background:#f1f5f9"><th>Événement</th><th>Gravité</th><th>Nombre</th><th>IP</th></tr>' . $lines . '</table>' : '<p>Aucun événement.</p>')
		. '<p>Bannissements en cours : ' . count(sentinelle_bans_active()) . '.</p>';
	return sentinelle_mail('résumé du ' . date('d/m/Y', time() - 86400), $html);
}

// -----------------------------------------------------------------------
// Compteurs (rafales, robots vérifiés) : petits fichiers dans
// datas/cache/sentinelle/ (aucun Memcache ni APCu requis)
// -----------------------------------------------------------------------

/** Dossier des compteurs (créé au besoin, interdit au web), ou '' */
function sentinelle_cache_dir() {
	static $dir = null;
	if ($dir !== null) return $dir;
	$base = (defined('SB_PATH') ? rtrim(SB_PATH, '/\\') : dirname(SBUIADMIN_PATH)) . '/datas/cache/sentinelle';
	if (!is_dir($base)) @mkdir($base, 0755, true);
	if (is_dir($base) && !is_file($base . '/.htaccess')) @file_put_contents($base . '/.htaccess', "Require all denied\n");
	$dir = (is_dir($base) && is_writable($base)) ? $base : '';
	return $dir;
}

/**
 * Compte un passage : nombre de passages de $key dans la tranche de $window
 * secondes en cours (0 si le compteur est indisponible)
 */
function sentinelle_counter($key, $window) {
	$dir = sentinelle_cache_dir();
	if ($dir === '') return 0;
	$file = $dir . '/' . sha1($key) . '.cnt';
	$slot = intdiv(time(), max(1, (int) $window));
	$fh = @fopen($file, 'c+');
	if (!$fh) return 0;
	flock($fh, LOCK_EX);
	$data = explode(' ', trim((string) stream_get_contents($fh)));
	$n = (count($data) === 2 && (int) $data[0] === $slot) ? (int) $data[1] + 1 : 1;
	ftruncate($fh, 0);
	rewind($fh);
	fwrite($fh, $slot . ' ' . $n);
	flock($fh, LOCK_UN);
	fclose($fh);
	// Ménage : compteurs de plus d'un jour, de temps en temps
	if (mt_rand(1, 500) === 1) foreach ((array) @glob($dir . '/*.cnt') as $f) if (@filemtime($f) < time() - 86400) @unlink($f);
	return $n;
}

/** Valeur gardée $ttl secondes ('' si absente ou périmée) */
function sentinelle_cache_get($key, $ttl) {
	$dir = sentinelle_cache_dir();
	$file = $dir === '' ? '' : $dir . '/' . sha1($key) . '.val';
	if ($file === '' || !is_file($file) || @filemtime($file) < time() - $ttl) return '';
	return (string) @file_get_contents($file);
}

function sentinelle_cache_set($key, $value) {
	$dir = sentinelle_cache_dir();
	if ($dir !== '') @file_put_contents($dir . '/' . sha1($key) . '.val', (string) $value, LOCK_EX);
}

/** Robots des moteurs : motif du navigateur => domaines de leur DNS inverse */
function sentinelle_good_bots() {
	return array(
		'~googlebot|google-inspectiontool|adsbot-google|mediapartners-google~i' => array('.googlebot.com', '.google.com', '.googleusercontent.com'),
		'~bingbot|msnbot|bingpreview~i' => array('.search.msn.com'),
		'~applebot~i' => array('.applebot.apple.com'),
		'~duckduckbot~i' => array('.duckduckgo.com'),
		'~yandex(bot|images)~i' => array('.yandex.ru', '.yandex.net', '.yandex.com'),
		'~qwantify|qwantbot~i' => array('.qwant.com'),
	);
}

/**
 * Robot d'un moteur de recherche ?
 * @return bool|null true : vérifié (DNS inverse puis direct), false : se dit
 *                   robot mais le DNS ne confirme pas, null : ne se dit pas robot
 */
function sentinelle_bot_check($ip, $agent, ?callable $rdns = null, ?callable $fdns = null) {
	$domains = null;
	foreach (sentinelle_good_bots() as $re => $d) if (preg_match($re, $agent)) { $domains = $d; break; }
	if ($domains === null || !filter_var($ip, FILTER_VALIDATE_IP)) return null;
	$cached = sentinelle_cache_get('bot|' . $ip, 86400);
	if ($cached !== '') return $cached === '1';
	$rdns = $rdns ?? function ($ip) { return @gethostbyaddr($ip); };
	$fdns = $fdns ?? function ($host) {
		$ips = array();
		foreach ((array) @dns_get_record($host, DNS_A | DNS_AAAA) as $r) $ips[] = $r['ip'] ?? ($r['ipv6'] ?? '');
		return $ips;
	};
	$host = strtolower((string) $rdns($ip));
	$ok = false;
	foreach ($domains as $d) {
		if ($host !== '' && substr($host, -strlen($d)) === $d) {
			foreach ((array) $fdns($host) as $a) if ($a !== '' && @inet_pton($a) === @inet_pton($ip)) { $ok = true; break 2; }
		}
	}
	sentinelle_cache_set('bot|' . $ip, $ok ? '1' : '0');
	return $ok;
}

// -----------------------------------------------------------------------
// Bannissement automatique (mode protection) : trop d'événements graves
// depuis une IP en peu de temps ; récidive = bannissement plus long
// -----------------------------------------------------------------------

/** Types comptés pour le bannissement automatique (en plus des événements bloqués ou critiques) */
function sentinelle_autoban_types() {
	return array('login_failed', 'twofa_failed', 'captcha_failed', 'login_locked', 'csrf_failed', 'scanner', 'bad_agent');
}

/** Durée d'un bannissement automatique pour $ip (récidive : x30, au plus 90 jours) */
function sentinelle_autoban_duration($ip) {
	$base = max(60, (int) sbSetting('sentinelle_autoban_duration', '86400'));
	$dbt = sentinelle_db();
	if (!$dbt) return $base;
	list($db, $table) = $dbt;
	$since = date('Y-m-d H:i:s', time() - 30 * 86400);
	$stmt = mysqli_prepare($db, "SELECT COUNT(*) FROM `$table` WHERE ip = ? AND type = 'auto_ban' AND created_at >= ?");
	if (!$stmt) return $base;
	mysqli_stmt_bind_param($stmt, 'ss', $ip, $since);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $n);
	mysqli_stmt_fetch($stmt);
	mysqli_stmt_close($stmt);
	return (int) $n >= 2 ? min($base * 30, 90 * 86400) : $base;
}

/** Après un événement : bannir l'IP si elle dépasse le seuil ? */
function sentinelle_autoban_maybe($ip, $type) {
	$limit = (int) sbSetting('sentinelle_autoban', '10');
	if ($limit <= 0 || sentinelle_mode() !== 'protect' || in_array($type, array('auto_ban', 'ip_banned', 'dos', 'fake_bot'), true)) return false;
	if (!filter_var($ip, FILTER_VALIDATE_IP) || sentinelle_is_trusted($ip) || sentinelle_banned($ip)) return false;
	$dbt = sentinelle_db();
	if (!$dbt) return false;
	list($db, $table) = $dbt;
	$minutes = max(1, min(1440, (int) sbSetting('sentinelle_autoban_window', '10')));
	$since = date('Y-m-d H:i:s', time() - $minutes * 60);
	$in = "'" . implode("','", sentinelle_autoban_types()) . "'";
	$stmt = mysqli_prepare($db, "SELECT COUNT(*) FROM `$table` WHERE ip = ? AND created_at >= ? AND (action = 'blocked' OR severity = 'critical' OR type IN ($in))");
	if (!$stmt) return false;
	mysqli_stmt_bind_param($stmt, 'ss', $ip, $since);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $n);
	mysqli_stmt_fetch($stmt);
	mysqli_stmt_close($stmt);
	if ((int) $n < $limit) return false;
	$duration = sentinelle_autoban_duration($ip);
	if (sentinelle_ban_add($ip, $duration, 'Automatique : ' . (int) $n . ' événements en ' . $minutes . ' min', 'auto') !== '') return false;
	sentinelle_event('auto_ban', 'critical', 'Bannie ' . ($duration >= 86400 ? round($duration / 86400) . ' jour(s)' : round($duration / 60) . ' min') . ' après ' . (int) $n . ' événements en ' . $minutes . ' min', 'banned');
	return true;
}
