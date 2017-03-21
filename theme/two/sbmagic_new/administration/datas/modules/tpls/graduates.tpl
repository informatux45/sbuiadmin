{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='graduates_bar.tpl'}

            <div class="row">
				
				{if $all}
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<span class="fa fa-certificate fa-fw"></span> <strong>{if $all}Gestion de vos meilleurs élèves{else}{$legend_add_edit}{/if}</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-graduates">
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
											{foreach from=$all item=graduates}
												<tr class="{cycle values="odd,even"} gradeX">
													<td>{$graduates.name|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$graduates.catname|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>[CS id={$graduates.id} name=sbgraduates_item]</td>
													<td>
														<span class="glyphicon glyphicon-eye-open {if $graduates.active}green{else}red{/if}" title="Statut {if $graduates.active}visible{else}non visible{/if}"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$graduates.id}" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$graduates.id}" title="Supprimer"></a>
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
                            <span class="fa fa-certificate fa-fw"></span> <strong>{if $allcategory}Gestion de vos catégories{else}{$legend_add_edit}{/if}</strong>
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
												<td>{$category.subtitle|unescape:"htmlall"|@sbDisplayLang}</td>
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
					<div class="col-lg-{if $smarty.get.a == 'categoryedit' OR $smarty.get.a == 'tpl_list' OR $smarty.get.a == 'tpl_single' OR $show_headpage}8{else}12{/if}">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-certificate fa-fw"></span> <strong>{$legend_add_edit}</strong>
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
					
					{if $show_headpage}
						{include file='shared/shared-slider-4col.tpl'}
					{/if}
					
					{if $graduates_help}
						<div class="col-lg-4">
							<div class="panel panel-default">
								<div class="panel-heading">
									<span class="fa fa-info-circle fa-fw"></span> <strong>Aide</strong>
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									{$graduates_help}
								</div>
								<!-- /.panel-body -->
							</div>
							<!-- /.panel -->
						</div>
						<!-- /.col-lg-4 -->
					{/if}
					
					{if $smarty.get.a == 'categoryedit' OR $smarty.get.a == 'tpl_list' OR $smarty.get.a == 'tpl_single'}
						{if $smarty.get.a == 'tpl_list' OR $smarty.get.a == 'tpl_single'}
							<div class="col-lg-4">
								<div class="panel panel-red">
									<div class="panel-heading">
										Variables à utiliser dans votre code {if $smarty.get.a == 'tpl_list'}TPL LIST{else}TPL SINGLE{/if}
									</div>
									<div class="panel-body">
										<p>
											{if $smarty.get.a == 'tpl_list' || $smarty.get.a == 'tpl_single'}
												{literal}
													<style>
														ul.tpl_list li {font-size: 12px; list-style: none; margin-left: -40px;}
													</style>
													<p>VARIABLES :
													<ul class="tpl_list">
														<li>Id: <b>{$graduates.id}</b></li>
														<li>Nom: <b>{$graduates.name}</b></li>
														<li>Image: <b>{$smarty.const._AM_MEDIAS_URL}/{$graduates.photo}</b></li>
														<li>Père / Mère: <b>{$graduates.sire_dam_info}</b></li>
														<li>Eleveur: <b>{$graduates.breeder}</b></li>
														<li>Owner: <b>{$graduates.owner}</b></li>
														<li>Perf 1: <b>{$graduates.perf_1}</b></li>
														<li>Vidéo 1: <b>{$graduates.video_1}</b></li>
														<li>...</li>
														<li>Perf 10: <b>{$graduates.perf_10}</b></li>
														<li>Vidéo 10: <b>{$graduates.video_10}</b></li>
														<li>Lien article:<br>
														<b>{seo url="index.php?p=graduates&op=article&id={$graduates.id}" rewrite="graduates/article/{$graduates.id}/{$graduates.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"|strip_tags|@sbRewriteString|@strtolower}"}</b></li>
														<li></li>
													</ul>
													</p>
													<p>CONSTANTES :
													<ul class="tpl_list">
														<li><b>{$smarty.const._CMS_GRADUATES}</b>:  Meilleur élève</li>
														<li><b>{$smarty.const._CMS_GRADUATES_S}</b>:  Meilleurs élèves</li>
														<li><b>{$smarty.const._CMS_GRADUATES_ALL}</b>:  Tous les meilleurs élèves</li>
														<li><b>{$smarty.const._CMS_GRADUATES_NOITEM}</b>:  Pas de meilleur élève disponible !</li>
														<li><b>{$smarty.const._CMS_GRADUATES_NOCATEGORIES}</b>:  Aucune catégorie disponible !</li>
														<li><b>{$smarty.const._CMS_GRADUATES_READ_ITEM}</b>:  Consulter sa page</li>
														<li><b>{$smarty.const._CMS_GRADUATES_BREEDER}</b>:  Eleveur</li>
														<li><b>{$smarty.const._CMS_GRADUATES_OWNER}</b>:  Propriétaire</li>
														<li><b>{$smarty.const._CMS_GRADUATES_NOMEDIAS}</b>:  Aucun médias</li>
														<li><b>{$smarty.const._CMS_GRADUATES_NOGRADUATES}</b>:  Pas de graduates pour cette catégorie !</li>
													</ul>
													</p>
													<p>PIPEs possible :<br>
													<b>|unescape:"htmlall"</b>&nbsp;(Echappement HTML)<br>
													<b>|@sbDisplayLang:"`$smarty.session.lang`"</b>&nbsp;(Conversion Langue Session)
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
										<button onclick="location.href='index.php?p=graduates&a=tpl_list&id={$smarty.get.id}'" type="button" class="btn btn-success">
											Template LIST
										</button>
									</p>
									<br>
									<p style="text-align: center;">
										<b>Modifier le template de l'article (SINGLE)</b>
										<br>
										<button onclick="location.href='index.php?p=graduates&a=tpl_single&id={$smarty.get.id}'" type="button" class="btn btn-success">
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
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script src="inc/plugins/ace/ace.js" type="text/javascript" charset="utf-8"></script>
		<script>
		$(document).ready(function() {
			$('#dataTables-graduates').DataTable({
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

