<?php
/** *****************************************************************************
*                      INFORMATUX sanitize class (UTF8)                         *
/** *****************************************************************************
* @author     Patrice BOUTHIER <contact[at]informatux.com>                      *
* @copyright  1996-2016 INFORMATUX                                              *
* @link       http://www.informatux.com/                                        *
* @since      1.0                                                               *
* @version    CVS: 1.8                                                          *
* ----------------------------------------------------------------------------- *
* Copyright (c) 2011, INFORMATUX Solutions and web development                  *
* All rights reserved.                                                          *
***************************************************************************** **/

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');


class sanitize extends sql {

	/**
	* Make links in the text clickable
	* @param  string  $text
	* @return string
	**/
	function makeClickable(&$text) {
		$patterns = array("/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([^, \r\n\"\(\)'<>]+)/i", "/(^|[^]_a-z0-9-=\"'\/])www\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)/i", "/(^|[^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)/i", "/(^|[^]_a-z0-9-=\"'\/:\.])([a-z0-9\-_\.]+?)@([^, \r\n\"\(\)'<>\[\]]+)/i");
		$replacements = array("\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", "\\1<a href=\"http://www.\\2.\\3\" target=\"_blank\">www.\\2.\\3</a>", "\\1<a href=\"ftp://ftp.\\2.\\3\" target=\"_blank\">ftp.\\2.\\3</a>", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>");
		return preg_replace($patterns, $replacements, $text);
	}


	/**
	* Convert linebreaks to <br /> tags
	* @param  string  $text
	* @return string
	*/
	function nl2Br($text) {
		return preg_replace("/(\015\012)|(\015)|(\012)/","<br />",$text);
	}


	/**
	* Convert <br /> to linebreaks tags
	* @param  string  $text
	* @return string
	*/
	function undoNl2Br($text) {
		return preg_replace("/(\015\012)|(\015)|(\012)/","\n",$text);
	}


	/**
	* Add slashes to the text if magic_quotes_gpc is turned off.
	* @param  string  $text
	* @return string
	**/
	function addSlashes($text) {
		if (!get_magic_quotes_gpc()) {
			$text = addslashes($text);
		}
		return $text;
	}


	/**
	* if magic_quotes_gpc is on, strip back slashes
	* @param  string  $text
	* @return string
	*/
	function stripSlashesGPC($text) {
		if (get_magic_quotes_gpc()) {
			$text = stripslashes($text);
		}
		return $text;
	}
	

	/**
	* Stripslashes on array
	* @param  array  $value
	* @return array
	*/
	function stripslashes_deep($value) {
		$value = is_array($value) ?
					array_map('stripslashes_deep', $value) :
					$this->stripSlashesGPC($value);
	
		return $value;
	}


	/**
	* for displaying data without html tags
	* @param  string  $text
	* @return string
	*/
	function stripTags($text) {
		return strip_tags($text);
	}


	/**
	* Convert all applicable characters to HTML entities
	* @param  strings $text, $encode
	* @return string
	*
	*				Supported charsets
	* ------------------------------------------------------------------------------
	* Charset  	Aliases  			Description
	* ------------------------------------------------------------------------------
	* ISO-8859-1  	ISO8859-1  			Western European, Latin-1
	* ISO-8859-15 	ISO8859-15 			Western European, Latin-9. Adds the Euro sign, French and Finnish letters missing in Latin-1(ISO-8859-1).
	* UTF-8 					ASCII compatible multi-byte 8-bit Unicode.
	* cp866 	ibm866, 866 			DOS-specific Cyrillic charset. This charset is supported in 4.3.2.
	* cp1251 	Windows-1251, win-1251, 1251 	Windows-specific Cyrillic charset. This charset is supported in 4.3.2.
	* cp1252 	Windows-1252, 1252 		Windows specific charset for Western European.
	* KOI8-R 	koi8-ru, koi8r			Russian. This charset is supported in 4.3.2.
	* BIG5		950				Traditional Chinese, mainly used in Taiwan.
	* GB2312 	936				Simplified Chinese, national standard character set.
	* BIG5-HKSCS					Big5 with Hong Kong extensions, Traditional Chinese.
	* Shift_JIS 	SJIS, 932			Japanese
	* EUC-JP 	EUCJP				Japanese
	* ------------------------------------------------------------------------------
	*/
	function htmlEntities($text, $encode = 'UTF-8') {
		return htmlentities($text, ENT_QUOTES, $encode);
	}


	/**
	* Convert all HTML entities to their applicable characters
	* @param  strings $text, $encode
	* @return string
	*
	*				Supported charsets
	* ------------------------------------------------------------------------------
	* Charset  	Aliases  			Description
	* ------------------------------------------------------------------------------
	* ISO-8859-1  	ISO8859-1  			Western European, Latin-1
	* ISO-8859-15 	ISO8859-15 			Western European, Latin-9. Adds the Euro sign, French and Finnish letters missing in Latin-1(ISO-8859-1).
	* UTF-8 					ASCII compatible multi-byte 8-bit Unicode.
	* cp866 	ibm866, 866 			DOS-specific Cyrillic charset. This charset is supported in 4.3.2.
	* cp1251 	Windows-1251, win-1251, 1251 	Windows-specific Cyrillic charset. This charset is supported in 4.3.2.
	* cp1252 	Windows-1252, 1252 		Windows specific charset for Western European.
	* KOI8-R 	koi8-ru, koi8r			Russian. This charset is supported in 4.3.2.
	* BIG5		950				Traditional Chinese, mainly used in Taiwan.
	* GB2312 	936				Simplified Chinese, national standard character set.
	* BIG5-HKSCS					Big5 with Hong Kong extensions, Traditional Chinese.
	* Shift_JIS 	SJIS, 932			Japanese
	* EUC-JP 	EUCJP				Japanese
	* ------------------------------------------------------------------------------
	*/
	function htmlEntitiesDecode($text, $encode = 'UTF-8') {
		return html_entity_decode($text, ENT_QUOTES, $encode);
	}


	/**
	* Convert special characters to HTML entities
	* @param   string  $text           string being converted
	* @param   int     $quote_style
	* @param   string  $charset        character set used in conversion
	* @param   bool    $double_encode
	* @return    string
	*/
	function htmlSpecialChars($text, $charset = "UTF-8", $double_encode = true) {
		if ( version_compare( phpversion(), "5.2.3", ">=" ) ) {
			$text = htmlspecialchars( $text, ENT_QUOTES, $charset, $double_encode );
		} else {
			$text = htmlspecialchars( $text, ENT_QUOTES);
		}
		return preg_replace(array("/&amp;/i", "/&nbsp;/i"),
				    array('&', '&amp;nbsp;'),
				    $text);
	}


	/**
	* Reverses {@link htmlSpecialChars()}
	* @param  string  $text
	* @return string
	**/
	function undoHtmlSpecialChars($text) {
		return preg_replace(array("/&gt;/i", "/&lt;/i", "/&quot;/i", "/&#039;/i", '/&amp;nbsp;/i'), array(">", "<", "\"", "'", "&nbsp;"), $text);
	}

	
	/**
	* Strip whitespace (or other characters) from the beginning and end of a string
	* @param  string  $text
	* @return string
	*
	*------------------------------------------------------------------------------
	*                 Character Mask
	* ------------------------------------------------------------------------------
    * " " (ASCII 32 (0x20)), an ordinary space.
    * "\t" (ASCII 9 (0x09)), a tab.
    * "\n" (ASCII 10 (0x0A)), a new line (line feed).
    * "\r" (ASCII 13 (0x0D)), a carriage return.
    * "\0" (ASCII 0 (0x00)), the NUL-byte.
    * "\x0B" (ASCII 11 (0x0B)), a vertical tab.
	*------------------------------------------------------------------------------
	**/
	function sTrim($text, $character_mask = '') {
		if ($character_mask != '')
			return trim($text, $character_mask);
		else
			return trim($text);
	}

	/**
	* Validate a website address
	* @param  string  $url
	* @return string url website
	*/
	function validateWebsite($url) {
		if ($url === 'http://') {
			return '';
		} else if (!preg_match('#^[a-z0-9]+://#i', $url) && strlen($url) > 0) {
			return 'http://' . $url;
		}
		return $url;
	}
	
	/**
	* Search for unavailable caracters and replace them
	* @param  string  $string
	* @return string string
	*/	
	function rewriteString($string, $lowupp = false) {
		$noValidString = trim($this->displayText($string));
		$noValidString = preg_replace('`\s+`', '-', trim($noValidString));
		$noValidString = str_replace("'", "-", $noValidString);
		$noValidString = str_replace('"', '-', $noValidString);
		$noValidString = preg_replace('`_+`', '-', trim($noValidString));
		$caracters_in  = array(' ', '?', '!', '.', ',', ':', "'", '&', '(', ')', '-', '/', '%', '=', '[', ']');
		$caracters_out = array('-', '', '', '', '', '-', '-', '-', '', '', '-', '-', '-', '', '', '');
		$noValidString = str_replace($caracters_in, $caracters_out, $noValidString);
		$noValidString = str_replace("------", "-", $noValidString);
		$noValidString = str_replace("-----", "-", $noValidString);
		$noValidString = str_replace("----", "-", $noValidString);
		$noValidString = str_replace("---", "-", $noValidString);
		$noValidString = str_replace("--", "-", $noValidString);
		$accents       = array('À','Á','Â','Ã','Ä','Å','à','á','â','ã','ä','å','Ò','Ó','Ô','Õ','Ö','Ø','ò','ó','ô','õ','ö','ø','È','É','Ê','Ë','è','é','ê','ë','Ç','ç','Ì','Í','Î','Ï','ì','í','î','ï','Ù','Ú','Û','Ü','ù','ú','û','ü','ÿ','Ñ','ñ');
		$ssaccents     = array('A','A','A','A','A','A','a','a','a','a','a','a','O','O','O','O','O','O','o','o','o','o','o','o','E','E','E','E','e','e','e','e','C','c','I','I','I','I','i','i','i','i','U','U','U','U','u','u','u','u','y','N','n');
		$validString   = str_replace($accents, $ssaccents, $noValidString);
	
		return (!$lowupp) ? $validString : strtolower($validString);
	}


	/**
	* A quick solution for filtering XSS scripts
	* @TODO: To be improved
	*/
	function filterXss($text) {
		$patterns = array();
		$replacements = array();

		$text           = str_replace( "\x00", "", $text );
		$c              = "[\x01-\x1f]*";
		$patterns[]     = "/\bj{$c}a{$c}v{$c}a{$c}s{$c}c{$c}r{$c}i{$c}p{$c}t{$c}[\s]*:/si";
		$replacements[] = "javascript;";
		$patterns[]     = "/\ba{$c}b{$c}o{$c}u{$c}t{$c}[\s]*:/si";
		$replacements[] = "about;";
		$patterns[]     = "/\bx{$c}s{$c}s{$c}[\s]*:/si";
		$replacements[] = "xss;";

		$text = preg_replace($patterns, $replacements, $text);

		return $text;
	}


	/**
	* Searches text for unwanted tags and removes them
	* @param string $text String to purify
	* @return string $text The purified text
	*/
	function stopXSS($text) {
		if (!is_array($text)) {
			$text = preg_replace("/\(\)/si", "", $text);
			$text = strip_tags($text);
			$text = str_replace(array("\"",">","<","\\"), "", $text);
		} else {
			foreach($text as $k=>$t) {
				if (is_array($t)) {
					StopXSS($t);
				} else {
					$t = preg_replace("/\(\)/si", "", $t);
					$t = strip_tags($t);
					$t = str_replace(array("\"",">","<","\\"), "", $t);
					$text[$k] = $t;
				}
			}
		}
		return $text;
	}


	/**
	* Filters textarea form data in DB for display
	* @param   string  $text
	* @param   bool    $html   allow html?
	* @param   bool    $smiley allow smileys?
	* @param   bool    $br     convert linebreaks?
	* @param   bool    $xss    allow filtering XSS scripts?
	* @return  string
	**/
	function displayText($text, $encode = 'UTF-8', $entities = 0, $decode_entities = 1, $html = 0, $br = 0, $clickable = 0, $xss = 1) {

		// Trim text
		$text = $this->sTrim($text);
	
		if ($entities != 0) {
			// Convert all applicable characters to HTML entities
			$text = $this->htmlEntities($text, $encode);
		}

		if ($decode_entities != 0) {
			// Convert all HTML entities to their applicable characters
			$text = $this->htmlEntitiesDecode($text, $encode);
		}

		if ($html != 0) {
			// html not allowed
			$text = $this->htmlSpecialChars($text, $encode);
		}

		if ($br != 0) {
			$text = $this->nl2Br($text);
		}

		if ($clickable != 0) {
			$text = $this->makeClickable($text);
		}

		if ($xss != 0) {
			$text = $this->filterXss($text);
		}

		$text = $this->stripSlashesGPC($text);

		return $text;
	}
	
	
	/**
	* Filters textarea form data in DB for display
	* @param   string  $text
	* @param   string  $lang   string to search
	* @return  string
	*
	*				Supported charsets
	* ------------------------------------------------------------------------------
	* Charset  	Aliases  			Description
	* ------------------------------------------------------------------------------
	* ISO-8859-1  	ISO8859-1  			Western European, Latin-1
	* ISO-8859-15 	ISO8859-15 			Western European, Latin-9. Adds the Euro sign, French and Finnish letters missing in Latin-1(ISO-8859-1).
	* UTF-8 					ASCII compatible multi-byte 8-bit Unicode.
	* cp866 	ibm866, 866 			DOS-specific Cyrillic charset. This charset is supported in 4.3.2.
	* cp1251 	Windows-1251, win-1251, 1251 	Windows-specific Cyrillic charset. This charset is supported in 4.3.2.
	* cp1252 	Windows-1252, 1252 		Windows specific charset for Western European.
	* KOI8-R 	koi8-ru, koi8r			Russian. This charset is supported in 4.3.2.
	* BIG5		950				Traditional Chinese, mainly used in Taiwan.
	* GB2312 	936				Simplified Chinese, national standard character set.
	* BIG5-HKSCS					Big5 with Hong Kong extensions, Traditional Chinese.
	* Shift_JIS 	SJIS, 932			Japanese
	* EUC-JP 	EUCJP				Japanese
	* ------------------------------------------------------------------------------
	**/
	function displayLang($string, $lang = "fr", $encode = "UTF-8") {
		// Show the language session (fr OR en OR ...)
		$string = $this->htmlEntitiesDecode($string, $encode);
		$string = $this->stripSlashesGPC($string);

		if (preg_match('#\[' . $lang . '\](.*)\[/' . $lang . ']#Us', $string, $match)) {
			$text = $match[1];
		} else {
			$text = $string;
		}

		return $text;
	}

}
