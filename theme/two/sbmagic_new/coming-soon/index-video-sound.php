<?php
/* ******************************* *
 * Coming soon (maintenance)       *
 * Page VIDEO-SOUND type           *
 * ------------------------------- *
 * @link http://www.dollar.fr/     *
 * @package CMS SBMAGIC            *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */

/** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'index-video-sound.php') { 
	die('You cannot load this page directly.');
}; 
 
 ?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
	<!--<![endif]-->
	<head>
		<?php $_GET['cscontent'] = 'metas'; include('index-main.php'); ?>

		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Exo+2:400,100,100italic,200,200italic,300,300italic,400italic,500,500italic,700,700italic,600,600italic' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Josefin+Sans:100,300,400,600' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="css/normalize.css">
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<link rel="stylesheet" type="text/css" href="clock/css/clock.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/responsive.css">
		
		<?php $_GET['cscontent'] = 'dark'; include('index-main.php'); ?>
		
		<style>
			body {
				background: url('<?php if ($cs['coming-soon-image'] != '') echo _AM_MEDIAS_URL.$cs['coming-soon-image']; else echo './img/bg/bg2.jpg'; ?>') no-repeat center center fixed;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
			}
		</style>
		
		<script src="js/vendor/modernizr-2.6.2.min.js"></script>
	</head>
	<body>

		<?php $_GET['cscontent'] = 'main'; include('index-main.php'); ?>

		<script src="js/vendor/jquery-1.11.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/min/classie.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/jquery.easing.1.3.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/jquery.tubular.1.0.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/jquery.cycle.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/plugins.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/main.js" type="text/javascript" charset="utf-8"></script>

		<script src="clock/js/svg.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="clock/js/svg.easing.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="clock/js/svg.clock.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/ThreeWebGL.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/ThreeExtras.js" type="text/javascript" charset="utf-8"></script>
		<script src="clock/js/jquery.timers.min.js" type="text/javascript" charset="utf-8"></script>

		<?php $_GET['cscontent'] = 'clock'; include('index-main.php'); ?>

		<script type="text/javascript">
			$(function() {
				var options = {
					videoId : '<?php if ($cs['coming-soon-video'] != '') echo $cs['coming-soon-video']; else echo 'U91Zp9wWS30'; ?>',
					start : 10,
					mute: false,
					muteButtonClass: 'sound_button'
				};
				$('body').tubular(options);
				
				$('.sound_button').click(function(){
					$(this).toggleClass('muted');
				});
			});
		</script>
		<div class="sound_button"></div>
	</body>
</html>
