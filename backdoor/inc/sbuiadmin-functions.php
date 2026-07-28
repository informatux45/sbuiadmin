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
 * sb_utf8_encode
 * sb_utf8_decode
 * sb_get_include_contents
 * sb_global_include
 * sb_get_server_url
 * sbGetEmailValid
 * get2DArrayFromCsv
 * sbDisplayMediaSize
 * sbDisplayMediaMime
 * sbDisplayFileExtension
 * sbGetTagifyDatas
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
 * insert_sbExplodeJson
 * ---------------------------- */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');

/** UTF8_ENCODE / UTF8_DECODE Deprecated for PHP8.2
============================================== */
if (!function_exists("sb_utf8_encode")) {
   function sb_utf8_encode($string, $from_encoding = 'ISO-8859-1', $to_encoding = 'UTF-8') {
      // mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
      return iconv($from_encoding, $to_encoding, $string);
   }
}

if (!function_exists("sb_utf8_decode")) {
   function sb_utf8_decode($string, $from_encoding = 'UTF-8', $to_encoding = 'ISO-8859-1') {
      // mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
      return iconv($from_encoding, $to_encoding, $string);
   }
}
/* =========================================== */

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

if (!function_exists("sbGetTagifyDatas")) {
   function sbGetTagifyDatas($datas) {
      global $sbsmarty, $sbsanitize;
      if (empty(trim($datas))) {
         $return = false;
      } else {
         $returns = json_decode(trim($datas));
         if (_AM_SITE_DEBUG) $sbsmarty->append('sbdebugsql', $returns);
         if (array_keys( $returns, true )) {
            $return = "";
            foreach($returns as $row) {
               $return .= $sbsanitize->displayText($row->value, 'UTF-8', 1, 0) . ",";
            }
            rtrim($return, ",");
         } else {
            $return = false;
         }
      }
      return $return;
   }
}

if (!function_exists("sbGetPageBuilderModulesList")) {
   // Référentiel unique des couples "module.champ" éligibles au Page
   // Builder (Point 15, phase 2) - un seul champ par module, celui qui
   // porte le contenu principal (pas les champs secondaires type "Intro").
   // Utilisé à la fois par le réglage (settings.php, whitelist Tagify) et
   // par sbModuleUsesPageBuilder() (vérification côté chaque module).
   function sbGetPageBuilderModulesList() {
      return array(
          'pages.content_fr'  => 'Pages – Contenu'
         ,'news.desc_full_fr' => 'Actualités – Article'
         ,'tabbs.content_fr'  => 'Tabbs – Contenu'
         ,'faq.response'      => 'FAQ – Réponse'
         ,'blocs.content_fr'  => 'Blocs – Contenu'
      );
   }
}

