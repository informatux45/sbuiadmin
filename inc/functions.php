<?php
/* ******************************* *
 * Function File                   *
 * ------------------------------- *
 * @link https://informatux.com/    *
 * @package Functions              *
 * @package SBUIADMIN              *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */

/** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'functions.php') { 
	die('You cannot load this page directly.');
}; 
 
/**
* Check if Template CMS exist
* @param	string	$tpl	Template path without SB_MODULES_DIR
* @return bool
*/
if (!function_exists("sbCheckTplExist")) {
	function sbCheckTplExist($tpl) {
		$tpl_path = SB_MODULES_DIR . $tpl;
		if (file_exists($tpl_path))
			return true;
		else
			return false;
	}
}

/**
* Get js file in a PHP Page
* @param	url 	file		Path file
* @return link (string)
*/
if (!function_exists("sbJsLink")) {
	function sbJsLink($file) {
		//we know it will exists within the HTTP Context
		return sprintf("<script type=\"text/javascript\" src=\"%s\"></script>", $file);
	}
}

/**
* Get if a string contains illegal characters
* @param	string 		string		Texte
* @return string
*/
if (!function_exists("sbIllegalCharacter")) {
	function sbIllegalCharacter($string) {
		$illegal_caracters = array('À','Á','Â','Ã','Ä','Å','à','á','â','ã','ä','å','Ò','Ó','Ô','Õ','Ö','Ø','ò','ó','ô','õ','ö','ø','È','É','Ê','Ë','è','é','ê','ë','Ç','ç','Ì','Í','Î','Ï','ì','í','î','ï','Ù','Ú','Û','Ü','ù','ú','û','ü','ÿ','Ñ','ñ','&','~','"','#','{','}','(',')','[',']','-','_','`','/','^','¨','@','°','+','=','$','£','¤','%','*','µ','?',',',';','.',':','!','§','n','”','“','¢','»','ł','|','ß','ð','đ','ŋ','ħ','j','ĸ','ł','µ','þ','œ','→','↓','←','ŧ','¶','€','«','æ','¬','¹');
	
		if (strpos($string, $illegal_caracters) === true)
			return true;
		else
			return false;
	}
}

/**
* Get a valid string without illegal characters
* @param	string 		string		Texte
* @return string
*/
if (!function_exists("sbRewriteString")) {
	function sbRewriteString($string) {
		global $sbsanitize;
		$noValidString = trim($sbsanitize->displayText($string));
		$noValidString = preg_replace('`\s+`', '-', trim($noValidString));
		$noValidString = str_replace("'", "-", $noValidString);
		$noValidString = str_replace('"', '-', $noValidString);
		$noValidString = preg_replace('`_+`', '-', trim($noValidString));
		$caracters_in  = array(' ', '?', '!', '.', ',', ':', "'", '&', '(', ')', '-', '/', '%', '=', '[', ']');
		$caracters_out = array('-', '', '', '', '-', '-', '-', '-', '', '', '-', '-', '-', '', '', '');
		$noValidString = str_replace($caracters_in, $caracters_out, $noValidString);
		$noValidString = str_replace("------", "-", $noValidString);
		$noValidString = str_replace("-----", "-", $noValidString);
		$noValidString = str_replace("----", "-", $noValidString);
		$noValidString = str_replace("---", "-", $noValidString);
		$noValidString = str_replace("--", "-", $noValidString);
		$accents       = array('À','Á','Â','Ã','Ä','Å','à','á','â','ã','ä','å','Ò','Ó','Ô','Õ','Ö','Ø','ò','ó','ô','õ','ö','ø','È','É','Ê','Ë','è','é','ê','ë','Ç','ç','Ì','Í','Î','Ï','ì','í','î','ï','Ù','Ú','Û','Ü','ù','ú','û','ü','ÿ','Ñ','ñ');
		$ssaccents     = array('A','A','A','A','A','A','a','a','a','a','a','a','O','O','O','O','O','O','o','o','o','o','o','o','E','E','E','E','e','e','e','e','C','c','I','I','I','I','i','i','i','i','U','U','U','U','u','u','u','u','y','N','n');
		$validString   = str_replace($accents, $ssaccents, $noValidString);
	
		return ($validString);
	}
}

/**
 * Create / Start to Increment Log file
 * @return string
 */
if (!function_exists("sbLogStart")) {
	function sbLogStart() {
		$dir = SB_PATH;  // __DIR__
		if (SBDEBUG) file_put_contents ( $dir . 'log.txt' , "==========================\n", FILE_APPEND);
	}
}

/**
 * Increment Log File
 * @return string
 */
if (!function_exists("sbLog")) {
	function sbLog ($var) {
		$dir = SB_PATH;  // __DIR__
		if (SBDEBUG) {
			file_put_contents ( $dir . 'log.txt' , var_export($var , true), FILE_APPEND);
			file_put_contents ( $dir . 'log.txt' , "\n", FILE_APPEND);
		}
	}
}

