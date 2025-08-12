<?php
  /**
   * Imprimir Ordem de Serviço
   *
   */
	
?>
<?php
  //CONFIGURACOES	
  $fonteTexto = '10px';
?>

<?php if (empty($orcamento)): ?>
	<div class="form-body">
		<div class="row">
			<div class="col-md-12">
				<div class="note note-warning">
					<h3 align="center" style="color: red;"><?php echo lang('ORDEM_SERVICO_VISUALIZAR_INVALIDO'); ?></h3>
					<h1><!-- espaçamento entre linhas --></h1>
					<h4 class="block"><?php echo lang('ORDEM_SERVICO_VISUALIZAR_NAO'); ?></h4>
				</div>
			</div>
		</div>
	</div>
<?php elseif ($orcamento->id_status < 5 || $orcamento->id_status > 8): ?>
	<div class="form-body">
		<div class="row">
			<div class="col-md-12">
				<div class="note note-warning">
					<h3 align="center" style="color: red;"><?php echo lang('ORDEM_SERVICO_VISUALIZAR_INVALIDO'); ?></h3>
					<h1><!-- espaçamento entre linhas --></h1>
					<h4 class="block"><?php echo lang('ORDEM_SERVICO_VISUALIZAR_NAO'); ?></h4>
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
			<th colspan="6" style="text-align:center;font-size:12px;"><strong><?php echo lang('ORDEM_SERVICO_M')?></strong></th>
		</tr>
		<tr>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('CLIENTE'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;" colspan="3"><strong><?php echo $orcamento->cliente; ?></strong></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('ORCAMENTO_TITULO'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><strong><?php echo $orcamento->id; ?></strong></td>
		</tr>
		<tr>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('CELULAR'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><strong><?php echo $orcamento->celularcliente; ?></strong></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('TELEFONE'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><strong><?php echo $orcamento->fixocliente; ?></strong></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('EMAIL'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><strong><?php echo $orcamento->emailcliente; ?></strong></td>
		</tr>
	</table>
	
	<div>&nbsp;</div>
	
	<table class="tabela">
		<tr>
			<th style="text-align:center;font-size:12px;"><strong><?php echo lang('ORCAMENTO_EQUIPAMENTO_M')?></strong></th>
		</tr>
		<tr>
			<td>
				<table>
					<tr>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('EQUIPAMENTO'); ?></td>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $detalhamento->equipamento; ?></td>
					</tr>
					<?php if ($detalhamento->equipamento_digitado): ?>
					<tr>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('REFERENCIA'); ?></td>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $detalhamento->equipamento_digitado; ?></td>
					</tr>
					<?php elseif (!empty($detalhamento->codigo_referencia)): ?>
					<tr>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('REFERENCIA'); ?></td>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $detalhamento->codigo_referencia; ?></td>
					</tr>
					<?php endif; ?>
					<?php if (!empty($detalhamento->etiqueta)): ?>
					<tr>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('ETIQUETA'); ?></td>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $detalhamento->etiqueta; ?></td>
					</tr>
					<?php endif; ?>
				</table>
			</td>
		</tr>
	</table>

	<div>&nbsp;</div>

	<?php if (!empty($detalhamento->descricao_problema) && $detalhamento->descricao_problema != ""): ?>
	<table class="tabela">	
		<tr>
			<th style="text-align:center;font-size:12px;"><strong><?php echo lang('ORCAMENTO_PROBLEMA_M')?></strong></th>
		</tr>
		<tr>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $detalhamento->descricao_problema; ?></td>
		</tr>
	</table>
	<?php endif; ?>
	
	<div>&nbsp;</div>
	
	<?php if (!empty($detalhamento->descricao_orcamento) && $detalhamento->descricao_orcamento != ""): ?>
	<table class="tabela">	
		<tr>
			<th style="text-align:center;font-size:12px;"><strong><?php echo lang('ORCAMENTO_SOLUCAO_M')?></strong></th>
		</tr>
		<tr>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $detalhamento->descricao_orcamento; ?></td>
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
						<th colspan="5" style="text-align:center;font-size:12px;"><strong><?php echo lang('ORCAMENTO_PRODUTOS_M')?></strong></th>
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
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $exrow->produto;?></td>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $exrow->descricao;?></td>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo moeda($exrow->valor);?></td>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo decimal($exrow->quantidade);?></td>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo moeda($exrow->valor_total);?></td>
					</tr>
	<?php 	endforeach;
			unset($exrow);
	?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="4"><?php echo lang('VALOR_PRODUTOS'); ?></th>
						<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo moeda($valor_produtos); ?></td>
					</tr>
				</tfoot>
			</table>
	<?php
		endif;
	?>
	<div>&nbsp;</div>
	
	<table class="tabela">	
		<tr>
			<th colspan="2" style="text-align:center;font-size:12px;"><strong><?php echo lang('ORCAMENTO_RESUMO')?></strong></th>
		</tr>
		<tr>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('SERVICO_M'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo moeda($detalhamento->valor_servico).' (+)'; ?></td>
		</tr>
		<?php if ($detalhamento->valor_produto > 0): ?>
		<tr>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('PRODUTO_M'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo moeda($detalhamento->valor_produto).' (+)'; ?></td>
		</tr>
		<?php endif; ?>
		
		<?php 
			$row_adicionais = $ordem_servico->getValoresAdicionaisOrdemServico($id_orcamento);
			if ($row_adicionais): 
				foreach($row_adicionais as $adRow):
		?>
				<tr>
					<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $adRow->descricao; ?></td>
					<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo moeda($adRow->valor_adicional).' (+)'; ?></td>
				</tr>
		<?php 
				endforeach;
			endif; ?>
		
		<?php if ($detalhamento->valor_desconto > 0): ?>
		<tr>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('DESCONTO_M'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo moeda($detalhamento->valor_desconto).' (-)'; ?></td>
		</tr>
		<?php endif; ?>
		
		<tr>
			<th><?php echo lang('VALOR_TOTAL'); ?></th>
			<th><?php echo moeda($detalhamento->valor_total); ?></th>
		</tr>
	</table>
	
	<?php if (!empty($detalhamento->prazo_entrega) || !empty($detalhamento->garantia) || !empty($detalhamento->condicao_pagamento)): ?>	
	
	<div>&nbsp;</div>
		
	<table class="tabela">	
		<tr>
			<th colspan="2" style="text-align:center;font-size:12px;"><strong><?php echo lang('ORCAMENTO_INFO_ADICIONAIS_M')?></strong></th>
		</tr>
		<?php if (!empty($detalhamento->prazo_entrega)): ?>
		<tr>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('ORCAMENTO_PRAZO'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $detalhamento->prazo_entrega; ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($detalhamento->garantia)): ?>
		<tr>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('ORCAMENTO_GARANTIA'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $detalhamento->garantia; ?></td>
		</tr>
		<?php endif; ?>
		<?php if (!empty($detalhamento->condicao_pagamento)): ?>
		<tr>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo lang('ORCAMENTO_PAGAMENTO'); ?></td>
			<td style="font-size:<?php echo $fonteTexto; ?>;"><?php echo $detalhamento->condicao_pagamento; ?></td>
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
			<td style="text-align:center;font-size:<?php echo $fonteTexto; ?>;"><?php echo "<strong>".$orcamento->cliente."</strong>"; ?></td>
			<td style="text-align:center;font-size:<?php echo $fonteTexto; ?>;"><?php echo "<strong>".$orcamento->nomeempresa."</strong>"; ?></td>
		</tr>
	</table>

<?php endif; ?>

