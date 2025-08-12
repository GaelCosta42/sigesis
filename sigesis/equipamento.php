<?php
  /**
   * Equipamento
   *
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Todos())
	  redirect_to("login.php");
?>
<?php switch(Filter::$acao): case "editar": ?>
<?php 
	$row = Core::getRowById("equipamento", Filter::$id);
	$cliente = getValue("nome","cadastro","id=".$row->id_cliente);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('EQUIPAMENTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EQUIPAMENTO_EDITAR');?></small></h1>
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
					<div class="portlet box <?php echo $core->primeira_cor;?>">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('EQUIPAMENTO_EDITAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CLIENTE');?></label>
													<div class="col-md-6">
														<input readonly type="text" class="form-control input-xlarge caps" name="cliente" value="<?php echo $cliente; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('EQUIPAMENTO');?></label>
													<div class="col-md-6">
														<input type="text" class="form-control input-xlarge caps" name="equipamento" value="<?php echo $row->equipamento; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('REFERENCIA');?></label>
													<div class="col-md-6">
														<input type="text" class="form-control input-xlarge caps" name="codigo_referencia" value="<?php echo $row->codigo_referencia; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('ETIQUETA');?></label>
													<div class="col-md-6">
														<input type="text" class="form-control input-xlarge caps" name="etiqueta" value="<?php echo $row->etiqueta; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CATEGORIA');?></label>
													<div class="col-md-9">
														<select class="select2me form-control input-xlarge" name="id_categoria" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
															<option value=""></option>
															<?php 
																$retorno_row = $categoria->getCategorias();
																if ($retorno_row):
																	foreach ($retorno_row as $srow):
															?>
																		<option value="<?php echo $srow->id;?>" <?php if($srow->id == $row->id_categoria) echo 'selected="selected"';?>><?php echo $srow->categoria;?></option>
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
									</div>
								</div>
								<input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
								<input name="id_cadastro" type="hidden" value="<?php echo $row->id_cliente;?>" />
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-6">
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
							<?php echo $core->doForm("processarEquipamento");?>	
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
				<h1><?php echo lang('EQUIPAMENTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EQUIPAMENTO_ADICIONAR');?></small></h1>
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
					<div class="portlet box <?php echo $core->primeira_cor;?>">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('EQUIPAMENTO_ADICIONAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CLIENTE');?></label>
													<div class="col-md-6">
														<select class="select2me form-control input-xlarge" name="id_cadastro" id="id_cadastro" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
															<option value=""></option>
															<?php 
																$retorno_row = $cadastro->getCadastros('CLIENTE');
																if ($retorno_row):
																	foreach ($retorno_row as $srow):
															?>
																		<option value="<?php echo $srow->id;?>"><?php echo $srow->nome;?></option>
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
													<label class="control-label col-md-3"><?php echo lang('EQUIPAMENTO');?></label>
													<div class="col-md-6">
														<input type="text" class="form-control input-xlarge caps" name="equipamento">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('REFERENCIA');?></label>
													<div class="col-md-6">
														<input type="text" class="form-control input-xlarge caps" name="codigo_referencia">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('ETIQUETA');?></label>
													<div class="col-md-6">
														<input type="text" class="form-control input-xlarge caps" name="etiqueta">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CATEGORIA');?></label>
													<div class="col-md-9">
														<select class="select2me form-control input-xlarge" name="id_categoria" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
															<option value=""></option>
															<?php 
																$retorno_row = $categoria->getCategorias();
																if ($retorno_row):
																	foreach ($retorno_row as $srow):
															?>
																		<option value="<?php echo $srow->id;?>"><?php echo $srow->categoria;?></option>
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
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-6">
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
							<?php echo $core->doForm("processarEquipamento");?>	
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
				<h1><?php echo lang('EQUIPAMENTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EQUIPAMENTO_LISTAR');?></small></h1>
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
								<i class="fa fa-list font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('EQUIPAMENTO_LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=equipamento&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						
						<div class="portlet-body form">
						<!-- INICIO FORM -->
							<form action="index.php?do=equipamento&acao=listar" method="post" class="form-horizontal">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CLIENTE');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_cadastro" id="id_cadastro" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value=""></option>
																<?php 
																	$retorno_row = $cadastro->getCadastros('CLIENTE');
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value="<?php echo $srow->id;?>"><?php echo $srow->nome;?></option>
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
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('BUSCAR_EQUIPAMENTOS');?></button>
													</div>
												</div>
											</div>
											<div class="col-md-6">
											</div>
										</div>
									</div>
								</div>
							</form>
						<!-- FINAL FORM -->
						</div>
						
						<div class="portlet-body form">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable">
								<thead>
									<tr>
										<th><?php echo lang('EQUIPAMENTO');?></th>
										<th><?php echo lang('REFERENCIA');?></th>
										<th><?php echo lang('ETIQUETA');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('CATEGORIA');?></th>
                                        <th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $ordem_servico->getEquipamentosListagem();
										if($retorno_row):
										foreach ($retorno_row as $exrow):?>
									<tr>
										<td><a href="index.php?do=equipamento&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->equipamento;?></a></td>
										<td><?php echo $exrow->codigo_referencia;?></td>
										<td><?php echo $exrow->etiqueta;?></td>
										<td><?php echo $exrow->cliente;?></td>
										<td><?php echo $exrow->categoria;?></td>
										<td>
											<a href="index.php?do=equipamento&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-xs blue" title="<?php echo lang('EDITAR').': '.$exrow->equipamento;?>"><i class="fa fa-pencil">&nbsp;&nbsp;</i><?php echo lang('EDITAR');?></a>
											<a href="javascript:void(0);" class="btn btn-xs red apagar" id="<?php echo $exrow->id;?>" acao="apagarEquipamento" title="<?php echo lang('EQUIPAMENTO_APAGAR').$exrow->equipamento;?>"><i class="fa fa-times">&nbsp;&nbsp;</i><?php echo lang('APAGAR');?></a>
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