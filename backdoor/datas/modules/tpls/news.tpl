{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='news_bar.tpl'}

            <div class="grid">

				{if isset($all) && !isset($smarty.get.a)}
                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Articles</span>
							<h2 class="card-title">Gestion de vos articles</h2>
						</div>
                    </div>
                            <div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher un article..." data-datatable-search="dataTables-news">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-news" data-datatable>
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
										{if isset($allnew)}
											{foreach from=$allnew item=news}
												<tr class="data-row">
													<td>{$news.date|unescape:"htmlall"}</td>
													<td>{$news.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$news.catname|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>[CS id={$news.id} name=sbnews_item]</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $news.active}var(--success){else}var(--danger){/if}" title="Statut {if $news.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$news.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon" data-confirm="Sûr de vouloir supprimer ceci ?" href="{$module_url}&a=del&id={$news.id}" title="Supprimer">
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
							<div class="data-foot" data-datatable-foot="dataTables-news">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>

                </section>
				{/if}

				{if isset($allcat)}

                <section class="col-12 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<span class="eyebrow">Articles</span>
							<h2 class="card-title">Gestion de vos catégories</h2>
						</div>
                    </div>
                            <div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-categories">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-categories" data-datatable>
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
													<td>{$category.sort}</td>
													<td>{$category.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>[CS id={$category.id} name=sbnews_latest]</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $category.active}var(--success){else}var(--danger){/if}" title="Statut {if $category.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=tpl_list&id={$category.id}" title="TPL LIST">
																<svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=tpl_single&id={$category.id}" title="TPL SINGLE">
																<svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=settingscategory&id={$category.id}" title="Paramètres">
																<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
															</a>
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
							<div class="data-foot" data-datatable-foot="dataTables-categories">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>

                </section>
				{/if}

				{if (!isset($all) || !isset($allcategory)) && (isset($smarty.get.a) && $smarty.get.a != 'category' && $smarty.get.a != 'del' && $smarty.get.a != 'categorydel') }

					<section class="col-8 card">
						<div class="card-head">
							<div class="card-title-wrap">
								<span class="eyebrow">Articles</span>
								<h2 class="card-title">{$legend_add_edit}</h2>
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
						{if isset($smarty.get.a) && ($smarty.get.a == 'edit' || $smarty.get.a == 'add')}
						<div class="card" style="margin-bottom:20px">
							<div class="card-head">
								<div class="card-title-wrap">
									<h2 class="card-title">Exemple d'articles (layout)</h2>
								</div>
							</div>
							<img src="img/news-template.jpg" alt="" style="width: 100%;border-radius:8px">
						</div>
						<!-- /.card -->
						{/if}
							
						{if isset($smarty.get.a) && ($smarty.get.a == 'tpl_list' OR $smarty.get.a == 'tpl_single')}

								<div class="card" style="margin-bottom:20px;border-left:3px solid var(--danger)">
									<div class="card-head">
										<div class="card-title-wrap">
											<h2 class="card-title">Variables à utiliser dans votre code {if $smarty.get.a == 'tpl_list'}TPL LIST{else}TPL SINGLE{/if}</h2>
										</div>
									</div>
										<p>
											{if $smarty.get.a == 'tpl_list'}
												{literal}
													<p><i>TITRE de l'article</i><br>
													<b>{$news.title}</b>
													</p>
													<p><i>DESCRIPTION courte (intro)</i><br>
													<b>{$news.desc_short}</b>
													</p>
													<p><i>DESCRIPTION entière</i><br>
													<b>{$news.desc_full}</b>
													</p>
													<p><i>IMAGE</i><br>
													<b>{$news.image}</b>
													</p>
													<p><i>LIEN IMAGE</i><br>
													<b>{$smarty.const._AM_MEDIAS_URL}/{$news.image}</b>
													</p>
													<p><i>DATE (US)</i><br>
													<b>{$news.date}</b>
													</p>
													<p><i>DATE (FR)</i><br>
													<b>{$news.date|@sbConvertDate:"FR"}</b>
													</p>
													<p><i>LIEN ARTICLE</i><br>
													<b>{seo url="index.php?p=news&op=article&id={$news.id}" rewrite="news/article/{$news.id}<br>/{$news.title|unescape:"htmlall"<br>|@sbDisplayLang:"`$smarty.session.lang`"|<br>strip_tags|@sbRewriteString|@strtolower}"}</b>
													</p>
													<p><i>PIPEs possible</i><br>
													<b>|unescape:"htmlall"</b>&nbsp;(Echappement HTML)<br>
													<b>|@sbDisplayLang:"`$smarty.session.lang`"</b>&nbsp;(Convertion Langue Session)
													</p>
												{/literal}
											{else}
												{literal}
													<p><i>TITRE de l'article</i><br>
													<b>{$item.title}</b>
													</p>
													<p><i>SOUS TITRE de l'article</i><br>
													<b>{$item.subtitle}</b>
													</p>
													<p><i>DESCRIPTION entière</i><br>
													<b>{$item.desc_full}</b>
													</p>
													<p><i>IMAGE</i><br>
													<b>{$item.image}</b>
													</p>
													<p><i>LIEN IMAGE</i><br>
													<b>{$smarty.const._AM_MEDIAS_URL}/{$item.image}</b>
													</p>
													<p><i>DATE (US)</i><br>
													<b>{$item.date}</b>
													</p>
													<p><i>DATE (FR)</i><br>
													<b>{$item.date|@sbConvertDate:"FR"}</b>
													</p>
													<p><i>PIPEs possible</i><br>
													<b>|unescape:"htmlall"</b>&nbsp;(Echappement HTML)<br>
													<b>|@sbDisplayLang:"`$smarty.session.lang`"</b>&nbsp;(Convertion Langue Session)
													</p>
												{/literal}
											{/if}
										</p>
									<div style="border-top:1px solid var(--border-soft);margin-top:14px;padding-top:14px">
										<a href="http://www.smarty.net/" target="_blank">Voir Doc smarty pour plus d'infos</a>
									</div>
								</div>

						{/if}

						{if isset($smarty.get.a) && $smarty.get.a != 'tpl_list' && $smarty.get.a != 'tpl_single' && $smarty.get.a != 'settings' && $smarty.get.a != 'edit' && $smarty.get.a != 'categoryadd' && $smarty.get.a != 'add'}

							<div class="card">
								<div class="card-head">
									<div class="card-title-wrap">
										<h2 class="card-title">Templates Catégorie</h2>
									</div>
								</div>
									<p style="text-align: center;">
										<b>Modifier le template de l'intro (LIST)</b>
										<br>
										<button onclick="location.href='index.php?p=news&a=tpl_list&id={$smarty.get.id}'" type="button" class="btn btn--primary">
											Template LIST
										</button>
									</p>
									<br>
									<p style="text-align: center;">
										<b>Modifier le template de l'article (SINGLE)</b>
										<br>
										<button onclick="location.href='index.php?p=news&a=tpl_single&id={$smarty.get.id}'" type="button" class="btn btn--primary">
											Template SINGLE
										</button>
									</p>
							</div>
							<!-- /.card -->

						{/if}

					</div>
					<!-- /.col-4 -->

				{/if}

            </div>
            <!-- /.grid -->

			<div class="modal fade" id="sbnews_shortcodes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">Shortcodes</h4>
						</div>
						<div class="modal-body" style="overflow: hidden;">
							<img src="img/modules/sbnews_shortcodes_1.png" alt="" style="float: left; margin: 0 15px 10px 0; border: 1px solid gray; width: 220px;" />
							<div style="font-size: 12px;">
							<span style="font-weight: bold;">[CS name=sbnews_blocks_recent]</span><br>
							<span style="font-style: italic;">Affiche le bloc d'articles de toutes les catégories au nombre par défaut de 5</span><br>
							<span style="font-weight: bold;">[CS name=sbnews_blocks_recent count=3]</span><br>
							<span style="font-style: italic;">Affiche le bloc d'articles de toutes les catégories au nombre de 3</span><br>
							<span style="font-weight: bold;">[CS name=sbnews_blocks_recent count=3 truncate=40]</span><br>
							<span style="font-style: italic;">Affiche le bloc d'articles de toutes les catégories au nombre de 3 avec des titres tronquer à 40 caractères</span><br>
							<span style="font-weight: bold;">[CS name=sbnews_blocks_recent count=3 truncate=40 id=1]</span><br>
							<span style="font-style: italic;">Affiche le bloc d'articles de la catégorie à l'ID 1 au nombre de 3 avec des titres tronquer à 40 caractères</span>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script src="inc/plugins/ace/ace.js" type="text/javascript" charset="utf-8"></script>
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

			var $editor = $('#code');
			if ($editor.length > 0) {
				var editor = ace.edit('code');
				editor.setTheme("ace/theme/textmate");
				editor.session.setMode("ace/mode/smarty");
				editor.getSession().setTabSize(4);
				editor.getSession().setUseWrapMode(true);
				editor.setShowPrintMargin(true);
				editor.setHighlightActiveLine(true);
				$editor.closest('form').submit(function() {
					var code = editor.getValue();
					$('input[name="code_hidden"]').val(code);
				});
			}
			
		});
		</script>
			
	{include file='sb_footer.tpl' page='false' pagef='false'}

