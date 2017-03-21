<?php
/* Smarty version 3.1.29, created on 2016-11-28 17:04:23
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/contact.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_583c5587adde20_44637999',
  'file_dependency' => 
  array (
    '1416492706c1b649833b7279324114cebd66746e' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/datas/modules/tpls/contact.tpl',
      1 => 1475672694,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:contact_bar.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_583c5587adde20_44637999 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:contact_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <div class="row">
				
				<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-envelope-o fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos formulaires de contact<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-contact">
                                    <thead>
                                        <tr>
                                            <?php
$_from = $_smarty_tpl->tpl_vars['sb_table_header']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_header_0_saved_item = isset($_smarty_tpl->tpl_vars['header']) ? $_smarty_tpl->tpl_vars['header'] : false;
$_smarty_tpl->tpl_vars['header'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['header']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['header']->value) {
$_smarty_tpl->tpl_vars['header']->_loop = true;
$__foreach_header_0_saved_local_item = $_smarty_tpl->tpl_vars['header'];
?>
												<th>
													<?php echo $_smarty_tpl->tpl_vars['header']->value;?>

												</th>
											<?php
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_0_saved_local_item;
}
if ($__foreach_header_0_saved_item) {
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_0_saved_item;
}
?>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
$_from = $_smarty_tpl->tpl_vars['all']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_contact_1_saved_item = isset($_smarty_tpl->tpl_vars['contact']) ? $_smarty_tpl->tpl_vars['contact'] : false;
$_smarty_tpl->tpl_vars['contact'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['contact']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['contact']->value) {
$_smarty_tpl->tpl_vars['contact']->_loop = true;
$__foreach_contact_1_saved_local_item = $_smarty_tpl->tpl_vars['contact'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['contact']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td>[CS id=<?php echo $_smarty_tpl->tpl_vars['contact']->value['id'];?>
 name=sbcontact class=your-class]</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['contact']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['contact']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['contact']->value['id'];?>
" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['contact']->value['id'];?>
" title="Supprimer"></a>
												</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['contact'] = $__foreach_contact_1_saved_local_item;
}
if ($__foreach_contact_1_saved_item) {
$_smarty_tpl->tpl_vars['contact'] = $__foreach_contact_1_saved_item;
}
?>
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
				<?php }?>
				
				<?php if (!$_smarty_tpl->tpl_vars['all']->value && $_GET['a'] && ($_GET['a'] != 'contact' || $_GET['a'] != 'settings')) {?>
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-envelope-o fa-fw"></span> <strong><?php echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;?>
</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
							<?php include_once ('/Applications/MAMP/htdocs/sbmagic_new/administration/form.php');?>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-8 -->
				
                <div class="col-lg-4">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <span class="fa fa-exclamation-circle fa-fw"></span> <strong>AIDE</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
							<img src="img/contact.jpg" alt="" style="width: 100%;" />
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
							<span style="font-weight: bold;">[SUBMIT name=go]</span>
							
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-4 -->
				<?php }?>
				
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<?php echo '<script'; ?>
>
		$(document).ready(function() {
			$('#dataTables-contact').DataTable({
					order: [ 0, 'desc' ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});	
		});
		<?php echo '</script'; ?>
>
			
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php }
}
