<?php
	define('_VALID_PHP', true);
	header('Content-Type: text/html; charset=utf-8');	
	
	require('enotas/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));	
		
	try
	{
		$numero = get('numero_nota');
		$serie = get('serie_nota');
		$numero_nota = $numero.'-'.$serie;
		$id_empresa = (get('id_empresa')) ? get('id_empresa') : session('idempresa');
		$ambiente = 'Producao'; //'Producao' ou 'Homologacao'
		$id_nota = getValue("id", "nota_fiscal", "numero_nota='$numero_nota'");
		$id_nota = ($id_nota) ? $id_nota : 0;

		$row_nota = ($id_nota) ? Core::getRowById("nota_fiscal", $id_nota) : 0;

		if ($row_nota && ($row_nota->status_enotas=="Negada" || empty($row_nota->status_enotas) || $row_nota->status_enotas=="")){
			
			$idExterno = 'inut_nfe-'.$id_nota;
			$row_empresa = Core::getRowById("empresa", $id_empresa);
			$id_enotas = $row_empresa->enotas;
			
			try {
				$consultaInutilizacao = eNotasGW::$NFeProdutoApi->consultarInutilizacao($id_enotas, $idExterno);	
				if ($consultaInutilizacao && $consultaInutilizacao->status == 'Autorizada') {
					$data_nota = array(
						'fiscal' => 2,
						'inf_adicionais' => sanitize('QUEBRA NA SEQUENCIA DA NUMERACAO'),
						'status_enotas' => 'Inutilizada',
						'motivo_status' => sanitize('QUEBRA NA SEQUENCIA DA NUMERACAO'),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$db->update("nota_fiscal", $data_nota, "id=" . $id_nota);		
							
					//Sucesso. Numeração inutilizada
					echo "<br><br>---------------------------------<br>";
					echo lang('NOTA_INUTILIZADAS_NFE_OK');
					echo "<br>---------------------------------<br><br>";
				} else {
					$nota_inutilizar = array(
						'id' => $idExterno,
						'ambienteEmissao' => $ambiente,  //'Producao' ou 'Homologacao'
						'serie' => $serie,
						'numeroInicial' => $numero,
						'numeroFinal' => $numero,
						'justificativa' => 'Quebra na sequência da numeração'
					);
					$enotas = eNotasGW::$NFeProdutoApi->inutilizarNumeracao($id_enotas, $nota_inutilizar);
					sleep(20);
					$retorno = eNotasGW::$NFeProdutoApi->consultarInutilizacao($id_enotas, $idExterno);
					if ($retorno) {
						if ($retorno->status == 'Autorizada') {
							$data_nota = array(
								'fiscal' => 2,
								'inf_adicionais' => sanitize('QUEBRA NA SEQUENCIA DA NUMERACAO'),
								'status_enotas' => 'Inutilizada',
								'motivo_status' => sanitize('QUEBRA NA SEQUENCIA DA NUMERACAO'),
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							$db->update("nota_fiscal", $data_nota, "id=" . $id_nota);		
							
							//Sucesso. Numeração inutilizada
							echo "<br><br>---------------------------------<br>";
							echo lang('NOTA_INUTILIZADAS_NFE_OK');
							echo "<br>---------------------------------<br><br>";
							
						} else {
							//inultilização não autorizada.
							echo "<br><br>---------------------------------<br>";
							echo lang('NOTA_INUTILIZADAS_NFE_NAO');
							echo "<br>";
							echo lang('STATUS').': '.$retorno->status;
							echo "<br>";
							echo lang('MOTIVO').': '.$retorno->motivoStatus;
							echo "<br>---------------------------------<br><br>";
						}
					} else {
						//Erro na tentativa de inutilização
						echo "<br><br>---------------------------------<br>";
						echo lang('NOTA_INUTILIZADAS_NFE_NAO');
						echo "<br>---------------------------------<br><br>";
					}
				}
			}
			catch(Exceptions\apiException $ex) {
				$nota_inutilizar = array(
					'id' => $idExterno,
					'ambienteEmissao' => $ambiente,  //'Producao' ou 'Homologacao'
					'serie' => $serie,
					'numeroInicial' => $numero,
					'numeroFinal' => $numero,
					'justificativa' => 'Quebra na sequência da numeração'
				);
				$enotas = eNotasGW::$NFeProdutoApi->inutilizarNumeracao($id_enotas, $nota_inutilizar);
				sleep(20);
				$retorno = eNotasGW::$NFeProdutoApi->consultarInutilizacao($id_enotas, $idExterno);
				if ($retorno) {
					if ($retorno->status == 'Autorizada') {
						$data_nota = array(
							'fiscal' => 2,
							'inf_adicionais' => sanitize('QUEBRA NA SEQUENCIA DA NUMERACAO'),
							'status_enotas' => 'Inutilizada',
							'motivo_status' => sanitize('QUEBRA NA SEQUENCIA DA NUMERACAO'),
							'usuario_venda' => session('nomeusuario'),
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->update("nota_fiscal", $data_nota, "id=" . $id_nota);		
							
						//Sucesso. Numeração inutilizada
						echo "<br><br>---------------------------------<br>";
						echo lang('NOTA_INUTILIZADAS_NFE_OK');
						echo "<br>---------------------------------<br><br>";
							
					} else {
						//inultilização não autorizada.
						echo "<br><br>---------------------------------<br>";
						echo lang('NOTA_INUTILIZADAS_NFE_NAO');
						echo "<br>";
						echo lang('STATUS').': '.$retorno->status;
						echo "<br>";
						echo lang('MOTIVO').': '.$retorno->motivoStatus;
						echo "<br>---------------------------------<br><br>";
					}
				} else {
					//Erro na tentativa de inutilização
					echo "<br><br>---------------------------------<br>";
					echo lang('NOTA_INUTILIZADAS_NFE_NAO');
					echo "<br>---------------------------------<br><br>";
				}
			}
		} else {
			echo "<br><br>---------------------------------<br>";
			echo lang('NOTA_INUTILIZADAS_NFE_AVISO');
			echo "<br>---------------------------------<br><br>";
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
		echo 'Erro de validação: </br></br>';
		echo $ex->getMessage();
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