<!DOCTYPE HTML>
<html>
	<head>
		<!-- Title -->
		<title>{insert name=sbGetPageTitle pti="{$sb_title}" mti="{$sb_pages_title}"} {$sb_site_title|default:""}</title>
		
		<!-- metas tags -->
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		{insert name="sbGetSeoMetas"}
		
		<!-- Favicons -->
		<link rel="apple-touch-icon" sizes="152x152" href="{$smarty.const.SB_THEME_URL}images/favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="{$smarty.const.SB_THEME_URL}images/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="{$smarty.const.SB_THEME_URL}images/favicons/favicon-16x16.png">
		<link rel="manifest" href="{$smarty.const.SB_THEME_URL}images/favicons/manifest.json">
		<link rel="mask-icon" href="{$smarty.const.SB_THEME_URL}images/favicons/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="theme-color" content="#ffffff">
		
		<!--[if lte IE 8]><script src="{$smarty.const.SB_THEME_URL}assets/js/ie/html5shiv.js"></script><![endif]-->
		
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}assets/css/ie8.css" /><![endif]-->
		
		<!-- Scripts -->
		<script src="{$smarty.const.SB_THEME_URL}assets/js/jquery.min.js"></script>
		
		{* --- ------------------------------------ --- *}
		{* --- Style modifiable pour le site client --- *}
		{* --- ------------------------------------ --- *}
        <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}style-custom.css?v={$smarty.now}">		
		{* --- ------------------------------------ --- *}
		<script src="{$smarty.const.SB_URL}inc/function.js?v={$smarty.now}"></script>
		
		{insert name="sbGetHeaders"}
		
	</head>
	<body class="{insert name=sbGetBodyClass th="{$theme_view}" pt="{$sb_pages_title}" ti="{$sb_title}" pid="{$page_id}"} {insert name="sbGetMobileDetect"}">
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

		<!-- #Wrapper -->
			<div {insert name=sbGetSectionClassId class="" evenid="wrapper" op="`$smarty.get.op`" page="`$page_id`" id="`$smarty.get.id`" ti="`$sb_title`"}>