if (!function_exists("sbModuleUsesPageBuilder")) {
   // $sb_link_settings est chargé une fois par index.php avant le
   // dispatch vers le module - disponible tel quel (même portée
   // d'inclusion) dans pages.php/news.php/etc, mais une fonction a besoin
   // de "global" pour y accéder.
   function sbModuleUsesPageBuilder($key) {
      global $sb_link_settings;
      if (empty($sb_link_settings) || !isset($sb_link_settings[36])) return false;
      $selected = array_map('trim', explode(',', trim($sb_link_settings[36])));
      return in_array($key, $selected, true);
   }
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

/**
 * Keep the server's PHP upload limits in sync with the "Taille max. autorisée
 * pour l'upload" CMS setting, so large uploads don't need a manual server
 * config step. Updates backdoor/.htaccess (mod_php) and backdoor/.user.ini
 * (PHP-FPM/CGI) in place - whichever one the server actually honors takes
 * effect, the other is a harmless no-op. post_max_size is kept a bit above
 * upload_max_filesize to leave room for the rest of the multipart form.
 *
 * @param int $bytes Max upload size in bytes (ex: from sbToByteSize()).
 */
function sbSyncUploadLimits($bytes) {
    if (!$bytes || $bytes <= 0) return;

    $uploadMb = max(1, (int)ceil($bytes / (1024 * 1024)));
    $postMb   = $uploadMb + 5;

    $targets = array(
        SBUIADMIN_PATH . '/.htaccess' => array(
            '/^php_value upload_max_filesize .*$/m' => 'php_value upload_max_filesize ' . $uploadMb . 'M',
            '/^php_value post_max_size .*$/m'       => 'php_value post_max_size ' . $postMb . 'M',
        ),
        SBUIADMIN_PATH . '/.user.ini' => array(
            '/^upload_max_filesize\s*=.*$/m' => 'upload_max_filesize = ' . $uploadMb . 'M',
            '/^post_max_size\s*=.*$/m'       => 'post_max_size = ' . $postMb . 'M',
        ),
    );

    foreach ($targets as $path => $patterns) {
        if (!is_file($path) || !is_writable($path)) continue;
        $content = file_get_contents($path);
        if ($content === false) continue;
        $content = preg_replace(array_keys($patterns), array_values($patterns), $content);
        file_put_contents($path, $content, LOCK_EX);
    }
}

function sbGetInfoImg($image_path, $info = 'width') {
   // Get infos image
   $file_image = getimagesize($image_path);
   if ($file_image === false)
	  return 0; // Not an image (getimagesize failed, ex: PDF/video/other)
   $file_image = array_values($file_image);
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
   $file = getimagesize($image_path);

   if ($file === false)
	  return false; // Not an image (getimagesize failed, ex: PDF/video/other)

   if ($file[0] > 0)
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
defined('BY_EXTENSION') OR define("BY_EXTENSION", 1);
defined('BY_EXPRESSION') OR define("BY_EXPRESSION", 2);

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
	$Directory = rtrim($Directory, '/') . '/';

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
		if (preg_match("/$Condition/", $dirEntry)) $add = true;
		
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

/*
 * This PHP script defines (if not exists) a strftime() function that is
 * deprecated and will be removed from standard PHP functions in the future.
 * The only thing you need to do is to load the script before everything else.
 * In this way, it is possible to run older code work based on strftime()
 * function on PHP version that doesn't support it without modifying your code.
 *
 * The script uses two methods to get the text:
 *   - using shell command;
 *   - using intl IntlDateFormatter class and additional processing.
 *
 * The choice between these two methods is automatic. The first method is used
 * if the system allows execution of shell commands and is the more reliable
 * option. The second method is not complete. I'm having trouble finding
 * a solution for the %V, %g, %G, %X, %c, %x tags.
 *
 * Pavel Tzonkov (C)2023
 */
if (!function_exists('strftime')) {
    function strftime($format, $timestamp=null) {

    // PARAMETER 1 CHECK

        if (($format === null) || ($format === false))
            return false;

        if ($format === true)
            return '1';

        $type = gettype($format);

        if (preg_match('/^(array|object|resource|resource \(closed\)|unknown type)$/', $type)) {
            trigger_error('strftime() expects parameter 1 to be string, ' . $type . ' given', E_USER_WARNING);
            return false;
        }

        if (preg_match('/^(integer|double)$/', $type))
            return (string) $format;

        if ($type !== 'string')
            return false;

    // PARAMETER 2 CHECK

        $type = gettype($timestamp);

        if ($timestamp === null)
            $timestamp = time();

        elseif (
            !is_scalar($timestamp) ||
            (is_string($timestamp) && !preg_match('/^(0|[1-9]\d*)$/', $timestamp))
        ) {
            trigger_error('strftime() expects parameter 2 to be integer, ' . $type . ' given', E_USER_WARNING);
            return false;
        }

        if (!is_integer($timestamp))
            $timestamp = (int) $timestamp;

        $locale = setlocale(LC_TIME, '0');


// EASY WAY - USING SHELL TO GET DATE TEXT

        if (is_callable('shell_exec') && (stripos(ini_get('disable_functions'), 'shell_exec') === false)) {
            $cmd = 'export LC_TIME=' . escapeshellarg($locale) . '; date --date @' . escapeshellarg($timestamp) . ' +' . escapeshellarg($format);
            return preg_replace('/\r?\n$/', '', shell_exec($cmd));
        }


// HARD WAY - NOT COMPLETED

    // CHECK FORMAT

        $format = strtr($format,[
            '%r' => '%I:%M:%S %p',
            '%R' => '%H:%M',
            '%T' => '%H:%M:%S',
            '%D' => '%m/%d/%y',
            '%F' => '%Y-%m-%d'
        ]);

        $modifiers = 'aAdejuwUVWbBhmCgGyYHkIlMpPSXzZcsxnt%';
        if (!preg_match('/%[' . $modifiers . ']/', $format))
            return $format;

    // FORMAT MAP

        $map = [    // https://unicode-org.github.io/icu/userguide/format_parse/datetime/
                    // https://www.php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters

            // DAY
            '%a' => 'ccc',      // Mon - Sun
            '%A' => 'cccc',     // Monday - Sunday
            '%d' => 'dd',       // 01 - 31
            '%e' => 'd',        // 1 - 31
            '%j' => ['D'],      // 001 - 366
            '%u' => ['c'],      // 1 - 7
            '%w' => ['c'],      // 0 - 6

            // WEEK
            '%U' => ['w'],      // Week number of the given year, starting with the first Sunday as the first week
            '%V' => ['ww'],     // Week number of the given year, starting with the first week of the year with at least 4 weekdays, with Monday being the start of the week (ISO-8601:1988)
            '%W' => ['w'],      // A numeric representation of the week of the year, starting with the first Monday as the first week

            // MONTH
            '%b' => 'LLL',      // Jan - Dec
            '%B' => 'LLLL',     // January - December
            '%h' => 'LLL',      // Jan - Dec
            '%m' => 'LL',       // 01 - 12

            // YEAR
            '%C' => ['y'],      // Two digit representation of the century (year divided by 100, truncated to an integer)
            '%g' => ['yy'],     // Two digit representation of the year (ISO-8601:1988 see %V)
            '%G' => ['y'],      // Full digit representation of the year (ISO-8601:1988 see %V)
            '%y' => 'yy',       // Two digit representation of the year
            '%Y' => 'y',        // Full digit representation of the year

            // TIME
            '%H' => 'HH',       // Hour 00 - 23
            '%k' => 'H',        // Hour 0 - 23
            '%I' => 'hh',       // Hour 01 - 12
            '%l' => 'h',        // Hour 1 - 12
            '%M' => 'mm',       // Minutes 00 - 59
            '%p' => [],         // AM / PM
            '%P' => [],         // am / pm
            '%S' => 'ss',       // Seconds 00 - 59
            '%X' => [],         // Preferred time representation based on locale, without the date. Example: 03:59:16 or 15:59:16
            '%z' => 'Z',        // Time zone -0500 for US Eastern Time
            '%Z' => 'z',        // Time zone EST for Eastern Time

            // TIME AND DATA STAMPS
            '%c' => [],         // Preferred date and time stamp based on locale. Example: Tue Feb 5 00:45:10 2009
            '%s' => [],         // Unix Epoch Time timestamp (same as the time() function)
            '%x' => [],         // Preferred date representation based on locale, without the time. Example: 02/05/09

            // MISCELLANEOUS
            '%n' => [],         // \n
            '%t' => [],         // \t
            '%%' => []          // %
        ];

        $timezone = date_default_timezone_get();

        $return = '';

        $length = strlen($format);

        for ($i = 0; $i < $length; $i++) {

            $current_char = $format[$i];
            $next_char = $i < $length - 1 ? $format[$i + 1] : false;

            // NORMAL TEXT
            if ($current_char !== '%') {
                $return .= $current_char;
                continue;
            }

            // MODIFIER
            else {

                // LAST CHARACTER
                if ($next_char === false) {
                    $return .= '%';
                    continue;
                }

                $fmt = $current_char . $next_char;
                $i++;

                // NOT FOUND
                if (!isset($map[$fmt])) {
                    $return .= $fmt;
                    continue;
                }

                // SIMPLE MODIFIER
                if (is_string($map[$fmt])) {
                    $return .= datefmt_format(datefmt_create(
                        $locale,
                        IntlDateFormatter::FULL,
                        IntlDateFormatter::FULL,
                        $timezone,
                        IntlDateFormatter::GREGORIAN,
                        $map[$fmt]
                    ), $timestamp);
                    continue;
                }

                // SPECIAL MODIFIERS
                if (!empty($map['fmt']))
                    $str = datefmt_format(datefmt_create(
                        $locale,
                        IntlDateFormatter::FULL,
                        IntlDateFormatter::FULL,
                        $timezone,
                        IntlDateFormatter::GREGORIAN,
                        $map[$fmt][0]
                    ), $timestamp);

                if ($fmt == '%j')
                    $return .= sprintf("%03d", $str);

                elseif ($fmt == '%u')
                    $return .= (--$str ? $str : '7');

                elseif ($fmt == '%w')
                    $return .= --$str;

                elseif ($fmt == '%U') {

                }

                elseif ($fmt == '%V') {

                }

                elseif ($fmt == '%W') {

                }

                elseif ($fmt == '%C')
                    $return .= (string) floor($str / 100);

                elseif ($fmt == '%g') {

                }

                elseif ($fmt == '%G') {

                }

                elseif (($fmt == '%p') || ($fmt == '%P')) {
                    $str = datefmt_format(datefmt_create(
                        'en_US',
                        IntlDateFormatter::FULL,
                        IntlDateFormatter::FULL,
                        $timezone,
                        IntlDateFormatter::GREGORIAN,
                        'a'
                    ), $timestamp);
                    $return .= ($fmt == '%p') ? strtoupper($str) : strtolower($str);
                }

                elseif ($fmt == '%X') {

                }

                elseif ($fmt == '%c') {

                }

                elseif ($fmt == '%s')
                    $return .= $timestamp;

                elseif ($fmt == '%x') {

                }

                elseif ($fmt == '%n')
                    $return .= "\n";

                elseif ($fmt == '%t')
                    $return .= "\t";

                elseif ($fmt == '%%')
                    $return .= '%';

                else
                    $return .= $fmt;

                continue;
            }
        }
        return $return;
    }
}

/**
 * Show date ISO in various format
 * This method return string to display
 *
 * @param	$date		Date ISO Format from MySQL
 * @param	$format		Format to return (ISO, US, UST, FR, FR2, FR3, FRT, FRH, YEAR)
 *
 * @return converted string
 */
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
	
	$lang    = (isset($_SESSION['lang']) && $_SESSION['lang'] != '') ? $_SESSION['lang'] : $langdefault;
	
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
 * Avatar utilisateur : photo choisie via le picker "photo" standard
 * (Médiathèque, sous-dossier avatars - voir users.php et
 * _AM_AVATARS_DIR/_AM_AVATARS_URL dans inc/sbuiadmin-config.php) si
 * présente, sinon repli sur Gravatar.
 * @param string $avatar Nom de fichier stocké dans sb_users.avatar (vide = pas de photo locale)
 * @param string $email
 * @param int $s Taille, transmise à Gravatar uniquement (l'avatar local est affiché tel quel)
 * @return string URL (relative pour l'avatar local, absolue pour Gravatar)
 */
function sbGetUserAvatar($avatar, $email, $s = 80) {
    if (!empty($avatar) && is_file(_AM_AVATARS_DIR . $avatar)) {
        return _AM_AVATARS_URL . '/' . $avatar;
    }
    return sbGetGravatar($email, $s);
}

/**
 * Whitelist des tables/colonnes réellement présentes en base (widgets du
 * dashboard, voir dashboard.php/index.php) - une valeur de table/colonne
 * soumise par un formulaire ne doit jamais être interpolée dans du SQL
 * sans avoir été confrontée à ce schéma au préalable. Le type de colonne
 * (ex: 'int(11)', 'date', 'datetime') sert à distinguer une vraie colonne
 * date/datetime/timestamp d'un entier de type "timestamp Unix" (ex:
 * sb_users.logintime) pour construire la bonne comparaison SQL (voir
 * index.php, calcul de tendance/graphique).
 * @return array ['nom_table_sans_prefixe' => ['col1' => 'type', ...]]
 */
function sbGetDbSchema() {
    global $sbsql;

    $schema = array();
    $prefix = _AM_DB_PREFIX;

    // mysqli_report(MYSQLI_REPORT_STRICT) est actif (voir sql::connect()) :
    // une requête en échec lève une exception au lieu de renvoyer false -
    // cette fonction ne doit jamais casser toute la page pour un souci de
    // schéma ponctuel (droits DB restreints, table verrouillée, etc.).
    try {
        $request_tables = $sbsql->query("SHOW TABLES LIKE '" . $sbsql->escape_string($prefix) . "%'");
        $tables = $sbsql->toarray($request_tables);
        if (!is_array($tables)) return $schema;
    } catch (Exception $e) {
        return $schema;
    }

    foreach ($tables as $t) {
        $full_name  = $t[0];
        $short_name = (strpos($full_name, $prefix) === 0) ? substr($full_name, strlen($prefix)) : $full_name;

        try {
            $request_cols = $sbsql->query("SHOW COLUMNS FROM `$full_name`");
            $cols = $sbsql->toarray($request_cols);
            if (!is_array($cols)) continue;
        } catch (Exception $e) {
            continue;
        }

        $schema[$short_name] = array();
        foreach ($cols as $c) {
            $schema[$short_name][$c[0]] = $c[1];
        }
    }

    return $schema;
}

/**
 * Widgets système du dashboard (Point 3) - une métrique par clé, sans lien
 * avec une table SQL contrairement aux widgets "table" classiques.
 * @param string $key voir dashboard.php pour la liste des clés proposées
 * @return string valeur déjà formatée pour affichage
 */
function sbGetSystemWidgetValue($key) {
    global $sbsql;

    switch ($key) {
        case 'users_count':
            try {
                $row = $sbsql->assoc($sbsql->query("SELECT COUNT(*) AS cpt FROM " . _AM_DB_PREFIX . "sb_users"));
                return ($row) ? intval($row['cpt']) : 0;
            } catch (Exception $e) {
                return 0;
            }

        case 'php_version':
            return _AM_SERVER_PHP_VERSION_ID;

        case 'db_host':
            return _AM_DB_HOST;

        case 'upload_limit':
            return _AM_MEDIAS_SIZE_LIMIT;

        case 'disk_free':
            $bytes = @disk_free_space(SBUIADMIN_PATH);
            return ($bytes !== false) ? sbFormatByteSize($bytes) : 'N/A';

        case 'media_size':
            return sbFormatByteSize(sbGetDirectorySize(_AM_MEDIAS_DIR));

        case 'active_sessions':
            try {
                $row = $sbsql->assoc($sbsql->query("SELECT COUNT(*) AS cpt FROM " . _AM_DB_PREFIX . "sb_sessions WHERE expiredate > NOW()"));
                return ($row) ? intval($row['cpt']) : 0;
            } catch (Exception $e) {
                return 0;
            }
    }

    return '';
}

/**
 * Formatage "Go/Mo/Ko/bytes" d'un nombre d'octets déjà connu - mêmes
 * seuils que sbDisplayMediaSize() (qui part elle d'un chemin de fichier).
 */
function sbFormatByteSize($bytes) {
    if ($bytes >= 1073741824) return round($bytes / 1073741824 * 100) / 100 . ' Go';
    if ($bytes >= 1048576)    return round($bytes / 1048576 * 100) / 100 . ' Mo';
    if ($bytes >= 1024)       return round($bytes / 1024 * 100) / 100 . ' Ko';
    return $bytes . ' bytes';
}

/**
 * Taille totale (récursive) d'un dossier - utilisée par le widget système
 * "Espace utilisé par les médias".
 */
function sbGetDirectorySize($dir) {
    if (!is_dir($dir)) return 0;

    $total    = 0;
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file->isFile()) $total += $file->getSize();
    }
    return $total;
}

/**
 * Widget météo (Point 3) - prévisions Open-Meteo (gratuit, sans clé) pour
 * les coordonnées résolues à l'enregistrement du widget (voir
 * sbGeocodeCity(), appelée une seule fois depuis dashboard.php - pas à
 * chaque affichage du dashboard). Timeout volontairement court : ce widget
 * ne doit jamais ralentir longtemps le rendu du dashboard si l'API est
 * lente/indisponible - repli explicite plutôt qu'une page cassée.
 * @param string $location format "Ville|lat|lon" (voir dashboard.php)
 * @return array|false ['city'=>.., 'temp'=>.., 'icon'=>.., 'label'=>..] ou false si indisponible
 */
function sbGetWeatherWidgetValue($location) {
    $parts = explode('|', $location);
    if (count($parts) < 3) return false;

    list($city, $lat, $lon) = $parts;
    $url = 'https://api.open-meteo.com/v1/forecast?latitude=' . urlencode($lat) . '&longitude=' . urlencode($lon) . '&current=temperature_2m,weather_code';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 4);
    curl_setopt($curl, CURLOPT_USERAGENT, 'SBUIADMIN Dashboard Widget');
    $response  = curl_exec($curl);
    $curlError = curl_errno($curl);
    curl_close($curl);

    if ($curlError || !$response) return false;

    $data = json_decode($response, true);
    if (!isset($data['current']['temperature_2m'])) return false;

    $code = isset($data['current']['weather_code']) ? intval($data['current']['weather_code']) : 0;

    return array(
        'city'  => $city,
        'temp'  => round($data['current']['temperature_2m']),
        'icon'  => sbGetWeatherIcon($code),
        'label' => sbGetWeatherLabel($code),
    );
}

/**
 * Icône Font Awesome (bundle FA4 embarqué) selon le code météo WMO renvoyé
 * par Open-Meteo - volontairement simplifié (quelques familles plutôt que
 * les ~30 codes WMO un par un, FA4 n'a de toute façon pas d'icône dédiée
 * à chacun).
 */
function sbGetWeatherIcon($code) {
    if ($code == 0) return 'sun-o';
    if ($code <= 48) return 'cloud';
    if ($code <= 67) return 'umbrella';
    if ($code <= 77) return 'asterisk';
    if ($code <= 82) return 'umbrella';
    if ($code <= 86) return 'asterisk';
    if ($code >= 95) return 'bolt';
    return 'cloud';
}

/**
 * Libellé français selon le code météo WMO - voir sbGetWeatherIcon().
 */
function sbGetWeatherLabel($code) {
    if ($code == 0) return 'Ciel dégagé';
    if ($code <= 3) return 'Partiellement nuageux';
    if ($code <= 48) return 'Brouillard';
    if ($code <= 67) return 'Pluie';
    if ($code <= 77) return 'Neige';
    if ($code <= 82) return 'Averses';
    if ($code <= 86) return 'Averses de neige';
    if ($code >= 95) return 'Orage';
    return 'Nuageux';
}

/**
 * Géocodage d'un nom de ville (Open-Meteo, gratuit, sans clé) - appelé une
 * seule fois à l'enregistrement d'un widget météo (voir dashboard.php),
 * jamais à l'affichage du dashboard.
 * @return array|false ['city'=>.., 'lat'=>.., 'lon'=>..] ou false si la ville est introuvable/API indisponible
 */
function sbGeocodeCity($city) {
    $url = 'https://geocoding-api.open-meteo.com/v1/search?name=' . urlencode($city) . '&count=1&language=fr';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 8);
    curl_setopt($curl, CURLOPT_USERAGENT, 'SBUIADMIN Dashboard Widget');
    $response  = curl_exec($curl);
    $curlError = curl_errno($curl);
    curl_close($curl);

    if ($curlError || !$response) return false;

    $data = json_decode($response, true);
    if (empty($data['results'][0])) return false;

    $result = $data['results'][0];
    return array(
        'city' => $result['name'],
        'lat'  => $result['latitude'],
        'lon'  => $result['longitude'],
    );
}

