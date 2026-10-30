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
defined('SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR') OR define("SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR", "Connexion de [%s] depuis [%s] refus&eacute;e : v&eacute;rification anti-robot (ALTCHA) &eacute;chou&eacute;e");
defined('SBUIADMIN_MSG_LOG_ACCESS_LOCKED') OR define("SBUIADMIN_MSG_LOG_ACCESS_LOCKED", "Connexion de [%s] depuis [%s] refus&eacute;e : blocage temporaire (trop d&#39;&eacute;checs)");
defined('SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR2') OR define("SBUIADMIN_MSG_LOG_ACCESS_CAPTCHA_ERROR2", "Utilisateur [%s] identifi&eacute; depuis [%s] avec une erreur captcha [%s]->[%s]");
defined('SBUIADMIN_MSG_LOG_ACCESS_USER_ERROR') OR define("SBUIADMIN_MSG_LOG_ACCESS_USER_ERROR", "Utilisateur d&eacute;sactiv&eacute; [%s] a essay&eacute; de s&#39;identifier depuis [%s]");
defined('SBUIADMIN_MSG_LOG_ACCESS_NOGRANTED') OR define("SBUIADMIN_MSG_LOG_ACCESS_NOGRANTED", "Identification non autoris&eacute;e depuis [%s] - propablement un intru");
defined('SBUIADMIN_MSG_LOG_ACCESS_MISSING') OR define("SBUIADMIN_MSG_LOG_ACCESS_MISSING", "Erreur d&#39;acc&egrave;s depuis [%s] - identifiant et&#47;ou mot de passe manquant");
defined('SBUIADMIN_MSG_LOG_IMPERSONATE_START') OR define("SBUIADMIN_MSG_LOG_IMPERSONATE_START", "Administrateur [%s] a pris l&#39;identit&eacute; de [%s] depuis [%s]");
defined('SBUIADMIN_MSG_LOG_IMPERSONATE_STOP') OR define("SBUIADMIN_MSG_LOG_IMPERSONATE_STOP", "Administrateur [%s] a rendu l&#39;identit&eacute; de [%s] apr&egrave;s %d min");
 
 // ** Control access message
defined('SBUIADMIN_MSG_ERROR_E1') OR define("SBUIADMIN_MSG_ERROR_E1", "V&eacute;rification anti-robot &eacute;chou&eacute;e : merci de r&eacute;essayer.");
defined('SBUIADMIN_MSG_ERROR_E2') OR define("SBUIADMIN_MSG_ERROR_E2", "Login incorrect");
defined('SBUIADMIN_MSG_ERROR_E3') OR define("SBUIADMIN_MSG_ERROR_E3", "Login manquant");
defined('SBUIADMIN_MSG_ERROR_E4') OR define("SBUIADMIN_MSG_ERROR_E4", "Compte d&eacute;sactiv&eacute;");
defined('SBUIADMIN_MSG_ERROR_E5') OR define("SBUIADMIN_MSG_ERROR_E5", "Double authentification impossible : aucune adresse e-mail valide sur ce compte. Contactez l&#39;administrateur.");
defined('SBUIADMIN_MSG_ERROR_E6') OR define("SBUIADMIN_MSG_ERROR_E6", "Double authentification impossible : l&#39;envoi du code par e-mail a &eacute;chou&eacute;. R&eacute;essayez ou contactez l&#39;administrateur.");
defined('SBUIADMIN_MSG_ERROR_E7') OR define("SBUIADMIN_MSG_ERROR_E7", "Trop d&#39;essais de code : reconnectez-vous.");
defined('SBUIADMIN_MSG_ERROR_E8') OR define("SBUIADMIN_MSG_ERROR_E8", "Trop de tentatives de connexion &eacute;chou&eacute;es : r&eacute;essayez dans quelques minutes.");

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
defined('SBUIADMIN_MENU_CONFIGURATION_SESSION') or define("SBUIADMIN_MENU_CONFIGURATION_SESSION", "Session");
defined('SBUIADMIN_MENU_CONFIGURATION_SERVER') or define("SBUIADMIN_MENU_CONFIGURATION_SERVER", "Serveur");
defined('SBUIADMIN_MENU_CONFIGURATION_CACHE') or define("SBUIADMIN_MENU_CONFIGURATION_CACHE", "Cache");
defined('SBUIADMIN_MENU_CONFIGURATION_DASHBOARD') or define("SBUIADMIN_MENU_CONFIGURATION_DASHBOARD", "Dashboard");
defined('SBUIADMIN_MENU_CONFIGURATION_TOOLBAR') or define("SBUIADMIN_MENU_CONFIGURATION_TOOLBAR", "Toolbar CKEditor");
defined('SBUIADMIN_MENU_CONFIGURATION_THEME') or define("SBUIADMIN_MENU_CONFIGURATION_THEME", "Thème");
defined('SBUIADMIN_MENU_CONFIGURATION_THEME_INFOS') or define("SBUIADMIN_MENU_CONFIGURATION_THEME_INFOS", "Thème infos");
defined('SBUIADMIN_MENU_CONFIGURATION_BOOTSTRAP') or define("SBUIADMIN_MENU_CONFIGURATION_BOOTSTRAP", "Thème sample");
defined('SBUIADMIN_MENU_CONFIGURATION_UPDATE') OR define("SBUIADMIN_MENU_CONFIGURATION_UPDATE", "Mise à jour");
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
defined('SBUIADMIN_MENU_CMSCONFIG_COMINGSOON') or define("SBUIADMIN_MENU_CMSCONFIG_COMINGSOON",	"Maintenance");
defined('SBUIADMIN_MENU_CMSCONFIG_MULTILANG') or define("SBUIADMIN_MENU_CMSCONFIG_MULTILANG", "Multilangue");
defined('SBUIADMIN_MENU_CMSCONFIG_PLUGINS') or define("SBUIADMIN_MENU_CMSCONFIG_PLUGINS", "Plugins");
defined('SBUIADMIN_MENU_CMSCONFIG_SEO') or define("SBUIADMIN_MENU_CMSCONFIG_SEO", "SEO");
// *** Menu
defined('SBUIADMIN_MENU_MENU') or define("SBUIADMIN_MENU_MENU", "Menu");
// ** Pages
defined('SBUIADMIN_MENU_PAGES') or define("SBUIADMIN_MENU_PAGES", "Pages");
defined('SBUIADMIN_MENU_PAGES_ALL') or define("SBUIADMIN_MENU_PAGES_ALL", "Toutes les pages");
defined('SBUIADMIN_MENU_PAGES_BLOCS') or define("SBUIADMIN_MENU_PAGES_BLOCS", "Tous les blocs");
// ** Menus des modules : datas/modules/<nom>/admin/lang/french.php

?>
