<?php
  /**
   * Webservices: Ponto Eletronico
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
			
			$nome_usuario = "";
			$usuario = "";
			$status = "";
			$retorno = 0;
			$id_usuario = (get('id_usuario')) ? get('id_usuario') : post('id_usuario');
			if ($id_usuario) {
				$row_ponto = $rh->getPontoEletronicoApp($id_usuario);
				if($row_ponto) {
					$retorno = 1;
					foreach ($row_ponto as $exrow){
						$nome_usuario = $exrow->nome;
						$usuario = $exrow->usuario;
						$pontos[] = array(
							"id_ponto" => $exrow->id,
							"operacao" => $exrow->operacao,
							"data_operacao" => $exrow->data_operacao,
							"horas" => $exrow->horas
						);
					}
				} else {
					$status .= "Erro - PONTO: Nenhum ponto encontrado. ";
					$pontos = [];
				}
			} else {
				$status .= "Erro USUARIO - ID do usuario vazio. ";
				$pontos = [];
			}
		} catch (Exception $e) {
			$status .= "Erro - EXCEวรO: ".$e->getMessage();
			$pontos = [];
		}

		$id_usuario = intval($id_usuario);
		$jsonRetorno = array(
			"cod" => $retorno,
			"id_usuario" => $id_usuario,
			"nome" => $nome_usuario,
			"usuario" => $usuario,
			"pontos" => $pontos,
			"retorno" => $status,
			"status" => $status
		);
		$retorno =  json_encode($jsonRetorno, JSON_UNESCAPED_UNICODE);
		echo $retorno;
	}
?>