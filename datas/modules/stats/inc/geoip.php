<?php
/**
 * Module stats : pays des visiteurs (lot 6).
 *
 * Base DB-IP « IP to Country Lite » (licence CC BY 4.0, mention « IP
 * Geolocation by DB-IP » obligatoire sur les pages qui affichent les pays),
 * au format MMDB, téléchargée depuis les Réglages du module après
 * acceptation de la licence, puis interrogée en local : aucun appel externe
 * par visite. Derrière Cloudflare, l'en-tête CF-IPCountry passe d'abord.
 *
 * Lecteur MMDB (format MaxMind DB 2) en PHP, sans dépendance : arbre de
 * recherche parcouru bit à bit dans le fichier (fseek), données décodées
 * pour le seul enregistrement trouvé.
 *
 * L'adresse IP sert à la recherche et n'est jamais enregistrée.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH') && !defined('SBUIADMIN_PATH')) die('Are you crazy!');

/** Dossier de la base (protégé, créé au besoin), '' s'il n'est pas inscriptible */
function stats_geoip_dir($create = false) {
	$dir = defined('STATS_GEOIP_DIR') ? STATS_GEOIP_DIR : dirname(__DIR__) . DIRECTORY_SEPARATOR . 'geoip';
	if ($create && !is_dir($dir)) @mkdir($dir, 0755, true);
	if ($create && is_dir($dir)) {
		if (!is_file($dir . '/.htaccess')) @file_put_contents($dir . '/.htaccess', "Require all denied\n");
		if (!is_file($dir . '/index.html')) @file_put_contents($dir . '/index.html', '');
	}
	return is_dir($dir) ? $dir : '';
}

/** Chemin de la base installée */
function stats_geoip_file() {
	return (defined('STATS_GEOIP_DIR') ? STATS_GEOIP_DIR : dirname(__DIR__) . DIRECTORY_SEPARATOR . 'geoip') . DIRECTORY_SEPARATOR . 'dbip-country-lite.mmdb';
}

// -----------------------------------------------------------------------
// Lecteur MMDB
// -----------------------------------------------------------------------

class StatsMmdb {
	private $fh;
	private $size;
	public $meta = array();
	private $nodeCount;
	private $recordSize;
	private $nodeBytes;
	private $treeSize;
	private $dataStart;
	private $ipv4Start = null;

	/** @throws RuntimeException fichier illisible ou qui n'est pas une base MMDB 2 */
	public function __construct($file) {
		$this->fh = @fopen($file, 'rb');
		if (!$this->fh) throw new RuntimeException('Base illisible');
		$this->size = (int) fstat($this->fh)['size'];
		if ($this->size < 64) throw new RuntimeException('Base vide ou tronquée');
		// Métadonnées : après le dernier marqueur, dans les 128 derniers Ko
		$tail = min($this->size, 131072);
		fseek($this->fh, $this->size - $tail);
		$buf = (string) fread($this->fh, $tail);
		$pos = strrpos($buf, "\xAB\xCD\xEFMaxMind.com");
		if ($pos === false) throw new RuntimeException('Pas une base MMDB');
		$metaStart = $this->size - $tail + $pos + 14;
		list($meta) = $this->decode($metaStart, $metaStart);
		if (!is_array($meta)) throw new RuntimeException('Métadonnées illisibles');
		$this->meta = $meta;
		$this->nodeCount = (int) ($meta['node_count'] ?? 0);
		$this->recordSize = (int) ($meta['record_size'] ?? 0);
		if ((int) ($meta['binary_format_major_version'] ?? 0) !== 2 || !in_array($this->recordSize, array(24, 28, 32), true) || $this->nodeCount <= 0) {
			throw new RuntimeException('Format MMDB non pris en charge');
		}
		$this->nodeBytes = $this->recordSize / 4;
		$this->treeSize = $this->nodeBytes * $this->nodeCount;
		$this->dataStart = $this->treeSize + 16;
		if ($this->dataStart > $this->size) throw new RuntimeException('Base tronquée');
	}

	public function __destruct() {
		if ($this->fh) fclose($this->fh);
	}

	private function read($offset, $len) {
		if ($len <= 0) return '';
		if ($offset < 0 || $offset + $len > $this->size) throw new RuntimeException('Lecture hors du fichier');
		fseek($this->fh, $offset);
		$s = (string) fread($this->fh, $len);
		if (strlen($s) !== $len) throw new RuntimeException('Lecture incomplète');
		return $s;
	}

