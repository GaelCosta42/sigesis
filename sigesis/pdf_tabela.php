<?php
  /**
   * Imprimir relatório
   *
   */
   
   //TOTAL DO CABECALHO DEVE SER = 277 PARA PAGINA Landscape
   //TOTAL DO CABECALHO DEVE SER = 190 PARA PAGINA Retrait
	define("_VALID_PHP", true);
	require_once("impressao.php");
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");

	$id_tabela = get('id'); 
	$id_categoria = get('id_categoria'); 
	$id_grupo = get('id_grupo'); 
	$id_fabricante = get('id_fabricante');
	$tab = ($id_tabela) ? getValue("tabela", "tabela_precos", "id=".get('id')) : "";
	$cat = ($id_categoria) ? getValue("categoria", "categoria", "id=".get('id_categoria')) : "";
	$gru = ($id_grupo) ? getValue("grupo", "grupo", "id=".get('id_grupo')) : "";
	$fab = ($id_fabricante) ? getValue("fabricante", "fabricante", "id=".get('id_fabricante')) : "";
	
	$titulo = utf8_decode(lang('GRADE_VENDAS'));	
	$inicio = 35;
	
	$nome_empresa = $core->empresa;
	$tab = ($tab) ? "TABELA: ".$tab : "" ;
	$cat = ($cat) ? "CATEGORIA: ".$cat : "" ;
	$gru = ($gru) ? "GRUPO: ".$gru : "" ;
	$fab = ($fab) ? "FABRICANTE: ".$fab : "" ;

	//$pdf = new PDF_Impressao( 'P', 'mm', 'A4' );
	$pdf = new Impressao_PDF( 'P', 'mm', 'A4' );
	
	$pdf->AddPage();
	$pdf->setRodape();
	$pdf->tamanho(-2);
	$pdf->addEmpresa( $nome_empresa,$tab,$cat,$gru,$fab);
	
	$pdf->subtitulo($titulo, date('d/m/Y'), 6);
	//$pdf->rascunho( "RASCUNHO" );

	$tabela_inicio = $inicio;
	$linha_inicio = $tabela_inicio + 10;
	$cabecalho=array( 
				 utf8_decode(lang('CODIGO_DA_NOTA'))    => 35,
				 utf8_decode(lang('PRODUTO'))  => 105,
				 utf8_decode(lang('ESTOQUE'))  => 20,
				 utf8_decode(lang('VALOR_VENDA'))  => 30);
				 
	$pdf->definirCols($cabecalho, "C", true, 1);
	
	$cols=array( utf8_decode(lang('CODIGO_DA_NOTA'))    => "L",
				 utf8_decode(lang('PRODUTO'))  => "L",
				 utf8_decode(lang('ESTOQUE'))  => "C",
				 utf8_decode(lang('VALOR_VENDA'))  => "C");
				 
	$pdf->addLineFormat($cols);
	$y    = $linha_inicio;
	
	$retorno_row = $produto->getTabela($id_tabela, $id_grupo, $id_categoria, $id_fabricante);
	$total = 0;
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$estoque = $produto->getEstoqueTotal($exrow->id_produto);
			$total += $estoque;			   
			$linha=array( 
				 utf8_decode(lang('CODIGO_DA_NOTA'))    => utf8_decode($exrow->codigonota),
				 utf8_decode(lang('PRODUTO'))  => utf8_decode($exrow->nome),
				 utf8_decode(lang('ESTOQUE'))  => utf8_decode($estoque),
				 utf8_decode(lang('VALOR_VENDA'))  => utf8_decode(moeda($exrow->valor_venda)) );
				 
			$size = $pdf->addLine( $y, $linha, false, "B" );
			$y   += $size + 2;
			if($y > 260) {		
				$tabela_final = $y - 30;
				$tabela_inicio = $inicio;
				$linha_inicio = $tabela_inicio + 10;
				$y    = $linha_inicio;
				$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
				$pdf->AddPage();
				$pdf->addEmpresa( $nome_empresa,$tab,$cat,$gru,$fab);
				$pdf->subtitulo($titulo, date('d/m/Y'), 6);
			}
		}
		unset($exrow);
		$tabela_final = $y-28;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
	}
	$pdf->Output();
?>