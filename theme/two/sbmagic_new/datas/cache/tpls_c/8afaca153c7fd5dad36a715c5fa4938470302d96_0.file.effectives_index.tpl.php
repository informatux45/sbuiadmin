<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:40:38
  from "/Applications/MAMP/htdocs/sbmagic_new/datas/modules/effectives/tpls/effectives_index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab16936736_95358564',
  'file_dependency' => 
  array (
    '8afaca153c7fd5dad36a715c5fa4938470302d96' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/datas/modules/effectives/tpls/effectives_index.tpl',
      1 => 1474619512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:effectives/tpls/effectives_header.tpl' => 1,
    'file:effectives/tpls/effectives_breadcrumb.tpl' => 1,
  ),
),false)) {
function content_5857ab16936736_95358564 ($_smarty_tpl) {
if (!is_callable('smarty_function_seo')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.seo.php';
?>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:effectives/tpls/effectives_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:effectives/tpls/effectives_breadcrumb.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php
$_from = $_smarty_tpl->tpl_vars['categories']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_cat_0_saved_item = isset($_smarty_tpl->tpl_vars['cat']) ? $_smarty_tpl->tpl_vars['cat'] : false;
$_smarty_tpl->tpl_vars['cat'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['cat']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['cat']->value) {
$_smarty_tpl->tpl_vars['cat']->_loop = true;
$__foreach_cat_0_saved_local_item = $_smarty_tpl->tpl_vars['cat'];
?>

	<div data-content="<?php echo sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['cat']->value['title'], 'UTF-8', 'HTML-ENTITIES'),((string)$_SESSION['lang']));?>
" class="sbeffectives-allcats" onclick="javascript:window.location='<?php ob_start();
echo strtolower(sbRewriteString(preg_replace('!<[^>]*?>!', ' ', sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['cat']->value['title'], 'UTF-8', 'HTML-ENTITIES'),((string)$_SESSION['lang'])))));
$_tmp1=ob_get_clean();
echo smarty_function_seo(array('url'=>"index.php?p=effectives&op=category&id=".((string)$_smarty_tpl->tpl_vars['cat']->value['id']),'rewrite'=>"effectives/category/".((string)$_smarty_tpl->tpl_vars['cat']->value['id'])."/".$_tmp1),$_smarty_tpl);?>
'" style="<?php if ($_smarty_tpl->tpl_vars['cat']->value['photo']) {?>background: url(<?php echo @constant('_AM_MEDIAS_URL');?>
/<?php echo $_smarty_tpl->tpl_vars['cat']->value['photo'];?>
) no-repeat center center;<?php }?>"></div>

<?php
$_smarty_tpl->tpl_vars['cat'] = $__foreach_cat_0_saved_local_item;
}
if (!$_smarty_tpl->tpl_vars['cat']->_loop) {
?>

	<?php echo @constant('_CMS_EFFECTIVES_NOCATEGORIES');?>


<?php
}
if ($__foreach_cat_0_saved_item) {
$_smarty_tpl->tpl_vars['cat'] = $__foreach_cat_0_saved_item;
}
}
}
