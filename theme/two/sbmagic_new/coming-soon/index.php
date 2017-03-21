<?php
/* ******************************* *
 * Coming soon (maintenance)       *
 * ------------------------------- *
 * @link http://www.dollar.fr/     *
 * @package CMS SBMAGIC            *
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
// --- Include ---
$include = include_once("index-$coming_soon_type.php");
// --------------------------------
// --- Test if include success
// --------------------------------
if (!$include) {
	// --------------------------------
	// --- Coming soon page default
	// --------------------------------
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo utf8_encode($cs['coming-soon-title']) ?></title>
	<link href="tools/style.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<script type="text/javascript" src="tools/jquery.min.js"></script> 
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-backstretch/2.0.4/jquery.backstretch.min.js"></script> 
	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:700%7CRoboto+Slab:700' rel='stylesheet' type='text/css'>
	<meta name="viewport" content="width=device-width, initial-scale = 1.0, user-scalable = no">
	
	
	</head>
	<body>
	<div class="wrapper">
		<div class="left-section"><img src="images/watch.png" /></div>
		<div class="right-section">
			<h1 id="logo"><?php echo utf8_encode($cs['coming-soon-title']) ?></h1>
			<div class="seprator"></div>
			<div class="main-content">
				<h2><?php echo utf8_encode($cs['coming-soon-title2']) ?></h2>
				<p><?php echo utf8_encode($cs['coming-soon-text']) ?></p>
			</div>
			<?php
			if ($cs['coming-soon-facebook'] || $cs['coming-soon-twitter'] || $cs['coming-soon-linkedin']) {
				echo '<ul class="social">';
				if ($cs['coming-soon-facebook']) echo '<li id="facebook"><a href="'.$cs['coming-soon-facebook'].'"><i class="fa fa-facebook"></i></a></li>';
				if ($cs['coming-soon-twitter']) echo '<li id="twitter"><a href="'.$cs['coming-soon-twitter'].'"><i class="fa fa-twitter"></i></a></li>';
				if ($cs['coming-soon-linkedin']) echo '<li id="linkedin"><a href="'.$cs['coming-soon-linkedin'].'"><i class="fa fa-linkedin"></i></a></li>';
				echo '</ul>';
			}
			?>
			<ul class="info">
				<?php
				if ($cs['coming-soon-tel']) echo '<li><i class="fa fa-phone-square"></i>'.$cs['coming-soon-tel'].'</li>';
				if ($cs['coming-soon-address']) echo '<li><i class="fa fa-map-marker"></i>'.$cs['coming-soon-address'].'</li>';
				if ($cs['coming-soon-email']) echo '<li><i class="fa fa-envelope"></i><a href="mailto:'.$cs['coming-soon-email'].'">'.$cs['coming-soon-email'].'</a></li>';
				?>
			</ul>
	
			<div class="social-links">
			<?php
			if ($cs['coming-soon-facebook'] || $cs['coming-soon-twitter'] || $cs['coming-soon-linkedin']) {
				echo '<ul class="social2">';
				if ($cs['coming-soon-facebook']) echo '<li id="facebook"><a href="'.$cs['coming-soon-facebook'].'"><i class="fa fa-facebook"></i></a></li>';
				if ($cs['coming-soon-twitter']) echo '<li id="twitter"><a href="'.$cs['coming-soon-twitter'].'"><i class="fa fa-twitter"></i></a></li>';
				if ($cs['coming-soon-linkedin']) echo '<li id="linkedin"><a href="'.$cs['coming-soon-linkedin'].'"><i class="fa fa-linkedin"></i></a></li>';
				echo '</ul>';
			}
			?>		
			</div>
			
		</div>
		<div class="watch-section"><img src="images/watch.png" /></div>
	</div>
	
	
	</body>
	</html>
<?php
}