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
  $origem = "";
  $telefone_cliente = "";
  $valor_pagar = 0;
	if($row_venda->id_cadastro) {
		$row = Core::getRowById("cadastro", $row_venda->id_cadastro);

		$nome_cliente = $row->nome;
		$telefone_cliente = $row->telefone ? $row->telefone : $row->celular;
		$origem = ($row->id_origem) ? getValue("origem", "origem", "id=" . $row->id_origem) : "";
	}
  	if($row_venda->id_vendedor) {
		$row_usuario = Core::getRowById("usuario", $row_venda->id_vendedor);
		$nome_vendedor = $row_usuario->nome;
	}
  $data_venda = exibedataHora($row_venda->data_venda);
  $valor = moeda($row_venda->valor_total);
  $desconto = $row_venda->valor_desconto;
  $acrescimo = $row_venda->valor_despesa_acessoria;
  $valor_desconto = moeda($row_venda->valor_desconto);
  $valor_acrescimo = moeda($row_venda->valor_despesa_acessoria);
  $valor_pago = moeda($row_venda->valor_pago);
  $atendente = $row_venda->usuario;
  $troco = moeda($row_venda->troco);
  $prazo_entrega = exibedata($row_venda->prazo_entrega);
  
  $nome_empresa = $core->empresa;
  $razao_empresa = $row_empresa->nome;
  $dados1_empresa = $row_empresa->endereco.", ".$row_empresa->numero;
  $dados2_empresa = $row_empresa->bairro." - ".$row_empresa->cidade." - ".$row_empresa->estado;
  $dados3_empresa = $row_empresa->telefone." - ".formatar_cpf_cnpj($row_empresa->cnpj);
  
  $data_impressao = date('d/m/Y H:i:s');

/*
 * construtor da classe, que permite que seja definido o formato da pagina
 * P=Retrato, mm =tipo de medida utilizada no casso milimetros,
 * tipo de folha = 80 x 80 mm
 * tipo de folha = 58 x 80 mm (quando o ID_ORIGEM (configurações) igual a 58)
 */
	
$formatorecibo = array(80,280);
$pdf = new PDF_Impressao_Recibo('P', 'mm', $formatorecibo);
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
$pdf->Cell(0, 10, ($nome_empresa));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ($razao_empresa));
$pdf->Ln(4);
$pdf->SetX("5");
$pdf->Cell(0, 10, ($dados1_empresa));
$pdf->Ln(4);
$pdf->SetX("5");
$pdf->Cell(0, 10, ($dados2_empresa));
$pdf->Ln(4);
$pdf->SetX("5");
$pdf->Cell(0, 10, ($dados3_empresa));
$pdf->Ln(2);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));

//EXIBINDO DADOS DA VENDA

if($row_venda->orcamento == 0){
	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B', 9);
	$pdf->SetX("20");
	$pdf->Cell(0, 10, mb_convert_encoding(lang('VENDA_ABERTO'), 'UTF-8'));
	$pdf->Ln(2);
}else{
	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B', 9);
	$pdf->SetX("25");
	$pdf->Cell(0, 10, mb_convert_encoding(lang('ORCAMENTO'), 'UTF-8'));
	$pdf->Ln(2);
}

$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));

//EXIBINDO DADOS DA VENDA
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('CODIGO').":"));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("17");
$pdf->Cell(0, 10, ($id));
$pdf->SetFont('arial', '', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, (lang('DATA').":"));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("44");
$pdf->Cell(0, 10, ($data_venda));

if($prazo_entrega != "-"){
	$pdf->Ln(5);
	$pdf->SetFont('arial', '', 9);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, (lang('PRAZO_ENTREGA').":"));
	$pdf->SetFont('arial', 'B', 9);
	$pdf->SetX("35");
	$pdf->Cell(0, 10, ($prazo_entrega));
}
	
$pdf->Ln(2);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));

