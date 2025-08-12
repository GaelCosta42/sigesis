<?php

/**
 * PDF - Nota Fiscal de Produto - Emissao
 *
 */

define('_VALID_PHP', true);
header('Content-Type: text/html; charset=utf-8');

require_once('enotas/eNotasGW.php');
require_once('init.php');

if (!$usuario->is_Todos())
	redirect_to('login.php');

use eNotasGW\Api\Exceptions as Exceptions;

eNotasGW::configure(array(
	'apiKey' => $enotas_apikey
));

$id = get('id');
$dg = get('debug');
$debug = ($dg == 1) ? true : false;

$row_notafiscal = Core::getRowById('nota_fiscal', $id);
$row_empresa = Core::getRowById('empresa', $row_notafiscal->id_empresa);
$row_cadastro = Core::getRowById('cadastro', $row_notafiscal->id_cadastro);

$id_enotas = $row_empresa->enotas;
$cnpj_contador = $row_empresa->cnpj_contador;

$duplicatas = 0;
if ($row_notafiscal->apresentar_duplicatas) {
	$duplicatas = array(
		'fatura' => array(
			'numero' => $id,
			'desconto' => round($row_notafiscal->valor_desconto,2),
			'valorOriginal' => round($row_notafiscal->valor_nota + $row_notafiscal->valor_desconto,2)
		),
		'parcelas' => array()
	);

	$row_receitas = $faturamento->obterReceitasNFeDuplicatas($id);
	if ($row_receitas) {
		$contador = 1;
		foreach ($row_receitas as $rrow) {
			$aux_contador = str_pad($contador++, 3, '0', STR_PAD_LEFT);
			$array_duplicatas = array(
				'numero' => $aux_contador,
				'valor' => round($rrow->valor,2),
				'vencimento' => $rrow->data_pagamento . 'T12:00:00Z'
			);
			$duplicatas['parcelas'][] = $array_duplicatas;
		}
	}
}

if ($core->emissor_producao) {
	$ambiente = 'Producao'; //'Producao' ou 'Homologacao'
	$idExterno = ($row_empresa->versao_emissao == 0) ? 'nfe-' . $id : 'nfe' . $row_empresa->versao_emissao . '-' . $id;
} else {
	$ambiente = 'Homologacao'; //'Producao' ou 'Homologacao'
	$idExterno = ($row_empresa->versao_emissao == 0) ? 'Hnfe-' . $id : 'Hnfe' . $row_empresa->versao_emissao . '-' . $id;
}

$tipooperacao = 'Saida'; // 'Entrada' ou 'Saida'
$naturezaoperacao = 'VENDA AO CONSUMIDOR';
$finalidade = null;
$devolucao = 0;

if ($row_notafiscal->cfop) {
	$row_cfop = $produto->getCFOP($row_notafiscal->cfop);
	$tipooperacao = (strtolower($row_cfop->operacao_e_s) == 'e') ? 'Entrada' : 'Saida';
	$naturezaoperacao = sanitize($row_notafiscal->natureza_operacao);
	$finalidade = sanitize($row_cfop->finalidade);
	$devolucao = $row_cfop->devolucao;
}

$naturezaoperacao = ($row_notafiscal->nota_exportacao) ? "Exportacao" : $naturezaoperacao;
$tipoPessoa = ($row_cadastro->tipo == 1) ? 'J' : 'F';
$enviarPorEmail = ($row_cadastro->email) ? true : false;
$chaveacesso = ($row_notafiscal->nfe_referenciada) ? true : false;
$valor_produtos = 0;

$razao_social = $row_cadastro->razao_social;
$cpf_cnpj = limparNumero($row_cadastro->cpf_cnpj);

if (strlen($cpf_cnpj) != 14 and $tipoPessoa == 'J')
	Filter::$msgs['cpf_cnpj'] = 'ERRO NO CNPJ DO CLIENTE (deve ter 14 digitos): ' . $cpf_cnpj;

if (strlen($cpf_cnpj) != 11 and $tipoPessoa == 'F')
	Filter::$msgs['cpf_cnpj'] = 'ERRO NO CPF DO CLIENTE (deve ter 11 digitos): ' . $cpf_cnpj;

$cep = limparNumero($row_cadastro->cep);
if (strlen($cep) != 8 and !$row_notafiscal->nota_exportacao)
	Filter::$msgs['cep'] = 'ERRO NO CEP DO ENDERECO DO CLIENTE (deve ter 8 digitos): ' . $cep;

$complemento = ($row_cadastro->complemento) ? $row_cadastro->complemento : null;

