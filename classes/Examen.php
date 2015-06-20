<?php
/**
 * Class Examen
 * Clase par exctamen técnico
 * @author Pablo César Sánchez Porta
 * @license http://fsf.org/ GNU License
 */
class Examen
{
	public $home		= '';		//@String URL del home

	public $path 		= '';		//@String Ruta del directorio de la aplicacion

	public $errors		= array();	//@Array Errores

	public $messages	= array();	//@Array Mensajes

	private $db			= null;		//@Object Base de Datos

 	public $user_data	= null;		//@object Datos del usuario
	/**
	 * Function __construct
	 * Inicializaicion del objeto examen 
	 */
	public function __construct($isHome=false){
		define('examenTemplate', 1);
		session_start();
		$this->home = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
		$this->path = dirname(__FILE__) . '/../';
		require_once($this->path . 'config/DB.php'); //Datos para conexion DB
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PSWD, DB_NAME); //Inicializando PDO
		if($this->db->connect_errno){
			$this->errors[] = 'Error al establecer la conexion con la Base de Datos';
		}

		$this->install();
		if($isHome==true){
			if(!$this->isLogedin()){
				$this->renderPartial('header', true, $this);
				$this->renderPartial('login', true, $this);
	            $this->renderPartial('footer', true, $this);
			}else{
	            $this->params = isset($_GET['params']) ? explode('/', $_GET['params']) : array(0=>'index');
	            $this->render($this->params[0], $this);
			}
		}
	}

	/**
	 * Function install
	 * Crea la tablas necesarias para el funcionamiento, sólo en caso de que no existan. 
	 */
	private function install(){
		$sql = "SHOW TABLES LIKE 'users'";
		$chk = $this->db->query($sql);
		if($chk->num_rows == 0){
			$sql = "CREATE TABLE IF NOT EXISTS `users` ( `id` varchar(5) NOT NULL,  `FB_id` varchar(20) NOT NULL,  `name` varchar(120) NOT NULL,  `first_name` varchar(60) NOT NULL,  `last_name` varchar(60) NOT NULL, `email` varchar(120) NOT NULL, `gender` varchar(5) NOT NULL,  `link` varchar(200) NOT NULL,  `last_modify` datetime NOT NULL,  `last_access` datetime NOT NULL,  `block` varchar(15) NOT NULL,  UNIQUE KEY `id` (`id`)) ENGINE=MyISAM;";
			if($this->db->query($sql) === TRUE){
				$this->messages[] = 'Se creo la tabla de usuarios'; 
			}
		}

		$sql = "SHOW TABLES LIKE 'numbers'";
		$chk = $this->db->query($sql);
		if($chk->num_rows == 0){
			$sql =  "CREATE TABLE IF NOT EXISTS `numbers` (`number` int(5), `user_id` varchar(5), `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, UNIQUE KEY `number` (`number`)) ENGINE = MyISAM";
			if($this->db->query($sql) === TRUE){
				$this->messages[] = 'Se creo la tabla para el regustro de números';
			}
		}
	}

	/**
	 * Function renderPartial
	 * Genera HTML de la vista de una plantilla parcialmente
	 */
	public function renderPartial($template, $echo=true, $E=null){
		$template_path = $this->path . 'templates/' . $template .'.php';
		$response = '';
		if(is_file($template_path)){
			ob_start();
			include($template_path);
			$response = ob_get_contents();
			ob_end_clean();
			if($echo == true){
				echo $response;
			}else{
				return $response;
			}
		}else{
			return 'false';
		}
	}

	/**
	 * Function render
	 * Genera HTML total de una pagina
	 */
	public function render($template, $E=null){
		$template_path = $this->path . 'templates/' . $template . '.php';
		$aside_path = $this->path . 'templates/aside/' . $template . '.php';

		$response = $this->renderPartial('header', false, $E);
		$response .= '<div class="row">';
		if(is_file($aside_path)){
			$response .= '<div class="col-sm-3 pull-left">';
			$response .= $this->renderPartial('aside/'.$template, false, $E);
			$response .= '</div><div class="col-sm-9 pull-left">';
		}else{
			$response.= '<div class="col-sm-12 pull-left">';
		}
		$response .= '<div class="well">';

		if(count($this->errors)>0){
			$response .= '<div class="alert alert-box"><ul>';
			foreach ($this->errors as $e) {
				$response .= '<li>'+$e+'</li>';
			}
			$response .= '</ul></div>';
		}

		if(count($this->messages)>0){
			$response .= '<div class="alert alert-success alert-box"><ul>';
			foreach ($this->messages as $m) {
				$response .= '<li>'+$m+'</li>';
			}
			$response .= '</ul></div>';
		}

		if(is_file($template_path)){
			$response .= $this->renderPartial($template, false, $E); 
		}else{
			$response .= $this->renderPartial('error', false, $E);
		}

		$response .= '</div></div></div>';
		$response .= $this->renderPartial('footer', false, $E);

		echo $response;
	}

	/**
	 * Function isLogedin
	 * Verifica si la sesión de usuario esta activa
	 */
	public function isLogedin(){
		if(isset($_SESSION['examenSession']) && $_SESSION['examenSession'] == 1) return true;
		return false;
	}

	/**
	 * Function Login
	 * Inicia la sesión, crea el usuario en DB Local o actualiza en caso de diferencias
	 */
	public function Login($user_data){
		if($user_data['action']!='login') $this->errors[] = 'Función inválida';
		$user_data['action'] = false;
		$sql = "SELECT * FROM `users` WHERE `FB_id` = $user_data[id]";
		$chk = $this->db->query($sql);
		if($chk->num_rows == 1){
			//Si ya existe el usuario en la Base de datos local verifica si existen diferencias y actualiza base de datos
			$user_data_local = $chk->fetch_assoc();
			$user_data['id'] = $user_data_local['id'];
			$user_data['FB_id'] = $user_data_local['FB_id'];
			$diff = array_diff_assoc($user_data_local, $user_data);
			if(count($diff)>0){
				$set = '';
				foreach ($diff as $key => $value) if($key != 'action'){
					$set .= " `$key` = '$value',";
				}
				$set = substr($set, 0, -1);
				$sql = "UPDATE `user` $set WHERE `id` LIKE '$user_data[id]'";
				if($set!='') $this->db->query($sql);
			}
		}else{
			//Crea el registro del usuario en la Bse de datos
			$user_data['FB_id'] = $user_data['id'];
			$user_data['id'] = $this->createUserId();
			$sql = "INSERT INTO `users` (`id`, `FB_id`, `name`, `first_name`, `last_name`, `email`, `gender`, `link`, `last_modify`) VALUES ('$user_data[id]', '$user_data[FB_id]', '$user_data[name]', '$user_data[first_name]', '$user_data[last_name]', '$user_data[email]', '$user_data[gender]', '$user_data[link]', NOW())";
			$this->db->query($sql);
		}
		//Actualiza campo de de último acceso
		$sql = "UPDATE `users` SET `last_access` = NOW() WHERE `id` LIKE '$user_data[id]'";
		$this->db->query($sql);
		$this->user_data = $user_data;
		$_SESSION['user_data'] = $user_data;
		$_SESSION['examenSession'] = 1;

		if(count($this->errors)==0) return true;
		else return false;
	}

	/**
	 * Function createUserId
	 * Retorna un identificador único, generado aleatoriamente, 5 caracteres alfanumericos.
	 * Verifica que no se encuentre registrado en la base de datos
	 */
	private function createUserId(){
		$chars = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
		do {
			$id = '';
			for ($i=0;$i<5;$i++){
				$pos = rand(0, strlen($chars)-1);
				$id .= substr($chars, $pos, 1);
			}
			$sql = "SELECT `id` FROM `users` WHERE `id` LIKE  '$id'";
			$chk = $this->db->query($sql);
		} while ($chk->num_rows==1);

		return $id;
	}

	/**
	 * Function Logout
	 * Termina la sesión actual.
	 */
	public function Logout($redirect_uri=null){
		$this->user_data = null;
		$_SESSION['examenSession'] = false;
		session_destroy();
		if($redirect_uri!=null) header('Location: '.$$redirect_uri);
	}

	/**
	 * Function addNumber
	 * Funcion para agregar registro numérico, realizando las siguentes validaciones:
	 *   -Usuario logueado
	 *   -Bloqueo por errores en captura
	 *   -Contador de errores (timer)
	 *   -Es numérico
	 *   -Se encuentra en la DB
	 */
	public function addNumber($n){
		if(!$this->isLogedin()) $this->errors[] = 'Acceso negado';
		$sql = "SELECT * FROM `numbers` WHERE `number` = $n";
		$chk = $this->db->query($sql);
		if($chk->num_rows>0) $this->errors[] = 'El numero ya esta registrado en la DB';

		if(count($this->errors)==0){
			$user_id = $this->db->real_escape_string($_SESSION['user_data']['id']);
			$sql = "INSERT INTO `numbers` (`number`, `user_id`) VALUES ('$n', '$user_id')";
			if($this->db->query($sql) !== TRUE) $this->errors[] = 'Ocurrio un error al guardar el registro.';
		}

		if(count($this->errors)>0){
			$response = array(
				'result' => 'error',
				'message' => $this->toList($this->errors)
				);
		}else{
			$response  = array(
				'result' => 'success',
				'message' => 'el registro se guardo con éxito'
				);
		}
		return $response;
	}

	/**
	 * Function getNumbers
	 * Funcion obtiene los registros guardado del usuario actual
	 */
	public function getNumbers($params=null){
		if(!$this->isLogedin()) $this->errors[] = 'Acceso negado';
		else{
			$id = $this->db->real_escape_string($_SESSION['user_data']['id']);
			$sql = "SELECT * FROM `numbers` WHERE `user_id` LIKE '$id'";
			$sql .= isset($params['orderby']) && $params['orderby']!='' ? " ORDER BY `$params[orderby]`" : '';
			$sql .= isset($params['order']) && $params['order']!='' ? " $params[order]" : '';
			$data_o = $this->db->query($sql);
			if($data_o->num_rows > 0){
				$data = array(); $n = 0;
				while($row = $data_o->fetch_object()){
					foreach($row as $k => $v){
						$data[$n][$k] = $v;
					}
					$n++;
				}
				return $data;
			}
		}
		return false;
	}

	/**
	 * Funtion toList
	 * Retorna un array en HTML de lista desordenada
	 */
	public function toList($array){
		$ret = '<ul>';
		foreach($array as $k => $v){
			$ret .= '<li>' . $v . '</li>';
		}
		$ret .= '</ul>';
		return $ret;
	}

	/**
	 * Function getUsers
	 * Obtiene listado de usuarios registrados en el sistema
	 */
	public function getUsers(){
		$sql = "SELECT `id`, `name` FROM `users`";

		$data_o = $this->db->query($sql);
		if($data_o->num_rows>0){
			$data = array(); $n = 0;
			while ($row = $data_o->fetch_object()) {
				foreach ($row as $k => $v) {
					$data[$n][$k] = $v;
				}
				$n++;
			}
			return $data;
		}
		return false;
	}

	/**
	 * Function getUserData
	 * Obtiene los datos de usuario y cantidad de cifras registradas
	 */
	public function getUserData($id){
		$id = $this->db->real_escape_string($id);
		$sql = "SELECT * FROM `users` WHERE `id` LIKE '$id'";
		$data_o = $this->db->query($sql);
		if($data_o->num_rows==1){
			$data = $data_o->fetch_object();
			$sql = "SELECT count('id') as n_cifras FROM `numbers` WHERE `user_id` LIKE '$data->id'";
			$data->n_cifras = $this->db->query($sql)->fetch_object()->n_cifras;
			return $data;
		}
		return false;
	}

}