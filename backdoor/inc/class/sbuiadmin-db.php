<?php
/**
 * PDO Class
 * SBUIADMIN Class
 *
 * Accès base par PDO, MySQL/MariaDB ou SQLite, une seule connexion par page
 * (sbDb()). Toutes les valeurs passent en paramètres liés ; les noms de
 * tables et de colonnes sont vérifiés (ils ne peuvent pas être liés).
 *
 *   $db = sbDb();
 *   $row = $db->row("SELECT * FROM {sb_users} WHERE username = ?", array($login));
 *   $id  = $db->insert('sb_faq', array('question' => $q, 'catid' => $cat));
 *   $n   = $db->update('sb_users', array('active' => 0), 'id = ?', array($id));
 *   $order = $db->order($_GET['tri'], array('date', 'title'), 'date');
 *
 * {sb_users} dans une requête devient la table préfixée (`tst_sb_users`).
 * En cas d'erreur : false (ou null pour row()/value()), message dans error().
 * Les valeurs lues sont des chaînes (NULL reste NULL), comme avec mysqli.
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
defined('SBUIADMIN_PATH') or defined('SBUIADMIN_DBCONFIG') or die('Are you crazy!');

class sbdb {
	/** Pilotes gérés : nom => extension PHP */
	const DRIVERS = array('mysql' => 'pdo_mysql', 'sqlite' => 'pdo_sqlite');

	private $pdo;
	private $driver;
	private $prefix;
	private $error   = '';
	private $last    = array('', array());
	private $columns = array();

	private function __construct(PDO $pdo, $driver, $prefix) {
		$this->pdo    = $pdo;
		$this->driver = $driver;
		$this->prefix = $prefix;
	}

	/**
	 * Connexion. $c : driver (mysql par défaut), host (« hôte », « hôte:port »
	 * ou « hôte:/socket »), name, user, password, prefix ; path pour SQLite.
	 * @throws RuntimeException connexion impossible (sans mot de passe dans le message)
	 */
	public static function connect(array $c) {
		$driver = isset($c['driver']) && $c['driver'] !== '' ? (string) $c['driver'] : 'mysql';
		$prefix = isset($c['prefix']) ? (string) $c['prefix'] : '';
		if (!isset(self::DRIVERS[$driver])) throw new RuntimeException('Type de base inconnu : ' . $driver);
		if (!extension_loaded(self::DRIVERS[$driver])) throw new RuntimeException('Extension PHP ' . self::DRIVERS[$driver] . ' absente');
		if ($prefix !== '' && !preg_match('/^[A-Za-z0-9_]{1,32}$/', $prefix)) throw new RuntimeException('Préfixe de tables invalide');
		foreach (array('host', 'name', 'path') as $k) {
			// Un « ; » ajouterait des paramètres à la chaîne de connexion
			if (isset($c[$k]) && preg_match('/[;\x00]/', (string) $c[$k])) throw new RuntimeException('Accès à la base invalide (' . $k . ')');
		}
		$options = array(
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_STRINGIFY_FETCHES  => true,
			PDO::ATTR_EMULATE_PREPARES   => false,
		);
		try {
			if ($driver === 'mysql') {
				list($host, $port, $socket) = self::hostParts(isset($c['host']) ? $c['host'] : '');
				$dsn = 'mysql:' . ($socket !== false ? 'unix_socket=' . $socket : 'host=' . $host . ';port=' . $port)
					. ';dbname=' . (isset($c['name']) ? $c['name'] : '') . ';charset=utf8mb4';
				$options[Pdo\Mysql::ATTR_MULTI_STATEMENTS] = false;
				$pdo = PDO::connect($dsn, isset($c['user']) ? (string) $c['user'] : '', isset($c['password']) ? (string) $c['password'] : '', $options);
			} else {
				$path = isset($c['path']) ? (string) $c['path'] : '';
				if ($path === '') throw new RuntimeException('Fichier SQLite non défini');
				$pdo = PDO::connect('sqlite:' . $path, null, null, $options);
				$pdo->exec('PRAGMA foreign_keys = ON');
				$pdo->exec('PRAGMA busy_timeout = 5000');
				self::sqliteFunctions($pdo);
			}
		} catch (PDOException $e) {
			// Le message de PDO peut citer l'utilisateur : code seul
			throw new RuntimeException('Connexion à la base impossible (' . $driver . ', code ' . $e->getCode() . ')');
		}
		return new self($pdo, $driver, $prefix);
	}

	/** « hôte », « hôte:port » ou « hôte:/socket » -> array(hôte, port, socket|false) */
	public static function hostParts($host) {
		$host = (string) $host;
		if (strpos($host, ':') === false) return array($host, 3306, false);
		list($h, $extra) = explode(':', $host, 2);
		return preg_match('/^\d+$/', $extra) ? array($h, (int) $extra, false) : array($h, 3306, $extra);
	}

	/** Fonctions MySQL courantes, recréées pour SQLite */
	private static function sqliteFunctions(Pdo\Sqlite $pdo) {
		$det = Pdo\Sqlite::DETERMINISTIC;
		$pdo->createFunction('NOW', function () { return date('Y-m-d H:i:s'); }, 0);
		$pdo->createFunction('CURDATE', function () { return date('Y-m-d'); }, 0);
		$pdo->createFunction('CURTIME', function () { return date('H:i:s'); }, 0);
		$pdo->createFunction('UNIX_TIMESTAMP', function ($d = null) {
			if ($d === null) return time();
			$t = strtotime((string) $d);
			return $t === false ? null : $t;
		}, -1);
		$pdo->createFunction('FROM_UNIXTIME', function ($t, $f = null) {
			if ($t === null) return null;
			return $f === null ? date('Y-m-d H:i:s', (int) $t) : sbdb::mysqlDate((string) $f, (int) $t);
		}, -1, $det);
		$pdo->createFunction('DATE_FORMAT', function ($d, $f) {
			if ($d === null || $f === null) return null;
			$t = strtotime((string) $d);
			return $t === false ? null : sbdb::mysqlDate((string) $f, $t);
		}, 2, $det);
		$pdo->createFunction('CONCAT', function () {
			$a = func_get_args();
			return in_array(null, $a, true) ? null : implode('', $a);
		}, -1, $det);
		$pdo->createFunction('RAND', function () { return mt_rand() / mt_getrandmax(); }, 0);
		$pdo->createFunction('LCASE', function ($s) { return $s === null ? null : mb_strtolower((string) $s, 'UTF-8'); }, 1, $det);
		$pdo->createFunction('UCASE', function ($s) { return $s === null ? null : mb_strtoupper((string) $s, 'UTF-8'); }, 1, $det);
		// « a REGEXP b » appelle regexp(b, a) ; insensible à la casse comme MySQL
		$pdo->createFunction('REGEXP', function ($pattern, $value) {
			if ($pattern === null || $value === null) return null;
			$r = @preg_match('~' . str_replace('~', '\~', (string) $pattern) . '~iu', (string) $value);
			return $r === false ? 0 : $r;
		}, 2, $det);
	}

	/** Format de DATE_FORMAT() de MySQL appliqué à un horodatage */
	public static function mysqlDate($format, $t) {
		static $map = array('Y' => 'Y', 'y' => 'y', 'm' => 'm', 'c' => 'n', 'd' => 'd', 'e' => 'j', 'H' => 'H', 'k' => 'G',
			'h' => 'h', 'I' => 'h', 'l' => 'g', 'i' => 'i', 's' => 's', 'S' => 's', 'p' => 'A', 'M' => 'F', 'b' => 'M',
			'W' => 'l', 'a' => 'D', 'T' => 'H:i:s', 'r' => 'h:i:s A', 'u' => 'W');
		return preg_replace_callback('/%(.)/', function ($m) use ($map, $t) {
			if ($m[1] === '%') return '%';
			if ($m[1] === 'j') return sprintf('%03d', date('z', $t) + 1);
			return isset($map[$m[1]]) ? date($map[$m[1]], $t) : $m[1];
		}, $format);
	}

	public function driver() { return $this->driver; }
	public function prefix() { return $this->prefix; }
	public function pdo()    { return $this->pdo; }
	public function error()  { return $this->error; }
	/** Dernière requête et ses paramètres (affichage de débogage) */
	public function lastQuery() { return $this->last; }

	/** Nom de table ou de colonne sûr, ou exception */
	public function ident($name) {
		if (!is_string($name) || !preg_match('/^[A-Za-z_][A-Za-z0-9_]{0,63}$/', $name)) {
			throw new InvalidArgumentException('Nom de table ou de colonne invalide');
		}
		return '`' . $name . '`';
	}

	/** Table préfixée, entre délimiteurs (`tst_sb_users`) */
	public function table($name) {
		return $this->ident($this->prefix . $name);
	}

	/** {sb_users} -> `tst_sb_users` */
	public function expand($sql) {
		return preg_replace_callback('/\{([A-Za-z_][A-Za-z0-9_]{0,63})\}/', function ($m) {
			return $this->table($m[1]);
		}, $sql);
	}

	/**
	 * Requête préparée. $bind : liste (?) ou tableau nommé (:nom).
	 * @return PDOStatement|false
	 */
	public function run($sql, array $bind = array()) {
		$this->error = '';
		try {
			$sql = $this->expand($sql);
			$this->last = array($sql, $bind);
			$st = $this->pdo->prepare($sql);
			foreach ($bind as $k => $v) {
				$st->bindValue(is_int($k) ? $k + 1 : (strpos($k, ':') === 0 ? $k : ':' . $k), is_bool($v) ? (int) $v : $v, self::type($v));
			}
			$st->execute();
			return $st;
		} catch (PDOException $e) {
			$this->error = isset($e->errorInfo[2]) && $e->errorInfo[2] !== '' ? (string) $e->errorInfo[2] : $e->getMessage();
			return false;
		} catch (InvalidArgumentException $e) {
			$this->error = $e->getMessage();
			return false;
		}
	}

	private static function type($v) {
		if (is_int($v) || is_bool($v)) return PDO::PARAM_INT;
		if ($v === null) return PDO::PARAM_NULL;
		return PDO::PARAM_STR;
	}

	/** Toutes les lignes (tableaux associatifs), ou false */
	public function all($sql, array $bind = array()) {
		$st = $this->run($sql, $bind);
		return $st ? $st->fetchAll(PDO::FETCH_ASSOC) : false;
	}

	/** Première ligne, null si aucune, false en cas d'erreur */
	public function row($sql, array $bind = array()) {
		$st = $this->run($sql, $bind);
		if (!$st) return false;
		$row = $st->fetch(PDO::FETCH_ASSOC);
		$st->closeCursor();
		return $row === false ? null : $row;
	}

	/** Première colonne de la première ligne, null si aucune, false en cas d'erreur */
	public function value($sql, array $bind = array()) {
		$st = $this->run($sql, $bind);
		if (!$st) return false;
		$v = $st->fetchColumn();
		$st->closeCursor();
		return $v === false ? null : $v;
	}

	/** Première colonne de toutes les lignes */
	public function column($sql, array $bind = array()) {
		$st = $this->run($sql, $bind);
		return $st ? $st->fetchAll(PDO::FETCH_COLUMN, 0) : false;
	}

	/**
	 * SELECT simple. $fields : '*' ou liste de colonnes ; $where : fragment
	 * avec ? ou :nom (ORDER BY / LIMIT compris, voir order() et limit()).
	 */
	public function select($table, $where = '', array $bind = array(), $fields = '*') {
		try {
			$cols = $fields === '*' ? '*' : implode(', ', array_map(array($this, 'ident'), (array) $fields));
			$sql = 'SELECT ' . $cols . ' FROM ' . $this->table($table) . ($where !== '' ? ' WHERE ' . $where : '');
		} catch (InvalidArgumentException $e) {
			$this->error = $e->getMessage();
			return false;
		}
		return $this->all($sql, $bind);
	}

	/** INSERT, renvoie l'identifiant créé (0 sans auto-incrément) ou false */
	public function insert($table, array $data) {
		return $this->write('INSERT', $table, $data) ? (int) $this->pdo->lastInsertId() : false;
	}

	/** INSERT sans erreur si la clé existe déjà : nombre de lignes ajoutées (0 ou 1) ou false */
	public function insertIgnore($table, array $data) {
		$st = $this->write($this->driver === 'sqlite' ? 'INSERT OR IGNORE' : 'INSERT IGNORE', $table, $data);
		return $st ? $st->rowCount() : false;
	}

	/**
	 * Ajoute ou met à jour. $keys : colonnes de la clé unique (exigées par
	 * SQLite) ; $update : colonnes mises à jour si la ligne existe (toutes les
	 * autres par défaut). Renvoie true ou false.
	 */
	public function upsert($table, array $data, array $keys, $update = null) {
		try {
			$update = $update === null ? array_values(array_diff(array_keys($data), $keys)) : (array) $update;
			$set = array();
			foreach ($update as $col) {
				$q = $this->ident($col);
				$set[] = $q . ' = ' . ($this->driver === 'sqlite' ? 'excluded.' . $q : 'VALUES(' . $q . ')');
			}
			if ($this->driver === 'sqlite') {
				$tail = ' ON CONFLICT (' . implode(', ', array_map(array($this, 'ident'), $keys)) . ') DO '
					. ($set ? 'UPDATE SET ' . implode(', ', $set) : 'NOTHING');
			} else {
				// Sans colonne à mettre à jour : la ligne existante est gardée telle quelle
				$k = $this->ident($keys ? reset($keys) : key($data));
				$tail = ' ON DUPLICATE KEY UPDATE ' . ($set ? implode(', ', $set) : $k . ' = ' . $k);
			}
		} catch (InvalidArgumentException $e) {
			$this->error = $e->getMessage();
			return false;
		}
		return (bool) $this->write('INSERT', $table, $data, $tail);
	}

	private function write($verb, $table, array $data, $tail = '') {
		if (!$data) { $this->error = 'Aucune donnée à écrire'; return false; }
		try {
			$cols = implode(', ', array_map(array($this, 'ident'), array_keys($data)));
			$sql = $verb . ' INTO ' . $this->table($table) . ' (' . $cols . ') VALUES (' . implode(', ', array_fill(0, count($data), '?')) . ')' . $tail;
		} catch (InvalidArgumentException $e) {
			$this->error = $e->getMessage();
			return false;
		}
		return $this->run($sql, array_values($data));
	}

	/**
	 * UPDATE, renvoie le nombre de lignes modifiées ou false. $where est
	 * obligatoire (« 1 = 1 » pour toute la table) ; $bind de même forme que
	 * ses marqueurs (? ou :nom).
	 */
	public function update($table, array $data, $where, array $bind = array()) {
		if (!$data) { $this->error = 'Aucune donnée à écrire'; return false; }
		if (trim((string) $where) === '') { $this->error = 'Condition WHERE obligatoire'; return false; }
		$named = $bind && !array_is_list($bind);
		$set = array(); $params = array(); $i = 0;
		try {
			foreach ($data as $col => $v) {
				if ($named) {
					$set[] = $this->ident($col) . ' = :sbset' . $i;
					$params['sbset' . $i++] = $v;
				} else {
					$set[] = $this->ident($col) . ' = ?';
					$params[] = $v;
				}
			}
			$sql = 'UPDATE ' . $this->table($table) . ' SET ' . implode(', ', $set) . ' WHERE ' . $where;
		} catch (InvalidArgumentException $e) {
			$this->error = $e->getMessage();
			return false;
		}
		$st = $this->run($sql, $named ? $params + $bind : array_merge($params, $bind));
		return $st ? $st->rowCount() : false;
	}

	/** DELETE, renvoie le nombre de lignes supprimées ou false ; $where obligatoire */
	public function delete($table, $where, array $bind = array()) {
		if (trim((string) $where) === '') { $this->error = 'Condition WHERE obligatoire'; return false; }
		try {
			$sql = 'DELETE FROM ' . $this->table($table) . ' WHERE ' . $where;
		} catch (InvalidArgumentException $e) {
			$this->error = $e->getMessage();
			return false;
		}
		$st = $this->run($sql, $bind);
		return $st ? $st->rowCount() : false;
	}

	/** Colonnes d'une table (liste mise en cache), array() si absente */
	public function columns($table) {
		$t = $this->prefix . $table;
		if (isset($this->columns[$t])) return $this->columns[$t];
		try {
			$q = $this->ident($t);
		} catch (InvalidArgumentException $e) {
			return array();
		}
		$rows = $this->driver === 'sqlite'
			? $this->all('PRAGMA table_info(' . $q . ')')
			: $this->all('SHOW COLUMNS FROM ' . $q);
		$cols = array();
		foreach ($rows ?: array() as $r) $cols[] = $this->driver === 'sqlite' ? $r['name'] : $r['Field'];
		return $this->columns[$t] = $cols;
	}

	/** Ne garde que les clés qui sont des colonnes de la table */
	public function filter($table, array $data) {
		return array_intersect_key($data, array_flip($this->columns($table)));
	}

	/** Tables de la base (préfixe compris), filtrées par début de nom */
	public function tables($startsWith = '') {
		$all = $this->driver === 'sqlite'
			? $this->column("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite\\_%' ESCAPE '\\' ORDER BY name")
			: $this->column('SHOW TABLES');
		$out = array();
		foreach ($all ?: array() as $t) {
			if ($startsWith === '' || strpos($t, $startsWith) === 0) $out[] = $t;
		}
		return $out;
	}

	/** La table (sans préfixe) existe-t-elle ? */
	public function hasTable($table) {
		return in_array($this->prefix . $table, $this->tables($this->prefix . $table), true);
	}

	/**
	 * Tri sûr : colonne prise dans $allowed (sinon $default), sens ASC/DESC.
	 * $allowed peut associer une clé reçue à une colonne : array('nom' => 'title').
	 */
	public function order($col, array $allowed, $default, $dir = 'ASC') {
		$map = array();
		foreach ($allowed as $k => $v) $map[is_int($k) ? $v : $k] = $v;
		$col = is_string($col) && isset($map[$col]) ? $map[$col] : $default;
		$dir = strtoupper((string) $dir) === 'DESC' ? 'DESC' : 'ASC';
		return $this->ident($col) . ' ' . $dir;
	}

	/** LIMIT sûr (entiers) */
	public function limit($count, $offset = 0) {
		return 'LIMIT ' . max(0, (int) $count) . ' OFFSET ' . max(0, (int) $offset);
	}

	/** Exécute $fn($this) dans une transaction ; annulée si $fn lève une exception */
	public function transaction(callable $fn) {
		$this->pdo->beginTransaction();
		try {
			$r = $fn($this);
			$this->pdo->commit();
			return $r;
		} catch (Throwable $e) {
			if ($this->pdo->inTransaction()) $this->pdo->rollBack();
			throw $e;
		}
	}

	public function lastInsertId() { return (int) $this->pdo->lastInsertId(); }

	/** Chaîne échappée sans guillemets (ancien code seulement : préférer les paramètres liés) */
	public function escape($s) {
		$s = (string) $s;
		// SQLite refuse l'octet nul dans un littéral : retiré
		if ($this->driver === 'sqlite') $s = str_replace("\0", '', $s);
		return substr($this->pdo->quote($s), 1, -1);
	}

	/** Version du serveur (« 8.0.36 », « 3.45.1 ») */
	public function serverVersion() {
		return (string) $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
	}
}

