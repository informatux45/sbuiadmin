<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:40:24
  from "/Applications/MAMP/htdocs/sbmagic_new/datas/modules/graduates/tpls/graduates_breadcrumb.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab084bda45_70394739',
  'file_dependency' => 
  array (
    'd648e378fb3316f238d0ef65c63efb619a001662' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/datas/modules/graduates/tpls/graduates_breadcrumb.tpl',
      1 => 1477317514,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857ab084bda45_70394739 ($_smarty_tpl) {
if (!is_callable('smarty_function_seo')) require_once '/Applications/MAMP/htdocs/sbmagic_new/administration/core/plugins/function.seo.php';
?>


<?php if ($_smarty_tpl->tpl_vars['sbgraduates_options']->value['breadcrumb']) {?>

	<style>
		@import url(<?php echo @constant('SB_URL');?>
datas/modules/graduates/inc/style_breadcrumb.css);
	</style>

	<div class="sbgraduates_breadcrumb_header">
		
		<?php if ($_smarty_tpl->tpl_vars['sbgraduates_nav1']->value) {?>
			<span class="sbgraduates_breadcrumb_name"><a href="<?php echo smarty_function_seo(array('url'=>"index.php?p=graduates",'rewrite'=>"graduates"),$_smarty_tpl);?>
"><?php echo @constant('_CMS_GRADUATES_S');?>
</a></span>
		<?php } else { ?>
			<span class="sbgraduates_breadcrumb_name"><?php echo @constant('_CMS_GRADUATES_S');?>
</span>
		<?php }?>
		    
		<?php if ($_smarty_tpl->tpl_vars['sbgraduates_nav1']->value && !$_smarty_tpl->tpl_vars['sbgraduates_nav2']->value) {?>
			&nbsp;&raquo;&nbsp;<span class="sbgraduates_breadcrumb_name"><?php echo $_smarty_tpl->tpl_vars['sbgraduates_nav1']->value;?>
</span>
		<?php } elseif ($_smarty_tpl->tpl_vars['sbgraduates_nav1']->value && $_smarty_tpl->tpl_vars['sbgraduates_nav2']->value) {?>
			&nbsp;&raquo;&nbsp;<span class="sbgraduates_breadcrumb_name"><a href="<?php ob_start();
echo strtolower(sbRewriteString(preg_replace('!<[^>]*?>!', ' ', sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['sbgraduates_cat_title']->value, 'UTF-8', 'HTML-ENTITIES'),((string)$_SESSION['lang'])))));
$_tmp2=ob_get_clean();
echo smarty_function_seo(array('url'=>"index.php?p=graduates&op=category&id=".((string)$_smarty_tpl->tpl_vars['sbgraduates_item_cat_id']->value),'rewrite'=>"graduates/category/".((string)$_smarty_tpl->tpl_vars['sbgraduates_item_cat_id']->value)."/".$_tmp2),$_smarty_tpl);?>
"><?php echo $_smarty_tpl->tpl_vars['sbgraduates_nav1']->value;?>
</a></span>
		<?php }?>
		    
		<?php if ($_smarty_tpl->tpl_vars['sbgraduates_nav2']->value) {?>
			&nbsp;&raquo;&nbsp;<span class="sbgraduates_breadcrumb_name"><?php echo $_smarty_tpl->tpl_vars['sbgraduates_nav2']->value;?>
</span>
		<?php }?>
	</div>

<?php }?>

<?php if ($_smarty_tpl->tpl_vars['sbgraduates_options']->value['title_h1']) {?>
	<h1>
		<a href="<?php echo smarty_function_seo(array('url'=>"index.php?p=graduates",'rewrite'=>"graduates"),$_smarty_tpl);?>
"><?php echo @constant('_CMS_GRADUATES_S');?>
</a>
	</h1>
<?php }?>
	
<?php if ($_smarty_tpl->tpl_vars['sbgraduates_options']->value['title_h2']) {?>
	<?php if ($_smarty_tpl->tpl_vars['sbgraduates_item_cat_title']->value) {?>
		<h2>
			<a href="<?php ob_start();
echo strtolower(sbRewriteString(preg_replace('!<[^>]*?>!', ' ', sbDisplayLang(mb_convert_encoding($_smarty_tpl->tpl_vars['sbgraduates_item_cat_title']->value, 'UTF-8', 'HTML-ENTITIES'),((string)$_SESSION['lang'])))));
$_tmp3=ob_get_clean();
echo smarty_function_seo(array('url'=>"index.php?p=graduates&op=category&id=".((string)$_smarty_tpl->tpl_vars['sbgraduates_item_cat_id']->value),'rewrite'=>"graduates/category/".((string)$_smarty_tpl->tpl_vars['sbgraduates_item_cat_id']->value)."/".$_tmp3),$_smarty_tpl);?>
">
				<?php echo $_smarty_tpl->tpl_vars['sbgraduates_item_cat_title']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['sbgraduates_item_cat_subtitle']->value) {?>- <?php echo $_smarty_tpl->tpl_vars['sbgraduates_item_cat_subtitle']->value;
}?>
			</a>
		</h2>
	<?php } elseif ($_smarty_tpl->tpl_vars['sbgraduates_cat_title']->value) {?>
		<h2>
			<?php echo $_smarty_tpl->tpl_vars['sbgraduates_cat_title']->value;?>
 <?php if ($_smarty_tpl->tpl_vars['sbgraduates_item_cat_subtitle']->value) {?>- <?php echo $_smarty_tpl->tpl_vars['sbgraduates_item_cat_subtitle']->value;
}?>
		</h2>
	<?php }
}?>

<div class="sbgraduates-clear-both"> </div>
<?php }
}