//EXIBINDO DADOS DO CLIENTE
if($row_venda->id_cadastro) {
	$pdf->Ln(5);
	$pdf->SetFont('arial', '', 8);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, (strtoupper(lang('NOME').":")));
	$pdf->SetX("15");
	$pdf->SetFont('arial', 'B', 8);
	$pdf->Cell(0, 10, ($nome_cliente));
	$pdf->Ln(4);

	if($telefone_cliente) {
		$pdf->SetFont('arial', '', 8);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (strtoupper(lang('TELEFONE').":")));
		$pdf->SetX("22");
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(0, 10, ($telefone_cliente ?? ''));
		$pdf->Ln(4);
	}

	if($row_venda->endereco){

		if( $row_venda->endereco){
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (mb_strtoupper(lang('ENDERECO').":")));
			$pdf->SetX("22");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row_venda->endereco ?? ''));
			$pdf->Ln(4);
		}

		if($row_venda->complemento ){
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('COMPLEMENTO').":")));
			$pdf->SetX("29");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row_venda->complemento ?? ''));
			$pdf->Ln(4);
		}

		if($row_venda->bairro ){
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('BAIRRO').":")));
			$pdf->SetX("17");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row_venda->bairro ?? ''));
			$pdf->Ln(4);
		}

		if($row_venda->cidade ) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('CIDADE').":")));
			$pdf->SetX("17");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row_venda->cidade ?? ''));
			$pdf->Ln(4);
		}

		if($row_venda->referencia ) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('REFERENCIA').":")));
			$pdf->SetX("24");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row_venda->referencia ?? ''));
			$pdf->Ln(4);
		}
	} else {
	
		if($row->endereco) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (mb_strtoupper(lang('ENDERECO').":")));
			$pdf->SetX("22");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row->endereco ?? ''));
			$pdf->Ln(4);
		}
			
		if($row->numero) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (mb_strtoupper(lang('NUMERO').":")));
			$pdf->SetX("20");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row->numero ?? ''));
			$pdf->Ln(4);
		} 
	
		if($row->complemento) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('COMPLEMENTO').":")));
			$pdf->SetX("29");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row->complemento ?? ''));
			$pdf->Ln(4);
		}
	
		if($row->bairro) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('BAIRRO').":")));
			$pdf->SetX("17");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row->bairro ?? ''));
			$pdf->Ln(4);
		}
	
		if($row->cidade) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('CIDADE').":")));
			$pdf->SetX("17");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row->cidade ?? ''));
			$pdf->Ln(4);
		}
	}

	
	if($origem != "") {
		$pdf->Ln(2);
		$pdf->SetX("30");
		$pdf->Cell(0, 10, (strtoupper($origem)));
		$pdf->Ln(4);
	}

	$pdf->SetFont('arial', '', 9);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, ("_____________________________________"));

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

		$pdf->SetFont('arial', '', 9);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, ("_____________________________________"));
	}
	
}

if ($row_venda->observacao){
	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B', 8);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, (lang('OBSERVACAO')));
	$pdf->SetFont('arial', '', 7);
	$pdf->Ln(4);
	$pdf->SetX("5");
	$observacao_venda = (strlen($row_venda->observacao)>49) ? substr($row_venda->observacao,0,47) : $row_venda->observacao ;
	$pdf->Cell(0, 10, ($observacao_venda));	
	if (strlen($row_venda->observacao)>49){
		$pdf->SetFont('arial', '', 7);
		$pdf->Ln(4);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (substr($row_venda->observacao,47)));
	}

	$pdf->Ln(2);
	$pdf->SetFont('arial', '', 9);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, ("_____________________________________"));
}

