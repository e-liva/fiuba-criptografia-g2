<?
//ckd_style('standalone');
ckd_style('keystroke');
ckd_script('ckd.min');

ckd_script('bootstrap-datepicker');
ckd_style('datepicker');
?>
<div id="contenido" class="container">
	<div class="hero-unit">

		<h1>Experimento de Cadencia de tecleo</h1>
		<p>
			Muchas gracias por participar, el proceso es muy sencillo y no tomará más de 3 minutos.
		</p>

		<div class="progress">
		  <div class="bar" style="width: 0;"></div>
		</div>

		<form class="form-horizontal">

			<div id='step1'>

				<div class="control-group">
					<label class="control-label" for=name>Nombre</label>
					<div class="controls">
						<input data-prog='1' autocomplete="off" id="name" type='text' class='cl'>
						<span class="help-block">Su nombre es opcional, puede dejar un apodo si lo desea, el objetivo es no confundir las pruebas.</span>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Con que mano escribe?</label>
					<div class="controls" id='ctrlhw'>
						<label class="radio inline">
						  <input data-prog='5' type="radio" name="hw" value="none"> ¡No escribo!
						</label>
						<label class="radio inline">
						  <input data-prog='5' type="radio" name="hw" value="left"> Izquierda
						</label>
						<label class="radio inline">
						  <input data-prog='5' type="radio" name="hw" value="right"> Derecha
						</label>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for=fnac>Fecha de nacimiento</label>
					<div class="controls" id='ctrlfnac'>

						<div class="input-prepend class-prepend">
							<span class="add-on">
								<i class="icon-calendar"></i>
							</span>
							<input data-prog='8' id="fnac" class="input-small" type="text" data-date-format="dd/mm/yyyy"> 
						</div>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Sexo</label>
					<div class="controls" id='ctrlsex'>
						<label class="radio inline">
					  	<input data-prog='10' type="radio" name="sex" value="female"> Mujer
						</label>
						<label class="radio inline">
						  <input data-prog='11' type="radio" name="sex" value="male"> Hombre
						</label>
					</div>
				</div>

			</div>


			<div id='step2'>
				<p class='datatext'>El siguiente formulario es para autentificarse en el sistema. Tipee el usuario <span class='label label-info label-user label-field' unselectable='on'>jperez@gmail.com</span> y la contraseña <span class='label label-info label-pass label-field' unselectable='on'>esta es una prueba 576</span>. <span class='count'>Tip: Utilice tab para pasar de campo.</span></p>
				<div class="control-group">
					<label class="control-label" for=user>Usuario</label>
					<div class="controls">
						<input autocomplete="off" id="user" type='text' class='listen'>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for='pass'>Contraseña</label>
					<div class="controls">
						<input autocomplete="off" id="pass" type='text' class='listen'>
					</div>
				</div>
			</div>

			<div id='step3'>
				<h2>Listo</h2>
				<p>¡Muchas gracias por participar del experimento!</p>
				<p>Si querés recibir una copia del estudio que se realizará con esta información dejanos tu correo electrónico. <b>Esto es solo por si tenés curiosidad</b>, no es obligatorio.</p>
				<div class="control-group">
					<label class="control-label" for=mail>E-mail</label>
					<div class="controls">
						<input id="mail" type='text' class='cl'>
					</div>
				</div>
			</div>


			<div class="control-group">
				<div class="controls">
					<input type='submit' class='btn btn-large btn-primary' id='next' value='Continuar'>
				</div>
			</div>
		</form>





	</div>
 
<script>

