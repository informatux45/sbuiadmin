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
                            <i class="fa fa-cubes fa-fw"></i> Configuration
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
		
                   {* ------------------------------------ *}
                   {* --- Include Shared Panel Actions --- *}
                   {include file='shared/shared-panel-actions.tpl'}
		
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
									<tr class="info gradeX">
										<td>
											Nom du client
										</td>
										<td>
											{$sb_config_customer_name}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Nom des Administrateurs
										</td>
										<td>
											{$sb_config_administrators}
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Database Host
										</td>
										<td>
											{$sb_config_dbhost}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Database Name
										</td>
										<td>
											{$sb_config_dbname}
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Database User
										</td>
										<td>
											{$sb_config_dbuser}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Database Password
										</td>
										<td>
											{$sb_config_dbpwd}
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Database Prefix Table
										</td>
										<td>
											{$sb_config_dbprefix}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Répertoire d'uploads
										</td>
										<td>
											{$sb_config_diruploads}
										</td>
									</tr>
									</tr>
									<tr class="info gradeX">
										<td>
											URL d'uploads
										</td>
										<td>
											{$sb_config_urluploads}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Upload Max autorisé
										</td>
										<td>
											{$sb_config_upload_max}
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Upload Extensions autorisées
										</td>
										<td>
											{$sb_config_upload_exts}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Nombre d'uploads simultanés autorisées
										</td>
										<td>
											{$sb_config_upload_limit}
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Taille maximum autorisée pour vos médias (px)<br>(hauteur et largeur confondues)
										</td>
										<td>
											{$sb_config_scaling_maxsize}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Nom des modules autorisés
										</td>
										<td>
											{$sb_config_modules}
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Google ReCaptcha Clé Publique
										</td>
										<td>
											{$sb_config_recaptcha_public}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Google ReCaptcha Clé Secrète
										</td>
										<td>
											{$sb_config_recaptcha_secret}
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Mode debug général (Kint)
										</td>
										<td>
											{if $sb_config_debug_general == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Mode debug formulaire
										</td>
										<td>
											{if $sb_config_debug_form == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Mode debug Smarty
										</td>
										<td>
											{if $sb_config_debug_smarty == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Sandbox
										</td>
										<td>
											{if $sb_config_sandbox == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											CMS
										</td>
										<td>
											{if $sb_config_cms == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="gradeX">
										<td>
											Captcha (Login)
										</td>
										<td>
											{if $sb_config_captcha_mode == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="info gradeX">
										<td>
											Mode UPGRADE
										</td>
										<td>
											{if $sb_config_upgrade_mode == 1}Activé{else}Désactivé{/if}
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
