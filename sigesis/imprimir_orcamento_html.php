<?php
  /**
   * Imprimir Orçamento - Serviços
   *
   */
	
?>

<?php if ($orcamento->id_status != 3 && $orcamento->id_status != 4): ?>
	<div class="form-body">
		<div class="row">
			<div class="col-md-12">
				<div class="note note-warning">
					<h3 align="center" style="color: red;"><?php echo lang('ORCAMENTO_VISUALIZAR_INVALIDO'); ?></h3>
					<h1><!-- espaçamento entre linhas --></h1>
					<h4 class="block"><?php echo lang('ORCAMENTO_VISUALIZAR_NAO'); ?></h4>
					<p><?php echo lang('EMPRESA').": ";?><strong><?php echo $orcamento->nomeempresa;?></strong></p>
					<p><?php echo lang('CONTATO').": ";?><strong><?php echo $orcamento->telefoneempresa.' | '.$orcamento->celularempresa.' | '.$orcamento->emailempresa;?></strong></p>
				</div>
			</div>
		</div>
	</div>
<?php else: 
		$detalhamento = $ordem_servico->getDetalhamentoImpressao($id_orcamento)
?>

	<table class="tabela">
		<tr>
			<th colspan="6" style="text-align:center;font-size:14px;"><strong><?php echo lang('ORCAMENTO_M')?></strong></th>
		</tr>
		<tr>
			<td><?php echo lang('CLIENTE'); ?></td>
			<td colspan="3"><strong><?php echo $orcamento->cliente; ?></strong></td>
			<td><?php echo lang('ORCAMENTO_TITULO'); ?></td>
			<td><strong><?php echo $orcamento->id; ?></strong></td>
		</tr>
		<tr>
			<td><?php echo lang('CELULAR'); ?></td>
			<td><strong><?php echo $orcamento->celularcliente; ?></strong></td>
			<td><?php echo lang('TELEFONE'); ?></td>
			<td><strong><?php echo $orcamento->fixocliente; ?></strong></td>
			<td><?php echo lang('EMAIL'); ?></td>
			<td><strong><?php echo $orcamento->emailcliente; ?></strong></td>
		</tr>
	</table>
	
	<div>&nbsp;</div>
	
	<table class="tabela">
		<tr>
			<th style="text-align:center;font-size:14px;"><strong><?php echo lang('ORCAMENTO_EQUIPAMENTO_M')?></strong></th>
		</tr>
		<tr>
			<td>
				<div>&nbsp;</div>
				<table>
					<tr>
						<td><?php echo lang('EQUIPAMENTO'); ?></td>
						<td><?php echo $detalhamento->equipamento; ?></td>
					</tr>
					<?php if ($detalhamento->equipamento_digitado): ?>
					<tr>
						<td><?php echo lang('REFERENCIA'); ?></td>
						<td><?php echo $detalhamento->equipamento_digitado; ?></td>
					</tr>
					<?php elseif (!empty($detalhamento->codigo_referencia)): ?>
					<tr>
						<td><?php echo lang('REFERENCIA'); ?></td>
						<td><?php echo $detalhamento->codigo_referencia; ?></td>
					</tr>
					<?php endif; ?>
					<?php if (!empty($detalhamento->etiqueta)): ?>
					<tr>
						<td><?php echo lang('ETIQUETA'); ?></td>
						<td><?php echo $detalhamento->etiqueta; ?></td>
					</tr>
					<?php endif; ?>
				</table>
				<div>&nbsp;</div>
			</td>
		</tr>
	</table>

	<div>&nbsp;</div>

	<?php if (!empty($detalhamento->descricao_problema) && $detalhamento->descricao_problema != ""): ?>
	<table class="tabela">	
		<tr>
			<th style="text-align:center;font-size:14px;"><strong><?php echo lang('ORCAMENTO_PROBLEMA_M')?></strong></th>
		</tr>
		<tr>
			<td><div>&nbsp;</div><?php echo $detalhamento->descricao_problema; ?><div>&nbsp;</div></td>
		</tr>
	</table>
	<?php endif; ?>
	
	<div>&nbsp;</div>
	
	<?php if (!empty($detalhamento->descricao_orcamento) && $detalhamento->descricao_orcamento != ""): ?>
	<table class="tabela">	
		<tr>
			<th style="text-align:center;font-size:14px;"><strong><?php echo lang('ORCAMENTO_SOLUCAO_M')?></strong></th>
		</tr>
		<tr>
			<td><div>&nbsp;</div><?php echo $detalhamento->descricao_orcamento; ?><div>&nbsp;</div></td>
		</tr>
	</table>
	<?php endif; ?>
	
	<div>&nbsp;</div>
	
	<?php 
		$produtos = $ordem_servico->getItensOrdemServico($id_orcamento);
		if ($produtos):
			$item = 0;
			$valor_produtos = 0;
	?>		
			<table class="tabela">	
				<thead>
					<tr>
						<th colspan="5" style="text-align:center;font-size:14px;"><strong><?php echo lang('ORCAMENTO_PRODUTOS_M')?></strong></th>
					</tr>
					<tr>
						<th><?php echo lang('PRODUTO');?></th>
						<th><?php echo lang('DESCRICAO');?></th>
						<th><?php echo lang('VALOR_UNITARIO');?></th>
						<th><?php echo lang('QUANT');?></th>
						<th><?php echo lang('VALOR_TOTAL');?></th>
					</tr>
				</thead>
				<tbody>
	<?php												
			foreach($produtos as $exrow):
				$valor_produtos += $exrow->valor_total;
	?>
					<tr>
						<td><?php echo $exrow->produto;?></td>
						<td><?php echo $exrow->descricao;?></td>
						<td><?php echo moeda($exrow->valor);?></td>
						<td><?php echo decimal($exrow->quantidade);?></td>
						<td><?php echo moeda($exrow->valor_total);?></td>
					</tr>
	<?php 	endforeach;
			unset($exrow);
	?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="4"><?php echo lang('VALOR_PRODUTOS'); ?></th>
						<td><?php echo moeda($valor_produtos); ?></td>
					</tr>
				</tfoot>
			</table>
	<?php
		endif;
	?>
	<div>&nbsp;</div>
	<div>&nbsp;</div>

	<table class="tabela">	
		<tr>
			<th colspan="2" style="text-align:center;font-size:14px;"><strong><?php echo lang('ORCAMENTO_RESUMO')?></strong></th>
		</tr>
		<tr>
			<td><?php echo lang('SERVICO_M'); ?></td>
			<td><?php echo moeda($detalhamento->valor_servico).' (+)'; ?></td>
		</tr>
		<?php if ($detalhamento->valor_produto > 0): ?>
		<tr>
			<td><?php echo lang('PRODUTO_M'); ?></td>
			<td><?php echo moeda($detalhamento->valor_produto).' (+)'; ?></td>
		</tr>
		<?php endif; ?>
		
		<?php 
			$row_adicionais = $ordem_servico->getValoresAdicionaisOrdemServico($id_orcamento);
			if ($row_adicionais): 
				foreach($row_adicionais as $adRow):
		?>
				<tr>
					<td><?php echo $adRow->descricao; ?></td>
					<td><?php echo moeda($adRow->valor_adicional).' (+)'; ?></td>
				</tr>
		<?php 
				endforeach;
			endif; ?>
		
		<?php if ($detalhamento->valor_desconto > 0): ?>
		<tr>
			<td><?php echo lang('DESCONTO_M'); ?></td>
			<td><?php echo moeda($detalhamento->valor_desconto).' (-)'; ?></td>
		</tr>
		<?php endif; ?>
		
		<tr>
			<th><?php echo lang('VALOR_TOTAL'); ?></th>
			<th><?php echo moeda($detalhamento->valor_total); ?></th>
		</tr>
	</table>
	
	<?php if (!empty($detalhamento->prazo_entrega) || !empty($detalhamento->garantia) || !empty($detalhamento->condicao_pagamento)): ?>	
	
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	
	<table class="tabela">	
		<tr>
			<th colspan="2" style="text-align:center;font-size:14px;"><strong><?php echo lang('ORCAMENTO_INFO_ADICIONAIS_M')?></strong></th>
		</tr>
		<?php if (!empty($detalhamento->prazo_entrega)): ?>
		<tr>
			<td><?php echo lang('ORCAMENTO_PRAZO'); ?></td>
			<td><?php echo $detalhamento->prazo_entrega; ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($detalhamento->garantia)): ?>
		<tr>
			<td><?php echo lang('ORCAMENTO_GARANTIA'); ?></td>
			<td><?php echo $detalhamento->garantia; ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($detalhamento->condicao_pagamento)): ?>
		<tr>
			<td><?php echo lang('ORCAMENTO_PAGAMENTO'); ?></td>
			<td><?php echo $detalhamento->condicao_pagamento; ?></td>
		</tr>
		<?php endif; ?>
	</table>
	
	<?php endif; ?>
	
	<div>&nbsp;</div>
	<div>&nbsp;</div>
	
	<table>
		<tr>
			<td style="text-align:center;"><?php echo '__________________________________'; ?></td>
			<td style="text-align:center;"><?php echo '__________________________________'; ?></td>
		</tr>
		<tr>
			<td style="text-align:center;"><?php echo "<strong>".$orcamento->cliente."</strong>"; ?></td>
			<td style="text-align:center;"><?php echo "<strong>".$orcamento->nomeempresa."</strong>"; ?></td>
		</tr>
	</table>

<?php endif; ?>

