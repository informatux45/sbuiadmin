<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP EasyInstaller Pro version                                            #
##  Developed by:  ApPHP <info@apphp.com>                                      #
##  License:       GNU LGPL v.3                                                #
##  Site:          http://www.apphp.com/php-easyinstaller/                     #
##  Copyright:     ApPHP EasyInstaller (c) 2009-2013. All rights reserved.     #
##                                                                             #
################################################################################

	session_start();
	
	require_once('include/shared.inc.php');    
    require_once('include/settings.inc.php');    
	require_once('include/functions.inc.php');
	require_once('include/languages.inc.php');	

	$task = isset($_POST['task']) ? prepare_input($_POST['task']) : '';
	$passed_step = isset($_SESSION['passed_step']) ? (int)$_SESSION['passed_step'] : 0;
	$focus_field = 'settings_customer_name';
	$error_msg = '';
	
	// handle previous steps
	// -------------------------------------------------
	if($passed_step >= 3){
		// OK
	}else{
		header('location: start.php');
		exit;				
	}
	
	// handle form submission
	// -------------------------------------------------
	if($task == 'send'){

		// skip this step if admin account is not required
		if(!EI_USE_ADMIN_ACCOUNT){
			$_SESSION['settings_customer_name']     = '';
			$_SESSION['settings_customer_url']      = '';
			$_SESSION['settings_url_upload']        = '';
			$_SESSION['settings_path_upload']       = '';
			$_SESSION['settings_recaptcha_public']  = '';
			$_SESSION['settings_recaptcha_private'] = '';
			$_SESSION['password_encryption']        = '';
			
			$_SESSION['passed_step'] = 4;
			header('location: ready_to_install.php');
			exit;
		}

		$settings_customer_name = isset($_POST['settings_customer_name']) ? prepare_input($_POST['settings_customer_name']) : '';
		$settings_customer_url = isset($_POST['settings_customer_url']) ? prepare_input($_POST['settings_customer_url'], false, 'medium') : '';
		$settings_url_upload = isset($_POST['settings_url_upload']) ? prepare_input($_POST['settings_url_upload'], false, 'medium') : '';
		$settings_path_upload = isset($_POST['settings_path_upload']) ? prepare_input($_POST['settings_path_upload']) : '';
		$settings_recaptcha_public = isset($_POST['settings_recaptcha_public']) ? prepare_input($_POST['settings_recaptcha_public']) : '';
		$settings_recaptcha_private = isset($_POST['settings_recaptcha_private']) ? prepare_input($_POST['settings_recaptcha_private']) : '';
		
		$password_encryption = isset($_POST['password_encryption']) ? prepare_input($_POST['password_encryption']) : EI_PASSWORD_ENCRYPTION_TYPE;

		// validation here
		// -------------------------------------------------
		if($settings_customer_name == ''){
			$focus_field = 'settings_customer_name';
			$error_msg = lang_key('alert_settings_customer_name_empty');	
		}else if($settings_customer_url == ''){
			$focus_field = 'settings_customer_url';
			$error_msg = lang_key('alert_settings_customer_url_empty');				
		}else if($settings_url_upload == ''){
			$focus_field = 'settings_url_upload';
			$error_msg = lang_key('alert_settings_url_upload_wrong');				
		}else if($settings_path_upload == ''){
			$focus_field = 'settings_path_upload';
			$error_msg = lang_key('alert_settings_path_upload_wrong');	
		//}else if($settings_recaptcha_public == ''){
		//	$focus_field = 'settings_recaptcha_public';
		//	$error_msg = lang_key('alert_settings_recaptcha_public_wrong');	
		//}else if($settings_recaptcha_private == ''){
		//	$focus_field = 'settings_recaptcha_private';
		//	$error_msg = lang_key('alert_settings_recaptcha_private_wrong');	
		}else{

			if(EI_MODE == 'demo'){
				if($settings_customer_name != 'test' || $settings_customer_url != 'test'){
					$error_msg = lang_key('alert_wrong_testing_parameters');
				}
			}

			if(empty($error_msg)){
				$_SESSION['settings_customer_name'] = $settings_customer_name;
				$_SESSION['settings_customer_url'] = $settings_customer_url;
				$_SESSION['settings_url_upload'] = $settings_url_upload;
				$_SESSION['settings_path_upload']       = $settings_path_upload;
				$_SESSION['settings_recaptcha_public']  = $settings_recaptcha_public;
				$_SESSION['settings_recaptcha_private'] = $settings_recaptcha_private;
				$_SESSION['password_encryption'] = $password_encryption;				

				$_SESSION['passed_step'] = 4;
				header('location: ready_to_install.php');
				exit;
			}
		}
	} else {
		// --- Initialization
        $current_url          =  ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $sbuiadmin_url        = str_replace('backdoor/install/administrator_account.php', '', $current_url);
		$sbuiadmin_url_upload = $sbuiadmin_url . 'upload/';
		// Url upload
		$settings_url_upload = isset($_SESSION['settings_url_upload']) ? $_SESSION['settings_url_upload'] : $sbuiadmin_url_upload;
		// Nom du client
		$settings_customer_name = isset($_SESSION['settings_customer_name']) ? $_SESSION['settings_customer_name'] : '';
		// Url du site client
		$settings_customer_url = isset($_SESSION['settings_customer_url']) ? $_SESSION['settings_customer_url'] : $sbuiadmin_url;
		// -----------
		$settings_path_upload = isset($_SESSION['settings_path_upload']) ? $_SESSION['settings_path_upload'] : '../upload';
		$settings_recaptcha_public = isset($_SESSION['settings_recaptcha_public']) ? $_SESSION['settings_recaptcha_public'] : '';
		$settings_recaptcha_private = isset($_SESSION['settings_recaptcha_private']) ? $_SESSION['settings_recaptcha_private'] : '';
		
		$password_encryption = isset($_SESSION['password_encryption']) ? $_SESSION['password_encryption'] : EI_PASSWORD_ENCRYPTION_TYPE;
		$install_type = isset($_SESSION['install_type']) ? $_SESSION['install_type'] : '';
		
		// skip administrator settings
		if($install_type == 'un-install'){
			$_SESSION['passed_step'] = 3;
			header('location: database_settings.php');					
		}
	}

	// --- Header INC
	include_once('include/header.inc.php');
	
