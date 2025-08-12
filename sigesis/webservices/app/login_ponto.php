<?php
  /**
   * Webservices: Login Ponto
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
			$json_string = file_get_contents('php://input');
			$json_array = json_decode($json_string,true);
			$id_usuario = 0;
			$retorno = 0;
			$nome_usuario = "";
			$telefone = "";
			$endereco = "";
			$numero = "";
			$complemento = "";
			$bairro = "";
			$cidade = "";
			$estado = "";
			$cpf = "";
			$identidade = "";
			$ctps = "";
			$pis = "";
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
					$sql = "SELECT id, nome, id_empresa, telefone, endereco, numero, complemento, bairro, cidade, estado, cpf, identidade, ctps, pis, active FROM usuario WHERE pin = '" . $entered_pin . "'";
					$result = $db->query($sql);
					if ($db->numrows($result) == 0) {
						$retorno = 0;
						$status .= "Erro - PIN: PIN nใo encontrado. ";
						$pontos = [];
					} else {
						$row = $db->fetch($result);
						if($row->active == "y") {
							$id_usuario = $row->id;
							$nome_usuario = $row->nome;
							$id_empresa = $row->id_empresa;
							$telefone = $row->telefone;
							$endereco = $row->endereco;
							$numero = $row->numero;
							$complemento = $row->complemento;
							$bairro = $row->bairro;
							$cidade = $row->cidade;
							$estado = $row->estado;
							$cpf = $row->cpf;
							$identidade = $row->identidade;
							$ctps = $row->ctps;
							$pis = $row->pis;
							$retorno = 1;
							$row_ponto = $rh->getPontoEletronicoApp($id_usuario);
							if($row_ponto) {
								foreach ($row_ponto as $exrow){
									$pontos[] = array(
										"id_ponto" => $exrow->id,
										"operacao" => $exrow->operacao,
										"data_operacao" => $exrow->data_operacao,
										"horas" => $exrow->horas
									);
								}
								unset($exrow);
							} else {
								$status .= "Erro - PONTO: Nenhum ponto encontrado. ";
								$pontos = [];
							}
						} else {
							$retorno = 0;
							$status .= "Erro - USUARIO: Usuแrio nใo ativo. ";
							$pontos = [];
						}
					}
				} elseif($usuario != ""){
					$sql = "SELECT id, nome, id_empresa, telefone, endereco, numero, complemento, bairro, cidade, estado, cpf, identidade, ctps, pis, senha, active FROM usuario WHERE active = 'y' AND usuario = '" . $nomeusuario . "'";
					$result = $db->query($sql);
					if ($db->numrows($result) == 0) {
						$retorno = 0;
						$status .= "Erro - USUARIO: Usuแrio nใo encontrado. ";
						$pontos = [];
					} else {
						$row = $db->fetch($result);
						$entered_pass = sha1($senha);
						if($row->active == "y" && $entered_pass == $row->senha) {
							$id_usuario = $row->id;
							$nome_usuario = $row->nome;
							$id_empresa = $row->id_empresa;
							$telefone = $row->telefone;
							$endereco = $row->endereco;
							$numero = $row->numero;
							$complemento = $row->complemento;
							$bairro = $row->bairro;
							$cidade = $row->cidade;
							$estado = $row->estado;
							$cpf = $row->cpf;
							$identidade = $row->identidade;
							$ctps = $row->ctps;
							$pis = $row->pis;
							$retorno = 1;
							$row_ponto = $rh->getPontoEletronicoApp($id_usuario);
							if($row_ponto) {
								foreach ($row_ponto as $exrow){
									$pontos[] = array(
										"id_ponto" => $exrow->id,
										"operacao" => $exrow->operacao,
										"data_operacao" => $exrow->data_operacao,
										"horas" => $exrow->horas
									);
								}
								unset($exrow);
							} else {
								$status .= "Erro - PONTO: Nenhum ponto encontrado. ";
								$pontos = [];
							}
						} else {
							$retorno = 0;
							$status .= "Erro - USUARIO: Usuแrio nใo ativo. ";
							$pontos = [];
						}
					}
				} else {
					$status .= "Erro - USUARIO ou PIN nao informado.";
					$pontos = [];
				}
			} else {
				$status .= "Erro - JSON vazio. ";
				$pontos = [];
			}
		} catch (Exception $e) {
			$status .= "Erro - EXCEวรO: ".$e->getMessage();
			$pontos = [];
		}

		$cnpj_empresa = ($id_empresa) ? getValue('cnpj', 'empresa', 'id='.$id_empresa) : '';
		$razao_empresa = ($id_empresa) ? getValue('nome', 'empresa', 'id='.$id_empresa) : '';
		$id_usuario = intval($id_usuario);
		$data_hora = date('Y-m-d H:i');
		$jsonRetorno = array(
			"data_hora" => $data_hora,
			"cnpj_empresa" => $cnpj_empresa,
			"razao_empresa" => $razao_empresa,
			"id_usuario" => $id_usuario,
			"nome_usuario" => $nome_usuario,
			"telefone" => $telefone,
			"endereco" => $endereco,
			"numero" => $numero,
			"complemento" => $complemento,
			"bairro" => $bairro,
			"cidade" => $cidade,
			"estado" => $estado,
			"cpf" => $cpf,
			"identidade" => $identidade,
			"ctps" => $ctps,
			"pis" => $pis,
			"pontos" => $pontos,
			"retorno" => $retorno,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno, JSON_UNESCAPED_UNICODE);
		echo $retorno;
	}
?>