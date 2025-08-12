<?php
  /**
   * Imprimir despesas
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
   
   //TOTAL DO CABECALHO DEVE SER = 277 PARA PAGINA Landscape
   //TOTAL DO CABECALHO DEVE SER = 190 PARA PAGINA Portrait
	
	require_once("impressao.php");
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	
	$mes_ano = get('mes_ano');
	$id = get('id');
	$salarios = $rh->getSalarioColaborador($mes_ano, $id);
	$row_usuasrio = Core::getRowById("usuario", $id);
	
	$venc = 0;
	$desc = 0;
	
	$titulo = utf8_decode($salarios['empresa']);	
	$inicio = 35;
  
	$pdf = new PDF_Impressao( 'P', 'mm', 'A4' );
	$pdf->AddPage();
	$pdf->setRodape();
	$pdf->tamanho(-2);
	$pdf->addEmpresa($titulo,$salarios['nome']);
	
	$pdf->subtitulo(utf8_decode("Demonstrativo de Pag. de Salário"), exibeMesAno($mes_ano, true, true), 8);
	//$pdf->rascunho( "RASCUNHO" );

	$tabela_inicio = $inicio;
	$linha_inicio = $tabela_inicio + 10;
	$cabecalho=array( 
				 utf8_decode(lang('DESCRICAO'))    => 120,
				 utf8_decode(lang('VENCIMENTOS'))  => 35,
				 utf8_decode(lang('DESCONTOS'))  => 35);
				 
	$pdf->definirCols($cabecalho, "C", true, 1);
	
	$cols=array( utf8_decode(lang('DESCRICAO'))    => "L",
				 utf8_decode(lang('VENCIMENTOS'))  => "L",
				 utf8_decode(lang('DESCONTOS'))  => "L");
				 
	$pdf->addLineFormat($cols);
	$y    = $linha_inicio;
	
	$linha=array( 
		utf8_decode(lang('DESCRICAO'))  => utf8_decode(lang('DESCRICAO')),
		 utf8_decode(lang('VENCIMENTOS'))  => utf8_decode(lang('VENCIMENTOS')),
		 utf8_decode(lang('DESCONTOS'))  => utf8_decode(lang('DESCONTOS')));
				 
	$size = $pdf->addLine( $y, $linha, true, 1 );
	$y   += $size + 2;
	
	$venc += $salarios['salario'];
	$linha=array( 
		utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('SALARIO_BASE')))),
		 utf8_decode(lang('VENCIMENTOS'))  => utf8_decode(moeda($salarios['salario'])),
		 utf8_decode(lang('DESCONTOS'))  => ' ');
				 
	$size = $pdf->addLine( $y, $linha, false, 1 );
	$y   += $size + 2;
	
	 if($salarios['insalubridade'] > 0) {
		 $venc += $salarios['insalubridade'];
		 $linha=array( 
			 utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('INSALUBRIDADE')))),
			 utf8_decode(lang('VENCIMENTOS'))  => utf8_decode(moeda($salarios['insalubridade'])),
			 utf8_decode(lang('DESCONTOS'))  => ' ');
				 
		$size = $pdf->addLine( $y, $linha, false, 1 );
		$y   += $size + 2;
	 } 
	 if($salarios['abono'] > 0) {
		 $venc += $salarios['abono'];
		 $linha=array( 
			 utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('ABONO')))),
			 utf8_decode(lang('VENCIMENTOS'))  => utf8_decode(moeda($salarios['abono'])),
			 utf8_decode(lang('DESCONTOS'))  => ' ');
				 
		$size = $pdf->addLine( $y, $linha, false, 1 );
		$y   += $size + 2;
	 }
	 if($salarios['filhos'] > 0) {
		 $venc += $salarios['filhos'];
		 $linha=array( 
			 utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('SALARIO_FAMILIA')))),
			 utf8_decode(lang('VENCIMENTOS'))  => utf8_decode(moeda($salarios['filhos'])),
			 utf8_decode(lang('DESCONTOS'))  => ' ');
				 
		$size = $pdf->addLine( $y, $linha, false, 1 );
		$y   += $size + 2;
	 }
	 
	 $bonus_row = $rh->getBonus($id, $mes_ano);
	 if($bonus_row) {
		foreach ($bonus_row as $exrow) {
			 $venc += $exrow->valor;
			 $linha=array( 
				 utf8_decode(lang('DESCRICAO'))  => utf8_decode($exrow->descricao),
				 utf8_decode(lang('VENCIMENTOS'))  => utf8_decode(moeda($exrow->valor)),
				 utf8_decode(lang('DESCONTOS'))  => ' ');
					 
			$size = $pdf->addLine( $y, $linha, false, 1 );
			$y   += $size + 2;
		}
		unset($exrow);
	 }
	 if($salarios['adiantamentos'] > 0) {
		 $desc += $salarios['adiantamentos'];
		 $linha=array( 
			 utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('ADIANTAMENTOS')))),
			 utf8_decode(lang('VENCIMENTOS'))  => ' ',
			 utf8_decode(lang('DESCONTOS'))  => utf8_decode(moeda($salarios['adiantamentos'])));
				 
		$size = $pdf->addLine( $y, $linha, false, 1 );
		$y   += $size + 2;
	 }
	 if($salarios['inss'] > 0) {
		 $desc += $salarios['inss'];
		 $linha=array( 
			 utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('INSS')))),
			 utf8_decode(lang('VENCIMENTOS'))  => ' ',
			 utf8_decode(lang('DESCONTOS'))  => utf8_decode(moeda($salarios['inss'])));
				 
		$size = $pdf->addLine( $y, $linha, false, 1 );
		$y   += $size + 2;
	 }
	 if($salarios['irrf'] > 0) {
		 $desc += $salarios['irrf'];
		 $linha=array( 
			 utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('IRRF')))),
			 utf8_decode(lang('VENCIMENTOS'))  => ' ',
			 utf8_decode(lang('DESCONTOS'))  => utf8_decode(moeda($salarios['irrf'])));
				 
		$size = $pdf->addLine( $y, $linha, false, 1 );
		$y   += $size + 2;
	 }
	 if($salarios['transporte'] > 0) {
		 $desc += $salarios['transporte'];
		 $linha=array( 
			 utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('TRANSPORTE')))),
			 utf8_decode(lang('VENCIMENTOS'))  => ' ',
			 utf8_decode(lang('DESCONTOS'))  => utf8_decode(moeda($salarios['transporte'])));
				 
		$size = $pdf->addLine( $y, $linha, false, 1 );
		$y   += $size + 2;
	 }
	 if($salarios['planodesaude'] > 0) {
		 $desc += $salarios['planodesaude'];
		 $linha=array( 
			 utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('PLANO_SAUDE')))),
			 utf8_decode(lang('VENCIMENTOS'))  => ' ',
			 utf8_decode(lang('DESCONTOS'))  => utf8_decode(moeda($salarios['planodesaude'])));
				 
		$size = $pdf->addLine( $y, $linha, false, 1 );
		$y   += $size + 2;
	 }
	 if($salarios['planodependente'] > 0) {
		 $desc += $salarios['planodependente'];
		 $linha=array( 
			 utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('PLANO_SAUDE_DEPENDENTE')))),
			 utf8_decode(lang('VENCIMENTOS'))  => ' ',
			 utf8_decode(lang('DESCONTOS'))  => utf8_decode(moeda($salarios['planodependente'])));
				 
		$size = $pdf->addLine( $y, $linha, false, 1 );
		$y   += $size + 2;
	 }
	 if($salarios['planoextra'] > 0) {
		 $desc += $salarios['planoextra'];
		 $linha=array( 
			 utf8_decode(lang('DESCRICAO'))  => utf8_decode(strtoupper((lang('PLANO_SAUDE_EXTRA')))),
			 utf8_decode(lang('VENCIMENTOS'))  => ' ',
			 utf8_decode(lang('DESCONTOS'))  => utf8_decode(moeda($salarios['planoextra'])));
				 
		$size = $pdf->addLine( $y, $linha, false, 1 );
		$y   += $size + 2;
	 }
	 
	 $desconto_row = $rh->getDescontos($id, $mes_ano);
	 if($desconto_row) {
		foreach ($desconto_row as $exrow) {
			 $desc += $exrow->valor;
			 $linha=array( 
				 utf8_decode(lang('DESCRICAO'))  => utf8_decode($exrow->descricao),
				 utf8_decode(lang('VENCIMENTOS'))  => ' ',
				 utf8_decode(lang('DESCONTOS'))  => utf8_decode(moeda($exrow->valor)));
					 
			$size = $pdf->addLine( $y, $linha, false, 1 );
			$y   += $size + 2;
		}
		unset($exrow);
	 }
	 
	 $linha=array( 
		 utf8_decode(lang('DESCRICAO'))  => ' ',
		 utf8_decode(lang('VENCIMENTOS'))  => ' ',
		 utf8_decode(lang('DESCONTOS'))  => ' ');
			 
	$size = $pdf->addLine( $y, $linha, false, 0 );
	$y   += $size + 2;
	 
	 $linha=array( 
		 utf8_decode(lang('DESCRICAO'))  => ' ',
		 utf8_decode(lang('VENCIMENTOS'))  => utf8_decode(moeda($venc)),
		 utf8_decode(lang('DESCONTOS'))  => utf8_decode(moeda($desc)));
			 
	$size = $pdf->addLine( $y, $linha, false, 0 );
	$y   += $size + 2;
	
	 $linha=array( 
		 utf8_decode(lang('DESCRICAO'))  => utf8_decode(lang('SALARIO_LIQUIDO')),
		 utf8_decode(lang('VENCIMENTOS'))  => utf8_decode(moeda($salarios['valor_pagar'])),
		 utf8_decode(lang('DESCONTOS'))  => ' ');
			 
	$size = $pdf->addLine( $y, $linha, true, 1 );
	$y   += $size + 2;
	
	 $linha=array( 
		 utf8_decode(lang('DESCRICAO'))  => ' ',
		 utf8_decode(lang('VENCIMENTOS'))  => ' ',
		 utf8_decode(lang('DESCONTOS'))  => ' ');
			 
	$size = $pdf->addLine( $y, $linha, false, 0 );
	$y   += $size + 2;
	
	 $linha=array( 
		 utf8_decode(lang('DESCRICAO'))  => utf8_decode(lang('CPF').": ".$row_usuasrio->cpf),
		 utf8_decode(lang('VENCIMENTOS'))  => ' ',
		 utf8_decode(lang('DESCONTOS'))  => ' ');
			 
	$size = $pdf->addLine( $y, $linha, false, 0 );
	$y   += $size + 2;
	
	 $linha=array( 
		 utf8_decode(lang('DESCRICAO'))  => utf8_decode(lang('BANCO').": ".$row_usuasrio->banco." ".$row_usuasrio->codigo),
		 utf8_decode(lang('VENCIMENTOS'))  => ' ',
		 utf8_decode(lang('DESCONTOS'))  => ' ');
			 
	$size = $pdf->addLine( $y, $linha, false, 0 );
	$y   += $size + 2;
	
	 $linha=array( 
		 utf8_decode(lang('DESCRICAO'))  => utf8_decode(lang('AGENCIA').": ".$row_usuasrio->agencia),
		 utf8_decode(lang('VENCIMENTOS'))  => ' ',
		 utf8_decode(lang('DESCONTOS'))  => ' ');
			 
	$size = $pdf->addLine( $y, $linha, false, 0 );
	$y   += $size + 2;
	
	 $linha=array( 
		 utf8_decode(lang('DESCRICAO'))  => utf8_decode(lang('CONTA').": ".$row_usuasrio->conta),
		 utf8_decode(lang('VENCIMENTOS'))  => ' ',
		 utf8_decode(lang('DESCONTOS'))  => ' ');
			 
	$size = $pdf->addLine( $y, $linha, false, 0 );
	$y   += $size + 2;
	 
	$pdf->Output();
?>