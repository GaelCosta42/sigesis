<?php
  /**
   * Webservices: Tabela de Preços
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
		// PAGE = é qual página.
		// PER_PAGE = quantos items por página.

		try {
			$json_string = file_get_contents('php://input');
			$json_array = json_decode($json_string,true);
			
			$id_tabela = 0;	
			$page = 0;
			$per_page = 0;
			$status = "";
			$busca = "";
			
			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "id")
						$id_tabela = $valor;
					if($nome_campo == "codigobarras")
						$codigobarras = $valor;	
					if($nome_campo == "page")
						$page = $valor;	
					if($nome_campo == "per_page")
						$per_page = $valor;	
					if($nome_campo == "busca")
						$busca = $valor;	
				}

				$page = ($page - 1)* $per_page;

				if($id_tabela){
					$row_precos = $produto->getTabelaAppVendas($id_tabela, $per_page, $page, $busca, $codigobarras);
					if($row_precos) {		
						foreach ($row_precos as $exrow){
							$array_precos[] = array(
								"id_produto" => $exrow->id_produto,
								"valor_venda" => $exrow->valor_venda,
								"nome" => $exrow->nome,
								"codigobarras" => $exrow->codigobarras,
								"estoque" => $exrow->estoque,
								"unidade" => $exrow->unidade,
								"valida_estoque" => intval($exrow->valida_estoque),
								"foto" => $exrow->imagem,
								"categoria" => $exrow->categoria,
								"id_categoria" => $exrow->id_categoria,
								"grupo" => $exrow->grupo,
								"id_grupo" => $exrow->id_grupo,
								"fabricante" => $exrow->fabricante,
								"id_fabricante" => $exrow->id_fabricante,
								"status" => ($exrow->inativo==0) ? "Ativo" : "Inativo"
							);
						}
					} else {
						$array_precos = [];
					}
				} else {
					$status = "Erro - ID TABELA vazio.";
					$array_precos = [];
				}
			} else {
				$status = "Erro - JSON vazio.";
				$array_precos = [];
			}
		} catch (Exception $e) {
			$status = "Erro - EXCEÇÃO: ".$e->getMessage();
			$array_precos = [];
		}

		$jsonRetorno = array(
			"precos" => $array_precos,
			"status" => $status
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>