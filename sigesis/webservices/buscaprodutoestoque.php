<?php

  /**
   * Buscar estoque
   *
   */
	define("_VALID_PHP", true);
	require('../init.php');		
	
	if(isset($_POST['id_produto'])) {
			$id_produto = $_POST['id_produto'];
			$quantidade = $produto->getEstoqueTotal($id_produto, null);
			$valor_custo = getValue("valor_custo","produto","id=".$id_produto);
			echo ($quantidade) ? $quantidade.'#'.$valor_custo : 0;
		} else {
			echo 'Não foi possível executar este script!';
		}
?>