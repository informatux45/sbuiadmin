<?php
/**
 * Adresse IP du visiteur (site et administration)
 *
 * REMOTE_ADDR par défaut : c'est la seule adresse que le visiteur ne choisit
 * pas. Les en-têtes CF-Connecting-IP / X-Forwarded-For ne sont lus que si la
 * connexion vient d'un proxy déclaré (Utilisateurs > IP bloquées > Paramètres) :
 * - proxy_mode 'cloudflare' : plages publiées par Cloudflare (+ proxy_trusted),
 *   en-tête CF-Connecting-IP ;
 * - proxy_mode 'custom' : adresses ou plages CIDR de proxy_trusted, en-tête
 *   X-Forwarded-For lu de droite à gauche (première adresse hors proxys).
 * Sans proxy déclaré, ces en-têtes permettaient de contourner le blocage
 * d'IP ou de faire bloquer l'adresse de quelqu'un d'autre.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

/** Plages Cloudflare (https://www.cloudflare.com/ips/, relevées le 2026-10-20) */
function sbClientIpCloudflareRanges() {
	return array(
		'173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22',
		'141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20',
		'197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/13',
		'104.24.0.0/14', '172.64.0.0/13', '131.0.72.0/22',
		'2400:cb00::/32', '2606:4700::/32', '2803:f800::/32', '2405:b500::/32',
		'2405:8100::/32', '2a06:98c0::/29', '2c0f:f248::/32',
	);
}

/** Adresse (IPv4/IPv6) ou plage CIDR valide, normalisée ; '' sinon */
function sbClientIpNormalizeRange($range) {
	$range = trim((string) $range);
	if ($range === '') return '';
	$parts = explode('/', $range, 2);
	$ip = $parts[0];
	$bin = filter_var($ip, FILTER_VALIDATE_IP) ? inet_pton($ip) : false;
	if ($bin === false) return '';
	$max = strlen($bin) * 8;
	if (!isset($parts[1])) return inet_ntop($bin);
	if (!preg_match('/^\d{1,3}$/', $parts[1]) || (int) $parts[1] > $max) return '';
	return inet_ntop($bin) . '/' . (int) $parts[1];
}

/** $ip est-elle dans la plage $range (adresse seule ou CIDR) ? */
function sbClientIpInRange($ip, $range) {
	$ipb = filter_var($ip, FILTER_VALIDATE_IP) ? inet_pton($ip) : false;
	$parts = explode('/', (string) $range, 2);
	$netb = filter_var($parts[0], FILTER_VALIDATE_IP) ? inet_pton($parts[0]) : false;
	if ($ipb === false || $netb === false || strlen($ipb) !== strlen($netb)) return false;
	$bits = isset($parts[1]) ? (int) $parts[1] : strlen($ipb) * 8;
	$bytes = intdiv($bits, 8);
	if (substr($ipb, 0, $bytes) !== substr($netb, 0, $bytes)) return false;
	$rest = $bits % 8;
	if ($rest === 0) return true;
	$mask = (0xFF << (8 - $rest)) & 0xFF;
	return (ord($ipb[$bytes]) & $mask) === (ord($netb[$bytes]) & $mask);
}

/** Lignes de proxy_trusted (ou d'un texte saisi) : plages valides et lignes refusées */
function sbClientIpParseTrusted($text) {
	$ok = array(); $bad = array();
	foreach (preg_split('/[\s,;]+/', (string) $text) as $line) {
		if ($line === '') continue;
		$n = sbClientIpNormalizeRange($line);
		if ($n === '') $bad[] = $line; else $ok[$n] = $n;
	}
	return array(array_values($ok), $bad);
}

/** Mode de proxy réglé : none, cloudflare ou custom */
function sbClientIpMode() {
	$mode = function_exists('sbSetting') ? sbSetting('proxy_mode', 'none') : 'none';
	return in_array($mode, array('cloudflare', 'custom'), true) ? $mode : 'none';
}

/** Proxys de confiance du mode réglé */
function sbClientIpTrustedRanges($mode = null) {
	$mode = $mode ?? sbClientIpMode();
	if ($mode === 'none') return array();
	list($ranges) = sbClientIpParseTrusted(function_exists('sbSetting') ? sbSetting('proxy_trusted') : '');
	return $mode === 'cloudflare' ? array_merge(sbClientIpCloudflareRanges(), $ranges) : $ranges;
}

/** $ip appartient-elle à un proxy de confiance ? */
function sbClientIpIsTrusted($ip, array $ranges) {
	foreach ($ranges as $range) {
		if (sbClientIpInRange($ip, $range)) return true;
	}
	return false;
}

/**
 * Calcul de l'adresse du visiteur (sans cache, testable)
 * @param array $server  $_SERVER
 * @param string $mode   none, cloudflare ou custom
 * @param array $ranges  proxys de confiance
 */
function sbClientIpResolve(array $server, $mode, array $ranges) {
	$remote = trim((string) ($server['REMOTE_ADDR'] ?? ''));
	if (!filter_var($remote, FILTER_VALIDATE_IP)) return $remote === '' ? '' : substr($remote, 0, 45);
	if ($mode === 'none' || !sbClientIpIsTrusted($remote, $ranges)) return $remote;
	if ($mode === 'cloudflare') {
		$cf = trim((string) ($server['HTTP_CF_CONNECTING_IP'] ?? ''));
		if (filter_var($cf, FILTER_VALIDATE_IP)) return $cf;
	}
	// X-Forwarded-For : chaque proxy ajoute à droite l'adresse qui l'a contacté
	$chain = array_reverse(array_map('trim', explode(',', (string) ($server['HTTP_X_FORWARDED_FOR'] ?? ''))));
	foreach ($chain as $ip) {
		if (!filter_var($ip, FILTER_VALIDATE_IP)) break;
		if (!sbClientIpIsTrusted($ip, $ranges)) return $ip;
	}
	return $remote;
}

/** Adresse IP du visiteur */
function sbClientIp() {
	$mode = sbClientIpMode();
	return sbClientIpResolve($_SERVER, $mode, sbClientIpTrustedRanges($mode));
}

/** Proxy probable devant le site (aide au réglage) : 'cloudflare', 'proxy' ou '' */
function sbClientIpDetectProxy(array $server) {
	$remote = (string) ($server['REMOTE_ADDR'] ?? '');
	if (!empty($server['HTTP_CF_CONNECTING_IP']) && sbClientIpIsTrusted($remote, sbClientIpCloudflareRanges())) return 'cloudflare';
	return !empty($server['HTTP_X_FORWARDED_FOR']) ? 'proxy' : '';
}
