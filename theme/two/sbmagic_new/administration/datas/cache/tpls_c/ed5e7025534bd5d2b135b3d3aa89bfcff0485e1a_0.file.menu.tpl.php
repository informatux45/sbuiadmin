<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:36:21
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/menu.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857aa15abda50_99862091',
  'file_dependency' => 
  array (
    'ed5e7025534bd5d2b135b3d3aa89bfcff0485e1a' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/menu.tpl',
      1 => 1472829748,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:system/menu_bar.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_5857aa15abda50_99862091 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:system/menu_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>primary<?php } else { ?>default<?php }?>">
                        <div class="panel-heading">
                            <span class="fa <?php if ($_smarty_tpl->tpl_vars['sort']->value) {?>fa-th-list<?php } else { ?>fa-copy<?php }?> fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos menus<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-menus">
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
$__foreach_page_1_saved_item = isset($_smarty_tpl->tpl_vars['page']) ? $_smarty_tpl->tpl_vars['page'] : false;
$_smarty_tpl->tpl_vars['page'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['page']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['page']->value) {
$_smarty_tpl->tpl_vars['page']->_loop = true;
$__foreach_page_1_saved_local_item = $_smarty_tpl->tpl_vars['page'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><?php echo mb_convert_encoding($_smarty_tpl->tpl_vars['page']->value['name'], 'UTF-8', 'HTML-ENTITIES');?>
</td>
												<td><?php echo mb_convert_encoding($_smarty_tpl->tpl_vars['page']->value['tag'], 'UTF-8', 'HTML-ENTITIES');?>
</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['page']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['page']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['page']->value['id'];?>
" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['page']->value['id'];?>
" title="Supprimer"></a>
												</td>
											</tr>										
										<?php
$_smarty_tpl->tpl_vars['page'] = $__foreach_page_1_saved_local_item;
}
if ($__foreach_page_1_saved_item) {
$_smarty_tpl->tpl_vars['page'] = $__foreach_page_1_saved_item;
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
			$('#dataTables-menu').DataTable({
					order: [ 0, 'asc' ],
					responsive: true
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
