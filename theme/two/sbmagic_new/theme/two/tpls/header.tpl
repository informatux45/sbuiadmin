<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		
        <title>{insert name=sbGetPageTitle pti="{$sb_title}" mti="{$sb_pages_title}"} {$sb_site_title|default:""}</title>

		<meta name="keywords" content="{$sb_seo_keywords|@sbGetSeo:"keywords"}">        
		<meta name="description" content="{$sb_seo_description|@sbGetSeo:"description"}">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/normalize.css">
        <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/font-awesome.css">
        <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/bootstrap.min.css">
        <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/templatemo-style.css">
		<link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}dist/css/lightbox.min.css">
		
		{* --- ------------------------------------ --- *}
		{* --- Style modifiable pour le site client --- *}
		{* --- ------------------------------------ --- *}
        <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}style-custom.css?v={$smarty.now}">		
		{* --- ------------------------------------ --- *}
		<script src="{$smarty.const.SB_URL}inc/function.js?v={$smarty.now}"></script>
		
        <script src="{$smarty.const.SB_THEME_URL}js/vendor/modernizr-2.6.2.min.js"></script>
		
		{insert name="sbGetHeaders"}
		
    </head>
    <body class="{insert name=sbGetBodyClass th="{$theme_view}" pt="{$sb_pages_title}" ti="{$sb_title}" pid="{$page_id}"} {insert name="sbGetMobileDetect"}">
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->