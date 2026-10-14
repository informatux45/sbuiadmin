{* -------------- *}
{* --- MODULE --- *}
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}

		{* ------------------------------------------------ *}
		{*       Write your own code after this line        *}
		{* ------------------------------------------------ *}

			{include file='shared/shared-settings-hero.tpl'}

			<div id="sbsrv" class="grid" data-type="{$sb_srv.type|escape}" data-url="index.php?p=server&amp;ajax=" data-live="{$sb_srv.live|escape}">

				{* --- Type d'hébergement --- *}
				<section class="col-12 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title"><i class="fa-solid fa-server"></i>&nbsp; Hébergement : {$sb_srv.type_name|escape}</h2>
						</div>
						{if $sb_srv.type == $sb_srv.setting}
							<span class="badge warning">forcé dans le réglage</span>
						{else}
							<span class="badge {if $sb_srv.detected.sure}success{else}info{/if}">détecté automatiquement{if !$sb_srv.detected.sure}, probable{/if}</span>
						{/if}
					</div>
					<div class="card-body">
						{if $sb_srv.type == 'shared'}
							<p>Hébergement mutualisé : la machine est partagée avec d'autres sites et gérée par l'hébergeur. Ce qui compte ici, ce sont les <strong>limites de votre site</strong> (PHP, base, espace occupé), pas la RAM ni le processeur du serveur.</p>
						{elseif $sb_srv.type == 'vps'}
							<p>Serveur virtuel (VPS) : les ressources affichées sont celles de la machine virtuelle, partagées entre tous les sites qu'elle héberge.</p>
						{else}
							<p>Serveur dédié : les ressources affichées sont celles de la machine physique.</p>
						{/if}
						{if $sb_srv.detected.clues}
							<p style="color:var(--t-muted)">Indices relevés : {foreach from=$sb_srv.detected.clues item=clue name=clues}{$clue|escape}{if !$smarty.foreach.clues.last} · {/if}{/foreach}.</p>
						{/if}
						{if $sb_srv.type == $sb_srv.setting && $sb_srv.type != $sb_srv.detected.type}
							<p style="color:var(--t-muted)">La détection automatique indiquait : {$sb_srv.detected.name|escape}.</p>
						{/if}
						{include_php file='form.php'}
					</div>
				</section>

				{* --- Relevés en direct --- *}
				<section class="col-12">
					<div class="kpi-grid sbsrv-kpis">
						{if $sb_srv.type == 'shared'}
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon purple"><i class="fa-brands fa-php"></i></div><div class="kpi-label">Version PHP</div></div></div>
							<div class="kpi-value">{$sb_srv.php.version|escape}</div>
							<div class="kpi-compare">{$sb_srv.php.sapi|escape}</div>
						</div>
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon info"><i class="fa-solid fa-memory"></i></div><div class="kpi-label">Mémoire PHP par page</div></div></div>
							<div class="kpi-value">{$sb_srv.php.memory_limit|escape}</div>
							<div class="kpi-compare">limite memory_limit</div>
						</div>
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon warning"><i class="fa-solid fa-upload"></i></div><div class="kpi-label">Envoi de fichiers</div></div></div>
							<div class="kpi-value">{$sb_srv.php.upload_max|escape}</div>
							<div class="kpi-compare">par fichier · {$sb_srv.php.post_max|escape} par envoi</div>
						</div>
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon success"><i class="fa-solid fa-database"></i></div><div class="kpi-label">Base de données</div></div></div>
							<div class="kpi-value" data-srv-bytes="{$sb_srv.db.size|default:''}">{if $sb_srv.db.size === null}—{/if}</div>
							<div class="kpi-compare">{if $sb_srv.db.tables !== null}{$sb_srv.db.tables} tables{else}non détecté{/if}</div>
						</div>
						{else}
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon primary"><i class="fa-solid fa-hard-drive"></i></div><div class="kpi-label">Disque du site</div></div></div>
							<div class="sv-radial">
								<div class="sv-radial-chart">
									<svg viewBox="0 0 80 80">
										<defs>
											<linearGradient id="sbsrv-g-ok" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#38bdf8"/><stop offset="1" stop-color="#2563eb"/></linearGradient>
											<linearGradient id="sbsrv-g-warn" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fcd34d"/><stop offset="1" stop-color="#ea580c"/></linearGradient>
											<linearGradient id="sbsrv-g-full" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fb7185"/><stop offset="1" stop-color="#b91c1c"/></linearGradient>
										</defs>
										<circle class="radial-track" cx="40" cy="40" r="32"/>
										<circle class="radial-fill sbsrv-radial" data-live-radial="disk" cx="40" cy="40" r="32" stroke-dasharray="201.06" stroke-dashoffset="201.06"/>
									</svg>
									<span class="pct" data-live="disk_pct"></span>
								</div>
								<div class="sv-radial-text">
									<div class="kpi-value" data-live="disk_free">—</div>
									<div class="sv-radial-caption" data-live="disk_total"></div>
								</div>
							</div>
						</div>
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon info"><i class="fa-solid fa-memory"></i></div><div class="kpi-label">Mémoire vive</div></div><span class="kpi-pill info" data-live="ram_pct"></span></div>
							<div class="kpi-value" data-live="ram_free">—</div>
							<div class="progress thin"><div class="progress-fill info" data-live-bar="ram"></div></div>
							<div class="kpi-compare" data-live="ram_total"></div>
						</div>
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon purple"><i class="fa-solid fa-microchip"></i></div><div class="kpi-label">Charge du processeur</div></div><span class="kpi-pill info" data-live="load_pct"></span></div>
							<div class="kpi-value" data-live="load">—</div>
							<div class="progress thin"><div class="progress-fill" data-live-bar="load"></div></div>
							<div class="kpi-compare" data-live="cores"></div>
						</div>
						<div class="kpi-card">
							<div class="kpi-top"><div class="kpi-identity"><div class="kpi-icon success"><i class="fa-solid fa-clock"></i></div><div class="kpi-label">En service depuis</div></div></div>
							<div class="kpi-value" data-live="uptime">—</div>
							<div class="kpi-compare" data-live="net"></div>
						</div>
						{/if}
					</div>
					<p class="sbsrv-refresh">Relevé de <span data-live="time"></span>{if $sb_srv.type != 'shared'}, actualisé toutes les 5 secondes{/if}</p>
				</section>

				{* --- Charge du processeur en temps réel (courbe SVG, server.js) --- *}
				{if $sb_srv.type != 'shared'}
				<section class="col-12 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Performance</span>
							<h2 class="card-title">Charge du processeur en temps réel</h2>
						</div>
						<span class="card-action" data-live="cpu_unit"></span>
					</div>
					<div class="chart-canvas-wrap sbsrv-cpu-chart" id="sbsrv-cpu-chart"></div>
					<div class="monthly-footer">
						<div class="stat-cell"><div class="stat-cell-label">Actuelle</div><div class="stat-cell-value" data-live="cpu_now">—</div></div>
						<div class="stat-cell"><div class="stat-cell-label">Moyenne (depuis l'ouverture)</div><div class="stat-cell-value" data-live="cpu_avg">—</div></div>
						<div class="stat-cell"><div class="stat-cell-label">Pic (depuis l'ouverture)</div><div class="stat-cell-value" data-live="cpu_max">—</div></div>
						<div class="stat-cell"><div class="stat-cell-label">Moyennes 5 et 15 min</div><div class="stat-cell-value" data-live="cpu_long">—</div></div>
					</div>
				</section>

				{/if}

				{* --- Relevés bloqués par open_basedir (VPS / dédié) --- *}
				{if $sb_srv.type != 'shared' && $sb_srv.blocked}
				<section class="col-12">
					<div class="alert warning"><span class="ico"><svg viewBox="0 0 24 24"><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/></svg></span><div class="body">
						PHP est confiné au site (<code>open_basedir</code>) : la mémoire vive, le processeur{if $sb_srv.net_blocked} et le réseau{/if} de la machine ne sont pas lisibles, d'où les « non détecté ».
						Pour les afficher, ajoutez ces chemins au réglage open_basedir du site, dans les options PHP de votre panneau d'hébergement (ISPConfig, Plesk, cPanel, etc.) ou dans la configuration PHP (php.ini ou pool PHP-FPM). Sur un hébergement mutualisé, ce réglage n'est généralement pas modifiable :
						<code>{foreach from=$sb_srv.blocked item=f name=bl}{$f|escape}{if !$smarty.foreach.bl.last}:{/if}{/foreach}</code>
						<br>Ces fichiers ne contiennent que des statistiques de la machine, aucune donnée d'un autre site.
					</div></div>
				</section>
				{/if}

				{* --- PHP --- *}
				<section class="col-6 card">
					<div class="card-head"><div class="card-title-wrap"><h2 class="card-title"><i class="fa-brands fa-php"></i>&nbsp; PHP</h2></div></div>
					<div class="card-body">
						<table class="table sbsrv-table">
							<tr><th>Version</th><td>{$sb_srv.php.version|escape} ({$sb_srv.php.sapi|escape})</td></tr>
							<tr><th>Serveur web</th><td>{$sb_srv.php.software|escape|default:'non détecté'}</td></tr>
							<tr><th>Système</th><td>{$sb_srv.php.os|escape}</td></tr>
							<tr><th>Mémoire par page</th><td>{$sb_srv.php.memory_limit|escape} <span class="sbsrv-muted">(cette page : <span data-srv-bytes="{$sb_srv.php.memory_peak}"></span>)</span></td></tr>
							<tr><th>Durée max. d'exécution</th><td>{if $sb_srv.php.max_execution}{$sb_srv.php.max_execution} s{else}illimitée{/if}</td></tr>
							<tr><th>Envoi de fichiers</th><td>{$sb_srv.php.upload_max|escape} par fichier, {$sb_srv.php.post_max|escape} par envoi</td></tr>
							<tr><th>max_input_vars</th><td>{$sb_srv.php.max_input_vars}</td></tr>
							<tr><th>Fuseau horaire</th><td>{$sb_srv.php.timezone|escape}</td></tr>
							<tr><th>OPcache</th><td>{if $sb_srv.php.opcache}{if $sb_srv.php.opcache.enabled}actif, <span data-srv-bytes="{$sb_srv.php.opcache.used}"></span> utilisés sur <span data-srv-bytes="{$sb_srv.php.opcache.used + $sb_srv.php.opcache.free}"></span>{else}inactif{/if}{else}non détecté{/if}</td></tr>
							<tr><th>Extensions</th><td>{foreach from=$sb_srv.php.extensions item=ext}<span class="badge {if $ext.loaded}success{elseif $ext.required}danger{else}warning{/if}" data-tooltip="{if $ext.loaded}présente{elseif $ext.required}manquante, indispensable{else}manquante, facultative{/if}">{$ext.name|escape}</span> {/foreach}</td></tr>
						</table>
					</div>
				</section>

				{* --- Base de données + restrictions --- *}
				<section class="col-6 card">
					<div class="card-head"><div class="card-title-wrap"><h2 class="card-title"><i class="fa-solid fa-database"></i>&nbsp; Base de données et restrictions</h2></div></div>
					<div class="card-body">
						<table class="table sbsrv-table">
							<tr><th>Serveur SQL</th><td>{$sb_srv.db.version|escape|default:'non détecté'}</td></tr>
							<tr><th>Taille de la base</th><td>{if $sb_srv.db.size !== null}<span data-srv-bytes="{$sb_srv.db.size}"></span>, {$sb_srv.db.tables} tables{else}non détecté{/if}</td></tr>
							<tr><th>open_basedir</th><td>{if $sb_srv.basedir}{foreach from=$sb_srv.basedir item=d}<code>{$d|escape}</code><br>{/foreach}{else}aucun (PHP voit tout le serveur){/if}</td></tr>
							<tr><th>Fonctions désactivées</th><td>{if $sb_srv.probe.disabled}{foreach from=$sb_srv.probe.disabled item=f}<span class="badge">{$f|escape}</span> {/foreach}{else}aucune{/if}</td></tr>
						</table>
					</div>
				</section>

				{* --- Espace occupé par le site (calculé à part : parcours des dossiers) --- *}
				<section class="col-12 card">
					<div class="card-head"><div class="card-title-wrap"><h2 class="card-title"><i class="fa-solid fa-folder-open"></i>&nbsp; Espace occupé par le site</h2></div></div>
					<div class="card-body">
						<table class="table sbsrv-table" id="sbsrv-sizes"><tr><td>Calcul en cours…</td></tr></table>
					</div>
				</section>

			</div>
			<!-- /.grid -->

		<script defer src="assets/adminator/server.js?v={$sb_srv.js_v}"></script>

	{include file='sb_footer.tpl' page='false' pagef='false'}
