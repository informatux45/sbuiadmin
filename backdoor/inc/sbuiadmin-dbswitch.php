<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Changer de base de données (Configuration > Réglages)
 *
 * SQLite -> MySQL/MariaDB, MySQL -> SQLite, ou vers une autre base du même
 * type : toutes les tables du CMS (préfixe) sont recopiées, leur nombre de
 * lignes vérifié, puis sbdbconfig.php bascule sur la nouvelle base (même
 * clé de chiffrement : les secrets restent lisibles). L'ancienne base n'est
 * jamais modifiée ; ses accès sont gardés à côté de sbdbconfig.php
 * (sbdbconfig-previous-*.php) pour pouvoir y revenir.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

/** Fichier des accès de la base précédente (à côté de sbdbconfig.php), ou false */
function sbDbSwitchPreviousFile() {
	$cur = sbDbConfigPath();
	if (!$cur) return false;
	return dirname($cur) . DIRECTORY_SEPARATOR . 'sbdbconfig-previous-' . preg_replace('/[^A-Za-z0-9_.-]/', '_', basename(sbDbConfigSiteId())) . '.php';
}

/** Accès de la base précédente (tableau), ou null */
function sbDbSwitchPrevious() {
	$f = sbDbSwitchPreviousFile();
	if (!$f || !@is_file($f)) return null;
	$data = sbDbConfigRead($f);
	return (is_array($data) && sbDbConfigIsOurs($data, true)) ? $data : null;
}

/** Libellé court d'une configuration : « MySQL base@hôte » ou « SQLite fichier » */
function sbDbSwitchLabel(array $c) {
	if (($c['driver'] ?? 'mysql') === 'sqlite') return 'SQLite ' . (string) ($c['path'] ?? '');
	return 'MySQL/MariaDB ' . (string) ($c['name'] ?? '') . ' sur ' . (string) ($c['host'] ?? '');
}

/** Les deux configurations visent-elles la même base (et le même préfixe) ? */
function sbDbSwitchSame(array $a, array $b) {
	if (($a['driver'] ?? 'mysql') !== ($b['driver'] ?? 'mysql') || (string) ($a['prefix'] ?? '') !== (string) ($b['prefix'] ?? '')) return false;
	if (($a['driver'] ?? 'mysql') === 'sqlite') {
		$ra = @realpath((string) $a['path']);
		$rb = @realpath((string) $b['path']);
		return ($ra && $rb) ? $ra === $rb : (string) $a['path'] === (string) $b['path'];
	}
	return strtolower((string) $a['host']) === strtolower((string) $b['host']) && (string) $a['name'] === (string) $b['name'];
}

/**
 * Configuration cible lue dans le formulaire (driver, host, name, user,
 * password, path ; préfixe de la base actuelle), contrôlée.
 * @throws RuntimeException motif du refus
 */
function sbDbSwitchTarget(array $in) {
	$cur = sbDbConfig();
	$driver = (($in['db_driver'] ?? '') === 'sqlite') ? 'sqlite' : 'mysql';
	if (!sbDbDriverAvailable($driver)) throw new RuntimeException("L'extension PHP " . sbdb::DRIVERS[$driver] . " n'est pas activée sur ce serveur.");
	$t = array('driver' => $driver, 'host' => '', 'name' => '', 'user' => '', 'password' => '', 'path' => '', 'prefix' => (string) $cur['prefix'], 'key' => (string) $cur['key']);
	if ($driver === 'sqlite') {
		$t['path'] = trim((string) ($in['db_path'] ?? ''));
		$err = sbDbSqlitePathError($t['path']);
		if ($err !== '') throw new RuntimeException($err);
	} else {
		foreach (array('host' => 'db_host', 'name' => 'db_name', 'user' => 'db_user') as $k => $f) {
			$t[$k] = trim((string) ($in[$f] ?? ''));
			if ($t[$k] === '') throw new RuntimeException('Hôte, nom de la base et utilisateur sont obligatoires.');
			if (preg_match('/[\x00-\x1f;]/', $t[$k])) throw new RuntimeException('Caractère refusé dans les accès MySQL.');
		}
		$t['password'] = (string) ($in['db_password'] ?? '');
	}
	if (sbDbSwitchSame($t, $cur)) throw new RuntimeException('C\'est la base actuelle.');
	return $t;
}

/**
 * Connexion à la cible et contrôles : vide pour ce préfixe (aucune table
 * du CMS), version MySQL suffisante.
 * @return sbdb
 * @throws RuntimeException
 */
function sbDbSwitchConnect(array $t) {
	$db = sbdb::connect($t);
	if ($db->driver() === 'mysql' && version_compare(preg_replace('/[^0-9.].*$/', '', $db->serverVersion()), '5.7', '<')) {
		throw new RuntimeException('MySQL 5.7 ou MariaDB 10.3 au minimum (serveur : ' . $db->serverVersion() . ').');
	}
	$existing = $db->tables((string) $t['prefix']);
	if ($existing) {
		throw new RuntimeException('La base cible contient déjà ' . count($existing) . ' table(s) au préfixe « ' . $t['prefix'] . ' » (' . implode(', ', array_slice($existing, 0, 3)) . (count($existing) > 3 ? '…' : '') . ') : choisissez une base vide.');
	}
	return $db;
}

