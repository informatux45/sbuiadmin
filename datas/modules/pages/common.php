<?php
/**
 * Plugin Name: SBUIADMIN PAGES
 * Description: Gestionnaire de pages libres
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
$modpage['name']        = 'SbMagic PAGES';
$modpage['dirname']     = basename(dirname(__FILE__));
$modpage['version']     = '0.1.1';
$modpage['description'] = "Gestionnaire de pages libres";
$modpage['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$modpage['tables']['pages'] = _AM_DB_PREFIX . "sb_pages";
// -------------------------------------------------

?>