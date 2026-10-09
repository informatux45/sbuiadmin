<?php
/**
 * Admin Startbootstrap
 * Manage THEME
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');

// -----------------------
// Include Config CMS
// -----------------------
include_once('../sbconfig.php');

// -----------------------
// Module URL
// -----------------------
$module_page = 'theme';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);
 
// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// Thème du site : réglage « theme » de sb_config (inc/admin/theme.txt
// jusqu'à la 4.14), voir sbSettingsTheme() dans inc/sbuiadmin-settings.php

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------

// ------------------------------------
// --- Control GET information --------
// ------------------------------------
// Lien « Activer ce thème » : jeton CSRF de la session (t=) et nom de thème
// existant dans theme/ (le nom sert ensuite à inclure les fichiers du thème)
if (isset($_GET['th']) && $_GET['th'] !== '') {
	$sb_theme_new = (string)$_GET['th'];
	if (empty($_SESSION['sbuiadmin_csrf_token']) || !hash_equals((string)$_SESSION['sbuiadmin_csrf_token'], (string)($_GET['t'] ?? ''))) {
		$sb_msg_error = 'Lien expiré : rechargez la page puis recommencez.';
	} elseif (!sbThemeIsValid($sb_theme_new)) {
		$sb_msg_error = 'Thème inconnu.';
	} elseif (sbSettingsSave(array('theme' => $sb_theme_new))) {
		$sb_msg_valid = 'Thème modifié avec succès';
	} else {
		$sb_msg_error = 'Error: Write Error (EDIT)!';
	}
}

// --------------------------------
// --- Thème actif
$sb_theme_name = sbSettingsTheme();
$sbsmarty->assign('sb_theme_name', $sb_theme_name);
$sbsmarty->assign('sb_csrf_token', sbCsrfToken());

// --- Debug SQL
if (_AM_SITE_DEBUG) $sbsmarty->assign('file_content', $sb_theme_name);						
// --------------------------------		
// --- Define variables
$sbsmarty->assign('formAction', $module_url);
// -----------------------------------
// --- All the THEME Names
// -----------------------------------
$sb_themes = sbGetThemesFront(SB_PATH . "theme");
$sbsmarty->assign('sb_themes', $sb_themes);

// ----------------------
// ASSIGN Settings
// ----------------------
$sb_theme_view = str_replace(SBADMIN, '', SB_THEME_URL) . 'screenshot-index.jpg';
$sbsmarty->assign('sb_theme_view', trim($sb_theme_view));

// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Thème');

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);
$sbsmarty->assign('sb_page', $sbpage);

?>