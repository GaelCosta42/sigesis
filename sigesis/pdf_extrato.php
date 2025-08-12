<?php
  /**
   * PDF Extrato
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  define("_VALID_PHP", true);
  
	require_once("impressao.php");
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	$nome = "";
	$saldoinicial = 0;
	$saldofinal = 0;
	$extrato_row = false;
	$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y', strtotime('-15 days')); 
	$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y'); 
	$id_banco = (get('id_banco')) ? get('id_banco') : -1;
	if($id_banco > 0) {
		$nome = getValue("banco","banco","id = ".$id_banco);
		$saldoinicial = $faturamento->getSaldoInicial($dataini, $id_banco);
		$saldofinal = $faturamento->getSaldoTotal($datafim, $id_banco);
		$extrato_row = $extrato->getExtrato_view($dataini, $datafim, $id_banco);
	}
	$saldo = $saldoinicial;
	
	$pdf = new Impressao_PDF();
	$pdf->Titulo(lang('EXTRATO'));
	$pdf->SubTitulo($dataini.' - '.$datafim);
	$pdf->HeaderPDF();

	// set font
	$pdf->SetFont('helvetica', '', 9, '', false);

	// add a page
	$pdf->AddPage();
		
	ob_start();
		require(BASEPATH . 'pdf_extrato_html.php');
		$pdfhtml = ob_get_contents();
	ob_end_clean();
	$pdf->writeHTMLSyle($pdfhtml);
	
	$pdf->Output();

?>