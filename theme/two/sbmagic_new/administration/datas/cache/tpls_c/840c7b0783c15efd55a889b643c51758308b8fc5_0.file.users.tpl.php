<?php
/* Smarty version 3.1.29, created on 2016-12-12 16:01:32
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/users.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584ebbcc271375_64945744',
  'file_dependency' => 
  array (
    '840c7b0783c15efd55a889b643c51758308b8fc5' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/users.tpl',
      1 => 1476097627,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:system/users_bar.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_584ebbcc271375_64945744 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/modifier.date_format.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:system/users_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

			
			
			<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <span class="fa fa-info-circle"></span> <strong>Vos informations</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
							Vous êtes connecté sous l'utilisateur : <span style="color: red;"><?php echo $_smarty_tpl->tpl_vars['sbmagic_user_name']->value;?>
</span>
							<br>
							Votre groupe d'utilisateur : <?php echo strtoupper($_smarty_tpl->tpl_vars['sbmagic_user_type']->value);?>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
			<?php }?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>primary<?php } else { ?>default<?php }?>">
                        <div class="panel-heading">
                            <span class="fa <?php if ($_smarty_tpl->tpl_vars['sort']->value) {?>fa-th-list<?php } else { ?>fa-user<?php }?> fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos utilisateurs<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-users">
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
$__foreach_user_1_saved_item = isset($_smarty_tpl->tpl_vars['user']) ? $_smarty_tpl->tpl_vars['user'] : false;
$_smarty_tpl->tpl_vars['user'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['user']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['user']->value) {
$_smarty_tpl->tpl_vars['user']->_loop = true;
$__foreach_user_1_saved_local_item = $_smarty_tpl->tpl_vars['user'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><img src="<?php echo sbGetGravatar($_smarty_tpl->tpl_vars['user']->value['email']);?>
" class="img-thumbnail" /></td>
												<td><?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
</td>
												<td><?php echo $_smarty_tpl->tpl_vars['user']->value['email'];?>
</td>
												<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['user']->value['lastlogin'],"%d.%m.%Y - %R");?>
</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['user']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['user']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-list yellow" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=menu&id=<?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
" title="Autorisation menu"></a>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
" title="Modifier"></a>
													&nbsp;												
													<?php if ($_smarty_tpl->tpl_vars['user']->value['username'] != $_smarty_tpl->tpl_vars['sbmagic_user_name']->value) {?>
														<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['user']->value['id'];?>
" title="Supprimer"></a>
													<?php }?>
												</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['user'] = $__foreach_user_1_saved_local_item;
}
if ($__foreach_user_1_saved_item) {
$_smarty_tpl->tpl_vars['user'] = $__foreach_user_1_saved_item;
}
?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
							<?php } else { ?>
								
								<?php include_once ('/Applications/MAMP/htdocs/sbmagic_new/administration/form.php');?>

							<?php }?>
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
		<?php echo '<script'; ?>
>
		$(document).ready(function() {
			$('#dataTables-users').DataTable({
					order: [ 0, 'asc' ],
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			<?php if ($_smarty_tpl->tpl_vars['sort']->value) {?>
				$( "#sortable" ).sortable({
					axis: "y",
					placeholder: "ui-state-highlight",
					
				});
				$( "#sortable" ).disableSelection();
			<?php }?>
		});
		<?php echo '</script'; ?>
>
		
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php }
}
