{* --------------- *}
{* --- MODULES --- *}	
{* --------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			<section class="hero">
				<div class="hero-text">
					<span class="eyebrow">Sandbox</span>
					<h1 class="hero-title">Sandbox</h1>
					<p class="hero-sub">Page de démonstration des composants du constructeur de formulaires (developpeurs).</p>
				</div>
				<div class="hero-actions">
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown>
							Actions
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu">
							<a class="dd-menu-item" href="index.php?p=sandbox"{if $all} style="color:var(--primary);font-weight:600"{/if}>Tous les enregistrements</a>
							<a class="dd-menu-item" href="index.php?p=sandbox&a=add"{if $smarty.get.a == 'add'} style="color:var(--primary);font-weight:600"{/if}>Ajouter un enregistrement</a>
							<a class="dd-menu-item" href="index.php?p=sandbox&a=sort"{if $sort} style="color:var(--primary);font-weight:600"{/if}>Trier les enregistrements</a>
							<div class="dd-divider"></div>
							<a class="dd-menu-item" href="index.php?p=sandbox">Autres liens</a>
							<a class="dd-menu-item" href="index.php?p=sandbox">Autres liens 2</a>
							<a class="dd-menu-item" href="index.php?p=sandbox">Autres liens 3</a>
						</div>
					</div>
				</div>
			</section>

            <div class="grid">
                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">{if $all}Gestion de vos enregistrements{else}{$legend_add_edit}{/if}</h2>
						</div>
                    </div>
							{if $all}
							<div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-graduates">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-graduates" data-datatable data-page-size="25">
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
										{if $allsandbox}
											{foreach from=$allsandbox item=record}
												<tr class="data-row">
													<td>{$record.nom|unescape:"htmlall"}</td>
													<td>{$record.telephone|unescape:"htmlall"}</td>
													<td>{$record.email|unescape:"htmlall"}</td>
													<td>{$record.company|unescape:"htmlall"}</td>
													<td>{$record.country|unescape:"htmlall"}</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $record.active}var(--success){else}var(--danger){/if}" title="Statut {if $record.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$record.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															{* Add "data-confirm" attribute to trigger a confirmation dialog before navigating *}
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=del&id={$record.id}" title="Supprimer">
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
							<div class="data-foot" data-datatable-foot="dataTables-graduates">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>
							{else}
								{* Afficher le formulaire ADD/EDIT *}
								{include_php file='form.php'}
							{/if}
                </section>
            </div>
            <!-- /.grid -->

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Your own code
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

	{include file='sb_footer.tpl' page='false' pagef='upload'}