<?php
	
	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	define("_VALID_PHP", true);
	require('../init.php');	
	echo ' >> INICIO - '.date('d/m/Y H:i:s').'<br>';
		$retorno_row = $gestao->getCidades();
		if($retorno_row) {
			foreach ($retorno_row as $exrow){
				 $id = $exrow->id;
				 $cep = $exrow->cep;
				$geocode = file_get_contents('http://cep.republicavirtual.com.br/web_cep.php?formato=json&cep='.$cep);				
				$output= json_decode($geocode);
				$cidade = $output->cidade;
				$estado = $output->uf;
				$data = array(
					'cidade' => $cidade, 
					'estado' => $estado
				);
				if(strlen($cidade) > 1) {
					$db->update("cadastro", $data, "id= '" . $exrow->id."'");
					if ($db->affected()) {               
						echo ">> ".$cidade." / ".$estado."<br>";
					}
				} else {
					echo "Nao encontrado!<br>";
				}
			}
			unset($exrow);
		}
		echo ' >> FINAL - '.date('d/m/Y H:i:s').'<br>';
 
 


?>