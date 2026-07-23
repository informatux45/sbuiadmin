<?php
/**
 * Plugin Name: SBUIADMIN FAQ
 * Description: Foire Aux Questions
 * Version: 1.0
 * Author: INFORMATUX
 * Author URI: //www.informatux.com/
 * File: functions.php
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

/**
 * Get FAQ questions/answers
 * id		int			$param id (optional, sb_faq_category id - all categories if empty)
 * name		string		$param name (function name after 'shortcode_')
 * return HTML
 */
function shortcode_sbfaq($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$item_html = '';
	// --- Tables
	$table = _AM_DB_PREFIX . 'sb_faq';

	// --- SQL FAQ
	$where = "active = '1'";
	if (!empty($param['id'])) $where .= " AND category = '" . intval($param['id']) . "'";

	$query   = "SELECT question, response FROM $table WHERE $where ORDER BY sort ASC";
	$request = $sbsql->query($query);
	$faqs    = $sbsql->toarray($request);

	// --- Check if FAQ exists
	if ($faqs) {
		// --- Construct HTML
		$item_html .= '<link href="' . SB_MODULES_URL . 'faq/inc/faq.css" rel="stylesheet" />';
		$item_html .= '<div class="sbfaq">';
		foreach($faqs as $row) {
			$question = $sbsanitize->displayText($sbsanitize->displayLang($row['question'], $_SESSION['lang']), 'UTF-8');
			$response = $sbsanitize->displayText($sbsanitize->displayLang($row['response'], $_SESSION['lang']), 'UTF-8');
			$item_html .= '<details class="sbfaq-item">';
			$item_html .= '<summary class="sbfaq-question">' . $question . '</summary>';
			$item_html .= '<div class="sbfaq-response">' . sbGetShortcode($response) . '</div>';
			$item_html .= '</details>';
		}
		$item_html .= '</div>';

		return $item_html;

	} else {
		// --- Item not found
		return _CMS_FAQ_ITEMNOTFOUND;
	}
}

?>
