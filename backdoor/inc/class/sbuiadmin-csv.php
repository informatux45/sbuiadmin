<?php
/** *****************************************************************************
*                      INFORMATUX CSV class (UTF8)                              *
/** *****************************************************************************
* @author     Patrice BOUTHIER <contact[at]informatux.com>                      *
* @copyright  1997-2021 INFORMATUX                                              *
* @link       https://www.informatux.com/                                       *
* @since      1.0                                                               *
* @version    CVS: 1.8                                                          *
* ----------------------------------------------------------------------------- *
* Copyright (c) 2021, INFORMATUX Solutions and web development                  *
* All rights reserved.                                                          *
***************************************************************************** **/

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');
 
/**
 * PHP CSV Class
 * ----------------------------
 * Usage to get ARRAY by record AND key name by Header of table (ID,NAME,JOB,COVER,...)
 * $file = $csv->file("$csv_path", true, ";");
 * $data = $csv->get();
 * ----------------------------
 * Usage to get ARRAY by line
 * $file = $csv->file("$csv_path", true);
 * $data = $csv->get();
 * ----------------------------
 * Usage to get ARRAY by record AND key name by INT (0,1,2,3,...)
 * $file = $csv->file("$csv_path", true);
 * $data = $csv->get();
 * ----------------------------
 */