?>	
<body onload="bodyOnLoad()">
<div id="main">
	<h1><?php echo lang_key('new_installation_of'); ?> <?php echo EI_APPLICATION_NAME.' '.EI_APPLICATION_VERSION;?>!</h1>
	<h2 class="sub-title"><?php echo lang_key('sub_title_message'); ?></h2>
	
	<div id="content">
		<?php
			draw_side_navigation(4);		
		?>
		<div class="central-part">
			<h2><?php echo lang_key('step_4_of'); ?> - <?php echo lang_key('administrator_account'); ?></h2>
			<h3><?php echo lang_key('admin_access_data'); ?></h3>

			<form action="administrator_account.php" method="post">
			<input type="hidden" name="task" value="send" />
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			
			<?php
				if(!empty($error_msg)){
					echo '<div class="alert alert-error">'.$error_msg.'</div>';
				}
			?>

			<table width="100%" border="0" cellspacing="1" cellpadding="1">
			<?php if(EI_USE_ADMIN_ACCOUNT){ ?>
			<tr>
				<td colspan="3"><span class="star">*</span> <?php echo lang_key('alert_required_fields'); ?></td>
			</tr>
			<tr><td nowrap height="10px" colspan="3"></td></tr>
			<tr>
				<td width="250px">&nbsp;<?php echo lang_key('settings_customer_name'); ?>&nbsp;<span class="star">*</span></td>
				<td><input name="settings_customer_name" id="settings_customer_name" class="form_text" size="28" value="<?php echo $settings_customer_name; ?>" onfocus="textboxOnFocus('notes_settings_customer_name')" onblur="textboxOnBlur('notes_settings_customer_name')" <?php if(EI_MODE != 'debug') echo 'autocomplete="off"'; ?> placeholder="<?php if(EI_MODE == 'demo') echo 'demo: test'; ?>" required="" /></td>
				<td rowspan="6" valign="top">					
					<div id="notes_settings_url_upload" class="notes_container">
						<h4><?php echo lang_key('settings_url_upload'); ?></h4>
						<p><?php echo lang_key('settings_url_upload_info'); ?></p>
					</div>
					<div id="notes_settings_customer_name" class="notes_container">
						<h4><?php echo lang_key('settings_customer_name'); ?></h4>
						<p><?php echo lang_key('settings_customer_name_info'); ?></p>
					</div>
					<div id="notes_settings_customer_url" class="notes_container">
						<h4><?php echo lang_key('settings_customer_url'); ?></h4>
						<p><?php echo lang_key('settings_customer_url_info'); ?></p>
					</div>
					<div id="notes_settings_path_upload" class="notes_container">
						<h4><?php echo lang_key('settings_path_upload'); ?></h4>
						<p><?php echo lang_key('settings_path_upload_info'); ?></p>
					</div>
					<div id="notes_settings_recaptcha_public" class="notes_container">
						<h4><?php echo lang_key('settings_recaptcha_public'); ?></h4>
						<p><?php echo lang_key('settings_recaptcha_public_info'); ?></p>
					</div>
					<div id="notes_settings_recaptcha_private" class="notes_container">
						<h4><?php echo lang_key('settings_recaptcha_private'); ?></h4>
						<p><?php echo lang_key('settings_recaptcha_private_info'); ?></p>
					</div>
					<img class="loading_img" src="images/ajax_loading.gif" alt="<?php echo lang_key('loading'); ?>..." />
					<div id="notes_message" class="notes_container"></div>					
				</td>
			</tr>
			<tr>
				<td>&nbsp;<?php echo lang_key('settings_customer_url'); ?>&nbsp;<span class="star">*</span></td>
				<td><input name="settings_customer_url" id="settings_customer_url" class="form_text" type="text" size="28" value="<?php echo $settings_customer_url; ?>" onfocus="textboxOnFocus('notes_settings_customer_url')" onblur="textboxOnBlur('notes_settings_customer_url')" <?php if(EI_MODE != 'debug') echo 'autocomplete="off"'; ?> placeholder="<?php if(EI_MODE == 'demo') echo 'demo: test'; ?>" required="" /></td>
			</tr>
			<tr>
				<td>&nbsp;<?php echo lang_key('settings_url_upload'); ?>&nbsp;<span class="star">*</span></td>
				<td><input name="settings_url_upload" id="settings_url_upload" class="form_text" size="28" value="<?php echo $settings_url_upload; ?>" onfocus="textboxOnFocus('notes_settings_url_upload')" onblur="textboxOnBlur('notes_settings_url_upload')" <?php if(EI_MODE != 'debug') echo 'autocomplete="off"'; ?> required="" /></td>
			</tr>
			<tr>
				<td>&nbsp;<?php echo lang_key('settings_path_upload'); ?>&nbsp;<span class="star">*</span></td>
				<td><input name="settings_path_upload" id="settings_path_upload" class="form_text" size="28" value="<?php echo $settings_path_upload; ?>" onfocus="textboxOnFocus('notes_settings_path_upload')" onblur="textboxOnBlur('notes_settings_path_upload')" <?php if(EI_MODE != 'debug') echo 'autocomplete="off"'; ?> required="" /></td>
			</tr>
			<tr>
				<td>&nbsp;<?php echo lang_key('settings_recaptcha_public'); ?></td>
				<td><input name="settings_recaptcha_public" id="settings_recaptcha_public" class="form_text" size="28" value="<?php echo $settings_recaptcha_public; ?>" onfocus="textboxOnFocus('notes_settings_recaptcha_public')" onblur="textboxOnBlur('notes_settings_recaptcha_public')" <?php if(EI_MODE != 'debug') echo 'autocomplete="off"'; ?> /></td>
			</tr>
			<tr>
				<td>&nbsp;<?php echo lang_key('settings_recaptcha_private'); ?></td>
				<td><input name="settings_recaptcha_private" id="settings_recaptcha_private" class="form_text" size="28" value="<?php echo $settings_recaptcha_private; ?>" onfocus="textboxOnFocus('notes_settings_recaptcha_private')" onblur="textboxOnBlur('notes_settings_recaptcha_private')" <?php if(EI_MODE != 'debug') echo 'autocomplete="off"'; ?> /></td>
			</tr>
				<?php if(EI_USE_PASSWORD_ENCRYPTION){ ?>
				<tr>
					<td>&nbsp;<?php echo lang_key('password_encryption'); ?>&nbsp;</td>
					<td>
						<select class="form_select" name="password_encryption" id="password_encryption">
						<option <?php echo (($password_encryption == 'AES') ? 'selected="selected"' : ''); ?> value="AES">AES</option>
						<option <?php echo (($password_encryption == 'MD5') ? 'selected="selected"' : ''); ?> value="MD5">MD5</option>
						</select>
					</td>
				</tr>							
				<?php } ?>
			<?php }else{ ?>
				<tr><td colspan="2"><?php echo lang_key('administrator_account_skipping'); ?></td></tr>			
			<?php } ?>
			<tr><td colspan="2" nowrap height="50px">&nbsp;</td></tr>
			<tr>
				<td colspan="2">
					<a href="database_settings.php" class="form_button" /><?php echo lang_key('back'); ?></a>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" class="form_button" value="<?php echo lang_key('continue'); ?>" />
				</td>
			</tr>                        
			</table>
			</form>                        
		</div>
		<div class="clear"></div>
	</div>
	
	<?php include_once('include/footer.inc.php'); ?>        

</div>

<script type="text/javascript">
	function bodyOnLoad(){
		setFocus("<?php echo $focus_field; ?>");
	}	
</script>
</body>
</html>
