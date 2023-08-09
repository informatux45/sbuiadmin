<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SBUIADMIN User Interface Administration | Powered by Smarty {$smarty.version} | Design by SBadmin 2 Bootstrap">
    <meta name="author" content="INFORMATUX">

	<link rel="apple-touch-icon" sizes="180x180" href="img/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="img/favicons/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="img/favicons/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="img/favicons/manifest.json">
	<link rel="mask-icon" href="img/favicons/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#ffffff">

    <title>{$smarty.const._AM_SITE_CUSTOMER_NAME}</title>

    <!-- Bootstrap Core CSS -->
    <link href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="assets/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    {if $page != 'login'}<link href="assets/dist/css/sb-admin-2.css" rel="stylesheet">{/if}

    <!-- Font Awesome latest -->
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' type='text/css' media='all' />
	
	<!-- OutdatedBrowser -->
	<link rel="stylesheet" href="inc/plugins/outdatedbrowser/outdatedbrowser.min.css">
	
	<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
	<link rel="stylesheet" href="assets/dist/css/jquery.fileupload.css">

	<!-- JS customs scripts -->
	<script src="assets/dist/js/sb-custom.js"></script>
	
    <!-- Custom Admin CSS -->
    {if $page != 'login'}<link href="assets/dist/css/sb-admin-custom.css" rel="stylesheet">{/if}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
    <!-- jQuery -->
    <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
	
	{if $page == 'login'}
		<!-- CSS to style background login page with pattern -->
		{*<link rel="stylesheet" href="assets/dist/css/pattern{$sb_random_bg}.css">*}
		{*<script type="text/javascript" charset="utf-8" src="assets/dist/js/jquery.tubular.1.0.js"></script>*}
		<link href="assets/dist/css/sb-admin-custom-login.css" rel="stylesheet">
	{/if}

</head>

<body class="{if $page == 'login'}login-pattern{/if}{if isset($smarty.get.p) && $smarty.get.p == 'transfert'} margin10{/if}">
	
    <div id="outdated">
        <h6>Your browser is out-of-date!</h6>
        <p>Update your browser to view SBUIADMIN Software correctly. <a id="btnUpdateBrowser" href="http://outdatedbrowser.com/">Update my browser now </a></p>
        <p class="last"><a href="#" id="btnCloseUpdateBrowser" title="Close">&times;</a></p>
    </div> <!-- OutdatedBrowser -->
	
    {if $page != 'login'}<div id="wrapper">{/if}