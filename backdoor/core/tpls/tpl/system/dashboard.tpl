{* -------------- *}
{* --- SYSTEM --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page page='false'}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='shared/shared-settings-hero.tpl'}
			
			{* Notes col lg 6 *}
			<div class="grid">

				<section class="col-6 card">
                    <div class="card-head">
						<div class="card-title-wrap">
							<h2 class="card-title">Configuration</h2>
						</div>
                    </div>
							{* Afficher le formulaire EDIT *}
							{include_php file='form.php'}
                </section>

                <div class="col-6">
                    {* ------------------------------------ *}
                    {* --- Include Shared Panel Actions --- *}
                    {include file='shared/shared-panel-actions.tpl'}

                    <div class="card">
                        <div class="card-head">
							<div class="card-title-wrap">
								<h2 class="card-title">Rappel de votre configuration</h2>
							</div>
                        </div>
							{* HTML Text Formatted *}
							<div style="overflow-x:auto">
							<table class="data-table" id="dataTables-settings">
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
									<tr class="data-row" style="background:var(--danger-soft)">
										<td>
											Tables SQL
										</td>
										<td>
											{if $sb_dashboard_tables}{$sb_dashboard_tables}{/if}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--warning-soft)">
										<td>
											[STATUS 1] Table SQL
										</td>
										<td>
											{$sb_dashboard_status1_table}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--warning-soft)">
										<td>
											[STATUS 1] Table (champs)
										</td>
										<td>
											{$sb_dashboard_status1_tbcol}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--warning-soft)">
										<td>
											[STATUS 1] Titre
										</td>
										<td>
											{$sb_dashboard_status1_title}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--warning-soft)">
										<td>
											[STATUS 1] Lien relatif
										</td>
										<td>
											{$sb_dashboard_status1_link}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--warning-soft)">
										<td>
											[STATUS 1] Icône
										</td>
										<td>
											{$sb_dashboard_status1_icon}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--success-soft)">
										<td>
											[STATUS 2] Table SQL
										</td>
										<td>
											{$sb_dashboard_status2_table}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--success-soft)">
										<td>
											[STATUS 2] Table (champs)
										</td>
										<td>
											{$sb_dashboard_status2_tbcol}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--success-soft)">
										<td>
											[STATUS 2] Titre
										</td>
										<td>
											{$sb_dashboard_status2_title}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--success-soft)">
										<td>
											[STATUS 2] Lien relatif
										</td>
										<td>
											{$sb_dashboard_status2_link}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--success-soft)">
										<td>
											[STATUS 2] Icône
										</td>
										<td>
											{$sb_dashboard_status2_icon}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--warning-soft)">
										<td>
											[STATUS 3] Table SQL
										</td>
										<td>
											{$sb_dashboard_status3_table}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--warning-soft)">
										<td>
											[STATUS 3] Table (champs)
										</td>
										<td>
											{$sb_dashboard_status3_tbcol}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--warning-soft)">
										<td>
											[STATUS 3] Titre
										</td>
										<td>
											{$sb_dashboard_status3_title}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--warning-soft)">
										<td>
											[STATUS 3] Lien relatif
										</td>
										<td>
											{$sb_dashboard_status3_link}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--warning-soft)">
										<td>
											[STATUS 3] Icône
										</td>
										<td>
											{$sb_dashboard_status3_icon}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--success-soft)">
										<td>
											[STATUS 4] Table SQL
										</td>
										<td>
											{if $sb_dashboard_status4_table}{$sb_dashboard_status4_table}{/if}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--success-soft)">
										<td>
											[STATUS 4] Table (champs)
										</td>
										<td>
											{if $sb_dashboard_status4_tbcol}{$sb_dashboard_status4_tbcol}{/if}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--success-soft)">
										<td>
											[STATUS 4] Titre
										</td>
										<td>
											{if $sb_dashboard_status4_title}{$sb_dashboard_status4_title}{/if}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--success-soft)">
										<td>
											[STATUS 4] Lien relatif
										</td>
										<td>
											{if $sb_dashboard_status4_link}{$sb_dashboard_status4_link}{/if}
										</td>
									</tr>
									<tr class="data-row" style="background:var(--success-soft)">
										<td>
											[STATUS 4] Icône
										</td>
										<td>
											{if $sb_dashboard_status4_icon}{$sb_dashboard_status4_icon}{/if}
										</td>
									</tr>
								</tbody>
							</table>
							</div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col-6 -->

            </div>
            <!-- /.grid -->

		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			// Your own code

		});
		</script>

	{include file='sb_footer.tpl' page='false' pagef='false'}