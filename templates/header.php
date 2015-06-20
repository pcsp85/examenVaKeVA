<?php if(!defined('examenTemplate')) die('Acceso negado'); ?>
<!DOCTYPE html>
<html>
<head>
<title>Examen Técnico Pablo César Sánchez Porta </title>
<meta charset="UTF-8">
    <link rel="stylesheet" href="<?=$E->home;?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=$E->home;?>/bower_components/components-font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=$E->home;?>/assets/css/examen.css">
<script>
	var isLogedin = <?=abs($E->isLogedin());?>;
	var home = '<?=$E->home;?>';
</script>
</head>
<body>
<?php if($E->isLogedin()): ?>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		            <span class="sr-only">Toggle navigation</span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
	          	</button>
	          	<a class="navbar-brand" href="<?=$E->home;?>">Examen Técnico</a>
			</div>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a class="logout tip" href="#" data-toggle="tooltip" data-placement="bottom" title="Cerrar sesión">
						<i class="fa fa-power-off "></i>
					</a>
				</li>
			</ul>
			<p id="status" class="nav navbar-text navbar-right"></p>
		</div>
	</nav>
<?php endif; ?>
	<div class="container">