	/** Les deux enregistrements d'un nœud de l'arbre */
	private function node($n) {
		$b = $this->read($n * $this->nodeBytes, $this->nodeBytes);
		switch ($this->recordSize) {
			case 24:
				return array(unpack('N', "\0" . substr($b, 0, 3))[1], unpack('N', "\0" . substr($b, 3, 3))[1]);
			case 28:
				$mid = ord($b[3]);
				return array((($mid & 0xF0) << 20) | unpack('N', "\0" . substr($b, 0, 3))[1], (($mid & 0x0F) << 24) | unpack('N', "\0" . substr($b, 4, 3))[1]);
			default:
				return array(unpack('N', substr($b, 0, 4))[1], unpack('N', substr($b, 4, 4))[1]);
		}
	}

	/**
	 * Enregistrement d'une adresse, ou null (non trouvée, invalide)
	 * @return array|null
	 */
	public function get($ip) {
		$bin = filter_var($ip, FILTER_VALIDATE_IP) ? @inet_pton($ip) : false;
		if ($bin === false) return null;
		$bits = strlen($bin) * 8;
		$node = 0;
		if ($bits === 32 && (int) ($this->meta['ip_version'] ?? 4) === 6) {
			// IPv4 dans un arbre IPv6 : sous ::/96
			if ($this->ipv4Start === null) {
				$n = 0;
				for ($i = 0; $i < 96 && $n < $this->nodeCount; $i++) $n = $this->node($n)[0];
				$this->ipv4Start = $n;
			}
			$node = $this->ipv4Start;
		}
		for ($i = 0; $i < $bits && $node < $this->nodeCount; $i++) {
			$bit = (ord($bin[$i >> 3]) >> (7 - ($i & 7))) & 1;
			$node = $this->node($node)[$bit];
		}
		if ($node <= $this->nodeCount) return null; // = node_count : absente
		$offset = $this->treeSize + ($node - $this->nodeCount);
		if ($offset >= $this->size) throw new RuntimeException('Pointeur hors du fichier');
		list($value) = $this->decode($offset, $this->dataStart);
		return is_array($value) ? $value : null;
	}

	/**
	 * Décode une valeur ; $base : début de la section (pointeurs relatifs)
	 * @return array (valeur, position suivante)
	 */
	private function decode($offset, $base, $depth = 0) {
		if ($depth > 32) throw new RuntimeException('Données trop imbriquées');
		$ctrl = ord($this->read($offset++, 1));
		$type = $ctrl >> 5;
		if ($type === 1) { // pointeur
			$ss = ($ctrl >> 3) & 3;
			$v = $ctrl & 7;
			switch ($ss) {
				case 0: $p = ($v << 8) | ord($this->read($offset, 1)); $offset += 1; break;
				case 1: $p = (($v << 16) | unpack('n', $this->read($offset, 2))[1]) + 2048; $offset += 2; break;
				case 2: $p = (($v << 24) | unpack('N', "\0" . $this->read($offset, 3))[1]) + 526336; $offset += 3; break;
				default: $p = unpack('N', $this->read($offset, 4))[1]; $offset += 4;
			}
			list($val) = $this->decode($base + $p, $base, $depth + 1);
			return array($val, $offset);
		}
		if ($type === 0) $type = 7 + ord($this->read($offset++, 1));
		$size = $ctrl & 0x1f;
		if ($size >= 29) {
			$n = $size - 28;
			$raw = $this->read($offset, $n);
			$offset += $n;
			$size = $n === 1 ? 29 + ord($raw) : ($n === 2 ? 285 + unpack('n', $raw)[1] : 65821 + unpack('N', "\0" . $raw)[1]);
		}
		switch ($type) {
			case 2: return array($this->read($offset, $size), $offset + $size);              // texte UTF-8
			case 3: return array(unpack('E', $this->read($offset, 8))[1], $offset + 8);     // double
			case 4: return array($this->read($offset, $size), $offset + $size);              // octets
			case 5: case 6: case 9: case 10:                                                    // entiers non signés
				$v = 0;
				foreach (str_split($size > 0 ? $this->read($offset, $size) : '') as $c) $v = $v * 256 + ord($c);
				return array($size > 7 ? (string) $v : (int) $v, $offset + $size);
			case 7: // map
				$out = array();
				for ($i = 0; $i < $size; $i++) {
					list($k, $offset) = $this->decode($offset, $base, $depth + 1);
					list($v, $offset) = $this->decode($offset, $base, $depth + 1);
					$out[(string) $k] = $v;
				}
				return array($out, $offset);
			case 8: // int32
				$raw = str_pad($size > 0 ? $this->read($offset, $size) : '', 4, "\0", STR_PAD_LEFT);
				return array(unpack('l', pack('L', unpack('N', $raw)[1]))[1], $offset + $size);
			case 11: // tableau
				$out = array();
				for ($i = 0; $i < $size; $i++) {
					list($v, $offset) = $this->decode($offset, $base, $depth + 1);
					$out[] = $v;
				}
				return array($out, $offset);
			case 14: return array($size !== 0, $offset);                                       // booléen
			case 15: return array(unpack('G', $this->read($offset, 4))[1], $offset + 4);      // float
			default: throw new RuntimeException('Type de donnée inconnu (' . $type . ')');
		}
	}
}

