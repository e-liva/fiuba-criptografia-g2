<?php
ckd_style('standalone');
ckd_style('keystroke');
ckd_script('kl.min');
ckd_script('bootstrap-editable.min');
ckd_style('bootstrap-editable');

?>
<div id="contenido" class="container">
	<div class="hero-unit">

		<h1><?=_('Demo de API de Keystroke Dynamics');?></h1>
		<p>
			<?=_('According to the keystroke dynamics...');?>
		</p>



<section class='special' id="testzone">
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
		<p><?=_('This place is available for the custom forms your company wants to add.');?></p>
	</div>
  <div class="tab-pane" id="tcaptcha">
		<p><?=_('Coming soon: Solve the captcha!');?></p>
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

	var keylogger=new Keylogger('input,textarea','#nfo','text');
});



</script>

</section>


<section class='special' id='datatable'>
<h2>Information retrieved</h2>

<p id='nfo'></p>



</section>


	</div>

