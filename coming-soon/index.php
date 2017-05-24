<?php
/* ******************************* *
 * Coming soon (maintenance)       *
 * ------------------------------- *
 * @link http://informatux.com/    *
 * @package SBUIADMIN              *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */

// ----------------------
// SESSION Initialisation
// ----------------------
session_start();

// ----------------------
// Global defined
// ----------------------
include_once('../sbconfig.php');

// ----------------------
// Global include
// ----------------------
include_once('../header.php');
// ----------------------

// ----------------------
// Force DEBUG Off
// ----------------------
error_reporting(0);
ini_set('display_errors', 0);

// ----------------------
// Define Globals
// ----------------------
global $sbsmarty, $sbsanitize, $sbpage, $sbsql;
// ----------------------

// --------------------------------
// --- Recuperation des donnees
// --------------------------------
$query = "SELECT config, content FROM " . _AM_DB_PREFIX . "sb_config WHERE config = 'coming-soon-url'
												   OR config = 'coming-soon-title'
												   OR config = 'coming-soon-title2'
												   OR config = 'coming-soon-text'
												   OR config = 'coming-soon-tel'
												   OR config = 'coming-soon-address'
												   OR config = 'coming-soon-email'
												   OR config = 'coming-soon-facebook'
												   OR config = 'coming-soon-twitter'
												   OR config = 'coming-soon-youtube'
												   OR config = 'coming-soon'
												   OR config = 'coming-soon-type'
												   OR config = 'coming-soon-image'
												   OR config = 'coming-soon-video'
												   OR config = 'coming-soon-dark'
												   OR config = 'coming-soon-date'
												   OR config = 'coming-soon-google-plus'
												   ";
$request = $sbsql->query($query);
$assoc   = $sbsql->toarray($request);
// --------------------------------
foreach($assoc as $row) {
	$cs[$row['config']] = utf8_encode($row['content']);
}
// --------------------------------

// --------------------------------
// --- Define coming soon type page
// --------------------------------
$coming_soon_type = (isset($cs['coming-soon-type']) && $cs['coming-soon-type'] != '') ? trim($cs['coming-soon-type']) : 'image';

?>

<!DOCTYPE HTML>

<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta charset="utf-8">

<!-- Description, Keywords and Author -->

<meta name="description" content="">
<meta name="author" content="">
<title><?php echo utf8_encode($cs['coming-soon-title']) ?></title>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

<!-- style -->
<?php
$coming_soon_image = (isset($cs['coming-soon-image']) && $cs['coming-soon-image'] != '') ? _AM_MEDIAS_URL . trim($cs['coming-soon-image']) : 'images/bg-image.jpg';
?>
<style>
main[role="main-wrapper-iamge"],main[role="main-wrapper-iamge"] > .over-bg-color{
	background-image: url(<?php echo $coming_soon_image; ?>);
	background-size: cover;
	background-position: 50% 0;
	background-repeat: no-repeat;
	width: 100%;
	height: 100%;
	position: fixed;
}
main[role="main-wrapper-iamge"] > .over-bg-color{background-color:rgba(255,255,255,0.5); background-image:none; padding:0}
</style>

<link href="css/style.css" rel="stylesheet">

<!-- style -->

<!-- bootstrap -->

<link href="css/bootstrap.min.css" rel="stylesheet">

<!-- responsive -->

<link href="css/responsive.css" rel="stylesheet">

<!-- font-awesome -->

<link href="css/font-awesome.min.css" rel="stylesheet">

<!-- font-awesome -->

<?php if ($coming_soon_type == 'video') { ?>
<!-- Video -->

<link href="css/video.css" rel="stylesheet">
<?php } ?>

</head>

<body>

