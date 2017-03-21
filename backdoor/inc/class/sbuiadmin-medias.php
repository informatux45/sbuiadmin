<?php
/** *****************************************************************************
 *                     INFORMATUX medias class (UTF8)                           *
 * ******************************************************************************
 * @author     Patrice BOUTHIER <contact[at]informatux.com>                     *
 * @copyright  1996-2016 INFORMATUX                                             *
 * @link       http://www.informatux.com/                                       *
 * @since      1.0                                                              *
 * @version    CVS: 1.8                                                         *
 * ---------------------------------------------------------------------------- *
 * Copyright (c) 2011, INFORMATUX Solutions and web development                 *
 * All rights reserved.                                                         *
 ********************************************************************************
 *
 *              UTILISATION DE LA CLASSE SCANDIR
 *
 * -------------------------------------------------------------------
 * Scan a single directory for all files, no sub-directories
 * CODE : $files = scanDir::scan('../img-A');
 * -------------------------------------------------------------------
 *
 * -------------------------------------------------------------------
 * Scan multiple directories for all files, no sub-dirs
 * CODE :
 * $dirs = array('../img-A','D:\folder2','C:\Other');
 * $files = scanDir::scan($dirs);
 * -------------------------------------------------------------------
 *
 * -------------------------------------------------------------------
 * Scan multiple directories for files with provided file extension,
 * no sub-dirs
 * CODE : $files = scanDir::scan($dirs, "jpg");
 * -------------------------------------------------------------------
 * 
 * -------------------------------------------------------------------
 * or with an array of extensions
 * CODE :
 * $file_ext = array("jpg","bmp","png");
 * $files = scanDir::scan($dirs, $file_ext);
 * -------------------------------------------------------------------
 * 
 * -------------------------------------------------------------------
 * Scan multiple directories for files with any extension,
 * include files in recursive sub-folders
 * CODE : $files = scanDir::scan($dirs, false, true);
 * -------------------------------------------------------------------
 *
 * -------------------------------------------------------------------
 * Multiple dirs, with specified extensions, include sub-dir files
 * CODE : $files = scanDir::scan($dirs, $file_ext, true);
 * 
 * **************************************************************** */


// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBMAGIC_PATH') or die('Are you crazy!');


class medias {
    static private $directories, $files, $ext_filter, $recursive;

// ----------------------------------------------------------------------------------------------
    // scan(dirpath::string|array, extensions::string|array, recursive::true|false)
    static public function scan(){
        // Initialize defaults
        self::$recursive = false;
        self::$directories = array();
        self::$files = array();
        self::$ext_filter = false;

        // Check we have minimum parameters
        if(!$args = func_get_args()){
            die("Must provide a path string or array of path strings");
        }
        if(gettype($args[0]) != "string" && gettype($args[0]) != "array"){
            die("Must provide a path string or array of path strings");
        }

        // Check if recursive scan | default action: no sub-directories
        if(isset($args[2]) && $args[2] == true){self::$recursive = true;}

        // Was a filter on file extensions included? | default action: return all file types
        if(isset($args[1])){
            if(gettype($args[1]) == "array"){self::$ext_filter = array_map('strtolower', $args[1]);}
            else
            if(gettype($args[1]) == "string"){self::$ext_filter[] = strtolower($args[1]);}
        }

        // Grab path(s)
        self::verifyPaths($args[0]);
        return self::$files;
    }

    static private function verifyPaths($paths){
        $path_errors = array();
        //if(gettype($paths) == "string"){$paths = array($paths);}
        if (!is_array($paths))
            $paths = array($paths);
        
        foreach($paths as $path){
            if(is_dir($path)){
                self::$directories[] = $path;
                $dirContents = self::find_contents($path);
            } else {
                $path_errors[] = $path;
            }
        }

        if($path_errors){echo "The following directories do not exists<br />";die(var_dump($path_errors));}
    }

    // This is how we scan directories
    static private function find_contents($dir){
        $result = array();
        $root = scandir($dir);
        foreach($root as $value){
            if($value === '.' || $value === '..') {continue;}
            if(is_file($dir.DIRECTORY_SEPARATOR.$value)){
                if(!self::$ext_filter || in_array(strtolower(pathinfo($dir.DIRECTORY_SEPARATOR.$value, PATHINFO_EXTENSION)), self::$ext_filter)){
                    self::$files[] = $result[] = $dir.DIRECTORY_SEPARATOR.$value;
                }
                continue;
            }
            if(self::$recursive){
                foreach(self::find_contents($dir.DIRECTORY_SEPARATOR.$value) as $value) {
                    self::$files[] = $result[] = $value;
                }
            }
        }
        // Return required for recursive search
        return $result;
    }
}

?>