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
								<div class="data-toolbar">
									<div class="data-toolbar-left">
										<div class="input-icon" style="flex:1;max-width:320px">
											<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
											<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-table">
										</div>
									</div>
								</div>
								<div style="overflow-x:auto">
									<table class="data-table" id="dataTables-table" data-datatable>
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
										{if $alltable}
											{foreach from=$alltable item=table}
												<tr class="data-row">
													<td>{$table.name|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>[CS id={$table.id} name=sbtable]</td>
													<td>{$table.type}</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $table.active}var(--success){else}var(--danger){/if}" title="Statut {if $table.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=edit&id={$table.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=editfield&tid={$table.id}" title="Modifier la structure">
																<svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
															</a>
															<a class="btn--icon" href="{$module_url}&a=editdatas&tid={$table.id}" title="Modifier les données">
																<svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
															</a>
															<a class="btn--icon jConfirm" href="{$module_url}&a=del&id={$table.id}" title="Supprimer">
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
								<div class="data-foot" data-datatable-foot="dataTables-table">
									<div class="data-foot-info" data-foot-info></div>
									<div class="pager"></div>
								</div>
	
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
								<div class="data-toolbar">
									<div class="data-toolbar-left">
										<div class="input-icon" style="flex:1;max-width:320px">
											<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
											<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-tablestructure">
										</div>
									</div>
								</div>
								<div style="overflow-x:auto">
									<table class="data-table" id="dataTables-tablestructure" data-datatable>
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
										{if $allstructure}
											{foreach from=$allstructure item=column}
												<tr class="data-row"{if !$column.active} style="background:var(--warning-soft)"{/if}>
													<td>{$column.sort}</td>
													<td>{$column.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$column.field_type}</td>
													<td>{$column.field_target}</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $column.active}var(--success){else}var(--danger){/if}" title="Statut {if $column.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=editfield&tid={$column.tid}&id={$column.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon jConfirm" href="{$module_url}&a=delfield&tid={$column.tid}&id={$column.id}" title="Supprimer">
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
								<div class="data-foot" data-datatable-foot="dataTables-tablestructure">
									<div class="data-foot-info" data-foot-info></div>
									<div class="pager"></div>
								</div>
	
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
								<p style="margin-bottom: 16px; color: var(--danger);">Les colonnes en rouge sont désactivées et ne sont pas affichées sur votre site !</p>
								<div class="data-toolbar">
									<div class="data-toolbar-left">
										<div class="input-icon" style="flex:1;max-width:320px">
											<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
											<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-tabledata">
										</div>
									</div>
								</div>
								<div style="overflow-x:auto">
									<table class="data-table" id="dataTables-tabledata" data-datatable>
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
										{if $alldatas}
											{foreach from=$alldatas item=datas}
												<tr class="data-row">
													<td>
														{$datas.sort}
													</td>
													{insert name=jsondata datas="`$datas.content`"}
													<td>
														<div class="data-cell-actions">
															<a class="btn--icon" href="{$module_url}&a=editdatas&tid={$datas.tid}&id={$datas.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon jConfirm" href="{$module_url}&a=deldatas&tid={$datas.tid}&id={$datas.id}" title="Supprimer">
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
								<div class="data-foot" data-datatable-foot="dataTables-tabledata">
									<div class="data-foot-info" data-foot-info></div>
									<div class="pager"></div>
								</div>
	
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