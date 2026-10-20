<?php
/**
 * Module sentinelle : tuile du dashboard « Score de sécurité »
 * (dernier passage du conseiller, réglage sentinelle_advisor)
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

defined('SBUIADMIN_PATH') or die('Are you crazy!');

return array(
	'title'  => 'Score de sécurité',
	'icon'   => 'shield-halved',
	'link'   => 'index.php?p=sentinelle&a=advisor',
	'format' => 'kpi',
	'description' => 'Score du conseiller sécurité de Sentinelle (mis à jour à chaque ouverture du conseiller).',
	'render' => function (array $p) {
		$a = json_decode(function_exists('sbSetting') ? sbSetting('sentinelle_advisor') : '', true);
		if (!is_array($a) || !isset($a['score'])) return array('value' => '—', 'compare' => 'Ouvrez le conseiller pour lancer l\'analyse');
		return array(
			'value'   => (int) $a['score'] . ' / 100',
			'compare' => (int) ($a['bad'] ?? 0) . ' à corriger · ' . (int) ($a['warn'] ?? 0) . ' à améliorer · ' . date('d/m/Y H:i', strtotime((string) $a['date'])),
		);
	},
);
