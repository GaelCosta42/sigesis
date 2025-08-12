<?php
  /**
   * Imprimir Proposta
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
	
	$pdf = new PDF_Impressao( 'P', 'mm', 'A4' );
	$pdf->setCabecalho(true);
	$pdf->setRodape(true);
	
	if (isset($_GET['id'])) {  
		
		$id_proposta = (post('id')) ? post('id') : get('id');
		$row_proposta = Core::getRowById("proposta", $id_proposta);
		$row_cadastro = Core::getRowById("cadastro", $row_proposta->id_cadastro);
		$row_responsavel = Core::getRowById("usuario", $row_proposta->id_responsavel);
		$apresentacao = getValue("conteudo", "conteudo", "id=".$row_proposta->id_apresentacao);
		$investimento = getValue("conteudo", "conteudo", "id=".$row_proposta->id_investimento);
		$referencias = getValue("conteudo", "conteudo", "id=".$row_proposta->id_referencia);
		$row_produtos = $contrato->getPropostasProdutos($id_proposta);
		$row_servicos = $contrato->getPropostasServicos($id_proposta);		
		$row_telefonia = $contrato->getPropostasTelefonia($id_proposta);		
		$row_pagamentos = $contrato->getPropostasPagamento($id_proposta);
		$valor_proposta = 0;
		
		$pdf->AddPage();
		$pdf->tamanho(-3);
		
		
		$inicio = 30;
		$const = 36;
		
		// INICIO DE UMA NOVA SECAO		
		$tabela_inicio = $const;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('NUMERO'))    => 15,
				 utf8_decode(lang('DATA_PROPOSTA'))  => 25,
				 utf8_decode(lang('RAZAO_SOCIAL'))  => 85,
				 utf8_decode(lang('CPF_CNPJ'))  => 28,
				 utf8_decode(lang('INSCRICAO'))  => 32 );
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('NUMERO'))    => "L",
					 utf8_decode(lang('DATA_PROPOSTA'))  => "L",
					 utf8_decode(lang('RAZAO_SOCIAL'))  => "L",
					 utf8_decode(lang('CPF_CNPJ'))  => "L",
					 utf8_decode(lang('INSCRICAO'))  => "L" );
					 
		$pdf->addLineFormat($cols);
		$linha=array( 
			 utf8_decode(lang('NUMERO'))    => utf8_decode($row_proposta->id),
			 utf8_decode(lang('DATA_PROPOSTA'))  => utf8_decode(exibedata($row_proposta->data_proposta)),
			 utf8_decode(lang('RAZAO_SOCIAL'))  => utf8_decode($row_cadastro->razao_social),
			 utf8_decode(lang('CPF_CNPJ'))  => utf8_decode(formatar_cpf_cnpj($row_cadastro->cpf_cnpj)),
			 utf8_decode(lang('INSCRICAO'))  => utf8_decode($row_cadastro->ie) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size - 1;
		$tabela_final = $y-$const;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		// FINAL DE UMA NOVA SECAO
		
		// INICIO DE UMA NOVA SECAO	
		$y = $pdf->novapagina($y, $inicio);	
		$tabela_inicio = $y+1;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('ENDERECO'))    => 190 );
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('ENDERECO'))    => "L");
					 
		$pdf->addLineFormat($cols);
		$linha=array( 
			 utf8_decode(lang('ENDERECO'))  => utf8_decode($row_cadastro->endereco.", ".$row_cadastro->numero." - ".$row_cadastro->bairro." - ".$row_cadastro->cidade."/".$row_cadastro->estado." - ".$row_cadastro->cep));
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size - 1;
		$tabela_final = $y-$const;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		// FINAL DE UMA NOVA SECAO
		
		// INICIO DE UMA NOVA SECAO
		$y = $pdf->novapagina($y, $inicio);
		$tabela_inicio = $y+1;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('CONTATO'))    => 60,
				 utf8_decode(lang('TELEFONE'))  => 60,
				 utf8_decode(lang('EMAIL'))  => 70);
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('CONTATO'))    => "L",
					 utf8_decode(lang('TELEFONE'))  => "L",
					 utf8_decode(lang('EMAIL'))  => "L" );
					 
		$pdf->addLineFormat($cols);
		$linha=array( 
			 utf8_decode(lang('CONTATO'))    => utf8_decode($row_cadastro->contato),
			 utf8_decode(lang('TELEFONE'))  => utf8_decode($row_cadastro->telefone." | ".$row_cadastro->celular),
			 utf8_decode(lang('EMAIL'))  => utf8_decode($row_cadastro->email) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size - 2;
		$tabela_final = $y-$const;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		// FINAL DE UMA NOVA SECAO	
		
		// INICIO DE UMA NOVA SECAO	
		$y = $pdf->novapagina($y, $inicio);
		$const += 12;				
		$tabela_inicio = $y+3;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('APRESENTACAO'))    => 190);
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('APRESENTACAO'))    => "L");
					 
		$pdf->addLineFormat($cols);
		$linha=array( 
			 utf8_decode(lang('APRESENTACAO'))    => utf8_decode($apresentacao) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size - 1;
		$tabela_final = $y-$const;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		// FINAL DE UMA NOVA SECAO
		
		// INICIO DE UMA NOVA SECAO	
		$y = $pdf->novapagina($y, $inicio);
		$const += 12;				
		$tabela_inicio = $y+3;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('INVESTIMENTO'))    => 190);
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('INVESTIMENTO'))    => "L");
					 
		$pdf->addLineFormat($cols);
		$linha=array( 
			 utf8_decode(lang('INVESTIMENTO'))    => utf8_decode($investimento) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size - 1;
		$tabela_final = $y-$const;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		// FINAL DE UMA NOVA SECAO
		
		// INICIO DE UMA NOVA SECAO	
		$y = $pdf->novapagina($y, $inicio);
		$const += 12;				
		$tabela_inicio = $y+3;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('REFERENCIAS'))    => 190);
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('REFERENCIAS'))    => "L");
					 
		$pdf->addLineFormat($cols);
		$linha=array( 
			 utf8_decode(lang('REFERENCIAS'))    => utf8_decode($referencias) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size - 1;
		$tabela_final = $y-$const;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		// FINAL DE UMA NOVA SECAO
		
		if($row_produtos) {
		
		// INICIO DE UMA NOVA SECAO	
		$y = $pdf->novapagina($y, $inicio);	
		$const += 12;			
		$tabela_inicio = $y+3;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('ITEM'))    => 15,
				 utf8_decode(lang('DESCRICAO_PRODUTOS'))  => 100,
				 utf8_decode(lang('QUANT'))  => 15,
				 utf8_decode(lang('VL_VENDA'))  => 25,
				 utf8_decode(lang('VL_TOTAL'))  => 35);
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('ITEM'))    => "L",
					 utf8_decode(lang('DESCRICAO_PRODUTOS'))  => "L",
					 utf8_decode(lang('QUANT'))  => "L",
					 utf8_decode(lang('VL_VENDA'))  => "L",
					 utf8_decode(lang('VL_TOTAL'))  => "L");
					 
		$pdf->addLineFormat($cols);
			$valor_total = 0;
			$item = 0;
			foreach ($row_produtos as $exrow) {	
				$item++;
				$valor_total += $exrow->valor_total;
				$valor_proposta += $exrow->valor_total;
				$linha=array( 
					 utf8_decode(lang('ITEM'))    => utf8_decode($item),
					 utf8_decode(lang('DESCRICAO_PRODUTOS'))  => utf8_decode($exrow->produto),
					 utf8_decode(lang('QUANT'))  => utf8_decode($exrow->quantidade),
					 utf8_decode(lang('VL_VENDA'))  => utf8_decode(moeda($exrow->valor_venda)),
					 utf8_decode(lang('VL_TOTAL'))  => utf8_decode(moeda($exrow->valor_total)) );
					 
				$size = $pdf->addLine( $y, $linha );
				$y   += $size + 2;
				
				if($y > 260) {		
					$tabela_final = $y-$const;
					$tabela_inicio = $inicio;
					$linha_inicio = $tabela_inicio + 6;
					$y    = $linha_inicio;
					$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
					$pdf->AddPage();
				}
			}
			unset($exrow);
			$linha=array( 
				 utf8_decode(lang('ITEM'))    => utf8_decode(""),
				 utf8_decode(lang('DESCRICAO_PRODUTOS'))  => utf8_decode(""),
				 utf8_decode(lang('QUANT'))  => utf8_decode(""),
				 utf8_decode(lang('VL_VENDA'))  => utf8_decode(lang('VL_TOTAL')),
				 utf8_decode(lang('VL_TOTAL'))  => utf8_decode(moeda($valor_total)) );
					 
			$size = $pdf->addLine( $y, $linha, true );
			$y   += $size + 2;
			$tabela_final = $y-$const;
			$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		}
		// FINAL DE UMA NOVA SECAO
		
		if($row_servicos) {
			
		// INICIO DE UMA NOVA SECAO	
		$y = $pdf->novapagina($y, $inicio);
		$const += 12;				
		$tabela_inicio = $y+3;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('ITEM'))    => 15,
				 utf8_decode(lang('DESCRICAO_SERVICOS'))  => 85,
				 utf8_decode(lang('QUANT'))  => 20,
				 utf8_decode(lang('VALOR'))  => 35,
				 utf8_decode(lang('VL_TOTAL'))  => 35);
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('ITEM'))    => "L",
					 utf8_decode(lang('DESCRICAO_SERVICOS'))  => "L",
					 utf8_decode(lang('QUANT'))  => "L",
					 utf8_decode(lang('VALOR'))  => "L",
					 utf8_decode(lang('VL_TOTAL'))  => "L");
					 
		$pdf->addLineFormat($cols);
			$valor_total = 0;
			$item = 0;
			foreach ($row_servicos as $exrow) {	
				$item++;
				$valor_total += $v = $exrow->valor*$exrow->quantidade;;
				$valor_proposta += $exrow->valor*$exrow->quantidade;;
				$linha=array( 
					 utf8_decode(lang('ITEM'))    => utf8_decode($item),
					 utf8_decode(lang('DESCRICAO_SERVICOS'))  => utf8_decode($exrow->descricao),
					 utf8_decode(lang('QUANT'))  => utf8_decode($exrow->quantidade),
					 utf8_decode(lang('VALOR'))  => utf8_decode(moeda($exrow->valor)),
					 utf8_decode(lang('VL_TOTAL'))  => utf8_decode(moeda($v)) );
					 
				$size = $pdf->addLine( $y, $linha );
				$y   += $size + 2;
				
				if($y > 260) {		
					$tabela_final = $y-$const;
					$tabela_inicio = $inicio;
					$linha_inicio = $tabela_inicio + 6;
					$y    = $linha_inicio;
					$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
					$pdf->AddPage();
				}
			}
			unset($exrow);
			$linha=array( 
				 utf8_decode(lang('ITEM'))    => utf8_decode(""),
				 utf8_decode(lang('DESCRICAO_SERVICOS'))  => utf8_decode(lang('VL_TOTAL')),
				 utf8_decode(lang('QUANT'))  => utf8_decode(""),
				 utf8_decode(lang('VALOR'))  => utf8_decode(""),
				 utf8_decode(lang('VL_TOTAL'))  => utf8_decode(moeda($valor_total)) );
					 
			$size = $pdf->addLine( $y, $linha, true );
			$y   += $size + 2;
			$tabela_final = $y-$const;
			$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		}
		// FINAL DE UMA NOVA SECAO
		
		if($row_telefonia) {
			
		// INICIO DE UMA NOVA SECAO	
			$valor_total = 0;
			foreach ($row_telefonia as $exrow) {
				$valor_total += $exrow->valor_integral;
				$valor_proposta += $exrow->valor_integral;
				$y = $pdf->novapagina($y, $inicio);
				$const += 12;				
				$tabela_inicio = $y+3;
				
				$linha_inicio = $tabela_inicio + 6;
				$y    = $linha_inicio;
				
				$cabecalho=array( 
						 utf8_decode(lang('PLANO_TELEFONIA'))    => 150,
						 utf8_decode(lang('LINHAS'))  => 40);
						 
				$pdf->definirCols($cabecalho, "L", false, 2);
				
				$cols=array( utf8_decode(lang('PLANO_TELEFONIA'))    => "L",
							 utf8_decode(lang('LINHAS'))  => "L");
							 
				$pdf->addLineFormat($cols);
				$linha=array( 
						 utf8_decode(lang('PLANO_TELEFONIA'))    => utf8_decode($exrow->plano_telefone),
						 utf8_decode(lang('LINHAS'))  => utf8_decode($exrow->linhas) );
						 
				$size = $pdf->addLine( $y, $linha );
				$y   += $size + 2;
				$tabela_final = $y-$const;
				$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
				$y = $pdf->novapagina($y, $inicio);
				$const += 12;				
				$tabela_inicio = $y;
				$linha_inicio = $tabela_inicio + 6;
				$y    = $linha_inicio;
				
				$cabecalho=array( 
						 utf8_decode(lang('PLANO_INTERNET'))    => 95,
						 utf8_decode(lang('ADICIONAIS'))  => 95);
						 
				$pdf->definirCols($cabecalho, "L", false, 0);
				
				$cols=array( utf8_decode(lang('PLANO_INTERNET'))    => "L",
							 utf8_decode(lang('ADICIONAIS'))  => "L");
							 
				$pdf->addLineFormat($cols);
				$linha=array( 
						 utf8_decode(lang('PLANO_INTERNET'))    => utf8_decode($exrow->plano_internet),
						 utf8_decode(lang('ADICIONAIS'))  => utf8_decode($exrow->adicionais) );
						 
				$size = $pdf->addLine( $y, $linha );
				$y   += $size + 2;
				$tabela_final = $y-$const;
				$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
				$y = $pdf->novapagina($y, $inicio);
				$const += 12;				
				$tabela_inicio = $y;
				$linha_inicio = $tabela_inicio + 6;
				$y    = $linha_inicio;
				
				$cabecalho=array( 
						 utf8_decode(lang('BENEFICIOS'))    => 190);
						 
				$pdf->definirCols($cabecalho, "L", false, 0);
				
				$cols=array( utf8_decode(lang('BENEFICIOS'))    => "L");
							 
				$pdf->addLineFormat($cols);
				$linha=array( 
						 utf8_decode(lang('BENEFICIOS'))    => utf8_decode($exrow->beneficios) );
						 
				$size = $pdf->addLine( $y, $linha );
				$y   += $size + 2;
				$tabela_final = $y-$const;
				$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
				$y = $pdf->novapagina($y, $inicio);
				$const += 12;				
				$tabela_inicio = $y;
				$linha_inicio = $tabela_inicio + 6;
				$y    = $linha_inicio;
				
				$cabecalho=array( 
						 utf8_decode(lang('VALOR_INTEGRAL'))    => 95,
						 utf8_decode(lang('VALOR_DESCONTO'))    => 55,
						 utf8_decode(lang('TAXA_INSTALACAO'))  => 40);
						 
				$pdf->definirCols($cabecalho, "L", false, 0);
				
				$cols=array( utf8_decode(lang('VALOR_INTEGRAL'))    => "L",
							 utf8_decode(lang('VALOR_DESCONTO'))    => "L",
							 utf8_decode(lang('TAXA_INSTALACAO'))  => "L");
							 
				$pdf->addLineFormat($cols);
				$linha=array( 
						 utf8_decode(lang('VALOR_INTEGRAL'))    => utf8_decode(moeda($exrow->valor_integral)),
						 utf8_decode(lang('VALOR_DESCONTO'))    => utf8_decode(moeda($exrow->valor_desconto)),
						 utf8_decode(lang('TAXA_INSTALACAO'))  => utf8_decode(moeda($exrow->taxa_instalacao)) );
						 
				$size = $pdf->addLine( $y, $linha );
				$y   += $size + 2;
				$tabela_final = $y-$const;
				$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
			}
			unset($exrow);
		}
		// FINAL DE UMA NOVA SECAO
		
		if($row_pagamentos) {
			
		// INICIO DE UMA NOVA SECAO	
		$y = $pdf->novapagina($y, $inicio);
		$const += 12;				
		$tabela_inicio = $y+3;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('ITEM'))    => 15,
				 utf8_decode(lang('CONDICAO_PAGAMENTO'))  => 75,
				 utf8_decode(lang('PARCELAS'))  => 15,
				 utf8_decode(lang('VL_PARCELA'))  => 25,
				 utf8_decode(lang('VL_DESCONTO'))  => 25,
				 utf8_decode(lang('VL_TOTAL'))  => 35);
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('ITEM'))    => "L",
					 utf8_decode(lang('CONDICAO_PAGAMENTO'))  => "L",
					 utf8_decode(lang('PARCELAS'))  => "L",
					 utf8_decode(lang('VL_PARCELA'))  => "L",
					 utf8_decode(lang('VL_DESCONTO'))  => "L",
					 utf8_decode(lang('VL_TOTAL'))  => "L");
					 
		$pdf->addLineFormat($cols);
			$item = 0;
			foreach ($row_pagamentos as $exrow) {	
				$item++;
				$linha=array( 
					 utf8_decode(lang('ITEM'))    => utf8_decode($item),
					 utf8_decode(lang('CONDICAO_PAGAMENTO'))  => utf8_decode($exrow->condicao),
					 utf8_decode(lang('PARCELAS'))  => utf8_decode($exrow->parcelas),
					 utf8_decode(lang('VL_PARCELA'))  => utf8_decode(moeda($exrow->valor_parcelas)),
					 utf8_decode(lang('VL_DESCONTO'))  => utf8_decode(moeda($row_proposta->valor_desconto)),
					 utf8_decode(lang('VL_TOTAL'))  => utf8_decode(moeda($exrow->valor_total)) );
					 
				$size = $pdf->addLine( $y, $linha );
				$y   += $size + 2;
				
				if($y > 260) {		
					$tabela_final = $y-$const;
					$tabela_inicio = $inicio;
					$linha_inicio = $tabela_inicio + 6;
					$y    = $linha_inicio;
					$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
					$pdf->AddPage();
				}
			}
			unset($exrow);
			$tabela_final = $y-$const;
			$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		}
		// FINAL DE UMA NOVA SECAO
		
		if($row_proposta->observacao) {
			
			// INICIO DE UMA NOVA SECAO
			$y = $pdf->novapagina($y, $inicio);	
			$const += 12;				
			$tabela_inicio = $y+3;
			$linha_inicio = $tabela_inicio + 6;
			$y    = $linha_inicio;
			
			$cabecalho=array( 
					 utf8_decode(lang('OBSERVACAO'))    => 190);
					 
			$pdf->definirCols($cabecalho, "L", false, 2);
			
			$cols=array( utf8_decode(lang('OBSERVACAO'))    => "L");
						 
			$pdf->addLineFormat($cols);
			$linha=array( 
				 utf8_decode(lang('OBSERVACAO'))    => utf8_decode($row_proposta->observacao) );
				 
			$size = $pdf->addLine( $y, $linha );
			$y   += $size + 2;
			$tabela_final = $y-$const;
			$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
			// FINAL DE UMA NOVA SECAO
		}
		
		
		// INICIO DE UMA NOVA SECAO	
		$y = $pdf->novapagina($y, $inicio);
		$const += 12;				
		$tabela_inicio = $y+3;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('RESPONSAVEL_PROPOSTA'))    => 70,
				 utf8_decode(lang('TELEFONE'))    => 50,
				 utf8_decode(lang('EMAIL'))  => 70);
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('RESPONSAVEL_PROPOSTA'))    => "L",
					 utf8_decode(lang('TELEFONE'))    => "L",
					 utf8_decode(lang('EMAIL'))  => "L" );
					 
		$pdf->addLineFormat($cols);
		$linha=array( 
			 utf8_decode(lang('RESPONSAVEL_PROPOSTA'))    => utf8_decode($row_responsavel->nome),
			 utf8_decode(lang('TELEFONE'))    => utf8_decode($row_responsavel->telefone),
			 utf8_decode(lang('EMAIL'))  => utf8_decode($row_responsavel->email) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size + 2;
		$tabela_final = $y-$const;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		// FINAL DE UMA NOVA SECAO	
		
		// INICIO DE UMA NOVA SECAO	
		$y = $pdf->novapagina($y, $inicio);	
		$tabela_inicio = $y;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('VALIDADE_PROPOSTA').": ".$row_proposta->validade)    => 95,
				 utf8_decode(lang('PRAZO_ENTREGA').": ".$row_proposta->entrega)  => 95);
				 
		$pdf->definirCols($cabecalho, "L", false, 0);
		
		$cols=array( 
				utf8_decode(lang('VALIDADE_PROPOSTA').": ".$row_proposta->validade)    => "L",
				utf8_decode(lang('PRAZO_ENTREGA').": ".$row_proposta->entrega)  => "L");
					 
		$pdf->addLineFormat($cols);
		$y   += $size - 7;
		$tabela_final = $y;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		// FINAL DE UMA NOVA SECAO
		
		// INICIO DE UMA NOVA SECAO	
		$y = $pdf->novapagina($y, $inicio);
		$const += 12;		
		$tabela_inicio = $y+3;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('VAZIO1'))    => 95,
				 utf8_decode(lang('VAZIO2'))  => 95 );
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('VAZIO1'))    => "C",
					 utf8_decode(lang('VAZIO2'))  => "C");
					 
		$pdf->addLineFormat($cols);
		$linha=array( 
			 utf8_decode(lang('VAZIO1'))    => utf8_decode(lang('VAZIO1')),
			 utf8_decode(lang('VAZIO2'))  => utf8_decode(lang('VAZIO1')));
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size + 4;
		$linha=array( 
			 utf8_decode(lang('VAZIO1'))    => utf8_decode(lang('TRACO')),
			 utf8_decode(lang('VAZIO2'))  => utf8_decode(lang('TRACO')));
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size + 2;
		$linha=array( 
			 utf8_decode(lang('VAZIO1'))    => utf8_decode(lang('GRUPO_TELECOM')),
			 utf8_decode(lang('VAZIO2'))  => utf8_decode($row_cadastro->razao_social));
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size + 2;
		$tabela_final = $y-$const;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		// FINAL DE UMA NOVA SECAO
		
		// INICIO DE UMA NOVA SECAO
		$y = $pdf->novapagina($y, $inicio);			
		$const += 12;
		$tabela_inicio = $y+1;
		$linha_inicio = $tabela_inicio + 6;
		$y    = $linha_inicio;
		
		$cabecalho=array( 
				 utf8_decode(lang('VAZIO1'))    => 95,
				 utf8_decode(lang('VAZIO2'))  => 95 );
				 
		$pdf->definirCols($cabecalho, "L", false, 2);
		
		$cols=array( utf8_decode(lang('VAZIO1'))    => "C",
					 utf8_decode(lang('VAZIO2'))  => "C");
					 
		$pdf->addLineFormat($cols);
		$linha=array( 
			 utf8_decode(lang('VAZIO1'))    => utf8_decode(lang('VALE1')),
			 utf8_decode(lang('VAZIO2'))  => utf8_decode(lang('SOLUTIONS1')) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size + 1;
		$linha=array( 
			 utf8_decode(lang('VAZIO1'))    => utf8_decode(lang('VALE2')),
			 utf8_decode(lang('VAZIO2'))  => utf8_decode(lang('SOLUTIONS2')) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size + 1;
		$linha=array( 
			 utf8_decode(lang('VAZIO1'))    => utf8_decode(lang('VALE3')),
			 utf8_decode(lang('VAZIO2'))  => utf8_decode(lang('SOLUTIONS3')) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size + 1;
		$linha=array( 
			 utf8_decode(lang('VAZIO1'))    => utf8_decode(lang('VALE4')),
			 utf8_decode(lang('VAZIO2'))  => utf8_decode(lang('SOLUTIONS4')) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size + 1;
		$linha=array( 
			 utf8_decode(lang('VAZIO1'))    => utf8_decode(lang('VALE5')),
			 utf8_decode(lang('VAZIO2'))  => utf8_decode(lang('SOLUTIONS5')) );
			 
		$size = $pdf->addLine( $y, $linha );
		$y   += $size + 2;
		$tabela_final = $y-$const;
		$pdf->addCols( $tabela_inicio , $tabela_final , $cabecalho);
		// FINAL DE UMA NOVA SECAO
		
		
	} else {		
		$pdf->AddPage();
		$pdf->addEmpresa(utf8_decode(lang('MSG_ERRO_PROPOSTA')), " ");
	}
	
	$pdf->Output();
?>