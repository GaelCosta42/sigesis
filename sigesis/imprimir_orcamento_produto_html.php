<?php
/**
 * Imprimir Orçamento - Produto
 *
 */
?>
<p style="font-size: 10px; font-weight: bold; text-align: center">
	<?php echo lang('DOCUMENTO_AUX_VENDA_ORCAMENTO'); ?>
</p>
<hr>
<div>&nbsp;</div>

<table>
	<tr>
		<td width="130px" rowspan="1">
			<?php if ($orcamentoVenda->logomarca_pdv) : ?>
				<img src="./uploads/logomarcapdv/<?php echo $orcamentoVenda->logomarca_pdv; ?>" alt="Logo cliente" class="logo-default" height="55">
			<?php endif; ?>
		</td>
		<td style="font-size:10px;">
			<?php
			echo "<strong>$orcamentoVenda->nome_empresa</strong>"
				. "<br>" . formatar_cpf_cnpj($orcamentoVenda->cnpj_empresa)
				. "<br>" . ($orcamentoVenda->endereco_empresa) . ", " . $orcamentoVenda->numero_empresa . ", " . $orcamentoVenda->bairro_empresa
				. "<br>" . ($orcamentoVenda->cep_empresa) . " - " . $orcamentoVenda->cidade_empresa . "/" . $orcamentoVenda->estado_empresa
				. "<br>" . ($orcamentoVenda->telefone_empresa) . " / " . $orcamentoVenda->celular_empresa
			?>
		</td>
	</tr>
</table>

<div>&nbsp;</div>

<table>
	<tr style="font-size:10px; font-weight: bold; ">
		<td colspan="2">
			<?php echo lang('DATA_ORCAMENTO') . ": " . exibedataHora($orcamentoVenda->data_orcamento) ?>
		</td>
		<td colspan="2">
			<?php echo lang('VENDEDOR') . ": " . ($orcamentoVenda->nome_vendedor) ?>
		</td>
		<td>
			<?php echo "Nº " . lang('ORCAMENTO_TITULO') . ": " . ($orcamentoVenda->id_orcamento) ?>
		</td>
	</tr>
</table>


<div>&nbsp;</div>

<!-- Parte cliente -->
<table class="tabela">
	<tr>
		<th colspan="6" style="text-align:center;font-size:10px;"><strong><?php echo lang('SOLICITANTE') ?></strong></th>
	</tr>
	<tr style="font-size: 8px;">
		<td><?php echo lang('CLIENTE'); ?></td>
		<td colspan=""><strong><?php echo $orcamentoVenda->nome_cliente; ?></strong></td>
		<td><?php echo lang('CELULAR'); ?></td>
		<td><strong><?php echo $orcamentoVenda->celular_cliente; ?></strong></td>
		<td><?php echo lang('TELEFONE'); ?></td>
		<td><strong><?php echo $orcamentoVenda->telefone_cliente; ?></strong></td>
	</tr>
	<tr style="font-size: 8px;">
		<td><?php echo lang('CPF_CNPJ'); ?></td>
		<td><strong><?php echo $orcamentoVenda->cpf_cnpj_cliente; ?></strong></td>
		<td><?php echo lang('CEP'); ?></td>
		<td><strong><?php echo $orcamentoVenda->cep_cliente; ?></strong></td>
		<td><?php echo lang('CIDADE'); ?></td>
		<td><strong><?php echo $orcamentoVenda->cidade_cliente; ?></strong></td>
	</tr>
	<tr style="font-size: 8px;">
		<td><?php echo lang('EMAIL'); ?></td>
		<td colspan="2"><strong><?php echo $orcamentoVenda->email_cliente; ?></strong></td>
		<td><?php echo lang('ENDERECO'); ?></td>
		<td colspan="3" ><strong><?php echo $orcamentoVenda->endereco_cliente . ", " . $orcamentoVenda->numero_cliente . " - " . $orcamentoVenda->bairro_cliente; ?></strong></td>
	</tr>
</table>

<?php
$produtos = $cadastro->getOrcamentoById($id_orcamento);
if ($produtos) :
	$item = 0;
	$valor_total_produtos = 0;
