<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:58
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/three/tpls/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab6636c089_17492282',
  'file_dependency' => 
  array (
    '445d1a9c7f19ab64d335d53d32a2f04d1f7cb9f2' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/three/tpls/index.tpl',
      1 => 1475762792,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 6,
    'file:navigation.tpl' => 6,
    'file:inc/index.tpl' => 6,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_5857ab6636c089_17492282 ($_smarty_tpl) {
?>








	
	<?php if (!$_smarty_tpl->tpl_vars['theme_view']->value || $_smarty_tpl->tpl_vars['theme_view']->value == 'index') {?>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('viewtype'=>'stretched'), 0, false);
?>
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('sidebar'=>"no"), 0, false);
?>

	<?php }?>

	
	<?php if ($_smarty_tpl->tpl_vars['theme_view']->value == 'index-left-sidebar') {?>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('viewtype'=>'stretched'), 0, true);
?>
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('sidebar'=>"left"), 0, true);
?>

	<?php }?>

	
	<?php if ($_smarty_tpl->tpl_vars['theme_view']->value == 'index-right-sidebar') {?>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('viewtype'=>'stretched'), 0, true);
?>
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('sidebar'=>"right"), 0, true);
?>

	<?php }?>

	
	<?php if ($_smarty_tpl->tpl_vars['theme_view']->value == 'boxed-full-width') {?>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('viewtype'=>'boxed'), 0, true);
?>
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('sidebar'=>"no"), 0, true);
?>

	<?php }?>

	
	<?php if ($_smarty_tpl->tpl_vars['theme_view']->value == 'boxed-left-sidebar') {?>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('viewtype'=>'boxed'), 0, true);
?>
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('sidebar'=>"left"), 0, true);
?>

	<?php }?>

	
	<?php if ($_smarty_tpl->tpl_vars['theme_view']->value == 'boxed-right-sidebar') {?>
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('viewtype'=>'boxed'), 0, true);
?>
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('sidebar'=>"right"), 0, true);
?>

	<?php }?>
	
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
