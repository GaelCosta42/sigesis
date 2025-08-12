<?php
  /**
   * OrdemServico
   *
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  
?>

<?php if ($core->tipo_sistema!=5) redirect_to("login.php"); ?>

<?php switch(Filter::$acao): case "editarorcamento": 
	if (!$usuario->is_Gerencia())
		redirect_to("login.php");
?>
<?php $row = Core::getRowById("ordem_servico", Filter::$id);?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('ORCAMENTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORCAMENTO_EDITAR');?></small></h1>
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
								<i class="fa fa-pencil">&nbsp;&nbsp;</i><?php echo lang('ORCAMENTO_EDITAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
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
																			<option value="<?php echo $srow->id;?>" <?php if($srow->id == $row->id_empresa) echo 'selected="selected"';?>><?php echo $srow->nome;?></option>
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
														<label class="control-label col-md-3"><?php echo lang('CLIENTE');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_cadastro_os" id="id_cadastro_os" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value=""></option>
																<?php 
																	$retorno_row = $cadastro->getCadastros('CLIENTE');
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value="<?php echo $srow->id;?>" <?php if($srow->id == $row->id_cadastro) echo 'selected="selected"';?>><?php echo $srow->nome;?></option>
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
														<label class="control-label col-md-3"><?php echo lang('RESPONSAVEL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="responsavel_cliente" value="<?php echo $row->responsavel; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="panel panel-default">	
														<div class="panel-heading">
															<?php echo lang('SELECIONE_EQUIPAMENTO_CLIENTE_OU_TEXTO'); ?>
														</div>
														<div class="panel-body">	
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"><?php echo lang('EQUIPAMENTO');?></label>
																	<div class="col-md-8">
																		<select class="select2me form-control" name="id_equipamento_os" id="id_equipamento_os" data-placeholder="<?php echo lang('SELECIONE_EQUIPAMENTO_CLIENTE');?>" >
																			<option value=""></option>
																			<?php 
																				$retorno_row = $ordem_servico->getEquipamentos($row->id_cadastro);
																				if ($retorno_row):
																					foreach ($retorno_row as $srow):
																			?>
																						<option value="<?php echo $srow->id;?>" <?php if($srow->id == $row->id_equipamento) echo 'selected="selected"';?>><?php echo $srow->equipamento.' ['.lang('ETIQUETA').': '.$srow->etiqueta.']'.' ['.$srow->codigo_referencia.']';?></option>
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
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-8">
																		<?php echo lang('OU'); ?>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"><?php echo lang('EQUIPAMENTO');?></label>
																	<div class="col-md-8">
																		<textarea rows="2" class="form-control caps" name="nome_equipamento" placeholder="<?php echo lang('SELECIONE_EQUIPAMENTO_CLIENTE_TEXTO');?>"><?php echo $row->equipamento_digitado; ?></textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!--col-md-6-->
											<div class="col-md-6">
												
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CRITICIDADE');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="criticidade" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value="1" <?php if($row->criticidade==1) echo 'selected="selected"';?>><?php echo lang('BAIXA_CRITICIDADE');?></option>
																<option value="2" <?php if($row->criticidade==2) echo 'selected="selected"';?>><?php echo lang('MEDIA_CRITICIDADE');?></option>
																<option value="3" <?php if($row->criticidade==3) echo 'selected="selected"';?>><?php echo lang('ALTA_CRITICIDADE');?></option>
															</select>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PRIORIDADE');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="prioridade" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value="1" <?php if($row->prioridade==1) echo 'selected="selected"';?>><?php echo lang('BAIXA');?></option>
																<option value="2" <?php if($row->prioridade==2) echo 'selected="selected"';?>><?php echo lang('MEDIA');?></option>
																<option value="3" <?php if($row->prioridade==3) echo 'selected="selected"';?>><?php echo lang('ALTA');?></option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('OBSERVACAO_EQUIPAMENTO');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea rows="3" class="form-control caps" name="descricao_equipamento"><?php echo $row->descricao_equipamento;?></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('DESCRICAO_PROBLEMA');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea rows="3" class="form-control caps" name="descricao_problema"><?php echo $row->descricao_problema;?></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>
									</div>
								</div>
								<input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
								<input name="id_tabela_os" type="hidden" value="<?php echo $row->id_tabela;?>" />
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-offset-3 col-md-19">
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
							<?php echo $core->doForm("processarOrdemServico");?>	
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
<?php case "visualizarorcamento":
	$row = Core::getRowById("ordem_servico", Filter::$id);
	$cliente = getValue("nome","cadastro","id=".$row->id_cadastro);
	$equipamento = ($row->equipamento_digitado) ? $row->equipamento_digitado : Core::getRowById("equipamento", $row->id_equipamento);
	$criticidade = ($row->criticidade==1) ? lang('BAIXA_CRITICIDADE') : (($row->criticidade==2) ? lang('MEDIA_CRITICIDADE') : lang('ALTA_CRITICIDADE'));
	$prioridade = ($row->prioridade==1) ? lang('BAIXA') : (($row->prioridade==2) ? lang('MEDIA') : lang('ALTA'));
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('ORCAMENTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORCAMENTO_VISUALIZAR');?></small></h1>
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
								<i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('ORCAMENTO_VISUALIZAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CLIENTE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="cliente" value="<?php echo $cliente; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EQUIPAMENTO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="equipamento" value="<?php echo ($row->equipamento_digitado) ? $row->equipamento_digitado : $equipamento->equipamento.' ['.$equipamento->codigo_referencia.']'; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ETIQUETA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="equipamento" value="<?php echo ($row->equipamento_digitado) ? $row->equipamento_digitado : $equipamento->etiqueta; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('RESPONSAVEL');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="responsavel_cliente" value="<?php echo $row->responsavel; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CRITICIDADE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="criticidade" value="<?php echo $criticidade; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PRIORIDADE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="prioridade" value="<?php echo $prioridade; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('OBSERVACAO_EQUIPAMENTO');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea readonly rows="3" class="form-control caps" name="descricao_equipamento"><?php echo $row->descricao_equipamento;?></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('DESCRICAO_PROBLEMA');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea readonly rows="3" class="form-control caps" name="descricao_problema"><?php echo $row->descricao_problema;?></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>										
									</div>
									
									<div class="row"><br><br></div>

									<?php 
										$produtos_orcamento = $ordem_servico->getItensOrdemServico(Filter::$id);
										if ($produtos_orcamento):  
											$item = 0;
									?>
											<div class="portlet-body">
												<table class="table table-bordered table-striped table-condensed table-advance">
													<thead>
														<tr>
															<th><?php echo lang('PRODUTO');?></th>
															<th><?php echo lang('DESCRICAO');?></th>
															<th><?php echo lang('QUANT');?></th>
															<th><?php echo lang('OPCOES');?></th>
														</tr>
													</thead>
													<tbody>
													<?php 											
															foreach ($produtos_orcamento as $exrow):
													?>
														<tr>
															<td><?php echo $exrow->produto;?></td>
															<td><?php echo $exrow->descricao;?></td>
															<td><?php echo decimal($exrow->quantidade);?></td>
															<td>
																	<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarItemOrdemServico" title="<?php echo lang('APAGAR').': '.$exrow->produto;?>"><i class="fa fa-times"></i></a>
															</td>
														</tr>
													<?php endforeach;?>
													<?php unset($exrow);?>
													</tbody>
												</table>
											</div>
											
									<?php endif;?>
									
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													
														<a href="#orcamento-itens-adicionar" data-toggle="modal" class="btn btn-success green" title="<?php echo lang('ORCAMENTO_PRODUTOS_ADICIONAR');?>"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;<?php echo lang('ORCAMENTO_PRODUTOS_ADICIONAR');?></a>
													
												</div>
											</div>
										</div>
									</div>

									<div class="row"><br><br></div>

									<div class="row">
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('ORCAMENTO_DESCRICAO');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea rows="3" class="form-control caps" name="descricao_orcamento"><?php echo $row->descricao_orcamento; ?></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('ORCAMENTO_TEMPO_SERVICO');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-6">
															<input type="time" class="form-control" name="tempo_servico" value="<?php echo ($row->tempo_servico && $row->tempo_servico!='00:00:00') ? $row->tempo_servico : ""; ?>">
														</div>
													</div>
												</div>	
											</div>
										</div>
									</div>
									
								</div>
								<input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-19">
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
														<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row text-right">
													<a href="javascript:void(0);" class="btn yellow-casablanca concluirOrcamento" id="<?php echo Filter::$id;?>" acao="concluirOrcamento" title="<?php echo lang('ORCAMENTO_CONCLUIR_DESCRICAO');?>"><?php echo lang('ORCAMENTO_CONCLUIR');?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm("processarOrcamento_OrdemServico");?>	
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

<div id="orcamento-itens-adicionar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-plus">&nbsp;&nbsp;</i><?php echo lang('ORDEM_SERVICO_PRODUTO');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="item_form" id="item_form" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">	
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('DESCRICAO');?></label>
								<div class="col-md-8">
									<input type="text" class="form-control caps" name="descricao">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('PRODUTO');?></label>
								<div class="col-md-8">
									<select class="select2me form-control" name="id_produto" data-placeholder="<?php echo lang('SELECIONE_PRODUTO');?>" >
										<option value=""></option>
										<?php 
											$retorno_row = $produto->getProdutos();
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
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('QUANTIDADE');?></label>
								<div class="col-md-8">
									<input type="text" class="form-control decimal" name="quantidade">
								</div>
							</div>
						</div>
					</div>
				</div>
				<input name="id_ordem" type="hidden" value="<?php echo $row->id;?>" />
				<input name="id_cadastro" type="hidden" value="<?php echo $row->id_cadastro;?>" />
				<input name="id_tabela" type="hidden" value="<?php echo $row->id_tabela;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit green"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarItemOrdemServico", "item_form");?>
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "adicionar": 
	if (!$usuario->is_Gerencia())
		redirect_to("login.php");
	
	$id_cadastro = (get('id_cliente')) ? get('id_cliente') : 0;
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('ORCAMENTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORCAMENTO_ADICIONAR');?></small></h1>
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
								<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ORCAMENTO_ADICIONAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
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
																			<option value="<?php echo $srow->id;?>" <?php if(count($retorno_row) == 1) echo 'selected="selected"';?>><?php echo $srow->nome;?></option>
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
														<label class="control-label col-md-3"><?php echo lang('CLIENTE');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_cadastro_os" id="id_cadastro_os" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value=""></option>
																<?php 
																	$retorno_row = $cadastro->getCadastros('CLIENTE');
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value="<?php echo $srow->id;?>" <?php if($srow->id == $id_cadastro) echo 'selected="selected"';?>><?php echo $srow->nome;?></option>
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
														<label class="control-label col-md-3"><?php echo lang('RESPONSAVEL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="responsavel_cliente">
														</div>
													</div>
												</div>											
												<div class="row">
													<div class="panel panel-default">	
														<div class="panel-heading">
															<?php echo lang('SELECIONE_EQUIPAMENTO_CLIENTE_OU_TEXTO'); ?>
														</div>
														<div class="panel-body">	
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"><?php echo lang('EQUIPAMENTO');?></label>
																	<div class="col-md-8">
																		<select class="select2me form-control" name="id_equipamento_os" id="id_equipamento_os" data-placeholder="<?php echo lang('SELECIONE_EQUIPAMENTO_CLIENTE');?>" >
																			<option value=""></option>
																		</select>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-8">
																		<?php echo lang('OU'); ?>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"><?php echo lang('EQUIPAMENTO');?></label>
																	<div class="col-md-8">
																		<textarea rows="2" class="form-control caps" name="nome_equipamento" placeholder="<?php echo lang('SELECIONE_EQUIPAMENTO_CLIENTE_TEXTO');?>"></textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												
											</div>
											<!--col-md-6-->
											<div class="col-md-6">
												
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CRITICIDADE');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="criticidade" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value="1"><?php echo lang('BAIXA_CRITICIDADE');?></option>
																<option value="2"><?php echo lang('MEDIA_CRITICIDADE');?></option>
																<option value="3"><?php echo lang('ALTA_CRITICIDADE');?></option>
															</select>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PRIORIDADE');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="prioridade" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value="1"><?php echo lang('BAIXA');?></option>
																<option value="2"><?php echo lang('MEDIA');?></option>
																<option value="3"><?php echo lang('ALTA');?></option>
															</select>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TABELA');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_tabela_os" id="id_tabela_os" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value=""></option>
																<?php 
																	$retorno_row = $produto->getTabelaPrecos();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																?>
																			<option value="<?php echo $srow->id;?>" <?php if(count($retorno_row) == 1) echo 'selected="selected"';?>><?php echo $srow->tabela;?></option>
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
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('OBSERVACAO_EQUIPAMENTO');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea rows="3" class="form-control caps" name="descricao_equipamento"></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('DESCRICAO_PROBLEMA');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea rows="3" class="form-control caps" name="descricao_problema"></textarea>
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
							<?php echo $core->doForm("processarOrdemServico");?>	
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
<?php case "gerenciarvalororcamento":
	$row = Core::getRowById("ordem_servico", Filter::$id);
	$cliente = getValue("nome","cadastro","id=".$row->id_cadastro);
	$equipamento = ($row->equipamento_digitado) ? $row->equipamento_digitado : Core::getRowById("equipamento", $row->id_equipamento);
	$criticidade = ($row->criticidade==1) ? lang('BAIXA_CRITICIDADE') : (($row->criticidade==2) ? lang('MEDIA_CRITICIDADE') : lang('ALTA_CRITICIDADE'));
	$prioridade = ($row->prioridade==1) ? lang('BAIXA') : (($row->prioridade==2) ? lang('MEDIA') : lang('ALTA'));
?>
<!-- INICIO CONTEUDO DA PAGINA -->

<div id="ordem_inserir_adicional" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-plus">&nbsp;&nbsp;</i><?php echo lang('ORCAMENTO_VALOR_ADICIONAL_TITULO');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="valor_adicional_form" id="valor_adicional_form" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">	
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('DESCRICAO');?></label>
								<div class="col-md-8">
									<textarea rows="3" class="form-control caps" name="descricao_adicional"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('VALOR');?></label>
								<div class="col-md-8">
									<input type="text" class="form-control moeda" name="valor_adicional">
								</div>
							</div>
						</div>
					</div>
				</div>
				<input name="id_ordem" type="hidden" value="<?php echo $row->id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit green"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarValorAdicionalOrdemServico", "valor_adicional_form");?>
</div>

<div id="ordem_produto_preco" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-dollar">&nbsp;&nbsp;</i><?php echo lang('ORCAMENTO_VALOR_PRODUTO_MUDAR');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="ordem_produto_preco_form" id="ordem_produto_preco_form" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('PRODUTO');?></label>
								<div class="col-md-8">
									<input readonly type="text" class="form-control" name="produto_atual_os" id="produto_atual_os">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('ORCAMENTO_VALOR_PRODUTO_NOVO');?></label>
								<div class="col-md-8">
									<input type="text" class="form-control moeda" name="novo_valor_produto_os" id="novo_valor_produto_os">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('ORCAMENTO_QTDE_PRODUTO_NOVO');?></label>
								<div class="col-md-8">
									<input type="text" class="form-control decimal" name="nova_qtde_produto_os" id="nova_qtde_produto_os">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('VALOR_TOTAL');?></label>
								<div class="col-md-8">
									<input readonly type="text" class="form-control" name="novo_total_produto_os" id="novo_total_produto_os">
								</div>
							</div>
						</div>
					</div>
				</div>
				<input name="id_ordem" id="id_ordem" type="hidden" value="<?php echo $row->id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit green"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarNovoValorProdutoOrdemServico", "ordem_produto_preco_form");?>
</div>

<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('ORCAMENTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORCAMENTO_VISUALIZAR');?></small></h1>
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
								<i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('ORCAMENTO_VISUALIZAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CLIENTE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="cliente" value="<?php echo $cliente; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EQUIPAMENTO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="equipamento" value="<?php echo ($row->equipamento_digitado) ? $row->equipamento_digitado : $equipamento->equipamento.' ['.$equipamento->codigo_referencia.']'; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ETIQUETA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="equipamento" value="<?php echo ($row->equipamento_digitado) ? $row->equipamento_digitado : $equipamento->etiqueta; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('RESPONSAVEL');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="responsavel_cliente" value="<?php echo $row->responsavel; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CRITICIDADE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="criticidade" value="<?php echo $criticidade; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PRIORIDADE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="prioridade" value="<?php echo $prioridade; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="col-md-12">
													<h4 class="form-section"><?php echo lang('OBSERVACAO_EQUIPAMENTO');?></h4>
													<div class="row">
														<div class="form-group">
															<div class="col-md-12">
																<textarea readonly rows="3" class="form-control caps" name="descricao_equipamento"><?php echo $row->descricao_equipamento;?></textarea>
															</div>
														</div>
													</div>	
												</div>
											</div>	
											<div class="col-md-6">
												<div class="col-md-12">
													<h4 class="form-section"><?php echo lang('DESCRICAO_PROBLEMA');?></h4>
													<div class="row">
														<div class="form-group">
															<div class="col-md-12">
																<textarea readonly rows="3" class="form-control caps" name="descricao_problema"><?php echo $row->descricao_problema;?></textarea>
															</div>
														</div>
													</div>	
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="col-md-12">
													<h4 class="form-section"><?php echo lang('ORCAMENTO_DESCRICAO');?></h4>
													<div class="row">
														<div class="form-group">
															<div class="col-md-12">
																<textarea readonly rows="3" class="form-control caps" name="descricao_orcamento"><?php echo $row->descricao_orcamento; ?></textarea>
															</div>
														</div>
													</div>	
												</div>
											</div>	
											<div class="col-md-6">
												<div class="col-md-12">
													<h4 class="form-section"><?php echo lang('ORCAMENTO_TEMPO_SERVICO');?></h4>
													<div class="row">
														<div class="form-group">
															<div class="col-md-6">
																<input readonly type="text" class="form-control hora" name="tempo_servico" value="<?php echo $row->tempo_servico; ?>">
															</div>
														</div>
													</div>	
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row"><br><br></div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<a href="#ordem_inserir_adicional" data-toggle="modal" class="btn btn-success green" title="<?php echo lang('ORCAMENTO_VALOR_ADICIONAL_TITULO');?>"><i class="fa fa-plus-square"></i>&nbsp;&nbsp;<?php echo lang('ORCAMENTO_VALOR_ADICIONAL_TITULO');?></a>
											</div>
										</div>	
									</div>
									
									<?php 
										$adicionais_os = $ordem_servico->getValoresAdicionaisOrdemServico(Filter::$id);
										if ($adicionais_os):  
											$valor_adicionais = 0;
									?>
											<h4 class="form-section"><?php echo lang('ORCAMENTO_VALOR_ADICIONAL_LISTA');?></h4>
											<div class="portlet-body">
												<table class="table table-bordered table-striped table-condensed table-advance">
													<thead>
														<tr>
															<th><?php echo lang('DESCRICAO');?></th>
															<th><?php echo lang('VALOR');?></th>
															<th><?php echo lang('OPCOES');?></th>
														</tr>
													</thead>
													<tbody>
													<?php 											
															foreach ($adicionais_os as $exrow):
															$valor_adicionais += $exrow->valor_adicional;
													?>
														<tr>
															<td><?php echo $exrow->descricao;?></td>
															<td><?php echo moeda($exrow->valor_adicional);?></td>
															<td><a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarAdicionalOS" title="<?php echo lang('APAGAR').': '.$exrow->descricao;?>"><i class="fa fa-times"></i></a></td>
														</tr>
													<?php endforeach;?>
													<?php unset($exrow);?>
													</tbody>
													<tfoot>
														<tr>
															<td><?php echo lang('ORCAMENTO_VALOR_ADICIONAL_TOTAL'); ?></td>
															<td colspan="2"><?php echo moeda($valor_adicionais); ?></td>
															
														</tr>
													</tfoot>
												</table>
											</div>
											
									<?php endif;?>
									
									<?php 
										$produtos_orcamento = $ordem_servico->getItensOrdemServico(Filter::$id);
										if ($produtos_orcamento):  
											$item = 0;
											$valor_produtos = 0;
									?>
											<h4 class="form-section"><?php echo lang('ORCAMENTO_PRODUTOS_LISTA');?></h4>
											<div class="portlet-body">
												<table class="table table-bordered table-striped table-condensed table-advance">
													<thead>
														<tr>
															<th><?php echo lang('PRODUTO');?></th>
															<th><?php echo lang('DESCRICAO');?></th>
															<th><?php echo lang('VALOR_UNITARIO');?></th>
															<th><?php echo lang('QUANT');?></th>
															<th><?php echo lang('VALOR_TOTAL');?></th>
															<th><?php echo lang('OPCOES');?></th>
														</tr>
													</thead>
													<tbody>
													<?php 											
															foreach ($produtos_orcamento as $exrow):
															$valor_produtos += $exrow->valor_total;
													?>
														<tr>
															<td><?php echo $exrow->produto;?></td>
															<td><?php echo $exrow->descricao;?></td>
															<td><?php echo moeda($exrow->valor);?></td>
															<td><?php echo decimal($exrow->quantidade);?></td>
															<td><?php echo moeda($exrow->valor_total);?></td>
															<td>
																<a href="javascript:void(0);" class="btn btn-sm green ordem_produto_preco" id="<?php echo $exrow->id;?>" nome="<?php echo $exrow->produto;?>" valor="<?php echo moeda($exrow->valor);?>" qtde="<?php echo decimal($exrow->quantidade);?>" total="<?php echo moeda($exrow->valor_total);?>" title="<?php echo lang('ORCAMENTO_VALOR_PRODUTO_MUDAR').': '.$exrow->produto;?>"><i class="fa fa-dollar"></i></a>
																<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarProdutoOS" title="<?php echo lang('APAGAR').': '.$exrow->produto;?>"><i class="fa fa-times"></i></a>
															</td>
														</tr>
													<?php endforeach;?>
													<?php unset($exrow);?>
													</tbody>
													<tfoot>
														<tr>
															<td colspan="4"><?php echo lang('VALOR_PRODUTOS'); ?></td>
															<td><?php echo moeda($valor_produtos); ?></td>
															<td></td>
														</tr>
													</tfoot>
												</table>
											</div>
											
									<?php endif;?>
									
									<div class="row">
										<div class="col-md-12">
											<div class="row"><br><br></div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('ORCAMENTO_VALOR');?></h4>
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label class="control-label col-md-12" style="text-align:center!important;"><?php echo lang('ORCAMENTO_VALOR_SERVICO');?></label>
															<div class="col-md-12">
																<input type="text" class="form-control moeda" id="valor_servico" name="valor_servico" value="<?php echo moeda($row->valor_servico); ?>">
															</div>
														</div>
													</div>
													<div class="col-md-1">
														<div class="form-group">
															<label class="control-label col-md-12" style="text-align:center!important;font-size:20px;"><?php echo '+'; ?></label>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label class="control-label col-md-12" style="text-align:center!important;"><?php echo lang('ORCAMENTO_VALOR_PRODUTO');?></label>
															<div class="col-md-12">
																<input readonly type="text" class="form-control" id="valor_produtos" name="valor_produtos" value="<?php echo moeda($row->valor_produto); ?>">
															</div>
														</div>
													</div>
													<div class="col-md-1">
														<div class="form-group">
															<label class="control-label col-md-12" style="text-align:center!important;font-size:20px;"><?php echo '+'; ?></label>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label class="control-label col-md-12" style="text-align:center!important;"><?php echo lang('ORCAMENTO_VALOR_ADICIONAL');?></label>
															<div class="col-md-12">
																<input readonly type="text" class="form-control" id="valor_adicional" name="valor_adicional" value="<?php echo moeda($row->valor_adicional); ?>">
															</div>
														</div>
													</div>
													<div class="col-md-1">
														<div class="form-group">
															<label class="control-label col-md-12" style="text-align:center!important;font-size:20px;"><?php echo '-'; ?></label>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label class="control-label col-md-12" style="text-align:center!important;"><?php echo lang('DESCONTO');?></label>
															<div class="col-md-12">
																<input type="text" class="form-control moeda" id="valor_desconto" name="valor_desconto" value="<?php echo moeda($row->valor_desconto); ?>">
															</div>
														</div>
													</div>
												</div>
												<div class="row">	
													<div class="col-md-12">
														<div class="form-group">
															<label class="control-label col-md-12" style="text-align:center!important;"><?php echo lang('TOTAL');?></label>
															<div class="col-md-12">
																<input type="text" readonly class="form-control" id="valor_total_servico" name="valor_total_servico" value="<?php echo moeda($row->valor_total); ?>">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row"><br><br></div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('ORCAMENTO_INFO_ADICIONAIS');?></h4>
											</div>
											<div class="col-md-12">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-2"><?php echo lang('ORCAMENTO_PRAZO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="prazo_entrega" value="<?php echo $row->prazo_entrega; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-2"><?php echo lang('ORCAMENTO_PAGAMENTO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="condicao_pagamento" value="<?php echo $row->condicao_pagamento; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-2"><?php echo lang('ORCAMENTO_GARANTIA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="garantia" value="<?php echo $row->garantia; ?>">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
								</div>
								<input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-19">
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
														<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm("processarValorOrcamento");?>	
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
				<h1><?php echo lang('ORDEM_SERVICO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORDEM_SERVICO_LISTAR');?></small></h1>
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
								<i class="fa fa-warning font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('ORDEM_SERVICO_LISTAR');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable-asc">
								<thead>
									<tr>
										<th>#</th>
										<th>OS</th>
										<th><?php echo lang('DATA_ABERTURA');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('CIDADE');?></th>
										<th><?php echo lang('EQUIPAMENTO');?></th>
										<th><?php echo lang('PROBLEMA');?></th>
										<th><?php echo lang('SOLUCAO');?></th>
										<th><?php echo lang('STATUS');?></th>
										<th><?php echo lang('RESPONSAVEL');?></th>
										<th width="130px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $ordem_servico->getOrdemServicoExecucao();
										if($retorno_row):
										foreach ($retorno_row as $exrow):
								?>
									<tr class="popovers" data-container="body" data-trigger="hover" data-placement="top" data-content="<?php echo $exrow->descricaoStatus?>" data-original-title="<?php echo lang('OBSERVACAO'); ?>">
										<td><?php echo $exrow->id;?></td>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo exibedata($exrow->data_abertura);?></td>
										<td><a href="index.php?do=cadastro&acao=receitas&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->nome;?></a></td>
										<td><?php echo $exrow->cidade;?></td>
										<td><?php echo ($exrow->descricao_equipamento) ? $exrow->descricao_equipamento : $exrow->equipamento;?></td>
										<td><?php echo $exrow->descricao_problema;?></td>
										<td><?php echo $exrow->descricao_orcamento;?></td>
										<td><?php echo $exrow->status;?></td>
										<td><?php echo strtoupper($exrow->responsavel);?></td>
										<td>	
											<?php if ($exrow->id_status==5 || $exrow->id_status==6): ?>
												<a href="index.php?do=ordem_servico&acao=executaratendimento&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('ORDEM_SERVICO_EXECUTAR').': '.$exrow->etiqueta;?>"><i class="fa fa-wrench"></i></a>
											<?php endif; ?>
											<?php if ($exrow->id_status == 6): ?>
												<a href="javascript:void(0);" class="btn btn-sm green finalizarServico" id="<?php echo $exrow->id;?>" acao="finalizarServico" title="<?php echo lang('ORDEM_SERVICO_FINALIZAR').': '.$exrow->etiqueta;?>"><i class="fa fa-check-square"></i></a>
											<?php endif; ?>
											<a href="imprimir_ordem_servico.php?id_orcamento=<?php echo $exrow->id; ?>" target="_blank" class="btn btn-sm yellow-casablanca" title="<?php echo lang('ORDEM_SERVICO_IMPRIMIR');?>" style="margin-top:4px;"><i class="fa fa-print"></i></a>
										</td>
									</tr>
								<?php 
									  endforeach;?>
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
<?php case "executaratendimento":
	$row = Core::getRowById("ordem_servico", Filter::$id);
	$cliente = getValue("nome","cadastro","id=".$row->id_cadastro);
	$equipamento = ($row->equipamento_digitado) ? $row->equipamento_digitado : Core::getRowById("equipamento", $row->id_equipamento);
	$criticidade = ($row->criticidade==1) ? lang('BAIXA_CRITICIDADE') : (($row->criticidade==2) ? lang('MEDIA_CRITICIDADE') : lang('ALTA_CRITICIDADE'));
	$prioridade = ($row->prioridade==1) ? lang('BAIXA') : (($row->prioridade==2) ? lang('MEDIA') : lang('ALTA'));
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('ORDEM_SERVICO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORDEM_SERVICO_EXECUTAR');?></small></h1>
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
								<i class="fa fa-wrench">&nbsp;&nbsp;</i><?php echo lang('ORDEM_SERVICO_EXECUTAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CLIENTE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="cliente" value="<?php echo $cliente; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EQUIPAMENTO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="equipamento" value="<?php echo ($row->equipamento_digitado) ? $row->equipamento_digitado : $equipamento->equipamento.' ['.$equipamento->codigo_referencia.']'; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ETIQUETA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="equipamento" value="<?php echo ($row->equipamento_digitado) ? $row->equipamento_digitado : $equipamento->etiqueta; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('RESPONSAVEL');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="responsavel_cliente" value="<?php echo $row->responsavel; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CRITICIDADE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="criticidade" value="<?php echo $criticidade; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PRIORIDADE');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="prioridade" value="<?php echo $prioridade; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('OBSERVACAO_EQUIPAMENTO');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea readonly rows="3" class="form-control caps" name="descricao_equipamento"><?php echo $row->descricao_equipamento;?></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('DESCRICAO_PROBLEMA');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea readonly rows="3" class="form-control caps" name="descricao_problema"><?php echo $row->descricao_problema;?></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('ORCAMENTO_DESCRICAO');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea readonly rows="3" class="form-control caps" name="descricao_orcamento"><?php echo $row->descricao_orcamento; ?></textarea>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('ORCAMENTO_TEMPO_SERVICO');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-6">
															<input readonly type="text" class="form-control hora" name="tempo_servico" value="<?php echo $row->tempo_servico; ?>">
														</div>
													</div>
												</div>	
											</div>
										</div>
									</div>
									
									<?php 
										$produtos_orcamento = $ordem_servico->getItensOrdemServico(Filter::$id);
										if ($produtos_orcamento):  
											$item = 0;
									?>
											<div class="portlet-body">
												<table class="table table-bordered table-striped table-condensed table-advance">
													<thead>
														<tr>
															<th><?php echo lang('PRODUTO');?></th>
															<th><?php echo lang('DESCRICAO');?></th>
															<th><?php echo lang('QUANT');?></th>
														</tr>
													</thead>
													<tbody>
													<?php 											
															foreach ($produtos_orcamento as $exrow):
													?>
														<tr>
															<td><?php echo $exrow->produto;?></td>
															<td><?php echo $exrow->descricao;?></td>
															<td><?php echo decimal($exrow->quantidade);?></td>
														</tr>
													<?php endforeach;?>
													<?php unset($exrow);?>
													</tbody>
												</table>
											</div>
									<?php endif;?>
									
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-12">
												<h4 class="form-section"><?php echo lang('ORDEM_SERVICO_EXECUTAR_DESCRICAO');?></h4>
												<div class="row">
													<div class="form-group">
														<div class="col-md-12">
															<textarea rows="3" class="form-control caps" name="servico_realizado"></textarea>
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<div class="col-md-6">
															<label class="control-label col-md-5"><?php echo lang('ORDEM_SERVICO_HORA_INICIO');?></label>
															<div class="col-md-7">
																<input type="time" class="form-control" name="hora_inicio">
															</div>
														</div>
														<div class="col-md-6">
															<label class="control-label col-md-5"><?php echo lang('ORDEM_SERVICO_HORA_FIM');?></label>
															<div class="col-md-7">
																<input type="time" class="form-control" name="hora_fim">
															</div>
														</div>
													</div>	
												</div>
											</div>
										</div>
									</div>
									
									<?php 
										$atendimento_os = $ordem_servico->getAtendimentosOrdemServico(Filter::$id);
										if ($atendimento_os):  
									?>
											<div class="portlet-body">
												<table class="table table-bordered table-striped table-condensed table-advance">
													<thead>
														<tr>
															<th><?php echo lang('RESPONSAVEL');?></th>
															<th><?php echo lang('DESCRICAO');?></th>
															<th><?php echo lang('DATA_INICIO');?></th>
															<th><?php echo lang('DATA_TERMINO');?></th>
														</tr>
													</thead>
													<tbody>
													<?php 											
															foreach ($atendimento_os as $exrow):
													?>
														<tr>
															<td><?php echo $exrow->nome;?></td>
															<td><?php echo $exrow->descricao_solucao;?></td>
															<td><?php echo exibeDataHora($exrow->data_inicio);?></td>
															<td><?php echo exibeDataHora($exrow->data_fim);?></td>
														</tr>
													<?php endforeach;?>
													<?php unset($exrow);?>
													</tbody>
												</table>
											</div>
									<?php endif;?>
									
								</div>
								<input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
								<input name="id_usuario" type="hidden" value="<?php echo $usuario->uid;?>" />
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-19">
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
														<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm("processarOS_Atendimento");?>	
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
<?php case "listar_os_finalizadas": 
	$datainicial = (get('di')) ? get('di') : date("d/m/Y");
	$datafinal = (get('df')) ? get('df') : date("d/m/Y", strtotime(date("Y-m-d")));
?>
<script type="text/javascript"> 
	$(document).ready(function () {
		$('#selecionardata').click(function() {
			var datainicial = $("#datainicial").val();
			var datafinal = $("#datafinal").val();
			window.location.href = 'index.php?do=ordem_servico&acao=listar_os_finalizadas&di='+datainicial+'&df='+datafinal;
		});
		
		var tabela_id = $('#tarefas').dataTable();
		tabela_id.fnSort([[ 0, "asc" ]]);
		tabela_id.fnSetColumnVis( 0, false );
		
	});
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('ORDEM_SERVICO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORDEM_SERVICO_LISTAR_FINALIZADAS');?></small></h1>
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
								<i class="fa fa-warning font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('ORDEM_SERVICO_LISTAR_FINALIZADAS');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<form autocomplete="off" class="form-horizontal">
								<div class="form-body">
									<div class='row'>
										<div class='col-md-12'>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-2"><?php echo lang('SELECIONE_DATA');?></label>
															<div class='col-md-4'>
																<input type="text" class="form-control input-medium calendario data" name="datainicial" id="datainicial" value="<?php echo $datainicial;?>" style="width: 160px !important;">
															</div>
															<div class='col-md-1'>
																<?php echo lang('SELECIONE_DATA_ATE'); ?>
															</div>
															<div class='col-md-4'>
																<input type="text" class="form-control input-medium calendario data" name="datafinal" id="datafinal" value="<?php echo $datafinal;?>" style="width: 160px !important;">
															</div>
													</div>
												</div>	
											</div>	
											<div class="col-md-6">	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-2"></label>
														<div class='col-md-10'>	
															<button type="button" id="selecionardata" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
														</div>
													</div>
												</div>	
											</div>
										</div>	
									</div>	
								</div>
							</form>
							<table class="table table-bordered table-striped table-condensed table-advance dataTable-asc">
								<thead>
									<tr>
										<th>#</th>
										<th>OS</th>
										<th><?php echo lang('DATA_ABERTURA');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('CIDADE');?></th>
										<th><?php echo lang('EQUIPAMENTO');?></th>
										<th><?php echo lang('PROBLEMA');?></th>
										<th><?php echo lang('SOLUCAO');?></th>
										<th><?php echo lang('STATUS');?></th>
										<th><?php echo lang('RESPONSAVEL');?></th>
										<th width="130px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $ordem_servico->getOrdemServicoFinalizada($datainicial, $datafinal);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
								?>
									<tr class="popovers" data-container="body" data-trigger="hover" data-placement="top" data-content="<?php echo $exrow->descricaoStatus?>" data-original-title="<?php echo lang('OBSERVACAO'); ?>">
										<td><?php echo $exrow->id;?></td>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo exibedata($exrow->data_abertura);?></td>
										<td><a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->nome;?></a></td>
										<td><?php echo $exrow->cidade;?></td>
										<td><?php echo ($exrow->descricao_equipamento) ? $exrow->descricao_equipamento : $exrow->equipamento;?></td>
										<td><?php echo $exrow->descricao_problema;?></td>
										<td><?php echo $exrow->descricao_orcamento;?></td>
										<td><?php echo $exrow->status;?></td>
										<td><?php echo strtoupper($exrow->responsavel);?></td>
										<td>	
											<a href="imprimir_ordem_servico.php?id_orcamento=<?php echo $exrow->id; ?>" target="_blank" class="btn btn-sm yellow-casablanca" title="<?php echo lang('ORDEM_SERVICO_IMPRIMIR');?>" style="margin-top:4px;"><i class="fa fa-print"></i></a>
											
											<?php if (!$exrow->id_nota_produto): ?>
												<?php if (!$exrow->id_fatura): ?>
													<?php if ($exrow->id_cadastro): ?>
														<a href="javascript:void(0);" class="btn btn-sm blue gerarNFeOS btn-fiscal" id="<?php echo $exrow->id; ?>" title="<?php echo lang('NOTA_FISCAL_CONVERTER_OS').': '.$exrow->id;?>"><i class="fa fa-files-o"></i></a>
													<?php else: ?>
														<a href="javascript:void(0);" class="btn btn-sm grey-cascade gerarNFeOSBloqueio btn-fiscal" title="<?php echo lang('NOTA_FISCAL_CONVERTER_OS_NAO').': '.$exrow->id;?>"><i class="fa fa-files-o"></i></a>
													<?php endif; ?>
												<?php endif; ?>
											<?php else: ?>
												<a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota_produto; ?>" class="btn btn-sm green" title="<?php echo lang('NOTA_FISCAL_CONVERTER_OS_SIM').': '.$exrow->id;?>"><i class="fa fa-files-o"></i></a>
											<?php endif; ?>

											<?php if (!$exrow->id_nota_servico): ?>
												<?php if (!$exrow->id_fatura): ?>
													<a href="index.php?do=xml&acao=saidas&id=<?php echo $exrow->id; ?>" class="btn btn-sm blue-hoki btn-fiscal" id="<?php echo $exrow->id; ?>" title="<?php echo lang('NOTA_SERVICO_CONVERTER_OS').': '.$exrow->id;?>"><i class="fa fa-file-code-o"></i></a>
												<?php endif; ?>
											<?php else: ?>
												<a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_nota_servico; ?>" class="btn btn-sm green-jungle" id="<?php echo $exrow->id; ?>" title="<?php echo lang('NOTA_SERVICO_VISUALIZAR_OS').': '.$exrow->id;?>"><i class="fa fa-file-code-o"></i></a>
											<?php endif; ?>
											
											<?php if (!$exrow->id_fatura): ?>
												<?php if (!$exrow->id_nota_produto || !$exrow->id_nota_servico): ?>
													<?php if ($exrow->id_cadastro): ?>
														<a href="javascript:void(0);" class="btn btn-sm blue-madison gerarFaturaOS btn-fiscal" id="<?php echo $exrow->id; ?>" title="<?php echo lang('NOTA_FATURA_CONVERTER_OS').': '.$exrow->id;?>"><i class="fa fa-money"></i></a>
													<?php else: ?>
														<a href="javascript:void(0);" class="btn btn-sm grey-cascade gerarFaturaOSBloqueio btn-fiscal" title="<?php echo lang('NOTA_FATURA_CONVERTER_OS_NAO').': '.$exrow->id;?>"><i class="fa fa-files-o"></i></a>
													<?php endif; ?>
												<?php endif; ?>
											<?php else: ?>
												<a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id_fatura; ?>" class="btn btn-sm green-haze" title="<?php echo lang('NOTA_FATURA_CONVERTER_OS_SIM').': '.$exrow->id;?>"><i class="fa fa-money"></i></a>
											<?php endif; ?>

										</td>
									</tr>
								<?php 
									  endforeach;?>
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
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "orcamentos": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('ORCAMENTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORCAMENTO_LISTAR');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('ORCAMENTO_LISTAR');?></span>
							</div>
							<?php if ($usuario->is_Master()): ?>
							<div class="actions btn-set">
								<a href="index.php?do=ordem_servico&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
							<?php endif; ?>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable-asc">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('ORDEM_SERVICO_SIGLA');?></th>
										<th><?php echo lang('DATA_ABERTURA');?></th>
										<th><?php echo lang('ETIQUETA');?></th>
										<th><?php echo lang('EQUIPAMENTO');?></th>
										<th><?php echo lang('CLIENTE');?></th>
										<th><?php echo lang('PROBLEMA');?></th>
										<th><?php echo lang('TECNICO');?></th>
										<th><?php echo lang('STATUS');?></th>
										<th width="150px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $ordem_servico->getOrdemServico();
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											/*if ($exrow->id_status <= 2 || $usuario->is_Gerencia()):*/
								?>
									<tr class="popovers" data-container="body" data-trigger="hover" data-placement="top" data-content="<?php echo $exrow->descricaoStatus?>" data-original-title="<?php echo lang('OBSERVACAO'); ?>">
										<td><?php echo $exrow->id;?></td>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo exibedata($exrow->data_abertura);?></td>
										<td><strong><?php echo $exrow->etiqueta;?></strong></td>
										<td><?php echo ($exrow->equipamento_digitado) ? $exrow->equipamento_digitado : $exrow->equipamento;?></td>
										<td><a href="index.php?do=cadastro&acao=receitas&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->nome;?></a></td>
										<td><?php echo $exrow->descricao_problema;?></td>
										<td><?php echo $exrow->usuario_orcamento;?></td>
										<td><?php echo $exrow->status;?></td>
										<td>
										<?php if ($usuario->is_Gerencia()): ?>
											<a href="index.php?do=ordem_servico&acao=editarorcamento&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('ORCAMENTO_EDITAR').': '.$exrow->etiqueta;?>"><i class="fa fa-pencil"></i></a>
											<?php if ($exrow->id_status>=3): ?><!-- Definir valor da OS e enviar para o cliente -->
											<a href="index.php?do=ordem_servico&acao=gerenciarvalororcamento&id=<?php echo $exrow->id;?>" class="btn btn-sm yellow-casablanca" title="<?php echo lang('ORCAMENTO_VALOR').': '.$exrow->etiqueta;?>"><i class="fa fa-dollar"></i></a>
											<?php endif; ?>
										<?php endif ?>	
											<a href="index.php?do=ordem_servico&acao=visualizarorcamento&id=<?php echo $exrow->id;?>" class="btn btn-sm grey-cascade" title="<?php echo lang('ORCAMENTO_VISUALIZAR').': '.$exrow->etiqueta;?>"><i class="fa fa-search"></i></a>
										<?php if ($usuario->is_Gerencia()): ?>	
											<a href="index.php?do=ordem_servico&acao=cancelar_orcamento&id=<?php echo $exrow->id;?>" class="btn btn-sm red" id="<?php echo $exrow->id;?>" title="<?php echo lang('ORCAMENTO_CANCELAR').$exrow->etiqueta;?>"><i class="fa fa-ban"></i></a>
										<?php endif; ?>	
										<?php if ($usuario->is_Gerencia()): ?>
										<br>
											<?php if ($exrow->id_status>=3): ?>
													<a href="imprimir_orcamento.php?id_orcamento=<?php echo $exrow->id; ?>" target="_blank" class="btn btn-sm yellow-casablanca" title="<?php echo lang('ORCAMENTO_IMPRIMIR');?>" style="margin-top:4px;"><i class="fa fa-print"></i></a>
											<?php endif; ?>
											<?php if ($exrow->id_status==4 && !empty($exrow->celular)): //Enviar OS para o cliente via whatsapp
														$substituir = array("(",")"," ","-");
														$celular = str_replace($substituir,"",$exrow->celular);
														$site_sistema = $core->site_sistema;
														$link = $site_sistema."imprimir_orcamento.php?id_orcamento=".$exrow->id;
														$linkwhatsapp = "https://wa.me/55$celular?text=$link";
												?>
													<a href="<?php echo $linkwhatsapp; ?>" target="_blank" class="btn btn-sm green-jungle" title="<?php echo lang('ORCAMENTO_ENVIAR_WHATSAPP');?>" style="margin-top:4px;"><i class="fa fa-whatsapp"></i></a>
											<?php endif; ?>													
											<?php if ($exrow->id_status==4): //Enviar OS para o cliente via whatsapp ?>
													<a href="javascript:void(0);" class="btn btn-sm yellow-lemon aprovarOrcamento" id="<?php echo $exrow->id;?>" acao="aprovarOrcamento" title="<?php echo lang('ORCAMENTO_APROVAR').' '.lang('ETIQUETA').': '.$exrow->etiqueta;?>" style="margin-top:4px;"><i class="fa fa-thumbs-o-up"></i></a>
											<?php endif; ?>
										<?php endif; ?>
										</td>
									</tr>
								<?php 	/*endif;*/
									  endforeach;?>
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
<?php case "cancelar_orcamento": 
	$id_orcamento = Filter::$id;
	$orcamento = Core::getRowById("ordem_servico", $id_orcamento);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('ORCAMENTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ORCAMENTO_CANCELAR_TITULO');?></small></h1>
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
					<div class="portlet box red">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('ORCAMENTO_CANCELAR_TITULO');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">	
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo lang('ORCAMENTO_CANCELAR_MOTIVO');?></label>
												<div class="col-md-8">
													<textarea rows="3" class="form-control caps" name="cancelar_motivo"></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3"><?php echo lang('ORCAMENTO_CANCELAR_EQUIPAMENTO');?></label>
												<div class="col-md-8">
													<textarea rows="3" class="form-control caps" name="cancelar_equipamento"></textarea>
												</div>
											</div>							
										</div>
									</div>
								</div>
								<input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-offset-3 col-md-19">
														<button type="button" class="btn btn-submit red"><?php echo lang('ORCAMENTO_CANCELAR_TITULO');?></button>
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
							<?php echo $core->doForm("cancelarOrcamento");?>	
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
<?php case "faturamento":
	if (!$usuario->is_Gerencia())
		redirect_to("login.php");
	$id_os = Filter::$id;
	$row_os = Core::getRowById("ordem_servico", $id_os);
	$valor_produtos = $row_os->valor_produto;
	$valor_servicos = $row_os->valor_total-$row_os->valor_produto;
	$valor_faturar  = ($row_os->id_nota_produto==0) ? $valor_produtos : 0;
	$valor_faturar += ($row_os->id_nota_servico==0) ? $valor_servicos : 0;
