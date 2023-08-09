{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='slider_bar.tpl'}

            <div class="row">
				
                <div class="col-lg-12">
                    <div class="well">
						<img src="img/screenshot-slider.jpg" style="width: 675px; max-width: 100%;" alt="" />
                    </div>
                </div>
                <!-- /.col-lg-12 -->
				
				{if isset($all) && !isset($smarty.get.a)}
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-sliders fa-fw"></span> <strong>{if $all}Gestion de vos slider{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-sliders">
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
										{if isset($allslider)}
											{foreach from=$allslider item=slider}
												<tr class="{cycle values="odd,even"} gradeX">
													<td>{$slider.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>{$slider.mode|unescape:"htmlall"}</td>
													<td>{$slider.cpt_img|default:0}</td>
													<td>[CS id={$slider.id} name=sbslider]</td>
													<td>
														<span class="glyphicon glyphicon-eye-open {if $slider.active}green{else}red{/if}" title="Statut {if $slider.active}visible{else}non visible{/if}"></span>
														&nbsp;
														<a class="glyphicon glyphicon-picture" href="{$module_url}&a=photo&sid={$slider.id}" title="Toutes les photos"></a>
														&nbsp;
														<a class="glyphicon glyphicon-sort-by-attributes" href="{$module_url}&a=sort&sid={$slider.id}" title="Trier les photos"></a>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$slider.id}" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$slider.id}" title="Supprimer"></a>
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
				
				{if isset($allphoto)}
				
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-sliders fa-fw"></span> <strong>{if $allphoto}Gestion de vos slider{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-photos">
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
										{foreach from=$allphoto item=photo}
											<tr class="{cycle values="odd,even"} gradeX">
												<td>{$photo.sort}</td>
												<td>{$photo.photo}</td>
												<td>{$photo.title|unescape:"htmlall"|@sbDisplayLang}</td>
												<td>
													<span class="glyphicon glyphicon-eye-open {if $photo.active}green{else}red{/if}" title="Statut {if $photo.active}visible{else}non visible{/if}"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="{$module_url}&a=photoedit&id={$photo.id}&sid={$sid}" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=delphoto&sid={$sid}&id={$photo.id}" title="Supprimer"></a>
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
				
				{if (!isset($all) || !isset($allphoto)) && (isset($smarty.get.a) && $smarty.get.a != 'photo') }
					{if $smarty.get.a == 'edit' || $smarty.get.a == 'add'}
						<style>
						/* --- Icons 2 (form) --- */
						.input-group-addon, .input-group-btn { width: auto !important; }
						</style>
					{/if}
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-sliders fa-fw"></span> <strong>{$legend_add_edit}</strong>
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
			$('#dataTables-sliders').DataTable({
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			$('#dataTables-photos').DataTable({
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

