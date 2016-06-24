<?php
ckd_style('standalone');
ckd_style('jquery.ibutton');
ckd_script('jquery.ibutton.min');
ckd_script('keystroke.min');
ckd_style('keystroke');
?><div id="contenido" class="container">
	<div class="hero-unit">
<h1>Demo de Cadencia de tecleo (Keystroke dynamics)</h1>
		<p>
			Esta demostración valida si la persona es quien dice ser.
		</p>
		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for=user>Usuario</label>
				<div class="controls">
					<input id="user" type='text'>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for='pass'>Contraseña</label>
				<div class="controls">
					<input id="pass" type='text'>
					<label class="checkbox">
					<input type="checkbox" id='showpass' checked='checked'> Mostrar contraseña </label>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for='tttr'>Modo</label>
				<div class="controls">
					<input type='checkbox' value='run' id='tttr'>
					<span class="help-block">Te recomendamos usar el modo RUN.</span>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<input id='sub' type='submit' value='Ejecutar' class='btn btn-large btn-primary'>
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
	<h1>Información avanzada</h1>
	<div id='training'>
	</div>
	<button id='drawtrain' class='btn'>Mostrar Entrenamiento</button>
