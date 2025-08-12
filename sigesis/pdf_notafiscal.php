<?php
  /**
   * PDF Nota fiscal
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
	
	$numero_nota = (get('numero_nota')) ? get('numero_nota') : ""; 
	$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
	$modelo = (get('modelo')) ? get('modelo') : 0; 
	$operacao = (get('operacao')) ? get('operacao') : 0; 
	$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y'); 
	$mes_ano = (get('ano')) ? 0 : $mes_ano; 
	$ano = (get('ano')) ? get('ano') : 0;
	
	$nomeempresa = ($id_empresa) ? getValue('nome', 'empresa', 'id='.$id_empresa) : 'TODAS EMPRESAS';
	$retorno_row = $produto->getNotaFiscal($mes_ano, $id_empresa, $modelo, $operacao, $numero_nota, $ano);
	$pdf = new Impressao_PDF();
	$pdf->Titulo(lang('NOTA_LISTAR'));
	$pdf->SubTitulo(lang('EMPRESA').": ".$nomeempresa);
	$pdf->HeaderCabecalho();

	$pdf->SetFont('helvetica', '', 10, '', false);
	$pdf->AddPage();
			
	ob_start();
		require(BASEPATH . 'pdf_notafiscal_html.php');
		$pdf_html = ob_get_contents();
	ob_end_clean();
	$pdf->writeHTMLSyle($pdf_html);
			
	$pdf->Output();

?>