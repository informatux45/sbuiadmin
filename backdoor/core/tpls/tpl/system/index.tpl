{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page pageindex='Dashboard' page='false'}
	{* ---------------- End Headers --------------- *}

			<section class="hero">
				<div class="hero-text">
					<span class="eyebrow">{$sb_dashboard_date_fr}</span>
					<h1 class="hero-title">Bon retour, <span class="accent">{$sbuiadmin_user_name}</span></h1>
					<p class="hero-sub">Voici un aperçu de votre back-office {$smarty.const._AM_SITE_CUSTOMER_NAME}.</p>
				</div>
			</section>

			{if $sbuiadmin_user_type == 'admin'}
			<div class="kpi-grid" aria-label="Aperçu système">
				<a href="index.php?p=users" class="kpi-card">
					<div class="kpi-top">
						<div class="kpi-identity">
							<div class="kpi-icon primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
							<div class="kpi-label">Utilisateurs</div>
						</div>
					</div>
					<div class="kpi-value">{$sb_users_cpt|default:0}</div>
				</a>

				<a href="index.php?p=database" class="kpi-card">
					<div class="kpi-top">
						<div class="kpi-identity">
							<div class="kpi-icon info"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="8" rx="2"/><rect x="2" y="13" width="20" height="8" rx="2"/><path d="M6 7h.01M6 17h.01"/></svg></div>
							<div class="kpi-label">Version PHP</div>
						</div>
					</div>
					<div class="kpi-value" style="font-size:28px">{$smarty.const._AM_SERVER_PHP_VERSION_ID}</div>
				</a>

				<a href="index.php?p=settings" class="kpi-card">
					<div class="kpi-top">
						<div class="kpi-identity">
							<div class="kpi-icon purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/></svg></div>
							<div class="kpi-label">DB Host</div>
						</div>
					</div>
					<div class="kpi-value" style="font-size:22px">{$smarty.const._AM_DB_HOST}</div>
				</a>

				<a href="index.php?p=settings" class="kpi-card">
					<div class="kpi-top">
						<div class="kpi-identity">
							<div class="kpi-icon warning"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M17 8l-5-5-5 5"/><path d="M12 3v12"/></svg></div>
							<div class="kpi-label">Upload limit</div>
						</div>
					</div>
					<div class="kpi-value" style="font-size:28px">{$smarty.const._AM_MEDIAS_SIZE_LIMIT}</div>
				</a>
			</div>
			{/if}


			<div class="kpi-grid" aria-label="Statuts configurables">
				{if $sb_dashboard_status1_table != ''}
				<a href="{$sb_dashboard_status1_link}" class="kpi-card">
					<div class="kpi-top">
						<div class="kpi-identity">
							<div class="kpi-icon success"><i class="fa fa-{$sb_dashboard_status1_icon}"></i></div>
							<div class="kpi-label">{$sb_dashboard_status1_title|@sbDisplayLang}</div>
						</div>
					</div>
					<div class="kpi-value">{$sb_dashboard_status1_cpt|default:0}</div>
				</a>
				{/if}

				{if $sb_dashboard_status2_table != ''}
				<a href="{$sb_dashboard_status2_link}" class="kpi-card">
					<div class="kpi-top">
						<div class="kpi-identity">
							<div class="kpi-icon info"><i class="fa fa-{$sb_dashboard_status2_icon}"></i></div>
							<div class="kpi-label">{$sb_dashboard_status2_title|@sbDisplayLang}</div>
						</div>
					</div>
					<div class="kpi-value">{$sb_dashboard_status2_cpt|default:0}</div>
				</a>
				{/if}

				{if $sb_dashboard_status3_table != ''}
				<a href="{$sb_dashboard_status3_link}" class="kpi-card">
					<div class="kpi-top">
						<div class="kpi-identity">
							<div class="kpi-icon warning"><i class="fa fa-{$sb_dashboard_status3_icon}"></i></div>
							<div class="kpi-label">{$sb_dashboard_status3_title|@sbDisplayLang}</div>
						</div>
					</div>
					<div class="kpi-value">{$sb_dashboard_status3_cpt|default:0}</div>
				</a>
				{/if}

				{if $sb_dashboard_status4_table != ''}
				<a href="{$sb_dashboard_status4_link}" class="kpi-card">
					<div class="kpi-top">
						<div class="kpi-identity">
							<div class="kpi-icon danger"><i class="fa fa-{$sb_dashboard_status4_icon}"></i></div>
							<div class="kpi-label">{$sb_dashboard_status4_title|@sbDisplayLang}</div>
						</div>
					</div>
					<div class="kpi-value">{$sb_dashboard_status4_cpt|default:0}</div>
				</a>
				{/if}
			</div>
			
			
			<div class="grid">
				{if $sb_dashboard_status1_table != ''}
				<section class="col-4 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Récent</span>
							<h2 class="card-title"><i class="fa fa-{$sb_dashboard_status1_icon} fa-fw"></i> {$sb_dashboard_status1_title|@sbDisplayLang}</h2>
						</div>
						<a class="card-action" href="{$sb_dashboard_status1_link}">Voir tout <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>
					</div>
					<div>
						{foreach from=$sb_dashboard_status1_all item=status1all}
							{if $status1all@iteration == 11}{break}{/if}
							<a href="#" style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border-soft);color:var(--t-base);font-size:13px">
								<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;color:var(--t-light)"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
								{$status1all.$sb_dashboard_status1_tbcol|@sbDisplayLang|unescape:"htmlall"}
							</a>
						{/foreach}
					</div>
				</section>
				{/if}

				{if $sb_dashboard_status2_table != ''}
				<section class="col-4 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Récent</span>
							<h2 class="card-title"><i class="fa fa-{$sb_dashboard_status2_icon} fa-fw"></i> {$sb_dashboard_status2_title|@sbDisplayLang}</h2>
						</div>
						<a class="card-action" href="{$sb_dashboard_status2_link}">Voir tout <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>
					</div>
					<div>
						{foreach from=$sb_dashboard_status2_all item=status2all}
							{if $status2all@iteration == 11}{break}{/if}
							<a href="#" style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border-soft);color:var(--t-base);font-size:13px">
								<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;color:var(--t-light)"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
								{$status2all.$sb_dashboard_status2_tbcol|@sbDisplayLang|unescape:"htmlall"}
							</a>
						{/foreach}
					</div>
				</section>
				{/if}

				{if $sb_dashboard_status3_table != ''}
				<section class="col-4 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Récent</span>
							<h2 class="card-title"><i class="fa fa-{$sb_dashboard_status3_icon} fa-fw"></i> {$sb_dashboard_status3_title|@sbDisplayLang}</h2>
						</div>
						<a class="card-action" href="{$sb_dashboard_status3_link}">Voir tout <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>
					</div>
					<div>
						{foreach from=$sb_dashboard_status3_all item=status3all}
							{if $status3all@iteration == 11}{break}{/if}
							<a href="#" style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border-soft);color:var(--t-base);font-size:13px">
								<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;color:var(--t-light)"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
								{$status3all.$sb_dashboard_status3_tbcol|@sbDisplayLang|unescape:"htmlall"}
							</a>
						{/foreach}
					</div>
				</section>
				{/if}

				{if $sb_dashboard_status4_table != ''}
				<section class="col-4 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Récent</span>
							<h2 class="card-title"><i class="fa fa-{$sb_dashboard_status4_icon} fa-fw"></i> {$sb_dashboard_status4_title|@sbDisplayLang}</h2>
						</div>
						<a class="card-action" href="{$sb_dashboard_status4_link}">Voir tout <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>
					</div>
					<div>
						{foreach from=$sb_dashboard_status4_all item=status4all}
							{if $status4all@iteration == 11}{break}{/if}
							<a href="#" style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border-soft);color:var(--t-base);font-size:13px">
								<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;color:var(--t-light)"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
								{$status4all.$sb_dashboard_status4_tbcol|@sbDisplayLang|unescape:"htmlall"}
							</a>
						{/foreach}
					</div>
				</section>
				{/if}
			</div>
			
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Avertissements sécurité admin : en toasts (empilables) plutôt qu'en
			// alertes fixes sur le dashboard, pour ne pas surcharger l'affichage.
			{if $sbuiadmin_user_type == 'admin'}
				{if isset($sb_warning_installer_lock)}
					sbToast('Le répertoire INSTALL existe toujours. Supprimez-le au plus vite !', 'error', '#', 'Vite');
				{/if}
				{if isset($sb_warning_install_file) && $sb_warning_install_file == true}
					sbToast('Le fichier INSTALL.PHP existe toujours. Supprimez-le au plus vite !', 'error', '#', 'Vite');
				{/if}
				{if isset($sb_warning_admin_user) && $sb_warning_admin_user == true}
					sbToast('L\'utilisateur ADMIN existe toujours. Créez d\'autres utilisateurs et supprimez-le !', 'error', 'index.php?p=users', 'Vite');
				{/if}
			{/if}
		});
		</script>

	{include file='sb_footer.tpl' page='false' pagef='false'}
