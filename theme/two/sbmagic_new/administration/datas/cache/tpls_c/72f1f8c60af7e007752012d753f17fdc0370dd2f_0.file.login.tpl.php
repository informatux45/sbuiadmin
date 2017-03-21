<?php
/* Smarty version 3.1.29, created on 2016-12-20 14:40:49
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/login.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_585934e13f8532_47939571',
  'file_dependency' => 
  array (
    '72f1f8c60af7e007752012d753f17fdc0370dd2f' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/login.tpl',
      1 => 1480929538,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:scripts.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_585934e13f8532_47939571 ($_smarty_tpl) {
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page'=>'login'), 0, false);
?>

	

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
			
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo @constant('_AM_SITE_CUSTOMER_NAME');?>
</h3>
                    </div>
                    <div class="panel-body">
						<?php if ($_smarty_tpl->tpl_vars['sbmagic_access_code']->value) {?>
							<div class="alert alert-danger">
							<?php if ($_smarty_tpl->tpl_vars['sbmagic_access_code']->value == 'E1') {?>
								<?php echo @constant('SBMAGIC_MSG_ERROR_E1');?>

							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['sbmagic_access_code']->value == 'E2') {?>
								<?php echo @constant('SBMAGIC_MSG_ERROR_E2');?>

							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['sbmagic_access_code']->value == 'E3') {?>
								<?php echo @constant('SBMAGIC_MSG_ERROR_E3');?>

							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['sbmagic_access_code']->value == 'E4') {?>
								<?php echo @constant('SBMAGIC_MSG_ERROR_E4');?>

							<?php }?>
							</div>
						<?php }?>
						<?php $_smarty_tpl->assign('get_browser' , insert_sbGetBrowser (array('ua' => ((string)$_SERVER['HTTP_USER_AGENT'])),$_smarty_tpl), true);?>
                        <form role="form" action="" method="post">
                            <fieldset>
								<?php if ($_smarty_tpl->tpl_vars['get_browser']->value == 'IE') {?>
									<img src="<?php echo @constant('_AM_SITE_URL');?>
img/noiexplorer.png" alt="" title="" style="margin-right: 15px; float: left; max-height: 100px;" />
									<span class="red" style="font-size: 1.2em; font-weight: bold;">Veuillez utiliser un navigateur Internet autre que Internet Explorer</span>
								<?php } else { ?>
									<div class="form-group">
										<input class="form-control" placeholder="Username" name="username" type="user" autofocus>
									</div>
									<div class="form-group">
										<input class="form-control" placeholder="Password" name="password" type="password" value="">
									</div>
									<div class="checkbox">
										<label>
											<input name="remember" type="checkbox" value="Remember Me">Remember Me
										</label>
									</div>
									<?php if (@constant('_AM_CAPTCHA_MODE')) {?>
									<div class="form-group">
										
										<div id="grecaptcha"></div>
										<?php echo '<script'; ?>
 src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=fr&remoteip=<?php echo $_SERVER['REMOTE_ADDR'];?>
" async defer><?php echo '</script'; ?>
>
										
									</div>
									<?php }?>
									<!-- Change this to a button or input when using this as a form -->
									<input type="submit" value="Login" class="btn btn-lg btn-success btn-block">
								<?php }?>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		
	<!-- ------------------------------------------------------------ -->
	<!-- Page-Level Scripts - Use this space this write your own code -->
	<!-- ------------------------------------------------------------ -->
	<?php echo '<script'; ?>
>
	$(document).ready(function() {
		// Your own code

	});
	<?php echo '</script'; ?>
>
	
	<?php if (@constant('_AM_CAPTCHA_MODE') && $_smarty_tpl->tpl_vars['get_browser']->value != 'IE') {?>
	<?php echo '<script'; ?>
 type="text/javascript">
		var onloadCallback = function() {
			grecaptcha.render('grecaptcha', {
				'sitekey' : '<?php echo $_smarty_tpl->tpl_vars['grecaptcha_publickey']->value;?>
',
				'theme' : 'light', // light, dark
				'type' : 'image', // image, audio
				'size' : 'normal', // normal, compact
				'tabindex' : 0
			});
		};
	<?php echo '</script'; ?>
>
	<?php }?>

<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:scripts.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('page'=>'login'), 0, false);
?>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
