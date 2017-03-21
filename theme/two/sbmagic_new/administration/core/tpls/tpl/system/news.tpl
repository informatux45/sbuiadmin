{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='system/news_bar.tpl'}

            <div class="row">
				
				{if $all}
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-rss fa-fw"></span> <strong>{if $all}Gestion de vos articles{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-news">
                                    <thead>
                                        <tr>
                                            {foreach from=$sb_table_header item=header}
												<th>
													{$header}
												</th>
											{/foreach}
                                        </tr>
                                    </thead>
                                    <tbody>
										{foreach from=$all item=news}
											<tr class="{cycle values="odd,even"} gradeX">
												<td>{$news.date|unescape:"htmlall"}</td>
												<td>{$news.title|unescape:"htmlall"|@sbDisplayLang}</td>
												<td>{$news.catname|unescape:"htmlall"|@sbDisplayLang}</td>
												<td>[CS id={$news.id} name=sbnews_item]</td>
												<td>
													<span class="glyphicon glyphicon-eye-open {if $news.active}green{else}red{/if}" title="Statut {if $news.active}visible{else}non visible{/if}"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$news.id}" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$news.id}" title="Supprimer"></a>
												</td>
											</tr>										
										{/foreach}
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
				{/if}
				
				{if $allcategory}
				
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-rss fa-fw"></span> <strong>{if $allcategory}Gestion de vos catégories{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-categories">
                                    <thead>
                                        <tr>
                                            {foreach from=$sb_table_header item=header}
												<th>
													{$header}
												</th>
											{/foreach}
                                        </tr>
                                    </thead>
                                    <tbody>
										{foreach from=$allcategory item=category}
											<tr class="{cycle values="odd,even"} gradeX">
												<td>{$category.sort}</td>
												<td>{$category.title|unescape:"htmlall"|@sbDisplayLang}</td>
												<td>[CS id={$category.id} name=sbnews_latest]</td>
												<td>
													<span class="glyphicon glyphicon-eye-open {if $category.active}green{else}red{/if}" title="Statut {if $category.active}visible{else}non visible{/if}"></span>
													&nbsp;
													<a class="glyphicon glyphicon-object-align-top yellow" href="{$module_url}&a=tpl_list&id={$category.id}" title="TPL LIST"></a>
													&nbsp;
													<a class="glyphicon glyphicon-object-align-left yellow" href="{$module_url}&a=tpl_single&id={$category.id}" title="TPL SINGLE"></a>
													&nbsp;
													<a class="glyphicon glyphicon-wrench green" href="{$module_url}&a=settingscategory&id={$category.id}" title="Paramètres"></a>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="{$module_url}&a=categoryedit&id={$category.id}" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=categorydel&id={$category.id}" title="Supprimer"></a>
												</td>
											</tr>										
										{/foreach}
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
				{/if}
				
				{if (!$all || !$allcategory) && ($smarty.get.a && $smarty.get.a != 'category') }

					<div class="col-lg-{if $smarty.get.a == 'categoryedit' OR $smarty.get.a == 'tpl_list' OR $smarty.get.a == 'tpl_single'}8{else}12{/if}">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-rss fa-fw"></span> <strong>{$legend_add_edit}</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								{* Afficher le formulaire ADD/EDIT *}
								{include_php file='form.php'}
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
					
					{if $smarty.get.a == 'categoryedit' OR $smarty.get.a == 'tpl_list' OR $smarty.get.a == 'tpl_single'}
						{if $smarty.get.a == 'tpl_list' OR $smarty.get.a == 'tpl_single'}
							<div class="col-lg-4">
								<div class="panel panel-red">
									<div class="panel-heading">
										Variables à utiliser dans votre code {if $smarty.get.a == 'tpl_list'}TPL LIST{else}TPL SINGLE{/if}
									</div>
									<div class="panel-body">
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
									</div>
									<div class="panel-footer">
										<a href="http://www.smarty.net/" target="_blank">Voir Doc smarty pour plus d'infos</a>
									</div>
								</div>
							</div>
							<!-- /.col-lg-4 -->
						{/if}
						{if $smarty.get.a != 'tpl_list' AND $smarty.get.a != 'tpl_single'}
						<div class="col-lg-4">
							<div class="panel panel-success">
								<div class="panel-heading">
									Templates Catégorie
								</div>
								<div class="panel-body">
									<p style="text-align: center;">
										<b>Modifier le template de l'intro (LIST)</b>
										<br>
										<button onclick="location.href='index.php?p=news&a=tpl_list&id={$smarty.get.id}'" type="button" class="btn btn-success">
											Template LIST
										</button>
									</p>
									<br>
									<p style="text-align: center;">
										<b>Modifier le template de l'article (SINGLE)</b>
										<br>
										<button onclick="location.href='index.php?p=news&a=tpl_single&id={$smarty.get.id}'" type="button" class="btn btn-success">
											Template SINGLE
										</button>
									</p>
								</div>
							</div>
						</div>
						{/if}

						<!-- /.col-lg-4 -->
					{/if}
				
				{/if}
				
            </div>
            <!-- /.row -->
				
			<div class="modal fade" id="sbnews_shortcodes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="myModalLabel">Shorcodes</h4>
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
			$('#dataTables-news').DataTable({
					order: [ 0, 'desc' ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			$('#dataTables-categories').DataTable({
					order: [ 0, 'asc' ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
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
			
	{include file='sb_footer.tpl'}

