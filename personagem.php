<?php
session_start();

function imprimePersonagem($data, $num){

	$dt = json_decode($data);
	$nome = $dt->name;
	$genero = $dt->gender;
	$altura = $dt->height;
	$cor_pele = $dt->skin_color;
	$cabelo = $dt->hair_color;
	$olhos = $dt->eye_color;

	echo "PERSONAGEM: $num <br>";
	echo "Nome: ".$nome." <br>";
	echo "Genero: ".$genero." <br>";
	echo "Altura: ".$altura." <br>";
	echo "Cor da pele: ".$cor_pele." <br>";
	echo "Cor do cabelo: ".$cabelo." <br>";
	echo "Cor dos olhos: ".$olhos." <br>";
	
}

//----------------------------------------------
$num = rand(1,10);
$data = file_get_contents("https://swapi.co/api/people/$num/");
//print_r($data);

if(!empty($_SESSION['nome'])){
	echo "Olá ".$_SESSION['nome'].", Bem vindo <br>";
}else{
	echo "Olá desconhecido, Bem vindo <br>";
}

echo "Deseja conhecer um dos personagens do Star Wars? <br>";
echo "<a href='http://localhost/app/personagem.php'>STAR WARS</a> <br><br>";

echo "Ou deseja conhecer um pouco mais sobre um dos filmes do Star Wars? <br>";
echo "<a href='http://localhost/app/filme.php'>Filme STAR WARS</a> <br>";
echo "<a href='sair.php'>Sair</a><br><br>";

imprimePersonagem($data, $num);

?>

