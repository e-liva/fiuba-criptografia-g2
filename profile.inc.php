<?php
ckd_style('standalone');
ckd_script('keystroke.min');
ckd_style('keystroke');
?><div id="contenido" class="container">
	<div class="hero-unit">
<h1>Cadencia de tecleo por perfil</h1>
		<p>
			Un usuario será identificado de acuerdo a la cadencia de tecleo.
		</p>
<div class='container'>
<div class='row'>
<div class='span4 nav-sidebar'>

<div class="navig">
	<ul class="nav nav-list">
		<li class='disabled'><a href="#" id='startrun'><i class="icon-repeat"></i> Comparar</a></li>
		<li><a href="#" id='addprofile'><i class="icon-plus-sign"></i> Nuevo perfil</a></li>
	</ul>
</div>

</div>
<div class='span8 subf'>
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
				<div class="controls">
					<input id='sub' type='submit' value='Ejecutar' class='btn btn-large btn-primary'>
				</div>
			</div>
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
		var prof=window.prompt("Ingrese el nombre del nuevo perfil","");
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
		$('#sub').val('Comparar');
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
		$('#sub').val('Entrenar');
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
		</form>
</div>
</div>
</div>

		<p id='result' style='font-size:25px'>
		</p>
	</div>
	<h1>Información avanzada</h1>
	<div id='training'>
	</div>
	<button id='drawtrain' class='btn'>Mostrar entrenamiento</button>
