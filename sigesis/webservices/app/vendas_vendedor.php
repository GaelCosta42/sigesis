<?php
  /**
   * Webservices: Vendas vendedor
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');
	
	if (!$core->app_vendas){
		$json_erro = array(
			"status" => 400,
			"retorno" => "OPCAO NAO DISPONIVEL. LIGUE PARA (31) 3829-1950"
		);
		$retorno =  json_encode($json_erro);
		echo $retorno;
	}
	else {
	
		try {
			
			$json_string = file_get_contents('php://input');
			$json_array = json_decode($json_string,true);
			$id_vendedor = 0;
			$data_inicio = "";
			$data_fim = "";
			$retorno = 0;
			$vendas = array();

			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "id_usuario")
						$id_vendedor = $valor;
					if($nome_campo == "data_inicio")
						$data_inicio = $valor;
					if($nome_campo == "data_fim")
						$data_fim = $valor;
				}
				
				$vendas_vendedor = $faturamento->ObterVendasVendedorPeriodo($id_vendedor, $data_inicio, $data_fim);
				
				if ($vendas_vendedor) {
					foreach($vendas_vendedor as $row_vendas) {
						$qtde_produtos = 0;
						$array_produtos = array();
						$nome_cliente = ($row_vendas->id_cadastro) ? getValue("nome","cadastro","id=".$row_vendas->id_cadastro) : "";
						$produtos_venda = $faturamento->ObterTodosProdutosVendaVendedor($row_vendas->id);
						if ($produtos_venda) {
							foreach($produtos_venda as $row_produtos){
								$qtde_produtos++;
								$array_produtos[] = array(
									'nome' => $row_produtos->nome,
									'quantidade' => $row_produtos->quantidade,
									'valor' => $row_produtos->valor,
								);
							}
						}
						
						$vendas[] = array(
							'id' => $row_vendas->id,
							'valor_venda' => $row_vendas->valor_total,
							'valor_desconto' => $row_vendas->valor_desconto,
							'valor_total' => $row_vendas->valor_pago,
							'data_venda' => $row_vendas->data_venda,
							'id_status_entrega' => $row_vendas->status_entrega,
							'valor_status_entrega' => $row_vendas->status,
							'cor_status_entrega' => $row_vendas->cor,
							'inativo' => $row_vendas->inativo,
							'qtde_produtos' => $qtde_produtos,
							'cliente' => $nome_cliente,
							'produtos' => $array_produtos
						);
					}
				}
				$retorno = 200;
			} else {
				$status = "Erro - JSON vazio.";
				$retorno = 0;
			}
		} catch (Exception $e) {
				$status = "Erro - EXCECAO: ".$e->getMessage();
				$retorno = 0;
		}

		$jsonRetorno = array(
			"vendas" => $vendas,
			"retorno" => $retorno,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>