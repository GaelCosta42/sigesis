<?php
  /**
   * Imprimir relatório
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
   
   //TOTAL DO CABECALHO DEVE SER = 277 PARA PAGINA Landscape
   //TOTAL DO CABECALHO DEVE SER = 190 PARA PAGINA Retrait
	
	require_once("impressao.php");
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	$id_categoria = get('id_categoria'); 
	$id_grupo = get('id_grupo'); 
	$id_fabricante = get('id_fabricante');
	$cat = ($id_categoria) ? getValue("categoria", "categoria", "id=".get('id_categoria')) : "";
	$gru = ($id_grupo) ? getValue("grupo", "grupo", "id=".get('id_grupo')) : "";
	$fab = ($id_fabricante) ? getValue("fabricante", "fabricante", "id=".get('id_fabricante')) : "";
	
	$titulo = utf8_decode(lang('PRODUTO_LISTAR'));	
	$inicio = 35;
	
	$nome_empresa = $core->empresa;
	$cat = ($cat) ? "CATEGORIA: ".$cat : "" ;
	$gru = ($gru) ? "GRUPO: ".$gru : "" ;
	$fab = ($fab) ? "FABRICANTE: ".$fab : "" ;
  
	$pdf = new PDF_Impressao( 'P', 'mm', 'A4' );
	$pdf->AddPage();
	$pdf->setRodape();
	$pdf->tamanho(-2);
	$pdf->addEmpresa( $nome_empresa,$cat,$gru,$fab);
	
	$pdf->subtitulo($titulo, date('d/m/Y'), 6);
	//$pdf->rascunho( "RASCUNHO" );

	$tabela_inicio = $inicio;
	$linha_inicio = $tabela_inicio + 10;
	$cabecalho=array( 
				 utf8_decode(lang('CODIGO_DA_NOTA'))    => 35,
				 utf8_decode(lang('PRODUTO'))  => 115,
				 utf8_decode(lang('ESTOQUE'))  => 20,
				 utf8_decode(lang('INVENTARIO'))  => 20);
				 
	$pdf->definirCols($cabecalho, "C", true, 1);
	
	$cols=array( utf8_decode(lang('CODIGO_DA_NOTA'))    => "L",
				 utf8_decode(lang('PRODUTO'))  => "L",
				 utf8_decode(lang('ESTOQUE'))  => "C",
				 utf8_decode(lang('INVENTARIO'))  => "C");
				 
	$pdf->addLineFormat($cols);
	$y    = $linha_inicio;
	
	$retorno_row = $produto->getProdutos($id_grupo, $id_categoria, $id_fabricante);
	$total = 0;
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$estoque = $produto->getEstoqueTotal($exrow->id);
			$total += $estoque;			   
			$linha=array( 
				 utf8_decode(lang('CODIGO_DA_NOTA'))    => utf8_decode($exrow->codigonota),
				 utf8_decode(lang('PRODUTO'))  => utf8_decode($exrow->nome),
				 utf8_decode(lang('ESTOQUE'))  => utf8_decode($estoque),
				 utf8_decode(lang('INVENTARIO'))  => '' );
				 
			$size = $pdf->addLine( $y, $linha, false, "B" );
			$y   += $size + 2;
			if($y > 260) {		
				$tabela_final = $y - 30;
				$tabela_inicio = $inicio;
				$linha_inicio = $tabela_inicio + 10;
				$y    = $linha_inicio;
				$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
				$pdf->AddPage();
				$pdf->addEmpresa( $nome_empresa,$cat,$gru,$fab);
				$pdf->subtitulo($titulo, date('d/m/Y'), 6);
			}
		}
		unset($exrow);
		$tabela_final = $y-28;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
	}
	$pdf->Output();
?>