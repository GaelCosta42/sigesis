<?php

  /**
   * Buscar produto
   *
   */
	define("_VALID_PHP", true);
	require('../init.php');		
	
			$id = post('id');
			$id_tabela = post('id_tabela');
			if($id > 0 and $id_tabela > 0) {
				$sql = "SELECT t.valor_venda, p.estoque, p.valor_avista, p.nome FROM produto_tabela as t, produto as p WHERE t.id_tabela = $id_tabela AND t.id_produto = $id AND t.id_produto = p.id LIMIT 50";
				$retorno_row = $db->first($sql);
				echo ($retorno_row) ? $retorno_row->valor_venda."#".$retorno_row->estoque."#".$retorno_row->valor_avista."#".$retorno_row->nome : "0";
			} else {
				echo "0";
			}
?>