<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Configuration > Serveur : relevés selon le type d'hébergement
 *
 *  - sbServerProbe()         : ce que PHP peut lire (open_basedir,
 *                              disable_functions, /proc, panneau, virtualisation)
 *  - sbServerDetectHosting() : mutualisé / VPS / dédié déduit des indices
 *  - sbServerLive()          : disque, RAM, CPU, réseau, uptime (rafraîchi)
 *  - sbServerPhp/Db/Site()   : fiches fixes
 *
 * Une valeur illisible vaut null (affichée « non détecté »), jamais 0 :
 * sous open_basedir, /proc et / sont refusés, l'ancien tableau de bord
 * affichait des zéros et une charge CPU de 600 %.
 *
 * Chargé par server.php (page Configuration > Serveur, administrateurs).
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

/** Types d'hébergement (valeurs du réglage hosting_type, hors 'auto') */
function sbServerHostingTypes() {
	return array('shared' => 'Mutualisé', 'vps' => 'VPS', 'dedicated' => 'Dédié');
}

/** Lecture d'un fichier système sans avertissement (open_basedir) */
function sbServerRead($file) {
	if (!sbServerReadable($file)) return null;
	$s = @file_get_contents($file);
	return ($s === false || $s === '') ? null : $s;
}

/** Chemin autorisé par open_basedir ? (évite l'avertissement de is_readable) */
function sbServerReadable($file) {
	$basedir = (string) ini_get('open_basedir');
	if ($basedir !== '') {
		$allowed = false;
		foreach (explode(PATH_SEPARATOR, $basedir) as $dir) {
			$dir = rtrim(trim($dir), '/');
			if ($dir !== '' && ($file === $dir || strpos($file, $dir . '/') === 0)) { $allowed = true; break; }
		}
		if (!$allowed) return false;
	}
	return @is_readable($file);
}

/** Fonctions désactivées (disable_functions), en minuscules */
function sbServerDisabledFunctions() {
	return array_values(array_filter(array_map('strtolower', array_map('trim', explode(',', (string) ini_get('disable_functions'))))));
}

/**
 * Indices bruts sur l'environnement. $root = racine du site (chemin réel).
 */
function sbServerProbe($root) {
	$cpuinfo = sbServerRead('/proc/cpuinfo');
	$vendor  = sbServerRead('/sys/class/dmi/id/sys_vendor');
	$product = sbServerRead('/sys/class/dmi/id/product_name');
	$cgroup  = sbServerRead('/proc/1/cgroup');
	return array(
		'open_basedir' => (string) ini_get('open_basedir'),
		'disabled'     => sbServerDisabledFunctions(),
		'proc'         => sbServerRead('/proc/meminfo') !== null,
		'hypervisor'   => $cpuinfo !== null && preg_match('/^flags\s*:.*\bhypervisor\b/m', $cpuinfo) === 1,
		'dmi'          => trim((string) $vendor . ' ' . (string) $product),
		'container'    => (sbServerReadable('/proc/vz') && !sbServerReadable('/proc/bc')) || sbServerReadable('/.dockerenv')
		                  || ($cgroup !== null && preg_match('/docker|lxc|kubepods/', $cgroup) === 1)
		                  || strpos((string) sbServerRead('/proc/self/mountinfo'), 'lxcfs') !== false,
		'root'         => (string) $root,
		'cpu_model'    => (string) sbServerCpuModel(),
		'cores'        => (int) sbServerCores(),
	);
}

/** Panneau d'hébergement reconnu au chemin du site, ou '' */
function sbServerPanel($root) {
	if (preg_match('~^/var/www/clients/client\d+/web\d+/|^/var/www/clients/c\d+/w\d+/~', $root)) return 'ISPConfig';
	if (strpos($root, '/var/www/vhosts/') === 0) return 'Plesk';
	if (preg_match('~^/homez?\.\d+/~', $root)) return 'OVH mutualisé';
	if (preg_match('~^/home\d*/[^/]+/public_html(/|$)~', $root)) return 'cPanel';
	return '';
}

/**
 * Type d'hébergement déduit des indices.
 * @return array ['type' => shared|vps|dedicated, 'sure' => bool, 'clues' => [texte...]]
 */
