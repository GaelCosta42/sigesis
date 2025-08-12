<?php
  /**
   * Imprimir Vendas do dia
   *
   */
  define("_VALID_PHP", true);
  
	require_once("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	$dataini = get('dataini');
	$datafim = get('datafim');
	$id_ref = get('id_ref');
	$nomeproduto = getValue("nome", "produto", "id = ".$id_ref);
	
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
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('VENDAS_PRODUTO')." - ".$nome." - ".$nomeproduto;?></span>
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
											<table class="table table-bordered table-striped table-advance table-condensed">
												<thead>
													<tr>
														<th><?php echo lang('CODIGO');?></th>
														<th><?php echo lang('CLIENTE');?></th>
														<th><?php echo lang('VALOR');?></th>
														<th><?php echo lang('VALOR_DESCONTO');?></th>
														<th><?php echo lang('VALOR_TOTAL');?></th>
														<th><?php echo lang('USUARIO');?></th>
														<th><?php echo lang('DATA');?></th>
													</tr>
													</thead>
													<tbody>
													<?php 	
														$retorno_row = $cliente->getVendasTipo($id_ref, $dataini, $datafim);
														$total = 0;
														$desconto = 0;
														$valor = 0;
														if($retorno_row):
															foreach ($retorno_row as $exrow):
																$total += $exrow->valor_total;
																$desconto += $exrow->valor_desconto;
																$valor += $exrow->valor;
													?>
														<tr>
															<td><?php echo $exrow->id;?></td>
															<td><?php echo $exrow->cliente;?></td>
															<td><?php echo moedap($exrow->valor);?></td>
															<td><?php echo moedap($exrow->valor_desconto);?></td>
															<td><span class="bold theme-font valor_total"><?php echo moedap($exrow->valor_total);?></span></td>
															<td><?php echo $exrow->usuario;?></td>
															<td><?php echo exibedataHora($exrow->data);?></td>
														</tr>
													<?php 	endforeach;?>
														<tr>
															<td colspan="2"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
															<td><strong><?php echo moedap($valor);?></strong></td>
															<td><strong><?php echo moedap($desconto);?></strong></td>
															<td><strong><?php echo moedap($total);?></strong></td>
															<td><strong></strong></td>
															<td><strong></strong></td>
														</tr>
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