/**
 * Copie complète puis bascule. Sans erreur : sbdbconfig.php vise la
 * nouvelle base, l'ancien contenu est gardé dans sbdbconfig-previous-*.php.
 * En cas d'échec : sbdbconfig.php n'est pas touché, les tables déjà copiées
 * dans la cible sont supprimées.
 * @return array tables (nom => lignes), from, to (libellés)
 * @throws RuntimeException
 */
function sbDbSwitchRun(array $t) {
	$cur = sbDbConfig();
	if ($cur['source'] === 'env') throw new RuntimeException('Accès définis par les variables d\'environnement du serveur (SBUIADMIN_DB_*) : changez-les côté serveur.');
	if ($cur['source'] === 'none' || $cur['source'] === 'legacy' || !sbDbConfigPath()) throw new RuntimeException('sbdbconfig.php introuvable : changement impossible.');
	$from = sbDb();
	if (!$from) throw new RuntimeException('Base actuelle injoignable.');
	$to = sbDbSwitchConnect($t);
	$tables = $from->tables((string) $cur['prefix']);
	if (!$tables) throw new RuntimeException('Aucune table du CMS dans la base actuelle.');
	@set_time_limit(0);
	ignore_user_abort(true);
	try {
		$counts = sbdbschema::copyTables($from, $to, $tables);
	} catch (Throwable $e) {
		foreach ($tables as $tb) { try { sbdbschema::exec($to, 'DROP TABLE IF EXISTS `' . str_replace('`', '``', $tb) . '`'); } catch (Throwable $x) {} }
		throw new RuntimeException('Copie interrompue, base actuelle inchangée : ' . $e->getMessage());
	}
	// Ancienne configuration gardée (pour revenir), puis bascule
	$prev = sbDbSwitchPreviousFile();
	$prevContent = sbDbConfigRender($cur);
	if (!$prev || @file_put_contents($prev, $prevContent, LOCK_EX) !== strlen($prevContent)) {
		throw new RuntimeException('Impossible d\'écrire ' . ($prev ?: 'la copie des accès') . ' : rien n\'a été basculé (les tables copiées restent dans la cible).');
	}
	@chmod($prev, 0640);
	$t['key'] = (string) $cur['key'];
	$written = sbDbConfigWrite($t);
	if (!$written) throw new RuntimeException('sbdbconfig.php n\'a pu être réécrit : rien n\'a été basculé.');
	$check = sbDbConfig(true);
	sbDb(true);
	if (!sbDbSwitchSame($check, $t) || !sbDb() || (int) sbDb()->value('SELECT COUNT(*) FROM {sb_config}') !== (int) ($counts[$cur['prefix'] . 'sb_config'] ?? -1)) {
		// Relecture non conforme : retour aux anciens accès
		sbDbConfigWrite($cur);
		sbDbConfig(true);
		sbDb(true);
		throw new RuntimeException('Vérification après bascule en échec : anciens accès remis.');
	}
	if (function_exists('sbUpdClearCaches')) sbUpdClearCaches();
	return array('tables' => $counts, 'from' => sbDbSwitchLabel($cur), 'to' => sbDbSwitchLabel($t));
}

/**
 * Revenir à la base précédente (accès seulement : les changements faits
 * depuis la bascule ne s'y trouvent pas). Les deux accès sont échangés.
 * @throws RuntimeException
 */
function sbDbSwitchRevert() {
	$prev = sbDbSwitchPrevious();
	if (!$prev) throw new RuntimeException('Aucune base précédente enregistrée.');
	$cur = sbDbConfig();
	if ($cur['source'] === 'env') throw new RuntimeException('Accès définis par les variables d\'environnement du serveur.');
	$prev['key'] = (string) $cur['key'];
	$db = sbdb::connect($prev);
	if (!sbdbschema::hasTable($db, (string) $prev['prefix'] . 'sb_config')) throw new RuntimeException('La base précédente ne contient plus les tables du CMS.');
	$content = sbDbConfigRender($cur);
	$file = sbDbSwitchPreviousFile();
	if (!$file || @file_put_contents($file, $content, LOCK_EX) !== strlen($content)) throw new RuntimeException('Impossible d\'écrire ' . ($file ?: 'la copie des accès') . '.');
	if (!sbDbConfigWrite($prev)) throw new RuntimeException('sbdbconfig.php n\'a pu être réécrit.');
	sbDbConfig(true);
	sbDb(true);
	if (function_exists('sbUpdClearCaches')) sbUpdClearCaches();
	return sbDbSwitchLabel($prev);
}
