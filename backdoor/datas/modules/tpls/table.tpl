{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='table_bar.tpl'}

            <div class="row">
				
				{if isset($all) && !isset($smarty.get.a)}

					<div class="col-lg-12">

						<div class="panel panel-warning">
							<div class="panel-heading">
								<span class="fa fa-info-circle"></span> <strong>Informations</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								
								La suppression d'un tableau comporte <b class="red">la perte</b> de toutes ses données et de sa structure !
							</div>
							<!-- /.panel-body -->
						</div>

						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>{if $all}Gestion de vos tableaux{else}{$legend_add_edit}{/if}</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-table">
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
										{if $alltable}
											{foreach from=$alltable item=table}
												<tr class="{cycle values="odd,even"} gradeX">
													<td>{$table.name|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>[CS id={$table.id} name=sbtable]</td>
													<td>{$table.type}</td>
													<td>
														<span class="glyphicon glyphicon-eye-open {if $table.active}green{else}red{/if}" title="Statut {if $table.active}visible{else}non visible{/if}"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$table.id}" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-wrench" href="{$module_url}&a=editfield&tid={$table.id}" title="Modifier la structure"></a>
														&nbsp;
														<a class="glyphicon glyphicon-sort-by-attributes" href="{$module_url}&a=editdatas&tid={$table.id}" title="Modifier les données"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$table.id}" title="Supprimer"></a>
													</td>
												</tr>										
											{/foreach}
										{/if}
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

				{if !$all}
					<div class="col-lg-{if isset($smarty.get.a) && ($smarty.get.a == 'sortstructure' || $smarty.get.a == 'sortdatas')}12{else}8{/if}">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>{$legend_add_edit}</strong>
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
					<!-- /.col-lg-8 -->
					
					{*if $allstructure || $alldatas || $alldatasempty || $allstructureempty*}
					{if $smarty.get.a != 'sortstructure' && $smarty.get.a != 'sortdatas'}
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-info-circle fa-fw"></i> Aide
							</div>
							<div class="panel-body">
								<p>
									{if isset($allstructure) || $smarty.get.a == 'editfield'}
										<u>Les types de champs utilisés pour une colonne de votre tableau :</u>
										<br>
										<b>date</b>: Input DATE<br>
										<b>texte</b>: Input TEXT<br>
										<b>textarea</b>: Textarea<br>
										<b>textareaHTML</b>: Editeur CKEditor<br>
										<b>photo</b>: Input PHOTO<br>
										<b>link_image</b>: Icône (lien vers photo)<br>
										<b>link_video</b>: Icône (lien vers vidéo)<br>
										<b>link_file</b>: Icône (lien vers fichier)<br>
										<br>
										<u>Les targets de champs :</u><br>
										<b>blank</b>: Ouvre le lien dans un nouvel onglet<br>
										<b>lightbox</b>: Ouvre votre lien dans une lightbox (NB: <i>Ne pas oublier d'activer la lightbox dans les plugins de l'administration</i>)<br>
										<b>lightbox_fancy</b>: Ouvre votre lien dans une lightbox FANCYBOX (NB: <i>Ne pas oublier d'activer la lightbox dans les plugins de l'administration</i>)<br>
									{elseif isset($alldatas) || $smarty.get.a == 'editdatas'}
										Saissisez les données de votre tableau par entrée (ligne).<br>
										<br>
										Si vous souhaitez modifier la structure de votre tableau, cliquez sur
										le bouton « sa structure ».<br>
										<br>
										Les <span style="color: red; background-color: yellow;">champs en jaune</span>
										dans le
										formulaire et dans votre tableau ci-dessous sont des champs (colonnes
										de tableau) qui ne seront pas affichées sur votre site car elles sont
										désactivées dans la structure de votre tableau. Ils ne sont pas cachés
										dans la saisie de données vous permettant de saisir des informations
										pour plus tard en réactivant le champs dans la structure du tableau.<br>
										<br>
									{elseif $smarty.get.a == 'edit' || $smarty.get.a == 'add'}
										Tableau pleine page (FULL)<br>
										<a style="cursor: pointer;" data-toggle="modal" data-target="#table_full"><img src="{$smarty.const._AM_SITE_IMG_URL}modules/sb_table_full.png" alt="" style="max-width: 100%;" /></a>
										<!-- Modal -->
										<div class="modal fade" id="table_full" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title" id="myModalLabel">Tableau pleine page (FULL)</h4>
													</div>
													<div class="modal-body">
														<img src="{$smarty.const._AM_SITE_IMG_URL}modules/sb_table_full.png" alt="" style="max-width: 100%;" />
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													</div>
												</div>
												<!-- /.modal-content -->
											</div>
											<!-- /.modal-dialog -->
										</div>
										<!-- /.modal -->
										<br><br>
										Tableau Option 1 (responsive CSS)<br>
										<a style="cursor: pointer;" data-toggle="modal" data-target="#table_option1"><img src="{$smarty.const._AM_SITE_IMG_URL}modules/sb_table_option1.png" alt="" style="max-width: 100%;" /></a>
										<!-- Modal -->
										<div class="modal fade" id="table_option1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title" id="myModalLabel">Tableau pleine page (FULL)</h4>
													</div>
													<div class="modal-body">
														<img src="{$smarty.const._AM_SITE_IMG_URL}modules/sb_table_option1.png" alt="" style="max-width: 100%;" />
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													</div>
												</div>
												<!-- /.modal-content -->
											</div>
											<!-- /.modal-dialog -->
										</div>
										<!-- /.modal -->
										<br><br>
										Tableau Option 2 (responsive CSS / JS)<br>
										<a style="cursor: pointer;" data-toggle="modal" data-target="#table_option2"><img src="{$smarty.const._AM_SITE_IMG_URL}modules/sb_table_option2.png" alt="" style="max-width: 100%;" /></a>
										<!-- Modal -->
										<div class="modal fade" id="table_option2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h4 class="modal-title" id="myModalLabel">Tableau Option 2 (responsive CSS / JS)</h4>
													</div>
													<div class="modal-body">
														<img src="{$smarty.const._AM_SITE_IMG_URL}modules/sb_table_option2.png" alt="" style="max-width: 100%;" />
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													</div>
												</div>
												<!-- /.modal-content -->
											</div>
											<!-- /.modal-dialog -->
										</div>
										<!-- /.modal -->
									{/if}
								</p>
							</div>
						</div>
					</div>
					<!-- /.col-lg-4 -->
					{/if}
					
				{/if}
				
				{if isset($allstructure)}

					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>Gestion des colonnes du tableau &laquo; {$tabname} &raquo;</strong>
								&nbsp;&nbsp;
								<button class="btn btn-danger" type="button" onclick="location.href='index.php?p=table&a=sortstructure&tid={$smarty.get.tid}'">
									Trier les colonnes
								</button>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-tablestructure">
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
										{if $allstructure}
											{foreach from=$allstructure item=column}
												<tr class="{cycle values="odd,even"} gradeX"{if !$column.active} style="background-color: yellow;"{/if}>
													<td>{$column.sort}</td>
													<td>{$column.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$column.field_type}</td>
													<td>{$column.field_target}</td>
													<td>
														<span class="glyphicon glyphicon-eye-open {if $column.active}green{else}red{/if}" title="Statut {if $column.active}visible{else}non visible{/if}"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=editfield&tid={$column.tid}&id={$column.id}" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=delfield&tid={$column.tid}&id={$column.id}" title="Supprimer"></a>
													</td>
												</tr>										
											{/foreach}
										{/if}
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
				
				{if isset($alldatas)}

					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>Gestion des données du tableau &laquo; {$tabname} &raquo;</strong>
								&nbsp;&nbsp;
								<button class="btn btn-danger" type="button" onclick="location.href='index.php?p=table&a=sortdatas&tid={$smarty.get.tid}'">
									Trier les données
								</button>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-tablestructure">
										<h5 style="margin-bottom: 20px; color: red;">Les colonnes en rouge sont désactivées et ne sont pas affichées sur votre site !</h5>
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
										{if $alldatas}
											{foreach from=$alldatas item=datas}
												<tr class="{cycle values="odd,even"} gradeX">
													<td>
														{$datas.sort}
													</td>
													{insert name=jsondata datas="`$datas.content`"}
													<td>
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=editdatas&tid={$datas.tid}&id={$datas.id}" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=deldatas&tid={$datas.tid}&id={$datas.id}" title="Supprimer"></a>
													</td>
												</tr>										
											{/foreach}
										{/if}
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
					
					{*<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>Trier les données du tableau &laquo; <span class="red">{$tabname}</span> &raquo;</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">*}
								{* Afficher le formulaire SORT *}
								{*insert name=sortdatas tid="`$smarty.get.tid`"}
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->*}
					
				{/if}
				
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			$('#dataTables-table').DataTable({
					order: [ 1, 'desc' ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			
			$('#dataTables-tablestructure').DataTable({
					order: [ [0, 'asc'] ],
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
		});
		</script>
			
	{include file='sb_footer.tpl' page='false' pagef='false'}