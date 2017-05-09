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
		
		<!-- metas tags -->
		{insert name="sbGetSeoMetas"}
        
		<!-- Title -->
        <title>{insert name=sbGetPageTitle pti="{$sb_title}" mti="{$sb_pages_title}"} {$sb_site_title|default:""}</title>
        
        <!-- [favicon] begin -->
		<link rel="apple-touch-icon" sizes="180x180" href="{$smarty.const.SB_THEME_URL}favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="{$smarty.const.SB_THEME_URL}favicons/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="{$smarty.const.SB_THEME_URL}favicons/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="{$smarty.const.SB_THEME_URL}favicons/manifest.json">
		<link rel="mask-icon" href="{$smarty.const.SB_THEME_URL}favicons/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="theme-color" content="#ffffff">
        <!-- [favicon] end -->
        
        <!-- CSSs -->
        <link rel="stylesheet" type="text/css" media="all" href="{$smarty.const.SB_THEME_URL}css/reset.css" /> <!-- RESET STYLESHEET -->
        <link rel="stylesheet" type="text/css" media="all" href="{$smarty.const.SB_THEME_URL}style.css" /> <!-- MAIN THEME STYLESHEET -->
        <link rel="stylesheet" id="max-width-1024-css" href="{$smarty.const.SB_THEME_URL}css/max-width-1024.css" type="text/css" media="screen and (max-width: 1240px)" />
        <link rel="stylesheet" id="max-width-768-css" href="{$smarty.const.SB_THEME_URL}css/max-width-768.css" type="text/css" media="screen and (max-width: 987px)" />
        <link rel="stylesheet" id="max-width-480-css" href="{$smarty.const.SB_THEME_URL}css/max-width-480.css" type="text/css" media="screen and (max-width: 480px)" />
        <link rel="stylesheet" id="max-width-320-css" href="{$smarty.const.SB_THEME_URL}css/max-width-320.css" type="text/css" media="screen and (max-width: 320px)" />
		<link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/bootstrap.min.css" />
        
        <!-- CSSs Plugin -->
        <link rel="stylesheet" id="thickbox-css" href="{$smarty.const.SB_THEME_URL}css/thickbox.css" type="text/css" media="all" />
        <link rel="stylesheet" id="styles-minified-css" href="{$smarty.const.SB_THEME_URL}css/style-minifield.css" type="text/css" media="all" />
        <link rel="stylesheet" id="buttons" href="{$smarty.const.SB_THEME_URL}css/buttons.css" type="text/css" media="all" />
        <link rel="stylesheet" id="cache-custom-css" href="{$smarty.const.SB_THEME_URL}css/cache-custom.css" type="text/css" media="all" />
	    
        <!-- FONTs -->
		<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' type='text/css' media='all' />
        {insert name="sbGetFonts"}
        
        <!-- JAVASCRIPTs -->
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/comment-reply.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.quicksand.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.tipsy.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.prettyPhoto.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.cycle.min.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.anythingslider.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.eislideshow.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.easing.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.flexslider-min.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.aw-showcase.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/layerslider.kreaturamedia.jquery-min.js"></script>
        <script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/shortcodes.js"></script>
		<script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.colorbox-min.js"></script> 
		<script type="text/javascript" src="{$smarty.const.SB_THEME_URL}js/jquery.tweetable.js"></script>

		{* --- ------------------------------------ --- *}
		{* --- Style modifiable pour le site client --- *}
		{* --- ------------------------------------ --- *}
        <link rel="stylesheet" type="text/css" href="{$smarty.const.SB_THEME_URL}style-custom.css?v={$smarty.now}" media="screen" />		
		{* --- ------------------------------------ --- *}
		<script src="{$smarty.const.SB_URL}inc/function.js?v={$smarty.now}"></script>
		
		{insert name="sbGetHeaders"}
        
    </head>
    <!-- END HEAD -->
    
    <!-- START BODY -->
    <body class="no_js responsive {$viewtype|default:"stretched"} {insert name=sbGetBodyClass th="{$theme_view}" pt="{$sb_pages_title}" ti="{$sb_title}" pid="{$page_id}"} {insert name="sbGetMobileDetect"}">
        
        <!-- START BG SHADOW -->
        <div class="bg-shadow">
            
            <!-- START WRAPPER -->
            <div id="wrapper" class="group">
				