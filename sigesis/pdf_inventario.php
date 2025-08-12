<?php
  /**
   * PDF Inventario
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
	  
	$id_empresa = get('id_empresa');
	$nomeempresa = ($id_empresa) ? getValue('nome', 'empresa', 'id='.$id_empresa) : 'TODAS EMPRESAS';
	$retorno_row = $produto->getEstoqueInventario($id_empresa);
	$pdf = new Impressao_PDF();
	$pdf->Titulo(lang('PRODUTO_INVENTARIO'));
	$pdf->SubTitulo(lang('EMPRESA').": ".$nomeempresa);
	$pdf->HeaderCabecalho();

	$pdf->SetFont('helvetica', '', 10, '', false);
	$pdf->AddPage();
			
	ob_start();
		require(BASEPATH . 'pdf_inventario_html.php');
		$pdf_html = ob_get_contents();
	ob_end_clean();
	$pdf->writeHTMLSyle($pdf_html);
			
	$pdf->Output();

?>