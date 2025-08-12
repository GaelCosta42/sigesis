<?php
  /**
   * PDF HTML Servico Dia
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
			<th width="80px"><?php echo lang('VENCIMENTO');?></th>
			<th width="130px"><?php echo lang('EMPRESA');?></th>
			<th width="90px"><?php echo lang('BANCO');?></th>
			<th width="168px"><?php echo lang('CLIENTE');?></th>
			<th width="90px"><?php echo lang('DOCUMENTO');?></th>
			<th width="80px"><?php echo lang('VALOR');?></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		
			$total = 0;
			$descricao = '';
			if($retorno_row):
				foreach ($retorno_row as $exrow):
					$total += $exrow->valor;
		?>
			<tr>
				<td width="80px"><?php echo exibedata($exrow->data_pagamento);?></td>
				<td width="130px"><?php echo substr($exrow->empresa,0,15);?></td>
				<td width="90px"><?php echo substr($exrow->banco,0,10);?></td>
				<td width="168px"><?php echo substr($exrow->cadastro,0,25);?></td>
				<td width="90px"><?php echo substr($exrow->duplicata,0,10);?></td>
				<td width="80px"><?php echo moeda($exrow->valor);?></td>
			</tr>
		<?php 	endforeach;?>
			<tr>
				<td width="558px" colspan="5"><strong><?php echo lang('TOTAL');?></strong></td>
				<td width="80px"><strong><?php echo moeda($total);?></strong></td>
			</tr>
		<?php unset($exrow);
			  endif;?>
	</tbody>
</table>