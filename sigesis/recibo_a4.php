<?php
 /**
   * PDF - Recibo
   *
   */
  define('_VALID_PHP', true);
  define('FPDF_FONTPATH', 'fpdf/font/');

  require_once("init.php");
  require_once('impressao_recibo.php');

  //if (!$usuario->is_Todos())
	  //redirect_to("login.php");

  $id = get('id');
  $crediario = get('crediario');
  $recibo58 = $core->is_recibo58();
  $row_venda = Core::getRowById("vendas", $id);
  $id_empresa = ($row_venda->id_empresa) ? $row_venda->id_empresa : 1;
  $row_empresa = Core::getRowById("empresa", $id_empresa);
  $data_venda = exibedataHora($row_venda->data_venda);
  $nome_cliente = "";
  $razao_cliente = "";
  $cpfcnpj_cliente = "";
  $origem = "";
  $telefone_cliente = "";
  $valor_pagar = 0;
  if($row_venda->id_cadastro) {
	$row = Core::getRowById("cadastro", $row_venda->id_cadastro);

	$nome_cliente = $row->nome;
	$razao_cliente = $row->razao_social;
	$cpfcnpj_cliente = $row->cpf_cnpj;
	$telefone_cliente = $row->telefone ? $row->telefone : $row->celular;
	$origem = ($row->id_origem) ? getValue("origem", "origem", "id=" . $row->id_origem) : "";
  }
  if($row_venda->id_vendedor) {
	$row_usuario = Core::getRowById("usuario", $row_venda->id_vendedor);
	$nome_vendedor = $row_usuario->nome;
  }

  $voucher_crediario = ($row_venda->voucher_crediario > 0) ? $row_venda->voucher_crediario : 0;
  $data_venda = exibedataHora($row_venda->data_venda);
  $valor = moeda($row_venda->valor_total);
  $desconto = $row_venda->valor_desconto-$row_venda->valor_troca;
  $troca = $row_venda->valor_troca;
  $acrescimo = $row_venda->valor_despesa_acessoria;
  $valor_desconto = ($desconto);
  $valor_troca = moeda($troca);
  $valor_acrescimo = moeda($acrescimo);
  $valor_pago = moeda($row_venda->valor_pago);
  $atendente = strtoupper(session('nomeusuario'));
  $troco = moeda($row_venda->troco);

  $nome_empresa = $row_empresa->nome;
  $razao_empresa = $row_empresa->razao_social;
  $dados1_empresa = $row_empresa->endereco.", ".$row_empresa->numero.", ".$dados2_empresa = $row_empresa->bairro." - ".$row_empresa->cidade." - ".$row_empresa->estado;
  $dados2_empresa = ($row_empresa->telefone) ? $row_empresa->telefone." - ".formatar_cpf_cnpj($row_empresa->cnpj) : formatar_cpf_cnpj($row_empresa->cnpj);

  $data_impressao = date('d/m/Y H:i:s');

/*
 * construtor da classe, que permite que seja definido o formato da pagina
 * P=Retrato, mm =tipo de medida utilizada no casso milimetros,
 * tipo de folha = 80 x 80 mm
 * tipo de folha = 58 x 80 mm (quando o ID_ORIGEM (configurações) igual a 58)
 */

$formatorecibo = array(80,280);
$pdf = new PDF_Impressao_Recibo('P', 'mm');
//Define as margens esquerda, superior e direita.
$pdf->SetMargins(5, 5, 5);
//define a fonte a ser usada, estilo e tamanho
$pdf->SetFont('arial', '', 10);
//define o titulo
$pdf->SetTitle("Recibo");
//assunto
$pdf->SetSubject($nome_empresa);
// posicao vertical no caso -1.. e o limite da margem
$pdf->SetY("-1");

//EXIBINDO DADOS DA EMPRESA
$pdf->SetFont('arial', 'B', 11);
$pdf->SetX("5");
$pdf->Cell(0, 10, ($nome_empresa.' ('.$razao_empresa.')'));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");

$pdf->Ln(4);
$pdf->SetX("5");
$pdf->Cell(0, 10, $dados1_empresa);

$pdf->Ln(4);
$pdf->SetX("5");
$pdf->Cell(0, 10, $dados2_empresa);

$pdf->Ln(2);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("________________________________________________________________________________________________________________"));

//EXIBINDO DADOS DA VENDA
$base_center = 50;
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX($base_center+5);
$pdf->Cell(0, 10, mb_convert_encoding("Código da venda:", 'UTF-8'));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX($base_center+35);
$pdf->Cell(0, 10, ($id));
$pdf->SetFont('arial', '', 9);
$pdf->SetX($base_center+60);
$pdf->Cell(0, 10, (lang('DATA').":"));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX($base_center+70);
$pdf->Cell(0, 10, ($data_venda));
$pdf->Ln(2);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("________________________________________________________________________________________________________________"));

