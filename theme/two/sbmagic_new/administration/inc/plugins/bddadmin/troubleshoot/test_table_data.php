
<?php
  require_once('../php/data.php');
  
  function inputs_ok()
  {
  	return ($_POST && $_POST['table'] && $_POST['db']);
  }

	if (!inputs_ok())
	{
?>

<form id="form1" name="form1" method="post" action="">
  <strong>Test: Show DB Table data via PHP</strong>
  <table border="0">
    <tr>
      <td>Database:</td>
      <td align="right"><input type="text" name="db" /></td>
    </tr>
    <tr>
      <td>Table: </td>
      <td align="right"><input type="text" name="table" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="right"><input type="submit" name="Submit" value="Submit" /></td>
    </tr>
  </table>
</form>

<?	
	}
	else
	{
?>
<form id="form1" name="form1" method="post" action="">
	<input type="submit" name="Submit" value="Try another database / table..." />
</form>	
<?
		error_reporting(E_ALL);
	
		// create a data object	
		echo "Creating data object...";
		$data = new turboData();
		echo "ok<br>";
		
		// get database list
		
		echo "Listing data...";
		$query = 'SELECT * FROM ' . bq($_POST['table']) . ' LIMIT 0, 30';
		$data = $data->execute_sql($query, $_POST['db']);
		echo "ok<br><br>";
		
		// send output
		echo "<pre>";	
		print_r($data);
		echo "</pre>";	
	}	
?>