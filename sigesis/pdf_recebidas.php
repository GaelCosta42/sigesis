<?php
  /**
   * PDF Receitas recebidas
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
	  
	$id_banco = (get('id_banco')) ? get('id_banco') : 0; 
	$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y', strtotime('-15 days')); 
	$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y'); 
	$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
	
	
	$retorno_row = $faturamento->getReceitas($dataini, $datafim, $id_banco, $id_empresa);
	$pdf = new Impressao_PDF();
	$pdf->Titulo(lang('CONTAS_RECEBIDAS'));
	$pdf->SubTitulo($dataini.' - '.$datafim);
	$pdf->HeaderPDF();

	// set font
	$pdf->SetFont('helvetica', '', 9, '', false);

	// add a page
	$pdf->AddPage();
		
	ob_start();
		require(BASEPATH . 'pdf_recebidas_html.php');
		$pdfhtml = ob_get_contents();
	ob_end_clean();
	$pdf->writeHTMLSyle($pdfhtml);
	
	$pdf->Output();

?>