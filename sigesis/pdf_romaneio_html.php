<?php
  /**
   * PDF Romaneio HTML
   *
   *
   * LARGURA PADRÃO - RETRATO (P) - EM px: 638px
   * LARGURA PADRÃO - PAISAGEM (L) - EM px: 946px
   */
	
?>

<table class="tabela">
	<tbody>
		<tr>
			<td align="right" ><?php echo lang('VIA_CLIENTE');?></td>
		</tr>
	</tbody>
</table>
<table class="tabela">
	<tbody >		
		<tr>
			<th width="50px" align="left"><?php echo lang('VENDA');?></th>
			<td width="50px"><?php echo $row->id;?></td>			
			<th width="80px" align="left"><?php echo lang('DATA_VENDA');?></th>
			<td width="80px"><?php echo exibedata($row->data_venda);?></td>
			<th width="90px" align="left"><?php echo lang('PRAZO_ENTREGA');?></th>
			<td width="80px"><?php echo exibedata($row->prazo_entrega);?></td>
			<th width="70px" align="left"><?php echo lang('VENDEDOR');?></th>
			<td width="138px"><?php echo ($nome_vendedor);?></td>
		</tr>
	</tbody>
</table>
<br/>
<br/>
<table>
	<tbody>
		<?php
			$row = Core::getRowById("vendas", $id);
			$nome_vendedor = ($id_vendedor == 0) ? lang('CADASTRO_VENDEDOR_SEM') : getValue("nome", "usuario", "id = ".$id_vendedor);
			
			$endereco = "";
			$bairro = "";
			$cidade = "";
			
			if($row->endereco) {
				$endereco = $row->endereco.', '.$row->complemento.', '.$row->referencia ; 
				$bairro = $row->bairro;
				$cidade = $row->cidade;
			}
			else {
				$endereco = $row_cadastro->endereco.', '.$row_cadastro->numero.', '.$row_cadastro->complemento ;
				$bairro = $row_cadastro->bairro;
				$cidade = $row_cadastro->cidade.' / '.$row_cadastro->estado;
			}
		?>
		<tr>
			<th width="50px" align="left" ><?php echo lang('NOME');?></th>
			<td width="390px"><?php echo $row_cadastro->nome;?></td>
			<th width="60px" align="left"><?php echo lang('CPF_CNPJ');?></th>
			<td width="138px"><?php echo $row_cadastro->cpf_cnpj;?></td>
		</tr>
		<tr>
			<th width="50px" align="left"><?php echo lang('TELEFONE');?></th>
			<td width="588px"><?php echo $row_cadastro->celular.' '.$row_cadastro->telefone.' '.$row_cadastro->celular2.' '.$row_cadastro->telefone2;?></td>
		</tr>
		<tr>
			<th width="50px" align="left"><?php echo lang('ENDERECO');?></th>
			<td width="588px"><?php echo $endereco; ?></td>
		</tr>
		<tr>
			<th width="50px" align="left"><?php echo lang('BAIRRO');?></th>
			<td width="288px"><?php echo $bairro ;?></td>
			<th width="60px" align="left"><?php echo lang('CIDADE');?></th>
			<td width="138px"><?php echo $cidade ;?></td>
		</tr>
	</tbody>
</table>
<br/>
<br/>
<table class="tabela" >
	<thead>
		<tr>
			<th colspan="4"><?php echo lang('OBSERVACAO');?></th>
		</tr>
	</thead>
	<tbody>
		<?php if($row->observacao):?>
			<tr>
				<td colspan="4"><?php echo nl2br($row->observacao);?></td>
			</tr>
		<?php endif;?>
	</tbody>