//EXIBINDO DETALHAMENTO DA VENDA
$pdf->Ln(7);
$pdf->SetFont('arial', 'B', 8);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DETALHAMENTO_ITENS')));
$pdf->SetFont('arial', '', 7);
$pdf->Ln(5);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('ITEM')));
$pdf->SetX("15");
$pdf->Cell(0, 10, (lang('CODIGO')));
$pdf->Cell(-5, 10, (lang('DESCRICAO')), 0, 0, 'R');
$pdf->Ln(3);
$pdf->SetX("10");
$pdf->Cell(0, 10, (lang('QUANTIDADE')));
$pdf->SetX("30");
$pdf->Cell(0, 10, (lang('VL_UNITARIO')));
$pdf->Cell(-5, 10, (lang('VL_TOTAL')), 0, 0, 'R');
$pdf->Ln(2);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));
$pdf->SetFont('arial', '', 7);
$pdf->Ln(5);
$contar = 0;
$total = 0;
$retorno_row = $cadastro->getItemVenda($id);
if($retorno_row){
	foreach ($retorno_row as $exrow) {
		$contar++;
		$item = str_pad($contar, 3, '0', STR_PAD_LEFT);	
		$prod = substr($exrow->produto, 0,30);
		$valor_total = round($exrow->quantidade*$exrow->valor,2);
		$total += $valor_total;
		$pdf->SetX("5");
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(0, 10, ($item.'        '.$exrow->codigo));
		$pdf->SetX("55");
		$pdf->Cell(0, 10, ($prod), 0, 0, 'R');
		if (strlen($exrow->produto)>30){
			$pdf->Ln(4);
			$pdf->SetX("25.5");
			$pdf->Cell(0, 10, (substr($exrow->produto, 30,30)), 0, 0,'L');
		}
		$pdf->Ln(4);
		$pdf->SetX("10");
		$pdf->Cell(0, 10, (decimalp($exrow->quantidade)));
		$pdf->SetX("25");
		$pdf->Cell(0, 10, ("  X    ".moedap($exrow->valor)));
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(-5, 10, (moedap($valor_total)), 0, 0, 'R');
		$pdf->Ln(5);
	}
	$total = round($total,2);
	unset($exrow);
}
$pdf->Ln(4);
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
$pdf->Cell(0, 10, ($valor_desconto));
$pdf->Ln(5);
$pdf->SetX("10");
$pdf->SetFont('arial', '', 9);
$pdf->Cell(0, 10, (strtoupper(lang('VALOR_ACRESCIMO_TITULO').":")));
$pdf->SetX("50");
$pdf->SetFont('arial', 'B', 9);
$pdf->Cell(0, 10, ($valor_acrescimo));
$pdf->Ln(5);
$pdf->SetX("10");
$pdf->SetFont('arial', 'B', 9);
$pdf->Cell(0, 10, (strtoupper(lang('VALOR_TOTAL').":")));
$pdf->SetX("50");
$pdf->SetFont('arial', 'B', 9);
$pdf->Cell(0, 10, (moeda($total+$acrescimo-$desconto)));
$pdf->Ln(5);

$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));

//EXIBINDO VENDEDOR
if($row_venda->id_vendedor) {
	$pdf->Ln(5);
	$pdf->SetFont('arial', '', 7);
	$pdf->SetX("10");
	$pdf->Cell(0, 10, (strtoupper(lang('VENDEDOR').":")));
	$pdf->SetX("40");
	$pdf->SetFont('arial', 'B', 7);
	$pdf->Cell(0, 10, ($nome_vendedor));
	$pdf->Ln(2);

$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));

}

//EXIBINDO OBSERVAÇÕES
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('IMPRESSO_EM').$data_impressao." - ".$atendente));

 
/*
 * IMPRIMIR A SAIDA DO ARQUIVO
 * nome do arquivo
 * I: envia o arquivo diretamente para o browser,
 * Se o plug-in estiver instalado ele serao usado.
 * mais opcoes no final do artigo ou visite o manual fpdf.
 */
$pdf->AutoPrint(true); 

	ob_end_clean();
 	$pdf->Output("recibo_".$id, "I");

?>
