<?php
	require_once "data.php";
	
	function &implodeFields($inDelim, &$inFields)
	{
		$field = reset($inFields);
		if ((list($key, $value) = each($inFields)) == false)
			return '';
		$result = $value;
		while (list($key, $value) = each($inFields))
			$result .= $inDelim . addslashes($value);
		return $result;			
	}
	
	class turboIo
	{
		var $Server;
		var $Db;
		var $Table;
		var $TableTarget;
		var $DbTarget;
		var $Eol;
		var $useBq;
		
		function init($inDb, $inDbTarget='', $inTable='', $inTableTarget='', $inEol = "\n")
		{
			$this->Db = $inDb;
			$this->Eol = $inEol;
			$this->DbTarget = ($inDbTarget <> '' ? $inDbTarget : $inDb);
			$this->Table = $inTable;
			$this->TableTarget = ($inTableTarget <> '' ? $inTableTarget : $inTable);
			$this->Server = &turboGetConnection($inDb);			
			$this->useBq = $this->Server->isMySql;
		}
		
		function bq($inStr)
		{
			if ($this->useBq)
				return "`$inStr`"; 
			else
				return $inStr;
		}
		
		function join_columns($inColumns)
		{
			return ($this->useBq) ? '`' . join('`, `', $inColumns) . '`' : join(', ', $inColumns);
		}
		
		function get_full_table_name()
		{
			return $this->bq($this->Db) . '.' . $this->bq($this->Table);
		}
		
		function get_full_target_name($inTablePrefix = '')
		{
			return $this->bq($this->Db) . '.' . $this->bq($this->TableTarget);
		}
		
		function get_table_data($inDb, $inTable, $inColumns = '*')
		{
			$this->init($inDb, '', $inTable);
			
			$metaColumns = $this->Server->MetaColumns($this->bq($inTable));

			$columns = array();
			foreach ($metaColumns as $metaColumn)
			{
				if (is_array($inColumns))
				{
					if (in_array($metaColumn->name, $inColumns))
						array_push($columns, $metaColumn);
				}
				else
					array_push($columns, $metaColumn);
				}
			if (is_array($inColumns))
				$inColumns = $this->join_columns($inColumns);
			//$query = 'SELECT ' . $inColumns . ' FROM ' . $this->get_full_table_name();
			$query = 'SELECT ' . $inColumns . ' FROM ' . $this->bq($inTable);
			$this->Server->SetFetchMode(ADODB_FETCH_NUM); 
			$rs = &$this->Server->Execute($query);
			if (!$rs) 
				user_error(mysql_error());
			return array('columns' => $columns, 'recordset' => $rs);	
		}
		
		function get_query_data($inDb, $inSql)
		{
			$this->init($inDb);
			$this->Server->SetFetchMode(ADODB_FETCH_NUM); 
			$rs = &$this->Server->Execute($inSql);
			if (!$rs) 
				user_error(mysql_error());
			$columns = get_set_columns($rs);	
			return array('columns' => $columns, 'recordset' => $rs);	
		}
		
		function get_html_output($inData, $inTargetColumns = '')
		{
			if (!$inData || !is_array($inData))
				return;
			$columns = $inData['columns'];
			$recordset = $inData['recordset'];
			$tab = chr(9);
			$export = '<table border="1" align="center" width="95%" cellspacing="0" bordercolor="black" cellpadding="4" style="border-collapse: collapse;">'
				. $this->Eol;
			// header
			$export .= $tab . '<tr>' . $this->Eol;
			$i = 0;
			foreach ($columns as $column)
			{
				$export_column = $inTargetColumns[$i] ? $inTargetColumns[$i] : $column->name;
				$export .= $tab . $tab . '<th>' . $export_column . '</th>' . $this->Eol;
				$i++;
			}	
			$export .= $tab . '</tr>' . $this->Eol;
			// data	
			while (!$recordset->EOF) 
			{
				$export .= $tab . "<tr>" . $this->Eol;
				$export .= $tab . $tab . "<td>";
				$export .= implodeFields("</td>" . $this->Eol . $tab . $tab . "<td>", $recordset->fields) . '</td>' . $this->Eol;
				$export .= $tab . "</tr>" . $this->Eol;
				$recordset->MoveNext();
			}
			$export .= '</table>' . $this->Eol;
			return $export;
		}
		
		function get_sv_output($inData, $inTargetColumns = '', $inSep = ', ')
		{
			if (!$inData || !is_array($inData))
				return;
			$columns = $inData['columns'];
			$recordset = $inData['recordset'];
			$q = '"';
			$export = '';
			$i = 0;
			foreach ($columns as $column)
			{
				$export_column = $inTargetColumns[$i] ? $inTargetColumns[$i] : $column->name;
				$export .= $q . $export_column . $q;
				$export .= $i < count($columns) - 1 ? $inSep : '';
				$i++;
			}	
			$export .= $this->Eol;
			while (!$recordset->EOF) 
			{
				$export .= $q . implodeFields($q . $inSep . $q, $recordset->fields) . $q . $this->Eol;
				$recordset->MoveNext();
			}
			return $export;
		}
		
		function get_query_sv($inDb, $inSql, $inSep = ', ')
		{
			$d = $this->get_query_data($inDb, $inSql);
			return $this->get_sv_output($d, '', $inSep);
		}
		
		function get_table_sv($inDb, $inTable, $inColumns = '*', $inTargetColumns = '', $inSep = ', ')
		{
			$d = $this->get_table_data($inDb, $inTable, $inColumns);
			return $this->get_sv_output($d, $inTargetColumns, $inSep);
		}
		
		function get_table_html($inDb, $inTable, $inColumns = '*', $inTargetColumns = '')
		{
			$d = $this->get_table_data($inDb, $inTable, $inColumns);
			return $this->get_html_output($d, $inTargetColumns);
		}
		
		function get_query_html($inDb, $inSql)
		{
			$d = $this->get_query_data($inDb, $inSql);
			return $this->get_html_output($d);
		}
	}
	
	class turboSqlIo extends turboIo
	{
		function get_table_status()
		{
			$q = "SHOW TABLE STATUS FROM ".$this->Db." LIKE '".$this->Table."'";
			$this->Server->SetFetchMode(ADODB_FETCH_ASSOC); 
			$rs = &$this->Server->Execute($q);
			return $rs->GetAssoc();
		}
		
		function get_drop_table_sql()
		{
			return 'DROP TABLE IF EXISTS ' . $this->bq($this->TableTarget) . ';';
		}
	
		function get_target_tables($inTableList, $inTablePrefix = '')
		{
			if (!$inTablePrefix)
				return $inTableList;
			$result = array();	
			foreach ($inTableList as $table)
				array_push($result, $inTablePrefix . $table);
			return $result;
		}
		
		function get_create_table_sql()
		{
			if ($this->useBq)
			{
				$q = 'SET SQL_QUOTE_SHOW_CREATE = ' . (/*use_backquotes*/ true ? '1' : '0');
				$rs = &$this->Server->Execute($q);
				if (!$rs) 
					user_error(mysql_error());
			}		
			//$q = 'SHOW CREATE TABLE ' . $this->get_full_table_name();
			$q = 'SHOW CREATE TABLE ' . $this->bq($this->Table);
			$this->Server->SetFetchMode(ADODB_FETCH_NUM); 
			$rs = &$this->Server->Execute($q);
			if (!$rs) 
				return '';
				//user_error(mysql_error());
			$sql = $rs->fields[1];
			if (/*use_if_not_exists*/ true) 
				$sql = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $sql);
			$sql = str_replace($this->bq($this->Table), $this->bq($this->TableTarget), $sql);
			return $sql . ';';
		}
		
		function get_create_database_sql($inEol="\n")
		{
			$sql = "CREATE DATABASE IF NOT EXISTS " . $this->bq($this->DbTarget) . ";" . $inEol;
			$sql .= "USE " . $this->bq($this->DbTarget) . ";" . $inEol . $inEol;
			return $sql;
		}
	
		function get_inserts_sql($inColumns = '')
		{
			$inColumns = (is_array($inColumns) && count($inColumns)) ?
					$inColumns = $this->join_columns($inColumns) :
					$inColumns = '';	
			if ($inColumns)
				//$q = 'SELECT ' . $inColumns . ' FROM ' . $this->get_full_table_name();	
				$q = 'SELECT ' . $inColumns . ' FROM ' . $this->bq($this->Table);	
			else
				//$q = 'SELECT * FROM ' . $this->get_full_table_name();
				$q = 'SELECT * FROM ' . $this->bq($this->Table);
			$this->Server->SetFetchMode(ADODB_FETCH_NUM); 
			$rs = &$this->Server->Execute($q);
			if (!$rs) 
				user_error(mysql_error());
			if ($inColumns)
				$inColumns = ' (' . $inColumns . ')';
			$sql = ($rs->EOF) ? "" : "INSERT INTO " . $this->bq($this->TableTarget) . $inColumns . " VALUES ";
			while (!$rs->EOF) 
			{
				$sql .= "('" . implodeFields("', '", $rs->fields) . "')";
				$rs->MoveNext();
				$sql .= (!$rs->EOF) ? ', ' . $this->Eol : ';' . $this->Eol;
			}
			return $sql;
		}
		
		function return_sql($sql = '', $inSilent=false, $inErrorMsg = "No Sql Returned")
		{
			if ($sql <> '')
				return $sql;
			else if (!$inSilent)
				user_error($inErrorMsg);
		}
		
		function export_table_sql($inDb, $inTable, $inTableTarget='', $inColumns = '', $inIncludeData = true, $inSilent = true)
		{
			$this->init($inDb, '', $inTable, $inTableTarget);
			$sql = '';
			$createSql = $this->get_create_table_sql();
			if (true) //use_drop_table
				$dropSql .= $this->get_drop_table_sql();
			if ($createSql)
				$sql .= $dropSql . $this->Eol . $this->Eol . $createSql . $this->Eol . $this->Eol;
			if ($inIncludeData)
				$sql .= $this->get_inserts_sql($inColumns). $this->Eol;
			return $this->return_sql($sql, $inSilent, "No SQL returned for [$inDb.$inTable]");
		}
		
		function export_tables_sql($inDb, $inTableArray, $inTableTargetArray = '', $inIncludeData = true, $inSilent=true)
		{
			$sql = '';
			for ($i = 0; $i < count($inTableArray); $i++)
			{
				$table = $inTableArray[$i];
				$target = $inTableTargetArray && $inTableTargetArray[$i] ? $inTableTargetArray[$i] : '';
				$sql .= $this->export_table_sql($inDb, $table, $target, '', $inIncludeData, $inSilent);
				$sql .= $this->Eol . $this->Eol;
			}	
			return $this->return_sql($sql, $inSilent);
		}
		
		function export_database_sql($inDb, $inTargetDb='', $inTablePrefix = '', $inIncludeData = true, $inSilent = true)
		{
			$this->init($inDb, $inTargetDb);
			$sql = $this->get_create_database_sql();
			$tables = $this->list_tables($inDb);
			$targetTables = $this->get_target_tables($tables, $inTablePrefix);
			$sql .= $this->export_tables_sql($inDb, $tables, $targetTables, $inIncludeData, $inSilent);
			return $this->return_sql($sql, $inSilent, '');
		}
		
		function export_databases_sql($inDbArray, $inDbTargetArray = '', $inTablePrefix = '', $inIncludeData = true, $inSilent = true)
		{
			$sql = '';
			for ($i = 0; $i < count($inDbArray); $i++)
			{
				$db = $inDbArray[$i];
				$targetDb = $inDbTargetArray && $inDbTargetArray[$i] ? $inDbTargetArray[$i] : '';
				$sql .= $this->export_database_sql($db, $targetDb, $inTablePrefix, $inIncludeData, $inSilent);
				$sql .= $this->Eol . $this->Eol;
			}	
			return $this->return_sql($sql, $inSilent);
		}
		
		function list_tables($inDb)
		{
			$this->init($inDb);
			$recordSet = &$this->Server->MetaTables();
			if ($recordSet === false) 
				user_error($db->ErrorMsg());
			else
				return $recordSet; 	
		}
		
		function duplicate_databases($inDbs, $inNewDbs)
		{
			$dupSql = $this->export_databases_sql($inDbs, $inNewDbs);
			$this->import_sql('', $dupSql);
		}
		
		function duplicate_tables($inTables, $inNewTables, $inDatabase)
		{
			$dupSql = $this->export_tables_sql($inDatabase, $inTables, $inNewTables, '', true);
			$this->import_sql($inDatabase, $dupSql);
		}
		
		function import_sql($inDb, &$inSql)
		{
			$this->init($inDb);
			$cmds = &splitSql($inSql, $this->Eol);
			foreach ($cmds as $cmd) 
			{
				$rs = &$this->Server->Execute($cmd);
				if (!$rs) 
					user_error(mysql_error());
			}
			return "Processed " . count($cmds) . " instruction(s).";
		}
	}
?>