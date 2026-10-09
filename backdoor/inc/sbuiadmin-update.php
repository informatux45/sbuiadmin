<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Mise à jour depuis GitHub (versions signées)
 *
 * Remplace l'ancien mode UPGRADE (serveur en HTTP clair, rien de vérifié).
 *
 *  - sbUpdCheck()     : au plus une fois par 24 h (ou à la demande), lit la
 *                       dernière version publiée sur GitHub ; n'annonce
 *                       qu'une version dont le manifeste est signé (Ed25519)
 *  - sbUpdPrepare()   : télécharge et vérifie (signature, SHA-256 de
 *                       l'archive et de la liste des fichiers), contrôle
 *                       chaque entrée, calcule le plan : fichiers à écrire, à
 *                       supprimer, fichiers modifiés localement
 *  - sbUpdRun()       : exécute le plan en une seule requête (sauvegarde,
 *                       maintenance, copie vérifiée, retraits, caches) en
 *                       notant l'avancement, que la page lit en parallèle
 *                       (update-status.php)
 *  - sbUpdRollback()  : annule la DERNIÈRE mise à jour (un cran) : base
 *                       (migrations annulées, ou sauvegarde SQL) puis
 *                       fichiers ; sauvegardes chiffrées (voir -update-db.php)
 *
 * Jamais touchés : fichiers propres au site (upload/, .htaccess,
 * sbconfig.php, inc/cmscustom.php, réglages, caches, sbdbconfig*.php) et
 * l'installeur. Le dossier d'administration peut avoir été renommé : les
 * entrées backdoor/ de l'archive vont dans le vrai dossier.
 * Travail et sauvegardes hors de la racine web quand c'est possible (dossier
 * de sbdbconfig.php), sinon dans datas/updates/ (refusé au web).
 *
 * Chargé par update.php (page Configuration > Mise à jour, administrateurs).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
if (defined('SBUIADMIN_UPDATE_LOADED')) return;
define('SBUIADMIN_UPDATE_LOADED', true);
require_once __DIR__ . '/sbuiadmin-update-db.php';

defined('SB_UPD_REPO')        or define('SB_UPD_REPO', 'informatux45/sbuiadmin');
// Clé publique de signature des versions (la même que sbuiadmin_netinstall.php)
defined('SB_UPD_PUBLIC_KEY')  or define('SB_UPD_PUBLIC_KEY', 'MFcedhT1D+i3Ej3abNnOs6Mz4EjIIPo7hwc0azE/uoY=');
defined('SB_UPD_MANIFEST')    or define('SB_UPD_MANIFEST', 'sbuiadmin-release.json');
defined('SB_UPD_ROOT')        or define('SB_UPD_ROOT', 'sbuiadmin/');
defined('SB_UPD_INTERVAL')    or define('SB_UPD_INTERVAL', 86400);   // vérification : 1 fois / 24 h
defined('SB_UPD_MAX_ZIP')     or define('SB_UPD_MAX_ZIP', 64 * 1024 * 1024);
defined('SB_UPD_MAX_UNZIP')   or define('SB_UPD_MAX_UNZIP', 512 * 1024 * 1024);
defined('SB_UPD_MAX_FILES')   or define('SB_UPD_MAX_FILES', 20000);
defined('SB_UPD_LOCK_TTL')    or define('SB_UPD_LOCK_TTL', 900);     // maintenance : 15 min maximum
defined('SB_UPD_ROLLBACK_META') or define('SB_UPD_ROLLBACK_META', '.sbupd/rollback.json'); // dans chaque sauvegarde
defined('SB_UPD_DB_ENTRY')     or define('SB_UPD_DB_ENTRY', '.sbupd/database.sql');      // sauvegarde SQL dans la sauvegarde
defined('SB_UPD_KEEP_BACKUPS') or define('SB_UPD_KEEP_BACKUPS', 1);

function sbUpdHosts() {
	return array('api.github.com', 'github.com', 'objects.githubusercontent.com', 'release-assets.githubusercontent.com');
}

// -----------------------------------------------------------------------
// Réseau et vérifications (mêmes règles que sbuiadmin_netinstall.php)
// -----------------------------------------------------------------------

function sbUpdAllowedUrl($url) {
	$p = parse_url((string) $url);
	return is_array($p) && ($p['scheme'] ?? '') === 'https' && in_array(strtolower($p['host'] ?? ''), sbUpdHosts(), true);
}

/** Téléchargement HTTPS vérifié, limité à GitHub et à $max octets */
function sbUpdGet($url, $max, $toFile = null, $timeout = 120) {
	if (!sbUpdAllowedUrl($url)) throw new RuntimeException('Adresse refusée : ' . $url);
	// Harnais de tests uniquement (tests_sbuiadmin/test_update.php) : réponses simulées
	if (defined('SB_UPD_TEST_MOCK') && isset($GLOBALS['sb_upd_test_http'])) {
		$body = call_user_func($GLOBALS['sb_upd_test_http'], $url);
		if ($body === null) throw new RuntimeException('Téléchargement impossible : ' . $url);
		if (strlen($body) > $max) throw new RuntimeException('Réponse trop volumineuse : ' . $url);
		if ($toFile !== null) { file_put_contents($toFile, $body); return ''; }
		return $body;
	}
	if (!function_exists('curl_init')) throw new RuntimeException('Extension cURL absente');
	$fh = null;
	if ($toFile !== null && ($fh = fopen($toFile, 'wb')) === false) throw new RuntimeException('Fichier temporaire impossible à créer');
	$body = '';
	$size = 0;
	$ch = curl_init($url);
	curl_setopt_array($ch, array(
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_MAXREDIRS      => 5,
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_SSL_VERIFYHOST => 2,
		CURLOPT_FAILONERROR    => true,
		CURLOPT_CONNECTTIMEOUT => min(10, $timeout),
		CURLOPT_TIMEOUT        => $timeout,
		CURLOPT_USERAGENT      => 'SBUIADMIN-update/' . (defined('_AM_START_VERSION') ? _AM_START_VERSION : '?'),
		CURLOPT_HTTPHEADER     => array('Accept: application/vnd.github+json, application/octet-stream;q=0.9, */*;q=0.1'),
		CURLOPT_WRITEFUNCTION  => function ($ch, $chunk) use (&$body, &$size, $fh, $max) {
			$size += strlen($chunk);
			if ($size > $max) return 0;
			if ($fh) return fwrite($fh, $chunk);
			$body .= $chunk;
			return strlen($chunk);
		},
	));
	if (defined('CURLOPT_PROTOCOLS_STR')) {
		curl_setopt($ch, CURLOPT_PROTOCOLS_STR, 'https');
		curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS_STR, 'https');
	} else {
		curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
		curl_setopt($ch, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS);
	}
	$ok    = curl_exec($ch);
	$err   = curl_error($ch);
	$final = (string) curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	if ($fh) fclose($fh);
	if ($size > $max) throw new RuntimeException('Réponse trop volumineuse : ' . $url);
	if (!$ok) throw new RuntimeException('Téléchargement impossible (' . $err . ') : ' . $url);
	if (!sbUpdAllowedUrl($final)) throw new RuntimeException('Redirection hors de GitHub refusée : ' . $final);
	return $body;
}

