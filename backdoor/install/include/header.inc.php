<!DOCTYPE html>
<html>

<!--

 _______  ______           _________ _______  ______   _______ _________ _       
(  ____ \(  ___ \ |\     /|\__   __/(  ___  )(  __  \ (       )\__   __/( (    /|
| (    \/| (   ) )| )   ( |   ) (   | (   ) || (  \  )| () () |   ) (   |  \  ( |
| (_____ | (__/ / | |   | |   | |   | (___) || |   ) || || || |   | |   |   \ | |
(_____  )|  __ (  | |   | |   | |   |  ___  || |   | || |(_)| |   | |   | (\ \) |
      ) || (  \ \ | |   | |   | |   | (   ) || |   ) || |   | |   | |   | | \   |
/\____) || )___) )| (___) |___) (___| )   ( || (__/  )| )   ( |___) (___| )  \  |
\_______)|/ \___/ (_______)\_______/|/     \|(______/ |/     \|\_______/|/    )_)
                                                                                 
-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="Advanced Power of PHP">
    <meta name="generator" content="EasyInstaller">
	<title><?php echo lang_key("installation_guide"); ?> | <?php echo lang_key('database_settings'); ?></title>

	<link href="images/favicon.ico" rel="shortcut icon" />
	<link rel="stylesheet" type="text/css" href="templates/<?php echo EI_TEMPLATE; ?>/css/styles.css" />
	<?php
		if($curr_lang_direction == 'rtl'){
			echo '<link rel="stylesheet" type="text/css" href="templates/'.EI_TEMPLATE.'/css/rtl.css" />'."\n";
		}
	?>

	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	<?php
		if(file_exists('languages/js/'.$curr_lang.'.js')){
			echo '<script type="text/javascript" src="language/'.$curr_lang.'/js/common.js"></script>';
		}else{
			echo '<script type="text/javascript" src="language/fr/js/common.js"></script>';
		}
	?>
</head>