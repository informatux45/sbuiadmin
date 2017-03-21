<?php
/* Smarty version 3.1.29, created on 2016-12-19 10:41:58
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/three/tpls/header.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5857ab6643c027_65701982',
  'file_dependency' => 
  array (
    '671b54a43db20c60492e5f35ddca0a02fda1ad2c' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/three/tpls/header.tpl',
      1 => 1479981175,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5857ab6643c027_65701982 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ie" dir="ltr" lang="fr-FR">
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ie" dir="ltr" lang="fr-FR">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="ie" dir="ltr" lang="fr-FR">
<![endif]-->
<!--[if IE 9]>
<html id="ie9" class="ie" dir="ltr" lang="fr-FR">
<![endif]-->
<!--[if gt IE 9]>
<html class="ie" dir="ltr" lang="fr-FR">
<![endif]-->
<!--[if !IE]>
<html dir="ltr" lang="fr-FR">
<![endif]-->
    
    <!-- START HEAD -->
    <head>
        
        <meta charset="UTF-8" />
        <!-- this line will appear only if the website is visited with an iPad -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.2, user-scalable=yes" />
		
		<meta name="keywords" content="<?php echo sbGetSeo($_smarty_tpl->tpl_vars['sb_seo_keywords']->value,"keywords");?>
">        
		<meta name="description" content="<?php echo sbGetSeo($_smarty_tpl->tpl_vars['sb_seo_description']->value,"description");?>
">
        
        <title><?php echo insert_sbGetPageTitle(array('pti' => ((string)$_smarty_tpl->tpl_vars['sb_title']->value), 'mti' => ((string)$_smarty_tpl->tpl_vars['sb_pages_title']->value)),$_smarty_tpl);?>
 <?php echo (($tmp = @$_smarty_tpl->tpl_vars['sb_site_title']->value)===null||$tmp==='' ? '' : $tmp);?>
</title>
        
        <!-- [favicon] begin -->
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo @constant('SB_THEME_URL');?>
favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="<?php echo @constant('SB_THEME_URL');?>
favicons/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="<?php echo @constant('SB_THEME_URL');?>
favicons/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="<?php echo @constant('SB_THEME_URL');?>
favicons/manifest.json">
		<link rel="mask-icon" href="<?php echo @constant('SB_THEME_URL');?>
favicons/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="theme-color" content="#ffffff">


        <!-- [favicon] end -->
        
        <!-- CSSs -->
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo @constant('SB_THEME_URL');?>
css/reset.css" /> <!-- RESET STYLESHEET -->
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo @constant('SB_THEME_URL');?>
style.css" /> <!-- MAIN THEME STYLESHEET -->
        <link rel="stylesheet" id="max-width-1024-css" href="<?php echo @constant('SB_THEME_URL');?>
css/max-width-1024.css" type="text/css" media="screen and (max-width: 1240px)" />
        <link rel="stylesheet" id="max-width-768-css" href="<?php echo @constant('SB_THEME_URL');?>
css/max-width-768.css" type="text/css" media="screen and (max-width: 987px)" />
        <link rel="stylesheet" id="max-width-480-css" href="<?php echo @constant('SB_THEME_URL');?>
css/max-width-480.css" type="text/css" media="screen and (max-width: 480px)" />
        <link rel="stylesheet" id="max-width-320-css" href="<?php echo @constant('SB_THEME_URL');?>
css/max-width-320.css" type="text/css" media="screen and (max-width: 320px)" />
        
        <!-- CSSs Plugin -->
        <link rel="stylesheet" id="thickbox-css" href="<?php echo @constant('SB_THEME_URL');?>
css/thickbox.css" type="text/css" media="all" />
        <link rel="stylesheet" id="styles-minified-css" href="<?php echo @constant('SB_THEME_URL');?>
css/style-minifield.css" type="text/css" media="all" />
        <link rel="stylesheet" id="buttons" href="<?php echo @constant('SB_THEME_URL');?>
css/buttons.css" type="text/css" media="all" />
        <link rel="stylesheet" id="cache-custom-css" href="<?php echo @constant('SB_THEME_URL');?>
css/cache-custom.css" type="text/css" media="all" />
	    
        <!-- FONTs -->
        <?php echo insert_sbGetFonts(array(),$_smarty_tpl);?>

        <link rel='stylesheet' href='<?php echo @constant('SB_THEME_URL');?>
css/font-awesome.css' type='text/css' media='all' />
        
        <!-- JAVASCRIPTs -->
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/comment-reply.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.quicksand.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.tipsy.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.prettyPhoto.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.cycle.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.anythingslider.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.eislideshow.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.easing.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.flexslider-min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.aw-showcase.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/layerslider.kreaturamedia.jquery-min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/shortcodes.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.colorbox-min.js"><?php echo '</script'; ?>
> 
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.tweetable.js"><?php echo '</script'; ?>
>

		
		
		
        <link rel="stylesheet" type="text/css" href="<?php echo @constant('SB_THEME_URL');?>
style-custom.css?v=<?php echo time();?>
" media="screen" />		
		
		<?php echo '<script'; ?>
 src="<?php echo @constant('SB_URL');?>
inc/function.js?v=<?php echo time();?>
"><?php echo '</script'; ?>
>
		
		<?php echo insert_sbGetHeaders(array(),$_smarty_tpl);?>

        
    </head>
    <!-- END HEAD -->
    
    <!-- START BODY -->
    <body class="no_js responsive <?php echo (($tmp = @$_smarty_tpl->tpl_vars['viewtype']->value)===null||$tmp==='' ? "stretched" : $tmp);?>
 <?php echo insert_sbGetBodyClass(array('th' => ((string)$_smarty_tpl->tpl_vars['theme_view']->value), 'pt' => ((string)$_smarty_tpl->tpl_vars['sb_pages_title']->value), 'ti' => ((string)$_smarty_tpl->tpl_vars['sb_title']->value), 'pid' => ((string)$_smarty_tpl->tpl_vars['page_id']->value)),$_smarty_tpl);?>
 <?php echo insert_sbGetMobileDetect(array(),$_smarty_tpl);?>
">
        
        <!-- START BG SHADOW -->
        <div class="bg-shadow">
            
            <!-- START WRAPPER -->
            <div id="wrapper" class="group">
				<?php }
}
