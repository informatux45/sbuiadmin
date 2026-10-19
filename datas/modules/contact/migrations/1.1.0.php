<?php
/**
 * Module contact, migration 1.1.0 : messages reçus (sb_contact_messages)
 *
 * Chaque envoi d'un formulaire est enregistré, même si le mail échoue ;
 * conservation réglable dans Contact > Paramètres (12 mois par défaut,
 * réglage contact_retention absent = 12). Retour : la table supprimée.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'messages reçus par les formulaires',
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$m->createTable('sb_contact_messages', "
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`form_id` int(11) NOT NULL DEFAULT '0' COMMENT '0: page Contact du module',
			`created_at` datetime NOT NULL,
			`name` varchar(255) NOT NULL DEFAULT '',
			`email` varchar(255) NOT NULL DEFAULT '',
			`fields` mediumtext NOT NULL COMMENT 'champs saisis (JSON)',
			`recipients` text NOT NULL COMMENT 'destinataires valides',
			`sent` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1: mail envoyé',
			`error` varchar(255) NOT NULL DEFAULT '' COMMENT 'erreur d''envoi',
			`is_read` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`),
			KEY `form_created` (`form_id`,`created_at`)");
	},

	'down' => function (SbMigration $m) {
		$m->dropTable('sb_contact_messages');
	},
);
