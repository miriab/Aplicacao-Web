<?php
session_start();
//include_once("api.php");

if(!empty($_SESSION['nome'])){
	echo "Olá ".$_SESSION['nome'].", Bem vindo <br>";
	echo "Deseja conhecer um dos personagens do Star Wars? <br>";
	echo "<a href='http://localhost/app/api.php'>STAR WARS</a> <br><br>";

	echo "Ou deseja conhecer um pouco mais sobre um dos filmes do Star Wars? <br>";
	echo "<a href='http://localhost/app/filme.php'>Filme STAR WARS</a> <br>";
	echo "<a href='sair.php'>Sair</a>";
}else{
	//$_SESSION['msg'] = "<div class='alert alert-danger'>Área restrita!</div>";
	//header("Location: login.php");
	echo "Olá desconhecido, Bem vindo <br>";
	echo "Deseja conhecer um dos personagens do Star Wars? <br>";
	echo "<a href='http://localhost/app/personagem.php'>STAR WARS</a> <br><br>";

	echo "Ou deseja conhecer um pouco mais sobre um dos filmes do Star Wars? <br>";
	echo "<a href='http://localhost/app/filme.php'>Filme STAR WARS</a> <br>";
	echo "<a href='sair.php'>Sair</a>";


}
