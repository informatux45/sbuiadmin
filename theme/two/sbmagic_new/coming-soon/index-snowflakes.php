<?php
/* ******************************* *
 * Coming soon (maintenance)       *
 * Page SNOWFLAKES type            *
 * ------------------------------- *
 * @link http://www.dollar.fr/     *
 * @package CMS SBMAGIC            *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */

/** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'index-snowflakes.php') { 
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
		<link rel="stylesheet" type="text/css" href="css/bg-slider.css" />
		<link rel="stylesheet" type="text/css" href="clock/css/clock.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/responsive.css">
		
		<?php $_GET['cscontent'] = 'dark'; include('index-main.php'); ?>
		
		<style>
			body.snowflakes {
				background: url(<?php if ($cs['coming-soon-image'] != '') echo _AM_MEDIAS_URL.$cs['coming-soon-image']; else echo './img/snowflakes.jpg'; ?>) center center no-repeat;
				background-size: cover;
			}		
		</style>
		
		<script src="js/vendor/modernizr-2.6.2.min.js"></script>
	</head>
	<body onload="init()" class="snowflakes">
		<script src="js/vendor/ThreeCanvas.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/snow.js" type="text/javascript" charset="utf-8"></script>
	
		<script>

			var SCREEN_WIDTH = window.innerWidth;
			var SCREEN_HEIGHT = window.innerHeight;

			var container;

			var particle;

			var camera;
			var scene;
			var renderer;

			var mouseX = 0;
			var mouseY = 0;

			var windowHalfX = window.innerWidth / 2;
			var windowHalfY = window.innerHeight / 2;
			
			var particles = []; 
			var particleImage = new Image();//THREE.ImageUtils.loadTexture( "img/ParticleSmoke.png" );
			particleImage.src = 'img/snow.png'; 

		
		
			function init() {

				container = document.createElement('div');
				container.setAttribute('id', 'snowflakes');
				document.body.appendChild(container);

				camera = new THREE.PerspectiveCamera( 75, SCREEN_WIDTH / SCREEN_HEIGHT, 1, 10000 );
				camera.position.z = 1000;

				scene = new THREE.Scene();
				scene.add(camera);
					
				renderer = new THREE.CanvasRenderer();
				renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
				var material = new THREE.ParticleBasicMaterial( { map: new THREE.Texture(particleImage) } );
					
				for (var i = 0; i < 500; i++) {

					particle = new Particle3D( material);
					particle.position.x = Math.random() * 2000 - 1000;
					particle.position.y = Math.random() * 2000 - 1000;
					particle.position.z = Math.random() * 2000 - 1000;
					particle.scale.x = particle.scale.y =  1;
					scene.add( particle );
					
					particles.push(particle); 
				}

				container.appendChild( renderer.domElement );

	
				document.addEventListener( 'mousemove', onDocumentMouseMove, false );
				document.addEventListener( 'touchstart', onDocumentTouchStart, false );
				document.addEventListener( 'touchmove', onDocumentTouchMove, false );
				
				setInterval( loop, 1000 / 60 );
				
			}
			
			function onDocumentMouseMove( event ) {

				mouseX = event.clientX - windowHalfX;
				mouseY = event.clientY - windowHalfY;
			}

			function onDocumentTouchStart( event ) {

				if ( event.touches.length == 1 ) {

					event.preventDefault();

					mouseX = event.touches[ 0 ].pageX - windowHalfX;
					mouseY = event.touches[ 0 ].pageY - windowHalfY;
				}
			}

			function onDocumentTouchMove( event ) {

				if ( event.touches.length == 1 ) {

					event.preventDefault();

					mouseX = event.touches[ 0 ].pageX - windowHalfX;
					mouseY = event.touches[ 0 ].pageY - windowHalfY;
				}
			}

			//

			function loop() {

			for(var i = 0; i<particles.length; i++)
				{

					var particle = particles[i]; 
					particle.updatePhysics(); 
	
					with(particle.position)
					{
						if(y<-1000) y+=2000; 
						if(x>1000) x-=2000; 
						else if(x<-1000) x+=2000; 
						if(z>1000) z-=2000; 
						else if(z<-1000) z+=2000; 
					}				
				}
			
				camera.position.x += ( mouseX - camera.position.x ) * 0.05;
				camera.position.y += ( - mouseY - camera.position.y ) * 0.05;
				camera.lookAt(scene.position); 

				renderer.render( scene, camera );

				
			}
		</script>

		<?php $_GET['cscontent'] = 'main'; include('index-main.php'); ?>

		<script src="js/vendor/jquery-1.11.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/classie.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/jquery.easing.1.3.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/jquery.tubular.1.0.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/vendor/jquery.cycle.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/plugins.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/main.js" type="text/javascript" charset="utf-8"></script>

		<script src="clock/js/svg.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="clock/js/svg.easing.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="clock/js/svg.clock.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="clock/js/jquery.timers.min.js" type="text/javascript" charset="utf-8"></script>	

		<?php $_GET['cscontent'] = 'clock'; include('index-main.php'); ?>
		
	</body>
</html>
