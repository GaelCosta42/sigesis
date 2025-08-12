<?php
  /**
   * Imprimir Instrutores
   *
   */
  define("_VALID_PHP", true);
  
	require_once("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	$mes_ano = get('mes_ano');
	$id = get('id');
	$salarios = $financeiro->getSalarioColaborador($mes_ano, $id);
	
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
								<div class='actions noprint'>								
									<a class="btn btn-lg <?php echo $core->primeira_cor;?> hidden-print margin-bottom-5" onclick="javascript:Imprimir();">
									<?php echo lang('IMPRIMIR');?>&nbsp;&nbsp;<i class="fa fa-print"></i>
									</a>
								</div>
							</div>
							<div class="portlet-body">
											<table class="table table-bordered table-advance table-condensed">
												<thead>
													<tr>
														<th colspan="2"><?php echo lang('SALARIO_DETALHES');?></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th><?php echo lang('NOME');?>:&nbsp;&nbsp;</th>
														<td><?php echo $salarios['nome'];?></td>
													</tr>
													<tr>
														<th><?php echo lang('MES_ANO');?>:&nbsp;&nbsp;</th>
														<td><?php echo exibeMesAno($mes_ano, true, true);?></td>
													</tr>
													<tr>
														<th><?php echo lang('NIVEL');?>:&nbsp;&nbsp;</th>
														<td><?php echo $salarios['nivel'];?></td>
													</tr>
													<tr>
														<th><?php echo lang('SALARIO_TOTAL');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['salario']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('INSS');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['inss']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('TRANSPORTE');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['transporte']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('PLANO_SAUDE');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['planodesaude']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('TOTAL_DESCONTOS');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['totaldescontos']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('ADIANTAMENTO');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['adiantamentos']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('TOTAL_BONUS');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['totalbonus']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('SALARIO_FAMILIA');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['filhos']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('SALARIO_PAGAR');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['valor_pagar']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('VALE_TRANSPORTE');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['onibus']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('LANCHE');?>:&nbsp;&nbsp;</th>
														<td><?php echo moedap($salarios['lanche']);?></td>
													</tr>
												</tbody>
											</table>
									<?php 	
										$retorno_row = $financeiro->getDescontosAbertos($id);
										if($retorno_row):
									?>
											<table class="table table-bordered table-advance table-condensed">
												<?php 
													foreach ($retorno_row as $exrow):
												?>
													<tr>
														<th width="70%"><?php echo $exrow->observacao ." (".exibedata($exrow->data)."): ";?></th>
														<td><?php echo moedap($exrow->valor);?></td>
													</tr>
												<?php endforeach;?>
												<?php unset($exrow);?>
											</table>
									<?php  endif;?>
									
									<?php 	
										$retorno_row = $financeiro->getBonusAbertos($id);
										if($retorno_row):
									?>
											<table class="table table-bordered table-advance table-condensed">
												<?php 
													foreach ($retorno_row as $exrow):
												?>
													<tr>
														<th><?php echo $exrow->observacao ." (".exibedata($exrow->data)."): ";?></th>
														<td><?php echo moedap($exrow->valor);?></td>
													</tr>
												<?php endforeach;?>
												<?php unset($exrow);?>
											</table>
									<?php  endif;?>
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