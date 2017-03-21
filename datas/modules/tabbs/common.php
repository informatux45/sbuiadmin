<?php
/**
 * Plugin Name: SBUIADMIN TABBS
 * Description: Jquery TABS
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
$module['name']        = 'SbMagic TABBS';
$module['dirname']     = basename(dirname(__FILE__));
$module['version']     = '0.1.1';
$module['description'] = "Jquery TABS";
$module['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$module['tables']['tabss']     = _AM_DB_PREFIX . "sb_tabbs";
$module['tables']['tabbstab']  = _AM_DB_PREFIX . "sb_tabbs_tab";
$module['tables']['tabbssett'] = _AM_DB_PREFIX . "sb_tabbs_settings";
// -------------------------------------------------

?>