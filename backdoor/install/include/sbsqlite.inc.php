<?php
/**
 * SBUIADMIN Installeur : base SQLite
 *
 * Même interface que la classe Database de l'installeur (Open, Run,
 * GetVersion, Error...) pour une base SQLite : le dump d'installation,
 * écrit pour MySQL, est traduit par sbdbschema (inc/class/sbuiadmin-dbschema.php).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('EI_APPLICATION_NAME')) { http_response_code(404); exit; }

class SbInstallSqlite {
	private $path;
	private $prefix;
	private $db = null;
	private $error = '';

	public function __construct($path, $prefix) {
		$this->path = (string) $path;
		$this->prefix = (string) $prefix;
	}

	/** Le fichier est créé à la connexion */
	public function Create() { return $this->Open(); }

	public function Open() {
		if ($this->db) return true;
		$err = sbDbSqlitePathError($this->path);
		if ($err !== '') { $this->error = $err; return false; }
		try {
			$this->db = sbdb::connect(array('driver' => 'sqlite', 'path' => $this->path, 'prefix' => $this->prefix));
			@chmod($this->path, 0640);
			return true;
		} catch (RuntimeException $e) {
			$this->error = $e->getMessage();
			return false;
		}
	}

	public function GetVersion()  { return $this->db ? $this->db->serverVersion() : ''; }
	public function GetDbDriver() { return 'SQLite'; }
	public function Error()       { return htmlspecialchars($this->error, ENT_QUOTES, 'UTF-8'); }

	/** Requête préparée (comme Database::Run) : false en cas d'échec */
	public function Run($query, array $bind = array()) {
		if (!$this->Open()) return false;
		try {
			$st = $this->db->pdo()->prepare($query);
			$st->execute(array_values($bind));
			return $st;
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			return false;
		}
	}

	/** Dump d'installation (MySQL) traduit et exécuté, en une transaction */
	public function Install($sql_dump_file) {
		if (!$this->Open()) return false;
		$sql = str_ireplace('<DB_PREFIX>', $this->prefix, (string) @file_get_contents($sql_dump_file));
		if ($sql === '') { $this->error = 'Dump illisible'; return false; }
		$pdo = $this->db->pdo();
		try {
			$pdo->beginTransaction();
			sbdbschema::runScript($this->db, $sql);
			$pdo->commit();
			return true;
		} catch (Throwable $e) {
			if ($pdo->inTransaction()) $pdo->rollBack();
			$this->error = $e->getMessage();
			return false;
		}
	}
}
