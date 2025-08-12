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
			echo ($quantidade) ? $quantidade : 0;
		} else {
			echo 'Não foi possível executar este script!';
		}
?>