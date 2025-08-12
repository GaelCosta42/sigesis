<?php

  /**
   * Buscar servico
   *
   * @package Sistemas Divulgação Online
   */
	define("_VALID_PHP", true);
	require('../init.php');		
	
	if(isset($_POST['id'])) {
			$id = $_POST['id'];
			$sql = ("SELECT valor_servico FROM servico WHERE id = $id");
			$retorno_row = $db->first($sql);
			echo ($retorno_row) ? $retorno_row->valor_servico : 0;
		} else {
			echo 'Não foi possível executar este script!';
		}
?>