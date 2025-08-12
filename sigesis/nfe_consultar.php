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
	$idExterno = 'nfe-'.$id;
	// $idExterno = 'Hnfe'.$id;
	$row_notafiscal = Core::getRowById('nota_fiscal', $id);
	$row_empresa = Core::getRowById('empresa', $row_notafiscal->id_empresa);
	$id_enotas = $row_empresa->enotas;
	
	try
	{
		echo "Envio da consulta para o ID: $id </br>";
		echo "ID da empresa: $id_enotas </br></br>";
		$retorno = eNotasGW::$NFeProdutoApi->consultar($id_enotas, $idExterno);	
		$json_retorno =  json_encode($retorno);
		echo 'Retorno de dados da nota (consulta por id): </br></br>';
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