<?php
/**
 * .htaccess du site : ligne ErrorDocument 404
 *
 * Une cible qui ne commence ni par « / » ni par « http » est affichée par
 * Apache comme un message texte : la ligne livrée jusqu'en 4.90
 * (ErrorDocument 404 index.php?p=pages&id=404) renvoyait ce texte brut pour
 * toute 404 produite par Apache lui-même (sous-dossier avec son propre
 * RewriteEngine, mod_rewrite absent).
 *
 * Utilisé par la migration 4.91 et l'installateur. Seules les formes livrées
 * sont remplacées (relative, relative avec le sous-dossier, /index.php) ;
 * une ligne retouchée à la main n'est jamais modifiée.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

/** Cible absolue de l'ErrorDocument 404 pour l'URL du site (sous-dossier compris) */
function sbHtaccessErrorDocTarget($site_url) {
	$sub = trim((string) parse_url((string) $site_url, PHP_URL_PATH), '/');
	return '/' . ($sub !== '' ? $sub . '/' : '') . 'index.php?p=pages&id=404';
}

/**
 * Contenu corrigé, ou null si rien à changer (ligne absente, déjà juste ou
 * retouchée à la main)
 */
function sbHtaccessErrorDocFix($src, $site_url) {
	$target = sbHtaccessErrorDocTarget($site_url);
	$sub    = trim((string) parse_url((string) $site_url, PHP_URL_PATH), '/');
	$known  = array('index.php?p=pages&id=404', '/index.php?p=pages&id=404');
	if ($sub !== '') $known[] = $sub . '/index.php?p=pages&id=404';
	$changed = false;
	$out = preg_replace_callback('/^([ \t]*ErrorDocument[ \t]+404[ \t]+)(\S+)([ \t]*)(?=\r?$)/m', function ($m) use ($known, $target, &$changed) {
		if ($m[2] === $target || !in_array($m[2], $known, true)) return $m[0];
		$changed = true;
		return $m[1] . $target . $m[3];
	}, (string) $src);
	return ($changed && is_string($out)) ? $out : null;
}

/**
 * Corrige le .htaccess sur le disque (écriture atomique, droits gardés)
 * @return bool|null true corrigé, null rien à faire, false échec d'écriture
 */
function sbHtaccessErrorDocApply($file, $site_url) {
	if (!is_file($file)) return null;
	$src = @file_get_contents($file);
	if (!is_string($src)) return null;
	$new = sbHtaccessErrorDocFix($src, $site_url);
	if ($new === null) return null;
	if (!is_writable($file)) return false;
	$tmp = $file . '.sb491-' . bin2hex(random_bytes(4));
	if (@file_put_contents($tmp, $new, LOCK_EX) !== strlen($new)) { @unlink($tmp); return false; }
	$perms = @fileperms($file);
	if ($perms !== false) @chmod($tmp, $perms & 0777);
	if (!@rename($tmp, $file)) { @unlink($tmp); return false; }
	return true;
}
