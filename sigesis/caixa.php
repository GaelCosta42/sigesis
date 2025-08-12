<?php
  /**
   * Caixa
   *
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Todos())
	  redirect_to("login.php");
  $data_caixa = (get('data_caixa')) ? get('data_caixa') : date("d/m/Y");
?>
<?php switch(Filter::$acao): case "retirar": ?>
<?php
	$id_caixa = $faturamento->verificaCaixa($usuario->uid);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('CAIXA_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('CAIXA_RETIRAR');?></small></h1>
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
								<i class='fa fa-minus-square'>&nbsp;&nbsp;</i><?php echo lang('CAIXA_RETIRAR');?>
							</div>
						</div>
						<?php if($id_caixa < 1): ?>
						<div class='portlet-body form'>
							<form autocomplete="off" action='' class='form-horizontal'>
								<div class='form-body'>
									<div class="alert alert-danger">
										<ul class="fa-ul">
											<li>
												<i class="fa fa-warning fa-lg fa-li"></i>
												<?php echo lang('CAIXA_VENDA_ERRO');?>
											</li>
										</ul>
									</div>
								</div>
								<div class='form-actions'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='col-md-6'>
												<div class='row'>
													<div class='col-md-offset-3 col-md-9'>
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
						</div>
						<?php else: ?>
						<div class='portlet-body form'>
							<!-- INICIO FORM-->
							<form action='' autocomplete="off" method='post' class='form-horizontal' name='admin_form' id='admin_form'>
								<div class='form-body'>
									<div class='row'>
										<div class='col-md-12'>
											<!--col-md-6-->
											<div class='col-md-6'>
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
														<label class='control-label col-md-3'><?php echo lang('FORNECEDOR');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_cadastro' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<option value=""><?php echo lang('RETIRADA_SANGRIA');?></option>
																<?php
																	$retorno_row = $cadastro->getCadastros('FORNECEDOR');
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>'><?php echo $srow->nome;?></option>
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
														<label class="control-label col-md-3"></label>
														<div class="col-md-9">
															<div class="md-checkbox-list">
																<div class="md-checkbox">
																	<input type="checkbox" class="md-check" name="sangria" id="sangria" value="1">
																	<label for="sangria">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('RETIRADA_SANGRIA');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('BANCO_TRANSFERENCIA_SANGRIA');?></label>
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
														<label class='control-label col-md-3'><?php echo lang('BANCO_TRANSFERENCIA_SANGRIA_DATA');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_transferencia'>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class='col-md-6'>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('CATEGORIA');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_pai' id='id_pai_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
														<label class='control-label col-md-3'><?php echo lang('VALOR_PAGO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control moedap' name='valor'>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
									</div>
								</div>
								<input name="id_caixa" type="hidden" value="<?php echo $id_caixa;?>" />
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
							<?php echo $core->doForm('processarRetirarCaixa');?>
							<!-- FINAL FORM-->
						</div>
						<?php endif; ?>
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
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#buscar').click(function() {
			var data_caixa = $("#data_caixa").val();
			window.location.href = 'index.php?do=caixa&acao=listar&data_caixa='+ data_caixa;
		});

		$('#buscarcaixa').click(function() {
			var id_caixa = $('#id_caixa').val();
			window.open('pdf_caixa.php?id_caixa='+id_caixa,'Imprimir caixa','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
	});
	// ]]>
</script>

<div id="novo-caixa" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-money">&nbsp;&nbsp;</i><?php echo lang('CAIXA_ABRIR');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="caixa_form" id="caixa_form" >
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p><?php echo lang('CAIXA_INICIAL');?></p>
							<p>
								<input type="text" class="form-control moedap" name="valor">
							</p>
							<p><?php echo lang('BANCO');?></p>
							<p>
								<select class="select2me form-control" name="id_banco" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
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
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-submit btn-info"><?php echo lang('CAIXA_ABRIR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("abrirCaixa", "caixa_form");?>
</div>

<div id="validar-caixa" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-money">&nbsp;&nbsp;</i><?php echo lang('CAIXA_VALIDAR');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="validar_form" id="validar_form" >
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p><?php echo lang('CAIXA_DINHEIRO');?></p>
							<p>
								<input readonly type="text" class="form-control" id="valor_dinheiro">
							</p>
							<p><?php echo lang('BANCO_TRANSFERIR_CREDITO');?></p>
							<p>
								<select class="select2me form-control" name="id_banco" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
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
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-submit btn-success"><?php echo lang('CAIXA_VALIDAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("validarCaixa", "validar_form");?>
</div>

<div id="adicionar-valor-caixa" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-dollar">&nbsp;&nbsp;</i><?php echo lang('CAIXA_ADICIONAR_TITULO');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="adicionar_valor_caixa_form" id="adicionar_valor_caixa_form" >
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p><?php echo lang('CAIXA_ADICIONAR_VALOR');?></p>
							<p>
								<input type="text" class="form-control moedap" name="valor">
							</p>
							<p><?php echo lang('BANCO');?></p>
							<p>
								<select class="select2me form-control" name="id_banco" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
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
							</p>
							<p><?php echo lang('TIPO_PAGAMENTO');?></p>
							<p>
								<select class="select2me form-control" name="tipo_pagamento" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
										$retorno_row = $faturamento->ObterPagamentosPorCategoria(1);
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
							</p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-submit btn-success"><?php echo lang('CAIXA_ADICIONAR_TITULO');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("adicionarValorAoCaixa", "adicionar_valor_caixa_form");?>
</div>

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CAIXA_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CAIXA_LISTAR');?></small></h1>
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
								<i class="fa fa-inbox font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CAIXA_LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="#novo-caixa" class="btn btn-sm btn-info" data-toggle="modal"><i class="fa fa-unlock">&nbsp;&nbsp;</i><?php echo lang('CAIXA_ABRIR');?></a>
								<a href="#adicionar-valor-caixa" class="btn btn-sm btn-success" data-toggle="modal"><i class="fa fa-dollar">&nbsp;&nbsp;</i><?php echo lang('CAIXA_ADICIONAR');?></a>
								<a href="index.php?do=caixa&acao=retirar" class="btn btn-sm btn-danger"><i class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('CAIXA_RETIRAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete="off" class="form-inline">
								<div class="form-group">
									<input type="text" class="form-control input-medium calendario data" name="data_caixa" id="data_caixa" value="<?php echo $data_caixa;?>" >
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR_DIA');?></button>
									&nbsp;&nbsp;
									&nbsp;&nbsp;
									&nbsp;&nbsp;
									<input type="text" class="form-control input-small" name="id_caixa" id="id_caixa" >
									&nbsp;&nbsp;
									<button type="button" id="buscarcaixa" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR_CAIXA');?></button>
								</div>
							</form>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('USUARIO');?></th>
										<th><?php echo lang('CAIXA_TOTAL');?></th>
										<th><?php echo lang('CAIXA_VALOR');?></th>
										<th><?php echo lang('CAIXA_RETIRADO');?></th>
										<th><?php echo lang('CAIXA_DINHEIRO');?></th>
										<th><?php echo lang('STATUS');?></th>
										<th><?php echo lang('DATA_STATUS');?></th>
                                        <th width="320px"><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $faturamento->getCaixas($data_caixa, $usuario->is_Gerencia(), $usuario->uid);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											$valor_caixa = $faturamento->getCaixaValor($exrow->id);
											$valor_dinheiro = $faturamento->getCaixaDinheiro($exrow->id);
											$valor_cheque = $faturamento->getCaixaCheque($exrow->id);
											$valor_retirada = $despesa->getCaixaRetirada($exrow->id);
											$valor_total = $valor_dinheiro - $valor_retirada;
								?>
									<tr>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo $exrow->nome;?></td>
										<td><?php echo moedap($valor_caixa);?></td>
										<td><?php echo moedap($valor_dinheiro);?></td>
										<td><?php echo moedap($valor_retirada);?></td>
										<td><?php echo moedap($valor_total);?></td>
										<td><?php echo statusCaixa($exrow->status);?></td>
										<?php if($exrow->status == '1'): ?>
										<td><?php echo exibedataHora($exrow->data_abrir);?></td>
										<?php elseif($exrow->status == '2'): ?>
										<td><?php echo exibedataHora($exrow->data_fechar);?></td>
										<?php elseif($exrow->status == '3'): ?>
										<td><?php echo exibedataHora($exrow->data_validar);?></td>
										<?php endif; ?>
										<td width="150px">
											<a href="javascript:void(0);" class="btn btn-xs grey-cascade" onclick="javascript:void window.open('ver_caixa.php?id_caixa=<?php echo $exrow->id;?>','<?php echo $exrow->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>"><i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('VISUALIZAR');?></a>
											<a href="javascript:void(0);" class="btn btn-xs yellow-casablanca" onclick="javascript:void window.open('pdf_caixa.php?id_caixa=<?php echo $exrow->id;?>','<?php echo $exrow->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR');?>"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR');?></a>
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
<?php case "adicionar": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CAIXA_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CAIXA_ABRIR');?></small></h1>
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
								<i class="fa fa-inbox font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CAIXA_ABRIR');?></span>
							</div>
						</div>
						<div class='portlet-body form'>
							<!-- INICIO FORM-->
							<form action='' autocomplete="off" method='post' class='form-horizontal' name='admin_form' id='admin_form'>
								<div class='form-body'>
									<div class='row'>
										<div class='col-md-12'>
											<!--col-md-6-->
											<div class='col-md-12'>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-4'><?php echo lang('CAIXA_INICIAL');?></label>
														<div class='col-md-8'>
															<input type="text" class="form-control moedap" name="valor">
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-4'><?php echo lang('BANCO');?></label>
														<div class='col-md-8'>
															<select class="select2me form-control" name="id_banco" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
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
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class='form-actions'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='col-md-6'>
												<div class='row'>
													<div class='col-md-offset-3 col-md-9'>
														<button type='submit' class='btn <?php echo $core->primeira_cor;?>'><?php echo lang('CAIXA_ABRIR');?></button>
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
						</div>
						<?php echo $core->doForm("abrirCaixa");?>
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
<?php case "aberto":
$data_ini_caixa = (get('data_ini')) ? get('data_ini') : date("d/m/Y");
$data_fim_caixa = (get('data_fim')) ? get('data_fim') : date("d/m/Y");
$caixas_aberto = $faturamento->getQuantidadeCaixasAberto();
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#buscar').click(function() {
			var data_ini_caixa = $("#data_ini_caixa").val();
			var data_fim_caixa = $("#data_fim_caixa").val();
			window.location.href = 'index.php?do=caixa&acao=aberto&data_ini='+data_ini_caixa+'&data_fim='+data_fim_caixa;
		});
	});
	// ]]>
</script>
<div id="validar-caixa" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-money">&nbsp;&nbsp;</i><?php echo lang('CAIXA_VALIDAR');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="validar_form" id="validar_form" >
				<div class="modal-body">
					<div class="row">
						<?php
						$pagamento_row = $faturamento->obterListaPagamentosCategoriaDinheiro(1);
						if (!$pagamento_row):
						?>
							<div class="note note-warning">
								<h4 class="block">ATENÇÃO!</h4>
								<p>
									Você precisa cadastrar um tipo de pagamento DINHEIRO para validar o caixa.
								</p>
							</div>
						<?php else: ?>
						<div class="col-md-12">
							<p><?php echo lang('CAIXA_DINHEIRO');?></p>
							<p>
								<input type="text" class="form-control moeda" id="valor_dinheiro" name="valor_dinheiro">
							</p>
							<p><?php echo lang('TIPO_PAGAMENTO_TRANSFERIR');?></p>
							<p>
								<select class="select2me form-control" name="id_tipopagamento" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<?php
										if ($pagamento_row):
											foreach ($pagamento_row as $trow):
									?>
												<option value="<?php echo $trow->id;?>"><?php echo $trow->tipo;?></option>
									<?php
											endforeach;
											unset($trow);
										endif;
									?>
								</select>
							</p>
 							<p><?php echo lang('BANCO_TRANSFERIR_CREDITO');?></p>
							<p>
								<select class="select2me form-control" name="id_banco" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
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
							</p>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="modal-footer">
					<?php if ($pagamento_row): ?>
						<button type="button" class="btn btn-submit btn-success"><?php echo lang('CAIXA_VALIDAR');?></button>
					<?php else: ?>
						<a href="index.php?do=tipopagamento&acao=adicionar"
							class="btn btn-warning"><?php echo lang('CADASTRAR_TIPO_PAGAMENTO'); ?></a>
					<?php endif; ?>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("validarCaixa", "validar_form");?>
</div>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CAIXA_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CAIXA_EMABERTO');?></small></h1>
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
								<i class="fa fa-exclamation-triangle font-<?php echo $core->primeira_cor;?>"></i><span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CAIXA_EMABERTO');?></span>
							</div>
						</div>

						<div class="alert alert-warning">
							<strong><?php echo lang('ATENCAO'); ?></strong> Existem <b><?php echo $caixas_aberto; ?></b> caixas abertos no sistema.
						</div>

						<div class="portlet-body">
							<form autocomplete="off" class="form-inline">
								<div class="form-group">
									<?php echo lang('DE'); ?>
									<input type="text" class="form-control input-medium calendario data" name="data_ini_caixa" id="data_ini_caixa" value="<?php echo $data_ini_caixa;?>" >
									&nbsp;&nbsp;
									<?php echo lang('ATE'); ?>
									<input type="text" class="form-control input-medium calendario data" name="data_fim_caixa" id="data_fim_caixa" value="<?php echo $data_fim_caixa;?>" >
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR_DIA');?></button>
								</div>
							</form>
						</div>
						<br>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('USUARIO');?></th>
										<th><?php echo lang('CAIXA_TOTAL');?></th>
										<th><?php echo lang('CAIXA_VALOR');?></th>
										<th><?php echo lang('CAIXA_RETIRADO');?></th>
										<th><?php echo lang('CAIXA_DINHEIRO');?></th>
										<th><?php echo lang('STATUS');?></th>
										<th><?php echo lang('DATA_STATUS');?></th>
                                        <th width="320px"><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $faturamento->getCaixasAbertoData($usuario->is_Gerencia(), $usuario->uid,$data_ini_caixa,$data_fim_caixa);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											$valor_caixa = $faturamento->getCaixaValor($exrow->id);
											$valor_dinheiro = $faturamento->getCaixaDinheiro($exrow->id);
											$valor_cheque = $faturamento->getCaixaCheque($exrow->id);
											$valor_retirada = $despesa->getCaixaRetirada($exrow->id);
											$valor_total = $valor_dinheiro - $valor_retirada;
								?>
									<tr>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo $exrow->nome;?></td>
										<td><?php echo moedap($valor_caixa);?></td>
										<td><?php echo moedap($valor_dinheiro);?></td>
										<td><?php echo moedap($valor_retirada);?></td>
										<td><?php echo moedap($valor_total);?></td>
										<td><?php echo statusCaixa($exrow->status);?></td>
										<?php if($exrow->status == '1'): ?>
										<td><?php echo exibedataHora($exrow->data_abrir);?></td>
										<?php elseif($exrow->status == '2'): ?>
										<td><?php echo exibedataHora($exrow->data_fechar);?></td>
										<?php elseif($exrow->status == '3'): ?>
										<td><?php echo exibedataHora($exrow->data_validar);?></td>
										<?php endif; ?>
										<td width="320px">
											<a href="javascript:void(0);" class="btn btn-xs grey-cascade" onclick="javascript:void window.open('ver_caixa.php?id_caixa=<?php echo $exrow->id;?>','<?php echo $exrow->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>"><i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('VISUALIZAR');?></a>
											<a href="javascript:void(0);" class="btn btn-xs yellow-casablanca" onclick="javascript:void window.open('pdf_caixa.php?id_caixa=<?php echo $exrow->id;?>','<?php echo $exrow->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR');?>"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR');?></a>
											<?php if($exrow->status == '1' and (($usuario->uid == $exrow->id_abrir) or $usuario->is_Master())): ?>
												<a href="javascript:void(0);" class="btn btn-xs btn-warning fecharcaixa" id="<?php echo $exrow->id;?>" title="<?php echo lang('CAIXA_FECHAR').": ".$exrow->nome;?>"><i class="fa fa-lock">&nbsp;&nbsp;</i><?php echo lang('CAIXA_FECHAR');?></a>
											<?php elseif($exrow->status == '2' and $usuario->is_Gerencia()): ?>
												<a href="javascript:void(0);"
												class="btn btn-xs btn-success validarcaixa"
												valor_dinheiro="<?php echo moedap($valor_total);?>"
												valor_cheque="<?php echo moedap($valor_cheque);?>"
												id="<?php echo $exrow->id;?>"
												title="<?php echo lang('CAIXA_VALIDAR').": ".$exrow->nome;?>">
												<i class="fa fa-check">&nbsp;&nbsp;</i><?php echo lang('CAIXA_VALIDAR');?></a>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
								<?php unset($exrow);
									  else:
								?>
										<tr><td colspan="9"><?php echo lang('CAIXA_ABERTO_NAO_ENCONTRADO'); ?></td></tr>
								<?php
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
<?php case 'listarretiradas':

$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');

?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#buscar').click(function() {
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			window.location.href = 'index.php?do=caixa&acao=listarretiradas&dataini='+ dataini +'&datafim='+ datafim;
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
				<h1><?php echo lang('CAIXA_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('CAIXA_LISTARRETIRADAS');?></small></h1>
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
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('CAIXA_LISTARRETIRADAS');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete="off" class="form-inline">
								<div class="form-group">
									<input type="text" class="form-control input-medium calendario data" name="dataini" id="dataini" value="<?php echo $dataini;?>" >
									<input type="text" class="form-control input-medium calendario data" name="datafim" id="datafim" value="<?php echo $datafim;?>" >
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
									<a href="javascript:void(0);" class="btn <?php echo $core->primeira_cor;?>" onclick="javascript:void window.open('ver_retiradas.php?dataini=<?php echo $dataini;?>&datafim=<?php echo $datafim;?>','<?php echo lang('IMPRIMIR');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR');?></a>
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable-desc'>
								<thead class='flip-content'>
									<tr>
										<th>#</th>
										<th><?php echo lang('DATA_PAGAMENTO');?></th>
										<th><?php echo lang('CAIXA');?></th>
										<th><?php echo lang('FORNECEDOR');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('PLANO_CONTAS');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
										<th><?php echo lang('RECIBO');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$total = 0;
										$retorno_row = $despesa->getDespesasCaixa($dataini, $datafim, $usuario->is_Gerencia());
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$total += $exrow->valor;
								?>
											<tr>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo $exrow->id_caixa;?></td>
												<td><?php echo $exrow->fornecedor;?></td>
												<td><?php echo $exrow->descricao;?></td>
												<td><?php echo $exrow->conta;?></td>
												<td><?php echo moedap($exrow->valor);?></td>
												<td>
													<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_despesa.php?id_despesa=<?php echo $exrow->id;?>','<?php echo lang('IMPRIMIR_RECIBO').$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-xs yellow-casablanca"><i class="fa fa-file-o">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_RECIBO');?></a>
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
										<td colspan="6"><strong><?php echo lang('TOTAL');?></strong></td>
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
<?php break;?>
<?php case 'pagamentos':
$id_caixa = get('id_caixa');
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('a.alterarpagamento').click(function(){
			var id = $(this).attr('id');
			var tipo = $(this).attr('tipo');
			$("#tipopagamento").val(tipo).change();
			$("#pagamento_form").append('<input name="id" type="hidden" value="'+ id +'" />');
			$("#alterar-pagamento").modal('show');
			return false;
		});
	});
	// ]]>
</script>
<div id="alterar-pagamento" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('CAIXA_MOVIMENTO');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="pagamento_form" id="pagamento_form" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('PAGAMENTO');?></label>
									<div class="col-md-9">
										<select class="select2me form-control" id="tipopagamento" name="tipopagamento" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
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
						</div>
					</div>
				</div>
				<input name="id_caixa" type="hidden" value="<?php echo $id_caixa;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarAlterarCaixa", "pagamento_form");?>
</div>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('CAIXA_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('CAIXA_MOVIMENTO');?></small></h1>
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
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('CAIXA_MOVIMENTO');?></span>
							</div>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance'>
								<thead class='flip-content'>
									<tr>
										<th><?php echo lang('CODIGO_VENDA');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('PAGAMENTO_FORMA');?></th>
										<th><?php echo lang('DETALHES');?></th>
										<th><?php echo lang('VALOR_VENDA');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
										<th><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
									$retorno_row = $faturamento->getMovimentoCaixa($id_caixa);
									if($retorno_row):
										foreach ($retorno_row as $exrow):
											$detalhes = '';
											if($exrow->tipo == 1) {
												$detalhes = '-';
											}elseif($exrow->tipo == 2) {
												$detalhes = 'NUMERO: ['.$exrow->numero_cheque.'] - '.$exrow->banco_cheque;
											}elseif($exrow->tipo == 3) {
												$detalhes = $exrow->banco;
											}else {
												$detalhes = 'PARCELA: ['.$exrow->parcelas_cartao.'] - '.$exrow->numero_cartao;
											}
								?>
											<tr <?php echo ($exrow->inativo) ? "class='font-red'" : "";?>>
											<?php if($exrow->id_venda):?>
												<td><a href="javascript:void(0);" onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id_venda;?>','<?php echo "CODIGO: ".$exrow->id_venda;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VER_DETALHES');?>"><?php echo $exrow->id_venda;?></a></td>
												<td><?php echo $exrow->cadastro;?></td>
											<?php else:?>
												<td colspan="2"><?php echo 'ABERTURA DE CAIXA';?></td>
											<?php endif;?>
												<td><?php echo $exrow->pagamento;?></td>
												<td><?php echo $detalhes;?></td>
												<td><?php echo moedap($exrow->valor_total_venda);?></td>
												<td><span class="bold theme-font"><?php echo ($exrow->inativo) ? "-" : moedap($exrow->valor_pago);?></span></td>
												<td>
													<a href="javascript:void(0);" class="btn btn-xs blue alterarpagamento" id="<?php echo $exrow->id;?>" tipo="<?php echo $exrow->tipo;?>" title="<?php echo lang('EDITAR').": ".$exrow->id;?>"><i class="fa fa-pencil">&nbsp;&nbsp;</i><?php echo lang('EDITAR');?></a>
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
<?php default: ?>
<div class="page-container">
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<div class="imagem-fundo">
						<img src="assets/img/bg-white.png" border="0">
					</div>
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php endswitch;?>