//EXIBINDO DADOS DO CLIENTE
if($row_venda->id_cadastro) {
	$pdf->Ln(5);
	$pdf->SetFont('arial', '', 8);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, (strtoupper(lang('NOME').":")));
	$pdf->SetFont('arial', 'B', 8);
	$pdf->SetX("16");
	$pdf->Cell(0, 10, $nome_cliente);

	if($razao_cliente) {
		$pdf->Ln(4);
		$pdf->SetFont('arial', '', 8);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (mb_strtoupper(lang('RAZAO_SOCIAL').":")));
		$pdf->SetX("28");
		$pdf->Cell(0, 10, mb_strtoupper(($razao_cliente ?? '')));
	}

	$pdf->Ln(4);
	$base_posicao = 5;

	if($cpfcnpj_cliente) {
		$pdf->SetFont('arial', '', 8);
		$pdf->SetX($base_posicao);
		$pdf->Cell(0, 10, (strtoupper(lang('CPF_CNPJ').":")));
		$pdf->SetX($base_posicao+16);
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(0, 10, ($cpfcnpj_cliente ?? ''));

		$base_posicao += 50 + strlen($cpfcnpj_cliente);
	}

	if($telefone_cliente) {
		$pdf->SetFont('arial', '', 8);
		$pdf->SetX($base_posicao);
		$pdf->Cell(0, 10, (strtoupper(lang('TELEFONE').":")));
		$base_posicao += 17;
		$pdf->SetX($base_posicao);
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(0, 10, ($telefone_cliente ?? ''));
	}

	$pdf->Ln(4);

	if($row_venda->endereco) {
		$endereco_cliente = "";
		$endereco_cliente .= ($row_venda->endereco) ? $row_venda->endereco : "";
		$endereco_cliente .= ($row_venda->complemento)? ", ".$row_venda->complemento : "";
		$endereco_cliente .= ($row_venda->bairro)? ", ".lang('BAIRRO').": ".$row_venda->bairro : "";
		$endereco_cliente .= ($row_venda->cidade)? " - ".$row_venda->cidade : "";
		$endereco_cliente .= ($row_venda->referencia)? ", ".lang('REFERENCIA').": ".$row_venda->referencia : "";

		$pdf->SetFont('arial', '', 8);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (mb_strtoupper(lang('ENDERECO').":")));
		$pdf->SetX("23");
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(0, 10, (mb_strtoupper($endereco_cliente)));
	} else {
		$endereco_cliente = "";
		$endereco_cliente .= ($row->endereco) ? $row->endereco : "";
		$endereco_cliente .= ($row->numero)? ", ".$row->numero : "";
		$endereco_cliente .= ($row->complemento)? ", ".$row->complemento : "";
		$endereco_cliente .= ($row->bairro)? ", ".lang('BAIRRO').": ".$row->bairro : "";
		$endereco_cliente .= ($row->cidade)? " - ".$row->cidade : "";

		if($row->endereco) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (mb_strtoupper(lang('ENDERECO').":")));
			$pdf->SetX("23");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, (mb_strtoupper($endereco_cliente)));
		}
	}

	if($origem != "") {
		$pdf->Ln(4);
		$pdf->SetFont('arial', '', 8);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (strtoupper(lang('ORIGEM').":")));
		$pdf->SetFont('arial', 'B', 8);
		$pdf->SetX("18");
		$pdf->Cell(0, 10, (strtoupper($origem)));
	}

	if ($crediario) {
		//EXIBINDO DETALHAMENTO DA VENDA
		$valor_crediario = $cadastro->getTotalCrediario($row->id);
		$valor_pagar = $cadastro->getPagarCrediario($row->id);
		$pdf->Ln(5);
		$pdf->SetFont('arial', 'B', 8);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (lang('DETALHAMENTO_CREDIARIO')));
		$pdf->Ln(3);
		$pdf->SetFont('arial', '', 7);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (strtoupper(lang('SALDO').":")));
		$pdf->SetX("17");
		$pdf->SetFont('arial', 'B', 7);
		$pdf->Cell(0, 10, (moeda($valor_crediario)));
		$pdf->Ln(3);
		$pdf->SetFont('arial', '', 7);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (strtoupper(lang('SALDOAPAGAR').":")));
		$pdf->SetX("17");
		$pdf->SetFont('arial', 'B', 7);
		$pdf->Cell(0, 10, (moeda($valor_pagar)));
	}

	$pdf->Ln(2);
	$pdf->SetFont('arial', '', 9);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, ("________________________________________________________________________________________________________________"));

}

