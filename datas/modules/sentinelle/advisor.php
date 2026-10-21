<?php
/**
 * Module sentinelle : conseiller sécurité
 * Vérifie la configuration du site point par point (ok, warn, bad, unknown,
 * info : simple rappel, hors score)
 * et donne la correction. Les contrôles « vus de l'extérieur » interrogent
 * le site lui-même (en reprenant l'éventuelle authentification HTTP).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

/** Groupes de contrôles : clé => libellé */
function sentinelle_advisor_groups() {
	return array(
		'php'      => 'PHP',
		'https'    => 'HTTPS et en-têtes',
		'exposure' => 'Fichiers exposés',
		'accounts' => 'Comptes et connexion',
		'sentinel' => 'Sentinelle',
		'updates'  => 'Mises à jour et réglages',
	);
}

/** URL de base du site (réglage site_url, sinon déduite de la requête) */
function sentinelle_site_base() {
	$url = function_exists('sbSetting') ? sbSetting('site_url') : '';
	if (preg_match('#^https?://#i', $url)) return rtrim($url, '/') . '/';
	$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
	return ($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/';
}

/**
 * Interroge le site lui-même, en parallèle
 * @param array $paths clé => chemin relatif au site
 * @return array clé => array(code, headers (minuscules), body (2 Ko)) ou null si impossible
 */
function sentinelle_probe_many(array $paths) {
	$out = array_fill_keys(array_keys($paths), null);
	if (!function_exists('curl_multi_init')) return $out;
	$base = sentinelle_site_base();
	$mh = curl_multi_init();
	$handles = array();
	foreach ($paths as $k => $path) {
		$ch = curl_init($base . ltrim($path, '/'));
		curl_setopt_array($ch, array(CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => true, CURLOPT_TIMEOUT => 6, CURLOPT_CONNECTTIMEOUT => 3,
			CURLOPT_FOLLOWLOCATION => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_USERAGENT => 'SBUIADMIN-Sentinelle-Advisor'));
		if (isset($_SERVER['PHP_AUTH_USER'])) curl_setopt($ch, CURLOPT_USERPWD, $_SERVER['PHP_AUTH_USER'] . ':' . ($_SERVER['PHP_AUTH_PW'] ?? ''));
		elseif (!empty($_SERVER['HTTP_AUTHORIZATION'])) curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $_SERVER['HTTP_AUTHORIZATION']));
		curl_multi_add_handle($mh, $ch);
		$handles[$k] = $ch;
	}
	do {
		$status = curl_multi_exec($mh, $running);
		if ($running) curl_multi_select($mh, 1.0);
	} while ($running && $status === CURLM_OK);
	foreach ($handles as $k => $ch) {
		$raw = curl_multi_getcontent($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$hsize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		if (is_string($raw) && $code !== 0 && $code !== 401) {
			$out[$k] = array('code' => $code, 'headers' => strtolower(substr($raw, 0, $hsize)), 'body' => substr($raw, $hsize, 2048));
		}
		curl_multi_remove_handle($mh, $ch);
	}
	curl_multi_close($mh);
	return $out;
}

/** En-tête présent dans une réponse sondée ? (valeur, ou null) */
function sentinelle_header(array $r, $name) {
	return preg_match('/^' . preg_quote(strtolower($name), '/') . ':\s*(.*)$/m', $r['headers'], $m) ? trim($m[1]) : null;
}

/** Valeur ini booléenne activée ? */
function sentinelle_ini_on($name) {
	return in_array(strtolower((string) ini_get($name)), array('1', 'on', 'yes', 'true', 'stdout'), true);
}

/**
 * Contrôles du conseiller
 * @param array|null $probes résultats de sentinelle_probe_many() (null : interroge le site)
 * @param array $env valeurs imposées (tests) : php_version, admin_dir, site_root, users...
 * @return array liste de array(id, group, label, status, value, advice)
 */
