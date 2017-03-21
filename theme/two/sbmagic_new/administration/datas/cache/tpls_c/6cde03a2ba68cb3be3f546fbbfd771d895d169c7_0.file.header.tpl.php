<?php
/* Smarty version 3.1.29, created on 2016-12-20 14:40:55
  from "/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/header.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_585934e7a0f794_43706429',
  'file_dependency' => 
  array (
    '6cde03a2ba68cb3be3f546fbbfd771d895d169c7' => 
    array (
      0 => '/Applications/MAMP/htdocs/sbmagic_new/administration/core/tpls/tpl/header.tpl',
      1 => 1482146499,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_585934e7a0f794_43706429 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SBUIADMIN User Interface Administration | Powered by Smarty <?php echo Smarty::SMARTY_VERSION;?>
 | Design by SBadmin 2 Bootstrap">
    <meta name="author" content="INFORMATUX">

	<link rel="apple-touch-icon" sizes="180x180" href="img/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" href="img/favicons/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="img/favicons/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="img/favicons/manifest.json">
	<link rel="mask-icon" href="img/favicons/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="theme-color" content="#ffffff">

    <title><?php echo @constant('_AM_SITE_CUSTOMER_NAME');?>
</title>

    <!-- Bootstrap Core CSS -->
    <link href="assets/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="assets/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="assets/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
	<!-- OutdatedBrowser -->
	<link rel="stylesheet" href="inc/plugins/outdatedbrowser/outdatedbrowser.min.css">
	
	<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
	<link rel="stylesheet" href="assets/dist/css/jquery.fileupload.css">

	<!-- JS customs scripts -->
	<?php echo '<script'; ?>
 src="assets/dist/js/sb-custom.js"><?php echo '</script'; ?>
>
	
    <!-- Custom Admin CSS -->
    <link href="assets/dist/css/sb-admin-custom.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <?php echo '<script'; ?>
 src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"><?php echo '</script'; ?>
>
    <![endif]-->
	
    <!-- jQuery -->
    <?php echo '<script'; ?>
 src="assets/bower_components/jquery/dist/jquery.min.js"><?php echo '</script'; ?>
>
	
	<?php if ($_smarty_tpl->tpl_vars['page']->value == 'login') {?>
	<!-- CSS to style background login page with pattern -->
	<link rel="stylesheet" href="assets/dist/css/pattern<?php echo $_smarty_tpl->tpl_vars['sb_random_bg']->value;?>
.css">
	<?php }?>

</head>

<body class="<?php if ($_smarty_tpl->tpl_vars['page']->value == 'login') {?>login-pattern<?php }
if ($_GET['p'] == 'transfert') {?> margin10<?php }?>">
	
    <div id="outdated">
        <h6>Your browser is out-of-date!</h6>
        <p>Update your browser to view SBMAGIC Software correctly. <a id="btnUpdateBrowser" href="http://outdatedbrowser.com/">Update my browser now </a></p>
        <p class="last"><a href="#" id="btnCloseUpdateBrowser" title="Close">&times;</a></p>
    </div> <!-- OutdatedBrowser -->
	
    <?php if ($_smarty_tpl->tpl_vars['page']->value != 'login') {?><div id="wrapper"><?php }
}
}
