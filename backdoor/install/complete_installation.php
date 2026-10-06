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

	// Meme nom de session que l'administration (inc/sbsession.php) : sinon la
	// session admin n'est jamais reconnue par inc-auth-guard.php.
	require_once(__DIR__ . '/../../inc/sbsession.php');
	session_start();

	// -------------------------------------------------------------------
	// Bouton « Supprimer le répertoire install/ » de la dernière étape.
	// Traité AVANT inc-auth-guard.php : une fois installer.lock posé, le
	// garde exige une session admin, que l'installateur n'a pas encore. Le
	// droit vient d'un jeton à usage unique (30 min) créé à la fin d'une
	// installation réussie ; seule son empreinte est stockée, dans
	// installer/ (refusé au web).
	// -------------------------------------------------------------------
	if (isset($_POST['task']) && $_POST['task'] === 'sb_remove_install') {
		$sb_token_file = __DIR__ . '/installer/remove.token';
		$sb_stored     = @file($sb_token_file, FILE_IGNORE_NEW_LINES);
		$sb_given      = isset($_POST['sb_remove_token']) ? (string) $_POST['sb_remove_token'] : '';
		$sb_dir        = realpath(__DIR__);
		$sb_allowed    = $sb_stored && count($sb_stored) >= 2 && $sb_given !== ''
			&& hash_equals($sb_stored[0], hash('sha256', $sb_given))
			&& (time() - (int) $sb_stored[1]) < 1800
			&& file_exists(__DIR__ . '/installer/installer.lock')
			&& $sb_dir && basename($sb_dir) === 'install'
			&& is_file(dirname($sb_dir) . '/inc/sbuiadmin-settings.php');
		$sb_removed = false;
		if ($sb_allowed) {
			@unlink($sb_token_file);
			$sb_items = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sb_dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
			foreach ($sb_items as $sb_item) {
				// Liens symboliques supprimés eux-mêmes, jamais suivis
				($sb_item->isDir() && !$sb_item->isLink()) ? @rmdir($sb_item->getPathname()) : @unlink($sb_item->getPathname());
			}
			$sb_removed = @rmdir($sb_dir);
		}
		// Réponse JSON (bouton AJAX de la dernière étape)
		header('Content-Type: application/json; charset=utf-8');
		if ($sb_removed) {
			$sb_answer = array('ok' => true, 'message' => "Le répertoire install/ a été supprimé.");
		} elseif ($sb_allowed) {
			$sb_answer = array('ok' => false, 'message' => "Suppression incomplète : certains fichiers de install/ n'ont pu être supprimés (droits d'écriture). Supprimez le répertoire à la main.");
		} else {
			http_response_code(403);
			$sb_answer = array('ok' => false, 'message' => "Suppression refusée (lien expiré ou invalide) : supprimez le répertoire install/ à la main.");
		}
		echo json_encode($sb_answer);
		exit;
	}

	require_once('inc-auth-guard.php');
	
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

		// --- Premier compte admin : identifiant + HASH posés à l'étape 4.
		// --- Vérifié AVANT de créer les tables, pour ne jamais laisser une
		// --- installation avec un compte admin sans mot de passe choisi.
		$admin_username      = isset($_SESSION['admin_username']) ? (string)$_SESSION['admin_username'] : '';
		$admin_password_hash = isset($_SESSION['admin_password_hash']) ? (string)$_SESSION['admin_password_hash'] : '';
		$sb_set_admin        = (EI_USE_ADMIN_ACCOUNT && $install_type != 'update' && $install_type != 'un-install');
		if ($sb_set_admin) {
			$admin_hash_info = password_get_info($admin_password_hash);
			if (!preg_match('/^[A-Za-z0-9_.@-]{3,50}$/', $admin_username) || empty($admin_hash_info['algo']) || !preg_match('/^[A-Za-z0-9_]*$/', $database_prefix)) {
				$error_mg[] = lang_key('alert_admin_password_missing');
			}
		}

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

								// --- Compte admin n°1 : identifiant et mot de passe choisis à
								// --- l'étape 4 (le dump ne contient plus de mot de passe).
								// --- Valeurs sûres en SQL : identifiant filtré par regex, hash
								// --- password_hash() limité à [./$A-Za-z0-9,=+-].
								$sb_admin_failed = false;
								if ($sb_set_admin) {
									$sb_admin_sql = "UPDATE `" . $database_prefix . "sb_users` SET `username` = '" . $admin_username . "', `password` = '" . str_replace("'", '', $admin_password_hash) . "' WHERE `id` = 1";
									if (!$db->Query($sb_admin_sql)) {
										$error_mg[] = lang_key('alert_admin_password_missing') . (EI_MODE == 'debug' ? ' ' . $db->Error() : '');
										$sb_admin_failed = true;
									}
								}
								// Sans compte admin utilisable, on ne finalise PAS (fichiers de
								// réglages, suppression de install.php) : l'installation reste
								// relançable au lieu d'aboutir à un back-office sans admin.
								if (!$sb_admin_failed) {
								
								# One level up
								$settings_file  = EI_CONFIG_FILE_PATH;
								$dashboard_file = EI_CONFIG_DASHBOARD_FILE_PATH;
								$htaccess_file  = EI_CONFIG_FILE_HTACCESS;
								$install_file   = EI_CONFIG_FILE_INSTALL_START;
								//clearstatcache(true);
								//$install_file   = getcwd() . '/' . EI_CONFIG_FILE_INSTALL_START;
								$installer_lock = EI_CONFIG_FILE_INSTALLER_LOCK;
								
								// Réglages : accès base + clé de chiffrement dans sbdbconfig.php
								// (au-dessus du site si possible, sinon à sa racine), le reste
								// dans la table sb_settings. Plus rien dans settings.txt.
								// Voir inc/sbuiadmin-settings.php.
								require_once(dirname(__DIR__) . '/inc/sbuiadmin-settings.php');
								$sb_install_ok = true;
								if ($install_type != 'un-install') {
									$sb_db_existing = sbDbConfig(true);
									$sb_db_new = array(
										'host'     => (string) $_SESSION['database_host'],
										'name'     => (string) $_SESSION['database_name'],
										'user'     => (string) $_SESSION['database_username'],
										'password' => (string) $_SESSION['database_password'],
										'prefix'   => (string) $_SESSION['database_prefix'],
										// Mise à jour : même clé, sinon les secrets déjà chiffrés
										// en base deviendraient illisibles
										'key'      => ($install_type == 'update' && $sb_db_existing['source'] !== 'legacy') ? $sb_db_existing['key'] : '',
									);
									$sb_dbconfig_file = sbDbConfigWrite($sb_db_new);
									if (!$sb_dbconfig_file) {
										$sb_install_ok = false;
										$error_mg[] = "<b>Erreur :</b> impossible d'écrire sbdbconfig.php. Rendez inscriptible le dossier <code>" . htmlspecialchars(dirname(dirname(__DIR__, 2))) . "</code> (recommandé, hors du site) ou <code>" . htmlspecialchars(dirname(__DIR__, 2)) . "</code>, puis relancez l'installation.";
									} else {
										sbDbConfig(true);
										sbSettingsDb(true);
										$sb_install_settings = array(
											'customer_name'         => $_SESSION['settings_customer_name'],
											'administrators'        => ($sb_set_admin && $admin_username !== '') ? $admin_username : 'admin',
											'medias_dir'            => $_SESSION['settings_path_upload'],
											'upload_size_limit'     => '2MB',
											'modules'               => '',
											'debug_admin'           => '0',
											'debug_form'            => '0',
											'debug_smarty_admin'    => '0',
											'upload_exts'           => 'jpg,jpeg,png,gif,webp,pdf,mp4',
											'medias_url'            => $_SESSION['settings_url_upload'],
											'upload_item_limit'     => '20',
											'site_url'              => $_SESSION['settings_customer_url'],
											'sandbox'               => '1',
											'cms'                   => '1',
											'scaling_maxsize'       => '1024',
											'recaptcha_public'      => $_SESSION['settings_recaptcha_public'],
											'recaptcha_secret'      => $_SESSION['settings_recaptcha_private'],
											'captcha_mode'          => '0',
											'upgrade_mode'          => '0',
											'maintenance'           => '1',
											'debug_front'           => '0',
											'debug_smarty_front'    => '0',
											'smarty_force_compile'  => '1',
											'rewrite_url'           => '0',
											'smarty_caching'        => '0',
											'smarty_cache_lifetime' => '3600',
											'medias_per_page'       => '24',
											'flood_enabled'         => '0',
											'flood_expiration'      => '86400',
											'flood_login_delay'     => '4',
											'toast_duration'        => '7',
											'pagebuilder_modules'   => '',
										);
										// Mise à jour : ne complète que les réglages absents
										if (!sbSettingsSave($sb_install_settings, $install_type == 'update')) {
											$sb_install_ok = false;
											$error_mg[] = "<b>Erreur :</b> impossible d'enregistrer les réglages dans la table " . htmlspecialchars(sbSettingsTable()) . ".";
										} else {
											// Un ancien settings.txt rempli serait re-migré par-dessus
											// au premier chargement : on le vide.
											@file_put_contents($settings_file, '', LOCK_EX);
										}
									}
								}

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

								# Nom de la session PHP : tire au sort pour CETTE installation.
								# Sans lui, toutes les installations issues de la meme archive
								# partageraient le cookie PHPSESSID par defaut et, servies par un
								# meme domaine, ecraseraient mutuellement l'identite de
								# l'administrateur connecte (voir inc/sbsession.php).
								# On supprime d'abord un eventuel fichier herite d'une
								# installation precedente : une nouvelle installation doit
								# repartir sur un nom neuf.
								$session_name_file = dirname(__DIR__, 2) . '/inc/sbsession.txt';
								@unlink($session_name_file);
								require_once(dirname(__DIR__, 2) . '/inc/sbsession.php');

								# Lock the installer (pas si les réglages n'ont pu être écrits :
								# l'installation doit rester relançable)
								if ($sb_install_ok) @file_put_contents($installer_lock, "installer lock file");

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
								
								} // fin if (!$sb_admin_failed)

								$set_errors = array_keys( $error_mg, true );
								if (!$set_errors) {
									$completed = true;
									session_destroy();
									// Jeton du bouton « Supprimer le répertoire install/ »
									$sb_remove_token = bin2hex(random_bytes(16));
									if (!@file_put_contents(__DIR__ . '/installer/remove.token', hash('sha256', $sb_remove_token) . "\n" . time() . "\n", LOCK_EX)) {
										$sb_remove_token = '';
									}
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
							<?php if (!empty($sb_remove_token)) { ?>
							<div class="sb-remove-install" style="margin: 10px 0;">
								<button type="button" class="form_button" data-token="<?php echo htmlspecialchars($sb_remove_token); ?>" onclick="sbRemoveInstall(this)">Supprimer le répertoire install/ maintenant</button>
								<div class="sb-remove-install-result alert" style="display: none; margin-top: 10px;"></div>
							</div>
							<?php } ?>
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
							<?php if (!empty($sb_remove_token)) { ?>
							<div class="sb-remove-install" style="margin: 10px 0;">
								<button type="button" class="form_button" data-token="<?php echo htmlspecialchars($sb_remove_token); ?>" onclick="sbRemoveInstall(this)">Supprimer le répertoire install/ maintenant</button>
								<div class="sb-remove-install-result alert" style="display: none; margin-top: 10px;"></div>
							</div>
							<?php } ?>
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

			<?php if (!empty($sb_remove_token)) { ?>
			<script>
			// Suppression de install/ en AJAX (voir le haut de ce fichier)
			function sbRemoveInstall(btn) {
				if (!confirm('Supprimer définitivement le répertoire install/ ?')) return;
				var box = btn.parentNode.querySelector('.sb-remove-install-result');
				var body = new FormData();
				body.append('task', 'sb_remove_install');
				body.append('sb_remove_token', btn.getAttribute('data-token'));
				btn.disabled = true;
				fetch('complete_installation.php', {method: 'POST', body: body, credentials: 'same-origin'})
					.then(function (r) { return r.json(); })
					.then(function (j) {
						box.className = 'sb-remove-install-result alert ' + (j.ok ? 'alert-success' : 'alert-danger');
						box.textContent = j.message;
						box.style.display = 'block';
						if (j.ok) {
							btn.style.display = 'none';
							var warn = document.querySelectorAll('.alert-warning');
							for (var i = 0; i < warn.length; i++) warn[i].style.display = 'none';
						} else {
							btn.disabled = false;
						}
					})
					.catch(function () {
						box.className = 'sb-remove-install-result alert alert-danger';
						box.textContent = "Erreur réseau : supprimez le répertoire install/ à la main.";
						box.style.display = 'block';
						btn.disabled = false;
					});
			}
			</script>
			<?php } ?>
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