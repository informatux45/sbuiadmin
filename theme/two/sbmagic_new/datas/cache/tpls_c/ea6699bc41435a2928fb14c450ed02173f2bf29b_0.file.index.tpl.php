<?php
/* Smarty version 3.1.29, created on 2016-12-09 16:10:54
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/one/tpls/inc/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584ac97e89f0e0_04462642',
  'file_dependency' => 
  array (
    'ea6699bc41435a2928fb14c450ed02173f2bf29b' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/one/tpls/inc/index.tpl',
      1 => 1473169878,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_584ac97e89f0e0_04462642 ($_smarty_tpl) {
?>

				<!-- START PRIMARY -->
				<div id="primary" class="sidebar-no">
				    <div class="inner group">
				        <!-- START CONTENT -->
				        <div id="content-page" class="content group">
							
							<?php echo insert_sbGetContentCms(array('o1' => ((string)$_smarty_tpl->tpl_vars['page_view']->value), 'o2' => ((string)$_smarty_tpl->tpl_vars['module_view']->value)),$_smarty_tpl);?>

							
				        </div>
				        <!-- END CONTENT -->
				        <!-- START EXTRA CONTENT -->
				        <!-- END EXTRA CONTENT -->
				    </div>
				</div>
				<!-- END PRIMARY --><?php }
}
