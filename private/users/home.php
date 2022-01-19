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
			  $query = "SELECT count(DISTINCT id) as quantidade FROM clientes WHERE data_cadastro BETWEEN DATE_ADD( '".date('Y-m-d', strtotime('+1 days'))."' , INTERVAL -$periodo DAY ) AND '".date('Y-m-d', strtotime('+1 days'))."' AND excluido = 0";

			  $clientes = $sqli->query($query);
			  $clientes = $clientes->fetch_array();
			  ?>

			  <h3><?php echo $clientes['quantidade'];?></h3>

			  <p><?php echo ($clientes['quantidade'] != 1) ? 'Clientes' : 'Cliente';?></p>
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
			  $query = "SELECT count(DISTINCT id) as quantidade FROM pedidos WHERE data BETWEEN DATE_ADD( '".date('Y-m-d', strtotime('+1 days'))."' , INTERVAL -$periodo DAY ) AND '".date('Y-m-d', strtotime('+1 days'))."' AND status_pedido != 8 AND excluido = 0";

			  $pedidos = $sqli->query($query);
			  $pedidos = $pedidos->fetch_array();
			  ?>

			  <h3><?php echo $pedidos['quantidade'];?></h3>

			  <p><?php echo ($clientes['quantidade'] != 1) ? 'Pedidos Realizados' : 'Pedido Realizado';?></p>
			</div>
			<div class="icon">
			  <i class="fa fa-list-alt"></i>
			</div>
			<!-- <a href="#" class="small-box-footer">Mais Detalhes <i class="fa fa-arrow-circle-right"></i></a> -->
		  </div>
		</div>
		<!-- ./col -->

		<div class="col-lg-3 col-xs-6">
		  <!-- small box -->
		  <div class="small-box bg-primary">
			<div class="inner">
			  <?php
			  $query = "SELECT count(DISTINCT id) as quantidade FROM pedidos WHERE data BETWEEN DATE_ADD( '".date('Y-m-d', strtotime('+1 days'))."' , INTERVAL -$periodo DAY ) AND '".date('Y-m-d', strtotime('+1 days'))."' AND (status_pedido = 2 OR status_pedido = 3 OR status_pedido = 4) AND excluido = 0";

			  $pedidos = $sqli->query($query);
			  $pedidos = $pedidos->fetch_array();
			  ?>

			  <h3><?php echo $pedidos['quantidade'];?></h3>

			  <p><?php echo ($clientes['quantidade'] != 1) ? 'Vendas Confirmadas' : 'Venda Confirmada';?></p>
			</div>
			<div class="icon">
			  <i class="fa fa-shopping-basket"></i>
			</div>
			<!-- <a href="#" class="small-box-footer">Mais Detalhes <i class="fa fa-arrow-circle-right"></i></a> -->
		  </div>
		</div>
		<!-- ./col -->
		<div class="col-lg-3 col-xs-6">
		  <!-- small box -->
		  <div class="small-box bg-green">
			<div class="inner">

			  <?php
			  $query = "SELECT sum(valor) as valor FROM pedidos WHERE data BETWEEN DATE_ADD( '".date('Y-m-d', strtotime('+1 days'))."' , INTERVAL -$periodo DAY ) AND '".date('Y-m-d', strtotime('+1 days'))."' AND (status_pedido = 2  OR status_pedido = 3 OR status_pedido = 4) AND excluido = 0";

			  $faturamento = $sqli->query($query);
			  $faturamento = $faturamento->fetch_array();

			  $valor_faturamento = number_format($faturamento['valor'], 2, ',', '.');
			  $valor_faturamento = explode(',', $valor_faturamento);
			  ?>

			  <h3><?php echo $valor_faturamento[0]; ?><sup style="font-size: 20px">,<?php echo $valor_faturamento[1]; ?></sup></h3>

			  <p>Faturamento</p>
			</div>
			<div class="icon">
			  <i class="fa fa-usd"></i>
			</div>
			<!-- <a href="#" class="small-box-footer">Mais Detalhes <i class="fa fa-arrow-circle-right"></i></a> -->
		  </div>
		</div>
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
					<h3 class="box-title">Últimos pedidos <a href="<?php echo PL_PATH_ADMIN;?>/pedidos"><small>ver todos</small></a></h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive" style="overflow-x: unset;">
						<table class="table no-margin lista_pedido">
							<thead>
								<tr>
									<th style="width: 30px" class="no-sort">Cod.</th>
									<th style="width: 140px" class="no-sort">Data</th>
									<th class="no-sort">Cliente</th>
									<th style="width: 160px" class="no-sort">Telefone</th>
									<th style="width: 100px" class="no-sort">Valor</th>
									<th style="width: 70px" class="no-sort">Pagamento</th>
									<th style="width: 135px" class="no-sort">Situação</th>
									<th style="width: 30px" class="no-sort"></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$dados = $bd->select(array('id','data', 'nome_cliente', 'email_cliente', 'valor', 'forma_pagamento', 'telefone_cliente', 'tipo_pagamento', 'situacao','status_pedido','novo','observacoes_entrega'), 'pedidos' ,array('excluido = 0'),array('novo','id'), array('DESC','DESC'),10);
								foreach ($dados as $key => $value) {
									$bg_novo = "";
									if($value['novo'] == 1){
										$bg_novo = ' <small class="label label-success pedido_novo"><i class="fa fa-exclamation-triangle"></i> novo</small>';
									}
								?>
									<tr id="dado_lista<?php echo $value['id'];?>">
										<td class="id" attr-id="<?php echo $value['id']?>"><?php echo $value['id']?></td>
										<td><?php echo convert_data_hora($value['data'])?></td>
										<td>
											<a href="<?php echo PL_PATH_ADMIN.'/pedidos_visualiza/'.$value['id']?>"><?php echo $value['nome_cliente'] . $bg_novo; ?></a>
											<?php 
											if(!empty($value['observacoes_entrega'])){
											?>
												<p class="help-block">
													<small>Obs.: <?php echo $value['observacoes_entrega']?></small>
												</p>
											<?php
											}
											?>
										</td>
										<td><?php echo $value['telefone_cliente'];?></td>
										<td>R$ <?php echo number_format($value['valor'], 2, ',', '.')?></td>
										<td>
											<?php
											if($value['tipo_pagamento'] == 8){
												echo "Mercado Pago";
											}elseif($value['tipo_pagamento'] == 7){
												echo "Dinheiro";
											}elseif($value['tipo_pagamento'] == 6){
												echo "Cartão";
											//pagseguro
											} elseif ($value['tipo_pagamento'] == 4) {
												echo "Pagamento Online - Cartão de Crédito";
											}
											?>		
										</td>
										
										<td class="label_status">
											<?php 
											if($value['status_pedido'] == 1){
												echo '<small class="label label-default">Pedido Realizado</small>';
											}else if($value['status_pedido'] == 2){
												echo '<small class="label label-info">Pedido Impresso</small>';
											}else if($value['status_pedido'] == 3){
												echo '<small class="label label-primary">Saiu para Entrega</small>';
											}else if($value['status_pedido'] == 4){
												echo '<small class="label label-success">Pedido Entregue</small>';
											}else if($value['status_pedido'] == 5){
												echo '<small class="label label-danger-strong">Cancelado</small>';
											}else if($value['status_pedido'] == 6){
												echo '<small class="label label-primary">Aguardando Pagamento</small>';
											}else if($value['status_pedido'] == 7){
												echo '<small class="label label-success">Pagamento Confirmado</small>';
											}else if($value['status_pedido'] == 8){
												echo '<small class="label label-danger">Pagamento Recusado</small>';
											}else if($value['status_pedido'] == 9){
												echo '<small class="label label-warning">Pagamento em Análise</small>';
											}else if($value['status_pedido'] == 10){
												echo '<small class="label label-gray-light">Pedido em Preparação</small>';
											}
											?>
											<?php if ($value['status_pedido'] != 6) { ?>
											<div class="btn-group">
												<button type="button" class="btn btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="height: 18.23px;"><i class="fa fa-caret-down"></i></button>
												<ul class="dropdown-menu">
												<?php
												if ($value['status_pedido'] == 1 || $value['status_pedido'] == 7) {
												?>
													<li class="status2">
														<a href="javascript:void(0)" onclick="altera_status(<?php echo $value['id'] . ', 2,\'' . $value['nome_cliente'] . '\',\'' . $value['email_cliente'] . '\''; ?>)">Pedido Impresso</a>
													</li>
													<li class="status10">
														<a href="javascript:void(0)" onclick="altera_status(<?php echo $value['id'] . ', 10,\'' . $value['nome_cliente'] . '\',\'' . $value['email_cliente'] . '\''; ?>)">Pedido em Preparação</a>
													</li>
													<li class="status3">
														<a href="javascript:void(0)" onclick="altera_status(<?php echo $value['id'] . ', 3,\'' . $value['nome_cliente'] . '\',\'' . $value['email_cliente'] . '\''; ?>)">Saiu para Entrega</a>
													</li>
													<li class="status4">
														<a href="javascript:void(0)" onclick="altera_status(<?php echo $value['id'] . ', 4,\'' . $value['nome_cliente'] . '\',\'' . $value['email_cliente'] . '\''; ?>)">Pedido Entregue</a>
													</li>
												<?php
												} elseif ($value['status_pedido'] == 2) {
												?>
													<li class="status10">
														<a href="javascript:void(0)" onclick="altera_status(<?php echo $value['id'] . ', 10,\'' . $value['nome_cliente'] . '\',\'' . $value['email_cliente'] . '\''; ?>)">Pedido em Preparação</a>
													</li>
													<li class="status3">
														<a href="javascript:void(0)" onclick="altera_status(<?php echo $value['id'] . ', 3,\'' . $value['nome_cliente'] . '\',\'' . $value['email_cliente'] . '\''; ?>)">Saiu para Entrega</a>
													</li>
													<li class="status4">
														<a href="javascript:void(0)" onclick="altera_status(<?php echo $value['id'] . ', 4,\'' . $value['nome_cliente'] . '\',\'' . $value['email_cliente'] . '\''; ?>)">Pedido Entregue</a>
													</li>
												<?php
												} elseif ($value['status_pedido'] == 10) { ?>
													<li class="status3">
														<a href="javascript:void(0)" onclick="altera_status(<?php echo $value['id'] . ', 3,\'' . $value['nome_cliente'] . '\',\'' . $value['email_cliente'] . '\''; ?>)">Saiu para Entrega</a>
													</li>
													<li class="status4">
														<a href="javascript:void(0)" onclick="altera_status(<?php echo $value['id'] . ', 4,\'' . $value['nome_cliente'] . '\',\'' . $value['email_cliente'] . '\''; ?>)">Pedido Entregue</a>
													</li>
												<?php } elseif ($value['status_pedido'] == 3) {
												?>
													<li class="status4">
														<a href="javascript:void(0)" onclick="altera_status(<?php echo $value['id'] . ', 4,\'' . $value['nome_cliente'] . '\',\'' . $value['email_cliente'] . '\''; ?>)">Pedido Entregue</a>
													</li>
												<?php
												} elseif ($value['status_pedido'] == 5) {
												?>
													<li><span class="text-red">Cancelado</span></li>
												<?php
												}
												?>
											</ul>
											</div>
											<?php } ?>
										</td>
										<td>
											<div class="btn-group">
												<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
													Ações <i class="fa fa-caret-down"></i>
												</button>
												<ul class="dropdown-menu">

													<li><a href="<?php echo PL_PATH_ADMIN.'/pedidos_visualiza/'.$value['id']?>"><i class="fa fa-pencil"></i> Visualiza</a></li>

													<li><a href="<?php echo PL_PATH_ADMIN.'/pedidos_imprime/'.$value['id']?>" target="_blank" onclick="altera_status_imprime(<?php echo $value['id'].', 2,\''.$value['nome_cliente'].'\',\''.$value['email_cliente'].'\'';?>)" ><i class="fa fa-print"></i> Imprimir</a></li>

													<?php if($value['status_pedido'] != 8){ ?>
													<li class="cancela_pedido">
														<a href="javascript:void(0);" class="text-red" onclick="cancela_pedido('pedidos',<?php echo $value['id'];?>);"><i class="fa fa-times"></i> Cancelar</a>
													</li>
													<?php } ?>
												</ul>
											</div>
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
		<section class="col-lg-6">

		  <!-- BAR CHART -->
		  <div class="box box-success">
			<div class="box-header">
			  <i class="fa fa-level-up"></i>
			  <h3 class="box-title">Mais vendidos</h3>
			</div>
			<div class="box-body">
				<div class="box-body chart-responsive">
					<div id="bar-chart" style="height: 300px;"></div>
				</div>
			</div>
			<!-- /.box-body -->
		  </div>
		  <!-- /.box -->
		  
		</section>
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