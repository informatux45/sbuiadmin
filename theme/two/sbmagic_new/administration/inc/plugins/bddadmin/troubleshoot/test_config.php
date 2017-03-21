<?php
  require_once('../php/data.php');

	error_reporting(E_ALL);

	// create a data object	
	echo "Creating data object...";
	$data = new turboData();
	echo "ok<br>";
	
	// get database list
	echo "Listing databases...";
	$dbs = $data->list_databases();
	echo "ok<br><br>";
	
	// send output
	echo "<pre>";	
	print_r($dbs);
	echo "</pre>";	
?>