<?php if ($coming_soon_type == 'image') { ?>

<!-- main-wrapper-iamge -->

<main id="main" role="main-wrapper-iamge">
<div class="over-bg-color"> 

<?php } elseif ($coming_soon_type == 'video') {

$coming_soon_video = ($cs['coming-soon-video'] == '') ? 'PF0L3gvSVcg' : $cs['coming-soon-video'];
	
?>

<!--Video Section--> 

<a id="video" class="player" data-property="{videoURL:'https://www.youtube.com/watch?v=<?php echo $coming_soon_video; ?>',containment:'.bg-container-youtube', showControls:false, autoPlay:true, loop:true,  startAt:0, opacity:1, addRaster:false, quality:'large'}"></a>
<div class="bg-container-youtube"></div>
<div class="overlay-bg"></div>

<!--Video Section--> 

<!-- main -->

<main role="video-container"> 

<?php } ?>

  <!-- container -->
  
  <div class="container">
	
	<!-- tab-content -->
	
	<div class="tab-content text-center"> 
	  
	  <!-- Countdown -->
	  
	  <section id="home" class="tab-pane fade in active">
		<article role="countdown" class="countdown-pan">
		  <div id="countdown" class="text-center"></div>
		  <p><?php echo utf8_encode($cs['coming-soon-title2']) ?></p>
		</article>
	  </section>
	  
	  <!-- Countdown --> 
	  
	  <!-- introduction -->
	  
	  <section id="menu1" class="tab-pane fade">
		<article role="introduction" class="introduction-pan">
		  <header class="page-title">
			<h2>A propos de nous</h2>
		  </header>
		  <p><?php echo html_entity_decode($cs['coming-soon-text']) ?></p>
		  
		  <!-- services -->
		  
		  <ul role="services">
			<li> <i class="fa fa-diamond" aria-hidden="true"></i>
			  <h6>Branding Consuting</h6>
			  <p>We are a team of talented people<br/>
				with big ideas and creative.</p>
			</li>
			<li> <i class="fa fa-camera-retro" aria-hidden="true"></i>
			  <h6>Fashion Photography</h6>
			  <p>We are a team of talented people<br/>
				with big ideas and creative.</p>
			</li>
			<li> <i class="fa fa-bullhorn" aria-hidden="true"></i>
			  <h6>Digital Marketing</h6>
			  <p>We are a team of talented people<br/>
				with big ideas and creative.</p>
			</li>
		  </ul>
		  
		  <!-- services --> 
		  
		</article>
	  </section>
	  
	  <!-- introduction --> 
	  
	  <!-- Subscribe -->
	  
	  <section id="menu2" class="tab-pane fade">
		<article role="subscribe" class="subscribe-pan">
		  <header class="page-title">
			<h2>Subscribe to Us</h2>
		  </header>
		  <div class="ntify_form">
			<form method="post" action="php/subscribe.php" name="subscribeform" id="subscribeform">
			  <input name="email" type="email" id="subemail" placeholder="Enter Your Email...">
			  <label>
				<input name="" type="submit" class="button-icon">
				<i class="fa fa-paper-plane" aria-hidden="true"></i> </label>
			</form>
			
			<!-- subscribe message -->
			
			<div id="mesaj"></div>
			
			<!-- subscribe message --> 
			
		  </div>
		  <p>Please enter your email below and we'll let you know once<br/>
			we're up and running.</p>
		</article>
	  </section>
	  
	  <!-- Subscribe --> 
	  
	  <!-- Contact -->
	  
	  <section id="menu3" class="tab-pane fade">
		<article role="contact" class="contact-pan">
		  <header class="page-title">
			<h2>Stay in touch with us</h2>
		  </header>
		  <h3><a href="mailto:Contact@pixicon.com">Contact @ pixicon.com</a></h3>
		  <ul>
			<li><i class="fa fa-map-marker" aria-hidden="true"></i> Collins Street West Victoria 8007 Australia.</li>
			<li><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:1-212-290-4700">+1-212-290-4700</a></li>
		  </ul>
		</article>
	  </section>
	</div>
	
	<!-- tab-content --> 
	
  </div>
  
  <!-- container --> 
  
  <!-- header -->
  
  <header role="header">
	<hgroup> 
	  
	  <!-- logo -->
	  
	  <h1> <a href="#" title="<?php echo utf8_encode($cs['coming-soon-title']) ?>"><?php echo utf8_encode($cs['coming-soon-title']) ?></a> </h1>
	  
	  <!-- logog --> 
	  
	  <!-- nav -->
	  
	  <nav role="nav" id="header-nav" class="nav navy">
		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#home" title="Countdown">Lancement</a></li>
		  <?php
			if ($cs['coming-soon-text'])
				echo '<li><a data-toggle="tab" href="#menu1" title="Introduction">A propos de nous</a></li>';
			if ($cs['coming-soon-subscribe'])
				echo '<li><a data-toggle="tab" href="#menu2" title="Subscribe">Inscription</a></li>';
			if ($cs['coming-soon-address'] || $cs['coming-soon-tel'] || $cs['coming-soon-email'])
				echo '<li><a data-toggle="tab" href="#menu3" title="Contact">Contact</a></li>';
		  ?>
		</ul>
		<div role="socil-icons" class="mobile-social">
			<?php
				if ($cs['coming-soon-twitter'])
					echo '<li><a href="'.$cs['coming-soon-twitter'].'" target="_blank" title="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>';
				if ($cs['coming-soon-facebook'])
					echo '<li><a href="'.$cs['coming-soon-facebook'].'" target="_blank" title="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>';
				if ($cs['coming-soon-google-plus'])
					echo '<li><a href="'.$cs['coming-soon-google-plus'].'" target="_blank" title="google-plus"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>';
				if ($cs['coming-soon-youtube'])
					echo '<li><a href="'.$cs['coming-soon-youtube'].'" target="_blank" title="youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>';
			?>
		</div>
	  </nav>
	  
	  <!-- nav --> 
	  
	  <!-- Social Icons -->
	  
	  <ul role="socil-icons" class="desk-social">
		<?php
			if ($cs['coming-soon-twitter'])
				echo '<li><a href="'.$cs['coming-soon-twitter'].'" target="_blank" title="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>';
			if ($cs['coming-soon-facebook'])
				echo '<li><a href="'.$cs['coming-soon-facebook'].'" target="_blank" title="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>';
			if ($cs['coming-soon-google-plus'])
				echo '<li><a href="'.$cs['coming-soon-google-plus'].'" target="_blank" title="google-plus"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>';
			if ($cs['coming-soon-youtube'])
				echo '<li><a href="'.$cs['coming-soon-youtube'].'" target="_blank" title="youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>';
		?>
	  </ul>
	  
	  <!-- Social Icons --> 
	  
	</hgroup>
	<footer class="desk">
	  <p><?php echo date('Y'); ?> &copy; <?php echo utf8_encode($cs['coming-soon-title']) ?> Tous droits r&eacute;serv&eacute;s.</p>
	</footer>
  </header>
  
  <!-- header -->
  
  <footer class="mobile">
	<p><?php echo date('Y'); ?> &copy; <?php echo utf8_encode($cs['coming-soon-title']) ?> Tous droits r&eacute;serv&eacute;s.</p>
  </footer>
</div>
</main>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 

<script src="js/jquery.min.js" type="text/javascript"></script> 

<!-- custom --> 

<script src="js/custom.js" type="text/javascript"></script> 
<script src="js/nav-custom.js" type="text/javascript"></script> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 

<script src="js/bootstrap.min.js" type="text/javascript"></script> 

<!-- jquery.countdown --> 

<script src="js/countdown-js.js" type="text/javascript"></script>

<?php if ($coming_soon_type == 'video') { ?>
<!-- Video --> 
<script src="js/jquery.mb.YTPlayer.js" type="text/javascript"></script> 
<script src="js/video.js" type="text/javascript"></script> 
<?php } ?>

<script src="js/html5shiv.js" type="text/javascript"></script>
</body>
</html>