function sbServerDetectHosting(array $p) {
	$clues = array();
	$panel = sbServerPanel($p['root']);
	if ($panel !== '') $clues[] = "Panneau d'hébergement reconnu : $panel";
	if ($p['open_basedir'] !== '') $clues[] = 'PHP confiné au site (open_basedir)';
	$vm = preg_match('/kvm|qemu|vmware|virtualbox|xen|hyper-v|virtual machine|openstack|amazon ec2|google compute|droplet|bochs/i', $p['dmi']) === 1;
	if ($p['container']) $clues[] = 'Conteneur (OpenVZ, LXC ou Docker)';
	if ($p['hypervisor']) $clues[] = 'Processeur virtualisé (hyperviseur)';
	if ($p['dmi'] !== '') $clues[] = 'Matériel : ' . $p['dmi'];

	if (in_array($panel, array('OVH mutualisé', 'cPanel'), true)) return array('type' => 'shared', 'sure' => $panel === 'OVH mutualisé', 'clues' => $clues);
	if ($p['container'] || $p['hypervisor'] || $vm) return array('type' => 'vps', 'sure' => true, 'clues' => $clues);
	// Puce de serveur (au moins 8 threads) dont on ne voit qu'une poignée de
	// cœurs : machine virtuelle qui masque le flag hypervisor
	$cores = $p['cores'] ?? 0;
	if ($cores > 0 && $cores <= 4 && preg_match('/xeon|epyc|opteron/i', $p['cpu_model'] ?? '')) {
		$clues[] = 'Processeur de serveur (' . $p['cpu_model'] . ') limité à ' . $cores . ' cœur' . ($cores > 1 ? 's' : '');
		return array('type' => 'vps', 'sure' => false, 'clues' => $clues);
	}
	if ($p['proc']) {
		$clues[] = 'Aucun signe de virtualisation';
		return array('type' => 'dedicated', 'sure' => false, 'clues' => $clues);
	}
	// /proc refusé : site confiné, la machine n'est pas visible
	if (in_array($panel, array('ISPConfig', 'Plesk'), true)) return array('type' => 'vps', 'sure' => false, 'clues' => $clues);
	return array('type' => 'shared', 'sure' => false, 'clues' => $clues);
}

/** Type retenu : réglage hosting_type s'il est forcé, sinon la détection */
function sbServerHostingType($setting, array $detected) {
	return array_key_exists($setting, sbServerHostingTypes()) ? $setting : $detected['type'];
}

/** Chemins à autoriser dans open_basedir pour les relevés de la machine */
function sbServerProcPaths() {
	return array('/proc/loadavg', '/proc/meminfo', '/proc/cpuinfo', '/proc/uptime', '/proc/net/dev');
}

/**
 * Charge moyenne 1, 5 et 15 min, ou null. /proc/loadavg d'abord : dans un
 * conteneur (LXC + lxcfs), c'est la charge du conteneur, alors que
 * sys_getloadavg() (appel système sysinfo, non filtré, mais hors
 * open_basedir) renvoie celle de la machine physique de l'hébergeur.
 */
function sbServerLoad() {
	$l = null;
	$raw = sbServerRead('/proc/loadavg');
	if ($raw !== null && preg_match('/^([\d.]+)\s+([\d.]+)\s+([\d.]+)/', $raw, $m)) {
		$l = array((float) $m[1], (float) $m[2], (float) $m[3]);
	} elseif (function_exists('sys_getloadavg') && !in_array('sys_getloadavg', sbServerDisabledFunctions(), true)) {
		$l = @sys_getloadavg();
		if (!is_array($l) || count($l) != 3) $l = null;
	}
	return $l ? array_map(function ($v) { return round($v, 2); }, $l) : null;
}

/** Nombre de cœurs, ou null */
function sbServerCores() {
	$cpuinfo = sbServerRead('/proc/cpuinfo');
	if ($cpuinfo !== null && ($n = preg_match_all('/^processor\s*:/m', $cpuinfo))) return $n;
	$online = sbServerRead('/sys/devices/system/cpu/online'); // ex. "0-3"
	if ($online !== null && preg_match('/^(\d+)-(\d+)$/', trim($online), $m)) return (int) $m[2] - (int) $m[1] + 1;
	return null;
}

/** Modèle du processeur, ou null */
function sbServerCpuModel() {
	$cpuinfo = sbServerRead('/proc/cpuinfo');
	return ($cpuinfo !== null && preg_match('/^model name\s*:\s*(.+)$/m', $cpuinfo, $m)) ? trim(preg_replace('/\s+/', ' ', $m[1])) : null;
}

/**
 * Relevés qui changent (rafraîchis par la page) : null = non détecté.
 * $root : dossier du site, mesuré quand / n'est pas accessible.
 */
function sbServerLive($root) {
	$out = array('disk' => null, 'ram' => null, 'load' => null, 'cores' => sbServerCores(), 'cpu' => sbServerCpuModel(), 'uptime' => null, 'net' => null, 'time' => date('H:i:s'));

	// Disque : partition du site (toujours dans open_basedir)
	$total = @disk_total_space($root);
	$free  = @disk_free_space($root);
	if ($total && $free !== false) $out['disk'] = array('total' => $total, 'free' => $free, 'used' => $total - $free);

	$meminfo = sbServerRead('/proc/meminfo');
	if ($meminfo !== null && preg_match('/MemTotal:\s+(\d+)/', $meminfo, $t) && preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $a)) {
		$out['ram'] = array('total' => $t[1] * 1024, 'free' => $a[1] * 1024, 'used' => ($t[1] - $a[1]) * 1024);
	}

	$out['load'] = sbServerLoad();
	$out['load_host'] = $out['load'] !== null && sbServerRead('/proc/loadavg') === null; // repli sys_getloadavg()

	$uptime = sbServerRead('/proc/uptime');
	if ($uptime !== null) $out['uptime'] = (int) $uptime;

	$netdev = sbServerRead('/proc/net/dev');
	if ($netdev !== null) {
		$rx = $tx = 0;
		foreach (explode("\n", $netdev) as $line) {
			if (!preg_match('/^\s*([^:\s]+):\s*(.*)$/', $line, $m) || $m[1] === 'lo') continue;
			$f = preg_split('/\s+/', trim($m[2]));
			if (isset($f[8])) { $rx += (int) $f[0]; $tx += (int) $f[8]; }
		}
		$out['net'] = array('rx' => $rx, 'tx' => $tx);
	}
	return $out;
}

