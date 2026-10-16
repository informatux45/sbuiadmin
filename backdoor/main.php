<?php
/**
 * Admin Startbootstrap
 * Common infos Admin Menu
 *
 * @link http://informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');

// ----------------------------------------------------
// ----------------------------------------------------
// ----------------------------------------------------
// - - - - - - - - - - - SYSTEM - - - - - - - - - - - -
// ----------------------------------------------------
// ----------------------------------------------------
// ----------------------------------------------------
$module_menu['settings']['main']  = SBUIADMIN_MENU_CONFIGURATION;
$module_menu['settings']['icon']  = "cubes";
$module_menu['settings']['group'] = "admin"; // user OR admin
$module_menu['settings']['li'][0]['title'] = SBUIADMIN_MENU_CONFIGURATION_GENERAL;
$module_menu['settings']['li'][0]['link']  = "index.php?p=settings";
$module_menu['settings']['li'][1]['title'] = SBUIADMIN_MENU_CONFIGURATION_SESSION;
$module_menu['settings']['li'][1]['link']  = "index.php?p=session";
$module_menu['settings']['li'][2]['title'] = SBUIADMIN_MENU_CONFIGURATION_SERVER;
$module_menu['settings']['li'][2]['link']  = "index.php?p=server";
$module_menu['settings']['li'][3]['title'] = SBUIADMIN_MENU_CONFIGURATION_CACHE;
$module_menu['settings']['li'][3]['link']  = "index.php?p=cache";
$module_menu['settings']['li'][4]['title'] = SBUIADMIN_MENU_CONFIGURATION_DASHBOARD;
$module_menu['settings']['li'][4]['link']  = "index.php?p=dashboard";
$module_menu['settings']['li'][5]['title'] = SBUIADMIN_MENU_CONFIGURATION_TOOLBAR;
$module_menu['settings']['li'][5]['link']  = "index.php?p=toolbarck";
$module_menu['settings']['li'][6]['title'] = SBUIADMIN_MENU_CONFIGURATION_THEME;
$module_menu['settings']['li'][6]['link']  = "index.php?p=theme";
$module_menu['settings']['li'][7]['title'] = SBUIADMIN_MENU_CONFIGURATION_THEME_INFOS;
$module_menu['settings']['li'][7]['link']  = "index.php?p=themeinfos";
$module_menu['settings']['li'][8]['title'] = SBUIADMIN_MENU_CONFIGURATION_BOOTSTRAP;
$module_menu['settings']['li'][8]['link']  = "assets/samples/";
$module_menu['settings']['li'][8]['target'] = "_blank";
// Lien statique (pas de p=) - la matrice de droits ne peut pas l'identifier
// via l'URL comme les autres entrées, on le rattache donc explicitement à
// sa propre ligne (voir sbGetRightsSubmodules()/sbHasMenuLinkRight()).
$module_menu['settings']['li'][8]['rights'] = 'settings:samples';
$module_menu['settings']['li'][9]['title'] = SBUIADMIN_MENU_CONFIGURATION_UPDATE;
$module_menu['settings']['li'][9]['link']  = "index.php?p=update";

$module_menu['logaccess']['main']  = SBUIADMIN_MENU_LOG;
$module_menu['logaccess']['icon']  = "list-alt";
$module_menu['logaccess']['group'] = "admin"; // user OR admin

$module_menu['users']['main']  = "Utilisateurs";
$module_menu['users']['icon']  = "users";
$module_menu['users']['group'] = "admin"; // user OR admin
$module_menu['users']['li'][0]['title']  = SBUIADMIN_MENU_ALL_USERS;
$module_menu['users']['li'][0]['link']   = "index.php?p=users";
$module_menu['users']['li'][1]['title']  = SBUIADMIN_MENU_BLOCKED_IPS;
$module_menu['users']['li'][1]['link']   = "index.php?p=users&a=blockedip";
$module_menu['users']['li'][2]['title']  = SBUIADMIN_MENU_GRAVATAR;
$module_menu['users']['li'][2]['link']   = "https://fr.gravatar.com/";
$module_menu['users']['li'][2]['target'] = "_blank";

$module_menu['medias']['main'] = SBUIADMIN_MENU_MEDIAS;
$module_menu['medias']['icon'] = "desktop";
$module_menu['medias']['group'] = "user"; // user OR admin

$module_menu['cmsconfig']['main']  = SBUIADMIN_MENU_CMSCONFIG;
$module_menu['cmsconfig']['icon']  = "gears";
$module_menu['cmsconfig']['group'] = "user"; // user OR admin
$module_menu['cmsconfig']['li'][0]['title'] = SBUIADMIN_MENU_CMSCONFIG_HEADERFOOTER;
$module_menu['cmsconfig']['li'][0]['link']  = "index.php?p=cmsconfig&op=headerfooter";
$module_menu['cmsconfig']['li'][1]['title'] = SBUIADMIN_MENU_CMSCONFIG_CSS;
$module_menu['cmsconfig']['li'][1]['link']  = "index.php?p=cmsconfig&op=css";
$module_menu['cmsconfig']['li'][2]['title'] = SBUIADMIN_MENU_CMSCONFIG_JAVASCRIPT;
$module_menu['cmsconfig']['li'][2]['link']  = "index.php?p=cmsconfig&op=javascript";
$module_menu['cmsconfig']['li'][3]['title'] = SBUIADMIN_MENU_CMSCONFIG_COMINGSOON;
$module_menu['cmsconfig']['li'][3]['link']  = "index.php?p=cmsconfig&op=comingsoon";
$module_menu['cmsconfig']['li'][4]['title'] = SBUIADMIN_MENU_CMSCONFIG_MULTILANG;
$module_menu['cmsconfig']['li'][4]['link']  = "index.php?p=cmsconfig&op=multilang";
$module_menu['cmsconfig']['li'][5]['title'] = SBUIADMIN_MENU_CMSCONFIG_PLUGINS;
$module_menu['cmsconfig']['li'][5]['link']  = "index.php?p=cmsconfig&op=plugins";
$module_menu['cmsconfig']['li'][6]['title'] = SBUIADMIN_MENU_CMSCONFIG_SEO;
$module_menu['cmsconfig']['li'][6]['link']  = "index.php?p=cmsconfig&op=seo";

$module_menu['menu']['main']  = SBUIADMIN_MENU_MENU;
$module_menu['menu']['icon']  = "th-list";
$module_menu['menu']['group'] = "user"; // user OR admin

// -----------------------------------------------------
// -----------------------------------------------------
// -----------------------------------------------------
// - - - - - - - - - - - MODULES - - - - - - - - - - - -
// -----------------------------------------------------
// -----------------------------------------------------
// -----------------------------------------------------

// -----------------------
// Menu PAGES
// -----------------------
$module_menu['pages']['main']  = SBUIADMIN_MENU_PAGES;
$module_menu['pages']['icon']  = "copy";
$module_menu['pages']['group'] = "user"; // user OR admin
$module_menu['pages']['li'][0]['title'] = SBUIADMIN_MENU_PAGES_ALL;
$module_menu['pages']['li'][0]['link']  = "index.php?p=pages";
$module_menu['pages']['li'][1]['title'] = SBUIADMIN_MENU_PAGES_BLOCS;
$module_menu['pages']['li'][1]['link']  = "index.php?p=blocs";

// -----------------------
// Menus des modules installés : datas/modules/<nom>/admin/menu.php
// (voir inc/sbuiadmin-modules.php) - un module présent mais non installé
// n'a pas d'entrée, il disparaît donc du menu et de la matrice de droits
// -----------------------
foreach (array_keys(sbModulesActive()) as $sb_module_name) {
	$sb_module_file = sbModuleAdminFile($sb_module_name, 'menu.php');
	if ($sb_module_file === null) continue;
	$sb_module_entry = include $sb_module_file;
	if (!is_array($sb_module_entry)) continue;
	// Icône : celle de module.php (aussi affichée dans la carte Modules)
	if (empty($sb_module_entry['icon'])) $sb_module_entry['icon'] = sbModuleMeta($sb_module_name)['icon'];
	$module_menu[$sb_module_name] = $sb_module_entry;
}
unset($sb_module_name, $sb_module_file, $sb_module_entry);

?>