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
			$status = "";
			$row_clientes = $cadastro->getTodosClienteAtivos();
			if($row_clientes) {
				foreach($row_clientes as $crow) {
					$array_clientes[] = array(
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
				}		
				$status = 200;
			} else {
				$array_cliente = 0;
				$status = "Erro - Nenhum cliente encontrado.";
			}
		} catch (Exception $e) {
			$status = "Erro - EXCEÇÃO: ".$e->getMessage();
			$array_cliente = 0;
		}

		$jsonRetorno = array(
			"clientes" => $array_clientes,
			"status" => $status
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>