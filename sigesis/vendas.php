<?php

/**
 * Vendas - Vendas e PDV
 *
 */
if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe não é permitido.');
if (!$usuario->is_Todos())
	redirect_to("login.php");
?>
<?php switch (Filter::$acao):
	case "_cancelado_vendasdia": ?>
		<?php
		if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif;
		$data = (get('data')) ? get('data') : date("d/m/Y");
		?>
		<script type="text/javascript">
			$(document).ready(function () {
				$('#selecionardata').click(function () {
					var data = $("#data").val();
					window.location.href = 'index.php?do=vendas&acao=vendasdia&data=' + data;
				});
			});
		</script>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_DO_DIA'); ?></small></h1>
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
							<div class="portlet light">
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<?php echo lang('SELECIONE_DATA'); ?>
											&nbsp;&nbsp;&nbsp;
											<input type="text" class="form-control input-medium calendario data" name="data"
												id="data" value="<?php echo $data; ?>">
											&nbsp;
											<button type="button" id="selecionardata"
												class="btn <?php echo $core->primeira_cor; ?>"><i class="fa fa-search" /></i>
												<?php echo lang('BUSCAR'); ?></button>
										</div>
									</form>
								</div>
							</div>
							<!-- INICIO TABELA -->
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-shopping-car font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_DO_DIA'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="javascript:void(0);" class="btn btn-sm <?php echo $core->primeira_cor; ?>"
											onclick="javascript:void window.open('imprimir_vendasdia.php?data=<?php echo $data; ?>','<?php echo lang('VENDAS_DO_DIA'); ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i
												class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR'); ?></a>
									</div>
								</div>
								<div class="portlet-body">
									<div>
										<h4>
											<i class="fa fa-shopping-cart font-<?php echo $core->primeira_cor; ?>"></i>
											<span
												class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_TITULO'); ?></span>
										</h4>
									</div>
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable-desc">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('COD_VENDA'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('DESCONTO'); ?></th>
												<th><?php echo lang('VALOR_TOTAL'); ?></th>
												<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
												<th><?php echo lang('VENDEDOR'); ?></th>
												<th><?php echo lang('CANCELADA'); ?></th>
												<th><?php echo lang('STATUS_NFC'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
												<th><?php echo lang('MOTIVO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $cadastro->getVendasDia($data);
											$estilo_status = "";
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$pgto_crediario = 0;
													$estilo_status = ($exrow->status_enotas == "Autorizada") ? ((!$exrow->contingencia) ? "badge bg-green" : "badge bg-blue-chambray") : (($exrow->status_enotas == "Negada") ? "badge bg-red" : (($exrow->status_enotas == "Inutilizada" || $exrow->status_enotas == "Cancelada") ? "badge bg-blue-hoki" : "badge bg-yellow"));
													$row_tipopagamento = $cadastro->getPagamentosVenda($exrow->id);
													$total += ($exrow->inativo) ? 0 : ($exrow->valor_pago - $exrow->troco);
													$cor_fiscal = ($exrow->fiscal && $exrow->status_enotas == "Autorizada") ? 'green' : 'purple';
													$pagamentoCrediario = 0;
													?>
													<tr <?php echo ($exrow->inativo) ? "class='font-red'" : ""; ?>>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo $exrow->id; ?></td>
														<td><a
																href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
														</td>
														<td>
															<?php
															echo ($exrow->valor_troca > 0)
																? moeda($exrow->valor_desconto - $exrow->valor_troca + $exrow->voucher_crediario) . ' +(' . lang('VALOR_TROCA') . MOEDA($exrow->valor_troca) . ') (' . lang('SALDO_CREDIARIO') . ': ' . moeda($exrow->voucher_crediario) . ')'
																: moeda($exrow->valor_desconto);
															?>
														</td>
														<td width="80px"><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago - $exrow->troco); ?></span>
														</td>
														<td>
															<?php
															if ($row_tipopagamento):
																foreach ($row_tipopagamento as $prow):
																	echo pagamento($prow->pagamento);
																	$pgto_crediario = ($prow->pagamento == NULL) ? 1 : $pgto_crediario;
																	$pagamentoCrediario = ($prow->id_categoria == 9) ? $pagamentoCrediario + 1 : $pagamentoCrediario;
																	?><br />
																<?php endforeach;
															endif; ?>
														</td>
														<td><?php echo $exrow->vendedor; ?></td>
														<td><?php echo ($exrow->inativo) ? "SIM" : "NAO"; ?></td>
														<td>
															<?php if ($exrow->id_venda > 0): ?>
																<?php if (((!empty($exrow->status_enotas_nf) && $exrow->status_enotas_nf != "") || $exrow->fiscal_nf == 0)):
																	$estilo_status = ($exrow->status_enotas_nf == "Autorizada") ? ((!$exrow->contingencia_nf) ? "badge bg-green" : "badge bg-blue-chambray") : (($exrow->status_enotas_nf == "Negada") ? "badge bg-red" : (($exrow->status_enotas_nf == "Inutilizada" || $exrow->status_enotas_nf == "Cancelada") ? "badge bg-blue-hoki" : "badge bg-yellow"));
																	?>
																	<div class="<?php echo $estilo_status; ?>">
																		<?php echo ($exrow->status_enotas_nf == "Autorizada") ?
																			((!$exrow->contingencia_nf) ? $exrow->status_enotas_nf : lang('NOTA_FISCAL_CONSUMIDOR_CONTIGENCIA')) :
																			(($exrow->status_enotas_nf == "Negada" || $exrow->status_enotas_nf == "Inutilizada" || $exrow->status_enotas_nf == "Cancelada") ? $exrow->status_enotas_nf : (lang('NOTA_FISCAL_CONSUMIDOR_PENDENTE'))); ?>
																	</div>
																<?php endif; ?>
															<?php else: ?>
																<?php if (((!empty($exrow->status_enotas) && $exrow->status_enotas != "") || $exrow->fiscal == 0)): ?>
																	<div class="<?php echo $estilo_status; ?>">
																		<?php echo ($exrow->status_enotas == "Autorizada") ?
																			((!$exrow->contingencia) ? $exrow->status_enotas : lang('NOTA_FISCAL_CONSUMIDOR_CONTIGENCIA')) :
																			(($exrow->status_enotas == "Negada" || $exrow->status_enotas == "Inutilizada" || $exrow->status_enotas == "Cancelada") ? $exrow->status_enotas : (lang('NOTA_FISCAL_CONSUMIDOR_PENDENTE'))); ?>
																	</div>
																<?php endif; ?>
															<?php endif; ?>
														</td>
														<td width="80px">
															<?php echo $exrow->id_venda > 0 ? $exrow->id_nf : $exrow->numero_nota; ?>
														</td>
														<td width="180px">
															<?php echo $exrow->id_venda > 0 ? $exrow->motivo_status_nf : $exrow->motivo_status; ?>
														</td>
														<td width="150px">
															<?php if (!$exrow->inativo): ?>
																<a href="javascript:void(0);"
																	onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																	title="<?php echo lang('VER_DETALHES'); ?>"
																	class="btn btn-sm grey-cascade btn-fiscal"><i
																		class="fa fa-search"></i></a>
																<?php if (!$exrow->fiscal || $exrow->status_enotas == "Inutilizada"): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('recibo.php?id=<?php echo $exrow->id; ?>&crediario=<?php echo $pgto_crediario; ?>','<?php echo lang('IMPRIMIR_RECIBO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																		title="<?php echo lang('IMPRIMIR_RECIBO'); ?>"
																		class="btn btn-sm yellow-casablanca btn-fiscal"><i
																			class="fa fa-file-o"></i></a>
																<?php endif; ?>
																<?php if (($usuario->is_nfc() && $core->tipo_sistema != 2) && (!$exrow->id_nota_fiscal) && $exrow->status_enotas != "Inutilizada"): ?>
																	<?php if ($exrow->status_enotas == "Autorizada" && !$exrow->contingencia): ?>
																		<a href="<?php echo $exrow->link_danfe; ?>"
																			title="<?php echo lang('NOTA_FISCAL_DANFE'); ?>"
																			class="btn btn-sm green"><i class="fa fa-file-text-o"></i></a>
																		<?php
																		$dentroPrazoCancelamento = !(strtotime($exrow->data_emissao . ' +30 minutes') < strtotime(date('Y-m-d H:i:s')));
																		if ($dentroPrazoCancelamento):
																			?>
																			<a href="index.php?do=vendas&acao=cancelarvendafiscal&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm red btn-fiscal"
																				title="<?php echo lang('CADASTRO_APAGAR_VENDA_FISCAL') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-minus-circle"></i></a>
																			<?php
																		endif;
																		?>
																	<?php else: ?>
																		<?php if (!$exrow->cadastro): ?>
																			<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>&pg=1"
																				class="btn btn-sm blue popovers btn-fiscal" data-container="body"
																				data-trigger="hover" data-placement="top"
																				data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																				data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																				title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-user"></i></a>
																		<?php endif; ?>
																		<?php if ($exrow->cadastro || $exrow->valor_total < 10000.00): ?>
																			<?php if (!$exrow->contingencia && $exrow->status_enotas != "Negada"): ?>
																				<a href="javascript:void(0);"
																					onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																					title="<?php echo lang('FISCAL_NFC'); ?>"
																					class="btn btn-sm btn-fiscal <?php echo $cor_fiscal; ?>"><i
																						class="fa fa-file-text-o"></i></a>
																				<?php if ($usuario->is_Controller()): ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC') . " [DEBUG]"; ?>"
																						class="btn btn-sm btn-fiscal <?php echo $cor_fiscal; ?>"><i
																							class="fa fa-bug"></i></a>
																				<?php endif; ?>
																			<?php else: ?>
																				<?php if ($exrow->status_enotas == "Negada"): ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>"
																						class="btn btn-sm red btn-fiscal"><i class="fa fa-file-text-o"></i></a>
																					<?php if ($usuario->is_Controller()): ?>
																						<a href="javascript:void(0);"
																							onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																							title="<?php echo lang('FISCAL_NFC_REPROCESSAR') . " [DEBUG]"; ?>"
																							class="btn btn-sm red btn-fiscal"><i class="fa fa-bug"></i></a>
																					<?php endif; ?>
																				<?php else: ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>"
																						class="btn btn-sm blue-chambray btn-fiscal"><i
																							class="fa fa-file-text-o"></i></a>
																					<?php if ($usuario->is_Controller()): ?>
																						<a href="javascript:void(0);"
																							onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																							title="<?php echo lang('FISCAL_NFC_REPROCESSAR') . " [DEBUG]"; ?>"
																							class="btn btn-sm blue-chambray btn-fiscal"><i
																								class="fa fa-bug"></i></a>
																					<?php endif; ?>
																				<?php endif; ?>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php endif; ?>
																<?php elseif (!$exrow->cadastro): ?>
																	<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>&pg=1"
																		class="btn btn-sm blue popovers btn-fiscal" data-container="body"
																		data-trigger="hover" data-placement="top"
																		data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																		data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																		title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																			class="fa fa-user"></i></a>
																<?php endif; ?>
																<?php if ($exrow->entrega): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('pdf_romaneio.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																		title="<?php echo lang('VER_ROMANEIO'); ?>"
																		class="btn btn-sm yellow-gold"><i
																			class="fa fa-truck btn-fiscal"></i></a>
																<?php endif; ?>
																<?php
																if ($row_tipopagamento):
																	foreach ($row_tipopagamento as $tipoPagamento):
																		$categoria_pagamento = $tipoPagamento->id_categoria;
																		$modulo_boleto = getValue("modulo_emissao_boleto", "empresa", "id = " . $exrow->id_empresa);

																		if ($modulo_boleto == 1 && $categoria_pagamento == 4):
																			$banco_boleto = getValue("boleto_banco", "empresa", "id = " . $tipoPagamento->id_empresa);
																			?>
																			<a href="boleto_<?php echo $banco_boleto; ?>.php?todos=1&id_pagamento=<?php echo $tipoPagamento->id; ?>&id_empresa=<?php echo $tipoPagamento->id_empresa; ?>"
																				target="_blank" title="<?php echo lang('GERAR_TODOS'); ?>"
																				class="btn btn-sm grey-cascade btn-fiscal"><i
																					class="fa fa-bold"></i></a>
																			<?php
																		endif;
																	endforeach;
																endif;
																?>
																<?php if (!$exrow->fiscal && $usuario->is_Gerencia() && !$exrow->id_nota_fiscal || $exrow->status_enotas == "Inutilizada"): ?>
																	<a href="index.php?do=vendas&acao=cancelarvenda&id=<?php echo $exrow->id; ?>&pg=1"
																		class="btn btn-sm red btn-fiscal"
																		title="<?php echo lang('CADASTRO_APAGAR_VENDA') . ': ' . $exrow->id; ?>"><i
																			class="fa fa-ban"></i></a>
																<?php endif; ?>
																<?php if ($exrow->id_nota_fiscal):
																	if (isset($exrow->status_enotas_nf)) {
																		if ($exrow->status_enotas_nf === "Negada") {
																			$cor_status = 'red';
																		} else {
																			$cor_status = 'green';
																		}
																	} else {
																		$cor_status = 'purple';
																	}
																	?>
																	<a href="index.php?do=notafiscal&acao=visualizar&id=<?= $exrow->id_nota_fiscal; ?>"
																		class="btn btn-sm <?php echo $cor_status ?>" title="NF-e">NF-e</a>
																<?php endif; ?>
																<?php if (!$exrow->fiscal && !$exrow->id_nota_fiscal && $core->tipo_sistema != 2 && $core->tipo_sistema != 3): ?>
																	<?php if ($exrow->cadastro): ?>
																		<a href="javascript:void(0);"
																			class="btn btn-sm blue gerarNFEvenda btn-fiscal"
																			id="<?php echo $exrow->id; ?>"
																			title="<?php echo lang('NOTA_FISCAL_CONVERTER') . ': ' . $exrow->id; ?>"><i
																				class="fa fa-files-o"></i></a>
																	<?php else: ?>
																		<a href="javascript:void(0);"
																			class="btn btn-sm grey-cascade gerarNFEvendaBloqueio btn-fiscal"
																			title="<?php echo lang('NOTA_FISCAL_CONVERTER_NAO') . ': ' . $exrow->id; ?>"><i
																				class="fa fa-files-o"></i></a>
																	<?php endif; ?>
																<?php endif; ?>
																<?php if ($pagamentoCrediario && $pagamentoCrediario > 0): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('recibo_promissorias.php?id_venda=<?php echo $exrow->id; ?>&id_receita=0','<?php echo lang('IMPRIMIR_RECIBO_PROMISSORIAS') . ': ' . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																		title="<?php echo lang('IMPRIMIR_RECIBO_PROMISSORIAS'); ?>"
																		class="btn btn-sm yellow btn-fiscal"><i class="fa fa-list-alt"></i></a>
																<?php endif; ?>
															<?php elseif ($exrow->link_danfe): ?>
																<a href="<?php echo $exrow->link_danfe; ?>"
																	title="<?php echo lang('NOTA_FISCAL_DANFE'); ?>"
																	class="btn btn-sm <?php echo 'green'; ?>"><i
																		class="fa fa-file-pdf-o"></i></a>
															<?php endif; ?>
															<a href="javascript:void(0);" onClick="location.reload();"
																title="<?php echo lang('RECARREGAR'); ?>"
																class="btn btn-sm btn-reload blue-madison ocultar"><i
																	class="fa fa-refresh"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4"><span class="bold"><?php echo lang('TOTAL'); ?></td>
												<td><span class="bold"><?php echo moedap($total); ?></span></td>
												<td colspan="7"></td>
											</tr>
										</tfoot>
									</table>
									<div>
										<h4>
											<i class="fa fa-usd font-<?php echo $core->primeira_cor; ?>"></i>
											<span
												class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PAGAMENTOS'); ?></span>
										</h4>
									</div>
									<table class="table table-bordered table-striped table-condensed table-advance">
										<thead>
											<tr>
												<th><?php echo lang('PAGAMENTO'); ?></th>
												<th><?php echo lang('VALOR'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$pago = 0;
											$retorno_row = $cadastro->getFinanceiroDia($data);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$pago += $exrow->valor_pago;
													?>
													<tr>
														<td><?php echo pagamento($exrow->pagamento); ?></td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago); ?></span>
														</td>
													</tr>
												<?php endforeach; ?>
												<tr>
													<td><strong><?php echo lang('VALOR_TOTAL'); ?></strong></td>
													<td><strong><?php echo moedap($pago); ?></strong></td>
												</tr>
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
	case "vendasperiodo": ?>
		<?php if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif;
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=vendas&acao=vendasperiodo&dataini=' + dataini + '&datafim=' + datafim;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_PERIODO'); ?></small></h1>
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
							<div class="portlet light">
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
										</div>
										<div class="form-group">
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?>'
												id="buscar" title='<?php echo lang('BUSCAR'); ?>'><i
													class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
										</div>
									</form>
								</div>
							</div>
							<!-- INICIO TABELA -->
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-shopping-car font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_PERIODO'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable-desc">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('COD_VENDA'); ?></th>
												<th><?php echo lang('DATA_VENDA'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('DESCONTO'); ?></th>
												<th><?php echo lang('VALOR_TOTAL'); ?></th>
												<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
												<th><?php echo lang('CANCELADA'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
												<th><?php echo lang('MOTIVO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $cadastro->getVendasPeriodo($dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$pgto_crediario = 0;
													$row_pagamento = $cadastro->getFinanceiroVendaBoleto($exrow->id);
													$categoria_pagamento = ($row_pagamento) ? getValue("id_categoria", "tipo_pagamento", "id = " . $row_pagamento->tipo) : 0;
													$estilo_status = ($exrow->status_enotas == "Autorizada") ? ((!$exrow->contingencia) ? "badge bg-green" : "badge bg-blue-chambray") : (($exrow->status_enotas == "Negada") ? "badge bg-red" : (($exrow->status_enotas == "Inutilizada" || $exrow->status_enotas == "Cancelada") ? "badge bg-blue-hoki" : "badge bg-yellow"));
													$cor_fiscal = ($exrow->fiscal && $exrow->status_enotas == "Autorizada") ? 'green' : 'purple';
													if (!$exrow->inativo)
														$total += $exrow->valor_pago - $exrow->troco;
													?>
													<tr <?php echo ($exrow->inativo) ? "class='font-red'" : ""; ?>>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo exibedata($exrow->data_venda); ?></td>
														<td><a
																href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
														</td>
														<td>
															<?php
															// echo ($exrow->valor_troca > 0) ? moeda($exrow->valor_desconto-$exrow->valor_troca).' +('.lang('VALOR_TROCA').MOEDA($exrow->valor_troca).')' : moeda($exrow->valor_desconto);

															echo ($exrow->valor_troca > 0)
																? moeda($exrow->valor_desconto - $exrow->valor_troca + $exrow->voucher_crediario) . ' + (' . lang('VALOR_TROCA') . MOEDA($exrow->valor_troca) . ') (' . lang('SALDO_CREDIARIO') . ': ' . moeda($exrow->voucher_crediario) . ')'
																: moeda($exrow->valor_desconto);
															?>
														</td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago - $exrow->troco); ?></span>
														</td>
														<td>
															<?php
															$row_tipopagamento = $cadastro->getPagamentosVenda($exrow->id);
															if ($row_tipopagamento):
																foreach ($row_tipopagamento as $prow):
																	$pgto_crediario = ($prow->pagamento == NULL) ? 1 : $pgto_crediario;
																	?>
																	<?php echo ($prow->pagamento); ?><br />
																<?php endforeach;
															endif; ?>
														</td>
														<td><?php echo ($exrow->inativo) ? "SIM" : "NAO"; ?></td>
														<td><?php echo $exrow->usuario_venda; ?></td>
														<td><?php echo $exrow->numero_nota; ?></td>
														<td><?php echo $exrow->motivo_status; ?></td>
														<td width="150px">
															<?php if (!$exrow->inativo): ?>
																<a href="javascript:void(0);"
																	onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																	title="<?php echo lang('VER_DETALHES'); ?>"
																	class="btn btn-sm grey-cascade btn-fiscal"><i
																		class="fa fa-search"></i></a>
																<?php if (!$exrow->fiscal || $exrow->status_enotas == "Inutilizada"): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('recibo.php?id=<?php echo $exrow->id; ?>&crediario=<?php echo $pgto_crediario; ?>','<?php echo lang('IMPRIMIR_RECIBO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																		title="<?php echo lang('IMPRIMIR_RECIBO'); ?>"
																		class="btn btn-sm yellow-casablanca btn-fiscal"><i
																			class="fa fa-file-o"></i></a>
																<?php endif; ?>
																<?php if (($usuario->is_nfc() && $core->tipo_sistema != 2) && (!$exrow->id_nota_fiscal) && $exrow->status_enotas != "Inutilizada"): ?>
																	<?php if ($exrow->status_enotas == "Autorizada" && !$exrow->contingencia): ?>
																		<a href="<?php echo $exrow->link_danfe; ?>"
																			title="<?php echo lang('NOTA_FISCAL_DANFE'); ?>"
																			class="btn btn-sm green"><i class="fa fa-file-text-o"></i></a>
																		<?php
																		$dentroPrazoCancelamento = !(strtotime($exrow->data_emissao . ' +30 minutes') < strtotime(date('Y-m-d H:i:s')));
																		if ($dentroPrazoCancelamento):
																			?>
																			<a href="index.php?do=vendas&acao=cancelarvendafiscal&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm red btn-fiscal"
																				title="<?php echo lang('CADASTRO_APAGAR_VENDA_FISCAL') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-minus-circle"></i></a>
																			<?php
																		endif;
																		?>
																	<?php else: ?>
																		<?php if (!$exrow->cadastro): ?>
																			<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm blue popovers btn-fiscal" data-container="body"
																				data-trigger="hover" data-placement="top"
																				data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																				data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																				title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-user"></i></a>
																		<?php endif; ?>
																		<?php if ($exrow->cadastro || $exrow->valor_total < 10000.00): ?>
																			<?php if (!$exrow->contingencia && $exrow->status_enotas != "Negada"): ?>
																				<a href="javascript:void(0);"
																					onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																					title="<?php echo lang('FISCAL_NFC'); ?>"
																					class="btn btn-sm btn-fiscal <?php echo $cor_fiscal; ?>"><i
																						class="fa fa-file-text-o"></i></a>
																				<?php if ($usuario->is_Controller()): ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC') . " [DEBUG]"; ?>"
																						class="btn btn-sm btn-fiscal <?php echo $cor_fiscal; ?>"><i
																							class="fa fa-bug"></i></a>
																				<?php endif; ?>
																			<?php else: ?>
																				<?php if ($exrow->status_enotas == "Negada"): ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>"
																						class="btn btn-sm red btn-fiscal"><i class="fa fa-file-text-o"></i></a>
																					<?php if ($usuario->is_Controller()): ?>
																						<a href="javascript:void(0);"
																							onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																							title="<?php echo lang('FISCAL_NFC_REPROCESSAR') . " [DEBUG]"; ?>"
																							class="btn btn-sm red btn-fiscal"><i class="fa fa-bug"></i></a>
																					<?php endif; ?>
																				<?php else: ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>"
																						class="btn btn-sm blue-chambray btn-fiscal"><i
																							class="fa fa-file-text-o"></i></a>
																					<?php if ($usuario->is_Controller()): ?>
																						<a href="javascript:void(0);"
																							onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																							title="<?php echo lang('FISCAL_NFC_REPROCESSAR') . " [DEBUG]"; ?>"
																							class="btn btn-sm blue-chambray btn-fiscal"><i
																								class="fa fa-bug"></i></a>
																					<?php endif; ?>
																				<?php endif; ?>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php endif; ?>
																<?php elseif (!$exrow->cadastro): ?>
																	<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>"
																		class="btn btn-sm blue popovers btn-fiscal" data-container="body"
																		data-trigger="hover" data-placement="top"
																		data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																		data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																		title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																			class="fa fa-user"></i></a>
																<?php endif; ?>

																<?php if ($exrow->entrega): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('pdf_romaneio.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																		title="<?php echo lang('VER_ROMANEIO'); ?>"
																		class="btn btn-sm yellow-gold"><i
																			class="fa fa-truck btn-fiscal"></i></a>
																<?php endif; ?>

																<?php $modulo_boleto = getValue("modulo_emissao_boleto", "empresa", "id = " . $exrow->id_empresa);
																	if ($modulo_boleto == 1 && $categoria_pagamento == 4):
																	$banco_boleto = getValue("boleto_banco", "empresa", "id = " . $row_pagamento->id_empresa);
																	?>
																	<a href="boleto_<?php echo $banco_boleto; ?>.php?todos=1&id_pagamento=<?php echo $row_pagamento->id; ?>&id_empresa=<?php echo $row_pagamento->id_empresa; ?>"
																		target="_blank" title="<?php echo lang('GERAR_TODOS'); ?>"
																		class="btn btn-sm grey-cascade btn-fiscal"><i
																			class="fa fa-bold"></i>
																		</a>
																<?php endif; ?>
																<?php if (!$exrow->fiscal && $usuario->is_Gerencia() && !$exrow->id_nota_fiscal || $exrow->status_enotas == "Inutilizada"): ?>
																	<a href="index.php?do=vendas&acao=cancelarvenda&id=<?php echo $exrow->id; ?>&pg=2"
																		class="btn btn-sm red btn-fiscal"
																		title="<?php echo lang('CADASTRO_APAGAR_VENDA') . ': ' . $exrow->id; ?>"><i
																			class="fa fa-ban"></i></a>
																<?php endif; ?>
															<?php elseif ($exrow->link_danfe): ?>
																<a href="<?php echo $exrow->link_danfe; ?>"
																	title="<?php echo lang('NOTA_FISCAL_DANFE'); ?>"
																	class="btn btn-sm <?php echo 'green'; ?>"><i
																		class="fa fa-file-pdf-o"></i></a>
															<?php endif; ?>
															<a href="javascript:void(0);" onClick="location.reload();"
																title="<?php echo lang('RECARREGAR'); ?>"
																class="btn btn-sm btn-reload blue-madison ocultar"><i
																	class="fa fa-refresh"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="5"><strong><?php echo lang('TOTAL'); ?></strong></td>
												<td><strong><?php echo moedap($total); ?></strong></td>
												<td colspan="6"></td>
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
		<?php break; ?>
	<?php
	case "vendas_fiscal_lote": ?>
		<?php if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif;
		$id_unico = $_SESSION['id_unico'] . '_' . $_SESSION['uid'] . '_' . date("YmdHis");
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		$categorias = (get('cp')) ? get('cp') : '';
		$id_cliente_selecionado = (get('c')) ? (int)get('c')  : '0';
		$cliente_selecionado = (get('c')) ? getValue('nome', 'cadastro', 'id='.$id_cliente_selecionado)  : '';
		$categorias_selecionadas = explode(',',$categorias);
		$categorias_pagamento = $faturamento->getCategoriaTipoPagamento();
		$vendas_emissao = $faturamento->getVendasEmissao($dataini,$datafim,$categorias_selecionadas,$id_cliente_selecionado);
		$categorias_titulo = "";
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					
					// Pega todos os checkboxes com a classe .categoria_pagamento que estão marcados
					const checkboxes = document.querySelectorAll('.categoria_pagamento:checked');
					// Cria um array com os valores (id das categorias)
					const selecionados = Array.from(checkboxes).map(cb => cb.value);
					// Exemplo: mostrar em um alert
					//alert("Categorias selecionadas: " + selecionados.join(", "));

					const c = document.getElementById("cliente").value;

					window.location.href = 'index.php?do=vendas&acao=vendas_fiscal_lote&dataini=' + dataini + '&datafim=' + datafim + '&cp=' + selecionados + '&c=' + c;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FISCAL_CONTROLE_LISTAR'); ?></small></h1>
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
							<div class="portlet light">
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<h4>Filtro de vendas para emissão fiscal em lote</h4>
										<br>
										<div class="form-group">
											vendas de
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											até	
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
										</div>
										
										<div class="form-group">
											<?php foreach($categorias_pagamento as $categoria): 
													if ($categoria->id==9) 
														continue;
													if (in_array($categoria->id,$categorias_selecionadas))
														$categorias_titulo .= ($categorias_titulo=="") ? $categoria->categoria : ", ".$categoria->categoria;
											?>
												<label class="control-label col-md-1"></label>
												<div class="col-md-2">
													<div class="md-checkbox-list">
														<div class="md-checkbox col-md-12">
															<input type="checkbox" class="md-check categoria_pagamento" name="categoria_pagamento[]"
																id="<?php echo $categoria->id; ?>" value="<?php echo $categoria->id; ?>" <?php if (in_array($categoria->id,$categorias_selecionadas))
																	echo 'checked'; ?>>
															<label for="<?php echo $categoria->id; ?>">
																<span></span>
																<span class="check"></span>
																<span class="box"></span>
																<?php echo $categoria->categoria; ?></label>
														</div>
													</div>
												</div>
											<?php endforeach; ?>
										</div>

										<div class="form-group">
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CLIENTE'); ?></label>
													<div class="col-md-9">
														<select class="select2me form-control" name="cliente" id="cliente" data-placeholder="<?php echo lang('SELECIONE_CLIENTE'); ?>">
															<option value=""></option>
															<?php
																$retorno_row = $cadastro->getCadastros('CLIENTE');
																if ($retorno_row):
																	foreach ($retorno_row as $crow):
															?>
																		<option value="<?php echo $crow->id; ?>" <?php if ($id_cliente_selecionado==$crow->id) echo 'selected="selected"'; ?>>
																			<?php echo $crow->nome; ?>
																		</option>
															<?php
																	endforeach;
																	unset($crow);
																endif;
															?>
														</select>
													</div>
												</div>
											</div>
										</div>

										<br><br>

										<div class="form-group">
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?>'
												id="buscar" title='Filtrar vendas'><i
													class='fa fa-search'>&nbsp;&nbsp;</i><?php echo 'Filtrar vendas'; ?></a>
										</div>

										<br><br>

										<div class="note note-info">
											<h4 class="block">Filtros aplicados:</h4>
											<p><?php
												echo "Data de $dataini até $datafim<br>";
												echo "Categorias de Tipos de pagamentos: ".(($categorias_titulo) ? $categorias_titulo : "(todos os tipos de pagamento)")."<br>";
												echo "Cliente: ".(($cliente_selecionado) ? $cliente_selecionado : "(todos os clientes)");
											?></p>
										</div>

									</form>
								</div>
							</div>
							<!-- INICIO TABELA -->
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-shopping-car font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('FISCAL_CONTROLE_LISTAR'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
										<div class="dashboard-stat sigesis-cor-1">
											<div class="visual">
												<i class="fa fa-shopping-cart"></i>
											</div>
											<div class="details">
												<div class="number"><?php echo $vendas_emissao->quantidade; ?></div>
												<div class="desc">Vendas selecionadas</div>
											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
										<div class="dashboard-stat sigesis-cor-1">
											<div class="visual">
												<i class="fa fa-money"></i>
											</div>
											<div class="details">
												<div class="number"><?php echo moeda($vendas_emissao->valor_vendas); ?></div>
												<div class="desc">Valor selecionado</div>
											</div>
										</div>
									</div>
									<?php if ($vendas_emissao->quantidade>0): ?>
										<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
											<div class="dashboard-stat sigesis-cor-1">
												<div class="visual">
													<i class="fa fa-file-o"></i>
												</div>
												<div class="details">
													<div class="number">
														<a href='javascript:void(0);' class='btn purple converter_vendas'
															id="converter_vendas" 
															quantidade='<?php echo $vendas_emissao->quantidade; ?>' 
															valor='<?php echo moeda($vendas_emissao->valor_vendas); ?>' 
															datai='<?php echo $dataini; ?>' 
															dataf='<?php echo $datafim; ?>' 
															cat='<?php echo $categorias; ?>' 
															cli='<?php echo $id_cliente_selecionado; ?>' 
															id_unico='<?php echo $id_unico; ?>' 
															title='Converter Vendas'>
															<i class='fa fa-copy'></i>
															&nbsp;&nbsp;
															<i class='fa fa-angle-double-right'></i>
															&nbsp;&nbsp;
															<i class='fa fa-file-o'></i>
															&nbsp;&nbsp;<?php echo 'Agrupar Vendas'; ?>
														</a>
													</div>
												</div>
											</div>
										</div>
									<?php endif; ?>



									<br><br><br><br><br><br><br><br><br>
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable-desc">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('COD_VENDA'); ?></th>
												<th><?php echo lang('DATA_VENDA'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('DESCONTO'); ?></th>
												<th><?php echo lang('VALOR_TOTAL'); ?></th>
												<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
												<th><?php echo lang('CANCELADA'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
												<th><?php echo lang('MOTIVO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $cadastro->getVendasEmissaoLote();
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$pgto_crediario = 0;
													$cor_fiscal = ($exrow->fiscal && $exrow->status_enotas == "Autorizada") ? 'green' : 'purple';
													if (!$exrow->inativo)
														$total += $exrow->valor_pago - $exrow->troco;
													?>
													<tr <?php echo ($exrow->inativo) ? "class='font-red'" : ""; ?>>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo exibedata($exrow->data_venda); ?></td>
														<td><?php echo ($exrow->cadastro) ? $exrow->cadastro : "(não registrado)"; ?></td>
														<td>
															<?php
															echo ($exrow->valor_troca > 0)
																? moeda($exrow->valor_desconto - $exrow->valor_troca + $exrow->voucher_crediario) . ' + (' . lang('VALOR_TROCA') . MOEDA($exrow->valor_troca) . ') (' . lang('SALDO_CREDIARIO') . ': ' . moeda($exrow->voucher_crediario) . ')'
																: moeda($exrow->valor_desconto);
															?>
														</td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago - $exrow->troco); ?></span>
														</td>
														<td>
															<?php
															$row_tipopagamento = $cadastro->getPagamentosVenda($exrow->id);
															if ($row_tipopagamento):
																foreach ($row_tipopagamento as $prow):
																	$pgto_crediario = ($prow->pagamento == NULL) ? 1 : $pgto_crediario;
																	?>
																	<?php echo ($prow->pagamento); ?><br />
																<?php endforeach;
															endif; ?>
														</td>
														<td><?php echo ($exrow->inativo) ? "SIM" : "NAO"; ?></td>
														<td><?php echo $exrow->usuario_venda; ?></td>
														<td><?php echo $exrow->numero_nota; ?></td>
														<td><?php echo $exrow->motivo_status; ?></td>
														<td width="150px">
															<?php if (!$exrow->inativo): ?>
																	<a href="javascript:void(0);" onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id; ?>',
																		'<?php echo lang('CODIGO') . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																		title="<?php echo lang('VER_DETALHES'); ?>"	class="btn btn-sm grey-cascade btn-fiscal"><i class="fa fa-search"></i>
																	</a>

																	<?php if (!$exrow->fiscal || $exrow->status_enotas == "Inutilizada"): ?>
																			<a href="javascript:void(0);" onclick="javascript:void window.open('recibo.php?id=<?php echo $exrow->id; ?>&crediario=<?php echo $pgto_crediario; ?>',
																				'<?php echo lang('IMPRIMIR_RECIBO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" 
																				title="<?php echo lang('IMPRIMIR_RECIBO'); ?>" class="btn btn-sm yellow-casablanca btn-fiscal"><i class="fa fa-file-o"></i>
																			</a>
																			<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_a4.php?id=<?php echo $exrow->id; ?>&crediario=<?php echo $pgto_crediario; ?>',
																				'<?php echo lang('IMPRIMIR_RECIBO_A4') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																				title="<?php echo lang('IMPRIMIR_RECIBO_A4'); ?>" class="btn btn-sm yellow-casablanca btn-fiscal">A4</a>
																	<?php endif; ?>

																	<?php 
																	if (($usuario->is_nfc() && $core->tipo_sistema != 2) && (!$exrow->id_nota_fiscal) && $exrow->status_enotas != "Inutilizada"): 
																	?>
																	<?php 
																		if ($exrow->status_enotas == "Autorizada" && !$exrow->contingencia): 
																	?>
																			<a href="<?php echo $exrow->link_danfe; ?>" title="<?php echo lang('NOTA_FISCAL_DANFE'); ?>" class="btn btn-sm green"><i class="fa fa-file-text-o"></i></a>
																	<?php		
																			$dentroPrazoCancelamento = !(strtotime($exrow->data_emissao . ' +30 minutes') < strtotime(date('Y-m-d H:i:s')));
																			if ($dentroPrazoCancelamento):
																	?>
																				<a href="index.php?do=vendas&acao=cancelarvendafiscal&id=<?php echo $exrow->id; ?>" class="btn btn-sm red btn-fiscal" title="<?php echo lang('CADASTRO_APAGAR_VENDA_FISCAL').': '.$exrow->id; ?>"><i class="fa fa-minus-circle"></i></a>
																	<?php 
																			endif; 
																	?>
																	<?php 
																		else:
																			if (!$exrow->contingencia && $exrow->status_enotas != "Negada"):
																	?>
																				<a href="javascript:void(0);" onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('FISCAL_NFC'); ?>" class="btn btn-sm btn-fiscal <?php echo $cor_fiscal; ?>"><i class="fa fa-file-text-o"></i></a>
																	<?php
																			else:
																				if ($value->status_enotas == "Negada"):
																	?>
																					<a href="javascript:void(0);" onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR').': '.$exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title=<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>" class="btn btn-sm red btn-fiscal"><i class="fa fa-file-text-o"></i></a>";
																	<?php													
																				else:
																	?>
																					<a href="javascript:void(0);" onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&id=<?php echo $exrow->id;?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>" class="btn btn-sm blue-chambray btn-fiscal"><i class="fa fa-file-text-o"></i></a>";
																	<?php
																				endif;
																			endif;
																		endif; 
																	?>
																	<?php 
																	endif; 
																	?>																
																	<?php 
																	if (!$exrow->fiscal && $usuario->is_Gerencia() && !$exrow->id_nota_fiscal || $exrow->status_enotas == "Inutilizada"): ?>
																		<a href="index.php?do=vendas&acao=cancelarvenda&id=<?php echo $exrow->id; ?>&pg=2"
																		class="btn btn-sm red btn-fiscal"
																		title="<?php echo lang('CADASTRO_APAGAR_VENDA') . ': ' . $exrow->id; ?>"><i
																		class="fa fa-ban"></i>
																		</a>
																	<?php 
																	endif; 
																	?>
																	<?php 
																	if ($exrow->cadastro):
																	?>
																		<a href="javascript:void(0);" class="btn btn-sm blue gerarNFEvenda btn-fiscal" id="<?php echo $exrow->id; ?>" title="<?php echo lang('NOTA_FISCAL_CONVERTER') . ': ' . $exrow->id; ?>"><i	class="fa fa-files-o"></i></a>
																	<?php 
																	endif;
																	?>
															<?php endif; ?>															
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4"><strong><?php echo lang('TOTAL'); ?></strong></td>
												<td><strong><?php echo moedap($total); ?></strong></td>
												<td colspan="6"></td>
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
	<?php break; ?>
	<?php case "vendas_emitidas_lote": ?>
		<?php if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif;
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();

					window.location.href = 'index.php?do=vendas&acao=vendas_emitidas_lote&dataini=' + dataini + '&datafim=' + datafim;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FISCAL_EMITIDAS_LISTAR'); ?></small></h1>
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
							<div class="portlet light">
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<h4><?php echo lang('FISCAL_EMITIDAS_FILTRO'); ?></h4>
										<br>
										<div class="form-group">
											vendas de
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											até	
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
										</div>
										<br><br>
										<div class="form-group">
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?>'
												id="buscar" title='Filtrar vendas'><i
													class='fa fa-search'>&nbsp;&nbsp;</i><?php echo 'Filtrar vendas'; ?></a>
										</div>
									</form>
								</div>
							</div>
							<!-- INICIO TABELA -->
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-shopping-car font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('FISCAL_EMITIDAS_LISTAR'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<br><br>
									<table
										class="table table-bordered table-striped table-condensed table-advance dataTable-desc">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('COD_VENDA'); ?></th>
												<th><?php echo lang('DATA_VENDA'); ?></th>
												<th><?php echo lang('DESCONTO'); ?></th>
												<th><?php echo lang('VALOR_TOTAL'); ?></th>
												<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
												<th><?php echo lang('CANCELADA'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
												<th><?php echo lang('MOTIVO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $cadastro->getVendasEmitidasLote($dataini,$datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$pgto_crediario = 0;
													$cor_fiscal = ($exrow->fiscal && $exrow->status_enotas == "Autorizada") ? 'green' : 'purple';
													if (!$exrow->inativo)
														$total += $exrow->valor_pago - $exrow->troco;
													?>
													<tr <?php echo ($exrow->inativo) ? "class='font-red'" : ""; ?>>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo exibedata($exrow->data_venda); ?></td>
														<td>
															<?php
															echo ($exrow->valor_troca > 0)
																? moeda($exrow->valor_desconto - $exrow->valor_troca + $exrow->voucher_crediario) . ' + (' . lang('VALOR_TROCA') . MOEDA($exrow->valor_troca) . ') (' . lang('SALDO_CREDIARIO') . ': ' . moeda($exrow->voucher_crediario) . ')'
																: moeda($exrow->valor_desconto);
															?>
														</td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago - $exrow->troco); ?></span>
														</td>
														<td>
															<?php
															$row_tipopagamento = $cadastro->getPagamentosVenda($exrow->id);
															if ($row_tipopagamento):
																foreach ($row_tipopagamento as $prow):
																	$pgto_crediario = ($prow->pagamento == NULL) ? 1 : $pgto_crediario;
																	?>
																	<?php echo ($prow->pagamento); ?><br />
																<?php endforeach;
															endif; ?>
														</td>
														<td><?php echo ($exrow->inativo) ? "SIM" : "NAO"; ?></td>
														<td><?php echo $exrow->usuario_venda; ?></td>
														<td><?php echo $exrow->numero_nota; ?></td>
														<td><?php echo $exrow->motivo_status; ?></td>
														<td width="150px">
															<?php if (!$exrow->inativo): ?>
																	<a href="javascript:void(0);" onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id; ?>',
																		'<?php echo lang('CODIGO') . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																		title="<?php echo lang('VER_DETALHES'); ?>"	class="btn btn-sm grey-cascade btn-fiscal"><i class="fa fa-search"></i>
																	</a>

																	<?php if (!$exrow->fiscal || $exrow->status_enotas == "Inutilizada"): ?>
																			<a href="javascript:void(0);" onclick="javascript:void window.open('recibo.php?id=<?php echo $exrow->id; ?>&crediario=<?php echo $pgto_crediario; ?>',
																				'<?php echo lang('IMPRIMIR_RECIBO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" 
																				title="<?php echo lang('IMPRIMIR_RECIBO'); ?>" class="btn btn-sm yellow-casablanca btn-fiscal"><i class="fa fa-file-o"></i>
																			</a>
																			<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_a4.php?id=<?php echo $exrow->id; ?>&crediario=<?php echo $pgto_crediario; ?>',
																				'<?php echo lang('IMPRIMIR_RECIBO_A4') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																				title="<?php echo lang('IMPRIMIR_RECIBO_A4'); ?>" class="btn btn-sm yellow-casablanca btn-fiscal">A4</a>
																	<?php endif; ?>

																	<?php 
																	if (($usuario->is_nfc() && $core->tipo_sistema != 2) && (!$exrow->id_nota_fiscal) && $exrow->status_enotas != "Inutilizada"): 
																	?>
																	<?php 
																		if ($exrow->status_enotas == "Autorizada" && !$exrow->contingencia): 
																	?>
																			<a href="<?php echo $exrow->link_danfe; ?>" title="<?php echo lang('NOTA_FISCAL_DANFE'); ?>" class="btn btn-sm green"><i class="fa fa-file-text-o"></i></a>
																	<?php		
																			$dentroPrazoCancelamento = !(strtotime($exrow->data_emissao . ' +30 minutes') < strtotime(date('Y-m-d H:i:s')));
																			if ($dentroPrazoCancelamento):
																	?>
																				<a href="index.php?do=vendas&acao=cancelarvendafiscal&id=<?php echo $exrow->id; ?>" class="btn btn-sm red btn-fiscal" title="<?php echo lang('CADASTRO_APAGAR_VENDA_FISCAL').': '.$exrow->id; ?>"><i class="fa fa-minus-circle"></i></a>
																	<?php 
																			endif; 
																	?>
																	<?php 
																		else:
																			if (!$exrow->contingencia && $exrow->status_enotas != "Negada"):
																	?>
																				<a href="javascript:void(0);" onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('FISCAL_NFC'); ?>" class="btn btn-sm btn-fiscal <?php echo $cor_fiscal; ?>"><i class="fa fa-file-text-o"></i></a>
																	<?php
																			else:
																				if ($exrow->status_enotas == "Negada"):
																	?>
																					<a href="javascript:void(0);" onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR').': '.$exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title=<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>" class="btn btn-sm red btn-fiscal"><i class="fa fa-file-text-o"></i></a>
																	<?php													
																				else:
																	?>
																					<a href="javascript:void(0);" onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&id=<?php echo $exrow->id;?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>" class="btn btn-sm blue-chambray btn-fiscal"><i class="fa fa-file-text-o"></i></a>
																	<?php
																				endif;
																			endif;
																		endif; 
																	?>
																	<?php 
																	endif; 
																	?>																
																	<?php 
																	if (!$exrow->fiscal && $usuario->is_Gerencia() && !$exrow->id_nota_fiscal || $exrow->status_enotas == "Inutilizada"): ?>
																		<a href="index.php?do=vendas&acao=cancelarvenda&id=<?php echo $exrow->id; ?>&pg=2" class="btn btn-sm red btn-fiscal" title="<?php echo lang('CADASTRO_APAGAR_VENDA') . ': ' . $exrow->id; ?>"><i class="fa fa-ban"></i></a>
																	<?php 
																	endif; 
																	?>
															<?php endif; ?>															
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4"><strong><?php echo lang('TOTAL'); ?></strong></td>
												<td><strong><?php echo moedap($total); ?></strong></td>
												<td colspan="6"></td>
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
	<?php break; ?>
	<?php
	case "vendasperiodofiscal": ?>
		<?php if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif;
		$tipo = get('tipo');
		$pagamento = ($tipo) ? getValue('tipo', 'tipo_pagamento', 'id=' . $tipo) : lang('TODAS');
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_CONSOLIDADAS_TIPO_FISCAL'); ?></small>
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
										<i class="fa fa-list-ol font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_CONSOLIDADAS_TIPO_FISCAL') . ': ' . $pagamento; ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('COD_VENDA'); ?></th>
												<th><?php echo lang('DATA_VENDA'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('VALOR_TOTAL'); ?></th>
												<th><?php echo lang('CANCELADA'); ?></th>
												<th><?php echo lang('STATUS_FISCAL'); ?></th>
												<th><?php echo lang('NUMERO_NOTA'); ?></th>
												<th><?php echo lang('MOTIVO'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $cadastro->getVendasPagamentoFiscal($tipo, $dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$total += ($exrow->inativo) ? 0 : $exrow->valor_pagamento;
													$categoria_pagamento = $exrow->id_categoria ?? 0;
													$estilo_status = ($exrow->status_enotas == "Autorizada" || ($exrow->nf_enotas == "Autorizada" && $exrow->status_enotas == "")) ? ((!$exrow->contingencia) ? "badge bg-green" : "badge bg-blue-chambray") : (($exrow->status_enotas == "Negada" || $exrow->nf_enotas == "Negada") ? "badge bg-red" : (($exrow->status_enotas == "Inutilizada" || $exrow->status_enotas == "Cancelada") ? "badge bg-blue-hoki" : "badge bg-yellow"));
													$cor_fiscal = ($exrow->fiscal && $exrow->status_enotas == "Autorizada") ? 'green' : 'purple';
													?>
													<tr <?php echo ($exrow->inativo) ? "class='font-red'" : ""; ?>>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo exibedata($exrow->data_venda); ?></td>
														<td><a
																href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
														</td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pagamento - $exrow->troco); ?></span>
														</td>
														<td><?php echo ($exrow->inativo) ? "SIM" : "NAO"; ?></td>
														<td>
															<?php if ((!empty($exrow->status_enotas) && $exrow->status_enotas != "") || $exrow->fiscal == 0): ?>
																<div class="<?php echo $estilo_status; ?>">
																	<?php echo ($exrow->status_enotas == "Autorizada" || ($exrow->nf_enotas == "Autorizada" && $exrow->status_enotas == "")) ? ((!$exrow->contingencia) ? ($exrow->nf_enotas == "Autorizada" && $exrow->status_enotas == "") ? "Autorizada" : $exrow->status_enotas : lang('NOTA_FISCAL_CONSUMIDOR_CONTIGENCIA')) : (($exrow->status_enotas == "Negada" || $exrow->nf_enotas == "Negada" || $exrow->status_enotas == "Inutilizada" || $exrow->status_enotas == "Cancelada") ? ($exrow->nf_enotas == "Negada") ? "Negada" : $exrow->status_enotas : (lang('NOTA_FISCAL_CONSUMIDOR_PENDENTE'))); ?>
																</div>
															<?php endif; ?>
														</td>
														<td>
															<?php if ($exrow->fiscal == 1): ?>
																<?php echo "NFC-e: $exrow->numero_nota"; ?>
															<?php endif; ?>
															<?php if ($exrow->id_nota_fiscal > 0): ?>
																<?php echo "NF-e: $exrow->nf_numero_nota"; ?>
															<?php endif; ?>
														</td>
														<td width="180px">
															<?php echo $exrow->motivo_status !== "" ? $exrow->motivo_status : $exrow->nf_motivo ?>
														</td>
														<td><?php echo $exrow->usuario_venda; ?></td>
														<td width="150px">
															<?php if (!$exrow->inativo): ?>
																<a href="javascript:void(0);"
																	onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO:" . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																	title="<?php echo lang('VER_DETALHES'); ?>"
																	class="btn btn-sm grey-cascade btn-fiscal"><i
																		class="fa fa-search"></i></a>
																<?php if (!$exrow->fiscal): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('recibo.php?id=<?php echo $exrow->id; ?>','<?php echo lang('IMPRIMIR_RECIBO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																		title="<?php echo lang('IMPRIMIR_RECIBO'); ?>"
																		class="btn btn-sm yellow-casablanca btn-fiscal"><i
																			class="fa fa-file-o"></i></a>
																<?php endif; ?>
																<?php if (($usuario->is_nfc() && $core->tipo_sistema != 2) && (!$exrow->id_nota_fiscal)): ?>
																	<?php if ($exrow->status_enotas == "Autorizada" && !$exrow->contingencia): ?>
																		<a href="<?php echo $exrow->link_danfe; ?>"
																			title="<?php echo lang('NOTA_FISCAL_DANFE'); ?>"
																			class="btn btn-sm green"><i class="fa fa-file-text-o"></i></a>
																		<?php
																		$dentroPrazoCancelamento = !(strtotime($exrow->data_emissao . ' +30 minutes') < strtotime(date('Y-m-d H:i:s')));
																		if ($dentroPrazoCancelamento):
																			?>
																			<a href="index.php?do=vendas&acao=cancelarvendafiscal&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm red btn-fiscal"
																				title="<?php echo lang('CADASTRO_APAGAR_VENDA_FISCAL') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-minus-circle"></i></a>
																			<?php
																		endif;
																		?>
																	<?php else: ?>
																		<?php if (!$exrow->cadastro): ?>
																			<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm blue popovers btn-fiscal" data-container="body"
																				data-trigger="hover" data-placement="top"
																				data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																				data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																				title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-user"></i></a>
																		<?php endif; ?>
																		<?php if ($exrow->cadastro || $exrow->valor_total < 10000.00): ?>
																			<?php if (!$exrow->contingencia && $exrow->status_enotas != "Negada"): ?>
																				<a href="javascript:void(0);"
																					onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																					title="<?php echo lang('FISCAL_NFC'); ?>"
																					class="btn btn-sm btn-fiscal <?php echo $cor_fiscal; ?>"><i
																						class="fa fa-file-text-o"></i></a>
																				<?php if ($usuario->is_Controller()): ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC') . " [DEBUG]"; ?>"
																						class="btn btn-sm btn-fiscal <?php echo $cor_fiscal; ?>"><i
																							class="fa fa-bug"></i></a>
																				<?php endif; ?>
																			<?php else: ?>
																				<?php if ($exrow->status_enotas == "Negada"): ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>"
																						class="btn btn-sm red btn-fiscal"><i class="fa fa-file-text-o"></i></a>
																					<?php if ($usuario->is_Controller()): ?>
																						<a href="javascript:void(0);"
																							onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																							title="<?php echo lang('FISCAL_NFC_REPROCESSAR') . " [DEBUG]"; ?>"
																							class="btn btn-sm btn-fiscal red"><i class="fa fa-bug"></i></a>
																					<?php endif; ?>
																				<?php else: ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>"
																						class="btn btn-sm btn-fiscal blue-chambray"><i
																							class="fa fa-file-text-o"></i></a>
																					<?php if ($usuario->is_Controller()): ?>
																						<a href="javascript:void(0);"
																							onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																							title="<?php echo lang('FISCAL_NFC_REPROCESSAR') . " [DEBUG]"; ?>"
																							class="btn btn-sm btn-fiscal blue-chambray"><i
																								class="fa fa-bug"></i></a>
																					<?php endif; ?>
																				<?php endif; ?>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php endif; ?>
																<?php elseif (!$exrow->cadastro): ?>
																	<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>"
																		class="btn btn-sm blue popovers btn-fiscal" data-container="body"
																		data-trigger="hover" data-placement="top"
																		data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																		data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																		title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																			class="fa fa-user"></i></a>
																<?php endif; ?>

																<?php if ($exrow->entrega): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('pdf_romaneio.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																		title="<?php echo lang('VER_ROMANEIO'); ?>"
																		class="btn btn-sm yellow-gold"><i
																			class="fa fa-truck btn-fiscal"></i></a>
																<?php endif; ?>
																<?php $modulo_boleto = getValue("modulo_emissao_boleto", "empresa", "id = " . $exrow->id_empresa);
																	if ($modulo_boleto == 1 && $categoria_pagamento == 4): ?>
																	<a href="boleto_<?php echo $exrow->banco_boleto; ?>.php?todos=1&id_pagamento=<?php echo $exrow->id_tipo_pagamento; ?>&id_empresa=<?php echo $exrow->id_empresa; ?>"
																		target="_blank" title="<?php echo lang('GERAR_TODOS'); ?>"
																		class="btn btn-sm grey-cascade btn-fiscal"><i
																			class="fa fa-bold"></i>
																		</a>
																<?php endif; ?>
																<?php if (!$exrow->fiscal && $usuario->is_Gerencia() && !$exrow->id_nota_fiscal): ?>
																	<a href="index.php?do=vendas&acao=cancelarvenda&id=<?php echo $exrow->id; ?>"
																		class="btn btn-sm red btn-fiscal"
																		title="<?php echo lang('CADASTRO_APAGAR_VENDA') . ': ' . $exrow->id; ?>"><i
																			class="fa fa-ban"></i></a>
																<?php endif; ?>
																<?php if ($exrow->id_nota_fiscal):
																	$cor_status = $exrow->nf_enotas === "Negada" ? 'red' : 'green';
																	?>

																	<a href="index.php?do=notafiscal&acao=visualizar&id=<?= $exrow->id_nota_fiscal; ?>"
																		class="btn btn-sm <?php echo $cor_status ?>" title="NF-e">NF-e</a>
																<?php endif; ?>
																<?php if (!$exrow->fiscal && !$exrow->id_nota_fiscal && $core->tipo_sistema != 2 && $core->tipo_sistema != 3): ?>
																	<?php if ($exrow->cadastro): ?>
																		<a href="javascript:void(0);"
																			class="btn btn-sm blue gerarNFEvenda btn-fiscal"
																			id="<?php echo $exrow->id; ?>"
																			title="<?php echo lang('NOTA_FISCAL_CONVERTER') . ': ' . $exrow->id; ?>"><i
																				class="fa fa-files-o"></i></a>
																	<?php else: ?>
																		<a href="javascript:void(0);"
																			class="btn btn-sm grey-cascade gerarNFEvendaBloqueio btn-fiscal"
																			title="<?php echo lang('NOTA_FISCAL_CONVERTER_NAO') . ': ' . $exrow->id; ?>"><i
																				class="fa fa-files-o"></i></a>
																	<?php endif; ?>
																<?php endif; ?>
															<?php elseif ($exrow->link_danfe): ?>
																<a href="<?php echo $exrow->link_danfe; ?>"
																	title="<?php echo lang('NOTA_FISCAL_DANFE'); ?>"
																	class="btn btn-sm <?php echo 'green'; ?>"><i
																		class="fa fa-file-pdf-o"></i></a>
															<?php endif; ?>
															<a href="javascript:void(0);" onClick="location.reload();"
																title="<?php echo lang('RECARREGAR'); ?>"
																class="btn btn-sm btn-reload blue-madison ocultar"><i
																	class="fa fa-refresh"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3"><span class="bold"><?php echo lang('TOTAL'); ?></td>
												<td><span class="bold"><?php echo moedap($total); ?></span></td>
												<td colspan="6"></td>
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
		<?php break; ?>
	<?php
	case "_backup_vendasaberto": ?>
		<?php if ($core->tipo_sistema == 1 || $core->tipo_sistema == 4)
			redirect_to("login.php"); ?>
		<?php if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif; ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_ABERTO'); ?></small></h1>
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
										<i class="fa fa-exclamation-triangle font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_ABERTO'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('COD_VENDA'); ?></th>
												<th><?php echo lang('DATA_VENDA'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
												<th><?php echo lang('VL_TOTAL'); ?></th>
												<th><?php echo lang('VL_DESCONTO'); ?></th>
												<th><?php echo lang('VL_ACRESCIMO'); ?></th>
												<th><?php echo lang('VL_PAGO'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $cadastro->getVendaAberto();
											$soma_total = 0;
											$soma_desconto = 0;
											$soma_acrescimo = 0;
											$soma_pago = 0;
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$soma_total += $exrow->valor_total;
													$soma_desconto += $exrow->valor_desconto;
													$soma_acrescimo += $exrow->valor_despesa_acessoria;
													$soma_pago += $exrow->valor_pago;
													?>
													<tr>
														<td><a
																href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id; ?>"><?php echo $exrow->id; ?></a>
														</td>
														<td><?php echo exibedata($exrow->data); ?></td>
														<td><a
																href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
														</td>
														<td>
															<?php
															$row_tipopagamento = $cadastro->getPagamentosVenda($exrow->id);
															if ($row_tipopagamento):
																foreach ($row_tipopagamento as $prow):
																	?>
																	<?php echo pagamento($prow->pagamento); ?><br />
																<?php endforeach;
															endif; ?>
														</td>
														<td><span class="theme-font"><?php echo moedap($exrow->valor_total); ?></span>
														</td>
														<td><span
																class="theme-font"><?php echo moedap($exrow->valor_desconto); ?></span>
														</td>
														<td><span
																class="theme-font"><?php echo moedap($exrow->valor_despesa_acessoria); ?></span>
														</td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago - $exrow->troco); ?></span>
														</td>
														<td><?php echo $exrow->usuario_venda; ?></td>
														<td>
															<a href="javascript:void(0);"
																onclick="javascript:void window.open('recibo_orcamento.php?id=<?php echo $exrow->id; ?>','<?php echo lang('IMPRIMIR_VENDA_ABERTO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																title="<?php echo lang('IMPRIMIR_VENDA_ABERTO'); ?>"
																class="btn btn-sm grey"><i class="fa fa-file-o"></i></a>
															<?php if (!$exrow->cadastro): ?>
																<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>&pg=2"
																	class="btn btn-sm blue popovers" data-container="body"
																	data-trigger="hover" data-placement="top"
																	data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																	data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																	title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																		class="fa fa-user"></i></a>
															<?php endif ?>
															<?php if ($exrow->id_cadastro > 0): ?>
																<a href="javascript:void(0);"
																	onclick="javascript:void window.open('pdf_pedido_orcamento.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																	title="<?php echo lang('IMPRIMIR_VENDA_ABERTO_A4'); ?>"
																	class="btn btn-sm grey-cascade"><i class="fa fa-file-text-o"></i></a>
																<?php if ($exrow->entrega): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('pdf_romaneio.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																		title="<?php echo lang('VER_ROMANEIO'); ?>"
																		class="btn btn-sm yellow-gold"><i class="fa fa-truck"></i></a>
																<?php endif; ?>
															<?php endif; ?>
															<?php if ($usuario->is_Todos()): ?>
																<a href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id; ?>"
																	class="btn btn-sm <?php echo $core->primeira_cor; ?>"
																	title="<?php echo lang('IR_PARA') . ": " . $exrow->id; ?>"><i
																		class="fa fa-share"></i></a>
																<a href="javascript:void(0);" class="btn btn-sm red apagar"
																	id="<?php echo $exrow->id; ?>" acao="processarCancelarVendaAberto"
																	title="<?php echo lang('CADASTRO_APAGAR_VENDA') . $exrow->id; ?>"><i
																		class="fa fa-ban"></i></a>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="4"><strong><?php echo lang('TOTAL'); ?></strong></td>
												<td><strong><?php echo moedap($soma_total); ?></strong></td>
												<td><strong><?php echo moedap($soma_desconto); ?></strong></td>
												<td><strong><?php echo moedap($soma_acrescimo); ?></strong></td>
												<td><strong><?php echo moedap($soma_pago); ?></strong></td>
												<td colspan="2"></td>
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
		<?php break; ?>
	<?php
	case "vendasaberto": ?>
		<?php if ($core->tipo_sistema == 1 || $core->tipo_sistema == 4)
			redirect_to("login.php"); ?>
		<?php if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif; ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_ABERTO'); ?></small></h1>
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
										<i class="fa fa-exclamation-triangle font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_ABERTO'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<?php
									$classeTipoUsuario = ($usuario->is_Administrativo()) ? "table_listar_vendas_aberto_cancelar" : "table_listar_vendas_aberto";
									$somaValoresVendas = $cadastro->obterTotalVendasAberto();
									?>
									<table class="table table-bordered table-condensed table-advance"
										id="<?php echo $classeTipoUsuario; ?>">
										<thead>
											<tr>
												<th><?php echo lang('COD_VENDA'); ?></th>
												<th><?php echo lang('DATA_VENDA'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('VL_TOTAL'); ?></th>
												<th><?php echo lang('VL_DESCONTO'); ?></th>
												<th><?php echo lang('VL_ACRESCIMO'); ?></th>
												<th><?php echo lang('VL_PAGO'); ?></th>
												<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3"></td>
												<td><?php echo moeda($somaValoresVendas->valor_total); ?></td>
												<td><?php echo moeda($somaValoresVendas->valor_desconto); ?></td>
												<td><?php echo moeda($somaValoresVendas->valor_acrescimo); ?></td>
												<td><?php echo moeda($somaValoresVendas->valor_pago); ?></td>
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
		<?php break; ?>
	<?php
	case "vendasvaloresalterados": ?>
		<?php if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif;
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=vendas&acao=vendasvaloresalterados&dataini=' + dataini + '&datafim=' + datafim;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_VALORES_ALTERADOS_OBS'); ?></small>
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
							<div class="portlet light">
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
										</div>
										<div class="form-group">
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?>'
												id="buscar" title='<?php echo lang('BUSCAR'); ?>'><i
													class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
										</div>
									</form>
								</div>
							</div>
							<!-- INICIO TABELA -->
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-shopping-car font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_VALORES_ALTERADOS_OBS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('COD_VENDA'); ?></th>
												<th><?php echo lang('DATA_VENDA'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('VALOR_REAL'); ?></th>
												<th><?php echo lang('VALOR_VENDA'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $cadastro->getVendasValorProdutoAlterado($dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo $exrow->id_venda; ?></td>
														<td><?php echo exibedata($exrow->data_venda); ?></td>
														<td>
															<?php if ($exrow->id_cadastro > 0): ?>
																<a
																	href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
															<?php else:
																echo '---'; ?>
															<?php endif; ?>
														</td>
														<td><?php echo $exrow->produto; ?></td>
														<td><?php echo moeda($exrow->valor_original); ?></td>
														<td><?php echo moeda($exrow->valor); ?></td>
														<td><?php echo $exrow->usuario; ?></td>
														<td width="150px">
															<a href="javascript:void(0);"
																onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id_venda; ?>','<?php echo "CODIGO: " . $exrow->id_venda; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title="<?php echo lang('VER_DETALHES'); ?>"
																class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
															<a href="javascript:void(0);"
																onclick="javascript:void window.open('recibo.php?id=<?php echo $exrow->id_venda; ?>','<?php echo lang('IMPRIMIR_RECIBO') . $exrow->id_venda; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																title="<?php echo lang('IMPRIMIR_RECIBO'); ?>"
																class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a>
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
	case "vendaspedidosentrega": ?>
		<?php if ($core->tipo_sistema != 4)
			redirect_to("login.php"); ?>
		<?php if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif; ?>
		<?php
		$valor_total1 = 0;
		$valor_total2 = 0;
		$valor_total3 = 0;
		$valor_total4 = 0;
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<script type="text/javascript">
			setTimeout(function () {
				location.reload();
			}, 60000);
		</script>

		<div id="definir-entregador" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i
								class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('ALTERAR_ENTREGADOR'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="definir_entregador_form" id="definir_entregador_form"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('NOVO_ENTREGADOR'); ?></label>
										<div class="col-md-9">
											<select class="select2me form-control input-large" name="id_entregador"
												id="id_entregador"
												data-placeholder="<?php echo lang('SELECIONE_ENTREGADOR'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $cadastro->getEntregadores();
												if ($retorno_row):
													foreach ($retorno_row as $erow):
														?>
														<option value="<?php echo $erow->id; ?>"><?php echo $erow->nome; ?></option>
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
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarDefinirEntregador", "definir_entregador_form"); ?>
		</div>

		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_ABERTO'); ?></small></h1>
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
										<i class="fa fa-exclamation-triangle font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_ABERTO'); ?></span>
									</div>
								</div>
								<div class="portlet-body">

									<div class="tabbable-custom ">
										<ul class="nav nav-tabs ">
											<li class="active">
												<a href="#tab_v_1" data-toggle="tab" aria-expanded="true">
													<?php echo lang('CADASTRO_VENDA_NOVA'); ?></a>
											</li>
											<li class="">
												<a href="#tab_v_2" data-toggle="tab" aria-expanded="false">
													<?php echo lang('CADASTRO_VENDA_ENTREGA'); ?></a>
											</li>
											<li class="">
												<a href="#tab_v_3" data-toggle="tab" aria-expanded="false">
													<?php echo lang('CADASTRO_VENDA_ENTREGUE'); ?></a>
											</li>
											<li class="">
												<a href="#tab_v_4" data-toggle="tab" aria-expanded="false">
													<?php echo lang('CADASTRO_VENDA_PROBLEMA'); ?></a>
											</li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane active" id="tab_v_1">
												<table
													class="table table-bordered table-striped table-condensed table-advance dataTable-desc">
													<thead>
														<tr>
															<th>#</th>
															<th><?php echo lang('COD_VENDA'); ?></th>
															<th><?php echo lang('DATA_VENDA'); ?></th>
															<th><?php echo lang('CLIENTE'); ?></th>
															<th><?php echo lang('VL_PAGAR'); ?></th>
															<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
															<th><?php echo lang('OBSERVACAO'); ?></th>
															<th><?php echo lang('PRAZO_ENTREGA'); ?></th>
															<th><?php echo lang('ENTREGADOR'); ?></th>
															<th><?php echo lang('USUARIO'); ?></th>
															<th><?php echo lang('OPCOES'); ?></th>
														</tr>
													</thead>
													<tbody>
														<?php
														$retorno_row = $cadastro->getVendaAberto_Novas();
														if ($retorno_row):
															foreach ($retorno_row as $exrow):
																$entregador = ($exrow->id_entregador) ? getValue("nome", "usuario", "id=" . $exrow->id_entregador) : "- - - - -";
																$valor_total1 += (($exrow->valor_total + $exrow->valor_despesa_acessoria) - $exrow->valor_desconto)
																	?>
																<tr>
																	<td><?php echo $exrow->id; ?></td>
																	<td><a
																			href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id; ?>"><?php echo $exrow->id; ?></a>
																	</td>
																	<td><?php echo exibedata($exrow->data); ?></td>
																	<td><a
																			href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
																	</td>
																	<td><span
																			class="bold theme-font valor_total"><?php echo moeda(($exrow->valor_total + $exrow->valor_despesa_acessoria) - $exrow->valor_desconto); ?></span>
																	</td>
																	<td>
																		<?php
																		$row_tipopagamento = $cadastro->getPagamentosVenda($exrow->id);
																		if ($row_tipopagamento):
																			foreach ($row_tipopagamento as $prow):
																				?>
																				<?php echo pagamento($prow->pagamento); ?><br />
																			<?php endforeach;
																		endif; ?>
																	</td>
																	<td><?php echo $exrow->observacao; ?></td>
																	<td><?php echo ($exrow->status_entrega == 9) ? lang('ENTREGA_BALCAO') : exibedata($exrow->prazo_entrega); ?>
																	</td>
																	<td><?php echo ($exrow->status_entrega == 9) ? lang('ENTREGA_BALCAO') : $entregador; ?>
																	</td>
																	<td><?php echo $exrow->usuario_venda; ?></td>
																	<td>
																		<?php if ($exrow->status_entrega != 9): ?>
																			<a href="javascript:void(0);"
																				class="btn btn-sm green definirEntregador"
																				id="<?php echo $exrow->id; ?>" acao="definirEntregador"
																				title="<?php echo lang('ALTERAR_ENTREGADOR'); ?>"><i
																					class="fa fa-user"></i></a>
																		<?php endif; ?>
																		<a href="javascript:void(0);"
																			onclick="javascript:void window.open('recibo_orcamento.php?id=<?php echo $exrow->id; ?>','<?php echo lang('IMPRIMIR_VENDA_ABERTO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																			title="<?php echo lang('IMPRIMIR_VENDA_ABERTO'); ?>"
																			class="btn btn-sm grey"><i class="fa fa-file-o"></i></a>
																		<?php if (!$exrow->cadastro): ?>
																			<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>&pg=2"
																				class="btn btn-sm blue popovers" data-container="body"
																				data-trigger="hover" data-placement="top"
																				data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																				data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																				title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-user"></i></a>
																		<?php endif ?>
																		<?php if ($exrow->id_cadastro > 0): ?>
																			<a href="javascript:void(0);"
																				onclick="javascript:void window.open('pdf_pedido_orcamento.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																				title="<?php echo lang('IMPRIMIR_VENDA_ABERTO_A4'); ?>"
																				class="btn btn-sm grey-cascade"><i
																					class="fa fa-file-text-o"></i></a>
																			<?php if ($exrow->entrega): ?>
																				<a href="javascript:void(0);"
																					onclick="javascript:void window.open('pdf_romaneio.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																					title="<?php echo lang('VER_ROMANEIO'); ?>"
																					class="btn btn-sm yellow-gold"><i
																						class="fa fa-truck"></i></a>
																			<?php endif; ?>
																		<?php endif; ?>
																		<?php if ($usuario->is_Todos()): ?>
																			<a href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm <?php echo $core->primeira_cor; ?>"
																				title="<?php echo lang('IR_PARA') . ": " . $exrow->id; ?>"><i
																					class="fa fa-share"></i></a>
																			<?php if ($usuario->is_Administrativo()): ?>
																				<!-- Condição adicionada conforme vendas em aberto no tipo de sistema N1 -->
																				<a href="javascript:void(0);" class="btn btn-sm red apagar"
																					id="<?php echo $exrow->id; ?>"
																					acao="processarCancelarVendaAberto"
																					title="<?php echo lang('CADASTRO_APAGAR_VENDA') . $exrow->id; ?>"><i
																						class="fa fa-ban"></i></a>
																			<?php endif; ?>
																		<?php endif; ?>
																	</td>
																</tr>
															<?php endforeach; ?>
															<?php unset($exrow);
														endif; ?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="4"><?php echo lang('TOTAL'); ?></td>
															<td><span class="bold"><?php echo moeda($valor_total1); ?></span>
															</td>
															<td colspan="6"></td>
														</tr>
													</tfoot>
												</table>
											</div>
											<div class="tab-pane" id="tab_v_2">
												<table
													class="table table-bordered table-striped table-condensed table-advance dataTable">
													<thead>
														<tr>
															<th><?php echo lang('COD_VENDA'); ?></th>
															<th><?php echo lang('DATA_VENDA'); ?></th>
															<th><?php echo lang('CLIENTE'); ?></th>
															<th><?php echo lang('VL_PAGAR'); ?></th>
															<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
															<th><?php echo lang('OBSERVACAO'); ?></th>
															<th><?php echo lang('PRAZO_ENTREGA'); ?></th>
															<th><?php echo lang('ENTREGADOR'); ?></th>
															<th><?php echo lang('USUARIO'); ?></th>
															<th><?php echo lang('OPCOES'); ?></th>
														</tr>
													</thead>
													<tbody>
														<?php
														$retorno_row = $cadastro->getVendaAberto_Entrega();
														if ($retorno_row):
															$valor_total2 = 0;
															foreach ($retorno_row as $exrow):
																$entregador = ($exrow->id_entregador) ? getValue("nome", "usuario", "id=" . $exrow->id_entregador) : "- - - - -";
																$valor_total2 += (($exrow->valor_total + $exrow->valor_despesa_acessoria) - $exrow->valor_desconto)
																	?>
																<tr>
																	<td><a
																			href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id; ?>"><?php echo $exrow->id; ?></a>
																	</td>
																	<td><?php echo exibedata($exrow->data); ?></td>
																	<td><a
																			href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
																	</td>
																	<td><span
																			class="bold theme-font valor_total"><?php echo moeda(($exrow->valor_total + $exrow->valor_despesa_acessoria) - $exrow->valor_desconto); ?></span>
																	</td>
																	<td>
																		<?php
																		$row_tipopagamento = $cadastro->getPagamentosVenda($exrow->id);
																		if ($row_tipopagamento):
																			foreach ($row_tipopagamento as $prow):
																				?>
																				<?php echo pagamento($prow->pagamento); ?><br />
																			<?php endforeach;
																		endif; ?>
																	</td>
																	<td><?php echo $exrow->observacao; ?></td>
																	<td><?php echo exibedata($exrow->prazo_entrega); ?></td>
																	<td><?php echo $entregador; ?></td>
																	<td><?php echo $exrow->usuario_venda; ?></td>
																	<td>
																		<a href="javascript:void(0);"
																			class="btn btn-sm green definirEntregador"
																			id="<?php echo $exrow->id; ?>" acao="definirEntregador"
																			title="<?php echo lang('ALTERAR_ENTREGADOR'); ?>"><i
																				class="fa fa-user"></i></a>
																		<a href="javascript:void(0);"
																			onclick="javascript:void window.open('recibo_orcamento.php?id=<?php echo $exrow->id; ?>','<?php echo lang('IMPRIMIR_VENDA_ABERTO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																			title="<?php echo lang('IMPRIMIR_VENDA_ABERTO'); ?>"
																			class="btn btn-sm grey"><i class="fa fa-file-o"></i></a>
																		<?php if (!$exrow->cadastro): ?>
																			<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>&pg=2"
																				class="btn btn-sm blue popovers" data-container="body"
																				data-trigger="hover" data-placement="top"
																				data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																				data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																				title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-user"></i></a>
																		<?php endif ?>
																		<?php if ($exrow->id_cadastro > 0): ?>
																			<?php if ($exrow->entrega): ?>
																				<a href="javascript:void(0);"
																					onclick="javascript:void window.open('pdf_romaneio.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																					title="<?php echo lang('VER_ROMANEIO'); ?>"
																					class="btn btn-sm yellow-gold"><i
																						class="fa fa-truck"></i></a>
																			<?php endif; ?>
																		<?php endif; ?>
																		<?php if ($usuario->is_Todos()): ?>
																			<a href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm <?php echo $core->primeira_cor; ?>"
																				title="<?php echo lang('IR_PARA') . ": " . $exrow->id; ?>"><i
																					class="fa fa-share"></i></a>
																			<a href="javascript:void(0);" class="btn btn-sm red apagar"
																				id="<?php echo $exrow->id; ?>"
																				acao="processarCancelarVendaAberto"
																				title="<?php echo lang('CADASTRO_APAGAR_VENDA') . $exrow->id; ?>"><i
																					class="fa fa-ban"></i></a>
																		<?php endif; ?>
																	</td>
																</tr>
															<?php endforeach; ?>
															<?php unset($exrow);
														endif; ?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="3"><?php echo lang('TOTAL'); ?></td>
															<td><span class="bold"><?php echo moeda($valor_total2); ?></span>
															</td>
															<td colspan="6"></td>
														</tr>
													</tfoot>
												</table>
											</div>
											<div class="tab-pane" id="tab_v_3">
												<table
													class="table table-bordered table-striped table-condensed table-advance dataTable">
													<thead>
														<tr>
															<th><?php echo lang('COD_VENDA'); ?></th>
															<th><?php echo lang('DATA_VENDA'); ?></th>
															<th><?php echo lang('CLIENTE'); ?></th>
															<th><?php echo lang('VL_PAGAR'); ?></th>
															<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
															<th><?php echo lang('OBSERVACAO'); ?></th>
															<th><?php echo lang('PRAZO_ENTREGA'); ?></th>
															<th><?php echo lang('ENTREGADOR'); ?></th>
															<th><?php echo lang('USUARIO'); ?></th>
															<th><?php echo lang('OPCOES'); ?></th>
														</tr>
													</thead>
													<tbody>
														<?php
														$retorno_row = $cadastro->getVendaAberto_Entregue();
														if ($retorno_row):
															foreach ($retorno_row as $exrow):
																$entregador = ($exrow->id_entregador) ? getValue("nome", "usuario", "id=" . $exrow->id_entregador) : "- - - - -";
																$valor_total3 += (($exrow->valor_total + $exrow->valor_despesa_acessoria) - $exrow->valor_desconto)
																	?>
																<tr>
																	<td><a
																			href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id; ?>"><?php echo $exrow->id; ?></a>
																	</td>
																	<td><?php echo exibedata($exrow->data); ?></td>
																	<td><a
																			href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
																	</td>
																	<td><span
																			class="bold theme-font valor_total"><?php echo moeda(($exrow->valor_total + $exrow->valor_despesa_acessoria) - $exrow->valor_desconto); ?></span>
																	</td>
																	<td>
																		<?php
																		$row_tipopagamento = $cadastro->getPagamentosVenda($exrow->id);
																		if ($row_tipopagamento):
																			foreach ($row_tipopagamento as $prow):
																				?>
																				<?php echo pagamento($prow->pagamento); ?><br />
																			<?php endforeach;
																		endif; ?>
																	</td>
																	<td><?php echo $exrow->observacao; ?></td>
																	<td><?php echo exibedata($exrow->prazo_entrega); ?></td>
																	<td><?php echo $entregador; ?></td>
																	<td><?php echo $exrow->usuario_venda; ?></td>
																	<td>
																		<a href="javascript:void(0);"
																			onclick="javascript:void window.open('recibo.php?id=<?php echo $exrow->id; ?>','<?php echo lang('IMPRIMIR_RECIBO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																			title="<?php echo lang('IMPRIMIR_RECIBO'); ?>"
																			class="btn btn-sm yellow-casablanca"><i
																				class="fa fa-file-o"></i></a>
																		<?php if (!$exrow->cadastro): ?>
																			<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>&pg=2"
																				class="btn btn-sm blue popovers" data-container="body"
																				data-trigger="hover" data-placement="top"
																				data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																				data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																				title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-user"></i></a>
																		<?php endif ?>
																		<?php if ($exrow->id_cadastro > 0): ?>
																			<?php if ($exrow->entrega): ?>
																				<a href="javascript:void(0);"
																					onclick="javascript:void window.open('pdf_romaneio.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																					title="<?php echo lang('VER_ROMANEIO'); ?>"
																					class="btn btn-sm yellow-gold"><i
																						class="fa fa-truck"></i></a>
																			<?php endif; ?>
																		<?php endif; ?>
																		<?php if ($usuario->is_Todos()): ?>
																			<a href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm <?php echo $core->primeira_cor; ?>"
																				title="<?php echo lang('IR_PARA') . ": " . $exrow->id; ?>"><i
																					class="fa fa-share"></i></a>
																			<a href="javascript:void(0);" class="btn btn-sm red apagar"
																				id="<?php echo $exrow->id; ?>"
																				acao="processarCancelarVendaAberto"
																				title="<?php echo lang('CADASTRO_APAGAR_VENDA') . $exrow->id; ?>"><i
																					class="fa fa-ban"></i></a>
																		<?php endif; ?>
																	</td>
																</tr>
															<?php endforeach; ?>
															<?php unset($exrow);
														endif; ?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="3"><?php echo lang('TOTAL'); ?></td>
															<td><span class="bold"><?php echo moeda($valor_total3); ?></span>
															</td>
															<td colspan="6"></td>
														</tr>
													</tfoot>
												</table>
											</div>
											<div class="tab-pane" id="tab_v_4">
												<table
													class="table table-bordered table-striped table-condensed table-advance dataTable">
													<thead>
														<tr>
															<th><?php echo lang('COD_VENDA'); ?></th>
															<th><?php echo lang('DATA_VENDA'); ?></th>
															<th><?php echo lang('CLIENTE'); ?></th>
															<th><?php echo lang('VL_PAGAR'); ?></th>
															<th><?php echo lang('TIPO_PAGAMENTO'); ?></th>
															<th><?php echo lang('OBSERVACAO'); ?></th>
															<th><?php echo lang('PRAZO_ENTREGA'); ?></th>
															<th><?php echo lang('ENTREGADOR'); ?></th>
															<th><?php echo lang('STATUS'); ?></th>
															<th><?php echo lang('USUARIO'); ?></th>
															<th><?php echo lang('OPCOES'); ?></th>
														</tr>
													</thead>
													<tbody>
														<?php
														$retorno_row = $cadastro->getVendaAberto_Problema();
														if ($retorno_row):
															foreach ($retorno_row as $exrow):
																$entregador = ($exrow->id_entregador) ? getValue("nome", "usuario", "id=" . $exrow->id_entregador) : "- - - - -";
																$valor_total4 += (($exrow->valor_total + $exrow->valor_despesa_acessoria) - $exrow->valor_desconto)
																	?>
																<tr>
																	<td><a
																			href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id; ?>"><?php echo $exrow->id; ?></a>
																	</td>
																	<td><?php echo exibedata($exrow->data); ?></td>
																	<td><a
																			href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
																	</td>
																	<td><span
																			class="bold theme-font valor_total"><?php echo moeda(($exrow->valor_total + $exrow->valor_despesa_acessoria) - $exrow->valor_desconto); ?></span>
																	</td>
																	<td>
																		<?php
																		$row_tipopagamento = $cadastro->getPagamentosVenda($exrow->id);
																		if ($row_tipopagamento):
																			foreach ($row_tipopagamento as $prow):
																				?>
																				<?php echo pagamento($prow->pagamento); ?><br />
																			<?php endforeach;
																		endif; ?>
																	</td>
																	<td><?php echo $exrow->observacao; ?></td>
																	<td><?php echo exibedata($exrow->prazo_entrega); ?></td>
																	<td><?php echo $entregador; ?></td>
																	<td><?php echo $exrow->status; ?></td>
																	<td><?php echo $exrow->usuario_venda; ?></td>
																	<td>
																		<a href="javascript:void(0);"
																			class="btn btn-sm green definirEntregador"
																			id="<?php echo $exrow->id; ?>" acao="definirEntregador"
																			title="<?php echo lang('ALTERAR_ENTREGADOR'); ?>"><i
																				class="fa fa-user"></i></a>
																		<a href="javascript:void(0);"
																			onclick="javascript:void window.open('recibo_orcamento.php?id=<?php echo $exrow->id; ?>','<?php echo lang('IMPRIMIR_VENDA_ABERTO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																			title="<?php echo lang('IMPRIMIR_VENDA_ABERTO'); ?>"
																			class="btn btn-sm grey"><i class="fa fa-file-o"></i></a>
																		<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>&pg=2"
																			class="btn btn-sm blue popovers" data-container="body"
																			data-trigger="hover" data-placement="top"
																			data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																			data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																			title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																				class="fa fa-user"></i></a>
																		<?php if ($exrow->id_cadastro > 0): ?>
																			<?php if ($exrow->entrega): ?>
																				<a href="javascript:void(0);"
																					onclick="javascript:void window.open('pdf_romaneio.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																					title="<?php echo lang('VER_ROMANEIO'); ?>"
																					class="btn btn-sm yellow-gold"><i
																						class="fa fa-truck"></i></a>
																			<?php endif; ?>
																		<?php endif; ?>
																		<?php if ($usuario->is_Todos()): ?>
																			<a href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm <?php echo $core->primeira_cor; ?>"
																				title="<?php echo lang('IR_PARA') . ": " . $exrow->id; ?>"><i
																					class="fa fa-share"></i></a>
																			<a href="javascript:void(0);" class="btn btn-sm red apagar"
																				id="<?php echo $exrow->id; ?>"
																				acao="processarCancelarVendaAberto"
																				title="<?php echo lang('CADASTRO_APAGAR_VENDA') . $exrow->id; ?>"><i
																					class="fa fa-ban"></i></a>
																		<?php endif; ?>
																	</td>
																</tr>
															<?php endforeach; ?>
															<?php unset($exrow);
														endif; ?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="3"><?php echo lang('TOTAL'); ?></td>
															<td><span class="bold"><?php echo moeda($valor_total4); ?></span>
															</td>
															<td colspan="7"></td>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
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
		<?php break; ?>
	<?php
	case "vendascanceladas":
		$data = (get('data')) ? get('data') : date("m/Y"); ?>
		<?php if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif; ?>
		<script type="text/javascript">
			$(document).ready(function () {
				$('#mes_ano').change(function () {
					var datafiltro = $("#mes_ano").val();
					window.location.href = 'index.php?do=vendas&acao=vendascanceladas&data=' + datafiltro;
				});
			});
		</script>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_CANCELADAS'); ?></small></h1>
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
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_CANCELADAS'); ?></span>
									</div>
								</div>
								<div class="portlet-body form">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="mes_ano" id="mes_ano"
												data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $gestao->getListaMes("cadastro_vendas", "data", false, "DESC");
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->mes_ano; ?>" <?php if ($srow->mes_ano == $data)
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
									</form>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('DATA_VENDA'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('ITEM'); ?></th>
												<th><?php echo lang('VL_TOTAL'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $cadastro->getVendaCanceladas($data);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo exibedata($exrow->data); ?></td>
														<td><a
																href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
														</td>
														<td><?php echo $exrow->produto; ?></td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_total); ?></span>
														</td>
														<td><?php echo $exrow->usuario_venda; ?></td>
														<td>
															<a href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"
																class="btn btn-sm <?php echo $core->primeira_cor; ?>"
																title="<?php echo lang('IR_PARA') . ": " . $exrow->endereco; ?>"><i
																	class="fa fa-share"></i></a>
															<a href="javascript:void(0);"
																onclick="javascript:void window.open('imprimir_detalhes_item.php?id=<?php echo $exrow->id; ?>','<?php echo $exrow->produto; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title="" class="btn btn-sm grey-cascade"><i
																	class="fa fa-search"></i></a>
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
	case "produtos":
		$id_familia = get('id_familia') ? get('id_familia') : 0;
		$id_fabricante = get('id_fabricante') ? get('id_fabricante') : 0;
		$id_classe = get('id_classe') ? get('id_classe') : 0;
		$id_grupo = get('id_grupo') ? get('id_grupo') : 0;
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.buscar').click(function () {
					var id_classe = $("#id_classe").val();
					var id_familia = $("#id_familia").val();
					var id_fabricante = $("#id_fabricante").val();
					var id_grupo = $("#id_grupo").val();
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=vendas&acao=produtos&dataini=' + dataini + '&datafim=' + datafim + '&id_classe=' + id_classe + '&id_familia=' + id_familia + '&id_fabricante=' + id_fabricante + '&id_grupo=' + id_grupo;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_PRODUTO'); ?></small></h1>
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
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_PRODUTO'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_familia" id="id_familia"
												data-placeholder="<?php echo lang('SELECIONE_FAMILIA'); ?>">
												<option value="">TODAS FAMILIAS</option>
												<?php
												$retorno_row = $familia->getFamilias();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_familia)
															   echo 'selected="selected"'; ?>><?php echo $srow->familia; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;
											<select class="select2me form-control input-large" name="id_classe" id="id_classe">
												<option value="">TODAS CLASSES</option>
												<?php
												$retorno_row = $classe->getClasses();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_classe)
															   echo 'selected="selected"'; ?>><?php echo $srow->classe; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											<br />
											<br />
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
											&nbsp;&nbsp;
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
											<br />
											<br />
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
											&nbsp;&nbsp;
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?> buscar'
												title='<?php echo lang('BUSCAR'); ?>'><i
													class='fa fa-search'></i><?php echo lang('BUSCAR'); ?></a>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('CODIGO_DA_NOTA'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('QUANTIDADE'); ?></th>
												<th><?php echo lang('TOTAL'); ?></th>
												<th><?php echo lang('ESTOQUE'); ?></th>
												<th><?php echo lang('PERSONAL'); ?></th>
												<th style="width: 230px"><?php echo lang('ACOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$tquant = 0;
											$total = 0;
											$retorno_row = $cadastro->getVendasProdutos($id_classe, $id_familia, $id_fabricante, $id_grupo, $dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$quantidade = $produto->getEstoqueTotal($exrow->id, false);
													$tquant += $exrow->quantidade;
													$total += $exrow->valor_total;
													?>
													<tr>
														<td><?php echo $exrow->codigo; ?></td>
														<td><?php echo $exrow->nome; ?></a></td>
														<td><?php echo $exrow->quantidade; ?></td>
														<td><?php echo moedap($exrow->valor_total); ?></td>
														<td><?php echo $quantidade; ?></td>
														<td><?php echo ($exrow->kit_personal) ? "SIM" : "NAO"; ?></td>
														<td>
															<a href="javascript:void(0);"
																onclick="javascript:void window.open('imprimir_vendas_produto.php?id_ref=<?php echo $exrow->id_ref; ?>&dataini=<?php echo $dataini; ?>&datafim=<?php echo $datafim; ?>','<?php echo "CODIGO: " . $exrow->id_ref; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title="" class="btn btn-sm grey-cascade"><i
																	class="fa fa-search"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="2"><strong><?php echo lang('TOTAL'); ?></strong></td>
												<td><strong><?php echo $tquant; ?></strong></td>
												<td><strong><?php echo moedap($total); ?></strong></td>
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
		<?php break; ?>
	<?php
	case "servicos":
		$id_grupo = get('id_grupo') ? get('id_grupo') : 0;
		$id_classe = get('id_classe') ? get('id_classe') : 0;
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.buscar').click(function () {
					var id_classe = $("#id_classe").val();
					var id_grupo = $("#id_grupo").val();
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=vendas&acao=servicos&dataini=' + dataini + '&datafim=' + datafim + '&id_classe=' + id_classe + '&id_grupo=' + id_grupo;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_SERVICO'); ?></small></h1>
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
										<i class="fa fa-scissors font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_SERVICO'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" name="id_classe" id="id_classe">
												<option value="">TODOS CLASSES</option>
												<?php
												$retorno_row = $classe->getClasses();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_classe)
															   echo 'selected="selected"'; ?>><?php echo $srow->classe; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
											&nbsp;&nbsp;
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
											<br />
											<br />
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
											&nbsp;&nbsp;
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?> buscar'
												title='<?php echo lang('BUSCAR'); ?>'><i
													class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('SERVICO'); ?></th>
												<th><?php echo lang('QUANTIDADE'); ?></th>
												<th><?php echo lang('TOTAL'); ?></th>
												<th style="width: 230px"><?php echo lang('ACOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$tquant = 0;
											$total = 0;
											$retorno_row = $cadastro->getVendasServicos($id_classe, $id_grupo, $dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$tquant += $exrow->quantidade;
													$total += $exrow->valor_total;
													?>
													<tr>
														<td><?php echo $exrow->nome; ?></a></td>
														<td><?php echo $exrow->quantidade; ?></td>
														<td><?php echo moedap($exrow->valor_total); ?></td>
														<td>
															<a href="javascript:void(0);"
																onclick="javascript:void window.open('imprimir_vendas_servico.php?id_ref=<?php echo $exrow->id_ref; ?>&dataini=<?php echo $dataini; ?>&datafim=<?php echo $datafim; ?>','<?php echo "CODIGO: " . $exrow->id_ref; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title="" class="btn btn-sm grey-cascade"><i
																	class="fa fa-search"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td><strong><?php echo lang('TOTAL'); ?></strong></td>
												<td><strong><?php echo $tquant; ?></strong></td>
												<td colspan="2"><strong><?php echo moedap($total); ?></strong></td>
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
		<?php break; ?>
	<?php
	case "produtoperiodo":
		$vendedor = (get('vendedor')) ? get('vendedor') : '';
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.buscar').click(function () {
					var vendedor = $("#vendedor").val();
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=vendas&acao=produtoperiodo&dataini=' + dataini + '&datafim=' + datafim + '&vendedor=' + vendedor;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_PORPRODUTO'); ?></small></h1>
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
										<i class="fa fa-shopping-cart font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_PERIODO'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
										</div>
										<div class="form-group">
											<select class="select2me form-control input-large" name="vendedor" id="vendedor"
												data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $cadastro->getUsuariosVenda($dataini, $datafim);
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->usuario; ?>" <?php if ($srow->usuario == $vendedor)
															   echo 'selected="selected"'; ?>>
															<?php echo $srow->usuario; ?>
														</option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
										<div class="form-group">
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?> buscar'
												title='<?php echo lang('BUSCAR'); ?>'><i
													class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
										</div>
									</form>
								</div>
							</div>
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-barcode font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTOS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('CODIGO'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th style="width: 70px"><?php echo lang('VALOR'); ?></th>
												<th style="width: 70px"><?php echo lang('ACRESCIMO'); ?></th>
												<th style="width: 70px"><?php echo lang('DESCONTO'); ?></th>
												<th style="width: 70px"><?php echo lang('TOTAL'); ?></th>
												<th style="width: 70px"><?php echo lang('CUSTO'); ?></th>
												<th style="width: 70px"><?php echo lang('MARGEM'); ?></th>
												<th style="width: 70px"><?php echo lang('LUCRO'); ?></th>
												<th style="width: 70px"><?php echo lang('LUCRO_%'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th style="width: 100px"><?php echo lang('ACOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$tquant = 0;
											$total = 0;
											$total_custo = 0;
											$total_margem = 0;
											$retorno_row = $cadastro->getVendasProdutoPeriodo($dataini, $datafim, $vendedor);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$valor_custo = $exrow->valor_custo;
													$custo = $valor_custo * $exrow->quantidade;
													$margem = ($custo) ? $exrow->valor_total / $custo : 0;
													$lucro = ($exrow->valor_total - $exrow->valor_desconto + $exrow->valor_despesa_acessoria) - $custo;
													$total_custo += $custo;
													$total_margem += $margem;
													$total += $exrow->valor_total + $exrow->valor_despesa_acessoria - $exrow->valor_desconto;
													$tquant++;
													?>
													<tr>
														<td><a href="javascript:void(0);"
																onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id_venda; ?>','<?php echo "CODIGO: " . $exrow->id_venda; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title=""><?php echo $exrow->id_venda; ?></a></td>
														<td><?php echo $exrow->cadastro; ?></a></td>
														<td><?php echo $exrow->produto; ?></a></td>
														<td><?php echo moedap($exrow->valor); ?></td>
														<td><?php echo moedap($exrow->valor_despesa_acessoria); ?></td>
														<td><?php echo moedap($exrow->valor_desconto); ?></td>
														<td><?php echo moedap($exrow->valor_total - $exrow->valor_desconto + $exrow->valor_despesa_acessoria); ?>
														</td>
														<td><?php echo moedap($custo); ?></td>
														<td><?php echo moedap($margem); ?></td>
														<td><?php echo moedap($lucro); ?></td>
														<td><?php echo ($lucro) ? fpercentual($lucro, $custo) : "-"; ?></td>
														<td><?php echo $exrow->usuario; ?></a></td>
														<td>
															<a href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"
																class="btn btn-sm <?php echo $core->primeira_cor; ?>"
																title="<?php echo lang('IR_PARA') . ": " . $exrow->endereco; ?>"><i
																	class="fa fa-share"></i></a>
															<a href="javascript:void(0);"
																onclick="javascript:void window.open('imprimir_detalhes_item.php?id=<?php echo $exrow->id; ?>','<?php echo $exrow->cadastro; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title="" class="btn btn-sm grey-cascade"><i
																	class="fa fa-search"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="2"><strong><?php echo lang('TOTAL'); ?></strong></td>
												<td><strong><?php echo $tquant; ?></strong></td>
												<td colspan="3"></td>
												<td><strong><?php echo moedap($total); ?></strong></td>
												<td><strong><?php echo moedap($total_custo); ?></strong></td>
												<td><strong><?php echo ($total_custo) ? moedap($total / $total_custo) : 0; ?></strong>
												</td>
												<td><strong><?php echo moedap($total - $total_custo); ?></strong></td>
												<td><strong><?php echo ($total - $total_custo) ? fpercentual(($total - $total_custo), $total_custo) : "-"; ?></strong>
												</td>
												<td colspan="2"></td>
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
		<?php break; ?>
	<?php
	case "vendasconsolidado":
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=vendas&acao=vendasconsolidado&dataini=' + dataini + '&datafim=' + datafim;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_CONSOLIDADAS'); ?></small>
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
										<i class="fa fa-list-ol font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_CONSOLIDADAS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
											&nbsp;&nbsp;
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?> buscar'
												title='<?php echo lang('BUSCAR'); ?>'><i
													class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
										</div>
									</form>
								</div>
							</div>
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-usd font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PAGAMENTOS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance">
										<thead>
											<tr>
												<th><?php echo lang('PAGAMENTO'); ?></th>
												<th><?php echo lang('QUANT'); ?></th>
												<th><?php echo lang('VALOR'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$totalpagamentos = 0;
											$total = 0;
											$totalcartoes = 0;
											$retorno_row = $cadastro->getFinanceiroPeriodo($dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													if ($exrow->id_categoria == 7 || $exrow->id_categoria == 8) {
														$totalcartoes += $exrow->valor_pago;
													}
													$total += $exrow->valor_pago;
													?>
													<tr>
														<td><?php echo ($exrow->pagamento) ? $exrow->pagamento : lang('CREDIARIO_M'); ?>
														</td>
														<td><?php echo $exrow->quant; ?></td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago); ?></span>
														</td>
														<td>
															<a href="index.php?do=vendas&acao=vendastipo&tipo=<?php echo $exrow->id; ?>&dataini=<?php echo $dataini; ?>&datafim=<?php echo $datafim; ?>"
																class="btn btn-sm grey-cascade"
																title="<?php echo lang('VENDAS_CONSOLIDADAS_TIPO') . ': ' . $exrow->pagamento; ?>"
																target="_blank"><i class="fa fa-search"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"><strong><?php echo lang('TOTAL'); ?></strong></td>
													<td colspan="2"><strong><?php echo moedap($total); ?></strong></td>
												</tr>
												<tr>
													<td colspan="3">&nbsp;&nbsp;</td>
												</tr>
												<tr class='info'>
													<td colspan="2"><strong><?php echo lang('TOTAL_CARTOES'); ?></strong></td>
													<td colspan="2"><strong><?php echo moedap($totalcartoes); ?></strong></td>
												</tr>
											</tfoot>
											<?php unset($exrow);
											endif; ?>
									</table>
								</div>
							</div>
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-users font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_VENDEDOR_PRODUTO_PERIODO'); ?></span>
										<h6><?php echo lang('VENDAS_VENDEDOR_PRODUTO_PERIODO_OBS'); ?></h6>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th style="width: 30px"><?php echo lang('NOME'); ?></th>
												<th style="width: 70px"><?php echo lang('QUANT'); ?></th>
												<th style="width: 70px"><?php echo lang('VALOR'); ?></th>
												<th style="width: 70px"><?php echo lang('DESCONTO'); ?></th>
												<th style="width: 70px"><?php echo lang('ACRESCIMO'); ?></th>
												<th style="width: 70px"><?php echo lang('TOTAL'); ?></th>
												<th style="width: 70px"><?php echo lang('TICKET_MEDIO'); ?></th>
												<th style="width: 70px"><?php echo lang('COMISSAO'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$tquant = 0;
											$valor = 0;
											$desconto = 0;
											$acrescimo = 0;
											$total = 0;
											$quant = 0;
											$ticket_medio = 0;
											$comissaoTotal = 0;
											$retorno_row = $cadastro->getVendasConsolidadoVendedor($dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$valor += $exrow->valor;
													$desconto += $exrow->valor_desconto;
													$acrescimo += $exrow->valor_despesa_acessoria;
													$total += ($exrow->valor_total - $exrow->valor_troco);
													$quant += $exrow->quant;
													$tquant++;
													$comissao = (($exrow->percentual / 100) * ($exrow->valor_total - $exrow->valor_troco));
													$comissaoTotal += $comissao;
													$ticket_medio += (($exrow->valor_total - $exrow->valor_troco) / $exrow->quant);
													?>
													<tr>
														<td><?php echo $exrow->nome; ?></td>
														<td><?php echo $exrow->quant; ?></td>
														<td><?php echo moedap($exrow->valor); ?></td>
														<td><?php echo moedap($exrow->valor_desconto); ?></td>
														<td><?php echo moedap($exrow->valor_despesa_acessoria); ?></td>
														<td><?php echo moedap($exrow->valor_total - $exrow->valor_troco); ?></td>
														<td><?php echo moedap(($exrow->valor_total - $exrow->valor_troco) / $exrow->quant); ?>
														</td>
														<td><?php echo moeda($comissao); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td><strong><?php echo lang('TOTAL') . ": " . $tquant; ?></strong></td>
													<td><strong><?php echo $quant; ?></strong></td>
													<td><strong><?php echo moedap($valor); ?></strong></td>
													<td><strong><?php echo moedap($desconto); ?></strong></td>
													<td><strong><?php echo moedap($acrescimo); ?></strong></td>
													<td><strong><?php echo moedap($total); ?></strong></td>
													<td><strong><?php echo moedap($ticket_medio); ?></strong></td>
													<td><strong><?php echo moeda($comissaoTotal); ?></strong></td>
												</tr>
											</tfoot>
											<?php unset($exrow);
											endif; ?>
									</table>
								</div>
							</div>

							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-users font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_VENDEDOR_RECEBIMENTOS_PERIODO'); ?></span>
										<h6><?php echo lang('VENDAS_VENDEDOR_RECEBIMENTOS_PERIODO_OBS'); ?></h6>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('NOME'); ?></th>
												<th style="width: 70px"><?php echo lang('QUANT'); ?></th>
												<th style="width: 70px"><?php echo lang('VALOR'); ?></th>
												<th style="width: 70px"><?php echo lang('PAGO'); ?></th>
												<th style="width: 70px"><?php echo lang('COMISSAO'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$tquant = 0;
											$valor = 0;
											$pago = 0;
											$quant = 0;
											$comissaoTotal = 0;
											$retorno_row = $cadastro->getVendasConsolidadoVendedorPago($dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$valor += $exrow->valor;
													$pago += ($exrow->valor_pago - $exrow->valor_troco);
													$quant += $exrow->quant;
													$tquant++;
													$comissao = (($exrow->percentual / 100) * $exrow->valor);
													$comissaoTotal += $comissao;
													?>
													<tr>
														<td><?php echo $exrow->nome; ?></td>
														<td><?php echo $exrow->quant; ?></td>
														<td><?php echo moedap($exrow->valor); ?></td>
														<td><?php echo moedap($exrow->valor_pago - $exrow->valor_troco); ?></td>
														<td><?php echo moeda($comissao); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td><strong><?php echo lang('TOTAL') . ": " . $tquant; ?></strong></td>
													<td><strong><?php echo $quant; ?></strong></td>
													<td><strong><?php echo moedap($valor); ?></strong></td>
													<td><strong><?php echo moedap($pago); ?></strong></td>
													<td><strong><?php echo moeda($comissaoTotal); ?></strong></td>
												</tr>
											</tfoot>
											<?php unset($exrow);
											endif; ?>
									</table>
								</div>
							</div>

							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-barcode font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTOS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('CODIGO'); ?></th>
												<th><?php echo lang('NCM'); ?></th>
												<th style="width: 70px"><?php echo lang('QUANT'); ?></th>
												<th style="width: 70px"><?php echo lang('VALOR'); ?></th>
												<th style="width: 70px"><?php echo lang('DESPESAS_ACESSORIAS'); ?></th>
												<th style="width: 70px"><?php echo lang('DESCONTO'); ?></th>
												<th style="width: 70px"><?php echo lang('TOTAL'); ?></th>
												<th style="width: 70px"><?php echo lang('VALOR_CUSTO'); ?></th>
												<th style="width: 70px"><?php echo lang('MARGEM'); ?></th>
												<th style="width: 70px"><?php echo lang('LUCRO'); ?></th>
												<th style="width: 70px"><?php echo lang('LUCRO_%'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$tquant = 0;
											$valor = 0;
											$desconto = 0;
											$despesa_acessoria = 0;
											$total = 0;
											$quant = 0;
											$custo = 0;
											$total_lucro = 0;
											$lucro = 0;
											$margem = 0;

											$retorno_row = $cadastro->getVendasConsolidado($dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$margem = ($exrow->valor_custo <> 0) ? $exrow->valor_total / $exrow->valor_custo : 0;
													$lucro = $exrow->valor_total - $exrow->valor_desconto + $exrow->despesa_acessoria - $exrow->valor_custo;
													$custo += $exrow->valor_custo;
													$valor += $exrow->valor;
													$total_lucro += $exrow->valor_total - $exrow->valor_desconto + $exrow->despesa_acessoria - $exrow->valor_custo;
													$despesa_acessoria += $exrow->despesa_acessoria;
													$desconto += $exrow->valor_desconto;
													$total += $exrow->valor_total + $exrow->despesa_acessoria - $exrow->valor_desconto;
													$quant += $exrow->quant;
													$tquant++;
													?>
													<tr>
														<td><?php echo $exrow->produto; ?></a></td>
														<td><?php echo $exrow->codigo; ?></a></td>
														<td><?php echo $exrow->ncm; ?></a></td>
														<td><?php echo $exrow->quant; ?></td>
														<td><?php echo moedap($exrow->valor); ?></td>
														<td><?php echo moedap($exrow->despesa_acessoria); ?></td>
														<td><?php echo moedap($exrow->valor_desconto); ?></td>
														<td><?php echo moedap($exrow->valor_total + $exrow->despesa_acessoria - $exrow->valor_desconto); ?>
														</td>
														<td><?php echo moedap($exrow->valor_custo); ?></td>
														<td><?php echo decimalp($margem); ?></td>
														<td><?php echo moedap($lucro); ?></td>
														<td><?php echo ($lucro) ? fpercentual($lucro, $exrow->valor_custo) : "0 %"; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="3"><strong><?php echo lang('TOTAL') . ": " . $tquant; ?></strong>
													</td>
													<td><strong><?php echo $quant; ?></strong></td>
													<td><strong><?php echo moedap($valor); ?></strong></td>
													<td><strong><?php echo moedap($despesa_acessoria); ?></strong></td>
													<td><strong><?php echo moedap($desconto); ?></strong></td>
													<td><strong><?php echo moedap($total); ?></strong></td>
													<td><strong><?php echo moedap($custo); ?></strong></td>
													<td><strong><?php echo decimalp($total / $custo); ?></strong></td>
													<td><strong><?php echo moedap($total_lucro); ?></strong></td>
													<td><strong><?php echo ($total_lucro) ? fpercentual($total_lucro, $custo) : "0 %"; ?></strong>
													</td>
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
	case "vendasfiscal":
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=vendas&acao=vendasfiscal&dataini=' + dataini + '&datafim=' + datafim;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_FISCAIS'); ?></small></h1>
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
										<i class="fa fa-list-ol font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_FISCAIS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
											&nbsp;&nbsp;
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?> buscar'
												title='<?php echo lang('BUSCAR'); ?>'><i
													class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
										</div>
									</form>
								</div>
							</div>
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-usd font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PAGAMENTOS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance">
										<thead>
											<tr>
												<th><?php echo lang('PAGAMENTO'); ?></th>
												<th><?php echo lang('QUANT'); ?></th>
												<th><?php echo lang('VALOR'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total_quant = 0;
											$total = 0;
											$retorno_row = $cadastro->getFinanceiroPeriodoFiscal($dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$total_quant += $exrow->quant;
													$total += $exrow->valor_pago;
													?>
													<tr>
														<td><?php echo ($exrow->pagamento) ? $exrow->pagamento : lang('CREDIARIO_M'); ?>
														</td>
														<td><?php echo $exrow->quant; ?></td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago); ?></span>
														</td>
														<td>
															<a href="index.php?do=vendas&acao=vendasperiodofiscal&tipo=<?php echo $exrow->id; ?>&dataini=<?php echo $dataini; ?>&datafim=<?php echo $datafim; ?>"
																class="btn btn-sm grey-cascade"
																title="<?php echo lang('VENDAS_CONSOLIDADAS_TIPO_FISCAL') . ': ' . $exrow->pagamento; ?>"
																target="_blank"><i class="fa fa-search"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td><strong><?php echo lang('TOTAL'); ?></strong></td>
													<td><strong><?php echo $total_quant; ?></strong></td>
													<td><strong><?php echo moedap($total); ?></strong></td>
													<td>
														<a href="index.php?do=vendas&acao=vendasperiodofiscal&tipo=0&dataini=<?php echo $dataini; ?>&datafim=<?php echo $datafim; ?>"
															class="btn btn-sm grey-cascade"
															title="<?php echo lang('VENDAS_PERIODO_FISCAL') ?>" target="_blank"><i
																class="fa fa-search"></i></a>
													</td>
												</tr>
											</tfoot>
											<?php unset($exrow);
											endif; ?>
									</table>
								</div>
							</div>
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-barcode font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTOS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('CODIGO'); ?></th>
												<th><?php echo lang('NCM'); ?></th>
												<th style="width: 70px"><?php echo lang('QUANT'); ?></th>
												<th style="width: 70px"><?php echo lang('VALOR'); ?></th>
												<th style="width: 70px"><?php echo lang('DESPESAS_ACESSORIAS'); ?></th>
												<th style="width: 70px"><?php echo lang('DESCONTO'); ?></th>
												<th style="width: 70px"><?php echo lang('TOTAL'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$tquant = 0;
											$valor = 0;
											$desconto = 0;
											$despesa_acessoria = 0;
											$total = 0;
											$quant = 0;
											$custo = 0;
											$total_margem = 0;
											$retorno_row = $cadastro->getVendasConsolidadoFiscal($dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$valor += $exrow->valor;
													$despesa_acessoria += $exrow->despesa_acessoria;
													$desconto += $exrow->valor_desconto;
													$total += $exrow->valor_total + $exrow->despesa_acessoria - $exrow->valor_desconto;
													$quant += $exrow->quant;
													$tquant++;
													?>
													<tr>
														<td><?php echo $exrow->produto; ?></a></td>
														<td><?php echo $exrow->codigo; ?></a></td>
														<td><?php echo $exrow->ncm; ?></a></td>
														<td><?php echo $exrow->quant; ?></td>
														<td><?php echo moedap($exrow->valor); ?></td>
														<td><?php echo moedap($exrow->despesa_acessoria); ?></td>
														<td><?php echo moedap($exrow->valor_desconto); ?></td>
														<td><?php echo moedap($exrow->valor_total); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="3"><strong><?php echo lang('TOTAL') . ": " . $tquant; ?></strong>
													</td>
													<td><strong><?php echo $quant; ?></strong></td>
													<td><strong><?php echo moedap($valor); ?></strong></td>
													<td><strong><?php echo moedap($despesa_acessoria); ?></strong></td>
													<td><strong><?php echo moedap($desconto); ?></strong></td>
													<td><strong><?php echo moedap($total); ?></strong></td>
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
	case "vendasprodutofornecedor":
		if ($core->tipo_sistema == 1 || $core->tipo_sistema == 3) {
			redirect_to("login.php");
		}
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		$id_produto = (get('produto')) ? get('produto') : 0;
		$id_produto = ($id_produto == "null") ? 0 : $id_produto;
		$id_fornecedor = (get('fornecedor')) ? get('fornecedor') : 0;
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					var produto = $("#id_produto_fornecedor").val();
					var fornecedor = $("#id_fornecedor").val();
					window.location.href = 'index.php?do=vendas&acao=vendasprodutofornecedor&dataini=' + dataini + '&datafim=' + datafim + '&produto=' + produto + '&fornecedor=' + fornecedor;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_PRODUTO_FABRICANTE_TITULO'); ?></small>
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
							<!-- INICIO TABELA -->
							<div class='portlet box <?php echo $core->primeira_cor; ?>'>
								<div class="portlet-title">
									<div class="caption">
										<i
											class="fa fa-cubes">&nbsp;&nbsp;</i><?php echo lang('VENDAS_PRODUTO_FABRICANTE_TITULO'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<form autocomplete="off" class="form-horizontal">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-4"><?php echo lang('DATA_INICIAL'); ?></label>
																<div class="col-md-8">
																	<input type="text" class="form-control calendario data"
																		name="dataini" id="dataini"
																		value="<?php echo $dataini; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-4"><?php echo lang('DATA_FINAL'); ?></label>
																<div class="col-md-8">
																	<input type="text" class="form-control calendario data"
																		name="datafim" id="datafim"
																		value="<?php echo $datafim; ?>">
																</div>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-4"></label>
																<div class="col-md-8">
																	<a href='javascript:void(0);'
																		class='btn <?php echo $core->primeira_cor; ?> buscar'
																		title='<?php echo lang('BUSCAR'); ?>'><i
																			class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
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
																	class="control-label col-md-4"><?php echo lang('PRODUTO'); ?></label>
																<div class="col-md-8">
																	<select class="form-control" id="id_produto_fornecedor"
																		name="id_produto_fornecedor"
																		data-placeholder="<?php echo lang('SELECIONE_PRODUTO'); ?>">
																	</select>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-4"><?php echo lang('FABRICANTE'); ?></label>
																<div class="col-md-8">
																	<select class="select2me form-control" id="id_fornecedor"
																		name="id_fornecedor"
																		data-placeholder="<?php echo lang('SELECIONE_FABRICANTE'); ?>">
																		<option value=""></option>
																		<?php
																		$retorno_fornecedor = $fabricante->getFabricantes();
																		if ($retorno_fornecedor):
																			foreach ($retorno_fornecedor as $frow):
																				?>
																				<option value="<?php echo $frow->id; ?>">
																					<?php echo $frow->fabricante; ?>
																				</option>
																				<?php
																			endforeach;
																		endif;
																		?>
																	</select>
																</div>
															</div>
														</div>
														<hr>
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- FINAL DO ROW FORMULARIO -->
					<!-- INICIO DO ROW TABELA -->
					<div class="row">
						<div class="col-md-12">
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-cubes font-<?php echo $core->primeira_cor; ?>"></i>
										<span class="font-<?php echo $core->primeira_cor; ?>">
											<?php if ($id_produto):
												$nome_produto = getValue("nome", "produto", "id=" . $id_produto);
												echo lang('PRODUTO') . ': ' . $nome_produto;
											endif;
											if ($id_produto && $id_fornecedor):
												echo "  |  ";
											elseif (!$id_produto && !$id_fornecedor):
												echo lang('VENDAS_PRODUTO_FABRICANTE_TITULO');
											endif;
											if ($id_fornecedor):
												$nome_fornecedor = getValue("fabricante", "fabricante", "id=" . $id_fornecedor);
												echo lang('FABRICANTE') . ': ' . $nome_fornecedor;
											endif;
											?>

										</span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('FABRICANTE'); ?></th>
												<th><?php echo lang('VENDEDOR'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('QUANTIDADE'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('DATA'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $cadastro->getVendasProdutoFornecedor($dataini, $datafim, $id_produto, $id_fornecedor);
											if ($retorno_row):
												$quantidade = 0;
												foreach ($retorno_row as $exrow):
													$quantidade += $exrow->quantidade;
													$vendedor = ($exrow->id_vendedor) ? $exrow->usuario : '-';
													?>
													<tr>
														<td><?php echo $exrow->fabricante; ?></td>
														<td><?php echo $vendedor; ?></td>
														<td><?php echo $exrow->produto; ?></td>
														<td><?php echo $exrow->quantidade; ?></td>
														<td><?php echo $exrow->cliente; ?></td>
														<td><?php echo exibedata($exrow->data_venda); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="3"></td>
													<td><strong><?php echo $quantidade; ?></strong></td>
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
		</div>
		<!-- FINAL CONTEUDO DA PAGINA -->
		<?php break; ?>
	<?php
	case "vendasconsolidadoprodutos":
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		$id_grupo = (get('id_grupo')) ? get('id_grupo') : 0;
		$monofasico = (get('monofasico')) ? get('monofasico') : 0;
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					var id_grupo = $("#id_grupo").val();
					var monofasico = $("#monofasico:checked").val();
					monofasico = (monofasico) ? monofasico : 0;
					window.location.href = 'index.php?do=vendas&acao=vendasconsolidadoprodutos&dataini=' + dataini + '&datafim=' + datafim + '&id_grupo=' + id_grupo + '&monofasico=' + monofasico;
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
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_CONSOLIDADAS_PRODUTOS'); ?></small>
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
										<i class="fa fa-list-ol font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_CONSOLIDADAS_PRODUTOS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="row">
											<div class="col-md-7">
												<input type="text" class="form-control calendario data" name="dataini"
													id="dataini" value="<?php echo $dataini; ?>">
												<input type="text" class="form-control calendario data" name="datafim"
													id="datafim" value="<?php echo $datafim; ?>">
												<select class="select2me form-select" id="id_grupo" name="id_grupo"
													data-placeholder="<?php echo lang('GRUPO'); ?>">
													<option value=""></option>
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
											<div class="col-md-2">
												<div class="md-checkbox-list">
													<div class="md-checkbox">
														<input type="checkbox" class="md-check" name="monofasico"
															id="monofasico" value="1" <?php if ($monofasico)
																echo 'checked'; ?>>
														<label for="monofasico">
															<span></span>
															<span class="check"></span>
															<span class="box"></span>
															<?php echo lang('PRODUTO_MONOFASICO_CHECK'); ?>
														</label>
													</div>
												</div>
											</div>
											<div class="col-md-1">
												<a href='javascript:void(0);'
													class='btn <?php echo $core->primeira_cor; ?> buscar'
													title='<?php echo lang('BUSCAR'); ?>'><i
														class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-barcode font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('PRODUTOS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('CODIGO'); ?></th>
												<th><?php echo lang('NCM'); ?></th>
												<th style="width: 70px"><?php echo lang('QUANT'); ?></th>
												<th style="width: 70px"><?php echo lang('VALOR'); ?></th>
												<th style="width: 70px"><?php echo lang('DESPESAS_ACESSORIAS'); ?></th>
												<th style="width: 70px"><?php echo lang('DESCONTO'); ?></th>
												<th style="width: 70px"><?php echo lang('TOTAL'); ?></th>
												<th style="width: 70px"><?php echo lang('VOUCHER_CREDIARIO'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$tquant = 0;
											$valor = 0;
											$desconto = 0;
											$despesa_acessoria = 0;
											$total = 0;
											$quant = 0;
											$custo = 0;
											$total_margem = 0;
											$retorno_row = $cadastro->getVendasConsolidadoProduto($dataini, $datafim, $id_grupo, $monofasico);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$valor += $exrow->valor;
													$despesa_acessoria += $exrow->despesa_acessoria;
													$desconto += $exrow->valor_desconto;
													$total += $exrow->valor_total;
													$quant += $exrow->quant;
													$tquant++;
													?>
													<tr>
														<td><?php echo $exrow->produto; ?></a></td>
														<td><?php echo $exrow->codigo; ?></a></td>
														<td><?php echo $exrow->ncm; ?></a></td>
														<td><?php echo $exrow->quant; ?></td>
														<td><?php echo moedap($exrow->valor); ?></td>
														<td><?php echo moedap($exrow->despesa_acessoria); ?></td>
														<td><?php echo moedap($exrow->valor_desconto); ?></td>
														<td><?php echo moedap($exrow->valor_total); ?></td>
														<td><?php echo moedap($exrow->voucher_crediario); ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="3"><strong><?php echo lang('TOTAL') . ": " . $tquant; ?></strong>
													</td>
													<td><strong><?php echo $quant; ?></strong></td>
													<td><strong><?php echo moedap($valor); ?></strong></td>
													<td><strong><?php echo moedap($despesa_acessoria); ?></strong></td>
													<td><strong><?php echo moedap($desconto); ?></strong></td>
													<td><strong><?php echo moedap($total); ?></strong></td>
													<td><strong></strong></td>
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
	case "vendastipo":
		$tipo = get('tipo');
		$pagamento = ($tipo) ? getValue('tipo', 'tipo_pagamento', 'id=' . $tipo) : lang('CREDIARIO_M');
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_CONSOLIDADAS_TIPO'); ?></small>
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
										<i class="fa fa-list-ol font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_CONSOLIDADAS_TIPO') . ': ' . $pagamento; ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('COD_VENDA'); ?></th>
												<th><?php echo lang('DATA_VENDA'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('VALOR_TOTAL'); ?></th>
												<th><?php echo lang('VENDEDOR'); ?></th>
												<th><?php echo lang('CANCELADA'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $cadastro->getVendasPagamento($tipo, $dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$total += ($exrow->inativo) ? 0 : $exrow->valor_pagamento;
													$row_pagamento = $cadastro->getFinanceiroVendaBoleto($exrow->id);
													$categoria_pagamento = ($row_pagamento) ? getValue("id_categoria", "tipo_pagamento", "id = " . $row_pagamento->tipo) : 0;
													$estilo_status = ($exrow->status_enotas == "Autorizada") ? ((!$exrow->contingencia) ? "badge bg-green" : "badge bg-blue-chambray") : (($exrow->status_enotas == "Negada") ? "badge bg-red" : (($exrow->status_enotas == "Inutilizada" || $exrow->status_enotas == "Cancelada") ? "badge bg-blue-hoki" : "badge bg-yellow"));
													$cor_fiscal = ($exrow->fiscal && $exrow->status_enotas == "Autorizada") ? 'green' : 'purple';
													?>
													<tr <?php echo ($exrow->inativo) ? "class='font-red'" : ""; ?>>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo exibedata($exrow->data_venda); ?></td>
														<td><a
																href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
														</td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pagamento); ?></span>
														</td>
														<td><?php echo $exrow->vendedor; ?></td>
														<td><?php echo ($exrow->inativo) ? "SIM" : "NAO"; ?></td>
														<td><?php echo $exrow->usuario_venda; ?></td>
														<td width="150px">
															<?php if (!$exrow->inativo): ?>
																<a href="javascript:void(0);"
																	onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																	title="<?php echo lang('VER_DETALHES'); ?>"
																	class="btn btn-sm grey-cascade btn-fiscal"><i
																		class="fa fa-search"></i></a>
																<?php if (!$exrow->fiscal || $exrow->status_enotas == "Inutilizada"): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('recibo.php?id=<?php echo $exrow->id; ?>&crediario=<?php echo $pgto_crediario; ?>','<?php echo lang('IMPRIMIR_RECIBO') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																		title="<?php echo lang('IMPRIMIR_RECIBO'); ?>"
																		class="btn btn-sm yellow-casablanca btn-fiscal"><i
																			class="fa fa-file-o"></i></a>
																<?php endif; ?>
																<?php if (($usuario->is_nfc() && $core->tipo_sistema != 2) && (!$exrow->id_nota_fiscal) && $exrow->status_enotas != "Inutilizada"): ?>
																	<?php if ($exrow->status_enotas == "Autorizada" && !$exrow->contingencia): ?>
																		<a href="<?php echo $exrow->link_danfe; ?>"
																			title="<?php echo lang('NOTA_FISCAL_DANFE'); ?>"
																			class="btn btn-sm green"><i class="fa fa-file-text-o"></i></a>
																		<?php
																		$dentroPrazoCancelamento = !(strtotime($exrow->data_emissao . ' +30 minutes') < strtotime(date('Y-m-d H:i:s')));
																		if ($dentroPrazoCancelamento):
																			?>
																			<a href="index.php?do=vendas&acao=cancelarvendafiscal&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm red btn-fiscal"
																				title="<?php echo lang('CADASTRO_APAGAR_VENDA_FISCAL') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-minus-circle"></i></a>
																			<?php
																		endif;
																		?>
																	<?php else: ?>
																		<?php if (!$exrow->cadastro): ?>
																			<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>"
																				class="btn btn-sm blue popovers btn-fiscal" data-container="body"
																				data-trigger="hover" data-placement="top"
																				data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																				data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																				title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																					class="fa fa-user"></i></a>
																		<?php endif; ?>
																		<?php if ($exrow->cadastro || $exrow->valor_total < 10000.00): ?>
																			<?php if (!$exrow->contingencia && $exrow->status_enotas != "Negada"): ?>
																				<a href="javascript:void(0);"
																					onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																					title="<?php echo lang('FISCAL_NFC'); ?>"
																					class="btn btn-sm btn-fiscal <?php echo $cor_fiscal; ?>"><i
																						class="fa fa-file-text-o"></i></a>
																				<?php if ($usuario->is_Controller()): ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC') . " [DEBUG]"; ?>"
																						class="btn btn-sm btn-fiscal <?php echo $cor_fiscal; ?>"><i
																							class="fa fa-bug"></i></a>
																				<?php endif; ?>
																			<?php else: ?>
																				<?php if ($exrow->status_enotas == "Negada"): ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>"
																						class="btn btn-sm red btn-fiscal"><i class="fa fa-file-text-o"></i></a>
																					<?php if ($usuario->is_Controller()): ?>
																						<a href="javascript:void(0);"
																							onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																							title="<?php echo lang('FISCAL_NFC_REPROCESSAR') . " [DEBUG]"; ?>"
																							class="btn btn-sm red btn-fiscal"><i class="fa fa-bug"></i></a>
																					<?php endif; ?>
																				<?php else: ?>
																					<a href="javascript:void(0);"
																						onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																						title="<?php echo lang('FISCAL_NFC_REPROCESSAR'); ?>"
																						class="btn btn-sm blue-chambray btn-fiscal"><i
																							class="fa fa-file-text-o"></i></a>
																					<?php if ($usuario->is_Controller()): ?>
																						<a href="javascript:void(0);"
																							onclick="$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&debug=1&id=<?php echo $exrow->id; ?>','<?php echo lang('FISCAL_NFC_REPROCESSAR') . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																							title="<?php echo lang('FISCAL_NFC_REPROCESSAR') . " [DEBUG]"; ?>"
																							class="btn btn-sm blue-chambray btn-fiscal"><i
																								class="fa fa-bug"></i></a>
																					<?php endif; ?>
																				<?php endif; ?>
																			<?php endif; ?>
																		<?php endif; ?>
																	<?php endif; ?>
																<?php elseif (!$exrow->cadastro): ?>
																	<a href="index.php?do=vendas&acao=adicionarclientevenda&id=<?php echo $exrow->id; ?>"
																		class="btn btn-sm blue popovers btn-fiscal" data-container="body"
																		data-trigger="hover" data-placement="top"
																		data-content="<?php echo lang('CADASTRO_CLIENTE_VENDA_TEXTO'); ?>"
																		data-original-title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"
																		title="<?php echo lang('CADASTRO_CLIENTE_VENDA') . ': ' . $exrow->id; ?>"><i
																			class="fa fa-user"></i></a>
																<?php endif; ?>
																<?php if ($exrow->entrega): ?>
																	<a href="javascript:void(0);"
																		onclick="javascript:void window.open('pdf_romaneio.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																		title="<?php echo lang('VER_ROMANEIO'); ?>"
																		class="btn btn-sm yellow-gold"><i
																			class="fa fa-truck btn-fiscal"></i></a>
																<?php endif; ?>
																<?php $modulo_boleto = getValue("modulo_emissao_boleto", "empresa", "id = " . $exrow->id_empresa);
																	if ($modulo_boleto == 1 && $categoria_pagamento == 4):
																	$banco_boleto = getValue("boleto_banco", "empresa", "id = " . $row_pagamento->id_empresa);
																	?>
																	<a href="boleto_<?php echo $banco_boleto; ?>.php?todos=1&id_pagamento=<?php echo $row_pagamento->id; ?>&id_empresa=<?php echo $row_pagamento->id_empresa; ?>"
																		target="_blank" title="<?php echo lang('GERAR_TODOS'); ?>"
																		class="btn btn-sm grey-cascade btn-fiscal"><i
																			class="fa fa-bold"></i>
																		</a>
																<?php endif; ?>
																<?php if (!$exrow->fiscal && $usuario->is_Gerencia() && !$exrow->id_nota_fiscal || $exrow->status_enotas == "Inutilizada"): ?>
																	<a href="index.php?do=vendas&acao=cancelarvenda&id=<?php echo $exrow->id; ?>&pg=2"
																		class="btn btn-sm red btn-fiscal"
																		title="<?php echo lang('CADASTRO_APAGAR_VENDA') . ': ' . $exrow->id; ?>"><i
																			class="fa fa-ban"></i></a>
																<?php endif; ?>
															<?php elseif ($exrow->link_danfe): ?>
																<a href="<?php echo $exrow->link_danfe; ?>"
																	title="<?php echo lang('NOTA_FISCAL_DANFE'); ?>"
																	class="btn btn-sm <?php echo 'green'; ?>"><i
																		class="fa fa-file-pdf-o"></i></a>
															<?php endif; ?>
															<a href="javascript:void(0);" onClick="location.reload();"
																title="<?php echo lang('RECARREGAR'); ?>"
																class="btn btn-sm btn-reload blue-madison ocultar"><i
																	class="fa fa-refresh"></i></a>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3"><span class="bold"><?php echo lang('TOTAL'); ?></td>
												<td><span class="bold"><?php echo moedap($total); ?></span></td>
												<td colspan="4"></td>
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
		<?php break; ?>
	<?php
	case "aniversariantes":
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y', strtotime('1 days'));
		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('.buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=vendas&acao=aniversariantes&dataini=' + dataini + '&datafim=' + datafim;
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
						<h1><?php echo lang('CRM'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ANIVERSARIANTES'); ?></small></h1>
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
										<i class="fa fa-calendar font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('ANIVERSARIANTES'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
											&nbsp;&nbsp;
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?> buscar'
												title='<?php echo lang('BUSCAR'); ?>'><i
													class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable">
										<thead>
											<tr>
												<th><?php echo lang('CODIGO'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th width="80px"><?php echo lang('DATA_NASCIMENTO'); ?></th>
												<th><?php echo lang('TELEFONE'); ?></th>
												<th><?php echo lang('CIDADE'); ?></th>
												<th width="210px"><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $cadastro->getAniversariantes($dataini, $datafim);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo $exrow->nome; ?></td>
														<td><?php echo exibedata($exrow->data_nasc); ?></td>
														<td><?php echo $exrow->telefone . " " . $exrow->celular; ?></td>
														<td><?php echo $exrow->cidade; ?></td>
														<td>
															<a href="javascript:void(0);" class="btn btn-sm grey-cascade"
																onclick="javascript:void window.open('ver_cadastro.php?id=<?php echo $exrow->id; ?>','<?php echo $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title="<?php echo lang('VISUALIZAR'); ?>"><i
																	class="fa fa-search"></i></a>
															<a href="javascript:void(0);" class="btn btn-sm grey-gallery retornocontato"
																id="<?php echo $exrow->id; ?>" nome="<?php echo $exrow->nome; ?>"
																telefone="<?php echo $exrow->telefone . " " . $exrow->celular; ?>"
																title="<?php echo lang('CONTATO_RETORNO'); ?>"><i
																	class="fa fa-phone"></i></a>
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
		<div id="retorno-contato" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-phone">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO'); ?>
						</h4>
						<h4 class="modal-title"><strong>
								<div id="nome"><strong></div>
						</h4>
						<h4 class="modal-title">
							<div id="telefone"></div>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="retorno_form" id="retorno_form">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<p><?php echo lang('CATEGORIA'); ?></p>
									<p>
										<select class="select2me form-control" id="id_categoria" name="id_categoria"
											data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $categoria->getCategorias();
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
									</p>
									<p><?php echo lang('RETORNO'); ?></p>
									<p>
										<select class="select2me form-control" id="id_status" name="id_status"
											data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $cadastro->getStatus();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->status; ?></option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</p>
									<p><?php echo lang('DATA_RETORNO'); ?></p>
									<p>
										<input type="text" class="form-control data calendario" name="data_retorno">
									</p>
									<p><?php echo lang('OBSERVACAO'); ?></p>
									<p>
										<input type="text" class="form-control caps" name="observacao">
									</p>
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
			<?php echo $core->doForm("processarCadastroRetorno", "retorno_form"); ?>
		</div>
		<?php break; ?>
	<?php
	case "vendasproduto":
		$opcao = (get('opcao')) ? get('opcao') : 0;
		?>
		<script type="text/javascript">
			$(document).ready(function () {
				$('#opcao').change(function () {
					var opcao = $("#opcao").val();
					window.location.href = 'index.php?do=vendas&acao=vendasproduto&opcao=' + opcao;
				});
			});
		</script>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('CRM'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('MENU_PRODUTOS'); ?></small>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_PORPRODUTO'); ?></small></h1>
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
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_PORPRODUTO'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="row">
										<div class="col-md-3 col-sm-12">
											<div class='row'>
												<div class='form-group'>
													<div class='col-md-12'>
														<select class='select2me form-control' id='opcao'
															data-placeholder='<?php echo lang('SELECIONE_OPCAO'); ?>'>
															<option value=""></option>
															<?php
															$retorno_row = $produto->getProdutos();
															if ($retorno_row):
																foreach ($retorno_row as $srow):
																	?>
																	<option value='<?php echo $srow->id; ?>' <?php if ($srow->id == $opcao)
																		   echo 'selected="selected"'; ?>>
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
										</div>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance">
										<thead>
											<tr>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('TELEFONE'); ?></th>
												<th width="80px"><?php echo lang('VALOR'); ?></th>
												<th><?php echo lang('DATA'); ?></th>
												<th><?php echo lang('USUARIO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $cadastro->getVendasProduto($opcao);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo $exrow->cadastro; ?></td>
														<td><?php echo $exrow->telefone . " " . $exrow->celular; ?></td>
														<td><?php echo moedap($exrow->valor_total); ?></td>
														<td><?php echo exibedata($exrow->data); ?></td>
														<td><?php echo $exrow->usuario; ?></td>
														<td>
															<a href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"
																class="btn btn-sm <?php echo $core->primeira_cor; ?>"
																title="<?php echo lang('IR_PARA') . ": " . $exrow->endereco; ?>"><i
																	class="fa fa-share"></i></a>
															<a href="javascript:void(0);"
																onclick="javascript:void window.open('imprimir_detalhes_item.php?id=<?php echo $exrow->id; ?>','<?php echo "PRODUTO: " . $exrow->item; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title="" class="btn btn-sm grey-cascade"><i
																	class="fa fa-search"></i></a>
															<a href="javascript:void(0);" class="btn btn-sm grey-cascade"
																onclick="javascript:void window.open('ver_cadastro.php?id=<?php echo $exrow->id_cadastro; ?>','<?php echo $exrow->id_cadastro; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title="<?php echo lang('VISUALIZAR'); ?>"><i
																	class="fa fa-search"></i></a>
															<a href="javascript:void(0);" class="btn btn-sm grey-gallery retornocontato"
																id="<?php echo $exrow->id_cadastro; ?>"
																nome="<?php echo $exrow->cadastro; ?>"
																telefone="<?php echo $exrow->telefone . " " . $exrow->celular; ?>"
																title="<?php echo lang('CONTATO_RETORNO'); ?>"><i
																	class="fa fa-phone"></i></a>
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
		<div id="retorno-contato" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-phone">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO'); ?>
						</h4>
						<h4 class="modal-title"><strong>
								<div id="nome"><strong></div>
						</h4>
						<h4 class="modal-title">
							<div id="telefone"></div>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="retorno_form" id="retorno_form">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<p><?php echo lang('CATEGORIA'); ?></p>
									<p>
										<select class="select2me form-control" id="id_categoria" name="id_categoria"
											data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $categoria->getCategorias();
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
									</p>
									<p><?php echo lang('RETORNO'); ?></p>
									<p>
										<select class="select2me form-control" id="id_status" name="id_status"
											data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $cadastro->getStatus();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->status; ?></option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</p>
									<p><?php echo lang('DATA_RETORNO'); ?></p>
									<p>
										<input type="text" class="form-control data calendario" name="data_retorno">
									</p>
									<p><?php echo lang('OBSERVACAO'); ?></p>
									<p>
										<input type="text" class="form-control caps" name="observacao">
									</p>
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
			<?php echo $core->doForm("processarCadastroRetorno", "retorno_form"); ?>
		</div>
		<?php break; ?>
	<?php
	case "novavenda":
		$id_unico = $_SESSION['id_unico'] . '_' . $_SESSION['uid'] . '_' . date("YmdHis");
		?>
		<style>
			#cpf_cnpj_modal {
				width: 100%;
				padding: 8px;
			}

			#sugestoes {
				position: absolute;
				border: 1px solid #ccc;
				background-color: #fff;
				max-height: 150px;
				overflow-y: auto;
				width: calc(100% - 16px);
				margin-top: 4px;
				box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
				z-index: 1000;
				display: none;
			}

			.sugestao {
				padding: 8px;
				cursor: pointer;
				font-size: 14px;
			}

			.sugestao:hover {
				background-color: #f0f0f0;
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function () {
				$('#cod_barras').focus().select();
			});

			async function buscarCpfCnpj() {
				let cpfcnpj = document.querySelector("#cpf_cnpj_modal").value
				cpfcnpj = cpfcnpj.replace(/[./-]/g, '')

				if (cpfcnpj.length > 0) {
					const response = await fetch(`./webservices/buscarcpfcnpj.php?query=${cpfcnpj}`, {
						method: "GET",
						headers: {
							"Content-Type": "application/json"
						}
					})
					if (!response.ok) throw new Error("Erro na consulta")

					const data = await response.json()
					mostrarSugestoes(data)
				}
			}

			function mostrarSugestoes(data) {
				const sugestoes = document.getElementById('sugestoes')
				sugestoes.innerHTML = ''

				if (data.length > 0) {
					sugestoes.style.display = 'block'

					data.forEach(cliente => {
						const div = document.createElement('div')

						div.className = 'sugestao'
						div.innerHTML = `
							${cliente.nome}</br><b>
							${cliente.cpf_cnpj}</b></br>
							${cliente.telefone}</br>
							${cliente.celular}</br>
							${cliente.endereco}</br> _________________________________`
						div.onclick = () => preencherCamposCliente(cliente)

						sugestoes.appendChild(div)
					})
				}
			}

			async function preencherCamposCliente(cliente) {
				document.getElementById('sugestoes').style.display = 'none'

				const nome = document.getElementById("cadastro")
				const cpfCnpj = document.getElementById("cpf_cnpj_modal")
				const celular = document.getElementById("celular")

				nome.value = cliente.nome
				cpfCnpj.value = cliente.cpf_cnpj
				celular.value = cliente.celular

				if (cliente.status == 1) {
					validaStatus(cliente)
				}
				$('.selecionado').removeClass("ocultar");
				$('.selecionado').addClass("mostrar");

				const data = await clienteCrediario(cliente.cpf_cnpj.replace(/[./-]/g, ''))

				if (data[0].crediarioSistema && data[0].crediario > 0) {
					$('.btncrediario').removeClass("ocultar");
					$('.btncrediario').addClass("mostrar");
					$('.devendo').removeClass("ocultar");
					$('.devendo').addClass("mostrar devendo");
				} else {
					$('.btncrediario').removeClass("mostrar");
					$('.btncrediario').addClass("ocultar");
					$('.devendo').removeClass("mostrar ");
					$('.devendo').addClass("ocultar");
				}

				let id_cadastro = cliente.id_cadastro
				$('#id_cadastro').val(id_cadastro);
				$('#id_cadastro_form').val(id_cadastro);

				clienteExistente(id_cadastro)
			}

			function validaStatus(cliente) {
				const nome = document.getElementById("cadastro")
				const cpfCnpj = document.getElementById("cpf_cnpj_modal")
				const celular = document.getElementById("celular")

				if (confirm("Cliente está desativado, deseja reativa-lo")) {
					return reativarCadastro(cliente.id_cadastro)
				}

				nome.value = ''
				cpfCnpj.value = ''
				celular.value = ''
			}

			async function reativarCadastro(id) {
				const response = await fetch(`./webservices/reativarcadastropdv.php?id_cadastro=${id}`, {
					method: "POST",
					headers: {
						"Content-Type": "application/json"
					}
				})

				if (!response.ok) {
					throw new Error("Erro na consulta")
				}
			}

			async function clienteCrediario(cpfcnpj) {
				const response = await fetch(`./webservices/listar_cadastro.php?query=${cpfcnpj}`, {
					method: "GET",
					headers: {
						"Content-Type": "application/json"
					}
				})
				if (!response.ok) throw new Error("Erro na consulta")

				const data = await response.json()
				return data
			}

			document.addEventListener("click", (event) => {
				if (!event.target.closest("#cpf_cnpj_modal")) {
					document.getElementById("sugestoes").style.display = "none";
				}
			})
		</script>
		<?php if (empty($id_unico) || strlen($id_unico)<31): ?>
			<script type="text/javascript">
				$(document).ready(function () {
					$('#modal_aviso_id_unico').modal();

					$('#modal_aviso_id_unico').on('hide.bs.modal', function (event) {
						location.href = "login.php";
					});
				});
			</script>
			<div class="modal fade" id="modal_aviso_id_unico" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
				<div class="">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title" id=""><strong>AVISO IMPORTANTE</strong></h5>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<div class="alert alert-warning">
											<h4 class="block"><?php echo lang('VENDA_FALHA_TITULO'); ?></h4>
											<h5><?php echo lang('VENDA_FALHA_DESCRICAO'); ?></h5>
											<h5><?php echo "[$id_unico]"; ?></h5>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn default" data-dismiss="modal"><?php echo lang('SAIR'); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php endif; ?>
		<?php if ($faturamento->verificaCaixa($usuario->uid) == 0): ?>
			<script type="text/javascript">
				$(document).ready(function () {
					$('#modal_aviso_caixa').modal();
				});
			</script>
			<div class="modal fade" id="modal_aviso_caixa" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
				<div class="">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title" id=""><strong>AVISO</strong></h5>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<div class="alert alert-warning">
											<h4 class="block"><?php echo lang('CAIXA_VENDA_AVISO'); ?></h4>
											<h5><?php echo lang('CAIXA_VENDA_AVISO_DESCRICAO'); ?></h5>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="index.php?do=caixa&acao=adicionar"
									class="btn btn-warning"><?php echo lang('ABRIR_CAIXA'); ?></a>
								<button type="button" class="btn default" data-dismiss="modal"><?php echo lang('SAIR'); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="page-container" style="overflow: hidden; width: 100%">
			<form action="" autocomplete="off" method="post" class="horizontal-form" name="admin_form" id="admin_form">
				<div class="page-content">
					<div class="" style="height: 450px; margin: 20px;"> <!-- container -->
						<div class="portlet-body form">
							<div class="col-md-12">
								<div class="dados_cliente ocultar">
									<div>
										<span id="cliente_novo" class="label label-danger ocultar">Novo</span>
										<span id="cliente_existente" class="label label-success ocultar">Existente</span>
										<span id="cliente_devendo"
											class="label label-danger devendo ocultar"><?php echo lang('CLIENTE_DEVENDO'); ?></span>
										<div id="resposta_cliente"></div>
										<div id="resposta_celular"></div>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-md-2">
										<label class="control-label"><?php echo lang('TABELA_PRECO'); ?></label>
										<div class="">
											<select class="form-control input-sm" id="id_tabela_venda" name="id_tabela">
												<?php
												$retorno_row = $produto->getTabelaNivel($usuario->nivel);
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>"
															desconto="<?php echo $srow->desconto; ?>"><?php echo $srow->tabela; ?>
														</option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
									</div>
									<div class="form-group col-md-4">
										<label class="control-label"><?php echo lang('CODIGO_DE_BARRAS'); ?></label>
										<div class="">
											<div class="input-group">
												<input type="text" class="form-control barcode input-sm" id="" name="cod_barras"
													autofocus placeholder="Código de barras">
												<span class="input-group-addon"><?php echo lang('TECLE_ENTER'); ?></span>
											</div>
											<small class="help-block"><?php echo lang('ATALHO_F2') ?></small>
										</div>
									</div>
									<div class="form-group col-md-3">
										<label class="control-label">
											<?php echo lang('VENDEDOR'); ?>
										</label>
										<div>
											<select class="select2me form-control input-sm" name="id_vendedor" id="id_vendedor"
												data-placeholder="<?php echo lang('SELECIONE_VENDEDOR'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $usuario->getVendedor();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $usuario->uid)
															   echo 'selected="selected"'; ?>><?php echo strtoupper($srow->usuario); ?>
														</option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
										<small class="help-block" style="color: #f00; font-size: 11px">* Obrigatório se for um
											orçamento.</small>
									</div>
									<div class="form-group col-md-3">
										<label class="control-label"><?php echo lang('PRAZO_ENTREGA'); ?></label>
										<div>
											<input type="text" class="form-control input-sm data calendario"
												name="prazo_entrega" id="prazo_entrega">
											<small class="help-block"><?php echo lang('AVISO_ROMANEIO'); ?></small>
										</div>
									</div>
								</div>
							</div>
							<div class="tabelas_pagamento_produto">
								<div class="col-md-8" id="tabela_produto_novavenda">
									<div class="row">
										<div class="col-md-12">
											<div class="table-scrollable table-scrollable-borderless"
												style=" height: 310px; overflow-y: scroll; border-radius: 5px">
												<table class="table table-hover table-advance">
													<thead>
														<tr style="
													position: sticky;
													top: 0;
													opacity: 0.95;
												">
															<th width="200px"><?php echo lang('PRODUTO'); ?></th>
															<th width="30px"><?php echo lang('UNIDADE'); ?></th>
															<th width="50px"><?php echo lang('ESTOQUE'); ?></th>
															<th width="50px"><?php echo lang('QUANTIDADE'); ?></th>
															<th width="70px"><?php echo lang('VALOR'); ?></th>
															<th width="70px"><?php echo lang('TOTAL'); ?></th>
															<th width="90px"><?php echo lang('ACOES'); ?></th>
														</tr>
													</thead>
													<tbody id="tabela_produtos"></tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4" id="tabela_pagamento_novavenda">
									<div class="row">
										<div class="col-md-12">
											<div class="table-scrollable table-scrollable-borderless"
												style="height: 310px; overflow-y: scroll;border-radius: 5px">
												<table class="table table-hover table-advance-green">
													<thead>
														<tr style="
													position: sticky;
													top: 0;
													opacity: 0.95;
												">
															<th width="20%"><?php echo lang('PAGAMENTO'); ?></th>
															<th width="10%"><?php echo lang('PARCELAS'); ?></th>
															<th width="20%"><?php echo lang('VALOR_PAGO'); ?></th>
															<th width="5%"><?php echo lang('ACOES'); ?></th>
														</tr>
													</thead>
													<tbody id="tabela_pagamentos"></tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="foorter-buttons">
					<div class="row" id="row_footer_buttons">
						<div class="col-md-3" style="width: 145px;">
							<button type="button" class="btn yellow-saffron modal_adicionar_produto" data-toggle="modal"
								data-target="#modal_produtos">
								<i class="fa fa-plus-square">&nbsp;</i>
								<?php echo lang('PRODUTOS'); ?> (F1)
							</button>
						</div>
						<div class="col-md-3" style="width: 190px;">
							<button type="button" class="btn red-thunderbird" data-toggle="modal" data-target="#modal_cliente">
								<i class="fa fa-plus-square">&nbsp;</i>
								<?php echo lang('CLIENTE_ADICIONAR'); ?> (F3)
							</button>
						</div>
						<div class="col-md-3" style="width: 155px;">
							<button type="button" class="btn blue-soft modal_adicionar_pagamento" data-toggle="modal"
								data-target="#modal_pagamentos">
								<i class="fa fa-usd">&nbsp;</i>
								<?php echo lang('PAGAMENTO_TITULO'); ?> (F4)
							</button>
						</div>
						<div class="col-md-3" style="width: 140px;">
							<button type="button" class="btn btn-submit green-seagreen btn_finalizar">
								<i class="fa fa-check-square-o">&nbsp;</i>
								<?php echo lang('FINALIZAR'); ?> (F6)
							</button>
						</div>
						<?php if ($usuario->is_nfc() && $core->tipo_sistema != 2): ?>
							<div class="col-md-3" style="width: 185px;">
								<a href="javascript:void(0)" class="btn purple btnvenda_nfc">
									<i class="fa fa-check-square-o">&nbsp;</i>
									<?php echo lang('FINALIZAR_COM_NFC'); ?> (F7)
								</a>
							</div>
						<?php endif; ?>
						<?php if ($usuario->is_VendaAberto() && $core->tipo_sistema != 1): ?>
							<div class="col-md-3" style="width: 130px;">
								<button type="button" class="btn btn-protect btnsalvar <?php echo $core->primeira_cor; ?>">
									<i class="fa fa-save">&nbsp;</i>
									<?php echo lang('SALVAR'); ?> (F8)
								</button>
							</div>
						<?php endif; ?>
						<?php if ($usuario->is_Orcamento() && $core->tipo_sistema != 1): ?>
							<div class="col-md-4" style="width: 165px;">
								<button type="button" class="btn btn-protect btn-salvar-orcamento"
									style="color: #fff; background-color: #d63384 ;">
									<i class="fa fa-save">&nbsp;</i>
									<?php echo lang('SALVAR_ORCAMENTO'); ?>
								</button>
							</div>
						<?php endif; ?>
						<div class="col-md-3" style="width: 150px;">
							<button type="button" class="btn btn-protect yellow-casablanca btncrediario ocultar">
								<i class="fa fa-money">&nbsp;</i>
								<?php echo lang('FINALIZAR_FICHA'); ?> (F10)
							</button>
						</div>
					</div>
				</div>

				<input type="hidden" name="valor" id="valor">
				<input type="hidden" name="id_cadastro" id="id_cadastro_form">
				<input type="hidden" name="cpf_cnpj" id="cpf_cnpj_form">
				<input type="hidden" name="cadastro" id="cadastro_form">
				<input type="hidden" name="celular" id="celular_form">
				<input type="hidden" name="observacao" id="observacao_form">

				<input type="hidden" name="data_boleto" id="data_boleto" class="form-control data calendario">
				<input type="hidden" name="valor_desconto" id="valor_desconto_rapida" class="form-control moeda">
				<input type="hidden" name="descporcentagem" id="valor_desconto_porcentagem" class="form-control desconto">
				<input type="hidden" name="valor_acrescimo" id="valor_acrescimo_rapida" class="form-control moeda">
				<input type="hidden" id="valor_pago" class="form-control moeda valor_pago_venda">
				<input type="hidden" id="parcelas" class="form-control inteiro">

				<input type="hidden" id="modal_cancelar_produto_venda"
					value="<?php echo $core->modal_cancelar_produto_venda; ?>">
				<input type="hidden" id="modal_alterar_valor_produto_venda"
					value="<?php echo $core->modal_alterar_valor_produto_venda; ?>">
				<input type="hidden" id="case-novavenda" value="<?php echo Filter::$acao ?>" />
				<input type="hidden" name="id_unico" id="id_unico" value="<?php echo $id_unico; ?>" />

				<?php echo $core->doForm("processarNovaVenda"); ?>
		</div>
		</form>
		<div class="modal fade" id="modal_cliente" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
			<div class="">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title" id=""><strong><?php echo lang('CLIENTE_ADICIONAR') ?></strong></h5>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<span class="help-block pull-right"><?php echo lang('ATALHO_ENTER') ?></span>
								<label class="control-label"><?php echo lang('CLIENTE'); ?>:</label>
								<div>
									<input name="id_cadastro" id="id_cadastro" type="hidden" />
									<input name="cpf_cnpj" id="cpf_cnpj" type="hidden" />
									<input type="text" autocomplete="off" class="form-control caps listar_cliente pular"
										name="cadastro" id="cadastro" placeholder="Digite aqui o nome ou razão social">
								</div>
								<div class="row selecionado ocultar">
									<div class="form-group">
										<div class="col-md-9">
											<span class="label label-success label-sm"><?php echo lang('SELECIONADO'); ?></span>
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
								<label class="control-label"><?php echo lang('CPF_CNPJ'); ?>:</label>
								<div>
									<input type="text" autocomplete="off" class="form-control cpf_cnpj pular"
										id="cpf_cnpj_modal" name="cpf_cnpj" placeholder="Digite aqui o CPF ou CNPJ">
									<div id="sugestoes"></div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label"><?php echo lang('CELULAR'); ?>:</label>
								<div>
									<input type="text" autocomplete="off" class="form-control celular pular" id="celular"
										name="celular">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label"><?php echo lang('OBSERVACAO'); ?>:</label>
								<div>
									<textarea class="form-control caps pular" name="observacao" id="observacao_modal"
										maxlength="250"></textarea>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" onclick="buscarCpfCnpj()"
								class="btn salvar_cliente_modal <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" class="btn default sair_cliente_modal"
								data-dismiss="modal"><?php echo lang('SAIR'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modal_produtos" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
			<div class="vertical-alignment-helper" style="min-width:90%;">
				<div class="modal-dialog vertical-align-center modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title" id=""><strong><?php echo lang('PRODUTOS') ?></strong></h5>
						</div>
						<div class="modal-body">
							<div class=row>
								<div class="col-md-12">
									<div class="form-group">
										<div class="col-md-6">
											<div id="showProductInput"></div>
										</div>
										<div class="col-md-3">
											<div id="showQuantidadeInput"></div>
										</div>
										<div class="col-md-3">
											<div id="showPriceProductInput"></div>
										</div>
									</div>
								</div>
							</div>
							<div id="showProduct"></div>
						</div>
						<div class="modal-footer">
							<span class="help-block pull-left">Aperte "ESC" para sair.</span>
							<button type="button" class="btn default" data-dismiss="modal"><?php echo lang('SAIR'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="modal_pagamentos" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog vertical-align-center" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title" id=""><strong><?php echo lang('TIPO_PAGAMENTO') ?></strong></h5>
						</div>
						<div class="modal-body">
							<select name="tipopagamento" id="tipopagamento" multiple class="form-control selectPayment"
								style=" height: 200px; margin-top: 10px; " autofocus>
								<?php
								$retorno_row = $faturamento->getTipoPagamento();
								if ($retorno_row):
									foreach ($retorno_row as $srow):
										?>
										<option class="todos_pagamentos" value="<?php echo $srow->id; ?>"
											avista="<?php echo $srow->avista; ?>" id_categoria="<?php echo $srow->id_categoria; ?>"
											<?php echo ($srow->id == 1) ? 'selected' : '' ?>>
											<?php echo $srow->tipo; ?>
										</option>
										<?php
									endforeach;
									unset($srow);
								endif;
								?>
							</select>
							<span class="help-block" style="margin-bottom:40px">
								<?php echo lang('ATALHO_MODALPAG_ALT_F'); ?>
								&emsp;&emsp;&emsp;
								<?php echo lang('INFORMACAO_FORMA_PAGAMENTO'); ?>
							</span>
							<div class="row valores_pagamento mostrar">
								<div class="col-md-4">
									<div class="input-group">
										<span class="input-group-addon">%</span>
										<input type="text" class="form-control desconto" name="descporcentagem"
											id="valor_desconto_porcentagem_modal" placeholder="Desconto" data-prefix="%">
									</div>
									<span class="help-block"><?php echo lang('ATALHO_MODALPAG_ALT_S'); ?></span>
								</div>
								<div class="col-md-4">
									<div class="input-group">
										<span class="input-group-addon">R$</span>
										<input type="text" class="form-control moeda" name="valor_desconto"
											id="valor_desconto_modal" placeholder="Desconto">
									</div>
									<span class="help-block"><?php echo lang('ATALHO_MODALPAG_ALT_D'); ?></span>
								</div>
								<div class="col-md-4">
									<div class="input-group">
										<span class="input-group-addon">R$</span>
										<input type="text" class="form-control moeda" name="valor_acrescimo"
											id="valor_acrescimo_modal" placeholder="Acrescimo (frete, taxas, etc)">
									</div>
									<span class="help-block"><?php echo lang('ATALHO_MODALPAG_ALT_A'); ?></span>
								</div>
								<div class="col-md-4">
									<input autocomplete="off" type="text" class="form-control inteiro" name=""
										id="parcelas_modal" placeholder="Parcelas">
									<span class="help-block"><?php echo lang('ATALHO_MODALPAG_ALT_P'); ?></span>
								</div>
								<div class="col-md-4">
									<div class="input-group">
										<span class="input-group-addon">R$</span>
										<input type="text" class="form-control moeda valor_pago_venda" name="valor_pago_modal"
											id="valor_pago_modal" placeholder="Valor a pagar">
										<input name="valor_pagar_modal_pgto" id="valor_pagar_modal_pgto" type="hidden" />
									</div>
									<span class="help-block"><?php echo lang('ATALHO_MODALPAG_ALT_V'); ?></span>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control data calendario" name="data_boleto"
										id="data_boleto_modal"
										value="<?php echo date('d/m/Y', strtotime(date("Y-m-d") . ' + 2 days')); ?>">
									<span id="span_data_boleto_modal"
										class="help-block"><?php echo lang('PRIMEIRA_PARCELA_BOLETO'); ?></span>
								</div>
							</div>
							<!-- <hr style="margin: 20px 0;"> -->
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default pull-left"
								data-dismiss="modal"><?php echo lang('SAIR'); ?></button>
							<div class="infos-modal valores_pagamento mostrar">
								<button type="button"
									class="btn btn-primary adicionar_pagamento_pdv"><?= lang('PAGAMENTO_ADICIONAR'); ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if ($core->modal_cancelar_produto_venda): ?>
			<div class="modal fade" id="modal_cancelamento_produto" tabindex="-1" role="dialog" aria-labelledby=""
				aria-hidden="true">
				<div class="vertical-alignment-helper">
					<div class="modal-dialog vertical-align-center" role="document">
						<div class="modal-content">
							<div class="modal-header" style="background-color: #f3565d; color: #67001A;">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title" id=""><strong><?php echo lang('CANCELAMENTO_PRODUTO') ?></strong></h5>
							</div>
							<form autocomplete="off">
								<div class="modal-body">
									<div class="alert alert-danger">
										<h4><?php echo lang('ATENCAO'); ?></h4>
										<h5><?php echo lang('AVISO_CANCELAMENTO_PRODUTO'); ?></h5>
									</div>
									<div class="row">
										<div class="form-group">
											<label class="col-md-2 control-label"><?php echo lang('PIN'); ?></label>
											<div class="col-md-10">
												<div class="input-group">
													<input type="password" class="form-control" name="pinUserCancel"
														id="pinUserCancel" autocomplete="off">
													<span class="input-group-addon"><?php echo lang('TECLE_ENTER') ?></span>
												</div>
												<small class="hidden info-pin-incorreto" style="color: #f00; font-weight: bold">O
													PIN está incorreto.</small>
											</div>
										</div>
									</div>

								</div>
								<div class="modal-footer">
									<button type="button" class="pull-left btn default"
										data-dismiss="modal"><?php echo lang('SAIR'); ?></button>
									<button type="button" class="btn btn-danger btn-form-cancelamento"
										style="color: #67001A"><?php echo lang('CANCELAR_CONFIRMAR'); ?></button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ($core->modal_alterar_valor_produto_venda): ?>
			<div class="modal fade" id="modal_alterar_valorvenda_por_pin" tabindex="-1" role="dialog" aria-labelledby=""
				aria-hidden="true">
				<div class="vertical-alignment-helper">
					<div class="modal-dialog vertical-align-center" role="document">
						<div class="modal-content">
							<div class="modal-header" style="background-color: #EECB00; color: #675800;">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title" id=""><strong><?php echo lang('ORCAMENTO_VALOR_PRODUTO_MUDAR') ?></strong>
									</h5>
							</div>
							<form autocomplete="off">
								<div class="modal-body">
									<div class="alert alert-warning">
										<h4><?php echo lang('ATENCAO'); ?></h4>
										<h5><?php echo lang('AVISO_ALTERACAO_VALOR_PRODUTO'); ?></h5>
									</div>
									<div class="row">
										<div class="form-group">
											<label class="col-md-2 control-label"><?php echo lang('PIN'); ?></label>
											<div class="col-md-10">
												<div class="input-group">
													<input type="password" class="form-control" name="pinUserAlter"
														id="pinUserAlter" autocomplete="off">
													<span class="input-group-addon"><?php echo lang('TECLE_ENTER') ?></span>
												</div>
												<small class="hidden info-pin-incorreto" style="color: #f00; font-weight: bold">O
													PIN está incorreto.</small>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="pull-left btn default"
										data-dismiss="modal"><?php echo lang('SAIR'); ?></button>
									<button type="button" class="btn btn-form-alterar-valorvenda"
										style="background-color: #EECB00; color: #675800;"><?php echo lang('ALTERACAO_CONFIRMAR'); ?></button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		</div>
		<?php break; ?>
	<?php
	case "finalizarvenda":
		$row = Core::getRowById("vendas", Filter::$id);
		$orcamento = (int) (getValue('orcamento', 'vendas', 'id=' . $row->id));

		if ($core->tipo_sistema==1 || $row->inativo || $orcamento==1)
			redirect_to("login.php");

		if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif;

		$valor_restante = 0;
		$nomecliente = ($row->id_cadastro) ? getValue('nome', 'cadastro', 'id=' . $row->id_cadastro) : "";
		$id_tabela = $cadastro->getTabelaVenda(Filter::$id);
		$seTabelaForAtiva = checkActive("tabela_precos", "id=$id_tabela");
		$nome_tabela = getValue("tabela", "tabela_precos", "id=" . $id_tabela . " AND inativo = 0");
		$tipo_pagamento = getValue("tipo", "cadastro_financeiro", "id_venda=" . Filter::$id . " AND inativo = 0");
		$tipo_pagamento = ($tipo_pagamento > 0) ? $tipo_pagamento : 0;
		$total_vendas = $cadastro->getTotalVenda(Filter::$id);
		$total_pagamento = floatval($cadastro->getTotalFinanceiro(Filter::$id));
		$valor_final_venda = floatval($total_vendas->valor_final);
		$total_vendas->soma_total = round($total_vendas->soma_total, 2);
		$total_vendas->valor_desconto = round($total_vendas->valor_desconto, 2);
		$total_vendas->valor_despesa_acessoria = round($total_vendas->valor_despesa_acessoria, 2);
		$total_vendas->troco = round($total_vendas->troco, 2);
		$total_pagamento = round($total_pagamento, 2); //vem do cad_financeiro
		$soma_total_venda = $total_vendas->soma_total + $total_vendas->valor_despesa_acessoria - $total_vendas->valor_desconto;
		$soma_total_venda = floatval($soma_total_venda);
		$soma_total_venda = round($soma_total_venda, 2);
		$valor_restante = ($total_vendas->soma_total + $total_vendas->valor_despesa_acessoria - $total_vendas->valor_desconto) - $total_pagamento;

		if ($valor_restante <= 0 && $total_vendas->troco > 0) {
			$valor_restante = 0;
		}

		// $pagamento_ok = ($soma_total_venda == $total_pagamento);
		$pagamento_ok = (($soma_total_venda < $total_pagamento && $total_vendas->troco > 0) || ($soma_total_venda == $total_pagamento));
		$dataHoje = date("Y-m-d");

		if ($row->id_cadastro > 0) {
			$cadastro->atualizarCadastroVenda(Filter::$id, $row->id_cadastro);
		}

		$valorTotalAVista = 0;
		$valorTotalNormal = 0;
		$produtosVenda = $cadastro->getValoresProdutosVenda(Filter::$id, $id_tabela);
		if ($produtosVenda) {
			foreach ($produtosVenda as $produtoVenda) {
				if (round($produtoVenda->valor, 2) != round($produtoVenda->valor_avista, 2) && round($produtoVenda->valor, 2) != round($produtoVenda->valor_tabela, 2)) {
					$valorTotalAVista += round($produtoVenda->valor * $produtoVenda->quantidade, 2);
					$valorTotalNormal += round($produtoVenda->valor * $produtoVenda->quantidade, 2);
				} else {
					$valorTotalNormal += round($produtoVenda->valor_tabela * $produtoVenda->quantidade, 2);
					if (round($produtoVenda->valor_avista, 2) > 0) {
						$valorTotalAVista += round($produtoVenda->valor_avista * $produtoVenda->quantidade, 2);
					} else {
						$valorTotalAVista += round($produtoVenda->valor_tabela * $produtoVenda->quantidade, 2);
					}
				}
			}
		}

		$pagamentoAVista = $cadastro->vendaComPagamentosAVista(Filter::$id);

		?>
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#cod_barras').focus();
			});
			// ]]>
		</script>
		<div id="alterar-dados" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i
								class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ALTERAR_INFORMACOES'); ?>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="dados_form" id="dados_form" class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('OBSERVACAO'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control caps pular" name="observacao"
												value="<?php echo ($total_vendas->observacao); ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('PRAZO_ENTREGA'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control calendario data" name="prazo_entrega"
												value="<?php echo (exibedata($total_vendas->prazo_entrega) != "-") ? exibedata($total_vendas->prazo_entrega) : ""; ?>">
										</div>
									</div>
								</div>
							</div>
						</div>
						<input name="id_venda" type="hidden" value="<?php echo Filter::$id; ?>" />
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarAtualizarDadosVenda", "dados_form"); ?>
		</div>
		<div id="alterar-desconto" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i
								class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ALTERAR_DESCONTO'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="desconto_form" id="desconto_form"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('DESCONTOS_ATUAL'); ?></label>
										<div class="col-md-9">
											<input readonly type="text" class="form-control moedap" id="valor_venda"
												value="<?php echo moedap($total_vendas->valor_desconto); ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('DESCONTOS_NOVO'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control moedap" id="novo_desconto"
												name="valor_desconto">
										</div>
									</div>
									<div class="form-group">
										<label
											class="control-label col-md-3"><?php echo lang('DESCONTOS_PORCENTAGEM'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control desconto" id="desc_porcentagem"
												name="desc_porcentagem">
										</div>
									</div>
								</div>
							</div>
						</div>
						<input name="id_venda" type="hidden" value="<?php echo Filter::$id; ?>" />
						<input name="id_cadastro" type="hidden" value="<?php echo $row->id_cadastro; ?>" />
						<input name="tipo_pagamento" type="hidden" value="<?php echo $tipo_pagamento; ?>" />
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarAtualizarDescontoVenda", "desconto_form"); ?>
		</div>

		<div id="alterar-acrescimo" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i
								class="fa fa fa-dollar">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ALTERAR_ACRESCIMO'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="acrescimo_form" id="acrescimo_form"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('ACRESCIMOS_ATUAL'); ?></label>
										<div class="col-md-9">
											<input readonly type="text" class="form-control moedap" id="valor_venda"
												value="<?php echo moedap($total_vendas->valor_despesa_acessoria); ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('ACRESCIMOS_NOVO'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control moedap" id="novo_acrescimo"
												name="valor_despesa_acessoria">
										</div>
									</div>
								</div>
							</div>
						</div>
						<input name="id_venda" type="hidden" value="<?php echo Filter::$id; ?>" />
						<input name="id_cadastro" type="hidden" value="<?php echo $row->id_cadastro; ?>" />
						<input name="tipo_pagamento" type="hidden" value="<?php echo $tipo_pagamento; ?>" />
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarAtualizarAcrescimoVenda", "acrescimo_form"); ?>
		</div>

		<div id="editar-produto-aberto" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i
								class="fa fa fa-shopping-cart">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_EDITAR_PRODUTO_ABERTO'); ?>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="editar_produto_aberto_form"
						id="editar_produto_aberto_form" class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
										<div class="col-md-9">
											<input readonly type="text" id="nome_produto_aberto" class="form-control">
										</div>
									</div>
										<div class="form-group">
											<label class="control-label col-md-3"><?php echo lang('VALOR'); ?></label>
											<div class="col-md-9">
												<input type="text" class="form-control moedap" id="valor_produto_aberto"
													name="valor_produto_aberto">
											</div>
										</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('QUANT'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control decimalp" id="quantidade_produto_aberto"
												name="quantidade_produto_aberto">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('DESCONTO'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control moedap" id="desconto_produto_aberto"
												name="desconto_produto_aberto">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('ACRESCIMO'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control moedap" id="acrescimo_produto_aberto"
												name="acrescimo_produto_aberto">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('VL_TOTAL'); ?></label>
										<div class="col-md-9">
											<input readonly type="text" class="form-control" id="total_produto_aberto"
												name="total_produto_aberto">
										</div>
									</div>
								</div>
							</div>
						</div>
						<input name="id_venda_aberto" id="id_venda_aberto" type="hidden" />
						<input name="id_cadastro_vendas_aberto" id="id_cadastro_vendas_aberto" type="hidden" />
						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarAtualizarProdutoVendaAberto", "editar_produto_aberto_form"); ?>
		</div>

		<div id="novo-produto" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i
								class="fa fa-barcode">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ADICIONAR_PRODUTO'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="produto_form" id="produto_form"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('TABELA_PRECO'); ?></label>
										<div class="col-md-9">
											<input readonly type="text" class="form-control"
												value="<?php echo $nome_tabela; ?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
										<div class="col-md-9">
											<select class="select2me form-control" id="id_produto_vendas_fv" name="id_produto"
												data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<option value=""></option>
												<?php

												$retorno_row = $produto->getProdutosTabela($seTabelaForAtiva ? $id_tabela : 0);
												if ($retorno_row):
													foreach ($retorno_row as $lrow):
														?>
														<option value="<?php echo $lrow->id; ?>">
															<?php echo $lrow->codigo . "#" . $lrow->nome; ?>
														</option>
														<?php
													endforeach;
													unset($lrow);
												endif;
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('VALOR'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control moeda" id="valor_venda_produto" name="valor">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('ESTOQUE'); ?></label>
										<div class="col-md-9">
											<input readonly type="text" class="form-control decimalp" id="estoque">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('QUANTIDADE'); ?></label>
										<div class="col-md-9">
											<input type="text" class="form-control decimalp" id="quantidade_finalizar_venda"
												name="quantidade">
										</div>
									</div>
								</div>
							</div>
						</div>

						<input name="id_venda" type="hidden" value="<?php echo Filter::$id; ?>" />
						<input name="id_tabela" id="id_ref_tabela" type="hidden" value="<?php echo $id_tabela; ?>" />
						<input name="id_cadastro" type="hidden" value="<?php echo $row->id_cadastro; ?>" />
						<input name="pagamentoAVista" type="hidden" id="pagamentoAVista" value="<?= $pagamentoAVista; ?>" />
						<input name="id_tabela" type="hidden" id="id_tabela_vendas" value="<?php echo $id_tabela; ?>">

						<div class="modal-footer">
							<button type="button"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarVendaProduto", "produto_form"); ?>
		</div>

		<div id="novo-pagamento" class="modal fade" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('PAGAMENTOS'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="pagar_form" id="pagar_form" class="form-horizontal">
						<div class="modal-body">
							<?php if (($valor_final_venda - $total_pagamento) <= 0): ?>
								<div class="note note-warning">
									<h4 class="block"><?php echo lang('MSG_ERRO_PAGAMENTO'); ?></h4>
									<p><?php echo lang('MSG_ERRO_PAGAMENTO_SUPERIOR'); ?></p>
								</div>
							<?php else: ?>
								<div class="form-group">
									<label class="control-label col-md-6"
										style="text-align:center;"><?php echo lang('VALOR_NORMAL') . ': <strong>' . moeda($valorTotalNormal + $row->valor_despesa_acessoria - $row->valor_desconto) . '</strong>'; ?></label>
									<label class="control-label col-md-6"
										style="text-align:center;"><?php echo lang('VALOR_AVISTA') . ': <strong>' . moeda($valorTotalAVista + $row->valor_despesa_acessoria - $row->valor_desconto) . '</strong>'; ?></label>
								</div>
								<hr>
								<span class="pull-right font-red bold">* Campo obrigatório</span> <br><br>
								<div class="form-group">
									<label class="control-label col-md-4"><?php echo lang('DATA_PAGAMENTO'); ?></label>
									<div class="col-md-7">
										<input type="text" class="form-control data calendario" name="data_pagamento"
											value="<?php echo date("d/m/Y"); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4">
										<?php echo lang('TIPO_PAGAMENTO'); ?>
										<span class="font-red">*</span>
									</label>
									<div class="col-md-7">
										<select class="form-control selectPayment" id="tipo_pagamento_finalizarvenda"
											name="tipo_pagamento_finalizarvenda"
											data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $faturamento->getTipoPagamento();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->id; ?>" avista="<?php echo $srow->avista; ?>"
														id_categoria="<?php echo $srow->id_categoria; ?>">
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
								<div class="form-group ocultar" id="valor_pago_aberto_dv">
									<label class="control-label col-md-4">
										<?php echo lang('VALOR_PAGAMENTO'); ?>
										<span class="font-red">*</span>
									</label>
									<div class="col-md-7">
										<input type="text" class="form-control moedap" name="valor_pago_aberto"
											id="valor_pago_aberto">
									</div>
								</div>
								<div class="form-group ocultar" id="total_parcelas_aberto_dv">
									<label class="control-label col-md-4"><?php echo lang('NUMERO_PARCELAS'); ?></label>
									<div class="col-md-7">
										<input type="text" class="form-control inteiro" name="total_parcelas_aberto"
											id="total_parcelas_aberto" value="1">
									</div>
								</div>
								<div class="form-group ocultar" id="data_parcela_boleto_dv">
									<label class="control-label col-md-4"><?php echo lang('PRIMEIRA_PARCELA_BOLETO'); ?></label>
									<div class="col-md-7">
										<input type="text" class="form-control data calendario" name="data_parcela_boleto"
											id="data_parcela_boleto"
											value="<?php echo date('d/m/Y', strtotime($dataHoje . ' + 2 days')); ?>">
									</div>
								</div>
							<?php endif; ?>
						</div>
						<input name="id_venda" type="hidden" value="<?php echo Filter::$id; ?>" />
						<input name="id_cadastro" type="hidden" value="<?php echo $row->id_cadastro; ?>" />
						<input name="valor_total_venda" type="hidden" value="<?php echo ($total_vendas->valor_total); ?>" />
						<input name="valor_final_venda" type="hidden" value="<?php echo ($total_vendas->valor_final); ?>" />
						<input name="valor_avista_venda" id="valor_avista_venda" type="hidden"
							value="<?php echo moeda($valorTotalAVista); ?>" />
						<input name="valor_normal_venda" id="valor_normal_venda" type="hidden"
							value="<?php echo moeda($valorTotalNormal); ?>" />
						<div class="modal-footer">
							<button type="button" id="btn_salvar_venda_aberto"
								class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?>
							</button>
							<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR'); ?>
							</button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarFinanceiroVendaAberto", "pagar_form"); ?>
		</div>

		<div id="finalizar-venda-nfc" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i
								class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('FINALIZAR_ABERTA_COM_NFC'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="finalizar_form_nfce" id="finalizar_form_nfce"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<?php if ($pagamento_ok): ?>
										<h4><?php echo lang('CADASTRO_FINALIZAR_VENDA_NFC_OK'); ?></h4>
										<div class="form-group">
											<label class="control-label col-md-3"><?php echo lang('VENDEDOR'); ?></label>
											<div class="col-md-9">
												<select class="select2me form-control" name="id_vendedor"
													data-placeholder="<?php echo lang('CADASTRO_VENDEDOR'); ?>">
													<option value=""></option>
													<?php
													$retorno_row = $usuario->getVendedor();
													if ($retorno_row):
														foreach ($retorno_row as $srow):
															?>
															<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $row->id_vendedor)
																   echo 'selected="selected"'; ?>><?php echo $srow->nome; ?></option>
															<?php
														endforeach;
														unset($srow);
													endif;
													?>
												</select>
											</div>
										</div>
									<?php else: ?>
										<h4 class="font-red"><?php echo lang('CADASTRO_FINALIZAR_VENDA_NOK'); ?></h4>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<input name="id_venda" type="hidden" value="<?php echo Filter::$id; ?>" />
						<input name="valor_total" type="hidden" value="<?php echo moedap($total_vendas->soma_total); ?>" />
						<input name="valor_desconto" type="hidden"
							value="<?php echo moedap($total_vendas->valor_desconto); ?>" />
						<input name="valor_pago" type="hidden" value="<?php echo moedap($total_pagamento); ?>" />
						<input name="tipo_sistema" type="hidden" value="<?php echo $core->tipo_sistema; ?>" />
						<input name="venda_fiscal" type="hidden" value="1" />
						<div class="modal-footer">
							<?php if ($pagamento_ok): ?>
								<button type="button"
									class="btn btn-submit purple"><?php echo lang('FINALIZAR_ABERTA_COM_NFC'); ?></button>
							<?php endif; ?>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarFinalizarVenda", "finalizar_form_nfce"); ?>
		</div>

		<div id="finalizar-venda" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i
								class="fa fa-shopping-cart">&nbsp;&nbsp;</i><?php echo lang('FINALIZAR_VENDA'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="finalizar_form" id="finalizar_form"
						class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<?php if ($pagamento_ok): ?>
										<h4><?php echo lang('CADASTRO_FINALIZAR_VENDA_OK'); ?></h4>
										<div class="form-group">
											<label class="control-label col-md-3"><?php echo lang('VENDEDOR'); ?></label>
											<div class="col-md-9">
												<select class="select2me form-control" name="id_vendedor"
													data-placeholder="<?php echo lang('CADASTRO_VENDEDOR'); ?>">
													<option value=""></option>
													<?php
													$retorno_row = $usuario->getVendedor();
													if ($retorno_row):
														foreach ($retorno_row as $srow):
															?>
															<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $row->id_vendedor)
																   echo 'selected="selected"'; ?>><?php echo $srow->nome; ?></option>
															<?php
														endforeach;
														unset($srow);
													endif;
													?>
												</select>
											</div>
										</div>
									<?php else: ?>
										<h4 class="font-red"><?php echo lang('CADASTRO_FINALIZAR_VENDA_NOK'); ?></h4>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<input name="id_venda" type="hidden" value="<?php echo Filter::$id; ?>" />
						<input name="valor_total" type="hidden" value="<?php echo moedap($total_vendas->soma_total); ?>" />
						<input name="valor_desconto" type="hidden"
							value="<?php echo moedap($total_vendas->valor_desconto); ?>" />
						<input name="valor_pago" type="hidden" value="<?php echo moedap($total_pagamento); ?>" />
						<input name="tipo_sistema" type="hidden" value="<?php echo $core->tipo_sistema; ?>" />
						<div class="modal-footer">
							<?php if ($pagamento_ok): ?>
								<button type="button"
									class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<?php endif; ?>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarFinalizarVenda", "finalizar_form"); ?>
		</div>

		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FINALIZAR_VENDA'); ?></small>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo $nomecliente; ?></small></h1>
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
										<i class="fa fa-usd font-<?php echo $core->primeira_cor; ?>"></i>
										<span class="font-<?php echo $core->primeira_cor; ?>">
											<?php echo lang('FINALIZAR_VENDA') . " - " . lang('COD_VENDA') . ": " . Filter::$id ?>
											<?php echo " | Cliente: " . $nomecliente; ?>
										</span>
									</div>
									<div class="actions btn-set">
										<?php if ($row->id_cadastro): ?>
											<a href="index.php?do=cadastro&acao=historico&id=<?php echo $row->id_cadastro ?>"
												class="btn <?php echo $core->primeira_cor; ?>"><?php echo lang('IR_PARA_CLIENTE'); ?></a>
										<?php endif; ?>
										<?php if ($core->tipo_sistema == 4): ?>
											<a href="index.php?do=vendas&acao=vendaspedidosentrega"
												class="btn default"><?php echo lang('VOLTAR'); ?></a>
										<?php else: ?>
											<a href="index.php?do=vendas_em_aberto"
												class="btn default"><?php echo lang('VOLTAR'); ?></a>
										<?php endif; ?>
									</div>
								</div>
								<div class="portlet-body util-btn-margin-bottom-5">
									<!-- INICIO UTIL-BTN-MARGIN-BOTTOM-5-->
									<div class="row">
										<div class="col-md-9 col-sm-12">
											<!-- <div class="clearfix">
											<input id="id_cadastro" type="hidden" value="<?php echo Filter::$id; ?>" />
											<input type="text" class="form-control barcodevendas" id="cod_barras" placeholder="<?php echo lang('CODIGO_DE_BARRAS'); ?>">
										</div>	 -->
											<div class="table-scrollable table-scrollable-borderless"
												style="height: 180px; overflow-y: scroll">
												<table class="table table-hover table-advance-green">
													<thead style="top: 0; position: sticky">
														<tr>
															<th><?php echo lang('PAGAMENTO'); ?></th>
															<th><?php echo lang('VALOR'); ?></th>
															<th><?php echo lang('PARCELAS'); ?></th>
															<th><?php echo lang('DATA_VENCIMENTO'); ?></th>
															<th><?php echo lang('USUARIO'); ?></th>
															<th><?php echo lang('OPCOES'); ?></th>
														</tr>
													</thead>
													<tbody>
														<?php
														$retorno_row = $cadastro->getFinanceiro(Filter::$id);
														if ($retorno_row):
															foreach ($retorno_row as $exrow):
																?>
																<tr>
																	<td><?php echo $exrow->pagamento; ?></td>
																	<td><span
																			class="bold theme-font"><?php echo moedap($exrow->valor_pago); ?></span>
																	</td>
																	<td><?php echo $exrow->total_parcelas; ?></td>
																	<td><?php echo ($exrow->total_parcelas > 1) ? lang('VER_PARCELAS') : exibedata($exrow->data_vencimento); ?>
																	</td>
																	<td><?php echo $exrow->usuario; ?></td>
																	<td>
																		<a href="javascript:void(0);"
																			onclick="javascript:void window.open('imprimir_detalhes_financeiro.php?id=<?php echo $exrow->id; ?>','<?php echo $exrow->pagamento; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																			title="<?php echo lang('VER_DETALHES'); ?>"
																			class="btn btn-sm grey-cascade"><i
																				class="fa fa-search"></i></a>
																		<a href="javascript:void(0);" class="btn btn-sm red apagar"
																			id="<?php echo $exrow->id; ?>"
																			acao="apagarCadastroFinanceiroVendaAberta"
																			title="<?php echo lang('CADASTRO_APAGAR_PAGAMENTO') . $exrow->pagamento; ?>"><i
																				class="fa fa-times"></i></a>
																	</td>
																	<input type="hidden" name="pagamento_avista[]"
																		class="pagamento_avista"
																		value="<?php echo ($exrow->avista) ? '1' : '0'; ?>" />
																	<input type="hidden" name="valor_pagamento[]"
																		class="valor_pagamento"
																		value="<?php echo $exrow->valor_pago; ?>" />
																</tr>
															<?php endforeach; ?>
															<?php unset($exrow);
														endif; ?>
													</tbody>
												</table>
											</div>
											<hr>
											<div class="table-scrollable table-scrollable-borderless"
												style="height: 330px; overflow-y: scroll">
												<table class="table table-hover table-advance">
													<thead style="top: 0; position: sticky">
														<tr>
															<th><?php echo lang('PRODUTO'); ?></th>
															<th><?php echo lang('VALOR'); ?></th>
															<th><?php echo lang('QUANT'); ?></th>
															<th><?php echo lang('DESCONTO'); ?></th>
															<th><?php echo lang('ACRESCIMO'); ?></th>
															<th><?php echo lang('VL_TOTAL'); ?></th>
															<th><?php echo lang('USUARIO'); ?></th>
															<th><?php echo lang('OPCOES'); ?></th>
														</tr>
													</thead>
													<tbody>
														<?php
														$retorno_row = $cadastro->getVendaProdutos(Filter::$id);
														if ($retorno_row):
															foreach ($retorno_row as $exrow):
																?>
																<tr class="produto_aberto" id="<?php echo $exrow->id; ?>"
																	id_venda="<?php echo Filter::$id; ?>"
																	informacoes="<?php echo $exrow->produto . '#' . moedap($exrow->valor) . '#' . decimalp($exrow->quantidade) . '#' . moedap($exrow->valor_desconto) . '#' . moedap($exrow->valor_despesa_acessoria) . '#' . moedap($exrow->valor_total + $exrow->valor_despesa_acessoria - $exrow->valor_desconto); ?>">
																	<td><?php echo $exrow->produto; ?></td>
																	<td><?php echo moedap($exrow->valor); ?></td>
																	<td><?php echo decimalp($exrow->quantidade); ?></td>
																	<td><?php echo moedap($exrow->valor_desconto); ?></td>
																	<td><?php echo moedap($exrow->valor_despesa_acessoria); ?></td>
																	<td><span
																			class="bold theme-font valor_total"><?php echo moedap($exrow->valor_total + $exrow->valor_despesa_acessoria - $exrow->valor_desconto); ?></span>
																	</td>
																	<td><?php echo $exrow->usuario; ?></td>
																	<td>
																		<a href="javascript:void(0);"
																			onclick="javascript:void window.open('imprimir_detalhes_item.php?id=<?php echo $exrow->id; ?>','<?php echo $exrow->produto; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																			title="<?php echo lang('VER_DETALHES'); ?>"
																			class="btn btn-sm grey-cascade"><i
																				class="fa fa-search"></i></a>
																		<?php if (!$exrow->inativo): ?>
																			<a href="javascript:void(0);" class="btn btn-sm red apagar"
																				id="<?php echo $exrow->id; ?>" acao="apagarVendaProduto"
																				title="<?php echo lang('CADASTRO_ITEM_APAGAR') . $exrow->produto; ?>"><i
																					class="fa fa-times"></i></a>
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
										<div class="col-md-3 col-sm-12">

											<?php if ($total_pagamento > 0): ?>
												<div class="clearfix">
													<a href="#finalizar-venda-nfc" class="btn purple btn-block"
														data-toggle="modal"><i
															class="fa fa-shopping-cart fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('FINALIZAR_ABERTA_COM_NFC'); ?></a>
												</div>
											<?php endif; ?>

											<div class="well">
												<div class="row static-info align-reverse">
													<div class="col-md-6 name">
														<?php echo lang('VALOR_TOTAL'); ?>:
													</div>
													<div class="col-md-6 value" id="valtotal_finalizavenda">
														<?php echo moedap($total_vendas->soma_total); ?>
													</div>
												</div>
												<div class="row static-info align-reverse">
													<div class="col-md-6 name">
														<?php echo lang('VALOR_DESCONTO'); ?>:
													</div>
													<div class="col-md-6 value" id="desconto">
														<?php echo moedap($total_vendas->valor_desconto); ?>
													</div>
												</div>
												<div class="row static-info align-reverse">
													<div class="col-md-6 name">
														<?php echo lang('VALOR_ACRESCIMO_TITULO'); ?>:
													</div>
													<div class="col-md-6 value" id="acrescimo">
														<?php echo moedap($total_vendas->valor_despesa_acessoria); ?>
													</div>
												</div>
												<div class="row static-info align-reverse">
													<div class="col-md-6 name">
														<?php echo lang('VALOR_PAGAR'); ?>:
													</div>
													<div class="col-md-6 value">
														<?php echo moedap($total_vendas->soma_total + $total_vendas->valor_despesa_acessoria - $total_vendas->valor_desconto); ?>
													</div>
												</div>
												<div class="row static-info align-reverse font-green">
													<div class="col-md-6 name">
														<?php echo lang('VALOR_PAGO'); ?>:
													</div>
													<div class="col-md-6 value">
														<?php echo moedap($total_pagamento); ?>
													</div>
												</div>
												<div class="row static-info align-reverse">
													<div class="col-md-6 name">
														<?php echo lang('TROCO'); ?>:
													</div>
													<div class="col-md-6 value">
														<?php echo moedap($total_vendas->troco); ?>
													</div>
												</div>
												<div class="row static-info align-reverse font-red">
													<div class="col-md-6 name">
														<?php echo lang('VALOR_RESTANTE'); ?>:
													</div>
													<div class="col-md-6 value">
														<?php
														echo moedap($valor_restante);
														?>
													</div>
												</div>
											</div>
											<div class="clearfix">
												<input type="text" readonly class="form-control"
													value="<?= lang('TABELA_PRECO_SIMPLES') . ': ' . $nome_tabela; ?>">
											</div>
											<br />
											<div class="clearfix">
												<input type="text" class="form-control barcodevendas" id="cod_barras"
													placeholder="<?php echo lang('CODIGO_DE_BARRAS'); ?>">
												<input name="id_venda" id="id_venda" type="hidden"
													value="<?php echo Filter::$id; ?>" />
												<input name="id_tabela" id="id_tabela_venda" type="hidden"
													value="<?php echo $id_tabela; ?>" />
												<input name="id_cadastro" id="id_cadastro" type="hidden"
													value="<?php echo $row->id_cadastro; ?>" />
											</div>
											<br />
											<div class="clearfix">
												<a href="#alterar-dados" class="btn blue btn-block" data-toggle="modal"><i
														class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ALTERAR_INFORMACOES'); ?></a>
											</div>
											<div class="clearfix">
												<a href="#alterar-desconto" class="btn yellow-casablanca btn-block"
													data-toggle="modal"><i
														class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ALTERAR_DESCONTO'); ?></a>
											</div>
											<div class="clearfix">
												<a href="#alterar-acrescimo" class="btn yellow btn-block" data-toggle="modal"><i
														class="fa fa fa-dollar">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ALTERAR_ACRESCIMO'); ?></a>
											</div>
											<div class="clearfix">
												<a href="#novo-produto" class="btn purple btn-block" data-toggle="modal"><i
														class="fa fa-barcode">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ADICIONAR_PRODUTO'); ?></a>
											</div>
											<div class="clearfix">
												<a href="#novo-pagamento" class="btn green-haze btn-block"
													data-toggle="modal"><i
														class="fa fa-money">&nbsp;&nbsp;</i><?php echo lang('FAZER_PAGAMENTO'); ?></a>
											</div>
											<?php if ($total_pagamento > 0): ?>
												<div class="clearfix">
													<a href="#finalizar-venda"
														class="btn <?php echo $core->primeira_cor; ?> btn-block"
														data-toggle="modal"><i
															class="fa fa-shopping-cart">&nbsp;&nbsp;</i><?php echo lang('FINALIZAR_VENDA'); ?></a>
												</div>
											<?php endif; ?>
										</div>
									</div>
									<!-- FINAL UTIL-BTN-MARGIN-BOTTOM-5-->
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
	case "vendascrediario":
		if ($core->tipo_sistema == 1 || $core->tipo_sistema == 3) {
			redirect_to("login.php");
		}
		$id_origem = get('id_origem');
		?>

		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {

				$('#imprimir_crediario').click(function () {
					var id_origem = $("#id_origem").val();
					window.open('pdf_crediario.php?id_origem=' + id_origem, 'Imprimir Crediario', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#id_origem').change(function () {
					var id_origem = $("#id_origem").val();
					window.location.href = 'index.php?do=vendas&acao=vendascrediario&id_origem=' + id_origem;
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
						<h1><?php echo lang('CADASTRO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_CREDIARIO_FICHA'); ?></small>
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
										<i class="fa fa-money font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('CADASTRO_CREDIARIO_FICHA'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="javascript:void(0);" id="atualizar_crediario"
											class="atualizar_crediario btn yellow-casablanca"><i
												class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('CREDIARIO_ATUALIZAR_DATA_FICHA'); ?></a>
										<a href="javascript:void(0);" id="imprimir_crediario"
											class="btn <?php echo $core->primeira_cor; ?>"><i
												class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR'); ?></a>
									</div>
								</div>
								<div class="portlet-body form">
									<form class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-large" id="id_origem" name="id_origem"
												data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $cadastro->getOrigem();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_origem)
															   echo 'selected="selected"'; ?>><?php echo $srow->origem; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<div class="form-group">
											<a href="index.php?do=vendas&acao=pagarvendascrediario&id_origem=<?php echo $id_origem; ?>"
												class="btn green"><i
													class="fa fa-money">&nbsp;&nbsp;</i><?php echo lang('CREDIARIO_PAGAR_TODOS'); ?></a>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed dataTable table_advance">
										<thead>
											<tr>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('CREDIARIO'); ?></th>
												<th><?php echo lang('SALDO'); ?></th>
												<th><?php echo lang('MULTA'); ?></th>
												<th><?php echo lang('JUROS'); ?></th>
												<th><?php echo lang('VALOR_PAGAR'); ?></th>
												<th><?php echo lang('ABERTO_DESDE'); ?></th>
												<th><?php echo lang('ULTIMO_PAGAMENTO'); ?></th>
												<th><?php echo lang('ACOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$total_juros = 0;
											$total_multa = 0;
											$retorno_row = $cadastro->getCadastrosCrediario($id_origem);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$valor_crediario = $exrow->crediario;
													$data_crediario = $exrow->data_crediario;
													$valores_crediario = $cadastro->getValoresCrediario($exrow->id);
													$valor_pagar = $valores_crediario->valor - $valores_crediario->valor_pago;
													$valor_multa = $valores_crediario->multa;
													$total_multa += $valor_multa;
													$valor_juros = $valores_crediario->juros;
													$total_juros += $valor_juros;
													$venda_antiga = $cadastro->getDataCrediario($exrow->id);
													$data_venda_antiga = $venda_antiga->data_venda_antiga;
													if ($valor_pagar > 0):
														$total += $valor_pagar;
														$saldo = $valor_crediario - $valor_pagar;
														?>
														<tr>
															<td><a
																	href="index.php?do=cadastro&acao=crediario&opcao=0&id=<?php echo $exrow->id; ?>"><?php echo $exrow->nome; ?></a>
															</td>
															<td><span class="font-blue"><?php echo moeda($valor_crediario); ?></span></td>
															<td><span class="font-green"><?php echo moeda($saldo); ?></span></td>
															<td><span <?php echo ($valor_multa > 0) ? 'class="bold font-red"' : ''; ?>><?php echo moeda($valor_multa); ?></span></td>
															<td><span <?php echo ($valor_juros > 0) ? 'class="bold font-red"' : ''; ?>><?php echo moeda($valor_juros); ?></span></td>
															<td><span class="bold font-red"><?php echo moeda($valor_pagar); ?></span></td>
															<td><span><?php echo exibedata($data_venda_antiga); ?></span></td>
															<td><span><?php echo exibedata($data_crediario); ?></span></td>
															<td>
																<a href="javascript:void(0);"
																	onclick="javascript:void window.open('pdf_crediario_carta.php?opcao=0&id_cliente=<?php echo $exrow->id; ?>','<?php echo $exrow->nome; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																	title="<?php echo lang('IMPRIMIR_CARTA'); ?>"
																	class="btn btn-sm <?php echo $core->primeira_cor; ?>"><i
																		class="fa fa-print"></i></a>
																<a href="javascript:void(0);" class="btn btn-sm grey-gallery retornocontato"
																	id="<?php echo $exrow->id; ?>" nome="<?php echo $exrow->nome; ?>"
																	telefone="<?php echo $exrow->telefone . " " . $exrow->celular; ?>"
																	title="<?php echo lang('CONTATO_RETORNO'); ?>"><i
																		class="fa fa-phone"></i></a>
															</td>
														</tr>
													<?php endif;
												endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="3"><span class="bold"><?php echo lang('TOTAL'); ?></span></td>
													<td><?= moeda($total_multa); ?></td>
													<td><?= moeda($total_juros); ?></td>
													<td><span class="bold font-red"><?php echo moeda($total); ?></span></td>
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
		<div id="retorno-contato" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-phone">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO'); ?>
						</h4>
						<h4 class="modal-title"><strong>
								<div id="nome"><strong></div>
						</h4>
						<h4 class="modal-title">
							<div id="telefone"></div>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="retorno_form" id="retorno_form">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<p><?php echo lang('RETORNO'); ?></p>
									<p>
										<select class="select2me form-control" id="id_status" name="id_status"
											data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $cadastro->getStatus();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->status; ?></option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</p>
									<p><?php echo lang('DATA_RETORNO'); ?></p>
									<p>
										<input type="text" class="form-control data calendario" name="data_retorno">
									</p>
									<p><?php echo lang('OBSERVACAO'); ?></p>
									<p>
										<input type="text" class="form-control caps" name="observacao">
									</p>
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
			<?php echo $core->doForm("processarCadastroRetorno", "retorno_form"); ?>
		</div>
		<?php break; ?>
	<?php
	case "vendasclienteperiodo":
		if ($core->tipo_sistema == 1 || $core->tipo_sistema == 3) {
			redirect_to("login.php");
		}
		$id_origem_vendas = (get('id_origem_vendas')) ? get('id_origem_vendas') : 0;
		$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y');
		$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<script type="text/javascript">
			// <![CDATA[
			$(document).ready(function () {
				$('#buscar').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					var id_origem_vendas = $("#id_origem_vendas").val();
					window.location.href = 'index.php?do=vendas&acao=vendasclienteperiodo&dataini=' + dataini + '&datafim=' + datafim + '&id_origem_vendas=' + id_origem_vendas;
				});
			});
			// ]]>
		</script>
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('CADASTRO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_CLIENTE_PERIODO'); ?></small>
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
										<i class="fa fa-users font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_CLIENTE_PERIODO'); ?></span>
									</div>
								</div>
								<div class="portlet-body form">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<?php echo lang('DE'); ?>
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											&nbsp;&nbsp;&nbsp;
											<?php echo lang('ATE'); ?>
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
											&nbsp;&nbsp;&nbsp;
											<?php echo lang('ORIGEM'); ?>
											<select class="select2me form-control input-large" id="id_origem_vendas"
												name="id_origem_vendas"
												data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $cadastro->getOrigem();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == $id_origem_vendas)
															   echo 'selected="selected"'; ?>><?php echo $srow->origem; ?></option>
														<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
										&nbsp;&nbsp;&nbsp;
										<div class="form-group">
											<a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?>'
												id="buscar" title='<?php echo lang('BUSCAR'); ?>'><i
													class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed dataTable table_advance">
										<thead>
											<tr>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('CIDADE'); ?></th>
												<th><?php echo lang('VALOR_PAGAR'); ?></th>
												<th><?php echo lang('DE'); ?></th>
												<th><?php echo lang('ATE'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $cadastro->getVendasClientesPeriodo($dataini, $datafim, $id_origem_vendas);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$total += $exrow->valor_pago;
													?>
													<tr>
														<td><?php echo $exrow->nome; ?></td>
														<td><?php echo strtoupper($exrow->cidade); ?></td>
														<td><?php echo moeda($exrow->valor_pago); ?></td>
														<td><?php echo $dataini; ?></td>
														<td><?php echo $datafim; ?></td>
													</tr>
													<?php
												endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"><span class="bold"><?php echo lang('TOTAL'); ?></span></td>
													<td><?php echo moeda($total); ?></td>
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
		</div>
		<!-- FINAL CONTEUDO DA PAGINA -->
		<div id="retorno-contato" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-phone">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO'); ?>
						</h4>
						<h4 class="modal-title"><strong>
								<div id="nome"><strong></div>
						</h4>
						<h4 class="modal-title">
							<div id="telefone"></div>
						</h4>
					</div>
					<form action="" autocomplete="off" method="post" name="retorno_form" id="retorno_form">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<p><?php echo lang('RETORNO'); ?></p>
									<p>
										<select class="select2me form-control" id="id_status" name="id_status"
											data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $cadastro->getStatus();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->status; ?></option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</p>
									<p><?php echo lang('DATA_RETORNO'); ?></p>
									<p>
										<input type="text" class="form-control data calendario" name="data_retorno">
									</p>
									<p><?php echo lang('OBSERVACAO'); ?></p>
									<p>
										<input type="text" class="form-control caps" name="observacao">
									</p>
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
			<?php echo $core->doForm("processarCadastroRetorno", "retorno_form"); ?>
		</div>
		<?php break; ?>
	<?php
	case "pagarvendascrediario":
		$id_origem = get('id_origem'); ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('CADASTRO_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_CREDIARIO_FICHA'); ?></small>
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
										<i class="fa fa-money font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('CREDIARIO_PAGAR_TODOS_FICHA'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div class="note note-warning">
										<h4 class="block"><?php echo lang('CREDIARIO_PAGAR_TODOS_ATENCAO'); ?></h4>
										<p><?php echo lang('CREDIARIO_PAGAR_TODOS_FICHA_TEXTO'); ?></p>
									</div>
								</div>
								<div class="portlet-body form">
									<table class="table table-bordered table-striped table-condensed dataTable table_advance">
										<thead>
											<tr>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('ORIGEM'); ?></th>
												<th><?php echo lang('CREDIARIO'); ?></th>
												<th><?php echo lang('SALDO'); ?></th>
												<th><?php echo lang('MULTA'); ?></th>
												<th><?php echo lang('JUROS'); ?></th>
												<th><?php echo lang('VALOR_PAGAR'); ?></th>
												<th><?php echo lang('DATA_PAGAMENTO'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $cadastro->getCadastrosCrediario($id_origem);
											if ($retorno_row):
												$quant = (is_array($retorno_row) ? count($retorno_row) : 0);
												foreach ($retorno_row as $exrow):
													$valor_crediario = $exrow->crediario;
													$data_crediario = $exrow->data_crediario;
													$valores_crediario = $cadastro->getValoresCrediario($exrow->id);
													$valor_pagar = $valores_crediario->valor - $valores_crediario->valor_pago;
													$valor_multa = $valores_crediario->multa;
													$valor_juros = $valores_crediario->juros;
													if ($valor_pagar > 0):
														$total += $valor_pagar;
														$saldo = $valor_crediario - $valor_pagar;
														?>
														<tr>
															<td><a
																	href="index.php?do=cadastro&acao=crediario&opcao=0&id=<?php echo $exrow->id; ?>"><?php echo $exrow->nome; ?></a>
															</td>
															<td><?php echo $exrow->origem; ?></td>
															<td><span class="font-blue"><?php echo moeda($valor_crediario); ?></span></td>
															<td><span class="font-green"><?php echo moeda($saldo); ?></span></td>
															<td><span <?php echo ($valor_multa > 0) ? 'class="bold font-red"' : ''; ?>><?php echo moeda($valor_multa); ?></span></td>
															<td><span <?php echo ($valor_juros > 0) ? 'class="bold font-red"' : ''; ?>><?php echo moeda($valor_juros); ?></span></td>
															<td><span class="bold font-red"><?php echo moeda($valor_pagar); ?></span></td>
															<td><span><?php echo exibedata($data_crediario); ?></span></td>
														</tr>
													<?php endif;
												endforeach; ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="6"><span class="bold"><?php echo lang('TOTAL'); ?></span></td>
													<td colspan="2"><span class="bold font-red"><?php echo moeda($total); ?></span>
													</td>
												</tr>
											</tfoot>
											<?php unset($exrow);
											endif; ?>
									</table>
									<br><br>
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-3">
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label
															class="control-label col-md-3"><?php echo lang('VALOR'); ?></label>
														<div class="col-md-6">
															<input type="text" readonly id="valor_pagamento"
																class="form-control" name="valor"
																value="<?php echo moeda($total); ?>">
														</div>
													</div>
													<div class="form-group">
														<label
															class="control-label col-md-3"><?php echo lang('DATA_PAGAMENTO'); ?></label>
														<div class="col-md-6">
															<input type="text" class="form-control data calendario"
																name="data_pagamento_crediario"
																value="<?php echo date('d/m/Y'); ?>">
														</div>
													</div>
													<div class="form-group">
														<label
															class="control-label col-md-3"><?php echo lang('PAGAMENTO'); ?></label>
														<div class="col-md-6">
															<select class="select2me form-control"
																name="tipopagamento_crediario"
																data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																<option value=""></option>
																<?php
																$retorno_row = $faturamento->getTipoPagamento();
																if ($retorno_row):
																	foreach ($retorno_row as $srow):
																		if ($srow->exibir_crediario == 1):
																			?>
																			<option value="<?php echo $srow->id; ?>">
																				<?php echo $srow->tipo; ?>
																			</option>
																			<?php
																		endif;
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
										<input name="id_origem" type="hidden" value="<?php echo $id_origem; ?>" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-1 col-md-11">
																<button type="button"
																	class="btn btn-submit green"><?php echo lang('CREDIARIO_PAGAR_TODOS_CONFIRMAR'); ?></button>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-1 col-md-11">
																<a href="index.php?do=vendas&acao=vendascrediario<?php echo ($id_origem) ? '&id_origem=' . $id_origem : ''; ?>"
																	class="btn default"><?php echo lang('CREDIARIO_PAGAR_TODOS_VOLTAR'); ?></a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm("processarPagamentoCrediarioClientes"); ?>
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
	case "cancelarvenda":
		$row_vendas = Core::getRowById("vendas", Filter::$id);
		$historico = (get('id_cadastro')) ? get('id_cadastro') : 0;
		$venda_dinheiro = false;
		$total_dinheiro = 0;
		$status = getValue("status", "caixa", "id = " . $row_vendas->id_caixa);
		$id_caixa = ($status < 3) ? $row_vendas->id_caixa : 0;
		$pagina = (get('pg')) ? get('pg') : 0;
		$voltar = ($pagina == 1) ? "index.php?do=vendas_do_dia" : "index.php?do=vendas_por_periodo";

		if ($row_vendas->inativo)
			redirect_to("login.php");

		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_CANCELAR'); ?></small></h1>
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
							<?php if ($status > 1): ?>
								<div class="portlet light">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-ban font-red"></i>
											<span
												class="font-red"><?php echo lang('VENDAS_CANCELAR') . ": " . Filter::$id; ?></span>
										</div>
										<div class="actions btn-set">
											<a href="javascript:history.back()"
												class="btn default"><?php echo lang('VOLTAR'); ?></a>
										</div>
									</div>
									<div class="portlet-body form">
										<!-- INICIO FORM-->
										<form autocomplete="off" action="javascript:;" class="form-horizontal">
											<div class="form-body">
												<div class="row">
													<div class="col-md-12">
														<div class="note note-warning">
															<h4 class="block">Atenção! Esta operação não é permitida.</h4>
															<p>Você não pode excluir uma venda em um caixa Fechado ou Validado. Para
																mais informações consulte o suporte do sistema.</p>
														</div>
													</div>
												</div>
											</div>
										</form>
										<!-- FINAL FORM-->
									</div>
								</div>
							<?php else: ?>
								<div class="portlet light">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-ban font-red"></i>
											<span
												class="font-red"><?php echo lang('VENDAS_CANCELAR') . ": " . Filter::$id; ?></span>
										</div>
										<div class="actions btn-set">
											<a href="index.php?do=vendas_em_aberto"
												class="btn default"><?php echo lang('VOLTAR'); ?></a>
										</div>
									</div>
									<div class="portlet-body form">
										<?php
											if ($row_vendas->venda_agrupamento==1):
										?>
												<div class="note note-warning">
													<h4 class="block">Atenção!</h4>
													<p>Esta é uma venda de agrupamento para emissão, o seu cancelamento irá desvincular as vendas agrupadas e não implica em movimentação financeira e nem de estoque.</p>
												</div>

										<?php		
											endif;
										?>
										<!-- INICIO FORM-->
										<form autocomplete="off" action="javascript:;" class="form-horizontal">
											<div class="form-body">
												<div class="row">
													<div class="col-md-12">
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('DATA_VENDA'); ?></label>
																	<div class="form-control-static col-md-9 bold">
																		<?php echo exibedataHora($row_vendas->data_venda); ?>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('USUARIO'); ?></label>
																	<div class="form-control-static col-md-9 bold">
																		<?php echo $row_vendas->usuario; ?>
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
																		class="control-label col-md-3"><?php echo lang('VALOR_PAGO'); ?></label>
																	<div class="form-control-static col-md-9 bold">
																		<?php echo moedap($row_vendas->valor_pago); ?>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('CAIXA'); ?></label>
																	<div class="form-control-static col-md-9 bold">
																		<?php echo $row_vendas->id_caixa . " " . statusCaixa($status); ?>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>
												</div>
											</div>
										</form>
										<!-- FINAL FORM-->
									</div>
									<div class="portlet-body">
										<table class="table table-bordered table-striped table-advance">
											<thead>
												<tr>
													<th><?php echo lang('PAGAMENTO'); ?></th>
													<th><?php echo lang('VALOR'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$retorno_row = $cadastro->getFinanceiroVenda(Filter::$id);
												if ($retorno_row):
													foreach ($retorno_row as $exrow):
														if ($exrow->id == 1) {
															$venda_dinheiro = true;
															$total_dinheiro = $exrow->valor_pago;
														}
														?>
														<tr>
															<td><?php echo pagamento($exrow->pagamento); ?></td>
															<td><span
																	class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago); ?></span>
															</td>
														</tr>
													<?php endforeach; ?>
													<?php unset($exrow);
												endif; ?>
											</tbody>
										</table>
									</div>
									<div class="portlet-body form">
										<!-- INICIO FORM-->
										<form action="" autocomplete="off" method="post" class="horizontal-form " name="admin_form"
											id="admin_form">
											<?php if ($venda_dinheiro && $id_caixa == 0 && $row_vendas->venda_agrupamento==0): ?>
												<div class="form-body">
													<div class="row">
														<div class="col-md-12">
															<!--col-md-6-->
															<div class="col-md-6">
																<div class="row">
																	<div class="form-group">
																		<label
																			class="control-label col-md-4"><?php echo lang('SELECIONE_BANCO'); ?></label>
																		<div class="col-md-8">
																			<select class="select2me form-control" name="id_banco"
																				data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																				<option value=""></option>
																				<?php
																				$retorno_row = $faturamento->getBancos();
																				if ($retorno_row):
																					foreach ($retorno_row as $srow):
																						?>
																						<option value="<?php echo $srow->id; ?>">
																							<?php echo $srow->banco; ?>
																						</option>
																						<?php
																					endforeach;
																					unset($srow);
																				endif;
																				?>
																			</select>
																			<span
																				class="help-block font-red"><?php echo lang('SELECIONE_BANCO_DEBITO'); ?></span>
																		</div>
																	</div>
																</div>
															</div>
															<!--/col-md-6-->
														</div>
													</div>
												</div>
											<?php endif; ?>
											<input name="id_venda" type="hidden" value="<?php echo Filter::$id; ?>" />
											<input name="id_caixa" type="hidden" value="<?php echo $id_caixa; ?>" />
											<input name="id_cadastro" type="hidden"
												value="<?php echo $row_vendas->id_cadastro; ?>" />
											<input name="total_dinheiro" type="hidden" value="<?php echo $total_dinheiro; ?>" />
											<input name="historico" type="hidden" value="<?php echo $historico; ?>" />
											<input name="pagina" type="hidden" value="<?php echo $pagina; ?>" />
											<div class="form-actions">
												<div class="row">
													<div class="col-md-12">
														<div class="col-md-12">
															<div class="row">
																<div class="col-md-offset-1 col-md-11">
																	<?php $botao_cancelar = ($row_vendas->venda_agrupamento==1) ? lang('VENDAS_CANCELAR_AGRUPAMENTO') : lang('VENDAS_CANCELAR'); ?>
																	<button type="button" class="btn btn-submit red"><?php echo $botao_cancelar; ?></button>
																	<a href="<?php echo $voltar; ?>"
																		class="btn default"><?php echo lang('VOLTAR'); ?></a>
																</div>
															</div>
														</div>
														<div class="col-md-6">
														</div>
													</div>
												</div>
											</div>
											<?php echo $core->doForm("processarCancelarVenda"); ?>
										</form>
										<!-- FINAL FORM-->
									</div>
								</div>
							<?php endif; ?>
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
	case "cancelarvendafiscal": ?>
		<?php $row_vendas = Core::getRowById("vendas", Filter::$id);
		$historico = (get('id_cadastro')) ? get('id_cadastro') : 0;
		$venda_dinheiro = false;
		$total_dinheiro = 0;
		$status = getValue("status", "caixa", "id = " . $row_vendas->id_caixa);
		$id_caixa = ($status < 3) ? $row_vendas->id_caixa : 0;

		if ($row_vendas->inativo)
			redirect_to("login.php");
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('VENDAS_CANCELAR'); ?></small></h1>
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
										<i class="fa fa-minus-circle font-red"></i>
										<span
											class="font-red"><?php echo lang('VENDAS_CANCELAR_FISCAL') . ": " . Filter::$id; ?></span>
									</div>
									<div class="actions btn-set">
										<a href="index.php?do=vendas_do_dia"
											class="btn default"><?php echo lang('VOLTAR'); ?></a>
									</div>
								</div>
								<div class="portlet-body form">
									<?php
										if ($row_vendas->venda_agrupamento==1):
									?>
											<div class="note note-warning">
												<h4 class="block">Atenção!</h4>
												<p>Esta é uma venda de agrupamento para emissão, o seu cancelamento irá desvincular as vendas agrupadas e não implica em movimentação financeira e nem de estoque.</p>
											</div>
									<?php		
										endif;
									?>
									<!-- INICIO FORM-->
									<form autocomplete="off" action="javascript:;" class="form-horizontal">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_EMISSAO'); ?></label>
																<div class="form-control-static col-md-9 bold">
																	<?php echo exibedataHora($row_vendas->data_emissao); ?>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('USUARIO'); ?></label>
																<div class="form-control-static col-md-9 bold">
																	<?php echo $row_vendas->usuario; ?>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('VENDAS_FISCAL'); ?></label>
																<div class="form-control-static col-md-9 bold">
																	<?php echo $row_vendas->numero_nota; ?>
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
																	class="control-label col-md-3"><?php echo lang('VALOR_PAGO'); ?></label>
																<div class="form-control-static col-md-9 bold">
																	<?php echo moedap($row_vendas->valor_pago); ?>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CAIXA'); ?></label>
																<div class="form-control-static col-md-9 bold">
																	<?php echo $row_vendas->id_caixa . " " . statusCaixa($status); ?>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
											</div>
										</div>
									</form>
									<!-- FINAL FORM-->
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-advance">
										<thead>
											<tr>
												<th><?php echo lang('PAGAMENTO'); ?></th>
												<th><?php echo lang('VALOR'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $cadastro->getFinanceiroVenda(Filter::$id);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													if ($exrow->id == 1) {
														$venda_dinheiro = true;
														$total_dinheiro = $exrow->valor_pago;
													}
													?>
													<tr>
														<td><?php echo pagamento($exrow->pagamento); ?></td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago); ?></span>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
									</table>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="horizontal-form " name="admin_form"
										id="admin_form">
										<?php if ($venda_dinheiro && $id_caixa == 0 && $row_vendas->venda_agrupamento==0): ?>
											<div class="form-body">
												<div class="row">
													<div class="col-md-12">
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-4"><?php echo lang('SELECIONE_BANCO'); ?></label>
																	<div class="col-md-8">
																		<select class="select2me form-control" name="id_banco"
																			data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																			<option value=""></option>
																			<?php
																			$retorno_row = $faturamento->getBancos();
																			if ($retorno_row):
																				foreach ($retorno_row as $srow):
																					?>
																					<option value="<?php echo $srow->id; ?>">
																						<?php echo $srow->banco; ?>
																					</option>
																					<?php
																				endforeach;
																				unset($srow);
																			endif;
																			?>
																		</select>
																		<span
																			class="help-block font-red"><?php echo lang('SELECIONE_BANCO_DEBITO'); ?></span>
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>
												</div>
											</div>
										<?php endif; ?>
										<input name="id_venda" type="hidden" value="<?php echo Filter::$id; ?>" />
										<input name="id_caixa" type="hidden" value="<?php echo $id_caixa; ?>" />
										<input name="id_cadastro" type="hidden"
											value="<?php echo $row_vendas->id_cadastro; ?>" />
										<input name="total_dinheiro" type="hidden" value="<?php echo $total_dinheiro; ?>" />
										<input name="historico" type="hidden" value="<?php echo $historico; ?>" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-12">
														<div class="row">
															<div class="col-md-offset-1 col-md-11">
																<?php $dentroPrazoCancelamento = !(strtotime($row_vendas->data_emissao . ' +30 minutes') < strtotime(date('Y-m-d H:i:s')));
																; ?>
																<?php if (!$dentroPrazoCancelamento): ?>
																	<div class="alert alert-warning">
																		<strong><?php echo lang('ATENCAO'); ?></strong><?php echo ' ' . lang('MSG_ERRO_CANCELAR_NFCE_ATENCAO'); ?>
																	</div>
																<?php else: ?>
																	<?php if ($row_vendas->status_enotas == "Cancelada"): ?>
																		<div class="alert alert-warning">
																			<strong><?php echo lang('ATENCAO'); ?></strong><?php echo ' ' . lang('MSG_ERRO_CANCELAR_NFCE_OK'); ?>
																		</div>
																	<?php else: 
																			if ($row_vendas->venda_agrupamento==1): ?>
																				<a href="javascript:void(0);" class="btn btn-sm red" onclick="javascript:void window.open('nfc_cancelar_lote.php?id_venda=<?php echo Filter::$id; ?>&id_caixa=<?php echo $id_caixa; ?>&historico=<?php echo $historico; ?>&total_dinheiro=<?php echo $total_dinheiro; ?>','<?php echo lang('VENDAS_CANCELAR_FISCAL'); ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VENDAS_CANCELAR_FISCAL_AGRUPAMENTO'); ?>"><i class="fa fa-minus-circle">&nbsp;&nbsp;</i><?php echo lang('VENDAS_CANCELAR_FISCAL_AGRUPAMENTO'); ?></a>
																	<?php	else: ?>
																				<a href="javascript:void(0);" class="btn btn-sm red" onclick="javascript:void window.open('nfc_cancelar.php?id_venda=<?php echo Filter::$id; ?>&id_caixa=<?php echo $id_caixa; ?>&historico=<?php echo $historico; ?>&total_dinheiro=<?php echo $total_dinheiro; ?>','<?php echo lang('VENDAS_CANCELAR_FISCAL'); ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VENDAS_CANCELAR_FISCAL'); ?>"><i class="fa fa-minus-circle">&nbsp;&nbsp;</i><?php echo lang('VENDAS_CANCELAR_FISCAL'); ?></a>
																	<?php 	endif; ?>
																	<?php endif; ?>
																<?php endif; ?>
																<?php if ($row_vendas->venda_agrupamento==1): ?>
																	<a href="index.php?do=vendas&acao=vendas_emitidas_lote" class="btn default"><?php echo lang('VOLTAR'); ?></a>
																<?php else: ?>
																	<a href="index.php?do=vendas_do_dia" class="btn default"><?php echo lang('VOLTAR'); ?></a>
																<?php endif; ?>
															</div>
														</div>
													</div>
													<div class="col-md-6">
													</div>
												</div>
											</div>
										</div>
										<?php //echo $core->doForm("processarCancelarVendaFiscal");
												?>
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
	case "adicionarclientevenda": ?>
		<?php $row_vendas = Core::getRowById("vendas", Filter::$id);
		$historico = (get('id_cadastro')) ? get('id_cadastro') : 0;
		$pagina = (get('pg')) ? get('pg') : 0;
		$voltar = ($pagina == 1) ? "index.php?do=vendas_do_dia" : "index.php?do=vendas_em_aberto";
		$venda_dinheiro = false;
		$total_dinheiro = 0;
		$status = getValue("status", "caixa", "id = " . $row_vendas->id_caixa);
		$id_caixa = ($status < 3) ? $row_vendas->id_caixa : 0;
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_CLIENTE_VENDA'); ?></small>
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
										<i class="fa fa-user font-blue"></i>
										<span
											class="font-blue"><?php echo lang('CADASTRO_CLIENTE_VENDA') . ": " . Filter::$id; ?></span>
									</div>
									<div class="actions btn-set">
										<a href="<?php echo $voltar; ?>"
											class="btn default"><?php echo lang('VOLTAR'); ?></a>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form autocomplete="off" action="javascript:;" class="form-horizontal">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('DATA_VENDA'); ?></label>
																<div class="form-control-static col-md-9 bold">
																	<?php echo exibedataHora($row_vendas->data_venda); ?>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('USUARIO'); ?></label>
																<div class="form-control-static col-md-9 bold">
																	<?php echo $row_vendas->usuario; ?>
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
																	class="control-label col-md-3"><?php echo lang('VALOR_PAGO'); ?></label>
																<div class="form-control-static col-md-9 bold">
																	<?php echo moedap($row_vendas->valor_pago); ?>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CAIXA'); ?></label>
																<div class="form-control-static col-md-9 bold">
																	<?php echo $row_vendas->id_caixa . " " . statusCaixa($status); ?>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
											</div>
										</div>
									</form>
									<!-- FINAL FORM-->
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-advance">
										<thead>
											<tr>
												<th><?php echo lang('PAGAMENTO'); ?></th>
												<th><?php echo lang('VALOR'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $cadastro->getFinanceiroVenda(Filter::$id);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													if ($exrow->id == 1) {
														$venda_dinheiro = true;
														$total_dinheiro = $exrow->valor_pago;
													}
													?>
													<tr>
														<td><?php echo pagamento($exrow->pagamento); ?></td>
														<td><span
																class="bold theme-font valor_total"><?php echo moedap($exrow->valor_pago); ?></span>
														</td>
													</tr>
												<?php endforeach; ?>
												<?php unset($exrow);
											endif; ?>
										</tbody>
									</table>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="horizontal-form" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-4">
														<div class="col-md-12">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label"><?php echo lang('CADASTRO_CLIENTE_VENDA_SELECIONE'); ?></label>
																	<div>
																		<input name="id_cadastro" id="id_cadastro"
																			type="hidden" />
																		<input name="cpf_cnpj" id="cpf_cnpj" type="hidden" />
																		<input type="text" autocomplete="off"
																			class="form-control caps listar_cliente"
																			name="cadastro"
																			placeholder="<?php echo lang('BUSCAR'); ?>">
																	</div>
																</div>
															</div>
															<div class="row selecionado ocultar">
																<div class="form-group">
																	<div>
																		<span
																			class="label label-success label-sm"><?php echo lang('CLIENTE_SELECIONADO'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row novocliente ocultar">
																<div class="form-group">
																	<div>
																		<span
																			class="label label-danger label-sm"><?php echo lang('CLIENTE_NAO_SELECIONADO'); ?></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<input name="id_venda" type="hidden" value="<?php echo Filter::$id; ?>" />
										<input name="pagina" type="hidden" value="<?php echo $pagina; ?>" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-12">
														<div class="row">
															<div class="col-md-offset-1 col-md-11">
																<button type="button"
																	class="btn btn-submit blue"><?php echo lang('CADASTRO_CLIENTE_VENDA'); ?></button>
																<a href="<?php echo $voltar; ?>"
																	class="btn default"><?php echo lang('VOLTAR'); ?></a>
															</div>
														</div>
													</div>
													<div class="col-md-6">
													</div>
												</div>
											</div>
										</div>
										<?php echo $core->doForm("processarVincularClienteVenda"); ?>
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
	case "vendasorcamento": ?>
		<?php if ($core->orcamento == 0)
			redirect_to("login.php"); ?>
		<?php if ($core->tipo_sistema == 1 || $core->tipo_sistema == 3)
			redirect_to("login.php"); ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORCAMENTOS'); ?></small></h1>
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
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('ORCAMENTOS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-condensed table-advance dataTable-desc">
										<thead>
											<tr>
												<th><?php echo lang('#'); ?></th>
												<th><?php echo lang('COD_ORCAMENTO_VENDA'); ?></th>
												<th><?php echo lang('DATA_ORCAMENTO'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('VL_TOTAL'); ?></th>
												<th><?php echo lang('DESCONTO'); ?></th>
												<th><?php echo lang('ACRESCIMO'); ?></th>
												<th><?php echo lang('VALOR_PAGAR'); ?></th>
												<th><?php echo lang('VENDEDOR'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>

											<?php
											$retorno = $cadastro->getOrcamentosVenda();
											if ($retorno):
												foreach ($retorno as $row):
													?>
													<tr>
														<td><?php echo $row->id ?></td>
														<td><?php echo $row->id ?></td>
														<td><?php echo exibedataHora($row->data_venda) ?></td>
														<td><?php echo $row->nome_cadastro ?></td>
														<td><?php echo moeda($row->valor_total) ?></td>
														<td><?php echo moeda($row->valor_desconto) ?></td>
														<td><?php echo moeda($row->valor_despesa_acessoria) ?></td>
														<td><?php echo moeda($row->valor_pago) ?></td>
														<td><?php echo $row->usuario ?></td>
														<td>
															<a href="index.php?do=vendas&acao=editarvendasorcamento&id=<?php echo $row->id ?>"
																class="btn btn-primary btn-sm" id="<?php echo $row->id ?>"
																title="Visualizar e editar orçamento: <?php echo $row->nome_cadastro ?>">
																<i class="fa fa-search"></i>
															</a>

															<a href="javascript:void(0);"
																onclick="javascript:void window.open('recibo_orcamento.php?id=<?php echo $row->id; ?>','<?php echo lang('IMPRIMIR_ORCAMENTO') . $row->id; ?>','width=360,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																title="Recibo orçamento" class="btn btn-sm grey">
																<i class="fa fa-file-o"></i>
															</a>
															<a href="imprimir_orcamento_produto.php?id_orcamento=<?php echo $row->id; ?>"
																target="_blank" class="btn btn-sm" id="<?php echo $row->id ?>"
																style="background-color: #ffc107; color: #fff"
																title="Imprimir orçamento">
																<i class="fa fa-print"></i>
															</a>

															<a href="#apagar-orcamento" class="btn btn-danger btn-sm apagar"
																id="<?php echo $row->id ?>" acao="apagarOrcamentoVenda"
																title="Deseja apagar este orçamento? <?php echo $row->nome_cadastro ?>">
																<i class="fa fa-trash"></i>
															</a>
														</td>
													</tr>

													<?php
												endforeach;
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
	case "editarvendasorcamento": ?>
		<?php
		$row_orc_venda = Core::getRowById("vendas", Filter::$id);

		$id_tabela = $cadastro->getTabelaVenda(Filter::$id);
		$nome_tabela = getValue("tabela", "tabela_precos", "id=" . $id_tabela);

		$valorTotalAVista = 0;
		$valorTotalNormal = 0;
		$produtosVenda = $cadastro->getValoresProdutosVenda(Filter::$id, $id_tabela);
		if ($produtosVenda) {
			foreach ($produtosVenda as $produtoVenda) {
				if (round($produtoVenda->valor, 2) != round($produtoVenda->valor_avista, 2) && round($produtoVenda->valor, 2) != round($produtoVenda->valor_tabela, 2)) {
					$valorTotalAVista += round($produtoVenda->valor, 2) * round($produtoVenda->quantidade, 2);
					$valorTotalNormal += round($produtoVenda->valor, 2) * round($produtoVenda->quantidade, 2);
				} else {
					$valorTotalNormal += round($produtoVenda->valor_tabela, 2) * round($produtoVenda->quantidade, 2);
					if (round($produtoVenda->valor_avista, 2) > 0) {
						$valorTotalAVista += round($produtoVenda->valor_avista, 2) * round($produtoVenda->quantidade, 2);
					} else {
						$valorTotalAVista += round($produtoVenda->valor_tabela, 2) * round($produtoVenda->quantidade, 2);
					}
				}
			}
		}
		$pagamentoAVista = $cadastro->vendaComPagamentosAVista(Filter::$id);
		?>
		<div class="modal fade" id="adicionarProdutoOrcamento" tabindex="-1" role="dialog" aria-labelledby=""
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="exampleModalLongTitle">
							<strong>
								<?php echo lang('ORCAMENTO_PRODUTOS_ADICIONAR') ?>
							</strong>
						</h4>
					</div>
					<form action="" autocomplete="off" method="POST" class="from-horizontal" name="produto_form"
						id="produto_form">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="form-group">
											<label class="control-label col-md-3"><?php echo lang('TABELA_PRECO'); ?></label>
											<div class="col-md-9">
												<input disabled type="text" class="form-control"
													value="<?php echo $nome_tabela; ?>">
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="form-group">
											<label class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
											<div class="col-md-9">
												<select class="select2me form-control" id="id_produto_vendas_orcamento"
													name="id_produto" data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
													<option value=""></option>
													<?php
													$retorno_row = $produto->getProdutosTabela($id_tabela);
													if ($retorno_row):
														foreach ($retorno_row as $srow):
															?>
															<option value="<?php echo $srow->id; ?>"
																data-codigo="<?php echo $srow->codigo ?>"
																data-codigobarras="<?php echo $srow->codigobarras ?>">
																<?php echo "#" . $srow->nome . " - " . $srow->codigobarras; ?>
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
									<br>
									<div class="row">
										<div class="form-group">
											<label class="control-label col-md-3"><?php echo lang('VALOR'); ?></label>
											<div class="col-md-9">
												<input type="text" class="form-control moeda" id="valor_venda_produto"
													name="valor">
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="form-group">
											<label class="control-label col-md-3"><?php echo lang('ESTOQUE'); ?></label>
											<div class="col-md-9">
												<input disabled type="text" class="form-control decimalp" id="estoque">
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="form-group">
											<label class="control-label col-md-3"><?php echo lang('QUANTIDADE'); ?></label>
											<div class="col-md-9">
												<input type="text" class="form-control decimalp" id="quantidade_finalizar_venda"
													name="quantidade">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input name="id_orcamento" id="id_orcamento" type="hidden" value="<?php echo Filter::$id; ?>" />
						<input name="id_tabela" id="id_ref_tabela" type="hidden" value="<?php echo $id_tabela; ?>" />
						<input name="id_cadastro" type="hidden" value="<?php echo $row_orc_venda->id_cadastro; ?>" />
						<input name="pagamentoAVista" type="hidden" id="pagamentoAVista" value="<?= $pagamentoAVista; ?>" />
						<input name="id_tabela" type="hidden" id="id_tabela_vendas" value="<?php echo $id_tabela; ?>">
						<div class="modal-footer">
							<button type="button" class="btn btn-submit"
								style="background-color: #fd7e14; color: #fff; border-radius: 5px; border: none;"><?php echo lang('ADICIONAR') ?></button>
							<button type="button" class="btn btn-secondary"
								data-dismiss="modal"><?PHP echo lang('SAIR') ?></button>
						</div>
						<?php echo $core->doForm("processarAdicionarProdOrcamento", "produto_form"); ?>
					</form>
				</div>
			</div>
		</div>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('VENDAS_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORCAMENTOS'); ?></small></h1>
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
										<i class="fa fa-search font-<?php echo $core->primeira_cor; ?>"></i>
										<span class="font-<?php echo $core->primeira_cor; ?>">
											<?php echo lang('ORCAMENTO_VISUALIZAR'); ?>
											&nbsp;<i
												class="fa fa-angle-right"></i>&nbsp;<small><?php echo "Nº " . $row_orc_venda->id ?></small>
										</span>
									</div>
								</div>
								<div class="portlet-body form">
									<form action="" class="">

										<?php if ($row_orc_venda->orcamento != 1): ?>
											<div class="alert alert-danger" role="alert">
												<h4>Ocorreu um erro!</h4>
												<h5>
													Este orçamento já virou uma venda. Visualize em
													<a href="<?php echo ($core->tipo_sistema == 4) ? "index.php?do=vendas&acao=vendaspedidosentrega" : "index.php?do=vendas_em_aberto" ?>"
														style="text-decoration: underline;">
														<?PHP echo lang('VENDAS_ABERTO') ?>.
													</a>
												</h5>
											</div>
										<?php else: ?>
											<div class="">
												<div class="row">
													<div class="col-md-2">
														<label for=""><?php echo lang('COD_ORCAMENTO_VENDA') ?></label>
														<input disabled type="text" class="form-control"
															value="<?php echo $row_orc_venda->id ?>">
													</div>
													<div class="col-md-4">
														<label for=""><?php echo lang('CLIENTE') ?></label>
														<?php $nome_cliente = getValue("nome", "cadastro", "id=" . $row_orc_venda->id_cadastro); ?>
														<input disabled type="text" class="form-control"
															value="<?php echo $nome_cliente ?>">
													</div>
													<div class="col-md-2">
														<label for=""><?php echo lang('VALOR_TOTAL') ?></label>
														<input disabled type="text" class="form-control"
															value="<?php echo moeda($row_orc_venda->valor_total) ?>">
													</div>
													<div class="col-md-2">
														<label for=""><?php echo lang('VALOR_PAGAR') ?></label>
														<input disabled type="text" class="form-control"
															value="<?php echo moeda($row_orc_venda->valor_total - $row_orc_venda->valor_desconto + $row_orc_venda->valor_despesa_acessoria) ?>">
													</div>
													<div class="col-md-2">
														<label for=""><?php echo lang('DATA_ORCAMENTO') ?></label>
														<input disabled type="text" class="form-control"
															value="<?php echo exibedataHora($row_orc_venda->data_venda) ?>">
													</div>
													<br><br><br><br><br>
													<div class="col-md-6">
														<label class=""><?php echo lang("OBSERVACAO") ?></label>
														<textarea disabled class="form-control" cols="15" rows="4" maxlength="250"
															style="resize: none;"><?php echo $row_orc_venda->observacao . "\n"; ?>
																																																		</textarea>
													</div>
													<div class="col-md-2">
														<label for=""><?php echo lang('DESCONTO') ?></label>
														<input disabled type="text" class="form-control"
															value="<?php echo moeda($row_orc_venda->valor_desconto) ?>">
													</div>
													<div class="col-md-2">
														<label for=""><?php echo lang('ACRESCIMO') ?></label>
														<input disabled type="text" class="form-control"
															value="<?php echo moeda($row_orc_venda->valor_despesa_acessoria) ?>">
													</div>
													<div class="col-md-2">
														<label for=""><?php echo lang('VENDEDOR') ?></label>
														<?php $nome_vendedor = getValue("nome", "usuario", "id=" . $row_orc_venda->id_vendedor); ?>
														<input disabled type="text" class="form-control"
															value="<?php echo $nome_vendedor ?>">
													</div>
													<br><br><br><br><br>
													<div class="col-md-2">
														<label for=""><?php echo lang('PAGAMENTO') ?></label>
														<?php $payments = $cadastro->ObterFormasPagamentoVenda($row_orc_venda->id); ?>
														<?php
														if ($payments) {
															foreach ($payments as $payment): ?>
																<input disabled type="text" class="form-control"
																	value="<?php echo $payment->tipo_pagamento ?>">
																<?php
															endforeach;
														} ?>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-2">
													<br><br>
													<a href="javascript:void(0)" id="<?php echo $row_orc_venda->id ?>"
														class="btn btn-sm transformarOrcamentoParaVenda"
														acao="transformarOrcamentoParaVenda"
														style="background-color: #1aa179; color: #fff; border-radius: 5px; border: none;"
														title="Deseja transformar o orçamento em uma venda aberta?">
														<i class="fa fa-check"></i> &nbsp;
														<?php echo lang('TRANSFORMAR_ORCAMENTO_EM_VENDA') ?>
													</a>
												</div>
												<div class="col-md-2">
													<br><br>
													<a href="imprimir_orcamento_produto.php?id_orcamento=<?php echo $row_orc_venda->id; ?>"
														target="_blank" id="<?php echo $row_orc_venda->id ?>" class="btn btn-sm"
														style="background-color: #ffc107; color: #fff; border-radius: 5px; border: none;"
														title="Imprimir">
														<i class="fa fa-print"></i> &nbsp;
														<?php echo lang('IMPRIMIR') ?>
													</a>
												</div>
												<div class="col-md-2">
													<br><br>
													<a href="javascript:void(0)" id="<?php echo $row_orc_venda->id ?>"
														class="btn btn-sm apagar" acao="apagarOrcamentoVenda"
														style="background-color: #b02a37; color: #fff; border-radius: 5px; border: none;"
														title="Deseja apagar orçamento?">
														<i class="fa fa-ban"></i> &nbsp;
														<?php echo lang('ORCAMENTO_CANCELAR_TITULO') ?>
													</a>
												</div>
											</div>
											<div class="row">
												<div id="aviso-orcamento" class="alert alert-danger" style="display:none"
													role="alert">
													Não é possível transformar orçamento em venda! Há um ou mais produtos validando
													estoque que não possuem a quantidade necessária para venda.
												</div>
											</div>
										</form>
										<hr>
										<div class="pull-right">
											<a href="javascrtipt:void(0)" class="btn btn-sm adicionarProdutoOrcamento"
												data-toggle="modal" data-target="#adicionarProdutoOrcamento"
												style="background-color: #fd7e14; color: #fff; border-radius: 5px; border: none;"
												title="Adicionar produtos">
												<i class="fa fa-plus-square"></i> &nbsp;
												<?php echo lang('ORCAMENTO_PRODUTOS_ADICIONAR') ?>
											</a>
										</div>
										<br>
										<br>
										<div style="display: flex; left: 20px; ">
											<p class="help-block">Legenda:</p> &nbsp;&nbsp;&nbsp;
											<p
												style="color: #a94442; background-color: #f2dede; padding: 5px; border: 1px solid #a94442;">
												Estoque zerado, negativo ou insuficiente</p>
										</div>
										<table class="table table-bordered table-condensed table-advance dataTable">
											<thead>
												<tr>
													<th>#</th>
													<th width="30%"><?php echo lang('PRODUTO') ?></th>
													<th width="10%"><?php echo lang('ESTOQUE') ?></th>
													<th width="10%"><?php echo lang('QUANTIDADE') ?></th>
													<th width="10%"><?php echo lang('VALOR') ?></th>
													<th width="10%"><?php echo lang('DESCONTO') ?></th>
													<th width="10%"><?php echo lang('ACRESCIMO') ?></th>
													<th width="10%"><?php echo lang('VALOR_TOTAL') ?></th>
													<th width="10%"><?php echo lang('ACOES') ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$row_orcamento = $cadastro->getOrcamentoById(Filter::$id);
												$total_valor = 0;
												$total_quantidade = 0;
												$total_desconto = 0;
												$total_acrescimo = 0;
												if ($row_orcamento):
													foreach ($row_orcamento as $rowv):
														if ((int) $rowv->valida_estoque && (floatval(str_replace(',', '', $rowv->estoque)) - floatval(str_replace(',', '', $rowv->quantidade))) < 0) { ?>
															<script>
																$(document).ready(function () {
																	$('#aviso-orcamento').css('display', 'block')
																	$(".transformarOrcamentoParaVenda").each(function () {
																		var id = $(this).attr('id');
																		var button = $('<a></a>', {
																			id: id,
																			class: 'btn btn-sm',
																			style: 'background-color: #1aa179; color: #fff; border-radius: 5px; border: none;',
																			title: 'Há um produto validando estoque, que não tem a quantidade necessária para venda!',
																			disabled: true,
																			html: '<i class="fa fa-check"></i> &nbsp;' + '<?php echo lang("TRANSFORMAR_ORCAMENTO_EM_VENDA") ?>'
																		});
																		$(this).replaceWith(button);
																	});
																});
															</script>
														<?php }
														$total_valor += $rowv->valor_total - $rowv->valor_desconto + $rowv->valor_despesa_acessoria;
														$total_quantidade += $rowv->quantidade;
														$total_desconto += $rowv->valor_desconto;
														$total_acrescimo += $rowv->valor_despesa_acessoria;
														?>
														<tr <?php echo ($rowv->estoque <= 0 || (floatval(str_replace(',', '', $rowv->estoque)) - floatval(str_replace(',', '', $rowv->quantidade))) < 0) ? "class='danger'" : ""; ?>>
															<td><?php echo $rowv->id ?></td>
															<td><?php echo $rowv->nome ?></td>
															<td><?php echo decimalp($rowv->estoque) ?></td>
															<td><?php echo decimalp($rowv->quantidade) ?></td>
															<td><?php echo moeda($rowv->valor) ?></td>
															<td><?php echo moeda($rowv->valor_desconto) ?></td>
															<td><?php echo moeda($rowv->valor_despesa_acessoria) ?></td>
															<td><?php echo moeda($rowv->valor_total - $rowv->valor_desconto + $rowv->valor_despesa_acessoria) ?>
															</td>
															<td>
																<a href="javascript:void(0)" class="btn btn-sm btn-danger apagar"
																	id="<?php echo $rowv->id ?>" acao="apagarProdutoOrcamento">
																	<i class="fa fa-trash"></i>
																</a>
																<button type='button' class='btn btn-sm blue edit-orcamento'
																	data-id="<?php echo $rowv->id ?>"
																	data-quantidade="<?php echo decimalp($rowv->quantidade) ?>"
																	data-fluxo='orcamento' data-toggle='modal'
																	data-target='#modal-editar-orcamento' data-whatever='@small'
																	data-id='$value->id'><i class='fa fa-pencil'></i></button>
															</td>
														</tr>
														<?php
													endforeach;
													unset($rowv);
												endif;
												?>
											</tbody>
											<tfoot>
												<tr>
													<th colspan="3"><?php echo lang("TOTAL"); ?></th>
													<th><?php echo decimalp($total_quantidade) ?></th>
													<th></th>
													<th><?php echo moeda($total_desconto) ?></th>
													<th><?php echo moeda($total_acrescimo) ?></th>
													<th><?php echo moeda($total_valor) ?></th>
													<th></th>
												</tr>
											</tfoot>
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
		<div class="modal fade" data-backdrop="static" data-keyboard="false" id="modal-editar-orcamento" tabindex="-1"
			role="dialog" aria-labelledby="modalEditar" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="width: 60%">
					<div class="modal-header">
						<label class="modal-title fw-bold font-sigesis-cor-1">Editar Quantidade do Produto</label>
					</div>
					<form id="form-editar-orcamento" name="form-editar-orcamento" action="" autocomplete="off" method="POST"
						style="margin-top: 1%">
						<div class="modal-body">
							<div class="row" style="margin-top: 1%">
								<div class="col-md-9">
									<label for="quantidade" class="form-label">Quantidade:</label>
									<input name="quantidade" id="quantidade" placeholder="0,000" type="text"
										class="cadastro decimalp form-control">
								</div>
							</div>
						</div>
						<input name="id_prod_orcamento" id="id_prod_orcamento" type="hidden" value="0" />
						<input name="id_orcamento" id="id_orcamento" type="hidden" value="<?php echo $row_orc_venda->id ?>" />
						<div class="modal-footer">
							<button type="button" class="btn red" data-dismiss="modal">Cancelar</button>
							<button id="submit-edit-orcamento" type="button"
								class="btn sigesis-cor-1 btn-submit">Editar</button>
						</div>
						<?php echo $core->doForm("processarAlterarQuantidadeProdOrcamento", "form-editar-orcamento"); ?>
					</form>
				</div>
			</div>
		</div>
		<?php break; ?>
	<?php case "impressaoproducao": ?>
		<?php
		if (!$usuario->is_Todos()):
			print Filter::msgInfo(lang('NAOAUTORIZADO'), false);
			return;
		endif;
		$dataini = (get('dataini')) ? get('dataini') : date("d/m/Y");
		$datafim = (get('datafim')) ? get('datafim') : date("d/m/Y");
		//Tipo => 1 = filtro por data de entrega, 0 = filtro por data da venda
		$tipo = (get('tipo')) ? 1 : 0;
		?>
		<script type="text/javascript">
			$(document).ready(function () {

				$('#filtrovenda').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=vendas&acao=impressaoproducao&dataini=' + dataini + '&datafim=' + datafim + '&tipo=0';
				});

				$('#filtroentrega').click(function () {
					var dataini = $("#dataini").val();
					var datafim = $("#datafim").val();
					window.location.href = 'index.php?do=vendas&acao=impressaoproducao&dataini=' + dataini + '&datafim=' + datafim + '&tipo=1';
				});

			});
		</script>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('CONTROLE_VENDAS'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CONTROLE_VENDAS_OS'); ?></small>
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
							<div class="portlet light">
								<div class="portlet-body">
									<form autocomplete="off" class="form-inline">
										<div class="form-group">
											<?php echo lang('DATA_INICIAL'); ?>
											&nbsp;&nbsp;&nbsp;
											<input type="text" class="form-control input-medium calendario data" name="dataini"
												id="dataini" value="<?php echo $dataini; ?>">
											&nbsp;
											<?php echo lang('DATA_FINAL'); ?>
											&nbsp;&nbsp;&nbsp;
											<input type="text" class="form-control input-medium calendario data" name="datafim"
												id="datafim" value="<?php echo $datafim; ?>">
											&nbsp;
											<button type="button" id="filtrovenda"
												class="btn <?php echo $core->primeira_cor; ?>"><i
													class="fa fa-calendar-o" /></i>&nbsp;<?php echo lang('FILTRO_DATA_VENDA'); ?></button>
											<button type="button" id="filtroentrega"
												class="btn <?php echo $core->primeira_cor; ?>"><i
													class="fa fa-truck" /></i>&nbsp;<?php echo lang('FILTRO_DATA_ENTREGA'); ?></button>
										</div>
									</form>
								</div>
							</div>
							<!-- INICIO TABELA -->
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-shopping-car font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('CONTROLE_VENDAS_OS'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<div>
										<h4>
											<i class="fa fa-shopping-cart font-<?php echo $core->primeira_cor; ?>"></i>
											<span
												class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('VENDAS_TITULO'); ?></span>
										</h4>
									</div>
									<table id="tabela_producao"
										class="table table-bordered table-striped table-condensed table-advance dataTable-asc">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('COD_VENDA'); ?></th>
												<th><?php echo lang('FILTRO_DATA_VENDA'); ?></th>
												<th><?php echo lang('FILTRO_DATA_ENTREGA'); ?></th>
												<th><?php echo lang('CLIENTE'); ?></th>
												<th><?php echo lang('VENDEDOR'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$total = 0;
											$retorno_row = $cadastro->getVendasProducao($dataini, $datafim, $tipo);
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													?>
													<tr>
														<td><?php echo ($tipo == 0) ? $exrow->data_venda : $exrow->data_entrega; ?></td>
														<td><?php echo $exrow->id; ?></td>
														<td><?php echo exibedata($exrow->data_venda); ?></td>
														<td><?php echo exibedata($exrow->data_entrega); ?></td>
														<td>
															<a
																href="index.php?do=cadastro&acao=historico&id=<?php echo $exrow->id_cadastro; ?>"><?php echo $exrow->cadastro; ?></a>
														</td>
														<td><?php echo $exrow->vendedor; ?></td>
														<td width="150px">

															<a href="javascript:void(0);"
																onclick="javascript:void window.open('reciboProducao.php?id=<?php echo $exrow->id; ?>','<?php echo lang('IMPRIMIR_RECIBO_PRODUCAO') . ': ' . $exrow->id; ?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;"
																title="<?php echo lang('IMPRIMIR_RECIBO_PRODUCAO'); ?>"
																class="btn btn-sm yellow-casablanca btn-fiscal"><i
																	class="fa fa-file-o"></i></a>

															<a href="javascript:void(0);"
																onclick="javascript:void window.open('pdf_romaneioProducao.php?id=<?php echo $exrow->id; ?>','<?php echo "CODIGO: " . $exrow->id; ?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"
																title="<?php echo lang('VER_ROMANEIO'); ?>"
																class="btn btn-sm yellow-gold"><i
																	class="fa fa-truck btn-fiscal"></i></a>

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

<?php if (isset($_GET["id_venda"])):
	$id_venda = get('id_venda');
	?>
	<script>
		function getStatus() {
			if (!window.Notification) {
				return "unsupported";
			}
			return window.Notification.permission;
		}
		// get permission Promise
		function getPermission() {
			return new Promise((resolve, reject) => {
				Notification.requestPermission(status => {
					var status = getStatus();
					if (status == 'granted') {
						resolve();
					} else {
						reject(status);
					}
				});
			});
		};
		getPermission()
			.then(function () {
				var n = new Notification("Venda realizada", {
					body: "Código da venda: <?php print $id_venda; ?>\nFAVOR DIRIGIR AO CAIXA \nPARA FINALIZAR A VENDA!"
				});
			}).catch(function (status) {
				console.log('Had no permission!');
			});
	</script>
<?php endif; ?>