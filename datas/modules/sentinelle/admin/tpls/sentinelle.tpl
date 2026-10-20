{* -------------- *}
{* --- MODULE --- *}
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}

			<section class="hero">
				<div class="hero-text">
					<span class="eyebrow">Sécurité</span>
					{if $sb_sentinelle_page == 'settings'}
					<h1 class="hero-title">Réglages de Sentinelle</h1>
					<p class="hero-sub">Mode de fonctionnement, conservation du journal et adresses de confiance.</p>
					{else}
					<h1 class="hero-title">Sentinelle</h1>
					<p class="hero-sub">Journal des attaques et des accès refusés, sur le site et dans l'administration.</p>
					{/if}
				</div>
				<div class="hero-actions">
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown aria-label="Actions Sentinelle">
							{if $sb_sentinelle_page == 'settings'}Réglages{else}Journal de sécurité{/if}
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu">
							<a class="dd-menu-item" href="{$module_url}"{if $sb_sentinelle_page != 'settings'} style="color:var(--primary);font-weight:600"{/if}>Journal de sécurité</a>
							<a class="dd-menu-item" href="{$module_url}&a=settings"{if $sb_sentinelle_page == 'settings'} style="color:var(--primary);font-weight:600"{/if}>Réglages</a>
						</div>
					</div>
				</div>
			</section>

			{if $sb_sentinelle_rescue}
			<div class="grid">
				<section class="col-12">
					<div class="alert danger">
						<span class="ico"><svg viewBox="0 0 24 24"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg></span>
						<div class="body">
							<div class="title">Sentinelle désactivée</div>
							<p>Le fichier de secours inc/admin/sentinelle-disabled est présent : rien n'est journalisé ni bloqué. Supprimez-le pour réactiver Sentinelle.</p>
						</div>
					</div>
				</section>
			</div>
			{/if}

			{if $sb_sentinelle_page == 'settings'}
			<div class="grid">
				<section class="col-8 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">{$legend_add_edit}</h2>
						</div>
					</div>
					{include_php file='form.php'}
				</section>
				<div class="col-4">
					{include file='shared/shared-panel-actions.tpl'}
					<section class="card" style="margin-top:16px">
						<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">Bloqué par erreur ?</h2></div></div>
						<p class="help-block">Créez le fichier vide <code>{$sb_sentinelle_rescue_path|escape}</code> sur le serveur (FTP) : Sentinelle s'arrête aussitôt. Supprimez-le une fois votre adresse ajoutée aux IP de confiance.</p>
					</section>
				</div>
			</div>
			{else}
			<div class="grid">
				<section class="col-12">
					<div class="kpi-grid">
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon {if $sb_sentinelle_mode == 'protect'}success{else}warning{/if}"><i class="fa-solid fa-shield-halved"></i></div><div class="kpi-label">Mode</div></div></div>
							<div class="kpi-value">{if $sb_sentinelle_mode == 'protect'}Protection{else}Observation{/if}</div>
							<div class="kpi-compare">{if $sb_sentinelle_mode == 'protect'}les attaques détectées sont bloquées{else}journal seulement, rien n'est bloqué{/if}</div>
						</div>
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon danger"><i class="fa-solid fa-triangle-exclamation"></i></div><div class="kpi-label">Événements sur 24 h</div></div></div>
							<div class="kpi-value">{$sb_sentinelle_kpi.day}</div>
							<div class="kpi-compare">dont {$sb_sentinelle_kpi.critical} critique(s) · {$sb_sentinelle_kpi.week} sur 7 jours</div>
						</div>
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon info"><i class="fa-solid fa-network-wired"></i></div><div class="kpi-label">Adresses IP sur 24 h</div></div></div>
							<div class="kpi-value">{$sb_sentinelle_kpi.ips}</div>
							<div class="kpi-compare">journal conservé {$sb_sentinelle_retention} jours</div>
						</div>
					</div>
				</section>

				<section class="col-12 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Journal de sécurité</h2>
						</div>
					</div>
					{if !$sb_sentinelle_ready}
						<p class="help-block">Base de données indisponible : journal illisible.</p>
					{else}
					<form method="get" action="index.php" class="data-toolbar" style="flex-wrap:wrap;gap:8px">
						<input type="hidden" name="p" value="sentinelle">
						<select name="type" class="select" style="max-width:220px">
							<option value="">Tous les événements</option>
							{foreach from=$sb_sentinelle_types key=k item=label}
							<option value="{$k}"{if $sb_sentinelle_filter.type == $k} selected{/if}>{$label|escape}</option>
							{/foreach}
						</select>
						<select name="severity" class="select" style="max-width:160px">
							<option value="">Toutes gravités</option>
							<option value="info"{if $sb_sentinelle_filter.severity == 'info'} selected{/if}>Info</option>
							<option value="warning"{if $sb_sentinelle_filter.severity == 'warning'} selected{/if}>Alerte</option>
							<option value="critical"{if $sb_sentinelle_filter.severity == 'critical'} selected{/if}>Critique</option>
						</select>
						<select name="days" class="select" style="max-width:160px">
							<option value="1"{if $sb_sentinelle_filter.days == '1'} selected{/if}>24 heures</option>
							<option value="7"{if $sb_sentinelle_filter.days == '7'} selected{/if}>7 jours</option>
							<option value="30"{if $sb_sentinelle_filter.days == '30'} selected{/if}>30 jours</option>
							<option value="all"{if $sb_sentinelle_filter.days == 'all'} selected{/if}>Tout</option>
						</select>
						<input class="input" type="text" name="ip" value="{$sb_sentinelle_filter.ip|escape}" placeholder="Adresse IP" style="max-width:180px">
						<button class="btn btn--primary" type="submit">Filtrer</button>
						{if $sb_sentinelle_filter.type || $sb_sentinelle_filter.severity || $sb_sentinelle_filter.ip || $sb_sentinelle_filter.days != '7'}<a class="btn btn--outline-primary" href="{$module_url}">Effacer</a>{/if}
					</form>
					<div class="data-toolbar">
						<div class="data-toolbar-left">
							<div class="input-icon" style="flex:1;max-width:320px">
								<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
								<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-sentinelle">
							</div>
						</div>
					</div>
					<div style="overflow-x:auto">
						<table class="data-table" id="dataTables-sentinelle" data-datatable data-page-size="50">
							<thead>
								<tr>
									{foreach from=$sb_table_header item=header}
									<th>{$header} <span class="sort"><svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span></th>
									{/foreach}
								</tr>
							</thead>
							<tbody>
								{foreach from=$sb_sentinelle_rows item=log}
								<tr class="data-row">
									<td data-sort-value="{$log.created_at|escape}">{$log.created_at|date_format:"%d.%m.%Y %H:%M:%S"}</td>
									<td><a href="{$module_url}&ip={$log.ip|escape:'url'}&days=all">{$log.ip|escape}</a></td>
									<td>{$log.type_label|escape}{if $log.user} <small>({$log.user|escape})</small>{/if}</td>
									<td><span class="badge {if $log.severity == 'critical'}danger{elseif $log.severity == 'warning'}warning{else}info{/if}">{if $log.severity == 'critical'}critique{elseif $log.severity == 'warning'}alerte{else}info{/if}</span></td>
									<td>{if $log.action == 'banned'}IP bannie{elseif $log.action == 'blocked'}bloqué{else}noté{/if}</td>
									<td>{if $log.context == 'admin'}admin{else}site{/if}</td>
									<td style="max-width:260px;word-break:break-all"><small>{$log.method|escape} {$log.uri|escape}</small></td>
									<td style="max-width:300px;word-break:break-word"><small>{$log.detail|escape}</small></td>
									<td>
										<a class="btn--icon" data-confirm="Supprimer cette ligne du journal ?" href="{$module_url}&a=del&id={$log.id}" data-tooltip="Supprimer">
											<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
										</a>
									</td>
								</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
					<div class="data-foot" data-datatable-foot="dataTables-sentinelle">
						<div class="data-foot-info" data-foot-info></div>
						<div class="pager"></div>
					</div>
					<div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:16px">
						<a class="btn btn--outline-primary" href="{$module_url}&a=purge&scope=old" data-confirm="Supprimer les lignes de plus de {$sb_sentinelle_retention} jours ?">Supprimer les lignes de plus de {$sb_sentinelle_retention} jours</a>
						<a class="btn btn--danger" href="{$module_url}&a=purge&scope=all" data-confirm="Vider tout le journal de sécurité ?">Vider le journal</a>
					</div>
					{/if}
				</section>
			</div>
			{/if}

	{include file='sb_footer.tpl' page='false' pagef='false'}
