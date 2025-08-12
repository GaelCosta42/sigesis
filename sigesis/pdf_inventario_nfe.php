<?php
  /**
   * PDF Inventario
   *
   */
   
  define("_VALID_PHP", true);
  
	require_once("impressao.php");
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	$id_empresa = get('id_empresa');
	$mes_ano = get('mes_ano');
	$nomeempresa = ($id_empresa) ? getValue('nome', 'empresa', 'id='.$id_empresa) : 'TODAS EMPRESAS';
	$cnpj_empresa = ($id_empresa) ? getValue('cnpj','empresa','id='.$id_empresa) : '';
	$retorno_row = $produto->getInventarioFiscal($id_empresa, $mes_ano);
	$pdf = new Impressao_PDF();
	$pdf->Titulo(lang('PRODUTO_INVENTARIO'));
	$pdf->SubTitulo(lang('EMPRESA').": ".$nomeempresa);
	$pdf->HeaderCabecalho();

	$pdf->SetFont('helvetica', '', 10, '', false);
	$pdf->AddPage();
			
	ob_start();
		require(BASEPATH . 'pdf_inventario_nfe_html.php');
		$pdf_html = ob_get_contents();
	ob_end_clean();
	$pdf->writeHTMLSyle($pdf_html);
			
	$pdf->Output();

?>