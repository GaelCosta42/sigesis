<?php
  /**
   * Webservices: Login_Cliente
   *
   *   Login do usuário final que irá fazer o pedido de casa: SIGESIS N1 Pedidos
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');
	$id_usuario = 0;
	$code = 0;
	$usuario = "";
	$senha = "";
	$status = "";
	
	if ($core->tipo_sistema!=4){
		$json_erro = array(
			"code" => 400,
			"status" => "OPCAO NAO DISPONIVEL PARA ESTA EMPRESA. ENTRE EM CONTATO COM O SUPORTE."
		);
		$retorno =  json_encode($json_erro);
		echo $retorno;
	}
	else {
	
		try {
			$json_string = file_get_contents('php://input');
			$json_array = json_decode($json_string,true);
			
			if ($json_string) 
			{
				foreach($json_array as $nome_campo => $valor) 
				{
					if($nome_campo == "usuario")
						$usuario = $valor;	
					if($nome_campo == "senha")
						$senha = $valor;
				}
				$usuario = sanitize($usuario);
				$senha = sanitize(strtolower($senha));
				
				if($usuario != "")
				{
					$sql = "SELECT id, nome, senha_app FROM cadastro WHERE cpf_cnpj = '$usuario'";
					$row = $db->first($sql);
					
					if (!$row) 
					{
						$status = "Erro - Usuario nao encontrado";
						$code = 0;
					} 
					else 
					{
						$entered_pass = sha1($senha);
						if($entered_pass == $row->senha_app) 
						{
							$id_usuario = $row->id;
							$code = 200;
						} else 
						{
							$status = "Erro - Senha invalida";
							$code = 0;
						}
					}
				} 
				else 
				{
					$status = "Erro - CPF nao informado.";
					$code = 0;
				}
			} 
			else 
			{
				$status = "Erro - JSON vazio. ";
				$code = 0;
			}
		} catch (Exception $e) {
			$status .= "Erro - EXCE��O: ".$e->getMessage();
			$code = 0;
		}

		$id_usuario = intval($id_usuario);
		$jsonRetorno = array(
			"id_usuario" => $id_usuario,
			"code" => $code,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>