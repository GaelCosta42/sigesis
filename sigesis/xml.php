<?php
  /**
   * Extrato
   *
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Administrativo())
	  redirect_to("login.php");
  if ($core->tipo_sistema==2 || $core->tipo_sistema==3)
	  redirect_to("login.php");
?>
<script src="./assets/scripts/highcharts.js" type="text/javascript"></script>
<?php switch(Filter::$acao): case "saidas": 
	$id = (get('id')) ? get('id') : 0;
?>
	<!-- Plupload -->
	<link rel="stylesheet" type="text/css" href="./assets/plugins/plupload/css/jquery.plupload.queue.css"/>

	<script type="text/javascript" src="./assets/plugins/plupload/plupload.full.js"></script>
	<script type="text/javascript" src="./assets/plugins/plupload/jquery.plupload.queue.js"></script>
	<script type="text/javascript" src="./assets/plugins/plupload/i18n/pt_BR.js"></script>
	<script type="text/javascript" src="./assets/scripts/fileupload.js"></script>
	<script>
		jQuery(document).ready(function() {
			FormFileUpload.init();
		});
	</script>
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	<div class="page-head">
		<div class="container">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1><?php echo lang('NOTA_FISCAL');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ARQUIVOS_XML_EMITIDAS');?></small></h1>
			</div>
			<!-- END PAGE TITLE -->
		</div>
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="portlet light">
				<form action="" autocomplete="off" class="form-inline" method="post" id="admin_form" name="admin_form">
				
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-success">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('ARQUIVOS_XML_EMITIDAS');?></h3>
									</div>
									<div class="panel-body">
										<?php 
											if ($id) {
												echo lang('ARQUIVOS_XML_DESCRICAO_EMITIDAS_OS').$id;
											} else {
												echo lang('ARQUIVOS_XML_DESCRICAO_EMITIDAS');
											}
										?>
									</div>
								</div>
								<div class="plupload"></div>
								<input name="processarNFeSaida" type="hidden" value="1" />
								<input name="OrdemServico" type="hidden" value="<?php echo $id; ?>" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->
<?php break;?>
<?php case "entradas": ?>
	<!-- Plupload -->
	<link rel="stylesheet" type="text/css" href="./assets/plugins/plupload/css/jquery.plupload.queue.css"/>

	<script type="text/javascript" src="./assets/plugins/plupload/plupload.full.js"></script>
	<script type="text/javascript" src="./assets/plugins/plupload/jquery.plupload.queue.js"></script>
	<script type="text/javascript" src="./assets/plugins/plupload/i18n/pt_BR.js"></script>
	<script type="text/javascript" src="./assets/scripts/fileupload.js"></script>
	<script>
		jQuery(document).ready(function() {
			FormFileUpload.init();
		});
	</script>
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	<div class="page-head">
		<div class="container">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1><?php echo lang('NOTA_FISCAL');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ARQUIVOS_XML_RECEBIDAS');?></small></h1>
			</div>
			<!-- END PAGE TITLE -->
		</div>
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="portlet light">
				<form action="" autocomplete="off" class="form-inline" method="post" id="admin_form" name="admin_form">
				
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('ARQUIVOS_XML_RECEBIDAS');?></h3>
									</div>
									<div class="panel-body">
										<?php echo lang('ARQUIVOS_XML_DESCRICAO_RECEBIDAS');?>
									</div>
								</div>
								<div class="plupload"></div>
								<input name="processarNFeEntrada" type="hidden" value="1" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->
<?php break;?>
<?php case "sintegra": ?>
	<!-- Plupload -->
	<link rel="stylesheet" type="text/css" href="./assets/plugins/plupload/css/jquery.plupload.queue.css"/>

	<script type="text/javascript" src="./assets/plugins/plupload/plupload.full.js"></script>
	<script type="text/javascript" src="./assets/plugins/plupload/jquery.plupload.queue.js"></script>
	<script type="text/javascript" src="./assets/plugins/plupload/i18n/pt_BR.js"></script>
	<script type="text/javascript" src="./assets/scripts/fileupload.js"></script>
	<script>
		jQuery(document).ready(function() {
			FormFileUpload.init();
		});
	</script>
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	<div class="page-head">
		<div class="container">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1><?php echo lang('NOTA_FISCAL');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ARQUIVOS_SINTEGRA');?></small></h1>
			</div>
			<!-- END PAGE TITLE -->
		</div>
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="portlet light">
				<form action="" class="form-inline" method="post" id="admin_form" name="admin_form">
				
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-file-text-o">&nbsp;&nbsp;</i><?php echo lang('ARQUIVOS_SINTEGRA');?></h3>
									</div>
									<div class="panel-body">
										<?php echo lang('ARQUIVOS_SINTEGRA_DESCRICAO');?>
									</div>
								</div>
								<div class="plupload"></div>
								<input name="processarSintegra" type="hidden" value="1" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->
<?php break;?>
<?php default: ?>
<div class="imagem-fundo">
	<img src="assets/img/logo.png" border="0">
</div>
<?php break;?>
<?php endswitch;?>