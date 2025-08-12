<?php
  	/**
   	* Taxas
   	*
	* @package SIGESIS
  	* @copyright 2022
   	* @version 1
	*
   	*/
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Gerencia())
	  redirect_to("login.php");
?>
<?php switch(Filter::$acao): case "editar": ?>
<?php $row = Core::getRowById("taxas", Filter::$id);?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('TAXAS');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EDITAR');?></small></h1>
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
										<!--col-md-12-->
										<div class="col-md-12">
											<div class='row'>
												<div class='form-group'>
													<label class='control-label col-md-2'><?php echo lang('BAIRRO');?></label>
													<div class='col-md-3'>
														<select class='select2me form-control' name='id_bairro' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
															<option value=""></option>
															<?php 
																$retorno_row = $produto->getBairros();
																if ($retorno_row):
																	foreach ($retorno_row as $srow):
															?>
																		<option value='<?php echo $srow->id;?>' <?php if($srow->id == $row->id_bairro) echo 'selected="selected"';?>><?php echo $srow->bairro.' / '.$srow->cidade;?></option>
															<?php
																	endforeach;
																	unset($srow);
																	endif;
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-2"><?php echo lang('VALOR');?></label>
													<div class="col-md-3">
														<input type="text" class="form-control moeda" name="valor_taxa" value="<?php echo moeda($row->valor_taxa);?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-2"><?php echo lang('TEMPO_APROXIMADO');?></label>
													<div class="col-md-3">
														<input type="text" class="form-control hora" name="tempo_aproximado" value="<?php echo $row->tempo_aproximado;?>">
													</div>
												</div>
											</div>
										</div>
										<!--/col-md-12-->
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
							<?php echo $core->doForm("processarTaxa");?>	
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
				<h1><?php echo lang('TAXAS');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ADICIONAR');?></small></h1>
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
										<!--col-md-12-->
										<div class="col-md-12">
											<div class='row'>
												<div class='form-group'>
													<label class='control-label col-md-2'><?php echo lang('BAIRRO');?></label>
													<div class='col-md-3'>
														<select class='select2me form-control' name='id_bairro' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
															<option value=""></option>
															<?php 
																$retorno_row = $produto->getBairros();
																if ($retorno_row):
																	foreach ($retorno_row as $srow):
															?>
																		<option value='<?php echo $srow->id;?>'><?php echo $srow->bairro.' / '.$srow->cidade;?></option>
															<?php
																	endforeach;
																	unset($srow);
																	endif;
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-2"><?php echo lang('VALOR');?></label>
													<div class="col-md-3">
														<input type="text" class="form-control moeda" name="valor_taxa" placeholder="R$ 0,00">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-2"><?php echo lang('TEMPO_APROXIMADO');?></label>
													<div class="col-md-3">
														<input type="text" class="form-control hora" name="tempo_aproximado" placeholder="hh:mm">
                                                        <span class="help-block">Exemplo: 00:30 ou 01:35</span>
													</div>
												</div>
											</div>
										</div>
										<!--/col-md-12-->
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
							<?php echo $core->doForm("processarTaxa");?>	
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
				<h1><?php echo lang('TAXAS');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('LISTAR');?></small></h1>
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
								<i class='fa fa-usd font-<?php echo $core->primeira_cor;?>'>&nbsp;&nbsp;</i><?php echo lang('LISTAR');?>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=taxas&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th width="100"><?php echo lang('BAIRRO');?></th>
										<th width="50"><?php echo lang('VALOR');?></th>
										<th width="50"><?php echo lang('TEMPO_APROXIMADO');?></th>
										<th width="50"><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $produto->getTaxas();
										if($retorno_row):
										foreach ($retorno_row as $exrow):
								?>
									<tr>
										<td><?php echo $exrow->bairro.' / '.$exrow->cidade;?></td>
										<td><?php echo moeda($exrow->valor_taxa);?></td>
										<td><?php echo date('H:i',strtotime($exrow->tempo_aproximado));?></td>
										<td>
											<a 
												href="index.php?do=taxas&acao=editar&id=<?php echo $exrow->id;?>" 
												class="btn btn-xs blue" title="<?php echo lang('EDITAR').': '.$exrow->bairro;?>">
												<i class="fa fa-pencil">&nbsp;&nbsp;</i>
												<?php echo lang('EDITAR');?>
											</a>
											<a 
												href="javascript:void(0);" 
												class="btn btn-xs red apagar" 
												id="<?php echo $exrow->id;?>" 
												acao="apagarTaxa" 
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
	<img src="assets/img/logo.png" border="0">
</div>
<?php break;?>
<?php endswitch;?>