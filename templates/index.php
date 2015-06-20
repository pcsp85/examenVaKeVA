<?php if(!defined('examenTemplate')) die('Acceso negado'); ?>
<h2>Inicio</h2>
<div class="row">
<div class="col-sm-6">
	<div class="well">
		<h3>Cifras capturadas</h3>
		<?php $E->renderPartial('parts/cifras', true, $E->getNumbers()); ?>
	</div>
</div>
<div class="col-sm-6">
	<div class="well">
		<h3>Capturar cifra</h3>
		<p>Por favor ingresa una cifra, debes considerar lo siguente:
			<ul>
				<li>Debe ser un valor numérico</li>
				<li>No debes repetir cifras</li>
			</ul>
		</p>
		<form name="numbers">
			<div class="input-group">
				<input name="number" type="text" class="form-control" placeholder="Ingresa una cifra" required pattern="[0-9]{0,5}" maxlength="5">
				<span class="input-group-btn">
					<button class="btn btn-default">Guardar</button>
				</span>
			</div>
		</form>
	</div>
</div>
</div>