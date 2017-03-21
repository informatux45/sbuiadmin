{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='effectives_bar.tpl'}

            <div class="row">
				
				{if $all}
					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<span class="fa fa-trophy fa-fw"></span> <strong>{if $all}Gestion de vos effectifs{else}{$legend_add_edit}{/if}</strong>
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
											{foreach from=$all item=effectives}
												<tr class="{cycle values="odd,even"} gradeX">
													<td>{$effectives.date|unescape:"htmlall"}</td>
													<td>{$effectives.name|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$effectives.catname|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>[CS id={$effectives.id} name=sbeffectives_item]</td>
													<td>
														<span class="glyphicon glyphicon-eye-open {if $effectives.active}green{else}red{/if}" title="Statut {if $effectives.active}visible{else}non visible{/if}"></span>
														&nbsp;
														<a class="glyphicon glyphicon-picture" href="{$module_url}&a=medias&id={$effectives.id}" title="Tous les medias"></a>
														&nbsp;
														<a class="glyphicon glyphicon-knight" href="{$module_url}&a=production&eid={$effectives.id}" title="Toute la production"></a>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$effectives.id}" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$effectives.id}" title="Supprimer"></a>
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
                            <span class="fa fa-trophy fa-fw"></span> <strong>{if $allcategory}Gestion de vos catégories{else}{$legend_add_edit}{/if}</strong>
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
				
				{if $allmedias}
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-trophy fa-fw"></span> <strong>{if $allmedias}Gestion de vos medias{else}{$legend_add_edit}{/if}</strong>
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
										{foreach from=$allmedias item=media}
											<tr class="{cycle values="odd,even"} gradeX">
												<td>{$media.sort}</td>
												<td>{$media.title|unescape:"htmlall"|@sbDisplayLang}</td>
												<td>{$media.effective_name|unescape:"htmlall"|@sbDisplayLang}</td>
												<td>
													<span class="glyphicon glyphicon-eye-open {if $media.active}green{else}red{/if}" title="Statut {if $media.active}visible{else}non visible{/if}"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="{$module_url}&a=mediasedit&id={$media.id}&eid={$media.eid}" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=mediasdel&id={$media.eid}&mid={$media.id}" title="Supprimer"></a>
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

				{if $allproduction}
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-trophy fa-fw"></span> <strong>{if $allproduction}Gestion de vos productions{else}{$legend_add_edit}{/if}</strong>
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
										{foreach from=$allproduction item=production}
											<tr class="{cycle values="odd,even"} gradeX">
												<td>{$production.sort}</td>
												<td>{$production.name|unescape:"htmlall"|@sbDisplayLang}</td>
												<td>{$production.effective_name|unescape:"htmlall"|@sbDisplayLang}</td>
												<td>
													<span class="glyphicon glyphicon-eye-open {if $production.active}green{else}red{/if}" title="Statut {if $production.active}visible{else}non visible{/if}"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="{$module_url}&a=productionedit&id={$production.id}&eid={$production.eid}" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=productiondel&id={$production.id}&eid={$production.eid}" title="Supprimer"></a>
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

				{if (!$all || !$allcategory || !$allmedias || !$allproduction) && ($smarty.get.a && $smarty.get.a != 'category' && $smarty.get.a != 'medias' && $smarty.get.a != 'production' && $smarty.get.a != 'mediasdel') }
					<div class="col-lg-{if $smarty.get.a == 'categoryedit' OR $smarty.get.a == 'tpl_list' OR $smarty.get.a == 'tpl_single' OR $show_headpage OR $effectives_help}8{else}12{/if}">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-trophy fa-fw"></span> <strong>{$legend_add_edit}</strong>
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
					
					{if $effectives_help}
						<div class="col-lg-4">
							<div class="panel panel-default">
								<div class="panel-heading">
									<span class="fa fa-info-circle fa-fw"></span> <strong>Aide</strong>
								</div>
								<!-- /.panel-heading -->
								<div class="panel-body">
									{$effectives_help|@html_entity_decode}
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
											{if $smarty.get.a == 'tpl_list'}
												{literal}
													<style>
														ul.tpl_list li {font-size: 12px; list-style: none; margin-left: -40px;}
													</style>
													<p>VARIABLES :
													<ul class="tpl_list">
														<li>Catégorie: <b>{$effectives.catname}</b></li>
														<li>Nom: <b>{$effectives.name}</b></li>
														<li>Image: <b>{$smarty.const._AM_MEDIAS_URL}/{$effectives.photo}</b></li>
														<li>Père: <b>{$effectives.sire}</b></li>
														<li>Mère: <b>{$effectives.dam}</b></li>
														<li>Père de mère: <b>{$effectives.sire_dam}</b></li>
														<li>Saillie: <b>{$effectives.projection}</b></li>
														<li>Lien catégorie:<br>
														<b>{seo url="index.php?p=effectives&op=article&id={$effectives.id}" rewrite="effectives/article/{$effectives.id}/{$effectives.title|unescape:"htmlall"<br>|@sbDisplayLang:"`$smarty.session.lang`"<br>|strip_tags|@sbRewriteString|@strtolower}"}</b></li>
														<li></li>
													</ul>
													</p>
													<p>CONSTANTES :
													<ul class="tpl_list">
														<li><b>{$smarty.const._CMS_EFFECTIVES_AND}</b>:  &</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BY}</b>:  par</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_READ_ITEM}</b>:  Consulter sa page</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES}</b>:  Effectif</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_S}</b>:  Effectifs</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_ALL}</b>:  Tous les effectifs</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOITEM}</b>:  Pas d'effectifs disponible !</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOCATEGORIES}</b>:  Aucune catégorie disponible !</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BORN}</b>:  né en</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BORNDATE}</b>:  en</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BREEDER}</b>:  Eleveur</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_OWNER}</b>:  Propriétaire</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOPROD}</b>:  Aucune production</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOMEDIAS}</b>:  Aucun médias</li>
													</ul>
													</p>
													<p>PIPEs possible :<br>
													<b>|unescape:"htmlall"</b>&nbsp;(Echappement HTML)<br>
													<b>|@sbDisplayLang:"`$smarty.session.lang`"</b>&nbsp;(Conversion Langue Session)
													</p>
												{/literal}
											{else}
												{literal}
													<style>
														ul.tpl_single li {font-size: 12px; list-style: none; margin-left: -40px;}
													</style>
													<p>VARIABLES :
													<ul class="tpl_single">
														<li>Date (Année): <b>{$item.date|@sbConvertDate:"YEAR"}</b></li>
														<li>Date (US): <b>{$item.date}</b></li>
														<li>Date (FR): <b>{$item.date|@sbConvertDate:"FR"}</b></li>
														<li>Nom: <b>{$item.name}</b></li>
														<li>Sous titre 1: <b>{$item.subtitle1}</b></li>
														<li>Sous titre 2: <b>{$item.subtitle2}</b></li>
														<li>Sexe: <b>{$item.sex}</b></li>
														<li>Robe: <b>{$item.colour}</b></li>
														<li>Taile: <b>{$item.size}</b></li>
														<li>Père: <b>{$item.sire}</b></li>
														<li>Mère: <b>{$item.dam}</b></li>
														<li>Père de mère: <b>{$item.sire_dam}</b></li>
														<li>Pays: <b>{$item.origine}</b></li>
														<li>Eleveur: <b>{$item.breeder}</b></li>
														<li>Propriétaire: <b>{$item.owner}</b></li>
														<li>Chrono: <b>{$item.chrono}</b></li>
														<li>Gains: <b>{$item.winnings}</b></li>
														<li>Statut: <b>{$item.status}</b></li>
														<li>Saillie: <b>{$item.projection}</b></li>
														<li>Description: <b>{$item.description}</b></li>
														<li>Pedigree Desc: <b>{$item.pedigree_desc}</b></li>
														<li>Pedigree (PDF):<br><b>{$smarty.const._AM_MEDIAS_URL}/{$item.pedigree}</b></li>
														<li>Pedigree Extend (PDF):<br><b>{$smarty.const._AM_MEDIAS_URL}/{$item.pedigree_extend}</b></li>
														<li>Photo:<br><b>{$smarty.const._AM_MEDIAS_URL}/{$item.photo}</b></li>
													</ul>
													</p>
													<p>TITRES :
													<ul class="tpl_single">
														<li>Description: <b>{$sbeffectives_options.title_description}</b></li>
														<li>Pedigree: <b>{$sbeffectives_options.title_pedigree}</b></li>
														<li>Production: <b>{$sbeffectives_options.title_production}</b></li>
														<li>Medias: <b>{$sbeffectives_options.title_medias}</b></li>
													</ul>
													</p>
													<p>PRODUCTION (Boucle type) :
													<ul class="tpl_single">
														<li><b>
															{foreach from=$production item=prod}<br>
																&lt;p><br>
																	{if $prod.date}<br>
																		{$prod.date|@sbConvertDate:"YEAR"} -<br>
																	{/if}<br>
																	{if $prod.group_bold}<br>
																		&lt;b>{$prod.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}&lt;/b><br>
																	{else}<br>
																		{$prod.name|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																	{/if}<br>
																	<br>
																	{if $prod.sex}<br>
																		({$prod.sex|lower}.{if $prod.colour|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"} {$prod.colour|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}{/if})<br>
																	{/if}<br>
																	<br>
																	{if $prod.photo}<br>
																		{insert name=sbeffectives_medias type="photo" url="`$prod.photo`" title="`$prod.name`" media="icon"}<br>
																	{/if}<br>
																	{if $prod.video}<br>
																		{insert name=sbeffectives_medias type="youtube" url="`$prod.video`" title="`$prod.name`" media="icon"}<br>
																	{/if}<br>
																	{if $prod.pedigree}<br>
																		{insert name=sbeffectives_medias type="pdf" url="`$prod.pedigree`" title="`$prod.name`" media="icon"}<br>
																	{/if}<br>
																	<br>
																	{if $prod.dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																		&lt;br><br>
																		&lt;i class="sbeffectives-single-dam-sire"><br>
																		{$prod.dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																		{if $prod.sire_dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																			{$smarty.const._CMS_EFFECTIVES_BY}<br>
																			{$prod.sire_dam|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																		{/if}<br>
																		&lt;/i><br>
																	{/if}<br>
																	<br>
																	{if $prod.performance|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																		&lt;br><br>
																		{$prod.performance|unescape:"htmlall"|@sbDisplayLang:"`$smarty.session.lang`"}<br>
																	{/if}<br>
																&lt;/p><br>
															{/foreach}<br>
														</b></li>
													</ul>
													</p>
													<p>MEDIAS (Boucle type) :
													<ul class="tpl_single">
														<li><b>
															{foreach from=$medias item=media}<br>
																{insert name=sbeffectives_medias type="`$media.type`" url="`$media.url`" title="`$media.title`"}<br>
															{/foreach}<br>
														</b></li>
													</ul>
													<p>CONSTANTES :
													<ul class="tpl_single">
														<li><b>{$smarty.const._CMS_EFFECTIVES_AND}</b>:  &</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BY}</b>:  par</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_READ_ITEM}</b>:  Consulter sa page</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES}</b>:  Effectif</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_S}</b>:  Effectifs</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_ALL}</b>:  Tous les effectifs</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOITEM}</b>:  Pas d'effectifs disponible !</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOCATEGORIES}</b>:  Aucune catégorie disponible !</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BORN}</b>:  né en</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BORNDATE}</b>:  en</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_BREEDER}</b>:  Eleveur</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_OWNER}</b>:  Propriétaire</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOPROD}</b>:  Aucune production</li>
														<li><b>{$smarty.const._CMS_EFFECTIVES_NOMEDIAS}</b>:  Aucun médias</li>
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
										<button onclick="location.href='index.php?p=effectives&a=tpl_list&id={$smarty.get.id}'" type="button" class="btn btn-success">
											Template LIST
										</button>
									</p>
									<br>
									<p style="text-align: center;">
										<b>Modifier le template de l'article (SINGLE)</b>
										<br>
										<button onclick="location.href='index.php?p=effectives&a=tpl_single&id={$smarty.get.id}'" type="button" class="btn btn-success">
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
			$('#dataTables-news').DataTable({
					order: [  [2, 'asc'], [0, 'desc'] ],
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

