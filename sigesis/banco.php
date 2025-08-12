<?php
  /**
   * Banco
   *
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe nao e permitido.');
  if (!$usuario->is_Administrativo())
	  redirect_to("login.php");
?>
<?php switch(Filter::$acao): case "editar": ?>
<?php $row = Core::getRowById("banco", Filter::$id);?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('BANCO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EDITAR');?></small></h1>
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
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class='fa fa-edit font-<?php echo $core->primeira_cor;?>'>&nbsp;&nbsp;</i><?php echo lang('EDITAR');?>
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
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMPRESA'); ?></label>
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
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BANCO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="banco" value="<?php echo $row->banco;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CODIGO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="codigo" value="<?php echo $row->codigo;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('AGENCIA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="agencia" value="<?php echo $row->agencia;?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CONTA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="conta" value="<?php echo $row->conta;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('OPERACAO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="operacao" value="<?php echo $row->operacao;?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('SALDO_INICIAL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moedap" name="saldo" value="<?php echo moedap($row->saldo);?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TAXA_BOLETO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moedap" name="taxa_boleto" value="<?php echo moedap($row->taxa_boleto);?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CODIGO_CONTABIL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="contabil" value="<?php echo $row->contabil;?>">
														</div>
													</div>
												</div>							
											</div>
											<!--/col-md-6-->
										</div>
									</div>
								</div>
								<input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm("processarBanco");?>	
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
<?php case "adicionar": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('BANCO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ADICIONAR');?></small></h1>
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
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class='fa fa-plus-square font-<?php echo $core->primeira_cor;?>'>&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?>
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
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMPRESA'); ?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_empresa" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value=""></option>
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
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BANCO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="banco">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CODIGO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="codigo">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('AGENCIA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="agencia">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CONTA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="conta">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('OPERACAO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="operacao">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('SALDO_INICIAL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moedap" name="saldo">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TAXA_BOLETO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moedap" name="taxa_boleto">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CODIGO_CONTABIL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="contabil">
														</div>
													</div>
												</div>						
											</div>
											<!--/col-md-6-->
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm("processarBanco");?>	
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
<?php case "listar": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('BANCO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('LISTAR');?></small></h1>
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
								<i class='fa fa-bank font-<?php echo $core->primeira_cor;?>'>&nbsp;&nbsp;</i><?php echo lang('LISTAR');?>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=banco&acao=transferencia" class="btn btn-sm blue"><i class="fa fa-exchange">&nbsp;&nbsp;</i><?php echo lang('BANCO_TRANSFERENCIA');?></a>
								<a href="index.php?do=banco&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable">
								<thead>
									<tr>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('CONTA');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('TAXA_BOLETO');?></th>
										<th><?php echo lang('SALDO_ATUAL');?></th>
										<th><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $faturamento->getBancos();
										if($retorno_row):
										foreach ($retorno_row as $exrow):
										$saldo = $faturamento->getSaldoTotal(false, $exrow->id);
								?>
									<tr>
										<td><?php echo $exrow->banco;?></td>
										<td><?php echo $exrow->conta;?></td>
										<td><?php echo $exrow->nome;?></td>
										<td><?php echo moedap($exrow->taxa_boleto);?></td>
										<td><strong <?php echo ($saldo < 0) ? 'class="font-red"' : 'class="font-green"'?>><?php echo moedap($saldo);?></strong></td>
										<td>
											<a href="index.php?do=banco&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->banco;?>"><i class="fa fa-pencil"></i></a>
											<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarBanco" title="<?php echo lang('BANCO_APAGAR').$exrow->banco;?>"><i class="fa fa-times"></i></a>
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
<?php case "transferencia": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('BANCO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('BANCO_TRANSFERENCIA');?></small></h1>
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
								<i class='fa fa-exchange font-<?php echo $core->primeira_cor;?>'>&nbsp;&nbsp;</i><?php echo lang('BANCO_TRANSFERENCIA');?>
							</div>
						</div>
						<div class='portlet-body form'>
							<!-- INICIO FORM-->
							<form action='' autocomplete="off" method='post' class='form-horizontal' name='admin_form' id='admin_form'>
								<div class='form-body'>
									<div class='row'>
										<div class='col-md-12'>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('BANCO_TRANSFERIR_DEBIDO');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_banco_origem' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
														<label class='control-label col-md-3'><?php echo lang('BANCO_TRANSFERIR_CREDITO');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_banco_destino' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
														<label class='control-label col-md-3'><?php echo lang('VALOR');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control moedap' name='valor'>
														</div>
													</div>
												</div>										
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DATA');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data'>
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
														<button type='submit' class='btn <?php echo $core->primeira_cor;?>'><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm('processarTranferenciaBanco');?>	
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
<?php case "extrato": ?>
<?php 
	$mes_ano = (get('mes_ano')) ? get('mes_ano') : date("m/Y"); 
	$id_banco = (get('id_banco')) ? get('id_banco') : -1; 
?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#mes_ano').change(function() {
			var mes_ano = $("#mes_ano").val();
			var id_banco = $("#id_banco").val();
			window.location.href = 'index.php?do=banco&acao=extrato&mes_ano='+mes_ano+'&id_banco='+id_banco;
		});
	});
	// ]]>
</script>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#id_banco').change(function() {
			var mes_ano = $("#mes_ano").val();
			var id_banco = $("#id_banco").val();
			window.location.href = 'index.php?do=banco&acao=extrato&mes_ano='+mes_ano+'&id_banco='+id_banco;
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
				<h1><?php echo lang('BANCO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EXTRATO');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('EXTRATO')." de ".exibeMesAno($mes_ano, true, true);?></span>
							</div>
							<div class="actions btn-set">
								<a href="javascript:void(0);" class="btn btn-sm <?php echo $core->primeira_cor;?>" onclick="javascript:void window.open('ver_extrato.php?mes_ano=<?php echo $mes_ano;?>&id_banco=<?php echo $id_banco;?>','<?php echo lang('EXTRATO');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="mes_ano" id="mes_ano" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
										<option value=""></option>
										<?php 
											$retorno_row = $gestao->getListaMes("despesa", "data_vencimento", false, false, "DESC");
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
							<table class="table table-bordered table-striped table-condensed table-advance">
								<thead>
									<tr>
										<th><?php echo lang('TIPO');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('PLANO_CONTAS');?></th>
										<th width="100px"><?php echo lang('VALOR');?></th>
										<th><?php echo lang('DATA_VENCIMENTO');?></th>
										<th><?php echo lang('DATA_PAGAMENTO');?></th>
										<th><?php echo lang('VALIDADO');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $faturamento->getExtrato_view($mes_ano, $id_banco);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											$descricao = '';
											if($exrow->tipo == 'D') {
												if($exrow->ti_ch == 1) {
													$descricao .= 'CHEQUE - ';												
												}
												$descricao .= $exrow->descricao;
											} elseif($exrow->tipo == 'C' AND $exrow->ti_ch > 0) {
												$descricao .= '['.$exrow->descricao.'] - '.$exrow->pagamento.' - '.$exrow->cliente;
											}
								?>
									<tr>
										<td><?php echo $exrow->tipo;?></td>
										<td><?php echo $descricao;?></td>
										<td><?php echo $exrow->conta;?></td>
										<td><strong <?php echo ($exrow->tipo == 'D') ? 'class="font-red"' : 'class="font-green"'?>><?php echo moedap($exrow->valor);?></strong></td>	
										<td><?php echo exibedata($exrow->data_vencimento);?></td>
										<td><?php echo exibedata($exrow->data_pagamento);?></td>
										<td><?php echo ($exrow->pago) ? lang('SIM') : lang('NAO');?></td>
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
<?php default: ?>
<div class="imagem-fundo">
	<img src="assets/img/logo.png" border="0">
</div>
<?php break;?>
<?php endswitch;?>