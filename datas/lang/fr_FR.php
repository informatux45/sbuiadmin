<?php
/* ******************************* *
 * Main File                       *
 * ------------------------------- *
 * @link http://informatux.com/    *
 * @package CMS SBUIADMIN            *
 * @package LANG FR                *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */

/** Prevent direct access */
$sb_this_file = 'fr_FR.php';
if (basename($_SERVER['PHP_SELF']) == $sb_this_file) { 
	die('You cannot load this page directly.');
}; 

// ------------------------------------------------------------------
//                          GENERAL
// ------------------------------------------------------------------
define('_CMS_GLOBAL_HOME',							"Accueil");
define('_CMS_GLOBAL_YES',							"oui");
define('_CMS_GLOBAL_NO',							"non");
define('_CMS_GLOBAL_GO',							"Ok");
define('_CMS_GLOBAL_REQUIRED',						'Requis');
define('_CMS_GLOBAL_EDIT',							'Éditer');
define('_CMS_GLOBAL_DELETE',						'Effacer');
define('_CMS_GLOBAL_SUBMIT',						'Valider');
define('_CMS_GLOBAL_MORE',							'plus...');
define('_CMS_GLOBAL_ADD',							'Ajouter');
define('_CMS_GLOBAL_REPLY',							'Répondre');
define('_CMS_GLOBAL_MAIN',							'Principal');
define('_CMS_GLOBAL_MENU',							'Menu');
define('_CMS_GLOBAL_PLEASEWAIT',					'Merci de patienter');
define('_CMS_GLOBAL_FETCHING',						'Chargement ...');
define('_CMS_GLOBAL_TAKINGBACK',					'Retour là où vous étiez ...');
define('_CMS_GLOBAL_INFO',							'Information');
define('_CMS_GLOBAL_OPEN',							'Ouvrir');
define('_CMS_GLOBAL_CLOSE',							'Fermer');
define('_CMS_GLOBAL_SEARCH',						'Recherche');
define('_CMS_GLOBAL_RESULT',						'1 résultat');
define('_CMS_GLOBAL_RESULTS',						'%s résultats');
define('_CMS_GLOBAL_SEARCH_RESULT',					'Résultat de recherche');
define('_CMS_GLOBAL_ALL',							'Tout');
define('_CMS_GLOBAL_LOGIN',							'Connexion');
define('_CMS_GLOBAL_LOGOUT',						'Déconnexion');
define('_CMS_GLOBAL_USERNAME',						'Identifiant : ');
define('_CMS_GLOBAL_PASSWORD',						'Mot de passe : ');
define('_CMS_GLOBAL_SELECT',						'Sélectionner');
define('_CMS_GLOBAL_IMAGE',							'Image');
define('_CMS_GLOBAL_SEND',							'Envoi');
define('_CMS_GLOBAL_CANCEL',						'Annuler');
define('_CMS_GLOBAL_ASCENDING',						'Ordre croissant');
define('_CMS_GLOBAL_DESCENDING',					'Ordre décroissant');
define('_CMS_GLOBAL_BACK',							'Retour');
define('_CMS_GLOBAL_NOTITLE',						'Aucun titre');
define('_CMS_GLOBAL_SECOND',						'1 seconde');
define('_CMS_GLOBAL_SECONDS',						'%s secondes');
define('_CMS_GLOBAL_MINUTE',						'1 minute');
define('_CMS_GLOBAL_MINUTES',						'%s minutes');
define('_CMS_GLOBAL_HOUR',							'1 heure');
define('_CMS_GLOBAL_HOURS',							'%s heures');
define('_CMS_GLOBAL_DAY',							'1 jour');
define('_CMS_GLOBAL_DAYS',							'%s jours');
define('_CMS_GLOBAL_WEEK',							'1 semaine');
define('_CMS_GLOBAL_WEEKS',							'%s semaines');
define('_CMS_GLOBAL_MONTH',							'1 mois');
define('_CMS_GLOBAL_MONTHS',						'%s mois');
define('_CMS_GLOBAL_YEAR',							'1 année');
define('_CMS_GLOBAL_YEARS',							'%s années');
define('_CMS_GLOBAL_DATE',							'Date');
define('_CMS_GLOBAL_DATESTRING',					'd/m/Y H:i:s');
define('_CMS_GLOBAL_MEDIUMDATESTRING',				'd/m/Y H:i');
define('_CMS_GLOBAL_SHORTDATESTRING',				'd/m/Y');
define('_CMS_GLOBAL_404',							"La page que vous recherchez n'existe pas.<br />Vous pouvez <a href='%s'>revenir à la page d'accueil</a> ou effectuer une nouvelle recherche.");
define('_CMS_GLOBAL_LEARN_MORE',					"En savoir +");
define('_CMS_GLOBAL_ABOUT',							"A propos");
define('_CMS_GLOBAL_ABOUT_US',						"A propos de nous");
define('_CMS_GLOBAL_PAGE_NOT_FOUND',				"Page introuvable, désolé !");

// ------------------------------------------------------------------
//               Get ALL Language Files Module
// ------------------------------------------------------------------
$sb_path_language_file = dirname(__DIR__) . DIRECTORY_SEPARATOR .  'modules';
$sb_path_dir           = new DirectoryIterator($sb_path_language_file);
foreach ($sb_path_dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
		include_once($sb_path_language_file . DIRECTORY_SEPARATOR . $fileinfo->getFilename() . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $sb_this_file);
    }
}

?>