<?php
/**
 * Theme Name: Pink Rio
 * Description: A Free Responsive HTML Template
 * Version: 0.1.1
 * Author: Yith
 * Author URI: https://yithemes.com/
 * Licence: Creative Commons Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

$theme['view'][0] = 'index';
$theme['view'][1] = 'index-left-sidebar';
$theme['view'][2] = 'index-right-sidebar';
$theme['view'][3] = 'index-contact';
$theme['view'][4] = 'boxed-full-width';
$theme['view'][5] = 'boxed-left-sidebar';
$theme['view'][6] = 'boxed-right-sidebar';
$theme['view'][7] = 'page-404';

// ---------------------------------
// --- Possibility to insert SLIDER
// ---------------------------------
// Comment line to disable option
$theme['headpage'][0] = true;

// ---------------------------------
// --- Special blocks
// ---------------------------------
//$theme['blocks'][0]['unique'] = '';