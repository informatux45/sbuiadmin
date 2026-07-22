{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='tabbs_bar.tpl'}

            <div class="row">
				
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="false" class="collapsed"><i class="fa fa-arrow-circle-down"></i> &nbsp;&nbsp;Que fait TABBS ?</a>
							</h4>
						</div>
						<div id="collapse" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
							<div class="panel-body">
								<p style="font-size: 1.2em;">
									TABBS permet :
									<ol>
										<li>d'afficher des contenus dans des onglets</li>
										<li>de créez autant d'onglets que vous le souhaitez</li>
										<li>d'insérer du texte, du code HTML, des shortcodes</li>
									</ol>
									Les onglets sont responsives et vous pouvez les customiser par CSS.
								</p>
								<p>
									<img src="img/modules/tabbs_example_1.png" alt="Exemple TABBS" style="max-width: 100%;" />
								</p>
							</div>
						</div>
					</div>
				</div>
				
				{if isset($all)}

					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>{if $all}Gestion de vos tabbs{else}{$legend_add_edit}{/if}</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="data-toolbar">
									<div class="data-toolbar-left">
										<div class="input-icon" style="flex:1;max-width:320px">
											<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
											<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-tabbs">
										</div>
									</div>
								</div>
								<div style="overflow-x:auto">
									<table class="data-table" id="dataTables-tabbs" data-datatable>
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
											{if isset($alltabb)}
												{foreach from=$alltabb item=tabbs}
													<tr class="data-row">
														<td>{$tabbs.name|unescape:"htmlall"|@sbDisplayLang}</td>
														<td>[CS id={$tabbs.id} name=sbtabbs]</td>
														<td>
															<div class="data-cell-actions">
																<span class="btn--icon" style="color:{if $tabbs.active}var(--success){else}var(--danger){/if}" title="Statut {if $tabbs.active}visible{else}non visible{/if}">
																	<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
																</span>
																<a class="btn--icon" href="{$module_url}&a=edit&id={$tabbs.id}" title="Modifier">
																	<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
																</a>
																<a class="btn--icon" href="{$module_url}&a=sort&tid={$tabbs.id}" title="Trier les onglets">
																	<svg viewBox="0 0 24 24"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
																</a>
																<a class="btn--icon jConfirm" href="{$module_url}&a=del&id={$tabbs.id}" title="Supprimer">
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
								<div class="data-foot" data-datatable-foot="dataTables-tabbs">
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
				
				{if isset($allt)}
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-list-alt fa-fw"></span> <strong>Gestion de vos onglets</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-tabbstab">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-tabbstab" data-datatable>
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
										{if $alltabs}
											{foreach from=$alltabs item=tabs}
												<tr class="data-row">
													<td>{$tabs.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$tabs.catname|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>
														<div class="data-cell-actions">
															<span class="btn--icon" style="color:{if $tabs.active}var(--success){else}var(--danger){/if}" title="Statut {if $tabs.active}visible{else}non visible{/if}">
																<svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
															</span>
															<a class="btn--icon" href="{$module_url}&a=tabedit&id={$tabs.id}" title="Modifier">
																<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
															</a>
															<a class="btn--icon jConfirm" href="{$module_url}&a=tabdel&id={$tabs.id}" title="Supprimer">
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
							<div class="data-foot" data-datatable-foot="dataTables-tabbstab">
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

				{if (!isset($all) || !isset($alltabs)) && (isset($smarty.get.a) && $smarty.get.a != 'alltabs' && $smarty.get.a != 'del' && $smarty.get.a != 'tabdel') }
					<div class="col-lg-12">
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
					<!-- /.col-lg-12 -->
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

