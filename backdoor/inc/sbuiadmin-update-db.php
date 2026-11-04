<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Mise à jour : base de données (migrations, sauvegarde SQL) et
 * chiffrement des sauvegardes
 *
 * MIGRATIONS - un fichier par version qui touche à la base :
 *   backdoor/inc/migrations/<version>.php (ex. 4.13.php), qui renvoie
 *   array(
 *     'description' => 'Ce que fait la migration',
 *     'up'   => function (SbMigration $m) { $m->addColumn('sb_news', 'auteur', "varchar(100) NOT NULL DEFAULT ''"); },
 *     'down' => function (SbMigration $m) { $m->dropColumn('sb_news', 'auteur'); },
 *     'reversible' => true, // false : le retour arrière passe par la sauvegarde SQL
 *   );
 * Règles :
 *  - IDEMPOTENT : n'utiliser que les méthodes de SbMigration, qui vérifient
 *    l'état réel de la base avant d'agir (relancer = sans effet) ;
 *    $m->query() seulement pour des requêtes elles-mêmes idempotentes ;
 *  - tables SANS préfixe (ajouté automatiquement) ;
 *  - suppression en deux temps : la version N cesse d'utiliser une table ou
 *    un champ, la version N+1 le supprime (le retour de N+1 vers N reste sûr) ;
 *  - l'API de SbMigration reste compatible d'une version à l'autre (une
 *    migration nouvelle est exécutée par le code de la version précédente).
 *
 * SAUVEGARDE SQL - tables du CMS (préfixe), en PHP pur (ni mysqldump ni
 * shell), par flux ; restauration complète (tables créées depuis supprimées).
 * MySQL/MariaDB ou SQLite (connexion sbdb) ; une migration écrite pour
 * MySQL est traduite sous SQLite (inc/class/sbuiadmin-dbschema.php).
 *
 * CHIFFREMENT - libsodium (secretstream) avec la clé de sbdbconfig.php :
 * une sauvegarde lue hors de son serveur est illisible.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');
if (defined('SBUIADMIN_UPDATE_DB_LOADED')) return;
define('SBUIADMIN_UPDATE_DB_LOADED', true);

defined('SB_UPD_DUMP_SEP') or define('SB_UPD_DUMP_SEP', "\n-- SBUPD --\n");

// -----------------------------------------------------------------------
// Migrations
// -----------------------------------------------------------------------

class SbMigration {
	/** @var sbdb */
	private $db;
	private $prefix;
	public $log = array();

	/** $db : connexion sbdb (une ancienne connexion mysqli est remplacée par sbDb()) */
	public function __construct($db, $prefix) {
		if (!($db instanceof sbdb)) $db = function_exists('sbDb') ? sbDb() : null;
		if (!($db instanceof sbdb)) throw new RuntimeException('Base de données inaccessible');
		$this->db = $db;
		$this->prefix = (string) $prefix;
	}

	/** Nom complet (préfixe), contrôlé */
	public function table($name) {
		$name = (string) $name;
		if (!preg_match('/^[A-Za-z0-9_]{1,60}$/', $name)) throw new RuntimeException('Nom de table refusé : ' . $name);
		return $this->prefix . $name;
	}
	private function ident($name) {
		if (!preg_match('/^[A-Za-z0-9_]{1,64}$/', (string) $name)) throw new RuntimeException('Identifiant refusé : ' . $name);
		return '`' . $name . '`';
	}
	private function sqlite() {
		return $this->db->driver() === 'sqlite';
	}

	/** Requête libre (doit être idempotente), écrite pour MySQL : traduite sous SQLite */
	public function query($sql) {
		try {
			sbdbschema::runScript($this->db, $sql);
		} catch (RuntimeException $e) {
			throw new RuntimeException('Requête en échec (' . $e->getMessage() . ') : ' . substr($sql, 0, 200));
		}
		$this->log[] = $sql;
		return true;
	}

