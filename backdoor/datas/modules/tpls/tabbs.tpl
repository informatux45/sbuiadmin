{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='tabbs_bar.tpl'}

            <div class="row">
				
				<div class="col-lg-12">
					<div class="panel panel-info">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="false" class="collapsed"><i class="fa fa-arrow-circle-down"></i> Que fait TABBS ?</a>
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
				
				{if $all}

					<div class="col-lg-12">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<span class="fa fa-list-alt fa-fw"></span> <strong>{if $all}Gestion de vos tabbs{else}{$legend_add_edit}{/if}</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="dataTable_wrapper">
									<table class="table table-striped table-bordered table-hover" id="dataTables-tabbs">
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
											{if $alltabb}
												{foreach from=$alltabb item=tabbs}
													<tr class="{cycle values="odd,even"} gradeX">
														<td>{$tabbs.name|unescape:"htmlall"|@sbDisplayLang}</td>
														<td>[CS id={$tabbs.id} name=sbtabbs]</td>
														<td>
															<span class="glyphicon glyphicon-eye-open {if $tabbs.active}green{else}red{/if}" title="Statut {if $tabbs.active}visible{else}non visible{/if}"></span>
															&nbsp;
															<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$tabbs.id}" title="Modifier"></a>
															&nbsp;
															<a class="glyphicon glyphicon-sort-by-attributes" href="{$module_url}&a=sort&tid={$tabbs.id}" title="Trier les onglets"></a>
															&nbsp;
															<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$tabbs.id}" title="Supprimer"></a>
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
				
				{if $allt}
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-list-alt fa-fw"></span> <strong>Gestion de vos onglets</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-tabbstab">
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
										{if $alltabs}
											{foreach from=$alltabs item=tabs}
												<tr class="{cycle values="odd,even"} gradeX">
													<td>{$tabs.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$tabs.catname|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>
														<span class="glyphicon glyphicon-eye-open {if $tabs.active}green{else}red{/if}" title="Statut {if $tabs.active}visible{else}non visible{/if}"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=tabedit&id={$tabs.id}" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=tabdel&id={$tabs.id}" title="Supprimer"></a>
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

				{if (!$all || !$alltabs) && ($smarty.get.a && $smarty.get.a != 'alltabs' && $smarty.get.a != 'del' && $smarty.get.a != 'tabdel') }
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
			$('#dataTables-tabbs').DataTable({
					order: [ 0, 'asc' ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			$('#dataTables-tabbstab').DataTable({
					order: [ [1, 'asc'], [0, 'asc'] ],
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
			
	{include file='sb_footer.tpl'}

