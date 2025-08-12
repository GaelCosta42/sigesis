<?php
 /**
   * PDF - Recibo de pagamento de crediario
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
  
  $id_pagamento_crediario = get('id');
  $row_pagamento = Core::getRowById("cadastro_crediario_pagamentos", $id_pagamento_crediario);
  $row_cadastro = Core::getRowById("cadastro", $row_pagamento->id_cadastro);
  $id_empresa = ($row_cadastro->id_empresa) ? $row_cadastro->id_empresa : 1;
  $row_empresa = Core::getRowById("empresa", $id_empresa);
  $data_pagamento = exibedataHora($row_pagamento->data_pagamento);
  $descricao = "Pagamento de crediário do cliente";
  $valor_pago = moeda($row_pagamento->valor_pago);
  $atendente = $row_pagamento->usuario;
  
  $nome_empresa = $row_empresa->nome;
  $razao_empresa = $row_empresa->razao_social;
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

//EXIBINDO DADOS DA RECEITA
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('CODIGO').":"));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, ($id_pagamento_crediario));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DATA_PAGAMENTO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("35");
$pdf->Cell(0, 10, ($data_pagamento));
$pdf->Ln(2);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));
$pdf->Ln(7);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('NOME')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("30");
$pdf->MultiCell(0, 5, ($row_cadastro->nome));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('VALOR_PAGO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("30");
$pdf->Cell(0, 10, ($valor_pago));
$pdf->Ln(10);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DESCRICAO')));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("30");
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

//EXIBINDO INFORMACAO MARCA DAGUA PAGO

/*
$pdf->Ln(-20);
$pdf->SetTextColor(194,8,8);
$pdf->SetFont('arial','B',56);
$pdf->SetX("5");
$pdf->Cell(0, 10, "PAGO");
*/

$pdf->Image('assets/img/pago.png',8,35,60,28,'png');
 
/*
 * IMPRIMIR A SAIDA DO ARQUIVO
 * nome do arquivo
 * I: envia o arquivo diretamente para o browser,
 * Se o plug-in estiver instalado ele serao usado.
 * mais opcoes no final do artigo ou visite o manual fpdf.
 */
 $pdf->AutoPrint(true); 
 $pdf->Output("recibo_receita_".$id_pagamento_crediario, "I");

?>