	public function tableExists($table) {
		return sbdbschema::hasTable($this->db, $this->table($table));
	}
	public function columnExists($table, $column) {
		$t = $this->table($table);
		if ($this->sqlite()) return (int) $this->db->value('SELECT COUNT(*) FROM pragma_table_info(?) WHERE name = ?', array($t, (string) $column)) > 0;
		return (int) $this->db->value('SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?', array($t, (string) $column)) > 0;
	}
	public function indexExists($table, $index) {
		$t = $this->table($table);
		if ($this->sqlite()) {
			if ($index === 'PRIMARY') { $m = sbdbschema::describe($this->db, $t); return $m && $m['pk']; }
			return (int) $this->db->value("SELECT COUNT(*) FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND name = ?", array($t, $t . '__' . $index)) > 0;
		}
		return (int) $this->db->value('SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?', array($t, (string) $index)) > 0;
	}

	/** $definition : colonnes et clés entre parenthèses, sans le CREATE */
	public function createTable($table, $definition, $options = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4') {
		if ($this->tableExists($table)) return false;
		return $this->query('CREATE TABLE IF NOT EXISTS ' . $this->ident($this->table($table)) . ' (' . $definition . ') ' . $options);
	}
	public function dropTable($table) {
		if (!$this->tableExists($table)) return false;
		return $this->query('DROP TABLE IF EXISTS ' . $this->ident($this->table($table)));
	}
	public function renameTable($from, $to) {
		if (!$this->tableExists($from) || $this->tableExists($to)) return false;
		return $this->query('RENAME TABLE ' . $this->ident($this->table($from)) . ' TO ' . $this->ident($this->table($to)));
	}
	/** $definition : type et options (ex. "varchar(100) NOT NULL DEFAULT ''"), $after : colonne précédente (facultatif) */
	public function addColumn($table, $column, $definition, $after = null) {
		if ($this->columnExists($table, $column)) return false;
		return $this->query('ALTER TABLE ' . $this->ident($this->table($table)) . ' ADD ' . $this->ident($column) . ' ' . $definition . ($after ? ' AFTER ' . $this->ident($after) : ''));
	}
	/** Changement de type (idempotent par nature) */
	public function modifyColumn($table, $column, $definition) {
		if (!$this->columnExists($table, $column)) throw new RuntimeException('Colonne absente : ' . $table . '.' . $column);
		return $this->query('ALTER TABLE ' . $this->ident($this->table($table)) . ' MODIFY ' . $this->ident($column) . ' ' . $definition);
	}
	public function renameColumn($table, $from, $to, $definition) {
		if (!$this->columnExists($table, $from) || $this->columnExists($table, $to)) return false;
		return $this->query('ALTER TABLE ' . $this->ident($this->table($table)) . ' CHANGE ' . $this->ident($from) . ' ' . $this->ident($to) . ' ' . $definition);
	}
	public function dropColumn($table, $column) {
		if (!$this->tableExists($table) || !$this->columnExists($table, $column)) return false;
		return $this->query('ALTER TABLE ' . $this->ident($this->table($table)) . ' DROP ' . $this->ident($column));
	}
	/** $columns : liste de colonnes, $type : INDEX, UNIQUE ou FULLTEXT */
	public function addIndex($table, $index, array $columns, $type = 'INDEX') {
		if (!in_array($type, array('INDEX', 'UNIQUE', 'FULLTEXT'), true)) throw new RuntimeException('Type d\'index refusé');
		if ($this->indexExists($table, $index)) return false;
		return $this->query('ALTER TABLE ' . $this->ident($this->table($table)) . ' ADD ' . ($type === 'INDEX' ? 'INDEX' : $type . ' INDEX') . ' ' . $this->ident($index) . ' (' . implode(', ', array_map(array($this, 'ident'), $columns)) . ')');
	}
	public function dropIndex($table, $index) {
		if (!$this->indexExists($table, $index)) return false;
		return $this->query('ALTER TABLE ' . $this->ident($this->table($table)) . ' DROP INDEX ' . $this->ident($index));
	}
	/** Ligne de sb_config ajoutée si absente (jamais écrasée) */
	public function addConfig($config, $content) {
		$t = $this->ident($this->table('sb_config'));
		if ((int) $this->db->value("SELECT COUNT(*) FROM $t WHERE `config` = ?", array((string) $config)) > 0) return false;
		if ($this->db->run("INSERT INTO $t (`config`, `content`) VALUES (?, ?)", array((string) $config, (string) $content)) === false) throw new RuntimeException('Requête en échec : ' . $this->db->error());
		$this->log[] = 'addConfig ' . $config;
		return true;
	}
	public function deleteConfig($config) {
		if ($this->db->run('DELETE FROM ' . $this->ident($this->table('sb_config')) . ' WHERE `config` = ?', array((string) $config)) === false) throw new RuntimeException('Requête en échec : ' . $this->db->error());
		$this->log[] = 'deleteConfig ' . $config;
		return true;
	}
}

