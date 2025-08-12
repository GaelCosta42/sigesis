<?php
  /**
   * Faturamento
   *
   */
  if (!defined('_VALID_PHP'))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Administrativo())
	  redirect_to('login.php');

  $datafiltro = get('datafiltro');
?>
<?php switch(Filter::$acao): case 'recebidas': ?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;
$id_banco = (get('id_banco')) ? get('id_banco') : 0;
$dataini = (get('dataini')) ? get('dataini') : date('01/m/Y');
$data = explode("/", $dataini);
$ultimo = cal_days_in_month(CAL_GREGORIAN, $data[1], $data[2]);
$datafim = (get('datafim')) ? get('datafim') : date($ultimo.'/'.$data[1].'/'.$data[2]);
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0;
$id_cadastro = (get('id_cadastro')) ? get('id_cadastro') : 0;
$filtro = (get('cadastro')) ? get('cadastro') : 0;

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
			var id_cadastro = $("#id_cadastro").val();
			var id_banco = $("#id_banco").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var id_empresa = $("#id_empresa").val();
			var filtro = $("cadastro").val();
			window.location.href = 'index.php?do=faturamento&acao=recebidas&dataini='+ dataini +'&datafim='+ datafim +'&id_banco='+ id_banco +'&id_empresa='+ id_empresa +'&id_cadastro='+ id_cadastro +'&filtro='+filtro;
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
									<input name="id_cadastro" id="id_cadastro" type="hidden" />
									<input type="text" autocomplete="off" class="form-control caps listar_cadastro input-xlarge" name="cadastro" placeholder="<?php echo lang('BUSCAR_CLIENTE');?>">
									<br>
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
										$id_cadastro = get('id_cadastro');
										$total = 0;
										$descricao = '';
										$retorno_row = $faturamento->getReceitas($id_cadastro, $dataini, $datafim, $id_banco, $id_empresa, $filtro);
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
												<td><?php echo moedap($exrow->valor);?></td>
												<td><?php echo moedap($exrow->valor_pago);?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo $pago;?></td>
												<td>
													<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_receita.php?id_receita=<?php echo $exrow->id;?>','<?php echo lang('IMPRIMIR_RECIBO').$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a>
													<a href="javascript:void(0);" onclick="javascript:void window.open('ver_receita.php?id_receita=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
													<?php if ($usuario->is_Master()): ?>
														<?php if (!$exrow->id_despesa): ?>
															<a href='index.php?do=faturamento&acao=editarreceita&id=<?php echo $exrow->id;?>' class='btn btn-sm blue' title='<?php echo lang('EDITAR').': '.$exrow->descricao;?>'><i class='fa fa-pencil'></i></a>
															<a href='javascript:void(0);' class='btn btn-sm red apagar' id='<?php echo $exrow->id;?>' acao='apagarReceita' title='<?php echo lang('APAGAR').": ".$descricao;?>'><i class='fa fa-times'></i></a>
														<?php else: ?>
															<a href='javascript:void(0);' class='btn btn-sm red apagarTransferenciaBancos' id_outro='<?php echo $exrow->id_despesa; ?>' id='<?php echo $exrow->id;?>' acao='apagarReceita' title='<?php echo lang('BANCO_TRANSFERENCIA_APAGAR').": ".$descricao;?>'><i class='fa fa-times'></i></a>
														<?php endif; ?>

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
										<td colspan="9"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo moedap($total);?></strong></td>
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
<?php case 'cadastro': ?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;
$id_cadastro = (get('id_cadastro')) ? get('id_cadastro') : 0;

