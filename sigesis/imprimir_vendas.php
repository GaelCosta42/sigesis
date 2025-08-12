<?php
  /**
   * Imprimir Vendas
   *
   */
  define("_VALID_PHP", true);
  
	require_once("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	$id = get('id');
	$row = Core::getRowById("vendas", $id);
	$id_vendedor = $row->id_vendedor;
	$id_cadastro = $row->id_cadastro;
	$data_venda = $row->data_venda;
	$nome_cliente = ($id_cadastro) ? getValue("nome", "cadastro", "id = ".$id_cadastro) : "SEM CLIENTE";
	$nome_vendedor = ($id_vendedor == 0) ? lang('CADASTRO_VENDEDOR_SEM') : getValue("nome", "usuario", "id = ".$id_vendedor);
	
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
<meta name="keywords" content="Vale Telecom, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis, telecom" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Vale Telecom"/>

<!-- Title -->
<title><?php echo $core->empresa;?></title>

<!-- Favicons -->
<link rel="shortcut icon" href="./assets/img/favicon.png">
<link rel="apple-touch-icon" href="./assets/img/favicon_60x60.png">
<link rel="apple-touch-icon" sizes="76x76" href="./assets/img/favicon_76x76.png">
<link rel="apple-touch-icon" sizes="120x120" href="./assets/img/favicon_120x120.png">
<link rel="apple-touch-icon" sizes="152x152" href="./assets/img/favicon_152x152.png">
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
									<i class="fa fa-history font-<?php echo $core->primeira_cor;?>"></i>								
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('HISTORICO_VENDA');?></span></br></br>
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('NOME').": ".$nome_cliente;?></span></br></br>
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CODIGO_VENDA').": ".$id;?></span></br>
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('DATA_VENDA').": ".exibedataHora($data_venda);?></span></br>
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('VENDEDOR').": ".$nome_vendedor;?></span>
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
											<h4>
												<i class="fa fa-barcode font-<?php echo $core->primeira_cor;?>"></i>								
												<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PRODUTOS');?></span>
											</h4>
										</td>
									</tr>
									<tr>
										<td>
											<table class="table table-bordered table-striped table-condensed table-advance">
												<thead>
													<tr>
														<th>#</th>
														<th><?php echo lang('TABELA');?></th>
														<th><?php echo lang('COD_PRODUTO');?></th>
														<th><?php echo lang('PRODUTO');?></th>
														<th><?php echo lang('CFOP');?></th>
														<th><?php echo lang('NCM');?></th>
														<th><?php echo lang('CSOSN_CST');?></th>
														<th><?php echo lang('CEST');?></th>
														<th><?php echo lang('VALOR');?></th>
														<th><?php echo lang('QUANT');?></th>
														<th><?php echo lang('DESCONTO');?></th>
														<?php if ($row->valor_troca > 0): ?>
														<th><?php echo lang('VALOR_TROCA_TITULO');?></th>
														<?php endif; ?>
														<th><?php echo lang('ACRESCIMO');?></th>
														<?php if($row->voucher_crediario > 0): ?>
														<th width="110px"><?php echo lang('SALDO_CREDIARIO');?></th>
														<?php else: ?>
															<th width="110px"><?php echo lang('VL_TOTAL');?></th>
														<?php endif; ?>
													</tr>
												</thead>
												<tbody>
													<?php 	
														$descontos = 0;
														$acrescimos = 0;
														$total = 0;
														$retorno_row = $cadastro->getProdutosVenda($row->id);
														if($retorno_row):
															$qtde_itens = count($retorno_row);
															$contagem = $qtde_itens;
															foreach ($retorno_row as $exrow):
																$acrescimos += $exrow->valor_despesa_acessoria;
																$total += $exrow->valor_total;
																$porcentagem_troca = ($row->valor_troca*100)/$row->valor_total;
																$valor_troca = ($porcentagem_troca*$exrow->valor_total)/100;
																$valor_troca = round($valor_troca,2);
																$descontos += ($exrow->valor_desconto-$valor_troca);												
													?>
														<tr>
															<td><?php echo ($contagem);?></td>
															<td><?php echo $exrow->tabela;?></td>
															<td><?php echo $exrow->codigo;?></td>
															<td><?php echo $exrow->produto;?></td>
															<td><?php echo $exrow->cfop;?></td>
															<td><?php echo $exrow->ncm;?></td>
															<td><?php echo $exrow->icms_cst;?></td>
															<td><?php echo $exrow->cest;?></td>
															<td><?php echo moedap($exrow->valor);?></td>
															<td><?php echo decimalp($exrow->quantidade);?></td>
															<td>
																<?php 
																	// echo moedap($exrow->valor_desconto-$valor_troca);
																	echo ($row->voucher_crediario > 0)
																	? moedap($exrow->valor_desconto-$valor_troca + $row->voucher_crediario) 
																	: moedap($exrow->valor_desconto-$valor_troca);
																?>
															</td>
															<?php if ($row->valor_troca > 0): ?>
																<td><?php echo moeda($row->valor_troca/$qtde_itens);?></td>
															<?php endif; ?>
															<td><?php echo moedap($exrow->valor_despesa_acessoria);?></td>
															
															<?php if($row->voucher_crediario > 0): ?>
															<td>
																<span class="bold theme-font">
																	<?php echo moeda($row->voucher_crediario);?>
																</span>
															</td>
															<?php else: ?>
															<td>
																<span class="bold theme-font valor_total">
																	<?php 
																		echo moeda($exrow->valor_total+$exrow->valor_despesa_acessoria-$exrow->valor_desconto) ;
																	?>
																</span>
															</td>
															<?php endif; ?>
														</tr>
													<?php 	$contagem--;
															endforeach;?>
												</tbody>
												<tfoot>
														<tr>
															<td colspan="10"><strong><?php echo lang('TOTAL');?></strong></td>
															<td><strong><?php echo moeda($descontos + $row->voucher_crediario);?></strong></td>
															<?php if ($row->valor_troca > 0): ?>
															<td><strong><?php echo moeda($row->valor_troca);?></strong></td>
															<?php endif; ?>
															<td><strong><?php echo moeda($acrescimos);?></strong></td>
															<?php if($row->voucher_crediario > 0): ?>
																<td><strong><?php echo " - ";?></strong></td>
															<?php else: ?>
																<td><strong><?php echo moeda($total+$acrescimos-$descontos);?></strong></td>
															<?php endif; ?>
														</tr>
												</tfoot>
												<?php unset($exrow);
													  endif;?>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<h4>
												<i class="fa fa-usd font-<?php echo $core->primeira_cor;?>"></i>								
												<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PAGAMENTOS');?></span>
											</h4>
										</td>
									</tr>
									<tr>
										<td>
											<table class="table table-bordered table-striped table-condensed table-advance">
												<thead>
													<tr>
														<th><?php echo lang('PAGAMENTO');?></th>
														<th><?php echo lang('PARCELAS');?></th>
														<th width="110px"><?php echo lang('VL_TOTAL');?></th>
													</tr>
												</thead>
												<tbody>
												<?php 	
														$total = 0;
														$retorno_row = $cadastro->getPagamentosVenda($row->id);
														if($retorno_row):
															foreach ($retorno_row as $exrow):
																$total += $exrow->valor_pago;	
												?>
													<tr>
														<td><?php echo pagamento($exrow->pagamento);?></td>
														<td><?php echo ($exrow->pagamento) ? $exrow->total_parcelas : pagamento($exrow->pagamento); ?></td>
														<td><span class="bold theme-font valor_total"><?php echo moeda($exrow->valor_pago);?></span></td>
													</tr>
												<?php 		endforeach;?>
												</tbody>
												<tfoot>
														<tr>
															<td colspan="2"><strong><?php echo lang('TOTAL');?></strong></td>
															<td><strong><?php echo moeda($total);?></strong></td>
														</tr>
												</tfoot>
												<?php unset($exrow);
													  endif;?>
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