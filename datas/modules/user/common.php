<?php
/**
 * Plugin Name: SBUIADMIN USER
 * Description: Gestion des utilisateurs
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
$modpage['name']        = 'SBUIADMIN USER';
$modpage['dirname']     = basename(dirname(__FILE__));
$modpage['version']     = '0.1.1';
$modpage['description'] = "Gestion des utilisateurs";
$modpage['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$modpage['tables']['user'] = _AM_DB_PREFIX . "sb_users";
// -------------------------------------------------

?>