/**
 * Connexion partagée par toute la page (accès de sbDbConfig()), null si
 * impossible : la raison est dans sbDbError().
 */
function sbDb($reset = false) {
	static $db = false;
	if ($db !== false && !$reset) return $db;
	$db = null;
	$GLOBALS['sb_db_error'] = '';
	if (!function_exists('sbDbConfig')) {
		$GLOBALS['sb_db_error'] = 'Réglages de la base non chargés';
		return $db;
	}
	try {
		$db = sbdb::connect(sbDbConfig());
	} catch (RuntimeException $e) {
		$GLOBALS['sb_db_error'] = $e->getMessage();
		error_log('SBUIADMIN : ' . $e->getMessage());
	}
	return $db;
}

/** Raison du dernier échec de sbDb() */
function sbDbError() {
	return isset($GLOBALS['sb_db_error']) ? (string) $GLOBALS['sb_db_error'] : '';
}

/**
 * Base inutilisable : page 503 claire (jamais d'erreur PHP ni de détail
 * technique à l'écran), puis arrêt. Extension PDO absente : son nom, à
 * activer chez l'hébergeur. En ligne de commande : message sur STDERR.
 */
function sbDbUnavailable() {
	$c = function_exists('sbDbConfig') ? sbDbConfig() : array();
	$driver = isset($c['driver']) && isset(sbdb::DRIVERS[$c['driver']]) ? $c['driver'] : 'mysql';
	$ext = sbdb::DRIVERS[$driver];
	$missing = !extension_loaded($ext);
	$title = $missing ? 'Extension PHP manquante' : 'Base de données indisponible';
	$text  = $missing
		? "L'extension PHP « " . $ext . " » n'est pas activée sur ce serveur. Elle est nécessaire au site depuis la version 4.72 : activez-la dans la configuration PHP de l'hébergement (ou demandez-le à votre hébergeur), puis rechargez la page."
		: 'Le site ne parvient pas à joindre sa base de données. Merci de revenir dans quelques instants.';
	if (PHP_SAPI === 'cli') {
		fwrite(STDERR, 'SBUIADMIN : ' . $title . ' - ' . $text . (sbDbError() !== '' ? ' (' . sbDbError() . ')' : '') . "\n");
		exit(1);
	}
	if (!headers_sent()) {
		http_response_code(503);
		header('Retry-After: ' . ($missing ? 3600 : 60));
		header('Content-Type: text/html; charset=utf-8');
		header('Cache-Control: no-store');
	}
	while (ob_get_level() > 0) @ob_end_clean();
	echo '<!doctype html><html lang="fr"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>' . $title . '</title>'
		. '<div style="font-family:sans-serif;max-width:36em;margin:20vh auto 0;padding:0 16px;text-align:center"><h1 style="font-size:1.4em">' . $title . '</h1><p>' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</p></div></html>';
	exit;
}

/** Pilote PDO disponible sur ce serveur ? ('mysql' ou 'sqlite') */
function sbDbDriverAvailable($driver) {
	return isset(sbdb::DRIVERS[$driver]) && extension_loaded(sbdb::DRIVERS[$driver]);
}
