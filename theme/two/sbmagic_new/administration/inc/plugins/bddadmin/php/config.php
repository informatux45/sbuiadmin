<?php

	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	// Change the database in:             -=
	// turbo/config.js                     -=
	// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	
	function turboGetConnectData() {
		// --------- Hook BooBoo
		define('_AM_DB_FILE', '../../../../inc/admin/settings.txt');
		$sb_db_config = file(_AM_DB_FILE);
		// ------------------------------------------
		return array(
			'server'   => trim($sb_db_config[2]),
			/* To enable experimental Postgres support change 'type' to 'postgres' */
			'type'     => 'mysql',
			'user'     => trim($sb_db_config[4]),
			'password' => trim($sb_db_config[5]),
			'db'       => trim($sb_db_config[3]),
			/*
				Multi-user basic auth settings:
				Multiple users can login to TurboDbAdmin by using basic auth. To enable this login method: 
				(1) comment out the previous 'user' and 'password' lines and uncomment the following 'user' 
				and 'password' lines.
				(2) Password protect the TurboDbAdmin folder using Basic Auth. Basic Auth users must have 
				name and password the same as their MySQL name and password.
				More options for user login will be available in the next release of TurboDbAdmin.
			*/
			// 'user' => '$_SERVER['PHP_AUTH_USER']',
			// 'password' => $_SERVER['PHP_AUTH_PW'],
			
			/* Character encoding settings. */
			// 'encoding' => $db_encoding, // data output from the DB is utf-8 and can go directly to the browser
			// 'names' => $db_names // MySQL 4.1 or higher only
		);
	}
	
	define('SQL_FILE', '../save/sql.xml');
	
	// NOTE: Increase script time limit if operations like executing large queues of sql commands are timing out.
	//set_time_limit(300);
	
?>