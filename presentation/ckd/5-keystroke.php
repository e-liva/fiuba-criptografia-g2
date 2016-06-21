<h2>How does a keystroke look like?</h2>
<script type='text/javascript' src='ckd.js'></script>


<div class='span8'>
<form>
<table border='0'><tr><td>
				<label class="control-label" for=user2><?=_('User');?></label>
</td><td>
					<input id="user2" type='text'>
</td></tr>
<tr><td>
				<label class="control-label" for='pass2'><?=_('Password');?></label>
</td><td>
					<input id="pass2" type='text'>
</td></tr>
<tr><td colspan=2>
					<label class="checkbox">
					<input type="checkbox" id='showpass' checked='checked'> <?=_('Show Password');?> </label>
</td></tr></table>
					<input id='sub3' type='submit' value='Execute' class='btn btn-large btn-primary'>
</form>
			<script>
$(function(){
	var k=new CKD();
	k.listen('#user2,#pass2');
	$('#sub3').click(function(){
		var kd=k.get();
console.log(kd.match(/.{1,30}/g).join(' '))
		$('#showme').html('<p style="font-size:10pt;">'+kd.match(/.{1,30}/g).join(' ')+'</p>');
		return false;
	});
});
			</script>
</div>

	<div id='showme'>
	</div>
