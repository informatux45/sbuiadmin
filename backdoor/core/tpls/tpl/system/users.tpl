{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='system/users_bar.tpl'}
			
			{* Notes full width *}
			{if $all}
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <span class="fa fa-info-circle"></span> <strong>Vos informations</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{* HTML Text Formatted *}
							Vous êtes connecté sous l'utilisateur : <span style="color: red;">{$sbmagic_user_name}</span>
							<br>
							Votre groupe d'utilisateur : {$sbmagic_user_type|@strtoupper}
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			{/if}

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-{if $all}primary{else}default{/if}">
                        <div class="panel-heading">
                            <span class="fa {if $sort}fa-th-list{else}fa-user{/if} fa-fw"></span> <strong>{if $all}Gestion de vos utilisateurs{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{if $all}
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-users">
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
										{if $alluser}
											{foreach from=$alluser item=user}
												<tr class="{cycle values="odd,even"} gradeX">
													<td><img src="{$user.email|@sbGetGravatar}" class="img-thumbnail" /></td>
													<td>{$user.username|@sbGetUserGroup|upper}</td>
													<td>{$user.username}</td>
													<td>{$user.email}</td>
													<td>{$user.lastlogin|date_format:"%d.%m.%Y - %R"}</td>
													<td>
														<span class="glyphicon glyphicon-eye-open {if $user.active}green{else}red{/if}" title="Statut {if $user.active}visible{else}non visible{/if}"></span>
														&nbsp;
														<a class="glyphicon glyphicon-list yellow" href="{$module_url}&a=menu&id={$user.id}" title="Autorisation menu"></a>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$user.id}" title="Modifier"></a>
														&nbsp;												
														{if $user.username != $sbmagic_user_name}
															<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$user.id}" title="Supprimer"></a>
														{/if}
													</td>
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
			$('#dataTables-users').DataTable({
					order: [ 0, 'asc' ],
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

