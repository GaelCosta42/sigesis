<?php
  /**
   * PDF HTML Nota fiscal chave de acesso
   *
   *
   * LARGURA PADRÃO - RETRATO (P) - EM px: 638px
   * LARGURA PADRÃO - PAISAGEM (L) - EM px: 946px
   */
	
?>

<div class="destaque"><span><?php echo $nomeempresa;?></span></div>
<table class="tabela">
	<thead>
		<tr>
			<th width="70px"><?php echo lang('DATA_NOTA');?></th>
			<th width="78px"><?php echo lang('MODELO');?></th>
			<th width="60px"><?php echo lang('OPERACAO');?></th>
			<th width="60px"><?php echo lang('NUMERO');?></th>
			<th width="100px"><?php echo lang('VALOR_NOTA');?></th>
			<th width="270px"><?php echo lang('CHAVE_ACESSO');?></th>
		</tr>
	</thead>
	<tbody>
		<?php 		
			$total = 0;
			if($retorno_row):
				foreach ($retorno_row as $exrow):
					if((($exrow->fiscal == 1) && ($exrow->status_enotas == 'Autorizada')) || ($exrow->operacao==1))
						$total += $exrow->valor;
		?>
			<tr>
				<td width="70px"><?php echo exibedata($exrow->data_emissao);?></td>
				<td width="78px"><?php echo modelo($exrow->modelo);?></td>
				<td width="60px"><?php echo operacao($exrow->operacao);?></td>
				<td width="60px"><?php echo $exrow->numero_nota;?></td>
				<td width="100px"><?php echo ((($exrow->fiscal == 1) && ($exrow->status_enotas == 'Autorizada')) || ($exrow->operacao==1)) ? moedap($exrow->valor) : '-';?></td>
				<td width="270px"><?php echo ($exrow->chaveacesso) ? $exrow->chaveacesso : (($exrow->status_enotas=='Inutilizada') ? 'NUMERACAO INUTILIZADA' : $exrow->status_enotas);?></td>
			</tr>
		<?php 	endforeach;?>
			<tr>
				<td width="268px" colspan="5"><?php echo lang('TOTAL');?></td>
				<td width="100px"><?php echo moeda($total);?></td>
				<td width="270px"></td>
			</tr>
		<?php unset($exrow);
			  endif;?>
	</tbody>
</table>