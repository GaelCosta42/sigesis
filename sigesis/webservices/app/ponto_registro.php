<?php
  /**
   * Webservices: Registro de ponto
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');
	
	if (!$core->modulo_ponto){
		$json_erro = array(
			"status" => 400,
			"retorno" => "OPCAO NAO DISPONIVEL. LIGUE PARA (31) 3829-1950"
		);
		$retorno =  json_encode($json_erro);
		echo $retorno;
	}
	else {
	
		try {
			
			$json_string = file_get_contents('php://input');
			$json_array = json_decode($json_string,true);
			$retorno = 0;
			$id_ponto = 0;
			$id_registro = 0;
			$id_usuario = 0;
			$operacao = 0;
			$data_operacao = "";
			$longitude = 0;
			$latitude = 0;
			
			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "id_registro")
						$id_registro = $valor;
					if($nome_campo == "id_usuario")
						$id_usuario = $valor;
					if($nome_campo == "operacao")
						$operacao = $valor;
					if($nome_campo == "data_operacao")
						$data_operacao = $valor;
					if($nome_campo == "ocorrencia")
						$ocorrencia = $valor;
					if($nome_campo == "longitude")
						$longitude = $valor;
					if($nome_campo == "latitude")
						$latitude = $valor;
				}
				
				if($id_usuario and $id_registro){
					$data = array(
						'id_usuario' => $id_usuario,
						'operacao' => $operacao,
						'data_operacao' => $data_operacao,
						'lat' => $latitude,
						'lng' => $longitude,
						'usuario' => 'app',
						'data' => "NOW()"
					);			 
					$id_ponto = $db->insert("ponto_eletronico", $data);
					
					$data_ocorrencia = array(
						'id_ponto' => $id_ponto,
						'id_usuario' => $id_usuario,
						'data_operacao' => $data_operacao,
						'ocorrencia' => sanitize($ocorrencia),
						'usuario' => 'app',
						'data' => "NOW()"
					);
					$db->insert("ponto_ocorrencia", $data_ocorrencia);
					if ($db->affected()) {	
						$status = "Sucesso. Ponto registrado.";
						$retorno = 1;
					  }
				} else {
				$status = "Erro - ID_REGISTRO ou ID_USUARIO invalidos.";
				}
			} else {
				$status = "Erro - JSON vazio.";
			}
		} catch (Exception $e) {
				$status = "Erro - JSON[".$json_string."] EXCEÇÃO: ".$e->getMessage();
		}

		$jsonRetorno = array(
			"id_registro" => $id_registro,
			"id_ponto" => $id_ponto,
			"retorno" => $retorno,
			"status" => $status,
			"json" => $json_string
		);
		echo json_encode($jsonRetorno);
	}
?>