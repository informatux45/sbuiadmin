<?php
/**
 * Plugin Name: SbMagic EFFECTIFS
 * Description: Gestionnaire d'effectifs (equins)
 * Version: 0.1.1
 * Agency: Agence DOLLAR
 * Agency URI: //dollar.fr
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 * File: functions.php
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}


/**
 * Get A Graduates item with ID
 * id		int			$param id
 * name		string		$param name (function name after 'shortcode_')
 * return HTML
 */
function shortcode_sbgraduates_item($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$item_html = '';
	// --- Tables
	$table = _AM_DB_PREFIX . 'sb_news';
	// --- SQL Slider
	$query_item   = "SELECT * FROM $table WHERE id = '{$param['id']}'";
	$request_item = $sbsql->query($query_item);
	$item_info    = $sbsql->assoc($request_item);
	// --- Check if news exists
	if ($item_info) {
		// --- Check if new is active
		if ($item_info['active']) {
			// --- Initialization
			$title      = $sbsanitize->displayText($sbsanitize->displayLang($item_info['title'], $_SESSION['lang']), 'UTF-8');
			$desc_short = $sbsanitize->displayText($sbsanitize->displayLang($item_info['desc_short'], $_SESSION['lang']), 'UTF-8');
			$title_url  = $sbsanitize->stripTags(strtolower(sbRewriteString($title)));
			$url        = sbGetSeoUrl("index.php?p=graduates&op=article&id={$param['id']}", "graduates/article/{$param['id']}/$title_url", false);
			// --- Construct HTML
			$item_html .= '<link href="' . SB_MODULES_URL . 'news/inc/style_blocks.css" rel="stylesheet" />';
			$item_html .= '<div class="sbgraduates">';
			$item_html .= '<div class="sbgraduates-div-l">';
			$item_html .= '<a class="" href="' . $url . '">';
			$item_html .= '<img src="' . _AM_MEDIAS_URL . '/' . $item_info['image'] . '" alt="' . $title . '" style="width: 100%;">';
			$item_html .= '</a>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbgraduates-div-r">';
			$item_html .= '<h3>';
			$item_html .= $title;
			$item_html .= '</h3>';
			$item_html .= '<p class="sbgraduates-date">';
			$item_html .= sbConvertDate($item_info['date'], "FR");
			$item_html .= '</p>';
			$item_html .= '<p class="sbgraduates-p">';
			$item_html .= sbTruncate($desc_short, 300, '...');
			$item_html .= '</p>';
			$item_html .= '<span class="sbgraduates-link-item">';
			$item_html .= '<a href="' . $url . '">';
			$item_html .= _CMS_GRADUATES_READ_ITEM;
			$item_html .= '</a>';
			$item_html .= '</span>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbgraduates-clear-both"> </div>';
			$item_html .= '</div>';
			
			return $item_html;
		
		} else {
			// --- Item inactive
			return _CMS_GRADUATES_ITEMINACTIVE;
		}
		
	} else {
		// --- Item not found
		return _CMS_GRADUATES_ITEMNOTFOUND;
	}
}

/**
 * Get the lastest graduates of category
 * id		int			$param id
 * name		string		$param name (function name after 'shortcode_')
 * return HTML
 */
function shortcode_sbgraduates_latest($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$item_html = '';
	// --- Tables
	$table          = _AM_DB_PREFIX . 'sb_graduates';
	$table_category = _AM_DB_PREFIX . 'sb_graduates_category';
	// --- SQL Slider
	//$query_item   = "SELECT t1.*, t2.title as catname FROM $table AS t1 WHERE catid = '{$param['id']}'";
	$query_item   = "SELECT t1.*, t2.title AS catname
					 FROM $table AS t1
					 LEFT JOIN $table_category AS t2 ON (t1.catid = t2.id)
					 WHERE catid = '{$param['id']}'
					 ORDER BY date DESC LIMIT 1
					 ";
	$request_item = $sbsql->query($query_item);
	$item_info    = $sbsql->assoc($request_item);
	// --- Check if news exists
	if ($item_info) {
		// --- Check if new is active
		if ($item_info['active']) {
			// --- Initialization
			$title      = $sbsanitize->displayText($sbsanitize->displayLang($item_info['title'], $_SESSION['lang']), 'UTF-8');
			$desc_short = $sbsanitize->displayText($sbsanitize->displayLang($item_info['desc_short'], $_SESSION['lang']), 'UTF-8');
			$title_url  = $sbsanitize->stripTags(strtolower(sbRewriteString($title)));
			$catname    = _CMS_GRADUATES_LATEST_ITEM . ' ' . _CMS_GRADUATES_OF . ' ' . $sbsanitize->displayText($sbsanitize->displayLang($item_info['catname'], $_SESSION['lang']), 'UTF-8');
			$url        = sbGetSeoUrl("index.php?p=graduates&op=article&id={$item_info['id']}", "graduates/article/{$item_info['id']}/$title_url", false);
			// --- Construct HTML
			$item_html .= '<link href="' . SB_MODULES_URL . 'graduates/inc/style_blocks.css" rel="stylesheet" />';
			$item_html .= '<div class="sbgraduates">';
			$item_html .= '<div class="sbgraduates-div-l">';
			$item_html .= '<a class="" href="' . $url . '">';
			$item_html .= '<img src="' . _AM_MEDIAS_URL . '/' . $item_info['image'] . '" alt="' . $title . '" style="width: 100%;">';
			$item_html .= '</a>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbgraduates-div-r">';
			$item_html .= '<h4 class="acenter">';
			$item_html .= $catname;
			$item_html .= '</h4>';
			$item_html .= '<h3>';
			$item_html .= $title;
			$item_html .= '</h3>';
			$item_html .= '<p class="sbgraduates-date">';
			$item_html .= sbConvertDate($item_info['date'], "FR");
			$item_html .= '</p>';
			$item_html .= '<p class="sbgraduates-p">';
			$item_html .= sbTruncate($desc_short, 300, '...');
			$item_html .= '</p>';
			$item_html .= '<span class="sbgraduates-link-item">';
			$item_html .= '<a href="' . $url . '">';
			$item_html .= _CMS_GRADUATES_READ_ITEM;
			$item_html .= '</a>';
			$item_html .= '</span>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbgraduates-clear-both"> </div>';
			$item_html .= '</div>';
			
			return $item_html;
		
		} else {
			// --- Item inactive
			return _CMS_GRADUATES_ITEMINACTIVE;
		}
		
	} else {
		// --- Item not found
		return _CMS_GRADUATES_ITEMNOTFOUND;
	}
}

?>