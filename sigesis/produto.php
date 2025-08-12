<?php

/**
 * Produto
 *
 */
if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe não é permitido.');
?>
<?php switch (Filter::$acao):
	case "editar":
		$id_grupo = get('id_grupo');
		$id_categoria = get('id_categoria');

		?>
		<?php if (!$usuario->is_Administrativo()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif; ?>
		<?php $row = Core::getRowById("produto", Filter::$id);
		$produto_inativo = ($row->inativo) ? "readonly" : "";
		?>
		<!-- Plupload -->
		<link href="./assets/plugins/plupload/css/jquery.plupload.queue.css" rel="stylesheet" type="text/css" />
		<link href="./assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
		<link href="./assets/css/layout.css" rel="stylesheet">

		<script type="text/javascript" src="./assets/plugins/plupload/plupload.full.js"></script>
		<script type="text/javascript" src="./assets/plugins/plupload/jquery.plupload.queue.js"></script>
		<script type="text/javascript" src="./assets/plugins/plupload/i18n/pt_BR.js"></script>
		<script type="text/javascript" src="./assets/scripts/fileupload.js"></script>
		<script type="text/javascript" src="./assets/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
		<script>
			jQuery(document).ready(function () {
				FormFileUpload.init();
			});
		</script>
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
		<div id="produto-novo" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i
								class="fa fa-barcode">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_ADICIONAR'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="produto_form" id="produto_form"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
										<div class="col-md-9">
											<select class="select2me form-control input-large" name="id_produto"
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
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('QUANTIDADE'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control decimalp" name="quantidade">
										</div>
									</div>
									<div class="form-group">
										<label
											class="control-label col-md-3"><?php echo lang('PRODUTO_ADICIONAR_MATERIA_PRIMA'); ?></label>
										<div class="col-md-9">
											<input type="checkbox" name="materia_prima" class="make-switch"
												data-on-color="success" data-off-color="warning"
												data-on-text="<?php echo lang('SIM'); ?>"
												data-off-text="<?php echo lang('NAO'); ?>" value="1">
											<span
												class="help-block"><?php echo lang('PRODUTO_ADICIONAR_MATERIA_PRIMA_OBS'); ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input name="id_produto_kit" type="hidden" value="<?php echo Filter::$id; ?>" />
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarProdutoKit", "produto_form"); ?>
		</div>
		<div id="atributo-novo" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i
								class="fa fa-puzzle-piece">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_ATRIBUTO_ADICIONAR'); ?>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="atributo_form" id="atributo_form"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('ATRIBUTO'); ?></label>
										<div class="col-md-9">
											<select class="select2me form-control input-large" name="id_atributo"
												data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $produto->getAtributos();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->atributo; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('DESCRICAO'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control caps" name="descricao">
										</div>
									</div>
								</div>
							</div>
						</div>
						<input name="id_produto" type="hidden" value="<?php echo Filter::$id; ?>" />
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarProdutoAtributo", "atributo_form"); ?>
		</div>
		<div id="novo-fornecedor" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><?php echo lang('FORNECEDOR_ADICIONAR'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="fornecedor_form" id="fornecedor_form"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('FORNECEDOR'); ?></label>
										<div class="col-md-9">
											<select class="select2me form-control" name="id_cadastro"
												data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $cadastro->getCadastros('FORNECEDOR');
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>">
															<?php echo $srow->nome . " (" . formatar_cpf_cnpj($srow->cpf_cnpj) . ")"; ?>
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
										<label class="control-label col-md-3"><?php echo lang('QUANTIDADE_COMPRA'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control decimalp" name="quantidade_compra">
											<span class="help-block"><?php echo lang('QUANTIDADE_COMPRA_UNIDADES'); ?></span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('UNIDADE_COMPRA'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control caps" name="unidade_compra">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('PRAZO'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control inteiro" name="prazo">
											<span class="help-block"><?php echo lang('PRAZO_DIAS'); ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input name="valor_unitario" type="hidden" value="<?php echo $row->valor_custo; ?>" />
						<input name="codigobarras" type="hidden" value="<?php echo $row->codigobarras; ?>" />
						<input name="ncm" type="hidden" value="<?php echo $row->ncm; ?>" />
						<input name="id_produto" type="hidden" value="<?php echo Filter::$id; ?>" />
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarProdutoFornecedor", "fornecedor_form"); ?>
		</div>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('PRODUTO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_EDITAR'); ?></small></h1>
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
							<?php if ($row->inativo): ?>
								<div class="alert alert-danger">
									<strong>CANCELADO.</strong> Produto cancelado por
									<?php echo $row->usuario . ' em ' . exibedataHora($row->data); ?>.
								</div>
							<?php endif; ?>
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_EDITAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-12" style="text-align:center">
														<label class="control-label float-right"><strong
																class="bold italic font-red"
																style="font-size: 15px"><?php echo lang('OBS_*'); ?></strong></label>
													</div>
													<div class="col-md-12">
														<h4 class="form-section">
															<strong><?php echo lang('PRODUTO_INFO_IDENTIFICACAO'); ?></strong>
														</h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NOME'); ?><strong
																		class="bold italic font-red"
																		style="font-size: 20px"><?php echo lang('*'); ?></strong></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control caps" name="nome"
																		value="<?php echo $row->nome; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CODIGO'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control caps" name="codigo" maxlength="45"
																		value="<?php echo $row->codigo; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CODIGO_INTERNO'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control caps" name="codigo_interno"
																		maxlength="45"
																		value="<?php echo $row->codigo_interno; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTOQUE'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control"
																		value="<?php echo decimalp($row->estoque); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTOQUE_MINIMO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control" name="estoquemin"
																		value="<?php echo decimalp($row->estoque_minimo); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('OBSERVACAO'); ?></label>
																<div class="col-md-9">
																	<textarea <?php echo $produto_inativo; ?>
																		class="form-control caps" rows="3" name="observacao"
																		maxlength="100"><?php echo $row->observacao; ?></textarea>
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
																	class="control-label col-md-3"><?php echo lang('CODIGO_DE_BARRAS'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control caps" name="codigobarras"
																		maxlength="15"
																		value="<?php echo $row->codigobarras; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input <?php echo $produto_inativo; ?>
																				type="checkbox" class="md-check"
																				name="codigobarrasautomatico"
																				id="codigobarrasautomatico" value="1">
																			<label for="codigobarrasautomatico">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('CODIGO_DE_BARRAS_AUTO'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_CUSTO'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control moeda" name="valor_custo"
																		value="<?php echo moeda($row->valor_custo); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_AVISTA'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control moeda" name="valor_avista"
																		value="<?php echo moeda($row->valor_avista); ?>">
																	<span
																		class="help-block"><?php echo lang('VALOR_AVISTA_OBS'); ?></span>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check"
																				name="ecommerce" id="ecommerce"
																				<?= ($row->ecommerce > 0) ? 'checked' : '' ?>>
																			<label for="ecommerce">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('ECOMMERCE'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section">
															<strong><?php echo lang('PRODUTO_INFO_FISCAIS'); ?></strong>
														</h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NCM'); ?><strong
																		class="bold italic font-red"
																		style="font-size: 20px"><?php echo lang('*'); ?></strong></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control codigo" name="ncm" maxlength="8"
																		value="<?php echo $row->ncm; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CFOP_SAIDA'); ?><strong
																		class="bold italic font-red"
																		style="font-size: 20px"><?php echo lang('*'); ?></strong></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control inteiro" name="cfop" maxlength="4"
																		value="<?php echo $row->cfop; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CFOP_ENTRADA'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control inteiro" name="cfop_entrada"
																		maxlength="4" value="<?php echo $row->cfop_entrada; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEST'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control codigo" name="cest" maxlength="15"
																		value="<?php echo $row->cest; ?>">
																	<span
																		class="help-block"><?php echo lang('OBS_CEST'); ?></span>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CSOSN_CST'); ?><strong
																		class="bold italic font-red"
																		style="font-size: 20px"><?php echo lang('*'); ?></strong></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control inteiro" name="icms_cst"
																		maxlength="4" value="<?php echo $row->icms_cst; ?>">
																	<span
																		class="help-block"><?php echo lang('OBS_CSOSN_CST'); ?></span>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ICMS_ORIGEM'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control inteiro" name="icms_origem"
																		maxlength="1" value="<?php echo $row->icms_origem; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control decimal" name="icms_percentual"
																		value="<?php echo $row->icms_percentual; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS_ST'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control decimal" name="icms_percentual_st"
																		value="<?php echo $row->icms_percentual_st; ?>">
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
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_MVA'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control decimal" name="mva_percentual"
																		value="<?php echo $row->mva_percentual; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CST_PIS'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control inteiro" name="pis_cst"
																		maxlength="4" value="<?php echo $row->pis_cst; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_PIS'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control decimal" name="pis_aliquota"
																		value="<?php echo $row->pis_aliquota; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CST_COFINS'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control inteiro" name="cofins_cst"
																		maxlength="4" value="<?php echo $row->cofins_cst; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_COFINS'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control decimal" name="cofins_aliquota"
																		value="<?php echo $row->cofins_aliquota; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CST_IPI_CODIGO'); ?></label>
																<div class="col-md-9">
																	<select <?php echo $produto_inativo; ?>
																		class="select2me form-control" name="ipi_saida_codigo"
																		data-placeholder="<?php echo lang('SELECIONE_IPI_CODIGO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getIPISaida();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->codigo; ?>" <?php if ($srow->codigo == $row->ipi_saida_codigo)
																					   echo 'selected="selected"'; ?>>
																					<?php echo $srow->codigo . ' - ' . $srow->descricao; ?>
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
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_CST_IPI'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control decimal" name="ipi_cst"
																		value="<?php echo $row->ipi_cst; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('UNIDADE'); ?><strong
																		class="bold italic font-red"
																		style="font-size: 20px"><?php echo lang('*'); ?></strong></label>
																<div class="col-md-9">
																	<select <?php echo $produto_inativo; ?>
																		class="select2me form-control" name="unidade"
																		data-placeholder="<?php echo lang('SELECIONE_UNIDADE_MEDIDA'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getUnidadeMedida();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option
																					value="<?php echo $srow->unidade . '#' . $srow->descricao; ?>"
																					<?php if ($srow->unidade == $row->unidade)
																						echo 'selected="selected"'; ?>>
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
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('UNIDADE_TRIBUTAVEL'); ?></label>
																<div class="col-md-9">
																	<input <?php echo $produto_inativo; ?> type="text"
																		class="form-control caps" name="unidade_tributavel"
																		value="<?php echo $row->unidade_tributavel; ?>">
																	<span
																		class="help-block"><?php echo lang('UNIDADE_TRIBUTAVEL_TITULO'); ?></span>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12">
													<br><br>
												</div>

												<?php if (!$row->inativo): ?>

													<div class="col-md-12">
														<div class="col-md-12">
															<h4 class="form-section">
																<strong><?php echo lang('PRODUTO_INFO_GERAIS'); ?></strong>
															</h4>
														</div>
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('GRUPO'); ?></label>
																	<div class="col-md-9">
																		<select class="select2me form-control" name="id_grupo"
																			data-placeholder="<?php echo lang('SELECIONE_GRUPO'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $produto->getGrupos();
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $row->id_grupo)
																						   echo 'selected="selected"'; ?>>
																						<?php echo $srow->grupo; ?>
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
																		class="control-label col-md-3"><?php echo lang('CATEGORIA'); ?></label>
																	<div class="col-md-9">
																		<select class="select2me form-control" name="id_categoria"
																			data-placeholder="<?php echo lang('SELECIONE_CATEGORIA'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $produto->getCategorias();
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $row->id_categoria)
																						   echo 'selected="selected"'; ?>>
																						<?php echo $srow->categoria; ?>
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
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox">
																				<input type="checkbox" class="md-check"
																					name="monofasico" id="monofasico" value="1"
																					<?php if ($row->monofasico)
																						echo 'checked'; ?>>
																				<label for="monofasico">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PRODUTO_MONOFASICO'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox">
																				<input type="checkbox" class="md-check"
																					name="valida_estoque" id="valida_estoque"
																					value="1" <?php if ($row->valida_estoque)
																						echo 'checked'; ?>>
																				<label for="valida_estoque">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('ESTOQUE_VALIDA'); ?></label>
																				<span
																					class="help-block"><?php echo lang('OBS_ESTOQUE_VALIDA'); ?></span>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox">
																				<input type="checkbox" class="md-check" name="kit"
																					id="kit" value="1" <?php if ($row->kit)
																						echo 'checked'; ?>>
																				<label for="kit">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PRODUTO_KIT_E'); ?></label>
																				<span
																					class="help-block"><?php echo lang('OBS_PRODUTO_KIT_E'); ?></span>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox">
																				<input type="checkbox" class="md-check"
																					name="produto_balanca" id="produto_balanca"
																					value="1" <?php if ($row->produto_balanca)
																						echo 'checked'; ?>>
																				<label for="produto_balanca">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PRODUTO_BALANCA'); ?></label>
																			</div>
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
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox">
																				<input type="checkbox" class="md-check" name="grade"
																					id="grade" value="1" <?php if ($row->grade)
																						echo 'checked'; ?>>
																				<label for="grade">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('GRADE_VENDAS'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('FABRICANTE'); ?></label>
																	<div class="col-md-9">
																		<select class="select2me form-control" name="id_fabricante"
																			data-placeholder="<?php echo lang('SELECIONE_FABRICANTE'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $produto->getFabricantes();
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $row->id_fabricante)
																						   echo 'selected="selected"'; ?>>
																						<?php echo $srow->exibir_romaneio ? $srow->fabricante . " (EXIBE EM ROMANEIO)" : $srow->fabricante; ?>
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
																		class="control-label col-md-3"><?php echo lang('PESO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control decimalp" name="peso"
																			value="<?php echo $row->peso; ?>">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('LINK'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control" name="link"
																			value="<?php echo $row->link; ?>">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('PRAZO_TROCA'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control inteiro"
																			name="prazo_troca" maxlength="4"
																			value="<?php echo $row->prazo_troca; ?>"
																			placeholder="dias">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('DETALHES'); ?></label>
																	<div class="col-md-9">
																		<textarea class="form-control caps" rows="4"
																			name="detalhamento"><?php echo $row->detalhamento; ?></textarea>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>
													<div class="col-md-12">
														<br>
													</div>
													<div class="col-md-12">
														<div class="col-md-12">
															<h4 class="form-section">
																<strong><?php echo lang('PRODUTO_INFO_PETROLEO'); ?></strong>
															</h4>
														</div>
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('COD_ANP'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control caps" name="cod_anp"
																			value="<?php echo $row->anp; ?>">
																		<span
																			class="help-block"><?php echo lang('OBS_ANP'); ?></span>
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
																		class="control-label col-md-3"><?php echo lang('VALOR_PARTIDA'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control "
																			onInput="mascaraMoeda(event);" name="valor_partida"
																			value="<?php echo 'R$ ' . decimalp($row->valor_partida); ?>">
																		<span
																			class="help-block"><?php echo lang('OBS_ANP'); ?></span>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>
													<div class="col-md-12">
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-4"><strong
																			class="bold italic font-red"
																			style="font-size: 15px"><?php echo lang('OBS_*'); ?></strong></label>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>

												<?php endif; ?>

											</div>
										</div>
										<input name="id" type="hidden" value="<?php echo Filter::$id; ?>" />
										<input name="o_grupo" type="hidden" value="<?php echo $id_grupo; ?>" />
										<input name="o_categoria" type="hidden" value="<?php echo $id_categoria; ?>" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<?php if (!$row->inativo): ?>
																	<button type="button"
																		class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
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
									<?php echo $core->doForm("processarProduto"); ?>
									<!-- FINAL FORM-->
								</div>
							</div>
						</div>
					</div>
					<!-- FINAL DO ROW FORMULARIO -->
					<!-- INICO DO ROW ATRIBUTOS PRODUTO -->
					<?php if (!$row->inativo): ?>

						<div class="row">
							<div class="col-md-12">
								<!-- INICIO TABELA -->
								<div class="portlet light">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-puzzle-piece font-<?php echo $core->primeira_cor; ?>"></i>
											<span
												class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTO_ATRIBUTO'); ?></span>
										</div>
										<div class="actions btn-set">
											<a href="#atributo-novo" class="btn btn-sm <?php echo $core->primeira_cor; ?>"
												data-toggle="modal"><i
													class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR'); ?></a>
										</div>
									</div>
									<div class="portlet-body">
										<table class="table table-hover table-bordered table-striped table-advance">
											<thead>
												<tr>
													<th><?php echo lang('ATRIBUTO'); ?></th>
													<th><?php echo lang('DESCRICAO'); ?></th>
													<th><?php echo lang('EXIBIR_ROMANEIO'); ?></th>
													<th width="140px"><?php echo lang('ACOES'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$total = 0;
												$retorno_row = $produto->getProdutoAtributo(Filter::$id);
												if ($retorno_row):
													foreach ($retorno_row as $exrow):
														?>
														<tr>
															<td><?php echo $exrow->atributo; ?></td>
															<td><?php echo $exrow->descricao; ?></td>
															<td>
																<?php echo $exrow->exibir_romaneio == 1 ? '<span style="background-color: #20B551; color: #fff; padding: 5px">SIM</span>' : '<span style="background-color: #f00; color: #fff; padding: 5px">NÃO</span>' ?>
															</td>
															<td>
																<a href="javascript:void(0);" class="btn btn-sm red apagar"
																	id="<?php echo $exrow->id; ?>" acao="apagarProdutoAtributo"
																	title="<?php echo lang('PRODUTO_ATRIBUTO_APAGAR') . $exrow->atributo; ?>"><i
																		class="fa fa-times"></i></a>
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
							</div>
						</div>
						<!-- FINAL DO ROW ATRIBUTOS PRODUTO -->
						<!-- INICO DO ROW FORNECEDOR -->
						<div class="row">
							<div class="col-md-12">
								<!-- INICIO TABELA -->
								<div class="portlet light">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-truck font-<?php echo $core->primeira_cor; ?>"></i>
											<span
												class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('FORNECEDOR_LISTAR'); ?></span>
										</div>
										<div class="actions btn-set">
											<a href="#novo-fornecedor" class="btn btn-sm <?php echo $core->primeira_cor; ?>"
												data-toggle="modal"><i
													class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR'); ?></a>
										</div>
									</div>
									<div class="portlet-body">
										<table class="table table-bordered table-advance dataTable">
											<thead>
												<tr>
													<th><?php echo lang('PRODUTO'); ?></th>
													<th><?php echo lang('NOTA'); ?></th>
													<th><?php echo lang('FORNECEDOR'); ?></th>
													<th><?php echo lang('TELEFONE'); ?></th>
													<th><?php echo lang('CODIGO'); ?></th>
													<th><?php echo lang('PRAZO'); ?></th>
													<th><?php echo lang('QUANT_UNID'); ?></th>
													<th><?php echo lang('QUANT'); ?></th>
													<th><?php echo lang('UNIDADE'); ?></th>
													<th><?php echo lang('VALOR'); ?></th>
													<th><?php echo lang('DATA'); ?></th>
													<th><?php echo lang('ACOES'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$retorno_row = 0;
												$retorno_row = $produto->getFornecedoresProduto(Filter::$id);
												if ($retorno_row):
													foreach ($retorno_row as $exrow):
														$quantidade = $produto->getQuantNota($exrow->id_nota, $exrow->id);
														?>
														<tr>
															<td><?php echo $exrow->produto; ?></td>
															<td><a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota; ?>"
																	target="_blank"><?php echo $exrow->numero_nota; ?></a></td>
															<td><?php echo $exrow->fornecedor; ?></td>
															<td><?php echo $exrow->telefone . " " . $exrow->celular; ?></td>
															<td><?php echo $exrow->codigonota; ?></td>
															<td><?php echo $exrow->prazo; ?></td>
															<td><?php echo $exrow->quantidade_compra; ?></td>
															<td><?php echo $quantidade; ?></td>
															<td><?php echo $exrow->unidade_compra; ?></td>
															<td><?php echo moedap($exrow->valor_unitario); ?></td>
															<td><?php echo exibedata($exrow->data_emissao); ?></td>
															<td>
																<a href="javascript:void(0);" class="btn btn-sm red apagar"
																	id="<?php echo $exrow->id; ?>" acao="apagarProdutoFornecedor"
																	title="<?php echo lang('FORNECEDOR_APAGAR') . $exrow->fornecedor; ?>"><i
																		class="fa fa-times"></i></a>
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
							</div>
						</div>
						<!-- FINAL DO ROW FORNECEDOR -->
						<?php if ($row->kit): ?>
							<!-- INICO DO ROW KIT PRODUTO -->
							<div class="row">
								<div class="col-md-12">
									<!-- INICIO TABELA -->
									<div class="portlet light">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-barcode font-<?php echo $core->primeira_cor; ?>"></i>
												<span
													class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTO_KIT'); ?></span>
											</div>
											<div class="actions btn-set">
												<a href="#produto-novo" class="btn btn-sm <?php echo $core->primeira_cor; ?>"
													data-toggle="modal"><i
														class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR'); ?></a>
											</div>
										</div>
										<div class="portlet-body">
											<table class="table table-hover table-bordered table-striped table-advance">
												<thead>
													<tr>
														<th><?php echo lang('PRODUTO'); ?></th>
														<th><?php echo lang('QUANTIDADE'); ?></th>
														<th><?php echo lang('PRODUTO_ADICIONAR_MATERIA_PRIMA'); ?></th>
														<th><?php echo lang('VALOR_CUSTO'); ?></th>
														<th><?php echo lang('ESTOQUE'); ?></th>
														<th width="140px"><?php echo lang('ACOES'); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php
													$total = 0;
													$retorno_row = $produto->getProdutokit(Filter::$id);
													if ($retorno_row):
														foreach ($retorno_row as $exrow):
															$total += $exrow->valor_custo;
															$materia_prima = ($exrow->materia_prima) ?
																"<span class='label label-success'>" . lang('SIM') . "</span>" :
																"<span class='label label-warning'>" . lang('NAO') . "</span>";
															?>
															<tr>
																<td><?= $exrow->nome; ?></td>
																<td><?= decimalp($exrow->quantidade); ?></td>
																<td><?= $materia_prima; ?></td>
																<td><?= moeda($exrow->valor_custo); ?></td>
																<td><?= decimalp($exrow->estoque); ?></td>
																<td>
																	<a href="javascript:void(0);" class="btn btn-sm red apagarProdutoKit"
																		id="<?php echo $exrow->id; ?>"
																		id_produto_kit="<?php echo $exrow->id_produto_kit; ?>"
																		id_produto="<?php echo $exrow->id_produto; ?>" acao="apagarProdutoKit"
																		title="<?php echo lang('PRODUTO_APAGAR') . $exrow->nome; ?>">
																		<i class="fa fa-times"></i>
																	</a>
																</td>
															</tr>
															<?php
														endforeach;
														unset($exrow);
														?>
														<tr>
															<td colspan="3"><strong><?php echo lang('VALOR_TOTAL'); ?></strong></td>
															<td><strong><?php echo moeda($total); ?></strong></td>
															<td colspan="2"></td>
														</tr>
														<?php
													endif;
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!-- FINAL DO ROW KIT PRODUTO -->
						<?php endif; ?>
						<!-- INICIO DO ROW IMAGEM PRODUTO -->
						<div class="row">
							<div class="col-md-12">
								<!-- BEGIN PAGE CONTENT INNER -->
								<div class="portlet light">
									<form action="" autocomplete="off" class="form-inline" method="post" id="admin_form"
										name="admin_form">
										<div class="portlet-body">
											<div class="row">
												<div class="col-md-12">
													<div class="plupload"></div>
													<input name="processarProdutoImagem" type="hidden" value="1" />
													<input name="id" type="hidden" value="<?php echo Filter::$id; ?>" />
												</div>
											</div>
										</div>
										<?php if ($row->imagem): ?>
											<div class="portlet-body">
												<div class="table-scrollable table-scrollable-borderless">
													<table class="table table-bordered table-hover table-light">
														<thead>
															<tr role="row" class="heading">
																<th><?php echo lang('IMAGEM'); ?></th>
																<th width="110px"><?php echo lang('ACOES'); ?></th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	<a href="<?php echo UPLOADSHTML . $row->imagem; ?>"
																		class="fancybox-button" data-rel="fancybox-button">
																		<img height="80px"
																			src="<?php echo UPLOADSHTML . $row->imagem; ?>" alt=""></a>
																</td>
																<td>
																	<a href="javascript:void(0);" class="btn btn-sm grey-cascade"
																		onclick="javascript:void window.open('<?php echo UPLOADSHTML . $row->imagem; ?>','<?php echo lang('VISUALIZAR'); ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
																			class="fa fa-search"></i></a>
																	<a href="javascript:void(0);" class="btn btn-sm red apagar"
																		id="<?php echo $row->id; ?>" acao="apagarProdutoImagem"
																		title="<?php echo lang('PRODUTO_IMAGEM_APAGAR') . $row->id; ?>"><i
																			class="fa fa-times"></i></a>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										<?php endif; ?>
									</form>
								</div>
								<!-- END PAGE CONTENT INNER -->
							</div>
						</div>

					<?php endif; ?>
					<!-- FINAL DO ROW IMAGEM PRODUTO -->
				</div>
			</div>
			<!-- FINAL DOS MODULOS DA PAGINA -->
		</div>
		<!-- FINAL CONTEUDO DA PAGINA -->
		<?php break; ?>
	<?php
	case "atualizacodbarras": ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('PRODUTO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_ATUALIZAR_CODBARRAS_TITULO'); ?></small>
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
										<i
											class="fa fa-barcode">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_ATUALIZAR_CODBARRAS_TITULO'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<div class="note note-warning">
														<h4 class="block">
															<?php echo lang('PRODUTO_ATUALIZAR_CODBARRAS_OBS1'); ?>
														</h4>
														<p><?php echo lang('PRODUTO_ATUALIZAR_CODBARRAS_OBS2'); ?></p>
													</div>
												</div>
											</div>
										</div>
										<input name="grade" type="hidden" value="1" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<button type="button"
																	class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTO_ATUALIZAR_CODBARRAS_SIM'); ?></button>
																<button type="button" id="voltar"
																	class="btn default"><?php echo lang('PRODUTO_ATUALIZAR_CODBARRAS_NAO'); ?></button>
															</div>
														</div>
													</div>
													<div class="col-md-6">
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm("processarAtualizarCodigosDeBarras"); ?>
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
	case "adicionar": ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('PRODUTO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_ADICIONAR'); ?></small></h1>
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
										<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_ADICIONAR'); ?>
									</div>
									<a href="index.php?do=produto&acao=importarprodutos"
										class="btn btn-sm yellow-gold pull-right">
										<i class="fa fa-file-excel-o">&nbsp;&nbsp;</i>
										<?php echo lang('IMPORTAR_PRODUTOS'); ?>
									</a>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-12" style="text-align:center">
														<label class="control-label float-right"><strong
																class="bold italic font-red"
																style="font-size: 15px"><?php echo lang('OBS_*'); ?></strong></label>
													</div>
													<div class="col-md-12">
														<h4 class="form-section">
															<strong><?php echo lang('PRODUTO_INFO_IDENTIFICACAO'); ?></strong>
														</h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NOME'); ?><strong
																		class="bold italic font-red"
																		style="font-size: 20px"><?php echo lang('*'); ?></strong></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="nome">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CODIGO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="codigo"
																		maxlength="45">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CODIGO_DE_BARRAS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="codigobarras" maxlength="15">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check"
																				name="codigobarrasautomatico"
																				id="codigobarrasautomatico" value="1">
																			<label for="codigobarrasautomatico">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('CODIGO_DE_BARRAS_AUTO'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('OBSERVACAO'); ?></label>
																<div class="col-md-9">
																	<textarea class="form-control caps" rows="3"
																		name="observacao" maxlength="100"></textarea>
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
																	class="control-label col-md-3"><?php echo lang('ESTOQUE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="estoque">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTOQUE_MINIMO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control" name="estoquemin">
																	<span
																		class="help-block"><?php echo lang('QTD_MIN_ESTOQUE'); ?></span>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_CUSTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control moeda"
																		name="valor_custo">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_VENDA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control moeda"
																		name="valor_venda">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_AVISTA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control moeda"
																		name="valor_avista">
																	<span
																		class="help-block"><?php echo lang('VALOR_AVISTA_OBS'); ?></span>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check"
																				name="ecommerce" id="ecommerce" value="1">
																			<label for="ecommerce">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('ECOMMERCE'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section">
															<strong><?php echo lang('PRODUTO_INFO_FISCAIS'); ?></strong>
														</h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NCM'); ?><strong
																		class="bold italic font-red"
																		style="font-size: 20px"><?php echo lang('*'); ?></strong></label>
																<div class="col-md-9">
																	<input type="text" class="form-control codigo" name="ncm"
																		maxlength="8">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CFOP_SAIDA'); ?><strong
																		class="bold italic font-red"
																		style="font-size: 20px"><?php echo lang('*'); ?></strong></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro" name="cfop"
																		maxlength="4">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CFOP_ENTRADA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro"
																		name="cfop_entrada" maxlength="4">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEST'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control codigo" name="cest"
																		maxlength="7">
																	<span
																		class="help-block"><?php echo lang('OBS_CEST'); ?></span>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CSOSN_CST'); ?><strong
																		class="bold italic font-red"
																		style="font-size: 20px"><?php echo lang('*'); ?></strong></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro"
																		name="icms_cst" maxlength="4">
																	<span
																		class="help-block"><?php echo lang('OBS_CSOSN_CST'); ?></span>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ICMS_ORIGEM'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro"
																		name="icms_origem" maxlength="1">
																</div>
															</div>
														</div>
														<?php
														$retorno_row = $empresa->getEmpresas();
														if ($retorno_row):
															foreach ($retorno_row as $row):
																?>
																<div class="row">
																	<div class="form-group">
																		<label
																			class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS'); ?></label>
																		<div class="col-md-9">
																			<input type="text" class="form-control decimal"
																				name="icms_percentual"
																				value="<?php echo $row->icms_normal_aliquota; ?>">
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label
																			class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS_ST'); ?></label>
																		<div class="col-md-9">
																			<input type="text" class="form-control decimal"
																				name="icms_percentual_st"
																				value="<?php echo $row->icms_st_aliquota; ?>">
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
																			class="control-label col-md-3"><?php echo lang('ALIQUOTA_MVA'); ?></label>
																		<div class="col-md-9">
																			<input type="text" class="form-control decimal"
																				name="mva_percentual" value="<?php echo $row->mva; ?>">
																		</div>
																	</div>
																</div>
																<?php
															endforeach;
															unset($srow);
														endif;
														?>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CST_PIS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro"
																		name="pis_cst" maxlength="4">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_PIS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal"
																		name="pis_aliquota">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CST_COFINS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro"
																		name="cofins_cst" maxlength="4">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_COFINS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal"
																		name="cofins_aliquota">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CST_IPI_CODIGO'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control"
																		name="ipi_saida_codigo"
																		data-placeholder="<?php echo lang('SELECIONE_IPI_CODIGO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getIPISaida();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->codigo; ?>">
																					<?php echo $srow->codigo . ' - ' . $srow->descricao; ?>
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
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_CST_IPI'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal"
																		name="ipi_cst">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('UNIDADE'); ?><strong
																		class="bold italic font-red"
																		style="font-size: 20px"><?php echo lang('*'); ?></strong></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="unidade"
																		data-placeholder="<?php echo lang('SELECIONE_UNIDADE_MEDIDA'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getUnidadeMedida();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option
																					value="<?php echo $srow->unidade . '#' . $srow->descricao; ?>">
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
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('UNIDADE_TRIBUTAVEL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="unidade_tributavel">
																	<span
																		class="help-block"><?php echo lang('UNIDADE_TRIBUTAVEL_TITULO'); ?></span>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12">
													<br><br>
												</div>
												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section">
															<strong><?php echo lang('PRODUTO_INFO_GERAIS'); ?></strong>
														</h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('GRUPO'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="id_grupo"
																		data-placeholder="<?php echo lang('SELECIONE_GRUPO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getGrupos();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>">
																					<?php echo $srow->grupo; ?>
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
																	class="control-label col-md-3"><?php echo lang('CATEGORIA'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="id_categoria"
																		data-placeholder="<?php echo lang('SELECIONE_CATEGORIA'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getCategorias();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>">
																					<?php echo $srow->categoria; ?>
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
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check"
																				name="monofasico" id="monofasico" value="1">
																			<label for="monofasico">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PRODUTO_MONOFASICO'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check"
																				name="valida_estoque" id="valida_estoque"
																				value="1">
																			<label for="valida_estoque">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('ESTOQUE_VALIDA'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check" name="kit"
																				id="kit" value="1">
																			<label for="kit">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PRODUTO_KIT_E'); ?></label>
																			<span
																				class="help-block"><?php echo lang('OBS_PRODUTO_KIT_E'); ?></span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input type="checkbox" class="md-check"
																				name="produto_balanca" id="produto_balanca"
																				value="1">
																			<label for="produto_balanca">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PRODUTO_BALANCA'); ?></label>
																		</div>
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
																	class="control-label col-md-3"><?php echo lang('FABRICANTE'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="id_fabricante"
																		data-placeholder="<?php echo lang('SELECIONE_FABRICANTE'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getFabricantes();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>">
																					<?php echo $srow->exibir_romaneio ? $srow->fabricante . " (EXIBE EM ROMANEIO)" : $srow->fabricante; ?>
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
																	class="control-label col-md-3"><?php echo lang('PESO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="peso">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('LINK'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control" name="link">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PRAZO_TROCA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro"
																		name="prazo_troca" maxlength="4" placeholder="dias">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DETALHES'); ?></label>
																<div class="col-md-9">
																	<textarea class="form-control caps" rows="4"
																		name="detalhamento"></textarea>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12">
													<br>
												</div>
												<div class="col-md-12">
													<div class="col-md-12">
														<h4 class="form-section">
															<strong><?php echo lang('PRODUTO_INFO_PETROLEO'); ?></strong>
														</h4>
													</div>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('COD_ANP'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="cod_anp">
																	<span
																		class="help-block"><?php echo lang('OBS_ANP'); ?></span>
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
																	class="control-label col-md-3"><?php echo lang('VALOR_PARTIDA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control moeda"
																		name="valor_partida">
																	<span
																		class="help-block"><?php echo lang('OBS_ANP'); ?></span>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-4"><strong
																		class="bold italic font-red"
																		style="font-size: 15px"><?php echo lang('OBS_*'); ?></strong></label>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
											</div>
										</div>
										<input name="grade" type="hidden" value="1" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<button type='button' id='novo'
																	class='btn green'><?php echo lang('SALVAR_ADICIONAR'); ?></button>
																<button type="button"
																	class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
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
									<?php echo $core->doForm("processarProduto"); ?>
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
	case "visualizar": ?>
		<?php if (!$usuario->is_Administrativo()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif; ?>
		<?php $row = Core::getRowById("produto", Filter::$id); ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('PRODUTO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_VISUALIZAR'); ?></small>
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
										<i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_VISUALIZAR'); ?>
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
																	class="control-label col-md-3"><?php echo lang('NOME'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="nome" value="<?php echo $row->nome; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CODIGO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="codigo" maxlength="10"
																		value="<?php echo $row->codigo; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NCM'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control codigo"
																		name="ncm" maxlength="15"
																		value="<?php echo $row->ncm; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CFOP'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control inteiro"
																		name="cfop" maxlength="4"
																		value="<?php echo $row->cfop; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEST'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control codigo"
																		name="cest" maxlength="15"
																		value="<?php echo $row->cest; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CSOSN_CST'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control inteiro"
																		name="icms_cst" maxlength="4"
																		value="<?php echo $row->icms_cst; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CST_PIS'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control inteiro"
																		name="pis_cst" maxlength="4"
																		value="<?php echo $row->pis_cst; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CST_COFINS'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control inteiro"
																		name="cofins_cst" maxlength="4"
																		value="<?php echo $row->cofins_cst; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CST_IPI'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control inteiro"
																		name="ipi_cst" maxlength="4"
																		value="<?php echo $row->ipi_cst; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('UNIDADE'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="unidade" value="<?php echo $row->unidade; ?>">
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
																	class="control-label col-md-3"><?php echo lang('CODIGO_DE_BARRAS'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control caps"
																		name="codigobarras" maxlength="15"
																		value="<?php echo $row->codigobarras; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_CUSTO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control"
																		name="valor_custo"
																		value="<?php echo moeda($row->valor_custo); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTOQUE'); ?></label>
																<div class="col-md-9">
																	<input readonly readonly type="text" class="form-control"
																		value="<?php echo decimalp($row->estoque); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('GRUPO'); ?></label>
																<div class="col-md-9">
																	<input readonly readonly type="text" class="form-control"
																		value="<?php echo getValue('grupo', 'grupo', 'id=' . $row->id_grupo); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CATEGORIA'); ?></label>
																<div class="col-md-9">
																	<input readonly readonly type="text" class="form-control"
																		value="<?php echo getValue('categoria', 'categoria', 'id=' . $row->id_categoria); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('FABRICANTE'); ?></label>
																<div class="col-md-9">
																	<input readonly readonly type="text" class="form-control"
																		value="<?php echo getValue('fabricante', 'fabricante', 'id=' . $row->id_fabricante); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('LINK'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control" name="link"
																		value="<?php echo $row->link; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DETALHES'); ?></label>
																<div class="col-md-9">
																	<textarea readonly class="form-control caps"
																		name="detalhamento"><?php echo $row->detalhamento; ?></textarea>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input readonly type="checkbox" class="md-check"
																				name="grade" id="grade" value="1" <?php if ($row->grade)
																					echo 'checked'; ?>>
																			<label for="grade">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('GRADE_VENDAS'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input readonly type="checkbox" class="md-check"
																				name="valida_estoque" id="valida_estoque"
																				value="1" <?php if ($row->valida_estoque)
																					echo 'checked'; ?>>
																			<label for="valida_estoque">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('ESTOQUE_VALIDA'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"></label>
																<div class="col-md-9">
																	<div class="md-checkbox-list">
																		<div class="md-checkbox">
																			<input readonly type="checkbox" class="md-check"
																				name="kit" id="kit" value="1" <?php if ($row->kit)
																					echo 'checked'; ?>>
																			<label for="kit">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PRODUTO_KIT_E'); ?></label>
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
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
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
					<!-- INICO DO ROW ATRIBUTOS PRODUTO -->
					<div class="row">
						<div class="col-md-12">
							<!-- INICIO TABELA -->
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-puzzle-piece font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTO_ATRIBUTO'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-hover table-bordered table-striped table-advance">
										<thead>
											<tr>
												<th><?php echo lang('ATRIBUTO'); ?></th>
												<th><?php echo lang('DESCRICAO'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $produto->getProdutoAtributo(Filter::$id);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo $exrow->atributo; ?></td>
														<td><?php echo $exrow->descricao; ?></td>
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
						</div>
					</div>
					<!-- FINAL DO ROW ATRIBUTOS PRODUTO -->
					<!-- INICO DO ROW FORNECEDOR -->
					<div class="row">
						<div class="col-md-12">
							<!-- INICIO TABELA -->
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-truck font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('FORNECEDOR_LISTAR'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-advance">
										<thead>
											<tr>
												<th><?php echo lang('FORNECEDOR'); ?></th>
												<th><?php echo lang('TELEFONE'); ?></th>
												<th><?php echo lang('CODIGO'); ?></th>
												<th><?php echo lang('QUANT'); ?></th>
												<th><?php echo lang('UNIDADE'); ?></th>
												<th><?php echo lang('PRAZO'); ?></th>
												<th><?php echo lang('NOTA'); ?></th>
												<th><?php echo lang('COMPRADOS'); ?></th>
												<th><?php echo lang('DATA'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = 0;
											$retorno_row = $produto->getFornecedoresProduto(Filter::$id);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$quantidade = $produto->getQuantNota($exrow->id_nota, $exrow->id);
													?>
													<tr>
														<td><?php echo $exrow->fornecedor; ?></td>
														<td><?php echo $exrow->telefone . " " . $exrow->celular; ?></td>
														<td><?php echo $exrow->codigonota; ?></td>
														<td><?php echo nodecimal($exrow->quantidade_compra) . 'x'; ?></td>
														<td><?php echo $exrow->unidade_compra; ?></td>
														<td><?php echo $exrow->prazo; ?></td>
														<td><a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota; ?>"
																target="_blank"><?php echo $exrow->numero_nota; ?></a></td>
														<td><?php echo $quantidade; ?></td>
														<td><?php echo exibedata($exrow->data_emissao); ?></td>
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
						</div>
					</div>
					<!-- FINAL DO ROW FORNECEDOR -->
					<?php if ($row->kit): ?>
						<!-- INICO DO ROW KIT PRODUTO -->
						<div class="row">
							<div class="col-md-12">
								<!-- INICIO TABELA -->
								<div class="portlet light">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-barcode font-<?php echo $core->primeira_cor; ?>"></i>
											<span
												class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTO_KIT'); ?></span>
										</div>
									</div>
									<div class="portlet-body">
										<table class="table table-hover table-bordered table-striped table-advance">
											<thead>
												<tr>
													<th><?php echo lang('PRODUTO'); ?></th>
													<th><?php echo lang('QUANTIDADE'); ?></th>
													<th><?php echo lang('VALOR_CUSTO'); ?></th>
													<th><?php echo lang('ESTOQUE'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$total = 0;
												$retorno_row = $produto->getProdutokit(Filter::$id);
												if ($retorno_row):
													foreach ($retorno_row as $exrow):
														$total += $exrow->valor_custo;
														?>
														<tr>
															<td><?php echo $exrow->nome; ?></td>
															<td><?php echo decimalp($exrow->quantidade); ?></td>
															<td><?php echo moeda($exrow->valor_custo); ?></td>
															<td><?php echo decimalp($exrow->estoque); ?></td>
														</tr>
														<?php
													endforeach;
													unset($exrow);
													?>
													<tr>
														<td><strong><?php echo lang('VALOR_TOTAL'); ?></strong></td>
														<td></td>
														<td><strong><?php echo moeda($total); ?></strong></td>
														<td></td>
													</tr>
													<?php
												endif;
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<!-- FINAL DO ROW KIT PRODUTO -->
					<?php endif; ?>
					<?php if ($row->imagem): ?>
						<!-- INICIO DO ROW IMAGEM PRODUTO -->
						<div class="row">
							<div class="col-md-12">
								<!-- BEGIN PAGE CONTENT INNER -->
								<div class="portlet light">
									<form action="" autocomplete="off" class="form-inline" method="post" id="admin_form"
										name="admin_form">
										<div class="portlet-body">
											<div class="table-scrollable table-scrollable-borderless">
												<table class="table table-bordered table-hover table-light">
													<thead>
														<tr role="row" class="heading">
															<th><?php echo lang('IMAGEM'); ?></th>
															<th width="110px"><?php echo lang('ACOES'); ?></th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>
																<a href="<?php echo UPLOADSHTML . $row->imagem; ?>"
																	class="fancybox-button" data-rel="fancybox-button">
																	<img height="80px"
																		src="<?php echo UPLOADSHTML . $row->imagem; ?>" alt=""></a>
															</td>
															<td>
																<a href="javascript:void(0);" class="btn btn-sm grey-cascade"
																	onclick="javascript:void window.open('<?php echo UPLOADSHTML . $row->imagem; ?>','<?php echo lang('VISUALIZAR'); ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
																		class="fa fa-search"></i></a>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</form>
								</div>
								<!-- END PAGE CONTENT INNER -->
							</div>
						</div>
						<!-- FINAL DO ROW IMAGEM PRODUTO -->
					<?php endif; ?>
				</div>
			</div>
			<!-- FINAL DOS MODULOS DA PAGINA -->
		</div>
		<!-- FINAL CONTEUDO DA PAGINA -->
		<?php break; ?>
	<?php
	case "listar":
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('PRODUTO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_LISTAR'); ?></small></h1>
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
										<i class="fa fa-barcode font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTO_LISTAR'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="javascript:void(0);" class="btn btn-sm blue exportar_remessa_balanca"
											id="exportar_remessa_balanca"
											title="Baixar remessa de produtos para a balança. Obs.: PARA IMPORTAR OS ITENS PARA A BALANÇA, O NOME DO ARQUIVO DEVERÁ SER SEMPRE ITENSMGV.txt .">
											<i class="fa fa-file-text">&nbsp;&nbsp;</i>
											<?php echo lang('EXPORTAR_REMESSA_PRODUTO'); ?>
										</a>
										<a href="javascript:void(0);" class="btn btn-sm red exportar_produtos"
											id="exportar_produtos" title="Baixar planilha com os produtos selecionados">
											<i class="fa fa-file-excel-o">&nbsp;&nbsp;</i>
											<?php echo lang('EXPORTAR_PRODUTO'); ?>
										</a>
										<a href="index.php?do=produto&acao=importarprodutos" class="btn btn-sm yellow-gold "><i
												class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('IMPORTAR_PRODUTOS'); ?></a>
										<a href="javascript:void(0);" class="btn btn-sm green-jungle adicionargrade"
											title="<?php echo lang('ADICIONAR_GRADE_VENDAS'); ?>"><i
												class="fa fa-th">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR_GRADE_VENDAS'); ?></a>
										<a href="javascript:void(0);" class="btn btn-sm grey-gallery limpargrade"
											title="<?php echo lang('LIMPAR_GRADE_VENDAS'); ?>"><i
												class="fa fa-th">&nbsp;&nbsp;</i><?php echo lang('LIMPAR_GRADE_VENDAS'); ?></a>
										<a href="index.php?do=produto&acao=adicionar"
											class="btn btn-sm <?php echo $core->primeira_cor; ?>"><i
												class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR'); ?></a>
									</div>
								</div>
								<div class="portlet-body">
									<form class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_grupo" id="id_grupo">
												<option value="">TODOS GRUPOS</option>
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
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_categoria"
												id="id_categoria">
												<option value="">TODAS CATEGORIAS</option>
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
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_fabricante"
												id="id_fabricante">
												<option value="">TODOS FABRICANTES</option>
												<?php
												$retorno_row = $produto->getFabricantes();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->fabricante; ?>
														</option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
									</form>
								</div>
								<style>
									.red-row {
										background-color: red !important;
										color: white;
									}
								</style>
								<div class="portlet-body">
									<form class="form-inline" action="" method="post" name="admin_form" id="admin_form">
										<table class="table table-bordered table-condensed table-advance"
											id="table_listar_produtos">
											<thead>
												<tr>
													<th class="table-checkbox">
														<input type="checkbox" id="checkTodos" name="checkTodos"
															class="group-checkable" data-set=".checkboxes" />
													</th>
													<th>#</th>
													<th><?php echo lang('PRODUTO'); ?></th>
													<th><?php echo lang('NCM'); ?></th>
													<th><?php echo lang('CEST'); ?></th>
													<th><?php echo lang('CODIGO'); ?></th>
													<th><?php echo lang('CODIGO_INTERNO'); ?></th>
													<th><?php echo lang('CODIGO_DE_BARRAS'); ?></th>
													<th><?php echo lang('GRUPO'); ?></th>
													<th><?php echo lang('CATEGORIA'); ?></th>
													<th><?php echo lang('ATRIBUTOS'); ?></th>
													<th><?php echo lang('ESTOQUE'); ?></th>
													<th><?php echo lang('CUSTO'); ?></th>
													<th><?php echo lang('PRODUTO_BALANCA'); ?></th>
													<th width="130px"><?php echo lang('ACOES'); ?></th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
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
	case "grade":
		$id_categoria = get('id_categoria');
		$id_grupo = get('id_grupo');
		$id_fabricante = get('id_fabricante');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#imprimir_grade').click(function () {
					var id_categoria = $("#id_categoria").val();
					var id_grupo = $("#id_grupo").val();
					var id_fabricante = $("#id_fabricante").val();
					window.open('pdf_grade.php?id_categoria=' + id_categoria + '&id_fabricante=' + id_fabricante + '&id_grupo=' + id_grupo, 'Imprimir Grade de Vendas', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});
			});
			// ]]>
		</script>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#id_categoria').change(function () {
					var id_categoria = $("#id_categoria").val();
					var id_grupo = $("#id_grupo").val();
					var id_fabricante = $("#id_fabricante").val();
					window.location.href = 'index.php?do=produto&acao=grade&id_categoria=' + id_categoria + '&id_grupo=' + id_grupo + '&id_fabricante=' + id_fabricante;
				});
			});
			// ]]>
		</script>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#id_grupo').change(function () {
					var id_categoria = $("#id_categoria").val();
					var id_grupo = $("#id_grupo").val();
					var id_fabricante = $("#id_fabricante").val();
					window.location.href = 'index.php?do=produto&acao=grade&id_categoria=' + id_categoria + '&id_grupo=' + id_grupo + '&id_fabricante=' + id_fabricante;
				});
			});
			// ]]>
		</script>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#id_fabricante').change(function () {
					var id_categoria = $("#id_categoria").val();
					var id_grupo = $("#id_grupo").val();
					var id_fabricante = $("#id_fabricante").val();
					window.location.href = 'index.php?do=produto&acao=grade&id_categoria=' + id_categoria + '&id_grupo=' + id_grupo + '&id_fabricante=' + id_fabricante;
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
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('GRADE_VENDAS'); ?></small></h1>
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
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('GRADE_VENDAS'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="javascript:void(0);" id="imprimir_grade"
											class="btn btn-sm <?php echo $core->primeira_cor; ?>"><i
												class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR'); ?></a>
									</div>
								</div>
								<div class="portlet-body">
									<form class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_grupo" id="id_grupo">
												<option value="">TODOS GRUPOS</option>
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
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_categoria"
												id="id_categoria">
												<option value="">TODAS CATEGORIAS</option>
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
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_fabricante"
												id="id_fabricante">
												<option value="">TODOS FABRICANTES</option>
												<?php
												$retorno_row = $produto->getFabricantes();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->fabricante; ?>
														</option>
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
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('CODIGO_DE_BARRAS'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('UNIDADE'); ?></th>
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
											$retorno_row = $produto->getProdutosGrade($id_grupo, $id_categoria, $id_fabricante);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$totalcusto += $invcusto = $exrow->estoque * $exrow->valor_custo;
													?>
													<tr>
														<td><?php echo $exrow->codigobarras; ?></td>
														<td><?php echo $exrow->nome; ?></td>
														<td><?php echo $exrow->unidade; ?></td>
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
													<td colspan="7"><strong><?php echo lang('INVENTARIO_CUSTO'); ?></strong></td>
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
	<?php
	case "pendentes": ?>

		<div id="todos-produto" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-plus">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_ADICIONAR'); ?>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="todos_form" id="todos_form" class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-md-12"><?php echo lang('TODOS_PRODUTO_ADICIONAR'); ?></label>
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
			<?php echo $core->doForm("todosProdutoNota", "todos_form"); ?>
		</div>
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
										<input type="text" class="form-control inteiro" name="cfop_entrada" id="cfop_entrada"
											maxlength="4">
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
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->categoria; ?></option>
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
		<div id="combinar-produto" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-link">&nbsp;&nbsp;</i><?php echo lang('COMBINAR_PRODUTO'); ?>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="combinar_form" id="combinar_form" class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('PRODUTO'); ?>!</label>
										<div class="col-md-8">
											<select class="form-control input-large" name="id_produto" id="id_produto_select2me"
												data-placeholder="<?php echo lang('SELECIONE_PRODUTO'); ?>">
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
										<input type="text" class="form-control inteiro" name="cfop_entrada" id="combinar_cfop_entrada"
											maxlength="4">
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
						<h1><?php echo lang('PRODUTO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_PENDENTES'); ?></small></h1>
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
										<i class="fa fa-pause font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTO_PENDENTES'); ?></span>
									</div>
									<div class="actions btn-set">
										<!--<a href="#todos-produto" class="btn btn-sm <?php echo $core->primeira_cor; ?>" data-toggle="modal"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('TODOS_PRODUTO'); ?></a>-->
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('NCM'); ?></th>
												<th><?php echo lang('UNIDADE'); ?></th>
												<th><?php echo lang('VL_UNITARIO'); ?></th>
												<th><?php echo lang('NOTA'); ?></th>
												<th><?php echo lang('DATA'); ?></th>
												<th width="80px"><?php echo lang('ACOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $produto->getProdutosPendentes();
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo $exrow->nome_fornecedor; ?></td>
														<td><?php echo $exrow->ncm; ?></td>
														<td><?php echo $exrow->unidade; ?></td>
														<td><?php echo moeda($exrow->valor_unitario); ?></td>
														<td><a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota; ?>"
																target="_blank"><?php echo $exrow->numero_nota; ?></a></td>
														<td><?php echo exibedata($exrow->data_emissao); ?></td>
														<td>
															<a href="javascript:void(0);" class="btn btn-sm blue-steel novoproduto"
																id="<?php echo $exrow->id_produto_fornecedor; ?>"
																id_nf_itens="<?php echo $exrow->id; ?>"
																id_nota="<?php echo $exrow->id_nota; ?>"
																title="<?php echo lang('NOVO_PRODUTO'); ?>"
																cfop="<?php echo $exrow->cfop; ?>" ncm_nf="<?php echo $exrow->ncm; ?>"
																cest_nf="<?php echo $exrow->cest; ?>"
																csosn_cst="<?php echo $exrow->icms_cst; ?>"
																cod_anp="<?php echo $exrow->cod_anp; ?>"
																valor_partida="<?php echo $exrow->valor_partida; ?>"
																codigobarras="<?php echo $exrow->codigobarras; ?>">
																<i class="fa fa-plus"></i>
															</a>
															<a href="javascript:void(0);" class="btn btn-sm blue-hoki combinarproduto"
																id="<?php echo $exrow->id_produto_fornecedor; ?>"
																id_nota="<?php echo $exrow->id_nota; ?>"
																id_nf_itens="<?php echo $exrow->id; ?>"
																title="<?php echo lang('COMBINAR_PRODUTO'); ?>">
																<i class="fa fa-link"></i>
															</a>
														</td>
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
	case "inventario":
		$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('Y');
		$id_empresa = (get('id_empresa')) ? get('id_empresa') : session('idempresa');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.imprimirinventario').click(function () {
					var id_empresa = $("#id_empresa").val();
					var mes_ano = $("#mes_ano").val();
					window.open('pdf_inventario.php?id_empresa=' + id_empresa, '<?php echo lang('PRODUTO_INVENTARIO'); ?>', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
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
										<span
											class="font-<?php echo $core->segunda_cor; ?>"><?php echo ' (' . lang('PRODUTO_INVENTARIO_OBSERVACAO') . ')'; ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form class="form-inline">
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
	case "buscar": ?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#produto').focus();
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
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_BUSCAR'); ?></small></h1>
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
										<i class="fa fa-search"></i>
										<span><?php echo lang('PRODUTO_BUSCAR'); ?></span>
									</div>
								</div>
								<?php
								$array_cancelados = array();
								if (post('produto') or post('codigo')):
									$retorno_row = $produto->getBuscarProdutos();
									if ($retorno_row): ?>
										<div class="portlet-body">
											<table class="table table-bordered table-striped table-condensed table-advance">
												<thead>
													<tr>
														<th><?php echo lang('CODIGO'); ?></th>
														<th><?php echo lang('CODIGO_DE_BARRAS'); ?></th>
														<th><?php echo lang('PRODUTO'); ?></th>
														<th><?php echo lang('GRUPO'); ?></th>
														<th><?php echo lang('CATEGORIA'); ?></th>
														<th><?php echo lang('ESTOQUE'); ?></th>
														<th><?php echo lang('VALOR_CUSTO'); ?></th>
														<th><?php echo lang('STATUS'); ?></th>
														<th width="220px"><?php echo lang('ACOES'); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach ($retorno_row as $exrow):
														$status = '';
														if (!$exrow->inativo):
															$status = "<span class='label label-sm bg-green'>" . lang('ATIVO') . "</span>";
															?>
															<tr <?php echo ($exrow->inativo) ? "class='font-red'" : ""; ?>>
																<td><?php echo $exrow->codigo; ?></td>
																<td><?php echo $exrow->codigobarras; ?></td>
																<td><a
																		href="index.php?do=produto&acao=editar&id=<?php echo $exrow->id; ?>"><?php echo $exrow->nome; ?></a>
																</td>
																<td><?php echo $exrow->grupo; ?></td>
																<td><?php echo $exrow->categoria; ?></td>
																<td><?php echo decimalp($exrow->estoque); ?></td>
																<td><?php echo moeda($exrow->valor_custo); ?></td>
																<td><?php echo $status; ?></td>
																<td>
																	<?php if ($exrow->grade): ?>
																		<a href="javascript:void(0);" class="btn btn-sm green-jungle gradevendas"
																			grade="0" id="<?php echo $exrow->id; ?>"
																			title="<?php echo lang('GRADE_VENDAS') . ": " . $exrow->nome; ?>"><i
																				class="fa fa-th"></i></a>
																	<?php else: ?>
																		<a href="javascript:void(0);" class="btn btn-sm grey-gallery gradevendas"
																			grade="1" id="<?php echo $exrow->id; ?>"
																			title="<?php echo lang('GRADE_VENDAS') . ": " . $exrow->nome; ?>"><i
																				class="fa fa-th"></i></a>
																	<?php endif; ?>
																	<a href="javascript:void(0);" class="btn btn-sm yellow"
																		onclick="javascript:void window.open('imprimir_estoque.php?id_produto=<?php echo $exrow->id; ?>','<?php echo $exrow->nome; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																		title="<?php echo lang('ESTOQUE_HISTORICO'); ?>"><i
																			class="fa fa-header"></i></a>
																	<a href="index.php?do=produto&acao=editar&id=<?php echo $exrow->id; ?>"
																		class="btn btn-sm blue"
																		title="<?php echo lang('EDITAR') . ': ' . $exrow->nome; ?>"><i
																			class="fa fa-pencil"></i></a>
																	<a href="javascript:void(0);" class="btn btn-sm red apagar"
																		id="<?php echo $exrow->id; ?>" acao="apagarProduto"
																		title="<?php echo lang('APAGAR') . $exrow->nome; ?>"><i
																			class="fa fa-times"></i></a>
																</td>
															</tr>
														<?php else:
															$status = "<span class='label label-sm bg-red'>" . lang('CANCELADO') . "</span>";
															$array_linha = array(
																'id' => $exrow->id,
																'codigo' => $exrow->codigo,
																'codigobarras' => $exrow->codigobarras,
																'nome' => $exrow->nome,
																'grupo' => $exrow->grupo,
																'categoria' => $exrow->categoria,
																'estoque' => $exrow->estoque,
																'valor_custo' => $exrow->valor_custo,
																'status' => $status
															);
															$array_cancelados[] = $array_linha;
														endif;
													endforeach;
													unset($exrow); ?>
												</tbody>
											</table>
										</div>
									<?php else: ?>
										<div class="portlet-body">
											<center>
												<h4><?php echo lang('PRODUTO_BUSCAR_VAZIO'); ?></h4>
											</center>
										</div>
									<?php endif;
								endif ?>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="index.php?do=produto&acao=buscar" method="post" class="horizontal-form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label"><?php echo lang('PRODUTO'); ?></label>
															<input type="text" class="form-control caps buscar"
																placeholder="Buscar pelo produto" id="produto" name="produto">
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label"><?php echo lang('CODIGO'); ?></label>
															<input type="text" class="form-control caps buscar"
																placeholder="Buscar pelo codigo" name="codigo">
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
																<button type="button"
																	class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('BUSCAR'); ?></button>
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
								<?php
								if ($array_cancelados): ?>
									<div class="portlet-body">
										<table class="table table-bordered table-striped table-condensed table-advance">
											<thead>
												<tr>
													<th><?php echo lang('CODIGO'); ?></th>
													<th><?php echo lang('CODIGO_DE_BARRAS'); ?></th>
													<th><?php echo lang('PRODUTO'); ?></th>
													<th><?php echo lang('GRUPO'); ?></th>
													<th><?php echo lang('CATEGORIA'); ?></th>
													<th><?php echo lang('ESTOQUE'); ?></th>
													<th><?php echo lang('VALOR_CUSTO'); ?></th>
													<th><?php echo lang('STATUS'); ?></th>
													<th width="220px"><?php echo lang('ACOES'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												foreach ($array_cancelados as $ccrow):
													?>
													<tr class="danger">
														<td><?php echo ($ccrow['codigo']); ?></td>
														<td><?php echo ($ccrow['codigobarras']); ?></td>
														<td><a
																href="index.php?do=produto&acao=editar&id=<?php echo $ccrow['id']; ?>"><?php echo $ccrow['nome']; ?></a>
														</td>
														<td><?php echo $ccrow['grupo']; ?></td>
														<td><?php echo $ccrow['categoria']; ?></td>
														<td><?php echo decimalp($ccrow['estoque']); ?></td>
														<td><?php echo moeda($ccrow['valor_custo']); ?></td>
														<td><?php echo $ccrow['status']; ?></td>
														<td>
															<a href="index.php?do=produto&acao=editar&id=<?php echo $ccrow['id']; ?>"
																class="btn btn-sm grey-cascade"
																title="<?php echo lang('VISUALIZAR') . ': ' . $ccrow['nome']; ?>"><i
																	class="fa fa-search"></i></a>

															<a href="javascript:void(0);" class="btn btn-sm green reativar_produto" id="<?php echo $ccrow['id']; ?>" 
																acao="reativar_produto" title="Você deseja reativar este produto? <?php echo $ccrow['nome']; ?>"><i class="fa fa-plus-square"></i></a>
															
														</td>
													</tr>
												<?php endforeach;
												unset($ccrow); ?>
											</tbody>
										</table>
									</div>
								<?php endif; ?>
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
	case "trocarproduto": ?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#id_venda').focus();
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
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_TROCA'); ?></small></h1>
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
										<i class="fa fa-exchange"></i>
										<span><?php echo lang('PRODUTO_TROCA'); ?></span>
									</div>
								</div>
								<?php
								if (post('id_venda') or post('numero_nota') /*or post('cpf_cliente')*/):
									$retorno_row = $produto->getBuscarVendaProdutoTroca();
									if ($retorno_row):
										?>
										<!--<div class="portlet-body">-->

										<div class="portlet-body form">
											<!-- INICIO FORM-->
											<form action="index.php?do=produto&acao=produtotrocavenda" method="post"
												class="horizontal-form">
												<div class="form-body">
													<div class="row">
														<div class="col-md-12">
															<table
																class="table table-bordered table-striped table-condensed table-advance">
																<thead>
																	<tr>
																		<th><?php echo lang('PRODUTO'); ?></th>
																		<th><?php echo lang('CODIGO'); ?></th>
																		<th><?php echo lang('ESTOQUE'); ?></th>
																		<th><?php echo lang('COD_VENDA'); ?></th>
																		<th><?php echo lang('CLIENTE'); ?></th>
																		<th><?php echo lang('VALOR'); ?></th>
																		<th><?php echo lang('QUANTIDADE'); ?></th>
																		<th><?php echo lang('QUANTIDADE_TROCADA'); ?></th>
																		<th><?php echo lang('VALOR_DESCONTO'); ?></th>
																		<th><?php echo lang('VALOR_TOTAL'); ?></th>
																		<th><?php echo lang('QTDE_TROCA'); ?></th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	$qtd = 0;
																	foreach ($retorno_row as $exrow):
																		$qtd = (float) $exrow->quantidade;
																		?>
																		<tr>
																			<td><?php echo $exrow->nome_produto; ?></td>
																			<td><?php echo $exrow->codigo_produto; ?></td>
																			<td><?php echo $exrow->estoque; ?></td>
																			<td><?php echo $exrow->id_venda; ?></td>
																			<td><?php echo $exrow->nome_cliente; ?></td>
																			<td><?php echo moeda($exrow->valor); ?></td>
																			<td><?php echo $exrow->quantidade; ?></td>
																			<td><?php echo $exrow->quantidade_trocada; ?></td>
																			<td><?php echo moeda($exrow->valor_desconto); ?></td>
																			<td><?php echo moeda($exrow->valor_total - $exrow->valor_desconto); ?>
																			</td>
																			<td>
																				<?php if (fmod($qtd, 1) === 0.0): ?>
																					<select class="form-control input-xsmall"
																						name="<?php echo "quantidade_produto[$exrow->cadastro_venda]"; ?>"
																						<?php echo ($exrow->quantidade - $exrow->quantidade_trocada == 0) ? "disabled" : ""; ?>>
																						<option selected="selected" value="<?php echo '0'; ?>">0
																						</option>
																						<?php for ($i = 1; $i <= ($exrow->quantidade - $exrow->quantidade_trocada); $i++): ?>
																							<option value="<?php echo $i; ?>"><?php echo $i; ?>
																							</option>
																						<?php endfor; ?>
																					</select>
																				<?php else: ?>
																					<input type="text"
																						name="<?php echo "quantidade_produto[$exrow->cadastro_venda]"; ?>"
																						class="form-control decimalp valor_quant_prod_troca"
																						style="width: 100px;" value="0" <?php echo ($exrow->quantidade - $exrow->quantidade_trocada == 0) ? "disabled" : ""; ?> />
																					<input type="hidden" class="vlr_quant_menos_quant_trocada"
																						value="<?php echo $exrow->quantidade - $exrow->quantidade_trocada ?>">
																				<?php endif; ?>
																			</td>
																		</tr>
																	<?php endforeach; ?>
																	<input name="id_venda" type="hidden"
																		value="<?php echo $exrow->id_venda; ?>" />
																	<?php unset($exrow); ?>
																</tbody>
															</table>
															<div class="portlet-body">
																<div class="row">
																	<div class="col-md-12" align="center">
																		<button type="button" class="btn btn-submit yellow"><i
																				class="fa fa-exchange"></i>&nbsp;&nbsp;<?php echo lang('PRODUTO_TROCAR_SELECIONADO'); ?></button>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</form>
										</div>
									<?php else: ?>
										<div class="portlet-body">
											<center>
												<h4><?php echo lang('PRODUTO_BUSCAR_VAZIO'); ?></h4>
											</center>
										</div>
									<?php endif;
								endif; ?>

								<div><br><br>
									<hr><br><br>
								</div>

								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="index.php?do=produto&acao=trocarproduto" method="post"
										class="horizontal-form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-4-->
													<div class="col-md-4">
														<div class="form-group">
															<label
																class="control-label"><?php echo lang('COD_VENDA'); ?></label>
															<input type="text" class="form-control caps buscar"
																placeholder="Buscar pelo código" id="id_venda" name="id_venda">
														</div>
													</div>
													<!--/col-md-4-->
													<!--col-md-4-->
													<div class="col-md-4">
														<div class="form-group">
															<label
																class="control-label"><?php echo lang('NUMERO_NOTA'); ?></label>
															<input type="text" class="form-control caps buscar"
																placeholder="Buscar pela nota" id="numero_nota"
																name="numero_nota">
														</div>
													</div>
													<!--/col-md-4-->
													<!--col-md-4-->
													<!--
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><?php echo lang('CPF_CNPJ'); ?></label>
													<input type="text" class="form-control caps cpf_cnpj buscar" placeholder="Buscar pelo CPF" id="cpf_cliente" name="cpf_cliente">
												</div>
											</div>
											-->
													<!--/col-md-4-->
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
																	class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('BUSCAR'); ?></button>
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
	case "trocarprodutoavulso":
		$msgError = get('msgError');
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('PRODUTO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_TROCA_AVULSO'); ?></small>
						</h1>
					</div>
					<!-- FINAL TITULO DA PAGINA -->
				</div>
			</div>
			<!-- FINAL CABECALHO DA PAGINA -->
			<!-- INICIO DOS MODULOS DA PAGINA -->
			<div class="page-content">
				<?php if ($msgError): ?>
					<div class="container">
						<div class="note note-warning">
							<h4 class="block"><?php echo lang('PRODUTO_TROCA_SELECIONE1'); ?></h4>
							<p><?php echo lang('PRODUTO_TROCA_SELECIONE2'); ?></p>
						</div>
					</div>
				<?php endif; ?>
				<div class="container">
					<!-- INICIO DO ROW FORMULARIO -->
					<div class="row">
						<div class="col-md-12">
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-exchange font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTO_TROCA_AVULSO'); ?></span>
									</div>
									<div class="actions btn-set">
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="index.php?do=produto&acao=produtotrocaavulso" method="post"
										class="horizontal-form">
										<div class="form-body">

											<div class="row">
												<div class="col-md-12">
													<div class="row">
														<div class="col-md-9">
															<h4 class="bold"><?php echo lang('PRODUTO_TROCA_VALOR'); ?></h4>
															<i>
																<h1 class="bold italic font-blue-madison" id="valor2">
																	R$ 0,00
																</h1>
															</i>
														</div>
														<div class="col-md-3">
															<br><br>
															<!-- <div class="form-actions"> -->
															<button type="button" class="btn btn-submit yellow">
																<i
																	class="fa fa-exchange"></i>&nbsp;&nbsp;<?php echo lang('PRODUTO_TROCAR_SELECIONADO'); ?>
															</button>
															<!-- </div> -->
														</div>
													</div>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-4">
														<div class="row">
															<div class="form-group col-md-12">
																<label
																	class="control-label"><?php echo lang('TABELA_PRECO'); ?></label>
																<div class="col-md-12">
																	<select class="select2me form-control" id="id_tabela_venda"
																		name="id_tabela"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<?php
																		$retorno_row = $produto->getTabelaNivel($usuario->nivel);
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>">
																					<?php echo $srow->tabela; ?>
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
															<div class="form-group col-md-12">
																<label
																	class="control-label"><?php echo lang('CODIGO_DE_BARRAS'); ?></label>
																<div class="col-md-12">
																	<input type="text" class="form-control barcode_troca"
																		id="cod_barras" name="cod_barras" autofocus>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group col-md-12">
																<label
																	class="control-label"><?php echo lang('PRODUTO'); ?></label>
																<div class="col-md-12">
																	<div class="col-md-10">
																		<select class="form-control" id="id_produto_venda"
																			name="id_produto"
																			data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		</select>
																	</div>
																	<div class="col-md-2">
																		<a href="javascript:void(0);" class="btn yellow"
																			id="adicionar_produto_a_trocar"
																			title="<?php echo lang('PRODUTO_ADICIONAR'); ?>"><i
																				class="fa fa-plus fa-fw"></i></a>
																	</div>
																</div>
															</div>
														</div>
														<!--
												<div class="row">
													<div class="form-group col-md-12">
														<label class="control-label"><?php echo lang('PAGAMENTO_PRODUTO_TROCA'); ?></label>
														<div class="col-md-12">
															<div class="col-md-10">
																<select class="select2me form-control selectPagamentoTroca" id="tipopagamento" name="tipopagamento" data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>" >
																	<option value=""></option>
																	<?php
																	$retorno_row = $faturamento->getTipoPagamento();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																			?>
																				<option value="<?php echo $srow->id; ?>" id_categoria="<?php echo $srow->id_categoria; ?>" avista="<?php echo $srow->avista; ?>" <?php if ($srow->id_categoria == "1")
																							 echo 'selected="selected"'; ?>><?php echo $srow->tipo; ?></option>
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
												-->
													</div>
													<div class="col-md-8">
														<div class="row">
															<div class="col-md-12">
																<div class="table-scrollable table-scrollable-borderless">
																	<table class="table table-hover table-advance">
																		<thead>
																			<tr>
																				<th width="40%"><?php echo lang('PRODUTO'); ?>
																				</th>
																				<th width="10%"><?php echo lang('ESTOQUE'); ?>
																				</th>
																				<th width="10%">
																					<?php echo lang('QUANTIDADE'); ?>
																				</th>
																				<th width="15%"><?php echo lang('VALOR'); ?>
																				</th>
																				<th width="20%"><?php echo lang('TOTAL'); ?>
																				</th>
																				<th width="5%"></th>
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
	case "selecionarprodutotroca":
		$id_venda = get('venda');
		$id_cadastro_venda = get('cadastro_venda');
		$row_venda = Core::getRowById("vendas", $id_venda);
		$row_cadastro_venda = Core::getRowById("cadastro_vendas", $id_cadastro_venda);
		$row_produto = Core::getRowById("produto", $row_cadastro_venda->id_produto);
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#id_produto_troca').focus();
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
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_TROCA'); ?></small></h1>
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
										<i class="fa fa-exchange"></i>
										<span><?php echo lang('PRODUTO_TROCA'); ?></span>
									</div>
								</div>
								<?php
								if ((empty($id_venda)) || ($id_venda == 0) || (empty($id_cadastro_venda)) || ($id_cadastro_venda == 0)):
									?>
									<div class="portlet-body">
										<center>
											<h4><?php echo lang('PRODUTO_BUSCAR_VAZIO'); ?></h4>
										</center>
										<div class="row">
											<div class="col-md-12">
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-offset-3 col-md-9">
															<a href="index.php?do=produto&acao=trocarproduto" class="btn default"
																title="<?php echo lang('VOLTAR'); ?>"><?php echo lang('VOLTAR'); ?></a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php
								else:
									?>
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
																	<div class="col-md-11">
																		<h4 class="form-section">
																			<?php echo lang('PRODUTO_TROCA_VENDA'); ?>
																		</h4>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('DATA'); ?></label>
																	<div class="col-md-9">
																		<input type="text" readonly class="form-control"
																			name="data_venda"
																			value="<?php echo exibeData($row_venda->data_venda); ?>">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" readonly class="form-control"
																			name="produto_venda"
																			value="<?php echo $row_produto->nome; ?>">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR'); ?></label>
																	<div class="col-md-9">
																		<input type="text" readonly class="form-control"
																			id="valor_venda" name="valor_venda"
																			value="<?php echo moeda($row_cadastro_venda->valor - $row_cadastro_venda->valor_desconto); ?>">
																	</div>
																</div>
															</div>
															<input id="codigo_produto_original" name="codigo_produto_original"
																type="hidden" value="<?php echo $row_produto->codigo; ?>" />
														</div>
														<!--/col-md-6-->
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<div class="col-md-11">
																		<h4 class="form-section">
																			<?php echo lang('PRODUTO_TROCA_NOVO'); ?>
																		</h4>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('TABELA_PRECO'); ?></label>
																	<div class="col-md-9">
																		<select class="select2me form-control" id="id_tabela_venda"
																			name="id_tabela"
																			data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																			<?php
																			$retorno_row = $produto->getTabelaNivel($usuario->nivel);
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->id; ?>">
																						<?php echo $srow->tabela; ?>
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
																		class="control-label col-md-3"><?php echo lang('PRODUTO_TROCA_SELECIONE'); ?></label>
																	<div class="col-md-9">
																		<select class="form-control" id="id_produto_venda"
																			name="id_produto"
																			data-placeholder="<?php echo lang('PRODUTO_TROCA_SELECIONE'); ?>"></select>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VALOR'); ?></label>
																	<div class="col-md-9">
																		<input type="text" readonly class="form-control"
																			name="valor_troca" id="valor_troca">

																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>
												</div>
												<div class="row">
													<div class="col-md-12 valor_credito ocultar">
														<div class="alert alert-success" align="center">
															<h4>
																<strong><?php echo lang('PRODUTO_TROCA_RECEBER') . ': '; ?></strong><span
																	class="resultado_credito"></span>
															</h4>
														</div>
													</div>
													<div class="col-md-12 valor_debito ocultar">
														<div class="alert alert-danger" align="center">
															<h4>
																<strong><?php echo lang('PRODUTO_TROCA_PAGAR') . ': '; ?></strong><span
																	class="resultado_credito"></span>
															</h4>
														</div>
													</div>
													<div class="col-md-12 mesmo_produto ocultar">
														<div class="alert alert-info" align="center">
															<h4>
																<strong><?php echo lang('PRODUTO_TROCA_MESMO'); ?></strong>
															</h4>
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
																		class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTO_TROCA_CONFIRMAR'); ?></button>
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
											<input name="id_venda" type="hidden" value="<?php echo $id_venda; ?>" />
											<input name="id_cadastro_venda" type="hidden"
												value="<?php echo $id_cadastro_venda; ?>" />
										</form>
										<?php echo $core->doForm("processarTrocadeProduto"); ?>
										<!-- FINAL FORM-->
									</div>
									<?php
								endif;
								?>
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
	case "produtotrocavenda":
		$id_venda = post('id_venda');
		$row_venda = Core::getRowById("vendas", $id_venda);
		$qtde_produto = post('quantidade_produto');
		$valor_total_troca = 0;
		$array_produtos = array();

		$produtos_row = $produto->ObterListaProdutosTroca2($qtde_produto);
		if ($produtos_row) {
			foreach ($qtde_produto as $indice => $qtde) {
				if ($qtde) {
					$array_produtos[$indice] = converteMoeda($qtde);
				}
			}
		}

		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('PRODUTO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_TROCA'); ?></small></h1>
					</div>
					<!-- FINAL TITULO DA PAGINA -->
				</div>
			</div>
			<!-- FINAL CABECALHO DA PAGINA -->
			<!-- INICIO DOS MODULOS DA PAGINA -->
			<div class="page-content">
				<div class="container">
					<!-- INICIO DO ROW FORMULARIO -->
					<?php if ($row_venda->orcamento == 1): ?>
						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-danger" role="alert">
									<h4><?php echo lang('ATENCAO') ?></h4>
									<h5><?php echo lang('INFO_TROCA_PRODUTO_ORCAMENTO') ?></h5>
								</div>
							</div>
							<div class="col-md-12">
								<a href="index.php?do=produto&acao=trocarproduto"
									class="btn default grey"><?php echo lang('VOLTAR'); ?></a>
							</div>
						</div>
					<?php else: ?>
						<?php if ($produtos_row == 0): ?>
							<div class="row">
								<div class="col-md-12">
									<div class="note note-danger">
										<h4 class="block"><?php echo lang('PRODUTO_TROCA_SELECIONE1'); ?></h4>
										<p><?php echo lang('PRODUTO_TROCA_SELECIONE2'); ?></p>
									</div>
								</div>
								<div class="col-md-12">
									<a href="index.php?do=produto&acao=trocarproduto"
										class="btn default grey"><?php echo lang('VOLTAR'); ?></a>
								</div>
							</div>
						<?php else: ?>
							<?php if ($faturamento->verificaCaixa($usuario->uid) == 0): ?>
								<div class="row">
									<div class="col-md-12">
										<div class="note note-warning">
											<h4 class="block"><?php echo lang('CAIXA_VENDA_AVISO'); ?></h4>
											<p><?php echo lang('CAIXA_VENDA_AVISO_DESCRICAO'); ?></p>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<div class="row">
								<div class="col-md-12">
									<div class="portlet light">
										<div class="portlet-title">
											<div class="caption">
												<i class="fa fa-exchange"></i>
												<span><?php echo lang('PRODUTO_TROCA'); ?></span>
											</div>
											<div class="actions btn-set">
											</div>
										</div>
										<div class="portlet-body form">
											<!-- INICIO FORM-->
											<form action="" autocomplete="off" method="post" class="horizontal-form" name="admin_form"
												id="admin_form">
												<div class="form-body">
													<input name="valor" id="valor" type="hidden" />
													<div class="row">
														<div class="col-md-12">
															<div class="row">
																<div class="form-group">
																	<div class="col-md-12">
																		<h4 class="form-section">
																			<?php echo lang('PRODUTO_TROCA_VENDA'); ?>
																		</h4>
																	</div>
																	<div class="col-md-12">
																		<h5 class="">
																			<?php
																			if ($row_venda->id_cadastro) {
																				$cliente = getValue("nome", "cadastro", "id=" . $row_venda->id_cadastro);
																				$telefone = getValue("telefone", "cadastro", "id=" . $row_venda->id_cadastro);
																				$celular = getValue("celular", "cadastro", "id=" . $row_venda->id_cadastro);
																				$telefone_celular = $telefone ? $telefone : $celular;
																				$info = $cliente ? lang('CLIENTE') . ": " . $cliente . " - " . $telefone_celular : '';
																				echo "<strong>" . $info . "</strong>";
																			} else {
																				?>
																				<div class="modal fade" id="modal_cliente" tabindex="-1"
																					role="dialog" aria-labelledby="" aria-hidden="true">
																					<div class="">
																						<div class="modal-dialog" role="document">
																							<div class="modal-content">
																								<div class="modal-header">
																									<button type="button" class="close"
																										data-dismiss="modal"
																										aria-label="Close">
																										<span
																											aria-hidden="true">&times;</span>
																									</button>
																									<h4 class="modal-title" id="">
																										<strong><?php echo lang('CLIENTE_ADICIONAR') ?></strong>
																			</h5>
																		</div>
																		<div class="modal-body">
																			<div class="form-group">
																				<span
																					class="help-block pull-right"><?php echo lang('ATALHO_ENTER') ?></span>
																				<label
																					class="control-label"><?php echo lang('CLIENTE'); ?>:</label>
																				<div>
																					<input name="id_cadastro" id="id_cadastro"
																						type="hidden" />
																					<input name="cpf_cnpj" id="cpf_cnpj" type="hidden" />
																					<input type="text" autocomplete="off"
																						class="form-control caps listar_cliente pular"
																						name="cadastro" id="cadastro"
																						placeholder="<?php echo lang('BUSCAR'); ?>">
																				</div>
																				<div class="row selecionado ocultar">
																					<div class="form-group">
																						<div class="col-md-9">
																							<span
																								class="label label-success label-sm"><?php echo lang('SELECIONADO'); ?></span>
																						</div>
																					</div>
																				</div>
																				<div class="row devendo ocultar">
																					<div class="form-group">
																						<div class="col-md-9">
																							<span
																								class="label label-danger label-sm"><?php echo lang('CLIENTE_DEVENDO'); ?></span>
																						</div>
																					</div>
																				</div>
																			</div>
																			<div class="form-group">
																				<label
																					class="control-label"><?php echo lang('CPF_CNPJ'); ?>:</label>
																				<div>
																					<input type="text" autocomplete="off"
																						class="form-control cpf_cnpj pular"
																						id="cpf_cnpj_modal" name="cpf_cnpj">
																				</div>
																			</div>
																			<div class="form-group">
																				<label
																					class="control-label"><?php echo lang('CELULAR'); ?>:</label>
																				<div>
																					<input type="text" autocomplete="off"
																						class="form-control celular pular" id="celular"
																						name="celular">
																				</div>
																			</div>
																		</div>
																		<div class="modal-footer">
																			<button type="button"
																				class="btn salvar-cliente-troca <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
																			<button type="button" class="btn default sair_cliente_modal"
																				data-dismiss="modal"><?php echo lang('SAIR'); ?></button>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="pull-left info_cliente_troca_produto ocultar"
															style="margin-bottom: 20px; display: flex;">
															<p class="cliente_troca"
																style="font-weight: bold; font-size: 15px; margin-right: 25px"></p>
															<span id="cliente_novo" class="label label-danger ocultar">Novo</span>
															<span id="cliente_existente"
																class="label label-success ocultar">Existente</span>
															<span id="cliente_devendo"
																class="label label-danger devendo ocultar"><?php echo lang('CLIENTE_DEVENDO'); ?></span>
														</div>
														<button type="button" class="btn btn-sm red-thunderbird pull-right"
															title="Adicionar cliente para salvar o restante do valor a utilizar no crediario"
															data-toggle="modal" data-target="#modal_cliente">
															<i class="fa fa-plus-square"></i>
															<?php echo lang('CLIENTE_ADICIONAR') ?>
														</button>
														<br><br>
														<?php
																			}
																			?>
													</h5>
												</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<table class="table table-bordered table-striped table-condensed table-advance">
										<thead>
											<tr>
												<th><?php echo lang('DATA'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('QUANTIDADE'); ?></th>
												<th><?php echo lang('VALOR'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($produtos_row as $exrow):
												$quantidade_prod_trocar = converteMoeda($array_produtos[$exrow->id]);

												$valor = (($exrow->valor_total - $exrow->valor_desconto) / $exrow->quantidade) * $quantidade_prod_trocar;
												$valor_total_troca += $valor;
												?>
												<tr>
													<td><?php echo exibedata($exrow->data); ?></td>
													<td><?php echo getValue("nome", "produto", "id=" . $exrow->id_produto); ?></td>
													<td><?php echo $quantidade_prod_trocar; ?></td>
													<td><?php echo moeda($valor); ?></td>
												</tr>
											<?php endforeach; ?>
											<?php unset($exrow); ?>
										</tbody>
									</table>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('VALOR_TROCA'); ?></h4>
											<i>
												<h1 class="bold italic font-grey-cascade" id="valor_troca">
													<?php echo moeda($valor_total_troca); ?>
												</h1>
											</i>
										</div>
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('VALOR_TOTAL'); ?></h4>
											<i>
												<h1 class="bold italic font-blue-madison" id="valor2">
													R$ 0,00</h1>
											</i>
										</div>
										<div class="col-md-3">
											<h4 class="bold valor_pagar_titulo"><?php echo lang('VALOR_PAGAR'); ?></h4>
											<i>
												<h1 class="bold italic font-green-seagreen valor_pagar_texto" id="valor_pagar">
													R$ 0,00</h1>
											</i>
										</div>
										<div class="col-md-2">
											<h4 class="bold"><?php echo lang('TROCO_DINHEIRO'); ?></h4>
											<i>
												<h2 class="bold italic font-red" id="troco">
													R$ 0,00</h2>
											</i>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-4">
										<div class="row">
											<div class="form-group col-md-12">
												<label class="control-label"><?php echo lang('TABELA_PRECO'); ?></label>
												<div class="col-md-12">
													<select class="select2me form-control" id="id_tabela_venda" name="id_tabela"
														data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
														<?php
														$retorno_row = $produto->getTabelaNivel($usuario->nivel);
														if ($retorno_row):
															foreach ($retorno_row as $srow):
																?>
																<option value="<?php echo $srow->id; ?>"><?php echo $srow->tabela; ?></option>
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
											<div class="form-group col-md-12">
												<label class="control-label"><?php echo lang('CODIGO_DE_BARRAS'); ?></label>
												<div class="col-md-12">
													<input type="text" class="form-control barcode_troca" id="cod_barras"
														name="cod_barras" autofocus>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-12">
												<label class="control-label"><?php echo lang('PRODUTO'); ?></label>
												<div class="col-md-12">
													<div class="col-md-10">
														<select class="form-control" id="id_produto_venda" name="id_produto"
															data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
														</select>
													</div>
													<div class="col-md-2">
														<a href="javascript:void(0);" class="btn purple" id="adicionar_produto_troca"
															title="<?php echo lang('PRODUTO_ADICIONAR'); ?>"><i
																class="fa fa-plus fa-fw"></i></a>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-12">
												<label class="control-label"><?php echo lang('VALOR_DESCONTO'); ?></label>
												<div class="col-md-12">
													<input type="text" class="form-control moeda" name="valor_desconto_troca"
														id="valor_desconto_troca">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-12">
												<label class="control-label"><?php echo lang('DESCONTO_EM_PORCENTAGEM'); ?></label>
												<div class="col-md-12">
													<input type="text" class="form-control desconto" name="descporcentagem"
														id="valor_desconto_porcentagem_troca" data-prefix="%">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-12">
												<label class="control-label"><?php echo lang('PARCELAS'); ?></label>
												<div class="col-md-12">
													<input type="text" class="form-control inteiro" id="parcelas">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-12">
												<label class="control-label"><?php echo lang('VALOR'); ?></label>
												<div class="col-md-12">
													<input type="text" class="form-control moeda" id="valor_pago">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col-md-12">
												<label class="control-label"><?php echo lang('PAGAMENTO'); ?></label>
												<div class="col-md-12">
													<div class="col-md-10">
														<select class="select2me form-control selectPagamentoTroca" id="tipopagamento"
															name="tipopagamento"
															data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
															<option value=""></option>
															<?php
															$retorno_row = $faturamento->getTipoPagamento();
															if ($retorno_row):
																foreach ($retorno_row as $srow):
																	?>
																	<option value="<?php echo $srow->id; ?>"
																		id_categoria="<?php echo $srow->id_categoria; ?>"
																		avista="<?php echo $srow->avista; ?>" <?php if ($srow->id == "1")
																			   echo 'selected="selected"'; ?>><?php echo $srow->tipo; ?></option>
																	<?php
																endforeach;
																unset($srow);
															endif;
															?>
														</select>
													</div>
													<div class="col-md-2">
														<a href="javascript:void(0);" class="btn green-seagreen"
															id="adicionar_pagamento_troca"
															title="<?php echo lang('PAGAMENTO_ADICIONAR'); ?>"><i
																class="fa fa-plus fa-fw"></i></a>
													</div>
												</div>
											</div>
										</div>
										<div class="row" id="divDataBoleto" style="display:none;">
											<div class="form-group col-md-12">
												<label class="control-label"><?php echo lang('PRIMEIRA_PARCELA_BOLETO'); ?></label>
												<div class="col-md-12">
													<input type="text" class="form-control data calendario" name="data_boleto"
														id="data_boleto"
														value="<?php echo date('d/m/Y', strtotime(date("Y-m-d") . ' + 2 days')); ?>">
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-8">
										<div class="row">
											<div class="col-md-12">
												<div class="table-scrollable table-scrollable-borderless">
													<table class="table table-hover table-advance-green">
														<thead>
															<tr>
																<th width="50%"><?php echo lang('PAGAMENTO'); ?></th>
																<th width="15%"><?php echo lang('PARCELAS'); ?></th>
																<th width="15%"><?php echo lang('DESCONTO'); ?></th>
																<th width="30%"><?php echo lang('VALOR_PAGO'); ?></th>
																<th width="5%"></th>
															</tr>
														</thead>
														<tbody id="tabela_pagamentos">
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="table-scrollable table-scrollable-borderless">
													<table class="table table-hover table-advance">
														<thead>
															<tr>
																<th width="40%"><?php echo lang('PRODUTO'); ?></th>
																<th width="10%"><?php echo lang('ESTOQUE'); ?></th>
																<th width="10%"><?php echo lang('QUANTIDADE'); ?></th>
																<th width="15%"><?php echo lang('VALOR'); ?></th>
																<th width="20%"><?php echo lang('TOTAL'); ?></th>
																<th width="5%"></th>
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
							</div>
						</div>
						<input name="id_venda" type="hidden" value="<?php echo $id_venda; ?>" />
						<?php
						foreach ($array_produtos as $id_cad_venda => $qtde):
							if ((float) converteMoeda($qtde) != 0):
								?>
								<input name="qtde_produto[]" type="hidden" value="<?php echo $id_cad_venda . "," . converteMoeda($qtde); ?>" />
								<?php
							endif;
						endforeach;
						?>
						<input name="valor_produto_troca" id="valor_produto_troca" type="hidden"
							value="<?php echo $valor_total_troca; ?>" />
						<input name="valor_venda_troca" id="valor_venda_troca" type="hidden"
							value="<?php echo $valor_total_troca; ?>" />
						<input type="hidden" name="id_cadastro" id="id_cadastro_troca" value="<?php echo $row_venda->id_cadastro; ?>" />
						<input type="hidden" name="voucher_crediario" id="voucher_crediario" value="">
						<input type="hidden" name="celular_troca" id="celular_troca" value="">
						<input type="hidden" name="cpf_cnpj_troca" id="cpf_cnpj_troca" value="">
						<div class="form-actions">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="form-group">
											<div class="col-md-6">
												<button type="button" class="btn btn-submit green-seagreen"
													title="<?php echo lang('PRODUTO_TROCA_CONFIRMAR_BOTAO'); ?>">
													<i class="fa fa-check-square-o">&nbsp;&nbsp;</i>
													<?php echo lang('PRODUTO_TROCA_CONFIRMAR_BOTAO'); ?>
												</button>
												<!-- Salvar restante do valor utilizado em um voucher do crediario -->
												<button type="button" class="btn salvar-restante-crediario yellow-casablanca ocultar"
													title="Confirmar troca e salvar valor a utilizar restante como crédito no crediário?">
													<i class="fa fa-check-square-o">&nbsp;&nbsp;</i>
													<?php echo lang('SALVAR_RESTANTE_CREDIARIO'); ?>
												</button>
											</div>
											<div class="col-md-6">
												<a href="index.php?do=produto&acao=trocarproduto"
													class="btn default grey"><?php echo lang('VOLTAR'); ?></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php echo $core->doForm("processarTrocaProduto"); ?>
						</form>
						<!-- FINAL FORM-->
					</div>
				</div>
				</div>
				</div>
				<!-- FINAL DO ROW FORMULARIO -->
			<?php endif; ?>
		<?php endif; ?>
		</div>
		</div>
		<!-- FINAL DOS MODULOS DA PAGINA -->
		</div>
		<?php break; ?>
	<?php
	case "produtotrocaavulso":
		$produtos_troca = (!empty($_POST['id_produto'])) ? $_POST['id_produto'] : null;
		$valores_troca = (!empty($_POST['valor_venda'])) ? $_POST['valor_venda'] : 0;
		$quantidades_troca = (!empty($_POST['quantidade'])) ? $_POST['quantidade'] : 0;
		$contar_produtos = (is_array($produtos_troca)) ? count($produtos_troca) : 0;
		if ($contar_produtos == 0) {
			redirect_to("index.php?do=produto&acao=trocarprodutoavulso&msgError=1");
			return;
		}
		$valor_total_troca = 0;
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('PRODUTO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTO_TROCA'); ?></small></h1>
					</div>
					<!-- FINAL TITULO DA PAGINA -->
				</div>
			</div>
			<!-- FINAL CABECALHO DA PAGINA -->
			<!-- INICIO DOS MODULOS DA PAGINA -->
			<div class="page-content">
				<div class="container">
					<!-- INICIO DO ROW FORMULARIO -->
					<?php if ($contar_produtos == 0): ?>
						<div class="row">
							<div class="col-md-12">
								<div class="note note-danger">
									<h4 class="block"><?php echo lang('PRODUTO_TROCA_SELECIONE1'); ?></h4>
									<p><?php echo lang('PRODUTO_TROCA_SELECIONE2'); ?></p>
								</div>
							</div>
							<div class="col-md-12">
								<a href="index.php?do=produto&acao=trocarprodutoavulso"
									class="btn default grey"><?php echo lang('VOLTAR'); ?></a>
							</div>
						</div>
					<?php else: ?>
						<?php if ($faturamento->verificaCaixa($usuario->uid) == 0): ?>
							<div class="row">
								<div class="col-md-12">
									<div class="note note-warning">
										<h4 class="block"><?php echo lang('CAIXA_VENDA_AVISO'); ?></h4>
										<p><?php echo lang('CAIXA_VENDA_AVISO_DESCRICAO'); ?></p>
									</div>
								</div>
							</div>
						<?php endif; ?>
						<div class="row">
							<div class="col-md-12">
								<div class="portlet light">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-exchange"></i>
											<span><?php echo lang('PRODUTO_TROCA'); ?></span>
										</div>
										<div class="actions btn-set">
										</div>
									</div>
									<div class="portlet-body form">
										<!-- INICIO FORM-->
										<form action="" autocomplete="off" method="post" class="horizontal-form" name="admin_form"
											id="admin_form">
											<div class="form-body">
												<input name="valor" id="valor" type="hidden" />
												<div class="row">
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-3">
																	<h4 class="form-section">
																		<?php echo lang('PRODUTO_TROCA_NOVO'); ?>
																	</h4>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<table
															class="table table-bordered table-striped table-condensed table-advance">
															<thead>
																<tr>
																	<th><?php echo lang('PRODUTO'); ?></th>
																	<th><?php echo lang('QUANTIDADE'); ?></th>
																	<th><?php echo lang('VALOR_TOTAL'); ?></th>
																</tr>
															</thead>
															<tbody>
																<?php
																for ($i = 0; $i < $contar_produtos; $i++):
																	// $valor = ($valores_troca[$i])*$quantidades_troca[$i];
																	$valor = converteMoeda($valores_troca[$i]) * $quantidades_troca[$i];
																	$valor_total_troca += $valor;
																	?>
																	<tr>
																		<td><?php echo getValue("nome", "produto", "id=" . $produtos_troca[$i]); ?>
																		</td>
																		<td><?php echo $quantidades_troca[$i]; ?></td>
																		<td><?php echo moeda($valor); ?></td>
																	</tr>
																<?php endfor; ?>
																<?php unset($i); ?>
															</tbody>
														</table>
													</div>
												</div>
												<hr>
												<div class="row">
													<div class="col-md-12">
														<div class="row">
															<div class="col-md-3">
																<h4 class="bold"><?php echo lang('VALOR_TROCA'); ?></h4>
																<i>
																	<h1 class="bold italic font-grey-cascade" id="valor_troca">
																		<?php echo moeda($valor_total_troca); ?>
																	</h1>
																</i>
															</div>
															<div class="col-md-3">
																<h4 class="bold"><?php echo lang('VALOR_TOTAL'); ?></h4>
																<i>
																	<h1 class="bold italic font-blue-madison" id="valor2">
																		R$ 0,00</h1>
																</i>
															</div>
															<div class="col-md-3">
																<h4 class="bold valor_pagar_titulo">
																	<?php echo lang('VALOR_PAGAR'); ?>
																</h4>
																<i>
																	<h1 class="bold italic font-green-seagreen valor_pagar_texto"
																		id="valor_pagar">
																		R$ 0,00</h1>
																</i>
															</div>
															<div class="col-md-2">
																<h4 class="bold"><?php echo lang('TROCO_DINHEIRO'); ?></h4>
																<i>
																	<h2 class="bold italic font-red" id="troco">
																		R$ 0,00</h2>
																</i>
															</div>
														</div>
													</div>
												</div>
												<hr>
												<div class="row">
													<div class="col-md-12">
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label"><?php echo lang('TABELA_PRECO'); ?></label>
																	<div class="">
																		<select class="select2me form-control" id="id_tabela_venda"
																			name="id_tabela"
																			data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																			<?php
																			$retorno_row = $produto->getTabelaNivel($usuario->nivel);
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->id; ?>">
																						<?php echo $srow->tabela; ?>
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
																		class="control-label"><?php echo lang('VENDEDOR'); ?></label>
																	<div class="">
																		<select class="select2me form-control" id="id_vendedor"
																			name="id_vendedor"
																			data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $usuario->getVendedor();
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $usuario->uid)
																						   echo 'selected="selected"'; ?>>
																						<?php echo strtoupper($srow->usuario); ?>
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
																		class="control-label"><?php echo lang('CODIGO_DE_BARRAS'); ?></label>
																	<div class="input-group">
																		<input type="text" class="form-control barcode_troca"
																			id="cod_barras" name="cod_barras" autofocus>
																		<span
																			class="input-group-addon"><?php echo lang('TECLE_ENTER'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label"><?php echo lang('PRODUTO'); ?></label>
																	<div class="row">
																		<div class="col-md-10">
																			<select class="form-control" id="id_produto_venda"
																				name="id_produto"
																				data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																			</select>
																		</div>
																		<a href="javascript:void(0);" class="btn purple"
																			id="adicionar_produto_troca"
																			title="<?php echo lang('PRODUTO_ADICIONAR'); ?>"><i
																				class="fa fa-plus fa-fw"></i></a>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label"><?php echo lang('VALOR_DESCONTO'); ?></label>
																	<div class="input-group">
																		<input type="text" class="form-control moeda"
																			name="valor_desconto_troca" id="valor_desconto_troca">
																		<span
																			class="input-group-addon"><?php echo lang('FORMATO_MOEDA'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label"><?php echo lang('DESCONTO_EM_PORCENTAGEM'); ?></label>
																	<div class="input-group">
																		<input type="text" class="form-control desconto"
																			name="descporcentagem"
																			id="valor_desconto_porcentagem_troca" data-prefix="%">
																		<span
																			class="input-group-addon"><?php echo lang('SIMB_PORCENTAGEM'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label"><?php echo lang('PARCELAS'); ?></label>
																	<div class="">
																		<input type="text" class="form-control inteiro"
																			id="parcelas">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label"><?php echo lang('VALOR'); ?></label>
																	<div class="input-group">
																		<input type="text" class="form-control moeda"
																			id="valor_pago">
																		<span
																			class="input-group-addon"><?php echo lang('FORMATO_MOEDA'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label"><?php echo lang('PAGAMENTO'); ?></label>
																	<div class="row">
																		<div class="col-md-10">
																			<select
																				class="select2me form-control selectPagamentoTroca"
																				id="tipopagamento" name="tipopagamento"
																				data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																				<option value=""></option>
																				<?php
																				$retorno_row = $faturamento->getTipoPagamento();
																				if ($retorno_row):
																					foreach ($retorno_row as $srow):
																						?>
																						<option value="<?php echo $srow->id; ?>"
																							id_categoria="<?php echo $srow->id_categoria; ?>"
																							avista="<?php echo $srow->avista; ?>" <?php if ($srow->id == "1")
																								   echo 'selected="selected"'; ?>>
																							<?php echo $srow->tipo; ?>
																						</option>
																						<?php
																					endforeach;
																					unset($srow);
																				endif;
																				?>
																			</select>
																		</div>
																		<a href="javascript:void(0);" class="btn green-seagreen"
																			id="adicionar_pagamento_troca"
																			title="<?php echo lang('PAGAMENTO_ADICIONAR'); ?>"><i
																				class="fa fa-plus fa-fw"></i></a>
																	</div>
																</div>
															</div>
															<div class="row" id="divDataBoleto" style="display:none;">
																<div class="form-group">
																	<label
																		class="control-label"><?php echo lang('PRIMEIRA_PARCELA_BOLETO'); ?></label>
																	<div class="">
																		<input type="text" class="form-control data calendario"
																			name="data_boleto" id="data_boleto"
																			value="<?php echo date('d/m/Y', strtotime(date("Y-m-d") . ' + 2 days')); ?>">
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-8">
															<div class="row">
																<div class="col-md-12">
																	<div class="table-scrollable table-scrollable-borderless"
																		style="height: 150px; overflow-y: scroll;">
																		<table class="table table-hover table-advance-green">
																			<thead style="top: 0; position: sticky;">
																				<tr>
																					<th width="50%"><?php echo lang('PAGAMENTO'); ?>
																					</th>
																					<th width="15%"><?php echo lang('PARCELAS'); ?>
																					</th>
																					<th width="15%"><?php echo lang('DESCONTO'); ?>
																					</th>
																					<th width="30%">
																						<?php echo lang('VALOR_PAGO'); ?>
																					</th>
																					<th width="5%"></th>
																				</tr>
																			</thead>
																			<tbody id="tabela_pagamentos">
																			</tbody>
																		</table>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-12">
																	<div class="table-scrollable table-scrollable-borderless"
																		style="height: 400px; overflow-y: scroll;">
																		<table class="table table-hover table-advance">
																			<thead style="top: 0; position: sticky;">
																				<tr>
																					<th width="40%"><?php echo lang('PRODUTO'); ?>
																					</th>
																					<th width="10%"><?php echo lang('ESTOQUE'); ?>
																					</th>
																					<th width="10%">
																						<?php echo lang('QUANTIDADE'); ?>
																					</th>
																					<th width="15%"><?php echo lang('VALOR'); ?>
																					</th>
																					<th width="20%"><?php echo lang('TOTAL'); ?>
																					</th>
																					<th width="5%"></th>
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
												</div>
											</div>
											<?php for ($i = 0; $i < $contar_produtos; $i++): ?>
												<input name="qtde_produto[]" type="hidden"
													value="<?php echo $produtos_troca[$i] . ',' . $quantidades_troca[$i]; ?>" />
											<?php endfor; ?>
											<input name="valor_produto_troca" id="valor_produto_troca" type="hidden"
												value="<?php echo $valor_total_troca; ?>" />
											<input name="valor_venda_troca" id="valor_venda_troca" type="hidden"
												value="<?php echo $valor_total_troca; ?>" />
											<div class="form-actions">
												<div class="row">
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<div class="col-md-6">
																	<button type="button" class="btn btn-submit green-seagreen"><i
																			class="fa fa-check-square-o">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_TROCA_CONFIRMAR_BOTAO'); ?></button>
																</div>
																<div class="col-md-6">
																	<a href="index.php?do=produto&acao=trocarproduto"
																		class="btn default grey"><?php echo lang('VOLTAR'); ?></a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php echo $core->doForm("processarTrocaProduto"); ?>
										</form>
										<!-- FINAL FORM-->
									</div>
								</div>
							</div>
						</div>
						<!-- FINAL DO ROW FORMULARIO -->
					<?php endif; ?>
				</div>
			</div>
			<!-- FINAL DOS MODULOS DA PAGINA -->
		</div>
		<?php break; ?>
	<?php
	case "importarprodutos"; ?>
		<!-- Plupload -->
		<link rel="stylesheet" type="text/css" href="./assets/plugins/plupload/css/jquery.plupload.queue.css" />
		<script type="text/javascript" src="./assets/plugins/plupload/plupload.full.js"></script>
		<script type="text/javascript" src="./assets/plugins/plupload/jquery.plupload.queue.js"></script>
		<script type="text/javascript" src="./assets/plugins/plupload/i18n/pt_BR.js"></script>
		<script type="text/javascript" src="./assets/scripts/fileupload.js"></script>
		<script>
			jQuery(document).ready(function () {
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
						<h1><?php echo lang('IMPORTACAO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('IMPORTAR_PRODUTOS'); ?></small></h1>
					</div>
					<!-- END PAGE TITLE -->
				</div>
			</div>
			<!-- END PAGE HEAD -->
			<!-- BEGIN PAGE CONTENT -->
			<div class="page-content">
				<div class="container">
					<div class="portlet light">
						<a href="excel/Importacao_Produtos.xlsx" download style="color: #f00;">
							<h4>
								<strong>
									<?php echo lang('DOWNLOAD_ARQUIVO_IMPORTACAO'); ?>
								</strong>
							</h4>
						</a>
						<br>
						<div class="help-block">
							<h5><?php echo lang('OBSERVACOES'); ?>:</h5>
							<h5>1- <?php echo lang('OBSERVACAO_IMPORTACAO_OBRIGATORIEDADE'); ?></h5>
							<h5>2- <?php echo lang('OBSERVACAO_DOIS_IMPORTACAO_PRODUTOS'); ?></h5>
							<h5>3- <?php echo lang('OBSERVACAO_QUATRO_IMPORTACAO_PRODUTOS'); ?></h5>
						</div>
						<form action="" autocomplete="off" class="form-inline" method="post" id="admin_form" name="admin_form">
							<div class="portlet-body">
								<div class="row">
									<div class="col-md-12">
										<div class="panel panel-info">
											<div class="panel-heading">
												<h3 class="panel-title"><i
														class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('IMPORTAR_PRODUTOS'); ?>
												</h3>
											</div>
											<div class="panel-body">
												<?php echo lang('IMPORTAR_PRODUTOS_OBS'); ?>
												<div class="plupload"></div>
											</div>
										</div>
										<input name="processarPlanilhaProdutosImportacao" type="hidden" value="1" />
									</div>
								</div>
							</div>
						</form>
						<?php if (!empty($_SESSION['erros_importacao'])): ?>
						<!-- CSS LOCALIZAÇÃO -> LAYOUT.CSS no ASSETS -->
						<!-- Botão flutuante -->
						<button id="btnVisualizarErros" class="btn btn-danger">
							Visualizar Erros
						</button>
						<!-- Fundo escurecido -->
						<div id="popupOverlay">
							<!-- Popup de erros -->
							<div id="popupErros">
								<!-- Botão de fechar -->
								<button id="fecharPopupErros" title="Fechar">&times;</button>
								<button id="btnBaixarArquivoErros" class="btn btn-success" style="position: absolute; font-size: 16px; top: 10px; right: 10px; z-index: 10;">
				     				⬇ Baixar Arquivo de Erros
				  				</button>
								<h3>Erros encontrados:</h3>
								<ul>
									<?php foreach ($_SESSION['erros_importacao'] as $erro): ?>
									<li><?= strip_tags("- " . $erro, '<b>') ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
						<!-- Script para controle do popup -->
						<script>
							document.addEventListener("DOMContentLoaded", function () {
								const btn = document.getElementById("btnVisualizarErros");
								const overlay = document.getElementById("popupOverlay");
								const closeBtn = document.getElementById("fecharPopupErros");
								const popup = document.getElementById("popupErros");

								btn.addEventListener("click", () => overlay.style.display = "block");
								closeBtn.addEventListener("click", () => overlay.style.display = "none");

								document.addEventListener("keydown", (e) => {
									if (e.key === "Escape") overlay.style.display = "none";
								});

								overlay.addEventListener("click", (e) => {
									if (!popup.contains(e.target)) {
									overlay.style.display = "none";
									}
								});
							});
						</script>
						<script>
							const errosImportacao = <?php echo json_encode($_SESSION['erros_importacao'] ?? []); ?>;
							document.getElementById("btnBaixarArquivoErros").addEventListener("click", function () {
								const titulo = "Por favor, corrija os seguintes erros para importar os produtos com sucesso:\n\n";
								const corpo = errosImportacao.map(erro => "- " + erro.replace(/<\/?b>/gi, "")).join("\n");
								const conteudo = titulo + corpo;

								const blob = new Blob([conteudo], { type: "text/plain;charset=utf-8" });
								const url = URL.createObjectURL(blob);

								const link = document.createElement("a");
								link.href = url;
								link.download = "erros_importacao.txt";
								link.click();

								URL.revokeObjectURL(url); // Libera a memória
							});
						</script>

						<?php unset($_SESSION['erros_importacao']); ?>
						<?php endif; ?>
					</div>
					<!-- END PAGE CONTENT INNER -->
				</div>
			</div>
			<!-- END PAGE CONTENT -->
		</div>
		<!-- END PAGE CONTAINER -->
		<?php break; ?>
	<?php
	default: ?>
		<div class="imagem-fundo"></div>
		<?php break; ?>
<?php endswitch; ?>