<?php

	define('SESSION_NAME', 'turbosid');
	//
	define('ROLES', 'roles');
	define('NOBODY_ROLE', 'nobody');
	define('USER_ROLE', 'user');
	define('ADMIN_ROLE', 'admin');
	//
	define('S_BAD_AUTH', 'Attempt to connect to an unauthorized session.');
	define('S_BAD_LOGOUT', 'Not logged in.');

	class turboAuthenticator 
	{
		var $Permissions;

		function turboAuthenticator()
		{
			$this->connect();
		}
		
		function unloadSession()
		{
			$_SESSION = array();
			unset($_COOKIE[session_name()]);
			session_destroy();			
		}
		
		function destroySession()
		{
			//if (isset($_COOKIE[session_name()])) 
			//	setcookie(session_name() ,"",0,"/");
			setcookie(session_name(), '', time()-42000, '/');
			$this->unloadSession();
			// ideally we would GC the session data now
		}
	
		function connect()
		{
			session_name(SESSION_NAME);
			session_start();
			$this->permit();
		}
		
		function permit()
		{
			if (isset($_SESSION[ROLES]))
				$this->Permissions = $_SESSION[ROLES];
			else
				$this->Permissions = array(NOBODY_ROLE);
		}
		
		function permitted($inRole)
		{
			return ($inRole == NULL || in_array($inRole, $this->Permissions));
		}

		function assign_roles($inCredentials)
		{
			$_SESSION[ROLES] = array(NOBODY_ROLE);
			// need some actual checking of credentials here
			$_SESSION[ROLES][] = USER_ROLE;
			if ($inCredentials[0] == ADMIN_ROLE)
				$_SESSION[ROLES][] = ADMIN_ROLE;
		}

		function authorize($inCredentials)
		{
			$this->unloadSession();
			session_regenerate_id();
			session_start();
			$this->assign_roles($inCredentials);
			$this->permit();
			return $this->Permissions;
		}
		
		function logout()
		{
			if (!isset($_SESSION[ROLES]))
				user_error(S_BAD_LOGOUT);
			$this->destroySession();
			$this->permit();
			return $this->Permissions;
		}
	}

	class turboLoginProxy
	{
		var $Authenticator = null;
		function turboLoginProxy($inAuth)
		{
			$this->Authenticator = $inAuth;
		}
		function login($inUser, $inPass)
		{
			return $this->Authenticator->Authorize(array($inUser, $inPass));
		}
		function logout($inUser, $inPass)
		{
			return $this->Authenticator->Logout();
		}
	}

?>