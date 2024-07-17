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
$module_menu['settings']['li'][1]['title'] = SBUIADMIN_MENU_CONFIGURATION_CACHE;
$module_menu['settings']['li'][1]['link']  = "index.php?p=cache";
$module_menu['settings']['li'][2]['title'] = SBUIADMIN_MENU_CONFIGURATION_DASHBOARD;
$module_menu['settings']['li'][2]['link']  = "index.php?p=dashboard";
$module_menu['settings']['li'][3]['title'] = SBUIADMIN_MENU_CONFIGURATION_TOOLBAR;
$module_menu['settings']['li'][3]['link']  = "index.php?p=toolbarck";
$module_menu['settings']['li'][4]['title'] = SBUIADMIN_MENU_CONFIGURATION_THEME;
$module_menu['settings']['li'][4]['link']  = "index.php?p=theme";
$module_menu['settings']['li'][5]['title'] = SBUIADMIN_MENU_CONFIGURATION_THEME_INFOS;
$module_menu['settings']['li'][5]['link']  = "index.php?p=themeinfos";
$module_menu['settings']['li'][6]['title'] = SBUIADMIN_MENU_CONFIGURATION_BOOTSTRAP;
$module_menu['settings']['li'][6]['link']  = "assets/samples/";
$module_menu['settings']['li'][6]['target'] = "_blank";

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
$module_menu['cmsconfig']['li'][6]['title'] = SBUIADMIN_MENU_CMSCONFIG_FONTS;
$module_menu['cmsconfig']['li'][6]['link']  = "index.php?p=cmsconfig&op=fonts";
$module_menu['cmsconfig']['li'][7]['title'] = SBUIADMIN_MENU_CMSCONFIG_SEO;
$module_menu['cmsconfig']['li'][7]['link']  = "index.php?p=cmsconfig&op=seo";

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
// Menu NEWS
// -----------------------
$module_menu['news']['main']  = SBUIADMIN_MENU_NEWS;
$module_menu['news']['icon']  = "rss";
$module_menu['news']['group'] = "user"; // user OR admin
$module_menu['news']['li'][0]['title'] = SBUIADMIN_MENU_NEWS_ALL;
$module_menu['news']['li'][0]['link']  = "index.php?p=news";
$module_menu['news']['li'][1]['title'] = SBUIADMIN_MENU_NEWS_CATEGORIES;
$module_menu['news']['li'][1]['link']  = "index.php?p=news&a=category";
$module_menu['news']['li'][2]['title'] = SBUIADMIN_MENU_NEWS_SETTINGS;
$module_menu['news']['li'][2]['link']  = "index.php?p=news&a=settings";

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
// Menu CONTACT (Forms)
// -----------------------
$module_menu['contact']['main']  = SBUIADMIN_MENU_CONTACT;
$module_menu['contact']['icon']  = "envelope";
$module_menu['contact']['group'] = "user"; // user OR admin
$module_menu['contact']['li'][0]['title'] = SBUIADMIN_MENU_CONTACT_ALL;
$module_menu['contact']['li'][0]['link']  = "index.php?p=contact";
$module_menu['contact']['li'][1]['title'] = SBUIADMIN_MENU_CONTACT_SETTINGS;
$module_menu['contact']['li'][1]['link']  = "index.php?p=contact&a=settings";

// -----------------------
// Menu SLIDER
// -----------------------
$module_menu['slider']['main']  = SBUIADMIN_MENU_SLIDERS;
$module_menu['slider']['icon']  = "sliders";
$module_menu['slider']['group'] = "user"; // user OR admin

// -----------------------
// Menu TABBS
// -----------------------
$module_menu['tabbs']['main']  = SBUIADMIN_MENU_TABBS;
$module_menu['tabbs']['icon']  = "list-alt";
$module_menu['tabbs']['group'] = "user"; // user OR admin
$module_menu['tabbs']['li'][0]['title'] = SBUIADMIN_MENU_TABBS_ALL;
$module_menu['tabbs']['li'][0]['link']  = "index.php?p=tabbs";
$module_menu['tabbs']['li'][1]['title'] = SBUIADMIN_MENU_TABBS_ONGLETS;
$module_menu['tabbs']['li'][1]['link']  = "index.php?p=tabbs&a=alltabs";

// -----------------------
// Menu TABLE
// -----------------------
$module_menu['table']['main']  = SBUIADMIN_MENU_TABLES;
$module_menu['table']['icon']  = "table";
$module_menu['table']['group'] = "user"; // user OR admin

?>