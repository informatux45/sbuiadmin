<?php
/* ******************************* *
 * Coming soon (maintenance)       *
 * Include MAIN content            *
 * ------------------------------- *
 * @link http://www.dollar.fr/     *
 * @package CMS SBMAGIC            *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */

/** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'index-main.php') { 
	die('You cannot load this page directly.');
}; 

// --- Intialization
$cs_content = '';

$type_content = $_GET['cscontent'];

switch($type_content) {
	
	case "dark":
		if ($cs['coming-soon-dark'])
			$cs_content .= '<link rel="stylesheet" type="text/css" href="css/dark-theme.css">';
	break;

	case "metas":
		$cs_content .= '
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<title>' . utf8_encode($cs['coming-soon-title']) . '</title>
			<meta name="description" content="">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="apple-touch-icon" sizes="76x76" href="./img/favicons/apple-touch-icon.png">
			<link rel="icon" type="image/png" href="./img/favicons/favicon-32x32.png" sizes="32x32">
			<link rel="icon" type="image/png" href="./img/favicons/favicon-16x16.png" sizes="16x16">
			<link rel="manifest" href="./img/favicons/manifest.json">
			<link rel="mask-icon" href="./img/favicons/safari-pinned-tab.svg" color="#5bbad5">
			<meta name="theme-color" content="#ffffff">		
		';
	break;

	case "clock":
		list($csday, $csmonth, $csyear) = explode("/", $cs['coming-soon-date']);
		$cs_content .= '
		<script>
		';
		$cs_content .= '
			function getCountDown(cdate) {
				// Set the unit values in milliseconds.
				var msecPerMinute = 1000 * 60;
				var msecPerHour = msecPerMinute * 60;
				var msecPerDay = msecPerHour * 24;
			
				// Set a date and get the milliseconds
				var date = new Date();
				var dateMsec = date.getTime();
			
				// Set the date to January 1, at midnight, of the specified year. 2014,7,20,5,25,30,500
			
				date = cdate;
			
				// Get the difference in milliseconds.
				var interval = date.getTime() - dateMsec;
			
				// Calculate how many days the interval contains. Subtract that
				// many days from the interval to determine the remainder.
				var days = Math.floor(interval / msecPerDay);
				interval = interval - (days * msecPerDay );
			
				// Calculate the hours, minutes, and seconds.
				var hours = Math.floor(interval / msecPerHour);
				interval = interval - (hours * msecPerHour );
			
				var minutes = Math.floor(interval / msecPerMinute);
				interval = interval - (minutes * msecPerMinute );
			
				var seconds = Math.floor(interval / 1000);
			
				// Display the result.
				//				document.write(days + " days, " + hours + " hours, " + minutes + " minutes, " + seconds + " seconds.");
			
				return (((days < 10) ? \'0\' + days : days) + " : " + ((hours < 10) ? \'0\' + hours : hours) + " : " + ((minutes < 10) ? \'0\' + minutes : minutes) + " : " + ((seconds < 10) ? \'0\' + seconds : seconds));
			}
		';
		$cs_content .= "
			function initNumbers() {
				var x = 260;
				var y = 230;
				var d = 215;
				var r = [];
				for ( i = 11; i >= 0; i--) {
					var span = $('<span class=\"clock-number\"></span>');
					span.text(((i == 0) ? 12 : i) + '');
					span.css('left', (x + (d) * Math.cos(1.57 - 30 * i * (Math.PI / 180))) + 'px');
					span.css('top', (y - (d) * Math.sin(1.57 - 30 * i * (Math.PI / 180))) + 'px');
					r.push(span);
				}
				return r;
			}
		";
		$cs_content .= '
			function scaleCoordinates(delta, firstTime) {
				$(\'#ticker, #timelable, #timeleft, .clock-number\').each(function() {
					//Get X,Y,font size
			
					if(firstTime == false) {
						$(this).css({\'left\':$(this).data(\'x\'), \'top\':$(this).data(\'y\'), \'fontSize\' : $(this).data(\'font\')});
					}
			
					var x = $(this).css(\'left\');
					//-px
					x = x.substr(0, x.length - 2);
					var y = $(this).css(\'top\');
					y = y.substr(0, y.length - 2);
					var fs = $(this).css(\'font-size\');
					fs = fs.substr(0, fs.length - 2);
					//-px
					if(firstTime) {
						$(this).attr({ \'data-x\' : x, \'data-y\' : y, \'data-font\' : fs });
					}
					//-%
					x = +x + Math.round((delta * x) / 555);
					//Set new X
					y = +y + Math.round((delta * y) / 555);
					//Set new Y
					fs = +fs + ((delta * fs) / 555);
					//set new font size %
			
					//apply new values to attributes
			
					if( $(this).is(\'#timeleft\') ) {
						x = 0;
					}
			
					$(this).css({
						"left" : x + "px",
						"top" : y + "px",
						"font-size" : fs + "px"
					});
				});
			}
			
			
			$(document).ready(function() {
				if( jQuery(\'link[href*="css/dark-theme.css"]\').length ) {
					var opts={plate:"#424242",marks:"#424242",label:"#424242",hours:"#424242",minutes:"#424242",seconds:"#424242"};
				} else {
					var opts={plate:"#FFFFFF",marks:"#FFFFFF",label:"#FFFFFF",hours:"#FFFFFF",minutes:"#FFFFFF",seconds:"#FFFFFF"};
				}
			
			
				SVG(\'canvas\', \'100%\').clock(\'100%\', \'\', opts).start();
			
				var n = initNumbers();
				$("#time-container .numbers-container").append(n);
			
				$("#canvas").everyTime("1s", function(i) {
			
					/* Date and time when your site starts to work */
		';
		$cs_content .= '
					var c = {
						year : ' . intval($csyear) . ',
						month : ' . intval($csmonth) . ',
						day : ' . intval($csday) . ',
						hh : 12,
						min : 30,
						sec : 0,
						milsec : 0
					};
		';
		$cs_content .= '					
					var cd = new Date();
					cd.setYear(c.year);
					c.month = Math.max(c.month-1, 0 );
					cd.setMonth(c.month, c.day);
					//month start from 0
					// cd.setDate(c.day);
					cd.setHours(c.hh, c.min, c.sec, c.milsec);
					//hh min sec milsec
					$("#timeleft").text(getCountDown(cd));
				});
			
				//////////////////////////////////////////////////////////////////////////////////////
				var delta = 0;
				var curWidth = $("#time-container").width();
				if (curWidth != null) {
					delta = curWidth - 555;
					scaleCoordinates(delta, true);
				}
				//555 , 450 , 250
				$(window).resize(function() {
					scaleCoordinates($("#time-container").width() - 555, false);
				});
				///////////////////////////////////////////////////////////////////////////////////////
			
			});
		</script>
		';
	break;

	case "main":
		$cs_content .= '
			<!--[if lt IE 7]>
			<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
			<![endif]-->
			<section class="main-menu-container">
				<div class="show_toggle"><a href="#"></a></div>
				<ul class="main-menu">
					<!--<li>
						<a href="#" class="home-link">Accueil</a>
					</li>-->';
					
			if ($cs['coming-soon-text'])
				$cs_content .= '
					<li>
						<a href="#" data-page="about">A propos</a>
					</li>';
					
			if ($cs['coming-soon-address'] || $cs['coming-soon-tel'] || $cs['coming-soon-email'])
				$cs_content .= '
					<li>
						<a href="#" data-page="contacts">Contact</a>
					</li>';
					
			$cs_content .= '
				</ul>
			</section>
			<!--<section class="twitter-container">
				<div class="twitter">
					<ul class="tweet_list" id="tweet_list">
						<li class="tweet_first tweet_odd">
							<span class="tweet_text">WordPress 3.8 “Parker” is out and
								<br>
								ready for download <a href="#">buff.ly/18EGYRR</a></span><span class="tweet_time"><a href="#" title="view tweet on twitter">about 5 days ago</a></span>
						</li>
						<li class="tweet_even">
							<span class="tweet_text"><span class="at">@</span><a href="http://twitter.com/BeyondLocal_">BeyondLocal_</a> <span class="at">@</span><a href="http://twitter.com/bespokeav">bespokeav</a> Thanks :)</span><span class="tweet_time"><a href="http://twitter.com/pixelthrone/status/466325405507387392" title="view tweet on twitter">about 15 days ago</a></span>
						</li>
						<li class="tweet_odd">
							<span class="tweet_text"><span class="at">@</span><a href="http://twitter.com/geeks_in_motion">geeks_in_motion</a> ,hi for a better support please visit our online forum.I have a great team able to help you. → <a href="http://goo.gl/fghIzb">goo.gl/fghIzb</a></span><span class="tweet_time"><a href="http://twitter.com/pixelthrone/status/466280221889409024" title="view tweet on twitter">about 15 days ago</a></span>
						</li>
					</ul>
				</div>
			</section>-->
	
			<section class="mainarea">
				<div id="clock" class="active">
					<div class="clock-container">
						<div id="time-container-wrap">
							<div id="time-container">
								<div class="numbers-container"></div>
								<span id="ticker" class="clock-label">MONTRE</span>
								<span id="timelable" class="clock-label">LANCEMENT</span>
								<span id="timeleft" class="clock-label">COUNTDOWN</span>
								<figure id="canvas"></figure>
							</div>
						</div>
					</div>
					<h3>' . utf8_encode($cs['coming-soon-title']) . '<br><i>' . utf8_encode($cs['coming-soon-title2']) . '</i></h3> 
					<!--<form action="#" class="subscribe" id="subscribe">
						<input type="email" placeholder="Enter your email" class="email form_item requiredField" name="subscribe_email" />
						<input type="submit" class="form_submit" value="subscribe" />
						<div id="form_results"></div>
					</form>-->
				</div>
				<div class="mainarea-content">';
				
			if ($cs['coming-soon-text'])
				$cs_content .= '				
					<div id="about" data-page="about" class="side-page active">
						<div class="container">
							<h2 class="title">Qui sommes nous</h2>
							<p style="text-align: justify;"> ' . html_entity_decode(utf8_encode($cs['coming-soon-text'])) . ' </p>
						</div>
					</div>';
			
			if ($cs['coming-soon-address'] || $cs['coming-soon-tel'] || $cs['coming-soon-email']) {
				$cs_content .= '
					<div id="contacts" data-page="contacts" class="side-page side-right went-right">
						<div class="container">
							<h2 class="title">Nos coordonnées</h2>
							<p style="font-size: 1.6em; line-height: 1.6em">';
							
			if ($cs['coming-soon-address'])
				$cs_content .= $cs['coming-soon-address'] . '<br>';
				
			if ($cs['coming-soon-tel'])
				$cs_content .= $cs['coming-soon-tel'] . '<br>';
				
			if ($cs['coming-soon-email'])
				$cs_content .= $cs['coming-soon-email'] . '<br>';
				
			$cs_content .= '</p>
							<!--<form id="contacts_form" action="#" class="contact-list">
								<div class="field-row">
									<input class="form_item" type="text" id="name" name="name" placeholder="Name" />
								</div>
								<div class="field-row">
									<input class="form_item" type="email" id="email" name="email" placeholder="E-mail" />
								</div>
								<div class="field-row">
									<input class="form_item" type="text" id="message" name="message" placeholder="Message" />
								</div>
								<div class="field-row">
									<input type="submit" class="form_submit" value="Send Message" />
								</div>
								<div id="contact_results"></div>
							</form>-->
						</div>
					</div>';
			}		
			
			$cs_content .= '
				</div> <!-- ./mainarea-content -->
				<a class="close" href="#"><img alt="" src="img/close.png"></a>
			</section> <!-- ./mainarea -->
			<section class="social-container">
				<ul class="social">';
				
			if ($cs['coming-soon-twitter'])
				$cs_content .= '
					<li>
						<a href="'.$cs['coming-soon-twitter'].'"><img src="img/icons/twitter-icon.png" alt="" /></a>
					</li>
				';
			if ($cs['coming-soon-youtube'])
				$cs_content .= '
					<li>
						<a href="'.$cs['coming-soon-youtube'].'"><img src="img/icons/youtube-icon.png" alt="" /></a>
					</li>
				';
			if ($cs['coming-soon-facebook'])
				$cs_content .= '
					<li>
						<a href="'.$cs['coming-soon-facebook'].'"><img src="img/icons/facebook-icon.png" alt="" /></a>
					</li>
				';
			if ($cs['coming-soon-google-plus'])
				$cs_content .= '
					<li>
						<a href="'.$cs['coming-soon-google-plus'].'"><img src="img/icons/google-plus-icon.png" alt="" /></a>
					</li>
				';
			
			$cs_content .= '
				</ul>
			</section>
			';
	break;
}

echo $cs_content;

?>