</table>
<br/>
<br/>
<table class="tabela">
	<thead>
		<tr>
			<th width="50px"><?php echo lang('CODIGO');?></th>
			<th width="90px"><?php echo lang('CODIGO_DE_BARRAS');?></th>
			<th width="148px"><?php echo lang('PRODUTO');?></th>
			<th width="50px"><?php echo lang('QUANT');?></th>
			<th width="70px"><?php echo lang('VALOR');?></th>
			<th width="70px"><?php echo lang('DESCONTO');?></th>
			<th width="90px"><?php echo lang('OUTRAS_DESPESAS');?></th>
			<th width="70px"><?php echo lang('VL_TOTAL');?></th>
		</tr>
	</thead>
	<tbody>
	<?php 	
		$descontos = 0;
		$despesas = 0;
		$total = 0;
		$total_item = 0;
		$retorno_row = $cadastro->getProdutosVenda($row->id);
		if($retorno_row):
			foreach ($retorno_row as $exrow):
				$descontos += $exrow->valor_desconto;
				$despesas += $exrow->valor_despesa_acessoria;
				$total += $exrow->valor_total;
				$total_item = $exrow->valor_total - $exrow->valor_desconto + $exrow->valor_despesa_acessoria;
				$fabricante = getValue("fabricante","fabricante","id=".$exrow->id_fabricante." AND exibir_romaneio = 1");
				$info_produto = $exrow->produto." ". ($exrow->id_fabricante == 0 ? ""  : " - ".$fabricante) ;
				$resposta = $produto->getAtributosProduto($exrow->id_produto);
	?>
		<tr>
			<td width="50px"><?php echo $exrow->codigo;?></td>
			<td width="90px"><?php echo $exrow->codigobarras;?></td>
			<td width="148px">
				<?php 
					echo $info_produto; 
					if($resposta){
						foreach($resposta as $res){
							if($res->exibir_romaneio == 1)
								echo " <br>- ".$res->atributo.": ".$res->descricao;
						} 
					}
				?>
			</td>
			<td width="50px"><?php echo decimalp($exrow->quantidade);?></td>
			<td width="70px"><?php echo moedap($exrow->valor);?></td>			
			<td width="70px"><?php echo moedap($exrow->valor_desconto);?></td>
			<td width="90px"><?php echo moedap($exrow->valor_despesa_acessoria);?></td>
			<td width="70px"><strong><?php echo moedap($total_item);?></strong></td>
		</tr>
	<?php
			endforeach;
	?>
		<tr>
			<td colspan="4"><strong><?php echo lang('TOTAL');?></strong></td>
			<td><strong><?php echo moedap($total);?></strong></td>
			<td><strong><?php echo moedap($descontos);?></strong></td>
			<td><strong><?php echo moedap($despesas);?></strong></td>
			<td><strong><?php echo moedap($total-$descontos+$despesas);?></strong></td>
		</tr>	
	<?php unset($exrow);
		endif;?>
	</tbody>
</table>
<br/>
<br/>
<table class="tabela">
	<thead>
		<tr>
			<th width="478px"><?php echo lang('PAGAMENTO');?></th>
			<th width="90px"><?php echo lang('PARCELAS');?></th>
			<th width="70px"><?php echo lang('VALOR');?></th>
		</tr>
	</thead>
	<tbody>
	<?php 	
		$total = 0;
		$parcela_valor = 0;
		$retorno_row = $cadastro->getPagamentosVenda($row->id);
		if($retorno_row):
			foreach ($retorno_row as $exrow):
				$total += $exrow->valor_pago;
				$parcela_valor = $exrow->valor_pago/$exrow->total_parcelas	
	?>
		<tr>
			<td width="478px"><?php echo $exrow->pagamento;?></td>
			<td width="90px"><?php echo $exrow->total_parcelas;?></td>
			<td width="70px"><strong><?php echo moedap($parcela_valor);?></strong></td>
		</tr>
	<?php 	endforeach;?>
		<tr>
			<td colspan="2"><strong><?php echo lang('TOTAL');?></strong></td>
			<td><strong><?php echo moedap($total);?></strong></td>
		</tr>
	<?php unset($exrow);
		  endif;?>	
	</tbody>
</table>
<br/>
<br/>
<br/>
<table>
	<tbody>
		<tr>
			<td>------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
		</tr>
	</tbody>
</table>
<br/>
<br/>
<br/>
<!--<div style="page-break-after: always"></div>-->
<table>
	<tbody>
		<tr>
			<td align="center"><?php echo $row_empresa->nome." - ".formatar_cpf_cnpj($row_empresa->cnpj); ?></td>
		</tr>
		<tr>
			<td align="center"><?php echo $row_empresa->endereco.", ".$row_empresa->numero." - ".$row_empresa->bairro." - ".$row_empresa->cidade." - ".$row_empresa->estado; ?></td>
		</tr>
	</tbody>
