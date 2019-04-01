<?php
	$servidor = "localhost";
	$usuario = "root";
	$senha = "";
	$dbname = "cadastro";
	$tabela = 'wp_users';

	//Criar a conexao
	$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);

	//conexao da API do Facebook
	$app_id = '2259360870980761';
    $app_secret = '39ab78c5c926cf6adb7a9a9aa4e5b01c';
    $default_graph_version = 'v3.2';
