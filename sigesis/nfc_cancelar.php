<?php
	define('_VALID_PHP', true);
	header('Content-Type: text/html; charset=utf-8');	
	
	require('enotas/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));
	
	$id_venda = intval(get('id_venda'));
	$row_vendas = Core::getRowById("vendas", $id_venda);

	$id_caixa = intval(get('id_caixa'));
	$historico = intval(get('historico'));

	$total_dinheiro = get('total_dinheiro');
		
	$dentroPrazoCancelamento = !(strtotime($row_vendas->data_emissao.' +30 minutes') < strtotime(date('Y-m-d H:i:s')));;
	if (!$dentroPrazoCancelamento){
		Filter::$msgs['prazo_cancelamento'] = lang('MSG_ERRO_CANCELAR_NFCE');
	}

	if ($id_caixa == 0 and $total_dinheiro > 0) {
		if (empty($GET['id_banco']))	
            Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');		  
	}


	if (empty(Filter::$msgs)) {
			
			$NFCe_cancelado = false;
			$row_empresa = Core::getRowById("empresa", $row_vendas->id_empresa);
			$id_enotas = $row_empresa->enotas;
			
			if ($core->emissor_producao) {
				$ambiente = 'Producao'; //'Producao' ou 'Homologacao'
				$id_externo = ($row_empresa->versao_emissao==0) ? 'nfc-'.$id_venda : 'nfc'.$row_empresa->versao_emissao.'-'.$id_venda;
			} else {	
				echo "</br>--- --- --- --- AMBIENTE DE HOMOLOGAÇÃO --- --- --- ---</br>";
				$ambiente = 'Homologacao'; //'Producao' ou 'Homologacao'
				$id_externo = ($row_empresa->versao_emissao==0) ? 'Hnfc-'.$id_venda : 'Hnfc'.$row_empresa->versao_emissao.'-'.$id_venda;
			}
			
			try {
				$cupom_cancelado = eNotasGW::$NFeConsumidorApi->cancelar($id_enotas, $id_externo);

				if ($cupom_cancelado === null) {
					$NFCe_cancelado = $cadastro->atualizarCupom($id_venda, $id_enotas, $id_externo);
				}
				else{
					echo lang('CADASTRO_APAGAR_VENDA_FISCAL_NOK');
				}	
			} catch (Exception $e) {
				// NF0001 - Código de cupom já status cancelado
				if ($e->errors[0]->codigo == 'NF0001') {
					echo lang('CADASTRO_APAGAR_VENDA_FISCAL_OK');
					$NFCe_cancelado = $cadastro->atualizarCupom($id_venda, $id_enotas, $id_externo);
				} else
					echo $e->getMessage();
			}

			if ($NFCe_cancelado) {
				$sql = "SELECT v.* " 
					. "\n FROM cadastro_vendas as v"
					. "\n WHERE v.id_venda = $id_venda"
					. "\n ORDER BY v.id DESC";
				$retorno_row = $db->fetch_all($sql);
				$caixa_venda = 0;
				foreach ($retorno_row as $prow) {
					$caixa_venda = $prow->id_caixa;		  
					$id_produto = $prow->id_produto;
					$quantidade = $prow->quantidade;
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
								$observacao = "CANCELAMENTO DE VENDA DE KIT [$nomekit] ";	
								$quantidade_kit = $quantidade*$exrow->quantidade;
								$data_estoque = array(
									'id_empresa' => session('idempresa'),
									'id_produto' => $exrow->id_produto, 
									'quantidade' => $quantidade_kit,
									'tipo' => 1, 
									'motivo' => 7,
									'observacao' => $observacao, 
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								$db->insert("produto_estoque", $data_estoque);	
								$totalestoque = $cadastro->getEstoqueTotal($exrow->id_produto);
								$data_update = array(
									'estoque' => $totalestoque, 
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								$db->update("produto", $data_update, "id=".$exrow->id_produto);
						}
					}
				} else {
					$observacao = "CANCELAMENTO DE VENDA DE PRODUTO";			
					$data_estoque = array(
						'id_empresa' => session('idempresa'),
						'id_produto' => $id_produto, 
						'quantidade' => $quantidade,
						'tipo' => 1, 
						'motivo' => 7,
						'observacao' => $observacao, 
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$db->insert("produto_estoque", $data_estoque);	
					$totalestoque = $cadastro->getEstoqueTotal($id_produto);
					$data_update = array(
						'estoque' => $totalestoque, 
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$db->update("produto", $data_update, "id=".$id_produto);
				}
				}
				$wheretipo = " AND tipo <> 1 ";
				if($caixa_venda == $id_caixa) {
					$wheretipo = " ";
				} else {
					if($total_dinheiro > 0){
						$descricao = "CANCELAMENTO DA VENDA [".$id_venda."]";
						$data_despesa = array(
							'id_empresa' => session('idempresa'),
							'id_conta' => '23',		
							'descricao' => $descricao,				
							'valor' => $total_dinheiro,				
							'data_vencimento' => "NOW()",
							'data_pagamento' => "NOW()",
							'pago' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						if($id_caixa) {
							$data_despesa['id_caixa'] = $id_caixa;
												
						} else {
							$data_despesa['id_banco'] = post('id_banco');				
						}
						$id_despesa = $db->insert("despesa", $data_despesa);
						$data_update = array(
							'agrupar' => $id_despesa
						);
						$db->update("despesa", $data_update, "id=".$id_despesa);
					}	
				}	
				$data = array(
					'inativo' => '1'
				);
				
				$vendaCrediario = getValue("crediario","vendas","id=".$id_venda);
				if ($vendaCrediario) {
					$data_crediario = array(
							'inativo' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"				
					);
					$sql_crediario = "SELECT * FROM cadastro_crediario WHERE id_venda = $id_venda";
					$row_crediario = $db->fetch_all($sql_crediario);
					if ($row_crediario) {
						foreach($row_crediario as $crow) {
							if ($crow->pago) {
								$valor_pagamento_total = getValue("valor","cadastro_crediario","id=".$crow->id_pagamento);
								$novo_valor_pagamento = $valor_pagamento_total - abs($crow->valor);
								$data_pago = array(
									'valor' => $novo_valor_pagamento,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								$db->update("cadastro_crediario", $data_pago, "id=" . $crow->id_pagamento);	
								
								$data_receita = array(
									'valor' => $novo_valor_pagamento,
									'valor_pago' => $novo_valor_pagamento,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								$db->update("receita", $data_receita, "id_pagamento=$crow->id_pagamento AND id_cadastro=$crow->id_cadastro AND descricao like 'PAGAMENTO CREDIARIO%'");	
							}
						}
						$db->update("cadastro_crediario", $data_crediario, "id_venda =" . $id_venda);	
					}
				}
				
				$db->update("receita", $data, "id_venda =" . $id_venda);	
				$db->update("cadastro_financeiro", $data, "id_venda =" . $id_venda.$wheretipo);
				$db->update("cadastro_vendas", $data, "id_venda =" . $id_venda);			
				$db->update("vendas", $data, "id =" . $id_venda);		
				
				if ($db->affected()){
					echo lang('CADASTRO_APAGAR_VENDA_FISCAL_OK');
				} else {
					echo lang('NAOPROCESSADO');
				}
			}
		
		} else
              print Filter::msgStatus();

?>