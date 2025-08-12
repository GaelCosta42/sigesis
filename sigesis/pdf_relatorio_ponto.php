<?php
  /**
   * Imprimir Mês
   *
   * @package Sistemas Sige Delivery
   * @author Fabio Alvarenga
   * @copyleft 2021
   * @version 3
   */
  define("_VALID_PHP", true);
  
	require_once("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8"/>
<title><?php echo lang('PONTO_RELATORIO_TITULO');?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="Sistemas Sige Delivery" name="description"/>
<meta content="Fabio Alvarenga" name="author"/>
<link rel="shortcut icon" href="./assets/img/favicon.ico"/>
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
<?php
	$ano = (get('ano')) ? get('ano') : date('Y');
	$mes = (get('mes')) ? get('mes') : date('m');
	$mes_ano = $mes . '/' . $ano ;
	$id_funcionario = (get('funcionario')) ? get('funcionario') : 0;
	$funcionario = "";
	$relatorio = (!empty($id_funcionario))
		? $pontoeletronico->getRelatorioPonto($ano,$mes,$id_funcionario)
		: []; 
?>
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
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PONTO_RELATORIO_TITULO').": ".$mes_ano;?></span>
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
														<th width="1px" style="white-space: nowrap;"><?php echo lang('DATA');?></th>
														<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_DIA');?></th>
														<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_ENTRADA1');?></th>
														<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_SAIDA1');?></th>
														<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_ENTRADA2');?></th>
														<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_SAIDA2');?></th>
														<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_ENTRADA3');?></th>
														<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_SAIDA3');?></th>
														<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_ENTRADA4');?></th>
														<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_SAIDA4');?></th>
														<th width="1px"><?php echo lang('PONTO_RELATORIO_HORAS');?></th>
														<th width="1px"><?php echo lang('PONTO_HORARIO_ABONO');?></th>
														<th width="1px"><?php echo lang('PONTO_HORARIO_TRABALHAR2');?></th>
														<th width="1px"><?php echo lang('PONTO_RELATORIO_SALDO');?></th>
													</tr>
												</thead>
												<tbody>
													<?php 	
														$total_horas_trabalhadas = 0;
														$total_horas_abono = 0;
														$total_horas_dia = 0;
														$saldo_mes = 0;
														if(!empty($relatorio['relatorio'])):
															foreach ($relatorio['relatorio'] as $exrow):
																$total_horas_trabalhadas += hora_para_segundos($exrow['horas_trabalhadas']);
																$total_horas_abono += hora_para_segundos($exrow['horas_abono']);
																$total_horas_dia += hora_para_segundos($exrow['horas_dia']);
																$saldo_mes += hora_para_segundos($exrow['saldo_dia']);
																$operacoes = $exrow['operacoes'];
																if ($exrow['status_saldo']=='negativo') {
																	$estilo = "class='danger'";
																} else if ($exrow['status_saldo']=='positivo') {
																	$estilo = "class='success'";
																} else if ($exrow['status_saldo']=='alerta') {
																	$estilo = "class='warning'";
																} else {
																	$estilo = '';
																}
													?>
																<tr <?php echo $estilo; ?>>
																	<td class="data_editar_ponto"><?php echo exibedata($exrow['data']); ?></td>
																	<td><?php echo $exrow['dia_semana'];?></td>
																	<td>
																		<?php echo (!empty($operacoes['entrada1']['horario'])) ? $operacoes['entrada1']['horario'] : '---'; ?>
																		<?php echo (!empty($operacoes['entrada1']) && $operacoes['entrada1']['usuario'] != 'app' ) ? '*' : '' ?>
																	</td>
																	<td>
																		<?php echo (!empty($operacoes['saida1']['horario'])) ? $operacoes['saida1']['horario'] : '---'; ?>
																		<?php echo (!empty($operacoes['saida1']) && $operacoes['saida1']['usuario'] != 'app' ) ? '*' : '' ?>
																	</td>
																	<td>
																		<?php echo (!empty($operacoes['entrada2']['horario'])) ? $operacoes['entrada2']['horario'] : '---'; ?>
																		<?php echo (!empty($operacoes['entrada2']) && $operacoes['entrada2']['usuario'] != 'app' ) ? '*' : '' ?>
																	</td>
																	<td>
																		<?php echo (!empty($operacoes['saida2']['horario'])) ? $operacoes['saida2']['horario'] : '---'; ?>
																		<?php echo (!empty($operacoes['saida2']) && $operacoes['saida2']['usuario'] != 'app' ) ? '*' : '' ?>
																	</td>
																	<td>
																		<?php echo (!empty($operacoes['entrada3']['horario'])) ? $operacoes['entrada3']['horario'] : '---'; ?>
																		<?php echo (!empty($operacoes['entrada3']) && $operacoes['entrada3']['usuario'] != 'app' ) ? '*' : '' ?>
																	</td>
																	<td>
																		<?php echo (!empty($operacoes['saida3']['horario'])) ? $operacoes['saida3']['horario'] : '---'; ?>
																		<?php echo (!empty($operacoes['saida3']) && $operacoes['saida3']['usuario'] != 'app' ) ? '*' : '' ?>
																	</td>
																	<td>
																		<?php echo (!empty($operacoes['entrada4']['horario'])) ? $operacoes['entrada4']['horario'] : '---'; ?>
																		<?php echo (!empty($operacoes['entrada4']) && $operacoes['entrada4']['usuario'] != 'app' ) ? '*' : '' ?>
																	</td>
																	<td>
																		<?php echo (!empty($operacoes['saida4']['horario'])) ? $operacoes['saida4']['horario'] : '---'; ?>
																		<?php echo (!empty($operacoes['saida4']) && $operacoes['saida4']['usuario'] != 'app' ) ? '*' : '' ?>
																	</td>
																	<td><?php echo $exrow['horas_trabalhadas']; ?></td>
																	<td><?php echo $exrow['horas_abono']; ?></td>
																	<td><?php echo $exrow['horas_dia']; ?></td>
																	<td><?php echo $exrow['saldo_dia']; ?></td>
																</tr>
													<?php 	endforeach;?>
													<?php 	unset($exrow); ?>
																<tfoot>
																<td colspan="10"><?php echo lang('PONTO_TABELA_HORAS'); ?></td>
																<td><?php echo segundos_para_hora($total_horas_trabalhadas); ?></td>
																<td><?php echo segundos_para_hora($total_horas_abono); ?></td>
																<td><?php echo segundos_para_hora($total_horas_dia); ?></td>
																<td><?php echo segundos_para_hora($saldo_mes); ?></td>
																</tfoot>
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