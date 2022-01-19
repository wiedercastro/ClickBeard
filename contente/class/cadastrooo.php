<?php
require("../../config/start.php");

// echo json_encode($_POST);
// exit();

if (!empty($_POST)) {

	$values = array();

	$nome = $_POST['nome_cat'];
	$url  = limpaString($_POST['nome_cat']);
	$url  = gera_url($url, 'categorias');

	$data = date('Y:m:d h:i:s');

	$sqli = $bd->conexao();

	$query = "INSERT INTO categorias VALUES (NULL, 0, 0, '$nome', '$url', '', '', '', 0, 0, '', '', '', '$data', '$data', 1, 0)";

	// echo pre($query);
	// exit();

	$result = $sqli->query($query);

	if ($sqli->connect_errno) {
		echo json_encode($sqli->error_list);
		exit();
	}else{

		//pre($result);
		// pre($values);
		// exit();

		$dados = array(
					'return' => true,
					'id' => $sqli->insert_id
					);
		echo json_encode($dados);
		exit;
	}

}else{

	$dados = array(
				'return' => false,
				'msg'    => "Houve um erro ao cadatrar a categoria!"
				);
	echo json_encode($dados);
	exit;
}