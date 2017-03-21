<?php
/**
 * Plugin Name: SbMagic SEARCH
 * Description: Module de recherche
 * Version: 0.1.1
 * Agency: Agence DOLLAR
 * Agency URI: //dollar.fr
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 * File: common.php
 */

 // Security Check
if (!defined('SB_PATH')) {
	die('You cannot load this file directly!');
}

// -------------------------------------------------
// --- Global MODULE
// -------------------------------------------------
$module['name']        = 'SbMagic SEARCH';
$module['dirname']     = basename(dirname(__FILE__));
$module['version']     = MODULEVERSION;
$module['description'] = "Module de recherche";
$module['author']      = "BooBoo";
// -------------------------------------------------
// --- Tables SQL
// -------------------------------------------------
$module['tables']['bien'] = "biens";
$module['tables']['dpe']  = "dpe";
$module['tables']['dept'] = "departement";
$module['tables']['regi'] = "region";
$module['tables']['type'] = "type";
$module['tables']['page'] = "sb_pages";
$module['tables']['phot'] = "photo";
$module['tables']['flag'] = "flag";
$module['tables']['sele'] = "selection";
// -------------------------------------------------

?>