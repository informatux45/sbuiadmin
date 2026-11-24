<?php
/**
 * Module stats : chargé par header.php pour chaque page du site (module
 * installé). Ne fait que brancher la collecte de fin de requête.
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (!defined('SB_PATH')) die('You cannot load this file directly!');

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'tracker.php');
stats_boot();
