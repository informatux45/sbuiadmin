<?php
/**
 * Admin Startbootstrap
 * Show PROFILE (self, read-only - Point 19)
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
$module_page = 'profile';
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

// Page dédiée au libre-service (Point 19), volontairement séparée de
// users.php : simple PRÉSENTATION en lecture seule de SA PROPRE fiche - $id
// vient uniquement de la session, jamais d'un paramètre GET/POST. Pas de
// formulaire ici : le bouton "Modifier" de la section hero renvoie vers
// users.php (a=edit), où le système de droits existant reprend la main tel
// quel (self-service mot de passe seul si pas de "modifier" sur "users",
// fiche complète sinon) - rien à dupliquer/maintenir ici.
$table = _AM_DB_PREFIX . "sb_users";
$id    = sbGetCurrentUserId();

$query   = "SELECT * FROM $table WHERE id = $id";
$request = $sbsql->query($query);
$assoc   = $sbsql->assoc($request);

$sbsmarty->assign('profile', array(
	'username'              => $assoc['username'],
	'email'                 => $assoc['email'],
	'prenom'                => $sbsanitize->displayLang(utf8_encode($assoc['prenom'])),
	'nom'                   => $sbsanitize->displayLang(utf8_encode($assoc['nom'])),
	'telephone'             => $sbsanitize->displayLang(utf8_encode($assoc['telephone'])),
	'fonction'               => $sbsanitize->displayLang(utf8_encode($assoc['fonction'])),
	'profession'            => $sbsanitize->displayLang(utf8_encode($assoc['profession'])),
	'centres_interet'       => $sbsanitize->displayLang(utf8_encode($assoc['centres_interet'])),
	'infos_complementaires' => $sbsanitize->displayLang(utf8_encode($assoc['infos_complementaires'])),
	'avatar_url'            => sbGetUserAvatar($assoc['avatar'], $assoc['email'], 200),
	'last_login'            => date("d/m/Y H:i", $assoc['lastlogin']),
));

// --- Debug SQL
if (_AM_SITE_DEBUG) $sbsmarty->assign('sbdebugsql', $query);

// ----------------------
// ASSIGN Page TITLE
// ----------------------
$sbsmarty->assign('page_title', 'Mon profil');
$sbsmarty->assign('legend_add_edit', 'Mon profil');

// ----------------------
// CLOSE SQL
// ----------------------
$sbsql->close();
?>
