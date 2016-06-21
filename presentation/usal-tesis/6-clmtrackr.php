<!doctype html>
<html lang="en">
	<head>
		<title>Face tracker</title>
		<meta charset="utf-8">
		<style>
			body {
				max-width: 1150px;
font-family: "Palatino Linotype", "Book Antiqua", Palatino, FreeSerif, serif;
			}
			
			#overlay {
				position: absolute;
				top: 0px;
				left: 0px;
				-o-transform : scaleX(-1);
				-webkit-transform : scaleX(-1);
				transform : scaleX(-1);
				-ms-filter : fliph; /*IE*/
				filter : fliph; /*IE*/

				width : 600px;
				height : 450px;
			}

			#videoel {
				-o-transform : scaleX(-1);
				-webkit-transform : scaleX(-1);
				transform : scaleX(-1);
				-ms-filter : fliph; /*IE*/
				filter : fliph; /*IE*/

				width : 600px;
				height : 450px;
			}
			
			#container {
				position : relative;
				width : 370px;
				/*margin : 0px auto;*/
			}
			
			#content {
				margin-top : 10px;
				margin-left : auto;
				margin-right : auto;
				max-width: 600px;
			}
			
			#sketch, #filter {
				display: none;
			}
			
			#controls {
				text-align : center;
			}

			#emotion_container {
				width: 600px;
			}

			#emotion_icons {
				height: 40px;
				padding-left: 55px;
			}

			.emotion_icon {
				width : 30px;
				height : 30px;
				margin-top: 2px;
				/*margin-left : 13px;*/
				margin-left : 50px;
			}

			#emotion_chart, #emotion_icons {
				margin: 0 auto;
				width : 600px;
			}

			#icon1, #icon2, #icon3, #icon4, #icon5, #icon6 {
				visibility : hidden;
			}

			/* d3 */
			.bar {
				fill : steelblue;
				fill-opacity : .9;
			}

		</style>
		<script>
			// getUserMedia only works over https in Chrome 47+, so we redirect to https. Also notify user if running from file.
			if (window.location.protocol == "file:") {
				alert("You seem to be running this example directly from a file. Note that these examples only work when served from a server or localhost due to canvas cross-domain restrictions.");
			} else if (window.location.hostname !== "localhost" && window.location.protocol !== "https:"){
				window.location.protocol = "https";
			}
		</script>
	</head>
	<body>
		<script src="/clmtrackr/js/utils.js"></script>
		<script src="/clmtrackr/js/clmtrackr.js"></script>
		<script src="/clmtrackr/models/model_pca_20_svm_emotionDetection.js"></script>
		<script src="/clmtrackr/js/Stats.js"></script>
		<script src="/clmtrackr/examples/js/d3.min.js"></script>
		<script src="/clmtrackr/examples/js/emotion_classifier.js"></script>
		<script src="emotionmodel.js"></script>
		<div id="content">
			<div id="container">
				<video id="videoel" width="400" height="300" preload="auto" loop>
				</video>
				<canvas id="overlay" width="400" height="300"></canvas>
			</div>
			<canvas id="sketch" width="400" height="300"></canvas>
			<div id="emotion_container">
				<div id="emotion_icons">
					<img class="emotion_icon" id="icon1" src="/clmtrackr/examples/media/icon_angry.png">
					<img class="emotion_icon" id="icon2" src="/clmtrackr/examples/media/icon_sad.png">
					<img class="emotion_icon" id="icon3" src="/clmtrackr/examples/media/icon_surprised.png">
					<img class="emotion_icon" id="icon4" src="/clmtrackr/examples/media/icon_happy.png">
				</div>
				<div id='emotion_chart'></div>
			</div>
			<script>
				var vid = document.getElementById('videoel');
				var overlay = document.getElementById('overlay');
				var overlayCC = overlay.getContext('2d');
				
				/********** check and set up video/webcam **********/

				navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
				window.URL = window.URL || window.webkitURL || window.msURL || window.mozURL;

				// check for camerasupport
				if (navigator.getUserMedia) {
					// set up stream
					
					var videoSelector = {video : true};
					if (window.navigator.appVersion.match(/Chrome\/(.*?) /)) {
						var chromeVersion = parseInt(window.navigator.appVersion.match(/Chrome\/(\d+)\./)[1], 10);
						if (chromeVersion < 20) {
							videoSelector = "video";
						}
					};
				
					navigator.getUserMedia(videoSelector, function( stream ) {
						if (vid.mozCaptureStream) {
							vid.mozSrcObject = stream;
						} else {
							vid.src = (window.URL && window.URL.createObjectURL(stream)) || stream;
						}
						vid.play();
					}, function() {
						//insertAltVideo(vid);
						alert("There was some problem trying to fetch video from your webcam. If you have a webcam, please make sure to accept when the browser asks for access to your webcam.");
					});
				} else {
					//insertAltVideo(vid);
					alert("This demo depends on getUserMedia, which your browser does not seem to support. :(");
				}

				vid.addEventListener('canplay', startVideo, false);
				
				/*********** setup of emotion detection *************/

				var ctrack = new clm.tracker({useWebGL : true});
				ctrack.init(pModel);

				function startVideo() {
					// start video
					vid.play();
					// start tracking
					ctrack.start(vid);
					// start loop to draw face
					drawLoop();
				}
				
				function drawLoop() {
					requestAnimFrame(drawLoop);
					overlayCC.clearRect(0, 0, 400, 300);
					//psrElement.innerHTML = "score :" + ctrack.getScore().toFixed(4);
					if (ctrack.getCurrentPosition()) {
						ctrack.draw(overlay);
					}
					var cp = ctrack.getCurrentParameters();
					
					var er = ec.meanPredict(cp);
					if (er) {
						updateData(er);
					}
				}
				
				var ec = new emotionClassifier();
				ec.init(emotionModel);
				var emotionData = ec.getBlank();	
				
				/************ d3 code for barchart *****************/

				var margin = {top : 20, right : 20, bottom : 10, left : 40},
					width = 600 - margin.left - margin.right,
					height = 100 - margin.top - margin.bottom;

				var barWidth = 30;

				var formatPercent = d3.format(".0%");
				
				var x = d3.scale.linear()
					.domain([0, ec.getEmotions().length]).range([margin.left, width+margin.left]);

				var y = d3.scale.linear()
					.domain([0,1]).range([0, height]);

				var svg = d3.select("#emotion_chart").append("svg")
					.attr("width", width + margin.left + margin.right)
					.attr("height", height + margin.top + margin.bottom)
				
				svg.selectAll("rect").
				  data(emotionData).
				  enter().
				  append("svg:rect").
				  attr("x", function(datum, index) { return x(index); }).
				  attr("y", function(datum) { return height - y(datum.value); }).
				  attr("height", function(datum) { return y(datum.value); }).
				  attr("width", barWidth).
				  attr("fill", "#2d578b");

				svg.selectAll("text.labels").
				  data(emotionData).
				  enter().
				  append("svg:text").
				  attr("x", function(datum, index) { return x(index) + barWidth; }).
				  attr("y", function(datum) { return height - y(datum.value); }).
				  attr("dx", -barWidth/2).
				  attr("dy", "1.2em").
				  attr("text-anchor", "middle").
				  text(function(datum) { return datum.value;}).
				  attr("fill", "white").
				  attr("class", "labels");
				
				svg.selectAll("text.yAxis").
				  data(emotionData).
				  enter().append("svg:text").
				  attr("x", function(datum, index) { return x(index) + barWidth; }).
				  attr("y", height).
				  attr("dx", -barWidth/2).
				  attr("text-anchor", "middle").
				  attr("style", "font-size: 18").
				  text(function(datum) { return datum.emotion;}).
				  attr("transform", "translate(0, 18)").
				  attr("class", "yAxis");

				function updateData(data) {
					// update
					var rects = svg.selectAll("rect")
						.data(data)
						.attr("y", function(datum) { return height - y(datum.value); })
						.attr("height", function(datum) { return y(datum.value); });
					var texts = svg.selectAll("text.labels")
						.data(data)
						.attr("y", function(datum) { return height - y(datum.value); })
						.text(function(datum) { return datum.value.toFixed(1);});

					// enter 
					rects.enter().append("svg:rect");
					texts.enter().append("svg:text");

					// exit
					rects.exit().remove();
					texts.exit().remove();
				}

				/******** stats ********/

/*				stats = new Stats();
				stats.domElement.style.position = 'absolute';
				stats.domElement.style.top = '0px';
				document.getElementById('container').appendChild( stats.domElement );

				// update stats on every iteration
				document.addEventListener('clmtrackrIteration', function(event) {
					stats.update();
				}, false);*/
				
			</script>
		</div>
	</body>
</html>
