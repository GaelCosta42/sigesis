<?php
/**
 * Empresa
 *
 */
if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe nao e permitido.');
if (!$usuario->is_Administrativo())
	redirect_to("login.php");
?>
<?php switch (Filter::$acao):
	case "editar": ?>
		<?php $row = Core::getRowById("empresa", Filter::$id); ?>
		<!-- Plupload -->
		<link href="./assets/plugins/plupload/css/jquery.plupload.queue.css" rel="stylesheet" type="text/css" />
		<link href="./assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="./assets/plugins/plupload/plupload.full.js"></script>
		<script type="text/javascript" src="./assets/plugins/plupload/jquery.plupload.queue.js"></script>
		<script type="text/javascript" src="./assets/plugins/plupload/i18n/pt_BR.js"></script>
		<script type="text/javascript" src="./assets/scripts/fileupload_logo.js"></script>
		<script type="text/javascript" src="./assets/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
		<script type="text/javascript" src="./assets/scripts/fileupload_logo_pdv.js"></script>
		<script>
			jQuery(document).ready(function () {
				FormFileUpload.init();
			});
		</script>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('EMPRESA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EMPRESA_EDITAR'); ?></small></h1>
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
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('EMPRESA_EDITAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form" enctype="multipart/form-data">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NOME'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="nome"
																		value="<?php echo $row->nome; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('RAZAO_SOCIAL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="razao_social"
																		value="<?php echo $row->razao_social; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('RESPONSAVEL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="responsavel"
																		value="<?php echo $row->responsavel; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('SIGLA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="sigla"
																		value="<?php echo $row->sigla; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('EMAIL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="email"
																		value="<?php echo $row->email; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CELULAR'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control telefone"
																		name="celular" value="<?php echo $row->celular; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TELEFONE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control telefone"
																		name="telefone" value="<?php echo $row->telefone; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ISS_ALIQUOTA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal"
																		name="aliquota_iss"
																		value="<?php echo decimal($row->iss_aliquota); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEST'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control" name="cest"
																		maxlength="7" value="<?php echo $row->cest; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS_ST'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal"
																		name="icms_st_aliquota"
																		value="<?php echo decimal($row->icms_st_aliquota); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal"
																		name="icms_normal_aliquota"
																		value="<?php echo decimal($row->icms_normal_aliquota); ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_MVA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal" name="mva"
																		value="<?php echo decimal($row->mva); ?>">
																</div>
															</div>
														</div>
														<hr>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PERFIL_EMPRESA'); ?></label>
																<div class="col-md-9">
																	<div class="md-radio-list">
																		<div class="md-radio">
																			<input type="radio" class="md-radiobtn"
																				name="perfil_empresa" id="perfilSimples"
																				value="SN" <?php getChecked($row->perfil_empresa, "SN"); ?>>
																			<label for="perfilSimples">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PERFIL_EMPRESA_SIMPLES'); ?></label>
																		</div>
																		<div class="md-radio">
																			<input type="radio" class="md-radiobtn"
																				name="perfil_empresa" id="perfilA" value="A"
																				<?php getChecked($row->perfil_empresa, "A"); ?>>
																			<label for="perfilA">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PERFIL_EMPRESA_A'); ?></label>
																		</div>
																		<div class="md-radio">
																			<input type="radio" class="md-radiobtn"
																				name="perfil_empresa" id="perfilB" value="B"
																				<?php getChecked($row->perfil_empresa, "B"); ?>>
																			<label for="perfilB">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PERFIL_EMPRESA_B'); ?></label>
																		</div>
																		<div class="md-radio">
																			<input type="radio" class="md-radiobtn"
																				name="perfil_empresa" id="perfilC" value="C"
																				<?php getChecked($row->perfil_empresa, "C"); ?>>
																			<label for="perfilC">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PERFIL_EMPRESA_C'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<hr>
														<?php if ($usuario->is_Controller()): ?>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('PERFIL_EMISSAO'); ?></label>
																	<div class="col-md-9">
																		<div class="md-radio-list">
																			<div class="md-radio">
																				<input type="radio" class="md-radiobtn"
																					name="perfil_emissao" id="emissao_producao"
																					value="1" <?php getChecked($row->emissor_producao, "1"); ?>>
																				<label for="emissao_producao">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PERFIL_EMISSAO_P'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" class="md-radiobtn"
																					name="perfil_emissao" id="emissao_homologacao"
																					value="0" <?php getChecked($row->emissor_producao, "0"); ?>>
																				<label for="emissao_homologacao">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('PERFIL_EMISSAO_H'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
																<hr>
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3">Calibagem da
																			Balança</label>
																		<div class="col-md-9">
																			<div class="md-radio-list">
																				<div class="md-radio">
																					<input type="radio" class="md-radiobtn"
																						name="calibragem_balanca"
																						id="calibragem_balanca_valor" value="1"
																						<?php getChecked($row->calibragem_balanca, "1"); ?>>
																					<label for="calibragem_balanca_valor">
																						<span></span>
																						<span class="check"></span>
																						<span class="box"></span>
																						Por Valor</label>
																				</div>
																				<div class="md-radio">
																					<input type="radio" class="md-radiobtn"
																						name="calibragem_balanca"
																						id="calibragem_balanca_peso" value="0" <?php getChecked($row->calibragem_balanca, "0"); ?>>
																					<label for="calibragem_balanca_peso">
																						<span></span>
																						<span class="check"></span>
																						<span class="box"></span>
																						Por Peso</label>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<hr>
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VERSAO_EMISSAO'); ?></label>
																	<div class="col-md-9">
																		<select class="select2me form-control" name="versao_emissao"
																			id="versao_emissao"
																			data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																			<option value=""></option>
																			<option <?= ($row->versao_emissao === '1') ? 'selected' : ''; ?> value="1">1</option>
																			<option <?= ($row->versao_emissao === '2') ? 'selected' : ''; ?> value="2">2</option>
																			<option <?= ($row->versao_emissao === '3') ? 'selected' : ''; ?> value="3">3</option>
																			<option <?= ($row->versao_emissao === '4') ? 'selected' : ''; ?> value="4">4</option>
																			<option <?= ($row->versao_emissao === '5') ? 'selected' : ''; ?> value="5">5</option>
																			<option <?= ($row->versao_emissao === '6') ? 'selected' : ''; ?> value="6">6</option>
																			<option <?= ($row->versao_emissao === '7') ? 'selected' : ''; ?> value="7">7</option>
																			<option <?= ($row->versao_emissao === '8') ? 'selected' : ''; ?> value="8">8</option>
																			<option <?= ($row->versao_emissao === '9') ? 'selected' : ''; ?> value="9">9</option>
																			<option <?= ($row->versao_emissao === '10') ? 'selected' : ''; ?> value="10">10</option>
																		</select>
																	</div>
																</div>
															</div>
														<?php else: ?>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('PERFIL_EMISSAO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" readonly class="form-control"
																			value="<?php echo ($row->emissor_producao == "1") ? lang('PERFIL_EMISSAO_P') : lang('PERFIL_EMISSAO_H'); ?>">
																	</div>
																</div>
															</div>
															<hr>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('VERSAO_EMISSAO'); ?></label>
																	<div class="col-md-9">
																		<input type="text" readonly class="form-control"
																			value="<?php echo ($row->versao_emissao) ? $row->versao_emissao : ""; ?>">
																	</div>
																</div>
															</div>
														<?php endif; ?>
														<hr>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PRODUTO_ATUALIZAR_PRECO'); ?></label>
																<div class="col-md-9">
																	<input type="checkbox" <?php if ($row->atualizar_valor_produto)
																		echo 'checked'; ?>
																		name="atualizar_valor_produto" class="make-switch"
																		data-on-color="success" data-off-color="danger"
																		data-on-text="<?php echo lang('SIM'); ?>"
																		data-off-text="<?php echo lang('NAO'); ?>" value="1">
																</div>
															</div>
														</div>
														<?php if ($usuario->is_Master()): ?>
															<hr>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('MOSTRAR_VENDAS_DIA_VENDEDOR'); ?></label>
																	<div class="col-md-9">
																		<input type="checkbox" <?php if ($row->mostrar_vendas_dia_vendedor)
																			echo 'checked'; ?>
																			name="mostrar_vendas_dia_vendedor" class="make-switch"
																			data-on-color="success" data-off-color="danger"
																			data-on-text="<?php echo lang('SIM'); ?>"
																			data-off-text="<?php echo lang('NAO'); ?>" value="1">
																	</div>
																</div>
															</div>
															<hr>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('BLOQUEAR_CANCELAMENTO_PRODUTO_VENDA'); ?></label>
																	<div class="col-md-9">
																		<input type="checkbox" <?php if ($row->modal_cancelar_produto_venda)
																			echo 'checked'; ?>
																			name="modal_cancelar_produto_venda"
																			id="modal_cancelar_produto_venda" class="make-switch"
																			data-on-color="success" data-off-color="danger"
																			data-on-text="<?php echo lang('SIM'); ?>"
																			data-off-text="<?php echo lang('NAO'); ?>" value="1">
																	</div>
																</div>
															</div>
															<hr>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('BLOQUEAR_ALTERAR_VALOR_PRODUTO_VENDA'); ?></label>
																	<div class="col-md-9">
																		<input type="checkbox" <?php if ($row->modal_alterar_valor_produto_venda)
																			echo 'checked'; ?> name="modal_alterar_valor_produto_venda"
																			id="modal_alterar_valor_produto_venda"
																			class="make-switch" data-on-color="success"
																			data-off-color="danger"
																			data-on-text="<?php echo lang('SIM'); ?>"
																			data-off-text="<?php echo lang('NAO'); ?>" value="1">
																	</div>
																</div>
															</div>
															<hr>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('BLOQUEAR_ALTERAR_VALOR_CREDIARIO'); ?></label>
																	<div class="col-md-9">
																		<input type="checkbox" <?php if ($row->alterar_valor_crediario)
																			echo 'checked'; ?>
																			name="alterar_valor_crediario"
																			id="alterar_valor_crediario" class="make-switch"
																			data-on-color="success" data-off-color="danger"
																			data-on-text="<?php echo lang('SIM'); ?>"
																			data-off-text="<?php echo lang('NAO'); ?>" value="1">
																	</div>
																</div>
															</div>
														<?php endif; ?>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<?php if ($usuario->is_Controller()): ?>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('ENOTAS_ID'); ?></label>
																	<div class="col-md-9">
																		<input type="text" class="form-control" name="enotas"
																			value="<?php echo $row->enotas; ?>">
																	</div>
																</div>
															</div>
														<?php endif; ?>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CNPJ'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control cpf_cnpj" name="cnpj"
																		value="<?php echo $row->cnpj; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEP'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cep" name="cep"
																			id="cep" value="<?php echo $row->cep; ?>">
																		<span class="input-group-btn">
																			<button id="cepbusca"
																				class="btn <?php echo $core->primeira_cor; ?>"
																				type="button"><i
																					class="fa fa-arrow-left fa-fw" /></i>
																				<?php echo lang('BUSCAR_END'); ?></button>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ENDERECO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="endereco"
																		id="endereco" value="<?php echo $row->endereco; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="numero"
																		id="numero" value="<?php echo $row->numero; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('COMPLEMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="complemento"
																		value="<?php echo $row->complemento; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('BAIRRO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="bairro"
																		id="bairro" value="<?php echo $row->bairro; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="cidade"
																		id="cidade" value="<?php echo $row->cidade; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTADO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps uf"
																		name="estado" id="estado"
																		value="<?php echo $row->estado; ?>">
																</div>
															</div>
														</div>
														<?php if ($usuario->is_Controller()): ?>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check" name="nfc"
																					id="nfc" value="1" <?php if ($row->nfc)
																						echo 'checked'; ?>>
																				<label for="nfc">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('FISCAL_NFC'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="venda_aberto" id="venda_aberto" value="1"
																					<?php if ($row->venda_aberto)
																						echo 'checked'; ?>>
																				<label for="venda_aberto">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('ABERTO_VENDAS'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="orcamento" id="orcamento" value="1" <?php if ($row->orcamento)
																						echo 'checked'; ?>>
																				<label for="orcamento">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('HABILITAR_ORCAMENTO'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="modulo_impressao" id="modulo_impressao"
																					value="1" <?php if ($row->modulo_impressao)
																						echo 'checked'; ?>>
																				<label for="modulo_impressao">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('MODULO_ETIQUETAS'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="app_vendas" id="app_vendas" value="1"
																					<?php if ($row->app_vendas)
																						echo 'checked'; ?>>
																				<label for="app_vendas">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('MODULO_APP_VENDAS'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="crediario_app" id="crediario_app"
																					value="1" <?php if ($row->crediario_app)
																						echo 'checked'; ?>>
																				<label for="crediario_app">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('MODULO_CREDIARIO_APP'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="desconto_app" id="desconto_app" value="1"
																					<?php if ($row->desconto_app)
																						echo 'checked'; ?>>
																				<label for="desconto_app">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('MODULO_DESCONTO_APP'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="cadastro_app" id="cadastro_app" value="1"
																					<?php if ($row->cadastro_app)
																						echo 'checked'; ?>>
																				<label for="cadastro_app">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('MODULO_CADASTRO_APP'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="ordernar_valor_app"
																					id="ordernar_valor_app" value="1" <?php if ($row->ordernar_valor_app)
																						echo 'checked'; ?>>
																				<label for="ordernar_valor_app">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('MODULO_ORDERNAR_VALOR_APP'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="modulo_integracao" id="modulo_integracao"
																					value="1" <?php if ($row->modulo_integracao)
																						echo 'checked'; ?>>
																				<label for="modulo_integracao">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('MODULO_INTEGRACAO'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="aplicativo_estoque"
																					id="aplicativo_estoque" value="1" <?php if ($row->aplicativo_estoque)
																						echo 'checked'; ?>>
																				<label for="aplicativo_estoque">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('APLICATIVO_ESTOQUE'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="modulo_ponto" id="modulo_ponto" value="1"
																					<?php if ($row->modulo_ponto)
																						echo 'checked'; ?>>
																				<label for="modulo_ponto">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('MODULO_PONTO_ELETRONICO'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="modulo_emissao_boleto"
																					id="modulo_emissao_boleto" value="1" <?php if ($row->modulo_emissao_boleto)
																						echo 'checked'; ?>>
																				<label for="modulo_emissao_boleto">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('MODULO_EMISSAO_BOLETO'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<div class="md-checkbox-list">
																			<div class="md-checkbox col-md-12">
																				<input type="checkbox" class="md-check"
																					name="modulo_integracao_ecommerce"
																					id="modulo_integracao_ecommerce" value="1" <?php if ($row->modulo_integracao_ecommerce)
																						echo 'checked'; ?>>
																				<label for="modulo_integracao_ecommerce">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('MODULO_INTEGRACAO_ECOMMERCE'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('TIPO_SISTEMA'); ?></label>
																	<div class="col-md-9">
																		<div class="md-radio-list">
																			<div class="md-radio">
																				<input type="radio" class="md-radiobtn"
																					name="tipo_sistema" id="n1_gestaoemissor"
																					value="0" <?php getChecked($row->tipo_sistema, 0); ?>>
																				<label for="n1_gestaoemissor">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_N1'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" class="md-radiobtn"
																					name="tipo_sistema" id="e1_emissor" value="1"
																					<?php getChecked($row->tipo_sistema, 1); ?>>
																				<label for="e1_emissor">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_E1'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" class="md-radiobtn"
																					name="tipo_sistema" id="g1_gestao" value="2"
																					<?php getChecked($row->tipo_sistema, 2); ?>>
																				<label for="g1_gestao">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_G1'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" class="md-radiobtn"
																					name="tipo_sistema" id="emissor" value="3" <?php getChecked($row->tipo_sistema, 3); ?>>
																				<label for="emissor">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_EMISSOR'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" class="md-radiobtn"
																					name="tipo_sistema" id="n2_pedidos" value="4"
																					<?php getChecked($row->tipo_sistema, 4); ?>>
																				<label for="n2_pedidos">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_N2PEDIDOS'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" class="md-radiobtn"
																					name="tipo_sistema" id="n2_ordemservico"
																					value="5" <?php getChecked($row->tipo_sistema, 5); ?>>
																				<label for="n2_ordemservico">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_N2OS'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														<?php else: ?>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<?php echo lang('FISCAL_NFC'); ?>
																		<span
																			class="label label-<?php echo ($row->nfc) ? 'success' : 'default'; ?>"><?php echo ($row->nfc) ? lang('SIM') : lang('NAO'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<?php echo lang('ABERTO_VENDAS'); ?>
																		<span
																			class="label label-<?php echo ($row->venda_aberto) ? 'success' : 'default'; ?>"><?php echo ($row->venda_aberto) ? lang('SIM') : lang('NAO'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<?php echo lang('HABILITAR_ORCAMENTO'); ?>
																		<span
																			class="label label-<?php echo ($row->orcamento) ? 'success' : 'default'; ?>"><?php echo ($row->orcamento) ? lang('SIM') : lang('NAO'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<?php echo lang('MODULO_ETIQUETAS'); ?>
																		<span
																			class="label label-<?php echo ($row->modulo_impressao) ? 'success' : 'default'; ?>"><?php echo ($row->modulo_impressao) ? lang('SIM') : lang('NAO'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<?php echo lang('MODULO_APP_VENDAS'); ?>
																		<span
																			class="label label-<?php echo ($row->app_vendas) ? 'success' : 'default'; ?>"><?php echo ($row->app_vendas) ? lang('SIM') : lang('NAO'); ?></span>
																	</div>
																</div>
															</div>
															<?php if ($row->app_vendas): ?>
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3"></label>
																		<div class="col-md-9">
																			<?php echo lang('MODULO_CREDIARIO_APP'); ?>
																			<span
																				class="label label-<?php echo ($row->crediario_app) ? 'success' : 'default'; ?>"><?php echo ($row->crediario_app) ? lang('SIM') : lang('NAO'); ?></span>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3"></label>
																		<div class="col-md-9">
																			<?php echo lang('MODULO_DESCONTO_APP'); ?>
																			<span
																				class="label label-<?php echo ($row->desconto_app) ? 'success' : 'default'; ?>"><?php echo ($row->desconto_app) ? lang('SIM') : lang('NAO'); ?></span>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3"></label>
																		<div class="col-md-9">
																			<?php echo lang('MODULO_CADASTRO_APP'); ?>
																			<span
																				class="label label-<?php echo ($row->cadastro_app) ? 'success' : 'default'; ?>"><?php echo ($row->cadastro_app) ? lang('SIM') : lang('NAO'); ?></span>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3"></label>
																		<div class="col-md-9">
																			<?php echo lang('MODULO_ORDERNAR_VALOR_APP'); ?>
																			<span
																				class="label label-<?php echo ($row->ordernar_valor_app) ? 'success' : 'default'; ?>"><?php echo ($row->ordernar_valor_app) ? lang('SIM') : lang('NAO'); ?></span>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3"></label>
																		<div class="col-md-9">
																			<div class="md-checkbox-list">
																				<div class="md-checkbox col-md-12">
																					<input type="checkbox" class="md-check"
																						name="modulo_integracao_ecommerce"
																						id="modulo_integracao_ecommerce" value="1" <?php if ($row->modulo_integracao_ecommerce)
																							echo 'checked'; ?>>
																					<label for="modulo_integracao_ecommerce">
																						<span></span>
																						<span class="check"></span>
																						<span class="box"></span>
																						<?php echo lang('MODULO_INTEGRACAO_ECOMMERCE'); ?></label>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															<?php endif; ?>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<?php echo lang('MODULO_INTEGRACAO'); ?>
																		<span
																			class="label label-<?php echo ($row->modulo_integracao) ? 'success' : 'default'; ?>"><?php echo ($row->modulo_integracao) ? lang('SIM') : lang('NAO'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<?php echo lang('APLICATIVO_ESTOQUE'); ?>
																		<span
																			class="label label-<?php echo ($row->aplicativo_estoque) ? 'success' : 'default'; ?>"><?php echo ($row->aplicativo_estoque) ? lang('SIM') : lang('NAO'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<?php echo lang('MODULO_PONTO_ELETRONICO'); ?>
																		<span
																			class="label label-<?php echo ($row->modulo_ponto) ? 'success' : 'default'; ?>"><?php echo ($row->modulo_ponto) ? lang('SIM') : lang('NAO'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"></label>
																	<div class="col-md-9">
																		<?php echo lang('MODULO_EMISSAO_BOLETO'); ?>
																		<span
																			class="label label-<?php echo ($row->modulo_emissao_boleto) ? 'success' : 'default'; ?>"><?php echo ($row->modulo_emissao_boleto) ? lang('SIM') : lang('NAO'); ?></span>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('TIPO_SISTEMA'); ?></label>
																	<div class="col-md-9">
																		<div class="md-radio-list">
																			<div class="md-radio">
																				<input type="radio" disabled class="md-radiobtn"
																					value="0" <?php getChecked($row->tipo_sistema, 0); ?>>
																				<label for="n1_gestaoemissor">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_N1'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" disabled class="md-radiobtn"
																					value="1" <?php getChecked($row->tipo_sistema, 1); ?>>
																				<label for="e1_emissor">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_E1'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" disabled class="md-radiobtn"
																					value="2" <?php getChecked($row->tipo_sistema, 2); ?>>
																				<label for="g1_gestao">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_G1'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" disabled class="md-radiobtn"
																					value="3" <?php getChecked($row->tipo_sistema, 3); ?>>
																				<label for="emissor">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_EMISSOR'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" disabled class="md-radiobtn"
																					value="4" <?php getChecked($row->tipo_sistema, 4); ?>>
																				<label for="n2_pedidos">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_N2PEDIDOS'); ?></label>
																			</div>
																			<div class="md-radio">
																				<input type="radio" disabled class="md-radiobtn"
																					value="5" <?php getChecked($row->tipo_sistema, 5); ?>>
																				<label for="n2_ordemservico">
																					<span></span>
																					<span class="check"></span>
																					<span class="box"></span>
																					<?php echo lang('TIPO_SISTEMA_N2OS'); ?></label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														<?php endif; ?>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12">
													<br><br>
												</div>
												<div class="col-md-12">
													<h4 class="form-section">
														<strong><?php echo lang('CREDIARIO_INFORMACOES'); ?></strong>
													</h4>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CREDIARIO_MULTA'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">
																			<i">%</i>
																		</span>
																		<input type="text" class="form-control decimal"
																			placeholder="<?php echo lang('CREDIARIO_MULTA_INFO'); ?>"
																			name="multa_crediario"
																			value="<?php echo decimal($row->multa_crediario); ?>">
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CREDIARIO_JUROS'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">
																			<i">%</i>
																		</span>
																		<input type="text" class="form-control decimal"
																			placeholder="<?php echo lang('CREDIARIO_JUROS_INFO'); ?>"
																			name="juros_crediario"
																			value="<?php echo decimal($row->juros_crediario); ?>">
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CREDIARIO_TOLERANCIA'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<span class="input-group-addon">
																			<i">Dias</i>
																		</span>
																		<input type="text" class="form-control inteiro"
																			placeholder="<?php echo lang('CREDIARIO_TOLERANCIA_INFO'); ?>"
																			name="tolerancia_crediario"
																			value="<?php echo $row->tolerancia_crediario; ?>">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-10"
																	style="padding-left:30px;text-align:left"><?php echo lang('CREDIARIO_MULTA_INFO'); ?></label>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-10"
																	style="padding-left:30px;text-align:left;padding-top:24px;"><?php echo lang('CREDIARIO_JUROS_INFO'); ?></label>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label class="control-label col-md-10"
																	style="padding-left:30px;text-align:left;padding-top: 15px;"><?php echo lang('CREDIARIO_TOLERANCIA_INFO'); ?></label>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12">
													<br><br>
												</div>
												<div class="col-md-12">
													<h4 class="form-section">
														<strong><?php echo lang('CONTABILIDADE_INFO'); ?></strong>
													</h4>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_NOME'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_nome"
																		value="<?php echo $row->contabilidade_nome; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_RAZAO_SOCIAL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_razao_social"
																		value="<?php echo $row->contabilidade_razao_social; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CONTATO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_nome_contato"
																		value="<?php echo $row->contabilidade_nome_contato; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CNPJ'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control cpf_cnpj"
																		name="contabilidade_cnpj"
																		value="<?php echo $row->contabilidade_cnpj; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_TELEFONE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control telefone"
																		name="contabilidade_telefone"
																		value="<?php echo $row->contabilidade_telefone; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_EMAIL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control"
																		name="contabilidade_email"
																		value="<?php echo $row->contabilidade_email; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CPF_CONTADOR'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control cpf"
																		name="contabilidade_cpf_contador"
																		value="<?php echo $row->contabilidade_cpf_contador; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CRC'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control"
																		name="contabilidade_crc_contador"
																		value="<?php echo $row->contabilidade_crc_contador; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CNPJ_CONTADOR'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control cpf_cnpj"
																		name="cnpj_contador"
																		value="<?php echo $row->cnpj_contador; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CEP'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cep"
																			name="contabilidade_cep" id="contabilidade_cep"
																			value="<?php echo $row->contabilidade_cep; ?>">
																		<span class="input-group-btn">
																			<button id="cepbuscacontador"
																				class="btn <?php echo $core->primeira_cor; ?>"
																				type="button"><i
																					class="fa fa-arrow-left fa-fw" /></i>
																				<?php echo lang('BUSCAR_END'); ?></button>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_ENDERECO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_endereco"
																		id="contabilidade_endereco"
																		value="<?php echo $row->contabilidade_endereco; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_NUMERO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_numero" id="contabilidade_numero"
																		value="<?php echo $row->contabilidade_numero; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_COMPLEMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_complemento"
																		value="<?php echo $row->contabilidade_complemento; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_BAIRRO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_bairro" id="contabilidade_bairro"
																		value="<?php echo $row->contabilidade_bairro; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_cidade" id="contabilidade_cidade"
																		value="<?php echo $row->contabilidade_cidade; ?>">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_ESTADO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps uf"
																		maxlength="2" name="contabilidade_estado"
																		id="contabilidade_estado"
																		value="<?php echo $row->contabilidade_estado; ?>">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
											</div>
										</div>
										<input name="id" type="hidden" value="<?php echo Filter::$id; ?>" />
										<input name="usuario_controle" type="hidden"
											value="<?php echo $usuario->is_Controller(); ?>" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<button type="submit"
																	class="btn <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
																<button type="button" id="voltar"
																	class="btn default"><?php echo lang('VOLTAR'); ?></button>
															</div>
														</div>
													</div>
													<div class="col-md-6">
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm("processarEmpresa"); ?>
									<!-- FINAL FORM-->
								</div>
							</div>
						</div>
					</div>
					<!-- FINAL DO ROW FORMULARIO -->
					<?php if ($usuario->is_Master()): ?>
						<div class="row">
							<div class="col-md-12">
								<div class="portlet box <?php echo $core->primeira_cor; ?>">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-cloud-upload">&nbsp;&nbsp;</i><?php echo lang('LOGOMARCA_PDV'); ?>
										</div>
									</div>
									<div class="portlet-body form">
										<!-- INICIO FORM-->
										<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
											id="admin_form" enctype="multipart/form-data">
											<div class="form-body">

												<div class="row">
													<div class="col-md-12">
														<div class="panel">
															<div class="panel-body">
																<i style="color: gray;">Coloque aqui a logomarca da empresa que irá
																	para o PDV (Vendas > Nova venda).</i>
																<span class="help-block" style="font-size: 11px;">Obs.: Somente uma
																	imagem por vez. (Tamanho 210x90)</span>

																<div class="plupload"></div>
															</div>
														</div>

														<input name="processarLogoPdv" type="hidden" value="1" />
														<input name="id_empresa" type="hidden" value="<?php echo Filter::$id; ?>">
													</div>
												</div>

											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<!-- FINAL DOS MODULOS DA PAGINA -->
		</div>
		<!-- FINAL CONTEUDO DA PAGINA -->
		<?php break; ?>
	<?php case "arquivo": ?>
		<?php $row = Core::getRowById("empresa", Filter::$id); ?>
		<!-- Plupload -->
		<link href="./assets/plugins/plupload/css/jquery.plupload.queue.css" rel="stylesheet" type="text/css" />
		<link href="./assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="./assets/plugins/plupload/plupload.full.js"></script>
		<script type="text/javascript" src="./assets/plugins/plupload/jquery.plupload.queue.js"></script>
		<script type="text/javascript" src="./assets/plugins/plupload/i18n/pt_BR.js"></script>
		<script type="text/javascript" src="./assets/scripts/fileupload_logo.js"></script>
		<script type="text/javascript" src="./assets/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
		<script>
			jQuery(document).ready(function () {
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
						<h1><?php echo lang('EMPRESA_TITULO'); ?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><strong
									class="font-black"><?php echo $row->nome; ?></strong></small>&nbsp;&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;&nbsp;<small><?php echo lang('EMPRESA_LOGO_TITULO'); ?></small>
						</h1>
					</div>
					<!-- END PAGE TITLE -->
				</div>
			</div>
			<!-- END PAGE HEAD -->
			<!-- BEGIN PAGE CONTENT -->
			<div class="page-content">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<!-- BEGIN PAGE CONTENT INNER -->
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-cloud-upload font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('EMPRESA_LOGO_TITULO'); ?></span>
									</div>
								</div>
								<form action="" autocomplete="off" class="form-inline" method="post" id="admin_form"
									name="admin_form">
									<div class="portlet-body">
										<div class="row">
											<div class="col-md-12">
												<div class="panel">
													<div class="panel-body">
														<?php echo lang('EMPRESA_LOGO_DESCRICAO'); ?>
													</div>
												</div>
												<div class="plupload"></div>
												<input name="processarEmpresaLogo" type="hidden" value="1" />
												<input name="id_empresa" type="hidden" value="<?php echo Filter::$id; ?>" />
											</div>
										</div>
									</div>
									<div class="portlet-body">
										<div class="table-scrollable table-scrollable-borderless">
											<table class="table table-bordered table-hover table-light">
												<tbody>
													<tr>
														<td><img src="./tcpdf/img/logo.png" /></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</form>
							</div>
							<!-- END PAGE CONTENT INNER -->
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT -->
		</div>
		<!-- END PAGE CONTAINER -->
		<?php break; ?>
	<?php case "adicionar":
		if (!$usuario->is_Controller())
			redirect_to("login.php");
		?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('EMPRESA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EMPRESA_ADICIONAR'); ?></small></h1>
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
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('EMPRESA_ADICIONAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-12">
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NOME'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="nome">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('RAZAO_SOCIAL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="razao_social">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('RESPONSAVEL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="responsavel">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('SIGLA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="sigla">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('EMAIL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="email">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ISS_ALIQUOTA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal"
																		name="iss_aliquota">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEST'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control" name="cest"
																		maxlength="7">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS_ST'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal"
																		name="icms_st_aliquota">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_ICMS'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal"
																		name="icms_normal_aliquota">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ALIQUOTA_MVA'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control decimal" name="mva">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('PERFIL_EMPRESA'); ?></label>
																<div class="col-md-9">
																	<div class="md-radio-list">
																		<div class="md-radio">
																			<input type="radio" class="md-radiobtn"
																				name="perfil_empresa" id="perfilSimples"
																				value="SN" <?php getChecked(1, 1); ?>>
																			<label for="perfilSimples">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PERFIL_EMPRESA_SIMPLES'); ?></label>
																		</div>
																		<div class="md-radio">
																			<input type="radio" class="md-radiobtn"
																				name="perfil_empresa" id="perfilA" value="A">
																			<label for="perfilA">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PERFIL_EMPRESA_A'); ?></label>
																		</div>
																		<div class="md-radio">
																			<input type="radio" class="md-radiobtn"
																				name="perfil_empresa" id="perfilB" value="B">
																			<label for="perfilB">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PERFIL_EMPRESA_B'); ?></label>
																		</div>
																		<div class="md-radio">
																			<input type="radio" class="md-radiobtn"
																				name="perfil_empresa" id="perfilC" value="C">
																			<label for="perfilC">
																				<span></span>
																				<span class="check"></span>
																				<span class="box"></span>
																				<?php echo lang('PERFIL_EMPRESA_C'); ?></label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CELULAR'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control telefone"
																		name="celular">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('TELEFONE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control telefone"
																		name="telefone">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CNPJ'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control cpf_cnpj"
																		name="cnpj">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CEP'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cep" name="cep"
																			id="cep">
																		<span class="input-group-btn">
																			<button id="cepbusca"
																				class="btn <?php echo $core->primeira_cor; ?>"
																				type="button"><i
																					class="fa fa-arrow-left fa-fw" /></i>
																				<?php echo lang('BUSCAR_END'); ?></button>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ENDERECO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="endereco"
																		id="endereco">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('NUMERO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="numero"
																		id="numero">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('COMPLEMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="complemento">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('BAIRRO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="bairro"
																		id="bairro">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps" name="cidade"
																		id="cidade">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('ESTADO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps uf"
																		name="estado" id="estado">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CNPJ_CONTADOR'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control cpf_cnpj"
																		name="cnpj_contador">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
												</div>
												<div class="col-md-12">
													<br><br>
												</div>
												<div class="col-md-12">
													<h4 class="form-section"><?php echo lang('CONTABILIDADE_INFO'); ?></h4>
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_NOME'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_nome">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_RAZAO_SOCIAL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_razao_social">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CONTATO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_nome_contato">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CNPJ'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control cpf_cnpj"
																		name="contabilidade_cnpj">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_TELEFONE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control telefone"
																		name="contabilidade_telefone">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_EMAIL'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control"
																		name="contabilidade_email">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CPF_CONTADOR'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control cpf"
																		name="contabilidade_cpf_contador">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CRC'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control"
																		name="contabilidade_crc_contador">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CNPJ_CONTADOR'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control cpf_cnpj"
																		name="cnpj_contador">
																</div>
															</div>
														</div>
													</div>
													<!--/col-md-6-->
													<!--col-md-6-->
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CEP'); ?></label>
																<div class="col-md-9">
																	<div class="input-group">
																		<input type="text" class="form-control cep"
																			name="contabilidade_cep" id="contabilidade_cep">
																		<span class="input-group-btn">
																			<button id="cepbuscacontador"
																				class="btn <?php echo $core->primeira_cor; ?>"
																				type="button"><i
																					class="fa fa-arrow-left fa-fw" /></i>
																				<?php echo lang('BUSCAR_END'); ?></button>
																		</span>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_ENDERECO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_endereco"
																		id="contabilidade_endereco">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_NUMERO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_numero" id="contabilidade_numero">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_COMPLEMENTO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_complemento">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_BAIRRO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_bairro" id="contabilidade_bairro">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_CIDADE'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps"
																		name="contabilidade_cidade" id="contabilidade_cidade">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="form-group">
																<label
																	class="control-label col-md-3"><?php echo lang('CONTABILIDADE_ESTADO'); ?></label>
																<div class="col-md-9">
																	<input type="text" class="form-control caps uf"
																		maxlength="2" name="contabilidade_estado"
																		id="contabilidade_estado">
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
																<button type="submit"
																	class="btn <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
																<button type="button" id="voltar"
																	class="btn default"><?php echo lang('VOLTAR'); ?></button>
															</div>
														</div>
													</div>
													<div class="col-md-6">
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm("processarEmpresa"); ?>
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
		<?php break; ?>
	<?php case "distancia": ?>
		<!-- INICIO BOX MODAL -->
		<div id="novo-distancia" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><?php echo lang('UNIDADES'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="empresa_form" id="empresa_form">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<p><?php echo lang('UNIDADE'); ?></p>
									<p>
										<select class="select2me form-control" name="id_empresa_origem"
											data-placeholder="Selecione a unidade de origem">
											<option value=""></option>
											<?php
											$retorno_row = $empresa->getEmpresas();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->nome; ?></option>
													<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</p>
									<p><?php echo lang('UNIDADE'); ?></p>
									<p>
										<select class="select2me form-control" name="id_empresa_destino"
											data-placeholder="Selecione a unidade de destino">
											<option value=""></option>
											<?php
											$retorno_row = $empresa->getEmpresas();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
													?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->nome; ?></option>
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
						<div class="modal-footer">
							<button type="submit"
								class="btn <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal"
								class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarEmpresaDistancia", "empresa_form"); ?>
		</div>
		<!-- FINAL BOX MODAL -->
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('EMPRESA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('UNIDADES_DISTANCIA'); ?></small>
						</h1>
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
										<i class="fa fa-code-fork font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('UNIDADES_DISTANCIA'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="#novo-distancia" class="btn btn-sm <?php echo $core->primeira_cor; ?>"
											data-toggle="modal"><i
												class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR'); ?></a>
									</div>
								</div>
								<div class="portlet-body flip-scroll">
									<table class="table table-bordered table-striped table-condensed flip-content dataTable">
										<thead class="flip-content">
											<tr>
												<th><?php echo lang('ORIGEM'); ?></th>
												<th><?php echo lang('DESTINO'); ?></th>
												<th><?php echo lang('ACOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $empresa->getEmpresasDistancia();
											if ($retorno_row):
												foreach ($retorno_row as $exrow): ?>
													<tr>
														<td><?php echo $exrow->origem; ?></td>
														<td><?php echo $exrow->destino; ?></td>
														<td>
															<a href="javascript:void(0);" class="btn btn-sm red apagar"
																id="<?php echo $exrow->id; ?>" acao="apagarEmpresaDistancia"
																title="<?php echo lang('EMPRESA_APAGAR') . $exrow->origem; ?>"><i
																	class="fa fa-times"></i></a>
														</td>
													</tr>
												<?php endforeach;
												unset($exrow);
											endif; ?>
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
		<?php break; ?>
	<?php case "listar": ?>
		<!-- Plupload -->
		<link rel="stylesheet" type="text/css" href="./assets/plugins/plupload/css/jquery.plupload.queue.css" />
		<script type="text/javascript" src="./assets/plugins/plupload/plupload.full.js"></script>
		<script type="text/javascript" src="./assets/plugins/plupload/jquery.plupload.queue.js"></script>
		<script type="text/javascript" src="./assets/plugins/plupload/i18n/pt_BR.js"></script>
		<!-- admin_form1 -->
		<script type="text/javascript" src="./assets/scripts/fileupload1.js"></script>
		<script>
			jQuery(document).ready(function () {
				FormFileUpload.init();
			});

		</script>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('EMPRESA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('EMPRESA_LISTAR'); ?></small></h1>
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
										<i class="fa fa-list font-<?php echo $core->primeira_cor; ?>"></i>
										<span
											class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('EMPRESA_LISTAR'); ?></span>
									</div>
									<?php if ($usuario->is_Controller()): ?>
										<div class="actions btn-set">
											<a href="index.php?do=empresa&acao=adicionar"
												class="btn btn-sm <?php echo $core->primeira_cor; ?>"><i
													class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR'); ?></a>
										</div>
									<?php endif; ?>
								</div>
								<div class="portlet-body flip-scroll">
									<table class="table table-bordered table-striped table-condensed flip-content dataTable">
										<thead class="flip-content">
											<tr>
												<th><?php echo lang('NOME'); ?></th>
												<th><?php echo lang('ENDERECO'); ?></th>
												<th><?php echo lang('TELEFONE'); ?></th>
												<th><?php echo lang('PERFIL_EMISSAO'); ?></th>
												<th><?php echo lang('ACOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $empresa->getEmpresas();
											if ($retorno_row):
												foreach ($retorno_row as $exrow):
													$perfil_emissao = ($exrow->tipo_sistema == 2) ? lang('TIPO_SISTEMA_G1') : (($exrow->emissor_producao == 1) ? lang('PERFIL_EMISSAO_P') : lang('PERFIL_EMISSAO_H'));
													?>
													<tr>
														<td><a
																href="index.php?do=empresa&acao=editar&id=<?php echo $exrow->id; ?>"><?php echo $exrow->nome; ?></a>
														</td>
														<td><?php echo $exrow->endereco; ?>, <?php echo $exrow->numero; ?> -
															<?php echo $exrow->cidade; ?>
														</td>
														<td><?php echo $exrow->telefone; ?></td>
														<td><?php echo $perfil_emissao; ?></td>
														<td>
															<a href="index.php?do=empresa&acao=editar&id=<?php echo $exrow->id; ?>"
																class="btn btn-sm blue"
																title="<?php echo lang('EDITAR') . ': ' . $exrow->nome; ?>"><i
																	class="fa fa-pencil"></i></a>
															<a href="index.php?do=empresa&acao=arquivo&id=<?php echo $exrow->id; ?>"
																class="btn btn-sm purple"
																title="<?php echo lang('ARQUIVO') . ': ' . $exrow->nome; ?>"><i
																	class="fa fa-file-image-o"></i></a>
															<?php if ($usuario->is_Master()): ?>
															<a href="index.php?do=empresa&acao=boletos&id=<?php echo $exrow->id; ?>"
																class="btn btn-sm grey"
																title="<?php echo lang('BOLETOS_CONFIGURAR') . ': ' . $exrow->nome; ?>"><i
																	class="fa fa-barcode"></i></a>
															<?php endif; ?>
															<?php if ($usuario->is_Controller()): ?>
																<a href="javascript:void(0);" class="btn btn-sm red apagar"
																	id="<?php echo $exrow->id; ?>" acao="apagarEmpresa"
																	title="<?php echo lang('EMPRESA_APAGAR') . $exrow->nome; ?>"><i
																		class="fa fa-times"></i></a>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach;
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
			<?php
			$config = $empresa->getConfigAppEmpresa();
			$getEmpresa = $empresa->getEmpresa();
			if ($getEmpresa->tipo_sistema == 4):
				?>
				<div class="page-content">
					<div class="container">
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-android font-<?php echo $core->primeira_cor; ?>"></i>
									<span
										class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('EMPRESA_APP_TITULO'); ?></span>
								</div>
							</div>
							<div class="portlet-body form">
								<form action="" autocomplete="off" class="form-horizontal" name="config_aplicativo"
									id="config_aplicativo" method="POST">
									<div class="row">
										<div class="form-group">
											<label class="control-label col-md-3"><?php echo lang('EMPRESA_APP_TEMA'); ?></label>
											<div class="col-md-9">
												<div class="md-radio-list col-md-4" style="padding-top: 9px !important;">
													<div class="md-radio col-md-6">
														<input type="radio" class="md-radiobtn" <?= ($config->tema_escuro) ? 'checked' : '' ?> name="tema_escuro" id="dark_ativo" value="1">
														<label for="dark_ativo">
															<span class="inc"></span>
															<span class="check"></span>
															<span class="box"></span>
															<?= lang('SIM') ?>
														</label>
													</div>
													<div class="md-radio col-md-6">
														<input type="radio" class="md-radiobtn" <?= (!$config->tema_escuro) ? 'checked' : '' ?> name="tema_escuro" id="dark_inativo" value="0">
														<label for="dark_inativo">
															<span class="inc"></span>
															<span class="check"></span>
															<span class="box"></span>
															<?= lang('NAO') ?>
														</label>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group">
												<label
													class='control-label col-md-3'><?php echo lang('EMPRESA_APP_COR_DESTAQUE'); ?></label>
												<div class='col-sm-1'>
													<input name="cor_destaque" id="cor_destaque" type="color" class='form-control'
														value="<?php echo $config->cor_destaque; ?>">
												</div>
												<h4><?php echo $config->cor_destaque; ?></h4>
											</div>
										</div>
									</div>
									<div class='form-actions'>
										<div class='row'>
											<div class='col-md-12'>
												<div class='col-md-6'>
													<div class='row'>
														<div class='col-md-offset-3 col-md-9'>
															<button type='submit'
																class='btn <?php echo $core->primeira_cor; ?>'><?php echo lang('SALVAR'); ?></button>
															<button type='button'
																class='btn default voltar'><?php echo lang('VOLTAR'); ?></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<?php echo $core->doForm("processarConfigAplicativo", "config_aplicativo"); ?>
						</div>
					</div>
				</div>
				<div class="page-content">
					<div class="container">
						<div class="portlet light">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-picture-o font-<?php echo $core->primeira_cor; ?>"></i>
									<span
										class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('EMPRESA_APP_UPLOAD_IMAGENS'); ?></span>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<form class="form-inline" method="POST" id="upload_form" name="upload_form">
										<div class="container">
											<div class="portlet-body">
												<div class="row">
													<div class="col-md-5">
														<div class="panel panel-info">
															<div class="panel-heading">
																<h3 class="panel-title">
																	<i class="fa fa-image">&nbsp;&nbsp;</i>
																	<strong>
																		<?php echo lang('EMPRESA_APP_LOGOMARCA'); ?>
																	</strong>
																</h3>
															</div>
															<div class="panel-body">
																<i
																	style="color: gray;"><?php echo lang('EMPRESA_APP_LOGOMARCA_TEXTO'); ?></i>
																<span class="help-block"
																	style="font-size: 11px;"><?php echo lang('EMPRESA_APP_LOGOMARCA_OBS'); ?></span>
																<hr>
																<div class="plupload" id="imagem_logo"></div>
															</div>
														</div>
														<input name="processarImagemLogoAplicativo" type="hidden" value="1" />
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
								<div class="col-md-6">
									<form class="form-inline" method="POST" id="admin_form" name="admin_form">
										<div class="container">
											<div class="portlet-body">
												<div class="row">
													<div class="col-md-5">
														<div class="panel panel-info">
															<div class="panel-heading">
																<h3 class="panel-title">
																	<i class="fa fa-image">&nbsp;&nbsp;</i>
																	<strong>
																		<?php echo lang('EMPRESA_APP_POPUP'); ?>
																	</strong>
																</h3>
															</div>
															<div class="panel-body">
																<i
																	style="color: gray;"><?php echo lang('EMPRESA_APP_POPUP_TEXTO'); ?></i>
																<span class="help-block"
																	style="font-size: 11px;"><?php echo lang('EMPRESA_APP_POPUP_OBS'); ?></span>
																<hr>
																<div class="plupload" id="imagem_popup"></div>
															</div>
														</div>
														<input name="processarImagemPopupAplicativo" type="hidden" value="1" />
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<!-- FINAL CONTEUDO DA PAGINA -->
		<?php break; ?>
		<?php case "boletos": ?>
		<?php $row = Core::getRowById("empresa", Filter::$id); ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('EMPRESA_TITULO'); ?>&nbsp;<i
								class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('BOLETOS_CONFIGURAR'); ?></small></h1>
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
							<div class="portlet box <?php echo $core->primeira_cor; ?>">
								<div class="portlet-title">
									<div class="caption">
										<i class="fa fa-barcode">&nbsp;&nbsp;</i><?php echo lang('BOLETOS_CONFIGURAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form"
										id="admin_form" enctype="multipart/form-data">
										<div class="form-body">
											<div class="row">
												<?php if($row->modulo_emissao_boleto): ?>
													<div class="col-md-12">
														<br><br>
													</div>
													<div class="col-md-12">
														<h4 class="form-section">
															<strong><?php echo lang('BOLETO_INFORMACOES'); ?></strong>
														</h4>
														<!--col-md-6-->
														<div class="col-md-6">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('BANCO'); ?></label>
																	<div class="col-md-9">
																		<select <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> class="select2me form-control"
																			name="boleto_banco" id="boleto_banco"
																			data-placeholder="<?php echo lang('SELECIONE_BANCO') ?>">
																			<option value=""></option>
																			<?php
																			$banco_boleto = $boleto->getBancosBoleto();
																			if ($banco_boleto):
																				foreach ($banco_boleto as $key):
																					?>
																					<option value="<?php echo $key->arquivo_boleto ?>"
																						codigo_banco="<?php echo $key->codigo_banco ?>"
																						<?php echo $row->boleto_banco === $key->arquivo_boleto ? ' selected="selected" ' : '' ?>>
																						<?php echo $key->codigo_banco . " - " . $key->nome_banco ?>
																					</option>
																					<?php
																				endforeach;
																			endif;
																			?>
																		</select>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('COD_BANCO'); ?></label>
																	<div class="col-md-9">
																		<input readonly <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control caps"
																			name="boleto_codigo_banco" id="boleto_codigo_banco"
																			value="<?php echo $row->boleto_codigo_banco ?>">
																	</div>
																</div>
															</div>
															<div class="itensBoleto <?php echo ($row->boleto_banco) ? 'mostrar' : 'ocultar'; ?>">
																<div class="row">
																	<div class="form-group">
																		<label
																			class="control-label col-md-3"><?php echo lang('AGENCIA'); ?></label>
																		<div class="col-md-9">
																			<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control caps inteiro"
																				name="boleto_agencia" id="boleto_agencia"
																				value="<?php echo $row->boleto_agencia ?>">
																			<span class="help-block"><?php echo 'sem dígito e sem traços ou pontuação'; ?></span>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label
																			class="control-label col-md-3"><?php echo lang('CONTA'); ?></label>
																		<div class="col-md-9">
																			<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control caps inteiro"
																				name="boleto_conta" id="boleto_conta"
																				value="<?php echo $row->boleto_conta ?>">
																			<span class="help-block"><?php echo 'com dígito e sem traços ou pontuação'; ?></span>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="form-group">
																		<label
																			class="control-label col-md-3"><?php echo lang('CONVENIO'); ?></label>
																		<div class="col-md-9">
																			<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control caps"
																				name="boleto_convenio" id="boleto_convenio"
																				value="<?php echo $row->boleto_convenio ?>">
																		</div>
																	</div>
																</div>
															</div>	
														</div>
														<!--/col-md-6-->
														<!--col-md-6-->
														<div class="col-md-6 itensBoleto <?php echo ($row->boleto_banco) ? 'mostrar' : 'ocultar'; ?>">
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('INSTRUCOES_BOLETO_CAMPO_UM'); ?></label>
																	<div class="col-md-9">
																		<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control caps"
																			name="boleto_instrucoes1" id="boleto_instrucoes1"
																			value="<?php echo $row->boleto_instrucoes1 ?>">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('INSTRUCOES_BOLETO_CAMPO_DOIS'); ?></label>
																	<div class="col-md-9">
																		<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control caps"
																			name="boleto_instrucoes2" id="boleto_instrucoes2"
																			value="<?php echo $row->boleto_instrucoes2 ?>">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('INSTRUCOES_BOLETO_CAMPO_TRES'); ?></label>
																	<div class="col-md-9">
																		<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control caps"
																			name="boleto_instrucoes3" id="boleto_instrucoes3"
																			value="<?php echo $row->boleto_instrucoes3 ?>">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('INSTRUCOES_BOLETO_CAMPO_QUATRO'); ?></label>
																	<div class="col-md-9">
																		<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control caps"
																			name="boleto_instrucoes4" id="boleto_instrucoes4"
																			value="<?php echo $row->boleto_instrucoes4 ?>">
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-6-->
													</div>

													<div class="col-md-12">
														<br><br>
													</div>
													<div class="col-md-12 itensBoleto <?php echo ($row->boleto_banco) ? 'mostrar' : 'ocultar'; ?>">
														<h4 class="form-section">
															<strong><?php echo lang('BOLETO_JUROSMULTAPROTESTO'); ?></strong>
														</h4>
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"><?php echo lang('BOLETO_COD_JUROS'); ?></label>
																	<div class="col-md-9">
																		<select <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> class="select2me form-control"
																			name="boleto_cod_juros" id="boleto_cod_juros"
																			data-placeholder="<?php echo lang('BOLETO_SEL_COD_JUROS') ?>">
																			<option value="0" <?php echo ($row->codigo_juros==0) ? ' selected="selected" ' : ''; ?>>Isento</option>
																			<option value="1" <?php echo ($row->codigo_juros==1) ? ' selected="selected" ' : ''; ?>>Valor por Dia</option>
																			<option value="2" <?php echo ($row->codigo_juros==2) ? ' selected="selected" ' : ''; ?>>Taxa Mensal</option>
																		</select>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('BOLETO_DATA_JUROS'); ?></label>
																	<div class="col-md-9">
																		<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control inteiro"
																			name="boleto_data_juros" id="boleto_data_juros"
																			value="<?php echo $row->dias_juros ?>">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('BOLETO_VALOR_JUROS'); ?></label>
																	<div class="col-md-9">
																		<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control decimal"
																			name="boleto_valor_juros" id="boleto_valor_juros"
																			value="<?php echo decimal($row->valor_juros); ?>">
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-4-->
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"><?php echo lang('BOLETO_COD_MULTA'); ?></label>
																	<div class="col-md-9">
																		<select <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> class="select2me form-control"
																			name="boleto_cod_multa" id="boleto_cod_multa"
																			data-placeholder="<?php echo lang('BOLETO_SEL_COD_MULTA') ?>">
																			<option value="0" <?php echo ($row->codigo_multa==0) ? ' selected="selected" ' : ''; ?>>Isento</option>
																			<option value="1" <?php echo ($row->codigo_multa==1) ? ' selected="selected" ' : ''; ?>>Valor fixo</option>
																			<option value="2" <?php echo ($row->codigo_multa==2) ? ' selected="selected" ' : ''; ?>>Percentual</option>
																		</select>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('BOLETO_DATA_MULTA'); ?></label>
																	<div class="col-md-9">
																		<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control inteiro"
																			name="boleto_data_multa" id="boleto_data_multa"
																			value="<?php echo $row->dias_multa; ?>">
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('BOLETO_VALOR_MULTA'); ?></label>
																	<div class="col-md-9">
																		<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control decimal"
																			name="boleto_valor_multa" id="boleto_valor_multa"
																			value="<?php echo decimal($row->valor_multa); ?>">
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-4-->
														<!--col-md-4-->
														<div class="col-md-4">
															<div class="row">
																<div class="form-group">
																	<label class="control-label col-md-3"><?php echo lang('BOLETO_COD_PROTESTO'); ?></label>
																	<div class="col-md-9">
																		<select <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> class="select2me form-control"
																			name="boleto_cod_protesto" id="boleto_cod_protesto"
																			data-placeholder="<?php echo lang('BOLETO_SEL_COD_JUROS') ?>">
																			<option value="3" <?php echo ($row->codigo_protesto==3) ? ' selected="selected" ' : ''; ?>>Não protestar</option>
																			<option value="1" <?php echo ($row->codigo_protesto==1) ? ' selected="selected" ' : ''; ?>>Protestar</option>
																		</select>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="form-group">
																	<label
																		class="control-label col-md-3"><?php echo lang('BOLETO_DATA_PROTESTO'); ?></label>
																	<div class="col-md-9">
																		<input <?php echo !$row->modulo_emissao_boleto ? 'disabled' : '' ?> type="text" class="form-control inteiro"
																			name="boleto_data_protesto" id="boleto_data_protesto"
																			value="<?php echo $row->dias_protesto; ?>">
																	</div>
																</div>
															</div>
														</div>
														<!--/col-md-4-->
													</div>

													<?php if ($row->usuario_edicao_boleto) : ?>
														
														<div class="col-md-12">
														<br><br>
														</div>
														<div class="col-md-12">
															<h4 class="form-section">
																<strong><?php echo lang('BOLETO_USUARIO_EDICAO_TITULO'); ?></strong>
															</h4>
															<!--col-md-6-->
															<div class="col-md-6">
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3"><?php echo lang('USUARIO'); ?></label>
																		<div class="col-md-9">
																			<input <?php echo 'disabled'; ?> type="text" class="form-control" value="<?php echo $row->usuario_edicao_boleto; ?>">
																		</div>
																	</div>
																</div>
															</div>
															<!--/col-md-6-->
															<!--col-md-6-->
															<div class="col-md-6">
																<div class="row">
																	<div class="form-group">
																		<label class="control-label col-md-3"><?php echo lang('DATA'); ?></label>
																		<div class="col-md-9">
																			<input <?php echo 'disabled'; ?> type="text" class="form-control" value="<?php echo exibedata($row->data_edicao_boleto); ?>">
																		</div>
																	</div>
																</div>
															</div>
															<!--/col-md-6-->
														</div>

													<?php endif; ?>

												<?php else: ?>
													<div class=col-md-12>
														<div class="note note-warning">
															<h4 class="block"><?php echo lang('BOLETOS_CONFIGURAR_ATENCAO'); ?></h4>
															<p><?php echo lang('BOLETOS_CONFIGURAR_ATENCAO_TEXTO'); ?></p>
														</div>
													</div>
												<?php endif; ?>
											</div>
										</div>
										<input name="id" type="hidden" value="<?php echo Filter::$id; ?>" />
										<input name="usuario_controle" type="hidden" value="<?php echo $usuario->is_Controller(); ?>" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<?php if($row->modulo_emissao_boleto): ?>
																	<button type="submit" class="btn <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
																<?php endif; ?>
																<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR'); ?></button>
															</div>
														</div>
													</div>
													<div class="col-md-6">
													</div>
												</div>
											</div>
										</div>
									</form>
									<?php echo $core->doForm("processarBoletoEmpresa"); ?>
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
		<?php break; ?>
	<?php default: ?>
		<div class="imagem-fundo">
			<img src="assets/img/logo.png" border="0">
		</div>
		<?php break; ?>
<?php endswitch; ?>