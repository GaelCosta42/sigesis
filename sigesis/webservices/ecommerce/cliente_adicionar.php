<?php
  /**
   * Webservices: cliente_adicionar - Cadastrar um novo cliente no sistema
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');

	if (!$core->modulo_integracao) {
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
			$status = 0;
			$retorno = "";
			$id_cliente = 0;
			$array_cliente = array(20);
			for ($i=0;$i<20; $i++)
				$array_cliente[$i] = 0;

			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "nome")
						$array_cliente[0] = strtoupper(sanitize($valor));
					if($nome_campo == "razao_social")
						$array_cliente[1] = strtoupper(sanitize($valor));
					if($nome_campo == "tipo_pessoa") //1 = Juridica 2 = Fisica
						$array_cliente[2] = sanitize($valor);
					if($nome_campo == "cpf_cnpj")
						$array_cliente[3] = limparCPF_CNPJ($valor);
					if($nome_campo == "email")
						$array_cliente[4] = strtoupper(sanitize($valor));
					if($nome_campo == "email2")
						$array_cliente[5] = strtoupper(sanitize($valor));
					if($nome_campo == "telefone")
						$array_cliente[6] = sanitize($valor);
					if($nome_campo == "telefone2")
						$array_cliente[7] = sanitize($valor);
					if($nome_campo == "celular")
						$array_cliente[8] = sanitize($valor);
					if($nome_campo == "celular2")
						$array_cliente[9] = sanitize($valor);
					if($nome_campo == "cep")
						$array_cliente[10] = sanitize($valor);
					if($nome_campo == "endereco")
						$array_cliente[11] = strtoupper(sanitize($valor));
					if($nome_campo == "numero")
						$array_cliente[12] = sanitize($valor);
					if($nome_campo == "complemento")
						$array_cliente[13] = strtoupper(sanitize($valor));
					if($nome_campo == "bairro")
						$array_cliente[14] = strtoupper(sanitize($valor));
					if($nome_campo == "cidade")
						$array_cliente[15] = strtoupper(sanitize($valor));
					if($nome_campo == "estado")
						$array_cliente[16] = strtoupper(sanitize($valor));
					if($nome_campo == "rg_ie")
						$array_cliente[17] = strtoupper(sanitize($valor));
					if($nome_campo == "data_cadastro")
						$array_cliente[18] = sanitize($valor);
					if($nome_campo == "data_alteracao")
						$array_cliente[19] = sanitize($valor);
				}

				if (!empty($array_cliente[3])) {
					$sql = "SELECT c.nome " 
					  . "\n FROM cadastro as c "
					  . "\n WHERE c.cpf_cnpj = '$array_cliente[3]'";
					$row = $db->first($sql);

					if ($row) {
						$status = 400;
						$retorno = str_replace("[NOME]", $row->nome, lang('MSG_ERRO_CPF_CNPJ_CADASTRADO'));
					} elseif(empty($array_cliente[0])) {
						$status = 405;
						$retorno = "O campo NOME é obrigatório";
					} elseif(empty($array_cliente[2])) {
						$status = 405;
						$retorno = "O campo TIPO PESSOA é obrigatório";
					} elseif( empty($array_cliente[6]) && empty($array_cliente[7]) && empty($array_cliente[8])  && empty($array_cliente[9])) {
						$status = 405;
						$retorno = "Informe pelo menos um telefone";
					} elseif(empty($array_cliente[11])) {
						$status = 405;
						$retorno = "O campo ENDEREÇO é obrigatório";
					} elseif(empty($array_cliente[15])) {
						$status = 405;
						$retorno = "O campo CIDADE é obrigatório";
					} else {
						$array_cadastro = array(
							'id_empresa' => 1,
							'nome' => html_entity_decode($array_cliente[0], ENT_QUOTES, 'UTF-8'),
							'razao_social' => html_entity_decode($array_cliente[1], ENT_QUOTES, 'UTF-8'), 
							'tipo' => $array_cliente[2], 
							'cpf_cnpj' => $array_cliente[3], 
							'email' => $array_cliente[4],
							'email2' => $array_cliente[5],
							'telefone' => $array_cliente[6],
							'telefone2' => $array_cliente[7],
							'celular' => $array_cliente[8],
							'celular2' => $array_cliente[9],
							'cep' => $array_cliente[10],
							'endereco' => $array_cliente[11],
							'numero' => $array_cliente[12],
							'complemento' => $array_cliente[13],
							'bairro' => $array_cliente[14],
							'cidade' => $array_cliente[15],
							'estado' => $array_cliente[16],
							'ie' => $array_cliente[17],
							'data_cadastro' => $array_cliente[18],
							'cliente' => 1,
							'fornecedor' => 0,
							'inativo' => 0,
							'usuario' => 'Externo',
							'data' => $array_cliente[19]
						);
						$id_cliente = $db->insert("cadastro", $array_cadastro);

						$data_endereco = array(
							'id_cadastro' => $id_cliente,
							'cep' => $array_cliente[10],
							'endereco' => $array_cliente[11],
							'numero' => $array_cliente[12],
							'complemento' => $array_cliente[13],
							'bairro' => $array_cliente[14],
							'cidade' => $array_cliente[15],
							'estado' => $array_cliente[16],
							'referencia' => 'ENDERECO FATURAMENTO', 
							'faturamento' => '1',
							'inativo' => '0',
							'usuario' => 'Externo',
							'data' => "NOW()"
						);
						$db->insert("cadastro_endereco", $data_endereco);

						$status = 200;
						$retorno = "Sucesso! Cliente cadastrado.";
					}
				} else {
					$status = 401;
					$retorno = "ERRO! CPF ou CNPJ não informado.";
				}
			} else {
				$status = 402;
				$retorno = "Erro - JSON vazio.";
			}
		} catch (Exception $e) {
				$status = 403;
				$retorno = "Erro - EXCEÇÃO: ".$e->getMessage();;
		}
		$jsonRetorno = array(
			"id_cliente" => $id_cliente,
			"retorno" => $retorno,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>