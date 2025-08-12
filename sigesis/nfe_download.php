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
	
	require('enotas/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;
	
	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));
	
	$id = get('id');
	$idExterno = 'nfe-'.$id;
	$row_notafiscal = Core::getRowById('nota_fiscal', $id);
	$numero = $row_notafiscal->numero;
	$row_empresa = Core::getRowById('empresa', $row_notafiscal->id_empresa);
	$id_enotas = $row_empresa->enotas;
	// $idExterno = 'Hnfe'.$id;
	
	try
	{
		$id = get('id');
		$pdf = eNotasGW::$NFeProdutoApi->downloadPdf($id_enotas, $idExterno);
		$arquivo="NFe-".$numero.".pdf";	
		
		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="'.rawurlencode($arquivo).'"');
		header('Cache-Control: private, max-age=0, must-revalidate');
		header('Pragma: public');
		echo $pdf;
		
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