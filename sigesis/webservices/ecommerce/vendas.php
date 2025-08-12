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
			$id_caixa = 0;
			$id_tabela = 0;
			$id_cadastro = 0;
			$valor_total = 0;
			$desconto = 0;
			$nome_cliente = "";
			$telefone_cliente = "";
			$data_venda = "";
			$id_vendedor = 0;
			$retorno = 0;
			$status = 0;
			$produtos = array();
			$pagamentos = array();
			$id_venda = 0;
			
			if ($json_string) {
				foreach($json_array as $nome_campo => $valor) {
					if($nome_campo == "id_cliente")
						$id_cadastro = $valor;	
					if($nome_campo == "nome_cliente")
						$nome_cliente = $valor;
					if($nome_campo == "telefone_cliente")
						$telefone_cliente = $valor;
					if($nome_campo == "id_usuario")
						$id_vendedor = $valor;
					if($nome_campo == "id_tabela")
						$id_tabela = $valor;
					if($nome_campo == "valor_total")
						$valor_total = $valor;
					if($nome_campo == "valor_desconto")
						$desconto = $valor;
					if($nome_campo == "data_venda")
						$data_venda = $valor;
					if($nome_campo == "produtos")
						$produtos = $valor;
					if($nome_campo == "pagamentos")
						$pagamentos = $valor;
				}
				$nomeusuario = "LojaOnLine";
				if(!$id_caixa){
					$id_caixa = $faturamento->verificaCaixa($id_vendedor);
					if(!$id_caixa) {
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
					if(!$id_cadastro and $nome_cliente) {
						  $data_cadastro = array(
							'nome' => html_entity_decode($nome_cliente, ENT_QUOTES, 'UTF-8'),
							'celular' => $telefone_cliente,
							'cliente' => '1',
							'usuario' => $nomeusuario,
							'data' => "NOW()"
						  );
						  $id_cadastro = $db->insert("cadastro", $data_cadastro);
					  }

					  $valor_pagar = $valor_total-$desconto;
					  $id_empresa = getValue('id_empresa', 'usuario', 'id='.$id_vendedor);
					  $data_v = array(
						'id_empresa' => $id_empresa,
						'id_cadastro' => $id_cadastro,
						'id_caixa' => $id_caixa,
						'id_vendedor' => $id_vendedor,
						'valor_total' => $valor_total,
						'valor_desconto' => $desconto,
						'valor_pago' => $valor_pagar,
						'data_venda' => $data_venda,
						'usuario_venda' => $nomeusuario,
						'pago' => 2,
						'usuario_pagamento' => $nomeusuario,
						'usuario' => $nomeusuario,
						'data' => "NOW()"
					  );
					  $id_venda = $db->insert("vendas", $data_v);
					  $retorno = "Sucesso. Venda adicionada.";
					  $status = 200;
			 
					  $nomecliente = ($id_cadastro) ? getValue("nome", "cadastro", "id=" . $id_cadastro) : "";	
					  $contar_produtos = count($produtos);
					  $contar_pagamentos = count($pagamentos);
					  
					  $porcentagem_desconto = ($desconto>0) ? ($desconto*100)/$valor_total : 0 ;
					  //$valor_desconto = $desconto/$contar_produtos;
					  
					  foreach ($produtos as $prow) {
						  $id_produto = $prow['id_produto'];
						  $valor_venda = $prow['valor_venda']; //valor por produto
						  $quantidade = $prow['quantidade'];
						  $valor_custo =  getValue("valor_custo","produto","id=".$id_produto);
			  
						  $quantidade = ($quantidade) ? $quantidade : 1;
						  $valor = $valor_venda; // valor por produto
						  
						  //Valor do desconto calculado em proporção por produto.
						  $valor_desconto = ($desconto>0) ? ($porcentagem_desconto*$valor_venda)/100 : 0;
						  
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
							'valor_desconto' => $desconto_produto,
							'valor_total' => $valor_venda,
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
							  $retorno_row = self::$db->fetch_all($sql);
							  if($retorno_row) {
								  foreach ($retorno_row as $exrow) {
									  $observacao = "VENDA DE KIT [$nomekit] PARA CLIENTE: ".$nomecliente;
									  
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
									  $totalestoque = $this->getEstoqueTotal($exrow->id_produto);
									  $data_update = array(
										'estoque' => $totalestoque, 
										'usuario' => $nomeusuario,
										'data' => "NOW()"
									  );
									  $db->update("produto", $data_update, "id=".$exrow->id_produto);
								  }
							  }
						  } else {			  
							  $observacao = "VENDA DE PRODUTO PARA CLIENTE: ".$nomecliente;
							  
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
					  $data_vencimento = "NOW()";
					  $valor_total_venda = $valor_total-$desconto;	  
					  foreach ($pagamentos as $parrow) {
						  $tipo = $parrow['id'];
						  $id_tipo_categoria = getValue("id_categoria","tipo_pagamento","id=" . $tipo);
						  $valor_pago = $valor_total_venda; //$parrow['valor'];
						  $total_parcelas = $parrow['parcelas'];
						  
						  $data = array(
							'id_empresa' => $id_empresa,
							'id_cadastro' => $id_cadastro, 
							'id_caixa' => $id_caixa,
							'id_venda' => $id_venda,							  
							'tipo' => $tipo, 
							'valor_total_venda' => $valor_total_venda,
							'total_parcelas' => $total_parcelas,
							'data_pagamento' => $data_venda,
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
							'data_pagamento' => $data_venda,
							'usuario' => $nomeusuario,
							'data' => "NOW()"
						  );

						  $row_cartoes = Core::getRowById("tipo_pagamento", $tipo);
						  $dias = $row_cartoes->dias;
						  $taxa = $row_cartoes->taxa;
						  $id_banco = $row_cartoes->id_banco;
						  
						  if($id_tipo_categoria == '1') {
							$data['pago'] = 1;
							$data['data_pagamento'] = $data_venda;
							$data['valor_pago'] = $valor_pago;
							$data['data_vencimento'] = $data_vencimento;
							 
							$id_pagamento = $db->insert("cadastro_financeiro", $data);
			 
						  } elseif($id_tipo_categoria == '2' or $id_tipo_categoria == '4') {	
							$data['pago'] = 1;
							$data['data_pagamento'] = $data_venda;	
							$data_receita['pago'] = 0;					
							$descricao = ($id_tipo_categoria == '2') ? "CHEQUE" : "BOLETO";
							$data['valor_pago'] = $valor_pago;
							$data['id_banco'] = $id_banco;
							$data['nome_cheque'] = sanitize(post('nome_cheque'));
							$data['banco_cheque'] = sanitize(post('banco_cheque'));
							$data['numero_cheque'] = sanitize(post('numero_cheque'));
							$data['data_vencimento'] = $data_vencimento;
								 
							$id_pagamento = $db->insert("cadastro_financeiro", $data);
							$data_parcela = explode('-', $data_venda);
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
							
							$data['pago'] = 1;
							$data['data_pagamento'] = $data_venda;	
							$data_receita['pago'] = 1;			
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
							
							$data['pago'] = 1;
							$data['data_pagamento'] = $data_venda;	
							$data_receita['pago'] = 1;	
							$data_parcela = explode('-', $data_venda);
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
					  }
			} else {
				$retorno = "Erro - JSON vazio.";
				$status = 400;
			}
		} catch (Exception $e) {
				$retorno = "Erro - EXCEÇÃO: ".$e->getMessage();
				$status = 401;
		}

		$jsonRetorno = array(
			"id_venda" => $id_venda,
			"retorno" => $retorno,
			"status" => $status,
			"json" => $json_string
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>