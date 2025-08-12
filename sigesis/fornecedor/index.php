<?php
  /**
   * Ver cotação
   *
   * @package Sistemas Divulgação Online
   * @author Geandro Bessa
   * @copyleft 2013
   * @version 2
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
	
	$co = get('co'); 
	$cod_fornecedor = getValue("cod_fornecedor", "fornecedor", "codigo='".$co."'");
	$nome_fornecedor = getValue("nome", "fornecedor", "codigo='".$co."'");
	$valor_frete = $cotacao->getValorFrete($id, $cod_fornecedor);
	
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
		<meta name="keywords" content="geandro bessa, divulgação online, divulgação, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis, telecom" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Geandro Bessa"/>
		
		<title><?php echo $core->empresa;?></title>
				
		<!-- Favicons -->
		<link rel="shortcut icon" href="../assets/img/favicon.png">
		<link rel="apple-touch-icon" href="../assets/img/favicon_60x60.png">
		<link rel="apple-touch-icon" sizes="76x76" href="../assets/img/favicon_76x76.png">
		<link rel="apple-touch-icon" sizes="120x120" href="../assets/img/favicon_120x120.png">
		<link rel="apple-touch-icon" sizes="152x152" href="../assets/img/favicon_152x152.png">
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
		<script src="../assets/scripts/jquery.mask.js" type="text/javascript"></script>
		<script src="../assets/scripts/jquery.maskMoney.js" type="text/javascript"></script>
		<script src="../assets/scripts/shortcut.js" type="text/javascript"></script>
		<!-- dataTables -->
		<script type="text/javascript" src="../assets/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="../assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
		<script type="text/javascript" src="../assets/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
		<script type="text/javascript" src="../assets/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
		<script type="text/javascript" src="../assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
		<script type="text/javascript" src="../assets/scripts/jquery.dataTables.grouping.js"></script>
		<script type="text/javascript" src="../assets/scripts/jquery.dataTables.columnFilter.min.js"></script>
		<!--[if lt IE 9]>
		<script src="../assets/plugins/respond.min.js"></script>
		<script src="../assets/plugins/excanvas.min.js"></script> 
		<![endif]-->
		<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
		<script src="../assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
		<script src="../assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="../assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
		<script src="../assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
		<script src="../assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
		<script src="../assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
		<script src="../assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
		<script src="../assets/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
		<script src="../assets/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>
		<!-- END CORE PLUGINS -->
		<!-- BEGIN PAGE LEVEL PLUGINS -->
		<script src="../assets/plugins/select2/select2.min.js" type="text/javascript"></script>
		<script src="../assets/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
		<script src="../assets/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>

		<!-- END PAGE LEVEL PLUGINS -->

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
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#imprimir_cotacao').click(function() {
			window.open('pdf_cotacao_todas.php?id=<?php echo $id;?>&cod_fornecedor=<?php echo $cod_fornecedor;?>','Imprimir Cotação','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
	});
	// ]]>
</script>
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
								<i class="fa fa-truck font-<?php echo $core->primeira_cor;?>"></i>		
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('FORNECEDOR').": ".$nome_fornecedor;?></span>
								<?php if($valor_frete > 0): ?>
								<br/>
								<br/>	
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('VALOR_FRETE').": ".percent($valor_frete);?></span>
								<?php endif; ?>
							</div>							
							<div class="actions btn-set">
								<a href="javascript:void(0);" id="imprimir_cotacao" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TODAS');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-advance">
								<thead>
									<tr>
										<th><?php echo lang('COD');?></th>
										<th><?php echo lang('LOJA');?></th>
										<th><?php echo lang('CIDADE');?></th>
										<th><?php echo lang('RESPONSAVEL');?></th>
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
										$retorno_row = $cotacao->getPedidosLojas($id, $cod_fornecedor);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											$vl_total += $exrow->valor;
											$site_sistema = $core->site_sistema;
											$lo = $exrow->cod_loja;
											$link = urlCurta("http://".$site_sistema."/cotacao/pdf_pedido.php?cc=".$cc."&lo=".$lo."&co=".$co);
								?>
									<tr>
										<td><?php echo $exrow->cod_loja;?></td>
										<td><?php echo $exrow->loja;?></td>
										<td><?php echo $exrow->cidade;?></td>
										<td><?php echo $exrow->responsavel;?></td>
										<td><?php echo $exrow->celular;?></td>
										<td><?php echo $exrow->email;?></td>							
										<td><?php echo $exrow->quant;?></td>										
										<td><?php echo moeda($exrow->valor);?></td>		
										<td>
											<a href="<?php echo $link;?>" class="btn btn-sm <?php echo $core->primeira_cor;?>" title="<?php echo lang('PEDIDO_IMPRIMIR').': '.$id;?>" target="_blank"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('PEDIDO_IMPRIMIR');?></a>
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
			<img src="https://n1.sige.pro/assets/img/sige.png" alt="">&bull; <?php echo date('Y');?> &bull; Desenvolvido por <a href="http://www.sigesis.com.br" target="_blank">SIGESIS - Sistemas.</a>
		</div>
	</div>
</div>
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div>
<!-- END FOOTER -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../assets/scripts/metronic.js" type="text/javascript"></script>
<script src="../assets/scripts/layout.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
});
</script>
</body>
<!-- END BODY -->
</html>