/** Dossier des migrations sur le site */
function sbUpdMigrationsDir() {
	return SBUIADMIN_PATH . '/inc/migrations';
}

/** Version du schéma de la base (initialisée à la version installée) */
function sbUpdDbVersion() {
	$v = sbSetting('db_version');
	if (!preg_match('/^\d+\.\d+(\.\d+)?$/', $v)) {
		$v = function_exists('sbUpdInstalledVersion') ? sbUpdInstalledVersion() : _AM_START_VERSION;
		// Pas de version inscrite : une base sans sb_modules ni sb_users_rights
		// est antérieure aux migrations (4.13 ou avant, fichiers remplacés à la
		// main) ; la noter « à jour » sautait ses migrations (4.43)
		if (sbUpdDbLooksOld()) $v = '4.13';
		sbSettingsSave(array('db_version' => $v));
	}
	return $v;
}

/** Base d'avant les migrations : ni sb_modules ni sb_users_rights */
function sbUpdDbLooksOld() {
	$db = function_exists('sbDb') ? sbDb() : null;
	if (!$db) return false;
	$prefix = function_exists('sbUpdDbPrefix') ? sbUpdDbPrefix() : (defined('_AM_DB_PREFIX') ? _AM_DB_PREFIX : '');
	foreach (array('sb_modules', 'sb_users_rights') as $t) {
		if (sbdbschema::hasTable($db, $prefix . $t)) return false;
	}
	return true;
}

/**
 * Migrations à passer pour aller de $fromDb à $toVersion, dans l'ordre.
 * @return array version => chemin
 */
function sbUpdPendingMigrations($fromDb, $toVersion, $dir = null) {
	$dir = $dir ?: sbUpdMigrationsDir();
	$list = array();
	foreach ((array) @glob($dir . '/*.php') as $f) {
		$v = basename($f, '.php');
		if (!preg_match('/^\d+\.\d+(\.\d+)?$/', $v)) continue;
		if (version_compare($v, $fromDb, '>') && version_compare($v, $toVersion, '<=')) $list[$v] = $f;
	}
	uksort($list, 'version_compare');
	return $list;
}

/** Charge et contrôle un fichier de migration */
function sbUpdLoadMigration($file) {
	$m = include $file;
	if (!is_array($m) || !isset($m['up']) || !is_callable($m['up'])) throw new RuntimeException('Migration invalide : ' . basename($file));
	$m['reversible'] = !empty($m['reversible']) && isset($m['down']) && is_callable($m['down']);
	return $m;
}

/**
 * Passe les migrations (montée), notant db_version après chacune.
 * @param callable|null $progress fonction(texte)
 * @return array versions appliquées
 */
function sbUpdMigrateUp($db, $prefix, array $pending, $progress = null) {
	$done = array();
	foreach ($pending as $v => $file) {
		$mig = sbUpdLoadMigration($file);
		if ($progress) call_user_func($progress, 'Base de données : migration ' . $v . (isset($mig['description']) ? ' (' . $mig['description'] . ')' : ''));
		call_user_func($mig['up'], new SbMigration($db, $prefix));
		sbSettingsSave(array('db_version' => $v));
		$done[] = $v;
	}
	return $done;
}

/**
 * Annule des migrations (descente), de la plus récente à la plus ancienne,
 * puis remet db_version à $toDb. Exige qu'elles soient toutes réversibles.
 */
