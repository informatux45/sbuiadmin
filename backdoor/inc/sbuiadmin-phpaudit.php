<?php
/**
 * Admin Startbootstrap
 * SBUIADMIN Journal d'audit PHP (outil de préparation des montées de version)
 *
 * Désactivé par défaut. Actif seulement si le fichier
 * inc/admin/php-audit.txt existe (vide) : chaque dépréciation, avertissement
 * ou notice émis par PHP est alors noté une fois (fichier:ligne, version de
 * PHP, page) dans inc/admin/php-audit.log - même quand le site masque les
 * erreurs (error_reporting(0) hors mode debug). Les .txt/.log de inc/ sont
 * refusés au web (inc/.htaccess).
 *
 * Usage : créer php-audit.txt, naviguer sur le site et l'administration sous
 * la nouvelle version de PHP, lire php-audit.log, puis supprimer les deux.
 *
 * Chargé par sbconfig.php (site) et inc/sbuiadmin-config.php (admin).
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

if (defined('SBUIADMIN_PHPAUDIT_LOADED')) return;
define('SBUIADMIN_PHPAUDIT_LOADED', true);

if (!is_file(__DIR__ . '/admin/php-audit.txt')) return;

$GLOBALS['sb_phpaudit'] = array();

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
	static $types = array(
		E_DEPRECATED => 'DEPRECATED', E_USER_DEPRECATED => 'USER_DEPRECATED',
		E_WARNING => 'WARNING', E_USER_WARNING => 'USER_WARNING',
		E_NOTICE => 'NOTICE', E_USER_NOTICE => 'USER_NOTICE',
	);
	$key = $errfile . ':' . $errline . ':' . $errstr;
	if (!isset($GLOBALS['sb_phpaudit'][$key])) {
		$GLOBALS['sb_phpaudit'][$key] = (isset($types[$errno]) ? $types[$errno] : $errno) . "\t" . $errfile . ':' . $errline . "\t" . $errstr;
	}
	return false; // traitement normal de PHP (affichage selon error_reporting)
}, E_ALL & ~E_ERROR & ~E_PARSE & ~E_CORE_ERROR & ~E_COMPILE_ERROR & ~E_USER_ERROR & ~E_RECOVERABLE_ERROR);

register_shutdown_function(function () {
	if (empty($GLOBALS['sb_phpaudit'])) return;
	$page = (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] . ' ' : '') . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'cli');
	$out = '';
	foreach ($GLOBALS['sb_phpaudit'] as $line) {
		$out .= date('Y-m-d H:i:s') . "\tPHP " . PHP_VERSION . "\t" . $line . "\t" . str_replace(array("\r", "\n", "\t"), ' ', $page) . "\n";
	}
	@file_put_contents(__DIR__ . '/admin/php-audit.log', $out, FILE_APPEND | LOCK_EX);
});
