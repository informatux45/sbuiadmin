{* --- User Form --- *}

	{if $smarty.session.sbmagic_user_name == '' && $smarty.session.sbmagic_user_password == ''}
	
		<div class="panel-body">
			{if $sbmagic_access_code}
				<div class="alert alert-danger">
				{if $sbmagic_access_code == 'E1'}
					{$smarty.const._CMS_USER_MSG_ERROR_E1}
				{/if}
				{if $sbmagic_access_code == 'E2'}
					{$smarty.const._CMS_USER_MSG_ERROR_E2}
				{/if}
				{if $sbmagic_access_code == 'E3'}
					{$smarty.const._CMS_USER_MSG_ERROR_E3}
				{/if}
				{if $sbmagic_access_code == 'E4'}
					{$smarty.const._CMS_USER_MSG_ERROR_E4}
				{/if}
				</div>
			{/if}
			{insert name=sbGetBrowser assign=get_browser ua="`$smarty.server.HTTP_USER_AGENT`"}
			<form role="form" action="" method="post">
				<fieldset>
					{if $get_browser == 'IE'}
						<span class="red" style="font-size: 1.2em; font-weight: bold;">
							{$smarty.const._CMS_USER_NO_IE}
						</span>
					{else}
						<div class="form-group has-feedback has-feedback-left">
							<label class="control-label">{$smarty.const._CMS_USER_FORM_USERNAME}</label>
							<input type="user" name="username" class="form-control" placeholder="{$smarty.const._CMS_USER_FORM_USERNAME}" autofocus required>
							<i class="glyphicon glyphicon-user form-control-feedback-left"></i>
						</div>
						<div class="form-group has-feedback has-feedback-left">
							<label class="control-label">{$smarty.const._CMS_USER_FORM_PASSWORD}</label>
							<input class="form-control" placeholder="{$smarty.const._CMS_USER_FORM_PASSWORD}" name="password" type="password" value="" required>
							<i class="glyphicon glyphicon-lock form-control-feedback-left"></i>
						</div>
						<div class="checkbox">
							<label>
								&nbsp;<input name="remember" type="checkbox" value="remember_me"><span class="rememberme">{$smarty.const._CMS_USER_FORM_REMEMBER_ME}</span>
							</label>
						</div>
						{if $smarty.const._CMS_USER_CAPTCHA_MODE}
						<div class="form-group">
							{* Google ReCaptcha *}
							<div id="grecaptcha_user"></div>
							<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=fr&remoteip={$smarty.server.REMOTE_ADDR}" async defer></script>
							{* ================ *}
						</div>
						{/if}
						<!-- Change this to a button or input when using this as a form -->
						
						<button type="submit" value="Login" class="btn btn-outline btn-primary">
							<span class="glyphicon glyphicon-log-in"> Login</span>
						</button>
						<p class="cta">
							<a href="#">{$smarty.const._CMS_USER_FORM_FORGOT_PWD}</a>
						</p>
					{/if}
				</fieldset>
			</form>
		</div>
	
		<script>
			$(document).ready(function() {
				// Your own code
		
			});
		</script>
		
		{if $smarty.const._CMS_USER_CAPTCHA_MODE}
		{*if $smarty.const._CMS_USER_CAPTCHA_MODE && $get_browser != 'IE'*}
		<script type="text/javascript">
			var onloadCallback = function() {
				grecaptcha.render('grecaptcha_user', {
					'sitekey' : '{$grecaptcha_publickey}',
					'theme' : 'light', // light, dark
					'type' : 'image', // image, audio
					'size' : 'normal', // normal, compact
					'tabindex' : 0
				});
			};
		</script>
		{/if}
	
	{else}
		Profil de l'utilisateur ({$smarty.session.sbmagic_user_name})
		<br>
		<a href="{seo url="index.php?lang=`$smarty.session.lang`&p=user&ac=logout" rewrite="`$smarty.session.lang`/user/?ac=logout"}">
			Deconnexion
		</a>
	{/if}