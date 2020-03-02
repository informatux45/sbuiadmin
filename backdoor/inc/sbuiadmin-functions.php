<?php
/**
 * Admin Startbootstrap
 * Al PHP Functions
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

/* -------------------------------
 * Available functions :
 * -------------------------------
 * sb_get_include_contents
 * sb_global_include
 * sb_get_server_url
 * sbGetEmailValid
 * get2DArrayFromCsv
 * sbDisplayMediaSize
 * sbDisplayMediaMime
 * sbDisplayFileExtension
 * sbRewriteTags
 * sbRewriteToId
 * sbFilename
 * sbFileRealname
 * sbIniGet
 * sbToByteSize
 * sbGetInfoImg
 * sbGetIfIsImg
 * sbShowTypeMimeImageInv
 * sbShowTypeMimeVideoInv
 * sbModifiedFileTime
 * sbGetFileList
 * sbConvertDate
 * sbGetLastPartOfUrl
 * sbDisplayLang
 * sbTranslate
 * sbPriceCalculation
 * sbAreaCalculation
 * sbTruncate
 * sbGetConfig
 * sbGetModulesPage
 * sbGetThemesFront
 * sbGetGravatar
 * sbGetMenuModule
 * sbArrayOrderby
 * sbGetFileDocData
 * sbEncryptStringWithSalt
 * sbGenerateRandKey
 * -------------------------------
 * Available Smarty functions :
 * -------------------------------
 * insert_sbFileOtherImg
 * insert_sbGetBrowser
 * ---------------------------- */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');


function sb_get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include_once $filename;
        return ob_get_clean();
    }
    return false;
}

function sb_global_include($script_path) {
    // check if the file to include exists:
    if (isset($script_path) && is_file($script_path)) {
        // extract variables from the global scope:
        extract($GLOBALS, EXTR_REFS);
        ob_start();
        include($script_path);
        return ob_get_clean();
    } else {
        ob_clean();
        trigger_error('The script "'.$script_path.'" to parse in the global scope was not found !');
    }
}

function sb_get_server_url() {
   $server_url = '';
   if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
	  // explode the host list separated by comma and use the first host
	  $hosts = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
	  $server_url = $hosts[0];
   } else if (!empty($_SERVER['HTTP_X_FORWARDED_SERVER'])) {
	  $server_url = $_SERVER['HTTP_X_FORWARDED_SERVER'];
   } else {
	  if (empty($_SERVER['SERVER_NAME'])) {
	  $server_url = $_SERVER['HTTP_HOST'];
	  } else {
	  $server_url = $_SERVER['SERVER_NAME'];
	  }
   }
   if (!strpos($server_url, ':')) {
	  if ( ($this->_isHttps() && $_SERVER['SERVER_PORT']!=443) || (!$this->_isHttps() && $_SERVER['SERVER_PORT']!=80)
	  ) {
		 $server_url .= ':';
		 $server_url .= $_SERVER['SERVER_PORT'];
	  }
   }
   return $server_url;
}

function sbGetEmailValid($email) {
   $atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
   $domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)
   
   $regex = '/^' . $atom . '+' .   // Une ou plusieurs fois les caractères autorisés avant l'arobase
   '(\.' . $atom . '+)*' .         // Suivis par zéro point ou plus
				   // séparés par des caractères autorisés avant l'arobase
   '@' .                           // Suivis d'un arobase
   '(' . $domain . '{1,63}\.)+' .  // Suivis par 1 à 63 caractères autorisés pour le nom de domaine
				   // séparés par des points
   $domain . '{2,63}$/i';          // Suivi de 2 à 63 caractères autorisés pour le nom de domaine
   
   // test de l'adresse e-mail
   if (preg_match($regex, $email)) {
	   return 1; // Valide
   } else {
	   return 0; // Non valide
   }
}

function get2DArrayFromCsv($file,$delimiter) {
   if (!file_exists($file))
	  return FALSE;

   if (($handle = fopen($file, "r")) !== FALSE) {
	   $i = 0;
	   while (($lineArray = fgetcsv($handle, 4000, $delimiter)) !== FALSE) {
		   for ($j=0; $j<count($lineArray); $j++) {
			   $data2DArray[$i][$j] = $lineArray[$j];
		   }
		   $i++;
	   }
	   fclose($handle);
   }
   return $data2DArray;
}