/** Signature puis contenu du manifeste. @return array */
function sbUpdVerifyManifest($manifest, $sigB64, $pubB64 = SB_UPD_PUBLIC_KEY) {
	$sig = base64_decode(trim((string) $sigB64), true);
	$pub = base64_decode($pubB64, true);
	if ($sig === false || strlen($sig) !== SODIUM_CRYPTO_SIGN_BYTES || $pub === false || strlen($pub) !== SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES
		|| !sodium_crypto_sign_verify_detached($sig, (string) $manifest, $pub)) {
		throw new RuntimeException('Signature du manifeste invalide : version non authentique');
	}
	$m = json_decode((string) $manifest, true);
	if (!is_array($m) || ($m['name'] ?? '') !== 'sbuiadmin'
		|| !preg_match('/^\d+\.\d+(\.\d+)?$/', (string) ($m['version'] ?? ''))
		|| !preg_match('/^sbuiadmin-\d+\.\d+(\.\d+)?\.zip$/', (string) ($m['file'] ?? ''))
		|| !is_int($m['size'] ?? null) || $m['size'] <= 0 || $m['size'] > SB_UPD_MAX_ZIP
		|| !preg_match('/^[0-9a-f]{64}$/', (string) ($m['sha256'] ?? ''))) {
		throw new RuntimeException('Manifeste signé mais incomplet ou invalide');
	}
	if (isset($m['files_list']) && ($m['files_list'] !== 'sbuiadmin-files.json' || !preg_match('/^[0-9a-f]{64}$/', (string) ($m['files_list_sha256'] ?? '')))) {
		throw new RuntimeException('Liste des fichiers du manifeste invalide');
	}
	return $m;
}

/**
 * Manifeste vérifié + adresses des fichiers d'une version publiée.
 * @param string|null $tag null = dernière version
 * @return array [manifeste, fichiers joints nom => url, données GitHub]
 */
function sbUpdFetchRelease($tag = null, $timeout = 30) {
	$api = 'https://api.github.com/repos/' . SB_UPD_REPO . '/releases/' . ($tag === null ? 'latest' : 'tags/' . rawurlencode($tag));
	$rel = json_decode(sbUpdGet($api, 1024 * 1024, null, $timeout), true);
	if (!is_array($rel) || empty($rel['assets']) || !is_array($rel['assets'])) throw new RuntimeException('Aucune version publiée trouvée' . ($tag ? ' (' . $tag . ')' : ''));
	$assets = array();
	foreach ($rel['assets'] as $a) {
		if (isset($a['name'], $a['browser_download_url']) && sbUpdAllowedUrl($a['browser_download_url'])) $assets[$a['name']] = $a['browser_download_url'];
	}
	if (!isset($assets[SB_UPD_MANIFEST], $assets[SB_UPD_MANIFEST . '.sig'])) throw new RuntimeException('Version publiée sans manifeste signé');
	$m = sbUpdVerifyManifest(sbUpdGet($assets[SB_UPD_MANIFEST], 64 * 1024, null, $timeout), sbUpdGet($assets[SB_UPD_MANIFEST . '.sig'], 4096, null, $timeout));
	if ($tag !== null && ($m['tag'] ?? '') !== $tag) throw new RuntimeException('Le manifeste signé ne correspond pas à l\'étiquette ' . $tag);
	return array($m, $assets, $rel);
}

/** Liste signée des fichiers d'une version (chemin => sha256) ou null */
function sbUpdFetchFileList(array $m, array $assets, $timeout = 60) {
	if (empty($m['files_list']) || !isset($assets[$m['files_list']])) return null;
	$raw = sbUpdGet($assets[$m['files_list']], 8 * 1024 * 1024, null, $timeout);
	if (!hash_equals($m['files_list_sha256'], hash('sha256', $raw))) throw new RuntimeException('Liste des fichiers non conforme au manifeste signé');
	$list = json_decode($raw, true);
	if (!is_array($list) || ($list['version'] ?? '') !== $m['version'] || !is_array($list['files'] ?? null)) throw new RuntimeException('Liste des fichiers invalide');
	foreach ($list['files'] as $path => $hash) {
		if (!is_string($path) || !preg_match('/^[0-9a-f]{64}$/', (string) $hash) || !sbUpdSafeRel($path)) throw new RuntimeException('Liste des fichiers : entrée refusée ' . $path);
	}
	return $list['files'];
}

/** Chemin relatif sûr (pas de .., d'absolu, d'antislash, de NUL ni de :) */
function sbUpdSafeRel($rel) {
	$rel = (string) $rel;
	if ($rel === '' || $rel[0] === '/' || strpbrk($rel, "\0\\:") !== false) return false;
	foreach (explode('/', rtrim($rel, '/')) as $seg) {
		if ($seg === '' || $seg === '.' || $seg === '..') return false;
	}
	return true;
}

// -----------------------------------------------------------------------
// État (sb_config) et vérification quotidienne
// -----------------------------------------------------------------------

function sbUpdState() {
	$latest = json_decode(sbSetting('update_latest'), true);
	return array(
		'current'    => sbUpdInstalledVersion(),
		'latest'     => is_array($latest) ? $latest : null,
		'last_check' => (int) sbSetting('update_last_check', '0'),
		'error'      => sbSetting('update_last_error'),
	);
}

function sbUpdIsAvailable(?array $state = null) {
	$state = $state ?: sbUpdState();
	return $state['latest'] && version_compare($state['latest']['version'], $state['current'], '>');
}

/** Vérification due ? (jamais faite, ou plus de 24 h) */
function sbUpdCheckDue() {
	return (time() - (int) sbSetting('update_last_check', '0')) >= SB_UPD_INTERVAL;
}

/**
 * Lit la dernière version signée publiée. Hors $force, au plus une fois par
 * 24 h (en cas d'échec, nouvel essai au bout d'une heure).
 * @return array état
 */
