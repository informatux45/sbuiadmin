<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Mise à jour des modules (versions signées, indépendantes du core)
 *
 * Une version publiée contient, en plus de l'archive complète, une archive
 * par module et l'index sbuiadmin-modules.json (version, core minimal,
 * empreinte de l'archive et de chaque fichier), dont l'empreinte est dans le
 * manifeste signé.
 *
 *  - sbModCatalog()   : modules publiés (cache sb_config) + état local
 *  - sbModPlan()      : ce que ferait une opération (rien n'est écrit)
 *  - sbModRun()       : télécharger, mettre à jour, installer, désinstaller,
 *                       supprimer les fichiers
 *
 * Fichiers écrits ou retirés : sauvegarde chiffrée avant (avec la base si
 * elle est touchée), retour automatique en cas d'échec. Liste des fichiers
 * livrés gardée dans le dossier de travail (modules/<nom>.json) : elle
 * permet de repérer les fichiers modifiés localement.
 *
 * Chargé par update.php (Configuration > Mise à jour, administrateurs).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
if (defined('SBUIADMIN_UPDATE_MODULES_LOADED')) return;
define('SBUIADMIN_UPDATE_MODULES_LOADED', true);
require_once __DIR__ . '/sbuiadmin-update.php';
require_once __DIR__ . '/sbuiadmin-modules.php';

// -----------------------------------------------------------------------
// Index signé des modules
// -----------------------------------------------------------------------

/** Dernière version publiée : [manifeste, fichiers joints, index des modules] */
function sbModFetchLatest($timeout = 30) {
	list($m, $assets) = sbUpdFetchRelease(null, $timeout);
	$idx = sbUpdFetchModulesIndex($m, $assets, $timeout);
	if ($idx === null) throw new RuntimeException('La version ' . $m['version'] . ' ne publie pas ses modules séparément');
	return array($m, $assets, $idx);
}

// -----------------------------------------------------------------------
// État local
// -----------------------------------------------------------------------

function sbModWorkDir() {
	$d = sbUpdWorkDir() . '/modules';
	if (!is_dir($d) && !@mkdir($d, 0700, true)) throw new RuntimeException('Dossier de travail des modules impossible à créer');
	return $d;
}

/** Liste des fichiers livrés lors de la dernière opération (chemin => sha256) ou null */
function sbModStoredList($name) {
	$j = json_decode((string) @file_get_contents(sbModWorkDir() . '/' . $name . '.json'), true);
	return (is_array($j) && is_array($j['files'] ?? null)) ? $j['files'] : null;
}
function sbModStoreList($name, $version, array $files) {
	$f = sbModWorkDir() . '/' . $name . '.json';
	if (@file_put_contents($f, json_encode(array('version' => $version, 'date' => date('c'), 'files' => $files), JSON_UNESCAPED_SLASHES), LOCK_EX) === false) throw new RuntimeException('Liste des fichiers du module impossible à enregistrer');
	@chmod($f, 0600);
}

/** Fichiers d'un module présent sur le disque (chemins de l'archive) */
function sbModLocalFiles($name) {
	$root = sbUpdSiteRoot() . '/';
	$out = array();
	$meta = sbModuleMeta($name);
	foreach ((array) ($meta['files'] ?? array()) as $rel) {
		if (is_string($rel) && sbModAllowedPath($name, $rel) && is_file($root . sbUpdTargetRel($rel))) $out[] = $rel;
	}
	$dir = sbModulesDir() . $name;
	if (is_dir($dir)) {
		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
		foreach ($it as $f) {
			if ($f->isFile() && !$f->isLink()) $out[] = 'datas/modules/' . $name . '/' . str_replace('\\', '/', substr($f->getPathname(), strlen($dir) + 1));
		}
	}
	sort($out, SORT_STRING);
	return array_values(array_unique($out));
}

/** Table sb_modules : créée par la migration 4.29 si besoin (idempotente) */
function sbModTable() {
	$db = sbUpdDb();
	$t = sbUpdDbPrefix() . 'sb_modules';
	$r = $db->query("SHOW TABLES LIKE '" . $db->real_escape_string($t) . "'");
	if (!$r || $r->num_rows === 0) {
		$mig = sbUpdLoadMigration(sbUpdMigrationsDir() . '/4.29.php');
		call_user_func($mig['up'], new SbMigration($db, sbUpdDbPrefix()));
		sbModulesInstalled(true);
	}
	return '`' . str_replace('`', '', $t) . '`';
}

function sbModRow($name) {
	sbModTable();
	$all = sbModulesInstalled(true);
	return $all[$name] ?? null;
}

/** Catalogue mis en cache (dernière vérification) */
function sbModCatalogCache() {
	$c = json_decode(sbSetting('modules_catalog'), true);
	return is_array($c) ? $c : array('checked' => 0, 'error' => '', 'core_version' => '', 'modules' => array());
}

/** Relit les modules publiés (dernière version GitHub) */
function sbModCatalogRefresh() {
	$cache = array('checked' => time(), 'error' => '', 'core_version' => '', 'modules' => array());
	try {
		list($m, , $idx) = sbModFetchLatest(15);
		$cache['core_version'] = $m['version'];
		foreach ($idx as $name => $e) {
			$cache['modules'][$name] = array('title' => $e['title'], 'icon' => $e['icon'], 'description' => $e['description'], 'version' => $e['version'], 'requires_core' => $e['requires_core'], 'size' => $e['size']);
			// Module arrivé avec le core (archive complète) : la liste officielle
			// de sa version permet ensuite de repérer ses fichiers modifiés
			$local = sbModuleMeta($name);
			if ($local && $local['origin'] !== 'site' && $local['version'] === $e['version'] && sbModStoredList($name) === null) sbModStoreList($name, $e['version'], $e['files']);
		}
	} catch (Throwable $e) {
		$old = sbModCatalogCache();
		$cache = $old;
		$cache['checked'] = time();
		$cache['error'] = mb_substr($e->getMessage(), 0, 500);
	}
	sbSettingsSave(array('modules_catalog' => json_encode($cache, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)));
	return $cache;
}

/**
 * Modules publiés et locaux, avec leur état et les opérations possibles.
 * @return array nom => ligne
 */
function sbModCatalog() {
	$cache = sbModCatalogCache();
	$core = sbUpdInstalledVersion();
	sbModulesPresent(true);
	$installed = sbModulesInstalled(true);
	$names = array_unique(array_merge(array_keys($cache['modules']), array_keys(sbModulesPresent())));
	sort($names, SORT_STRING);
	// Modules remplacés par un module présent (store remplace boutique) :
	// plus proposés depuis GitHub, sinon l'ancien revient à côté du nouveau
	$replaced = array();
	foreach (sbModulesPresent() as $pm) {
		$old = (string) ($pm['replaces']['module'] ?? '');
		if ($old !== '') $replaced[$old] = true;
	}
	$out = array();
	foreach ($names as $name) {
		if (isset($replaced[$name]) && !sbModuleMeta($name)) continue;
		$local  = sbModuleMeta($name);
		// Module propre au site : jamais rapproché du module publié du même nom
		$site   = $local && $local['origin'] === 'site';
		$remote = $site ? null : ($cache['modules'][$name] ?? null);
		$row = array(
			'name'        => $name,
			'title'       => $local['title'] ?? ($remote['title'] ?? $name),
			'icon'        => $local['icon'] ?? ($remote['icon'] ?? 'puzzle-piece'),
			'description' => $local['description'] ?? ($remote['description'] ?? ''),
			'present'     => $local !== null,
			'local'       => $local['version'] ?? '',
			'installed'   => ($installed === null) ? ($local !== null) : isset($installed[$name]),
			'remote'      => $remote['version'] ?? '',
			'requires'    => $remote['requires_core'] ?? ($local['requires_core'] ?? ''),
			'local_requires' => $local['requires_core'] ?? '',
			'site'        => $site,
		);
		$row['remote_ok']  = $remote && version_compare($core, $remote['requires_core'], '>=');
		$row['local_ok']   = $local && version_compare($core, $local['requires_core'], '>=');
		$row['newer']      = $remote && $local && version_compare($remote['version'], $local['version'], '>');
		// Fichiers plus récents que la base (posés à la main, archive complète
		// décompressée) : migrations du module à passer sur place
		$dbv = ($installed !== null && isset($installed[$name])) ? (string) (($installed[$name]['db_version'] ?? '') ?: ($installed[$name]['version'] ?? '')) : '';
		$row['db_version'] = $dbv;
		$row['db_behind']  = $local && $row['installed'] && $dbv !== '' && version_compare($local['version'], $dbv, '>');
		// Deux cas : installé (Mettre à jour, Désinstaller), sinon Installer
		// (téléchargé d'abord s'il est absent). Opération impossible pour
		// l'instant : bouton grisé avec la raison
		$row['actions']    = array();
		$row['blocked']    = array();
		$why = function ($req) use ($core) { return 'Demande le core ' . $req . ' (installé : ' . $core . ') : mettez d\'abord SBUIADMIN à jour'; };
		if ($local && $row['installed']) {
			if ($row['db_behind'] && $row['local_ok']) $row['actions'][] = 'migrate';
			elseif ($row['db_behind']) $row['blocked']['migrate'] = $why($local['requires_core']);
			if ($row['newer'] && $row['remote_ok']) $row['actions'][] = 'update';
			elseif ($row['newer']) $row['blocked']['update'] = 'La version ' . $remote['version'] . ' ' . lcfirst($why($remote['requires_core']));
			$row['actions'][] = 'uninstall';
		} elseif ($local) {
			if ($row['local_ok']) $row['actions'][] = 'install'; else $row['blocked']['install'] = $why($local['requires_core']);
		} elseif ($remote) {
			if ($row['remote_ok']) $row['actions'][] = 'install'; else $row['blocked']['install'] = $why($remote['requires_core']);
		}
		// Dernière opération annulable (sauvegarde gardée, rien n'a bougé depuis)
		$rb = sbModRollbackInfo($name);
		$row['rollback'] = $rb ? array('label' => $rb['label'] ?? 'la dernière opération', 'date' => date('d/m/Y H:i', strtotime($rb['date'] ?? 'now'))) : null;
		if ($rb) $row['actions'][] = 'rollback';
		if (!$local) $row['state'] = 'absent';
		elseif (!$row['installed']) $row['state'] = 'present';
		elseif ($row['newer']) $row['state'] = 'outdated';
		elseif ($row['db_behind']) $row['state'] = 'dbbehind';
		else $row['state'] = 'installed';
		$out[$name] = $row;
	}
	return $out;
}

// -----------------------------------------------------------------------
// Verrou, sauvegarde et restauration
// -----------------------------------------------------------------------

function sbModLock() {
	$job = sbUpdLoadJob();
	if ($job && in_array($job['status'], array('running', 'restoring', 'rollback'), true) && !sbUpdStale($job)) throw new RuntimeException('Une mise à jour du core est en cours');
	$fh = fopen(sbModWorkDir() . '/modules.lock', 'c');
	if (!$fh || !flock($fh, LOCK_EX | LOCK_NB)) throw new RuntimeException('Une opération sur les modules est déjà en cours');
	return $fh;
}
function sbModUnlock($fh) {
	if ($fh) { flock($fh, LOCK_UN); fclose($fh); }
}

/** « Module Tableaux » (nom affiché) pour les messages */
function sbModLabel($name) {
	return 'Module ' . (sbModuleMeta($name)['title'] ?? $name);
}

/** « 1 fichier », « 2 fichiers » (pluriel accordé) */
function sbModN($n, $one, $many) {
	return (int) $n . ' ' . ((int) $n > 1 ? $many : $one);
}

/** Tables du module (noms complets), d'après son module.php */
function sbModScope($name) {
	$out = array();
	foreach (array_keys((array) (sbModuleMeta($name)['tables'] ?? array())) as $t) {
		if (preg_match('/^[A-Za-z0-9_]+$/', $t)) $out[] = sbUpdDbPrefix() . $t;
	}
	return $out;
}

/**
 * Sauvegarde EN CLAIR (chiffrée ensuite) : fichiers touchés, fichiers
 * ajoutés (à retirer au retour), état du module avant l'opération (ligne du
 * registre, liste livrée, version de base gardée) et, si $withDb, ses tables
 * SEULEMENT (périmètre limité : le reste de la base n'est jamais touché).
 * @return string chemin du zip en clair
 */
function sbModBackup($name, array $touched, array $added, $withDb) {
	$root  = sbUpdSiteRoot() . '/';
	$stamp = $name . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4));
	$plain = sbModWorkDir() . '/tmp-' . $stamp . '.zip';
	$z = new ZipArchive();
	if ($z->open($plain, ZipArchive::CREATE | ZipArchive::EXCL) !== true) throw new RuntimeException('Sauvegarde impossible à créer');
	foreach ($touched as $rel) {
		$t = sbUpdTargetRel($rel);
		if (is_file($root . $t) && !is_link($root . $t)) $z->addFile($root . $t, $t);
	}
	$dump = null;
	if ($withDb) {
		$dump = sbModWorkDir() . '/tmp-' . $stamp . '.sql';
		sbUpdDbDump(sbUpdDb(), sbUpdDbPrefix(), $dump, sbModScope($name));
		$z->addFile($dump, SB_UPD_DB_ENTRY);
	}
	$installed = sbModulesInstalled(true);
	$before = array(
		'row'     => $installed[$name] ?? null,
		'stored'  => sbModStoredList($name),
		'kept'    => sbModKept($name),
		'version' => sbModuleMeta($name)['version'] ?? '',
	);
	$z->addFromString(SB_UPD_ROLLBACK_META, json_encode(array('module' => $name, 'date' => date('c'), 'added' => array_values(array_map('sbUpdTargetRel', $added)), 'db' => (bool) $withDb, 'before' => $before), JSON_UNESCAPED_SLASHES));
	$closed = $z->close();
	if ($dump) @unlink($dump);
	if (!$closed) { @unlink($plain); throw new RuntimeException('Sauvegarde impossible à écrire'); }
	@chmod($plain, 0600);
	return $plain;
}

