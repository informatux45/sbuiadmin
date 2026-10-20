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
 * Inspection de la requête ('front' ou 'admin') : filtres d'attaques.
 * Exemptés : IP de confiance, utilisateurs connectés à l'administration.
 * Un seul événement par requête (le plus grave) ; en mode protection, un
 * filtre réglé sur « on » répond 403 et arrête la page.
 */
function sentinelle_guard($context) {
	$context = $context === 'admin' ? 'admin' : 'front';
	$GLOBALS['sentinelle_context'] = $context;
	if (sentinelle_is_trusted() || sentinelle_backoffice_user()) return;
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
