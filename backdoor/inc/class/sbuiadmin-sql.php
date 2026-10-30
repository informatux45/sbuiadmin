<?php
/**
 * SQL Class (ancienne API, sur PDO depuis la 4.72)
 * SBUIADMIN Class
 *
 * Mêmes méthodes qu'avant (query, assoc, toarray, object, numrows,
 * lastinsertid, escape_string, error), sur la connexion PDO partagée
 * sbDb() : MySQL/MariaDB ou SQLite. query() renvoie un sbresult, qui imite
 * aussi mysqli_result (fetch_assoc(), fetch_row(), num_rows...), ou true
 * (INSERT, UPDATE...), ou false. Les fonctions mysqli_* ne marchent plus sur
 * ces résultats. Nouveau code : sbDb() et ses requêtes préparées.
 *
 * @link https://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sbuiadmin-db.php');

// sbconfig.php d'avant 2018 (propre au site, jamais remplacé par la mise à
// jour) : ni port ni socket, lus depuis l'hôte (« hôte:port » ou « hôte:/socket »)
if (!defined('_AM_DB_PORT') || !defined('_AM_DB_SOCKET')) {
	$sb_db_parts = function_exists('sbDbHostParts') ? sbDbHostParts(defined('_AM_DB_HOST') ? _AM_DB_HOST : '') : array('', 3306, false);
	defined('_AM_DB_PORT')   or define('_AM_DB_PORT',   $sb_db_parts[1]);
	defined('_AM_DB_SOCKET') or define('_AM_DB_SOCKET', $sb_db_parts[2]);
	unset($sb_db_parts);
}

/** Résultat d'une requête (lignes déjà lues, comme mysqli_query()) */
class sbresult implements IteratorAggregate, Countable {
	public $num_rows    = 0;
	public $field_count = 0;
	private $names = array();
	private $rows  = array();
	private $pos   = 0;

	public function __construct(PDOStatement $st) {
		$this->field_count = $st->columnCount();
		for ($i = 0; $i < $this->field_count; $i++) {
			$meta = $st->getColumnMeta($i);
			$this->names[$i] = $meta && isset($meta['name']) ? (string) $meta['name'] : (string) $i;
		}
		$this->rows = $st->fetchAll(PDO::FETCH_NUM);
		$st->closeCursor();
		$this->num_rows = count($this->rows);
	}

	private function next() {
		return $this->pos < $this->num_rows ? $this->rows[$this->pos++] : null;
	}

	/** Colonne de même nom : la dernière l'emporte (comme mysqli) */
	private function assocOf(array $r) {
		$a = array();
		foreach ($r as $i => $v) $a[$this->names[$i]] = $v;
		return $a;
	}

	private function bothOf(array $r) {
		$a = array();
		foreach ($r as $i => $v) { $a[$i] = $v; $a[$this->names[$i]] = $v; }
		return $a;
	}

	public function fetch_assoc() { $r = $this->next(); return $r === null ? null : $this->assocOf($r); }
	public function fetch_row()   { return $this->next(); }
	public function fetch_array($mode = 3) {
		$r = $this->next();
		if ($r === null) return null;
		return $mode === 2 ? $r : ($mode === 1 ? $this->assocOf($r) : $this->bothOf($r)); // 1 ASSOC, 2 NUM, 3 BOTH
	}
	public function fetch_object() { $r = $this->fetch_assoc(); return $r === null ? null : (object) $r; }
	public function fetch_all($mode = 2) {
		$out = array();
		while (($r = $this->fetch_array($mode)) !== null) $out[] = $r;
		return $out;
	}
	public function data_seek($n) {
		if ($n < 0 || $n >= $this->num_rows) return false;
		$this->pos = (int) $n;
		return true;
	}
	public function free() { $this->rows = array(); $this->num_rows = 0; $this->pos = 0; }
	public function close() { $this->free(); }
	public function count(): int { return $this->num_rows; }
	public function getIterator(): Iterator {
		foreach ($this->rows as $r) yield $this->assocOf($r);
	}
}

class sql {
	public $error  = '';
	public $query  = '';
	/** Dernier résultat (sbresult, true ou false) */
	public $result_id = false;
	/** Lignes lues (SELECT, SHOW...) ou modifiées (INSERT, UPDATE...) par la dernière requête */
	private $rows = 0;
	private static $lastError = '';

	/** Connexion partagée ; base indisponible : page d'erreur 503 (sbDbUnavailable) */
	protected function db() {
		$db = sbDb();
		if (!$db) sbDbUnavailable();
		return $db;
	}

	public function query($query) {
		$db  = $this->db();
		$pdo = $db->pdo();
		$this->query = trim((string) $query);
		$this->rows  = 0;
		try {
			// Requête complète, sans paramètres : envoyée telle quelle (comme
			// mysqli_query), y compris les ordres que MySQL ne sait pas préparer
			$st = $db->driver() === 'mysql'
				? $pdo->prepare($this->query, array(PDO::ATTR_EMULATE_PREPARES => true))
				: $pdo->prepare($this->query);
			$st->execute();
			if ($st->columnCount() > 0) {
				$this->result_id = new sbresult($st);
				$this->rows = $this->result_id->num_rows;
			} else {
				$this->rows = $st->rowCount();
				$st->closeCursor();
				$this->result_id = true;
			}
			$this->error = self::$lastError = '';
		} catch (PDOException $e) {
			$this->result_id = false;
			$this->error = self::$lastError = isset($e->errorInfo[2]) && $e->errorInfo[2] !== '' ? (string) $e->errorInfo[2] : $e->getMessage();
		}
		return $this->result_id;
	}

	public function lastinsertid() {
		return $this->db()->lastInsertId();
	}

	/** Lignes de la dernière requête : lues (SELECT) ou modifiées (INSERT, UPDATE, DELETE) ; 0 après un échec */
	public function numrows() {
		return $this->result_id === false ? 0 : $this->rows;
	}

	public function object($result) {
		return ($result instanceof sbresult) ? $result->fetch_object() : null;
	}

	public function assoc($result) {
		if (!($result instanceof sbresult)) return false;
		return $result->fetch_assoc();
	}

	/** Lignes restantes, chaque colonne par nom ET par numéro (comme MYSQLI_BOTH) */
	public function toarray($result) {
		if (!($result instanceof sbresult)) return false;
		return $result->fetch_all(3);
	}

	// --- Publique : appelée hors de la classe (plugin {lang}, modules)
	public function error() {
		return self::$lastError;
	}

	// --- Ancienne requête multiple : plus jamais de résultat suivant
	// --- (une requête à la fois, voir sbdb::connect)
	public function next_result() {
		return false;
	}

	// --- La connexion est partagée par toute la page : jamais fermée ici
	public function close() {
		return true;
	}

	public function free() {
	}

	/** Chaîne échappée sans guillemets (ancien code : préférer sbDb() et ses paramètres liés) */
	public function escape_string($escapestr) {
		return $this->db()->escape((string) $escapestr);
	}
}

?>
