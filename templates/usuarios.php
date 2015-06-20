<?php if(!defined('examenTemplate')) die('Acceso negado');
if(!isset($E->params[1]) || $E->params[1] == ''):
 ?>
<h2>Usuarios</h2>
<?php $E->renderPartial('parts/usuarios', true, $E->getUsers()); 
else: 
	$E->renderPartial('parts/usuario', true, $E->getUserData($E->params[1]));
endif; ?>