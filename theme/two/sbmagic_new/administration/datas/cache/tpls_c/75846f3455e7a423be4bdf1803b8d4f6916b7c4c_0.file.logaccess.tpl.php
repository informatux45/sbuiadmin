<?php
/* Smarty version 3.1.29, created on 2016-12-12 15:36:56
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/logaccess.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584eb608f20535_14742719',
  'file_dependency' => 
  array (
    '75846f3455e7a423be4bdf1803b8d4f6916b7c4c' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/logaccess.tpl',
      1 => 1480078724,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_584eb608f20535_14742719 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/modifier.date_format.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>primary<?php } else { ?>default<?php }?>">
                        <div class="panel-heading">
                            <span class="fa fa-eye fa-fw"></span> <strong>Journaux de connexion</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-logaccess">
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
$__foreach_log_1_saved_item = isset($_smarty_tpl->tpl_vars['log']) ? $_smarty_tpl->tpl_vars['log'] : false;
$_smarty_tpl->tpl_vars['log'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['log']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['log']->value) {
$_smarty_tpl->tpl_vars['log']->_loop = true;
$__foreach_log_1_saved_local_item = $_smarty_tpl->tpl_vars['log'];
?>
											<tr class="<?php if ($_smarty_tpl->tpl_vars['log']->value['logaccess_type'] == 'error') {?>danger<?php } else {
echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX<?php }?>">
												<td><?php echo $_smarty_tpl->tpl_vars['log']->value['id'];?>
</td>
												<td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['log']->value['logaccess_date'],"%d.%m.%Y - %R");?>
</td>
												<td><?php echo $_smarty_tpl->tpl_vars['log']->value['logaccess_user'];?>
</td>
												<td><?php echo $_smarty_tpl->tpl_vars['log']->value['logaccess_type'];?>
</td>
												<td><?php echo $_smarty_tpl->tpl_vars['log']->value['logaccess_event'];?>
</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['log'] = $__foreach_log_1_saved_local_item;
}
if ($__foreach_log_1_saved_item) {
$_smarty_tpl->tpl_vars['log'] = $__foreach_log_1_saved_item;
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
			$('#dataTables-logaccess').DataTable({
					order: [ 0, 'desc' ],
					responsive: true,
					"lengthMenu": [50, 75, 100, 200, 500]
			});
		});
		<?php echo '</script'; ?>
>
			
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php }
}
