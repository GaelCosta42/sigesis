<?php
  /**
   * Webservices: Fechar caixa
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');
	
	if (!$core->app_vendas){
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
			$id_usuario = 0;
			$id_caixa = 0;
			
			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "id_caixa")
						$id_caixa = $valor;
					if($nome_campo == "id_usuario")
						$id_vendedor = $valor;
				}
				
				if($id_caixa){
					$data = array(
						'id_fechar' => $id_fechar,
						'data_fechar' => "NOW()",
						'status' => '2',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$db->update("caixa", $data, "id=" . $id_caixa);
					$status = "Sucesso. Caixa fechado.";
					$retorno = 1;
				} else {
					$status = "Erro - ID CAIXA não preenchido.";
					$retorno = 0;
				}
			} else {
				$status = "Erro - JSON vazio.";
				$retorno = 0;
			}
		} catch (Exception $e) {
				$status = "Erro - EXCEÇÃO: ".$e->getMessage();
				$retorno = 0;
		}

		$jsonRetorno = array(
			"id_caixa" => $id_caixa,
			"retorno" => $retorno,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}

?>