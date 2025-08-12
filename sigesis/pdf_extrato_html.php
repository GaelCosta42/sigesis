<?php
  /**
   * PDF Extrato
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   *
   * LARGURA PADRÃO - RETRATO (P) - EM px: 638px
   * LARGURA PADRÃO - PAISAGEM (L) - EM px: 946px
   */
	
?>
<table class="tabela">
	<thead>
		<tr>
			<th width="100px"><?php echo lang('DATA');?></th>
			<th width="288px"><?php echo lang('DESCRICAO');?></th>
			<th width="100px"><?php echo lang('VALOR');?></th>
			<th width="100px"><?php echo lang('SALDO');?></th>
			<th width="50px"><?php echo lang('TIPO');?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td width="100px"><?php echo ($dataini);?></td>
			<td width="288px"><?php echo lang('SALDO');?></td>
			<td width="100px">-</td>
			<td width="100px"><strong <?php echo ($saldoinicial < 0) ? 'class="font-red"' : 'class="font-green"'?>><?php echo moeda($saldoinicial);?></strong></td>	
			<td width="50px">-</td>			
		</tr>
		<?php
				if($extrato_row):
				foreach ($extrato_row as $exrow):
				$descricao = '';
				if($exrow->tipo == 'D') {
					$saldo -= $exrow->valor;
					if($exrow->ti_ch == 1) {
						$descricao = 'CHEQUE - ';		
					}
					$descricao .= $exrow->descricao;
				} elseif($exrow->tipo == 'C') {
					$saldo += $exrow->valor;
					$descricao = $exrow->descricao;
				} else {
					$saldo += $exrow->valor;
					$descricao = $exrow->conta;
				}
		?>
			<tr>
				<td width="100px"><?php echo exibedata($exrow->data_pagamento);?></td>
				<td width="288px"><?php echo $descricao;?></td>
				<td width="100px"><strong <?php echo ($exrow->tipo == 'D') ? 'class="font-red"' : 'class="font-green"'?>><?php echo moeda($exrow->valor);?></strong></td>	
				<td width="100px"><strong <?php echo ($saldo < 0) ? 'class="font-red"' : 'class="font-green"'?>><?php echo moeda($saldo);?></strong></td>	
				<td width="50px"><?php echo $exrow->tipo;?></td>
			</tr>
		<?php 	endforeach;?>
		<?php unset($exrow);
			  endif;?>
	</tbody>
</table>