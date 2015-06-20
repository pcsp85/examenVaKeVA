<?php if(!defined('examenTemplate')) die('Acceso negado'); ?>
<?php if($E!=false): ?>
<table class="cifras">
	<thead>
		<tr>
			<th>Cifra 
				<i class="tip fa fa-chevron-circle-down" title="Ordenar mayor a menor" data-order="DESC" data-orderby="number"></i>
				<i class="tip fa fa-chevron-circle-up" title="Ordenar menor a mayor" data-order="ASC" data-orderby="number"></i>
			</th>
			<th>Fecha
				<i class="tip fa fa-chevron-circle-down" title="Ordenar más reciente" data-order="DESC" data-orderby="date"></i>
				<i class="tip fa fa-chevron-circle-up" title="Ordenar más antiguo" data-order="ASC" data-orderby="date"></i>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($E as $field):?>
		<tr>
			<td><?=$field['number'];?></td>
			<td><?=$field['date'];?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
No has registrado cifras
<?php endif; ?>