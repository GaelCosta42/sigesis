<?php
  /**
   * Webservices: Pedidos App - Registra os pedidos feitos pelo aplicativo.
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

			$id_cadastro = 0;
			$cep = "";
			$endereco = "";
			$bairro = "";
			$cidade = "";
			$complemento = "";
			$referencia = "";
			$estado = "";
			$numero = 0;
			$id_pagamento = 0;
			$troco = 0;
			$valor_total = 0;
			$observacao = "";
			$produtos = array();
			$code = 0;
			$status = "";
			$nomeusuario = "APP";
			$id_tabela = 1;
			$id_caixa = 0;
			$id_venda = 0;
			$taxa_bairro = 0;

			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "id_cliente")
						$id_cadastro = $valor;
					if($nome_campo == "cep")
						$cep = $valor;
					if($nome_campo == "endereco")
						$endereco = $valor;
					if($nome_campo == "bairro")
						$bairro = $valor;
					if($nome_campo == "cidade")
						$cidade = $valor;
					if($nome_campo == "uf")
						$estado = $valor;
					if($nome_campo == "complemento")
						$complemento = $valor;
					if($nome_campo == "referencia")
						$referencia = $valor;
					if($nome_campo == "numero")
						$numero = $valor;
					if($nome_campo == "pagamento")
						$id_pagamento = $valor;
					if($nome_campo == "valor_total")
						$valor_total = $valor;
					if($nome_campo == "troco")
						$troco = $valor;
					if($nome_campo == "observacao")
						$observacao = $valor;
					if($nome_campo == "produtos")
						$produtos = $valor;
					if($nome_campo == "taxa_bairro")
						$taxa_bairro = $valor;
				}

				$sql_empresa = "SELECT id FROM empresa WHERE inativo = 0";
				$row_empresa = $db->first($sql_empresa);
				$id_empresa = ($row_empresa) ? $row_empresa->id : 0;
				$valor_pago = $valor_total + $taxa_bairro ;
				$data_venda = array(
					'id_empresa' => intval($id_empresa),
					'id_caixa' => $id_caixa,
					'id_cadastro' => $id_cadastro,
					'valor_total' => $valor_total,
					'valor_despesa_acessoria' => $taxa_bairro,
					'valor_pago' => $valor_pago,
					'entrega' => 1,
					'status_entrega' => 1,
					'data_venda' => "NOW()",
					'prazo_entrega' => "NOW()",
					'cep' => $cep,
					'endereco' => sanitize($endereco).', '.$numero,
					'complemento' => sanitize($complemento),
					'bairro' => sanitize($bairro),
					'cidade' => sanitize($cidade),
					'estado' => sanitize($estado),
					'referencia' => sanitize($referencia),
					'observacao' => sanitize($observacao),
					'usuario_venda' => $nomeusuario,
					'pago' => 2,
					'usuario' => $nomeusuario,
					'data' => "NOW()"
				);
				$data_venda['observacao'] .= ($troco > 0) ? "Levar troco para ".moeda($troco) : "" ;
				$id_venda = $db->insert("vendas", $data_venda);
				$status = "Sucesso. Venda adicionada.";

				$id_cadastro_endereco = getValue("id", "cadastro_endereco", "id_cadastro=" . $id_cadastro);
				if (!$id_cadastro_endereco) {
					$data_endereco_cadastro = array(
						'cep' => $cep,
						'endereco' => sanitize($endereco),
						'numero' => sanitize($numero),
						'complemento' => sanitize($complemento),
						'bairro' => sanitize($bairro),
						'cidade' => sanitize($cidade),
						'estado' => sanitize($estado),
						'usuario' => $nomeusuario,
						'data' => "NOW()"
					);
					$db->update("cadastro", $data_endereco_cadastro, "id=".$id_cadastro);

					$data_cadastro_endereco = array(
						'id_cadastro' => $id_cadastro,
						'cep' => $cep,
						'endereco' => sanitize($endereco),
						'numero' => sanitize($numero),
						'complemento' => sanitize($complemento),
						'bairro' => sanitize($bairro),
						'cidade' => sanitize($cidade),
						'estado' => sanitize($estado),
						'referencia' => 'ENDERECO FATURAMENTO',
						'faturamento' => 1,
						'usuario' => $nomeusuario,
						'data' => "NOW()"
					);
					$id_endereco = $db->insert("cadastro_endereco", $data_cadastro_endereco);
				}
				$nomecliente = getValue("nome", "cadastro", "id=" . $id_cadastro);

				foreach ($produtos as $prow) {
					$id_produto = $prow['id_produto'];
					$valor_produto = $prow['valor'];
					$quantidade = $prow['quantidade'];
					$quantidade = ($quantidade) ? $quantidade : 1;
					$valor_total_produto = $valor_produto * $quantidade;
					$quant_estoque = $quantidade * (-1);
					$valor_custo =  getValue("valor_custo","produto","id=".$id_produto);

					$data_cadastro_venda = array(
						'id_empresa' => $id_empresa,
						'id_cadastro' => $id_cadastro,
						'id_caixa' => $id_caixa,
						'id_venda' => $id_venda,
						'id_produto' => $id_produto,
						'id_tabela' => $id_tabela,
						'valor_custo' => $valor_custo,
						'valor' => $valor_produto,
						'valor_despesa_acessoria' => round($taxa_bairro / count($produtos), 2),
						'quantidade' => $quantidade,
						'valor_desconto' => 0,
						'valor_total' => $valor_total_produto,
						'usuario' => $nomeusuario,
						'data' => "NOW()"
					);
					$id_cadastro_venda = $db->insert("cadastro_vendas", $data_cadastro_venda);

					$kit = getValue("kit", "produto", "id=" . $id_produto);
					if($kit) {
						$nomekit = getValue("nome", "produto", "id=" . $id_produto);
						$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque "
						. "\n FROM produto_kit as k"
						. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
						. "\n WHERE k.id_produto_kit = $id_produto "
						. "\n ORDER BY p.nome ";
						$retorno_row = $db->fetch_all($sql);
						if($retorno_row) {
							foreach ($retorno_row as $exrow) {
								$observacao = "VENDA DE KIT [$nomekit] PARA CLIENTE (via APP): ".$nomecliente;

								$data_estoque = array(
									'id_empresa' => $id_empresa,
									'id_produto' => $exrow->id_produto,
									'quantidade' => $quant_estoque,
									'tipo' => 2,
									'motivo' => 3,
									'observacao' => $observacao,
									'id_ref' => $id_cadastro_venda,
									'usuario' => $nomeusuario,
									'data' => "NOW()"
								);
								$db->insert("produto_estoque", $data_estoque);
								$totalestoque = $cadastro->getEstoqueTotal($exrow->id_produto);
								$data_update = array(
									'estoque' => $totalestoque,
									'usuario' => $nomeusuario,
									'data' => "NOW()"
								);
								$db->update("produto", $data_update, "id=".$exrow->id_produto);
							}
						}
					} else {
						$observacao = "VENDA DE PRODUTO PARA CLIENTE (via APP): ".$nomecliente;

						$data_estoque = array(
							'id_empresa' => $id_empresa,
							'id_produto' => $id_produto,
							'quantidade' => $quant_estoque,
							'tipo' => 2,
							'motivo' => 3,
							'observacao' => $observacao,
							'id_ref' => $id_cadastro_venda,
							'usuario' => $nomeusuario,
							'data' => "NOW()"
						);
						$db->insert("produto_estoque", $data_estoque);
						$totalestoque = $cadastro->getEstoqueTotal($id_produto);
						$data_update = array(
							'estoque' => $totalestoque,
							'usuario' => $nomeusuario,
							'data' => "NOW()"
						);
						$db->update("produto", $data_update, "id=".$id_produto);
					}
				}

				$novo_valor_acrescimo = $cadastro->obterAcrescimoVenda($id_venda, $id_caixa, $id_empresa, $id_cadastro);
				if ($novo_valor_acrescimo->vlr_acrescimo != $taxa_bairro) {
					$novo_acrescimo = ($taxa_bairro - $novo_valor_acrescimo->vlr_acrescimo) + $novo_valor_acrescimo->valor_despesa_acessoria;
					$data_acrescimo = array('valor_despesa_acessoria' => $novo_acrescimo);
					$db->update("cadastro_vendas", $data_acrescimo, "id=" . $novo_valor_acrescimo->id);
				}

				$data_vencimento = "NOW()";
				$valor_total_venda = $valor_total + $taxa_bairro;

				//foreach ($pagamentos as $parrow) {

				$tipo = $id_pagamento;
				$id_tipo_categoria = getValue("id_categoria","tipo_pagamento","id=" . $tipo);
				$valor_pago = $valor_total_venda;
				$total_parcelas = 1;

				$data = array(
					'id_empresa' => $id_empresa,
					'id_cadastro' => $id_cadastro,
					'id_caixa' => $id_caixa,
					'id_venda' => $id_venda,
					'tipo' => $tipo,
					'valor_total_venda' => $valor_total_venda,
					'total_parcelas' => $total_parcelas,
					'data_pagamento' => "NOW()",
					'usuario' => $nomeusuario,
					'data' => "NOW()"
				);

				$data_receita = array(
					'id_empresa' => $id_empresa,
					'id_cadastro' => $id_cadastro,
					'id_caixa' => $id_caixa,
					'id_venda' => $id_venda,
					'id_conta' => 19,
					'tipo' => $tipo,
					'data_pagamento' => "NOW()",
					'usuario' => $nomeusuario,
					'data' => "NOW()"
				);

				$row_cartoes = Core::getRowById("tipo_pagamento", $tipo);
				$dias = $row_cartoes->dias;
				$taxa = $row_cartoes->taxa;
				$id_banco = $row_cartoes->id_banco;

				if($id_tipo_categoria == '1') {
					$data['pago'] = 2;
					$data['valor_pago'] = $valor_pago;
					$data['data_vencimento'] = $data_vencimento;

					$id_pagamento = $db->insert("cadastro_financeiro", $data);

				} elseif($id_tipo_categoria == '2' or $id_tipo_categoria == '4') {
					$data['pago'] = 2;
					$data_receita['pago'] = 2;
					$descricao = ($id_tipo_categoria == '2') ? "CHEQUE" : "BOLETO";
					$data['valor_pago'] = $valor_pago;
					$data['id_banco'] = $id_banco;
					$data['data_vencimento'] = $data_vencimento;

					$id_pagamento = $db->insert("cadastro_financeiro", $data);

					if ($id_tipo_categoria==4){
						$data_temp = somarData(date('d/m/Y'), 3, 0, 0);
					}
					else {
						$data_temp = date('d/m/Y');
					}

					$data_parcela = explode('/', $data_temp);
					$valor_cheque = round($valor_pago/$total_parcelas, 2);
					$diferenca = $valor_pago - ($valor_cheque*$total_parcelas);

					for($i=0;$i<$total_parcelas;$i++)
					{
						$newData = novadata($data_parcela[1] + $i, $data_parcela[2], $data_parcela[0]);
						$parc = ($i+1);
						$p = $parc."/".$total_parcelas;
						$data_receita['id_banco'] = $id_banco;
						$data_receita['descricao'] = "$descricao - $p - ".$nomecliente;
						$data_receita['valor'] = $valor_cheque;
						$data_receita['valor_pago'] = $valor_cheque;
						$data_receita['id_pagamento'] = $id_pagamento;
						$data_receita['data_pagamento'] = $newData;
						$data_receita['parcela'] = $parc;
						if($i == 0) {
							$data_receita['valor'] = $valor_cheque + $diferenca;
							$data_receita['valor_pago'] = $valor_cheque + $diferenca;
						}
						$db->insert("receita", $data_receita);
					}
				} elseif($id_tipo_categoria == '3' or $id_tipo_categoria == '6') {
					$data['pago'] = 2;
					$data_receita['pago'] = 2;
					$data['id_banco'] = $id_banco;
					$data['valor_pago'] = $valor_pago;
					$data['data_vencimento'] = $data_vencimento;

					$id_pagamento = $db->insert("cadastro_financeiro", $data);

					$data_receita['id_banco'] = $id_banco;
					$data_receita['descricao'] = "BANCO - ".$nomecliente;
					$data_receita['valor'] = $valor_pago;
					$data_receita['valor_pago'] = $valor_pago;
					$data_receita['id_pagamento'] = $id_pagamento;
					$data_receita['data_recebido'] = $data_vencimento;
					$data_receita['parcela'] = 1;
					$db->insert("receita", $data_receita);
				} else {
					$data['pago'] = 2;
					$data_receita['pago'] = 2;
					$data_temp = date('d/m/Y');
					$data_parcela = explode('/', $data_temp);
					$valor_taxa = $valor_pago*$taxa/100;
					$valor_cartao = $valor_pago - $valor_taxa;
					$valor_parcelas_pago = round($valor_pago/$total_parcelas, 2);
					$valor_parcelas_cartao = $valor_cartao/$total_parcelas;
					$diferenca = $valor_pago - ($valor_parcelas_pago * $total_parcelas);
					$diferenca_parcela = $valor_cartao - ($valor_parcelas_cartao * $total_parcelas);
					$data['id_banco'] = $id_banco;
					$data['valor_pago'] = $valor_pago;
					$data['valor_total_cartao'] = $valor_cartao;
					$data['valor_parcelas_cartao'] = $valor_parcelas_cartao;
					$data['parcelas_cartao'] = $total_parcelas;
					$data['data_vencimento'] = $data_vencimento;
					$id_pagamento = $db->insert("cadastro_financeiro", $data);
					for($i=1;$i<$total_parcelas+1;$i++)
					{
						if($dias == 30) {
							$m = $i - 1;
							$newData = novadata($data_parcela[1] + $m, $data_parcela[2], $data_parcela[0]);
						} else {
							$newData = novadata($data_parcela[1], $data_parcela[2] + ($i*$dias), $data_parcela[0]);
						}
						$p = $i."/".$total_parcelas;
						$data_receita['id_banco'] = $id_banco;
						$data_receita['descricao'] = $row_cartoes->tipo." - $p - ".$nomecliente;
						$data_receita['valor'] = $valor_parcelas_pago;
						$data_receita['valor_pago'] = $valor_parcelas_cartao;
						$data_receita['data_recebido'] = $newData;
						$data_receita['parcela'] = $i;
						if($i == 1) {
							$data_receita['valor'] = $valor_parcelas_pago + $diferenca_parcela;
							$data_receita['valor_pago'] = $valor_parcelas_cartao + $diferenca_parcela;
						}
						$data_receita['id_pagamento'] = $id_pagamento;
						$db->insert("receita", $data_receita);
					}
				}
			} else {
				$status = "Erro - JSON vazio.";
				$code = 0;
			}
		} catch (Exception $e) {
			$status = "Erro - EXCECAO: ".$e->getMessage();
			$code = 0;
		}

		$code = ($id_venda > 0) ? 200 : 0;
		$jsonRetorno = array(
			"id" => $id_venda,
			"code" => $code,
			"status" => $status,
			"json" => $json_string,
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>