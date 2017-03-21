<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:48
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/blocs.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab5c2f3393_71610642',
  'file_dependency' => 
  array (
    '98a99aa46e9a8215e158426457c42f425808fd06' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/blocs.tpl',
      1 => 1476261205,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:system/pages_bar.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_5857ab5c2f3393_71610642 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_capitalize')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/modifier.capitalize.php';
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:system/pages_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <div class="row">
				<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
                <div class="col-lg-6">
					<div class="panel panel-yellow">
                        <div class="panel-heading">
                            <span class="fa fa-sort-amount-asc fa-fw"></span> <strong>Trier vos blocs par page</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>
								<?php
$_from = $_smarty_tpl->tpl_vars['all_pages']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_page_0_saved_item = isset($_smarty_tpl->tpl_vars['page']) ? $_smarty_tpl->tpl_vars['page'] : false;
$_smarty_tpl->tpl_vars['page'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['page']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['page']->value) {
$_smarty_tpl->tpl_vars['page']->_loop = true;
$__foreach_page_0_saved_local_item = $_smarty_tpl->tpl_vars['page'];
?>
									<button class="btn btn-outline btn-success" type="button" onclick="location.href='index.php?p=blocs&a=sort&pid=<?php echo $_smarty_tpl->tpl_vars['page']->value['id'];?>
'">
										<?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['page']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>

									</button>
								<?php
$_smarty_tpl->tpl_vars['page'] = $__foreach_page_0_saved_local_item;
}
if ($__foreach_page_0_saved_item) {
$_smarty_tpl->tpl_vars['page'] = $__foreach_page_0_saved_item;
}
?>
                            </p>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-6 -->

                <div class="col-lg-6">
					<div class="panel panel-yellow">
                        <div class="panel-heading">
                            <span class="fa fa-sort-amount-desc fa-fw"></span> <strong>Trier vos blocs par module</strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <p>
								<?php
$__section_mod_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_mod']) ? $_smarty_tpl->tpl_vars['__smarty_section_mod'] : false;
$__section_mod_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['all_modules']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_mod_0_total = $__section_mod_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_mod'] = new Smarty_Variable(array());
if ($__section_mod_0_total != 0) {
for ($__section_mod_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_mod']->value['index'] = 0; $__section_mod_0_iteration <= $__section_mod_0_total; $__section_mod_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_mod']->value['index']++){
?>
									<button class="btn btn-outline btn-info" type="button" onclick="location.href='index.php?p=blocs&a=sortmod&pid=<?php echo $_smarty_tpl->tpl_vars['all_modules']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_mod']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_mod']->value['index'] : null)];?>
'">
										<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['all_modules']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_mod']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_mod']->value['index'] : null)]);?>

									</button>
								<?php
}
}
if ($__section_mod_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_mod'] = $__section_mod_0_saved;
}
?>
							</p>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-lg-6 -->
				
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-copy fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos blocs<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-blocs">
                                    <thead>
                                        <tr>
                                            <?php
$_from = $_smarty_tpl->tpl_vars['sb_table_header']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_header_1_saved_item = isset($_smarty_tpl->tpl_vars['header']) ? $_smarty_tpl->tpl_vars['header'] : false;
$_smarty_tpl->tpl_vars['header'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['header']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['header']->value) {
$_smarty_tpl->tpl_vars['header']->_loop = true;
$__foreach_header_1_saved_local_item = $_smarty_tpl->tpl_vars['header'];
?>
												<th>
													<?php echo $_smarty_tpl->tpl_vars['header']->value;?>

												</th>
											<?php
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_1_saved_local_item;
}
if ($__foreach_header_1_saved_item) {
$_smarty_tpl->tpl_vars['header'] = $__foreach_header_1_saved_item;
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
$__foreach_bloc_2_saved_item = isset($_smarty_tpl->tpl_vars['bloc']) ? $_smarty_tpl->tpl_vars['bloc'] : false;
$_smarty_tpl->tpl_vars['bloc'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['bloc']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['bloc']->value) {
$_smarty_tpl->tpl_vars['bloc']->_loop = true;
$__foreach_bloc_2_saved_local_item = $_smarty_tpl->tpl_vars['bloc'];
?>
											<tr class="<?php echo smarty_function_cycle(array('values'=>"odd,even"),$_smarty_tpl);?>
 gradeX">
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['bloc']->value['name'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['bloc']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['bloc']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['bloc']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['bloc']->value['id'];?>
" title="Modifier"></a>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['bloc']->value['id'];?>
" title="Supprimer"></a>
												</td>
											</tr>
										
										<?php
$_smarty_tpl->tpl_vars['bloc'] = $__foreach_bloc_2_saved_local_item;
}
if ($__foreach_bloc_2_saved_item) {
$_smarty_tpl->tpl_vars['bloc'] = $__foreach_bloc_2_saved_item;
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
				
				<?php } else { ?>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-copy fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos blocs<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							
							<?php include_once ('/Applications/MAMP/htdocs/sbmagic_new/administration/form.php');?>

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->

				<?php }?>
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<?php echo '<script'; ?>
>
		$(document).ready(function() {
			$('#dataTables-blocs').DataTable({
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