function sbUpdMigrateDown($db, $prefix, array $files, $toDb, $progress = null) {
	$migs = array();
	foreach ($files as $v => $file) {
		$mig = sbUpdLoadMigration($file);
		if (!$mig['reversible']) throw new RuntimeException('Migration ' . $v . ' non réversible');
		$migs[$v] = $mig;
	}
	uksort($migs, function ($a, $b) { return version_compare($b, $a); });
	foreach ($migs as $v => $mig) {
		if ($progress) call_user_func($progress, 'Base de données : annulation de la migration ' . $v);
		call_user_func($mig['down'], new SbMigration($db, $prefix));
	}
	sbSettingsSave(array('db_version' => $toDb));
}

// -----------------------------------------------------------------------
// Sauvegarde SQL (PHP pur)
// -----------------------------------------------------------------------

/** Tables du CMS (préfixe) */
function sbUpdDbTables(sbdb $db, $prefix) {
	if ($db->driver() === 'sqlite') return $db->tables((string) $prefix);
	$like = str_replace(array('\\', '_', '%'), array('\\\\', '\\_', '\\%'), $prefix) . '%';
	$rows = $db->all('SHOW FULL TABLES LIKE ' . $db->pdo()->quote($like));
	if ($rows === false) throw new RuntimeException('Liste des tables illisible : ' . $db->error());
	$tables = array();
	foreach ($rows as $row) {
		$row = array_values($row);
		if (($row[1] ?? 'BASE TABLE') === 'BASE TABLE') $tables[] = $row[0];
	}
	return $tables;
}

/** Taille approximative des tables du CMS (octets), pour l'espace disque */
function sbUpdDbSize(sbdb $db, $prefix) {
	if ($db->driver() === 'sqlite') {
		$c = function_exists('sbDbConfig') ? sbDbConfig() : array();
		return (int) @filesize((string) ($c['path'] ?? ''));
	}
	$like = str_replace(array('\\', '_', '%'), array('\\\\', '\\_', '\\%'), $prefix) . '%';
	return (int) $db->value('SELECT COALESCE(SUM(DATA_LENGTH + INDEX_LENGTH), 0) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME LIKE ?', array($like));
}

/** Valeur -> littéral SQL de la sauvegarde (binaire en hexadécimal) */
function sbUpdDumpValue(sbdb $db, $v) {
	if ($v === null) return 'NULL';
	$v = (string) $v;
	if ($v === '') return "''";
	if (strpos($v, "\0") === false && preg_match('//u', $v)) return $db->pdo()->quote($v);
	return $db->driver() === 'sqlite' ? "X'" . bin2hex($v) . "'" : '0x' . bin2hex($v);
}

/**
 * Écrit la sauvegarde SQL (en clair) dans $file : en-tête JSON (tables),
 * puis une instruction par bloc (séparateur SB_UPD_DUMP_SEP). Elle ne se
 * restaure que sur une base du même type (MySQL ou SQLite).
 * $only : périmètre limité (noms complets, ex. tables d'un module) ; la
 * restauration ne touchera alors qu'à ces tables (en-tête 'scope').
 * @return array tables sauvegardées
 */
