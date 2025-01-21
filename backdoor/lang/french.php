<?php
/**
 * Admin Startbootstrap
 * French LANGUAGE
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// ** Log message
defined('SBUIADMIN_MSG_LOG_ACCESS_GRANTED') OR define("SBUIADMIN_MSG_LOG_ACCESS_GRANTED", "Utilisateur [%s] identifi&eacute; depuis [%s]");
defined('SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR') OR define("SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR", "Utilisateur [%s] identifi&eacute; depuis [%s] avec une erreur captcha");
defined('SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR2') OR define("SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR2", "Utilisateur [%s] identifi&eacute; depuis [%s] avec une erreur captcha [%s]->[%s]");
defined('SBUIADMIN_MSG_LOG_ACCESS_USER_ERROR') OR define("SBUIADMIN_MSG_LOG_ACCESS_USER_ERROR", "Utilisateur d&eacute;sactiv&eacute; [%s] a essay&eacute; de s&#39;identifier depuis [%s]");
defined('SBUIADMIN_MSG_LOG_ACCESS_NOGRANTED') OR define("SBUIADMIN_MSG_LOG_ACCESS_NOGRANTED", "Identification non autoris&eacute;e depuis [%s] - propablement un intru");
defined('SBUIADMIN_MSG_LOG_ACCESS_MISSING') OR define("SBUIADMIN_MSG_LOG_ACCESS_MISSING", "Erreur d&#39;acc&egrave;s depuis [%s] - identifiant et&#47;ou mot de passe manquant");
 
 // ** Control access message
defined('SBUIADMIN_MSG_ERROR_E1') OR define("SBUIADMIN_MSG_ERROR_E1", "Captcha incorrect");
defined('SBUIADMIN_MSG_ERROR_E2') OR define("SBUIADMIN_MSG_ERROR_E2", "Login incorrect");
defined('SBUIADMIN_MSG_ERROR_E3') OR define("SBUIADMIN_MSG_ERROR_E3", "Login manquant");
defined('SBUIADMIN_MSG_ERROR_E4') OR define("SBUIADMIN_MSG_ERROR_E4", "Compte d&eacute;sactiv&eacute;");

// ** User Interface
defined('SBUIADMIN_GLOBAL_LAST_LOGIN') OR define("SBUIADMIN_GLOBAL_LAST_LOGIN", "Derni&egrave;re connexion");
defined('SBUIADMIN_GLOBAL_SETTINGS') OR define("SBUIADMIN_GLOBAL_SETTINGS", "Param&egrave;tres");
defined('SBUIADMIN_GLOBAL_LOGOUT') OR define("SBUIADMIN_GLOBAL_LOGOUT", "D&eacute;connexion");
defined('SBUIADMIN_GLOBAL_HI') OR define("SBUIADMIN_GLOBAL_HI", "Bonjour");
defined('SBUIADMIN_GLOBAL_PREV') OR define("SBUIADMIN_GLOBAL_PREV", "Précédent");
defined('SBUIADMIN_GLOBAL_NEXT') OR define("SBUIADMIN_GLOBAL_NEXT", "Suivant");
defined('SBUIADMIN_GLOBAL_PAGE_INFOS') or define('SBUIADMIN_GLOBAL_PAGE_INFOS', "%s à %s sur %s résultats");
 
// --------------------------------
//          MENU (MAIN)           -
// --------------------------------
// ** Configuration
defined('SBUIADMIN_MENU_CONFIGURATION') or define("SBUIADMIN_MENU_CONFIGURATION", "Configuration");
defined('SBUIADMIN_MENU_CONFIGURATION_GENERAL') or define("SBUIADMIN_MENU_CONFIGURATION_GENERAL", "Générale");
defined('SBUIADMIN_MENU_CONFIGURATION_CACHE') or define("SBUIADMIN_MENU_CONFIGURATION_CACHE", "Cache");
defined('SBUIADMIN_MENU_CONFIGURATION_DASHBOARD') or define("SBUIADMIN_MENU_CONFIGURATION_DASHBOARD", "Dashboard");
defined('SBUIADMIN_MENU_CONFIGURATION_TOOLBAR') or define("SBUIADMIN_MENU_CONFIGURATION_TOOLBAR", "Toolbar CKEditor");
defined('SBUIADMIN_MENU_CONFIGURATION_THEME') or define("SBUIADMIN_MENU_CONFIGURATION_THEME", "Thème");
defined('SBUIADMIN_MENU_CONFIGURATION_THEME_INFOS') or define("SBUIADMIN_MENU_CONFIGURATION_THEME_INFOS", "Thème infos");
defined('SBUIADMIN_MENU_CONFIGURATION_BOOTSTRAP') or define("SBUIADMIN_MENU_CONFIGURATION_BOOTSTRAP", "Bootstrap Samples");
// ** Logaccess
defined('SBUIADMIN_MENU_LOG') or define("SBUIADMIN_MENU_LOG", "Journaux");
// ** Utilisateurs
defined('SBUIADMIN_MENU_USERS') or define("SBUIADMIN_MENU_USERS", "Utilisateurs");
defined('SBUIADMIN_MENU_ALL_USERS') or define("SBUIADMIN_MENU_ALL_USERS", "Tous les utilisateurs");
defined('SBUIADMIN_MENU_BLOCKED_IPS') or define("SBUIADMIN_MENU_BLOCKED_IPS", "IPs Bloquées");
defined('SBUIADMIN_MENU_GRAVATAR') or define("SBUIADMIN_MENU_GRAVATAR", "Gravatar");
// ** Medias
defined('SBUIADMIN_MENU_MEDIAS') or define("SBUIADMIN_MENU_MEDIAS",	"Médias");
// ** CMS Config
defined('SBUIADMIN_MENU_CMSCONFIG') or define("SBUIADMIN_MENU_CMSCONFIG", "CMS config");
defined('SBUIADMIN_MENU_CMSCONFIG_HEADERFOOTER') or define("SBUIADMIN_MENU_CMSCONFIG_HEADERFOOTER",	"Header / Footer");
defined('SBUIADMIN_MENU_CMSCONFIG_CSS') or define("SBUIADMIN_MENU_CMSCONFIG_CSS", "CSS");
defined('SBUIADMIN_MENU_CMSCONFIG_JAVASCRIPT') or define("SBUIADMIN_MENU_CMSCONFIG_JAVASCRIPT",	"JAVASCRIPT");
defined('SBUIADMIN_MENU_CMSCONFIG_COMINGSOON') or define("SBUIADMIN_MENU_CMSCONFIG_COMINGSOON",	"Coming Soon");
defined('SBUIADMIN_MENU_CMSCONFIG_MULTILANG') or define("SBUIADMIN_MENU_CMSCONFIG_MULTILANG", "Multilangue");
defined('SBUIADMIN_MENU_CMSCONFIG_PLUGINS') or define("SBUIADMIN_MENU_CMSCONFIG_PLUGINS", "Plugins");
defined('SBUIADMIN_MENU_CMSCONFIG_FONTS') or define("SBUIADMIN_MENU_CMSCONFIG_FONTS", "Polices");
defined('SBUIADMIN_MENU_CMSCONFIG_SEO') or define("SBUIADMIN_MENU_CMSCONFIG_SEO", "SEO");
// *** Menu
defined('SBUIADMIN_MENU_MENU') or define("SBUIADMIN_MENU_MENU", "Menu");
// ** Actualites
defined('SBUIADMIN_MENU_NEWS') or define("SBUIADMIN_MENU_NEWS", "Actualités");
defined('SBUIADMIN_MENU_NEWS_ALL') or define("SBUIADMIN_MENU_NEWS_ALL", "Tous les articles");
defined('SBUIADMIN_MENU_NEWS_CATEGORIES') or define("SBUIADMIN_MENU_NEWS_CATEGORIES", "Toutes les catégories");
defined('SBUIADMIN_MENU_NEWS_SETTINGS') or define("SBUIADMIN_MENU_NEWS_SETTINGS", "Paramètres");
// ** Pages
defined('SBUIADMIN_MENU_PAGES') or define("SBUIADMIN_MENU_PAGES", "Pages");
defined('SBUIADMIN_MENU_PAGES_ALL') or define("SBUIADMIN_MENU_PAGES_ALL", "Toutes les pages");
defined('SBUIADMIN_MENU_PAGES_BLOCS') or define("SBUIADMIN_MENU_PAGES_BLOCS", "Tous les blocs");
// *** Contact
defined('SBUIADMIN_MENU_CONTACT') or define("SBUIADMIN_MENU_CONTACT", "Contact");
defined('SBUIADMIN_MENU_CONTACT_ALL') or define("SBUIADMIN_MENU_CONTACT_ALL", "Tous les formulaires");
defined('SBUIADMIN_MENU_CONTACT_SETTINGS') or define("SBUIADMIN_MENU_CONTACT_SETTINGS", "Paramètres");
// ** Sliders
defined('SBUIADMIN_MENU_SLIDERS') or define("SBUIADMIN_MENU_SLIDERS", "Sliders");
// ** Tabbs
defined('SBUIADMIN_MENU_TABBS') or define("SBUIADMIN_MENU_TABBS", "Tabbs");
defined('SBUIADMIN_MENU_TABBS_ALL') or define("SBUIADMIN_MENU_TABBS_ALL", "Tous les tabbs");
defined('SBUIADMIN_MENU_TABBS_ONGLETS') or define("SBUIADMIN_MENU_TABBS_ONGLETS", "Tous les onglets");
// ** Tableaux
defined('SBUIADMIN_MENU_TABLES') or define("SBUIADMIN_MENU_TABLES", "Tableaux");

?>