/** Valeur ini en octets (« 128M » -> 134217728), -1 = illimité */
function sbServerIniBytes($v) {
	$v = trim((string) $v);
	if ($v === '' || $v === '-1') return -1;
	$n = (float) $v;
	switch (strtolower(substr($v, -1))) {
		case 'g': $n *= 1024;
		case 'm': $n *= 1024;
		case 'k': $n *= 1024;
	}
	return (int) $n;
}

/** Fiche PHP */
function sbServerPhp() {
	$ext = array();
	// Extensions dont le CMS a besoin (true = indispensable)
	foreach (array('mysqli' => true, 'mbstring' => true, 'json' => true, 'openssl' => true, 'sodium' => true, 'curl' => true,
	               'gd' => true, 'fileinfo' => true, 'zip' => false, 'intl' => false, 'iconv' => false, 'zend opcache' => false) as $e => $required) {
		$ext[] = array('name' => $e === 'zend opcache' ? 'opcache' : $e, 'loaded' => extension_loaded($e), 'required' => $required);
	}
	$opcache = null;
	if (function_exists('opcache_get_status') && !in_array('opcache_get_status', sbServerDisabledFunctions(), true)) {
		$s = @opcache_get_status(false);
		if (is_array($s) && !empty($s['memory_usage'])) {
			$opcache = array('enabled' => !empty($s['opcache_enabled']),
			                 'used' => $s['memory_usage']['used_memory'], 'free' => $s['memory_usage']['free_memory']);
		}
	}
	return array(
		'version'        => PHP_VERSION,
		'sapi'           => PHP_SAPI,
		'os'             => PHP_OS_FAMILY . (function_exists('php_uname') && !in_array('php_uname', sbServerDisabledFunctions(), true) ? ' ' . php_uname('r') : ''),
		'software'       => (string) ($_SERVER['SERVER_SOFTWARE'] ?? ''),
		'memory_limit'   => (string) ini_get('memory_limit'),
		'memory_peak'    => memory_get_peak_usage(true),
		'max_execution'  => (int) ini_get('max_execution_time'),
		'upload_max'     => (string) ini_get('upload_max_filesize'),
		'post_max'       => (string) ini_get('post_max_size'),
		'max_input_vars' => (int) ini_get('max_input_vars'),
		'timezone'       => date_default_timezone_get(),
		'extensions'     => $ext,
		'opcache'        => $opcache,
	);
}

/** Fiche base de données (MySQL / MariaDB) */
function sbServerDb($sbsql) {
	$out = array('version' => null, 'size' => null, 'tables' => null);
	$r = @$sbsql->query("SELECT VERSION() AS v");
	if ($r && ($row = $sbsql->assoc($r))) $out['version'] = $row['v'];
	$r = @$sbsql->query("SELECT COUNT(*) AS n, SUM(data_length + index_length) AS s FROM information_schema.TABLES WHERE table_schema = DATABASE()");
	if ($r && ($row = $sbsql->assoc($r))) { $out['tables'] = (int) $row['n']; $out['size'] = (int) $row['s']; }
	return $out;
}

/**
 * Taille d'un dossier, bornée (gros sites) : ['size' => octets, 'partial' => bool]
 */
function sbServerDirSize($dir, $max_files = 200000, $max_seconds = 5) {
	$size = 0; $n = 0; $start = microtime(true);
	if (!is_dir($dir)) return array('size' => 0, 'partial' => false);
	try {
		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::LEAVES_ONLY, RecursiveIteratorIterator::CATCH_GET_CHILD);
		foreach ($it as $f) {
			if ($f->isFile()) $size += $f->getSize();
			if (++$n >= $max_files || ($n % 1000 == 0 && microtime(true) - $start > $max_seconds)) return array('size' => $size, 'partial' => true);
		}
	} catch (Exception $e) {
		return array('size' => $size, 'partial' => true);
	}
	return array('size' => $size, 'partial' => false);
}

/** Fiche du site : place prise par dossier */
function sbServerSite(array $dirs) {
	$out = array();
	foreach ($dirs as $label => $dir) $out[] = array('label' => $label, 'dir' => $dir) + sbServerDirSize($dir);
	return $out;
}
