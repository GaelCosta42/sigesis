<?php
  /**
   * PDF Caixa
   *
   */
   
   //TOTAL DO CABECALHO DEVE SER = 277 PARA PAGINA Landscape
   //TOTAL DO CABECALHO DEVE SER = 190 PARA PAGINA Retrait
   
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
		$pdf->HeaderSIGE();

		// set font
		$pdf->SetFont('helvetica', '', 10, '', false);

		// add a page
		$pdf->AddPage();
		
		ob_start();
			require(BASEPATH . 'pdf_caixa_resumo.php');
			$pdf_caixa_html = ob_get_contents();
		ob_end_clean();
		$pdf->writeHTMLSyle($pdf_caixa_html);
		
		$pdf->Output();
	endif;

?>