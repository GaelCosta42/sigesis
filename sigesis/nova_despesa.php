<?php
   /**
   * Nova despesa
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  define("_VALID_PHP", true);
  
	require_once("init.php");
	if (!$usuario->is_Administrativo())
	  redirect_to("login.php");
  
	$data = get('data');
	$valor = get('valor');
	$descricao = get('descricao');
	$id_banco = get('id_banco');
	$row = $despesa->getDespesaExtrato($descricao, $id_banco);
	$id_pai = ($row) ? getValue("id_pai", "conta", "id = ".$row->id_conta) : 0;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8"/>
<title><?php echo $core->empresa;?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="Sigesis N1" name="description"/>
<meta content="Vale Telecom" name="author"/>

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
<link rel="shortcut icon" href="./assets/img/favicon.ico"/>
<script src="./assets/plugins/jquery.min.js" type="text/javascript"></script>
<script src="./assets/scripts/jquery.mask.js" type="text/javascript"></script>
<script src="./assets/scripts/jquery.maskMoney.js" type="text/javascript"></script>
<script src="./assets/scripts/shortcut.js" type="text/javascript"></script>
<!-- dataTables -->
<script type="text/javascript" src="./assets/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="./assets/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="./assets/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="./assets/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="./assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="./assets/scripts/jquery.dataTables.grouping.js"></script>
<script type="text/javascript" src="./assets/scripts/jquery.dataTables.columnFilter.min.js"></script>
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
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript" ></script>
<script src="./assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js" type="text/javascript" ></script>

<!-- END THEME STYLES -->
</head>
<body>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('FINANCEIRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_ADICIONAR');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW FORMULARIO -->
			<div class='row'>
				<div class='col-md-12'>		
					<div class='portlet box <?php echo $core->primeira_cor;?>'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-minus'>&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_ADICIONAR');?>
							</div>
						</div>
						<div class='portlet-body form'>
							<!-- INICIO FORM-->
							<form action='' autocomplete="off" method='post' class='form-horizontal' name='admin_form' id='admin_form'>
								<div class='form-body'>
									<div class='row'>
										<div class='col-md-12'>
											<!--col-md-6-->
											<div class='col-md-6'>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DESCRICAO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='descricao' value='<?php echo $descricao;?>' >
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMPRESA');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_empresa" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value=""></option>
																<?php 
																	$retorno_row = $empresa->getEmpresas();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value="<?php echo $srow->id;?>" <?php if($row and $srow->id == $row->id_empresa) echo 'selected="selected"';?>><?php echo $srow->nome;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('FORNECEDOR');?></label>
														<div class='col-md-9'>
															<input name="id_cadastro" id="id_cadastro" type="hidden" value="<?php echo ($row) ? $row->id_cadastro : 0;?>"/>
															<input type="text" autocomplete="off" class="form-control caps listar_cadastro" name="cadastro" placeholder="<?php echo lang('BUSCAR');?>" value="<?php echo ($row) ? getValue('nome', 'cadastro', 'id='.$row->id_cadastro) : '';?>">
														</div>
													</div>
												</div>
												<div class="row selecionado ocultar">
													<div class="form-group">
														<label class="control-label col-md-3"></label>
														<div class="col-md-9">
															<span class="label label-success label-sm"><?php echo lang('SELECIONADO');?></span>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('CATEGORIA');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_pai' id='id_pai_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getPai('"D"');
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($srow->id == $id_pai) echo 'selected="selected"';?>><?php echo $srow->conta;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('PLANO_CONTAS');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_conta' id='id_conta_despesa' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getFilho($id_pai);
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id_filho;?>' <?php if($row and $srow->id_filho == $row->id_conta) echo 'selected="selected"';?>><?php echo $srow->filho;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('BANCO');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getBancos();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($srow->id == $id_banco) echo 'selected="selected"';?>><?php echo $srow->banco;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class='col-md-6'>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('VALOR');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control moeda' name='valor' value='<?php echo $valor;?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('PAGAMENTO');?></label>
														<div class='col-md-9'>
															<select class='select2me form-control' name='tipo' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
																<option value=""></option>
																<?php 
																	$retorno_row = $faturamento->getTipoPagamento();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value='<?php echo $srow->id;?>' <?php if($row and $srow->id == $row->tipo) echo 'selected="selected"';?>><?php echo $srow->tipo;?></option>
																<?php
																		endforeach;
																		unset($srow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DATA_VENCIMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_vencimento' value='<?php echo $data;?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('DATA_PAGAMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control data calendario' name='data_pagamento' value='<?php echo $data;?>'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'><?php echo lang('NRO_DOCUMENTO');?></label>
														<div class='col-md-9'>
															<input type='text' class='form-control caps' name='nro_documento'>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'></label>
														<div class='col-md-9'>
															<div class='md-checkbox-list'>
																<div class='md-checkbox'>
																	<input type='checkbox' class='md-check' name='pago' id='pago' value='1' checked>
																	<label for='pago'>
																	<span></span>
																	<span class='check'></span>
																	<span class='box'></span>
																	<?php echo lang('FINANCEIRO_PAGA');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class='row'>
													<div class='form-group'>
														<label class='control-label col-md-3'></label>
														<div class='col-md-9'>
															<div class='md-checkbox-list'>
																<div class='md-checkbox'>
																	<input type='checkbox' class='md-check' name='cheque' id='cheque' value='1'>
																	<label for='cheque'>
																	<span></span>
																	<span class='check'></span>
																	<span class='box'></span>
																	<?php echo lang('FINANCEIRO_CHEQUE');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
									</div>
								</div>
								<input name="processarNovaDespesas" type="hidden" value="1" />
								<div class='form-actions'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='col-md-6'>
												<div class='row'>
													<div class='col-md-offset-3 col-md-9'>
														<button type='button' id='salvardespesa' class='btn <?php echo $core->primeira_cor;?>'><?php echo lang('SALVAR');?></button>
														<button type='button' id='fechar' class='btn default'><?php echo lang('FECHAR');?></button>
													</div>
												</div>
											</div>
											<div class='col-md-6'>
											</div>
										</div>
									</div>
								</div>
							</form>
							<!-- FINAL FORM-->
						</div>
					</div>
				</div>
			</div>
			<!-- FINAL DO ROW FORMULARIO -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<script src="./assets/scripts/metronic.js" type="text/javascript"></script>
<script src="./assets/scripts/layout.js" type="text/javascript"></script><script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
});
</script>
</body>
</html>