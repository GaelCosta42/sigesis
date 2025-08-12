<?php

/**
 * Tipo de pagamento
 *
 */
if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe nao e permitido.');
if (!$usuario->is_Gerencia())
	redirect_to("login.php");
?>
<?php switch (Filter::$acao):
	case "editar": ?>
		<?php $row = Core::getRowById("tipo_pagamento", Filter::$id); ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('TIPO_PAGAMENTO'); ?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FINANCEIRO_PAGAMENTOS_EDITAR'); ?></small></h1>
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
										<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_PAGAMENTOS_EDITAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"><?php echo lang('TIPO_PAGAMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="tipo" value="<?php echo $row->tipo; ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label class='control-label col-md-3'><?php echo lang('CATEGORIA_PAGAMENTO'); ?></label>
																<div class='col-md-9'>
																	<select class='select2me form-control' name='id_categoria_pagamento' data-placeholder='<?php echo lang('SELECIONE_CATEGORIA_PAGAMENTO'); ?>'>
																		<option value=""></option>
																		<?php
																		$retorno_row = $faturamento->getCategoriaTipoPagamento();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																		?>
																				<option value='<?php echo $srow->id; ?>' <?php if ($srow->id == $row->id_categoria) echo 'selected="selected"'; ?>><?php echo $srow->categoria; ?></option>
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
																<label class="control-label col-md-3"><?php echo lang('TAXA'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">%</span>
																		<input type="text" class="form-control decimal" name="taxa" value="<?php echo decimal($row->taxa); ?>">
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"><?php echo lang('DIAS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro" name="dias" value="<?php echo $row->dias; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"><?php echo lang('NUMERO_PARCELAS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro" name="parcelas" value="<?php echo $row->parcelas; ?>">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label class='control-label col-md-3'><?php echo lang('BANCO'); ?></label>
																<div class='col-md-9'>
																	<select class='select2me form-control' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO'); ?>'>
																		<option value=""></option>
																		<?php
																		$retorno_row = $faturamento->getBancos();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																		?>
																				<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $row->id_banco) echo 'selected="selected"'; ?>><?php echo $srow->banco; ?></option>
																		<?php
																			endforeach;
																			unset($srow);
																		endif;
																		?>
																	</select>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox">
																				<input type="checkbox" class="md-check" name="exibir_nfe" id="exibir_nfe" value="1" <?php if ($row->exibir_nfe) echo 'checked'; ?>>
																				<label for="exibir_nfe">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_PAGAMENTO_NFE'); ?></label>
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
																				<input type="checkbox" class="md-check" name="exibir_crediario" id="exibir_crediario" value="1" <?php if ($row->exibir_crediario) echo 'checked'; ?>>
																				<label for="exibir_crediario">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_PAGAMENTO_CREDIARIO'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox">
																				<input type="checkbox" class="md-check" name="avista" id="avista" value="1" <?php if ($row->avista) echo 'checked'; ?>>
																				<label for="avista">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_AVISTA'); ?></label>
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
																				<input type="checkbox" class="md-check" name="primeiro_vencimento" id="primeiro_vencimento" value="1" <?php if ($row->primeiro_vencimento) echo 'checked'; ?>>
																				<label for="primeiro_vencimento">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PRIMEIRO_VENCIMENTO'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<input name="id" type="hidden" value="<?php echo Filter::$id; ?>" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<button type="submit" class="btn <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
																<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR'); ?></button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm("processarTipoPagamento"); ?>
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
						<h1><?php echo lang('TIPO_PAGAMENTO'); ?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FINANCEIRO_PAGAMENTOS_ADICIONAR'); ?></small></h1>
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
										<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_PAGAMENTOS_ADICIONAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-12">
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"><?php echo lang('TIPO_PAGAMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="tipo">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label class='control-label col-md-3'><?php echo lang('CATEGORIA_PAGAMENTO'); ?></label>
																<div class='col-md-9'>
																	<select class='select2me form-control' name='id_categoria_pagamento' data-placeholder='<?php echo lang('SELECIONE_CATEGORIA_PAGAMENTO'); ?>'>
																		<option value=""></option>
																		<?php
																		$retorno_row = $faturamento->getCategoriaTipoPagamento();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																		?>
																				<option value='<?php echo $srow->id; ?>'><?php echo $srow->categoria; ?></option>
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
																<label class="control-label col-md-3"><?php echo lang('TAXA'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">%</span>
																		<input type="text" class="form-control decimal" name="taxa">
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"><?php echo lang('DIAS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro" name="dias">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-3"><?php echo lang('NUMERO_PARCELAS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control inteiro" name="parcelas">
																</div>
															</div>
														</div>
														<div class='row'>
															<div class='form-group'>
																<label class='control-label col-md-3'><?php echo lang('BANCO'); ?></label>
																<div class='col-md-9'>
																	<select class='select2me form-control' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO'); ?>'>
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
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox">
																				<input type="checkbox" class="md-check" name="exibir_nfe" id="exibir_nfe" value="1">
																				<label for="exibir_nfe">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_PAGAMENTO_NFE'); ?></label>
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
																				<input type="checkbox" class="md-check" name="exibir_crediario" id="exibir_crediario" value="1">
																				<label for="exibir_crediario">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_PAGAMENTO_CREDIARIO'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox">
																				<input type="checkbox" class="md-check" name="avista" id="avista" value="1">
																				<label for="avista">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_AVISTA'); ?></label>
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
																				<input type="checkbox" class="md-check" name="primeiro_vencimento" id="primeiro_vencimento" value="1">
																				<label for="primeiro_vencimento">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PRIMEIRO_VENCIMENTO'); ?></label>
																			</div>
																		</div>
																	</div>
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
																<button type="submit" class="btn <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
																<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR'); ?></button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm("processarTipoPagamento"); ?>
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
	case "listar": ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('TIPO_PAGAMENTO'); ?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FINANCEIRO_PAGAMENTOS_LISTAR'); ?></small></h1>
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
										<span class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('FINANCEIRO_PAGAMENTOS_LISTAR'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="index.php?do=tipopagamento&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor; ?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR'); ?></a>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
												<th><?php echo lang('CATEGORIA_PAGAMENTO'); ?></th>
												<th><?php echo lang('TAXA'); ?></th>
												<th><?php echo lang('DIAS'); ?></th>
												<th width="60px"><?php echo lang('NUMERO_PARCELAS'); ?></th>
												<th><?php echo lang('BANCO'); ?></th>
												<th width="60px"><?php echo lang('TIPO_PAGAMENTO_NFE_TITULO'); ?></th>
												<th width="90px"><?php echo lang('TIPO_PAGAMENTO_CREDIARIO_TITULO'); ?></th>
												<th width="75px"><?php echo lang('TIPO_AVISTA'); ?></th>
												<th width="80px"><?php echo lang('ACOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $faturamento->getTipoPagamento();
											if ($retorno_row):
												foreach ($retorno_row as $exrow):

													$exibirNFE = ($exrow->exibir_nfe) ?
														'<span class="label label-success">' . lang('SIM') . '</span>'
														: '<span class="label label-warning">' . lang('NAO') . '</span>';

													$exibirCrediario = ($exrow->exibir_crediario) ?
														'<span class="label label-success">' . lang('SIM') . '</span>'
														: '<span class="label label-warning">' . lang('NAO') . '</span>';

													$pagamentoAVista = ($exrow->avista) ?
														'<span class="label label-success">' . lang('SIM') . '</span>'
														: '<span class="label label-warning">' . lang('NAO') . '</span>';
											?>
													<tr>
														<td><?= $exrow->tipo; ?></td>
														<td><?= $exrow->categoria; ?></td>
														<td><?= decimal($exrow->taxa) . ' %'; ?></td>
														<td><?= $exrow->dias; ?></td>
														<td width="60px"><?= $exrow->parcelas; ?></td>
														<td><?= $exrow->banco; ?></td>
														<td width="60px"><?= $exibirNFE; ?></td>
														<td width="90px"><?= $exibirCrediario; ?></td>
														<td width="75px"><?= $pagamentoAVista; ?></td>
														<td width="80px">
															<a href="index.php?do=tipopagamento&acao=editar&id=<?php echo $exrow->id; ?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR') . ': ' . $exrow->tipo; ?>"><i class="fa fa-pencil"></i></a>
															<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id; ?>" acao="apagarTipoPagamento" title="<?php echo lang('APAGAR') . ": " . $exrow->tipo; ?>"><i class="fa fa-times"></i></a>
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
	default: ?>
		<div class="imagem-fundo">
			<img src="assets/img/logo.png" border="0">
		</div>
		<?php break; ?>
<?php endswitch; ?>