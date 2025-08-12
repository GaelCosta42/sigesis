<?php
	define('_VALID_PHP', true);
	header('Content-Type: text/html; charset=utf-8');	
	
	require('enotas/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));	
	
	$id = get('id');
	$row_notafiscal = Core::getRowById('nota_fiscal', $id);
	$row_empresa = Core::getRowById('empresa', $row_notafiscal->id_empresa);
	$id_enotas = $row_empresa->enotas;

	if ($core->emissor_producao) {
		$idExterno = ($row_empresa->versao_emissao==0) ? 'nfe-'.$id : 'nfe'.$row_empresa->versao_emissao.'-'.$id;
		$ambiente = 'Producao'; //'Producao' ou 'Homologacao'
	} else {	
		$idExterno = ($row_empresa->versao_emissao==0) ? 'Hnfe-'.$id : 'Hnfe'.$row_empresa->versao_emissao.'-'.$id;
		$ambiente = 'Homologacao'; //'Producao' ou 'Homologacao'
	}	
	
	try
	{
		$retorno = eNotasGW::$NFeProdutoApi->cancelar($id_enotas, $idExterno);	

		$data = array(
			'inativo' => '1'
		);

		$data_venda = array(
			'id_nota_fiscal' => '0'
		);

		if (!$retorno) {
			$db->update("despesa", $data, "id_nota=" . $id);
			$db->update("receita", $data, "id_nota=" . $id);
			$db->update("nota_fiscal", $data, "id=" . $id);
			$db->update("vendas", $data_venda, "id_nota_fiscal=" . $id);
			$observacao = str_replace("[ID_NFE]",$id,lang('CANCELAMENTO_NFE_PRODUTO'));
			$sql_produto = "SELECT id_produto, quantidade, inativo FROM nota_fiscal_itens WHERE id_nota=".$id." AND inativo = 0";
			$produto_row = $db->fetch_all($sql_produto);
			if ($produto_row) {
				foreach($produto_row as $prow) {
					$id_venda = getValue("id_venda", "nota_fiscal", "id=".$id);
                	if (!$id_venda || $id_venda==0) {
						if ($prow->inativo==0) {
							$qtde_compra = getValue("quantidade_compra","produto_fornecedor","id_produto=".$prow->id_produto." AND id_nota=".$id);
							$qtde_compra = ($qtde_compra) ? $qtde_compra : 1;
							$quantidade = floatval($qtde_compra) * floatval($prow->quantidade);
							$kit = getValue("kit", "produto", "id=" .$prow->id_produto);
							if($kit) {
								$nomekit = getValue("nome", "produto", "id=" .$prow->id_produto);
								$sql_kit = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade "
										. "\n FROM produto_kit as k"
										. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
										. "\n WHERE k.id_produto_kit = $prow->id_produto AND k.materia_prima=0"
										. "\n ORDER BY p.nome ";
								$retorno_krow = $db->fetch_all($sql_kit);
								if($retorno_krow) {
									foreach ($retorno_krow as $exrow) {
										$observacao_kit = str_replace("[ID_NFE]",$id,lang('CANCELAMENTO_NOTA_KIT'));
										$observacao_kit = str_replace("[NOME_KIT]",$nomekit,$observacao_kit);
										$quantidade_kit = $quantidade * $exrow->quantidade;
										$data_estoque = array(
											'id_empresa' => session('idempresa'),
											'id_produto' => $exrow->id_produto,
											'quantidade' => $quantidade_kit,
											'tipo' => 1,
											'motivo' => 7,
											'observacao' => $observacao_kit,
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
							}
							$data_estoque = array(
								'id_empresa' => session('idempresa'),
								'id_produto' => $prow->id_produto,
								'quantidade' => $quantidade,
								'tipo' => 1,
								'motivo' => 7,
								'observacao' => $observacao,
								'id_ref' => $id,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							$db->insert("produto_estoque", $data_estoque);
							$totalestoque = $cadastro->getEstoqueTotal($prow->id_produto);
							$data_update = array(
								'estoque' => $totalestoque,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							$db->update("produto", $data_update, "id=".$prow->id_produto);
						}
					}
				}
			}
			$db->update("nota_fiscal_itens", $data, "id_nota=" . $id);
			echo "</br>NOTA FISCAL CANCELADA COM SUCESSO.";
		} else {
			echo "</br>NÃO FOI POSSIVEL CANCELAR A NOTA FISCAL.";
		}
	}
	catch(Exceptions\invalidApiKeyException $ex) {
		echo 'Erro de autenticação: </br></br>';
		echo $ex->getMessage();
	}
	catch(Exceptions\unauthorizedException $ex) {
		echo 'Acesso negado: </br></br>';
		echo $ex->getMessage();
	}
	catch(Exceptions\apiException $ex) {
		echo 'Erro de validação: </br></br>';
		echo $ex->getMessage();
	}
	catch(Exceptions\requestException $ex) {
		echo 'Erro na requisição web: </br></br>';
		
		echo 'Requested url: ' . $ex->requestedUrl;
		echo '</br>';
		echo 'Response Code: ' . $ex->getCode();
		echo '</br>';
		echo 'Message: ' . $ex->getMessage();
		echo '</br>';
		echo 'Response Body: ' . $ex->responseBody;
	}
?>