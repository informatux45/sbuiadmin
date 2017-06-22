<?php
/**
 * Theme Name: Editorial
 * Description: a blog/magazine-ish template built around a toggleable "locking" sidebar
 * Version: 0.1.1
 * Author: HTML5 UP
 * Author URI: html5up.net
 * Licence: Creative Commons Attribution 3.0 Unported
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

// --- Theme view for ADMIN
$theme['view'][0] = 'index';
$theme['view'][1] = 'index-title';
$theme['view'][2] = 'index-without-title';
$theme['view'][3] = 'index-contact';
$theme['view'][4] = 'page-404';

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