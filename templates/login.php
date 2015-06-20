<?php if(!defined('examenTemplate')) die('Acceso negado'); ?>

<div class="login text-center">
    <?php if(count($E->errors)>0): ?>
        <div class="alert alert-warning">
            <ul>
                <?php foreach($E->errors as $e): ?><li><?=$e;?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if(count($E->messages)>0): ?>
        <div class="alert alert-success">
            <ul>
                <?php foreach($E->messages as $m): ?> <li><?=$m;?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

	<h2> <i class="fa fa-list-alt"></i> Examen Técnico</h2>
	<fb:login-button scope="public_profile,email" onlogin="checkLoginState();"> Iniciar Sesión
	</fb:login-button>

	<div id="status">
	</div>
</div>