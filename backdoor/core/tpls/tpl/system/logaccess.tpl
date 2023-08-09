{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{*include file='system/logaccess_bar.tpl'*}

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-{if $all}primary{else}default{/if}">
                        <div class="panel-heading">
                            <span class="fa fa-eye fa-fw"></span> <strong>Journaux de connexion</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{if $all}
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-logaccess">
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
										{if $allaccess}
											{foreach from=$allaccess item=log}
												<tr class="{if $log.logaccess_type == 'error'}danger{else}{cycle values="odd,even"} gradeX{/if}">
													<td>{$log.id}</td>
													<td>{$log.logaccess_date|date_format:"%d.%m.%Y - %R"}</td>
													<td>{$log.logaccess_user}</td>
													<td>{$log.logaccess_type}</td>
													<td>{$log.logaccess_event}</td>
												</tr>										
											{/foreach}
										{/if}
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
			$('#dataTables-logaccess').DataTable({
					order: [ 0, 'desc' ],
					responsive: true,
					"lengthMenu": [50, 75, 100, 200, 500]
			});
		});
		</script>
			
	{include file='sb_footer.tpl' page='false' pagef='false'}

