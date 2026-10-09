<?php
/**
 * Migration 4.17 : page de maintenance (ancien « Coming soon »)
 *
 * Compte à rebours désormais au choix (CMS Config > Maintenance), désactivé
 * par défaut : il était en commentaire dans l'ancienne page. La ligne
 * coming-soon-google-plus (Google+ fermé) n'est plus lue, elle est laissée.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => 'compte à rebours de la page de maintenance',
	'reversible'  => true,
	'up'   => function (SbMigration $m) { $m->addConfig('coming-soon-countdown', '0'); },
	'down' => function (SbMigration $m) { $m->deleteConfig('coming-soon-countdown'); },
);
