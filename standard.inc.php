<?php
ckd_style('standalone');
ckd_script('keystroke.min');
ckd_style('keystroke');
?><div id="contenido" class="container">
	<div class="hero-unit">
		<h1>Demo de Cadencia de tecleo (Keystroke dynamics)</h1>
		<p>
			Esta demostración valida si la persona es quien dice ser.
		</p>
		<form class="form-horizontal" autocomplete="off">
			<div class="control-group">
				<label class="control-label" for=mail>E-mail</label>
				<div class="controls">
					<input id="mail" type='text' autocomplete="off">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for='pass1'>Contraseña</label>
				<div class="controls">
					<input id="pass1" type='password'>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for='pass2'>Verificar contraseña</label>
				<div class="controls">
					<input id="pass2" type='password'>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<input id='sub' type='submit' value="Registrar" class='btn btn-large btn-primary'>
				</div>
			</div>
			<script>
$(function(){
	var modo='train';
	var keystroke=new cAPI(function(){
		return modo;
	});
	keystroke.listen('mail');
	keystroke.listen('pass1');
	keystroke.listen('pass2');

	function clean(){
		$('#mail,#pass1,#pass2')
			.val('')
			.first().focus();
	}
	function validate(){
		var
			$pass1=$('#pass1'),
			$pass2=$('#pass2'),
			$mail=$('#mail'),
			result=true,
			$f=$('form');

		$f.find('.control-group').removeClass('warning');
		$f.find('span.help-inline').remove();
		if (!$mail.val().match(/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/)) {
			$mail.closest('.control-group').addClass('warning');
			$mail.after('<span class="help-inline">Por favor ingrese un E-mail válido</span>');
			result=false;
		}
		if ($pass1.val()=='') {
			$pass1.closest('.control-group').addClass('warning');
			$pass1.after('<span class="help-inline">Se requiere una contraseña</span>');
			result=false;
		}
		if ($pass1.val()!=$pass2.val()) {
			$pass2.closest('.control-group').addClass('warning');
			$pass2.after('<span class="help-inline">Las contraseñas deben coincidir</span>');
			result=false;
		}
		return result;
	}

	var mail,pass;

	$('#sub').click(function(){
		switch(modo) {
		case 'train':
			if (!validate()) return false;
			modo='run';
			mail=$('#mail').val();
			pass=$('#pass1').val();
			clean();
			$('#pass2').closest('.control-group').remove();
			$(this).val('Ingresar');
			break;
		case 'run':
			if (mail!=$('#mail').val() || pass!=$('#pass1').val()) {
				$('#result').html("E-mail o contraseña incorrectos");
			} else {
				clean();
				keystroke.run(function(text){
					$('#result').html(text);
				});
			}
			break;
		}
		
		return false;
	});
	$('#drawtrain').click(function(){
		keystroke.drawtrain('training');
		return false;
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
	<button id='drawtrain' class='btn'>Mostrar entrenamiento</button>

