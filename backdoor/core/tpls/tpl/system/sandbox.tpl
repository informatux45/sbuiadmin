{* --------------- *}
{* --- MODULES --- *}	
{* --------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='system/sandbox_bar.tpl'}

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-{if $all}primary{else}default{/if}">
                        <div class="panel-heading">
                            <span class="fa {if $sort}fa-sort-amount-desc{else}fa-ambulance{/if} fa-fw"></span> <strong>{if $all}Gestion de vos enregistrements{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{if $all}
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
										{foreach from=$all item=record}
											<tr class="{cycle values="odd,even"} gradeX">
												<td>{$record.nom|unescape:"htmlall"}</td>
												<td>{$record.telephone|unescape:"htmlall"}</td>
												<td>{$record.email|unescape:"htmlall"}</td>
												<td>{$record.company|unescape:"htmlall"}</td>
												<td>{$record.country|unescape:"htmlall"}</td>
												<td>
													<span class="glyphicon glyphicon-eye-open {if $record.active}green{else}red{/if}" title="Statut {if $record.active}visible{else}non visible{/if}"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$record.id}" title="Modifier"></a>
													&nbsp;
													{* Add "jConfirm" class to add confirm box alert *}
													<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$record.id}" title="Supprimer"></a>
												</td>
											</tr>
										{foreachelse}
										&nbsp;
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
			// Your own code
			$('#dataTables-graduates').DataTable({
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

	{include file='sb_footer.tpl' pagef='upload'}