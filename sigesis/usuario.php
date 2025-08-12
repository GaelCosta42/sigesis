<?php
  /**
   * Usuario
   *
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Administrativo())
	  redirect_to("login.php");
?>
<?php switch(Filter::$acao): case "editar": ?>
<?php $row = Core::getRowById("usuario", Filter::$id);?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('USUARIO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EDITAR');?></small></h1>
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
								<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('USUARIO_EDITAR');?>
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
														<label class="control-label col-md-3"><?php echo lang('NOME');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="nome" value="<?php echo $row->nome;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('USUARIO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="usuario" value="<?php echo $row->usuario;?>">
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
																	$retorno_row = $empresa->getEmpresasTodas();
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
														<label class="control-label col-md-3"><?php echo lang('SENHA');?></label>
														<div class="col-md-9">
															<input type="password" class="form-control" name="senha">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CONFIRMA_SENHA');?></label>
														<div class="col-md-9">
															<input type="password" class="form-control" name="confirma">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('IDENTIDADE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="identidade" value="<?php echo $row->identidade;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('TITULO_ELEITOR');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="eleitor" value="<?php echo $row->eleitor;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('NOME_MAE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="nome_mae" value="<?php echo $row->nome_mae;?>">
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
														<label class="control-label col-md-3"><?php echo lang('CELULAR');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps celular" name="telefone" value="<?php echo $row->telefone;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_NASC');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps data calendario" name="aniversario" <?php echo ($row->aniversario!='0000-00-00') ? 'value="'.exibedata($row->aniversario).'"':'';?> >
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ESCOLARIDADE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="escolaridade" value="<?php echo $row->escolaridade;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CEP');?></label>
														<div class="col-md-9">
															<div class="input-group">
															<input type="text" class="form-control cep" name="cep" id="cep" value="<?php echo $row->cep;?>">
																<span class="input-group-btn">
																<button id="cepbusca" class="btn <?php echo $core->primeira_cor;?>" type="button"><i class="fa fa-arrow-left fa-fw"/></i> <?php echo lang('BUSCAR_END');?></button>
																</span>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ENDERECO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="endereco" id="endereco" value="<?php echo $row->endereco;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('NUMERO');?></label>
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
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BAIRRO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="bairro" id="bairro" value="<?php echo $row->bairro;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CIDADE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="cidade" id="cidade" value="<?php echo $row->cidade;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ESTADO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps uf" name="estado" id="estado" value="<?php echo $row->estado;?>">
														</div>
													</div>
												</div>	
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CARGO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="cargo" value="<?php echo $row->cargo;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BANCO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="banco" value="<?php echo $row->banco;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CODIGO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="codigo" value="<?php echo $row->codigo;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('AGENCIA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="agencia" value="<?php echo $row->agencia;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CONTA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="conta" value="<?php echo $row->conta;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_tabela_ponto" data-placeholder="<?php echo lang('SELECIONE_HORARIO_PONTO');?>" >
																<option value=""></option>
																<?php 
																	$retorno_row = $pontoeletronico->getTabelasDePonto();
																	if ($retorno_row):
																		foreach ($retorno_row as $prow):
																?>
																			<option value="<?php echo $prow->id;?>" <?php if($prow->id == $row->id_tabela_ponto) echo 'selected="selected"';?>><?php echo $prow->titulo.' - '.$prow->hora_total;?></option>
																<?php
																		endforeach;
																		unset($prow);
																		endif;
																?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CPF');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps cpf" name="cpf" value="<?php echo $row->cpf;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CTPS');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="ctps" value="<?php echo $row->ctps;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PIS');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="pis" value="<?php echo $row->pis;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('SALARIO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moeda" name="salario" value="<?php echo  moeda($row->salario);;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_ADMISSAO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps data calendario" name="data_admissao" <?php echo ($row->data_admissao!='0000-00-00') ? 'value="'.exibedata($row->data_admissao).'"':'';?>>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label"><?php echo lang('TRANSPORTE');?></label>
														<div class="col-md-9">
															<div class="input-icon right">
																<i class="fa">%</i>
																<input type="text" class="form-control decimal" name="transporte" value="<?php echo decimal($row->transporte);?>" placeholder="<?php echo lang('PERCENTUAL');?>">
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label"><?php echo lang('INSALUBRIDADE');?></label>
														<div class="col-md-9">
															<div class="input-icon right">
																<i class="fa">%</i>
																<input type="text" class="form-control decimal" name="insalubridade" value="<?php echo decimal($row->insalubridade);?>" placeholder="<?php echo lang('PERCENTUAL');?>">
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ABONO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moeda" name="abono" value="<?php echo moeda($row->abono);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('LANCHE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moeda" name="lanche" value="<?php echo moeda($row->lanche);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PLANO_SAUDE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moeda" name="planodesaude" value="<?php echo moeda($row->planodesaude);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PLANO_SAUDE_DEPENDENTE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moeda" name="planodependente" value="<?php echo moeda($row->planodependente);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PLANO_SAUDE_EXTRA');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moeda" name="planoextra" value="<?php echo moeda($row->planoextra);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('BONUS_REMUNERACOES');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control moeda" name="bonus" value="<?php echo moeda($row->bonus);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('SABADOS');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control inteiro" name="sabado" value="<?php echo $row->sabado;?>">
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
														<label class="control-label col-md-3"><?php echo lang('CARTEIRA');?></label>
														<div class="col-md-9">
															<div class="md-checkbox-list">
																<div class="md-checkbox">
																	<input type="checkbox" class="md-check" name="carteira" id="carteira" value="1" <?php if($row->carteira) echo 'checked';?>>
																	<label for="carteira">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('CARTEIRA_ASSINADA');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PROLABORE');?></label>
														<div class="col-md-9">
															<div class="md-checkbox-list">
																<div class="md-checkbox">
																	<input type="checkbox" class="md-check" name="prolabore" id="prolabore" value="1" <?php if($row->prolabore) echo 'checked';?>>
																	<label for="prolabore">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('RECEBE_PROLABORE');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ADIANTAMENTO_SALARIAL');?></label>
														<div class="col-md-9">
															<div class="md-checkbox-list">
																<div class="md-checkbox">
																	<input type="checkbox" class="md-check" name="adiantamento" id="adiantamento"  value="1" <?php if($row->adiantamento) echo 'checked';?>>
																	<label for="adiantamento">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('SIM_DIA20');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VENDEDOR');?></label>
														<div class="col-md-9">
															<div class="md-checkbox-list">
																<div class="md-checkbox">
																	<input type="checkbox" class="md-check" name="vendedor" id="vendedor" value="1" <?php if($row->vendedor) echo 'checked';?>>
																	<label for="vendedor">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('COMISSAO_VENDEDOR');?></label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="col-md-3 control-label"><?php echo lang('PERCENTUAL');?></label>
														<div class="col-md-9">
															<div class="input-icon right">
																<i class="fa">%</i>
																<input type="text" class="form-control decimal" name="percentual" value="<?php echo decimal($row->percentual);?>" placeholder="<?php echo lang('PERCENTUAL');?>">
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('NIVEL');?></label>
														<div class="col-md-9">
															<div class="md-radio-list">
																<?php if($usuario->is_Master()):?>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel8" value="8" <?php getChecked($row->nivel, 8); ?>>
																	<label for="nivel8">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('MASTER');?></label>
																</div>
																<?php endif;?>
																<?php if($usuario->is_Gerencia()):?>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel7" value="7" <?php getChecked($row->nivel, 7); ?>>
																	<label for="nivel7">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('GERENCIA_FINANCEIRO');?></label>
																</div>
																<?php endif;?>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel6" value="6" <?php getChecked($row->nivel, 6); ?>>
																	<label for="nivel6">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('ADMINISTRATIVO');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel5" value="5" <?php getChecked($row->nivel, 5); ?>>
																	<label for="nivel5">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('COMERCIAL');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel3" value="3" <?php getChecked($row->nivel, 3); ?>>
																	<label for="nivel3">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('ATENDIMENTO');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel2" value="2" <?php getChecked($row->nivel, 2); ?>>
																	<label for="nivel2">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('COLABORADOR');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel1" value="1" <?php getChecked($row->nivel, 1); ?>>
																	<label for="nivel1">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('CONTABILIDADE');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel0" value="0" <?php getChecked($row->nivel, 0); ?>>
																	<label for="nivel0">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('SEM_ACESSO');?></label>
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
							<?php echo $core->doForm("processarUsuario");?>	
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
				<h1><?php echo lang('USUARIO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ADICIONAR');?></small></h1>
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
								<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?>
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
														<label class="control-label col-md-3"><?php echo lang('NOME');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="nome">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('USUARIO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="usuario">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('SENHA');?></label>
														<div class="col-md-9">
															<input type="password" class="form-control" name="senha">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CONFIRMA_SENHA');?></label>
														<div class="col-md-9">
															<input type="password" class="form-control" name="confirma">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('EMPRESA');?></label>
														<div class="col-md-9">
															<select class="select2me form-control" name="id_empresa" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																<option value=""></option>
																<?php 
																	$retorno_row = $empresa->getEmpresasTodas();
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
														<label class="control-label col-md-3"><?php echo lang('NIVEL');?></label>
														<div class="col-md-9">
															<div class="md-radio-list">
																<?php if($usuario->is_Master()):?>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel8" value="8" >
																	<label for="nivel8">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('MASTER');?></label>
																</div>
																<?php endif;?>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel7" value="7" >
																	<label for="nivel7">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('GERENCIA_FINANCEIRO');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel6" value="6" >
																	<label for="nivel6">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('ADMINISTRATIVO');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel5" value="5" >
																	<label for="nivel5">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('COMERCIAL');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel3" value="3" >
																	<label for="nivel3">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('ATENDIMENTO');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel2" value="2" >
																	<label for="nivel2">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('COLABORADOR');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel1" value="1" >
																	<label for="nivel1">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('CONTABILIDADE');?></label>
																</div>
																<div class="md-radio">
																	<input type="radio" class="md-radiobtn" name="nivel" id="nivel0" value="0" >
																	<label for="nivel0">
																	<span></span>
																	<span class="check"></span>
																	<span class="box"></span>
																	<?php echo lang('SEM_ACESSO');?></label>
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
							<?php echo $core->doForm("processarUsuario");?>	
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
				<h1><?php echo lang('USUARIO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('LISTAR');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=usuario&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable">
								<thead>
									<tr>
										<th><?php echo lang('NOME');?></th>
										<th><?php echo lang('USUARIO');?></th>
										<th><?php echo lang('EMPRESA');?></th>
										<th><?php echo lang('NIVEL');?></th>
										<th><?php echo lang('LOGIN');?></th>
										<th><?php echo lang('STATUS');?></th>
										<th><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $usuario->getUsuarios();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
												if($exrow->nivel <= 8):
								?>
												<tr>
													<td><a href="index.php?do=usuario&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->nome;?></a></td>
													<td><?php echo $exrow->usuario;?></td>
													<td><?php echo $exrow->empresa;?></td>
													<td><?php echo nivel($exrow->nivel);?></td>
													<td><?php echo exibedataHora($exrow->login);?></td>
													<td><?php echo ($exrow->active == 'y') ? "<span class='label label-sm bg-green'>ATIVO</span>" : "<span class='label label-sm bg-red'>BLOQUEADO</span>";?></td>
													<td>
														<?php if($exrow->active == 'y'): ?>
														<?php if($exrow->nivel == 8 and $usuario->is_Master()): ?>
															<a href="javascript:void(0);" class="btn btn-sm yellow bloquear" id="<?php echo $exrow->id;?>" acao="apagarUsuario" title="<?php echo lang('BLOQUEAR').": ".$exrow->nome;?>"><i class="fa fa-minus"></i></a>
															<a href="index.php?do=usuario&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->nome;?>"><i class="fa fa-pencil"></i></a>
														<?php elseif($exrow->nivel == 7 and $usuario->is_Gerencia()): ?>
															<a href="javascript:void(0);" class="btn btn-sm yellow bloquear" id="<?php echo $exrow->id;?>" acao="apagarUsuario" title="<?php echo lang('BLOQUEAR').": ".$exrow->nome;?>"><i class="fa fa-minus"></i></a>
															<a href="index.php?do=usuario&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->nome;?>"><i class="fa fa-pencil"></i></a>
														<?php elseif($exrow->nivel < 7): ?>
															<a href="javascript:void(0);" class="btn btn-sm yellow bloquear" id="<?php echo $exrow->id;?>" acao="apagarUsuario" title="<?php echo lang('BLOQUEAR').": ".$exrow->nome;?>"><i class="fa fa-minus"></i></a>
															<a href="index.php?do=usuario&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->nome;?>"><i class="fa fa-pencil"></i></a>
														<?php endif; ?>
														<?php else: ?>
															<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarUsuario" title="<?php echo lang('USUARIO_APAGAR').$exrow->nome;?>"><i class="fa fa-times"></i></a>
															<a href="javascript:void(0);" class="btn btn-sm green ativar" id="<?php echo $exrow->id;?>" title="<?php echo lang('ATIVAR').": ".$exrow->nome;?>"><i class="fa fa-check"></i></a>
														<?php endif; ?>
													</td>
												</tr>
								<?php 
												endif;
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
<?php case "bloqueados": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('USUARIO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('LISTAR');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=usuario&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable dataTable-tools">
								<thead>
									<tr>
										<th><?php echo lang('NOME');?></th>
										<th><?php echo lang('USUARIO');?></th>
										<th><?php echo lang('NIVEL');?></th>
										<th><?php echo lang('LOGIN');?></th>
										<th><?php echo lang('STATUS');?></th>
										<th><?php echo lang('ACOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $usuario->getBloqueadosUsuarios();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
								?>
												<tr>
													<td><a href="index.php?do=usuario&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->nome;?></a></td>
													<td><?php echo $exrow->usuario;?></td>
													<td><?php echo nivel($exrow->nivel);?></td>
													<td><?php echo exibedataHora($exrow->login);?></td>
													<td><?php echo ($exrow->active == 'y') ? "<span class='label label-sm bg-green'>ATIVO</span>" : "<span class='label label-sm bg-red'>BLOQUEADO</span>";?></td>
													<td>
														<?php if($exrow->active == 'y'): ?>
															<a href="javascript:void(0);" class="btn btn-sm yellow apagar" id="<?php echo $exrow->id;?>" acao="apagarUsuario" title="<?php echo lang('BLOQUEAR').": ".$exrow->nome;?>"><i class="fa fa-minus"></i></a>
															<a href="index.php?do=usuario&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->nome;?>"><i class="fa fa-pencil"></i></a>
														<?php else: ?>
															<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarUsuario" title="<?php echo lang('USUARIO_APAGAR').$exrow->nome;?>"><i class="fa fa-times"></i></a>
															<a href="javascript:void(0);" class="btn btn-sm green ativar" id="<?php echo $exrow->id;?>" title="<?php echo lang('ATIVAR').": ".$exrow->nome;?>"><i class="fa fa-check"></i></a>
														<?php endif; ?>
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
<?php default: ?>
<div class="page-container">
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<div class="imagem-fundo">
						<img src="assets/img/bg-white.png" border="0">
					</div>
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php endswitch;?>