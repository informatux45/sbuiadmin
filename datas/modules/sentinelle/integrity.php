<?php
/**
 * Module sentinelle : intégrité des fichiers
 * Compare les fichiers du site à la liste signée de la version installée
 * (sbuiadmin-files.json, manifeste Ed25519 de la mise à jour) : fichiers du
 * cœur et des modules officiels modifiés ou manquants, fichiers PHP ajoutés
 * (portes dérobées), scripts dans upload/. La référence est gardée en cache
 * et sa signature revérifiée à chaque analyse.
 * Fichiers PHP propres au site : approuvés depuis la page Intégrité (chemin +
 * SHA-256 en base, sb_sentinelle_approved ; modifiés ensuite = signalés), ou
 * sous un préfixe surveillé (réglage sentinelle_integrity_prefixes : nouveaux
 * fichiers signalés en alerte, pas en critique).
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

/** Fichiers approuvés : chemin => SHA-256 (table absente : aucun) */
function sentinelle_integrity_approved() {
	$dbt = sentinelle_db('sb_sentinelle_approved');
	if (!$dbt) return array();
	list($db, $table) = $dbt;
	$out = array();
	try {
		// Table absente (base du module pas encore mise à jour) : aucun
		$res = @mysqli_query($db, "SELECT `path`, `sha256` FROM `$table`");
		if ($res) while ($row = mysqli_fetch_assoc($res)) $out[$row['path']] = $row['sha256'];
	} catch (Throwable $e) {
		return array();
	}
	return $out;
}

/** Préfixes surveillés (« vendor/mpdf8/ »), un par ligne, sans .. ni / initial */
function sentinelle_integrity_prefixes($text = null) {
	$text = $text === null ? sbSetting('sentinelle_integrity_prefixes') : $text;
	$out = array();
	foreach (preg_split('/[\r\n,;]+/', (string) $text) as $p) {
		$p = trim(str_replace('\\', '/', $p), " \t/");
		if ($p === '' || strpos($p, '..') !== false || !preg_match('#^[A-Za-z0-9_.\-/]{1,200}$#', $p)) continue;
		// Dossier d'administration renommé : chemins de l'analyse en backdoor/
		$p .= '/';
		$adm = sbUpdAdminDir() . '/';
		if ($adm !== 'backdoor/' && strpos($p, $adm) === 0) $p = 'backdoor/' . substr($p, strlen($adm));
		$out[$p] = $p;
	}
	return array_values($out);
}

/**
 * Approuve les fichiers propres au site d'une analyse (PHP ajoutés, sous un
 * préfixe surveillé, approuvés puis modifiés) : chemin + SHA-256 actuel
 * @return int nombre de fichiers approuvés, -1 si la table est absente
 */
function sentinelle_integrity_approve(array $r, $user = '') {
	$dbt = sentinelle_db('sb_sentinelle_approved');
	if (!$dbt) return -1;
	list($db, $table) = $dbt;
	$root = rtrim(sbUpdSiteRoot(), '/') . '/';
	try {
		$stmt = mysqli_prepare($db, "INSERT INTO `$table` (`path`, `sha256`, `approved_at`, `user`) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `sha256` = VALUES(`sha256`), `approved_at` = VALUES(`approved_at`), `user` = VALUES(`user`)");
	} catch (Throwable $e) {
		return -1;
	}
	if (!$stmt) return -1;
	$n = 0;
	$now = date('Y-m-d H:i:s');
	$user = sentinelle_cut($user, 64);
	foreach (array_merge($r['added'], $r['watched'], $r['approved_changed']) as $rel) {
		$hash = sbUpdHashFile($root . sbUpdTargetRel($rel));
		if ($hash === null || strlen($rel) > 255) continue;
		mysqli_stmt_bind_param($stmt, 'ssss', $rel, $hash, $now, $user);
		try {
			if (mysqli_stmt_execute($stmt)) $n++;
		} catch (Throwable $e) {
			// ligne refusée : fichier laissé « ajouté »
		}
	}
	if ($n) sentinelle_event('integrity_approve', 'warning', 'Intégrité : ' . $n . ' fichier(s) propre(s) au site approuvé(s)', 'logged', $user);
	return $n;
}

/** Retire toutes les approbations */
function sentinelle_integrity_unapprove_all($user = '') {
	$dbt = sentinelle_db('sb_sentinelle_approved');
	if (!$dbt) return false;
	list($db, $table) = $dbt;
	try {
		$ok = (bool) @mysqli_query($db, "DELETE FROM `$table`");
	} catch (Throwable $e) {
		return false;
	}
	if ($ok) sentinelle_event('integrity_approve', 'warning', 'Intégrité : approbations des fichiers propres au site retirées', 'logged', sentinelle_cut($user, 64));
	return $ok;
}

/** Chemin réel (dossier d'administration renommé) → chemin de la liste */
function sentinelle_integrity_rel($target) {
	$admin = sbUpdAdminDir() . '/';
	return strpos($target, $admin) === 0 ? 'backdoor/' . substr($target, strlen($admin)) : $target;
}

