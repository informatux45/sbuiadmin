<?php
/**
 * Module download : source de la recherche du site (module search, core)
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
* TELECHARGEMENTS (titre + description)
* Seul module sans page publique qui possede malgre tout une URL propre :
* celle qui sert le fichier.
* @return array resultats ('title', 'excerpt', 'url', 'host')
*/
if (!function_exists("sbSearchSource_download")) {
	function sbSearchSource_download($terms, $lang) {
		global $sbsql, $sbsanitize;
		$results = array();
		$where   = sbSearchWhere(array('title', 'description'), $terms);
		$query   = "SELECT id, title, description, filename, randkey
					FROM " . sbSearchTable('sb_download') . "
					WHERE active = '1'" . $where . " ORDER BY id DESC";
		$request = $sbsql->query($query);
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				if (!sbSearchMatches(array($row['title'], $row['description']), $terms)) continue;
				$key  = $sbsanitize->sTrim($row['randkey']);
				// --- Le slug se calcule sur le titre NON echappe : sbSearchTitle()
				// --- passe par htmlSpecialChars et un "&" deviendrait "amp" dans
				// --- l'URL. Le slug n'est que decoratif, la cle porte le routage.
				$slug = $sbsanitize->stripTags(strtolower(sbRewriteString($sbsanitize->displayLang($row['title'], $lang))));
				$host = sbSearchHostFor('sbdownload', $row['id']);
				$results[] = array(
					'title'   => sbSearchTitle($row['title']),
					'excerpt' => sbSearchExcerpt($row['description'], $terms),
					'url'     => sbGetSeoUrl("index.php?p=download&k=$key", "download/item/$key/$slug", false),
					'host'    => ($host) ? $host['label'] : false,
				);
			}
		}
		return $results;
	}
}

?>
