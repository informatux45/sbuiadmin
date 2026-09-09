<?php
/**
 * Plugin Name: SBUIADMIN SEARCH
 * Description: Search
 * File: EN Language
 * Version: 0.2.0
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 /** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'en_US.php') { 
	die('You cannot load this page directly.');
};

define('_CMS_SEARCH_TITLE',			'Search');
define('_CMS_SEARCH_PLACEHOLDER',	'Search this website...');
define('_CMS_SEARCH_SUBMIT',		'Search');
define('_CMS_SEARCH_LEGEND',		'Your search');
define('_CMS_SEARCH_TOOSHORT',		'Please type at least %d characters.');
define('_CMS_SEARCH_EMPTY',			'Type one or more words to start the search.');
define('_CMS_SEARCH_NORESULT',		'No result for &laquo;&nbsp;%s&nbsp;&raquo;.');
define('_CMS_SEARCH_RESULT',		'1 result for &laquo;&nbsp;%s&nbsp;&raquo;');
define('_CMS_SEARCH_RESULTS',		'%d results for &laquo;&nbsp;%s&nbsp;&raquo;');
define('_CMS_SEARCH_NOLINK',		'Content not published on the website');
define('_CMS_SEARCH_SEEIN',			'Seen in:');
define('_CMS_SEARCH_PREVIOUS',		'Previous');
define('_CMS_SEARCH_NEXT',			'Next');

// --- Source labels (result "type" column)
define('_CMS_SEARCH_SRC_PAGES',		'Page');
define('_CMS_SEARCH_SRC_NEWS',		'News');
define('_CMS_SEARCH_SRC_CONTACT',	'Form');
define('_CMS_SEARCH_SRC_TABLE',		'Table');
define('_CMS_SEARCH_SRC_TABBS',		'Tabs');
define('_CMS_SEARCH_SRC_DOWNLOAD',	'Download');
define('_CMS_SEARCH_SRC_GALLERY',	'Gallery');

?>
