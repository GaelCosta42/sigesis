<?php
  /**
   * Visualizar Caixa
   *
   */
  define("_VALID_PHP", true);

	require_once("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");

	$id_caixa = get('id_caixa');
	if($id_caixa == 0):
		echo lang('CAIXA_ERRO');
	else:
	$detalhes_row = $faturamento->getDetalhesCaixa($id_caixa);

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-BR" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="pt-BR" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-BR">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>

<!-- Meta -->
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="SIGESIS - Sistemas - VOCÊ NO CONTROLE DA SUA EMPRESA, em qualquer lugar... a qualquer momento!"/>
<meta name="keywords" content="vale telecom, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis, telecom" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Vale Telecom"/>

<!-- Title -->
<title><?php echo $core->empresa;?></title>

<!-- Favicons -->
<link rel="shortcut icon" href="./assets/img/favicon.png">
<link rel="apple-touch-icon" href="./assets/img//favicon_60x60.png">
<link rel="apple-touch-icon" sizes="76x76" href="./assets/img//favicon_76x76.png">
<link rel="apple-touch-icon" sizes="120x120" href="./assets/img//favicon_120x120.png">
<link rel="apple-touch-icon" sizes="152x152" href="./assets/img//favicon_152x152.png">

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="./assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="./assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="./assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="./assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="./assets/plugins/select2/select2.css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="./assets/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="./assets/css/plugins.css" rel="stylesheet" type="text/css">
<link href="./assets/css/layout.css" rel="stylesheet" type="text/css">
<link href="./assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color">
<link href="./assets/css/custom.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="./assets/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/typeahead/typeahead.css">
<!-- END THEME STYLES -->
</head>
<script Language="JavaScript">
	function Imprimir(){
	window.print();
	window.close();
	}
</Script>
<style type="text/css">
    @media print {
      .noprint { display: none; margin: 30px;}
	  .quebra_pagina {page-break-after:always;}
    }
