<?php

	define('RPC_DISCOVER', 'discover');

	class turboRpcCodec
	{
		function &decode(&$inBuffer)
		{
			return $inBuffer;
		}
		function &encode(&$inBuffer)
		{
			return $inBuffer;
		}
	}
	
	class turboRpcResponse
	{
		var $result = NULL;
		var $error = NULL;
		var $id = 0;
		//
		function turboRpcResponse($inId)
		{
			$this->id = $inId;
		}
	}
	
	class turboRpc
	{
		var $Targets;
		var $Response;
		var $Config;
		var $Codec;

		function turboRpc($inCodec = NULL)
		{
			$this->Codec = $inCodec;
			$old_error_handler = set_error_handler(array(&$this, "rpcErrorHandler"));
		}
				
		function addTarget(&$inTarget)
		// various ampersands needed to avoid deep-copying objects in PHP4
		{
			$this->Targets[] = &$inTarget;
			$inTarget->rpc = &$this;
		}
		
		function getTargetMethods($inTargets)
		{
			$result = array();
			$c = count($inTargets);
			// 'for' instead of 'foreach' to avoid deep-copying objects in PHP4
			for ($i=0; $i < $c; $i++)
				$result = array_merge($result, array_diff(get_class_methods($inTargets[$i]), array(get_class($inTargets[$i]))));
			return $result;
		}
		
		function getMethodList()
		{
			return $this->getTargetMethods($this->Targets);
		}
		
		function &findInTargets($inMethod, &$inTargets)
		{
			$c = count($inTargets);
			// 'for' instead of 'foreach' to avoid deep-copying objects in PHP4
			for ($i=0; $i < $c; $i++)
				if (method_exists($inTargets[$i], $inMethod))
					return $inTargets[$i];
			return null;
		}
		
		function &findTarget($inMethod)
		{
			$target = $this->findInTargets($inMethod, $this->Targets);
			// error output is here because subclasses may have other
			// reasons for returning a NULL target
			if ($target == NULL)
				$this->Response->error = "rpc: could not find remote procedure '$request->method'";
			return $target;				
		}

		function _dispatch($inRequest)
		{
			echo "method: ".$inRequest->method." -- ";
			if ($inRequest->id == 1)
				exit;
			$this->Response = new turboRpcResponse($inRequest->id);
			if ($inRequest->method == RPC_DISCOVER)
				$this->Response->result = $this->getMethodList();
			else {
				$target =& $this->findTarget($inRequest->method);
				if ($target !== NULL)
					$this->Response->result = call_user_func_array(array(&$target, $inRequest->method), $inRequest->arguments);
			}
		}
		
		function dispatch(&$inRequest)
		{
			echo "dispatching request: ".$inRequest." -- ";
			$req = $this->Codec->decode($inRequest);
			echo "decode successful -- ";
			if ($req->id == 0)
				exit;
			$this->_dispatch($this->Codec->decode($inRequest));
			$this->finish();
		}

		function error($inError)
		{
			$this->Response->error = $inError;
			$this->finish();
		} 
		
		function rpcErrorHandler($errno, $errmsg, $filename, $linenum, $vars) 
		{
			switch ($errno)
			{
				case E_USER_NOTICE:
					$this->error($errmsg);
					break;
				case E_USER_ERROR:
				case E_USER_WARNING:
					$this->error("$errmsg (line $linenum in " . basename($filename) . ")");
					//$this->error(array($errmsg, basename($filename), $linenum));
					break;
				default:
					break;
			}				
		}
		
		function finish()
		{
			echo $this->Codec->encode($this->Response);
			exit;
		}
	}

?>