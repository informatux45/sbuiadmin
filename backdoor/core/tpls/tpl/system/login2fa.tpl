{* -------------- *}
{* --- SYSTEM --- *}
{* -------------- *}
{* Double authentification : saisie du code reçu par e-mail (inc/sbuiadmin-2fa.php) *}

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
				<h1>Vérification en deux étapes.</h1>
				<p>Un code à usage unique vient d'être envoyé à l'adresse e-mail de votre compte.</p>
			</div>
			<div class="auth-aside-footer"><span>&copy; {$smarty.now|date_format:"%Y"}</span> <span>SBUIADMIN v{$smarty.const._AM_START_VERSION}</span></div>
		</aside>

		<main class="auth-main">
			<div class="auth-main-top">
				<a href="index.php?ac=logout" style="font-size:12.5px;color:var(--t-muted);display:inline-flex;align-items:center;gap:6px">
					<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
					Annuler
				</a>
			</div>

			<div class="auth-card">
				<h2>Code de connexion</h2>
				<p class="sub">Saisissez le code à {$sb2fa_length} chiffres envoyé à <strong>{$sb2fa_email|escape}</strong>. Il est valable {$sb2fa_minutes} minutes.</p>

				{if $sb2fa_error}
					<div class="alert danger">
						<span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg></span>
						<div class="body">{$sb2fa_error|escape}</div>
					</div>
				{elseif $sb2fa_info}
					<div class="alert success">
						<span class="ico"><svg viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg></span>
						<div class="body">{$sb2fa_info|escape}</div>
					</div>
				{/if}

				<form id="sbuiadmin-login-2fa" class="auth-form" action="index.php" method="post" autocomplete="off">
					<div class="field">
						<label class="field-label" for="sb2fa_code">Code reçu par e-mail</label>
						<div class="input-icon">
							<span class="ico"><svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
							<input id="sb2fa_code" class="input" placeholder="Code" name="sb2fa_code" type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]*" maxlength="{$sb2fa_length}" autofocus required>
						</div>
					</div>
					<button type="submit" class="btn btn--primary auth-submit">
						Valider
						<svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
					</button>
				</form>

				<p style="margin-top:16px;font-size:13px;text-align:center">
					<a href="index.php?sb2fa=resend">Renvoyer un code</a>
					&nbsp;·&nbsp;
					<a href="index.php?ac=logout">Annuler</a>
				</p>
			</div>
		</main>
	</div>

{include file='scripts.tpl' page='login' pagef='false'}

{include file='footer.tpl'}
