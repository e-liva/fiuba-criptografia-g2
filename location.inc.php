<?
ckd_style('standalone');
ckd_style('keystroke');
ckd_script('browscap');
ckd_script('bootstrap-editable.min');
ckd_style('bootstrap-editable');
ckd_script('ckd.min');

include('api.php');
$api=new CKD('apikey');
?>
<div id="contenido" class="container">
	<div class="hero-unit">

		<h1><?=_('Demo de API de Keystroke Dynamics');?></h1>
		<p>
			<?=_('According to the keystroke dynamics...');?>
		</p>



<section class='special' id="testzone">
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<h2>Typing Zone</h2>



<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a href="#tfree"><?=_('Free text');?></a></li>
  <li><a href="#tlogin"><?=_('Login Form');?></a></li>
  <li><a href="#tother"><?=_('Other Form');?></a></li>
  <li><a href="#tcaptcha"><?=_('Captcha');?></a></li>
</ul>
 
<div class="tab-content">
  <div class="tab-pane active" id="tfree">

		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for='freetext'><?=_('Free text');?></label>
				<div class="controls">
					<textarea id='freetext'></textarea>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<button class='btn btn-large btn-primary subm' id='tfreesubmit'><?=_('Probar resultados');?></button>
				</div>
			</div>
		</form>

	</div>
  <div class="tab-pane" id="tlogin">

		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for=user><?=_('User');?></label>
				<div class="controls">
					<input autocomplete="off" id="user" type='text' class='cl'>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for='pass'><?=_('Password');?></label>
				<div class="controls">
					<input autocomplete="off" id="pass" type='text' class='cl'>
					<label class="checkbox">
					<input type="checkbox" id='showpass' checked='checked'> <?=_('Show Password');?> </label>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<input id='tloginsubmit' type='submit' value='Ingresar' class='btn btn-large btn-primary subm'>
				</div>
			</div>
		</form>


	</div>
  <div class="tab-pane" id="tother">
		<p><?=_('This place is available for custom forms your company wants to add.');?></p>
	</div>
  <div class="tab-pane" id="tcaptcha">
		<p><?=_('Comming soon: Solve the captcha!');?></p>
	</div>
</div>
 
<script>

$(function(){
	$('#myTab a').click(function (e) {
	  e.preventDefault();
	  $(this).tab('show');
	});

	$("#showpass").change(function(){
		$('#pass').get(0).type=$("#showpass:checked").size()?'text':'password';
	});

	var api=new CKD();
	api.listen('#testzone input.cl, #testzone textarea');
	$('#myTab li:eq(1) a').tab('show');

	function test() {
		var keystroke=api.get();
		newData('<?=_('Cadencia');?>',keystroke);

		$('.subm').attr('disabled','disabled');
		$("#tfreesubmit").text('<?=_('Simulando prueba...');?>');
		$("#tloginsubmit").val('<?=_('Simulando ingreso...');?>');

		$.ajax({
			url: 'ajax.php?op=check',
			type: 'POST',
			data: 'k='+keystroke,
			success: function(data){
				$('.subm').removeAttr('disabled');
				$("#tfreesubmit").text('<?=_('Probar Resultados');?>');
				$("#tloginsubmit").val('<?=_('Ingresar');?>');
			}
	 	 });
		
		$('a.entity[data-type=select]').each(function(){
			var $this=$(this),pk=$this.data('pk')/1;

			var result=api.onlineCheck(pk),
				src=$this.data('src'),
				out=[];
		
			for (var i in src){
				var name=src[i].text,res=result[src[i].value/1];
				out.push([name,res]);
			};
			if (out.length==2) {
				var winner=out[0][1]<out[1][1]?0:1,loser=1-winner;
				var regla=Math.round(10000*out[winner][1]/out[loser][1])/100;
				var w_text=out[winner][0];
				$this.text(w_text);
				var $parent=$this.parent();
				$parent.find('span.percent').remove();
				var val='urgent';
				if (regla>75) val='medium';
				if (regla>90) val='low';
				$parent.append(' <span class="percent result-cad '+val+'">'+regla+' %</span>');
				//,"with",regla,$this.closest('tr').find('.dimension').text());
			}
		});
		

		api.clean();
	}

	$("#tfreesubmit").click(function(){
		$('#tfree textarea').val('');
		test();
		return false;
	});
	$("#tloginsubmit").click(function(){
		$('#tlogin input.cl').val('');
		test();
		return false;
	});


	//editable:
	//$.fn.editable.defaults.mode = 'inline';

	$('.entity').each(function(){
		var $this=$(this),source=undefined;
		if ($this.data('type')=='select') {
			source=$this.data('src');
		}
		$this.editable({
			url: 'ajax.php?op=train',
			title: '<?=_('Enter value');?>',
			placement: 'left',
			source:source,
			params: function(params) {
				params.ks=api.get();
	    	return params;
			}
		});
	});
	function cacheDimension(id) {
		$.ajax({
			url: 'ajax.php?op=cache',
			type: 'POST',
			data: 'pk='+id,
			dataType: 'json',
			success: function(data){
				api.onlineTrain(id,data);
			}
	 	 });
	}

	cacheDimension(3);

});