function sbUpdCheck($force = false) {
	if (!$force && !sbUpdCheckDue()) return sbUpdState();
	$save = array('update_last_check' => (string) time(), 'update_last_error' => '');
	try {
		list($m, , $rel) = sbUpdFetchRelease(null, 8);
		$save['update_latest'] = json_encode(array(
			'version'   => $m['version'],
			'tag'       => $m['tag'] ?? '',
			'commit'    => $m['commit'] ?? '',
			'size'      => $m['size'],
			'published' => (string) ($rel['published_at'] ?? ''),
			'url'       => sbUpdAllowedUrl($rel['html_url'] ?? '') ? $rel['html_url'] : '',
			'notes'     => mb_substr((string) ($rel['body'] ?? ''), 0, 20000),
		), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	} catch (Throwable $e) {
		$save['update_last_check'] = (string) (time() - SB_UPD_INTERVAL + 3600);
		$save['update_last_error'] = mb_substr($e->getMessage(), 0, 500);
	}
	sbSettingsSave($save);
	return sbUpdState();
}

// -----------------------------------------------------------------------
// Emplacements
// -----------------------------------------------------------------------

/** Racine du site et nom réel du dossier d'administration */
function sbUpdSiteRoot() {
	return dirname(SBUIADMIN_PATH);
}
function sbUpdAdminDir() {
	return basename(SBUIADMIN_PATH);
}

/**
 * Dossier de travail (archive téléchargée, état, sauvegardes), choisi dans
 * cet ordre :
 *  1. imposé : variable d'environnement SBUIADMIN_BACKUP_DIR, ou clé
 *     'backup_dir' de sbdbconfig.php (chemin absolu) ;
 *  2. dossier de sbdbconfig.php s'il est hors du dossier publié (private/,
 *     dossier parent, dossier du compte... selon l'hébergeur) ;
 *  3. à défaut, dans le site : datas/updates/<64 caractères aléatoires>,
 *     interdit au web (Apache 2.2/2.4, IIS) ; les sauvegardes y sont de
 *     toute façon chiffrées, et un auto-test HTTP vérifie l'interdiction.
 * @return string
 */
function sbUpdWorkDir() {
	$info = sbUpdStorageInfo();
	return $info['path'];
}

/** @return array path, mode (custom, private, site), outside (hors du dossier du site) */
function sbUpdStorageInfo() {
	static $info = null;
	if ($info !== null) return $info;
	$suffix = '/sbuiadmin-updates-' . substr(sha1(sbUpdSiteRoot()), 0, 8);
	$base = null;
	$mode = '';
	// 1. Emplacement imposé
	$custom = (string) getenv('SBUIADMIN_BACKUP_DIR');
	if ($custom === '' && function_exists('sbDbConfigPath') && ($cfgFile = sbDbConfigPath())) {
		$cfg = sbDbConfigRead($cfgFile);
		if (is_array($cfg) && !empty($cfg['backup_dir'])) $custom = (string) $cfg['backup_dir'];
	}
	if ($custom !== '') {
		if ($custom[0] !== '/' && !preg_match('#^[A-Za-z]:[\\/]#', $custom)) throw new RuntimeException('SBUIADMIN_BACKUP_DIR doit être un chemin absolu');
		if (!is_dir($custom) && !@mkdir($custom, 0700, true)) throw new RuntimeException('Dossier de sauvegarde imposé inaccessible : ' . $custom);
		if (!is_writable($custom)) throw new RuntimeException('Dossier de sauvegarde imposé non inscriptible : ' . $custom);
		$base = rtrim($custom, '/\\');
		$mode = 'custom';
	}
	// 2. À côté de sbdbconfig.php, hors du site
	if ($base === null && function_exists('sbDbConfigPath') && ($cfgFile = sbDbConfigPath()) && !sbDbConfigIsInsideSite($cfgFile) && is_writable(dirname($cfgFile))) {
		$base = dirname($cfgFile);
		$mode = 'private';
	}
	// 3. Dans le site, nom aléatoire et interdictions
	if ($base === null) {
		$token = sbSetting('update_dir_token');
		if (!preg_match('/^[0-9a-f]{64}$/', $token)) {
			$token = bin2hex(random_bytes(32));
			sbSettingsSave(array('update_dir_token' => $token));
		}
		$parent = SBUIADMIN_PATH . '/datas/updates';
		foreach (array($parent, $parent . '/' . $token) as $d) {
			if (!is_dir($d) && !@mkdir($d, 0700, true)) throw new RuntimeException('Dossier de travail impossible à créer : ' . $d);
			sbUpdDenyWeb($d);
		}
		$base = $parent . '/' . $token;
		$mode = 'site';
		$suffix = '';
	}
	$path = $base . $suffix;
	if (!is_dir($path) && !@mkdir($path, 0700, true)) throw new RuntimeException('Dossier de travail impossible à créer : ' . $path);
	$realPath = realpath($path) ?: $path;
	$realSite = realpath(sbUpdSiteRoot()) ?: sbUpdSiteRoot();
	$info = array('path' => $path, 'mode' => $mode, 'outside' => strpos($realPath . '/', rtrim($realSite, '/') . '/') !== 0);
	return $info;
}

/** Interdit un dossier au web, quel que soit le serveur (fichiers absents seulement) */
function sbUpdDenyWeb($dir) {
	$files = array(
		'.htaccess'  => "# SBUIADMIN : sauvegardes de mise à jour, jamais servies\n<IfModule mod_authz_core.c>\n\tRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n\tOrder deny,allow\n\tDeny from all\n</IfModule>\n",
		'web.config' => "<?xml version=\"1.0\"?>\n<configuration><system.webServer><authorization><deny users=\"*\" /></authorization></system.webServer></configuration>\n",
		'index.html' => '',
	);
	foreach ($files as $name => $content) {
		if (!is_file($dir . '/' . $name)) @file_put_contents($dir . '/' . $name, $content);
	}
}

/**
 * Auto-test : un fichier du dossier de travail est-il lisible par le web ?
 * (seulement quand il est dans le site)
 * @return string 'outside', 'protected', 'exposed' ou 'unknown'
 */
function sbUpdSelfTest() {
	$info = sbUpdStorageInfo();
	if ($info['outside']) return 'outside';
	if (!function_exists('curl_init') || !defined('_AM_SITE_URL')) return 'unknown';
	$name  = 'probe-' . bin2hex(random_bytes(8)) . '.txt';
	$value = bin2hex(random_bytes(16));
	if (@file_put_contents($info['path'] . '/' . $name, $value) === false) return 'unknown';
	$rel = substr(realpath($info['path']) ?: $info['path'], strlen(realpath(SBUIADMIN_PATH) ?: SBUIADMIN_PATH) + 1);
	$ch = curl_init(rtrim(_AM_SITE_URL, '/') . '/' . str_replace('%2F', '/', rawurlencode($rel)) . '/' . $name);
	curl_setopt_array($ch, array(CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_CONNECTTIMEOUT => 3, CURLOPT_FOLLOWLOCATION => false, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => 0));
	$body = curl_exec($ch);
	$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
	@unlink($info['path'] . '/' . $name);
	if ($code === 0) return 'unknown';
	return ($code === 200 && trim((string) $body) === $value) ? 'exposed' : 'protected';
}

/** Fichiers propres au site : jamais écrits ni supprimés (chemins de l'archive) */
function sbUpdIsProtected($rel) {
	static $exact = array(
		'.htaccess', '.user.ini', 'sbconfig.php', 'sitemap.xml', 'robots.txt', 'sbuiadmin_netinstall.php',
		'inc/cmscustom.php', 'inc/sbsession.txt',
		'backdoor/.htaccess', 'backdoor/.user.ini', 'backdoor/install.php',
		'backdoor/inc/admin/settings.txt', 'backdoor/inc/admin/dashboard.txt', 'backdoor/inc/admin/theme.txt',
		'backdoor/inc/admin/php-audit.txt', 'backdoor/inc/admin/php-audit.log', 'backdoor/inc/admin/2fa-disabled',
	);
	static $prefixes = array('upload/', 'datas/cache/', 'backdoor/datas/cache/', 'backdoor/datas/updates/', 'backdoor/install/', '.sbupd/');
	if (in_array($rel, $exact, true)) return true;
	foreach ($prefixes as $p) if (strpos($rel, $p) === 0) return true;
	return (bool) preg_match('#(^|/)sbdbconfig[^/]*\.php$|(^|/)sbsettings-backup-[^/]*\.php$#', $rel);
}

/** Chemin de l'archive (backdoor/...) -> chemin réel sur le site */
function sbUpdTargetRel($rel) {
	return (strpos($rel, 'backdoor/') === 0) ? sbUpdAdminDir() . '/' . substr($rel, 9) : $rel;
}

function sbUpdHashFile($path) {
	return (is_file($path) && !is_link($path)) ? hash_file('sha256', $path) : null;
}

// -----------------------------------------------------------------------
// Maintenance pendant la copie
// -----------------------------------------------------------------------

function sbUpdLockFile() {
	return sbUpdSiteRoot() . '/.sbuiadmin-update.lock';
}
function sbUpdLock($on) {
	if ($on) @file_put_contents(sbUpdLockFile(), (string) time(), LOCK_EX);
	else @unlink(sbUpdLockFile());
}

// -----------------------------------------------------------------------
// Plan, étapes, restauration
// -----------------------------------------------------------------------

function sbUpdStateFile() {
	return sbUpdWorkDir() . '/state.json';
}
function sbUpdLoadJob() {
	$j = @json_decode((string) @file_get_contents(sbUpdStateFile()), true);
	return is_array($j) ? $j : null;
}
function sbUpdSaveJob(array $job) {
	if (@file_put_contents(sbUpdStateFile(), json_encode($job, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX) === false) {
		throw new RuntimeException('État de la mise à jour impossible à écrire');
	}
}
function sbUpdClearJob() {
	@unlink(sbUpdStateFile());
}

/**
 * Prépare la mise à jour vers la dernière version : téléchargement,
 * vérifications, plan. N'écrit rien sur le site.
 * @return array résumé pour la page
 */
function sbUpdPrepare() {
	if (!class_exists('ZipArchive')) throw new RuntimeException('Extension zip absente');
	@set_time_limit(300);
	$work = sbUpdWorkDir();
	$job  = sbUpdLoadJob();
	if ($job && in_array($job['status'], array('running', 'restoring'), true)) throw new RuntimeException('Une mise à jour est déjà en cours');
	sbUpdClearJob();

	// 1. Dernière version : manifeste signé, archive et liste conformes
	list($m, $assets) = sbUpdFetchRelease(null, 30);
	$installed = sbUpdInstalledVersion();
	if (!version_compare($m['version'], $installed, '>')) throw new RuntimeException('Aucune version plus récente que ' . $installed);
	if (!isset($assets[$m['file']])) throw new RuntimeException('Archive ' . $m['file'] . ' absente de la version publiée');
	$newList = sbUpdFetchFileList($m, $assets);
	if ($newList === null) throw new RuntimeException('La version ' . $m['version'] . ' n\'a pas de liste signée des fichiers : mise à jour depuis l\'admin impossible');
	$zipPath = $work . '/' . $m['file'];
	sbUpdGet($assets[$m['file']], $m['size'], $zipPath, 300);
	if (filesize($zipPath) !== $m['size'] || !hash_equals($m['sha256'], hash_file('sha256', $zipPath))) {
		@unlink($zipPath);
		throw new RuntimeException('L\'archive ne correspond pas au manifeste signé (taille ou SHA-256)');
	}

	// 2. Version installée : sa liste signée permet de repérer les fichiers
	// modifiés localement (et de supprimer les fichiers retirés)
	$oldList = null;
	$oldNote = '';
	try {
		list($om, $oassets) = sbUpdFetchRelease('v' . $installed, 30);
		$oldList = sbUpdFetchFileList($om, $oassets);
	} catch (Throwable $e) {
		$oldNote = $e->getMessage();
	}
	if ($oldList === null && $oldNote === '') $oldNote = 'la version ' . $installed . ' n\'a pas de liste signée des fichiers';

	// 3. Contrôle de chaque entrée de l'archive et plan de copie
	$zip = new ZipArchive();
	if ($zip->open($zipPath, ZipArchive::RDONLY) !== true) throw new RuntimeException('Archive illisible');
	if ($zip->numFiles < 1 || $zip->numFiles > SB_UPD_MAX_FILES) throw new RuntimeException('Nombre de fichiers anormal dans l\'archive');
	$root  = sbUpdSiteRoot() . '/';
	$write = array(); // [index zip, chemin archive, chemin site]
	$seen  = array();
	$total = 0;
	$protectedChanged = array();
	for ($i = 0; $i < $zip->numFiles; $i++) {
		$st = $zip->statIndex($i);
		$name = (string) $st['name'];
		if ($name === SB_UPD_ROOT) continue;
		if (strpos($name, SB_UPD_ROOT) !== 0) throw new RuntimeException('Entrée hors du dossier ' . SB_UPD_ROOT . ' : ' . $name);
		$rel = substr($name, strlen(SB_UPD_ROOT));
		if (!sbUpdSafeRel($rel)) throw new RuntimeException('Chemin refusé dans l\'archive : ' . $name);
		if ($zip->getExternalAttributesIndex($i, $opsys, $attr) && $opsys === ZipArchive::OPSYS_UNIX && ((($attr >> 16) & 0170000) === 0120000)) {
			throw new RuntimeException('Lien symbolique refusé dans l\'archive : ' . $name);
		}
		$total += (int) $st['size'];
		if ($total > SB_UPD_MAX_UNZIP) throw new RuntimeException('Taille décompressée trop importante');
		if (substr($rel, -1) === '/') continue; // dossiers créés à la volée
		if (!isset($newList[$rel])) throw new RuntimeException('Fichier absent de la liste signée : ' . $rel);
		$seen[$rel] = true;
		if (sbUpdIsProtected($rel)) {
			// Fichier propre au site, non écrit : signaler s'il change dans la nouvelle version
			if ($oldList !== null && isset($oldList[$rel]) && $oldList[$rel] !== $newList[$rel]) $protectedChanged[] = $rel;
			continue;
		}
		$target = sbUpdTargetRel($rel);
		// Déjà à jour (même contenu) : rien à écrire
		if (sbUpdHashFile($root . $target) === $newList[$rel]) continue;
		$write[] = array($i, $rel, $target);
	}
	$zip->close();
	foreach (array_keys($newList) as $rel) {
		if (!isset($seen[$rel]) && !sbUpdIsProtected($rel)) throw new RuntimeException('Fichier de la liste signée absent de l\'archive : ' . $rel);
	}

	// 4. Fichiers modifiés localement et fichiers à retirer
	$modified = array();
	$delete   = array();
	if ($oldList !== null) {
		foreach ($oldList as $rel => $hash) {
			if (sbUpdIsProtected($rel)) continue;
			$local = sbUpdHashFile($root . sbUpdTargetRel($rel));
			if ($local === null) continue;
			if ($local !== $hash) {
				$modified[] = $rel;
			} elseif (!isset($newList[$rel])) {
				$delete[] = $rel; // retiré de la nouvelle version, jamais modifié ici
			}
		}
		// Fichier ajouté par la nouvelle version mais déjà présent ici (créé localement)
		foreach ($write as $w) {
			if (!isset($oldList[$w[1]]) && is_file($root . $w[2])) $modified[] = $w[1];
		}
	}
	sort($modified);

	// 5. Migrations de base de données apportées par la nouvelle version
	$dbFrom = sbUpdDbVersion();
	$migrations = array();
	foreach (array_keys($newList) as $rel) {
		if (preg_match('#^backdoor/inc/migrations/(\d+\.\d+(?:\.\d+)?)\.php$#', $rel, $mm)
			&& version_compare($mm[1], $dbFrom, '>') && version_compare($mm[1], $m['version'], '<=')) $migrations[] = $mm[1];
	}
	usort($migrations, 'version_compare');

	$job = array(
		'status'    => 'ready',
		'from'      => $installed,
		'to'        => $m['version'],
		'manifest'  => $m,
		'zip'       => $zipPath,
		'write'     => $write,
		'delete'    => $delete,
		'modified'  => $modified,
		'unchecked' => ($oldList === null),
		'check_note' => $oldNote,
		'protected_changed' => $protectedChanged,
		'new_list'  => $newList,
		'db_from'   => $dbFrom,
		'migrations' => $migrations,
		'done'      => 0,
		'prepared'  => time(),
		'user'      => isset($_SESSION['sbuiadmin_user_name']) ? (string) $_SESSION['sbuiadmin_user_name'] : '',
	);
	sbUpdPreflight($job);
	sbUpdSaveJob($job);
	return sbUpdSummary($job);
}

/**
 * Vérifications préalables, avant de toucher à quoi que ce soit : tous les
 * fichiers à écrire ou retirer sont modifiables, l'espace disque suffit
 * (archive + sauvegarde des fichiers et de la base), la clé de chiffrement
 * des sauvegardes existe.
 */
function sbUpdPreflight(array $job) {
	sbUpdBackupKey();
	$root = sbUpdSiteRoot() . '/';
	$denied = array();
	foreach ($job['write'] as $w) {
		$t = $root . $w[2];
		if (is_file($t)) { if (!is_writable($t) || !is_writable(dirname($t))) $denied[] = $w[2]; continue; }
		for ($d = dirname($t); !is_dir($d) && $d !== dirname($d); $d = dirname($d));
		if (!is_writable($d)) $denied[] = $w[2];
	}
	foreach ($job['delete'] as $rel) {
		$t = $root . sbUpdTargetRel($rel);
		if (is_file($t) && !is_writable(dirname($t))) $denied[] = sbUpdTargetRel($rel);
	}
	if ($denied) throw new RuntimeException('Fichiers non modifiables par PHP (droits) : ' . implode(', ', array_slice($denied, 0, 10)) . (count($denied) > 10 ? ' et ' . (count($denied) - 10) . ' autres' : '') . '. Rien n\'a été modifié.');
	$link = function_exists('sbSettingsDb') ? sbSettingsDb() : null;
	$dbSize = ($link instanceof mysqli) ? sbUpdDbSize($link, sbUpdDbPrefix()) : 0;
	$need = (int) ($job['manifest']['size'] * 3 + $dbSize * 2 + 20 * 1024 * 1024);
	$free = @disk_free_space(sbUpdWorkDir());
	if ($free !== false && $free < $need) throw new RuntimeException('Espace disque insuffisant : ' . round($free / 1048576) . ' Mo libres, ' . round($need / 1048576) . ' Mo nécessaires');
}

function sbUpdDbPrefix() {
	$c = function_exists('sbDbConfig') ? sbDbConfig() : array();
	return (string) ($c['prefix'] ?? (defined('_AM_DB_PREFIX') ? _AM_DB_PREFIX : ''));
}

function sbUpdSummary(array $job) {
	return array(
		'status'    => $job['status'],
		'from'      => $job['from'],
		'to'        => $job['to'],
		'write'     => count($job['write']),
		'delete'    => count($job['delete']),
		'modified'  => $job['modified'],
		'unchecked' => $job['unchecked'],
		'check_note' => $job['check_note'],
		'protected_changed' => $job['protected_changed'],
		'migrations' => $job['migrations'] ?? array(),
		'done'      => $job['done'],
		'total'     => count($job['write']),
		'backup'    => isset($job['backup']) ? basename($job['backup']) : '',
	);
}

/** Enregistre l'avancement (lu par update-status.php pendant la copie) */
function sbUpdProgress(array &$job, $phase, $step, $force = false) {
	static $last = 0.0;
	$job['phase'] = $phase;
	$job['step']  = $step;
	if ($force || (microtime(true) - $last) > 0.4) {
		$last = microtime(true);
		sbUpdSaveJob($job);
	}
}


/** Journal d'accès de l'administration (Journal des connexions) */
function sbUpdLog($event, $user = '') {
	global $sbusers;
	if (is_object($sbusers) && method_exists($sbusers, 'updateAccessLog')) {
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'cli';
		@$sbusers->updateAccessLog('update', $event . ' (par ' . ($user !== '' ? $user : '?') . ' depuis ' . $ip . ')', $user);
	}
}

/** Connexion et préfixe de la base (null hors CMS) */
function sbUpdDb() {
	$link = function_exists('sbSettingsDb') ? sbSettingsDb() : null;
	if (!($link instanceof mysqli)) throw new RuntimeException('Base de données inaccessible');
	return $link;
}

/**
 * Exécute une mise à jour préparée, du début à la fin, dans CETTE requête :
 * le code du CMS est déjà chargé en mémoire, la copie ne mélange donc
 * jamais deux versions. L'avancement est écrit dans l'état au fil de l'eau
 * (la page l'affiche via update-status.php). Continue même si le
 * navigateur est fermé.
 *  1. sauvegarde chiffrée : fichiers remplacés/retirés, liste des fichiers
 *     ajoutés, base de données (tables du CMS) ;
 *  2. maintenance, copie vérifiée, retraits ;
 *  3. migrations de la base (idempotentes), db_version notée après chacune ;
 *  4. en cas d'échec : retour automatique (base remise depuis la sauvegarde,
 *     fichiers remis, fichiers ajoutés retirés).
 * @param bool $confirmModified l'administrateur accepte de remplacer les fichiers modifiés localement
 * @return array résumé final
 */
function sbUpdRun($confirmModified = false) {
	ignore_user_abort(true);
	@set_time_limit(0);
	$job = sbUpdLoadJob();
	if (!$job || $job['status'] !== 'ready') throw new RuntimeException('Aucune mise à jour prête (relancez la préparation)');
	if (($job['modified'] || $job['unchecked']) && !$confirmModified) {
		throw new RuntimeException('Confirmez le remplacement des fichiers modifiés localement');
	}
	if (!is_file($job['zip']) || !hash_equals($job['manifest']['sha256'], hash_file('sha256', $job['zip']))) {
		throw new RuntimeException('Archive modifiée depuis la préparation : relancez la préparation');
	}
	sbUpdPreflight($job);
	$db     = sbUpdDb();
	$prefix = sbUpdDbPrefix();
	$root   = sbUpdSiteRoot() . '/';
	$total  = count($job['write']);
	$work   = sbUpdWorkDir();
	$job['status']  = 'running';
	$job['started'] = time();
	$job['done']    = 0;
	$job['total']   = $total;
	$job['migrations_applied'] = array();
	sbUpdProgress($job, 'backup', 'Sauvegarde de la base de données…', true);

	// 1. Sauvegarde : base, fichiers remplacés ou retirés, fichiers ajoutés
	$stamp   = $job['from'] . '-' . $job['to'] . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4));
	$dump    = $work . '/tmp-' . $stamp . '.sql';
	$plain   = $work . '/tmp-' . $stamp . '.zip';
	$backup  = $work . '/backup-' . $stamp . '.zip.enc';
	$sidecar = $work . '/backup-' . $stamp . '.json';
	try {
		$tables = sbUpdDbDump($db, $prefix, $dump);
		sbUpdProgress($job, 'backup', 'Sauvegarde des fichiers…', true);
		$bz = new ZipArchive();
		if ($bz->open($plain, ZipArchive::CREATE | ZipArchive::EXCL) !== true) throw new RuntimeException('Sauvegarde impossible à créer');
		$n = 0;
		$added = array();
		foreach ($job['write'] as $w) {
			if (is_file($root . $w[2]) && !is_link($root . $w[2])) { $bz->addFile($root . $w[2], $w[2]); $n++; }
			else $added[] = $w[2];
		}
		foreach ($job['delete'] as $rel) {
			$t = sbUpdTargetRel($rel);
			if (is_file($root . $t)) { $bz->addFile($root . $t, $t); $n++; }
		}
		$meta = array('from' => $job['from'], 'to' => $job['to'], 'date' => date('c'), 'files' => $n, 'added' => $added,
			'db_from' => $job['db_from'], 'tables' => $tables);
		$bz->addFromString(SB_UPD_ROLLBACK_META, json_encode($meta, JSON_UNESCAPED_SLASHES));
		$bz->addFile($dump, SB_UPD_DB_ENTRY);
		if (!$bz->close()) throw new RuntimeException('Sauvegarde impossible à écrire');
		@chmod($plain, 0600);
		@unlink($dump);
		sbUpdEncryptFile($plain, $backup);
		$job['backup'] = $backup;
		sbUpdWriteSidecar($sidecar, array('from' => $job['from'], 'to' => $job['to'], 'date' => date('c'), 'files' => $n,
			'added' => count($added), 'db_from' => $job['db_from'], 'migrations' => array(), 'reversible' => true, 'complete' => false));
	} catch (Throwable $e) {
		foreach (array($dump, $plain, $backup, $sidecar) as $f) @unlink($f);
		$job['status'] = 'ready';
		sbUpdProgress($job, 'ready', 'Sauvegarde impossible : ' . $e->getMessage(), true);
		throw new RuntimeException('Sauvegarde impossible, rien n\'a été modifié : ' . $e->getMessage());
	}

	// 2. Maintenance, copie, retraits, 3. migrations
	sbUpdLock(true);
	$migStarted = false;
	try {
		sbUpdProgress($job, 'copy', 'Sauvegarde : ' . $n . ' fichier(s) et ' . count($tables) . ' table(s), chiffrée. Copie…', true);
		$zip = new ZipArchive();
		if ($zip->open($job['zip'], ZipArchive::RDONLY) !== true) throw new RuntimeException('Archive illisible');
		foreach ($job['write'] as $k => $w) {
			sbUpdWriteFile($root . $w[2], $zip->getFromIndex($w[0]), $job['new_list'][$w[1]], $w[1]);
			$job['done'] = $k + 1;
			if ($k % 25 === 0) sbUpdLock(true);
			sbUpdProgress($job, 'copy', 'Copie : ' . $job['done'] . ' / ' . $total);
		}
		$zip->close();
		foreach ($job['delete'] as $rel) @unlink($root . sbUpdTargetRel($rel));
		sbUpdClearCaches();

		// Migrations : fichiers de la NOUVELLE version, désormais en place
		$reversible = true;
		foreach (sbUpdPendingMigrations($job['db_from'], $job['to']) as $v => $file) {
			$migStarted = true;
			$mig = sbUpdLoadMigration($file);
			if (!$mig['reversible']) $reversible = false;
			sbUpdLock(true);
			sbUpdProgress($job, 'migrate', 'Base de données : migration ' . $v . (isset($mig['description']) ? ' (' . $mig['description'] . ')' : '') . '…', true);
			call_user_func($mig['up'], new SbMigration($db, $prefix));
			sbSettingsSave(array('db_version' => $v));
			$job['migrations_applied'][] = $v;
		}

		// Vérification complète des fichiers écrits
		sbUpdProgress($job, 'verify', 'Vérification des fichiers écrits…', true);
		$bad = array();
		foreach ($job['write'] as $w) {
			if (sbUpdHashFile($root . $w[2]) !== $job['new_list'][$w[1]]) $bad[] = $w[1];
		}
		if ($bad) throw new RuntimeException('Fichiers non conformes après copie : ' . implode(', ', array_slice($bad, 0, 10)));
	} catch (Throwable $e) {
		// 4. Retour automatique : le site ne reste jamais à moitié mis à jour
		$job['error'] = $e->getMessage();
		sbUpdProgress($job, 'rollback', 'Échec : ' . $e->getMessage() . ' — retour à la version ' . $job['from'] . '…', true);
		try {
			sbUpdRestoreFromPlain($plain, $job, $migStarted, 'rollback');
			$job['status'] = 'rolledback';
			$msg = 'Mise à jour interrompue (' . $e->getMessage() . ') : retour automatique à la version ' . $job['from'] . ' effectué' . ($migStarted ? ', base de données comprise' : '') . ', le site est inchangé';
			foreach (array($backup, $sidecar) as $f) @unlink($f); // la sauvegarde de la mise à jour précédente reste la bonne
		} catch (Throwable $e2) {
			$job['status'] = 'failed';
			$msg = 'Mise à jour interrompue (' . $e->getMessage() . ') ET retour automatique en échec (' . $e2->getMessage() . ') : utilisez le retour arrière de la page';
		}
		@unlink($plain);
		sbUpdLock(false);
		sbUpdHistory($job, $job['status'] === 'rolledback' ? 'échec, retour automatique' : 'échec');
		sbUpdLog('Mise à jour ' . $job['from'] . ' → ' . $job['to'] . ' en échec (' . $e->getMessage() . '), ' . ($job['status'] === 'rolledback' ? 'retour automatique effectué' : 'retour automatique en échec'), $job['user'] ?? '');
		sbUpdProgress($job, $job['status'], $msg, true);
		throw new RuntimeException($msg);
	}
	sbUpdLock(false);
	@unlink($plain);

	// 5. Journal, une seule sauvegarde gardée (retour d'un cran seulement)
	sbUpdWriteSidecar($sidecar, array('from' => $job['from'], 'to' => $job['to'], 'date' => date('c'), 'files' => $n,
		'added' => count($added), 'db_from' => $job['db_from'], 'migrations' => $job['migrations_applied'], 'reversible' => $reversible, 'complete' => true));
	foreach (sbUpdBackups() as $b) if ($b !== $backup) sbUpdDeleteBackup($b);
	$job['status']   = 'done';
	$job['finished'] = time();
	sbUpdHistory($job, 'mise à jour');
	sbUpdLog('Mise à jour ' . $job['from'] . ' → ' . $job['to'] . ' réussie (' . $total . ' fichiers, ' . count($job['migrations_applied']) . ' migration(s))', $job['user'] ?? '');
	@unlink($job['zip']);
	$summary = array('status' => 'done', 'from' => $job['from'], 'to' => $job['to'], 'done' => $total, 'total' => $total,
		'step' => 'Terminé : ' . $total . ' fichier(s) écrit(s), ' . count($job['delete']) . ' retiré(s), ' . count($job['migrations_applied']) . ' migration(s) de la base, tout vérifié',
		'backup' => basename($backup));
	$job['write'] = array();
	unset($job['new_list']);
	sbUpdProgress($job, 'done', $summary['step'], true);
	sbSettingsSave(array('update_last_check' => '0'));
	return $summary;
}

