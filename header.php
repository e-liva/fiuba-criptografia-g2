<?php
putenv('LC_ALL=es_AR');
setlocale(LC_ALL, 'es_AR');
//bindtextdomain("keystroke", "./locale/");
//textdomain("keystroke");

function ckd_script($name) {
	echo '<script type="text/javascript" src="js/'.$name.'.js"></script>';
}
function ckd_style($name,$id=false) {
	if ($id) {
		$more=" id='$id'";
	} else {
		$more='';
	}
	echo '<link rel="stylesheet" type="text/css" href="css/'.$name.'.css"'.$more.'>';
}
function ckd_dir() {
	return './';
}
function ckd_path() {
	return './';
}



?><!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Demo de Keystroke Dynamics</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/ckd/public/design/bootstrap/css/bootstrap.css" rel="stylesheet">
<script src="/ckd/public/design/bootstrap/js/jquery-1.8.2.min.js"></script>
<link href="/ckd/public/design/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<script src="/ckd/public/design/bootstrap/js/bootstrap.js"></script>

<!-- IE Fix for HTML5 Tags -->
<!--[if lt IE 9]>
				<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
				<![endif]-->
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a data-target=".subnav-collapse" data-toggle="collapse" class="btn btn-navbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</a>
			<!--<span class="brand"><a href="http://es.cloodie.com">-->
			<!--<img src="http://www.cloodie.com/wp-content/uploads/2012/10/cloo3.png" alt="Cloodie Hospital Information System">-->
			</a>
			</span>
			<div class="nav-collapse subnav-collapse">
				<ul class="nav float-none">
					<li><a href=".">Demo principal</a></li>
					<li><a href="advanced.php">Demo avanzada</a></li>
					<li><a href="profile.php">Demo por perfiles</a></li>
					<!--<li><a href="keylogger.php">Demo keylogger</a></li>-->
					<!--<li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#">Acerca de <b class="caret"></b></a>
					<ul class="dropdown-menu float-none">
						<li><a href="/">Demo HIS</a></li>
						<li><a href="http://es.cloodie.com/contacto/">Contactarse</a></li>
						<li><a href="js/keystroke.adv.min.js">Código Fuente</a></li>
						<li><a href="http://www.cloodie.com">Cloodie in English</a></li>
					</ul>
					</li>-->
				</ul>
			</div>
			<!-- /.nav-collapse -->
		</div>
	</div>
	<!-- /navbar-inner -->
</div>
