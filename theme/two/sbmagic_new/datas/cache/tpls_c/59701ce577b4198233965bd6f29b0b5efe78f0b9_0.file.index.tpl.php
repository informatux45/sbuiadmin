<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:58
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/three/tpls/inc/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab6654e109_66516214',
  'file_dependency' => 
  array (
    '59701ce577b4198233965bd6f29b0b5efe78f0b9' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/three/tpls/inc/index.tpl',
      1 => 1476196832,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857ab6654e109_66516214 ($_smarty_tpl) {
?>


				<!-- START PRIMARY -->
				<div id="primary" class="sidebar-<?php echo (($tmp = @$_smarty_tpl->tpl_vars['sidebar']->value)===null||$tmp==='' ? "no" : $tmp);?>
">
					
					 <!-- START INNER GROUP -->
				    <div <?php echo insert_sbGetSectionClassId(array('class' => "inner group", 'evenid' => '', 'op' => ((string)$_GET['op']), 'page' => ((string)$_smarty_tpl->tpl_vars['page_id']->value), 'id' => ((string)$_GET['id']), 'ti' => ((string)$_smarty_tpl->tpl_vars['sb_title']->value)),$_smarty_tpl);?>
>
						
				        <!-- START CONTENT -->
				        <div id="content-page" class="content group">
							<?php echo insert_sbGetContentCms(array('o1' => ((string)$_smarty_tpl->tpl_vars['page_view']->value), 'o2' => ((string)$_smarty_tpl->tpl_vars['module_view']->value)),$_smarty_tpl);?>

				        </div>
				        <!-- END CONTENT -->
				        
						<?php if ($_smarty_tpl->tpl_vars['sidebar']->value != "no") {?>
				        <!-- START SIDEBAR -->
				        <div class="sidebar group">
							<?php echo insert_sbGetContentCms(array('o1' => ((string)$_smarty_tpl->tpl_vars['page_view_blocks']->value), 'o2' => ((string)$_smarty_tpl->tpl_vars['module_view_blocks']->value)),$_smarty_tpl);?>

				        </div>
				        <!-- END SIDEBAR -->
						<?php }?>

				    </div> <!-- END INNER GROUP -->
					
				</div>
				<!-- END PRIMARY --><?php }
}
