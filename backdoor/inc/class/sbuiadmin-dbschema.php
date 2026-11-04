<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Schéma des tables, MySQL/MariaDB et SQLite
 *
 * Les scripts SQL du CMS (dump d'installation, install.php des modules,
 * migrations) sont écrits pour MySQL. Sous SQLite, ils sont lus puis
 * traduits : CREATE TABLE et ses ALTER TABLE (clé primaire, index,
 * AUTO_INCREMENT) réunis en une seule création, index en CREATE INDEX,
 * chaînes à la mode SQLite, instructions propres à MySQL (SET, /*!...)
 * ignorées. Les ALTER TABLE sur une table existante sont rejoués par
 * reconstruction de la table quand SQLite ne sait pas les faire.
 *
 * Le type MySQL d'origine est gardé dans un commentaire de la création
 * SQLite (« /*my:{...}*\/ ») : une base SQLite peut ainsi être recopiée
 * vers MySQL avec ses types exacts (Configuration > Réglages > Base de
 * données).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or defined('SBUIADMIN_DBCONFIG') or die('Are you crazy!');

class sbdbschema {

	// -------------------------------------------------------------------
	// Lecture du SQL
	// -------------------------------------------------------------------

	/** Script -> instructions (commentaires retirés, sauf /*! ... *\/ gardés tels quels) */
	public static function split($sql) {
		$sql = (string) $sql;
		$out = array();
		$cur = '';
		$n = strlen($sql);
		for ($i = 0; $i < $n; ) {
			$c = $sql[$i];
			if (($c === '-' && substr($sql, $i, 2) === '--' && ($i + 2 >= $n || ctype_space($sql[$i + 2]))) || $c === '#') {
				$e = strpos($sql, "\n", $i);
				$i = $e === false ? $n : $e + 1;
				$cur .= "\n";
				continue;
			}
			if ($c === '/' && substr($sql, $i, 2) === '/*') {
				$e = strpos($sql, '*/', $i + 2);
				$end = $e === false ? $n : $e + 2;
				$cur .= substr($sql, $i, 3) === '/*!' ? substr($sql, $i, $end - $i) : ' ';
				$i = $end;
				continue;
			}
			if ($c === "'" || $c === '"' || $c === '`') {
				$j = self::skipQuoted($sql, $i);
				$cur .= substr($sql, $i, $j - $i);
				$i = $j;
				continue;
			}
			if ($c === ';') {
				if (trim($cur) !== '') $out[] = trim($cur);
				$cur = '';
				$i++;
				continue;
			}
			$cur .= $c;
			$i++;
		}
		if (trim($cur) !== '') $out[] = trim($cur);
		return $out;
	}

	/** Position après la chaîne ou l'identifiant entre délimiteurs qui commence en $i */
	private static function skipQuoted($s, $i, $backslash = true) {
		$q = $s[$i];
		$n = strlen($s);
		for ($j = $i + 1; $j < $n; $j++) {
			if ($backslash && $q !== '`' && $s[$j] === '\\') { $j++; continue; }
			if ($s[$j] === $q) {
				if ($j + 1 < $n && $s[$j + 1] === $q) { $j++; continue; }
				return $j + 1;
			}
		}
		return $n;
	}

	/**
	 * Jetons : array(type, valeur) ; types : id (mot), qid (`nom`), str
	 * (valeur décodée), num, p (ponctuation), cmt (contenu d'un commentaire).
	 * $mysql : échappements \' \n... de MySQL dans les chaînes.
	 */
	public static function tokens($s, $mysql = true) {
		$s = (string) $s;
		$t = array();
		$n = strlen($s);
		for ($i = 0; $i < $n; ) {
			$c = $s[$i];
			if (ctype_space($c)) { $i++; continue; }
			if ($c === '/' && substr($s, $i, 2) === '/*') {
				$e = strpos($s, '*/', $i + 2);
				$end = $e === false ? $n : $e;
				$t[] = array('cmt', substr($s, $i + 2, $end - $i - 2));
				$i = $e === false ? $n : $e + 2;
				continue;
			}
			if ($c === '-' && substr($s, $i, 2) === '--') {
				$e = strpos($s, "\n", $i);
				$i = $e === false ? $n : $e + 1;
				continue;
			}
			if ($c === '`' || $c === '"' && !$mysql) {
				$j = self::skipQuoted($s, $i, false);
				$t[] = array('qid', str_replace($c . $c, $c, substr($s, $i + 1, $j - $i - 2)));
				$i = $j;
				continue;
			}
			if ($c === "'" || $c === '"') {
				$j = self::skipQuoted($s, $i, $mysql);
				$t[] = array('str', self::unquote(substr($s, $i, $j - $i), $mysql));
				$i = $j;
				continue;
			}
			if (ctype_digit($c) || ($c === '.' && $i + 1 < $n && ctype_digit($s[$i + 1]))) {
				preg_match('/\G[0-9]*\.?[0-9]+(?:[eE][+-]?[0-9]+)?/', $s, $m, 0, $i);
				$t[] = array('num', $m[0]);
				$i += strlen($m[0]);
				continue;
			}
			if (ctype_alpha($c) || $c === '_' || $c === '@' || $c === '$') {
				preg_match('/\G[A-Za-z0-9_@$.]+/', $s, $m, 0, $i);
				$t[] = array('id', $m[0]);
				$i += strlen($m[0]);
				continue;
			}
			$t[] = array('p', $c);
			$i++;
		}
		return $t;
	}

	/** 'chaîne' (avec ses délimiteurs) -> valeur */
	private static function unquote($lit, $mysql) {
		$q = $lit[0];
		$body = substr($lit, 1, -1);
		$body = str_replace($q . $q, $q, $body);
		if (!$mysql) return $body;
		return preg_replace_callback('/\\\\(.)/s', function ($m) {
			$map = array('0' => "\0", 'n' => "\n", 'r' => "\r", 't' => "\t", 'b' => "\x08", 'Z' => "\x1a", '%' => '\\%', '_' => '\\_');
			return isset($map[$m[1]]) ? $map[$m[1]] : $m[1];
		}, $body);
	}

	/** Valeur -> littéral SQL standard ('...' avec '' doublés) */
	public static function quote($v) {
		return "'" . str_replace("'", "''", (string) $v) . "'";
	}

	/** Groupe entre parenthèses à partir de $t[$i] (une « ( ») : array(jetons intérieurs, index après) */
	private static function group(array $t, $i) {
		$depth = 0;
		$inner = array();
		for ($n = count($t); $i < $n; $i++) {
			if ($t[$i] === array('p', '(')) { if ($depth++ === 0) continue; }
			elseif ($t[$i] === array('p', ')')) { if (--$depth === 0) return array($inner, $i + 1); }
			$inner[] = $t[$i];
		}
		throw new RuntimeException('Parenthèse non fermée');
	}

	/** Découpe une liste de jetons sur les virgules de premier niveau */
	private static function commaSplit(array $t) {
		$parts = array(array());
		$depth = 0;
		foreach ($t as $tok) {
			if ($tok === array('p', '(')) $depth++;
			if ($tok === array('p', ')')) $depth--;
			if ($depth === 0 && $tok === array('p', ',')) { $parts[] = array(); continue; }
			$parts[count($parts) - 1][] = $tok;
		}
		return array_values(array_filter($parts));
	}

	private static function kw($tok, $word) {
		return $tok !== null && $tok[0] === 'id' && strcasecmp($tok[1], $word) === 0;
	}

	/** Jetons -> texte SQL (MySQL : chaînes \-échappées non, standard '') */
	private static function render(array $t) {
		$out = '';
		foreach ($t as $tok) {
			switch ($tok[0]) {
				case 'qid': $s = '`' . str_replace('`', '``', $tok[1]) . '`'; break;
				case 'str': $s = self::quote($tok[1]); break;
				case 'cmt': $s = '/*' . $tok[1] . '*/'; break;
				default:    $s = $tok[1];
			}
			if ($out !== '' && !($tok[0] === 'p' && in_array($tok[1], array(',', ')', '.'), true)) && substr($out, -1) !== '(' && substr($out, -1) !== '.') $out .= ' ';
			$out .= $s;
		}
		return $out;
	}

	// -------------------------------------------------------------------
	// Modèle d'une table
	// -------------------------------------------------------------------

	/**
	 * Colonne : name, type (« int(11) », « enum('a','b') »), unsigned, null
	 * (accepte NULL), default (SQL brut ou null), autoinc, onupdate, comment.
	 */
	private static function parseColumn(array $t) {
		$name = array_shift($t);
		if (!$name || !in_array($name[0], array('qid', 'id'), true)) throw new RuntimeException('Colonne illisible');
		$col = array('name' => $name[1], 'type' => '', 'unsigned' => false, 'null' => true, 'default' => null,
			'autoinc' => false, 'onupdate' => false, 'comment' => null, 'pk' => false, 'unique' => false);
		$i = 0;
		$n = count($t);
		// Type : mots (« double precision ») puis arguments
		$type = array();
		while ($i < $n && $t[$i][0] === 'id' && !self::isColumnKeyword($t[$i][1])) $type[] = strtolower($t[$i++][1]);
		if ($i < $n && $t[$i] === array('p', '(')) {
			list($inner, $i) = self::group($t, $i);
			$type[count($type) - 1] .= '(' . self::render($inner) . ')';
		}
		$col['type'] = implode(' ', $type);
		for (; $i < $n; $i++) {
			$tok = $t[$i];
			$next = $t[$i + 1] ?? null;
			if ($tok[0] === 'cmt') {
				// Type MySQL d'origine gardé dans la création SQLite
				if (strpos($tok[1], 'my:') === 0) {
					$h = json_decode(substr($tok[1], 3), true);
					if (is_array($h)) {
						if (isset($h['t'])) $col['type'] = (string) $h['t'];
						if (!empty($h['u'])) $col['unsigned'] = true;
						if (!empty($h['o'])) $col['onupdate'] = true;
						if (isset($h['c'])) $col['comment'] = (string) $h['c'];
					}
				}
				continue;
			}
			if (self::kw($tok, 'UNSIGNED')) { $col['unsigned'] = true; continue; }
			if (self::kw($tok, 'SIGNED') || self::kw($tok, 'ZEROFILL') || self::kw($tok, 'BINARY')) continue;
			if (self::kw($tok, 'NOT') && self::kw($next, 'NULL')) { $col['null'] = false; $i++; continue; }
			if (self::kw($tok, 'NULL')) { $col['null'] = true; continue; }
			if ((self::kw($tok, 'CHARACTER') && self::kw($next, 'SET'))) { $i += 2; continue; }
			if (self::kw($tok, 'CHARSET') || self::kw($tok, 'COLLATE')) { $i++; continue; }
			if (self::kw($tok, 'AUTO_INCREMENT') || self::kw($tok, 'AUTOINCREMENT')) { $col['autoinc'] = true; continue; }
			if (self::kw($tok, 'PRIMARY') && self::kw($next, 'KEY')) { $col['pk'] = true; $i++; continue; }
			if (self::kw($tok, 'UNIQUE')) { $col['unique'] = true; if (self::kw($next, 'KEY')) $i++; continue; }
			if (self::kw($tok, 'COMMENT') && $next && $next[0] === 'str') { $col['comment'] = $next[1]; $i++; continue; }
			if (self::kw($tok, 'ON') && self::kw($next, 'UPDATE')) {
				$col['onupdate'] = true;
				$i += 2;
				if (($t[$i + 1] ?? null) === array('p', '(')) $i += 2;
				continue;
			}
			if (self::kw($tok, 'CHECK') && $next === array('p', '(')) { list(, $j) = self::group($t, $i + 1); $i = $j - 1; continue; }
			if (self::kw($tok, 'DEFAULT')) {
				$i++;
				$v = $t[$i] ?? null;
				if ($v === null) break;
				if ($v === array('p', '(')) {
					list($inner, $j) = self::group($t, $i);
					$raw = self::render($inner);
					$col['default'] = preg_match("/^datetime\s*\(\s*'now'/i", $raw) ? 'CURRENT_TIMESTAMP' : $raw;
					$i = $j - 1;
				} elseif ($v === array('p', '-') && ($t[$i + 1][0] ?? '') === 'num') {
					$col['default'] = '-' . $t[++$i][1];
				} elseif ($v[0] === 'str') {
					$col['default'] = self::quote($v[1]);
				} elseif ($v[0] === 'num') {
					$col['default'] = $v[1];
				} else {
					$w = strtoupper($v[1]);
					$col['default'] = in_array($w, array('CURRENT_TIMESTAMP', 'NOW', 'LOCALTIMESTAMP'), true) ? 'CURRENT_TIMESTAMP' : ($w === 'NULL' ? 'NULL' : $v[1]);
					if (($t[$i + 1] ?? null) === array('p', '(')) $i += 2; // current_timestamp()
				}
				continue;
			}
		}
		if ($col['type'] === '') throw new RuntimeException('Type absent : ' . $col['name']);
		return $col;
	}

	private static function isColumnKeyword($w) {
		return in_array(strtoupper($w), array('UNSIGNED', 'SIGNED', 'ZEROFILL', 'NOT', 'NULL', 'DEFAULT', 'AUTO_INCREMENT', 'AUTOINCREMENT',
			'PRIMARY', 'UNIQUE', 'COMMENT', 'ON', 'CHARACTER', 'CHARSET', 'COLLATE', 'CHECK', 'BINARY', 'REFERENCES', 'GENERATED'), true);
	}

	/** (`a`, `b`(191)) -> array('a', 'b') */
	private static function keyColumns(array $inner) {
		$cols = array();
		foreach (self::commaSplit($inner) as $part) {
			if ($part && in_array($part[0][0], array('qid', 'id'), true)) $cols[] = $part[0][1];
		}
		return $cols;
	}

	/** Nom d'index SQLite (« table__nom ») -> nom MySQL */
	private static function indexName($table, $name) {
		if (strpos($name, $table . '__') === 0) return substr($name, strlen($table) + 2);
		$p = strpos($name, '__');
		return $p === false ? $name : substr($name, $p + 2);
	}

	/**
	 * Élément de création (colonne, clé) appliqué au modèle
	 * @return bool reconnu
	 */
	private static function applyElement(array &$m, array $part) {
		$a = $part[0] ?? null;
		$b = $part[1] ?? null;
		if (self::kw($a, 'CONSTRAINT')) { array_splice($part, 0, $b && in_array($b[0], array('qid', 'id'), true) && !self::kw($b, 'PRIMARY') && !self::kw($b, 'UNIQUE') ? 2 : 1); $a = $part[0] ?? null; $b = $part[1] ?? null; }
		if (self::kw($a, 'PRIMARY') && self::kw($b, 'KEY')) {
			$p = array_search(array('p', '('), $part, true);
			list($inner) = self::group($part, $p);
			$m['pk'] = self::keyColumns($inner);
			return true;
		}
		if (self::kw($a, 'UNIQUE') || self::kw($a, 'KEY') || self::kw($a, 'INDEX') || self::kw($a, 'FULLTEXT') || self::kw($a, 'SPATIAL')) {
			$kind = self::kw($a, 'UNIQUE') ? 'unique' : (self::kw($a, 'FULLTEXT') ? 'fulltext' : 'index');
			$p = array_search(array('p', '('), $part, true);
			$name = null;
			for ($i = 1; $i < $p; $i++) if (in_array($part[$i][0], array('qid'), true) || ($part[$i][0] === 'id' && !in_array(strtoupper($part[$i][1]), array('KEY', 'INDEX'), true))) $name = $part[$i][1];
			list($inner) = self::group($part, $p);
			$cols = self::keyColumns($inner);
			if ($name === null) $name = $cols[0];
			$m[$kind][$name] = $cols;
			return true;
		}
		if (self::kw($a, 'FOREIGN') || self::kw($a, 'CHECK')) return true; // non repris
		$col = self::parseColumn($part);
		if ($col['pk']) $m['pk'] = array($col['name']);
		if ($col['unique']) $m['unique'][$col['name']] = array($col['name']);
		unset($col['pk'], $col['unique']);
		$m['columns'][$col['name']] = $col;
		return true;
	}

	private static function emptyModel($name) {
		return array('name' => $name, 'columns' => array(), 'pk' => array(), 'unique' => array(), 'index' => array(), 'fulltext' => array(), 'comment' => '');
	}

	/** CREATE TABLE (MySQL, ou SQLite créé par ce fichier) -> modèle */
	public static function parseCreate($sql, $mysql = true) {
		$t = self::tokens($sql, $mysql);
		$i = 0;
		while ($i < count($t) && !(self::kw($t[$i], 'TABLE'))) $i++;
		$i++;
		if (self::kw($t[$i] ?? null, 'IF')) $i += 3;
		$name = $t[$i++] ?? null;
		if (!$name || !in_array($name[0], array('qid', 'id', 'str'), true)) throw new RuntimeException('CREATE TABLE illisible');
		$m = self::emptyModel($name[1]);
		list($inner, $j) = self::group($t, $i);
		foreach (self::commaSplit($inner) as $part) self::applyElement($m, $part);
		foreach ($m['pk'] as $c) if (isset($m['columns'][$c])) $m['columns'][$c]['null'] = false;
		for (; $j < count($t); $j++) {
			if (self::kw($t[$j], 'COMMENT')) {
				if (($t[$j + 1] ?? null) === array('p', '=')) $j++;
				if (($t[$j + 1][0] ?? '') === 'str') $m['comment'] = $t[++$j][1];
			}
		}
		return $m;
	}

	/**
	 * ALTER TABLE -> array(table, opérations). Opérations : add (col, after),
	 * modify (col), change (from, col), drop (col), pk (cols), droppk,
	 * unique|index|fulltext (name, cols), dropindex (name), rename (to).
	 */
	public static function parseAlter($sql) {
		$t = self::tokens($sql);
		$i = 2;
		if (self::kw($t[$i] ?? null, 'IGNORE')) $i++;
		$table = $t[$i++] ?? null;
		if (!$table || !in_array($table[0], array('qid', 'id'), true)) throw new RuntimeException('ALTER TABLE illisible');
		$ops = array();
		foreach (self::commaSplit(array_slice($t, $i)) as $part) {
			$verb = strtoupper($part[0][1] ?? '');
			array_shift($part);
			if (self::kw($part[0] ?? null, 'COLUMN')) array_shift($part);
			if ($verb === 'ADD') {
				$tmp = self::emptyModel($table[1]);
				$after = null;
				$n = count($part);
				if ($n > 2 && self::kw($part[$n - 2], 'AFTER')) { $after = $part[$n - 1][1]; array_splice($part, -2); }
				elseif ($n > 1 && self::kw($part[$n - 1], 'FIRST')) array_pop($part);
				self::applyElement($tmp, $part);
				if ($tmp['columns']) {
					$col = reset($tmp['columns']);
					$ops[] = array('add', $col, $after);
					if ($tmp['pk']) $ops[] = array('pk', $tmp['pk']);
				}
				elseif ($tmp['pk']) $ops[] = array('pk', $tmp['pk']);
				foreach (array('unique', 'index', 'fulltext') as $k) foreach ($tmp[$k] as $nm => $cols) $ops[] = array($k, $nm, $cols);
			} elseif ($verb === 'MODIFY') {
				$ops[] = array('modify', self::parseColumn($part));
			} elseif ($verb === 'CHANGE') {
				$from = array_shift($part);
				$ops[] = array('change', $from[1], self::parseColumn($part));
			} elseif ($verb === 'DROP') {
				if (self::kw($part[0] ?? null, 'PRIMARY')) $ops[] = array('droppk');
				elseif (self::kw($part[0] ?? null, 'INDEX') || self::kw($part[0] ?? null, 'KEY')) $ops[] = array('dropindex', $part[1][1]);
				else $ops[] = array('drop', $part[0][1]);
			} elseif ($verb === 'RENAME') {
				if (self::kw($part[0] ?? null, 'TO') || self::kw($part[0] ?? null, 'AS')) array_shift($part);
				$ops[] = array('rename', $part[0][1]);
			}
			// AUTO_INCREMENT=n, ENGINE=, CONVERT TO CHARACTER SET... : sans objet
		}
		return array($table[1], $ops);
	}

	/** Opérations d'ALTER appliquées au modèle */
	public static function applyOps(array $m, array $ops) {
		foreach ($ops as $op) {
			switch ($op[0]) {
				case 'add':
					$cols = array();
					foreach ($m['columns'] as $k => $c) {
						$cols[$k] = $c;
						if ($op[2] !== null && $k === $op[2]) $cols[$op[1]['name']] = $op[1];
					}
					if (!isset($cols[$op[1]['name']])) $cols[$op[1]['name']] = $op[1];
					$m['columns'] = $cols;
					break;
				case 'modify':
					if (!isset($m['columns'][$op[1]['name']])) throw new RuntimeException('Colonne absente : ' . $op[1]['name']);
					$m['columns'][$op[1]['name']] = $op[1];
					break;
				case 'change':
					$cols = array();
					foreach ($m['columns'] as $k => $c) $cols[$k === $op[1] ? $op[2]['name'] : $k] = $k === $op[1] ? $op[2] : $c;
					$m['columns'] = $cols;
					foreach (array('pk') as $k) $m[$k] = array_map(function ($c) use ($op) { return $c === $op[1] ? $op[2]['name'] : $c; }, $m[$k]);
					foreach (array('unique', 'index', 'fulltext') as $k) foreach ($m[$k] as $nm => $cs) $m[$k][$nm] = array_map(function ($c) use ($op) { return $c === $op[1] ? $op[2]['name'] : $c; }, $cs);
					break;
				case 'drop':
					unset($m['columns'][$op[1]]);
					$m['pk'] = array_values(array_diff($m['pk'], array($op[1])));
					foreach (array('unique', 'index', 'fulltext') as $k) foreach ($m[$k] as $nm => $cs) if (in_array($op[1], $cs, true)) unset($m[$k][$nm]);
					break;
				case 'pk':       $m['pk'] = $op[1]; break;
				case 'droppk':   $m['pk'] = array(); break;
				case 'unique':
				case 'index':
				case 'fulltext': $m[$op[0]][$op[1]] = $op[2]; break;
				case 'dropindex':
					foreach (array('unique', 'index', 'fulltext') as $k) unset($m[$k][$op[1]]);
					break;
				case 'rename':   $m['name'] = $op[1]; break;
			}
		}
		return $m;
	}

	// -------------------------------------------------------------------
	// Écriture
	// -------------------------------------------------------------------

	private static function isText($type) {
		return (bool) preg_match('/^(var)?char\b|text\b|^enum\b|^set\b/i', $type);
	}

	private static function q($name) {
		return '`' . str_replace('`', '``', $name) . '`';
	}

	/** Colonne auto-incrémentée seule en clé primaire (INTEGER PRIMARY KEY sous SQLite) */
	private static function rowidColumn(array $m) {
		if (count($m['pk']) !== 1) return null;
		$c = $m['columns'][$m['pk'][0]] ?? null;
		return ($c && $c['autoinc']) ? $c['name'] : null;
	}

	/** Modèle -> instructions de création SQLite (table, index, déclencheurs) */
	public static function sqliteCreate(array $m, $ifNotExists = false) {
		$t = $m['name'];
		$rowid = self::rowidColumn($m);
		$lines = array();
		$triggers = array();
		foreach ($m['columns'] as $c) {
			$hint = array();
			$type = $c['type'];
			if (!preg_match('/^[a-z ]+(\(\s*\d+\s*(,\s*\d+\s*)?\))?$/i', $type)) { $hint['t'] = $type; $type = 'text'; }
			if ($c['unsigned']) $hint['u'] = 1;
			if ($c['onupdate']) $hint['o'] = 1;
			if ($c['comment'] !== null && $c['comment'] !== '') $hint['c'] = $c['comment'];
			if ($c['name'] === $rowid) {
				$hint['t'] = $c['type'];
				$line = self::q($c['name']) . ' INTEGER PRIMARY KEY AUTOINCREMENT';
			} else {
				$line = self::q($c['name']) . ' ' . $type;
				if (!$c['null']) $line .= ' NOT NULL';
				if ($c['default'] !== null) {
					$line .= ' DEFAULT ' . ($c['default'] === 'CURRENT_TIMESTAMP' ? "(datetime('now','localtime'))" : $c['default']);
				}
				if (self::isText($c['type'])) $line .= ' COLLATE NOCASE';
				if (preg_match("/^enum\((.*)\)$/is", $c['type'], $e)) $line .= ' CHECK (' . self::q($c['name']) . ' IN (' . $e[1] . ')' . ($c['null'] ? ' OR ' . self::q($c['name']) . ' IS NULL' : '') . ')';
			}
			if ($hint) $line .= ' /*my:' . str_replace('*/', '* /', json_encode($hint, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) . '*/';
			$lines[] = $line;
			if ($c['onupdate']) {
				$triggers[] = 'CREATE TRIGGER IF NOT EXISTS ' . self::q($t . '__' . $c['name'] . '_onupdate') . ' AFTER UPDATE ON ' . self::q($t)
					. ' FOR EACH ROW WHEN NEW.' . self::q($c['name']) . ' IS OLD.' . self::q($c['name'])
					. ' BEGIN UPDATE ' . self::q($t) . ' SET ' . self::q($c['name']) . " = datetime('now','localtime') WHERE rowid = NEW.rowid; END";
			}
		}
		if ($m['pk'] && $rowid === null) $lines[] = 'PRIMARY KEY (' . implode(', ', array_map(array(__CLASS__, 'q'), $m['pk'])) . ')';
		$out = array('CREATE TABLE ' . ($ifNotExists ? 'IF NOT EXISTS ' : '') . self::q($t) . " (\n  " . implode(",\n  ", $lines) . "\n)");
		foreach (array('unique' => 'CREATE UNIQUE INDEX', 'index' => 'CREATE INDEX', 'fulltext' => 'CREATE INDEX') as $k => $verb) {
			foreach ($m[$k] as $name => $cols) {
				$out[] = $verb . ' IF NOT EXISTS ' . self::q($t . '__' . $name) . ' ON ' . self::q($t) . ' (' . implode(', ', array_map(array(__CLASS__, 'q'), $cols)) . ')';
			}
		}
		return array_merge($out, $triggers);
	}

	/** Modèle -> CREATE TABLE MySQL */
	public static function mysqlCreate(array $m, $ifNotExists = false) {
		$lines = array();
		foreach ($m['columns'] as $c) {
			$line = self::q($c['name']) . ' ' . $c['type'] . ($c['unsigned'] ? ' unsigned' : '') . ($c['null'] ? ' NULL' : ' NOT NULL');
			if ($c['default'] !== null && !$c['autoinc']) $line .= ' DEFAULT ' . $c['default'];
			if ($c['autoinc']) $line .= ' AUTO_INCREMENT';
			if ($c['onupdate']) $line .= ' ON UPDATE CURRENT_TIMESTAMP';
			if ($c['comment'] !== null && $c['comment'] !== '') $line .= ' COMMENT ' . self::mysqlQuote($c['comment']);
			$lines[] = $line;
		}
		if ($m['pk']) $lines[] = 'PRIMARY KEY (' . implode(', ', array_map(array(__CLASS__, 'q'), $m['pk'])) . ')';
		foreach (array('unique' => 'UNIQUE KEY', 'index' => 'KEY', 'fulltext' => 'FULLTEXT KEY') as $k => $verb) {
			foreach ($m[$k] as $name => $cols) $lines[] = $verb . ' ' . self::q($name) . ' (' . implode(', ', array_map(array(__CLASS__, 'q'), $cols)) . ')';
		}
		return 'CREATE TABLE ' . ($ifNotExists ? 'IF NOT EXISTS ' : '') . self::q($m['name']) . " (\n  " . implode(",\n  ", $lines) . "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
			. ($m['comment'] !== '' ? ' COMMENT=' . self::mysqlQuote($m['comment']) : '');
	}

	private static function mysqlQuote($v) {
		return "'" . str_replace(array('\\', "'"), array('\\\\', "''"), (string) $v) . "'";
	}

	// -------------------------------------------------------------------
	// Base réelle
	// -------------------------------------------------------------------

	/** Modèle d'une table existante (nom complet, préfixe compris), ou null */
	public static function describe(sbdb $db, $table) {
		if ($db->driver() === 'sqlite') {
			$sql = $db->value("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ?", array($table));
			if (!$sql) return null;
			$m = self::parseCreate($sql, false);
			$m['name'] = $table;
			foreach ($db->all("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND sql IS NOT NULL", array($table)) ?: array() as $ix) {
				$t = self::tokens($ix['sql'], false);
				$p = array_search(array('p', '('), $t, true);
				list($inner) = self::group($t, $p);
				$kind = self::kw($t[1] ?? null, 'UNIQUE') ? 'unique' : 'index';
				$m[$kind][self::indexName($table, $ix['name'])] = self::keyColumns($inner);
			}
			return $m;
		}
		$row = $db->row('SHOW CREATE TABLE ' . self::q($table));
		if (!$row) return null;
		$m = self::parseCreate(array_values($row)[1]);
		$m['name'] = $table;
		return $m;
	}

	/** Table (nom complet) présente ? */
	public static function hasTable(sbdb $db, $table) {
		return in_array($table, $db->tables($table), true);
	}

	/**
	 * Exécute une instruction brute, exception si échec. Jamais par run() :
	 * il remplacerait les {nom} jusque dans les chaînes ({lang} d'une page).
	 */
	public static function exec(sbdb $db, $sql) {
		try {
			$db->pdo()->exec($sql);
		} catch (PDOException $e) {
			$msg = isset($e->errorInfo[2]) && $e->errorInfo[2] !== '' ? (string) $e->errorInfo[2] : $e->getMessage();
			throw new RuntimeException('Requête en échec (' . $msg . ') : ' . substr($sql, 0, 200));
		}
	}

	/**
	 * ALTER TABLE MySQL rejoué sous SQLite : colonnes et index ajoutés
	 * directement, le reste par reconstruction de la table (création d'une
	 * copie au nouveau schéma, recopie des lignes, échange).
	 */
	public static function sqliteAlter(sbdb $db, $alterSql) {
		list($table, $ops) = self::parseAlter($alterSql);
		$m = self::describe($db, $table);
		if (!$m) throw new RuntimeException('Table absente : ' . $table);
		$simple = true;
		foreach ($ops as $op) {
			if (in_array($op[0], array('unique', 'index', 'fulltext', 'dropindex'), true)) continue;
			if ($op[0] === 'add' && !$op[1]['autoinc'] && ($op[1]['null'] || $op[1]['default'] !== null) && $op[1]['default'] !== 'CURRENT_TIMESTAMP') continue;
			$simple = false;
		}
		$new = self::applyOps($m, $ops);
		if (!$simple) {
			self::rebuild($db, $m, $new, $ops);
			return;
		}
		foreach ($ops as $op) {
			if ($op[0] === 'add') {
				$one = self::sqliteCreate(array('name' => $table, 'columns' => array($op[1]['name'] => $op[1])) + self::emptyModel($table));
				preg_match('/\(\n  (.*)\n\)$/s', $one[0], $mm);
				self::exec($db, 'ALTER TABLE ' . self::q($table) . ' ADD COLUMN ' . $mm[1]);
				foreach (array_slice($one, 1) as $s) self::exec($db, $s);
			} elseif ($op[0] === 'dropindex') {
				self::exec($db, 'DROP INDEX IF EXISTS ' . self::q($table . '__' . $op[1]));
			} else {
				$only = array('name' => $table, 'columns' => array()) + self::emptyModel($table);
				$only[$op[0]] = array($op[1] => $op[2]);
				foreach (array_slice(self::sqliteCreate($only), 1) as $s) self::exec($db, $s);
			}
		}
	}

	/** Reconstruction SQLite d'une table vers le modèle $new (lignes gardées) */
	public static function rebuild(sbdb $db, array $old, array $new, array $ops = array()) {
		$from = $old['name'];
		$to = $new['name'];
		$tmp = $to . '__sbnew';
		// Colonnes reprises (renommées par CHANGE)
		$map = array();
		foreach (array_keys($old['columns']) as $c) $map[$c] = $c;
		foreach ($ops as $op) if ($op[0] === 'change' && isset($map[$op[1]])) $map[$op[1]] = $op[2]['name'];
		$copy = array();
		foreach ($map as $src => $dst) if (isset($new['columns'][$dst])) $copy[$src] = $dst;
		$create = self::sqliteCreate(array('name' => $tmp) + $new);
		$db->pdo()->exec('PRAGMA foreign_keys = OFF');
		try {
			$db->transaction(function () use ($db, $create, $tmp, $from, $to, $copy, $new) {
				self::exec($db, 'DROP TABLE IF EXISTS ' . self::q($tmp));
				self::exec($db, $create[0]);
				if ($copy) {
					self::exec($db, 'INSERT INTO ' . self::q($tmp) . ' (' . implode(', ', array_map(array(__CLASS__, 'q'), $copy)) . ') SELECT '
						. implode(', ', array_map(array(__CLASS__, 'q'), array_keys($copy))) . ' FROM ' . self::q($from));
				}
				self::exec($db, 'DROP TABLE ' . self::q($from));
				self::exec($db, 'ALTER TABLE ' . self::q($tmp) . ' RENAME TO ' . self::q($to));
				foreach (array_slice(self::sqliteCreate($new), 1) as $s) self::exec($db, $s);
			});
		} finally {
			$db->pdo()->exec('PRAGMA foreign_keys = ON');
		}
	}

	/**
	 * Instruction de données (INSERT, UPDATE...) écrite pour MySQL -> SQLite :
	 * chaînes réécrites (échappements \), INSERT IGNORE -> INSERT OR IGNORE,
	 * TRUNCATE -> DELETE.
	 */
	public static function sqliteDml($sql) {
		$out = '';
		$n = strlen($sql);
		for ($i = 0; $i < $n; ) {
			$c = $sql[$i];
			if ($c === "'" || $c === '"') {
				$j = self::skipQuoted($sql, $i, true);
				$out .= self::quote(self::unquote(substr($sql, $i, $j - $i), true));
				$i = $j;
				continue;
			}
			if ($c === '`') {
				$j = self::skipQuoted($sql, $i, false);
				$out .= substr($sql, $i, $j - $i);
				$i = $j;
				continue;
			}
			$out .= $c;
			$i++;
		}
		$out = preg_replace('/^\s*INSERT\s+IGNORE\s+INTO\b/i', 'INSERT OR IGNORE INTO', $out);
		$out = preg_replace('/^\s*TRUNCATE\s+(TABLE\s+)?/i', 'DELETE FROM ', $out);
		return $out;
	}

	/** Instruction sans objet sous SQLite (réglages de session MySQL) */
	private static function sqliteSkip($stmt) {
		return (bool) preg_match('/^(\/\*!|SET\s|LOCK\s+TABLES|UNLOCK\s+TABLES|START\s+TRANSACTION|BEGIN\b|COMMIT\b|USE\s|OPTIMIZE\s|ANALYZE\s)/i', ltrim($stmt));
	}

	/**
	 * Exécute un script SQL écrit pour MySQL sur la base, quel que soit son
	 * type. $only : rappel fonction(type, table) -> bool pour filtrer les
	 * instructions (type : create, alter, drop, data, other).
	 * @return array tables créées
	 */
	public static function runScript(sbdb $db, $sql, ?callable $only = null) {
		$stmts = self::split($sql);
		$created = $kept = array();
		if ($db->driver() !== 'sqlite') {
			foreach ($stmts as $s) {
				$kind = self::kind($s, $table);
				if ($only && !$only($kind, $table)) continue;
				// Table déjà là (CREATE ... IF NOT EXISTS) : ni recréée ni retouchée
				if ($kind === 'alter' && isset($kept[$table])) continue;
				if ($kind === 'create' && preg_match('/^CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS/i', $s) && self::hasTable($db, $table)) { $kept[$table] = true; continue; }
				self::exec($db, $s);
				if ($kind === 'create') $created[$table] = true;
			}
			return array_keys($created);
		}
		// SQLite : chaque CREATE TABLE réunit les ALTER TABLE du script qui le suivent
		$models = array();
		foreach ($stmts as $k => $s) {
			$kind = self::kind($s, $table);
			if ($kind === 'create') $models[$table] = array('model' => self::parseCreate($s), 'at' => $k, 'ine' => (bool) preg_match('/^CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS/i', $s));
		}
		$skip = $kept = array();
		foreach ($stmts as $k => $s) {
			$kind = self::kind($s, $table);
			if ($kind === 'alter' && isset($models[$table]) && $models[$table]['at'] < $k) {
				list(, $ops) = self::parseAlter($s);
				$models[$table]['model'] = self::applyOps($models[$table]['model'], $ops);
				$skip[$k] = true;
			}
		}
		foreach ($stmts as $k => $s) {
			$kind = self::kind($s, $table);
			if (isset($skip[$k])) continue;
			if ($only && !$only($kind, $table)) continue;
			if (self::sqliteSkip($s)) continue;
			if ($kind === 'alter' && isset($kept[$table])) continue;
			switch ($kind) {
				case 'create':
					if ($models[$table]['ine'] && self::hasTable($db, $table)) { $kept[$table] = true; break; }
					foreach (self::sqliteCreate($models[$table]['model']) as $c) self::exec($db, $c);
					$created[$table] = true;
					break;
				case 'alter':
					self::sqliteAlter($db, $s);
					break;
				case 'rename':
					if (preg_match('/^RENAME\s+TABLE\s+`?([A-Za-z0-9_]+)`?\s+TO\s+`?([A-Za-z0-9_]+)`?/i', $s, $mm)) {
						self::exec($db, 'ALTER TABLE ' . self::q($mm[1]) . ' RENAME TO ' . self::q($mm[2]));
					}
					break;
				case 'drop':
					self::exec($db, preg_replace('/\s+(CASCADE|RESTRICT)\s*$/i', '', $s));
					break;
				default:
					self::exec($db, self::sqliteDml($s));
			}
		}
		return array_keys($created);
	}

	/** Nature d'une instruction : create, alter, drop, rename, data, other ; $table renseignée si connue */
	public static function kind($stmt, &$table = null) {
		$table = null;
		$s = ltrim($stmt);
		if (preg_match('/^CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?([A-Za-z0-9_]+)`?/i', $s, $m)) { $table = $m[1]; return 'create'; }
		if (preg_match('/^ALTER\s+(?:IGNORE\s+)?TABLE\s+`?([A-Za-z0-9_]+)`?/i', $s, $m)) { $table = $m[1]; return 'alter'; }
		if (preg_match('/^DROP\s+TABLE\s+(?:IF\s+EXISTS\s+)?`?([A-Za-z0-9_]+)`?/i', $s, $m)) { $table = $m[1]; return 'drop'; }
		if (preg_match('/^RENAME\s+TABLE\s+`?([A-Za-z0-9_]+)`?/i', $s, $m)) { $table = $m[1]; return 'rename'; }
		if (preg_match('/^(?:INSERT|REPLACE)\s+(?:IGNORE\s+)?(?:INTO\s+)?`?([A-Za-z0-9_]+)`?/i', $s, $m)) { $table = $m[1]; return 'data'; }
		if (preg_match('/^(?:UPDATE|DELETE\s+FROM|TRUNCATE(?:\s+TABLE)?)\s+`?([A-Za-z0-9_]+)`?/i', $s, $m)) { $table = $m[1]; return 'data'; }
		return 'other';
	}

	// -------------------------------------------------------------------
	// Copie d'une base vers une autre (changement de type de base)
	// -------------------------------------------------------------------

	/**
	 * Copie les tables $tables (noms complets) de $from vers $to : schéma
	 * traduit, lignes recopiées par lots, nombre de lignes vérifié. Les
	 * tables de même nom dans $to sont remplacées. $from n'est jamais modifié.
	 * @param callable|null $progress fonction(texte)
	 * @return array table => nombre de lignes
	 */
	public static function copyTables(sbdb $from, sbdb $to, array $tables, $progress = null) {
		$counts = array();
		foreach ($tables as $t) {
			$m = self::describe($from, $t);
			if (!$m) throw new RuntimeException('Table illisible : ' . $t);
			if ($progress) call_user_func($progress, 'Table ' . $t);
			self::exec($to, 'DROP TABLE IF EXISTS ' . self::q($t));
			if ($to->driver() === 'sqlite') {
				foreach (self::sqliteCreate($m) as $s) self::exec($to, $s);
			} else {
				self::exec($to, self::mysqlCreate($m));
			}
			$cols = array_keys($m['columns']);
			$insert = 'INSERT INTO ' . self::q($t) . ' (' . implode(', ', array_map(array(__CLASS__, 'q'), $cols)) . ') VALUES (' . implode(', ', array_fill(0, count($cols), '?')) . ')';
			$st = $from->run('SELECT * FROM ' . self::q($t));
			if ($st === false) throw new RuntimeException('Lecture impossible : ' . $t . ' (' . $from->error() . ')');
			$n = 0;
			$to->pdo()->beginTransaction();
			try {
				$ins = $to->pdo()->prepare($insert);
				while (($row = $st->fetch(PDO::FETCH_ASSOC)) !== false) {
					$vals = array();
					foreach ($cols as $c) $vals[] = $row[$c] ?? null;
					$ins->execute($vals);
					if (++$n % 1000 === 0) { $to->pdo()->commit(); $to->pdo()->beginTransaction(); }
				}
				$to->pdo()->commit();
			} catch (Throwable $e) {
				if ($to->pdo()->inTransaction()) $to->pdo()->rollBack();
				throw new RuntimeException('Copie de ' . $t . ' impossible : ' . $e->getMessage());
			}
			$check = (int) $to->value('SELECT COUNT(*) FROM ' . self::q($t));
			$source = (int) $from->value('SELECT COUNT(*) FROM ' . self::q($t));
			if ($check !== $source || $check !== $n) throw new RuntimeException('Vérification de ' . $t . ' en échec : ' . $source . ' lignes, ' . $check . ' copiées');
			$counts[$t] = $n;
		}
		return $counts;
	}
}
