<?php
  /**
   * Imprimir Detalhes Item
   *
   */
  define("_VALID_PHP", true);
  
	require_once("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	$id = get('id');
	$row = Core::getRowById("cadastro_vendas", $id);
	$item = getValue("nome","produto", "id = ".$row->id_produto);
	
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
									<i class="fa fa-info font-<?php echo $core->primeira_cor;?>"></i>								
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('DETALHES_ITEM');?></span>
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
												<tbody>	
													<tr>
														<th><?php echo lang('CLIENTE');?></th>
														<td><?php echo getValue("nome", "cadastro", "id=".$row->id_cadastro);?></td>
													</tr>	
													<tr>
														<th><?php echo lang('CAIXA');?></th>
														<td><?php echo $row->id_caixa;?></td>
													</tr>	
													<tr>
														<th><?php echo lang('COD_VENDA');?></th>
														<td><?php echo $row->id_venda;?></td>
													</tr>	
													<tr>
														<th><?php echo lang('DATA_VENDA');?></th>
														<td><?php echo exibedataHora($row->data);?></td>
													</tr>	
													<tr>
														<th><?php echo lang('USUARIO');?></th>
														<td><?php echo $row->usuario;?></td>
													</tr>	
													<tr>
														<th><?php echo lang('DESCRICAO');?></th>
														<td><?php echo $item;?></td>
													</tr>	
													<tr>
														<th><?php echo lang('VALOR');?></th>
														<td><?php echo moedap($row->valor);?></td>
													</tr>	
													<tr>
														<th><?php echo lang('QUANTIDADE');?></th>
														<td><?php echo moedap($row->quantidade);?></td>
													</tr>
													<tr>
														<th><?php echo lang('VALOR_DESCONTO');?></th>
														<td><?php echo moedap($row->valor_desconto);?></td>
													</tr>
													<tr>
														<th><?php echo lang('VALOR_ACRESCIMO');?></th>
														<td><?php echo moedap($row->valor_despesa_acessoria);?></td>
													</tr>													
													<tr>
														<th><?php echo lang('VALOR_TOTAL');?></th>
														<td><?php echo moedap(($row->valor*$row->quantidade)+$row->valor_despesa_acessoria-$row->valor_desconto);?></td>
													</tr>
													<tr>
														<th><?php echo lang('PAGO');?></th>
														<td><?php echo ($row->pago == 1) ? lang('MSIM') : lang('MNAO');?></td>
													</tr>		
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