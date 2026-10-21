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
					{elseif $sb_sentinelle_page == 'integrity'}
					<h1 class="hero-title">Intégrité des fichiers</h1>
					<p class="hero-sub">Les fichiers du site comparés à la version signée : modifications, fichiers PHP ajoutés, scripts dans les médias.</p>
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
							{if $sb_sentinelle_page == 'settings'}Réglages{elseif $sb_sentinelle_page == 'bans'}IP bannies{elseif $sb_sentinelle_page == 'advisor'}Conseiller{elseif $sb_sentinelle_page == 'integrity'}Intégrité{else}Journal de sécurité{/if}
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu">
							<a class="dd-menu-item" href="{$module_url}"{if $sb_sentinelle_page == 'log'} style="color:var(--primary);font-weight:600"{/if}>Journal de sécurité</a>
							<a class="dd-menu-item" href="{$module_url}&a=advisor"{if $sb_sentinelle_page == 'advisor'} style="color:var(--primary);font-weight:600"{/if}>Conseiller</a>
							<a class="dd-menu-item" href="{$module_url}&a=integrity"{if $sb_sentinelle_page == 'integrity'} style="color:var(--primary);font-weight:600"{/if}>Intégrité</a>
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
			{elseif $sb_sentinelle_page == 'integrity'}
			<div class="grid">
				<section class="col-12 card">
					<div class="card-head"><div class="card-title-wrap"><h2 class="card-title">Dernière analyse</h2></div></div>
					{if $sb_integrity}
						<p>Version {$sb_integrity.version|escape}, le {$sb_integrity.date|date_format:"%d.%m.%Y à %H:%M"} : <strong>{$sb_integrity.checked}</strong> fichiers comparés à la liste signée.</p>
						{if $sb_integrity.error}<div class="alert danger"><div class="body"><div class="title">Analyse impossible</div><p>{$sb_integrity.error|escape}</p></div></div>{/if}
						<div class="kpi-grid" style="margin:12px 0">
							<div class="kpi-card"><div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon {if $sb_integrity.counts.modified}danger{else}success{/if}"><i class="fa-solid fa-file-pen"></i></div><div class="kpi-label">Fichiers modifiés</div></div></div><div class="kpi-value">{$sb_integrity.counts.modified}</div></div>
							<div class="kpi-card"><div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon {if $sb_integrity.counts.added}danger{else}success{/if}"><i class="fa-solid fa-file-circle-plus"></i></div><div class="kpi-label">PHP ajoutés</div></div></div><div class="kpi-value">{$sb_integrity.counts.added}</div></div>
							<div class="kpi-card"><div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon {if $sb_integrity.counts.upload}danger{else}success{/if}"><i class="fa-solid fa-photo-film"></i></div><div class="kpi-label">Scripts dans upload/</div></div></div><div class="kpi-value">{$sb_integrity.counts.upload}</div></div>
							<div class="kpi-card"><div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon {if $sb_integrity.counts.missing}warning{else}success{/if}"><i class="fa-solid fa-file-circle-question"></i></div><div class="kpi-label">Fichiers manquants</div></div></div><div class="kpi-value">{$sb_integrity.counts.missing}</div></div>
							<div class="kpi-card"><div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon {if $sb_integrity.counts.approved_changed|default:0}danger{elseif $sb_integrity.counts.watched|default:0}warning{else}success{/if}"><i class="fa-solid fa-file-shield"></i></div><div class="kpi-label">Fichiers du site approuvés</div></div></div><div class="kpi-value">{$sb_integrity.counts.approved|default:0}</div><div class="kpi-compare">{$sb_integrity.counts.approved_changed|default:0} modifié(s) · {$sb_integrity.counts.watched|default:0} sous préfixe surveillé</div></div>
						</div>
						{foreach from=['modified' => 'Fichiers modifiés (contenu différent de la version signée)', 'approved_changed' => 'Fichiers du site approuvés puis modifiés', 'added' => 'Fichiers PHP ajoutés (absents de la version signée)', 'watched' => 'PHP nouveaux sous un préfixe surveillé (alerte)', 'upload' => 'Scripts dans les médias', 'missing' => 'Fichiers manquants'] key=k item=title}
							{if !empty($sb_integrity.$k)}
							<h3 style="margin-top:16px">{$title}</h3>
							<ul style="font-family:monospace;font-size:12px;word-break:break-all">{foreach from=$sb_integrity.$k item=f}<li>{$f|escape}</li>{/foreach}</ul>
							{if $sb_integrity.counts.$k > 100}<p class="help-block">100 affichés sur {$sb_integrity.counts.$k}.</p>{/if}
							{/if}
						{/foreach}
						{if $sb_integrity.modules}<p class="help-block" style="margin-top:12px">Modules non vérifiés (version différente de celle publiée avec le cœur) : {foreach from=$sb_integrity.modules item=mm name=mods}{$mm|escape}{if !$smarty.foreach.mods.last}, {/if}{/foreach}.</p>{/if}
					{else}
						<p>Aucune analyse pour l'instant.</p>
					{/if}
					<form method="post" action="{$module_url}&a=integrity" style="margin-top:16px">
						<button class="btn btn--primary" type="submit">Analyser maintenant</button>
						<span class="help-block" style="margin-left:8px">Analyse quotidienne automatique : {if $sb_integrity_auto}activée{else}désactivée (Réglages){/if}.</span>
					</form>
					{if $sb_integrity && !$sb_integrity.error && $sb_to_approve > 0}
					<form method="post" action="{$module_url}&a=approve" style="margin-top:16px;padding:14px;border:1px solid var(--border);border-radius:10px">
						<h3 style="margin:0 0 6px">Fichiers propres au site</h3>
						<p class="help-block" style="margin:0 0 10px">{if $sb_to_approve > 1}Ces {$sb_to_approve} fichiers sont à vous{else}Ce fichier est à vous{/if} (bibliothèques dans vendor/, ajouts du site) ? Approuvez{if $sb_to_approve > 1}-les{else}-le{/if} : l'empreinte est enregistrée et le fichier n'est plus signalé, sauf si son contenu change. <strong>N'approuvez qu'après avoir vérifié la liste</strong> : un fichier inconnu peut être une porte dérobée.</p>
						<div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
							<div class="field" style="max-width:340px;flex:1 1 240px">
								<label class="field-label" for="sb-approve-password">Votre mot de passe (confirmation)</label>
								<input class="input" type="password" id="sb-approve-password" name="password" autocomplete="current-password" required>
							</div>
							<button class="btn btn--warning" type="submit">{if $sb_to_approve > 1}Approuver ces {$sb_to_approve} fichiers{else}Approuver ce fichier{/if}</button>
						</div>
					</form>
					{/if}
					{if $sb_integrity_approved}
					<form method="post" action="{$module_url}&a=unapprove" style="margin-top:12px">
						<p class="help-block" style="margin:0 0 6px">{$sb_integrity_approved} {if $sb_integrity_approved > 1}fichiers du site approuvés{else}fichier du site approuvé{/if}.{if $sb_integrity_prefixes} Préfixes surveillés : {foreach from=$sb_integrity_prefixes item=pp name=pfx}<code>{$pp|escape}</code>{if !$smarty.foreach.pfx.last}, {/if}{/foreach} (Réglages).{/if}</p>
						<div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
							<div class="field" style="max-width:340px;flex:1 1 240px">
								<label class="field-label" for="sb-unapprove-password">Votre mot de passe (confirmation)</label>
								<input class="input" type="password" id="sb-unapprove-password" name="password" autocomplete="current-password" required>
							</div>
							<button class="btn btn--ghost" type="submit">Retirer toutes les approbations</button>
						</div>
					</form>
					{elseif $sb_integrity_prefixes}
					<p class="help-block" style="margin-top:12px">Préfixes surveillés : {foreach from=$sb_integrity_prefixes item=pp name=pfx}<code>{$pp|escape}</code>{if !$smarty.foreach.pfx.last}, {/if}{/foreach} (Réglages).</p>
					{/if}
					<p class="help-block" style="margin-top:12px">Un fichier modifié par vous (personnalisation) apparaît aussi : vérifiez chaque ligne. Un fichier PHP ajouté ou un script dans upload/ que vous ne connaissez pas est le signe le plus courant d'un site piraté. Les fichiers propres au site (sbconfig.php, .htaccess, sbdbconfig.php...) ne sont pas comparés.</p>
				</section>
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
									<td style="white-space:normal;min-width:200px;max-width:340px;overflow-wrap:anywhere">{$ban.reason|escape}</td>
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
									<td>{if $log.country}{$log.country|escape}{else}—{/if}</td>
									<td>{$log.type_label|escape}{if $log.user} <small>({$log.user|escape})</small>{/if}</td>
									<td><span class="badge {if $log.severity == 'critical'}danger{elseif $log.severity == 'warning'}warning{else}info{/if}">{if $log.severity == 'critical'}critique{elseif $log.severity == 'warning'}alerte{else}info{/if}</span></td>
									<td>{if $log.action == 'banned'}IP bannie{elseif $log.action == 'blocked'}bloqué{else}noté{/if}</td>
									<td>{if $log.context == 'admin'}admin{else}site{/if}</td>
									<td style="white-space:normal;min-width:180px;max-width:260px;word-break:break-all"><small>{$log.method|escape} {$log.uri|escape}</small></td>
									<td style="white-space:normal;min-width:220px;max-width:300px;overflow-wrap:anywhere"><small>{$log.detail|escape}</small></td>
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
