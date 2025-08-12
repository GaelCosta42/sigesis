<?php
 /**
   * PDF - Nota Fiscal XML - Download
   *
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
	
	try
	{
		$id = get('id');
		$xml = eNotasGW::$NFeProdutoApi->downloadXml($id_enotas, $idExterno);
		$xml = utf8_decode($xml);
		$xml = 	str_replace('\"', '"', $xml);
		$aspas = substr($xml, 0, 1);
		if($aspas == '"') {
			$xml = substr($xml, 1, -1);
		}
		if(!$row_notafiscal->chaveacesso) {
			$dom = new DOMDocument('1.0', 'UTF-8');	
			$dom_sxe = $dom->loadXML($xml);
			if (!$dom_sxe) {
				print Filter::msgAlert(lang('MSG_ERRO_XML'));
				exit;
			}
			foreach($dom->getElementsByTagName("infNFe") as $compnfse)
			{
				$chaveacesso = $compnfse->getAttribute('Id');
				$chaveacesso = str_replace("NFe","",$chaveacesso);
				$data = array(
					'chaveacesso' => $chaveacesso, 
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$db->update("nota_fiscal", $data, "id=".$id);
			}
		}	
		echo $xml;
		$arquivo="NFe-".$numero.".xml";
		file_put_contents(UPLOADNOTAS.$arquivo, $xml); 
		header('Content-type: octet/stream');
		header('Content-disposition: attachment; filename="'.$arquivo.'";'); 
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