/** Écrit un fichier de façon atomique (fichier temporaire puis renommage) et le vérifie */
function sbUpdWriteFile($path, $data, $expectedHash, $rel) {
	if ($data === false || !hash_equals($expectedHash, hash('sha256', $data))) throw new RuntimeException('Contenu non conforme : ' . $rel);
	$dir = dirname($path);
	if (!is_dir($dir) && !@mkdir($dir, 0755, true)) throw new RuntimeException('Dossier impossible à créer : ' . dirname($rel));
	if (is_link($path)) throw new RuntimeException('Lien symbolique à la place de ' . $rel . ' : refusé');
	$tmp = $dir . '/.sbupd-' . bin2hex(random_bytes(4)) . '.tmp';
	if (@file_put_contents($tmp, $data, LOCK_EX) !== strlen($data)) { @unlink($tmp); throw new RuntimeException('Écriture impossible : ' . $rel); }
	@chmod($tmp, 0644);
	if (!@rename($tmp, $path)) { @unlink($tmp); throw new RuntimeException('Remplacement impossible : ' . $rel); }
	if (function_exists('opcache_invalidate')) @opcache_invalidate($path, true);
}

function sbUpdClearCaches() {
	foreach (array(SBUIADMIN_PATH . '/datas/cache/tpls_c', sbUpdSiteRoot() . '/datas/cache/tpls_c', sbUpdSiteRoot() . '/datas/cache/core') as $d) {
		foreach ((array) @glob($d . '/*.php') as $f) if (is_file($f)) @unlink($f);
	}
	if (function_exists('opcache_reset')) @opcache_reset();
}

