<?php

  /**
   * Retorna o total de NF-e e NFC-e emitidas em um determinado mês
   *
   * @package Sigesis - Sistemas de Gestão
   */
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../../init.php');
	
	try {
		header('Content-Type: application/json');
		$json_string = file_get_contents('php://input');
		$json_array = json_decode($json_string,true);
		
		$tipo = "";
		$empresaId = "";
		$nfeId = "";
		$nfeStatus = "";
		$nfeMotivoStatus = "";
		$nfeLinkDanfe = "";
		$nfeLinkXml = "";
		$nfeNumero = "";
		$nfeSerie = "";
		$nfeChaveAcesso = "";
		$nfeDataEmissao = "";
		$nfeDataAutorizacao = "";
		$nfeNumeroProtocolo = "";
		$nfeDigestValue = "";
		$x_token = "";

		if ($json_string) {
			foreach($json_array as $nome_campo => $valor) {
				if($nome_campo == "tipo")
					$tipo = $valor;
				if($nome_campo == "empresaId")
					$empresaId = $valor;
				if($nome_campo == "nfeId")
					$nfeId = $valor;
				if($nome_campo == "nfeStatus")
					$nfeStatus = $valor;
				if($nome_campo == "nfeMotivoStatus")
					$nfeMotivoStatus = $valor;
				if($nome_campo == "nfeLinkDanfe")
					$nfeLinkDanfe = $valor;
				if($nome_campo == "nfeLinkXml")
					$nfeLinkXml = $valor;
				if($nome_campo == "nfeNumero")
					$nfeNumero = $valor;
				if($nome_campo == "nfeSerie")
					$nfeSerie = $valor;
				if($nome_campo == "nfeChaveAcesso")
					$nfeChaveAcesso = $valor;
				if($nome_campo == "nfeDataEmissao")
					$nfeDataEmissao = $valor;
				if($nome_campo == "nfeDataAutorizacao")
					$nfeDataAutorizacao = $valor;
				if($nome_campo == "nfeNumeroProtocolo")
					$nfeNumeroProtocolo = $valor;
				if($nome_campo == "nfeDigestValue")
					$nfeDigestValue = $valor;
			}
	
			$headers = apache_request_headers();
			foreach ($headers as $nome_campo => $valor) {
				if (strtolower($nome_campo) == "x-token")
					$x_token = $valor;
			}
	
			if ($x_token!="") {
				$token_webhook = $core->token_webhook;
				if ($x_token===$token_webhook) {
					$row_empresa = $empresa->getEmpresa();
					if ($empresaId==$row_empresa->enotas) {
						//Procurar a nota em questão e atualizar as informações
						$numero_nota = $nfeNumero.'-'.$nfeSerie;
						$dataRetornoAPI = new DateTime($nfeDataEmissao);
						$dataRetornoAPI->sub(new DateInterval('PT03H00S'));
						$data_emissao = $dataRetornoAPI->format('Y-m-d H:i:s');

						$atualizar_nota = array(
							'status_enotas' => $nfeStatus,
							'link_danfe' => $nfeLinkDanfe,
							'link_download_xml' => $nfeLinkXml,
							'numero_nota' => $numero_nota,
							'numero' => $nfeNumero,
							'serie' => $nfeSerie,
							'chaveacesso' => $nfeChaveAcesso,
							'fiscal' => 1,
							'data_emissao' => $data_emissao,
							'usuario' => 'WebHook',
							'data' => 'NOW()'
						);

						$id_aux = explode("-",$nfeId);
						$id_atualizar = intval($id_aux[1]);

						if ($tipo=='NFC-e') { //se a nota for NFC-e
							$atualizar_nota['motivo_status'] = $nfeMotivoStatus;
							$db->update("vendas", $atualizar_nota, "id=".$id_atualizar);
						} elseif ($tipo=='NF-e') { //se a nota for NF-e
							$db->update("nota_fiscal", $atualizar_nota, "id=".$id_atualizar);
						}
						
					} else {
						echo http_response_code(401); //Acesso negado	
					}
				} else {
					echo http_response_code(403); //Token invalido ou nao enviado
				}
			} else {
				echo http_response_code(401); //Acesso negado
			}		
		} else {
			echo http_response_code(402); //"Erro - JSON vazio.";
		}

	} catch (Exception $e) {
		http_response_code(500); //Falha no processamento da requisicao
	}

?>