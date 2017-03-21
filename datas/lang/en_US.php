<?php
/* ******************************* *
 * Main File                       *
 * ------------------------------- *
 * @link http://informatux.com/    *
 * @package CMS SBMAGIC            *
 * @package LANG US                *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */

/** Prevent direct access */
$sb_this_file = 'en_US.php';
if (basename($_SERVER['PHP_SELF']) == $sb_this_file) { 
	die('You cannot load this page directly.');
}; 

// ------------------------------------------------------------------
//                          GENERAL
// ------------------------------------------------------------------
define('_CMS_GLOBAL_HOME',							"Home");
define('_CMS_GLOBAL_YES',							"yes");
define('_CMS_GLOBAL_NO',							"no");
define('_CMS_GLOBAL_GO',							"Ok");
define('_CMS_GLOBAL_REQUIRED',						'Required');
define('_CMS_GLOBAL_EDIT',							'Edit');
define('_CMS_GLOBAL_DELETE',						'Delete');
define('_CMS_GLOBAL_SUBMIT',						'Submit');
define('_CMS_GLOBAL_MORE',							'more...');
define('_CMS_GLOBAL_ADD',							'Add');
define('_CMS_GLOBAL_REPLY',							'Reply');
define('_CMS_GLOBAL_MAIN',							'Main');
define('_CMS_GLOBAL_PLEASEWAIT',					'Please wait');
define('_CMS_GLOBAL_FETCHING',						'Loading ...');
define('_CMS_GLOBAL_TAKINGBACK',					'Taking you back to where you were...');
define('_CMS_GLOBAL_INFO',							'Information');
define('_CMS_GLOBAL_OPEN',							'Open');
define('_CMS_GLOBAL_CLOSE',							'Close');
define('_CMS_GLOBAL_SEARCH',						'Search');
define('_CMS_GLOBAL_RESULT',						'1 result');
define('_CMS_GLOBAL_RESULTS',						'%s results');
define('_CMS_GLOBAL_SEARCH_RESULT',					'Search result');
define('_CMS_GLOBAL_ALL',							'All');
define('_CMS_GLOBAL_LOGIN',							'Login');
define('_CMS_GLOBAL_LOGOUT',						'Logout');
define('_CMS_GLOBAL_USERNAME',						'Username: ');
define('_CMS_GLOBAL_PASSWORD',						'Password: ');
define('_CMS_GLOBAL_SELECT',						'Select');
define('_CMS_GLOBAL_IMAGE',							'Image');
define('_CMS_GLOBAL_SEND',							'Send');
define('_CMS_GLOBAL_CANCEL',						'Cancel');
define('_CMS_GLOBAL_ASCENDING',						'Ascending order');
define('_CMS_GLOBAL_DESCENDING',					'Descending order');
define('_CMS_GLOBAL_BACK',							'Back');
define('_CMS_GLOBAL_NOTITLE',						'No title');
define('_CMS_GLOBAL_SECOND',						'1 second');
define('_CMS_GLOBAL_SECONDS',						'%s seconds');
define('_CMS_GLOBAL_MINUTE',						'1 minute');
define('_CMS_GLOBAL_MINUTES',						'%s minutes');
define('_CMS_GLOBAL_HOUR',							'1 hour');
define('_CMS_GLOBAL_HOURS',							'%s hours');
define('_CMS_GLOBAL_DAY',							'1 day');
define('_CMS_GLOBAL_DAYS',							'%s days');
define('_CMS_GLOBAL_WEEK',							'1 week');
define('_CMS_GLOBAL_WEEKS',							'%s weeks');
define('_CMS_GLOBAL_MONTH',							'1 month');
define('_CMS_GLOBAL_MONTHS',						'%s months');
define('_CMS_GLOBAL_YEAR',							'1 year');
define('_CMS_GLOBAL_YEARS',							'%s years');
define('_CMS_GLOBAL_DATE',							'Date');
define('_CMS_GLOBAL_DATESTRING',					'd/m/Y H:i:s');
define('_CMS_GLOBAL_MEDIUMDATESTRING',				'd/m/Y H:i');
define('_CMS_GLOBAL_SHORTDATESTRING',				'd/m/Y');
define('_CMS_GLOBAL_404',							"We are sorry but the page you are looking for does not exist.<br />You could <a href='%s'>return to the home page</a> or search using the search box below.");
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