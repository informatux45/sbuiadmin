<?php
/**
 * Plugin Name: SBUIADMIN DOWNLOAD
 * Description: Gestionnaire de boutons de téléchargement de fichiers
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
function shortcode_sbdownload($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$item_html = '';
	$lang      = isset($_SESSION['lang']) ? $_SESSION['lang'] : '';
	// --- Id : toujours un entier, il vient du texte d'un contenu
	$did       = isset($param['id']) ? intval($param['id']) : 0;
	if ($did <= 0) return false;
	// --- Tables
	$table = _AM_DB_PREFIX . 'sb_download';
	// --- SQL Download
	$query_item   = "SELECT * FROM $table WHERE id = '$did' AND active = '1'";
	$request_item = $sbsql->query($query_item);
	$item_info    = $sbsql->assoc($request_item);
	// --- Check if download exists
	if ($item_info) {
		// --- Check if new is active
		if ($item_info['active']) {
			// --- Include CSS
			?>
				<style>
				<?php include SB_MODULES_DIR . 'download' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'style.css'; ?>
				</style>
			<?php
			// --- Initialization
			$title        = $sbsanitize->displayText($sbsanitize->displayLang($item_info['title'], $lang), 'UTF-8');
			$title_url    = $sbsanitize->stripTags(strtolower(sbRewriteString($title)));
			$filename     = $sbsanitize->displayText($sbsanitize->displayLang($item_info['filename'], $lang), 'UTF-8');
			$size         = $sbsanitize->displayText($sbsanitize->displayLang($item_info['size'], $lang), 'UTF-8');
			$downloaded   = $sbsanitize->displayText($sbsanitize->displayLang($item_info['downloaded'], $lang), 'UTF-8');
			$key          = $sbsanitize->sTrim($item_info['randkey']);
			$download_url = sbGetSeoUrl("index.php?p=download&k=$key", "download/item/$key/$title_url", false);
			//$url        = sbGetSeoUrl("index.php?p=news&op=article&id={$param['id']}", "news/article/{$param['id']}/$title_url", false);
			// --- Construct HTML
			// <a href="https://informatux.com/index.php?id=download&amp;f=phpsitemapng-1.5.3.zip"><button id="glow" class="dlmbtn"><span class="divider"><img src="https://informatux.com/plugins/mod1fy_dlmanager/assets/css/download.png"></span><span class="file">phpsitemapng-1.5.3.zip</span><br><span class="count"><span style="padding-right:2px;color:#8F8DD8;">▼</span>86</span><span class="size">38 KB</span></button></a>
			$item_html .= '<a href="' . $download_url . '">';
				$item_html .= '<button id="glow" class="dlmbtn">';
					$item_html .= '<span class="divider">';
					$item_html .= '<img src="'.SB_MODULES_URL . 'download' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'download.png">';
					$item_html .= '</span>';
					$item_html .= '<span class="file">'.$title.'</span>';
					$item_html .= '<br>';
					$item_html .= '<span class="count"><span style="padding-right: 2px; color: #8F8DD8;"><i class="fa fa-download"></i></span>'.$downloaded.'</span>';
					$item_html .= '<span class="size">'.$size.'</span>';
				$item_html .= '</button>';
			$item_html .= '</a>';
			
			//$item_html .= '<link href="' . SB_MODULES_URL . 'news/inc/style_blocks.css" rel="stylesheet" />';
			//$item_html .= '<div class="sbnews">';
			//$item_html .= '<div class="sbnews-div-l">';
			//$item_html .= '<a class="" href="' . $url . '">';
			//$item_html .= '<img src="' . _AM_MEDIAS_URL . '/' . $item_info['image'] . '" alt="' . $title . '" style="width: 100%;">';
			//$item_html .= '</a>';
			//$item_html .= '</div>';
			//$item_html .= '<div class="sbnews-div-r">';
			//$item_html .= '<h3>';
			//$item_html .= $title;
			//$item_html .= '</h3>';
			//$item_html .= '<p class="sbnews-date">';
			//$item_html .= sbConvertDate($item_info['date'], "FR");
			//$item_html .= '</p>';
			//$item_html .= '<p class="sbnews-p">';
			//$item_html .= sbTruncate($desc_short, 300, '...');
			//$item_html .= '</p>';
			//$item_html .= '<span class="sbnews-link-item">';
			//$item_html .= '<a href="' . $url . '">';
			//$item_html .= _CMS_DOWNLOAD_READ_ITEM;
			//$item_html .= '</a>';
			//$item_html .= '</span>';
			//$item_html .= '</div>';
			//$item_html .= '<div class="sbnews-clear-both"> </div>';
			//$item_html .= '</div>';
			
			return $item_html;
		
		} else {
			// --- Item inactive
			return false;
		}
		
	} else {
		// --- Item not found
		return false;
	}
}

?>