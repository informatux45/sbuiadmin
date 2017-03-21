<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:07
  from "/Applications/MAMP/htdocs/sbmagic_new/datas/modules/contact/tpls/contact_index_blocks.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab33d251d8_19011167',
  'file_dependency' => 
  array (
    '0cd35c7d935640ff9959d74fe7295936119d2287' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/datas/modules/contact/tpls/contact_index_blocks.tpl',
      1 => 1476266182,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857ab33d251d8_19011167 ($_smarty_tpl) {
?>


<?php if ($_GET['p'] != 'pages') {?>

	<?php $_smarty_tpl->assign('blocks' , insert_sbGetContentCmsBlocks (array('pid' => ((string)mb_strtolower(@constant('MODULENAME'), 'UTF-8')), 'lang' => ((string)$_SESSION['lang']), 'mod' => "module"),$_smarty_tpl), true);?>
	
	<?php
$_from = $_smarty_tpl->tpl_vars['blocks']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_block_0_saved_item = isset($_smarty_tpl->tpl_vars['block']) ? $_smarty_tpl->tpl_vars['block'] : false;
$_smarty_tpl->tpl_vars['block'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['block']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['block']->value) {
$_smarty_tpl->tpl_vars['block']->_loop = true;
$__foreach_block_0_saved_local_item = $_smarty_tpl->tpl_vars['block'];
?>
	
		<div id="bloc_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
" class="">
	
			<?php if ($_smarty_tpl->tpl_vars['block']->value['title']) {?>
				<h3><?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['block']->value['title'], 'UTF-8', 'HTML-ENTITIES'),((string)$_SESSION['lang']));?>
</h3>
			<?php }?>
	
			<?php echo sbGetShortcode(sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['block']->value['content'], 'UTF-8', 'HTML-ENTITIES'),((string)$_SESSION['lang'])));?>

	
		</div> <!-- bloc_<?php echo $_smarty_tpl->tpl_vars['block']->value['id'];?>
 -->
	
	<?php
$_smarty_tpl->tpl_vars['block'] = $__foreach_block_0_saved_local_item;
}
if ($__foreach_block_0_saved_item) {
$_smarty_tpl->tpl_vars['block'] = $__foreach_block_0_saved_item;
}
?>

<?php }
}
}
