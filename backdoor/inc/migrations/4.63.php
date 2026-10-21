<?php
/**
 * Migration 4.63 : inc/admin/theme.txt supprimé
 *
 * Le thème est en base depuis la 4.14 (réglage « theme » de sb_config) et le
 * core ne lit plus le fichier. Il est GARDÉ quand le sbconfig.php du site le
 * lit encore (ancien sbconfig.php, jamais remplacé par les mises à jour : il
 * s'arrête sans lui).
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

$sb463_file = SBUIADMIN_PATH . '/inc/admin/theme.txt';

/** Le sbconfig.php du site lit-il encore theme.txt ? */
$sb463_needed = function () {
	$cfg = @file_get_contents(dirname(SBUIADMIN_PATH) . '/sbconfig.php');
	return $cfg === false || strpos($cfg, 'theme.txt') !== false;
};

return array(
	'description' => 'fichier theme.txt retiré (thème en base)',
	'reversible'  => true,

	'up' => function (SbMigration $m) use ($sb463_file, $sb463_needed) {
		if (is_file($sb463_file) && !$sb463_needed()) @unlink($sb463_file);
	},

	// Rien à recréer : le core de la 4.62 lit d'abord le thème en base
	'down' => function (SbMigration $m) {
	},
);
