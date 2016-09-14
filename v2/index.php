<?php

	require '../libs/Slim/Slim/Slim.php';
	
	\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim();
	$app->response()->header('Content-Type', 'application/json;charset=utf-8');
	$app->get('/', function (){
		echo "Slim Framework funcionando";
	});

	
	$app->get('/produtos', 'getProdutos');

	$app->post('/produtos','addProduto');

	$app->put('/produtos/:id', 'uploadFile');

	$app->run();


	//retorna todos os produtos cadastrados
	function getProdutos()
	{
		require "../include/DBConnection.php";

		$sql = "SELECT * FROM produto";
		$result = $db->getAll($sql);

		$produtos = array();
		$i = 0;
		foreach ($result as $row) {
			$produtos[$i]['id'] = $row['id'];
			$produtos[$i]['idUsuario'] = $row['idUsuario'];
			$produtos[$i]['titulo'] = $row['titulo'];
			$produtos[$i]['descricao'] = $row['descricao'];
			$produtos[$i]['preco'] = $row['preco'];
			$produtos[$i]['aceitaTrocar'] = $row['aceitaTrocar'];
			$produtos[$i]['ativo'] = $row['ativo'];
			$produtos[$i]['vendido'] = $row['vendido'];
			$produtos[$i]['dataCadastro'] = $row['dataCadastro'];
			$produtos[$i]['dataVenda'] = $row['dataVenda'];
			$i++;
		}
		print json_encode(array("produtos" =>$produtos));
	}

	function addProduto()
	{
		require "includes/conexao.php";

		$request = \Slim\Slim::getInstance()->request();
		$produto = json_decode($request->getBody());

		$sql = "INSERT INTO produto(idUsuario, titulo, descricao, preco, aceitaTrocar, ativo, dataCadastro) VALUES (?,?,?,?,?,?,?)";
		$values = array(1, $produto->titulo, $produto->descricao, $produto->preco, $produto->aceitaTrocar, 1, '2015-10-5');

		$result = $db->Execute($sql, $values);
		$id = $db->Insert_ID();

		echo json_encode(array("idProduto"=>$id));
		//echo json_encode($id);

	}


	function uploadFile ($id) {

	    //require "includes/conexao.php";

		$request = \Slim\Slim::getInstance()->request();
		$produto = json_decode($request->getBody());

		$nomeFoto = geraNomeAleatorio("foto.jpg");
		$location = "uploads/".$nomeFoto;                              
		//echo $location;
        //$current = file_get_contents($location, true);                          
        $current = base64_decode($produto->foto);                           
        @file_put_contents($location, $current); 

		echo json_encode(array("foto"=>$nomeFoto));
		exit;
	    
	}


	//função extraida da internet. http://www.linhadecomando.com/php/php-gerando-nome-de-arquivo-aleatorio

	function pegaExtensao($arquivo){
	  $ext = explode('.',$arquivo);
	  $ext = array_reverse($ext);
	  return ".".$ext[0]; 
	}
	function pegaSomenteNome($arquivo){
	  $nome = pathinfo($arquivo);
	  return $nome['filename'];
	}
	function geraNomeAleatorio($arquivo){
	  $extensao    = pegaExtensao($arquivo);
	  $somenteNome = pegaSomenteNome($arquivo);
	  //$rand	       = rand(0, 99999);
	  //ou
	  $rand = sha1($somenteNome.time() + rand(0,999999));
	  return $somenteNome.$rand.$extensao;
	}
?>