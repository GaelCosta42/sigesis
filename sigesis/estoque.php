<?php
/**
 * Estoque
 *
 */
if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe não é permitido.');
if (!$usuario->is_Todos())
	redirect_to("login.php");
?><?php switch (Filter::$acao):
	case "adicionar": ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('ESTOQUE_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ESTOQUE_ADICIONAR'); ?></small></h1>
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
										<i class="fa fa-plus-square font-green"></i>
										<span class="font-green"><?php echo lang('ESTOQUE_ADICIONAR'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="index.php?do=estoque&acao=retirada" class="btn btn-sm red"><i
												class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_RETIRADA'); ?></a>
										<a href="index.php?do=estoque&acao=historico" class="btn btn-sm blue-hoki"><i
												class="fa fa-history">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_HISTORICO'); ?></a>
										<a href="index.php?do=estoque&acao=movimentacao" class="btn btn-sm grey-cascade"><i
												class="fa fa-circle-o-notch">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_MOVIMENTACAO'); ?></a>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control"
																		id="id_produto_entrada_estoque" name="id_produto"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getProdutosEntradaRetirada();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>">
																					<?php echo $srow->codigo . "#" . $srow->nome . "#" . $srow->codigobarras; ?>
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
																	class="control-label col-md-3"><?php echo lang('ESTOQUE_ATUAL'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control decimalp"
																		id="estoqueatual">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VALOR_CUSTO'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control decimalp"
																		id="custoatual">
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('QUANTIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalpositivo"
																		name="quantidade">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NOVO_VALOR_CUSTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="novo_valor_custo">
																	<span
																		class="help-block"><?php echo lang('NOVO_VALOR_CUSTO_OBS'); ?></span>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('MOTIVO'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="motivo"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<option value="1"><?php echo motivo(1); ?></option>
																		<option value="2"><?php echo motivo(2); ?></option>
																		<option value="6"><?php echo motivo(6); ?></option>
																		<option value="8"><?php echo motivo(8); ?></option>
																	</select>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('OBSERVACAO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="observacao">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<input name="tipo" type="hidden" value="1" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
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
									<?php echo $core->doForm("processarEstoque"); ?>
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
	<?php case "retirada": ?>
		<?php if (!$usuario->is_Administrativo()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return; endif; ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('ESTOQUE_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ESTOQUE_RETIRADA'); ?></small></h1>
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
										<i class="fa fa-minus-square font-red"></i>
										<span class="font-red"><?php echo lang('ESTOQUE_RETIRADA'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="index.php?do=estoque&acao=adicionar" class="btn btn-sm green"><i
												class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_ADICIONAR'); ?></a>
										<a href="index.php?do=estoque&acao=historico" class="btn btn-sm blue-hoki"><i
												class="fa fa-history">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_HISTORICO'); ?></a>
										<a href="index.php?do=estoque&acao=movimentacao" class="btn btn-sm grey-cascade"><i
												class="fa fa-circle-o-notch">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_MOVIMENTACAO'); ?></a>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control"
																		id="id_produto_estoque" name="id_produto"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getProdutosEntradaRetirada();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>">
																					<?php echo $srow->codigo . "#" . $srow->nome . "#" . $srow->codigobarras; ?>
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
																	class="control-label col-md-3"><?php echo lang('ESTOQUE_ATUAL'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control decimalp"
																		id="estoqueatual">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('QUANTIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalpositivo"
																		name="quantidade">
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('MOTIVO'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control" name="motivo"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<option value="4"><?php echo motivo(4); ?></option>
																		<option value="5"><?php echo motivo(5); ?></option>
																		<option value="6"><?php echo motivo(6); ?></option>
																		<option value="10"><?php echo motivo(10); ?></option>
																	</select>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('OBSERVACAO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="observacao">
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<br>
												</div>
												<div class="col-md-12">
													<h4 class="form-section"><?php echo lang('ESTOQUE_INFO_DESPESA');?></h4>
													<div class='row'>
														<div class="col-md-12">
															<div class="col-md-6">
																<div class='row'>
																	<div class='form-group'>
																		<label class='control-label col-md-3'></label>
																		<div class='col-md-9'>
																			<div class='md-checkbox-list'>
																				<div class='md-checkbox'>
																					<input type='checkbox' class='md-check' name='estoque_gerar_despesa' id='estoque_gerar_despesa' value='1'>
																					<label for='estoque_gerar_despesa'>
																					<span></span>
																					<span class='check'></span>
																					<span class='box'></span>
																					<?php echo lang('GERAR_DESPESA_ESTOQUE');?></label>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
															</div>
														</div>
													</div>
													<div class='row itens_despesa_estoque ocultar'>
														<div class='col-md-12'>
															<!--col-md-6-->
															<div class='col-md-6'>																
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
																		<label class='control-label col-md-3'><?php echo lang('CENTRO_CUSTO'); ?></label>
																		<div class='col-md-9'>
																			<select style="width: 100%" class='select2me form-control' name='id_custo' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
																			<select style="width: 100%" class='select2me form-control' name='id_pai' id='id_pai_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
																			<select style="width: 100%" class='select2me form-control' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
															</div>
															<!--/col-md-6-->
															<!--col-md-6-->
															<div class='col-md-6'>
																<div class='row'>
																	<div class='form-group'>
																		<label class='control-label col-md-3'><?php echo lang('BANCO');?></label>
																		<div class='col-md-9'>
																			<select style="width: 100%" class='select2me form-control' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
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
																			<select class='select2me form-control' name='tipo_pagamento' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' style="width: 100%">
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
															</div>
															<!--/col-md-6-->
														</div>														
													</div>
												</div>
											</div>
										</div>
										<input name="tipo" type="hidden" value="2" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
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
									<?php echo $core->doForm("processarEstoque"); ?>
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
	<?php case "transferencia": ?>
		<?php if (!$usuario->is_Administrativo()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return; endif; ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('ESTOQUE_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ESTOQUE_TRANSFERENCIA'); ?></small>
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
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-exchange font-blue"></i>
										<span class="font-blue"><?php echo lang('ESTOQUE_TRANSFERENCIA'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="index.php?do=estoque&acao=adicionar" class="btn btn-sm green"><i
												class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_ADICIONAR'); ?></a>
										<a href="index.php?do=estoque&acao=retirada" class="btn btn-sm red"><i
												class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_RETIRADA'); ?></a>
										<a href="index.php?do=estoque&acao=historico" class="btn btn-sm blue-hoki"><i
												class="fa fa-history">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_HISTORICO'); ?></a>
										<a href="index.php?do=estoque&acao=movimentacao" class="btn btn-sm grey-cascade"><i
												class="fa fa-circle-o-notch">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_MOVIMENTACAO'); ?></a>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control"
																		id="id_produto_estoque" name="id_produto"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $produto->getProdutosGrade();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>">
																					<?php echo $srow->codigo . "#" . $srow->nome . "#" . $srow->codigobarras; ?>
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
																	class="control-label col-md-3"><?php echo lang('ESTOQUE_ATUAL'); ?></label>
																<div class="col-md-9">
																	<input readonly type="text" class="form-control decimalp"
																		id="estoqueatual">
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('QUANTIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimalp"
																		name="quantidade">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('EMPRESA_ORIGEM'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control"
																		name="id_empresa_origem"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $empresa->getEmpresas();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>">
																					<?php echo $srow->nome; ?></option>
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
																	class="control-label col-md-3"><?php echo lang('EMPRESA_DESTINO'); ?></label>
																<div class="col-md-9">
																	<select class="select2me form-control"
																		name="id_empresa_destino"
																		data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_row = $empresa->getEmpresas();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																				?>
																				<option value="<?php echo $srow->id; ?>">
																					<?php echo $srow->nome; ?></option>
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
										<input name="motivo" type="hidden" value="2" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
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
									<?php echo $core->doForm("processarTransferenciaEstoque"); ?>
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
	<?php case "listar":
		$id_categoria = get('id_categoria');
		$id_grupo = get('id_grupo');
		$id_fabricante = get('id_fabricante');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#id_categoria').change(function () {
					var id_categoria = $("#id_categoria").val();
					var id_grupo = $("#id_grupo").val();
					var id_fabricante = $("#id_fabricante").val();
					window.location.href = 'index.php?do=estoque&acao=listar&id_categoria=' + id_categoria + '&id_grupo=' + id_grupo + '&id_fabricante=' + id_fabricante;
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
					window.location.href = 'index.php?do=estoque&acao=listar&id_categoria=' + id_categoria + '&id_grupo=' + id_grupo + '&id_fabricante=' + id_fabricante;
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
					window.location.href = 'index.php?do=estoque&acao=listar&id_categoria=' + id_categoria + '&id_grupo=' + id_grupo + '&id_fabricante=' + id_fabricante;
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
						<h1><?php echo lang('ESTOQUE_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ESTOQUE_LISTAR'); ?></small></h1>
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
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('ESTOQUE_LISTAR'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="index.php?do=estoque&acao=adicionar" class="btn btn-sm green"><i
												class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_ADICIONAR'); ?></a>
										<a href="index.php?do=estoque&acao=retirada" class="btn btn-sm red"><i
												class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_RETIRADA'); ?></a>
										<a href="index.php?do=estoque&acao=historico" class="btn btn-sm blue-hoki"><i
												class="fa fa-history">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_HISTORICO'); ?></a>
										<a href="index.php?do=estoque&acao=movimentacao" class="btn btn-sm grey-cascade"><i
												class="fa fa-circle-o-notch">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_MOVIMENTACAO'); ?></a>
										<?php if ($core->aplicativo_estoque == 1): ?>
											<a href="index.php?do=estoque&acao=comparacaoestoquefisico" class="btn btn-sm"
												style="background-color: #fd7e14; color: #fff;"
												title="Comparar estoque fisico (produtos que vieram do APP ESTOQUE) com estoque do sistema"><i
													class="fa fa-exchange">&nbsp;&nbsp;</i><?php echo lang('COMPARAR_ESTOQUE'); ?></a>
										<?php endif; ?>
										<?php
										$estoque_minimo = $_GET['estoqueminimo'] ?? false;
										if ($estoque_minimo == 'true') {
											echo "<a href='index.php?do=estoque&acao=listar' class='btn btn-sm green'><i class='fa fa-circle-o-notch'>&nbsp;&nbsp;</i>" . lang('ESTOQUE_COMPLETO') . "</a>";
										} else {
											echo "<a href='index.php?do=estoque&acao=listar&estoqueminimo=true' class='btn btn-sm red'><i class='fa fa-circle-o-notch'>&nbsp;&nbsp;</i>" . lang('ESTOQUE_MINIMO') . "</a>";
										}
										?>
									</div>
								</div>
								<div class="portlet-body">
									<form class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_grupo" id="id_grupo">
												<option value="">TODOS GRUPOS</option>
												<?php
												$retorno_row = $grupo->getGrupos();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_grupo)
															  echo 'selected="selected"'; ?>><?php echo $srow->grupo; ?></option>
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
												$retorno_row = $categoria->getCategorias();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_categoria)
															  echo 'selected="selected"'; ?>><?php echo $srow->categoria; ?></option>
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
												$retorno_row = $fabricante->getFabricantes();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_fabricante)
															  echo 'selected="selected"'; ?>><?php echo $srow->fabricante; ?></option>
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
												<th>#</th>
												<th><?php echo lang('CODIGO_DA_NOTA'); ?></th>
												<th><?php echo lang('CODIGO_DE_BARRAS'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('GRUPO'); ?></th>
												<th><?php echo lang('ESTOQUE_ATUAL'); ?></th>
												<th><?php echo lang('ESTOQUE_MINIMO'); ?></th>
												<th><?php echo lang('ACOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php $estoquem = $_GET['estoqueminimo'] ?? false;
											$retorno_row = $produto->getTodosEstoque($id_grupo, $id_categoria, $id_fabricante, $estoquem);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr
														class="<?= $exrow->estoque <= $exrow->estoque_minimo && $exrow->estoque_minimo > 0 ? 'danger' : '' ?>">
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo $exrow->codigo; ?></td>
														<td><?php echo $exrow->codigobarras; ?></td>
														<td><?php echo $exrow->nome; ?></td>
														<td><?php echo $exrow->grupo; ?></td>
														<td><?php echo decimalp($exrow->estoque); ?></td>
														<td><?php echo decimalp($exrow->estoque_minimo); ?></td>
														<td>
															<a href="index.php?do=estoque&acao=historico&id_produto=<?php echo $exrow->id; ?>"
																class="btn btn-sm grey-cascade"
																title="<?php echo lang('DETALHES') . ': ' . $exrow->nome; ?>"><i
																	class="fa fa-search"></i></a>
															<a href="javascript:void(0);" class="btn btn-sm yellow"
																onclick="javascript:void window.open('imprimir_estoque.php?id_produto=<?php echo $exrow->id; ?>','<?php echo $exrow->nome; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title="<?php echo lang('VISUALIZAR'); ?>"><i
																	class="fa fa-header"></i></a>
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
	<?php case "historico":
		$id_produto = get('id_produto');
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y', strtotime('-15 days'));
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y'); ?>
		<?php if (!$usuario->is_Administrativo()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return; endif; ?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					var id_produto = $("#id_produto").val();
					window.location.href = 'index.php?do=estoque&acao=historico&id_produto=' + id_produto + '&dataini=' + dataini + '&datafim=' + datafim;

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
						<h1><?php echo lang('ESTOQUE_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ESTOQUE_HISTORICO'); ?></small></h1>
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
										<i class="fa fa-history font-blue-hoki"></i>
										<span class="font-blue-hoki"><?php echo lang('ESTOQUE_HISTORICO'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="index.php?do=estoque&acao=adicionar" class="btn btn-sm green"><i
												class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_ADICIONAR'); ?></a>
										<?php if ($usuario->is_Administrativo()): ?>
											<a href="index.php?do=estoque&acao=retirada" class="btn btn-sm red"><i
													class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_RETIRADA'); ?></a>
										<?php endif; ?>
										<a href="index.php?do=estoque&acao=movimentacao" class="btn btn-sm grey-cascade"><i
												class="fa fa-circle-o-notch">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_MOVIMENTACAO'); ?></a>
									</div>
								</div>
								<div class="portlet-body">
									<form class="form-inline">
										<label><?php echo lang('DATA'); ?>&nbsp;&nbsp;</label><input type="text"
											class="form-control input-medium calendario data" name="dataini" id="dataini"
											value="<?php echo $dataini; ?>">
										<input type="text" class="form-control input-medium calendario data" name="datafim"
											id="datafim" value="<?php echo $datafim; ?>">
										&nbsp;&nbsp;
										<select class="select2me form-control input-large" id="id_produto" name="id_produto"
											data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $produto->getProdutosEntradaRetirada();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_produto)
														  echo 'selected="selected"'; ?>><?php echo $srow->codigo . "#" . $srow->nome; ?></option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
										<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor; ?>"><i
												class="fa fa-search" /></i> <?php echo lang('BUSCAR'); ?></button>
									</form>
								</div>
								<div class="portlet-body">
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable-asc">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('CODIGO'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('MOVIMENTACAO'); ?></th>
												<th><?php echo lang('QUANTIDADE'); ?></th>
												<th><?php echo lang('OBSERVACAO'); ?></th>
												<th><?php echo lang('EMPRESA'); ?></th>
												<th><?php echo lang('DATA'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $produto->getHistoricoEstoque($id_produto, $dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow): ?>
													<tr>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo $exrow->codigo; ?></td>
														<td><?php echo $exrow->produto; ?></td>
														<td><?php echo motivo($exrow->motivo); ?></td>
														<td><?php echo decimalp($exrow->quantidade); ?></td>
														<td><?php echo $exrow->observacao; ?></td>
														<td><?php echo $exrow->empresa; ?></td>
														<td><?php echo exibedataHora($exrow->data); ?></td>
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
	<?php case "movimentacao":
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y', strtotime('-15 days'));
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y'); ?>
		<?php if (!$usuario->is_Administrativo()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return; endif; ?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=estoque&acao=movimentacao&dataini=' + dataini + '&datafim=' + datafim;

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
						<h1><?php echo lang('ESTOQUE_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ESTOQUE_MOVIMENTACAO'); ?></small>
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
										<i class="fa fa-circle-o-notch font-grey-cascade"></i>
										<span class="font-grey-cascade"><?php echo lang('ESTOQUE_MOVIMENTACAO'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="index.php?do=estoque&acao=adicionar" class="btn btn-sm green"><i
												class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_ADICIONAR'); ?></a>
										<?php if ($usuario->is_Administrativo()): ?>
											<a href="index.php?do=estoque&acao=retirada" class="btn btn-sm red"><i
													class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_RETIRADA'); ?></a>
										<?php endif; ?>
										<a href="index.php?do=estoque&acao=historico" class="btn btn-sm blue-hoki"><i
												class="fa fa-history">&nbsp;&nbsp;</i><?php echo lang('ESTOQUE_HISTORICO'); ?></a>
									</div>
								</div>
								<div class="portlet-body">
									<form class="form-inline">
										<label><?php echo lang('DATA'); ?>&nbsp;&nbsp;</label><input type="text"
											class="form-control input-medium calendario data" name="dataini" id="dataini"
											value="<?php echo $dataini; ?>">
										<input type="text" class="form-control input-medium calendario data" name="datafim"
											id="datafim" value="<?php echo $datafim; ?>">
										&nbsp;&nbsp;
										<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor; ?>"><i
												class="fa fa-search"></i> <?php echo lang('BUSCAR'); ?></button>
									</form>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th>ID Produto</th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('ESTOQUE_MOTIVO_COMPRA'); ?></th>
												<th><?php echo lang('ESTOQUE_MOTIVO_TRANSFERENCIA'); ?></th>
												<th><?php echo lang('ESTOQUE_MOTIVO_VENDA'); ?></th>
												<th><?php echo lang('ESTOQUE_MOTIVO_CONSUMO'); ?></th>
												<th><?php echo lang('ESTOQUE_MOTIVO_PERDA'); ?></th>
												<th><?php echo lang('ESTOQUE_MOTIVO_AJUSTE'); ?></th>
												<th><?php echo lang('ESTOQUE_MOTIVO_CANCELAMENTO'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$tabela = '';
											$retorno_row = $produto->getMovimentacaoEstoque($dataini, $datafim);
											if ($retorno_row) {
												foreach ($retorno_row as $exrow) {

													$id_produto = $exrow->id_produto;
													$nome = $exrow->produto;
													$compra = $exrow->compra;
													$transferencia = $exrow->transferencia;
													$venda = $exrow->venda;
													$consumo = $exrow->consumo;
													$perda = $exrow->perda;
													$ajuste = $exrow->ajuste;
													$cancelamento = $exrow->cancelamento;

													$tabela .= "<tr>
																<td>$id_produto</td>
																<td>$nome</td>
																<td>$compra</td>
																<td>$transferencia</td>
																<td>$venda</td>
																<td>$consumo</td>
																<td>$perda</td>
																<td>$ajuste</td>
																<td>$cancelamento</td>
															</tr>";
												}
												unset($exrow);
												echo $tabela;
											}
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
	<?php case "comparacaoestoquefisico":
		if ($core->aplicativo_estoque == 0)
			redirect_to("login.php");
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('ESTOQUE_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('COMPARAR_ESTOQUE'); ?></small></h1>
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
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('COMPARAR_ESTOQUE'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="javascript:void(0);" class="btn btn-sm btn-success somarEstoqueTodosProdutos"
											acao="somarEstoque"
											title="Somar o estoque fisico com estoque atual de todos produto">
											<i class="fa fa-plus-square"></i> &nbsp;
											<?php echo lang('SOMAR') ?>
										</a>
										<a href="javascript:void(0);"
											class="btn btn-sm btn-primary substituirEstoqueTodosProdutos"
											acao="substituirEstoque"
											title="Substituir o estoque atual pelo estoque fisico de todos produtos">
											<i class="fa fa-refresh"></i> &nbsp;
											<?php echo lang('SUBSTITUIR') ?>
										</a>
										<a href="javascript:void(0);" class="btn btn-sm btn-danger manterEstoqueTodosProdutos"
											title="Manter estoque de todos produtos" acao="manterEstoque">
											<i class="fa fa-retweet"></i> &nbsp;
											<?php echo lang('MANTER') ?>
										</a>

									</div>
								</div>
								<div class="portlet-body">
									<div class="help-block carregando ocultar">Carregando...</div>
									<!-- Barra de progresso -->
									<div class="containerProgressBar ocultar">
										<div class="progress-bar" data-progress="0"></div>
									</div>
									<!-- Barra de progresso -->
								</div>
								<div class="portlet-body">
									<form class="form-inline" action="" method="post" name="admin_form" id="admin_form">
										<table
											class="table table-bordered table-striped table-condensed table-advance dataTable-desc">
											<thead>
												<tr>
													<th>#</th>
													<th class="table-checkbox">
														<input type="checkbox" id="checkTodos" name="checkTodos"
															class="group-checkable" data-set=".checkboxes" />
													</th>
													<th>#</th>
													<th><?php echo lang('PRODUTO'); ?></th>
													<th><?php echo lang('ESTOQUE_ATUAL'); ?></th>
													<th><?php echo lang('ESTOQUE_FISICO'); ?></th>
													<th><?php echo lang('VALOR_CUSTO'); ?></th>
													<th><?php echo lang('USUARIO'); ?></th>
													<th><?php echo lang('DATA_ENTRADA'); ?></th>
													<th><?php echo lang('ACOES'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$retorno_row = $produto->getProdutosAppEstoqueComparacao();
												$soma_quantidade = 0;
												if ($retorno_row):
													foreach ($retorno_row as $exrow):
														?>
														<tr>
															<td><?php echo $exrow->id; ?></td>
															<td>
																<input name="id_produto[]" type="checkbox" class="checkboxes"
																	value="<?php echo $exrow->id_produto ?>" />
															</td>
															<td><?php echo $exrow->id; ?></td>
															<td>
																<a href="index.php?do=produto&acao=editar&id=<?php echo $exrow->id_produto ?>"
																	target="_blank">
																	<?php echo $exrow->nome; ?>
																</a>
															</td>
															<td><?php echo $exrow->estoque_atual != $exrow->estoque_atual_analise ? decimalp($exrow->estoque_atual) : decimalp($exrow->estoque_atual_analise); ?>
															</td>
															<td><?php echo decimalp($exrow->estoque_fisico); ?></td>
															<td><?php echo moeda($exrow->valor_custo); ?></td>
															<td><?php echo $exrow->usuario ?></td>
															<td><?php echo exibedataHora($exrow->data) ?></td>
															<td>
																<a href="javascript:void(0);"
																	class="btn btn-sm btn-success acaoAnaliseEstoque"
																	id="<?php echo $exrow->id ?>"
																	id_produto="<?php echo $exrow->id_produto ?>"
																	nome="<?php echo $exrow->nome ?>"
																	estoque_atual="<?php echo $exrow->estoque_atual ?>"
																	estoque_fisico="<?php echo $exrow->estoque_fisico ?>"
																	valor_custo="<?php echo $exrow->valor_custo; ?>" acao="somarEstoque"
																	title="Somar o estoque fisico com estoque atual do produto: <?php echo "#" . $exrow->nome ?>">
																	<i class="fa fa-plus-square"></i>
																</a>
																<a href="javascript:void(0);"
																	class="btn btn-sm btn-primary acaoAnaliseEstoque"
																	id="<?php echo $exrow->id ?>"
																	id_produto="<?php echo $exrow->id_produto ?>"
																	nome="<?php echo $exrow->nome ?>"
																	estoque_atual="<?php echo $exrow->estoque_atual ?>"
																	estoque_fisico="<?php echo $exrow->estoque_fisico ?>"
																	valor_custo="<?php echo $exrow->valor_custo; ?>"
																	acao="substituirEstoque"
																	title="Substituir o estoque atual pelo estoque fisico do produto: <?php echo "#" . $exrow->nome ?>">
																	<i class="fa fa-refresh"></i>
																</a>
																<a href="javascript:void(0);"
																	class="btn btn-sm btn-danger acaoAnaliseEstoque"
																	id="<?php echo $exrow->id ?>"
																	id_produto="<?php echo $exrow->id_produto ?>"
																	nome="<?php echo $exrow->nome ?>"
																	estoque_atual="<?php echo $exrow->estoque_atual ?>"
																	estoque_fisico="<?php echo $exrow->estoque_fisico ?>"
																	valor_custo="<?php echo $exrow->valor_custo; ?>"
																	title="Manter o estoque do produto: <?php echo "#" . $exrow->nome ?>"
																	acao="manterEstoque">
																	<i class="fa fa-retweet"></i>
																</a>
															</td>
														</tr>
													<?php endforeach; ?>
													<?php unset($exrow);
												endif; ?>
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
		<?php break; ?>
<?php endswitch; ?>