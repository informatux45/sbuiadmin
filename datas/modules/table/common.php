<?php
/**
 * Plugin Name: SBUIADMIN TABLE
 * Description: Tableaux Dynamiques
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
$module['name']        = 'SBUIADMIN TABLE';
$module['dirname']     = basename(dirname(__FILE__));
$module['version']     = '0.1.1';
$module['description'] = "Tableaux Dynamiques";
$module['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$module['tables']['tabss']     = _AM_DB_PREFIX . "sb_tabbs";
$module['tables']['tabbstab']  = _AM_DB_PREFIX . "sb_tabbs_tab";
$module['tables']['tabbssett'] = _AM_DB_PREFIX . "sb_tabbs_settings";
// -------------------------------------------------

?>
