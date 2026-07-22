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
							<div class="data-toolbar">
								<div class="data-toolbar-left">
									<div class="input-icon" style="flex:1;max-width:320px">
										<span class="ico"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
										<input class="input" type="search" placeholder="Rechercher..." data-datatable-search="dataTables-logaccess">
									</div>
								</div>
							</div>
							<div style="overflow-x:auto">
                                <table class="data-table" id="dataTables-logaccess" data-datatable data-page-size="50">
                                    <thead>
                                        <tr>
                                            {foreach from=$sb_table_header item=header}
												<th>
													{$header} <span class="sort"><svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span>
												</th>
											{/foreach}
                                        </tr>
                                    </thead>
                                    <tbody>
										{if $allaccess}
											{foreach from=$allaccess item=log}
												<tr class="data-row"{if $log.logaccess_type == 'error'} style="background:var(--danger-soft)"{/if}>
													<td>{$log.id}</td>
													<td data-sort-value="{$log.logaccess_date}">{$log.logaccess_date|date_format:"%d.%m.%Y - %R"}</td>
													<td>{$log.logaccess_user}</td>
													<td>{$log.logaccess_type}</td>
													<td>{$log.logaccess_event}</td>
												</tr>
											{/foreach}
										{/if}
                                    </tbody>
                                </table>
                            </div>
							<div class="data-foot" data-datatable-foot="dataTables-logaccess">
								<div class="data-foot-info" data-foot-info></div>
								<div class="pager"></div>
							</div>
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
			
	{include file='sb_footer.tpl' page='false' pagef='false'}

