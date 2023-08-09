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
	require_once('include/functions.inc.php');
	require_once('include/languages.inc.php');	

	$task = isset($_POST['task']) ? prepare_input($_POST['task']) : '';
	$passed_step = isset($_SESSION['passed_step']) ? (int)$_SESSION['passed_step'] : 0;
	
	// handle previous steps
	// -------------------------------------------------
	if($passed_step >= 4){
		// OK
	}else{
		header('location: start.php');
		exit;				
	}

	// handle form submission
	// -------------------------------------------------
	if($task == 'send'){
		$_SESSION['passed_step'] = 5;
		header('location: complete_installation.php');
		exit;
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
			draw_side_navigation(5);		
		?>
		<div class="central-part">
			<h2><?php echo lang_key('step_5_of'); ?> - <?php echo lang_key('ready_to_install'); ?></h2>
			<h3><?php echo lang_key('we_are_ready_to_installation'); ?></h3>			
		
			<p><?php echo lang_key('we_are_ready_to_installation_text'); ?></p>			
		
			<form method="post" action="ready_to_install.php">
			<input type="hidden" name="task" value="send" />
			<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
			
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
			<tr><td nowrap height="10px" colspan="3"></td></tr>

			<tr><td colspan="2" nowrap height="20px">&nbsp;</td></tr>
			<tr>
				<td colspan="2">
					<a href="administrator_account.php" class="form_button" /><?php echo lang_key('back'); ?></a>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" id="form_submit_installation" class="form_button" value="<?php echo lang_key('continue'); ?>" />
					<img class="show_installation_in_progress" src='images/ajax_loading.gif' alt='' style="position: absolute; margin-top: -3px; visibility: hidden;" />
					&nbsp;<h3 class="show_installation_in_progress" style="display: inline; margin-left: 40px; visibility: hidden;"><?php echo lang_key('installation_in_progress'); ?></h3>
				</td>
			</tr>                        
			</table>
			</form>                        

		</div>
		<div class="clear"></div>
	</div>

	<script>	
		// Check if form submit
		$("form").submit(function() {
			$('#form_submit_installation').css("display", "none");
			$('.show_installation_in_progress').css("visibility", "visible");
		});
	</script>
	
	<?php include_once('include/footer.inc.php'); ?>        

</div>
</body>
</html>