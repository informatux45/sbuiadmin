<?php
	define("TURBO_LIB_PATH", 'lib/');
  require_once(TURBO_LIB_PATH . 'json_rpc.php');
  require_once(TURBO_LIB_PATH . 'secure_rpc.php');
  require_once(TURBO_LIB_PATH . 'authenticator.php');

	class testService 
	{
		function hello_world()
		{
			return 'Hello World';
		}
	};
	
	// create credentialling authority
	$auth = new turboAuthenticator();
	
	// create rpc sink
	$rpc = &new turboSecureRpc($auth);
	$rpc->addTarget(NOBODY_ROLE, new testService());
	
	// process turbo-json-rpc request
	turboJsonRpc($rpc);
?>