{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='header.tpl' page='login'}
	{* ---------------- End Headers --------------- *}
	
	{* ------------------------- *}
	{* ---       VIDEOS      --- *}
	{* --- Chaine INFORMATUX --- *}
	{* ------------------------- *}
	{* sM8BCNLo2pE : Foret       *}
	{* es86J41Du-Y : Particules  *}
	{* NY6xjlmFG7g : Plage       *}
	{* bmYcOEhIHjY : Travail     *}
	{* kyu_m1LYmaE : Feu de camp *}
	{* 0l3uuAQCgRQ : Taxis NY    *}
	{* LXBIv9XuXq0 : Pluie       *}
	{* 8p0RJSp-xkw : Mosaique    *}
	{* 5k4Y9FGKFTU : Plage 2     *}
	{* ------------------------- *}

    <div class="container" id="bgcontainer">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
			
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{$smarty.const._AM_SITE_CUSTOMER_NAME}</h3>
						 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" x="0px" y="0px" viewBox="0 0 48 48" class="icon sbuiadmin-logo" ><circle fill="#FFF59D" cx="24" cy="22" r="20"></circle><path fill="#FBC02D" d="M37,22c0-7.7-6.6-13.8-14.5-12.9c-6,0.7-10.8,5.5-11.4,11.5c-0.5,4.6,1.4,8.7,4.6,11.3	c1.4,1.2,2.3,2.9,2.3,4.8V37h12v-0.1c0-1.8,0.8-3.6,2.2-4.8C35.1,29.7,37,26.1,37,22z"></path><path fill="#FFF59D" d="M30.6,20.2l-3-2c-0.3-0.2-0.8-0.2-1.1,0L24,19.8l-2.4-1.6c-0.3-0.2-0.8-0.2-1.1,0l-3,2	c-0.2,0.2-0.4,0.4-0.4,0.7s0,0.6,0.2,0.8l3.8,4.7V37h2V26c0-0.2-0.1-0.4-0.2-0.6l-3.3-4.1l1.5-1l2.4,1.6c0.3,0.2,0.8,0.2,1.1,0	l2.4-1.6l1.5,1l-3.3,4.1C25.1,25.6,25,25.8,25,26v11h2V26.4l3.8-4.7c0.2-0.2,0.3-0.5,0.2-0.8S30.8,20.3,30.6,20.2z"></path><circle fill="#5C6BC0" cx="24" cy="44" r="3"></circle><path fill="#9FA8DA" d="M26,45h-4c-2.2,0-4-1.8-4-4v-5h12v5C30,43.2,28.2,45,26,45z"></path><g>	<path fill="#5C6BC0" d="M30,41l-11.6,1.6c0.3,0.7,0.9,1.4,1.6,1.8l9.4-1.3C29.8,42.5,30,41.8,30,41z"></path>	<polygon fill="#5C6BC0" points="18,38.7 18,40.7 30,39 30,37"></polygon></g></svg>
                    </div>
                    <div class="panel-body">
						{if $sbuiadmin_access_code}
							<div class="alert alert-danger">
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
                        <form id="sbuiadmin-login" role="form" action="" method="post">
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

									{if $smarty.const._AM_CAPTCHA_MODE}
									<div class="form-group">
										{* Google Invisible ReCaptcha *}
										<script src="https://www.google.com/recaptcha/api.js?hl=fr&remoteip={$smarty.server.REMOTE_ADDR}" async defer></script>
										{* ================ *}
									</div>
									{/if}
									<!-- Change this to a button or input when using this as a form -->
									
									{if $smarty.const._AM_CAPTCHA_MODE}
										<button
											type="submit"
											value="Login"
											class="g-recaptcha btn btn-outline btn-primary"
											data-sitekey="{$grecaptcha_publickey}"
											data-callback="sbLoginOnSubmit">
											<span class="glyphicon glyphicon-log-in"> Login</span>
										</button>
									{else}
										<button type="submit" value="Login" class="btn btn-outline btn-primary">
											<span class="glyphicon glyphicon-log-in"> Login</span>
										</button>
									{/if}
									<p class="cta">
										&laquo;&nbsp;<a href="{$smarty.const.SB_URL}">Back to Website</a>
										&nbsp; | &nbsp;
										<a href="#">Forgot your password?</a>&nbsp;&raquo;
									</p>
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
       function sbLoginOnSubmit(token) {
         document.getElementById("sbuiadmin-login").submit();
       }
	</script>
	{/if}

{include file='scripts.tpl' page='login'}

{include file='footer.tpl'}
