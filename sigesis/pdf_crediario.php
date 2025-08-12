<?php  
/**   
 * Imprimir relatório   
 */   
//TOTAL DO CABECALHO DEVE SER = 277 PARA PAGINA Landscape   
//TOTAL DO CABECALHO DEVE SER = 190 PARA PAGINA Portrait

	require_once("impressao_header_crediario.php");	
	require_once("init.php");
	if (!$usuario->is_Todos())
		redirect_to("login.php");

	$titulo = utf8_decode(lang('CADASTRO_CREDIARIO'));		
	$inicio = 35;	
	$row_empresa = Core::getRowById("empresa", 1);	
	
	$pdf = new PDF_Impressao( 'P', 'mm', 'A4' );	
	$pdf->AddPage();	
	$pdf->setRodape();	
	$pdf->tamanho(-2);	
	$pdf->addEmpresa($row_empresa->nome, formatar_cpf_cnpj($row_empresa->cnpj),$row_empresa->endereco.", ".$row_empresa->numero,$row_empresa->bairro.", ".$row_empresa->cidade);
	
	//$pdf->rascunho( "RASCUNHO" );	
	$tabela_inicio = $inicio;	
	$linha_inicio = $tabela_inicio + 10;	
	$cabecalho=array(
		utf8_decode(lang('CLIENTE'))    => 80,
		utf8_decode(lang('CREDIARIO'))  => 35,
		utf8_decode(lang('SALDO'))  => 35,
		utf8_decode(lang('VALOR_PAGAR'))  => 40);
	$pdf->definirCols($cabecalho, "C", true, 1);
	
	$cols=array( 
		utf8_decode(lang('CLIENTE'))    => "L",
		utf8_decode(lang('CREDIARIO'))  => "C",
		utf8_decode(lang('SALDO'))  => "C",
		utf8_decode(lang('VALOR_PAGAR'))  => "C");
	$pdf->addLineFormat($cols);	
	
	$y = $linha_inicio;	
	$retorno_row = $cadastro->getCadastros('CLIENTE');
	$total = 0;	
	
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$valor_crediario = $exrow->crediario;
			$valor_pagar = $cadastro->getPagarCrediario($exrow->id);
			if($valor_pagar > 0) {
				$total += $valor_pagar;
				$saldo = $valor_crediario - $valor_pagar;
				$linha=array(
					utf8_decode(lang('CLIENTE'))    => utf8_decode($exrow->nome),
					utf8_decode(lang('CREDIARIO'))  => utf8_decode(moeda($valor_crediario)),
					utf8_decode(lang('SALDO'))  => utf8_decode(moeda($saldo)),
					utf8_decode(lang('VALOR_PAGAR'))  => utf8_decode(moeda($valor_pagar)) 
				);
					
				$size = $pdf->addLine( $y, $linha, false, 1 );
				$y   += $size + 2;
				if($y > 260) {
					$tabela_final = $y - 30;
					$tabela_inicio = $inicio;
					$linha_inicio = $tabela_inicio + 10;
					$y    = $linha_inicio;
					$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
					$pdf->AddPage();
					$pdf->addEmpresa($row_empresa->nome, $row_empresa->cnpj,$row_empresa->endereco.", ".$row_empresa->numero,$row_empresa->bairro.", ".$row_empresa->cidade);
				}
			}		
		}
		unset($exrow);
		
		$linha=array(
			utf8_decode(lang('CLIENTE'))    => '',
			utf8_decode(lang('CREDIARIO'))  => '',
			utf8_decode(lang('SALDO'))  => utf8_decode(lang('TOTAL')),
			utf8_decode(lang('VALOR_PAGAR'))  => utf8_decode(moeda($total)) 
		);
		
		$size = $pdf->addLine( $y, $linha, true, 1 );
		$tabela_final = $y-32;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
	}	
	
	$pdf->Output();?>