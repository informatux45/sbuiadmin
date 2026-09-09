<?php
/**
 * Plugin Name: SBUIADMIN SEARCH
 * Description: Recherche
 * File: FR Language
 * Version: 0.2.0
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 /** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'fr_FR.php') { 
	die('You cannot load this page directly.');
};

define('_CMS_SEARCH_TITLE',			'Recherche');
define('_CMS_SEARCH_PLACEHOLDER',	'Rechercher sur le site...');
define('_CMS_SEARCH_SUBMIT',		'Rechercher');
define('_CMS_SEARCH_LEGEND',		'Votre recherche');
define('_CMS_SEARCH_TOOSHORT',		'Merci de saisir au moins %d caractères.');
define('_CMS_SEARCH_EMPTY',			'Saisissez un ou plusieurs mots pour lancer la recherche.');
define('_CMS_SEARCH_NORESULT',		'Aucun résultat pour «&nbsp;%s&nbsp;».');
define('_CMS_SEARCH_RESULT',		'1 résultat pour «&nbsp;%s&nbsp;»');
define('_CMS_SEARCH_RESULTS',		'%d résultats pour «&nbsp;%s&nbsp;»');
define('_CMS_SEARCH_NOLINK',		'Contenu non publié sur le site');
define('_CMS_SEARCH_SEEIN',			'Vu dans&nbsp;:');
define('_CMS_SEARCH_PREVIOUS',		'Précédent');
define('_CMS_SEARCH_NEXT',			'Suivant');

// --- Libelles des sources (colonne "type" du resultat)
define('_CMS_SEARCH_SRC_PAGES',		'Page');
define('_CMS_SEARCH_SRC_NEWS',		'Actualité');
define('_CMS_SEARCH_SRC_CONTACT',	'Formulaire');
define('_CMS_SEARCH_SRC_TABLE',		'Tableau');
define('_CMS_SEARCH_SRC_TABBS',		'Onglets');
define('_CMS_SEARCH_SRC_DOWNLOAD',	'Téléchargement');
define('_CMS_SEARCH_SRC_GALLERY',	'Galerie');

?>
