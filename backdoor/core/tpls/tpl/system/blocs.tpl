{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			<section class="hero">
				<div class="hero-text">
					<span class="eyebrow">Pages</span>
					<h1 class="hero-title">Blocs</h1>
					<p class="hero-sub">Gérez les blocs de contenu réutilisables de votre site.</p>
				</div>
				<div class="hero-actions">
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown>
							Pages
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu" style="min-width:220px">
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=pages">Toutes les pages</a>
							<div class="dd-divider"></div>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=pages&a=add">+1 page</a>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=pages&a=addcustom">+1 page (Custom)</a>
						</div>
					</div>
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown>
							Blocs
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu" style="min-width:220px">
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=blocs">Tous les blocs</a>
							<div class="dd-divider"></div>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=blocs&a=add">+1 bloc</a>
						</div>
					</div>
				</div>
			</section>

            <div class="grid">
				{if $all}
                <section class="col-6 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Trier vos blocs par page</h2>
						</div>
                    </div>
							<p>
								{foreach from=$all_pages item=page}
									<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=blocs&a=sort&pid={$page.id}'">
										{$page.title|unescape:"htmlall"|@sbDisplayLang}
									</button>
								{/foreach}
							</p>
                </section>

                <section class="col-6 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Trier vos blocs par module</h2>
						</div>
                    </div>
							<p>
								{section name=mod loop=$all_modules}
									<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=blocs&a=sortmod&pid={$all_modules[mod]}'">
										{$all_modules[mod]|capitalize}
									</button>
								{/section}
							</p>
                </section>

                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">{if $all}Gestion de vos blocs{else}{$legend_add_edit}{/if}</h2>
						</div>
                    </div>
							<div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-blocs">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-blocs" data-datatable>
                                    <thead>
                                        <tr>
                                            {foreach from=$sb_table_header item=header}
												<th{if $header@last} data-sort="false"{/if}>
													{$header}{if !$header@last} <span class="sort"><svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span>{/if}
												</th>
											{/foreach}
                                        </tr>
                                    </thead>
                                    <tbody>
										{if $allbloc}
											{foreach from=$allbloc item=bloc}
												<tr class="data-row">
													<td>{$bloc.name|unescape:"htmlall"|@sbDisplayLang}</td>
													{*<td>{$bloc.pagename|unescape:"htmlall"|@sbDisplayLang}</td>*}
													<td>{$bloc.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $bloc.active}var(--success){else}var(--danger){/if}" title="Statut {if $bloc.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$bloc.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=del&id={$bloc.id}" title="Supprimer">
																<svg viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
															</a>
														</div>
													</td>
												</tr>
											{/foreach}
										{/if}
                                    </tbody>
                                </table>
                            </div>
							<div class="data-foot" data-datatable-foot="dataTables-blocs">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>
                </section>

				{else}
                <section class="col-8 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">{if $all}Gestion de vos blocs{else}{$legend_add_edit}{/if}</h2>
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
                    <div class="card">
						<div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">Exemple de grille (layout)</h2>
							</div>
						</div>
							<img src="img/cssgridlayout.jpg" alt="" style="width: 100%;">
                    </div>
                    <!-- /.card -->
				</div>
				<!-- /.col-4 -->

				{/if}
            </div>
            <!-- /.grid -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			{if $sort}
				$( "#sortable" ).sortable({
					axis: "y",
					placeholder: "ui-state-highlight",
					{*update: function() { 
						var order = $('#sortable').sortable('serialize'); 
						$.post('sortable.php',order);
					}*}
				});
				$( "#sortable" ).disableSelection();
			{/if}
		});
		</script>
			
	{include file='sb_footer.tpl' page='false' pagef='false'}

