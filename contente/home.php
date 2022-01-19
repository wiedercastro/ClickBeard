<div class="wrapper">

  <?php
  require("layout/topo.php");
  require("layout/menu.php");
  $sqli = $bd->conexao();

  $periodo = 7;
  if((isset($_SESSION['PL_USER']['dashboard'])) && (!empty($_SESSION['PL_USER']['dashboard']))){
  	$periodo = $_SESSION['PL_USER']['dashboard'];
  }
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	
	<!-- Content Header (Page header) -->
	<section class="content-header">

		<?php
		$status_loja = verifica_status_loja($sqli);
		if(!empty($status_loja)){
			echo $status_loja;
		}
		?>

	 <h1>
			<span class="pull-left" style="margin: 5px 10px 0px 0px;">Dashboard</span>
		</h1>

		<div class="pull-left">

		    <form action="<?php echo PL_PATH_CLASS.'/periodo_home.php'?>" method="post">
				<div class="form-group pull-left" style="margin-right: 10px">
					<select class="form-control" name="periodo">
						<option value="1" <?php echo ($periodo == 1) ? 'selected="" disabled=""' : ''?>>Hoje</option>
						<option value="2" <?php echo ($periodo == 2) ? 'selected="" disabled=""' : ''?>>Ontem</option>
						<option value="7" <?php echo ($periodo == 7) ? 'selected="" disabled=""' : ''?>>Últimos 7 dias</option>
						<option value="15" <?php echo ($periodo == 15) ? 'selected="" disabled=""' : ''?>>Últimos 15 dias</option>
						<option value="30" <?php echo ($periodo == 30) ? 'selected="" disabled=""' : ''?>>Últimos 30 dias</option>
						<option value="60" <?php echo ($periodo == 60) ? 'selected="" disabled=""' : ''?>>Últimos 60 dias</option>
					</select>
				</div>
				
				<div class="form-group pull-left">
					<button type="submit" class="btn btn-success" name="salvar" value="salva">
						<i class="fa fa-filter"></i> Filtrar
					</button>
				</div>

			</form>

		</div>
	</section>
	<div style="clear: both;"></div>

	<!-- Main content -->
	<section class="content">
	  <!-- Small boxes (Stat box) -->
	  <div class="row">

		<div class="col-lg-3 col-xs-6">
		  <!-- small box -->
		  <div class="small-box bg-aqua">
			<div class="inner">

			  <?php
			  $query = "SELECT count(DISTINCT id) as usuarios FROM usuarios WHERE tipo_usuario = 3";

			  $clientes = $sqli->query($query);
			  $clientes = $clientes->fetch_array();
			  ?>

			  <h3><?php echo $clientes['usuarios'];?></h3>

			  <p><?php echo ($clientes['usuarios'] != 1) ? 'Clientes' : 'Cliente';?></p>
			</div>
			<div class="icon">
			  <i class="fa fa-users"></i>
			</div>
			<!-- <a href="#" class="small-box-footer">Mais Detalhes <i class="fa fa-arrow-circle-right"></i></a> -->
		  </div>
		</div>
		<!-- ./col -->
		<div class="col-lg-3 col-xs-6">
		  <!-- small box -->
		  <div class="small-box bg-yellow">
			<div class="inner">

			  <?php
			  $query = "SELECT count(DISTINCT id) as servicos FROM servicos ";

			  $pedidos = $sqli->query($query);
			  $pedidos = $pedidos->fetch_array();
			  ?>

			  <h3><?php echo $pedidos['servicos'];?></h3>

			  <p><?php echo 'Atendimentos';?></p>
			</div>
			<div class="icon">
			  <i class="fa fa-list-alt"></i>
			</div>
			<!-- <a href="#" class="small-box-footer">Mais Detalhes <i class="fa fa-arrow-circle-right"></i></a> -->
		  </div>
		</div>
		<!-- ./col -->
       

		<!-- ./col -->
	  </div>
	  <!-- /.row -->
	  <!-- Main row -->
	  <div class="row">

	  <!-- right col (We are only adding the ID to make the widgets sortable)-->
		<section class="col-lg-12">

			<div class="box box-primary">
				<div class="box-header with-border">
					<i class="fa fa-list-alt"></i>
					<h3 class="box-title">Atendimentos <a href="<?php echo PL_PATH_ADMIN;?>/pedidos"><small>ver todos</small></a></h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive" style="overflow-x: unset;">
						<table class="table no-margin lista_pedido">
							<thead>
								<tr>
									<th style="width: 30px" class="no-sort">Cod.</th>
									<th style="width: 140px" class="no-sort">Data</th>
									<th class="no-sort">Atendimento</th>
									<th class="no-sort">Cliente</th>
									<th class="no-sort">Funcionário</th>
									<th style="width: 160px" class="no-sort">Horário</th>
								
								</tr>
							</thead>
							<tbody>
								<?php
								$dados = $bd->select(array('id', 'id_categoria','id_funcionario','id_cliente','data_cadastro', 'hora_atendi', 'status'), 'servicos' ,array('excluido = 0'),'', array('DESC','DESC'),10);
								foreach ($dados as $key => $value) {
									$dados1 = $bd->select(array('nome'), 'categorias', array('status = 1', 'excluido = 0','id = '.$value['id_categoria']), array('ordem'), 'DESC', 1000);
                                    $dados2 = $bd->select(array('nome'), 'tarefas', array('id = '.$value['id_funcionario']),'', 'DESC', 1000);
									$dados3 = $bd->select(array('nome'), 'usuarios', array('id = '.$value['id_cliente']),'', 'DESC', 1000);
							
								?>
									<tr id="dado_lista<?php echo $value['id'];?>">
										<td class="id" attr-id="<?php echo $value['id']?>"><?php echo $value['id']?></td>
										<td><?php echo convert_data($value['data_cadastro'])?></td>
										<td>
											
											<?php  foreach ($dados1 as $key => $value1) {
                                                echo $value1['nome'];
                                                } ?>
				
								
										</td>
										<td>
											
											<?php  foreach ($dados3 as $key => $value3) {
                                                echo $value3['nome'];
                                                } ?>
				
								
										</td>
										<td>
											
											<?php  foreach ($dados2 as $key => $value2) {
                                                echo $value2['nome'];
                                                } ?>
				
								
										</td>
										<td><?php echo $value['hora_atendi'];?></td>
										<td>
												
										</td>
										
									</tr>
								<?php } ?>

							</tbody>
						</table>
					</div>
					<!-- /.table-responsive -->
				</div>
				<!-- /.box-body -->
			</div>

		</section>

		<!-- Left col -->
		<section class="col-lg-6">

		  <!-- TO DO List -->
		  <div class="box box-primary">
			<div class="box-header">
			  <i class="fa fa-bar-chart"></i>
			  <h3 class="box-title">
			  	<?php
			  	if($periodo == 1){
			  		echo "Hoje";
			  	}else if($periodo == 2){
			  		echo "Ontem";
			  	}else{
			  		echo "Últimos $periodo dias";
			  	}
			  	?>
			  </h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<div class="box-body chart-responsive">
				  <div id="line-chart" style="height: 300px;"></div>
				</div>
			</div>
		  </div>

		</section>
		<!-- /.Left col -->

		<!-- right col (We are only adding the ID to make the widgets sortable)-->
		<!-- right col -->

		<!-- 
		<section class="col-lg-6">
		  <div class="box box-primary">
			<div class="box-header">
			  <i class="fa fa-clipboard"></i>
			  <h3 class="box-title">Termos mais buscados <a href="<?php echo PL_PATH_ADMIN;?>/termos_buscados"><small>ver todos</small></a></h3>
			</div>
			
			<div class="box-body">
			  
			  <ul class="todo-list">

				<?php
				$dados = $bd->select(array('id', 'termo', 'quantidade'), 'termos_buscados' ,'', array('quantidade'), 'DESC',6);
				foreach ($dados as $key => $value) {
				?>

					<li>
						
						<span class="text"><?php echo $value['termo'];?></span>
						
						<small class="label label-default"><i class="fa fa-search-plus"></i> <?php echo $value['quantidade'];?></small>
					</li>
				<?php }?>
				
			  </ul>
			</div>
		  </div>
		  
		</section>
		-->
	  </div>
	  <!-- /.row (main row) -->

	</section>
	<!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <?php require("layout/rodape.php") ?>
  
