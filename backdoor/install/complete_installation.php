<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP EasyInstaller Free version                                           #
##  Developed by:  ApPHP <info@apphp.com>                                      #
##  License:       GNU LGPL v.3                                                #
##  Site:          http://www.apphp.com/php-easyinstaller/                     #
##  Copyright:     ApPHP EasyInstaller (c) 2009-2013. All rights reserved.     #
##                                                                             #
################################################################################

	session_start();
	
	require_once('include/shared.inc.php');    
    require_once('include/settings.inc.php');
	require_once('include/database.class.php'); 
    require_once('include/functions.inc.php');	
	require_once('include/languages.inc.php');	
    
	$passed_step = isset($_SESSION['passed_step']) ? (int)$_SESSION['passed_step'] : 0;

	// handle previous steps
	// -------------------------------------------------
	if($passed_step >= 5){
		// OK
	}else{
		header('location: start.php');
		exit;				
	}
	
	if(EI_MODE == 'debug') error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
    
	$completed = false;
	$error_mg  = array();
		
	if($passed_step == 5){
		
		$database_host	   = isset($_SESSION['database_host']) ? prepare_input($_SESSION['database_host']) : '';
		$database_name	   = isset($_SESSION['database_name']) ? prepare_input($_SESSION['database_name']) : '';
		$database_username = isset($_SESSION['database_username']) ? prepare_input($_SESSION['database_username']) : '';
		$database_password = isset($_SESSION['database_password']) ? prepare_input($_SESSION['database_password']) : '';
		$database_prefix   = isset($_SESSION['database_prefix']) ? stripslashes($_SESSION['database_prefix']) : '';
		$install_type  	   = isset($_SESSION['install_type']) ? $_SESSION['install_type'] : 'create';
		
		$settings_customer_name     = isset($_SESSION['settings_customer_name']) ? stripslashes($_SESSION['settings_customer_name']) : '';
		$settings_customer_url 	    = isset($_SESSION['settings_customer_url']) ? $_SESSION['settings_customer_url'] : '';
		$settings_url_upload 	    = isset($_SESSION['settings_url_upload']) ? $_SESSION['settings_url_upload'] : '';
		$settings_path_upload       = isset($_SESSION['settings_path_upload']) ? stripslashes($_SESSION['settings_path_upload']) : '';
		$settings_recaptcha_public  = isset($_SESSION['settings_recaptcha_public']) ? stripslashes($_SESSION['settings_recaptcha_public']) : '';
		$settings_recaptcha_private = isset($_SESSION['settings_recaptcha_private']) ? stripslashes($_SESSION['settings_recaptcha_private']) : '';
		
		$password_encryption = isset($_SESSION['password_encryption']) ? $_SESSION['password_encryption'] : EI_PASSWORD_ENCRYPTION_TYPE;
		
		if($install_type == 'update'){
			$sql_dump_file = EI_SQL_DUMP_FILE_UPDATE;
		}else if($install_type == 'un-install'){
			$sql_dump_file = EI_SQL_DUMP_FILE_UN_INSTALL;
		}else{
			$sql_dump_file = EI_SQL_DUMP_FILE_CREATE;
		}		
						
		if(empty($database_host)) $error_mg[] = lang_key('alert_db_host_empty');	
		if(empty($database_name)) $error_mg[] = lang_key('alert_db_name_empty'); 
		if(empty($database_username)) $error_mg[] = lang_key('alert_db_username_empty'); 	
		if (empty($database_password)) $error_mg[] = lang_key('alert_db_password_empty');

		if(empty($error_mg)){		
			if(EI_MODE == 'demo'){
				if($database_host == 'localhost' && $database_name == 'db_name' && $database_username == 'test' && $database_password == 'test'){
					$completed = true; 
				}else{
					$error_mg[] = lang_key('alert_wrong_testing_parameters');
				}
			}else{				
				$db = Database::GetInstance($database_host, $database_name, $database_username, $database_password, EI_DATABASE_TYPE, false, true);
				if(EI_DATABASE_CREATE && ($install_type == 'create') && !$db->Create()){
					$error_mg[] = $db->Error();					
				}else if($db->Open()){
					if(EI_CHECK_DB_MINIMUM_VERSION && (version_compare($db->GetVersion(), EI_DB_MINIMUM_VERSION, '<'))){
						$alert_min_version_db = lang_key('alert_min_version_db');
						$alert_min_version_db = str_replace('_DB_VERSION_', '<b>'.EI_DB_MINIMUM_VERSION.'</b>', $alert_min_version_db);
						$alert_min_version_db = str_replace('_DB_CURR_VERSION_', '<b>'.$db->GetVersion().'</b>', $alert_min_version_db);
						$alert_min_version_db = str_replace('_DB_', '<b>'.$db->GetDbDriver().'</b>', $alert_min_version_db);
						$error_mg[] = $alert_min_version_db;
					}else{
						// read sql dump file
						$sql_dump = file_get_contents($sql_dump_file);
						if($sql_dump != ''){
							if(false == ($db_error = apphp_db_install($sql_dump_file))){
								if(EI_MODE != 'debug') $error_mg[] = lang_key('error_sql_executing');								
							}else{
								// write additional operations here, like setting up system preferences etc.
								
								# One level up
								$settings_file  = EI_CONFIG_FILE_PATH;
								$dashboard_file = EI_CONFIG_DASHBOARD_FILE_PATH;
								$htaccess_file  = EI_CONFIG_FILE_HTACCESS;
								$install_file   = EI_CONFIG_FILE_INSTALL_START;
								//clearstatcache(true);
								//$install_file   = getcwd() . '/' . EI_CONFIG_FILE_INSTALL_START;
								$installer_lock = EI_CONFIG_FILE_INSTALLER_LOCK;
								
								// Injection des données (Settings File)
								$output_file  = $_SESSION['settings_customer_name'] . "\n";
								$output_file .= "admin" . "\n"; // Administrateurs
								$output_file .= $_SESSION['database_host'] . "\n";
								$output_file .= $_SESSION['database_name'] . "\n";
								$output_file .= $_SESSION['database_username'] . "\n";
								$output_file .= $_SESSION['database_password'] . "\n";
								$output_file .= $_SESSION['settings_path_upload'] . "\n";
								$output_file .= "2MB" . "\n";  // Upload max
								$output_file .= " " . "\n";    // Modules autorises
								$output_file .= "0" . "\n";    // Debug General
								$output_file .= "0" . "\n";    // Debug Formulaire
								$output_file .= "0" . "\n";    // Debug Smarty
								$output_file .= "jpg,jpeg,png,gif,pdf,xml,mp4" . "\n";
								$output_file .= $_SESSION['settings_url_upload'] . "\n";
								$output_file .= "20" . "\n";   // Uploads simultanes (limit)
								$output_file .= $_SESSION['settings_customer_url'] . "\n";
								$output_file .= "1" . "\n";    // Sandbox
								$output_file .= "1" . "\n";    // Cms
								$output_file .= "1024" . "\n"; // Image Scaling Max Size (Medias upload)
								$output_file .= $_SESSION['settings_recaptcha_public'] . "\n";
								$output_file .= $_SESSION['settings_recaptcha_private'] . "\n";
								$output_file .= $_SESSION['database_prefix'] . "\n";
								$output_file .= "0" . "\n"; // Captcha
								$output_file .= "0" . "\n"; // Upgrade
								$output_file .= "1" . "\n"; // Coming soon (maintenance)
								$output_file .= "0" . "\n"; // Debug General Front
								$output_file .= "0" . "\n"; // Debug Smarty Front
								$output_file .= "1" . "\n"; // Smarty Force Compile
								$output_file .= "0" . "\n"; // Rewrite Url
								$output_file .= "0" . "\n"; // Smarty Caching
								$output_file .= "3600" . "\n"; // Smarty Caching Lifetime
								
								// Locker le fichier pour qu'une seule personne a la fois ecrive dedans
								$result_edit = file_put_contents($settings_file, $output_file, FILE_USE_INCLUDE_PATH | LOCK_EX);
								
								// Injection des données (Dashboard File)
								$output_file_2  = $_SESSION['database_prefix'] . "sb_sandbox" . "\n";
								$output_file_2 .= $_SESSION['database_prefix'] . "sb_sandbox" . "\n";
								$output_file_2 .= "Nom (Sandbox)" . "\n";
								$output_file_2 .= "index.php?p=sandbox" . "\n";
								$output_file_2 .= "ambulance" . "\n";
								$output_file_2 .= "nom" . "\n";
								
								// Locker le fichier pour qu'une seule personne a la fois ecrive dedans
								$result_edit_2 = file_put_contents($dashboard_file, $output_file_2, FILE_USE_INCLUDE_PATH | LOCK_EX);
								
								// Ecrire le fichier htaccess
								//$output_htaccess  = '# Prevent viewing of .htaccess file' . "\n";
								//$output_htaccess .= '<Files .htaccess>' . "\n";
								//$output_htaccess .= 'order allow,deny' . "\n";
								//$output_htaccess .= 'deny from all' . "\n";
								//$output_htaccess .= '</Files>' . "\n\n";
								//$output_htaccess .= '# Prevent directory listings' . "\n";
								//$output_htaccess .= 'Options All -Indexes' . "\n";
						
								// Locker le fichier pour qu'une seule personne a la fois ecrive dedans
								//$result_edit = file_put_contents($htaccess_file, $output_htaccess, FILE_USE_INCLUDE_PATH | LOCK_EX);
								
								// Supprimer le fichier INSTALL.PHP
								$delete_install_start_file = @unlink($install_file);
								if (!$delete_install_start_file)
									$error_mg[] = "<b>Erreur :</b> Fichier install.php non supprimé (Vérifier les droits d'écriture sur le fichier) !"; 

								# Lock the installer
								@file_put_contents($installer_lock, "installer lock file");

								//// now try to create file and write information
								//$config_file = file_get_contents(EI_CONFIG_FILE_TEMPLATE);
								//$config_file = str_replace('<DB_HOST>', $database_host, $config_file);
								//$config_file = str_replace('<DB_NAME>', $database_name, $config_file);
								//$config_file = str_replace('<DB_USER>', $database_username, $config_file);
								//$config_file = str_replace('<DB_PASSWORD>', $database_password, $config_file);
								//$config_file = str_replace('<DB_PREFIX>', $database_prefix, $config_file);
								//
								//if(EI_USE_ADMIN_ACCOUNT){
								//	$config_file = str_replace('<ENCRYPTION>', (EI_USE_PASSWORD_ENCRYPTION) ? 'true' : 'false', $config_file);			
								//	$config_file = str_replace('<ENCRYPTION_TYPE>', $password_encryption, $config_file);			
								//	$config_file = str_replace('<ENCRYPT_KEY>', EI_PASSWORD_ENCRYPTION_KEY, $config_file);
								//}else{
								//	$config_file = str_replace('<ENCRYPTION>', '', $config_file);			
								//	$config_file = str_replace('<ENCRYPTION_TYPE>', '', $config_file);			
								//	$config_file = str_replace('<ENCRYPT_KEY>', '', $config_file);									
								//}
								//
								//chmod(EI_CONFIG_FILE_PATH, 0777);
								//$f = fopen(EI_CONFIG_FILE_PATH, 'w+');
								//if(!fwrite($f, $config_file) > 0){
								//	$error_mg[] = str_replace('_CONFIG_FILE_PATH_', EI_CONFIG_FILE_PATH, lang_key('error_can_not_open_config_file')); 
								//}
								//fclose($f);
								//if($install_type == 'un-install') unlink(EI_CONFIG_FILE_PATH);
								///@chmod('../'.EI_CONFIG_FILE_DIRECTORY, 0644);
								
								$set_errors = array_keys( $error_mg, true );
								if (!$set_errors) {
									$completed = true;
									session_destroy();
								}
								
							}							
						}else{
							$error_mg[] = str_replace('_SQL_DUMP_FILE_', $sql_dump_file, lang_key('error_can_not_read_file')); 
						}						
					}
				}else{
					if(EI_MODE == 'debug'){
						$error_mg[] = str_replace('_ERROR_', '<br />Error: '.$db->Error(), lang_key('error_check_db_connection')); 
					}else{
						$error_mg[] = str_replace('_ERROR_', '', lang_key('error_check_db_connection')); 
					}						
				}
			}			
		}
	}else{
		$error_mg[] = lang_key('alert_wrong_parameter_passed');
	}
    
	// --- Header INC
	include_once('include/header.inc.php');
	    
