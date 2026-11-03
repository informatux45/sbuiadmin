<?php
/**
 * Module contact : source de la recherche du site (module search, core)
 * Chargé par sbSearchProviders() quand le module est installé.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

/**
* FORMULAIRES (titre UNIQUEMENT)
* La table sb_contact contient les DEFINITIONS de formulaires, pas les
* messages recus. La colonne "recipients" porte des adresses e-mail et la
* colonne "form" la structure du formulaire : ni l'une ni l'autre ne doit
* etre cherchee ni exposee a un visiteur.
* @return array resultats ('title', 'excerpt', 'url', 'host')
*/
if (!function_exists("sbSearchSource_contact")) {
	function sbSearchSource_contact($terms, $lang) {
		$results = array();
		$bind    = array();
		$where   = sbSearchWhere(array('title'), $terms, $bind);
		$query   = "SELECT id, title
					FROM " . sbSearchTable('sb_contact') . "
					WHERE active = '1'" . $where . " ORDER BY sort ASC";
		$rows    = sbDb()->all($query, $bind) ?: array();
		if ($rows) {
			foreach ($rows as $row) {
				if (!sbSearchMatches(array($row['title']), $terms)) continue;
				// --- Deux shortcodes possibles pour un meme formulaire
				$host = sbSearchHostFor('sbcontact', $row['id']);
				if (!$host) $host = sbSearchHostFor('sbcontactajax', $row['id']);
				$results[] = array(
					'title'   => sbSearchTitle($row['title']),
					'excerpt' => '',
					'url'     => ($host) ? $host['url'] : false,
					'host'    => ($host) ? $host['label'] : false,
				);
			}
		}
		return $results;
	}
}

?>
