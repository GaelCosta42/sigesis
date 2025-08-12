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
  
  $id_despesa = get('id_despesa');
  $recibo58 = $core->is_recibo58();
  $row_despesa = $despesa->getDetalhesDespesa($id_despesa);
  $id_empresa = ($row_despesa->id_empresa) ? $row_despesa->id_empresa : 1;
  $row_empresa = Core::getRowById("empresa", $id_empresa);
  $data_pagamento = ($row_despesa->data_pagamento == '0000-00-00 00:00:00') ? exibedataHora($row_despesa->data_vencimento) : exibedataHora($row_despesa->data_pagamento);
  $nome_fornecedor = substr($row_despesa->cadastro, 0,30);
  $descricao = $row_despesa->descricao;
  $valor_pago = moeda($row_despesa->valor);
  $usuarioPgto = $row_despesa->usuario;
  $atendente = session('nomeusuario');
  
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
$pdf->Cell(0, 10, mb_convert_encoding(lang('CODIGO'),'UTF-8'));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->Cell(0, 10, ($id_despesa));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, (lang('DATA_VENCIMENTO')));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->Cell(0, 10, (exibedataHora($row_despesa->data_vencimento)));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, (lang('DATA_PAGAMENTO')));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->Cell(0, 10, (exibedataHora($row_despesa->data_pagamento)));
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
$pdf->Cell(0, 10, (lang('VALOR_PAGO')));
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("25");
$pdf->Cell(0, 10, ($valor_pago));
$pdf->Ln(8);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("3");
$pdf->Cell(0, 10, mb_convert_encoding(lang('DESCRICAO'),'UTF-8'));
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
$pdf->SetFont('arial', '', 7);
$pdf->SetX("5");
$pdf->Cell(0, 10, "Usuário pagamanto: ".$usuarioPgto);
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("5");
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
$pdf->Cell(0, 10, mb_convert_encoding(lang('CODIGO').":",'UTF-8'));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, ($id_despesa));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DATA_VENCIMENTO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, (exibedataHora($row_despesa->data_vencimento)));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DATA_PAGAMENTO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, (exibedataHora($row_despesa->data_pagamento)));
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
$pdf->Cell(0, 10, (lang('VALOR_PAGO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, ($valor_pago));
$pdf->Ln(10);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, mb_convert_encoding(lang('DESCRICAO'),'UTF-8'));
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
$pdf->Cell(0, 10, "Usuário pagamanto: ".$usuarioPgto);
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('IMPRESSO_EM').$data_impressao." - ".$atendente));

}
 
/*
 * IMPRIMIR A SAIDA DO ARQUIVO
 * nome do arquivo
 * I: envia o arquivo diretamente para o browser,
 * Se o plug-in estiver instalado ele serao usado.
 * mais opcoes no final do artigo ou visite o manual fpdf.
 */
 $pdf->AutoPrint(true); 
 $pdf->Output("recibo_despesa_".$id_despesa, "I");

?>