</div>
<!-- ./wrapper -->
<script src="<?php echo PL_PATH_ADMIN ?>/public/js/funcoes_home.js"></script>

<!-- FLOT CHARTS -->
<script src="<?php echo PL_PATH_ADMIN ?>/public/bower_components/Flot/jquery.flot.js"></script>

<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
<script src="<?php echo PL_PATH_ADMIN ?>/public/bower_components/Flot/jquery.flot.categories.js"></script>

<script src="<?php echo PL_PATH_ADMIN ?>/public/js/lista_pedido_home.js"></script>


<script type="text/javascript">
	
  $(function () {

	/*
	 * LINE CHART
	 * ----------
	 */
	//LINE randomly generated data

	var dados = '';
	<?php
	$query = "SELECT CAST(data AS DATE) as data, SUM(valor) as valor
			FROM pedidos
			WHERE data BETWEEN DATE_ADD( '".date('Y-m-d', strtotime('+1 days'))."' , INTERVAL -$periodo DAY ) AND '".date('Y-m-d', strtotime('+1 days'))."' AND excluido = 0
			GROUP BY CAST(data AS DATE)
			ORDER BY data ASC
			LIMIT 10 OFFSET 0";

	$ult_pedidos = $sqli->query($query);
	
	?>
	var line_data1 = {
	data : [
	  
	<?php
	$dados = '';
	foreach ($ult_pedidos as $key => $value) {
		$dados .=  "['".convert_dayMonth($value['data'])."',".number_format($value['valor'], 0, '', '')."],";
	}
	echo substr($dados, 0, -1);
	?>

	],
	  color: '#3c8dbc'
	}

	$.plot('#line-chart', [line_data1], {
	  grid  : {
		hoverable  : true,
		borderWidth: 1,
		borderColor: '#f3f3f3',
		tickColor  : '#f3f3f3'
	  },
	  series: {
		shadowSize: 0,
		lines     : {
		  show: true
		},
		points    : {
		  show: true
		}
	  },
	  lines : {
		fill : true,
		color: ['#3c8dbc', '#f56954']
	  },
	  xaxis : {
		mode      : 'categories',
		tickLength: 0
	  }
	})

	//Initialize tooltip on hover
	$('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
	  position: 'absolute',
	  display : 'none',
	  opacity : 0.8
	}).appendTo('body')
	$('#line-chart').bind('plothover', function (event, pos, item) {

	  if (item) {
		var x = item.datapoint[0].toFixed(2),
			y = item.datapoint[1].toFixed(2)

		$('#line-chart-tooltip').html('R$ ' + y)
		  .css({ top: item.pageY + 5, left: item.pageX + 5 })
		  .fadeIn(200)
	  } else {
		$('#line-chart-tooltip').hide()
	  }

	})
	/* END LINE CHART */

	/*
	 * BAR CHART
	 * ---------
	 */

	<?php
	$query =   "SELECT PP.id_pedido, PP.nome, PP.id_produto, SUM(quantidade) as quantidade, P.status_pedido
				FROM pedidos as P

				JOIN pedidos_produto as PP ON P.id = PP.id_pedido

				WHERE P.excluido = 0  AND (P.status_pedido = 2 OR P.status_pedido = 3 OR P.status_pedido = 4)

				GROUP BY PP.id_produto
				ORDER BY quantidade DESC
				LIMIT 5 OFFSET 0";

	$mais_vendidos = $sqli->query($query);
	
	?>
	var bar_data = [<?php
		$dados = array();
		$valor = '';

		foreach ($mais_vendidos as $key => $value) {
			$dados[$key] =  $value;
		}

		foreach (array_reverse($dados) as $key => $value) {
			$valor .= "[".$value['quantidade'].",".$key."],";
		}
		
		echo substr($valor, 0, -1);
		?>];

	var tickLabels = [<?php
		$tickLabels = array();
		$valorTick = '';
		foreach ($mais_vendidos as $key => $value) {

			$tickLabels[$key] =  $value;
		}

		foreach (array_reverse($tickLabels) as $key => $value) {
			$valorTick .= "[".$key.",'".mb_strimwidth($value['nome'], 0, 40, "...")."'],";
		}

		echo substr($valorTick, 0, -1);
	?>];

	$.plot('#bar-chart', [
		{
			data: bar_data,
			color: '#00a65a',
			bars: {  
				show: true,  
				horizontal: true  
			} 
		} 
		],{
			grid  : {
				borderWidth: 1,
				borderColor: '#f3f3f3',
				tickColor  : '#f3f3f3',
			},
			series: {
				bars: {
					show    : true,
					barWidth: 0.5,
					horizontal : true,
				}
			},
			yaxis : {
				ticks: tickLabels,
				labelWidth: 100,
			}
		}
	);
	/* END BAR CHART */	

  })
</script>