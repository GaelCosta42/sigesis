<?php
  /**
   * Ver cotação
   *
   */
  define("_VALID_PHP", true);

	require_once("../init.php");

	$cc = get('cc');
	if(!$cc)
		header("Location: ./_aviso");

	$id = getValue("id", "cotacao", "codigo='".$cc."'");
	$id_status = getValue("id_status", "cotacao", "codigo='".$cc."'");

	if($id_status < 5 or $id_status == 7)
		header("Location: ./_aviso");

	$lo = get('lo');
	$nome_loja = getValue("loja", "loja", "cod_loja='".$lo."'");

?>


<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-BR" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="pt-BR" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-BR">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo $core->empresa;?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="SIGESIS - Sistemas" name="description"/>
<meta content="Vale Telecom" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="../assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="../assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../assets/plugins/select2/select2.css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="../assets/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="../assets/css/plugins.css" rel="stylesheet" type="text/css">
<link href="../assets/css/layout.css" rel="stylesheet" type="text/css">
<link href="../assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color">
<link href="../assets/css/custom.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="../assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="../assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>
<link rel="stylesheet" type="text/css" href="../assets/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="../assets/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
<link rel="stylesheet" type="text/css" href="../assets/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
<link rel="stylesheet" type="text/css" href="../assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="../assets/plugins/typeahead/typeahead.css">

<!-- END THEME STYLES -->

<!-- Favicons -->
<link rel="shortcut icon" href="../assets/img/favicon.png">
<link rel="apple-touch-icon" href="../assets/img//favicon_60x60.png">
<link rel="apple-touch-icon" sizes="76x76" href="../assets/img//favicon_76x76.png">
<link rel="apple-touch-icon" sizes="120x120" href="../assets/img//favicon_120x120.png">
<link rel="apple-touch-icon" sizes="152x152" href="../assets/img//favicon_152x152.png">

<script src="../assets/plugins/jquery.min.js" type="text/javascript"></script>
<!--[if lt IE 9]>
<script src="../assets/plugins/respond.min.js"></script>
<script src="../assets/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="../assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="../assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="../assets/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
<script src="../assets/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->

<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
        $('a.confirmaentrega').click(function(){
			var id = $(this).attr('id');
			var cod_fornecedor = $(this).attr('cod_fornecedor');
			var col_loja = $(this).attr('cod_loja');
			jQuery.ajax({
				type: 'POST',
				url: '../controller.php',
				data: 'confirmaEntrega=1&id='+id+'&cod_fornecedor='+cod_fornecedor+'&cod_loja='+col_loja,
				success: function( data )
				{
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						stackup_spacing: 10
					});
					if(response[2] == "1")
						setTimeout(function(){
							window.location.href=response[3];
						}, 1000);
					location.reload();
				}
			});
			return false;

		});

        $('a.cancelaentrega').click(function(){
			var id = $(this).attr('id');
			var cod_fornecedor = $(this).attr('cod_fornecedor');
			var col_loja = $(this).attr('cod_loja');
			jQuery.ajax({
				type: 'POST',
				url: '../controller.php',
				data: 'cancelaEntrega=1&id='+id+'&cod_fornecedor='+cod_fornecedor+'&cod_loja='+col_loja,
				success: function( data )
				{
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						stackup_spacing: 10
					});
					if(response[2] == "1")
						setTimeout(function(){
							window.location.href=response[3];
						}, 1000);
					location.reload();
				}
			});
			return false;

		});
	});
	// ]]>
</script>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body>
<!-- BEGIN HEADER -->
<div class="page-header">
	<!-- BEGIN HEADER TOP -->
	<div class="page-header-top">
		<div class="container">
			<!-- BEGIN LOGO -->
			<div class="page-logo">
				<a href="index.php"><img src="../assets/img/logo.png" alt="logo" class="logo-default"></a>
			</div>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER -->
			<a href="javascript:;" class="menu-toggler"></a>
			<!-- END RESPONSIVE MENU TOGGLER -->
			<!-- BEGIN TOP NAVIGATION MENU -->
			<div class="top-menu">
				<ul class="nav navbar-nav pull-right">
					<li class="droddown dropdown-separator">
						<span class="separator"></span>
					</li>
					<li class="dropdown dropdown-dark">
						<a href="javascript:void(0);" class="dropdown-toggle">
						<span class="username username-hide-mobile"><?php echo $core->empresa;?></span>
						</a>
					</li>
				</ul>
			</div>
			<!-- END TOP NAVIGATION MENU -->
		</div>
	</div>
	<!-- END HEADER TOP -->
	<!-- BEGIN HEADER MENU -->
	<div class="page-header-menu bg-<?php echo $core->primeira_cor;?>">
		<div class="container"></div>
	</div>
	<!-- END HEADER MENU -->
