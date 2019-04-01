<?php
session_start();
include_once("conexao.php");

$btnLogin = filter_input(INPUT_POST, 'btnLogin', FILTER_SANITIZE_STRING);
if($btnLogin){
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
	$senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
	//echo "$email - $senha";
	if((!empty($usuario)) AND (!empty($senha))){
		//Gerar a senha criptografa
		//echo password_hash($senha, PASSWORD_DEFAULT);
		//Pesquisar o usuário no BD
		$result_usuario = "SELECT ID, nome, email, senha FROM cadastro WHERE email='$email' LIMIT 1";
		$resultado_usuario = mysqli_query($conn, $result_usuario);
		if($resultado_usuario){
			$row_usuario = mysqli_fetch_assoc($resultado_usuario);
			//var_dump($row_usuario);
			//echo "$row_usuario['senha']";
			if(password_verify($senha, $row_usuario['senha'])){
				$_SESSION['id'] = $row_usuario['ID'];
				$_SESSION['nome'] = $row_usuario['nome'];
				$_SESSION['email'] = $row_usuario['email'];
				header("Location: administrativo.php");
			}else{
				$_SESSION['msg'] = "<div class='alert alert-danger'>Senha incorreta!</div>";
				header("Location: login.php");
			}
		}
	}else{
		$_SESSION['msg'] = "<div class='alert alert-danger'>Login incorreto!</div>";
		header("Location: login.php");
	}
}else{
	$_SESSION['msg'] = "<div class='alert alert-danger'>Página não encontrada</div>";
	header("Location: login.php");
}