?>	
<body>
<div id="main">    
	<h1><?php echo lang_key('new_installation_of'); ?> <?php echo EI_APPLICATION_NAME.' '.EI_APPLICATION_VERSION;?>!</h1>
	<h2 class="sub-title"><?php echo lang_key('sub_title_message'); ?></h2>
	
	<div id="content">
		<?php
			draw_side_navigation(6);		
		?>

		<div class="central-part">
			<h2><?php echo lang_key('step_6_of'); ?>
			<?php if(!$completed){ ?>
				- <?php echo lang_key('database_import_error'); ?>
			<?php }else{ ?>
				- <?php echo lang_key('completed'); ?>
				<!--<h3><?php //echo lang_key('updating_completed'); ?></h3>			-->
			<?php } ?>
			</h2>

			<?php
				if(!$completed){
					echo '<div class="alert alert-error">';
					foreach($error_mg as $msg){
						echo $msg.'<br>';
					}
					echo '</div>';
				}
			?>
		
			<table width="99%" cellspacing="0" cellpadding="0" border="0">
			<tbody>
			<?php if(!$completed){ ?>
				<tr><td nowrap height="25px">&nbsp;</td></tr>
				<tr>
					<td>	
						<a href="ready_to_install.php" class="form_button"><?php echo lang_key('back'); ?></a>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" class="form_button" onclick="javascript:location.reload();" value="<?php echo lang_key('complete'); ?>" />
					</td>
				</tr>							
			<?php }else{ ?>
				<tr><td>&nbsp;</td></tr>						
				<?php if($install_type == 'update'){ ?>
					<tr><td><h4><?php echo lang_key('updating_completed'); ?></h4></td></tr>
					<tr>
						<td>
							<div class="alert alert-success"><?php echo str_replace('_CONFIG_FILE_', EI_CONFIG_FILE_PATH, lang_key('file_successfully_rewritten')); ?></div>
							<div class="alert alert-warning"><?php echo lang_key('alert_remove_files'); ?></div>
							<?php echo (EI_POST_INSTALLATION_TEXT != '') ? '<div class="alert alert-info">'.EI_POST_INSTALLATION_TEXT.'</div>' : ''; ?>
							<br /><br />
							<?php if(EI_APPLICATION_START_FILE != ''){ ?><a href="<?php echo '../'.EI_APPLICATION_START_FILE;?>"><?php echo lang_key('proceed_to_login_page'); ?></a><?php } ?>
						</td>
					</tr>									
				<?php }else if($install_type == 'un-install'){ ?>
					<tr><td><h4><?php echo lang_key('uninstallation_completed'); ?></h4></td></tr>
					<tr>
						<td>
							<div class="alert alert-success"><?php echo str_replace('_CONFIG_FILE_', EI_CONFIG_FILE_PATH, lang_key('file_successfully_deleted')); ?></div>
							<div class="alert alert-warning"><?php echo lang_key('alert_remove_files'); ?></div>
							<br /><br />
							<?php if(EI_APPLICATION_START_FILE != ''){ ?><a href="<?php echo '../'.EI_APPLICATION_START_FILE;?>"><?php echo lang_key('proceed_to_login_page'); ?></a><?php } ?>
						</td>
					</tr>															
				<?php }else{ ?>									
					<tr><td><h4><?php echo lang_key('installation_completed'); ?></h4></td></tr>
					<tr>
						<td>
							<div class="alert alert-success"><?php echo str_replace('_CONFIG_FILE_', EI_CONFIG_FILE_PATH, lang_key('file_successfully_created')); ?></div>
							<div class="alert alert-warning"><?php echo lang_key('alert_remove_files'); ?></div>
							<?php echo (EI_POST_INSTALLATION_TEXT != '') ? '<div class="alert alert-info">'.EI_POST_INSTALLATION_TEXT.'</div>' : ''; ?>
							<br /><br />
							<?php if(EI_APPLICATION_START_FILE == '') { ?><a class="form_button" href="<?php echo '../'.EI_APPLICATION_START_FILE;?>"><?php echo lang_key('proceed_to_login_page'); ?></a><?php } ?>
							&nbsp;&nbsp;&nbsp;
							<a class="form_button" href="<?php echo '../../'; ?>">Aller sur votre site</a>
						</td>
					</tr>															
				<?php } ?>
			<?php } ?>
			</tbody>
			</table>
			<br>

			<?php
				if(EI_ALLOW_START_ALL_OVER && $completed){
					echo '<h3>'.lang_key('start_all_over').'</h3>';
					echo '<p>'.lang_key('start_all_over_text').'</p>';
					echo '<form action="start.php" method="post">';
					echo '<input type="hidden" name="task" value="start_over" />';
					echo '<input type="hidden" name="token" value="'.$_SESSION['token'].'" />';
					echo '<input type="submit" class="form_button" name="btnSubmit" value="'.lang_key('remove_configuration_button').'" />';
				}
			?>			
			
		</div>
		<div class="clear"></div>
	</div>
	
	<?php include_once('include/footer.inc.php'); ?>        

</div>
</body>
</html>