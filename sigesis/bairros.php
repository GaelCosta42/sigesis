<?php
  /**
   * Bairro
   *
   * @package SIGESIS
   * @copyright 2022
   * @version 1
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Gerencia())
	  redirect_to("login.php");
?>
<?php switch(Filter::$acao): case "editar": ?>
<?php $row = Core::getRowById("bairros", Filter::$id);?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('BAIRROS');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EDITAR');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW FORMULARIO -->
			<div class="row">
				<div class="col-md-12">	
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class='fa fa-edit font-<?php echo $core->primeira_cor;?>'>&nbsp;&nbsp;</i><?php echo lang('EDITAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form autocomplete="off" action="" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BAIRRO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="bairro" value="<?php echo $row->bairro;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CIDADE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="cidade" value="<?php echo $row->cidade;?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">						
											</div>
											<!--/col-md-6-->
										</div>
									</div>
								</div>
								<input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
														<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
											<div class="col-md-6">
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm("processarBairro");?>	
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
<?php break;?>
<?php case "adicionar": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('BAIRROS');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ADICIONAR');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW FORMULARIO -->
			<div class="row">
				<div class="col-md-12">	
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class='fa fa-plus-square font-<?php echo $core->primeira_cor;?>'>&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?>
							</div>
						</div>	
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form autocomplete="off" action="" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BAIRRO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="bairro">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CIDADE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="cidade">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">						
											</div>
											<!--/col-md-6-->
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
														<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
											<div class="col-md-6">
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm("processarBairro");?>	
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
<?php break;?>
<?php case "listar": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('BAIRROS');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('LISTAR');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->		
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class='fa fa-map-marker font-<?php echo $core->primeira_cor;?>'>&nbsp;&nbsp;</i><?php echo lang('LISTAR');?>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=bairros&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th width="200"><?php echo lang('BAIRRO');?></th>
										<th width="200"><?php echo lang('CIDADE');?></th>
										<th width="50"><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $produto->getBairros();
										if($retorno_row):
										foreach ($retorno_row as $exrow):
								?>
									<tr>
										<td><?php echo $exrow->bairro;?></td>
										<td><?php echo $exrow->cidade;?></td>
										<td>
											<a 
												href="index.php?do=bairros&acao=editar&id=<?php echo $exrow->id;?>" 
												class="btn btn-xs blue" 
												title="<?php echo lang('EDITAR').': '.$exrow->bairro;?>">
												<i class="fa fa-pencil">&nbsp;&nbsp;</i>
												<?php echo lang('EDITAR');?>
											</a>
											
											<a 
												href="javascript:void(0);" 
												class="btn btn-xs red apagar" 
												id="<?php echo $exrow->id;?>" 
												acao="apagarBairro" 
												title="<?php echo lang('APAGAR').': '.$exrow->bairro;?>">
												<i class="fa fa-times">&nbsp;&nbsp;</i>
												<?php echo lang('APAGAR');?>
											</a>

										</td>
									</tr>
								<?php endforeach;?>
								<?php unset($exrow);
									  endif;?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php default: ?>
<div class="imagem-fundo">
	<img src="assets/img/logo.png">
</div>
<?php break;?>
<?php endswitch;?>