<?php
  /**
   * Descricao
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe n�o � permitido.');
  if (!$usuario->is_Administrativo())
	  redirect_to("login.php");
?>
<?php switch(Filter::$acao): case "editar": ?>
<?php $row = Core::getRowById("salario_descricao", Filter::$id);?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('DESCRICAO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('DESCRICAO_EDITAR');?></small></h1>
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
								<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('DESCRICAO_EDITAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label col-md-2"><?php echo lang('DESCRICAO');?></label>
												<div class="col-md-6">
													<input type="text" class="form-control caps" name="descricao" value="<?php echo $row->descricao;?>">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2"><?php echo lang('CODIGO');?></label>
												<div class="col-md-6">
													<input type="text" class="form-control inteiro" name="codigo" value="<?php echo $row->codigo;?>">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2"><?php echo lang('TIPO');?></label>
												<div class="col-md-6">
													<select class="select2me form-control input-large" name="tipo" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
														<option value="1" <?php if($row->tipo == '1') echo 'selected="selected"';?>><?php echo lang('BONUS_REMUNERACOES');?></option>
														<option value="2" <?php if($row->tipo == '2') echo 'selected="selected"';?>><?php echo lang('DESCONTOS');?></option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2"></label>
												<div class="col-md-9">
													<div class="md-checkbox-list">
														<div class="md-checkbox">
															<input type="checkbox" class="md-check" name="inss" id="inss" value="1" <?php if($row->inss) echo 'checked';?>>
															<label for="inss">
															<span></span>
															<span class="check"></span>
															<span class="box"></span>
															<?php echo lang('INCIDE_INSS');?></label>
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
							<?php echo $core->doForm("processarDescricao");?>	
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
				<h1><?php echo lang('DESCRICAO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('DESCRICAO_ADICIONAR');?></small></h1>
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
								<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('DESCRICAO_ADICIONAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label col-md-2"><?php echo lang('DESCRICAO');?></label>
												<div class="col-md-6">
													<input type="text" class="form-control caps" name="descricao">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2"><?php echo lang('CODIGO');?></label>
												<div class="col-md-6">
													<input type="text" class="form-control inteiro" name="codigo">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2"><?php echo lang('TIPO');?></label>
												<div class="col-md-6">
													<select class="select2me form-control input-large" name="tipo" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
														<option value="1"><?php echo lang('BONUS_REMUNERACOES');?></option>
														<option value="2"><?php echo lang('DESCONTOS');?></option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2"></label>
												<div class="col-md-9">
													<div class="md-checkbox-list">
														<div class="md-checkbox">
															<input type="checkbox" class="md-check" name="inss" id="inss" value="1">
															<label for="inss">
															<span></span>
															<span class="check"></span>
															<span class="box"></span>
															<?php echo lang('INCIDE_INSS');?></label>
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
							<?php echo $core->doForm("processarDescricao");?>	
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
				<h1><?php echo lang('DESCRICAO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('DESCRICAO_LISTAR');?></small></h1>
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
								<i class="fa fa-file-text-o font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('DESCRICAO_LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=descricao&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th><?php echo lang('DESCRICAO');?></th>
										<th><?php echo lang('CODIGO');?></th>
										<th><?php echo lang('TIPO');?></th>
										<th><?php echo lang('INCIDE_INSS');?></th>
										<th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $salario_descricao->getDescricao();
										if($retorno_row):
										foreach ($retorno_row as $exrow):?>
											<tr>
												<td><a href="index.php?do=descricao&acao=editar&id=<?php echo $exrow->id;?>"><?php echo $exrow->descricao;?></a></td>
												<td><?php echo $exrow->codigo;?></td>
												<td><?php echo ($exrow->tipo == '1') ? lang('BONUS_REMUNERACOES') : lang('DESCONTOS');?></td>
												<td><?php echo ($exrow->inss == '1') ? lang('SIM') : lang('NAO');?></td>
												<td>
													<a href="index.php?do=descricao&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->descricao;?>"><i class="fa fa-pencil"></i></a>
													<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarDescricao" title="<?php echo lang('APAGAR').$exrow->descricao;?>"><i class="fa fa-times"></i></a>
												</td>
											</tr>
								<?php 	endforeach;
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
<div class="imagem-fundo">
	<img src="assets/img/logo.png" border="0">
</div>
<?php break;?>
<?php endswitch;?>