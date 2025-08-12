<?php
	define('_VALID_PHP', true);
	header('Content-Type: text/html; charset=utf-8');	
	
	require('enotas/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));	
	
	$id = get('id');
	$row_carta = Core::getRowById('nota_fiscal_carta', $id);
	$row_empresa = Core::getRowById('empresa', $row_carta->id_empresa);
	$id_enotas = $row_empresa->enotas;
	$id_nota = 'correcao-p-'.$id;
	
	try
	{
		echo "Envio da consulta para o CARTA DE CORREÇÃO: $id </br>";
		echo "ID da empresa: $id_enotas </br></br>";
		$retorno = eNotasGW::$NFeProdutoApi->consultarCartaCorrecao($id_enotas, $id_nota);	
		$json_retorno =  json_encode($retorno);
		echo 'Retorno de dados da carta de correção (consulta por id): </br></br>';
		echo 'STATUS: ';
		echo $retorno->status;
		echo '</br>';
		echo 'MOTIVO STATUS: ';
		echo $retorno->motivoStatus;
		echo '</br>';
		echo 'NUMERO: ';
		echo $retorno->numero;
		echo '</br>';
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