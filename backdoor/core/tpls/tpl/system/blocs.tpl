{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='datas/modules/tpls/pages_bar.tpl'}

            <div class="row">
				{if $all}
                <div class="col-lg-6">
					<div class="panel panel-yellow">
                        <div class="panel-heading">
                            <span class="fa fa-sort-amount-asc fa-fw"></span> <strong>Trier vos blocs par page</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>
								{foreach from=$all_pages item=page}
									<button class="btn btn-outline btn-success" type="button" onclick="location.href='index.php?p=blocs&a=sort&pid={$page.id}'">
										{$page.title|unescape:"htmlall"|@sbDisplayLang}
									</button>
								{/foreach}
                            </p>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-6 -->

                <div class="col-lg-6">
					<div class="panel panel-yellow">
                        <div class="panel-heading">
                            <span class="fa fa-sort-amount-desc fa-fw"></span> <strong>Trier vos blocs par module</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>
								{section name=mod loop=$all_modules}
									<button class="btn btn-outline btn-info" type="button" onclick="location.href='index.php?p=blocs&a=sortmod&pid={$all_modules[mod]}'">
										{$all_modules[mod]|capitalize}
									</button>
								{/section}
							</p>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-6 -->
				
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-copy fa-fw"></span> <strong>{if $all}Gestion de vos blocs{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-blocs">
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
										{if $allbloc}
											{foreach from=$allbloc item=bloc}
												<tr class="{cycle values="odd,even"} gradeX">
													<td>{$bloc.name|unescape:"htmlall"|@sbDisplayLang}</td>
													{*<td>{$bloc.pagename|unescape:"htmlall"|@sbDisplayLang}</td>*}
													<td>{$bloc.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>
														<span class="glyphicon glyphicon-eye-open {if $bloc.active}green{else}red{/if}" title="Statut {if $bloc.active}visible{else}non visible{/if}"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$bloc.id}" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$bloc.id}" title="Supprimer"></a>
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
				
				{else}
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-copy fa-fw"></span> <strong>{if $all}Gestion de vos blocs{else}{$legend_add_edit}{/if}</strong>
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
				
				<div class="col-lg-4">
					{* ------------------------------------ *}
					{* --- Include Shared Panel Actions --- *}
					{include file='shared/shared-panel-actions.tpl'}
					{* ------------------------------------ *}
					{* ------------------------------------ *}
                    <div class="panel panel-default">
						<div class="panel-heading">
							<span class="fa fa-copy fa-fw"></span> <strong>Exemple de grille (layout)</strong>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<img src="img/cssgridlayout.jpg" alt="" style="width: 100%;">
						</div>
						<!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
				</div>
				<!-- /.col-lg-4 -->

				{/if}
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			$('#dataTables-blocs').DataTable({
					responsive: true
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

