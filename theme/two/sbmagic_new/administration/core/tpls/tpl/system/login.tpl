{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='header.tpl' page='login'}
	{* ---------------- End Headers --------------- *}

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
			
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{$smarty.const._AM_SITE_CUSTOMER_NAME}</h3>
                    </div>
                    <div class="panel-body">
						{if $sbmagic_access_code}
							<div class="alert alert-danger">
							{if $sbmagic_access_code == 'E1'}
								{$smarty.const.SBMAGIC_MSG_ERROR_E1}
							{/if}
							{if $sbmagic_access_code == 'E2'}
								{$smarty.const.SBMAGIC_MSG_ERROR_E2}
							{/if}
							{if $sbmagic_access_code == 'E3'}
								{$smarty.const.SBMAGIC_MSG_ERROR_E3}
							{/if}
							{if $sbmagic_access_code == 'E4'}
								{$smarty.const.SBMAGIC_MSG_ERROR_E4}
							{/if}
							</div>
						{/if}
						{insert name=sbGetBrowser assign=get_browser ua="`$smarty.server.HTTP_USER_AGENT`"}
                        <form role="form" action="" method="post">
                            <fieldset>
								{if $get_browser == 'IE'}
									<img src="{$smarty.const._AM_SITE_URL}img/noiexplorer.png" alt="" title="" style="margin-right: 15px; float: left; max-height: 100px;" />
									<span class="red" style="font-size: 1.2em; font-weight: bold;">Veuillez utiliser un navigateur Internet autre que Internet Explorer</span>
								{else}
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
									{if $smarty.const._AM_CAPTCHA_MODE}
									<div class="form-group">
										{* Google ReCaptcha *}
										<div id="grecaptcha"></div>
										<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=fr&remoteip={$smarty.server.REMOTE_ADDR}" async defer></script>
										{* ================ *}
									</div>
									{/if}
									<!-- Change this to a button or input when using this as a form -->
									<input type="submit" value="Login" class="btn btn-lg btn-success btn-block">
								{/if}
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		
	<!-- ------------------------------------------------------------ -->
	<!-- Page-Level Scripts - Use this space this write your own code -->
	<!-- ------------------------------------------------------------ -->
	<script>
	$(document).ready(function() {
		// Your own code

	});
	</script>
	
	{if $smarty.const._AM_CAPTCHA_MODE && $get_browser != 'IE'}
	<script type="text/javascript">
		var onloadCallback = function() {
			grecaptcha.render('grecaptcha', {
				'sitekey' : '{$grecaptcha_publickey}',
				'theme' : 'light', // light, dark
				'type' : 'image', // image, audio
				'size' : 'normal', // normal, compact
				'tabindex' : 0
			});
		};
	</script>
	{/if}

{include file='scripts.tpl' page='login'}

{include file='footer.tpl'}
