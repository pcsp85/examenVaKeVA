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

	public $errors		= array();	//@Array Errores

	public $messages	= array();	//@Array Mensajes

	private $db			= null;		//@Object Base de Datos

 	public $user_data	= null;		//@object Datos del usuario
	/**
	 * Function __construct
	 * Inicializaicion del objeto examen 
	 */
	public function __construct(){
		$this->home = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'])
		session_start();
		require_once('../config/DB.php'); //Datos para conexion DB
		$this->db = new mysqli(DB_HOST, DB_USER, DB_PSWD, DB_NAME); //Inicializando PDO
		if($this->db->connect_errorno){
			$this->errors[] = 'Error al establecer la conexion con la Base de Datos';
		}

		$this->install();

	}

	/**
	 * Function install
	 * Crea la tablas necesarias para el funcionamiento, sólo en caso de que no existan. 
	 */
	private function install(){
		$sql = "SHOW TABLES LIKE 'users'";
		$chk = $this->db->query($sql);
		if($chk->num_rows == 0){
			$sql = "CREATE TABLE IF NOT EXISTS `users` ( `id` varchar(5) NOT NULL,  `FB_id` int(20) NOT NULL,  `name` varchar(120) NOT NULL,  `first_name` varchar(60) NOT NULL,  `last_name` varchar(60) NOT NULL,  `link` varchar(200) NOT NULL,  `birthday` date NOT NULL,  `last_modify` datetime NOT NULL,  `last_access` datetime NOT NULL,  `block` varchar(15) NOT NULL,  UNIQUE KEY `id` (`id`)) ENGINE=MyISAM;";
			if($this->db->query($sql) === TRUE){
				$this->messages[] = 'Se creo la tabla de usuarios'; 
			}
		}

		$sql = "SHOW TABLES LIKE 'numbers'";
		$chk = $this->db->query($sql);
		if($chk->num_rows == 0){
			$sql =  "CREATE TABLE IF NOT EXISTS `numbers` "
		}
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
	 * Inicia la sesión, se utiliza el PHP_SDK descargado de http://github/facebook/facebook-php-sdk-v4 para utilizar FB connect en el registro/Login
	 */
	public function Login(){
		require_once('../config/FB.php') //Datos para inicializacion de Facebook SDK
		define('FACEBOOK_SDK_V4_SRC_DIR', 'facebook-php-sdk-v4-4.0-dev/src/Facebook/');
		require __DIR__ . '/facebook-php-sdk-v4-4.0-dev/autoload.php';

		use Facebook\FacebookSession;
		use Facebook\FacebookRedirectLoginHelper;
		use Facebook\FacebookRequest;
		use Facebook\GraphUser;
		use Facebook\FacebookRequestException;

		FacebookSession::setDefaultApplication(FB_APP_ID, FB_APP_SECRET);

		$helper =  new FacebookRedirectLoginHelper($this->home);

		try {
			$session = $helper->getSessionFromRedirect();
		} catch(FacebookRequestException $ex) {
			$this->errors [] = 'Error: ' . $ex->getCode() .' -> '. $ex->getMessage();
		} catch(\Exception $ex){
			$this->errors [] = "Error al iniciar sesión";
		}

		if($session){
		  try {
			$_SESSION['examenSession'] = 1;
			$user_data =( new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());
			$this->user_data = (object) array(
				'FB_id'			=> $user_data->getId(),
				'name'			=> $user_data->getName,
				'first_name'	=> $user_data->getFirstName,
				'last_name'		=> $user_data->getLastName,
				'link'			=> $user_data->getLink,
				'birthday'		=> $user_data->getBirthday
				);
				$sql = "SELECT * FROM `users` WHERE `FB_id` = $this->user_data->FB_id";
				$chk = $this->db->query($sql);
				if($chk->num_rows == 1){
					$user_data_local = $chk->fetch_object();
					$user_data->'id' = $user_data_local->id;
				}else{
					$id = $this->createUserId();
					$sql = "INSERT INTO `users` (`id`, `FB_id`, `name`, `first_name`, `last_name`, `link`, `birthday`, `last_modify`) VALUES ('$id', '$this->user_data->FB_id', '$this->user_data->name', '$this->user_data->first_name', '$this->user_data->last_name', '', '', '')";
				}
		  } catch(FacebookRequestException $e) {
		  	$this->errors [] = 'Error: ' . $e->getCode() .' -> '. $e->getMessage();
		  }
		}
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
			for ($i=0,$i<5;$i++){
				$pos = rand(0, strlen($chars)-1);
				$id .= substr($chars, $pos, 1);
			}
			$sql = "SELECT `id` FROM `users` WHERE `id` LIKE  '$id'";
			$chk = $this->db->query();
		} while ($chk->num_rows==1);

		return $id;
	}

	/**
	 * Function LoginUrl
	 * Retorna la url para el acceso mediante FB connect
	 */

	public function LoginUrl(){
		require_once('../config/FB.php') //Datos para inicializacion de Facebook SDK
		define('FACEBOOK_SDK_V4_SRC_DIR', 'facebook-php-sdk-v4-4.0-dev/src/Facebook/');
		require __DIR__ . '/facebook-php-sdk-v4-4.0-dev/autoload.php';

		use Facebook\FacebookSession;
		use Facebook\FacebookRedirectLoginHelper;

		$helper = new FacebookRedirectLoginHelper($this->home);
		return $helper->getLoginUrl();
	}

	/**
	 * Function Logout
	 * Termina la sesión actual.
	 */
	public function Logout($redirect_uri=null){
		$this->user_data = null;
		session_destroy();
		if($redirect_uri!=null) header('Location: '.$$redirect_uri);
	}

}