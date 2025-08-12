<?php

  /**
   * Buscar codigo de barras
   *
   */
	define("_VALID_PHP", true);
	require('../init.php');		
	
	if(isset($_POST['codigo'])) {
			$codigo = post('codigo');
			$id_tabela = post('id_tabela');
			
			$sql = "SELECT t.valor_venda, p.estoque, p.id, p.nome, p.valor_avista, p.unidade, p.descricao_unidade, p.codigo 
			FROM produto_tabela as t, produto as p 
			WHERE p.grade = 1 AND p.inativo = 0 AND t.id_tabela = $id_tabela AND p.codigobarras = '$codigo' AND t.id_produto = p.id ";
			
			$retorno_row = $db->first($sql);
			
			$nome = ($retorno_row) ? str_replace('#', '', $retorno_row->nome) : 'NAO ENCONTRADO';
			
			echo ($retorno_row) ? $retorno_row->valor_venda."#".$retorno_row->estoque."#".$retorno_row->id."#".$nome."#".$retorno_row->valor_avista."#".$retorno_row->unidade."#".$retorno_row->codigo : "0";
		} else {
			echo '0';
		}
?>