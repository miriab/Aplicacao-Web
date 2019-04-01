<?php
session_start();

function imprimeFilme($data, $num){

	$dt = json_decode($data);
	$titulo = $dt->title;
	$episodio = $dt->episode_id;
	$diretor = $dt->director;
	$produtor = $dt->producer;
	$lancamento = $dt->release_date;
	$personagem = $dt->characters;


	echo "FILME: $num <br>";
	echo "Título: ".$titulo." <br>";
	echo "Episodio: ".$episodio." <br>";
	echo "Diretor: ".$diretor." <br>";
	echo "Produtor: ".$produtor." <br>";
	echo "Lancamento: ".$lancamento." <br>";
	echo "<br> Personagens: <br>";	
	foreach ($personagem as $perso) {
		$ator_incode = file_get_contents("$perso");
		$ator = json_decode($ator_incode);
		//print_r($ator);
		echo "$ator->name <br>";
	
	}
	
}


$num = rand(1,7);
$data = file_get_contents("https://swapi.co/api/films/$num/");

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

imprimeFilme($data, $num);


?>

