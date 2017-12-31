<?php
/**
 * Plugin Name: SBUIADMIN NEWS
 * Description: Gestionnaire d'articles
 * File: EN Language
 * Version: 0.1.1
 * Author: BooBoo
 * Author URI: //www.informatux.com/
 */

 /** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'en_US.php') { 
	die('You cannot load this page directly.');
};

define('_CMS_NEWS_NEWS',					'News');
define('_CMS_NEWS_ALLNEWS',					"All news");
define('_CMS_NEWS_NONEWS',					'No item for this category!');
define('_CMS_NEWS_NOCATEGORIES',			'No category available!');
define('_CMS_NEWS_READ_ITEM',				"Read item");
define('_CMS_NEWS_NOITEM',					'Item not available!');
define('_CMS_NEWS_ITEMNOTFOUND',			"Item not found");
define('_CMS_NEWS_ITEMINACTIVE',			"Item desactivated!");
define('_CMS_NEWS_LATEST_ITEM',				"Latest item");
define('_CMS_NEWS_OF',						"of");

?>