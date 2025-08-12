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

<div class="destaque"><span><?php echo lang('NOTA_INVENTARIO_REGISTRO').' - '.$mes_ano;?></span></div>
<div class="destaque"><span><?php echo $nomeempresa.' - '.formatar_cpf_cnpj($cnpj_empresa);?></span></div>

<table class="tabela">
	<thead>
		<tr>
			<th width="90px"><?php echo lang('CODIGO');?></th>
			<th width="200px"><?php echo lang('PRODUTO');?></th>
			<th width="63px"><?php echo lang('NCM');?></th>
			<th width="55px"><?php echo lang('UNIDADE');?></th>
			<th width="70px"><?php echo lang('QUANTIDADE');?></th>
			<th width="70px"><?php echo lang('VALOR_UNITARIO');?></th>
			<th width="90px"><?php echo lang('VALOR_TOTAL');?></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		
			$total = 0;
			if($retorno_row):
				foreach ($retorno_row as $exrow):
				$total += $valor = $exrow->quantidade*$exrow->valor_unitario;
		?>
			<tr>
				<td width="90px"><?php echo $exrow->id;?></td>
				<td width="200px"><?php echo $exrow->nome;?></td>
				<td width="63px"><?php echo $exrow->ncm;?></td>
				<td width="55px"><?php echo $exrow->unidade;?></td>
				<td width="70px"><?php echo decimal($exrow->quantidade);?></td>
				<td width="70px"><?php echo moeda($exrow->valor_unitario);?></td>
				<td width="90px"><?php echo moeda($valor);?></td>
			</tr>
		<?php 	endforeach;?>
			<tr>
				<td width="538px" colspan="4"><?php echo lang('TOTAL');?></td>
				<td width="100px"><?php echo moeda($total);?></td>
			</tr>
		<?php unset($exrow);
			  endif;?>
	</tbody>
</table>