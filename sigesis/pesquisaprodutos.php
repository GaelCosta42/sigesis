<?php
  /**
   * Pesquisar Produtos
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  define("_VALID_PHP", true);
  
	require_once("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	include("head.php");
?>
<div class="page-container">
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<div class="row">
					<div class="col-md-12">			
						<div class="portlet light">
							<div class='portlet-title'>
								<div class="caption">
									<i class="fa fa-barcode"></i>								
									<span><?php echo lang('PRODUTO_LISTAR');?></span>
								</div>
							</div>
							<div class="portlet-body">
								<table class="table table-bordered table-striped table-condensed table-advance dataTable ">
									<thead>
									<tr>
										<th><?php echo lang('PRODUTO');?></th>
										<th><?php echo lang('CODIGO');?></th>
										<th><?php echo lang('CODIGO_DE_BARRAS');?></th>
										<th><?php echo lang('QUANTIDADE');?></th>
									</tr>
									</thead>
									<tbody>
									<?php 	
										$retorno_row = $produto->getProdutos();
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$quantidade = $produto->getEstoqueTotal($exrow->id);
									?>
										<tr>
											<td><?php echo $exrow->nome;?></td>
											<td><?php echo $exrow->codigo;?></td>
											<td><?php echo $exrow->codigobarras;?></td>
											<td><?php echo $quantidade;?></td>
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
<div class="quebra_pagina"></div>
<div class="noprint"></div>
<?php
	include("footer.php");
?>