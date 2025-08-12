<?php
/**
 * Header
 *
 */

if (!defined("_VALID_PHP"))
	die('O acesso direto a está página não é permitido');

$titulo_header = $core->empresa;
$nome_pagina = get('do');
$id_pagina = get('id');
if ($nome_pagina == 'cadastro' and $id_pagina) {
	$titulo_header = getValue('nome', 'cadastro', 'id=' . $id_pagina);
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
	<title><?php echo $titulo_header; ?></title>

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
	<link href="assets/css/profile.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/tasks.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL SCRIPTS -->

	<!-- BEGIN THEME STYLES -->
	<link href="./assets/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
	<link href="./assets/css/plugins.css" rel="stylesheet" type="text/css">
	<link href="./assets/css/layout.css" rel="stylesheet" type="text/css">
	<link href="./assets/css/themes/default.css" rel="stylesheet" type="text/css">
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
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->

<body>
	<div class="modal fade" id="cadRenovacaoCertificado" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="exampleModalLabel"><?php echo lang('ATUALIZAR_CARDASTRO') ?></h4>
				</div>
				<form autocomplete="off" action="" method="post" name="dados_cliente_certificado_form"
					id="dados_cliente_certificado_form">
					<div class="modal-body">
						<?php if (!$empresa->verificaRegistroDuplicadoCertificadosVencidos()): ?>
							<div class="alert alert-warning" role="alert">
								<?php
								echo lang('INFO_VENCIMENTO_CERTIFICADO');
								?>
							</div>
						<?php else: ?>
							<div class="alert alert-info" role="alert">
								<?php
								echo lang('INFO_VENCIMENTO_CERTIFICADO_DUPLICADO');
								?>
							</div>
						<?php endif; ?>
						<div class="pull-right" style="color: #f00; font-weight: bold;">
							<?php echo lang('OBS_*') ?>
						</div>
						<div class="form-group">
							<label for=""><?php echo lang('EMPRESA') ?>
								<span style="color: #f00;"><?php echo lang('*') ?></span>
							</label>
							<input type="text" class="form-control caps" name="empresa"
								value="<?php echo $core->nome_empresa; ?>" />
						</div>
						<div class="form-group">
							<label for=""><?php echo lang('RAZAO_SOCIAL_MAIUSCULO') ?>
								<span style="color: #f00;"><?php echo lang('*') ?></span>
							</label>
							<input type="text" class="form-control caps" name="razao_social"
								value="<?php echo $core->razao_social; ?>" />
						</div>
						<div class="form-group">
							<label for=""><?php echo lang('TELEFONE_CELULAR') ?>
								<span style="color: #f00;"><?php echo lang('*') ?></span>
							</label>
							<input type="text" class="form-control telefone" name="telefone_celular"
								value="<?php echo $core->telefone; ?>" />
						</div>
						<div class="form-group">
							<label for=""><?php echo lang('EMAIL_MAIUSCULO') ?>
								<span style="color: #f00;"><?php echo lang('*') ?></span>
							</label>
							<input type="text" class="form-control caps" name="email"
								value="<?php echo $core->email; ?>" />
						</div>
						<div class="form-group">
							<label for=""><?php echo lang('CPF_CNPJ') . " (da empresa)" ?>
								<span style="color: #f00;"><?php echo lang('*') ?></span>
							</label>
							<input type="text" class="form-control cpf_cnpj" name="cpf_cnpj"
								value="<?php echo $core->cnpj_cliente; ?>" />
						</div>
						<div class="form-group">
							<label for=""><?php echo lang('CONTATO_MAIUSCULO') ?>
								<span style="color: #f00;"><?php echo lang('*') ?></span>
							</label>
							<input type="text" class="form-control caps" name="contato"
								value="<?php echo $core->responsavel; ?>" />
						</div>
						<span class="help-block bold">
							<?php echo lang('MSG_INFO_CAD_RENOVACAO_CERTIFICADO') ?>
						</span>
						<br>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary pull-left"
							data-dismiss="modal"><?php echo lang('SAIR') ?></button>
						<button <?php echo ($empresa->verificaRegistroDuplicadoCertificadosVencidos()) ? 'disabled' : '' ?> type="button" class="btn btn-primary btn-submit"><?php echo lang('SALVAR') ?></button>
					</div>
					<?php echo $core->doForm("processarInfoAtualizarCertificado", "dados_cliente_certificado_form"); ?>
				</form>
			</div>
		</div>
	</div>
	<!-- BEGIN HEADER -->
	<div class="page-header" <?php echo (Filter::$acao == "novavenda") ? ' style="height: 145px;" ' : '' ?>>
		<!-- NAVBAR USADO EM VENDAS > NOVAVENDA -->
		<?php if (Filter::$acao == "novavenda"): ?>
			<div id="page-header-menu-header2" class="page-header-menu bg-<?php echo $core->primeira_cor; ?>">
				<div class="container">
					<!-- BEGIN MEGA MENU -->
					<!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
					<!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
					<div class="hor-menu ">
						<ul class="nav navbar-nav">
							<?php if ($core->tipo_sistema == 5): ?>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('ATENDIMENTO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<?php if ($usuario->is_Gerencia()): ?>
											<li class="dropdown-submenu">
												<a href="javascript:;">
													<i class="fa fa-cogs"></i>
													<?php echo lang('ORCAMENTO_TITULO'); ?>
												</a>
												<ul class="dropdown-menu pull-left">
													<li>
														<a href="index.php?do=ordem_servico&acao=adicionar" class="iconify">
															<i class="fa fa-plus"></i>
															<?php echo lang('ORCAMENTO_ADICIONAR'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=ordem_servico&acao=orcamentos" class="iconify">
															<i class="fa fa-list"></i>
															<?php echo lang('ORCAMENTO_LISTAR'); ?>
														</a>
													</li>
												</ul>
											</li>
										<?php else: ?>
											<li>
												<a href="index.php?do=ordem_servico&acao=orcamentos" class="iconify">
													<i class="fa fa-cogs"></i>
													<?php echo lang('ORCAMENTO_TITULO'); ?>
												</a>
											</li>
										<?php endif; ?>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-wrench"></i>
												<?php echo lang('ORDEM_SERVICO_TITULO'); ?>
											</a>
											<ul class="dropdown-menu pull-left">
												<li>
													<a href="index.php?do=ordem_servico&acao=listar" class="iconify">
														<i class="fa fa-list"></i>
														<?php echo lang('ORDEM_SERVICO_LISTAR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=ordem_servico&acao=listar_os_finalizadas"
														class="iconify">
														<i class="fa fa-list"></i>
														<?php echo lang('ORDEM_SERVICO_LISTAR_FINALIZADAS'); ?>
													</a>
												</li>
											</ul>
										</li>
										<li>
											<a href="index.php?do=equipamento&acao=listar">
												<i class="fa fa-building"></i>
												<?php echo lang('EQUIPAMENTO_TITULO'); ?>
											</a>
										</li>
									</ul>
								</li>
							<?php endif; ?>
							<?php if ($usuario->is_Administrativo()): ?>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('VENDAS_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li>
											<a href="index.php?do=vendas&acao=novavenda" class="iconify">
												<i class="fa fa-barcode"></i>
												<?php echo lang('VENDAS_NOVA'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=vendas_do_dia" class="iconify">
												<i class="fa fa-calendar-o"></i>
												<?php echo lang('VENDAS_DO_DIA'); ?>
											</a>
										</li>
										<?php if ($core->tipo_sistema == 4): ?>
											<li>
												<a href="index.php?do=vendas&acao=vendaspedidosentrega" class="iconify">
													<i class="fa fa-exclamation-triangle"></i>
													<?php echo lang('VENDAS_ABERTO'); ?>
												</a>
											</li>
										<?php else: ?>
											<?php if ($usuario->is_VendaAberto() && $core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
												<li>
													<a href="index.php?do=vendas_em_aberto" class="iconify">
														<i class="fa fa-exclamation-triangle"></i>
														<?php echo lang('VENDAS_ABERTO'); ?>
													</a>
												</li>
											<?php else: ?>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-exclamation-triangle"></i>
														<?php echo lang('VENDAS_ABERTO'); ?>
													</span>
												</li>
											<?php endif; ?>
										<?php endif; ?>
										<?php if ($usuario->is_Orcamento() && $core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
											<li>
												<a href="index.php?do=vendas&acao=vendasorcamento" class="iconify">
													<i class="fa fa-list"></i>
													<?php echo lang('ORCAMENTOS'); ?>
												</a>
											</li>
										<?php else: ?>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-list"></i>
													<?php echo lang('ORCAMENTOS'); ?>
												</span>
											</li>
										<?php endif; ?>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-inbox"></i>
												<?php echo lang('MENU_CAIXA'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=caixa&acao=adicionar" class="iconify">
														<i class="fa fa-inbox"></i>
														<?php echo lang('CAIXA_ABRIR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=listar" class="iconify">
														<i class="fa fa-calendar-o"></i>
														<?php echo lang('CAIXA_DIA'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=aberto" class="iconify">
														<i class="fa fa-exclamation-triangle"></i>
														<?php echo lang('CAIXA_EMABERTO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=listarretiradas" class="iconify">
														<i class="fa fa-minus-square"></i>
														<?php echo lang('CAIXA_LISTARRETIRADAS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=produto&acao=trocarproduto" class="iconify">
														<i class="fa fa-exchange"></i>
														<?php echo lang('PRODUTO_TROCA_FISCAL'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=produto&acao=trocarprodutoavulso" class="iconify">
														<i class="fa fa-exchange"></i>
														<?php echo lang('PRODUTO_TROCA_AVULSO'); ?>
													</a>
												</li>
											</ul>
										</li>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-barcode"></i>
												<?php echo lang('CONTROLE_VENDAS'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=vendas_por_periodo" class="iconify">
														<i class="fa fa-shopping-cart"></i>
														<?php echo lang('VENDAS_PERIODO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=vendas&acao=vendasconsolidado" class="iconify">
														<i class="fa fa-list-ol"></i>
														<?php echo lang('VENDAS_CONSOLIDADAS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=vendas&acao=vendasconsolidadoprodutos"
														class="iconify">
														<i class="fa fa-list-ol"></i>
														<?php echo lang('VENDAS_CONSOLIDADAS_PRODUTOS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=vendas&acao=vendasclienteperiodo" class="iconify">
														<i class="fa fa-users"></i>
														<?php echo lang('VENDAS_CLIENTE_PERIODO'); ?>
													</a>
												</li>
												<?php if ($core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
													<li>
														<a href="index.php?do=vendas&acao=vendasprodutofornecedor" class="iconify">
															<i class="fa fa-cubes"></i>
															<?php echo lang('VENDAS_PRODUTO_FABRICANTE'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=romaneiovenda" class="iconify">
															<i class="fa fa-file-text"></i>
															<?php echo lang('ROMANEIO_CARGA'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=vendas&acao=impressaoproducao" class="iconify">
															<i class="fa fa-cogs"></i>
															<?php echo lang('IMPRIMIR_VENDA_OS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=vendas&acao=vendascrediario" class="iconify">
															<i class="fa fa-money"></i>
															<?php echo lang('CADASTRO_CREDIARIO_FICHA'); ?>
														</a>
													</li>
												<?php else: ?>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-file-tex"></i>
															<?php echo lang('VENDAS_PRODUTO_FORNECEDOR'); ?>
														</span>
													</li>
													<li>
														<a href="iconify disabled menu-desabilitado" class="iconify">
															<i class="fa fa-file-text"></i>
															<?php echo lang('ROMANEIO_CARGA'); ?>
														</a>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-money"></i>
															<?php echo lang('CADASTRO_CREDIARIO_FICHA'); ?>
														</span>
													</li>
												<?php endif; ?>
											</ul>
										</li>
									</ul>
								</li>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('CADASTRO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li>
											<a href="index.php?do=cadastro&acao=adicionar" class="iconify">
												<i class="fa fa-plus-square"></i>
												<?php echo lang('CADASTRO_ADICIONAR'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=clientes" class="iconify">
												<i class="fa fa-list"></i>
												<?php echo lang('CADASTRO_CLIENTES'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=fornecedores" class="iconify">
												<i class="fa fa-list"></i>
												<?php echo lang('CADASTRO_FORNECEDORES'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=buscar" class="iconify">
												<i class="fa fa-search"></i>
												<?php echo lang('CADASTRO_BUSCAR'); ?>
											</a>
										</li>
									</ul>
								</li>
								<li class="menu-dropdown mega-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;" class="dropdown-toggle">
										<?php echo lang('PRODUTO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu" style="min-width: 480px">
										<li>
											<div class="mega-menu-content">
												<div class="row">
													<div class="col-md-6">
														<ul class="mega-menu-submenu">
															<li>
																<a href="index.php?do=produto&acao=adicionar" class="iconify">
																	<i class="fa fa-plus-square"></i>
																	<?php echo lang('PRODUTO_ADICIONAR'); ?>
																</a>
															</li>
															<li>
																<a href="index.php?do=produto&acao=grade" class="iconify">
																	<i class="fa fa-th"></i>
																	<?php echo lang('GRADE_VENDAS'); ?>
																</a>
															</li>
															<li>
																<a href="index.php?do=produto&acao=listar" class="iconify">
																	<i class="fa fa-list"></i>
																	<?php echo lang('PRODUTO_LISTAR'); ?>
																</a>
															</li>
															<li>
																<a href="index.php?do=estoque&acao=listar" class="iconify">
																	<i class="fa fa-tasks"></i>
																	<?php echo lang('ESTOQUE_TITULO'); ?>
																</a>
															</li>
															<li>
																<a href="index.php?do=produto&acao=pendentes" class="iconify">
																	<i class="fa fa-pause"></i>
																	<?php echo lang('PRODUTO_PENDENTES'); ?>
																</a>
															</li>
															<li>
																<a href="index.php?do=produto&acao=atualizacodbarras"
																	class="iconify">
																	<i class="fa fa-barcode"></i>
																	<?php echo lang('PRODUTO_ATUALIZAR_CODBARRAS'); ?>
																</a>
															</li>
														</ul>
													</div>
													<div class="col-md-6">
														<ul class="mega-menu-submenu">
															<li>
																<a href="index.php?do=produto&acao=buscar" class="iconify">
																	<i class="fa fa-search"></i>
																	<?php echo lang('PRODUTO_BUSCAR'); ?>
																</a>
															</li>
															<li class="dropdown-submenu">
																<a href="index.php?do=tabela&acao=listar">
																	<i class="fa fa-table"></i>
																	<?php echo lang('TABELA_PRECO_TITULO'); ?>
																</a>
																<ul class="dropdown-menu">
																	<li>
																		<a href="index.php?do=tabela&acao=adicionar"
																			class="iconify">
																			<i class="fa fa-plus-square"></i>
																			<?php echo lang('TABELA_PRECO_ADICIONAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=tabela&acao=listar"
																			class="iconify">
																			<i class="fa fa-list"></i>
																			<?php echo lang('TABELA_PRECO_LISTAR'); ?>
																		</a>
																	</li>
																</ul>
															</li>
															<li class="dropdown-submenu">
																<a href="index.php?do=tabela&acao=listar">
																	<i class="fa fa-puzzle-piece"></i>
																	<?php echo lang('PRODUTO_ATRIBUTO'); ?>
																</a>
																<ul class="dropdown-menu">
																	<li>
																		<a href="index.php?do=atributo&acao=adicionar"
																			class="iconify">
																			<i class="fa fa-plus-square"></i>
																			<?php echo lang('PRODUTO_ATRIBUTO_ADICIONAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=atributo&acao=listar"
																			class="iconify">
																			<i class="fa fa-list"></i>
																			<?php echo lang('PRODUTO_ATRIBUTO_LISTAR'); ?>
																		</a>
																	</li>
																</ul>
															</li>
															<li class="dropdown-submenu">
																<a href="index.php?do=grupo&acao=listar">
																	<i class="fa fa-list-alt"></i>
																	<?php echo lang('GRUPO_TITULO'); ?>
																</a>
																<ul class="dropdown-menu">
																	<li>
																		<a href="index.php?do=grupo&acao=adicionar"
																			class="iconify">
																			<i class="fa fa-plus-square"></i>
																			<?php echo lang('GRUPO_ADICIONAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=grupo&acao=listar"
																			class="iconify">
																			<i class="fa fa-list"></i>
																			<?php echo lang('GRUPO_LISTAR'); ?>
																		</a>
																	</li>
																</ul>
															</li>
															<li class="dropdown-submenu">
																<a href="index.php?do=categoria&acao=listar">
																	<i class="fa fa-table"></i>
																	<?php echo lang('CATEGORIA_TITULO'); ?>
																</a>
																<ul class="dropdown-menu">
																	<li>
																		<a href="index.php?do=categoria&acao=adicionar"
																			class="iconify">
																			<i class="fa fa-plus-square"></i>
																			<?php echo lang('CATEGORIA_ADICIONAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=categoria&acao=listar"
																			class="iconify">
																			<i class="fa fa-list"></i>
																			<?php echo lang('CATEGORIA_LISTAR'); ?>
																		</a>
																	</li>
																</ul>
															</li>
															<li class="dropdown-submenu">
																<a href="index.php?do=fabricante&acao=listar">
																	<i class="fa fa-database"></i>
																	<?php echo lang('FABRICANTE_TITULO'); ?>
																</a>
																<ul class="dropdown-menu">
																	<li>
																		<a href="index.php?do=fabricante&acao=adicionar"
																			class="iconify">
																			<i class="fa fa-plus-square"></i>
																			<?php echo lang('FABRICANTE_ADICIONAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=fabricante&acao=listar"
																			class="iconify">
																			<i class="fa fa-list"></i>
																			<?php echo lang('FABRICANTE_LISTAR'); ?>
																		</a>
																	</li>
																</ul>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</li>
									</ul>
								</li>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('FINANCEIRO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-minus-square"></i>
												<?php echo lang('DESPESAS'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=despesa&acao=adicionar" class="iconify">
														<i class="fa fa-minus"></i>
														<?php echo lang('FINANCEIRO_ADICIONAR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=despesa&acao=despesaspagas" class="iconify">
														<i class="fa fa-check"></i>
														<?php echo lang('FINANCEIRO_DESPESASPAGAS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=despesa&acao=despesas" class="iconify">
														<i class="fa fa-exclamation"></i>
														<?php echo lang('FINANCEIRO_DESPESASAPAGAR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=boleto_sigesis&acao=listar" class="iconify">
														<i class="fa fa-file-text-o"></i>
														Boletos Sigesis
													</a>
												</li>
												<li>
													<a href="index.php?do=despesa&acao=agrupar" class="iconify">
														<i class="fa fa-share-alt"></i>
														<?php echo lang('FINANCEIRO_DESPESASAGRUPAR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=despesa&acao=pagarcartoes" class="iconify">
														<i class="fa fa-credit-card"></i>
														<?php echo lang('FINANCEIRO_DESPESASCARTOES'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=extrato&acao=conciliardespesas" class="iconify">
														<i class="fa fa-link"></i>
														<?php echo lang('EXTRATO_CONCILIARDESPESAS'); ?>
													</a>
												</li>
											</ul>
										</li>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-plus-square"></i>
												<?php echo lang('FINANCEIRO_RECEITAS'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=faturamento&acao=receitarapida" class="iconify">
														<i class="fa fa-usd"></i>
														<?php echo lang('RECEITA_RAPIDA'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=faturamento&acao=adicionar" class="iconify">
														<i class="fa fa-plus"></i>
														<?php echo lang('FINANCEIRO_ADICIONAR_RECEITA'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=faturamento&acao=recebidas" class="iconify">
														<i class="fa fa-check"></i>
														<?php echo lang('CONTAS_RECEBIDAS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=faturamento&acao=receber" class="iconify">
														<i class="fa fa-exclamation"></i>
														<?php echo lang('CONTAS_A_RECEBER'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=faturamento&acao=receber_crediario" class="iconify">
														<i class="fa fa-money"></i>
														<?php echo lang('PROMISSORIAS_ARECEBER'); ?>
													</a>
												</li>
											</ul>
										</li>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-bank"></i>
												<?php echo lang('BANCO_TITULO'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=banco&acao=listar" class="iconify">
														<i class="fa fa-bank"></i>
														<?php echo lang('BANCO_TITULO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=extrato&acao=extrato" class="iconify">
														<i class="fa fa-sort-numeric-asc"></i>
														<?php echo lang('EXTRATO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=faturamento&acao=cheques" class="iconify">
														<i class="fa fa-stack-overflow"></i>
														<?php echo lang('FINANCEIRO_CHEQUES'); ?> </a>
												</li>
												<li>
													<a href="index.php?do=extrato&acao=arquivoboletos" class="iconify">
														<i class="fa fa-bold"></i>
														<?php echo lang('EXTRATO_ARQUIVOBOLETOS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=extrato&acao=arquivobanco" class="iconify">
														<i class="fa fa-file-excel-o"></i>
														<?php echo lang('EXTRATO_ARQUIVOBANCOS'); ?>
													</a>
												</li>
											</ul>
										</li>
										<?php if ($core->tipo_sistema == 2 || $core->tipo_sistema == 3): ?>
											<li class="dropdown-submenu">
												<a href="javascript:;" style="color: #BDBDBD">
													<i class="fa fa-file-text-o" style="color: #BDBDBD"></i>
													<?php echo lang('NOTA_FISCAL'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-list"></i>
															<?php echo lang('NOTA_LISTAR'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-plus-square"></i>
															<?php echo lang('NOTA_ADICIONAR'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-sign-in"></i>
															<?php echo lang('NOTA_FISCAL_COMPRAS'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-sign-out"></i>
															<?php echo lang('NOTA_FISCAL_VENDAS'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-repeat"></i>
															<?php echo lang('NOTA_INVENTARIO'); ?>
														</span>
													</li>
												</ul>
											</li>
										<?php else: ?>
											<li class="dropdown-submenu">
												<a href="javascript:;">
													<i class="fa fa-file-text-o"></i>
													<?php echo lang('NOTA_FISCAL'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=notafiscal&acao=notafiscal" class="iconify">
															<i class="fa fa-list"></i>
															<?php echo lang('NOTA_LISTAR'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=notafiscal&acao=adicionar" class="iconify">
															<i class="fa fa-plus-square"></i>
															<?php echo lang('NOTA_ADICIONAR'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=xml&acao=entradas" class="iconify">
															<i class="fa fa-sign-in"></i>
															<?php echo lang('NOTA_FISCAL_COMPRAS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=xml&acao=saidas" class="iconify">
															<i class="fa fa-sign-out"></i>
															<?php echo lang('NOTA_FISCAL_VENDAS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=notafiscal&acao=inventario" class="iconify">
															<i class="fa fa-repeat"></i>
															<?php echo lang('NOTA_INVENTARIO'); ?>
														</a>
													</li>
												</ul>
											</li>
										<?php endif; ?>
										<?php if ($usuario->is_Gerencia()): ?>
											<li class="dropdown-submenu">
												<a href="javascript:;">
													<i class="fa fa-cogs"></i>
													<?php echo lang('CONFIGURACOES'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=centrocusto&acao=listar" class="iconify">
															<i class="fa fa-share-alt"></i>
															<?php echo lang('CENTRO_CUSTO_TITULO'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=faturamento&acao=plano_contas" class="iconify">
															<i class="fa fa-bars"></i>
															<?php echo lang('FINANCEIRO_PLANO_CONTAS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=tipopagamento&acao=listar" class="iconify">
															<i class="fa fa-usd"></i>
															<?php echo lang('TIPO_PAGAMENTO'); ?>
														</a>
													</li>
												</ul>
											</li>
											<?php if ($core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
												<li class="dropdown-submenu">
													<a href="javascript:;">
														<i class="fa fa-file-text"></i>
														<?php echo lang('DRE'); ?>
													</a>
													<ul class="dropdown-menu">
														<li>
															<a href="index.php?do=extrato&acao=dre" class="iconify">
																<i class="fa fa-file-text"></i>
																<?php echo lang('DRE_FINANCEIRO'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=faturamento&acao=metasdre" class="iconify">
																<i class="fa fa-crosshairs"></i>
																<?php echo lang('METAS_DRE'); ?>
															</a>
														</li>
													</ul>
												</li>
											<?php else: ?>
												<li class="dropdown-submenu">
													<a href="javascript:;" style="color: #BDBDBD">
														<i class="fa fa-file-text" style="color: #BDBDBD"></i>
														<?php echo lang('DRE'); ?>
													</a>
													<ul class="dropdown-menu">
														<li>
															<span class="iconify disabled menu-desabilitado">
																<i class="fa fa-file-text"></i>
																<?php echo lang('DRE_FINANCEIRO'); ?>
															</span>
														</li>
														<li>
															<span class="iconify disabled menu-desabilitado">
																<i class="fa fa-crosshairs"></i>
																<?php echo lang('METAS_DRE'); ?>
															</span>
														</li>
													</ul>
												</li>
											<?php endif; ?>
										<?php endif; ?>
									</ul>
								</li>
								<?php if ($core->tipo_sistema == 2): ?>
									<li class="menu-dropdown classic-menu-dropdown ">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;">
											<?php echo lang('CONTABIL_TITULO'); ?>
											<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-file-code-o"></i>
													<?php echo lang('NOTA_LISTAR_CHAVEACESSO'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-list-ol"></i>
													<?php echo lang('VENDAS_FISCAIS'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-times"></i>
													Notas Negadas
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-ban"></i>
													<?php echo lang('NOTA_INUTILIZADAS'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-ban"></i>
													<?php echo lang('NOTA_INUTILIZADAS_NFE'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-file-text-o"></i>
													<?php echo lang('SINTEGRA_ARQUIVO'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-file-text"></i>
													<?php echo lang('SINTEGRA_ARQUIVO_INVENTARIO_FISCAL'); ?>
												</span>
											</li>
											<li>
												<a href="index.php?do=cfop&acao=listar" class="iconify">
													<i class="fa fa-puzzle-piece"></i>
													Conversão de CFOP
													</span>
												</a>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-file-text"></i>
													<?php echo lang('RECEBIMENTOS_TITULO'); ?>
												</span>
											</li>
										</ul>
									</li>
								<?php else: ?>
									<li class="menu-dropdown classic-menu-dropdown ">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;">
											<?php echo lang('CONTABIL_TITULO'); ?>
											<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<a href="index.php?do=notafiscal&acao=chaveacesso" class="iconify">
													<i class="fa fa-file-code-o"></i>
													<?php echo lang('NOTA_LISTAR_CHAVEACESSO'); ?>
												</a>
											</li>
											<li>
												<a href="index.php?do=vendas&acao=vendasfiscal" class="iconify">
													<i class="fa fa-list-ol"></i>
													<?php echo lang('VENDAS_FISCAIS'); ?>
												</a>
											</li>
											<li>
												<a href="index.php?do=notas_negadas&acao=listar" class="iconify">
													<i class="fa fa-times"></i>
													Notas Negadas
												</a>
											</li>
											<li>
												<a href="index.php?do=notafiscal&acao=inutilizar" class="iconify">
													<i class="fa fa-ban"></i>
													<?php echo lang('NOTA_INUTILIZADAS'); ?>
												</a>
											</li>
											<?php if ($core->tipo_sistema == 3): ?>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-ban"></i>
														<?php echo lang('NOTA_INUTILIZADAS_NFE'); ?>
													</span>
												</li>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-file-text-o"></i>
														<?php echo lang('SINTEGRA_ARQUIVO'); ?>
													</span>
												</li>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-file-text"></i>
														<?php echo lang('SINTEGRA_ARQUIVO_INVENTARIO_FISCAL'); ?>
													</span>
												</li>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-puzzle-piece"></i>
														Conversão de CFOP
													</span>
												</li>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-file-text"></i>
														<?php echo lang('RECEBIMENTOS_TITULO'); ?>
													</span>
												</li>
											<?php else: ?>
												<li>
													<a href="index.php?do=notafiscal&acao=inutilizarNFe" class="iconify">
														<i class="fa fa-ban"></i>
														<?php echo lang('NOTA_INUTILIZADAS_NFE'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=notafiscal&acao=sintegra" class="iconify">
														<i class="fa fa-file-text-o"></i>
														<?php echo lang('SINTEGRA_ARQUIVO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=notafiscal&acao=sintegrainventario" class="iconify">
														<i class="fa fa-file-text"></i>
														<?php echo lang('SINTEGRA_ARQUIVO_INVENTARIO_FISCAL'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=cfop&acao=listar" class="iconify">
														<i class="fa fa-puzzle-piece"></i>
														Conversão de CFOP
														</span>
													</a>
												</li>
												<li>
													<a href="index.php?do=notafiscal&acao=das" class="iconify">
														<i class="fa fa-file-text"></i>
														<?php echo lang('RECEBIMENTOS_TITULO'); ?>
													</a>
												</li>
											<?php endif; ?>
										</ul>
									</li>
								<?php endif; ?>
								<?php if ($usuario->is_Master() && $core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
									<li class="menu-dropdown classic-menu-dropdown ">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;">
											<?php echo lang('GESTAO'); ?>
											<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<a href="index.php?do=gestao&acao=dremensal">
													<i class="fa fa-bar-chart-o"></i>
													<?php echo lang('GESTAO_DRE_MENSAL'); ?>
												</a>
											</li>
											<li class="dropdown-submenu">
												<a href="javascript:;">
													<i class="fa fa-usd"></i>
													<?php echo lang('FINANCEIRO'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=extrato&acao=financeiro" class="iconify">
															<i class="fa fa-bar-chart-o"></i>
															<?php echo lang('GESTAO_ANALISE'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=extrato&acao=financeiromensal" class="iconify">
															<i class="fa fa-bar-chart-o"></i>
															<?php echo lang('PAINEL_ANALISEMENSAL'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=extrato&acao=analiseestoque" class="iconify">
															<i class="fa fa-th font-"></i>
															<?php echo lang('GESTAO_ESTOQUE'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=extrato&acao=despesasano" class="iconify">
															<i class="fa fa-sign-out"></i>
															<?php echo lang('GESTAO_DESPESA'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=extrato&acao=receitasano" class="iconify">
															<i class="fa fa-money"></i>
															<?php echo lang('GESTAO_RECEITA'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=extrato&acao=faturamentoano" class="iconify">
															<i class="fa fa-usd"></i>
															<?php echo lang('GESTAO_FATURAMENTO'); ?>
														</a>
													</li>
												</ul>
											</li>
										</ul>
									</li>
								<?php elseif ($usuario->is_Master()): ?>
									<li class="menu-dropdown classic-menu-dropdown " style="color: #BDBDBD">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;" style="color: #BDBDBD">
											<?php echo lang('GESTAO'); ?> <i class="fa fa-angle-down" style="color: #BDBDBD"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-bar-chart-o"></i>
													<?php echo lang('GESTAO_DRE_MENSAL'); ?>
												</span>
											</li>
											<li class="dropdown-submenu">
												<a href="javascript:;" style="color: #BDBDBD">
													<i class="fa fa-usd" style="color: #BDBDBD"></i>
													<?php echo lang('FINANCEIRO'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-bar-chart-o"></i>
															<?php echo lang('GESTAO_ANALISE'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-bar-chart-o"></i>
															<?php echo lang('PAINEL_ANALISEMENSAL'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-th font-"></i>
															<?php echo lang('GESTAO_ESTOQUE'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-sign-out"></i>
															<?php echo lang('GESTAO_DESPESA'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-money"></i>
															<?php echo lang('GESTAO_RECEITA'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-usd"></i>
															<?php echo lang('GESTAO_FATURAMENTO'); ?>
														</span>
													</li>
												</ul>
											</li>
										</ul>
									</li>
								<?php endif; ?>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('CONFIGURACOES'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-repeat"></i>
												<?php echo lang('ATUALIZACOES'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=empresa&acao=listar">
														<i class="fa fa-home"></i>
														<?php echo lang('EMPRESA_TITULO'); ?>
													</a>
												</li>
												<?php if ($usuario->is_Controller()): ?>
													<li>
														<a href="webservices/estoque.php" target="_blank" class="iconify">
															<i class="fa fa-tasks"></i>
															<?php echo lang('ATUALIZAR_ESTOQUE'); ?>
														</a>
													</li>
													<li>
														<a href="webservices/enderecos.php" target="_blank" class="iconify">
															<i class="fa fa-map-marker"></i>
															<?php echo lang('ATUALIZAR_MAPA'); ?>
														</a>
													</li>
												<?php endif; ?>
											</ul>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=origem" class="iconify">
												<i class="fa fa-clipboard"></i>
												<?php echo lang('ORIGEM'); ?>
											</a>
										</li>
										<?php if (false): ?>
											<?php if ($usuario->is_Administrativo()): ?>
												<li class="dropdown-submenu">
													<a href="javascript:;">
														<i class="fa fa-clock-o"></i>
														<?php echo lang('PONTO_TITULO'); ?>
													</a>
													<ul class="dropdown-menu">
														<li>
															<a href="index.php?do=ponto_eletronico&acao=horariolistar">
																<i class="fa fa-calendar"></i>
																<?php echo lang('PONTO_HORARIO'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=tabelalistar">
																<i class="fa fa-table"></i>
																<?php echo lang('PONTO_TABELA'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=feriadolistar">
																<i class="fa fa-sun-o"></i>
																<?php echo lang('PONTO_FERIADO'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=relatorioponto">
																<i class="fa fa-history"></i>
																<?php echo lang('PONTO_RELATORIO_TITULO'); ?>
															</a>
														</li>
													</ul>
												</li>
											<?php endif; ?>
										<?php endif; ?>
										<?php if ($usuario->is_Gerencia()): ?>
											<li class="dropdown-submenu">
												<a href="index.php?do=usuario&acao=listar">
													<i class="fa fa-user"></i>
													<?php echo lang('USUARIO_TITULO'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=usuario&acao=adicionar" class="iconify">
															<i class="fa fa-plus-square"></i>
															<?php echo lang('USUARIO_ADICIONAR'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=usuario&acao=bloqueados" class="iconify">
															<i class="fa fa-ban"></i>
															<?php echo lang('USUARIO_BLOQUEADOS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=usuario&acao=listar" class="iconify">
															<i class="fa fa-list"></i>
															<?php echo lang('USUARIO_LISTAR'); ?>
														</a>
													</li>
												</ul>
											</li>
										<?php endif; ?>
										<?php if ($core->tipo_sistema == 4): ?>
											<li class="dropdown-submenu">
												<a href="index.php?do=usuario&acao=listar">
													<i class="fa fa-cog" aria-hidden="true"></i>
													<?php echo lang('SISTEMA'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=taxas&acao=listar" class="iconify">
															<i class="fa fa-usd"></i>
															<?php echo lang('TAXAS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=bairros&acao=listar" class="iconify">
															<i class="fa fa-map-marker"></i>
															<?php echo lang('BAIRROS'); ?>
														</a>
													</li>
												</ul>
											</li>
										<?php endif; ?>
									</ul>
								</li>
							<?php elseif ($usuario->igual_Contador()): ?>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('FINANCEIRO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li>
											<a href="index.php?do=contador&acao=recebidas" class="iconify">
												<i class="fa fa-plus-square"></i>
												<?php echo lang('CONTAS_RECEBIDAS'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=contador&acao=despesaspagas" class="iconify">
												<i class="fa fa-minus-square"></i>
												<?php echo lang('FINANCEIRO_DESPESASPAGAS'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=boleto_sigesis&acao=listar" class="iconify">
												<i class="fa fa-file-text-o"></i>
												Boletos Sigesis
											</a>
										</li>
										<li>
											<a href="index.php?do=contador&acao=extrato" class="iconify">
												<i class="fa fa-sort-numeric-asc"></i>
												<?php echo lang('EXTRATO'); ?>
											</a>
										</li>
									</ul>
								</li>
								<?php if ($core->tipo_sistema == 2 || $core->tipo_sistema == 3): ?>
									<li class="menu-dropdown classic-menu-dropdown" style="color: #BDBDBD">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;" style="color: #BDBDBD">
											<?php echo lang('NOTA_FISCAL'); ?> <i class="fa fa-angle-down"
												style="color: #BDBDBD"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-list"></i>
													<?php echo lang('NOTA_LISTAR'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-repeat"></i>
													<?php echo lang('NOTA_INVENTARIO'); ?>
												</span>
											</li>
										</ul>
									</li>
								<?php else: ?>
									<li class="menu-dropdown classic-menu-dropdown ">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;">
											<?php echo lang('NOTA_FISCAL'); ?>
											<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<a href="index.php?do=contador&acao=notafiscal" class="iconify">
													<i class="fa fa-list"></i>
													<?php echo lang('NOTA_LISTAR'); ?>
												</a>
											</li>
											<li>
												<a href="index.php?do=contador&acao=inventario" class="iconify">
													<i class="fa fa-repeat"></i>
													<?php echo lang('NOTA_INVENTARIO'); ?>
												</a>
											</li>
										</ul>
									</li>
								<?php endif; ?>
							<?php else: ?>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('CADASTRO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li>
											<a href="index.php?do=cadastro&acao=adicionar" class="iconify">
												<i class="fa fa-plus-square"></i>
												<?php echo lang('CADASTRO_ADICIONAR'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=listar" class="iconify">
												<i class="fa fa-list"></i>
												<?php echo lang('CADASTRO_LISTAR'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=clientes" class="iconify">
												<i class="fa fa-list"></i>
												<?php echo lang('CADASTRO_CLIENTES'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=fornecedores" class="iconify">
												<i class="fa fa-list"></i>
												<?php echo lang('CADASTRO_FORNECEDORES'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=buscar" class="iconify">
												<i class="fa fa-search"></i>
												<?php echo lang('CADASTRO_BUSCAR'); ?>
											</a>
										</li>
									</ul>
								</li>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('VENDAS_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li>
											<a href="index.php?do=vendas&acao=novavenda" class="iconify">
												<i class="fa fa-barcode"></i>
												<?php echo lang('VENDAS_NOVA'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=vendas_do_dia" class="iconify">
												<i class="fa fa-calendar-o"></i>
												<?php echo lang('VENDAS_DO_DIA'); ?>
											</a>
										</li>
										<?php if ($usuario->is_VendaAberto()): ?>
											<?php if ($core->tipo_sistema == 4): ?>
												<li>
													<a href="index.php?do=vendas&acao=vendaspedidosentrega" class="iconify">
														<i class="fa fa-exclamation-triangle"></i>
														<?php echo lang('VENDAS_ABERTO'); ?>
													</a>
												</li>
											<?php else: ?>
												<?php if ($usuario->is_VendaAberto() && $core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
													<li>
														<a href="index.php?do=vendas_em_aberto" class="iconify">
															<i class="fa fa-exclamation-triangle"></i>
															<?php echo lang('VENDAS_ABERTO'); ?>
														</a>
													</li>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
										<?php if ($usuario->is_Orcamento()): ?>
											<?php if ($core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
												<li>
													<a href="index.php?do=vendas&acao=vendasorcamento" class="iconify">
														<i class="fa fa-list"></i>
														<?php echo lang('ORCAMENTOS'); ?>
													</a>
												</li>
											<?php endif; ?>
										<?php endif; ?>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-inbox"></i>
												<?php echo lang('MENU_CAIXA'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=caixa&acao=adicionar" class="iconify">
														<i class="fa fa-inbox"></i>
														<?php echo lang('CAIXA_ABRIR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=listar" class="iconify">
														<i class="fa fa-calendar-o"></i>
														<?php echo lang('CAIXA_DIA'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=aberto" class="iconify">
														<i class="fa fa-exclamation-triangle"></i>
														<?php echo lang('CAIXA_EMABERTO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=listarretiradas" class="iconify">
														<i class="fa fa-minus-square"></i>
														<?php echo lang('CAIXA_LISTARRETIRADAS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=produto&acao=trocarproduto" class="iconify">
														<i class="fa fa-exchange"></i>
														<?php echo lang('PRODUTO_TROCA_FISCAL'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=produto&acao=trocarprodutoavulso" class="iconify">
														<i class="fa fa-exchange"></i>
														<?php echo lang('PRODUTO_TROCA_AVULSO'); ?>
													</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('TABELA_PRECO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<?php
										$retorno_row = $produto->getTabelaPrecos();
										if ($retorno_row):
											foreach ($retorno_row as $exrow): ?>
												<li>
													<a href="index.php?do=tabela&acao=tabela&id=<?php echo $exrow->id; ?>"
														class="iconify">
														<i class="fa fa-angle-right"></i>
														<?php echo $exrow->tabela; ?>
													</a>
												</li>
											<?php endforeach;
											unset($exrow);
										endif;
										?>
										<li>
											<a href="index.php?do=tabela&acao=consultaprecos" class="iconify">
												<i class="fa fa-search"></i>
												<?php echo lang('TABELA_PRECO_CONSULTAR'); ?>
											</a>
										</li>
									</ul>
								</li>
							<?php endif; ?>

							<li class="menu-dropdown classic-menu-dropdown ">
								<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
									href="javascript:;">
									<?php echo lang('AJUDA'); ?>
									<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-left">
									<li>
										<a href="#" class="iconify" onclick="window.location.reload(true)">
											<i class="fa fa-repeat"></i>
											<?php echo lang('RECARREGAR'); ?>
										</a>
									</li>
									<li>
										<a href="https://sigesistema.com.br/suporte-sige.exe" target="_blank"
											class="iconify">
											<i class="fa fa-desktop"></i>
											<?php echo lang('ACESSO_REMOTO'); ?>
										</a>
									</li>
									<li>
										<a href="https://centraldeajuda.sigesistema.com.br" target="_blank"
											class="dropdown-toggle">
											<img src="https://centraldeajuda.sigesistema.com.br/img/claudio.png"
												width="20px">
											<?php echo lang('CENTRAL_AJUDA'); ?>
										</a>
									</li>
									<li>
										<a href="https://api.whatsapp.com/send?phone=553138291980&text=Ol%C3%A1%2c%20eu%20gostaria%20suporte%20para%20o%20sistema%20de%20delivery.&source=&data="
											target="_blank" class="iconify">
											<i class="fa fa-comment"></i>
											<?php echo lang('WHATSAPP'); ?>
										</a>
									</li>
									<li>
										<a href="http://www.sigesistema.com.br/contato/" target="_blank" class="iconify">
											<i class="fa fa-phone"></i>
											<?php echo lang('CONTATO'); ?>
										</a>
									</li>
									<li>
										<a href="index.php?do=atualizacao&acao=listar" class="iconify">
											<i class="fa fa-gift"></i>
											<?php echo lang('ATUALIZACOES'); ?>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
					<!-- END MEGA MENU -->
				</div>
			</div>
		<?php endif; ?>
		<!-- USADO EM VENDAS > NOVAVENDA -->
		<?php if (Filter::$acao == "novavenda"): ?>
			<div class="page-header-top">
				<div class="" id="containerpdv" style="margin: 0 40px 0 40px"> <!-- class="container" -->
					<!-- BEGIN LOGO -->
					<!-- BEGIN RESPONSIVE MENU TOGGLER -->
					<a href="javascript:;" class="menu-toggler" id="menu-toggler-pdv"></a>
					<!-- END RESPONSIVE MENU TOGGLER -->
					<div id="headerpdv">
						<div class="col-md-1 page-logo pull-left">
							<a href="index.php"><img src="./assets/img/logo.png" alt="logo" class="logo-default"></a>
						</div>
						<!-- END LOGO -->
						<div class="col-md-7" id="valores">
							<div class="row" id="headvalores">
								<div class="col-md-4" style="text-align: center;">
									<h4 class="bold nomenclatura_valores"><?php echo lang('VALOR_TOTAL'); ?></h4>
									<i>
										<h2 class="bold italic font-blue-madison" id="valor2" style="margin-top: -3px">
											R$ 0,00
										</h2>
									</i>
								</div>
								<div class="col-md-4" style="text-align: center;">
									<h4 class="bold nomenclatura_valores"><?php echo lang('VALOR_PAGAR'); ?></h4>
									<i>
										<h2 class="bold italic font-green-seagreen" id="valor_pagar"
											style="margin-top: -3px">
											R$ 0,00
										</h2>
									</i>
								</div>
								<div class="col-md-4" style="text-align: center;">
									<h4 class="bold nomenclatura_valores"><?php echo lang('TROCO_DINHEIRO'); ?></h4>
									<i>
										<h2 class="bold italic font-red" id="troco" style="margin-top: -3px">
											R$ 0,00
										</h2>
									</i>
								</div>
							</div>
							<div class="row" id="headvalores_secundarios">
								<div class="col-md-4" style="text-align: center;">
									<span><?php echo lang('DESCONTO') . ':'; ?></span>
									<span id="valor_desconto_pdv">R$ 0,00</span>
									<br>
									<span><?php echo lang('ACRESCIMO') . ':'; ?></span>
									<span id="valor_acrescimo_pdv">R$ 0,00</span>
								</div>
								<div class="col-md-4" style="text-align: center;">
									<span><?php echo lang('VALOR_PAGO') . ':'; ?></span>
									<span id="valor_pago_pdv">R$ 0,00</span>
									</i>
								</div>
							</div>
						</div>
						<?php
						$rowempresa = Core::getRowById('empresa', 'id=1');
						?>
						<div class="pull-right">
							<a href="#">
								<img src="./uploads/logomarcapdv/<?php echo $rowempresa->logomarca_pdv; ?>"
									alt="Logo cliente" class="logo-default" id="logo_cliente_header" height="85">
							</a>
						</div>
					</div>
					<!-- BEGIN TOP NAVIGATION MENU -->
					<div class="top-menu" id="top-menu-header2"> <!-- style="margin-top: -15px;" -->
						<ul class="nav navbar-nav pull-right">
							<li class="droddown dropdown-separator">
								<span class="separator"></span>
							</li>
							<li class="dropdown dropdown-dark">
								<a href="javascript:void(0);" class="dropdown-toggle">
									<span class="username username-hide-mobile">
										<?php echo $usuario->nomeempresa; ?>
									</span>
								</a>
							</li>
							<!-- END USER LOGIN DROPDOWN -->
							<li class="droddown dropdown-separator">
								<span class="separator"></span>
							</li>
							<!-- BEGIN VENDA RAPIDA -->
							<li class="dropdown dropdown-dark">
								<a href="index.php?do=vendas&acao=novavenda" class="dropdown-toggle">
									<span class="username username-hide-mobile">
										<?php echo lang('F12'); ?>
									</span>
								</a>
							</li>
							<!-- SEPARATOR -->
							<li class="droddown dropdown-separator">
								<span class="separator"></span>
							</li>
							<li class="dropdown dropdown-user dropdown-dark">
								<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
									data-close-others="true">
									<span class="username username-hide-mobile">
										<?php echo $usuario->nome; ?>
									</span>
								</a>
								<ul class="dropdown-menu dropdown-menu-default">
									<li>
										<a href="index.php?do=usuarioeditar&id=<?php echo $usuario->uid; ?>">
											<i class="icon-user"></i>
											<?php echo lang('USUARIO_EDITAR'); ?>
										</a>
									</li>
									<li class="divider">
									</li>
									<li>
										<a href="logout.php">
											<i class="icon-key"></i>
											<?php echo lang('SAIR'); ?>
										</a>
									</li>
								</ul>
							</li>
							<!-- END USER LOGIN DROPDOWN -->
						</ul>
					</div>
					<!-- END TOP NAVIGATION MENU -->
				</div>
			</div>
			<!-- USADO EM TODOS MENOS NOVA VENDA -->
		<?php else: ?>
			<!-- BEGIN HEADER TOP -->
			<div class="page-header-top">
				<div class="" style="margin: 0 40px 0 40px;"> <!-- class="container" -->
					<!-- BEGIN LOGO -->
					<div class="page-logo">
						<a href="index.php">
							<img src="./assets/img/logo.png" alt="logo" class="logo-default">
						</a>
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
									<span class="username username-hide-mobile">
										<?php echo substr($usuario->nomeempresa, 0, 30); ?>
										<!-- NOME DA EMPRESA TERÁ SOMENTE 3O CARACTERS -->
									</span>
								</a>
							</li>
							<!-- END USER LOGIN DROPDOWN -->
							<li class="droddown dropdown-separator">
								<span class="separator"></span>
							</li>
							<li>
								<a href="https://centraldeajuda.sigesistema.com.br" target="_blank" class="dropdown-toggle">
									<img src="https://centraldeajuda.sigesistema.com.br/img/claudio.png" width="35px">
									<?php echo lang('CENTRAL_AJUDA'); ?>
								</a>
							</li>
							<!-- END USER LOGIN DROPDOWN -->
							<li class="droddown dropdown-separator">
								<span class="separator"></span>
							</li>
							<!-- BEGIN VENDA DO DIA -->
							<li class="dropdown dropdown-dark">
								<a href="index.php?do=vendas_do_dia" target="_blank" class="dropdown-toggle">
									<span class="username username-hide-mobile">
										<?php echo lang('F9'); ?>
									</span>
								</a>
							</li>
							<!-- SEPARATOR -->
							<li class="droddown dropdown-separator">
								<span class="separator"></span>
							</li>
							<!-- BEGIN VENDA RAPIDA -->
							<li class="dropdown dropdown-dark">
								<a href="index.php?do=vendas&acao=novavenda" class="dropdown-toggle">
									<span class="username username-hide-mobile">
										<?php echo lang('F12'); ?>
									</span>
								</a>
							</li>
							<!-- SEPARATOR -->
							<li class="droddown dropdown-separator">
								<span class="separator"></span>
							</li>
							<?php
							$certificadoVencimento = $empresa->verificarVencimentoCertificado();
							$seDuplicouRenovacao = $empresa->verificaRegistroDuplicadoCertificadosVencidos();
							$data_vencimento = ($certificadoVencimento) ? $certificadoVencimento : '';
							if ($data_vencimento):
								$data_cobranca_20d = date('Y-m-d', strtotime('-20 days', strtotime($certificadoVencimento)));
								$hoje = new DateTime();
								$Dia30 = new DateTime();
								$Dia30->add(new DateInterval('P30D'));
								$style = (strtotime($data_vencimento) < strtotime($hoje->format("Y-m-d")) || $data_vencimento == '0000-00-00')
									? " style='color: #fff; font-weight: bold; background-color: #f00; border-radius: 5px; padding: 5px;' "
									: " style='color: #332701; font-weight: bold; background-color: #ffc107; border-radius: 5px; padding: 5px;' ";
								$data_venc_certa = exibedata($data_vencimento);
								$resposta = (strtotime($data_vencimento) < strtotime($hoje->format("Y-m-d")) || $data_vencimento == '0000-00-00')
									? "URGENTE#O seu certificado digital venceu dia $data_venc_certa. Clique aqui!" : ((strtotime($data_vencimento) < strtotime($Dia30->format("Y-m-d"))) ? "IMPORTANTE#O seu certificado digital está prestes a vencer: $data_venc_certa. Clique aqui!" : "");
								$mensagem = explode('#', $resposta);
								if (($mensagem[0] === "URGENTE" || $mensagem[0] === "IMPORTANTE") && $usuario->is_Master()):
									?>
									<li class="dropdown dropdown-dark">
										<a href="javascript:void(0)" class="dropdown-toggle new-update" data-toggle="modal"
											data-target="#cadRenovacaoCertificado" title="<?php echo $mensagem[1] ?>">
											<span class="username username-hide-mobile" <?php echo $style ?>>
												<?php echo lang('ALERTA_VENCIMENTO_CERTIFICADO'); ?>
											</span>
										</a>
									</li>
								<?php endif; ?>
							<?php endif; ?>
							<li class="droddown dropdown-separator">
								<span class="separator"></span>
							</li>
							<li class="dropdown dropdown-user dropdown-dark">
								<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
									data-close-others="true">
									<span class="username username-hide-mobile">
										<?php echo $usuario->nome; ?>
									</span>
								</a>
								<ul class="dropdown-menu dropdown-menu-default">
									<li>
										<a href="index.php?do=usuarioeditar&id=<?php echo $usuario->uid; ?>">
											<i class="icon-user"></i>
											<?php echo lang('USUARIO_EDITAR'); ?>
										</a>
									</li>
									<li class="divider">
									</li>
									<li>
										<a href="logout.php">
											<i class="icon-key"></i>
											<?php echo lang('SAIR'); ?>
										</a>
									</li>
								</ul>
							</li>
							<!-- END USER LOGIN DROPDOWN -->
						</ul>
					</div>
					<!-- END TOP NAVIGATION MENU -->
				</div>
			</div>
			<!-- END HEADER TOP -->
		<?php endif; ?>
		<!-- NAVBAR USADO EM TODOS OS MENUS -->
		<?php Filter::$acao;
		if (Filter::$acao == "" || Filter::$acao != "novavenda"): ?>
			<div class="page-header-menu bg-<?php echo $core->primeira_cor; ?>">
				<div class="container">
					<!-- BEGIN MEGA MENU -->
					<!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
					<!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
					<div class="hor-menu ">
						<ul class="nav navbar-nav">
							<?php if ($core->tipo_sistema == 5): ?>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('ATENDIMENTO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<?php if ($usuario->is_Gerencia()): ?>
											<li class="dropdown-submenu">
												<a href="javascript:;">
													<i class="fa fa-cogs"></i>
													<?php echo lang('ORCAMENTO_TITULO'); ?>
												</a>
												<ul class="dropdown-menu pull-left">
													<li>
														<a href="index.php?do=ordem_servico&acao=adicionar" class="iconify">
															<i class="fa fa-plus"></i>
															<?php echo lang('ORCAMENTO_ADICIONAR'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=ordem_servico&acao=orcamentos" class="iconify">
															<i class="fa fa-list"></i>
															<?php echo lang('ORCAMENTO_LISTAR'); ?>
														</a>
													</li>
												</ul>
											</li>
										<?php else: ?>
											<li>
												<a href="index.php?do=ordem_servico&acao=orcamentos" class="iconify">
													<i class="fa fa-cogs"></i>
													<?php echo lang('ORCAMENTO_TITULO'); ?>
												</a>
											</li>
										<?php endif; ?>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-wrench"></i>
												<?php echo lang('ORDEM_SERVICO_TITULO'); ?>
											</a>
											<ul class="dropdown-menu pull-left">
												<li>
													<a href="index.php?do=ordem_servico&acao=listar" class="iconify">
														<i class="fa fa-list"></i>
														<?php echo lang('ORDEM_SERVICO_LISTAR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=ordem_servico&acao=listar_os_finalizadas"
														class="iconify">
														<i class="fa fa-list"></i>
														<?php echo lang('ORDEM_SERVICO_LISTAR_FINALIZADAS'); ?>
													</a>
												</li>
											</ul>
										</li>
										<li>
											<a href="index.php?do=equipamento&acao=listar">
												<i class="fa fa-building"></i>
												<?php echo lang('EQUIPAMENTO_TITULO'); ?>
											</a>
										</li>
									</ul>
								</li>
							<?php endif; ?>
							<?php if ($usuario->is_Administrativo()): ?>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('VENDAS_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li>
											<a href="index.php?do=vendas&acao=novavenda" class="iconify">
												<i class="fa fa-barcode"></i>
												<?php echo lang('VENDAS_NOVA'); ?>
											</a>
										</li>
										<!--
										<li>
											<a href="index.php?do=ecomd" class="iconify">
												<i class="fa fa-barcode"></i>
												<?php echo lang('ECOMMERCE'); ?>
											</a>
										</li>
										-->
										<li>
											<a href="index.php?do=vendas_do_dia" class="iconify">
												<i class="fa fa-calendar-o"></i>
												<?php echo lang('VENDAS_DO_DIA'); ?>
											</a>
										</li>
										<?php if ($core->tipo_sistema == 4): ?>
											<li>
												<a href="index.php?do=vendas&acao=vendaspedidosentrega" class="iconify">
													<i class="fa fa-exclamation-triangle"></i>
													<?php echo lang('VENDAS_ABERTO'); ?>
												</a>
											</li>
										<?php else: ?>
											<?php if ($usuario->is_VendaAberto() && $core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
												<li>
													<a href="index.php?do=vendas_em_aberto" class="iconify">
														<i class="fa fa-exclamation-triangle"></i>
														<?php echo lang('VENDAS_ABERTO'); ?>
													</a>
												</li>
											<?php else: ?>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-exclamation-triangle"></i>
														<?php echo lang('VENDAS_ABERTO'); ?>
													</span>
												</li>
											<?php endif; ?>
										<?php endif; ?>
										<?php if ($usuario->is_nfc() && $core->tipo_sistema != 2): ?>
											<li class="dropdown-submenu">
												<a href="javascript:;">
													<i class="fa fa-file-text-o"></i>
													<?php echo lang('FISCAL_CONTROLE'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=vendas&acao=vendas_fiscal_lote" class="iconify">
															<i class="fa fa-exclamation-circle"></i>
															<?php echo lang('FISCAL_CONTROLE_LISTAR'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=vendas&acao=vendas_emitidas_lote" class="iconify">
															<i class="fa fa-check-square-o"></i>
															<?php echo lang('FISCAL_CONTROLE_EMITIDAS'); ?>
														</a>
													</li>
												</ul>
											</li>
										<?php endif; ?>
										<?php if ($usuario->is_Orcamento() && $core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
											<li>
												<a href="index.php?do=vendas&acao=vendasorcamento" class="iconify">
													<i class="fa fa-list"></i>
													<?php echo lang('ORCAMENTOS'); ?>
												</a>
											</li>
										<?php else: ?>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-list"></i>
													<?php echo lang('ORCAMENTOS'); ?>
												</span>
											</li>
										<?php endif; ?>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-inbox"></i>
												<?php echo lang('MENU_CAIXA'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=caixa&acao=adicionar" class="iconify">
														<i class="fa fa-inbox"></i>
														<?php echo lang('CAIXA_ABRIR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=listar" class="iconify">
														<i class="fa fa-calendar-o"></i>
														<?php echo lang('CAIXA_DIA'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=aberto" class="iconify">
														<i class="fa fa-exclamation-triangle"></i>
														<?php echo lang('CAIXA_EMABERTO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=listarretiradas" class="iconify">
														<i class="fa fa-minus-square"></i>
														<?php echo lang('CAIXA_LISTARRETIRADAS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=produto&acao=trocarproduto" class="iconify">
														<i class="fa fa-exchange"></i>
														<?php echo lang('PRODUTO_TROCA_FISCAL'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=produto&acao=trocarprodutoavulso" class="iconify">
														<i class="fa fa-exchange"></i>
														<?php echo lang('PRODUTO_TROCA_AVULSO'); ?>
													</a>
												</li>
											</ul>
										</li>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-barcode"></i>
												<?php echo lang('CONTROLE_VENDAS'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=vendas_por_periodo" class="iconify">
														<i class="fa fa-shopping-cart"></i>
														<?php echo lang('VENDAS_PERIODO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=vendas&acao=vendasconsolidado" class="iconify">
														<i class="fa fa-list-ol"></i>
														<?php echo lang('VENDAS_CONSOLIDADAS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=vendas&acao=vendasconsolidadoprodutos"
														class="iconify">
														<i class="fa fa-list-ol"></i>
														<?php echo lang('VENDAS_CONSOLIDADAS_PRODUTOS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=vendas&acao=vendasclienteperiodo" class="iconify">
														<i class="fa fa-users"></i>
														<?php echo lang('VENDAS_CLIENTE_PERIODO'); ?>
													</a>
												</li>
												<?php if ($core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
													<li>
														<a href="index.php?do=vendas&acao=vendasprodutofornecedor" class="iconify">
															<i class="fa fa-cubes"></i>
															<?php echo lang('VENDAS_PRODUTO_FABRICANTE'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=romaneiovenda" class="iconify">
															<i class="fa fa-file-text"></i>
															<?php echo lang('ROMANEIO_CARGA'); ?> </a>
													</li>
													<li>
														<a href="index.php?do=vendas&acao=impressaoproducao" class="iconify">
															<i class="fa fa-cogs"></i>
															<?php echo lang('IMPRIMIR_VENDA_OS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=vendas&acao=vendascrediario" class="iconify">
															<i class="fa fa-money"></i>
															<?php echo lang('CADASTRO_CREDIARIO_FICHA'); ?>
														</a>
													</li>
												<?php else: ?>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-cubes"></i>
															<?php echo lang('VENDAS_PRODUTO_FORNECEDOR'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-file-text"></i>
															<?php echo lang('ROMANEIO_CARGA'); ?> </a>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-money"></i>
															<?php echo lang('CADASTRO_CREDIARIO_FICHA'); ?>
														</span>
													</li>
												<?php endif; ?>
											</ul>
										</li>
									</ul>
								</li>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('CADASTRO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li>
											<a href="index.php?do=cadastro&acao=adicionar" class="iconify">
												<i class="fa fa-plus-square"></i>
												<?php echo lang('CADASTRO_ADICIONAR'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=clientes" class="iconify">
												<i class="fa fa-list"></i>
												<?php echo lang('CADASTRO_CLIENTES'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=fornecedores" class="iconify">
												<i class="fa fa-list"></i>
												<?php echo lang('CADASTRO_FORNECEDORES'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=buscar" class="iconify">
												<i class="fa fa-search"></i>
												<?php echo lang('CADASTRO_BUSCAR'); ?>
											</a>
										</li>
									</ul>
								</li>
								<li class="menu-dropdown mega-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;" class="dropdown-toggle">
										<?php echo lang('PRODUTO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu" style="min-width: 480px">
										<li>
											<div class="mega-menu-content">
												<div class="row">
													<div class="col-md-6">
														<ul class="mega-menu-submenu">
															<li>
																<a href="index.php?do=produto&acao=adicionar" class="iconify">
																	<i class="fa fa-plus-square"></i>
																	<?php echo lang('PRODUTO_ADICIONAR'); ?>
																</a>
															</li>
															<li>
																<a href="index.php?do=produto&acao=grade" class="iconify">
																	<i class="fa fa-th"></i>
																	<?php echo lang('GRADE_VENDAS'); ?>
																</a>
															</li>
															<li>
																<a href="index.php?do=produto&acao=listar" class="iconify">
																	<i class="fa fa-list"></i>
																	<?php echo lang('PRODUTO_LISTAR'); ?>
																</a>
															</li>
															<li>
																<a href="index.php?do=estoque&acao=listar" class="iconify">
																	<i class="fa fa-tasks"></i>
																	<?php echo lang('ESTOQUE_TITULO'); ?>
																</a>
															</li>
															<li>
																<a href="index.php?do=produto&acao=pendentes" class="iconify">
																	<i class="fa fa-pause"></i>
																	<?php echo lang('PRODUTO_PENDENTES'); ?>
																</a>
															</li>
															<li>
																<a href="index.php?do=produto&acao=atualizacodbarras"
																	class="iconify">
																	<i class="fa fa-barcode"></i>
																	<?php echo lang('PRODUTO_ATUALIZAR_CODBARRAS'); ?>
																</a>
															</li>
														</ul>
													</div>
													<div class="col-md-6">
														<ul class="mega-menu-submenu">
															<li>
																<a href="index.php?do=produto&acao=buscar" class="iconify">
																	<i class="fa fa-search"></i>
																	<?php echo lang('PRODUTO_BUSCAR'); ?>
																</a>
															</li>
															<li class="dropdown-submenu">
																<a href="index.php?do=tabela&acao=listar">
																	<i class="fa fa-table"></i>
																	<?php echo lang('TABELA_PRECO_TITULO'); ?>
																</a>
																<ul class="dropdown-menu">
																	<li>
																		<a href="index.php?do=tabela&acao=adicionar"
																			class="iconify">
																			<i class="fa fa-plus-square"></i>
																			<?php echo lang('TABELA_PRECO_ADICIONAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=tabela&acao=listar"
																			class="iconify">
																			<i class="fa fa-list"></i>
																			<?php echo lang('TABELA_PRECO_LISTAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=tabela&acao=consultaprecos"
																			class="iconify">
																			<i class="fa fa-search"></i>
																			<?php echo lang('TABELA_PRECO_CONSULTAR'); ?>
																		</a>
																	</li>
																</ul>
															</li>
															<li class="dropdown-submenu">
																<a href="index.php?do=tabela&acao=listar">
																	<i class="fa fa-puzzle-piece"></i>
																	<?php echo lang('PRODUTO_ATRIBUTO'); ?>
																</a>
																<ul class="dropdown-menu">
																	<li>
																		<a href="index.php?do=atributo&acao=adicionar"
																			class="iconify">
																			<i class="fa fa-plus-square"></i>
																			<?php echo lang('PRODUTO_ATRIBUTO_ADICIONAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=atributo&acao=listar"
																			class="iconify">
																			<i class="fa fa-list"></i>
																			<?php echo lang('PRODUTO_ATRIBUTO_LISTAR'); ?>
																		</a>
																	</li>
																</ul>
															</li>
															<li class="dropdown-submenu">
																<a href="index.php?do=grupo&acao=listar">
																	<i class="fa fa-list-alt"></i>
																	<?php echo lang('GRUPO_TITULO'); ?>
																</a>
																<ul class="dropdown-menu">
																	<li>
																		<a href="index.php?do=grupo&acao=adicionar"
																			class="iconify">
																			<i class="fa fa-plus-square"></i>
																			<?php echo lang('GRUPO_ADICIONAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=grupo&acao=listar"
																			class="iconify">
																			<i class="fa fa-list"></i>
																			<?php echo lang('GRUPO_LISTAR'); ?>
																		</a>
																	</li>
																</ul>
															</li>
															<li class="dropdown-submenu">
																<a href="index.php?do=categoria&acao=listar">
																	<i class="fa fa-table"></i>
																	<?php echo lang('CATEGORIA_TITULO'); ?>
																</a>
																<ul class="dropdown-menu">
																	<li>
																		<a href="index.php?do=categoria&acao=adicionar"
																			class="iconify">
																			<i class="fa fa-plus-square"></i>
																			<?php echo lang('CATEGORIA_ADICIONAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=categoria&acao=listar"
																			class="iconify">
																			<i class="fa fa-list"></i>
																			<?php echo lang('CATEGORIA_LISTAR'); ?>
																		</a>
																	</li>
																</ul>
															</li>
															<li class="dropdown-submenu">
																<a href="index.php?do=fabricante&acao=listar">
																	<i class="fa fa-database"></i>
																	<?php echo lang('FABRICANTE_TITULO'); ?>
																</a>
																<ul class="dropdown-menu">
																	<li>
																		<a href="index.php?do=fabricante&acao=adicionar"
																			class="iconify">
																			<i class="fa fa-plus-square"></i>
																			<?php echo lang('FABRICANTE_ADICIONAR'); ?>
																		</a>
																	</li>
																	<li>
																		<a href="index.php?do=fabricante&acao=listar"
																			class="iconify">
																			<i class="fa fa-list"></i>
																			<?php echo lang('FABRICANTE_LISTAR'); ?>
																		</a>
																	</li>
																</ul>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</li>
									</ul>
								</li>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('FINANCEIRO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-minus-square"></i>
												<?php echo lang('DESPESAS'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=despesa&acao=adicionar" class="iconify">
														<i class="fa fa-minus"></i>
														<?php echo lang('FINANCEIRO_ADICIONAR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=despesa&acao=despesaspagas" class="iconify">
														<i class="fa fa-check"></i>
														<?php echo lang('FINANCEIRO_DESPESASPAGAS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=despesa&acao=despesas" class="iconify">
														<i class="fa fa-exclamation"></i>
														<?php echo lang('FINANCEIRO_DESPESASAPAGAR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=boleto_sigesis&acao=listar" class="iconify">
														<i class="fa fa-file-text-o"></i>
														Boletos Sigesis
													</a>
												</li>
												<li>
													<a href="index.php?do=despesa&acao=agrupar" class="iconify">
														<i class="fa fa-share-alt"></i>
														<?php echo lang('FINANCEIRO_DESPESASAGRUPAR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=despesa&acao=pagarcartoes" class="iconify">
														<i class="fa fa-credit-card"></i>
														<?php echo lang('FINANCEIRO_DESPESASCARTOES'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=extrato&acao=conciliardespesas" class="iconify">
														<i class="fa fa-link"></i>
														<?php echo lang('EXTRATO_CONCILIARDESPESAS'); ?>
													</a>
												</li>
											</ul>
										</li>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-plus-square"></i>
												<?php echo lang('FINANCEIRO_RECEITAS'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=faturamento&acao=receitarapida" class="iconify">
														<i class="fa fa-usd"></i>
														<?php echo lang('RECEITA_RAPIDA'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=faturamento&acao=adicionar" class="iconify">
														<i class="fa fa-plus"></i>
														<?php echo lang('FINANCEIRO_ADICIONAR_RECEITA'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=faturamento&acao=recebidas" class="iconify">
														<i class="fa fa-check"></i>
														<?php echo lang('CONTAS_RECEBIDAS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=faturamento&acao=receber" class="iconify">
														<i class="fa fa-exclamation"></i>
														<?php echo lang('CONTAS_A_RECEBER'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=faturamento&acao=receber_crediario" class="iconify">
														<i class="fa fa-money"></i>
														<?php echo lang('PROMISSORIAS_ARECEBER'); ?>
													</a>
												</li>
											</ul>
										</li>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-bank"></i>
												<?php echo lang('BANCO_TITULO'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=banco&acao=listar" class="iconify">
														<i class="fa fa-bank"></i>
														<?php echo lang('BANCO_TITULO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=extrato&acao=extrato" class="iconify">
														<i class="fa fa-sort-numeric-asc"></i>
														<?php echo lang('EXTRATO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=faturamento&acao=cheques" class="iconify">
														<i class="fa fa-stack-overflow"></i>
														<?php echo lang('FINANCEIRO_CHEQUES'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=extrato&acao=arquivoboletos" class="iconify">
														<i class="fa fa-bold"></i>
														<?php echo lang('EXTRATO_ARQUIVOBOLETOS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=extrato&acao=arquivobanco" class="iconify">
														<i class="fa fa-file-excel-o"></i>
														<?php echo lang('EXTRATO_ARQUIVOBANCOS'); ?>
													</a>
												</li>
											</ul>
										</li>
										<?php if ($core->tipo_sistema == 2 || $core->tipo_sistema == 3): ?>
											<li class="dropdown-submenu">
												<a href="javascript:;" style="color: #BDBDBD">
													<i class="fa fa-file-text-o" style="color: #BDBDBD"></i>
													<?php echo lang('NOTA_FISCAL'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-list"></i>
															<?php echo lang('NOTA_LISTAR'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-plus-square"></i>
															<?php echo lang('NOTA_ADICIONAR'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-sign-in"></i>
															<?php echo lang('NOTA_FISCAL_COMPRAS'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-sign-out"></i>
															<?php echo lang('NOTA_FISCAL_VENDAS'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-repeat"></i>
															<?php echo lang('NOTA_INVENTARIO'); ?>
														</span>
													</li>
												</ul>
											</li>
										<?php else: ?>
											<li class="dropdown-submenu">
												<a href="javascript:;">
													<i class="fa fa-file-text-o"></i>
													<?php echo lang('NOTA_FISCAL'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=notafiscal&acao=notafiscal" class="iconify">
															<i class="fa fa-list"></i>
															<?php echo lang('NOTA_LISTAR'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=notafiscal&acao=adicionar" class="iconify">
															<i class="fa fa-plus-square"></i>
															<?php echo lang('NOTA_ADICIONAR'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=xml&acao=entradas" class="iconify">
															<i class="fa fa-sign-in"></i>
															<?php echo lang('NOTA_FISCAL_COMPRAS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=xml&acao=saidas" class="iconify">
															<i class="fa fa-sign-out"></i>
															<?php echo lang('NOTA_FISCAL_VENDAS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=notafiscal&acao=inventario" class="iconify">
															<i class="fa fa-repeat"></i>
															<?php echo lang('NOTA_INVENTARIO'); ?>
														</a>
													</li>
												</ul>
											</li>
										<?php endif; ?>
										<?php if ($usuario->is_Gerencia()): ?>
											<li class="dropdown-submenu">
												<a href="javascript:;">
													<i class="fa fa-cogs"></i>
													<?php echo lang('CONFIGURACOES'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=centrocusto&acao=listar" class="iconify">
															<i class="fa fa-share-alt"></i>
															<?php echo lang('CENTRO_CUSTO_TITULO'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=faturamento&acao=plano_contas" class="iconify">
															<i class="fa fa-bars"></i>
															<?php echo lang('FINANCEIRO_PLANO_CONTAS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=tipopagamento&acao=listar" class="iconify">
															<i class="fa fa-usd"></i>
															<?php echo lang('TIPO_PAGAMENTO'); ?>
														</a>
													</li>
												</ul>
											</li>
											<?php if ($core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
												<li class="dropdown-submenu">
													<a href="javascript:;">
														<i class="fa fa-file-text"></i>
														<?php echo lang('DRE'); ?> </a>
													<ul class="dropdown-menu">
														<li>
															<a href="index.php?do=extrato&acao=dre" class="iconify">
																<i class="fa fa-file-text"></i>
																<?php echo lang('DRE_FINANCEIRO'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=faturamento&acao=metasdre" class="iconify">
																<i class="fa fa-crosshairs"></i>
																<?php echo lang('METAS_DRE'); ?>
															</a>
														</li>
													</ul>
												</li>
											<?php else: ?>
												<li class="dropdown-submenu">
													<a href="javascript:;" style="color: #BDBDBD">
														<i class="fa fa-file-text" style="color: #BDBDBD"></i>
														<?php echo lang('DRE'); ?>
													</a>
													<ul class="dropdown-menu">
														<li>
															<span class="iconify disabled menu-desabilitado">
																<i class="fa fa-file-text"></i>
																<?php echo lang('DRE_FINANCEIRO'); ?>
															</span>
														</li>
														<li>
															<span class="iconify disabled menu-desabilitado">
																<i class="fa fa-crosshairs"></i>
																<?php echo lang('METAS_DRE'); ?>
															</span>
														</li>
													</ul>
												</li>
											<?php endif; ?>
										<?php endif; ?>
									</ul>
								</li>
								<?php if ($core->tipo_sistema == 2): ?>
									<li class="menu-dropdown classic-menu-dropdown ">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;">
											<?php echo lang('CONTABIL_TITULO'); ?>
											<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-file-code-o"></i>
													<?php echo lang('NOTA_LISTAR_CHAVEACESSO'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-list-ol"></i>
													<?php echo lang('VENDAS_FISCAIS'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-times"></i>
													Notas Negadas
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-ban"></i>
													<?php echo lang('NOTA_INUTILIZADAS'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-ban"></i>
													<?php echo lang('NOTA_INUTILIZADAS_NFE'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-file-text-o"></i>
													<?php echo lang('SINTEGRA_ARQUIVO'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-file-text"></i>
													<?php echo lang('SINTEGRA_ARQUIVO_INVENTARIO_FISCAL'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-puzzle-piece"></i>
													Conversão de CFOP
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-file-text"></i>
													<?php echo lang('RECEBIMENTOS_TITULO'); ?>
												</span>
											</li>
										</ul>
									</li>
								<?php else: ?>
									<li class="menu-dropdown classic-menu-dropdown ">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;">
											<?php echo lang('CONTABIL_TITULO'); ?>
											<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<a href="index.php?do=notafiscal&acao=chaveacesso" class="iconify">
													<i class="fa fa-file-code-o"></i>
													<?php echo lang('NOTA_LISTAR_CHAVEACESSO'); ?>
												</a>
											</li>
											<li>
												<a href="index.php?do=vendas&acao=vendasfiscal" class="iconify">
													<i class="fa fa-list-ol"></i>
													<?php echo lang('VENDAS_FISCAIS'); ?>
												</a>
											</li>
											<li>
												<a href="index.php?do=notas_negadas&acao=listar" class="iconify">
													<i class="fa fa-times"></i>
													Notas Negadas
												</a>
											</li>
											<li>
												<a href="index.php?do=notafiscal&acao=inutilizar" class="iconify">
													<i class="fa fa-ban"></i>
													<?php echo lang('NOTA_INUTILIZADAS'); ?>
												</a>
											</li>
											<?php if ($core->tipo_sistema == 3): ?>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-ban"></i>
														<?php echo lang('NOTA_INUTILIZADAS_NFE'); ?>
													</span>
												</li>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-file-text-o"></i>
														<?php echo lang('SINTEGRA_ARQUIVO'); ?>
													</span>
												</li>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-file-text"></i>
														<?php echo lang('SINTEGRA_ARQUIVO_INVENTARIO_FISCAL'); ?>
													</span>
												</li>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-puzzle-piece"></i>
														Conversão de CFOP
													</span>
												</li>
												<li>
													<span class="iconify disabled menu-desabilitado">
														<i class="fa fa-file-text"></i>
														<?php echo lang('RECEBIMENTOS_TITULO'); ?>
													</span>
												</li>
											<?php else: ?>
												<li>
													<a href="index.php?do=notafiscal&acao=inutilizarNFe" class="iconify">
														<i class="fa fa-ban"></i>
														<?php echo lang('NOTA_INUTILIZADAS_NFE'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=notafiscal&acao=sintegra" class="iconify">
														<i class="fa fa-file-text-o"></i>
														<?php echo lang('SINTEGRA_ARQUIVO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=notafiscal&acao=sintegrainventario" class="iconify">
														<i class="fa fa-file-text"></i>
														<?php echo lang('SINTEGRA_ARQUIVO_INVENTARIO_FISCAL'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=cfop&acao=listar" class="iconify">
														<i class="fa fa-puzzle-piece"></i>
														Conversão de CFOP
														</span>
													</a>
												</li>
												<li>
													<a href="index.php?do=notafiscal&acao=das" class="iconify">
														<i class="fa fa-file-text"></i>
														<?php echo lang('RECEBIMENTOS_TITULO'); ?>
													</a>
												</li>
											<?php endif; ?>
										</ul>
									</li>
								<?php endif; ?>
								<?php if ($usuario->is_Master() && $core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
									<li class="menu-dropdown classic-menu-dropdown ">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;">
											<?php echo lang('GESTAO'); ?>
											<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<a href="index.php?do=gestao&acao=dremensal">
													<i class="fa fa-bar-chart-o"></i>
													<?php echo lang('GESTAO_DRE_MENSAL'); ?>
												</a>
											</li>
											<li class="dropdown-submenu">
												<a href="javascript:;">
													<i class="fa fa-usd"></i>
													<?php echo lang('FINANCEIRO'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=extrato&acao=financeiro" class="iconify">
															<i class="fa fa-bar-chart-o"></i>
															<?php echo lang('GESTAO_ANALISE'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=extrato&acao=financeiromensal" class="iconify">
															<i class="fa fa-bar-chart-o"></i>
															<?php echo lang('PAINEL_ANALISEMENSAL'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=extrato&acao=analiseestoque" class="iconify">
															<i class="fa fa-th font-"></i>
															<?php echo lang('GESTAO_ESTOQUE'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=extrato&acao=despesasano" class="iconify">
															<i class="fa fa-sign-out"></i>
															<?php echo lang('GESTAO_DESPESA'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=extrato&acao=receitasano" class="iconify">
															<i class="fa fa-money"></i>
															<?php echo lang('GESTAO_RECEITA'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=extrato&acao=faturamentoano" class="iconify">
															<i class="fa fa-usd"></i>
															<?php echo lang('GESTAO_FATURAMENTO'); ?>
														</a>
													</li>
												</ul>
											</li>
											<?php if ($core->modulo_ponto): ?>
												<li class="dropdown-submenu">
													<a href="javascript:;">
														<i class="fa fa-clock-o"></i>
														<?php echo lang('PONTO_TITULO'); ?>
													</a>
													<ul class="dropdown-menu">
														<li>
															<a href="index.php?do=ponto_eletronico&acao=horariolistar">
																<i class="fa fa-calendar"></i>
																<?php echo lang('PONTO_HORARIO'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=tabelalistar">
																<i class="fa fa-table"></i>
																<?php echo lang('PONTO_TABELA'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=feriadolistar">
																<i class="fa fa-sun-o"></i>
																<?php echo lang('PONTO_FERIADO'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=relatorioponto">
																<i class="fa fa-history"></i>
																<?php echo lang('PONTO_RELATORIO_TITULO'); ?>
															</a>
														</li>
													</ul>
												</li>
											<?php endif; ?>
										</ul>
									</li>
								<?php elseif ($usuario->is_Master()): ?>
									<li class="menu-dropdown classic-menu-dropdown " style="color: #BDBDBD">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;" style="color: #BDBDBD">
											<?php echo lang('GESTAO'); ?>
											<i class="fa fa-angle-down" style="color: #BDBDBD"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-bar-chart-o"></i>
													<?php echo lang('GESTAO_DRE_MENSAL'); ?>
												</span>
											</li>
											<li class="dropdown-submenu">
												<a href="javascript:;" style="color: #BDBDBD">
													<i class="fa fa-usd" style="color: #BDBDBD"></i>
													<?php echo lang('FINANCEIRO'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-bar-chart-o"></i>
															<?php echo lang('GESTAO_ANALISE'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-bar-chart-o"></i>
															<?php echo lang('PAINEL_ANALISEMENSAL'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-th font-"></i>
															<?php echo lang('GESTAO_ESTOQUE'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-sign-out"></i>
															<?php echo lang('GESTAO_DESPESA'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-money"></i>
															<?php echo lang('GESTAO_RECEITA'); ?>
														</span>
													</li>
													<li>
														<span class="iconify disabled menu-desabilitado">
															<i class="fa fa-usd"></i>
															<?php echo lang('GESTAO_FATURAMENTO'); ?>
														</span>
													</li>
												</ul>
											</li>
											<?php if ($core->modulo_ponto): ?>
												<li class="dropdown-submenu">
													<a href="javascript:;">
														<i class="fa fa-clock-o"></i>
														<?php echo lang('PONTO_TITULO'); ?>
													</a>
													<ul class="dropdown-menu">
														<li>
															<a href="index.php?do=ponto_eletronico&acao=horariolistar">
																<i class="fa fa-calendar"></i>
																<?php echo lang('PONTO_HORARIO'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=tabelalistar">
																<i class="fa fa-table"></i>
																<?php echo lang('PONTO_TABELA'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=feriadolistar">
																<i class="fa fa-sun-o"></i>
																<?php echo lang('PONTO_FERIADO'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=relatorioponto">
																<i class="fa fa-history"></i>
																<?php echo lang('PONTO_RELATORIO_TITULO'); ?>
															</a>
														</li>
													</ul>
												</li>
											<?php endif; ?>
										</ul>
									</li>
								<?php endif; ?>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('CONFIGURACOES'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<?php if ($usuario->is_Controller()): ?>
											<li class="dropdown-submenu">
												<a href="javascript:;">
													<i class="fa fa-repeat"></i>
													<?php echo lang('ATUALIZACOES'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="webservices/estoque.php" target="_blank" class="iconify">
															<i class="fa fa-tasks"></i>
															<?php echo lang('ATUALIZAR_ESTOQUE'); ?>
														</a>
													</li>
													<li>
														<a href="webservices/enderecos.php" target="_blank" class="iconify">
															<i class="fa fa-map-marker"></i>
															<?php echo lang('ATUALIZAR_MAPA'); ?>
														</a>
													</li>
												</ul>
											</li>
										<?php endif; ?>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-cogs"></i>
												<?php echo lang('CONFIGURACOES'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=empresa&acao=listar">
														<i class="fa fa-home"></i>
														<?php echo lang('EMPRESA_TITULO'); ?>
													</a>
												</li>
											</ul>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=origem" class="iconify">
												<i class="fa fa-clipboard"></i>
												<?php echo lang('ORIGEM'); ?>
											</a>
										</li>
										<?php if (false): ?>
											<?php if ($usuario->is_Administrativo()): ?>
												<li class="dropdown-submenu">
													<a href="javascript:;">
														<i class="fa fa-clock-o"></i>
														<?php echo lang('PONTO_TITULO'); ?>
													</a>
													<ul class="dropdown-menu">
														<li>
															<a href="index.php?do=ponto_eletronico&acao=horariolistar">
																<i class="fa fa-calendar"></i>
																<?php echo lang('PONTO_HORARIO'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=tabelalistar">
																<i class="fa fa-table"></i>
																<?php echo lang('PONTO_TABELA'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=feriadolistar">
																<i class="fa fa-sun-o"></i>
																<?php echo lang('PONTO_FERIADO'); ?>
															</a>
														</li>
														<li>
															<a href="index.php?do=ponto_eletronico&acao=relatorioponto">
																<i class="fa fa-history"></i>
																<?php echo lang('PONTO_RELATORIO_TITULO'); ?>
															</a>
														</li>
													</ul>
												</li>
											<?php endif; ?>
										<?php endif; ?>
										<?php if ($usuario->is_Gerencia()): ?>
											<li class="dropdown-submenu">
												<a href="index.php?do=usuario&acao=listar">
													<i class="fa fa-user"></i>
													<?php echo lang('USUARIO_TITULO'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=usuario&acao=adicionar" class="iconify">
															<i class="fa fa-plus-square"></i>
															<?php echo lang('USUARIO_ADICIONAR'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=usuario&acao=bloqueados" class="iconify">
															<i class="fa fa-ban"></i>
															<?php echo lang('USUARIO_BLOQUEADOS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=usuario&acao=listar" class="iconify">
															<i class="fa fa-list"></i>
															<?php echo lang('USUARIO_LISTAR'); ?>
														</a>
													</li>
												</ul>
											</li>
										<?php endif; ?>
										<?php if ($core->tipo_sistema == 4): ?>
											<li class="dropdown-submenu">
												<a href="index.php?do=usuario&acao=listar">
													<i class="fa fa-cog" aria-hidden="true"></i>
													<?php echo lang('SISTEMA'); ?>
												</a>
												<ul class="dropdown-menu">
													<li>
														<a href="index.php?do=taxas&acao=listar" class="iconify">
															<i class="fa fa-usd"></i>
															<?php echo lang('TAXAS'); ?>
														</a>
													</li>
													<li>
														<a href="index.php?do=bairros&acao=listar" class="iconify">
															<i class="fa fa-map-marker"></i>
															<?php echo lang('BAIRROS'); ?>
														</a>
													</li>
												</ul>
											</li>
										<?php endif; ?>
									</ul>
								</li>
							<?php elseif ($usuario->igual_Contador()): ?>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('FINANCEIRO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li>
											<a href="index.php?do=contador&acao=recebidas" class="iconify">
												<i class="fa fa-plus-square"></i>
												<?php echo lang('CONTAS_RECEBIDAS'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=contador&acao=despesaspagas" class="iconify">
												<i class="fa fa-minus-square"></i>
												<?php echo lang('FINANCEIRO_DESPESASPAGAS'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=boleto_sigesis&acao=listar" class="iconify">
												<i class="fa fa-file-text-o"></i>
												Boletos Sigesis
											</a>
										</li>
										<li>
											<a href="index.php?do=contador&acao=extrato" class="iconify">
												<i class="fa fa-sort-numeric-asc"></i>
												<?php echo lang('EXTRATO'); ?>
											</a>
										</li>
									</ul>
								</li>
								<?php if ($core->tipo_sistema == 2 || $core->tipo_sistema == 3): ?>
									<li class="menu-dropdown classic-menu-dropdown" style="color: #BDBDBD">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;" style="color: #BDBDBD">
											<?php echo lang('NOTA_FISCAL'); ?>
											<i class="fa fa-angle-down" style="color: #BDBDBD"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-list"></i>
													<?php echo lang('NOTA_LISTAR'); ?>
												</span>
											</li>
											<li>
												<span class="iconify disabled menu-desabilitado">
													<i class="fa fa-repeat"></i>
													<?php echo lang('NOTA_INVENTARIO'); ?>
												</span>
											</li>
										</ul>
									</li>
								<?php else: ?>
									<li class="menu-dropdown classic-menu-dropdown ">
										<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
											href="javascript:;">
											<?php echo lang('NOTA_FISCAL'); ?>
											<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu pull-left">
											<li>
												<a href="index.php?do=contador&acao=notafiscal" class="iconify">
													<i class="fa fa-list"></i>
													<?php echo lang('NOTA_LISTAR'); ?>
												</a>
											</li>
											<li>
												<a href="index.php?do=contador&acao=inventario" class="iconify">
													<i class="fa fa-repeat"></i>
													<?php echo lang('NOTA_INVENTARIO'); ?>
												</a>
											</li>
										</ul>
									</li>
								<?php endif; ?>
							<?php else: ?>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('CADASTRO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li>
											<a href="index.php?do=cadastro&acao=adicionar" class="iconify">
												<i class="fa fa-plus-square"></i>
												<?php echo lang('CADASTRO_ADICIONAR'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=listar" class="iconify">
												<i class="fa fa-list"></i>
												<?php echo lang('CADASTRO_LISTAR'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=clientes" class="iconify">
												<i class="fa fa-list"></i>
												<?php echo lang('CADASTRO_CLIENTES'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=fornecedores" class="iconify">
												<i class="fa fa-list"></i>
												<?php echo lang('CADASTRO_FORNECEDORES'); ?>
											</a>
										</li>
										<li>
											<a href="index.php?do=cadastro&acao=buscar" class="iconify">
												<i class="fa fa-search"></i>
												<?php echo lang('CADASTRO_BUSCAR'); ?>
											</a>
										</li>
									</ul>
								</li>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('VENDAS_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li>
											<a href="index.php?do=vendas&acao=novavenda" class="iconify">
												<i class="fa fa-barcode"></i>
												<?php echo lang('VENDAS_NOVA'); ?>
											</a>
										</li>
										<!--
										<li>
											<a href="index.php?do=ecomd" class="iconify">
												<i class="fa fa-barcode"></i>
												<?php echo lang('ECOMMERCE'); ?>
											</a>
										</li>
										-->
										<li>
											<a href="index.php?do=vendas_do_dia" class="iconify">
												<i class="fa fa-calendar-o"></i>
												<?php echo lang('VENDAS_DO_DIA'); ?>
											</a>
										</li>
										<?php if ($usuario->is_VendaAberto()): ?>
											<?php if ($core->tipo_sistema == 4): ?>
												<li>
													<a href="index.php?do=vendas&acao=vendaspedidosentrega" class="iconify">
														<i class="fa fa-exclamation-triangle"></i>
														<?php echo lang('VENDAS_ABERTO'); ?>
													</a>
												</li>
											<?php else: ?>
												<?php if ($core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
													<li>
														<a href="index.php?do=vendas_em_aberto" class="iconify">
															<i class="fa fa-exclamation-triangle"></i>
															<?php echo lang('VENDAS_ABERTO'); ?>
														</a>
													</li>
												<?php endif; ?>
											<?php endif; ?>
										<?php endif; ?>
										<?php if ($usuario->is_Orcamento()): ?>
											<?php if ($core->tipo_sistema != 1 && $core->tipo_sistema != 3): ?>
												<li>
													<a href="index.php?do=vendas&acao=vendasorcamento" class="iconify">
														<i class="fa fa-list"></i>
														<?php echo lang('ORCAMENTOS'); ?>
													</a>
												</li>
											<?php endif; ?>
										<?php endif; ?>
										<li class="dropdown-submenu">
											<a href="javascript:;">
												<i class="fa fa-inbox"></i>
												<?php echo lang('MENU_CAIXA'); ?>
											</a>
											<ul class="dropdown-menu">
												<li>
													<a href="index.php?do=caixa&acao=adicionar" class="iconify">
														<i class="fa fa-inbox"></i>
														<?php echo lang('CAIXA_ABRIR'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=listar" class="iconify">
														<i class="fa fa-calendar-o"></i>
														<?php echo lang('CAIXA_DIA'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=aberto" class="iconify">
														<i class="fa fa-exclamation-triangle"></i>
														<?php echo lang('CAIXA_EMABERTO'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=caixa&acao=listarretiradas" class="iconify">
														<i class="fa fa-minus-square"></i>
														<?php echo lang('CAIXA_LISTARRETIRADAS'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=produto&acao=trocarproduto" class="iconify">
														<i class="fa fa-exchange"></i>
														<?php echo lang('PRODUTO_TROCA_FISCAL'); ?>
													</a>
												</li>
												<li>
													<a href="index.php?do=produto&acao=trocarprodutoavulso" class="iconify">
														<i class="fa fa-exchange"></i>
														<?php echo lang('PRODUTO_TROCA_AVULSO'); ?>
													</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
								<li class="menu-dropdown classic-menu-dropdown ">
									<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
										href="javascript:;">
										<?php echo lang('TABELA_PRECO_TITULO'); ?>
										<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-left">
										<?php
										$retorno_row = $produto->getTabelaPrecos();
										if ($retorno_row):
											foreach ($retorno_row as $exrow): ?>
												<li>
													<a href="index.php?do=tabela&acao=tabela&id=<?php echo $exrow->id; ?>"
														class="iconify">
														<i class="fa fa-angle-right"></i>
														<?php echo $exrow->tabela; ?>
													</a>
												</li>
										<?php endforeach;
											unset($exrow);
										endif; ?>
										<li>
											<a href="index.php?do=tabela&acao=consultaprecos" class="iconify">
												<i class="fa fa-search"></i>
												<?php echo lang('TABELA_PRECO_CONSULTAR'); ?>
											</a>
										</li>
									</ul>
								</li>
							<?php endif; ?>
							<li class="menu-dropdown classic-menu-dropdown ">
								<a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown"
									href="javascript:;">
									<?php echo lang('AJUDA'); ?>
									<i class="fa fa-angle-down"></i>
								</a>
								<ul class="dropdown-menu pull-left">
									<li>
										<a href="#" class="iconify" onclick="window.location.reload(true)">
											<i class="fa fa-repeat"></i>
											<?php echo lang('RECARREGAR'); ?>
										</a>
									</li>
									<li>
										<a href="https://sigesistema.com.br/suporte-sige.exe" target="_blank"
											class="iconify">
											<i class="fa fa-desktop"></i>
											<?php echo lang('ACESSO_REMOTO'); ?>
										</a>
									</li>
									<li>
										<a href="https://centraldeajuda.sigesistema.com.br" target="_blank"
											class="dropdown-toggle">
											<img src="https://centraldeajuda.sigesistema.com.br/img/claudio.png"
												width="20px">
											<?php echo lang('CENTRAL_AJUDA'); ?>
										</a>
									</li>
									<li>
										<a href="https://api.whatsapp.com/send?phone=553138291980&text=Ol%C3%A1%2c%20eu%20gostaria%20suporte%20para%20o%20sistema%20de%20delivery.&source=&data="
											target="_blank" class="iconify">
											<i class="fa fa-comment"></i>
											<?php echo lang('WHATSAPP'); ?>
										</a>
									</li>
									<li>
										<a href="http://www.sigesistema.com.br/contato/" target="_blank" class="iconify">
											<i class="fa fa-phone"></i>
											<?php echo lang('CONTATO'); ?>
										</a>
									</li>
									<li>
										<a href="index.php?do=atualizacao&acao=listar" class="iconify">
											<i class="fa fa-gift"></i>
											<?php echo lang('ATUALIZACOES'); ?>
										</a>
									</li>
									<li>
										<a href="https://cdn.webchat.sz.chat/?cid=6086eea7e0c51d2d1e556013&host=https://valetelecom.conecta.com.vc"
											target="_blank" class="iconify">
											<i class="fa fa-phone-square" aria-hidden="true"></i>
											<?php echo lang('SUPORTE'); ?>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
					<!-- END MEGA MENU -->
				</div>
			</div>
		<?php endif; ?>
		<!-- END HEADER MENU -->
	</div>
	<!-- END HEADER -->