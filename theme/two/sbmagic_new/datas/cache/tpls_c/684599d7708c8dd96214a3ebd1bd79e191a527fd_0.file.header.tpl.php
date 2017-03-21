<?php
/* Smarty version 3.1.29, created on 2016-11-29 15:32:53
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/two/tpls/header.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_583d91951a8fc2_96889270',
  'file_dependency' => 
  array (
    '684599d7708c8dd96214a3ebd1bd79e191a527fd' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/two/tpls/header.tpl',
      1 => 1479981190,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_583d91951a8fc2_96889270 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		
        <title><?php echo insert_sbGetPageTitle(array('pti' => ((string)$_smarty_tpl->tpl_vars['sb_title']->value), 'mti' => ((string)$_smarty_tpl->tpl_vars['sb_pages_title']->value)),$_smarty_tpl);?>
 <?php echo (($tmp = @$_smarty_tpl->tpl_vars['sb_site_title']->value)===null||$tmp==='' ? '' : $tmp);?>
</title>

		<meta name="keywords" content="<?php echo sbGetSeo($_smarty_tpl->tpl_vars['sb_seo_keywords']->value,"keywords");?>
">        
		<meta name="description" content="<?php echo sbGetSeo($_smarty_tpl->tpl_vars['sb_seo_description']->value,"description");?>
">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="<?php echo @constant('SB_THEME_URL');?>
css/normalize.css">
        <link rel="stylesheet" href="<?php echo @constant('SB_THEME_URL');?>
css/font-awesome.css">
        <link rel="stylesheet" href="<?php echo @constant('SB_THEME_URL');?>
css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo @constant('SB_THEME_URL');?>
css/templatemo-style.css">
		<link rel="stylesheet" href="<?php echo @constant('SB_THEME_URL');?>
dist/css/lightbox.min.css">
		
		
		
		
        <link rel="stylesheet" href="<?php echo @constant('SB_THEME_URL');?>
style-custom.css?v=<?php echo time();?>
">		
		
		<?php echo '<script'; ?>
 src="<?php echo @constant('SB_URL');?>
inc/function.js?v=<?php echo time();?>
"><?php echo '</script'; ?>
>
		
        <?php echo '<script'; ?>
 src="<?php echo @constant('SB_THEME_URL');?>
js/vendor/modernizr-2.6.2.min.js"><?php echo '</script'; ?>
>
		
		<?php echo insert_sbGetHeaders(array(),$_smarty_tpl);?>

		
    </head>
    <body class="<?php echo insert_sbGetBodyClass(array('th' => ((string)$_smarty_tpl->tpl_vars['theme_view']->value), 'pt' => ((string)$_smarty_tpl->tpl_vars['sb_pages_title']->value), 'ti' => ((string)$_smarty_tpl->tpl_vars['sb_title']->value), 'pid' => ((string)$_smarty_tpl->tpl_vars['page_id']->value)),$_smarty_tpl);?>
 <?php echo insert_sbGetMobileDetect(array(),$_smarty_tpl);?>
">
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]--><?php }
}
