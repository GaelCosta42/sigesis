<?php
	define('_VALID_PHP', true);	
	require('enotas/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));	

	ini_set('display_errors', 0); // Desabilita a exibição de erros
	error_reporting(0); // Desabilita todos os tipos de relatórios de erros
	
	$id = get('id');
	$dg = get('debug');
	$debug = ($dg == 1) ? true: false;
	$row_carta = Core::getRowById('nota_fiscal_carta', $id);
	$row_empresa = Core::getRowById('empresa', $row_carta->id_empresa);
	$id_enotas = $row_empresa->enotas;
	$numero = number_format($row_carta->numero, 0, '.', '');
	$numero = floatval($numero);
	$id_nota = 'correcao-p-'.$row_carta->id_nota.'-n'.$numero;

	if ($core->emissor_producao) {
		$ambiente = 'Producao'; //'Producao' ou 'Homologacao'
	} else {	
		$ambiente = 'Homologacao'; //'Producao' ou 'Homologacao'
	}
	
	try
	{
		if($row_carta->emitida) {		
			ob_start();	
			$xml = eNotasGW::$NFeProdutoApi->downloadXmlCartaCorrecao($id_enotas, $id_nota);				
			header('Content-Type:application/xml');
			header('Content-Disposition: inline; filename="'.rawurlencode('carta-'.$id.'.xml').'"');
			header('Cache-Control: private, max-age=0, must-revalidate');
			header('Pragma: no-cache');
			ob_clean();
			flush();
			echo trim($xml);
		} else {
			$array_nfe = array();	
			$chave = str_replace(' ', '', $row_carta->chaveacesso);
			$array_nfe = array(
				'chaveAcesso'=> $chave
			);
			
			$carta_correcao = array(
				'id' => $id_nota,
				'ambienteEmissao' => $ambiente,  //'Producao' ou 'Homologacao'
				'numero'=> $numero,
				'correcao'=> $row_carta->correcao,
				'nfe'=> $array_nfe
			);
			if(true) {
				echo "ID EMPRESA: $id_enotas </br>";
				echo "</br>--- URL CARTA CORREÇÃO ---</br>";
				echo "</br>--- INICIO CARTA DE CORREÇÃO ---</br>";
				$json_carta = json_encode($carta_correcao);
				echo $json_carta;
				echo "</br>--- FIM CARTA DE CORREÇÃO ---</br>";
			}
			echo "</br>--- INICIO RETORNO CARTA DE CORREÇÃO Corpo ---</br>";
			$enota = eNotasGW::$NFeProdutoApi->cartaCorrecao($id_enotas,$carta_correcao);
			sleep(15);		
			echo "</br>--- FIM RETORNO CARTA DE CORREÇÃO ---</br>";

			$retorno = eNotasGW::$NFeProdutoApi->consultarCartaCorrecao($id_enotas, $id_nota);
			if($retorno->status == 'Autorizada') {
				$data = array(
					'emitida' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$db->update("nota_fiscal_carta", $data, "id=" . $id);
					
				$xml = eNotasGW::$NFeProdutoApi->downloadXmlCartaCorrecao($id_enotas, $id_nota);	
					
				header('Content-Type: application/xml');
				header('Content-Disposition: inline; filename="'.rawurlencode('carta-'.$id.'.xml').'"');
				header('Cache-Control: private, max-age=0, must-revalidate');
				header('Pragma: public');
				echo trim($xml);
			} else {
				echo "Carta de Correção não autorizada. Tratar as informações.";
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
			echo '<b>Erro de validação:</b> </br></br>';
			$msg = $ex->getMessage();
			echo '<b>Mensagem:</b> </br></br>';
			// print_r($ex);
			if(validaTexto($msg, 'NFe0001')) {
				$array_nfe = array();
				$chave = str_replace(' ', '', $row_carta->chaveacesso);
				$array_nfe = array(
					'chaveAcesso'=> $chave
				);
				$carta_correcao = array(
					'id' => $id_nota,
					'ambienteEmissao' => $ambiente,  //'Producao' ou 'Homologacao'
					'numero'=> $numero,
					'correcao'=> $row_carta->correcao,
					'nfe'=> $array_nfe
				);
				if(true) {
					echo "ID EMPRESA: $id_enotas </br>";
					echo "</br>--- URL CARTA CORREÇÃO ---</br>";
					echo "</br>--- INICIO CARTA DE CORREÇÃO ---</br>";
					$json_carta = json_encode($carta_correcao);
					echo $json_carta;
					echo "</br>--- FIM CARTA DE CORREÇÃO ---</br>";
				}
				echo "</br>--- INICIO RETORNO CARTA DE CORREÇÃO Exception ---</br>";
				$enota = eNotasGW::$NFeProdutoApi->cartaCorrecao($id_enotas,$carta_correcao);
				sleep(15);		
				print_r($enota);
				echo "</br>--- FIM RETORNO CARTA DE CORREÇÃO ---</br>";				
			} else {
				echo $msg;
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