if ($row_venda->observacao){
	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B', 8);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, (lang('OBSERVACAO')));
	$pdf->SetFont('arial', '', 7);
	$pdf->Ln(4);
	$pdf->SetX("5");
	$observacao_venda = (strlen($row_venda->observacao)>150) ? substr($row_venda->observacao,0,150) : $row_venda->observacao ;
	$pdf->Cell(0, 10, ($observacao_venda));
	if (strlen($row_venda->observacao)>150){
		$pdf->SetFont('arial', '', 7);
		$pdf->Ln(4);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (substr($row_venda->observacao,150,150)));
	}
	if (strlen($row_venda->observacao)>300){
		$pdf->SetFont('arial', '', 7);
		$pdf->Ln(4);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (substr($row_venda->observacao,300,150)));
	}
	if (strlen($row_venda->observacao)>450){
		$pdf->SetFont('arial', '', 7);
		$pdf->Ln(4);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (substr($row_venda->observacao,450,150)));
	}
	if (strlen($row_venda->observacao)>600){
		$pdf->SetFont('arial', '', 7);
		$pdf->Ln(4);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (substr($row_venda->observacao,600,150)));
	}

	$pdf->Ln(2);
	$pdf->SetFont('arial', '', 9);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, ("________________________________________________________________________________________________________________"));

}

//EXIBINDO DETALHAMENTO DA VENDA
$pdf->Ln(5);
$pdf->SetFont('arial', 'B', 8);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('ITEM')));
$pdf->SetX("13");
$pdf->Cell(0, 10, (lang('CODIGO')));
$pdf->SetX("30");
$pdf->Cell(0, 10, (lang('DESCRICAO')));
$pdf->SetX("153");
$pdf->Cell(0, 10, (lang('QUANTIDADE')));
$pdf->SetX("170");
$pdf->Cell(0, 10, (lang('VL_UNITARIO')));
$pdf->SetX("188");
$pdf->Cell(0, 10, (lang('VL_TOTAL')));
$pdf->Ln(2);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("________________________________________________________________________________________________________________"));
$pdf->SetFont('arial', '', 7);
$pdf->Ln(5);
$contar = 0;
$total = 0;
$retorno_row = $cadastro->getItemVenda($id);
if($retorno_row){
	foreach ($retorno_row as $exrow) {
		$contar++;
		$item = str_pad($contar, 3, '0', STR_PAD_LEFT);
		$valor_total = round($exrow->quantidade*$exrow->valor,2);
		$total += $valor_total;
		$pdf->SetX("5");
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(0, 10, ($item.'      '.substr($exrow->codigo, 0,11)));
		$pdf->SetX("30");
		$produto = (strlen($exrow->produto)>82) ? substr($exrow->produto, 0,82) : $exrow->produto;
		$pdf->Cell(0, 10, $produto);
		$pdf->SetX("153");
		$pdf->Cell(0, 10, (decimalp($exrow->quantidade)));
		$pdf->SetX("166");
		$pdf->Cell(0, 10, ("X   ".moedap($exrow->valor)));
		$pdf->SetFont('arial', 'B', 8);
		$pdf->SetX("187");
		$pdf->Cell(0, 10, (moedap($valor_total)));
		$pdf->SetFont('arial', '', 9);
		$pdf->Ln(1);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, ("________________________________________________________________________________________________________________"));
		$pdf->Ln(4);
	}
	$total = round($total,2);
	unset($exrow);
}
$pdf->Ln(5);
$pdf->SetX("10");
$pdf->SetFont('arial', '', 9);
$pdf->Cell(0, 10, (strtoupper(lang('VALOR').":")));
$pdf->SetX("50");
$pdf->SetFont('arial', 'B', 9);
$pdf->Cell(0, 10, ($valor));
$pdf->Ln(5);
$pdf->SetX("10");
$pdf->SetFont('arial', '', 9);
$pdf->Cell(0, 10, (strtoupper(lang('VALOR_DESCONTO').":")));
$pdf->SetX("50");
$pdf->SetFont('arial', 'B', 9);
$pdf->Cell(0, 10, moeda($valor_desconto + $voucher_crediario));
if ($row_venda->valor_troca > 0):
	$pdf->Ln(5);
	$pdf->SetX("10");
	$pdf->SetFont('arial', '', 9);
	$pdf->Cell(0, 10, (strtoupper(lang('VALOR_TROCA_TITULO').":")));
	$pdf->SetX("50");
	$pdf->SetFont('arial', 'B', 9);
	$pdf->Cell(0, 10, ($valor_troca));
endif;
$pdf->Ln(5);
$pdf->SetX("10");
$pdf->SetFont('arial', '', 9);
$pdf->Cell(0, 10, (strtoupper(lang('VALOR_ACRESCIMO_TITULO').":")));
$pdf->SetX("50");
$pdf->SetFont('arial', 'B', 9);
$pdf->Cell(0, 10, ($valor_acrescimo));

