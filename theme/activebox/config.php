<?php
/**
 * Theme Name: activebox
 * Description: Activebox theme
 * Version: 0.1.1
 * Author: Kamal Chaneman
 * Author_URI: http://kamalchaneman.com/activebox/
 * Licence: Creative Commons Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0)
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

$theme['view'][0] = 'index';
$theme['view'][1] = 'index-contact';
$theme['view'][2] = 'page-404';

// ---------------------------------
// --- Possibility to insert SLIDER
// ---------------------------------
// Comment line to disable option
$theme['headpage'][0] = true;

// ---------------------------------
// --- Various informations
// ---------------------------------
$theme['slogan'] = "[CS name=sbuser href_class=headerlogin menu=li menu_class=mymenuclass]";

// ---------------------------------
// --- Special blocks
// ---------------------------------
//$theme['blocks'][0]['unique'] = '';
