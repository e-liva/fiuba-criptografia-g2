<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8">

		<title>reveal.js - The HTML Presentation Framework</title>
<link rel="stylesheet" type="text/css" href="apple-keyboard-white.css">

<script src='jquery-1.10.1.min.js'></script>
<style>

.data {
	color:white;
	font-size:15pt;
	padding-left:10px;

}

</style>

<script>
$(function(){
	$(document).keydown(function(e){
		evt(e.keyCode,e.originalEvent.location,true,e.timeStamp/1);
		return false;
	}).keyup(function(e){
		evt(e.keyCode,e.originalEvent.location,false,e.timeStamp/1);
		return false;
	});
	function evt(code,loc,pressure,ts) {
		switch (code) {
		case 188: //escape sequence: < for mac+chrome
			if (loc==0 && pressure==false) parent.focus();
			break;
		case 32:
			if (pressure)
				next();
			return;
		case 16: //shift
		//case 19: //command
		case 18: //option
			spec(code,loc,pressure,ts);
			break;
		default:
			keyEvent('.c'+code,pressure,ts);
		}
	}
	var last=0;
	function spec(code,loc,pressure,ts) {
		keyEvent('.c'+code+'.k'+(loc==2?'r':'l'),pressure,ts);
	}

	function keyEvent(class1,down,ts) {
		var $el=$(class1);
		if (down) {
			$el.addClass('pressed');
			last=ts;
		} else {
			$el.removeClass('pressed');
			record_press(class1,ts-last);
		}
	}

});

var keypresses={};

function record_press(class1,dur) {
	var findme=class1.replace(/^./,'');
	if (keypresses[findme]) {
		$('#table').find('.ul'+findme).find('.dur').text(keypresses[findme].join(', '));
		keypresses[findme].push(dur);
	} else {
		keypresses[findme]=[dur];
		var li=$('#keyboard').find(class1).closest('li');
		if (li.size()) {
			$('#table').append('<ul class="cf ul'+findme+'" id="asdfg"><li>'+li.html()+'</li> <span class="data">Press Times: <span class="dur">'+dur+'</span></span></ul>');
			if ($('#table').children().size()>1) {
				$('#keyboard').slideUp();
			}
		}
	}
}
var status=1;
function next() {
			console.log(keypresses)
	switch(status) {
	case 1:
		for (k in keypresses) {
			var $span=$('a.'+k+':visible').parent().next();
			var n=0,s=0,ss=0;
			for (j in keypresses[k]) {
				var i=keypresses[k][j];
				n+=1;
				s+=i;
				ss+=i*i;
			}
			$span.text("n="+n+" s="+s+" ss="+ss);
		}
		status=2;
		break;
	case 2:
		for (k in keypresses) {
			var $span=$('a.'+k+':visible').parent().next();
			var n,s,ss;
			n=0,s=0,ss=0;
			for (var j in keypresses[k]) {
				var i=keypresses[k][j];
				n+=1;
				s+=i;
				ss+=i*i;
			}
			var mu=s/n;
			var sigma=(ss/n)-mu*mu;
			sigma=Math.round(sigma*100)/100;
			mu=Math.round(mu*100)/100;
			$span.html("n="+n+" &mu;="+mu+" &sigma;<sup>2</sup>="+sigma);
		}
		status=3;
		break;
	case 3:
		for (k in keypresses) {
			var $span=$('a.'+k+':visible').parent().next(),
				dur=keypresses[k].join(', ')
			
			$span.html('Press Times: <span class="dur">'+dur+'</span>');
		}
		status=1;
		break;

	}

}



</script>
		<!--[if lt IE 9]>
		<script src="lib/js/html5shiv.js"></script>
		<![endif]-->
	</head>

	<body>

