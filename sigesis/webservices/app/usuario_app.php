<?php
  /**
   * Webservices: Usuario App - Listagem do Usuario e seus pedidos para o Aplicativo
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);
	define('_VALID_PHP', true);
	require('../../init.php');
	cors();

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
			$code = 0;
			$status = "";
			$id_usuario = 0;
			$nome = "";
			$email = "";
			$telefone = "";

			if ($json_string)
			{
				foreach($json_array as $nome_campo => $valor)
				{
					if($nome_campo == "id_usuario")
						$id_usuario = $valor;
				}

				$sql_usuario = "SELECT * FROM cadastro WHERE id=$id_usuario";
				$row_usuario = $db->first($sql_usuario);
				//$row_usuario = Core::getRowById("cadastro", $id_usuario);
				if($row_usuario) {
					$nome = $row_usuario->nome;
					$email = $row_usuario->email;
					$telefone = ($row_usuario->celular) ? $row_usuario->celular : $row_usuario->celular2;
					$row_pedidos = $cadastro->getVendas($id_usuario);
					$array_pedido = array();
					foreach ($row_pedidos as $prow){

						$row_itens = $cadastro->getVendaProdutos($prow->id);
						$array_itens = array();
						if($row_itens){
							foreach ($row_itens as $irow){
								$imagem = ($irow->imagem) ? "/uploads/data/".$irow->imagem : "";
								$array_itens[] = array(
									"id" => $irow->id_produto,
									"nome" => $irow->produto,
									"foto" => $imagem,
									"preco" => $irow->valor,
									"quantidade" => $irow->quantidade
								);
							}
						}

						$array_endereco = array(
							"cep" => $prow->cep,
							"endereco" => $prow->endereco,
							"complemento" => $prow->complemento,
							"bairro" => $prow->bairro,
							"cidade" => $prow->cidade
						);

						$array_pedido[] = array(
							"id_pedido" => $prow->id,
							"data_hora" => $prow->data_venda,
							"valor_total" => $prow->valor_pago,
							"status_pedido" => $prow->inativo,
							"id_status" => $prow->status_entrega,
							"status" => $prow->inativo == 1 ? "CANCELADO" : $prow->status,
							"cor_status" => $prow->inativo == 1 ? "#FF6347" : $prow->cor,
							"itens" => $array_itens,
							"endereco" => $array_endereco
						);
					}
					$code = 200;
					$status = "Sucesso";
				} else {
					$array_pedido = array();
					$code = 404;
					$status = "Erro - Usuario nao encontrado. ";
				}
			}
			else
			{
				$status = "Erro - JSON vazio. ";
			}
		} catch (Exception $e) {
			$status = "Erro - EXCECAO: ".$e->getMessage();
			$array_produto = "";
		}

		$jsonRetorno = array(
			"nome" => $nome,
			"email" => $email,
			"telefone" => $telefone,
			"pedidos" => $array_pedido,
			"status" => $status,
			"code" => $code
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>