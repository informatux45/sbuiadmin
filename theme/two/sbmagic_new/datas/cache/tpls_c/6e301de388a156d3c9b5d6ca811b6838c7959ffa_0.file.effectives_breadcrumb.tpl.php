<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:40:38
  from "/Applications/MAMP/htdocs/sbmagic_new/datas/modules/effectives/tpls/effectives_breadcrumb.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab169c63e0_73409806',
  'file_dependency' => 
  array (
    '6e301de388a156d3c9b5d6ca811b6838c7959ffa' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/datas/modules/effectives/tpls/effectives_breadcrumb.tpl',
      1 => 1475052176,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857ab169c63e0_73409806 ($_smarty_tpl) {
if (!is_callable('smarty_function_seo')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.seo.php';
?>


<?php if ($_smarty_tpl->tpl_vars['sbeffectives_options']->value['breadcrumb']) {?>

	<style>
		@import url(<?php echo @constant('SB_URL');?>
datas/modules/effectives/inc/style_breadcrumb.css);
	</style>

	<div class="sbeffectives_breadcrumb_header">
		
		<?php if ($_smarty_tpl->tpl_vars['sbeffectives_nav1']->value) {?>
			<span class="sbeffectives_breadcrumb_name"><a href="<?php echo smarty_function_seo(array('url'=>"index.php?p=effectives",'rewrite'=>"effectives"),$_smarty_tpl);?>
"><?php echo @constant('_CMS_EFFECTIVES_S');?>
</a></span>
		<?php } else { ?>
			<span class="sbeffectives_breadcrumb_name"><?php echo @constant('_CMS_EFFECTIVES_S');?>
</span>
		<?php }?>
		    
		<?php if ($_smarty_tpl->tpl_vars['sbeffectives_nav1']->value && !$_smarty_tpl->tpl_vars['sbeffectives_nav2']->value) {?>
			&nbsp;&raquo;&nbsp;<span class="sbeffectives_breadcrumb_name"><?php echo $_smarty_tpl->tpl_vars['sbeffectives_nav1']->value;?>
</span>
		<?php } elseif ($_smarty_tpl->tpl_vars['sbeffectives_nav1']->value && $_smarty_tpl->tpl_vars['sbeffectives_nav2']->value) {?>
			&nbsp;&raquo;&nbsp;<span class="sbeffectives_breadcrumb_name"><a href="<?php ob_start();
echo strtolower(sbRewriteString(preg_replace('!<[^>]*?>!', ' ', sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['sbeffectives_cat_title']->value, 'UTF-8', 'HTML-ENTITIES'),((string)$_SESSION['lang'])))));
$_tmp2=ob_get_clean();
echo smarty_function_seo(array('url'=>"index.php?p=effectives&op=category&id=".((string)$_smarty_tpl->tpl_vars['sbeffectives_item_cat_id']->value),'rewrite'=>"effectives/category/".((string)$_smarty_tpl->tpl_vars['sbeffectives_item_cat_id']->value)."/".$_tmp2),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['sbeffectives_nav1']->value;?>
</a></span>
		<?php }?>
		    
		<?php if ($_smarty_tpl->tpl_vars['sbeffectives_nav2']->value) {?>
			&nbsp;&raquo;&nbsp;<span class="sbeffectives_breadcrumb_name"><?php echo $_smarty_tpl->tpl_vars['sbeffectives_nav2']->value;?>
</span>
		<?php }?>
	</div>

<?php }?>

<?php if ($_smarty_tpl->tpl_vars['sbeffectives_options']->value['title_h1']) {?>
	<h1>
		<a href="<?php echo smarty_function_seo(array('url'=>"index.php?p=effectives",'rewrite'=>"effectives"),$_smarty_tpl);?>
"><?php echo @constant('_CMS_EFFECTIVES_S');?>
</a>
	</h1>
<?php }?>
	
<?php if ($_smarty_tpl->tpl_vars['sbeffectives_options']->value['title_h2']) {?>
	<?php if ($_smarty_tpl->tpl_vars['sbeffectives_item_cat_title']->value) {?>
		<h2>
			<a href="<?php ob_start();
echo strtolower(sbRewriteString(preg_replace('!<[^>]*?>!', ' ', sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['sbeffectives_item_cat_title']->value, 'UTF-8', 'HTML-ENTITIES'),((string)$_SESSION['lang'])))));
$_tmp3=ob_get_clean();
echo smarty_function_seo(array('url'=>"index.php?p=effectives&op=category&id=".((string)$_smarty_tpl->tpl_vars['sbeffectives_item_cat_id']->value),'rewrite'=>"effectives/category/".((string)$_smarty_tpl->tpl_vars['sbeffectives_item_cat_id']->value)."/".$_tmp3),$_smarty_tpl);?>
">
				<?php echo $_smarty_tpl->tpl_vars['sbeffectives_item_cat_title']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['sbeffectives_item_cat_subtitle']->value) {?>- <?php echo $_smarty_tpl->tpl_vars['sbeffectives_item_cat_subtitle']->value;
}?>
			</a>
		</h2>
	<?php } elseif ($_smarty_tpl->tpl_vars['sbeffectives_cat_title']->value) {?>
		<h2>
			<?php echo $_smarty_tpl->tpl_vars['sbeffectives_cat_title']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['sbeffectives_item_cat_subtitle']->value) {?>- <?php echo $_smarty_tpl->tpl_vars['sbeffectives_item_cat_subtitle']->value;
}?>
		</h2>
	<?php }
}?>

<div class="sbeffectives-clear-both"> </div>
<?php }
}
