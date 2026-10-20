<?php
/**
 * Module sentinelle : intégrité des fichiers
 * Compare les fichiers du site à la liste signée de la version installée
 * (sbuiadmin-files.json, manifeste Ed25519 de la mise à jour) : fichiers du
 * cœur et des modules officiels modifiés ou manquants, fichiers PHP ajoutés
 * (portes dérobées), scripts dans upload/. La référence est gardée en cache
 * et sa signature revérifiée à chaque analyse.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
require_once SBUIADMIN_PATH . '/inc/sbuiadmin-update.php';

/** Extensions exécutables cherchées parmi les fichiers ajoutés */
function sentinelle_integrity_exec_re() {
	return '/\.(php\d?|phtml|pht|phar|phps|inc)$/i';
}

/**
 * Référence signée d'une version : manifeste, liste des fichiers et index
 * des modules, téléchargés une fois puis revérifiés à chaque lecture
 * @return array array(liste chemin => sha256, index des modules nom => entrée)
 * @throws RuntimeException référence introuvable ou non conforme
 */
function sentinelle_integrity_reference($version, $download = true) {
	$dir = sentinelle_cache_dir();
	if ($dir === '') throw new RuntimeException('Dossier datas/cache/sentinelle/ non inscriptible');
	$base = $dir . '/ref-' . preg_replace('/[^0-9.]/', '', $version);
	$files = array('manifest' => $base . '.manifest', 'sig' => $base . '.sig', 'list' => $base . '.list', 'index' => $base . '.index');
	if (!is_file($files['manifest']) || !is_file($files['sig']) || !is_file($files['list'])) {
		if (!$download) throw new RuntimeException('Référence de la version ' . $version . ' absente');
		list($m, $assets) = sbUpdFetchRelease('v' . $version);
		if (empty($m['files_list']) || !isset($assets[$m['files_list']])) throw new RuntimeException('La version ' . $version . ' ne publie pas la liste de ses fichiers');
		file_put_contents($files['manifest'], sbUpdGet($assets[SB_UPD_MANIFEST], 64 * 1024, null, 30));
		file_put_contents($files['sig'], sbUpdGet($assets[SB_UPD_MANIFEST . '.sig'], 4096, null, 30));
		file_put_contents($files['list'], sbUpdGet($assets[$m['files_list']], 8 * 1024 * 1024, null, 60));
		if (!empty($m['modules_index']) && isset($assets[SB_UPD_MODULES_INDEX])) file_put_contents($files['index'], sbUpdGet($assets[SB_UPD_MODULES_INDEX], 8 * 1024 * 1024, null, 60));
	}
	// Revérifiée à chaque fois : un cache modifié est refusé
	$m = sbUpdVerifyManifest((string) file_get_contents($files['manifest']), (string) file_get_contents($files['sig']));
	if (($m['version'] ?? '') !== $version) throw new RuntimeException('Référence signée d\'une autre version (' . ($m['version'] ?? '?') . ')');
	$raw = (string) file_get_contents($files['list']);
	if (!hash_equals((string) ($m['files_list_sha256'] ?? ''), hash('sha256', $raw))) throw new RuntimeException('Liste des fichiers en cache non conforme à la signature');
	$list = json_decode($raw, true);
	if (!is_array($list['files'] ?? null)) throw new RuntimeException('Liste des fichiers illisible');
	$index = array();
	if (!empty($m['modules_index']) && is_file($files['index'])) {
		$rawi = (string) file_get_contents($files['index']);
		if (!hash_equals((string) ($m['modules_index_sha256'] ?? ''), hash('sha256', $rawi))) throw new RuntimeException('Index des modules en cache non conforme à la signature');
		$index = (array) (json_decode($rawi, true)['modules'] ?? array());
	}
	return array($list['files'], $index);
}

/** Chemin réel (dossier d'administration renommé) → chemin de la liste */
function sentinelle_integrity_rel($target) {
	$admin = sbUpdAdminDir() . '/';
	return strpos($target, $admin) === 0 ? 'backdoor/' . substr($target, strlen($admin)) : $target;
}

/**
 * Analyse
 * @param array|null $list liste de référence (tests), null : version installée
 * @return array version, date, modified, missing, added, upload, modules (non vérifiés), error
 */
