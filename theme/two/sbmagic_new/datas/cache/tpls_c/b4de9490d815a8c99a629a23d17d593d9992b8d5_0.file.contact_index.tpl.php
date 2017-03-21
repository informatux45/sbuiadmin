<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:07
  from "/Applications/MAMP/htdocs/sbmagic_new/datas/modules/contact/tpls/contact_index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab33ca2433_16605514',
  'file_dependency' => 
  array (
    'b4de9490d815a8c99a629a23d17d593d9992b8d5' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/datas/modules/contact/tpls/contact_index.tpl',
      1 => 1475761699,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857ab33ca2433_16605514 ($_smarty_tpl) {
?>


			<div class="hentry group">

			     <form action="http://<?php echo $_SERVER['HTTP_HOST'];
echo $_SERVER['REQUEST_URI'];?>
" method="post" class="contact-form">
					<div class="usermessagea">
						<?php if ($_smarty_tpl->tpl_vars['errMsg']->value) {?>
							<div class="box alert-box">
								<?php echo $_smarty_tpl->tpl_vars['errMsg']->value;?>

							</div>
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['succMsg']->value) {?>
							<div class="box success-box">
								<?php echo $_smarty_tpl->tpl_vars['succMsg']->value;?>

							</div>
						<?php }?>
					</div>
					<fieldset>
						<ul>
							<li class="text-field">
								<label for="name-contact-us">
								<span class="label"><?php echo @constant('_CMS_CONTACT_FIELD_NAME');?>
</span>
								<br />					
								</label>
								<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input type="text" name="name" id="name-contact-us" value="<?php if (!$_smarty_tpl->tpl_vars['sendmailok']->value) {
echo $_POST['name'];
}?>" required></div>
								<div class="msg-error"></div>
							</li>
							<li class="text-field">
								<label for="email-contact-us">
								<span class="label"><?php echo @constant('_CMS_CONTACT_FIELD_EMAIL');?>
</span>
								<br />					
								</label>
								<div class="input-prepend"><span class="add-on"><i class="icon-envelope"></i></span><input type="text" name="email" id="email-contact-us" value="<?php if (!$_smarty_tpl->tpl_vars['sendmailok']->value) {
echo $_POST['email'];
}?>" required></div>
								<div class="msg-error"></div>
							</li>
							<li class="text-field">
								<label for="object-contact-us">
								<span class="label"><?php echo @constant('_CMS_CONTACT_FIELD_OBJECT');?>
</span>
								<br />					
								</label>
								<div class="input-prepend"><span class="add-on"><i class="icon-share"></i></span><input type="text" name="object" id="object-contact-us" class="<?php if (!$_smarty_tpl->tpl_vars['sendmailok']->value) {
echo $_POST['object'];
}?>" value="" required></div>
								
							</li>
							<li class="text-field">
								<label for="phone-contact-us">
								<span class="label"><?php echo @constant('_CMS_CONTACT_FIELD_PHONE');?>
</span>
								<br />					
								</label>
								<div class="input-prepend"><span class="add-on"><i class="icon-phone"></i></span><input type="text" name="phone" id="phone-contact-us" value="<?php if (!$_smarty_tpl->tpl_vars['sendmailok']->value) {
echo $_POST['phone'];
}?>" required></div>
								<div class="msg-error"></div>
							</li>
							<li class="textarea-field">
								<label for="message-contact-us">
								<span class="label"><?php echo @constant('_CMS_CONTACT_FIELD_MESSAGE');?>
</span>
								</label>
								<div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span><textarea name="message" id="message-contact-us" rows="8" cols="30" required><?php if (!$_smarty_tpl->tpl_vars['sendmailok']->value) {
echo $_POST['message'];
}?></textarea></div>
								<div class="msg-error"></div>
							</li>
							<li class="text-field">
								
								
								
								<div id="grecaptcha"></div>
								<?php echo '<script'; ?>
 src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=<?php if ($_SESSION['lang'] == 'en') {?>en<?php } else { ?>fr<?php }?>&remoteip=<?php echo $_SERVER['REMOTE_ADDR'];?>
" async defer><?php echo '</script'; ?>
>
								
							</li>
							<li class="submit-button">
								<input type="submit" name="submit" value="<?php echo @constant('_CMS_CONTACT_BUTTON_SEND');?>
" class="sendmail alignleft" />			
							</li>
						</ul>
					</fieldset>
			     </form>
						
						
				
				
				
				<?php echo '<script'; ?>
 type="text/javascript" src="datas/modules/contact/inc/jquery.validate.min.js"><?php echo '</script'; ?>
>
				
				
				
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
				


			</div>

<?php }
}
