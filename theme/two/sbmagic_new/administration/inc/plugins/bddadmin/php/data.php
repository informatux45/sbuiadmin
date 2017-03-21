<?php

	$local = dirname(__FILE__) . '/';
	require_once($local . 'adodb/adodb.inc.php');
	require_once($local . 'config.php');
	require_once($local . 'sql_parse.php');

	define("ID_MACRO", "%id");
	
	function bq($inCode) 
	{ 
		return "`$inCode`"; 
	}

	function hex($s)
	{
		$l = strlen($s);
		for ($i=0; $i < $l; $i++)
			echo '['.bin2hex($s{$i}).']';
	}

	function turboGetConnection($inDb = '')
	{
		$data = turboGetConnectData();
		//
		if ($inDb <> '')
			$data['db'] = $inDb;
		if (!@$data['type'])
			$data['type'] = 'mysql';
		$conn = &ADONewConnection($data['type']);	
		$conn->Connect($data['server'], $data['user'], $data['password'], $inDb);   
   	$conn->SetFetchMode(ADODB_FETCH_NUM); 
		//		
		$conn->isMySql = (substr($data['type'], 0, 5) == 'mysql');
		if ($conn->isMySql)
		{
			switch ($data['type'])
			{
				case 'mysqli':
					$conn->MySqlVer = floatval(substr(mysqli_get_server_info($conn->_connectionID), 0, 3));
					break;
				default:
					$conn->MySqlVer = floatval(substr(mysql_get_server_info(), 0, 3));
			}
		} 
		//
		// utf-8 mode is default on systems >= 4.1 
		if ($conn->isMySql && $conn->MySqlVer >= 4.1)
		{
			if (!@$data['names'])
				$data['names'] = 'utf8';
			//if (@$data['names'])
			//	turboRpcDebug("SET NAMES '" . $data['names'] . "'");
			if (@$data['names'])
				$conn->Execute("SET NAMES '" . $data['names'] . "'");
		}
		//		
		return $conn;
	}
	
	function splitSql(&$inSql, $inEol="\n")
	{
		$cmd = '';
		$cmds = Array();
		$lines = explode($inEol, $inSql);
		foreach ($lines as $line)
		{
			$line = trim($line);
			$cmd .= $line;
			$l = strlen($line) - 1;
			if ($l >= 0 && $line[$l] == ';')
			{
				$cmds[] = $cmd;
				$cmd = '';
			}
		}
		return $cmds;		
	}
	
	// FIXME: moved out of class; needed in sql_io.php. (Separate utility functions)
	function get_set_columns(&$inRecordSet)
	{	
		$result = array();
		for ($i = 0; $i < $inRecordSet->FieldCount(); $i++)
		{
			$field = $inRecordSet->FetchField($i);
			$result[$field->name] = $field;
		}	
		return $result;
	}
		
	// FIXME: moved out of class; needed in sql_io.php. (Separate utility functions)
	function set_to_schema(&$inRecordSet)
	{
		if (!method_exists($inRecordSet, 'FieldCount') || $inRecordSet->FieldCount() == 0)
			return array();
		else	
			return array(
				'columns' => get_set_columns($inRecordSet),
				'count' => $inRecordSet->RecordCount()
			);
	}
	
	class turboData
	{
		var $db;
		var $useBq;
		
		function connect($inDatabase = '')
		{
			if ($this->db) 
				$this->db->Close();
			$this->db = turboGetConnection($inDatabase);
			$this->useBq = $this->db->isMySql;
			if (!$this->db)
				user_error('data: could not connect to database server');
		}

		function is_mysql()
		{
			$this->connect();
			return $this->db->isMySql;
		}
		
		function bq($inStr)
		{
			if ($this->useBq)
				return "`$inStr`"; 
			else
				return $inStr;
		}
		
		function get_server_name()
		{
			$data = turboGetConnectData();
			return $data['server'];
		}

		function &check_set(&$inRecordSet)
		{
			if (!$inRecordSet) 
				user_error($this->db->ErrorMsg());
			else		
				return $inRecordSet;
		}
		
		function set_to_array(&$inRecordSet)
		{
			if ($inRecordSet && method_exists($inRecordSet, 'GetArray'))
				return $inRecordSet->GetArray();
			else
				return array();
		}
		
		function set_to_array_limit(&$inRecordSet, $inLimit = false)
		{
			if ($inRecordSet && method_exists($inRecordSet, 'GetArray'))
				return $inLimit ? $inRecordSet->GetArray($inLimit) : $inRecordSet->GetArray();
			else
				return array();
		}
		
		function list_servers()
		{
			return array($this->get_server_name());
		}
		
		function list_databases()
		{
			$this->connect();
			$dbs = $this->db->MetaDatabases();
			if ($this->db->ErrorNo()) 
				user_error($this->db->ErrorMsg());
			return $dbs;
		}
		
		function list_tables($inDatabase = '', $ttype = false, $showSchema = false, $mask=false)
		{
			$this->connect($inDatabase);	
			$tables = $this->db->MetaTables($ttype, $showSchema, $mask);
			if ($this->db->ErrorNo()) 
				user_error($this->db->ErrorMsg());
			return $tables;
		}
		
		function list_all_tables()
		{
			$result = array();
			$dbs = $this->list_databases();
			foreach ($dbs as $db) 
				$result[$db] = $this->list_tables($db);
			return array($this->get_server_name() => $result);
		}
		
		function list_columns($inTable, $inDatabase = '')
		{
			$this->connect($inDatabase);	
			return $this->db->MetaColumns($this->bq($inTable));
		}
		
		function get_row_count($inTable, $inDatabase = '')
		{
			if (!$this->db)
				$this->connect($inDatabase);	
			$sql = 'SELECT COUNT(*) FROM ' . $this->bq($inTable);
			$set = $this->db->Execute($sql);			
			if ($this->db->ErrorNo()) 
				user_error($this->db->ErrorMsg() . ': ' . $sql);
			if ($set && !$set->EOF)
				return $set->fields[0];
			else
				user_error('no count');
		}

		function get_table_schema($inTable, $inDatabase = '', $inOwner = false, $inPrimary = false)
		{
			$this->connect($inDatabase);	
			$table = $this->bq($inTable); 
			return array(
				'columns' => $this->db->MetaColumns($table),
				'keys' => $this->db->MetaPrimaryKeys($table, $inOwner),
				'indexes' => $this->db->MetaIndexes($table, $inPrimary, $inOwner),
				'foreignKeys' => $this->db->MetaForeignKeys($table, $inOwner),
				'count' => $this->get_row_count($inTable)
			);				
		}
		
		function _execute_sql($inQuery)
		{
			$set = $this->db->Execute($inQuery);
			if ($this->db->ErrorNo()) 
				user_error($this->db->ErrorMsg());
			return $this->set_to_array($set);
		}
		
		function execute_sql($inQuery, $inDatabase = '')
		{
			$this->connect($inDatabase);
			return $this->_execute_sql($inQuery);
		}
		
		function execute_untrusted_sql_reflect($inQuery, $inStart = 0, $inLimit = false, $inDatabase = '')
		{
			$this->connect($inDatabase);	
			global $sql_parser;
			$cmds = $sql_parser->split_sql($inQuery);
			$result = array('sql' => '', 'data' => '', 'schema' => '', 'affectedRows' => 0);
			foreach ($cmds as $cmd)
			{
				if (!$cmd)
					continue;
				if ($sql_parser->is_bad_command($cmd))
					user_error('Disallowed SQL command not processed: ' . $cmd);
				$result = $this->_execute_sql_command_reflect($cmd, $inStart, $inLimit);	
			}	
			return $result;
		}
		
		function _execute_sql_command_reflect($inQuery, $inStart = 0, $inLimit = false)
		{
			if ($inQuery != '')
			{
				$set = $this->db->Execute($inQuery);
				if ($this->db->ErrorNo()) 
					user_error($this->db->ErrorMsg());
				if ($set)
				{
					// move cursor to first page row	
					if ($set->RecordCount() > $inStart)
						$set->Move($inStart);	
					$data = $this->set_to_array_limit($set, $inLimit);
					// if no results, return rows affected	
					$affected_rows = (method_exists($set, 'Affected_Rows') ? $set->Affected_Rows() : '');
					return array(
						'sql' => $inQuery,
						'data' => $data,
						'schema' => set_to_schema($set),
						'affectedRows' => $affected_rows
					);
				}
			}	
			$result = array('sql' => '', 'data' => '', 'schema' => '', 'affectedRows' => 0);	
		}
		
		function _execute_sql_queue($inSql)
		{
			//$cmds = &splitSql($inSql);
			$cmds = $inSql;
			$results = array();
			foreach ($cmds as $cmd) 
			{
				$set = &$this->db->Execute($cmd);
				if ($this->db->ErrorNo()) 
					user_error($this->db->ErrorMsg());
				else
					$results[] = $set;	
			}		
			return $results;
		}		
		
		function execute_sql_queue($inSql, $inDatabase = '')
		{
			$this->connect($inDatabase);	
			return $this->_execute_sql_queue($inSql);
		}	
		
		function select_limit($inQuery, $inLimit, $inOffset, $inDatabase = '')
		{
			$this->connect($inDatabase);	
			$set = $this->db->SelectLimit($inQuery, $inLimit, $inOffset);
			if ($this->db->ErrorNo()) 
				user_error($this->db->ErrorMsg());
			return $this->set_to_array($set);
		}
		
		function update_reflect($inUpdateQuery, $inFetchQuery, $inDatabase = '')
		{
			$this->connect($inDatabase);	
			$editOk = $this->db->Execute($inUpdateQuery);
			if (!$editOk)
				user_error($this->db->ErrorMsg());
			$affectedRows = $this->db->Affected_Rows();	
			// process auto increment.
			if (strpos($inFetchQuery, ID_MACRO) !== false)
			{
				$insert_id = $this->db->Insert_ID();
				if (!$insert_id)
					user_error($this->db->ErrorMsg());	
				$inFetchQuery = str_replace(ID_MACRO, $insert_id, $inFetchQuery);
			}
			$result = $this->_execute_sql($inFetchQuery);			
			// affected rows added as first return element of array
			array_push($result, $affectedRows);
			return $result;
		}

		// returns false if fetchSql returned any results
		function delete_row($inDeleteSql, $inFetchSql, $inDatabase = '')
		{
			$this->connect($inDatabase);	
			$this->_execute_sql($inDeleteSql);
			$rs = $this->_execute_sql($inFetchSql);			
			return (!$rs || $rs->RecordCount() == 0);
			/*
			$this->execute_sql_queue($inDeleteSql, $inDatabase);
			$results = $this->execute_sql_queue($inFetchQuery, $inDatabase);			
			foreach ($results as $rs)
				if ($rs->RecordCount() > 0)
					return false;
			return true;
			*/
		}
		
		function delete_queue($inQueue, $inDatabase = '')
		{
			$this->connect($inDatabase);	
			$deleted = 0;
			$error = '';
			foreach ($inQueue as $cmd) 
			{
				$set = &$this->db->Execute($cmd);
				if ($this->db->ErrorNo()) 
				{
					$error = $this->db->ErrorMsg();
					break;
				}
				else
					$deleted++;
			}		
			return Array("deleted" => $deleted, "error" => $error);
		}		
		
		// returns false if fetchSql returned any results
		function delete_rows($inDeleteSql, $inFetchSql, $inDatabase = '')
		{
			$this->connect($inDatabase);	
			$this->_execute_sql_queue($inDeleteSql);
			$results = $this->_execute_sql_queue($inFetchSql);			
			foreach ($results as $rs)
				if ($rs->RecordCount() > 0)
					return false;
			return true;
		}
	}
	/*
	//$sql = file_get_contents('x_testing_database.sql');
	$sql = "USE testing_database; INSERT INTO baseball (Name) VALUES('Tony'); SELECT * FROM testing_database.baseball LIMIT 0, 30";
	$test = new TurboData();
	echo('<pre>');
	print_r($test->execute_untrusted_sql_reflect($sql, 0, false, ''));
	echo('</pre>');
	*/
?>
