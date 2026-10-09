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
	/** @var mysqli */
	private $db;
	private $prefix;
	private $schema;
	public $log = array();

	public function __construct(mysqli $db, $prefix) {
		$this->db = $db;
		$this->prefix = (string) $prefix;
		$r = $db->query('SELECT DATABASE()');
		$this->schema = $r ? (string) $r->fetch_row()[0] : '';
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
	private function q($v) {
		return "'" . $this->db->real_escape_string((string) $v) . "'";
	}
	private function scalar($sql) {
		$r = $this->db->query($sql);
		if ($r === false) throw new RuntimeException('Requête en échec : ' . $this->db->error);
		$row = $r->fetch_row();
		return $row ? $row[0] : null;
	}

	/** Requête libre (doit être idempotente) */
	public function query($sql) {
		if ($this->db->query($sql) === false) throw new RuntimeException('Requête en échec (' . $this->db->error . ') : ' . substr($sql, 0, 200));
		$this->log[] = $sql;
		return true;
	}

	public function tableExists($table) {
		return (int) $this->scalar('SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = ' . $this->q($this->schema) . ' AND TABLE_NAME = ' . $this->q($this->table($table))) > 0;
	}
	public function columnExists($table, $column) {
		return (int) $this->scalar('SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ' . $this->q($this->schema) . ' AND TABLE_NAME = ' . $this->q($this->table($table)) . ' AND COLUMN_NAME = ' . $this->q($column)) > 0;
	}
	public function indexExists($table, $index) {
		return (int) $this->scalar('SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ' . $this->q($this->schema) . ' AND TABLE_NAME = ' . $this->q($this->table($table)) . ' AND INDEX_NAME = ' . $this->q($index)) > 0;
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
		if ((int) $this->scalar("SELECT COUNT(*) FROM $t WHERE `config` = " . $this->q($config)) > 0) return false;
		return $this->query("INSERT INTO $t (`config`, `content`) VALUES (" . $this->q($config) . ', ' . $this->q($content) . ')');
	}
	public function deleteConfig($config) {
		return $this->query('DELETE FROM ' . $this->ident($this->table('sb_config')) . ' WHERE `config` = ' . $this->q($config));
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
		sbSettingsSave(array('db_version' => $v));
	}
	return $v;
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
function sbUpdMigrateUp(mysqli $db, $prefix, array $pending, $progress = null) {
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
function sbUpdMigrateDown(mysqli $db, $prefix, array $files, $toDb, $progress = null) {
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
function sbUpdDbTables(mysqli $db, $prefix) {
	$like = str_replace(array('\\', '_', '%'), array('\\\\', '\\_', '\\%'), $prefix) . '%';
	$r = $db->query("SHOW FULL TABLES LIKE '" . $db->real_escape_string($like) . "'");
	if ($r === false) throw new RuntimeException('Liste des tables illisible : ' . $db->error);
	$tables = array();
	while ($row = $r->fetch_row()) if (($row[1] ?? 'BASE TABLE') === 'BASE TABLE') $tables[] = $row[0];
	return $tables;
}

/** Taille approximative des tables du CMS (octets), pour l'espace disque */
function sbUpdDbSize(mysqli $db, $prefix) {
	$like = str_replace(array('\\', '_', '%'), array('\\\\', '\\_', '\\%'), $prefix) . '%';
	$r = $db->query("SELECT COALESCE(SUM(DATA_LENGTH + INDEX_LENGTH), 0) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME LIKE '" . $db->real_escape_string($like) . "'");
	return $r ? (int) $r->fetch_row()[0] : 0;
}

/**
 * Écrit la sauvegarde SQL (en clair) dans $file : en-tête JSON (tables),
 * puis une instruction par bloc (séparateur SB_UPD_DUMP_SEP).
 * @return array tables sauvegardées
 */
function sbUpdDbDump(mysqli $db, $prefix, $file) {
	$tables = sbUpdDbTables($db, $prefix);
	$fh = fopen($file, 'wb');
	if (!$fh) throw new RuntimeException('Sauvegarde SQL impossible à créer');
	@chmod($file, 0600);
	fwrite($fh, '-- ' . json_encode(array('sbupd_dump' => 1, 'date' => date('c'), 'prefix' => $prefix, 'tables' => $tables)) . SB_UPD_DUMP_SEP);
	foreach ($tables as $t) {
		$c = $db->query('SHOW CREATE TABLE `' . str_replace('`', '``', $t) . '`');
		if ($c === false) throw new RuntimeException('Structure illisible : ' . $t);
		fwrite($fh, 'DROP TABLE IF EXISTS `' . str_replace('`', '``', $t) . '`' . SB_UPD_DUMP_SEP);
		fwrite($fh, $c->fetch_row()[1] . SB_UPD_DUMP_SEP);
		$r = $db->query('SELECT * FROM `' . str_replace('`', '``', $t) . '`', MYSQLI_USE_RESULT);
		if ($r === false) throw new RuntimeException('Données illisibles : ' . $t);
		$fields = $r->fetch_fields();
		$cols = array();
		$binary = array();
		foreach ($fields as $i => $f) {
			$cols[] = '`' . str_replace('`', '``', $f->name) . '`';
			$binary[$i] = ($f->charsetnr == 63 && in_array($f->type, array(MYSQLI_TYPE_BLOB, MYSQLI_TYPE_TINY_BLOB, MYSQLI_TYPE_MEDIUM_BLOB, MYSQLI_TYPE_LONG_BLOB, MYSQLI_TYPE_STRING, MYSQLI_TYPE_VAR_STRING), true));
		}
		$head = 'INSERT INTO `' . str_replace('`', '``', $t) . '` (' . implode(', ', $cols) . ') VALUES ';
		$batch = array();
		$size = 0;
		while ($row = $r->fetch_row()) {
			$vals = array();
			foreach ($row as $i => $v) {
				if ($v === null) $vals[] = 'NULL';
				elseif ($binary[$i]) $vals[] = ($v === '') ? "''" : '0x' . bin2hex($v);
				else $vals[] = "'" . $db->real_escape_string($v) . "'";
			}
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
		$r->free();
	}
	if (!fclose($fh)) throw new RuntimeException('Sauvegarde SQL impossible à écrire');
	return $tables;
}

/**
 * Remet la base dans l'état de la sauvegarde : tables recréées et
 * remplies, tables du CMS apparues depuis supprimées.
 */
function sbUpdDbRestore(mysqli $db, $prefix, $file) {
	$fh = fopen($file, 'rb');
	if (!$fh) throw new RuntimeException('Sauvegarde SQL illisible');
	$header = null;
	$buf = '';
	$db->query('SET FOREIGN_KEY_CHECKS = 0');
	try {
		while (!feof($fh) || $buf !== '') {
			if (!feof($fh)) $buf .= fread($fh, 1024 * 1024);
			while (($p = strpos($buf, SB_UPD_DUMP_SEP)) !== false) {
				$stmt = substr($buf, 0, $p);
				$buf = substr($buf, $p + strlen(SB_UPD_DUMP_SEP));
				if ($header === null) {
					$header = json_decode(substr($stmt, 3), true);
					if (!is_array($header) || empty($header['sbupd_dump']) || ($header['prefix'] ?? null) !== $prefix) throw new RuntimeException('Sauvegarde SQL non reconnue');
					// Tables du CMS créées depuis la sauvegarde : supprimées
					foreach (array_diff(sbUpdDbTables($db, $prefix), $header['tables']) as $extra) {
						if ($db->query('DROP TABLE IF EXISTS `' . str_replace('`', '``', $extra) . '`') === false) throw new RuntimeException('Suppression impossible : ' . $extra);
					}
					continue;
				}
				if ($stmt !== '' && $db->query($stmt) === false) throw new RuntimeException('Restauration SQL en échec : ' . $db->error);
			}
			if (feof($fh) && $buf !== '') {
				if (trim($buf) !== '') throw new RuntimeException('Sauvegarde SQL tronquée');
				$buf = '';
			}
		}
	} finally {
		$db->query('SET FOREIGN_KEY_CHECKS = 1');
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