?>
	<table class="tabela">
		<thead>
			<tr>
				<th colspan="6" style="text-align:center; font-size:10px;"><strong><?php echo lang('PRODUTOS_MAIUSCULO') ?></strong></th>
			</tr>
			<tr style="font-size:9px;">
				<th width="30px">#</th>
				<th width="80px"><?php echo lang('COD_PRODUTO'); ?></th>
				<th width="260px"><?php echo lang('ITEM'); ?></th>
				<th width="40px"><?php echo lang('QUANT'); ?></th>
				<th width="67px"><?php echo lang('UNIDADE_ABREV_MEDIDA'); ?></th>
				<th width="80px"><?php echo lang('VALOR_UNITARIO'); ?></th>
				<th width="80px"><?php echo lang('VALOR_TOTAL'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			foreach ($produtos as $exrow) :
				$valor_total_produtos += $exrow->valor_total;
				$fabricante = getValue("fabricante", "fabricante", "id=" . $exrow->id_fabricante . " AND exibir_romaneio = 1");
				$info_produtos = $exrow->nome . " " . ($exrow->id_fabricante == 0 ? ""  : " - " . $fabricante);
				$resposta = $produto->getAtributosProduto($exrow->id_produto);
				$count++;
			?>
				<tr style="font-size: 8px">
					<td width="30px"><?php echo $count; ?></td>
					<td width="80px"><?php echo ($exrow->id_produto); ?></td>
					<td width="260px">
						<?php

						echo $info_produtos;
						if ($resposta) {
							foreach ($resposta as $res) {
								echo " <br>- " . $res->atributo . ": " . $res->descricao;
							}
						}

						?>
					</td>
					<td width="40px">
						<?php echo decimalp($exrow->quantidade); ?>
					</td>
					<td width="67px">
						<?php echo ($exrow->unidade); ?>
					</td>
					<td width="80px">
						<?php echo moeda($exrow->valor); ?>
					</td>
					<td width="80px">
						<?php echo moeda($exrow->valor_total); ?>
					</td>
				</tr>
			<?php
			endforeach;
			unset($exrow);
			?>
		</tbody>
		<tfoot>
			<tr style="font-size: 9px">
				<th colspan="6"><?php echo lang('TOTAL'); ?></th>
				<td>
					<strong>
						<?php echo moeda($valor_total_produtos); ?>
					</strong>
				</td>
			</tr>
		</tfoot>
	</table>
<?php
endif;
?>

<table class="tabela">
	<tr style="font-size: 9px">
		<th colspan="1"><?php echo lang('SUBTOTAL') . ": " . moeda($valor_total_produtos); ?></th>
		<th colspan="1"><?php echo lang('DESCONTO_M') . ": " . moeda($orcamentoVenda->valor_desconto); ?></th>
		<th colspan="1"><?php echo lang('ACRESCIMO_MAIUSCULO') . ": " . moeda($orcamentoVenda->valor_despesa_acessoria); ?></th>
		<th colspan="1"><?php echo lang('TOTAL_MAIUSCULO') . ": " . moeda($orcamentoVenda->valor_pago); ?></th>
	</tr>
</table>

<table class="tabela">
	<thead>
		<tr>
			<th colspan="5" style="text-align:center; font-size:10px;"><?php echo lang('OBSERVACOES_MAIUSCULO') ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="5">
				<?php echo $orcamentoVenda->observacao; ?>
				<br>
				<br>
				<?php
				$payments = $cadastro->ObterFormasPagamentoVenda($orcamentoVenda->id_orcamento);
				if ($payments) {
					echo "Forma de pagamento: <br>";
					foreach ($payments as $payment) {
						echo $payment->tipo_pagamento . "<br>";
					}
				}
				?>
			</td>
		</tr>
	</tbody>
</table>

<div>&nbsp;</div>

<table style="font-size: 10px">
	<tr>
		<td style="text-align:center;"><?php echo '_________/_________/_________'; ?></td>
		<td style="text-align:center;"><?php echo '_____________________________'; ?></td>
		<td style="text-align:center;"><?php echo '_____________________________'; ?></td>
	</tr>
	<tr>
		<td style="text-align:center;"><?php echo "<strong>DATA</strong>"; ?></td>
		<td style="text-align:center;"><?php echo "<strong>" . $orcamentoVenda->nome_cliente . "</strong>"; ?></td>
		<td style="text-align:center;"><?php echo "<strong>" . $orcamentoVenda->nome_empresa . "</strong>"; ?></td>
	</tr>
</table>