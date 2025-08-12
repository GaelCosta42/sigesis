<?php
  /**
   * Ponto Eletrônico
   *
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Todos())
	  redirect_to("login.php");
?>
<?php switch(Filter::$acao): case "horariolistar": ?>

<script type="text/javascript"> 
	$(document).ready(function () {
		var tabela_horarios = $('#tabela_horarios').dataTable();
		tabela_horarios.fnSort([[ 0, "asc" ]]);
		tabela_horarios.fnSetColumnVis( 0, false );
	});
</script>

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PONTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PONTO_HORARIO_LISTAR');?></small></h1>
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
								<i class="fa fa-calendar font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PONTO_HORARIO_LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=ponto_eletronico&acao=adicionarhorario" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('PONTO_HORARIO_ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable" id="tabela_horarios">
								<thead>
									<tr>
										<th><?php echo lang('NUMERO');?></th>
										<th><?php echo lang('PONTO_HORARIO_DIA');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_ENTRADA1');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_SAIDA1');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_ENTRADA2');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_SAIDA2');?></th>
                                        <th><?php echo lang('PONTO_VIRADA_TURNO');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_TRABALHAR');?></th>
                                        <th><?php echo lang('STATUS');?></th>
                                        <th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $pontoeletronico->getHorariosPonto();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											 $statusHorario = "Ativo";
								?>
												<tr>
													<td><?php echo $exrow->numero_dia; ?></td>
													<td><?php echo $exrow->dia;?></td>
													<td><?php echo $exrow->entrada1; ?></td>
													<td><?php echo $exrow->saida1; ?></td>
													<td><?php echo ($exrow->entrada2=='00:00:00') ? '---' : $exrow->entrada2; ?></td>
													<td><?php echo ($exrow->saida2=='00:00:00') ? '---' : $exrow->saida2; ?></td>
													<td><?php echo $exrow->virada_turno; ?></td>
													<td><?php echo $exrow->total_horas; ?></td>
													<td><?php echo $statusHorario; ?></td>
													<td>
														<a href="index.php?do=ponto_eletronico&acao=horariopontoeditar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->dia;?>"><i class="fa fa-pencil"></i></a>
														<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarHorarioPonto" title="<?php echo lang('PONTO_HORARIO_APAGAR').$exrow->dia;?>"><i class="fa fa-times"></i></a>
													</td>
												</tr>
									<?php 	endforeach;?>
									<?php 	unset($exrow);
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
<?php case "adicionarhorario": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PONTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PONTO_HORARIO_ADICIONAR');?></small></h1>
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
								<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('PONTO_HORARIO_ADICIONAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_DIA');?></label>
														<div class="col-md-9">
															<select class="select2me form-control input-large" name="dia_semana" id="dia_semana" data-placeholder="<?php echo lang('SELECIONE_DIA');?>" >
																<option value=""></option>
																<?php 
																	$retorno_row = $pontoeletronico->getDiasSemana();
																	if ($retorno_row):
																		foreach ($retorno_row as $drow):
																?>
																			<option value="<?php echo $drow['numero'].'#'.$drow['dia'];?>"><?php echo $drow['dia'];?></option>
																<?php
																		endforeach;
																	unset($drow);
																	endif;
																?>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_ENTRADA1');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control hora" name="horario_entrada1">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_ENTRADA2');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control hora" name="horario_entrada2">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_SAIDA1');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control hora" name="horario_saida1">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_SAIDA2');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control hora" name="horario_saida2">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_VIRADA_TURNO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control hora" name="virada_turno">
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
														<button type="submit" class="btn <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm("processarHorarioPonto");?>	
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
<?php case "horariopontoeditar": 
	$row = Core::getRowById("ponto_horario", Filter::$id);
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PONTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PONTO_HORARIO_ALTERAR');?></small></h1>
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
								<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('PONTO_HORARIO_ALTERAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
											<div class="row">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_DIA');?></label>
													<div class="col-md-9">
														<select class="select2me form-control input-large" name="dia_semana" data-placeholder="<?php echo lang('SELECIONE_DIA');?>" >
															<option value=""></option>
															<?php 
																$retorno_row = $pontoeletronico->getDiasSemana();
																if ($retorno_row):
																	foreach ($retorno_row as $drow):
															?>
																		<option value="<?php echo $drow['numero'].'#'.$drow['dia'];?>" <?php if($drow['numero'] == $row->numero_dia) echo 'selected="selected"';?>><?php echo $drow['dia'];?></option>
															<?php
																	endforeach;
																unset($drow);
																endif;
															?>
														</select>
														
													</div>
												</div>
											</div>
											</div>
										</div>
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_ENTRADA1');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control hora" name="horario_entrada1" value="<?php echo $row->entrada1; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_ENTRADA2');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control hora" name="horario_entrada2" value="<?php echo $row->entrada2; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_SAIDA1');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control hora" name="horario_saida1" value="<?php echo $row->saida1; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_SAIDA2');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control hora" name="horario_saida2" value="<?php echo $row->saida2; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_VIRADA_TURNO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control hora" name="virada_turno" value="<?php echo $row->virada_turno; ?>">
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
														<button type="submit" class="btn <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm("processarHorarioPonto");?>	
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
<?php case "tabelalistar":  ?>

<script type="text/javascript"> 
	$(document).ready(function () {
		var tabela_horarios = $('#tabela_horarios').dataTable();
		tabela_horarios.fnSort([[ 0, "asc" ]]);
		tabela_horarios.fnSetColumnVis( 0, false );
	});
</script>

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PONTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PONTO_TABELA_LISTAR');?></small></h1>
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
								<i class="fa fa-table font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PONTO_TABELA_LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=ponto_eletronico&acao=adicionartabela" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('PONTO_TABELA_ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable" id="tabela_horarios">
								<thead>
									<tr>
										<th><?php echo lang('id');?></th>
										<th><?php echo lang('TITULO');?></th>
                                        <th><?php echo lang('DESCRICAO');?></th>
                                        <th><?php echo lang('PONTO_TABELA_HORARIOS');?></th>
                                        <th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $pontoeletronico->getTabelasDePonto();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
												$usuarios_ponto = $pontoeletronico->obterUsuariosPonto($exrow->id);
								?>
												<tr>
													<td><?php echo $exrow->id; ?></td>
													<td><?php echo $exrow->titulo;?></td>
													<td><?php echo $exrow->descricao; ?></td>
													<td><?php echo $exrow->hora_total; ?></td>
													<td>
													<?php if ($usuarios_ponto==0): ?>
														<a href="index.php?do=ponto_eletronico&acao=tabelaPontoHorarios&id=<?php echo $exrow->id;?>" class="btn btn-sm yellow-casablanca" title="<?php echo lang('PONTO_TABELA_ADICIONAR_HORARIOS').': '.$exrow->titulo;?>"><i class="fa fa-table"></i></a>
													<?php else: ?>
														<div class="tooltips" data-container="body" data-placement="top" data-original-title="<?= $usuarios_ponto; ?> usuário(s) nesta tabela de ponto">
																
																<span class="badge badge-warning"><i class="fa fa-user"></i>&nbsp;&nbsp;<?php echo $usuarios_ponto;?></span>
														</div>
													<?php endif; ?>
													<?php if ($exrow->hora_total=='00:00:00'): ?>
														<a href="index.php?do=ponto_eletronico&acao=tabelapontoeditar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->titulo;?>"><i class="fa fa-pencil"></i></a>
														<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarTabelaPonto" title="<?php echo lang('PONTO_HORARIO_APAGAR').$exrow->titulo;?>"><i class="fa fa-times"></i></a>
													<?php endif; ?>	
													</td>
												</tr>
									<?php 	endforeach;?>
									<?php 	unset($exrow);
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
<?php case "adicionartabela": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PONTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PONTO_TABELA_ADICIONAR');?></small></h1>
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
								<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('PONTO_TABELA_ADICIONAR');?>
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
														<label class="control-label col-md-3"><?php echo lang('TITULO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="titulo_tabela">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DESCRICAO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="descricao_tabela">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ESCALA');?></label>
														<div class="col-md-9">
															<select class="select2me form-control input-large" name="escala_tabela" data-placeholder="Selecione um modelo de escala" >
																<option value="4x2">4x2</option>
																<option value="5x1">5x1</option>
																<option value="5x2" selected>5x2</option>
																<option value="6x1">6x1</option>
																<option value="12x36">12x36</option>
																<option value="18x36">18x36</option>
																<option value="24x48">24x48</option>
															</select>
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
														<button type="submit" class="btn <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm("processarTabelaPonto");?>	
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
<?php case "tabelapontoeditar": ?>
<?php $row = Core::getRowById("ponto_descricao", Filter::$id); ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PONTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PONTO_TABELA_ALTERAR');?></small></h1>
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
								<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('PONTO_TABELA_ALTERAR');?>
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
														<label class="control-label col-md-3"><?php echo lang('TITULO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="titulo_tabela" value="<?php echo $row->titulo; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DESCRICAO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control caps" name="descricao_tabela" value="<?php echo $row->descricao; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('ESCALA');?></label>
														<div class="col-md-9">
															<select class="select2me form-control input-large" name="escala_tabela" data-placeholder="Selecione um modelo de escala" >
																<option value="4x2" <?php echo ($row->escala == '4x2') ? 'selected': ''; ?>>4x2</option>
																<option value="5x1" <?php echo ($row->escala == '5x1') ? 'selected': ''; ?>>5x1</option>
																<option value="5x2" <?php echo ($row->escala == '5x2') ? 'selected': ''; ?>>5x2</option>
																<option value="6x1" <?php echo ($row->escala == '6x1') ? 'selected': ''; ?>>6x1</option>
																<option value="12x36" <?php echo ($row->escala == '12x36') ? 'selected': ''; ?>>12x36</option>
																<option value="18x36" <?php echo ($row->escala == '18x36') ? 'selected': ''; ?>>18x36</option>
																<option value="24x48" <?php echo ($row->escala == '24x48') ? 'selected': ''; ?>>24x48</option>
															</select>
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
														<button type="submit" class="btn <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm("processarTabelaPonto");?>	
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
<?php case "tabelaPontoHorarios": ?>
<?php $row = Core::getRowById("ponto_descricao", Filter::$id); ?>

<script type="text/javascript"> 
	$(document).ready(function () {
		var tabela_tabela_horarios = $('#tabela_tabela_horarios').dataTable();
		tabela_tabela_horarios.fnSort([[ 0, "asc" ]]);
		tabela_tabela_horarios.fnSetColumnVis( 0, false );
	});
</script>

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PONTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PONTO_TABELA_ADICIONAR_HORARIOS');?></small></h1>
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
								<i class="fa fa-table">&nbsp;&nbsp;</i><?php echo lang('PONTO_TABELA_ADICIONAR_HORARIOS');?>
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
														<label class="control-label col-md-3"><?php echo lang('TITULO');?></label>
														<div class="col-md-9">
															<input type="text" readonly class="form-control" name="titulo_tabela" value="<?php echo $row->titulo; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DESCRICAO');?></label>
														<div class="col-md-9">
															<input type="text" readonly class="form-control" name="descricao_tabela" value="<?php echo $row->descricao; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<hr>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_TABELA_HORARIOS_SELECIONE_TITULO');?></label>
														<div class="col-md-9">
															<select class="select2me form-control input-large" name="pontotabelahorario" data-placeholder="<?php echo lang('PONTO_TABELA_HORARIOS_SELECIONE_DESECRICAO');?>" >
																<option value=""></option>
																<?php 
																	$retorno_row = $pontoeletronico->getHorariosTabela(Filter::$id);
																	if ($retorno_row):
																		foreach ($retorno_row as $hrow):
																?>
																			<option value="<?php echo $hrow->id;?>"><?php echo "$hrow->dia = $hrow->entrada1 / $hrow->saida1 / $hrow->entrada2 / $hrow->saida2"; ?></option>
																<?php
																		endforeach;
																		unset($hrow);
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
								<input name="id_ponto_descricao" type="hidden" value="<?php echo Filter::$id;?>" />
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														<button type="submit" class="btn <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
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
							<?php echo $core->doForm("processarHorarioTabelaPonto");?>	
							<!-- FINAL FORM-->
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable" id="tabela_tabela_horarios">
								<thead>
									<tr>
										<th><?php echo lang('NUMERO');?></th>
										<th><?php echo lang('PONTO_HORARIO_DIA');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_ENTRADA1');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_SAIDA1');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_ENTRADA2');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_SAIDA2');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_TRABALHAR');?></th>
                                        <th><?php echo lang('STATUS');?></th>
                                        <th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $pontoeletronico->getHorariosPontoTabela(Filter::$id);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											 $statusHorario = "Ativo";
								?>
												<tr>
													<td><?php echo $exrow->numero_dia; ?></td>
													<td><?php echo $exrow->dia;?></td>
													<td><?php echo $exrow->entrada1; ?></td>
													<td><?php echo $exrow->saida1; ?></td>
													<td><?php echo ($exrow->entrada2=='00:00:00') ? '---' : $exrow->entrada2; ?></td>
													<td><?php echo ($exrow->saida2=='00:00:00') ? '---' : $exrow->saida2; ?></td>
													<td><?php echo $exrow->total_horas; ?></td>
													<td><?php echo $statusHorario; ?></td>
													<td>
														<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarHorarioPonto" title="<?php echo lang('PONTO_HORARIO_APAGAR').$exrow->dia;?>"><i class="fa fa-times"></i></a>
													</td>
												</tr>
									<?php 	endforeach;?>
									<?php 	unset($exrow); ?>
									
												<tfoot>
													<td colspan="6"><?php echo lang('PONTO_TABELA_HORAS'); ?></td>
													<td><?php echo $row->hora_total; ?></td>
													<td colspan="2"></td>
												</tfoot>
									<?php endif;?>
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
<?php case "relatorioponto": ?>
<?php
	$ano = (get('ano')) ? get('ano') : date('Y');
	$mes = (get('mes')) ? get('mes') : date('m');
	$id_funcionario = (get('funcionario')) ? get('funcionario') : 0;
	$funcionario = "";
	$relatorio = (!empty($id_funcionario))
		? $pontoeletronico->getRelatorioPonto($ano,$mes,$id_funcionario)
		: [];
?>

<div id="modal-abonar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><?php echo lang('PONTO_HORARIO_ABONAR');?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<p><?php echo lang('HORAS');?></p>
						<p><input type="text" class="form-control hora" name="tempo" id="tempo" value="00:00"></p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>" id="button-abonar"><?php echo lang('SALVAR');?></button>
				<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript"> 
	$(document).ready(function () {
		var tabela_relatorio_ponto = $('#tabela_relatorio_ponto').dataTable();
		tabela_relatorio_ponto.fnSort([[ 0, "asc" ]]);
		tabela_relatorio_ponto.fnSetColumnVis( 0, false );
		
		$('#buscar_ponto').click(function() {
			var ano = $("#ano").val();
			var mes = $("#mes").val();
			var id_funcionario = $("#id_funcionario").val();
			window.location.href = 'index.php?do=ponto_eletronico&acao=relatorioponto&ano='+ ano +'&mes='+ mes +'&funcionario='+ id_funcionario;
		});
		
		$('#imprimir_ponto').click(function() {
			var ano = $("#ano").val();
			var mes = $("#mes").val();
			var id_funcionario = $("#id_funcionario").val();
			window.open('pdf_relatorio_ponto.php?ano='+ ano +'&mes='+ mes +'&funcionario='+ id_funcionario,'Relatório de ponto eletrônico','width=600,height=800,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=1,left=0,top=0');
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
				<h1><?php echo lang('PONTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PONTO_RELATORIO_TITULO');?></small></h1>
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
								<i class="fa fa-history">&nbsp;&nbsp;</i><?php echo lang('PONTO_RELATORIO_TITULO');?>
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
														<label class="control-label col-md-3"><?php echo lang('ANO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control inteiro" maxlength="4" name="ano" id="ano" value="<?php echo $ano; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
											<!--col-md-6-->
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('MES');?></label>
														<div class="col-md-9">
															<select class="select2me form-control input-large" name="mes" id="mes" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
																<option value=""></option>
																<option value="01" <?php if($mes == '01') echo 'selected="selected"';?>>Janeiro</option>
																<option value="02" <?php if($mes == '02') echo 'selected="selected"';?>>Feveiro</option>
																<option value="03" <?php if($mes == '03') echo 'selected="selected"';?>>Março</option>
																<option value="04" <?php if($mes == '04') echo 'selected="selected"';?>>Abril</option>
																<option value="05" <?php if($mes == '05') echo 'selected="selected"';?>>Maio</option>
																<option value="06" <?php if($mes == '06') echo 'selected="selected"';?>>Junho</option>
																<option value="07" <?php if($mes == '07') echo 'selected="selected"';?>>Julho</option>
																<option value="08" <?php if($mes == '08') echo 'selected="selected"';?>>Agosto</option>
																<option value="09" <?php if($mes == '09') echo 'selected="selected"';?>>Setembro</option>
																<option value="10" <?php if($mes == '10') echo 'selected="selected"';?>>Outubro</option>
																<option value="11" <?php if($mes == '11') echo 'selected="selected"';?>>Novembro</option>
																<option value="12" <?php if($mes == '12') echo 'selected="selected"';?>>Dezembro</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<hr>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_RELATORIO_FUNCIONARIO_TITULO');?></label>
														<div class="col-md-9">
															<select class="select2me form-control input-large" name="id_funcionario" id="id_funcionario" data-placeholder="<?php echo lang('PONTO_RELATORIO_FUNCIONARIO');?>" >
																<option value=""></option>
																<?php 
																	$retorno_row = $usuario->getUsuariosPonto();
																	if ($retorno_row):
																		foreach ($retorno_row as $urow):
																?>
																			<option value="<?php echo $urow->id;?>" <?php if ($urow->id == $id_funcionario) { echo 'selected="selected"'; $funcionario=$urow->nome;} ?>><?php echo $urow->nome.' ('.formatar_cpf_cnpj($urow->cpf).')'; ?></option>
																<?php
																		endforeach;
																		unset($urow);
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
								<input name="id_ponto_descricao" type="hidden" value="<?php echo 1;?>" />
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="col-md-offset-3 col-md-9">
														<button type="button" id="buscar_ponto" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
														&nbsp;&nbsp;
														<button type="button" id="imprimir_ponto" class="btn green"><i class="fa fa-print"/></i> <?php echo lang('IMPRIMIR');?></button>
														&nbsp;&nbsp;
														<a href="#modal-abonar" <?php echo ((empty($_GET['funcionario'])) ? 'disabled' : '') ?> class="btn btn-sm <?php echo $core->primeira_cor;?>" data-toggle="modal"><i class="fa fa-clock-o">&nbsp;&nbsp;</i><?php echo lang('PONTO_HORARIO_ABONAR');?></a>													</div>
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
						<?php if(!empty($relatorio['ponto'])): ?>
						<div class="portlet-body">
							<!-- INICIO FORM-->
							<div class="form-horizontal">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<!--col-md-6-->
											<div class="col-md-4">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-2"><?php echo lang('TITULO');?></label>
														<div class="col-md-9">
															<input type="text" class="form-control" disabled value="<?php echo $relatorio['ponto']['descricao']->titulo; ?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-2"><?php echo lang('DESCRICAO');?></label>
														<div class="col-md-9">
														<input type="text" class="form-control" disabled value="<?php echo $relatorio['ponto']['descricao']->descricao; ?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('PONTO_HORARIO_TRABALHAR');?></label>
														<div class="col-md-8">
														<input type="text" class="form-control" disabled value="<?php echo $relatorio['ponto']['descricao']->hora_total; ?>">
														</div>
													</div>
												</div>
											</div>
											<!--/col-md-6-->
										</div>
									</div>
								</div>
							</div>
							<!-- FINAL FORM-->
							<table class="table table-bordered table-striped table-condensed table-advance">
								<thead>
									<tr>
										<th><?php echo lang('PONTO_HORARIO_DIA');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_ENTRADA1');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_SAIDA1');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_ENTRADA2');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_SAIDA2');?></th>
                                        <th><?php echo lang('PONTO_HORARIO_TRABALHAR');?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($relatorio['ponto']['horarios'] as $horario): ?>
									<tr>
										<td><?php echo $horario->dia;?></td>
										<td><?php echo $horario->entrada1; ?></td>
										<td><?php echo $horario->saida1; ?></td>
										<td><?php echo ($horario->entrada2=='00:00:00') ? '---' : $horario->entrada2; ?></td>
										<td><?php echo ($horario->saida2=='00:00:00') ? '---' : $horario->saida2; ?></td>
										<td><?php echo $horario->total_horas; ?></td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
						<?php endif; ?>
						<div class="portlet-body">
							<table style="width: 100%;" class="table table-bordered table-striped table-condensed dataTable" id="tabela_relatorio_ponto" >
								<thead>
									<tr>
										<th width="1px" style="white-space: nowrap;"><?php echo lang('DATA');?></th>
										<th width="1px" style="white-space: nowrap;"><input type="checkbox" class="tabela_checkbox_ponto_todos"/></th>
										<th width="1px" style="white-space: nowrap;"><?php echo lang('DATA');?></th>
										<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_DIA');?></th>
                                        <th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_ENTRADA1');?></th>
                                        <th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_SAIDA1');?></th>
                                        <th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_ENTRADA2');?></th>
                                        <th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_SAIDA2');?></th>
										<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_ENTRADA3');?></th>
                                        <th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_SAIDA3');?></th>
										<th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_ENTRADA4');?></th>
                                        <th width="1px" style="white-space: nowrap;"><?php echo lang('PONTO_HORARIO_SAIDA4');?></th>
                                        <th width="1px"><?php echo lang('PONTO_RELATORIO_HORAS');?></th>
										<th width="1px"><?php echo lang('PONTO_HORARIO_ABONO');?></th>
										<th width="1px"><?php echo lang('PONTO_HORARIO_TRABALHAR2');?></th>
                                        <th width="1px"><?php echo lang('PONTO_RELATORIO_SALDO');?></th>
                                        <th width="1px" style="white-space: nowrap;"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$total_horas_trabalhadas = 0;
										$total_horas_abono = 0;
										$total_horas_dia = 0;
										$saldo_mes = 0;
										if(!empty($relatorio['relatorio'])):
											foreach ($relatorio['relatorio'] as $exrow):
												$total_horas_trabalhadas += hora_para_segundos($exrow['horas_trabalhadas']);
												$total_horas_abono += hora_para_segundos($exrow['horas_abono']);
												$total_horas_dia += hora_para_segundos($exrow['horas_dia']);
												$saldo_mes += hora_para_segundos($exrow['saldo_dia']);
												$operacoes = $exrow['operacoes'];
												if ($exrow['status_saldo']=='negativo') {
													$estilo = "class='danger'";
												} else if ($exrow['status_saldo']=='positivo') {
													$estilo = "class='success'";
												} else if ($exrow['status_saldo']=='alerta') {
													$estilo = "class='warning'";
												} else {
													$estilo = '';
												}
								?>
												<tr <?php echo $estilo; ?>>
													<td><?php echo $exrow['data']; ?></td>
													<td><input type="checkbox" class="tabela_checkbox_ponto_linha" data-dia="<?php echo $exrow['data']; ?>"/></td>
													<td class="data_editar_ponto"><?php echo exibedata($exrow['data']); ?></td>
													<td><?php echo $exrow['dia_semana'];?></td>
													<td style="white-space: nowrap;">
														<?php if (!empty($operacoes['entrada1']['horario']) && $operacoes['entrada1']['horario'] !== '00:00:00'): ?>
														<input id="<?php echo $operacoes['entrada1']['id'] ?? '0'; ?>" operacao="1" class="hora2" type="text" style="width: 50px;" disabled value="<?php echo $operacoes['entrada1']['horario'] ?? '00:00:00'; ?>">
														<span style="display:none;">---</span>
														<a href="https://www.google.com/maps/search/?api=1&query=<?php echo $operacoes['entrada1']['lat'].','.$operacoes['entrada1']['lng'];?>" class="btn btn-icon-only blue" target="_blank">
															<i class="fa fa-map-marker"></i>
														</a>
														<?php else: ?>
														<input id="<?php echo $operacoes['entrada1']['id'] ?? '0'; ?>" operacao="1" class="hora2" type="text" style="width: 50px; display:none;" disabled value="<?php echo $operacoes['entrada1']['horario'] ?? '00:00:00'; ?>">
														<span>---</span>
														<?php endif; ?>
														<?php echo (!empty($operacoes['entrada1']) && $operacoes['entrada1']['usuario'] != 'app' ) ? '*' : '' ?>

														

													</td>
													<td style="white-space: nowrap;">
														<?php if (!empty($operacoes['saida1']['horario']) && $operacoes['saida1']['horario'] !== '00:00:00'): ?>
														<input id="<?php echo $operacoes['saida1']['id'] ?? '0'; ?>" operacao="2" class="hora2" type="text" style="width: 50px;" disabled value="<?php echo $operacoes['saida1']['horario'] ?? '00:00:00'; ?>">
														<span style="display:none;">---</span>
														<a href="https://www.google.com/maps/search/?api=1&query=<?php echo $operacoes['saida1']['lat'].','.$operacoes['saida1']['lng'];?>" class="btn btn-icon-only blue" target="_blank">
															<i class="fa fa-map-marker"></i>
														</a>
														<?php else: ?>
														<input id="<?php echo $operacoes['saida1']['id'] ?? '0'; ?>" operacao="2" class="hora2" type="text" style="width: 50px; display:none;" disabled value="<?php echo $operacoes['saida1']['horario'] ?? '00:00:00'; ?>">
														<span>---</span>
														<?php endif; ?>
														<?php echo (!empty($operacoes['saida1']) && $operacoes['saida1']['usuario'] != 'app' ) ? '*' : '' ?>
													</td>
													<td style="white-space: nowrap;">
														<?php if (!empty($operacoes['entrada2']['horario']) && $operacoes['entrada2']['horario'] !== '00:00:00'): ?>
														<input id="<?php echo $operacoes['entrada2']['id'] ?? '0'; ?>" operacao="1" class="hora2" type="text" style="width: 50px;" disabled value="<?php echo $operacoes['entrada2']['horario'] ?? '00:00:00'; ?>">
														<span style="display:none;">---</span>
														<a href="https://www.google.com/maps/search/?api=1&query=<?php echo $operacoes['entrada2']['lat'].','.$operacoes['entrada2']['lng'];?>" class="btn btn-icon-only blue" target="_blank">
															<i class="fa fa-map-marker"></i>
														</a>
														<?php else: ?>
														<input id="<?php echo $operacoes['entrada2']['id'] ?? '0'; ?>" operacao="1" class="hora2" type="text" style="width: 50px; display:none;" disabled value="<?php echo $operacoes['entrada2']['horario'] ?? '00:00:00'; ?>">
														<span>---</span>
														<?php endif; ?>
														<?php echo (!empty($operacoes['entrada2']) && $operacoes['entrada2']['usuario'] != 'app' ) ? '*' : '' ?>
													</td>
													<td style="white-space: nowrap;">
														<?php if (!empty($operacoes['saida2']['horario']) && $operacoes['saida2']['horario'] !== '00:00:00'): ?>
														<input id="<?php echo $operacoes['saida2']['id'] ?? '0'; ?>" operacao="2" class="hora2" type="text" style="width: 50px;" disabled value="<?php echo $operacoes['saida2']['horario'] ?? '00:00:00'; ?>">
														<span style="display:none;">---</span>
														<a href="https://www.google.com/maps/search/?api=1&query=<?php echo $operacoes['saida2']['lat'].','.$operacoes['saida2']['lng'];?>" class="btn btn-icon-only blue" target="_blank">
															<i class="fa fa-map-marker"></i>
														</a>
														<?php else: ?>
														<input id="<?php echo $operacoes['saida2']['id'] ?? '0'; ?>" operacao="2" class="hora2" type="text" style="width: 50px; display:none;" disabled value="<?php echo $operacoes['saida2']['horario'] ?? '00:00:00'; ?>">
														<span>---</span>
														<?php endif; ?>
														<?php echo (!empty($operacoes['saida2']) && $operacoes['saida2']['usuario'] != 'app' ) ? '*' : '' ?>
													</td>
													<td>
														<?php if (!empty($operacoes['entrada3']['horario']) && $operacoes['entrada3']['horario'] !== '00:00:00'): ?>
														<input id="<?php echo $operacoes['entrada3']['id'] ?? '0'; ?>" operacao="1" class="hora2" type="text" style="width: 50px;" disabled value="<?php echo $operacoes['entrada3']['horario'] ?? '00:00:00'; ?>">
														<span style="display:none;">---</span>
														<a href="https://www.google.com/maps/search/?api=1&query=<?php echo $operacoes['entrada3']['lat'].','.$operacoes['entrada3']['lng'];?>" class="btn btn-icon-only blue" target="_blank">
															<i class="fa fa-map-marker"></i>
														</a>
														<?php else: ?>
														<input id="<?php echo $operacoes['entrada3']['id'] ?? '0'; ?>" operacao="1" class="hora2" type="text" style="width: 50px; display:none;" disabled value="<?php echo $operacoes['entrada3']['horario'] ?? '00:00:00'; ?>">
														<span>---</span>
														<?php endif; ?>
														<?php echo (!empty($operacoes['entrada3']) && $operacoes['entrada3']['usuario'] != 'app' ) ? '*' : '' ?>
													</td>
													<td style="white-space: nowrap;">
														<?php if (!empty($operacoes['saida3']['horario']) && $operacoes['saida3']['horario'] !== '00:00:00'): ?>
														<input id="<?php echo $operacoes['saida3']['id'] ?? '0'; ?>" operacao="2" class="hora2" type="text" style="width: 50px;" disabled value="<?php echo $operacoes['saida3']['horario'] ?? '00:00:00'; ?>">
														<span style="display:none;">---</span>
														<a href="https://www.google.com/maps/search/?api=1&query=<?php echo $operacoes['saida3']['lat'].','.$operacoes['saida3']['lng'];?>" class="btn btn-icon-only blue" target="_blank">
															<i class="fa fa-map-marker"></i>
														</a>
														<?php else: ?>
														<input id="<?php echo $operacoes['saida3']['id'] ?? '0'; ?>" operacao="2" class="hora2" type="text" style="width: 50px; display:none;" disabled value="<?php echo $operacoes['saida3']['horario'] ?? '00:00:00'; ?>">
														<span>---</span>
														<?php endif; ?>
														<?php echo (!empty($operacoes['saida3']) && $operacoes['saida3']['usuario'] != 'app' ) ? '*' : '' ?>
													</td>
													<td style="white-space: nowrap;">
														<?php if (!empty($operacoes['entrada4']['horario']) && $operacoes['entrada4']['horario'] !== '00:00:00'): ?>
														<input id="<?php echo $operacoes['entrada4']['id'] ?? '0'; ?>" operacao="1" class="hora2" type="text" style="width: 50px;" disabled value="<?php echo $operacoes['entrada4']['horario'] ?? '00:00:00'; ?>">
														<span style="display:none;">---</span>
														<a href="https://www.google.com/maps/search/?api=1&query=<?php echo $operacoes['entrada4']['lat'].','.$operacoes['entrada4']['lng'];?>" class="btn btn-icon-only blue" target="_blank">
															<i class="fa fa-map-marker"></i>
														</a>
														<?php else: ?>
														<input id="<?php echo $operacoes['entrada4']['id'] ?? '0'; ?>" operacao="1" class="hora2" type="text" style="width: 50px; display:none;" disabled value="<?php echo $operacoes['entrada4']['horario'] ?? '00:00:00'; ?>">
														<span>---</span>
														<?php endif; ?>
														<?php echo (!empty($operacoes['entrada4']) && $operacoes['entrada4']['usuario'] != 'app' ) ? '*' : '' ?>
													</td>
													<td style="white-space: nowrap;">
														<?php if (!empty($operacoes['saida4']['horario']) && $operacoes['saida4']['horario'] !== '00:00:00'): ?>
														<input id="<?php echo $operacoes['saida4']['id'] ?? '0'; ?>" operacao="2" class="hora2" type="text" style="width: 50px;" disabled value="<?php echo $operacoes['saida4']['horario'] ?? '00:00:00'; ?>">
														<span style="display:none;">---</span>
														<a href="https://www.google.com/maps/search/?api=1&query=<?php echo $operacoes['saida4']['lat'].','.$operacoes['saida4']['lng'];?>" class="btn btn-icon-only blue" target="_blank">
															<i class="fa fa-map-marker"></i>
														</a>
														<?php else: ?>
														<input id="<?php echo $operacoes['saida4']['id'] ?? '0'; ?>" operacao="2" class="hora2" type="text" style="width: 50px; display:none;" disabled value="<?php echo $operacoes['saida4']['horario'] ?? '00:00:00'; ?>">
														<span>---</span>
														<?php endif; ?>
														<?php echo (!empty($operacoes['saida4']) && $operacoes['saida4']['usuario'] != 'app' ) ? '*' : '' ?>
													</td>
													<td><?php echo $exrow['horas_trabalhadas']; ?></td>
													<td><?php echo $exrow['horas_abono']; ?></td>
													<td><?php echo $exrow['horas_dia']; ?></td>
													<td><?php echo $exrow['saldo_dia']; ?></td>
													<td style="white-space: nowrap;">
														<a href="javascript:void(0);" class="btn btn-sm blue editar_ponto"><i class="fa fa-pencil"></i></a>
														<a style="display: none;" href="javascript:void(0);" class="btn btn-sm green salvar_editar_ponto"><i class="fa fa-save"></i></a>
														<a style="display: none;" href="javascript:void(0);" class="btn btn-sm red cancelar_editar_ponto"><i class="fa fa-times"></i></a>
													</td>
												</tr>
									<?php 	endforeach;?>
									<?php 	unset($exrow); ?>
												<tfoot>
													<td colspan="12"><?php echo lang('PONTO_TABELA_HORAS'); ?></td>
													<td><?php echo segundos_para_hora($total_horas_trabalhadas); ?></td>
													<td><?php echo segundos_para_hora($total_horas_abono); ?></td>
													<td><?php echo segundos_para_hora($total_horas_dia); ?></td>
													<td><?php echo segundos_para_hora($saldo_mes); ?></td>
													<td></td>
												</tfoot>
									<?php endif;?>
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
<?php case "feriadolistar": ?>

<script type="text/javascript"> 
	$(document).ready(function () {
		var tabela_feriados = $('#tabela_feriados').dataTable();
		tabela_feriados.fnSort([[ 0, "asc" ]]);
		tabela_feriados.fnSetColumnVis( 0, false );
	});
</script>

<div id="novo-feriado" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><?php echo lang('FERIADO_TITULO');?></h4>
			</div>
			<form action="" method="post" name="feriado_form" id="feriado_form" >
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p><?php echo lang('FERIADO');?></p>
							<p><input type="text" class="form-control caps" name="feriado" id="feriado_editar"></p>
							<p><?php echo lang('DATA');?></p>
							<p><input type="text" class="form-control data calendario" name="data" id="data_editar"></p>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div><?php echo $core->doForm("processarFeriado", "feriado_form");?>
</div>

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PONTO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PONTO_FERIADO_LISTAR');?></small></h1>
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
								<i class="fa fa-sun-o font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PONTO_FERIADO_LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="#novo-feriado" class="btn btn-sm <?php echo $core->primeira_cor;?>" data-toggle="modal"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('PONTO_FERIADO_ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable" id="tabela_feriados">
								<thead>
									<tr>
										<th><?php echo lang('DATA');?></th>
										<th><?php echo lang('FERIADO');?></th>
										<th><?php echo lang('DIA_SEMANA');?></th>
                                        <th><?php echo lang('DATA');?></th>
                                        <th><?php echo lang('STATUS');?></th>
                                        <th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $pontoeletronico->getFeriados();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
												$ano_feriado = date('Y', strtotime($exrow->data));
												$statusFeriado = ($ano_feriado==date("Y")) ? "Validado" : "Pendente";
												$diaSemana = ($statusFeriado=="Validado") ? $pontoeletronico->getDiaDaSemana(date('N', strtotime($exrow->data))) : "---";
												$estilo = ($statusFeriado=="Validado") ? "" : "class='danger'";
								?>
												<tr <?php echo $estilo; ?>>
													<td><?php echo $exrow->data; ?></td>
													<td><?php echo $exrow->feriado;?></td>
													<td><?php echo $diaSemana; ?></td>
													<td><?php echo exibedata($exrow->data); ?></td>
													<td><?php echo $statusFeriado; ?></td>
													<td>
													<?php if (($exrow->data > date('Y-m-d')) || ($statusFeriado=="Pendente")): ?>
														<a href="javascript:void(0);" class="btn btn-sm blue editarferiado" title="<?php echo lang('EDITAR').': '.$exrow->feriado;?>" id="<?php echo $exrow->id; ?>" feriado="<?php echo $exrow->feriado; ?>" data="<?php echo exibedata($exrow->data); ?>"><i class="fa fa-pencil"></i></a>
														<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarFeriado" title="<?php echo lang('FERIADO_APAGAR').$exrow->feriado;?>"><i class="fa fa-times"></i></a>
													<?php endif; ?>	
													</td>
												</tr>
									<?php 	endforeach;?>
									<?php 	unset($exrow);
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