function sentinelle_integrity_scan(?array $list = null, array $index = array()) {
	$version = defined('_AM_START_VERSION') ? _AM_START_VERSION : sbUpdInstalledVersion();
	$out = array('version' => $version, 'date' => date('Y-m-d H:i:s'), 'checked' => 0, 'modified' => array(), 'missing' => array(), 'added' => array(), 'upload' => array(), 'modules' => array(), 'error' => '');
	if ($list === null) {
		try {
			list($list, $index) = sentinelle_integrity_reference($version);
		} catch (Throwable $e) {
			$out['error'] = $e->getMessage();
			return $out;
		}
	}
	$root = rtrim(sbUpdSiteRoot(), '/') . '/';
	// Fichiers des modules : vérifiés contre leur propre liste publiée, si la version installée est celle de l'index
	$owned = array();
	foreach ($index as $name => $e) foreach (array_keys((array) ($e['files'] ?? array())) as $rel) $owned[$rel] = $name;
	$modref = array();
	$verified = array();
	foreach ($index as $name => $e) {
		$meta = sbModuleMeta($name);
		if (!$meta || $meta['origin'] === 'site') continue;
		if ($meta['version'] !== ($e['version'] ?? '')) { $out['modules'][] = $name . ' ' . $meta['version']; continue; }
		$verified[] = $name;
		foreach ((array) ($e['files'] ?? array()) as $rel => $hash) $modref[$rel] = $hash;
	}
	foreach ($list as $rel => $hash) {
		if (sbUpdIsProtected($rel) || isset($owned[$rel])) continue;
		$modref[$rel] = $hash;
	}
	foreach ($modref as $rel => $hash) {
		if (sbUpdIsProtected($rel)) continue;
		$out['checked']++;
		$local = sbUpdHashFile($root . sbUpdTargetRel($rel));
		if ($local === null) $out['missing'][] = $rel;
		elseif (!hash_equals((string) $hash, $local)) $out['modified'][] = $rel;
	}
	// Fichiers exécutables ajoutés : racine, dossier d'administration, inc/, vendor/, modules officiels
	$known = array_flip(array_keys($list)) + array_flip(array_keys($owned));
	$dirs = array(sbUpdAdminDir(), 'inc', 'vendor');
	// Modules d'une autre version que celle publiée : fichiers légitimes inconnus, dossier écarté
	foreach ($verified as $name) if (is_dir($root . 'datas/modules/' . $name)) $dirs[] = 'datas/modules/' . $name;
	foreach ((array) @scandir($root) as $f) {
		if (is_file($root . $f) && preg_match(sentinelle_integrity_exec_re(), $f) && !isset($known[$f]) && !sbUpdIsProtected($f) && !preg_match('/^sbdbconfig[^\/]*\.php$/', $f)) $out['added'][] = $f;
	}
	foreach ($dirs as $d) {
		if (!is_dir($root . $d)) continue;
		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . $d, FilesystemIterator::SKIP_DOTS));
		foreach ($it as $file) {
			if (!$file->isFile() || !preg_match(sentinelle_integrity_exec_re(), $file->getFilename())) continue;
			$rel = sentinelle_integrity_rel(substr(str_replace('\\', '/', $file->getPathname()), strlen($root)));
			if (isset($known[$rel]) || sbUpdIsProtected($rel) || preg_match('#(^|/)sbdbconfig[^/]*\.php$#', $rel)) continue;
			$out['added'][] = $rel;
			if (count($out['added']) >= 500) break 2;
		}
	}
	// Médias : aucun script attendu
	if (is_dir($root . 'upload')) {
		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . 'upload', FilesystemIterator::SKIP_DOTS));
		foreach ($it as $file) {
			if ($file->isFile() && preg_match('/\.(php\d?|phtml|pht|phar|phps|cgi|pl|sh|asp|aspx|jsp)(\.|$)/i', $file->getFilename())) {
				$out['upload'][] = substr(str_replace('\\', '/', $file->getPathname()), strlen($root));
				if (count($out['upload']) >= 200) break;
			}
		}
	}
	foreach (array('modified', 'missing', 'added', 'upload') as $k) sort($out[$k]);
	return $out;
}

/** Résultat inquiétant ? (fichiers modifiés, PHP ajoutés, scripts dans upload/) */
function sentinelle_integrity_alarming(array $r) {
	return $r['error'] === '' && ($r['modified'] || $r['added'] || $r['upload']);
}

/**
 * Lance l'analyse, garde le résumé (réglage sentinelle_integrity) et note un
 * événement critique si le résultat est inquiétant et a changé
 */
function sentinelle_integrity_run() {
	$r = sentinelle_integrity_scan();
	$prev = json_decode(sbSetting('sentinelle_integrity'), true);
	$cut = function (array $a) { return array_slice($a, 0, 100); };
	$summary = array('version' => $r['version'], 'date' => $r['date'], 'checked' => $r['checked'], 'error' => $r['error'],
		'counts' => array('modified' => count($r['modified']), 'missing' => count($r['missing']), 'added' => count($r['added']), 'upload' => count($r['upload'])),
		'modified' => $cut($r['modified']), 'missing' => $cut($r['missing']), 'added' => $cut($r['added']), 'upload' => $cut($r['upload']), 'modules' => $r['modules']);
	$summary['hash'] = sha1(json_encode(array($r['modified'], $r['added'], $r['upload'])));
	sbSettingsSave(array('sentinelle_integrity' => json_encode($summary, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 'sentinelle_integrity_last' => date('Y-m-d')));
	if (sentinelle_integrity_alarming($r) && ($prev['hash'] ?? '') !== $summary['hash']) {
		$parts = array();
		if ($r['modified']) $parts[] = count($r['modified']) . ' modifié(s) : ' . implode(', ', array_slice($r['modified'], 0, 5));
		if ($r['added']) $parts[] = count($r['added']) . ' PHP ajouté(s) : ' . implode(', ', array_slice($r['added'], 0, 5));
		if ($r['upload']) $parts[] = count($r['upload']) . ' script(s) dans upload/ : ' . implode(', ', array_slice($r['upload'], 0, 5));
		sentinelle_event('integrity', 'critical', 'Intégrité : ' . implode(' ; ', $parts), 'logged');
	}
	return $summary;
}
