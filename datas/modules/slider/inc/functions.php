<?php
/**
 * Plugin Name: SBUIADMIN SLIDER
 * Description: Gestionnaire de slider / carousel
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 * File: functions.php
 */

// Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

function shortcode_sbslider($param = '') {
	global $sbsanitize, $sbsql;
	// --- Tables
	$table_slider = _AM_DB_PREFIX . 'sb_slider';
	$table_photos = _AM_DB_PREFIX . 'sb_slider_photos';
	// --- SQL Slider
	$query_slider   = "SELECT * FROM $table_slider WHERE id = '{$param['id']}'";
	$request_slider = $sbsql->query($query_slider);
	$slider_config  = $sbsql->assoc($request_slider);
	// --- Check if slider is active
	if ($slider_config['active']) {
		// --- Sql photos	
		$query_photos   = "SELECT * FROM $table_photos WHERE active = '1' AND sid = '{$param['id']}' ORDER BY sort ASC";
		$request_photos = $sbsql->query($query_photos);
		$photos         = $sbsql->toarray($request_photos);
	
		// --- Slider HTML Construct
		$slider_html  = '';
		// --- Scripts loading
		if ($slider_config['jquery']) $slider_html .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>';
		$slider_html .= '<script src="' . SB_MODULES_URL . 'slider/inc/jquery.bxslider.min.js"></script>';
		if ($slider_config['video']) $slider_html .= '<script src="' . SB_MODULES_URL . 'slider/inc/plugins/jquery.fitvids.js"></script>';
		$slider_html .= '<link href="' . SB_MODULES_URL . 'slider/inc/jquery.bxslider.css" rel="stylesheet" />';
		// --- Photos list
		$slider_html .= '<ul class="sbslider'.$param['id'].'">';
		foreach($photos as $row) {
			if ($row['type'] == 'video')
				$slider_html .= '<li>' . $sbsanitize->displayText($row['photo'], 'UTF-8', 0, 1) . '</li>';
			else	
				$slider_html .= '<li><img src="' . _AM_MEDIAS_URL . DIRECTORY_SEPARATOR . $row['photo'] . '" title="' . $sbsanitize->displayLang(sb_utf8_encode($row['title'])) . '" /></li>';
		}
		$slider_html .= '</ul>';
		// --- Slider options
		$slider_html .= "<script type='text/javascript'>
						  $('.sbslider".$param['id']."').bxSlider({
							auto: ".slider_showtrueorfalse($slider_config['auto']).",
							randomStart: ".slider_showtrueorfalse($slider_config['randomstart']).",
							mode: '". $slider_config['mode'] ."',
							speed: ". $slider_config['speed'] .",
							captions: ".slider_showtrueorfalse($slider_config['captions']).",
							slideMargin: ".$slider_config['slidemargin'].",
							adaptiveHeight: ".slider_showtrueorfalse($slider_config['adaptiveheight']).",
							adaptiveHeightSpeed: ".$slider_config['adaptiveheightspeed'].",
							responsive: ".slider_showtrueorfalse($slider_config['responsive']).",
							useCSS: ".slider_showtrueorfalse($slider_config['usecss']).",
							preloadImages: '".$slider_config['preloadimages']."',
							controls: ".slider_showtrueorfalse($slider_config['controls']).",
							autoControls: ".slider_showtrueorfalse($slider_config['autocontrols']).",
							pause: ".$slider_config['pause'].",
							autoHover: ".slider_showtrueorfalse($slider_config['autohover']).",
							video: ".slider_showtrueorfalse($slider_config['video']).",
							pager: ".slider_showtrueorfalse($slider_config['pager']).",
							pagerType: '".$slider_config['pagertype']."',
						  });
						 </script>";
		// --- Return HTML construct
		return $slider_html;

	} else {
		// --- Slider inactive
		//return "Slider inactive";
	}
}

function slider_showtrueorfalse($config) {
	return ($config) ? 'true' : 'false';
}

?>