function sbUpdHistory(array $job, $result = 'mise à jour') {
	$h = json_decode(sbSetting('update_history'), true);
	if (!is_array($h)) $h = array();
	array_unshift($h, array('date' => date('Y-m-d H:i'), 'result' => $result, 'from' => $job['from'] ?? '', 'to' => $job['to'] ?? '', 'user' => $job['user'] ?? '',
		'files' => count($job['write'] ?? array()), 'deleted' => count($job['delete'] ?? array()), 'modified' => count($job['modified'] ?? array()),
		'migrations' => count($job['migrations_applied'] ?? array())));
	sbSettingsSave(array('update_history' => json_encode(array_slice($h, 0, 20), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)));
}

// -----------------------------------------------------------------------
// Sauvegardes et retour arrière (un cran : la dernière mise à jour)
// -----------------------------------------------------------------------

function sbUpdWriteSidecar($file, array $data) {
	@file_put_contents($file, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
	@chmod($file, 0600);
}

/** Sauvegardes chiffrées (plus récente d'abord) */
function sbUpdBackups() {
	$list = (array) @glob(sbUpdWorkDir() . '/backup-*.zip.enc');
	usort($list, function ($a, $b) { return (filemtime($b) - filemtime($a)) ?: strcmp(basename($b), basename($a)); });
	return $list;
}

/**
 * Version réellement installée (fichier version.php sur le disque) : juste
 * après une mise à jour, la constante en mémoire est encore l'ancienne.
 */
function sbUpdInstalledVersion() {
	$src = (string) @file_get_contents(SBUIADMIN_PATH . '/inc/admin/version.php');
	return preg_match("/_AM_START_VERSION'\\s*,\\s*'(\\d+\\.\\d+(?:\\.\\d+)?)'/", $src, $m) ? $m[1] : _AM_START_VERSION;
}

/** Informations en clair d'une sauvegarde (fichier .json à côté) */
function sbUpdBackupInfo($file) {
	$j = json_decode((string) @file_get_contents(substr($file, 0, -strlen('.zip.enc')) . '.json'), true);
	return is_array($j) ? $j : array();
}

function sbUpdDeleteBackup($file) {
	@unlink($file);
	@unlink(substr($file, 0, -strlen('.zip.enc')) . '.json');
}

/**
 * Retour arrière possible ? Seulement la dernière mise à jour, et seulement
 * si la version installée est bien celle qu'elle a apportée.
 * @return array|null infos (from, to, migrations, reversible...) ou null
 */
function sbUpdRollbackInfo() {
	$b = sbUpdBackups();
	if (!$b) return null;
	$info = sbUpdBackupInfo($b[0]);
	if (empty($info['complete']) || ($info['to'] ?? '') !== sbUpdInstalledVersion()) return null;
	$info['name'] = basename($b[0]);
	$info['needs_dump'] = !empty($info['migrations']) && empty($info['reversible']);
	return $info;
}

/**
 * Remet fichiers (et base si $withDb) depuis une sauvegarde EN CLAIR :
 * fichiers remplacés ou retirés remis, fichiers ajoutés supprimés.
 */
function sbUpdRestoreFromPlain($plain, array &$job, $withDb, $phase) {
	$root = sbUpdSiteRoot() . '/';
	$zip = new ZipArchive();
	if ($zip->open($plain, ZipArchive::RDONLY) !== true) throw new RuntimeException('Sauvegarde illisible');
	$meta = json_decode((string) $zip->getFromName(SB_UPD_ROLLBACK_META), true);
	if (!is_array($meta)) { $zip->close(); throw new RuntimeException('Sauvegarde incomplète'); }
	try {
		// Base de données d'abord (le code restauré doit trouver son schéma)
		if ($withDb) {
			sbUpdProgress($job, $phase, 'Retour arrière : base de données…', true);
			$tmp = sbUpdWorkDir() . '/tmp-restore-' . bin2hex(random_bytes(4)) . '.sql';
			$in  = $zip->getStream(SB_UPD_DB_ENTRY);
			$out = $in ? fopen($tmp, 'wb') : false;
			if (!$in || !$out) throw new RuntimeException('Sauvegarde SQL absente ou illisible');
			@chmod($tmp, 0600);
			stream_copy_to_stream($in, $out);
			fclose($in);
			fclose($out);
			try { sbUpdDbRestore(sbUpdDb(), sbUpdDbPrefix(), $tmp); } finally { @unlink($tmp); }
		}
		$entries = array();
		for ($i = 0; $i < $zip->numFiles; $i++) {
			$n = $zip->getNameIndex($i);
			if (substr($n, -1) === '/' || strpos($n, '.sbupd/') === 0) continue;
			if (!sbUpdSafeRel($n) || sbUpdIsProtected(sbUpdArchiveRel($n))) throw new RuntimeException('Chemin refusé dans la sauvegarde : ' . $n);
			$entries[] = array($i, $n);
		}
		$added = is_array($meta['added'] ?? null) ? $meta['added'] : array();
		$job['done'] = 0;
		$job['total'] = count($entries) + count($added);
		sbUpdLock(true);
		foreach ($entries as $k => $e) {
			$data = $zip->getFromIndex($e[0]);
			sbUpdWriteFile($root . $e[1], $data, hash('sha256', (string) $data), $e[1]);
			$job['done']++;
			if ($k % 25 === 0) sbUpdLock(true);
			sbUpdProgress($job, $phase, 'Retour arrière : ' . $job['done'] . ' / ' . $job['total']);
		}
		$dirs = array();
		foreach ($added as $rel) {
			if (!is_string($rel) || !sbUpdSafeRel($rel) || sbUpdIsProtected(sbUpdArchiveRel($rel))) continue;
			if (is_file($root . $rel) && !is_link($root . $rel)) @unlink($root . $rel);
			for ($d = dirname($rel); $d !== '.' && $d !== ''; $d = dirname($d)) $dirs[$d] = substr_count($d, '/');
			$job['done']++;
		}
		arsort($dirs);
		foreach (array_keys($dirs) as $d) @rmdir($root . $d); // échoue sans dommage si le dossier n'est pas vide
	} finally {
		$zip->close();
		sbUpdClearCaches();
	}
	return $meta;
}

/** Chemin réel sur le site -> chemin de l'archive (backdoor/...), pour les règles de protection */
function sbUpdArchiveRel($target) {
	$admin = sbUpdAdminDir() . '/';
	return (strpos($target, $admin) === 0) ? 'backdoor/' . substr($target, strlen($admin)) : $target;
}

/**
 * Revenir à la version d'avant la DERNIÈRE mise à jour (un cran seulement).
 * Base : migrations annulées (descente : contenus saisis depuis conservés) ;
 * si une migration n'est pas réversible ou si l'annulation échoue, base
 * remise depuis la sauvegarde SQL (contenus saisis depuis perdus : il faut
 * alors $acceptDataLoss). Puis fichiers remis. La sauvegarde est ensuite
 * supprimée : on ne recule pas davantage.
 */
function sbUpdRollback($name, $acceptDataLoss = false) {
	ignore_user_abort(true);
	@set_time_limit(0);
	$job = sbUpdLoadJob();
	if ($job && in_array($job['status'], array('running', 'restoring', 'rollback'), true) && !sbUpdStale($job)) throw new RuntimeException('Une opération est déjà en cours');
	$info = sbUpdRollbackInfo();
	if (!$info || $info['name'] !== $name) throw new RuntimeException('Retour arrière impossible : seule la dernière mise à jour peut être annulée');
	if ($info['needs_dump'] && !$acceptDataLoss) throw new RuntimeException('Cette mise à jour a modifié la base de façon non réversible : confirmez la perte des contenus saisis depuis');
	$file = sbUpdWorkDir() . '/' . $name;
	$job = array('status' => 'restoring', 'from' => $info['to'], 'to' => $info['from'], 'done' => 0, 'total' => 0, 'started' => time(),
		'user' => isset($_SESSION['sbuiadmin_user_name']) ? (string) $_SESSION['sbuiadmin_user_name'] : '');
	sbUpdProgress($job, 'restore', 'Déchiffrement de la sauvegarde…', true);
	$plain = sbUpdWorkDir() . '/tmp-rollback-' . bin2hex(random_bytes(4)) . '.zip';
	sbUpdDecryptFile($file, $plain);
	$dbNote = '';
	sbUpdLock(true);
	try {
		$useDump = false;
		if (!empty($info['migrations'])) {
			if ($info['needs_dump']) {
				$useDump = true;
			} else {
				try {
					$files = array();
					foreach ($info['migrations'] as $v) {
						$f = sbUpdMigrationsDir() . '/' . $v . '.php';
						if (!is_file($f)) throw new RuntimeException('Migration ' . $v . ' absente');
						$files[$v] = $f;
					}
					sbUpdProgress($job, 'restore', 'Base de données : annulation des migrations…', true);
					sbUpdMigrateDown(sbUpdDb(), sbUpdDbPrefix(), $files, $info['db_from']);
					$dbNote = ', migrations annulées (contenus conservés)';
				} catch (Throwable $e) {
					// Base dans un état incertain : la sauvegarde SQL fait foi
					$useDump = true;
					$dbNote = ', annulation des migrations en échec (' . $e->getMessage() . ') : base remise depuis la sauvegarde';
				}
			}
			if ($useDump && $dbNote === '') $dbNote = ', base remise depuis la sauvegarde';
		}
		sbUpdRestoreFromPlain($plain, $job, $useDump, 'restore');
	} finally {
		@unlink($plain);
		sbUpdLock(false);
	}
	sbUpdDeleteBackup($file); // un seul cran : pas de retour plus loin
	$job['status'] = 'restored';
	sbUpdHistory(array('from' => $job['from'], 'to' => $job['to'], 'user' => $job['user']), 'retour arrière');
	sbUpdLog('Retour arrière ' . $job['from'] . ' → ' . $job['to'] . $dbNote, $job['user']);
	sbUpdProgress($job, 'restored', 'Retour à la version ' . $job['to'] . ' terminé' . $dbNote, true);
	sbSettingsSave(array('update_last_check' => '0'));
	return array('status' => 'restored', 'done' => $job['done'], 'total' => $job['total'], 'step' => $job['step']);
}

/** Opération abandonnée (navigateur fermé...) depuis plus que la durée du verrou ? */
function sbUpdStale(array $job) {
	return !empty($job['started']) && (time() - $job['started']) > SB_UPD_LOCK_TTL;
}

/** Abandonne une préparation (rien n'a été écrit) */
function sbUpdCancel() {
	$job = sbUpdLoadJob();
	if ($job && in_array($job['status'], array('running', 'restoring'), true) && !sbUpdStale($job)) throw new RuntimeException('Copie en cours : patientez');
	if ($job && !empty($job['zip'])) @unlink($job['zip']);
	sbUpdClearJob();
	sbUpdLock(false);
}
