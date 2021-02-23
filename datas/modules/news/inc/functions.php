<?php
/**
 * Plugin Name: SBUIADMIN NEWS
 * Description: Gestionnaire d'articles
 * Version: 0.1.2
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 * File: functions.php
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

/**
 * Get A News item with ID
 * id		int			$param id
 * name		string		$param name (function name after 'shortcode_')
 * return HTML
 */
function shortcode_sbnews_item($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$item_html = '';
	// --- Tables
	$table = _AM_DB_PREFIX . 'sb_news';
	// --- SQL Slider
	$query_item   = "SELECT * FROM $table WHERE id = '{$param['id']}' AND active = '1'";
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
			$url        = sbGetSeoUrl("index.php?p=news&op=article&id={$param['id']}", "news/article/{$param['id']}/$title_url", false);
			$media_dir  = str_replace("../", "", _AM_MEDIAS_DIR);
			// --- Construct HTML
			$item_html .= '<link href="' . SB_MODULES_URL . 'news/inc/style_blocks.css" rel="stylesheet" />';
			$item_html .= '<div class="sbnews">';
			$item_html .= '<div class="sbnews-div-l">';
			$item_html .= '<a class="" href="' . $url . '">';
			$item_html .= '<img src="' . _AM_MEDIAS_URL . '/' . $item_info['image'] . '" alt="' . $title . '" style="width: 100%;">';
			$item_html .= '</a>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbnews-div-r">';
			$item_html .= '<h3>';
			$item_html .= $title;
			$item_html .= '</h3>';
			$item_html .= '<p class="sbnews-date">';
			$item_html .= sbConvertDate($item_info['date'], "FR");
			$item_html .= '</p>';
			$item_html .= '<p class="sbnews-p">';
			$item_html .= sbTruncate($desc_short, 300, '...');
			$item_html .= '</p>';
			$item_html .= '<span class="sbnews-link-item">';
			$item_html .= '<a href="' . $url . '">';
			$item_html .= _CMS_NEWS_READ_ITEM;
			$item_html .= '</a>';
			$item_html .= '</span>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbnews-clear-both"> </div>';
			$item_html .= '</div>';
			
			return $item_html;
		
		} else {
			// --- Item inactive
			return _CMS_NEWS_ITEMINACTIVE;
		}
		
	} else {
		// --- Item not found
		return _CMS_NEWS_ITEMNOTFOUND;
	}
}

/**
 * Get the lastest news of category
 * id		int			$param id
 * name		string		$param name (function name after 'shortcode_')
 * return HTML
 */
function shortcode_sbnews_latest($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$item_html = '';
	// --- Tables
	$table          = _AM_DB_PREFIX . 'sb_news';
	$table_category = _AM_DB_PREFIX . 'sb_news_category';
	// --- SQL Request
	//$query_item   = "SELECT t1.*, t2.title as catname FROM $table AS t1 WHERE catid = '{$param['id']}'";
	$query_item   = "SELECT t1.*, t2.title AS catname
					 FROM $table AS t1
					 LEFT JOIN $table_category AS t2 ON (t1.catid = t2.id)
					 WHERE t1.catid = '{$param['id']}' AND t1.active = '1'
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
			$catname    = _CMS_NEWS_LATEST_ITEM . ' ' . _CMS_NEWS_OF . ' ' . $sbsanitize->displayText($sbsanitize->displayLang($item_info['catname'], $_SESSION['lang']), 'UTF-8');
			$url        = sbGetSeoUrl("index.php?p=news&op=article&id={$item_info['id']}", "news/article/{$item_info['id']}/$title_url", false);
			// --- Construct HTML
			$item_html .= '<link href="' . SB_MODULES_URL . 'news/inc/style_blocks.css" rel="stylesheet" />';
			$item_html .= '<div class="sbnews">';
			$item_html .= '<div class="sbnews-div-l">';
			$item_html .= '<a class="" href="' . $url . '">';
			$item_html .= '<img src="' . _AM_MEDIAS_URL . '/' . $item_info['image'] . '" alt="' . $title . '" style="width: 100%;">';
			$item_html .= '</a>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbnews-div-r">';
			$item_html .= '<h4 class="acenter">';
			$item_html .= $catname;
			$item_html .= '</h4>';
			$item_html .= '<h3>';
			$item_html .= $title;
			$item_html .= '</h3>';
			$item_html .= '<p class="sbnews-date">';
			$item_html .= sbConvertDate($item_info['date'], "FR");
			$item_html .= '</p>';
			$item_html .= '<p class="sbnews-p">';
			$item_html .= sbTruncate($desc_short, 300, '...');
			$item_html .= '</p>';
			$item_html .= '<span class="sbnews-link-item">';
			$item_html .= '<a href="' . $url . '">';
			$item_html .= _CMS_NEWS_READ_ITEM;
			$item_html .= '</a>';
			$item_html .= '</span>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbnews-clear-both"> </div>';
			$item_html .= '</div>';
			
			return $item_html;
		
		} else {
			// --- Item inactive
			return _CMS_NEWS_ITEMINACTIVE;
		}
		
	} else {
		// --- Item not found
		return _CMS_NEWS_ITEMNOTFOUND;
	}
}

