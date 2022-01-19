<?php
require_once(__DIR__ . "/class/funcoes/controle_exibir.php");
use Model\ControleExibir as ControleExibir;

$exibir = new ControleExibir();

$sqli = $bd->conexao();

if(isset($_GET['teste'])){
	echo "<script>alert('Agendamento já realizado para este horário e data')</script>";
}

require("layout/topo.php");
require("layout/menu.php");


$itensUserCardapioForm = [
	'nome' => true,
	'sku' => true,
	'status' => true,
	'valor' => true,
	'categorias' => true,

	'imagens' => true,
];

$itensUserCardapioTabs = [
	'dados-gerais' => true,
	'imagens' => true,
];

$inputExibir = $exibir->defineElementosUi($itensUserCardapioForm);
$tabsExibir = $exibir->defineElementosUi($itensUserCardapioTabs);

?>

<!-- Select2 -->
<link rel="stylesheet" href="<?php echo PL_PATH_ADMIN ?>/public/bower_components/select2/dist/css/select2.css">

<div class="wrapper">

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<form class="form-horizontal" action="<?php echo PL_PATH_CLASS . '/agendar_cadastro.php' ?>" method="post" enctype="multipart/form-data" id="form">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					<span class="pull-left" style="margin-right: 10px">Atendimento - Cadastro</span>
				</h1>

				<div class="pull-right">
					<button type="submit" class="btn btn-success btn-xs" name="salvar" value="salva"><i class="fa fa-floppy-o"></i> Salvar</button>
					<!--<button type="submit" class="btn btn-success btn-xs" name="salvar" value="continuar">Salvar e continuar</button>-->
				</div>
			</section>
			<div style="clear: both;"></div>
			<!-- Main content -->
			<section class="content">
				<div class="row">

					<div class="col-md-12">
						<!-- Custom Tabs (Pulled to the right) -->
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs pull-left">
								<?php if ($exibir->devoExibirElementoUi('dados-gerais', $tabsExibir)) : ?>
									<li <?php if (empty($_GET['tela'])) echo 'class="active"' ?>>
										<a href="#tab_1" data-toggle="tab">Agendar</a>
									</li>
								<?php endif; ?>

							</ul>
							<div style="clear: both;"></div>
							<div class="tab-content">
								<div class="tab-pane <?php if (empty($_GET['tela'])) echo 'active' ?>" id="tab_1">

									<?php if ($exibir->devoExibirElementoUi('status', $inputExibir)) : ?>
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Serviço</label>

											<div class="col-sm-10">
												<select class="form-control" id="servico" name="servico" onchange="teste()">
												<option value="0" selected>Selecione um serviço</option>
												<?php
												$dados = $bd->select(array('id', 'nome'), 'categorias', array('status = 1', 'excluido = 0'), array('ordem'), 'DESC', 1000);

												foreach ($dados as $key => $value) {
												?>
													<option value="<?php echo $value['id'] ?>"><?php echo $value['nome'] ?></option>
													<?php }?>
												</select>
											</div>
										</div>
									<?php endif; ?>
									<div style="display: none;" id="cadastro">				
										<?php if ($exibir->devoExibirElementoUi('status', $inputExibir)) : ?>
											<div class="form-group">
												<label for="status" class="col-sm-2 control-label">Funcionários</label>

												<div class="col-sm-10" id="funcionarios" name="funcionarios">
												</div>
											</div>
										<?php endif; ?>

										<div class="form-group">
											<label for="data_nascimento" class="col-sm-2 control-label">Data</label>

											<div class="col-sm-10">
												<input type="text" class="form-control" id="data" name="data" placeholder="Data do atendimento" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
											</div>
										</div>

										<div class="form-group">
											<label for="informacao_loja_fechada" class="col-sm-2 control-label">Horário</label>

											<div class="col-sm-10">
												<input type="time" class="form-control col-sm-3" id="horario" name="horario" style="width: auto;">
											</div>
										</div>
									</div>	
									

									<div style="clear: both;"></div>

								</div>
								
								
								<!-- /.tab-pane -->
							</div>
							<!-- /.tab-content -->
						</div>
						<!-- nav-tabs-custom -->
					</div>

				</div>

			</section>
		</form>
	</div>

	<?php require("layout/rodape.php") ?>

	<!-- Select2 -->
	<script src="<?php echo PL_PATH_ADMIN ?>/public/bower_components/select2/dist/js/select2.full.min.js"></script>

	<!-- CK Editor -->
	<script src="<?php echo PL_PATH_ADMIN ?>/public/bower_components/ckeditor/ckeditor.js"></script>

	<script type="text/javascript" src="<?php echo PL_PATH_ADMIN ?>/public/js/maskMoney.js"></script>

	<!-- InputMask -->
	<script src="<?php echo PL_PATH_ADMIN ?>/public/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="<?php echo PL_PATH_ADMIN ?>/public/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
	<script src="<?php echo PL_PATH_ADMIN ?>/public/plugins/input-mask/jquery.inputmask.extensions.js"></script>
	
	<script src="<?php echo PL_PATH_ADMIN ?>/public/validate/jquery.validate.js"></script>

	<script src="<?php echo PL_PATH_ADMIN ?>/public/js/funcoes_produtos.js"></script>
	
	<script src="<?php echo PL_PATH_ADMIN ?>/public/js/funcoes_clientes.js?v=2"></script>

	<script type="text/javascript" src="<?php echo PL_PATH_ADMIN ?>/public/js/jquery.validate.min.js"></script>

	<script>

		function teste(){
			var valor = $("#servico").val();		
			$.ajax({
				url:'content/busca.php',
				method:'POST',
				data:{name: valor},
				dataType:'json'

			}).done(function(result){
				var sel = "";
				sel = '<select class="form-control" name="func" id="func">';
				var i = 0;
				while(i < result.length){
					sel += '<option value="'+result[i].id+'">'+result[i].nome+'</option>';
					i++;
				}
				sel += '</select>';             
				$("#funcionarios").html(sel);
				document.getElementById("cadastro").style.display = "block";
				
			});
		}
	</script>
	<script>
	$(function () {
		//Initialize Select2 Elements
		$('.select2').select2();

		//Datemask dd/mm/yyyy
    	// $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
    	$('[data-mask]').inputmask()
	})
	</script>

</div>