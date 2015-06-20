<?php if(!defined('examenTemplate')) die('Acceso negado');?>
<h2><?=$E->name;?></h2>
<div class="row">
	<div class="col-sm-9">
		<ul class="list-group">
			<li class="list-group-item">
				<span>Nombre</span>
				<span class="pull-right"><?=$E->first_name;?></span>
			</li>
			<li class="list-group-item">
				<span>Apellidos</span>
				<span class="pull-right"><?=$E->last_name;?></span>
			</li>
			<li class="list-group-item">
				<span>e-mail</span>
				<span class="pull-right"><?=$E->email;?></span>
			</li>
			<li class="list-group-item">
				<span>Género</span>
				<span class="pull-right"><?=$E->gender =='male' ? 'Hombre' : 'Mujer';?></span>
			</li>
			<li class="list-group-item">
				<span>Link</span>
				<span class="pull-right"><?=$E->link;?></span>
			</li>
			<li class="list-group-item">
				<span>Última actializacion:</span>
				<span class="pull-right"><?=$E->last_modify;?></span>
			</li>
			<li class="list-group-item">
				<span>Último acceso:</span>
				<span class="pull-right"><?=$E->last_access;?></span>
			</li>
			<li class="list-group-item">
				<span class="badge"><?=$E->n_cifras;?></span>
				<span>cifras guardadas</span>
			</li>
			<?php if($E->block!=''): ?>
			<li class="list-group-item list-group-item-danger text-center">
				El usuario se encuentra bloqueado, podrá guardar mas cifras a partir de <strog><?=date('Y-m-d H:i', $E->block);?></strong>
			</li>
			<?php endif;?>
		</ul>
	</div>
</div>
