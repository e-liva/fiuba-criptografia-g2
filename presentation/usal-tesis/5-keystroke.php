<h2>¿Cómo se ve un keystroke?</h2>
<script type='text/javascript' src='ckd.js'></script>


<div class='span8'>
<form>
<label class="control-label" for=user2>Opinión del Producto</label><br>
<textarea class='focusme' id="user2" style='width:800px;height:200px;'></textarea>
</form>
			<script>
$(function(){
	var k=new CKD();
	k.listen('#user2,#pass2');
	$('#user2').on('keyup',function(){
		var kd=k.get();
console.log(kd.match(/.{1,30}/g).join(' '))
		$('#showme').html('<p>'+kd.match(/.{1,30}/g).join(' ')+'</p>');
		return false;
	});
});
			</script>
</div>
	<div id='showme' style='height:300px;width:1000px'>
	</div>
