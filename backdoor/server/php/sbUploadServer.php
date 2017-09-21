<?php
/**
 * Admin Startbootstrap
 * UPLOAD MEDIAS
 *
 * @link http://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */
 
// Include the uploader class
require_once '../../server/php/qqFileUploader.php';

// Get Settings
$sb_upload_config = file('../../inc/admin/settings.txt');

// File path
$sbfiles_medias_subdir = (isset($_REQUEST['subdir']) && $_REQUEST['subdir'] != '') ? '/' . rtrim($_REQUEST['subdir'], "/") : '';
$sbfiles_medias_dir = '../../' . trim($sb_upload_config[6]) . $sbfiles_medias_subdir;

$uploader = new qqFileUploader();

// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
$uploader->allowedExtensions = array();

// Specify max file size in bytes.
//$uploader->sizeLimit = 10 * 1024 * 1024;

// Specify the input name set in the javascript.
$uploader->inputName = 'qqfile';

// If you want to use resume feature for uploader, specify the folder to save parts.
$uploader->chunksFolder = 'chunks';

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
$result = $uploader->handleUpload($sbfiles_medias_dir);
// To save the upload with a specified name, set the second parameter.
//$result = $uploader->handleUpload($sbfiles_medias_dir, sbRewriteString($uploader->getUploadName()));

// To return a name used for uploaded file you can use the following line.
$result['uploadName'] = $uploader->getUploadName();


header("Content-Type: text/plain");
echo json_encode($result);
