<?php
/**
 * Migration 4.91 : .htaccess du site, ErrorDocument 404 en adresse absolue
 *
 * « ErrorDocument 404 index.php?... » (sans « / ») était affiché comme du
 * texte par Apache. La ligne est remplacée par /<sous-dossier>/index.php?...
 * (tiré de l'URL du site) si elle est encore sous une forme livrée ; une
 * ligne retouchée à la main reste telle quelle. Rien sans .htaccess ou s'il
 * n'est pas inscriptible. Retour arrière : rien à défaire (sans effet sur
 * la 4.90).
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

// Fonction nouvelle : la migration peut être exécutée par le code de la 4.90
require_once(dirname(__DIR__) . '/sbuiadmin-htaccess.php');

return array(
	'description' => '.htaccess : ErrorDocument 404 en adresse absolue (sous-dossier compris)',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$file = dirname(SBUIADMIN_PATH) . '/.htaccess';
		if (sbHtaccessErrorDocApply($file, sbSetting('site_url')) === false) {
			error_log('Migration 4.91 : .htaccess non modifié (fichier non inscriptible)');
		}
	},

	'down' => function (SbMigration $m) {
	},
);
