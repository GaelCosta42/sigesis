<?php
  /**
   * Visualizar Receita
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
	  
	$id_receita = get('id_receita');
	$detalhes_row = $faturamento->getDetalhesReceitas($id_receita);

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
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('FINANCEIRO_RECEITAS').": ".$id_receita;?></span>
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
														<th colspan="2"><?php echo lang('FINANCEIRO_RECEITAS');?></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<th><?php echo lang('CODIGO');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->id;?></td>
													</tr>
													<tr>
														<th><?php echo lang('PAGO');?>:&nbsp;&nbsp;</th>
														<td><?php echo ($detalhes_row->pago==1) ? "<label class='label label-sm bg-green'>SIM</label>" : "<label class='label label-sm bg-red'>NAO</label>";?></td>
													</tr>
													<tr>
														<th><?php echo lang('EMPRESA');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->empresa;?></td>
													</tr>
													<tr>
														<th><?php echo lang('CLIENTE');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->cadastro;?></td>
													</tr>
													<tr>
														<th><?php echo lang('BANCO');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->banco;?></td>
													</tr>
													<tr>
														<th><?php echo lang('PLANO_CONTAS');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->conta;?></td>
													</tr>
													<tr>
														<th><?php echo lang('DESCRICAO');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->descricao;?></td>
													</tr>
													<tr>
														<th><?php echo lang('VALOR');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($detalhes_row->valor);?></td>
													</tr>
													<tr>
														<th><?php echo lang('VALOR_PAGO');?>:&nbsp;&nbsp;</th>
														<td><?php echo moeda($detalhes_row->valor_pago);?></td>
													</tr>
													<tr>
														<th><?php echo lang('DATA_VENCIMENTO');?>:&nbsp;&nbsp;</th>
														<td><?php echo exibedata($detalhes_row->data_pagamento);?></td>
													</tr>
													<tr>
														<th><?php echo lang('DATA_PAGAMENTO');?>:&nbsp;&nbsp;</th>
														<td><?php echo exibedata($detalhes_row->data_recebido);?></td>
													</tr>
													<tr>
														<th><?php echo lang('TIPO_PAGAMENTO');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->pagamento;?></td>
													</tr>
													<tr>
														<th><?php echo lang('DATA_FISCAL');?>:&nbsp;&nbsp;</th>
														<td><?php echo exibedata($detalhes_row->data_fiscal);?></td>
													</tr>
													<tr>
														<th><?php echo lang('DUPLICATA');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->duplicata;?></td>
													</tr>
													<?php if($detalhes_row->id_nota):?>
													<tr>
														<th><?php echo lang('NOTA_FISCAL');?>:&nbsp;&nbsp;</th>
														<td><?php echo getValue("numero_nota", "nota_fiscal", "id=".$detalhes_row->id_nota);?></td>
													</tr>
													<?php endif;?>
													<tr>
														<th>Data extrato:&nbsp;&nbsp;</th>
														<td><?php echo exibedataHora($detalhes_row->extrato_data);?></td>
													</tr>
													<tr>
														<th>Data remessa:&nbsp;&nbsp;</th>
														<td><?php echo exibedataHora($detalhes_row->data_remessa);?></td>
													</tr>
													<tr>
														<th>Data retorno:&nbsp;&nbsp;</th>
														<td><?php echo exibedataHora($detalhes_row->data_retorno);?></td>
													</tr>
													<tr>
														<th><?php echo lang('DATA');?>:&nbsp;&nbsp;</th>
														<td><?php echo exibedataHora($detalhes_row->data);?></td>
													</tr>
													<tr>
														<th><?php echo lang('USUARIO');?>:&nbsp;&nbsp;</th>
														<td><?php echo $detalhes_row->usuario;?></td>
													</tr>
												<tbody>
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