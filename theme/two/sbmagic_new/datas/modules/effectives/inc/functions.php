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
 * Get Type Media to see
 * type		string		$param type
 * url		string		$param url
 * return HTML
 */
function insert_sbeffectives_medias($param = '') {
	global $sbsanitize;

	$type  = $sbsanitize->sTrim($param['type']);
	$url   = $sbsanitize->sTrim($param['url']);
	$title = $sbsanitize->displayLang($param['title'], $_SESSION['lang']);
	$icon  = $param['media'];
	
	// --- Initialization
	$media_to_show = '';
	
	switch($type) {
		case "video":
			$media_to_show .= '<a href="' . $url . '" target="_blank">';
			$media_to_show .= '<img src="' . SB_MODULES_URL . 'effectives/inc/images/media_video.jpg" title="' . $title . '" />';
			$media_to_show .= '</a>';
		break;
		case "youtube":
			if ($icon) {
				$media_to_show .= '<a class="media-icon" target="_blank" href="https://www.youtube.com/embed/' . $url . '" data-title="' . $title . '">';
				$media_to_show .= '<img src="' . SB_MODULES_URL . 'effectives/inc/images/media_video_icon.png" title="' . $title . '" />';
				$media_to_show .= '</a>';
			} else
				$media_to_show .= '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $url . '" frameborder="0" allowfullscreen></iframe>';
		break;
		case "photo":
			if ($icon) {
				$media_to_show .= '<a class="media-icon" href="' . _AM_MEDIAS_URL . '/' . $url . '" data-title="' . $title . '" data-lightbox="' . $title . '">';
				$media_to_show .= '<img src="' . SB_MODULES_URL . '/effectives/inc/images/media_photo_icon.png" title="' . $title . '" />';
				$media_to_show .= '</a>';
			} else {
				$media_to_show .= '<a href="' . _AM_MEDIAS_URL . '/' . $url . '" data-title="' . $title . '" data-lightbox="Medias">';
				$media_to_show .= '<img src="' . _AM_MEDIAS_URL . '/' . $url . '" title="' . $title . '" />';
				$media_to_show .= '</a>';
			}
		break;
		case "pdf":
			if ($icon) {
				$media_to_show .= '<a class="media-icon" target="_blank" href="' . _AM_MEDIAS_URL . '/' . $url . '">';
				$media_to_show .= '<img src="' . SB_MODULES_URL . '/effectives/inc/images/media_pdf_icon.png" title="' . $title . '" />';
				$media_to_show .= '</a>';
			} else {
				$media_to_show .= '<a href="' . _AM_MEDIAS_URL . '/' . $url . '" target="_blank">';
				$media_to_show .= '<img src="' . SB_MODULES_URL . 'effectives/inc/images/media_pdf.jpg" title="' . $title . '" />';									
				$media_to_show .= '</a>';
			}
		break;
	}
	
	return $media_to_show;
}


/**
 * Get A News item with ID
 * id		int			$param id
 * name		string		$param name (function name after 'shortcode_')
 * return HTML
 */
function shortcode_sbeffectives_item($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$item_html = '';
	// --- Tables
	$table = _AM_DB_PREFIX . 'sb_effectives';
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
			$url        = sbGetSeoUrl("index.php?p=effectives&op=article&id={$param['id']}", "effectives/article/{$param['id']}/$title_url", false);
			// --- Construct HTML
			$item_html .= '<link href="' . SB_MODULES_URL . 'effectives/inc/style_blocks.css" rel="stylesheet" />';
			$item_html .= '<div class="sbeffectives">';
			$item_html .= '<div class="sbeffectives-div-l">';
			$item_html .= '<a class="" href="' . $url . '">';
			$item_html .= '<img src="' . _AM_MEDIAS_URL . '/' . $item_info['image'] . '" alt="' . $title . '" style="width: 100%;">';
			$item_html .= '</a>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbeffectives-div-r">';
			$item_html .= '<h3>';
			$item_html .= $title;
			$item_html .= '</h3>';
			$item_html .= '<p class="sbeffectives-date">';
			$item_html .= sbConvertDate($item_info['date'], "FR");
			$item_html .= '</p>';
			$item_html .= '<p class="sbeffectives-p">';
			$item_html .= sbTruncate($desc_short, 300, '...');
			$item_html .= '</p>';
			$item_html .= '<span class="sbeffectives-link-item">';
			$item_html .= '<a href="' . $url . '">';
			$item_html .= _CMS_NEWS_READ_ITEM;
			$item_html .= '</a>';
			$item_html .= '</span>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbeffectives-clear-both"> </div>';
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
function shortcode_sbeffectives_latest($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$item_html = '';
	// --- Tables
	$table          = _AM_DB_PREFIX . 'sb_effectives';
	$table_category = _AM_DB_PREFIX . 'sb_effectives_category';
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
			$catname    = _CMS_EFFECTIVES_LATEST_ITEM . ' ' . _CMS_EFFECTIVES_OF . ' ' . $sbsanitize->displayText($sbsanitize->displayLang($item_info['catname'], $_SESSION['lang']), 'UTF-8');
			$url        = sbGetSeoUrl("index.php?p=effectives&op=article&id={$item_info['id']}", "effectives/article/{$item_info['id']}/$title_url", false);
			// --- Construct HTML
			$item_html .= '<link href="' . SB_MODULES_URL . 'effectives/inc/style_blocks.css" rel="stylesheet" />';
			$item_html .= '<div class="sbeffectives">';
			$item_html .= '<div class="sbeffectives-div-l">';
			$item_html .= '<a class="" href="' . $url . '">';
			$item_html .= '<img src="' . _AM_MEDIAS_URL . '/' . $item_info['image'] . '" alt="' . $title . '" style="width: 100%;">';
			$item_html .= '</a>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbeffectives-div-r">';
			$item_html .= '<h4 class="acenter">';
			$item_html .= $catname;
			$item_html .= '</h4>';
			$item_html .= '<h3>';
			$item_html .= $title;
			$item_html .= '</h3>';
			$item_html .= '<p class="sbeffectives-date">';
			$item_html .= sbConvertDate($item_info['date'], "FR");
			$item_html .= '</p>';
			$item_html .= '<p class="sbeffectives-p">';
			$item_html .= sbTruncate($desc_short, 300, '...');
			$item_html .= '</p>';
			$item_html .= '<span class="sbeffectives-link-item">';
			$item_html .= '<a href="' . $url . '">';
			$item_html .= _CMS_EFFECTIVES_READ_ITEM;
			$item_html .= '</a>';
			$item_html .= '</span>';
			$item_html .= '</div>';
			$item_html .= '<div class="sbeffectives-clear-both"> </div>';
			$item_html .= '</div>';
			
			return $item_html;
		
		} else {
			// --- Item inactive
			return _CMS_EFFECTIVES_ITEMINACTIVE;
		}
		
	} else {
		// --- Item not found
		return _CMS_EFFECTIVES_ITEMNOTFOUND;
	}
}

?>