<?php
/**
 * Theme Name: Volton
 * Description: Bootstrap v3.3.1 mobile friendly layout
 * Version: 0.1.1
 * Author: templatemo.com
 * Author URI: http://www.templatemo.com/tm-441-volton
 * Licence: Creative Commons Attribution 4.0 International License
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

// --- Theme view for ADMIN
$theme['view'][0] = 'index';
$theme['view'][1] = 'index-contact';
$theme['view'][2] = '404';

// ---------------------------------
// --- Possibility to insert SLIDER
// ---------------------------------
// Comment line to disable option
$theme['headpage'][0] = true;

// ---------------------------------
// --- Various informations
// ---------------------------------
$theme['slogan'] = "";

// ---------------------------------
// --- Special blocks
// ---------------------------------
//$theme['blocks'][0]['unique'] = '';