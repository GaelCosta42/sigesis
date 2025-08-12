<?php
  /**
   * Webservices: Vendas
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');

	if (!$core->app_vendas)	{
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
			$id_cadastro = 0;
			$nome_cliente = "";
			$razao_social = "";
			$cpf_cnpj = "";
			$contato_cliente = "";
			$telefone_cliente = "";
			$celular_cliente = "";
			$cep_cliente = "";
			$endereco_cliente = "";
			$numero_cliente = "";
			$complemento_cliente = "";
			$bairro_cliente = "";
			$cidade_cliente = "";
			$estado_cliente = "";
			$email_cliente = "";
			$id_vendedor = 0;
			$code = 0;

			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "nome_cliente")
						$nome_cliente = $valor;
					if($nome_campo == "razao_social")
						$razao_social = $valor;
					if($nome_campo == "cpf_cnpj")
						$cpf_cnpj = $valor;
					if($nome_campo == "contato_cliente")
						$contato_cliente = $valor;
					if($nome_campo == "telefone_cliente")
						$telefone_cliente = $valor;
					if($nome_campo == "celular_cliente")
						$celular_cliente = $valor;
					if($nome_campo == "cep_cliente")
						$cep_cliente = $valor;
					if($nome_campo == "endereco_cliente")
						$endereco_cliente = $valor;
					if($nome_campo == "numero_cliente")
						$numero_cliente = $valor;
					if($nome_campo == "complemento_cliente")
						$complemento_cliente = $valor;
					if($nome_campo == "bairro_cliente")
						$bairro_cliente = $valor;
					if($nome_campo == "cidade_cliente")
						$cidade_cliente = $valor;
					if($nome_campo == "estado_cliente")
						$estado_cliente = $valor;
					if($nome_campo == "email_cliente")
						$email_cliente = $valor;
					if($nome_campo == "id_usuario")
						$id_vendedor = $valor;
				}

				$nome_cliente = sanitize(strtoupper($nome_cliente));
				$nomeusuario = getValue('usuario', 'usuario', 'id='.$id_vendedor);
				$cliente_sistema = (!empty($cpf_cnpj)) ? getValue('id', 'cadastro', 'cpf_cnpj='.$cpf_cnpj) : 0;
				
				if ($cliente_sistema){
					$code = 0;
					$status = "ERRO! CNPJ/CPF ja cadastrado no sistema";
				} else {
					$data_cadastro = array(
						'nome' => html_entity_decode($nome_cliente, ENT_QUOTES, 'UTF-8'),
						'razao_social' => html_entity_decode($razao_social, ENT_QUOTES, 'UTF-8'),
						'contato' => $contato_cliente,
						'telefone'=> $telefone_cliente,
						'celular' => $celular_cliente,
						'cep' => $cep_cliente,
						'endereco' => $endereco_cliente,
						'numero' => $numero_cliente,
						'complemento' => $complemento_cliente,
						'bairro' => $bairro_cliente,
						'cidade' => $cidade_cliente,
						'estado' => $estado_cliente,
						'email' => $email_cliente,
						'cliente' => '1',
						'usuario' => $nomeusuario,
						'data' => "NOW()"
					);

					if(!empty($cpf_cnpj))
						$data_cadastro['cpf_cnpj'] = $cpf_cnpj;

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
				$status = "Erro - JSON vazio.";
				$retorno = 0;
			}
		} catch (Exception $e) {
			$status = "Erro - EXCEÇÃO: ".$e->getMessage();
			$code = 0;
		}

		$jsonRetorno = array(
			"id_cliente" => $id_cadastro,
            "code" => $code,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>