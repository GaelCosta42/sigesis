<?php
 /**
   * PDF - Nota Fiscal de Produto - Download
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */ 
	define('_VALID_PHP', true);
	header('Content-Type: text/html; charset=utf-8');	
	
	require('enotas_servico/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;
	
	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));
	
	$id = get('id');
	$row_notafiscal = Core::getRowById('nota_fiscal', $id);
	$row_empresa = Core::getRowById('empresa', $row_notafiscal->id_empresa);
	$id_enotas = $row_empresa->enotas;
	
	try
	{
		$id = get('id');
		$xml = eNotasGW::$NFeApi->downloadXmlPorIdExterno($id_enotas, $id);
		$xml = utf8_decode($xml);
		$xml = 	str_replace('\"', '"', $xml);
		$aspas = substr($xml, 0, 1);
		if($aspas == '"') {
			$xml = substr($xml, 1, -1);
		}		
		echo $xml;
		$arquivo="NFSe-".$id.".xml";
		$caminho=UPLOADNOTAS."/".$arquivo;
		file_put_contents($caminho, $xml); 
		header('Content-type: octet/stream');
		header('Content-disposition: attachment; filename="'.$arquivo.'";'); 
		readfile($caminho);
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