if (empty(Filter::$msgs)) {
	try {
		if ($row_notafiscal->fiscal == 1 && $row_notafiscal->status_enotas != "Negada") {
			if ($row_notafiscal->chaveacesso && $row_notafiscal->fiscal && $row_notafiscal->status_enotas == "Autorizada") {
				$pdf = eNotasGW::$NFeProdutoApi->downloadPdf($id_enotas, $idExterno);
				header('Content-Type: application/pdf');
				header('Content-Disposition: inline; filename="' . rawurlencode('nfe-' . $idExterno . '.pdf') . '"');
				header('Cache-Control: private, max-age=0, must-revalidate');
				header('Pragma: public');
				echo $pdf;
			} else {
				$retorno = eNotasGW::$NFeProdutoApi->consultar($id_enotas, $idExterno);
				if ($retorno->status == 'Autorizada') {
					$numero_nota = $retorno->numero . '-' . $retorno->serie;
					$dataRetornoAPI = new DateTime($retorno->dataEmissao);
					$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
					$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
					if (intval($retorno->numero) > 0) {
						$data = array(
							'numero_nota' => $numero_nota,
							'numero' => $retorno->numero,
							'serie' => $retorno->serie,
							'chaveacesso' => $retorno->chaveAcesso,
							'link_danfe' => $retorno->linkDanfe,
							'link_download_xml' => $retorno->linkDownloadXml,
							'status_enotas' => $retorno->status,
							'motivo_status' => $retorno->motivoStatus,
							'contingencia' => ($retorno->forcarEmissaoContingencia && empty($retorno->dataAutorizacao)) ? 1 : 0,
							'data_emissao' => $data_emissao,
							'data_entrada' => $data_emissao,
							'fiscal' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->update("nota_fiscal", $data, "id=" . $id);
						$pdf = eNotasGW::$NFeProdutoApi->downloadPdf($id_enotas, $idExterno);
						header('Content-Type: application/pdf');
						header('Content-Disposition: inline; filename="' . rawurlencode('nfe-' . $idExterno . '.pdf') . '"');
						header('Cache-Control: private, max-age=0, must-revalidate');
						header('Pragma: public');
						echo $pdf;
					}
				} else {
					$numero_nota = (isset($retorno->numero)) ? $retorno->numero . '-' . $retorno->serie : "";
					if (isset($retorno->dataEmissao)) {
						$dataRetornoAPI = new DateTime($retorno->dataEmissao);
						$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
						$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
					}
					$data = array(
						'numero_nota' => $numero_nota,
						'numero' => (isset($retorno->numero)) ? $retorno->numero : 0,
						'serie' => (isset($retorno->serie)) ? $retorno->serie : 0,
						'chaveacesso' => (isset($retorno->chaveAcesso)) ? $retorno->chaveAcesso : "",
						'link_danfe' => (isset($retorno->linkDanfe)) ? $retorno->linkDanfe : "",
						'link_download_xml' => (isset($retorno->linkDownloadXml)) ? $retorno->linkDownloadXml : "",
						'status_enotas' => (isset($retorno->status)) ? $retorno->status : "",
						'motivo_status' => (isset($retorno->motivoStatus)) ? $retorno->motivoStatus : "",
						'contingencia' => (isset($retorno->forcarEmissaoContingencia) && $retorno->forcarEmissaoContingencia && empty($retorno->dataAutorizacao)) ? 1 : 0,
						'fiscal' => '1',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					if (isset($data_emissao)) {
						$data['data_emissao'] = $data_emissao;
						$data['data_entrada'] = $data_emissao;
					}
					$db->update("nota_fiscal", $data, "id=" . $id);

					echo "</br>--- status: " . $retorno->status . " ---</br>";
					$dataRetornoAPI = new DateTime($retorno->dataEmissao);
					$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
					$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
					echo "</br>--- dataEmissao: " . exibedata($data_emissao) . " ---</br>";
					$dataCriacao = substr(sanitize($retorno->dataCriacao), 0, 10);
					echo "</br>--- dataCriacao: " . exibedata($dataCriacao) . " ---</br>";
					$dataUltimaAlteracao = substr(sanitize($retorno->dataUltimaAlteracao), 0, 10);
					echo "</br>--- dataUltimaAlteracao: " . exibedata($dataUltimaAlteracao) . " ---</br>";
				}
			}
		} else {
			$telefone = (validaTelefone($row_cadastro->telefone)) ? validaTelefone($row_cadastro->telefone) : "";
			$telefone = ($telefone == "" && validaTelefone($row_cadastro->telefone2)) ? validaTelefone($row_cadastro->telefone2) : $telefone;
			$telefone = ($telefone == "" && validaTelefone($row_cadastro->celular)) ? validaTelefone($row_cadastro->celular) : $telefone;

			$array_itens = array();
			$array_cliente = array(
				'tipoPessoa' => $tipoPessoa,
				'nome' => $row_cadastro->razao_social,
				'email' => ($row_cadastro->email) ? $row_cadastro->email : null,
				'telefone' => ($row_notafiscal->nota_exportacao) ? null : $telefone,
				'cpfCnpj' => ($row_notafiscal->nota_exportacao) ? null : $cpf_cnpj,
				'endereco' => array(
					'uf' => ($row_notafiscal->nota_exportacao) ? 'EX' : $row_cadastro->estado,
					'cidade' => ($row_notafiscal->nota_exportacao) ? 'Exterior' : obterCodigoIbgeCidade($row_cadastro->cidade, $row_cadastro->estado),
					'logradouro' => $row_cadastro->endereco,
					'numero' => limparNumero($row_cadastro->numero),
					'complemento' => ($row_notafiscal->nota_exportacao) ? null : $complemento,
					'bairro' => ($row_notafiscal->nota_exportacao) ? null : $row_cadastro->bairro,
					'cep' => ($row_notafiscal->nota_exportacao) ? null : $cep,
					'pais' => ($row_notafiscal->nota_exportacao) ? $row_notafiscal->pais_exportacao : '1058'
				)
			);
			if ($row_notafiscal->nota_exportacao) unset($array_cliente['tipoPessoa']);
			$ie = intval(limparNumero($row_cadastro->ie));
			if ($ie && $tipoPessoa == 'J') {
				$array_cliente['inscricaoEstadual'] = $row_cadastro->ie;
			} else if ($row_cadastro->ie == 'ISENTO' || $row_cadastro->ie == 'ISENTA') {
				$array_cliente['inscricaoEstadual'] = 'isento';
			} else {
				$array_cliente['inscricaoEstadual'] = null;
			}
			$retorno_row = $produto->getProdutosNota($id);
			if ($retorno_row) {
				$count_itens = 0;
				foreach ($retorno_row as $exrow) {
					$quantidade = floatval($exrow->quantidade);
					$valor_desconto = round(floatval($exrow->valor_desconto),2);
					//$valor_unitario = round(floatval($exrow->valor_unitario),2);
					$valor_unitario = floatval($exrow->valor_unitario);
					$valor_prd = round($valor_unitario - $valor_desconto,2);
					$valor_total = round(floatval($valor_prd * $quantidade),2);
					$outras_despesas = round(floatval($exrow->outrasDespesasAcessorias),2);
					$nome_produto = $exrow->nome;

					$cfop = ($exrow->cfop) ? $exrow->cfop : $exrow->cfop_produto;
					$cfop = ($cfop) ? $cfop : $exrow->cfop_produto_original;
					$cfop = ($cfop) ? $cfop : $row_notafiscal->cfop;
					$cfop = ($row_notafiscal->nota_exportacao) ? $row_notafiscal->cfop : $cfop;

					$ncm = ($exrow->ncm) ? $exrow->ncm : $exrow->ncm_produto;
					$ncm = limparNumero($ncm);
					$cest = ($exrow->cest) ? $exrow->cest : $exrow->cest_produto;
					$cest = ($cest) ? $cest : null;
					$codigo = ($exrow->codigo) ? $exrow->codigo : $exrow->id_produto;
					$unidade = ($exrow->unidade) ? $exrow->unidade : $exrow->unidade_produto;
					$unidade_tributavel = ($exrow->unidade_tributavel) ? $exrow->unidade_tributavel : null;
					$icms_cst = ($exrow->icms_cst) ? $exrow->icms_cst : '102';
					$icms_percentual = ($exrow->icms_percentual) ? round(floatval($exrow->icms_percentual), 2) : 12;
					$csosn = intval($icms_cst);
					if ($csosn == 103 or $csosn == 203 or $csosn == 300 or $csosn == 400) {
						$icms_percentual = 0;
					}

					$icms_base = round(floatval($exrow->icms_base),2);
					$icms_percentual_mva_st = round(floatval($exrow->icms_percentual_mva_st),2);
					$icms_st_base = ($exrow->icms_st_base) ? round(floatval($exrow->icms_st_base),2) : round((($icms_base / 100) * $icms_percentual_mva_st) + $icms_base,2);
					$icms_st_percentual = round(floatval($exrow->icms_st_percentual),2);
					$icms_st_valor = ($exrow->icms_st_valor) ? round(floatval($exrow->icms_st_valor),2) : round(($icms_st_base / 100) * $icms_st_percentual,2);

					$origem = intval($exrow->origem);
					$icms_mod_st = ($exrow->icms_mod_st) ? intval($exrow->icms_mod_st) : 4;

					$pis_cst = ($exrow->pis_cst) ? $exrow->pis_cst : '07';
					$pis_base = round(floatval($exrow->pis_base),2);
					$pis_percentual = round(floatval($exrow->pis_percentual),2);
					$cofins_cst = ($exrow->cofins_cst) ? $exrow->cofins_cst : '07';
					$situacaoTibutariaParaIPI = ($exrow->ipi_saida_codigo) ? $exrow->ipi_saida_codigo : '0';
					$situacaoTibutariaParaIPI = ($row_notafiscal->nota_exportacao) ? '999' : $situacaoTibutariaParaIPI;
					$cofins_base = round(floatval($exrow->cofins_base),2);
					$cofins_percentual = round(floatval($exrow->cofins_percentual),2);
					$ipi_produto = ($exrow->ipi_percentual) ? round(floatval($exrow->ipi_percentual),2) : round(floatval($exrow->ipi_produto),2);

					if ($exrow->anp) {
						$count_itens++;
						$array_itens[] = array(
							'cfop' => $cfop,
							'codigo' => $codigo,
							'descricao' => $nome_produto,
							'ncm' => $ncm,
							'cest' => $cest,
							'quantidade' => $quantidade,
							'unidadeMedida' => $unidade,
							'unidadeMedidaTributavel' => $unidade_tributavel,
							'valorUnitario' => $valor_unitario,
							'tipoArredondamento' => 'ABNT_NBR_5891',
							'numeroPedidoCompra' => $exrow->numero_pedido_compra,
							'itemPedidoCompra' => intval($exrow->item_pedido_compra),
							'valorTotal' => $valor_total,
							'descontos' => $valor_desconto,
							'outrasDespesas' => $outras_despesas,
							'impostos' => array(
								'percentualAproximadoTributos' => array(
									'simplificado' => array(
										'percentual' => $icms_percentual
									),
									'fonte' => 'IBPT'
								),
								'icms' => array(
									'situacaoTributaria' => $icms_cst,
									'modalidadeBaseCalculo' => 0,
									'origem' => $origem,
									'baseCalculo' => $icms_base,
									'aliquota' => $icms_percentual,
									'baseCalculoST' => $icms_st_base,
									'aliquotaST' => $icms_st_percentual,
									'valorST' => $icms_st_valor,
									'baseCalculoSTDestino' => 0.00,
									'modalidadeBaseCalculoST' => $icms_mod_st,
									'percentualMargemValorAdicionadoST' => $icms_percentual_mva_st,
									'percentualReducaoBaseCalculoST' => 0.00,
									'aliquotaCreditoSimplesNacional' => $icms_percentual,
									'valorCreditoSimplesNacional' => 0
								),
								'icmsST' => array(
									'situacaoTributaria' => $icms_cst,
									'origem' => $origem,
									'baseCalculoST' => $icms_st_base,
									'valorST' => $icms_st_valor,
									'baseCalculoSTDestino' => 0.00,
									'valorSTDestino' => 0.00
								),
								'pis' => array(
									'situacaoTributaria' => $pis_cst,
									'porAliquota' => array(
										'baseCalculo' => $pis_base,
										'aliquota' => $pis_percentual
									)
								),
								'cofins' => array(
									'situacaoTributaria' => $cofins_cst,
									'porAliquota' => array(
										'baseCalculo' => $cofins_base,
										'aliquota' => $cofins_percentual
									)
								),
								'ipi' => array(
									'situacaoTributaria' => $situacaoTibutariaParaIPI,
									'porAliquota' => array(
										'aliquota' => $ipi_produto
									)
								)
							),
							'combustivel' => array(
								'codigoProdutoANP' => $exrow->anp,
								'percentualGasNatural' => 100,
								'valorDePartida' => round($exrow->valor_partida,2),
								'codif' => null,
								'quantidadeFaturadaTempAmbiente' => $quantidade,
								'ufConsumo' => 'MG',
								'cide' => array(
									'quantidadeBaseCalculo' => $quantidade,
									'valorAliquota' => 0.0000,
									'valor' => $valor_prd
								)
							)
						);
					} else {
						$count_itens++;
						$array_itens[] = array(
							'cfop' => $cfop,
							'codigo' => $codigo,
							'descricao' => $nome_produto,
							'ncm' => $ncm,
							'cest' => $cest,
							'quantidade' => $quantidade,
							'unidadeMedida' => $unidade,
							'unidadeMedidaTributavel' => $unidade_tributavel,
							'valorUnitario' => $valor_unitario,
							'tipoArredondamento' => 'ABNT_NBR_5891',
							'numeroPedidoCompra' => $exrow->numero_pedido_compra,
							'itemPedidoCompra' => intval($exrow->item_pedido_compra),
							'valorTotal' => $valor_total,
							'descontos' => $valor_desconto,
							'outrasDespesas' => $outras_despesas,
							'impostos' => array(
								'percentualAproximadoTributos' => array(
									'simplificado' => array(
										'percentual' => $icms_percentual
									),
									'fonte' => 'IBPT'
								),
								'icms' => array(
									'situacaoTributaria' => $icms_cst,
									'modalidadeBaseCalculo' => 0,
									'origem' => $origem,
									'baseCalculo' => $icms_base,
									'aliquota' => $icms_percentual,
									'baseCalculoST' => $icms_st_base,
									'aliquotaST' => $icms_st_percentual,
									'valorST' => $icms_st_valor,
									'baseCalculoSTDestino' => 0.00,
									'modalidadeBaseCalculoST' => $icms_mod_st,
									'percentualMargemValorAdicionadoST' => $icms_percentual_mva_st,
									'percentualReducaoBaseCalculoST' => 0.00,
									'aliquotaCreditoSimplesNacional' => $icms_percentual,
									'valorCreditoSimplesNacional' => 0
								),
								'pis' => array(
									'situacaoTributaria' => $pis_cst,
									'porAliquota' => array(
										'baseCalculo' => $pis_base,
										'aliquota' => $pis_percentual
									)
								),
								'cofins' => array(
									'situacaoTributaria' => $cofins_cst,
									'porAliquota' => array(
										'baseCalculo' => $cofins_base,
										'aliquota' => $cofins_percentual
									)
								),
								'ipi' => array(
									'situacaoTributaria' => $situacaoTibutariaParaIPI,
									'porAliquota' => array(
										'aliquota' => $ipi_produto
									)
								)
							)
						);
					}

					if ($row_notafiscal->nota_exportacao) {
						unset($array_itens[$count_itens - 1]['impostos']['ipi']['porAliquota']);
					}

					if (!$exrow->numero_pedido_compra || $exrow->numero_pedido_compra == 0) {
						unset($array_itens[$count_itens - 1]['numeroPedidoCompra']);
						unset($array_itens[$count_itens - 1]['itemPedidoCompra']);
					}

					if ($exrow->anp && ($icms_cst == '060' || $icms_cst == '60')) {
						unset($array_itens[$count_itens - 1]['impostos']['icms']);
					} elseif ($exrow->anp) {
						unset($array_itens[$count_itens - 1]['impostos']['icmsST']);
					}

					if ($exrow->anp && $exrow->anp != '210203001') {
						unset($array_itens[$count_itens - 1]['combustivel']['percentualGasNatural']);
					}

					if ($icms_cst == '900' || $icms_cst == '0900') {
						unset($array_itens[$count_itens - 1]['impostos']['icms']['valorST']);
						unset($array_itens[$count_itens - 1]['impostos']['icmsST']['valorST']);
					}
				}
				unset($exrow);
			}

			$nota_simples = array(
				'id' => $idExterno,
				'ambienteEmissao' => $ambiente,  //'Producao' ou 'Homologacao'
				'tipoOperacao' => $tipooperacao,
				'naturezaOperacao' => $naturezaoperacao,
				'consumidorFinal' => true,
				'indicadorPresencaConsumidor' => 'OperacaoPresencial',  //'OperacaoPelaInternet' ou 'OperacaoPelaInternet' ou 'NaoSeAplica'
				'cliente' => $array_cliente,
				'enviarPorEmail' => $enviarPorEmail,
				'itens' => $array_itens,
				'valorTotal' => round($row_notafiscal->valor_nota,2),
				'informacoesAdicionais' => $row_notafiscal->inf_adicionais
			);

			if ($row_notafiscal->nota_exportacao) {
				unset($nota_simples['consumidorFinal']);
				unset($nota_simples['indicadorPresencaConsumidor']);
			}

			if ($row_notafiscal->dataSaidaEntrada && $row_notafiscal->dataSaidaEntrada != '0000-00-00') {
				$nota_simples['dataSaidaEntrada'] = $row_notafiscal->dataSaidaEntrada . 'T12:00:00Z';
			}

			if ($duplicatas) {
				$nota_simples['cobranca'] = $duplicatas;
			}

			if ($cnpj_contador) {
				$array_contador = array();
				$array_contador[] = array(
					'cpfCnpj' => $cnpj_contador
				);
				$nota_simples['autorizacaoDownloadXml'] = $array_contador;
			}

			if ($finalidade) {
				$nota_simples['finalidade'] = $finalidade;
			}

			$id_transporte = getValue('id', 'nota_fiscal_transporte', 'id_nota=' . $id);
			if ($id_transporte > 0) {
				$row_transporte = Core::getRowById("nota_fiscal_transporte", $id_transporte);

				if ($row_notafiscal->nota_exportacao) {
					$frete = array(
						'modalidade' => $row_transporte->modalidade,
						'valor' => round($row_notafiscal->valor_frete,2)
					);
					$especie = ($row_transporte->especie) ? $row_transporte->especie : null;
					$marca = ($row_transporte->marca) ? $row_transporte->marca : "Sem Marca";
					$quantidade = ($row_transporte->quantidade > 0) ? $row_transporte->quantidade : 1;
					$quantidade = floatval($quantidade);
					$array_transporte = array(
						'frete' => $frete,
						'volume' => array(
							'quantidade' => $quantidade,
							'especie' => $especie,
							'marca' => $marca,
							'numeracao' => '0001',
							'pesoLiquido' => $row_transporte->pesoliquido,
							'pesoBruto' => $row_transporte->pesobruto
						)
					);
				} else {

					$tipoPessoaDestinatario = ($row_transporte->tipopessoadestinatario) ? $row_transporte->tipopessoadestinatario : $tipoPessoa;
					$cpfCnpjDestinatario = ($row_transporte->cpfcnpjdestinatario) ? limparNumero($row_transporte->cpfcnpjdestinatario) : $cpf_cnpj;
					$uf = ($row_transporte->uf) ? $row_transporte->uf : $row_cadastro->estado;
					$cidade = ($row_transporte->cidade) ? $row_transporte->cidade : $row_cadastro->cidade;
					$logradouro = ($row_transporte->logradouro) ? $row_transporte->logradouro : $row_cadastro->endereco;
					$numero = ($row_transporte->numero) ? $row_transporte->numero : $row_cadastro->numero;
					$complemento = ($row_transporte->complemento) ? $row_transporte->complemento : $row_cadastro->complemento;
					$bairro = ($row_transporte->bairro) ? $row_transporte->bairro : $row_cadastro->bairro;
					$cep = ($row_transporte->cep) ? limparNumero($row_transporte->cep) : limparNumero($row_cadastro->cep);
					$complemento = ($complemento) ? $complemento : null;
					$enderecoentrega = array(
						'nome' => ($cpfCnpjDestinatario == $row_cadastro->cpf_cnpj) ? $row_cadastro->nome : "",
						'tipoPessoaDestinatario' => $tipoPessoaDestinatario,
						'cpfCnpjDestinatario' => ($row_notafiscal->nota_exportacao) ? '' : $cpfCnpjDestinatario,
						'uf' => ($row_notafiscal->nota_exportacao) ? 'EX' : $uf,
						'cidade' => $cidade,
						'logradouro' => cleanSanitize($logradouro),
						'numero' => $numero,
						'complemento' => $complemento,
						'bairro' => $bairro,
						'cep' => $cep
					);
					if ($row_transporte->trans_cpfcnpj) {
						$transportadora = array(
							'usarDadosEmitente' => (limparNumero($row_transporte->trans_cpfcnpj) == limparNumero($row_empresa->cnpj)) ? true : false,
							'tipoPessoa' => $row_transporte->trans_tipopessoa,
							'cpfCnpj' => limparNumero($row_transporte->trans_cpfcnpj),
							'nome' => cleanSanitize($row_transporte->trans_nome),
							'inscricaoEstadual' => limparNumero($row_transporte->trans_inscricaoestadual),
							'enderecoCompleto' => cleanSanitize($row_transporte->trans_endereco),
							'cidade' => $row_transporte->trans_cidade,
							'uf' => $row_transporte->trans_uf
						);
						$frete = array(
							'modalidade' => $row_transporte->modalidade,
							'valor' => round($row_notafiscal->valor_frete,2)
						);
						$veiculo = array(
							'placa' => $row_transporte->veiculo_placa,
							'uf' => $row_transporte->veiculo_uf
						);
					} else {
						$transportadora = null;
						$frete = array(
							'modalidade' => $row_transporte->modalidade,
							'valor' => round($row_notafiscal->valor_frete,2)
						);
					}

					$especie = ($row_transporte->especie) ? $row_transporte->especie : null;
					$marca = ($row_transporte->marca) ? $row_transporte->marca : null;
					$quantidade = ($row_transporte->quantidade > 0) ? $row_transporte->quantidade : 1;
					$quantidade = floatval($quantidade);
					$array_transporte = array(
						'frete' => $frete,
						'transportadora' => $transportadora,
						'veiculo' => $veiculo,
						'volume' => array(
							'especie' => $especie,
							'marca' => $marca,
							'quantidade' => $quantidade,
							'pesoLiquido' => $row_transporte->pesoliquido,
							'pesoBruto' => $row_transporte->pesobruto
						),
						'enderecoEntrega' => $enderecoentrega
					);
				}
				$nota_simples['transporte'] = $array_transporte;
			}
			if ($chaveacesso) {
				$array_nfeReferenciada = array();
				$array_nfeReferenciada[] = array(
					'chaveAcesso' => $row_notafiscal->nfe_referenciada
				);
				$nota_simples['nfeReferenciada'] = $array_nfeReferenciada;
			}
			if ($debug) {
				echo "ID EMPRESA: $id_enotas </br>";
				echo "</br>--- INICIO NOTA NORMAL ---</br>";
				$json_nota = json_encode($nota_simples);
				echo $json_nota;
				echo "</br>--- FIM NOTA NORMAL ---</br>";
			}

			$enota = eNotasGW::$NFeProdutoApi->emitir($id_enotas, $nota_simples);
			sleep(15);
			$retorno = eNotasGW::$NFeProdutoApi->consultar($id_enotas, $idExterno);

			if ($retorno->status == 'Autorizada') {
				$numero_nota = $retorno->numero . '-' . $retorno->serie;
				$dataRetornoAPI = new DateTime($retorno->dataEmissao);
				$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
				$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
				if (intval($retorno->numero) > 0) {
					$data = array(
						'numero_nota' => $numero_nota,
						'numero' => $retorno->numero,
						'serie' => $retorno->serie,
						'chaveacesso' => $retorno->chaveAcesso,
						'link_danfe' => $retorno->linkDanfe,
						'link_download_xml' => $retorno->linkDownloadXml,
						'status_enotas' => $retorno->status,
						'motivo_status' => $retorno->motivoStatus,
						'contingencia' => ($retorno->forcarEmissaoContingencia && empty($retorno->dataAutorizacao)) ? 1 : 0,
						'data_emissao' => $data_emissao,
						'data_entrada' => $data_emissao,
						'fiscal' => '1',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$db->update("nota_fiscal", $data, "id=" . $id);
					if (!$debug) {
						redirect_to("index.php?do=notafiscal&acao=visualizar&id=" . $id);
					}
				}
			} else {
				sleep(15);
				echo "<b/>Consulta para buscar retorno NF-e NORMAL:</b></br>";
				$retorno = eNotasGW::$NFeProdutoApi->consultar($id_enotas, $idExterno);
				echo "Envio da consulta para o ID: $id </br>";
				echo "ID da empresa: $id_enotas </br></br>";
				echo 'STATUS: ';
				echo $retorno->status;
				echo '</br>';
				echo 'MOTIVO STATUS: ';
				echo $retorno->motivoStatus;
				echo '</br>';
				if ($debug) {
					echo "</br>--- RETORNO COMPLETO ---</br>";
					echo json_encode($retorno);
					echo "</br>--- FIM RETORNO ---</br>";
				}
				if ($retorno->status == 'Autorizada') {
					$numero_nota = $retorno->numero . '-' . $retorno->serie;
					$dataRetornoAPI = new DateTime($retorno->dataEmissao);
					$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
					$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
					if (intval($retorno->numero) > 0) {
						$data = array(
							'numero_nota' => $numero_nota,
							'numero' => $retorno->numero,
							'serie' => $retorno->serie,
							'chaveacesso' => $retorno->chaveAcesso,
							'link_danfe' => $retorno->linkDanfe,
							'link_download_xml' => $retorno->linkDownloadXml,
							'status_enotas' => $retorno->status,
							'motivo_status' => $retorno->motivoStatus,
							'contingencia' => ($retorno->forcarEmissaoContingencia && empty($retorno->dataAutorizacao)) ? 1 : 0,
							'data_emissao' => $data_emissao,
							'data_entrada' => $data_emissao,
							'fiscal' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->update("nota_fiscal", $data, "id=" . $id);
						if (!$debug) {
							redirect_to("index.php?do=notafiscal&acao=visualizar&id=" . $id);
						}
					}
				} else {
					$numero_nota = (isset($retorno->numero)) ? $retorno->numero . '-' . $retorno->serie : "";
					if (isset($retorno->dataEmissao)) {
						$dataRetornoAPI = new DateTime($retorno->dataEmissao);
						$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
						$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
					}
					$data = array(
						'numero_nota' => $numero_nota,
						'numero' => (isset($retorno->numero)) ? $retorno->numero : 0,
						'serie' => (isset($retorno->serie)) ? $retorno->serie : 0,
						'chaveacesso' => (isset($retorno->chaveAcesso)) ? $retorno->chaveAcesso : "",
						'link_danfe' => (isset($retorno->linkDanfe)) ? $retorno->linkDanfe : "",
						'link_download_xml' => (isset($retorno->linkDownloadXml)) ? $retorno->linkDownloadXml : "",
						'status_enotas' => (isset($retorno->status)) ? $retorno->status : "",
						'motivo_status' => (isset($retorno->motivoStatus)) ? $retorno->motivoStatus : "",
						'contingencia' => (isset($retorno->forcarEmissaoContingencia) && $retorno->forcarEmissaoContingencia && empty($retorno->dataAutorizacao)) ? 1 : 0,
						'fiscal' => '1',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					if (isset($data_emissao)) {
						$data['data_emissao'] = $data_emissao;
						$data['data_entrada'] = $data_emissao;
					}
					$db->update("nota_fiscal", $data, "id=" . $id);

					echo "</br>--- status: " . $retorno->status . " ---</br>";
					$dataRetornoAPI = new DateTime($retorno->dataEmissao);
					$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
					$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
					echo "</br>--- dataEmissao: " . exibedata($data_emissao) . " ---</br>";
					$dataCriacao = substr(sanitize($retorno->dataCriacao), 0, 10);
					echo "</br>--- dataCriacao: " . exibedata($dataCriacao) . " ---</br>";
					$dataUltimaAlteracao = substr(sanitize($retorno->dataUltimaAlteracao), 0, 10);
					echo "</br>--- dataUltimaAlteracao: " . exibedata($dataUltimaAlteracao) . " ---</br>";
				}
			}
		}
	} catch (Exceptions\invalidApiKeyException $ex) {
		echo 'Erro de autenticação: </br></br>';
		echo $ex->getMessage();
	} catch (Exceptions\unauthorizedException $ex) {
		echo 'Acesso negado: </br></br>';
		echo $ex->getMessage();
	} catch (Exceptions\apiException $ex) {
		echo '<b>Erro de validação:</b> </br></br>';
		$msg = $ex->getMessage();
		echo '<b>Mensagem:</b> </br></br>';
		if (validaTexto($msg, 'Autorizada')) {
			$retorno = eNotasGW::$NFeProdutoApi->consultar($id_enotas, $idExterno);
			if ($retorno->status == 'Autorizada') {
				$numero_nota = $retorno->numero . '-' . $retorno->serie;
				$dataRetornoAPI = new DateTime($retorno->dataEmissao);
				$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
				$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
				if (intval($retorno->numero) > 0) {
					$data = array(
						'numero_nota' => $numero_nota,
						'numero' => $retorno->numero,
						'serie' => $retorno->serie,
						'chaveacesso' => $retorno->chaveAcesso,
						'link_danfe' => $retorno->linkDanfe,
						'link_download_xml' => $retorno->linkDownloadXml,
						'status_enotas' => $retorno->status,
						'motivo_status' => $retorno->motivoStatus,
						'contingencia' => ($retorno->forcarEmissaoContingencia && empty($retorno->dataAutorizacao)) ? 1 : 0,
						'data_emissao' => $data_emissao,
						'data_entrada' => $data_emissao,
						'fiscal' => '1',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$db->update("nota_fiscal", $data, "id=" . $id);
					if (!$debug) {
						redirect_to("index.php?do=notafiscal&acao=visualizar&id=" . $id);
					}
				}
			} else {
				echo "ID da empresa: $id_enotas </br></br>";
				echo "Retorno de dados da nota (consulta por id): $id</br></br>";
				echo 'STATUS: ';
				echo $retorno->status;
				echo '</br>';
				echo 'MOTIVO STATUS: ';
				echo $retorno->motivoStatus;
				echo '</br>';
				if ($debug) {
					echo "</br>--- RETORNO COMPLETO ---</br>";
					echo json_encode($retorno);
					echo "</br>--- FIM RETORNO ---</br>";
				}
			}
		} else {
			echo $msg;
		}
	} catch (Exceptions\requestException $ex) {
		echo 'Erro na requisição web: </br></br>';

		echo 'Requested url: ' . $ex->requestedUrl;
		echo '</br>';
		echo 'Response Code: ' . $ex->getCode();
		echo '</br>';
		echo 'Message: ' . $ex->getMessage();
		echo '</br>';
		echo 'Response Body: ' . $ex->responseBody;
	}
} else {
	echo 'CLIENTE: ' . $razao_social;
	echo '<br/>';
	echo '<a href="index.php?do=cadastro&acao=editar&id=' . $row_notafiscal->id_cadastro . '" title="EDITAR O CLIENTE" target="_blank">EDITAR O CLIENTE</a>';
	echo '<br/>';
	echo '<br/>';
	$retorno = Filter::msgStatus();
	$mensagem = explode('#', $retorno);
	print $mensagem[0];
}