/**
* Get Current Url
* @return string
*/
if (!function_exists("sbGetCurrentUrl")) {
	function sbGetCurrentUrl() {
		$request_url = 'http' . (($_SERVER['HTTPS'] == 'on') ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		return $request_url;
	}
}

/**
 * Get Current Url Without arguments
 * @return string
 */
if (!function_exists("sbGetCurrentUrlWithoutArgs")) {
	function sbGetCurrentUrlWithoutArgs() {
		$request_url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
		// Check last string character
		if (substr($request_url, -1) == '/')
			return $request_url;
		else
			return $request_url . '/';
	}
}

/**
* Get Rewrite URL (SEO PHP)
* @param	string	normal_url	No rewriting url
* @param	string	seo_url		Rewriting url
* @param	string	encode		True or False (urlencode)
* @return string
*/
if (!function_exists("sbGetSeoUrl")) {
	function sbGetSeoUrl($normal_url, $seo_url, $encode = false) {
		$base_url   = SB_URL;
		if (SBREWRITEURL === true) {
			if ($encode)
				return urlencode($base_url . $seo_url);
			else
				return $base_url . $seo_url . DIRECTORY_SEPARATOR;
		}
	
		return $base_url . $normal_url;
	}
}

/**
* Get Rewrite URL (SEO)
* @param	string	subdirectory	Site subdirectory visible in url (SBSITESUBDIRECTORY) without trailing slashes
* @return string (core display)
*/
if (!function_exists("sbRewriteUrl")) {
	function sbRewriteUrl($subdirectory) {
		// --- Initialization
		global $sbsanitize;
		// --- Remove subdirectory from url
		$path = str_replace(DIRECTORY_SEPARATOR . $subdirectory . DIRECTORY_SEPARATOR, "", $_SERVER['REQUEST_URI']);
		// --- Trim leading slash(es)
		$path = trim($path, DIRECTORY_SEPARATOR);
		// --- Split path on slashes
		$elements = explode('/', $path);
		// --- No path elements means home
		if (count($elements) == 0) {
			// --- Homepage
			return false;
		} else {
			// --- Initialization
			$type = false;
			// --- Switch value
			$switch_element = $elements[0];
			// --- Check if key id OR args for modules/pages
			$pos = strpos($switch_element, '?');
			$switch_element = ($pos !== false) ? "" : $switch_element;
			// --- Get / Check modules List
			$dir = SB_MODULES_DIR;
			$result_modules_dir = array();	 
			$modules_dir = scandir($dir);
			foreach ($modules_dir as $key => $value) {
				if (!in_array($value,array(".","..","pages","contact","search"))) {
					if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
						if ($switch_element == $value)
							$type = 'module';
					}
				}
			}
			// --- Switch array elements
			switch($switch_element) {
				default:
					if ($type === false) {
						// === PAGES ID ===
						$_GET['id'] = $_REQUEST['id'] = $sbsanitize->stopXSS($switch_element);
						return ($switch_element) ? 'pages' : 'index';
					} else {
						// === MODULES ===
						// --- Check if key id OR args
						$pos = strpos($elements[1], '?');
						$count_elements = ($pos !== false) ? count($elements) - 1 : count($elements);
						if ($count_elements > 3) {
							// Array for MODULE
							// Schema:
							// [0] 1 => Module
							// [1] 2 => op
							// [2] 3 => id
							// [3] 4 => txt
							$_GET['op']  = ($elements[1]) ? $elements[1] : NULL;
							$_GET['id']  = ($elements[2]) ? $elements[2] : NULL;
							$_GET['txt'] = ($elements[3]) ? $elements[3] : NULL;
						}
						// Return switch element (default module perform)
						return $sbsanitize->stopXSS($switch_element);
					}
				break;
			}
		}	
	}
}

/**
* Get Stats Script
* @param	string	sb_pages_title	Page title
* @return code (include stats script)
*/
function sbGetStats($sb_pages_title) {
	$nom_page = $sb_pages_title;
	$Racine_abs = str_replace($_SERVER['PHP_SELF'], "", $_SERVER['SCRIPT_FILENAME']);
	$sb_require_front = 'go';
	$sb_require_admin = SBADMIN;
	require_once(SB_ADMIN_DIR . 'inc/plugins/stats/visiteur.php');
	// --------------------------------
}

/**
 * Get Shortcode for page
 * @param	string	$string
 * @return html content
 */
if (!function_exists("sbGetShortcode")) {
	function sbGetShortcode($string, $smarty = false) {
		// Get the shortcode(s)
		preg_match_all( '/\[CS(.*?)\]/', $string, $matches );
		// Get if is one more shortcode
		$result = $shortcode = $param = array();
		for($i = 0; $i < count($matches[1]); $i++) {
			$result[]    = explode(" ", trim($matches[1][$i]));
			$shortcode[] = trim($matches[0][$i]);
		}
		// Check if result exist ;-)
		if (!empty($result)) {
			// Get the result	
			for($i = 0; $i < count($result); $i++) {
				for($j = 0; $j < count($result[$i]); $j++) {
					// List all keys => values
					list($key, $value) = explode("=", $result[$i][$j]);
					// --- Get name of shortcode function
					if ($key == 'name') $shortcode_function = $value;
					// --- Assign keys
					$param[$key] = $value;
				}
				// ------------------------------------------------
				// Do function module if exist (shortcode function)
				// ------------------------------------------------
				// --- Check if exists
				$function_name = 'shortcode_' . $shortcode_function;
				if (function_exists($function_name)) {
					// --- Replace SHORTCODE By Function HTML Return
					$string = str_replace($shortcode[$i], $function_name($param), $string);
				}
			}
			return $string;
		
		} else {
			// Return string without Shortcode
			return $string;
		}
	}
}