function sbDisplayMediaSize($file) {
   if (!is_file($file)) {
	  return 'O byte';
   } else {
	  $size = filesize($file);
	  if ($size >= 1073741824) $size = round($size / 1073741824 * 100) / 100 . " Go";
	  elseif ($size >= 1048576) $size = round($size / 1048576 * 100) / 100 . " Mo";
	  elseif ($size >= 1024) $size = round($size / 1024 * 100) / 100 . " Ko";
	  else $size = $size . " bytes";
	  return $size;
   }
}

function sbDisplayMediaMime($file) {
   $fileinfo = finfo_open(FILEINFO_MIME_TYPE); // Retourne le type mime à l'extension mimetype
   $filemime = finfo_file($fileinfo, $file) . "\n";
   finfo_close($fileinfo);
   
   return $filemime;
}

function sbDisplayFileExtension($filename) {
	$extension = explode('.', $filename);
	$extension = array_reverse($extension);
	$extension = $extension[0];

	return strtolower($extension);
}

function sbRewriteTags($string) {
	$noValidString     = trim($string);
	$noValidCharacters = array("$","+","/","=","[","]","&","~","`","'",",","%",'"',"-","_","£","<",">",":",".","´","*","#","{","}","(",")","|","@",";","!");
	$validCharacters   = array(" "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," "," ");
	$validString       = str_replace($noValidCharacters, $validCharacters, $noValidString);

	return ($validString);
}

