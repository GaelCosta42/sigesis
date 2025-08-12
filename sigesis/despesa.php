<?php
  /**
   * Financeiro
   *
   */
  if (!defined('_VALID_PHP'))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Administrativo())
	  redirect_to('login.php');
  
  $datafiltro = get('datafiltro');
?>
<?php switch(Filter::$acao): case 'editar': ?>
<?php $row = Core::getRowById('despesa', Filter::$id);
	  $id_pai = getValue("id_pai", "conta", "id = ".$row->id_conta);
?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_DESPESASEDITAR');?></small></h1>
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
								<i class='fa fa-minus'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_DESPESASEDITAR');?>
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
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DESCRICAO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='descricao' value='<?php echo $row->descricao;?>'>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMPRESA'); ?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_empresa" data-placeholder="<?php echo lang('SELECIONE_EMPRESA');?>" >
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
														<label class='control-label col-md-3'><?php echo lang('FORNECEDOR');?></label>
														<div class='col-md-9'>
															<input name="id_cadastro" id="id_cadastro" type="hidden" value="<?php echo $row->id_cadastro;?>"/>
															<input type="text" autocomplete="off" class="form-control caps listar_cadastro" name="cadastro" placeholder="<?php echo lang('BUSCAR');?>" value="<?php echo getValue('nome', 'cadastro', 'id='.$row->id_cadastro);?>">
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
														<label class='control-label col-md-3'><?php echo lang('CENTRO_CUSTO'); ?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_custo' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $despesa->getCentroCusto();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($srow->id == $row->id_custo) echo 'selected="selected"';?>><?php echo $srow->centro_custo;?></option>
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
														<label class='control-label col-md-3'><?php echo lang('CATEGORIA'); ?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_pai' id='id_pai_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getPai('"D"');
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
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('PLANO_CONTAS');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getFilho($id_pai);
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
														<label class='control-label col-md-3'><?php echo lang('NRO_DOCUMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='nro_documento' value='<?php echo $row->nro_documento;?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DUPLICATA');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='duplicata' value='<?php echo $row->duplicata;?>'>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class='col-md-6'>
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
														<label class='control-label col-md-3'><?php echo lang('VALOR');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control moedap' name='valor' value='<?php echo moedap($row->valor);?>'>
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
														<label class='control-label col-md-3'><?php echo lang('DATA_VENCIMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_vencimento' value='<?php echo exibedata($row->data_vencimento);?>'>
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
																	<input type='checkbox' class='md-check' name='cheque' id='cheque' value='1' <?php echo getChecked($row->cheque, 1);?>>
																	<label for='cheque'>
																	<span></span>
																	<span class='check'></span>
																	<span class='box'></span>
																	<?php echo lang('FINANCEIRO_IMPORTANTE');?></label>
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
								<input name='id' type='hidden' value='<?php echo Filter::$id;?>' />
								<div class='form-actions'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='col-md-6'>
												<div class='row'>
													<div class='col-md-offset-3 col-md-9'>
														<button type='button' class='btn btn-submit <?php echo $core->primeira_cor;?>'><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm('editarDespesas');?>	
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
<?php case 'duplicardespesas': ?>
<?php $row = Core::getRowById('despesa', Filter::$id);
	  $id_pai = getValue("id_pai", "conta", "id = ".$row->id_conta);
?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_DESPESASDUPLICAR');?></small></h1>
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
								<i class='fa fa-files-o'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_DESPESASDUPLICAR');?>
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
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DESCRICAO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='descricao' value='<?php echo $row->descricao;?>'>
														</div>
													</div>
												</div>
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
														<label class='control-label col-md-3'><?php echo lang('FORNECEDOR');?></label>
														<div class='col-md-9'>
															<input name="id_cadastro" id="id_cadastro" type="hidden" value="<?php echo $row->id_cadastro;?>"/>
															<input type="text" autocomplete="off" class="form-control caps listar_cadastro" name="cadastro" placeholder="<?php echo lang('BUSCAR');?>" value="<?php echo getValue('nome', 'cadastro', 'id='.$row->id_cadastro);?>">
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
														<label class='control-label col-md-3'><?php echo lang('CENTRO_CUSTO'); ?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_custo' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $despesa->getCentroCusto();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($srow->id == $row->id_custo) echo 'selected="selected"';?>><?php echo $srow->centro_custo;?></option>
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
														<label class='control-label col-md-3'><?php echo lang('CATEGORIA');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_pai' id='id_pai_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getPai('"D"');
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
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('PLANO_CONTAS');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getFilho($id_pai);
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
														<label class='control-label col-md-3'><?php echo lang('NRO_DOCUMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='nro_documento' value='<?php echo $row->nro_documento;?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DUPLICATA');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='duplicata' value='<?php echo $row->duplicata;?>'>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class='col-md-6'>
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
														<label class='control-label col-md-3'><?php echo lang('VALOR');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control moedap' name='valor' value='<?php echo moedap($row->valor);?>'>
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
														<label class='control-label col-md-3'><?php echo lang('DATA_VENCIMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_vencimento' value='<?php echo exibedata($row->data_vencimento);?>'>
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
																	<input type='checkbox' class='md-check' name='cheque' id='cheque' value='1' <?php echo getChecked($row->cheque, 1);?>>
																	<label for='cheque'>
																	<span></span>
																	<span class='check'></span>
																	<span class='box'></span>
																	<?php echo lang('FINANCEIRO_IMPORTANTE');?></label>
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
								<input name="id_nota" type="hidden" value="<?php echo $row->id_nota;?>"/>
								<div class='form-actions'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='col-md-6'>
												<div class='row'>
													<div class='col-md-offset-3 col-md-9'>
														<button type='button' class='btn btn-submit <?php echo $core->primeira_cor;?>'><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm('duplicarDespesas');?>	
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
<?php case 'editarpagas': ?>
<?php 
	$row = Core::getRowById('despesa', Filter::$id);
	$id_pai = getValue("id_pai", "conta", "id = ".$row->id_conta);
?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_DESPESASEDITAR');?></small></h1>
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
								<i class='fa fa-minus'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_DESPESASEDITAR');?>
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
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DESCRICAO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='descricao' value='<?php echo $row->descricao;?>'>
														</div>
													</div>
												</div>
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
														<label class='control-label col-md-3'><?php echo lang('FORNECEDOR');?></label>
														<div class='col-md-9'>
															<input name="id_cadastro" id="id_cadastro" type="hidden" value="<?php echo $row->id_cadastro;?>"/>
															<input type="text" autocomplete="off" class="form-control caps listar_cadastro" name="cadastro" placeholder="<?php echo lang('BUSCAR');?>" value="<?php echo getValue('nome', 'cadastro', 'id='.$row->id_cadastro);?>">
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
														<label class='control-label col-md-3'><?php echo lang('CENTRO_CUSTO'); ?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_custo' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $despesa->getCentroCusto();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($srow->id == $row->id_custo) echo 'selected="selected"';?>><?php echo $srow->centro_custo;?></option>
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
														<label class='control-label col-md-3'><?php echo lang('CATEGORIA');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_pai' id='id_pai_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getPai('"D"');
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
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('PLANO_CONTAS');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getFilho($id_pai);
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
														<label class='control-label col-md-3'><?php echo lang('NRO_DOCUMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='nro_documento' value='<?php echo $row->nro_documento;?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DUPLICATA');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='duplicata' value='<?php echo $row->duplicata;?>'>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class='col-md-6'>
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
														<label class='control-label col-md-3'><?php echo lang('VALOR');?></label>
														<div class='col-md-9'>
															<input readonly type='text' class='form-control moedap' name='valor' value='<?php echo moedap($row->valor);?>'>
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
														<label class='control-label col-md-3'><?php echo lang('DATA_VENCIMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_vencimento' value='<?php echo exibedata($row->data_vencimento);?>'>
														</div>
													</div>
												</div>
												<div class='row'>	
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DATA_PAGAMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_pagamento' value='<?php echo exibedata($row->data_pagamento);?>'>
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
																	<input type='checkbox' class='md-check' name='pago' id='pago' value='1' <?php if($row->pago) echo 'checked="checked"';?>>
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
								<input name='id' type='hidden' value='<?php echo Filter::$id;?>' />
								<div class='form-actions'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='col-md-6'>
												<div class='row'>
													<div class='col-md-offset-3 col-md-9'>
														<button type='button' class='btn btn-submit <?php echo $core->primeira_cor;?>'><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm('editarDespesasPagas');?>	
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
<?php case 'adicionar': 
?>
<?php if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_ADICIONAR');?></small></h1>
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
								<i class='fa fa-minus'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_ADICIONAR');?>
							</div>
						</div>
						<div class='portlet-body form'>
							<!-- INICIO FORM-->
							<?php if (isset($_GET["novo"])):
									$valor = get('valor');
									$id_despesa = get('id_despesa');
									$row = Core::getRowById('despesa', $id_despesa);
									$id_pai = getValue("id_pai", "conta", "id = ".$row->id_conta);
							?>
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
															<input type='text' class='form-control caps' name='descricao' value='<?php echo $row->descricao;?>'>
														</div>
													</div>
												</div>
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
														<label class='control-label col-md-3'><?php echo lang('FORNECEDOR');?></label>
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
														<label class='control-label col-md-3'><?php echo lang('CENTRO_CUSTO'); ?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_custo' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $despesa->getCentroCusto();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($srow->id == $row->id_custo) echo 'selected="selected"';?>><?php echo $srow->centro_custo;?></option>
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
														<label class='control-label col-md-3'><?php echo lang('CATEGORIA');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_pai' id='id_pai_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getPai('"D"');
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
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('PLANO_CONTAS');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getFilho($id_pai);
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
														<label class='control-label col-md-3'><?php echo lang('NRO_DOCUMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='nro_documento' value='<?php echo $row->nro_documento;?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DUPLICATA');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='duplicata' value='<?php echo $row->duplicata;?>'>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class='col-md-6'>
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
														<label class='control-label col-md-3'><?php echo lang('VALOR');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control moedap' name='valor' value='<?php echo moedap($valor);?>'>
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
														<label class='control-label col-md-3'><?php echo lang('DATA_VENCIMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_vencimento' value='<?php echo exibedata($row->data_vencimento);?>'>
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
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'></label>
														<div class='col-md-9'>
															<div class='md-checkbox-list'>
																<div class='md-checkbox'>
																	<input type='checkbox' class='md-check' name='fiscal' id='fiscal' value='1'>
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
																	<input type='checkbox' class='md-check' name='cheque' id='cheque' value='1'>
																	<label for='cheque'>
																	<span></span>
																	<span class='check'></span>
																	<span class='box'></span>
																	<?php echo lang('FINANCEIRO_IMPORTANTE');?></label>
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
																	<input type='checkbox' class='md-check' name='pago' id='pago' value='1'>
																	<label for='pago'>
																	<span></span>
																	<span class='check'></span>
																	<span class='box'></span>
																	<?php echo lang('FINANCEIRO_PAGA');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DATA_PAGAMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_pagamento'>
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
														<button type='button' id='novo' class='btn green'><?php echo lang('SALVAR_ADICIONAR');?></button>
														<button type='button' class='btn btn-submit <?php echo $core->primeira_cor;?>'><?php echo lang('SALVAR');?></button>
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
							<?php else:?>
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
														<label class='control-label col-md-3'><?php echo lang('FORNECEDOR');?></label>
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
														<label class='control-label col-md-3'><?php echo lang('CENTRO_CUSTO'); ?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_custo' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $despesa->getCentroCusto();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>'><?php echo $srow->centro_custo;?></option>
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
														<label class='control-label col-md-3'><?php echo lang('NRO_DOCUMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='nro_documento'>
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
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class='col-md-6'>
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
														<label class='control-label col-md-3'><?php echo lang('VALOR');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control moedap' name='valor'>
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
														<label class='control-label col-md-3'><?php echo lang('DATA_VENCIMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_vencimento'>
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
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'></label>
														<div class='col-md-9'>
															<div class='md-checkbox-list'>
																<div class='md-checkbox'>
																	<input type='checkbox' class='md-check' name='fiscal' id='fiscal' value='1'>
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
																	<input type='checkbox' class='md-check' name='cheque' id='cheque' value='1'>
																	<label for='cheque'>
																	<span></span>
																	<span class='check'></span>
																	<span class='box'></span>
																	<?php echo lang('FINANCEIRO_IMPORTANTE');?></label>
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
																	<input type='checkbox' class='md-check' name='pago' id='pago' value='1'>
																	<label for='pago'>
																	<span></span>
																	<span class='check'></span>
																	<span class='box'></span>
																	<?php echo lang('FINANCEIRO_PAGA');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DATA_PAGAMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_pagamento'>
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
														<button type='button' id='novo' class='btn green'><?php echo lang('SALVAR_ADICIONAR');?></button>
														<button type='button' class='btn btn-submit <?php echo $core->primeira_cor;?>'><?php echo lang('SALVAR');?></button>
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
							<?php endif;?>	
							<?php echo $core->doForm('processarDespesas');?>	
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
<?php case 'despesas': 
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
$id_cadastro = (get('id_cadastro')) ? get('id_cadastro') : 0; 
$id_banco = (get('id_banco')) ? get('id_banco') : 0; 
$id_centro_custo = (get('id_centro')) ? get('id_centro') : 0; 
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
			var id_centro_custo = $("#id_centro_custo").val();
			var id_conta = $("#id_conta_despesa").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var valor = $("#valor").val();
			window.location.href = 'index.php?do=despesa&acao=despesas&dataini='+ dataini +'&datafim='+ datafim +'&id_cadastro='+ id_cadastro +'&id_banco='+ id_banco +'&id_centro='+ id_centro_custo +'&id_empresa='+ id_empresa +'&id_conta='+ id_conta +'&valor='+ valor;
		});
		$('#imprimir').click(function() {
			var id_empresa = $("#id_empresa").val();
			var id_cadastro = $("#id_cadastro").val();
			var id_banco = $("#id_banco_despesa").val();
			var id_centro_custo = $("#id_centro_custo").val();
			var id_conta = $("#id_conta_despesa").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var valor = $("#valor").val();
			window.open('pdf_despesas.php?dataini='+ dataini +'&datafim='+ datafim +'&id_cadastro='+ id_cadastro +'&id_banco='+ id_banco +'&id_centro='+ id_centro_custo +'&id_conta='+ id_conta +'&id_empresa='+ id_empresa +'&valor='+ valor,'Imprimir Despesas','width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
		});
	});
	// ]]>
</script>
<!-- INICIO BOX MODAL -->
<div id='pagar-despesa' class='modal fade' tabindex='-1'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
				<h4 class='modal-title'><?php echo lang('PAGAR_DESPESA');?></h4>
			</div>
			<form action='' autocomplete="off" method='post' name='despesa_form' id='despesa_form' >
				<div class='modal-body'>
					<div class='row'>
						<div class='col-md-12'>
							<p><?php echo lang('BANCO');?></p>
							<p>	
								<select class='select2me form-control' id='id_banco' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
								<input type='text' class='form-control data calendario' name='data_pagamento' value='<?php echo date("d/m/Y");?>'>
							</p>
							<p><?php echo lang('NRO_DOCUMENTO');?></p>
							<p>	
								<input type='text' class='form-control caps' id='documento' name='nro_documento'>
							</p>
							<p><?php echo lang('VALOR_PAGO');?></p>
							<p>	
								<input type='text' class='form-control moedap' name='valor_pago'>
							</p>
							<p>&nbsp;&nbsp;</p>
							<p>	
								<div class='md-checkbox-list'>
									<div class='md-checkbox'>
										<input type='checkbox' class='md-check' name='cheque' id='cheque' value='1'>
										<label for='cheque'>
										<span></span>
										<span class='check'></span>
										<span class='box'></span>
										<?php echo lang('FINANCEIRO_IMPORTANTE');?></label>
									</div>
								</div>
							</p>
						</div>
					</div>
				</div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-submit <?php echo $core->primeira_cor;?>'><?php echo lang('SALVAR');?></button>
					<button type='button' data-dismiss='modal' class='btn default'><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm('processarPagamentoDespesas', 'despesa_form');?>
</div>
<!-- FINAL BOX MODAL -->
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_DESPESASAPAGAR');?></small></h1>
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
								<i class='fa fa-minus-square-o font-<?php echo $core->primeira_cor;?>'></i>								
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('FINANCEIRO_DESPESASAPAGAR');?></span>	
							</div>
							<div class='actions btn-set'>
								<small class="font-blue"><i class='fa fa-circle'>&nbsp;&nbsp;</i><?php echo lang('FISCAL');?></small>&nbsp;&nbsp;
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
									<select class='select2me form-control input-large' name='id_centro' id='id_centro_custo' data-placeholder='<?php echo lang('SELECIONE_CENTRO_CUSTO');?>' >
										<option value=""></option>
										<?php 
												$retorno_row = $faturamento->getCentroCustoDespesa();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value='<?php echo $srow->id;?>'><?php echo $srow->centro_custo;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
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
									<select class='select2me form-control input-large' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_CATEGORIA');?>' >
										<option value="0"></option>
									</select>
									<br/>
									<br/>
									<label><?php echo lang('DATA_VENCIMENTO');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium calendario data" name="dataini" id="dataini" value="<?php echo $dataini;?>" >
									<input type="text" class="form-control input-medium calendario data" name="datafim" id="datafim" value="<?php echo $datafim;?>" >
									<br/>
									<br/>
									<label><?php echo lang('SELECIONE_VALOR');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium moedap" name="valor" id="valor" value="<?php echo $valor;?>">
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
										<th><?php echo lang('DATA');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('PAGAMENTO');?></th>
										<th><?php echo lang('FORNECEDOR');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('VALOR');?></th>
										<th style='width: 210px'><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$total = 0;
										$retorno_row = $despesa->getDespesas($id_empresa, $dataini, $datafim, $id_banco, $id_centro_custo, $id_conta, $id_cadastro, $valor);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$total += $exrow->valor;
											$estilo = '';
											
											if($exrow->cheque) {
												$estilo = "class='warning'";
											}elseif($exrow->fiscal) {
												$estilo = "class='info'";
											}
								?>
												<tr <?php echo $estilo;?>>								
													<td><?php echo $exrow->controle;?></td>					
													<td><?php echo $exrow->id;?></td>					
													<td><?php echo exibedata($exrow->data_vencimento);?></td>						
													<td><?php echo $exrow->empresa;?></td>
													<td><?php echo $exrow->tipo;?></td>
													<td><a href="index.php?do=cadastro&acao=despesas&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->cadastro;?></a></td>
													<td><?php echo $exrow->descricao;?></td>
													<td><?php echo moedap($exrow->valor);?></td>
													<td>
														<a href="javascript:void(0);" onclick="javascript:void window.open('ver_despesa.php?id_despesa=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
														<a href='javascript:void(0);' class='btn btn-sm green pagar' id='<?php echo $exrow->id;?>' id_banco='<?php echo $exrow->id_banco;?>' documento='<?php echo $exrow->nro_documento;?>' cheque='<?php echo $exrow->cheque;?>' title='<?php echo lang('PAGAR_DESPESA').$exrow->descricao;?>'><i class='fa fa-check'></i></a>
														<a href='index.php?do=despesa&acao=editar&id=<?php echo $exrow->id;?>' class='btn btn-sm blue' title='<?php echo lang('EDITAR').': '.$exrow->descricao;?>'><i class='fa fa-pencil'></i></a>
														<a href='index.php?do=despesa&acao=duplicardespesas&id=<?php echo $exrow->id;?>' class='btn btn-sm blue-chambray' title='<?php echo lang('FINANCEIRO_DESPESASDUPLICAR').': '.$exrow->descricao;?>'><i class='fa fa-files-o'></i></a>
														<a href='javascript:void(0);' class='btn btn-sm red apagar' id='<?php echo $exrow->id;?>' acao='apagarDespesas' title='<?php echo lang('FINANCEIRO_DESPESAS_APAGAR').$exrow->descricao;?>'><i class='fa fa-times'></i></a>
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
										<td colspan="7"><strong><?php echo lang('TOTAL');?></strong></td>
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
<?php case 'despesaspagas': 
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
$id_cadastro = (get('id_cadastro')) ? get('id_cadastro') : 0; 
$id_banco = (get('id_banco')) ? get('id_banco') : 0; 
$id_centro_custo = (get('id_centro')) ? get('id_centro') : 0; 
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
			var id_centro_custo = $("#id_centro_custo").val();
			var id_conta = $("#id_conta_despesa").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var valor = $("#valor").val();
			window.location.href = 'index.php?do=despesa&acao=despesaspagas&dataini='+ dataini +'&datafim='+ datafim +'&id_cadastro='+ id_cadastro +'&id_banco='+ id_banco +'&id_centro='+ id_centro_custo +'&id_empresa='+ id_empresa +'&id_conta='+ id_conta +'&valor='+ valor;
		});
		$('#imprimir').click(function() {
			var id_empresa = $("#id_empresa").val();
			var id_cadastro = $("#id_cadastro").val();
			var id_banco = $("#id_banco_despesa").val();
			var id_centro_custo = $("#id_centro_custo").val();
			var id_conta = $("#id_conta_despesa").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			var valor = $("#valor").val();
			window.open('pdf_despesaspagas.php?dataini='+ dataini +'&datafim='+ datafim +'&id_cadastro='+ id_cadastro +'&id_banco='+ id_banco +'&id_centro='+ id_centro_custo +'&id_conta='+ id_conta +'&id_empresa='+ id_empresa +'&valor='+ valor,'Imprimir Despesas','width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
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
									<select class='select2me form-control input-large' name='id_centro' id='id_centro_custo' data-placeholder='<?php echo lang('SELECIONE_CENTRO_CUSTO');?>' >
										<option value=""></option>
										<?php 
												$retorno_row = $faturamento->getCentroCustoDespesa();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value='<?php echo $srow->id;?>'><?php echo $srow->centro_custo;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
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
									<select class='select2me form-control input-large' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_CATEGORIA');?>' >
										<option value=""></option>
										<?php 
											$retorno_row = $faturamento->getFilho($id_pai);
											if ($retorno_row):
												foreach ($retorno_row as $srow):
										?>
													<option value='<?php echo $srow->id_filho;?>'><?php echo $srow->filho;?></option>
										<?php
												endforeach;
												unset($srow);
											endif;
										?>
									</select>
									<br/>
									<br/>
									<label><?php echo lang('DATA_PAGAMENTO');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium calendario data" name="dataini" id="dataini" value="<?php echo $dataini;?>" >
									<input type="text" class="form-control input-medium calendario data" name="datafim" id="datafim" value="<?php echo $datafim;?>" >
									<br/>
									<br/>
									<label><?php echo lang('SELECIONE_VALOR');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium moedap" name="valor" id="valor" value="<?php echo $valor;?>">
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
										<th style="width: 200px"><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$total = 0;
										$pago = 0;
										$totaljuros = 0;
										$retorno_row = $despesa->getDespesasPagas($id_empresa, $dataini, $datafim, $id_banco, $id_centro_custo, $id_conta, $id_cadastro, $valor);
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
												<td><?php echo moedap($exrow->valor);?></td>
												<td <?php echo $juros;?>><?php echo moedap($exrow->valor_pago);?></td>
												<td>
													<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_despesa.php?id_despesa=<?php echo $exrow->id;?>','<?php echo lang('IMPRIMIR_RECIBO').$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a>
													<a href="javascript:void(0);" onclick="javascript:void window.open('ver_despesa.php?id_despesa=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
													<?php if (!$exrow->id_receita): ?>
														<a href='index.php?do=despesa&acao=duplicardespesas&id=<?php echo $exrow->id;?>' class='btn btn-sm blue-chambray' title='<?php echo lang('FINANCEIRO_DESPESASDUPLICAR').': '.$exrow->descricao;?>'><i class='fa fa-files-o'></i></a>
													<?php endif; ?>
													<?php if($usuario->is_Master()): ?>
														<?php if (!$exrow->id_receita): ?>
															<a href='index.php?do=despesa&acao=editarpagas&id=<?php echo $exrow->id;?>' class='btn btn-sm blue' title='<?php echo lang('EDITAR').': '.$exrow->descricao;?>'><i class='fa fa-pencil'></i></a>
															<a href='javascript:void(0);' class='btn btn-sm red apagar' id='<?php echo $exrow->id;?>' acao='apagarDespesas' title='<?php echo lang('FINANCEIRO_DESPESAS_APAGAR').$exrow->descricao;?>'><i class='fa fa-times'></i></a>
														<?php else: ?>
															<a href='javascript:void(0);' class='btn btn-sm red apagarTransferenciaBancos' id_outro='<?php echo $exrow->id_receita; ?>' id='<?php echo $exrow->id;?>' acao='apagarDespesa' title='<?php echo lang('BANCO_TRANSFERENCIA_APAGAR').": ".$exrow->descricao;?>'><i class='fa fa-times'></i></a>
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
										<td colspan="8"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo moedap($total);?></strong></td>
										<td><strong><?php echo moedap($pago);?></strong></td>
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
<?php case 'cadastro': 
$id_cadastro = (get('id_cadastro')) ? get('id_cadastro') : -1; 

?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#buscar').click(function() {
			var id_cadastro = $("#id_cadastro").val();
			window.location.href = 'index.php?do=despesa&acao=cadastro&id_cadastro='+ id_cadastro;
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
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_CADASTRO');?></small></h1>
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
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('FINANCEIRO_CADASTRO');?></span>
							</div>
							<div class='actions btn-set'>
								<small class="font-blue"><i class='fa fa-circle'>&nbsp;&nbsp;</i><?php echo lang('FISCAL');?></small>&nbsp;&nbsp;
								<small class="font-yellow-gold"><i class='fa fa-square'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_IMPORTANTE');?></small>&nbsp;&nbsp;
								<small class="font-green"><i class='fa fa-check'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_CONCILIADO');?></small>&nbsp;&nbsp;
								<a href='index.php?do=despesa&acao=adicionar' class='btn btn-sm <?php echo $core->primeira_cor;?>'><i class='fa fa-plus-square'>&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete='off' class="form-inline">
								<div class="form-group">
									<input name="id_cadastro" id="id_cadastro" type="hidden" />
									<input type="text" autocomplete="off" class="form-control caps listar_cadastro input-xlarge" name="cadastro" placeholder="<?php echo lang('BUSCAR');?>">
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
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
										<th><?php echo lang('PLANO_CONTAS');?></th>
										<th><?php echo lang('VALOR');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
										<th><?php echo lang('PAGO');?></th>
										<th><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$total = 0;
										$total_pago = 0;
										$retorno_row = $despesa->getDespesasCadastro($id_cadastro);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$total += $exrow->valor;
											$total_pago += $exrow->valor_pago;
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
											$pago = '';
											if($exrow->pago) {
												$pago = "<span class='label label-sm bg-green'>".lang('SIM')."</span>";
											} else {
												$pago = "<span class='label label-sm bg-red'>".lang('NAO')."</span>";
											}
											if($exrow->inativo) {
												$pago = "<span class='label label-sm bg-red'>".lang('CANCELADO')."</span>";
											}
								?>
											<tr <?php echo $estilo;?>>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo $exrow->id;?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo ($exrow->empresa);?></td>
												<td><?php echo ($exrow->banco);?></td>
												<td><?php echo $exrow->cadastro;?></td>
												<td><?php echo $exrow->descricao;?></td>
												<td><?php echo $exrow->conta;?></td>
												<td><?php echo moedap($exrow->valor);?></td>
												<td <?php echo $juros;?>><?php echo moedap($exrow->valor_pago);?></td>
												<td><?php echo $pago;?></td>
												<td>
													<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_despesa.php?id_despesa=<?php echo $exrow->id;?>','<?php echo lang('IMPRIMIR_RECIBO').$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a>
													<a href="javascript:void(0);" onclick="javascript:void window.open('ver_despesa.php?id_despesa=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
													<?php if($usuario->is_Master()): ?>
														<a href='index.php?do=despesa&acao=editarpagas&id=<?php echo $exrow->id;?>' class='btn btn-sm blue' title='<?php echo lang('EDITAR').': '.$exrow->descricao;?>'><i class='fa fa-pencil'></i></a>
														<?php if(!$exrow->inativo): ?>
														<a href='javascript:void(0);' class='btn btn-sm red apagar' id='<?php echo $exrow->id;?>' acao='apagarDespesas' title='<?php echo lang('FINANCEIRO_DESPESAS_APAGAR').$exrow->descricao;?>'><i class='fa fa-times'></i></a>
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
										<td colspan="8"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo moedap($total);?></strong></td>
										<td><strong><?php echo moedap($total_pago);?></strong></td>
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
<?php case 'despesasdre': 
$id_conta = (get('id_conta')) ? get('id_conta') : 0; 
$dataini = (get('dataini')) ? get('dataini') : date('01/m/Y'); 
$data = explode("/", $dataini);	
$ultimo = cal_days_in_month(CAL_GREGORIAN, $data[1], $data[2]);
$datafim = (get('datafim')) ? get('datafim') : date($ultimo.'/'.$data[1].'/'.$data[2]); 

?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#buscar').click(function() {
			var id_conta = $("#id_conta_despesa").val();
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			window.location.href = 'index.php?do=despesa&acao=despesasdre&dataini='+ dataini +'&datafim='+ datafim +'&id_conta='+ id_conta;
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
								<small class="font-yellow-gold"><i class='fa fa-square'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_IMPORTANTE');?></small>&nbsp;&nbsp;
								<a href='index.php?do=despesa&acao=adicionar' class='btn btn-sm <?php echo $core->primeira_cor;?>'><i class='fa fa-plus-square'>&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete='off' class="form-inline">
								<div class="form-group">
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
									<select class='select2me form-control input-large' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_CATEGORIA');?>' >
										<option value=""></option>
										<?php 
											$retorno_row = $faturamento->getFilho($id_pai);
											if ($retorno_row):
												foreach ($retorno_row as $srow):
										?>
													<option value='<?php echo $srow->id_filho;?>'><?php echo $srow->filho;?></option>
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
										<th><?php echo lang('DATA_PAGAMENTO');?></th>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('FORNECEDOR');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('PLANO_CONTAS');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$total = 0;
										$retorno_row = $despesa->getDespesasDRE($dataini, $datafim, $id_conta);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$total += $exrow->valor;
								?>
											<tr>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo nocaixa($exrow->banco);?></td>
												<td><?php echo $exrow->cadastro;?></td>
												<td><?php echo $exrow->descricao;?></td>
												<td><?php echo $exrow->conta;?></td>
												<td><?php echo moedap($exrow->valor);?></td>
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
<?php case 'agrupar': 
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
$id_cadastro = (get('id_cadastro')) ? get('id_cadastro') : 0; 
$id_banco = (get('id_banco')) ? get('id_banco') : 0; 
$id_conta = (get('id_conta')) ? get('id_conta') : 0; 
$valor = (get('valor')) ? get('valor') : ''; 
$numero = (get('numero')) ? get('numero') : ''; 
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
			var numero = $("#numero").val();
			window.location.href = 'index.php?do=despesa&acao=agrupar&dataini='+ dataini +'&datafim='+ datafim +'&id_cadastro='+ id_cadastro +'&id_banco='+ id_banco +'&id_conta='+ id_conta +'&id_empresa='+ id_empresa +'&valor='+ valor +'&numero='+ numero;
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
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_DESPESASAGRUPAR');?></small></h1>
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
								<i class='fa fa-share-alt font-<?php echo $core->primeira_cor;?>'></i>								
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('FINANCEIRO_DESPESASAGRUPAR');?></span>
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
										<option value="0">Selecione antes o plano de contas</option>
									</select>
									<br/>
									<br/>
									<label><?php echo lang('DATA_PAGAMENTO');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium calendario data" name="dataini" id="dataini" value="<?php echo $dataini;?>" >
									<input type="text" class="form-control input-medium calendario data" name="datafim" id="datafim" value="<?php echo $datafim;?>" >
									<br/>
									<br/>
									<label><?php echo lang('SELECIONE_VALOR');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-small moedap" name="valor" id="valor" value="<?php echo $valor;?>">
									&nbsp;&nbsp;
									<label><?php echo lang('NRO_DOCUMENTO');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-small" name="numero" id="numero" value="<?php echo $numero;?>">
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
								</div>
							</form>
						</div>
						<div class='portlet-body'>						
							<form autocomplete='off' class="form-inline" name='admin_form' id='admin_form'>
								<table class="table table-bordered table-striped table-condensed table-advance">
									<thead class='flip-content'>
										<tr>
											<th>#</th>
											<th><?php echo lang('PAGAMENTO');?></th>
											<th><?php echo lang('GRUPO');?></th>
											<th><?php echo lang('BANCO');?></th>
											<th><?php echo lang('DESCRICAO');?></th>
											<th><?php echo lang('PLANO_CONTAS');?></th>
											<th width="90px"><?php echo lang('VALOR_PAGO');?></th>
											<th><?php echo lang('NRO_DOCUMENTO');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 	
											$total = 0;
											$retorno_row = $despesa->getDespesasPagas($id_empresa, $dataini, $datafim, $id_banco, $id_conta, $id_cadastro, $valor, $numero);
											if($retorno_row):
												foreach ($retorno_row as $exrow):
												$total += $exrow->valor_pago;
												$estilo = "";	
												if($exrow->cheque) {
													$estilo = "class='warning'";
												}elseif($exrow->fiscal) {
													$estilo = "class='info'";
												}
									?>
												<tr <?php echo $estilo;?>>	
													<td>
														<input type="checkbox" class="agrupar" name="agrupar[]" value="<?php echo $exrow->id;?>" valor="<?php echo $exrow->valor_pago;?>"/>
													</td>
													<td><?php echo exibedata($exrow->data_pagamento);?></td>
													<td><?php echo $exrow->agrupar;?></td> 
													<td><?php echo nocaixa($exrow->banco);?></td>
													<td><?php echo $exrow->descricao;?></td>
													<td><?php echo $exrow->conta;?></td>
													<td><?php echo moedap($exrow->valor_pago);?></td>
													<td><?php echo $exrow->nro_documento;?></td>
												</tr>
									<?php 
												endforeach;
												unset($exrow);
											endif;
									?>
									</tbody>
									<tfoot class='flip-content'>
										<tr>
											<td colspan="2">
												<a href='javascript:void(0);' class='btn btn-sm yellow-gold agrupardespesas' title='<?php echo lang('FINANCEIRO_DESPESASAGRUPAR');?>'><i class='fa fa-share-alt'>&nbsp;&nbsp;</i><?php echo lang('AGRUPAR');?></a>
											</td>
											<td></td>
											<td colspan="3"><strong><?php echo lang('TOTAL');?></strong></td>
											<td><strong><div id="resultado"></div></strong></td>
											<td></td>
										</tr>
									</tfoot>
								</table>
								<input name='total' type='hidden' id="total" />
								<input name='agruparDespesas' type='hidden' value='1' />
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
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('.agrupar').click(function() {
			var valor = $(this).attr('valor');
			//alert(valor);
			var total = $('#total').val();
			total = parseFloat(total);
			total = (isNaN(total)) ? 0 : parseFloat(total);
			total = ($(this).prop('checked')) ? total + parseFloat(valor) : total - parseFloat(valor);
			$('#total').val(total.toFixed(2));
			resultado = total.toFixed(2);
			resultado = 'R$ ' + resultado.replace('.',',');	
			$('#resultado').text(resultado);
		});
	});
	// ]]>
</script>
<?php break;?>
<?php case 'pagarcartoes': 
$numero = (get('numero')) ? get('numero') : ''; 

?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#buscar').click(function() {
			var numero = $("#numero").val();
			window.location.href = 'index.php?do=despesa&acao=pagarcartoes&numero='+ numero;
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
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_DESPESASCARTOES');?></small></h1>
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
								<i class='fa fa-credit-card font-<?php echo $core->primeira_cor;?>'></i>								
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('FINANCEIRO_DESPESASCARTOES');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete='off' class="form-inline">
								<div class="form-group">
									<label><?php echo lang('NRO_DOCUMENTO');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-small" name="numero" id="numero" value="<?php echo $numero;?>">
									&nbsp;&nbsp;
									<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
								</div>
							</form>
						</div>
						<div class='portlet-body'>						
							<form autocomplete='off' class="form-inline" name='admin_form' id='admin_form'>
								<hr>
								<div class='row'>
									<div class='col-md-12'>
										<select class="select2me form-control input-large" name="id_banco" data-placeholder="<?php echo lang('SELECIONE_BANCO');?>" >
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
										&nbsp;&nbsp;
										<label><?php echo lang('DATA_PAGAMENTO');?>&nbsp;&nbsp;</label><input type="text" class="form-control input-medium calendario data" name="data_pagamento">
										&nbsp;&nbsp;
										<a href='javascript:void(0);' class='btn btn-sm green agrupardespesas' title='<?php echo lang('FINANCEIRO_DESPESASCARTOES');?>'><i class='fa fa-check'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_DESPESASCARTOES');?></a>
									</div>
								</div>	
								<br>
								<div class='row'>
									<div class='col-md-12'>
										<table class="table table-bordered table-striped table-condensed table-advance">
											<thead class='flip-content'>
												<tr>
													<th>#</th>
													<th><?php echo lang('PAGAMENTO');?></th>
													<th><?php echo lang('GRUPO');?></th>
													<th><?php echo lang('BANCO');?></th>
													<th><?php echo lang('DESCRICAO');?></th>
													<th><?php echo lang('PLANO_CONTAS');?></th>
													<th width="90px"><?php echo lang('VALOR_PAGO');?></th>
													<th><?php echo lang('NRO_DOCUMENTO');?></th>
												</tr>
											</thead>
											<tbody>
											<?php 	
													$total = 0;
													$retorno_row = $despesa->getDespesasCartoes($numero);
													if($retorno_row):
														foreach ($retorno_row as $exrow):
														$total += $exrow->valor;
											?>
														<tr>
															<td>
																<?php echo $exrow->id;?>
																<input type="hidden" name="agrupar[]" value="<?php echo $exrow->id;?>" valor="<?php echo $exrow->valor;?>"/>
															</td>
															<td><?php echo exibedata($exrow->data_pagamento);?></td>
															<td><?php echo $exrow->agrupar;?></td> 
															<td><?php echo nocaixa($exrow->banco);?></td>
															<td><?php echo $exrow->descricao;?></td>
															<td><?php echo $exrow->conta;?></td>
															<td><?php echo moedap($exrow->valor);?></td>
															<td><?php echo $exrow->nro_documento;?></td>
														</tr>
											<?php 
														endforeach;
														unset($exrow);
													endif;
											?>
											</tbody>
											<tfoot class='flip-content'>
												<tr>
													<td colspan="2">
														<a href='javascript:void(0);' class='btn btn-sm green agrupardespesas' title='<?php echo lang('FINANCEIRO_DESPESASCARTOES');?>'><i class='fa fa-check'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_DESPESASCARTOES');?></a>
													</td>
													<td></td>
													<td colspan="3"><strong><?php echo lang('TOTAL');?></strong></td>
													<td><strong><?php echo moedap($total);?></strong></td>
													<td></td>
												</tr>
											</tfoot>
										</table>
										<input name='agruparPagarDespesasCartoes' type='hidden' value='1' />
									</div>
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
<?php default: ?>
<div class='imagem-fundo'>
	<img src='assets/img/logo.png' border='0'>
</div>
<?php break;?>
<?php endswitch;?>

<?php if (isset($_GET["id_despesa"])):
		$id_despesa = get('id_despesa');
		$row_despesa = $despesa->getDetalhesDespesa($id_despesa);
?>
    <script>
        function getStatus () {
            if (!window.Notification) {
                return "unsupported";
            }
            return window.Notification.permission;
        }

        // get permission Promise
        function getPermission () {
            return new Promise((resolve, reject) => {
                Notification.requestPermission(status => {
                    var status = getStatus();
                    if (status == 'granted') {
                        resolve();
                    }else{
                        reject(status);
                    }
                });
            });
        };
		
		getPermission()
			.then(function(){
				var n = new Notification("Despesa adicionada", {
					body: "Código: #<?php print $id_despesa;?>\nValor: <?php print moedap($row_despesa->valor);?>\n<?php print $row_despesa->cadastro;?>"
				});
			}).catch(function(status){
				console.log('Had no permission!');
			});
    </script>
<?php endif;?>