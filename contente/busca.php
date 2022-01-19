<?php
require("../config/start.php");
header('Content-type: application/json');
$valor = $_POST['name'];
$sqli = $bd->conexao();

$query = "SELECT * FROM produtos_categorias where id_categoria = 17";
$total_produtos = $sqli->query($query);

//$total_produtos = $total_produtos->fetch_array();

$dados = $bd->select(array('id_produto'), 'produtos_categorias',array('id_categoria='.$valor),'', 'DESC', 1000);
$valores =  Array();
$i=0;
foreach ($dados as $key => $value) {
    $valores[$i]['id'] = $value['id_produto'];
    $dados1 = $bd->select(array('nome'), 'tarefas',array('id='.$value['id_produto']),'', 'DESC', 1000);
    foreach ($dados1 as $key1 => $value1) {
        $valores[$i]['nome'] = $value1['nome'];
    }
$i++;
}
echo json_encode($valores);