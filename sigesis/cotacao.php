<?php
  /**
   * Cotacao
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
<?php switch(Filter::$acao): case "visualizar": 
	$row = Core::getRowById("cotacao", Filter::$id);
	$id_cadastro = get('id_cadastro');
	$id_produto = get('id_produto');
	$id_status = $row->id_status;
	$valida = $cotacao->validaCotacao(Filter::$id);
	if(!$valida and $row->id_status == 2) {
		$id_status = 3;
	}
?>
<script src="./assets/scripts/table-cotacao.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {  
   TableEditable.init();
});
</script>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#imprimir_cotacao').click(function() {
			window.open('pdf_cotacao.php?id=<?php echo Filter::$id;?>','Imprimir Cotação','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
	});
	// ]]>
</script>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#enviar_email').click(function() {
			window.open('email_cotacao.php?id=<?php echo Filter::$id;?>','Enviar cotação por e-mail','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
	});
	// ]]>
</script>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#id_cadastro').click(function() {
			var id_cadastro = $("#id_cadastro").val();
			var id_produto = $("#id_produto").val();
			window.location.href = 'index.php?do=cotacao&acao=visualizar&id=<?php echo Filter::$id;?>&id_cadastro='+id_cadastro + '&id_produto='+id_produto;
		});
	});
	// ]]>
</script>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#id_produto').click(function() {
			var id_cadastro = $("#id_cadastro").val();
			var id_produto = $("#id_produto").val();
			window.location.href = 'index.php?do=cotacao&acao=visualizar&id=<?php echo Filter::$id;?>&id_cadastro='+id_cadastro + '&id_produto='+id_produto;
		});
	});
	// ]]>
</script>
<div id="enviar-cotacao" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-folder-open">&nbsp;&nbsp;</i><?php echo lang('COTACAO_ABERTA');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="enviar_form" id="enviar_form" class="form-horizontal">
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo lang('DATA_FECHAMENTO');?></label>
						<div class="col-md-8">
							<div class="input-group date datahora">
								<input type="text" size="16" readonly class="form-control" name="data_fechamento">
								<span class="input-group-btn">
								<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
							<!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo lang('OBSERVACAO');?></label>
						<div class="col-md-8">
								<input type="text" class="form-control caps" name="observacao">
						</div>
					</div>
				</div>
				<input name="id_cotacao" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit purple"><i class="fa fa-send">&nbsp;&nbsp;</i><?php echo lang('COTACAO_ENVIAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("enviarCotacao", "enviar_form");?>
</div>
<div id="reabrir-cotacao" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-repeat">&nbsp;&nbsp;</i><?php echo lang('COTACAO_REABRIR');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="reabrir_form" id="reabrir_form" class="form-horizontal">
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo lang('DATA_FECHAMENTO');?></label>
						<div class="col-md-8">
							<div class="input-group date datahora">
								<input type="text" size="16" readonly class="form-control" name="data_fechamento">
								<span class="input-group-btn">
								<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
							<!-- /input-group -->
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo lang('OBSERVACAO');?></label>
						<div class="col-md-8">
								<input type="text" class="form-control caps" name="observacao">
						</div>
					</div>
				</div>
				<input name="id_cotacao" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit green"><i class="fa fa-save">&nbsp;&nbsp;</i><?php echo lang('COTACAO_SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("reabrirCotacao", "reabrir_form");?>
</div>
<div id="validar-cotacao" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-check-square">&nbsp;&nbsp;</i><?php echo lang('COTACAO_VALIDACAO');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="validar_form" id="validar_form" class="form-horizontal">
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-12"><?php echo lang('COTACAO_VALIDAR_AVISO');?></label>
					</div>
				</div>
				<input name="id_cotacao" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit blue-madison"><i class="fa fa-check-square">&nbsp;&nbsp;</i><?php echo lang('COTACAO_VALIDAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("validarCotacao", "validar_form");?>
</div>
<div id="aprovar-cotacao" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-check">&nbsp;&nbsp;</i><?php echo lang('COTACAO_APROVACAO');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="aprovar_form" id="aprovar_form" class="form-horizontal">
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-12"><?php echo lang('COTACAO_APROVAR_AVISO');?></label>
					</div>
				</div>
				<input name="id_cotacao" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit blue"><i class="fa fa-check">&nbsp;&nbsp;</i><?php echo lang('COTACAO_APROVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("aprovarCotacao", "aprovar_form");?>
</div>
<div id="finalizar-cotacao" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-power-off">&nbsp;&nbsp;</i><?php echo lang('COTACAO_FINALIZAR');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="finalizar_form" id="finalizar_form" class="form-horizontal">
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-12"><?php echo lang('COTACAO_FINALIZAR_AVISO');?></label>
					</div>
				</div>
				<input name="id_cotacao" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit green"><i class="fa fa-power-off">&nbsp;&nbsp;</i><?php echo lang('COTACAO_FINALIZAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("finalizarCotacao", "finalizar_form");?>
</div>
<div id="cancelar-cotacao" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('COTACAO_CANCELAR');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="cancelar_form" id="cancelar_form" class="form-horizontal">
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-12"><?php echo lang('COTACAO_CANCELAR_AVISO');?></label>
					</div>
				</div>
				<input name="id_cotacao" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit red"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('COTACAO_CANCELAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("cancelarCotacao", "cancelar_form");?>
</div>
<div id="adicionar-frete" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><i class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR_FRETE');?></h4>
			</div>
			<form action="" autocomplete="off" method="post" name="frete_form" id="frete_form" class="form-horizontal">
				<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-3"><?php echo lang('PERCENTUAL');?></label>
						<div class="col-md-8">
								<input type="text" class="form-control decimal" name="percentual">
						</div>
					</div>
				</div>
				<input name="id_cotacao" type="hidden" value="<?php echo Filter::$id;?>" />
				<div class="modal-footer">
					<button type="button" class="btn btn-submit purple"><i class="fa fa-save">&nbsp;&nbsp;</i><?php echo lang('SALVAR');?></button>
					<button type="button" data-dismiss="modal" class="btn default"><?php echo lang('VOLTAR');?></button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $core->doForm("adicionarFrete", "frete_form");?>
</div>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('COTACAO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('COTACAO_VISUALIZAR');?></small></h1>
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
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('COTACAO_VISUALIZAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="javascript:void(0);" id="imprimir_cotacao" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-print">&nbsp;&nbsp;</i><?php echo lang('IMPRIMIR');?></a>
								<?php if($row->id_status < 3):?>
									<a href="#enviar-cotacao" class="btn btn-sm purple" data-toggle="modal"><i class="fa fa-send">&nbsp;&nbsp;</i><?php echo lang('COTACAO_ENVIAR');?></a>
									<?php if($row->id_status == 2):?>
										<a href="#validar-cotacao" class="btn btn-sm blue-madison" data-toggle="modal"><i class="fa fa-check-square">&nbsp;&nbsp;</i><?php echo lang('COTACAO_VALIDAR');?></a>
									<?php endif;?>
									<a href="#cancelar-cotacao" class="btn btn-sm red" data-toggle="modal"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('COTACAO_CANCELAR');?></a>	
								<?php elseif($row->id_status == 4 and $usuario->is_Master()):?>
									<a href="#aprovar-cotacao" class="btn btn-sm blue" data-toggle="modal"><i class="fa fa-check">&nbsp;&nbsp;</i><?php echo lang('COTACAO_APROVAR');?></a>	
									<a href="#reabrir-cotacao" class="btn btn-sm green" data-toggle="modal"><i class="fa fa-repeat">&nbsp;&nbsp;</i><?php echo lang('COTACAO_REABRIR');?></a>	
									<a href="#cancelar-cotacao" class="btn btn-sm red" data-toggle="modal"><i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('COTACAO_CANCELAR');?></a>	
								<?php elseif($row->id_status == 5):?>
									<?php if($cotacao->validaEntrega(Filter::$id)):?>
										<a href="#finalizar-cotacao" class="btn btn-sm green" data-toggle="modal"><i class="fa fa-power-off">&nbsp;&nbsp;</i><?php echo lang('COTACAO_FINALIZAR');?></a>	
									<?php endif;?>
								<?php endif;?>
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
														<label class="control-label col-md-3"><?php echo lang('COTACAO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->id;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('QUANTIDADE_ITENS');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->quantidade;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('USUARIO_ABERTURA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->usuario_abertura;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('USUARIO_FECHAMENTO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->usuario_fechamento;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('USUARIO_APROVACAO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo $row->usuario_aprovacao;?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo ($row->id_status == 7) ? lang('USUARIO_CANCELADA') : lang('USUARIO_FINALIZADO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo ($row->id_status == 7) ? $row->usuario : $row->usuario_finalizado;?>">
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('STATUS');?></label>
														<div class="col-md-9">
															<?php echo statusCotacao($id_status);?>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('VALOR_TOTAL');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo moeda($row->valor_total);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_COTACAO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo exibedataHora($row->data_abertura);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_FECHAMENTO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo exibedataHora($row->data_fechamento);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo lang('DATA_APROVACAO');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo exibedataHora($row->data_aprovacao);?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="form-group">
														<label class="control-label col-md-3"><?php echo ($row->id_status == 7) ? lang('DATA_CANCELADA') : lang('DATA_FINALIZADA');?></label>
														<div class="col-md-9">
															<input readonly type="text" class="form-control caps" value="<?php echo ($row->id_status == 7) ? exibedataHora($row->data) : exibedataHora($row->data_finalizado);?>">
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
						<?php if($row->id_status < 7):?>
							<?php if($usuario->is_Master() and $row->id_status > 1):?>
								<h4 class="form-section font-<?php echo $core->primeira_cor;?>"><i class="fa fa-truck">&nbsp;&nbsp;</i><?php echo lang('FORNECEDOR_TITULO');?></h4>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-advance">
										<thead>
											<tr>
												<th><?php echo lang('CODIGO');?></th>
												<th><?php echo lang('FORNECEDOR');?></th>
												<th width="110px"><?php echo lang('CPF_CNPJ');?></th>
												<th><?php echo lang('CONTATO');?></th>
												<th><?php echo lang('CELULAR');?></th>
												<th><?php echo lang('EMAIL');?></th>
												<th><?php echo lang('QUANT');?></th>
												<th><?php echo lang('VALOR');?></th>
												<th><?php echo lang('LINK');?></th>
												<th width="230px"><?php echo lang('OPCOES');?></th>
											</tr>
										</thead>
										<tbody>
										<?php 	
												$vl_total = 0;
												$valido = ($row->id_status > 4);
												$retorno_row = $cotacao->getCotacaoFornecedores(Filter::$id, $valido, true);
												if($retorno_row):
												foreach ($retorno_row as $exrow):													
													$estilo = '';
													if($exrow->inativo)
														$estilo = 'class="danger"';
													$vl_total += $exrow->valor;
													$site_sistema = $core->site_sistema;
													$cc = $row->id;
													$co = $exrow->id_cadastro;
													$link = urlCurta("https://".$site_sistema."/cotacao/index.php?cc=".$cc."&co=".$co);
													$link_fornecedor = urlCurta("https://".$site_sistema."/fornecedor/index.php?cc=".$cc."&co=".$co);
										?>
											<tr <?php echo $estilo;?>>
												<td><?php echo $exrow->id_cadastro;?></td>
												<td><a href="index.php?do=fornecedor&acao=editar&id=<?php echo $exrow->id;?>" target="_blank"><?php echo $exrow->fornecedor;?></a></td>
												<td><?php echo formatar_cpf_cnpj($exrow->cpf_cnpj);?></td>
												<td><?php echo $exrow->contato;?></td>
												<td><?php echo $exrow->celular;?></td>
												<td><?php echo $exrow->email;?></td>												
												<td><?php echo $exrow->quant;?></td>												
												<td><?php echo decimal($exrow->valor);?></td>													
												<td><?php echo (!$valido) ? $link : $link_fornecedor;?></td>
												<td>
													<?php if(!$valido):?>
														<a href="<?php echo $link;?>" class="btn btn-sm <?php echo $core->primeira_cor;?>" title="<?php echo lang('COTACAO_VISUALIZAR').': '.Filter::$id;?>" target="_blank" title=""><i class="fa fa-search"></i></a>
													<?php else:?>
														<a href="javascript:void(0);" id_cadastro="<?php echo $exrow->id_cadastro;?>" class="btn btn-sm purple adicionarfrete" title="<?php echo lang('ADICIONAR_FRETE');?>"><i class="fa fa-usd"></i></a>
														<a href="index.php?do=cotacao&acao=fornecedor&id=<?php echo Filter::$id;?>&id_cadastro=<?php echo $exrow->id_cadastro;?>" class="btn btn-sm purple" target="_blank" title="<?php echo lang('PEDIDO_EDITAR').': '.$exrow->id_cadastro;?>"><i class="fa fa-shopping-cart"></i></a>
														<a href="javascript:void(0);" id="<?php echo $exrow->id;?>" id_cotacao="<?php echo Filter::$id;?>" acao="enviarEmailFornecedor" class="btn btn-sm yellow enviaremail" title="<?php echo lang('ENVIAR_EMAIL');?>"><i class="fa fa-send"></i></a>
														<a href="<?php echo $link_fornecedor;?>" class="btn btn-sm green" title="<?php echo lang('PEDIDO_VISUALIZAR');?>" target="_blank"><i class="fa fa-folder-open"></i></a>
														<?php if($exrow->inativo == 0):?>
															<a href="javascript:void(0);" id_cotacao="<?php echo Filter::$id;?>" id_cadastro="<?php echo $exrow->id_cadastro;?>" class="btn btn-sm red bloquearfornecedor" title="<?php echo lang('BLOQUEAR');?>"><i class="fa fa-ban"></i></a>
														<?php elseif($exrow->inativo == 2):?>
															<a href="javascript:void(0);" id_cotacao="<?php echo Filter::$id;?>" id_cadastro="<?php echo $exrow->id_cadastro;?>" class="btn btn-sm green-haze liberarfornecedor" title="<?php echo lang('ATIVAR');?>"><i class="fa fa-check"></i></a>
														<?php endif;?>
													<?php endif;?>
												</td>
											</tr>
										<?php endforeach;?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="7"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
												<td><strong><?php echo decimal($vl_total);?></strong></td>
												<td></td>
												<td></td>
											</tr>
										</tfoot>
										<?php unset($exrow);
											  endif;?>
									</table>
								</div>
							<?php endif;?>
							<?php if($row->id_status > 4):?>
								<h4 class="form-section font-<?php echo $core->primeira_cor;?>"><i class="fa fa-th-large">&nbsp;&nbsp;</i><?php echo lang('CENTRO_CUSTO_TITULO');?></h4>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-advance">
										<thead>
											<tr>
												<th><?php echo lang('CODIGO');?></th>
												<th><?php echo lang('CENTRO_CUSTO');?></th>
												<th><?php echo lang('RESPONSAVEL');?></th>
												<th><?php echo lang('CELULAR');?></th>
												<th><?php echo lang('EMAIL');?></th>
												<th><?php echo lang('QUANT');?></th>
												<th><?php echo lang('VALOR');?></th>
												<th width="150px"><?php echo lang('OPCOES');?></th>
											</tr>
										</thead>
										<tbody>
										<?php 	
												$vl_total = 0;
												$retorno_row = $cotacao->getPedidosCentroCusto(Filter::$id);
												if($retorno_row):
												foreach ($retorno_row as $exrow):
													$cc = $row->codigo;
													$lo = $exrow->id_custo;
													$valida_entrega = $cotacao->validaEntrega(Filter::$id, false, $lo);
													$estilo = '';
													if($valida_entrega)
														$estilo = 'class="success"';
													$vl_total += $exrow->valor;
													$site_sistema = $core->site_sistema;
													$link = urlCurta("https://".$site_sistema."/centro_custo/index.php?lo=".$lo."&cc=".$cc);
										?>
											<tr <?php echo $estilo;?>>
												<td><?php echo $exrow->id_custo;?></td>
												<td><?php echo $exrow->centro_custo;?></td>
												<td><?php echo $exrow->responsavel;?></td>
												<td><?php echo $exrow->celular;?></td>
												<td><?php echo $exrow->email;?></td>									
												<td><?php echo $exrow->quant;?></td>												
												<td><?php echo decimal($exrow->valor);?></td>		
												<td>
													<a href="index.php?do=cotacao&acao=pedido&id_cotacao=<?php echo Filter::$id;?>&id_custo=<?php echo $exrow->id_custo;?>" class="btn btn-sm purple" target="_blank" title="<?php echo lang('PEDIDO_EDITAR').': '.$exrow->id_custo;?>"><i class="fa fa-shopping-cart"></i></a>
													<a href="javascript:void(0);" id="<?php echo $exrow->id;?>" id_cotacao="<?php echo Filter::$id;?>" acao="enviarEmailCentroCusto" class="btn btn-sm yellow enviaremail" title="<?php echo lang('ENVIAR_EMAIL');?>"><i class="fa fa-send"></i></a>
													<a href="<?php echo $link;?>" class="btn btn-sm green" title="<?php echo lang('PEDIDO_VISUALIZAR');?>" target="_blank"><i class="fa fa-folder-open"></i></a>
												</td>
											</tr>
										<?php endforeach;?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="6"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
												<td><strong><?php echo decimal($vl_total);?></strong></td>
												<td></td>
											</tr>
										</tfoot>
										<?php unset($exrow);
											  endif;?>
									</table>
								</div>
							<?php else: ?>
								<h4 class="form-section font-<?php echo $core->primeira_cor;?>"><i class="fa fa-shopping-cart">&nbsp;&nbsp;</i><?php echo lang('PEDIDO_TITULO');?></h4>
								<div class="portlet-body">
									<table class="table table-bordered table-striped table-condensed table-advance">
										<thead>
											<tr>
												<th><?php echo lang('CODIGO');?></th>
												<th><?php echo lang('CENTRO_CUSTO');?></th>
												<th><?php echo lang('DATA_PEDIDO');?></th>
												<th><?php echo lang('OPCOES');?></th>
											</tr>
										</thead>
										<tbody>
										<?php 	
												$retorno_row = $cotacao->getPedidosCotacao(Filter::$id);
												if($retorno_row):
												foreach ($retorno_row as $exrow):?>
											<tr>
												<td><a href="index.php?do=pedido&acao=visualizar&id=<?php echo $exrow->id;?>" target="_blank"><?php echo $exrow->id;?></a></td>
												<td><?php echo $exrow->centro_custo;?></td>
												<td><?php echo exibedata($exrow->data_pedido);?></td>
												<td>
													<a href="index.php?do=pedido&acao=visualizar&id=<?php echo $exrow->id;?>" class="btn btn-sm grey-cascade" target="_blank" title="<?php echo lang('VISUALIZAR').': '.$exrow->id;?>"><i class="fa fa-search"></i></a>
												</td>
											</tr>
										<?php endforeach;?>
										<?php unset($exrow);
											  endif;?>
										</tbody>
									</table>
								</div>
								<h4 class="form-section font-<?php echo $core->primeira_cor;?>"><i class="fa fa-barcode">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_TITULO');?></h4>
								<div class="portlet-body">
									<form class="form-inline">
										<div class="form-group">
											<select class="select2me form-control input-xlarge" name="id_cadastro" id="id_cadastro" data-placeholder="<?php echo lang('SELECIONE_FORNECEDOR');?>" >
												<option value=""></option>
												<?php 
													$retorno_row = $cotacao->getCotacaoFornecedores(Filter::$id);
													$quant_fornecedores = count($retorno_row);
													if ($retorno_row):
														foreach ($retorno_row as $srow):
												?>
															<option value="<?php echo $srow->id_cadastro;?>" <?php if($srow->id_cadastro == $id_cadastro) echo 'selected="selected"';?>><?php echo $srow->fornecedor." (".formatar_cpf_cnpj($srow->cpf_cnpj).")";?></option>
												<?php
														endforeach;
													unset($srow);
													endif;
												?>
											</select>
											&nbsp;&nbsp;
											<select class="select2me form-control input-xlarge" name="id_produto" id="id_produto" data-placeholder="<?php echo lang('SELECIONE_PRODUTO');?>" >
												<option value=""></option>
												<?php 
													$retorno_row = $cotacao->getCotacaoProdutos(Filter::$id);
													if ($retorno_row):
														foreach ($retorno_row as $srow):
												?>
															<option value="<?php echo $srow->id_produto;?>" <?php if($srow->id_produto == $id_produto) echo 'selected="selected"';?>><?php echo $srow->produto;?></option>
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
									<table class="table table-bordered table-condensed table-advance" id="table_tabela">
										<thead>
											<tr>
												<th>#</th>
												<th>-</th>
												<th><?php echo lang('FORNECEDOR');?></th>
												<th><?php echo lang('PRODUTO');?></th>
												<th><?php echo lang('UNIDADE');?></th>
												<th><?php echo lang('VL_COMPRA');?></th>
												<th><?php echo lang('QUANTIDADE_COTACAO');?></th>
												<th><?php echo lang('VL_UNITARIO');?></th>
												<th><?php echo lang('VL_TOTAL');?></th>
												<th><?php echo lang('DATA_ENTREGA');?></th>
												<th><?php echo lang('OPCOES');?></th>
											</tr>
										</thead>
										<tbody>
										<?php 											
												$vl_estimativa = 0;
												$vl_total = 0;
												$retorno_row = $cotacao->getCotacaoItens(Filter::$id, $id_cadastro, $id_produto);
												if($retorno_row):
												foreach ($retorno_row as $exrow):
												$vl_estimativa += $estimativa = $exrow->quantidade_pedido*$exrow->valor_produto;
												$total = $exrow->quantidade_cotacao*$exrow->valor_unitario;
												$vencedor = '-';
												$estilo = '';
												if($exrow->valor_unitario > 0 or $exrow->valido == 1)
												{
													if($cotacao->validaMenorValor(Filter::$id, $exrow->id_produto, $exrow->valor_unitario, $exrow->id))
													{
														$vencedor = 'V';
														$vl_total += $total;
														$estilo = 'class="success"';
														if($exrow->valido)
															$estilo = 'class="warning"';
													}
												}
												
										?>
											<tr <?php echo $estilo;?>>
												<td><?php echo $exrow->id;?></td>
												<td><?php echo $vencedor;?></td>
												<td><?php echo $exrow->fornecedor;?></td>
												<td><?php echo $exrow->produto;?></td>
												<td><?php echo $exrow->unidade;?></td>
												<td><?php echo moeda($exrow->valor_produto);?></td>
												<td><?php echo decimal($exrow->quantidade_cotacao);?></td>
												<td><?php echo moeda($exrow->valor_unitario);?></td>
												<td><span class="bold theme-font valor_total"><?php echo moeda($total);?></span></td>
												<td><?php echo exibedata($exrow->data_entrega);?></td>
												<td>
												<?php if($row->id_status < 3): ?>
													<a href="javascript:void(0);" class="btn btn-sm blue alterar" title="<?php echo lang('ALTERAR').": ".$exrow->produto;?>"><i class="fa fa-pencil"></i></a>											
												<?php endif; ?>
												<?php if($row->id_status < 4 or $usuario->is_Master()): ?>
													<?php if(!$exrow->valido): ?>
														<a href="javascript:void(0);" class="btn btn-sm green validar" id="<?php echo $exrow->id;?>" id_cotacao="<?php echo $exrow->id_cotacao;?>" id_produto="<?php echo $exrow->id_produto;?>" title="<?php echo lang('VALIDAR').": ".$exrow->produto;?>"><i class="fa fa-check"></i></a>
													<?php endif; ?>
												<?php endif; ?>
												</td>
											</tr>
										<?php endforeach;?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="8"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
												<td><span class="bold theme-font valor_total"><?php echo moeda($vl_total);?></span></td>
												<td colspan="2"></td>
											</tr>
										</tfoot>
										<?php unset($exrow);
											  endif;?>
									</table>
								</div>
							<?php endif;?>
						<?php endif;?>
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
<?php case "fornecedor": 
	$row = Core::getRowById("cotacao", Filter::$id);
	$id_cadastro = get('id_cadastro');
	if(!$id_cadastro) {
		die();
	}
?>
<script src="./assets/scripts/table-cotacao.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {  
   TableEditable.init();
});
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('COTACAO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PEDIDO_EDITAR');?></small></h1>
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
									<i class="fa fa-shopping-cart font-<?php echo $core->primeira_cor;?>"></i>								
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PEDIDO_EDITAR');?></span>
							</div>
						</div>
								<h4 class="form-section font-<?php echo $core->primeira_cor;?>"><i class="fa fa-barcode">&nbsp;&nbsp;</i><?php echo lang('PRODUTO_TITULO');?></h4>
								<div class="portlet-body">
									<table class="table table-bordered table-condensed table-advance" id="table_tabela">
										<thead>
											<tr>
												<th>#</th>
												<th>-</th>
												<th><?php echo lang('FORNECEDOR');?></th>
												<th><?php echo lang('PRODUTO');?></th>
												<th><?php echo lang('UNIDADE');?></th>
												<th><?php echo lang('VL_COMPRA');?></th>
												<th><?php echo lang('QUANTIDADE_COTACAO');?></th>
												<th><?php echo lang('VL_UNITARIO');?></th>
												<th><?php echo lang('VL_TOTAL');?></th>
												<th><?php echo lang('DATA_ENTREGA');?></th>
												<th><?php echo lang('OPCOES');?></th>
											</tr>
										</thead>
										<tbody>
										<?php 											
												$vl_estimativa = 0;
												$vl_total = 0;
												$retorno_row = $cotacao->getCotacaoItens(Filter::$id, $id_cadastro);
												if($retorno_row):
												foreach ($retorno_row as $exrow):
												$vl_estimativa += $estimativa = $exrow->quantidade_pedido*$exrow->valor_produto;
												$total = $exrow->quantidade_cotacao*$exrow->valor_unitario;
												
										?>
											<tr>
												<td><?php echo $exrow->id;?></td>
												<td>-</td>
												<td><?php echo $exrow->fornecedor;?></td>
												<td><?php echo $exrow->produto;?></td>
												<td><?php echo $exrow->unidade;?></td>
												<td><?php echo moeda($exrow->valor_produto);?></td>
												<td><?php echo decimal($exrow->quantidade_cotacao);?></td>
												<td><?php echo moeda($exrow->valor_unitario);?></td>
												<td><span class="bold theme-font valor_total"><?php echo moeda($total);?></span></td>
												<td><?php echo exibedata($exrow->data_entrega);?></td>
												<td>
													<a href="javascript:void(0);" class="btn btn-sm blue alterar" title="<?php echo lang('ALTERAR').": ".$exrow->produto;?>"><i class="fa fa-pencil"></i></a>
												</td>
											</tr>
										<?php endforeach;?>
										</tbody>
										<tfoot>
											<tr>
												<td colspan="8"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
												<td><span class="bold theme-font valor_total"><?php echo moeda($vl_total);?></span></td>
												<td colspan="2"></td>
											</tr>
										</tfoot>
										<?php unset($exrow);
											  endif;?>
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
				<h1><?php echo lang('COTACAO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('COTACAO_LISTAR');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('COTACAO_LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=cotacao&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable dataTable-tools">
								<thead>
									<tr>
										<th><?php echo lang('COTACAO');?></th>
										<th><?php echo lang('USUARIO_ABERTURA');?></th>
										<th><?php echo lang('DATA_COTACAO');?></th>
										<th><?php echo lang('DATA_FECHAMENTO');?></th>
										<th><?php echo lang('DATA_FINALIZADA');?></th>
										<th><?php echo lang('QUANTIDADE_ITENS');?></th>
										<th><?php echo lang('VALOR_TOTAL');?></th>
										<th><?php echo lang('STATUS');?></th>
                                        <th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $cotacao->getCotacoes();
										if($retorno_row):
										foreach ($retorno_row as $exrow):
										$id_status = $exrow->id_status;
										$valida = $cotacao->validaCotacao($exrow->id);
										if(!$valida and $exrow->id_status == 2) {
											$id_status = 3;
										}
								?>
									<tr>
										<td><a href="index.php?do=cotacao&acao=visualizar&id=<?php echo $exrow->id;?>"><?php echo $exrow->id;?></a></td>
										<td><?php echo $exrow->usuario_abertura;?></td>
										<td><?php echo exibedata($exrow->data_abertura);?></td>
										<td><?php echo exibedata($exrow->data_fechamento);?></td>
										<td><?php echo exibedata($exrow->data_finalizado);?></td>
										<td><?php echo $exrow->quantidade;?></td>
										<td><?php echo moeda($exrow->valor_total);?></td>
										<td><?php echo statusCotacao($id_status);?></td>
										<td>
											<a href="index.php?do=cotacao&acao=visualizar&id=<?php echo $exrow->id;?>" class="btn btn-sm grey-cascade" title="<?php echo lang('VISUALIZAR').': '.$exrow->id;?>"><i class="fa fa-search"></i></a>
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
				<h1><?php echo lang('COTACAO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('COTACAO_ABERTO');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('COTACAO_ABERTO');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=cotacao&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable dataTable-tools">
								<thead>
									<tr>
										<th><?php echo lang('COTACAO');?></th>
										<th><?php echo lang('USUARIO_ABERTURA');?></th>
										<th><?php echo lang('DATA_COTACAO');?></th>
										<th><?php echo lang('DATA_FECHAMENTO');?></th>
										<th><?php echo lang('DATA_FINALIZADA');?></th>
										<th><?php echo lang('QUANTIDADE_ITENS');?></th>
										<th><?php echo lang('VALOR_TOTAL');?></th>
										<th><?php echo lang('STATUS');?></th>
                                        <th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $cotacao->getCotacoesAberto();
										if($retorno_row):
										foreach ($retorno_row as $exrow):?>
									<tr>
										<td><a href="index.php?do=cotacao&acao=visualizar&id=<?php echo $exrow->id;?>"><?php echo $exrow->id;?></a></td>
										<td><?php echo $exrow->usuario_abertura;?></td>
										<td><?php echo exibedata($exrow->data_abertura);?></td>
										<td><?php echo exibedata($exrow->data_fechamento);?></td>
										<td><?php echo exibedata($exrow->data_finalizado);?></td>
										<td><?php echo $exrow->quantidade;?></td>
										<td><?php echo moeda($exrow->valor_total);?></td>
										<td><?php echo statusCotacao($exrow->id_status);?></td>
										<td>
											<a href="index.php?do=cotacao&acao=visualizar&id=<?php echo $exrow->id;?>" class="btn btn-sm grey-cascade" title="<?php echo lang('VISUALIZAR').': '.$exrow->id;?>"><i class="fa fa-search"></i></a>
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
<?php case "pedido": 
	$id_cotacao = get('id_cotacao');
	$id_custo = get('id_custo');
?>
<script src="./assets/scripts/table-pedido.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {  
   TableEditable.init();
});
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('PEDIDO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PEDIDO_CENTRO_CUSTO');?></small></h1>
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
									<i class="fa fa-shopping-cart font-<?php echo $core->primeira_cor;?>"></i>								
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('PEDIDO_TITULO');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed table-advance" id="table_tabela">
								<thead>
									<tr>
										<th>#</th>
										<th><?php echo lang('PEDIDO');?></th>
										<th><?php echo lang('CATEGORIA');?></th>
										<th><?php echo lang('PRODUTO');?></th>
										<th><?php echo lang('QUANTIDADE_PEDIDO');?></th>
										<th width="100px"><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 											
										$retorno_row = $cotacao->getPedidosItensCotacao($id_cotacao, $id_custo);
										if($retorno_row):
										foreach ($retorno_row as $exrow):
										$estilo = "";
										if($exrow->inativo) {
											$estilo = 'class="danger"';
										}
										
								?>
									<tr <?php echo $estilo;?>>
										<td><?php echo $exrow->id;?></td>
										<td><?php echo $exrow->id_pedido;?></td>
										<td><?php echo $exrow->categoria;?></td>
										<td><?php echo $exrow->produto;?></td>
										<td><?php echo decimal($exrow->quantidade_pedido);?></td>
											<td>
												<?php if($exrow->inativo): ?>
													<a href="javascript:void(0);" class="btn btn-sm green ativar_produto" id="<?php echo $exrow->id;?>" title="<?php echo lang('ATIVAR').": ".$exrow->produto;?>"><i class="fa fa-check"></i></a>
												<?php else: ?>
													<a href="javascript:void(0);" class="btn btn-sm blue alterar" id="<?php echo $exrow->id;?>" title="<?php echo lang('ALTERAR').": ".$exrow->produto;?>"><i class="fa fa-pencil"></i></a>
													<a href="javascript:void(0);" class="btn btn-sm red delete" id="<?php echo $exrow->id;?>"  title="<?php echo lang('PRODUTO_APAGAR').$exrow->produto;?>"><i class="fa fa-times"></i></a>
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
<?php case "adicionar": 
	$id_cotacao = $cotacao->getCotacaoAberta();
?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('COTACAO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('COTACAO_ADICIONAR');?></small></h1>
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
								<i class="fa fa-exclamation font-<?php echo $core->primeira_cor;?>"></i>
								<?php if($id_cotacao):?>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('COTACAO_ESCOLHA').$id_cotacao;?></span>
								<?php else:?>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('COTACAO_NOVA');?></span>
								<?php endif;?>								
							</div>
							<div class="actions btn-set">
								<a href="javascript:void(0);" class="btn btn-sm <?php echo $core->primeira_cor;?> salvarcotacao" id="<?php echo $id_cotacao;?>" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class='fa fa-save'>&nbsp;&nbsp;</i><?php echo lang('COTACAO_SALVAR');?></a>
								<?php if($id_cotacao):?>	
								<a href="index.php?do=cotacao&acao=visualizar&id=<?php echo $id_cotacao;?>" class="btn btn-sm grey-cascade"><i class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('COTACAO_VISUALIZAR');?></a>
								<?php endif;?>																							
							</div>
						</div>						
						<div class="portlet-body">
							<form class="form-inline" action="" method="post" name="admin_form" id="admin_form">
								<table class="table table-bordered table-condensed table-advance checkTable">
									<thead>
										<tr>
											<th class="table-checkbox">
												<input type="checkbox" class="group-checkable" data-set=".checkboxes"/>
											</th>
											<th><?php echo lang('CODIGO');?></th>
											<th><?php echo lang('CENTRO_CUSTO');?></th>
											<th><?php echo lang('DATA_PEDIDO');?></th>
											<th><?php echo lang('OPCOES');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 	
										$totalcusto = 0;
										$totalvenda = 0;
										$retorno_row = $cotacao->getPedidosAberto();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
									?>
										<tr>
											<td>
												<input name="id_pedido[]" type="checkbox" class="checkboxes" value="<?php echo $exrow->id;?>"/>
											</td>
											<td><?php echo $exrow->id;?></td>
											<td><?php echo $exrow->centro_custo;?></td>
											<td><?php echo exibedata($exrow->data_pedido);?></td>
											<td>
												<a href="index.php?do=pedido&acao=visualizar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" target="_blank" title="<?php echo lang('EDITAR').': '.$exrow->id;?>"><i class="fa fa-pencil"></i></a>
												<a href="javascript:void(0);" onclick="javascript:void window.open('ver_pedido.php?id=<?php echo $exrow->id;?>','<?php echo lang('VISUALIZAR').$exrow->id;?>','width=560,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>" class="btn btn-sm grey-cascade"><i class="fa fa-search"></i></a>
											</td>
										</tr>
									<?php endforeach;?>
									<?php unset($exrow);
										  endif;?>
									</tbody>
								</table>
								<input name="id_cotacao" type="hidden" value="<?php echo $id_cotacao;?>" />
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
<?php break;?>
<?php default: ?>
<div class="imagem-fundo">
	<img src="assets/img/logo.png" border="0">
</div>
<?php break;?>
<?php endswitch;?>