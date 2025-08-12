<?php
  /**
   * Editar usuario
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Todos())
	  redirect_to("login.php");
?>

<?php $row = Core::getRowById("usuario", Filter::$id);?>
<div id="novo-onibus" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><?php echo lang('ONIBUS_ADICIONAR');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="onibus_form" id="onibus_form" >
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p><?php echo lang('EMPRESA');?></p>
							<p>	
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
							</p>
							<p><?php echo lang('LINHA');?></p>
							<p>
								<select class="select2me form-control" name="id_onibus" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
									<option value=""></option>
									<?php 
										$retorno_row = $onibus->getOnibus();
										if ($retorno_row):
											foreach ($retorno_row as $srow):
									?>
												<option value="<?php echo $srow->id;?>"><?php echo $srow->linha;?></option>
									<?php
											endforeach;
											unset($srow);
											endif;
									?>
								</select>
							</p>
						</div>
					</div>
				</div>
				<input name="id_usuario" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="submit" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarUsuarioOnibus", "onibus_form");?>
</div>
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
														<label class="control-label col-md-3"><?php echo lang('USUARIO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" name="usuario" value="<?php echo $row->usuario;?>">
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
														<label class="control-label col-md-3"><?php echo lang('PIN');?></label>
														<div class="col-md-9">
															<input type="password" class="form-control inteiro" name="pin">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CONFIRMA_PIN');?></label>
														<div class="col-md-9">
															<input type="password" class="form-control inteiro" name="confirma_pin">
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
														<label class="control-label col-md-3"><?php echo lang('IDENTIDADE');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="identidade" value="<?php echo $row->identidade;?>">
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
															<input type="text" class="form-control caps data" name="aniversario" value="<?php echo exibedata($row->aniversario);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ENDERECO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="endereco" value="<?php echo $row->endereco;?>">
														</div>
													</div>
												</div>
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
														<button type="submit" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm("editarUsuario");?>	
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