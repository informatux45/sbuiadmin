<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:40:24
  from "/Applications/MAMP/htdocs/sbmagic_new/datas/modules/graduates/tpls/graduates_index.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab0837c9f4_28273256',
  'file_dependency' => 
  array (
    '073516f33164f365c46b6bed46f233918ce41db2' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/datas/modules/graduates/tpls/graduates_index.tpl',
      1 => 1477316989,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:graduates/tpls/graduates_header.tpl' => 1,
    'file:graduates/tpls/graduates_breadcrumb.tpl' => 1,
  ),
),false)) {
function content_5857ab0837c9f4_28273256 ($_smarty_tpl) {
if (!is_callable('smarty_function_seo')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.seo.php';
?>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:graduates/tpls/graduates_header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:graduates/tpls/graduates_breadcrumb.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
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
" class="sbgraduates-allcats" onclick="javascript:window.location='<?php ob_start();
echo strtolower(sbRewriteString(preg_replace('!<[^>]*?>!', ' ', sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['cat']->value['title'], 'UTF-8', 'HTML-ENTITIES'),((string)$_SESSION['lang'])))));
$_tmp1=ob_get_clean();
echo smarty_function_seo(array('url'=>"index.php?p=graduates&op=category&id=".((string)$_smarty_tpl->tpl_vars['cat']->value['id']),'rewrite'=>"graduates/category/".((string)$_smarty_tpl->tpl_vars['cat']->value['id'])."/".$_tmp1),$_smarty_tpl);?>
'" style="<?php if ($_smarty_tpl->tpl_vars['cat']->value['photo']) {?>background: url(<?php echo @constant('_AM_MEDIAS_URL');?>
/<?php echo $_smarty_tpl->tpl_vars['cat']->value['photo'];?>
) no-repeat center center;<?php }?>"></div>

<?php
$_smarty_tpl->tpl_vars['cat'] = $__foreach_cat_0_saved_local_item;
}
if (!$_smarty_tpl->tpl_vars['cat']->_loop) {
?>

	<?php echo @constant('_CMS_GRADUATES_NOCATEGORIES');?>


<?php
}
if ($__foreach_cat_0_saved_item) {
$_smarty_tpl->tpl_vars['cat'] = $__foreach_cat_0_saved_item;
}
}
}
