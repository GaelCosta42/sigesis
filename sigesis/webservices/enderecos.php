<?php
	
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	define("_VALID_PHP", true);
	require('../init.php');	
	echo ' >> INICIO - '.date('d/m/Y H:i:s').'<br>';
		$retorno_row = $gestao->getEnderecos();
		if($retorno_row) {
			foreach ($retorno_row as $exrow){
				$endereco = $exrow->endereco.",".$exrow->cidade.",".$exrow->estado.",".$exrow->cep;
				$endereco = strtolower(str_replace(" ","+",$endereco));
				$endereco = strtolower(str_replace(",","+",$endereco));
				$endereco = strtolower(str_replace("-","+",$endereco));
				$geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$endereco.',+CA&key=AIzaSyD0FcX2XLqCAey-JJFAX5cfLxl59WcYXs0');
				$output= json_decode($geocode);
				$lat = $output->results[0]->geometry->location->lat;
				$lng = $output->results[0]->geometry->location->lng;
				$data = array(
					'lat' => $lat, 
					'lng' => $lng,
					'mapa' => 1
				);
				if(strlen($lat) > 1) {
					$db->update("cadastro", $data, "id= '" . $exrow->id."'");
					if ($db->affected()) {               
						echo ">> ".$lat." - ".$lng."<br>";
					}
				} else {
					echo "Nao encontrado!<br>";
				}
			}
			unset($exrow);
		}
		echo ' >> FINAL - '.date('d/m/Y H:i:s').'<br>';
 
 


?>