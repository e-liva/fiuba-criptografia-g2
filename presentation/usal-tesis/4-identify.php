<?
?>
<h2>Demo</h2>
<script type='text/javascript' src='keystroke.js'></script>
<style>
.p-low {
	background-color:green;
}
.p-medium {
	background-color:#ff8040;
}

.p-urgent {
	background-color:red;
}

</style>

<div class='span4 nav-sidebar' style='float:left; width:49%'>

<div class="navig">
	<ul class="nav nav-list">
		<li class='disabled'><a href="#" id='startrun'><i class="icon-repeat"></i> <?=_('Compare');?></a></li>
		<li><a href="#" id='addprofile'><i class="icon-plus-sign"></i> <?=_('New Profile');?></a></li>
	</ul>
</div>

</div>


<div class='span8 subf' style='float:right; width:50%'>
<form>
<table border='0'><tr><td>
				<label class="control-label" for=user><?=_('User');?></label>
</td><td>
					<input id="user" type='text'>
</td></tr>
<tr><td>
				<label class="control-label" for='pass'><?=_('Password');?></label>
</td><td>
					<input id="pass" type='text'>
</td></tr>
<tr><td colspan=2>
					<label class="checkbox">
					<input type="checkbox" id='showpass' checked='checked'> <?=_('Show Password');?> </label>
</td></tr></table>
					<input id='sub' type='submit' value='Execute' class='btn btn-large btn-primary'>
</form>
			<script>
$(function(){
	var keystroke=new cAPI(function(){
		return $('.active #startrun').size()?'run':'train';
	});
	$('.subf').hide();

	function change() {
		var profile=$('#sub').data('selected');
		if (profile) keystroke.save(profile);
	}
	function train(profile) {
		if (profile) keystroke.load(profile);
		$('#sub').data('selected',profile);
	}

	$('#addprofile').click(function(){
		var prof=window.prompt("<?=_('Insert the name of the new profile');?>","");
		$('.nav-list li').removeClass('active');
		$('<li class="active"><a href="#" id="add_profile" data-profile="'+prof+'"><i class="icon-user"></i> '+prof+'</a></li>').insertBefore($('#addprofile').parent());
		change();
		train(prof);
		$('.subf').show();
		$('#sub').val('Entrenar');
		$('#startrun').parent().removeClass('disabled');
		$('#user').focus();
		return false;
	});
	$('#startrun').click(function(){
		var $this=$(this);
		$('.nav-list li').removeClass('active');
		$this.parent().addClass('active');
		change();
		train(null);
		$('#sub').val('<?=_('Compare');?>');
		$('#user').focus();
		return false;
	});
	$('.nav-list').on('click','a[data-profile]',function(){
		var $this=$(this);
		$('.nav-list li').removeClass('active');
		$this.parent().addClass('active');
		change();
		train($(this).data('profile'));
		$('.subf').show();
		$('#sub').val('<?=_('Entrenar');?>');
		$('#user').focus();
		return false;
	});
	keystroke.listen('user');
	keystroke.listen('pass');
	$('#sub').click(function(){
		keystroke.match(function(text){
			$('#result').html(text);
		});
		return false;
	});
	$('#drawtrain').click(function(){
		keystroke.drawtrain('training');
		return false;
	});
	$("#showpass").change(function(){
		$('#pass').get(0).type=$("#showpass:checked").size()?'text':'password';
	});
});
			</script>
</div>

		<p id='result' style='font-size:25px'>
		</p>
	<div id='training'>
	</div>
	<button id='drawtrain' class='btn'><?=_('Show Training');?></button>