?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#buscar').click(function() {
			var id_cadastro = $("#id_cadastro").val();
			window.location.href = 'index.php?do=faturamento&acao=cadastro&id_cadastro='+ id_cadastro;
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
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_RECEITAS');?></small></h1>
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
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('FINANCEIRO_RECEITAS');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete='off' class="form-inline">
								<div class="form-group">
									<input name="id_cadastro" id="id_cadastro" type="hidden" />
									<input type="text" autocomplete="off" class="form-control caps listar_cadastro input-large" name="cadastro" placeholder="<?php echo lang('BUSCAR');?>">
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable-asc'>
								<thead class='flip-content'>
									<tr>
										<th>#</th>
										<th>#</th>
										<th><?php echo lang('DATA_PAGAMENTO');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('NOTA');?></th>
										<th><?php echo lang('NUMERO_NOTA');?></th>
										<th><?php echo lang('DOCUMENTO');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('VALOR');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
										<th><?php echo lang('DATA_VENCIMENTO');?></th>
										<th><?php echo lang('PAGO');?></th>
										<th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$total = 0;
										$descricao = '';
										$retorno_row = $faturamento->getReceitasCadastro($id_cadastro);
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
											if($exrow->inativo) {
												$pago = "<span class='label label-sm bg-red'>".lang('CANCELADO')."</span>";
											}

								?>
											<tr>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo $exrow->id;?></td>
												<td><?php echo exibedata($exrow->data_recebido);?></td>
												<td><?php echo ($exrow->empresa);?></td>
												<td><?php echo ($exrow->banco);?></td>
													<td><a href="index.php?do=cadastro&acao=receitas&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->cliente;?></a></td>
													<td><a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota;?>"><?php echo $exrow->numero_nota;?></a></td>
												<td><?php echo ($exrow->numero_nota);?></td>
												<td><?php echo ($exrow->duplicata);?></td>
												<td><?php echo $descricao;?></td>
												<td><?php echo moedap($exrow->valor);?></td>
												<td><?php echo moedap($exrow->valor_pago);?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo $pago;?></td>
												<td width="130px">
													<a href="javascript:void(0);" onclick="javascript:void window.open('ver_receita.php?id_receita=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
												<?php if ($usuario->is_Master()): ?>
													<a href='index.php?do=faturamento&acao=editarreceita&id=<?php echo $exrow->id;?>' class='btn btn-sm blue' title='<?php echo lang('EDITAR').': '.$exrow->descricao;?>'><i class='fa fa-pencil'></i></a>
													<?php if (!$exrow->inativo): ?>
														<a href='javascript:void(0);' class='btn btn-sm red apagar' id='<?php echo $exrow->id;?>' acao='apagarReceita' title='<?php echo lang('APAGAR').": ".$descricao;?>'><i class='fa fa-times'></i></a>
													<?php endif; ?>
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
										<td colspan="10"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo moedap($total);?></strong></td>
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
<?php case "receber": ?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;
$id_banco = (get('id_banco')) ? get('id_banco') : 0;
$dataini = (get('dataini')) ? get('dataini') : date('01/m/Y');
$data = explode("/", $dataini);
$ultimo = cal_days_in_month(CAL_GREGORIAN, $data[1], $data[2]);
$datafim = (get('datafim')) ? get('datafim') : date($ultimo.'/'.$data[1].'/'.$data[2]);
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0;
$id_cadastro = (get('id_cadastro')) ? get('id_cadastro') : 0;
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#imprimir_data').click(function() {
			var id_banco = $("#id_banco").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var id_empresa = $("#id_empresa").val();
			var filtro = $("input[type='search']").val();
			window.open('pdf_receber.php?&dataini='+ dataini +'&datafim='+ datafim +'&id_banco='+ id_banco +'&id_empresa='+ id_empresa +'&ordem_cliente=0&filtro='+filtro,'Imprimir Receitas','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
	});

	$(document).ready(function () {
		$('#imprimir_cliente').click(function() {
			var id_banco = $("#id_banco").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var id_empresa = $("#id_empresa").val();
			var filtro = $("input[type='search']").val();
			window.open('pdf_receber.php?&dataini='+ dataini +'&datafim='+ datafim +'&id_banco='+ id_banco +'&id_empresa='+ id_empresa +'&ordem_cliente=1&filtro='+filtro,'Imprimir Receitas','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
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
			var id_cadastro = $("#id_cadastro").val();
			window.location.href = 'index.php?do=faturamento&acao=receber&dataini='+ dataini +'&datafim='+ datafim + '&id_cadastro='+id_cadastro +'&id_banco='+ id_banco +'&id_empresa='+ id_empresa;
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
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_RECEITAS');?></small></h1>
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
								<i class='fa fa-exclamation font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('CONTAS_A_RECEBER');?></span>
							</div>
							<div class='actions btn-set'>
								<small class="font-blue"><i class='fa fa-square'>&nbsp;&nbsp;</i><?php echo lang('NAO_FISCAL');?></small>&nbsp;&nbsp;
								<a href='index.php?do=faturamento&acao=adicionar' class='btn btn-sm <?php echo $core->primeira_cor;?>'><i class='fa fa-plus-square'>&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete='off' class="form-inline">
								<div class="form-group">
									<input name="id_cadastro" id="id_cadastro" type="hidden" />
									<input type="text" autocomplete="off" class="form-control caps listar_cadastro input-xlarge" name="cadastro" placeholder="<?php echo lang('BUSCAR');?>">
									<br>
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
									<br/><br/>
									<button type="button" id="imprimir_data" class="btn green"><i class="fa fa-print"/></i> <?php echo lang('IMPRIMIR_POR_DATA');?></button>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<button type="button" id="imprimir_cliente" class="btn green"><i class="fa fa-print"/></i> <?php echo lang('IMPRIMIR_POR_CLIENTE');?></button>
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable-asc'>
								<thead class='flip-content'>
									<tr>
										<th>#</th>
										<th>#</th>
										<th><?php echo lang('DATA_VENCIMENTO');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('NOTA');?></th>
										<th><?php echo lang('DOCUMENTO');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('VALOR');?></th>
										<th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$id_cadastro = get('id_cadastro');
										$total = 0;
										$descricao = '';
										$retorno_row = $faturamento->getReceitasReceber($id_cadastro, $dataini, $datafim, $id_banco, $id_empresa);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$categoria_pagamento = getValue("id_categoria", "tipo_pagamento", "id = ".$exrow->tipo);
											$total += $exrow->valor;
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
											$enviado = ($exrow->enviado) ? 'yellow-crusta' : 'grey-cascade';
											if($exrow->enviado) {
												$enviado = ($exrow->remessa == 1) ? 'purple' : 'yellow-crusta';
											} else {
												$enviado = 'grey-cascade';
											}
											$estilo = '';
											if(!$exrow->fiscal) {
												$estilo = "class='info'";
											}
											$is_boleto = true;
											if($exrow->atrasado and $exrow->remessa != 1) {
												$is_boleto = false;
											}

								?>
											<tr <?php echo $estilo;?>>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo $exrow->id;?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo ($exrow->empresa);?></td>
												<td><?php echo ($exrow->banco);?></td>
												<td><a href="index.php?do=cadastro&acao=receitas&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->cadastro;?></a></td>
												<td><a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota;?>"><?php echo $exrow->numero_nota;?></a></td>
												<td><?php echo ($exrow->duplicata);?></td>
												<td><?php echo $descricao;?></td>
												<td width="70px"><?php echo moedap($exrow->valor);?></td>
												<td width="210px">
													<a href="javascript:void(0);" onclick="javascript:void window.open('ver_receita.php?id_receita=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
													<a href='javascript:void(0);' class='btn btn-sm green pagarfinanceiro' id_banco='<?php echo $exrow->id_banco;?>' valor_pago='<?php echo moedap($exrow->valor_pago);?>' id='<?php echo $exrow->id;?>' title='<?php echo lang('PAGAR').$descricao;?>'><i class='fa fa-check'></i></a>
													<a href='index.php?do=faturamento&acao=editarreceita&id=<?php echo $exrow->id;?>' class='btn btn-sm blue' title='<?php echo lang('EDITAR').': '.$exrow->descricao;?>'><i class='fa fa-pencil'></i></a>
												<?php
													$modulo_boleto = getValue("modulo_emissao_boleto", "empresa", "id = " . $exrow->id_empresa);
													if(($modulo_boleto == 1 && ($categoria_pagamento == 3 or $categoria_pagamento == 4) AND $is_boleto)):
														$banco_boleto = getValue("boleto_banco", "empresa", "id = ".$exrow->id_empresa);
												?>
														<a href="boleto_<?php echo $banco_boleto; ?>.php?todos=0&id_pagamento=<?php echo $exrow->id;?>&id_empresa=<?php echo $exrow->id_empresa; ?>" target="_blank" title="<?php echo lang('BOLETO_GERAR');?>" class="btn btn-sm <?php echo $enviado;?>"><i class="fa fa-bold"></i></a>
												<?php endif;?>
													<a href='javascript:void(0);' class='btn btn-sm red apagar' id='<?php echo $exrow->id;?>' acao='apagarReceita' title='<?php echo lang('APAGAR').": ".$descricao;?>'><i class='fa fa-times'></i></a>
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
										<td><strong><?php echo moedap($total);?></strong></td>
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

<div id='pagar-receita' class='modal fade' tabindex='-1'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
				<h4 class='modal-title'><?php echo lang('PAGAR');?></h4>
			</div>
			<form action='' autocomplete="off" method='post' name='pagar_form' id='pagar_form' >
				<div class='modal-body'>
					<div class='row'>
						<div class='col-md-12'>
							<p><?php echo lang('BANCO');?></p>
							<p>
								<select class='select2me form-control' id='id_banco2' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
							</p>
							<p><?php echo lang('VALOR_PAGO');?></p>
							<p>
								<input type='text' class='form-control moedap' name='valor_pago' id='valor_pago2'>
							</p>
							<p><?php echo lang('DATA_PAGAMENTO');?></p>
							<p>
								<input type='text' class='form-control data calendario' name='data_recebido' value='<?php echo date("d/m/Y");?>'>
							</p>
							<br/>
							<p>
								<div class='md-checkbox-list'>
									<div class='md-checkbox'>
										<input type='checkbox' class='md-check' name='novareceita' id='novareceita' value='1'>
										<label for='novareceita'>
										<span></span>
										<span class='check'></span>
										<span class='box'></span>
										<?php echo lang('FINANCEIRO_RECEITAGERAR');?></label>
									</div>
								</div>
							</p>
						</div>
					</div>
				</div>
				<div class='modal-footer'>
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type='button' data-dismiss='modal' class='btn default'><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm('pagarFinanceiro', 'pagar_form');?>
</div>
<?php break;?>
<?php case 'receitarapida': ?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;
$data = date('d/m/Y');
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_RECEITAS');?></small></h1>
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
					<form action="" autocomplete="off" method="post" name="admin_form" id="admin_form">
					<div class='portlet light'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-usd font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('RECEITA_RAPIDA');?></span>
							</div>
							<div class='actions btn-set'>
								<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><i class="fa fa-save">&nbsp;&nbsp;</i><?php echo lang('RECEITA_RAPIDA_SALVAR');?></button>
							</div>
						</div>
						<div class='portlet-body'>
							<div class="table-container">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr role="row" class="heading">
											<th width="20%"><?php echo lang('EMPRESA');?></th>
											<th width="20%"><?php echo lang('BANCO');?></th>
											<th width="15%"><?php echo lang('PAGAMENTO');?></th>
											<th width="15%"><?php echo lang('DESCRICAO');?></th>
											<th width="15%"><?php echo lang('VALOR');?></th>
											<th width="15%"><?php echo lang('DATA');?></th>
											<th><?php echo lang('ACOES');?></th>
										</tr>
										<tr role="row" class="filter">
											<td>
												<select id="id_empresa_receita" class="form-control form-filter input-sm">
													<option value=""><?php echo lang('SELECIONE');?></option>
										<?php
												$retorno_row = $empresa->getEmpresas();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
													<option value="<?php echo $srow->id;?>"><?php echo $srow->nome;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
												</select>
											</td>
											<td>
												<select id="id_banco_receita" class="form-control form-filter input-sm">
													<option value=""><?php echo lang('SELECIONE');?></option>
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
											</td>
											<td>
												<select id="tipo_receita" class="form-control form-filter input-sm">
													<option value=""><?php echo lang('SELECIONE');?></option>
										<?php
												$retorno_row = $faturamento->getTipoPagamento();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														if ($srow->id_categoria==9) continue;
										?>
													<option value='<?php echo $srow->id;?>'><?php echo $srow->tipo;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
												</select>
											</td>
											<td>
												<input type="text" class="form-control form-filter input-sm caps" id="descricao_receita" placeholder="<?php echo lang('DESCRICAO');?>"/>
											</td>
											<td>
												<input type="text" class="form-control form-filter input-sm moedap" id="valor_receita" placeholder="<?php echo lang('VALOR');?>"/>
											</td>
											<td>
												<input type="text" class="form-control form-filter input-sm data calendario" id="data_receita" value="<?php echo $data;?>" placeholder="<?php echo lang('DATA');?>">
											</td>
											<td>
											<a href="javascript:void(0);" class="btn btn-sm green" id="adicionar_nova_receita" title="<?php echo lang('ADICIONAR');?>"><i class="fa fa-plus"></i></a>
											</td>
										</tr>
									</thead>
									<tbody id="tabela_receita">
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php echo $core->doForm("processarReceitaRapida");?>
					</form>
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
<?php case "receber_crediario": ?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;
$id_banco = (get('id_banco')) ? get('id_banco') : 0;
$dataini = (get('dataini')) ? get('dataini') : date('01/m/Y');
$data = explode("/", $dataini);
$ultimo = cal_days_in_month(CAL_GREGORIAN, $data[1], $data[2]);
$datafim = (get('datafim')) ? get('datafim') : date($ultimo.'/'.$data[1].'/'.$data[2]);
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0;
$id_cadastro = (get('id_cadastro')) ? get('id_cadastro') : 0;
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#buscar').click(function() {
			var id_banco = $("#id_banco").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var id_empresa = $("#id_empresa").val();
			var id_cadastro = $("#id_cadastro").val();
			window.location.href = 'index.php?do=faturamento&acao=receber_crediario&dataini='+ dataini +'&datafim='+ datafim + '&id_cadastro='+id_cadastro +'&id_banco='+ id_banco +'&id_empresa='+ id_empresa;
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
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('CREDIARIO_INFORMACOES');?></small></h1>
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
								<i class='fa fa-money font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('PROMISSORIAS_ARECEBER');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete='off' class="form-inline">
								<div class="form-group">
									<input name="id_cadastro" id="id_cadastro" type="hidden" />
									<input type="text" autocomplete="off" class="form-control caps listar_cadastro input-xlarge" name="cadastro" placeholder="<?php echo lang('BUSCAR');?>">
									<br>
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
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable-asc'>
								<thead class='flip-content'>
									<tr>
										<th>#</th>
										<th>#</th>
										<th><?php echo lang('DATA_VENCIMENTO');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('NOTA');?></th>
										<th><?php echo lang('DOCUMENTO');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('VALOR');?></th>
										<th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$id_cadastro = get('id_cadastro');
										$total = 0;
										$descricao = '';
										$retorno_row = $faturamento->getReceitasReceberCrediario($id_cadastro, $dataini, $datafim, $id_banco, $id_empresa);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$categoria_pagamento = getValue("id_categoria", "tipo_pagamento", "id = ".$exrow->tipo);
											$total += $exrow->valor;
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
											$enviado = ($exrow->enviado) ? 'yellow-crusta' : 'grey-cascade';
											if($exrow->enviado) {
												$enviado = ($exrow->remessa == 1) ? 'purple' : 'yellow-crusta';
											} else {
												$enviado = 'grey-cascade';
											}
											$estilo = '';
											if(!$exrow->fiscal) {
												$estilo = "class='info'";
											}
											$is_boleto = true;
											if($exrow->atrasado and $exrow->remessa != 1) {
												$is_boleto = false;
											}

								?>
											<tr <?php echo $estilo;?>>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo $exrow->id;?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo ($exrow->empresa);?></td>
												<td><?php echo ($exrow->banco);?></td>
												<td><a href="index.php?do=cadastro&acao=receitas&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->cadastro;?></a></td>
												<td><a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota;?>"><?php echo $exrow->numero_nota;?></a></td>
												<td><?php echo ($exrow->duplicata);?></td>
												<td><?php echo $descricao;?></td>
												<td width="70px"><?php echo moedap($exrow->valor);?></td>
												<td width="210px">
													<a href="javascript:void(0);" onclick="javascript:void window.open('ver_receita.php?id_receita=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
													<a href='javascript:void(0);' class='btn btn-sm green pagarfinanceiro' id_banco='<?php echo $exrow->id_banco;?>' valor_pago='<?php echo moedap($exrow->valor_pago);?>' id='<?php echo $exrow->id;?>' title='<?php echo lang('PAGAR').$descricao;?>'><i class='fa fa-check'></i></a>
													<a href='index.php?do=faturamento&acao=editarreceita&id=<?php echo $exrow->id;?>' class='btn btn-sm blue' title='<?php echo lang('EDITAR').': '.$exrow->descricao;?>'><i class='fa fa-pencil'></i></a>
												<?php
 													$modulo_boleto = getValue("modulo_emissao_boleto", "empresa", "id = " . $exrow->id_empresa);
													if(($modulo_boleto == 1 && ($categoria_pagamento == 3 or $categoria_pagamento == 4) AND $is_boleto)):
														$banco_boleto = getValue("boleto_banco", "empresa", "id = ".$exrow->id_empresa);
												?>
														<a href="boleto_<?php echo $banco_boleto; ?>.php?todos=0&id_pagamento=<?php echo $exrow->id;?>&id_empresa=<?php echo $exrow->id_empresa; ?>" target="_blank" title="<?php echo lang('BOLETO_GERAR');?>" class="btn btn-sm <?php echo $enviado;?>"><i class="fa fa-bold"></i></a>
												<?php endif;?>
													<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_promissorias.php?id_venda=0&id_receita=<?php echo $exrow->id;?>','<?php echo lang('IMPRIMIR_RECIBO_PROMISSORIAS').': '.$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO_PROMISSORIAS');?>" class="btn btn-sm yellow btn-fiscal"><i class="fa fa-list-alt"></i></a>
													<a href='javascript:void(0);' class='btn btn-sm red apagar' id='<?php echo $exrow->id;?>' acao='apagarReceita' title='<?php echo lang('APAGAR').": ".$descricao;?>'><i class='fa fa-times'></i></a>
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
										<td><strong><?php echo moedap($total);?></strong></td>
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

<div id='pagar-receita' class='modal fade' tabindex='-1'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
				<h4 class='modal-title'><?php echo lang('PAGAR');?></h4>
			</div>
			<form action='' autocomplete="off" method='post' name='pagar_form' id='pagar_form' >
				<div class='modal-body'>
					<div class='row'>
						<div class='col-md-12'>
							<p><?php echo lang('BANCO');?></p>
							<p>
								<select class='select2me form-control' id='id_banco2' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
							</p>
							<p><?php echo lang('PAGAMENTO');?></p>
							<p>
								<select class='select2me form-control' name='tipo_pagamento_crediario' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
									<option value=""></option>
									<?php
										$retorno_row = $faturamento->getTipoPagamento();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
												if ($srow->id_categoria==2 || $srow->id_categoria==4 || $srow->id_categoria==8 || $srow->id_categoria==9)
													continue;
									?>
												<option value='<?php echo $srow->id;?>'><?php echo $srow->tipo;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
								</select>
							</p>
							<p><?php echo lang('VALOR_PAGO');?></p>
							<p>
								<input type='text' class='form-control moedap' name='valor_pago' id='valor_pago2'>
							</p>
							<p><?php echo lang('DATA_PAGAMENTO');?></p>
							<p>
								<input type='text' class='form-control data calendario' name='data_recebido' value='<?php echo date("d/m/Y");?>'>
							</p>
							<br/>
							<p>
								<div class='md-checkbox-list'>
									<div class='md-checkbox'>
										<input type='checkbox' class='md-check' name='novareceita' id='novareceita' value='1'>
										<label for='novareceita'>
										<span></span>
										<span class='check'></span>
										<span class='box'></span>
										<?php echo lang('FINANCEIRO_RECEITAGERAR');?></label>
									</div>
								</div>
							</p>
						</div>
					</div>
				</div>
				<div class='modal-footer'>
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type='button' data-dismiss='modal' class='btn default'><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm('pagarFinanceiro', 'pagar_form');?>
</div>
<?php break;?>
<?php case "adicionar":
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_ADICIONAR_RECEITA');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW FORMULARIO -->
			<div class='row'>
				<div class='col-md-12'>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class='fa fa-plus-square font-<?php echo $core->primeira_cor;?>'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_ADICIONAR_RECEITA');?>
							</div>
						</div>
						<div class='portlet-body form'>
							<!-- INICIO FORM-->
							<form action='' autocomplete="off" method='post' class='form-horizontal' name='admin_form' id='admin_form'>
								<div class='form-body'>
									<div class='row'>
										<div class='col-md-12'>
											<!--col-md-6-->
											<div class='col-md-6'>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMPRESA');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_empresa" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value=""></option>
																<?php
																	$retorno_row = $empresa->getEmpresas();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value="<?php echo $srow->id;?>" <?php if($srow->id == session('idempresa')) echo 'selected="selected"';?>><?php echo $srow->nome;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('BANCO');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('CLIENTE');?></label>
														<div class='col-md-9'>
															<input name="id_cadastro" id="id_cadastro" type="hidden" />
															<input type="text" autocomplete="off" class="form-control caps listar_cadastro" name="cadastro" placeholder="<?php echo lang('BUSCAR');?>">
														</div>
													</div>
												</div>
												<div class="row selecionado ocultar">
													<div class="form-group">
														<label class="control-label col-md-3"></label>
														<div class="col-md-9">
															<span class="label label-success label-sm"><?php echo lang('SELECIONADO');?></span>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DESCRICAO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='descricao'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DUPLICATA');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='duplicata'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('VALOR');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control moedap' name='valor'>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class='col-md-6'>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DATA_VENCIMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control calendario data' name='data_pagamento'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('CATEGORIA');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_pai' id='id_pai_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php
																	$retorno_row = $faturamento->getPai('"C"');
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
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('PLANO_CONTAS');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('PAGAMENTO');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='tipo' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php
																	$retorno_row = $faturamento->getTipoPagamento();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																			if ($srow->id_categoria==9) continue;
																?>
																			<option value='<?php echo $srow->id;?>'><?php echo $srow->tipo;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('REPETICOES');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control inteiro' name='repeticoes'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DIAS');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control inteiro' name='dias'  placeholder='<?php echo lang('FINANCEIRO_DIAS');?>'>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
									</div>
								</div>
								<div class='form-actions'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='col-md-6'>
												<div class='row'>
													<div class='col-md-offset-3 col-md-9'>
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
														<button type='button' id='voltar' class='btn default'><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
											<div class='col-md-6'>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm('processarCreditoBanco');?>
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
<?php case "editarreceita":
	  $id_nota = (get('id_nota')) ? get('id_nota') : 0;
	  $row = Core::getRowById('receita', Filter::$id);
	  $id_pai = getValue("id_pai", "conta", "id = ".$row->id_conta);
	  $nome_cadastro = getValue("nome", "cadastro", "id = ".$row->id_cadastro);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_RECEITAEDITAR');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW FORMULARIO -->
			<div class='row'>
				<div class='col-md-12'>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class='fa fa-edit font-<?php echo $core->primeira_cor;?>'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_RECEITAEDITAR');?>
							</div>
						</div>
						<div class='portlet-body form'>
							<!-- INICIO FORM-->
							<form action='' autocomplete="off" method='post' class='form-horizontal' name='admin_form' id='admin_form'>
								<div class='form-body'>
									<div class='row'>
										<div class='col-md-12'>
											<!--col-md-6-->
											<div class='col-md-6'>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMPRESA');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_empresa" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value=""></option>
																<?php
																	$retorno_row = $empresa->getEmpresas();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value="<?php echo $srow->id;?>" <?php if($srow->id == $row->id_empresa) echo 'selected="selected"';?>><?php echo $srow->nome;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('BANCO');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php
																	$retorno_row = $faturamento->getBancos();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($srow->id == $row->id_banco) echo 'selected="selected"';?>><?php echo $srow->banco;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('CLIENTE');?></label>
														<div class='col-md-9'>
															<input name="id_cadastro" id="id_cadastro" type="hidden" value='<?php echo $row->id_cadastro;?>'/>
															<input type="text" autocomplete="off" class="form-control caps listar_cadastro" name="cadastro" placeholder="<?php echo $nome_cadastro;?>">
														</div>
													</div>
												</div>
												<div class="row selecionado ocultar">
													<div class="form-group">
														<label class="control-label col-md-3"></label>
														<div class="col-md-9">
															<span class="label label-success label-sm"><?php echo lang('SELECIONADO');?></span>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DESCRICAO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='descricao' value='<?php echo $row->descricao;?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DOCUMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='duplicata' value='<?php echo $row->duplicata;?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('PAGAMENTO');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='tipo' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php
																	$retorno_row = $faturamento->getTipoPagamento();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																			if ($srow->id_categoria==9) continue;
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($srow->id == $row->tipo) echo 'selected="selected"';?>><?php echo $srow->tipo;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('NUMERO_NOTA');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_nota' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php
																	$retorno_row = $produto->getNumeroNota($row->id_cadastro);
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($srow->id == $row->id_nota) echo 'selected="selected"';?>><?php echo $srow->numero_nota;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class='col-md-6'>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('VALOR');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control moedap' name='valor' value='<?php echo moedap($row->valor);?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('VALOR_PAGO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control moedap' name='valor_pago' value='<?php echo moedap($row->valor_pago);?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DATA_VENCIMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control calendario data' name='data_pagamento' value='<?php echo exibedata($row->data_pagamento);?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DATA_PAGAMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control calendario data' name='data_recebido' value='<?php echo (exibedata($row->data_recebido) != "-") ? exibedata($row->data_recebido) : "";?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DATA_FISCAL');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control calendario data' name='data_fiscal' value='<?php echo (exibedata($row->data_fiscal) != "-") ? exibedata($row->data_fiscal) : "";?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('CATEGORIA');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_pai' id='id_pai_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php
																	$retorno_row = $faturamento->getPai('"C"');
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($srow->id == $id_pai) echo 'selected="selected"';?>><?php echo $srow->conta;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<?php
													$retorno_row = $faturamento->getFilho($id_pai);
												?>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('PLANO_CONTAS');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id_filho;?>' <?php if($srow->id_filho == $row->id_conta) echo 'selected="selected"';?>><?php echo $srow->filho;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'></label>
														<div class='col-md-9'>
															<div class='md-checkbox-list'>
																<div class='md-checkbox'>
																	<input type='checkbox' class='md-check' name='fiscal' id='fiscal' value='1' <?php echo getChecked($row->fiscal, 1);?>>
																	<label for='fiscal'>
																	<span></span>
																	<span class='check'></span>
																	<span class='box'></span>
																	<?php echo lang('FISCAL');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'></label>
														<div class='col-md-9'>
															<div class='md-checkbox-list'>
																<div class='md-checkbox'>
																	<input type='checkbox' class='md-check' name='pago' id='pago' value='1' <?php if($row->pago==1) echo 'checked="checked"';?>>
																	<label for='pago'>
																	<span></span>
																	<span class='check'></span>
																	<span class='box'></span>
																	<?php echo lang('PAGO');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
									</div>
								</div>
								<input type='hidden' name='id_receita' value='<?php echo $row->id;?>' />
								<input type='hidden' name='id_nota' value='<?php echo $id_nota;?>' />
								<input type='hidden' name='promissoria' value='<?php echo $row->promissoria;?>' />
								<div class='form-actions'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='col-md-6'>
												<div class='row'>
													<div class='col-md-offset-3 col-md-9'>
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
														<button type='button' id='voltar' class='btn default'><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
											<div class='col-md-6'>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm('editarReceitas');?>
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
<?php case 'cheques': ?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;
?>
<!-- INICIO BOX MODAL -->
<div id='pagar-cheque' class='modal fade' tabindex='-1'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
				<h4 class='modal-title'><?php echo lang('PAGAR_CHEQUE');?></h4>
			</div>
			<form action='' autocomplete="off" method='post' name='cheque_form' id='cheque_form' >
				<div class='modal-body'>
					<div class='row'>
						<div class='col-md-12'>
							<p><?php echo lang('BANCO');?></p>
							<p>
								<select class='select2me form-control' id='id_banco2' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
							</p>
						</div>
					</div>
				</div>
				<div class='modal-footer'>
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type='button' data-dismiss='modal' class='btn default'><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm('pagarCheque', 'cheque_form');?>
</div>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_CHEQUES');?></small></h1>
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
								<i class='fa fa-stack-overflow font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('FINANCEIRO_CHEQUES');?></span>
							</div>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable-asc'>
								<thead class='flip-content'>
									<tr>
										<th>#</th>
										<th><?php echo lang('DATA_PAGAMENTO');?></th>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('NOME');?></th>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('NUMERO');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
										<th><?php echo lang('DATA_VENCIMENTO');?></th>
										<th><?php echo lang('PAGO');?></th>
										<th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$total = 0;
										$destaque = '';
										$retorno_row = $faturamento->getCheques();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
												$total += $exrow->valor_pago;
												$destaque = '';
												if($exrow->atrasado){
													$destaque = 'danger';
												}
												if($exrow->hoje){
													$destaque = 'info';
												}
												if($exrow->pago == 1){
													$destaque = 'success';
												}
												if($exrow->pago == 2){
													$destaque = 'warning';
												}
												$pago = '';
												if($exrow->pago == 1) {
													$pago = "<span class='label label-sm bg-green'>".lang('SIM')."</span>";
												} else {
													$pago = "<span class='label label-sm bg-red'>".lang('NAO')."</span>";
												}
								?>
											<tr class='<?php echo $destaque;?>'>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo exibedata($exrow->data_recebido);?></td>
												<td><?php echo $exrow->banco;?></td>
													<td><a href="index.php?do=cadastro&acao=receitas&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->cadastro;?></a></td>
												<td><?php echo $exrow->nome_cheque;?></td>
												<td><?php echo $exrow->banco_cheque;?></td>
												<td><?php echo $exrow->numero_cheque;?></td>
												<td><?php echo moedap($exrow->valor_pago);?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo $pago;?></td>
												<td>
													<?php if($exrow->pago == 1): ?>
													<a href='javascript:void(0);' class='btn btn-sm yellow estornarcheque' id='<?php echo $exrow->id;?>'  title='<?php echo lang('ESTORNAR_CHEQUE').": ".$exrow->cadastro;?>'><i class='fa fa-repeat'></i></a>
													<?php else: ?>
													<a href='javascript:void(0);' class='btn btn-sm green pagarcheque' id='<?php echo $exrow->id;?>'  id_banco='<?php echo $exrow->id_banco;?>' title='<?php echo lang('PAGAR_CHEQUE').": ".$exrow->cliente;?>'><i class='fa fa-usd'></i></a>
													<?php endif;?>
												</td>
											</tr>
								<?php
											endforeach;
											unset($exrow);
										endif;
								?>
								</tbody>
								<tfoot class='flip-content'>
									<tr>
										<td colspan="7"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo moedap($total);?></strong></td>
										<td colspan="3"></td>
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
<?php case 'cartoes': ?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;
$numero_cartao = (get('numero_cartao')) ? get('numero_cartao') : 0;
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#buscar').click(function() {
			var numero_cartao = $("#numero_cartao").val();
			window.location.href = 'index.php?do=faturamento&acao=cartoes&numero_cartao='+ numero_cartao;
		});
	});
	// ]]>
