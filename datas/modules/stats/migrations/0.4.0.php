<?php
/**
 * Module stats, migration 0.4.0 : tableau de bord de chaque utilisateur
 * (sb_stats_dashboard : widgets, colonnes, ordre). Retour : la table supprimée.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'tableau de bord à widgets par utilisateur',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$m->createTable('sb_stats_dashboard', "
  `user_id` int(11) NOT NULL,
  `layout` text NOT NULL COMMENT 'JSON : colonnes, widgets, réduits',
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`)", 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci');
	},

	'down' => function (SbMigration $m) {
		$m->dropTable('sb_stats_dashboard');
	},
);
