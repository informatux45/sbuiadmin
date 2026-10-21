<?php
/**
 * Module sentinelle, migration 1.7.0 : fichiers PHP propres au site approuvés
 * dans l'analyse d'intégrité (sb_sentinelle_approved). Retour : la table
 * supprimée.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'fichiers propres au site approuvés (intégrité)',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$m->createTable('sb_sentinelle_approved', "
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT 'chemin de la liste (backdoor/ pour le dossier d''administration)',
  `sha256` char(64) NOT NULL DEFAULT '',
  `approved_at` datetime NOT NULL,
  `user` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `path` (`path`)", 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci');
	},

	'down' => function (SbMigration $m) {
		$m->dropTable('sb_sentinelle_approved');
	},
);