function sbUpdDbDump(sbdb $db, $prefix, $file, ?array $only = null) {
	$tables = sbUpdDbTables($db, $prefix);
	if ($only !== null) $tables = array_values(array_intersect($tables, $only));
	$fh = fopen($file, 'wb');
	if (!$fh) throw new RuntimeException('Sauvegarde SQL impossible à créer');
	@chmod($file, 0600);
	$head = array('sbupd_dump' => 1, 'date' => date('c'), 'prefix' => $prefix, 'driver' => $db->driver(), 'tables' => $tables);
	if ($only !== null) $head['scope'] = array_values($only);
	fwrite($fh, '-- ' . json_encode($head) . SB_UPD_DUMP_SEP);
	foreach ($tables as $t) {
		$q = '`' . str_replace('`', '``', $t) . '`';
		fwrite($fh, 'DROP TABLE IF EXISTS ' . $q . SB_UPD_DUMP_SEP);
		if ($db->driver() === 'sqlite') {
			// Table, puis ses index et déclencheurs, tels qu'enregistrés par SQLite
			$ddl = $db->all("SELECT sql FROM sqlite_master WHERE tbl_name = ? AND sql IS NOT NULL ORDER BY CASE type WHEN 'table' THEN 0 WHEN 'index' THEN 1 ELSE 2 END", array($t));
			if (!$ddl) throw new RuntimeException('Structure illisible : ' . $t);
			foreach ($ddl as $d) fwrite($fh, $d['sql'] . SB_UPD_DUMP_SEP);
		} else {
			$c = $db->row('SHOW CREATE TABLE ' . $q);
			if (!$c) throw new RuntimeException('Structure illisible : ' . $t);
			fwrite($fh, array_values($c)[1] . SB_UPD_DUMP_SEP);
		}
		$st = $db->run('SELECT * FROM ' . $q);
		if ($st === false) throw new RuntimeException('Données illisibles : ' . $t);
		$head = null;
		$batch = array();
		$size = 0;
		while (($row = $st->fetch(PDO::FETCH_ASSOC)) !== false) {
			if ($head === null) $head = 'INSERT INTO ' . $q . ' (' . implode(', ', array_map(function ($c) { return '`' . str_replace('`', '``', $c) . '`'; }, array_keys($row))) . ') VALUES ';
			$vals = array();
			foreach ($row as $v) $vals[] = sbUpdDumpValue($db, $v);
			$line = '(' . implode(', ', $vals) . ')';
			$batch[] = $line;
			$size += strlen($line);
			if (count($batch) >= 200 || $size > 512 * 1024) {
				fwrite($fh, $head . implode(', ', $batch) . SB_UPD_DUMP_SEP);
				$batch = array();
				$size = 0;
			}
		}
		if ($batch) fwrite($fh, $head . implode(', ', $batch) . SB_UPD_DUMP_SEP);
		$st->closeCursor();
	}
	if (!fclose($fh)) throw new RuntimeException('Sauvegarde SQL impossible à écrire');
	return $tables;
}

/**
 * Remet la base dans l'état de la sauvegarde : tables recréées et
 * remplies, tables du CMS apparues depuis supprimées. Sauvegarde à
 * périmètre limité ('scope') : seules les tables de ce périmètre sont
 * concernées, le reste de la base n'est jamais touché.
 */
function sbUpdDbRestore(sbdb $db, $prefix, $file) {
	$fh = fopen($file, 'rb');
	if (!$fh) throw new RuntimeException('Sauvegarde SQL illisible');
	$header = null;
	$buf = '';
	$sqlite = $db->driver() === 'sqlite';
	$db->pdo()->exec($sqlite ? 'PRAGMA foreign_keys = OFF' : 'SET FOREIGN_KEY_CHECKS = 0');
	// SQLite : tout ou rien, et sans une écriture disque par ligne
	if ($sqlite) $db->pdo()->beginTransaction();
	try {
		while (!feof($fh) || $buf !== '') {
			if (!feof($fh)) $buf .= fread($fh, 1024 * 1024);
			while (($p = strpos($buf, SB_UPD_DUMP_SEP)) !== false) {
				$stmt = substr($buf, 0, $p);
				$buf = substr($buf, $p + strlen(SB_UPD_DUMP_SEP));
				if ($header === null) {
					$header = json_decode(substr($stmt, 3), true);
					if (!is_array($header) || empty($header['sbupd_dump']) || ($header['prefix'] ?? null) !== $prefix) throw new RuntimeException('Sauvegarde SQL non reconnue');
					// Sauvegardes d'avant la 4.80 : MySQL
					if (($header['driver'] ?? 'mysql') !== $db->driver()) throw new RuntimeException('Sauvegarde SQL d\'une base ' . ($header['driver'] ?? 'mysql') . ' : restauration impossible sur ' . $db->driver());
					// Tables (du périmètre) créées depuis la sauvegarde : supprimées
					$current = sbUpdDbTables($db, $prefix);
					if (isset($header['scope'])) $current = array_intersect($current, (array) $header['scope']);
					foreach (array_diff($current, $header['tables']) as $extra) sbdbschema::exec($db, 'DROP TABLE IF EXISTS `' . str_replace('`', '``', $extra) . '`');
					continue;
				}
				if ($stmt === '') continue;
				try {
					sbdbschema::exec($db, $stmt);
				} catch (RuntimeException $e) {
					throw new RuntimeException('Restauration SQL en échec : ' . $e->getMessage());
				}
			}
			if (feof($fh) && $buf !== '') {
				if (trim($buf) !== '') throw new RuntimeException('Sauvegarde SQL tronquée');
				$buf = '';
			}
		}
		if ($sqlite && $header === null) throw new RuntimeException('Sauvegarde SQL vide');
		if ($sqlite) $db->pdo()->commit();
	} catch (Throwable $e) {
		if ($sqlite && $db->pdo()->inTransaction()) $db->pdo()->rollBack();
		throw $e;
	} finally {
		$db->pdo()->exec($sqlite ? 'PRAGMA foreign_keys = ON' : 'SET FOREIGN_KEY_CHECKS = 1');
		fclose($fh);
	}
	if ($header === null) throw new RuntimeException('Sauvegarde SQL vide');
}

