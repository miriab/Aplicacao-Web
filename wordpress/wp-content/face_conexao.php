<?php
session_start();
unset($_SESSION['face_acess_token']);
require_once 'C:\xampp\htdocs\app\wordpress\wp-content\lib\Facebook\autoload.php'; // change path as needed



	//conexao do banco
	$servidor = "localhost";
	$usuario = "root";
	$senha = "";
	$dbname = "cadastro";
	$tabela = 'wp_users';
	//Criar a conexao
	$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);


	$fb = new \Facebook\Facebook([
	  'app_id' => '2259360870980761',
	  'app_secret' => '39ab78c5c926cf6adb7a9a9aa4e5b01c',
	  'default_graph_version' => 'v3.2',
	  
	]);

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email']; // Optional permissions

	try {
		//recuperando o acess token
		if(isset($_SESSION['face_acess_token'])) {
			$accessToken = $_SESSION['face_acess_token'];
		}else{
			$accessToken = $helper->getAccessToken();
		}
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  // When Graph returns an error
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  // When validation fails or other local issues
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}
	//se não tiver logado, logue no site
	if (! isset($accessToken)) {
		$_url_login = 'http://localhost/app/wordpress/wp-content/face_conexao.php';
		$loginUrl = $helper->getLoginUrl($_url_login, $permissions);

		}else{
			$_url_login = 'http://localhost/app/wordpress/wp-content/face_conexao.php';
			$loginUrl = $helper->getLoginUrl($_url_login, $permissions);
			//usuario ja autenticado
			if (isset($_SESSION['face_acess_token'])) {
				$fb->setDefaultAccessToken($_SESSION['face_acess_token']);
			}

			//usuario nao autenticado
			else{
				$_SESSION['face_acess_token'] = (string) $accessToken;
				// The OAuth 2.0 client handler helps us manage access tokens
				$oAuth2Client = $fb->getOAuth2Client();
				//aumentar tempo de acesso
				$_SESSION['face_acess_token'] = $oAuth2Client->getLongLivedAccessToken($_SESSION['face_acess_token']);
				$fb->setDefaultAccessToken($_SESSION['face_acess_token']);
			
			}
			//Recuperando as informações do usuario no face
			try {
				  $response = $fb->get('/me?fields=name, picture, email');
				  $user = $response->getGraphUser();
				  echo "<br> Pegando valores <br>";
				  //var_dump($user);
				  $retorno = json_decode($user);
				  $id = $retorno->id;
				  $nome = $retorno->name;
				  $email = $retorno->email;

				  $result_usuario = "SELECT ID, user_login, user_email FROM $tabela WHERE user_email='".$email."' LIMIT 1";
				  $resultado_usuario = mysqli_query($conn, $result_usuario);

				  if($resultado_usuario){
				  	//return "USUARIO JA CADASTRADO";
						}
					else{
						echo "<br> -----USUARIO NAO CADASTRADO----- <br>";
						
						$sql="insert into $tabela (ID, user_login, user_email) values ($id,'$nome','$email')";
						$query = mysqli_query($conn, $sql);

						//return "NOVO USUARIO CADASTRADO COM SUCESSO";
						
						}
				
				
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
				  echo 'Graph returned an error: ' . $e->getMessage();
				  exit;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
				  echo 'Facebook SDK returned an error: ' . $e->getMessage();
				  exit;
				}
		}



	?>
	<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Login</title>
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/signin.css" rel="stylesheet">
	</head>
	<body>
		<a href="<?php echo $loginUrl; ?>">Facebook</a>
		<div class="row text-center" style="margin-top: 60px;"> 
						<a href="http://localhost/app/wordpress/my-account/">
							<button type="button" class="btn btn-primary">Login
							</button>
						</a>
					</div>		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
	<!--<div class="wp-block-button is-style-default"><a class="wp-block-button__link has-background has-vivid-cyan-blue-background-color" href="http://localhost/app/wordpress/my-account/">Login</a></div>-->
	<!--<a href="<?php echo $loginUrl; ?>">Facebook</a>-->