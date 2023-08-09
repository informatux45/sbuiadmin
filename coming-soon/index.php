<?php
/* ******************************* *
 * Coming soon (maintenance)       *
 * ------------------------------- *
 * @link http://informatux.com/    *
 * @package SBUIADMIN              *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */
error_reporting(1);
ini_set('display_errors', 1);
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
		
		<a target="_blank" rel="noopener" href="https://github.com/informatux45/sbuiadmin"><img style="position: absolute; top: 0; right: 0; border: 0; width: 115px; height: 115px;" src="images/sbuiadmin-fork-me-on-github-blue.png" alt="Fork me on GitHub - SBUIADMIN - INFORMATUX"></a>
	  
	  <!-- Countdown -->
	  
	  <section id="home" class="tab-pane fade in active">
		<article role="countdown" class="countdown-pan">
		  <!--<div id="countdown" class="text-center"></div>-->
		  <p><?php echo html_entity_decode( utf8_encode($cs['coming-soon-title2']) ) ?></p>
		</article>
	  </section>
	  
	  <!-- Countdown --> 
	  
	  <!-- About us -->
	  
	  <section id="menu1" class="tab-pane fade">
		<article role="introduction" class="introduction-pan">
		  <header class="page-title">
			<h2>A propos de nous</h2>
		  </header>
		  <p><?php echo html_entity_decode($cs['coming-soon-text']) ?></p>
		</article>
	  </section>
	  
	  <!-- About us --> 
	  
	  <!-- Contact -->
	  
	  <section id="menu3" class="tab-pane fade">
		<article role="contact" class="contact-pan">
		  <header class="page-title">
			<h2>Contactez nous</h2>
		  </header>
		  <?php
			if (isset($cs['coming-soon-email']) && $cs['coming-soon-email'] != '') {
				echo "<h3><a href='mailto:".$cs['coming-soon-email']."'>";
				echo $cs['coming-soon-email'];
				echo '</a></h3>';
			}
			echo '<ul class="contact-infos">';
			if ((isset($cs['coming-soon-address']) && $cs['coming-soon-address'] != '') || (isset($cs['coming-soon-tel']) && $cs['coming-soon-tel'] != '')) {
				if ($cs['coming-soon-address'] != '') {
					echo "<li><i class='fa fa-map-marker' aria-hidden='true'></i> ";
					echo $cs['coming-soon-address'];
					echo '</li>';
				}
				if (isset($cs['coming-soon-tel']) && $cs['coming-soon-tel'] != '') {
					echo "<li><i class='fa fa-phone' aria-hidden='true'></i> ";
					echo "<a href='tel:" . $cs['coming-soon-tel'] . "'>";
					echo $cs['coming-soon-tel'];
					echo '</a></li>';
				}
			}
			echo '</ul>';
		  ?>
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
		  <li class="active"><a data-toggle="tab" href="#home" title="Countdown">Maintenance</a></li>
		  <?php
			if ($cs['coming-soon-text'])
				echo '<li><a data-toggle="tab" href="#menu1" title="Introduction">A propos de nous</a></li>';
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
	  <p>&copy; <?php echo utf8_encode($cs['coming-soon-title']) ?> <?php echo date('Y'); ?>. Tous droits r&eacute;serv&eacute;s.</p>
	</footer>
  </header>
  
  <!-- header -->
  
  <footer class="mobile">
	<p>&copy; <?php echo utf8_encode($cs['coming-soon-title']) ?> <?php echo date('Y'); ?>. Tous droits r&eacute;serv&eacute;s.</p>
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
<!--<script>
	// set the date we're counting down to
	<?php
		$coming_soon_date = $cs['coming-soon-date'];
		list($date_day, $date_month, $date_year) = explode("/", $coming_soon_date);
	?>
	var target_date = new Date('<?php echo $date_year; ?>, <?php echo $date_month; ?>, <?php echo $date_day; ?>').getTime();
</script>
<script src="js/countdown-js.js" type="text/javascript"></script>-->

<?php if ($coming_soon_type == 'video') { ?>
<!-- Video --> 
<script src="js/jquery.mb.YTPlayer.js" type="text/javascript"></script> 
<script src="js/video.js" type="text/javascript"></script> 
<?php } ?>

<script src="js/html5shiv.js" type="text/javascript"></script>
</body>
</html>