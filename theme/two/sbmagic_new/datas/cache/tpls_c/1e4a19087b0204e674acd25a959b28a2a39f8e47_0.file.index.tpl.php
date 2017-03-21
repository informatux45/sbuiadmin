<?php
/* Smarty version 3.1.29, created on 2016-11-29 15:32:53
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/two/tpls/index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_583d919511d2d3_77865540',
  'file_dependency' => 
  array (
    '1e4a19087b0204e674acd25a959b28a2a39f8e47' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/two/tpls/index.tpl',
      1 => 1473174333,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
    'file:navigation.tpl' => 1,
    'file:inc/index.tpl' => 1,
    'file:inc/page-404.tpl' => 1,
    'file:footer.tpl' => 1,
  ),
),false)) {
function content_583d919511d2d3_77865540 ($_smarty_tpl) {
?>






<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:navigation.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


	<?php if (!$_smarty_tpl->tpl_vars['theme_view']->value || $_smarty_tpl->tpl_vars['theme_view']->value == 'index') {?>
		
		
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/index.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		
	<?php } elseif ($_smarty_tpl->tpl_vars['theme_view']->value == '404') {?>
		
		
		
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:inc/page-404.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


	<?php }?>
	
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
