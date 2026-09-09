<?php
/**
 * Plugin Name: SBUIADMIN GALLERY
 * Description: Gestionnaire de galeries photos / videos
 * Version: 0.1.1
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
function shortcode_sbgallery($param = '') {
	global $sbsanitize, $sbsql;

	// --- Ce module n'a pas de contrôleur front : header.php inclut ce
	// --- fichier pour TOUS les modules, mais jamais le fichier de langue.
	// --- On le charge donc ici, sinon _CMS_GALLERY_ITEMNOTFOUND est une
	// --- constante indéfinie (Error fatale depuis PHP 8.0).
	if (!defined('_CMS_GALLERY_ITEMNOTFOUND')) {
		$sblang_gallery = (SBLANG && (!isset($_SESSION['lang']) || $_SESSION['lang'] != 'en')) ? SBLANG : 'en_US';
		$lang_path      = SB_MODULES_DIR . 'gallery' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sblang_gallery . '.php';
		if (file_exists($lang_path)) include_once($lang_path);
	}

	// --- Initialization
	$item_html = '';
	$lang      = isset($_SESSION['lang']) ? $_SESSION['lang'] : '';
	// --- Id de galerie : toujours un entier, il vient du texte d'un contenu
	$gid       = isset($param['id']) ? intval($param['id']) : 0;
	if ($gid <= 0) return defined('_CMS_GALLERY_ITEMNOTFOUND') ? _CMS_GALLERY_ITEMNOTFOUND : '';
	// --- Tables
	$table = _AM_DB_PREFIX . 'sb_gallery';
	$table_photos = _AM_DB_PREFIX . 'sb_gallery_photos';
	// --- SQL Gallery
	$query_item   = "SELECT t1.*, t2.title AS image_name, t2.photo AS image_photo, t2.type AS image_type
					 FROM $table AS t1
					 LEFT JOIN $table_photos AS t2 ON (t2.gid = t1.id)
					 WHERE t1.id = '$gid' AND t2.active = '1' AND t1.active = '1'
					 ORDER BY t2.sort ASC
					 ";
	$request_item = $sbsql->query($query_item);
	$item_infos   = $sbsql->toarray($request_item);
	// --- Check if news exists
	if ($item_infos) {
		// --- Check if new is active
		foreach($item_infos as $item_info) {
			// --- Initialization
			$title       = $sbsanitize->displayText($sbsanitize->displayLang($item_info['title'], $lang), 'UTF-8');
			$image_name  = $sbsanitize->displayText($sbsanitize->displayLang($item_info['image_name'], $lang), 'UTF-8');
			$image_photo = $item_info['image_photo'];
			$image_type  = $item_info['image_type'];
			$css         = $sbsanitize->displayText($item_info['css']);
			$template    = $sbsanitize->displayText($item_info['template']);
			$javascript  = $sbsanitize->displayText($item_info['javascript']);
			// --- Load image
			$template    = @preg_replace("/{IMAGE}/", _AM_MEDIAS_URL . $image_photo, $template);
			$template    = @preg_replace("/{IMAGE_THUMB}/", SB_URL . 'thumb.php?src=' . _AM_MEDIAS_URL . $image_photo . '&size=150x150', $template);
			$template    = @preg_replace("/{IMAGE_NAME}/", $image_name, $template);
			// -----------------
			//{$smarty.const.SB_URL}thumb.php?src={$smarty._AM_MEDIAS_URL}{$image.image_photo}&size=200x200
			//$url_thumb  = sbGetSeoUrl("index.php?p=news&op=article&id={$param['id']}", "news/article/{$param['id']}/$title_url", false);
			//$media_dir  = str_replace("../", "", _AM_MEDIAS_DIR);
			// --- Construct HTML
			$item_html .= $template;
		}
		return $item_html;
		
	} else {
		// --- Item not found
		return defined('_CMS_GALLERY_ITEMNOTFOUND') ? _CMS_GALLERY_ITEMNOTFOUND : '';
	}
}