function sbRewriteToId($string) {
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

function sbFilename($filename) {
   $file_info = pathinfo($filename);
   $file_name = basename($filename, '.' . $file_info['extension']);
   
   return $file_name;
}

function sbFileRealname($filename) {
   $file_name = basename($filename);
   
   return $file_name;
}

function sbIniGet($iniget_varname) {
   return ini_get($iniget_varname);
}

function sbToByteSize($p_sFormatted) {
    $aUnits = array('B'=>0, 'KB'=>1, 'MB'=>2, 'GB'=>3, 'TB'=>4, 'PB'=>5, 'EB'=>6, 'ZB'=>7, 'YB'=>8);
    $sUnit = strtoupper(trim(substr($p_sFormatted, -2)));
    if (intval($sUnit) !== 0) {
        $sUnit = 'B';
    }
    if (!in_array($sUnit, array_keys($aUnits))) {
        return false;
    }
    $iUnits = trim(substr($p_sFormatted, 0, strlen($p_sFormatted) - 2));
    if (!intval($iUnits) == $iUnits) {
        return false;
    }
    return $iUnits * pow(1024, $aUnits[$sUnit]);
}

function sbGetInfoImg($image_path, $info = 'width') {
   // Get infos image
   $file_image = array_values(getimagesize($image_path));
   //use list on new array
   list($width, $height, $type, $attr) = $file_image;
   
   switch($info) {
	  default:       return $width;  break;
	  case "height": return $height; break;
	  case "type"  : return $type;   break;
	  case "attr"  : return $attr;   break;
   }
}

function sbGetIfIsImg($image_path) {
   // Get if infos image
   $file = array_values(getimagesize($image_path));
   //use list on new array
   list($width, $height, $type, $attr) = $file;
   
   if ($width > 0)
	  return true; // Is an image
   else
	  return false; // Is not an image
}

function sbShowTypeMimeImageInv($type) {
	switch (strtolower($type)) {
		// ------------------
		// Type image
		case 'image/jpeg':						return 'jpeg';
		case 'image/jpeg':						return 'jpg';
		case 'image/jpeg':						return 'jpe';
		case 'image/png':						return 'png';
		case 'image/gif':						return 'gif';
		case 'image/tiff':						return 'tif';
		case 'image/tiff':						return 'tiff';
		case 'application/x-shockwave-flash':	return 'swf';
		case 'image/psd':						return 'psd';
		case 'image/bmp':						return 'bmp';
		case 'image/jp2':						return 'jp2';
		case 'image/iff':						return 'iff';
		case 'image/vnd.wap.wbmp':				return 'wbmp';
		case 'image/xbm':						return 'xbm';
		case 'image/vnd.microsoft.icon':		return 'ico';

		default: return false;
	}
}

function sbShowTypeMimeVideoInv($type) {
	switch (strtolower($type)) {
		// ------------------
		// Type video
		case 'video/mpeg':		return 'mpg';
		case 'video/mp4':		return 'mp4';
		case 'video/quicktime':	return 'mov';
		case 'video/x-ms-wmv':	return 'wmv';
		case 'video/x-msvideo':	return 'avi';
		case 'video/x-flv':		return 'flv';

		default: return false;
	}
}

function sbModifiedFileTime($filename, $date = "en") {
   // Test if the file exist
   if (file_exists($filename)) {
	  if ($date == "fr") return date ("d-m-Y", filemtime($filename));
	  else return date ("Y-m-d", filemtime($filename));
   } else {
	  return false;
   }
}

/**
* Get physical file listing
*/
define("BY_EXTENSION", 1);
define("BY_EXPRESSION", 2);

function sbGetFileList($HowToSearch, $Condition, $Directory, $AddPath) {
	//-------------------------------------------------------------
	// Here are some examples of usage of the GetFileList function.
	// The function expects four values:
	//
	// 1. $HowToSearch - Specifies the search method. There are
	//    two options: BY_EXTENSION, or BY_EXPRESSION.
	// 2. $Condition - This specifies the search condition. If you
	//    are using the BY_EXTENSION method, simply supply the
	//    extension in quotes (e.g. "gif"). If you are using the
	//    BY_EXPRESSION method, supply a valid PCRE expression.
	//    (e.g. '/gif{1}$/').
	// 3. $Directory - The directory to search in (e.g. "images")
	// 4. $AddPath - true or false. Prefixes the filenames returned
	//    in the array with the directory you specified.
	//-------------------------------------------------------------

	//--------------------------------------------------------------------
	// Get a list of JPGs from the IMAGES directory. Prefix with the path.
	//--------------------------------------------------------------------
	//$List1 = GetFileList(BY_EXTENSION, "jpg", "images", true);

	//----------------------------------------
	// Get a list of files that start with sm_
	//----------------------------------------
	//$List2 = GetFileList(BY_EXPRESSION, '/^sm_/i', "images", false);

	//------------------------------------------------
	// Search the current directory for any PHP files.
	//------------------------------------------------
	//$List3 = GetFileList(BY_EXTENSION, "php", ".", false);
	
	$hDir = opendir($Directory);
	if (!$hDir) return false;

	$result = array();
	$index = 0;

	//---------------------------------
	// Add trailing slash to directory.
	//---------------------------------
	if (!preg_match("/\/${1}/", $Directory)) $Directory .= "/";

	//--------------------------------------------
	// Loop while we still have directory entries.
	//--------------------------------------------
	while ($dirEntry = readdir($hDir)) {
		$new_entry = "";
		$add = false;

		//--------------------------------
		// Add entries based on extension.
		//--------------------------------
		if ($HowToSearch == BY_EXTENSION)
		if (preg_match("/$Condition${1}/", $dirEntry)) $add = true;
		
		//---------------------------------------------------------
		// Add entries based on Perl-compatible regular-expression.
		//---------------------------------------------------------
		if ($HowToSearch == BY_EXPRESSION)
		if (preg_match($Condition, $dirEntry)) $add = true;

		//-------------------------------
		// Add the entry if it qualifies.
		//-------------------------------
		if ($add) {
		if ($AddPath == true) $new_entry = $Directory;

		$new_entry .= $dirEntry;
		$result[$index++] = $new_entry;
		}
	}

	closedir($hDir);
	return $result;
}

function sbConvertDate($date, $format = 'ISO') {
	switch(strtoupper($format)) {
		// Format ISO (AAAA-MM-DD)
		default: return strftime("%F", strtotime($date)); break;
		// Format US (MM-DD-AAAA)
		case "US": return strftime("%m/%d/%Y", strtotime($date)); break;
		// Format US (MM-DD-AAAA HH:mm:ss)
		case "UST": return strftime("%m/%d/%Y %T", strtotime($date)); break;
		// Format FR (DD-MM-AAAA)
		case "FR": return strftime("%d/%m/%Y", strtotime($date)); break;
		// Format FR (DD-MM-AAAA)
		case "FR2": return strftime("%d-%m-%Y", strtotime($date)); break;
		// Format FR (DD-MM-AAAA à HH:mm:ss)
		case "FR3": return strftime("%d-%m-%Y à %T", strtotime($date)); break;
		// Format FR (DD-MM-AAAA HH:mm:ss) with time
		case "FRT": return strftime("%d-%m-%Y %T", strtotime($date)); break;
		// Format FRH (DD MM AAAA) Human readable
		case "FRH": return strftime("%e %B %Y", strtotime($date)); break;
		// Year (AAAA)
		case "YEAR": return strftime("%Y", strtotime($date)); break;
	}
}

function sbGetLastPartOfUrl($url) {
	return basename(parse_url($url, PHP_URL_PATH));
}

function sbDisplayLang($string, $lang = "fr") {
	// Show the language session (fr OR en OR ...)
	if (preg_match('#\[' . $lang . '\](.*)\[/' . $lang . ']#Us', $string, $match)) {
		$text = $match[1];
	} else {
		$text = $string;
	}

	return $text;
}

function sbTranslate($lang, $text) {
		preg_match('#\[' . $lang . '\](.*)\[/' . $lang . ']#Us', $text, $match);
		return $match[1];
}

function sbPriceCalculation($price, $lang = 'fr', $money = '€') {
	return ($lang == 'fr') ? number_format($price, 0, '', ' ') . ' ' . $money : $money . ' ' . number_format($price, 0, '', ' ');
}

function sbAreaCalculation($area) {
	if ($area < 10000) return number_format($area, 0, '', ' ') . ' ' . 'm2';
	if ($area >= 10000) {
		$number = $area / 10000;
		if (is_int($number))
			return number_format($number, 0, '', ' ') . ' ' . 'Ha';
		else
			return number_format($number, 1, ',', ' ') . ' ' . 'Ha';
	}
}

function sbTruncate($string, $max = 20, $replacement = '') {
	if (strlen($string) <= $max) {
		return $string;
	}
	$leave = $max - strlen ($replacement);
	return substr_replace($string, $replacement, $leave);
}

/**
* Get CMS Configuration
* @param	string	config	Config name to return
* @return html
*/
function sbGetConfig($config, $langdefault = 'fr') {
	global $sbsanitize, $sbsql;
	$table_config = _AM_DB_PREFIX . 'sb_config';
	$config_name  = $sbsanitize->stopXSS(trim($config));

	// --- Get CMS Configuration
	$query   = "SELECT content FROM $table_config WHERE config = '$config_name'";
	$request = $sbsql->query($query);
	$result  = $sbsql->object($request);
	
	$lang    = ($_SESSION['lang'] != '') ? $_SESSION['lang'] : $langdefault;
	
	return ($sbsanitize->displayText($sbsanitize->displayLang($result->content, $lang)));
}

/**
 * Get List Module useable for the module PAGES
 * @return array module list
 */
function sbGetModulesPage() {
	$dir = SB_MODULES_DIR;
	$result_modules_dir = array();	 
	$modules_dir = scandir($dir);
	foreach ($modules_dir as $key => $value) {
	   if (!in_array($value, array(".","..","pages","slider","tabbs","table"))) {
		  if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
			 $result_modules_dir[] = $value;
		  }
	   }
	}
	return $result_modules_dir;
}

