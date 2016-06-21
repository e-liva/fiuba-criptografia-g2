<?php
ckd_style('standalone');
ckd_style('jquery.ibutton');
ckd_script('jquery.ibutton.min');
ckd_script('keystroke.min');
ckd_style('keystroke');
?><div id="contenido" class="container">
	<div class="hero-unit">
<h1><?=_('Demo de Keystroke Dynamics');?></h1>
		<p>
			<?=_('This demonstration validates if the person is who he says he is');?>
		</p>
		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for=user><?=_('User');?></label>
				<div class="controls">
					<input id="user" type='text'>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for='pass'><?=_('Password');?></label>
				<div class="controls">
					<input id="pass" type='text'>
					<label class="checkbox">
					<input type="checkbox" id='showpass' checked='checked'> <?=_('Show Password');?> </label>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for='tttr'><?=_('Use mode');?></label>
				<div class="controls">
					<input type='checkbox' value='run' id='tttr'>
					<span class="help-block"><?=_("Te recomendamos usar el modo run");?></span>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<input id='sub' type='submit' value='<?=_('Ejecutar');?>' class='btn btn-large btn-primary'>
				</div>
			</div>
			<script>
$(function(){
	var keystroke=new cAPI(function(){
		return $('#tttr:checked').size()?'run':'train';
	});
	keystroke.listen('user');
	keystroke.listen('pass');
	$('#sub').click(function(){
		keystroke.run(function(text){
			$('#result').html(text);
		});
		return false;
	});
	$('#drawtrain').click(function(){
		keystroke.drawtrain('training');
		return false;
	});
	$("#tttr").iButton({
		labelOn: "RUN",
		labelOff: "TRAIN"
	});
	$("#showpass").change(function(){
		$('#pass').get(0).type=$("#showpass:checked").size()?'text':'password';
	});
});
			</script>
		</form>
		<p id='result' style='font-size:25px'>
		</p>
	</div>
	<h1><?=_('Advanced Information');?></h1>
	<div id='training'>
	</div>
	<button id='drawtrain' class='btn'><?=_('Mostrar Entrenamiento');?></button>