$(function(){

	var
		api=new CKD(),
		step=0,
		base_progress=5,
		out=[],
		fnac = $('#fnac').datepicker().on('changeDate', function(ev) {
		  fnac.blur();
			fnac.closest('.control-group').removeClass('error');
		}),
		total=0;
	$('.datatext .label').each(function(){
		total+=$(this).text().length;
	})

	api.listen('.listen');
	resetForm();

	$('input[data-prog]').focus(function(){
		progress($(this).data('prog'));
	});

	$('#step2,#step3').hide();
	$('#name').focus();

	$('input[type=radio]').change(function(){
		$(this).closest('.control-group').removeClass('error');
	});

	$('.listen').change(function(){
		checkField($(this));
	});

	function checkField($input) {
		val=$input.val(),
		shouldbe=$('.label-'+$input.attr('id')).text();
		if (val==shouldbe) {
			$input.closest('.control-group').removeClass('error');
			return true;
		} else {
			$input.closest('.control-group').addClass('error');
			return false;
		}
	}

	function checkError() {
		var error=false;
		$('.listen').each(function(){
			if (!checkField($(this))) error=true;
		});
		return error;
	}

	$('form').submit(function(){
		if (step==0) {
			//validate
			var error=false;
			if (!$('input[name="sex"]:checked').size()) {
				$('#ctrlsex').parent().addClass('error');
				error=true;
			}
			if (!$('input[name="hw"]:checked').size()) {
				$('#ctrlhw').parent().addClass('error');
				error=true;
			}
			if (!/^[0-3]?[0-9]\/[0-1]?[0-9]\/[0-9]{2,4}$/.test($('#fnac').val())) {
				var $ctrl=$('#ctrlfnac');
				$ctrl.find('.help-inline').remove();
				error='Sexo no especificado';
				$ctrl.append('<span class="help-inline">Ingrese una fecha válida</span>');
				$ctrl.parent().addClass('error');
				error=true;
			}
			if (error) {
				return false;
			}
			$('#step1').hide();
			$('#step2').show();
		} else if (step==-1) {
			savemail();
			resetForm();
			out=[];
			$('#step1').show();
			$('#step3').hide();
			step=0;
			base_progress=5;
			$('.progress .bar').data('current',0);
			progress(0);
			$('#name').focus();
			$('#next').val('Continuar')
			$('#mail').val('');
			return false;
		} else if (step==12) {
			if (checkError()) return false;
			train();
			progress(100);
			finish();
			$('#step2').hide();
			$('#step3').show();
			$('#mail').focus();
			step=-1;
			$('#next').val('Reiniciar')
			return false;
		} else {
			if (checkError()) return false;
			var times=12-step,text;
			if (times==1) {
				text='Deberá ingresar este código por última vez.';
			} else {
				text="Deberá ingresar nuevamente este código "+times+" veces.";
			}
			$('.count').html("<p>"+text+"</p>");
			progress(base_progress);
			train();
			$('.listen').each(function(){
				$(this).val('');
			});
		}
		step++;
		$('#user').focus();
		base_progress+=90/12;
		return false;
	});

	function resetForm() {
		$('#step1 input[type=text]').each(function(){
			$(this).val('');
		});
		$('input[type=radio]').removeAttr('checked');
	}
	$('#step2 input').keyup(function(){
		var total2=0;
		$('#step2 input').each(function(){
			total2+=$(this).val().length;
		})
		var prog=(total2/total);
		if (prog>1) prog=1;
		progress(base_progress+prog*90/12);
	});
	
	$('#mail').keyup(function(){
		var
			valid=isMailValid($(this).val()),
			$controlGroup=$(this).closest('.control-group'),
			emp=$(this).val()=='';
	
		if (valid || emp) {
			$controlGroup.removeClass('warning');
		} else {
			$controlGroup.addClass('warning');
		}
	
		if (valid) {
			$('#next').val('Guardar mail y reiniciar')
		} else {
			$('#next').val('Reiniciar')
		}
	});
	
	function nopaste(e) {
	  $('.count').text('Usar copy/paste arruina el experimento, por favor tipeá los textos.');
	  e.preventDefault();
	}
	$('.listen').bind('copy paste cut',nopaste);
	$('.datatext').bind('select selectstart copy cut',nopaste);
	
	function isMailValid(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	} 
	function progress(value) {
		var $bar=$('.progress .bar');
		var val_c=$bar.data('current');
		if (val_c==undefined) val_c=0;
		value=val_c>value?val_c:value;
		$bar.data('current',value);
		$bar.css('width',value+'%');
	}
	function train() {
		out.push(api.get());
		api.clean();
	}
	function finish() {
		var params={}
		params['name']=$('#name').val();
		params['gender']=$('input[name="sex"]:checked').val();
		params['dexterity']=$('input[name="hw"]:checked').val();
		params['birth']=$('#fnac').val();
		params['data']=out.join(',');
		$.ajax({
			url: '<?=ckd_path();?>ajax.php?op=experiment',
			type: 'POST',
			data: params
		});
	}
	
	function savemail() {
		var params={};
		if (!isMailValid($('#mail').val())) return;
		params['name']=$('#name').val();
		params['mail']=$('#mail').val();
		$.ajax({
			url: '<?=ckd_path();?>ajax.php?op=savemail',
			type: 'POST',
			data: params
		});
	}
	

});



</script>


	</div>

