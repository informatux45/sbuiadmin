<?php
/**
 * Migration 4.39 : code d'accès au site en maintenance tiré au sort
 *
 * Le SQL d'installation posait « comingSoon » (identique partout, public
 * dans le dépôt) et un code vide valait « DevProgress » : n'importe qui
 * voyait le site en maintenance avec ?d=comingSoon. Ces valeurs sont
 * remplacées par un code aléatoire (Configuration CMS > Maintenance, et le
 * lien « voir le site » de l'admin le porte). Un code choisi par
 * l'administrateur est gardé. Retour : rien à défaire, le nouveau code
 * reste valable avec l'ancienne version.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => "code d'accès au site en maintenance tiré au sort",
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$m->query('UPDATE `' . $m->table('sb_config') . "` SET `content` = '" . bin2hex(random_bytes(8))
			. "' WHERE `config` = 'coming-soon-url' AND TRIM(`content`) IN ('', 'comingSoon', 'DevProgress')");
	},

	'down' => function (SbMigration $m) {
	},
);
