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
		$id = get('id');
		$idExterno = 'nfc-'.$id;
			
		// echo "</br>--- --- --- --- AMBIENTE DE HOMOLOGAÇÃO --- --- --- ---</br>";
		// $idExterno = 'Hnfc-'.$id;
		// echo $idExterno;
		// echo "</br>--- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---</br>";
		$row_venda = Core::getRowById("vendas", $id);
		$row_empresa = Core::getRowById("empresa", $row_venda->id_empresa);
		$id_enotas = $row_empresa->enotas;
		$retorno = eNotasGW::$NFeConsumidorApi->consultar($id_enotas, $idExterno);
		$json_retorno =  json_encode($retorno);
		echo 'Retorno de dados da nota (consulta por $id): </br></br>';
		echo 'STATUS: ';
		echo $retorno->status;
		echo '</br>';
		echo 'MOTIVO STATUS: ';
		echo $retorno->motivoStatus;
		echo '</br>';
		echo 'NUMERO: ';
		echo $retorno->numero;
		echo '</br>';
		echo 'SERIE: ';
		echo $retorno->serie;
		echo '</br>';
		echo 'DATAEMISSAO: ';
		echo $retorno->dataEmissao;
		echo '</br>';
		echo 'ARRAY: </br></br>';
		print_r( $retorno );
		echo 'JSON: </br></br>';
		echo $json_retorno ;
		
		
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
		try {
			$idExterno = $id;
			$retorno = eNotasGW::$NFeConsumidorApi->consultar($id_enotas, $idExterno);
			if($retorno->status == 'Autorizada') {
			print_r($retorno);
			} else {
				echo 'Erro de validação: </br></br>';
				echo $ex->getMessage();
			}
		} catch(Exceptions\apiException $ex) {
			echo 'Erro de validação: </br></br>';
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