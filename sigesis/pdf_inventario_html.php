<?php
  /**
   * PDF HTML Inventario
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
			<th width="100px"><?php echo lang('CODIGO');?></th>
			<th width="338px"><?php echo lang('PRODUTO');?></th>
			<th width="100px"><?php echo lang('ESTOQUE');?></th>
			<th width="100px"><?php echo lang('INVENTARIO');?></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		
			if($retorno_row):
				foreach ($retorno_row as $exrow):
		?>
			<tr>
				<td width="100px"><?php echo $exrow->codigo;?></td>
				<td width="338px"><?php echo $exrow->produto;?></td>
				<td width="100px"><?php echo $exrow->estoque;?></td>
				<td width="100px"></td>
			</tr>
		<?php 	endforeach;?>
		<?php unset($exrow);
			  endif;?>
	</tbody>
</table>