/**
 * Widget dashboard "rss" - lit un flux RSS 2.0 ou Atom et renvoie les
 * $limit entrées les plus récentes. Aucun cache : lu à chaque affichage
 * du dashboard, comme la météo (voir sbGetWeatherWidgetValue()) - même
 * repli silencieux (false) en cas d'échec réseau/flux invalide.
 * @return array|false [['title'=>.., 'link'=>.., 'date'=>..], ...] ou false
 */
function sbGetRssWidgetValue($url, $limit = 5) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);
    curl_setopt($curl, CURLOPT_USERAGENT, 'SBUIADMIN Dashboard Widget');
    $response  = curl_exec($curl);
    $curlError = curl_errno($curl);
    curl_close($curl);

    if ($curlError || !$response) return false;

    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($response);
    libxml_use_internal_errors(false);
    if (!$xml) return false;

    $items = array();

    if (isset($xml->channel->item)) {
        // --- RSS 2.0
        foreach ($xml->channel->item as $item) {
            $items[] = array(
                'title' => (string) $item->title,
                'link'  => (string) $item->link,
                'date'  => isset($item->pubDate) ? date('d/m/Y', strtotime((string) $item->pubDate)) : '',
            );
            if (count($items) >= $limit) break;
        }
    } elseif (isset($xml->entry)) {
        // --- Atom
        foreach ($xml->entry as $entry) {
            $link = '';
            if (isset($entry->link)) {
                foreach ($entry->link as $sb_atom_link) {
                    if (!isset($sb_atom_link['rel']) || (string) $sb_atom_link['rel'] == 'alternate') {
                        $link = (string) $sb_atom_link['href'];
                        break;
                    }
                }
            }
            $items[] = array(
                'title' => (string) $entry->title,
                'link'  => $link,
                'date'  => isset($entry->updated) ? date('d/m/Y', strtotime((string) $entry->updated)) : '',
            );
            if (count($items) >= $limit) break;
        }
    } else {
        return false;
    }

    return $items;
}

