<?php
	
	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	$db = new mysqli('localhost', 'inov9' ,'teste', 'inov9');
	
	if(!$db) {
		// Show error if we cannot connect.
		echo 'ERRO: Não foi possível conectar no banco de dados.';
	} else {
				$db->query('SET NAMES utf8');
				$query = $db->query("SELECT id, cmv FROM produto ORDER BY cmv DESC");
				if($query) {
					// While there are results loop through them - fetching an Object (i like PHP5 btw!).
					$acumulado = 0;
					while ($result = $query ->fetch_object()) {
						$cmv = $result->cmv;
						$id = $result->id;
						$acumulado += $cmv;
						if($acumulado < 80)
							$curva = "A";
						elseif($acumulado < 95)
							$curva = "B";
						else $curva = "C";
						$update = $db->query("UPDATE produto SET curva_abc = '$curva' WHERE id=$id");						
					}
					echo 'SUCESSO: CURVA ABC cadastrada com sucesso.';
				} else {
					echo 'ERRO: Existe um problema no select.';
				}
	}
?>