</script>

</section>

<section class='special' id='dims'>
<h2>Dimensions</h2>
<table class='table' id='dimensions'>
<tr><th><?=_('Dimension');?></th><th><?=_('Value');?></th></tr>
<?
$dimensions=$api->listDimensions();

foreach ($dimensions as $dim) {
$id=$dim['id_dimension'];
$name=$dim['name'];
$form=$dim['form'];
$src='';
switch($dim['form']) {
	case 'e':
		$type='typeahead';
		$src=' data-source="ajax.php?op=src&amp;pk='.$id.'"';
		break;
	case 'f':
		$type='number';
		break;
	case 'c':
		$type='select';
		$entities=$api->listEntities($id);
		$out=array();
		if ($entities) foreach ($entities as $ent) {
			$out[]=array('value'=>$ent['id_entity'],'text'=>$ent['name']);
		}
		$src=" data-src='".json_encode($out)."'";
		break;
}

	echo "<tr><td class='dimension'>$name</td><td><a href='#'$src data-name='dim$id' data-pk='$id' data-type='$type' class='entity'></a></td></tr>";
}

?>
</table>
</section>

<section class='special' id='datatable'>
<h2>Information table</h2>
<table class='table' id='data'>
<tr><th><?=_('Attribute');?></th><th><?=_('Value');?></th></tr>
</table>
</section>


<section class='special' id="map">
<h2>User location</h2>



<article>
</article>
<script>

$('#map').hide();

function success(position) {
	var
		mapcanvas = document.createElement('div'),
		geocoder = new google.maps.Geocoder();

	mapcanvas.id = 'mapcontainer';
	mapcanvas.style.height = '400px';
	mapcanvas.style.width = '100%';
	
	document.querySelector('article').appendChild(mapcanvas);
	var
		coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
		options = {
			zoom: 15,
			center: coords,
			mapTypeControl: false,
			navigationControlOptions: {
				style: google.maps.NavigationControlStyle.SMALL
			},
			mapTypeId: google.maps.MapTypeId.ROADMAP
		},
		map = new google.maps.Map(document.getElementById("mapcontainer"), options);
		marker = new google.maps.Marker({
			position: coords,
			map: map,
			title:"You are here!"
		});

	geocoder.geocode(
		{'latLng': coords},
		function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if(results[0]) {
					newData("Dirección",results[0].formatted_address);
				}
			} else {
				newData("Dirección",status);
			}
		}
	);

	$('#map').slideDown();

}


if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(success);
} else {
	newData("<?=_('Geo location support');?>","<?=_('Not available');?>");
}

var b=new browscap('browscap.ini');

b.getBrowser(navigator.userAgent,function(browser){
	var browser2={
		'Browser':'<?=_('Browser');?>',
		'Version':'<?=_('Version');?>',
		'Device_Name':'<?=_('Device');?>',
		'Platform':'<?=_('Platform');?>'
	};

	for (var i in browser2) {
		newData(browser2[i],browser[i]);
	}
});


function newData(attr,val) {
	$('#data tr').each(function(){
		if ($(this).children('td').first().text()==attr) {
			$(this).children('td').last().text(val);
			attr=false;
		}
	});
	if (attr) $('#data').append('<tr><td>'+attr+'</td><td>'+val+'</td></tr>');
}



</script>

</section>


	</div>