/**
 * Widget dashboard "logs" - dernières $lines lignes d'un fichier situé
 * dans backdoor/logs/ (protégé par .htaccess, accès web direct interdit).
 * $filename est réduit à son basename() avant toute utilisation : aucun
 * chemin ni traversée de répertoire n'est jamais possible depuis le
 * formulaire, quelle que soit la valeur saisie.
 * @return array|false lignes (les plus anciennes en premier) ou false si le fichier n'existe pas/n'est pas lisible
 */
function sbTailLogFile($filename, $lines = 15) {
    $safeName = basename($filename);
    if ($safeName === '') return false;

    $path = SBUIADMIN_PATH . '/logs/' . $safeName;
    if (!is_file($path) || !is_readable($path)) return false;

    $size   = filesize($path);
    $handle = fopen($path, 'r');
    if (!$handle) return false;

    // 64 Ko lus depuis la fin suffisent très largement pour ~15-100 lignes
    // de log usuelles - pas besoin de charger le fichier entier.
    $chunkSize = min(65536, $size);
    fseek($handle, -$chunkSize, SEEK_END);
    $chunk = fread($handle, $chunkSize);
    fclose($handle);

    $rows = explode("\n", trim($chunk));
    return array_slice($rows, -$lines);
}

/**
 * Widget dashboard "logaccess" - les $limit dernières connexions réussies
 * (module Journaux, table sb_logaccess). Ne remonte que logaccess_type =
 * 'login' - les tentatives échouées ('error') ne sont volontairement pas
 * mélangées, ce n'est pas ce que "dernières connexions" désigne.
 * @return array [['user'=>.., 'date'=>..], ...] (vide si aucune ou en cas d'erreur)
 */
