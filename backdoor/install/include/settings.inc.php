<?php

    // -------------------------------------------------------------------------
    // 1. GLOBAL SETTINGS
    // -------------------------------------------------------------------------
    // *** system mode (demo|debug|production)
    define('EI_MODE', 'production');
    
    // *** version number of SBUIADMIN Installer
    define('EI_VERSION', '2.2.0');

    // *** default language: en - English
    define('EI_DEFAULT_LANGUAGE', 'fr');
    // *** default language direction: ltr - Left-To-Right (), rtl - Right-To-Left
    define('EI_DEFAULT_LANGUAGE_DIRECTION', 'ltr');

    // *** array of available languages
    //     to not show dropdown box with languages define it as empty
    //     $arr_active_languages = array()
    $arr_active_languages = array(
        'fr'=>array('name'=>'French', 'direction'=>'ltr'),
        'en'=>array('name'=>'English', 'direction'=>'ltr'),
        //'es'=>array('name'=>'Spanish', 'direction'=>'ltr'),
        //'de'=>array('name'=>'German', 'direction'=>'ltr'),
    );
    
    // *** default template
    define('EI_TEMPLATE', 'informatux');


    // -------------------------------------------------------------------------
    // 2. GENERAL SETTINGS
    // -------------------------------------------------------------------------
    // *** check for PHP minimum version number (true, false) -
    //     checks if a minimum required version of PHP runs on a server
    define('EI_CHECK_PHP_MINIMUM_VERSION', true);
    define('EI_PHP_MINIMUM_VERSION', '7.2.0');
    
    // *** check or not config directory for writability
    define('EI_CHECK_CONFIG_DIR_WRITABILITY', false);

    // *** allows collecting info for magic quotes
    define('EI_CHECK_MAGIC_QUOTES', false);
    
    // *** allows collecting info for mbstring support
    define('EI_CHECK_MBSTRING_SUPPORT', false);

    // *** allows collecting info for email settings
    define('EI_CHECK_MAIL_SETTINGS', false);

    // *** allows collecting info for specified extensions
    define('EI_CHECK_EXTENSIONS', true);

    // *** allows collecting info for specified modes
    define('EI_CHECK_MODES', true);
    
    // *** allows collecting info for writability of specified directories and files
    define('EI_CHECK_DIRECTORIES_AND_FILES', true);
    
   
    
    // -------------------------------------------------------------------------
    // 3. DATABASE SETTINGS
    // -------------------------------------------------------------------------
    // *** force database creation
    define('EI_DATABASE_CREATE', false);

    // *** define database type
    // *** to check installed drivers use: print_r(PDO::getAvailableDrivers());
    //     mysql          - MySql,
    //     pgsql          - PostgreSQL
    //     sqlite/sqlite2 - SQLite 
    //     oci            - Oracle
    //     cubrid         - Cubrid
    //     firebird       - Firebird/Interbase 6
    //     dblib          - FreeTDS / MSSQL / Sybase
    //     sqlsrv         - Microsoft SQL Server 
    //     ibm            - IBM DB2
    //     informix       - IBM Informix Dynamic Server
    //     odbc           - ODBC v3 (IBM DB2, unixODBC and win32 ODBC)
    define('EI_DATABASE_TYPE', 'mysql');

    // *** check for database engine minimum version number (true, false) -
    //     checks if a minimum required version of database engine runs on a server
    define('EI_CHECK_DB_MINIMUM_VERSION', true);
    define('EI_DB_MINIMUM_VERSION', '5.0.0');    
        
    // *** admin username and password (true, false) - get admin username and password
    define('EI_USE_ADMIN_ACCOUNT', true);
    // *** encrypt or not admin password true|false
    define('EI_USE_PASSWORD_ENCRYPTION', false);        
    // *** type of encryption - AES|MD5
    define('EI_PASSWORD_ENCRYPTION_TYPE', 'AES');        
    // *** password encryption key for AES encryption
    define('EI_PASSWORD_ENCRYPTION_KEY', 'php_installer_informatux');
    
    
    // -------------------------------------------------------------------------
    // 4. CONFIG PARAMETERS
    // -------------------------------------------------------------------------
    // *** config file directory - directory, where config file must be created
    //     for ex.: '../common/' or 'common/' - according to directory hierarchy and relatively to start.php file
    define('EI_CONFIG_FILE_DIRECTORY', '../inc/admin/');
    // *** config file name - output file with config parameters (database, username, etc.)
    define('EI_CONFIG_FILE_NAME', 'settings.txt');
    // *** dashboard file name - output file with config parameters (database, tables, etc.)
    define('EI_CONFIG_DASHBOARD_FILE_NAME', 'dashboard.txt');
    // *** according to directory hierarchy (you may add/remove '../' before EI_CONFIG_FILE_DIRECTORY)
    define('EI_CONFIG_FILE_PATH', EI_CONFIG_FILE_DIRECTORY.EI_CONFIG_FILE_NAME);
    // *** according to directory hierarchy (you may add/remove '../' before EI_CONFIG_FILE_DIRECTORY)
    define('EI_CONFIG_DASHBOARD_FILE_PATH', EI_CONFIG_FILE_DIRECTORY.EI_CONFIG_DASHBOARD_FILE_NAME);
    // *** install file backdoor
    define('EI_CONFIG_FILE_INSTALL_START', '../install.php');
    // *** htaccess file backdoor
    define('EI_CONFIG_FILE_HTACCESS', '../htaccess');   
    // *** Installer LOCK file
    define('EI_CONFIG_FILE_INSTALLER_LOCK', './installer/installer.lock');
    
    // *** specifies whether to allow new installation
    define('EI_ALLOW_NEW_INSTALLATION', true);        
    // *** specifies whether to allow update
    define('EI_ALLOW_UPDATE', false);        
    // *** specifies whether to allow un-installation
    define('EI_ALLOW_UN_INSTALLATION', false);        

    // *** allows start all over button
    define('EI_ALLOW_START_ALL_OVER', false);
    
    // *** sql dump file - file that includes SQL statements for instalation
    if (version_compare(phpversion(), '7.1.0', '<')) {
        // Only for 7.0 PHP Version
        define('EI_SQL_DUMP_FILE_CREATE', 'sql_dump/sbuiadmin_create.sql');
    } else {
        // For 7.1 PHP Version and more
        define('EI_SQL_DUMP_FILE_CREATE', 'sql_dump/sbuiadmin_create_7.1_and_more.sql');
    }
    define('EI_SQL_DUMP_FILE_UPDATE', 'sql_dump/update.sql');
    define('EI_SQL_DUMP_FILE_UN_INSTALL', 'sql_dump/un-install.sql');

    // *** defines using of utf-8 encoding and collation for SQL dump file
    define('EI_USE_ENCODING', true);
    define('EI_DUMP_FILE_ENCODING', 'utf8');
    define('EI_DUMP_FILE_COLLATION', 'utf8_unicode_ci');               
    
    // *** allows manual installation
    define('EI_ALLOW_MANUAL_INSTALLATION', false);
    // *** manual installation text directory and text files
    define('EI_MANUAL_INSTALLATION_DIR', 'manual/');    
    $arr_manual_installations = array(
        'en'=>'manual.en.txt',
        'es'=>'manual.es.txt',
        'de'=>'manual.de.txt'
    );
    

    // -------------------------------------------------------------------------
    // 5. CONFIG TEMPLATE PARAMETERS
    // -------------------------------------------------------------------------
    // *** config file name - config template file name
    define('EI_CONFIG_FILE_TEMPLATE', 'config.tpl');
   
    
    // -------------------------------------------------------------------------
    // 6. APPLICATION PARAMETERS
    // -------------------------------------------------------------------------
    // *** application name
    define('EI_APPLICATION_NAME', 'SBUIADMIN');
    // *** version number of your application
    define('SBUIADMIN_PATH', true);
    // --- Handler ajax
    global $handler_ajax;
    if (!$handler_ajax)
        include_once('../inc/admin/version.php');
    else
        include_once('../../inc/admin/version.php');
    define('EI_APPLICATION_VERSION', _AM_START_VERSION . ' ');
    
    // *** default start file name - application start file
    define('EI_APPLICATION_START_FILE', '');
    
    // *** license agreement page
    define('EI_LICENSE_AGREEMENT_PAGE', false);    
   
    // *** additional text after successful installation
    define('EI_POST_INSTALLATION_TEXT', '');
    
?>