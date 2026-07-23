{* -------------------------------------------------------------- *}
{* Shared hero + section dropdown for the "Configuration" family   *}
{* (settings, session, server, cache, dashboard, toolbarck, theme, *}
{* themeinfos). Bootstrap Samples excluded from the active states  *}
{* since it's an external link, not a page of its own.             *}
{* -------------------------------------------------------------- *}

{if !isset($smarty.get.p) || $smarty.get.p == 'settings'}
	{assign var="sh_title" value="Générale"}
	{assign var="sh_sub" value="Réglages généraux du site : base de données, uploads, sécurité, sandbox, CMS et cache."}
{elseif $smarty.get.p == 'session'}
	{assign var="sh_title" value="Session"}
	{assign var="sh_sub" value="Durée de vie et paramètres des sessions utilisateur."}
{elseif $smarty.get.p == 'server'}
	{assign var="sh_title" value="Serveur"}
	{assign var="sh_sub" value="Informations serveur en temps réel (PHP, base de données, extensions)."}
{elseif $smarty.get.p == 'cache'}
	{assign var="sh_title" value="Cache"}
	{assign var="sh_sub" value="Vider les caches FRONT et ADMIN du site."}
{elseif $smarty.get.p == 'dashboard'}
	{assign var="sh_title" value="Dashboard"}
	{assign var="sh_sub" value="Personnalisez les statuts affichés sur le tableau de bord."}
{elseif $smarty.get.p == 'toolbarck'}
	{assign var="sh_title" value="Toolbar CKEditor"}
	{assign var="sh_sub" value="Configuration de la barre d'outils de l'éditeur CKEditor."}
{elseif $smarty.get.p == 'theme'}
	{assign var="sh_title" value="Thème"}
	{assign var="sh_sub" value="Choisissez et activez le thème du site."}
{elseif $smarty.get.p == 'themeinfos'}
	{assign var="sh_title" value="Thème infos"}
	{assign var="sh_sub" value="Coordonnées et réseaux sociaux affichés par le thème."}
{else}
	{assign var="sh_title" value="Configuration"}
	{assign var="sh_sub" value="Réglages de l'administration."}
{/if}

<section class="hero">
	<div class="hero-text">
		<span class="eyebrow">Configuration</span>
		<h1 class="hero-title">{$sh_title}</h1>
		<p class="hero-sub">{$sh_sub}</p>
	</div>
	<div class="hero-actions">
		<div class="dd-wrap">
			<button class="btn btn--outline-primary" data-dropdown aria-label="Sections de configuration">
				{$sh_title}
				<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
			</button>
			<div class="dd-menu" role="menu">
				<a class="dd-menu-item" href="index.php?p=settings"{if !isset($smarty.get.p) || $smarty.get.p == 'settings'} style="color:var(--primary);font-weight:600"{/if}>Générale</a>
				<a class="dd-menu-item" href="index.php?p=session"{if $smarty.get.p == 'session'} style="color:var(--primary);font-weight:600"{/if}>Session</a>
				<a class="dd-menu-item" href="index.php?p=server"{if $smarty.get.p == 'server'} style="color:var(--primary);font-weight:600"{/if}>Serveur</a>
				<a class="dd-menu-item" href="index.php?p=cache"{if $smarty.get.p == 'cache'} style="color:var(--primary);font-weight:600"{/if}>Cache</a>
				<a class="dd-menu-item" href="index.php?p=dashboard"{if $smarty.get.p == 'dashboard'} style="color:var(--primary);font-weight:600"{/if}>Dashboard</a>
				<a class="dd-menu-item" href="index.php?p=toolbarck"{if $smarty.get.p == 'toolbarck'} style="color:var(--primary);font-weight:600"{/if}>Toolbar CKEditor</a>
				<a class="dd-menu-item" href="index.php?p=theme"{if $smarty.get.p == 'theme'} style="color:var(--primary);font-weight:600"{/if}>Thème</a>
				<a class="dd-menu-item" href="index.php?p=themeinfos"{if $smarty.get.p == 'themeinfos'} style="color:var(--primary);font-weight:600"{/if}>Thème infos</a>
				<div class="dd-divider"></div>
				<a class="dd-menu-item" href="{$smarty.const.SB_ADMIN_URL}assets/samples/" target="_blank">Thème sample</a>
			</div>
		</div>
	</div>
</section>
