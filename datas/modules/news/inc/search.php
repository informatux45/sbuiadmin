<?php
/**
 * Module news : source de la recherche du site (module search, core)
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
* URL publique d'un article (meme construction que shortcode_sbnews_item()).
* @param	int		$id
* @param	string	$title	titre brut de la base
* @return	string
*/
if (!function_exists("sbSearchNewsUrl")) {
	function sbSearchNewsUrl($id, $title) {
		global $sbsanitize;

		$lang      = (isset($_SESSION['lang']) && $_SESSION['lang'] != '') ? $_SESSION['lang'] : 'fr';
		$id        = intval($id);
		$clean     = $sbsanitize->displayText($sbsanitize->displayLang($title, $lang), 'UTF-8');
		$title_url = $sbsanitize->stripTags(strtolower(sbRewriteString($clean)));

		return sbGetSeoUrl("index.php?p=news&op=article&id=$id", "news/article/$id/$title_url", false);
	}
}

/**
* ACTUALITES (titre + sous-titre + chapo + article)
* @return array resultats ('title', 'excerpt', 'url', 'host')
*/
if (!function_exists("sbSearchSource_news")) {
	function sbSearchSource_news($terms, $lang) {
		global $sbsql;
		$results = array();
		$where   = sbSearchWhere(array('title', 'subtitle', 'desc_short', 'desc_full'), $terms);
		$query   = "SELECT id, title, subtitle, desc_short, desc_full
					FROM " . sbSearchTable('sb_news') . "
					WHERE active = '1'" . $where . " ORDER BY date DESC";
		$request = $sbsql->query($query);
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				if (!sbSearchMatches(array($row['title'], $row['subtitle'], $row['desc_short'], $row['desc_full']), $terms)) continue;
				$excerpt = sbSearchExcerpt($row['desc_full'], $terms);
				if ($excerpt === '') $excerpt = sbSearchExcerpt($row['desc_short'], $terms);
				$results[] = array(
					'title'   => sbSearchTitle($row['title']),
					'excerpt' => $excerpt,
					'url'     => sbSearchNewsUrl($row['id'], $row['title']),
					'host'    => false,
				);
			}
		}
		return $results;
	}
}

/**
* Hotes de shortcodes : articles qui en contiennent (voir sbSearchHostIndex())
*/
if (!function_exists("sbSearchHosts_news")) {
	function sbSearchHosts_news($collect, array &$index) {
		global $sbsql, $sbsanitize;
		$lang    = (isset($_SESSION['lang']) && $_SESSION['lang'] != '') ? $_SESSION['lang'] : 'fr';
		$query   = "SELECT id, title, desc_short, desc_full
					FROM " . sbSearchTable('sb_news') . "
					WHERE active = '1' AND (desc_full LIKE '%[CS %' OR desc_short LIKE '%[CS %')";
		$request = $sbsql->query($query);
		$rows    = $sbsql->toarray($request);
		if ($rows) {
			foreach ($rows as $row) {
				$url   = sbSearchNewsUrl($row['id'], $row['title']);
				$label = $sbsanitize->displayText($sbsanitize->displayLang($row['title'], $lang), 'UTF-8');
				$collect($row['desc_full'] . ' ' . $row['desc_short'], $url, $label);
			}
		}
	}
}

?>