/**
 * Analyse
 * @param array|null $list liste de référence (tests), null : version installée
 * @param array|null $approved fichiers approuvés (tests), null : en base
 * @param array|null $prefixes préfixes surveillés (tests), null : réglage
 * @return array version, date, modified, missing, added, upload,
 *   approved_changed (approuvés puis modifiés), watched (sous un préfixe
 *   surveillé), approved (nombre conformes), modules (non vérifiés), error
 */
function sentinelle_integrity_scan(?array $list = null, array $index = array(), ?array $approved = null, ?array $prefixes = null) {
	$version = defined('_AM_START_VERSION') ? _AM_START_VERSION : sbUpdInstalledVersion();
	$out = array('version' => $version, 'date' => date('Y-m-d H:i:s'), 'checked' => 0, 'modified' => array(), 'missing' => array(), 'added' => array(), 'upload' => array(),
		'approved_changed' => array(), 'watched' => array(), 'approved' => 0, 'modules' => array(), 'error' => '');
	$approved = $approved ?? sentinelle_integrity_approved();
	$prefixes = $prefixes ?? sentinelle_integrity_prefixes();
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
	// PHP inconnu de la liste : approuvé (conforme ou modifié), sous un préfixe surveillé, ou ajouté
	$unknown = function ($rel, $path) use (&$out, $approved, $prefixes) {
		if (isset($approved[$rel])) {
			if (hash_equals((string) $approved[$rel], (string) sbUpdHashFile($path))) $out['approved']++;
			else $out['approved_changed'][] = $rel;
			return;
		}
		foreach ($prefixes as $p) if (strpos($rel, $p) === 0) { $out['watched'][] = $rel; return; }
		$out['added'][] = $rel;
	};
	foreach ((array) @scandir($root) as $f) {
		if (is_file($root . $f) && preg_match(sentinelle_integrity_exec_re(), $f) && !isset($known[$f]) && !sbUpdIsProtected($f) && !preg_match('/^sbdbconfig[^\/]*\.php$/', $f)) $unknown($f, $root . $f);
	}
	foreach ($dirs as $d) {
		if (!is_dir($root . $d)) continue;
		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . $d, FilesystemIterator::SKIP_DOTS));
		foreach ($it as $file) {
			if (!$file->isFile() || !preg_match(sentinelle_integrity_exec_re(), $file->getFilename())) continue;
			$rel = sentinelle_integrity_rel(substr(str_replace('\\', '/', $file->getPathname()), strlen($root)));
			if (isset($known[$rel]) || sbUpdIsProtected($rel) || preg_match('#(^|/)sbdbconfig[^/]*\.php$#', $rel)) continue;
			$unknown($rel, $file->getPathname());
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
	foreach (array('modified', 'missing', 'added', 'upload', 'approved_changed', 'watched') as $k) sort($out[$k]);
	return $out;
}

/** Résultat inquiétant ? (fichiers modifiés, PHP ajoutés, scripts dans upload/, approuvés puis modifiés) */
function sentinelle_integrity_alarming(array $r) {
	return $r['error'] === '' && ($r['modified'] || $r['added'] || $r['upload'] || !empty($r['approved_changed']));
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
		'counts' => array('modified' => count($r['modified']), 'missing' => count($r['missing']), 'added' => count($r['added']), 'upload' => count($r['upload']),
			'approved_changed' => count($r['approved_changed']), 'watched' => count($r['watched']), 'approved' => $r['approved']),
		'modified' => $cut($r['modified']), 'missing' => $cut($r['missing']), 'added' => $cut($r['added']), 'upload' => $cut($r['upload']),
		'approved_changed' => $cut($r['approved_changed']), 'watched' => $cut($r['watched']), 'modules' => $r['modules']);
	$summary['hash'] = sha1(json_encode(array($r['modified'], $r['added'], $r['upload'], $r['approved_changed'], $r['watched'])));
	sbSettingsSave(array('sentinelle_integrity' => json_encode($summary, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), 'sentinelle_integrity_last' => date('Y-m-d')));
	if (sentinelle_integrity_alarming($r) && ($prev['hash'] ?? '') !== $summary['hash']) {
		$parts = array();
		if ($r['modified']) $parts[] = count($r['modified']) . ' modifié(s) : ' . implode(', ', array_slice($r['modified'], 0, 5));
		if ($r['added']) $parts[] = count($r['added']) . ' PHP ajouté(s) : ' . implode(', ', array_slice($r['added'], 0, 5));
		if ($r['upload']) $parts[] = count($r['upload']) . ' script(s) dans upload/ : ' . implode(', ', array_slice($r['upload'], 0, 5));
		if ($r['approved_changed']) $parts[] = count($r['approved_changed']) . ' approuvé(s) puis modifié(s) : ' . implode(', ', array_slice($r['approved_changed'], 0, 5));
		sentinelle_event('integrity', 'critical', 'Intégrité : ' . implode(' ; ', $parts), 'logged');
	} elseif ($r['error'] === '' && $r['watched'] && ($prev['hash'] ?? '') !== $summary['hash']) {
		// Nouveaux fichiers sous un préfixe surveillé : alerte, pas critique
		sentinelle_event('integrity', 'warning', 'Intégrité : ' . count($r['watched']) . ' PHP nouveau(x) sous un préfixe surveillé : ' . implode(', ', array_slice($r['watched'], 0, 5)), 'logged');
	}
	return $summary;
}
