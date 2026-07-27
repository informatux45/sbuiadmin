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

			{if $sb_dashboard_widgets}
			<div class="kpi-grid" aria-label="Widgets configurables">
				{foreach from=$sb_dashboard_widgets item=widget}
				{if $widget.type == 'table' || $widget.type == 'system' || $widget.type == 'weather'}
				<a href="{$widget.link}" class="kpi-card c-{$widget.color}">
					<div class="kpi-top">
						<div class="kpi-identity">
							<div class="kpi-icon {$widget.color}"><i class="fa fa-{$widget.icon}"></i></div>
							<div class="kpi-label">{$widget.title|@sbDisplayLang}</div>
						</div>
						{if $widget.trend}
							<span class="kpi-pill {$widget.trend.direction}">
								<svg viewBox="0 0 24 24">{if $widget.trend.direction == 'up'}<path d="M7 17l10-10M7 7h10v10"/>{elseif $widget.trend.direction == 'down'}<path d="M7 7l10 10M17 7v10H7"/>{else}<path d="M5 12h14"/>{/if}</svg>
								{if $widget.trend.direction == 'up'}+{elseif $widget.trend.direction == 'down'}-{/if}{$widget.trend.percent}%
							</span>
						{/if}
					</div>
					<div class="kpi-value"{if $widget.type != 'table'} style="font-size:24px"{/if}>
						{if $widget.type == 'table'}{$widget.cpt|default:0}{else}{$widget.value}{/if}
					</div>
					{if $widget.trend}
						<div class="kpi-compare">
							<svg class="{$widget.trend.direction}" viewBox="0 0 24 24">{if $widget.trend.direction == 'up'}<path d="M7 17l10-10M7 7h10v10"/>{elseif $widget.trend.direction == 'down'}<path d="M7 7l10 10M17 7v10H7"/>{else}<path d="M5 12h14"/>{/if}</svg>
							{if $widget.trend.direction == 'up'}en hausse{elseif $widget.trend.direction == 'down'}en baisse{else}stable{/if} depuis <strong>{$widget.trend.previous}</strong> <span class="sep">·</span> 7 derniers jours
						</div>
					{elseif $widget.type == 'weather' && $widget.label}
						<div class="kpi-compare">{$widget.label} · {$widget.city}</div>
					{/if}
				</a>
				{/if}
				{/foreach}
			</div>

			<div class="grid">
				{foreach from=$sb_dashboard_widgets item=widget}
				{if $widget.type == 'table'}
				<section class="col-4 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Récent</span>
							<h2 class="card-title"><i class="fa fa-{$widget.icon} fa-fw" style="color:var(--{$widget.color})"></i> {$widget.title|@sbDisplayLang}</h2>
						</div>
						<a class="card-action" href="{$widget.link}">Voir tout <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>
					</div>
					{if $widget.chart}
						<div class="sb-dashboard-chart" data-sb-chart data-sb-chart-color="{$widget.color}" data-sb-chart-labels="{$widget.chart.labels|@json_encode|escape:'html'}" data-sb-chart-values="{$widget.chart.values|@json_encode|escape:'html'}"></div>
					{/if}
					<div>
						{foreach from=$widget.all item=row}
							<a href="#" style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border-soft);color:var(--t-base);font-size:13px">
								<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;color:var(--t-light)"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
								{$row.val|@sbDisplayLang|unescape:"htmlall"}
							</a>
						{foreachelse}
							<p style="color:var(--t-muted);font-size:13px;padding:10px 0">Aucun élément pour l'instant.</p>
						{/foreach}
					</div>
				</section>
				{elseif $widget.type == 'html' || $widget.type == 'text'}
				<section class="col-4 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">{if $widget.icon}<i class="fa fa-{$widget.icon} fa-fw" style="color:var(--{$widget.color})"></i> {/if}{$widget.title|@sbDisplayLang}</h2>
						</div>
						{if $widget.link}<a class="card-action" href="{$widget.link}">Voir tout <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg></a>{/if}
					</div>
					<div>{$widget.content|unescape:"htmlall"}</div>
				</section>
				{/if}
				{/foreach}
			</div>
			{elseif $sbuiadmin_user_type == 'admin'}
			<section class="card" style="text-align:center;padding:40px;color:var(--t-muted)">
				Aucun widget configuré sur le dashboard. <a href="index.php?p=dashboard">Ajoutez-en un</a> pour afficher un aperçu de vos modules ici.
			</section>
			{/if}

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
