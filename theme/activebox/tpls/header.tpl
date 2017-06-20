<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- metas tags -->
    {insert name="sbGetSeoMetas"}

    <!-- Favicons -->
    <title>{insert name=sbGetPageTitle pti="{$sb_title}" mti="{$sb_pages_title}"} {$sb_site_title|default:""}</title>
	
    <link rel="apple-touch-icon" sizes="180x180" href="{$smarty.const.SB_THEME_URL}images/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="{$smarty.const.SB_THEME_URL}images/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{$smarty.const.SB_THEME_URL}images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{$smarty.const.SB_THEME_URL}images/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{$smarty.const.SB_THEME_URL}images/favicons/favicon-16x16.png">
	
    <!-- Fonts -->
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' type='text/css' media='all' />
    {insert name="sbGetFonts"}
	
    <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/bootstrap.min.css">
    <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/flexslider.css">
    <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/jquery.fancybox.css">
    <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/main.css">
    <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/responsive.css">
    <link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/animate.min.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{$smarty.const.SB_THEME_URL}js/jquery.min.js"><\/script>')</script>
	
    {* --- ---------------------------------------- --- *}
    {* --- Style/JS modifiables pour le site client --- *}
    {* --- ---------------------------------------- --- *}
    <link rel="stylesheet" type="text/css" href="{$smarty.const.SB_THEME_URL}style-custom.css?v={$smarty.now}" media="screen" />
    <script src="{$smarty.const.SB_URL}inc/function.js?v={$smarty.now}"></script>
    {* --- ---------------------------------------- --- *}

    <!-- Headers SBUIADMIN -->
    {insert name="sbGetHeaders"}
	
</head>
<body class="{insert name=sbGetBodyClass th="{$theme_view}" pt="{$sb_pages_title}" ti="{$sb_title}" pid="{$page_id}"} {insert name="sbGetMobileDetect"}">
				