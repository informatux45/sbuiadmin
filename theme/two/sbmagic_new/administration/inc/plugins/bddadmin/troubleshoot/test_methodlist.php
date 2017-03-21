<?php
	define("TURBO_LIB_PATH", "../php/lib/");
  require_once(TURBO_LIB_PATH . 'json_rpc.php');
  require_once(TURBO_LIB_PATH . 'secure_rpc.php');
  require_once(TURBO_LIB_PATH . 'authenticator.php');
  require_once('../php/data.php');
	
	// create credentialling authority
	$auth = new turboAuthenticator();
	
	// create rpc sink
	$rpc = &new turboSecureRpc($auth);
	$rpc->addTarget(NOBODY_ROLE, new turboLoginProxy($auth));
	$rpc->addTarget(NOBODY_ROLE, new turboData());

	$result = $rpc->getMethodList();	
	
	echo "Service functions:";
	echo "<pre>";	
	print_r($result);
	echo "</pre>";	
?>