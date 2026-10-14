<?php
/**
 * Admin Startbootstrap
 * Configuration > Serveur : relevés adaptés au type d'hébergement
 * (mutualisé, VPS, dédié), détecté ou forcé par le réglage hosting_type.
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
require_once(SBUIADMIN_PATH . '/inc/sbuiadmin-server.php');

// -----------------------
// Module URL
// -----------------------
$module_page = 'server';
$sbsmarty->assign('module_page', $module_page);
// -----------------------
$module_url = _AM_SITE_PROTOCOL . SBUIADMIN_URL . SBUIADMIN_BASE . '?p=' . $module_page;
$sbsmarty->assign('module_url', $module_url);

// -----------------------
// Message status
// -----------------------
$sb_msg_error = false;
$sb_msg_valid = false;

// ---------------------------------------------------
// ---------------------------------------------------
// Write your own code after these lines
// ---------------------------------------------------
// ---------------------------------------------------

$sb_srv_root = realpath(SB_PATH) ?: SB_PATH;

// --------------------------------
// --- AJAX : relevés rafraîchis par la page (disque, RAM, charge, réseau)
// --------------------------------
if (isset($_GET['ajax']) && $_GET['ajax'] == 'live') {
	header('Content-Type: application/json');
	echo json_encode(sbServerLive($sb_srv_root));
	exit;
}

// --------------------------------
// --- AJAX : place prise par le site (parcours des dossiers, borné)
// --------------------------------
if (isset($_GET['ajax']) && $_GET['ajax'] == 'sizes') {
	$sb_srv_media = realpath(_AM_MEDIAS_DIR);
	$sb_srv_dirs  = array('Site complet' => $sb_srv_root);
	if ($sb_srv_media) $sb_srv_dirs['Médias'] = $sb_srv_media;
	$sb_srv_dirs['Caches (front)']  = SB_PATH . 'datas/cache';
	$sb_srv_dirs['Caches (admin)']  = _AM_SITE_DIR . 'datas/cache';
	$sb_srv_dirs['Journaux']        = _AM_SITE_DIR . 'logs';
	$sb_srv_sizes = array();
	foreach (sbServerSite($sb_srv_dirs) as $s) {
		$sb_srv_sizes[] = array('label' => $s['label'], 'size' => $s['size'], 'partial' => $s['partial']);
	}
	header('Content-Type: application/json');
	echo json_encode($sb_srv_sizes);
	exit;
}

// --------------------------------
// --- Réglage : type d'hébergement (auto ou forcé)
// --------------------------------
$sb_srv_types = sbServerHostingTypes();
if (isset($_POST['form_submit']) && $_POST['form_submit'] == 'server_form') {
	$sb_srv_new = (string) ($_POST['hosting_type'] ?? 'auto');
	if (!array_key_exists($sb_srv_new, $sb_srv_types)) $sb_srv_new = 'auto';
	if (sbSettingsSave(array('hosting_type' => $sb_srv_new))) {
		$GLOBALS['sb_settings']['hosting_type'] = $sb_srv_new;
		$sb_msg_valid = "Type d'hébergement enregistré";
	} else {
		$sb_msg_error = "Enregistrement impossible";
	}
}

$sb_srv_setting  = sbSetting('hosting_type', 'auto');
$sb_srv_probe    = sbServerProbe($sb_srv_root);
$sb_srv_detected = sbServerDetectHosting($sb_srv_probe);
$sb_srv_type     = sbServerHostingType($sb_srv_setting, $sb_srv_detected);

// Relevés de la machine que open_basedir empêche de lire (VPS / dédié)
$sb_srv_blocked = array();
if ($sb_srv_probe['open_basedir'] !== '') {
	foreach (sbServerProcPaths() as $f) if (!sbServerReadable($f)) $sb_srv_blocked[] = $f;
}

$sbsmarty->assign('sb_srv', array(
	'types'     => $sb_srv_types,
	'setting'   => $sb_srv_setting,
	'type'      => $sb_srv_type,
	'type_name' => $sb_srv_types[$sb_srv_type],
	'detected'  => $sb_srv_detected + array('name' => $sb_srv_types[$sb_srv_detected['type']]),
	'probe'     => $sb_srv_probe,
	'basedir'   => array_values(array_filter(explode(PATH_SEPARATOR, $sb_srv_probe['open_basedir']))),
	'blocked'   => $sb_srv_blocked,
	'net_blocked' => in_array('/proc/net/dev', $sb_srv_blocked, true),
	'php'       => sbServerPhp(),
	'db'        => sbServerDb($sbsql),
	'live'      => json_encode(sbServerLive($sb_srv_root)),
	'js_v'      => @filemtime(_AM_SITE_DIR . 'assets/adminator/server.js'),
));

// --------------------------------
// --- Formulaire du réglage
// --------------------------------
$sbform->openForm(array('action' => $module_url, 'name' => 'server_form', 'id' => 'server_form', 'reloadpage' => $module_url, 'submitpage' => $module_url));
$sbform->openSelect("Type d'hébergement", array('id' => 'hosting_type', 'name' => 'hosting_type'));
$sb_srv_opt = array('value' => 'auto');
if (!array_key_exists($sb_srv_setting, $sb_srv_types)) $sb_srv_opt['selected'] = '';
$sbform->addOption('Automatique (détecté : ' . $sb_srv_types[$sb_srv_detected['type']] . ')', $sb_srv_opt);
foreach ($sb_srv_types as $k => $v) {
	$sb_srv_opt = array('value' => $k);
	if ($k === $sb_srv_setting) $sb_srv_opt['selected'] = '';
	$sbform->addOption($v, $sb_srv_opt);
}
$sbform->closeSelect();
$sbform->addInput('hidden', '', array('name' => 'form_submit', 'value' => 'server_form'));
$sbform->addInput('submit', '', array('value' => 'Enregistrer'));
$sbform->closeForm();

// ---------------------------------------------------
// ---------------------------------------------------
// IMPORTANT: Don't remove these lines
// ---------------------------------------------------
// ---------------------------------------------------
// ----------------------------------------
// ASSIGN Page TITLE - Modify this |
// ----------------------------------------
$sbsmarty->assign('page_title', 'Serveur');

// ----------------------
// ASSIGN Message status
// ----------------------
$sbsmarty->assign('sb_msg_error', $sb_msg_error);
$sbsmarty->assign('sb_msg_valid', $sb_msg_valid);

// ----------------------
// CLOSE SQL
// ----------------------
$sbsql->close();

?>
