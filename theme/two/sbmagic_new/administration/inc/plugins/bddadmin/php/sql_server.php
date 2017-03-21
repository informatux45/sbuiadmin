<?php
	error_reporting(E_ALL);
	require_once('turbolibs.php');	
	require_once(TURBO_LIB_PATH . 'json.php');
	require_once('config.php');
  require_once('data.php');
  require_once('sql_io.php');

	// get input
	if ($_SERVER['REQUEST_METHOD'] != 'POST') 
	{
		echo '"PHP: Invalid HTTP request method: '.$_SERVER['REQUEST_METHOD'].'"';
		exit;
	}
	//
	// Determine character encodings
	$db = turboGetConnection();
	if ($db->isMySql && $db->MySqlVer < 4.1)
	{
		// mysql_client_encoding is (seemingly) always 'latin1' 
		// we could auto-detect if we access server's character_set value
		//$encoding = strtok(mysql_client_encoding(), "_");
		//turboRpcDebug("detected '$encoding' encoding");
		//if ($encoding != 'latin1')
		// $encoding = SERVICES_JSON_ISO_8859_1;
		//else if (!function_exists('mb_convert_encoding'))
		//{
		//	$encoding = SERVICES_JSON_ISO_8859_1;
		//	turboRpcDebug("mb_strings not installed, defaulting to ISO-8859-1 encoding");
		//}
		$encoding = SERVICES_JSON_ISO_8859_1;
	}
	else
		$encoding = SERVICES_JSON_UTF_8;
	//
	$config = turboGetConnectData();
	if (@$config["encoding"])
		$encoding = $config["encoding"];		
	//	
	$input = file_get_contents("php://input");
	$s = strpos($input, "|");
	$db = substr($input, 0, $s);
	$query = substr($input, $s+1);
	//	
	function sqlErrorHandler($errno, $errmsg, $filename, $linenum, $vars) 
	{
		global $error;
		//$msg = $errmsg . ($filename ? ", $filename" : '') . ($linenum ? ", $linenum" : '') . ($vars ? ", $vars" : '');
		$msg = $errmsg;
		if ($errno == E_WARNING || $errno == E_USER_ERROR || $errno == E_USER_WARNING || $errno == E_USER_NOTICE)
			$error = array('error' => $msg);
	}
	$old_error_handler = set_error_handler("sqlErrorHandler");

	$data = new TurboData();
	$json = new Services_JSON();

	$error = '';
	
	$result = $data->execute_untrusted_sql_reflect($query, 0, 1, $db);
	// send sql only if there is result data.
	if (is_array($result) && empty($result['data']))
		$result['sql'] = '';
	$result = array('result' => $result);
	
	header("Content-Type: text/plain; charset=utf-8");
	
	echo(!$error ? $json->encode($result) : $json->encode($error));
?>