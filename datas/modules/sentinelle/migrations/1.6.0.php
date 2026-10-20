<?php
/**
 * Module sentinelle, migration 1.6.0 : pays de l'adresse dans le journal
 * (sb_sentinelle_log.country). Retour : la colonne supprimée.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'pays de l\'adresse dans le journal',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		if ($m->tableExists('sb_sentinelle_log') && !$m->columnExists('sb_sentinelle_log', 'country')) {
			$m->addColumn('sb_sentinelle_log', 'country', "varchar(2) NOT NULL DEFAULT ''", 'ip');
		}
	},

	'down' => function (SbMigration $m) {
		$m->dropColumn('sb_sentinelle_log', 'country');
	},
);
