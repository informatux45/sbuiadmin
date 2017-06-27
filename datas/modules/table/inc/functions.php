<?php
/**
 * Plugin Name: SBUIADMIN TABLE
 * Description: Tableaux Dynamiques
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
 * Text shortcode		[CS id=1 name=sbtable]
 * return HTML
 */
function shortcode_sbtable($param = '') {
	global $sbsanitize, $sbsql;

	// --- Initialization
	$html_table = '';
	$id         = intval($param['id']);
	// --- Tables
	$table_table     = _AM_DB_PREFIX . "sb_table";
	$table_structure = _AM_DB_PREFIX . "sb_table_structure";
	$table_datas     = _AM_DB_PREFIX . "sb_table_datas";
	// --- SQL TABLE
	$query = "SELECT t2.tid, t2.content FROM $table_table AS t1
			  LEFT JOIN $table_datas AS t2 ON (t1.id = t2.tid)
			  WHERE t1.id = '$id' AND t1.active = '1'
			  ORDER BY t2.sort ASC
			 ";
	$request = $sbsql->query($query);
	$table   = $sbsql->toarray($request);
	
	// --- Check if news exists
	if ($table) {
		// --- Get Config options Table
		$query_config      = "SELECT type FROM $table_table WHERE id = '$id'";
		$request_config    = $sbsql->query($query_config);
		$result_config     = $sbsql->object($request_config);
		$responsive_config = $sbsanitize->sTrim($result_config->type);
		// --- Construct HTML
		switch($responsive_config) {
			default:
			case "option1":
				$html_table .= '<link href="' . SB_MODULES_URL . 'table/inc/option1/responsive-table.css" rel="stylesheet" />';
				$class_table = 'responsive-table';
			break;
			case "option2":				
				$html_table .= '<link type="text/css" media="screen" rel="stylesheet" href="' . SB_MODULES_URL . 'table/inc/option2/responsive-tables.css" />';
				$html_table .= '<script type="text/javascript" src="' . SB_MODULES_URL . 'table/inc/option2/responsive-tables.js"></script>';
				$class_table = 'responsive';
			break;
		}
		// --- Table
		$html_table .= '<table class="' . $class_table . '">';
		// --- Thead (header)
		$html_table .= '<thead>';
		$html_table .= '<tr>';

		// --- SQL Request
		$query_structure   = "SELECT * FROM $table_structure WHERE tid = '$id' AND active = 1 ORDER BY sort ASC";
		$request_structure = $sbsql->query($query_structure);
		$result_structure  = $sbsql->toarray($request_structure);

		$columns      = [];
		$field_type   = [];
		$field_target = [];
		foreach($result_structure as $col) {
			$theaders       = $sbsanitize->displayLang($col['title']);
			$columns[]      = $theaders;
			$field_type[]   = $col['field_type'];
			$field_target[] = $col['field_target'];
			$html_table .= '<th scope="col">' . $theaders . '</th>';
		}
		// --- END Thead
		$html_table .= '</tr>';
		$html_table .= '</thead>';
		// --- Tbody (Datas)
		$html_table .= '<tbody>';
		// --- Loop on Datas Table
		foreach($table as $key => $row) {
			// --- Row
			$html_table .= '<tr class="row'.$key.'">';
			// --- Initialize Data Row
			$data_table = "";
			for($i = 0; $i < count($columns); $i++) {
				// --- First column or not
				$table_tag = ($i == 0) ? "th" : "td";
				// --- Correct column name to compare
				$columns[$i] = $sbsanitize->rewriteString($columns[$i], true);
				// --- Get the right value if column is unknown 
				$data_table = ($table_tag == 'th') ?
								'<' . $table_tag . ' scope="row" class="' . $columns[$i] . '"> - </' . $table_tag . '>' :
								'<' . $table_tag . ' data-title="' . $columns[$i] . '" class="' . $columns[$i] . '"> - </' . $table_tag . '>' ;
	
				foreach(json_decode($row['content'], true) as $k => $val) {
					// Input name
					$input = $val['i'];
					// Check if column is into the JSON Datas
					if ($columns[$i] == $input) {
						// Json table (Value)
						$value = $sbsanitize->displayText($val['v']);
						// --- Define Target behavior
						$data_fancy = "";
						switch($field_target[$i]) {
							default:
								$data_target = '';
							break;
							case "blank":
								$data_target = ' target="blank"';
							break;
							case "lightbox":
								$data_target = ' data-lightbox="image-' . $val['i'] . '-' . $key . '" data-title=""';
							break;
							case "lightbox_fancy":
								$data_fancy = ' fancybox';
							break;
						}
						// --- 
						switch($field_type[$i]) {
							default: $data_value = $value; break;
							case "photo":
								if ($data_target != '' || isset($data_fancy))
									$data_value = "<a class='$data_fancy' href='"._AM_MEDIAS_URL."$value' title=''$data_target><img class='table_image' src='"._AM_MEDIAS_URL."$value' alt='' /></a>";
								else
									$data_value = "<img class='table_image' src='"._AM_MEDIAS_URL."$value' alt='' />";
							break;
							case "textarea":
								$data_value = nl2br($value);
							break;
							case "link_image":
								$data_value = '<a class="icon-large icon-picture' . $data_fancy . '" href="'.$value.'" title=""' . $data_target . '></a>';
							break;
							case "link_video":
								$data_value = '<a class="icon-large icon-facetime-video' . $data_fancy . '" href="'.$value.'" title=""' . $data_target . '></a>';
							break;
							case "link_file":
								$data_value = '<a class="icon-large icon-file' . $data_fancy . '" href="'.$value.'" title=""' . $data_target . '></a>';
							break;
						}
						$data_table   = ($table_tag == 'th') ?
										'<' . $table_tag . ' scope="row" class="' . $columns[$i] . '">' . $data_value . '</' . $table_tag . '>' :
										'<' . $table_tag . ' data-title="' . $columns[$i] . '" class="' . $columns[$i] . '">' . $data_value . '</' . $table_tag . '>' ;
					}
				}
				
				// --- Increase Data Table
				$html_table .= $data_table;
				
			}
			// --- END Row
			$html_table .= '</tr>';
		}
		// --- END Tbody		
		$html_table .= '</tbody>';
		// --- END Table
		$html_table .= '</table>'; // END .responsive-table
		
		return $html_table;
		
	} else {
		// --- Item not found
		return _CMS_TABLE_ITEMNOTFOUND;
	}
}

?>