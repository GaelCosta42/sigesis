<?php
  /**
   * Visualizar extrato
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  define("_VALID_PHP", true);
  
	require_once("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	$nome = "";
	$saldoinicial = 0;
	$saldofinal = 0;
	$extrato_row = false;
	$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y', strtotime('-15 days')); 
	$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y'); 
	$id_banco = (get('id_banco')) ? get('id_banco') : -1;
	if($id_banco > 0) {
		$nome = getValue("banco","banco","id = ".$id_banco);
		$saldoinicial = $faturamento->getSaldoInicial($dataini, $id_banco);
		$saldofinal = $faturamento->getSaldoTotal($datafim, $id_banco);
		$extrato_row = $extrato->getExtrato_view($dataini, $datafim, $id_banco);
	}
	
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
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('EXTRATO')." - ".$nome." - ".$dataini." a ".$datafim;?></span>
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
													<th><?php echo lang('DATA');?></th>
													<th><?php echo lang('DESCRICAO');?></th>
													<th width="100px"><?php echo lang('VALOR');?></th>
													<th width="100px"><?php echo lang('SALDO');?></th>
													<th><?php echo lang('TIPO');?></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><?php echo ($dataini);?></td>
													<td><?php echo lang('SALDO');?></td>
													<td>-</td>
													<td><strong <?php echo ($saldoinicial < 0) ? 'class="font-red"' : 'class="font-green"'?>><?php echo moeda($saldoinicial);?></strong></td>	
													<td>-</td>
													
												</tr>
													<?php 	
														$saldo = $saldoinicial;
														if($extrato_row):
														foreach ($extrato_row as $exrow):
														$descricao = '';
														if($exrow->tipo == 'D') {
															$saldo -= $exrow->valor;
															if($exrow->ti_ch == 1) {
																$descricao = 'CHEQUE - ';												
															}
															$descricao .= $exrow->descricao;
														} elseif($exrow->tipo == 'C') {
															$saldo += $exrow->valor;
															$descricao = $exrow->descricao;
														} else {
															$saldo += $exrow->valor;
															$descricao = $exrow->conta;
														}
													?>
													<tr>
														<td><?php echo exibedata($exrow->data_pagamento);?></td>
														<td><?php echo $descricao;?></td>
														<td><strong <?php echo ($exrow->tipo == 'D') ? 'class="font-red"' : 'class="font-green"'?>><?php echo moeda($exrow->valor);?></strong></td>	
														<td><strong <?php echo ($saldo < 0) ? 'class="font-red"' : 'class="font-green"'?>><?php echo moeda($saldo);?></strong></td>	
														<td><?php echo $exrow->tipo;?></td>
													</tr>
													<?php 	endforeach;?>
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