/**
 * Get the lastest news of a category / of all news
 * id		int			$param id (category ID)
 * count	int			$param count (Number of news)
 * name		string		$param name (function name after 'shortcode_')
 * return HTML
 */
function shortcode_sbnews_blocks_recent($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$items_html = '';
	$catid      = (isset($param['id'])) ? intval($param['id']) : false;
	$limit      = (isset($param['count'])) ? intval($param['count']) : 3;
	$truncate   = (isset($param['truncate'])) ? intval($param['truncate']) : 40;
	// --- Tables
	$table          = _AM_DB_PREFIX . 'sb_news';
	$table_category = _AM_DB_PREFIX . 'sb_news_category';
	// --- SQL Request
	//$query_item   = "SELECT t1.*, t2.title as catname FROM $table AS t1 WHERE catid = '{$param['id']}'";
	$query_item  = "SELECT t1.*, t2.title AS catname
					FROM $table AS t1
					LEFT JOIN $table_category AS t2 ON (t1.catid = t2.id) ";
	if ($catid)
		$query_item .= "WHERE t1.catid = '$catid' AND t1.active = '1' ";
	else
		$query_item .= "WHERE t1.active = '1' ";
	$query_item .= "ORDER BY date DESC LIMIT $limit";
	$request_item = $sbsql->query($query_item);
	$items_info   = $sbsql->toarray($request_item);
	
	// --- Check if news exists
	if ($items_info) {
		// --- Construct HTML
		$items_html .= '<link href="' . SB_MODULES_URL . 'news/inc/style_items_blocks.css" rel="stylesheet" />';
		$items_html .= '<div class="sbnews-recent-post sbnews-group">';
		
		foreach($items_info as $row) {
		
			// --- Check if new is active
			if ($row['active']) {
				// --- Initialization
				$title       = $sbsanitize->displayText($sbsanitize->displayLang($row['title'], $_SESSION['lang']), 'UTF-8');
				$desc_short  = $sbsanitize->displayText($sbsanitize->displayLang($row['desc_short'], $_SESSION['lang']), 'UTF-8');
				$title_url   = $sbsanitize->stripTags(strtolower(sbRewriteString($title)));
				$catname     = _CMS_NEWS_LATEST_ITEM . ' ' . _CMS_NEWS_OF . ' ' . $sbsanitize->displayText($sbsanitize->displayLang($row['catname'], $_SESSION['lang']), 'UTF-8');
				$url         = sbGetSeoUrl("index.php?p=news&op=article&id={$row['id']}", "news/article/{$row['id']}/$title_url", false);
				$media_dir   = str_replace("../", "", _AM_MEDIAS_DIR);
				// --- Construct HTML
				$items_html .= '<div class="sbnews-hentry-post sbnews-group">';
				$items_html .= '<div class="sbnews-thumb-img">';
				$items_html .= '<img src="'.SB_URL.'thumb.php?src=' . $media_dir . '/' . $row['image'].'&size=55x&zc=1" alt="'.$title.'" title="'.$title.'"></div>';
				$items_html .= '<div class="text">';
				$items_html .= '<a href="'.$url.'" title="'.$title.'" class="title">'.sbTruncate($sbsanitize->stripTags($title), $truncate, "...").'</a>';
				$items_html .= '<a class="sbnews-read-more" href="'.$url.'">'._CMS_NEWS_READ_ITEM.'</a>';
				$items_html .= '</div>'; // thumb-img
				$items_html .= '</div>'; // hentry-post group
			}

		}
		
		// --- Construct HTML
		$items_html .= '</div>'; // Recent-post group
		
		return $items_html;
		
	} else {
		// --- Items not found
		return _CMS_NEWS_ITEMNOTFOUND;
	}
}

?>