/** Lecteur de la base installée (gardé pour la requête), null si absente ou invalide */
function stats_geoip_reader($reset = false) {
	static $reader = false;
	if ($reset) $reader = false;
	if ($reader !== false) return $reader;
	$reader = null;
	$file = stats_geoip_file();
	if (is_file($file)) {
		try { $reader = new StatsMmdb($file); } catch (Throwable $e) { $reader = null; }
	}
	return $reader;
}

/** Code pays (iso_code en minuscules) d'un enregistrement, '' sinon */
function stats_geoip_code($record) {
	$cc = is_array($record) ? (string) ($record['country']['iso_code'] ?? ($record['registered_country']['iso_code'] ?? '')) : '';
	return preg_match('/^[A-Za-z]{2}$/', $cc) ? strtolower($cc) : '';
}

/**
 * Pays d'un visiteur : en-tête CF-IPCountry (Cloudflare), sinon base DB-IP
 * @return string code ISO en minuscules, '' si inconnu
 */
function stats_country_of($ip, $cfHeader = '') {
	$cf = strtoupper(trim((string) $cfHeader));
	if (preg_match('/^[A-Z]{2}$/', $cf) && !in_array($cf, array('XX', 'T1'), true)) return strtolower($cf);
	$reader = stats_geoip_reader();
	if (!$reader) return '';
	try { return stats_geoip_code($reader->get($ip)); } catch (Throwable $e) { return ''; }
}

// -----------------------------------------------------------------------
// Téléchargement depuis les Réglages
// -----------------------------------------------------------------------

/** Page de téléchargement de DB-IP (lien du mois et empreinte SHA1) */
function stats_geoip_page_url() {
	return 'https://db-ip.com/db/download/ip-to-country-lite';
}

/**
 * Lien et empreinte du fichier MMDB lus sur la page de DB-IP
 * @return array url, sha1 ('' si non trouvés)
 */
function stats_geoip_parse_page($html) {
	$html = (string) $html;
	$url = $sha = '';
	if (preg_match('~https://download\.db-ip\.com/free/dbip-country-lite-(\d{4}-\d{2})\.mmdb\.gz~', $html, $m, PREG_OFFSET_CAPTURE)) {
		$url = $m[0][0];
		$pos = $m[0][1];
		// Empreinte SHA1 du fichier MMDB : dans sa carte, entre la mention
		// « MMDB » et le lien (mise en page de DB-IP), sinon juste après le lien ;
		// jamais celle du CSV
		$start = strrpos(substr($html, 0, $pos), '>MMDB<');
		$zones = array();
		if ($start !== false && $pos - $start < 4000) $zones[] = substr($html, $start, $pos - $start);
		$zones[] = substr($html, $pos, 1500);
		foreach ($zones as $zone) {
			if (stripos($zone, '.csv') !== false && $zone !== end($zones)) continue;
			if (preg_match('~SHA1SUM.{0,200}?\b([a-f0-9]{40})\b~is', $zone, $h)) { $sha = strtolower($h[1]); break; }
		}
	}
	return array($url, $sha);
}

