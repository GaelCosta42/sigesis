<?php

/**
 * Webservices: Vendas em Crediário
 *
 */

set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 6000);
ini_set('fastcgi_read_timeout', 6000);
ini_set('default_socket_timeout', 9000);
define('_VALID_PHP', true);
require('../../init.php');

if (!$core->app_vendas) {
	$json_erro = array(
		"status" => 400,
		"retorno" => "OPCAO NAO DISPONIVEL. LIGUE PARA (31) 3829-1950"
	);
	$retorno =  json_encode($json_erro);
	echo $retorno;
} else {
	try {
		$json_string = file_get_contents('php://input');
		$json_array = json_decode($json_string, true);
		$id_caixa = 0;
		$id_tabela = 0;
		$id_cadastro = 0;
		$valor_total = 0;
		$desconto = 0;
		$crediario = 0;
		$acrescimo = 0;
		$nome_cliente = "";
		$cpf_cnpj = "";
		$telefone_cliente = "";
		$data_venda = "";
		$id_vendedor = 0;
		$retorno = 0;
		$produtos = array();
		$pagamentos = array();
		$id_unico = 0;

		if ($json_string) {
			foreach ($json_array as $nome_campo => $valor) {
				if ($nome_campo == "id_cliente")
					$id_cadastro = $valor;
				if ($nome_campo == "nome_cliente")
					$nome_cliente = $valor;
				if ($nome_campo == "telefone_cliente")
					$telefone_cliente = $valor;
				if ($nome_campo == "id_usuario")
					$id_vendedor = $valor;
				if ($nome_campo == "id_tabela")
					$id_tabela = $valor;
				if ($nome_campo == "crediario")
					$crediario = $valor;
				if ($nome_campo == "valor_acrescimo")
					$acrescimo = $valor;
				if ($nome_campo == "valor_total")
					$valor_total = $valor;
				if ($nome_campo == "valor_desconto")
					$desconto = $valor;
				if ($nome_campo == "data_venda")
					$data_venda = $valor;
				if ($nome_campo == "produtos")
					$produtos = $valor;
				if ($nome_campo == "pagamentos")
					$pagamentos = $valor;
				if ($nome_campo == "cep")
					$cep = $valor;
				if ($nome_campo == "endereco")
					$endereco = $valor;
				if ($nome_campo == "numero")
					$numero = $valor;
				if ($nome_campo == "complemento")
					$complemento = $valor;
				if ($nome_campo == "bairro")
					$bairro = $valor;
				if ($nome_campo == "cidade")
					$cidade = $valor;
				if ($nome_campo == "estado")
					$estado = $valor;
				if ($nome_campo == "estado")
					$estado = $valor;
				if ($nome_campo == "id_unico")
					$id_unico = $valor;
			}

			$existeVenda = getValue("id", "vendas", "id_unico='" . $id_unico . "'");

			if (empty($id_unico) || strlen($id_unico)<31 || $existeVenda) {
				$status = lang('MSG_ERRO_VENDA_DUPLICADO');
				$retorno = 0;
				$id_venda=0;				
			} 
			else {

				if ($id_cadastro) {
					$nomeusuario = getValue('usuario', 'usuario', 'id=' . $id_vendedor);
					$credito = $cadastro->getTotalCrediario($id_cadastro);
					$id_empresa = getValue('id_empresa', 'usuario', 'id=' . $id_vendedor);
					$valor_pagar = $valor_total - $desconto + $acrescimo;
					$soma_dinheiro = 0;

					if (!$valor_pagar)
						$valor_pagar = 0;

					if (!$credito)
						$credito = 0;

					$contar_pagamentos = (is_array($pagamentos)) ? count($pagamentos) : 0;
					$pago = 0;

					for ($i = 0; $i < $contar_pagamentos; $i++) {
						$row_pagamento = Core::getRowById("tipo_pagamento", $pagamentos[$i]['id']);
						$pago += $valor_total;

						if ($row_pagamento->id_categoria == '1')
							$soma_dinheiro += $valor_total;
					}

					$valor_crediario = $valor_pagar;
					$saldo = $credito - $valor_crediario;

					if ($soma_dinheiro > 0) {
						$soma_restante = $pago - $soma_dinheiro;
						$total_pagar_dinheiro = $valor_pagar - $soma_restante;
						$troco = $soma_dinheiro - $total_pagar_dinheiro;
						$percentual_dinheiro = ($soma_dinheiro - $troco) / $soma_dinheiro;
						$pago = ($troco < 0) ? $pago : $pago - $troco;
					}

					if ($saldo >= 0) {
						if (!$id_caixa) {
							$id_caixa = $faturamento->verificaCaixa($id_vendedor);

							if (!$id_caixa) {
								$data = array(
									'id_abrir' => $id_vendedor,
									'data_abrir' => "NOW()",
									'status' => '1',
									'usuario' => $nomeusuario,
									'data' => "NOW()"
								);
								$id_caixa = $db->insert("caixa", $data);
							}
						}

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
						$db->update("cadastro", $data_endereco_cadastro, "id=" . $id_cadastro);

						$id_cadastro_endereco = getValue('id', 'cadastro_endereco', 'id_cadastro=' . $id_cadastro);
						$data_cadastro_endereco = array(
							'id_cadastro' => $id_cadastro,
							'cep' => $cep,
							'endereco' => sanitize($endereco),
							'numero' => sanitize($numero),
							'complemento' => sanitize($complemento),
							'bairro' => sanitize($bairro),
							'cidade' => sanitize($cidade),
							'estado' => sanitize($estado),
							'faturamento' => 1,
							'usuario' => $nomeusuario,
							'data' => "NOW()"
						);

						if ($id_cadastro_endereco) {
							$db->update("cadastro_endereco", $data_cadastro_endereco, "id=" . $id_cadastro_endereco);
						} else {
							$db->insert("cadastro_endereco", $data_cadastro_endereco);
						}

						$data_v = array(
							'id_unico' => $id_unico,
							'id_empresa' => $id_empresa,
							'id_cadastro' => $id_cadastro,
							'id_caixa' => $id_caixa,
							'id_vendedor' => $id_vendedor,
							'status_entrega' => 9,
							'valor_total' => $valor_total,
							'valor_despesa_acessoria' => $acrescimo,
							'valor_desconto' => $desconto,
							'valor_pago' => $valor_pagar,
							'data_venda' => $data_venda,
							'crediario' => 1,
							'usuario_venda' => $nomeusuario,
							'pago' => 1,
							'usuario_pagamento' => $nomeusuario,
							'usuario' => $nomeusuario,
							'data' => "NOW()"
						);
						$id_venda = $db->insert("vendas", $data_v);
						$status = "Sucesso. Venda adicionada no crediário.";

						$nomecliente = getValue("nome", "cadastro", "id=" . $id_cadastro);
						$contar_produtos = count($produtos);
						$contar_pagamentos = count($pagamentos);

						$porcentagem_desconto = ($desconto > 0) ? ($desconto * 100) / $valor_total : 0;
						//$valor_desconto = $desconto/$contar_produtos;

						$acrescimo_inserido = 0;

						foreach ($produtos as $prow) {
							$id_produto = $prow['id_produto'];
							$valor_venda = $prow['valor_venda']; //valor por produto
							$quantidade = $prow['quantidade'];
							$valor_custo =  getValue("valor_custo", "produto", "id=" . $id_produto);

							$quantidade = ($quantidade) ? $quantidade : 1;
							$valor = $valor_venda; // valor por produto

							//Valor do desconto calculado em proporção por produto.
							$valor_desconto = ($desconto > 0) ? ($porcentagem_desconto * $valor_venda) / 100 : 0;

							//Valor do produto subtraido pelo desconto por produto multiplicaod pela quantidade deste produto.
							$valor_venda = ($valor_venda - $valor_desconto) * $quantidade;

							$quant_estoque = $quantidade * (-1);

							//Desconto total do produto é o valor do desconto por produto multiplicado pela quantidade.
							$desconto_produto = $valor_desconto * $quantidade;

							$data_cadastro_venda = array(
								'id_empresa' => $id_empresa,
								'id_cadastro' => $id_cadastro,
								'id_caixa' => $id_caixa,
								'id_venda' => $id_venda,
								'id_produto' => $id_produto,
								'id_tabela' => $id_tabela,
								'valor_custo' => $valor_custo,
								'valor' => $valor,
								'quantidade' => $quantidade,
								'valor_despesa_acessoria' => (!$acrescimo_inserido) ? $acrescimo : 0,
								'valor_desconto' => $desconto_produto,
								'valor_total' => $valor_venda,
								'crediario' => 1,
								'pago' => 1,
								'usuario' => $nomeusuario,
								'data' => "NOW()"
							);
							$id_cadastro_venda = $db->insert("cadastro_vendas", $data_cadastro_venda);
							$acrescimo_inserido = 1;

							$kit = getValue("kit", "produto", "id=" . $id_produto);
							if ($kit) {
								$nomekit = getValue("nome", "produto", "id=" . $id_produto);
								$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque "
									. "\n FROM produto_kit as k"
									. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
									. "\n WHERE k.id_produto_kit = $id_produto "
									. "\n ORDER BY p.nome ";
								$retorno_row = $db->fetch_all($sql);

								if ($retorno_row) {
									foreach ($retorno_row as $exrow) {
										$observacao = str_replace("[ID_VENDA]", $id_venda, lang('VENDA_KIT'));
										$observacao = str_replace("[NOME_KIT]", $nomekit, $observacao);
										$observacao = str_replace("[NOME_CLIENTE]", $nomecliente, $observacao);

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
										$db->update("produto", $data_update, "id=" . $exrow->id_produto);
									}
								}
								$observacao = str_replace("[ID_VENDA]", $id_venda, lang('VENDA_PRODUTO_CLIENTE'));
								$observacao = str_replace("[NOME_CLIENTE]", $nomecliente, $observacao);
								$data_estoque = array(
									'id_empresa' => $id_empresa,
									'id_produto' => $id_produto,
									'quantidade' => $quant_estoque,
									'tipo' => 2,
									'motivo' => 3,
									'observacao' => $observacao,
									'id_ref' => $id_cadastro_venda,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->insert("produto_estoque", $data_estoque);
								$totalestoque = $cadastro->getEstoqueTotal($id_produto);
								$data_update = array(
									'estoque' => $totalestoque,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->update("produto", $data_update, "id=" . $id_produto);
							} else {
								$observacao = str_replace("[ID_VENDA]", $id_venda, lang('VENDA_PRODUTO_CLIENTE'));
								$observacao = str_replace("[NOME_CLIENTE]", $nomecliente, $observacao);

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
								$db->update("produto", $data_update, "id=" . $id_produto);
							}
						}

						///////////////////////////////////
						//Este trecho de código serve para ajustar o valor do desconto quando o mesmo for quebrado e gerar diferença no total.
						$novo_valor_desconto = $cadastro->obterDescontosVenda($id_venda, $id_caixa, $id_empresa, $id_cadastro);
						if (round($novo_valor_desconto->vlr_desc, 2) != $desconto) {
							$novo_desconto = ($desconto - round($novo_valor_desconto->vlr_desc, 2)) + $novo_valor_desconto->valor_desconto;
							$data_desconto = array('valor_desconto' => $novo_desconto);
							$db->update("cadastro_vendas", $data_desconto, "id=" . $novo_valor_desconto->id);
						}
						///////////////////////////////////

						$data_vencimento = "NOW()";
						$valor_total_venda = $valor_total - $desconto + $acrescimo;

						$data_financeiro = array(
							'id_empresa' => $id_empresa,
							'id_cadastro' => $id_cadastro,
							'id_venda' => $id_venda,
							'id_caixa' => $id_caixa,
							'valor_pago' => $valor_crediario,
							'valor_total_venda' => $valor_total_venda,
							'data_vencimento' => "NOW()",
							'data_pagamento' => "NOW()",
							'pago' => 1,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->insert("cadastro_financeiro", $data_financeiro);

						$valor_operacao = $valor_crediario;

						$data_crediario = array(
							'id_empresa' => session('idempresa'),
							'id_cadastro' => $id_cadastro,
							'id_venda' => $id_venda,
							'id_caixa' => $id_caixa,
							'valor_venda' => $valor_total_venda,
							'operacao' => '1',
							'valor' => $valor_operacao,
							'data_operacao' => "NOW()",
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->insert("cadastro_crediario", $data_crediario);
					} else {
						$id_venda = 0;
						$status = lang('MSG_ERRO_CREDIARIO_PAGAR') . moeda($credito);
						$retorno = 0;
					}
				} else {
					$id_venda = 0;
					$status = lang('MSG_ERRO_CREDIARIO_CLIENTE');
					$retorno = 0;
				}
			}
		} else {
			$status = "Erro - JSON vazio.";
			$retorno = 0;
		}
	} catch (Exception $e) {
		$status = "Erro - EXCEÇÃO: " . $e->getMessage();
		$retorno = 0;
	}

	$retorno = ($id_venda > 0) ? 1 : 0;
	$jsonRetorno = array(
		"id_venda" => $id_venda,
		"retorno" => $retorno,
		"status" => $status,
		"json" => $json_string
	);
	$retorno =  json_encode($jsonRetorno);
	echo $retorno;
}
