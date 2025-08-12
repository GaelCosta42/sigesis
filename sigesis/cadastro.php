<?php
  /**
   * Cadastro
   *
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Todos())
	  redirect_to("login.php");
?>
<?php switch(Filter::$acao): case "editar": ?>
<?php $row = Core::getRowById("cadastro", Filter::$id);?>
<div id="endereco-novo" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-map-marker">&nbsp;&nbsp;</i><?php echo lang('ENDERECO_NOVO');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="endereco_form" id="endereco_form" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('REFERENCIA');?></label>
								<div class="col-md-9">
									<input type="text" class="form-control caps" name="referencia" id="referencia">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('CEP');?></label>
								<div class="col-md-9">
									<div class="input-group">
									<input type="text" class="form-control cep" name="cep" id="cep2">
										<span class="input-group-btn">
										<button id="httpscep2" class="btn <?php echo $core->primeira_cor;?>" type="button"><i class="fa fa-arrow-left fa-fw"></i> <?php echo lang('BUSCAR_END');?></button>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('ENDERECO');?></label>
								<div class="col-md-9">
									<input type="text" class="form-control caps" name="endereco" id="endereco2">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('NUMERO');?></label>
								<div class="col-md-9">
									<input type="text" class="form-control caps" name="numero" id="numero2">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('COMPLEMENTO');?></label>
								<div class="col-md-9">
									<input type="text" class="form-control caps" name="complemento2">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('BAIRRO');?></label>
								<div class="col-md-9">
									<input type="text" class="form-control caps" name="bairro" id="bairro2">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('CIDADE');?></label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="cidade" id="cidade2">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('ESTADO');?></label>
								<div class="col-md-9">
									<input type="text" class="form-control caps uf" maxlength="2" name="estado" id="estado2">
								</div>
							</div>
						</div>
					</div>
				</div>
				<input name="id_cadastro" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarEndereco", "endereco_form");?>
</div>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><strong class="font-black"><?php echo $row->nome;?></strong></small>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><?php echo lang('CADASTRO_EDITAR');?></small></h1>
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
				<?php include("menucadastro.php");?>
				<div class="col-md-12">
					<div class="portlet box <?php echo $core->primeira_cor;?>">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_EDITAR');?>
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
														<label class="control-label col-md-3"><?php echo lang('NOME');?> / <?php echo lang('NOME_FANTASIA');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="nome" value="<?php echo $row->nome;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('RAZAO_SOCIAL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="razao_social" value="<?php echo $row->razao_social;?>">
															<span class="help-block"><?php echo lang('OBS_RAZAO_SOCIAL'); ?></span>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CONTATO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="contato" value="<?php echo $row->contato;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CELULAR');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control telefone" name="celular" value="<?php echo $row->celular;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CELULAR');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control telefone" name="celular2" value="<?php echo $row->celular2;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TELEFONE');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control telefone" name="telefone" value="<?php echo $row->telefone;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TELEFONE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control telefone" name="telefone2" value="<?php echo $row->telefone2;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_NASCIMENTO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control data calendario" name="data_nascimento" value="<?php echo exibedata($row->data_nascimento) ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('STATUS');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo getValue("status", "cadastro_status", "id=".$row->id_status);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-4"><strong class="bold italic font-red" style="font-size: 15px"><?php echo lang('OBS_*');?></strong></label>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TIPO');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<div class="md-radio-list">
																<div class="md-radio col-md-6">
																	<input type="radio" class="md-radiobtn" name="tipo" id="tipo_j" value="1" <?php getChecked($row->tipo,'1');?> >
																	<label for="tipo_j">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('PESSOA_JURIDICA');?></label>
																</div>
																<div class="md-radio col-md-6">
																	<input type="radio" class="md-radiobtn" name="tipo" id="tipo_f" value="2" <?php getChecked($row->tipo,'2');?> >
																	<label for="tipo_f">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('PESSOA_FISICA');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CPF_CNPJ');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control cpf_cnpj" name="cpf_cnpj" value="<?php echo $row->cpf_cnpj;?>">
															<span class="help-block"><?php echo lang('OBS_CPF_CNPJ'); ?></span>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('INSCRICAO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="ie" value="<?php echo $row->ie;?>">
															<span class="help-block"><?php echo lang('OBS_INSCRICAO'); ?></span>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMAIL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="email" value="<?php echo $row->email;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMAIL_COMERCIAL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="email2" value="<?php echo $row->email2;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('OBSERVACAO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="observacao" value="<?php echo $row->observacao;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ORIGEM');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_origem" data-placeholder="<?php echo lang('SELECIONE_ORIGEM');?>" >
																<option value=""></option>
															<?php
																	$retorno_row = $cadastro->getOrigem();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
															?>
																			<option value="<?php echo $srow->id;?>" <?php if($srow->id == $row->id_origem) echo 'selected="selected"';?>><?php echo $srow->origem;?></option>
															<?php
																		endforeach;
																		unset($row->observacao);
																	endif;
															?>
															</select>
														</div>
													</div>
												</div>
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('CREDIARIO');?></label>
															<div class="col-md-9">
																<?php
																	$retorno_row = $empresa->getEmpresas();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
																			if ($srow->alterar_valor_crediario == 1 && !$usuario->is_Master() && !$usuario->is_Controller()) {
																?>
																				<input readonly type="text" class="form-control" name="valor_crediario" value="<?php echo moeda($row->crediario);?>">
																<?php
																			} else {
																?>
																				<input type="text" class="form-control moeda" name="valor_crediario" value="<?php echo moeda($row->crediario);?>">
																<?php
																			};
																		endforeach;
																		unset($srow);
																	endif;
																?>
															</div>
														</div>
													</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<div class="md-checkbox-list">
																<div class="md-checkbox col-md-6">
																	<input type="checkbox" class="md-check" name="cliente" id="op_cliente" value="1" <?php if($row->cliente) echo 'checked';?>>
																	<label for="op_cliente">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('CLIENTE');?></label>
																</div>
																<div class="md-checkbox col-md-6">
																	<input type="checkbox" class="md-check" name="fornecedor" id="op_fornecedor" value="1" <?php if($row->fornecedor) echo 'checked';?>>
																	<label for="op_fornecedor">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('FORNECEDOR');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"></label>
														<label class="col-md-9"><strong><?php echo "Alterado em: ".exibedataHora($row->data)." por ".$row->usuario;?></strong></label>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
										<div class="col-md-12">
											<h4 class="form-section"><?php echo lang('ENDERECO_FATURAMENTO');?></h4>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CEP');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<div class="input-group">
															<input type="text" class="form-control cep" name="cep" id="cep" value="<?php echo $row->cep;?>">
																<span class="input-group-btn">
																<button id="cepbusca" class="btn <?php echo $core->primeira_cor;?>" type="button"><i class="fa fa-arrow-left fa-fw"></i> <?php echo lang('BUSCAR_END');?></button>
																</span>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ENDERECO');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="endereco" id="endereco" value="<?php echo $row->endereco;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('NUMERO');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="numero" id="numero" value="<?php echo $row->numero;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('COMPLEMENTO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="complemento" value="<?php echo $row->complemento;?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BAIRRO');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="bairro" id="bairro" value="<?php echo $row->bairro;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CIDADE');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control" name="cidade" id="cidade" value="<?php echo $row->cidade;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ESTADO');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps uf" name="estado" id="estado" maxlength="2" value="<?php echo $row->estado;?>">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<h4 class="form-section"><?php echo lang('INFORMACOES_BANCARIAS');?></h4>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TITULAR');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="titular" value="<?php echo $row->titular;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BANCO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control inteiro" name="banco" value="<?php echo $row->banco;?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('AGENCIA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control" name="agencia" value="<?php echo $row->agencia;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CONTA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control" name="conta" value="<?php echo $row->conta;?>">
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php
											$retorno_row = $empresa->getEmpresas();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													if ($srow->tipo_sistema == 4):
										?>
														<div class="col-md-12">
															<h4 class="form-section"><?php echo lang('USUARIO_APP');?></h4>
															<div class="col-md-6">
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3"><?php echo lang('USUARIO');?></label>
																		<div class="col-md-9">
																			<input readonly type="text" class="form-control" name="usuario" value="<?php echo $row->cpf_cnpj;?>">
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3"><?php echo lang('EMAIL');?></label>
																		<div class="col-md-9">
																			<input readonly type="text" class="form-control caps" name="email_app" value="<?php echo $row->email;?>">
																		</div>
																	</div>
																</div>
															</div>
															<!--/col-md-6-->
															<!--col-md-6-->
															<div class="col-md-6">
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3"><?php echo lang('SENHA');?></label>
																		<div class="col-md-9">
																			<input type="password" class="form-control" name="senha_app">
																		</div>
																	</div>
																</div>
															</div>
														</div>
										<?php
													endif;
												endforeach;
												unset($srow);
											endif;
										?>
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
							<?php echo $core->doForm("processarCadastro");?>
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
<?php case "adicionar":
$os = (get('os')) ? 1 : 0 ;
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_ADICIONAR');?></small></h1>
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
								<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ADICIONAR');?>
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
													<label class="control-label col-md-3"><?php echo lang('NOME');?> / <?php echo lang('NOME_FANTASIA');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="nome">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('RAZAO_SOCIAL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="razao_social">
															<span class="help-block"><?php echo lang('OBS_RAZAO_SOCIAL'); ?></span>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CONTATO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="contato">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CELULAR');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control telefone" name="celular">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CELULAR');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control telefone" name="celular2">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('TELEFONE');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control telefone" name="telefone">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TELEFONE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control telefone" name="telefone2">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_NASCIMENTO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control data calendario" name="data_nascimento">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-4"><strong class="bold italic font-red" style="font-size: 15px"><?php echo lang('OBS_*');?></strong></label>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('TIPO');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<div class="md-radio-list">
																<div class="md-radio col-md-6">
																	<input type="radio" class="md-radiobtn" name="tipo" id="tipo_j" value="1" >
																	<label for="tipo_j">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('PESSOA_JURIDICA');?></label>
																</div>
																<div class="md-radio col-md-6">
																	<input type="radio" class="md-radiobtn" name="tipo" id="tipo_f" value="2" >
																	<label for="tipo_f">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('PESSOA_FISICA');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CPF_CNPJ');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control cpf_cnpj" name="cpf_cnpj">
															<span class="help-block"><?php echo lang('OBS_CPF_CNPJ'); ?></span>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('INSCRICAO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="ie">
															<span class="help-block"><?php echo lang('OBS_INSCRICAO'); ?></span>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMAIL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="email">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMAIL_COMERCIAL');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="email2">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('OBSERVACAO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="observacao">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ORIGEM');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_origem" data-placeholder="<?php echo lang('SELECIONE_ORIGEM');?>" >
																<option value=""></option>
															<?php
																	$retorno_row = $cadastro->getOrigem();
																	if ($retorno_row):
																		foreach ($retorno_row as $srow):
															?>
																			<option value="<?php echo $srow->id;?>"><?php echo $srow->origem;?></option>
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
														<label class="control-label col-md-3"><?php echo lang('CREDIARIO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moeda" name="valor_crediario">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
													<label class="control-label col-md-3"><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<div class="md-checkbox-list">
																<div class="md-checkbox col-md-6">
																	<input type="checkbox" class="md-check" name="cliente" id="op_cliente" value="1">
																	<label for="op_cliente">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('CLIENTE');?></label>
																</div>
																<div class="md-checkbox col-md-6">
																	<input type="checkbox" class="md-check" name="fornecedor" id="op_fornecedor" value="1">
																	<label for="op_fornecedor">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('FORNECEDOR');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
										<div class="col-md-12">
											<h4 class="form-section"><?php echo lang('ENDERECO_FATURAMENTO');?></h4>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CEP');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<div class="input-group">
															<input type="text" class="form-control cep" name="cep" id="cep">
																<span class="input-group-btn">
																<button id="cepbusca" class="btn <?php echo $core->primeira_cor;?>" type="button"><i class="fa fa-arrow-left fa-fw"></i> <?php echo lang('BUSCAR_END');?></button>
																</span>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('ENDERECO');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="endereco" id="endereco">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('NUMERO');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="numero" id="numero">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('COMPLEMENTO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="complemento">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('BAIRRO');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="bairro" id="bairro">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('CIDADE');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="cidade" id="cidade">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('ESTADO');?><strong class="bold italic font-red" style="font-size: 20px"><?php echo lang('*');?></strong></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps uf" name="estado" maxlength="2" id="estado">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<h4 class="form-section"><?php echo lang('INFORMACOES_BANCARIAS');?></h4>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TITULAR');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="titular">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BANCO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control inteiro" name="banco">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('AGENCIA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control" name="agencia" >
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CONTA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control" name="conta">
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
														<input name="os" type="hidden" value="<?php echo $os;?>" />
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
							<?php echo $core->doForm("processarCadastro");?>
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
<?php case "cnpj":
require_once("./lib/class_captcha.php");
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_ADICIONAR_CNPJ');?></small></h1>
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
								<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ADICIONAR_CNPJ');?>
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
														<label class="control-label col-md-5"><?php echo lang('CPF_CNPJ');?></label>
														<div class="col-md-7">
															<input type="text" class="form-control cpf_cnpj" name="cnpj">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-5"><?php echo lang('CADASTRO_IMAGEM');?></label>
														<div class="col-md-7">
															<input type="text" class="form-control" name="captcha_cnpj">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<br/>
													<br/>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-1"></label>
														<div class="col-md-11">
															<img id="captcha_cnpj" src="<?php echo $imagem_cnpj;?>" border="0">
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
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('BUSCAR');?></button>
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
							<?php echo $core->doForm("processarCNPJReceita");?>
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
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_LISTAR');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CADASTRO_LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=cadastro&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th><?php echo lang('NOME');?></th>
										<th><?php echo lang('CONTATO');?></th>
										<th><?php echo lang('CPF_CNPJ');?></th>
										<th><?php echo lang('TELEFONE');?></th>
										<th><?php echo lang('CELULAR');?></th>
										<th><?php echo lang('BAIRRO');?></th>
										<th><?php echo lang('CIDADE');?></th>
										<th width="120px"><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $cadastro->getCadastros();
										if($retorno_row):
										foreach ($retorno_row as $exrow):

								?>
									<tr <?php echo ($exrow->restricao) ? "class='danger'" : "";?> >
										<td><a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->nome;?></a></td>
										<td><?php echo $exrow->contato;?></td>
										<td><?php echo $exrow->cpf_cnpj;?></td>
										<td><?php echo $exrow->telefone;?></td>
										<td><?php echo $exrow->celular;?></td>
										<td><?php echo $exrow->bairro;?></td>
										<td><?php echo $exrow->cidade;?></td>
										<td>
											<a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->nome;?>"><i class="fa fa-pencil"></i></a>
											<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarCadastro" title="<?php echo lang('CADASTRO_APAGAR').$exrow->nome;?>"><i class="fa fa-ban"></i></a>
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
<?php case "fornecedores": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_FORNECEDORES');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CADASTRO_FORNECEDORES');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=cadastro&acao=importar_cliente_fornecedor" class="btn btn-sm yellow-gold "><i class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('IMPORTAR_CLIENTES_FORNECEDORES');?></a>
								<a href="index.php?do=cadastro&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th><?php echo lang('NOME');?></th>
										<th><?php echo lang('CONTATO');?></th>
										<th><?php echo lang('CPF_CNPJ');?></th>
										<th><?php echo lang('TELEFONE');?></th>
										<th><?php echo lang('CELULAR');?></th>
										<th><?php echo lang('BAIRRO');?></th>
										<th><?php echo lang('CIDADE');?></th>
										<th width="120px"><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $cadastro->getCadastros('FORNECEDOR');
										if($retorno_row):
										foreach ($retorno_row as $exrow):

								?>
									<tr>
										<td><a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->nome;?></a></td>
										<td><?php echo $exrow->contato;?></td>
										<td><?php echo formatar_cpf_cnpj($exrow->cpf_cnpj);?></td>
										<td><?php echo formatar_telefone($exrow->telefone);?></td>
										<td><?php echo formatar_telefone($exrow->celular);?></td>
										<td><?php echo $exrow->bairro;?></td>
										<td><?php echo $exrow->cidade;?></td>
										<td>
											<a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->nome;?>"><i class="fa fa-pencil"></i></a>
											<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarCadastro" title="<?php echo lang('CADASTRO_APAGAR').$exrow->nome;?>"><i class="fa fa-ban"></i></a>
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
<?php case "clientes": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_CLIENTES');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CADASTRO_CLIENTES');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=cadastro&acao=importar_cliente_fornecedor" class="btn btn-sm yellow-gold "><i class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('IMPORTAR_CLIENTES_FORNECEDORES');?></a>
								<a href="index.php?do=cadastro&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th><?php echo lang('NOME');?></th>
										<th><?php echo lang('CONTATO');?></th>
										<th><?php echo lang('CPF_CNPJ');?></th>
										<th><?php echo lang('TELEFONE');?></th>
										<th><?php echo lang('CELULAR');?></th>
										<th><?php echo lang('EMAIL');?></th>
										<th><?php echo lang('DATA_NASCIMENTO');?></th>
										<th width="120px"><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $cadastro->getCadastros('CLIENTE');
										if($retorno_row):
										foreach ($retorno_row as $exrow):

								?>
									<tr <?php echo ($exrow->restricao) ? "class='danger'" : "";?> >
										<td><a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->nome;?></a></td>
										<td><?php echo $exrow->contato;?></td>
										<td><?php echo $exrow->cpf_cnpj;?></td>
										<td><?php echo $exrow->telefone;?></td>
										<td><?php echo $exrow->celular;?></td>
										<td><?php echo $exrow->email;?></td>
										<td><?php echo exibedata($exrow->data_nascimento);?></td>
										<td>
											<a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->nome;?>"><i class="fa fa-pencil"></i></a>
											<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarCadastro" title="<?php echo lang('CADASTRO_APAGAR').$exrow->nome;?>"><i class="fa fa-ban"></i></a>
											<?php if ($core->tipo_sistema==5): ?>
												<a href="index.php?do=ordem_servico&acao=adicionar&id_cliente=<?php echo $exrow->id;?>" class="btn btn-sm grey-cascade" title="<?php echo lang('ORCAMENTO_ADICIONAR').': '.$exrow->nome;?>"><i class="fa fa-cogs"></i></a>
											<?php endif; ?>
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
<?php case "oportunidades": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('OPORTUNIDADES');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('OPORTUNIDADES');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th><?php echo lang('NOME');?></th>
										<th><?php echo lang('CONTATO');?></th>
										<th><?php echo lang('CPF_CNPJ');?></th>
										<th><?php echo lang('TELEFONE');?></th>
										<th><?php echo lang('CELULAR');?></th>
										<th><?php echo lang('ENDERECO');?></th>
										<th><?php echo lang('NUMERO');?></th>
										<th><?php echo lang('COMPLEMENTO');?></th>
										<th><?php echo lang('CIDADE');?></th>
										<th width="120px"><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $cadastro->getOportunidades();
										if($retorno_row):
										foreach ($retorno_row as $exrow):?>
									<tr>
										<td><a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->nome;?></a></td>
										<td><?php echo $exrow->contato;?></td>
										<td><?php echo $exrow->cpf_cnpj;?></td>
										<td><?php echo $exrow->telefone;?></td>
										<td><?php echo $exrow->celular;?></td>
										<td><?php echo $exrow->endereco;?></td>
										<td><?php echo $exrow->numero;?></td>
										<td><?php echo $exrow->complemento;?></td>
										<td><?php echo $exrow->cidade;?></td>
										<td>
											<a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->nome;?>"><i class="fa fa-pencil"></i></a>
											<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarCadastro" title="<?php echo lang('CADASTRO_APAGAR').$exrow->nome;?>"><i class="fa fa-ban"></i></a>
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
<?php case "buscar": ?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#nome').focus();
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_BUSCAR');?></small></h1>
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
								<i class="fa fa-search"></i>
								<span><?php echo lang('BUSCAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=cadastro&acao=adicionar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('CADASTRO_ADICIONAR');?></a>
							</div>
						</div>
						<?php
							$array_cancelados = array();
							if(post('nome') or post('cpf') or post('telefone')):
								$retorno_row = $cadastro->getBuscarCadastro();
								if($retorno_row):?>
									<div class="portlet-body">
										<table class="table table-bordered table-striped table-condensed table-advance">
											<thead >
												<tr>
													<th><?php echo lang('NOME');?></th>
													<th><?php echo lang('CPF_CNPJ');?></th>
													<th><?php echo lang('TELEFONE');?></th>
													<th><?php echo lang('BAIRRO');?></th>
													<th><?php echo lang('CIDADE');?></th>
													<th><?php echo lang('STATUS');?></th>
													<th width="110px"><?php echo lang('ACOES');?></th>
												</tr>
											</thead>
											<tbody>
											<?php
													foreach ($retorno_row as $exrow):
														$status = '';
													if(!$exrow->inativo):
													$status = "<span class='label label-sm bg-green'>".lang('ATIVO')."</span>";
											?>
												<tr>
													<td><a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->nome;?></a></td>
													<td><?php echo formatar_cpf_cnpj($exrow->cpf_cnpj);?></td>
													<td><?php echo $exrow->telefone;?></td>
													<td><?php echo $exrow->bairro;?></td>
													<td><?php echo $exrow->cidade;?></td>
													<td><?php echo $status;?></td>
													<td>
														<a href="index.php?do=cadastro&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->nome;?>"><i class="fa fa-pencil"></i></a>
														<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarCadastro" title="<?php echo lang('CADASTRO_APAGAR').$exrow->nome;?>"><i class="fa fa-times"></i></a>
													</td>
												</tr>
											<?php
													else:
														$status = "<span class='label label-sm bg-red'>".lang('CANCELADO')."</span>";
														$array_linha = array(
															'id' => $exrow->id,
															'nome' => $exrow->nome,
															'cpf_cnpj' => $exrow->cpf_cnpj,
															'telefone' => $exrow->telefone,
															'bairro' => $exrow->bairro,
															'cidade' => $exrow->cidade,
															'status' => $status
														);
														$array_cancelados[] = $array_linha;

													endif;
													endforeach;
													unset($exrow);?>
											</tbody>
										</table>
									</div>
									<?php else: ?>
									<div class="portlet-body">
										<center><h4><?php echo lang('CADASTRO_VAZIO');?></h4></center>
									</div>
									<?php endif;
									endif?>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="index.php?do=cadastro&acao=buscar" autocomplete="off" method="post" class="horizontal-form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php echo lang('NOME');?></label>
													<input type="text" class="form-control caps buscar" placeholder="Buscar pelo nome, razão social, contato, bairro, cidade" id="nome" name="nome">
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label"><?php echo lang('CPF_CNPJ');?></label>
													<input type="text" class="form-control cpf_cnpj buscar" placeholder="Buscar pelo CPF/CNPJ" name="cpf">
												</div>
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
														<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('BUSCAR');?></button>
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
							<!-- FINAL FORM-->
						</div>

						<?php
								if($array_cancelados):?>
									<div class="portlet-body">
										<table class="table table-bordered table-striped table-condensed table-advance">
											<thead >
												<tr>
													<th><?php echo lang('NOME');?></th>
													<th><?php echo lang('CPF_CNPJ');?></th>
													<th><?php echo lang('TELEFONE');?></th>
													<th><?php echo lang('BAIRRO');?></th>
													<th><?php echo lang('CIDADE');?></th>
													<th><?php echo lang('STATUS');?></th>
													<th width="110px"><?php echo lang('ACOES');?></th>
												</tr>
											</thead>
											<tbody>
											<?php
													foreach ($array_cancelados as $ccrow):
											?>
												<tr class="danger">
													<td><a href="index.php?do=cadastro&acao=editar&id=<?php echo $ccrow['id'];?>"><?php echo $ccrow['nome'];?></a></td>
													<td><?php echo formatar_cpf_cnpj($ccrow['cpf_cnpj']);?></td>
													<td><?php echo $ccrow['telefone'];?></td>
													<td><?php echo $ccrow['bairro'];?></td>
													<td><?php echo $ccrow['cidade'];?></td>
													<td><?php echo $ccrow['status'];?></td>
													<td>
														<a href="index.php?do=cadastro&acao=editar&id=<?php echo $ccrow['id'];?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$ccrow['nome'];?>"><i class="fa fa-pencil"></i></a>
													</td>
												</tr>
											<?php	endforeach;
													unset($ccrow);?>
											</tbody>
										</table>
									</div>
									<?php endif;?>
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
<?php case "contato":
	$row = Core::getRowById("cadastro", Filter::$id);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><strong class="font-black"><?php echo $row->nome;?></strong></small>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CONTATO_RETORNO');?></small></h1>
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
				<?php include("menucadastro.php");?>
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-phone font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CONTATO_RETORNO');?></span>
							</div>
							<div class="actions">
								<a href="#retorno-contato" data-toggle="modal" class="btn btn-sm grey-gallery"><i class="fa fa-phone"></i></a>
								<a href="index.php?do=cadastro&acao=buscar" class="btn default"><?php echo lang('VOLTAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<table class="table table-bordered table-striped table-condensed table-advance">
										<thead >
											<tr>
												<th><?php echo lang('STATUS');?></th>
												<th><?php echo lang('OBSERVACAO');?></th>
												<th><?php echo lang('DATA_RETORNO');?></th>
												<th><?php echo lang('USUARIO');?></th>
												<th><?php echo lang('DATA_CONTATO');?></th>
											</tr>
										</thead>
										<tbody>
										<?php
												$retorno_row = $cadastro->getCadastroRetorno(Filter::$id);
												if($retorno_row):
													foreach ($retorno_row as $exrow):
														$estilo = '';
														if($exrow->data_retorno == '0000-00-00')
															$estilo = '';
														elseif($exrow->atrasado)
															$estilo = 'class="danger"';
														elseif($exrow->hoje)
															$estilo = 'class="warning"';
														elseif($exrow->agendado)
															$estilo = 'class="info"';
										?>
											<tr <?php echo $estilo;?>>
												<td><?php echo $exrow->status;?></td>
												<td><?php echo $exrow->observacao;?></td>
												<td><?php echo exibedata($exrow->data_retorno);?></td>
												<td><?php echo $exrow->usuario;?></td>
												<td><?php echo exibedataHora($exrow->data);?></td>
											</tr>
										<?php 		endforeach;
													unset($exrow);
												endif;
										?>
										</tbody>
									</table>
								</div>
							</div>
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

<div id="retorno-contato" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-phone">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO');?></h4>
				<h4 class="modal-title"><?php echo $row->nome;?></h4>
				<h4 class="modal-title"><?php echo $row->telefone." ".$row->celular;?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="retorno_form" id="retorno_form" >
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p><?php echo lang('RETORNO');?></p>
							<p>
								<select class="select2me form-control" id="id_status" name="id_status" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
										$retorno_row = $cadastro->getStatus();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
											$style = ($srow->tipo) ? "class='label-warning'" : "";
									?>
												<option <?php echo $style;?> value="<?php echo $srow->id;?>"><?php echo $srow->status;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
								</select>
							</p>
							<p><?php echo lang('DATA_RETORNO');?></p>
							<p>
								<input type="text" class="form-control data calendario" name="data_retorno">
							</p>
							<p><?php echo lang('OBSERVACAO');?></p>
							<p>
								<input type="text" class="form-control caps" name="observacao">
							</p>
							<p><?php echo lang('INTERESSE');?></p>
							<p>
								<div class="md-radio-list">
									<div class="md-radio col-md-2">
										<input type="radio" class="md-radiobtn" name="interesse" id="p1" value="20" >
										<label for="p1">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										20%</label>
									</div>
									<div class="md-radio col-md-2">
										<input type="radio" class="md-radiobtn" name="interesse" id="p2" value="40" >
										<label for="p2">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										40%</label>
									</div>
									<div class="md-radio col-md-2">
										<input type="radio" class="md-radiobtn" name="interesse" id="p3" value="60" >
										<label for="p3">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										60%</label>
									</div>
									<div class="md-radio col-md-2">
										<input type="radio" class="md-radiobtn" name="interesse" id="p4" value="80" >
										<label for="p4">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										80%</label>
									</div>
									<div class="md-radio col-md-2">
										<input type="radio" class="md-radiobtn" name="interesse" id="p5" value="100" >
										<label for="p5">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										100%</label>
									</div>
								</div>
							</p>
						</div>
					</div>
				</div>
				<input name="id_cadastro" type="hidden" value="<?php echo Filter::$id;?>" />
				<input name="contato" type="hidden" value="1" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarCadastroRetorno", "retorno_form");?>
</div>
<?php break;?>
<?php case "despesas":
	if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;
	$row = Core::getRowById("cadastro", Filter::$id);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><strong class="font-black"><?php echo $row->nome;?></strong></small>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><?php echo lang('DESPESAS');?></small></h1>
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
				<?php include("menucadastro.php");?>
				<div class="col-md-12">
					<div class="portlet box red-pink">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('DESPESAS');?>
							</div>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable-asc'>
								<thead>
									<tr>
										<th>#</th>
										<th>#</th>
										<th><?php echo lang('DATA_VENCIMENTO');?></th>
										<th><?php echo lang('DATA_PAGAMENTO');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('PLANO_CONTAS');?></th>
										<th><?php echo lang('VALOR');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
										<th><?php echo lang('PAGO');?></th>
										<?php if ($usuario->is_Master()): ?>
										<th><?php echo lang('ACOES');?></th>
										<?php endif; ?>
									</tr>
								</thead>
								<tbody>
								<?php
										$total = 0;
										$pago = 0;
										$retorno_row = $despesa->getDespesasCadastro(Filter::$id);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$total += $exrow->valor;
											$pago += $exrow->valor_pago;
											$estilo = '';
											$juros = '';
											if($exrow->cheque) {
												$estilo = "class='warning'";
											}
											if($exrow->pago==1) {
												$estilo = "class='success'";
											}
											if($exrow->valor_pago > $exrow->valor) {
												$juros = "class='font-red'";
											}
								?>
											<tr <?php echo $estilo;?>>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo $exrow->id;?></td>
												<td><?php echo exibedata($exrow->data_vencimento);?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo ($exrow->empresa);?></td>
												<td><?php echo ($exrow->banco);?></td>
												<td><?php echo $exrow->descricao;?></td>
												<td><?php echo $exrow->conta;?></td>
												<td><?php echo decimalp($exrow->valor);?></td>
												<td <?php echo $juros;?>><?php echo decimalp($exrow->valor_pago);?></td>
												<td><?php echo ($exrow->pago==1) ? "SIM" : "NAO";?></td>
												<?php if($usuario->is_Master()): ?>
												<td>
												<?php if(!$exrow->inativo): ?>
														<a href='javascript:void(0);' class='btn btn-sm red apagar' id='<?php echo $exrow->id;?>' acao='apagarDespesas' title='<?php echo lang('FINANCEIRO_DESPESAS_APAGAR').$exrow->descricao;?>'><i class='fa fa-times'></i></a>
												<?php endif; ?>
												</td>
												<?php endif; ?>
											</tr>
								<?php
											endforeach;
											unset($exrow);
										endif;
								?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="8"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo moedap($total);?></strong></td>
										<td><strong><?php echo moedap($pago);?></strong></td>
										<td></td>
										<?php if($usuario->is_Master()): ?>
										<td></td>
										<?php endif; ?>
									</tr>
								</tfoot>
							</table>
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
<?php case "receitas":
	if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;
	$row = Core::getRowById("cadastro", Filter::$id);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><strong class="font-black"><?php echo $row->nome;?></strong></small>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><?php echo lang('FINANCEIRO_RECEITAS');?></small></h1>
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
				<?php include("menucadastro.php");?>
				<div class="col-md-12">
					<div class="portlet box green">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_RECEITAS');?>
							</div>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable-asc'>
								<thead class='flip-content'>
									<tr>
										<th>#</th>
										<th>#</th>
										<th><?php echo lang('DATA_CREDITO');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('BANCO');?></th>
										<th><?php echo lang('NUMERO_NOTA');?></th>
										<th><?php echo lang('DOCUMENTO');?></th>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('VALOR_PAGO');?></th>
										<th><?php echo lang('DATA_TRANSACAO');?></th>
										<th><?php echo lang('PAGO');?></th>
										<?php if ($usuario->is_Master()): ?>
										<th><?php echo lang('ACOES');?></th>
										<?php endif; ?>
									</tr>
								</thead>
								<tbody>
								<?php
										$total = 0;
										$descricao = '';
										$retorno_row = $faturamento->getReceitasCadastro(Filter::$id);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
												$id_categoria = getValue("id_categoria","tipo_pagamento","id=".$exrow->tipo);

												if ($id_categoria==1 && strpos($exrow->descricao,"CREDIARIO/FICHA")) continue;

												$total += ($exrow->pago==1) ? $exrow->valor_pago : 0;
												if(trim($exrow->descricao) != ''){
													$descricao = $exrow->descricao;
												} elseif($exrow->tipo == 0){
													$descricao = 'TRANSFERENCIA ENTRE BANCO';
												} elseif($exrow->tipo == 1) {
													$descricao = $exrow->pagamento.' - CAIXA ID:'.$exrow->id_caixa;
												} else {
													$nome = ($exrow->id_cadastro) ? ' - '.getValue("nome","cadastro","id = '".$exrow->id_cadastro."'") : "";
													$descricao = $exrow->pagamento.$nome;
												}
												$pago = '';
												if($exrow->pago==1) {
													$pago = "<span class='label label-sm bg-green'>".lang('SIM')."</span>";
												} else {
													$pago = "<span class='label label-sm bg-red'>".lang('NAO')."</span>";
												}
												if($exrow->inativo) {
													$pago = "<span class='label label-sm bg-red'>".lang('CANCELADO')."</span>";
												}

								?>
											<tr>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo $exrow->id;?></td>
												<td><?php echo exibedata($exrow->data_recebido);?></td>
												<td><?php echo ($exrow->empresa);?></td>
												<td><?php echo ($exrow->banco);?></td>
												<td><?php echo ($exrow->numero_nota);?></td>
												<td><?php echo ($exrow->duplicata);?></td>
												<td><?php echo $descricao;?></td>
												<td><?php echo moedap($exrow->valor_pago);?></td>
												<td><?php echo exibedata($exrow->data_pagamento);?></td>
												<td><?php echo $pago;?>
													<?php if (!$exrow->crediario): ?>
														<?php if ($exrow->pago!=1):?>
															<a href='javascript:void(0);' class='btn btn-sm green pagarfinanceiro' id_categoria='<?php echo $id_categoria; ?>' id_banco='<?php echo $exrow->id_banco;?>' valor_pago='<?php echo moedap($exrow->valor_pago);?>' id='<?php echo $exrow->id;?>' title='<?php echo lang('PAGAR').$descricao;?>'><i class='fa fa-check'></i></a>
														<?php endif; ?>
													<?php endif; ?>
												</td>
												<?php if ($usuario->is_Master()): ?>
													<td>
														<?php if (!$exrow->crediario): ?>
															<?php if (!$exrow->inativo): ?>
																<a href='javascript:void(0);' class='btn btn-sm red apagar' id='<?php echo $exrow->id;?>' acao='apagarReceita' title='<?php echo lang('APAGAR').": ".$descricao;?>'><i class='fa fa-times'></i></a>
															<?php endif; ?>
														<?php endif; ?>
													</td>
												<?php endif; ?>
											</tr>
								<?php
											endforeach;
											unset($exrow);
										endif;
								?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="8"><strong><?php echo lang('TOTAL');?></strong></td>
										<td><strong><?php echo moedap($total);?></strong></td>
										<td></td>
										<td></td>
										<?php if($usuario->is_Master()): ?>
										<td></td>
										<?php endif; ?>
									</tr>
								</tfoot>
							</table>
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
<div id='pagar-receita' class='modal fade' tabindex='-1'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
				<h4 class='modal-title'><?php echo lang('PAGAR');?></h4>
			</div>
			<form action='' autocomplete="off" method='post' name='pagar_form' id='pagar_form' >
				<div class='modal-body'>
					<div class='row'>
						<div class='col-md-12'>
							<p><?php echo lang('BANCO');?></p>
							<p>
								<select class='select2me form-control' id='id_banco2' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
									<option value=""></option>
									<?php
										$retorno_row = $faturamento->getBancos();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value='<?php echo $srow->id;?>'><?php echo $srow->banco;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
								</select>
							</p>
							<div class="ocultar" id="tipo_pagamento_crediario_dv">
								<p><?php echo lang('PAGAMENTO');?></p>
								<p>
									<select class='select2me form-control' name='tipo_pagamento_crediario' id='tipo_pagamento_crediario' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
										<option value=""></option>
										<?php
											$retorno_row = $faturamento->getTipoPagamento();
											if ($retorno_row):
												foreach ($retorno_row as $srow):

													if ($srow->exibir_crediario==1 && $srow->id_categoria!==2 && $srow->id_categoria!=4 && $srow->id_categoria!=9):
										?>
														<option value='<?php echo $srow->id;?>'><?php echo $srow->tipo;?></option>
										<?php
													endif;
												endforeach;
												unset($srow);
											endif;
										?>
									</select>
								</p>
							</div>
							<p><?php echo lang('VALOR_PAGO');?></p>
							<p>
								<input type='text' class='form-control moedap' name='valor_pago' id='valor_pago2'>
							</p>
							<p><?php echo lang('DATA_PAGAMENTO');?></p>
							<p>
								<input type='text' class='form-control data calendario' name='data_recebido' value='<?php echo date("d/m/Y");?>'>
							</p>
							<br/>
							<p>
								<div class='md-checkbox-list'>
									<div class='md-checkbox'>
										<input type='checkbox' class='md-check' name='novareceita' id='novareceita' value='1'>
										<label for='novareceita'>
										<span></span>
										<span class='check'></span>
										<span class='box'></span>
										<?php echo lang('FINANCEIRO_RECEITAGERAR');?></label>
									</div>
								</div>
							</p>
						</div>
					</div>
				</div>
				<div class='modal-footer'>
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type='button' data-dismiss='modal' class='btn default'><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm('pagarFinanceiroReceita', 'pagar_form');?>
</div>

<?php break;?>
<?php case "notafiscal":
	if(!$usuario->is_Administrativo()): print Filter::msgInfo(lang('NAOAUTORIZADO'), false); return; endif;
	$row = Core::getRowById("cadastro", Filter::$id);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><strong class="font-black"><?php echo $row->nome;?></strong></small>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><?php echo lang('NOTA_FISCAL');?></small></h1>
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
				<?php include("menucadastro.php");?>
				<div class="col-md-12">
					<div class="portlet box blue-hoki">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-file-code-o">&nbsp;&nbsp;</i><?php echo lang('NOTA_FISCAL');?>
							</div>
						</div>
						<div class='portlet-body'>
							<table class="table table-bordered table-striped table-condensed table-advance dataTable">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('DATA_NOTA');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('MODELO');?></th>
										<th><?php echo lang('OPERACAO');?></th>
										<th><?php echo lang('NUMERO_NOTA');?></th>
										<th><?php echo lang('VALOR_NOTA');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $produto->getNotaFiscalCadastro(Filter::$id);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
											$estilo = '';
											if($exrow->inativo)
												$estilo = 'class="danger"';
								?>
										<tr <?php echo $estilo;?>>
										<td><?php echo $exrow->controle;?></td>
										<td><?php echo exibedata($exrow->data_emissao);?></td>
										<td><?php echo $exrow->empresa;?></td>
										<td><?php echo modelo($exrow->modelo);?></td>
										<td><?php echo operacao($exrow->operacao);?></td>
										<td><a href="index.php?do=notafiscal&acao=visualizar&id=<?php echo $exrow->id;?>"><?php echo $exrow->numero_nota;?></a></td>
										<td><?php echo moedap($exrow->valor_nota);?></td>
									</tr>
								<?php endforeach;?>
								<?php unset($exrow);
									  endif;?>
								</tbody>
							</table>
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
<?php case 'origem':
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('ORIGEM');?></small></h1>
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
								<i class='fa fa-clipboard'>&nbsp;&nbsp;</i><?php echo lang('ORIGEM');?>
							</div>
						</div>
						<div class='portlet-body form'>
							<!-- INICIO FORM-->
							<form action='' autocomplete="off" method='post' class='form-horizontal' name='origem_form' id='origem_form'>
								<div class='form-body'>
									<div class='form-group'>
										<label class='control-label col-md-2'><?php echo lang('ORIGEM');?></label>
										<div class='col-md-6'>
											<input type='text' class='form-control caps' name='origem'>
										</div>
									</div>
								</div>
								<div class='form-actions'>
									<div class='row'>
										<div class='col-md-12'>
											<div class='col-md-6'>
												<div class='row'>
													<div class='col-md-offset-3 col-md-9'>
														<button type='submit' class='btn <?php echo $core->primeira_cor;?>'><?php echo lang('SALVAR');?></button>
													</div>
												</div>
											</div>
											<div class='col-md-6'>
											</div>
										</div>
									</div>
								</div>
							</form>
							<?php echo $core->doForm('processarOrigem', 'origem_form');?>
							<!-- FINAL FORM-->
						</div>
					</div>
				</div>
			</div>
			<!-- FINAL DO ROW FORMULARIO -->
			<!-- INICIO DO ROW TABELA -->
			<div class='row'>
				<div class='col-md-12'>
					<!-- INICIO TABELA -->
					<div class='portlet light'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-list font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('LISTAR');?></span>
							</div>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable'>
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('ORIGEM');?></th>
										<th><?php echo lang('CADASTROS');?></th>
										<th><?php echo lang('ATIVO');?></th>
										<th><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $cadastro->getTodasOrigem();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
												$quant = $cadastro->getTotalOrigem($exrow->id);
												$style = '';
												if($exrow->inativo){
													$style = 'class="bg-red"';
												}
								?>
												<tr <?php echo $style;?>>
													<td><?php echo $exrow->id;?></td>
													<td><?php echo $exrow->origem;?></td>
													<td><?php echo $quant;?></td>
													<td><?php echo ($exrow->inativo) ? lang('NAO') : lang('SIM');?></td>
													<td>
														<?php if($exrow->inativo):?>
															<a href='javascript:void(0);' class='btn btn-sm green ativarorigem' id='<?php echo $exrow->id;?>' title='<?php echo lang('ATIVAR').': '.$exrow->origem;?>'><i class='fa fa-check'></i></a>
														<?php else:?>
															<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarOrigem" title="<?php echo lang('CANCELAR').": ".$exrow->origem;?>"><i class="fa fa-ban"></i></a>
														<?php endif;?>
													</td>
												</tr>
								<?php
											endforeach;
											unset($exrow);
										endif;
								?>
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
<?php case "relacionamento":
	$id_origem = (get('id_origem')) ? get('id_origem') : $cadastro->getUltimaOrigem();
	$id_cidade = get('id_cidade');
	$id_bairro = get('id_bairro');
?>
<script type="text/javascript">

	$(document).ready(function () {
		$('#id_origem').change(function() {
			var id_origem = $("#id_origem").val();
			var id_cidade = $("#id_cidade").val();
			var id_bairro = $("#id_bairro").val();
			window.location.href = 'index.php?do=cadastro&acao=relacionamento&id_origem='+id_origem+'&id_cidade='+id_cidade+'&id_bairro='+id_bairro;
		});

		$('#id_cidade').change(function() {
			var id_origem = $("#id_origem").val();
			var id_cidade = $("#id_cidade").val();
			var id_bairro = $("#id_bairro").val();
			window.location.href = 'index.php?do=cadastro&acao=relacionamento&id_origem='+id_origem+'&id_cidade='+id_cidade+'&id_bairro='+id_bairro;
		});

		$('#id_bairro').change(function() {
			var id_origem = $("#id_origem").val();
			var id_cidade = $("#id_cidade").val();
			var id_bairro = $("#id_bairro").val();
			window.location.href = 'index.php?do=cadastro&acao=relacionamento&id_origem='+id_origem+'&id_cidade='+id_cidade+'&id_bairro='+id_bairro;
		});
	});

</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('MENU_RELACIONAMENTO');?></small></h1>
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
								<i class="fa fa-comments-o font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('MENU_RELACIONAMENTO');?></span>
							</div>
							<div class="actions">
								<a href="#oportunidade" data-toggle="modal" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-3 col-sm-12">
									<div class='row'>
										<div class='form-group'>
											<div class='col-md-12'>
												<select class='select2me form-control' id='id_origem' name='id_origem' data-placeholder='<?php echo lang('SELECIONE_ORIGEM');?>' >
													<option value=""></option>
													<?php
														$retorno_row = $cadastro->getOrigemOportunidade();
														if ($retorno_row):
															foreach ($retorno_row as $srow):
													?>
													<option value='<?php echo $srow->id;?>' <?php if($srow->id == $id_origem) echo 'selected="selected"';?>><?php echo $srow->origem;?></option>
													<?php
															endforeach;
															unset($srow);
														endif;
													?>
												</select>
											</div>
											<br><br><br>
											<div class='col-md-12'>
												<select class='select2me form-control' id='id_cidade' name='id_cidade' data-placeholder='<?php echo lang('SELECIONE_CIDADE');?>' >
													<option value=""></option>
													<?php
														$retorno_row = $cadastro->getCidade();
														if ($retorno_row):
															foreach ($retorno_row as $srow):
													?>
													<option value='<?php echo $srow->cidade;?>' <?php if($srow->cidade == $id_cidade) echo 'selected="selected"';?>><?php echo $srow->cidade;?></option>
													<?php
															endforeach;
															unset($srow);
														endif;
													?>
												</select>
											</div>
											<br><br><br>
											<div class='col-md-12'>
												<select class='select2me form-control' id='id_bairro' name='id_bairro' data-placeholder='<?php echo lang('SELECIONE_BAIRRO');?>' >
													<option value=""></option>
													<?php
														$retorno_row = $cadastro->getBairro();
														if ($retorno_row):
															foreach ($retorno_row as $srow):
													?>
													<option value='<?php echo $srow->bairro;?>' <?php if($srow->bairro == $id_bairro) echo 'selected="selected"';?>><?php echo $srow->bairro;?></option>
													<?php
															endforeach;
															unset($srow);
														endif;
													?>
												</select>
											</div>
										</div>
									</div>
									<br/>
									<div class="table-responsive">
										<table class="table table-bordered table-advance">
											<thead >
												<tr>
													<th><?php echo lang('CONTATO_ABERTO');?></th>
												</tr>
											</thead>
											<tbody>
											<?php
													$id_cadastro = $cadastro->getCadastroAberto($id_origem,$id_cidade, $id_bairro);
												if($id_cadastro):
													$row_cadastro = Core::getRowById("cadastro", $id_cadastro);
											?>
												<tr>
													<td><strong><a href="index.php?do=cadastro&acao=contato&id=<?php echo $row_cadastro->id;?>"><?php echo $row_cadastro->nome;?></a></strong></td>
												</tr>
												<?php if($row_cadastro->id_origem):?>
													<tr><td><?php echo getValue("origem", "origem", "id=".$row_cadastro->id_origem);?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->telefone):?>
													<tr><td><?php echo $row_cadastro->telefone;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->celular):?>
													<tr><td><?php echo $row_cadastro->celular;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->telefone2):?>
													<tr><td><?php echo $row_cadastro->telefone2;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->celular2):?>
													<tr><td><?php echo $row_cadastro->celular2;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->bairro):?>
													<tr><td><?php echo $row_cadastro->bairro;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->cidade):?>
													<tr><td><?php echo $row_cadastro->cidade;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->observacao):?>
													<tr><td><?php echo $row_cadastro->observacao;?></td></tr>
												<?php endif;?>
													<tr><td><?php echo lang('CADASTRO').": ".exibedataHora($row_cadastro->data_cadastro);?></td></tr>
													<tr><td><a href="javascript:void(0);" class="btn btn-block grey-cascade" onclick="javascript:void window.open('ver_cadastro.php?id=<?php echo $row_cadastro->id;?>','<?php echo $row_cadastro->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>"><i class="fa fa-search"></i></a></td></tr>
													<tr><td><a href="javascript:void(0);" class="btn btn-block grey-gallery retornocontato" id="<?php echo $row_cadastro->id;?>" nome="<?php echo $row_cadastro->nome;?>" telefone="<?php echo $row_cadastro->telefone." ".$row_cadastro->celular;?>" title="<?php echo lang('CONTATO_RETORNO');?>"><i class="fa fa-phone">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO');?></a></td></tr>
											<?php 	else:?>
												<tr>
													<td><strong><?php echo lang('MSG_ERRO_OPORTUNIDADE');?></strong></td>
												</tr>
											<?php 	endif;?>
											</tbody>
										</table>
									</div>
									<br />
									<div class="table">
										<table class="table table-bordered table-advance">
											<thead >
												<tr>
													<th><?php echo lang('CONTATO_SELECIONADOS');?></th>
												</tr>
											</thead>
											<tbody>
											<?php
													$id_cadastro = $cadastro->getCadastroSelecionado($id_origem,$id_cidade, $id_bairro);
												if($id_cadastro):
													$row_cadastro = Core::getRowById("cadastro", $id_cadastro);
											?>
												<tr>
													<td><strong><a href="index.php?do=cadastro&acao=contato&id=<?php echo $row_cadastro->id;?>"><?php echo $row_cadastro->nome;?></a></strong></td>
												</tr>
												<?php if($row_cadastro->id_origem):?>
													<tr><td><?php echo getValue("origem", "origem", "id=".$row_cadastro->id_origem);?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->telefone):?>
													<tr><td><?php echo $row_cadastro->telefone;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->celular):?>
													<tr><td><?php echo $row_cadastro->celular;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->telefone2):?>
													<tr><td><?php echo $row_cadastro->telefone2;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->celular2):?>
													<tr><td><?php echo $row_cadastro->celular2;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->bairro):?>
													<tr><td><?php echo $row_cadastro->bairro;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->cidade):?>
													<tr><td><?php echo $row_cadastro->cidade;?></td></tr>
												<?php endif;?>
												<?php if($row_cadastro->observacao):?>
													<tr><td><?php echo $row_cadastro->observacao;?></td></tr>
												<?php endif;?>
													<tr><td><?php echo lang('CADASTRO').": ".exibedataHora($row_cadastro->data_cadastro);?></td></tr>
													<tr><td><a href="javascript:void(0);" class="btn btn-block grey-cascade" onclick="javascript:void window.open('ver_cadastro.php?id=<?php echo $row_cadastro->id;?>','<?php echo $row_cadastro->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>"><i class="fa fa-search"></i></a></td></tr>
													<tr><td><a href="javascript:void(0);" class="btn btn-block grey-gallery retornocontato" id="<?php echo $row_cadastro->id;?>" nome="<?php echo $row_cadastro->nome;?>" telefone="<?php echo $row_cadastro->telefone." ".$row_cadastro->celular;?>" title="<?php echo lang('CONTATO_RETORNO');?>"><i class="fa fa-phone">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO');?></a></td></tr>
											<?php 	else:?>
												<tr>
													<td><strong><?php echo lang('MSG_ERRO_OPORTUNIDADE');?></strong></td>
												</tr>
											<?php 	endif;?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-md-9 col-sm-12">
									<div>
										<table class="table table-bordered table-condensed table-advance dataTable">
											<thead>
												<tr>
													<th>#</th>
													<th><?php echo lang('CLIENTE');?></th>
													<th><?php echo lang('CATEGORIA');?></th>
													<th><?php echo lang('TELEFONE');?></th>
													<th><?php echo lang('CIDADE')."/".lang('BAIRRO');?></th>
													<th><?php echo lang('OBSERVACAO');?></th>
													<th><?php echo lang('STATUS');?></th>
													<th><?php echo lang('RETORNO');?></th>
													<th><?php echo lang('INTERESSE');?></th>
													<th width="70px"><?php echo lang('ACOES');?></th>
												</tr>
											</thead>
											<tbody>
											<?php
													$retorno_row = $cadastro->getMeusCadastros();
													if($retorno_row):
													foreach ($retorno_row as $exrow):
													$estilo = '';
													if($exrow->data_retorno == '0000-00-00')
														$estilo = '';
													elseif($exrow->atrasado)
														$estilo = 'class="danger"';
													elseif($exrow->hoje)
														$estilo = 'class="warning"';
													elseif($exrow->agendado)
														$estilo = 'class="info"';
											?>
												<tr <?php echo $estilo;?>>
													<td><?php echo $exrow->controle;?></td>
													<td><a href="index.php?do=cadastro&acao=contato&id=<?php echo $exrow->id;?>"><?php echo $exrow->nome;?></a></td>
													<td><?php echo $exrow->categoria;?></td>
													<td><?php echo $exrow->telefone." ".$exrow->celular." ".$exrow->telefone2." ".$exrow->celular2;?></td>
													<td><?php echo $exrow->cidade."/".$exrow->bairro;?></td>
													<td><?php echo $exrow->observacao;?></td>
													<td><?php echo $exrow->status;?></td>
													<td><?php echo exibedata($exrow->data_retorno);?></td>
													<td><?php echo $exrow->interesse;?>%</td>
													<td>
														<a href="javascript:void(0);" class="btn btn-sm grey-cascade" onclick="javascript:void window.open('ver_cadastro.php?id=<?php echo $exrow->id;?>','<?php echo $exrow->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>"><i class="fa fa-search"></i></a>
														<a href="javascript:void(0);" class="btn btn-sm grey-gallery retornocontato" id="<?php echo $exrow->id;?>" nome="<?php echo $exrow->nome;?>" telefone="<?php echo $exrow->telefone." ".$exrow->celular." ".$exrow->telefone2." ".$exrow->celular2;?>" title="<?php echo lang('CONTATO_RETORNO');?>"><i class="fa fa-phone"></i></a>
													</td>
												</tr>
											<?php endforeach;?>
											<?php unset($exrow);
												  endif;?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
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
<div id="retorno-contato" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-phone">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO');?></h4>
				<h4 class="modal-title"><strong><div id="nome"><strong></div></h4>
				<h4 class="modal-title"><div id="telefone"></div></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="retorno_form" id="retorno_form" >
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p><?php echo lang('RETORNO');?></p>
							<p>
								<select class="select2me form-control" id="id_status" name="id_status" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php
										$retorno_row = $cadastro->getStatus();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
											$style = ($srow->tipo) ? "class='label-warning'" : "";
									?>
												<option <?php echo $style;?> value="<?php echo $srow->id;?>"><?php echo $srow->status;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
								</select>
							</p>
							<p><?php echo lang('DATA_RETORNO');?></p>
							<p>
								<input type="text" class="form-control data calendario" name="data_retorno">
							</p>
							<p><?php echo lang('OBSERVACAO');?></p>
							<p>
								<input type="text" class="form-control caps" name="observacao">
							</p>
							<p><?php echo lang('INTERESSE');?></p>
							<p>
								<div class="md-radio-list">
									<div class="md-radio col-md-2">
										<input type="radio" class="md-radiobtn" name="interesse" id="p1" value="20" >
										<label for="p1">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										20%</label>
									</div>
									<div class="md-radio col-md-2">
										<input type="radio" class="md-radiobtn" name="interesse" id="p2" value="40" >
										<label for="p2">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										40%</label>
									</div>
									<div class="md-radio col-md-2">
										<input type="radio" class="md-radiobtn" name="interesse" id="p3" value="60" >
										<label for="p3">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										60%</label>
									</div>
									<div class="md-radio col-md-2">
										<input type="radio" class="md-radiobtn" name="interesse" id="p4" value="80" >
										<label for="p4">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										80%</label>
									</div>
									<div class="md-radio col-md-2">
										<input type="radio" class="md-radiobtn" name="interesse" id="p5" value="100" >
										<label for="p5">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
										100%</label>
									</div>
								</div>
							</p>
						</div>
					</div>
				</div>
				<input name="relacionamento" type="hidden" value="1" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarCadastroRetorno", "retorno_form");?>
</div>

<div id="oportunidade" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('CONTATO_ADICIONAR');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="contato_form" id="contato_form"  class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('NOME');?></label>
									<div class="col-md-9">
										<input type="text" class="form-control caps"  name="nome">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CPF_CNPJ');?></label>
									<div class="col-md-9">
										<input type="text" class="form-control cpf_cnpj"  name="cpf_cnpj">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CONTATO');?></label>
									<div class="col-md-9">
										<input type="text" class="form-control caps"  name="contato">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('TELEFONE');?></label>
									<div class="col-md-9">
										<input type="text" class="form-control telefone"  name="telefone">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CELULAR');?></label>
									<div class="col-md-9">
										<input type="text" class="form-control celular"  name="celular">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('OBSERVACAO');?></label>
									<div class="col-md-9">
										<input type="text" class="form-control caps"  name="observacao">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('ORIGEM');?></label>
									<div class="col-md-9">
										<select class="select2me form-control" name="id_origem" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
											<option value=""></option>
										<?php
												$retorno_row = $cadastro->getOrigem();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>
														<option value="<?php echo $srow->id;?>"><?php echo $srow->origem;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"></label>
									<div class="col-md-9">
										<div class="md-checkbox-list">
											<div class="md-checkbox">
												<input type="checkbox" class="md-check" name="id_status" id="status" value="1" >
												<label for="status">
													<span></span>
													<span class="check"></span>
													<span class="box"></span>
													<?php echo lang('ADICIONAR_LISTA');?>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarContato", "contato_form");?>
</div>
<?php break;?>
<?php case "produtos": ?>
<?php $row = Core::getRowById("cadastro", Filter::$id);
$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y', strtotime('-7 days'));
$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y');
?>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#buscar').click(function() {
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			window.location.href = 'index.php?do=cadastro&acao=produtos&id=<?php echo Filter::$id;?>&dataini='+ dataini +'&datafim='+ datafim;
		});
	});
	// ]]>
	</script>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#imprimir_produtos').click(function() {
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			window.open('pdf_cadastro_produtos.php?id_cliente=<?php echo Filter::$id;?>&dataini='+ dataini +'&datafim='+ datafim,'<?php echo lang('PRODUTOS_VENDIDOS');?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><strong class="font-black"><?php echo $row->nome;?></strong></small>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PRODUTOS_VENDIDOS');?></small></h1>
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
				<?php include("menucadastro.php");?>
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-shopping-cart font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PRODUTOS_VENDIDOS');?></span>
							</div>
							<div class="actions btn-set">
								<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-inline">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="form-group">
													<div class="col-md-12">
														<?php echo lang('SELECIONE_PERIODO');?>:
														&nbsp;&nbsp;
														<input type="text" class="form-control input-medium calendario data" name="dataini" id="dataini" value="<?php echo $dataini;?>" >
														&nbsp;
														<input type="text" class="form-control input-medium calendario data" name="datafim" id="datafim" value="<?php echo $datafim;?>" >
														&nbsp;
														<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
														&nbsp;
														<button type="button" id="imprimir_produtos" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-print"/></i> <?php echo lang('IMPRIMIR');?></button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<table class='table table-bordered table-striped table-condensed table-advance dataTable'>
										<thead >
											<tr>
												<th><?php echo lang('PRODUTO');?></th>
												<th><?php echo lang('CODIGO');?></th>
												<th><?php echo lang('VALOR');?></th>
												<th><?php echo lang('QUANTIDADE');?></th>
												<th><?php echo lang('VALOR_TOTAL');?></th>
											</tr>
										</thead>
										<tbody>
										<?php
												$total = 0;
												$retorno_row = $cadastro->getCadastroProdutosGestao(Filter::$id, $dataini, $datafim);
												if($retorno_row):
												foreach ($retorno_row as $exrow):
													$total += $exrow->valor_total;
										?>
											<tr>
												<td><?php echo $exrow->produto;?></td>
												<td><?php echo $exrow->codigo;?></td>
												<td><?php echo moeda($exrow->valor);?></td>
												<td><?php echo decimal($exrow->quant);?></td>
												<td><?php echo moeda($exrow->valor_total);?></td>
											</tr>
										<?php endforeach;?>
										</tbody>
										<tfoot>
											<tr class="info">
												<td colspan="4"><strong><?php echo lang('TOTAL');?></strong></td>
												<td><strong><?php echo moeda($total);?></strong></td>
											</tr>
										</tfoot>
										<?php unset($exrow);
											  endif;?>
									</table>
								</div>
							</div>
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
<?php case "crediario":
	if ($core->tipo_sistema==1 || $core->tipo_sistema==3)
		redirect_to("index.php?do=cadastro&acao=editar&id=".Filter::$id);

	$data = (get('data')) ? get('data') : date("m/Y");
	$row = Core::getRowById("cadastro", Filter::$id);
	$valor_crediario = $cadastro->getTotalCrediario(Filter::$id);
	$valor_pagar = $cadastro->getPagarCrediario(Filter::$id);
	$opcao = "";
	$id_caixa = $faturamento->verificaCaixa($usuario->uid);

	if (isset($_GET['opcao'])) {
		$opcao = get('opcao');
	}
?>

<script type="text/javascript">
	$(document).ready(function () {
		$('#opcao').change(function() {
			var opcao = $("#opcao").val();
			window.location.href = 'index.php?do=cadastro&acao=crediario&opcao='+ opcao+'&id=<?php echo Filter::$id;?>';
		});
	});
</script>

<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function () {
		$('#imprimir_crediario').click(function() {
			var opcao = $("#opcao").val();
			window.open('pdf_crediario_carta.php?id_cliente=<?php echo Filter::$id;?>&opcao='+ opcao,'Imprimir Crediario','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
	});
	// ]]>
</script>

<div id="pagar-crediario" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php if ($id_caixa>0): ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title"><i class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('FAZER_PAGAMENTO');?></h4>
				</div>
				<form action="" autocomplete="off" method="post" name="pagar_form" id="pagar_form" class="form-horizontal">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">

								<span class="pull-right" style="color: #f00;"><?php echo lang('OBS_*') ?></span>
								<br><br>

								<div class="form-group">
									<label class="control-label col-md-3">
										<?php echo lang('VALOR');?>
										<span style="color: #f00">
											<?php echo lang('*') ?>
										</span>
									</label>
									<div class="col-md-9">
										<input type="text" id="valor_pagamento" class="form-control moeda" name="valor_pagamento_crediario" value="<?= moeda($valor_pagar); ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('VALOR_DESCONTO');?></label>
									<div class="col-md-9">
										<input type="text" id="valor_desconto_crediario" class="form-control moeda" name="valor_desconto_crediario" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('DATA_PAGAMENTO');?></label>
									<div class="col-md-9">
										<input type="text" class="form-control data calendario" name="data_pagamento" value="<?php echo date('d/m/Y');?>">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">
										<?php echo lang('PAGAMENTO');?>
										<span style="color: #f00">
											<?php echo lang('*') ?>
										</span>
									</label>
									<div class="col-md-9">
										<select class="select2me form-control" name="tipopagamento" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
											<option value=""></option>
										<?php
												$retorno_row = $faturamento->getTipoPagamento();
												if ($retorno_row):
													foreach ($retorno_row as $srow):
														if ($srow->exibir_crediario==1):
										?>
														<option value="<?php echo $srow->id;?>"><?php echo $srow->tipo;?></option>
										<?php
														endif;
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
						<input name="id_cliente" type="hidden" value="<?php echo Filter::$id;?>" />
						<input name="id_caixa" type="hidden" value="<?php echo $id_caixa;?>" />
						<div class="modal-footer">
							<button type="button" id="pagarcrediario" class="btn <?php echo $core->primeira_cor;?>"><?php echo lang('FAZER_PAGAMENTO');?></button>
							<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
						</div>
				</form>
			<?php else: ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('FAZER_PAGAMENTO_CAIXA_NAO');?></h4>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><strong class="font-black"><?php echo $row->nome;?></strong></small>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_CREDIARIO_FICHA');?></small></h1>
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
				<?php include("menucadastro.php");?>
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-money font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CADASTRO_CREDIARIO_FICHA');?></span>
							</div>
							<div class="actions btn-set">
							<?php if ($valor_pagar>0):
									$pagarCor='green';
									$pagarIcone='fa-usd';
									if ($id_caixa<=0) {
										$pagarCor='red';
										$pagarIcone='fa-exclamation-circle';
									}
							?>
									<a href="javascript:void(0);" id="imprimir_crediario" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_CARTA');?></a>
									<a href="#pagar-crediario" data-toggle="modal" class="btn btn-sm <?php echo $pagarCor; ?>"><i class="fa <?php echo $pagarIcone; ?>">&nbsp;&nbsp;</i><?php echo lang('FAZER_PAGAMENTO');?></a>
								<?php endif; ?>
								<a href="index.php?do=cadastro&acao=crediariopagamentos&id=<?php echo Filter::$id; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-check">&nbsp;&nbsp;</i><?php echo lang('FAZER_PAGAMENTO_REALIZADO');?></a>
							</div>
						</div>
						<div class="portlet-body form">
							<form class="form-inline">
								<div class="form-group">
									<label><?php echo lang('SELECIONE_VENDAS');?>&nbsp;&nbsp;</label>
									<select class="select2me form-control input-large" name="opcao" id="opcao" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
										<option value="0" <?php if("0" == $opcao) echo 'selected="selected"';?>><?php echo lang('NAO_PAGAS');?></option>
										<option value="1" <?php if("1" == $opcao) echo 'selected="selected"';?>><?php echo lang('PAGAS');?></option>
										<option value="" <?php if("" == $opcao) echo 'selected="selected"';?>><?php echo lang('TODAS');?></option>
									</select>
								</div>
							</form>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('CREDIARIO');?></h4>
											<i><h1 class="bold italic font-blue-madison">
													<?php
														echo moeda($row->crediario);
													?></h1></i>
										</div>
										<div class="col-md-3">
											<h4 class="bold">
												<?php echo lang('SALDO');?>
												<i
													class="fa fa-info-circle"
													title="Valor do crediario + valor que foi retornado como crédito na troca de produto: <?php echo moeda($cadastro->getVoucherCrediarioByIdCadastro($row->id)->total_voucher) ?>"
												>
												</i>
											</h4>
											<i><h1 class="bold italic font-green-seagreen">
													<?php
														echo moeda($valor_crediario);
													?></h1></i>
										</div>
										<div class="col-md-3">
											<input id="total_pagar" type="hidden" value="<?php echo moeda($valor_pagar);?>"/>
											<input id="valor_pagar" type="hidden"/>
											<h4 class="bold"><?php echo lang('VALOR_PAGAR');?></h4>
											<i><h1 class="bold italic font-red"><div id="soma_pagar_total">
													<?php echo moeda($valor_pagar);?></div></h1></i>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<form class="form-inline" action="" method="post" name="admin_form" id="admin_form">
										<table class="table table-bordered table-condensed table-advance Tbcheck">
											<thead>
												<tr>
													<th><?php echo lang('DATA');?></th>
													<th><?php echo lang('VENDA');?></th>
													<th><?php echo lang('CAIXA');?></th>
													<th><?php echo lang('OPERACAO');?></th>
													<th><?php echo lang('VALOR');?></th>
													<th><?php echo lang('VALOR_PAGO');?></th>
													<th><?php echo lang('VALOR_PAGAR');?></th>
													<th><?php echo lang('ACOES');?></th>
												</tr>
											</thead>
											<tbody>
											<?php
													$total = 0;
													$restante_total = 0;
													$total_pago = 0;
													$retorno_row = $cadastro->getClienteCrediario(Filter::$id, $opcao);
													$contar = (is_array($retorno_row)) ? count($retorno_row) : 0;
													if($retorno_row):
														foreach ($retorno_row as $exrow):
															$total += $exrow->valor;
															$total_pago += $exrow->valor_pago;
															$restante_total += ($exrow->valor-$exrow->valor_pago);
															$id_receita = 0;
															if($exrow->operacao == 1) {
																$cor = "red";
															} else {
																$id_receita = getValue('id', 'receita', 'id_pagamento='.$exrow->id.' AND id_venda = 0');
																$cor = "green-seagreen";
															}
											?>
												<tr>
													<td><?php echo exibedataHora($exrow->data_operacao);?></td>
													<td><?php echo $exrow->id_venda;?></td>
													<td><?php echo $exrow->id_caixa;?></td>
													<td><span class="label bg-<?php echo $cor;?>"><?php echo ($exrow->operacao == 1) ? lang('VENDA') : lang('PAGAMENTO');?></span></td>
													<td><span class="bold font-<?php echo $cor;?>"><?php echo moeda($exrow->valor);?><span></td>
													<td><span class="bold font-<?php echo $cor;?>"><?php echo moeda($exrow->valor_pago);?><span></td>
													<td><span class="bold font-<?php echo $cor;?>"><?php echo moeda($exrow->valor-$exrow->valor_pago);?><span></td>
													<td>
													<?php if($exrow->operacao == 2):?>
														<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_receita.php?id_receita=<?php echo $id_receita;?>','<?php echo lang('IMPRIMIR_RECIBO').$id_receita;?>','width=360,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a>
														<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarCrediario" title="<?php echo lang('APAGAR').lang('CREDIARIO');?>"><i class="fa fa-times"></i></a>
													<?php else:?>
														<a href="javascript:void(0);" onclick="javascript:void window.open('recibo.php?id=<?php echo $exrow->id_venda;?>&crediario=1','<?php echo lang('IMPRIMIR_RECIBO').$exrow->id_venda;?>','width=360,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a>
														<a href="javascript:void(0);" onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id_venda;?>','<?php echo "CODIGO: ".$exrow->id_venda;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VER_DETALHES');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
													<?php endif;?>
													</td>
												</tr>
											<?php endforeach;?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="4"><span class="bold"><?php echo lang('TOTAL');?><span></td>
													<td><span class="bold"><?php echo moeda($total);?><span></td>
													<td><span class="bold"><?php echo moeda($total_pago);?><span></td>
													<td><span class="bold"><?php echo moeda($restante_total);?><span></td>
													<td></td>
												</tr>
											</tfoot>
											<?php unset($exrow);
												  endif;?>
										</table>
									</form>
								</div>
							</div>
							<?php if($contar > 8):?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('CREDIARIO');?></h4>
											<i><h1 class="bold italic font-blue-madison">
													<?php echo moeda($row->crediario);?></h1></i>
										</div>
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('SALDO');?></h4>
											<i><h1 class="bold italic font-green-seagreen">
													<?php echo moeda($valor_crediario);?></h1></i>
										</div>
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('VALOR_PAGAR');?></h4>
											<i><h1 class="bold italic font-red"><div id="soma_pagar_total2">
													<?php echo moeda($valor_pagar);?></div></h1></i>
										</div>
										<div class="col-md-3">
										</div>
									</div>
								</div>
							</div>
							<?php endif;?>
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
<?php case "crediariopagamentos":
	if ($core->tipo_sistema==1 || $core->tipo_sistema==3)
		redirect_to("index.php?do=cadastro&acao=editar&id=".Filter::$id);

	$row = Core::getRowById("cadastro", Filter::$id);
	$valor_crediario = $cadastro->getTotalCrediario(Filter::$id);
	$valor_pagar = $cadastro->getPagarCrediario(Filter::$id);
?>

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><strong class="font-black"><?php echo $row->nome;?></strong></small>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_CREDIARIO_FICHA');?></small></h1>
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
				<?php include("menucadastro.php");?>
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-check font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('FAZER_PAGAMENTO_REALIZADO');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=cadastro&acao=crediario&opcao=0&id=<?php echo Filter::$id; ?>" class="btn btn-sm blue"><i class="fa fa-arrow-left">&nbsp;&nbsp;</i><?php echo lang('VOLTAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('CREDIARIO_PGTO_SELECIONADOS');?></h4>
											<i><h1 class="bold italic font-blue-madison pagamentos-selecionados">0</h1></i>
										</div>
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('CREDIARIO_PGTO_APAGAR_VALOR');?></h4>
											<i><h1 class="bold italic font-red valor-a-pagar-crediario">R$ 0,00</h1></i>
										</div>
										<div class="col-md-3">
											<a href="javascript:void(0);" disabled class="btn btn-xm red apagarPagamentoCrediario" id="apagarPagamentoCrediario" acao="apagarPagamentoCrediario" title="<?php echo lang('CREDIARIO_PGTO_APAGAR_BOTAO');?>"><i class="fa fa-ban">&nbsp;&nbsp</i><?php echo lang('CREDIARIO_PGTO_APAGAR_BOTAO'); ?></a>
										</div>

									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<form class="form-inline" action="" method="post" name="admin_form_apagar_crediario" id="admin_form_apagar_crediario">
										<table class="table table-bordered table-striped table-condensed table-advance dataTable-asc">
											<thead>
												<tr>
													<th><?php echo lang('#');?></th>
													<th><?php echo lang('SELECIONE');?></th>
													<th><?php echo lang('DATA_PAGAMENTO');?></th>
													<th><?php echo lang('CAIXA');?></th>
													<th><?php echo lang('VALOR_PAGO');?></th>
													<th><?php echo lang('TIPO_PAGAMENTO');?></th>
													<th><?php echo lang('OPCOES');?></th>
												</tr>
											</thead>
											<tbody>
											<?php
													$total_pago = 0;
													$retorno_row = $cadastro->getClientePagamentoCrediario(Filter::$id);
													$contar = (is_array($retorno_row)) ? count($retorno_row) : 0;
													if($retorno_row):
														foreach ($retorno_row as $exrow):
															$total_pago += $exrow->valor_pago;
															$tipoPagamento = getValue("tipo","tipo_pagamento","id=".$exrow->tipo_pagamento);
															$statusCaixaPagamento = getValue("status","caixa","id=".$exrow->id_caixa);
											?>
												<tr>
													<td><?php echo Filter::$id; ?></td>
													<td>
														<?php if ($statusCaixaPagamento<3): ?>
															<input name="id_crediario_pagamento[]" valor_pago="<?php echo $exrow->valor_pago; ?>" type="checkbox" class="checkboxes-crediario-pagamento" value="<?php echo $exrow->id;?>"/>
														<?php else: ?>
															<p><?php echo lang('CAIXA_FECHADO_MSG'); ?></p>
														<?php endif; ?>
													</td>
													<td><?php echo exibedataHora($exrow->data_pagamento);?></td>
													<td><?php echo $exrow->id_caixa;?></td>
													<td><?php echo moeda($exrow->valor_pago);?></td>
													<td><?php echo $tipoPagamento;?></td>
													<td><a href="javascript:void(0);" onclick="javascript:void window.open('recibo_crediario.php?id=<?php echo $exrow->id;?>','<?php echo lang('IMPRIMIR_RECIBO').$exrow->id;?>','width=360,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a></td>
												</tr>
											<?php endforeach;?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="4"><span class="bold"><?php echo lang('TOTAL');?><span></td>
													<td><span class="bold"><?php echo moeda($total_pago);?><span></td>
													<td colspan="2"></td>
												</tr>
											</tfoot>
											<?php unset($exrow);
												  endif;?>
										</table>
										<input name="id_cadastro" type="hidden" value="<?php echo Filter::$id; ?>" />
										<input name="valor_a_cancelar" id="valor_a_cancelar" type="hidden" />
									</form>
								</div>
							</div>
							<?php if($contar > 8):?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('CREDIARIO');?></h4>
											<i><h1 class="bold italic font-blue-madison">
													<?php echo moeda($row->crediario);?></h1></i>
										</div>
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('SALDO');?></h4>
											<i><h1 class="bold italic font-green-seagreen">
													<?php echo moeda($valor_crediario);?></h1></i>
										</div>
										<div class="col-md-3">
											<h4 class="bold"><?php echo lang('VALOR_PAGAR');?></h4>
											<i><h1 class="bold italic font-red"><div id="soma_pagar_total2">
													<?php echo moeda($valor_pagar);?></div></h1></i>
										</div>
										<div class="col-md-3">
										</div>
									</div>
								</div>
							</div>
							<?php endif;?>
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
<?php case "historico": ?>
<?php $row = Core::getRowById("cadastro", Filter::$id);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><strong class="font-black"><?php echo $row->nome;?></strong></small>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('CADASTRO_HISTORICO');?></small></h1>
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
				<?php include("menucadastro.php");?>
				<div class="col-md-12">
					<!-- INICIO TABELA -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-history font-<?php echo $core->primeira_cor;?>"></i>
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CADASTRO_HISTORICO');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=cadastro&acao=buscar" class="btn default"><?php echo lang('VOLTAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<h4><i class="fa fa-shopping-cart font-<?php echo $core->primeira_cor;?>"></i>&nbsp;&nbsp;<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CADASTRO_VENDAS');?></span></h4>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<table class="table table-bordered table-striped table-condensed table-advance">
										<thead >
											<tr>
												<th><?php echo lang('COD_VENDA');?></th>
												<th><?php echo lang('VALOR_TOTAL');?></th>
												<th><?php echo lang('VALOR_DESCONTO');?></th>
												<th><?php echo lang('VALOR_PAGO');?></th>
												<th><?php echo lang('DATA_VENDA');?></th>
												<th><?php echo lang('STATUS');?></th>
												<th><?php echo lang('USUARIO');?></th>
												<th><?php echo lang('ACOES');?></th>
											</tr>
										</thead>
										<tbody>
										<?php
												$total = 0;
												$desconto = 0;
												$pago = 0;
												$retorno_row = $cadastro->getVendas(Filter::$id);
												if($retorno_row):
												foreach ($retorno_row as $exrow):
													$pagamentoCrediario = $cadastro->existePagamentoCrediario($exrow->id);
													$style = '';
													$status = '';
													$cor_fiscal = ($exrow->fiscal) ? 'green' : 'purple';
													if($exrow->inativo == 1) {
														$style = 'class="danger"';
														$status = 'CANCELADA';
													} elseif($exrow->pago == 1) {
														$style = 'class="success"';
														$status = 'FINALIZADA';
														$total += $exrow->valor_total;
														$desconto += $exrow->valor_desconto;
														$pago += $exrow->valor_pago;
													} else {
														$style = 'class="info"';
														$status = 'ABERTA';
														$total += $exrow->valor_total;
														$desconto += $exrow->valor_desconto;
														$pago += $exrow->valor_pago;
													}
										?>
											<tr <?php echo $style;?>>
												<td><?php echo $exrow->id;?></td>
												<td><?php echo moedap($exrow->valor_total);?></td>
												<td><?php echo moedap($exrow->valor_desconto);?></td>
												<td>
													<?php
														// echo $exrow->id_cad_crediario == 0 || $exrow->id_cad_crediario == "" || $exrow->id_cad_crediario == null ? moedap($exrow->valor_pago)." (Utilizado o crédito da troca)" : moedap($exrow->valor_pago);
														echo moedap($exrow->valor_pago);
													?>
												</td>
												<td><?php echo exibedataHora($exrow->data_venda);?></td>
												<td><?php echo $status;?></td>
												<td><?php echo $exrow->usuario;?></td>
												<td width="200px">
													<?php if(!$exrow->inativo and $exrow->pago==1 and !$exrow->fiscal):?>
														<a href="javascript:void(0);" onclick="javascript:void window.open('recibo.php?id=<?php echo $exrow->id;?>','<?php echo lang('IMPRIMIR_RECIBO').$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a>
													<?php endif;?>
													<?php if($usuario->is_nfc()):?>
														<?php if ($exrow->status_enotas=="Autorizada" && !$exrow->contingencia): ?>
																<a href="<?php echo $exrow->link_danfe;?>" title="<?php echo lang('IMPRIMIR_NFC');?>" class="btn btn-sm <?php echo $cor_fiscal;?>"><i class="fa fa-file-text-o"></i></a>
														<?php endif;?>
													<?php endif;?>
													<?php if(!$exrow->inativo):?>
														<a href="javascript:void(0);" onclick="javascript:void window.open('pdf_romaneio.php?id=<?php echo $exrow->id;?>','<?php echo "CODIGO: ".$exrow->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VER_ROMANEIO');?>" class="btn btn-sm yellow-gold"><i class="fa fa-truck"></i></a>
													<?php endif;?>
													<a href="javascript:void(0);" onclick="javascript:void window.open('imprimir_vendas.php?id=<?php echo $exrow->id;?>','<?php echo "CODIGO: ".$exrow->id;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VER_DETALHES');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
													<?php if ($pagamentoCrediario && $pagamentoCrediario>0): ?>
															<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_promissorias.php?id_venda=<?php echo $exrow->id;?>&id_receita=0','<?php echo lang('IMPRIMIR_RECIBO_PROMISSORIAS').': '.$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO_PROMISSORIAS');?>" class="btn btn-sm yellow btn-fiscal"><i class="fa fa-list-alt"></i></a>
													<?php endif; ?>
													<?php if($exrow->inativo && $exrow->link_danfe):?>
														<a href="<?php echo $exrow->link_danfe;?>" title="<?php echo lang('NOTA_FISCAL_DANFE');?>" class="btn btn-sm <?php echo $cor_fiscal;?>"><i class="fa fa-file-pdf-o"></i></a>
													<?php endif;?>
													<?php if($usuario->is_Administrativo()):?>
														<?php if(!$exrow->inativo and $exrow->pago != 1):?>
															<a href="index.php?do=vendas&acao=finalizarvenda&id=<?php echo $exrow->id;?>" class="btn btn-sm <?php echo $core->primeira_cor;?>" title="<?php echo lang('IR_PARA').": ".$exrow->id;?>"><i class="fa fa-share"></i></a>
														<?php endif;?>
													<?php endif;?>
												</td>
											</tr>
										<?php endforeach;?>
											<tr>
												<td></td>
												<td><strong><?php echo moedap($total);?></strong></td>
												<td><strong><?php echo moedap($desconto);?></strong></td>
												<td><strong><?php echo moedap($pago);?></strong></td>
												<td colspan="4"></td>
											</tr>
										<?php unset($exrow);
											  endif;?>
										</tbody>
									</table>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-sm-12">
									<h4><i class="fa fa-money font-<?php echo $core->primeira_cor;?>"></i>&nbsp;&nbsp;<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CADASTRO_PAGAMENTO');?></span></h4>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12">
									<table class="table table-bordered table-striped table-condensed table-advance dataTable-desc">
										<thead >
											<tr>
												<th>#</th>
												<th><?php echo lang('DATA_VENCIMENTO');?></th>
												<th><?php echo lang('BANCO');?></th>
												<th><?php echo lang('DESCRICAO');?></th>
												<th width="70px"><?php echo lang('VALOR');?></th>
												<th><?php echo lang('DATA_PAGAMENTO');?></th>
												<th><?php echo lang('DATA_LANCAMENTO');?></th>
												<th><?php echo lang('PLANO_CONTAS');?></th>
												<th><?php echo lang('PAGO');?></th>
												<th><?php echo lang('ACOES');?></th>
											</tr>
										</thead>
										<tbody>
										<?php
											$total = 0;
											$descricao = '';
											$retorno_row = $cadastro->getReceitasCadastro(Filter::$id);
											if($retorno_row):
												foreach ($retorno_row as $exrow):

												$total += $exrow->valor_pago;
												$id_pai = getValue("id_pai", "conta", "id = ".$exrow->id_conta);

												if(trim($exrow->descricao) != ''){
													$descricao = $exrow->descricao;
												} elseif($exrow->tipo == 0){
													$descricao = 'TRANSFERENCIA ENTRE BANCO';
												} elseif($exrow->tipo == 1) {
													$descricao = $exrow->pagamento.' - CAIXA ID:'.$exrow->id_caixa;
												} else {
													$nome = ($exrow->id_cliente) ? ' - '.getValue("nome","cadastro","id = '".$exrow->id_cliente."'") : "";
													$descricao = $exrow->pagamento.$nome;
												}
												$pago = '';
												if($exrow->pago == 1) {
													$pago = "<span class='label label-sm bg-green'>".lang('SIM')."</span>";
												} else {
													$pago = "<span class='label label-sm bg-red'>".lang('NAO')."</span>";
												}
										?>
											<tr>
												<td><?php echo $exrow->controle;?></td>
												<td><?php echo exibedataHora($exrow->data_pagamento);?></td>
												<td><?php echo ($exrow->banco);?></td>
												<td><?php echo $descricao;?></td>
												<td><?php echo moeda($exrow->valor_pago);?></td>
												<td><?php echo exibedata($exrow->data_recebido);?></td>
												<td><?php echo exibedatahora($exrow->data);?></td>
												<td><?php echo $exrow->conta;?></td>
												<td><?php echo $pago;?></td>
												<td>
													<?php if($exrow->pago==1 && $exrow->crediario==0):?>
														<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_receita.php?id_receita=<?php echo $exrow->id;?>','<?php echo lang('IMPRIMIR_RECIBO').$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a>
													<?php elseif($exrow->id_categoria==1): ?>
														<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_receita.php?pgto_dnhr=<?php echo $exrow->data.$exrow->id_caixa;?>&id_cadastro=<?php echo $exrow->id_cadastro;?>','<?php echo lang('IMPRIMIR_RECIBO').$exrow->id;?>','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('IMPRIMIR_RECIBO');?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-file-o"></i></a>
													<?php endif;?>
												</td>
											</tr>
										<?php endforeach;?>
										</tbody>
										<tfoot>
											<tr class="info">
												<td colspan="4"><strong><?php echo lang('TOTAL');?></strong></td>
												<td><strong><?php echo moeda($total);?></strong></td>
												<td></td>
												<td></td>
												<td></td>
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
<?php case "arquivocontatos":?>
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
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('CONTATO_ARQUIVOS');?></small></h1></small></h1></small></h1>
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
								<div class="panel panel-info">
									<div class="panel-heading">
										<h3 class="panel-title"><?php echo lang('CONTATO_ARQUIVOS');?></h3>
									</div>
									<div class="panel-body">
										<?php echo lang('CONTATO_ARQUIVOS_DESCRICAO');?>
									</div>
								</div>
								<div class="plupload"></div>
								<input name="processarArquivoContato" type="hidden" value="1" />
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
<?php case 'listacomercial':
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;&nbsp;<small><?php echo lang('LISTA_COLABORADORES');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW TABELA -->
			<div class='row'>
				<div class='col-md-12'>
					<!-- INICIO TABELA -->
					<div class='portlet light'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-list font-<?php echo $core->primeira_cor;?>'></i>
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('LISTA_COLABORADORES');?></span>
							</div>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance dataTable'>
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('NOME');?></th>
										<th><?php echo lang('USUARIO');?></th>
										<th><?php echo lang('LOGIN');?></th>
										<th><?php echo lang('ENDERECO_IP');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $cadastro->getUsuariosComercial();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
								?>
												<tr>
													<td><?php echo $exrow->id;?></td>
													<td><?php echo $exrow->nome;?></td>
													<td><?php echo $exrow->usuario;?></td>
													<td><?php echo $exrow->lastlogin;?></td>
													<td><?php echo $exrow->lastip;?></td>
												</tr>
								<?php
											endforeach;
											unset($exrow);
										endif;
								?>
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
<?php case "importar_cliente_fornecedor"; ?>
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
				<h1><?php echo lang('IMPORTACAO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('IMPORTAR_CLIENTES_FORNECEDORES');?></small></h1>
			</div>
			<!-- END PAGE TITLE -->
		</div>
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<div class="portlet light">
				<a href="excel/Importacao_Cliente_Fornecedor.xlsx" download>
					<h4>
						<strong>
							<?php echo lang('DOWNLOAD_ARQUIVO_IMPORTACAO');?>
						</strong>
					</h4>
				</a>
				<br>
				<div class="help-block">
					<h5><?php echo lang('OBSERVACAO');?>:</h5>
					<h5>1- <?php echo lang('OBSERVACAO_IMPORTACAO_OBRIGATORIEDADE');?></h5>
					<h5>2- <?php echo lang('CPFCNPJ_IGUAL_IMPORTACAO');?></h5>
				</div>
				<form action="" autocomplete="off" class="form-inline" method="post" id="admin_form" name="admin_form" >
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-info">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-file-excel-o">&nbsp;&nbsp;</i><?php echo lang('IMPORTAR_CLIENTES_FORNECEDORES');?></h3>
									</div>
									<div class="panel-body">
										<?php echo lang('IMPORTAR_CLIENTES_OBS');?>
										<div class="plupload"></div>
									</div>
								</div>
								<input name="processarPlanilhaClienteFornecedorImportacao" type="hidden" value="1" />
							</div>
						</div>
					</div>
				</form>
					<?php if (!empty($_SESSION['erros_importacao'])): ?>
						<!-- CSS LOCALIZAÇÃO -> LAYOUT.CSS no ASSETS -->
						<!-- Botão flutuante -->
						<button id="btnVisualizarErros" class="btn btn-danger">
							Visualizar Erros
						</button>
						<!-- Fundo escurecido -->
						<div id="popupOverlay">
							<!-- Popup de erros -->
							<div id="popupErros">
								<!-- Botão de fechar -->
								<button id="fecharPopupErros" title="Fechar">&times;</button>
								<button id="btnBaixarArquivoErros" class="btn btn-success" style="position: absolute; font-size: 16px; top: 10px; right: 10px; z-index: 10;">
				     				⬇ Baixar Arquivo de Erros
				  				</button>
								<h3>Erros encontrados:</h3>
								<ul>
									<?php foreach ($_SESSION['erros_importacao'] as $erro): ?>
									<li><?= strip_tags("- " . $erro, '<b>') ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
						<!-- Script para controle do popup -->
						<script>
							document.addEventListener("DOMContentLoaded", function () {
								const btn = document.getElementById("btnVisualizarErros");
								const overlay = document.getElementById("popupOverlay");
								const closeBtn = document.getElementById("fecharPopupErros");
								const popup = document.getElementById("popupErros");

								btn.addEventListener("click", () => overlay.style.display = "block");
								closeBtn.addEventListener("click", () => overlay.style.display = "none");

								document.addEventListener("keydown", (e) => {
									if (e.key === "Escape") overlay.style.display = "none";
								});

								overlay.addEventListener("click", (e) => {
									if (!popup.contains(e.target)) {
									overlay.style.display = "none";
									}
								});
							});
						</script>
						<script>
							const errosImportacao = <?php echo json_encode($_SESSION['erros_importacao'] ?? []); ?>;
							document.getElementById("btnBaixarArquivoErros").addEventListener("click", function () {
								const titulo = "Por favor, corrija os seguintes erros para importar os Clientes ou Fornecedores com sucesso:\n\n";
								const corpo = errosImportacao.map(erro => "- " + erro.replace(/<\/?b>/gi, "")).join("\n");
								const conteudo = titulo + corpo;

								const blob = new Blob([conteudo], { type: "text/plain;charset=utf-8" });
								const url = URL.createObjectURL(blob);

								const link = document.createElement("a");
								link.href = url;
								link.download = "erros_importacao.txt";
								link.click();

								URL.revokeObjectURL(url); // Libera a memória
							});
						</script>

						<?php unset($_SESSION['erros_importacao']); ?>
						<?php endif; ?>
			</div>
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->
	</div>
	<!-- END PAGE CONTAINER -->

<?php break;?>
<?php case "ordemservico":
	$id_empresa = $_SESSION['idempresa'];
	$tipo_sistema = getValue("tipo_sistema","empresa","id=".$id_empresa);
	$row = Core::getRowById("cadastro", Filter::$id);
	if($tipo_sistema<>5): 
		print Filter::msgInfo(lang('NAOAUTORIZADO'), false); 
		return; 
	endif;
	$ordens_servico = $ordem_servico->getOrdensServicoCadastro(Filter::$id);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CADASTRO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><strong class="font-black"><?php echo $row->nome;?></strong></small>&nbsp;&nbsp;<i class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><?php echo lang('ORDEM_SERVICO_LISTAR');?></small></h1>
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
				<?php include("menucadastro.php");?>
				<div class="col-md-12">
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-wrench">&nbsp;&nbsp;</i><?php echo lang('ORDEM_SERVICO_LISTAR');?>
							</div>
						</div>
						<div class='portlet-body'>
							<?php if ($ordens_servico): ?>
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
										foreach ($ordens_servico as $exrow):
								?>
									<tr>
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
								?>
								</tbody>
							</table>
							<?php else: ?>
								<div class="alert alert-info"><?php echo lang('ORDEM_SERVICO_CADASTRO_NAO'); ?></div>
							<?php endif; ?>	
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
<div id='pagar-receita' class='modal fade' tabindex='-1'>
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'></button>
				<h4 class='modal-title'><?php echo lang('PAGAR');?></h4>
			</div>
			<form action='' autocomplete="off" method='post' name='pagar_form' id='pagar_form' >
				<div class='modal-body'>
					<div class='row'>
						<div class='col-md-12'>
							<p><?php echo lang('BANCO');?></p>
							<p>
								<select class='select2me form-control' id='id_banco2' name='id_banco' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
									<option value=""></option>
									<?php
										$retorno_row = $faturamento->getBancos();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value='<?php echo $srow->id;?>'><?php echo $srow->banco;?></option>
									<?php
											endforeach;
											unset($srow);
										endif;
									?>
								</select>
							</p>
							<div class="ocultar" id="tipo_pagamento_crediario_dv">
								<p><?php echo lang('PAGAMENTO');?></p>
								<p>
									<select class='select2me form-control' name='tipo_pagamento_crediario' id='tipo_pagamento_crediario' data-placeholder='<?php echo lang('SELECIONE_OPCAO');?>' >
										<option value=""></option>
										<?php
											$retorno_row = $faturamento->getTipoPagamento();
											if ($retorno_row):
												foreach ($retorno_row as $srow):

													if ($srow->exibir_crediario==1 && $srow->id_categoria!==2 && $srow->id_categoria!=4 && $srow->id_categoria!=9):
										?>
														<option value='<?php echo $srow->id;?>'><?php echo $srow->tipo;?></option>
										<?php
													endif;
												endforeach;
												unset($srow);
											endif;
										?>
									</select>
								</p>
							</div>
							<p><?php echo lang('VALOR_PAGO');?></p>
							<p>
								<input type='text' class='form-control moedap' name='valor_pago' id='valor_pago2'>
							</p>
							<p><?php echo lang('DATA_PAGAMENTO');?></p>
							<p>
								<input type='text' class='form-control data calendario' name='data_recebido' value='<?php echo date("d/m/Y");?>'>
							</p>
							<br/>
							<p>
								<div class='md-checkbox-list'>
									<div class='md-checkbox'>
										<input type='checkbox' class='md-check' name='novareceita' id='novareceita' value='1'>
										<label for='novareceita'>
										<span></span>
										<span class='check'></span>
										<span class='box'></span>
										<?php echo lang('FINANCEIRO_RECEITAGERAR');?></label>
									</div>
								</div>
							</p>
						</div>
					</div>
				</div>
				<div class='modal-footer'>
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type='button' data-dismiss='modal' class='btn default'><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm('pagarFinanceiroReceita', 'pagar_form');?>
</div>
<?php break;?>
<?php default: ?>
<div class="imagem-fundo">
	<img src="assets/img/logo.png" border="0">
</div>
<?php break;?>
<?php endswitch;?>
<?php if (isset($_GET["id_venda"])):
		$id_venda = get('id_venda');
?>
    <script>
        function getStatus () {
            if (!window.Notification) {
                return "unsupported";
            }
            return window.Notification.permission;
        }

        // get permission Promise
        function getPermission () {
            return new Promise((resolve, reject) => {
                Notification.requestPermission(status => {
                    var status = getStatus();
                    if (status == 'granted') {
                        resolve();
                    }else{
                        reject(status);
                    }
                });
            });
        };

		getPermission()
			.then(function(){
				var n = new Notification("Venda realizada", {
					body: "Código da venda: <?php print $id_venda;?>\nFAVOR DIRIGIR AO CAIXA \nPARA FINALIZAR A VENDA!"
				});
			}).catch(function(status){
				console.log('Had no permission!');
			});
    </script>
<?php endif;?>