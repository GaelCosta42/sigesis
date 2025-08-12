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
	$idExterno = 'nfc-'.$id;
	$row_venda = Core::getRowById("vendas", $id);
	$numero_nota = $row_venda->numero;
	$row_empresa = Core::getRowById("empresa", $row_venda->id_empresa);
	$id_enotas = $row_empresa->enotas;
	$data_impressao = date('Ymd');
	try
	{		
		if($row_venda->fiscal) {
			$pdf = eNotasGW::$NFeConsumidorApi->downloadPdf($id_enotas, $idExterno);
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="'.rawurlencode($data_impressao.'-'.$numero_nota.'.pdf').'";');
			header('Cache-Control: private, max-age=0, must-revalidate');
			header('Pragma: public');
			echo $pdf;
		} else {
			echo 'Nota fiscal não gerada.</br></br>';
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
		try {
			$pdf = eNotasGW::$NFeConsumidorApi->downloadPdf($id_enotas, $id);
			header('Content-Type: application/pdf');
			header('Content-Disposition: inline; filename="'.rawurlencode($data_impressao.'-'.$numero_nota.'.pdf').'";');
			header('Cache-Control: private, max-age=0, must-revalidate');
			header('Pragma: public');
			echo $pdf;
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