<?php //error_reporting(0);
require_once('classes/Examen.php');
$E = new Examen();

if(isset($_GET)) extract($_GET);
if(isset($_POST)) extract($_POST);

switch ($action) {
	case 'login':
		$response = array(
			'result' => !$E->login($_POST) ? 'error' : 'success',
			'message' => count($E->errors)>0 ? $E->toList($E->errors) : ''
			);
		$json=true;
		break;
	
	default:
		$response = array(
			'result' => 'errror',
			'message' => 'Función inválida'
			);
		$json=true;
		break;
}

if($json==true){
	$response = json_encode($response);
}

echo $response;