function sentinelle_advisor_checks(?array $probes = null, array $env = array()) {
	$c = array();
	$add = function ($id, $group, $label, $status, $value, $advice = '') use (&$c) {
		$c[] = array('id' => $id, 'group' => $group, 'label' => $label, 'status' => $status, 'value' => (string) $value, 'advice' => $status === 'ok' ? '' : $advice);
	};
	$root = rtrim($env['site_root'] ?? (defined('SB_PATH') ? SB_PATH : dirname(SBUIADMIN_PATH) . '/'), '/') . '/';
	$admin = $env['admin_dir'] ?? basename(SBUIADMIN_PATH);
	$setting = function ($n, $d = '') use ($env) { return array_key_exists($n, $env['settings'] ?? array()) ? (string) $env['settings'][$n] : (function_exists('sbSetting') ? sbSetting($n, $d) : $d); };

	// --- PHP
	$php = $env['php_version'] ?? PHP_VERSION;
	$add('php_version', 'php', 'Version de PHP', version_compare($php, '8.4.0', '>=') ? 'ok' : (version_compare($php, '8.2.0', '>=') ? 'warn' : 'bad'), $php,
		'Passez à PHP 8.4 ou plus récent (panneau de votre hébergeur) : les versions plus anciennes ne reçoivent plus ou bientôt plus de correctifs de sécurité.');
	$add('display_errors', 'php', 'Erreurs PHP masquées aux visiteurs', sentinelle_ini_on('display_errors') ? 'warn' : 'ok', sentinelle_ini_on('display_errors') ? 'affichées' : 'masquées',
		'display_errors = Off (php.ini ou .user.ini) : une erreur affichée révèle des chemins et des requêtes.');
	$add('expose_php', 'php', 'Version de PHP cachée', sentinelle_ini_on('expose_php') ? 'warn' : 'ok', sentinelle_ini_on('expose_php') ? 'annoncée (X-Powered-By)' : 'cachée',
		'expose_php = Off (php.ini) : inutile d\'indiquer aux robots la version exacte de PHP.');
	$add('allow_url_include', 'php', 'Inclusion de fichiers distants interdite', sentinelle_ini_on('allow_url_include') ? 'bad' : 'ok', sentinelle_ini_on('allow_url_include') ? 'autorisée' : 'interdite',
		'allow_url_include = Off (php.ini) : sinon une faille d\'inclusion permet d\'exécuter du code venu d\'ailleurs.');
	$cookie = session_status() === PHP_SESSION_ACTIVE ? session_get_cookie_params() : array('httponly' => sentinelle_ini_on('session.cookie_httponly'), 'secure' => sentinelle_ini_on('session.cookie_secure'), 'samesite' => (string) ini_get('session.cookie_samesite'));
	$https_now = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
	$cok = !empty($cookie['httponly']) && (!$https_now || !empty($cookie['secure'])) && (string) ($cookie['samesite'] ?? '') !== '';
	$add('session_cookie', 'php', 'Cookie de session protégé', $cok ? 'ok' : 'warn',
		'HttpOnly ' . (!empty($cookie['httponly']) ? 'oui' : 'non') . ', Secure ' . (!empty($cookie['secure']) ? 'oui' : 'non') . ', SameSite ' . (($cookie['samesite'] ?? '') ?: 'non'),
		'session.cookie_httponly = 1, session.cookie_secure = 1 (site en HTTPS), session.cookie_samesite = Lax : le cookie de session ne doit être lisible ni par un script, ni transmis en clair.');
	$add('session_strict', 'php', 'Identifiants de session imposés refusés', sentinelle_ini_on('session.use_strict_mode') ? 'ok' : 'warn', sentinelle_ini_on('session.use_strict_mode') ? 'oui' : 'non',
		'session.use_strict_mode = 1 (php.ini ou .user.ini) : empêche la fixation de session.');

	// --- HTTPS et en-têtes (page d'accueil sondée)
	if ($probes === null) {
		$probes = sentinelle_probe_many(array(
			'home' => '', 'git' => '.git/HEAD', 'install' => $admin . '/install.php', 'upload' => 'upload/',
			'readme' => $admin . '/inc/migrations/README.txt',
		));
	}
	$site_url = $setting('site_url');
	$add('https', 'https', 'Site en HTTPS', stripos($site_url, 'https://') === 0 ? 'ok' : ($https_now ? 'warn' : 'bad'), $site_url !== '' ? $site_url : ($https_now ? 'HTTPS (URL du site non réglée)' : 'HTTP'),
		'Activez un certificat (Let\'s Encrypt chez la plupart des hébergeurs) et mettez l\'URL du site en https:// dans Configuration > Générale.');
	$home = $probes['home'] ?? null;
	if ($home === null) {
		$add('headers', 'https', 'En-têtes de sécurité', 'unknown', 'non vérifiable', 'Le serveur ne peut pas interroger le site lui-même (accès HTTP impossible ou protégé) : vérifiez les en-têtes avec un outil en ligne (securityheaders.com).');
	} else {
		$hsts = sentinelle_header($home, 'strict-transport-security');
		$add('hsts', 'https', 'HSTS (HTTPS imposé au navigateur)', $hsts !== null ? 'ok' : (stripos($site_url, 'https://') === 0 ? 'warn' : 'unknown'), $hsts ?? 'absent',
			'En HTTPS, l\'en-tête Strict-Transport-Security (posé par le .htaccess du site avec mod_headers) empêche le retour en HTTP.');
		$miss = array();
		foreach (array('x-content-type-options', 'x-frame-options', 'referrer-policy', 'permissions-policy') as $h) if (sentinelle_header($home, $h) === null) $miss[] = $h;
		$add('headers', 'https', 'En-têtes de sécurité', $miss ? 'warn' : 'ok', $miss ? 'absents : ' . implode(', ', $miss) : 'tous présents',
			'Le .htaccess livré pose ces en-têtes : vérifiez que mod_headers est actif et que le .htaccess racine est celui du CMS.');
		$csp = sentinelle_header($home, 'content-security-policy');
		$add('csp', 'https', 'Politique de sécurité du contenu (CSP)', $csp !== null ? 'ok' : 'warn', $csp !== null ? 'présente' : 'absente',
			'Une CSP limite les scripts que le navigateur accepte d\'exécuter : la meilleure parade aux XSS. À ajouter au .htaccess après test (Content-Security-Policy-Report-Only d\'abord).');
		$server = sentinelle_header($home, 'server');
		$powered = sentinelle_header($home, 'x-powered-by');
		$leak = ($server !== null && preg_match('/\d+\.\d+/', $server)) || $powered !== null;
		$add('server_leak', 'https', 'Logiciels du serveur discrets', $leak ? 'warn' : 'ok', trim(($server !== null ? 'Server: ' . $server : '') . ($powered !== null ? ' X-Powered-By: ' . $powered : '')) ?: 'rien d\'annoncé',
			'ServerTokens Prod (Apache) et expose_php = Off : ne pas donner aux robots les versions exactes à attaquer.');
	}

	// --- Fichiers exposés
	$code = function ($k) use ($probes) { return isset($probes[$k]) ? (int) $probes[$k]['code'] : null; };
	$g = $code('git');
	$add('git', 'exposure', 'Dossier .git inaccessible', $g === null ? 'unknown' : ($g === 200 ? 'bad' : 'ok'), $g === null ? 'non vérifiable' : 'code ' . $g,
		'Le dossier .git expose tout l\'historique du site (et parfois des mots de passe) : bloquez-le (règle RewriteRule ^\\.git - [F] du .htaccess livré) ou supprimez-le du serveur.');
	$i = $code('install');
	$add('install', 'exposure', 'Installeur fermé', $i === null ? 'unknown' : ($i === 200 && stripos((string) ($probes['install']['body'] ?? ''), 'install') !== false ? 'bad' : 'ok'), $i === null ? 'non vérifiable' : 'code ' . $i,
		'L\'installeur ne doit plus être utilisable après l\'installation : remettez le fichier ' . $admin . '/install.php livré (simple redirection).');
	$r = $code('readme');
	$add('htaccess', 'exposure', 'Fichiers internes protégés (.htaccess pris en compte)', $r === null ? 'unknown' : ($r === 200 ? 'bad' : 'ok'), $r === null ? 'non vérifiable' : 'code ' . $r,
		'Le serveur ignore les .htaccess (AllowOverride All manquant, ou nginx) : les fichiers internes du CMS sont lisibles. Voir help.txt pour nginx.');
	$u = $probes['upload'] ?? null;
	$listing = $u !== null && (int) $u['code'] === 200 && stripos($u['body'], 'index of') !== false;
	$add('listing', 'exposure', 'Liste des fichiers des dossiers masquée', $u === null ? 'unknown' : ($listing ? 'bad' : 'ok'), $u === null ? 'non vérifiable' : ($listing ? 'upload/ listé' : 'masquée'),
		'Options -Indexes dans le .htaccess racine : sinon n\'importe qui parcourt vos médias.');
	$dbc = function_exists('sbDbConfigPath') ? (string) sbDbConfigPath() : '';
	$inside = $dbc !== '' && function_exists('sbDbConfigIsInsideSite') && sbDbConfigIsInsideSite($dbc);
	$add('dbconfig', 'exposure', 'Accès à la base rangés hors du site', $dbc === '' ? 'unknown' : ($inside ? 'warn' : 'ok'), $dbc === '' ? 'introuvable' : ($inside ? 'dans le site (protégé par .htaccess)' : 'hors du site'),
		'sbdbconfig.php est mieux placé hors du dossier web (voir help.txt, sbDbConfigDirs()) : il reste lisible si les .htaccess cessent d\'être appliqués.');
	$up = is_file($root . 'upload/.htaccess');
	$add('upload_htaccess', 'exposure', 'Aucun script exécuté dans les médias', $up ? 'ok' : 'bad', $up ? 'upload/.htaccess présent' : 'upload/.htaccess absent',
		'Remettez le fichier upload/.htaccess livré : sans lui, un fichier PHP glissé dans les médias s\'exécuterait.');
	$dumps = array();
	foreach (array('*.sql', '*.sql.gz', '*.zip', '*.tar.gz', '*.bak', '*.old') as $pat) foreach ((array) @glob($root . $pat) as $f) $dumps[] = basename($f);
	$add('backups', 'exposure', 'Pas de sauvegarde ni d\'archive à la racine', $dumps ? 'bad' : 'ok', $dumps ? implode(', ', array_slice($dumps, 0, 5)) : 'aucune',
		'Une archive ou un export SQL à la racine se télécharge par n\'importe qui : déplacez-les hors du site ou supprimez-les.');
	$w = array();
	foreach (array('index.php', $admin . '/index.php') as $f) if (is_file($root . $f) && is_writable($root . $f)) $w[] = $f;
	$add('writable', 'exposure', 'Fichiers PHP du cœur en lecture seule pour le serveur web', $w ? 'warn' : 'ok', $w ? 'modifiables : ' . implode(', ', $w) : 'lecture seule',
		'Fréquent en hébergement mutualisé (la mise à jour depuis l\'administration en a besoin) ; sur un serveur dédié, réservez l\'écriture au déploiement.');

	// --- Comptes et connexion
	$add('admin_dir', 'accounts', 'Dossier d\'administration renommé', $admin === 'backdoor' ? 'warn' : 'ok', $admin,
		'Renommez le dossier « backdoor » et mettez le même nom dans SBADMIN (sbconfig.php) : la page de connexion n\'est plus à l\'adresse connue des robots.');
	$add('twofa', 'accounts', 'Double authentification', $setting('twofa_enabled') === '1' ? 'ok' : 'warn', $setting('twofa_enabled') === '1' ? 'activée' : 'désactivée',
		'Activez-la dans Configuration > Générale (après un code de test reçu) : un mot de passe volé ne suffit plus.');
	$add('login_lock', 'accounts', 'Verrouillage après échecs de connexion', $setting('login_lock_enabled', '1') === '1' ? 'ok' : 'bad', $setting('login_lock_enabled', '1') === '1' ? 'actif' : 'inactif',
		'Réactivez le verrouillage (Configuration > Générale) : sans lui, les mots de passe se testent sans limite.');
	$add('altcha', 'accounts', 'Anti-robot sur la connexion (ALTCHA)', $setting('altcha_login', '1') === '1' ? 'ok' : 'warn', $setting('altcha_login', '1') === '1' ? 'actif' : 'inactif',
		'Activez ALTCHA sur la connexion (Configuration > Générale).');
	$users = $env['users'] ?? sentinelle_advisor_users();
	if ($users !== null) {
		$weak = array_values(array_intersect(array_map('strtolower', $users), array('admin', 'administrator', 'administrateur', 'root', 'test', 'demo', 'webmaster')));
		$add('usernames', 'accounts', 'Identifiants difficiles à deviner', $weak ? 'warn' : 'ok', $weak ? 'comptes : ' . implode(', ', $weak) : count($users) . ' compte(s)',
			'Les robots essaient d\'abord « admin », « root », « test »... : renommez ces comptes ou créez-en d\'autres et supprimez-les.');
	}

	// --- Sentinelle
	$mode = $setting('sentinelle_mode', 'observe');
	$add('sentinel_mode', 'sentinel', 'Sentinelle en protection', $mode === 'protect' ? 'ok' : 'warn', $mode === 'protect' ? 'protection' : 'observation',
		'Après quelques jours d\'observation sans fausse alerte, passez en mode protection (Réglages) : les attaques détectées seront bloquées.');
	$add('sentinel_trusted', 'sentinel', 'IP de confiance déclarées', $setting('sentinelle_trusted') !== '' ? 'ok' : 'warn', $setting('sentinelle_trusted') !== '' ? 'oui' : 'aucune',
		'Ajoutez vos adresses fixes aux IP de confiance : vous ne risquez plus de vous bloquer vous-même.');
	$add('sentinel_alerts', 'sentinel', 'Alertes par e-mail', filter_var($setting('sentinelle_alert_email'), FILTER_VALIDATE_EMAIL) && $setting('sentinelle_alert_level', 'critical') !== 'off' ? 'ok' : 'warn',
		filter_var($setting('sentinelle_alert_email'), FILTER_VALIDATE_EMAIL) ? $setting('sentinelle_alert_email') : 'aucun destinataire', 'Indiquez un destinataire dans les Réglages : vous serez prévenu des attaques critiques.');
	$integ = json_decode($setting('sentinelle_integrity'), true);
	if (!is_array($integ)) {
		$add('integrity', 'sentinel', 'Intégrité des fichiers', 'unknown', 'jamais analysée', 'Lancez l\'analyse (Sentinelle > Intégrité) : elle compare les fichiers du site à la version signée.');
	} else {
		$ic = (array) ($integ['counts'] ?? array());
		$alarm = (int) ($ic['modified'] ?? 0) + (int) ($ic['added'] ?? 0) + (int) ($ic['upload'] ?? 0) + (int) ($ic['approved_changed'] ?? 0);
		$watched = (int) ($ic['watched'] ?? 0);
		$old = strtotime((string) ($integ['date'] ?? '')) < time() - 7 * 86400;
		$add('integrity', 'sentinel', 'Intégrité des fichiers', ($integ['error'] ?? '') !== '' ? 'unknown' : ($alarm ? 'bad' : ($old || $watched ? 'warn' : 'ok')),
			($integ['error'] ?? '') !== '' ? 'analyse impossible' : ($alarm ? $alarm . ' fichier(s) à vérifier' : ($watched ? $watched . ' PHP nouveau(x) sous un préfixe surveillé' : 'conforme')) . ' (' . date('d/m/Y', strtotime((string) $integ['date'])) . ')',
			$alarm ? 'Ouvrez Sentinelle > Intégrité : un fichier modifié ou un PHP ajouté peut être une porte dérobée. Remettez les fichiers d\'origine (mise à jour, ou archive de la version) ; approuvez seulement les fichiers propres au site que vous connaissez.'
				: ($watched ? 'Ouvrez Sentinelle > Intégrité : vérifiez les nouveaux fichiers de vos bibliothèques puis approuvez-les.' : 'Relancez l\'analyse (Sentinelle > Intégrité) ou activez l\'analyse quotidienne.'));
	}
	$rate = (int) $setting('sentinelle_rate', '120'); $ab = (int) $setting('sentinelle_autoban', '10');
	$add('sentinel_autoban', 'sentinel', 'Anti-rafales et bannissement automatique', $rate > 0 && $ab > 0 ? 'ok' : 'warn',
		($rate > 0 ? $rate . ' requêtes/min' : 'rafales non surveillées') . ' · ' . ($ab > 0 ? 'bannissement après ' . $ab . ' événements' : 'pas de bannissement automatique'),
		'Réglages > Rafales et bannissement automatique : laissez-les actifs (ils ne bannissent qu\'en mode protection).');
	$rescue = function_exists('sbSentinelleRescueFile') && is_file(sbSentinelleRescueFile());
	$add('sentinel_rescue', 'sentinel', 'Fichier de secours absent', $rescue ? 'bad' : 'ok', $rescue ? 'présent : Sentinelle arrêtée' : 'absent',
		'Supprimez ' . $admin . '/inc/admin/sentinelle-disabled : Sentinelle est arrêtée tant qu\'il existe.');

	// --- Mises à jour et réglages
	$latest = json_decode($setting('update_latest'), true);
	$cur = $env['core_version'] ?? (defined('_AM_START_VERSION') ? _AM_START_VERSION : '');
	if (is_array($latest) && !empty($latest['version'])) {
		$behind = version_compare((string) $latest['version'], $cur, '>');
		$add('core_update', 'updates', 'SBUIADMIN à jour', $behind ? 'warn' : 'ok', $behind ? $cur . ' (disponible : ' . $latest['version'] . ')' : $cur,
			'Installez la dernière version depuis Configuration > Mise à jour : elle contient souvent des correctifs de sécurité.');
	} else {
		$add('core_update', 'updates', 'SBUIADMIN à jour', 'unknown', $cur . ' (jamais vérifié)', 'Lancez « Vérifier » dans Configuration > Mise à jour.');
	}
	$debug = array();
	foreach (array('debug_admin' => 'admin', 'debug_front' => 'site', 'debug_form' => 'formulaires', 'smarty_force_compile' => 'compilation Smarty forcée') as $k => $l) if ($setting($k) === '1') $debug[] = $l;
	$add('debug', 'updates', 'Modes de débogage coupés', $debug ? 'warn' : 'ok', $debug ? implode(', ', $debug) : 'coupés',
		'Désactivez le débogage en production (Configuration > Générale) : il affiche des requêtes et des chemins.');
	$mc = extension_loaded('memcache') || extension_loaded('memcached');
	$add('flood', 'updates', 'Anti-flood de la connexion', $setting('flood_enabled') === '1' && $mc ? 'ok' : 'warn', $setting('flood_enabled') === '1' ? ($mc ? 'actif' : 'activé mais Memcache absent') : 'désactivé',
		'Utilisateurs > IP bloquées > Paramètres : l\'anti-flood bloque les rafales de connexions (Memcache requis sur le serveur).');
	$add('maintenance', 'updates', 'Site ouvert (maintenance coupée)', $setting('maintenance') === '1' ? 'info' : 'ok', $setting('maintenance') === '1' ? 'en maintenance' : 'ouvert',
		'Simple rappel : le mode maintenance est actif.');
	return $c;
}

/** Identifiants des comptes de l'administration, ou null */
function sentinelle_advisor_users() {
	$dbt = function_exists('sentinelle_db') ? sentinelle_db('sb_users') : null;
	if (!$dbt) return null;
	list($db, $table) = $dbt;
	$res = @mysqli_query($db, "SELECT username FROM `$table`");
	if (!$res) return null;
	$out = array();
	while ($r = mysqli_fetch_row($res)) $out[] = (string) $r[0];
	return $out;
}

/** Score sur 100 : ok = 1, warn = 0,5, bad = 0 (non vérifiables et rappels exclus) */
function sentinelle_advisor_score(array $checks) {
	$pts = 0; $n = 0;
	foreach ($checks as $ch) {
		if ($ch['status'] === 'unknown' || $ch['status'] === 'info') continue;
		$n++;
		$pts += $ch['status'] === 'ok' ? 1 : ($ch['status'] === 'warn' ? 0.5 : 0);
	}
	return $n ? (int) round($pts / $n * 100) : 0;
}
