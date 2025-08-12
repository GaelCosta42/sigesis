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
	$valida = $cotacao->validaCotacao($id);
	if(!$valida)
		header("Location: ./_aviso");
	
	$row = Core::getRowById("cotacao", $id);	
	$co = get('co'); 
	$cod_fornecedor = getValue("cod_fornecedor", "fornecedor", "codigo='".$co."'");
	$nome_fornecedor = getValue("nome", "fornecedor", "codigo='".$co."'");
	$data_entrega = $cotacao->getPrazoEntrega($id, $cod_fornecedor);
	
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
		
		<title><?php echo $core->empresa;?></title>
				
		<!-- Favicons -->
		<link rel="shortcut icon" href="../assets/img/favicon.png">
		<link rel="apple-touch-icon" href="../assets/img/favicon_60x60.png">
		<link rel="apple-touch-icon" sizes="76x76" href="../assets/img/favicon_76x76.png">
		<link rel="apple-touch-icon" sizes="120x120" href="../assets/img/favicon_120x120.png">
		<link rel="apple-touch-icon" sizes="152x152" href="../assets/img/favicon_152x152.png">
		
		<!-- Custom CSS -->
		<link href="dist/css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="vendors/bower_components/bootstrap-datepicker/css/datepicker3.css"/>
	</head>
	<body>		
		<div class="wrapper">
		
			<!-- Top Menu Items -->
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="mobile-only-brand pull-left">
					<div class="nav-header pull-left">
						<div class="logo-wrap">
							<a href="javascript:void(0);">
								<span class="brand-text"><img class="brand-img" src="dist/img/logo-mobile.png" alt=""/></span>
							</a>
						</div>
					</div>
					<div class="top-nav-search collapse pull-left">
						<div class="logo-wrap">
							<a href="javascript:void(0);">
								<span><img src="dist/img/logo.png" alt=""/></span>
							</a>
						</div>
					</div>
				</div>
			</nav>
			<!-- Main Content -->
			<div class="page-wrapper">
				<div class="container-fluid">
					
					<!-- Row -->
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default card-view">
								<div class="panel-wrapper">
									<div class="panel-body">
										<div class="row">
											<div class="col-md-12">
												<div class="form-wrap">
													<form action="" method="post" class="form-horizontal" name="admin_form" id="admin_form" role="form">
														<div class="form-body">
															<h5 class="txt-dark capitalize-font"><?php echo lang('COTACAO').": ".$id;?></h5>
															<h5 class="txt-dark capitalize-font"><?php echo lang('FORNECEDOR').": ".$nome_fornecedor;?></h5>
															<hr class="light-grey-hr"/>
															<h5 class="txt-dark"><?php echo $row->observacao;?></h5>
															<hr class="light-grey-hr"/>
															<div class="row">
																<div class="col-md-5">
																	<div class="form-group">
																		<label class="control-label col-md-3"><?php echo lang('DATA_ENTREGA');?></label>
																		<div class="col-md-7">
																			<input type="text" class="form-control data calendario" name="data_entrega" value="<?php echo exibedata($data_entrega);?>">
																		</div>
																	</div>
																</div>
															</div>
															<hr class="light-grey-hr"/>
													<?php 	
														$vl_total = 0;								
														$retorno_row = $cotacao->getCotacaoItens($id, $cod_fornecedor);
														if($retorno_row):
															foreach ($retorno_row as $exrow):
																$vl_total += $total = $exrow->quantidade_cotacao*$exrow->valor_unitario;
																	
													?>
															<input name="id_item[]" type="hidden" value="<?php echo $exrow->id;?>" />
															<div class="row">
																<div class="col-md-2">
																	<div class="form-group">
																		<div class="col-md-12">
																			<p class="form-control-static"><strong><?php echo $exrow->produto;?></strong></p>
																		</div>
																	</div>
																</div>
																<!--/span-->
																<div class="col-md-2">
																	<div class="form-group">
																		<div class="col-md-12">
																			<p class="form-control-static"><?php echo $exrow->codigo_barras;?></p>
																		</div>
																	</div>
																</div>
																<!--/span-->
																<div class="col-md-2">
																	<div class="form-group">
																		<div class="col-md-12">
																			<p class="form-control-static"><?php echo decimal($exrow->quantidade_cotacao)." ".$exrow->unidade_compra;?></p>
																		</div>
																	</div>
																</div>
																<!--/span-->
																<div class="col-md-2">
																	<div class="form-group">
																		<div class="col-md-12">
																			<input type="text" class="form-control moeda" name="valor[]" value="<?php echo moeda($exrow->valor_unitario);?>">
																		</div>
																	</div>
																</div>
																<!--/span-->
																<div class="col-md-2">
																	<div class="form-group">
																		<div class="col-md-12">
																			<p class="form-control-static"><?php echo "<strong>".lang('TOTAL').": </strong>".moeda($total);?></p>
																		</div>
																	</div>
																</div>
																<!--/span-->
															</div>
															<hr class="light-black-hr"/>
													<?php 	
															endforeach;
														unset($exrow);
														endif;
													?>
															<!-- /Row -->
															<div class="row">
																<div class="col-md-2">
																	<div class="form-group">
																		<div class="col-md-12">
																			<p class="form-control-static"><?php echo "<strong>".lang('VALOR_TOTAL').": </strong>".moeda($vl_total);?></p>
																		</div>
																	</div>
																</div>
																<!--/span-->
															</div>
															<input name="id_cotacao" type="hidden" value="<?php echo $id;?>" />
															<input name="cod_fornecedor" type="hidden" value="<?php echo $cod_fornecedor;?>" />
															<div class="form-actions">
																<button id="salvarcotacao" class="btn btn-info mr-10 pull-left"><span><?php echo lang('SALVAR');?></span></button>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /Row -->
				
				</div>
				<!-- Footer -->
				<footer class="footer container-fluid pl-30 pr-30">
					<div class="row">
						<div class="col-sm-12">
							<img src="https://n1.sige.pro/assets/img/sige.png" alt="">&bull; <?php echo date('Y');?> &bull; Desenvolvido por <a href="http://www.sigesis.com.br" target="_blank">SIGESIS - Sistemas.</a>
						</div>
					</div>
				</footer>
				<!-- /Footer -->
			
			</div>
			<!-- /Main Content -->
		
		</div>
		<!-- /#wrapper -->
		
		<!-- JavaScript -->
		
		<!-- jQuery -->
		<script src="vendors/bower_components/jquery/dist/jquery.min.js"></script>		
		<!-- Bootstrap Core JavaScript -->
		<script src="vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>	
		<script src="vendors/bower_components/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript" ></script>
		<script src="vendors/bower_components/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js" type="text/javascript" ></script>
		<script src="vendors/bower_components/bootstrap-growl/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
		<script src="dist/js/jquery.mask.js"></script>
		<script src="dist/js/jquery.maskMoney.js"></script>
		<!-- Init JavaScript -->
		<script src="dist/js/init.js"></script>
	</body>
</html>
