{* -------------- *}
{* --- SYSTEM --- *}
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}

			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}

			<section class="hero">
				<div class="hero-text">
					<span class="eyebrow">Dashboard</span>
					<h1 class="hero-title">Widgets du tableau de bord</h1>
					<p class="hero-sub">Choisissez quelles tables SQL apparaissent en page d'accueil, sous forme de tuiles et de listes.</p>
				</div>
				<div class="hero-actions">
					{if $all && (!isset($smarty.get.a) || $smarty.get.a == '' || $smarty.get.a == 'del' || $smarty.get.a == 'up' || $smarty.get.a == 'down')}
						<a class="btn btn--primary" href="{$module_url}&a=add">+1 widget</a>
					{elseif !$all && isset($smarty.get.a) && $smarty.get.a != 'del' && $smarty.get.a != 'up' && $smarty.get.a != 'down'}
						<a class="btn btn--ghost" href="{$module_url}"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Retour aux widgets</a>
					{/if}
				</div>
			</section>

			<div class="grid">

				{if $all && (!isset($smarty.get.a) || $smarty.get.a == '' || $smarty.get.a == 'del' || $smarty.get.a == 'up' || $smarty.get.a == 'down')}
				<section class="col-12 card">
					<div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Vos widgets</h2>
						</div>
					</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-dashboardwidgets">
                                    <thead>
                                        <tr>
                                            {foreach from=$sb_table_header item=header}
												<th data-sort="false">{$header}</th>
											{/foreach}
                                        </tr>
                                    </thead>
                                    <tbody>
										{if $all_widgets}
											{foreach from=$all_widgets item=widget}
												<tr class="data-row">
													<td>{$widget.position}</td>
													<td>{if $widget.icon}<i class="fa fa-{$widget.icon} fa-fw" style="color:var(--{$widget.color})"></i> {/if}{$widget.title|unescape:"htmlall"}</td>
													<td>{if isset($sb_type_options[$widget.type])}{$sb_type_options[$widget.type]}{else}{$widget.type}{/if}</td>
													<td>
														{if $widget.type == 'system'}
															{if isset($sb_system_options[$widget.widget_key])}{$sb_system_options[$widget.widget_key]}{else}{$widget.widget_key}{/if}
														{elseif $widget.type == 'weather'}
															{$widget.location}
														{elseif $widget.type == 'html' || $widget.type == 'text'}
															<span style="color:var(--t-light)">Contenu personnalisé</span>
														{elseif $widget.type == 'rss'}
															{$widget.location} ({$widget.value_column} art.)
														{elseif $widget.type == 'iframe'}
															{$widget.location}
														{elseif $widget.type == 'logs'}
															logs/{$widget.location} ({$widget.value_column} lignes)
														{elseif $widget.type == 'logaccess'}
															{$widget.value_column} dernières connexions
														{elseif isset($sb_table_titles[$widget.table_name])}
															{$sb_table_titles[$widget.table_name]}
														{else}
															{$widget.table_name}
														{/if}
													</td>
													<td>
														{if $widget.type == 'table' && $widget.date_column}
															<span style="color:var(--success)" title="Colonne date : {$widget.date_column}">Tendance{if $widget.show_chart} + graphique{/if}</span>
														{else}
															<span style="color:var(--t-light)">—</span>
														{/if}
													</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $widget.active}var(--success){else}var(--danger){/if}" title="Statut {if $widget.active}actif{else}inactif{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=up&id={$widget.id}" title="Monter">
																<svg viewBox="0 0 24 24"><path d="m18 15-6-6-6 6"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=down&id={$widget.id}" title="Descendre">
																<svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$widget.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ce widget ?" href="{$module_url}&a=del&id={$widget.id}" title="Supprimer">
																<svg viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
															</a>
														</div>
													</td>
												</tr>
											{/foreach}
										{else}
											<tr class="data-row"><td colspan="6">Aucun widget configuré pour l'instant.</td></tr>
										{/if}
                                    </tbody>
                                </table>
                            </div>
				</section>
				{/if}

				{if !$all && isset($smarty.get.a) && $smarty.get.a != 'del' && $smarty.get.a != 'up' && $smarty.get.a != 'down'}

					<section class="col-8 card">
						<div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">{$legend_add_edit|unescape:"htmlall"}</h2>
							</div>
						</div>
							{* Afficher le formulaire ADD/EDIT *}
							{include_php file='form.php'}
					</section>

					<div class="col-4">
						{* ------------------------------------ *}
						{* --- Include Shared Panel Actions --- *}
						{include file='shared/shared-panel-actions.tpl'}
						{* ------------------------------------ *}
						{* ------------------------------------ *}
					</div>

				{/if}

            </div>
            <!-- /.grid -->

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		{if isset($sb_dashboard_schema_json)}
			<script>window.sbDashboardSchema = {$sb_dashboard_schema_json}; window.sbDashboardTableLinks = {$sb_dashboard_table_links_json};</script>
			<script defer src="assets/adminator/dashboard-widget-form.js"></script>
		{/if}

	{include file='sb_footer.tpl' page='false' pagef='false'}
