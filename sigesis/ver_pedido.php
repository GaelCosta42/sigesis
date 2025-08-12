<?php
  /**
   * Ver pedido
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
	  
	
	$id = get('id'); 
	$row = Core::getRowById("pedido", $id)
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8"/>
<title><?php echo lang('PEDIDO_VISUALIZAR');?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="Sigesis N1" name="description"/>
<meta content="Vale Telecom" name="author"/>
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
									<i class="fa fa-list font-<?php echo $core->primeira_cor;?>"></i>								
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PEDIDO_VISUALIZAR');?></span>
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
														<th><?php echo lang('CODIGO_PREMIALY');?></th>
														<td><?php echo $row->cod_pedido;?></td>
													</tr>
													<tr>
														<th><?php echo lang('LOJA');?></th>
														<td><?php echo getValue("loja", "loja", "id_custo='".$row->id_custo."'");?></td>
													</tr>
													<tr>
														<th><?php echo lang('DATA_PEDIDO');?></th>
														<td><?php echo exibedata($row->data_pedido);?></td>
													</tr>
													<tr>
														<th><?php echo lang('PROTOCOLO');?></th>
														<td><?php echo $row->protocolo;?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</table>
							</div>
							<div class="portlet-body">
								<table width="100%">
									<tr>
										<td>
											<table class="table table-bordered table-striped table-condensed">
												<thead>
													<tr>
														<th><?php echo lang('CODIGO_PREMIALY');?></th>
														<th><?php echo lang('DEPARTAMENTO');?></th>
														<th><?php echo lang('PRODUTO');?></th>
														<th><?php echo lang('QUANTIDADE_PEDIDO');?></th>
													</tr>
												</thead>
												<tbody>
												<?php 	
													$retorno_row = $cotacao->getPedidosItens($row->cod_pedido);
													if($retorno_row):
														foreach ($retorno_row as $exrow):
															$estilo = "";
															if($exrow->inativo) {
																$estilo = 'class="danger"';
															}
												?>
													<tr <?php echo $estilo;?>>
														<td><?php echo $exrow->cod_pedido_item;?></td>
														<td><?php echo $exrow->departamento;?></td>
														<td><?php echo $exrow->produto;?></td>
														<td><?php echo decimal($exrow->quantidade_pedido);?></td>
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