<?php
/**
 * Plugin Name: SBUIADMIN TABBS
 * Description: Jquery TABS
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
 * Get A TABBS with ID
 * id		int			$param id
 * name		string		$param name (function name after 'shortcode_')
 * return HTML
 */
function shortcode_sbtabbs($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$item_html = '';
	// --- Tables
	$table     = _AM_DB_PREFIX . 'sb_tabbs';
	$table_tab = _AM_DB_PREFIX . 'sb_tabbs_tab';
	// --- SQL TABBS
	$query = "SELECT t2.title, t2.content
			  FROM $table AS t1
			  LEFT JOIN $table_tab AS t2 ON (t1.id = t2.tid)
			  WHERE t1.id = '{$param['id']}' AND t1.active = '1' AND t2.active = '1'
			  ORDER BY t2.sort ASC
			 ";
	$request = $sbsql->query($query);
	$tabbs   = $sbsql->toarray($request);

	// --- Check if news exists
	if ($tabbs) {
		// --- Initialization
		$title      = $sbsanitize->displayText($sbsanitize->displayLang($item_info['title'], $_SESSION['lang']), 'UTF-8');
		$desc_short = $sbsanitize->displayText($sbsanitize->displayLang($item_info['desc_short'], $_SESSION['lang']), 'UTF-8');
		$title_url  = $sbsanitize->stripTags(strtolower(sbRewriteString($title)));
		// --- Construct HTML
		$item_html .= '<link href="' . SB_MODULES_URL . 'tabbs/inc/responsive-tabs.css" rel="stylesheet" />';
		$item_html .= '<script type="text/javascript" src="' . SB_MODULES_URL . 'tabbs/inc/responsiveTabs.js"></script>';
		$item_html .= '<script>';
		$item_html .= 'jQuery(document).ready(function() {
						RESPONSIVEUI.responsiveTabs();
					   })';
		$item_html .= '</script>';
		$item_html .= '<div class="responsive-tabs">';
		// Show all the Tabs
		foreach($tabbs as $row) {
			// Onglet
			$item_html .= '<h2>';
			$item_html .= sbGetShortcode($sbsanitize->displayText($sbsanitize->displayLang($row['title'], $_SESSION['lang']), 'UTF-8'));
			$item_html .= '</h2>';
			// Contenu
			$item_html .= '<div>';
			$item_html .= sbGetShortcode($sbsanitize->displayText($sbsanitize->displayLang($row['content'], $_SESSION['lang']), 'UTF-8'));
			$item_html .= '</div>'; // END Div
		}

		$item_html .= '</div>'; // END .responsive-tabs
		
		return $item_html;
		
	} else {
		// --- Item not found
		return _CMS_TABBS_ITEMNOTFOUND;
	}
}

?>
