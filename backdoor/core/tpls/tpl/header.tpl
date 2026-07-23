<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SBUIADMIN User Interface Administration {$smarty.const._AM_START_VERSION} | Powered by Smarty {$smarty.version} | Design by Adminator">
    <meta name="author" content="INFORMATUX">

	<link rel="apple-touch-icon" sizes="180x180" href="img/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="img/favicons/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="img/favicons/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="img/favicons/manifest.json">
	<link rel="mask-icon" href="img/favicons/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#ffffff">

    <title>{$smarty.const._AM_SITE_CUSTOMER_NAME}</title>

    <!-- Adminator dark-mode bootstrap: must run before first paint to avoid a flash of the wrong theme -->
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('dash26-theme');
                var theme = stored || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>

    <!-- Adminator theme (self-hosted fonts + compiled CSS bundle) -->
    <link href="assets/adminator/fonts/fonts.css" rel="stylesheet">
    <link href="assets/adminator/style.css" rel="stylesheet">

    <!-- Font Awesome (self-hosted, no CDN) — used for icons throughout the app -->
    <link rel="stylesheet" href="assets/bower_components/font-awesome/css/font-awesome.min.css">

    <!-- Adminator theme extension: grid gap-fills, third-party widget dark-mode skinning,
         and small custom classes ported from the removed sb-admin-2/sb-admin-custom stack -->
    <link href="assets/adminator/bridge.css" rel="stylesheet">

	<!-- OutdatedBrowser -->
	<link rel="stylesheet" href="inc/plugins/outdatedbrowser/outdatedbrowser.min.css">

	<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
	<link rel="stylesheet" href="assets/dist/css/jquery.fileupload.css">

	<!-- JS customs scripts -->
	<script src="assets/dist/js/sb-custom.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
    <!-- jQuery -->
    <script src="assets/bower_components/jquery/dist/jquery.min.js"></script>
	

</head>

<body class="{if isset($smarty.get.p) && $smarty.get.p == 'transfert'}margin10{/if}">

    {if $page != 'login'}
    <div id="outdated">
        <h6>Your browser is out-of-date!</h6>
        <p>Update your browser to view SBUIADMIN Software correctly. <a id="btnUpdateBrowser" href="http://outdatedbrowser.com/">Update my browser now </a></p>
        <p class="last"><a href="#" id="btnCloseUpdateBrowser" title="Close">&times;</a></p>
    </div> <!-- OutdatedBrowser -->
    {/if}
