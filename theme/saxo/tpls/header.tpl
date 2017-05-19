<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- metas tags -->
	{insert name="sbGetSeoMetas"}
	<!-- Title -->
	<title>{insert name=sbGetPageTitle pti="{$sb_title}" mti="{$sb_pages_title}"} {$sb_site_title|default:""}</title>
	<!-- FONTs -->
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' type='text/css' media='all' />
	{insert name="sbGetFonts"}
	<!-- CSS -->
	<link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/bootstrap.css">
	<link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/bootstrap-responsive.css">
	<link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/prettyPhoto.css" />
	<link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/flexslider.css" />
	<link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/custom-styles.css">
	
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<link rel="stylesheet" href="{$smarty.const.SB_THEME_URL}css/style-ie.css"/>
	<![endif]--> 
	
	<!-- Favicons -->
	<link rel="shortcut icon" href="{$smarty.const.SB_THEME_URL}img/favicon.ico">
	<link rel="apple-touch-icon" href="{$smarty.const.SB_THEME_URL}img/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="{$smarty.const.SB_THEME_URL}img/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="{$smarty.const.SB_THEME_URL}img/apple-touch-icon-114x114.png">
	
	<!-- JS -->
	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script src="{$smarty.const.SB_THEME_URL}js/bootstrap.js"></script>
	<script src="{$smarty.const.SB_THEME_URL}js/jquery.prettyPhoto.js"></script>
	<script src="{$smarty.const.SB_THEME_URL}js/jquery.flexslider.js"></script>
	<script src="{$smarty.const.SB_THEME_URL}js/jquery.custom.js"></script>

	{* --- ---------------------------------------- --- *}
	{* --- Style/JS modifiables pour le site client --- *}
	{* --- ---------------------------------------- --- *}
	<link rel="stylesheet" type="text/css" href="{$smarty.const.SB_THEME_URL}style-custom.css?v={$smarty.now}" media="screen" />
	<script src="{$smarty.const.SB_URL}inc/function.js?v={$smarty.now}"></script>
	{* --- ---------------------------------------- --- *}

	<!-- Headers SBUIADMIN -->	
	{insert name="sbGetHeaders"}

</head>

<body class="home {insert name=sbGetBodyClass th="{$theme_view}" pt="{$sb_pages_title}" ti="{$sb_title}" pid="{$page_id}"} {insert name="sbGetMobileDetect"}">
    <!-- Color Bars (above header)-->
	<div class="color-bar-1"></div>
    <div class="color-bar-2 color-bg"></div>
	
	<div class="container">
				