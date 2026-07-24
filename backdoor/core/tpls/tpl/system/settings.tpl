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
									<tr class="data-row">
										<td>
											Nom du client
										</td>
										<td>
											{$sb_config_customer_name}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Nom des Administrateurs
										</td>
										<td>
											{$sb_config_administrators}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Database Host
										</td>
										<td>
											**************
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Database Name
										</td>
										<td>
											**************
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Database User
										</td>
										<td>
											**************
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Database Password
										</td>
										<td>
											**************
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Database Prefix Table
										</td>
										<td>
											**************
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Répertoire d'uploads
										</td>
										<td>
											{$sb_config_diruploads}
										</td>
									</tr>
									</tr>
									<tr class="data-row">
										<td>
											URL d'uploads
										</td>
										<td>
											{$sb_config_urluploads}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Upload Max autorisé
										</td>
										<td>
											{$sb_config_upload_max}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Upload Extensions autorisées
										</td>
										<td>
											{$sb_config_upload_exts}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Nombre d'uploads simultanés autorisées
										</td>
										<td>
											{$sb_config_upload_limit}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Taille maximum autorisée pour vos médias (px)<br>(hauteur et largeur confondues)
										</td>
										<td>
											{$sb_config_scaling_maxsize}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Nom des modules autorisés
										</td>
										<td>
											{$sb_config_modules}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Google ReCaptcha Clé Publique
										</td>
										<td>
											**************
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Google ReCaptcha Clé Secrète
										</td>
										<td>
											**************
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Mode debug général (Kint)
										</td>
										<td>
											{if $sb_config_debug_general == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Mode debug formulaire
										</td>
										<td>
											{if $sb_config_debug_form == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Mode debug Smarty
										</td>
										<td>
											{if $sb_config_debug_smarty == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Sandbox
										</td>
										<td>
											{if $sb_config_sandbox == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											CMS
										</td>
										<td>
											{if $sb_config_cms == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="data-row">
										<td>
											Captcha (Login)
										</td>
										<td>
											{if $sb_config_captcha_mode == 1}Activé{else}Désactivé{/if}
										</td>
									</tr>
									<tr class="data-row">
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