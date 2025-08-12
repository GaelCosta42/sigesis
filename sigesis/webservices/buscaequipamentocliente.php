<?php

  /**
   * Buscar Itens de Produto por Produto
   *
   */
	define("_VALID_PHP", true);
	require_once('../init.php');		

	if(isset($_POST['id_cliente'])) 
	{
		$id_cliente = ($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;
		$retorno_row = $ordem_servico->getEquipamentos($id_cliente);
		if ($retorno_row)
		{
			foreach ($retorno_row as $row) 
			{
				echo '<option value="'.$row->id.'">'.$row->equipamento.' ['.lang('ETIQUETA').': '.$row->etiqueta.']'.' ['.$row->codigo_referencia.']</option>';
			}
		}
	} 
	else 
	{
		echo 'Não foi possível executar este script!';
	}
?>