/**
* Get SEO Meta Keywords / Description
* @return html
*/
if (!function_exists("sbGetSeo")) {
	function sbGetSeo($text = '', $key = '') {
		global $sbsql, $sbsanitize;
		$cms_seo = '';
		$table   = _AM_DB_PREFIX . 'sb_config';
		
		// --- Get CMS SEO
		$query   = "SELECT config, content FROM $table WHERE config = 'seo-keywords' OR config = 'seo-description'";
		$request = $sbsql->query($query);
		$result  = $sbsql->toarray($request);
		
		if ($result) {
			foreach($result as $row) {
				$cs[$row['config']] = sb_utf8_encode($row['content']);
			}
			
			if ($key == 'keywords') {
				return $sbsanitize->sTrim($text . ',' . $sbsanitize->displayText($cs['seo-keywords']), ",");
			}
			
			if ($key == 'description') {
				return $sbsanitize->sTrim($text . ' ' . $sbsanitize->displayText($cs['seo-description']));
			}
		}		
	}
}

/**
* Get Date by Shortcode
* @return string (current server year)
*/
if (!function_exists("shortcode_sbyear")) {
	function shortcode_sbyear($param = '') {
		return (date('Y'));
	}
}

/**
 * Get Real User IP
 * @return string
 */
if (!function_exists("sbGetUserIP")) {
	function sbGetUserIP() {
		// Initialize
		$ip = "";
		// Get real visitor IP behind CloudFlare network
		if ( isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ) {
			$_SERVER['REMOTE_ADDR']    = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			// ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			// ip pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}
if (!function_exists("insert_sbGetUserIP")) {
	function insert_sbGetUserIP() {
		return (function_exists("sbGetUserIP")) ? sbGetUserIP() : false;
	}
}

/**
 * Check if IP is blocked
 * @return string/void		expiration time OR false
 */
if (!function_exists("sbIsBlockedIP")) {
	function sbIsBlockedIP( $ip = '') {
		global $sbsql, $sbsanitize, $sbflood;
		if (class_exists('Memcache') && extension_loaded('memcache') && function_exists('memcache_connect')) {
			$time_flood   = $sbflood->expiration;    // 1 jour; 24 heures; 60 minutes; 60 secondes
			$how_many     = $sbflood->how_many;      // Combien de fois avant le blocage définitif
			$last_request = $sbflood->last_request;  // Derniere requete en secondes
		} else {
			$time_flood   = 1 * 24 * 60 * 60; // 1 jour; 24 heures; 60 minutes; 60 secondes
			$how_many     = 3;  // Combien de fois avant le blocage définitif
			$last_request = 2;  // Derniere requete en secondes
		}
		$table   = _AM_DB_PREFIX . 'sb_blocked_ip';
		$user_ip = trim( $sbsanitize->stopXSS( (empty($ip)) ? sbGetUserIP() : $ip ) );
		
		// Supprimer tout ceux dont le temps a expiré
		$sbsql->query("DELETE FROM $table WHERE expirationtime + $time_flood < " . time());
		// Chercher si l'ip est présente dans la table
		$query   = "SELECT ip FROM $table WHERE ip = '$user_ip'";
		$request = $sbsql->query($query);
		$user    = $sbsql->assoc($request);

		return ($user) ? $user['ip'] : false;
	}
}
if (!function_exists("insert_sbIsBlockedIP")) {
	function insert_sbIsBlockedIP() {
		return (function_exists("sbIsBlockedIP")) ? sbIsBlockedIP() : false;
	}
}

/**
 * Check if IP is blocked
 * @return string/void		expiration time OR false
 */
if (!function_exists("sbGetInfoBlockedIP")) {
	function sbGetInfoBlockedIP( $ip = '') {
		global $sbsql, $sbsanitize;
		$table   = _AM_DB_PREFIX . 'sb_blocked_ip';
		$user_ip = trim( $sbsanitize->stopXSS( (empty($ip)) ? sbGetUserIP() : $ip ) );

		$query   = "SELECT * FROM $table WHERE ip = '$user_ip'";
		$request = $sbsql->query($query);
		$user    = $sbsql->assoc($request);
		
		return $user;
	}
}
if (!function_exists("insert_sbGetInfoBlockedIP")) {
	function insert_sbGetInfoBlockedIP() {
		return (function_exists("sbGetInfoBlockedIP")) ? sbGetInfoBlockedIP() : false;
	}
}


// --------------------------------
// Smarty functions
// --------------------------------

/**
* Get Menu CMS
* @param	string	mclass	Define CSS class
* @param	string	mid		Define CSS id
* @param	string	mtag	Define Smarty Tag From SQL
* @param	string	mlang	Define Language (Global Session)
* @return string
*/
if (!function_exists("insert_sbGetMenuCms")) {
	function insert_sbGetMenuCms($param) {
		global $sbsql, $sbsanitize;
		$table_menu = _AM_DB_PREFIX . 'sb_menu';
		$table_page = _AM_DB_PREFIX . 'sb_pages';
		$menu_tag   = trim($param['mtag']);
		
		$query   = "SELECT * FROM $table_menu WHERE tag = '$menu_tag' AND active = '1'";
		$request = $sbsql->query($query);
		$assoc   = $sbsql->assoc($request);
	
		$menu_ul_class = trim($param['mclass']);
		$menu_ul_id    = trim($param['mid']);
		$menu_lang     = trim($param['mlang']);
		$menu_type     = (isset($param['mtype'])) ? trim($param['mtype']) : '';
		
		$menu_entries  = explode("|", $assoc['pages']);
		
		// --- Initialization
		if (!isset($menu_type) || $menu_type != 'option') $menu   = '<ul id="' . $menu_ul_id . '" class="' . $menu_ul_class . '">';
		$active = '';
	
		foreach($menu_entries as $entry) {
			// Get Page Info
			$query2   = "SELECT * FROM $table_page WHERE id = '$entry' AND active = '1'";
			$request2 = $sbsql->query($query2);
			$assoc2   = $sbsql->assoc($request2);
			// Check if entry is actived AND exist
			if ($assoc2) {
				// Check if active menu
				$seo_url_rewrite = $sbsanitize->sTrim(strtolower(sbRewriteString($assoc2['seo_url'])));
				$seo_url_id      = (isset($_GET['id'])) ? $sbsanitize->sTrim($_GET['id']) : '';
				// Seo / Normal URL
				$seo_normal_url = sbGetSeoUrl("index.php?p=pages&id={$assoc2['seo_url']}", $assoc2['seo_url'], false);
				// Increase Menu
				if ($menu_type == 'option') {
					$active = ($seo_url_rewrite == $seo_url_id) ? 'selected="" ' : '';
					$menu .= '<option ' . $active . 'id="menu_' . $assoc2['id'] . '" data-href="' . ((isset($assoc2['seo_url']) && trim($assoc2['seo_url']) != '') ? $seo_normal_url : SB_URL) . '">';
					$menu .= $sbsanitize->displayLang($sbsanitize->displayText($assoc2['menu'], 'UTF-8', 1, 0), "$menu_lang");
					$menu .= '</option>';
				} else {
					$menu .= '<li id="menu_' . $assoc2['id'] . '">';
					// --- Get active menu
					if ($assoc2['url_custom']) {
						// --- Custom URL
						$current_url    = $sbsanitize->htmlEntities(sbGetCurrentUrl());
						$stringtosearch = $assoc2['url_custom'];
						$active  = ($current_url == $stringtosearch) ? 'active' : '';
						$menu .= '<a class="' . $active . '" href="' . $assoc2['url_custom'] . '">';
					} else {
						// --- Page URL
						$active = ($seo_url_rewrite == $seo_url_id) ? 'active' : '';
						$menu .= '<a class="' . $active . '" href="' . ((isset($assoc2['seo_url']) && trim($assoc2['seo_url']) != '') ? $seo_normal_url : SB_URL) . '">';
					}
					$menu .= $sbsanitize->displayLang($sbsanitize->displayText($assoc2['menu'], 'UTF-8', 1, 0), "$menu_lang");
					$menu .= '</a>';
					$menu .= '</li>';
				}
			}
			$active = '';
		}
		if (!isset($menu_type) || $menu_type != 'option') $menu .= '</ul>';
	
		return $menu;
	}
}

/**
* Get CMS Contents ordered
* @param	string	o1	Template Smarty 1
* @param	string	o2	Template Smarty 2
* @param	string	o3	Template Smarty 3
* @param	string	o4	Template Smarty 4
* @return html display (Smarty)
*/
if (!function_exists("insert_sbGetContentCms")) {
	function insert_sbGetContentCms($param) {
		global $sbsmarty;
		
		if (isset($param['o1']) && $param['o1'] != '' && sbCheckTplExist($param['o1']))
			$sbsmarty->display($param['o1']);
	
		if (isset($param['o2']) && $param['o2'] != '' && sbCheckTplExist($param['o2']))
		$sbsmarty->display($param['o2']);
	
		if (isset($param['o3']) && $param['o3'] != '' && sbCheckTplExist($param['o3']))
			$sbsmarty->display($param['o3']);	
	
		if (isset($param['o4']) && $param['o4'] != '' && sbCheckTplExist($param['o4']))
			$sbsmarty->display($param['o4']);
	}
}

/**
* Get CMS headers from SQL
* @return html
*/
if (!function_exists("insert_sbGetHeaders")) {
	function insert_sbGetHeaders() {
		global $sbsql, $sbsanitize;
		$cms_headers  = '';
		$table_config = _AM_DB_PREFIX . 'sb_config';
	
		// --- Get CMS headers CODE (CSS / JAVASCRIPT)
		// --- Key sort by Alphabetical order => css (0), javascript (1)
		$query   = "SELECT config, content FROM $table_config
					WHERE config = 'css'
					OR config = 'javascript'
					OR config = 'seo-google-analytics'
					ORDER BY config ASC";
		$request = $sbsql->query($query);
		$result  = $sbsql->toarray($request);
		
		// --- Get CSS Code
		if ($sbsanitize->sTrim($result[0]['content']) != '') {
			$cms_headers .= '<style>';
			$cms_headers .= $result[0]['content'];
			$cms_headers .= '</style>';
		}
	
		// --- Get JAVASCRIPT Code
		if ($sbsanitize->sTrim($result[1]['content']) != '') {
			$cms_headers .= '<script type="text/javascript">';
			$cms_headers .= "document.addEventListener('DOMContentLoaded', function() {";
			$cms_headers .= $sbsanitize->displayText($result[1]['content']);
			$cms_headers .= "});";
			$cms_headers .= '</script>';
		}
		
		// --- Get Google Analytic
		if ($sbsanitize->sTrim($result[2]['content']) != '') {
			$cms_headers .= "\n" . '<!-- Google Analytics -->' . "\n";
			$cms_headers .= '<script type="text/javascript">' . "\n";
			$cms_headers .= "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
								(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
								m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
							 })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');" . "\n";
			$cms_headers .= "ga('create', 'UA-".$sbsanitize->displayText($result[2]['content'])."', 'auto');" . "\n";
			$cms_headers .= "ga('send', 'pageview');" . "\n";
			$cms_headers .= '</script>' . "\n";
			$cms_headers .= '<!-- End Google Analytics -->' . "\n";
		}
		
		return $cms_headers;
	}
}

/**
* Get CMS footer plugins
* @return html
*/
if (!function_exists("insert_sbGetPlugins")) {
	function insert_sbGetPlugins() {
		global $sbsql, $sbsanitize;
		$cms_plugins  = '';
		$table_config = _AM_DB_PREFIX . 'sb_config';
		
		// --- Get CMS Plugins CODE (PLUGINS)
		$query   = "SELECT content FROM $table_config WHERE config = 'plugins' ORDER BY config ASC";
		$request = $sbsql->query($query);
		$result  = $sbsql->assoc($request);
		
		// --- Get PLUGINS Code
		if ($sbsanitize->sTrim($result['content']) != '') {
			// --- Plugin Array
			$plugins_array = explode("|", $result['content']);
			// --------------------------
			// --- Plugin JQUERY Latest
			// --------------------------
			if (in_array('jquery', $plugins_array)) {
				$cms_plugins .= '<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>';
			}
			// --------------------------
			// --- Plugin APPEAR / DISAPPEAR
			// --------------------------
			if (in_array('appear', $plugins_array)) {
				$cms_plugins .= '<script src="'.SB_URL.'plugins/appear/jquery.appear.js"></script>';
			}
			
			// --------------------------
			// --- Plugin LIGHTBOX
			// --------------------------
			if (in_array('lightbox', $plugins_array)) {
				$cms_plugins .= '<link href="'.SB_URL.'plugins/lightbox/css/lightbox.min.css" rel="stylesheet">';
				$cms_plugins .= '<script src="'.SB_URL.'plugins/lightbox/js/lightbox.min.js"></script>';
			}
	
			// --------------------------
			// --- Plugin FANCYBOX
			// --------------------------
			if (in_array('fancybox', $plugins_array)) {
				$cms_plugins .= '<script type="text/javascript" src="'.SB_URL.'plugins/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>';
				$cms_plugins .= '<script type="text/javascript" src="'.SB_URL.'plugins/fancybox/jquery.fancybox.js"></script>';
				$cms_plugins .= '<link rel="stylesheet" type="text/css" href="'.SB_URL.'plugins/fancybox/jquery.fancybox.css" media="screen" />';
				$cms_plugins .= '<link rel="stylesheet" type="text/css" href="'.SB_URL.'plugins/fancybox/helpers/jquery.fancybox-buttons.css" />';
				$cms_plugins .= '<script type="text/javascript" src="'.SB_URL.'plugins/fancybox/helpers/jquery.fancybox-buttons.js"></script>';
				$cms_plugins .= '<link rel="stylesheet" type="text/css" href="'.SB_URL.'plugins/fancybox/helpers/jquery.fancybox-thumbs.css" />';
				$cms_plugins .= '<script type="text/javascript" src="'.SB_URL.'plugins/fancybox/helpers/jquery.fancybox-thumbs.js"></script>';
				$cms_plugins .= '<script type="text/javascript" src="'.SB_URL.'plugins/fancybox/helpers/jquery.fancybox-media.js"></script>';
				$cms_plugins .= '<script type="text/javascript">
									jQuery(document).ready(function() {
										jQuery(".fancybox").fancybox();
									});
								 </script>';
			}
	
			// --------------------------
			// --- Plugin CHECKBOXCSS
			// --------------------------
			if (in_array('checkboxcss', $plugins_array)) {
				$cms_plugins .= '<link href="'.SB_URL.'plugins/checkboxcss/checkboxcss.css" rel="stylesheet">';
			}
			
			// --------------------------
			// --- Plugin MAGNIFIC POPUP
			// --------------------------
			if (in_array('magnificpopup', $plugins_array)) {
				$cms_plugins .= '<script src="'.SB_URL.'plugins/magnific-popup/dist/jquery.magnific-popup.min.js"></script>';
				$cms_plugins .= '<script src="'.SB_URL.'plugins/magnific-popup/dist/jquery.magnific-popup-init.js"></script>';
			}
			// --------------------------
			
		}
		
		return $cms_plugins;
	}
}

/**
* Get CMS footer plugins
* @return html
*/
if (!function_exists("insert_sbGetFonts")) {
	function insert_sbGetFonts() {
		global $sbsql, $sbsanitize;
		$cms_fonts    = '';
		$table_config = _AM_DB_PREFIX . 'sb_config';
		
		// --- Get CMS Plugins CODE (PLUGINS)
		$query   = "SELECT content FROM $table_config WHERE config = 'fonts' ORDER BY config ASC";
		$request = $sbsql->query($query);
		$result  = $sbsql->assoc($request);
		
		if ($sbsanitize->sTrim($result['content'])) {
			$cms_fonts .= $sbsanitize->displayText($result['content']);
		}
		
		return $cms_fonts;
		
	}
}

/**
* Get CMS Configuration
* @param	string	id	Config ID to display
* @return html
*/
if (!function_exists("insert_sbGetConfig")) {
	function insert_sbGetConfig($param) {
		global $sbsql, $sbsanitize, $_SESSION;
		$table_config = _AM_DB_PREFIX . 'sb_config';
		$config_name  = $sbsanitize->stopXSS(trim($param['id']));
	
		// --- Get CMS Configuration
		$query   = "SELECT content FROM $table_config WHERE config = '$config_name'";
		$request = $sbsql->query($query);
		$result  = $sbsql->object($request);
		
		$config_info = $sbsanitize->displayText($sbsanitize->displayLang($result->content, $_SESSION['lang']));
		
		return sbGetShortcode($config_info);
	}
}

/**
* Get Count num row (SQL)
* @param	string	col		SQL column for SELECT
* @param	string	tbl		SQL Table
* @param	string	cond	SQL condition
* @return html
*/
if (!function_exists("insert_sbGetNumRows")) {
	function insert_sbGetNumRows($param) {
		global $sbsql, $sbsanitize;
		$colon = $sbsanitize->stopXSS(trim($param['col']));
		$table = $sbsanitize->stopXSS(trim($param['tbl']));
		$cond  = $sbsanitize->stopXSS(trim($param['cond']));
	
		// --- Get CMS Configuration
		$query   = "SELECT $colon FROM $table WHERE $cond";
		$request = $sbsql->query($query);
		$result  = $sbsql->numrows();
		
		return $result;
	}
}

/**
* Get Url in progress and return the same with another langage
* @param	string	request_uri		$_SERVER request_uri
* @param	string	query_string	$_SERVER query_string
* @param	string	script_name		$_SERVER script_name
* @param	string	server_name		$_SERVER server_name
* @param	string	session			Langage Session (fr, en, ...)
* @return url (string)
*/
if (!function_exists("insert_sbGetLangUrl")) {
	function insert_sbGetLangUrl($param) {
		$request_uri  = $param['request_uri'];
		$query_string = $param['query_string'];
		$script_name  = $param['script_name'];
		$server_name  = $param['server_name'];
		$session_lang = ($param['lang'] == 'en') ? 'fr' : 'en';
		
		// Search ? in request_uri
		$url = strstr($request_uri, '?');
		
		// Clean request uri for url complete
		$langages          = array("&lang=fr", "&lang=en", "?lang=fr", "?lang=en");
		$clean_request_uri = str_replace($langages, "", $request_uri);
	
		// Choose operand for url complete	
		$operand = ($query_string != '' && $query_string != "lang=fr" && $query_string != "lang=en") ? '&' : '?';
		
		// Construct new url complete
		$url_complete = SB_DEFAULT_PROTOCOL . $server_name . $clean_request_uri . $operand . 'lang=' . $session_lang;
		
		return $url_complete;
	}
}

/**
* Get all the blocks associated with a page
* @param	integer	page_id		Page ID
* @return url (string)
*/
if (!function_exists("insert_sbGetContentCmsBlocks")) {
	function insert_sbGetContentCmsBlocks($param) {
		global $sbsql, $sbsmarty, $sbsanitize;
		
		// --- Initialization
		$table_blocs   = _AM_DB_PREFIX . 'sb_blocs';
		$table_sort    = _AM_DB_PREFIX . 'sb_blocs_sort';
		$page_id       = (!isset($param['mod'])) ? intval($param['pid']) : $param['pid'];
		$type_t1       = (!isset($param['mod'])) ? 't1.page_id' : 't1.module_id';
		$type_t1_query = (!isset($param['mod'])) ? "$type_t1 = $page_id" : "$type_t1 = '$page_id'";
		
		// --- Get ALL blocks for this page_id
		$query   = "SELECT t1.bloc_id, $type_t1, t1.sort,
						   t2.id, t2.name, t2.title, t2.content, t2.various_view
						   FROM $table_sort AS t1
						   LEFT JOIN $table_blocs AS t2 ON (t1.bloc_id = t2.id)
						   WHERE t2.active = '1' AND $type_t1_query
						   ORDER BY t1.sort ASC ";	
		$request = $sbsql->query($query);
		return $sbsql->toarray($request);
	}
}

/**
* Get Current Url
* @param	bool	$full	Full url OR without get
* @return string
*/
if (!function_exists("insert_sbGetCurrentUrl")) {
	function insert_sbGetCurrentUrl() {
		return (function_exists("sbGetCurrentUrl")) ? sbGetCurrentUrl() : false;
	}
}

/**
* Get Current Url Pagination
* @param	bool	$full	Full url OR without get
* @return string
*/
if (!function_exists("insert_sbGetCurrentUrlPagination")) {
	function insert_sbGetCurrentUrlPagination() {
		$current_url = sbGetCurrentUrl();
		$position    = strpos($current_url, '?');
		$search_l    = strpos($current_url, 'l=');
		
		if ($position) {
			// ? in url
			if ($search_l)
				return substr($current_url, 0, -3);
			else
				return $current_url . '&';
		} else {
			// No ? in url
			return rtrim($current_url, '&');
		}
	}
}

/**
* Get file is existing
* @param	string	path
* @return bool
*/
if (!function_exists("insert_sbGetFile")) {
	function insert_sbGetFile($param) {
		$path      = $param['path'];
		$filename  = $path . $param['filename'];
	
		if (is_file($filename)) {
			return $filename;
		} else {
			return false;
		}
	}
}

/**
 * Get one Shortcode for page (smarty code)
 * @param	string	$string
 * @return html content
 */
if (!function_exists("insert_sbDoShortcode")) {
	function insert_sbDoShortcode($shortcode) {
		return (function_exists("sbGetShortcode")) ? sbGetShortcode($shortcode['code']) : false;
	}
}

/**
 * Get Infos to construct body class
 * @param	string	$string
 * @return string (Body Classes)
 */
if (!function_exists("insert_sbGetBodyClass")) {
	function insert_sbGetBodyClass($param) {
		// --- Initialization
		$body_classes = '';
	
		// --- Page ID
		$page_id = strtolower($param['pid']);
		if ($page_id != '') $body_classes .= ' ' . $page_id;	
		// --- Theme view
		$theme_view = strtolower($param['th']);
		if ($theme_view != '') $body_classes .= ' ' . $theme_view;
		// --- Page title
		$pages_title = rtrim(strtolower(sbRewriteString($param['pt'])), "-");
		if ($pages_title != '' && $page_id != $pages_title) $body_classes .= ' ' . $pages_title;
		// --- Title
		$title = rtrim(strtolower(sbRewriteString($param['ti'])), "-");
		if ($title != '') $body_classes .= ' ' . $title;
		// --- Module Name
		$module_name = (defined("MODULENAME")) ? constant("MODULENAME") : false; // A revoir 
		if ($module_name) $body_classes .= ' ' . $module_name;
		// --- Page Mod Name
		$page_modname = (defined("MODNAME")) ? strtolower(constant("MODNAME")) : false;
		if ($page_modname) $body_classes .= ' ' . $page_modname;
	
		return strtolower(trim($body_classes)); 
	}
}

/**
 * Get Infos to construct page section classes & ID
 * @param	string	$string
 * @return string (Section Classes ID)
 */
if (!function_exists("insert_sbGetSectionClassId")) {
	function insert_sbGetSectionClassId($param) {
		// --- Initialization
		$op     = $param['op'];
		$page   = $param['page'];
		$id     = $param['id'];
		$ti     = $param['ti'];
		$class  = $param['class'];
		$evenid = $param['evenid'];
		$classes_id = '';
	
		// Construct ID
		if ($evenid != '') {
			$classes_id = 'id="' . $evenid . '"';		
		} else {
			if ($ti != '') $classes_id = 'id="page-' . sbRewriteString(strtolower($ti)) . '"';
			elseif ($id != '') $classes_id = 'id="page-' . sbRewriteString(strtolower($id)) . '"';
			elseif ($page != '') $classes_id = 'id="page-' . sbRewriteString(strtolower($page)) . '"';
		}
		
		// --- Init Class
		$classes_id .= ' class="' . $class;
		
		// Construct classes
		if ($op != '') $classes_id .= ' ' . sbRewriteString(strtolower($op));
		if ($page != '') $classes_id .= ' page-' . sbRewriteString(strtolower($page));
		if ($id != '') $classes_id .= ' ' . sbRewriteString(strtolower($id));
		if ($ti != '') $classes_id .= ' ' . sbRewriteString(strtolower($ti));
		// --- Action
		$classes_id .= '"';
		
		return $classes_id;
	
	}
}

/**
 * Get Infos to construct head title
 * @param	string	$string
 * @return string (Head Title)
 */
if (!function_exists("insert_sbGetPageTitle")) {
	function insert_sbGetPageTitle($param) {
		global $sbsanitize;
		
		$page_title = $param['pti'];
		$mod_title  = $param['mti'];
		
		$home_array = ['accueil','home','homepage'];
		
		if (in_array(strtolower($sbsanitize->sTrim($page_title)), $home_array)) {
			// HomePage
			// Clean Title
			return '';
		} else {
			// --- Check if module title
			if ($sbsanitize->sTrim($mod_title)) {
				return $mod_title;
			} else {
				return $page_title;
			}
		}
	}
}

/**
 * Get Infos Mobile Detect
 * @return string (type mobile devices)
 */
if (!function_exists("insert_sbGetMobileDetect")) {
	function insert_sbGetMobileDetect($param) {
		global $sbsanitize;
	
		include_once('plugins/mobile-detect/Mobile_Detect.php');
		
		if (!class_exists('Mobile_Detect')) {
			return 'classic';
		} else {
			$sbmobiledetect = new Mobile_Detect;
			$sb_isMobile    = $sbmobiledetect->isMobile();
			$sb_isTablet    = $sbmobiledetect->isTablet();
			$sb_classes_md  = '';
			
			// Layout Type
			$sb_classes_md .= ($sb_isMobile ? ($sb_isTablet ? 'tablet' : 'mobile') : 'computer');
			
			// Custom detection methods
			$sb_custom_detection = '';
			foreach($sbmobiledetect->getRules() as $name => $regex) {
				$sb_check_custom = $sbmobiledetect->{'is'.$name}();
				if ($sb_check_custom)
					$sb_classes_md .= ' ' . $name;
			}
	
			return strtolower($sb_classes_md);
		}
	}
}

/**
 * Get SEO All Metas From Administration
 * @return html
 */
if (!function_exists("insert_sbGetSeoMetas")) {
	function insert_sbGetSeoMetas() {
		global $sbsql, $sbsanitize;
		$metas_seo = '';
		$table     = _AM_DB_PREFIX . 'sb_config';
		
		// --- Get CMS SEO Metas
		$query   = "SELECT config, content FROM $table WHERE config LIKE 'seo-%'";
		$request = $sbsql->query($query);
		$result  = $sbsql->toarray($request);
		
		if ($result) {
			// Loop all Metas
			foreach($result as $row) {
				// Initialize
				$cs[$row['config']] = sb_utf8_encode($row['content']);
				// Instantiate
				if (!empty($cs[$row['config']])) {
					switch($row['config']) {
						case "seo-keywords":
							$metas_seo .= "\t\t" . '<meta name="keywords" content="' . $cs[$row['config']] . '">' . "\n";
						break;
						case "seo-description":
							$metas_seo .= "\t\t" . '<meta name="description" content="' . $cs[$row['config']] . '">' . "\n";
						break;
						case "seo-rating":
							$metas_seo .= "\t\t" . '<meta name="rating" content="' . $cs[$row['config']] . '">' . "\n";
						break;
						case "seo-robots":
							$metas_seo .= "\t\t" . '<meta name="robots" content="' . $cs[$row['config']] . '">' . "\n";
						break;
						case "seo-copyright":
							$metas_seo .= "\t\t" . '<meta name="copyright" content="' . $cs[$row['config']] . '">' . "\n";
						break;
						case "seo-author":
							$metas_seo .= "\t\t" . '<meta name="author" content="' . $cs[$row['config']] . '">' . "\n";
						break;
						case "seo-generator":
							$metas_seo .= "\t\t" . '<meta name="generator" content="' . $cs[$row['config']] . '">' . "\n";
						break;
						case "seo-google-analytics":
							$metas_seo .= "\t\t" . '<meta name="google-site-verification" content="' . $cs[$row['config']] . '">' . "\n";
						break;
					}
				}
			}
		}
		
		return $metas_seo;
	}
}

/**
 * Get Theme Options
 * @param string Option name
 * @return String
 */
if (!function_exists("insert_sbGetThemeOption")) {
	function insert_sbGetThemeOption($param) {
		global $theme;
		# Initialize
		$option_name = $param['option'];
		$option_num  = (isset($param['n'])) ? $param['n'] : '';
		# Get option string
		if (!empty($option_num))
			$option_string = $theme[$option_name][$option_num];
		else
			$option_string = $theme[$option_name];
		return sbGetShortcode($option_string);
	}
}

/**
 * Get Real User IP
 * @return string
 */
if (!function_exists("insert_sbGetUserIP")) {
	function insert_sbGetUserIP() {
		return (function_exists("sbGetUserIP")) ? sbGetUserIP() : false;
	}
}

/**
 * Blocked IP
 * @return void
 */
if (!function_exists("insert_sbIsFlood")) {
	function insert_sbIsFlood() {
		return (function_exists("sbIsFlood")) ? sbIsFlood() : false;
	}
}

/**
 * Check if IP is blocked
 * @return string/void		expiration time OR false
 */
if (!function_exists("insert_sbIsBlockedIP")) {
	function insert_sbIsBlockedIP() {
		return (function_exists("sbIsBlockedIP")) ? sbIsBlockedIP() : false;
	}
}

/**
 * Check if IP is blocked
 * @return string/void		expiration time OR false
 */
if (!function_exists("insert_sbGetInfoBlockedIP")) {
	function insert_sbGetInfoBlockedIP() {
		return (function_exists("sbGetInfoBlockedIP")) ? sbGetInfoBlockedIP() : false;
	}
}

?>
