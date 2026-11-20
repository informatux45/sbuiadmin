<?php
/**
 * Migration 4.86 : .htaccess des dossiers d'écriture rétablis
 *
 * datas/cache/{core,medias,tpls_c} (site et admin) et upload/ portent un
 * .htaccess qui refuse tout script. Ces dossiers sont protégés : la mise à
 * jour ne les réécrit jamais, un .htaccess perdu ou vidé (copie FTP, archive
 * incomplète) n'était donc jamais rétabli.
 *
 * Le fichier livré est posé seulement s'il est absent ou vide (blancs
 * seulement) : un .htaccess propre au site n'est jamais touché. Rien sans le
 * dossier, ou s'il n'est pas inscriptible. Retour arrière : rien à défaire
 * (la protection reste, sans effet sur la 4.85).
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

$sb486_deny = <<<'HTACCESS'
## Aucun script ne doit s'exécuter dans ce dossier d'écriture (uploads,
## caches) : même si un fichier .php y était déposé malgré les contrôles de
## l'upload, Apache refuse de le servir. Les templates compilés par Smarty
## restent utilisables : PHP les inclut depuis le disque, pas par HTTP.
<FilesMatch "(?i)\.(php\d*|phtml|phar|pht|phps|cgi|pl|py|sh|shtml|asp|aspx|jsp)$">
	<IfModule mod_authz_core.c>
		Require all denied
	</IfModule>
	<IfModule !mod_authz_core.c>
		Order allow,deny
		Deny from all
	</IfModule>
</FilesMatch>
<FilesMatch "^\.(htaccess|user\.ini)$">
	<IfModule mod_authz_core.c>
		Require all denied
	</IfModule>
	<IfModule !mod_authz_core.c>
		Order allow,deny
		Deny from all
	</IfModule>
</FilesMatch>

HTACCESS;

$sb486_upload = <<<'HTACCESS'
## Documents qui peuvent porter du JavaScript (html, svg, xml, js) : jamais
## affichés comme une page du site. Servis en téléchargement, dans un bac à
## sable sans script. Nécessite mod_headers (sans lui, ce bloc est ignoré :
## la liste blanche de l'upload reste la protection principale).
<FilesMatch "(?i)\.(html?|xhtml|xht|svgz?|xml|xsl|xslt|js|mjs)$">
	<IfModule mod_headers.c>
		Header always set Content-Security-Policy "sandbox"
		Header always set Content-Disposition "attachment"
	</IfModule>
</FilesMatch>

HTACCESS;

$sb486_end  = "Options -ExecCGI -Indexes\n";
$sb486_root = dirname(SBUIADMIN_PATH);
$sb486_list = array(
	$sb486_root . '/datas/cache/core'          => $sb486_deny . $sb486_end,
	$sb486_root . '/datas/cache/medias'        => $sb486_deny . $sb486_end,
	$sb486_root . '/datas/cache/tpls_c'        => $sb486_deny . $sb486_end,
	SBUIADMIN_PATH . '/datas/cache/core'       => $sb486_deny . $sb486_end,
	SBUIADMIN_PATH . '/datas/cache/medias'     => $sb486_deny . $sb486_end,
	SBUIADMIN_PATH . '/datas/cache/tpls_c'     => $sb486_deny . $sb486_end,
	$sb486_root . '/upload'                    => $sb486_deny . $sb486_upload . $sb486_end,
);

return array(
	'description' => '.htaccess des caches et de upload/ rétablis s\'ils manquent ou sont vides',
	'reversible'  => true,

	'up' => function (SbMigration $m) use ($sb486_list) {
		foreach ($sb486_list as $dir => $content) {
			if (!is_dir($dir)) continue;
			$file = $dir . '/.htaccess';
			if (is_file($file)) {
				$src = @file_get_contents($file);
				if (!is_string($src) || trim($src) !== '') continue;
			} elseif (file_exists($file)) {
				continue;
			}
			// Écriture atomique (fichier temporaire + renommage)
			$tmp = $dir . '/.htaccess.sb486-' . bin2hex(random_bytes(4));
			if (@file_put_contents($tmp, $content, LOCK_EX) !== strlen($content)) {
				@unlink($tmp);
				error_log('Migration 4.86 : ' . $file . ' non rétabli (dossier non inscriptible)');
				continue;
			}
			@chmod($tmp, 0644);
			if (!@rename($tmp, $file)) {
				@unlink($tmp);
				error_log('Migration 4.86 : ' . $file . ' non rétabli');
			}
		}
	},

	'down' => function (SbMigration $m) {
	},
);
