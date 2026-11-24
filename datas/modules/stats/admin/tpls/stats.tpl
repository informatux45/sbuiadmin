{* -------------- *}
{* --- MODULE --- *}
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
	<link rel="stylesheet" href="../datas/modules/stats/admin/assets/stats-admin.css?v={$sb_stats_asset_v}">

	{* --- Corps d'un rapport : graphique éventuel, tableau avec barre de part --- *}
	{function name=sbst_block_body b=[]}
			{if !$b.rows}
				<p class="sbst-empty">Aucune donnée sur cette période.</p>
			{else}
				{if $b.chart}<div class="sbst-chart sbst-chart--{$b.chart.type}" data-sbst-chart="{$b.chart_json|escape}" role="img" aria-label="{$b.title|escape}"></div>{/if}
				<div class="sbst-table-wrap">
				<table class="sbst-table">
					<thead><tr>
						<th scope="col">{$b.title|escape}</th>
						{foreach from=$b.columns key=ck item=cdef}<th scope="col" class="num">{$cdef[0]|escape}</th>{/foreach}
					</tr></thead>
					<tbody>
					{foreach from=$b.rows item=r}
						<tr{if $r.other} class="sbst-other"{/if}>
							<td class="sbst-label">
								<div class="sbst-label-text">
									{if $r.icon}<i class="fa-solid fa-{$r.icon}" aria-hidden="true"></i>{/if}
									{if $r.link}<a href="{$r.link|escape}" target="_blank" rel="noopener noreferrer" title="{$r.raw|escape}">{$r.label|escape}</a>{else}<span title="{$r.raw|escape}">{$r.label|escape}</span>{/if}
								</div>
								<div class="sbst-bar" aria-hidden="true"><span style="width:{$r.share}%"></span></div>
							</td>
							{foreach from=$r.values key=ck item=v}<td class="num">{$v|escape}{if $ck == $b.main} <small>{$r.share|replace:'.':','} %</small>{/if}</td>{/foreach}
						</tr>
					{/foreach}
					</tbody>
				</table>
				</div>
				{if !empty($b.attribution)}<p class="sbst-attrib"><a href="https://db-ip.com" target="_blank" rel="noopener">IP Geolocation by DB-IP</a></p>{/if}
			{/if}
	{/function}

	{* --- Bloc de rapport complet (carte) --- *}
	{function name=sbst_block b=[]}
		<section class="card sbst-block">
			<div class="card-head">
				<div class="card-title-wrap"><h2 class="card-title">{$b.title|escape}</h2></div>
				{if $b.rows}<a class="sbst-export" href="{$module_url}&a=export&r={$b.report|escape:'url'}{$sb_stats_qs}" title="Exporter en CSV"><i class="fa-solid fa-file-csv"></i><span class="sr-only">Exporter en CSV</span></a>{/if}
			</div>
			{call name=sbst_block_body b=$b}
		</section>
	{/function}

	{* --- Indicateurs clés --- *}
	{function name=sbst_kpis kpis=[] compare='' mini=false}
		<div class="{if $mini}sbst-kpis-mini{else}sbst-kpis{/if}">
			{foreach from=$kpis item=k}
			<div class="{if $mini}sbst-kpi-mini{else}kpi-card{/if}">
				{if $mini}
					<div class="sbst-kpi-mini-label"><i class="fa-solid fa-{$k.icon}" aria-hidden="true"></i> {$k.label|escape}</div>
					<div class="sbst-kpi-mini-value">{$k.value|escape}</div>
				{else}
					<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon info"><i class="fa-solid fa-{$k.icon}"></i></div><div class="kpi-label">{$k.label|escape}</div></div></div>
					<div class="kpi-value">{$k.value|escape}</div>
				{/if}
				<div class="{if $mini}sbst-kpi-mini-trend{else}kpi-compare{/if}">
					{if $k.trend === null}<span class="sbst-trend">pas de comparaison</span>
					{else}
						{assign var=sbst_good value=(($k.good == 'up' && $k.trend >= 0) || ($k.good == 'down' && $k.trend <= 0))}
						<span class="sbst-trend {if $k.trend == 0}flat{elseif $sbst_good}good{else}bad{/if}">
							<i class="fa-solid fa-{if $k.trend > 0}arrow-trend-up{elseif $k.trend < 0}arrow-trend-down{else}minus{/if}" aria-hidden="true"></i>
							{if $k.trend > 0}+{/if}{$k.trend}{if !empty($k.points)} pts{else} %{/if}
						</span>
						<span class="sbst-trend-note">{$compare}</span>
					{/if}
				</div>
			</div>
			{/foreach}
		</div>
	{/function}

	{* --- Liste courte de visites (widgets) --- *}
	{function name=sbst_visits_mini visits=[] empty=''}
		{if !$visits}<p class="sbst-empty">{$empty}</p>
		{else}
		<ul class="sbst-mini-visits">
			{foreach from=$visits item=v}
			<li>
				<span class="sbst-action-time">{$v.time_fr}</span>
				<i class="fa-solid fa-{$v.device_icon}" title="{$v.device_label|escape}" aria-hidden="true"></i>
				<span class="sbst-mini-main">{if $v.actions_list}{if $v.actions_list[0].title}{$v.actions_list[0].title|escape}{else}{$v.actions_list[0].url|escape}{/if}{else}{$v.entry_url|escape}{/if}</span>
				<span class="sbst-mini-meta"><i class="fa-solid fa-{$v.referer_icon}" aria-hidden="true"></i> {if $v.referer_name}{$v.referer_name|escape}{else}{$v.referer_label|escape}{/if} · {$v.actions} action(s)</span>
			</li>
			{/foreach}
		</ul>
		{/if}
	{/function}

			<section class="hero sbst-hero">
				<div class="hero-text">
					<span class="eyebrow">Statistiques{if $sb_stats_section} · {$sb_stats_section|escape}{/if}</span>
					<h1 class="hero-title">{$sb_stats_page_title|escape}</h1>
					{if $sb_stats_page != 'settings' && $sb_stats_page != 'realtime'}<p class="hero-sub"><i class="fa-solid fa-calendar-days"></i> {$sb_stats_period.label|escape}</p>{/if}
					{if $sb_stats_page == 'realtime'}<p class="hero-sub">Visites des 30 dernières minutes{if $sb_stats_refresh_max > 0}, mises à jour toutes les 30 secondes pendant {$sb_stats_refresh_max} minutes{/if}.</p>{/if}
				</div>
				{if $sb_stats_page != 'settings' && $sb_stats_page != 'realtime'}
				<form method="get" action="index.php" class="sbst-period" data-sbst-period>
					<input type="hidden" name="p" value="stats">
					{if $sb_stats_page != 'dashboard'}<input type="hidden" name="a" value="{$sb_stats_page|escape}">{/if}
					<label class="sr-only" for="sbst-period">Période</label>
					<select id="sbst-period" name="period" class="select">
						{foreach from=['day' => 'Jour', 'week' => 'Semaine', 'month' => 'Mois', 'year' => 'Année', 'range' => 'Plage de dates'] key=pk item=pl}
						<option value="{$pk}"{if $sb_stats_period.period == $pk} selected{/if}>{$pl}</option>
						{/foreach}
					</select>
					<span data-sbst-single{if $sb_stats_period.period == 'range'} hidden{/if}>
						<label class="sr-only" for="sbst-date">Date</label>
						<input id="sbst-date" type="date" name="date" class="input" value="{$sb_stats_period.date}" max="{$sb_stats_today}">
					</span>
					<span data-sbst-range{if $sb_stats_period.period != 'range'} hidden{/if}>
						<label class="sr-only" for="sbst-from">Du</label>
						<input id="sbst-from" type="date" name="from" class="input" value="{$sb_stats_period.from}" max="{$sb_stats_today}">
						<label class="sr-only" for="sbst-to">Au</label>
						<input id="sbst-to" type="date" name="to" class="input" value="{$sb_stats_period.to}" max="{$sb_stats_today}">
					</span>
					<button type="submit" class="btn btn--primary">Afficher</button>
				</form>
				{/if}
			</section>

			<div class="sbst-layout">
				{* --- Menu des rapports (comme Matomo) --- *}
				<nav class="card sbst-nav" aria-label="Rapports">
					{foreach from=$sb_stats_menu key=section item=items}
						<div class="sbst-nav-section">{$section|escape}</div>
						{foreach from=$items key=k item=title}
							<a href="{$module_url}{if $k != 'dashboard'}&a={$k}{/if}{if $k != 'settings' && $k != 'realtime'}{$sb_stats_qs}{/if}"{if $sb_stats_page == $k} class="active" aria-current="page"{/if}>{$title|escape}</a>
						{/foreach}
					{/foreach}
				</nav>

				<div class="sbst-main">

				{if $sb_stats_page == 'dashboard'}
					<div class="sbst-dash-bar">
						<div class="sbst-dash-cols" role="group" aria-label="Nombre de colonnes">
							<i class="fa-solid fa-table-columns" aria-hidden="true"></i>
							{foreach from=[1, 2, 3] item=n}<button type="button" class="btn {if $sb_stats_layout.cols == $n}btn--primary{else}btn--outline-primary{/if} btn--sm" data-sbst-cols="{$n}" aria-pressed="{if $sb_stats_layout.cols == $n}true{else}false{/if}">{$n} col.</button>{/foreach}
						</div>
						<div class="sbst-dash-add">
							<label class="sr-only" for="sbst-add">Widget à ajouter</label>
							<select id="sbst-add" class="select" data-sbst-add-select>
								<option value="">Ajouter un widget…</option>
								{foreach from=$sb_stats_catalog key=wid item=wt}<option value="{$wid|escape}">{$wt|escape}</option>{/foreach}
							</select>
							<button type="button" class="btn btn--primary btn--sm" data-sbst-add><i class="fa-solid fa-plus"></i> Ajouter</button>
							<button type="button" class="btn btn--outline-primary btn--sm" data-sbst-reset data-confirm-text="Revenir au tableau de bord par défaut ?"><i class="fa-solid fa-rotate-left"></i> Réinitialiser</button>
						</div>
						<span class="sbst-dash-status" data-sbst-status role="status" aria-live="polite"></span>
					</div>

					<div class="sbst-dash sbst-dash--{$sb_stats_layout.cols}" data-sbst-dash data-layout="{$sb_stats_layout_json|escape}" data-save="{$module_url|escape}&amp;a=dashsave">
						{foreach from=$sb_stats_dash key=ci item=col}
						<div class="sbst-dash-col" data-col="{$ci}">
							{foreach from=$col item=w}
							<section class="card sbst-widget{if $w.collapsed} is-collapsed{/if}" data-widget="{$w.id|escape}" aria-label="{$w.title|escape}">
								<header class="sbst-widget-head">
									<span class="sbst-grip" title="Glisser pour déplacer" aria-hidden="true"><i class="fa-solid fa-grip-vertical"></i></span>
									<h2 class="sbst-widget-title">{$w.title|escape}</h2>
									<div class="sbst-widget-tools">
										<button type="button" data-move="up" title="Monter"><i class="fa-solid fa-arrow-up"></i><span class="sr-only">Monter {$w.title|escape}</span></button>
										<button type="button" data-move="down" title="Descendre"><i class="fa-solid fa-arrow-down"></i><span class="sr-only">Descendre {$w.title|escape}</span></button>
										<button type="button" data-move="left" title="Colonne de gauche"><i class="fa-solid fa-arrow-left"></i><span class="sr-only">Vers la colonne de gauche</span></button>
										<button type="button" data-move="right" title="Colonne de droite"><i class="fa-solid fa-arrow-right"></i><span class="sr-only">Vers la colonne de droite</span></button>
										<button type="button" data-collapse title="Réduire / déplier" aria-expanded="{if $w.collapsed}false{else}true{/if}"><i class="fa-solid fa-chevron-up"></i><span class="sr-only">Réduire ou déplier {$w.title|escape}</span></button>
										<button type="button" data-remove title="Retirer"><i class="fa-solid fa-xmark"></i><span class="sr-only">Retirer {$w.title|escape}</span></button>
									</div>
								</header>
								<div class="sbst-widget-body">
									{if $w.type == 'kpis'}
										{call name=sbst_kpis kpis=$w.kpis compare=$sb_stats_compare_label mini=true}
									{elseif $w.type == 'evolution'}
										<div class="sbst-chart sbst-chart--line sbst-chart--widget" data-sbst-chart="{$w.chart_json|escape}" role="img" aria-label="Visites et pages vues"></div>
									{elseif $w.type == 'realtime'}
										<div class="sbst-live"><span class="sbst-live-value">{$w.live.visits30}</span> visite(s) depuis 30 minutes <small>· {$w.live.visits24} sur 24 h</small></div>
										{call name=sbst_visits_mini visits=$w.visits empty='Personne en ce moment.'}
										<a class="sbst-widget-more" href="{$module_url}&a=realtime">Temps réel <i class="fa-solid fa-up-right-from-square"></i></a>
									{elseif $w.type == 'visits'}
										{call name=sbst_visits_mini visits=$w.visits empty='Aucune visite sur cette période.'}
										<a class="sbst-widget-more" href="{$module_url}&a=visitors{$sb_stats_qs}">Journal des visites <i class="fa-solid fa-up-right-from-square"></i></a>
									{else}
										{call name=sbst_block_body b=$w.block}
									{/if}
								</div>
							</section>
							{/foreach}
						</div>
						{/foreach}
					</div>

				{elseif $sb_stats_page == 'overview'}
					{call name=sbst_kpis kpis=$sb_stats_kpis compare=$sb_stats_compare_label}

					<section class="card sbst-block">
						<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">Évolution des visites</h2></div></div>
						<div class="sbst-chart sbst-chart--line" data-sbst-chart="{$sb_stats_chart_json|escape}" role="img" aria-label="Visites et pages vues par {if $sb_stats_period.chart_step == 'month'}mois{else}jour{/if}"></div>
					</section>

					<div class="sbst-blocks">
						{foreach from=$sb_stats_blocks item=b}{call name=sbst_block b=$b}{/foreach}
					</div>

				{elseif $sb_stats_page == 'visitors' || $sb_stats_page == 'realtime'}
					{if $sb_stats_page == 'realtime'}
					<p class="sbst-live-note" data-sbst-refresh-note hidden>Actualisation automatique arrêtée après {$sb_stats_refresh_max} minutes. <button type="button" class="btn btn--outline-primary btn--sm" data-sbst-refresh-resume>Reprendre</button></p>
					<div class="sbst-kpis" data-sbst-refresh="30" data-sbst-refresh-max="{$sb_stats_refresh_max}">
						<div class="kpi-card"><div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon success"><i class="fa-solid fa-signal"></i></div><div class="kpi-label">Visites (30 min)</div></div></div><div class="kpi-value">{$sb_stats_live.visits30}</div><div class="kpi-compare">{$sb_stats_live.actions30} action(s)</div></div>
						<div class="kpi-card"><div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon info"><i class="fa-solid fa-clock"></i></div><div class="kpi-label">Visites (24 h)</div></div></div><div class="kpi-value">{$sb_stats_live.visits24}</div><div class="kpi-compare">{$sb_stats_live.actions24} action(s)</div></div>
					</div>
					{/if}
					<section class="card sbst-block">
						<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">{if $sb_stats_page == 'realtime'}Dernières visites{else}{$sb_stats_pager.total} visite(s){/if}</h2></div></div>
						{if !$sb_stats_visits}
							<p class="sbst-empty">Aucune visite {if $sb_stats_page == 'realtime'}depuis 24 heures{else}sur cette période{/if}.</p>
						{else}
						<ol class="sbst-visits">
							{foreach from=$sb_stats_visits item=v}
							<li class="sbst-visit">
								<div class="sbst-visit-head">
									<strong>{$v.date_fr}</strong>
									{if $sb_stats_page == 'realtime'}<span class="sbst-chip">il y a {$v.ago}</span>{/if}
									<span class="sbst-chip" title="{$v.device_label|escape}"><i class="fa-solid fa-{$v.device_icon}"></i> {$v.device_label|escape}</span>
									<span class="sbst-chip">{$v.browser|escape} {$v.browser_version|escape} · {$v.os|escape} {$v.os_version|escape}</span>
									{if $v.resolution}<span class="sbst-chip"><i class="fa-solid fa-display"></i> {$v.resolution|escape}</span>{/if}
									{if $v.country}<span class="sbst-chip"><i class="fa-solid fa-globe"></i> {$v.country_label|escape}</span>{/if}
									<span class="sbst-chip"><i class="fa-solid fa-language"></i> {$v.language_label|escape}</span>
									<span class="sbst-chip"><i class="fa-solid fa-{$v.referer_icon}"></i> {$v.referer_label|escape}{if $v.referer_name} : {$v.referer_name|escape}{/if}{if $v.referer_keyword} « {$v.referer_keyword|escape} »{/if}</span>
									<span class="sbst-visit-meta">{$v.actions} action(s) · {$v.duration_fr}</span>
								</div>
								<ol class="sbst-actions">
									{foreach from=$v.actions_list item=a}
									<li class="sbst-action sbst-action--{$a.type|escape}">
										<span class="sbst-action-time">{$a.time}</span>
										<i class="fa-solid fa-{if $a.type == 'page'}file-lines{elseif $a.type == '404'}triangle-exclamation{elseif $a.type == 'download'}file-arrow-down{elseif $a.type == 'outlink'}arrow-up-right-from-square{else}bolt{/if}" aria-hidden="true"></i>
										{if $a.link}<a href="{$a.link|escape}" target="_blank" rel="noopener noreferrer">{if $a.title}{$a.title|escape}{else}{$a.url|escape}{/if}</a>{else}{if $a.title}{$a.title|escape}{else}{$a.url|escape}{/if}{/if}
										{if $a.title && $a.type != 'download'}<span class="sbst-action-url">{$a.url|escape}</span>{/if}
										{if $a.spent}<span class="sbst-action-spent">{$a.spent}</span>{/if}
									</li>
									{/foreach}
								</ol>
							</li>
							{/foreach}
						</ol>
						{if $sb_stats_pager.pages > 1}
						<nav class="sbst-pager" aria-label="Pages du journal">
							{if $sb_stats_pager.page > 1}<a class="btn btn--outline-primary" href="{$module_url}&a=visitors{$sb_stats_qs}&pg={$sb_stats_pager.page - 1}">Plus récentes</a>{/if}
							<span>Page {$sb_stats_pager.page} / {$sb_stats_pager.pages}</span>
							{if $sb_stats_pager.page < $sb_stats_pager.pages}<a class="btn btn--outline-primary" href="{$module_url}&a=visitors{$sb_stats_qs}&pg={$sb_stats_pager.page + 1}">Plus anciennes</a>{/if}
						</nav>
						{/if}
						{/if}
					</section>

				{elseif $sb_stats_page == 'settings'}
					<div class="sbst-settings">
						<div>
						<section class="card">
							<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">{$legend_add_edit}</h2></div></div>
							{include_php file='form.php'}
						</section>
						<section class="card" style="margin-top:20px" id="sbst-geoip">
							<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">Pays des visiteurs</h2></div></div>
							{assign var=g value=$sb_stats_geoip}
							{if $g.installed}
							<dl class="sbst-status">
								<dt>Base installée</dt><dd>DB-IP {$g.version|escape} ({$g.size|replace:'.':','} Mo){if $g.updated}<br><small>le {$g.updated|date_format:'%d/%m/%Y'}</small>{/if}</dd>
								<dt>Contrôle</dt><dd>{if $g.checked}empreinte SHA1 conforme à celle publiée{else}structure et résultats vérifiés{/if}</dd>
							</dl>
							{if $g.old}<p class="sbst-warn"><i class="fa-solid fa-triangle-exclamation"></i> Base de plus de 45 jours : pensez à la mettre à jour (DB-IP en publie une chaque mois).</p>{/if}
							{else}
							<p class="help-block">Aucune base installée : le pays des visiteurs n'est pas déterminé (sauf derrière Cloudflare, qui le fournit). La base gratuite DB-IP « IP to Country Lite » (environ 8 Mo) est téléchargée une fois puis interrogée sur le serveur, sans appel externe par visite ; aucune adresse IP n'est enregistrée.</p>
							{/if}
							{if !$g.curl}<p class="sbst-warn">Extension PHP curl absente : téléchargement impossible.</p>{elseif !$g.writable}<p class="sbst-warn">Dossier datas/modules/stats/geoip/ non inscriptible : téléchargement impossible.</p>{/if}
							<form method="post" action="{$module_url}&amp;a=geoip" class="sbst-geoip-form">
								<input type="hidden" name="op" value="update">
								{if !$g.license}
								<label class="sbst-check"><input type="checkbox" name="geoip_accept" required> <span>J'accepte la <a href="https://creativecommons.org/licenses/by/4.0/deed.fr" target="_blank" rel="noopener">licence CC BY 4.0</a> de la base DB-IP (mention « IP Geolocation by DB-IP » affichée avec les pays).</span></label>
								{else}
								<p class="help-block">Licence CC BY 4.0 acceptée le {$g.license|date_format:'%d/%m/%Y'}.</p>
								{/if}
								<button type="submit" class="btn btn--primary"{if !$g.curl || !$g.writable} disabled{/if}><i class="fa-solid fa-file-arrow-down"></i> {if $g.installed}Mettre à jour la base{else}Télécharger la base{/if}</button>
							</form>
							{if $g.license}
							<form method="post" action="{$module_url}&amp;a=geoip" class="sbst-geoip-form">
								<input type="hidden" name="op" value="auto">
								<label class="sbst-check"><input type="checkbox" name="geoip_auto"{if $g.auto} checked{/if} data-sbst-autosubmit> <span>Mettre à jour automatiquement chaque mois (à partir du 3, lors des visites du site)</span></label>
								<noscript><button type="submit" class="btn btn--outline-primary btn--sm">Enregistrer</button></noscript>
							</form>
							{/if}
							{if $g.installed}
							<form method="post" action="{$module_url}&amp;a=geoip" class="sbst-geoip-form">
								<input type="hidden" name="op" value="remove">
								<button type="submit" class="btn btn--outline-primary btn--sm"><i class="fa-solid fa-xmark"></i> Supprimer la base</button>
							</form>
							{/if}
							<p class="sbst-attrib"><a href="https://db-ip.com" target="_blank" rel="noopener">IP Geolocation by DB-IP</a></p>
						</section>
						</div>
						<div>
							{include file='shared/shared-panel-actions.tpl'}
							<section class="card" style="margin-top:16px">
								<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">Données</h2></div></div>
								<dl class="sbst-status">
									<dt>Journal des visites</dt><dd>{$sb_stats_status.visits} visite(s), {$sb_stats_status.actions} action(s){if $sb_stats_status.oldest}<br><small>depuis le {$sb_stats_status.oldest|date_format:'%d/%m/%Y'}</small>{/if}</dd>
									<dt>Rapports par jour</dt><dd>{if $sb_stats_status.archived_until}jusqu'au {$sb_stats_status.archived_until|date_format:'%d/%m/%Y'}{if $sb_stats_status.first_day}<br><small>depuis le {$sb_stats_status.first_day|date_format:'%d/%m/%Y'}</small>{/if}{else}aucun jour archivé{/if}</dd>
									<dt>Dernier calcul</dt><dd>{if $sb_stats_status.last_run > 0}{$sb_stats_status.last_run|date_format:'%d/%m/%Y %H:%M'}{else}jamais{/if}</dd>
								</dl>
								<p class="help-block">Les jours terminés sont calculés automatiquement, au plus une fois par heure, lors des visites du site.</p>
								<div class="sbst-actions-row">
									<form method="post" action="{$module_url}&amp;a=archive" style="display:inline"><button type="submit" class="btn btn--outline-primary"><i class="fa-solid fa-rotate"></i> Calculer maintenant</button></form>
									<a class="btn btn--danger" href="{$module_url}&a=purgedel" data-confirm="Effacer toutes les statistiques (visites et rapports) ? Les réglages sont gardés. Action irréversible."><i class="fa-solid fa-broom"></i> Tout effacer</a>
								</div>
							</section>

						</div>
					</div>

				{elseif $sb_stats_page != 'error'}
					<div class="sbst-blocks{if count($sb_stats_blocks) == 1} sbst-blocks--single{/if}">
						{foreach from=$sb_stats_blocks item=b}{call name=sbst_block b=$b}{/foreach}
					</div>
				{/if}

				</div>
			</div>

	<script defer src="../datas/modules/stats/admin/assets/stats-admin.js?v={$sb_stats_asset_v}"></script>
	{include file='sb_footer.tpl' page='false' pagef='false'}
