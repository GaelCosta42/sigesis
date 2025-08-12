<?php
  /**
   * PDF Nota fiscal chave de acesso
   *
   */
   
  define("_VALID_PHP", true);
  
	require_once("impressao.php");
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	
	$operacao = (get('operacao')) ? get('operacao') : ''; 
	$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0; 
	$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y'); 
	
	$nomeempresa = ($id_empresa) ? getValue('nome', 'empresa', 'id='.$id_empresa) : 'TODAS EMPRESAS';
	$retorno_row = $produto->getNotaFiscalChaveAcesso($mes_ano, $id_empresa, $operacao);
	$pdf = new Impressao_PDF();
	$pdf->Titulo(lang('NOTA_LISTAR_CHAVEACESSO'));
	$pdf->SubTitulo(lang('EMPRESA').": ".$nomeempresa);
	$pdf->HeaderCabecalho();

	$pdf->SetFont('helvetica', '', 10, '', false);
	$pdf->AddPage();
			
	ob_start();
		require(BASEPATH . 'pdf_chaveacesso_html.php');
		$pdf_html = ob_get_contents();
	ob_end_clean();
	$pdf->writeHTMLSyle($pdf_html);
			
	$pdf->Output();

?>