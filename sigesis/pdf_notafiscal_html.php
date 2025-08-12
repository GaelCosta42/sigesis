<?php
  /**
   * PDF HTML Nota fiscal
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

<div class="destaque"><span><?php echo $nomeempresa;?></span></div>
<table class="tabela">
	<thead>
		<tr>
			<th width="100px"><?php echo lang('DATA_NOTA');?></th>
			<th width="138px"><?php echo lang('EMPRESA');?></th>
			<th width="100px"><?php echo lang('OPERACAO');?></th>
			<th width="100px"><?php echo lang('NOME');?></th>
			<th width="100px"><?php echo lang('NUMERO_NOTA');?></th>
			<th width="100px"><?php echo lang('VALOR_NOTA');?></th>
		</tr>
	</thead>
	<tbody>
		<?php 		
			$total = 0;
			if($retorno_row):
				foreach ($retorno_row as $exrow):
					$operacao = operacao($exrow->operacao);
					if($exrow->inativo) {
						$operacao = "CANCELADA";
					} else {
						$total += $exrow->valor_nota;
					}	
		?>
			<tr>
				<td width="100px"><?php echo exibedata($exrow->data_emissao);?></td>
				<td width="138px"><?php echo substr($exrow->empresa,0,13);?></td>
				<td width="100px"><?php echo $operacao;?></td>
				<td width="100px"><?php echo substr($exrow->razao_social,0,31);?></td>
				<td width="100px"><?php echo $exrow->numero_nota;?></td>
				<td width="100px"><?php echo moeda($exrow->valor_nota);?></td>
			</tr>
		<?php 	endforeach;?>
			<tr>
				<td width="538px" colspan="5"><?php echo lang('TOTAL');?></td>
				<td width="100px"><?php echo moeda($total);?></td>
			</tr>
		<?php unset($exrow);
			  endif;?>
	</tbody>
</table>