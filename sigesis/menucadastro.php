<?php
	/**
	* Menu cadastro
	*
	*/
	
	if (!defined("_VALID_PHP"))
		die('Acesso direto a esta classe não é permitido.');

	$id_empresa = $_SESSION['idempresa'];
	$tipo_sistema = getValue("tipo_sistema","empresa","id=".$id_empresa);
?>
<div class="col-md-12">
	<ul class="nav nav-pills">	
		<?php if($row->inativo):?>
				<li>
					<a href="javascript:void(0);" class="bg-red">
						<i class="fa fa-ban">&nbsp;&nbsp;</i><?php echo lang('CANCELADO');?>
					</a>
				</li>
		<?php endif;?>
			<li>
				<a href="index.php?do=cadastro&acao=historico&id=<?php echo Filter::$id;?>" class="<?php echo ($row->inativo) ? 'bg-red' : 'bg-blue-madison' ?>">
					<i class="fa fa-history">&nbsp;&nbsp;</i><?php echo lang('HISTORICO');?>
				</a>
			</li>
			<li>
				<a href="index.php?do=cadastro&acao=editar&id=<?php echo Filter::$id;?>" class="<?php echo ($row->inativo) ? 'bg-red' : 'bg-blue' ?> ">
					<i class="fa fa-edit">&nbsp;&nbsp;</i><?php echo lang('EDITAR');?>
				</a>
			</li>
			<?php if($usuario->is_Gerencia()):?>
			<li>
				<a href="index.php?do=cadastro&acao=despesas&id=<?php echo Filter::$id;?>" class="<?php echo ($row->inativo) ? 'bg-red' : 'bg-red-pink' ?>">
					<i class="fa fa-minus-square">&nbsp;&nbsp;</i><?php echo lang('DESPESAS');?>
				</a>
			</li>
			<li>
				<a href="index.php?do=cadastro&acao=receitas&id=<?php echo Filter::$id;?>" class="<?php echo ($row->inativo) ? 'bg-red' : 'bg-green' ?>">
					<i class="fa fa-plus-square">&nbsp;&nbsp;</i><?php echo lang('FINANCEIRO_RECEITAS');?>
				</a>
			</li>
			<?php
				if ($tipo_sistema==5):
			?>
					<li>
						<a href="index.php?do=cadastro&acao=ordemservico&id=<?php echo Filter::$id;?>" class="bg-grey-cascade">
							<i class="fa fa-wrench">&nbsp;&nbsp;</i><?php echo lang('ORDEM_SERVICO_TITULO');?>
						</a>
					</li>
			
			<?php
				endif;
			?>
			<li>
				<a href="index.php?do=cadastro&acao=produtos&id=<?php echo Filter::$id;?>" class="bg-purple">
					<i class="fa fa-shopping-cart">&nbsp;&nbsp;</i><?php echo lang('PRODUTOS_VENDIDOS');?>
				</a>
			</li>
			<?php 
				if ($tipo_sistema <> 1 && $tipo_sistema <> 3):
					//$valor_crediario = $cadastro->getTotalCrediario(Filter::$id);
			?>
					<li>
						<a href="index.php?do=cadastro&acao=crediario&opcao=0&id=<?php echo Filter::$id;?>" class="bg-green-jungle">
							<i class="fa fa-money">&nbsp;&nbsp;</i>&nbsp;<?php echo lang('CREDIARIO_FICHA'); /*.": <strong>".moeda($valor_crediario)."</strong>";*/ ?>
						</a>
					</li>
			<?php 
				endif;
			?>
			<li>
				<a href="index.php?do=cadastro&acao=notafiscal&id=<?php echo Filter::$id;?>" class="<?php echo ($row->inativo) ? 'bg-red' : 'bg-blue-hoki' ?>">
					<i class="fa fa-file-code-o">&nbsp;&nbsp;</i><?php echo lang('NOTA_FISCAL');?>
				</a>
			</li>
			<?php endif;?>	
		<li>
			<a href="index.php?do=cadastro&acao=contato&id=<?php echo Filter::$id;?>" class="<?php echo ($row->inativo) ? 'bg-red' : 'bg-grey-gallery' ?>">
				<i class="fa fa-phone">&nbsp;&nbsp;</i><?php echo lang('CONTATO');?>
			</a>
		</li>
	</ul>
</div>