</style>
<body>
<div class="page-container">
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<div class="row">
					<div class="col-md-12">
						<div class="portlet light">
							<div class='portlet-title'>
								<div class="caption">
									<i class="fa fa-inbox font-<?php echo $core->primeira_cor;?>"></i>
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CAIXA_DETALHES').": ".$id_caixa;?></span>
								</div>
								<div class='actions noprint'>
									<a class="btn btn-lg <?php echo $core->primeira_cor;?> hidden-print margin-bottom-5" onclick="javascript:Imprimir();">
									<?php echo lang('IMPRIMIR');?>&nbsp;&nbsp;<i class="fa fa-print"></i>
									</a>
								</div>
							</div>
							<div class="portlet-body">
								<table width="100%">
									<tr>
										<td>
											<table class="table table-bordered table-advance table-condensed">
												<thead>
													<tr>
														<th colspan="2"><?php echo lang('CAIXA_DETALHES');?></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th><?php echo lang('RESPONSAVEL_ABERTO');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->aberto;?></td>
													</tr>
													<tr>
														<th><?php echo lang('DATA_ABERTO');?>:&nbsp;&nbsp;</th>
														<td><?php echo exibedataHora($detalhes_row->data_abrir);?></td>
													</tr>
													<tr>
														<th><?php echo lang('RESPONSAVEL_FECHADO');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->fechado;?></td>
													</tr>
													<tr>
														<th><?php echo lang('DATA_FECHADO');?>:&nbsp;&nbsp;</th>
														<td><?php echo exibedataHora($detalhes_row->data_fechar);?></td>
													</tr>
													<tr>
														<th><?php echo lang('RESPONSAVEL_VALIDADO');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->validado;?></td>
													</tr>
													<tr>
														<th><?php echo lang('DATA_VALIDADO');?>:&nbsp;&nbsp;</th>
														<td><?php echo exibedataHora($detalhes_row->data_validar);?></td>
													</tr>
													<tr>
														<th><?php echo lang('STATUS');?>:&nbsp;&nbsp;</th>
														<td><?php echo statusCaixa($detalhes_row->status);?></td>
													</tr>
													<tr>
														<th><?php echo lang('USUARIO');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->usuario;?></td>
													</tr>
													<tr>
														<th><?php echo lang('DATA');?>:&nbsp;&nbsp;</th>
														<td><?php echo exibedataHora($detalhes_row->data);?></td>
													</tr>
												<tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<h4>
												<i class="fa fa-shopping-cart font-<?php echo $core->primeira_cor;?>"></i>
												<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('VENDAS_TITULO');?></span>
											</h4>
										</td>
									</tr>
									<tr>
										<td>
											<table class="table table-bordered table-striped table-advance table-condensed">
												<thead>
													<tr>
														<th><?php echo lang('COD_VENDA');?></th>
														<th><?php echo lang('CLIENTE');?></th>
														<th><?php echo lang('PAGAMENTOS');?></th>
														<th><?php echo lang('VALOR');?></th>
														<th><?php echo lang('VALOR_DESCONTO');?></th>
														<th><?php echo lang('VALOR_TOTAL');?></th>
														<th><?php echo lang('CANCELADA');?></th>
													</tr>
													</thead>
													<tbody>
													<?php
														$retorno_row = $faturamento->getVendasCaixa($id_caixa);
														$existeInfoVendas = 0;
														$total = 0;
														$desconto = 0;
														$pago = 0;
														if($retorno_row):
															$existeInfoVendas = 1;
															foreach ($retorno_row as $exrow):
																$row_pagamentos = $cadastro->getFinanceiro($exrow->id);
																$pagamentos = "";
																if ($row_pagamentos) {
																	foreach($row_pagamentos as $rpgto) {
																		$pagamentos .= ($pagamentos==="") ? pagamento($rpgto->pagamento) : '<br>'.pagamento($rpgto->pagamento);
																	}
																}
																$total += ($exrow->inativo) ? 0 : $exrow->valor_total;
																$desconto += ($exrow->inativo) ? 0 : $exrow->valor_desconto;
																$pago += ($exrow->inativo) ? 0 : $exrow->valor_pago-$exrow->troco;
													?>
														<tr>
															<td><?php echo $exrow->id;?></td>
															<td><?php echo $exrow->cadastro;?></td>
															<td><?php echo $pagamentos;?></td>
															<td><?php echo moedap($exrow->valor_total);?></td>
															<td><?php echo moedap($exrow->valor_desconto);?></td>
															<td><span <?php echo ($exrow->inativo) ? 'class="bold font-red"' : 'class="bold font-green"';?>><?php echo moedap($exrow->valor_pago-$exrow->troco);?></span></td>
															<td><?php echo ($exrow->inativo) ? "SIM" : "NÃO";?></td>
														</tr>
													<?php 	endforeach;
															unset($exrow);
														endif;

														if ($existeInfoVendas):
													?>
															<tr>
																<td colspan="3"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
																<td><strong><?php echo moedap($total);?></strong></td>
																<td><strong><?php echo moedap($desconto);?></strong></td>
																<td><strong><?php echo moedap($pago);?></strong></td>
																<td><strong>-</strong></td>
															</tr>
													<?php
														else: ?>
															<tr><td colspan="7"><?php echo lang('CAIXA_VENDA_NAO'); ?></td></tr>
													<?php
														endif; ?>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<h4>
												<i class="fa fa-usd font-<?php echo $core->primeira_cor;?>"></i>
												<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CAIXA_RETIRADAS');?></span>
											</h4>
										</td>
									</tr>
									<tr>
										<td>
											<table class="table table-bordered table-striped table-advance table-condensed">
												<thead>
													<tr>
														<th><?php echo lang('FORNECEDOR');?></th>
														<th><?php echo lang('DESCRICAO');?></th>
														<th><?php echo lang('PLANO_CONTAS');?></th>
														<th width="135px"><?php echo lang('VALOR');?></th>
													</tr>
												</thead>
												<tbody>
												<?php
													$retorno_row = $despesa->getCaixaListaRetirada($id_caixa);
													$valor_retirada = 0;
													if($retorno_row):
														foreach ($retorno_row as $exrow):
															$valor_retirada += $exrow->valor;
												?>
													<tr>
														<td><?php echo $exrow->cadastro;?></td>
														<td><?php echo $exrow->descricao;?></td>
														<td><?php echo $exrow->conta;?></td>
														<td><span class="bold theme-font valor_total"><?php echo moedap($exrow->valor);?></span></td>
													</tr>
												<?php 	endforeach;?>
													<tr>
														<td colspan="3"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
														<td><strong><?php echo moedap($valor_retirada);?></strong></td>
													</tr>
												<?php unset($exrow);
													  else: ?>
													  <tr><td colspan="4"><?php echo lang('CAIXA_RETIRADAS_NAO'); ?></td></tr>
												<?php endif;?>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<h4>
												<i class="fa fa-usd font-<?php echo $core->primeira_cor;?>"></i>
												<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CAIXA_RESUMO');?></span>
											</h4>
										</td>
									</tr>
									<tr>
										<td>
											<table class="table table-bordered table-striped table-advance table-condensed">
												<thead>
													<tr>
														<th width="50%"><?php echo lang('DESCRICAO');?></th>
														<th><?php echo lang('VALOR');?></th>
														<th><?php echo lang('DETALHES');?></th>
													</tr>
												</thead>
												<tbody>
												<?php
													$valor_abertura = $faturamento->getCaixaAbertura($id_caixa);
												?>
													<tr>
														<td><?php echo lang('CAIXA_ABERTURA');?></td>
														<td><span class='font-green'><?php echo moedap($valor_abertura);?></span></td>
														<td></td>
													</tr>
													<tr>
														<td><?php echo lang('CAIXA_TOTALRETIRADA');?></td>
														<td><span class='font-red'><?php echo moedap($valor_retirada);?></span></td>
														<td></td>
													</tr>
												<?php
													$retorno_row = $faturamento->getFinanceiroCaixa($id_caixa);
													$total = 0;
													$valor_pago = 0;
													$totaldinheiro = 0;

													if($retorno_row):
														foreach ($retorno_row as $exrow):
															$total += $valor_pago = $exrow->valor_pago;
															if($exrow->id_categoria==1){
																$totaldinheiro += $valor_pago;
												?>
																<tr>
																	<td><?php echo pagamento($exrow->pagamento);?></td>
																	<td><span class="bold theme-font valor_total"><?php echo moedap($valor_pago);?></span></td>
																	<td><?php echo $exrow->detalhes;?></td>
																</tr>
												<?php
															}
															if($exrow->id_categoria<>1){
												?>
															<tr>
																<td><?php echo pagamento($exrow->pagamento);?></td>
																<td><span class="bold theme-font valor_total"><?php echo moedap($valor_pago);?></span></td>
																<td><?php echo $exrow->detalhes;?></td>
															</tr>
												<?php
															}
														endforeach;
														$totaldinheiro -= $valor_retirada;
													?>
													<tr>
														<td><strong><?php echo lang('FINANCEIRO_TOTALCAIXA_VALOR');?></strong></td>
														<td colspan="2"><strong><?php echo moedap($total-$valor_retirada);?></strong></td>
													</tr>
													<tr>
														<td><strong><?php echo lang('FINANCEIRO_TOTALCAIXA_DINHEIRO');?></strong></td>
														<td colspan="2"><strong><?php echo moedap($totaldinheiro);?></strong></td>
													</tr>
												<?php unset($exrow);
													  endif;?>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<h4>
												<i class="fa fa-usd font-<?php echo $core->primeira_cor;?>"></i>
												<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CAIXA_MOVIMENTO');?></span>
											</h4>
										</td>
									</tr>
									<tr>
										<td>
											<table class="table table-bordered table-striped table-advance table-condensed">
												<thead>
													<tr>
														<th><?php echo lang('CODIGO_VENDA');?></th>
														<th><?php echo lang('CLIENTE');?></th>
														<th><?php echo lang('BANCO');?></th>
														<th><?php echo lang('PAGAMENTO_FORMA');?></th>
														<th><?php echo lang('DETALHES');?></th>
														<th><?php echo lang('VALOR_VENDA');?></th>
														<th><?php echo lang('VALOR_PAGO');?></th>
													</tr>
												</thead>
												<tbody>
												<?php
													$retorno_row = $faturamento->getMovimentoCaixa($id_caixa);
													if($retorno_row):
														$contador = 0;
														$total_pago = 0;
														foreach ($retorno_row as $exrow):
															$total_pago += $exrow->valor_pago;
															$detalhes = '';
															$categoria_pagamento = getValue("id_categoria","tipo_pagamento","id=".$exrow->tipo);
															if ($exrow->detalhe) {
																$detalhes = $exrow->detalhe;
															}elseif($exrow->tipo == 0) {
																$detalhes = pagamento($exrow->pagamento);
															}elseif($categoria_pagamento == 1 || $categoria_pagamento == 3 || $categoria_pagamento == 6) {
																$detalhes = 'Vendas';
															}elseif($categoria_pagamento == 2) {
																$detalhes = 'Vendas-NUMERO: ['.$exrow->numero_cheque.'] - '.$exrow->banco_cheque;
															}elseif($categoria_pagamento == 8) {
																$detalhes = 'Vendas-BANCO: '.$exrow->banco;
															}elseif($categoria_pagamento == 9) {
																$detalhes = "Vendas-PROMISSORIAS";
															} else {
																$detalhes = 'Vendas-PARCELA: ['.$exrow->parcelas_cartao.'] - '.$exrow->numero_cartao;
															}
												?>
													<tr <?php echo ($exrow->inativo) ? "class='font-red'" : "";?>>
														<?php if($exrow->id_venda):?>
														<td><a href="javascript:void(0);" onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id_venda;?>','<?php echo "CODIGO: ".$exrow->id_venda;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VER_DETALHES');?>"><?php echo $exrow->id_venda;?></a></td>
														<td><?php echo $exrow->cadastro;?></td>
														<?php else:
																$contador++;
																if ($contador > 1):
														?>
														<td colspan="2"><?php echo 'ADICIONADO AO CAIXA';?></td>
														<?php else:?>
														<td colspan="2"><?php echo 'ABERTURA DE CAIXA';?></td>
														<?php endif;?>
														<?php endif;?>
														<td><?php echo ($exrow->id_banco) ? getValue("banco","banco","id=".$exrow->id_banco) : '-';?></td>
														<td><?php echo pagamento($exrow->pagamento);?></td>
														<td><?php echo $detalhes;?></td>
														<td><?php echo moedap($exrow->valor_total_venda);?></td>
														<td><span class="bold theme-font"><?php echo ($exrow->inativo) ? "-" : moedap($exrow->valor_pago);?></span></td>
													</tr>
												<?php endforeach; ?>
														<tr>
															<td colspan="6"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
															<td><strong><?php echo moedap($total_pago);?></strong></td>
														</tr>
												<?php  unset($exrow);
													else: ?>
														<tr><td colspan="7"><?php echo lang('CAIXA_MOVIMENTO_NAO'); ?></td></tr>
												<?php  endif;?>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<h4>
												<i class="fa fa-usd font-<?php echo $core->primeira_cor;?>"></i>
												<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PRODUTOS');?></span>
											</h4>
										</td>
									</tr>
									<tr>
										<td>
											<table class="table table-bordered table-striped table-advance table-condensed">
												<thead>
													<tr>
														<th><?php echo lang('VENDA');?></th>
														<th><?php echo lang('CLIENTE');?></th>
														<th><?php echo lang('PRODUTO');?></th>
														<th style="width: 70px"><?php echo lang('VALOR');?></th>
														<th style="width: 70px"><?php echo lang('ACRESCIMO');?></th>
														<th style="width: 70px"><?php echo lang('DESCONTO');?></th>
														<th style="width: 70px"><?php echo lang('TOTAL');?></th>
														<th style="width: 70px"><?php echo lang('CUSTO');?></th>
														<th style="width: 70px"><?php echo lang('MARGEM');?></th>
														<th style="width: 70px"><?php echo lang('LUCRO');?></th>
														<th style="width: 70px"><?php echo lang('LUCRO_%');?></th>
														<th><?php echo lang('USUARIO');?></th>
														<th style="width: 100px"><?php echo lang('ACOES');?></th>
													</tr>
												</thead>
												<tbody>
												<?php
													$tquant = 0;
													$total = 0;
													$total_custo = 0;
													$total_margem = 0;
													$retorno_row = $faturamento->getVendasCaixaTipo($id_caixa);
													if($retorno_row):
														foreach ($retorno_row as $exrow):
														$valor_custo = $exrow->valor_custo;
														$custo = $valor_custo*$exrow->quantidade;
														$margem = ($custo) ? $exrow->valor_total/$custo : 0;
														$lucro = ($exrow->valor_total- $exrow->valor_desconto + $exrow->valor_despesa_acessoria) - $custo;
														$total_custo += $custo;
														$total_margem += $margem;
														$total += $exrow->valor_total + $exrow->valor_despesa_acessoria - $exrow->valor_desconto;
														$tquant++;
												?>
													<tr>
														<td><?php echo $exrow->id_venda;?></a></td>
														<td><?php echo $exrow->cadastro;?></a></td>
														<td><?php echo $exrow->produto;?></a></td>
														<td><?php echo moedap($exrow->valor);?></td>
														<td><?php echo moedap($exrow->valor_despesa_acessoria);?></td>
														<td><?php echo moedap($exrow->valor_desconto);?></td>
														<td><?php echo moedap($exrow->valor_total-$exrow->valor_desconto+$exrow->valor_despesa_acessoria);?></td>
														<td><?php echo moedap($custo);?></td>
														<td><?php echo decimalp($margem);?></td>
														<td><?php echo moedap($lucro);?></td>
														<td><?php echo ($lucro) ? fpercentual($lucro, $custo) : "-";?></td>
														<td><?php echo $exrow->usuario;?></a></td>
														<td>
															<a href="javascript:void(0);" onclick="javascript:void window.open('imprimir_detalhes_item.php?id=<?php echo $exrow->id;?>','<?php echo $exrow->cadastro;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VER_DETALHES');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('VER_DETALHES');?></a>
														</td>
													</tr>
												<?php endforeach;?>
												<?php unset($exrow);
													  endif;?>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="2"><strong><?php echo lang('TOTAL');?></strong></td>
														<td><strong><?php echo $tquant;?></strong></td>
														<td colspan="3"></td>
														<td><strong><?php echo moedap($total);?></strong></td>
														<td><strong><?php echo moedap($total_custo);?></strong></td>
														<td><strong><?php echo ($total_custo) ? decimalp($total/$total_custo) : 0;?></strong></td>
														<td><strong><?php echo moedap($total - $total_custo);?></strong></td>
														<td><strong><?php echo ($total - $total_custo) ? fpercentual(($total - $total_custo), $total_custo) : "-";?></strong></td>
														<td colspan="2"></td>
													</tr>
												</tfoot>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<h4>
												<i class="fa fa-usd font-<?php echo $core->primeira_cor;?>"></i>
												<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('VENDAS_ABERTO_USUARIO').$detalhes_row->aberto;?></span>
											</h4>
										</td>
									</tr>
									<tr>
										<td>
											<table class="table table-bordered table-striped table-advance table-condensed">
												<thead>
													<tr>
														<th>#</th>
														<th><?php echo lang('DATA_VENDA');?></th>
														<th><?php echo lang('CLIENTE');?></th>
														<th><?php echo lang('ITEM');?></th>
														<th><?php echo lang('VL_TOTAL');?></th>
														<th><?php echo lang('USUARIO');?></th>
													</tr>
												</thead>
												<tbody>
												<?php
													$retorno_row = $cadastro->getVendaAbertoUsuario($detalhes_row->responsavel);
													if($retorno_row):
														foreach ($retorno_row as $exrow):
															$produtos = $cadastro->geNomesProdutosDaVenda($exrow->id);
												?>
													<tr>
														<td><?php echo $exrow->id;?></td>
														<td><?php echo exibedata($exrow->data);?></td>
														<td><?php echo $exrow->cadastro;?></td>
														<td><?php echo $produtos;?></td>
														<td><span class="bold theme-font valor_total"><?php echo decimalp($exrow->valor_total);?></span></td>
														<td><?php echo $exrow->usuario;?></td>
													</tr>
												<?php endforeach;
													  unset($exrow);
													else: ?>
														<tr><td colspan="5"><?php echo lang('VENDAS_ABERTO_NAO'); ?></td></tr>
												<?php endif;?>
												</tbody>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>
<div class="quebra_pagina"></div>

<div class="noprint"></div>
</body>
</html>
<?php endif;?>