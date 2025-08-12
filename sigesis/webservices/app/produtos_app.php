<?php
  /**
   * Webservices: Produtos App - Listagem dos produtos para o Aplicativo
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');
	
	if (!$core->app_vendas && $core->tipo_sistema!=4){
		$array_produto = array();
		$json_erro = array(
			"produto" => $array_produto,
			"status" => "OPCAO NAO DISPONIVEL. LIGUE PARA (31) 3829-1950",
			"code" => 404
		);
		$retorno =  json_encode($json_erro);
		echo $retorno;
	}
	else {
		try {
			$code = 0;
			$status = "";
			$id_tabela = getValue('id', 'tabela_precos', 'aplicativo=1');
			if ($id_tabela) {
				
				$row_produto = $produto->getProdutosVendaApp($id_tabela);
				if($row_produto) {		
					foreach ($row_produto as $exrow){
						$imagem = "/uploads/data/".$exrow->imagem;
						$array_produto[] = array(
							"id" => $exrow->id,
							"nome" => $exrow->nome,
							"codigobarras" => $exrow->codigobarras,
							"estoque" => $exrow->estoque,
							"unidade" => $exrow->unidade,
							"foto" => $imagem,
							"preco" => $exrow->valor_venda,
							"categoria" => $exrow->categoria,
							"id_categoria" => $exrow->id_categoria,
							"grupo" => $exrow->grupo,
							"id_grupo" => $exrow->id_grupo,
							"fabricante" => $exrow->fabricante,
							"id_fabricante" => $exrow->id_fabricante,
							"valida_estoque" => $exrow->valida_estoque
						);
					}
					$code = 200;
				} else {
					$array_produto = array();
					$status = "OPCAO NAO DISPONIVEL. LIGUE PARA (31) 3829-1950";
					$code = 404;
				}
				
			} else {
				$code = 401;
				$status = lang('TABELA_PRECO_APLICATIVO_ERRO');
				$array_produto = array();
			}
		} catch (Exception $e) {
			$status = "Erro - EXCECAO: ".$e->getMessage();
			$code = 402;
			$array_produto = array();
		}

		$jsonRetorno = array(
			"produto" => $array_produto,
			"status" => $status,
			"code" => $code
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;

	}
?>