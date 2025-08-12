<?php
	
	/**
   	* Atualização
   	*
   	**/
	
	if (!defined("_VALID_PHP"))
        die('Acesso direto a esta classe nao e permitido.');

?>
<?php switch(Filter::$acao): case "listar": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1>
                    <?php echo lang('ATUALIZACOES'); ?>
					&nbsp;
                    <i class="fa fa-angle-right"></i>&nbsp;
                    <small><?php echo lang('NOVAS_ATUALIZACOES'); ?></small>
                </h1>
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
								<i class="fa fa-list">&nbsp;&nbsp;</i>
                                <?php echo lang('ATUALIZACOES'); ?>
							</div>
						</div>
						<div class="portlet-body">
						<table class="table table-bordered table-condensed table-advance dataTable-desc">
							<thead>
								<tr>
									<th>#</th>
									<th>#</th>
									<th><?php echo lang('DATA_ATUALIZACAO'); ?></th>
									<th width="500px"><?php echo lang('TITULO'); ?></th>
									<th><?php echo lang('SISTEMA'); ?></th>
									<th><?php echo lang('ACOES'); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php 	
									$row_verifica = $empresa->verificaAtualizacao();
									$r = json_decode($row_verifica, true);
									
									if($r):
										foreach($r as $res):
											if( $res['id_categoria'] == 1):
											
							?>
								<tr class="">
									<td><?php echo $res['id']; ?></td>
									<td><?php echo $res['id']; ?></td>
									<td><?php echo $res['data_atualizacao']; ?></td>
									<td><?php echo $res['titulo']; ?></td>
									<td><?php echo $res['categoria'];?></td>
									
									<td>
										<a 
											href="index.php?do=atualizacao&acao=visualizar&id=<?php echo $res['id'];?>"
											class="btn btn-sm grey-cascade visualizar"
											id="<?php echo $res['id']; ?>"
											title="<?php echo lang('VISUALIZAR').': '.$res['id'];?>">
											<i class="fa fa-search"></i>
										</a>
									</td>
								</tr>
								<?php endif; ?>
							<?php endforeach; ?>
							<?php unset($res);
								endif; ?>
							</tbody>
						</table>
							<!-- FINAL TABLE  -->
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



<!-- INICIO PAGINAL VISUALIZAR -->
<?php case "visualizar": ?>

<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1>
					<?php echo lang('ATUALIZACOES'); ?>
					&nbsp;
                    <i class="fa fa-angle-right"></i>&nbsp;
                    <small><?php echo lang('VISUALIZAR_ATUALIZACOES'); ?></small>
                </h1>
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
								<i class="fa fa-edit">&nbsp;&nbsp;</i>
                                <?php echo lang('VISUALIZAR_ATUALIZACOES'); ?>
							</div>
						</div>
						
						<div class="portlet-body">
						<?php 
								$row_verifica = $empresa->verificaAtualizacao();
								$retorno = json_decode($row_verifica, true);
								
								if($retorno):
									foreach($retorno as $resposta):
										if( $resposta['id'] == Filter::$id ):		
						?>
							
							<h3>
								<?php echo $resposta['titulo']; ?>
								<small><?php echo ($resposta['data_atualizacao']); ?></small>
							</h3><hr>
							
							<span class="help-block"> 
								<?php echo $resposta['menu']; ?> 
							</span>
							
							<br><br>
							
							<h5 style="text-align: justify;">
								<?php echo $resposta['descricao']; ?>
							</h5>

							<?php if($resposta['imagem']): ?>
								
							<br><br>
							<div class="row">
								<div class="col-md-6">
									<span><strong> Demonstração:</strong></span>
									<img class="imagem_print" src="<?php echo $resposta['imagem']; ?>" alt="" width="150px" style="border: 5px solid #fafafa; border-radius: 10px;" alt="Imagem da atualização">
								</div>
								<!-- MODAL DE IMAGEM -->
								<div class="modal fade" id="modal" tabindex="-1" role="dialog" >
									<div class="modal-dialog modal-lg" role="document">
										<div class="modal-content">
											<div class="modal-header"></div>
											<div class="modal-body element" style="text-align: center;">
												<figure class="zoom"  style="background-image: url(<?php echo $resposta['imagem']; ?>)">
													<img src="<?php echo $resposta['imagem']; ?>" />
												</figure>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php endif; ?>
							
							<?php 
										endif;
									endforeach;
								endif;
								unset($respota);

							?>
							<hr>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-offset-11">
												<button type="button" id="voltar" class="btn default"><?php echo lang('VOLTAR');?></button>
											</div>
										</div>
									</div>
								</div>
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


<script>
	$(document).ready(()=>{
		$('.imagem_print').click(()=>{
			$('#modal').modal('show');
		}).css('cursor', 'pointer');

	});
</script>

<?php break; ?>
<!--FINAL-->



<?php default: ?>
<div class="imagem-fundo">
	<img src="assets/img/logo.png">
</div>
<?php break;?>
<?php endswitch;?>