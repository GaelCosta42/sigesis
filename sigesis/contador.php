<?php
  /**
   * Contador
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  if (!defined('_VALID_PHP'))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->igual_Contador())
	  redirect_to('login.php');
  
  $datafiltro = get('datafiltro');
?>
<?php switch(Filter::$acao): case 'recebidas': ?>
<?php 
$id_banco = (get('id_banco')) ? get('id_banco') : 0; 
$dataini = (get('dataini')) ? get('dataini') : date('01/m/Y'); 
$data = explode("/", $dataini);	
$ultimo = cal_days_in_month(CAL_GREGORIAN, $data[1], $data[2]);
$datafim = (get('datafim')) ? get('datafim') : date($ultimo.'/'.$data[1].'/'.$data[2]); 
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#imprimir').click(function() {
			var id_banco = $("#id_banco").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var id_empresa = $("#id_empresa").val();
			window.open('pdf_recebidas.php?&dataini='+ dataini +'&datafim='+ datafim +'&id_banco='+ id_banco +'&id_empresa='+ id_empresa,'Imprimir Receitas','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
	});
	// ]]>
</script>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#buscar').click(function() {
			var id_banco = $("#id_banco").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var id_empresa = $("#id_empresa").val();
			window.location.href = 'index.php?do=faturamento&acao=recebidas&dataini='+ dataini +'&datafim='+ datafim +'&id_banco='+ id_banco +'&id_empresa='+ id_empresa;
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
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('CONTAS_RECEBIDAS');?></small></h1>
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
								<i class='fa fa-check font-<?php echo $core->primeira_cor;?>'></i>								
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('CONTAS_RECEBIDAS');?></span>
							</div>
							<div class='actions btn-set'>
								<small class="font-blue"><i class='fa fa-square'>&nbsp;&nbsp;</i><?php echo lang('NAO_FISCAL');?></small>&nbsp;&nbsp;
								<a href='index.php?do=faturamento&acao=adicionar' class='btn btn-sm <?php echo $core->primeira_cor;?>'><i class='fa fa-plus-square'>&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete='off' class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="id_banco" id="id_banco" data-placeholder="<?php echo lang('SELECIONE_BANCO');?>" >
										<option value=""></option>
										<?php 
											$retorno_row = $faturamento->getBancos();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
										?>
													<option value="<?php echo $srow->id;?>" <?php if($srow->id == $id_banco) echo 'selected="selected"';?>><?php echo $srow->banco;?></option>
										<?php
												endforeach;
											unset($srow);
											endif;
										?>
									</select>
									&nbsp;&nbsp;
									<select class="select2me form-control" name="id_empresa" id="id_empresa" data-placeholder="<?php echo lang('SELECIONE_EMPRESA');?>" >
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
									<br/>
									<br/>
									<label><?php echo lang('SELECIONE_PERIODO');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium calendario data" name="dataini" id="dataini" value="<?php echo $dataini;?>" >
									<input type="text" class="form-control input-medium calendario data" name="datafim" id="datafim" value="<?php echo $datafim;?>" >
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
									&nbsp;&nbsp;
									<button type="button" id="imprimir" class="btn green"><i class="fa fa-print"/></i> <?php echo lang('IMPRIMIR');?></button>
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable-asc'>
								<thead class='flip-content'>
									<tr>
										<th>#</th>
										<th>#</th>
										<th><?php echo lang('PAGAMENTO');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('DOCUMENTO');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('VALOR');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
										<th><?php echo lang('VENCIMENTO');?></th>
										<th><?php echo lang('PAGO');?></th>
										<th width="120px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$total = 0;
										$descricao = '';
										$retorno_row = $faturamento->getReceitas($dataini, $datafim, $id_banco, $id_empresa);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$total += $exrow->valor_pago;
											if(trim($exrow->descricao) != ''){
												$descricao = $exrow->descricao;
											} elseif($exrow->tipo == 0){
												$descricao = 'TRANSFERENCIA ENTRE BANCO';
											} elseif($exrow->tipo == 1) {
												$descricao = $exrow->pagamento.' - CAIXA ID:'.$exrow->id_caixa;
											} else {
												$nome = ($exrow->id_cadastro) ? ' - '.getValue("nome","cadastro","id = '".$exrow->id_cadastro."'") : "";
												$descricao = $exrow->pagamento.$nome;
											}
											$pago = '';
											if($exrow->pago == 1) {
												$pago = "<span class='label label-sm bg-green'>".lang('SIM')."</span>";
											} else {
												$pago = "<span class='label label-sm bg-red'>".lang('NAO')."</span>";
											}
											$estilo = '';
											if(!$exrow->fiscal) {
												$estilo = "class='info'";
											}
					
								?>
											<tr <?php echo $estilo;?>>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo $exrow->id;?></td>
												<td><?php echo exibedata($exrow->data_recebido);?></td>
												<td><?php echo ($exrow->empresa);?></td>
												<td><?php echo ($exrow->banco);?></td>
													<td><a href="index.php?do=cadastro&acao=receitas&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->cadastro;?></a></td>
												<td><?php echo ($exrow->duplicata);?></td>
												<td><?php echo $descricao;?></td>
												<td><?php echo moeda($exrow->valor);?></td>
												<td><?php echo moeda($exrow->valor_pago);?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo $pago;?></td>
												<td>
													<a href="javascript:void(0);" onclick="javascript:void window.open('ver_receita.php?id_receita=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
												</td>
											</tr>
								<?php 
											endforeach;
											unset($exrow);
										endif;
								?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="9"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo moeda($total);?></strong></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</tfoot>
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
<?php case 'despesaspagas': 
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
$id_cadastro = (get('id_cadastro')) ? get('id_cadastro') : 0; 
$id_banco = (get('id_banco')) ? get('id_banco') : 0; 
$id_conta = (get('id_conta')) ? get('id_conta') : 0; 
$valor = (get('valor')) ? get('valor') : ''; 
$dataini = (get('dataini')) ? get('dataini') : date('01/m/Y'); 
$data = explode("/", $dataini);	
$ultimo = cal_days_in_month(CAL_GREGORIAN, $data[1], $data[2]);
$datafim = (get('datafim')) ? get('datafim') : date($ultimo.'/'.$data[1].'/'.$data[2]); 

?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#buscar').click(function() {
			var id_empresa = $("#id_empresa").val();
			var id_cadastro = $("#id_cadastro").val();
			var id_banco = $("#id_banco_despesa").val();
			var id_conta = $("#id_conta_despesa").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var valor = $("#valor").val();
			window.location.href = 'index.php?do=despesa&acao=despesaspagas&dataini='+ dataini +'&datafim='+ datafim +'&id_cadastro='+ id_cadastro +'&id_banco='+ id_banco +'&id_empresa='+ id_empresa +'&id_conta='+ id_conta +'&valor='+ valor;
		});
		$('#imprimir').click(function() {
			var id_empresa = $("#id_empresa").val();
			var id_cadastro = $("#id_cadastro").val();
			var id_banco = $("#id_banco_despesa").val();
			var id_conta = $("#id_conta_despesa").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var valor = $("#valor").val();
			window.open('pdf_despesaspagas.php?dataini='+ dataini +'&datafim='+ datafim +'&id_cadastro='+ id_cadastro +'&id_banco='+ id_banco +'&id_conta='+ id_conta +'&id_empresa='+ id_empresa +'&valor='+ valor,'Imprimir Despesas','width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
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
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_DESPESASPAGAS');?></small></h1>
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
								<i class='fa fa-minus-square font-<?php echo $core->primeira_cor;?>'></i>								
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('FINANCEIRO_DESPESASPAGAS');?></span>
							</div>
							<div class='actions btn-set'>
								<small class="font-blue"><i class='fa fa-circle'>&nbsp;&nbsp;</i><?php echo lang('FISCAL');?></small>&nbsp;&nbsp;
								<small class="font-green"><i class='fa fa-check'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_CONCILIADO');?></small>&nbsp;&nbsp;
								<small class="font-yellow-gold"><i class='fa fa-square'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_IMPORTANTE');?></small>&nbsp;&nbsp;
								<a href='index.php?do=despesa&acao=adicionar' class='btn btn-sm <?php echo $core->primeira_cor;?>'><i class='fa fa-plus-square'>&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete='off' class="form-inline">
								<div class="form-group">
									<input name="id_cadastro" id="id_cadastro" type="hidden" />
									<input type="text" autocomplete="off" class="form-control caps listar_cadastro input-xlarge" name="cadastro" placeholder="<?php echo lang('BUSCAR');?>">
									<br/>
									<select class="select2me form-control input-large" name="id_empresa" id="id_empresa" data-placeholder="<?php echo lang('SELECIONE_EMPRESA');?>" >
										<option value=""></option>
										<option value="">TODAS</option>
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
									&nbsp;&nbsp;
									<select class="select2me form-control input-large" name="id_banco" id="id_banco_despesa" data-placeholder="<?php echo lang('SELECIONE_BANCO');?>" >
										<option value=""></option>
										<?php 
											$retorno_row = $faturamento->getBancos();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
										?>
													<option value="<?php echo $srow->id;?>" <?php if($srow->id == $id_banco) echo 'selected="selected"';?>><?php echo $srow->banco;?></option>
										<?php
												endforeach;
											unset($srow);
											endif;
										?>
									</select>
									<br/>
									<br/>
									<select class='select2me form-control input-large' name='id_pai' id='id_pai_despesa' data-placeholder='<?php echo lang('SELECIONE_CONTAS');?>' >
										<option value=""></option>
										<?php 
												$retorno_row = $faturamento->getPai('"D"');
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value='<?php echo $srow->id;?>'><?php echo $srow->conta;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
									<select class='select2me form-control input-large' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
										<option value="0"></option>
									</select>
									<br/>
									<br/>
									<label><?php echo lang('DATA_PAGAMENTO');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium calendario data" name="dataini" id="dataini" value="<?php echo $dataini;?>" >
									<input type="text" class="form-control input-medium calendario data" name="datafim" id="datafim" value="<?php echo $datafim;?>" >
									<br/>
									<br/>
									<label><?php echo lang('SELECIONE_VALOR');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium decimal" name="valor" id="valor" value="<?php echo $valor;?>">
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
									&nbsp;&nbsp;
									<button type="button" id="imprimir" class="btn green"><i class="fa fa-print"/></i> <?php echo lang('IMPRIMIR');?></button>
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable-asc'>
								<thead>
									<tr>
										<th>#</th>
										<th>#</th>
										<th><?php echo lang('DATA_PAGAMENTO');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('FORNECEDOR');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('DATA_VENCIMENTO');?></th>
										<th><?php echo lang('VALOR');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
										<th><?php echo lang('JUROS');?></th>
										<th style="width: 280px"><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$total = 0;
										$pago = 0;
										$totaljuros = 0;
										$retorno_row = $despesa->getDespesasPagas($id_empresa, $dataini, $datafim, $id_banco, $id_conta, $id_cadastro, $valor);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$total += $exrow->valor;
											$pago += $exrow->valor_pago;
											$totaljuros += $j = $exrow->valor_pago - $exrow->valor;
											$estilo = '';
											$juros = '';
											
											if($exrow->cheque) {
												$estilo = "class='warning'";
											}elseif($exrow->fiscal) {
												$estilo = "class='info'";
											}
											if($exrow->conciliado) {
												$estilo = "class='success'";
											}
											if($exrow->valor_pago > $exrow->valor) {
												$juros = "class='font-red'";
											}
								?>
											<tr <?php echo $estilo;?>>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo $exrow->id;?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo ($exrow->empresa);?></td>
												<td><?php echo ($exrow->banco);?></td>
													<td><a href="index.php?do=cadastro&acao=despesas&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->cadastro;?></a></td>
												<td><?php echo $exrow->descricao;?></td>
												<td><?php echo exibedata($exrow->data_vencimento);?></td>
												<td><?php echo decimal($exrow->valor);?></td>
												<td <?php echo $juros;?>><?php echo decimal($exrow->valor_pago);?></td>
												<td <?php echo $juros;?>><?php echo decimal($j);?></td>
												<td>
													<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_despesa.php?id_despesa=<?php echo $exrow->id;?>','<?php echo lang('IMPRIMIR_RECIBO').$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a>
													<a href="javascript:void(0);" onclick="javascript:void window.open('ver_despesa.php?id_despesa=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
													<a href='index.php?do=despesa&acao=duplicardespesas&id=<?php echo $exrow->id;?>' class='btn btn-sm blue-chambray' title='<?php echo lang('FINANCEIRO_DESPESASDUPLICAR').': '.$exrow->descricao;?>'><i class='fa fa-files-o'></i></a>
													<?php if($usuario->is_Master()): ?>
													<a href='index.php?do=despesa&acao=editarpagas&id=<?php echo $exrow->id;?>' class='btn btn-sm blue' title='<?php echo lang('EDITAR').': '.$exrow->descricao;?>'><i class='fa fa-pencil'></i></a>
														
													<a href='javascript:void(0);' class='btn btn-sm red apagar' id='<?php echo $exrow->id;?>' acao='apagarDespesas' title='<?php echo lang('FINANCEIRO_DESPESAS_APAGAR').$exrow->descricao;?>'><i class='fa fa-times'></i></a>
													<?php endif; ?>
												</td>
											</tr>
								<?php 
											endforeach;
											unset($exrow);
										endif;
								?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="8"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo decimal($total);?></strong></td>
										<td><strong><?php echo decimal($pago);?></strong></td>
										<td><strong><?php echo decimal($totaljuros);?></strong></td>
										<td></td>
									</tr>
								</tfoot>
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
<?php case "visualizar_produto": ?>
<?php $row = Core::getRowById("nota_fiscal", Filter::$id);
$operacao = ($row->cfop) ? getValue('descricao', 'cfop', 'cfop='.$row->cfop) : operacao($row->operacao);
$devolucao = ($row->cfop) ? getValue('devolucao', 'cfop', 'cfop='.$row->cfop) : 0;
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('NOTA_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_VISUALIZAR');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW FORMULARIO -->
			<div class="row">
				<div class="col-md-12">		
					<div class="portlet box <?php echo $core->primeira_cor;?>">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('NOTA_VISUALIZAR');?>
							</div>
							<div class="actions btn-set">								
								<?php if($row->nome_arquivo): ?>
								<a href="javascript:void(0);" class="btn btn-sm default" onclick="javascript:void window.open('<?php echo "./uploads/data/".$row->nome_arquivo;?>','<?php echo lang('ARQUIVOS_VISUALIZAR_XML');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('ARQUIVOS_VISUALIZAR_XML');?></a>
								<?php endif; ?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<?php if($row->inativo):?>
									<div class="row">
										<div class="col-md-12">		
											<div class="note note-danger">
												<h4 class="block"><?php echo lang('NOTA_CANCELADA');?></h4>
											</div>
										</div>	
									</div>
									<?php endif;?>
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CHAVE_ACESSO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="chaveacesso" value="<?php echo $row->chaveacesso;?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CHAVE_ACESSO_REFERENCIADA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="nfe_referenciada" value="<?php echo $row->nfe_referenciada;?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMPRESA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo getValue("nome", "empresa", "id=".$row->id_empresa);?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('RAZAO_SOCIAL');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo getValue("razao_social", "cadastro", "id=".$row->id_cadastro);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('MODELO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo modelo($row->modelo);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('OPERACAO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $operacao;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('NUMERO_NOTA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->numero_nota;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CFOP');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->cfop;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_EMISSAO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control data calendario" value="<?php echo exibedata($row->data_emissao);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_ENTRADA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control data calendario" value="<?php echo exibedata($row->data_entrada);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_FRETE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_frete);?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_SEGURO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_seguro);?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-5-->
											<div class="col-md-6">	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_BASE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_base_icms);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_ICMS');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_icms);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_BASE_ST');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap"value="<?php echo moedap($row->valor_base_st);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_ST');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_st);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_IPI');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_ipi);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_PIS');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_pis);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_COFINS');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_cofins);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_OUTRO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_outro);?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_TRIBUTOS');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_total_trib);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_TOTAL_PRODUTOS');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_produto);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_DESCONTO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_desconto);?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_TOTAL_NOTA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control moedap" value="<?php echo moedap($row->valor_nota);?>">
														</div>
													</div>
												</div>				
											</div>
											<!--/col-md-6-->
											<div class="col-md-12">
												<div class="row">
													<div class="form-group">
														<label class="col-md-3"><?php echo lang('DISCRIMINACAO');?></label>
													</div>
												</div>	
											</div>	
											<div class="col-md-12">
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea readonly class="form-control" name="descriminacao"><?php echo $row->descriminacao;?></textarea>
														</div>
													</div>
												</div>	
											</div>
											<div class="col-md-12">
												<div class="row">
													<div class="form-group">
														<label class="col-md-3"><?php echo lang('DUPLICATAS');?></label>
													</div>
												</div>	
											</div>	
											<div class="col-md-12">
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea readonly class="form-control" name="duplicatas"><?php echo $row->duplicatas;?></textarea>
														</div>
													</div>
												</div>	
											</div>
											<div class="col-md-12">
												<div class="row">
													<div class="form-group">
														<label class="col-md-3"><?php echo lang('INF_ADICIONAIS');?></label>
													</div>
												</div>	
											</div>	
											<div class="col-md-12">
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea readonly class="form-control" name="inf_adicionais"><?php echo $row->inf_adicionais;?></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>
									</div>	
									<?php 	$id_transporte = getValue('id', 'nota_fiscal_transporte', 'id_nota='.$row->id);
											if($id_transporte): 
												$row_transporte = Core::getRowById("nota_fiscal_transporte", $id_transporte);
									?>									
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('INFORMACOES_TRANSPORTE');?></h4>
											</div>	
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TRANSPORTE');?></label>
														<div class="col-md-9">
															<select disabled class="select2me form-control" name="modalidade" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value=""><?php echo lang('SEM_TRANSPORTE');?></option>
																<option value="SemFrete" <?php if($row_transporte->modalidade == 'SemFrete') echo 'selected="selected"';?>><?php echo lang('SEMFRETE');?></option>
																<option value="PorContadoEmitente" <?php if($row_transporte->modalidade == 'PorContadoEmitente') echo 'selected="selected"';?>><?php echo lang('PORCONTADOEMITENTE');?></option>
																<option value="PorContadoDestinatario" <?php if($row_transporte->modalidade == 'PorContadoDestinatario') echo 'selected="selected"';?>><?php echo lang('PORCONTADODESTINATARIO');?></option>
																<option value="ContratacaoPorContaDoRemetente" <?php if($row_transporte->modalidade == 'ContratacaoPorContaDoRemetente') echo 'selected="selected"';?>><?php echo lang('CONTRATACAOPORCONTADOREMETENTE');?></option>
																<option value="ContratacaoPorContaDoDestinario" <?php if($row_transporte->modalidade == 'ContratacaoPorContaDoDestinario') echo 'selected="selected"';?>><?php echo lang('CONTRATACAOPORCONTADODESTINARIO');?></option>
																<option value="ContratacaoPorContaDeTerceiros" <?php if($row_transporte->modalidade == 'ContratacaoPorContaDeTerceiros') echo 'selected="selected"';?>><?php echo lang('CONTRATACAOPORCONTADETERCEIROS');?></option>
																<option value="TransporteProprioPorContaDoRemetente" <?php if($row_transporte->modalidade == 'TransporteProprioPorContaDoRemetente') echo 'selected="selected"';?>><?php echo lang('TRANSPORTEPROPRIOPORCONTADOREMETENTE');?></option>
																<option value="TransporteProprioPorContaDoDestinatario" <?php if($row_transporte->modalidade == 'TransporteProprioPorContaDoDestinatario') echo 'selected="selected"';?>><?php echo lang('TRANSPORTEPROPRIOPORCONTADODESTINATARIO');?></option>
																<option value="SemOcorrenciaDeTransporte" <?php if($row_transporte->modalidade == 'SemOcorrenciaDeTransporte') echo 'selected="selected"';?>><?php echo lang('SEMOCORRENCIADETRANSPORTE');?></option>
															</select>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TIPO');?></label>
														<div class="col-md-9">
															<div class="md-radio-list">
																<div class="md-radio col-md-6">
																	<input disabled type="radio" class="md-radiobtn" name="tipopessoadestinatario" id="tipo_j" value="J"  <?php getChecked($row_transporte->tipopessoadestinatario,'J');?>>
																	<label for="tipo_j">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('PESSOA_JURIDICA');?></label>
																</div>
																<div class="md-radio col-md-6">
																	<input disabled type="radio" class="md-radiobtn" name="tipopessoadestinatario" id="tipo_f" value="F"  <?php getChecked($row_transporte->tipopessoadestinatario,'F');?>>
																	<label for="tipo_f">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('PESSOA_FISICA');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CPF_CNPJ');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control cpf_cnpj" name="cpfcnpjdestinatario" value="<?php echo formatar_cpf_cnpj($row_transporte->cpfcnpjdestinatario);?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CEP');?></label>
														<div class="col-md-9">
															<div class="input-group">
															<input readonly type="text" class="form-control cep" name="cep" id="cep" value="<?php echo $row_transporte->cep;?>">
																<span class="input-group-btn">
																<button id="cepbusca" class="btn <?php echo $core->primeira_cor;?>" type="button"><i class="fa fa-arrow-left fa-fw"/></i> <?php echo lang('BUSCAR_END');?></button>
																</span>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ENDERECO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="logradouro" id="endereco" value="<?php echo $row_transporte->logradouro;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('NUMERO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="numero" id="numero" value="<?php echo $row_transporte->numero;?>">
														</div>
													</div>
												</div>		
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('COMPLEMENTO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="complemento" value="<?php echo $row_transporte->complemento;?>">
														</div>
													</div>
												</div>									
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BAIRRO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="bairro" id="bairro" value="<?php echo $row_transporte->bairro;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CIDADE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="cidade" id="cidade" value="<?php echo $row_transporte->cidade;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ESTADO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps uf" name="uf" id="estado" value="<?php echo $row_transporte->uf;?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-5-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('QUANT_VOLUMES');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control decimal" name="quantidade" value="<?php echo decimal($row_transporte->quantidade);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ESPECIE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="especie" value="<?php echo ($row_transporte) ? $row_transporte->especie : '';?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PESOLIQUIDO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control decimalp" name="pesoliquido" value="<?php echo decimalp($row_transporte->pesoliquido);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PESOBRUTO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control decimalp" name="pesobruto" value="<?php echo decimalp($row_transporte->pesobruto);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TRANSPORTADORA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="trans_nome" value="<?php echo $row_transporte->trans_nome;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TIPO');?></label>
														<div class="col-md-9">
															<div class="md-radio-list">
																<div class="md-radio col-md-6">
																	<input disabled type="radio" class="md-radiobtn" name="trans_tipopessoa" id="tipo_j" value="J"  <?php getChecked($row_transporte->trans_tipopessoa,'J');?>>
																	<label for="tipo_j">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('PESSOA_JURIDICA');?></label>
																</div>
																<div class="md-radio col-md-6">
																	<input disabled type="radio" class="md-radiobtn" name="trans_tipopessoa" id="tipo_f" value="F" <?php getChecked($row_transporte->trans_tipopessoa,'F');?>>
																	<label for="tipo_f">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('PESSOA_FISICA');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TRANS_CPF_CNPJ');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control cpf_cnpj" name="trans_cpfcnpj" value="<?php echo formatar_cpf_cnpj($row_transporte->trans_cpfcnpj);?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('INSCRICAO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="trans_inscricaoestadual" value="<?php echo $row_transporte->trans_inscricaoestadual;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ENDERECO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="trans_endereco" value="<?php echo $row_transporte->trans_endereco;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CIDADE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="trans_cidade" value="<?php echo $row_transporte->trans_cidade;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ESTADO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="trans_uf" value="<?php echo $row_transporte->trans_uf;?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>	
									</div>
									<?php endif; ?>
									<div class="row">
									<?php if($row->operacao == 1):?>								
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('INFORMACOES_DESPESAS');?></h4>
											</div>	
											<div class="portlet-body">		
												<div class="table-scrollable table-scrollable-borderless">
													<table class="table table-hover table-light">
														<thead>
															<tr>
																<th><?php echo lang('VENCIMENTO');?></th>
																<th><?php echo lang('BANCO');?></th>
																<th><?php echo lang('DUPLICATA');?></th>
																<th><?php echo lang('VALOR');?></th>
																<th><?php echo lang('PAGAMENTO');?></th>
																<th><?php echo lang('STATUS');?></th>
																<th><?php echo lang('OPCOES');?></th>
															</tr>
														</thead>
														<tbody>
														<?php 	
																$total = 0;
																$retorno_row = $despesa->getDespesasNota(Filter::$id);
																if($retorno_row):
																foreach ($retorno_row as $exrow):
																$status = '-';
																$estilo = '';
																if($exrow->pago == 0) {
																	$status = "<span class='label label-sm bg-blue'>A PAGAR</span>";
																} elseif($exrow->pago == 1) {
																	$status = "<span class='label label-sm bg-green'>PAGA</span>";
																} elseif($exrow->pago == 2) {
																	$status = "<span class='label label-sm bg-yellow'>PENDENTE</span>";
																}
																if($exrow->inativo){
																	$estilo = 'class="danger"';
																	$status = "<span class='label label-sm bg-red'>CANCELADA</span>";
																} else {
																	$total += $exrow->valor;
																}
																	
														?>
															<tr <?php echo $estilo;?>>
																<td><?php echo exibedata($exrow->data_vencimento);?></td>
																<td><?php echo $exrow->banco;?></td>
																<td><?php echo $exrow->duplicata;?></td>
																<td><span class="theme-font valor_total"><?php echo decimalp($exrow->valor);?></span></td>
																<td><?php echo exibedata($exrow->data_pagamento);?></td>
																<td><?php echo $status;?></td>
																<td>
																</td>
															</tr>
														<?php 	endforeach;?>
															<tr>
																<td colspan="3"><strong><?php echo lang('TOTAL');?></strong></td>
																<td><span class="bold theme-font valor_total"><?php echo decimalp($total);?></span></td>
																<td colspan="3"></td>
															</tr>
														<?php unset($exrow);
															  endif;?>
														</tbody>
													</table>
												</div>
												<div class="caption">
														<?php if($row->fiscal):?>
														<a href="nfe_download.php?id=<?php echo Filter::$id;?>" target="_blank" title="<?php echo lang('ENOTAS_PDF');?>" class="btn green-jungle"><i class="fa fa-file-pdf-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_PDF');?></a>
														<a href="nfe_xml.php?id=<?php echo Filter::$id;?>" target="_blank" title="<?php echo lang('ENOTAS_XML');?>" class="btn green-turquoise"><i class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_XML');?></a>
														<?php endif;?>
													<?php elseif($row->fiscal): ?>
														<a href="nfe_download.php?id=<?php echo Filter::$id;?>" target="_blank" title="<?php echo lang('ENOTAS_PDF');?>" class="btn green-jungle"><i class="fa fa-file-pdf-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_PDF');?></a>
													<?php endif;?>
												</div>
											</div>
										</div>
									<?php endif;
										  if($row->operacao == 2):?>									
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('INFORMACOES_RECEITAS');?></h4>
											</div>	
											<div class="portlet-body">
												<div class="table-scrollable table-scrollable-borderless">
													<table class="table table-hover table-light">
														<thead>
															<tr>
																<th><?php echo lang('TIPO');?></th>
																<th><?php echo lang('DATA_TRANSACAO');?></th>
																<th><?php echo lang('BANCO');?></th>
																<th><?php echo lang('VALOR');?></th>
																<th><?php echo lang('PAGAMENTO');?></th>
																<th><?php echo lang('FISCAL');?></th>
																<th><?php echo lang('STATUS');?></th>
																<th><?php echo lang('OPCOES');?></th>
															</tr>
														</thead>
														<tbody>
														<?php 	
																$total = 0;
																$retorno_row = $faturamento->getReceitasNota(Filter::$id);
																$is_boleto = false;
																if($retorno_row):
																foreach ($retorno_row as $exrow):
																$status = '-';
																$estilo = '';
																$enviado = ($exrow->enviado) ? 'green-turquoise' : 'grey-gallery';
																$enviado_texto = ($exrow->enviado) ? 'Boleto enviado' : 'Boleto nao enviado';
																if($exrow->pago == 0) {
																	$status = "<span class='label label-sm bg-blue'>A PAGAR</span>";
																} elseif($exrow->pago == 1) {
																	$status = "<span class='label label-sm bg-green'>PAGA</span>";
																}
																if($exrow->inativo){
																	$estilo = 'class="danger"';
																	$status = '-';
																} else {
																	$total += $exrow->valor_pago;
																}
																	
														?>
															<tr <?php echo $estilo;?>>
																<td><?php echo $exrow->pagamento;?></td>
																<td><?php echo exibedata($exrow->data_pagamento);?></td>
																<td><?php echo $exrow->banco;?></td>
																<td><span class="theme-font valor_total"><?php echo decimalp($exrow->valor_pago);?></span></td>
																<td><?php echo exibedata($exrow->data_recebido);?></td>
																<td><?php echo exibedata($exrow->data_fiscal);?></td>
																<td><?php echo $status;?></td>
																<td>
																</td>
															</tr>
														<?php 	endforeach;?>
															<tr>
																<td colspan="3"><strong><?php echo lang('TOTAL');?></strong></td>
																<td><span class="bold theme-font valor_total"><?php echo decimalp($total);?></span></td>
																<td colspan="4"></td>
															</tr>
														<?php unset($exrow);
															  endif;?>
														</tbody>
													</table>
												</div>
												<div class="caption">
														<?php if($row->fiscal):?>
														<a href="nfe_download.php?id=<?php echo Filter::$id;?>" target="_blank" title="<?php echo lang('ENOTAS_PDF');?>" class="btn green-jungle"><i class="fa fa-file-pdf-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_PDF');?></a>
														<a href="nfe_xml.php?id=<?php echo Filter::$id;?>" target="_blank" title="<?php echo lang('ENOTAS_XML');?>" class="btn green-turquoise"><i class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_XML');?></a>
														<?php endif;?>
													<?php elseif($row->fiscal): ?>
														<a href="nfe_download.php?id=<?php echo Filter::$id;?>" target="_blank" title="<?php echo lang('ENOTAS_PDF');?>" class="btn green-jungle"><i class="fa fa-file-pdf-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_PDF');?></a>
													<?php endif;?>
												</div>
											</div>
										</div>
									<?php endif;
										  if($row->modelo == 2):?>
										<div class="col-md-12">
											<div class="col-md-12">
												<br/>
											</div>	
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('INFORMACOES_PRODUTOS');?></h4>
											</div>	
											<div class="portlet-body">		
												<div class="table-scrollable table-scrollable-borderless">
													<table class="table table-light">
														<thead>
															<tr>
																<th><?php echo lang('PRODUTO');?></th>
																<th><?php echo lang('CFOP');?></th>
																<th><?php echo lang('NCM');?></th>
																<th><?php echo lang('UNIDADE');?></th>
																<th><?php echo lang('QUANT');?></th>
																<th><?php echo lang('VALOR');?></th>
																<th><?php echo lang('VL_DESC');?></th>	
																<th><?php echo lang('VL_TRIB');?></th>
																<th><?php echo lang('VL_TOTAL');?></th>
																<th width="100px"><?php echo lang('ACOES');?></th>
															</tr>
														</thead>
														<tbody>
														<?php 	
															$quantidade = 0;
															$unitario = 0;
															$desconto = 0;
															$icms = 0;
															$trib = 0;
															$total = 0;
															$retorno_row = $produto->getProdutosNota(Filter::$id);
															if($retorno_row):
																foreach ($retorno_row as $exrow):
																$estilo = ($exrow->id_produto) ? 'class="info"' : 'class="danger"';
																$nome_produto = ($exrow->id_produto) ? $exrow->nome : $exrow->nome_fornecedor;
																$quantidade += $exrow->quantidade;
																$unitario += $exrow->valor_unitario;
																$desconto += $exrow->valor_desconto;
																$icms += $exrow->icms_valor;
																$trib += $exrow->valor_total_trib;
																$total += $exrow->valor_total;
														?>
															<tr <?php echo $estilo;?>>
																<td>
																	<?php echo $nome_produto;?>
																</td>
																<td><?php echo ($exrow->cfop) ? $exrow->cfop : $exrow->cfop_produto;?></td>
																<td><?php echo ($exrow->ncm) ? $exrow->ncm : $exrow->ncm_produto;?></td>
																<td><?php echo ($exrow->unidade) ? $exrow->unidade : $exrow->unidade_produto;?></td>
																<td><?php echo decimalp($exrow->quantidade);?></td>
																<td><?php echo decimalp($exrow->valor_unitario);?></td>
																<td><?php echo decimalp($exrow->valor_desconto);?></td>
																<td><?php echo decimalp($exrow->valor_total_trib);?></td>
																<td><span class="bold theme-font valor_total"><?php echo decimalp($exrow->valor_total);?></span></td>
																<td>
																</td>
															</tr>
														<?php 	endforeach;?>
															<tr>
																<td colspan="4"><strong><?php echo lang('TOTAL');?></strong></td>
																<td><strong><?php echo decimalp($quantidade);?></strong></td>
																<td><strong><?php echo decimalp($unitario);?></strong></td>
																<td><strong><?php echo decimalp($desconto);?></strong></td>
																<td><strong><?php echo decimalp($trib);?></strong></td>
																<td><span class="bold theme-font valor_total"><?php echo decimalp($total);?></span></td>
																<td></td>
															</tr>
														<?php unset($exrow);
															  endif;?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<?php endif;?>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
											<div class="col-md-6">
											</div>
										</div>
									</div>
								</div>
							</form>
							<!-- FINAL FORM-->
						</div>
					</div>
				</div>
			</div>
			<!-- FINAL DO ROW FORMULARIO -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "notafiscal": 
$numero_nota = (get('numero_nota')) ? get('numero_nota') : ""; 
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
$modelo = (get('modelo')) ? get('modelo') : 0; 
$operacao = (get('operacao')) ? get('operacao') : 0; 
$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y'); 
$mes_ano = (get('ano')) ? 0 : $mes_ano; 
$ano = (get('ano')) ? get('ano') : 0;
?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#buscar').click(function() {
			var numero_nota = $("#numero_nota").val();
			var id_empresa = $("#id_empresa").val();
			var modelo = $("#modelo").val();
			var operacao = $("#operacao").val();
			var mes_ano = $("#mes_ano").val();
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=notafiscal&acao=notafiscal&id_empresa='+ id_empresa +'&modelo='+ modelo +'&operacao='+ operacao +'&mes_ano='+ mes_ano +'&numero_nota='+ numero_nota +'&ano='+ ano;
		});
		$('#imprimir').click(function() {
			var numero_nota = $("#numero_nota").val();
			var id_empresa = $("#id_empresa").val();
			var modelo = $("#modelo").val();
			var operacao = $("#operacao").val();
			var mes_ano = $("#mes_ano").val();
			var ano = $("#ano").val();
			window.open('pdf_notafiscal.php?id_empresa='+ id_empresa +'&modelo='+ modelo +'&operacao='+ operacao +'&mes_ano='+ mes_ano +'&numero_nota='+ numero_nota +'&ano='+ ano,'Imprimir Notas fiscais','width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
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
				<h1><?php echo lang('NOTA_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_LISTAR');?></small></h1>
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
								<i class="fa fa-list font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('NOTA_LISTAR');?></span>
							</div>
						</div>
						<div class="portlet-body form">
							<form autocomplete="off" class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="mes_ano" id="mes_ano" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
										<option value=""></option>
										<?php 
												$retorno_row = $gestao->getListaMes("nota_fiscal", "data_emissao", false, "DESC");
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>				
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $mes_ano) echo 'selected="selected"';?>><?php echo exibeMesAno($srow->mes_ano, true, true);?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<select class="select2me form-control" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php 
												$retorno_row = $gestao->getListaAno("nota_fiscal", "data_emissao", "DESC");
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
									&nbsp;&nbsp;&nbsp;&nbsp;
									<select class="select2me form-control input-large" id="id_empresa" name="id_empresa" data-placeholder="<?php echo lang('SELECIONE_EMPRESA');?>" >
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
									<br/>
									<br/>
									<select class="select2me form-control" id="modelo" name="modelo" data-placeholder="<?php echo lang('SELECIONE_TIPO');?>" >
										<option value=""></option>
										<option value="1" <?php if(1 == $modelo) echo 'selected="selected"';?>><?php echo lang('SERVICO');?></option>
										<option value="2" <?php if(2 == $modelo) echo 'selected="selected"';?>><?php echo lang('PRODUTO');?></option>
										<option value="3" <?php if(3 == $modelo) echo 'selected="selected"';?>><?php echo lang('FATURA');?></option>
										<option value="4" <?php if(4 == $modelo) echo 'selected="selected"';?>><?php echo lang('TRANSPORTE');?></option>
									</select>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<select class="select2me form-control" id="operacao" name="operacao" data-placeholder="<?php echo lang('SELECIONE_OPERACAO');?>" >
										<option value=""></option>
										<option value="1" <?php if(1 == $operacao) echo 'selected="selected"';?>><?php echo lang('COMPRA');?></option>
										<option value="2" <?php if(2 == $operacao) echo 'selected="selected"';?>><?php echo lang('VENDA');?></option>
									</select>
									<br/>
									<br/>
									<label><?php echo lang('NUMERO_NOTA');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium" name="numero_nota" id="numero_nota" value="<?php echo $numero_nota;?>">
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
									&nbsp;&nbsp;
									<button type="button" id="imprimir" class="btn green"><i class="fa fa-print"/></i> <?php echo lang('IMPRIMIR');?></button>
								</div>
							</form>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable-asc ">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('DATA_NOTA');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('MODELO');?></th>
										<th><?php echo lang('OPERACAO');?></th>
										<th><?php echo lang('NOME');?></th>
										<th><?php echo lang('NUMERO_NOTA');?></th>
										<th><?php echo lang('VALOR_NOTA');?></th>
                                        <th width="110px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$total = 0;
										$retorno_row = $produto->getNotaFiscal($mes_ano, $id_empresa, $modelo, $operacao, $numero_nota, $ano);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											$estilo = '';
											if($exrow->inativo)
												$estilo = 'class="danger"';
											else
												$total += $exrow->valor_nota;
								?>
									<tr <?php echo $estilo;?>>
										<td><?php echo $exrow->controle;?></td>
										<td><?php echo exibedata($exrow->data_emissao);?></td>
										<td><?php echo $exrow->empresa;?></td>
										<td><?php echo modelo($exrow->modelo);?></td>
										<td><?php echo operacao($exrow->operacao);?></td>								
										<td><a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id;?>"><?php echo $exrow->razao_social;?></a></td>
										<td><?php echo $exrow->numero_nota;?></td>
										<td><?php echo moedap($exrow->valor_nota);?></td>
										<td>
											<?php if($exrow->modelo == 1):?>
												<a href="index.php?do=notafiscal&acao=visualizar_servico&id=<?php echo $exrow->id;?>" title="<?php echo lang('VISUALIZAR').': '.$exrow->razao_social;?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
											<?php else:?>
												<a href="index.php?do=notafiscal&acao=visualizar_produto&id=<?php echo $exrow->id;?>" class="btn btn-sm grey-cascade" title="<?php echo lang('VISUALIZAR').': '.$exrow->razao_social;?>"><i class="fa fa-search"></i></a>
											<?php endif;?>
										</td>
									</tr>
								<?php endforeach;?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="7"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo moeda($total);?></strong></td>
										<td></td>
									</tr>
								</tfoot>
								<?php unset($exrow);
									  endif;?>
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
<?php case "visualizar_servico": 
	$row = Core::getRowById("nota_fiscal", Filter::$id);
	$row_empresa = Core::getRowById("empresa", $row->id_empresa);
	?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('NOTA_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_VISUALIZAR');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW FORMULARIO -->
			<div class="row">
				<div class="col-md-12">		
					<div class="portlet box <?php echo $core->primeira_cor;?>">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('NOTA_VISUALIZAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('EMPRESA');?></label>
														<div class='col-md-9'>
															<input name="id_empresa" value="<?php echo $row->id_empresa;?>" type="hidden" />
															<input readonly type="text" autocomplete="off" class="form-control caps" value="<?php echo getValue('nome', 'empresa', 'id='.$row->id_empresa);?>" >
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('NOME');?></label>
														<div class='col-md-9'>
															<input name="id_cadastro" value="<?php echo $row->id_cadastro;?>" type="hidden" />
															<input readonly type="text" autocomplete="off" class="form-control caps" value="<?php echo getValue('nome', 'cadastro', 'id='.$row->id_cadastro);?>" >
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('VALOR_SERVICO');?></label>
														<div class='col-md-9'>
															<input readonly type="text" autocomplete="off" class="form-control caps" value="<?php echo moeda($row->valor_servico);?>" >
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"></label>
														<div class="col-md-9">
															<div class="md-checkbox-list">
																<div class="md-checkbox">
																	<input disabled type="checkbox" class="md-check" id="iss_retido" <?php if($row->iss_retido) echo 'checked';?>>
																	<label for="iss_retido">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('ISS_RETIDO');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('ISS_ALIQUOTA');?></label>
														<div class='col-md-9'>
															<input readonly type="text" autocomplete="off" class="form-control caps" value="<?php echo decimal($row_empresa->iss_aliquota);?>" >
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('VALOR_ISS');?></label>
														<div class='col-md-9'>
															<input readonly type="text" autocomplete="off" class="form-control caps" value="<?php echo moeda($row->valor_iss);?>" >
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('VALOR_NOTA');?></label>
														<div class='col-md-9'>
															<input readonly type="text" autocomplete="off" class="form-control caps" value="<?php echo moeda($row->valor_nota);?>" >
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<div class="col-md-12">
												<div class="row">
													<div class="form-group">
														<label class="col-md-3"><?php echo lang('DISCRIMINACAO');?></label>
													</div>
												</div>	
											</div>	
											<div class="col-md-12">
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea readonly class="form-control" name="descriminacao"><?php echo ($row->descriminacao);?></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														<?php if($row->fiscal):?>
															<a href="javascript:void(0);" onclick="javascript:void window.open('nfse_download.php?id=<?php echo $row->id;?>','<?php echo lang('NOTA_DOWNLOAD').$row->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('NOTA_DOWNLOAD');?>" class="btn btn-sm green-turquoise"><i class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('NOTA_DOWNLOAD');?></a>
														<?php endif;?>
														<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
											<div class="col-md-6">
											</div>
										</div>
									</div>
								</div>
							</form>	
							<!-- FINAL FORM-->
						</div>
					</div>
				</div>
			</div>
			<!-- FINAL DO ROW FORMULARIO -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "chaveacesso": 
$operacao = (get('operacao')) ? get('operacao') : ''; 
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y');
?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#id_empresa').click(function() {
			var id_empresa = $("#id_empresa").val();
			var mes_ano = $("#mes_ano").val();
			var operacao = $("#operacao").val();
			window.location.href = 'index.php?do=notafiscal&acao=chaveacesso&id_empresa='+ id_empresa +'&mes_ano='+ mes_ano+'&operacao='+ operacao;
		});
		$('#mes_ano').click(function() {
			var id_empresa = $("#id_empresa").val();
			var mes_ano = $("#mes_ano").val();
			var operacao = $("#operacao").val();
			window.location.href = 'index.php?do=notafiscal&acao=chaveacesso&id_empresa='+ id_empresa +'&mes_ano='+ mes_ano+'&operacao='+ operacao;
		});
		$('#operacao').click(function() {
			var id_empresa = $("#id_empresa").val();
			var mes_ano = $("#mes_ano").val();
			var operacao = $("#operacao").val();
			window.location.href = 'index.php?do=notafiscal&acao=chaveacesso&id_empresa='+ id_empresa +'&mes_ano='+ mes_ano+'&operacao='+ operacao;
		});
		$('#imprimir').click(function() {
			var id_empresa = $("#id_empresa").val();
			var mes_ano = $("#mes_ano").val();
			var operacao = $("#operacao").val();
			window.open('pdf_chaveacesso.php?id_empresa='+ id_empresa +'&mes_ano='+ mes_ano +'&operacao='+ operacao,'Imprimir Notas fiscais','width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
		});
		$('#imprimir_xml').click(function() {
			var id_empresa = $("#id_empresa").val();
			if(!id_empresa) {
				alert('Selecione uma empresa para gerar o arquivo com XMLs.');
				return false;
			}
			var mes_ano = $("#mes_ano").val();
			window.open('nfe_xml_download_todos.php?id_empresa='+ id_empresa +'&mes_ano='+ mes_ano,'Imprimir Notas fiscais','width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
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
				<h1><?php echo lang('NOTA_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_LISTAR_CHAVEACESSO');?></small></h1>
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
								<i class="fa fa-file-code-o font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('NOTA_LISTAR_CHAVEACESSO');?></span>
							</div>
						</div>
						<div class="portlet-body form">
							<form autocomplete="off" class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="mes_ano" id="mes_ano" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
										<option value=""></option>
										<?php 
												$retorno_row = $produto->getListaMesNF();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>				
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $mes_ano) echo 'selected="selected"';?>><?php echo exibeMesAno($srow->mes_ano, true, true);?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<select class="select2me form-control input-large" id="operacao" name="operacao" data-placeholder="<?php echo lang('SELECIONE_OPERACAO');?>" >
										<option value=""></option>
										<option value="1" <?php if('1' == $operacao) echo 'selected="selected"';?>><?php echo 'ENTRADA';?></option>
										<option value="2" <?php if('2' == $operacao) echo 'selected="selected"';?>><?php echo 'SAIDA';?></option>
									</select>
									<br/>
									<br/>
									<select class="select2me form-control input-large" id="id_empresa" name="id_empresa" data-placeholder="<?php echo lang('SELECIONE_EMPRESA');?>" >
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
									&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="button" id="imprimir" class="btn yellow-casablanca"><i class="fa fa-print"/></i> <?php echo lang('IMPRIMIR');?></button>
									<button type="button" id="imprimir_xml" class="btn green-turquoise"><i class="fa fa-print"/></i> <?php echo lang('IMPRIMIR_TODOS');?></button>
								</div>
							</form>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable-asc ">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('DATA_NOTA');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('MODELO');?></th>
										<th><?php echo lang('OPERACAO');?></th>
										<th><?php echo lang('NUMERO_NOTA');?></th>
										<th><?php echo lang('VALOR_NOTA');?></th>
										<th><?php echo lang('CHAVE_ACESSO');?></th>
                                        <th width="110px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$total = 0;
										$retorno_row = $produto->getNotaFiscalChaveAcesso($mes_ano, $id_empresa, $operacao);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											if($exrow->fiscal == 1)
												$total += $exrow->valor;
								?>
									<tr>
										<td><?php echo $exrow->numero;?></td>
										<td><?php echo exibedata($exrow->data_emissao);?></td>
										<td><?php echo $exrow->empresa;?></td>
										<td><?php echo modelo($exrow->modelo);?></td>
										<td><?php echo operacao($exrow->operacao, true);?></td>
										<td><?php echo $exrow->numero_nota;?></td>
										<td><?php echo moeda($exrow->valor);?></td>
										<td><?php echo ($exrow->chaveacesso) ? $exrow->chaveacesso : 'NUMERACAO INUTILIZADA';?></td>
										<td>
											<?php if($exrow->fiscal == 1):?>
												<?php if($exrow->modelo == 6):?>
													<a href="javascript:void(0);" class="btn btn-sm purple" onclick="javascript:void window.open('nfc_download.php?id=<?php echo $exrow->id;?>','<?php echo lang('ENOTAS_PDF').'-'.$exrow->id;?>','width=300,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-file-pdf-o"></i></a>
													<a href="javascript:void(0);" class="btn btn-sm green-turquoise" onclick="javascript:void window.open('nfc_xml.php?id=<?php echo $exrow->id;?>','<?php echo lang('ENOTAS_XML').'-'.$exrow->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-file-excel-o"></i></a>
												<?php else: ?>
													<a href="javascript:void(0);" class="btn btn-sm purple" onclick="javascript:void window.open('nfe_download.php?id=<?php echo $exrow->id;?>','<?php echo lang('ENOTAS_PDF').'-'.$exrow->id;?>','width=300,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-file-pdf-o"></i></a>
													<a href="javascript:void(0);" class="btn btn-sm green-turquoise" onclick="javascript:void window.open('nfe_xml.php?id=<?php echo $exrow->id;?>','<?php echo lang('ENOTAS_XML').'-'.$exrow->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-file-excel-o"></i></a>
												<?php endif;?>
											<?php elseif($exrow->nome_arquivo != ''):?>
												<a href="javascript:void(0);" class="btn btn-sm green-turquoise" onclick="javascript:void window.open('<?php echo "./uploads/data/".$exrow->nome_arquivo;?>','<?php echo lang('ENOTAS_XML');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-file-excel-o"></i></a>
											<?php endif;?>
										</td>
									</tr>
								<?php endforeach;?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="6"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo moeda($total);?></strong></td>
										<td colspan="2"></td>
									</tr>
								</tfoot>
								<?php unset($exrow);
									  endif;?>
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
<?php case "inventario": 
$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('Y'); 
$id_empresa = (get('id_empresa')) ? get('id_empresa') : session('idempresa');
?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('.imprimirinventario').click(function() {
			var id_empresa = $("#id_empresa").val();
			var mes_ano = $("#mes_ano").val();
			window.open('pdf_inventario_nfe.php?id_empresa='+id_empresa+'&mes_ano='+mes_ano,'INVENTARIO FISCAL '+mes_ano,'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
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
				<h1><?php echo lang('PRODUTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_INVENTARIO');?></small></h1>
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
								<i class="fa fa-file-text-o font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PRODUTO_INVENTARIO');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete="off" class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="id_empresa" id="id_empresa" data-placeholder="<?php echo lang('SELECIONE_EMPRESA');?>" >
										<option value=""></option>
										<?php 
											$retorno_row = $empresa->getEmpresas();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
										?>
													<option value='<?php echo $srow->id;?>' <?php if($srow->id == $id_empresa) echo 'selected="selected"';?>><?php echo $srow->nome;?></option>
										<?php
												endforeach;
												unset($srow);
											endif;
										?>
									</select>
									&nbsp;&nbsp;
									<select class="select2me form-control input-large" name="mes_ano" id="mes_ano" data-placeholder="<?php echo lang('SELECIONE_ANO');?>" >
										<option value=""></option>
										<?php 
												$retorno_row = $gestao->getListaAno("nota_fiscal", "data_emissao", "DESC");
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>				
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $mes_ano) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
									<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor;?> imprimirinventario' title='<?php echo lang('IMPRIMIR');?>'><i class='fa fa-print'>&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR');?></a>
								</div>
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
<?php case "extrato": ?>
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
								<a href="javascript:void(0);" class="btn btn-sm <?php echo $core->primeira_cor;?>" onclick="javascript:void window.open('ver_extrato.php?dataini=<?php echo $dataini;?>&datafim=<?php echo $datafim;?>&id_banco=<?php echo $id_banco;?>','<?php echo lang('EXTRATO');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR');?></a>
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
										<td><?php echo ($dataini);?></td>
										<td><?php echo lang('SALDO');?></td>
										<td>-</td>
										<td><strong <?php echo ($saldoinicial < 0) ? 'class="font-red"' : 'class="font-green"'?>><?php echo moeda($saldoinicial);?></strong></td>	
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
										<td><strong <?php echo ($exrow->tipo == 'D') ? 'class="font-red"' : 'class="font-green"'?>><?php echo moeda($exrow->valor);?></strong></td>	
										<td><strong <?php echo ($saldo < 0) ? 'class="font-red"' : 'class="font-green"'?>><?php echo moeda($saldo);?></strong></td>	
										<td><?php echo $exrow->tipo;?></td>
									</tr>
								<?php endforeach;?>
								<?php unset($exrow);?>
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
<?php default: ?>
<div class='imagem-fundo'>
	<img src='assets/img/logo.png' border='0'>
</div>
<?php break;?>
<?php endswitch;?>