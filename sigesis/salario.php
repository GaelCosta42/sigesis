<?php
  /**
   * SalarioMinimo
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
<?php $row = Core::getRowById("salario_minimo", Filter::$id);?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('SALARIO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('SALARIO_EDITAR');?></small></h1>
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
								<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('SALARIO_EDITAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label col-md-2"><?php echo lang('SALARIO');?></label>
												<div class="col-md-6">
													<input type="text" class="form-control moeda" name="salario" value="<?php echo moeda($row->salario);?>">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2"><?php echo lang('ANO');?></label>
												<div class="col-md-6">
													<input type="text" class="form-control inteiro" name="ano" value="<?php echo $row->ano;?>">
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
							<?php echo $core->doForm("processarSalarioMinimo");?>	
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
				<h1><?php echo lang('SALARIO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('SALARIO_ADICIONAR');?></small></h1>
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
								<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('SALARIO_ADICIONAR');?>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-horizontal" name="admin_form" id="admin_form">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="control-label col-md-2"><?php echo lang('SALARIO');?></label>
												<div class="col-md-6">
													<input type="text" class="form-control moeda" name="salario">
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-2"><?php echo lang('ANO');?></label>
												<div class="col-md-6">
													<input type="text" class="form-control inteiro" name="ano">
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
							<?php echo $core->doForm("processarSalarioMinimo");?>	
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
				<h1><?php echo lang('SALARIO_TITULO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('SALARIO_LISTAR');?></small></h1>
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
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('SALARIO_LISTAR');?></span>
							</div>
							<div class="actions btn-set">
								<a href="index.php?do=salario&acao=adicionar" class="btn btn-sm <?php echo $core->primeira_cor;?>"><i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('ADICIONAR');?></a>
							</div>
						</div>
						<div class="portlet-body">
							<table class="table table-bordered table-striped table-condensed dataTable">
								<thead>
									<tr>
										<th><?php echo lang('SALARIO');?></th>
										<th><?php echo lang('ANO');?></th>
										<th><?php echo lang('OPCOES');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$retorno_row = $salario_minimo->getSalarioMinimo();
										if($retorno_row):
										foreach ($retorno_row as $exrow):?>
											<tr>
												<td><a href="index.php?do=salario&acao=editar&id=<?php echo $exrow->id;?>"><?php echo moeda($exrow->salario);?></a></td>
												<td><?php echo $exrow->ano;?></td>
												<td>
													<a href="index.php?do=salario&acao=editar&id=<?php echo $exrow->id;?>" class="btn btn-sm blue" title="<?php echo lang('EDITAR').': '.$exrow->salario;?>"><i class="fa fa-pencil"></i></a>
													<a href="javascript:void(0);" class="btn btn-sm red apagar" id="<?php echo $exrow->id;?>" acao="apagarSalarioMinimo" title="<?php echo lang('APAGAR').$exrow->salario;?>"><i class="fa fa-times"></i></a>
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