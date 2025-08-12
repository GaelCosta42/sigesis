<?php

/**
 * Nota Fiscal
 *
 */
if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe nao e permitido.');
if (!$usuario->is_Administrativo())
	redirect_to("login.php");
if ($core->tipo_sistema == 2)
	redirect_to("login.php");
?>
<?php switch (Filter::$acao):
	case "editar": ?>
		<?php
		if (!$usuario->is_Administrativo()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif;

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");
		?>
		<?php
		$row = Core::getRowById("nota_fiscal", Filter::$id);
		$row_transporte = $produto->getTransporteNota(Filter::$id);
		$nome_cadastro = getValue("nome", "cadastro", "id = " . $row->id_cadastro);
		$numero_pedido_compra = $produto->getNumeroPedidoCompraNota(Filter::$id);
		$row_empresa = Core::getRowById("empresa", $usuario->idempresa);
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_EDITAR'); ?></small></h1>
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
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-pencil">&nbsp;&nbsp;</i><?php echo lang('NOTA_EDITAR'); ?>
									</div>
									<div class="actions btn-set">
										<?php if ($row->nome_arquivo): ?>
											<a href="javascript:void(0);" class="btn btn-sm default"
												onclick="javascript:void window.open('<?php echo "./uploads/data/" . $row->nome_arquivo; ?>','<?php echo lang('ARQUIVOS_VISUALIZAR_XML'); ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
													class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('ARQUIVOS_VISUALIZAR_XML'); ?></a>
										<?php endif; ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('EMPRESA'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="id_empresa"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $empresa->getEmpresas();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $row->id_empresa)
																					   echo 'selected="selected"'; ?>><?php echo $srow->nome; ?>
																				</option>
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
																<label
																	class='control-label col-md-3'><?php echo lang('NOME'); ?></label>
																<div class='col-md-9'>
																	<input name="id_cadastro" id="id_cadastro" type="hidden"
																		value="<?php echo $row->id_cadastro; ?>" />
																	<input type="text" autocomplete="off"
																		class="form-control caps listar_cadastro"
																		name="cadastro"
																		placeholder="<?php echo $nome_cadastro; ?>">
																</div>
															</div>
														</div>
														<div class="row selecionado ocultar">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<span
																		class="label label-success label-sm"><?php echo lang('SELECIONADO'); ?></span>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CHAVE_ACESSO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="chaveacesso"
																		value="<?php echo $row->chaveacesso; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CHAVE_ACESSO_REFERENCIADA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="nfe_referenciada"
																		value="<?php echo $row->nfe_referenciada; ?>">
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CFOP'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="cfop" id="select_cfop_nfe"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getCFOP_Todos();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->cfop; ?>" descricao="<?php echo $srow->descricao; ?>"
																					<?php if ($srow->cfop == $row->cfop)
																						echo 'selected="selected"'; ?>>
																						<?php echo $srow->cfop . " - " . $srow->descricao; ?>
																				</option>
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
																<label
																	class="control-label col-md-3"><?php echo lang('NATUREZA_OPERACAO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="natureza_operacao_nfe" id="natureza_operacao_nfe" maxlength="60"
																		value="<?php echo $row->natureza_operacao; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO_PEDIDO_COMPRA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="numero_pedido_compra" <?php if ($numero_pedido_compra)
																			echo 'value="' . $numero_pedido_compra . '"'; ?>>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section"><strong><?php echo lang('EXPORTACAO_INFORMACOES');?></strong></h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check"
																				name="nf_exportacao" id="nf_exportacao"
																				value="1" <?php if ($row->nota_exportacao)
																					echo 'checked'; ?>>
																			<label for="nf_exportacao">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('NOTA_FISCAL_EXPORTACAO'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NOTA_FISCAL_EXPORTACAO_PAIS'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control"
																		name="pais_exportacao"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getPaisesIbge();
																		if ($retorno_row):
																			foreach ($retorno_row as $prow):
																				?>
																				<option value="<?php echo $prow->codigo; ?>" <?php if ($prow->codigo == $row->pais_exportacao)
																					   echo 'selected="selected"'; ?>><?php echo $prow->pais; ?>
																				</option>
																				<?php
																			endforeach;
																			unset($prow);
																		endif;
																		?>
																	</select>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-12">
														<h4 class="form-section"></h4>
												</div>

												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS_ST'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">%</span>
																		<input type="text" class="form-control decimal"
																			name="icms_st_aliquota"
																			value="<?php echo decimal($row_empresa->icms_st_aliquota); ?>">
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">%</span>
																		<input type="text" class="form-control decimal"
																			name="icms_normal_aliquota"
																			value="<?php echo decimal($row_empresa->icms_normal_aliquota); ?>">
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_MVA'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">%</span>
																		<input type="text" class="form-control decimal" name="mva"
																			value="<?php echo decimal($row_empresa->mva); ?>">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--col-md-6-->
													<!--/col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_SEGURO'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">R$</span>
																		<input type="text" class="form-control decimalp"
																			name="valor_seguro"
																			value="<?php echo decimalp($row->valor_seguro); ?>">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<div class="col-md-12">
														<h4 class="form-section"></h4>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check"
																				name="apresentar_duplicatas"
																				id="apresentar_duplicatas" value="1" <?php if ($row->apresentar_duplicatas)
																					echo 'checked'; ?>>
																			<label for="apresentar_duplicatas">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('NOTA_FISCAL_DUPLICATAS'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--col-md-6-->
													<!--/col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_VENCIMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control data calendario"
																		name="data_vencimento"
																		value="<?php echo ($row->data_pagamento != '0000-00-00') ? exibedata($row->data_pagamento) : ""; ?>">
																	<span class="help-block">
																		<?php echo lang('DATA_VENCIMENTO_REGRA'); ?>
																	</span>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<h4 class="form-section"></h4>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><strong><?php echo lang('DISCRIMINACAO'); ?></strong></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea class="form-control"
																		name="descriminacao"><?php echo $row->descriminacao; ?></textarea>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><strong><?php echo lang('INF_ADICIONAIS'); ?></strong></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea class="form-control"
																		name="inf_adicionais"><?php echo $row->inf_adicionais; ?></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-12"><br><br></div>

												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section">
															<strong><?php echo lang('INFORMACOES_TRANSPORTE'); ?></strong>
														</h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TRANSPORTE'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="modalidade"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""><?php echo lang('SEM_TRANSPORTE'); ?>
																		</option>
																		<option value="SemFrete" <?php if ($row_transporte and 'SemFrete' == $row_transporte->modalidade)
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('SEMFRETE'); ?>
																		</option>
																		<option value="PorContaDoEmitente" <?php if ($row_transporte and 'PorContaDoEmitente' == $row_transporte->modalidade)
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('PORCONTADOEMITENTE'); ?>
																		</option>
																		<option value="PorContaDoDestinatario" <?php if ($row_transporte and 'PorContaDoDestinatario' == $row_transporte->modalidade)
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('PORCONTADODESTINATARIO'); ?>
																		</option>
																		<option value="ContratacaoPorContaDoRemetente" <?php if ($row_transporte and 'ContratacaoPorContaDoRemetente' == $row_transporte->modalidade)
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('CONTRATACAOPORCONTADOREMETENTE'); ?>
																		</option>
																		<option value="ContratacaoPorContaDoDestinatario" <?php if ($row_transporte and 'ContratacaoPorContaDoDestinatario' == $row_transporte->modalidade)
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('CONTRATACAOPORCONTADODESTINARIO'); ?>
																		</option>
																		<option value="ContratacaoPorContaDeTerceiros" <?php if ($row_transporte and 'ContratacaoPorContaDeTerceiros' == $row_transporte->modalidade)
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('CONTRATACAOPORCONTADETERCEIROS'); ?>
																		</option>
																		<option value="TransporteProprioPorContaDoRemetente"
																			<?php if ($row_transporte and 'TransporteProprioPorContaDoRemetente' == $row_transporte->modalidade)
																				echo 'selected="selected"'; ?>>
																			<?php echo lang('TRANSPORTEPROPRIOPORCONTADOREMETENTE'); ?>
																		</option>
																		<option value="TransporteProprioPorContaDoDestinatario"
																			<?php if ($row_transporte and 'TransporteProprioPorContaDoDestinatario' == $row_transporte->modalidade)
																				echo 'selected="selected"'; ?>>
																			<?php echo lang('TRANSPORTEPROPRIOPORCONTADODESTINATARIO'); ?>
																		</option>
																		<option value="SemOcorrenciaDeTransporte" <?php if ($row_transporte and 'SemOcorrenciaDeTransporte' == $row_transporte->modalidade)
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('SEMOCORRENCIADETRANSPORTE'); ?>
																		</option>
																	</select>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_FRETE'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">R$</span>
																		<input type="text" class="form-control decimalp"
																			name="valor_frete"
																			value="<?php echo decimalp($row->valor_frete); ?>">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_SAIDA_ENTRADA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control data calendario"
																		name="dataSaidaEntrada"
																		value="<?php echo ($row->dataSaidaEntrada != '0000-00-00') ? exibedata($row->dataSaidaEntrada) : ''; ?>">
																	<span class="help-block">
																		<?php echo lang('DATA_SAIDA_ENTRADA_REGRA'); ?>
																	</span>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12"><br><br></div>

												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="col-md-12">
															<h5 class="form-section">
																<strong><?php echo lang('EMPRESA_DESTINO_DADOS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TIPO'); ?></label>
																<div class="col-md-9">
																	<div class="md-radio-list">
																		<div class="md-radio col-md-6">
																			<input type="radio" class="md-radiobtn"
																				name="tipopessoadestinatario" id="tipo_j"
																				value="J" <?php ($row_transporte) ? getChecked($row_transporte->tipopessoadestinatario, 'J') : ''; ?>>
																			<label for="tipo_j">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_JURIDICA'); ?></label>
																		</div>
																		<div class="md-radio col-md-6">
																			<input type="radio" class="md-radiobtn"
																				name="tipopessoadestinatario" id="tipo_f"
																				value="F" <?php ($row_transporte) ? getChecked($row_transporte->tipopessoadestinatario, 'F') : ''; ?>>
																			<label for="tipo_f">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_FISICA'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CPF_CNPJ'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cpf_cnpj"
																			name="cpfcnpjdestinatario" id="cpfcnpjdestinatario"
																			value="<?php echo ($row_transporte) ? $row_transporte->cpfcnpjdestinatario : ''; ?>">
																		<span
																			class="input-group-addon"><?php echo lang('TECLE_ENTER') ?></span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEP'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cep" name="cep"
																			id="cep"
																			value="<?php echo ($row_transporte) ? $row_transporte->cep : ''; ?>">
																		<span class="input-group-btn">
																			<button id="cepbusca"
																				class="btn <?php echo $core->primeira_cor; ?>"
																				type="button"><i
																					class="fa fa-arrow-left fa-fw"></i>
																				<?php echo lang('BUSCAR_END'); ?></button>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ENDERECO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="logradouro" id="endereco"
																		value="<?php echo ($row_transporte) ? $row_transporte->logradouro : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="numero"
																		id="numero"
																		value="<?php echo ($row_transporte) ? $row_transporte->numero : ''; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<br><br><br><br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('COMPLEMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="complemento" id="complemento"
																		value="<?php echo ($row_transporte) ? $row_transporte->complemento : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('BAIRRO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="bairro"
																		id="bairro"
																		value="<?php echo ($row_transporte) ? $row_transporte->bairro : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="cidade"
																		id="cidade"
																		value="<?php echo ($row_transporte) ? $row_transporte->cidade : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTADO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps uf" name="uf"
																		id="estado"
																		value="<?php echo ($row_transporte) ? $row_transporte->uf : ''; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12"><br><br></div>

												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="col-md-12">
															<h5 class="form-section">
																<strong><?php echo lang('DADOS_MERCADORIAS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('QUANT_VOLUMES'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="quantidade"
																		value="<?php echo ($row_transporte) ? $row_transporte->quantidade : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESPECIE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="especie"
																		value="<?php echo ($row_transporte) ? $row_transporte->especie : ''; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PESOLIQUIDO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="pesoliquido"
																		value="<?php echo ($row_transporte) ? $row_transporte->pesoliquido : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PESOBRUTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="pesobruto"
																		value="<?php echo ($row_transporte) ? $row_transporte->pesobruto : ''; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12"><br><br></div>

												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="col-md-12">
															<h5 class="form-section">
																<strong><?php echo lang('TRANSPORTADORA_DADOS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TRANSPORTADORA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="trans_nome" id="trans_nome"
																		value="<?php echo ($row_transporte) ? $row_transporte->trans_nome : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TIPO'); ?></label>
																<div class="col-md-9">
																	<div class="md-radio-list">
																		<div class="md-radio col-md-6">
																			<input type="radio" class="md-radiobtn"
																				name="trans_tipopessoa" id="tipo_j_trans" value="J"
																				<?php ($row_transporte) ? getChecked($row_transporte->trans_tipopessoa, 'J') : ''; ?>>
																			<label for="tipo_j_trans">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_JURIDICA'); ?></label>
																		</div>
																		<div class="md-radio col-md-6">
																			<input type="radio" class="md-radiobtn"
																				name="trans_tipopessoa" id="tipo_f_trans" value="F"
																				<?php ($row_transporte) ? getChecked($row_transporte->trans_tipopessoa, 'F') : ''; ?>>
																			<label for="tipo_f_trans">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_FISICA'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TRANS_CPF_CNPJ'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cpf_cnpj"
																			name="trans_cpfcnpj" id="trans_cpfcnpj"
																			value="<?php echo ($row_transporte) ? $row_transporte->trans_cpfcnpj : ''; ?>">
																		<span class="input-group-addon">
																			<?php echo lang('TECLE_ENTER') ?>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('INSCRICAO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="trans_inscricaoestadual" id="trans_inscricaoestadual"
																		value="<?php echo ($row_transporte) ? $row_transporte->trans_inscricaoestadual : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PLACA_VEICULO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="veiculo_placa"
																		value="<?php echo ($row_transporte) ? $row_transporte->veiculo_placa : ''; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEP'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cep"
																			name="trans_cep" id="trans_cep">
																		<span class="input-group-btn">
																			<button id="ceptransportadora"
																				class="btn <?php echo $core->primeira_cor; ?>"
																				type="button"><i
																					class="fa fa-arrow-left fa-fw"></i>
																				<?php echo lang('BUSCAR_END'); ?></button>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ENDERECO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="trans_endereco" id="trans_endereco"
																		value="<?php echo ($row_transporte) ? $row_transporte->trans_endereco : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="trans_cidade" id="trans_cidade"
																		value="<?php echo ($row_transporte) ? $row_transporte->trans_cidade : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTADO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="trans_uf"
																		id="trans_uf"
																		value="<?php echo ($row_transporte) ? $row_transporte->trans_uf : ''; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('UF_VEICULO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps uf"
																		name="veiculo_uf"
																		value="<?php echo ($row_transporte) ? $row_transporte->veiculo_uf : ''; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12"><br><br></div>

												<?php if (!$row->id_venda): ?>
													<div class="col-md-12">
														<div class="col-md-12">
															<h4 class="form-section">
																<strong><?php echo lang('INFORMACOES_PRODUTOS'); ?></strong>
															</h4>
														</div>
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
																	<div class="col-md-9">
																		<select class="select2me form-control produto_notafiscal"
																			name="sel_produto" id="sel_produto"
																			data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $produto->getProdutos();
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->id; ?>">
																						<?php echo $srow->codigo . "#" . $srow->nome; ?>
																					</option>
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
																	<label
																		class="control-label col-md-3"><?php echo lang('PRODUTO_TIPO_ITEM'); ?></label>
																	<div class="col-md-9">
																		<select class="select2me form-control" name="sel_tipo_item"
																			id="sel_tipo_item"
																			data-placeholder="<?php echo lang('SELECIONE_TIPO_ITEM'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $produto->getTipoItemProduto();
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->codigo; ?>" <?php if ($srow->codigo == '00')
																						   echo 'selected="selected"'; ?>>
																						<?php echo $srow->codigo . " - " . $srow->descricao; ?>
																					</option>
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
																	<label
																		class="control-label col-md-3"><?php echo lang('QUANTIDADE'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control decimalp"
																			name="sel_quant" id="sel_quant">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_UNIDADE'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_valor" id="sel_valor">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_DESCONTO'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_desconto" id="sel_desconto">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('DESPESAS_ACESSORIAS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_despesas" id="sel_despesas">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
														<!--col-md-5-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ITEM_PEDIDO_COMPRA'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_item_pedido_compra"
																			id="sel_item_pedido_compra">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CFOP'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_cfop" id="sel_cfop" maxlength="4">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CSOSN_CST'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_cst" id="sel_cst" maxlength="4">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('NCM'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_ncm" id="sel_ncm" maxlength="8">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CEST'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_cest" id="sel_cest" maxlength="7">
																		<span
																			class="help-block"><?php echo lang('OBS_CEST'); ?></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="col-md-6">
															<h5 class="form-section">
																<strong><?php echo lang('TRIBUTOS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<br><br>
														</div>
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_ICMS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_icms_base" id="sel_icms_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_icms" id="sel_icms">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_ST'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_st_base" id="sel_st_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS_ST'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_st_percentual" id="sel_st_percentual">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_MVA'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_mva" id="sel_mva">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-4-->
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CST_PIS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">Cód</span>
																			<input type="text" class="form-control inteiro"
																				name="sel_pis_cst" id="sel_pis_cst">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_PIS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_pis_base" id="sel_pis_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_PIS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_pis" id="sel_pis">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CST_COFINS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">Cód</span>
																			<input type="text" class="form-control inteiro"
																				name="sel_cofins_cst" id="sel_cofins_cst">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_COFINS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_cofins_base" id="sel_cofins_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_COFINS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_cofins" id="sel_cofins">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-4-->
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CST_IPI'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">Cód</span>
																			<input type="text" class="form-control inteiro"
																				name="sel_ipi_cst" id="sel_ipi_cst">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_IPI'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_ipi_base" id="sel_ipi_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_CST_IPI'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_ipi" id="sel_ipi">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div class="row">
																<div class="form-group">
																	<div class="col-md-3">
																		<a href="javascript:void(0);"
																			class="btn green adicionar_produto"
																			title="<?php echo lang('PRODUTO_ADICIONAR'); ?>"><i
																				class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_ADICIONAR'); ?></a>
																	</div>
																</div>
															</div>
														</div>
													</div>
												<?php endif; ?>
												<?php
												$tabela_produto = '';
												$produto_row = $produto->getProdutosNota(Filter::$id);
												if ($produto_row) {
													foreach ($produto_row as $prow) {
														$icms_percentual = (!empty($prow->icms_percentual) && $prow->icms_percentual > 0) ? converteMoeda($prow->icms_percentual) : converteMoeda($row->icms_normal_aliquota);
														$icms_st_percentual = (!empty($prow->icms_st_percentual) && $prow->icms_st_percentual > 0) ? converteMoeda($prow->icms_st_percentual) : converteMoeda($row->icms_st_aliquota);
														$mva_aliquota = (!empty($prow->icms_percentual_mva_st) && $prow->icms_percentual_mva_st > 0) ? converteMoeda($prow->icms_percentual_mva_st) : converteMoeda($row->mva);

														$total_produto = $prow->valor_total;
														$base_icms = $total_produto;
														$valor_icms = round($base_icms * $icms_percentual / 100, 2);

														$base_ipi = $total_produto;
														$valor_ipi = round($base_ipi * $prow->ipi_percentual / 100, 2);

														$base_st = ($total_produto + $valor_ipi) + ((($total_produto + $valor_ipi) / 100) * $mva_aliquota);
														$valor_mva = ($base_st / 100) * $icms_st_percentual;
														$valor_st = ($mva_aliquota) ? round($valor_mva - $valor_icms, 2) : 0;

														$base_pis = $total_produto - $valor_icms;
														$base_cofins = $total_produto - $valor_icms;

														$tabela_produto .= '
															<tr>
																<td>' . $prow->nome . '</td>
																<td>' . $prow->quantidade . '</td>
																<td><span class="bold theme-font valor_total">' . decimalp($prow->valor_total) . '</span></td>
																<td><input type="text" class="form-control" name="cfop_produto[]" id="cfop_produto" value="' . $prow->cfop . '"></td>
																<td><input type="text" class="form-control" name="icms_cst[]" id="icms_cst" value="' . $prow->icms_cst . '"></td>
																<td><input type="text" class="form-control" name="ncm[]" id="ncm" value="' . $prow->ncm . '"></td>
																<td><input type="text" class="form-control" name="cest[]" id="cest" value="' . $prow->cest . '"></td>
																<td>' . decimalp($prow->valor_desconto) . '</td>
																<td>' . decimalp($prow->outrasDespesasAcessorias) . '</td>
																<td>' . decimalp(($prow->icms_valor) ? $prow->icms_valor : $valor_icms) . '</td>
																<td>' . decimalp(($prow->icms_st_valor) ? $prow->icms_st_valor : $valor_st) . '</td>
																<td><input type="text" class="form-control" name="item_pedido_compra[]" id="item_pedido_compra" value="' . $prow->item_pedido_compra . '"></td>';

														$tabela_produto .= (!$row->id_venda) ? '<td><a href="javascript:void(0);" class="btn btn-xs red remover_produto" title="Deseja remover este produto?"><i class="fa fa-times"></i></a></td>' : '';

														$tabela_produto .= '<input name="id_produto[]" type="hidden" value="' . $prow->id_produto . '" />
																<input name="produto_tipo_item[]" type="hidden" value="' . $prow->produto_tipo_item . '" />
																<input name="quantidade_produto[]" type="hidden" value="' . $prow->quantidade . '" />
																<input name="valor_unitario_produto[]" type="hidden" value="' . $prow->valor_unitario . '" />
																<input name="valor_desconto_produto[]" type="hidden" value="' . $prow->valor_desconto . '" />
																<input name="valor_despesas_produto[]" type="hidden" value="' . $prow->outrasDespesasAcessorias . '" />
																<input name="valor_base_icms[]" type="hidden" value="' . $base_icms . '" />
																<input name="icms_percentual[]" type="hidden" value="' . $icms_percentual . '" />
																<input name="icms_st_base[]" type="hidden" value="' . $base_st . '" />
																<input name="icms_st_percentual[]" type="hidden" value="' . $icms_st_percentual . '" />
																<input name="pis_cst[]" type="hidden" value="' . $prow->pis_cst . '" />
																<input name="valor_base_pis[]" type="hidden" value="' . $base_pis . '" />
																<input name="pis_percentual[]" type="hidden" value="' . $prow->pis_percentual . '" />
																<input name="cofins_cst[]" type="hidden" value="' . $prow->cofins_cst . '" />
																<input name="valor_base_cofins[]" type="hidden" value="' . $base_cofins . '" />
																<input name="cofins_percentual[]" type="hidden" value="' . $prow->cofins_percentual . '" />
																<input name="ipi_cst[]" type="hidden" value="' . $prow->ipi_cst . '" />
																<input name="valor_base_ipi[]" type="hidden" value="' . $base_ipi . '" />
																<input name="ipi_percentual[]" type="hidden" value="' . $prow->ipi_percentual . '" />
																<input name="mva_produto[]" type="hidden" value="' . $mva_aliquota . '"/>
															</tr>';
													}
													unset($prow);
												}
												?>
												<div class="col-md-12">
													<div class="portlet-body">
														<div class="table-scrollable table-scrollable-borderless">
															<table class="table table-hover table-light">
																<thead>
																	<tr>
																		<th><?php echo lang('PRODUTO'); ?></th>
																		<th><?php echo lang('QUANT'); ?></th>
																		<th><?php echo lang('VL_TOTAL'); ?></th>
																		<th><?php echo lang('CFOP'); ?></th>
																		<th><?php echo lang('CSOSN_CST'); ?></th>
																		<th><?php echo lang('NCM'); ?></th>
																		<th><?php echo lang('CEST'); ?></th>
																		<th><?php echo lang('VL_DESC'); ?></th>
																		<th><?php echo lang('VL_ACRESC'); ?></th>
																		<th><?php echo lang('VL_ICMS'); ?></th>
																		<th><?php echo lang('VL_ST'); ?></th>
																		<th><?php echo lang('ITEM_PEDIDO_COMPRA'); ?></th>
																		<?php if (!$row->id_venda): ?>
																			<th></th>
																		<?php endif; ?>
																	</tr>
																</thead>
																<tbody id="tabela_produtos">
																	<?php echo $tabela_produto; ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										<input type='hidden' name='id' value='<?php echo $row->id; ?>' />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<button type="button"
																	class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR_NOTA'); ?></button>
																<button type="button" id="voltar"
																	class="btn default"><?php echo lang('VOLTAR'); ?></button>
															</div>
														</div>
													</div>
													<div class="col-md-6">
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm('editarNotaFiscal'); ?>
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
		<?php break; ?>
	<?php
	case "receita":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$row = Core::getRowById("nota_fiscal", Filter::$id);
		?>

		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
		<script type="module" src="./modulos/nota_fiscal/receita/js/receita.js"></script>
		<link rel="stylesheet" href="./modulos/nota_fiscal/receita/css/receita.css">

		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class='page-container'>
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class='page-head'>
				<div class='container'>
					<!-- INICIO TITULO DA PAGINA -->
					<div class='page-title'>
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_ADICIONAR_RECEITA'); ?></small>
						</h1>
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
										<i
											class='fa fa-plus-square font-<?php echo $core->primeira_cor; ?>'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_ADICIONAR_RECEITA'); ?>
									</div>
								</div>

								<div class="alert alert-warning" role="alert" style="text-align: center">
									Campos marcados com <span style="color: red; font-weight: bold;">*</span> são obrigatórios!
								</div>
								<div class='portlet-body form'>
									<!-- INICIO FORM-->
									<div>
										<div class='form-body'>
											<!-- Primeira Linha -->
											<div class='col-md-12'>
												<div class='row'>
													<div class='form-group col-md-5'>
														<label
															class='control-label col-md-3'><?php echo lang('NUMERO_NOTA'); ?></label>
														<div class='col-md-9 col-sm-12'>
															<input readonly type='text' class='form-control caps'
																value='<?php echo $row->numero_nota; ?>'>
														</div>
													</div>
													<div class='form-group col-md-7'>
														<label class='control-label col-md-3 col-sm-12'>
															<?php echo lang('PAGAMENTO'); ?>
															<span style="color: red; font-weight: bold;">*</span>
														</label>
														<div class='col-md-9 col-sm-12'>
															<select class='select2me form-control' name='tipo'
																id="receita_pagamento"
																data-placeholder='<?php echo lang('SELECIONE_OPCAO'); ?>'
																style="width: 100% !important">
																<option value=""></option>
																<?php
																$retorno_row = $faturamento->getTipoPagamento();
																if ($retorno_row):
																	foreach ($retorno_row as $srow):
																		?>
																		<option value='<?php echo $srow->id; ?>'
																			data-banco='<?php echo $srow->banco; ?>'
																			data-idbanco='<?php echo $srow->id_banco ?? 0; ?>'
																			data-dias='<?php echo $srow->dias; ?>'
																			data-parcelas='<?php echo $srow->parcelas; ?>'>
																			<?php echo $srow->tipo; ?>
																		</option>
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

											<!-- Segunda Linha -->
											<div class='col-md-12'>
												<div class='row'>
													<div class='form-group col-md-5'>
														<label
															class='control-label col-md-3 col-sm-12'><?php echo lang('DOCUMENTO'); ?></label>
														<div class='col-md-9 col-sm-12'>
															<input type='text' class='form-control caps' id="receita_documento"
																name='duplicata'>
														</div>
													</div>
													<div class='form-group col-md-7'>
														<label class='control-label col-md-3 col-sm-12'>Data do Pagamento
															<span style="color: red; font-weight: bold;">*</span>
														</label>
														<div class='col-md-9 col-sm-12'>
															<input type='text' class='form-control calendario data'
																id='receita_data' name='data_pagamento'>
														</div>
													</div>
												</div>
											</div>

											<!-- Terceira Linha -->
											<div class='col-md-12'>
												<div class='row'>
													<div class='form-group col-md-5'>
														<label class='control-label col-md-3'>Valor Total<span
																style="color: red; font-weight: bold;">*</span></label>
														<div class='col-md-9 col-sm-12'>
															<input type='text' class='form-control moedap' id="receita_valor"
																name='valor'>
														</div>
													</div>
													<div class='form-group col-md-7'>
														<label class='control-label col-md-3'>Parcelas</label>
														<div class='col-md-9 col-sm-12'>
															<input type='number' class='form-control' disabled
																id="receita_repeticoes" name='repeticoes'>
														</div>
													</div>
												</div>
											</div>

											<!-- Quarta Linha -->
											<div class='col-md-12'>
												<div class='row' id="rowButton">
													<div class='form-group'>
														<button type="button" class="btn <?php echo $core->primeira_cor; ?>"
															id="addRow">Adicionar Receita</button>
													</div>
												</div>
											</div>
											</th>
										</div>
										<div
											style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
											<div style="text-align: center; flex: 1;">
												<label for="valor_total"
													style="display: block; color: #2ecc71; font-weight: bold">Valor da
													Nota</label>
												<p id="valor_total" style="margin: 0; font-size: 16px; color: #2ecc71;">R$
													--.--
												</p>
											</div>
											<div style="text-align: center; flex: 1;">
												<label for="valor_pago"
													style="display: block; color: #e74c3c; font-weight: bold">Total em
													Receita</label>
												<p id="valor_pago" style="margin: 0; font-size: 16px; color: #e74c3c;">R$ --.--
												</p>
											</div>
											<div style="text-align: center; flex: 1;">
												<label for="valor_pendente" style="display: block; font-weight: bold">Valor
													Pendente</label>
												<p id="valor_pendente" style="margin: 0; font-size: 16px; color: #000000;">R$
													--.--</p>
											</div>
										</div>

										<form id="receita_lista">
											<table id="myTable" class="table table-bordered"
												style="border: 1px solid #e5e5e5; border-radius: 4px">
												<thead>
													<tr>
														<th style="width: 30%">Documento</th>
														<th style="width: 20%">Banco</th>
														<th style="width: 10%">Valor</th>
														<th style="width: 20%">Pagamento</th>
														<th style="width: 15%">Data Transação</th>
														<th style="width: 5%">Ação</th>
													</tr>
												</thead>
												<tbody>
													<tr id="no-records">
														<td colspan="6" style="text-align: center;">Nenhuma receita
															adicionada.</td>
													</tr>
												</tbody>
											</table>

											<input type='hidden' id="id_nota" name='id_nota' value='<?php echo $row->id; ?>' />
											<input type='hidden' id='id_empresa' name='id_empresa'
												value='<?php echo $row->id_empresa; ?>' />
											<input type='hidden' id='id_cadastro' name='id_cadastro'
												value='<?php echo $row->id_cadastro; ?>' />
											<input type='hidden' id='modelo' name='modelo'
												value='<?php echo $row->modelo; ?>' />
											<input type='hidden' id='numero_nota' name='numero_nota'
												value='<?php echo $row->numero_nota; ?>' />
										</form>
									</div>
									<div class='form-actions'>
										<div class='row'>
											<div class='col-md-12'>
												<div class='col-md-6'>
													<!-- Deixando a coluna à esquerda vazia para alinhar à direita -->
												</div>
												<div class='col-md-6'>
													<div class='row'>
														<div class='col-md-offset-3 col-md-9 text-right'>
															<button type="button" id="receita_submit"
																class="btn <?php echo $core->primeira_cor; ?>">
																<?php echo lang('SALVAR'); ?>
															</button>
															<button type='button' id='voltar' class='btn default'>
																<?php echo lang('VOLTAR'); ?>
															</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- FINAL FORM-->
								</div>
							</div>
						</div>
					</div>
					<!-- FINAL DO ROW FORMULARIO -->
				</div>
			</div>
			<!-- FINAL DOS MODULOS DA PAGINA -->
			<div id="overlay">
				<span class="loader"></span>
			</div>
		</div>
		<!-- FINAL CONTEUDO DA PAGINA -->
		<?php break; ?>
	<?php
	case 'despesa':

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$row = Core::getRowById("nota_fiscal", Filter::$id);
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class='page-container'>
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class='page-head'>
				<div class='container'>
					<!-- INICIO TITULO DA PAGINA -->
					<div class='page-title'>
						<h1><?php echo lang('FINANCEIRO_TITULO'); ?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_ADICIONAR'); ?></small>
						</h1>
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
							<div class='portlet box <?php echo $core->primeira_cor; ?>'>
								<div class='portlet-title'>
									<div class='caption'>
										<i
											class='fa fa-minus-square'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_ADICIONAR'); ?>
									</div>
								</div>
								<div class='portlet-body form'>
									<!-- INICIO FORM-->
									<form action='' autocomplete="off" method='post' class='form-horizontal' name='admin_form'
										id='admin_form'>
										<div class='form-body'>
											<div class='row'>
												<div class='col-md-12'>
													<!--col-md-6-->
													<div class='col-md-6'>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('NUMERO_NOTA'); ?></label>
																<div class='col-md-9'>
																	<input readonly type='text' class='form-control caps'
																		value='<?php echo $row->numero_nota; ?>'>
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('BANCO'); ?></label>
																<div class='col-md-9'>
																	<select class='select2me form-control' name='id_banco'
																		data-placeholder='<?php echo lang('SELECIONE_OPCAO'); ?>'>
																		<option value=""></option>
																		<?php
																		$retorno_row = $faturamento->getBancos();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value='<?php echo $srow->id; ?>'>
																					<?php echo $srow->banco; ?>
																				</option>
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
																<label
																	class='control-label col-md-3'><?php echo lang('NRO_DOCUMENTO'); ?></label>
																<div class='col-md-9'>
																	<input type='text' class='form-control caps'
																		name='nro_documento'
																		value='<?php echo $row->numero_nota; ?>'>
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('DUPLICATA'); ?></label>
																<div class='col-md-9'>
																	<input type='text' class='form-control caps'
																		name='duplicata'>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class='col-md-6'>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('VALOR'); ?></label>
																<div class='col-md-9'>
																	<input type='text' class='form-control moedap' name='valor'>
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('DATA_VENCIMENTO'); ?></label>
																<div class='col-md-9'>
																	<input type='text' class='form-control data calendario'
																		name='data_vencimento'>
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('REPETICOES'); ?></label>
																<div class='col-md-9'>
																	<input type='text' class='form-control inteiro'
																		name='repeticoes'>
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('DIAS'); ?></label>
																<div class='col-md-9'>
																	<input type='text' class='form-control inteiro' name='dias'
																		placeholder='<?php echo lang('FINANCEIRO_DIAS'); ?>'>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
											</div>
										</div>
										<input type='hidden' name='id_nota' value='<?php echo $row->id; ?>' />
										<input type='hidden' name='id_empresa' value='<?php echo $row->id_empresa; ?>' />
										<input type='hidden' name='id_cadastro' value='<?php echo $row->id_cadastro; ?>' />
										<input type='hidden' name='modelo' value='<?php echo $row->modelo; ?>' />
										<input type='hidden' name='numero_nota' value='<?php echo $row->numero_nota; ?>' />
										<div class='form-actions'>
											<div class='row'>
												<div class='col-md-12'>
													<div class='col-md-6'>
														<div class='row'>
															<div class='col-md-offset-3 col-md-9'>
																<button type='button'
																	class='btn btn-submit <?php echo $core->primeira_cor; ?>'><?php echo lang('SALVAR'); ?></button>
																<button type='button' id='voltar'
																	class='btn default'><?php echo lang('VOLTAR'); ?></button>
															</div>
														</div>
													</div>
													<div class='col-md-6'>
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm('processarNotaFiscalDespesas'); ?>
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
		<?php break; ?>
	<?php
	case "visualizar":
		if ($core->tipo_sistema == 3)
			redirect_to("login.php");
		?>

		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script type="module" src="./modulos/nota_fiscal/visualizar/js/visualizar.js"></script>
		<link rel="stylesheet" href="./modulos/nota_fiscal/visualizar/css/visualizar.css">

		<script>
			// Mascara com 3 digitos
			function mascaraMoeda(event) {
				const onlyDigits = event.target.value
					.split("")
					.filter(s => /\d/.test(s))
					.join("")
					.padStart(4, "0");
				const digitsFloat = onlyDigits.slice(0, -3) + "." + onlyDigits.slice(-3);
				event.target.value = maskCurrency(digitsFloat);
			}

			function maskCurrency(valor, locale = 'pt-BR', currency = 'BRL') {
				return new Intl.NumberFormat(locale, {
					style: 'currency',
					currency,
					minimumFractionDigits: 3,
					maximumFractionDigits: 3
				}).format(valor);
			}
		</script>
		<?php
		$row = Core::getRowById("nota_fiscal", Filter::$id);
		$impostos = $produto->getImpostosNotaFiscal(Filter::$id);
		$operacao = ($row->cfop) ? getValue('descricao', 'cfop', 'cfop=' . $row->cfop) : operacao($row->operacao);
		$devolucao = ($row->cfop) ? getValue('devolucao', 'cfop', 'cfop=' . $row->cfop) : 0;
		$numero_pedido_compra = $produto->getNumeroPedidoCompraNota(Filter::$id);
		?>
		<div id="novo-produto" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-plus">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_ADICIONAR'); ?>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="novo_form" id="novo_form" class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-md-12"><?php echo lang('NOVO_PRODUTO_ADICIONAR'); ?></label>
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="control-label col-md-3"><?php echo lang('QUANTIDADE_UNIDADES'); ?></label>
									<div class="col-md-9">
										<input type="text" class="form-control decimalp" name="quant_unidades">
										<?php echo lang('QUANTIDADE_UNIDADES_DESCRICAO'); ?>
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="control-label col-md-3"><?php echo lang('VALOR_VENDA'); ?></label>
									<div class="col-md-9">
										<input type="text" class="form-control moedap" name="valor_venda">
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="control-label col-md-3"><?php echo lang('CFOP_SAIDA'); ?></label>
									<div class="col-md-3">
										<input type="text" class="form-control inteiro" name="cfop" id="cfop" maxlength="4">
									</div>
									<label class="control-label col-md-3"><?php echo lang('CFOP_ENTRADA'); ?></label>
									<div class="col-md-3">
										<input type="text" class="form-control inteiro" name="cfop_entrada" id="cfop_entrada" maxlength="4">
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="control-label col-md-3"><?php echo lang('NCM'); ?></label>
									<div class="col-md-3">
										<input type="text" class="form-control inteiro" name="ncm_nf" id="ncm_nf" maxlength="8">
									</div>
									<label class="control-label col-md-3"><?php echo lang('CEST'); ?></label>
									<div class="col-md-3">
										<input type="text" class="form-control inteiro" name="cest_nf" id="cest_nf"
											maxlength="7">
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="control-label col-md-3"><?php echo lang('CSOSN_CST'); ?></label>
									<div class="col-md-9">
										<input type="text" class="form-control inteiro" name="csosn_cst" id="csosn_cst"
											maxlength="4">
										<span class="help-block"><?php echo lang('OBS_CSOSN_CST'); ?></span>
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="control-label col-md-3"><?php echo lang('COD_ANP'); ?></label>
									<div class="col-md-3">
										<input type="text" class="form-control inteiro" name="cod_anp" id="cod_anp">
									</div>
									<label class="control-label col-md-3"><?php echo lang('VALOR_PARTIDA'); ?></label>
									<div class="col-md-3">
										<input type="text" class="form-control" onInput="mascaraMoeda(event);"
											name="valor_partida" id="valor_partida">
									</div>
									<span class="span help-block"><?php echo lang('OBS_ANP'); ?></span>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('UNIDADE'); ?></label>
									<div class="col-md-8">
										<select class="select2me form-control" name="unidade"
											data-placeholder="<?php echo lang('SELECIONE_UNIDADE_MEDIDA'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $produto->getUnidadeMedida();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->unidade ?>">
														<?php echo $srow->unidade . ' - ' . $srow->descricao; ?>
													</option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('PRODUTO_TIPO_ITEM'); ?></label>
									<div class="col-md-8">
										<select class="select2me form-control" name="produto_tipo_item" id="produto_tipo_item"
											data-placeholder="<?php echo lang('SELECIONE_TIPO_ITEM'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $produto->getTipoItemProduto();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->codigo; ?>" <?php if ($srow->codigo == '00')
														   echo 'selected="selected"'; ?>>
														<?php echo $srow->codigo . " - " . $srow->descricao; ?>
													</option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CODIGO_DE_BARRAS'); ?></label>
									<div class="col-md-8">
										<input type="text" class="form-control codigo" name="codigobarras" id="codigobarras">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('GRUPO'); ?></label>
									<div class="col-md-8">
										<select class="select2me form-control" name="id_grupo"
											data-placeholder="<?php echo lang('SELECIONE_GRUPO'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $produto->getGrupos();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->grupo; ?></option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CATEGORIA'); ?></label>
									<div class="col-md-8">
										<select class="select2me form-control" name="id_categoria"
											data-placeholder="<?php echo lang('SELECIONE_CATEGORIA'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $produto->getCategorias();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->categoria; ?>
													</option>
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
						<input name="id_nota" type="hidden" value="<?php echo Filter::$id; ?>" />
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("novoProdutoNota", "novo_form"); ?>
		</div>

		<div id="editar-Info-Produto" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-pencil">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_EDITAR'); ?>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="editar_produto_form" id="editar_produto_form"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-md-12"><?php echo lang('EDITAR_PRODUTO_NF'); ?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CFOP_SAIDA_FORNECEDOR'); ?></label>
									<div class="col-md-8">
										<input readonly type="text" class="form-control readonly" name="cfop_editar"
											id="cfopsaida_editar" maxlength="4">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CFOP_ENTRADA'); ?></label>
									<div class="col-md-8">
										<input type="text" class="form-control inteiro" name="cfop_entrada_editar"
											id="cfopentrada_editar" maxlength="4">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CODIGO_DE_BARRAS'); ?></label>
									<div class="col-md-8">
										<input readonly type="text" class="form-control caps" name="codigobarras_editar"
											id="codigobarras_editar">
									</div>
								</div>
							</div>
						</div>
						<input name="id_nota" type="hidden" value="<?php echo Filter::$id; ?>" />
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("editarProdutoNotaEntrada", "editar_produto_form"); ?>
		</div>

		<div id="combinar-produto" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-link">&nbsp;&nbsp;</i><?php echo lang('COMBINAR_PRODUTO'); ?>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="combinar_form" id="combinar_form"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
										<div class="col-md-8">
											<select class="select2me form-control" name="id_produto"
												data-placeholder="<?php echo lang('SELECIONE_PRODUTO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $produto->getProdutos();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->nome; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('QUANTIDADE_UNIDADES'); ?></label>
									<div class="col-md-8">
										<input type="text" class="form-control decimalp" name="quant_unidades">
										<br>
										<?php echo lang('QUANTIDADE_UNIDADES_DESCRICAO'); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CFOP_ENTRADA'); ?></label>
									<div class="col-md-8">
										<input type="text" class="form-control inteiro" name="cfop_entrada"
											id="combinar_cfop_entrada" maxlength="4">
									</div>
								</div>
							</div>
						</div>
						<input name="id_nota" type="hidden" value="<?php echo Filter::$id; ?>" />
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("combinarProdutoNota", "combinar_form"); ?>
		</div>

		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_VISUALIZAR'); ?></small>
						</h1>
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
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('NOTA_VISUALIZAR'); ?>
									</div>
									<div class="actions btn-set">
										<?php if ($row->nome_arquivo): ?>
											<a href="javascript:void(0);" class="btn btn-sm default"
												onclick="javascript:void window.open('<?php echo "./uploads/data/" . $row->nome_arquivo; ?>','<?php echo lang('ARQUIVOS_VISUALIZAR_XML'); ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
													class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('ARQUIVOS_VISUALIZAR_XML'); ?></a>
										<?php endif; ?>
										<?php if($row->modelo == 3): ?>
												<a href="javascript:void(0);" class="btn btn-sm grey-gallery" onclick="javascript:void window.open('pdf_fatura.php?id=<?php echo $row->id;?>','<?php echo lang('FATURA_VISUALIZAR');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('FATURA_VISUALIZAR');?></a>
										<?php endif; ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<?php if ($row->inativo): ?>
												<div class="row">
													<div class="col-md-12">
														<div class="note note-danger">
															<h4 class="block"><?php echo lang('NOTA_CANCELADA'); ?></h4>
														</div>
													</div>
												</div>
											<?php endif; ?>
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CHAVE_ACESSO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="chaveacesso"
																		value="<?php echo $row->chaveacesso; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CHAVE_ACESSO_REFERENCIADA'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="nfe_referenciada"
																		value="<?php echo $row->nfe_referenciada; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('EMPRESA'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		value="<?php echo getValue("nome", "empresa", "id=" . $row->id_empresa); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('RAZAO_SOCIAL'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		value="<?php echo getValue("razao_social", "cadastro", "id=" . $row->id_cadastro); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('MODELO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		value="<?php echo modelo($row->modelo); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('OPERACAO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		value="<?php echo $operacao; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO_NOTA'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		value="<?php echo $row->numero_nota; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CFOP'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		value="<?php echo $row->cfop; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_EMISSAO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text"
																		class="form-control data calendario"
																		value="<?php echo exibedata($row->data_emissao); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_ENTRADA'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text"
																		class="form-control data calendario"
																		value="<?php echo exibedata($row->data_entrada); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_FRETE'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($row->valor_frete); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_SEGURO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($row->valor_seguro); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO_PEDIDO_COMPRA'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="numero_pedido_compra" <?php if ($numero_pedido_compra)
																			echo 'value="' . $numero_pedido_compra . '"'; ?>>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_BASE'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($impostos->icms_base); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_ICMS'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($impostos->icms_valor); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_BASE_ST'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($impostos->icms_st_base); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_ST'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($impostos->icms_st_valor); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_IPI'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($impostos->ipi_valor); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_PIS'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($impostos->pis_valor); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_COFINS'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($impostos->cofins_valor); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_OUTRO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($row->valor_outro); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_TRIBUTOS'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($impostos->valor_total_trib); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_TOTAL_PRODUTOS'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($row->valor_produto); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_DESCONTO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($row->valor_desconto); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_ACRESCIMO_TITULO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($impostos->outrasDespesasAcessorias); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_TOTAL_NOTA'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control moedap"
																		value="<?php echo moedap($row->valor_nota); ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><?php echo lang('DISCRIMINACAO'); ?></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea readonly class="form-control"
																		name="descriminacao"><?php echo $row->descriminacao; ?></textarea>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><?php echo lang('DUPLICATAS'); ?></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea readonly class="form-control"
																		name="duplicatas"><?php echo $row->duplicatas; ?></textarea>
																</div>
															</div>
														</div>
													</div>

													<?php if ($row->operacao == 2): ?>
														<div class="col-md-12">
															<div class="row">
																<div class="form-group">
																	<?php if ($row->apresentar_duplicatas): ?>
																		<label
																			class="col-md-5"><?php echo lang('DUPLICATAS_NFE_SIM'); ?></label>
																	<?php else: ?>
																		<label
																			class="col-md-5"><?php echo lang('DUPLICATAS_NFE_NAO'); ?></label>
																	<?php endif; ?>
																</div>
															</div>
														</div>
													<?php endif; ?>

													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><?php echo lang('INF_ADICIONAIS'); ?></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea readonly class="form-control"
																		name="inf_adicionais"><?php echo $row->inf_adicionais; ?></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php $id_transporte = getValue('id', 'nota_fiscal_transporte', 'id_nota=' . $row->id);
											if ($id_transporte):
												$row_transporte = Core::getRowById("nota_fiscal_transporte", $id_transporte);
												?>
												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section"><?php echo lang('INFORMACOES_TRANSPORTE'); ?>
														</h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TRANSPORTE'); ?></label>
																<div class="col-md-9">
																	<select disabled class="select2me form-control"
																		name="modalidade"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""><?php echo lang('SEM_TRANSPORTE'); ?>
																		</option>
																		<option value="SemFrete" <?php if ($row_transporte->modalidade == 'SemFrete')
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('SEMFRETE'); ?>
																		</option>
																		<option value="PorContaDoEmitente" <?php if ($row_transporte->modalidade == 'PorContaDoEmitente')
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('PORCONTADOEMITENTE'); ?>
																		</option>
																		<option value="PorContaDoDestinatario" <?php if ($row_transporte->modalidade == 'PorContaDoDestinatario')
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('PORCONTADODESTINATARIO'); ?>
																		</option>
																		<option value="ContratacaoPorContaDoRemetente" <?php if ($row_transporte->modalidade == 'ContratacaoPorContaDoRemetente')
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('CONTRATACAOPORCONTADOREMETENTE'); ?>
																		</option>
																		<option value="ContratacaoPorContaDoDestinario" <?php if ($row_transporte->modalidade == 'ContratacaoPorContaDoDestinario')
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('CONTRATACAOPORCONTADODESTINARIO'); ?>
																		</option>
																		<option value="ContratacaoPorContaDeTerceiros" <?php if ($row_transporte->modalidade == 'ContratacaoPorContaDeTerceiros')
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('CONTRATACAOPORCONTADETERCEIROS'); ?>
																		</option>
																		<option value="TransporteProprioPorContaDoRemetente" <?php if ($row_transporte->modalidade == 'TransporteProprioPorContaDoRemetente')
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('TRANSPORTEPROPRIOPORCONTADOREMETENTE'); ?>
																		</option>
																		<option value="TransporteProprioPorContaDoDestinatario"
																			<?php if ($row_transporte->modalidade == 'TransporteProprioPorContaDoDestinatario')
																				echo 'selected="selected"'; ?>>
																			<?php echo lang('TRANSPORTEPROPRIOPORCONTADODESTINATARIO'); ?>
																		</option>
																		<option value="SemOcorrenciaDeTransporte" <?php if ($row_transporte->modalidade == 'SemOcorrenciaDeTransporte')
																			echo 'selected="selected"'; ?>>
																			<?php echo lang('SEMOCORRENCIADETRANSPORTE'); ?>
																		</option>
																	</select>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_SAIDA_ENTRADA'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control data calendario"
																		name="dataSaidaEntrada"
																		value="<?php echo exibedata($row->dataSaidaEntrada); ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12"><br></div>

												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="col-md-12">
															<h5 class="form-section">
																<strong><?php echo lang('DADOS_DESTINATARIO'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TIPO'); ?></label>
																<div class="col-md-9">
																	<div class="md-radio-list">
																		<div class="md-radio col-md-6">
																			<input disabled type="radio" class="md-radiobtn"
																				name="tipopessoadestinatario" id="tipo_j" value="J"
																				<?php getChecked($row_transporte->tipopessoadestinatario, 'J'); ?>>
																			<label for="tipo_j">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_JURIDICA'); ?></label>
																		</div>
																		<div class="md-radio col-md-6">
																			<input disabled type="radio" class="md-radiobtn"
																				name="tipopessoadestinatario" id="tipo_f" value="F"
																				<?php getChecked($row_transporte->tipopessoadestinatario, 'F'); ?>>
																			<label for="tipo_f">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_FISICA'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CPF_CNPJ'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control cpf_cnpj"
																		name="cpfcnpjdestinatario"
																		value="<?php echo formatar_cpf_cnpj($row_transporte->cpfcnpjdestinatario); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEP'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input readonly type="text" class="form-control cep"
																			name="cep" id="cep"
																			value="<?php echo $row_transporte->cep; ?>">
																		<span class="input-group-btn">
																			<button id="cepbusca"
																				class="btn <?php echo $core->primeira_cor; ?>"
																				type="button"><i
																					class="fa fa-arrow-left fa-fw" ></i>
																				<?php echo lang('BUSCAR_END'); ?></button>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ENDERECO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="logradouro" id="endereco"
																		value="<?php echo $row_transporte->logradouro; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="numero" id="numero"
																		value="<?php echo $row_transporte->numero; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<br><br><br><br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('COMPLEMENTO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="complemento"
																		value="<?php echo $row_transporte->complemento; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('BAIRRO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="bairro" id="bairro"
																		value="<?php echo $row_transporte->bairro; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CIDADE'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="cidade" id="cidade"
																		value="<?php echo $row_transporte->cidade; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTADO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps uf"
																		name="uf" id="estado"
																		value="<?php echo $row_transporte->uf; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12"><br></div>

												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="col-md-12">
															<h5 class="form-section">
																<strong><?php echo lang('DADOS_MERCADORIAS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('QUANT_VOLUMES'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control decimalp"
																		name="quantidade"
																		value="<?php echo decimalp($row_transporte->quantidade); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESPECIE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="especie"
																		value="<?php echo $row_transporte->especie; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PESOLIQUIDO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control decimalp"
																		name="pesoliquido"
																		value="<?php echo decimalp($row_transporte->pesoliquido); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PESOBRUTO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control decimalp"
																		name="pesobruto"
																		value="<?php echo decimalp($row_transporte->pesobruto); ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12"><br></div>

												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="col-md-12">
															<h5 class="form-section">
																<strong><?php echo lang('TRANSPORTADORA_DADOS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TRANSPORTADORA'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="trans_nome"
																		value="<?php echo $row_transporte->trans_nome; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TIPO'); ?></label>
																<div class="col-md-9">
																	<div class="md-radio-list">
																		<div class="md-radio col-md-6">
																			<input disabled type="radio" class="md-radiobtn"
																				name="trans_tipopessoa" id="tipo_j" value="J" <?php getChecked($row_transporte->trans_tipopessoa, 'J'); ?>>
																			<label for="tipo_j">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_JURIDICA'); ?></label>
																		</div>
																		<div class="md-radio col-md-6">
																			<input disabled type="radio" class="md-radiobtn"
																				name="trans_tipopessoa" id="tipo_f" value="F" <?php getChecked($row_transporte->trans_tipopessoa, 'F'); ?>>
																			<label for="tipo_f">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_FISICA'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TRANS_CPF_CNPJ'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control cpf_cnpj"
																		name="trans_cpfcnpj"
																		value="<?php echo formatar_cpf_cnpj($row_transporte->trans_cpfcnpj); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('INSCRICAO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="trans_inscricaoestadual"
																		value="<?php echo $row_transporte->trans_inscricaoestadual; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PLACA_VEICULO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="veiculo_placa"
																		value="<?php echo $row_transporte->veiculo_placa; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ENDERECO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="trans_endereco"
																		value="<?php echo $row_transporte->trans_endereco; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CIDADE'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="trans_cidade"
																		value="<?php echo $row_transporte->trans_cidade; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTADO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="trans_uf"
																		value="<?php echo $row_transporte->trans_uf; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<br><br><br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('UF_VEICULO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps uf"
																		name="veiculo_uf"
																		value="<?php echo $row_transporte->veiculo_uf; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
											</div>
										<?php endif; ?>
										<div class="row">
											<?php if ($row->operacao == 1): ?>
												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section"><?php echo lang('INFORMACOES_DESPESAS'); ?>
														</h4>
													</div>
													<div class="portlet-body">
														<div class="table-scrollable table-scrollable-borderless">
															<table class="table table-hover table-light">
																<thead>
																	<tr>
																		<th><?php echo lang('VENCIMENTO'); ?></th>
																		<th><?php echo lang('BANCO'); ?></th>
																		<th><?php echo lang('DUPLICATA'); ?></th>
																		<th><?php echo lang('VALOR'); ?></th>
																		<th><?php echo lang('PAGAMENTO'); ?></th>
																		<th><?php echo lang('STATUS'); ?></th>
																		<th><?php echo lang('OPCOES'); ?></th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	$total = 0;
																	$retorno_row = $despesa->getDespesasNota(Filter::$id);
																	if ($retorno_row):
																		foreach ($retorno_row as $exrow):
																			$status = '-';
																			$estilo = '';
																			if ($exrow->pago == 0) {
																				$status = "<span class='label label-sm bg-blue'>A PAGAR</span>";
																			} elseif ($exrow->pago == 1) {
																				$status = "<span class='label label-sm bg-green'>PAGA</span>";
																			} elseif ($exrow->pago == 2) {
																				$status = "<span class='label label-sm bg-yellow'>PENDENTE</span>";
																			}
																			if ($exrow->inativo) {
																				$estilo = 'class="danger"';
																				$status = "<span class='label label-sm bg-red'>CANCELADA</span>";
																			} else {
																				$total += $exrow->valor;
																			}

																			?>
																			<tr <?php echo $estilo; ?>>
																				<td><?php echo exibedata($exrow->data_vencimento); ?></td>
																				<td><?php echo $exrow->banco; ?></td>
																				<td><?php echo $exrow->duplicata; ?></td>
																				<td><span
																						class="theme-font valor_total"><?php echo decimalp($exrow->valor); ?></span>
																				</td>
																				<td><?php echo exibedata($exrow->data_pagamento); ?></td>
																				<td><?php echo $status; ?></td>
																				<td>
																					<?php if ($exrow->pago == 2 and !$exrow->inativo): ?>
																						<a href="javascript:void(0);"
																							class="btn btn-sm yellow emdespesa"
																							id="<?php echo $exrow->id; ?>"
																							title="<?php echo lang('GERAR_DESPESA'); ?>"><i
																								class="fa fa-usd"></i></a>
																						<a href='javascript:void(0);'
																							class='btn btn-sm red apagar'
																							id='<?php echo $exrow->id; ?>' acao='apagarDespesas'
																							title='<?php echo lang('FINANCEIRO_DESPESAS_APAGAR') . $exrow->descricao; ?>'><i
																								class='fa fa-times'></i></a>
																					<?php endif; ?>
																					<?php if (!$exrow->pago): ?>
																						<a href='index.php?do=despesa&acao=editar&id=<?php echo $exrow->id; ?>'
																							class='btn btn-sm blue'
																							title='<?php echo lang('EDITAR') . ': ' . $exrow->descricao; ?>'><i
																								class='fa fa-pencil'></i></a>
																					<?php elseif ($exrow->pago and $usuario->is_Master()): ?>
																						<a href='index.php?do=despesa&acao=editarpagas&id=<?php echo $exrow->id; ?>'
																							class='btn btn-sm blue'
																							title='<?php echo lang('EDITAR') . ': ' . $exrow->descricao; ?>'><i
																								class='fa fa-pencil'></i></a>
																					<?php endif; ?>
																					<?php if (!$exrow->pago and !$exrow->inativo): ?>
																						<a href='javascript:void(0);'
																							class='btn btn-sm green pagar'
																							id='<?php echo $exrow->id; ?>'
																							id_banco='<?php echo $exrow->id_banco; ?>'
																							documento='<?php echo $exrow->nro_documento; ?>'
																							cheque='<?php echo $exrow->cheque; ?>'
																							title='<?php echo lang('PAGAR_DESPESA') . $exrow->descricao; ?>'><i
																								class='fa fa-check'></i></a>
																						<a href='javascript:void(0);'
																							class='btn btn-sm red apagar'
																							id='<?php echo $exrow->id; ?>' acao='apagarDespesas'
																							title='<?php echo lang('FINANCEIRO_DESPESAS_APAGAR') . $exrow->descricao; ?>'><i
																								class='fa fa-times'></i></a>
																					<?php endif; ?>
																				</td>
																			</tr>
																		<?php endforeach; ?>
																		<tr>
																			<td colspan="3">
																				<strong><?php echo lang('TOTAL'); ?></strong>
																			</td>
																			<td><span
																					class="bold theme-font valor_total"><?php echo decimalp($total); ?></span>
																			</td>
																			<td colspan="3"></td>
																		</tr>
																		<?php unset($exrow);
																	endif; ?>
																</tbody>
															</table>
														</div>
														<div class="caption">
															<?php if (!$row->inativo): ?>
																<a href='index.php?do=notafiscal&acao=despesa&id=<?php echo Filter::$id; ?>'
																	class='btn <?php echo $core->primeira_cor; ?>'><i
																		class='fa fa-minus-square'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_ADICIONAR'); ?></a>
															<?php endif; ?>
														</div>
													</div>
												</div>
											<?php endif;
											if ($row->operacao == 2):
												$is_boleto = false; ?>
												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section"><?php echo lang('INFORMACOES_RECEITAS'); ?></h4>
													</div>
													<div class="portlet-body">
														<div class="table-scrollable table-scrollable-borderless">
															<table class="table table-hover table-light">
																<thead>
																	<tr>
																		<th><?php echo lang('TIPO'); ?></th>
																		<th><?php echo lang('DATA_TRANSACAO'); ?></th>
																		<th><?php echo lang('BANCO'); ?></th>
																		<th><?php echo lang('VALOR'); ?></th>
																		<th><?php echo lang('VALOR_PAGO'); ?></th>
																		<th><?php echo lang('PAGAMENTO'); ?></th>
																		<th><?php echo lang('FISCAL'); ?></th>
																		<th><?php echo lang('STATUS'); ?></th>
																		<th><?php echo lang('OPCOES'); ?></th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	function geraLinhas($retorno_row, $usuario, $row)
																	{
																		$total = 0;
																		$total_pago = 0;
																		$is_boleto = false;
																		foreach ($retorno_row as $exrow):

																			$status = '-';
																			$estilo = '';
																			$id_nota = $exrow->id_nota ?? 0;

																			if ($id_nota > 0) {
																				$enviado = ($exrow->enviado) ? 'green-turquoise' : 'grey-gallery';
																				$enviado_texto = ($exrow->enviado) ? 'Boleto enviado' : 'Boleto nao enviado';
																				if ($exrow->pago == 0) {
																					$status = "<span class='label label-sm bg-blue'>A RECEBER</span>";
																				} elseif ($exrow->pago == 1) {
																					$status = "<span class='label label-sm bg-green'>RECEBIDO</span>";
																				}
																				if ($exrow->inativo) {
																					$estilo = 'class="danger"';
																					$status = '-';
																				} else {
																					$total += $exrow->valor;
																					$total_pago += ($exrow->pago) ? $exrow->valor_pago : 0;
																				}
																			} else {
																				$status = "<span class='label label-sm bg-green'>RECEBIDO</span>";
																				$total += $exrow->valor;
																				$total_pago += ($exrow->pago) ? $exrow->valor_pago : 0;
																			}

																			?>
																			<tr <?php echo $estilo; ?>>
																				<td><?php echo $exrow->pagamento; ?></td>
																				<td><?php echo exibedata($exrow->data_pagamento); ?>
																				</td>
																				<td><?php echo $exrow->banco; ?></td>
																				<td>
																					<span
																						class="theme-font valor_total"><?php echo decimalp($exrow->valor); ?>
																					</span>
																				</td>
																				<td>
																					<span
																						class="theme-font valor_total"><?php echo ($exrow->pago) ? decimalp($exrow->valor_pago) : decimalp(0); ?>
																					</span>
																				</td>
																				<td><?php echo exibedata(isset($exrow->data_fiscal) ? $exrow->data_recebido : null); ?>
																				</td>
																				<td><?php echo exibedata(isset($exrow->data_fiscal) ? $exrow->data_fiscal : null); ?>
																				</td>
																				<td><?php echo $status; ?></td>
																				<td>
																					<?php
																					if (!$row->fiscal || ($row->fiscal && $row->status_enotas == "Negada")):
																						if (!($exrow->id_venda > 0) && !$exrow->pago && !$exrow->inativo): ?>
																							<?php $modulo_boleto = getValue("modulo_emissao_boleto", "empresa", "id = " . $row->id_empresa);
																								if ($modulo_boleto == 1 && $exrow->categoria_pagamento == 4):
																									$is_boleto = true;
																									$banco_boleto = getValue("boleto_banco", "empresa", "id = " . $row->id_empresa);
																									?>
																									<a href="boleto_<?php echo $banco_boleto; ?>.php?todos=0&id_empresa=<?php echo $row->id_empresa; ?>&id_pagamento=<?php echo $exrow->id; ?>"
																										target="_blank"
																										title="<?php echo $enviado_texto; ?>"
																										class="btn btn-sm <?php echo $enviado; ?>">
																										<i class="fa fa-bold"></i>
																									</a>
																							<?php endif; ?>
																							<a href='javascript:void(0);'
																								class='btn btn-sm green pagarfinanceiro'
																								id_banco='<?php echo $exrow->id_banco; ?>'
																								valor_pago='<?php echo moedap($exrow->valor_pago); ?>'
																								id='<?php echo $exrow->id; ?>'
																								title='<?php echo lang('PAGAR') . ": " . $exrow->descricao; ?>'><i
																									class='fa fa-check'></i></a>
																							<a href='javascript:void(0);'
																								class='btn btn-sm red apagar'
																								id='<?php echo $exrow->id; ?>'
																								acao='apagarReceitaNFe'
																								title='<?php echo lang('APAGAR') . ": " . $exrow->descricao; ?>'><i
																									class='fa fa-times'></i></a>
																						<?php endif; ?>
																						<?php if (!($exrow->id_venda > 0) && (!$exrow->pago or $usuario->is_Master())): ?>
																							<a href='index.php?do=faturamento&acao=editarreceita&id_nota=<?php echo $exrow->id_nota; ?>&id=<?php echo $exrow->id; ?>'
																								class='btn btn-sm blue'
																								title='<?php echo lang('EDITAR') . ': ' . $exrow->descricao; ?>'><i
																									class='fa fa-pencil'></i></a>
																						<?php endif;
																					endif; ?>
																				</td>
																			</tr>
																		<?php endforeach; ?>
																		<?php unset($exrow);
																		return ['total_pago' => $total_pago > 0 ? $total_pago : 0, 'is_boleto' => $is_boleto];
																	}

																	$total_pago = 0;
																	$is_boleto = false;

																	$retorno_row = $faturamento->getReceitasNota(Filter::$id);
																	if ($retorno_row):
																		$resultado = geraLinhas($retorno_row, $usuario, $row);
																		$total_pago += $resultado['total_pago'];
																		$is_boleto = $is_boleto || $resultado['is_boleto'];
																	endif;

																	$retorno_row = $faturamento->getFinanceiroNota(Filter::$id);
																	if ($retorno_row):
																		$resultado = geraLinhas($retorno_row, $usuario, $row);
																		$total_pago += $resultado['total_pago'];
																	endif;

																	?>
																	<tr>
																		<td colspan="4">
																			<strong><?php echo lang('TOTAL_PAGO'); ?></strong>
																		</td>
																		<td><span
																				class="bold theme-font valor_total"><?php echo decimalp($total_pago); ?></span>
																		</td>
																		<td colspan="4"></td>
																	</tr>
																</tbody>
															</table>
														</div>

														<div class="caption">
															<?php if (!$row->inativo): ?>
																<?php if ($row->status_enotas != "Autorizada" && $row->status_enotas != "CancelamentoNegado"): ?>
																	<a href='index.php?do=notafiscal&acao=editar&id=<?php echo Filter::$id; ?>'
																		class='btn btn-info <?php echo $core->terceira_cor; ?>'><i
																			class='fa fa-pencil'>&nbsp;&nbsp;</i><?php echo lang('NOTA_EDITAR'); ?></a>
																	<?php if (!$row->id_venda): ?>
																		<a href='index.php?do=notafiscal&acao=receita&id=<?php echo Filter::$id; ?>'
																			class='btn <?php echo $core->primeira_cor; ?>'><i
																				class='fa fa-plus-square'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_ADICIONAR_RECEITA'); ?></a>
																	<?php endif; ?>
																<?php endif; ?>

																<?php if (!$row->id_venda && $row->modelo!=3): ?>
																	<a href="javascript:void(0);"
																		title="<?php echo lang('NOTA_DUPLICAR'); ?>"
																		id="<?php echo Filter::$id; ?>"
																		class="btn blue-chambray duplicar_nfe"><i
																			class="fa fa-copy">&nbsp;&nbsp;</i><?php echo lang('NOTA_DUPLICAR'); ?></a>
																<?php endif; ?>

																<?php $modulo_boleto = getValue("modulo_emissao_boleto", "empresa", "id = " . $row->id_empresa);
																	if ($modulo_boleto && $is_boleto):
																		$banco_boleto = getValue("boleto_banco", "empresa", "id = " . $row->id_empresa); ?>
																		<a href="boleto_<?php echo $banco_boleto; ?>.php?todos=1&id_empresa=<?php echo $row->id_empresa; ?>&id_pagamento=0&id_nota=<?php echo Filter::$id; ?>"
																			target="_blank" title="<?php echo lang('GERAR_TODOS'); ?>"
																			class="btn grey-cascade">
																			<i class="fa fa-bold">&nbsp;&nbsp;</i>
																			<?php echo lang('GERAR_TODOS'); ?>
																		</a>
																		<a href="javascript:void(0);" class="btn blue emailboleto"
																			id="<?php echo Filter::$id; ?>">
																			<i class="fa fa-send-o">&nbsp;&nbsp;</i>
																			<?php echo lang('FINANCEIRO_BOLETOS_ENVIAR'); ?>
																		</a>
																<?php endif; ?>

																<?php if (!$row->numero_nota && $row->modelo!=3): ?>
																	<button type="button" data-idnota="<?php echo Filter::$id; ?>"
																		title="<?php echo lang('ENOTAS_GERAR'); ?>"
																		class="btn purple gerar_nota_fiscal" type="button"><i
																			class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_GERAR'); ?></button>
																	<?php if ($usuario->is_Controller()): ?>
																		<button type="button" data-controller='1'
																			data-idnota="<?php echo Filter::$id; ?>"
																			title="<?php echo lang('ENOTAS_GERAR'); ?>"
																			class="btn purple gerar_nota_fiscal" type="button"><i
																				class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_GERAR') . " [DEBUG]"; ?></button>
																	<?php endif; ?>
																<?php endif; ?>

																<?php if ($row->modelo!=3 && $row->fiscal && ($row->status_enotas == "Autorizada" || $row->status_enotas == "CancelamentoNegado")): ?>
																	<a href='index.php?do=notafiscal&acao=carta&id_nota=<?php echo Filter::$id; ?>'
																		title="<?php echo lang('NOTA_CARTA'); ?>" class="btn yellow"><i
																			class="fa fa-file-text">&nbsp;&nbsp;</i><?php echo lang('NOTA_CARTA'); ?></a>

																	<?php if (!$row->link_danfe): ?>
																		<a href="nfe_download.php?id=<?php echo Filter::$id; ?>" target="_blank"
																			title="<?php echo lang('ENOTAS_PDF'); ?>"
																			class="btn green-jungle"><i
																				class="fa fa-file-pdf-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_PDF'); ?></a>
																		<a href="nfe_xml.php?id=<?php echo Filter::$id; ?>" target="_blank"
																			title="<?php echo lang('ENOTAS_XML'); ?>"
																			class="btn green-turquoise"><i
																				class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_XML'); ?></a>
																	<?php else: ?>
																		<a href="<?php echo str_replace("http://", "https://", $row->link_danfe); ?>"
																			title="<?php echo lang('ENOTAS_PDF'); ?>"
																			class="btn green-jungle"><i
																				class="fa fa-file-pdf-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_PDF'); ?></a>
																		<a href="<?php echo str_replace("http://", "https://", $row->link_download_xml); ?>"
																			title="<?php echo lang('ENOTAS_XML'); ?>"
																			class="btn green-turquoise"><i
																				class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_XML'); ?></a>
																	<?php endif; ?>
																<?php endif; ?>

																<?php if ($row->modelo!=3 && $row->fiscal && ($row->status_enotas == "Autorizada")): ?>
																	<?php
																	$dentroPrazoCancelamento = !(strtotime($row->data_emissao . ' +1440 minutes') < strtotime(date('Y-m-d H:i:s')));
																	$dentroPrazoExtemporaneo = !(strtotime($row->data_emissao . ' +28800 minutes') < strtotime(date('Y-m-d H:i:s')));
																	if ($dentroPrazoCancelamento):
																		?>
																		<a href="javascript:void(0);"
																			title="<?php echo lang('ENOTAS_CANCELAR'); ?>"
																			id="<?php echo Filter::$id; ?>" class="btn red cancelar_nota"><i
																				class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_CANCELAR'); ?></a>
																		<?php
																	elseif ($dentroPrazoExtemporaneo):
																		if ($row->cancelamento_extemporaneo):
																			//exibe botao para cancelar a NF
																			?>
																			<a href="javascript:void(0);"
																				title="<?php echo lang('ENOTAS_CANCELAR_EXTEMPORANEO'); ?>"
																				id="<?php echo Filter::$id; ?>"
																				class="btn red-thunderbird cancelar_nota"><i
																					class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_CANCELAR_EXTEMPORANEO'); ?></a>
																			<?php
																		else:
																			//exibe botao para autorizar (somente no sistema) o cancelamento extemporanoe
																			?>
																			<a href="javascript:void(0);"
																				title="<?php echo lang('ENOTAS_PERMITIR_EXTEMPORANEO'); ?>"
																				id="<?php echo Filter::$id; ?>"
																				class="btn red-pink permitir_cancelar_extemporaneo"><i
																					class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_PERMITIR_EXTEMPORANEO'); ?></a>
																			<?php
																		endif;
																	endif;

																	?>
																<?php endif; ?>
																<?php if ($row->fiscal && $row->modelo!=3): ?>
																	<?php
																	$estilo_status = ($row->status_enotas == "Autorizada") ? ((!$row->contingencia) ? "badge bg-green" : "badge bg-blue-chambray") : (($row->status_enotas == "Negada") ? "badge bg-red" : (($row->status_enotas == "Inutilizada") ? "badge bg-blue-hoki" : (($row->status_enotas == "Cancelada" || $row->inativo) ? "badge bg-gray" : "badge bg-yellow")));
																	?>

																	<br><br><br>
																	<span
																		class="<?php echo $estilo_status; ?>"><?php echo $row->status_enotas; ?></span>
																	<?php echo $row->motivo_status; ?>
																	<br><br>
																	<?php if ($row->modelo!=3 && $row->fiscal && $row->status_enotas != "Autorizada" && $row->status_enotas != "Cancelada" && $row->status_enotas != "CancelamentoNegado"): ?>
																		<button data-idnota="<?php echo Filter::$id; ?>"
																			title="<?php echo lang('ENOTAS_REPROCESSAR'); ?>"
																			class="btn purple gerar_nota_fiscal" type="button"><i
																				class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_REPROCESSAR'); ?></button>
																		<?php if ($usuario->is_Controller()): ?>
																			<button data-idnota="<?php echo Filter::$id; ?>" data-controller="1"
																				title="<?php echo lang('ENOTAS_REPROCESSAR'); ?>"
																				class="btn purple gerar_nota_fiscal" type="button"><i
																					class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_REPROCESSAR') . " [DEBUG]"; ?></button>
																		<?php endif; ?>
																		<br><br>
																	<?php endif; ?>

																<?php endif; ?>
															<?php elseif ($row->fiscal && $row->modelo!=3): ?>
																<?php if ($row->link_danfe): ?>
																	<a href="<?php echo str_replace("http://", "https://", $row->link_danfe); ?>"
																		title="<?php echo lang('ENOTAS_PDF'); ?>"
																		class="btn green-jungle"><i
																			class="fa fa-file-pdf-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_PDF'); ?></a>
																<?php endif; ?>
																<?php if ($row->link_download_xml): ?>
																	<a href="<?php echo str_replace("http://", "https://", $row->link_download_xml); ?>"
																		title="<?php echo lang('ENOTAS_XML'); ?>"
																		class="btn green-turquoise"><i
																			class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_XML'); ?></a>
																<?php endif; ?>
																<?php if ($usuario->is_Controller()): ?>
																	<a href="nfe_consultar.php?id=<?php echo Filter::$id; ?>"
																		target="_blank" title="<?php echo lang('ENOTAS_CONSULTAR'); ?>"
																		class="btn grey-cascade"><i
																			class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_CONSULTAR'); ?></a>
																<?php endif; ?>
															<?php endif; ?>
															<?php if($row->modelo == 3): ?>
																	<a href="javascript:void(0);" class="btn grey-gallery" onclick="javascript:void window.open('pdf_fatura.php?id=<?php echo $row->id;?>','<?php echo lang('FATURA_VISUALIZAR');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('FATURA_VISUALIZAR');?></a>
															<?php endif; ?>
														</div>
													</div>
												<?php endif; ?>

												<?php if ($row->modelo == 2): ?>
													<div class="col-md-12">
														<div class="col-md-12">
															<br />
														</div>
														<div class="col-md-12">
															<h4 class="form-section"><?php echo lang('INFORMACOES_PRODUTOS'); ?>
															</h4>
														</div>
														<div class="portlet-body">
															<div class="table-scrollable table-scrollable-borderless">
																<table class="table table-light">
																	<thead>
																		<tr>
																			<th><?php echo lang('PRODUTO'); ?></th>
																			<th><?php echo lang('CFOP'); ?></th>
																			<th><?php echo lang('CFOP_ENTRADA'); ?></th>
																			<th><?php echo lang('NCM'); ?></th>
																			<th><?php echo lang('CSOSN_CST'); ?></th>
																			<?php
																			if ($numero_pedido_compra):
																				?>
																				<th><?php echo lang('ITEM_PEDIDO_COMPRA_INFO'); ?></th>
																				<?php
																			endif;
																			?>
																			<th><?php echo lang('UNIDADE'); ?></th>
																			<th><?php echo lang('QUANT'); ?></th>
																			<th><?php echo lang('VALOR'); ?></th>
																			<th><?php echo lang('VL_DESC'); ?></th>
																			<th><?php echo lang('VL_ACRESC'); ?></th>
																			<th><?php echo lang('VL_TRIB'); ?></th>
																			<th><?php echo lang('VL_TOTAL'); ?></th>
																			<th width="100px"><?php echo lang('ACOES'); ?></th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		$quantidade = 0;
																		$unitario = 0;
																		$desconto = 0;
																		$acresicmo = 0;
																		$icms = 0;
																		$trib = 0;
																		$total = 0;
																		$retorno_row = $produto->getProdutosNota(Filter::$id);
																		if ($retorno_row):
																			foreach ($retorno_row as $exrow):
																				$estilo = ($exrow->id_produto) ? 'class="info"' : 'class="danger"';
																				$nome_produto = ($exrow->id_produto) ? $exrow->nome : $exrow->nome_fornecedor;
																				$quantidade += $exrow->quantidade;
																				$unitario += $exrow->valor_unitario;
																				$desconto += $exrow->valor_desconto;
																				$acresicmo += $exrow->outrasDespesasAcessorias;
																				$icms += $exrow->icms_valor;
																				$trib += $exrow->valor_total_trib;
																				$total += $exrow->valor_total;
																				$valor_partida = getValue("valor_partida", "nota_fiscal_itens", "id_nota = " . Filter::$id);
																				?>
																				<tr <?php echo $estilo; ?>>
																					<td>
																						<?php if ($exrow->id_produto): ?>
																							<a
																								href="index.php?do=produto&acao=editar&id=<?php echo $exrow->id_produto; ?>"><?php echo $nome_produto; ?></a>
																						<?php else: ?>
																							<?php echo $nome_produto; ?>
																						<?php endif; ?>
																					</td>
																					<td><?php echo ($exrow->cfop) ? $exrow->cfop : $exrow->cfop_produto; ?>
																					</td>
																					<td><?php echo ($exrow->cfop_entrada) ? $exrow->cfop_entrada : '0000'; ?>
																					</td>
																					<td><?php echo ($exrow->ncm) ? $exrow->ncm : $exrow->ncm_produto; ?>
																					</td>
																					<td><?php echo ($exrow->icms_cst) ? $exrow->icms_cst : $exrow->cst_produto; ?>
																					</td>
																					<?php
																					if ($numero_pedido_compra):
																						?>
																						<td><?php echo $exrow->item_pedido_compra; ?></td>
																						<?php
																					endif;
																					?>
																					<td><?php echo ($exrow->unidade) ? $exrow->unidade : $exrow->unidade_produto; ?>
																					</td>
																					<td><?php echo decimalp($exrow->quantidade); ?></td>
																					<td><?php echo decimalp($exrow->valor_unitario); ?></td>
																					<td><?php echo decimalp($exrow->valor_desconto); ?></td>
																					<td><?php echo decimalp($exrow->outrasDespesasAcessorias); ?>
																					</td>
																					<td><?php echo decimalp($exrow->valor_total_trib); ?>
																					</td>
																					<td><span
																							class="bold theme-font valor_total"><?php echo decimalp($exrow->valor_total); ?></span>
																					</td>
																					<td>
																						<?php if (!$exrow->id_produto): ?>

																							<a href="javascript:void(0);"
																								class="btn btn-sm blue-steel novoproduto"
																								id="<?php echo $exrow->id_produto_fornecedor; ?>"
																								id_nf_itens="<?php echo $exrow->id; ?>"
																								cfop="<?php echo $exrow->cfop; ?>"
																								ncm_nf="<?php echo $exrow->ncm; ?>"
																								cest_nf="<?php echo $exrow->cest; ?>"
																								csosn_cst="<?php echo $exrow->icms_cst; ?>"
																								cod_anp="<?php echo $exrow->cod_anp; ?>"
																								valor_partida="<?php echo $valor_partida; ?>"
																								codigobarras="<?php echo $exrow->codigobarras; ?>"
																								title="<?php echo lang('NOVO_PRODUTO'); ?>"><i
																									class="fa fa-plus"></i></a>
																							<a href="javascript:void(0);"
																								class="btn btn-sm blue-hoki combinarproduto"
																								id="<?php echo $exrow->id_produto_fornecedor; ?>"
																								id_nota="<?php echo $exrow->id_nota; ?>"
																								id_nf_itens="<?php echo $exrow->id; ?>"
																								title="<?php echo lang('COMBINAR_PRODUTO'); ?>"><i
																									class="fa fa-link"></i></a>
																						<?php elseif ($row->operacao == 1): ?>
																							<a href="javascript:void(0);"
																								class="btn btn-sm blue-steel editarInfoProduto"
																								id="<?php echo $exrow->id; ?>"
																								codigobarras="<?php echo $exrow->codigobarras; ?>"
																								cfopsaida="<?php echo ($exrow->cfop) ? $exrow->cfop : $exrow->cfop_produto; ?>"
																								cfopentrada="<?php echo ($exrow->cfop_entrada) ? $exrow->cfop_entrada : '0000'; ?>"
																								title="<?php echo lang('EDITAR') . ': ' . $nome_produto; ?>"><i
																									class="fa fa-pencil"></i></a>
																						<?php endif; ?>
																					</td>
																				</tr>
																			<?php endforeach; ?>
																			<tr>
																				<td
																					colspan="<?php echo ($numero_pedido_compra) ? 7 : 6; ?>">
																					<strong><?php echo lang('TOTAL'); ?></strong>
																				</td>
																				<td><strong><?php echo decimalp($quantidade); ?></strong>
																				</td>
																				<td><strong><?php echo decimalp($unitario); ?></strong>
																				</td>
																				<td><strong><?php echo decimalp($desconto); ?></strong>
																				</td>
																				<td><strong><?php echo decimalp($acresicmo); ?></strong>
																				</td>
																				<td><strong><?php echo decimalp($trib); ?></strong></td>
																				<td><span
																						class="bold theme-font valor_total"><?php echo decimalp($total); ?></span>
																				</td>
																				<td></td>
																			</tr>
																			<?php unset($exrow);
																		endif; ?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												<?php endif; ?>
											</div>
										</div>
										<br><br>
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<?php if (!$row->inativo and $row->chaveacesso and !$devolucao): ?>
																	<a href='index.php?do=notafiscal&acao=devolucao&id=<?php echo Filter::$id; ?>'
																		class='btn yellow'><i
																			class='fa fa-repeat'>&nbsp;&nbsp;</i><?php echo lang('ENOTAS_DEVOLUCAO'); ?></a>
																<?php endif; ?>
																<button type="button" id="voltar"
																	class="btn default"><?php echo lang('VOLTAR'); ?></button>
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
		<!-- INICIO BOX MODAL -->
		<div id='pagar-despesa' class='modal fade' tabindex='-1'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
						<h4 class='modal-title'><?php echo lang('PAGAR_DESPESA'); ?></h4>
					</div>
					<form action='' autocomplete="off" method='post' name='despesa_form' id='despesa_form'>
						<div class='modal-body'>
							<div class='row'>
								<div class='col-md-12'>
									<p><?php echo lang('BANCO'); ?></p>
									<p>
										<select class='select2me form-control' id='id_banco' name='id_banco'
											data-placeholder='<?php echo lang('SELECIONE_OPCAO'); ?>'>
											<option value=""></option>
											<?php
											$retorno_row = $faturamento->getBancos();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value='<?php echo $srow->id; ?>'><?php echo $srow->banco; ?></option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</p>
									<p><?php echo lang('DATA_PAGAMENTO'); ?></p>
									<p>
										<input type='text' class='form-control data calendario' name='data_pagamento'
											value='<?php echo date("d/m/Y"); ?>'>
									</p>
									<p><?php echo lang('NRO_DOCUMENTO'); ?></p>
									<p>
										<input type='text' class='form-control caps' id='documento' name='nro_documento'>
									</p>
									<p><?php echo lang('VALOR_PAGO'); ?></p>
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
												<?php echo lang('FINANCEIRO_CHEQUE'); ?></label>
										</div>
									</div>
									</p>
								</div>
							</div>
						</div>
						<div class='modal-footer'>
							<button type='submit'
								class='btn <?php echo $core->primeira_cor; ?>'><?php echo lang('SALVAR'); ?></button>
							<button type='button' data-dismiss='modal'
								class='btn default'><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm('processarPagamentoDespesas', 'despesa_form'); ?>
		</div>
		<div id='pagar-receita' class='modal fade' tabindex='-1'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
						<h4 class='modal-title'><?php echo lang('PAGAR'); ?></h4>
					</div>
					<form action='' autocomplete="off" method='post' name='pagar_form' id='pagar_form'>
						<div class='modal-body'>
							<div class='row'>
								<div class='col-md-12'>
									<p><?php echo lang('BANCO'); ?></p>
									<p>
										<select class='select2me form-control' id='id_banco2' name='id_banco'
											data-placeholder='<?php echo lang('SELECIONE_OPCAO'); ?>'>
											<option value=""></option>
											<?php
											$retorno_row = $faturamento->getBancos();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value='<?php echo $srow->id; ?>'><?php echo $srow->banco; ?></option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</p>
									<p><?php echo lang('VALOR_PAGO'); ?></p>
									<p>
										<input type='text' class='form-control moedap' name='valor_pago' id='valor_pago2'>
									</p>
									<p><?php echo lang('DATA_PAGAMENTO'); ?></p>
									<p>
										<input type='text' class='form-control data calendario' name='data_recebido'
											value='<?php echo date("d/m/Y"); ?>'>
									</p>
									<br />
									<p>
									<div class='md-checkbox-list'>
										<div class='md-checkbox'>
											<input type='checkbox' class='md-check' name='novareceita' id='novareceita'
												value='1'>
											<label for='novareceita'>
												<span></span>
												<span class='check'></span>
												<span class='box'></span>
												<?php echo lang('FINANCEIRO_RECEITAGERAR'); ?></label>
										</div>
									</div>
									</p>
								</div>
							</div>
						</div>
						<div class='modal-footer'>
							<button type='submit'
								class='btn <?php echo $core->primeira_cor; ?>'><?php echo lang('SALVAR'); ?></button>
							<button type='button' data-dismiss='modal'
								class='btn default'><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm('pagarFinanceiro', 'pagar_form'); ?>
			<div id="overlay">
				<span class="loader"></span>
			</div>
		</div>
		<?php break; ?>
	<?php
	case "adicionar":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$row_empresa = Core::getRowById("empresa", $usuario->idempresa);
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_ADICIONAR'); ?></small></h1>
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
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('NOTA_ADICIONAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('EMPRESA'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="id_empresa"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $empresa->getEmpresas();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>">
																					<?php echo $srow->nome; ?>
																				</option>
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
																<label
																	class='control-label col-md-3'><?php echo lang('NOME'); ?></label>
																<div class='col-md-9'>
																	<input name="id_cadastro" id="id_cadastro" type="hidden" />
																	<input type="text" autocomplete="off"
																		class="form-control caps listar_cadastro"
																		name="cadastro"
																		placeholder="<?php echo lang('BUSCAR'); ?>">
																</div>
															</div>
														</div>
														<div class="row selecionado ocultar">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<span
																		class="label label-success label-sm"><?php echo lang('SELECIONADO'); ?></span>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CFOP'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="cfop" id="select_cfop_nfe"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getCFOP_Todos();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->cfop; ?>" descricao="<?php echo $srow->descricao; ?>">
																					<?php echo $srow->cfop . " - " . $srow->descricao; ?>
																				</option>
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
																<label
																	class="control-label col-md-3"><?php echo lang('NATUREZA_OPERACAO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="natureza_operacao_nfe" id="natureza_operacao_nfe" maxlength="60">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO_PEDIDO_COMPRA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="numero_pedido_compra">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12"><br></div>

												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section"><strong><?php echo lang('EXPORTACAO_INFORMACOES');?></strong></h4>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check"
																				name="nf_exportacao" id="nf_exportacao"
																				value="1">
																			<label for="nf_exportacao">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('NOTA_FISCAL_EXPORTACAO'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NOTA_FISCAL_EXPORTACAO_PAIS'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control"
																		name="pais_exportacao"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getPaisesIbge();
																		if ($retorno_row):
																			foreach ($retorno_row as $prow):
																				?>
																				<option value="<?php echo $prow->codigo; ?>">
																					<?php echo $prow->pais; ?>
																				</option>
																				<?php
																			endforeach;
																			unset($prow);
																		endif;
																		?>
																	</select>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-12">
													<h4 class="form-section"></h4>
												</div>

												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS_ST'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">%</span>
																		<input type="text" class="form-control decimal"
																			name="icms_st_aliquota"
																			value="<?php echo decimal($row_empresa->icms_st_aliquota); ?>">
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">%</span>
																		<input type="text" class="form-control decimal"
																			name="icms_normal_aliquota"
																			value="<?php echo decimal($row_empresa->icms_normal_aliquota); ?>">
																		</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_MVA'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">%</span>
																		<input type="text" class="form-control decimal" name="mva"
																			value="<?php echo decimal($row_empresa->mva); ?>">
																		</div>

																</div>
															</div>
														</div>
													</div>
													<!--col-md-6-->
													<!--/col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_SEGURO'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">R$</span>
																		<input type="text" class="form-control decimalp"
																			name="valor_seguro">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<div class="col-md-12">
														<h4 class="form-section"></h4>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check"
																				name="apresentar_duplicatas"
																				id="apresentar_duplicatas" value="1">
																			<label for="apresentar_duplicatas">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('NOTA_FISCAL_DUPLICATAS'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--col-md-6-->
													<!--/col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_VENCIMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control data calendario"
																		name="data_vencimento">
																	<span
																		class="help-block"><?php echo lang('DATA_VENCIMENTO_REGRA'); ?></span>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<h4 class="form-section"></h4>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><strong><?php echo lang('DISCRIMINACAO'); ?></strong></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea class="form-control"
																		name="descriminacao"></textarea>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><strong><?php echo lang('INF_ADICIONAIS'); ?></strong></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea class="form-control"
																		name="inf_adicionais"></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12"><br></div>
												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section">
															<strong><?php echo lang('INFORMACOES_TRANSPORTE'); ?></strong>
														</h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TRANSPORTE'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="modalidade"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""><?php echo lang('SEM_TRANSPORTE'); ?>
																		</option>
																		<option value="SemFrete"><?php echo lang('SEMFRETE'); ?>
																		</option>
																		<option value="PorContaDoEmitente">
																			<?php echo lang('PORCONTADOEMITENTE'); ?>
																		</option>
																		<option value="PorContaDoDestinatario">
																			<?php echo lang('PORCONTADODESTINATARIO'); ?>
																		</option>
																		<option value="ContratacaoPorContaDoRemetente">
																			<?php echo lang('CONTRATACAOPORCONTADOREMETENTE'); ?>
																		</option>
																		<option value="ContratacaoPorContaDoDestinario">
																			<?php echo lang('CONTRATACAOPORCONTADODESTINARIO'); ?>
																		</option>
																		<option value="ContratacaoPorContaDeTerceiros">
																			<?php echo lang('CONTRATACAOPORCONTADETERCEIROS'); ?>
																		</option>
																		<option value="TransporteProprioPorContaDoRemetente">
																			<?php echo lang('TRANSPORTEPROPRIOPORCONTADOREMETENTE'); ?>
																		</option>
																		<option value="TransporteProprioPorContaDoDestinatario">
																			<?php echo lang('TRANSPORTEPROPRIOPORCONTADODESTINATARIO'); ?>
																		</option>
																		<option value="SemOcorrenciaDeTransporte">
																			<?php echo lang('SEMOCORRENCIADETRANSPORTE'); ?>
																		</option>
																	</select>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_FRETE'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">R$</span>
																		<input type="text" class="form-control decimalp"
																			name="valor_frete">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_SAIDA_ENTRADA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control data calendario"
																		name="dataSaidaEntrada">
																	<span
																		class="help-block"><?php echo lang('DATA_SAIDA_ENTRADA_REGRA'); ?></span>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12"><br><br></div>
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="col-md-12">
															<h5 class="form-section">
																<strong><?php echo lang('EMPRESA_DESTINO_DADOS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TIPO'); ?></label>
																<div class="col-md-9">
																	<div class="md-radio-list">
																		<div class="md-radio col-md-6">
																			<input type="radio" class="md-radiobtn"
																				name="tipopessoadestinatario" id="tipo_j"
																				value="J">
																			<label for="tipo_j">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_JURIDICA'); ?></label>
																		</div>
																		<div class="md-radio col-md-6">
																			<input type="radio" class="md-radiobtn"
																				name="tipopessoadestinatario" id="tipo_f"
																				value="F">
																			<label for="tipo_f">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_FISICA'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CPF_CNPJ'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cpf_cnpj"
																			name="cpfcnpjdestinatario" id="cpfcnpjdestinatario">
																		<span
																			class="input-group-addon"><?php echo lang('TECLE_ENTER') ?></span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEP'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cep" name="cep"
																			id="cep">
																		<span class="input-group-btn">
																			<button id="cepbusca"
																				class="btn <?php echo $core->primeira_cor; ?>"
																				type="button"><i
																					class="fa fa-arrow-left fa-fw"></i>
																				<?php echo lang('BUSCAR_END'); ?></button>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ENDERECO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="logradouro" id="endereco">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="numero"
																		id="numero">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<br><br><br><br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('COMPLEMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="complemento" id="complemento">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('BAIRRO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="bairro"
																		id="bairro">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="cidade"
																		id="cidade">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTADO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps uf" name="uf"
																		id="estado" maxlength="2">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12"><br><br></div>
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="col-md-12">
															<h5 class="form-section">
																<strong><?php echo lang('DADOS_MERCADORIAS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('QUANT_VOLUMES'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="quantidade">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESPECIE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="especie">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PESOLIQUIDO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="pesoliquido">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PESOBRUTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="pesobruto">
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>
													<div class="col-md-12"><br><br></div>
													<div class="col-md-12">
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="col-md-12">
																<h5 class="form-section">
																	<strong><?php echo lang('TRANSPORTADORA_DADOS'); ?></strong>
																</h5>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('TRANSPORTADORA'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="trans_nome" id="trans_nome">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('TIPO'); ?></label>
																	<div class="col-md-9">
																		<div class="md-radio-list">
																			<div class="md-radio col-md-6">
																				<input type="radio" class="md-radiobtn"
																					name="trans_tipopessoa" id="tipo_j_trans"
																					value="J">
																				<label for="tipo_j_trans">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PESSOA_JURIDICA'); ?></label>
																			</div>
																			<div class="md-radio col-md-6">
																				<input type="radio" class="md-radiobtn"
																					name="trans_tipopessoa" id="tipo_f_trans"
																					value="F">
																				<label for="tipo_f_trans">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PESSOA_FISICA'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('TRANS_CPF_CNPJ'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<input type="text" class="form-control cpf_cnpj"
																				name="trans_cpfcnpj" id="trans_cpfcnpj">
																			<span class="input-group-addon">
																				<?php echo lang('TECLE_ENTER') ?>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('INSCRICAO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="trans_inscricaoestadual" id="trans_inscricaoestadual">
																	</div>
																</div>
															</div>
															<div class="row">
																<br><br><br>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('PLACA_VEICULO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="veiculo_placa" maxlength="7">
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<br><br><br>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CEP'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<input type="text" class="form-control cep"
																				name="trans_cep" id="trans_cep">
																			<span class="input-group-btn">
																				<button id="ceptransportadora"
																					class="btn <?php echo $core->primeira_cor; ?>"
																					type="button"><i
																						class="fa fa-arrow-left fa-fw"></i>
																					<?php echo lang('BUSCAR_END'); ?></button>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ENDERECO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="trans_endereco" id="trans_endereco">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CIDADE'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="trans_cidade" id="trans_cidade">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ESTADO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="trans_uf" id="trans_uf" maxlength="2">
																	</div>
																</div>
															</div>
															<div class="row">
																<br><br><br>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('UF_VEICULO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="veiculo_uf" maxlength="2">
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>
													<div class="col-md-12"><br></div>
													<div class="col-md-12">
														<div class="col-md-12">
															<h4 class="form-section">
																<strong><?php echo lang('INFORMACOES_PRODUTOS'); ?></strong>
															</h4>
														</div>
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
																	<div class="col-md-9">
																		<select
																			class="select2me form-control produto_notafiscal"
																			name="sel_produto" id="sel_produto"
																			data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $produto->getProdutos();
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->id; ?>"
																						estoque="<?php echo $srow->estoque; ?>"
																						validaestoque="<?php echo $srow->valida_estoque; ?>">
																						<?php echo $srow->nome . " - Cod.: " . $srow->codigo . " - " . $srow->codigobarras; ?>
																					</option>
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
																	<label
																		class="control-label col-md-3"><?php echo lang('PRODUTO_TIPO_ITEM'); ?></label>
																	<div class="col-md-9">
																		<select class="select2me form-control"
																			name="sel_tipo_item" id="sel_tipo_item"
																			data-placeholder="<?php echo lang('SELECIONE_TIPO_ITEM'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $produto->getTipoItemProduto();
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->codigo; ?>" <?php if ($srow->codigo == '00')
																						   echo 'selected="selected"'; ?>>
																						<?php echo $srow->codigo . " - " . $srow->descricao; ?>
																					</option>
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
																	<label
																		class="control-label col-md-3"><?php echo lang('QUANTIDADE'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control decimalp"
																			name="sel_quant" id="sel_quant">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_UNIDADE'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_valor" id="sel_valor">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_DESCONTO'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_desconto" id="sel_desconto">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('DESPESAS_ACESSORIAS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_despesas" id="sel_despesas">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
														<!--col-md-5-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ITEM_PEDIDO_COMPRA'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_item_pedido_compra"
																			id="sel_item_pedido_compra">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CFOP'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_cfop" id="sel_cfop" maxlength="4">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CSOSN_CST'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_cst" id="sel_cst" maxlength="4">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('NCM'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_ncm" id="sel_ncm" maxlength="8">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CEST'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_cest" id="sel_cest" maxlength="7">
																		<span
																			class="help-block"><?php echo lang('OBS_CEST'); ?></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="col-md-6">
															<h5 class="form-section">
																<strong><?php echo lang('TRIBUTOS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<br><br>
														</div>
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_ICMS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_icms_base" id="sel_icms_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_icms" id="sel_icms">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_ST'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_st_base" id="sel_st_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS_ST'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_st_percentual" id="sel_st_percentual">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_MVA'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_mva" id="sel_mva">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-4-->
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CST_PIS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">Cód</span>
																			<input type="text" class="form-control inteiro"
																				name="sel_pis_cst" id="sel_pis_cst">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_PIS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_pis_base" id="sel_pis_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_PIS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_pis" id="sel_pis">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CST_COFINS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">Cód</span>
																			<input type="text" class="form-control inteiro"
																				name="sel_cofins_cst" id="sel_cofins_cst">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_COFINS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_cofins_base" id="sel_cofins_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_COFINS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_cofins" id="sel_cofins">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-4-->
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CST_IPI'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">Cód</span>
																			<input type="text" class="form-control inteiro"
																				name="sel_ipi_cst" id="sel_ipi_cst">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_IPI'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_ipi_base" id="sel_ipi_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_CST_IPI'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_ipi" id="sel_ipi">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div class="row">
																<div class="form-group">
																	<div class="col-md-3">
																		<a href="javascript:void(0);"
																			class="btn green adicionar_produto"
																			title="<?php echo lang('PRODUTO_ADICIONAR'); ?>"><i
																				class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_ADICIONAR'); ?></a>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="portlet-body">
															<div class="table-scrollable table-scrollable-borderless">
																<table class="table table-hover table-light">
																	<thead>
																		<tr>
																			<th><?php echo lang('PRODUTO'); ?></th>
																			<th><?php echo lang('QUANT'); ?></th>
																			<th><?php echo lang('VL_TOTAL'); ?></th>
																			<th><?php echo lang('CFOP'); ?></th>
																			<th><?php echo lang('CSOSN_CST'); ?></th>
																			<th><?php echo lang('NCM'); ?></th>
																			<th><?php echo lang('CEST'); ?></th>
																			<th><?php echo lang('VL_DESC'); ?></th>
																			<th><?php echo lang('VL_ACRESC'); ?></th>
																			<th><?php echo lang('VL_ICMS'); ?></th>
																			<th><?php echo lang('VL_ST'); ?></th>
																			<th><?php echo lang('ITEM_PEDIDO_COMPRA'); ?></th>
																			<th></th>
																		</tr>
																	</thead>
																	<tbody id="tabela_produtos">
																	</tbody>
																</table>
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
																	<button type="button"
																		class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR_NOTA'); ?></button>
																	<button type="button" id="voltar"
																		class="btn default"><?php echo lang('VOLTAR'); ?></button>
																</div>
															</div>
														</div>
														<div class="col-md-6">
														</div>
													</div>
												</div>
											</div>
									</form>
									<?php echo $core->doForm("processarNotaFiscal"); ?>
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
		<?php break; ?>
	<?php
	case "adicionar_servico":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$mes = date('m');
		$ano = date('Y');
		$mes = $mes - 1;
		if ($mes == 12) {
			$ano = $ano - 1;
		}
		$mes_ano = $mes . '/' . $ano; //exibeMesAnoContrato($mes,$ano);
		$id_nota = getValue('id', 'nota_fiscal', 'id_financeiro=' . Filter::$id);
		if ($id_nota) {
			redirecionar("index.php?do=notafiscal&acao=visualizar_servico&id=" . $id_nota);
		} else {
			$row = Core::getRowById("cadastro_financeiro", Filter::$id);
			$row_empresa = Core::getRowById("empresa", $row->id_empresa);
			$row_cadastro = Core::getRowById("cadastro", $row->id_cadastro);
		}
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_ADICIONAR'); ?></small></h1>
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
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('NOTA_ADICIONAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('EMPRESA'); ?></label>
																<div class='col-md-9'>
																	<input name="id_empresa"
																		value="<?php echo $row->id_empresa; ?>" type="hidden" />
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo getValue('nome', 'empresa', 'id=' . $row->id_empresa); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('NOME'); ?></label>
																<div class='col-md-9'>
																	<input name="id_cadastro"
																		value="<?php echo $row->id_cadastro; ?>"
																		type="hidden" />
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo getValue('nome', 'cadastro', 'id=' . $row->id_cadastro); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('VALOR_SERVICO'); ?></label>
																<div class='col-md-9'>
																	<input name="valor_servico"
																		value="<?php echo $row->valor_contrato; ?>"
																		type="hidden" />
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo moedap($row->valor_contrato); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<input name="iss_retido"
																	value="<?php echo $row_cadastro->reter_iss; ?>"
																	type="hidden" />
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input disabled type="checkbox" class="md-check"
																				id="iss_retido" <?php if ($row_cadastro->reter_iss)
																					echo 'checked'; ?>>
																			<label for="iss_retido">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('ISS_RETIDO'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('ISS_ALIQUOTA'); ?></label>
																<div class='col-md-9'>
																	<input name="iss_aliquota"
																		value="<?php echo $row_empresa->iss_aliquota; ?>"
																		type="hidden" />
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo decimalp($row_empresa->iss_aliquota); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('VALOR_ISS'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo moedap($row->valor_contrato * ($row_empresa->iss_aliquota / 100)); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('VALOR_NOTA'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo ($row_cadastro->reter_iss) ? moedap($row->valor_contrato * (1 - ($row_empresa->iss_aliquota / 100))) : moedap($row->valor_contrato); ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><?php echo lang('DISCRIMINACAO'); ?></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea class="form-control"
																		name="descriminacao"><?php echo ($row->id_contrato) ? lang('DISCRIMINACAO_SERVICOS_CONTRATO') . $mes_ano : lang('DISCRIMINACAO_SERVICOS_ORDEM_SERVICO'); ?></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<input name="id_financeiro" value="<?php echo $row->id; ?>" type="hidden" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<button type="button"
																	class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR_NOTA'); ?></button>
																<button type="button" id="voltar"
																	class="btn default"><?php echo lang('VOLTAR'); ?></button>
															</div>
														</div>
													</div>
													<div class="col-md-6">
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm("processarNotaFiscalServico"); ?>
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
		<?php break; ?>
	<?php
	case "visualizar_servico":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

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
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_VISUALIZAR'); ?></small></h1>
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
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('NOTA_VISUALIZAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('EMPRESA'); ?></label>
																<div class='col-md-9'>
																	<input name="id_empresa"
																		value="<?php echo $row->id_empresa; ?>" type="hidden" />
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo getValue('nome', 'empresa', 'id=' . $row->id_empresa); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('NOME'); ?></label>
																<div class='col-md-9'>
																	<input name="id_cadastro"
																		value="<?php echo $row->id_cadastro; ?>"
																		type="hidden" />
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo getValue('nome', 'cadastro', 'id=' . $row->id_cadastro); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('VALOR_SERVICO'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo moedap($row->valor_servico); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input disabled type="checkbox" class="md-check"
																				id="iss_retido" <?php if ($row->iss_retido)
																					echo 'checked'; ?>>
																			<label for="iss_retido">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('ISS_RETIDO'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('ISS_ALIQUOTA'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo decimalp($row_empresa->iss_aliquota); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('VALOR_ISS'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo moedap($row->valor_iss); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('VALOR_NOTA'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo moedap($row->valor_nota); ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><?php echo lang('DISCRIMINACAO'); ?></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea readonly class="form-control"
																		name="descriminacao"><?php echo ($row->descriminacao); ?></textarea>
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
																<?php if ($row->fiscal): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('nfse_download.php?id=<?php echo $row->id; ?>','<?php echo lang('NOTA_DOWNLOAD') . $row->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																		title="<?php echo lang('NOTA_DOWNLOAD'); ?>"
																		class="btn btn-sm green-turquoise"><i
																			class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('NOTA_DOWNLOAD'); ?></a>
																<?php else: ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('nfse.php?id=<?php echo $row->id; ?>','<?php echo lang('NOTA_EMITIR') . $row->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																		title="<?php echo lang('NOTA_EMITIR'); ?>"
																		class="btn btn-sm purple"><i
																			class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('NOTA_EMITIR'); ?></a>
																<?php endif; ?>
																<button type="button" id="voltar"
																	class="btn default"><?php echo lang('VOLTAR'); ?></button>
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
		<?php break; ?>
	<?php
	case "editar_servico":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

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
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_EDITAR'); ?></small></h1>
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
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-pencil">&nbsp;&nbsp;</i><?php echo lang('NOTA_EDITAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('EMPRESA'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo getValue('nome', 'empresa', 'id=' . $row->id_empresa); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('NOME'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo getValue('nome', 'cadastro', 'id=' . $row->id_cadastro); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('VALOR_SERVICO'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo moedap($row->valor_servico); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input disabled type="checkbox" class="md-check"
																				id="iss_retido" <?php if ($row->iss_retido)
																					echo 'checked'; ?>>
																			<label for="iss_retido">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('ISS_RETIDO'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('ISS_ALIQUOTA'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo decimalp($row_empresa->iss_aliquota); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('VALOR_ISS'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo moedap($row->valor_iss); ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label
																	class='control-label col-md-3'><?php echo lang('VALOR_NOTA'); ?></label>
																<div class='col-md-9'>
																	<input readonly type="text" autocomplete="off"
																		class="form-control caps"
																		value="<?php echo moedap($row->valor_nota); ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><?php echo lang('DISCRIMINACAO'); ?></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea class="form-control"
																		name="descriminacao"><?php echo ($row->descriminacao); ?></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<input name="id" value="<?php echo $row->id; ?>" type="hidden" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<button type="button"
																	class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR_NOTA'); ?></button>
																<button type="button" id="voltar"
																	class="btn default"><?php echo lang('VOLTAR'); ?></button>
															</div>
														</div>
													</div>
													<div class="col-md-6">
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm("editarNotaFiscalServico"); ?>
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
		<?php break; ?>
	<?php
	case "devolucao":
		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$row = Core::getRowById("nota_fiscal", Filter::$id);
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ENOTAS_DEVOLUCAO'); ?></small></h1>
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
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-repeat">&nbsp;&nbsp;</i><?php echo lang('ENOTAS_DEVOLUCAO'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CFOP'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="cfop"  id="select_cfop_nfe"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getCFOP_Todos();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->cfop; ?>" descricao="<?php echo $srow->descricao; ?>">
																					<?php echo $srow->cfop . " - " . $srow->descricao; ?>
																				</option>
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
																<label
																	class="control-label col-md-3"><?php echo lang('NATUREZA_OPERACAO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="natureza_operacao_nfe" id="natureza_operacao_nfe" maxlength="60">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('EMPRESA'); ?></label>
																<div class="col-md-9">
																	<input name="id_empresa" type="hidden"
																		value="<?php echo $row->id_empresa; ?>" />
																	<input readonly type="text" class="form-control"
																		value="<?php echo getValue('nome', 'empresa', 'id=' . $row->id_empresa); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NOME'); ?></label>
																<div class="col-md-9">
																	<input name="id_cadastro" type="hidden"
																		value="<?php echo $row->id_cadastro; ?>" />
																	<input readonly type="text" class="form-control"
																		value="<?php echo getValue('nome', 'cadastro', 'id=' . $row->id_cadastro); ?>">
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CHAVE_ACESSO_REFERENCIADA'); ?></label>
																<div class="col-md-9">
																	<input name="nfe_referenciada" type="hidden"
																		value="<?php echo $row->chaveacesso; ?>" />
																	<input readonly type="text" class="form-control"
																		value="<?php echo $row->chaveacesso; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO_NOTA'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control"
																		value="<?php echo $row->numero_nota; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_EMISSAO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control"
																		value="<?php echo exibedata($row->data_emissao); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_ENTRADA'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text"
																		class="form-control data calendario"
																		value="<?php echo exibedata($row->data_entrada); ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label
																	class="col-md-3"><strong><?php echo lang('DISCRIMINACAO'); ?></strong></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea class="form-control"
																		name="descriminacao"></textarea>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label class="col-md-3"><strong><?php echo lang('INF_ADICIONAIS'); ?>
																		</sstrong></label>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-12">
																	<textarea class="form-control"
																		name="inf_adicionais"></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="col-md-12"><br></div>

												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section">
															<strong><?php echo lang('INFORMACOES_TRANSPORTE'); ?></strong>
														</h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TRANSPORTE'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="modalidade"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""><?php echo lang('SEM_TRANSPORTE'); ?>
																		</option>
																		<option value="SemFrete"><?php echo lang('SEMFRETE'); ?>
																		</option>
																		<option value="PorContaDoEmitente">
																			<?php echo lang('PORCONTADOEMITENTE'); ?>
																		</option>
																		<option value="PorContaDoDestinatario">
																			<?php echo lang('PORCONTADODESTINATARIO'); ?>
																		</option>
																		<option value="ContratacaoPorContaDoRemetente">
																			<?php echo lang('CONTRATACAOPORCONTADOREMETENTE'); ?>
																		</option>
																		<option value="ContratacaoPorContaDoDestinario">
																			<?php echo lang('CONTRATACAOPORCONTADODESTINARIO'); ?>
																		</option>
																		<option value="ContratacaoPorContaDeTerceiros">
																			<?php echo lang('CONTRATACAOPORCONTADETERCEIROS'); ?>
																		</option>
																		<option value="TransporteProprioPorContaDoRemetente">
																			<?php echo lang('TRANSPORTEPROPRIOPORCONTADOREMETENTE'); ?>
																		</option>
																		<option value="TransporteProprioPorContaDoDestinatario">
																			<?php echo lang('TRANSPORTEPROPRIOPORCONTADODESTINATARIO'); ?>
																		</option>
																		<option value="SemOcorrenciaDeTransporte">
																			<?php echo lang('SEMOCORRENCIADETRANSPORTE'); ?>
																		</option>
																	</select>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_FRETE'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">R$</span>
																		<input type="text" class="form-control decimalp"
																			name="valor_frete"
																			value="<?php echo decimalp($row->valor_frete); ?>">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12"><br><br></div>

												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="col-md-12">
															<h5 class="form-section">
																<strong><?php echo lang('EMPRESA_DESTINO_DADOS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TIPO'); ?></label>
																<div class="col-md-9">
																	<div class="md-radio-list">
																		<div class="md-radio col-md-6">
																			<input type="radio" class="md-radiobtn"
																				name="tipopessoadestinatario" id="tipo_j"
																				value="J">
																			<label for="tipo_j">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_JURIDICA'); ?></label>
																		</div>
																		<div class="md-radio col-md-6">
																			<input type="radio" class="md-radiobtn"
																				name="tipopessoadestinatario" id="tipo_f"
																				value="F">
																			<label for="tipo_f">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PESSOA_FISICA'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CPF_CNPJ'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cpf_cnpj"
																			name="cpfcnpjdestinatario" id="cpfcnpjdestinatario">
																		<span
																			class="input-group-addon"><?php echo lang('TECLE_ENTER') ?></span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEP'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cep" name="cep"
																			id="cep">
																		<span class="input-group-btn">
																			<button id="cepbusca"
																				class="btn <?php echo $core->primeira_cor; ?>"
																				type="button"><i
																					class="fa fa-arrow-left fa-fw"></i>
																				<?php echo lang('BUSCAR_END'); ?></button>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ENDERECO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="logradouro" id="endereco">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="numero"
																		id="numero">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<br><br><br><br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('COMPLEMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="complemento" id="complemento">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('BAIRRO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="bairro"
																		id="bairro">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="cidade"
																		id="cidade">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTADO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps uf" name="uf"
																		id="estado">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>

												<div class="col-md-12"><br><br></div>

												<div class="col-md-12">
													<!--col-md-5-->
													<div class="col-md-6">
														<div class="col-md-12">
															<h5 class="form-section">
																<strong><?php echo lang('DADOS_MERCADORIAS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('QUANT_VOLUMES'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="quantidade">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESPECIE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="especie">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<br><br><br>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PESOLIQUIDO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="pesoliquido">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PESOBRUTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="pesobruto">
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>

													<div class="col-md-12"><br><br></div>

													<div class="col-md-12">
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="col-md-12">
																<h5 class="form-section">
																	<strong><?php echo lang('TRANSPORTADORA_DADOS'); ?></strong>
																</h5>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('TRANSPORTADORA'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="trans_nome" id="trans_nome">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('TIPO'); ?></label>
																	<div class="col-md-9">
																		<div class="md-radio-list">
																			<div class="md-radio col-md-6">
																				<input type="radio" class="md-radiobtn"
																					name="trans_tipopessoa" id="tipo_j_trans"
																					value="J">
																				<label for="tipo_j_trans">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PESSOA_JURIDICA'); ?></label>
																			</div>
																			<div class="md-radio col-md-6">
																				<input type="radio" class="md-radiobtn"
																					name="trans_tipopessoa" id="tipo_f_trans"
																					value="F">
																				<label for="tipo_f_trans">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PESSOA_FISICA'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('TRANS_CPF_CNPJ'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<input type="text" class="form-control cpf_cnpj"
																				name="trans_cpfcnpj" id="trans_cpfcnpj">
																			<span class="input-group-addon">
																				<?php echo lang('TECLE_ENTER') ?>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('INSCRICAO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="trans_inscricaoestadual" id="trans_inscricaoestadual">
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<br><br><br>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CEP'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<input type="text" class="form-control cep"
																				name="trans_cep" id="trans_cep">
																			<span class="input-group-btn">
																				<button id="ceptransportadora"
																					class="btn <?php echo $core->primeira_cor; ?>"
																					type="button"><i
																						class="fa fa-arrow-left fa-fw"></i>
																					<?php echo lang('BUSCAR_END'); ?></button>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ENDERECO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="trans_endereco" id="trans_endereco">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CIDADE'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="trans_cidade" id="trans_cidade">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ESTADO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps"
																			name="trans_uf" id="trans_uf">
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>
													<div class="col-md-12"><br></div>
													<div class="col-md-12">
														<div class="col-md-12">
															<h4 class="form-section">
																<strong><?php echo lang('INFORMACOES_PRODUTOS'); ?></strong>
															</h4>
														</div>
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
																	<div class="col-md-9">
																		<select
																			class="select2me form-control produto_notafiscal_devolucao"
																			name="sel_produto_devolucao" id="sel_produto"
																			data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $produto->getProdutosNotaDevolucao(Filter::$id);
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->id; ?>"
																						id_produto="<?php echo $srow->id_produto; ?>"
																						data-quantidade="<?php echo decimalp($srow->quantidade); ?>">
																						<?php echo $srow->nome_fornecedor . " - Quant.: " . decimalp($srow->quantidade) . " - Cod.: " . $srow->codigonota; ?>
																					</option>
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
																	<label
																		class="control-label col-md-3"><?php echo lang('PRODUTO_TIPO_ITEM'); ?></label>
																	<div class="col-md-9">
																		<select class="select2me form-control"
																			name="sel_tipo_item" id="sel_tipo_item"
																			data-placeholder="<?php echo lang('SELECIONE_TIPO_ITEM'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $produto->getTipoItemProduto();
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->codigo; ?>" <?php if ($srow->codigo == '00')
																						   echo 'selected="selected"'; ?>>
																						<?php echo $srow->codigo . " - " . $srow->descricao; ?>
																					</option>
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
																	<label
																		class="control-label col-md-3"><?php echo lang('QUANTIDADE'); ?></label>
																	<div class="col-md-9 pai_quantidade_devolucao">
																		<select class="form-control class_quantidade_devolucao"
																			name="sel_quant" id="sel_quantidade_devolucao">
																		</select>
																		<input
																			class="form-control class_quantidade_devolucao decimalp"
																			name="sel_quant" id="" style="display: none;">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_UNIDADE'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_valor" id="sel_valor_unitario_devolucao">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_DESCONTO'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_desconto" id="sel_desconto_devolucao">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('DESPESAS_ACESSORIAS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_despesas" id="sel_despesas_devolucao">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
														<!--col-md-5-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ITEM_PEDIDO_COMPRA'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_item_pedido_compra"
																			id="sel_item_pedido_compra">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CFOP'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_cfop" id="sel_cfop_devolucao"
																			maxlength="4">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CSOSN_CST'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_cst" id="sel_cst_devolucao" maxlength="4">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('NCM'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_ncm" id="sel_ncm_devolucao" maxlength="8">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CEST'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="sel_cest" id="sel_cest_devolucao"
																			maxlength="7">
																		<span
																			class="help-block"><?php echo lang('OBS_CEST'); ?></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="col-md-6">
															<h5 class="form-section">
																<strong><?php echo lang('TRIBUTOS'); ?></strong>
															</h5>
														</div>
														<div class="row">
															<br><br>
														</div>
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_ICMS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_icms_base" id="sel_icms_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_icms" id="sel_icms_devolucao">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_ST'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_st_base" id="sel_st_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS_ST'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_st_percentual" id="sel_st_percentual_devolucao">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_MVA'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_mva" id="sel_mva_devolucao">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-4-->
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CST_PIS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">Cód</span>
																			<input type="text" class="form-control inteiro"
																				name="sel_pis_cst" id="sel_pis_cst_devolucao">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_PIS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_pis_base" id="sel_pis_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_PIS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_pis" id="sel_pis_devolucao">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CST_COFINS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">Cód</span>
																			<input type="text" class="form-control inteiro"
																				name="sel_cofins_cst" id="sel_cofins_cst_devolucao">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_COFINS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_cofins_base" id="sel_cofins_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_COFINS'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_cofins" id="sel_cofins_devolucao">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-4-->
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CST_IPI'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">Cód</span>
																			<input type="text" class="form-control inteiro"
																				name="sel_ipi_cst" id="sel_ipi_cst_devolucao">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR_BASE_IPI'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">R$</span>
																			<input type="text" class="form-control decimalp"
																				name="sel_ipi_base" id="sel_ipi_base">
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ALIQUOTA_CST_IPI'); ?></label>
																	<div class="col-md-9">
																		<div class="input-group">
																			<span class="input-group-addon">%</span>
																			<input type="text" class="form-control decimal"
																				name="sel_ipi" id="sel_ipi_devolucao">
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div class="row">
																<div class="form-group">
																	<div class="col-md-3">
																		<a href="javascript:void(0);"
																			class="btn green adicionar_produto"
																			title="<?php echo lang('PRODUTO_ADICIONAR'); ?>"><i
																				class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_ADICIONAR'); ?></a>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="portlet-body">
															<div class="table-scrollable table-scrollable-borderless">
																<table class="table table-hover table-light">
																	<thead>
																		<tr>
																			<th><?php echo lang('PRODUTO'); ?></th>
																			<th><?php echo lang('QUANT'); ?></th>
																			<th><?php echo lang('VL_TOTAL'); ?></th>
																			<th><?php echo lang('CFOP'); ?></th>
																			<th><?php echo lang('CSOSN_CST'); ?></th>
																			<th><?php echo lang('NCM'); ?></th>
																			<th><?php echo lang('CEST'); ?></th>
																			<th><?php echo lang('VL_DESC'); ?></th>
																			<th><?php echo lang('VL_ACRESC'); ?></th>
																			<th><?php echo lang('VL_ICMS'); ?></th>
																			<th><?php echo lang('VL_ST'); ?></th>
																			<th><?php echo lang('ITEM_PEDIDO_COMPRA'); ?></th>
																			<th></th>
																		</tr>
																	</thead>
																	<tbody id="tabela_produtos">
																	</tbody>
																</table>
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
																	<button type="button"
																		class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR_NOTA'); ?></button>
																	<button type="button" id="voltar"
																		class="btn default"><?php echo lang('VOLTAR'); ?></button>
																</div>
															</div>
														</div>
														<div class="col-md-6">
														</div>
													</div>
												</div>
											</div>
									</form>
									<?php echo $core->doForm("processarNotaFiscal"); ?>
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
		<?php break; ?>
	<?php
	case "carta":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$id_nota = get('id_nota');
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class='page-container'>
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class='page-head'>
				<div class='container'>
					<!-- INICIO TITULO DA PAGINA -->
					<div class='page-title'>
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;&nbsp;<small><?php echo lang('NOTA_CARTA'); ?></small></h1>
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
							<div class='portlet box <?php echo $core->primeira_cor; ?>'>
								<div class='portlet-title'>
									<div class='caption'>
										<i class='fa fa-file-text'>&nbsp;&nbsp;</i><?php echo lang('NOTA_CARTA'); ?>
									</div>
								</div>
								<div class='portlet-body form'>
									<!-- INICIO FORM-->
									<form action='' autocomplete="off" method='post' class='form-horizontal' name='carta_form'
										id='carta_form'>
										<div class='form-body'>
											<div class='form-group'>
												<div class="col-md-12">
													<div class="note note-info">
														<p><?php echo lang('NOTA_CONDICAO'); ?></p>
													</div>
													<div class="note note-warning">
														<p><?php echo lang('NOTA_CARTA_AVISO'); ?></p>
													</div>
													<div class="note note-warning">
														<p><?php echo lang('NOTA_CARTA_OBSERVACAO'); ?></p>
													</div>
												</div>
											</div>
										</div>
										<?php if ($id_nota):
											$numero_nota = getValue('numero_nota', 'nota_fiscal', 'id=' . $id_nota);
											$id_cadastro = getValue('id_cadastro', 'nota_fiscal', 'id=' . $id_nota);
											$chaveacesso = getValue('chaveacesso', 'nota_fiscal', 'id=' . $id_nota);
											$nome_cadastro = getValue('nome', 'cadastro', 'id=' . $id_cadastro);
											$id_empresa = getValue('id_empresa', 'nota_fiscal', 'id=' . $id_nota);
											$numero_carta = $produto->obterNumeroCartaCorrecao($id_nota);
											?>
											<div>
												<div class='form-group'>
													<label class='control-label col-md-2'><?php echo lang('NOTA_FISCAL'); ?></label>
													<div class='col-md-6'>
														<input readonly type='text' class='form-control caps'
															value='<?php echo $numero_nota; ?>'>
													</div>
												</div>
											</div>
											<div>
												<div class='form-group'>
													<label class='control-label col-md-2'><?php echo lang('CADASTRO'); ?></label>
													<div class='col-md-6'>
														<input readonly type='text' class='form-control caps'
															value='<?php echo $nome_cadastro; ?>'>
													</div>
												</div>
											</div>
											<input name="id_nota" type="hidden" value="<?php echo $id_nota; ?>" />
											<input name="id_empresa" type="hidden" value="<?php echo $id_empresa; ?>" />
											<input name="numero" type="hidden" value="<?php echo $numero_carta; ?>" />
											<input name="chaveacesso" type="hidden" value="<?php echo $chaveacesso; ?>" />
										<?php else: ?>
											<div>
												<div class='form-group'>
													<label
														class='control-label col-md-2'><?php echo lang('CHAVE_ACESSO'); ?></label>
													<div class='col-md-6'>
														<input type='text' class='form-control caps' name='chaveacesso'>
													</div>
												</div>
											</div>
											<div>
												<div class='form-group'>
													<label class='control-label col-md-2'><?php echo lang('EMPRESA'); ?></label>
													<div class='col-md-6'>
														<select class="select2me form-control" name="id_empresa"
															data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
															<option value=""></option>
															<?php
															$retorno_row = $empresa->getEmpresas();
															if ($retorno_row):
																foreach ($retorno_row as $srow):
																	?>
																	<option value="<?php echo $srow->id; ?>"><?php echo $srow->nome; ?>
																	</option>
																	<?php
																endforeach;
																unset($srow);
															endif;
															?>
														</select>
													</div>
												</div>
											</div>
										<?php endif; ?>
										<div>
											<div class='form-group'>
												<label
													class='control-label col-md-2'><?php echo lang('NOTA_CARTA_NUMERO'); ?></label>
												<div class='col-md-6'>
													<input readonly type='text' class='form-control caps'
														value='<?php echo $numero_carta; ?>'>
													<br>
													<?php echo lang('NOTA_CARTA_NUMERO_DESCRICAO'); ?>
												</div>
											</div>
										</div>
										<div>
											<div class='form-group'>
												<label
													class='control-label col-md-2'><?php echo lang('NOTA_CORRECAO'); ?></label>
												<div class='col-md-6'>
													<textarea class='form-control caps' name='correcao'></textarea>
												</div>
											</div>
										</div>
										<div class='form-actions'>
											<div class='row'>
												<div class='col-md-12'>
													<div class='col-md-6'>
														<div class='row'>
															<div class='col-md-offset-3 col-md-9'>
																<button type='submit'
																	class='btn <?php echo $core->primeira_cor; ?>'><?php echo lang('SALVAR'); ?></button>
															</div>
														</div>
													</div>
													<div class='col-md-6'>
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm('processarNotaFiscalCarta', 'carta_form'); ?>
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
										<i class='fa fa-list font-<?php echo $core->primeira_cor; ?>'></i>
										<span
											class='font-<?php echo $core->primeira_cor; ?>'><?php echo lang('LISTAR'); ?></span>
									</div>
								</div>
								<div class='portlet-body'>
									<table class='table table-bordered table-striped table-condensed table-advance dataTable'>
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('NOTA_FISCAL'); ?></th>
												<th><?php echo lang('EMPRESA'); ?></th>
												<th><?php echo lang('NOTA_CARTA_NUMERO'); ?></th>
												<th><?php echo lang('NOTA_CORRECAO'); ?></th>
												<th><?php echo lang('EMITIDA'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $produto->getCartaCorrecao();
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo $exrow->id; ?></td>
														<?php if ($exrow->id_nota): ?>
															<td><a
																	href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota; ?>"><?php echo $exrow->numero_nota; ?></a>
															</td>
														<?php else: ?>
															<td><?php echo $exrow->chaveacesso; ?></td>
														<?php endif; ?>
														<td><?php echo $exrow->empresa; ?></td>
														<td><?php echo $exrow->numero; ?></td>
														<td><?php echo $exrow->correcao; ?></td>
														<td>
															<a href="nfe_carta.php?id=<?php echo $exrow->id; ?>" target="_blank"
																title="<?php echo lang('NOTA_VISUALIZAR_CARTA'); ?>"
																class="btn yellow"><i
																	class="fa fa-file-text">&nbsp;&nbsp;</i><?php echo lang('NOTA_VISUALIZAR_CARTA'); ?></a>
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
		<?php break; ?>
	<?php
	case "notafiscal":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

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
				$('#buscar').click(function () {
					var numero_nota = $("#numero_nota").val();
					var id_empresa = $("#id_empresa").val();
					var modelo = $("#modelo").val();
					var operacao = $("#operacao").val();
					var mes_ano = $("#mes_ano").val();
					var ano = $("#ano").val();
					window.location.href = 'index.php?do=notafiscal&acao=notafiscal&id_empresa=' + id_empresa + '&modelo=' + modelo + '&operacao=' + operacao + '&mes_ano=' + mes_ano + '&numero_nota=' + numero_nota + '&ano=' + ano;
				});
				$('#imprimir').click(function () {
					var numero_nota = $("#numero_nota").val();
					var id_empresa = $("#id_empresa").val();
					var modelo = $("#modelo").val();
					var operacao = $("#operacao").val();
					var mes_ano = $("#mes_ano").val();
					var ano = $("#ano").val();
					window.open('pdf_notafiscal.php?id_empresa=' + id_empresa + '&modelo=' + modelo + '&operacao=' + operacao + '&mes_ano=' + mes_ano + '&numero_nota=' + numero_nota + '&ano=' + ano, 'Imprimir Notas fiscais', 'width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
				});
			});
			// ]]>
		</script>
		<div id="editar-Cfop-Transporte" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-pencil">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_EDITAR'); ?>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="editarCfopTransporte_form"
						id="editarCfopTransporte_form" class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-md-12"><?php echo lang('NOTA_EDITAR_CFOP'); ?></label>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CFOP'); ?></label>
									<div class="col-md-8">
										<input type="text" class="form-control inteiro" name="cfop_transporte"
											id="cfop_transporte" maxlength="4">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("editarCfopTransporte", "editarCfopTransporte_form"); ?>
		</div>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_LISTAR'); ?></small></h1>
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
										<i class="fa fa-list font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('NOTA_LISTAR'); ?></span>
									</div>
								</div>
								<div class="portlet-body form">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="mes_ano" id="mes_ano"
												data-placeholder="<?php echo lang('SELECIONE_MES'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $gestao->getListaMes("nota_fiscal", "data_emissao", false, "DESC");
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->mes_ano; ?>" <?php if ($srow->mes_ano == $mes_ano)
															   echo 'selected="selected"'; ?>>
															<?php echo exibeMesAno($srow->mes_ano, true, true); ?>
														</option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<select class="select2me form-control" name="ano" id="ano"
												data-placeholder="<?php echo lang('SELECIONE_ANO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $gestao->getListaAno("nota_fiscal", "data_emissao", "DESC");
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->mes_ano; ?>" <?php if ($srow->mes_ano == $ano)
															   echo 'selected="selected"'; ?>><?php echo $srow->mes_ano; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<select class="select2me form-control input-large" id="id_empresa" name="id_empresa"
												data-placeholder="<?php echo lang('SELECIONE_EMPRESA'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $empresa->getEmpresas();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_empresa)
															   echo 'selected="selected"'; ?>><?php echo $srow->nome; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											<br />
											<br />
											<select class="select2me form-control" id="modelo" name="modelo"
												data-placeholder="<?php echo lang('SELECIONE_TIPO'); ?>">
												<option value=""></option>
												<option value="1" <?php if (1 == $modelo)
													echo 'selected="selected"'; ?>>
													<?php echo lang('SERVICO'); ?>
												</option>
												<option value="2" <?php if (2 == $modelo)
													echo 'selected="selected"'; ?>>
													<?php echo lang('PRODUTO'); ?>
												</option>
												<option value="3" <?php if (3 == $modelo)
													echo 'selected="selected"'; ?>>
													<?php echo lang('FATURA'); ?>
												</option>
												<option value="4" <?php if (4 == $modelo)
													echo 'selected="selected"'; ?>>
													<?php echo lang('TRANSPORTE'); ?>
												</option>
											</select>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<select class="select2me form-control" id="operacao" name="operacao"
												data-placeholder="<?php echo lang('SELECIONE_OPERACAO'); ?>">
												<option value=""></option>
												<option value="1" <?php if (1 == $operacao)
													echo 'selected="selected"'; ?>>
													<?php echo lang('COMPRA'); ?>
												</option>
												<option value="2" <?php if (2 == $operacao)
													echo 'selected="selected"'; ?>>
													<?php echo lang('VENDA'); ?>
												</option>
											</select>
											<br />
											<br />
											<label><?php echo lang('NUMERO_NOTA'); ?>&nbsp;&nbsp;</label><input type="text"
												class="form-control input-medium" name="numero_nota" id="numero_nota"
												value="<?php echo $numero_nota; ?>">
											&nbsp;&nbsp;
											<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor; ?>"><i
													class="fa fa-search"></i> <?php echo lang('BUSCAR'); ?></button>
											&nbsp;&nbsp;
											<button type="button" id="imprimir" class="btn green"><i class="fa fa-print"></i>
												<?php echo lang('IMPRIMIR'); ?></button>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable-asc ">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('ID'); ?></th>
												<th><?php echo lang('DATA_NOTA'); ?></th>
												<th><?php echo lang('EMPRESA'); ?></th>
												<th><?php echo lang('MODELO'); ?></th>
												<th><?php echo lang('OPERACAO'); ?></th>
												<th><?php echo lang('NOME'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
												<th><?php echo lang('CFOP'); ?></th>
												<th><?php echo lang('VALOR_NOTA'); ?></th>
												<th><?php echo lang('STATUS'); ?></th>
												<th><?php echo lang('MOTIVO'); ?></th>
												<th width="110px"><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$estilo_status = "";
											$retorno_row = $produto->getNotaFiscal($mes_ano, $id_empresa, $modelo, $operacao, $numero_nota, $ano);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$estilo = '';
													if ($exrow->inativo)
														$estilo = 'class="danger"';
													else
														$total += $exrow->valor_nota;

													$estilo_status = ($exrow->status_enotas == "Autorizada") ? ((!$exrow->contingencia) ? "badge bg-green" : "badge bg-blue-chambray") : (($exrow->status_enotas == "Negada") ? "badge bg-red" : (($exrow->status_enotas == "Inutilizada") ? "badge bg-blue-hoki" : (($exrow->status_enotas == "Cancelada" || $exrow->inativo) ? "badge bg-gray" : "badge bg-yellow")));

													if ($exrow->operacao == 1)
														$estilo_status = "badge badge-info";
													?>
													<tr <?php echo $estilo; ?>>
														<td><?php echo $exrow->controle; ?></td>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo exibedata($exrow->data_emissao); ?></td>
														<td><?php echo $exrow->empresa; ?></td>
														<td><?php echo modelo($exrow->modelo); ?></td>
														<?php if ($exrow->id_venda > 0): ?>
															<td><?php echo operacao($exrow->operacao) . " - ID venda: $exrow->id_venda"; ?></td>
														<?php elseif ($exrow->id_ordem_servico): ?>
															<td><?php echo operacao($exrow->operacao) . " - ID OS: $exrow->id_ordem_servico"; ?></td>
														<?php else: ?>
															<td><?php echo operacao($exrow->operacao); ?></td>
														<?php endif; ?>
														<td><?php echo $exrow->razao_social; ?></td>
														<td><?php echo $exrow->numero_nota; ?></td>
														<td>
															<?php
															if ($exrow->modelo == 4):
																$texto = ($exrow->cfop) ? $exrow->cfop : lang('EDITAR');
																?>
																<a href="javascript:void(0);"
																	class="btn btn-sm blue-hoki editarCfopTransporte"
																	id_nota="<?php echo $exrow->id; ?>"
																	title="<?php echo lang('EDITAR'); ?>"><?php echo $texto; ?></a>
																<?php
															else:
																echo $exrow->cfop;
															endif;
															?>
														<td><?php echo moedap($exrow->valor_nota); ?></td>
														<td>
															<?php if ($exrow->operacao == 1): ?>
																<div class="<?php echo $estilo_status; ?>">
																	<?php echo ucfirst(strtolower((operacao($exrow->operacao)))); ?>
																</div>
															<?php elseif ((!empty($exrow->status_enotas) && $exrow->status_enotas != "") || $exrow->fiscal == 0): ?>
																<div class="<?php echo $estilo_status; ?>">
																	<?php echo ($exrow->status_enotas == "Autorizada") ? ((!$exrow->contingencia) ? $exrow->status_enotas : lang('NOTA_FISCAL_CONSUMIDOR_CONTIGENCIA')) : (($exrow->status_enotas == "Negada") ? $exrow->status_enotas : (($exrow->status_enotas == "Inutilizada" || $exrow->status_enotas == "Cancelada") ? $exrow->status_enotas : (($exrow->inativo) ? lang('CANCELADA') : lang('NOTA_FISCAL_CONSUMIDOR_PENDENTE')))); ?>
																</div>
															<?php endif; ?>
														</td>
														<td><?php echo (!empty($exrow->motivo_status)) ? $exrow->motivo_status : ""; ?>
														</td>
														<td>
															<?php if ($exrow->status_enotas != "Inutilizada"): ?>
																<?php if ($exrow->modelo == 1): ?>
																	<a href="index.php?do=notafiscal&acao=visualizar_servico&id=<?php echo $exrow->id; ?>"
																		title="<?php echo lang('VISUALIZAR') . ': ' . $exrow->razao_social; ?>"
																		class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
																<?php else: ?>
																	<a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id; ?>"
																		class="btn btn-sm grey-cascade"
																		title="<?php echo lang('VISUALIZAR') . ': ' . $exrow->razao_social; ?>"><i
																			class="fa fa-search"></i></a>
																<?php endif; ?>
																<?php if ($exrow->fiscal && $exrow->status_enotas == "Autorizada"): ?>
																	<?php if (!$exrow->link_danfe): ?>
																		<a href="nfe_download.php?id=<?php echo $exrow->id; ?>" target="_blank"
																			title="<?php echo lang('ENOTAS_PDF'); ?>"
																			class="btn btn-sm green-jungle"><i class="fa fa-file-pdf-o"></i></a>
																	<?php else: ?>
																		<a href="<?php echo str_replace("http://", "https://", $exrow->link_danfe); ?>"
																			target="_blank" title="<?php echo lang('ENOTAS_PDF'); ?>"
																			class="btn btn-sm green-jungle"><i class="fa fa-file-pdf-o"></i></a>
																	<?php endif; ?>
																<?php elseif ($exrow->nome_arquivo == ''): ?>
																	<?php if ($exrow->modelo == 1): ?>
																		<a href="index.php?do=notafiscal&acao=editar_servico&id=<?php echo $exrow->id; ?>"
																			class="btn btn-sm blue"
																			title="<?php echo lang('EDITAR') . ': ' . $exrow->razao_social; ?>"><i
																				class="fa fa-pencil"></i></a>
																	<?php elseif (!$exrow->inativo): ?>
																		<a href="index.php?do=notafiscal&acao=editar&id=<?php echo $exrow->id; ?>"
																			class="btn btn-sm blue"
																			title="<?php echo lang('EDITAR') . ': ' . $exrow->razao_social; ?>"><i
																				class="fa fa-pencil"></i></a>
																	<?php endif; ?>
																<?php endif; ?>
																<?php if (!$exrow->inativo and !$exrow->fiscal): ?>
																	<a href="javascript:void(0);" class="btn btn-sm red apagar"
																		id="<?php echo $exrow->id; ?>" acao="apagarNotaFiscal"
																		title="<?php echo lang('NOTA_APAGAR') . $exrow->razao_social; ?>">
																		<i class="fa fa-ban"></i>
																	</a>
																<?php endif; ?>
															<?php endif; ?>
															<?php if ($exrow->status_enotas == "Inutilizada" and !$exrow->inativo): ?>
																	<a href="javascript:void(0);" class="btn btn-sm red apagar"
																		id="<?php echo $exrow->id; ?>" acao="apagarNotaFiscal"
																		title="<?php echo lang('NOTA_APAGAR') . $exrow->razao_social; ?>">
																		<i class="fa fa-ban"></i>
																	</a>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="9"><strong><?php echo lang('TOTAL'); ?></strong></td>
													<td><strong><?php echo moedap($total); ?></strong></td>
													<td colspan="3"></td>
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
	<?php
	case "inutilizar":
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#inutilizar').click(function () {
					var numero_nota = $("#numero_nota").val();
					var serie_nota = $("#serie_nota").val();
					var id_empresa = $("#id_empresa").val();
					window.open('nfc_inutilizar.php?numero_nota=' + numero_nota + '&serie_nota=' + serie_nota + '&id_empresa=' + id_empresa, 'Inutilizar Notas fiscais', 'width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
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
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_INUTILIZADAS'); ?></small></h1>
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
										<i class="fa fa-ban font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('NOTA_INUTILIZADAS'); ?></span>
									</div>
								</div>
								<div class="note note-warning">
									<h4 class="block"><b><?php echo lang('ATENCAO'); ?></b></h4>
									<p><?php echo lang('NOTA_INUTILIZAR_NFC'); ?></p>
								</div>
								<div class="portlet-body form">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" id="id_empresa" name="id_empresa"
												data-placeholder="<?php echo lang('SELECIONE_EMPRESA'); ?>">
												<?php
												$retorno_row = $empresa->getEmpresas();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->nome; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											<br />
											<br />
											<label><?php echo lang('NUMERO_NOTA'); ?>&nbsp;&nbsp;</label><input type="text"
												class="form-control input-medium codigo" name="numero_nota"
												id="numero_nota"><label><?php echo lang('SERIE_NOTA'); ?>&nbsp;&nbsp;</label><input
												type="text" class="form-control input-medium codigo" name="serie_nota"
												id="serie_nota">
											&nbsp;&nbsp;
											<button type="button" id="inutilizar"
												class="btn <?php echo $core->primeira_cor; ?>"><i class="fa fa-ban" /></i>
												<?php echo lang('NOTA_INUTILIZAR'); ?></button>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable-asc ">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('ID'); ?></th>
												<th><?php echo lang('DATA_NOTA'); ?></th>
												<th><?php echo lang('EMPRESA'); ?></th>
												<th><?php echo lang('MODELO'); ?></th>
												<th><?php echo lang('OPERACAO'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $produto->getNFCeInutilizadas();
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo $exrow->controle; ?></td>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo exibedata($exrow->data_emissao); ?></td>
														<td><?php echo $exrow->empresa; ?></td>
														<td><?php echo modelo($exrow->modelo); ?></td>
														<td><?php echo operacao($exrow->operacao); ?></td>
														<td><?php echo $exrow->numero_nota; ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
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
	<?php
	case "inutilizarNFe":
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#inutilizarNFE').click(function () {
					var numero_nota = $("#numero_nota").val();
					var serie_nota = $("#serie_nota").val();
					var id_empresa = $("#id_empresa").val();
					window.open('nfe_inutilizar.php?numero_nota=' + numero_nota + '&serie_nota=' + serie_nota + '&id_empresa=' + id_empresa, 'Inutilizar Notas fiscais', 'width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
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
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_INUTILIZADAS_NFE'); ?></small>
						</h1>
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
										<i class="fa fa-ban font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('NOTA_INUTILIZADAS_NFE'); ?></span>
									</div>
								</div>
								<div class="note note-warning">
									<h4 class="block"><b><?php echo lang('ATENCAO'); ?></b></h4>
									<p><?php echo lang('NOTA_INUTILIZAR_NFE_ATENCAO'); ?></p>
								</div>
								<div class="portlet-body form">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" id="id_empresa" name="id_empresa"
												data-placeholder="<?php echo lang('SELECIONE_EMPRESA'); ?>">
												<?php
												$retorno_row = $empresa->getEmpresas();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->nome; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											<br />
											<br />
											<label><?php echo lang('NUMERO_NOTA'); ?>&nbsp;&nbsp;</label><input type="text"
												class="form-control input-medium codigo" name="numero_nota"
												id="numero_nota"><label><?php echo lang('SERIE_NOTA'); ?>&nbsp;&nbsp;</label><input
												type="text" class="form-control input-medium codigo" name="serie_nota"
												id="serie_nota">
											&nbsp;&nbsp;
											<button type="button" id="inutilizarNFE"
												class="btn <?php echo $core->primeira_cor; ?>"><i class="fa fa-ban" /></i>
												<?php echo lang('NOTA_INUTILIZAR_NFE'); ?></button>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable-asc ">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('ID'); ?></th>
												<th><?php echo lang('DATA_NOTA'); ?></th>
												<th><?php echo lang('EMPRESA'); ?></th>
												<th><?php echo lang('MODELO'); ?></th>
												<th><?php echo lang('OPERACAO'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $produto->getNFeInutilizadas();
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo $exrow->controle; ?></td>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo exibedata($exrow->data_emissao); ?></td>
														<td><?php echo $exrow->empresa; ?></td>
														<td><?php echo modelo($exrow->modelo); ?></td>
														<td><?php echo operacao($exrow->operacao); ?></td>
														<td><?php echo $exrow->numero_nota; ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
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
	<?php
	case "chaveacesso":
		$operacao = (get('operacao')) ? get('operacao') : '';
		$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0;
		$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#id_empresa').change(function () {
					var id_empresa = $("#id_empresa").val();
					var mes_ano = $("#mes_ano").val();
					var operacao = $("#operacao").val();
					window.location.href = 'index.php?do=notafiscal&acao=chaveacesso&id_empresa=' + id_empresa + '&mes_ano=' + mes_ano + '&operacao=' + operacao;
				});
				$('#mes_ano').change(function () {
					var id_empresa = $("#id_empresa").val();
					var mes_ano = $("#mes_ano").val();
					var operacao = $("#operacao").val();
					window.location.href = 'index.php?do=notafiscal&acao=chaveacesso&id_empresa=' + id_empresa + '&mes_ano=' + mes_ano + '&operacao=' + operacao;
				});
				$('#operacao').change(function () {
					var id_empresa = $("#id_empresa").val();
					var mes_ano = $("#mes_ano").val();
					var operacao = $("#operacao").val();
					window.location.href = 'index.php?do=notafiscal&acao=chaveacesso&id_empresa=' + id_empresa + '&mes_ano=' + mes_ano + '&operacao=' + operacao;
				});
				$('#imprimir').click(function () {
					var id_empresa = $("#id_empresa").val();
					var mes_ano = $("#mes_ano").val();
					var operacao = $("#operacao").val();
					window.open('pdf_chaveacesso.php?id_empresa=' + id_empresa + '&mes_ano=' + mes_ano + '&operacao=' + operacao, 'Imprimir Notas fiscais', 'width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
				});
				$('#imprimir_xml').click(function () {
					var id_empresa = $("#id_empresa").val();
					if (!id_empresa) {
						alert('Selecione uma empresa para gerar o arquivo com XMLs.');
						return false;
					}
					var mes_ano = $("#mes_ano").val();
					window.open('nfe_xml_download_todos.php?id_empresa=' + id_empresa + '&mes_ano=' + mes_ano, 'Imprimir Notas fiscais', 'width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
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
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('NOTA_LISTAR_CHAVEACESSO'); ?></small>
						</h1>
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
										<i class="fa fa-file-code-o font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('NOTA_LISTAR_CHAVEACESSO'); ?></span>
									</div>
								</div>
								<div class="portlet-body form">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="mes_ano" id="mes_ano"
												data-placeholder="<?php echo lang('SELECIONE_MES'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $produto->getListaMesNF();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->mes_ano; ?>" <?php if ($srow->mes_ano == $mes_ano)
															   echo 'selected="selected"'; ?>>
															<?php echo exibeMesAno($srow->mes_ano, true, true); ?>
														</option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<select class="select2me form-control input-large" id="operacao" name="operacao"
												data-placeholder="<?php echo lang('SELECIONE_OPERACAO'); ?>">
												<option value=""></option>
												<option value="1" <?php if ('1' == $operacao)
													echo 'selected="selected"'; ?>>
													<?php echo 'ENTRADA'; ?>
												</option>
												<option value="2" <?php if ('2' == $operacao)
													echo 'selected="selected"'; ?>>
													<?php echo 'SAIDA'; ?>
												</option>
											</select>
											<br />
											<br />
											<select class="select2me form-control input-large" id="id_empresa" name="id_empresa"
												data-placeholder="<?php echo lang('SELECIONE_EMPRESA'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $empresa->getEmpresas();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_empresa)
															   echo 'selected="selected"'; ?>><?php echo $srow->nome; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<button type="button" id="imprimir" class="btn yellow-casablanca"><i
													class="fa fa-print" /></i> <?php echo lang('IMPRIMIR'); ?></button>

											<br><br><br>

											<div class="row">
												<!-- BOTAO BAIXA TODOS XML SAIDA CUPOM FISCAL NFC-E  -->
												<div class="col-sm-6">
													<a href="controller.php?downloadCupomFiscal=1&data_inicio=<?php echo dataInicioMes($mes_ano); ?>&data_final=<?php echo dataFinalMes($mes_ano); ?>"
														class="btn purple">
														<i class="fa fa-download"></i> &nbsp;&nbsp;
														<?php echo lang('IMPRIMIR_TODOS_CUPOM_FISCAL'); ?>
													</a>
													<span
														class="help-block"><?php echo lang('IMPRIMIR_TODOS_CUPOM_FISCAL_OBS'); ?></span>
												</div>

												<div class="col-sm-5">
													<!-- BOTAO DE BAIXAR XML DE ENTRADA APARTIR DA DATA DE ENTRADA -->
													<a href="controller.php?downloadXMLEntrada=1&data_inicio=<?php echo dataInicioMes($mes_ano); ?>&data_final=<?php echo dataFinalMes($mes_ano); ?>"
														class="btn btn-info">
														<i class="fa fa-download"></i> &nbsp;&nbsp;
														<?php echo lang('IMPRIMIR_TODOS_XML_ENTRADA'); ?>
													</a>
													<span
														class="help-block"><?php echo lang('IMPRIMIR_TODOS_XML_ENTRADA_OBS'); ?></span>
												</div>
											</div>

											<div class="row">
												<!-- BOTAO BAIXA TODOS XML SAIDA NF-E  -->
												<div class="col-sm-6">
													<a href="controller.php?downloadXMLSaida=1&data_inicio=<?php echo dataInicioMes($mes_ano); ?>&data_final=<?php echo dataFinalMes($mes_ano); ?>"
														class="btn green-turquoise">
														<i class="fa fa-download"></i>&nbsp;&nbsp;
														<?php echo lang('IMPRIMIR_TODOS_XML_SAIDA'); ?>
													</a>
													<span
														class="help-block"><?php echo lang('IMPRIMIR_TODOS_XML_SAIDA_OBS'); ?></span>
												</div>

												<!-- ==================== BOTAO DE IMPRIMIR XML DE ENTRADA A PARTIR DA DATA DE EMISSAO ==================== -->
												<div class="col-sm-6">
													<a href="controller.php?downloadXMLEntradaDtEmissao=1&data_inicio=<?php echo dataInicioMes($mes_ano); ?>&data_final=<?php echo dataFinalMes($mes_ano); ?>"
														class="btn btn-warning">
														<i class="fa fa-download"></i> &nbsp;&nbsp;
														<?php echo lang('IMPRIMIR_TODOS_XML_EMISSAO'); ?>
													</a>
													<span
														class="help-block"><?php echo lang('IMPRIMIR_TODOS_XML_EMISSAO_OBS'); ?></span>

												</div>
												<br><br>
												<!-- =================================================================== -->
											</div>

											<div class="row">
												<!-- BOTAO BAIXA TODOS PDF SAIDA CUPOM FISCAL NFC-E  -->
												<div class="col-sm-6">
													<a href="controller.php?downloadPDFCupomFiscal=1&data_inicio=<?php echo dataInicioMes($mes_ano); ?>&data_final=<?php echo dataFinalMes($mes_ano); ?>"
														class="btn blue-soft">
														<i class="fa fa-download"></i> &nbsp;&nbsp;
														<?php echo lang('IMPRIMIR_TODOS_PDF_CUPOM_FISCAL'); ?>
													</a>
													<span
														class="help-block"><?php echo lang('IMPRIMIR_TODOS_PDF_CUPOM_FISCAL_OBS'); ?></span>
												</div>

												<!-- BOTAO BAIXA TODOS PDF SAIDA NF-E  -->
												<div class="col-sm-6">
													<a href="controller.php?downloadPDFSaida=1&data_inicio=<?php echo dataInicioMes($mes_ano); ?>&data_final=<?php echo dataFinalMes($mes_ano); ?>"
														class="btn green-seagreen">
														<i class="fa fa-download"></i>&nbsp;&nbsp;
														<?php echo lang('IMPRIMIR_TODOS_PDF_SAIDA'); ?>
													</a>
													<span
														class="help-block"><?php echo lang('IMPRIMIR_TODOS_PDF_SAIDA_OBS'); ?></span>
												</div>
											</div>

											<div class="row">
												<!-- BOTAO BAIXA TODOS XML SAIDA CUPOM FISCAL NFC-E CANCELADO -->
												<div class="col-sm-6">
													<a href="controller.php?downloadCupomFiscalCancelado=1&data_inicio=<?php echo dataInicioMes($mes_ano); ?>&data_final=<?php echo dataFinalMes($mes_ano); ?>"
														class="btn red">
														<i class="fa fa-download"></i> &nbsp;&nbsp;
														<?php echo lang('IMPRIMIR_TODOS_CUPOM_FISCAL_CANCELADO'); ?>
													</a>
													<span
														class="help-block"><?php echo lang('IMPRIMIR_TODOS_CUPOM_FISCAL_OBS_CANCELADO'); ?></span>
												</div>

												<!-- BOTAO BAIXA TODOS XML SAIDA NF-E CANCELADO -->
												<div class="col-sm-6">
													<a href="controller.php?downloadXMLSaidaCancelado=1&data_inicio=<?php echo dataInicioMes($mes_ano); ?>&data_final=<?php echo dataFinalMes($mes_ano); ?>"
														class="btn red-haze">
														<i class="fa fa-download"></i>&nbsp;&nbsp;
														<?php echo lang('IMPRIMIR_TODOS_XML_SAIDA_CANCELADO'); ?>
													</a>
													<span
														class="help-block"><?php echo lang('IMPRIMIR_TODOS_XML_SAIDA_OBS_CANCELADO'); ?></span>
												</div>
											</div>

										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable-asc ">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('ID'); ?></th>
												<th><?php echo lang('DATA_NOTA'); ?></th>
												<th><?php echo lang('EMPRESA'); ?></th>
												<th><?php echo lang('MODELO'); ?></th>
												<th><?php echo lang('OPERACAO'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
												<th><?php echo lang('VALOR_NOTA'); ?></th>
												<th><?php echo lang('CHAVE_ACESSO'); ?></th>
												<th width="110px"><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $produto->getNotaFiscalChaveAcesso($mes_ano, $id_empresa, $operacao);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													if ((($exrow->fiscal == 1) && ($exrow->status_enotas == 'Autorizada')) || ($exrow->operacao == 1))
														$total += $exrow->valor;
													?>
													<tr <?php echo ($exrow->inativo == 1) ? "class='font-red'" : ""; ?>>
														<td><?php echo $exrow->numero; ?></td>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo exibedata($exrow->data_emissao); ?></td>
														<td><?php echo $exrow->empresa; ?></td>
														<td><?php echo modelo($exrow->modelo); ?></td>
														<td><?php echo operacao($exrow->operacao, true); ?></td>
														<td><?php echo $exrow->numero_nota; ?></td>
														<td><?php echo ((($exrow->fiscal == 1) && ($exrow->status_enotas == 'Autorizada')) || ($exrow->operacao == 1)) ? moedap($exrow->valor) : '-'; ?>
														</td>
														<td><?php echo ($exrow->chaveacesso) ? $exrow->chaveacesso : (($exrow->status_enotas == 'Inutilizada') ? 'NUMERACAO INUTILIZADA' : $exrow->status_enotas); ?>
														</td>
														<td>
															<?php if ($exrow->chaveacesso): ?>
																<?php if ($exrow->fiscal == 1): ?>
																	<?php if (!$exrow->link_danfe or $exrow->link_danfe == ""): ?>
																		<?php if ($exrow->modelo == 6): ?>
																			<a href="javascript:void(0);" class="btn btn-sm purple"
																				onclick="javascript:void window.open('nfc_download.php?id=<?php echo $exrow->id; ?>','<?php echo lang('ENOTAS_PDF') . '-' . $exrow->id; ?>','width=300,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
																					class="fa fa-file-pdf-o"></i></a>
																			<a href="javascript:void(0);" class="btn btn-sm green-turquoise"
																				onclick="javascript:void window.open('nfc_xml.php?id=<?php echo $exrow->id; ?>','<?php echo lang('ENOTAS_XML') . '-' . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
																					class="fa fa-file-excel-o"></i></a>
																		<?php else: ?>
																			<a href="javascript:void(0);" class="btn btn-sm purple"
																				onclick="javascript:void window.open('nfe_download.php?id=<?php echo $exrow->id; ?>','<?php echo lang('ENOTAS_PDF') . '-' . $exrow->id; ?>','width=300,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
																					class="fa fa-file-pdf-o"></i></a>
																			<a href="javascript:void(0);" class="btn btn-sm green-turquoise"
																				onclick="javascript:void window.open('nfe_xml.php?id=<?php echo $exrow->id; ?>','<?php echo lang('ENOTAS_XML') . '-' . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
																					class="fa fa-file-excel-o"></i></a>
																		<?php endif; ?>
																	<?php else: ?>
																		<a href="<?php echo str_replace("http://", "https://", $exrow->link_danfe); ?>"
																			class="btn btn-sm purple"><i class="fa fa-file-pdf-o"></i></a>
																		<a href="<?php echo str_replace("http://", "https://", $exrow->link_download_xml); ?>"
																			class="btn btn-sm green-turquoise"><i
																				class="fa fa-file-excel-o"></i></a>
																	<?php endif; ?>
																<?php elseif ($exrow->status_enotas == 'Inutilizada'): echo $exrow->status_enotas; ?>
																<?php elseif ($exrow->nome_arquivo != ''): ?>
																	<a href="javascript:void(0);" class="btn btn-sm green-turquoise"
																		onclick="javascript:void window.open('<?php echo "./uploads/data/" . $exrow->nome_arquivo; ?>','<?php echo lang('ENOTAS_XML'); ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
																			class="fa fa-file-excel-o"></i></a>
																<?php endif; ?>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="7"><strong><?php echo lang('TOTAL'); ?></strong></td>
													<td><strong><?php echo moedap($total); ?></strong></td>
													<td colspan="2"></td>
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

			<div id="overlay">
				<span class="loader"></span>
			</div>

		</div>
		<!-- FINAL CONTEUDO DA PAGINA -->
		<?php break; ?>
	<?php
	case "sintegra":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$produtos_row = $produto->getProdutosPendentes();

		$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#mes_ano').change(function () {
					var mes_ano = $("#mes_ano").val();
					window.location.href = 'index.php?do=notafiscal&acao=sintegra&mes_ano=' + mes_ano;
				});
				$('#download_sintegra').click(function () {
					var mes_ano = $("#mes_ano").val();
					window.open('sintegra.php?mes_ano=' + mes_ano + '&entrada=0');
				});

				$('#download_sintegra_entrada').click(function () {
					var mes_ano = $("#mes_ano").val();
					window.open('sintegra.php?mes_ano=' + mes_ano + '&entrada=1');
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
						<h1><?php echo lang('SINTEGRA_ARQUIVO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('SINTEGRA_DOWNLOAD'); ?></small></h1>
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
										<i class="fa fa-file-code-o font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('SINTEGRA_DOWNLOAD'); ?></span>
									</div>
								</div>
								<div class="portlet-body form">
									<form autocomplete="off" class="form-inline">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<select class="select2me form-control input-large"
																	name="mes_ano" id="mes_ano"
																	data-placeholder="<?php echo lang('SELECIONE_MES'); ?>">
																	<option value=""></option>
																	<?php
																	$retorno_row = $produto->getListaMesNF();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																			?>
																			<option value="<?php echo $srow->mes_ano; ?>" <?php if ($srow->mes_ano == $mes_ano)
																				   echo 'selected="selected"'; ?>>
																				<?php echo exibeMesAno($srow->mes_ano, true, true); ?>
																			</option>
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
												<div class="col-md-12">
													<br><br>
												</div>
												<?php if ($produtos_row): ?>
													<div class="col-md-12">
														<div class="row">
															<div class="note note-warning">
																<h4 class="block"><?= lang('ATENCAO'); ?></h4>
																<p><?= lang('SINTEGRA_DOWNLOAD_ATENCAO'); ?></p>
																<p><?= lang('SINTEGRA_DOWNLOAD_PENDENTES'); ?></p>
																<?php
																foreach ($produtos_row as $prow) {
																	?>
																	<p><a
																			href="index.php?do=notafiscal&acao=visualizar&id=<?= $prow->id_nota; ?>">NF-e
																			<?= $prow->numero_nota . ' - ' . exibedata($prow->data_emissao); ?></a>
																	</p>
																	<?php
																}
																?>
															</div>
														</div>
													</div>
												<?php else: ?>
													<div class="col-md-12">
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<button type="button" id="download_sintegra"
																		class="btn btn-primary"><i class="fa fa-file-code-o"></i>
																		<?php echo lang('SINTEGRA_DOWNLOAD_EMISSAO'); ?></button>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<button type="button" id="download_sintegra_entrada"
																		class="btn btn-warning"><i class="fa fa-file-code-o"></i>
																		<?php echo lang('SINTEGRA_DOWNLOAD_ENTRADA'); ?></button>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>
												<?php endif; ?>
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
		<?php break; ?>
	<?php
	case "sintegrainventario":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y');
		$produtos_row = $produto->getProdutosPendentes();
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#mes_ano').change(function () {
					var mes_ano = $("#mes_ano").val();
					window.location.href = 'index.php?do=notafiscal&acao=sintegrainventario&mes_ano=' + mes_ano;
				});

				$('#download_sintegra_inventario_fiscal').click(function () {
					var mes_ano = $("#mes_ano").val();
					window.open('sintegra.php?mes_ano=' + mes_ano + '&inventario_fiscal=1&entrada=0');
				});

				$('#download_sintegra_inventario_fiscal_entrada').click(function () {
					var mes_ano = $("#mes_ano").val();
					window.open('sintegra.php?mes_ano=' + mes_ano + '&inventario_fiscal=1&entrada=1');
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
						<h1><?php echo lang('SINTEGRA_ARQUIVO_INVENTARIO_FISCAL'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('SINTEGRA_DOWNLOAD_INVENTARIO_FISCAL'); ?></small>
						</h1>
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
										<i class="fa fa-file-code-o font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('SINTEGRA_DOWNLOAD_INVENTARIO_FISCAL'); ?></span>
									</div>
								</div>
								<div class="portlet-body form">
									<form autocomplete="off" class="form-inline">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<select class="select2me form-control input-large"
																	name="mes_ano" id="mes_ano"
																	data-placeholder="<?php echo lang('SELECIONE_MES'); ?>">
																	<option value=""></option>
																	<?php
																	$retorno_row = $produto->getListaMesNF();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																			?>
																			<option value="<?php echo $srow->mes_ano; ?>" <?php if ($srow->mes_ano == $mes_ano)
																				   echo 'selected="selected"'; ?>>
																				<?php echo exibeMesAno($srow->mes_ano, true, true); ?>
																			</option>
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
												<div class="col-md-12">
													<br><br>
												</div>
												<?php if ($produtos_row): ?>
													<div class="col-md-12">
														<div class="row">
															<div class="note note-warning">
																<h4 class="block"><?= lang('ATENCAO'); ?></h4>
																<p><?= lang('SINTEGRA_DOWNLOAD_ATENCAO'); ?></p>
																<p><?= lang('SINTEGRA_DOWNLOAD_PENDENTES'); ?></p>
																<?php
																foreach ($produtos_row as $prow) {
																	?>
																	<p><a
																			href="index.php?do=notafiscal&acao=visualizar&id=<?= $prow->id_nota; ?>">NF-e
																			<?= $prow->numero_nota . ' - ' . exibedata($prow->data_emissao); ?></a>
																	</p>
																	<?php
																}
																?>
															</div>
														</div>
													</div>
												<?php else: ?>
													<div class="col-md-12">
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<button type="button" id="download_sintegra_inventario_fiscal"
																		class="btn btn-primary"><i class="fa fa-file-code-o"></i>
																		<?php echo lang('SINTEGRA_DOWNLOAD_INVENTARIO_FISCAL_EMISSAO'); ?></button>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<button type="button"
																		id="download_sintegra_inventario_fiscal_entrada"
																		class="btn btn-warning"><i class="fa fa-file-code-o"></i>
																		<?php echo lang('SINTEGRA_DOWNLOAD_INVENTARIO_FISCAL_ENTRADA'); ?></button>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>
												<?php endif; ?>



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
		<?php break; ?>
	<?php
	case "duplicatas":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('DUPLICATAS'); ?></small></h1>
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
										<i class="fa fa-usd font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('DUPLICATAS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable dataTable-asc">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('VENCIMENTO'); ?></th>
												<th><?php echo lang('NOME'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
												<th><?php echo lang('DATA_NOTA'); ?></th>
												<th><?php echo lang('DUPLICATA'); ?></th>
												<th><?php echo lang('VALOR'); ?></th>
												<th><?php echo lang('PAGAMENTO'); ?></th>
												<th><?php echo lang('STATUS'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $produto->getDuplicatas();
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$status = '-';
													if ($exrow->pago == 0) {
														$status = "<span class='label label-sm bg-blue'>A PAGAR</span>";
													} elseif ($exrow->pago == 1) {
														$status = "<span class='label label-sm bg-green'>PAGA</span>";
													} elseif ($exrow->pago == 2) {
														$status = "<span class='label label-sm bg-yellow'>PENDENTE</span>";
													}
													?>
													<tr>
														<td><?php echo $exrow->controle; ?></td>
														<td><?php echo exibedata($exrow->data_vencimento); ?></td>
														<td><?php echo $exrow->razao_social; ?></td>
														<td><a
																href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota; ?>"><?php echo $exrow->numero_nota; ?></a>
														</td>
														<td><?php echo exibedata($exrow->data_emissao); ?></td>
														<td><?php echo $exrow->duplicata; ?></td>
														<td><?php echo moedap($exrow->valor); ?></td>
														<td><?php echo exibedata($exrow->data_pagamento); ?></td>
														<td><?php echo $status; ?></td>
														<td>
															<a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota; ?>"
																class="btn btn-sm grey-cascade"
																title="<?php echo lang('VISUALIZAR') . ': ' . $exrow->numero_nota; ?>"><i
																	class="fa fa-search"></i></a>
															<?php if ($exrow->pago == 2): ?>
																<a href="javascript:void(0);" class="btn btn-sm yellow emdespesa"
																	id="<?php echo $exrow->id; ?>"
																	title="<?php echo lang('GERAR_DESPESA'); ?>"><i
																		class="fa fa-usd"></i></a>
															<?php endif; ?>
															<?php if ($usuario->is_Master()): ?>
																<a href='javascript:void(0);' class='btn btn-sm red apagar'
																	id='<?php echo $exrow->id; ?>' acao='apagarDespesas'
																	title='<?php echo lang('FINANCEIRO_DESPESAS_APAGAR') . $exrow->descricao; ?>'><i
																		class='fa fa-times'></i></a>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
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
		<?php break; ?>
	<?php
	case "das":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y', strtotime('-30 days'));
		$id_empresa = (get('id_empresa')) ? get('id_empresa') : session('idempresa');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.imprimirdas').click(function () {
					var id_empresa = $("#id_empresa").val();
					var mes_ano = $("#mes_ano").val();
					window.open('pdf_das.php?id_empresa=' + id_empresa + '&mes_ano=' + mes_ano, '<?php echo lang('RELATORIO_DAS'); ?>', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
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
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('RELATORIO_DAS'); ?></small></h1>
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
										<i class="fa fa-file-text font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('RELATORIO_DAS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_empresa" id="id_empresa"
												data-placeholder="<?php echo lang('SELECIONE_EMPRESA'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $empresa->getEmpresas();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value='<?php echo $srow->id; ?>' <?php if ($srow->id == $id_empresa)
															   echo 'selected="selected"'; ?>><?php echo $srow->nome; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;
											<select class="select2me form-control input-large" name="mes_ano" id="mes_ano"
												data-placeholder="<?php echo lang('SELECIONE_MES'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $gestao->getListaMes("nota_fiscal", "data_emissao", false, "DESC");
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->mes_ano; ?>" <?php if ($srow->mes_ano == $mes_ano)
															   echo 'selected="selected"'; ?>>
															<?php echo exibeMesAno($srow->mes_ano, true, true); ?>
														</option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;
											<a href='javascript:void(0);'
												class='btn <?php echo $core->primeira_cor; ?> imprimirdas'
												title='<?php echo lang('IMPRIMIR'); ?>'><i
													class='fa fa-print'>&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR'); ?></a>
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
		<?php break; ?>
	<?php
	case "faturas":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0;
		$mes_ano = (get('mes_ano')) ? get('mes_ano') : 0;

		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#buscar').click(function () {
					var id_empresa = $("#id_empresa").val();
					var mes_ano = $("#mes_ano").val();
					window.location.href = 'index.php?do=notafiscal&acao=faturas&id_empresa=' + id_empresa + '&mes_ano=' + mes_ano;
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
						<h1><?php echo lang('NOTA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FATURA_LISTAR'); ?></small></h1>
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
										<i class="fa fa-list font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('FATURA_LISTAR'); ?></span>
									</div>
								</div>
								<div class="portlet-body form">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="mes_ano" id="mes_ano"
												data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $gestao->getListaMes("nota_fiscal", "data_emissao", false, "DESC");
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->mes_ano; ?>" <?php if ($srow->mes_ano == $mes_ano)
															   echo 'selected="selected"'; ?>>
															<?php echo exibeMesAno($srow->mes_ano, true, true); ?>
														</option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;&nbsp;&nbsp;
											<select class="select2me form-control input-large" id="id_empresa" name="id_empresa"
												data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $empresa->getEmpresas();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_empresa)
															   echo 'selected="selected"'; ?>><?php echo $srow->nome; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											<br />
											<br />
											<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor; ?>"><i
													class="fa fa-search"></i> <?php echo lang('BUSCAR'); ?></button>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable dataTable-desc ">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('DATA_NOTA'); ?></th>
												<th><?php echo lang('EMPRESA'); ?></th>
												<th><?php echo lang('NOME'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
												<th><?php echo lang('VALOR_NOTA'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $produto->getFaturasMes($id_empresa, $mes_ano);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo $exrow->controle; ?></td>
														<td><?php echo exibedata($exrow->data_emissao); ?></td>
														<td><?php echo $exrow->empresa; ?></td>
														<td><a
																href="index.php?do=cadastro&acao=notafiscal&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->razao_social; ?></a>
														</td>
														<td><a
																href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id; ?>"><?php echo $exrow->numero_nota; ?></a>
														</td>
														<td><?php echo moedap($exrow->valor_nota); ?></td>
														<td>
															<a href="javascript:void(0);" class="btn btn-sm yellow-casablanca"
																onclick="javascript:void window.open('pdf_fatura.php?id=<?php echo $exrow->id; ?>','<?php echo lang('FATURA_VISUALIZAR'); ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
																	class="fa fa-print"></i></a>
															<a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id; ?>"
																class="btn btn-sm grey-cascade"
																title="<?php echo lang('VISUALIZAR') . ': ' . $exrow->razao_social; ?>"><i
																	class="fa fa-search"></i></a>
															<a href="index.php?do=notafiscal&acao=editar&id=<?php echo $exrow->id; ?>"
																class="btn btn-sm blue"
																title="<?php echo lang('EDITAR') . ': ' . $exrow->razao_social; ?>"><i
																	class="fa fa-pencil"></i></a>
															<?php if (!$exrow->inativo and !$exrow->fiscal): ?>
																<a href="javascript:void(0);" class="btn btn-sm red apagar"
																	id="<?php echo $exrow->id; ?>" acao="apagarNotaFiscal"
																	title="<?php echo lang('NOTA_APAGAR') . $exrow->razao_social; ?>"><i
																		class="fa fa-ban"></i></a>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
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
		<?php break; ?>
	<?php
	case "inventario":

		if ($core->tipo_sistema == 3)
			redirect_to("login.php");

		$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('Y');
		$id_empresa = (get('id_empresa')) ? get('id_empresa') : session('idempresa');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.imprimirinventario').click(function () {
					var id_empresa = $("#id_empresa").val();
					var mes_ano = $("#mes_ano").val();
					window.open('pdf_inventario_nfe.php?id_empresa=' + id_empresa + '&mes_ano=' + mes_ano, 'INVENTARIO FISCAL ' + mes_ano, 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
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
						<h1><?php echo lang('PRODUTO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_INVENTARIO'); ?></small>
						</h1>
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
										<i class="fa fa-file-text-o font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTO_INVENTARIO'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_empresa" id="id_empresa"
												data-placeholder="<?php echo lang('SELECIONE_EMPRESA'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $empresa->getEmpresas();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value='<?php echo $srow->id; ?>' <?php if ($srow->id == $id_empresa)
															   echo 'selected="selected"'; ?>><?php echo $srow->nome; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;
											<select class="select2me form-control input-large" name="mes_ano" id="mes_ano"
												data-placeholder="<?php echo lang('SELECIONE_ANO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $gestao->getListaAno("nota_fiscal", "data_emissao", "DESC");
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->mes_ano; ?>" <?php if ($srow->mes_ano == $mes_ano)
															   echo 'selected="selected"'; ?>>
															<?php echo $srow->mes_ano; ?>
														</option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;
											<a href='javascript:void(0);'
												class='btn <?php echo $core->primeira_cor; ?> imprimirinventario'
												title='<?php echo lang('IMPRIMIR'); ?>'><i
													class='fa fa-print'>&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR'); ?></a>
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
		<?php break; ?>
	<?php
	default: ?>
		<div class="imagem-fundo">
			<img src="assets/img/logo.png" border="0">
		</div>
		<?php break; ?>
<?php endswitch; ?>