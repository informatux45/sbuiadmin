<?php
	
	$local = dirname(__FILE__) . '/';
  require_once($local . 'rpc.php');
  require_once($local . 'json.php');
  require_once($local . 'des.php');
	
	function turboJsonRpc(&$inRpc)
	{
		// get input
		if ($_SERVER['REQUEST_METHOD'] != 'POST') 
		{
			echo '"PHP: Invalid HTTP request method: '.$_SERVER['REQUEST_METHOD'].'"';
			exit;
		}
		//$input = @$GLOBALS['HTTP_RAW_POST_DATA'];
		$input = file_get_contents("php://input");
		//
    if(function_exists('mb_convert_encoding'))
			echo 'mb_convert_encoding available --';
		else
			echo 'WARNING: mb_convert_encoding not available --';
		//
		echo "[".$input."] -- ";
		//
		$id = substr($input, -2, 1);
		//
		// ... decrypt here ... 
		if (substr($input, 0, 3) == 'DES')
			$input = des('pickapeckofpickledpeppers', hexToString($input), 0, NULL);
		//
		$inRpc->Codec = new JSON();
		echo "created JSON object...";
		if ($id == 2)
			exit;
		$inRpc->dispatch($input);
	}
	
?>