</div>
<!-- END HEADER -->
<div class="page-container">
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light">
						<div class='portlet-title'>
							<div class="caption">
						<i class="fa fa-folder font-<?php echo $core->primeira_cor;?>"></i>
						<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('COTACAO').": ".$id;?></span>
						<br/>
						<br/>
						<i class="fa fa-th-large font-<?php echo $core->primeira_cor;?>"></i>
						<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('LOJA').": ".$nome_loja;?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-advance">
								<thead>
									<tr>
										<th><?php echo lang('COD');?></th>
										<th><?php echo lang('FORNECEDOR');?></th>
										<th><?php echo lang('CPF_CNPJ');?></th>
										<th><?php echo lang('CONTATO');?></th>
										<th><?php echo lang('CELULAR');?></th>
										<th><?php echo lang('EMAIL');?></th>
										<th><?php echo lang('QUANT');?></th>
										<th><?php echo lang('VALOR');?></th>
										<th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$vl_total = 0;
										$retorno_row = $cotacao->getPedidosFornecedores($id, $lo);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											$valida_entrega = $cotacao->validaEntrega($id, $exrow->cod_fornecedor, $lo);
											$estilo = '';
											if($valida_entrega)
												$estilo = 'class="success"';
											$vl_total += $exrow->valor;
											$site_sistema = $core->site_sistema;
											$co = $exrow->codigo;
											$link = urlCurta("http://".$site_sistema."/cotacao/pdf_pedido.php?cc=".$cc."&lo=".$lo."&co=".$co);
								?>
									<tr <?php echo $estilo;?>>
										<td><?php echo $exrow->cod_fornecedor;?></td>
										<td><a href="index.php?do=fornecedor&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->fornecedor;?></a></td>
										<td><?php echo formatar_cpf_cnpj($exrow->cpf_cnpj);?></td>
										<td><?php echo $exrow->contato;?></td>
										<td><?php echo $exrow->celular;?></td>
										<td><?php echo $exrow->email;?></td>
										<td><?php echo $exrow->quant;?></td>
										<td><?php echo moeda($exrow->valor);?></td>
										<td>
											<a href="<?php echo $link;?>" class="btn btn-sm <?php echo $core->primeira_cor;?>" title="<?php echo lang('PEDIDO_IMPRIMIR').': '.$id;?>" target="_blank"><i class="fa fa-print"></i></a>
											<?php if($id_status == 5): ?>
												<?php if(!$valida_entrega): ?>
													<a href="javascript:void(0);" class="btn btn-sm green confirmaentrega" id="<?php echo $id;?>" cod_fornecedor="<?php echo $exrow->cod_fornecedor;?>" cod_loja="<?php echo $lo;?>" title="<?php echo lang('PEDIDO_CONFIRMAR').": ".$exrow->fornecedor;?>"><i class="fa fa-check"></i></a>
													<a href="javascript:void(0);" class="btn btn-sm red cancelaentrega" id="<?php echo $id;?>" cod_fornecedor="<?php echo $exrow->cod_fornecedor;?>" cod_loja="<?php echo $lo;?>" title="<?php echo lang('PEDIDO_CONFIRMAR').": ".$exrow->fornecedor;?>"><i class="fa fa-ban"></i></a>
												<?php endif; ?>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach;?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="7"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
										<td><strong><?php echo moeda($vl_total);?></strong></td>
										<td></td>
									</tr>
								</tfoot>
								<?php unset($exrow);
									  endif;?>
							</table>
						</div>
						<h4 class="form-section font-<?php echo $core->primeira_cor;?>"><i class="fa fa-barcode">&nbsp;&nbsp;</i><?php echo lang('ITENS_NAO_COTADOS');?></h4>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable dataTable-noheader dataTable-nofooter" id="table_tabela">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('PEDIDO');?></th>
										<th><?php echo lang('PRODUTO');?></th>
										<th><?php echo lang('CODIGO_DE_BARRAS');?></th>
										<th><?php echo lang('QUANTIDADE_PEDIDO');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$vl_total = 0;
										$retorno_row = $cotacao->getItensNaoCotados($id, $lo);
										if($retorno_row):
										foreach ($retorno_row as $exrow):

								?>
									<tr>
										<td><?php echo $exrow->cod_produto;?></td>
										<td><?php echo $exrow->cod_pedido;?></td>
										<td><?php echo $exrow->produto;?></td>
										<td><?php echo $exrow->codigo_barras;?></td>
										<td><?php echo decimal($exrow->quantidade_pedido)." ".$exrow->unidade_compra;?></td>
									</tr>
								<?php endforeach;?>
								</tbody>
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
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="container">
		<div class="pull-right">
			 <img src="../assets/img/divulgacao.png" alt=""> Copyright &copy;<?php echo date('Y');?> &bull; Desenvolvido por <a href="http://www.divulgacaoonline.com.br" target="_blank">Divulgação Online.</a>
		</div>
	</div>
</div>
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->
</body>
<!-- END BODY -->
</html>