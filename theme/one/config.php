<?php
/**
 * Theme Name: One
 * Description: Theme Pink Rio
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
$theme['view'][1] = 'sidebar-left';
$theme['view'][2] = 'sidebar-right';
$theme['view'][3] = 'page-404';

// ---------------------------------
// --- Possibility to insert SLIDER
// ---------------------------------
// Comment line to disable option
$theme['headpage'][0] = true;