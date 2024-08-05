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
			if (isset($modules_dir[$i])) $module_name = pathinfo($modules_dir[$i], PATHINFO_FILENAME);
			
			// --- Check if Module infos exists
			if (isset($module_menu[$module_name]['main'])) {
				
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
                  $module_menu[$module_name]['li'] = (isset($module_menu[$module_name]['li'])) ? $module_menu[$module_name]['li'] : [];
                  $ul_module_menu = count((array)$module_menu[$module_name]['li']);
						
						// Check if active menu
						if ($request_url == _AM_SITE_URL . "index.php?p=" . $module_name && $ul_module_menu == 0) {
						     $class_active = ' class="active" ';
						} elseif (isset($_GET['p']) && trim($_GET['p']) == $module_name && $ul_module_menu == 0) {
						     $class_active = ' class="active" ';
						} elseif ( isset($_GET['p']) && (trim($_GET['p']) == 'session' || trim($_GET['p']) == 'cache' || trim($_GET['p']) == 'dashboard' || trim($_GET['p']) == 'toolbarck' || trim($_GET['p']) == 'theme' || trim($_GET['p']) == 'themeinfos') && $module_name == 'settings' && $ul_module_menu == 0) {
						     $class_active = ' class="active" ';
						} else {
						     $class_active = ' ';
						}
						
						// Main menu
						$ret_module_menu .= '<a'.$class_active.'href="index.php?p=' . $module_name . '">';
						$ret_module_menu .= '<i class="fa fa-' . $module_menu[$module_name]['icon'].' fa-fw fa-menu-i"></i> '. $module_menu[$module_name]['main'];
						if ($ul_module_menu) $ret_module_menu .= '<span class="fa arrow"></span>';
						$ret_module_menu .= '</a>';
						
						// --- Check if there is menu entries
						if ($ul_module_menu > 0) {
							// Menu UL (if entries)
							$collapse_in = (isset($_GET['p']) && $_GET['p'] == $module_name) ? ' collapse in' : '';
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
