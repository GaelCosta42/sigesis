<?php

  /**
   * Buscar conta
   *
   */
	define("_VALID_PHP", true);
	require('../init.php');		
	
	if(isset($_POST['id_pai'])) {
			$id_pai = $_POST['id_pai'];
			$retorno_row = $faturamento->getFilho($id_pai);
			echo '<option value=""></option>';
			foreach ($retorno_row as $row) {
				echo '<option value="'.$row->id_filho.'">'.$row->filho.'</option>';
			}
		} else {
			echo 'Não foi possível executar este script!';
		}
?>