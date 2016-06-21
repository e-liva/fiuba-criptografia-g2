<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8">

		<script src="jquery-1.10.1.min.js"></script>
		<title>Keystroke Dynamics</title>

		<meta name="description" content="A framework for easily creating beautiful presentations using HTML">
		<meta name="author" content="Hakim El Hattab">

		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

		<link rel="stylesheet" href="css/reveal.css">
		<link rel="stylesheet" href="css/theme/default.css" id="theme">

		<!-- For syntax highlighting -->
		<link rel="stylesheet" href="lib/css/zenburn.css">

		<!-- If the query includes 'print-pdf', use the PDF print sheet -->
		<script>
			document.write( '<link rel="stylesheet" href="css/print/' + ( window.location.search.match( /print-pdf/gi ) ? 'pdf' : 'paper' ) + '.css" type="text/css" media="print">' );
		</script>

		<!--[if lt IE 9]>
		<script src="lib/js/html5shiv.js"></script>
		<![endif]-->


<style>
sup {vertical-align: super; }
span.sub { vertical-align: sub; }
</style>

	</head>

	<body>

		<div class="reveal">

			<!-- Any section element inside of this container is displayed as a slide -->
			<div class="slides">

				<section>
					<h2>Detecting features and identity of users in web forms using Keystroke Dynamics</h2>
					<p>
						<small>Presented by <a href="http://cloodie.com/ckd">Enrique P. Calot</a></small>
					</p>
				</section>

				<section>
					<h1>Keystroke Dynamics</h1>
					<h3>Is it possible to <b>catch a hacker</b> behind a keyboard?</h3>
				</section>

				<section>
					<iframe src='2-keyboard.php' width='100%' height='600'></iframe>
				</section>

				<section>
					<iframe src='3-keyboard-keys.php' width='100%' height='600'></iframe>
				</section>

				<section>
					<section>
						<h2>Different Algorithms</h2>
						<ul>
							<li>Euclidean</li>
							<li>Normalized Euclidean</li>
							<li>Mahalanobis distance</li>
						</ul>
					</section>
					<section>
						<h3>Euclidean</h3>
						<p>&mu;['h']<sup>2</sup>+&mu;['e']<sup>2</sup>+&mu;['l']<sup>2</sup>+&mu;['o']<sup>2</sup></p>
					</section>
					<section>
						<h3>Normalized Euclidean</h3>
						<p>&mu;['h']<sup>2</sup>/&sigma;['h']+&mu;['e']<sup>2</sup>/&sigma;['e']+&mu;['l']<sup>2</sup>/&sigma;['l']+&mu;['o']<sup>2</sup>/&sigma;['o']</p>
					</section>
					<section>
						<h3>Mahalanobis distance</h3>
						<p>Using a crazy matrix</p>
					</section>
				</section>

				<section>
					<section>
					<h1>Applications</h1>
					</section>
					<section>
					<h2>Detect a person</h2>
					</section>
					<section>
					<h2>Dexterity</h2>
					</section>
					<section>
					<h2>Emotional state</h2>
					</section>
					<section>
					<h2>Age (indirectly; with less precision)</h2>
					</section>
					<section>
					<h2>Gender (with less precision)</h2>
					</section>
				</section>

				<section>
					<h1>Limitless Ideas</h1>
				</section>
				<section>
					<h2>Minified Code in Web</h2>
					<pre><code contenteditable>window.CKD=function(){function t(a,f){var e=n,h=f-p.e,c=p.c&lt;&lt;10|a;-1!=p.c
&amp;&amp;800&gt;h&amp;&amp;(void 0==e[c]&amp;&amp;(e[c]={a:0,b:0,f:0}),e[c].a+=h,e[c].b+=h*h,e[c].f++)
;p={c:a,e:f}}function s(a){for(var f=&quot;&quot;;31&lt;a;)f+=
&quot;0124689qwertyuiopsdfgASDFGHJKLZX&quot;.charAt(a&amp;31),a&gt;&gt;=5;return f+
&quot;357ahjklzxcvbnmQWERTYUIOPCVBNM #&quot;.charAt(a)}function v(a,f){function
e(b){var a=1+76.18009173/b-86.50532033/(b+1)+24.01409822/(b+2)-
1.231739516/(b+3)+0.00120858003/(b+4)-5.36382E-6/(b+5);return(b-0.5)
*h(b+4.5)-(b+4.5)+h(2.50662827465*a)}var h=
Math.log,c=Math.exp,m=Math.abs,n=Math.sqrt;this.d=function(b){var d=b/f;
b=a/1;var g,l,k;if(0&gt;=d)b=0;else if(200&lt;b)d=(d-b)/n(b),g=1/(1+0.2316419*
m(d)),g=0.3989423*c(-d*d/2)*g*(0.3193815+g*(-0.3565638+g*(1.781478+
g*(-1.821256+1.330274*g)))),0&lt;d&amp;&amp;(g=1-g),l=2/n(b),k=0.39894228*c(-d*
d/2),b=3*(6/b)*(d*d-3)+l*l*(d^4-10*d*d+15),b=g-l*(d*d-1)*k/6-k*d*b/72;else
if(d&lt;b+1){l=g=1/b;for(k=1;g&gt;1E-5*l;)g=g*d/(b+k),l+=g,k+=1;b=l*=c(b*h(d)-d
-e(b))}else{g=0;k=l=1;for(var q=d,p=0,r=0;1E-5&lt;m((k-p)/k);)p=k,r+=1,g=k+(r-
b)*g,l=q+(r-b)*l,k=d*g+r*k,q=d*l+r*q,g/=q,l/=q,k/=q,q=1;b=1-c(b*h(d)-d-e(b
))*k}return b}}var p={c:-1,e:0},n={};this.listen=function(a){a.jQuery||(a=$(a));
a.keydown(function(a){t(2*a.keyCode|1,a.timeStamp)}).keyup(function(a){t(2*a.
keyCode,a.timeStamp)});return this};this.clean=function(){n={};return this};this.get
=function(){var a,f,e=&quot;&quot;,h=[],c=0;for(a in n)h.push(a/1);h.sort(function(a,c){return
a-c});for(f in h)a=h[f],e+=s(a-c)+s(n[a].f)+s(n[a].b)+s(n[a].a),c=a;return e};var u=
{};this.onlineTrain=function(a,f){for(var e in f)for(var h in f[e]){var c=f[e][h][0],
m=f[e][h][1]+1,m=new v(c*c/m,m/c);m.a=c;f[e][h]=m}u[a]=f};this.onlineCheck
=function(a){a=u[a];var f={},e;for(e in a){var h=a[e],c=0,m=0,p;for(p in n){var 
b=h[p];void 0!=b&amp;&amp;(b=b.d(n[p].a)-b.d(b.a),c+=b*b,m++)}m&amp;&amp;(f[e]=c/m)}return f}};</code></pre>
				</section>

				<section>
					<? include('4-identify.php'); ?>
				</section>

				<section>
					<? include('5-keystroke.php'); ?>
				</section>
				<section>
					<h2>The Code</h2>
					<pre><code contenteditable><?=htmlentities(file_get_contents('keystroke.js'),ENT_QUOTES); ?></code></pre>
				</section>

				<section>
					<h1>THE END</h1>
					<h3>¿Questions?</h3>
				</section>
				<section>
					<h1>Thank you</h1>
				</section>

			</div>

		</div>

		<script src="lib/js/head.min.js"></script>
		<script src="js/reveal.min.js"></script>

		<script>

			// Full list of configuration options available here:
			// https://github.com/hakimel/reveal.js#configuration
			Reveal.initialize({
				controls: true,
				progress: true,
				history: true,

				theme: Reveal.getQueryHash().theme, // available themes are in /css/theme
				transition: Reveal.getQueryHash().transition || 'default', // default/cube/page/concave/zoom/linear/none

				// Optional libraries used to extend on reveal.js
				dependencies: [
					{ src: 'lib/js/classList.js', condition: function() { return !document.body.classList; } },
					{ src: 'plugin/markdown/showdown.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
					{ src: 'plugin/markdown/markdown.js', condition: function() { return !!document.querySelector( '[data-markdown]' ); } },
					{ src: 'plugin/highlight/highlight.js', async: true, callback: function() { hljs.initHighlightingOnLoad(); } },
					{ src: 'plugin/zoom-js/zoom.js', async: true, condition: function() { return !!document.body.classList; } },
					{ src: 'plugin/notes/notes.js', async: true, condition: function() { return !!document.body.classList; } }
				]
			});

		</script>

	</body>
</html>
