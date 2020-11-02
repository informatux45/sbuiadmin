{* -------------- *}
{* --- MODULE --- *}	
{* -------------- *}

	{* ------------------ Headers ----------------- *}
	{include file='sb_header.tpl' module=$module_page}
	{* ---------------- End Headers --------------- *}
			
			{* ------------------------------------------------ *}
			{*       Write your own code after this line        *}
			{* ------------------------------------------------ *}
			
			{include file='contact_bar.tpl'}

            <div class="row">
				
				{if $all}
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-envelope-o fa-fw"></span> <strong>{if $all}Gestion de vos formulaires de contact{else}{$legend_add_edit}{/if}</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-contact">
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
										{if $allcontact}
											{foreach from=$allcontact item=contact}
												<tr class="{cycle values="odd,even"} gradeX">
													<td>{$contact.title|unescape:"htmlall"|@sbDisplayLang}</td>
													<td>[CS id={$contact.id} name=sbcontact class=your-class]</td>
													<td>
														<span class="glyphicon glyphicon-eye-open {if $contact.active}green{else}red{/if}" title="Statut {if $contact.active}visible{else}non visible{/if}"></span>
														&nbsp;
														<a class="glyphicon glyphicon-cog" href="{$module_url}&a=edit&id={$contact.id}" title="Modifier"></a>
														&nbsp;
														<a class="glyphicon glyphicon-remove red jConfirm" href="{$module_url}&a=del&id={$contact.id}" title="Supprimer"></a>
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
				
				{if !$all && $smarty.get.a && ($smarty.get.a != 'contact' || $smarty.get.a != 'settings') }
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-envelope-o fa-fw"></span> <strong>{$legend_add_edit}</strong>
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
                <!-- /.col-lg-8 -->
				
                <div class="col-lg-4">
					{* ------------------------------------ *}
					{* --- Include Shared Panel Actions --- *}
					{include file='shared/shared-panel-actions.tpl'}
					{* ------------------------------------ *}
					{* ------------------------------------ *}
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-exclamation-circle fa-fw"></span> <strong>AIDE</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							{* Aide Contact *}
							<img src="img/contact.jpg" alt="" style="width: 100%;" />
							{if $smarty.get.a != 'settings'}
							<br><br>
							Si les champs suivants ne sont pas remplis, les paramètres par défaut
							seront utilisés :<br>
							- Email(s) de destinataires<br>
							- Sujet<br>
							<br>
							<u>Le formulaire de contact</u><br>
							<br>
							Il doit contenir au minimum les 2 champs suivants :<br>
							- nom (de votre correspondant)<br>
							- email (de votre correspondant)<br>
							Deux boutons sont prévus à cet effet (NAME, EMAIL).<br>
							<br>
							Vous pouvez insérer du HTML.<br>
							<br>
							Placer votre curseur dans votre formulaire, puis cliquez sur le type de
							champs (bouton) que vous souhaitez insérer.<br>
							Les champs doivent contenir un nom (sans espaces, sans caractères
							accentués et sans ponctuations) et/ou un caractère d'obligation pour
							être rempli.<br>
							<br>
							<span style="text-decoration: underline;">Exemples :</span> <br>
							<br>
							Champs TEXT avec obligation d'être rempli<br>
							<span style="font-weight: bold;">[TEXT name=telephone/required=required]</span><br>
							<br>
							Champs TEXT avec obligation d'être rempli et placeholder<br>
							<span style="font-weight: bold;">[TEXT name=telephone/required=required/placeholder=Votre téléphone]</span><br>
							<br>
							Champs CHECKBOX avec obligation d'être rempli<br>
							<span style="font-weight: bold;">[CHECKBOX name=telephone/required=required]</span><br>
							<br>
							Champs TEXTAREA sans obligation d'être rempli<br>
							<span style="font-weight: bold;">[TEXTAREA name=message]</span><br>
							<br>
							Chams SELECT avec obligation d'être rempli<br>
							<span style="font-weight: bold;">[SELECT name=selection/
							options=choisissez un choix|choix 1|choix 2|choix 3|choix 4/value=0|10|20|30|40/
							required=required]</span><br>
							<span style="font-style: italic;">Les choix doivent être séparés par
							des |</span><br style="font-style: italic;">
							<span style="font-style: italic;"> Mettre autant de "value" que de
							"options"</span><br style="font-style: italic;">
							<span style="font-style: italic;">- options: Textes dans le champs SELECT</span><br
							style="font-style: italic;">
							<span style="font-style: italic;">- value: Valeurs affectées à chaque
							choix</span><span style="font-weight: bold;"></span><br>
							<br>
							Champs SUBMIT<br>
							<span style="font-weight: bold;">[SUBMIT name=go]</span><br>
							<span style="font-style: italic;">Si vous choisissez le RECAPTCHA INVISIBLE, celui-ci devient le bouton SUBMIT donc ne pas insérez le bouton SUBMIT cause double emploi.</span>
							{/if}
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-4 -->
				{/if}
				
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<script>
		$(document).ready(function() {
			$('#dataTables-contact').DataTable({
					order: [ 0, 'desc' ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});	
		});
		</script>
			
	{include file='sb_footer.tpl'}

