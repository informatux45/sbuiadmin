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
					<p class="hero-sub">Mode de fonctionnement, filtres, alertes par e-mail et adresses de confiance.</p>
					{elseif $sb_sentinelle_page == 'advisor'}
					<h1 class="hero-title">Conseiller sécurité</h1>
					<p class="hero-sub">Vérification de la configuration du site, point par point, avec la correction à faire.</p>
					{elseif $sb_sentinelle_page == 'bans'}
					<h1 class="hero-title">IP bannies</h1>
					<p class="hero-sub">Adresses et plages refusées sur tout le site, quel que soit le mode.</p>
					{else}
					<h1 class="hero-title">Sentinelle</h1>
					<p class="hero-sub">Journal des attaques et des accès refusés, sur le site et dans l'administration.</p>
					{/if}
				</div>
				<div class="hero-actions">
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown aria-label="Actions Sentinelle">
							{if $sb_sentinelle_page == 'settings'}Réglages{elseif $sb_sentinelle_page == 'bans'}IP bannies{elseif $sb_sentinelle_page == 'advisor'}Conseiller{else}Journal de sécurité{/if}
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu">
							<a class="dd-menu-item" href="{$module_url}"{if $sb_sentinelle_page == 'log'} style="color:var(--primary);font-weight:600"{/if}>Journal de sécurité</a>
							<a class="dd-menu-item" href="{$module_url}&a=advisor"{if $sb_sentinelle_page == 'advisor'} style="color:var(--primary);font-weight:600"{/if}>Conseiller</a>
							<a class="dd-menu-item" href="{$module_url}&a=bans"{if $sb_sentinelle_page == 'bans'} style="color:var(--primary);font-weight:600"{/if}>IP bannies</a>
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
			{elseif $sb_sentinelle_page == 'advisor'}
			<div class="grid">
				<section class="col-12">
					<div class="kpi-grid">
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon {if $sb_advisor_score >= 80}success{elseif $sb_advisor_score >= 60}warning{else}danger{/if}"><i class="fa-solid fa-shield-halved"></i></div><div class="kpi-label">Score de sécurité</div></div></div>
							<div class="kpi-value">{$sb_advisor_score}<small style="font-size:16px"> / 100</small></div>
							<div class="kpi-compare">{if $sb_advisor_score >= 80}bonne configuration{elseif $sb_advisor_score >= 60}à améliorer{else}des points importants à corriger{/if}</div>
						</div>
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon danger"><i class="fa-solid fa-circle-xmark"></i></div><div class="kpi-label">À corriger</div></div></div>
							<div class="kpi-value">{$sb_advisor_count.bad}</div>
							<div class="kpi-compare">points en rouge</div>
						</div>
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon warning"><i class="fa-solid fa-triangle-exclamation"></i></div><div class="kpi-label">À améliorer</div></div></div>
							<div class="kpi-value">{$sb_advisor_count.warn}</div>
							<div class="kpi-compare">{$sb_advisor_count.ok} point(s) en ordre{if $sb_advisor_count.unknown} · {$sb_advisor_count.unknown} non vérifiable(s){/if}</div>
						</div>
					</div>
				</section>
				{foreach from=$sb_advisor_groups item=grp}
				{if $grp.checks}
				<section class="col-6 card">
					<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">{$grp.label|escape}</h2></div></div>
					{foreach from=$grp.checks item=ch}
					<div style="display:flex;gap:10px;align-items:flex-start;padding:10px 0;border-top:1px solid var(--border-soft)">
						<span class="badge {if $ch.status == 'ok'}success{elseif $ch.status == 'warn'}warning{elseif $ch.status == 'bad'}danger{else}info{/if}" style="flex:0 0 auto;min-width:74px;text-align:center">{if $ch.status == 'ok'}en ordre{elseif $ch.status == 'warn'}à améliorer{elseif $ch.status == 'bad'}à corriger{elseif $ch.status == 'info'}à noter{else}inconnu{/if}</span>
						<div style="min-width:0">
							<strong>{$ch.label|escape}</strong> <small style="color:var(--t-muted);word-break:break-word">— {$ch.value|escape}</small>
							{if $ch.advice}<div class="help-block" style="margin-top:4px">{$ch.advice|escape}</div>{/if}
						</div>
					</div>
					{/foreach}
				</section>
				{/if}
				{/foreach}
				<section class="col-12">
					<p class="help-block">Analyse refaite à chaque ouverture de cette page. Les points « vus de l'extérieur » (en-têtes, .git, installeur) interrogent le site depuis le serveur ; s'il n'y parvient pas, ils restent « inconnu ».</p>
				</section>
			</div>
			{elseif $sb_sentinelle_page == 'bans'}
			<div class="grid">
				<section class="col-12 card">
					<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">Bannir une adresse ou une plage</h2></div></div>
					<form method="post" action="{$module_url}&a=banadd" style="display:flex;gap:8px;flex-wrap:wrap;align-items:flex-end">
						<div><label class="field-label">Adresse ou plage CIDR</label><input class="input" type="text" name="ip" placeholder="203.0.113.7 ou 203.0.113.0/24" required style="min-width:240px"></div>
						<div><label class="field-label">Durée</label>
							<select name="d" class="select">
								{foreach from=$sb_sentinelle_durations key=k item=label}<option value="{$k}"{if $k == '86400'} selected{/if}>{$label}</option>{/foreach}
							</select>
						</div>
						<div style="flex:1;min-width:200px"><label class="field-label">Motif</label><input class="input" type="text" name="reason" maxlength="255" placeholder="Robot, tentative d'intrusion..."></div>
						<button class="btn btn--primary" type="submit">Bannir</button>
					</form>
					<p class="help-block" style="margin-top:14px">Une IP bannie reçoit une page 403 partout sur le site. Les IP de confiance et votre propre adresse ({$sb_sentinelle_my_ip|escape}) ne peuvent pas être bannies ; plage au plus /16 en IPv4, /32 en IPv6.</p>
				</section>
				<section class="col-12 card">
					<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">Bannissements</h2></div></div>
					<div style="overflow-x:auto">
						<table class="data-table" id="dataTables-sentinelle-bans" data-datatable data-page-size="50">
							<thead><tr>{foreach from=$sb_table_header item=header}<th>{$header} <span class="sort"><svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span></th>{/foreach}</tr></thead>
							<tbody>
								{foreach from=$sb_sentinelle_bans item=ban}
								<tr class="data-row"{if !$ban.active} style="opacity:.55"{/if}>
									<td><strong>{$ban.ip_range|escape}</strong>{if !$ban.active} <span class="badge info">expiré</span>{/if}</td>
									<td>{$ban.reason|escape}</td>
									<td>{if $ban.source == 'auto'}automatique{else}manuel{/if}</td>
									<td data-sort-value="{$ban.created_at|escape}">{$ban.created_at|date_format:"%d.%m.%Y %H:%M"}</td>
									<td data-sort-value="{$ban.expires_at|default:'9999'|escape}">{if $ban.expires_at}{$ban.expires_at|date_format:"%d.%m.%Y %H:%M"}{else}<span class="badge danger">permanent</span>{/if}</td>
									<td>{$ban.hits}{if $ban.last_hit} <small>(dernière {$ban.last_hit|date_format:"%d.%m %H:%M"})</small>{/if}</td>
									<td><a class="btn--icon" data-confirm="Lever ce bannissement ?" href="{$module_url}&a=bandel&id={$ban.id}" data-tooltip="Lever"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg></a></td>
								</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
					<div class="data-foot" data-datatable-foot="dataTables-sentinelle-bans"><div class="data-foot-info" data-foot-info></div><div class="pager"></div></div>
				</section>
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
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon purple"><i class="fa-solid fa-ban"></i></div><div class="kpi-label">IP bannies</div></div></div>
							<div class="kpi-value">{$sb_sentinelle_bans_count}</div>
							<div class="kpi-compare"><a href="{$module_url}&a=bans">gérer les bannissements</a></div>
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
						<a class="btn btn--outline-primary" href="{$module_url}&a=export&type={$sb_sentinelle_filter.type|escape:'url'}&severity={$sb_sentinelle_filter.severity|escape:'url'}&ip={$sb_sentinelle_filter.ip|escape:'url'}&days={$sb_sentinelle_filter.days|escape:'url'}">Exporter (CSV)</a>
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
									<td style="white-space:nowrap">
										{if $log.ip != $sb_sentinelle_my_ip}<a class="btn--icon" data-confirm="Bannir {$log.ip|escape} pendant 24 heures ?" href="{$module_url}&a=banadd&ip={$log.ip|escape:'url'}&d=86400" data-tooltip="Bannir 24 h">
											<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="m5.6 5.6 12.8 12.8"/></svg>
										</a>{/if}
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
