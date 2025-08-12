<?php
  /**
   * Webservices: Login para o entregador dos pedidos
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
			$nome_usuario = "";
			$retorno = 0;
			$usuario = "";
			$senha = "";
			$status = "";

			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "usuario")
						$usuario = $valor;
					if($nome_campo == "senha")
						$senha = $valor;
				}
				$nomeusuario = sanitize(strtolower($usuario));
				$nomeusuario = $db->escape($nomeusuario);
				$senha = sanitize(strtolower($senha));

				if($usuario != ""){
					$sql = "SELECT id, nome, senha, active FROM usuario WHERE active = 'y' AND usuario = '" . $nomeusuario . "'";
					$result = $db->query($sql);
					if ($db->numrows($result) == 0) {
						$retorno = 0;
					} else {
						$row = $db->fetch($result);
						$entered_pass = sha1($senha);
						if($row->active == "y" && $entered_pass == $row->senha) {
							$id_usuario = $row->id;
							$nome_usuario = $row->nome;
							$retorno = 1;
						} else {
							$retorno = 0;
						}
					}
				} else {
					$status .= "Erro - USUARIO nao informado.";
				}
			} else {
				$status .= "Erro - JSON vazio. ";
			}
		} catch (Exception $e) {
			$status .= "Erro - EXCE��O: ".$e->getMessage();
		}

		$id_usuario = intval($id_usuario);
		$jsonRetorno = array(
			"id" => $id_usuario,
			"nome" => $nome_usuario,
			"retorno" => $retorno,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>