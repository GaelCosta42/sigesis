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
	$idExterno = 'nfc-'.$id;
	$row_venda = Core::getRowById("vendas", $id);
	$numero = $row_venda->numero;
	$row_empresa = Core::getRowById("empresa", $row_venda->id_empresa);
	$id_enotas = $row_empresa->enotas;
	$data_impressao = date('Ymd');
	
	try
	{
		$xml = eNotasGW::$NFeConsumidorApi->downloadXml($id_enotas, $idExterno);
		$xml = utf8_decode($xml);
		$xml = 	str_replace('\"', '"', $xml);
		$aspas = substr($xml, 0, 1);
		if($aspas == '"') {
			$xml = substr($xml, 1, -1);
		}
		if(!$row_venda->numero and $row_venda->fiscal) {
			$dom = new DOMDocument('1.0', 'UTF-8');	
			$dom_sxe = $dom->loadXML($xml);
			if (!$dom_sxe) {
				print Filter::msgAlert(lang('MSG_ERRO_XML'));
				exit;
			}
			foreach($dom->getElementsByTagName("infNFe") as $compnfse)
			{
				$array_nodes = array();
				$childNodes = $compnfse->childNodes;
				lerNodes($childNodes, $array_nodes);
				$chaveacesso = $compnfse->getAttribute('Id');
				$chaveacesso = str_replace("NFe","",$chaveacesso);
				$serie = $array_nodes['ide_serie'];
				$numero = $array_nodes['ide_nNF'];
				$numero_nota = $numero.'-'.$serie;
				$data_emissao = substr(sanitize($array_nodes['ide_dhEmi']), 0,10);
				$data = array(
					'numero_nota' => $numero_nota,
					'numero' => $numero,
					'serie' => $serie,
					'chaveacesso' => $chaveacesso,
					'data_emissao' => $data_emissao,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$db->update("vendas", $data, "id=".$id);
			}
		}	
		echo $xml;
		$arquivo = $data_impressao.'-NFCe-'.$numero.".xml";
		
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
		$xml = eNotasGW::$NFeConsumidorApi->downloadXml($id_enotas, $id);
		echo $xml;
		$arquivo = $data_impressao.'-NFCe-'.$numero.".xml";		
		file_put_contents(UPLOADNOTAS.$arquivo, $xml); 
		header('Content-type: octet/stream');
		header('Content-disposition: attachment; filename="'.$arquivo.'";');
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