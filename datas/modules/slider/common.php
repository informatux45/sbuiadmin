<?php
/**
 * Plugin Name: SBUIADMIN Slider
 * Description: Générateur de slider / carousel
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

// -------------------------------------------------
// --- Global MODULE
// -------------------------------------------------
$modpage['name']        = 'SBUIADMIN SLIDER';
$modpage['dirname']     = basename(dirname(__FILE__));
$modpage['version']     = '0.1.1';
$modpage['description'] = "Générateur de slider / carousel";
$modpage['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$modpage['tables']['slider'] = _AM_DB_PREFIX . "sb_slider";
// -------------------------------------------------

?>
