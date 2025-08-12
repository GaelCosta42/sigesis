<?php
  /**
   * Webservices: Cliente Listar - Recebe um Id e retorna as informações do cliente
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');
	
	if (!$core->modulo_integracao){
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
			$cpf_cnpj = 0;	
			$status = 0;
			$retorno = "";
			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "cpf_cnpj")
						$cpf_cnpj = $valor;	
				}
				if($cpf_cnpj){
					$crow = $cadastro->getCadastrocpfcnpj(limparCPF_CNPJ($cpf_cnpj));  
					if($crow) {		
						
							$array_cliente = array(
								"id_cliente" => $crow->id,
								"nome" => $crow->nome,
								"razao_social" => $crow->razao_social,
								"contato" => $crow->contato,
								"cpf_cnpj" => $crow->cpf_cnpj,
								"email" => $crow->email,
								"email2" => $crow->email2,
								"telefone" => $crow->telefone,
								"telefone2" => $crow->telefone2,
								"celular" => $crow->celular,
								"celular2" => $crow->celular2,
								"cep" => $crow->cep,
								"endereco" => $crow->endereco,
								"numero" => $crow->numero,
								"complemento" => $crow->complemento,
								"bairro" => $crow->bairro,
								"cidade" => $crow->cidade,
								"estado" => $crow->estado,
								"rg_ie" => $crow->ie,
								"data_cadastro" => $crow->data_cadastro,
								"data_alteracao" => $crow->data_cadastro,
								"status" => ($crow->inativo==0) ? "Ativo" : "Inativo"
							);
						
						$status = 200;
						$retorno = "SUCESSO";
					} else {
						$array_cliente = 0;
						$status = 404;
						$retorno = "Erro - Cliente não cadastrado.";
					}
				} else {
					$status = 401;
					$retorno = "Erro - CPF_CNPJ vazio.";
					$array_cliente = 0;
				}
			} else {
				$retorno = "Erro - JSON vazio.";
				$status = 402;
				$array_cliente = 0;
			}
		} catch (Exception $e) {
			$retorno = "Erro - EXCEÇÃO: ".$e->getMessage();
			$status = 403;
			$array_cliente = 0;
		}

		$jsonRetorno = array(
			"cliente" => $array_cliente,
			"status" => $status,
			"retorno" => $retorno
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>