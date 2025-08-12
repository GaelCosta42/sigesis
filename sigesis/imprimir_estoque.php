<?php

/**
 * Imprimir Estoque
 *
 */
define("_VALID_PHP", true);

require_once("init.php");
if (!$usuario->is_Todos())
	redirect_to("login.php");

$id_produto = get('id_produto');
$nome = getValue("nome", "produto", "id = " . $id_produto);
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
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="SIGESIS - Sistemas - VOCÊ NO CONTROLE DA SUA EMPRESA, em qualquer lugar... a qualquer momento!" />
	<meta name="keywords" content="Vale Telecom, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis, telecom" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Vale Telecom" />

	<!-- Title -->
	<title><?php echo $core->empresa; ?></title>

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
	<link href="./assets/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN PAGE LEVEL STYLES -->
	<link rel="stylesheet" type="text/css" href="./assets/plugins/select2/select2.css" />
	<link href="assets/css/profile.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/tasks.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL SCRIPTS -->

	<!-- BEGIN THEME STYLES -->
	<link href="./assets/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
	<link href="./assets/css/plugins.css" rel="stylesheet" type="text/css">
	<link href="./assets/css/layout.css" rel="stylesheet" type="text/css">
	<link href="./assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color">
	<link href="./assets/css/custom.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="./assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
	<link rel="stylesheet" type="text/css" href="./assets/plugins/bootstrap-datepicker/css/datepicker3.css" />
	<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css" />
	<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css" />
	<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="./assets/plugins/typeahead/typeahead.css">
	<link rel="stylesheet" type="text/css" href="./assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css" />
	<link rel="stylesheet" type="text/css" href="./assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />

	<!-- BEGIN NOTIFICATION STYLES -->
	<link rel="stylesheet" type="text/css" href="./assets/css/notification.css">
	<!-- END NOTIFICATION -->
	<!-- END THEME STYLES -->

	<script src="./assets/plugins/jquery.min.js" type="text/javascript"></script>
	<script src="./assets/scripts/jquery.mask.js" type="text/javascript"></script>
	<script src="./assets/scripts/jquery.maskMoney.js" type="text/javascript"></script>
	<script src="./assets/scripts/shortcut.js" type="text/javascript"></script>

	<!-- dataTables -->
	<script src="./assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="./assets/plugins/datatables/dataTables.select.min.js"></script>
	<script src="./assets/plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="./assets/plugins/datatables/buttons.flash.min.js"></script>
	<script src="./assets/plugins/datatables/jszip.min.js"></script>
	<script src="./assets/plugins/datatables/pdfmake.min.js"></script>
	<script src="./assets/plugins/datatables/vfs_fonts.js"></script>
	<script src="./assets/plugins/datatables/buttons.html5.min.js"></script>
	<script src="./assets/plugins/datatables/buttons.print.min.js"></script>

	<!--[if lt IE 9]>
	<script src="./assets/plugins/respond.min.js"></script>
	<script src="./assets/plugins/excanvas.min.js"></script> 
	<![endif]-->

	<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="./assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script src="./assets/plugins/select2/select2.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
	<script src="./assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js" type="text/javascript"></script>
	<script src="./assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<script src="./assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<!-- END PAGE LEVEL PLUGINS -->
</head>
<script Language="JavaScript">
	function Imprimir() {
		window.print();
		window.close();
	}
</Script>
<style type="text/css">
	@media print {
		.noprint {
			display: none;
			margin: 30px;
		}

		.quebra_pagina {
			page-break-after: always;
		}
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
									<i class="fa fa-history font-<?php echo $core->primeira_cor; ?>"></i>
									<span class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('ESTOQUE_HISTORICO') . ": " . $nome; ?></span>
								</div>
								<div class='actions noprint'>
									<a class="btn btn-lg <?php echo $core->primeira_cor; ?> hidden-print margin-bottom-5" onclick="javascript:Imprimir();">
										<?php echo lang('IMPRIMIR'); ?>&nbsp;&nbsp;<i class="fa fa-print"></i>
									</a>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-bordered table-striped table-advance table-condensed dataTable" id="tabelaHistorico">
									<thead>
										<tr>
											<th><?php echo lang('MOVIMENTACAO'); ?></th>
											<th><?php echo lang('QUANTIDADE'); ?></th>
											<th><?php echo lang('OBSERVACAO'); ?></th>
											<th><?php echo lang('EMPRESA'); ?></th>
											<th><?php echo lang('DATA'); ?></th>
											<th><?php echo lang('USUARIO'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$retorno_row = $produto->getHistoricoEstoque($id_produto);
										$un = 1;
										if ($retorno_row) :
											foreach ($retorno_row as $exrow) :
										?>
												<tr>
													<td><?php echo motivo($exrow->motivo); ?></td>
													<td><?php echo decimalp($exrow->quantidade); ?></td>
													<td><?php echo $exrow->observacao; ?></td>
													<td><?php echo $exrow->empresa; ?></td>
													<td><?php echo exibedataHora($exrow->data); ?></td>
													<td><?php echo $exrow->usuario; ?></td>
												</tr>
											<?php endforeach; ?>
									</tbody>
									<tfoot>
										<?php
											$total_estoque = $produto->getEstoqueTotal($id_produto)
										?>
										<tr>
											<td colspan="1"><strong><?php echo lang('ESTOQUE_ATUAL'); ?></strong></td>
											<td><strong><?php echo decimalp($total_estoque); ?></strong></td>
											<td colspan="4"></td>
										</tr>
									<?php unset($exrow);
										endif; ?>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		console.log(document.querySelector("#tabelaHistorico"))
	</script>
	<div class="quebra_pagina"></div>
	<div class="noprint"></div>
</body>

</html>