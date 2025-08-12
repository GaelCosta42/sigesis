<?php
  /**
   * Imprimir DAS
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
   
   //TOTAL DO CABECALHO DEVE SER = 277 PARA PAGINA Landscape
   //TOTAL DO CABECALHO DEVE SER = 190 PARA PAGINA Retrait
    define("_VALID_PHP", true);
	require_once("impressao_das.php");
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");

	$pdf = new PDF_Impressao( 'L', 'mm', 'A4' );
	$pdf->setRodape(true);

	if (isset($_GET['id_empresa'])) {

		$id_empresa = get('id_empresa');
		$mes_ano = get('mes_ano');
		$nome_empresa = getValue('nome', 'empresa', 'id='.$id_empresa);
		$cnpj = getValue('cnpj', 'empresa', 'id='.$id_empresa);
		$cidade_empresa = getValue('cidade', 'empresa', 'id='.$id_empresa);

		$pdf->AddPage();
		$pdf->tamanho(-3);
		$pdf->addEmpresa($nome_empresa,formatar_cpf_cnpj($cnpj));
		$pdf->subtitulo(exibeMesAno($mes_ano, true, true),$cidade_empresa);

		$titulo = lang('NOTA_FISCAL');	
		$inicio = 20;

		$tabela_inicio = $inicio;
		$linha_inicio = $tabela_inicio + 10;
		$cabecalho = array( 
						 lang('DATA_EMISSAO')    => 20,
						 lang('NUMERO_NOTA')  => 25,
						 lang('TRIBUTADA')  => 20,
						 lang('ST')  => 20,
						 lang('VALOR_SERVICO')  => 20,
						 lang('CIDADE')  => 20,
						 lang('VALOR_TOTAL')  => 25,
						 lang('PARC')  => 9,
						 lang('TPARC')  => 9,
						 lang('VALOR_PARCELA')  => 24,
						 lang('VENCIMENTO')  => 20,
						 lang('RECEBIMENTO')  => 20,
						 lang('VALOR_PAGO')  => 25,
						 lang('ISS_RETIDO')  => 20 
					); //TOTAL DO CABECALHO DEVE SER = 277 PARA PAGINA Landscape
					 
		$pdf->definirCols($cabecalho);

		$cols = array( 
						 lang('DATA_EMISSAO')    => 'C',
						 lang('NUMERO_NOTA')  => 'C',
						 lang('TRIBUTADA')  => 'C',
						 lang('ST')  => 'C',
						 lang('VALOR_SERVICO')  => 'C',
						 lang('CIDADE')  => 'C',
						 lang('VALOR_TOTAL')  => 'C',
						 lang('PARC')  => 'C',
						 lang('TPARC')  => 'C',
						 lang('VALOR_PARCELA')  => 'C',
						 lang('VENCIMENTO')  => 'C',
						 lang('RECEBIMENTO')  => 'C',
						 lang('VALOR_PAGO')  => 'C',
						 lang('ISS_RETIDO')  => 'C' 
				);
					 
		$pdf->addLineFormat($cols);
		$y    = $linha_inicio;
		
		$vltrib = 0;
		$vlst = 0;
		$vlservico = 0;
		$vltotal = 0;
		$vlparcela = 0;
		$vlpago = 0;
		$vliss = 0;
		
		$p_vltrib = 0;
		$p_vlst = 0;
		$p_vlservico = 0;
		$p_vltotal = 0;
		$p_vlparcela = 0;
		$p_vlpago = 0;
		$p_vliss = 0;

		$retorno_row = $faturamento->getReceitasDAS($id_empresa, $mes_ano);

		//echo "ola mundo6";
		//die();
		
		if($retorno_row) {
			foreach ($retorno_row as $exrow) {
				$tr = 0;
				$st = 0;
				$sv = 0;
				$id_nota = $exrow->id_nota;			   
				$id_venda = $exrow->id_venda;			   
				$vltotal += $exrow->valor_total;	
					$p_vltotal += $exrow->valor_total;		   
				$vlparcela += $exrow->valor;	
					$p_vlparcela += $exrow->valor;			   
				$vlpago += $exrow->valor;	
					$p_vlpago += $exrow->valor;			   
				$vliss += $exrow->valor_iss_retido;
					$p_vliss += $exrow->valor_iss_retido;
				
					
				$numero_nota = ($exrow->numero_nota) ? $exrow->numero_nota : $exrow->duplicata;
				$cidade = ($exrow->modelo != 2) ? $exrow->cidade : '';
				$parcela = "-";
				$tparcela = "-";
				if($id_nota) {
					$nf = $faturamento->getReceitaNFTotal($id_nota);
					$tparcela = $nf->quant;
					$nfp = $faturamento->getReceitaNFParcela($id_nota, $exrow->data_pagamento);
					$parcela = $nfp->quant;
				} else if($id_venda) {
					$nf = $faturamento->getReceitaNFCTotal($id_venda);
					$tparcela = $nf->quant;
					$nfp = $faturamento->getReceitaNFEParcela($id_venda, $exrow->data_pagamento);
					$parcela = $nfp->quant;
				}
				//modelo == 1 : SERVICO - modelo == 2 : PRODUTO
				if($exrow->modelo == 1 or $exrow->modelo == 3) {
					$sv = $exrow->valor_total;			
				} else {
					$percentual = ($exrow->valor_nota) ? $exrow->valor/$exrow->valor_nota : 0;
					if ($id_nota) {
						$tr = $produto->getTotalProdutoNFTributado($id_nota);
						$tr = $tr*$percentual;
						$st = $produto->getTotalProdutoNFST($id_nota);	
						$st = $st*$percentual;
					} else if ($id_venda) {
						$tr = $produto->getTotalProdutoNFETributado($id_venda);
						$tr = $tr*$percentual;
						$st = $produto->getTotalProdutoNFEST($id_venda);	
						$st = $st*$percentual;
					}
				}				
				$vltrib += $tr;
					$p_vltrib += $tr;
				$vlst += $st;
					$p_vlst += $st;
				$vlservico += $sv;	
					$p_vlservico += $sv;
				$linha=array(
						 lang('DATA_EMISSAO')    => exibedata($exrow->data_emissao),
						 lang('NUMERO_NOTA')  => $numero_nota,
						 lang('TRIBUTADA')  => decimal($tr),
						 lang('ST')  => decimal($st),
						 lang('VALOR_SERVICO')  => decimal($sv),
						 lang('CIDADE')  => $cidade,
						 lang('VALOR_TOTAL')  => decimal($exrow->valor_total),
						 lang('PARC')  => $parcela,
						 lang('TPARC')  => $tparcela,
						 lang('VALOR_PARCELA')  => decimal($exrow->valor),
						 lang('VENCIMENTO')  => exibedata($exrow->data_pagamento),
						 lang('RECEBIMENTO')  => exibedata($exrow->data_fiscal),
						 lang('VALOR_PAGO')  => decimal($exrow->valor_pago),
						 lang('ISS_RETIDO')  => decimal($exrow->valor_iss_retido)
					);
					 
				$size = $pdf->addLine( $y, $linha );
				$y   += $size + 2;
				if($y > 170) {					
					$linha=array( 
						 lang('DATA_EMISSAO')    => '',
						 lang('NUMERO_NOTA')  => lang('TOTAL_PAG'),
						 lang('TRIBUTADA')  => decimal($p_vltrib),
						 lang('ST')  => decimal($p_vlst),
						 lang('VALOR_SERVICO')  => decimal($p_vlservico),
						 lang('CIDADE')  => '',
						 lang('VALOR_TOTAL')  => decimal($p_vltotal),
						 lang('PARC')  => '',
						 lang('TPARC')  => '',
						 lang('VALOR_PARCELA')  => decimal($p_vlparcela),
						 lang('VENCIMENTO')  => '',
						 lang('RECEBIMENTO')  => '',
						 lang('VALOR_PAGO')  => decimal($p_vlpago),
						 lang('ISS_RETIDO')  => decimal($p_vliss)
					);	
					$size = $pdf->addLine( $y, $linha, true );
					$p_vltrib = 0;
					$p_vlst = 0;
					$p_vlservico = 0;
					$p_vltotal = 0;
					$p_vlparcela = 0;
					$p_vlpago = 0;
					$p_vliss = 0;
					$tabela_final = $y-15; //AJUSTA A DISTANCA DO FINAL DA TABELA
					$tabela_inicio = $inicio;
					$linha_inicio = $tabela_inicio + 10;
					$y    = $linha_inicio;
					$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
					$pdf->AddPage();
					$pdf->addEmpresa($nome_empresa,$cidade_empresa);
					$pdf->subtitulo(exibeMesAno($mes_ano, true, true),$cidade_empresa);
				}
			}
			unset($exrow);
			$linha=array( 
						lang('DATA_EMISSAO')    => '',
						 lang('NUMERO_NOTA')  => '',
						 lang('TRIBUTADA')  => '',
						 lang('ST')  => '',
						 lang('VALOR_SERVICO')  => '',
						 lang('CIDADE')  => '',
						 lang('VALOR_TOTAL')  => '',
						 lang('PARC')  => '',
						 lang('TPARC')  => '',
						 lang('VALOR_PARCELA')  => '',
						 lang('VENCIMENTO')  => '',
						 lang('RECEBIMENTO')  => '',
						 lang('VALOR_PAGO')  => '',
						 lang('ISS_RETIDO')  => ''
					);
					 
			$size = $pdf->addLine( $y, $linha, true );
			$y   += $size + 2;
			$linha=array( 
						 lang('DATA_EMISSAO')    => '',
						 lang('NUMERO_NOTA')  => lang('TOTAL'),
						 lang('TRIBUTADA')  => decimal($vltrib),
						 lang('ST')  => decimal($vlst),
						 lang('VALOR_SERVICO')  => decimal($vlservico),
						 lang('CIDADE')  => '',
						 lang('VALOR_TOTAL')  => decimal($vltotal),
						 lang('PARC')  => '',
						 lang('TPARC')  => '',
						 lang('VALOR_PARCELA')  => decimal($vlparcela),
						 lang('VENCIMENTO')  => '',
						 lang('RECEBIMENTO')  => '',
						 lang('VALOR_PAGO')  => decimal($vlpago),
						 lang('ISS_RETIDO')  => decimal($vliss)
					);
					 
			$size = $pdf->addLine( $y, $linha, true );
			$y   += $size + 2;
			$tabela_final = $y-20; //AJUSTA A DISTANCA DO FINAL DA TABELA
			$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		} else {
			$pdf->AddPage();
			$pdf->addEmpresa(lang('BUSCAR_VAZIO'), " ");		
		}
	} else {		
		$pdf->AddPage();
		$pdf->addEmpresa(lang('SELECIONE_EMPRESA'), " ");
	}
	
	$pdf->Output();
?>