<?php
/* Smarty version 3.1.29, created on 2016-12-09 16:10:54
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/one/tpls/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584ac97e7070a9_89339748',
  'file_dependency' => 
  array (
    'c8e59ace00a2321af40a19c1b9633b8a859a0b20' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/one/tpls/index.tpl',
      1 => 1473169878,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:navigation.tpl' => 1,
    'file:inc/index.tpl' => 1,
    'file:inc/contact.tpl' => 1,
    'file:inc/sidebar-left.tpl' => 1,
    'file:inc/sidebar-right.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_584ac97e7070a9_89339748 ($_smarty_tpl) {
?>








<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


	<?php if (!$_smarty_tpl->tpl_vars['theme_view']->value || $_smarty_tpl->tpl_vars['theme_view']->value == 'index') {?>
		
		
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		
	<?php } elseif ($_smarty_tpl->tpl_vars['theme_view']->value == 'contact') {?>
		
		
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/contact.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		
	<?php } elseif ($_smarty_tpl->tpl_vars['theme_view']->value == 'sidebar-left') {?>
		
		
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/sidebar-left.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


	<?php } elseif ($_smarty_tpl->tpl_vars['theme_view']->value == 'sidebar-right') {?>
		
		
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/sidebar-right.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


	<?php }?>
	
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
