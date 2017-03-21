{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='poulinage_foal_bar.tpl'}

			{* Notes full width *}
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-{if $all}primary{else}default{/if}">
                        <div class="panel-heading">
                            <i class="fa fa-star-half-empty fa-fw"></i> Tous les produits
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{if $all}
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-foals">
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
										{foreach from=$all item=foreachall}
											<tr class="{cycle values="odd,even"} gradeX">
												<td>{$foreachall.name|unescape:"htmlall"}</td>
												<td>{$foreachall.term|unescape:"htmlall"}</td>
												<td>{$foreachall.birth|unescape:"htmlall"}</td>
												<td>{$foreachall.stallion_n|unescape:"htmlall"}</td>
												<td>
													<span class="glyphicon glyphicon-eye-open {if $foreachall.active}green{else}red{/if}" title="Statut {if $foreachall.active}visible{else}non visible{/if}"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$foreachall.id}" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$foreachall.id}" title="Supprimer"></a>
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
			// Your own code
			$('#dataTables-foals').DataTable({
					responsive: true
			});
		});
		</script>

	{include file='sb_footer.tpl'}