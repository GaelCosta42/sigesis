<script src="./assets/plugins/jquery.min.js" type="text/javascript"></script>
<?php
 /**
   * PDF - Nota Fiscal Todos - Download
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */ 
	define('_VALID_PHP', true);
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	
	require('enotas/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;
	
	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));	
	
	$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y'); 
	$id_empresa = get('id_empresa'); 
	$id_enotas = getValue('enotas', 'empresa', 'id="'.$id_empresa.'"');
	$data_impressao = date('Ymd');
	
	try
	{
		$retorno_row = $produto->getNotaFiscalChaveAcesso($mes_ano, $id_empresa);
		if($retorno_row) {
			foreach ($retorno_row as $exrow) {
				$id = $exrow->id;
				$numero = $exrow->numero;
				if($exrow->modelo == 6):
					echo "NFC: ".$exrow->numero_nota."<br/>";
					try
					{
						$idExterno = 'nfc-'.$id;						
						$xml = eNotasGW::$NFeConsumidorApi->downloadXml($id_enotas, $idExterno);
						$xml = utf8_decode($xml);
						$xml = 	str_replace('\"', '"', $xml);
						$aspas = substr($xml, 0, 1);
						if($aspas == '"') {
							$xml = substr($xml, 1, -1);
						}
						if(!$exrow->numero and $exrow->fiscal) {
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
					catch(Exceptions\apiException $ex) {
						continue;
					}
				else: 
					echo "NF-e: ".$exrow->numero_nota."<br/>";
					try
					{
						$idExterno = 'nfe-'.$id;
						$xml = eNotasGW::$NFeProdutoApi->downloadXml($id_enotas, $idExterno);
						$xml = utf8_decode($xml);
						$xml = 	str_replace('\"', '"', $xml);
						$aspas = substr($xml, 0, 1);
						if($aspas == '"') {
							$xml = substr($xml, 1, -1);
						}
						if(!$exrow->chaveacesso) {
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
						$arquivo = $data_impressao.'-NFe-'.$numero.".xml";
						file_put_contents(UPLOADNOTAS.$arquivo, $xml); 
						header('Content-type: octet/stream');
						header('Content-disposition: attachment; filename="'.$arquivo.'";'); 
					}
					catch(Exceptions\apiException $ex) {
						continue;
					}
				endif;
			}
			$contar = count($retorno_row);
			echo "Quantidade de notas fiscais encontradas para o mês $mes_ano: $contar</br></br>";
		} else {
			echo "Nenhuma nota fiscal encontrada para o mês: $mes_ano</br></br>";
		}
	}
	catch(Exception $e) {
		echo 'Erro na requisição do código: </br></br>';
		echo 'Message: ' . $e->getMessage();
	}
?>