</script>
<!-- INICIO BOX MODAL -->
<div id='alterar-receita' class='modal fade' tabindex='-1'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
				<h4 class='modal-title'><?php echo lang('RECEITA');?></h4>
			</div>
			<form action='' autocomplete="off" method='post' name='receita_form' id='receita_form' >
				<div class='modal-body'>
					<div class='row'>
						<div class='col-md-12'>
							<p><?php echo lang('BANCO');?></p>
							<p>
								<select class='select2me form-control' id='id_banco2' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
							</p>
							<p><?php echo lang('DATA_PAGAMENTO');?></p>
							<p>
								<input type='text' class='form-control data calendario' id='data_recebido' name='data_recebido' value='<?php echo date("d/m/Y");?>'>
							</p>
						</div>
					</div>
				</div>
				<input type='hidden' name='numero_cartao' value='<?php echo $numero_cartao;?>' />
				<div class='modal-footer'>
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type='button' data-dismiss='modal' class='btn default'><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm('processarDataReceita', 'receita_form');?>
</div>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_CARTOES');?></small></h1>
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
								<i class='fa fa-search font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('FINANCEIRO_CARTOES');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete='off' class="form-inline">
								<div class="form-group">
									<label><?php echo lang('NUMERO_CARTAO');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium" name="numero_cartao" id="numero_cartao" value="<?php echo $numero_cartao;?>" >
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<?php if($numero_cartao): ?>
							<h4>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('EXTRATO_SISTEMA');?></span>
							</h4>
							<table class='table table-bordered table-striped table-condensed table-advance'>
								<thead class='flip-content'>
									<tr>
										<th><?php echo lang('DATA_PAGAMENTO');?></th>
										<th><?php echo lang('NUMERO_CARTAO');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('CAIXA');?></th>
										<th><?php echo lang('TIPO');?></th>
										<th><?php echo lang('PARCELA');?></th>
										<th><?php echo lang('VALOR');?></th>
										<th><?php echo lang('VALOR_TOTAL');?></th>
										<th><?php echo lang('DATA_VENCIMENTO');?></th>
										<th><?php echo lang('BANCO');?></th>
										<?php if ($usuario->is_Master()): ?>
										<th><?php echo lang('OPCOES');?></th>
										<?php endif; ?>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $faturamento->getCartaoSistema($numero_cartao);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
								?>
											<tr>
												<td><?php echo exibedata($exrow->data_recebido);?></td>
												<td><?php echo $exrow->numero_cartao;?></td>
												<td><?php echo $exrow->cadastro;?></td>
												<td><?php echo $exrow->id_caixa;?></td>
												<td><?php echo $exrow->tipo;?></td>
												<td><?php echo $exrow->parcelas_cartao."/".$exrow->total_parcelas;?></td>
												<td><?php echo moedap($exrow->valor_parcelas_cartao);?></td>
												<td><?php echo moedap($exrow->valor_total_cartao);?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo $exrow->banco;?></td>
												<?php if ($usuario->is_Administrativo()): ?>
												<td>
													<a href='javascript:void(0);' class='btn btn-sm blue alterarreceita' id='<?php echo $exrow->id_receita;?>' tipo='<?php echo $exrow->tipo;?>' id_banco='<?php echo $exrow->id_banco;?>' data_recebido='<?php echo exibedata($exrow->data_recebido);?>' title='<?php echo lang('EDITAR').$exrow->numero_cartao;?>'><i class='fa fa-pencil'></i></a>
												</td>
												<?php endif; ?>
											</tr>
								<?php
											endforeach;
											unset($exrow);
										endif;
								?>
								</tbody>
							</table>
							<?php endif; ?>
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
<?php case "plano_contas":
	$id_conta = get('id_conta');
	$row = ($id_conta) ?  Core::getRowById('conta', $id_conta) : 0;
	$tipo = ($id_conta) ? $row->tipo : '';
	$id_pai = ($id_conta) ? $row->id_pai : 0;
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_PLANO_CONTAS');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW FORMULARIO -->
			<div class='row'>
				<div class='col-md-12'>
					<div class='portlet box <?php echo $core->primeira_cor;?>'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-bars'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_PLANO_CONTAS');?>
							</div>
						</div>
						<div class='portlet-body form'>
							<!-- INICIO FORM-->
							<form action='' autocomplete="off" method='post' class='form-horizontal' name='admin_form' id='admin_form'>
								<div class='form-body'>
									<div class='form-group'>
										<label class='control-label col-md-2'><?php echo lang('TIPO');?></label>
										<div class='col-md-6'>
											<select class='select2me form-control' name='tipo' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
												<option value=''></option>
												<option value='C' <?php if($tipo == 'C') echo 'selected="selected"';?>><?php echo lang('RECEITA');?></option>
												<option value='D' <?php if($tipo == 'D') echo 'selected="selected"';?>><?php echo lang('DESPESA');?></option>
											</select>
										</div>
									</div>
									<div class='form-group'>
										<label class='control-label col-md-2'><?php echo lang('CATEGORIA');?></label>
										<div class='col-md-6'>
											<select class='select2me form-control' name='id_pai' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
												<option value='NULL'>CATEGORIA PRINCIPAL</option>
												<?php
													$retorno_row = $faturamento->getPai();
													if ($retorno_row):
														foreach ($retorno_row as $srow):
												?>
															<option value='<?php echo $srow->id;?>' <?php if($id_pai == $srow->id) echo 'selected="selected"';?>><?php echo $srow->conta;?></option>
												<?php
														endforeach;
														unset($srow);
													endif;
												?>
											</select>
										</div>
									</div>
									<div class='form-group'>
										<label class='control-label col-md-2'><?php echo lang('PLANO_CONTAS');?></label>
										<div class='col-md-6'>
											<input type='text' class='form-control caps' name='conta' value='<?php echo ($row) ? $row->conta : '';?>'>
										</div>
									</div>
									<div class='form-group'>
										<label class='control-label col-md-2'><?php echo lang('CODIGO_CONTABIL');?></label>
										<div class='col-md-2'>
											<input type='text' class='form-control caps' name='contabil' value='<?php echo ($row) ? $row->contabil : '';?>'>
										</div>
									</div>
									<div class='form-group'>
										<label class='control-label col-md-2'><?php echo lang('ORDEM');?></label>
										<div class='col-md-2'>
											<input type='text' class='form-control caps' name='ordem' value='<?php echo ($row) ? $row->ordem : '';?>'>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2"></label>
										<div class="col-md-2">
											<div class="md-checkbox-list">
												<div class="md-checkbox">
													<input type="checkbox" class="md-check" name="dre" id="dre"  value="1" <?php echo ($row) ? ($row->dre) ? 'checked' : '' : '';?>>
													<label for="dre">
													<span></span>
													<span class="check"></span>
														<span class="box"></span>
													<?php echo lang('MOSTRAR_DRE');?></label>
												</div>
											</div>
										</div>
									</div>
									<?php if($id_conta):?>
										<input type='hidden' name='id' value='<?php echo $id_conta;?>' />
									<?php endif;?>
								</div>
								<div class='form-actions'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='col-md-6'>
												<div class='row'>
													<div class='col-md-offset-3 col-md-9'>
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
														<button type='button' id='voltar' class='btn default'><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
											<div class='col-md-6'>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm('processarConta');?>
							<!-- FINAL FORM-->
						</div>
					</div>
				</div>
			</div>
			<!-- FINAL DO ROW FORMULARIO -->
			<!-- INICIO DO ROW TABELA -->
			<div class='row'>
				<div class='col-md-12'>
					<!-- INICIO TABELA -->
					<div class='portlet light'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-list font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('LISTAR');?></span>
							</div>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable'>
								<thead class='flip-content'>
									<tr>
										<th>#</th>
										<th><?php echo lang('CATEGORIA');?></th>
										<th><?php echo lang('PLANO_CONTAS');?></th>
										<th><?php echo lang('CODIGO_CONTABIL');?></th>
										<th><?php echo lang('TIPO');?></th>
										<th><?php echo lang('EXIBIR');?></th>
										<th><?php echo lang('DRE');?></th>
										<th><?php echo lang('ORDEM');?></th>
										<th><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $faturamento->getContas();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
												$style = '';
												if(!$exrow->exibir){
													$style = 'class="bg-red"';
												}
								?>
												<tr <?php echo $style;?>>
													<td><?php echo $exrow->id;?></td>
													<td><?php echo ($exrow->pai) ? $exrow->pai : '<span class="label label-sm bg-green">CATEGORIA PRINCIPAL</span>';?></td>
													<td><?php echo $exrow->filho;?></td>
													<td><?php echo $exrow->contabil;?></td>
													<td><?php echo tipoConta($exrow->tipo);?></td>
													<td><?php echo ($exrow->exibir) ? lang('SIM') : lang('NAO');?></td>
													<td><?php echo ($exrow->dre) ? lang('SIM') : lang('NAO');?></td>
													<td><?php echo $exrow->ordem;?></td>
													<td>
														<?php if($exrow->exibir):?>
															<a href='index.php?do=faturamento&acao=plano_contas&id_conta=<?php echo $exrow->id;?>' class='btn btn-sm blue' title='<?php echo lang('EDITAR').': '.$exrow->filho;?>'><i class='fa fa-pencil'></i></a>
															<a href='javascript:void(0);' class='btn btn-sm red ocultarconta' id='<?php echo $exrow->id;?>' title='<?php echo lang('FINANCEIRO_CONTA_OCULTAR').': '.$exrow->filho;?>'><i class='fa fa-minus'></i></a>
														<?php else:?>
															<a href='javascript:void(0);' class='btn btn-sm green conta' id='<?php echo $exrow->id;?>' title='<?php echo lang('FINANCEIRO_CONTA_EXIBIR').': '.$exrow->filho;?>'><i class='fa fa-check'></i></a>
														<?php endif;?>
													</td>
												</tr>
								<?php
											endforeach;
											unset($exrow);
										endif;
								?>
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
<?php case "metasdre":