</table>
<table class="tabela">
	<tbody>
		<tr>
			<td align="right" ><?php echo lang('VIA_LOJA');?></td>
		</tr>
	</tbody>
</table>
<table class="tabela">
	<tbody>		
		<tr>
			<th width="50px" align="left"><?php echo lang('VENDA');?></th>
			<td width="50px"><?php echo $row->id;?></td>
			<th width="80px" align="left"><?php echo lang('DATA_VENDA');?></th>
			<td width="80px"><?php echo exibedata($row->data_venda);?></td>
			<th width="90px" align="left"><?php echo lang('PRAZO_ENTREGA');?></th>
			<td width="80px"><?php echo exibedata($row->prazo_entrega);?></td>
			<th width="70px" align="left"><?php echo lang('VENDEDOR');?></th>
			<td width="138px"><?php echo ($nome_vendedor);?></td>
		</tr>
	</tbody>
</table>
<br/>
<br/>
<table>
	<tbody>
		<?php
			$row = Core::getRowById("vendas", $id);
			$nome_vendedor = ($id_vendedor == 0) ? lang('CADASTRO_VENDEDOR_SEM') : getValue("nome", "usuario", "id = ".$id_vendedor);

			$endereco = "";
			$bairro = "";
			$cidade = "";
			
			if($row->endereco) {
				$endereco = $row->endereco.', '.$row->complemento.', '.$row->referencia;
				$bairro = $row->bairro;
				$cidade = $row->cidade;
			}
			else {
				$endereco = $row_cadastro->endereco.', '.$row_cadastro->numero.', '.$row_cadastro->complemento ;
				$bairro = $row_cadastro->bairro;
				$cidade = $row_cadastro->cidade.' / '.$row_cadastro->estado;
			}
		?>
		<tr>
			<th width="50px" align="left" ><?php echo lang('NOME');?></th>
			<td width="390px"><?php echo $row_cadastro->nome;?></td>
			<th width="60px" align="left"><?php echo lang('CPF_CNPJ');?></th>
			<td width="138px"><?php echo $row_cadastro->cpf_cnpj;?></td>
		</tr>
		<tr>
			<th width="50px" align="left"><?php echo lang('TELEFONE');?></th>
			<td width="588px"><?php echo $row_cadastro->celular.' '.$row_cadastro->telefone.' '.$row_cadastro->celular2.' '.$row_cadastro->telefone2;?></td>
		</tr>
		<tr>
			<th width="50px" align="left"><?php echo lang('ENDERECO');?></th>
			<td width="588px"><?php echo $endereco; ?></td>
		</tr>
		<tr>
			<th width="50px" align="left"><?php echo lang('BAIRRO');?></th>
			<td width="390px"><?php echo $bairro; ?></td>
			<th width="60px" align="left"><?php echo lang('CIDADE');?></th>
			<td width="138px"><?php echo $cidade;?></td>
		</tr>
	</tbody>
</table>
<br/>
<br/>
<table class="tabela" >
	<thead>
		<tr>
			<th colspan="4"><?php echo lang('OBSERVACAO');?></th>
		</tr>
	</thead>
	<tbody>
		<?php if($row->observacao):?>
			<tr>
				<td colspan="4"><?php echo nl2br($row->observacao);?></td>
			</tr>
		<?php endif;?>
	</tbody>
