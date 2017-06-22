<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

		<!-- metas tags -->
		{insert name="sbGetSeoMetas"}

		<!-- Title -->
        <title>{insert name=sbGetPageTitle pti="{$sb_title}" mti="{$sb_pages_title}"} {$sb_site_title|default:""}</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Favicons -->
		<link rel="apple-touch-icon" sizes="152x152" href="{$smarty.const.SB_THEME_URL}img/favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="{$smarty.const.SB_THEME_URL}img/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="{$smarty.const.SB_THEME_URL}img/favicons/favicon-16x16.png">
		<link rel="manifest" href="{$smarty.const.SB_THEME_URL}img/favicons/manifest.json">
		<link rel="mask-icon" href="{$smarty.const.SB_THEME_URL}img/favicons/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="theme-color" content="#ffffff">
		
		<!-- Scripts -->
        <script src="{$smarty.const.SB_THEME_URL}js/vendor/jquery-1.10.2.min.js"></script>
        
        <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/normalize.css">
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' type='text/css' media='all' />
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