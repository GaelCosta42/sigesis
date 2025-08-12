<?php
  /**
   * Visualizar Produtos
   *
  * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
	
	require_once("impressao.php");
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	
	$id_familia = get('id_familia'); 
	$id_fabricante = get('id_fabricante'); 
	
	$titulo = utf8_decode(lang('ESTOQUE_LISTAR'));	
	$inicio = 15;
  
	$pdf = new PDF_Impressao( 'P', 'mm', 'A4' );
	
	$pdf->SetFont('arial', '', 8);
	$pdf->AddPage();

	$tabela_inicio = $inicio;
	$linha_inicio = $tabela_inicio + 10;
	$cabecalho=array( 
				 utf8_decode(lang('CODIGO'))    => 15,
				 utf8_decode(lang('PRODUTO'))  => 40,
				 utf8_decode(lang('FAMILIA'))  => 40,
				 utf8_decode(lang('FABRICANTE'))  => 40,
				 utf8_decode(lang('ESTOQUE'))  => 20 );
				 
	$pdf->definirCols($cabecalho);
	
	$cols=array( utf8_decode(lang('CODIGO'))    => "R",
				 utf8_decode(lang('PRODUTO'))  => "L",
				 utf8_decode(lang('FAMILIA'))  => "L",
				 utf8_decode(lang('FABRICANTE'))  => "L",
				 utf8_decode(lang('ESTOQUE'))  => "L" );
				 
	$pdf->addLineFormat($cols);
	$y    = $linha_inicio;
	
	$retorno_row = $produto->getProdutosEstoque($id_familia, $id_fabricante);
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$total = $produto->getEstoqueTotal($exrow->id, false);		   
			$linha=array( 
				 utf8_decode(lang('CODIGO'))    => utf8_decode($exrow->codigonota),
				 utf8_decode(lang('PRODUTO'))  => utf8_decode($exrow->nome),
				 utf8_decode(lang('FAMILIA'))  => utf8_decode($exrow->familia),
				 utf8_decode(lang('FABRICANTE'))  => utf8_decode($exrow->fabricante),
				 utf8_decode(lang('ESTOQUE'))  => utf8_decode(decimal($total)) );
				 
			$size = $pdf->addLine( $y, $linha );
			$y   += $size + 2;
			if($y > 220) {		
				$tabela_final = $y - 30;
				$tabela_inicio = $inicio;
				$linha_inicio = $tabela_inicio + 10;
				$y    = $linha_inicio;
				$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
				$pdf->AddPage();							  
				$pdf->subtitulo( $titulo, '' );
			}
		}
		unset($exrow);
	}
	$pdf->Output();
?>