/** Liens à essayer : celui de la page, puis le mois en cours et le précédent */
function stats_geoip_candidates($pageUrl, $now = null) {
	$now = $now ?? time();
	$out = array();
	if ($pageUrl !== '') $out[] = $pageUrl;
	foreach (array(date('Y-m', $now), date('Y-m', strtotime('first day of last month', $now))) as $ym) {
		$u = 'https://download.db-ip.com/free/dbip-country-lite-' . $ym . '.mmdb.gz';
		if (!in_array($u, $out, true)) $out[] = $u;
	}
	return $out;
}

/**
 * GET HTTPS : dans un fichier ($to) ou en mémoire. Taille bornée.
 * @return array (code HTTP, contenu ou '' si fichier, erreur)
 */
function stats_http_get($url, $to = null, $max = 52428800, $timeout = 120) {
	if (!function_exists('curl_init')) return array(0, '', 'Extension PHP curl absente');
	$ch = curl_init($url);
	$fh = null;
	$body = '';
	$len = 0;
	if ($to !== null) {
		$fh = @fopen($to, 'wb');
		if (!$fh) return array(0, '', 'Écriture impossible : ' . $to);
	}
	curl_setopt_array($ch, array(
		CURLOPT_FOLLOWLOCATION => true, CURLOPT_MAXREDIRS => 3, CURLOPT_PROTOCOLS => CURLPROTO_HTTPS, CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS,
		CURLOPT_CONNECTTIMEOUT => 15, CURLOPT_TIMEOUT => $timeout, CURLOPT_SSL_VERIFYPEER => true, CURLOPT_SSL_VERIFYHOST => 2,
		CURLOPT_USERAGENT => 'SBUIADMIN-stats/1.0',
		CURLOPT_WRITEFUNCTION => function ($ch, $chunk) use (&$fh, &$body, &$len, $max) {
			$len += strlen($chunk);
			if ($len > $max) return 0; // trop gros : transfert interrompu
			if ($fh) return fwrite($fh, $chunk);
			$body .= $chunk;
			return strlen($chunk);
		},
	));
	$ok = curl_exec($ch);
	$code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
	$err = $ok ? '' : ($len > $max ? 'Fichier trop gros' : curl_error($ch));
	if (PHP_VERSION_ID < 80000) curl_close($ch);
	if ($fh) fclose($fh);
	return array($code, $body, $err);
}

/** Décompresse un .gz vers $to (taille bornée) ; '' ou message d'erreur */
function stats_gunzip($from, $to, $max = 209715200) {
	$in = @gzopen($from, 'rb');
	if (!$in) return 'Archive illisible';
	$out = @fopen($to, 'wb');
	if (!$out) { gzclose($in); return 'Écriture impossible'; }
	$total = 0;
	$err = '';
	while (!gzeof($in)) {
		$chunk = gzread($in, 1048576);
		if ($chunk === false) { $err = 'Archive corrompue'; break; }
		$total += strlen($chunk);
		if ($total > $max) { $err = 'Fichier décompressé trop gros'; break; }
		fwrite($out, $chunk);
	}
	gzclose($in);
	fclose($out);
	if ($err === '' && $total === 0) $err = 'Archive vide';
	return $err;
}

/** Adresses témoins du contrôle : adresse => pays attendu */
function stats_geoip_witnesses() {
	return array('8.8.8.8' => 'us', '2606:4700:4700::1111' => 'us', '2001:41d0::1' => 'fr', '2a01:e0a::1' => 'fr', '212.27.48.10' => 'fr');
}

/**
 * Contrôle d'une base MMDB pays : format, type, date, quelques adresses connues
 * @return array (erreur ou '', métadonnées)
 */
function stats_geoip_validate($file, $now = null) {
	$now = $now ?? time();
	try {
		$r = new StatsMmdb($file);
	} catch (Throwable $e) {
		return array('Base invalide : ' . $e->getMessage(), array());
	}
	$m = $r->meta;
	if ((int) ($m['ip_version'] ?? 0) !== 6) return array('Base invalide : IPv6 attendu', $m);
	if (stripos((string) ($m['database_type'] ?? ''), 'country') === false) return array('Base invalide : ce n\'est pas une base de pays (' . (string) ($m['database_type'] ?? '?') . ')', $m);
	$epoch = (int) ($m['build_epoch'] ?? 0);
	if ($epoch < $now - 400 * 86400 || $epoch > $now + 86400) return array('Base invalide : date de construction inattendue', $m);
	if ((int) ($m['node_count'] ?? 0) < 1000) return array('Base invalide : trop petite', $m);
	// Adresses témoins stables (DNS de Google, Cloudflare, OVH, Free) : toutes
	// doivent donner un pays, 4 sur 5 le pays attendu (une base peut déplacer
	// une adresse d'un mois à l'autre sans être fausse)
	try {
		$hits = 0;
		foreach (stats_geoip_witnesses() as $ip => $cc) {
			$got = stats_geoip_code($r->get($ip));
			if ($got === '') return array('Base invalide : aucun pays pour ' . $ip, $m);
			if ($got === $cc) $hits++;
		}
		if ($hits < 4) return array('Base invalide : résultats inattendus pour les adresses témoins (' . $hits . ' sur 5)', $m);
	} catch (Throwable $e) {
		return array('Base invalide : ' . $e->getMessage(), $m);
	}
	return array('', $m);
}

