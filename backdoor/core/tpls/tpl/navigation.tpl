{* ------------------------------------------------- *}
{* Navigation: sidebar + topbar (Adminator chrome)   *}
{* ------------------------------------------------- *}
<div class="shell">
	<aside class="d-sidebar">
		<div class="brand">
			<div class="brand-logo">
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" x="0px" y="0px" viewBox="0 0 48 48" width="28" height="28"><circle fill="#FFF59D" cx="24" cy="22" r="20"></circle><path fill="#FBC02D" d="M37,22c0-7.7-6.6-13.8-14.5-12.9c-6,0.7-10.8,5.5-11.4,11.5c-0.5,4.6,1.4,8.7,4.6,11.3	c1.4,1.2,2.3,2.9,2.3,4.8V37h12v-0.1c0-1.8,0.8-3.6,2.2-4.8C35.1,29.7,37,26.1,37,22z"></path><path fill="#FFF59D" d="M30.6,20.2l-3-2c-0.3-0.2-0.8-0.2-1.1,0L24,19.8l-2.4-1.6c-0.3-0.2-0.8-0.2-1.1,0l-3,2	c-0.2,0.2-0.4,0.4-0.4,0.7s0,0.6,0.2,0.8l3.8,4.7V37h2V26c0-0.2-0.1-0.4-0.2-0.6l-3.3-4.1l1.5-1l2.4,1.6c0.3,0.2,0.8,0.2,1.1,0	l2.4-1.6l1.5,1l-3.3,4.1C25.1,25.6,25,25.8,25,26v11h2V26.4l3.8-4.7c0.2-0.2,0.3-0.5,0.2-0.8S30.8,20.3,30.6,20.2z"></path><circle fill="#5C6BC0" cx="24" cy="44" r="3"></circle><path fill="#9FA8DA" d="M26,45h-4c-2.2,0-4-1.8-4-4v-5h12v5C30,43.2,28.2,45,26,45z"></path><g>	<path fill="#5C6BC0" d="M30,41l-11.6,1.6c0.3,0.7,0.9,1.4,1.6,1.8l9.4-1.3C29.8,42.5,30,41.8,30,41z"></path>	<polygon fill="#5C6BC0" points="18,38.7 18,40.7 30,39 30,37"></polygon></g></svg>
			</div>
			<div class="brand-text">
				<div class="brand-name">{$smarty.const._AM_SITE_CUSTOMER_NAME}</div>
				<div class="brand-tag">SBUIADMIN v{$smarty.const._AM_START_VERSION}</div>
			</div>
		</div>

		<nav class="nav-section">
			{include file='main_menu.tpl'}
		</nav>

		<div class="sidebar-footer">
			<div class="dd-wrap">
				<div class="workspace" data-dropdown tabindex="0" role="button" aria-label="Menu utilisateur">
					<img src="{$sbuiadmin_user_email|@sbGetGravatar}" alt="" style="width:36px;height:36px;border-radius:50%;flex-shrink:0;">
					<div class="workspace-text">
						<div class="workspace-name">{$sbuiadmin_user_name}</div>
						<div class="workspace-role">{$smarty.const.SBUIADMIN_GLOBAL_LAST_LOGIN} {$sbuiadmin_user_last_login}</div>
					</div>
					<svg class="workspace-chev" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m6 9 6 6 6-6"/></svg>
				</div>
				<div class="dd-menu dd-profile" role="menu">
					<div class="dd-profile-head">
						<div class="dd-profile-name">{$sbuiadmin_user_name}</div>
						<div class="dd-profile-email">{$smarty.const.SBUIADMIN_GLOBAL_LAST_LOGIN} {$sbuiadmin_user_last_login}</div>
					</div>
					<a class="dd-menu-item danger" href="{$smarty.const._AM_SITE_URL}?ac=logout" title="{$smarty.const.SBUIADMIN_GLOBAL_LOGOUT}">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
						{$smarty.const.SBUIADMIN_GLOBAL_LOGOUT}
					</a>
				</div>
			</div>
		</div>
	</aside>

	<div class="main">
		<header class="d-topbar">
			<div class="crumbs">
				<button class="hamburger" data-drawer-open aria-label="Ouvrir la navigation">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
				</button>
				{if $page_title}
					<a href="{$smarty.const._AM_SITE_URL}"><i class="fa fa-home fa-fw"></i> Dashboard</a>
					<svg class="sep" viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
					<a href="{if isset($smarty.get.p)}{$smarty.const._AM_SITE_URL}index.php?p={$smarty.get.p}{else}#{/if}">{$page_title|lower|@ucfirst}</a>
					{if isset($smarty.get.a)}
						<svg class="sep" viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
						<span class="current">{$smarty.get.a|lower|@ucfirst}</span>
					{/if}
				{else}
					<i class="fa fa-home fa-fw"></i> <span class="current">{$pageindex|upper}</span>
				{/if}
			</div>
			<div class="topbar-actions">
				<a class="icon-btn" target="_blank" href="{if $sb_url_customer != ''}{$sb_url_customer}{else}index.php{/if}" title="Aller sur le site {$smarty.const._AM_SITE_CUSTOMER_NAME}" aria-label="Voir le site">
					<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20z"/></svg>
				</a>

				<div class="dd-wrap">
					<button class="icon-btn" data-dropdown aria-label="Mises à jour"{if $sbuiadmin_upgrade_core || $sbuiadmin_upgrade_modules} style="color:var(--warning)"{/if}>
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 12a9 9 0 1 1-3-6.7L21 8"/><path d="M21 3v5h-5"/></svg>
						{if $sbuiadmin_upgrade_core || $sbuiadmin_upgrade_modules}<span class="count warning">!</span>{/if}
					</button>
					<div class="dd-menu" role="menu">
						<div class="dd-head"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 12a9 9 0 1 1-3-6.7L21 8"/><path d="M21 3v5h-5"/></svg> Mises à jour</div>
						<div class="dd-list">
							{if $sbuiadmin_upgrade_core}
								<a class="dd-item" href="#" data-target="#sbupgradecore" data-toggle="modal">
									<div class="dd-body">
										<div class="dd-text"><strong>Nouvelle version {$sbuiadmin_upgrade_core}</strong></div>
										<div class="dd-time">Version actuelle {$smarty.const._AM_START_VERSION}</div>
									</div>
								</a>
							{else}
								<div class="dd-item">
									<div class="dd-body">
										<div class="dd-text">Système à jour</div>
										<div class="dd-time">Version actuelle {$smarty.const._AM_START_VERSION}</div>
									</div>
								</div>
							{/if}
							{if $sbuiadmin_upgrade_modules}
								<a class="dd-item" href="#" data-target="#sbupgrademodules" data-toggle="modal">
									<div class="dd-body">
										<div class="dd-text"><strong>{$sbuiadmin_upgrade_modules} nouveaux modules</strong></div>
									</div>
								</a>
							{else}
								<div class="dd-item">
									<div class="dd-body">
										<div class="dd-text">Modules à jour</div>
									</div>
								</div>
							{/if}
						</div>
					</div>
				</div>

				<div class="dd-wrap">
					<button class="icon-btn" data-dropdown aria-label="Informations" title="Credits SBUIADMIN">
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
					</button>
					<div class="dd-menu" role="menu">
						<div class="dd-head"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg> Informations</div>
						<div class="dd-list">
							<div class="dd-item"><div class="dd-body"><div class="dd-text">Software SBUIADMIN par INFORMATUX</div></div></div>
							<div class="dd-item"><div class="dd-body"><div class="dd-text">SBUIADMIN version {$smarty.const._AM_START_VERSION}</div></div></div>
							<div class="dd-item"><div class="dd-body"><div class="dd-text">Powered by Smarty {$smarty.version}</div></div></div>
							<div class="dd-item"><div class="dd-body"><div class="dd-text">&copy; 2007 - {$smarty.now|date_format:'%Y'} INFORMATUX</div></div></div>
						</div>
					</div>
				</div>

				<button id="themeToggle" class="icon-btn" aria-label="Changer de thème"></button>
			</div>
		</header>

		{* --- Dialog box UPGRADE CORE --- *}
		<div aria-hidden="true" aria-labelledby="sbupgradecoreLabel" role="dialog" tabindex="-1" id="sbupgradecore" class="modal fade" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
						<h4 id="sbupgradecoreLabel" class="modal-title">Mise à niveau vers la version <span style="color: red;">{$sbuiadmin_upgrade_core}</span></h4><a onclick="javascript:sbUgrade('core','{$smarty.const._AM_SITE_URL}');" role="button" class="upgrade-now btn btn--danger" id="upgrade-core">upgrade now!</a>
					</div>
					<div id="upgrade-ajax-content" class="modal-body">
						<div class="sbupgrade-filelist">
							<span>Liste des fichiers à mettre à niveau :</span><br>
							{$sbuiadmin_upgrade_core_filelist}
						</div>
						<div id="sbupgrade-inprogress" class="center" style="display: none;">
							<br>Mise à niveau en cours<br>
							<img src="{$smarty.const._AM_SITE_URL}img/ajax-loader-upgrade.gif" alt="Upgrade in progress" />
						</div>
					</div>
					<div class="modal-footer">
						<button data-dismiss="modal" class="btn btn--ghost" type="button" onclick="javascript:location.href='{$smarty.const._AM_SITE_URL}'">Close</button>
					</div>
				</div>
			</div>
		</div>

		{* --- Dialog box UPGRADE MODULES --- *}
		<div aria-hidden="true" aria-labelledby="sbupgrademodulesLabel" role="dialog" tabindex="-1" id="sbupgrademodules" class="modal fade" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
						<h4 id="sbupgrademodulesLabel" class="modal-title">Mise à niveau des modules <span style="color: red;">{$sbuiadmin_upgrade_modules}</span></h4>
					</div>
					<div class="modal-body">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					</div>
					<div class="modal-footer">
						<button data-dismiss="modal" class="btn btn--ghost" type="button">Close</button>
					</div>
				</div>
			</div>
		</div>
