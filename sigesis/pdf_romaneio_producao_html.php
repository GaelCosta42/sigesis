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
<?php if($row->observacao):?>
<table class="tabela" >
	<thead>
		<tr>
			<th colspan="4"><?php echo lang('OBSERVACAO');?></th>
		</tr>
	</thead>
	<tbody>
		
			<tr>
				<td colspan="4"><?php echo nl2br($row->observacao);?></td>
			</tr>
		
	</tbody>
</table>
<br/>
<br/>
<?php endif;?>

<table class="tabela">
	<thead>
		<tr>
			<th width="15%"><?php echo lang('CODIGO');?></th>
			<th width="15%"><?php echo lang('CODIGO_DE_BARRAS');?></th>
			<th width="60%"><?php echo lang('PRODUTO');?></th>
			<th width="10%"><?php echo lang('QUANT');?></th>
		</tr>
	</thead>
	<tbody>
	<?php 	
		$retorno_row = $cadastro->getProdutosVenda($row->id);
		if($retorno_row):
			foreach ($retorno_row as $exrow):
				$fabricante = getValue("fabricante","fabricante","id=".$exrow->id_fabricante." AND exibir_romaneio = 1");
				$info_produto = $exrow->produto." ". ($exrow->id_fabricante == 0 ? ""  : " - ".$fabricante) ;
				$resposta = $produto->getAtributosProduto($exrow->id_produto);
	?>
		<tr>
			<td width="15%"><?php echo $exrow->codigo;?></td>
			<td width="15%"><?php echo $exrow->codigobarras;?></td>
			<td width="60%">
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
			<td width="10%"><?php echo decimalp($exrow->quantidade);?></td>
		</tr>
	<?php
			endforeach;
	        unset($exrow);
		endif;?>
	</tbody>
</table>