<?php
/**
 * Migration 4.48 : champ « Heure » de la page de démonstration (sandbox.php)
 *
 * Type 'time' de nouveau accepté par addInput() (sbuiadmin-form.php) :
 * exemple ajouté au sandbox, enregistré au format HH:MM (vide = aucune heure).
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => "champ Heure de la page de démonstration",
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		if ($m->tableExists('sb_sandbox')) $m->addColumn('sb_sandbox', 'hour', "varchar(5) NOT NULL DEFAULT ''", 'color');
	},

	'down' => function (SbMigration $m) {
		$m->dropColumn('sb_sandbox', 'hour');
	},
);