function sbGetLastLoginsWidgetValue($limit = 10) {
    global $sbsql;

    try {
        // LEFT JOIN : un utilisateur supprimé depuis reste visible dans
        // l'historique (avatar/email absents -> repli Gravatar générique
        // via sbGetUserAvatar()).
        $rows = $sbsql->toarray($sbsql->query(
            "SELECT l.logaccess_user, l.logaccess_date, u.avatar, u.email
			 FROM " . _AM_DB_PREFIX . "sb_logaccess l
			 LEFT JOIN " . _AM_DB_PREFIX . "sb_users u ON u.username = l.logaccess_user
			 WHERE l.logaccess_type = 'login' ORDER BY l.logaccess_date DESC LIMIT " . intval($limit)
        ));
    } catch (Exception $e) {
        return array();
    }

    $items = array();
    foreach ($rows as $sb_row) {
        $items[] = array(
            'user'   => $sb_row['logaccess_user'],
            'date'   => date('d/m/Y H:i', intval($sb_row['logaccess_date'])),
            'avatar' => sbGetUserAvatar($sb_row['avatar'] ?? '', $sb_row['email'] ?? '', 40),
        );
    }
    return $items;
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
			$modules_dir = array_values(array_intersect($modules_order, $modules_dir));
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
		for($i = 0; $i < count($modules_dir); $i++) {
			// --- Get Module name
			if (isset($modules_dir[$i])) $module_name = pathinfo($modules_dir[$i], PATHINFO_FILENAME);
			
			// --- Check if Module infos exists
			if (isset($module_menu[$module_name]['main'])) {
				
				// --- Check if user is authorized to view menu (Global)
				if (($module_menu[$module_name]['group'] == 'admin' && $sbuiadmin_user_type == 'admin')
					|| ($module_menu[$module_name]['group'] == 'user' && $sbuiadmin_user_type == 'admin')
					|| ($module_menu[$module_name]['group'] == 'user' && $sbuiadmin_user_type == 'user')) {
					
					// --- Check if user is authorized to view menu (droits granulaires,
					// voir inc/sbuiadmin-rights.php - remplace l'ancienne liste
					// pipe-délimitée sb_users.menu, devenue inutilisée).
					// Un module avec sous-menu (li) peut regrouper des entrées pointant
					// vers des modules différents (ex: "Configuration" -> session/cache/
					// server/theme/..., "Pages" -> blocs) : se contenter de vérifier le
					// droit du module PARENT masquerait tout le groupe même si certaines
					// sous-entrées restent autorisées individuellement. Chaque lien est
					// donc filtré pour lui-même (sbHasMenuLinkRight) ; le groupe entier
					// n'est masqué que si plus aucun lien n'y survit. Pour un module sans
					// sous-menu (lien unique), ça revient exactement à vérifier le module.
					$module_menu[$module_name]['li'] = (isset($module_menu[$module_name]['li'])) ? $module_menu[$module_name]['li'] : [];
					$ul_module_menu = count((array)$module_menu[$module_name]['li']);

					if ($ul_module_menu > 0) {
						$visible_li = array();
						foreach ($module_menu[$module_name]['li'] as $li_item) {
							$li_link  = isset($li_item['link']) ? $li_item['link'] : '';
							$li_rights = isset($li_item['rights']) ? $li_item['rights'] : null;
							if (sbHasMenuLinkRight($li_link, $li_rights)) $visible_li[] = $li_item;
						}
						$module_menu[$module_name]['li'] = $visible_li;
						$ul_module_menu = count($visible_li);
						$show_module = ($ul_module_menu > 0);
					} else {
						$show_module = sbHasRight($module_name, 'view');
					}

					if ($show_module) {
						// Init Current URL
						$request_url = 'http' . (($_SERVER['HTTPS'] == 'on') ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

						// Check if active menu
						if ($request_url == _AM_SITE_URL . "index.php?p=" . $module_name && $ul_module_menu == 0) {
						     $class_active = ' is-active';
						} elseif (isset($_GET['p']) && trim($_GET['p']) == $module_name && $ul_module_menu == 0) {
						     $class_active = ' is-active';
						} elseif ( isset($_GET['p']) && (trim($_GET['p']) == 'session' || trim($_GET['p']) == 'cache' || trim($_GET['p']) == 'dashboard' || trim($_GET['p']) == 'toolbarck' || trim($_GET['p']) == 'theme' || trim($_GET['p']) == 'themeinfos') && $module_name == 'settings' && $ul_module_menu == 0) {
						     $class_active = ' is-active';
						} else {
						     $class_active = '';
						}

						// --- Check if there is menu entries
						if ($ul_module_menu > 0) {
							// Item with a submenu - open when the current page is either the
							// module itself, or any of its submenu entries (ex: "settings" has
							// entries pointing to p=session, p=server, p=cache... not just
							// p=settings, so a plain $_GET['p'] == $module_name check only kept
							// the group open for the very first entry).
							$collapse_in = '';
							if (isset($_GET['p'])) {
								if (trim($_GET['p']) == $module_name) {
									$collapse_in = ' is-open';
								} else {
									foreach ((array)$module_menu[$module_name]['li'] as $sub_li) {
										if (empty($sub_li['link'])) continue;
										parse_str((string)parse_url($sub_li['link'], PHP_URL_QUERY), $sub_li_params);
										if (isset($sub_li_params['p']) && $sub_li_params['p'] == trim($_GET['p'])) {
											$collapse_in = ' is-open';
											break;
										}
									}
								}
							}
							// Highlight the group's own toggle link (like a permanent hover)
							// whenever the browsed page belongs to this section, so the active
							// section is visible even when the submenu covers several distinct
							// p= values and no single child looks like an obvious "parent" match.
							$is_current_section = ($collapse_in != '') ? ' is-current' : '';
							$ret_module_menu .= '<div class="nav-item-group' . $collapse_in . '" data-nav-group id="' . $module_name . '">';
							$ret_module_menu .= '<a class="nav-link' . $is_current_section . '" href="javascript:void(0)" data-nav-toggle>';
							$ret_module_menu .= '<i class="fa fa-' . $module_menu[$module_name]['icon'].' fa-fw"></i><span>'. $module_menu[$module_name]['main'] . '</span>';
							$ret_module_menu .= '<svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m9 18 6-6-6-6"/></svg>';
							$ret_module_menu .= '</a>';
							$ret_module_menu .= '<div class="nav-submenu">';

							// Menu entries (choices) - matched by comparing query params against
							// the current request rather than exact full-URL string equality
							// (too fragile: broke on any incidental difference). Beyond p=, each
							// submenu family uses its own extra param to tell entries apart -
							// a= for users.php, op= for cmsconfig.php, none at all for settings.php
							// (each entry there has a distinct p=) - so rather than hardcoding one
							// param name (which missed op= and made every cmsconfig entry show
							// active together, since they only differ by op=), collect whichever
							// keys this module's own entries actually use to distinguish
							// themselves, and require the current request to agree with the
							// entry on all of them (present-and-equal, or both absent).
							$li_discriminator_keys = array();
							foreach ((array)$module_menu[$module_name]['li'] as $sib_li) {
								if (empty($sib_li['link'])) continue;
								parse_str((string)parse_url($sib_li['link'], PHP_URL_QUERY), $sib_params);
								foreach ($sib_params as $sib_key => $sib_val) {
									if ($sib_key != 'p') $li_discriminator_keys[$sib_key] = true;
								}
							}
							$li_discriminator_keys = array_keys($li_discriminator_keys);

							for($j = 0; $j < $ul_module_menu; $j++) {
								$class_active = '';
								if (!empty($module_menu[$module_name]['li'][$j]['link']) && isset($_GET['p'])) {
									parse_str((string)parse_url($module_menu[$module_name]['li'][$j]['link'], PHP_URL_QUERY), $li_params);
									$li_matches = isset($li_params['p']) && $li_params['p'] == trim($_GET['p']);
									if ($li_matches) {
										foreach ($li_discriminator_keys as $disc_key) {
											$li_val  = isset($li_params[$disc_key]) ? $li_params[$disc_key] : null;
											$get_val = isset($_GET[$disc_key]) ? trim($_GET[$disc_key]) : null;
											if ($li_val !== $get_val) {
												$li_matches = false;
												break;
											}
										}
									}
									if ($li_matches) {
										$class_active = ' is-active';
									}
								}
								$link_target  = !empty($module_menu[$module_name]['li'][$j]['target']) ? ' target="' . $module_menu[$module_name]['li'][$j]['target'] . '"' : '';
								$ret_module_menu .= '<a class="' . trim($class_active) . '" id="ss-' . strtolower(sbRewriteToId($module_menu[$module_name]['li'][$j]['title'])) . '" href="'.$module_menu[$module_name]['li'][$j]['link'].'"'.$link_target.'>'.$module_menu[$module_name]['li'][$j]['title'].'</a>';
							}

							$ret_module_menu .= '</div>'; // /.nav-submenu
							$ret_module_menu .= '</div>'; // /.nav-item-group
						} else {
							// Flat item, no submenu
							$ret_module_menu .= '<a class="nav-link' . $class_active . '" id="' . $module_name . '" href="index.php?p=' . $module_name . '">';
							$ret_module_menu .= '<i class="fa fa-' . $module_menu[$module_name]['icon'].' fa-fw"></i><span>'. $module_menu[$module_name]['main'] . '</span>';
							$ret_module_menu .= '</a>';
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

/**
 * Return JSON array
 *
 * @return string
 */
if (!function_exists("insert_sbExplodeJson")) {
	function insert_sbExplodeJson($param) {
		$client_info = "";
		$json = json_decode($param['json'], true);
      if (json_last_error() === JSON_ERROR_NONE) {
         foreach($json as $key => $row) {
            if ($key == 'location') {
               foreach($row as $k => $v) {
                  if ($k == 'languages') {
                     foreach($v[0] as $ke => $va) {
                        $client_info .= $ke . ' : ' . $va . '<br>';
                     }
                  } else {
                     $client_info .= $k . ' : ' . $v . '<br>';
                  }
               }
            } else {
               $client_info .= $key . ' : ' . $row . '<br>';
            }
         }
      } else {
         $client_info = $param['json'];
      }
		return $client_info;
	}
}

?>
