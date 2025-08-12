<?php
  /**
   * Webservices: Produtos
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
			
			$status = "";
			$row_produto = $produto->getProdutosVenda();
			if($row_produto) {		
				foreach ($row_produto as $exrow){
					$array_produto[] = array(
						"id" => $exrow->id,
						"nome" => $exrow->nome,
						"codigonota" => $exrow->codigonota,
						"codigobarras" => $exrow->codigobarras,
						"estoque" => $exrow->estoque,
						"unidade" => $exrow->unidade,
						"valida_estoque" => 1,
						"foto" => "",
						"categoria" => $exrow->categoria,
						"id_categoria" => $exrow->id_categoria,
						"grupo" => $exrow->grupo,
						"id_grupo" => $exrow->id_grupo,
						"fabricante" => $exrow->fabricante,
						"id_fabricante" => $exrow->id_fabricante
					);
				}
			} else {
				$array_produto = "";
			}
		} catch (Exception $e) {
			$status = "Erro - EXCEÇÃO: ".$e->getMessage();
			$array_produto = "";
		}

		$jsonRetorno = array(
			"produto" => $array_produto,
			"status" => $status
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>