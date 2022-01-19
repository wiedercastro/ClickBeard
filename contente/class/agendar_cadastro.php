<?php
require("../../config/start.php");

// pre($_POST);
// exit();

if (!empty($_POST)) {

	//campos com validação simples
	$campos = array('servico','func','data','horario');
	
	foreach($campos as $campo){
		if((isset($_POST[$campo])) && (!empty($_POST[$campo]))){
			$$campo = addslashes($_POST[$campo]);
		}else{
			$$campo = "";
		}
	}
	$id_cliente = $_SESSION['PL_USER']['id'];
	$status = 1;
	$excluido = 0;

	if(isset($_POST['data']) && !empty($_POST['data'])){
		$data = $_POST['data'];
		$data = explode('/',$data);
		$data = $data[2]."-".$data[1]."-".$data[0];
	}

	
	$sqli = $bd->conexao();
	
	if (mysqli_connect_errno()) {
	    printf("Falha na conexao: %s\n", mysqli_connect_error());
	    exit();
	}

	$dados = $bd->select(array('id','id_categoria','id_funcionario','data_cadastro','hora_atendi'), 'servicos', array('status = 1', 'excluido = 0', 'data_cadastro = "'.$data.'"','id_categoria ='.$servico,'id_funcionario ='.$func),'', 'DESC', 1000);
	$operador = 0;
	foreach($dados as $value) {
		$operador = 0;
		$hora = explode(':',$value['hora_atendi']);
		$horaM = $hora[1];
		$hora = $hora[0];
		if($value['hora_atendi']==$horario){
			$operador = 1;
		}else{
			for($i = 1;$i<=30;$i++){
				if($horaM-$i==60){
					$hora = $hora+1;
					$horaM = 00;
				}
				$valorH = $hora.":".($horaM+$i);
				if($valorH==$horario){
					$operador = 1;
					break;
				}
			}
			if($operador==0){
				for($i = 1;$i<=30;$i++){
					if($horaM-$i==0){
						$hora = $hora-1;
						$horaM = 60;
					}
					$valorH = $hora.":".($horaM-$i);
					if($valorH==$horario){
						$operador = 1;break;
					}
				}
			}else{
				break;
			}
		}
	}
	if($operador==0){
		$query = "INSERT INTO servicos VALUES (NULL, $servico, $func, $id_cliente, '$data','$horario',$status,$excluido)";

		$sqli->query($query);

		if(!$sqli->connect_errno){

			if($_POST['salvar'] == 'continuar'){
				header("location:".PL_PATH_ADMIN.'/produtos_edita/'.$url);
			}else{
				header("location:".PL_PATH_ADMIN.'/agendar');
			}
		}else{
			echo $sqli->error;
			pre($sqli->error_list);
			exit;
		}
	}else{
		header("location:".PL_PATH_ADMIN.'/agendar_cadastro&teste=1');
	}
	

	// pre($query);
	// pre($sqli);
	// pre($_POST);
	// exit();

	

	// printf ("New Record has id %d.\n", $sqli->insert_id);


}else{

	header("location:".PL_PATH_ADMIN.'/produtos');
}