/**
 * Télécharge, contrôle et installe la base (remplacement atomique).
 * @param callable|null $http fonction de téléchargement (tests)
 * @return array (message d'erreur ou '', informations : version, sha1, taille)
 */
function stats_geoip_update($http = null, $now = null) {
	$http = $http ?? 'stats_http_get';
	$dir = stats_geoip_dir(true);
	if ($dir === '' || !is_writable($dir)) return array('Dossier ' . basename(dirname(__DIR__)) . '/geoip/ non inscriptible', array());
	// 1. Page de DB-IP : lien du mois et empreinte (facultatifs)
	list($code, $html) = call_user_func($http, stats_geoip_page_url(), null, 2097152, 30);
	list($pageUrl, $sha1) = $code === 200 ? stats_geoip_parse_page($html) : array('', '');
	// 2. Fichier compressé
	$gz = $dir . DIRECTORY_SEPARATOR . 'download.mmdb.gz';
	$tmp = $dir . DIRECTORY_SEPARATOR . 'download.mmdb';
	$got = '';
	foreach (stats_geoip_candidates($pageUrl, $now) as $url) {
		list($code, , $err) = call_user_func($http, $url, $gz, 52428800, 180);
		if ($code === 200 && $err === '' && is_file($gz) && filesize($gz) > 1000) { $got = $url; break; }
	}
	$fail = function ($msg) use ($gz, $tmp) { @unlink($gz); @unlink($tmp); return array($msg, array()); };
	if ($got === '') return $fail('Téléchargement impossible depuis download.db-ip.com');
	// 3. Décompression
	$err = stats_gunzip($gz, $tmp);
	if ($err !== '') return $fail($err);
	// 4. Empreinte publiée par DB-IP (celle du fichier .mmdb décompressé) :
	// refus si elle ne correspond pas, seulement pour le fichier qu'elle décrit
	$hash = sha1_file($tmp);
	if ($sha1 !== '' && $got === $pageUrl && !hash_equals($sha1, $hash)) return $fail('Empreinte SHA1 différente de celle publiée par DB-IP : fichier refusé');
	list($err, $meta) = stats_geoip_validate($tmp, $now);
	if ($err !== '') return $fail($err);
	// 5. Installation atomique
	if (!@rename($tmp, stats_geoip_file())) return $fail('Installation impossible');
	@unlink($gz);
	@chmod(stats_geoip_file(), 0644);
	stats_geoip_reader(true);
	preg_match('~(\d{4}-\d{2})\.mmdb\.gz$~', $got, $vm);
	$info = array(
		'version' => $vm[1] ?? '', 'sha1' => $hash, 'sha1_checked' => ($sha1 !== '' && $got === $pageUrl) ? '1' : '0',
		'size' => (string) filesize(stats_geoip_file()), 'build' => (string) (int) ($meta['build_epoch'] ?? 0), 'updated' => (string) ($now ?? time()),
	);
	if (function_exists('stats_settings_save')) {
		stats_settings_save(array('geoip_version' => $info['version'], 'geoip_sha1' => $info['sha1'], 'geoip_checked' => $info['sha1_checked'], 'geoip_updated' => $info['updated']));
	}
	return array('', $info);
}

/** Supprime la base installée */
function stats_geoip_remove() {
	@unlink(stats_geoip_file());
	stats_geoip_reader(true);
	if (function_exists('stats_settings_save')) stats_settings_save(array('geoip_version' => '', 'geoip_sha1' => '', 'geoip_checked' => '0', 'geoip_updated' => '0'));
	return !is_file(stats_geoip_file());
}