if ($core->tipo_sistema==1 || $core->tipo_sistema==3)
		redirect_to("login.php");

	  $ano = get('ano') ? get('ano') : date('Y');
	  $totaldremeta = array();
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
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#ano').change(function() {
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=faturamento&acao=metasdre&ano='+ ano;
		});
	});
	// ]]>
</script>
<!-- INICIO BOX MODAL -->
<div id="novo-meta" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><?php echo lang('META_MENSAL');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="meta_form" id="meta_form" >
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p><?php echo lang('VALOR');?></p>
							<p>
								<input type="text" class="form-control moedap" name="valor" id="valor">
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarMetaDRE", "meta_form");?>
</div>
<!-- FINAL BOX MODAL -->
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('METAS_DRE');?></small></h1>
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
								<i class="fa fa-crosshairs font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('METAS_DRE')." em ".$ano;?></span>
							</div>
						</div>
						<div class="portlet-body form">
							<form autocomplete='off' class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="ano" id="ano" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
										<option value=""></option>
										<?php
												$retorno_row = $gestao->getListaAno("despesa", "data_vencimento", "DESC", true);
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
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-advance dataTable">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('PLANO_CONTAS');?></th>
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

									$tabela = "";
									$conta_row = $faturamento->getPai('"D"');
									foreach ($conta_row as $crow):
										$id_conta = $crow->id;
										$total = 0;
										$tabela .= '<tr><td>'.$crow->ordem.'</td><td><b>'.$crow->conta.'</b></td>';
										for($i=1;$i<13;$i++){
											$mes_ano = ($i < 10) ? "0".$i."/".$ano : $i."/".$ano;
											$total += $valor = $faturamento->getMetasDRE($mes_ano, $id_conta);
											$valor = ($valor) ? $valor : "0";
											$totaldremeta[$i] += $valor;
											$tabela .= '<td><a href="javascript:void(0);" class="btn btn-sm grey metasdre" valor="'.moedap($valor).'" mes_ano="'.$mes_ano.'" id_conta="'.$id_conta.'" title="Clique para alterar a meta">'.moedap($valor).'</a></td>';
										}
										$tabela .= '<td><b>'.moedap($total).'</b></td></tr>';
									endforeach;
									unset($crow);
									$total = 0;
									$tabela .= '<tr><td>99</td><td><b>TOTAL</b></td>';
									for($i=1;$i<13;$i++){
										$total += $totaldremeta[$i];
										$tabela .= '<td><b>'.moedap($totaldremeta[$i]).'</b></td>';
									}
									$tabela .= '<td><b>'.moedap($total).'</b></td></tr>';
									echo $tabela;
								?>
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
<?php case "propostaordemservico": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<?php
$datafiltro = get('datafiltro');
$id_empresa = get('id_empresa');
$mes_ano = ($datafiltro) ? $datafiltro : date('m/Y');
$OS_Faturar = $faturamento->getOSFaturar($mes_ano,$id_empresa);
$retorno_row = $ordem_servico->getOrdemServicoFinalizadas();
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FATURAMENTO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FATURAMENTO_OS');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('FATURAMENTO_OS_MES').exibeMesAno($mes_ano, true, true).lang('FATURAMENTO_OS_MES_ANTERIORES');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="dashboard-stat <?php echo $core->primeira_cor;?>">
										<div class="visual">
											<i class="fa fa-briefcase"></i>
										</div>
										<div class="details">
											<div class="number">
												<?php
													echo count($retorno_row);
												?>
											</div>
											<div class="desc">
												 <?php echo lang('FATURAMENTO_ORDEM_SERVICO');?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="dashboard-stat <?php echo $core->primeira_cor;?>">
										<div class="visual">
											<i class="fa fa-shopping-cart"></i>
										</div>
										<div class="details">
											<div class="number">
												<?php
													$produtos = 0;
													$valor_faturar = 0;
													if ($retorno_row)
													foreach($retorno_row as $faturar_produto){
														if ($faturar_produto->faturar_produtos==1)
															$produtos++;
														$valor_faturar += $faturar_produto->valor_total;
													}
													echo $produtos;
												?>
											</div>
											<div class="desc">
												 <?php echo lang('FATURAMENTO_ORDEM_SERVICO_PRODUTOS');?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="dashboard-stat <?php echo $core->primeira_cor;?>">
										<div class="visual">
											<i class="fa fa-money"></i>
										</div>
										<div class="details">
											<div class="number">
												<?php
													  echo moedap($valor_faturar);
												?>
											</div>
											<div class="desc">
												 <?php echo lang('FATURAMENTO_ORDEM_SERVICO_VALOR_TOTAL');?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable">
								<thead>
									<tr>
										<th>#</th>
										<th>#</th>
										<th><?php echo lang('DATA_ABERTURA');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('CONTRATO');?></th>
										<th><?php echo lang('RESPONSAVEL');?></th>
										<th><?php echo lang('DATA_FECHAMENTO');?></th>
										<th width="120px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											$estilo = '';
											if($exrow->faturar_produtos==1)
												$estilo = 'class="info"';
								?>
									<tr <?php echo $estilo;?>>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo exibedata($exrow->data_abertura);?></td>
										<td><a href="index.php?do=cadastro&acao=atendimento&id=<?php echo $exrow->id_cadastro;?>" title="<?php echo lang('ORDEM_SERVICO').': '.$exrow->id;?>"><?php echo $exrow->cadastro;?></a></td>
										<td><?php echo ($exrow->id_contrato) ? '<span class="label label-sm bg-green">'.lang('SIM').'</span>' : '<span class="label label-sm bg-red">'.lang('NAO').'</span>';?></td>
										<td><?php echo $exrow->responsavel;?></td>
										<td><?php echo exibedata($exrow->data_fechamento);?></td>
										<td>
											<a href="index.php?do=faturamento&acao=faturarordemservico&id=<?php echo $exrow->id;?>" class="btn btn-sm <?php echo $core->primeira_cor;?>" title="<?php echo lang('FATURAR_ORDEM_SERVICO').': '.$exrow->id;?>"><i class="fa fa-share"></i></a>
											<a href="javascript:void(0);" onclick="javascript:void window.open('pdf_ordem_servico.php?id=<?php echo $exrow->id;?>','<?php echo lang('ORDEM_SERVICO_IMPRIMIR').$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('ORDEM_SERVICO_IMPRIMIR').': '.$exrow->cadastro;?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print"></i></a>

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
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "faturarordemservico":
	$id_atendimento = (Filter::$id) ? Filter::$id : 0;