class csv extends sanitize {
	private $file;
    private $fp;
    private $parse_header;
    private $header;
    private $delimiter;
    private $length;
	var $mappings = array(); 
    //--------------------------------------------------------------------
    function file($file_name, $parse_header = false, $delimiter = "\t", $length = 8000) {
		$this->file         = $file_name;
        $this->fp           = (file_exists($file_name)) ? fopen($file_name, "r") : false;
        $this->parse_header = $parse_header;
        $this->delimiter    = $delimiter;
        $this->length       = $length;
        $this->lines        = $lines;
		
		// Check if file exists
		if ($this->fp !== false) {
			if ($this->parse_header) {
			   $this->header = fgetcsv($this->fp, $this->length, $this->delimiter);
			}
		} else {
			return false;
		}

    }
    //--------------------------------------------------------------------
    function __destruct() {
        if ($this->fp) {
            fclose($this->fp);
        }
    }
	function count($add = 0) {
		if ($this->fp !== false) {
			// Create new file reference
			$file = new SplFileObject($this->file, 'r');
			// Try to seek to the highest INT PHP can handle
			$file->seek(PHP_INT_MAX);
			// Handle the highest line in the file
			$count = $file->key() + intval($add);
			// Check if add header table line
			$count = ($this->parse_header) ? $count - 1 : $count;
			// Go back to the start of the file
			$file->rewind();
			return $count;
		} else {
			return 'File not exists';
		}
	}
	// -------------------------------------------------------------------
    function get($max_lines = 0) {

		if ($this->fp !== false) {

			//if $max_lines is set to 0, then get all the data
			$data = array();
	
			if ($max_lines > 0) {
				$line_count = 0;
			} else {
				$line_count = -1; // so loop limit is ignored
			}
				
			while ($line_count < $max_lines && ($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== FALSE) {
				if ($this->parse_header) {
					foreach ($this->header as $i => $heading_i) {
						$row_new[$heading_i] = $row[$i];
					}
					$data[] = $row_new;
				} else {
					$data[] = $row;
				}
	
				if ($max_lines > 0)
					$line_count++;
			}
			return $data;
		
		} else {
			return false;
		}
    }
	//--------------------------------------------------------------------
	function parse_file($convert_from = false, $convert_to = false, $convert_key = false) {
		
		if ($this->fp !== false) {
			
			$id   = fopen($this->file, "r");
			$data = fgetcsv($id, filesize($this->file)); /* This will get us the main column names */
		
			if (!$this->mappings) $this->mappings = $data;
		
			while ($data = fgetcsv($id, filesize($this->file))) {
				if ($data[0]) {
					foreach($data as $key => $value) {
						// Check if $value must be converted
						// From ex: ISO-8859-15
						// To ex: UTF-8
						if ($convert_from) {
							$new_value = iconv($convert_from, $convert_to, $value);
							$new_key   = ($convert_key) ? iconv($convert_from, $convert_to, $this->mappings[$key]) : $this->mappings[$key];
						} else {
							$new_value = $value;
							$new_key   = $this->mappings[$key];
						}
						$converted_data[$new_key] = addslashes($new_value);
					}
					$table[] = $converted_data; /* put each line into */
				}                               /* its own entry in    */
			}                                   /* the $table array    */
			fclose($id); //close file
			return $table;
		
		} else {
			return array();
		}
	}
    //--------------------------------------------------------------------
	// --- Multisort array by key
	function sort(array $array, $on, $order = SORT_ASC) {
		$new_array = array();
		$sortable_array = array();
	
		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}
	
			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
				break;
				case SORT_DESC:
					arsort($sortable_array);
				break;
			}
	
			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}
	
		return $new_array;
	}
	//--------------------------------------------------------------------
	/**
	 * Groups an array by a given key.
	 *
	 * Groups an array into arrays by a given key, or set of keys, shared between all array members.
	 *
	 * Based on {@author Jake Zatecky}'s {@link https://github.com/jakezatecky/array_group_by array_group_by()} function.
	 * This variant allows $key to be closures.
	 *
	 * @param array $array   The array to have grouping performed on.
	 * @param mixed $key,... The key to group or split by. Can be a _string_,
	 *                       an _integer_, a _float_, or a _callable_.
	 *
	 *                       If the key is a callback, it must return
	 *                       a valid key from the array.
	 *
	 *                       If the key is _NULL_, the iterated element is skipped.
	 *
	 *                       ```
	 *                       string|int callback ( mixed $item )
	 *                       ```
	 *
	 * @return array|null Returns a multidimensional array or `null` if `$key` is invalid.
	 */
	function group_by(array $array, $key) {
		
		if ($this->fp !== false) {
		
			if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key) ) {
				trigger_error('group_by(): The key should be a string, an integer, or a callback', E_USER_ERROR);
				return null;
			}
	
			$func = (!is_string($key) && is_callable($key) ? $key : null);
			$_key = $key;
	
			// Load the new array, splitting by the target key
			$grouped = [];
			foreach ($array as $value) {
				$key = null;
	
				if (is_callable($func)) {
					$key = call_user_func($func, $value);
				} elseif (is_object($value) && property_exists($value, $_key)) {
					$key = $value->{$_key};
				} elseif (isset($value[$_key])) {
					$key = $value[$_key];
				}
	
				if ($key === null) {
					continue;
				}
	
				$grouped[$key][] = $value;
			}
	
			// Recursively build a nested grouping if more parameters are supplied
			// Each grouped array value is grouped according to the next sequential key
			if (func_num_args() > 2) {
				$args = func_get_args();
	
				foreach ($grouped as $key => $value) {
					$params = array_merge([ $value ], array_slice($args, 2, func_num_args()));
					//$grouped[$key] = call_user_func_array('array_group_by', $params);
					$grouped[$key] = call_user_func_array($this->group_by, $params);
				}
			}

			return $grouped;
		
		} else {
			return false;
		}
	}
    //--------------------------------------------------------------------
	/**
	 * Analyse file CSV
	 * @param	$file 					Path of file
	 * @param	$capture_limit_in_kb	Limit of kb
	 * --------------------------------------------
	 * Usage:
	 * $Array = analyse_file('/www/files/file.csv', 10);
	 * 
	 * Example usable parts
	 * $Array['delimiter']['value'] => ,
	 * $Array['line_ending']['value'] => \r\n
	 * --------------------------------------------
	 * Full function output:
	 * Array
	 * (
	 *     [peak_mem] => Array
	 *         (
	 *             [start] => 786432
	 *             [end] => 786432
	 *         )
	 * 
	 *     [line_ending] => Array
	 *         (
	 *             [results] => Array
	 *                 (
	 *                     [nr] => 0
	 *                     [r] => 4
	 *                     [n] => 4
	 *                     [rn] => 4
	 *                 )
	 * 
	 *             [count] => 4
	 *             [key] => rn
	 *             [value] =>
	 * 
	 *         )
	 * 
	 *     [lines] => Array
	 *         (
	 *             [count] => 4
	 *             [length] => 94
	 *         )
	 * 
	 *     [delimiter] => Array
	 *         (
	 *             [results] => Array
	 *                 (
	 *                     [colon] => 0
	 *                     [semicolon] => 0
	 *                     [pipe] => 0
	 *                     [tab] => 1
	 *                     [comma] => 17
	 *                 )
	 * 
	 *             [count] => 17
	 *             [key] => comma
	 *             [value] => ,
	 *         )
	 * 
	 *     [read_kb] => 10
	 * )
	 */
	function analyse($file, $capture_limit_in_kb = 10) {
		
		$output = array();
		
		if ($this->fp !== false) {
			// capture starting memory usage
			$output['peak_mem']['start'] = memory_get_peak_usage(true);
		
			// log the limit how much of the file was sampled (in Kb)
			$output['read_kb'] = $capture_limit_in_kb;
		   
			// read in file
			$fh       = $this->fp;
			$contents = fread($fh, ($capture_limit_in_kb * 1024)); // in KB
			fclose($fh);
		   
			// specify allowed field delimiters
			$delimiters = array(
				'comma'     => ',',
				'semicolon' => ';',
				'tab'       => "\t",
				'pipe'      => '|',
				'colon'     => ':'
			);
		   
			// specify allowed line endings
			$line_endings = array(
				'rn' => "\r\n",
				'n'  => "\n",
				'r'  => "\r",
				'nr' => "\n\r"
			);
		   
			// loop and count each line ending instance
			foreach ($line_endings as $key => $value) {
				$line_result[$key] = substr_count($contents, $value);
			}
		   
			// sort by largest array value
			asort($line_result);
		   
			// log to output array
			$output['line_ending']['results'] = $line_result;
			$output['line_ending']['count']   = end($line_result);
			$output['line_ending']['key']     = key($line_result);
			$output['line_ending']['value']   = $line_endings[$output['line_ending']['key']];
			$lines = explode($output['line_ending']['value'], $contents);
		   
			// remove last line of array, as this maybe incomplete?
			array_pop($lines);
		   
			// create a string from the legal lines
			$complete_lines = implode(' ', $lines);
		   
			// log statistics to output array
			$output['lines']['count']  = count($lines);
			$output['lines']['length'] = strlen($complete_lines);
		   
			// loop and count each delimiter instance
			foreach ($delimiters as $delimiter_key => $delimiter) {
				$delimiter_result[$delimiter_key] = substr_count($complete_lines, $delimiter);
			}
		   
			// sort by largest array value
			asort($delimiter_result);
		   
			// log statistics to output array with largest counts as the value
			$output['delimiter']['results'] = $delimiter_result;
			$output['delimiter']['count']   = end($delimiter_result);
			$output['delimiter']['key']     = key($delimiter_result);
			$output['delimiter']['value']   = $delimiters[$output['delimiter']['key']];
		   
			// capture ending memory usage
			$output['peak_mem']['end'] = memory_get_peak_usage(true);
			
		}
		
		return $output;
		
	}

}

?>
