<?php
  /**
   * PDF Caixa
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
	  
	$id_caixa = get('id_caixa');
	if($id_caixa == 0):
		echo lang('CAIXA_ERRO');
	else:
		$detalhes_row = $faturamento->getDetalhesCaixa($id_caixa);
		$pdf = new Impressao_PDF();
		$pdf->Titulo(lang('CAIXA_DETALHES').": ".$id_caixa);
		$pdf->SubTitulo(exibedata($detalhes_row->data_abrir));
		$pdf->HeaderPDF();

		// set font
		$pdf->SetFont('helvetica', '', 10, '', false);

		// add a page
		$pdf->AddPage();
		
		ob_start();
			require(BASEPATH . 'imprimir_caixa_html.php');
			$imprimir_caixa_html = ob_get_contents();
		ob_end_clean();
		$pdf->writeHTMLSyle($imprimir_caixa_html);
		
		$pdf->Output();
	endif;

?>