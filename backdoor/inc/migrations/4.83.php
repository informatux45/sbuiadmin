<?php
/**
 * Migration 4.83 : .htaccess du site, sauvegardes et fichiers de dev refusés
 *
 * Une copie posée à côté d'un fichier (functions.php.bak-20261112, x.php~,
 * dump.sql.gz...) n'est pas exécutée par PHP : Apache la servait en clair.
 * La mise à jour ne remplace jamais le .htaccess (fichier propre au site) :
 * cette migration y ajoute le bloc livré dans le .htaccess de la 4.83, après
 * celui des fichiers cachés (<FilesMatch "^\.">), sinon à la fin.
 *
 * Rien n'est touché sans .htaccess (autre serveur), s'il est illisible ou non
 * inscriptible, ou si le bloc y est déjà (installation neuve, relance).
 * Retour arrière : le bloc est retiré s'il n'a pas été modifié depuis.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

$sb483_file  = dirname(SBUIADMIN_PATH) . '/.htaccess';
$sb483_begin = '## SBUIADMIN 4.83 : sauvegardes et fichiers de dev jamais servis (début)';
$sb483_block = <<<'HTACCESS'
## SBUIADMIN 4.83 : sauvegardes et fichiers de dev jamais servis (début)
## Une copie posée à côté d'un fichier n'est pas exécutée par PHP : Apache la
## servirait en clair (functions.php.bak-20261112, x.php~, dump.sql.gz...).
## 1) extensions de dev ou de sauvegarde en fin de nom ;
## 2) source PHP suivie d'un suffixe, quel qu'il soit ;
## 3) suffixes de sauvegarde, fichiers d'éditeur ; .old/.save/.tmp au milieu
##    d'un nom seulement suivis d'un chiffre (jquery.old-browser.js servi).
<FilesMatch "(?i)^(composer\.(json|lock)|installed\.(json|php)|package(-lock)?\.json|.*\.(md|dist|sql|bak|old|orig|swp|swo|save|sav|log|ini|sh|tmp))$">
<IfModule mod_authz_core.c>
	Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
	Order allow,deny
	Deny from all
</IfModule>
</FilesMatch>
<FilesMatch "(?i)\.(php|phtml|phar)(\d+|[.~#_-][^/]*)$">
<IfModule mod_authz_core.c>
	Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
	Order allow,deny
	Deny from all
</IfModule>
</FilesMatch>
<FilesMatch "(?i)(\.(bak|orig|swp|swo|sql)([._~-][^/]*|\d+)|\.(old|save|sav|tmp)(\d+|[._-]\d[^/]*)|~|^#.*#)$">
<IfModule mod_authz_core.c>
	Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
	Order allow,deny
	Deny from all
</IfModule>
</FilesMatch>
## SBUIADMIN 4.83 (fin)

HTACCESS;

/** Bloc aux fins de ligne du fichier */
$sb483_eol = function ($src) use ($sb483_block) {
	return (strpos($src, "\r\n") !== false) ? str_replace("\n", "\r\n", $sb483_block) : $sb483_block;
};

/** .htaccess avec le bloc : après celui des fichiers cachés, sinon à la fin */
$sb483_insert = function ($src) use ($sb483_eol) {
	$block = $sb483_eol($src);
	$nl    = (strpos($src, "\r\n") !== false) ? "\r\n" : "\n";
	$open  = strpos($src, '<FilesMatch "^\.">');
	$close = ($open !== false) ? strpos($src, '</FilesMatch>', $open) : false;
	$eol   = ($close !== false) ? strpos($src, "\n", $close) : false;
	if ($eol !== false) return substr($src, 0, $eol + 1) . $nl . $block . substr($src, $eol + 1);
	if ($src !== '' && substr($src, -1) !== "\n") $src .= $nl;
	return $src . $nl . $block;
};

/** Écriture atomique (fichier temporaire + renommage), droits gardés */
$sb483_write = function ($file, $data) {
	$tmp = $file . '.sb483-' . bin2hex(random_bytes(4));
	if (@file_put_contents($tmp, $data, LOCK_EX) !== strlen($data)) { @unlink($tmp); return false; }
	$perms = @fileperms($file);
	if ($perms !== false) @chmod($tmp, $perms & 0777);
	if (!@rename($tmp, $file)) { @unlink($tmp); return false; }
	return true;
};

return array(
	'description' => '.htaccess : sauvegardes (x.php.bak-…, x.php~) et fichiers de dev jamais servis',
	'reversible'  => true,

	'up' => function (SbMigration $m) use ($sb483_file, $sb483_begin, $sb483_insert, $sb483_write) {
		if (!is_file($sb483_file)) return;
		$src = @file_get_contents($sb483_file);
		if (!is_string($src) || strpos($src, $sb483_begin) !== false) return;
		if (!is_writable($sb483_file) || !$sb483_write($sb483_file, $sb483_insert($src))) {
			error_log('Migration 4.83 : .htaccess non modifié (fichier non inscriptible)');
		}
	},

	// Bloc retiré tel qu'il a été posé (s'il a été retouché, il reste)
	'down' => function (SbMigration $m) use ($sb483_file, $sb483_eol, $sb483_write) {
		$src = is_file($sb483_file) ? @file_get_contents($sb483_file) : false;
		if (!is_string($src)) return;
		$nl    = (strpos($src, "\r\n") !== false) ? "\r\n" : "\n";
		$cut   = $nl . $sb483_eol($src);
		$pos   = strpos($src, $cut);
		if ($pos === false) return;
		$sb483_write($sb483_file, substr($src, 0, $pos) . substr($src, $pos + strlen($cut)));
	},
);