<div id="keyboard">
    	<ul class="cf">
        	<li><a href="#" class="key c27 fn"><span id="esc">esc</span></a></li>
        	<li><a href="#" class="key c112 fn"><span>F1</span></a></li>
        	<li><a href="#" class="key c113 fn"><span>F2</span></a></li>
        	<li><a href="#" class="key c114 fn"><span>F3</span></a></li>
        	<li><a href="#" class="key c115 fn"><span>F4</span></a></li>
        	<li><a href="#" class="key c116 fn"><span>F5</span></a></li>
        	<li><a href="#" class="key c117 fn"><span>F6</span></a></li>
        	<li><a href="#" class="key c118 fn"><span>F7</span></a></li>
        	<li><a href="#" class="key c119 fn"><span>F8</span></a></li>
        	<li><a href="#" class="key c120 fn"><span>F9</span></a></li>
        	<li><a href="#" class="key c121 fn"><span>F10</span></a></li>
        	<li><a href="#" class="key c122 fn"><span>F11</span></a></li>
        	<li><a href="#" class="key c123 fn"><span>F12</span></a></li>
        	<li><a href="#" class="key fn"><span>Eject</span></a></li>
        </ul>
    	<ul class="cf" id="numbers">
	    	<li><a href="#" class="key c192"><b>~</b><span>`</span></a></li>
	    	<li><a href="#" class="key c49"><b>!</b><span>1</span></a></li>
	    	<li><a href="#" class="key c50"><b>@</b><span>2</span></a></li>
	    	<li><a href="#" class="key c51"><b>#</b><span>3</span></a></li>
	    	<li><a href="#" class="key c52"><b>$</b><span>4</span></a></li>
	    	<li><a href="#" class="key c53"><b>%</b><span>5</span></a></li>
	    	<li><a href="#" class="key c54"><b>^</b><span>6</span></a></li>
	    	<li><a href="#" class="key c55"><b>&amp;</b><span>7</span></a></li>
	    	<li><a href="#" class="key c56"><b>*</b><span>8</span></a></li>
	    	<li><a href="#" class="key c57"><b>(</b><span>9</span></a></li>
	    	<li><a href="#" class="key c48"><b>)</b><span>0</span></a></li>
	    	<li><a href="#" class="key c189 alt"><b>_</b><span>-</span></a></li>
	    	<li><a href="#" class="key c187"><b>+</b><span>=</span></a></li>
	    	<li><a href="#" class="key c46" id="delete"><span>Delete</span></a></li>
        </ul>
    	<ul class="cf" id="qwerty">
	    	<li><a href="#" class="key c9" id="tab"><span>tab</span></a></li>
	    	<li><a href="#" class="key c81"><span>q</span></a></li>
	    	<li><a href="#" class="key c87"><span>w</span></a></li>
	    	<li><a href="#" class="key c69"><span>e</span></a></li>
	    	<li><a href="#" class="key c82"><span>r</span></a></li>
	    	<li><a href="#" class="key c84"><span>t</span></a></li>
	    	<li><a href="#" class="key c89"><span>y</span></a></li>
	    	<li><a href="#" class="key c85"><span>u</span></a></li>
	    	<li><a href="#" class="key c73"><span>i</span></a></li>
	    	<li><a href="#" class="key c79"><span>o</span></a></li>
	    	<li><a href="#" class="key c80"><span>p</span></a></li>
	    	<li><a href="#" class="key c219 alt"><b>{</b><span>[</span></a></li>
	    	<li><a href="#" class="key c221 alt"><b>}</b><span>]</span></a></li>
	    	<li><a href="#" class="key c220 alt"><b>|</b><span>\</span></a></li>
        </ul>
        <ul class="cf" id="asdfg">
	    	<li><a href="#" class="key c20 alt" id="caps"><b></b><span>caps lock</span></a></li>
	    	<li><a href="#" class="key c65"><span>a</span></a></li>
	    	<li><a href="#" class="key c83"><span>s</span></a></li>
	    	<li><a href="#" class="key c68"><span>d</span></a></li>
	    	<li><a href="#" class="key c70"><span>f</span></a></li>
	    	<li><a href="#" class="key c71"><span>g</span></a></li>
	    	<li><a href="#" class="key c72"><span>h</span></a></li>
	    	<li><a href="#" class="key c74"><span>j</span></a></li>
	    	<li><a href="#" class="key c75"><span>k</span></a></li>
	    	<li><a href="#" class="key c76"><span>l</span></a></li>
	    	<li><a href="#" class="key c186 alt"><b>:</b><span>;</span></a></li>
	    	<li><a href="#" class="key c222 alt"><b>"</b><span>'</span></a></li>
	    	<li><a href="#" class="key c13 alt" id="enter"><span>return</span></a></li>
        </ul>
        <ul class="cf" id="zxcvb">
	    	<li><a href="#" class="key c16 shiftleft kl"><span>Shift</span></a></li>
	    	<li><a href="#" class="key c90"><span>z</span></a></li>
	    	<li><a href="#" class="key c88"><span>x</span></a></li>
	    	<li><a href="#" class="key c67"><span>c</span></a></li>
	    	<li><a href="#" class="key c86"><span>v</span></a></li>
	    	<li><a href="#" class="key c66"><span>b</span></a></li>
	    	<li><a href="#" class="key c78"><span>n</span></a></li>
	    	<li><a href="#" class="key c77"><span>m</span></a></li>
	    	<li><a href="#" class="key c188 alt"><b>&lt;</b><span>,</span></a></li>
	    	<li><a href="#" class="key c190 alt"><b>&gt;</b><span>.</span></a></li>
	    	<li><a href="#" class="key c191 alt"><b>?</b><span>/</span></a></li>
	    	<li><a href="#" class="key c16 shiftright kr"><span>Shift</span></a></li>
        </ul>
		<ul class="cf" id="bottomrow">
	    	<li><a href="#" class="key" id="fn"><span>fn</span></a></li>
	    	<li><a href="#" class="key c17" id="control"><span>control</span></a></li>
	    	<li><a href="#" class="key option kl" id="optionleft"><span>option</span></a></li>
	    	<li><a href="#" class="key command kl" id="commandleft"><span>command</span></a></li>
	    	<li><a href="#" class="key c32" id="spacebar"></a></li>
	    	<li><a href="#" class="key command kr" id="commandright"><span>command</span></a></li>
	    	<li><a href="#" class="key option kr" id="optionright"><span>option</span></a></li>
            <ol class="cf">
            	<li><a href="#" class="key c37" id="left"><span><img src="left.png" /></span></a></li>
                <li>
                	<a href="#" class="key c38" id="up"><span><img src="up.png" /></span></a>
                	<a href="#" class="key c40" id="down"><span><img src="down.png" /></span></a>
                </li>
            	<li><a href="#" class="key c39" id="right"><span><img src="right.png" /></span></a></li>
            </ol>
        </ul>
    </div>
    
	</div>

<div id='table'>
</div>

	</body>
</html>