?>

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('ORDEM_SERVICO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('FATURAMENTO');?></small></h1>
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
								<i class="fa fa-usd font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('FATURAMENTO');?></span>
							</div>
						</div>
						<?php if ($row_os->id_nota_servico>0 && $row_os->id_nota_produto>0): ?>
							<div class="portlet-body">
								<div class="note note-warning">
									<h4 class="block"><?php echo lang('ORDEM_SERVICO_FATURAMENTO_AVISO'); ?></h4>
									<p><?php echo lang('ORDEM_SERVICO_FATURAMENTO_TEXTO'); ?></p>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-19">
														<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
													</div>
												</div>
											</div>
											<div class="col-md-6">
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php else: ?>						
							<div class="portlet-body">
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="dashboard-stat sigesis-cor-1">
										<div class="visual">
											<i class="fa fa-shopping-cart"></i>
										</div>
										<div class="details">
											<div class="number"><?php echo moeda($valor_produtos); ?></div>
											<?php if ($row_os->id_nota_produto>0): ?>
												<div class="desc"><s style="font-size:12px">(Valor total dos produtos)</s> Produto já faturado</div>
											<?php else: ?>	
												<div class="desc">Valor total dos produtos</div>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="dashboard-stat sigesis-cor-1">
										<div class="visual">
											<i class="fa fa-cogs"></i>
										</div>
										<div class="details">
											<div class="number"><?php echo moeda($valor_servicos); ?></div>
											<?php if ($row_os->id_nota_servico>0): ?>
												<div class="desc"><s style="font-size:12px">(Valor total dos serviços)</s> Serviço já faturado</div>
											<?php else: ?>	
												<div class="desc">Valor total dos serviços</div>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
									<div class="dashboard-stat sigesis-cor-1">
										<div class="visual">
											<i class="fa fa-dollar"></i>
										</div>
										<div class="details">
											<div class="number"><?php echo moeda($valor_faturar); ?></div>
											<div class="desc">Valor a ser faturado</div>
										</div>
									</div>
								</div>
							</div>	
								<div><br><br><br><br><br></div>
							<div class="portlet-body">
								<table class="table table-bordered table-striped table-condensed table-advance">
									<thead>
										<tr>
											<th><?php echo lang('VENCIMENTO');?></th>
											<th><?php echo lang('CLIENTE');?></th>
											<th><?php echo lang('EMPRESA');?></th>
											<th><?php echo lang('NUMERO_CONTRATO');?></th>
											<th width="90px"><?php echo lang('VALOR');?></th>
											<th><?php echo lang('STATUS');?></th>
											<th width="220px"><?php echo lang('OPCOES');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 	
											$retorno_row = 0;//$cadastro->getFaturamentoOrdemServicos();
											$total = 0;
											if($retorno_row):
											foreach ($retorno_row as $exrow):
												$total += $exrow->valor;
												$style = "";
												$condicao = lang('A_VENCER');
												if((!$exrow->atrasado_receita and $exrow->id_receita)){
													$condicao = lang('FATURADO');
													$style = "class='warning'";
												} elseif($exrow->pago == 1){
													$condicao = lang('PAGO');
													$style = "class='success'";
												}										
												$enviado = ($exrow->enviado) ? 'yellow-crusta' : 'grey-cascade';
												$estilo = '';
									?>
										<tr <?php echo $style;?>>
											<td><?php echo (!$exrow->atrasado_receita and $exrow->id_receita) ? exibedata($exrow->data_receita) : exibedata($exrow->data_vencimento);?></td>
											<td><a href="index.php?do=cadastro&acao=receitas&id=<?php echo $exrow->id_cadastro;?>"><?php echo $exrow->nome;?></a></td>
											<td><?php echo $exrow->empresa;?></td>
											<td><a href="index.php?do=contrato&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->numero_contrato;?></a></td>
											<td><?php echo moeda($exrow->valor);?></td>
											<td><?php echo $condicao;?></td>
											<td>
												<a href="javascript:void(0);" class="btn btn-sm grey-cascade" onclick="javascript:void window.open('imprimir_detalhes_cliente.php?id=<?php echo $exrow->id_cadastro;?>','<?php echo $exrow->id_cadastro;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>"><i class="fa fa-search"></i></a>
												<?php if(!$exrow->atrasado_receita and $exrow->id_receita):?>
													<?php if($exrow->pago == 2 and $exrow->taxa_boleto > 0):?>
														<a href="javascript:void(0);" nome="<?php echo $exrow->nome;?>" email="<?php echo strtolower($exrow->email);?>" id="<?php echo $exrow->id_receita;?>" title="<?php echo lang('BOLETO_ENVIAR');?>" class="emailboleto btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-send"></i></a>
														<a href="javascript:void(0);" id_contrato="<?php echo $exrow->id;?>" id_receita="<?php echo $exrow->id_receita;?>" title="<?php echo lang('BOLETO_GERAR');?>" class="boleto_santander btn btn-sm <?php echo $enviado;?>"><i class="fa fa-bold"></i></a>
														<a href="javascript:void(0);" class="btn btn-sm green pagarcontrato" id="<?php echo $exrow->id;?>" title="<?php echo lang('PAGAMENTO').': '.$exrow->nome;?>"><i class="fa fa-usd"></i></a>
														<a href="javascript:void(0);" id="<?php echo $exrow->id;?>" title="<?php echo lang('ORDEM_SERVICO_VENCIMENTO');?>" class="btn btn-sm purple vencimento"><i class="fa fa-calendar"></i></a>
													<?php elseif($exrow->pago == 2):?>
														<a href="javascript:void(0);" class="btn btn-sm green pagarcontrato" id="<?php echo $exrow->id;?>" title="<?php echo lang('PAGAMENTO').': '.$exrow->nome;?>"><i class="fa fa-usd"></i></a>
														<a href="javascript:void(0);" id="<?php echo $exrow->id;?>" title="<?php echo lang('ORDEM_SERVICO_VENCIMENTO');?>" class="btn btn-sm purple vencimento"><i class="fa fa-calendar"></i></a>
													<?php endif;?>
												<?php else:?>
													<a href="javascript:void(0);" id="<?php echo $exrow->id;?>" title="<?php echo lang('FATURAR_GERAR');?>" class="btn btn-sm yellow gerar_fatura"><i class="fa fa-check-square"></i></a>
												<?php endif;?>
											</td>
										</tr>
									<?php endforeach;?>
									<tfoot>
										<tr>
											<td colspan="4"><strong><?php echo lang('TOTAL');?></strong></td>
											<td><strong><?php echo moeda($total);?></strong></td>
											<td></td>
											<td></td>
										</tr>
									</tfoot>
									<?php unset($exrow);
										endif;?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>	
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