<?php
//session_start();
//unset($_SESSION['face_acess_token']);
require_once 'lib/Facebook/autoload.php'; // change path as needed
require_once 'conexao.php'; // change path as needed

$fb = new \Facebook\Facebook([
  'app_id' => '2259360870980761',
  'app_secret' => '39ab78c5c926cf6adb7a9a9aa4e5b01c',
  'default_graph_version' => 'v3.2',
  //'default_access_token' => '{access-token}', // optional

]);

$helper = $fb->getRedirectLoginHelper();
//var_dump($helper);

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
	$_url_login = 'http://localhost/app/face.php';
	$loginUrl = $helper->getLoginUrl($_url_login, $permissions);

	}else{
		$_url_login = 'http://localhost/app/face.php';
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
			  // Returns a `Facebook\FacebookResponse` object
			  $response = $fb->get('/me?fields=name, picture, email');
			  $user = $response->getGraphUser();
			  var_dump($user);
			  //validando o usuário

			   $result_usuario = "SELECT ID, nome, email FROM cadastro WHERE email='".$user['email']."' LIMIT 1";
			  $resultado_usuario = mysqli_query($conn, $result_usuario);
			  if($resultado_usuario){
					$row_usuario = mysqli_fetch_assoc($resultado_usuario);
					$_SESSION['id'] = $row_usuario['ID'];
					$_SESSION['nome'] = $row_usuario['nome'];
					$_SESSION['email'] = $row_usuario['email'];
					header("Location: administrativo.php");
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

	<!--<a href="<?php echo $loginUrl; ?>">Facebook</a>-->