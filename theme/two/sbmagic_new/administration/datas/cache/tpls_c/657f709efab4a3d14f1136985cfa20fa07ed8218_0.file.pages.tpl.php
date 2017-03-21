<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:04
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/pages.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab304d41a0_67906279',
  'file_dependency' => 
  array (
    '657f709efab4a3d14f1136985cfa20fa07ed8218' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/system/pages.tpl',
      1 => 1474551204,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sb_header.tpl' => 1,
    'file:system/pages_bar.tpl' => 1,
    'file:shared/shared-slider-4col.tpl' => 1,
    'file:sb_footer.tpl' => 1,
  ),
),false)) {
function content_5857ab304d41a0_67906279 ($_smarty_tpl) {
if (!is_callable('smarty_function_cycle')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.cycle.php';
if (!is_callable('smarty_modifier_replace')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/modifier.replace.php';
?>

	


	
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('module'=>$_smarty_tpl->tpl_vars['module_page']->value), 0, false);
?>

	
			
			
			
			
			
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:system/pages_bar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


            <div class="row">
				<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <span class="fa fa-copy fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos pages<?php } else {
echo $_smarty_tpl->tpl_vars['legend_add_edit']->value;
}?></strong>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-pages">
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
												<td><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['page']->value['title'], 'UTF-8', 'HTML-ENTITIES'));?>
</td>
												<td><?php echo mb_convert_encoding($_smarty_tpl->tpl_vars['page']->value['theme_view'], 'UTF-8', 'HTML-ENTITIES');?>
</td>
												<td><?php echo mb_convert_encoding($_smarty_tpl->tpl_vars['page']->value['module_view'], 'UTF-8', 'HTML-ENTITIES');?>
</td>
												<td><a href="<?php echo $_smarty_tpl->tpl_vars['seo_url_cms']->value;
echo mb_convert_encoding($_smarty_tpl->tpl_vars['page']->value['seo_url'], 'UTF-8', 'HTML-ENTITIES');?>
" target="_blank"><?php echo mb_convert_encoding($_smarty_tpl->tpl_vars['page']->value['seo_url'], 'UTF-8', 'HTML-ENTITIES');?>
</a></td>
												<td>
													<span class="glyphicon glyphicon-eye-open <?php if ($_smarty_tpl->tpl_vars['page']->value['active']) {?>green<?php } else { ?>red<?php }?>" title="Statut <?php if ($_smarty_tpl->tpl_vars['page']->value['active']) {?>visible<?php } else { ?>non visible<?php }?>"></span>
													&nbsp;
													<a class="glyphicon glyphicon-cog" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=edit<?php if ($_smarty_tpl->tpl_vars['page']->value['url_custom'] != '') {?>custom<?php }?>&id=<?php echo $_smarty_tpl->tpl_vars['page']->value['id'];?>
" title="Modifier"></a>
													<?php if ($_smarty_tpl->tpl_vars['page']->value['seo_url'] != '' || $_smarty_tpl->tpl_vars['page']->value['url_custom'] != '') {?>
													&nbsp;
													<a class="glyphicon glyphicon-remove red jConfirm" href="<?php echo $_smarty_tpl->tpl_vars['module_url']->value;?>
&a=del&id=<?php echo $_smarty_tpl->tpl_vars['page']->value['id'];?>
" title="Supprimer"></a>
													<?php }?>
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

                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
				
				<?php } else { ?>
				
                <div class="col-lg-<?php if ($_GET['a'] == 'addcustom' || $_GET['a'] == 'editcustom') {?>12<?php } else { ?>8<?php }?>">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span class="fa fa-copy fa-fw"></span> <strong><?php if ($_smarty_tpl->tpl_vars['all']->value) {?>Gestion de vos pages<?php } else {
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
                <!-- /.col-lg-8 -->

				<?php if ($_GET['a'] != 'addcustom' && $_GET['a'] != 'editcustom') {?>
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<span class="fa fa-columns fa-fw"></span> <strong>Choix du template (VIEW)</strong>
							</div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								<div class="theme_view">
									<img id="img_theme_view" src="" title="" />
								</div>
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-4 -->

					<?php if ($_smarty_tpl->tpl_vars['show_headpage']->value) {?>
						<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:shared/shared-slider-4col.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

					<?php }?>

				<?php }?>

				<?php }?>
            </div>
            <!-- /.row -->
	
		<!-- ------------------------------------------------------------ -->
		<!-- Page-Level Scripts - Use this space this write your own code -->
		<!-- ------------------------------------------------------------ -->
		<?php echo '<script'; ?>
>
		$(document).ready(function() {
			$('#dataTables-pages').DataTable({
					responsive: true,
					"lengthMenu": [25, 50, 75, 100, 150]
			});
			$("#theme_view").change(function () {
				var str = $( "select#theme_view option:selected" ).val();
				var new_theme_view = '<?php echo smarty_modifier_replace(@constant('SB_THEME_URL'),((string)@constant('SBADMIN')),'');?>
screenshot-'+str+'.jpg';
				if (str != '') {
					$('img#img_theme_view').attr('src', new_theme_view);
				} else {
					$('img#img_theme_view').attr('src', '<?php echo @constant('_AM_SITE_IMG_URL');?>
theme-noview.jpg');
				}
			}).change();
		});
		<?php echo '</script'; ?>
>
			
	<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:sb_footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php }
}
