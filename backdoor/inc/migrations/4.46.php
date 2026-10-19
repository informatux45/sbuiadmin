<?php
/**
 * Migration 4.46 : réglages de la page de connexion de l'administration
 *
 * Configuration > Admin Login (sbLoginBranding()) : deux couleurs du
 * dégradé du bloc de gauche (vide = couleurs du thème), version de
 * SBUIADMIN affichée ou non, logo carré choisi dans les médias (vide =
 * logo SBUIADMIN). Valeurs par défaut : page de connexion inchangée.
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'description' => "réglages de la page de connexion de l'administration",
	'reversible'  => true,

	'up' => function (SbMigration $m) {
		$m->addConfig('login_color_start', '');
		$m->addConfig('login_color_end', '');
		$m->addConfig('login_show_version', '1');
		$m->addConfig('login_logo', '');
	},

	'down' => function (SbMigration $m) {
		foreach (array('login_color_start', 'login_color_end', 'login_show_version', 'login_logo') as $c) $m->deleteConfig($c);
	},
);
