<?
if (!function_exists('_')) {
	function _($t) { return $t;}
}
?><!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8">

		<script src="jquery-1.10.1.min.js"></script>
		<title>Tesis</title>

		<meta name="description" content="A framework for easily creating beautiful presentations using HTML">
		<meta name="author" content="Enrique Calot">

		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<link rel="stylesheet" href="css/reveal.css">
		<link rel="stylesheet" href="css/theme/serif.css" id="theme">

		<!-- Theme used for syntax highlighting of code -->
		<link rel="stylesheet" href="lib/css/zenburn.css">

		<!-- Printing and PDF exports -->
		<script>
			var link = document.createElement( 'link' );
			link.rel = 'stylesheet';
			link.type = 'text/css';
			link.href = window.location.search.match( /print-pdf/gi ) ? 'css/print/pdf.css' : 'css/print/paper.css';
			document.getElementsByTagName( 'head' )[0].appendChild( link );
		</script>

		<!--[if lt IE 9]>
		<script src="lib/js/html5shiv.js"></script>
		<![endif]-->
		<style>
			.reveal section img.no-border {
				background: none;/* rgba(255, 255, 255, 0.12) none repeat scroll 0 0;*/
				border:0px;
				box-shadow: 0 0 0 rgba(0, 0, 0, 0);
				margin: 0 0;
			}
		</style>
	</head>

	<body>

		<div class="reveal">

			<!-- Any section element inside of this container is displayed as a slide -->
			<div class="slides">

				<section data-transition="zoom">
					<img src='usal.svg' width='800' height='600' alt='Universidad del Salvador' class='no-border'>
				</section>
				<section>
					<h1>Keystroke Dynamics</h1>
					<h3>¿Es posible <b>detectar Emociones</b> o <b>atrapar a un Hacker</b> detrás del telado?</h3>
					<p>
						<small>Presentado por <a href="http://cloodie.com/ckd">Enrique P. Calot</a></small>
					</p>
				</section>
				<section data-transition="zoom">
					<h2>Contenidos de la presentación</h2>
					<ul>
						<li>Estado del arte<ul>
							<li>Keystroke Dynamics</li>
							<li>Contraste: EEG</li>
							<li>Contraste: Gestos faciales</li>
						</ul></li>
						<li>Experimentación</li>
						<li>Conclusiones</li>
					</ul>
				</section>


				<section data-background-transition="zoom" data-transition="concave" data-background="bgd.jpg">
					<iframe class='focusme' src='2-keyboard.php' width='100%' height='600'></iframe>
				</section>

				<section data-background-transition="zoom" data-transition="slide" data-background="bgd.jpg">
					<iframe class='focusme' src='3-keyboard-keys.php' width='100%' height='600'></iframe>
				</section>

				<section>
					<? include('4-identify.php'); ?>
				</section>

				<section data-state="faceend">
					<? include('5-keystroke.php'); ?>
				</section>

				<section>
					<? include('emo.php'); ?>
				</section>

				<section data-state="facestart">
					<h2>Emotiv Epoc</h2>
					<img src='emotiv-epoc.png' class='no-border'>
				</section>

				<section data-transition="slide" data-state="facestart">
					<iframe src='about:blank' width='100%' height='600' id='clmtrackrframe'></iframe>
				</section>

				<section data-transition="slide" data-state="faceend">
					<h1>El experimento</h1>
				</section>

				<section>
					<h1>Muchas Gracias</h1>
					<h3>¿Preguntas?</h3>
				</section>

			</div>

		</div>

		<script src="lib/js/head.min.js"></script>
		<script src="js/reveal.js"></script>

		<script>

			// More info https://github.com/hakimel/reveal.js#configuration
			Reveal.initialize({
				controls: true,
				progress: true,
				history: true,
				center: true,

				transition: 'concave', // none/fade/slide/convex/concave/zoom

				// More info https://github.com/hakimel/reveal.js#dependencies
				dependencies: [
//					{ src: 'lib/js/classList.js', condition: function() { return !document.body.classList; } },
//					{ src: 'plugin/markdown/marked.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
//					{ src: 'plugin/markdown/markdown.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
//					{ src: 'plugin/highlight/highlight.js', async: true, callback: function() { hljs.initHighlightingOnLoad(); } },
					{ src: 'plugin/zoom-js/zoom.js', async: true },
					{ src: 'plugin/notes/notes.js', async: true }
				]
			});

function changeClm(url) {
	if ($('#clmtrackrframe').attr('src')!=url) {
		setTimeout(function(){
			$('#clmtrackrframe').attr('src',url);
		}, 2000);
	}
}

Reveal.addEventListener('facestart',function() {
	changeClm('6-clmtrackr.php');
});
Reveal.addEventListener('faceend',function() {
	changeClm('about:blank');
});

$(document).on('keyup',function(ev){
	if (ev.keyCode==189/* || ev.keyCode==173*/) {
		setTimeout(function(){
			var $fm=$(document).find('section.present .focusme');
			if ($fm.prop("tagName")=='IFRAME') {
				$(document).find('section.present .focusme')[0].contentWindow.focus();
			} else {
				$fm.focus();
			}
		}, 100);
	}
});

		</script>

	</body>
</html>
