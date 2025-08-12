<?php
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	define("_VALID_PHP", true);
	require('../init.php');	
	$results = array();
	$query = $_REQUEST["query"];
	$id_tabela = $_REQUEST["id_tabela"];
	$retorno_row = $produto->getListaProdutos($query, $id_tabela );
	if($retorno_row) {
		foreach ($retorno_row as $exrow){
			$results[] = array(
				"id" => $exrow->id,
				"nome" => $exrow->nome,
				"codigonota" => $exrow->codigonota,
				"ncm" => $exrow->ncm,
				"estoque" => $exrow->estoque,
				"valor_venda" => $exrow->valor_venda,
				"valor" => moeda($exrow->valor_venda),
				"valor_custo" => moeda($exrow->valor_custo),
				"tokens" => array($query, $exrow->nome)
			);
		}
		unset($exrow);
	}
	echo json_encode($results);
?>