if($voucher_crediario > 0){
	$pdf->Ln(5);
	$pdf->SetX("10");
	$pdf->SetFont('arial', '', 9);
	$pdf->Cell(0, 10, (strtoupper(lang('SALDO_CREDIARIO').":")));
	$pdf->SetX("50");
	$pdf->SetFont('arial', 'B', 9);
	$pdf->Cell(0, 10, (moeda($voucher_crediario)));
} else {
	$pdf->Ln(5);
	$pdf->SetX("10");
	$pdf->SetFont('arial', '', 9);
	$pdf->Cell(0, 10, (strtoupper(lang('VALOR_TOTAL').":")));
	$pdf->SetX("50");
	$pdf->SetFont('arial', 'B', 9);
	$pdf->Cell(0, 10, (moeda((($total+$acrescimo)-$desconto)-$troca)));
}

$pdf->Ln(5);
$pdf->SetX("10");
$pdf->SetFont('arial', '', 9);
$pdf->Cell(0, 10, (strtoupper(lang('VALOR_PAGO').":")));
$pdf->SetX("50");
$pdf->SetFont('arial', 'B', 9);
$pdf->Cell(0, 10, ($valor_pago));

if($troco) {
	$pdf->Ln(5);
	$pdf->SetFont('arial', '', 9);
	$pdf->SetX("10");
	$pdf->Cell(0, 10, (strtoupper(lang('TROCO').":")));
	$pdf->SetX("50");
	$pdf->SetFont('arial', 'B', 9);
	$pdf->Cell(0, 10, ($troco));
	$pdf->Ln(3);
}

$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("________________________________________________________________________________________________________________"));

//EXIBINDO DETALHAMENTO DO PAGAMENTO
$pdf->Ln(5);
$pdf->SetFont('arial', 'B', 8);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DETALHAMENTO_PAGAMENTO')));
$pdf->SetFont('arial', '', 7);
$pdf->Ln(5);
$retorno_row = $cadastro->getFinanceiroVenda($id);
if($retorno_row){
	foreach ($retorno_row as $exrow) {
		$pag = substr(pagamento($exrow->pagamento), 0,30);
		$pdf->SetX("10");
		$pdf->Cell(0, 10, ($pag.":"));
		$pdf->SetX("50");
		$pdf->Cell(0, 10, (moeda($exrow->valor_pago)));
		if($exrow->total_parcelas >= 1) {
			$pdf->Ln(3);
			$pdf->SetX("10");
			$pdf->Cell(0, 10, (strtoupper(lang('PARCELAS_CARTAO').": (").$exrow->total_parcelas."x)"));
			$descricaoParcelas = $cadastro->getFinanceiroPagamentoVendaParcelas($exrow->id_financeiro);
			if ($descricaoParcelas) {
				foreach($descricaoParcelas as $drow) {
					if ($drow->id_categoria == 4 || $drow->id_categoria == 9) {
						$pdf->SetX("30");
						$parcelaPaga = ($drow->pago==1) ? " - pg" : "";
						$pdf->Cell(2, 10, ($drow->parcela).": ".(moeda(($drow->valor) ?? 1))." - ".exibeData($drow->data_pagamento).$parcelaPaga);
						$pdf->Ln(3);
					}
				}
			}
		}
		$pdf->Ln(4);
	}
	unset($exrow);
}

if($row_venda->id_vendedor) {
	$pdf->Ln(6);
	$pdf->SetFont('arial', '', 7);
	$pdf->SetX("10");
	$pdf->Cell(0, 10, (strtoupper(lang('VENDEDOR').":")));
	$pdf->SetX("26");
	$pdf->SetFont('arial', 'B', 7);
	$pdf->Cell(0, 10, ($nome_vendedor));
	$pdf->Ln(2);
}

$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("________________________________________________________________________________________________________________"));

//EXIBINDO OBSERVAÇÕES
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('IMPRESSO_EM').$data_impressao." - ".$atendente));

$pdf->Ln(4);
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("5");
$pdf->Cell(0, 10, "SIGESIS - Sistemas de Gestão");

$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("5");
$pdf->Cell(0, 10, "Recibo - Sem valor Fiscal");

/*
 * IMPRIMIR A SAIDA DO ARQUIVO
 * nome do arquivo
 * I: envia o arquivo diretamente para o browser,
 * Se o plug-in estiver instalado ele serao usado.
 * mais opcoes no final do artigo ou visite o manual fpdf.
 */
 $pdf->AutoPrint(true);
 $pdf->Output("recibo_a4_".$id, "I");

?>
