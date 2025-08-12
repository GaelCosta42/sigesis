<?php
 /**
   * PDF - Nota Fiscal do Consumidor
   *
   */

	define('_VALID_PHP', true);
	header('Content-Type: text/html; charset=utf-8');

	require_once('enotas/eNotasGW.php');
	require_once('init.php');

	if (!$usuario->is_Todos())
	  redirect_to("login.php");

	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));

	$id = get('id');
	$dg = get('debug');
	$ctg = get('contingencia');
	$ngd = get('negada');
	$debug = ($dg == 1) ? true : false;
	$reprocessarContingencia = ($ctg == 1) ? true : false;
	$reprocessarNegada = ($ngd == 1) ? true : false;

	$row_venda = Core::getRowById("vendas", $id);
	$row_empresa = Core::getRowById("empresa", $row_venda->id_empresa);

	if ($core->emissor_producao) {
		$ambiente = 'Producao'; //'Producao' ou 'Homologacao'
		$idExterno = ($row_empresa->versao_emissao==0) ? 'nfc-'.$id : 'nfc'.$row_empresa->versao_emissao.'-'.$id;
	} else {
		echo "</br>--- --- --- --- AMBIENTE DE HOMOLOGAÇÃO --- --- --- ---</br>";
		$ambiente = 'Homologacao'; //'Producao' ou 'Homologacao'
		$idExterno = ($row_empresa->versao_emissao==0) ? 'Hnfc-'.$id : 'Hnfc'.$row_empresa->versao_emissao.'-'.$id;
	}
	$id_enotas = $row_empresa->enotas;
	$data_venda = exibedataHora($row_venda->data_venda);
	$valor_total = floatval($row_venda->valor_total);
	$valor_desconto = floatval($row_venda->valor_desconto);
	$valor_pago = floatval($row_venda->valor_pago);
	$valor_desconto = 0;
	$atendente = $row_venda->usuario;
	$cnpj_contador = $row_empresa->cnpj_contador;

	$data_impressao = date('Ymd');
	try
	{
		if($row_venda->fiscal && !$reprocessarNegada) {
			if($row_venda->chaveacesso && !$reprocessarContingencia) {
				$pdf = eNotasGW::$NFeConsumidorApi->downloadPdf($id_enotas, $idExterno);
				header('Content-Type: application/pdf');
				header('Content-Disposition: inline; filename="'.rawurlencode($data_impressao.'-NFCe-'.$id.'.pdf').'"');
				header('Cache-Control: private, max-age=0, must-revalidate');
				header('Pragma: public');
				echo $pdf;
			} else {
				$retorno = eNotasGW::$NFeConsumidorApi->consultar($id_enotas, $idExterno);
				//Este procedimento de subtrair três horas da data de emissão é necessário pois a API gera um horário com três horas a mais no registro
				//das notas. Mas na danfe, a própria api gerar o horário correto e na sefaz o horário também vai correto.
				$dataRetornoAPI = new DateTime($retorno->dataEmissao);
				$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
				$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
				if($retorno->status == 'Autorizada') {
					$numero_nota = $retorno->numero.'-'.$retorno->serie;
					if(intval($retorno->numero) > 0) {
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
							'fiscal' => (empty($retorno->chaveAcesso)) ? '0' : '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->update("vendas", $data, "id=" . $id);
						$pdf = eNotasGW::$NFeConsumidorApi->downloadPdf($id_enotas, $idExterno);
						header('Content-Type: application/pdf');
						header('Content-Disposition: inline; filename="'.rawurlencode($data_impressao.'-NFCe-'.$id.'.pdf').'"');
						header('Cache-Control: private, max-age=0, must-revalidate');
						header('Pragma: public');
						echo $pdf;
					}
				} else {
					if(intval($retorno->numero) > 0) {
						$numero_nota = $retorno->numero.'-'.$retorno->serie;
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
							'fiscal' => (empty($retorno->chaveAcesso)) ? '0' : '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->update("vendas", $data, "id=" . $id);
					}

					echo "</br>--- status: ".$retorno->status." ---</br>";
					//Este procedimento de subtrair três horas da data de emissão é necessário pois a API gera um horário com três horas a mais no registro
					//das notas. Mas na danfe, a própria api gerar o horário correto e na sefaz o horário também vai correto.
					$dataRetornoAPI = new DateTime($retorno->dataEmissao);
					$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
					$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
					echo "</br>--- dataEmissao: ".exibedata($data_emissao)." ---</br>";
					$dataCriacao = substr(sanitize($retorno->dataCriacao), 0,10);
					echo "</br>--- dataCriacao: ".exibedata($dataCriacao)." ---</br>";
					$dataUltimaAlteracao = substr(sanitize($retorno->dataUltimaAlteracao), 0,10);
					echo "</br>--- dataUltimaAlteracao: ".exibedata($dataUltimaAlteracao)." ---</br>";
				}
			}
		} else {
			$array_itens = array();
			$retorno_row = $cadastro->getItemVenda($id);
			if($retorno_row){
				$count_itens = 0;
				foreach ($retorno_row as $exrow) {
					$icms_cst = ($exrow->icms_cst > 0) ? $exrow->icms_cst : '102';
					$icms_cst = ($exrow->icms_cst == '000' || $exrow->icms_cst == '00') ? $exrow->icms_cst : $icms_cst;
					$icms_percentual = ($exrow->icms_percentual > 0) ? $exrow->icms_percentual : 17.72;
					$icms_percentual = floatval($icms_percentual);
					$cst = intval($icms_cst);
					if($cst == 103 or $cst == 203 or $cst == 300 or $cst == 400) {
						$icms_percentual = 0;
					}
					$codigo = ($exrow->codigo) ? $exrow->codigo : $exrow->id_produto;
					$ncm = limparNumero($exrow->ncm);
					$quantidade = floatval($exrow->quantidade);
					$descontos = floatval($exrow->valor_desconto);
					$outrasDespesas = floatval($exrow->valor_despesa_acessoria);
					$valor_prd = floatval($exrow->valor);
					$cest = limparNumero($exrow->cest);

					if($exrow->anp) {
						$count_itens++;
						$array_itens[] = array(
						  'cfop' => $exrow->cfop,
						  'codigo' => $codigo,
						  'descricao' => $exrow->produto,
						  'ncm' => $ncm,
						  'cest' => $cest,
						  'quantidade' => $quantidade,
						  'unidadeMedida' => $exrow->unidade,
						  'valorUnitario' => $valor_prd,
						  'tipoArredondamento' => 'ABNT_NBR_5891',
						  'descontos' => $descontos,
						  'outrasDespesas' => $outrasDespesas,
						  'impostos' => array(
								'percentualAproximadoTributos' => array(
									'simplificado' => array(
										'percentual' => $icms_percentual
									),
									'fonte' => 'IBPT'
								),
								'icms' => array(
								  'situacaoTributaria' => $icms_cst,
								  'origem' => ($row_empresa->perfil_empresa=='SN') ? '0' : '3'
								),
								'pis' => array(
									'situacaoTributaria' => $exrow->pis_cst,
									'porAliquota' => array(
										'aliquota' => $exrow->pis_aliquota,
									)
								),
								'cofins' => array(
									'situacaoTributaria' => $exrow->cofins_cst,
									'porAliquota' => array(
										'aliquota' => $exrow->cofins_aliquota,
									)
								),
								'ipi' => array(
									'situacaoTributaria' => $exrow->ipi_saida_codigo,
									'porAliquota' => array(
										'aliquota' => $exrow->ipi_cst,
									)
								)
							),
						  'combustivel' => array(
								'codigoProdutoANP' => $exrow->anp,
								'percentualGasNatural' => 100,
								'valorDePartida' => $exrow->valor_partida,
								'codif' => null,
								'quantidadeFaturadaTempAmbiente' => $exrow->quantidade,
								'ufConsumo' => 'MG',
								'cide' => array(
									'quantidadeBaseCalculo' => $exrow->quantidade,
									'valorAliquota' => 0.0000,
									'valor' => $valor_prd
								)
							)
						);

						if ($exrow->anp != '210203001'){
							unset($array_itens[$count_itens-1]['combustivel']['percentualGasNatural']);
						}

						if ($exrow->pis_cst == ''){
							unset($array_itens[$count_itens-1]['impostos']['pis']);
						}elseif($exrow->pis_aliquota == 0){
							unset($array_itens[$count_itens-1]['impostos']['pis']['porAliquota']);
						}

						if ($exrow->cofins_cst== ''){
							unset($array_itens[$count_itens-1]['impostos']['cofins']);
						}elseif($exrow->cofins_aliquota == 0){
							unset($array_itens[$count_itens-1]['impostos']['cofins']['porAliquota']);
						}

						if ($exrow->ipi_saida_codigo == 0){
							unset($array_itens[$count_itens-1]['impostos']['ipi']);
						}elseif($exrow->ipi_cst == 0){
							unset($array_itens[$count_itens-1]['impostos']['ipi']['porAliquota']);
						}

					} elseif($cest) {
						$count_itens++;
						$array_itens[] = array(
						  'cfop' => $exrow->cfop,
						  'codigo' => $codigo,
						  'descricao' => $exrow->produto,
						  'ncm' => $ncm,
						  'cest' => $cest,
						  'quantidade' => $quantidade,
						  'unidadeMedida' => $exrow->unidade,
						  'valorUnitario' => $valor_prd,
						  'tipoArredondamento' => 'ABNT_NBR_5891',
						  'descontos' => $descontos,
						  'outrasDespesas' => $outrasDespesas,
						  'impostos' => array(
							'percentualAproximadoTributos' => array(
								'simplificado' => array(
									'percentual' => $icms_percentual
								),
								'fonte' => 'IBPT'
							),
							'icms' => array(
							  'situacaoTributaria' => $icms_cst,
							  'origem' => '0'
							),
							'pis' => array(
								'situacaoTributaria' => $exrow->pis_cst,
								'porAliquota' => array(
									'aliquota' => $exrow->pis_aliquota,
								)
							),
							'cofins' => array(
								'situacaoTributaria' => $exrow->cofins_cst,
								'porAliquota' => array(
									'aliquota' => $exrow->cofins_aliquota,
								)
							),
							'ipi' => array(
								'situacaoTributaria' => $exrow->ipi_saida_codigo,
								'porAliquota' => array(
									'aliquota' => $exrow->ipi_cst,
								)
							)
						  )
						);

						if ($exrow->pis_cst == ''){
							unset($array_itens[$count_itens-1]['impostos']['pis']);
						}elseif($exrow->pis_aliquota == 0){
							unset($array_itens[$count_itens-1]['impostos']['pis']['porAliquota']);
						}

						if ($exrow->cofins_cst== ''){
							unset($array_itens[$count_itens-1]['impostos']['cofins']);
						}elseif($exrow->cofins_aliquota == 0){
							unset($array_itens[$count_itens-1]['impostos']['cofins']['porAliquota']);
						}

						if ($exrow->ipi_saida_codigo == 0){
							unset($array_itens[$count_itens-1]['impostos']['ipi']);
						}elseif($exrow->ipi_cst == 0){
							unset($array_itens[$count_itens-1]['impostos']['ipi']['porAliquota']);
						}

					} else {
						$count_itens++;
						$array_itens[] = array(
						  'cfop' => $exrow->cfop,
						  'codigo' => $codigo,
						  'descricao' => $exrow->produto,
						  'ncm' => $ncm,
						  'quantidade' => $quantidade,
						  'unidadeMedida' => $exrow->unidade,
						  'valorUnitario' => $valor_prd,
						  'tipoArredondamento' => 'ABNT_NBR_5891',
						  'descontos' => $descontos,
						  'outrasDespesas' => $outrasDespesas,
						  'impostos' => array(
							'percentualAproximadoTributos' => array(
								'simplificado' => array(
									'percentual' => $icms_percentual
								),
								'fonte' => 'IBPT'
							),
							'icms' => array(
							  'situacaoTributaria' => $icms_cst,
							  'origem' => '0'
							),
							'pis' => array(
								'situacaoTributaria' => $exrow->pis_cst,
								'porAliquota' => array(
									'aliquota' => $exrow->pis_aliquota,
								)
							),
							'cofins' => array(
								'situacaoTributaria' => $exrow->cofins_cst,
								'porAliquota' => array(
									'aliquota' => $exrow->cofins_aliquota,
								)
							),
							'ipi' => array(
								'situacaoTributaria' => $exrow->ipi_saida_codigo,
								'porAliquota' => array(
									'aliquota' => $exrow->ipi_cst,
								)
							)
						  )
						);

						if ($exrow->pis_cst == ''){
							unset($array_itens[$count_itens-1]['impostos']['pis']);
						}elseif($exrow->pis_aliquota == 0){
							unset($array_itens[$count_itens-1]['impostos']['pis']['porAliquota']);
						}

						if ($exrow->cofins_cst== ''){
							unset($array_itens[$count_itens-1]['impostos']['cofins']);
						}elseif($exrow->cofins_aliquota == 0){
							unset($array_itens[$count_itens-1]['impostos']['cofins']['porAliquota']);
						}

						if ($exrow->ipi_saida_codigo == 0){
							unset($array_itens[$count_itens-1]['impostos']['ipi']);
						}elseif($exrow->ipi_cst == 0){
							unset($array_itens[$count_itens-1]['impostos']['ipi']['porAliquota']);
						}
					}
				}
				unset($exrow);
			}

			$array_pagamento = array();
			$retorno_row = $cadastro->getFinanceiroVenda($id);
			if($retorno_row){
				foreach ($retorno_row as $exrow) {
					$tipo = pagamentoNFC($exrow->pagamento);
					if(($tipo == "CartaoDeCredito") or ($tipo == "CartaoDeDebito")) {
						$array_pagamento[] = array(
							'tipo' => $tipo,
							'valor' => $exrow->valor_pago,
							'credenciadoraCartao' => array(
								'tipoIntegracaoPagamento' => 'NaoIntegradoAoSistemaDeGestao'
							)
						);
					} else {
						$array_pagamento[] = array(
							'tipo' => $tipo,
							'descricao' => ($exrow->pagamento) ? $exrow->pagamento : "Outros",
							'valor' => $exrow->valor_pago
						);
					}
				}
				unset($exrow);
			}

			$nota_simples = array(
				'id' => $idExterno,
				'ambienteEmissao' => $ambiente, //'Producao' ou 'Homologacao'
				'forcarEmissaoContingencia' => false,
				'pedido' => array(
					'presencaConsumidor' => 'OperacaoPresencial',
					'pagamento' => array(
						'tipo' => 'PagamentoAVista',
						'formas' => $array_pagamento
					)
				),
				'itens' => $array_itens
			);

			$cadastro_valido = true;
			if($row_venda->id_cadastro) {
				$row_cadastro = Core::getRowById("cadastro", $row_venda->id_cadastro);
				if(valida_cpf_cnpj($row_cadastro->cpf_cnpj)) {
					$tipo = '';
					if($row_cadastro->tipo == 1) {
						$tipo = 'J';
					} elseif($row_cadastro->tipo == 2) {
						$tipo = 'F';
					}
					$array_cliente = array(
						//F - Fisica, J - Juridica
						'tipoPessoa' => $tipo,
						'nome' => $row_cadastro->nome, 	//opcional
						'email' => $row_cadastro->email, 	//opcional
						'telefone' => limparNumero($row_cadastro->telefone), //opcional
						'cpfCnpj' => limparCPF_CNPJ($row_cadastro->cpf_cnpj), //opcional
						//opcional, para emitir sem o endereço basta não informar o atributo “endereco”
						'endereco' => array(
							'uf' => $row_cadastro->estado, //opcional
							'cidade' => $row_cadastro->cidade, //opcional
							'logradouro' => $row_cadastro->endereco, //opcional
							'numero' => $row_cadastro->numero, //opcional
							'complemento' => $row_cadastro->complemento, //opcional
							'bairro' => $row_cadastro->bairro, //opcional
							'cep' => limparNumero($row_cadastro->cep) //opcional
						)
					);

					if ($row_cadastro->estado=='' && $row_cadastro->cidade=='')
						unset($array_cliente['endereco']);

					$nota_simples['cliente'] = $array_cliente;
					$cadastro_valido = true;
				} else {
					echo "<br><br>---------------------------------<br>";
					echo lang('MSG_ERRO_CPF_INVALIDO')."( $row_cadastro->cpf_cnpj )";
					echo "<br>---------------------------------<br><br>";
					$cadastro_valido = false;
				}
			}

			if ($cnpj_contador) {
				$array_contador = array();
				$array_contador[] = array(
					'cpfCnpj' => $cnpj_contador
				);
				$nota_simples['autorizacaoDownloadXml'] = $array_contador;
			}

			if($debug) {
				echo "AMBIENTE: $ambiente </br>";
				echo "ID EMPRESA: $id_enotas </br>";
				echo "</br>--- INICIO REGISTRO DE VENDA ---</br>";
				print_r($row_venda);
				echo "</br>--- FINAL REGISTRO DE VENDA ---</br>";
				echo "</br>--- INICIO DA NOTA ANTES DA CONVERSAO ---</br>";
				print_r($nota_simples);
				echo "</br>--- FINAL DA NOTA ANTES DA CONVERSAO ---</br>";
				echo "</br>--- INICIO NOTA JSON ---</br>";
				$json_nota = json_encode($nota_simples);
				echo $json_nota;
				echo "</br>--- FIM NOTA JSON ---</br></br>";
			}

			if ($cadastro_valido) {

				$retorno = eNotasGW::$NFeConsumidorApi->emitir($id_enotas, $nota_simples);

				if($debug) {
					echo "</br>--- RETORNO COMPLETO ---</br>";
					echo json_encode($retorno);
					echo "</br>--- FIM RETORNO ---</br>";
				}
				$status = $retorno->status;
				if($status == "Negada") {

					//Este procedimento de subtrair três horas da data de emissão é necessário pois a API gera um horário com três horas a mais no registro
					//das notas. Mas na danfe, a própria api gerar o horário correto e na sefaz o horário também vai correto.
					$dataRetornoAPI = new DateTime($retorno->dataEmissao);
					$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
					$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
					$numero_nota = $retorno->numero.'-'.$retorno->serie;
					$data = array(
						'numero_nota' => $numero_nota,
						'numero' => $retorno->numero,
						'serie' => $retorno->serie,
						'chaveacesso' => $retorno->chaveAcesso,
						'link_danfe' => ($retorno->linkDanfe) ? $retorno->linkDanfe : "",
						'link_download_xml' => ($retorno->linkDownloadXml) ? $retorno->linkDownloadXml : "",
						'status_enotas' => $retorno->status,
						'motivo_status' => $retorno->motivoStatus,
						'contingencia' => ($retorno->forcarEmissaoContingencia && empty($retorno->dataAutorizacao)) ? 1 : 0,
						'data_emissao' => $data_emissao,
						'fiscal' => (empty($retorno->chaveAcesso)) ? '0' : '1',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$db->update("vendas", $data, "id=" . $id);

					echo $motivo = $retorno->motivoStatus;
				} else {
					$numero_nota = $retorno->numero.'-'.$retorno->serie;
					//Este procedimento de subtrair três horas da data de emissão é necessário pois a API gera um horário com três horas a mais no registro
					//das notas. Mas na danfe, a própria api gerar o horário correto e na sefaz o horário também vai correto.
					$dataRetornoAPI = new DateTime($retorno->dataEmissao);
					$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
					$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
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
						'fiscal' => (empty($retorno->chaveAcesso)) ? '0' : '1',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$db->update("vendas", $data, "id=" . $id);
					$pdf = eNotasGW::$NFeConsumidorApi->downloadPdf($id_enotas, $idExterno);
					header('Content-Type: application/pdf');
					header('Content-Disposition: inline; filename="'.rawurlencode($data_impressao.'-NFCe-'.$id.'.pdf').'"');
					header('Cache-Control: private, max-age=0, must-revalidate');
					header('Pragma: public');
					echo $pdf;
				}
			} else {
				echo "<br><br>---------------------------------<br>";
				echo lang('MSG_ERRO_CPF_INVALIDO')."( $row_cadastro->cpf_cnpj )";
				echo "<br>---------------------------------<br><br>";
			}
		}
	}
	catch(Exceptions\invalidApiKeyException $ex) {
		echo 'Erro de autenticação: </br></br>';
		echo $ex->getMessage();
	}
	catch(Exceptions\unauthorizedException $ex) {
		echo 'Acesso negado: </br></br>';
		echo $ex->getMessage();
	}
	catch(Exceptions\apiException $ex) {
		$retorno = $ex->getMessage();
		if(validaTexto($retorno, "NFe0003")) {

			$retorno_nota = eNotasGW::$NFeConsumidorApi->consultar($id_enotas, $idExterno);
			//Este procedimento de subtrair três horas da data de emissão é necessário pois a API gera um horário com três horas a mais no registro
			//das notas. Mas na danfe, a própria api gerar o horário correto e na sefaz o horário também vai correto.
			$dataRetornoAPI = new DateTime($retorno_nota->dataEmissao);
			$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
			$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');
			$numero_nota = $retorno_nota->numero.'-'.$retorno_nota->serie;
			$data = array(
				'numero_nota' => $numero_nota,
				'numero' => $retorno_nota->numero,
				'serie' => $retorno_nota->serie,
				'chaveacesso' => $retorno_nota->chaveAcesso,
				'link_danfe' => $retorno_nota->linkDanfe,
				'link_download_xml' => $retorno_nota->linkDownloadXml,
				'status_enotas' => $retorno_nota->status,
				'motivo_status' => $retorno_nota->motivoStatus,
				'contingencia' => ($retorno_nota->forcarEmissaoContingencia && empty($retorno_nota->dataAutorizacao)) ? 1 : 0,
				'data_emissao' => $data_emissao,
				'fiscal' => (empty($retorno_nota->chaveAcesso)) ? '0' : '1',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			$db->update("vendas", $data, "id=" . $id);

			$pdf = eNotasGW::$NFeConsumidorApi->downloadPdf($id_enotas, $idExterno);
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="'.rawurlencode($data_impressao.'-NFCe-'.$id.'.pdf').'"');
			header('Cache-Control: private, max-age=0, must-revalidate');
			header('Pragma: public');
			echo $pdf;
		} else {
			echo $ex->getMessage();
		}
	}
	catch(Exceptions\requestException $ex) {
		echo 'Erro na requisição web: </br></br>';
		echo 'Requested url: ' . $ex->requestedUrl;
		echo '</br>';
		echo 'Response Code: ' . $ex->getCode();
		echo '</br>';
		echo 'Message: ' . $ex->getMessage();
		echo '</br>';
		echo 'Response Body: ' . $ex->responseBody;
	}
?>