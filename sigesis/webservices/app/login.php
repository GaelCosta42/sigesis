<?php
  /**
   * Webservices: Login: SIGESIS VENDAS
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
			"code" => 400,
			"status" => "OPCAO NAO DISPONIVEL. LIGUE PARA (31) 3829-1950",
		);
		$retorno =  json_encode($json_erro);
		echo $retorno;
	}
	else {
	
		try {
			$json_string = file_get_contents('php://input');
			$json_array = json_decode($json_string,true);
			$id_caixa = 0;
			$id_usuario = 0;
			$nivel = 0;
			$retorno = 0;
			$nome_usuario = "";
			$usuario = "";
			$senha = "";
			$pin = "";
			$status = "";
			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "usuario")
						$usuario = $valor;	
					if($nome_campo == "senha")
						$senha = $valor;
					if($nome_campo == "pin")
						$pin = $valor;
				}
				$nomeusuario = sanitize(strtolower($usuario));
				$nomeusuario = $db->escape($nomeusuario);
				$senha = sanitize(strtolower($senha));
				if ($pin != "") {
					$entered_pin = sha1($pin);
					$sql = "SELECT id, id_empresa, usuario, nome, nivel, active FROM usuario WHERE pin = '" . $entered_pin . "'";
					$result = $db->query($sql);
					if ($db->numrows($result) == 0) {
						$retorno = 0;
					} else {
						$row = $db->fetch($result);
						if($row->active == "y") {
							$id_usuario = $row->id;
							$id_empresa = $row->id_empresa;
							$usuario = $row->usuario;
							$nome_usuario = $row->nome;
							$nivel = $row->nivel;
							$id_caixa = $faturamento->verificaCaixa($id_usuario);
							if(!$id_caixa) {
								$data = array(
									'id_abrir' => $id_usuario,
									'data_abrir' => "NOW()",
									'status' => '1',
									'usuario' => $usuario,
									'data' => "NOW()"
								);
								$id_caixa = $db->insert("caixa", $data);
							}
							$sql_empresa = "SELECT id, nome, endereco, numero, bairro, cidade, estado, telefone, cnpj FROM empresa WHERE id = '$id_empresa' ";
							$res_empresa = $db->query($sql_empresa);
							if ($db->numrows($res_empresa) == 0) {
								$array_empresa = [];
							} else {
								$row_empresa = $db->fetch($res_empresa);
								$array_empresa[] = array(
									"id" => $row_empresa->id,
									"nome" => $row_empresa->nome,
									"endereco" => $row_empresa->endereco,
									"numero" => $row_empresa->numero,
									"bairro" => $row_empresa->bairro,
									"cidade" => $row_empresa->cidade,
									"estado" => $row_empresa->estado,
									"telefone" => $row_empresa->telefone,
									"cnpj" => formatar_cpf_cnpj($row_empresa->cnpj)
								);
							}
							$retorno = 1;
						} else {
							$retorno = 0;
						}
					}
				} elseif($usuario != ""){
					$sql = "SELECT id, id_empresa, usuario, nome, senha, nivel, active FROM usuario WHERE active = 'y' AND usuario = '" . $nomeusuario . "'";
					$result = $db->query($sql);
					if ($db->numrows($result) == 0) {
						$retorno = 0;
					} else {
						$row = $db->fetch($result);
						$entered_pass = sha1($senha);
						if($row->active == "y" && $entered_pass == $row->senha) {
							$id_usuario = $row->id;
							$id_empresa = $row->id_empresa;
							$usuario = $row->usuario;
							$nome_usuario = $row->nome;
							$nivel = $row->nivel;
							$id_caixa = $faturamento->verificaCaixa($id_usuario);
							if(!$id_caixa) {
								$data = array(
									'id_abrir' => $id_usuario,
									'data_abrir' => "NOW()",
									'status' => '1',
									'usuario' => $usuario,
									'data' => "NOW()"
								);
								$id_caixa = $db->insert("caixa", $data);
							}
							$sql_empresa = "SELECT id, nome, endereco, numero, bairro, cidade, estado, telefone, cnpj FROM empresa WHERE id = '$id_empresa' ";
							$res_empresa = $db->query($sql_empresa);
							if ($db->numrows($res_empresa) == 0) {
								$array_empresa = [];
							} else {
								$row_empresa = $db->fetch($res_empresa);
								$array_empresa[] = array(
									"id" => $row_empresa->id,
									"nome" => $row_empresa->nome,
									"endereco" => $row_empresa->endereco,
									"numero" => $row_empresa->numero,
									"bairro" => $row_empresa->bairro,
									"cidade" => $row_empresa->cidade,
									"estado" => $row_empresa->estado,
									"telefone" => $row_empresa->telefone,
									"cnpj" => formatar_cpf_cnpj($row_empresa->cnpj)
								);
							}
							$retorno = 1;
						} else {
							$retorno = 0;
						}
					}
				} else {
					$status .= "Erro - USUARIO ou PIN nao informado.";
				}
			} else {
				$status .= "Erro - JSON vazio. ";
			}
		} catch (Exception $e) {
			$status .= "Erro - EXCE��O: ".$e->getMessage();
		}

		$id_usuario = intval($id_usuario);
		$jsonRetorno = array(
			"id_usuario" => $id_usuario,
			"nome_usuario" => $nome_usuario,
			"array_empresa" => $array_empresa,
			"nivel" => $nivel,
			"id_caixa" => $id_caixa,
			"retorno" => $retorno,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>