<?php
/**
 * Theme Name: Saxo
 * Description: Piccolo theme
 * Version: 0.1.1
 * Author: Nathan Brown
 * Author URI: https://wegraphics.net/demo/piccolo/
 * Licence: Creative Commons Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

$theme['view'][0] = 'index';
$theme['view'][1] = 'index-title';
$theme['view'][2] = 'index-left-sidebar';
$theme['view'][3] = 'index-right-sidebar';
$theme['view'][4] = 'index-contact';
$theme['view'][5] = 'page-404';

// ---------------------------------
// --- Possibility to insert SLIDER
// ---------------------------------
// Comment line to disable option
$theme['headpage'][0] = true;

// ---------------------------------
// --- Various informations
// ---------------------------------
$theme['slogan'] = "[CS name=sbuser href_class=headerlogin]";

// ---------------------------------
// --- Special blocks
// ---------------------------------
//$theme['blocks'][0]['unique'] = '';
