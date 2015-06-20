<?php //error_reporting(0);
require_once('classes/Examen.php');
$E = new Examen();

$json = false;
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
	
	case 'logout':
		$E->Logout();
		$response = 'success';
		break;

	case 'addNumber':
		$response = $E->addNumber($number);
		$json = true;
		break;

	case 'getNumbers':
		$response = $E->renderPartial('parts/cifras', false, $E->getNumbers());
		break;

	default:
		$response = array(
			'result' => 'error',
			'message' => 'Función inválida'
			);
		$json=true;
		break;
}

if($json==true){
	$response = json_encode($response);
}

echo $response;