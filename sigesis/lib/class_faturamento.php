<?php
/**
 * Classe Faturamento
 *
 */
if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe não é permitido.');

class Faturamento
{
	private static $db;

	/**
	 * Faturamento::__construct()
	 *
	 * @return
	 */
	function __construct()
	{
		self::$db = Registry::get("Database");
	}

	/**
	 * Faturamento::processarBanco()
	 *
	 * @return
	 */
	public function processarBanco()
	{
		if (empty($_POST['banco']))
			Filter::$msgs['banco'] = lang('MSG_ERRO_NOME');

		if (empty($_POST['id_empresa']))
			Filter::$msgs['id_empresa'] = lang('MSG_ERRO_EMPRESA');

		if (empty(Filter::$msgs)) {
			$data = array(
				'id_empresa' => sanitize(post('id_empresa')),
				'banco' => sanitize(post('banco')),
				'codigo' => sanitize(post('codigo')),
				'agencia' => sanitize(post('agencia')),
				'conta' => sanitize(post('conta')),
				'operacao' => sanitize(post('operacao')),
				'taxa_boleto' => converteMoeda(post('taxa_boleto')),
				'contabil' => sanitize(post('contabil')),
				'saldo' => converteMoeda(post('saldo'))
			);

			(Filter::$id) ? self::$db->update("banco", $data, "id=" . Filter::$id) : self::$db->insert("banco", $data);

			$message = (Filter::$id) ? lang('BANCO_AlTERADO_OK') : lang('BANCO_ADICIONADO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=banco&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::getBancoPrincipal()
	 *
	 * @return
	 */
	public function getBancoPrincipal($id_empresa)
	{
		$sql = "SELECT b.id "
			. "\n FROM banco AS b"
			. "\n WHERE b.inativo = 0 AND b.taxa_boleto > 0 AND b.id_empresa = $id_empresa";
		$row = self::$db->first($sql);

		return ($row) ? $row->id : 0;
	}

	/**
	 * Faturamento::getBancosRemessa()
	 *
	 * @return
	 */
	public function getBancosRemessa()
	{
		$sql = "SELECT r.id_banco, b.banco "
			. "\n FROM receita AS r"
			. "\n LEFT JOIN banco AS b ON b.id = r.id_banco "
			. "\n WHERE r.inativo = 0 AND r.remessa = 9"
			. "\n GROUP BY r.id_banco"
			. "\n ORDER BY b.banco";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getBancos()
	 *
	 * @return
	 */
	public function getBancos()
	{
		$sql = "SELECT b.id, b.id_empresa, b.banco, b.codigo, b.agencia, b.conta, b.operacao, b.saldo,  b.taxa_boleto, e.nome "
			. "\n FROM banco AS b"
			. "\n LEFT JOIN empresa AS e ON e.id = b.id_empresa "
			. "\n WHERE b.inativo = 0 "
			. "\n ORDER BY b.banco ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getCategoriaTipoPagamento()
	 *
	 * @return
	 */
	public function getCategoriaTipoPagamento()
	{
		$sql = "SELECT * FROM tipo_pagamento_categoria WHERE inativo = 0";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::processarTipoPagamento()
	 *
	 * @return
	 */
	public function processarTipoPagamento()
	{
		if (empty($_POST['tipo']))
			Filter::$msgs['tipo'] = lang('MSG_ERRO_TIPO_PAGAMENTO');

		if (empty($_POST['id_categoria_pagamento']))
			Filter::$msgs['id_categoria_pagamento'] = lang('MSG_ERRO_CATEGORIA_PAGAMENTO');

		if (empty($_POST['parcelas']) or $_POST['parcelas'] == 0)
			Filter::$msgs['parcelas'] = lang('MSG_ERRO_NUM_PARCELAS');

		if (empty(Filter::$msgs)) {
			$data = array(
				'tipo' => sanitize(post('tipo')),
				'id_categoria' => intval(sanitize(post('id_categoria_pagamento'))),
				'taxa' => converteMoeda(post('taxa')),
				'dias' => sanitize(post('dias')),
				'parcelas' => sanitize(post('parcelas')),
				'id_banco' => intval(sanitize(post('id_banco'))),
				'exibir_nfe' => (post('exibir_nfe')) ? sanitize(post('exibir_nfe')) : 0,
				'exibir_crediario' => (post('exibir_crediario')) ? sanitize(post('exibir_crediario')) : 0,
				'avista' => (post('avista')) ? sanitize(post('avista')) : 0,
				'primeiro_vencimento' => (post('primeiro_vencimento')) ? sanitize(post('primeiro_vencimento')) : 0,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			(Filter::$id) ? self::$db->update("tipo_pagamento", $data, "id=" . Filter::$id) : self::$db->insert("tipo_pagamento", $data);

			$message = (Filter::$id) ? lang('FINANCEIRO_PAGAMENTOS_ALTERADO_OK') : lang('FINANCEIRO_PAGAMENTOS_ADICIONADO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=tipopagamento&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::getTipoPagamentoValido()
	 * Está função foi criada para retornar os tipos de pagamento na Venda Rápida, onde seleciona somente os tipos de pagamentos Válidos, pois quando o TipoPagamento tem quantidade
	 * de parcelas igual a Zero ele gera um erro no pagamento.
	 * @return
	 */
	public function getTipoPagamentoValido()
	{
		$sql = "	SELECT 	t.id,
		  					t.tipo,
							t.taxa,
							t.dias,
							t.parcelas,
							t.avista,
							t.id_categoria,
							t.id_banco,
							b.banco

		 			FROM 		tipo_pagamento 	AS t
		 			LEFT JOIN 	banco 			AS b ON b.id = t.id_banco

		 			WHERE 		t.inativo = 0 and t.parcelas > 0

		 			ORDER BY 	t.tipo ASC ";

		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getTipoPagamento()
	 *
	 * @return
	 */
	public function getTipoPagamento()
	{
		$sql = "SELECT 	t.id,
		  				t.tipo,
						t.taxa,
						t.dias,
						t.parcelas,
						t.id_categoria,
						t.exibir_nfe,
						t.exibir_crediario,
						t.avista,
						t.primeiro_vencimento,
						b.banco,
						pc.categoria,
						b.id AS id_banco,
						t.desconto

		  		FROM 		tipo_pagamento 				AS t
		  		LEFT JOIN 	banco 						AS b 	ON b.id = t.id_banco
		  		LEFT JOIN 	tipo_pagamento_categoria 	AS pc 	ON pc.id = t.id_categoria

		  		WHERE t.inativo = 0";

		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getPagamentoCartoes()
	 *
	 * @return
	 */
	public function getPagamentoCartoes()
	{
		$sql = "SELECT id, tipo, taxa, dias"
			. "\n FROM tipo_pagamento"
			. "\n WHERE id_categoria IN (7,8) AND inativo = 0 "
			. "\n ORDER BY tipo ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getVendasEmissao($dataini,$datafim,$categorias_selecionadas,$cliente)
	 * 
	 * @return
	 */
	public function getVendasEmissao($dataini,$datafim,$categorias_selecionadas,$cliente)
	{
		$categorias = implode(',',$categorias_selecionadas);
		$wCategoria = ($categorias) ? " AND tp.id_categoria IN ($categorias) " : "";
		$wCliente = ($cliente) ? " AND ve.id_cadastro=$cliente " : "";
		$sql_vendas = "SELECT count(ve.id) AS quantidade, SUM(ve.valor_total) AS valor_total, "
				  ."\n SUM(ve.valor_despesa_acessoria) AS valor_despesa_acessoria, SUM(ve.valor_desconto) AS valor_desconto, "
				  ."\n ((SUM(ve.valor_total)+SUM(ve.valor_despesa_acessoria))-SUM(ve.valor_desconto)) AS valor_vendas "
				  ."\n FROM vendas AS ve "
				  ."\n LEFT JOIN cadastro_financeiro AS cf ON cf.id_venda=ve.id "
			      ."\n LEFT JOIN tipo_pagamento AS tp ON tp.id=cf.tipo "
				  ."\n WHERE ve.venda_agrupamento=0 AND ve.fiscal=0 AND ve.id_nota_fiscal=0 AND ve.orcamento=0 $wCategoria $wCliente "
				  ."\n AND ve.data_venda BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')";
		$row = self::$db->first($sql_vendas);
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getProdutosVendas($ids_vendas)
	 *
	 * @return
	 */
	public function getProdutosVendas($ids_vendas)
	{
		$sql = "SELECT id_produto,id_empresa,SUM(quantidade) AS quantidade, AVG(valor_custo) AS valor_custo, AVG(valor_original) AS valor_original, "
		   ."\n AVG(valor) AS valor, SUM(valor_despesa_acessoria) AS valor_despesa_acessoria, SUM(valor_desconto) AS valor_desconto, "
		   ."\n SUM(valor_total) AS valor_total "
		   ."\n FROM cadastro_vendas WHERE inativo=0 AND id_venda IN ($ids_vendas) GROUP BY id_produto ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getFinanceiroVenda()
	 *
	 * @return
	 */
	public function getTodosFinanceirosVendas($ids_vendas)
	{
		$sql = "SELECT t.id, f.id_empresa, t.tipo as pagamento, t.id_categoria as categoria, SUM(f.valor_pago) AS valor_pago, SUM(f.total_parcelas) AS total_parcelas"
			. "\n FROM cadastro_financeiro as f"
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo"
			. "\n WHERE f.inativo = 0 AND f.id_venda IN ($ids_vendas)"
			. "\n GROUP BY t.id"
			. "\n ORDER BY t.id DESC";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::converterVendasEmissao($quantidade,$valor,$dataini,$datafim,$categorias_selecionadas,$id_unico,$cliente)
	 * 
	 * @return
	 */
	public function converterVendasEmissao($quantidade,$valor,$dataini,$datafim,$categorias_selecionadas,$id_unico,$cliente)
	{
		$valor_original = converteMoeda($valor);
		$categorias = $categorias_selecionadas;

		$wCategoria = ($categorias) ? " AND tp.id_categoria IN ($categorias) " : "";
		$wCliente = ($cliente) ? " AND ve.id_cadastro=$cliente" : "";
		$sql_vendas = "SELECT ve.id, ve.id_empresa, ve.voucher_crediario, ve.valor_troca, ve.valor_total, ve.valor_despesa_acessoria, "
				  ."\n ve.valor_desconto, ve.valor_pago, ve.troco, "
				  ."\n ((ve.valor_total+ve.valor_despesa_acessoria)-ve.valor_desconto) AS valor_venda "
				  ."\n FROM vendas AS ve "
				  ."\n LEFT JOIN cadastro_financeiro AS cf ON cf.id_venda=ve.id "
			      ."\n LEFT JOIN tipo_pagamento AS tp ON tp.id=cf.tipo "
				  ."\n WHERE ve.venda_agrupamento=0 AND ve.fiscal=0 AND ve.id_nota_fiscal=0 AND ve.orcamento=0 $wCategoria $wCliente "
				  ."\n AND ve.data_venda BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')";
		$row = self::$db->fetch_all($sql_vendas);

		$existeVenda = getValue("id", "vendas", "id_unico='" . $id_unico . "'");
		if (empty($id_unico) || strlen($id_unico)<31) {
			Filter::$msgs['ja_existe_venda'] = lang('MSG_ERRO_VENDA_DUPLICADO');
		}

		$quantidade_vendas = 0;
		$valor_vendas = 0;
		$valor_descontos = 0;
		$valor_despesas = 0;
		$valor_total = 0;
		$ids_vendas = '';

		if ($row) {

			$quantidade_vendas = count($row);
			if ($quantidade_vendas!=$quantidade) {
				Filter::$msgs['valores_incorretos'] = lang('MSG_ERRO_VALORES_INCORRETOS');
			} 

			if (empty(Filter::$msgs)) {

				foreach($row as $venda) {

					if (($valor_vendas+$venda->valor_venda) < 10000) {
						$valor_vendas += $venda->valor_venda;
						$valor_descontos += $venda->valor_desconto;
						$valor_despesas += $venda->valor_despesa_acessoria;
						$valor_total += $venda->valor_total;
						$ids_vendas .= ($ids_vendas) ? ','.$venda->id : $venda->id;
						if ($venda != end($row))
							continue;
					} 

					if (!$existeVenda || $existeVenda == '' || empty($existeVenda)) {
						$data_venda = array(
							'id_unico' => $id_unico,
							'id_empresa' => session('idempresa'),
							'valor_total' => $valor_total,
							'valor_pago' => $valor_vendas,
							'valor_despesa_acessoria' => $valor_despesas,
							'valor_desconto' => $valor_descontos,
							'data_venda' => "NOW()",
							'pago' => 3, //pago igual a 3 para indicar uma venda de agrupamento para emissao fiscal
							'venda_agrupamento' => 1, //recebe 1 se for uma venda de agrupamento
							'observacao' => lang('FISCAL_CONTROLE_OBSERVACAO'),
							'usuario_venda' => session('nomeusuario'),
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						if ($cliente>0) {
							$data_venda['id_cadastro'] = $cliente;
						}
						$id_venda = self::$db->insert("vendas", $data_venda);

						$data_update = array(
							'venda_agrupada' => $id_venda,
							'fiscal' => 2,
						);
						self::$db->update("vendas", $data_update, "id IN (".$ids_vendas.")");
						
						$lista_produtos = $this->getProdutosVendas($ids_vendas);
						foreach($lista_produtos AS $produto) {
							$data_cadastro_venda = array(
								'id_empresa' => $produto->id_empresa,
								'id_venda' => $id_venda,
								'id_produto' => $produto->id_produto,
								'valor_custo' => $produto->valor_custo,
								'valor_original' => $produto->valor_original,
								'valor' => $produto->valor,
								'valor_despesa_acessoria' => $produto->valor_despesa_acessoria,
								'quantidade' => $produto->quantidade,
								'valor_desconto' => $produto->valor_desconto,
								'valor_total' => $produto->valor_total,
								'pago' => 3,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->insert("cadastro_vendas", $data_cadastro_venda);								
						}
							
						$lista_pagamentos = $this->getTodosFinanceirosVendas($ids_vendas);
						foreach($lista_pagamentos AS $pagamento) {
							$data_pagamentos = array(
								'id_empresa' => $pagamento->id_empresa,
								'id_venda' => $id_venda,
								'tipo' => $pagamento->id,
								'valor_total_venda' => $pagamento->valor_pago,
								'valor_pago' => $pagamento->valor_pago,
								'total_parcelas' => ($pagamento->categoria==1 || $pagamento->categoria==3 || $pagamento->categoria==6) ? 1 : $pagamento->total_parcelas,
								'pago' => 3,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->insert("cadastro_financeiro", $data_pagamentos);
						}
							
					}

					$valor_vendas = $venda->valor_venda;
					$valor_descontos = $venda->valor_desconto;
					$valor_despesas = $venda->valor_despesa_acessoria;
					$valor_total = $venda->valor_total;
					$ids_vendas = $venda->id;

				}

				print Filter::msgOk(lang('FISCAL_CONTROLE_OK'), "index.php?do=vendas&acao=vendas_fiscal_lote");

			} else {
				print Filter::msgStatus();			
			}

		} else {
			Filter::$msgs['erro_vendas'] = lang('MSG_ERRO_VENDAS_ERRO');
			print Filter::msgStatus();
		}
	}

	/**
	 * Faturamento::processarTranferenciaBanco()
	 *
	 * @return
	 */
	public function processarTranferenciaBanco()
	{
		if (empty($_POST['id_banco_origem']))
			Filter::$msgs['id_banco_origem'] = lang('MSG_ERRO_BANCO');

		if (empty($_POST['id_banco_destino']))
			Filter::$msgs['id_banco_destino'] = lang('MSG_ERRO_BANCO');

		if (!empty($_POST['id_banco_origem']) && !empty($_POST['id_banco_destino'])) {
			if (sanitize($_POST['id_banco_destino']) == sanitize($_POST['id_banco_origem'])) {
				Filter::$msgs['id_banco_iguais'] = lang('MSG_ERRO_BANCO_IGUAIS');
			}
		}

		if (empty($_POST['valor']))
			Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');

		if (empty(Filter::$msgs)) {

			$nome_origem = getValue("banco", "banco", "id = " . sanitize($_POST['id_banco_origem']));
			$id_empresa_origem = getValue("id_empresa", "banco", "id = " . sanitize($_POST['id_banco_origem']));
			$nome_destino = getValue("banco", "banco", "id = " . sanitize($_POST['id_banco_destino']));
			$id_empresa_destino = getValue("id_empresa", "banco", "id = " . sanitize($_POST['id_banco_destino']));

			$obs = 'TRANSFERENCIA ENTRE BANCO DE ' . $nome_origem . ' PARA ' . $nome_destino;

			$data_transferencia = (empty($_POST['data'])) ? date('d/m/Y') : sanitize($_POST['data']);

			$data_despesa = array(
				'id_empresa' => $id_empresa_origem,
				'id_banco' => sanitize($_POST['id_banco_origem']),
				'id_conta' => 118,
				'descricao' => $obs,
				'valor' => converteMoeda($_POST['valor']),
				'valor_pago' => converteMoeda($_POST['valor']),
				'data_pagamento' => dataMySQL($data_transferencia),
				'data_vencimento' => dataMySQL($data_transferencia),
				'pago' => '1',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			$id_despesa = self::$db->insert("despesa", $data_despesa);

			$data_receita = array(
				'id_empresa' => $id_empresa_destino,
				'id_banco' => sanitize($_POST['id_banco_destino']),
				'id_conta' => 117,
				'id_despesa' => $id_despesa,
				'descricao' => $obs,
				'valor' => converteMoeda($_POST['valor']),
				'valor_pago' => converteMoeda($_POST['valor']),
				'data_pagamento' => dataMySQL($data_transferencia),
				'data_recebido' => dataMySQL($data_transferencia),
				'data_fiscal' => dataMySQL($data_transferencia),
				'parcela' => '1',
				'pago' => '1',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			$id_receita = self::$db->insert("receita", $data_receita);

			$data_update = array(
				'agrupar' => $id_despesa,
				'id_receita' => $id_receita,
			);
			self::$db->update("despesa", $data_update, "id=" . $id_despesa);

			$message = lang('BANCO_TRANSACAO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=banco&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::obterPagamentosCategoria($id_categoria)
	 *
	 * @return
	 */
	public function obterListaPagamentosCategoria($id_categoria)
	{
		$sql = "SELECT id FROM tipo_pagamento WHERE id_categoria=$id_categoria AND inativo=0";
		$row = self::$db->fetch_all($sql);
		if ($row) {
			$array_tipo = array();
			foreach ($row as $rtipo) {
				array_push($array_tipo, $rtipo->id);
			}
			return implode(',', $array_tipo);
		} else {
			return '0';
		}
	}

	/**
	 * Faturamento::obterListaPagamentosCategoriaDinheiro($id_categoria)
	 *
	 * @return
	 */
	public function obterListaPagamentosCategoriaDinheiro($id_categoria)
	{
		$sql = "SELECT id, tipo FROM tipo_pagamento WHERE id_categoria = $id_categoria AND inativo = 0";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::processarValidarCaixa()
	 *
	 * @return
	 */
	public function processarValidarCaixa()
	{
		if (empty($_POST['id_banco']))
			Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');

		if (empty($_POST['id_caixa']))
			Filter::$msgs['id_caixa'] = lang('CAIXA_ERRO');

		if (empty(Filter::$msgs)) {

			$tipo_pgto_dinheiro = $this->obterListaPagamentosCategoria(1); /* retorna lista de tipos separados por virgula */

			$id_caixa = intval($_POST['id_caixa']);
			$id_banco = sanitize($_POST['id_banco']);
			$id_tipopagamento= sanitize($_POST['id_tipopagamento']);
			$id_validar = session('uid');

			$dinheiro_caixa = round(floatval(converteMoeda(post('valor_dinheiro'))), 2, PHP_ROUND_HALF_UP);

			$sql = "SELECT SUM(d.valor) AS total "
				. "\n FROM despesa as d "
				. "\n WHERE d.id_caixa = '$id_caixa' AND d.inativo = 0 AND d.pago = 1 ";
			$row = self::$db->first($sql);
			$valor_retirada = ($row) ? $row->total : 0;

			$sql = "SELECT SUM(f.valor_pago) AS total "
				. "\n FROM cadastro_financeiro as f "
				. "\n WHERE f.id_caixa = '$id_caixa' AND f.inativo = 0 AND f.tipo IN ($tipo_pgto_dinheiro)";
			$row = self::$db->first($sql);
			$valor_dinheiro = ($row) ? $row->total : 0;

			$sql_crediario = "SELECT SUM(f.valor_pago) AS total "
				. "\n FROM cadastro_crediario_pagamentos as f "
				. "\n WHERE f.id_caixa = $id_caixa AND f.inativo = 0 AND f.tipo_pagamento IN ($tipo_pgto_dinheiro)";
			$row_crediario = self::$db->first($sql_crediario);
			$valor_dinheiro += ($row_crediario) ? $row_crediario->total : 0;

			$totaldinheiro = round(floatval($valor_dinheiro) - floatval($valor_retirada), 2, PHP_ROUND_HALF_UP);

			$data_update = array(
				'id_banco' => $id_banco,
				'id_caixa' => $id_caixa
			);
			self::$db->update("receita", $data_update, "tipo IN (" . $tipo_pgto_dinheiro . ") AND id_caixa=" . $id_caixa);

			if ($totaldinheiro > 0) {
				$nome_banco = getValue("banco", "banco", "id = " . $id_banco);

				if ($dinheiro_caixa != $totaldinheiro) {
					$descricao = "TRANSFERENCIA DE DINHEIRO DO CAIXA [" . $id_caixa . "] PARA O BANCO [" . $nome_banco . "]. Quebra de Caixa, valor sistema = [" . moeda($totaldinheiro) . "]";

					if ($dinheiro_caixa < $totaldinheiro) {
						$descricaoDespesa = "DESPESA DE QUEBRA DE CAIXA [" . $id_caixa . "]. Valor sistema = [" . moeda($totaldinheiro) . "]. Valor caixa = [" . moeda($dinheiro_caixa) . "]";
						$valor_despesa = $totaldinheiro - $dinheiro_caixa;
						$data_despesa = array(
							'id_banco' => $id_banco,
							'id_conta' => '27',
							'descricao' => $descricaoDespesa,
							'valor' => $valor_despesa,
							'valor_pago' => $valor_despesa,
							'data_pagamento' => "NOW()",
							'data_vencimento' => "NOW()",
							'pago' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$id_despesa = self::$db->insert("despesa", $data_despesa);
					}

					$totaldinheiro = $dinheiro_caixa;
				} else {
					$descricao = "TRANSFERENCIA DE DINHEIRO DO CAIXA [" . $id_caixa . "] PARA O BANCO " . $nome_banco;
				}

				$data_receita = array(
					'id_empresa' => session('idempresa'),
					'id_conta' => 19,
					'id_banco' => $id_banco,
					'id_caixa' => $id_caixa,
					'descricao' => $descricao,
					'valor' => $totaldinheiro,
					'valor_pago' => $totaldinheiro,
					'data_pagamento' => "NOW()",
					'data_recebido' => "NOW()",
					'tipo' => $id_tipopagamento,
					'pago' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("receita", $data_receita);

			}

			$data = array(
				'id_validar' => $id_validar,
				'data_validar' => "NOW()",
				'status' => '3',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->update("caixa", $data, "id=" . $id_caixa);

			if (self::$db->affected()) {
				print Filter::msgOk(lang('CAIXA_VALIDAR_OK'), "index.php?do=caixa&acao=aberto");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::processarCreditoBanco()
	 *
	 * @return
	 */
	public function processarCreditoBanco()
	{
		if (empty($_POST['tipo']))
			Filter::$msgs['tipo'] = lang('MSG_ERRO_TIPO');

		if (empty($_POST['id_conta']))
			Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');

		if (empty($_POST['id_banco']))
			Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');

		if (empty($_POST['valor']))
			Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');

		if (empty($_POST['id_cadastro']) and empty($_POST['cadastro']))
			Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_CADASTRO');

		if (empty(Filter::$msgs)) {

			$id_cadastro = post('id_cadastro');
			if (empty($_POST['id_cadastro'])) {
				$nome = cleanSanitize(post('cadastro'));
				$data_cadastro = array(
					'nome' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
					'cliente' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_cadastro = self::$db->insert("cadastro", $data_cadastro);
			}

			$data_temp = (empty($_POST['data_pagamento'])) ? date('d/m/Y') : sanitize($_POST['data_pagamento']);
			$repeticoes = (is_numeric($_POST['repeticoes']) and $_POST['repeticoes'] > 0) ? $_POST['repeticoes'] : 1;
			$dias = (empty($_POST['dias'])) ? 30 : $_POST['dias'];
			$data_repeticoes = explode('/', $data_temp);
			for ($i = 0; $i < $repeticoes; $i++) {
				$mes = ($dias == 30) ? $i : 0;
				$dia = ($dias != 30) ? $dias * $i : 0;
				$newData = date('Y-m-d', mktime(0, 0, 0, $data_repeticoes[1] + $mes, $data_repeticoes[0] + $dia, $data_repeticoes[2]));
				$parc = $i + 1;
				$descricao = ($repeticoes > 1) ? sanitize(post('descricao')) . " - PARCELA [" . $parc . "/" . $repeticoes . "]" : sanitize(post('descricao'));
				$data = array(
					'id_empresa' => post('id_empresa'),
					'id_cadastro' => $id_cadastro,
					'id_banco' => post('id_banco'),
					'id_conta' => post('id_conta'),
					'descricao' => $descricao,
					'duplicata' => sanitize(post('duplicata')),
					'tipo' => post('tipo'),
					'valor' => converteMoeda(post('valor')),
					'valor_pago' => converteMoeda(post('valor')),
					'parcela' => '1',
					'data_pagamento' => $newData,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("receita", $data);
			}
			$message = lang('FINANCEIRO_RECEITA_ADICIONADA_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=faturamento&acao=receber");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::processarReceitaRapida()
	 *
	 * @return
	 */
	public function processarReceitaRapida()
	{
		if (empty(Filter::$msgs)) {
			$id_empresa = $_POST['id_empresa'];
			$id_banco = $_POST['id_banco'];
			$tipo = $_POST['tipo'];
			$descricao = $_POST['descricao'];
			$valor = $_POST['valor'];
			$data_receita = $_POST['data_receita'];
			$contar_receitas = count($id_empresa);

			for ($i = 0; $i < $contar_receitas; $i++) {
				$data = array(
					'id_empresa' => $id_empresa[$i],
					'id_banco' => $id_banco[$i],
					'tipo' => $tipo[$i],
					'id_conta' => 19,
					'descricao' => sanitize($descricao[$i]),
					'valor' => converteMoeda($valor[$i]),
					'valor_pago' => converteMoeda($valor[$i]),
					'pago' => '1',
					'parcela' => '1',
					'data_pagamento' => dataMySQL($data_receita[$i]),
					'data_recebido' => dataMySQL($data_receita[$i]),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("receita", $data);
			}
			$message = lang('FINANCEIRO_RECEITA_ADICIONADA_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=faturamento&acao=receitarapida");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::editarReceitas()
	 *
	 * @return
	 */
	public function editarReceitas()
	{
		if (empty($_POST['id_conta']))
			Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');

		if (empty($_POST['descricao']))
			Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');

		if (empty($_POST['valor']))
			Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');

		if (empty(Filter::$msgs)) {
			$situacao_pago = getValue("pago", "receita", "id=" . post('id_receita'));
			$id_nota = getValue("id_nota", "receita", "id=" . post('id_receita')); //Verifica se a receita tem id_nota mesmo sendo editada fora da nota
			$data = array(
				'id_empresa' => sanitize(post('id_empresa')),
				'id_banco' => sanitize(post('id_banco')),
				'id_cadastro' => sanitize(post('id_cadastro')),
				'descricao' => sanitize(post('descricao')),
				'duplicata' => sanitize(post('duplicata')),
				'id_conta' => sanitize(post('id_conta')),
				'id_nota' => $id_nota,
				'tipo' => sanitize(post('tipo')),
				'valor' => converteMoeda((post('valor'))),
				'valor_pago' => converteMoeda((post('valor_pago'))),
				'data_recebido' => dataMySQL(post('data_recebido')),
				'data_fiscal' => (post('data_fiscal') && !empty(post('data_fiscal') && (post('data_fiscal') != '--'))) ? dataMySQL(post('data_fiscal')) : '0000-00-00',
				'data_pagamento' => dataMySQL(post('data_pagamento')),
				'pago' => (post('pago')) ? post('pago') : (($situacao_pago == 2) ? 2 : 0),
				'fiscal' => post('fiscal'),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->update("receita", $data, "id=" . post('id_receita'));

			$message = lang('FINANCEIRO_RECEITA_AlTERADO_OK');
			if (post('id_nota') > 0) {
				$redirecionar = "index.php?do=notafiscal&acao=visualizar&id=" . post('id_nota');
			} else {
				$mes_ano = post('pago') ? explode("/", (post('data_recebido'))) : explode("/", (post('data_pagamento')));
				if (post('pago') == 1) {
					$acao = "recebidas";
				} else {
					if (post('promissoria') == 0) {
						$acao = "receber";
					} else {
						$acao = "receber_crediario";
					}
				}
				$redirecionar = "index.php?do=faturamento&acao=" . $acao . "&dataini=01/" . $mes_ano[1] . "/" . $mes_ano[2] . "&datafim=30/" . $mes_ano[1] . "/" . $mes_ano[2];
			}

			if (self::$db->affected()) {
				Filter::msgOk($message, $redirecionar);
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::processarDataReceita()
	 *
	 * @return
	 */
	public function processarDataReceita()
	{
		if (empty($_POST['id_banco']))
			Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');

		if (empty($_POST['data_recebido']))
			Filter::$msgs['data_recebido'] = lang('MSG_ERRO_DATA');

		if (empty(Filter::$msgs)) {

			$data = array(
				'id_empresa' => sanitize($_POST['id_empresa']),
				'id_banco' => sanitize($_POST['id_banco']),
				'id_conta' => sanitize(post('id_conta')),
				'data_recebido' => dataMySQL($_POST['data_recebido']),
				'data_fiscal' => dataMySQL($_POST['data_recebido']),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			if (!empty($_POST['tipo'])) {
				$data['tipo'] = sanitize($_POST['tipo']);
			}
			self::$db->update("receita", $data, "id=" . $_POST['id_receita']);

			$message = lang('FINANCEIRO_RECEITA_AlTERADO_OK');


			if (empty($_POST['numero_cartao'])) {
				$redirecionar = "index.php?do=faturamento&acao=receber&id_banco=" . $_POST['id_banco'] . "&dataini=" . $_POST['data_recebido'];
			} else {
				$redirecionar = "index.php?do=faturamento&acao=cartoes&numero_cartao=" . $_POST['numero_cartao'];
			}



			if (self::$db->affected()) {
				Filter::msgOk($message, $redirecionar);
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::obterReceitasNFeDuplicatas($id_nota)
	 *
	 * @return
	 */
	public function obterReceitasNFeDuplicatas($id_nota)
	{
		$id_venda = getValue("id_venda", "nota_fiscal", "id = " . $id_nota);
		if ($id_venda) {
			$sql = "SELECT cf.valor_pago as valor, cf.data_vencimento as data_pagamento"
				. "\n FROM cadastro_financeiro as cf "
				. "\n LEFT JOIN tipo_pagamento as tp ON tp.id = cf.tipo "
				. "\n WHERE cf.inativo=0 AND tp.exibir_nfe=1 AND cf.id_venda = $id_venda "
				. "\n ORDER BY cf.data_vencimento, cf.id ";
		} else {
			$sql = "SELECT r.*, tp.exibir_nfe "
				. "\n FROM receita as r "
				. "\n LEFT JOIN tipo_pagamento as tp ON tp.id = r.tipo "
				. "\n WHERE r.inativo = 0 AND tp.exibir_nfe = 1 AND r.id_nota = $id_nota "
				. "\n ORDER BY data_pagamento, id ";
		}
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::processarNotaFiscalReceita()
	 *
	 * @return
	 */
	public function processarNotaFiscalReceita()
	{

		if (empty(Filter::$msgs)) {

			$data_pagamento = (empty($_POST['data_pagamento'])) ? date('d/m/Y') : sanitize($_POST['data_pagamento']);
			$descricao = "RECEITA GERADA PARA NOTA";
			if (post('modelo') == 1)
				$descricao .= " FISCAL DE SERVICO - ";
			elseif (post('modelo') == 2)
				$descricao .= " FISCAL DE PRODUTO - ";
			elseif (post('modelo') == 3)
				$descricao .= " DE FATURA - ";
			else
				$descricao .= " - ";

			$descricao .= post('numero_nota');
			
			$id_nota = sanitize(post('id_nota'));
			$data = array(
				'id_empresa' => sanitize(post('id_empresa')),
				'id_cadastro' => sanitize(post('id_cadastro')),
				'id_nota' => $id_nota,
				'id_conta' => 19,
				'id_banco' => post('id_banco'),
				'duplicata' => sanitize(post('duplicata')),
				'descricao' => $descricao,
				'tipo' => sanitize(post('tipo')),
				'parcela' => '1',
				'valor' => converteMoeda(($_POST['valor'])),
				'valor_pago' => converteMoeda(($_POST['valor'])),
				'data_pagamento' => dataMySQL($data_pagamento),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->insert("receita", $data);
			$message = lang('FINANCEIRO_RECEITA_ADICIONADA_OK');

			if (self::$db->affected()) {

				$row_receitas = $this->obterReceitasNFeDuplicatas($id_nota);
				$duplicatas = "";
				if ($row_receitas) {
					$contador = 1;
					foreach ($row_receitas as $rrow) {
						$aux_contador = str_pad($contador++, 3, '0', STR_PAD_LEFT);
						$duplicatas .= "($aux_contador, " . exibedata($rrow->data_pagamento) . ", " . moeda($rrow->valor) . ")";
					}
				}
				$data_duplicatas = array(
					'duplicatas' => $duplicatas
				);
				self::$db->update("nota_fiscal", $data_duplicatas, "id=" . $id_nota);


				Filter::msgOk($message, "index.php?do=notafiscal&acao=visualizar&id=" . $id_nota);
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::getContasReceitas()
	 *
	 * @return
	 */
	public function getContasReceitas($mes_ano)
	{
		$sql = "SELECT c.id, c.conta"
			. "\n FROM receita as p"
			. "\n LEFT JOIN conta as c ON c.id = p.id_conta"
			. "\n WHERE DATE_FORMAT(p.data_vencimento, '%m/%Y') = '$mes_ano' "
			. "\n GROUP BY c.conta"
			. "\n ORDER BY c.conta";
		//echo $sql;
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::totalPagamentos()
	 *
	 * @return
	 */
	public function totalPagamentos($consdata = false)
	{
		$data = ($consdata) ? $consdata : date("d/m/Y");

		$sql = "SELECT SUM(valor) as total FROM receita"
			. "\n WHERE pago = 1 AND DATE_FORMAT(data_pagamento, '%d/%m/%Y') = '$data'";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::totalPagamentosMes()
	 *
	 * @return
	 */
	public function totalPagamentosMes($consdata = false, $id_conta = false, $pago = 1)
	{
		$data = ($consdata) ? $consdata : date("m/Y");
		$conta = ($id_conta) ? "AND id_conta = '$id_conta'" : "";

		$sql = "SELECT SUM(valor) as total FROM receita"
			. "\n WHERE pago = $pago AND DATE_FORMAT(data_vencimento, '%m/%Y') = '$data' $conta";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::totalFaturamentosMes()
	 *
	 * @return
	 */
	public function totalFaturamentosMes($consdata = false, $id_conta = false)
	{
		$data = ($consdata) ? $consdata : date("m/Y");
		$conta = ($id_conta) ? "AND id_conta = '$id_conta'" : "";

		$sql = "SELECT SUM(valor) as total FROM faturamento "
			. "\n WHERE inativo = 0 AND DATE_FORMAT(data_pagamento, '%m/%Y') = '$data' $conta";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::processarConta()
	 *
	 * @return
	 */
	public function processarConta()
	{
		if (empty($_POST['conta']))
			Filter::$msgs['conta'] = lang('MSG_PADRAO');

		if (empty(Filter::$msgs)) {

			$data = array(
				'conta' => sanitize(post('conta')),
				'tipo' => sanitize(post('tipo')),
				'contabil' => sanitize(post('contabil')),
				'id_pai' => sanitize(post('id_pai')),
				'ordem' => sanitize(post('ordem')),
				'dre' => sanitize(post('dre'))
			);
			(Filter::$id) ? self::$db->update("conta", $data, "id=" . Filter::$id) : self::$db->insert("conta", $data);
			$message = (Filter::$id) ? lang('FINANCEIRO_CONTA_AlTERADO_OK') : lang('FINANCEIRO_CONTA_ADICIONADO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=faturamento&acao=plano_contas");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::getContas()
	 *
	 * @return
	 */
	public function getContas()
	{
		$sql = "SELECT filho.id AS id, filho.contabil, pai.conta AS pai, filho.conta as filho, filho.tipo, filho.exibir, filho.dre, filho.ordem "
			. "\n FROM conta AS pai "
			. "\n RIGHT JOIN conta AS filho ON filho.id_pai = pai.id "
			. "\n ORDER BY pai.conta, filho.conta";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getCentroCusto()
	 *
	 * @return
	 */
	public function getCentroCustoDespesa()
	{
		$sql = "SELECT id, centro_custo FROM centro_custo WHERE inativo = 0 ORDER BY centro_custo";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getPAI()
	 *
	 * @return
	 */
	public function getPai($tipo = false)
	{
		$where = ($tipo) ? "AND tipo in ($tipo)" : "";
		$sql = "SELECT id, conta, ordem FROM conta WHERE exibir = 1 AND id_pai IS NULL $where ORDER BY conta";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getFilho()
	 *
	 * @return
	 */
	public function getFilho($pai)
	{
		$sql = "SELECT c1.conta AS pai, c2.id AS id_filho, c2.conta AS filho FROM conta AS c1 LEFT JOIN conta AS c2 ON c2.id_pai = c1.id WHERE c1.id = '$pai' AND c1.exibir = 1 AND c2.exibir = 1 ORDER BY c2.conta";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceber()
	 *
	 * @return
	 */
	public function getReceber()
	{
		$sql = "SELECT count(1) AS quant, SUM(p.valor) as valor "
			. "\n FROM receita as p"
			. "\n WHERE p.pago = 0 AND p.data_vencimento > CURDATE()";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getSaldoInicial()
	 *
	 * @return
	 */
	public function getSaldoInicial($data = false, $id_banco = 0)
	{
		$data = ($data) ? $data : date('d/m/Y');

		$sql = "SELECT SUM(valor_pago) as total FROM despesa "
			. "\n WHERE id_banco = '$id_banco' AND pago = 1 AND inativo = 0 AND data_pagamento < STR_TO_DATE('$data 00:00:00','%d/%m/%Y %H:%i:%s')";
		$row = self::$db->first($sql);

		$despesa = ($row) ? $row->total : 0;


		$sql = "SELECT SUM(valor_pago) as total FROM receita "
			. "\n WHERE id_banco = '$id_banco' AND pago = 1 AND inativo = 0 AND data_recebido < STR_TO_DATE('$data 00:00:00','%d/%m/%Y %H:%i:%s')";
		$row = self::$db->first($sql);

		$receita = ($row) ? $row->total : 0;

		$sql = "SELECT saldo FROM banco"
			. "\n WHERE inativo = 0 "
			. "\n AND id = $id_banco";
		$row = self::$db->first($sql);

		$saldo = ($row) ? $row->saldo : 0;

		return $saldo + $receita - $despesa;
	}

	/**
	 * Faturamento::getSaldoTotal()
	 *
	 * @return
	 */
	public function getSaldoTotal($data = false, $id_banco = 0)
	{
		$data = ($data) ? $data : date('d/m/Y');

		$sql = "SELECT SUM(valor_pago) as total FROM despesa "
			. "\n WHERE id_banco = '$id_banco' AND pago = 1 AND inativo = 0 AND data_pagamento < STR_TO_DATE('$data 23:59:59','%d/%m/%Y %H:%i:%s')";
		$row = self::$db->first($sql);

		$despesa = ($row) ? $row->total : 0;


		$sql = "SELECT SUM(valor_pago) as total FROM receita "
			. "\n WHERE id_banco = '$id_banco' AND pago = 1 AND inativo = 0 AND data_recebido < STR_TO_DATE('$data 23:59:59','%d/%m/%Y %H:%i:%s')";
		$row = self::$db->first($sql);

		$receita = ($row) ? $row->total : 0;

		$sql = "SELECT saldo FROM banco"
			. "\n WHERE inativo = 0 "
			. "\n AND id = $id_banco";
		$row = self::$db->first($sql);

		$saldo = ($row) ? $row->saldo : 0;

		return $saldo + $receita - $despesa;
	}

	/**
	 * Faturamento::totalCreditoBanco()
	 *
	 * @return
	 */
	public function totalCreditoBanco($id_banco)
	{

		$sql = "SELECT SUM(valor_pago) as total FROM receita "
			. "\n WHERE id_banco = '$id_banco' AND pago = 1 AND inativo = 0";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getFaturamentoParcelas()
	 *
	 * @return
	 */
	public function getFaturamentoReceitas($mes_ano = 0)
	{
		$sql = "SELECT SUM(r.valor_pago) AS total "
			. "\n FROM receita as r "
			. "\n WHERE DATE_FORMAT(r.data_pagamento, '%m/%Y') = '$mes_ano' ";
		//echo $sql;
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getFaturamentoProdutos()
	 *
	 * @return
	 */
	public function getFaturamentoProdutos($mes_ano = 0)
	{
		$sql = "SELECT SUM(c.valor_total) AS total "
			. "\n FROM cadastro_vendas as c "
			. "\n WHERE c.pago = 1 AND c.inativo = 0 AND DATE_FORMAT(c.data, '%m/%Y') = '$mes_ano' ";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getFaturamentoServicos()
	 *
	 * @return
	 */
	public function getFaturamentoServicos($mes_ano = 0)
	{
		$sql = "SELECT SUM(c.valor_total) AS total "
			. "\n FROM cadastro_vendas as c "
			. "\n WHERE c.pago = 1 AND c.inativo = 0 AND DATE_FORMAT(c.data, '%m/%Y') = '$mes_ano' ";
		//echo $sql;
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::processarMetaDRE()
	 *
	 * @return
	 */
	public function processarMetaDRE()
	{
		if (empty($_POST['id_conta']))
			Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');

		if (empty($_POST['mes_ano']))
			Filter::$msgs['mes_ano'] = lang('MSG_ERRO_DATA');

		if (empty(Filter::$msgs)) {

			$mes_ano = $_POST['mes_ano'];

			if (strlen($mes_ano) > 9) {
				$mes_ano = substr($mes_ano, 3);
			}
			$ano = explode("/", $mes_ano);
			$id_conta = $_POST['id_conta'];
			self::$db->delete("conta_meta", "id_conta = $id_conta AND DATE_FORMAT(mes_ano, '%m/%Y') = '$mes_ano'");
			$mes_ano = "01/" . $mes_ano;

			$data = array(
				'id_conta' => sanitize($_POST['id_conta']),
				'mes_ano' => dataMySQL($mes_ano),
				'meta' => converteMoeda($_POST['valor']),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			self::$db->insert("conta_meta", $data);
			$message = lang('META_ADICIONADO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=faturamento&acao=metasdre&ano=" . $ano[1]);
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::getMetas()
	 *
	 * @return
	 */
	public function getMetasDRE($mes_ano, $id_conta)
	{
		$sql = "SELECT SUM(meta) as total FROM conta_meta WHERE id_conta = $id_conta AND DATE_FORMAT(mes_ano, '%m/%Y') = '$mes_ano' ";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getReceitaDinheiro()
	 *
	 * @return
	 */
	public function getReceitaDinheiro($mes_ano = 0)
	{
		$tipo_pgto_dinheiro = $this->obterListaPagamentosCategoria(1);
		$sql = "SELECT SUM(r.valor_pago) AS total "
			. "\n FROM receita as r "
			. "\n LEFT JOIN conta as c ON c.id = r.id_conta "
			. "\n WHERE r.inativo = 0 AND r.pago = 1 AND c.exibir = 1 AND c.dre = 1 AND r.tipo IN ($tipo_pgto_dinheiro) AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' ";
		//echo $sql;
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getReceitaCheque()
	 *
	 * @return
	 */
	public function getReceitaCheque($mes_ano = 0)
	{
		$tipo_pgto_cheque = $this->obterListaPagamentosCategoria(2);
		$sql = "SELECT SUM(r.valor_pago) AS total "
			. "\n FROM receita as r "
			. "\n LEFT JOIN conta as c ON c.id = r.id_conta "
			. "\n WHERE r.inativo = 0 AND r.pago = 1 AND c.exibir = 1 AND c.dre = 1 AND r.tipo IN ($tipo_pgto_cheque) AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' ";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getReceitaDeposito()
	 *
	 * @return
	 */
	public function getReceitaDeposito($mes_ano = 0)
	{
		$tipo_pgto1 = $this->obterListaPagamentosCategoria(3);
		$tipo_pgto2 = $this->obterListaPagamentosCategoria(6);
		$sql = "SELECT SUM(r.valor_pago) AS total "
			. "\n FROM receita as r "
			. "\n LEFT JOIN conta as c ON c.id = r.id_conta "
			. "\n WHERE r.inativo = 0 AND r.pago = 1 AND c.exibir = 1 AND c.dre = 1 AND (r.tipo IN ($tipo_pgto1) OR r.tipo IN ($tipo_pgto2)) AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' ";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getReceitaDebito()
	 *
	 * @return
	 */
	public function getReceitaDebito($mes_ano = 0)
	{
		$tipo_pgto = $this->obterListaPagamentosCategoria(7);
		$sql = "SELECT SUM(r.valor_pago) AS total "
			. "\n FROM receita as r "
			. "\n LEFT JOIN conta as c ON c.id = r.id_conta "
			. "\n WHERE r.inativo = 0 AND r.pago = 1 AND c.exibir = 1 AND c.dre = 1 AND r.tipo IN ($tipo_pgto) AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' ";
		//echo $sql;
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getReceitaCredito()
	 *
	 * @return
	 */
	public function getReceitaCredito($mes_ano = 0)
	{
		$tipo_pgto = $this->obterListaPagamentosCategoria(8);
		$sql = "SELECT SUM(r.valor_pago) AS total "
			. "\n FROM receita as r "
			. "\n LEFT JOIN conta as c ON c.id = r.id_conta "
			. "\n WHERE r.inativo = 0 AND r.pago = 1 AND c.exibir = 1 AND c.dre = 1 AND r.tipo IN ($tipo_pgto) AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' ";
		//echo $sql;
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getReceitaDeposito()
	 *
	 * @return
	 */
	public function getReceitaTransferencia($mes_ano = 0)
	{
		$tipo_pgto1 = $this->obterListaPagamentosCategoria(3);
		$tipo_pgto2 = $this->obterListaPagamentosCategoria(6);
		$sql = "SELECT SUM(r.valor_pago) AS total "
			. "\n FROM receita as r "
			. "\n LEFT JOIN conta as c ON c.id = r.id_conta "
			. "\n WHERE r.inativo = 0 AND r.pago = 1 AND c.exibir = 1 AND c.dre = 1 AND (r.tipo IN ($tipo_pgto1) OR r.tipo IN ($tipo_pgto2)) AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' ";
		$row = self::$db->first($sql);
		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getCheques()
	 *
	 * @return
	 */
	public function getCheques()
	{
		$tipo_pgto = $this->obterListaPagamentosCategoria(2);
		$sql = "SELECT f.id, c.conta, cl.nome as cadastro, f.id_banco, b.banco, f.tipo, f.id_cadastro, f.valor_pago, f.conciliado, f.data_recebido, f.data_pagamento, f.pago, f.fiscal, f.enviado, cf.nome_cheque, cf.banco_cheque, cf.numero_cheque,  DATE_FORMAT(f.data_recebido, '%Y%m%d') as controle, f.data_recebido < CURDATE() as atrasado, f.data_recebido = CURDATE() as hoje "
			. "\n FROM receita as f"
			. "\n LEFT JOIN conta as c ON c.id = f.id_conta"
			. "\n LEFT JOIN cadastro as cl ON cl.id = f.id_cadastro"
			. "\n LEFT JOIN cadastro_financeiro as cf ON cf.id = f.id_pagamento"
			. "\n LEFT JOIN banco as b ON b.id = f.id_banco"
			. "\n WHERE f.inativo = 0 AND f.tipo IN ($tipo_pgto) "
			. "\n ORDER BY f.data_recebido";
		//echo $sql;
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getDetalhesReceitas()
	 *
	 * @return
	 */
	public function getDetalhesReceitas($id_receita = 0)
	{
		$sql = "SELECT f.id, c.conta, f.id_conta, f.id_banco, b.banco, f.descricao, f.tipo, f.id_cadastro, f.valor, f.valor_pago, f.conciliado, f.data_fiscal, f.data_recebido, f.data_pagamento, f.pago, f.fiscal, f.enviado, t.tipo as pagamento, DATE_FORMAT(f.data_pagamento, '%Y%m%d') as controle, e.nome as empresa, ca.nome as cadastro, f.duplicata, f.id_empresa, f.inativo, f.id_nota, f.usuario, f.data, f.nossonumero, f.extrato_data, f.extrato_doc, f.data_remessa, f.data_retorno, f.parcela "
			. "\n FROM receita as f"
			. "\n LEFT JOIN conta as c ON c.id = f.id_conta"
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo"
			. "\n LEFT JOIN banco as b ON b.id = f.id_banco"
			. "\n LEFT JOIN empresa as e ON e.id = f.id_empresa"
			. "\n LEFT JOIN cadastro as ca ON ca.id = f.id_cadastro"
			. "\n WHERE f.id = '$id_receita' ";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getDetalhesPagamentoDinheiro()
	 *
	 * @return
	 */
	public function getDetalhesPagamentoDinheiro($pgto_dnhr, $id_cadastro)
	{
		$sql = "SELECT ccp.id, 'Pagamento de Crediário em Dinheiro' as conta, '0' as id_conta, '0' as id_banco, 'Caixa do dia' as banco,
			    'PAGAMENTO CREDIARIO/FICHA - DINHEIRO' as descricao, ccp.data_pagamento as tipo, ccp.id_cadastro, SUM(ccp.valor_pago) as valor,
			    SUM(ccp.valor_pago) as valor_pago, '0' as conciliado, ccp.data_pagamento as data_fiscal, ccp.data_pagamento as data_recebido, ccp.data_pagamento,
			    '1' as pago, '0' as fiscal, '0' as enviado, t.tipo as pagamento, DATE_FORMAT(ccp.data_pagamento, '%Y%m%d') as controle,
			    '' as empresa, '' as cadastro, '0' as duplicata, '1' as id_empresa, ccp.inativo, '0' as id_nota, ccp.usuario,  ccp.data,
			    '0' as nossonumero, '0' as extrato_data, '0' as extrato_doc, '0' as data_remessa, '0' as data_retorno, '1' as parcela
			    FROM cadastro_crediario_pagamentos as ccp
				LEFT JOIN tipo_pagamento as t ON t.id = ccp.tipo_pagamento
				WHERE CONCAT(ccp.data,ccp.id_caixa)='$pgto_dnhr' AND ccp.inativo = 0 AND ccp.id_cadastro = '$id_cadastro' AND t.id_categoria=1
				GROUP BY CONCAT(ccp.data,ccp.id_caixa) ";

		$row = self::$db->first($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitasReceber()
	 *
	 * @return
	 */
	public function getReceitasReceber($id_cadastro = false, $dataini = false, $datafim = false, $id_banco = false, $id_empresa = false, $ordem_cliente = false, $filtro = false)
	{
		$dataini = ($dataini) ? $dataini : date('d/m/Y', strtotime('-15 days'));
		$datafim = ($datafim) ? $datafim : date("d/m/Y");
		$where = "AND f.data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')";
		$wherebanco = ($id_banco) ? "AND f.id_banco = $id_banco" : "";
		$whereempresa = ($id_empresa) ? "AND f.id_empresa = $id_empresa" : "";
		$ordem = ($ordem_cliente == 1) ? "ORDER BY ca.nome" : "ORDER BY f.data_pagamento";
		$wherefiltro = ($filtro) ? " AND (f.descricao like '%" . $filtro . "%' OR ca.nome like '%" . $filtro . "%')" : "";
		$wherecadastro = ($id_cadastro) ? "AND f.id_cadastro = $id_cadastro" : ""; // +++++++++++
		$where = ($id_cadastro) ? "" : $where;

		$sql = "SELECT f.id, f.id_despesa, c.conta, f.id_conta, f.id_empresa, f.id_banco, b.banco, f.id_nota, f.descricao, f.tipo, f.id_cadastro, f.valor, f.valor_pago, f.conciliado, f.data_recebido, f.data_pagamento, f.pago, f.fiscal, f.enviado, f.remessa, t.tipo as pagamento, DATE_FORMAT(f.data_pagamento, '%Y%m%d') as controle, f.data_pagamento < CURDATE() as atrasado, e.nome as empresa, ca.nome as cadastro, f.duplicata, f.id_empresa, n.numero_nota "
			. "\n FROM receita as f"
			. "\n LEFT JOIN conta as c ON c.id = f.id_conta"
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo"
			. "\n LEFT JOIN banco as b ON b.id = f.id_banco"
			. "\n LEFT JOIN empresa as e ON e.id = f.id_empresa"
			. "\n LEFT JOIN cadastro as ca ON ca.id = f.id_cadastro"
			. "\n LEFT JOIN nota_fiscal as n ON n.id = f.id_nota"
			. "\n WHERE f.inativo = 0 AND f.pago = 0 "
			. "\n $where"
			. "\n $wherebanco"
			. "\n $whereempresa"
			. "\n $wherefiltro"
			. "\n $wherecadastro" // ++++++++
			. "\n $ordem";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitas()
	 *
	 * @return
	 */
	public function getReceitas($id_cadastro = false, $dataini = false, $datafim = false, $id_banco = false, $id_empresa = false, $filtro)
	{
		$dataini = ($dataini) ? $dataini : date('d/m/Y', strtotime('-15 days'));
		$datafim = ($datafim) ? $datafim : date("d/m/Y");
		$where = "AND f.data_recebido BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')";
		$wherebanco = ($id_banco) ? "AND f.id_banco = $id_banco" : "";
		$whereempresa = ($id_empresa) ? "AND f.id_empresa = $id_empresa" : "";
		$wherefiltro = ($filtro) ? " AND (f.descricao like '%" . $filtro . "%' OR ca.nome like '%" . $filtro . "%')" : "";
		$wherecadastro = ($id_cadastro) ? "AND f.id_cadastro = $id_cadastro" : "";
		$where = ($id_cadastro) ? "" : $where;

		$sql = "SELECT f.id, f.id_despesa, c.conta, f.id_conta, f.id_banco, b.banco, f.id_nota, f.descricao, f.tipo, f.id_cadastro, f.valor,  f.valor_pago, f.conciliado, f.data_fiscal, f.data_recebido, f.data_pagamento, f.pago, f.fiscal, f.enviado, t.tipo as pagamento, DATE_FORMAT(f.data_recebido, '%Y%m%d') as controle, e.nome as empresa, ca.nome as cadastro, f.duplicata, f.id_empresa "
			. "\n FROM receita as f"
			. "\n LEFT JOIN conta as c ON c.id = f.id_conta"
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo"
			. "\n LEFT JOIN banco as b ON b.id = f.id_banco"
			. "\n LEFT JOIN empresa as e ON e.id = f.id_empresa"
			. "\n LEFT JOIN cadastro as ca ON ca.id = f.id_cadastro"
			. "\n WHERE f.inativo = 0 AND f.pago = 1 "
			. "\n $where"
			. "\n $wherebanco"
			. "\n $whereempresa"
			. "\n $wherefiltro"
			. "\n $wherecadastro"
			. "\n ORDER BY f.data_recebido";
		//echo $sql;
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitasReceberCrediario()
	 *
	 * @return
	 */
	public function getReceitasReceberCrediario($id_cadastro = false, $dataini = false, $datafim = false, $id_banco = false, $id_empresa = false, $ordem_cliente = false, $filtro = false)
	{
		$dataini = ($dataini) ? $dataini : date('d/m/Y', strtotime('-15 days'));
		$datafim = ($datafim) ? $datafim : date("d/m/Y");
		$where = "AND f.data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')";
		$wherebanco = ($id_banco) ? "AND f.id_banco = $id_banco" : "";
		$whereempresa = ($id_empresa) ? "AND f.id_empresa = $id_empresa" : "";
		$ordem = ($ordem_cliente == 1) ? "ORDER BY ca.nome" : "ORDER BY f.data_pagamento";
		$wherefiltro = ($filtro) ? " AND (f.descricao like '%" . $filtro . "%' OR ca.nome like '%" . $filtro . "%')" : "";
		$wherecadastro = ($id_cadastro) ? "AND f.id_cadastro = $id_cadastro" : "";
		$where = ($id_cadastro) ? "" : $where;

		$sql = "SELECT f.id, c.conta, f.id_conta, f.id_empresa, f.id_banco, b.banco, f.id_nota, f.descricao, f.tipo, f.id_cadastro, f.valor, f.valor_pago, f.conciliado, f.data_recebido, f.data_pagamento, f.pago, f.fiscal, f.enviado, f.remessa, t.tipo as pagamento, DATE_FORMAT(f.data_pagamento, '%Y%m%d') as controle, f.data_pagamento < CURDATE() as atrasado, e.nome as empresa, ca.nome as cadastro, f.duplicata, f.id_empresa, n.numero_nota "
			. "\n FROM receita as f"
			. "\n LEFT JOIN conta as c ON c.id = f.id_conta"
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo"
			. "\n LEFT JOIN banco as b ON b.id = f.id_banco"
			. "\n LEFT JOIN empresa as e ON e.id = f.id_empresa"
			. "\n LEFT JOIN cadastro as ca ON ca.id = f.id_cadastro"
			. "\n LEFT JOIN nota_fiscal as n ON n.id = f.id_nota"
			. "\n WHERE f.inativo=0 AND f.pago<>1 AND f.promissoria=1"
			. "\n $where"
			. "\n $wherebanco"
			. "\n $whereempresa"
			. "\n $wherefiltro"
			. "\n $wherecadastro"
			. "\n $ordem";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitasCadastro()
	 *
	 * @return
	 */
	public function getReceitasCadastro($id_cadastro = 0)
	{
		$sql = "SELECT 0 as crediario, f.id, c.conta, f.id_conta, f.id_banco, b.banco, f.id_nota, f.descricao, f.tipo, f.id_cadastro, f.valor,  "
			. "\n f.valor_pago, f.conciliado, f.data_recebido, f.data_pagamento, f.pago, f.fiscal, f.enviado, t.tipo as pagamento, "
			. "\n DATE_FORMAT(f.data_recebido, '%Y%m%d') as controle, e.nome as empresa, ca.nome as cadastro, f.duplicata, f.id_empresa, "
			. "\n f.inativo, n.numero_nota "
			. "\n FROM receita as f "
			. "\n LEFT JOIN conta as c ON c.id = f.id_conta "
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo "
			. "\n LEFT JOIN banco as b ON b.id = f.id_banco "
			. "\n LEFT JOIN empresa as e ON e.id = f.id_empresa "
			. "\n LEFT JOIN cadastro as ca ON ca.id = f.id_cadastro "
			. "\n LEFT JOIN nota_fiscal as n ON n.id = f.id_nota "
			. "\n WHERE f.inativo = 0 AND f.id_cadastro = '$id_cadastro' "
			. "\n UNION "
			. "\n SELECT 1 as crediario, ccp.id, 'Pagamento de Crediário - Dinheiro' as conta, 0 as id_conta, 0 as id_banco, 'Caixa do dia' as banco, "
			. "\n 0 as id_nota, 'Pagamento de Crediário - Dinheiro' as descricao, ccp.tipo_pagamento as tipo, ccp.id_cadastro, "
			. "\n ccp.valor_pago as valor,  ccp.valor_pago, 0 as conciliado, ccp.data_pagamento as data_recebido, ccp.data_pagamento, "
			. "\n 1 as pago, 0 as fiscal, 0 as enviado, t.tipo as pagamento, DATE_FORMAT(ccp.data_pagamento, '%Y%m%d') as controle, "
			. "\n '' as empresa, ca.nome as cadastro, 0 as duplicata, 0 as id_empresa, ccp.inativo, 0 as numero_nota "
			. "\n FROM cadastro_crediario_pagamentos as ccp "
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = ccp.tipo_pagamento "
			. "\n LEFT JOIN cadastro as ca ON ca.id = ccp.id_cadastro "
			. "\n WHERE ccp.inativo = 0 AND ccp.id_cadastro = '$id_cadastro' "
			. "\n ORDER BY controle";
		$row = ($id_cadastro) ? self::$db->fetch_all($sql) : 0;
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitasNota()
	 *
	 * @return
	 */
	public function getReceitasNota($id_nota = false)
	{
		if ($id_nota) {

			$sql = "SELECT	f.id,f.tipo,f.pago,f.valor,f.fiscal,f.id_nota,f.inativo,f.enviado,f.id_venda,f.id_conta,
			                f.id_banco,f.descricao,f.duplicata,f.valor_pago,f.id_empresa,f.conciliado,f.valorTaxado,
							f.id_cadastro,f.data_fiscal,f.data_recebido,f.data_pagamento,b.banco,c.conta,
							e.nome 	AS empresa,ca.nome AS cadastro,t.tipo 	AS pagamento,
							t.id_categoria AS categoria_pagamento,
							DATE_FORMAT(f.data_recebido, '%Y%m%d') AS controle

			  		FROM 		receita 		AS f
			  		LEFT JOIN 	conta 			AS c 	ON 	c.id  = f.id_conta
			  		LEFT JOIN 	tipo_pagamento 	AS t 	ON 	t.id  = f.tipo
			  		LEFT JOIN 	banco 			AS b 	ON 	b.id  = f.id_banco
			  		LEFT JOIN 	empresa 		AS e 	ON 	e.id  = f.id_empresa
			  		LEFT JOIN 	cadastro 		AS ca	ON 	ca.id = f.id_cadastro

			  		WHERE 	f.inativo = 0
			  		AND 	f.id_nota = $id_nota

			  		ORDER BY f.data_pagamento ";

			$row = self::$db->fetch_all($sql);

			return ($row) ? $row : 0;

		} else {
			return 0;
		}
	}

	/**
	 * Faturamento::getFinanceiroNota()
	 *
	 * @return
	 */

	public function getFinanceiroNota($id_nota = false)
	{
		if ($id_nota) {

			$sql = "SELECT	b.banco,
							cf.id_venda,
							cf.valor_pago,
							cf.valor_pago as valor,
							cf.data_pagamento,
							e.nome AS empresa,
							cf.pago,
							t.tipo AS pagamento,
							ca.nome AS cadastro,
							t.id_categoria AS categoria_pagamento,
							DATE_FORMAT( cf.data_pagamento, '%Y%m%d' ) AS controle

					FROM		cadastro_financeiro AS cf
					LEFT JOIN 	tipo_pagamento 		AS t 	ON t.id = cf.tipo
					LEFT JOIN 	banco 				AS b 	ON b.id = cf.id_banco
					LEFT JOIN 	empresa 			AS e 	ON e.id = cf.id_empresa
					LEFT JOIN 	cadastro 			AS ca 	ON ca.id = cf.id_cadastro

					WHERE	cf.inativo = 0
					AND 	cf.id_nota = $id_nota
					AND 	t.id_categoria = 1

					GROUP BY	cf.id";

			$row = self::$db->fetch_all($sql);

			return ($row) ? $row : 0;

		} else {
			return 0;
		}
	}

	/**
	 * Faturamento::getDetalhesNota()
	 *
	 * @return
	 */
	public function getDetalhesNota($id_nota)
	{
		$sql = "SELECT n.*, e.*, DATE_FORMAT(n.data_emissao, '%Y') as ano"
			. "\n FROM nota_fiscal as n"
			. "\n LEFT JOIN empresa as e ON e.id = n.id_empresa "
			. "\n WHERE n.id = $id_nota ";
		$row = self::$db->first($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getCartaoSistema()
	 *
	 * @return
	 */
	public function getCartaoSistema($numero_cartao)
	{

		$sql = "SELECT f.id, f.total_parcelas, f.parcelas_cartao, f.numero_cartao, f.data_pagamento, f.data_vencimento, f.valor_total_cartao, f.valor_parcelas_cartao, c.nome as cadastro, t.tipo, r.data_recebido, b.banco, r.id_banco, r.id as id_receita "
			. "\n FROM cadastro_financeiro as f"
			. "\n LEFT JOIN cadastro as c ON c.id = f.id_cadastro"
			. "\n LEFT JOIN receita as r ON r.id_pagamento = f.id"
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo"
			. "\n LEFT JOIN banco as b ON b.id = r.id_banco "
			. "\n WHERE f.inativo = 0 AND f.numero_cartao like '%" . $numero_cartao . "%' "
			. "\n ORDER BY f.data_vencimento";
		//echo $sql;
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getCartaoExtrato()
	 *
	 * @return
	 */
	public function getCartaoExtrato($numero_cartao)
	{

		$sql = "SELECT e.id, b.banco, e.produto, e.valor_liquido, e.valor_transacao, e.data_lancamento, e.data_transacao, e.numero_cartao"
			. "\n FROM extrato_cartoes as e"
			. "\n LEFT JOIN banco as b ON b.id = e.id_banco"
			. "\n WHERE e.numero_cartao like '%" . $numero_cartao . "%' "
			. "\n ORDER BY e.data_lancamento";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitaOutros()
	 *
	 * @return
	 */
	public function getReceitaOutros($mes_ano)
	{
		$tipo_pgto = $this->obterListaPagamentosCategoria(1);
		$sql = "SELECT SUM(r.valor_pago) AS total "
			. "\n FROM receita as r "
			. "\n LEFT JOIN conta as c ON c.id = r.id_conta "
			. "\n WHERE r.inativo = 0 AND r.pago = 1 AND c.exibir = 1 AND c.dre = 1 AND r.tipo NOT IN ($tipo_pgto) AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' ";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Faturamento::getDREMes()
	 *
	 * @return
	 */
	public function getDREMes($mes_ano = 0, $id_empresa = 0)
	{
		$wempresa = ($id_empresa) ? "AND r.id_empresa = $id_empresa" : "";
		$sql = "SELECT c.id, c.id_pai, p.conta AS conta_pai, c.conta, SUM(r.valor_pago) as total "
			. "\n FROM conta AS c"
			. "\n LEFT JOIN conta AS p ON p.id = c.id_pai"
			. "\n LEFT JOIN receita AS r ON c.id = r.id_conta AND r.inativo = 0 "
			. "\n 	$wempresa "
			. "\n 	AND r.pago = 1 "
			. "\n 	AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' "
			. "\n WHERE  c.id_pai IS NOT NULL AND c.tipo = 'C' AND p.dre = 1 AND c.dre = 1 "
			. "\n GROUP BY c.id_pai , c.id "
			. "\n ORDER BY p.ordem, p.conta, c.ordem, c.conta ASC ";

		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getContasDRE()
	 *
	 * @return
	 */
	public function getContasDRE()
	{

		$sql = "SELECT c.id, c.id_pai, p.conta AS conta_pai, c.conta "
			. "\n FROM conta AS c"
			. "\n LEFT JOIN conta AS p ON p.id = c.id_pai"
			. "\n WHERE c.exibir = 1 AND c.id_pai IS NOT NULL AND c.tipo = 'C' AND p.dre = 1 AND c.dre = 1 "
			. "\n ORDER BY p.ordem, c.ordem, p.conta , c.conta ASC ";
		//echo $sql;
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitasDAS()
	 * MODELO: 1 - SERVICO
	 * MODELO: 2 - PRODUTO
	 * MODELO: 3 - FATURA
	 * OPERACAO: 1 - COMPRA (ENTRADA)
	 * OPERACAO: 2 - VENDA (SAIDA)
	 *
	 * @return
	 */
	public function backup_getReceitasDAS($id_empresa, $mes_ano)
	{
		$sql = "SELECT r.id, r.descricao, r.id_nota, r.tipo, r.id_cadastro, r.duplicata, r.id_empresa, r.valor, r.valor_pago, r.data_pagamento, r.data_fiscal, r.data_recebido, t.tipo as pagamento, DATE_FORMAT(r.data_fiscal, '%Y%m%d') as controle, e.nome as empresa, ca.nome as cadastro, ca.cidade, n.numero_nota, n.valor_nota as valor_total, n.valor_nota, n.data_emissao, n.iss_retido, n.valor_st, n.valor_outro, n.modelo, r.enviado "
			. "\n FROM receita as r"
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = r.tipo"
			. "\n LEFT JOIN empresa as e ON e.id = r.id_empresa"
			. "\n LEFT JOIN cadastro as ca ON ca.id = r.id_cadastro"
			. "\n LEFT JOIN nota_fiscal as n ON n.id = r.id_nota"
			. "\n WHERE r.inativo = 0 AND r.fiscal = 1 AND r.pago = 1 AND r.id_nota > 0 AND r.id_empresa  = $id_empresa"
			. "\n AND DATE_FORMAT(r.data_fiscal, '%m/%Y') = '$mes_ano' "
			. "\n ORDER BY r.data_fiscal";
		//echo $sql;
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitasDAS()
	 * MODELO: 1 - SERVICO
	 * MODELO: 2 - PRODUTO
	 * MODELO: 3 - FATURA
	 * OPERACAO: 1 - COMPRA (ENTRADA)
	 * OPERACAO: 2 - VENDA (SAIDA)
	 *
	 * @return
	 */
	public function getReceitasDAS($id_empresa, $mes_ano)
	{
		$sql = "SELECT r.id, r.descricao, r.id_nota, r.id_venda,r.tipo, r.id_cadastro, r.duplicata, r.id_empresa, r.valor, r.valor_pago, r.data_pagamento, r.data_fiscal, r.data_recebido, t.tipo as pagamento, DATE_FORMAT(r.data_fiscal, '%Y%m%d') as controle, e.nome as empresa, ca.nome as cadastro, ca.cidade, n.numero_nota, n.valor_nota as valor_total, n.valor_nota, n.data_emissao, n.iss_retido as valor_iss_retido, n.valor_st, n.valor_outro, n.modelo, r.enviado "
			. "\n FROM receita as r"
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = r.tipo"
			. "\n LEFT JOIN empresa as e ON e.id = r.id_empresa"
			. "\n LEFT JOIN cadastro as ca ON ca.id = r.id_cadastro"
			. "\n LEFT JOIN nota_fiscal as n ON n.id = r.id_nota"
			. "\n WHERE r.inativo = 0 AND r.fiscal = 1 AND r.pago = 1 AND r.id_nota > 0 "
			. "\n AND DATE_FORMAT(n.data_emissao, '%m/%Y') = '$mes_ano' "
			. "\n UNION"
			. "\n SELECT r.id, r.descricao, r.id_nota, r.id_venda, r.tipo, r.id_cadastro, r.duplicata, r.id_empresa, r.valor, r.valor_pago, r.data_pagamento, r.data_pagamento as data_fiscal, r.data_recebido, t.tipo as pagamento, DATE_FORMAT(r.data_fiscal, '%Y%m%d') as controle, e.nome as empresa, ca.nome as cadastro, ca.cidade, v.numero_nota, v.valor_pago as valor_total, v.valor_pago as valor_nota, v.data_emissao, 0 as valor_iss_retido, 0 as valor_st, 0 as valor_outro, 2 as modelo, r.enviado"
			. "\n FROM receita as r  "
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = r.tipo "
			. "\n LEFT JOIN empresa as e ON e.id = r.id_empresa "
			. "\n LEFT JOIN cadastro as ca ON ca.id = r.id_cadastro "
			. "\n LEFT JOIN vendas as v ON v.id = r.id_venda "
			. "\n WHERE r.inativo = 0 AND r.fiscal = 1 AND r.pago = 1 AND r.id_venda > 0 AND v.status_enotas='Autorizada'"
			. "\n AND DATE_FORMAT(v.data_emissao, '%m/%Y') = '$mes_ano' "
			. "\n UNION"
			. "\n SELECT cf.id, 'DINHEIRO' as descricao, 0 as id_nota, cf.id_venda, cf.tipo, cf.id_cadastro, '' as duplicata, cf.id_empresa, cf.valor_total_venda  as valor, cf.valor_pago, cf.data_pagamento, v.data_emissao as data_fiscal, cf.data_pagamento as data_recebido, t.tipo as pagamento, DATE_FORMAT(v.data_emissao, '%Y%m%d') as controle, e.nome as empresa, ca.nome as cadastro, ca.cidade, v.numero_nota, v.valor_pago as valor_total, v.valor_pago as valor_nota, v.data_emissao, 0 as valor_iss_retido, 0 as valor_st, 0 as valor_outro, 2 as modelo, 0 as enviado "
			. "\n FROM cadastro_financeiro as cf "
			. "\n LEFT JOIN vendas as v ON v.id = cf.id_venda "
			. "\n LEFT JOIN empresa as e ON e.id = v.id_empresa "
			. "\n LEFT JOIN cadastro as ca ON ca.id = v.id_cadastro "
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = cf.tipo "
			. "\n WHERE cf.inativo = 0 AND v.fiscal = 1 AND t.id_categoria=1 AND cf.id_venda > 0 AND v.status_enotas='Autorizada' "
			. "\n AND DATE_FORMAT(v.data_emissao, '%m/%Y') = '$mes_ano' "
			. "\n ORDER BY data_fiscal";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}


	/**
	 * Faturamento::getReceitaNFTotal()
	 *
	 * @return
	 */
	public function getReceitaNFTotal($id_pagamento, $todos = false)
	{
		$wTipo = ($todos) ? " r.id_pagamento = $id_pagamento " : " r.id_nota = $id_pagamento";
		$sql = "SELECT COUNT(1) AS quant, SUM(r.valor) AS total "
			. "\n FROM receita as r"
			. "\n WHERE r.inativo = 0 AND $wTipo ";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitaNFCTotal()
	 *
	 * @return
	 */
	public function getReceitaNFCTotal($id_pagamento, $todos = false)
	{
		$wTipo = ($todos) ? " r.id_pagamento = $id_pagamento " : " r.id_venda = $id_pagamento";
		$sql = "SELECT COUNT(1) AS quant, SUM(r.valor) AS total "
			. "\n FROM receita as r"
			. "\n WHERE r.inativo = 0 AND $wTipo ";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitaNFParcela()
	 *
	 * @return
	 */
	public function getReceitaNFParcela($id_nota, $data_pagamento)
	{
		$sql = "SELECT COUNT(1) AS quant "
			. "\n FROM receita as r"
			. "\n WHERE r.inativo = 0 AND r.id_nota = '$id_nota' AND r.data_pagamento <= '$data_pagamento' ";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitaNFEParcela()
	 *
	 * @return
	 */
	public function getReceitaNFEParcela($id_venda, $data_pagamento)
	{
		$sql = "SELECT COUNT(1) AS quant "
			. "\n FROM receita as r"
			. "\n WHERE r.inativo = 0 AND r.id_venda = '$id_venda' AND r.data_pagamento <= '$data_pagamento' ";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitaNFTotalNumero()
	 *
	 * @return
	 */
	public function getReceitaNFTotalNumero($id_pagamento)
	{
		$sql = "SELECT COUNT(1) AS quant, SUM(r.valor) AS total "
			. "\n FROM receita as r"
			. "\n WHERE r.inativo = 0 AND r.id_pagamento = $id_pagamento ";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitaNFPagasNumero()
	 *
	 * @return
	 */
	public function getReceitaNFPagasNumero($numero_nota)
	{
		$sql = "SELECT COUNT(1) AS quant, SUM(r.valor) AS total "
			. "\n FROM receita as r"
			. "\n WHERE r.inativo = 0 AND r.pago = 1 AND r.descricao like '%$numero_nota%' ";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getReceitaNF()
	 *
	 * @return
	 */
	public function getReceitaNF($id_nota)
	{
		$sql = "SELECT id, id_nota, data_fiscal, data_recebido, data_pagamento, valor, valor_pago "
			. "\n FROM receita as r"
			. "\n WHERE r.inativo = 0 AND r.id_nota = '$id_nota' "
			. "\n ORDER BY data_pagamento ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getBancoBoletos()
	 *
	 * @param
	 * @return
	 */
	public function getBancoBoletos($id, $todos = false)
	{
		$where = ($todos) ? " AND r.id_nota = $id" : " AND r.id = $id";
		$sql = "SELECT b.codigo"
			. "\n FROM receita AS r"
			. "\n LEFT JOIN banco AS b ON b.id = r.id_banco"
			. "\n WHERE r.inativo = 0 AND r.id_banco > 0 $where ";

		$row = self::$db->first($sql);

		return ($row) ? $row->codigo : 0;
	}

	/**
	 * Faturamento::validaBancoBoletos()
	 *
	 * @param
	 * @return
	 */
	public function validaBancoBoletos($id, $todos = false, $banco = '-1')
	{
		$where = ($todos) ? " AND r.id_nota = $id" : " AND r.id = $id";
		$sql = "SELECT r.id, r.id_banco"
			. "\n FROM receita AS r"
			. "\n LEFT JOIN banco AS b ON b.id = r.id_banco"
			. "\n WHERE r.inativo = 0 AND (r.id_banco = 0 OR b.codigo <> '$banco') $where ";

		$row = self::$db->first($sql);

		return ($row) ? $row->id : 0;
	}

	/**
	 * Faturamento::getGerarBoletos()
	 *
	 * @param
	 * @return
	 */
	public function getGerarBoletos($id, $todos = false, $nota = false)
	{
		$where = "";
		if ($nota)
			$where = " r.id_nota = $nota ";
		else
			if ($todos)
				$where = " r.id_pagamento = $id ";
			else
				$where = " r.id = $id ";

		$sql = "SELECT r.id, r.id_cadastro, r.id_empresa, r.id_banco, r.id_pagamento, r.id_nota, r.valor, r.parcela, r.data_pagamento as data_vencimento, r.data_recebido as data_pagamento, r.pago, c.nome, c.razao_social, c.cpf_cnpj, c.endereco, c.numero, c.complemento, c.bairro, c.cidade, c.estado, c.cep"
			. "\n FROM receita as r"
			. "\n LEFT JOIN cadastro as c on c.id = r.id_cadastro"
			. "\n WHERE r.inativo = 0 AND $where ";

		$row = self::$db->fetch_all($sql);

		$condicao = ($todos) ? "enviado = 0 AND id_pagamento = $id " : "enviado = 0 AND id = $id ";

		$data = array(
			'enviado' => '1',
			'remessa' => '9',
		);
		self::$db->update("receita", $data, $condicao);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getBancoRemessa()
	 *
	 * @param
	 * @return
	 */
	public function getBancoRemessa()
	{
		$sql = "SELECT DISTINCT b.id, b.banco"
			. "\n FROM receita AS r"
			. "\n LEFT JOIN banco AS b ON b.id = r.id_banco"
			. "\n WHERE r.inativo = 0 AND r.remessa = 9 AND r.id_banco > 0 ";

		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getRemessa()
	 *
	 * @return
	 */
	public function getRemessa($id_banco = 0)
	{
		$sql = "SELECT r.id, r.id_cadastro, r.id_empresa, r.id_pagamento, r.id_nota, r.valor, r.parcela, r.data_pagamento as data_vencimento, r.data_recebido as data_pagamento, r.pago, c.nome, c.razao_social, c.cpf_cnpj, c.endereco, c.numero, c.complemento, c.bairro, c.cidade, c.estado, c.cep, c.cpf_cnpj, c.tipo"
			. "\n FROM receita as r"
			. "\n LEFT JOIN cadastro as c on c.id = r.id_cadastro"
			. "\n WHERE r.remessa = 9 AND r.inativo = 0 AND r.data_pagamento > NOW() ";

		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::isRemessa()
	 *
	 * @return
	 */
	public function isRemessa($id_banco = '')
	{
		if ($id_banco) {
			$sql = "SELECT r.id "
				. "\n FROM receita as r"
				. "\n WHERE r.id_banco = '$id_banco' AND r.remessa = 9 AND r.inativo = 0 ";
			$row = self::$db->first($sql);
		} else {
			$row = false;
		}

		return ($row) ? $row->id : 0;
	}

	/**
	 * Faturamento::getTodosCaixa()
	 *
	 * @return
	 */
	public function getTodosCaixa()
	{
		$tipo_pgto = $this->obterListaPagamentosCategoria(1);
		$sql = "SELECT f.id_caixa, SUM(f.valor_pago) AS valor_pago "
			. "\n FROM cadastro_financeiro as f "
			. "\n WHERE f.id_caixa > 0 AND f.inativo = 0 AND f.tipo IN ($tipo_pgto) "
			. "\n GROUP BY f.id_caixa "
			. "\n ORDER BY f.id_caixa DESC";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::abrirCaixa()
	 *
	 * @return
	 */
	public function abrirCaixa($id_abrir = 0)
	{
		$id_abrir = ($id_abrir) ? $id_abrir : session('uid');
		$id_caixa = $this->verificaCaixa($id_abrir);
		$id_banco = sanitize($_POST['id_banco']);
		$valor = converteMoeda($_POST['valor']);

		if (empty($_POST['id_banco']) and $valor > 0)
			Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');

		if ($id_caixa > 0)
			Filter::$msgs['id_caixa'] = lang('CAIXA_ABRIR_ERRO');

		if (!empty($_POST['id_banco'])) {
			$saldo = $this->getSaldoTotal(false, $id_banco);
			if (($valor > 0) and ($valor > $saldo))
				Filter::$msgs['valor'] = lang('MSG_ERRO_SALDO');
		}
		if (empty(Filter::$msgs)) {

			$data = array(
				'id_abrir' => $id_abrir,
				'data_abrir' => "NOW()",
				'status' => '1',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			$id_caixa = self::$db->insert("caixa", $data);

			if ($valor > 0) {
				$data_valor = array(
					'id_caixa' => $id_caixa,
					'tipo' => getvalue("id", "tipo_pagamento", "id_categoria=1 AND inativo=0"),
					'valor_pago' => $valor,
					'data_pagamento' => "NOW()",
					'data_vencimento' => "NOW()",
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_pagamento = self::$db->insert("cadastro_financeiro", $data_valor);

				$obs = 'ABERTURA DO CAIXA [' . $id_caixa . '] NO DIA ' . date("d/m/Y");

				$data_despesa = array(
					'id_banco' => $id_banco,
					'id_conta' => '27',
					'descricao' => $obs,
					'valor' => $valor,
					'valor_pago' => $valor,
					'data_pagamento' => "NOW()",
					'data_vencimento' => "NOW()",
					'pago' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_despesa = self::$db->insert("despesa", $data_despesa);
			}

			if (self::$db->affected()) {
				print Filter::msgOk(lang('CAIXA_ABRIR_OK'), "index.php?do=caixa&acao=listar");
			} else {
				print Filter::msgAlert(lang('NAOPROCESSADO'));
			}
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::adicionarValorAoCaixa()
	 *
	 * @return
	 */
	public function adicionarValorAoCaixa($id_abrir = 0)
	{
		$id_abrir = ($id_abrir) ? $id_abrir : session('uid');
		$id_caixa = $this->verificaCaixa($id_abrir);
		$id_banco = sanitize($_POST['id_banco']);
		$valor = converteMoeda($_POST['valor']);

		if (empty($_POST['valor']) or $valor <= 0)
			Filter::$msgs['valor'] = lang('CAIXA_ADICIONAR_VALOR_ERRO');

		if (empty($_POST['id_banco']) and $valor > 0)
			Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');

		if (empty($_POST['tipo_pagamento']))
			Filter::$msgs['tipo_pagamento'] = lang('MSG_ERRO_TIPO_PAGAMENTO');

		if ($id_caixa == 0)
			Filter::$msgs['id_caixa'] = lang('CAIXA_ADICIONAR_ERRO');

		if (!empty($_POST['id_banco'])) {
			$saldo = $this->getSaldoTotal(false, $id_banco);
			if (($valor > 0) and ($valor > $saldo))
				Filter::$msgs['valor'] = lang('MSG_ERRO_SALDO');
		}

		if (empty(Filter::$msgs)) {

			if ($valor > 0) {
				$data_valor = array(
					'id_caixa' => $id_caixa,
					'id_banco' => $id_banco,
					'tipo' => sanitize($_POST['tipo_pagamento']),
					'valor_pago' => $valor,
					'data_pagamento' => "NOW()",
					'data_vencimento' => "NOW()",
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_pagamento = self::$db->insert("cadastro_financeiro", $data_valor);

				$obs = 'ADICIONADO DINHEIRO AO CAIXA [' . $id_caixa . '] NO DIA ' . date("d/m/Y");

				$data_despesa = array(
					'id_banco' => $id_banco,
					'id_conta' => '27',
					'descricao' => $obs,
					'valor' => $valor,
					'valor_pago' => $valor,
					'data_pagamento' => "NOW()",
					'data_vencimento' => "NOW()",
					'pago' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_despesa = self::$db->insert("despesa", $data_despesa);
			}

			if (self::$db->affected()) {
				print Filter::msgOk(lang('CAIXA_ADICIONAR_OK'), "index.php?do=caixa&acao=listar");
			} else {
				print Filter::msgAlert(lang('NAOPROCESSADO'));
			}
		} else
			print Filter::msgStatus();
	}

	/**
	 * Faturamento::verificaCaixa()
	 *
	 * @return
	 */
	public function verificaCaixa($id_usuario)
	{
		$sql = "SELECT id, data_abrir "
			. "\n FROM caixa "
			. "\n WHERE status = 1 "
			. "\n AND id_abrir = '$id_usuario' ";
		$row = self::$db->first($sql);
		return ($row) ? $row->id : 0;
	}

	/**
	 * Faturamento::getCaixas()
	 *
	 * @return
	 */
	public function getCaixas($data, $ismaster = false, $id_usuario = 0)
	{
		$where = ($ismaster) ? "" : "AND c.id_abrir = '$id_usuario'";
		$sql = "SELECT u.nome, c.* "
			. "\n FROM caixa as c"
			. "\n LEFT JOIN usuario as u ON u.id = c.id_abrir "
			. "\n WHERE DATE_FORMAT(c.data_abrir, '%d/%m/%Y') = '$data'  $where"
			. "\n ORDER BY c.id ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getCaixasAberto()
	 *
	 * @return
	 */
	public function getCaixasAberto($ismaster = false, $id_usuario = 0)
	{
		$where = ($ismaster) ? "" : "AND c.id_abrir = '$id_usuario'";
		$sql = "SELECT u.nome, c.* "
			. "\n FROM caixa as c"
			. "\n LEFT JOIN usuario as u ON u.id = c.id_abrir "
			. "\n WHERE status in (1,2) $where"
			. "\n ORDER BY c.id DESC";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getQuantidadeCaixasAberto()
	 *
	 * @return
	 */
	public function getQuantidadeCaixasAberto()
	{
		$sql = "SELECT count(id) as caixas FROM caixa WHERE status in (1,2)";
		$row = self::$db->first($sql);
		return ($row) ? $row->caixas : 0;
	}

	/**
	 * Faturamento::getCaixasAbertoData()
	 *
	 * @return
	 */
	public function getCaixasAbertoData($ismaster = false, $id_usuario = 0, $data_ini, $data_fim)
	{
		$data_ini = dataMySQL($data_ini);
		$data_fim = dataMySQL($data_fim);

		$where = ($ismaster) ? "" : "AND c.id_abrir = '$id_usuario'";
		$sql = "SELECT u.nome, c.* "
			. "\n FROM caixa as c"
			. "\n LEFT JOIN usuario as u ON u.id = c.id_abrir "
			. "\n WHERE status in (1,2) $where AND c.data_abrir BETWEEN STR_TO_DATE('$data_ini 00:00:00','%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('$data_fim 23:59:59','%Y-%m-%d %H:%i:%s')"
			. "\n ORDER BY c.id DESC";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getDetalhesCaixa()
	 *
	 * @return
	 */
	public function getDetalhesCaixa($id_caixa)
	{
		$sql = "SELECT a.usuario as responsavel, a.nome as aberto, f.nome as fechado, v.nome as validado, c.* "
			. "\n FROM caixa as c"
			. "\n LEFT JOIN usuario as a ON a.id = c.id_abrir "
			. "\n LEFT JOIN usuario as f ON f.id = c.id_fechar "
			. "\n LEFT JOIN usuario as v ON v.id = c.id_validar "
			. "\n WHERE c.id = '$id_caixa'";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * cadastro::getVendasCaixaTipo()
	 *
	 * @return
	 */
	public function getVendasCaixaTipo($id_caixa)
	{
		$sql = "SELECT v.id, c.nome as cadastro, v.id_venda, v.id_caixa, v.id_produto, v.id_cadastro, v.valor_total, v.valor_despesa_acessoria, v.valor_desconto, v.valor, v.quantidade, v.usuario, v.data, p.nome as produto, p.codigo, v.valor_custo "
			. "\n FROM cadastro_vendas as v"
			. "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
			. "\n LEFT JOIN produto as p ON p.id = v.id_produto "
			. "\n WHERE v.pago > 0 AND v.inativo = 0 "
			. "\n AND v.id_caixa = '$id_caixa' "
			. "\n ORDER BY v.id ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getVendasCaixa()
	 *
	 * @return
	 */
	public function getVendasCaixa($id_caixa)
	{
		$sql = "SELECT v.id, v.crediario, c.nome as cadastro, v.id_cadastro, v.valor_total, v.valor_desconto, v.valor_pago, v.troco, v.usuario, v.inativo "
			. "\n FROM vendas as v"
			. "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
			. "\n WHERE v.id_caixa = '$id_caixa' AND v.orcamento <> 1"
			. "\n ORDER BY v.id ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getCrediariosCaixa()
	 * filipe
	 * @return
	 */
	public function getCrediariosCaixa($id_caixa)
	{
		$sql = "SELECT cc.id, cc.id_venda, c.nome as cadastro, cc.id_cadastro, cc.valor_venda, cc.valor, cc.valor_pago, cc.usuario, cc.inativo "
			. "\n FROM cadastro_crediario AS cc "
			. "\n LEFT JOIN cadastro AS c ON c.id = cc.id_cadastro "
			. "\n WHERE cc.id IN (SELECT id_cadastro_crediario FROM cadastro_crediario_pagamentos WHERE id_caixa = '$id_caixa') "
			. "\n ORDER BY cc.id ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Cadastro::getPagamentosCrediario()
	 * filipe
	 * @return
	 */
	public function getPagamentosCrediario($id_cadastro_crediario, $id_caixa)
	{
		$sql = "SELECT ccp.*, t.tipo as pagamento, t.avista "
			. "\n FROM cadastro_crediario_pagamentos as ccp "
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = ccp.tipo_pagamento "
			. "\n WHERE ccp.id_cadastro_crediario = $id_cadastro_crediario AND ccp.id_caixa='$id_caixa' AND ccp.inativo = 0 "
			. "\n ORDER BY ccp.tipo_pagamento ASC";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getMovimentoCaixa()
	 *
	 * @return
	 */
	public function getMovimentoCaixa($id_caixa)
	{
		$sql = "SELECT f.id, f.inativo, t.tipo AS pagamento, f.id_cadastro, c.nome AS cadastro, f.id_venda, f.id_banco, b.banco, f.tipo, f.valor_total_venda, CASE WHEN f.inativo = 1 THEN 0 ELSE f.valor_pago END AS valor_pago, f.numero_cartao, f.parcelas_cartao, f.banco_cheque, f.numero_cheque, '0' AS detalhe "
			. "\n FROM cadastro_financeiro as f "
			. "\n LEFT JOIN cadastro as c ON c.id = f.id_cadastro "
			. "\n LEFT JOIN banco as b ON b.id = f.id_banco "
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo "
			. "\n WHERE f.id_caixa = $id_caixa AND f.tipo<>0 AND f.pago<>2"
			. "\n UNION "
			. "\n SELECT f.id, f.inativo, t.tipo as pagamento, f.id_cadastro, c.nome as cadastro, f.id_venda, '0' as id_banco, '' as banco,
				ccp.tipo_pagamento as tipo, f.valor as valor_total_venda, ccp.valor_pago, '' as numero_cartao, '' as parcelas_cartao, '' as banco_cheque, '' as numero_cheque, 'Pagamento de crediário' as detalhe "
			. "\n FROM cadastro_crediario as f "
			. "\n LEFT JOIN cadastro as c ON c.id = f.id_cadastro "
			. "\n LEFT JOIN cadastro_crediario_pagamentos as ccp ON ccp.id_cadastro_crediario = f.id "
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = ccp.tipo_pagamento "
			. "\n WHERE ccp.id_caixa = $id_caixa AND ccp.inativo=0";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getFinanceiroCaixa()
	 *
	 * @return
	 */
	public function getFinanceiroCaixa($id_caixa)
	{
		$sql = "SELECT t.id, t.tipo AS pagamento, t.id_categoria, SUM(f.valor_pago) AS valor_pago, 'Vendas' AS detalhes"
			. "\n FROM cadastro_financeiro AS f"
			. "\n LEFT JOIN tipo_pagamento AS t ON t.id = f.tipo"
			. "\n WHERE f.id_caixa = $id_caixa AND f.inativo = 0 AND f.tipo > 0"
			. "\n GROUP BY t.id"
			. "\n UNION "
			. "\n SELECT t.id AS id, t.tipo as pagamento, t.id_categoria, SUM(c.valor_pago) AS valor_pago, 'Pagamento de crediário' AS detalhes"
			. "\n FROM cadastro_crediario_pagamentos AS c"
			. "\n LEFT JOIN tipo_pagamento AS t ON t.id = c.tipo_pagamento"
			. "\n WHERE c.id_caixa = $id_caixa AND c.inativo = 0"
			. "\n GROUP BY t.id";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::getCaixaValor()
	 *
	 * @return
	 */
	public function getCaixaValor($id_caixa)
	{
		$total = 0;
		$sql = "SELECT SUM(f.valor_pago) AS valor_pago "
			. "\n FROM cadastro_financeiro as f "
			. "\n WHERE f.id_caixa = $id_caixa AND f.inativo = 0 AND f.tipo>0";
		$row = self::$db->first($sql);
		$total += ($row) ? $row->valor_pago : 0;
		$sql = "SELECT SUM(ccp.valor_pago) AS valor_pago "
			. "\n FROM cadastro_crediario_pagamentos as ccp "
			. "\n WHERE ccp.id_caixa = $id_caixa AND ccp.inativo = 0";
		$row = self::$db->first($sql);
		$total += ($row) ? $row->valor_pago : 0;
		return $total;
	}

	/**
	 * Faturamento::getCaixaDinheiro()
	 *
	 * @return
	 */
	public function getCaixaDinheiro($id_caixa)
	{
		$sql = "SELECT SUM(f.valor_pago) AS valor_pago "
			. "\n FROM cadastro_financeiro as f "
			. "\n LEFT JOIN tipo_pagamento as tp ON tp.id = f.tipo"
			. "\n WHERE f.id_caixa = $id_caixa AND f.inativo = 0 AND tp.id_categoria = 1 AND f.tipo>0";
		$row = self::$db->first($sql);

		$sql_ficha = "SELECT SUM(c.valor_pago) AS valor_pago"
			. "\n FROM cadastro_crediario_pagamentos AS c "
			. "\n LEFT JOIN tipo_pagamento as tp ON tp.id = c.tipo_pagamento"
			. "\n where c.id_caixa = $id_caixa AND c.inativo = 0 AND tp.id_categoria = 1";
		$row_ficha = self::$db->first($sql_ficha);

		$sql_crediario = "SELECT SUM(r.valor_pago) AS valor_pago"
			. "\n FROM receita AS r "
			. "\n LEFT JOIN tipo_pagamento as tp ON tp.id = r.tipo"
			. "\n WHERE r.id_caixa=$id_caixa AND r.promissoria=1 AND r.pago=1 AND tp.id_categoria = 1";
		$row_crediario = self::$db->first($sql_crediario);

		$v1 = ($row->valor_pago) ? floatval($row->valor_pago) : 0;
		$v2 = ($row_ficha->valor_pago) ? floatval($row_ficha->valor_pago) : 0;
		$v3 = ($row_crediario->valor_pago) ? floatval($row_crediario->valor_pago) : 0;

		return ($v1 + $v2 + $v3);
	}

	/**
	 * Faturamento::getCaixaCheque()
	 *
	 * @return
	 */
	public function getCaixaCheque($id_caixa)
	{
		$tipo_pgto = $this->obterListaPagamentosCategoria(2);
		$sql = "SELECT SUM(f.valor_pago) AS valor_pago "
			. "\n FROM cadastro_financeiro as f "
			. "\n WHERE f.id_caixa = $id_caixa AND f.inativo = 0 AND f.tipo IN ($tipo_pgto) ";
		$row = self::$db->first($sql);

		return ($row) ? $row->valor_pago : 0;
	}

	/**
	 * Faturamento::getCaixaAbertura()
	 *
	 * @return
	 */
	public function getCaixaAbertura($id_caixa)
	{
		$tipo_pgto = $this->obterListaPagamentosCategoria(1);
		$sql = "SELECT SUM(f.valor_pago) AS valor_pago "
			. "\n FROM cadastro_financeiro as f "
			. "\n WHERE f.id_caixa = $id_caixa AND f.inativo = 0 AND f.tipo IN ($tipo_pgto) AND f.id_venda = 0 AND f.id_cadastro = 0 ";
		$row = self::$db->first($sql);

		return ($row) ? $row->valor_pago : 0;
	}

	/**
	 * Faturamento::getConta_BB()
	 *
	 * @param
	 * @return
	 */
	public function getConta_BB($id, $todos = false)
	{
		$where = ($todos) ? " r.id_nota = $id " : " r.id = $id ";
		$sql = "SELECT r.id, r.id_banco, b.conta"
			. "\n FROM receita as r"
			. "\n LEFT JOIN banco as b on b.id = r.id_banco"
			. "\n WHERE r.inativo = 0 AND $where ";

		$row = self::$db->first($sql);

		return ($row) ? $row->conta : 0;
	}

	/**
	 * Contrato::processarCondicaoPagamento()
	 *
	 * @return
	 */
	public function processarCondicaoPagamento()
	{
		if (empty($_POST['condicao']))
			Filter::$msgs['condicao'] = lang('MSG_ERRO_CONDICAO');

		if (empty(Filter::$msgs)) {
			$data = array(
				'condicao' => sanitize(post('condicao')),
				'parcelas' => sanitize(post('parcelas')),
				'dias' => sanitize(post('dias')),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			(Filter::$id) ? self::$db->update("condicao_pagamento", $data, "id=" . Filter::$id) : self::$db->insert("condicao_pagamento", $data);

			$message = (Filter::$id) ? lang('CONDICAO_ALTERADO_OK') : lang('CONDICAO_ADICIONADO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=condicaopagamento&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Contrato::getCondicaoPagamento()
	 *
	 * @return
	 */
	public function getCondicaoPagamento()
	{
		$sql = "SELECT c.id, c.condicao, c.dias, c.parcelas, c.usuario, c.data "
			. "\n FROM condicao_pagamento as c"
			. "\n WHERE c.inativo = 0 "
			. "\n ORDER BY c.condicao ASC ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::ObterVendasVendedorPeriodo($id_vendedor, $data_ini, $data_fim)
	 *
	 * @return
	 */
	public function ObterVendasVendedorPeriodo($id_vendedor, $data_ini, $data_fim)
	{
		$sql = "SELECT v.* , vse.status, vse.cor"
			. "\n FROM vendas as v"
			. "\n LEFT JOIN  vendas_status_entrega as vse ON vse.id=v.status_entrega"
			. "\n WHERE v.id_vendedor = $id_vendedor AND v.data_venda BETWEEN STR_TO_DATE('$data_ini 00:00:00','%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('$data_fim 23:59:59','%Y-%m-%d %H:%i:%s')";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento::ObterTodosProdutosVendaVendedor($id_venda)
	 *
	 * @return
	 */
	public function ObterTodosProdutosVendaVendedor($id_venda)
	{
		$sql = "SELECT cv.*, p.nome "
			. "\n FROM cadastro_vendas AS cv "
			. "\n LEFT JOIN produto AS p ON p.id = cv.id_produto "
			. "\n WHERE cv.id_venda = $id_venda AND cv.inativo = 0 ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Faturamento:: ObterPagamentosPorCategoria($categoria)
	 *
	 * @return
	 */
	public function ObterPagamentosPorCategoria($categoria)
	{
		$sql = "SELECT id, id_banco, tipo, id_categoria "
		  . "\n FROM tipo_pagamento "
		  . "\n WHERE inativo=0 AND id_categoria=$categoria ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

}
?>