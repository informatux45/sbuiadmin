<?php

	$local = dirname(__FILE__) . '/';
  require_once($local . 'rpc.php');
	
	define('S_NO_CRED', 'Credentials insufficient for operation.');

	class turboSecureRpc extends turboRpc
	{
		var $Authority;
		
		function turboSecureRpc(&$inAuthority)
		{
			$this->turboRpc();
			$this->Authority =& $inAuthority;
		}
		
		function addTarget($inRole, &$inTarget)
		// various ampersands needed to avoid deep-copying objects in PHP4
		{
			$this->Targets[$inRole][] = &$inTarget;
			$inTarget->rpc = &$this;
		}
		
		function getMethodList()
		{
			$result = array();
			foreach ($this->Targets as $role => $targets)				
				$result = array_merge($result, $this->getTargetMethods($targets));
			return $result;				
		}
				
		function &findTarget($inMethod)
		{
			foreach ($this->Targets as $role => $targets)				
			{
				$target =& $this->findInTargets($inMethod, $targets);
				if ($target)
					if ($this->Authority->permitted($role))
						return $target;
					else
					{
						$this->Response->error = S_NO_CRED . "($role, $inMethod)";
						break;
					}
			}
			return NULL;
		}
	}
?>