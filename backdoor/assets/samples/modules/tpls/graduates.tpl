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
                <div class="col-lg-12">
                    <div class="panel panel-{if $graduates_all}primary{else}default{/if}">
                        <div class="panel-heading">
                            <span class="fa {if $graduates_sort}fa-sort-amount-desc{else}fa-certificate{/if} fa-fw"></span> <strong>{if $graduates_all}Gestion de vos meilleurs élèves{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{if $graduates_all}
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-graduates">
                                    <thead>
                                        <tr>
                                            {foreach from=$sb_table_header_graduates item=header_graduates}
												<th>
													{$header_graduates}
												</th>
											{/foreach}
                                        </tr>
                                    </thead>
                                    <tbody>
										{foreach from=$graduates_all item=graduate}
											<tr class="{cycle values="odd,even"} gradeX">
												<td>{$graduate.name|unescape:"htmlall"}</td>
												<td>{$graduate.sire_dam_info|unescape:"htmlall"}</td>
												<td>
													<span class="glyphicon glyphicon-eye-open {if $graduate.active}green{else}red{/if}" title="Statut {if $graduate.active}visible{else}non visible{/if}"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$graduate.id}" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$graduate.id}" title="Supprimer"></a>
												</td>
											</tr>										
										{/foreach}
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
							{else}
								{* Afficher le formulaire ADD/EDIT *}
								{include_php file='form.php'}
							{/if}
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			$('#dataTables-graduates').DataTable({
					responsive: true
			});
			{if $graduates_sort}
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

