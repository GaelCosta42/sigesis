<?php
  /**
   * Imprimir Cotacao
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
   
   //TOTAL DO CABECALHO DEVE SER = 277 PARA PAGINA Landscape
   //TOTAL DO CABECALHO DEVE SER = 190 PARA PAGINA Retrait
   
   
require('impressao.php');
require('init.php');

$id_cotacao = (get('id')) ? get('id') : 0; 

if($id_cotacao){
	$pdf = new PDF_Impressao( 'L', 'mm', 'A4' );
	$pdf->setHeader(utf8_decode(lang('COTACAO_VISUALIZAR')));
	$pdf->setFooter();
	$pdf->AddPage();
	//Second table: specify 3 columns
	$pdf->AddColTabela('produto',77,'Produto','C');
	$centrocusto_row = $cotacao->getCentroCustoCotacao($id_cotacao);
	if ($centrocusto_row) {
		$cont_cc = count($centrocusto_row);
		$resto = (200 % $cont_cc);
		$col = (200-$resto)/($cont_cc);
		foreach ($centrocusto_row as $ccrow):
			$pdf->AddColTabela($ccrow->id,$col+$resto,substr($ccrow->centro_custo, 0, 6),'C');
			$resto = 0;
		endforeach;
		unset($ccrow);
		$prop=array('HeaderColor'=>array(15,117,188),
					'HeaderTextColor'=>array(255,255,255),
					'color1'=>array(215,240,255),
					'color2'=>array(255,255,255),
					'padding'=>2);

		$cols = array();			
		$rows = array();
		$retorno_row = $cotacao->getCotacaoProdutos($id_cotacao);
		if ($retorno_row):
			foreach ($retorno_row as $srow):
				$id_produto = $srow->id_produto;
				$cols['produto'] = $srow->produto;		
				foreach ($centrocusto_row as $ccrow):
					$cols[$ccrow->id] = $cotacao->getQuantItensCentroCusto($id_cotacao, $id_produto, $ccrow->id);
					$resto = 0;
				endforeach;
				unset($ccrow);
				$rows[] = (object) $cols;
			endforeach;
			unset($srow);
		endif;
		// print_r($rows);
		$pdf->Tabela($rows	,$prop);
		$pdf->Output();
	} else {
		echo lang('MSG_ERRO_COTACAO_CENTROCUSTO');
	}
} else {
	echo lang('MSG_ERRO_COTACAO');
}
?>
