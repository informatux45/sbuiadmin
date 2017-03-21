<?php
	error_reporting(E_ALL);
	
	require_once('turbolibs.php');
  require_once(TURBO_LIB_PATH . 'json.php');
  require_once(TURBO_LIB_PATH . 'rpc.php');
  require_once(TURBO_LIB_PATH . 'secure_rpc.php');
  require_once(TURBO_LIB_PATH . 'authenticator.php');
	require_once('config.php');
  require_once('data.php');
  require_once('file_io.php');
  require_once('sql_io.php');

	// get input
	if ($_SERVER['REQUEST_METHOD'] != 'POST') 
	{
		echo '"PHP: Invalid HTTP request method: '.$_SERVER['REQUEST_METHOD'].'"';
		exit;
	}
	
	// Determine character encodings
	$db = turboGetConnection();
	if ($db->isMySql && $db->MySqlVer < 4.1)
	//if (floatval(substr(mysql_get_server_info(), 0, 3)) < 4.1)
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
	
	// create credentialling authority
	$auth = new turboAuthenticator();
	
	// create rpc sink
	$rpc = &new turboSecureRpc($auth);
	$rpc->addTarget(NOBODY_ROLE, new turboLoginProxy($auth));
	$rpc->addTarget(NOBODY_ROLE, new turboData());
	$rpc->addTarget(NOBODY_ROLE, new turboFile());
	$rpc->addTarget(NOBODY_ROLE, new turboIo());
	$rpc->addTarget(NOBODY_ROLE, new turboSqlIo());
	
	//$input = @$GLOBALS['HTTP_RAW_POST_DATA'];
	$input = file_get_contents("php://input");
	//
	//echo "[".$input."]";
	//
	// set up response encoding 
  header("Content-Type: text/plain; charset=utf-8");
	$rpc->Codec = new Services_JSON($encoding);
	$rpc->dispatch($input);
?>