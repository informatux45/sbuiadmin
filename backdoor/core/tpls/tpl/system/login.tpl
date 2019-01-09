{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='header.tpl' page='login'}
	{* ---------------- End Headers --------------- *}

    <div class="container" id="bgcontainer">
        <div class="row">
			{if $sbuiadmin_access_code}
				<div class="alert alert-danger alert-danger-custom">
				{if $sbuiadmin_access_code == 'E1'}
					{$smarty.const.SBUIADMIN_MSG_ERROR_E1}
				{/if}
				{if $sbuiadmin_access_code == 'E2'}
					{$smarty.const.SBUIADMIN_MSG_ERROR_E2}
				{/if}
				{if $sbuiadmin_access_code == 'E3'}
					{$smarty.const.SBUIADMIN_MSG_ERROR_E3}
				{/if}
				{if $sbuiadmin_access_code == 'E4'}
					{$smarty.const.SBUIADMIN_MSG_ERROR_E4}
				{/if}
				</div>
			{/if}
			{insert name=sbGetBrowser assign=get_browser ua="`$smarty.server.HTTP_USER_AGENT`"}
			
			{if $get_browser == 'IE'}
				<img src="{$smarty.const._AM_SITE_URL}img/noiexplorer.png" alt="" title="" style="margin-right: 15px; float: left; max-height: 100px;" />
				<span class="red" style="font-size: 1.2em; font-weight: bold;">Veuillez utiliser un navigateur Internet autre que Internet Explorer</span>
			{else}
				
				<div class="main">
					<div class="container">
						<center>
							<div class="logo">
								<img src="{$smarty.const._AM_SITE_URL}img/icon-sbuiadmin-w.png" alt="SBUIADMIN">
								<div class="clearfix"></div>
							</div>
							<div class="middle">
								<div id="login">
									<form id="sbuiadmin-login" role="form" action="" method="post">
										<fieldset class="clearfix">
											<p>
												<span class="fa fa-user"></span>
												<input placeholder="Username" name="username" type="text" autofocus required>
											</p>
											<p>
												<span class="fa fa-lock"></span>
												<input placeholder="Password" name="password" type="password" value="" required>
											</p>
											<p>
												<input name="remember" type="checkbox" value="longtime" id="switch"><label for="switch">Toggle</label>
												<span class="lblcheck">Se souvenir de moi</span>
											</p>
											<div>
												<span style="width: 48%; text-align:left;  display: inline-block;">
													<a class="small-text" href="#">Forgot password?</a>
												</span>
												<span style="width: 48%; text-align:right;  display: inline-block;">
													{if $smarty.const._AM_CAPTCHA_MODE}
														<button
															type="submit"
															value="Login"
															class="g-recaptcha"
															data-sitekey="{$grecaptcha_publickey}"
															data-callback="sbLoginOnSubmit">
															<span class="glyphicon glyphicon-log-in"> Login</span>
														</button>
													{else}
														<button type="submit" value="Login" class="">
															<span class="glyphicon glyphicon-log-in"> Login</span>
														</button>
													{/if}
												</span>
											</div>
										</fieldset>
										<div class="clearfix"></div>
									</form>
							
								</div> <!-- end login -->
							  
							</div>
						</center>
					</div>
				</div>

				{if $smarty.const._AM_CAPTCHA_MODE}
					{* Google Invisible ReCaptcha *}
					<script src="https://www.google.com/recaptcha/api.js?hl=fr&remoteip={$smarty.server.REMOTE_ADDR}" async defer></script>
					{* ================ *}
				{/if}

			{/if}
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
       function sbLoginOnSubmit(token) {
         document.getElementById("sbuiadmin-login").submit();
       }
	</script>
	{/if}

{include file='scripts.tpl' page='login'}

{include file='footer.tpl'}