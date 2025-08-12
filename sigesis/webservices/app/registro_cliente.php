<?php
  /**
   * Webservices: Registro de novo cliente para pedidos via app
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

			$nome = "";
			$email = "";
			$telefone = "";
			$celular = "";
			$cpf = "";
			$senha = "";
			$code = 0;
			$status = "";
			$id_cadastro = 0;
			
			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "nome")
						$nome = $valor;	
					if($nome_campo == "email")
						$email = $valor;
					if($nome_campo == "telefone")
						$telefone = $valor;
					if($nome_campo == "celular")
						$celular = $valor;
					if($nome_campo == "cpf")
						$cpf = $valor;
					if($nome_campo == "senha")
						$senha = $valor;
				}
				
				$cpf = limparCPF_CNPJ($cpf);
				$senha = sha1(strtolower($senha));
				$nome = sanitize(strtoupper($nome));
				
				if (validaCPF_CNPJ($cpf)) {
					$cliente_sistema = getValue('id', 'cadastro', 'cpf_cnpj='.$cpf);
					if ($cliente_sistema){
						$code = 0;
						$status = "ERRO! CPF ja cadastrado no sistema";
					} else {
						$sql_empresa = "SELECT id FROM empresa WHERE inativo = 0";
						$row_empresa = $db->first($sql_empresa);
						$id_empresa = ($row_empresa) ? $row_empresa->id : 0;
						$data_cadastro = array(
							'id_empresa' => $id_empresa,
							'nome' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
							'tipo' => 2,
							'cpf_cnpj' => $cpf,
							'senha_app' => $senha,
							'email' => $email,
							'telefone' => $telefone,
							'celular' => $celular,
							'cliente' => '1',
							'usuario' => 'APP',
							'data' => "NOW()"
						);
						$id_cadastro = $db->insert("cadastro", $data_cadastro);
						
						if ($db->affected()){
							$code = 200;
							$status = "Cadastro efetivado com sucesso";
						} else {
							$code = 0;
							$status = "ERRO! Cadastro nao realizado";
						}
					}
				} else {
					$code = 0;
					$status = "ERRO! CPF invalido";
				}			
			} else {
				$status = "Erro - JSON vazio.";
				$code = 0;
			}
		} catch (Exception $e) {
				$status = "Erro - EXCECAO: ".$e->getMessage();
				$code = 0;
		}

		$jsonRetorno = array(
			"id_usuario" => $id_cadastro,
			"code" => $code,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>