<?php if(!defined('examenTemplate')) die('Acceso negado');
error_reporting(0);
$e  = new Examen();
if(count($E)>0): ?>
	<div class="list-group">
		<?php foreach ($E as $field): ?>
			<a class="list-group-item" href="<?=$e->home;?>/usuarios/<?=$field['id'];?>"><?=$field['name'];?></a>
		<?php endforeach; ?>
	</div>
<?php else: ?>
	No hay usuarios registrdos
<?php endif;?>