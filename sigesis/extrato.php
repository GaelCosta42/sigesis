<?php
  /**
   * Extrato - Arquivo extrato.php
   *
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Administrativo())
	  redirect_to("login.php");
?>
<script src="./assets/scripts/highcharts.js" type="text/javascript"></script>
<?php switch(Filter::$acao): case "extrato": ?>
<?php
$saldoinicial = 0;
$saldofinal = 0;
$extrato_row = false;
$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y', strtotime('-15 days'));
$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
$id_banco = (get('id_banco')) ? get('id_banco') : -1;

if($id_banco > 0) {
	$saldoinicial = $faturamento->getSaldoInicial($dataini, $id_banco);
	$saldofinal = $faturamento->getSaldoTotal($datafim, $id_banco);
	$extrato_row = $extrato->getExtrato_view($dataini, $datafim, $id_banco);
}
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('.buscarextrato').click(function() {
			var id_banco = $("#id_banco").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			window.location.href = 'index.php?do=extrato&acao=extrato&dataini='+ dataini +'&datafim='+ datafim +'&id_banco='+id_banco;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EXTRATO_SISTEMA');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-sort-numeric-asc font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('EXTRATO');?></span>
							</div>
							<div class="actions btn-set">
								<a href="javascript:void(0);" class="btn btn-sm <?php echo $core->primeira_cor;?>" onclick="javascript:void window.open('pdf_extrato.php?dataini=<?php echo $dataini;?>&datafim=<?php echo $datafim;?>&id_banco=<?php echo $id_banco;?>','<?php echo lang('EXTRATO');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="id_banco" id="id_banco" data-placeholder="<?php echo lang('SELECIONE_BANCO');?>" >
										<option value=""></option>
										<?php
											$retorno_row = $faturamento->getBancos();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
										?>
													<option value='<?php echo $srow->id;?>' <?php if($srow->id == $id_banco) echo 'selected="selected"';?>><?php echo $srow->banco;?></option>
										<?php
												endforeach;
												unset($srow);
											endif;
										?>
									</select>
									&nbsp;&nbsp;
									<input type="text" class="form-control input-medium calendario data" name="dataini" id="dataini" value="<?php echo $dataini;?>" >
									<input type="text" class="form-control input-medium calendario data" name="datafim" id="datafim" value="<?php echo $datafim;?>" >
									&nbsp;&nbsp;
									<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor;?> buscarextrato' title='<?php echo lang('BUSCAR');?>'><i class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR');?></a>
								</div>
							</form>
						</div>
						<?php
								$saldo = $saldoinicial;
								if($extrato_row):
						?>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable">
								<thead>
									<tr>
										<th><?php echo lang('DATA');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th width="100px"><?php echo lang('VALOR');?></th>
										<th width="100px"><?php echo lang('SALDO');?></th>
										<th><?php echo lang('TIPO');?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo ($dataini);?></td>
										<td><?php echo lang('SALDO');?></td>
										<td>-</td>
										<td><strong <?php echo ($saldoinicial < 0) ? 'class="font-red"' : 'class="font-green"'?>><?php echo moedap($saldoinicial);?></strong></td>
										<td>-</td>

									</tr>
								<?php
										foreach ($extrato_row as $exrow):
											$descricao = '';
											if($exrow->tipo == 'D') {
												$saldo -= $exrow->valor;
												if($exrow->ti_ch == 1) {
													$descricao = 'CHEQUE - ';
												}
												$descricao .= $exrow->descricao;
											} elseif($exrow->tipo == 'C') {
												$saldo += $exrow->valor;
												$descricao = $exrow->descricao;
											} else {
												$saldo += $exrow->valor;
												$descricao = $exrow->conta;
											}
								?>
									<tr>
										<td><?php echo exibedata($exrow->data_pagamento);?></td>
										<td><?php echo $descricao;?></td>
										<td><strong <?php echo ($exrow->tipo == 'D') ? 'class="font-red"' : 'class="font-green"'?>><?php echo moedap($exrow->valor);?></strong></td>
										<td><strong <?php echo ($saldo < 0) ? 'class="font-red"' : 'class="font-green"'?>><?php echo moedap($saldo);?></strong></td>
										<td><?php echo $exrow->tipo;?></td>
									</tr>
								<?php endforeach;?>
								<?php unset($exrow);?>
								</tbody>
							</table>
						</div>
						<?php   else:?>

						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance">
								<thead>
									<tr>
										<th><?php echo lang('DATA');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th width="100px"><?php echo lang('VALOR');?></th>
										<th width="100px"><?php echo lang('SALDO');?></th>
										<th><?php echo lang('TIPO');?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="5"><?php echo lang('MSG_ERRO_EXTRATO');?></td>
									</tr>
								</tbody>
							</table>
						</div>

						<?php   endif;?>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "arquivobanco":
	$id_banco = (get('id_banco')) ? get('id_banco') : -1;
?>
	<!-- Plupload -->
	<link rel="stylesheet" type="text/css" href="./assets/plugins/plupload/css/jquery.plupload.queue.css"/>

	<script type="text/javascript" src="./assets/plugins/plupload/plupload.full.js"></script>
	<script type="text/javascript" src="./assets/plugins/plupload/jquery.plupload.queue.js"></script>
	<script type="text/javascript" src="./assets/plugins/plupload/i18n/pt_BR.js"></script>
	<script type="text/javascript" src="./assets/scripts/fileupload.js"></script>
	<script>
		jQuery(document).ready(function() {
			FormFileUpload.init();
		});
	</script>
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	<div class="page-head">
		<div class="container">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EXTRATO_ARQUIVOBANCOS');?></small></h1>
			</div>
			<!-- END PAGE TITLE -->
		</div>
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="portlet light">
				<form action="" autocomplete="off" class="form-inline" method="post" id="admin_form" name="admin_form">
					<div class="portlet-body">
						<div class="form-group">
							<select class="select2me form-control input-large" name="id_banco" data-placeholder="<?php echo lang('SELECIONE_BANCO');?>" >
								<option value=""></option>
								<?php
									$retorno_row = $faturamento->getBancos();
									if ($retorno_row):
										foreach ($retorno_row as $srow):
								?>
											<option value='<?php echo $srow->id;?>'><?php echo $srow->banco;?></option>
								<?php
										endforeach;
										unset($srow);
									endif;
								?>
							</select>
						</div>
					</div>
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-info">
									<div class="panel-heading">
										<h3 class="panel-title"><?php echo lang('EXTRATO_ARQUIVOBANCOS');?></h3>
									</div>
									<div class="panel-body">
										<?php echo lang('EXTRATO_BANCO_DESCRICAO');?>
									</div>
								</div>
								<div class="plupload"></div>
								<input name="processarArquivoBanco" type="hidden" value="1" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->
<?php break;?>
<?php case "arquivoboletos":
$id_empresa = session('idempresa');
$banco_boleto = getValue("boleto_banco", "empresa", "id = ".$id_empresa);
?>
	<!-- Plupload -->
	<link rel="stylesheet" type="text/css" href="./assets/plugins/plupload/css/jquery.plupload.queue.css"/>

	<script type="text/javascript" src="./assets/plugins/plupload/plupload.full.js"></script>
	<script type="text/javascript" src="./assets/plugins/plupload/jquery.plupload.queue.js"></script>
	<script type="text/javascript" src="./assets/plugins/plupload/i18n/pt_BR.js"></script>
	<script type="text/javascript" src="./assets/scripts/fileupload.js"></script>
	<script>
		jQuery(document).ready(function() {
			FormFileUpload.init();
		});
	</script>

<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#boleto_remessa').click(function() {
			var id_banco = $("#id_banco").val();
			var banco_boleto = $("#banco_boleto").val();
			var id_empresa = $("#id_empresa").val();
			window.open('boleto_'+banco_boleto+'_remessa.php?&id_banco='+ id_banco +'&id_empresa='+ id_empresa,'Remessa de boletos','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});

        $('#confirmar_remessa').click(function(){
			var id_banco = $("#id_banco").val();
			var aviso = "Você confirmar o envio da remessa com sucesso?";
			bootbox.dialog({
                    message: aviso,
                    title: "Confirmar Remessa",
                    buttons: {
                      salvar: {
                        label: "CONFIRMAR",
                        className: "green",
                        callback: function() {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'confirmarRemessa=1&id_banco='+id_banco,
								success: function( data )
								{
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										stackup_spacing: 10
									});
									if(response[2] == "1")
										setTimeout(function(){
											window.location.href=response[3];
										}, 1000);
								}
							});
                        }
                      },
					  voltar: {
                        label: "Voltar",
                        className: "default"
                      }
                    }
			});
			return false;
		});
	});
	// ]]>
</script>
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	<div class="page-head">
		<div class="container">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EXTRATO_ARQUIVOBOLETOS');?></small></h1>
			</div>
			<!-- END PAGE TITLE -->
		</div>
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<input name="banco_boleto" id="banco_boleto" type="hidden" value="<?php echo $banco_boleto; ?>" />
						<input name="id_empresa" id="id_empresa" type="hidden" value="<?php echo $id_empresa; ?>" />
						<form action="" autocomplete="off" class="form-inline" method="post" id="admin_form" name="admin_form">
							<?php $retorno_row = $faturamento->getBancosRemessa();
								  if ($retorno_row):
							?>
							<div class="portlet-body">
								<div class="form-group">
									<select class='form-control input-large' id='id_banco' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
									<?php
											foreach ($retorno_row as $srow):
									?>
												<option value='<?php echo $srow->id_banco;?>'><?php echo $srow->banco;?></option>
									<?php
											endforeach;
											unset($srow);
									?>
									</select>
									&nbsp;&nbsp;
									<button type="button" id="boleto_remessa" class="btn yellow-crusta"><i class="fa fa-plus-square"/></i> <?php echo lang('EXTRATO_REMESSA');?></button>
									<button type="button" id="confirmar_remessa" class="btn blue"><i class="fa fa-check"/></i> <?php echo lang('FINANCEIRO_REMESSA_CONFIRMAR');?></button>
								</div>
							</div>
							<?php 	else: ?>
							<div class="portlet-body">
								<div class="row">
									<div class="col-md-12">
										<div class="note note-warning">
											<p><?php echo lang('MSG_ERRO_BANCO_REMESSA');?></p>
										</div>
									</div>
								</div>
							</div>
							<?php 	endif;?>
							<div class="portlet-body">
								<div class="row">
									<div class="col-md-12">
										<div class="panel panel-info">
											<div class="panel-heading">
												<h3 class="panel-title"><?php echo lang('EXTRATO_ARQUIVOBOLETOS');?></h3>
											</div>
											<div class="panel-body">
												<?php echo lang('EXTRATO_BOLETOS_DESCRICAO');?>
											</div>
										</div>
										<div class="plupload"></div>
										<input name="processarBoletoSicoob" type="hidden" value="1" />
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('EXTRATO_ARQUIVOBOLETOS');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('PAGO');?></th>
										<th><?php echo lang('DOCUMENTO');?></th>
										<th><?php echo lang('NOME');?></th>
										<th><?php echo lang('DATA_VENCIMENTO');?></th>
										<th><?php echo lang('DATA_BAIXA');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
										<th><?php echo lang('DATA_CREDITO');?></th>
										<th><?php echo lang('VALOR_LIQUIDO');?></th>
										<th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $boleto->getBoletos();
										if($retorno_row):
										foreach ($retorno_row as $exrow):?>
									<tr>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo ($exrow->valor_pago) ? ($exrow->pago) ? '<span class="label label-sm bg-green">'.lang('MSIM').'</span>' : '<span class="label label-sm bg-red">'.lang('MNAO').'</span>' : '<span class="label label-sm bg-yellow-crusta">'.lang('MDESCONHECIDO').'</span>';?></td>
										<td><?php echo $exrow->nosso_numero;?></td>
										<td><?php echo $exrow->empresa;?></td>
										<td><?php echo exibedata($exrow->data_vencimento);?></td>
										<td><?php echo exibedata($exrow->data_pagamento);?></td>
										<td><?php echo moedap($exrow->valor_pago)?></td>
										<td><?php echo exibedata($exrow->data_banco);?></td>
										<td><?php echo moedap($exrow->valor_liquido);?></td>
										<td>
										<?php if($exrow->id > 0):?>
										<a href="javascript:void(0);" onclick="javascript:void window.open('ver_receita.php?id_receita=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
										<?php endif;?>
										</td>
									</tr>
								<?php endforeach;?>
								<?php unset($exrow);
									  endif;?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->
<?php break;?>
<?php case "conciliardespesas": ?>
<?php
	$ordernar = (get('ordernar')) ? get('ordernar') : 'dataasc';
	$id_banco = (get('id_banco')) ? get('id_banco') : -1;
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ordernar').change(function() {
			var ordernar = $("#ordernar").val();
			var id_banco = $("#id_banco").val();
			window.location.href = 'index.php?do=extrato&acao=conciliardespesas&ordernar='+ordernar+'&id_banco='+id_banco;
		});
	});
	// ]]>
</script>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#id_banco').change(function() {
			var ordernar = $("#ordernar").val();
			var id_banco = $("#id_banco").val();
			window.location.href = 'index.php?do=extrato&acao=conciliardespesas&ordernar='+ordernar+'&id_banco='+id_banco;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EXTRATO_CONCILIARDESPESAS');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-link font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('EXTRATO_CONCILIARDESPESAS');?></span>
							</div>
							<div class="actions btn-set">
								<a href="javascript:void(0);" class="btn btn-sm <?php echo $core->primeira_cor;?> conciliarbanco"><i class="fa fa-check">&nbsp;&nbsp;</i><?php echo lang('CONCILIAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="ordernar" id="ordernar" data-placeholder="<?php echo lang('ORDERNAR');?>" >
											<option value=""></option>
											<option value="dataasc" <?php if($ordernar == "dataasc") echo 'selected="selected"';?>><?php echo lang('EXTRATO_MENORDATA');?></option>
											<option value="datadesc" <?php if($ordernar == "datadesc") echo 'selected="selected"';?>><?php echo lang('EXTRATO_MAIORDATA');?></option>
											<option value="valorasc" <?php if($ordernar == "valorasc") echo 'selected="selected"';?>><?php echo lang('EXTRATO_MENORVALOR');?></option>
											<option value="valordesc" <?php if($ordernar == "valordesc") echo 'selected="selected"';?>><?php echo lang('EXTRATO_MAIORVALOR');?></option>
									</select>
									&nbsp;&nbsp;
									<select class="select2me form-control input-large" name="id_banco" id="id_banco" data-placeholder="<?php echo lang('SELECIONE_BANCO');?>" >
										<option value=""></option>
										<?php
											$retorno_row = $faturamento->getBancos();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
										?>
													<option value='<?php echo $srow->id;?>' <?php if($srow->id == $id_banco) echo 'selected="selected"';?>><?php echo $srow->banco;?></option>
										<?php
												endforeach;
												unset($srow);
											endif;
										?>
									</select>
								</div>
							</form>
						</div>
						<div class="portlet-body">
							<form action="" autocomplete="off" method="post" id="admin_form" name="admin_form">
								<table class="table table-bordered table-striped table-condensed table-advance">
									<thead>
										<tr>
											<th colspan="3"><strong><?php echo lang('EXTRATO_BANCO');?></strong></th>
											<th colspan="4"><strong><?php echo lang('EXTRATO_DESPESAS');?></strong></th>
										</tr>
										<tr class="info">
											<td width="10%"><?php echo lang('DATA');?></td>
											<td width="20%"><?php echo lang('DETALHES');?></td>
											<td width="15%"><?php echo lang('VALOR');?></td>
											<td width="10%"><?php echo lang('DATA');?></td>
											<td width="25%"><?php echo lang('DETALHES');?></td>
											<td width="15%"><?php echo lang('VALOR');?></td>
											<td width="5%"></td>
										</tr>
									</thead>
									<tbody>
									<?php
										$totalbanco = 0;
										$totalsistema = 0;
										$retorno_row = $extrato->getExtratoDespesas($id_banco);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
										$totalbanco += $valor = $exrow->valor;
										$despesa_row = $extrato->pesquisarDespesas($exrow->data, $valor, $exrow->id_banco);
										$contador = (is_array($despesa_row) ? count($despesa_row) : 0);
										if(!$despesa_row):

									?>
										<tr>
											<td><?php echo exibedata($exrow->data);?></td>
											<td><?php echo $exrow->historico;?></td>
											<td><?php echo moedap($valor);?></td>
											<td colspan="4">
												<a href="javascript:void(0);" onclick="javascript:void window.open('nova_despesa.php?data=<?php echo exibedata($exrow->data);?>&descricao=<?php echo strtoupper($exrow->historico);?>&id_banco=<?php echo $id_banco;?>&valor=<?php echo moedap($valor);?>','<?php echo lang('FINANCEIRO_ADICIONAR');?>','width=1000,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('FINANCEIRO_ADICIONAR');?>" class="btn btn-xs green"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_ADICIONAR');?></a>
											</td>
										</tr>
									<?php
										elseif($contador == 1):
									?>
										<tr>
											<td><?php echo exibedata($exrow->data);?></td>
											<td><?php echo $exrow->historico;?></td>
											<td><?php echo moedap($valor);?></td>
									<?php
										foreach ($despesa_row as $drow):
										$totalsistema += $drow->valor;
									?>
											<?php
												if($drow->conciliado):
													if($exrow->id == $drow->extrato_doc):
													?>
														<td style="background:#838383;color:#fefefe"><?php echo exibedata($drow->data_pagamento);?></td>
														<td style="background:#838383;color:#fefefe"><?php echo $drow->id." # ".$drow->descricao;?></td>
														<td style="background:#838383;color:#fefefe"><?php echo moedap($drow->valor);?></td>
														<td style="background:#838383;color:#fefefe">
															<a href='javascript:void(0);' class='btn btn-xs default cancelarconciliacao' id='<?php echo $exrow->id;?>' title='<?php echo lang('CANCELAR').$exrow->historico;?>'><i class='fa fa-unlink'></i></a>
														</td>
													<?php
													elseif($exrow->conciliado):
													?>
														<td><input  class="md-radiobtn" type="radio" name="<?php echo $exrow->id;?>" value="<?php echo $exrow->documento."#".$exrow->data."#".$drow->id."#".$exrow->id;?>" checked>
															<?php echo exibedata($drow->data_pagamento);?></td>
														<td><?php echo $drow->id." # ".$drow->descricao;?></td>
														<td><?php echo moedap($drow->valor);?></td>
														<td></td>
													<?php
													else:
													?>
														<td><input  class="md-radiobtn" type="radio" name="<?php echo $exrow->id;?>" value="<?php echo $exrow->documento."#".$exrow->data."#".$drow->id."#".$exrow->id;?>" checked>
															<?php echo exibedata($drow->data_pagamento);?></td>
														<td><?php echo $drow->id." # ".$drow->descricao;?></td>
														<td><?php echo moedap($drow->valor);?></td>
														<td></td>
													<?php
													endif;
												else:
											?>
												<td><input  class="md-radiobtn" type="radio" name="<?php echo $exrow->id;?>" value="<?php echo $exrow->documento."#".$exrow->data."#".$drow->id."#".$exrow->id;?>" checked>
													<?php echo exibedata($drow->data_pagamento);?></td>
												<td><?php echo $drow->id." # ".$drow->descricao;?></td>
												<td><?php echo moedap($drow->valor);?></td>
												<td></td>
											<?php
												endif;
											?>
										</tr>
									<?php endforeach;
										  unset($drow);
										elseif($exrow->conciliado == 1):
											$row_despesa = Core::getRowById('despesa', $exrow->id_ref);
										?>
											<tr>
												<td><?php echo exibedata($exrow->data);?></td>
												<td><?php echo $exrow->historico;?></td>
												<td><?php echo moedap($valor);?></td>
												<td style="background:#838383;color:#fefefe"><?php echo exibedata($row_despesa->data_pagamento);?></td>
												<td style="background:#838383;color:#fefefe"><?php echo $row_despesa->id." # ".$row_despesa->descricao;?></td>
												<td style="background:#838383;color:#fefefe"><?php echo moedap($row_despesa->valor);?></td>
												<td style="background:#838383;color:#fefefe">
													<a href='javascript:void(0);' class='btn btn-xs default cancelarconciliacao' id='<?php echo $exrow->id;?>' title='<?php echo lang('CANCELAR').$exrow->historico;?>'><i class='fa fa-unlink'></i></a>
												</td>
											</tr>
										<?php
										else:
										  $contador++;
										  $total_conciliado = 0;
										  foreach ($despesa_row as $drow) {
											  $total_conciliado += ($drow->conciliado) ? 1 : 0;
										  }
										  $contador -= $total_conciliado;

									?>
										<tr>
											<td rowspan="<?php echo $contador;?>"><?php echo exibedata($exrow->data);?></td>
											<td rowspan="<?php echo $contador;?>"><?php echo $exrow->historico;?></td>
											<td rowspan="<?php echo $contador;?>"><?php echo moedap($valor);?></td>
										<?php
											if($contador == 1):
										?>
											<td colspan="4">
												<a href="javascript:void(0);" onclick="javascript:void window.open('nova_despesa.php?data=<?php echo exibedata($exrow->data);?>&descricao=<?php echo strtoupper($exrow->historico);?>&id_banco=<?php echo $id_banco;?>&valor=<?php echo moedap($valor);?>','<?php echo lang('FINANCEIRO_ADICIONAR');?>','width=1000,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('FINANCEIRO_ADICIONAR');?>" class="btn btn-xs green"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_ADICIONAR');?></a>
											</td>
										<?php
											endif;
										?>
										</tr>
										<?php
											$check = "";
											$primeira = true;
											foreach ($despesa_row as $drow):
												$check = ($drow->conciliado) ? "checked" : "";
												$totalsistema += ($primeira) ? $drow->valor: 0 ;
												$primeira = false;
												if($drow->conciliado and $exrow->id == $drow->extrato_doc):
											?>
												<tr>
													<td style="background:#838383;color:#fefefe"><input class="md-radiobtn" type="radio" name="<?php echo $exrow->id;?>" value="<?php echo $exrow->documento."#".$exrow->data."#".$drow->id."#".$exrow->id;?>" checked>
														<?php echo exibedata($drow->data_pagamento);?></td>
													<td style="background:#838383;color:#fefefe"><?php echo $drow->id." # ".$drow->descricao;?></td>
													<td style="background:#838383;color:#fefefe"><?php echo moedap($drow->valor);?></td>
													<td style="background:#838383;color:#fefefe">
														<a href='javascript:void(0);' class='btn btn-xs default cancelarconciliacao' id='<?php echo $exrow->id;?>' title='<?php echo lang('CANCELAR').$exrow->historico;?>'><i class='fa fa-unlink'></i></a>
													</td>
												</tr>
											<?php
												elseif($drow->conciliado == 0):
											?>
												<tr>
													<td><input class="md-radiobtn" type="radio" name="<?php echo $exrow->id;?>" value="<?php echo $exrow->documento."#".$exrow->data."#".$drow->id."#".$exrow->id;?>" checked>
														<?php echo exibedata($drow->data_pagamento);?></td>
													<td><?php echo $drow->id." # ".$drow->descricao;?></td>
													<td><?php echo moedap($drow->valor);?></td>
													<td></td>
												</tr>
											<?php
												endif;
											?>
										<?php	 endforeach;
												unset($drow);
												endif;
										?>
									<?php endforeach;?>
									<?php unset($exrow);
										  endif;?>
										  <tr>
											<td colspan="2"><strong><?php echo lang('TOTAL');?></strong></td>
											<td><strong><?php echo moedap($totalbanco);?></strong></td>
											<td colspan="2"><strong></strong></td>
											<td><strong><?php echo moedap($totalsistema);?></strong></td>
											<td></td>
										</tr>
									</tbody>
									<tfoot>
										 <tr>
											<td colspan="5"></td>
											<td><a href="javascript:void(0);" class="btn btn-sm <?php echo $core->primeira_cor;?> conciliarbanco"><i class="fa fa-check">&nbsp;&nbsp;</i><?php echo lang('CONCILIAR');?></a></td>
											<td></td>
										</tr>
									</tfoot>
								</table>
								<input name="conciliarBanco" type="hidden" value="1" />
								<input name="id_banco" type="hidden" value="<?php echo $id_banco;?>" />
							</form>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "conciliarreceitas":
	$id_banco = (get('id_banco')) ? get('id_banco') : -1;
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#id_banco').change(function() {
			var id_banco = $("#id_banco").val();
			window.location.href = 'index.php?do=extrato&acao=conciliarreceitas&id_banco='+id_banco;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EXTRATO_CONCILIARRECEITAS');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-link font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('EXTRATO_CONCILIARRECEITAS');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="id_banco" id="id_banco" data-placeholder="<?php echo lang('SELECIONE_BANCO');?>" >
										<option value=""></option>
										<?php
											$retorno_row = $faturamento->getBancos();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
										?>
													<option value='<?php echo $srow->id;?>' <?php if($srow->id == $id_banco) echo 'selected="selected"';?>><?php echo $srow->banco;?></option>
										<?php
												endforeach;
												unset($srow);
											endif;
										?>
									</select>
								</div>
							</form>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance">
								<thead>
									<tr>
										<th><strong><?php echo lang('DATA');?></strong></th>
										<th><?php echo lang('DESCRICAO');?></th>
                                        <th><?php echo lang('BANCO');?></th>
                                        <th><?php echo lang('SISTEMA');?></th>
                                        <th><?php echo lang('CARTAO');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $extrato->getExtratoReceitas($id_banco);
										$data = '-';
										$total = 0;
										$boleto = false;
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											if($data == '-') {
												$data = $exrow->data;
											}
											if ($data <> $exrow->data):
												$valor_boletos = $extrato->getExtratoBoletos($id_banco, $data);
												if($valor_boletos > 0):
													$valor_sistema = $extrato->getReceitaSistema($id_banco, $data, "CR COB");
								?>
									<tr>
										<td><?php echo exibedata($data);?></td>
										<td><?php echo "CR COB - TOTAL DE BOLETOS NO DIA";?></td>
										<td>
											<strong>
												<a href="javascript:void(0);" onclick="javascript:void window.open('ver_boletos.php?data=<?php echo $data;?>&id_banco=<?php echo $id_banco;?>','<?php echo lang('BOLETOS');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;">
												<?php echo moedap($valor_boletos);?></a>
											</strong>
										</td>
										<td>[1]
											<strong>
												<?php if($valor_sistema): ?>
												<a href="javascript:void(0);" onclick="javascript:void window.open('ver_sistema.php?data=<?php echo $data;?>&id_banco=<?php echo $id_banco;?>&historico=CR COB','<?php echo lang('SISTEMA');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;">
												<?php echo moedap($valor_sistema);?></a>
												<?php else:
														echo moedap(0);
													  endif; ?>
											</strong>
										</td>
										<td><strong><?php echo moedap(0);?></strong></td>
									</tr>
								<?php
												endif;
												$valor_sistema = $extrato->getReceitaSistema($id_banco, $data, "TODOS");
								?>
									<tr class="info">
										<td><?php echo exibedata($data);?></td>
										<td><?php echo "TODOS LANCAMENTOS";?></td>
										<td>
											<strong>
												<?php echo moedap(0);?>
											</strong>
										</td>
										<td>[2]
											<strong>
												<?php if($valor_sistema): ?>
												<a href="javascript:void(0);" onclick="javascript:void window.open('ver_sistema.php?data=<?php echo $data;?>&id_banco=<?php echo $id_banco;?>&historico=TODOS','<?php echo lang('SISTEMA');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;">
												<?php echo moedap($valor_sistema);?></a>
												<?php else:
														echo moedap(0);
													  endif; ?>
											</strong>
										</td>
										<td>
										</td>
									</tr>
								<?php
											endif;
											if(!substr_count($exrow->historico, "CR COB")):
												$valor_sistema = $extrato->getReceitaSistema($id_banco, $exrow->data, $exrow->historico);
								?>
									<tr>
										<td><?php echo exibedata($exrow->data);?></td>
										<td><?php echo $exrow->historico;?></td>
										<td>
											<strong>
												<?php if($exrow->total): ?>
												<a href="javascript:void(0);" onclick="javascript:void window.open('ver_bancos.php?data=<?php echo $exrow->data;?>&id_banco=<?php echo $id_banco;?>&historico=<?php echo $exrow->historico;?>','<?php echo lang('BANCOS');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;">
												<?php echo moedap($exrow->total);?></a>
												<?php else:
														echo moedap(0);
													  endif; ?>
											</strong>
										</td>
										<td>[3]
											<strong>
												<?php if($valor_sistema): ?>
												<a href="javascript:void(0);" onclick="javascript:void window.open('ver_sistema.php?data=<?php echo $exrow->data;?>&id_banco=<?php echo $id_banco;?>&historico=<?php echo $exrow->historico;?>','<?php echo lang('SISTEMA');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;">
												<?php echo moedap($valor_sistema);?></a>
												<?php else:
														echo moedap(0);
													  endif; ?>
											</strong>
										</td>
										<td>
										</td>
									</tr>
								<?php
											endif;
											$data = $exrow->data;
									  endforeach;
									  unset($exrow);
									  endif;?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case 'dre':

	if ($core->tipo_sistema==1 || $core->tipo_sistema==3)
		redirect_to("login.php");

$ano = (get('ano')) ? get('ano') : date('Y');
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0;
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ano').change(function() {
			var ano = $("#ano").val();
			var id_empresa = $("#id_empresa").val();
			window.location.href = 'index.php?do=extrato&acao=dre&ano='+ ano + '&id_empresa='+ id_empresa;
		});
		$('#id_empresa').change(function() {
			var ano = $("#ano").val();
			var id_empresa = $("#id_empresa").val();
			window.location.href = 'index.php?do=extrato&acao=dre&ano='+ ano + '&id_empresa='+ id_empresa;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_DRE');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW TABELA -->
			<div class='row'>
				<div class='col-md-12'>
					<!-- INICIO TABELA -->
					<div class='portlet light'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-file-text font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('FINANCEIRO_DRE');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php
												$retorno_row = $gestao->getListaAno("despesa", "data_pagamento", false, "DESC", true);
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $ano) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
									<select class="select2me form-control input-large" id="id_empresa" data-placeholder="<?php echo lang('SELECIONE_EMPRESA');?>" >
										<option value=""></option>
										<?php
											$retorno_row = $empresa->getEmpresas();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
										?>
												<option value="<?php echo $srow->id;?>" <?php if($srow->id == $id_empresa) echo 'selected="selected"';?>><?php echo $srow->nome;?></option>
										<?php
												endforeach;
												unset($srow);
											endif;
										?>
									</select>
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<div class='table-scrollable'>
								<table class="table table-bordered table-advance">
									<thead>
										<tr>
											<th width="20%"><?php echo lang('FINANCEIRO_RECEITAS');?></th>
											<th>JAN</th>
											<th>FEV</th>
											<th>MAR</th>
											<th>ABR</th>
											<th>MAI</th>
											<th>JUN</th>
											<th>JUL</th>
											<th>AGO</th>
											<th>SET</th>
											<th>OUT</th>
											<th>NOV</th>
											<th>DEZ</th>
											<th><?php echo lang('TOTAL');?></th>
										</tr>
									</thead>
									<tbody>
									<?php
											$destaque = 'info';
											$totalmesreceita = array();
											$totaldrereceita = array();
											$tabela = '';

											$idTable = array();
											$totalmes = array();
											$conta_pai = '';
											$id_pai = 0;

											$totaldrereceita[1] = 0;
											$totaldrereceita[2] = 0;
											$totaldrereceita[3] = 0;
											$totaldrereceita[4] = 0;
											$totaldrereceita[5] = 0;
											$totaldrereceita[6] = 0;
											$totaldrereceita[7] = 0;
											$totaldrereceita[8] = 0;
											$totaldrereceita[9] = 0;
											$totaldrereceita[10] = 0;
											$totaldrereceita[11] = 0;
											$totaldrereceita[12] = 0;
											$totaldrereceita[13] = 0;

											$totalmes = 0;
											for($i=1;$i<13;$i++){
												$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
												$conta_row = $faturamento->getDREMes($mes_ano, $id_empresa);
												foreach ($conta_row as $crow){
													$id = $crow->id;
													$id_pai = $crow->id_pai;
													$totalmes = ($crow->total) ? $crow->total : 0;
													$idTable[$id][$i] = $totalmes;
													if(empty($totalmesreceita[$id_pai][$i])) {
														$totalmesreceita[$id_pai][$i] = 0;
													}
													if(empty($totalmesreceita[$id_pai][13])) {
														$totalmesreceita[$id_pai][13] = 0;
													}
													$totalmesreceita[$id_pai][$i] += $totalmes;
													$totalmesreceita[$id_pai][13] += $totalmes;
												}
												unset($crow);
											}
											$id_pai = 0;
											$conta_row = $faturamento->getContasDRE();
											$primeiro = true;
											foreach ($conta_row as $crow){
												if($id_pai != $crow->id_pai) {
													$id_pai = $crow->id_pai;
													$primeiro = true;
												}
												if($primeiro) {
													$destaque = 'info';
													$tabela .= "<tr class='$destaque'><td><strong>".$crow->conta_pai."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][1])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][2])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][3])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][4])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][5])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][6])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][7])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][8])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][9])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][10])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][11])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][12])."</strong></td>"
																			."<td><strong>".decimalp($totalmesreceita[$id_pai][13])."</strong></td></tr>";
													$primeiro = false;
												}
												$totaldrereceita[13] += $totalmes;
												$totalmes = 0;
												$tabela .= "<tr><td>".$crow->conta."</td>";
												for($i=1;$i<13;$i++){
													$id = $crow->id;
													$totalmes += $total = $idTable[$id][$i];
													$totaldrereceita[$i] +=  $total;
													$tabela .= "<td>".decimalp($total)."</td>";
												}
												$tabela .= "<td>".decimalp($totalmes)."</td>";
												$tabela .= "</tr>";
											}
											$totaldrereceita[13] += $totalmes;

											$destaque = 'info';
											$tabela .= "<tr class='$destaque'><td><strong>".lang('TOTAL')."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[1])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[2])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[3])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[4])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[5])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[6])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[7])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[8])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[9])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[10])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[11])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[12])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[13])."</strong></td>";

											unset($crow);
											echo $tabela;
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div class='portlet-body'>
							<div class='table-scrollable'>
								<table class="table table-bordered table-advance">
									<thead>
										<tr>
											<th width="20%"><?php echo lang('FINANCEIRO_DESPESAS');?></th>
											<th>JAN</th>
											<th>FEV</th>
											<th>MAR</th>
											<th>ABR</th>
											<th>MAI</th>
											<th>JUN</th>
											<th>JUL</th>
											<th>AGO</th>
											<th>SET</th>
											<th>OUT</th>
											<th>NOV</th>
											<th>DEZ</th>
											<th><?php echo lang('TOTAL');?></th>
										</tr>
									</thead>
									<tbody>
									<?php
											//$destaque = 'bg-'.$core->primeira_cor;
											$destaque = 'danger';
											$idTable = array();
											$totalmes = array();
											$totalmesdespesa = array();
											$totalmesmeta = array();
											$totaldredespesa = array();
											$totaldremeta = array();
											$tabela = '';
											$conta_pai = '';
											$id_pai = 0;
											$totaldespesa = 0;
											$totalmeta = 0;
											$totaldredespesa[1] = 0;
											$totaldredespesa[2] = 0;
											$totaldredespesa[3] = 0;
											$totaldredespesa[4] = 0;
											$totaldredespesa[5] = 0;
											$totaldredespesa[6] = 0;
											$totaldredespesa[7] = 0;
											$totaldredespesa[8] = 0;
											$totaldredespesa[9] = 0;
											$totaldredespesa[10] = 0;
											$totaldredespesa[11] = 0;
											$totaldredespesa[12] = 0;
											$totaldredespesa[13] = 0;
											$totaldremeta[1] = 0;
											$totaldremeta[2] = 0;
											$totaldremeta[3] = 0;
											$totaldremeta[4] = 0;
											$totaldremeta[5] = 0;
											$totaldremeta[6] = 0;
											$totaldremeta[7] = 0;
											$totaldremeta[8] = 0;
											$totaldremeta[9] = 0;
											$totaldremeta[10] = 0;
											$totaldremeta[11] = 0;
											$totaldremeta[12] = 0;
											$totaldremeta[13] = 0;
											for($i=1;$i<13;$i++){
												$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
												$conta_row = $despesa->getDREMes($mes_ano, $id_empresa);
												foreach ($conta_row as $crow){
													if(empty($totalmesmeta[$crow->id_pai][13])) {
														$totalmesmeta[$crow->id_pai][13] = 0;
													}
													if($id_pai != $crow->id_pai) {
														$totalmeta = $faturamento->getMetasDRE($mes_ano, $crow->id_pai);
														$totalmesmeta[$crow->id_pai][$i] = $totalmeta;
														$totalmesmeta[$crow->id_pai][13] += $totalmeta;
														$totaldremeta[$i] += $totalmeta;
														$totaldremeta[13] += $totalmeta;
													}
													$id = $crow->id;
													$id_pai = $crow->id_pai;
													$totaldespesa = ($crow->total) ? $crow->total : 0;
													$idTable[$id][$i] = $totaldespesa;
													if(empty($totalmesdespesa[$id_pai][$i])) {
														$totalmesdespesa[$id_pai][$i] = 0;
													}
													if(empty($totalmesdespesa[$id_pai][13])) {
														$totalmesdespesa[$id_pai][13] = 0;
													}
													$totalmesdespesa[$id_pai][$i] += $totaldespesa;
													$totalmesdespesa[$id_pai][13] += $totaldespesa;
												}
												unset($crow);
											}
											$id_pai = 0;
											$conta_row = $despesa->getContas();
											$primeiro = true;
											foreach ($conta_row as $crow){
												if($id_pai != $crow->id_pai) {
													$id_pai = $crow->id_pai;
													$primeiro = true;
												}
												if($primeiro) {
													$destaque = 'info';
													$tabela .= "<tr class='$destaque'><td><strong>".$crow->conta_pai."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][1])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][2])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][3])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][4])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][5])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][6])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][7])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][8])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][9])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][10])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][11])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][12])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][13])."</strong></td></tr>";

													$tabela .= "<tr><td class='$destaque'><strong>".lang('META')."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][1] > $totalmesmeta[$id_pai][1]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][1])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][2] > $totalmesmeta[$id_pai][2]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][2])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][3] > $totalmesmeta[$id_pai][3]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][3])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][4] > $totalmesmeta[$id_pai][4]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][4])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][5] > $totalmesmeta[$id_pai][5]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][5])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][6] > $totalmesmeta[$id_pai][6]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][6])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][7] > $totalmesmeta[$id_pai][7]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][7])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][8] > $totalmesmeta[$id_pai][8]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][8])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][9] > $totalmesmeta[$id_pai][9]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][9])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][10] > $totalmesmeta[$id_pai][10]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][10])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][11] > $totalmesmeta[$id_pai][12]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][11])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][12] > $totalmesmeta[$id_pai][12]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][12])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][13] > $totalmesmeta[$id_pai][13]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][13])."</strong></td></tr>";
													$primeiro = false;
												}
												$totaldredespesa[13] += $totaldespesa;
												$totaldespesa = 0;
												$tabela .= "<tr><td>".$crow->conta."</td>";
												for($i=1;$i<13;$i++){
													$id = $crow->id;
													$totaldespesa += $total = $idTable[$id][$i];
													$totaldredespesa[$i] +=  $total;
													// $tabela .= "<td>".decimalp($total)."</td>";
													$tabela .= ($total > 0) ? '<td><a href="ver_dre.php?id_conta='.$id.'&mes='.$i.'&ano='.$ano.'&id_empresa='.$id_empresa.'" target="_blank">'.decimalp($total).'</a></td>' : "<td>".decimalp($total)."</td>";
												}
												$tabela .= "<td>".decimalp($totaldespesa)."</td>";
												$tabela .= "</tr>";
											}
											$totaldredespesa[13] += $totaldespesa;

											$destaque = 'info';
											$tabela .= "<tr class='$destaque'><td><strong>".lang('TOTAL')."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[1])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[2])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[3])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[4])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[5])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[6])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[7])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[8])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[9])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[10])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[11])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[12])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[13])."</strong></td>";

											$tabela .= "<tr><td class='$destaque'><strong>".lang('META')."</strong></td>";
													$destaque =  ($totaldredespesa[1] > $totaldremeta[1]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[1])."</strong></td>";
													$destaque =  ($totaldredespesa[2] > $totaldremeta[2]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[2])."</strong></td>";
													$destaque =  ($totaldredespesa[3] > $totaldremeta[3]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[3])."</strong></td>";
													$destaque =  ($totaldredespesa[4] > $totaldremeta[4]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[4])."</strong></td>";
													$destaque =  ($totaldredespesa[5] > $totaldremeta[5]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[5])."</strong></td>";
													$destaque =  ($totaldredespesa[6] > $totaldremeta[6]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[6])."</strong></td>";
													$destaque =  ($totaldredespesa[7] > $totaldremeta[7]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[7])."</strong></td>";
													$destaque =  ($totaldredespesa[8] > $totaldremeta[8]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[8])."</strong></td>";
													$destaque =  ($totaldredespesa[9] > $totaldremeta[9]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[9])."</strong></td>";
													$destaque =  ($totaldredespesa[10] > $totaldremeta[10]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[10])."</strong></td>";
													$destaque =  ($totaldredespesa[11] > $totaldremeta[12]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[11])."</strong></td>";
													$destaque =  ($totaldredespesa[12] > $totaldremeta[12]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[12])."</strong></td>";
													$destaque =  ($totaldredespesa[13] > $totaldremeta[13]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[13])."</strong></td></tr>";

											unset($crow);
											echo $tabela;
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div class='portlet-body'>
							<div class='table-scrollable'>
								<table class="table table-bordered table-advance">
									<thead>
										<tr>
											<th width="20%"><?php echo lang('RESULTADO');?></th>
											<th>JAN</th>
											<th>FEV</th>
											<th>MAR</th>
											<th>ABR</th>
											<th>MAI</th>
											<th>JUN</th>
											<th>JUL</th>
											<th>AGO</th>
											<th>SET</th>
											<th>OUT</th>
											<th>NOV</th>
											<th>DEZ</th>
											<th><?php echo lang('TOTAL');?></th>
										</tr>
									</thead>
									<tbody>
									<?php
											$tabela = '';
											$destaque = 'success';
											$tabela .= "<tr class='$destaque'><td><strong>".lang('FINANCEIRO_RECEITAS')."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[1])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[2])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[3])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[4])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[5])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[6])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[7])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[8])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[9])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[10])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[11])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[12])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[13])."</strong></td></tr>";
											$destaque = 'danger';
											$tabela .= "<tr class='$destaque'><td><strong>".lang('FINANCEIRO_DESPESAS')."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[1])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[2])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[3])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[4])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[5])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[6])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[7])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[8])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[9])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[10])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[11])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[12])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[13])."</strong></td>";
											$destaque = 'info';
											$tabela .= "<tr><td class='$destaque'><strong>".lang('META')."</strong></td>";
													$destaque =  ($totaldredespesa[1] > $totaldremeta[1]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[1])."</strong></td>";
													$destaque =  ($totaldredespesa[2] > $totaldremeta[2]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[2])."</strong></td>";
													$destaque =  ($totaldredespesa[3] > $totaldremeta[3]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[3])."</strong></td>";
													$destaque =  ($totaldredespesa[4] > $totaldremeta[4]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[4])."</strong></td>";
													$destaque =  ($totaldredespesa[5] > $totaldremeta[5]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[5])."</strong></td>";
													$destaque =  ($totaldredespesa[6] > $totaldremeta[6]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[6])."</strong></td>";
													$destaque =  ($totaldredespesa[7] > $totaldremeta[7]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[7])."</strong></td>";
													$destaque =  ($totaldredespesa[8] > $totaldremeta[8]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[8])."</strong></td>";
													$destaque =  ($totaldredespesa[9] > $totaldremeta[9]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[9])."</strong></td>";
													$destaque =  ($totaldredespesa[10] > $totaldremeta[10]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[10])."</strong></td>";
													$destaque =  ($totaldredespesa[11] > $totaldremeta[12]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[11])."</strong></td>";
													$destaque =  ($totaldredespesa[12] > $totaldremeta[12]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[12])."</strong></td>";
													$destaque =  ($totaldredespesa[13] > $totaldremeta[13]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[13])."</strong></td></tr>";
											$destaque = 'info';
											$tabela .= "<tr class='$destaque'><td><strong>".lang('TOTAL')."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[1] - $totaldredespesa[1])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[2] - $totaldredespesa[2])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[3] - $totaldredespesa[3])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[4] - $totaldredespesa[4])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[5] - $totaldredespesa[5])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[6] - $totaldredespesa[6])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[7] - $totaldredespesa[7])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[8] - $totaldredespesa[8])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[9] - $totaldredespesa[9])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[10] - $totaldredespesa[10])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[11] - $totaldredespesa[11])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[12] - $totaldredespesa[12])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[13] - $totaldredespesa[13])."</strong></td>";
											$tabela .= "<tr><td><strong>".lang('MARGEM')."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[1], $totaldredespesa[1])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[2], $totaldredespesa[2])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[3], $totaldredespesa[3])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[4], $totaldredespesa[4])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[5], $totaldredespesa[5])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[6], $totaldredespesa[6])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[7], $totaldredespesa[7])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[8], $totaldredespesa[8])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[9], $totaldredespesa[9])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[10], $totaldredespesa[10])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[11], $totaldredespesa[11])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[12], $totaldredespesa[12])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[13], $totaldredespesa[13])."</strong></td>";

											echo $tabela;
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case 'previsao':
$ano = (get('ano')) ? get('ano') : date('Y');

?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ano').change(function() {
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=extrato&acao=previsao&ano='+ ano;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('DRE_PREVISAO');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW TABELA -->
			<div class='row'>
				<div class='col-md-12'>
					<!-- INICIO TABELA -->
					<div class='portlet light'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-file-text font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('DRE_PREVISAO');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php
												$retorno_row = $gestao->getListaAno("despesa", "data_pagamento", false, "DESC", true);
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $ano) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<div class='table-scrollable'>
								<table class="table table-bordered table-advance">
									<thead>
										<tr>
											<th width="20%"><?php echo lang('FINANCEIRO_RECEITAS');?></th>
											<th>JAN</th>
											<th>FEV</th>
											<th>MAR</th>
											<th>ABR</th>
											<th>MAI</th>
											<th>JUN</th>
											<th>JUL</th>
											<th>AGO</th>
											<th>SET</th>
											<th>OUT</th>
											<th>NOV</th>
											<th>DEZ</th>
											<th><?php echo lang('TOTAL');?></th>
										</tr>
									</thead>
									<tbody>
									<?php
											$destaque = 'info';
											$totaldrereceita = array();
											$tabela = '';

											$totaldrereceita[1] = 0;
											$totaldrereceita[2] = 0;
											$totaldrereceita[3] = 0;
											$totaldrereceita[4] = 0;
											$totaldrereceita[5] = 0;
											$totaldrereceita[6] = 0;
											$totaldrereceita[7] = 0;
											$totaldrereceita[8] = 0;
											$totaldrereceita[9] = 0;
											$totaldrereceita[10] = 0;
											$totaldrereceita[11] = 0;
											$totaldrereceita[12] = 0;
											$totaldrereceita[13] = 0;

											$totalmes = 0;
											$tabela .= "<tr><td>".lang('DINHEIRO')."</td>";
											for($i=1;$i<13;$i++){
												$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
												$totalmes += $total = $faturamento->getReceitaDinheiro($mes_ano);
												$totaldrereceita[$i] +=  $total;
												$tabela .= "<td>".decimalp($total)."</td>";
											}
											$totaldrereceita[13] += $totalmes;
											$tabela .= "<td>".decimalp($totalmes)."</td>";
											$tabela .= "</tr>";

											$totalmes = 0;
											$tabela .= "<tr><td>".lang('CHEQUE')."</td>";
											for($i=1;$i<13;$i++){
												$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
												$totalmes += $total = $faturamento->getReceitaCheque($mes_ano);
												$totaldrereceita[$i] +=  $total;
												$tabela .= "<td>".decimalp($total)."</td>";
											}
											$totaldrereceita[13] += $totalmes;
											$tabela .= "<td>".decimalp($totalmes)."</td>";
											$tabela .= "</tr>";

											$totalmes = 0;
											$tabela .= "<tr><td>".lang('PARCELAS')."</td>";
											$mes_atual = date('m');
											$ano_atual = date('Y');
											for($i=1;$i<13;$i++){
												$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
												if(($ano = $ano_atual and $i > $mes_atual) or ($ano > $ano_atual)) {
													$totalmes += $total = $faturamento->getFaturamentoParcelas($mes_ano);
												} else {
													$totalmes += $total = $faturamento->getReceitaDeposito($mes_ano);
												}
												$totaldrereceita[$i] +=  $total;
												$tabela .= "<td>".decimalp($total)."</td>";
											}
											$totaldrereceita[13] += $totalmes;
											$tabela .= "<td>".decimalp($totalmes)."</td>";
											$tabela .= "</tr>";

											$totalmes = 0;
											$tabela .= "<tr><td>".lang('CARTAO_DEBITO')."</td>";
											for($i=1;$i<13;$i++){
												$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
												$totalmes += $total = $faturamento->getReceitaDebito($mes_ano);
												$totaldrereceita[$i] +=  $total;
												$tabela .= "<td>".decimalp($total)."</td>";
											}
											$totaldrereceita[13] += $totalmes;
											$tabela .= "<td>".decimalp($totalmes)."</td>";
											$tabela .= "</tr>";

											$totalmes = 0;
											$tabela .= "<tr><td>".lang('CARTAO_CREDITO')."</td>";
											for($i=1;$i<13;$i++){
												$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
												$totalmes += $total = $faturamento->getReceitaCredito($mes_ano);
												$totaldrereceita[$i] +=  $total;
												$tabela .= "<td>".decimalp($total)."</td>";
											}
											$totaldrereceita[13] += $totalmes;
											$tabela .= "<td>".decimalp($totalmes)."</td>";
											$tabela .= "</tr>";

											$tabela .= "<tr class='$destaque'><td><strong>".lang('TOTAL')."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[1])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[2])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[3])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[4])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[5])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[6])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[7])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[8])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[9])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[10])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[11])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[12])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[13])."</strong></td></tr>";
											echo $tabela;
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div class='portlet-body'>
							<div class='table-scrollable'>
								<table class="table table-bordered table-advance">
									<thead>
										<tr>
											<th width="20%"><?php echo lang('FINANCEIRO_DESPESAS');?></th>
											<th>JAN</th>
											<th>FEV</th>
											<th>MAR</th>
											<th>ABR</th>
											<th>MAI</th>
											<th>JUN</th>
											<th>JUL</th>
											<th>AGO</th>
											<th>SET</th>
											<th>OUT</th>
											<th>NOV</th>
											<th>DEZ</th>
											<th><?php echo lang('TOTAL');?></th>
										</tr>
									</thead>
									<tbody>
									<?php
											//$destaque = 'bg-'.$core->primeira_cor;
											$destaque = 'danger';
											$idTable = array();
											$totalmes = array();
											$totalmesdespesa = array();
											$totalmesmeta = array();
											$totaldredespesa = array();
											$totaldremeta = array();
											$tabela = '';
											$conta_pai = '';
											$id_pai = 0;
											$totaldespesa = 0;
											$totalmeta = 0;
											$totaldredespesa[1] = 0;
											$totaldredespesa[2] = 0;
											$totaldredespesa[3] = 0;
											$totaldredespesa[4] = 0;
											$totaldredespesa[5] = 0;
											$totaldredespesa[6] = 0;
											$totaldredespesa[7] = 0;
											$totaldredespesa[8] = 0;
											$totaldredespesa[9] = 0;
											$totaldredespesa[10] = 0;
											$totaldredespesa[11] = 0;
											$totaldredespesa[12] = 0;
											$totaldredespesa[13] = 0;
											$totaldremeta[1] = 0;
											$totaldremeta[2] = 0;
											$totaldremeta[3] = 0;
											$totaldremeta[4] = 0;
											$totaldremeta[5] = 0;
											$totaldremeta[6] = 0;
											$totaldremeta[7] = 0;
											$totaldremeta[8] = 0;
											$totaldremeta[9] = 0;
											$totaldremeta[10] = 0;
											$totaldremeta[11] = 0;
											$totaldremeta[12] = 0;
											$totaldremeta[13] = 0;
											for($i=1;$i<13;$i++){
												$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
												$conta_row = $despesa->getDREPrevisaoMes($mes_ano);
												foreach ($conta_row as $crow){
													if(empty($totalmesmeta[$crow->id_pai][13])) {
														$totalmesmeta[$crow->id_pai][13] = 0;
													}
													if($id_pai != $crow->id_pai) {
														$totalmeta = $faturamento->getMetasDRE($mes_ano, $crow->id_pai);
														$totalmesmeta[$crow->id_pai][$i] = $totalmeta;
														$totalmesmeta[$crow->id_pai][13] += $totalmeta;
														$totaldremeta[$i] += $totalmeta;
														$totaldremeta[13] += $totalmeta;
													}
													$id = $crow->id;
													$id_pai = $crow->id_pai;
													$totaldespesa = ($crow->total) ? $crow->total : 0;
													$idTable[$id][$i] = $totaldespesa;
													if(empty($totalmesdespesa[$id_pai][$i])) {
														$totalmesdespesa[$id_pai][$i] = 0;
													}
													if(empty($totalmesdespesa[$id_pai][13])) {
														$totalmesdespesa[$id_pai][13] = 0;
													}
													$totalmesdespesa[$id_pai][$i] += $totaldespesa;
													$totalmesdespesa[$id_pai][13] += $totaldespesa;
												}
												unset($crow);
											}
											$id_pai = 0;
											$conta_row = $despesa->getContas();
											$primeiro = true;
											foreach ($conta_row as $crow){
												if($id_pai != $crow->id_pai) {
													$id_pai = $crow->id_pai;
													$primeiro = true;
												}
												if($primeiro) {
													$destaque = 'info';
													$tabela .= "<tr class='$destaque'><td><strong>".$crow->conta_pai."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][1])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][2])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][3])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][4])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][5])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][6])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][7])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][8])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][9])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][10])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][11])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][12])."</strong></td>"
																			."<td><strong>".decimalp($totalmesdespesa[$id_pai][13])."</strong></td></tr>";

													$tabela .= "<tr><td class='$destaque'><strong>".lang('META')."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][1] > $totalmesmeta[$id_pai][1]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][1])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][2] > $totalmesmeta[$id_pai][2]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][2])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][3] > $totalmesmeta[$id_pai][3]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][3])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][4] > $totalmesmeta[$id_pai][4]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][4])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][5] > $totalmesmeta[$id_pai][5]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][5])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][6] > $totalmesmeta[$id_pai][6]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][6])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][7] > $totalmesmeta[$id_pai][7]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][7])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][8] > $totalmesmeta[$id_pai][8]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][8])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][9] > $totalmesmeta[$id_pai][9]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][9])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][10] > $totalmesmeta[$id_pai][10]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][10])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][11] > $totalmesmeta[$id_pai][12]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][11])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][12] > $totalmesmeta[$id_pai][12]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][12])."</strong></td>";
													$destaque =  ($totalmesdespesa[$id_pai][13] > $totalmesmeta[$id_pai][13]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totalmesmeta[$id_pai][13])."</strong></td></tr>";
													$primeiro = false;
												}
												$totaldredespesa[13] += $totaldespesa;
												$totaldespesa = 0;
												$tabela .= "<tr><td>".$crow->conta."</td>";
												for($i=1;$i<13;$i++){
													$id = $crow->id;
													$totaldespesa += $total = $idTable[$id][$i];
													$totaldredespesa[$i] +=  $total;
													$tabela .= "<td>".decimalp($total)."</td>";
												}
												$tabela .= "<td>".decimalp($totaldespesa)."</td>";
												$tabela .= "</tr>";
											}

											$destaque = 'info';
											$tabela .= "<tr class='$destaque'><td><strong>".lang('TOTAL')."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[1])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[2])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[3])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[4])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[5])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[6])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[7])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[8])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[9])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[10])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[11])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[12])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[13])."</strong></td>";

											$tabela .= "<tr><td class='$destaque'><strong>".lang('META')."</strong></td>";
													$destaque =  ($totaldredespesa[1] > $totaldremeta[1]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[1])."</strong></td>";
													$destaque =  ($totaldredespesa[2] > $totaldremeta[2]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[2])."</strong></td>";
													$destaque =  ($totaldredespesa[3] > $totaldremeta[3]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[3])."</strong></td>";
													$destaque =  ($totaldredespesa[4] > $totaldremeta[4]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[4])."</strong></td>";
													$destaque =  ($totaldredespesa[5] > $totaldremeta[5]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[5])."</strong></td>";
													$destaque =  ($totaldredespesa[6] > $totaldremeta[6]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[6])."</strong></td>";
													$destaque =  ($totaldredespesa[7] > $totaldremeta[7]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[7])."</strong></td>";
													$destaque =  ($totaldredespesa[8] > $totaldremeta[8]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[8])."</strong></td>";
													$destaque =  ($totaldredespesa[9] > $totaldremeta[9]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[9])."</strong></td>";
													$destaque =  ($totaldredespesa[10] > $totaldremeta[10]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[10])."</strong></td>";
													$destaque =  ($totaldredespesa[11] > $totaldremeta[12]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[11])."</strong></td>";
													$destaque =  ($totaldredespesa[12] > $totaldremeta[12]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[12])."</strong></td>";
													$destaque =  ($totaldredespesa[13] > $totaldremeta[13]) ? 'danger' : 'success';
													$tabela .= "<td class='$destaque'><strong>".decimalp($totaldremeta[13])."</strong></td></tr>";

											unset($crow);
											echo $tabela;
									?>
									</tbody>
								</table>
							</div>
						</div>
						<div class='portlet-body'>
							<div class='table-scrollable'>
								<table class="table table-bordered table-advance">
									<thead>
										<tr>
											<th width="20%"><?php echo lang('RESULTADO');?></th>
											<th>JAN</th>
											<th>FEV</th>
											<th>MAR</th>
											<th>ABR</th>
											<th>MAI</th>
											<th>JUN</th>
											<th>JUL</th>
											<th>AGO</th>
											<th>SET</th>
											<th>OUT</th>
											<th>NOV</th>
											<th>DEZ</th>
											<th><?php echo lang('TOTAL');?></th>
										</tr>
									</thead>
									<tbody>
									<?php
											$tabela = '';
											$destaque = 'success';
											$tabela .= "<tr class='$destaque'><td><strong>".lang('FINANCEIRO_RECEITAS')."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[1])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[2])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[3])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[4])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[5])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[6])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[7])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[8])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[9])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[10])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[11])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[12])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[13])."</strong></td></tr>";
											$destaque = 'danger';
											$tabela .= "<tr class='$destaque'><td><strong>".lang('FINANCEIRO_DESPESAS')."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[1])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[2])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[3])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[4])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[5])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[6])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[7])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[8])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[9])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[10])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[11])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[12])."</strong></td>"
																			."<td><strong>".decimalp($totaldredespesa[13])."</strong></td>";
											$destaque = 'info';
											$tabela .= "<tr class='$destaque'><td><strong>".lang('TOTAL')."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[1] - $totaldredespesa[1])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[2] - $totaldredespesa[2])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[3] - $totaldredespesa[3])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[4] - $totaldredespesa[4])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[5] - $totaldredespesa[5])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[6] - $totaldredespesa[6])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[7] - $totaldredespesa[7])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[8] - $totaldredespesa[8])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[9] - $totaldredespesa[9])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[10] - $totaldredespesa[10])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[11] - $totaldredespesa[11])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[12] - $totaldredespesa[12])."</strong></td>"
																			."<td><strong>".decimalp($totaldrereceita[13] - $totaldredespesa[13])."</strong></td>";
											$tabela .= "<tr><td><strong>".lang('MARGEM')."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[1], $totaldredespesa[1])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[2], $totaldredespesa[2])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[3], $totaldredespesa[3])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[4], $totaldredespesa[4])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[5], $totaldredespesa[5])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[6], $totaldredespesa[6])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[7], $totaldredespesa[7])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[8], $totaldredespesa[8])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[9], $totaldredespesa[9])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[10], $totaldredespesa[10])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[11], $totaldredespesa[11])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[12], $totaldredespesa[12])."</strong></td>"
																			."<td><strong>".margem($totaldrereceita[13], $totaldredespesa[13])."</strong></td>";

											echo $tabela;
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "financeiro":

	if ($core->tipo_sistema==1 || $core->tipo_sistema==3)
		redirect_to("login.php");

$ano = (get('ano')) ? get('ano') : date('Y');

?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ano').change(function() {
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=extrato&acao=financeiro&ano='+ ano;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('PAINEL_ANALISEANUAL');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<?php
												$retorno_row = $gestao->getListaAno("despesa", "data_pagamento", "DESC", true);
										?>
									<select class="select2me form-control input-large" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $ano) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
					</div>
<?php
	$mes = '';
	$receita = '';
	$despesa = '';
	$despesa_pagas = '';
	$lucro = '';
	$caixa = '';
	$tipodespesa = '';
	$tiporeceita = '';
	$treceita = array();
	$tdespesa = array();
	$tdespesa_pagas = array();
	$faturado = '';
	$recebido = '';
	$diferenca = '';
	$tfaturado = array();
	$trecebido = array();
	$treceber = array();
	$tparcelas = array();
	$recebido = '';
	$receber = '';
	$parcelas = '';
	for($i=1;$i<13;$i++) {
		if(strlen($recebido) > 0) {
				$mes .= ",";
				$receita .= ",";
				$despesa .= ",";
				$despesa_pagas .= ",";
				$lucro .= ",";
				$caixa .= ",";
				$faturado .= ",";
				$diferenca .= ",";
				$recebido .= ",";
				$receber .= ",";
		}
		$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
		$reb = $gestao->getRecebido($mes_ano);
		$recebido .= ($reb) ? $reb : 0;
		$trecebido[$i] = ($reb) ? $reb : 0;
		$areb = $gestao->getReceber($mes_ano);
		$receber .= ($areb) ? $areb : 0;
		$treceber[$i] = ($areb) ? $areb : 0;
		$fat = $gestao->getFaturado($mes_ano);
		$tfaturado[$i] = ($fat) ? $fat : 0;
		$soma = $fat;
		$faturado .= ($soma) ? $soma : 0;
		$des_pg = $gestao->getDespesasPagas($mes_ano);
		$despesa_pagas .= ($des_pg) ? $des_pg : 0;
		$tdespesa_pagas[$i] = ($des_pg) ? $des_pg : 0;
		$lucro .= $reb-$des_pg;
		$des = $gestao->getDespesas($mes_ano);
		$despesa .= ($des) ? $des : 0;
		$tdespesa[$i] = ($des) ? $des : 0;
		$vcaixa = $des-$reb;
		$caixa .= ($vcaixa > 0) ? $vcaixa : 0;
		$diferenca .= $soma-$reb;
		$mes .= "'".exibeMesAno($mes_ano, false, true)."'";
	}
?>
<script type="text/javascript">
 $(document).ready(function(){
  var s1 = [<?php print $recebido;?>];
  var s2 = [<?php print $despesa_pagas;?>];
  var s3 = [<?php print $lucro;?>];
  var s4 = [<?php print $despesa;?>];
  var s5 = [<?php print $caixa;?>];
  var cat = [<?php print $mes;?>];
  chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                backgroundColor: '#F8F8F8',
				marginTop: 20,
				marginLeft: 80,
                marginBottom: 45,
				zoomType: 'xy'
            },
			credits: {
				enabled: false
			},
            title: {
                text: '',
                x: 0, //center
				y: 10
            },
            xAxis: {
                categories: cat
            },
            yAxis: {

				title: {
                    text: null
                },
				labels: {
                    align: 'left',
                    x: -60,
                    y: -3,
                    formatter: function() {
                        return 'R$ ' + Highcharts.numberFormat(this.value, 3, ',', '.');
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': R$ '+ Highcharts.numberFormat(this.y, 3, ',', '.');
                }
            },
            legend: {
                layout: 'horizontal',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 10,
                floating: true,
                shadow: true
            },
			plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Receitas',
				color: '#3D979D',
				type: 'column',
                data: s1
            }, {
                name: 'Despesas pagas',
				color: '#AA4643',
				type: 'column',
                data: s2
            }, {
                name: 'Lucro',
				color: '#89A54E',
				type: 'spline',
                data: s3
            }]
        });
});
</script>
<script type="text/javascript">
 $(document).ready(function(){
  var s1 = [<?php print $recebido;?>];
  var s2 = [<?php print $receber;?>];
  var cat = [<?php print $mes;?>];
  chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart3',
                backgroundColor: '#F8F8F8',
				marginTop: 20,
				marginLeft: 80,
                marginBottom: 45,
				zoomType: 'xy'
            },
			credits: {
				enabled: false
			},
            title: {
                text: '',
                x: 0, //center
				y: 10
            },
            xAxis: {
                categories: cat
            },
            yAxis: {

				title: {
                    text: null
                },
				labels: {
                    align: 'left',
                    x: -60,
                    y: -3,
                    formatter: function() {
                        return 'R$ ' + Highcharts.numberFormat(this.value, 3, ',', '.');
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': R$ '+ Highcharts.numberFormat(this.y, 3, ',', '.');
                }
            },
            legend: {
                layout: 'horizontal',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 10,
                floating: true,
                shadow: true
            },
			plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Recebido',
				color: '#368EE0',
				type: 'column',
                data: s1
            }, {
                name: 'A Receber',
				color: '#3D979D',
				type: 'column',
                data: s2
            }]
        });
});
</script>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bar-chart-o font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_ANALISE')." em ".$ano;?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div class="note note-info note-bordered">
								<p>
									<?php echo lang('GESTAO_ANALISE_DESCRICAO');?>
								</p>
							</div>
							<div id="chart" class="chart"></div>
						</div>
					</div>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bar-chart-o font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('FLUXO_CAIXA')." em ".$ano;?></span>
							</div>
						</div>
						<!-- INICIO CONTEUDO DA PAGINA -->
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance">
								<thead>
									<tr>
										<th>#</th>
										<th>JAN</th>
										<th>FEV</th>
										<th>MAR</th>
										<th>ABR</th>
										<th>MAI</th>
										<th>JUN</th>
										<th>JUL</th>
										<th>AGO</th>
										<th>SET</th>
										<th>OUT</th>
										<th>NOV</th>
										<th>DEZ</th>
										<th><?php echo lang('TOTAL');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$totalfat = 0;
										$totaldespesas = 0;
										$totallucro = 0;
										$tabela = "";
										$tabela .= "<tr><td>".lang('RECEITAS')."</td>";
										for($i=1;$i<13;$i++){
											$totalfat += $fat = $trecebido[$i];
											$tabela .= "<td>".decimalp($fat)."</td>";
										}
										$tabela .= "<td>".moedap($totalfat)."</td>";
										$tabela .= "</tr><tr><td>".lang('DESPESAS')."</td>";
										for($i=1;$i<13;$i++){
											$totaldespesas += $despesas = $tdespesa_pagas[$i];
											$tabela .= "<td>".decimalp($despesas)."</td>";
										}
										$tabela .= "<td>".moedap($totaldespesas)."</td>";
										$tabela .= "</tr><tr><td>".lang('LUCRO')."</td>";
										for($i=1;$i<13;$i++){
											$totallucro += $lucro = $trecebido[$i] - $tdespesa_pagas[$i];
											$tabela .= "<td>".decimalp($lucro)."</td>";
										}
										$tabela .= "<td>".moedap($totallucro)."</td>";
										$tabela .= "</tr><tr><td>".lang('LUCRATIVIDADE')."</td>";
										for($i=1;$i<13;$i++){
											$lucro = $trecebido[$i] - $tdespesa_pagas[$i];
											$lucratividade = ($trecebido[$i] > 0 ) ? fpercentual($lucro,$trecebido[$i]) : "0,00 %";
											$tabela .= "<td>".$lucratividade."</td>";
										}
										$tabela .= "<td>-</td></tr>";
										echo $tabela;
								?>
								</tbody>
							</table>
						</div>
						<!-- FINAL CONTEUDO DA PAGINA -->
					</div>
					<!-- FINAL TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bar-chart-o font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_RECEBIDO_RECEBER')." em ".$ano;?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div class="note note-info note-bordered">
								<p>
									<?php echo lang('GESTAO_RECEBIDO_RECEBER_DESCRICAO');?>
								</p>
							</div>
							<div id="chart3" class="chart"></div>
						</div>
					</div>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bar-chart-o font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_RECEBIDO_RECEBER')." em ".$ano;?></span>
							</div>
						</div>
						<!-- INICIO CONTEUDO DA PAGINA -->
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance">
								<thead>
									<tr>
										<th>#</th>
										<th>JAN</th>
										<th>FEV</th>
										<th>MAR</th>
										<th>ABR</th>
										<th>MAI</th>
										<th>JUN</th>
										<th>JUL</th>
										<th>AGO</th>
										<th>SET</th>
										<th>OUT</th>
										<th>NOV</th>
										<th>DEZ</th>
										<th><?php echo lang('TOTAL');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$totalrecebido = 0;
										$totalfaturado = 0;
										$totaldiferenca = 0;
										$tabela = "";

										$tabela .= "<tr><td>(+) ".lang('CONTAS_RECEBIDAS')."</td>";
										$total = 0;
										for($i=1;$i<13;$i++){
											$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
											$total += $valor = $trecebido[$i];
											$tabela .= "<td>".decimalp($valor)."</td>";
										}
										$tabela .= "<td>".moedap($total)."</td></tr>";

										$tabela .= "<tr><td>(+) ".lang('CONTAS_A_RECEBER')."</td>";
										$total = 0;
										for($i=1;$i<13;$i++){
											$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
											$total += $valor = $treceber[$i];
											$tabela .= "<td>".decimalp($valor)."</td>";
										}
										$tabela .= "<td>".moedap($total)."</td></tr>";

										$tabela .= "<tr><td>(=) ".lang('CONTAS_FATURADAS')."</td>";
										$total = 0;
										for($i=1;$i<13;$i++){
											$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
											$total += $valor = $trecebido[$i]+$treceber[$i];
											$tabela .= "<td>".decimalp($valor)."</td>";
										}
										$tabela .= "<td>".moedap($total)."</td></tr>";
										$tabela .= "</tr><tr><td>".lang('PERCENTUAL')."</td>";
										for($i=1;$i<13;$i++){
											$percentual = fpercentual($treceber[$i],$trecebido[$i]+$treceber[$i]);
											$tabela .= "<td>".$percentual."</td>";
										}
										$tabela .= "<td>-</td></tr>";
										echo $tabela;
								?>
								</tbody>
							</table>
						</div>
						<!-- FINAL CONTEUDO DA PAGINA -->
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php case "despesasano":

	if ($core->tipo_sistema==1 || $core->tipo_sistema==3)
		redirect_to("login.php");

$ano = (get('ano')) ? get('ano') : date('Y');
$mes = (get('mes')) ? get('mes') : '';

?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ano').change(function() {
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=extrato&acao=despesasano&ano='+ ano;
		});
	});
	// ]]>
</script>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#mes').change(function() {
			var mes = $("#mes").val();
			window.location.href = 'index.php?do=extrato&acao=despesasmes&mes='+ mes;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('GESTAO_DESPESAANO');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<?php
												$retorno_row = $gestao->getListaAno("despesa", "data_pagamento", "DESC", true);
										?>
									<select class="select2me form-control input-large" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $ano) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
									<?php
												$retorno_row = $gestao->getListaMes("despesa", "data_pagamento", false, "DESC");
										?>
									<select class="select2me form-control input-large" name="mes" id="mes" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $mes) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
					</div>
<?php
	$tipodespesa = '';
	$retorno_row = $gestao->tipoDespesas($ano);
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$tipodespesa .= "['".$exrow->conta."', ".$exrow->valor."],";
		}
	}
	unset($exrow);
?>
<script type="text/javascript">
 $(function () {
    var chart;
    $(document).ready(function() {

		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
			credits: {
				enabled: false
			},
			title: {
                text: '<b>Despesas</b>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>',
            	percentageDecimals: 2
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>' + this.point.name + '</b>: ' + ' R$ ' + Highcharts.numberFormat(this.y, 2, ',', '.');
                        },
                        // Forçar a exibição de todos os rótulos
                        softConnector: false // Impede que o Highcharts remova pequenos rótulos
                    },
                    // Configurar a porcentagem mínima para exibir rótulos
                    minSize: 10,  // Valor mínimo em pixels para que a fatia seja visível
                    showInLegend: true // Exibe todos os itens na legenda, mesmo os de valores baixos
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            series: [{
                type: 'pie',
                name: 'Despesas',
                data: [
                    <?php print $tipodespesa; ?>
                ]
            }]
        });
    });

});
</script>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-sign-out font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_DESPESAANO')." em ".$ano;?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div id="chart" style="width:100%;height:800px" class="chart"></div>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php case "despesasmes":
$ano = (get('ano')) ? get('ano') : '';
$mes = (get('mes')) ? get('mes') : date('m/Y');

?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ano').change(function() {
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=extrato&acao=despesasano&ano='+ ano;
		});
	});
	// ]]>
</script>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#mes').change(function() {
			var mes = $("#mes").val();
			window.location.href = 'index.php?do=extrato&acao=despesasmes&mes='+ mes;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('GESTAO_DESPESAMES');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<?php
												$retorno_row = $gestao->getListaAno("despesa", "data_pagamento", "DESC", true);
										?>
									<select class="select2me form-control input-large" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $ano) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
									<?php
												$retorno_row = $gestao->getListaMes("despesa", "data_pagamento", false, "DESC");
										?>
									<select class="select2me form-control input-large" name="mes" id="mes" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $mes) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
					</div>
<?php
	$tipodespesa = '';
	$retorno_row = $gestao->tipoDespesas(false, $mes);
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$tipodespesa .= "['".$exrow->conta."', ".$exrow->valor."],";
		}
	}
	unset($exrow);
?>
<script type="text/javascript">
 $(function () {
    var chart;
    $(document).ready(function() {

		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
			credits: {
				enabled: false
			},
			title: {
                text: '<b>Despesas</b>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>',
            	percentageDecimals: 2
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ ' R$ '+ Highcharts.numberFormat(this.y, 3, ',', '.');
                        }
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            series: [{
                type: 'pie',
                name: 'Despesas',
                data: [
                    <?php print $tipodespesa;?>
                ]
            }]
        });
    });

});
</script>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bar-sign-out font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_DESPESAMES')." em ".$mes;?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div id="chart" class="chart"></div>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php case "receitasano":

	if ($core->tipo_sistema==1 || $core->tipo_sistema==3)
		redirect_to("login.php");

$ano = (get('ano')) ? get('ano') : date('Y');
$mes = (get('mes')) ? get('mes') : '';

?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ano').change(function() {
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=extrato&acao=receitasano&ano='+ ano;
		});
	});
	// ]]>
</script>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#mes').change(function() {
			var mes = $("#mes").val();
			window.location.href = 'index.php?do=extrato&acao=receitasmes&mes='+ mes;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('GESTAO_RECEITASANO');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<?php
												$retorno_row = $gestao->getListaAno("receita", "data_recebido", "DESC", true);
										?>
									<select class="select2me form-control input-large" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $ano) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
									<?php
												$retorno_row = $gestao->getListaMes("receita", "data_recebido", false, "DESC");
										?>
									<select class="select2me form-control input-large" name="mes" id="mes" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $mes) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
					</div>
<?php
	$tiporeceitas = '';
	$retorno_row = $gestao->tipoReceitas($ano, false);
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$tiporeceitas .= "['".$exrow->tipo."', ".$exrow->valor."],";
		}
	}
	unset($exrow);
?>
<script type="text/javascript">
 $(function () {
    var chart;
    $(document).ready(function() {

		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
			credits: {
				enabled: false
			},
			title: {
                text: '<b>Receitas</b>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>',
            	percentageDecimals: 2
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ ' R$ '+ Highcharts.numberFormat(this.y, 3, ',', '.');
                        }
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            series: [{
                type: 'pie',
                name: 'Receitas',
                data: [
                    <?php print $tiporeceitas;?>
                ]
            }]
        });
    });

});
</script>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-money font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_RECEITASANO')." em ".$ano;?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div id="chart" class="chart"></div>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php case "receitasmes":
$ano = (get('ano')) ? get('ano') : '';
$mes = (get('mes')) ? get('mes') : date('m/Y');

?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ano').change(function() {
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=extrato&acao=receitasano&ano='+ ano;
		});
	});
	// ]]>
</script>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#mes').change(function() {
			var mes = $("#mes").val();
			window.location.href = 'index.php?do=extrato&acao=receitasmes&mes='+ mes;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('GESTAO_RECEITASMES');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<?php
												$retorno_row = $gestao->getListaAno("receita", "data_recebido", "DESC", true);
										?>
									<select class="select2me form-control input-large" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $ano) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
									<?php
												$retorno_row = $gestao->getListaMes("receita", "data_recebido", false, "DESC");
										?>
									<select class="select2me form-control input-large" name="mes" id="mes" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $mes) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
					</div>
<?php
	$tiporeceitas = '';
	$retorno_row = $gestao->tipoReceitas(false, $mes);
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$tiporeceitas .= "['".$exrow->tipo."', ".$exrow->valor."],";
		}
	}
	unset($exrow);
?>
<script type="text/javascript">
 $(function () {
    var chart;
    $(document).ready(function() {

		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
			credits: {
				enabled: false
			},
			title: {
                text: '<b>Receitas</b>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>',
            	percentageDecimals: 2
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ ' R$ '+ Highcharts.numberFormat(this.y, 3, ',', '.');
                        }
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            series: [{
                type: 'pie',
                name: 'Receitas',
                data: [
                    <?php print $tiporeceitas;?>
                ]
            }]
        });
    });

});
</script>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-money font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_RECEITASMES')." em ".$mes;?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div id="chart" class="chart"></div>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php case "faturamentoano":

	if ($core->tipo_sistema==1 || $core->tipo_sistema==3)
		redirect_to("login.php");

$ano = (get('ano')) ? get('ano') : date('Y');
$mes = (get('mes')) ? get('mes') : '';

?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ano').change(function() {
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=extrato&acao=faturamentoano&ano='+ ano;
		});
	});
	// ]]>
</script>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#mes').change(function() {
			var mes = $("#mes").val();
			window.location.href = 'index.php?do=extrato&acao=faturamentomes&mes='+ mes;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('GESTAO_FATURAMENTOANO');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<?php
												$retorno_row = $gestao->getListaAno("receita", "data_pagamento", "DESC", true);
										?>
									<select class="select2me form-control input-large" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $ano) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
									<?php
												$retorno_row = $gestao->getListaMes("receita", "data_pagamento", false, "DESC");
										?>
									<select class="select2me form-control input-large" name="mes" id="mes" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $mes) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
					</div>
<?php
	$tipofaturamento = '';
	$retorno_row = $gestao->tipoFaturamento($ano, false);
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$tipofaturamento .= "['".($exrow->tipo)."', ".$exrow->valor."],";
		}
	}
	unset($exrow);
?>
<script type="text/javascript">
 $(function () {
    var chart;
    $(document).ready(function() {

		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
			credits: {
				enabled: false
			},
			title: {
                text: '<b>Faturamento</b>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>',
            	percentageDecimals: 2
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ ' R$ '+ Highcharts.numberFormat(this.y, 3, ',', '.');
                        }
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            series: [{
                type: 'pie',
                name: 'Faturamento',
                data: [
                    <?php print $tipofaturamento;?>
                ]
            }]
        });
    });

});
</script>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-usd font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_FATURAMENTOANO')." em ".$ano;?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div id="chart" class="chart"></div>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php case "faturamentomes":
$ano = (get('ano')) ? get('ano') : '';
$mes = (get('mes')) ? get('mes') : date('m/Y');

?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ano').change(function() {
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=extrato&acao=faturamentoano&ano='+ ano;
		});
	});
	// ]]>
</script>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#mes').change(function() {
			var mes = $("#mes").val();
			window.location.href = 'index.php?do=extrato&acao=faturamentomes&mes='+ mes;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('GESTAO_FATURAMENTOMES');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<?php
												$retorno_row = $gestao->getListaAno("receita", "data_pagamento", "DESC", true);
										?>
									<select class="select2me form-control input-large" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $ano) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
									<?php
												$retorno_row = $gestao->getListaMes("receita", "data_pagamento", false, "DESC");
										?>
									<select class="select2me form-control input-large" name="mes" id="mes" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $mes) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
					</div>
<?php
	$tipofaturamento = '';
	$retorno_row = $gestao->tipoFaturamento(false, $mes);
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$tipofaturamento .= "['".($exrow->tipo)."', ".$exrow->valor."],";
		}
	}
	unset($exrow);
?>
<script type="text/javascript">
 $(function () {
    var chart;
    $(document).ready(function() {

		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
			credits: {
				enabled: false
			},
			title: {
                text: '<b>Faturamento</b>'
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>',
            	percentageDecimals: 2
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ ' R$ '+ Highcharts.numberFormat(this.y, 3, ',', '.');
                        }
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            series: [{
                type: 'pie',
                name: 'Faturamento',
                data: [
                    <?php print $tipofaturamento;?>
                ]
            }]
        });
    });

});
</script>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-usd font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_FATURAMENTOMES')." em ".$mes;?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div id="chart" class="chart"></div>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php case "indicadores": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('INDICADORES');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-compass font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('INDICADORES');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance">
								<tbody>
									<tr>
										<th><?php echo lang('PMV');?></th>
										<td width="60%"><?php echo $pmv = $gestao->getPMV();?></td>
									</tr>
									<tr>
										<th><?php echo lang('AMR');?></th>
										<td width="60%"><?php echo $amv = $gestao->getAMR();?></td>
									</tr>
									<tr>
										<th><?php echo lang('PMR');?></th>
										<td width="60%"><?php echo $pmv + $amv;?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case 'financeiromensal':

	if ($core->tipo_sistema==1 || $core->tipo_sistema==3)
		redirect_to("login.php");

?>
<?php $data = (get('data')) ? get('data') : date("m/Y"); ?>
<script type="text/javascript">

	$(document).ready(function () {
		$('#mes_ano').change(function() {
			var datafiltro = $("#mes_ano").val();
			window.location.href = 'index.php?do=extrato&acao=financeiromensal&data='+datafiltro;
		});
	});

</script>
<!-- INICIO BOX MODAL -->
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('PAINEL_ANALISEMENSAL');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW TABELA -->
			<div class='row'>
				<div class='col-md-12'>
					<!-- INICIO TABELA -->
					<div class='portlet light'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-usd font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('PAINEL_ANALISEMENSAL');?></span>
							</div>
						</div>
						<?php
							 $retorno_row = $gestao->getListaMes("receita", "data_pagamento", false, "DESC");
						?>
						<div class="portlet-body form">
							<form class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="mes_ano" id="mes_ano" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
										<option value=""></option>
										<?php
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $data) echo 'selected="selected"';?>><?php echo exibeMesAno($srow->mes_ano, true, true);?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance'>
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('DIA_SEMANA');?></th>
										<th><?php echo lang('RECEITAS');?></th>
										<th><?php echo lang('RECEBIDO');?></th>
										<th><?php echo lang('DESPESAS');?></th>
										<th><?php echo lang('SALDO');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$data_mes = explode("/", $data);
										$total_recebido = 0;
										$total_receita = 0;
										$total_despesa = 0;
										$total_saldo = 0;
										$ultimo = cal_days_in_month(CAL_GREGORIAN, $data_mes[0], $data_mes[1]);
										for($i=1;$i<=$ultimo;$i++):
											$dia = ($i<10) ? "0".$i : $i;
											$diasemana = diasemana($dia."/".$data, true);
											$receber = $gestao->getReceberDia($dia."/".$data);
											$total_receita += $receber;
											$recebido = $gestao->getReceitasDia($dia."/".$data);
											$total_recebido += $recebido ;
											$despesaspagas = $gestao->getDespesasPagasDia($dia."/".$data);
											$total_despesa += $despesaspagas;
											$saldo = $recebido - $despesaspagas;
											$total_saldo += $saldo;
								?>
											<tr>
												<td><?php echo $dia;?></td>
												<td><?php echo $diasemana;?></td>
												<td><?php echo moedap($receber);?></td>
												<td><?php echo moedap($recebido);?></td>
												<td><?php echo moedap($despesaspagas);?></td>
												<td><?php echo moedap($total_saldo);?></td>
											</tr>
								<?php
										endfor;
								?>
											<tr>
												<td colspan="2"><strong><?php echo lang('TOTAL');?></strong></td>
												<td><strong><?php echo moedap($total_receita);?></strong></td>
												<td><strong><?php echo moedap($total_recebido);?></strong></td>
												<td><strong><?php echo moedap($total_despesa);?></strong></td>
												<td><strong><?php echo moedap($total_saldo);?></strong></td>
											</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php default: ?>
<div class="imagem-fundo">
	<img src="assets/img/logo.png" border="0">
</div>
<?php break;?>
<?php
	case "analiseestoque":
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('GESTAO_ESTOQUE');?></small></h1>
					</div>
					<!-- FINAL TITULO DA PAGINA -->
				</div>
			</div>
			<!-- FINAL CABECALHO DA PAGINA -->
			<!-- INICIO DOS MODULOS DA PAGINA -->
			<div class="page-content">
				<div class="container">
					<!-- INICIO DO ROW TABELA -->
					<div class="row">
						<div class="col-md-12">
							<!-- INICIO TABELA -->
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-th font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('GESTAO_ESTOQUE'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('CODIGO_DE_BARRAS'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('UNIDADE'); ?></th>
												<th><?php echo lang('GRADE_VENDAS'); ?></th>
												<th><?php echo lang('GRUPO'); ?></th>
												<th><?php echo lang('CATEGORIA'); ?></th>
												<th><?php echo lang('ESTOQUE'); ?></th>
												<th><?php echo lang('VALOR_CUSTO'); ?></th>
												<th><?php echo lang('INVENTARIO_CUSTO'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$totalcusto = 0;
											$retorno_row = $produto->getProdutosAnaliseEstoque();
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$totalcusto += $invcusto = $exrow->estoque * $exrow->valor_custo;
													$grade = '';
													if($exrow->grade == 1) {
														$grade = "<span class='label label-sm bg-green'>".lang('SIM')."</span>";
													} else {
														$grade = "<span class='label label-sm bg-yellow-casablanca'>".lang('NAO')."</span>";
													}
													?>
													<tr>
														<td><?php echo $exrow->codigobarras; ?></td>
														<td><?php echo $exrow->nome; ?></td>
														<td><?php echo $exrow->unidade; ?></td>
														<td><?php echo $grade; ?></td>
														<td><?php echo $exrow->grupo; ?></td>
														<td><?php echo $exrow->categoria; ?></td>
														<td><?php echo decimalp($exrow->estoque); ?></td>
														<td><?php echo moeda($exrow->valor_custo); ?></td>
														<td><?php echo moeda($invcusto); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="8"><strong><?php echo lang('INVENTARIO_CUSTO'); ?></strong></td>
													<td><strong><?php echo moeda($totalcusto); ?></strong></td>
												</tr>
											</tfoot>
											<?php unset($exrow);
											endif; ?>
									</table>
								</div>
							</div>
							<!-- FINAL TABELA -->
						</div>
					</div>
					<!-- FINAL DO ROW TABELA -->
				</div>
			</div>
			<!-- FINAL DOS MODULOS DA PAGINA -->
		</div>
		<!-- FINAL CONTEUDO DA PAGINA -->
		<?php break; ?>
<?php endswitch;?>