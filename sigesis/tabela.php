<?php

/**
 * Tabela Preco
 *
 */
if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe não é permitido.');
if (!$usuario->is_Todos())
	redirect_to("login.php");
?>
<?php switch (Filter::$acao):
	case "editar": ?>
		<?php $row = Core::getRowById("tabela_precos", Filter::$id); ?>
		<?php if (!$usuario->is_Master()) : print redirect_to("./_error/");
			return;
		endif; ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('TABELA_PRECO_TITULO'); ?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('TABELA_PRECO_EDITAR'); ?></small></h1>
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
										<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('TABELA_PRECO_EDITAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-11">
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('TABELA_PRECO'); ?></label>
															<div class="col-md-9">
																<input type="text" class="form-control caps" name="tabela" value="<?php echo $row->tabela; ?>">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('QUANT_MINIMA'); ?></label>
															<div class="col-md-9">
																<input type="text" class="form-control inteiro" name="quantidade" value="<?php echo $row->quantidade; ?>">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('DESC_MAXIMO'); ?></label>
															<div class="col-md-9">
																<input type="text" class="form-control decimalp" name="desconto" value="<?php echo decimalp($row->desconto); ?>">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('PERCENTUAL_ACRESCIMO'); ?></label>
															<div class="col-md-9">
																<input type="text" class="form-control decimal" name="percentual" value="<?php echo decimal($row->percentual); ?>">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('NIVEL'); ?></label>
															<div class="col-md-9">
																<select class="select2me form-control" name="nivel" data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																	<option value=""></option>
																	<option value="8" <?php if ("8" == $row->nivel) echo 'selected="selected"'; ?>><?php echo lang('MASTER'); ?></option>
																	<option value="7" <?php if ("7" == $row->nivel) echo 'selected="selected"'; ?>><?php echo lang('GERENCIA_FINANCEIRO'); ?></option>
																	<option value="6" <?php if ("6" == $row->nivel) echo 'selected="selected"'; ?>><?php echo lang('ADMINISTRATIVO'); ?></option>
																	<option value="5" <?php if ("5" == $row->nivel) echo 'selected="selected"'; ?>><?php echo lang('COMERCIAL'); ?></option>
																	<option value="4" <?php if ("4" == $row->nivel) echo 'selected="selected"'; ?>><?php echo lang('CONSULTOR'); ?></option>
																	<option value="3" <?php if ("3" == $row->nivel) echo 'selected="selected"'; ?>><?php echo lang('ATENDIMENTO'); ?></option>
																	<option value="2" <?php if ("2" == $row->nivel) echo 'selected="selected"'; ?>><?php echo lang('COLABORADOR'); ?></option>
																	<option value="0" <?php if ("0" == $row->nivel) echo 'selected="selected"'; ?>><?php echo lang('TODOS'); ?></option>
																</select>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"></label>
															<div class="col-md-9">
																<div class="md-checkbox-list">
																	<div class="md-checkbox col-md-12">
																		<input type="checkbox" class="md-check" name="principal_pdv" id="principal_pdv" value="1" <?php if ($row->principal_pdv) echo 'checked'; ?>>
																		<label for="principal_pdv">
																			<span></span>
																			<span class="check"></span>
																			<span class="box"></span>
																			<?php echo lang('TABELA_PRECO_PDV_TITULO'); ?></label>
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
																		<input type="checkbox" class="md-check" name="appvendas" id="appvendas" value="1" <?php if ($row->appvendas) echo 'checked'; ?>>
																		<label for="appvendas">
																			<span></span>
																			<span class="check"></span>
																			<span class="box"></span>
																			<?php echo lang('TABELA_PRECO_APPVENDAS_TITULO'); ?></label>
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
																		<input type="checkbox" class="md-check" name="exibir_app" id="exibir_app" value="1" <?php if ($row->aplicativo) echo 'checked'; ?>>
																		<label for="exibir_app">
																			<span></span>
																			<span class="check"></span>
																			<span class="box"></span>
																			<?php echo lang('TABELA_PRECO_APLICATIVO_TITULO'); ?></label>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<input name="id" type="hidden" value="<?php echo Filter::$id; ?>" />
										<div class="form-actions">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="row">
															<div class="col-md-offset-3 col-md-9">
																<button type="button" class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
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
									<?php echo $core->doForm("processarTabelaPreco"); ?>
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
	<?php
	case "adicionar": ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('TABELA_PRECO_TITULO'); ?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('TABELA_PRECO_ADICIONAR'); ?></small></h1>
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
										<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('TABELA_PRECO_ADICIONAR'); ?>
									</div>
								</div>
								<div class="portlet-body form">
									<!-- INICIO FORM-->
									<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
										<div class="form-body">
											<div class="row">
												<div class="col-md-11">
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('TABELA_PRECO'); ?></label>
															<div class="col-md-9">
																<input type="text" class="form-control caps" name="tabela">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('QUANT_MINIMA'); ?></label>
															<div class="col-md-9">
																<input type="text" class="form-control inteiro" name="quantidade">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('DESC_MAXIMO'); ?></label>
															<div class="col-md-9">
																<input type="text" class="form-control decimalp" name="desconto">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('PERCENTUAL_ACRESCIMO'); ?></label>
															<div class="col-md-9">
																<input type="text" class="form-control decimal" name="percentual">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="form-group">
															<label class="control-label col-md-3"><?php echo lang('NIVEL'); ?></label>
															<div class="col-md-9">
																<select class="select2me form-control" name="nivel" data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
																	<option value=""></option>
																	<option value="8"><?php echo lang('MASTER'); ?></option>
																	<option value="7"><?php echo lang('GERENCIA_FINANCEIRO'); ?></option>
																	<option value="6"><?php echo lang('ADMINISTRATIVO'); ?></option>
																	<option value="5"><?php echo lang('COMERCIAL'); ?></option>
																	<option value="4"><?php echo lang('CONSULTOR'); ?></option>
																	<option value="3"><?php echo lang('ATENDIMENTO'); ?></option>
																	<option value="2"><?php echo lang('COLABORADOR'); ?></option>
																	<option value="0"><?php echo lang('TODOS'); ?></option>
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
															<div class="col-md-offset-3 col-md-9">
																<button type="button" class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
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
									<?php echo $core->doForm("processarTabelaPreco"); ?>
									<!-- FINAL FORM-->
									<div id="overlay">
										<span class="loader"></span>
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
		<?php break; ?>
	<?php
	case "listar": ?>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('TABELA_PRECO_TITULO'); ?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('TABELA_PRECO_LISTAR'); ?></small></h1>
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
										<span class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('TABELA_PRECO_LISTAR'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="index.php?do=tabela&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor; ?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR'); ?></a>
									</div>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed dataTable">
										<thead>
											<tr>
												<th><?php echo lang('TABELA_PRECO'); ?></th>
												<th><?php echo lang('QUANT_MINIMA'); ?></th>
												<th><?php echo lang('DESC_MAXIMO'); ?></th>
												<th><?php echo lang('PERCENTUAL_ACRESCIMO'); ?></th>
												<th><?php echo lang('NIVEL'); ?></th>
												<th><?php echo lang('TABELA_PRECO_PDV'); ?></th>
												<th><?php echo lang('TABELA_PRECO_APPVENDAS'); ?></th>
												<th><?php echo lang('TABELA_PRECO_APLICATIVO'); ?></th>
												<th><?php echo lang('OPCOES'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$retorno_row = $produto->getTabelaPrecos();
											if ($retorno_row) :
												foreach ($retorno_row as $exrow) : ?>
													<tr>
														<td><a href="index.php?do=tabela&acao=tabela&id=<?php echo $exrow->id; ?>"><?php echo $exrow->tabela; ?></a></td>
														<td><?php echo $exrow->quantidade; ?></td>
														<td><?php echo percent($exrow->desconto); ?></td>
														<td><?php echo percent($exrow->percentual); ?></td>
														<td><?php echo ($exrow->nivel) ? nivel($exrow->nivel) : lang('TODOS'); ?></td>
														<td><?php echo ($exrow->principal_pdv) ? '<span class="badge badge-success">' . lang('SIM') . '</span>' : '<span class="badge badge-danger">' . lang('NAO') . '</span>'; ?></td>
														<td><?php echo ($exrow->appvendas) ? '<span class="badge badge-success">' . lang('SIM') . '</span>' : '<span class="badge badge-danger">' . lang('NAO') . '</span>'; ?></td>
														<td><?php echo ($exrow->aplicativo) ? '<span class="badge badge-success">' . lang('SIM') . '</span>' : '<span class="badge badge-danger">' . lang('NAO') . '</span>'; ?></td>
														<td>
															<a href="index.php?do=tabela&acao=tabela&id=<?php echo $exrow->id; ?>" class="btn btn-sm grey-cascade" title="<?php echo lang('VISUALIZAR') . ': ' . $exrow->tabela; ?>"><i class="fa fa-search"></i></a>
															<a href="index.php?do=tabela&acao=etiquetas&id_tabela=<?php echo $exrow->id; ?>" class="btn btn-sm purple"><i class="fa fa-tags"></i></a>
															<?php if ($usuario->is_Master()) : ?>
																<a href="index.php?do=tabela&acao=editar&id=<?php echo $exrow->id; ?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR') . ': ' . $exrow->tabela; ?>"><i class="fa fa-pencil"></i></a>
																<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id; ?>" acao="apagarTabelaPreco" title="<?php echo lang('TABELA_PRECO_APAGAR') . $exrow->tabela; ?>"><i class="fa fa-times"></i></a>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php unset($exrow);
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
	<?php
	case "tabela":
		$row = Core::getRowById("tabela_precos", Filter::$id);
	?>
		<script src="./assets/scripts/table-tabela.js" type="text/javascript"></script>
		<script type="text/javascript">
			// <![CDATA[  
			$(document).ready(function() {
				$('#imprimir_tabela').click(function() {
					var id = $("#id").val();
					var id_categoria = $("#id_categoria").val();
					var id_grupo = $("#id_grupo").val();
					var id_fabricante = $("#id_fabricante").val();
					window.open('pdf_tabela.php?id_categoria=' + id_categoria + '&id_fabricante=' + id_fabricante + '&id_grupo=' + id_grupo + '&id=' + id, 'Imprimir Estoque', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});
			});
			// ]]>
		</script>
		<script type="text/javascript">
			// <![CDATA[  
			$(document).ready(function() {
				$('#id').change(function() {
					var id = $("#id").val();
					var id_categoria = $("#id_categoria").val();
					var id_grupo = $("#id_grupo").val();
					var id_fabricante = $("#id_fabricante").val();
					window.location.href = 'index.php?do=tabela&acao=tabela&id=' + id;
				});
			});
			// ]]>
		</script>
		<div id="buscar-produto" class="modal fade" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-search">&nbsp;&nbsp;</i><?php echo lang('TABELA_PRECO_PRODUTOS'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="produtos_form" id="produtos_form" class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('PRODUTO'); ?></label>
										<div class="col-md-9">
											<select class="select2me form-control input-large" name="id_produto" data-placeholder="<?php echo lang('SELECIONE_PRODUTO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $produto->getProdutosGrade();
												if ($retorno_row) :
													foreach ($retorno_row as $srow) :
												?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->codigo . "#" . $srow->nome; ?></option>
												<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('GRUPO'); ?></label>
										<div class="col-md-9">
											<select class="select2me form-control input-large" name="id_grupo" data-placeholder="<?php echo lang('SELECIONE_GRUPO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $grupo->getGrupos();
												if ($retorno_row) :
													foreach ($retorno_row as $srow) :
												?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->grupo; ?></option>
												<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('CATEGORIA'); ?></label>
										<div class="col-md-9">
											<select class="select2me form-control input-large" name="id_categoria" data-placeholder="<?php echo lang('SELECIONE_CATEGORIA'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $categoria->getCategorias();
												if ($retorno_row) :
													foreach ($retorno_row as $srow) :
												?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->categoria; ?></option>
												<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('FABRICANTE'); ?></label>
										<div class="col-md-9">
											<select class="select2me form-control input-large" name="id_fabricante" data-placeholder="<?php echo lang('SELECIONE_FABRICANTE'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $fabricante->getFabricantes();
												if ($retorno_row) :
													foreach ($retorno_row as $srow) :
												?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->fabricante; ?></option>
												<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo lang('NOTA_TITULO'); ?></label>
										<div class="col-md-9">
											<select class="select2me form-control input-large" name="id_nota" data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<option value=""></option>
												<?php
												$retorno_row = $produto->getGradeNotaFiscal();
												if ($retorno_row) :
													foreach ($retorno_row as $srow) :
												?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->numero_nota; ?></option>
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
						<input name="id" type="hidden" value="<?php echo Filter::$id; ?>" />
						<div class="modal-footer">
							<button type="button" class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("buscarProdutos", "produtos_form"); ?>
		</div>
		<div id="alterar-todos" class="modal fade" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title"><i class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('ALTERAR_TODOS'); ?></h4>
					</div>
					<form action="" autocomplete="off" method="post" name="todos_form" id="todos_form" class="form-horizontal">
						<div class="modal-body">
							<div class="row">
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('PERCENTUAL'); ?></label>
									<div class="col-md-8">
										<input type="text" class="form-control decimal" id="percentual" name="percentual">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('GRUPO'); ?></label>
									<div class="col-md-9">
										<select class="select2me form-control input-large" name="id_grupo" data-placeholder="<?php echo lang('SELECIONE_GRUPO'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $grupo->getGrupos();
											if ($retorno_row) :
												foreach ($retorno_row as $srow) :
											?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->grupo; ?></option>
											<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('CATEGORIA'); ?></label>
									<div class="col-md-9">
										<select class="select2me form-control input-large" name="id_categoria" data-placeholder="<?php echo lang('SELECIONE_CATEGORIA'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $categoria->getCategorias();
											if ($retorno_row) :
												foreach ($retorno_row as $srow) :
											?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->categoria; ?></option>
											<?php
												endforeach;
												unset($srow);
											endif;
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3"><?php echo lang('FABRICANTE'); ?></label>
									<div class="col-md-9">
										<select class="select2me form-control input-large" name="id_fabricante" data-placeholder="<?php echo lang('SELECIONE_FABRICANTE'); ?>">
											<option value=""></option>
											<?php
											$retorno_row = $fabricante->getFabricantes();
											if ($retorno_row) :
												foreach ($retorno_row as $srow) :
											?>
													<option value="<?php echo $srow->id; ?>"><?php echo $srow->fabricante; ?></option>
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
						<input name="id_tabela" type="hidden" value="<?php echo Filter::$id; ?>" />
						<div class="modal-footer">
							<button type="button" class="btn btn-submit <?php echo $core->primeira_cor; ?>"><?php echo lang('SALVAR'); ?></button>
							<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR'); ?></button>
						</div>
					</form>
				</div>
			</div>
			<?php echo $core->doForm("processarAlterarTodos", "todos_form"); ?>
		</div>
		<!-- INICIO CONTEUDO DA PAGINA -->
		<div class="page-container">
			<!-- INICIO CABECALHO DA PAGINA -->
			<div class="page-head">
				<div class="container">
					<!-- INICIO TITULO DA PAGINA -->
					<div class="page-title">
						<h1><?php echo lang('TABELA_PRECO_TITULO'); ?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('TABELA_PRECO_PRODUTOS'); ?></small></h1>
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
										<i class="fa fa-table font-<?php echo $core->primeira_cor; ?>"></i>
										<span class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('TABELA_PRECO_PRODUTOS'); ?></span>
									</div>
									<div class="actions btn-set">
										<a href="index.php?do=tabela&acao=etiquetas&id_tabela=<?php echo Filter::$id; ?>" class="btn btn-sm purple"><i class="fa fa-tags">&nbsp;&nbsp;</i><?php echo lang('ETIQUETAS'); ?></a>
										<?php if ($usuario->is_Administrativo()) : ?>
											<a href="#buscar-produto" data-toggle="modal" class="btn btn-sm blue"><i class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('TABELA_PRECO_BUSCAR'); ?></a>
											<a href="#alterar-todos" data-toggle="modal" class="btn btn-sm green"><i class='fa fa-usd'>&nbsp;&nbsp;</i><?php echo lang('ALTERAR_TODOS'); ?></a>
										<?php endif; ?>
									</div>
								</div>
								<div class="portlet-body">
									<form class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-medium" name="id" id="id" data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<?php
												$retorno_row = $produto->getTabelaPrecos();
												if ($retorno_row) :
													foreach ($retorno_row as $srow) :
												?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == Filter::$id) echo 'selected="selected"'; ?>><?php echo $srow->tabela; ?></option>
												<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
										<div class="form-group">
											<select class="select2me form-control input-medium" name="id_grupo" id="id_grupo">
												<option value="">TODOS GRUPOS</option>
												<?php
												$retorno_row = $grupo->getGrupos();
												if ($retorno_row) :
													foreach ($retorno_row as $srow) :
												?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->grupo; ?></option>
												<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
										<div class="form-group">
											<select class="select2me form-control input-medium" name="id_categoria" id="id_categoria">
												<option value="">TODAS CATEGORIAS</option>
												<?php
												$retorno_row = $categoria->getCategorias();
												if ($retorno_row) :
													foreach ($retorno_row as $srow) :
												?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->categoria; ?></option>
												<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
										<div class="form-group">
											<select class="select2me form-control input-medium" name="id_fabricante" id="id_fabricante">
												<option value="">TODOS FABRICANTES</option>
												<?php
												$retorno_row = $fabricante->getFabricantes();
												if ($retorno_row) :
													foreach ($retorno_row as $srow) :

												?>
														<option value="<?php echo $srow->id; ?>"><?php echo $srow->fabricante; ?></option>
												<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<?php
									$classeTipoUsuario = ($usuario->is_Administrativo()) ? "table_listar_tabela_preco" : "table_listar_tabela_preco_geral";
									?>
									<table class="table table-bordered table-condensed table-advance" id="<?php echo $classeTipoUsuario; ?>">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('CODIGO'); ?></th>
												<th><?php echo lang('CODIGO_DE_BARRAS'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('GRUPO'); ?></th>
												<th><?php echo lang('CATEGORIA'); ?></th>
												<th><?php echo lang('ESTOQUE'); ?></th>
												<?php if ($usuario->is_Administrativo()) : ?>
													<th><?php echo lang('CUSTO'); ?></th>
													<th><?php echo lang('PERCENTUAL'); ?></th>
												<?php endif; ?>
												<th><?php echo lang('VENDA'); ?></th>
												<?php if ($usuario->is_Administrativo()) : ?>
													<th width="80px"><?php echo lang('OPCOES'); ?></th>
												<?php endif; ?>
											</tr>
										</thead>
										<tbody>
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
	<?php
	case "consultaprecos":
		Filter::$id = (isset(Filter::$id) && !empty(Filter::$id) && Filter::$id > 0) ? Filter::$id : $produto->getPrimeiraTabelaPrecosCadastrada();
		$row = Core::getRowById("tabela_precos", Filter::$id);
	?>
		<script type="text/javascript">
			// <![CDATA[  
			$(document).ready(function() {
				$('#id').change(function() {
					var id = $("#id").val();
					window.location.href = 'index.php?do=tabela&acao=consultaprecos&id=' + id;
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
						<h1><?php echo lang('TABELA_PRECO_TITULO'); ?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('TABELA_PRECO_CONSULTAR'); ?></small></h1>
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
										<i class="fa fa-search font-<?php echo $core->primeira_cor; ?>"></i>
										<span class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('TABELA_PRECO_CONSULTAR'); ?></span>
									</div>
								</div>
								<div class="portlet-body">
									<form class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-medium" name="id" id="id" data-placeholder="<?php echo lang('SELECIONE_OPCAO'); ?>">
												<?php
												$retorno_row = $produto->getTabelaPrecos();
												if ($retorno_row) :
													foreach ($retorno_row as $srow) :
												?>
														<option value="<?php echo $srow->id; ?>" <?php if ($srow->id == Filter::$id) echo 'selected="selected"'; ?>><?php echo $srow->tabela; ?></option>
												<?php
													endforeach;
													unset($srow);
												endif;
												?>
											</select>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance" id="table_listar_preco_produtos">
										<thead>
											<tr>
												<th>#</th>
												<th><?php echo lang('CODIGO'); ?></th>
												<th><?php echo lang('CODIGO_DE_BARRAS'); ?></th>
												<th><?php echo lang('CODIGO_INTERNO'); ?></th>
												<th><?php echo lang('PRODUTO'); ?></th>
												<th><?php echo lang('ESTOQUE'); ?></th>
												<th><?php echo lang('VALOR_AVISTA'); ?></th>
												<th><?php echo lang('VALOR_NORMAL'); ?></th>
												<th><?php echo lang('OBSERVACAO'); ?></th>
											</tr>
										</thead>
										<tbody>
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
	<?php
	case "etiquetas":
		$id_tabela = get('id_tabela');
	?>
		<script type="text/javascript">
			// <![CDATA[  
			$(document).ready(function() {
				$('#etiqueta_a4').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#etiqueta_a4_63x31').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_63x31.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#etiqueta_tag3').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_tag.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#etiqueta_tag4').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_tag4.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#etiqueta_tag_joia').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_joia.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#etiqueta_tag3_preco').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_tag_preco.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#etiqueta_tag4_preco').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_tag4_preco.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#etiqueta_tag_joia_preco').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_joia_preco.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#etiqueta_tag_corte').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_tag_corte.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#etiqueta_tag_corte2').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_tag_corte2.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});

				$('#etiqueta_tag2').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_tag2.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
				});
				$('#etiqueta_tag_gondola').click(function() {
					var id = $(this).attr('id_tabela');
					var posicao = $("#posicao").val();
					if (posicao > 24) {
						alert('A posição deve ser inferior a 24.');
						$("#posicao").val('');
						$("#posicao").focus();
						return false;
					}
					var quant_impressao = $("#quant_impressao").val();
					var q = parseFloat(quant_impressao);
					if (isNaN(q)) {
						q = 1;
					}
					var lista = '';
					$("input[name='id_produto[]']").each(function() {
						var checked = jQuery(this).is(":checked");
						if (checked) {
							lista += ($(this).val()) + ',';
						}
					});
					window.open('pdf_etiquetas_tag_gondola.php?id=' + id + '&posicao=' + posicao + '&quant_impressao=' + q + '&lista=' + lista, 'Imprimir Etiqueta A4', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
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
						<h1><?php echo lang('TABELA_PRECO_TITULO'); ?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('ETIQUETAS'); ?></small></h1>
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
										<i class="fa fa-tags font-<?php echo $core->primeira_cor; ?>"></i>
										<span class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('ETIQUETAS'); ?></span>
									</div>
									<div class="actions btn-set">
										<button type="button" id="voltar" class="btn btn-sm default"><?php echo lang('VOLTAR'); ?></button>
									</div>
								</div>
								<div class="portlet-body">
									<form class="horizontal-form">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<div class="col-md-3">
														<input type="text" class="form-control input-medium inteiro" id="posicao" maxlength="2" placeholder="<?php echo lang('POSICAO_INICIAL'); ?>">
													</div>
													<div class="col-md-4">
														<input type="text" class="form-control input-medium inteiro" id="quant_impressao" maxlength="2" placeholder="<?php echo lang('QUANTIDADE_IMPRESSOES'); ?>">
													</div>
												</div>
											</div>
										</div>
										<br />
										<div class="row">
											<div class="col-md-12">
												<div class="col-md-3">
													<div class="row">
														<div class="col-md-12">
															<div class="form-group">
																<div class="col-md-12">
																	<a href="javascript:void(0);" id="etiqueta_a4" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_A4_POLIFIX'); ?></a><br><?php echo lang('ETIQUETAS_A4_POLIFIX'); ?>
																</div>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="form-group">
																<div class="col-md-12">
																	<a href="javascript:void(0);" id="etiqueta_a4_63x31" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_A4_MAXPRINT'); ?></a><br><?php echo lang('ETIQUETAS_A4_MAXPRINT'); ?>
																</div>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="form-group">
																<div class="col-md-12">
																	<a href="javascript:void(0);" id="etiqueta_tag_gondola" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG_GONDOLA'); ?></a><br><?php echo lang('ETIQUETAS_TAG_GONDOLA'); ?>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="row">
														<div class="col-md-12">
															<div class="form-group">
																<div class="col-md-12">
																	<a href="javascript:void(0);" id="etiqueta_tag_corte" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG_CORTE'); ?></a><br><?php echo lang('ETIQUETAS_TAG_CORTE'); ?>
																</div>
															</div>
														</div>
													</div>
													<br />
													<div class="row">
														<div class="col-md-12">
															<div class="form-group">
																<div class="col-md-12">
																	<a href="javascript:void(0);" id="etiqueta_tag_corte2" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG_CORTE2'); ?></a><br><?php echo lang('ETIQUETAS_TAG_CORTE2'); ?>
																</div>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12">
															<div class="form-group">
																<div class="col-md-12">
																	<a href="javascript:void(0);" id="etiqueta_tag2" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG2'); ?></a><br><?php echo lang('ETIQUETAS_TAG2'); ?>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php if ($core->modulo_impressao) : ?>
													<div class="col-md-3">
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="javascript:void(0);" id="etiqueta_tag3" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG3'); ?></a><br><?php echo lang('ETIQUETAS_TAG3'); ?>
																	</div>
																</div>
															</div>
														</div>
														<br />
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="javascript:void(0);" id="etiqueta_tag4" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG4'); ?></a><br><?php echo lang('ETIQUETAS_TAG4'); ?>
																	</div>
																</div>
															</div>
														</div>
														<br />
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="javascript:void(0);" id="etiqueta_tag_joia" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG_JOIA'); ?></a><br><?php echo lang('ETIQUETAS_TAG_JOIA'); ?>
																	</div>
																</div>
															</div>
														</div>
														<br />
													</div>
													<div class="col-md-3">
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="javascript:void(0);" id="etiqueta_tag3_preco" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG3_PRECO'); ?></a><br><?php echo lang('ETIQUETAS_TAG3_PRECO'); ?>
																	</div>
																</div>
															</div>
														</div>
														<br />
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="javascript:void(0);" id="etiqueta_tag4_preco" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG4_PRECO'); ?></a><br><?php echo lang('ETIQUETAS_TAG4_PRECO'); ?>
																	</div>
																</div>
															</div>
														</div>
														<br />
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="javascript:void(0);" id="etiqueta_tag_joia_preco" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm yellow-casablanca"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG_JOIA_PRECO'); ?></a><br><?php echo lang('ETIQUETAS_TAG_JOIA_PRECO'); ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												<?php else : ?>
													<div class="col-md-3">
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="" disabled id="etiqueta_tag3" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm grey-cararra font-grey-silver"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG3'); ?></a><br><?php echo lang('ETIQUETAS_TAG3'); ?>
																	</div>
																</div>
															</div>
														</div>
														<br />
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="" disabled id="etiqueta_tag4" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm grey-cararra font-grey-silver"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG4'); ?></a><br><?php echo lang('ETIQUETAS_TAG4'); ?>
																	</div>
																</div>
															</div>
														</div>
														<br />
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="" disabled id="etiqueta_tag_joia" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm grey-cararra font-grey-silver"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG_JOIA'); ?></a><br><?php echo lang('ETIQUETAS_TAG_JOIA'); ?>
																	</div>
																</div>
															</div>
														</div>
														<br />
													</div>
													<div class="col-md-3">
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="" disabled id="etiqueta_tag3_preco" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm grey-cararra font-grey-silver"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG3_PRECO'); ?></a><br><?php echo lang('ETIQUETAS_TAG3_PRECO'); ?>
																	</div>
																</div>
															</div>
														</div>
														<br />
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="" disabled id="etiqueta_tag4_preco" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm grey-cararra font-grey-silver"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG4_PRECO'); ?></a><br><?php echo lang('ETIQUETAS_TAG4_PRECO'); ?>
																	</div>
																</div>
															</div>
														</div>
														<br />
														<div class="row">
															<div class="col-md-12">
																<div class="form-group">
																	<div class="col-md-12">
																		<a href="" disabled id="etiqueta_tag_joia_preco" id_tabela="<?php echo $id_tabela; ?>" class="btn btn-sm grey-cararra font-grey-silver"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR_TAG_JOIA_PRECO'); ?></a><br><?php echo lang('ETIQUETAS_TAG_JOIA_PRECO'); ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												<?php endif; ?>
											</div>
										</div>
									</form>
								</div>
								<div class="portlet-body">
									<form class="form-inline" action="" method="post" name="admin_form" id="admin_form">
										<table class="table table-bordered table-condensed table-advance checkTable">
											<thead>
												<tr>
													<th class="table-checkbox">
														<input type="checkbox" class="group-checkable" data-set=".checkboxes" />
													</th>
													<th><?php echo lang('PRODUTO'); ?></th>
													<th><?php echo lang('CODIGO_DE_BARRAS'); ?></th>
													<th><?php echo lang('VALOR_VENDA'); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$totalcusto = 0;
												$totalvenda = 0;
												$retorno_row = $produto->getTabela($id_tabela);
												if ($retorno_row) :
													foreach ($retorno_row as $exrow) :
												?>
														<tr>
															<td>
																<input name="id_produto[]" type="checkbox" class="checkboxes" value="<?php echo $exrow->id_produto; ?>" />
															</td>
															<td><?php echo $exrow->nome; ?></td>
															<td><?php echo $exrow->codigobarras; ?></td>
															<td><?php echo moedap($exrow->valor_venda); ?></td>
														</tr>
													<?php endforeach; ?>
												<?php unset($exrow);
												endif; ?>
											</tbody>
										</table>
									</form>
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
	<?php
	default: ?>
		<div class="imagem-fundo">
			<img src="assets/img/logo.png" border="0">
		</div>
		<?php break; ?>
<?php endswitch; ?>