/** Sauvegardes chiffrées d'un module (plus récente d'abord) */
function sbModBackups($name) {
	$list = (array) @glob(sbModWorkDir() . '/backup-' . $name . '-*.zip.enc');
	usort($list, function ($a, $b) { return strcmp(basename($b), basename($a)); });
	return $list;
}

function sbModDeleteBackups($name) {
	foreach (sbModBackups($name) as $f) { @unlink($f); @unlink(substr($f, 0, -strlen('.zip.enc')) . '.json'); }
}

/**
 * Garde la sauvegarde chiffrée (une seule par module), avec à côté ce qu'il
 * faut pour proposer de l'annuler ($info : op, label, after = état attendu
 * pour que l'annulation reste possible, complete).
 */
function sbModKeepBackup($name, $plain, array $info = array()) {
	sbModDeleteBackups($name);
	$base = sbModWorkDir() . '/backup-' . $name . '-' . date('Ymd-His');
	sbUpdEncryptFile($plain, $base . '.zip.enc');
	@unlink($plain);
	$info += array('module' => $name, 'date' => date('c'), 'complete' => false);
	@file_put_contents($base . '.json', json_encode($info, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
	@chmod($base . '.json', 0600);
}

/** Remet l'état du module d'avant l'opération : registre, liste livrée, base gardée */
function sbModRestoreState($name, array $before) {
	$db = sbUpdDb();
	$t = sbModTable();
	$q = function ($v) use ($db) { return "'" . $db->real_escape_string((string) $v) . "'"; };
	$db->query("DELETE FROM $t WHERE `name` = " . $q($name));
	if (is_array($before['row'] ?? null)) {
		$r = $before['row'];
		$ok = $db->query("INSERT INTO $t (`name`, `version`, `db_version`, `installed_at`, `updated_at`) VALUES (" . $q($name) . ', ' . $q($r['version'] ?? '') . ', ' . $q($r['db_version'] ?? '') . ', '
			. (empty($r['installed_at']) ? 'NOW()' : $q($r['installed_at'])) . ', ' . (empty($r['updated_at']) ? 'NULL' : $q($r['updated_at'])) . ')');
		if (!$ok) throw new RuntimeException('Registre des modules : ' . $db->error);
	}
	if (is_array($before['stored'] ?? null)) sbModStoreList($name, (string) ($before['version'] ?? ''), $before['stored']);
	else @unlink(sbModWorkDir() . '/' . $name . '.json');
	sbModKept($name, (string) ($before['kept'] ?? ''));
	sbModulesInstalled(true);
}

/** Remet base (tables du module), fichiers et état depuis une sauvegarde en clair */
function sbModRestore($plain) {
	$root = sbUpdSiteRoot() . '/';
	$z = new ZipArchive();
	if ($z->open($plain, ZipArchive::RDONLY) !== true) throw new RuntimeException('Sauvegarde illisible');
	try {
		$meta = json_decode((string) $z->getFromName(SB_UPD_ROLLBACK_META), true);
		if (!is_array($meta)) throw new RuntimeException('Sauvegarde incomplète');
		if (!empty($meta['db'])) {
			$tmp = sbModWorkDir() . '/tmp-restore-' . bin2hex(random_bytes(4)) . '.sql';
			$in = $z->getStream(SB_UPD_DB_ENTRY);
			$out = $in ? fopen($tmp, 'wb') : false;
			if (!$in || !$out) throw new RuntimeException('Sauvegarde SQL illisible');
			stream_copy_to_stream($in, $out);
			fclose($in); fclose($out);
			try { sbUpdDbRestore(sbUpdDb(), sbUpdDbPrefix(), $tmp); } finally { @unlink($tmp); }
		}
		for ($i = 0; $i < $z->numFiles; $i++) {
			$n = $z->getNameIndex($i);
			if (substr($n, -1) === '/' || strpos($n, '.sbupd/') === 0) continue;
			if (!sbUpdSafeRel($n) || sbUpdIsProtected(sbUpdArchiveRel($n))) throw new RuntimeException('Chemin refusé dans la sauvegarde : ' . $n);
			$data = $z->getFromIndex($i);
			sbUpdWriteFile($root . $n, $data, hash('sha256', (string) $data), $n);
		}
		$gone = array();
		foreach ((array) ($meta['added'] ?? array()) as $t) {
			if (is_string($t) && sbUpdSafeRel($t) && !sbUpdIsProtected(sbUpdArchiveRel($t)) && is_file($root . $t) && !is_link($root . $t)) { @unlink($root . $t); $gone[] = $t; }
		}
		sbModPruneDirs($gone);
		if (is_array($meta['before'] ?? null) && !empty($meta['module'])) sbModRestoreState($meta['module'], $meta['before']);
	} finally {
		$z->close();
		sbUpdClearCaches();
		sbModulesPresent(true);
	}
}

/**
 * Dernière opération annulable : sauvegarde complète, et module toujours
 * dans l'état qu'elle a laissé (sinon l'annuler écraserait ce qui a suivi).
 * @return array|null infos de la sauvegarde (op, label, date, db...)
 */
function sbModRollbackInfo($name) {
	$b = sbModBackups($name);
	if (!$b) return null;
	$info = json_decode((string) @file_get_contents(substr($b[0], 0, -strlen('.zip.enc')) . '.json'), true);
	if (!is_array($info) || empty($info['complete']) || !is_array($info['after'] ?? null)) return null;
	$installed = sbModulesInstalled();
	$now = array('installed' => is_array($installed) && isset($installed[$name]), 'version' => sbModuleMeta($name)['version'] ?? '');
	if ($now['installed'] !== (bool) $info['after']['installed'] || $now['version'] !== (string) $info['after']['version']) return null;
	$info['file'] = $b[0];
	return $info;
}

/** Retire les dossiers vides laissés par des fichiers supprimés */
function sbModPruneDirs(array $targets) {
	$root = sbUpdSiteRoot() . '/';
	$dirs = array();
	foreach ($targets as $t) for ($d = dirname($t); $d !== '.' && $d !== ''; $d = dirname($d)) $dirs[$d] = substr_count($d, '/');
	arsort($dirs);
	foreach (array_keys($dirs) as $d) {
		if (in_array($d, array('datas', 'datas/modules', sbUpdAdminDir(), sbUpdAdminDir() . '/datas', sbUpdAdminDir() . '/datas/modules', sbUpdAdminDir() . '/img'), true)) continue;
		@rmdir($root . $d); // échoue sans dommage si le dossier n'est pas vide
	}
}

// -----------------------------------------------------------------------
// Base de données d'un module
// -----------------------------------------------------------------------

/**
 * Schéma d'installation (install.php du module) : CREATE TABLE IF NOT EXISTS
 * puis ALTER, ces derniers seulement pour les tables créées ici (une table
 * gardée à la désinstallation n'est pas retouchée). Rien d'autre n'est admis.
 * @return array tables créées
 */
function sbModRunSql(mysqli $db, $prefix, $sql) {
	if (!preg_match('/^[A-Za-z0-9_]*$/', (string) $prefix)) throw new RuntimeException('Préfixe de table refusé');
	$created = $skipped = array();
	foreach (preg_split('/;\s*(\n|$)/', str_replace('<DB_PREFIX>', $prefix, (string) $sql)) as $stmt) {
		$stmt = trim($stmt);
		if ($stmt === '') continue;
		if (preg_match('/^CREATE TABLE IF NOT EXISTS `([A-Za-z0-9_]+)` \(/', $stmt, $m)) {
			$r = $db->query("SHOW TABLES LIKE '" . $db->real_escape_string($m[1]) . "'");
			if ($r && $r->num_rows > 0) { $skipped[$m[1]] = true; continue; }
			if (!$db->query($stmt)) throw new RuntimeException('Création de ' . $m[1] . ' impossible : ' . $db->error);
			$created[$m[1]] = true;
		} elseif (preg_match('/^ALTER TABLE `([A-Za-z0-9_]+)`\s/', $stmt, $m)) {
			if (!isset($created[$m[1]])) continue;
			if (!$db->query($stmt)) throw new RuntimeException('Modification de ' . $m[1] . ' impossible : ' . $db->error);
		} else {
			throw new RuntimeException('Instruction SQL refusée dans le schéma du module : ' . substr($stmt, 0, 60));
		}
	}
	return array_keys($created);
}

/** Migrations du module (datas/modules/<nom>/migrations/<version>.php) entre deux versions */
function sbModMigrate($name, $from, $to) {
	$done = array();
	foreach (sbUpdPendingMigrations($from, $to, sbModulesDir() . $name . '/migrations') as $v => $file) {
		$mig = sbUpdLoadMigration($file);
		call_user_func($mig['up'], new SbMigration(sbUpdDb(), sbUpdDbPrefix()));
		$done[] = $v;
	}
	return $done;
}

function sbModSaveRow($name, $version, $dbVersion, $new) {
	$db = sbUpdDb();
	$t = sbModTable();
	$q = function ($v) use ($db) { return "'" . $db->real_escape_string((string) $v) . "'"; };
	$sql = $new
		? "INSERT INTO $t (`name`, `version`, `db_version`, `installed_at`) VALUES (" . $q($name) . ', ' . $q($version) . ', ' . $q($dbVersion) . ', NOW())'
		: "UPDATE $t SET `version` = " . $q($version) . ', `db_version` = ' . $q($dbVersion) . ', `updated_at` = NOW() WHERE `name` = ' . $q($name);
	if (!$db->query($sql)) throw new RuntimeException('Registre des modules impossible à mettre à jour : ' . $db->error);
	sbModulesInstalled(true);
}

/**
 * Version de base d'un module désinstallé dont les tables sont gardées
 * (réglage modules_kept, JSON nom => version) ; $set : '' efface
 */
function sbModKept($name, $set = null) {
	$all = json_decode(sbSetting('modules_kept'), true);
	if (!is_array($all)) $all = array();
	if ($set === null) return (string) ($all[$name] ?? '');
	if ($set === '') unset($all[$name]); else $all[$name] = $set;
	sbSettingsSave(array('modules_kept' => $all ? json_encode($all) : ''));
	return $set;
}

/** Tables du module qui existent en base */
function sbModExistingTables($name) {
	$meta = sbModuleMeta($name);
	$db = sbUpdDb();
	$out = array();
	foreach (array_keys((array) ($meta['tables'] ?? array())) as $t) {
		if (!preg_match('/^[A-Za-z0-9_]+$/', $t)) continue;
		$r = $db->query("SHOW TABLES LIKE '" . $db->real_escape_string(sbUpdDbPrefix() . $t) . "'");
		if ($r && $r->num_rows > 0) $out[] = $t;
	}
	return $out;
}

/** Contenus qui utilisent encore les codes courts du module */
function sbModShortcodeUses($name) {
	$meta = sbModuleMeta($name);
	$codes = (array) ($meta['shortcodes'] ?? array());
	if (!$codes) return array();
	$db = sbUpdDb();
	$p = sbUpdDbPrefix();
	$sources = array('sb_pages' => array('Pages', 'content'), 'sb_blocs' => array('Blocs', 'content'), 'sb_news' => array('Actualités', 'desc_full'), 'sb_tabbs_tab' => array('Onglets', 'content'));
	$out = array();
	foreach ($sources as $table => $info) {
		$r = $db->query("SHOW TABLES LIKE '" . $db->real_escape_string($p . $table) . "'");
		if (!$r || $r->num_rows === 0) continue;
		$or = array();
		foreach ($codes as $c) if (preg_match('/^[a-z0-9_]+$/', $c)) $or[] = "`" . $info[1] . "` REGEXP 'name=" . $c . "([^a-z0-9_]|$)'";
		if (!$or) continue;
		$r = $db->query('SELECT COUNT(*) FROM `' . $p . $table . '` WHERE ' . implode(' OR ', $or));
		$n = $r ? (int) $r->fetch_row()[0] : 0;
		if ($n > 0) $out[] = $info[0] . ' : ' . $n;
	}
	return $out;
}

// -----------------------------------------------------------------------
// Téléchargement et plan de copie
// -----------------------------------------------------------------------

/**
 * Télécharge et vérifie l'archive d'un module (dernière version publiée).
 * @return array entry (index), zip (chemin), map (chemin archive => index zip)
 */
function sbModDownload($name) {
	if (!class_exists('ZipArchive')) throw new RuntimeException('Extension zip absente');
	list($m, $assets, $idx) = sbModFetchLatest(30);
	if (!isset($idx[$name])) throw new RuntimeException('Module ' . $name . ' absent de la version ' . $m['version']);
	$e = $idx[$name];
	if (!version_compare(sbUpdInstalledVersion(), $e['requires_core'], '>=')) throw new RuntimeException('Le module ' . $name . ' ' . $e['version'] . ' demande le core ' . $e['requires_core'] . ' : mettez d\'abord SBUIADMIN à jour');
	if (!isset($assets[$e['file']])) throw new RuntimeException('Archive ' . $e['file'] . ' absente de la version publiée');
	$zipPath = sbModWorkDir() . '/' . $e['file'];
	// Archives abandonnées (préparation sans suite) : retirées après un jour
	foreach ((array) @glob(sbModWorkDir() . '/sbuiadmin-module-*.zip') as $old) if (is_file($old) && filemtime($old) < time() - 86400) @unlink($old);
	// Déjà téléchargée par la préparation et toujours conforme à l'index signé : réutilisée
	$reuse = is_file($zipPath) && filesize($zipPath) === $e['size'] && hash_equals($e['sha256'], hash_file('sha256', $zipPath));
	if (!$reuse) sbUpdGet($assets[$e['file']], $e['size'], $zipPath, 300);
	if (filesize($zipPath) !== $e['size'] || !hash_equals($e['sha256'], hash_file('sha256', $zipPath))) {
		@unlink($zipPath);
		throw new RuntimeException('L\'archive du module ne correspond pas à l\'index signé');
	}
	$z = new ZipArchive();
	if ($z->open($zipPath, ZipArchive::RDONLY) !== true) throw new RuntimeException('Archive du module illisible');
	$map = array();
	$total = 0;
	for ($i = 0; $i < $z->numFiles; $i++) {
		$st = $z->statIndex($i);
		$n = (string) $st['name'];
		if ($n === SB_UPD_ROOT || substr($n, -1) === '/') continue;
		if (strpos($n, SB_UPD_ROOT) !== 0) throw new RuntimeException('Entrée hors du dossier ' . SB_UPD_ROOT . ' : ' . $n);
		$rel = substr($n, strlen(SB_UPD_ROOT));
		if (!isset($e['files'][$rel])) throw new RuntimeException('Fichier absent de l\'index signé : ' . $rel);
		if ($z->getExternalAttributesIndex($i, $opsys, $attr) && $opsys === ZipArchive::OPSYS_UNIX && ((($attr >> 16) & 0170000) === 0120000)) throw new RuntimeException('Lien symbolique refusé : ' . $rel);
		$total += (int) $st['size'];
		if ($total > SB_UPD_MAX_UNZIP) throw new RuntimeException('Taille décompressée trop importante');
		$map[$rel] = $i;
	}
	$z->close();
	foreach (array_keys($e['files']) as $rel) if (!isset($map[$rel])) throw new RuntimeException('Fichier de l\'index absent de l\'archive : ' . $rel);
	return array('entry' => $e, 'zip' => $zipPath, 'map' => $map);
}

/**
 * Plan de copie : fichiers à écrire, à retirer, modifiés localement.
 * $old : liste livrée précédemment (null : inconnue).
 */
function sbModFilePlan(array $new, $old) {
	$root = sbUpdSiteRoot() . '/';
	$write = $delete = $modified = array();
	foreach ($new as $rel => $hash) {
		$local = sbUpdHashFile($root . sbUpdTargetRel($rel));
		if ($local === $hash) continue;
		$write[] = $rel;
		if ($local !== null && ($old === null || !isset($old[$rel]) || $old[$rel] !== $local)) $modified[] = $rel;
	}
	if ($old !== null) {
		foreach ($old as $rel => $hash) {
			if (isset($new[$rel]) || !sbUpdSafeRel($rel)) continue;
			$local = sbUpdHashFile($root . sbUpdTargetRel($rel));
			if ($local === null) continue;
			if ($local === $hash) $delete[] = $rel; else $modified[] = $rel;
		}
	}
	sort($modified, SORT_STRING);
	return array('write' => $write, 'delete' => $delete, 'modified' => array_values(array_unique($modified)));
}

/**
 * Liste officielle des fichiers d'un module : celle livrée par cette page
 * si elle est de la version présente, sinon celle de l'index publié pour
 * cette version (module arrivé avec le core, ou changé hors de cette page :
 * FTP, git), alors gardée ; null si introuvable.
 */
function sbModOfficialList($name) {
	if (sbModuleIsSite($name)) return null; // module du site : aucune liste publiée ne le concerne
	$local = sbModuleMeta($name);
	if (!$local) return null;
	$j = json_decode((string) @file_get_contents(sbModWorkDir() . '/' . $name . '.json'), true);
	if (is_array($j) && is_array($j['files'] ?? null) && (string) ($j['version'] ?? '') === $local['version']) return $j['files'];
	$files = sbModFindPublishedList($name, $local['version']);
	if ($files === null) return null;
	sbModStoreList($name, $local['version'], $files);
	return $files;
}

/**
 * Liste des fichiers d'une version de module, cherchée dans les versions
 * publiées (la plus récente d'abord, manifeste signé vérifié pour chacune) ;
 * arrêt à la première version sans index des modules (antérieure à 4.29).
 * @return array|null chemin => sha256
 */
function sbModFindPublishedList($name, $version, $max = 15) {
	try {
		$rels = json_decode(sbUpdGet('https://api.github.com/repos/' . SB_UPD_REPO . '/releases?per_page=' . (int) $max, 2 * 1024 * 1024, null, 20), true);
	} catch (Throwable $e) {
		return null;
	}
	foreach ((array) $rels as $rel) {
		$tag = (string) ($rel['tag_name'] ?? '');
		if (!preg_match('/^v\d+\.\d+(\.\d+)?$/', $tag) || !empty($rel['draft']) || !empty($rel['prerelease'])) continue;
		// Fichiers joints lus dans la liste elle-même : un seul appel à l'API
		// GitHub (limitée à 60 par heure sans authentification), le reste en
		// téléchargements ; manifeste signé vérifié comme pour toute version
		$assets = array();
		foreach ((array) ($rel['assets'] ?? array()) as $a) {
			if (isset($a['name'], $a['browser_download_url']) && sbUpdAllowedUrl($a['browser_download_url'])) $assets[$a['name']] = $a['browser_download_url'];
		}
		if (!isset($assets[SB_UPD_MANIFEST], $assets[SB_UPD_MANIFEST . '.sig'])) continue;
		try {
			$m = sbUpdVerifyManifest(sbUpdGet($assets[SB_UPD_MANIFEST], 64 * 1024, null, 20), sbUpdGet($assets[SB_UPD_MANIFEST . '.sig'], 4096, null, 20));
			if (($m['tag'] ?? '') !== $tag) continue;
			$idx = sbUpdFetchModulesIndex($m, $assets, 20);
		} catch (Throwable $e) {
			continue;
		}
		if ($idx === null) break;
		if (isset($idx[$name]) && $idx[$name]['version'] === $version) return $idx[$name]['files'];
		if (isset($idx[$name]) && version_compare($idx[$name]['version'], $version, '<')) break;
	}
	return null;
}

/**
 * Fichiers d'un module présent et ceux modifiés sur le site (comparés à la
 * liste officielle) ; sans liste : non vérifiables, aucun déclaré modifié
 */
function sbModDeletePlan($name) {
	$root = sbUpdSiteRoot() . '/';
	$old = sbModOfficialList($name);
	$files = sbModLocalFiles($name);
	$modified = array();
	if ($old !== null) {
		foreach ($files as $rel) {
			if (!isset($old[$rel]) || $old[$rel] !== sbUpdHashFile($root . sbUpdTargetRel($rel))) $modified[] = $rel;
		}
	}
	return array('files' => $files, 'modified' => $modified, 'unchecked' => $old === null);
}

// -----------------------------------------------------------------------
// Opérations
// -----------------------------------------------------------------------

/** Contrôles communs : module connu, opération possible */
function sbModCheck($name, $op) {
	if (!sbModuleValidName($name) || in_array($name, sbModuleCore(), true)) throw new RuntimeException('Module refusé');
	if (!in_array($op, array('install', 'update', 'migrate', 'uninstall', 'rollback'), true)) throw new RuntimeException('Opération inconnue');
	$cat = sbModCatalog();
	if (!isset($cat[$name])) throw new RuntimeException('Module inconnu : ' . $name);
	if (!in_array($op, $cat[$name]['actions'], true)) {
		throw new RuntimeException('Opération impossible pour ' . $name . ' dans son état actuel');
	}
	return $cat[$name];
}

/** Lignes de chaque table du module présente en base */
function sbModTableRows($name) {
	$db = sbUpdDb();
	$out = array();
	foreach (sbModExistingTables($name) as $t) {
		$r = $db->query('SELECT COUNT(*) FROM `' . sbUpdDbPrefix() . $t . '`');
		$out[$t] = $r ? (int) $r->fetch_row()[0] : 0;
	}
	return $out;
}

/** module.php contenu dans une archive téléchargée (tableau vide si absent) */
function sbModZipMeta($zipPath, $name) {
	$z = new ZipArchive();
	if ($z->open($zipPath, ZipArchive::RDONLY) !== true) return array();
	$src = $z->getFromName(SB_UPD_ROOT . 'datas/modules/' . $name . '/module.php');
	$z->close();
	if ($src === false) return array();
	$tmp = sbModWorkDir() . '/tmp-meta-' . bin2hex(random_bytes(4)) . '.php';
	file_put_contents($tmp, $src);
	try { $meta = include $tmp; } finally { @unlink($tmp); }
	return is_array($meta) ? $meta : array();
}

/** Tables déclarées par le module.php contenu dans une archive téléchargée */
function sbModZipTables($zipPath, $name) {
	return array_keys((array) (sbModZipMeta($zipPath, $name)['tables'] ?? array()));
}

/**
 * Ce que ferait l'opération (rien n'est écrit). Installer un module absent
 * et Mettre à jour téléchargent et vérifient l'archive.
 */
function sbModPlan($name, $op) {
	$row = sbModCheck($name, $op);
	// notes : avertissements (orange) ; info : simples informations (gris)
	$plan = array('name' => $name, 'op' => $op, 'title' => $row['title'], 'modified' => array(), 'unchecked' => false, 'notes' => array(), 'info' => array());
	if ($op === 'update' || ($op === 'install' && !$row['present'])) {
		$dl = sbModDownload($name);
		$old = $row['present'] ? sbModOfficialList($name) : sbModStoredList($name);
		$fp = sbModFilePlan($dl['entry']['files'], $old);
		$plan['version'] = $dl['entry']['version'];
		$plan['write'] = count($fp['write']);
		$plan['delete'] = count($fp['delete']);
		$plan['modified'] = $fp['modified'];
		$plan['unchecked'] = ($op === 'update' && $old === null);
		$plan['download'] = ($op === 'install');
		if ($op === 'install') {
			$zmeta = sbModZipMeta($dl['zip'], $name);
			$plan['tables_new'] = array_keys((array) ($zmeta['tables'] ?? array()));
			$plan['replaces'] = is_array($zmeta['replaces'] ?? null) ? $zmeta['replaces'] : array();
		}
		// Archive gardée : l'exécution la réutilise si elle est toujours conforme
		if ($op === 'update' && $row['installed']) $plan['info'][] = 'Base de données sauvegardée avant la mise à jour (migrations éventuelles du module appliquées ensuite).';
	}
	if ($op === 'install') {
		$existing = array();
		if ($row['present']) {
			$existing = sbModExistingTables($name);
			$plan['tables_new'] = array_values(array_diff(array_keys((array) (sbModuleMeta($name)['tables'] ?? array())), $existing));
		}
		$plan['tables_kept'] = $existing;
		if ($existing) $plan['info'][] = 'Tables déjà présentes (données gardées) : ' . implode(', ', $existing) . '.';
		// Reprise d'un ancien module renommé (store remplace boutique)
		try {
			$ti = sbModReplaceInfo($name, $row['present'] ? null : ($plan['replaces'] ?? array()));
			if ($ti) $plan['tables_new'] = array_values(array_diff($plan['tables_new'], array_values($ti['tables']))); // reprises, pas créées
			if ($ti) $plan['notes'][] = 'Reprise de « ' . $ti['meta']['title'] . ' » ' . $ti['meta']['version'] . ' (module ' . $ti['old'] . ') : ses données sont gardées ('
				. sbModN(count($ti['tables']), 'table renommée', 'tables renommées') . ', droits repris), ses fichiers retirés ; sauvegarde chiffrée faite avant. L\'annulation de l\'installation ne sera pas proposée.';
		} catch (Throwable $e) {
			$plan['notes'][] = $e->getMessage();
		}
		unset($plan['replaces']);
	}
	if ($op === 'migrate') {
		$plan['from'] = $row['db_version'];
		$plan['version'] = $row['local'];
		$plan['migrations'] = array_keys(sbUpdPendingMigrations($row['db_version'], $row['local'], sbModulesDir() . $name . '/migrations'));
		$plan['info'][] = 'Base de données du module sauvegardée avant (chiffrée), opération annulable.';
	}
	if ($op === 'rollback') {
		$rb = sbModRollbackInfo($name);
		if (!$rb) throw new RuntimeException('Rien à annuler pour ce module');
		$plan['label'] = $rb['label'] ?? 'la dernière opération';
		$plan['date'] = date('d/m/Y H:i', strtotime($rb['date'] ?? 'now'));
		if (!empty($rb['before_absent'])) {
			$plan['info'][] = 'Le module, installé depuis GitHub, sera retiré : ses tables et ses fichiers supprimés (sauvegarde chiffrée faite avant).';
		} else {
			if (!empty($rb['db'])) $plan['notes'][] = 'Les tables du module reviennent à leur état du ' . $plan['date'] . ' : ce qui y a été saisi depuis sera perdu.';
			if (!empty($rb['files'])) $plan['info'][] = 'Les fichiers du module reviennent à leur état d\'avant (' . (int) $rb['files'] . ' concerné' . ((int) $rb['files'] > 1 ? 's' : '') . ').';
		}
	}
	if ($op === 'uninstall') {
		// Ce que supprimerait « Supprimer le module » (affiché si la case est cochée)
		$dp = sbModDeletePlan($name);
		$plan['rows'] = sbModTableRows($name);
		$plan['uses'] = sbModShortcodeUses($name);
		$plan['files'] = count($dp['files']);
		$plan['modified'] = $dp['modified'];
		$plan['unchecked'] = $dp['unchecked'];
		if (!$row['local_ok']) $plan['notes'][] = 'Attention : ce module demande le core ' . $row['local_requires'] . ' (installé : ' . sbUpdInstalledVersion() . '). Une fois désinstallé, il ne pourra être réinstallé qu\'après la mise à jour de SBUIADMIN.';
		if ($plan['uses']) $plan['notes'][] = 'Codes courts du module encore utilisés (ils s\'afficheront vides) : ' . implode(', ', $plan['uses']) . '.';
	}
	return $plan;
}

/**
 * Exécute une opération sur un module.
 * @param array $opt confirm (fichiers modifiés localement), remove (désinstallation :
 *                   « Supprimer le module » = données, tables et fichiers)
 * @return array résumé (step : texte)
 */
function sbModRun($name, $op, array $opt = array()) {
	@set_time_limit(300);
	ignore_user_abort(true);
	$row = sbModCheck($name, $op);
	$confirm = !empty($opt['confirm']);
	$user = isset($_SESSION['sbuiadmin_user_name']) ? (string) $_SESSION['sbuiadmin_user_name'] : '';
	$lock = sbModLock();
	try {
		switch ($op) {
			case 'update':
				$out = sbModRunFiles($name, 'update', $row, $confirm);
				break;
			case 'rollback':
				$out = sbModRunRollback($name);
				break;
			case 'migrate':
				$out = sbModRunMigrate($name, $row);
				break;
			case 'install':
				if ($row['present']) {
					$out = sbModRunInstall($name, $row);
					break;
				}
				// Absent : téléchargé, puis installé ; installation en échec =
				// fichiers téléchargés retirés, le module redevient absent ;
				// annulée plus tard = module retiré (il était absent)
				$dl = sbModRunFiles($name, 'download', $row, $confirm);
				sbModulesPresent(true);
				try {
					$out = sbModRunInstall($name, sbModCatalog()[$name], true);
				} catch (Throwable $e) {
					try { sbModRunDelete($name, true); } catch (Throwable $e2) { }
					throw $e;
				}
				$out['step'] = sbModLabel($name) . ' ' . $dl['to'] . ' téléchargé (' . sbModN($dl['files'], 'fichier', 'fichiers') . ') et installé' . substr($out['step'], strpos($out['step'], ' installé') + strlen(' installé'));
				$out['files'] = $dl['files'];
				break;
			default:
				$out = sbModRunUninstall($name, !empty($opt['remove']));
		}
	} catch (Throwable $e) {
		sbUpdLog(sbModLabel($name) . ' : ' . $op . ' en échec (' . $e->getMessage() . ')', $user);
		throw $e;
	} finally {
		sbModUnlock($lock);
	}
	sbUpdHistory(array('from' => $out['from'] ?? '', 'to' => $out['to'] ?? '', 'user' => $user, 'write' => array_fill(0, (int) ($out['files'] ?? 0), 1)), 'module ' . $name . ' : ' . $out['label']);
	sbUpdLog(sbModLabel($name) . ' : ' . $out['step'], $user);
	sbModulesPresent(true);
	sbModulesInstalled(true);
	return array('status' => 'done', 'step' => $out['step']);
}

/** Télécharger (module absent, avant Installer) ou mettre à jour (module présent) */
function sbModRunFiles($name, $op, array $row, $confirm) {
	if ($op === 'download' && $row['present']) throw new RuntimeException('Le module ' . $name . ' est déjà présent');
	if (sbModuleIsSite($name)) throw new RuntimeException('Le module ' . $name . ' est propre au site : rien à télécharger depuis GitHub');
	if ($op === 'update' && !$row['present']) throw new RuntimeException('Le module ' . $name . ' n\'est pas présent');
	$dl = sbModDownload($name);
	$new = $dl['entry']['files'];
	$old = $row['present'] ? sbModOfficialList($name) : sbModStoredList($name);
	$fp = sbModFilePlan($new, $old);
	if (($fp['modified'] || ($op === 'update' && $old === null)) && !$confirm) {
		// Archive gardée : le nouvel essai, confirmé, la réutilise
		throw new RuntimeException('Confirmez le remplacement des fichiers modifiés localement');
	}
	$installed = $row['installed'];
	$dbRow = $installed ? sbModRow($name) : null;
	$fromVersion = $row['local'];
	$root = sbUpdSiteRoot() . '/';
	$added = array();
	foreach ($fp['write'] as $rel) if (!is_file($root . sbUpdTargetRel($rel))) $added[] = $rel;
	$plain = sbModBackup($name, array_merge($fp['write'], $fp['delete']), $added, $installed);
	$migrations = array();
	$v = $dl['entry']['version'];
	try {
		$z = new ZipArchive();
		if ($z->open($dl['zip'], ZipArchive::RDONLY) !== true) throw new RuntimeException('Archive du module illisible');
		foreach ($fp['write'] as $rel) sbUpdWriteFile($root . sbUpdTargetRel($rel), $z->getFromIndex($dl['map'][$rel]), $new[$rel], $rel);
		$z->close();
		$targets = array();
		foreach ($fp['delete'] as $rel) { @unlink($root . sbUpdTargetRel($rel)); $targets[] = sbUpdTargetRel($rel); }
		sbModPruneDirs($targets);
		sbUpdClearCaches();
		sbModulesPresent(true);
		if (sbModuleMeta($name) === null) throw new RuntimeException('module.php absent ou invalide après la copie');
		if ($installed) {
			$migrations = sbModMigrate($name, ($dbRow['db_version'] ?? '') ?: $fromVersion, $dl['entry']['version']);
			sbModSaveRow($name, $dl['entry']['version'], $dl['entry']['version'], false);
		}
		foreach ($fp['write'] as $rel) {
			if (sbUpdHashFile($root . sbUpdTargetRel($rel)) !== $new[$rel]) throw new RuntimeException('Fichier non conforme après copie : ' . $rel);
		}
	} catch (Throwable $e) {
		try {
			sbModRestore($plain);
			$msg = $e->getMessage() . ' : retour automatique effectué, module inchangé';
		} catch (Throwable $e2) {
			$msg = $e->getMessage() . ' ET retour automatique en échec (' . $e2->getMessage() . ') : sauvegarde gardée dans le dossier de travail';
			sbModKeepBackup($name, $plain);
			$plain = null;
		}
		if ($plain) @unlink($plain);
		@unlink($dl['zip']);
		throw new RuntimeException($msg);
	}
	sbModStoreList($name, $v, $new);
	if ($op === 'update') {
		sbModKeepBackup($name, $plain, array('op' => 'update', 'label' => 'la mise à jour ' . $fromVersion . ' → ' . $v, 'db' => $installed, 'files' => count($fp['write']) + count($fp['delete']),
			'after' => array('installed' => $installed, 'version' => $v), 'complete' => true));
	} else {
		@unlink($plain); // téléchargement avant installation : l'annulation retirera le module
	}
	@unlink($dl['zip']);
	$step = ($op === 'download')
		? 'téléchargé en ' . $v . ' (' . sbModN(count($fp['write']), 'fichier', 'fichiers') . ') : il reste à l\'installer'
		: 'mis à jour ' . $fromVersion . ' → ' . $v . ' (' . sbModN(count($fp['write']), 'fichier écrit', 'fichiers écrits') . ', ' . sbModN(count($fp['delete']), 'retiré', 'retirés') . ($migrations ? ', ' . sbModN(count($migrations), 'migration', 'migrations') : '') . ')';
	return array('step' => sbModLabel($name) . ' ' . $step, 'label' => ($op === 'download' ? 'téléchargé ' : 'mis à jour ') . $v, 'from' => $fromVersion, 'to' => $v, 'files' => count($fp['write']));
}

/**
 * Reprise d'un module renommé ('replaces' dans module.php, ex. store qui
 * remplace boutique) : seulement si l'ancien est installé, se déclare
 * officiel (auteur attendu, pas 'origin' => 'site') et que CHACUN de ses
 * fichiers est celui de la liste publiée. Un module du même nom propre au
 * site (autre auteur, marqué site, sans module.php) n'est jamais touché :
 * le nouveau s'installe alors à côté, tables neuves.
 * @return array|null ['old', 'meta', 'tables' (ancienne => nouvelle, présentes), 'files']
 * @throws RuntimeException ancien module officiel modifié ou invérifiable
 */
function sbModReplaceInfo($name, ?array $r = null) {
	if ($r === null) $r = sbModuleMeta($name)['replaces'] ?? array();
	$old = (string) ($r['module'] ?? '');
	if (!sbModuleValidName($old) || $old === $name || in_array($old, sbModuleCore(), true)) return null;
	$installed = sbModulesInstalled(true);
	if (!is_array($installed) || !isset($installed[$old])) return null;
	$om = sbModuleMeta($old);
	if (!$om || $om['origin'] === 'site' || $om['author'] !== (string) ($r['author'] ?? '')) return null;
	$label = sbModLabel($old) . ' ' . $om['version'];
	$list = sbModOfficialList($old);
	if ($list === null) throw new RuntimeException($label . ' : liste officielle de ses fichiers introuvable (GitHub injoignable ?), reprise impossible à vérifier. Réessayez plus tard');
	$root = sbUpdSiteRoot() . '/';
	$files = sbModLocalFiles($old);
	foreach ($files as $rel) {
		if (!isset($list[$rel]) || $list[$rel] !== sbUpdHashFile($root . sbUpdTargetRel($rel))) {
			throw new RuntimeException($label . ' modifié sur ce site (' . $rel . ') : reprise refusée. Si c\'est la boutique propre au site, ajoutez \'origin\' => \'site\' dans son module.php');
		}
	}
	$db = sbUpdDb();
	$pre = sbUpdDbPrefix();
	$exists = function ($t) use ($db) { $q = $db->query("SHOW TABLES LIKE '" . $db->real_escape_string($t) . "'"); return $q && $q->num_rows > 0; };
	$tables = array();
	foreach ((array) ($r['tables'] ?? array()) as $from => $to) {
		if (!preg_match('/^[A-Za-z0-9_]+$/', (string) $from) || !preg_match('/^[A-Za-z0-9_]+$/', (string) $to)) throw new RuntimeException('Tables de reprise invalides');
		if (!$exists($pre . $from)) continue;
		if ($exists($pre . $to)) throw new RuntimeException('Table ' . $pre . $to . ' déjà présente : reprise de ' . $label . ' impossible');
		$tables[$from] = $to;
	}
	return array('old' => $old, 'meta' => $om, 'tables' => $tables, 'files' => $files);
}

/**
 * Exécute la reprise (sauvegarde de l'ancien module faite par l'appelant) :
 * tables renommées, droits et réglage « modules » repris, ancien module
 * retiré du registre et du disque.
 * @return array ce qu'il faut pour revenir en arrière (sbModTakeoverUndo)
 */
function sbModTakeover($name, array $info) {
	$db = sbUpdDb();
	$pre = sbUpdDbPrefix();
	$old = $info['old'];
	$q = function ($v) use ($db) { return "'" . $db->real_escape_string((string) $v) . "'"; };
	$undo = array('tables' => array(), 'rights' => false, 'modules' => null);
	foreach ($info['tables'] as $from => $to) {
		if (!$db->query('RENAME TABLE `' . $pre . $from . '` TO `' . $pre . $to . '`')) throw new RuntimeException('Renommage de ' . $pre . $from . ' impossible : ' . $db->error);
		$undo['tables'][$to] = $from;
	}
	// Droits : « boutique » et « boutique:xxx » deviennent « store », « store:xxx »
	$rt = $pre . 'sb_users_rights';
	$r = $db->query("SHOW TABLES LIKE " . $q($rt));
	if ($r && $r->num_rows > 0) {
		if (!$db->query("UPDATE `$rt` SET `module` = CONCAT(" . $q($name) . ", SUBSTRING(`module`, " . (strlen($old) + 1) . ")) WHERE `module` = " . $q($old) . " OR `module` LIKE " . $q($old . ':%'))) throw new RuntimeException('Droits : ' . $db->error);
		$undo['rights'] = true;
	}
	// Réglage « modules » (modules autorisés de l'admin)
	$ct = $pre . 'sb_config';
	$r = $db->query("SELECT `content` FROM `$ct` WHERE `config` = 'modules'");
	$row = $r ? $r->fetch_row() : null;
	if ($row) {
		$list = array_map('trim', explode(',', (string) $row[0]));
		if (in_array($old, $list, true)) {
			$undo['modules'] = (string) $row[0];
			$list = array_values(array_unique(array_map(function ($m) use ($old, $name) { return $m === $old ? $name : $m; }, $list)));
			if (!$db->query("UPDATE `$ct` SET `content` = " . $q(implode(',', $list)) . " WHERE `config` = 'modules'")) throw new RuntimeException('Réglage modules : ' . $db->error);
		}
	}
	if (!$db->query('DELETE FROM `' . str_replace('`', '', sbModTable()) . '` WHERE `name` = ' . $q($old))) throw new RuntimeException('Registre des modules : ' . $db->error);
	$root = sbUpdSiteRoot() . '/';
	$targets = array();
	foreach ($info['files'] as $rel) { @unlink($root . sbUpdTargetRel($rel)); $targets[] = sbUpdTargetRel($rel); }
	sbModPruneDirs($targets);
	@unlink(sbModWorkDir() . '/' . $old . '.json');
	sbModulesPresent(true);
	sbModulesInstalled(true);
	return $undo;
}

/** Défait la partie de la reprise que la sauvegarde de l'ancien module ne couvre pas */
function sbModTakeoverUndo($name, $old, array $undo) {
	$db = sbUpdDb();
	$pre = sbUpdDbPrefix();
	$q = function ($v) use ($db) { return "'" . $db->real_escape_string((string) $v) . "'"; };
	if (!empty($undo['rights'])) $db->query('UPDATE `' . $pre . 'sb_users_rights` SET `module` = CONCAT(' . $q($old) . ', SUBSTRING(`module`, ' . (strlen($name) + 1) . ')) WHERE `module` = ' . $q($name) . ' OR `module` LIKE ' . $q($name . ':%'));
	if ($undo['modules'] !== null) $db->query('UPDATE `' . $pre . "sb_config` SET `content` = " . $q($undo['modules']) . " WHERE `config` = 'modules'");
}

/** Installer : tables (install.php), migrations des tables gardées, inscription */
/**
 * Mettre à jour la base : fichiers du module plus récents que sa version en
 * base (copiés sans passer par GitHub). Migrations db_version → version des
 * fichiers, après une sauvegarde chiffrée des tables du module (annulable).
 */
function sbModRunMigrate($name, array $row) {
	$meta = sbModuleMeta($name);
	if (!$meta || !version_compare(sbUpdInstalledVersion(), $meta['requires_core'], '>=')) throw new RuntimeException('Le module demande le core ' . ($meta['requires_core'] ?? '?'));
	$from = $row['db_version'];
	$to = $meta['version'];
	if ($from === '' || !version_compare($to, $from, '>')) throw new RuntimeException('Base du module déjà à jour');
	$plain = sbModBackup($name, array(), array(), true);
	try {
		$done = sbModMigrate($name, $from, $to);
		sbModSaveRow($name, $to, $to, false);
	} catch (Throwable $e) {
		$msg = $e->getMessage() . ' : base du module remise';
		try { sbModRestore($plain); @unlink($plain); } catch (Throwable $e2) { sbModKeepBackup($name, $plain); $msg = $e->getMessage() . ' ET retour en échec : sauvegarde gardée'; }
		throw new RuntimeException($msg);
	}
	sbModKeepBackup($name, $plain, array('op' => 'migrate', 'label' => 'la mise à jour de la base ' . $from . ' → ' . $to, 'db' => true, 'files' => 0,
		'after' => array('installed' => true, 'version' => $to), 'complete' => true));
	sbUpdClearCaches();
	return array('step' => sbModLabel($name) . ' : base mise à jour ' . $from . ' → ' . $to . ' (' . sbModN(count($done), 'migration', 'migrations') . ')', 'label' => 'base mise à jour ' . $to, 'from' => $from, 'to' => $to);
}

function sbModRunInstall($name, array $row, $wasAbsent = false) {
	$meta = sbModuleMeta($name);
	if (!version_compare(sbUpdInstalledVersion(), $meta['requires_core'], '>=')) throw new RuntimeException('Le module demande le core ' . $meta['requires_core']);
	if (sbModRow($name)) throw new RuntimeException('Module déjà installé');
	$db = sbUpdDb();
	$kept = sbModExistingTables($name);
	$keptDb = sbModKept($name);
	// Reprise d'un ancien module (renommé) : contrôlée AVANT toute écriture
	$takeover = sbModReplaceInfo($name);
	// Périmètre = tables du module : au retour, celles créées ici sont
	// retirées et celles gardées remises telles quelles
	$plain = sbModBackup($name, array(), array(), true);
	$oplain = $takeover ? sbModBackup($takeover['old'], $takeover['files'], array(), true) : null;
	$undo = null;
	try {
		if ($takeover) $undo = sbModTakeover($name, $takeover);
		$created = array();
		$file = sbModulesDir() . $name . '/install.php';
		if (is_file($file)) {
			$inst = include $file;
			if (!is_array($inst)) throw new RuntimeException('install.php du module invalide');
			if (!empty($inst['sql'])) $created = sbModRunSql($db, sbUpdDbPrefix(), $inst['sql']);
			if (isset($inst['up']) && is_callable($inst['up'])) call_user_func($inst['up'], new SbMigration($db, sbUpdDbPrefix()));
		}
		$migrations = ($kept && preg_match('/^\d+\.\d+\.\d+$/', $keptDb)) ? sbModMigrate($name, $keptDb, $meta['version']) : array();
		sbModSaveRow($name, $meta['version'], $meta['version'], true);
		sbModKept($name, '');
	} catch (Throwable $e) {
		// Tables créées ici retirées, tables gardées remises, registre remis ;
		// reprise : ancien module remis (tables, fichiers, registre), droits
		$msg = $e->getMessage() . ' : installation annulée';
		try { sbModRestore($plain); @unlink($plain); } catch (Throwable $e2) { sbModKeepBackup($name, $plain); $msg .= ' (retour en échec : sauvegarde gardée)'; }
		if ($oplain) {
			try {
				sbModRestore($oplain);
				if ($undo) sbModTakeoverUndo($name, $takeover['old'], $undo);
				@unlink($oplain);
			} catch (Throwable $e3) {
				sbModKeepBackup($takeover['old'], $oplain);
				$msg .= ' (module ' . $takeover['old'] . ' non remis : sauvegarde chiffrée gardée)';
			}
		}
		throw new RuntimeException($msg);
	}
	// Reprise : sauvegarde de l'ancien module gardée (chiffrée) ; l'annulation
	// de l'installation n'est pas proposée (elle retirerait les données reprises)
	if ($oplain) sbModKeepBackup($takeover['old'], $oplain, array('op' => 'replaced', 'label' => 'la reprise par ' . $name . ' ' . $meta['version'], 'db' => true, 'files' => count($takeover['files']), 'complete' => false));
	sbModKeepBackup($name, $plain, array('op' => 'install', 'label' => 'l\'installation ' . $meta['version'], 'db' => true, 'files' => 0, 'before_absent' => (bool) $wasAbsent,
		'after' => array('installed' => true, 'version' => $meta['version']), 'complete' => !$takeover));
	sbUpdClearCaches();
	$step = sbModLabel($name) . ' ' . $meta['version'] . ' installé (' . ($takeover ? 'reprise de ' . $takeover['meta']['title'] . ' ' . $takeover['meta']['version'] . ' : ' . sbModN(count($takeover['tables']), 'table renommée', 'tables renommées') . ', ' : '') . sbModN(count($created), 'table créée', 'tables créées') . ($kept ? ', ' . sbModN(count($kept), 'gardée', 'gardées') : '') . ($migrations ? ', ' . sbModN(count($migrations), 'migration', 'migrations') : '') . ')';
	return array('step' => $step, 'label' => 'installé ' . $meta['version'], 'to' => $meta['version']);
}

/**
 * Désinstaller : retiré du registre, données et fichiers gardés ; avec
 * $remove (« Supprimer le module ») : sauvegarde chiffrée de la base et des
 * fichiers, puis tables et fichiers supprimés (le module redevient absent).
 */
function sbModRunUninstall($name, $remove) {
	$row = sbModRow($name);
	if (!$row) throw new RuntimeException('Module non installé');
	$db = sbUpdDb();
	$tables = sbModExistingTables($name);
	if (!$remove) {
		$plain = sbModBackup($name, array(), array(), false);
		if (!$db->query('DELETE FROM ' . sbModTable() . " WHERE `name` = '" . $db->real_escape_string($name) . "'")) { @unlink($plain); throw new RuntimeException('Registre des modules : ' . $db->error); }
		sbModKept($name, $tables ? (string) $row['db_version'] : '');
		sbModKeepBackup($name, $plain, array('op' => 'uninstall', 'label' => 'la désinstallation', 'db' => false, 'files' => 0,
			'after' => array('installed' => false, 'version' => (string) (sbModuleMeta($name)['version'] ?? '')), 'complete' => true));
		sbUpdClearCaches();
		$step = sbModLabel($name) . ' désinstallé, données et fichiers gardés' . ($tables ? ' (' . sbModN(count($tables), 'table', 'tables') . ')' : '');
		return array('step' => $step, 'label' => 'désinstallé', 'from' => $row['version']);
	}
	$dp = sbModDeletePlan($name);
	$plain = sbModBackup($name, $dp['files'], array(), true);
	$root = sbUpdSiteRoot() . '/';
	$targets = array();
	try {
		foreach ($tables as $t) {
			if (!$db->query('DROP TABLE IF EXISTS `' . sbUpdDbPrefix() . $t . '`')) throw new RuntimeException('Suppression de la table ' . $t . ' impossible (' . $db->error . ')');
		}
		if (!$db->query('DELETE FROM ' . sbModTable() . " WHERE `name` = '" . $db->real_escape_string($name) . "'")) throw new RuntimeException('Registre des modules : ' . $db->error);
		foreach ($dp['files'] as $rel) {
			$t = sbUpdTargetRel($rel);
			if (is_file($root . $t) && !@unlink($root . $t)) throw new RuntimeException('Suppression impossible : ' . $t);
			$targets[] = $t;
		}
	} catch (Throwable $e) {
		// Base (tables et registre) puis fichiers remis : module toujours installé
		try { sbModRestore($plain); @unlink($plain); $msg = ' : base et fichiers remis, module toujours installé'; }
		catch (Throwable $e2) { sbModKeepBackup($name, $plain); $msg = ' ET remise en échec (' . $e2->getMessage() . ') : sauvegarde gardée dans le dossier de travail'; }
		sbModulesInstalled(true);
		throw new RuntimeException($e->getMessage() . $msg);
	}
	sbModPruneDirs($targets);
	@rmdir(sbModulesDir() . $name);
	@unlink(sbModWorkDir() . '/' . $name . '.json');
	sbModKept($name, '');
	sbModKeepBackup($name, $plain, array('op' => 'remove', 'label' => 'la suppression du module', 'db' => true, 'files' => count($targets),
		'after' => array('installed' => false, 'version' => ''), 'complete' => true));
	sbUpdClearCaches();
	$step = sbModLabel($name) . ' supprimé : ' . sbModN(count($tables), 'table', 'tables') . ' et ' . sbModN(count($targets), 'fichier', 'fichiers') . ' retirés (sauvegarde chiffrée gardée)';
	return array('step' => $step, 'label' => 'supprimé (données, tables, fichiers)', 'from' => $row['version'], 'files' => count($targets));
}

/**
 * Annuler la dernière opération d'un module : base (tables du module),
 * fichiers et état remis depuis sa sauvegarde ; une installation depuis
 * GitHub (module absent avant) est annulée en retirant le module. Un seul
 * cran : la sauvegarde est ensuite supprimée.
 */
function sbModRunRollback($name) {
	$rb = sbModRollbackInfo($name);
	if (!$rb) throw new RuntimeException('Rien à annuler pour ce module');
	$label = $rb['label'] ?? 'la dernière opération';
	if (!empty($rb['before_absent'])) {
		sbModRunUninstall($name, true);
		sbModDeleteBackups($name);
		return array('step' => sbModLabel($name) . ' : ' . $label . ' annulée, module retiré', 'label' => 'annulation de ' . $label);
	}
	$plain = sbModWorkDir() . '/tmp-rollback-' . bin2hex(random_bytes(4)) . '.zip';
	sbUpdDecryptFile($rb['file'], $plain);
	try {
		sbModRestore($plain);
	} finally {
		@unlink($plain);
	}
	sbModDeleteBackups($name);
	return array('step' => sbModLabel($name) . ' : ' . $label . ' annulée' . (!empty($rb['db']) ? ' (tables remises à leur état du ' . date('d/m/Y H:i', strtotime($rb['date'] ?? 'now')) . ')' : ''), 'label' => 'annulation de ' . $label);
}

/** Supprimer les fichiers d'un module non installé (retour arrière d'une installation) */
function sbModRunDelete($name, $confirm) {
	if (sbModRow($name)) throw new RuntimeException('Désinstallez d\'abord le module');
	$dp = sbModDeletePlan($name);
	if (($dp['modified'] || $dp['unchecked']) && !$confirm) throw new RuntimeException('Confirmez la suppression des fichiers modifiés localement ou non vérifiables');
	$version = sbModuleMeta($name)['version'] ?? '';
	$plain = sbModBackup($name, $dp['files'], array(), false);
	$root = sbUpdSiteRoot() . '/';
	$targets = array();
	try {
		foreach ($dp['files'] as $rel) {
			$t = sbUpdTargetRel($rel);
			if (is_file($root . $t) && !@unlink($root . $t)) throw new RuntimeException('Suppression impossible : ' . $t);
			$targets[] = $t;
		}
	} catch (Throwable $e) {
		try { sbModRestore($plain); @unlink($plain); } catch (Throwable $e2) { sbModKeepBackup($name, $plain); }
		throw new RuntimeException($e->getMessage() . ' : fichiers remis');
	}
	sbModPruneDirs($targets);
	@rmdir(sbModulesDir() . $name);
	@unlink(sbModWorkDir() . '/' . $name . '.json');
	sbModKeepBackup($name, $plain);
	sbUpdClearCaches();
	return array('step' => sbModLabel($name) . ' supprimé du disque (' . sbModN(count($targets), 'fichier', 'fichiers') . ', sauvegarde chiffrée gardée)', 'label' => 'fichiers supprimés', 'from' => $version, 'files' => count($targets));
}

?>
