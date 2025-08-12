<?php
	
	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	$db = new mysqli('localhost', 'inov9' ,'teste', 'inov9');
	
	if(!$db) {
		// Show error if we cannot connect.
		echo 'ERRO: Não foi possível conectar no banco de dados.';
	} else {
				$db->query('SET NAMES utf8');
				$query = $db->query("select id, nome, custo_medio, quantidade, consumo_medio, (consumo_medio*custo_medio) as custo_trimestral, (select sum(consumo_medio*custo_medio) from produto) as total, (consumo_medio*custo_medio)/(select sum(consumo_medio*custo_medio) from produto)*100 as cmv from produto");
				if($query) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					while ($result = $query ->fetch_object()) {
						$cmv = $result->cmv;
						$id = $result->id;
						$update = $db->query("UPDATE produto SET cmv = $cmv WHERE id=$id");						
					}
					echo 'SUCESSO: CVM cadastrado com sucesso.';
				} else {
					echo 'ERRO: Existe um problema no select.';
				}
	}
?>