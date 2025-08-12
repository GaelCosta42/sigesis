<?php
  /**
   * Pedido
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
<?php switch(Filter::$acao): case "novo": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PEDIDO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PEDIDO_NOVO');?></small></h1>
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
								<i class="fa fa-plus font-<?php echo $core->primeira_cor;?>"></i>	
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PEDIDO_NOVO');?></span>
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
														<label class="control-label col-md-3"><?php echo lang('CENTRO_CUSTO');?></label>
														<div class="col-md-9">
															<select class="select2me form-control input-xlarge" name="id_custo" id="id_custo" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
																	<option value=""></option>
																	<?php 
																		$retorno_row = $despesa->getCentroCusto();
																		if ($retorno_row):
																			foreach ($retorno_row as $srow):
																	?>
																				<option value="<?php echo $srow->id;?>"><?php echo $srow->centro_custo;?></option>
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
															<input readonly type="text" class="form-control caps" value="<?php echo $usuario->nomeusuario;?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DESCRICAO');?></label>
														<div class="col-md-9">
															<textarea name="descricao" rows="4" class="form-control caps"></textarea>
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
							<?php echo $core->doForm("processarPedido");?>	
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
<?php case "editar": 
	$row = Core::getRowById("pedido", Filter::$id); ?>
<div id="produto-novo" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-barcode">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_ADICIONAR');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="produto_form" id="produto_form" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">	
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo lang('PRODUTO');?></label>
								<div class="col-md-9">
									<select class="select2me form-control input-xlarge" name="id_produto" data-placeholder="<?php echo lang('SELECIONE_PRODUTO');?>" >
										<option value=""></option>
										<?php 
											$retorno_row = $produto->getProdutos();
											if ($retorno_row):
												foreach ($retorno_row as $srow):
										?>
													<option value="<?php echo $srow->id;?>"><?php echo $srow->codigo."#".$srow->nome;?></option>
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
								<div class="col-md-9">
									<input type="text" class="form-control decimal" name="quantidade">
								</div>
							</div>
						</div>
					</div>
				</div>
				<input name="id_pedido" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit <?php echo $core->primeira_cor;?>"><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("processarProdutoPedido", "produto_form");?>
</div>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PEDIDO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PEDIDO_NOVO');?></small></h1>
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
								<i class="fa fa-pencil font-<?php echo $core->primeira_cor;?>"></i>	
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PEDIDO_EDITAR');?></span>
							</div>
							<div class="actions btn-set">
								<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
									<input name="id" type="hidden" value="<?php echo Filter::$id;?>" />
									<button type="button" class="btn btn-submit green"><?php echo lang('PEDIDO_FINALIZAR');?></button>
								</form>
								<?php echo $core->doForm("processarFinalizarPedido");?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" class="form-horizontal">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CODIGO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->id;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_PEDIDO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo exibedata($row->data_pedido);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CENTRO_CUSTO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo getValue("centro_custo", "centro_custo", "id='".$row->id_custo."'");?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('USUARIO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->usuario_pedido;?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DESCRICAO');?></label>
														<div class="col-md-9">
															<textarea rows="5" readonly class="form-control caps"><?php echo $row->descricao;?></textarea>
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
														<a href="#produto-novo" class="btn <?php echo $core->primeira_cor;?>" data-toggle="modal"><i class="fa fa-plus">&nbsp;&nbsp;</i><?php echo lang('PEDIDO_PRODUTO');?></a>
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
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance dataTable">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('PRODUTO');?></th>
										<th><?php echo lang('QUANTIDADE_PEDIDO');?></th>
										<th width="100px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 											
										$retorno_row = $cotacao->getPedidosItens($row->id);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
										$estilo = "";
										if($exrow->inativo) {
											$estilo = 'class="danger"';
										}
										
								?>
									<tr>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo $exrow->produto;?></td>
										<td><?php echo decimal($exrow->quantidade_pedido);?></td>
										<td>
											<a href="javascript:void(0);" class="btn btn-sm red apagar" acao="apagarProdutoPedido" id="<?php echo $exrow->id;?>"  title="<?php echo lang('PRODUTO_APAGAR').$exrow->produto;?>"><i class="fa fa-times"></i></a>
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
			<!-- FINAL DO ROW FORMULARIO -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "visualizar": 
	$row = Core::getRowById("pedido", Filter::$id); ?>
<script src="./assets/scripts/table-pedido.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {  
   TableEditable.init();
});
</script>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#imprimir_pedido').click(function() {
			window.open('pdf_pedido.php?id=<?php echo Filter::$id;?>','Imprimir Pedido','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
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
				<h1><?php echo lang('PEDIDO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PEDIDO_VISUALIZAR');?></small></h1>
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
								<i class="fa fa-search font-<?php echo $core->primeira_cor;?>"></i>	
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PEDIDO_VISUALIZAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="javascript:void(0);" id="imprimir_pedido" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR');?></a>
								<button type="button" id="voltar" class="btn btn-sm default"><i class="fa fa-repeat">&nbsp;&nbsp;</i><?php echo lang('VOLTAR');?></button>
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
														<label class="control-label col-md-3"><?php echo lang('CODIGO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->id;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_PEDIDO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo exibedata($row->data_pedido);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('CENTRO_CUSTO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo getValue("centro_custo", "centro_custo", "id='".$row->id_custo."'");?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('USUARIO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->usuario_pedido;?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DESCRICAO');?></label>
														<div class="col-md-9">
															<textarea rows="5" readonly class="form-control caps"><?php echo $row->descricao;?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
							<!-- FINAL FORM-->
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance" id="table_tabela">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('PRODUTO');?></th>
										<th><?php echo lang('QUANTIDADE_PEDIDO');?></th>
										<th><?php echo lang('ENTREGUE');?></th>
										<th width="150px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php
										$retorno_row = $cotacao->getPedidosItens($row->id);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
										$estilo = "";
										if($exrow->inativo) {
											$estilo = 'class="danger"';
										}
										if($exrow->entrega) {
											$entregue = "<span class='label label-sm bg-green'>".lang('MSIM')."</span>";
										} else {
											$entregue = "<span class='label label-sm bg-red'>".lang('MNAO')."</span>";
										}
										
								?>
									<tr <?php echo $estilo;?>>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo $exrow->produto;?></td>
										<td><?php echo decimal($exrow->quantidade_pedido);?></td>
										<td><?php echo $entregue;?></td>
										<td>
											<?php if($usuario->is_Administrativo()): ?>
												<?php if($exrow->inativo): ?>
													<a href="javascript:void(0);" class="btn btn-sm green ativar_produto" id="<?php echo $exrow->id;?>" title="<?php echo lang('ATIVAR').": ".$exrow->produto;?>"><i class="fa fa-check"></i></a>
												<?php else: ?>
													<a href="javascript:void(0);" class="btn btn-sm green entregapedidoitem" id_pedido="<?php echo $row->id;?>" id="<?php echo $exrow->id;?>"  title="<?php echo lang('PEDIDO_ENTREGAR').$exrow->id;?>"><i class="fa fa-check-square-o"></i></a>
													<a href="javascript:void(0);" class="btn btn-sm blue alterar" id="<?php echo $exrow->id;?>" title="<?php echo lang('ALTERAR').": ".$exrow->produto;?>"><i class="fa fa-pencil"></i></a>
													<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>"  title="<?php echo lang('PRODUTO_APAGAR').$exrow->produto;?>"><i class="fa fa-times"></i></a>
												<?php endif; ?>
											<?php elseif(($row->id_status == 1 and $row->usuario_pedido == $usuario->nomeusuario)): ?>
												<?php if(!$exrow->inativo): ?>
												<a href="javascript:void(0);" class="btn btn-sm blue alterar" id="<?php echo $exrow->id;?>" title="<?php echo lang('ALTERAR').": ".$exrow->produto;?>"><i class="fa fa-pencil"></i></a>
														<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>"  title="<?php echo lang('PRODUTO_APAGAR').$exrow->produto;?>"><i class="fa fa-times"></i></a>
												<?php endif; ?>
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
				<h1><?php echo lang('PEDIDO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PEDIDO_LISTAR');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PEDIDO_LISTAR');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable-desc">
								<thead>
									<tr>
										<th><?php echo lang('CODIGO');?></th>
										<th><?php echo lang('CODIGO');?></th>
										<th><?php echo lang('CENTRO_CUSTO');?></th>
										<th><?php echo lang('DATA_PEDIDO');?></th>
										<th><?php echo lang('COTACAO');?></th>
										<th><?php echo lang('DATA_COTACAO');?></th>
										<th><?php echo lang('COTACAO');?></th>
										<th><?php echo lang('STATUS');?></th>
                                        <th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $cotacao->getPedidos();
										if($retorno_row):
										foreach ($retorno_row as $exrow):
								?>
									<tr>
										<td><?php echo $exrow->id;?></td>
										<td><a href="index.php?do=pedido&acao=visualizar&id=<?php echo $exrow->id;?>"><?php echo $exrow->id;?></a></td>
										<td><?php echo $exrow->centro_custo;?></td>
										<td><?php echo exibedata($exrow->data_pedido);?></td>
										<td><?php echo $exrow->id_cotacao;?></td>
										<td><?php echo exibedata($exrow->data_abertura);?></td>
										<td><?php echo statusCotacao($exrow->id_status_cotacao);?></td>
										<td><?php echo statusPedido($exrow->id_status);?></td>
										<td>
											<a href="index.php?do=pedido&acao=visualizar&id=<?php echo $exrow->id;?>" class="btn btn-sm grey-cascade" title="<?php echo lang('VISUALIZAR').': '.$exrow->id;?>"><i class="fa fa-search"></i></a>
											<?php if($usuario->is_Administrativo()):?>
												<a href="javascript:void(0);" class="btn btn-sm green entregapedido" id="<?php echo $exrow->id;?>"  title="<?php echo lang('PEDIDO_ENTREGAR').$exrow->id;?>"><i class="fa fa-check-square-o"></i></a>
												<a href="javascript:void(0);" class="btn btn-sm red apagar" acao="apagarPedido" id="<?php echo $exrow->id;?>"  title="<?php echo lang('PEDIDO_APAGAR').$exrow->id;?>"><i class="fa fa-times"></i></a>
											<?php endif;?>
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
<?php case "aberto": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PEDIDO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PEDIDO_ABERTO');?></small></h1>
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
								<i class="fa fa-folder-open font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PEDIDO_ABERTO');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable-desc">
								<thead>
									<tr>
										<th><?php echo lang('CODIGO');?></th>
										<th><?php echo lang('CODIGO');?></th>
										<th><?php echo lang('CENTRO_CUSTO');?></th>
										<th><?php echo lang('DATA_PEDIDO');?></th>
										<th><?php echo lang('COTACAO');?></th>
										<th><?php echo lang('DATA_COTACAO');?></th>
										<th><?php echo lang('STATUS');?></th>
                                        <th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $cotacao->getPedidosAberto();
										if($retorno_row):
										foreach ($retorno_row as $exrow):?>
									<tr>
										<td><?php echo $exrow->id;?></td>
										<td><a href="index.php?do=pedido&acao=visualizar&id=<?php echo $exrow->id;?>"><?php echo $exrow->id;?></a></td>
										<td><?php echo $exrow->centro_custo;?></td>
										<td><?php echo exibedata($exrow->data_pedido);?></td>
										<td><?php echo $exrow->id_cotacao;?></td>
										<td><?php echo exibedata($exrow->data_abertura);?></td>
										<td><?php echo statusCotacao($exrow->id_status_cotacao);?></td>
										<td>
											<a href="index.php?do=pedido&acao=visualizar&id=<?php echo $exrow->id;?>" class="btn btn-sm grey-cascade" title="<?php echo lang('VISUALIZAR').': '.$exrow->id;?>"><i class="fa fa-search"></i></a>
											<a href="javascript:void(0);" class="btn btn-sm red apagar" acao="apagarPedido" id="<?php echo $exrow->id;?>"  title="<?php echo lang('PEDIDO_APAGAR').$exrow->id;?>"><i class="fa fa-times"></i></a>
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
<?php case "pendentes": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PEDIDO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PEDIDO_PENDENTES');?></small></h1>
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
								<i class="fa fa-folder-o font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PEDIDO_PENDENTES');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable-desc">
								<thead>
									<tr>
										<th><?php echo lang('CODIGO');?></th>
										<th><?php echo lang('CODIGO');?></th>
										<th><?php echo lang('CENTRO_CUSTO');?></th>
										<th><?php echo lang('DATA_PEDIDO');?></th>
                                        <th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $cotacao->getPedidosPendentes();
										if($retorno_row):
										foreach ($retorno_row as $exrow):?>
									<tr>
										<td><?php echo $exrow->id;?></td>
										<td><a href="index.php?do=pedido&acao=visualizar&id=<?php echo $exrow->id;?>"><?php echo $exrow->id;?></a></td>
										<td><?php echo $exrow->centro_custo;?></td>
										<td><?php echo exibedata($exrow->data_pedido);?></td>
										<td>
											<a href="index.php?do=pedido&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->id;?>"><i class="fa fa-pencil"></i></a>
											<a href="javascript:void(0);" class="btn btn-sm red apagar" acao="apagarPedido" id="<?php echo $exrow->id;?>"  title="<?php echo lang('PEDIDO_APAGAR').$exrow->id;?>"><i class="fa fa-times"></i></a>
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