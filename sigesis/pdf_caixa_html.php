<?php
  /**
   * Visualizar Caixa Detalhes
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
	
?>
<table>
	<tr>
	<td width="50%">
	<table class="tabela">
		<tbody>
			<tr>
				<th><?php echo lang('RESPONSAVEL_ABERTO');?>:&nbsp;&nbsp;</th>
				<td><?php echo $detalhes_row->aberto;?></td>
			</tr>
			<tr>
				<th><?php echo lang('DATA_ABERTO');?>:&nbsp;&nbsp;</th>
				<td><?php echo exibedataHora($detalhes_row->data_abrir);?></td>
			</tr>
			<tr>
				<th><?php echo lang('RESPONSAVEL_FECHADO');?>:&nbsp;&nbsp;</th>
				<td><?php echo $detalhes_row->fechado;?></td>
			</tr>
			<tr>
				<th><?php echo lang('DATA_FECHADO');?>:&nbsp;&nbsp;</th>
				<td><?php echo exibedataHora($detalhes_row->data_fechar);?></td>
			</tr>
			<tr>
				<th><?php echo lang('RESPONSAVEL_VALIDADO');?>:&nbsp;&nbsp;</th>
				<td><?php echo $detalhes_row->validado;?></td>
			</tr>
			<tr>
				<th><?php echo lang('DATA_VALIDADO');?>:&nbsp;&nbsp;</th>
				<td><?php echo exibedataHora($detalhes_row->data_validar);?></td>
			</tr>
			<tr>
				<th><?php echo lang('STATUS');?>:&nbsp;&nbsp;</th>
				<td><?php echo statusCaixa($detalhes_row->status);?></td>
			</tr>
			<tr>
				<th><?php echo lang('USUARIO');?>:&nbsp;&nbsp;</th>
				<td><?php echo $detalhes_row->usuario;?></td>
			</tr>
			<tr>
				<th><?php echo lang('DATA');?>:&nbsp;&nbsp;</th>
				<td><?php echo exibedataHora($detalhes_row->data);?></td>
			</tr>
		<tbody>
	</table>
</td>
<td width="50%">
	<table class="tabela">
		<tbody>
		<?php 
			$valor_abertura = $faturamento->getCaixaAbertura($id_caixa);
			$valor_retirada = $despesa->getCaixaRetirada($id_caixa);
		?>
			<tr>
				<td><?php echo lang('CAIXA_ABERTURA');?></td>
				<td><?php echo moeda($valor_abertura);?></td>
			</tr>
			<tr>
				<td><?php echo lang('CAIXA_TOTALRETIRADA');?></td>
				<td><?php echo moeda($valor_retirada);?></td>
			</tr>
		<?php 	
			$retorno_row = $faturamento->getFinanceiroCaixa($id_caixa);
			$total = 0;
			$valor_pago = 0;
			if($retorno_row):
				foreach ($retorno_row as $exrow):				
					$total += $valor_pago = $exrow->valor_pago;
					if($exrow->id == 1):
						$totaldinheiro = $valor_pago - $valor_retirada;
		?>
			<tr>
				<td><?php echo pagamento($exrow->pagamento);?></td>
				<td><?php echo moeda($valor_pago-$valor_abertura);?></td>
			</tr>
			<tr>
				<td><?php echo lang('CAIXA_TOTALDINHEIRO');?></td>
				<td><strong><?php echo moeda($totaldinheiro);?></strong></td>
			</tr>
		<?php
					else:
		?>
			<tr>
				<td><?php echo pagamento($exrow->pagamento);?></td>
				<td><?php echo moeda($valor_pago);?></td>
			</tr>
		<?php 	
				endif;
				endforeach;?>
			<tr>
				<td><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
				<td><strong><?php echo moeda($total-$valor_retirada);?></strong></td>
			</tr>
		<?php unset($exrow);
			  endif;?>
		</tbody>
	</table>
</td>
</tr>
</table>
<br/>
<div class="destaque"><span><?php echo lang('VENDAS_TITULO');?></span></div>
<table class="tabela">
	<thead>
		<tr>
			<th><?php echo lang('COD_VENDA');?></th>
			<th><?php echo lang('CLIENTE');?></th>
			<th><?php echo lang('VALOR');?></th>
			<th><?php echo lang('VALOR_DESCONTO');?></th>
			<th><?php echo lang('VALOR_TOTAL');?></th>
		</tr>
	</thead>
	<tbody>
		<?php 	
			$retorno_row = $faturamento->getVendasCaixa($id_caixa);
			$total = 0;
			$desconto = 0;
			$pago = 0;
			if($retorno_row):
				foreach ($retorno_row as $exrow):
					$total += $exrow->valor_total;
					$desconto += $exrow->valor_desconto;
					$pago += $exrow->valor_pago;
		?>
			<tr>
				<td><?php echo $exrow->id;?></td>
				<td><?php echo $exrow->cliente;?></td>
				<td><?php echo decimal($exrow->valor_total);?></td>
				<td><?php echo decimal($exrow->valor_desconto);?></td>
				<td><?php echo decimal($exrow->valor_pago);?></td>
			</tr>
		<?php 	endforeach;?>
			<tr>
				<td colspan="2"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
				<td><strong><?php echo decimal($total);?></strong></td>
				<td><strong><?php echo decimal($desconto);?></strong></td>
				<td><strong><?php echo decimal($pago);?></strong></td>
			</tr>
		<?php unset($exrow);
			  endif;?>
	</tbody>
</table>
<?php if($valor_retirada > 0): ?>
<br/>
<div class="destaque"><?php echo lang('CAIXA_RETIRADAS');?></div>
<table class="tabela">
	<thead>
		<tr>
			<th><?php echo lang('FORNECEDOR');?></th>
			<th><?php echo lang('DESCRICAO');?></th>
			<th><?php echo lang('PLANO_CONTAS');?></th>
			<th><?php echo lang('VALOR');?></th>
		</tr>
	</thead>
	<tbody>
	<?php 	
		$retorno_row = $despesa->getCaixaListaRetirada($id_caixa);
		$valor_retirada = 0;
		if($retorno_row):
			foreach ($retorno_row as $exrow):
				$valor_retirada += $exrow->valor;
				$fornecedor = ($exrow->id_fornecedor) ? $exrow->fornecedor : lang('RETIRADA_SANGRIA');;
	?>
		<tr>
			<td><?php echo $fornecedor;?></td>
			<td><?php echo $exrow->descricao;?></td>
			<td><?php echo $exrow->conta;?></td>
			<td><?php echo moeda($exrow->valor);?></td>
		</tr>
	<?php 	endforeach;?>
		<tr>
			<td colspan="3"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
			<td><strong><?php echo moeda($valor_retirada);?></strong></td>
		</tr>
	<?php unset($exrow);
		  endif;?>
	</tbody>
</table>
<?php endif; ?>
<br/>
<div class="destaque"><?php echo lang('CAIXA_MOVIMENTO');?></div>
<table class="tabela">
	<thead>
		<tr>
			<th><?php echo lang('COD_VENDA');?></th>
			<th><?php echo lang('CLIENTE');?></th>
			<th><?php echo lang('PAGAMENTO');?></th>
			<th><?php echo lang('DETALHES');?></th>
			<th><?php echo lang('VALOR_VENDA');?></th>
			<th><?php echo lang('VALOR_PAGO');?></th>
		</tr>
	</thead>
	<tbody>
	<?php 	
		$retorno_row = $faturamento->getMovimentoCaixa($id_caixa);
		if($retorno_row):
			foreach ($retorno_row as $exrow):
				$detalhes = '';
				if($exrow->tipo == 2) {
					$detalhes = 'NUMERO: ['.$exrow->numero_cheque.'] - '.$exrow->banco_cheque;
				}elseif($exrow->tipo == 3) {
					$detalhes = $exrow->banco;
				}
	?>
		<tr>
			<?php if($exrow->id_venda):?>
			<td><?php echo $exrow->id_venda;?></td>
			<td><?php echo $exrow->cliente;?></td>
			<?php else:?>
			<td colspan="2"><?php echo 'ABERTURA DE CAIXA';?></td>
			<?php endif;?>
			<td><?php echo $exrow->pagamento;?></td>
			<td><?php echo $detalhes;?></td>
			<td><?php echo decimal($exrow->valor_total_venda);?></td>
			<td><?php echo ($exrow->inativo) ? "-" : decimal($exrow->valor_pago);?></td>
		</tr>
	<?php endforeach;
		  unset($exrow);
		  endif;?>
	</tbody>
</table>
<?php if($usuario->is_Master()):?>
<br/>
<div class="destaque"><?php echo lang('PRODUTOS');?></div>
<table class="tabela">
	<thead>
		<tr>
			<th><?php echo lang('COD_VENDA');?></th>
			<th><?php echo lang('CLIENTE');?></th>
			<th><?php echo lang('PRODUTO');?></th>
			<th><?php echo lang('VALOR');?></th>
			<th><?php echo lang('DESCONTO');?></th>
			<th><?php echo lang('TOTAL');?></th>
			<th><?php echo lang('USUARIO');?></th>
		</tr>
	</thead>
	<tbody>
	<?php 	
		$tquant = 0;
		$total = 0;
		$total_custo = 0;
		$total_margem = 0;
		$retorno_row = $faturamento->getVendasCaixaTipo($id_caixa);
		if($retorno_row):
			foreach ($retorno_row as $exrow):
			$valor_custo = getValue("valor_custo", "produto", "id = ".$exrow->id_produto);
			$custo = $valor_custo*$exrow->quantidade;
			$margem = ($custo) ? $exrow->valor_total/$custo : 0;
			$lucro = $exrow->valor_total - $custo;
			$total_custo += $custo;
			$total_margem += $margem;
			$total += $exrow->valor_total;
			$tquant++;
	?>
		<tr>
			<td><?php echo $exrow->id_venda;?></td>
			<td><?php echo $exrow->cliente;?></td>
			<td><?php echo $exrow->produto;?></td>
			<td><?php echo decimal($exrow->valor);?></td>
			<td><?php echo decimal($exrow->valor_desconto);?></td>
			<td><?php echo decimal($exrow->valor_total);?></td>
			<td><?php echo $exrow->usuario;?></td>
		</tr>
	<?php endforeach;?>	
		<tr>
			<td colspan="5"><strong><?php echo lang('TOTAL');?></strong></td>
			<td><strong><?php echo decimal($total);?></strong></td>
			<td><strong><?php echo "Quant.: ".$tquant;?></strong></td>
		</tr>
	<?php unset($exrow);
		  endif;?>
	</tbody>
</table>
<?php endif;?>