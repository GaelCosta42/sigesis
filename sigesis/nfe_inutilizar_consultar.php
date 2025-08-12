<?php
	define('_VALID_PHP', true);
	header('Content-Type: text/html; charset=utf-8');	
	
	require('enotas/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));	
		
	$id_enotas = '10d99091-3399-4a05-acec-f188644f0500';
	$idExterno = 'inu-102';
	
	try
	{
		echo "Envio da consulta para o ID: $idExterno </br>";
		echo "ID da empresa: $id_enotas </br></br>";
		$retorno = eNotasGW::$NFeProdutoApi->consultarInutilizacao($id_enotas, $idExterno);	
		$json_retorno =  json_encode($retorno);
		echo 'Retorno de dados da nota (consulta inutilização por id): </br></br>';
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