/**
 * Get List Various HTML Content File useable for the module PAGES
 * @return array various files list
 */
function sbGetVariousPage($type) {
	$dir                = SB_VARIOUS_DIR;
	$search_type        = "$type-";
	$result_various_dir = array();	 
	$various_dir        = scandir($dir);
	foreach ($various_dir as $key => $value) {
	   if (!in_array($value, array(".",".."))) {
		  if (!is_dir($dir . DIRECTORY_SEPARATOR . $value) && strpos($value, $search_type) !== false) {
			 $result_various_dir[] = $value;
		  }
	   }
	}
	return $result_various_dir;
}

/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source https://gravatar.com/site/implement/images/php/
 */
function sbGetGravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

/**
 * Construct Module Menu (Out of System)
 * @param string $type main / admin
 * @return String menu Admin
 */
function sbGetMenuModule($param = '') {
	global $module_menu, $sbsanitize, $sbsql, $sbusers, $sbuiadmin_user_type;
	
	// --- Initialization
	$ret_module_menu = '';
	switch($param) {
		default:
		case "admin":
			// --- Assign Path Modules
			$path_modules = _AM_SITE_DIR;
			// --- Get Module menu Infos
			include_once(_AM_SITE_DIR . 'main.php');
			// --- Get file list (array)
			$modules_dir = sbGetFileList(BY_EXTENSION, "php", $path_modules, true);
			// --- 	Specific sort
			$modules_order = [_AM_SITE_DIR.'settings.php'
							 ,_AM_SITE_DIR.'logaccess.php'
							 ,_AM_SITE_DIR.'database.php'
							 ,_AM_SITE_DIR.'explorer.php'
							 ,_AM_SITE_DIR.'cmsconfig.php'
							 ,_AM_SITE_DIR.'users.php'
							 ,_AM_SITE_DIR.'medias.php'
							 ,_AM_SITE_DIR.'menu.php'
							 ,_AM_SITE_DIR.'blocs.php'
							];
			$modules_dir = array_intersect($modules_order, $modules_dir);
		break;
		case "main":
			// --- Assign Path Modules
			$path_modules = _AM_SITE_DIR . "datas/modules/";
			// --- Get Module menu Infos
			include_once($path_modules . 'common.php');
			// --- Get file list (array)
			$modules_dir = sbGetFileList(BY_EXTENSION, "php", $path_modules, true);
		break;
	}

	if (!empty($modules_dir)) {
		// --- Get User Authorizations
		$id         = $sbusers->getUserInfo($_SESSION['sbuiadmin_user_name'], 'id');
		$table      = _AM_DB_PREFIX . "sb_users";
		$query      = "SELECT menu FROM $table WHERE id = $id";
		$request    = $sbsql->query($query);
		$user_auth  = $sbsql->assoc($request);
		$auth_array = explode("|", $user_auth['menu']);
		
		for($i = 0; $i < count($modules_dir); $i++) {
			// --- Get Module name
			$module_name = pathinfo($modules_dir[$i], PATHINFO_FILENAME);
			
			// --- Check if Module infos exists
			if ($module_menu[$module_name]['main']) {
				
				// --- Check if user is authorized to view menu (Global)
				if (($module_menu[$module_name]['group'] == 'admin' && $sbuiadmin_user_type == 'admin')
					|| ($module_menu[$module_name]['group'] == 'user' && $sbuiadmin_user_type == 'admin')
					|| ($module_menu[$module_name]['group'] == 'user' && $sbuiadmin_user_type == 'user')) {
					
					// --- Check if user is authorized to view menu (Personnalized)
					if (!in_array($module_name, $auth_array)) {			
						// Init Current URL
						$request_url = 'http' . (($_SERVER['HTTPS'] == 'on') ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
						
						// Init menu
						$ret_module_menu .= '<li id="' . $module_name . '">';
						
						// Menu UL (Entries)
						$ul_module_menu = @count($module_menu[$module_name]['li']);
						
						// Check if active menu
						$class_active = ($request_url == _AM_SITE_URL . "index.php?p=" . $module_name && $ul_module_menu == 0) ? ' class="active" ' : ' ';
						
						// Main menu
						$ret_module_menu .= '<a'.$class_active.'href="index.php?p=' . $module_name . '">';
						$ret_module_menu .= '<i class="fa fa-' . $module_menu[$module_name]['icon'].' fa-fw fa-menu-i"></i> '. $module_menu[$module_name]['main'];
						if ($ul_module_menu) $ret_module_menu .= '<span class="fa arrow"></span>';
						$ret_module_menu .= '</a>';
						
						// --- Check if there is menu entries
						if ($ul_module_menu > 0) {
							// Menu UL (if entries)
							$collapse_in = ($_GET['p'] == $module_name) ? ' collapse in' : '';
							$ret_module_menu .= '<ul class="nav nav-second-level' . $collapse_in . '">';
							
							// Menu LI (choices)
							for($j = 0; $j < $ul_module_menu; $j++) {
								$class_active = ($request_url == _AM_SITE_URL . $module_menu[$module_name]['li'][$j]['link']) ? ' class="active" ' : ' ';
								$ret_module_menu .= '<li id="ss-' . strtolower(sbRewriteToId($module_menu[$module_name]['li'][$j]['title'])) . '">';
								$ret_module_menu .= '<a'.$class_active.'href="'.$module_menu[$module_name]['li'][$j]['link'].'">'.$module_menu[$module_name]['li'][$j]['title'].'</a>';
								$ret_module_menu .= '</li>';
							}
							
							// End of UL
							$ret_module_menu .= '</ul>';
							
							// End of menu
							$ret_module_menu .= '</li>';
						}
					}
				}	
			}
		}
	}

	return $ret_module_menu;	
}

/**
 * Construct Theme Menu (Out of System)
 * @return Array menu Admin
 */
function sbGetThemesFront($themes_path = '') {
	// --- Get file list (array)
	return array_values(array_diff(scandir($themes_path), array('..', '.')));
}

/**
 * Get User group
 * @return string (group)
 */
function sbGetUserGroup($username) {
	global $sbadministrators;
	
	$sbuiadmin_usergroup = (in_array(trim($username), $sbadministrators)) ? 'admin' : 'user';
	
	return $sbuiadmin_usergroup;
}

/**
 * Get Array Multidimensional ordered by column
 * @param array $array (ex: $sbfiles)
 * @param columns array (ex: 'time')
 * @param sort (SORT_DESC, SORT_ASC)
 * Example sbArrayOrderby($sbfiles_new, 'time', SORT_DESC, 'file', SORT_ASC)
 * @return Array Ordered
 */
function sbArrayOrderby() {
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

/**
 * Get Searchword doc comment to found
 * Find values that match your search criteria
 * @return string
 */
function sbGetFileDocData($file, $searchword) {
    $docComments = array_filter(
        token_get_all( file_get_contents( $file ) ), function($entry) {
            return $entry[0] == T_DOC_COMMENT;
        }
    );
    $fileDocComment = array_shift( $docComments );
	
	// Create array for each line of the results
	$tags = explode("\n", $fileDocComment[1]);

	// Initialize tag string to return
	$tag_value = "";
	
	// Check if array is empty
	if (array_keys( $tags, true )) {			
		// Find keys / values that match your search criteria
		foreach($tags as $key => $value) {
			if ( preg_match("/\b$searchword\b/i", $value) ) {
				$tag_key   = $key;
				$tag_value = $value;
			}
		}
	}
	
	// Check if result
	if ( $tag_value != "" ) {
		list($tag_phpdoc, $tag_detail) = explode(":", $tag_value, 2);
		return trim($tag_detail);
	} else {
		return "N.C.";	
	}
}

/**
 * Get An Encrypt String With Salt
 * string	string		$param $string
 * hash		mode		$param hash (md5 Or hash)
 * salt		string		$param $salt (salt key)
 * return string
 */
function sbEncryptStringWithSalt($string, $hash = 'md5', $salt = '') {
    if ($hash == 'md5') {
		$pass_hash = md5("{$string}{$salt}");
    } else  {
		$pass_hash = hash("$hash", "{$string}{$salt}");
    }
    return $pass_hash;
}

/**
 * Get A Random key
 * length	int		$param $length (key length to generated)
 * return string
 */
function sbGenerateRandKey($length = 64) {
    $salt = '';
    $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $microtime = function_exists('microtime') ? microtime() : time();
    srand((double)$microtime * 1000000);
    for($i=0; $i<=$length; $i++)
	$salt.= substr($base, rand() % strlen($base), 1);
    return $salt;
}

// -------------------------------------------------------------------
// -------------------------------------------------------------------
// -------------------------------------------------------------------
//                   INSERT FUNCTIONS FOR SMARTY
// -------------------------------------------------------------------
// -------------------------------------------------------------------
// -------------------------------------------------------------------
/**
 * Get icon info by file extension
 * @param string $file Filename
 * @return String Icon
 */
function insert_sbFileOtherImg($file) {
	$type = sbDisplayFileExtension($file['f']);
	switch($type) {
		case "pdf": return "file-pdf";
		case "xml": return "file-code";
		case "mpg": case "mp4": case "mov": case "wmv": case "avi": case "flv": return "file-video";
		
		default: return "file";
	}
}

/**
 * Get Browser
 * @param string Browser name
 * @return String
 */
function insert_sbGetBrowser($param) {
	$user_agent = $param['ua'];
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'IE';
   
    return 'Other';
}

?>
