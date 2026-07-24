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
					<span class="eyebrow">FAQ</span>
					<h1 class="hero-title">FAQ</h1>
					<p class="hero-sub">Gérez vos questions fréquentes et leurs réponses.</p>
				</div>
				<div class="hero-actions">
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown>
							Questions
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu" style="min-width:220px">
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=faq">Toutes les questions</a>
							<div class="dd-divider"></div>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=faq&a=add">+1 question</a>
						</div>
					</div>
					<div class="dd-wrap">
						<button class="btn btn--outline-primary" data-dropdown>
							Catégories
							<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="dd-menu" role="menu" style="min-width:220px">
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=faq&a=category">Toutes les catégories</a>
							<div class="dd-divider"></div>
							<a class="dd-menu-item" href="{$smarty.const._AM_SITE_URL}index.php?p=faq&a=categoryadd">+1 catégorie</a>
						</div>
					</div>
					{if isset($all) && (!isset($smarty.get.a) || $smarty.get.a == '' || $smarty.get.a == 'del')}
						<button class="btn btn--ghost" type="button" onclick="location.href='index.php?p=faq&a=sort'">
							Trier les questions
						</button>
					{/if}
					<button class="btn btn--ghost" type="button" data-toggle="modal" data-target="#sbfaq_shortcodes">
						Shortcodes
					</button>
				</div>
			</section>

            <div class="grid">

				{if isset($all) && (!isset($smarty.get.a) || $smarty.get.a == '' || $smarty.get.a == 'del')}
                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Gestion de vos questions</h2>
						</div>
                    </div>
                            <div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-faq">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-faq" data-datatable>
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
										{if $allfaq}
											{foreach from=$allfaq item=faq}
												<tr class="data-row">
													<td>{$faq.sort}</td>
													<td>{$faq.category_name|unescape:"htmlall"}</td>
													<td>{$faq.question|unescape:"htmlall"}</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $faq.active}var(--success){else}var(--danger){/if}" title="Statut {if $faq.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$faq.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=del&id={$faq.id}" title="Supprimer">
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
							<div class="data-foot" data-datatable-foot="dataTables-faq">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>

                </section>
				{/if}

				{if isset($allcat) && isset($smarty.get.a) && ($smarty.get.a == 'category' || $smarty.get.a == 'categorydel')}

                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Gestion de vos catégories</h2>
						</div>
                    </div>
                            <div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-faqcategories">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-faqcategories" data-datatable>
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
										{if $allcategory}
											{foreach from=$allcategory item=category}
												<tr class="data-row">
													<td>{$category.name|unescape:"htmlall"}</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $category.active}var(--success){else}var(--danger){/if}" title="Statut {if $category.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=categoryedit&id={$category.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=categorydel&id={$category.id}" title="Supprimer">
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
							<div class="data-foot" data-datatable-foot="dataTables-faqcategories">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>

                </section>
				{/if}

				{if (!isset($all) || !isset($allfaq)) && (!isset($allcat)) && isset($smarty.get.a) && $smarty.get.a != 'del' && $smarty.get.a != 'category' && $smarty.get.a != 'categorydel' }

					<section class="col-8 card">
						<div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">{$legend_add_edit}</h2>
							</div>
						</div>
							{* Afficher le formulaire ADD/EDIT/SORT *}
							{include_php file='form.php'}
					</section>

					{if $smarty.get.a != 'sort'}
					<div class="col-4">
						{* ------------------------------------ *}
						{* --- Include Shared Panel Actions --- *}
						{include file='shared/shared-panel-actions.tpl'}
						{* ------------------------------------ *}
						{* ------------------------------------ *}
					</div>
					{/if}

				{/if}

            </div>
            <!-- /.grid -->

			<div class="modal fade" id="sbfaq_shortcodes" tabindex="-1" role="dialog" aria-labelledby="sbfaq_shortcodes_label" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="sbfaq_shortcodes_label">Shortcodes</h4>
						</div>
						<div class="modal-body">
							<div style="font-size: 12px;">
							<span style="font-weight: bold;">[CS name=sbfaq]</span><br>
							<span style="font-style: italic;">Affiche toutes les questions actives, toutes catégories confondues</span><br><br>
							<span style="font-weight: bold;">[CS name=sbfaq id=1]</span><br>
							<span style="font-style: italic;">Affiche uniquement les questions actives de la catégorie à l'ID 1</span><br><br>
							<span style="font-weight: bold;">Insertion directe dans un tpl (module inc)</span><br>
							<span style="font-style: italic;">Pour afficher la FAQ directement dans le template d'un module, sans passer par un contenu éditable, insérer le shortcode via le modifier Smarty <code>sbGetShortcode</code> :</span><br>
							<code>{ldelim}"[CS name=sbfaq]"|sbGetShortcode{rdelim}</code><br>
							<code>{ldelim}"[CS name=sbfaq id=1]"|sbGetShortcode{rdelim}</code><br><br>
							<span style="font-style: italic;">Ou via la fonction Smarty <code>insert</code> <code>sbDoShortcode</code> (ex : navigation.tpl, index.tpl d'un thème) :</span><br>
							<code>{ldelim}insert name="sbDoShortcode" code="[CS name=sbfaq]"{rdelim}</code><br>
							<code>{ldelim}insert name="sbDoShortcode" code="[CS name=sbfaq id=1]"{rdelim}</code>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			{if $sort}
				$( "#sortable" ).sortable({
					axis: "y",
					placeholder: "ui-state-highlight"
				});
				$( "#sortable" ).disableSelection();
			{/if}
		});
		</script>

	{include file='sb_footer.tpl' page='false' pagef='false'}
