<?php if(!defined('examenTemplate')) die('Acceso negado'); ?>
<div class="list-group">
	<a href="<?=$E->home;?>" class="list-group-item">Inicio</a>
	<a href="<?=$E->home;?>/usuarios" class="list-group-item <?php echo !isset($E->params[1]) || $E->params[1] == '' ? 'active': '';?>">Lista de usuarios</a>
</div>