<?php

	$local = dirname(__FILE__) . '/';
	require_once($local . 'config.php');
	
	define('ROOT_NODE', 'saves');
	define('XML_DEF', '<?xml version="1.0" ?>');
	
	// file_put_contents is php5 only so use this (from php.net docs)
	//define('FILE_APPEND', 1);
	function put_contents($filename, $data, $flags = 0, $f = FALSE)
	{
		if (($f===FALSE) && (($flags%2)==1))
			$f=fopen($filename, 'a');
		else if ($f===FALSE)
			$f=fopen($filename, 'w');
		if (round($flags/2)==1) 
			while (!flock($f, LOCK_EX)) { /* lock */ }
		if (is_array($data)) 
			$data=implode('', $data);
		fwrite($f, $data);
		if (round($flags/2)==1)
			flock($f, LOCK_UN);
		fclose($f);
	}
	
	function ot($inTag)
	{
		return '<' . $inTag . '>';
	}
	
	function ct($inTag)
	{
		return '</' . $inTag . '>';
	}

	class turboFile
	{
		var $fileName;
		var $eol = "\n";
		var $tab = "\t";
		
		function set_file($inFile)
		{
			$this->fileName = $local . $inFile;
		}
		
		function get_file()
		{
			if (!$this->fileName)
				$this->fileName = dirname(SQL_FILE) ? SQL_FILE : local . SQL_FILE;
			return $this->fileName;
		}
		
		// syntax: <item><name></name><data></data></item>
		function parse_file_string($inString)
		{
			if (!preg_match_all('/<(.*)>(.*)<\/\1>/isU', $inString, $items))
				return;
			while ($items[1][0] != 'item')
			{
				if (!preg_match_all('/<(.*)>(.*)<\/\1>/isU', $items[2][0], $items))
					return;
			}		
			$result = array();
			foreach ($items[2] as $item)
			{
				preg_match_all('/<(.*)>(.*)<\/\1>/isU', $item, $props);
				$itemArray = array();
				for($i=0; $i < count($props[1]); $i++)
					$itemArray[$props[1][$i]] = $props[2][$i];
				$result[] = $itemArray;
			}
			return $result;
		}
		function load_file()
		{
			$file = $this->get_file();
			if (file_exists($file))
				$s = file_get_contents($file);
			else
				return;	
			
			return $this->parse_file_string($s);
		}
		
		// duplicate if property matches (name by default)
		function item_exists($inList, $inItem, $inProp = 'name')
		{
			return $this->item_index($inList, $inItem, $inProp) != -1;
		}
		
		function item_index($inList, $inItem, $inProp = 'name')
		{
			if (!is_array($inList))
				return -1;
			for ($i=0; $i < count($inList); $i++)
				if (is_array($inItem))
				{
					if ($inItem[$inProp] == $inList[$i][$inProp])
						return $i;
				}		
				else if (is_object($inItem))
				{
					if ($inItem->$inProp == $inList[$i][$inProp])
						return $i;
				}		
			return -1;	
		}
		
		function append_items($inSrc, $inAppend)
		{
			if (!is_array($inAppend))
				return $inSrc;
			foreach ($inAppend as $item)
			{
				$itemIndex = $this->item_index($inSrc, $item);
				if ($itemIndex == -1)
					$inSrc[] = $item;
				else
					$inSrc[$itemIndex] = $item;
			}
			return $inSrc;
		}
		
		function items_to_xml_string($inItems, $inName = 'item', $inRoot = '')
		{
			if (!$inRoot)
				$inRoot = ROOT_NODE;
			$s = XML_DEF . $this->eol;
			$s .= ot($inRoot) . $this->eol;
			$s .= $this->items_to_string($inItems, $inName);
			$s .= ct($inRoot);
			return $s;
		}
		
		function items_to_string($inItems, $inName = 'item')
		{
			$s = '';
			foreach($inItems as $item)
			{
				$s .= ot($inName) . $this->eol;
				foreach($item as $key => $value)
					$s .= $this->tab . ot($key) . $value . ct($key) . $this->eol;
				$s .= ct($inName) . $this->eol;	
			}
			return $s;
		}		
		
		function save_items($inItems, $rewrite = false)
		{
			if (!$rewrite)
				$items = $this->append_items($this->load_file(), $inItems);
			else
				$items = $inItems;	
			put_contents($this->get_file(), $this->items_to_xml_string($items));
		}
		
		function delete_items($inDeleteItems)
		{
			$items = $this->load_file();
			foreach($inDeleteItems as $deleteItem)
			{
			 	$delIndex = $this->item_index($items, $deleteItem);
				if ($delIndex > -1)
					array_splice($items, $delIndex, 1);
			}	
			put_contents($this->get_file(), $this->items_to_xml_string($items));
		}
		
		function edit_items($inEditItems)
		{
			$items = $this->load_file();
			foreach($inEditItems as $editItem)
			{
			 	$editIndex = $this->item_index($items, $editItem);
				if ($editIndex > -1)
					$items[$editIndex] = $editItem;
			}	
			put_contents($this->get_file(), $this->items_to_xml_string($items));
		}
	}
?>