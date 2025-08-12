<?php
 /**
   * PDF - Recibo
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  define('_VALID_PHP', true);
  define('FPDF_FONTPATH', 'fpdf/font/');
  
  require_once("init.php");
  require_once('impressao_recibo.php');
  
  if (!$usuario->is_Todos())
	  redirect_to("login.php");
  
  $id_receita = get('id_receita');
  $pgto_dnhr = get('pgto_dnhr');
  $id_cadastro = get('id_cadastro');
  $row_cadastro = 0;
  $recibo58 = $core->is_recibo58();

  if ($id_receita)
    $row_receita = $faturamento->getDetalhesReceitas($id_receita);
  elseif ($pgto_dnhr) {
    $row_receita = $faturamento->getDetalhesPagamentoDinheiro($pgto_dnhr,$id_cadastro);
    $row_cadastro = Core::getRowById("cadastro", $id_cadastro);
  }

  $id_empresa = ($row_receita->id_empresa) ? $row_receita->id_empresa : 1;
  $row_empresa = Core::getRowById("empresa", $id_empresa);
  $data_pagamento = ($row_receita->data_pagamento == '0000-00-00 00:00:00') ? exibedataHora($row_receita->data_pagamento) : exibedataHora($row_receita->data_pagamento);
  $descricao = $row_receita->descricao;
  $valor = moeda($row_receita->valor);
  $valor_pago = moeda($row_receita->valor_pago);
  $nome_fornecedor = ($id_receita) ? substr($row_receita->cadastro, 0,30) : substr($row_cadastro->nome, 0,30);
  $atendente = $row_receita->usuario;

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

if($recibo58) {
	
$formatorecibo = array(58,280);
$pdf = new PDF_Impressao_Recibo('P', 'mm', $formatorecibo);
//Define as margens esquerda, superior e direita.
$pdf->SetMargins(5, 5, 5);
//define a fonte a ser usada, estilo e tamanho
$pdf->SetFont('arial', '', 6);
//define o titulo
$pdf->SetTitle("Recibo");
//assunto
$pdf->SetSubject($nome_empresa);
// posicao vertical no caso -1.. e o limite da margem
$pdf->SetY("-1");

//EXIBINDO DADOS DA EMPRESA
$pdf->SetFont('arial', 'B', 10);
$pdf->SetX("3");
$pdf->Cell(0, 10, ($nome_empresa));
$pdf->Ln(4);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, ($razao_empresa));
$pdf->Ln(3);
$pdf->SetX("3");
$pdf->Cell(0, 10, ($dados1_empresa));
$pdf->Ln(3);
$pdf->SetX("3");
$pdf->Cell(0, 10, ($dados2_empresa));
$pdf->Ln(3);
$pdf->SetX("3");
$pdf->Cell(0, 10, ($dados3_empresa));
$pdf->Ln(2);
$pdf->SetX("3");
$pdf->Cell(0, 10, ("_____________________________________"));

//EXIBINDO DADOS DA RECEITA
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, (lang('CODIGO')));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->Cell(0, 10, ($id_receita));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, (lang('DATA_VENCIMENTO')));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->Cell(0, 10, (exibedataHora($row_receita->data_pagamento)));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, (lang('DATA_PAGAMENTO')));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->Cell(0, 10, (exibedataHora($row_receita->data_recebido)));
$pdf->Ln(2);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, ("_____________________________________"));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, (lang('NOME')));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->Cell(0, 10, ($nome_fornecedor));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, (lang('VALOR')));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->Cell(0, 10, ($valor));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, (lang('VALOR_PAGO')));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->Cell(0, 10, ($valor_pago));
$pdf->Ln(8);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, (lang('DESCRICAO')));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->MultiCell(0, 4, ($descricao));
$pdf->Ln(10);
$pdf->SetX("3");
$pdf->Cell(0, 10, ("Assinatura:"));
$pdf->Ln(2);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, ("_____________________________________"));

//EXIBINDO OBSERVAÇÕES
$pdf->Ln(5);
$pdf->SetFont('arial', '', 6);
$pdf->SetX("3");
$pdf->Cell(0, 10, (lang('IMPRESSO_EM').$data_impressao." - ".$atendente));

} else {
	
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

//EXIBINDO DADOS DA RECEITA
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('CODIGO').":"));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, ($id_receita));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DATA_VENCIMENTO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, (exibedata($row_receita->data_pagamento)));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DATA_PAGAMENTO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, (exibedata($row_receita->data_recebido)));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DATA_LANCAMENTO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, (exibedataHora($row_receita->data)));
$pdf->Ln(2);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('NOME')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, ($nome_fornecedor));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('VALOR')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, ($valor));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('VALOR_PAGO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, ($valor_pago));
$pdf->Ln(10);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DESCRICAO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->MultiCell(0, 5, ($descricao));
$pdf->Ln(10);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("Assinatura:"));
$pdf->Ln(2);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));

//EXIBINDO OBSERVAÇÕES
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('IMPRESSO_EM').$data_impressao." - ".$atendente));

if ($row_receita->pago) {
  $pdf->Image('assets/img/pago.png',8,35,60,28,'png');
}

}
 
/*
 * IMPRIMIR A SAIDA DO ARQUIVO
 * nome do arquivo
 * I: envia o arquivo diretamente para o browser,
 * Se o plug-in estiver instalado ele serao usado.
 * mais opcoes no final do artigo ou visite o manual fpdf.
 */
 $pdf->AutoPrint(true); 
 $pdf->Output("recibo_receita_".$id_receita, "I");

?>
