{* -------------- *}
{* --- SYSTEM --- *}
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='header.tpl' page='login'}
	{* ---------------- End Headers --------------- *}

	<div class="auth-shell">
		<aside class="auth-aside">
			<div class="auth-brand">
				<div class="logo"><img src="{$smarty.const._AM_SITE_URL}img/icon-sbuiadmin-w.png" alt="{$smarty.const._AM_SITE_CUSTOMER_NAME}" style="max-width: 32px; max-height: 32px;"></div>
				<div class="name">{$smarty.const._AM_SITE_CUSTOMER_NAME}</div>
			</div>
			<div class="auth-aside-body">
				<span class="auth-aside-eyebrow">Administration</span>
				<h1>Gérez votre site en toute simplicité.</h1>
				<p>Contenus, utilisateurs, médias et paramètres&nbsp;: tout votre back-office SBUIADMIN au même endroit.</p>
			</div>
			<div class="auth-aside-footer"><span>&copy; {$smarty.now|date_format:"%Y"}</span> <span>SBUIADMIN v{$smarty.const._AM_START_VERSION}</span></div>
		</aside>

		<main class="auth-main">
			<div class="auth-main-top">
				<a href="{$smarty.const._AM_SITE_URL}" style="font-size:12.5px;color:var(--t-muted);display:inline-flex;align-items:center;gap:6px">
					<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
					Retour au site
				</a>
			</div>

			<div class="auth-card">
				<h2>Connexion</h2>
				<p class="sub">Identifiez-vous pour accéder à l'administration.</p>

				{if $sbuiadmin_access_code}
					<div class="alert danger">
						<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span>
						<div class="body">
							{if $sbuiadmin_access_code == 'E1'}
								{$smarty.const.SBUIADMIN_MSG_ERROR_E1}
							{elseif $sbuiadmin_access_code == 'E2'}
								{$smarty.const.SBUIADMIN_MSG_ERROR_E2}
							{elseif $sbuiadmin_access_code == 'E3'}
								{$smarty.const.SBUIADMIN_MSG_ERROR_E3}
							{elseif $sbuiadmin_access_code == 'E4'}
								{$smarty.const.SBUIADMIN_MSG_ERROR_E4}
							{/if}
						</div>
					</div>
				{/if}

				{insert name=sbGetBrowser assign=get_browser ua="`$smarty.server.HTTP_USER_AGENT`"}

				{if $get_browser == 'IE'}
					<div class="alert warning">
						<span class="ico"><svg viewBox="0 0 24 24"><path d="M12 9v4M12 17h.01"/><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg></span>
						<div class="body">
							<div class="title">Navigateur non supporté</div>
							Veuillez utiliser un navigateur autre qu'Internet Explorer pour accéder à l'administration.
						</div>
					</div>
				{else}
					<form id="sbuiadmin-login" class="auth-form" action="" method="post">
						<div class="field">
							<label class="field-label" for="username">Nom d'utilisateur</label>
							<div class="input-icon">
								<span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.5-6 8-6s8 2 8 6"/></svg></span>
								<input id="username" class="input" placeholder="Nom d'utilisateur" name="username" type="text" autocomplete="username" autofocus required>
							</div>
						</div>
						<div class="field">
							<label class="field-label" for="password">Mot de passe</label>
							<div class="input-icon">
								<span class="ico"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
								<input id="password" class="input" placeholder="Mot de passe" name="password" type="password" value="" autocomplete="current-password" required>
							</div>
						</div>
						<label class="check">
							<input name="remember" type="checkbox" value="longtime" id="switch"> <span class="box"></span> Se souvenir de moi
						</label>

						{if $smarty.const._AM_CAPTCHA_MODE}
							<button
								type="submit"
								class="btn btn--primary auth-submit g-recaptcha"
								data-sitekey="{$grecaptcha_publickey}"
								data-callback="sbLoginOnSubmit">
								Connexion
								<svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
							</button>
						{else}
							<button type="submit" class="btn btn--primary auth-submit">
								Connexion
								<svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
							</button>
						{/if}
					</form>

					{if $smarty.const._AM_CAPTCHA_MODE}
						{* Google Invisible ReCaptcha *}
						<script src="https://www.google.com/recaptcha/api.js?hl=fr&remoteip={$smarty.server.REMOTE_ADDR}" async defer></script>
						{* ================ *}
					{/if}
				{/if}
			</div>

			<div class="auth-main-bottom">{$smarty.const._AM_SITE_CUSTOMER_NAME} &middot; propulsé par SBUIADMIN</div>
		</main>
	</div>

	{if $smarty.const._AM_CAPTCHA_MODE && $get_browser != 'IE'}
	<script type="text/javascript">
       function sbLoginOnSubmit(token) {
         document.getElementById("sbuiadmin-login").submit();
       }
	</script>
	{/if}

{include file='scripts.tpl' page='login' pagef='false'}

{include file='footer.tpl'}