// -----------------------------------------------------------------------
// Chiffrement des sauvegardes (libsodium secretstream, clé de sbdbconfig.php)
// -----------------------------------------------------------------------

defined('SB_UPD_ENC_MAGIC') or define('SB_UPD_ENC_MAGIC', "SBUPDENC1\n");
defined('SB_UPD_ENC_CHUNK') or define('SB_UPD_ENC_CHUNK', 1024 * 1024);

function sbUpdBackupKey() {
	$k = function_exists('sbSecretKey') ? sbSecretKey() : false;
	if (!$k || strlen($k) !== SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES) {
		throw new RuntimeException('Clé de chiffrement absente (sbdbconfig.php) : sauvegardes chiffrées impossibles, mise à jour refusée');
	}
	return $k;
}

function sbUpdEncryptFile($in, $out) {
	$key = sbUpdBackupKey();
	$src = fopen($in, 'rb');
	$dst = fopen($out, 'wb');
	if (!$src || !$dst) throw new RuntimeException('Chiffrement : fichier inaccessible');
	@chmod($out, 0600);
	list($state, $header) = sodium_crypto_secretstream_xchacha20poly1305_init_push($key);
	fwrite($dst, SB_UPD_ENC_MAGIC . $header);
	do {
		$chunk = fread($src, SB_UPD_ENC_CHUNK);
		$last  = feof($src);
		fwrite($dst, pack('N', strlen($chunk) + SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_ABYTES)
			. sodium_crypto_secretstream_xchacha20poly1305_push($state, $chunk, '', $last ? SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL : SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_MESSAGE));
	} while (!$last);
	fclose($src);
	if (!fclose($dst)) throw new RuntimeException('Chiffrement : écriture impossible');
}

function sbUpdDecryptFile($in, $out) {
	$key = sbUpdBackupKey();
	$src = fopen($in, 'rb');
	if (!$src) throw new RuntimeException('Sauvegarde illisible');
	$magic = fread($src, strlen(SB_UPD_ENC_MAGIC));
	$header = fread($src, SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_HEADERBYTES);
	if ($magic !== SB_UPD_ENC_MAGIC || strlen($header) !== SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_HEADERBYTES) { fclose($src); throw new RuntimeException('Sauvegarde non reconnue'); }
	$state = sodium_crypto_secretstream_xchacha20poly1305_init_pull($header, $key);
	$dst = fopen($out, 'wb');
	@chmod($out, 0600);
	$final = false;
	while (!$final) {
		$len = fread($src, 4);
		if (strlen($len) !== 4) { fclose($src); fclose($dst); @unlink($out); throw new RuntimeException('Sauvegarde tronquée'); }
		$n = unpack('N', $len)[1];
		if ($n < SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_ABYTES || $n > SB_UPD_ENC_CHUNK + 64) { fclose($src); fclose($dst); @unlink($out); throw new RuntimeException('Sauvegarde corrompue'); }
		$res = sodium_crypto_secretstream_xchacha20poly1305_pull($state, fread($src, $n));
		if ($res === false) { fclose($src); fclose($dst); @unlink($out); throw new RuntimeException('Sauvegarde altérée ou mauvaise clé'); }
		fwrite($dst, $res[0]);
		$final = ($res[1] === SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL);
	}
	$trailing = fread($src, 1);
	fclose($src);
	fclose($dst);
	if ($trailing !== '' && $trailing !== false) { @unlink($out); throw new RuntimeException('Sauvegarde altérée (données en trop)'); }
}
