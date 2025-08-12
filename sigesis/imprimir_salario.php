<?php
  /**
   * Imprimir Aluno
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
	  
	$instrutor = get('instrutor');
	$mes_ano = get('data');
	$dias = get('dias');
	$domingos = get('domingos');
	$salarios = $financeiro->calculoInstrutores($mes_ano, $instrutor, $dias, $domingos);
	
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
								<div class='actions btn-set noprint'>
									<button type="button" onclick="Imprimir()" class="btn btn-sm <?php echo $core->primeira_cor;?>">
										<i class="fa fa-print"></i>&nbsp;&nbsp;<?php echo lang('IMPRIMIR');?>
									</button>
								</div>
							</div>
							<div class="portlet-body">
								<table>
									<tr>
										<td>
											<table class="table table-bordered table-advance table-condensed">
												<thead>
													<tr>
														<th colspan="2"><?php echo lang('SALARIO_DETALHES');?></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th><?php echo lang('NOME');?>:&nbsp;&nbsp;</th>
														<td><?php echo $instrutor;?></td>
													</tr>
													<tr>
														<th><?php echo lang('MES_ANO');?>:&nbsp;&nbsp;</th>
														<td><?php echo exibeMesAno($mes_ano, true, true);?></td>
													</tr>
													<tr>
														<th><?php echo lang('HORAS');?>:&nbsp;&nbsp;</th>
														<td><?php echo $salarios['horas'];?></td>
													</tr>
													<tr>
														<th><?php echo lang('AULAS');?>:&nbsp;&nbsp;</th>
														<td><?php echo $salarios['aulas'];?></td>
													</tr>
													<tr>
														<th><?php echo lang('SALARIO_TOTAL');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['total']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('OUTROS_DESCONTOS');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['descontos']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('ADIANTAMENTO');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['adiantamentos']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('VALES');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['vales']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('SALARIO_PAGAR');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['valor_pagar']);?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<?php 	
										$retorno_row = $financeiro->getUnidadesInstrutor($mes_ano, $instrutor);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
									?>
									<tr>
										<td>
											<table class="table table-bordered table-advance table-condensed">
												
												<tbody>
													<tr>
														<th><?php echo lang('HORAS');?>:&nbsp;&nbsp;</th>
														<td><?php echo $salarios['horas'];?></td>
													</tr>
													<tr>
														<th><?php echo lang('AULAS');?>:&nbsp;&nbsp;</th>
														<td><?php echo $salarios['aulas'];?></td>
													</tr>
													<tr>
														<th><?php echo lang('SALARIO');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['valor_salario']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('BONUS_REMUNERACOES');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['bonus']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('DESCANSO');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['descanso']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('VALE_TRANSPORTE');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['onibus']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('SALARIO_TOTAL');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['salario_total']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('DESCONTO_TRANSPORTE');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['transporte']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('DESCONTO_INSS');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['inss']);?></td>
													</tr>
													<tr>
														<th><?php echo lang('VALOR_LIQUIDO');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($salarios['valor_liquido']);?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<?php endforeach;?>
									<?php unset($exrow);
										  endif;?>
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