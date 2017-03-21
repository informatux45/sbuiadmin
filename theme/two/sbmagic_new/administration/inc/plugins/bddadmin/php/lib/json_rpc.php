<?php
	
	$local = dirname(__FILE__) . '/';
  require_once($local . 'rpc.php');
  require_once($local . 'json.php');
  //require_once($local . 'des.php');
	
	function turboJsonRpc(&$inRpc, $inDecode, $inEncode)
	{
		// get input
		if ($_SERVER['REQUEST_METHOD'] != 'POST') 
		{
			echo '"PHP: Invalid HTTP request method: '.$_SERVER['REQUEST_METHOD'].'"';
			exit;
		}
		//
		//$input = @$GLOBALS['HTTP_RAW_POST_DATA'];
		$input = file_get_contents("php://input");
		//
		//echo "[".$input."]";
		//
		// ... decrypt here ... 
		//if (substr($input, 0, 3) == 'DES')
		//	$input = des('pickapeckofpickledpeppers', hexToString($input), 0, NULL);
		//
		$inRpc->Codec = new Services_JSON($inDecode, $inEncode);
		$inRpc->dispatch($input);
	}
	
?>