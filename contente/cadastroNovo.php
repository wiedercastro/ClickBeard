<?php
require("../../config/start.php");

// echo json_encode($_POST);
// exit();
echo "<pre>";
var_dump("teste");
exit;
if (!empty($_POST)) {
   
	//campos com validção simples
	$campos = array('nome','usuario','senha_anterior');
	
	foreach($campos as $campo){
		if(isset($_POST[$campo])){
			$$campo = $_POST[$campo];
		}else{
			$$campo = "";
		}
	}

		$tipo_usuario = 3;
	

	//campos com validção especifica
	if(isset($_POST['senha'])){
		$senha = md5($_POST['senha']);
	}else{
		$senha = "";
	}
		$data_cadastro = date("Y-m-d H:i:s");
		$ultima_atualizacao = date("Y-m-d H:i:s");
		$status = "1";
		$excluido = "0";
	

	$sqli = $bd->conexao();

	$query = "INSERT INTO usuarios VALUES (NULL, '$nome', $tipo_usuario, '$usuario', '$senha', '$senha_anterior', '$data_cadastro', '$ultima_atualizacao', '$status', '$excluido')";

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