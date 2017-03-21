<?php
/**
 * Admin Startbootstrap
 * Common infos Admin Menu
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
defined('SBMAGIC_PATH') or die('Are you crazy!');

// ----------------------------------------------------
// ----------------------------------------------------
// ----------------------------------------------------
// - - - - - - - - - - - SYSTEM - - - - - - - - - - - -
// ----------------------------------------------------
// ----------------------------------------------------
// ----------------------------------------------------
$module_menu['settings']['main']  = "Configuration";
$module_menu['settings']['icon']  = "cubes";
$module_menu['settings']['group'] = "admin"; // user OR admin
$module_menu['settings']['li'][0]['title'] = "Générale";
$module_menu['settings']['li'][0]['link']  = "index.php?p=settings";
$module_menu['settings']['li'][1]['title'] = "Cache";
$module_menu['settings']['li'][1]['link']  = "index.php?p=cache";
$module_menu['settings']['li'][2]['title'] = "Infos serveur";
$module_menu['settings']['li'][2]['link']  = "index.php?p=server";
$module_menu['settings']['li'][3]['title'] = "Dashboard";
$module_menu['settings']['li'][3]['link']  = "index.php?p=dashboard";
$module_menu['settings']['li'][4]['title'] = "Toolbar CKEditor";
$module_menu['settings']['li'][4]['link']  = "index.php?p=toolbarck";

$module_menu['logaccess']['main']  = "Journaux";
$module_menu['logaccess']['icon']  = "list-alt";
$module_menu['logaccess']['group'] = "admin"; // user OR admin

$module_menu['database']['main']  = "Database";
$module_menu['database']['icon']  = "database";
$module_menu['database']['group'] = "admin"; // user OR admin

$module_menu['explorer']['main']  = "Explorer";
$module_menu['explorer']['icon']  = "sitemap";
$module_menu['explorer']['group'] = "admin"; // user OR admin

$module_menu['users']['main']  = "Utilisateurs";
$module_menu['users']['icon']  = "users";
$module_menu['users']['group'] = "admin"; // user OR admin
$module_menu['users']['li'][0]['title'] = "Tous les utilisateurs";
$module_menu['users']['li'][0]['link']  = "index.php?p=users";
$module_menu['users']['li'][1]['title'] = "Ajouter un utilisateur";
$module_menu['users']['li'][1]['link']  = "index.php?p=users&a=add";

$module_menu['medias']['main'] = "Medias";
$module_menu['medias']['icon'] = "desktop";
$module_menu['medias']['group'] = "user"; // user OR admin

$module_menu['cmsconfig']['main']  = "CMS config";
$module_menu['cmsconfig']['icon']  = "gears";
$module_menu['cmsconfig']['group'] = "user"; // user OR admin
$module_menu['cmsconfig']['li'][0]['title'] = "Header / Footer";
$module_menu['cmsconfig']['li'][0]['link']  = "index.php?p=cmsconfig&op=headerfooter";
$module_menu['cmsconfig']['li'][1]['title'] = "CSS Code";
$module_menu['cmsconfig']['li'][1]['link']  = "index.php?p=cmsconfig&op=css";
$module_menu['cmsconfig']['li'][2]['title'] = "JAVASCRIPT Code";
$module_menu['cmsconfig']['li'][2]['link']  = "index.php?p=cmsconfig&op=javascript";
$module_menu['cmsconfig']['li'][3]['title'] = "Coming Soon";
$module_menu['cmsconfig']['li'][3]['link']  = "index.php?p=cmsconfig&op=comingsoon";
$module_menu['cmsconfig']['li'][4]['title'] = "Multilangue";
$module_menu['cmsconfig']['li'][4]['link']  = "index.php?p=cmsconfig&op=multilang";
$module_menu['cmsconfig']['li'][5]['title'] = "Plugins";
$module_menu['cmsconfig']['li'][5]['link']  = "index.php?p=cmsconfig&op=plugins";
$module_menu['cmsconfig']['li'][6]['title'] = "Fonts";
$module_menu['cmsconfig']['li'][6]['link']  = "index.php?p=cmsconfig&op=fonts";
$module_menu['cmsconfig']['li'][7]['title'] = "SEO";
$module_menu['cmsconfig']['li'][7]['link']  = "index.php?p=cmsconfig&op=seo";

$module_menu['menu']['main']  = "Menu";
$module_menu['menu']['icon']  = "th-list";
$module_menu['menu']['group'] = "user"; // user OR admin
$module_menu['menu']['li'][0]['title'] = "Tous les Menus";
$module_menu['menu']['li'][0]['link']  = "index.php?p=menu";
$module_menu['menu']['li'][1]['title'] = "Ajouter un menu";
$module_menu['menu']['li'][1]['link']  = "index.php?p=menu&a=add";

$module_menu['news']['main']  = "Articles";
$module_menu['news']['icon']  = "rss";
$module_menu['news']['group'] = "user"; // user OR admin
$module_menu['news']['li'][0]['title'] = "Tous les articles";
$module_menu['news']['li'][0]['link']  = "index.php?p=news";
$module_menu['news']['li'][1]['title'] = "Ajouter un article";
$module_menu['news']['li'][1]['link']  = "index.php?p=news&a=add";
$module_menu['news']['li'][2]['title'] = "Toutes les catégories";
$module_menu['news']['li'][2]['link']  = "index.php?p=news&a=category";
$module_menu['news']['li'][3]['title'] = "Ajouter une catégorie";
$module_menu['news']['li'][3]['link']  = "index.php?p=news&a=categoryadd";
$module_menu['news']['li'][4]['title'] = "Paramètres";
$module_menu['news']['li'][4]['link']  = "index.php?p=news&a=settings";

$module_menu['pages']['main']  = "Pages";
$module_menu['pages']['icon']  = "copy";
$module_menu['pages']['group'] = "user"; // user OR admin
$module_menu['pages']['li'][0]['title'] = "Toutes les pages";
$module_menu['pages']['li'][0]['link']  = "index.php?p=pages";
$module_menu['pages']['li'][1]['title'] = "Ajouter une page";
$module_menu['pages']['li'][1]['link']  = "index.php?p=pages&a=add";
$module_menu['pages']['li'][2]['title'] = "Ajouter une page (custom)";
$module_menu['pages']['li'][2]['link']  = "index.php?p=pages&a=addcustom";

$module_menu['blocs']['main']  = "Blocs";
$module_menu['blocs']['icon']  = "codepen";
$module_menu['blocs']['group'] = "user"; // user OR admin
$module_menu['blocs']['li'][0]['title'] = "Tous les blocs";
$module_menu['blocs']['li'][0]['link']  = "index.php?p=blocs";
$module_menu['blocs']['li'][1]['title'] = "Ajouter un bloc";
$module_menu['blocs']['li'][1]['link']  = "index.php?p=blocs&a=add";

// -----------------------------------------------------
// -----------------------------------------------------
// -----------------------------------------------------
// - - - - - - - - - - - MODULES - - - - - - - - - - - -
// -----------------------------------------------------
// -----------------------------------------------------
// -----------------------------------------------------

// -----------------------
// Menu CONTACT (Forms)
// -----------------------
$module_menu['contact']['main']  = "Contact";
$module_menu['contact']['icon']  = "envelope";
$module_menu['contact']['group'] = "user"; // user OR admin
$module_menu['contact']['li'][0]['title'] = "Tous les formulaires";
$module_menu['contact']['li'][0]['link']  = "index.php?p=contact";
$module_menu['contact']['li'][1]['title'] = "Ajouter un formulaire";
$module_menu['contact']['li'][1]['link']  = "index.php?p=contact&a=add";
$module_menu['contact']['li'][2]['title'] = "Paramètres généraux";
$module_menu['contact']['li'][2]['link']  = "index.php?p=contact&a=settings";

// -----------------------
// Menu SLIDER
// -----------------------
$module_menu['slider']['main']  = "Sliders";
$module_menu['slider']['icon']  = "sliders";
$module_menu['slider']['group'] = "user"; // user OR admin
$module_menu['slider']['li'][0]['title'] = "Tous les sliders";
$module_menu['slider']['li'][0]['link']  = "index.php?p=slider";
$module_menu['slider']['li'][1]['title'] = "Ajouter un slider";
$module_menu['slider']['li'][1]['link']  = "index.php?p=slider&a=add";
$module_menu['slider']['li'][2]['title'] = "Ajouter une photo / vidéo";
$module_menu['slider']['li'][2]['link']  = "index.php?p=slider&a=photoadd";

// -----------------------
// Menu EFFECTIVES
// -----------------------
$module_menu['effectives']['main']  = "Effectifs";
$module_menu['effectives']['icon']  = "trophy";
$module_menu['effectives']['group'] = "user"; // user OR admin
$module_menu['effectives']['li'][0]['title'] = "Tous les effectifs";
$module_menu['effectives']['li'][0]['link']  = "index.php?p=effectives";
$module_menu['effectives']['li'][1]['title'] = "Ajouter un effectif";
$module_menu['effectives']['li'][1]['link']  = "index.php?p=effectives&a=add";
$module_menu['effectives']['li'][2]['title'] = "Ajouter une production";
$module_menu['effectives']['li'][2]['link']  = "index.php?p=effectives&a=productionadd";
$module_menu['effectives']['li'][3]['title'] = "Toutes les catégories";
$module_menu['effectives']['li'][3]['link']  = "index.php?p=effectives&a=category";
$module_menu['effectives']['li'][4]['title'] = "Ajouter une catégorie";
$module_menu['effectives']['li'][4]['link']  = "index.php?p=effectives&a=categoryadd";
$module_menu['effectives']['li'][5]['title'] = "Paramètres";
$module_menu['effectives']['li'][5]['link']  = "index.php?p=effectives&a=settings";

// -----------------------
// Menu GRADUATES
// -----------------------
$module_menu['graduates']['main']  = "Graduates";
$module_menu['graduates']['icon']  = "certificate";
$module_menu['graduates']['group'] = "user"; // user OR admin
$module_menu['graduates']['li'][0]['title'] = "Tous les graduates";
$module_menu['graduates']['li'][0]['link']  = "index.php?p=graduates";
$module_menu['graduates']['li'][1]['title'] = "Ajouter un graduate";
$module_menu['graduates']['li'][1]['link']  = "index.php?p=graduates&a=add";
$module_menu['graduates']['li'][2]['title'] = "Toutes les catégories";
$module_menu['graduates']['li'][2]['link']  = "index.php?p=graduates&a=category";
$module_menu['graduates']['li'][3]['title'] = "Ajouter une catégorie";
$module_menu['graduates']['li'][3]['link']  = "index.php?p=graduates&a=categoryadd";
$module_menu['graduates']['li'][4]['title'] = "Paramètres";
$module_menu['graduates']['li'][4]['link']  = "index.php?p=graduates&a=settings";

// -----------------------
// Menu TABBS
// -----------------------
$module_menu['tabbs']['main']  = "Tabbs";
$module_menu['tabbs']['icon']  = "list-alt";
$module_menu['tabbs']['group'] = "user"; // user OR admin
$module_menu['tabbs']['li'][0]['title'] = "Tous les tabbs";
$module_menu['tabbs']['li'][0]['link']  = "index.php?p=tabbs";
$module_menu['tabbs']['li'][1]['title'] = "Ajouter un tabbs";
$module_menu['tabbs']['li'][1]['link']  = "index.php?p=tabbs&a=add";
$module_menu['tabbs']['li'][2]['title'] = "Tous les onglets";
$module_menu['tabbs']['li'][2]['link']  = "index.php?p=tabbs&a=alltabs";
$module_menu['tabbs']['li'][3]['title'] = "Ajouter un onglet";
$module_menu['tabbs']['li'][3]['link']  = "index.php?p=tabss&a=tabadd";

// -----------------------
// Menu TABLE
// -----------------------
$module_menu['table']['main']  = "Table";
$module_menu['table']['icon']  = "table";
$module_menu['table']['group'] = "user"; // user OR admin
$module_menu['table']['li'][0]['title'] = "Tous les tableaux";
$module_menu['table']['li'][0]['link']  = "index.php?p=table";
$module_menu['table']['li'][1]['title'] = "Ajouter un tableau";
$module_menu['table']['li'][1]['link']  = "index.php?p=table&a=add";

?>