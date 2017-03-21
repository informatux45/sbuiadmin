<?php
/* Smarty version 3.1.29, created on 2016-12-09 16:10:54
  from "/Applications/MAMP/htdocs/sbmagic_new/theme/one/tpls/header.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_584ac97e7b1cc3_40091979',
  'file_dependency' => 
  array (
    'b074f500a2ffc7cd0f28c02b9b4b22cc9125e80a' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/theme/one/tpls/header.tpl',
      1 => 1479981157,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_584ac97e7b1cc3_40091979 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ie" dir="ltr" lang="en-US">
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ie" dir="ltr" lang="en-US">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="ie" dir="ltr" lang="en-US">
<![endif]-->
<!--[if IE 9]>
<html id="ie9" class="ie" dir="ltr" lang="en-US">
<![endif]-->
<!--[if gt IE 9]>
<html class="ie" dir="ltr" lang="en-US">
<![endif]-->
<!--[if !IE]>
<html dir="ltr" lang="en-US">
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
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo @constant('SB_THEME_URL');?>
images/favicon.ico" />
        <link rel="icon" type="image/x-icon" href="<?php echo @constant('SB_THEME_URL');?>
images/favicon.ico" />
        <!-- Touch icons more info: http://mathiasbynens.be/notes/touch-icons -->
        <!-- For iPad3 with retina display: -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo @constant('SB_THEME_URL');?>
apple-touch-icon-144x.png" />
        <!-- For first- and second-generation iPad: -->
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo @constant('SB_THEME_URL');?>
apple-touch-icon-114x.png" />
        <!-- For first- and second-generation iPad: -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo @constant('SB_THEME_URL');?>
apple-touch-icon-72x.png" />
        <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
        <link rel="apple-touch-icon-precomposed" href="<?php echo @constant('SB_THEME_URL');?>
apple-touch-icon-57x.png" />
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
        <link rel='stylesheet' href='<?php echo @constant('SB_THEME_URL');?>
css/font-awesome.css' type='text/css' media='all' />
		 <?php echo insert_sbGetFonts(array(),$_smarty_tpl);?>

        
        <!-- JAVASCRIPTs -->
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.quicksand.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('SB_THEME_URL');?>
js/jquery.aw-showcase.js"><?php echo '</script'; ?>
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
    <body class="no_js responsive stretched <?php echo insert_sbGetBodyClass(array('th' => ((string)$_smarty_tpl->tpl_vars['theme_view']->value), 'pt' => ((string)$_smarty_tpl->tpl_vars['sb_pages_title']->value), 'ti' => ((string)$_smarty_tpl->tpl_vars['sb_title']->value)),$_smarty_tpl);?>
 <?php echo insert_sbGetMobileDetect(array(),$_smarty_tpl);?>
">
        
        <!-- START BG SHADOW -->
        <div class="bg-shadow">
            
            <!-- START WRAPPER -->
            <div id="wrapper" class="group">
				<?php }
}