</table>
<br/>
<br/>
<table class="tabela">
	<thead>
		<tr>
			<th width="50px"><?php echo lang('CODIGO');?></th>
			<th width="90px"><?php echo lang('CODIGO_DE_BARRAS');?></th>
			<th width="148px"><?php echo lang('PRODUTO');?></th>
			<th width="50px"><?php echo lang('QUANT');?></th>
			<th width="70px"><?php echo lang('VALOR');?></th>
			<th width="70px"><?php echo lang('DESCONTO');?></th>
			<th width="90px"><?php echo lang('OUTRAS_DESPESAS');?></th>
			<th width="70px"><?php echo lang('VL_TOTAL');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
		$descontos = 0;
		$despesas = 0;
		$total = 0;
		$total_item = 0;
		$retorno_row = $cadastro->getProdutosVenda($row->id);
		if($retorno_row):
			foreach ($retorno_row as $exrow):
				$descontos += $exrow->valor_desconto;
				$despesas += $exrow->valor_despesa_acessoria;
				$total += $exrow->valor_total;
				$total_item = $exrow->valor_total - $exrow->valor_desconto + $exrow->valor_despesa_acessoria;
				$fabricante = getValue("fabricante","fabricante","id=".$exrow->id_fabricante." AND exibir_romaneio = 1");
				$info_produto = $exrow->produto." ". ($exrow->id_fabricante == 0 ? ""  : " - ".$fabricante) ;
				$resposta = $produto->getAtributosProduto($exrow->id_produto);
	?>
		<tr>
			<td width="50px"><?php echo $exrow->codigo;?></td>
			<td width="90px"><?php echo $exrow->codigobarras;?></td>
			<td width="148px">
				<?php 
					echo $info_produto; 
					if($resposta){
						foreach($resposta as $res){
							if($res->exibir_romaneio == 1)
								echo " <br>- ".$res->atributo.": ".$res->descricao;
						} 
					}
				?>
			</td>
			<td width="50px"><?php echo decimalp($exrow->quantidade);?></td>
			<td width="70px"><?php echo moedap($exrow->valor);?></td>
			<td width="70px"><?php echo moedap($exrow->valor_desconto);?></td>
			<td width="90px"><?php echo moedap($exrow->valor_despesa_acessoria);?></td>
			<td width="70px"><strong><?php echo moedap($total_item);?></strong></td>
		</tr>
	<?php
			endforeach;
	?>
		<tr>
			<td colspan="4"><strong><?php echo lang('TOTAL');?></strong></td>
			<td><strong><?php echo moedap($total);?></strong></td>
			<td><strong><?php echo moedap($descontos);?></strong></td>
			<td><strong><?php echo moedap($despesas);?></strong></td>
			<td><strong><?php echo moedap($total-$descontos+$despesas);?></strong></td>
		</tr>
	<?php unset($exrow);
		  endif;?>
	</tbody>
</table>
<br/>
<br/>
<table class="tabela">
	<thead>
		<tr>
			<th width="478px"><?php echo lang('PAGAMENTO');?></th>
			<th width="90px"><?php echo lang('PARCELAS');?></th>
			<th width="70px"><?php echo lang('VALOR');?></th>
		</tr>
	</thead>
	<tbody>
	<?php
		$total = 0;
		$parcela_valor = 0;
		$retorno_row = $cadastro->getPagamentosVenda($row->id);
		if($retorno_row):
			foreach ($retorno_row as $exrow):
				$total += $exrow->valor_pago;
				$parcela_valor = $exrow->valor_pago/$exrow->total_parcelas
	?>
		<tr>
			<td width="478px"><?php echo $exrow->pagamento;?></td>
			<td width="90px"><?php echo $exrow->total_parcelas;?></td>
			<td width="70px"><strong><?php echo moedap($parcela_valor);?></strong></td>
		</tr>
	<?php 	endforeach;?>
		<tr>
			<td colspan="2"><strong><?php echo lang('TOTAL');?></strong></td>
			<td><strong><?php echo moedap($total);?></strong></td>
		</tr>
	<?php unset($exrow);
		  endif;?>	
	</tbody>
</table>
<br/>
<br/>
<table>
	<tbody>
		<tr>
			<td><?php echo lang('TERMO_RECEBIMENTO');?></td>
		</tr>
	</tbody>
</table>
<br/>
<br/>
<br/>
<br/>
<table border="0">
	<tbody>
		<tr>
			<td align="center"><?php echo lang('TRACO');?></td>
			<td align="center"><?php echo lang('TRACO');?></td>
		</tr>
		<tr>
			<td align="center"><?php echo lang('ASSINATURA_CLIENTE');?></td>
			<td align="center"><?php echo lang('DATA_ENTREGA');?></td>
		</tr>
	</tbody>
</table>