<?php
  /**
   * Visualizar Caixa Detalhes
   *
   */
?>
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
<br/>
<div class="destaque"><?php echo lang('CAIXA_RESUMO');?></div>
<table class="tabela">
	<thead>
		<tr>
			<th><?php echo lang('DESCRICAO');?></th>
			<th><?php echo lang('VALOR');?></th>
			<th><?php echo lang('DETALHES');?></th>
		</tr>
	</thead>
	<tbody>
	<?php 
		$valor_abertura = $faturamento->getCaixaAbertura($id_caixa);
		$valor_retirada = $despesa->getCaixaRetirada($id_caixa);
	?>
		<tr>
			<td><?php echo lang('CAIXA_ABERTURA');?></td>
			<td><?php echo moeda($valor_abertura);?></td>
			<td></td>
		</tr>
		<tr>
			<td><?php echo lang('CAIXA_TOTALRETIRADA');?></td>
			<td><?php echo moeda($valor_retirada);?></td>
			<td></td>
		</tr>
	<?php 	
		$retorno_row = $faturamento->getFinanceiroCaixa($id_caixa);
		$total = 0;
		$valor_pago = 0;
		$totaldinheiro = 0;
		if($retorno_row):
			foreach ($retorno_row as $exrow):				
				$total += $valor_pago = $exrow->valor_pago;
				if($exrow->id_categoria == 1){
					$totaldinheiro += $valor_pago;
	?>
					<tr>
						<td><?php echo pagamento($exrow->pagamento);?></td>
						<td><?php echo moeda($valor_pago);?></td>
						<td><?php echo $exrow->detalhes;?></td>
					</tr>
	<?php
				}
				if($exrow->id_categoria <> 1){
	?>
					<tr>
						<td><?php echo pagamento($exrow->pagamento);?></td>
						<td><?php echo moeda($valor_pago);?></td>
						<td><?php echo $exrow->detalhes;?></td>
					</tr>	
	<?php 	
				};
			endforeach;
			$totaldinheiro -= $valor_retirada;
	?>
		<tr>
			<td><strong><?php echo lang('CAIXA_TOTALDINHEIRO');?></strong></td>
			<td colspan="2"><strong><?php echo moeda($totaldinheiro);?></strong></td>
		</tr>
		<tr>
			<td><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
			<td colspan="2"><strong><?php echo moeda($total-$valor_retirada);?></strong></td>
		</tr>
	<?php unset($exrow);
		  endif;
	?>
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
			$contador = 0;
			$total_pago = 0;
			foreach ($retorno_row as $exrow):
				$total_pago += $exrow->valor_pago;
				$detalhes = '';
				$categoria_pagamento = getValue("id_categoria","tipo_pagamento","id=".$exrow->tipo);
				if ($exrow->detalhe) {
					$detalhes = $exrow->detalhe;
				}elseif($exrow->tipo == 0) {
					$detalhes = pagamento($exrow->pagamento);
				}elseif($categoria_pagamento == 1 || $categoria_pagamento == 3 || $categoria_pagamento == 6) {
					$detalhes = 'Vendas';
				}elseif($categoria_pagamento == 2) {
					$detalhes = 'Vendas-NUMERO: ['.$exrow->numero_cheque.'] - '.$exrow->banco_cheque;
				}elseif($categoria_pagamento == 8) {
					$detalhes = 'Vendas-BANCO: '.$exrow->banco;
				}elseif($categoria_pagamento == 9) {
					$detalhes = "Vendas-CREDIARIO";
				} else {
					$detalhes = 'Vendas-PARCELA: ['.$exrow->parcelas_cartao.'] - '.$exrow->numero_cartao;
				}
	?>
		<tr>
			<?php if($exrow->id_venda):?>
				<td><?php echo $exrow->id_venda;?></td>
				<td><?php echo $exrow->cadastro;?></td>
			<?php else:
				$contador++; 
				if ($contador > 1):?>
					<td colspan="2"><?php echo 'ADICIONADO AO CAIXA';?></td>
				<?php else:?>
					<td colspan="2"><?php echo 'ABERTURA DE CAIXA';?></td>
				<?php endif;?>
			<?php endif;?>
			<td><?php echo pagamento($exrow->pagamento);?></td>
			<td><?php echo $detalhes;?></td>
			<td><?php echo moedap($exrow->valor_total_venda);?></td>
			<td><?php echo ($exrow->inativo) ? "-" : moedap($exrow->valor_pago);?></td>
		</tr>
	<?php endforeach;?>
		<tr>
			<td colspan="5"><strong><?php echo lang('VALOR_TOTAL');?></strong></td>
			<td><strong><?php echo moedap($total_pago);?></strong></td>
		</tr>
	<?php unset($exrow);
		  endif;?>
	</tbody>
</table>