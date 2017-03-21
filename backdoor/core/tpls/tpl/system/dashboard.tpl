{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='system/settings_bar.tpl'}
			
			{* Notes col lg 6 *}
			<div class="row">
                
				<div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-dashboard fa-fw"></i> Configuration
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{* Afficher le formulaire EDIT *}
							{include_php file='form.php'}
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
				
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-info-circle fa-fw"></i> Rappel de votre configuration
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{* HTML Text Formatted *}
							<table class="table table-striped table-bordered table-hover" id="dataTables-settings">
								<thead>
									<tr>
										<th>
											Config
										</th>
										<th>
											Valeur
										</th>
									</tr>
								</thead>
								<tbody>
									<tr class="danger gradeX">
										<td>
											Tables SQL
										</td>
										<td>
											{$sb_dashboard_tables}
										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 1] Table SQL
										</td>
										<td>
											{$sb_dashboard_status1_table}
										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 1] Table (champs)
										</td>
										<td>
											{$sb_dashboard_status1_tbcol}
										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 1] Titre
										</td>
										<td>
											{$sb_dashboard_status1_title}
										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 1] Lien relatif
										</td>
										<td>
											{$sb_dashboard_status1_link}
										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 1] Icône
										</td>
										<td>
											{$sb_dashboard_status1_icon}
										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 2] Table SQL
										</td>
										<td>
											{$sb_dashboard_status2_table}
										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 2] Table (champs)
										</td>
										<td>
											{$sb_dashboard_status2_tbcol}
										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 2] Titre
										</td>
										<td>
											{$sb_dashboard_status2_title}
										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 2] Lien relatif
										</td>
										<td>
											{$sb_dashboard_status2_link}
										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 2] Icône
										</td>
										<td>
											{$sb_dashboard_status2_icon}
										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 3] Table SQL
										</td>
										<td>
											{$sb_dashboard_status3_table}
										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 3] Table (champs)
										</td>
										<td>
											{$sb_dashboard_status3_tbcol}
										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 3] Titre
										</td>
										<td>
											{$sb_dashboard_status3_title}
										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 3] Lien relatif
										</td>
										<td>
											{$sb_dashboard_status3_link}
										</td>
									</tr>
									<tr class="gradeX warning">
										<td>
											[STATUS 3] Icône
										</td>
										<td>
											{$sb_dashboard_status3_icon}
										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 4] Table SQL
										</td>
										<td>
											{$sb_dashboard_status4_table}
										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 4] Table (champs)
										</td>
										<td>
											{$sb_dashboard_status4_tbcol}
										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 4] Titre
										</td>
										<td>
											{$sb_dashboard_status4_title}
										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 4] Lien relatif
										</td>
										<td>
											{$sb_dashboard_status4_link}
										</td>
									</tr>
									<tr class="gradeX success">
										<td>
											[STATUS 4] Icône
										</td>
										<td>
											{$sb_dashboard_status4_icon}
										</td>
									</tr>
								</tbody>
							</table>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
				
            </div>
            <!-- /.row -->

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Your own code

		});
		</script>

	{include file='sb_footer.tpl'}