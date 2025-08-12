<?php
  /**
   * PDF Receitas a Receber
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
	  
	$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
	$id_cadastro = (get('id_cadastro')) ? get('id_cadastro') : 0; 
	$id_banco = (get('id_banco')) ? get('id_banco') : 0; 
	$id_conta = (get('id_conta')) ? get('id_conta') : 0; 
	$valor = (get('valor')) ? get('valor') : ''; 
	$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y'); 
	$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y'); 
	
	
	$retorno_row = $despesa->getDespesasPagas($id_empresa, $dataini, $datafim, $id_banco, $id_conta, $id_cadastro, $valor);
	$pdf = new Impressao_PDF();
	$pdf->Titulo(lang('FINANCEIRO_DESPESASPAGAS'));
	$pdf->SubTitulo($dataini.' - '.$datafim);
	$pdf->HeaderPDF();

	// set font
	$pdf->SetFont('helvetica', '', 7, '', false);

	// add a page
	$pdf->AddPage();
		
	ob_start();
		require(BASEPATH . 'pdf_despesaspagas_html.php');
		$pdfhtml = ob_get_contents();
	ob_end_clean();
	$pdf->writeHTMLSyle($pdfhtml);
	
	$pdf->Output();

?>