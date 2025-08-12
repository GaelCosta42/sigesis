<?php
  /**
   * PDF Orçamento
   *
   */
  define("_VALID_PHP", true);
  
	require_once("impressao_orcamento_pedido.php");
	require_once("init.php");
		  
	$id_orcamento = (get('id_orcamento')) ? get('id_orcamento') : 0;
	$orcamento = $ordem_servico->getOrdemServicoImpressao($id_orcamento);
	
	$pdf = new Impressao_PDF();
	$pdf->Titulo(lang('ORCAMENTO_VISUALIZAR'));
	
	$subtitulo = $orcamento->nomeempresa;
	$pdf->SubTitulo($subtitulo);
	
	$pdf->HeaderPDF();
	// set font
	$pdf->SetFont('helvetica', '', 10, '', false);
	// add a page
	$pdf->AddPage();
	
	ob_start();
		require(BASEPATH . 'imprimir_orcamento_html.php');
		$html = ob_get_contents();
	ob_end_clean();
	$pdf->writeHTMLSyle($html);
		
	$pdf->Output();

?>