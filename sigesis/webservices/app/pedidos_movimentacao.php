<?php
  /**
   * Webservices: Login
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');
	
	if ($core->tipo_sistema!=4){
		$json_erro = array(
			"status" => 400,
			"retorno" => "OPCAO NAO DISPONIVEL. LIGUE PARA (31) 3829-1950"
		);
		$retorno =  json_encode($json_erro);
		echo $retorno;
	} else {
	
		try {
			$json_string = file_get_contents('php://input');
			$json_array = json_decode($json_string,true);
			$id_entregador = 0;
			$id_venda = 0;
			$id_status = 0;
			$retorno = 0;
			$status = "";
			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "id_entregador")
						$id_entregador = $valor;	
					if($nome_campo == "id_venda")
						$id_venda = $valor;
					if($nome_campo == "id_status")
						$id_status = $valor;
				}
				if($id_entregador && $id_venda && $id_status){
					$nomeusuario = getValue('usuario', 'usuario', 'id='.$id_entregador);
					$data_venda = array(
						'status_entrega' => $id_status,
						'usuario' => $nomeusuario,
						'data' => "NOW()"
					);
					
					if ($id_status==2)
						$data_venda['id_entregador'] = $id_entregador;
					
					if ($id_status==3)
						$data_venda['data_entrega'] = "NOW()";
					
					$db->update("vendas", $data_venda, "id=".$id_venda);
					
					if ($db->affected()) {	
						$retorno = 1;
						$status .= "Sucesso. Status atualizado.";
					} else {
						$retorno = 0;
						$status .= "Erro - Falha ao atualizar o banco de dados.";
					}
				} else {
					$status .= "Erro - Verificar os itens enviados.";
				}
			} else {
				$status .= "Erro - JSON vazio. ";
			}
		} catch (Exception $e) {
			$status .= "Erro - EXCEวรO: ".$e->getMessage();
		}

		$jsonRetorno = array(
			"sucesso" => $retorno,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>