?>
<div id="faturar-produto" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#7e6ea4;color:#fff">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('FATURAR_OS_PRODUTO_OPCOES');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="faturar_form" id="faturar_form" class="form-horizontal">
				<div class="modal-body">

					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('EMPRESA');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" id="id_empresa" name="id_empresa" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_empresa = $empresa->getEmpresasTodas();
										if ($retorno_empresa):
											foreach ($retorno_empresa as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->nome;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('DATA_VENCIMENTO');?></label>
						<div class="col-md-7">
							<input type="text" class="form-control data calendario" name="data_vencimento">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('TIPO_PAGAMENTO');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" id="tipo_pagamento" name="tipo_pagamento" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_row = $faturamento->getTipoPagamento();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->tipo;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
							</select>
						</div>
					</div>
					<div class="form-group ocultar" id="id_banco">
						<label class="control-label col-md-4"><?php echo lang('BANCO');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" name="id_banco" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_row = $faturamento->getBancos();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->banco;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
								</select>
						</div>
					</div>
					<div class="form-group ocultar" id="total_parcelas">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_PARCELAS');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="total_parcelas" value="1">
						</div>
					</div>
					<div class="form-group ocultar" id="numero_cartao">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_CARTAO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control numero_cartao" name="numero_cartao">
						</div>
					</div>
					<div class="form-group ocultar">
						<label class="control-label col-md-4"><?php echo lang('TITULAR_CHEQUE');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control caps" id="nome_cheque" name="nome_cheque">
						</div>
					</div>
					<div class="form-group ocultar" id="banco_cheque">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_BANCO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="banco_cheque">
						</div>
					</div>
					<div class="form-group ocultar" id="agencia_cheque">
						<label class="control-label col-md-4"><?php echo lang('AGENCIA_BANCO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="agencia_cheque">
						</div>
					</div>
					<div class="form-group ocultar" id="numero_cheque">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_CHEQUE');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="numero_cheque">
						</div>
					</div>
				</div>
				<input name="id_atendimento" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("ProcessarFaturaProdutos", "faturar_form");?>
</div>

<!-- INICIO MODAL PARA DEFINIR OPÇÕES DE PAGAMENTO NO FATURAMENTO DE ORDEM DE SERVIÇO -->
<div id="faturar-ordem-servico" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#45B6AF;color:#fff">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('FATURAR_OS_OPCOES');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="faturar_os_form" id="faturar_os_form" class="form-horizontal">
			<?php
				  $verificar_OS = Core::getRowById('atendimento', Filter::$id);
				  if (($verificar_OS->faturar_produtos != 1 OR $verificar_OS->valor_total_produtos == 0) AND ($verificar_OS->valor_total == 0)): ?>
					<input name="faturamento_zerado" type="hidden" value="1" />
					<br />
					<div class="note note-warning">
						<h4 class="block"><?php echo lang('FATURAR_OS_ZERADA'); ?></h4>
					</div>
			<?php else: ?>
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('DATA_VENCIMENTO');?></label>
						<div class="col-md-7">
							<input type="text" class="form-control data calendario" name="data_vencimento_os">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('TIPO_PAGAMENTO');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" id="tipo_pagamento_os" name="tipo_pagamento_os" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_row = $faturamento->getTipoPagamento();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->tipo;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
							</select>
						</div>
					</div>
					<div class="form-group ocultar" id="id_banco_os">
						<label class="control-label col-md-4"><?php echo lang('BANCO');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" name="id_banco_os" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_row = $faturamento->getBancos();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->banco;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
								</select>
						</div>
					</div>
					<div class="form-group ocultar" id="total_parcelas_os">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_PARCELAS');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="total_parcelas_os" value="1">
						</div>
					</div>
					<div class="form-group ocultar" id="numero_cartao_os">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_CARTAO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control numero_cartao" name="numero_cartao_os">
						</div>
					</div>
					<div class="form-group ocultar">
						<label class="control-label col-md-4"><?php echo lang('TITULAR_CHEQUE');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control caps" id="nome_cheque_os" name="nome_cheque_os">
						</div>
					</div>
					<div class="form-group ocultar" id="banco_cheque_os">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_BANCO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="banco_cheque_os">
						</div>
					</div>
					<div class="form-group ocultar" id="agencia_cheque_os">
						<label class="control-label col-md-4"><?php echo lang('AGENCIA_BANCO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="agencia_cheque_os">
						</div>
					</div>
					<div class="form-group ocultar" id="numero_cheque_os">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_CHEQUE');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="numero_cheque_os">
						</div>
					</div>
				</div>
			<?php endif; ?>
				<input name="id_atendimento" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarFaturamentoOS", "faturar_os_form");?>
</div>
<!-- FINAL MODAL PARA DEFINIR OPÇÕES DE PAGAMENTO NO FATURAMENTO DE ORDEM DE SERVIÇO -->

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FATURAMENTO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FATURAR_ORDEM_SERVICO');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('FATURAR_ORDEM_SERVICO');?></span>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<?php $infoOS = $ordem_servico->getInfoOrdemServico($id_atendimento); ?>
									<!-- INICIO DO ROW FORMULARIO -->
									<div class="row">
										<div class="col-md-12">
											<div class="portlet box <?php echo $core->primeira_cor;?>">
												<div class="portlet-title">
													<div class="caption">
														<i class="fa fa-book">&nbsp;&nbsp;</i><?php echo lang('OS_INFO_CADASTRO');?>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('ORDEM_SERVICO_NUMERO');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo $id_atendimento;?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('ORDEM_SERVICO_STATUS');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo $infoOS->status;?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('ORDEM_SERVICO_CRITICIDADE');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo $infoOS->criticidade;?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('DATA_ABERTURA');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo exibedata($infoOS->data_abertura);?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CONTATO');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo ($infoOS->id_cadastro_contato) ? $infoOS->contato : $infoOS->contato_abertura;?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('RAZAO_SOCIAL');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo $infoOS->razao_social; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CPF_CNPJ');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps cpf_cnpj" value="<?php echo $infoOS->cpf_cnpj; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('ENDERECO');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo $infoOS->endereco.', '.$infoOS->numero.', '.$infoOS->bairro.'-'.$infoOS->cidade;?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('SERVICO');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo $infoOS->descricao_servico; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('DESCRICAO');?></label>
													<div class="col-md-8">
														<textarea readonly class="form-control caps" rows="8"><?php echo $infoOS->descricao_os;?></textarea>
													</div>
												</div>
											</div>
											<?php if ($infoOS->forma_pagamento): ?>
											<div class="row">
												<div class="form-group">
													<div class="col-md-12">
														<div class="note note-info note-bordered">
															<h4><?php echo lang('FATURAR_OS_INFO_PAGAMENTO'); ?></h4>
															<p><?php echo $infoOS->forma_pagamento; ?></p>
														</div>
													</div>
												</div>
											</div>
											<?php endif; ?>
										</div>
									</div>

									<?php if ($infoOS->id_contrato > 0): ?>
									<br>
									<div class="row">
										<div class="col-md-12">
											<h4 class="form-section"><strong><?php echo lang('OS_INFO_EQUIPAMENTO');?></strong></h4>
											<?php
												$item = 0;
												$row_equipamentos = $ordem_servico->getEquipamentosOrdemServico($id_atendimento);
												if($row_equipamentos):
											?>
											<div class="row">
												<div class="col-md-12">
													<table class="table table-bordered table-condensed table-advance">
														<thead>
															<tr>
																<th><?php echo lang('ITEM');?></th>
																<th><?php echo lang('EQUIPAMENTO');?></th>
																<th><?php echo lang('DESCRICAO');?></th>
																<th><?php echo lang('HORAS');?></th>
															</tr>
														</thead>
														<tbody>
														<?php
															foreach ($row_equipamentos as $exrow):
																$item++;
														?>
															<tr>
																<td><?php echo $item;?></td>
																<td><?php echo $exrow->descricao;?></td>
																<td><?php echo $exrow->problema;?></td>
																<td><?php echo ($exrow->quantidade_horas=="00:00:00")?'-':$exrow->quantidade_horas; ?></td>
															</tr>
														<?php endforeach;?>
														</tbody>
													</table>
												</div>
											</div>
											<?php unset($exrow);
												else: echo lang('OS_INFO_EQUIPAMENTO_NAO');
												endif;?>
										</div>
									</div>
									<?php endif; ?>
									<div class="row">
										<div class="col-md-12">
											<?php
												$item = 0;
												$valor_total = 0;
												$row_produtos = $ordem_servico->getProdutosOrdemServico($id_atendimento);
												if($row_produtos):
											?>
											<h4 class="form-section"><strong><?php echo lang('ORDEM_SERVICO_PRODUTOS_LISTA');?></strong></h4>
											<div class="row">
												<div class="col-md-12">
													<table class="table table-bordered table-condensed table-advance">
														<thead>
															<tr>
																<th><?php echo lang('ITEM');?></th>
																<th><?php echo lang('PRODUTO');?></th>
																<th><?php echo lang('QUANT');?></th>
																<th><?php echo lang('VALOR_PARCIAL');?></th>
																<th><?php echo lang('VL_DESCONTO');?></th>
																<th><?php echo lang('VALOR_TOTAL');?></th>
															</tr>
														</thead>
														<tbody>
														<?php
															foreach ($row_produtos as $exrow):
																$item++;
																$valor_total += $exrow->valor_total;
														?>
															<tr>
																<td><?php echo $item;?></td>
																<td><?php echo $exrow->produto;?></td>
																<td><?php echo decimalp($exrow->quantidade);?></td>
																<td><?php echo moedap($exrow->valor_parcial);?></td>
																<td><?php echo moedap($exrow->valor_desconto);?></td>
																<td><?php echo moedap($exrow->valor_total);?></td>
															</tr>
														<?php endforeach;?>
														</tbody>
														<tfoot>
															<tr>
																<td colspan="5"><strong><?php echo lang('TOTAL');?></strong></td>
																<td><strong><?php echo moedap($valor_total);?></strong></td>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
											<?php if ($infoOS->faturar_produtos==1):
													if ($infoOS->valor_total_produtos>0):
											?>
														<div class="row">
															<div class="col-md-12">
																<div class="col-md-6">
																	<div class="col-md-offset-3 col-md-12">
																		<div class='form-group'>
																			<a href="#faturar-produto" data-toggle="modal" class="btn purple" title="<?php echo lang('FATURAR_OS_PRODUTOS_CONFIRMA');?>" id_atendimento="<?php echo $id_atendimento; ?>"><i class="fa fa-usd"></i>&nbsp;<?php echo lang('FATURAR_OS_PRODUTOS_CONFIRMA');?></a>
																		</div>
																	</div>
																</div>
															</div>
														</div>
											<?php 	else: ?>
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<div class="note note-info note-bordered">
																		<p><?php echo lang('FATURAR_OS_PRODUTO_ZERADO'); ?></p>
																	</div>
																</div>
															</div>
														</div>
											<?php	endif; ?>
											<?php else: ?>
											<div class="row">
												<div class="form-group">
													<div class="col-md-12">
														<div class="note note-success note-bordered">
															<p><?php echo lang('FATURAR_OS_PRODUTO_FATURADO'); ?></p>
														</div>
													</div>
												</div>
											</div>
											<?php endif; ?>
											<?php unset($exrow);
												  endif;?>
										</div>
									</div>
								<!-- LISTAGEM DOS HISTÓRICOS DE MOVIMENTAÇÃO REALIZADOS NESTE ATENDIMENTO -->
									<br><br>
									<div class="portlet box <?php echo $core->primeira_cor;?>">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-history">&nbsp;&nbsp;</i><?php echo lang('OS_INFO_HISTORICO');?>
											</div>
										</div>
										<div class="portlet-body form">
											<div class="form-body">
												<div class="row">
													<!--col-md-12-->
													<div class="col-md-12">
														<div class="col-md-12">
															<table class="table table-light">
																<thead>
																	<tr>
																		<th width="40%"><?php echo lang('DESCRICAO');?></th>
																		<th width="10%"><?php echo lang('RESPONSAVEL');?></th>
																		<th width="20%"><?php echo lang('STATUS');?></th>
																		<th width="20%"><?php echo lang('DATA');?></th>
																		<th width="10%"><?php echo lang('HORAS');?></th>
																	</tr>
																</thead>
																<tbody>
																<?php $row_historico = $ordem_servico->getHistoricoOrdemServico($id_atendimento);
																	if ($row_historico):
																		foreach($row_historico as $hrow):
																?>
																			<tr>
																				<td><?php echo $hrow->descricao; ?></td>
																				<td><?php echo strtoupper($hrow->usuario); ?></td>
																				<td><?php echo $hrow->status; ?></td>
																				<td><?php echo exibedata($hrow->data); ?></td>
																				<td><?php echo ($hrow->quantidade_horas=="00:00:00")?'-':$hrow->quantidade_horas; ?></td>
																			</tr>
																<?php
																		endforeach;
																		unset($hrow);
																	endif;
																?>
																</tbody>
															</table>
														</div>
													</div>
													<!--/col-md-12-->
												</div>
												<!-- FINAL DO ROW TABELA -->
											</div>
										</div>
									</div>
									<!-- FINAL DA LISTAGEM DOS HISTÓRICOS DE MOVIMENTAÇÃO -->

									<!-- INICIO DO RESUMO DAS INFORMAÇÕES FINANCEIRAS PARA FATURAMENTO -->
									<br><br>
									<div class="portlet box <?php echo $core->primeira_cor;?>">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-money">&nbsp;&nbsp;</i><?php echo lang('OS_INFO_HISTORICO_FATURAMENTO');?>
											</div>
										</div>
										<div class="portlet-body form">
											<div class="form-body">
												<div class="row">
													<!--col-md-12-->
													<div class="col-md-12">
														<div class="col-md-12">
															<table class="table table-light">
																<thead>
																	<tr>
																		<th width="15%"><?php echo lang('VALOR_HORA_SERVICO');?></th>
																		<th width="20%"><?php echo lang('HORAS_UTILIZADAS');?></th>
																		<th width="15%"><?php echo lang('VALOR_PARCIAL');?></th>
																		<th width="15%"><?php echo lang('VALOR_DESCONTO');?></th>
																		<?php if ($infoOS->faturar_produtos==1): ?>
																			<th width="15%"><?php echo lang('ADICIONAIS');?></th>
																		<?php endif; ?>
																		<th width="20%"><?php echo lang('VALOR_TOTAL');?></th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td><?php echo moedap($infoOS->valor_hora); ?></td>
																		<td><?php echo $infoOS->quantidade_horas; ?></td>
																		<td><?php echo moedap($infoOS->valor_atendimento); ?></td>
																		<td><?php echo moedap($infoOS->valor_desconto); ?></td>
																		<?php if ($infoOS->faturar_produtos==1): ?>
																			<td><?php echo moedap($infoOS->valor_total_produtos); ?></td>
																		<?php endif; ?>
																		<td><?php echo moedap($infoOS->valor_total); ?></td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
													<!--/col-md-12-->
												</div>
												<!-- FINAL DO ROW TABELA -->
											</div>
										</div>
									</div>
									<!-- FINAL DO RESUMO DAS INFORMAÇÕES FINANCEIRAS PARA FATURAMENTO -->

								</div>

								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-12">

													<?php if ($infoOS->id_contrato == 0): ?>
														<a href="#faturar-ordem-servico" data-toggle="modal" class="btn btn-success" title="<?php echo lang('FATURAR_OS_CONFIRMAR');?>" id_atendimento="<?php echo $id_atendimento; ?>">&nbsp;<?php echo lang('FATURAR_OS_CONFIRMAR');?></a>
													<?php endif; ?>
														<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
														<input name='id_atendimento' type='hidden' value='<?php echo $id_atendimento;?>' />
													</div>
												</div>
											</div>
											<div class="col-md-6">
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm("processarFaturamentoOS");?>
							<!-- FINAL FORM-->
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
<?php case "faturamentoproposta": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<?php
$numero_proposta = (get('numero_proposta')) ? get('numero_proposta') : "";
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0;
$id_status = (get('id_status')) ? get('id_status') : 0;
$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y');
$retorno_propostas = $contrato->getPropostasFaturar();
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#buscar').click(function() {
			var numero_proposta = $("#numero_proposta").val();
			var id_empresa = $("#id_empresa").val();
			var id_status = $("#id_status").val();
			var mes_ano = $("#mes_ano").val();
			window.location.href = 'index.php?do=comercial&acao=propostas&id_empresa='+ id_empresa +'&id_status='+ id_status +'&mes_ano='+ mes_ano +'&numero_proposta='+ numero_proposta;
		});
		$('.imprimirproposta').click(function() {
			var id = $(this).attr('id');
			window.open('pdf_proposta.php?id='+id,'<?php echo lang('PROPOSTA_IMPRIMIR');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
		$('.verproposta').click(function() {
			var id = $(this).attr('id');
			window.open('ver_proposta.php?id='+id,'<?php echo lang('PROPOSTA_VER');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
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
				<h1><?php echo lang('FATURAMENTO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FATURAMENTO_PROPOSTA');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('FATURAMENTO_PROPOSTA_MES').exibeMesAno($mes_ano, true, true).lang('FATURAMENTO_OS_MES_ANTERIORES');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="dashboard-stat <?php echo $core->primeira_cor;?>">
										<div class="visual">
											<i class="fa fa-briefcase"></i>
										</div>
										<div class="details">
											<div class="number">
												<?php
													echo ($retorno_propostas)? count($retorno_propostas) : '0';
												?>
											</div>
											<div class="desc">
												 <?php echo lang('FATURAMENTO_PROPOSTAS_FATURAR');?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="dashboard-stat <?php echo $core->primeira_cor;?>">
										<div class="visual">
											<i class="fa fa-file-text-o"></i>
										</div>
										<div class="details">
											<div class="number">
												<?php
													$abrir_ordem_servico = 0;
													$valor_faturar = 0;
													if ($retorno_propostas)
													foreach ($retorno_propostas as $exrow){
														$valor_faturar += $contrato->getTotalProposta($exrow->id);
														$abrir_ordem_servico += $contrato->getQuantidadeOrdemServico($exrow->id);
													}
													echo $abrir_ordem_servico;
												?>
											</div>
											<div class="desc">
												 <?php echo lang('FATURAMENTO_PROPOSTA_ABERTURA_OS');?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="dashboard-stat <?php echo $core->primeira_cor;?>">
										<div class="visual">
											<i class="fa fa-money"></i>
										</div>
										<div class="details">
											<div class="number">
												<?php
													  echo moedap($valor_faturar);
												?>
											</div>
											<div class="desc">
												 <?php echo lang('FATURAMENTO_PROPOSTAS_VALOR_TOTAL');?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable">
								<thead>
									<tr>
										<th>#</th>
										<th>#</th>
										<th><?php echo lang('DATA_PROPOSTA');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('PRAZO_ENTREGA');?></th>
										<th><?php echo lang('RESPONSAVEL');?></th>
										<th><?php echo lang('VALOR_TOTAL');?></th>
										<th><?php echo lang('STATUS');?></th>
										<th width="120px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php

										if($retorno_propostas):
										foreach ($retorno_propostas as $exrow):
											$valor_total = $contrato->getTotalProposta($exrow->id);
											$estilo = '';
											if($exrow->status_ordem_servico==1)
												$estilo = 'class="warning"';
								?>
									<tr <?php echo $estilo;?>>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo exibedata($exrow->data_proposta);?></td>
										<td><a href="index.php?do=cadastro&acao=propostas&id=<?php echo $exrow->id_cadastro;?>" title="<?php echo lang('PROPOSTA_COMERCIAL').': '.$exrow->id;?>"><?php echo $exrow->cadastro;?></a></td>
										<td><?php echo $exrow->entrega;?></td>
										<td><?php echo $exrow->responsavel;?></td>
										<td><?php echo moedap($valor_total);?></td>
										<td><?php echo $exrow->status;?></td>
										<td>
										<?php if($usuario->is_Administrativo()):?>
											<a href="index.php?do=faturamento&acao=faturarproposta&id=<?php echo $exrow->id;?>" class="btn btn-sm <?php echo $core->primeira_cor;?>" title="<?php echo lang('FATURAR_ORDEM_SERVICO').': '.$exrow->id;?>"><i class="fa fa-share"></i></a>
										<?php elseif($exrow->id_status <= 3 and $exrow->responsavel == $usuario->nomeusuario):?>
											<a href="index.php?do=cadastro&acao=propostas&id=<?php echo $exrow->id_cadastro;?>&id_proposta=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->id;?>"><i class="fa fa-pencil"></i></a>
										<?php endif;?>
											<a href="javascript:void(0);" class="btn btn-sm imprimirproposta yellow-gold" id="<?php echo $exrow->id;?>" title="<?php echo lang('PROPOSTA_IMPRIMIR').': '.$exrow->id;?>"><i class="fa fa-print"></i></a>
											<a href="javascript:void(0);" class="btn btn-sm verproposta blue-hoki" id="<?php echo $exrow->id;?>"><i class="fa fa-search"></i></a>
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
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "faturarproposta": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<?php
	$id_proposta = Filter::$id;
	$infoProposta = $contrato->getInfoProposta($id_proposta);
	$valor_desconto = $infoProposta->valor_desconto;
	$total_proposta = 0;
	$proposta_produtos = $contrato->getPropostasProdutos($id_proposta);
	$proposta_servicos = $contrato->getPropostasServicos($id_proposta);
	$proposta_ordem_servico = $contrato->getPropostasOrdemServicos($id_proposta);
	$proposta_pagamento = $contrato->getPropostasPagamento($id_proposta);
?>
<!-- INICIO MODAL PARA DEFINIR OPÇÕES DE FATURAMENTO DE PRODUTOS DA PROPOSTA -->
<div id="faturar-produto-proposta" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#7e6ea4;color:#fff">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('FATURAR_PROPOSTA_PRODUTO_OPCOES');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="faturar_proposta_produto_form" id="faturar_proposta_produto_form" class="form-horizontal">
				<div class="modal-body">

									<?php if ($proposta_pagamento):?>
									<!-- INICIO DA LISTAGEM DE OPÇÕES DE PAGAMENTO PARA ESTA PROPOSTA -->
										<div class="row">
											<div class="col-md-12">
												<h4 class="form-section"><strong><?php echo lang('PROPOSTA_PAGAMENTOS_LISTA');?></strong></h4>
												<div class="row">
													<div class="col-md-12">
														<table class="table table-bordered table-condensed table-advance">
															<thead>
																<tr>
																	<th><?php echo lang('ITEM');?></th>
																	<th><?php echo lang('CONDICAO_PAGAMENTO');?></th>
																	<th><?php echo lang('PARCELAS');?></th>
																</tr>
															</thead>
															<tbody>
															<?php
															$item = 0;
															foreach ($proposta_pagamento as $exrow):
																$item++;
																?>
																<tr>
																	<td><?php echo $item;?></td>
																	<td><?php echo $exrow->condicao;?></td>
																	<td><?php echo $exrow->parcelas;?></td>
																</tr>
															<?php endforeach;?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									<!-- FINAL DA LISTAGEM DE OPÇÕES DE PAGAMENTO PARA ESTA PROPOSTA -->
									<?php endif; ?>

					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('EMPRESA');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" id="id_empresa" name="id_empresa" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_empresa = $empresa->getEmpresasTodas();
										if ($retorno_empresa):
											foreach ($retorno_empresa as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->nome;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('DATA_VENCIMENTO');?></label>
						<div class="col-md-7">
							<input type="text" class="form-control data calendario" name="data_vencimento">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('TIPO_PAGAMENTO');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" id="tipo_pagamento" name="tipo_pagamento" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_row = $faturamento->getTipoPagamento();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->tipo;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
							</select>
						</div>
					</div>
					<div class="form-group ocultar" id="id_banco">
						<label class="control-label col-md-4"><?php echo lang('BANCO');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" name="id_banco" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_row = $faturamento->getBancos();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->banco;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
								</select>
						</div>
					</div>
					<div class="form-group ocultar" id="total_parcelas">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_PARCELAS');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="total_parcelas" value="1">
						</div>
					</div>
					<div class="form-group ocultar" id="numero_cartao">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_CARTAO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control numero_cartao" name="numero_cartao">
						</div>
					</div>
					<div class="form-group ocultar">
						<label class="control-label col-md-4"><?php echo lang('TITULAR_CHEQUE');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control caps" id="nome_cheque" name="nome_cheque">
						</div>
					</div>
					<div class="form-group ocultar" id="banco_cheque">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_BANCO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="banco_cheque">
						</div>
					</div>
					<div class="form-group ocultar" id="agencia_cheque">
						<label class="control-label col-md-4"><?php echo lang('AGENCIA_BANCO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="agencia_cheque">
						</div>
					</div>
					<div class="form-group ocultar" id="numero_cheque">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_CHEQUE');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="numero_cheque">
						</div>
					</div>
				</div>
				<input name="id_proposta" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("ProcessarFaturaProdutosProposta", "faturar_proposta_produto_form");?>
</div>
<!-- DINAL MODAL PARA DEFINIR OPÇÕES DE FATURAMENTO DE PRODUTOS DA PROPOSTA -->

<!-- INICIO MODAL PARA DEFINIR OPÇÕES DE PAGAMENTO NO FATURAMENTO DE PROPOSTA -->
<div id="faturar-proposta" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header" style="background-color:#45B6AF;color:#fff">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('FATURAR_PROPOSTA_OPCOES');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="faturar_proposta_form" id="faturar_proposta_form" class="form-horizontal">
				<div class="modal-body">

									<?php if ($proposta_pagamento):?>
									<!-- INICIO DA LISTAGEM DE OPÇÕES DE PAGAMENTO PARA ESTA PROPOSTA -->
										<div class="row">
											<div class="col-md-12">
												<h4 class="form-section"><strong><?php echo lang('PROPOSTA_PAGAMENTOS_LISTA');?></strong></h4>
												<div class="row">
													<div class="col-md-12">
														<table class="table table-bordered table-condensed table-advance">
															<thead>
																<tr>
																	<th><?php echo lang('ITEM');?></th>
																	<th><?php echo lang('CONDICAO_PAGAMENTO');?></th>
																	<th><?php echo lang('PARCELAS');?></th>
																</tr>
															</thead>
															<tbody>
															<?php
															$item = 0;
															foreach ($proposta_pagamento as $exrow):
																$item++;
																?>
																<tr>
																	<td><?php echo $item;?></td>
																	<td><?php echo $exrow->condicao;?></td>
																	<td><?php echo $exrow->parcelas;?></td>
																</tr>
															<?php endforeach;?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									<!-- FINAL DA LISTAGEM DE OPÇÕES DE PAGAMENTO PARA ESTA PROPOSTA -->
									<?php endif; ?>

					<div class="form-group">
						<div class="col-md-12">
						<?php if ($proposta_produtos):
								if ($infoProposta->nota_produto>0):
						?>
									<div class="alert alert-info"><?php echo lang('FATURAR_PROPOSTA_PRODUTO_FATURADO'); ?></div>
						<?php
								else:
						?>
									<div class="alert alert-warning"><?php echo lang('FATURAR_PROPOSTA_PRODUTO_A_FATURAR'); ?></div>
						<?php   endif;
							  endif;
						?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('EMPRESA');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" id="id_empresa_proposta" name="id_empresa_proposta" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_empresa = $empresa->getEmpresasTodas();
										if ($retorno_empresa):
											foreach ($retorno_empresa as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->nome;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('DATA_VENCIMENTO');?></label>
						<div class="col-md-7">
							<input type="text" class="form-control data calendario" name="data_vencimento_proposta">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4"><?php echo lang('TIPO_PAGAMENTO');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" id="tipo_pagamento_proposta" name="tipo_pagamento_proposta" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_row = $faturamento->getTipoPagamento();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->tipo;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
							</select>
						</div>
					</div>
					<div class="form-group ocultar" id="id_banco_proposta">
						<label class="control-label col-md-4"><?php echo lang('BANCO');?></label>
						<div class="col-md-7">
							<select class="select2me bs-select form-control" name="id_banco_proposta" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
									    $retorno_row = $faturamento->getBancos();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->banco;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
								</select>
						</div>
					</div>
					<div class="form-group ocultar" id="total_parcelas_proposta">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_PARCELAS');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="total_parcelas_proposta" value="1">
						</div>
					</div>
					<div class="form-group ocultar" id="numero_cartao_proposta">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_CARTAO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control numero_cartao" name="numero_cartao_proposta">
						</div>
					</div>
					<div class="form-group ocultar">
						<label class="control-label col-md-4"><?php echo lang('TITULAR_CHEQUE');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control caps" id="nome_cheque_proposta" name="nome_cheque_proposta">
						</div>
					</div>
					<div class="form-group ocultar" id="banco_cheque_proposta">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_BANCO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="banco_cheque_proposta">
						</div>
					</div>
					<div class="form-group ocultar" id="agencia_cheque_proposta">
						<label class="control-label col-md-4"><?php echo lang('AGENCIA_BANCO');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="agencia_cheque_proposta">
						</div>
					</div>
					<div class="form-group ocultar" id="numero_cheque_proposta">
						<label class="control-label col-md-4"><?php echo lang('NUMERO_CHEQUE');?></label>
						<div class="col-md-7">
								<input type="text" class="form-control inteiro" name="numero_cheque_proposta">
						</div>
					</div>
				</div>
				<input name="id_proposta" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarFaturamentoProposta", "faturar_proposta_form");?>
</div>
<!-- FINAL MODAL PARA DEFINIR OPÇÕES DE PAGAMENTO NO FATURAMENTO DE PROPOSTA -->

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('FATURAMENTO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FATURAR_PROPOSTA');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('FATURAR_PROPOSTA');?></span>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<!-- INICIO DO ROW FORMULARIO -->
									<div class="row">
										<div class="col-md-12">
											<div class="portlet box <?php echo $core->primeira_cor;?>">
												<div class="portlet-title">
													<div class="caption">
														<i class="fa fa-book">&nbsp;&nbsp;</i><?php echo lang('PROPOSTA_INFO_FATURAMENTO');?>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('PROPOSTA_NUMERO');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo $id_proposta;?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('PROPOSTA_SITUACAO');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo $infoProposta->status;?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('DATA_ABERTURA');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo exibedata($infoProposta->data_proposta);?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CLIENTE');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo $infoProposta->nome; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('RAZAO_SOCIAL');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps" value="<?php echo $infoProposta->razao_social; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CPF_CNPJ');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps cpf_cnpj" value="<?php echo formatar_cpf_cnpj($infoProposta->cpf_cnpj); ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CIDADE');?></label>
													<div class="col-md-8">
														<input type="text" readonly class="form-control caps cpf_cnpj" value="<?php echo $infoProposta->cidade; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('DESCRICAO');?></label>
													<div class="col-md-8">
														<textarea readonly class="form-control caps" rows="8"><?php echo $infoProposta->observacao;?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>

									<?php if ($proposta_pagamento):?>
									<!-- INICIO DA LISTAGEM DE OPÇÕES DE PAGAMENTO PARA ESTA PROPOSTA -->
										<div class="row">
											<div class="col-md-12">
												<h4 class="form-section"><strong><?php echo lang('PROPOSTA_PAGAMENTOS_LISTA');?></strong></h4>
												<div class="row">
													<div class="col-md-12">
														<table class="table table-bordered table-condensed table-advance">
															<thead>
																<tr>
																	<th><?php echo lang('ITEM');?></th>
																	<th><?php echo lang('CONDICAO_PAGAMENTO');?></th>
																	<th><?php echo lang('PARCELAS');?></th>
																	<th><?php echo lang('DESCONTO');?></th>
																	<th><?php echo lang('VALOR_TOTAL');?></th>
																</tr>
															</thead>
															<tbody>
															<?php
															$item = 0;
															foreach ($proposta_pagamento as $exrow):
																$item++;
																?>
																<tr>
																	<td><?php echo $item;?></td>
																	<td><?php echo $exrow->condicao;?></td>
																	<td><?php echo $exrow->parcelas;?></td>
																	<td><?php echo moedap($infoProposta->valor_desconto);?></td>
																	<td><?php echo moedap($exrow->valor_total);?></td>
																</tr>
															<?php endforeach;?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									<!-- FINAL DA LISTAGEM DE OPÇÕES DE PAGAMENTO PARA ESTA PROPOSTA -->
									<?php endif; ?>

									<?php if ($proposta_produtos): ?>
									<!-- INICIO DE LISTAGEM DE PRODUTOS A FATURAR NESTA PROPOSTA -->
									<div class="row">
										<div class="col-md-12">
											<?php
												$item = 0;
												$valor_total = 0;
											?>
											<h4 class="form-section"><strong><?php echo lang('PROPOSTA_PRODUTOS_LISTA');?></strong></h4>
											<div class="row">
												<div class="col-md-12">
													<table class="table table-bordered table-condensed table-advance">
														<thead>
															<tr>
																<th><?php echo lang('ITEM');?></th>
																<th><?php echo lang('PRODUTO');?></th>
																<th><?php echo lang('QUANT');?></th>
																<th><?php echo lang('VL_UNITARIO');?></th>
																<th><?php echo lang('VALOR_TOTAL');?></th>
															</tr>
														</thead>
														<tbody>
														<?php
															foreach ($proposta_produtos as $exrow):
																$item++;
																$valor_total += $exrow->valor_total;
																$total_proposta += $exrow->valor_total;
														?>
															<tr>
																<td><?php echo $item;?></td>
																<td><?php echo $exrow->produto;?></td>
																<td><?php echo decimalp($exrow->quantidade);?></td>
																<td><?php echo moedap($exrow->valor_venda);?></td>
																<td><?php echo moedap($exrow->valor_total);?></td>
															</tr>
														<?php endforeach;?>
														</tbody>
														<tfoot>
															<tr>
																<td colspan="4"><strong><?php echo lang('TOTAL');?></strong></td>
																<td><strong><?php echo moedap($valor_total);?></strong></td>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
											<?php if ($proposta_produtos):
													if ($infoProposta->nota_produto>0):
											?>
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<div class="note note-success note-bordered">
																		<p><?php echo lang('FATURAR_OS_PRODUTO_FATURADO'); ?></p>
																	</div>
																</div>
															</div>
														</div>
											<?php 	else: ?>
														<div class="row">
															<div class="col-md-12">
																<div class="col-md-6">
																	<div class="col-md-offset-3 col-md-12">
																		<div class='form-group'>
																			<a href="#faturar-produto-proposta" data-toggle="modal" class="btn purple" title="<?php echo lang('FATURAR_OS_PRODUTOS_CONFIRMA');?>" id_atendimento="<?php echo $id_atendimento; ?>"><i class="fa fa-usd"></i>&nbsp;<?php echo lang('FATURAR_OS_PRODUTOS_CONFIRMA');?></a>
																		</div>
																	</div>
																</div>
															</div>
														</div>
											<?php	endif;
												  endif;
											?>
											<?php unset($exrow);?>
										</div>
									</div>
									<!-- FINAL DE LISTAGEM DE PRODUTOS A FATURAR NESTA PROPOSTA -->
									<?php endif; ?>

									<?php if ($proposta_servicos): ?>
									<!-- INICIO DE LISTAGEM DE SERVICOS A FATURAR NESTA PROPOSTA -->
									<div class="row">
										<div class="col-md-12">
											<?php
												$item = 0;
												$valor_total = 0;
											?>
											<h4 class="form-section"><strong><?php echo lang('PROPOSTA_SERVICOS_LISTA');?></strong></h4>
											<div class="row">
												<div class="col-md-12">
													<table class="table table-bordered table-condensed table-advance">
														<thead>
															<tr>
																<th><?php echo lang('ITEM');?></th>
																<th><?php echo lang('SERVICO');?></th>
																<th><?php echo lang('QUANT');?></th>
																<th><?php echo lang('VL_UNITARIO');?></th>
																<th><?php echo lang('VALOR_TOTAL');?></th>
															</tr>
														</thead>
														<tbody>
														<?php
															foreach ($proposta_servicos as $exrow):
																$item++;
																$valor_total += $exrow->valor_total;
																$total_proposta += $exrow->valor_total;
														?>
															<tr>
																<td><?php echo $item;?></td>
																<td><?php echo $exrow->descricao;?></td>
																<td><?php echo decimalp($exrow->quantidade);?></td>
																<td><?php echo moedap($exrow->valor);?></td>
																<td><?php echo moedap($exrow->valor_total);?></td>
															</tr>
														<?php endforeach;?>
														</tbody>
														<tfoot>
															<tr>
																<td colspan="4"><strong><?php echo lang('TOTAL');?></strong></td>
																<td><strong><?php echo moedap($valor_total);?></strong></td>
															</tr>
														</tfoot>
													</table>
												</div>
											</div>
											<?php unset($exrow);?>
										</div>
									</div>
									<!-- FINAL DE LISTAGEM DE SERVICOS A FATURAR NESTA PROPOSTA -->
									<?php endif; ?>

									<?php if ($proposta_ordem_servico): ?>
									<!-- INICIO DE LISTAGEM DE PEDIDO DE ABERTURA DE ORDEM DE SERVICO -->
									<div class="row">
										<div class="col-md-12">
											<?php
												$item = 0;
											?>
											<h4 class="form-section"><strong><?php echo lang('PROPOSTA_OS_LISTA');?></strong></h4>
											<div class="row">
												<div class="col-md-12">
													<table class="table table-bordered table-condensed table-advance">
														<thead>
															<tr>
																<th><?php echo lang('ITEM');?></th>
																<th><?php echo lang('SERVICO');?></th>
																<th><?php echo lang('DESCRICAO_SERVICO');?></th>
																<th><?php echo lang('DESCRICAO_INFO_SERVICOS');?></th>
																<th><?php echo lang('PROPOSTA_OS_GERA_FATURA');?></th>
															</tr>
														</thead>
														<tbody>
														<?php
															foreach ($proposta_ordem_servico as $exrow):
																$item++;
																$estilo = ($exrow->faturar_horas)? "class='warning'" : "";

														?>
															<tr <?php echo $estilo;?>>
																<td><?php echo $item;?></td>
																<td><?php echo $exrow->info_servico;?></td>
																<td><?php echo $exrow->descricao_servico;?></td>
																<td><?php echo $exrow->descricao_proposta;?></td>
																<td><?php echo ($exrow->faturar_horas)?lang('SIM'):lang('NAO');?></td>
															</tr>
														<?php endforeach;?>
														</tbody>
													</table>
												</div>
											</div>
											<?php unset($exrow);?>
										</div>
									</div>
									<!-- FINAL DE LISTAGEM DE PEDIDO DE ABERTURA DE ORDEM DE SERVICO -->
									<?php endif; ?>
									<div class="row">
										<div class="col-md-12">
											<h5><?php echo lang('VALOR_DESCONTO').": ".moedap($valor_desconto);?></h5>
											<h5><?php echo lang('VALOR_TOTAL').": ".moedap($total_proposta);?></h5>
											<h5><strong><?php echo lang('VALOR_PAGAR').": ".moedap($total_proposta-$valor_desconto);?></strong></h5>
										</div>
									</div>
								</div>

								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-12">
													<?php $mensagem = ($proposta_ordem_servico)? lang('FATURAR_PROPOSTA_OS_CONFIRMAR') : lang('FATURAR_PROPOSTA_CONFIRMAR'); ?>
													<?php
														$contar = $faturamento->getTotalFormaPagamento($id_proposta);
														if ($contar == 1): ?>
														<a href="#faturar-proposta" data-toggle="modal" class="btn btn-success" title="<?php echo $mensagem;?>" id_proposta="<?php echo $id_proposta; ?>">&nbsp;<?php echo $mensagem;?></a>
														<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
														<input name='id_proposta' type='hidden' value='<?php echo $id_proposta;?>' />
													<?php else: ?>
															<h5><strong><?php echo lang('ERRO_FATURAR_PROPOSTA');?></strong></h5>
													<?php endif; ?>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-6 col-md-12">
														<?php if (!$infoProposta->nota_produto>0): ?>
															<a href="javascript:void(0);" class="btn btn-danger reprovarProposta" id="<?php echo $id_proposta; ?>" acao="reprovarPropostaComercial" title="<?php echo lang('FATURAR_PROPOSTA_ERRO_COMERCIAL');?>">&nbsp;<?php echo lang('FATURAR_PROPOSTA_ERRO_COMERCIAL');?></a>
														<?php endif; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm("processarFaturamentoPropostaFinal");?>
							<!-- FINAL FORM-->
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
<div class='imagem-fundo'>
	<img src='assets/img/logo.png' border='0'>
</div>
<?php break;?>
<?php endswitch;?>