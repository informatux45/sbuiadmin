<?php
/* ******************************* *
 * Coming soon (maintenance)       *
 * Page CLOUD type                 *
 * ------------------------------- *
 * @link http://www.dollar.fr/     *
 * @package CMS SBMAGIC            *
 * @file UTF-8                     *
 * ©INFORMATUX.COM                 *
 * ******************************* */
 
/** Prevent direct access */
if (basename($_SERVER['PHP_SELF']) == 'index-cloud.php') { 
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

		<script type="text/javascript" src="js/vendor/three.min.js"></script>
		<script type="text/javascript" src="js/vendor/detector.js"></script>

		<script id="vs" type="x-shader/x-vertex">

			varying vec2 vUv;

			void main() {

			vUv = uv;
			gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );

			}

		</script>

		<script id="fs" type="x-shader/x-fragment">

			uniform sampler2D map;

			uniform vec3 fogColor;
			uniform float fogNear;
			uniform float fogFar;

			varying vec2 vUv;

			void main() {

			float depth = gl_FragCoord.z / gl_FragCoord.w;
			float fogFactor = smoothstep( fogNear, fogFar, depth );

			gl_FragColor = texture2D( map, vUv );
			gl_FragColor.w *= pow( gl_FragCoord.z, 20.0 );
			gl_FragColor = mix( gl_FragColor, vec4( fogColor, gl_FragColor.w ), fogFactor );

			}

		</script>

		<script type="text/javascript">
			if (!Detector.webgl)
				Detector.addGetWebGLMessage();

			var container;
			var camera, scene, renderer;
			var mesh, geometry, material;

			var mouseX = 0, mouseY = 0;
			var start_time = Date.now();

			var windowHalfX = window.innerWidth / 2;
			var windowHalfY = window.innerHeight / 2;

			init();

			function init() {

				container = document.createElement('div');
				container.className = "cloud";
				document.body.appendChild(container);

				// Bg gradient

				var canvas = document.createElement('canvas');
				canvas.width = 32;
				canvas.height = window.innerHeight;

				var context = canvas.getContext('2d');

				var gradient = context.createLinearGradient(0, 0, 0, canvas.height);
				gradient.addColorStop(0, "#1e4877");
				gradient.addColorStop(0.5, "#4584b4");

				context.fillStyle = gradient;
				context.fillRect(0, 0, canvas.width, canvas.height);

				//

				camera = new THREE.PerspectiveCamera(30, window.innerWidth / window.innerHeight, 1, 3000);
				camera.position.z = 6000;

				scene = new THREE.Scene();

				geometry = new THREE.Geometry();

				var texture = THREE.ImageUtils.loadTexture('img/cloud.png', null, animate);
				texture.magFilter = THREE.LinearMipMapLinearFilter;
				texture.minFilter = THREE.LinearMipMapLinearFilter;

				var fog = new THREE.Fog(0x4584b4, -100, 3000);

				material = new THREE.ShaderMaterial({

					uniforms : {

						"map" : {
							type : "t",
							value : texture
						},
						"fogColor" : {
							type : "c",
							value : fog.color
						},
						"fogNear" : {
							type : "f",
							value : fog.near
						},
						"fogFar" : {
							type : "f",
							value : fog.far
						},

					},
					vertexShader : document.getElementById('vs').textContent,
					fragmentShader : document.getElementById('fs').textContent,
					depthWrite : false,
					depthTest : false,
					transparent : true

				});

				var plane = new THREE.Mesh(new THREE.PlaneGeometry(64, 64));

				for (var i = 0; i < 8000; i++) {

					plane.position.x = Math.random() * 1000 - 500;
					plane.position.y = -Math.random() * Math.random() * 200 - 15;
					plane.position.z = i;
					plane.rotation.z = Math.random() * Math.PI;
					plane.scale.x = plane.scale.y = Math.random() * Math.random() * 1.5 + 0.5;

					THREE.GeometryUtils.merge(geometry, plane);

				}

				mesh = new THREE.Mesh(geometry, material);
				scene.add(mesh);

				mesh = new THREE.Mesh(geometry, material);
				mesh.position.z = -8000;
				scene.add(mesh);

				renderer = new THREE.WebGLRenderer({
					antialias : false
				});
				renderer.setSize(window.innerWidth, window.innerHeight);
				container.appendChild(renderer.domElement);

			//	document.addEventListener('mousemove', onDocumentMouseMove, false);
				window.addEventListener('resize', onWindowResize, false);

			}

			function onDocumentMouseMove(event) {

				mouseX = (event.clientX - windowHalfX ) * 0.25;
				mouseY = (event.clientY - windowHalfY ) * 0.15;

			}

			function onWindowResize(event) {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize(window.innerWidth, window.innerHeight);

			}

			function animate() {

				requestAnimationFrame(animate);

				position = ((Date.now() - start_time ) * 0.03 ) % 8000;

				camera.position.x += (mouseX - camera.position.x ) * 0.01;
				camera.position.y += (-mouseY - camera.position.y ) * 0.01;
				camera.position.z = -position + 8000;

				renderer.render(scene, camera);

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
