<?php
  /**
   * Webservices: Login
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');

	if ($core->tipo_sistema!=4){
		$json_erro = array(
			"status" => "OPCAO NAO DISPONIVEL. LIGUE PARA (31) 3829-1950",
			"retorno" => 400
		);
		$retorno =  json_encode($json_erro);
		echo $retorno;
	} else {
		try {
			$json_string = file_get_contents('php://input');
			$json_array = json_decode($json_string,true);
			$id_entregador = 0;
			$retorno = 0;
			$status = "";
			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "id_entregador")
						$id_entregador = $valor;	
				}
				if($id_entregador){
					$row_pedidos_entregador = $cadastro->getEntregadorVendasApp($id_entregador);
					if ($row_pedidos_entregador){
						foreach($row_pedidos_entregador as $prow){
							$array_pagamentos = [];
							$array_produtos = [];

							$pagamentos = $cadastro->getFinanceiro($prow->id);
							if ($pagamentos){
								foreach($pagamentos as $pag_row){
									$array_pagamentos[] = array(
										"tipo" => $pag_row->pagamento,
										"parcelas" => $pag_row->total_parcelas
									);
								}	
							}

							$produtos = $cadastro->getProdutosVendaEntrega($prow->id);
							if ($produtos){
								foreach($produtos as $pro_row){
									$array_produtos[] = array(
										"id_produto" => $pro_row->id_produto,
										"nome" => str_replace('&quot;','',(str_replace("&apos;",'',$pro_row->nome))),
										"quantidade" => $pro_row->quantidade
									);
								}	
							}

							$array_pedidos[] = array(
								"id_venda" => $prow->id,
								"id_cliente" => $prow->id_cadastro,
								"data" => $prow->data_venda,
								"data_entrega" => $prow->prazo_entrega,
								"observacao" => $prow->observacao,
								"id_entregador" => $prow->id_entregador,
								"nome" => $prow->nome,
								"cep" => $prow->cadastro_cep,
								"endereco" => $prow->cadastro_endereco,
								"numero" => $prow->cadastro_numero,
								"complemento" => $prow->cadastro_complemento,
								"bairro" => $prow->cadastro_bairro,
								"cidade" => $prow->cadastro_cidade,
								"telefone" => ($prow->celular) ? $prow->celular : $prow->telefone,
								"valor_total" => $prow->valor_pago,
								"pagamento" => $array_pagamentos,
								"produtos" => $array_produtos
							);
						}
						$retorno = 1;
					} else {
						$retorno = 0;
						$status .= "Nao existe entrega pendente.";
					}
				} else {
					$retorno = 0;
					$status .= "Erro - ENTREGADOR nao informado.";
				}
			} else {
				$retorno = 0;
				$status .= "Erro - JSON vazio. ";
			}
		} catch (Exception $e) {
			$retorno = 0;
			$status .= "Erro - EXCECAO: ".$e->getMessage();
		}

		$jsonRetorno = array(
			"pedidos" => (isset($array_pedidos)) ? $array_pedidos : null,
			"retorno" => $retorno,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>