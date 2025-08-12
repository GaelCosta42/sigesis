<?php
/**
 * Classe Produto
 *
 */
use function PHPSTORM_META\type;
if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe não é permitido.');

class Produto
{
	const uTable = "produto";
	private static $db;

	/**
	 * Produto::__construct()
	 *
	 * @return
	 */
	function __construct()
	{
		self::$db = Registry::get("Database");
	}

	/**
	 * Produto::getAtributos()
	 *
	 * @return
	 */
	public function getAtributos()
	{
		$sql = "SELECT id, atributo, exibir_romaneio FROM atributo WHERE inativo = 0 ORDER BY atributo";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarAtributo()
	 *
	 * @return
	 */
	public function processarAtributo()
	{
		if (empty($_POST['nome']))
			Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');

		if (empty(Filter::$msgs)) {

			$data = array(
				'atributo' => sanitize($_POST['nome']),
				'exibir_romaneio' => (post('exibir_romaneio')) ? sanitize(post('exibir_romaneio')) : 0,
			);

			(Filter::$id) ? self::$db->update("atributo", $data, "id=" . Filter::$id) : self::$db->insert("atributo", $data);
			$message = (Filter::$id) ? lang('PRODUTO_ATRIBUTO_AlTERADO_OK') : lang('PRODUTO_ATRIBUTO_ADICIONADO_OK');

			if (self::$db->affected()) {

				Filter::msgOk($message, "index.php?do=atributo&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getCategorias()
	 *
	 * @return
	 */
	public function getCategorias()
	{
		$sql = "SELECT id, categoria FROM categoria WHERE inativo = 0 ORDER BY categoria";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarCategoria()
	 *
	 * @return
	 */
	public function processarCategoria()
	{
		if (empty($_POST['nome']))
			Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');

		if (empty(Filter::$msgs)) {

			$data = array(
				'categoria' => sanitize($_POST['nome'])
			);

			(Filter::$id) ? self::$db->update("categoria", $data, "id=" . Filter::$id) : self::$db->insert("categoria", $data);
			$message = (Filter::$id) ? lang('CATEGORIA_ALTERADO_OK') : lang('CATEGORIA_ADICIONADO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=categoria&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getGrupos()
	 *
	 * @return
	 */
	public function getGrupos()
	{
		$sql = "SELECT id, grupo FROM grupo WHERE inativo = 0 ORDER BY grupo";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarGrupo()
	 *
	 * @return
	 */
	public function processarGrupo()
	{
		if (empty($_POST['nome']))
			Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');

		if (empty(Filter::$msgs)) {
			$data = array(
				'grupo' => sanitize($_POST['nome'])
			);

			(Filter::$id) ? self::$db->update("grupo", $data, "id=" . Filter::$id) : self::$db->insert("grupo", $data);
			$message = (Filter::$id) ? lang('GRUPO_ALTERADO_OK') : lang('GRUPO_ADICIONADO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=grupo&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getFabricantees()
	 *
	 * @return
	 */
	public function getFabricantes()
	{
		$sql = "SELECT id, fabricante, exibir_romaneio FROM fabricante WHERE inativo = 0 ORDER BY fabricante";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarFabricante()
	 *
	 * @return
	 */
	public function processarFabricante()
	{
		if (empty($_POST['nome']))
			Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');

		if (empty(Filter::$msgs)) {

			$data = array(
				'fabricante' => sanitize($_POST['nome'])
			);

			(Filter::$id) ? self::$db->update("fabricante", $data, "id=" . Filter::$id) : self::$db->insert("fabricante", $data);
			$message = (Filter::$id) ? lang('FABRICANTE_ALTERADO_OK') : lang('FABRICANTE_ADICIONADO_OK');

			if (self::$db->affected()) {

				Filter::msgOk($message, "index.php?do=fabricante&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getProdutos()
	 *
	 * @return
	 */
	public function getProdutos($id_grupo = false, $id_categoria = false, $id_fabricante = false)
	{
		$wgrupo = ($id_grupo) ? "AND p.id_grupo = " . $id_grupo : "";
		$wcategoria = ($id_categoria) ? "AND p.id_categoria = " . $id_categoria : "";
		$wfabricante = ($id_fabricante) ? "AND p.id_fabricante = " . $id_fabricante : "";

		$sql = "SELECT 	p.*,
						c.categoria,
						g.grupo,
						fa.fabricante,
						pp.prazo

				FROM 		produto 			AS p
				LEFT JOIN 	categoria 			AS c 	ON 	c.id = p.id_categoria
				LEFT JOIN 	grupo 				AS g 	ON 	g.id = p.id_grupo
				LEFT JOIN 	fabricante 			AS fa 	ON 	fa.id = p.id_fabricante
				LEFT JOIN 	produto_fornecedor 	AS pp 	ON 	pp.id_produto = p.id
														AND pp.principal = 1

				WHERE 	p.inativo = 0
				$wgrupo
				$wcategoria
				$wfabricante

				ORDER BY 	p.nome";

		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getCFOP()
	 *
	 * @return
	 */
	public function getCFOP($cfop)
	{
		$sql = "SELECT c.id, c.cfop, c.titulo, c.finalidade, c.devolucao, c.descricao, c.st, c.tributado, c.operacao_e_s, c.estado_d_f "
			. "\n FROM cfop as c"
			. "\n WHERE c.cfop = '$cfop' ";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getCFOP_Saida()
	 *
	 * @return
	 */
	public function getCFOP_Saida()
	{
		$sql = "SELECT c.id, c.cfop, c.descricao, c.st, c.tributado, c.operacao_e_s, c.estado_d_f "
			. "\n FROM cfop as c"
			. "\n WHERE c.operacao_e_s = 's' "
			. "\n ORDER BY c.cfop";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getCFOP_Todos()
	 *
	 * @return
	 */
	public function getCFOP_Todos()
	{
		$sql = "SELECT c.id, c.cfop, c.descricao, c.st, c.tributado, c.operacao_e_s, c.estado_d_f "
			. "\n FROM cfop as c"
			. "\n ORDER BY c.cfop";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getBuscarProdutos()
	 *
	 * @return
	 */
	public function getBuscarProdutos()
	{
		$row = false;
		$nome_produto = post('produto');
		$codigo = post('codigo');
		if ($nome_produto) {
			$prod = explode(" ", $nome_produto);
			$whereproduto = "";
			$count = count($prod);
			for ($i = 0; $i < $count; $i++) {
				$whereproduto .= " AND p.nome LIKE '%" . $prod[$i] . "%'";
			}
		}
		if ($codigo) {
			$sql = "SELECT p.*, c.categoria, g.grupo, fa.fabricante "
				. "\n FROM produto as p"
				. "\n LEFT JOIN categoria AS c ON c.id = p.id_categoria"
				. "\n LEFT JOIN grupo AS g ON g.id = p.id_grupo"
				. "\n LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante"
				. "\n WHERE p.codigo LIKE '%" . $codigo . "%' "
				. "\n ORDER BY p.nome ";
			$row = self::$db->fetch_all($sql);
		} elseif ($nome_produto) {
			$sql = "SELECT p.*, c.categoria, g.grupo, fa.fabricante "
				. "\n FROM produto as p"
				. "\n LEFT JOIN categoria AS c ON c.id = p.id_categoria"
				. "\n LEFT JOIN grupo AS g ON g.id = p.id_grupo"
				. "\n LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante"
				. "\n WHERE p.inativo < 2 $whereproduto "
				. "\n ORDER BY p.nome ";
			$row = self::$db->fetch_all($sql);
		}

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutosVenda()
	 *
	 * @return
	 */
	public function getProdutosVenda()
	{
		$sql = "SELECT p.*, c.categoria, g.grupo, fa.fabricante "
			. "\n FROM produto as p"
			. "\n LEFT JOIN categoria AS c ON c.id = p.id_categoria"
			. "\n LEFT JOIN grupo AS g ON g.id = p.id_grupo"
			. "\n LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante"
			. "\n WHERE p.inativo = 0 AND p.grade = 1 "
			. "\n ORDER BY p.nome";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutosTabela()
	 *
	 * @return
	 */
	public function getProdutosTabela($id_tabela)
	{
		$sql = "SELECT p.id, p.nome, p.codigo, p.codigobarras, pt.id_tabela as id_tabela, pt.valor_venda, p.estoque"
			. "\n FROM produto as p"
			. "\n LEFT JOIN produto_tabela as pt on pt.id_produto = p.id"
			. "\n LEFT JOIN tabela_precos as tp on tp.id = pt.id_tabela"
			. "\n WHERE p.inativo = 0 AND p.grade = 1 AND tp.id = {$id_tabela}"
			. "\n ORDER BY p.nome";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutosVendaApp()
	 *
	 * @return
	 */
	public function getProdutosVendaApp($id_tabela = 0)
	{
		$sql = "SELECT p.*, c.categoria, g.grupo, fa.fabricante, t.valor_venda"
			. "\n FROM produto as p"
			. "\n LEFT JOIN categoria AS c ON c.id = p.id_categoria"
			. "\n LEFT JOIN grupo AS g ON g.id = p.id_grupo"
			. "\n LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante"
			. "\n LEFT JOIN produto_tabela as t ON t.id_produto = p.id "
			. "\n WHERE p.inativo = 0 AND p.grade = 1 AND t.id_tabela = $id_tabela"
			. "\n ORDER BY p.nome";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getListaProdutos()
	 *
	 * @return
	 */
	public function getListaProdutos($nome = "", $id_tabela = false, $limite = 30)
	{
		$wtabela = ($id_tabela) ? "AND t.id_tabela = " . $id_tabela : "";
		$sql = "SELECT t.id_tabela, t.id_produto, t.valor_venda, p.id, p.nome, p.ncm, p.codigo, p.valor_custo, p.estoque"
			. "\n FROM produto_tabela as t"
			. "\n LEFT JOIN produto AS p ON p.id = t.id_produto"
			. "\n WHERE p.inativo = 0 AND p.grade = 1 $wtabela AND p.id = t.id_produto AND p.nome LIKE '%$nome%'"
			. "\n ORDER BY p.nome LIMIT $limite ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutoID()
	 *
	 * @return
	 */
	public function getProdutoID($id = 0)
	{
		$sql = "SELECT t.*, p.nome, p.ncm, p.codigo, p.valor_custo"
			. "\n FROM produto_tabela as t"
			. "\n LEFT JOIN produto AS p ON p.id = t.id_produto"
			. "\n WHERE p.inativo = 0 AND p.id = t.id_produto AND p.id = $id";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getIPISaida()
	 *
	 * @return
	 */
	public function getIPISaida()
	{
		$sql = "SELECT * FROM produto_ipi_saida ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getUnidadeMedida()
	 *
	 * @return
	 */
	public function getUnidadeMedida()
	{
		$sql = "SELECT * FROM unidade_medida ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::reativar_produto()
	 *
	 * @return
	 */
	public function reativar_produto($id_produto)
	{
		$row_produto = Core::getRowById("produto", $id_produto);
		$codigobarras = $row_produto->codigobarras;
		$wCodigoBarras = (!empty($codigobarras) && $codigobarras!="") ? " AND codigobarras='$codigobarras'": "";
		$sql = "SELECT nome FROM produto WHERE codigobarras='$codigobarras' AND codigobarras<>'' AND codigobarras IS NOT NULL ";
		$row = self::$db->first($sql);
		if ($row)
			Filter::$msgs['codigobarras'] = str_replace("[PRODUTO]", $row_produto->nome, lang('MSG_ERRO_CODBARRAS_CADASTRADO'));

		if (empty(Filter::$msgs)) {

			$data = array(
				'inativo' => '0',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->update("produto", $data, "id=" . $id_produto);
			
			if (self::$db->affected()) {
				Filter::msgOk(lang('PRODUTO_REATIVAR_OK'), "index.php?do=produto&acao=editar&id=".$id_produto);
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			Filter::$msgs['nao_reativado'] = lang('PRODUTO_REATIVAR_NAO');
			print Filter::msgStatus();
	}

	/**
	 * Produto::processarProduto()
	 *
	 * @return
	 */
	public function processarProduto()
	{
		$codBarAuto = 0;
		if (!empty($_POST['codigobarrasautomatico'])) {
			$id_empresa = session('idempresa');
			$cnpj_empresa = getValue("cnpj", "empresa", "id=" . $id_empresa);
			$codBarAuto = gerarCodigoBarrasAutomatico($cnpj_empresa, self::$db);
		} else if (!empty($_POST['codigobarras'])) {
			$codigobarras = sanitize(post('codigobarras'));
			$where = (Filter::$id) ? "AND id <> " . Filter::$id : "";
			$sql = "SELECT nome FROM produto WHERE inativo = 0 AND codigobarras = '$codigobarras' $where ";
			$row = self::$db->first($sql);
			if ($row)
				Filter::$msgs['codigobarras'] = str_replace("[PRODUTO]", $row->nome, lang('MSG_ERRO_CODBARRAS_CADASTRADO'));
		}

		$tipo_sistema = getValue('tipo_sistema', 'empresa', 'inativo=0');

		if (empty($_POST['nome']))
			Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');

		if (empty($_POST['ncm']) && $tipo_sistema <> 2)
			Filter::$msgs['ncm'] = lang('MSG_ERRO_NCM');

		if (empty($_POST['icms_cst']) && $tipo_sistema <> 2)
			Filter::$msgs['icms_cst'] = lang('MSG_ERRO_ICMS_CST');

		if (empty($_POST['cfop']) && $tipo_sistema <> 2)
			Filter::$msgs['cfop'] = lang('MSG_ERRO_CFOP');

		if (empty($_POST['unidade']))
			Filter::$msgs['unidade'] = lang('MSG_ERRO_UNIDADE');

		if (!empty($_POST['cod_anp']) && ($_POST['cod_anp'] == '210203001')) {
			if (empty($_POST['valor_partida']))
				Filter::$msgs['valor_partida'] = lang('MSG_ERRO_VALOR_PARTIDA');
		}

		if (empty(Filter::$msgs)) {
			$array_unidade = explode('#', sanitize(post('unidade')));

			$data = array(
				'nome' => sanitize(post('nome')),
				'link' => post('link'),
				'prazo_troca' => sanitize($_POST['prazo_troca']),
				'observacao' => sanitize(post('observacao')),
				'codigo' => sanitize(post('codigo')),
				'codigobarras' => (post('codigobarrasautomatico')) ? $codBarAuto : limparNumero(post('codigobarras')),
				'ncm' => limparNumero(post('ncm')),
				'cfop' => limparNumero(post('cfop')),
				'cfop_entrada' => limparNumero(post('cfop_entrada')),
				'cest' => limparNumero(post('cest')),
				'icms_cst' => limparNumero(post('icms_cst')),
				'icms_percentual' => (post('icms_percentual')) ? converteMoeda(post('icms_percentual')) : 0,
				'icms_percentual_st' => (post('icms_percentual_st')) ? converteMoeda(post('icms_percentual_st')) : 0,
				'mva_percentual' => (post('mva_percentual')) ? converteMoeda(post('mva_percentual')) : 0,
				'pis_cst' => limparNumero(post('pis_cst')),
				'pis_aliquota' => (post('pis_aliquota')) ? converteMoeda(post('pis_aliquota')) : 0,
				'cofins_cst' => limparNumero(post('cofins_cst')),
				'cofins_aliquota' => (post('cofins_aliquota')) ? converteMoeda(post('cofins_aliquota')) : 0,
				'ipi_cst' => (post('ipi_cst')) ? converteMoeda(post('ipi_cst')) : 0,
				'ipi_saida_codigo' => intval(limparNumero(post('ipi_saida_codigo'))),
				'anp' => sanitize(post('cod_anp')),
				'valor_partida' => (post('valor_partida')) ? converteMoeda(post('valor_partida')) : 0,
				'unidade' => $array_unidade[0],
				'unidade_tributavel' => sanitize(post('unidade_tributavel')),
				'descricao_unidade' => $array_unidade[1],
				'detalhamento' => sanitize(post('detalhamento')),
				'valor_custo' => (post('valor_custo')) ? converteMoeda(post('valor_custo')) : 0,
				'valor_avista' => (post('valor_avista')) ? converteMoeda(post('valor_avista')) : 0,
				'valor_mercado' => (post('valor_mercado')) ? converteMoeda(post('valor_mercado')) : 0,
				'valor_comissao' => (post('valor_comissao')) ? converteMoeda(post('valor_comissao')) : 0,
				'valor_despesas' => (post('valor_despesas')) ? converteMoeda(post('valor_despesas')) : 0,
				'valor_frete' => (post('valor_frete')) ? converteMoeda(post('valor_frete')) : 0,
				'id_grupo' => intval(sanitize(post('id_grupo'))),
				'id_categoria' => intval(sanitize(post('id_categoria'))),
				'id_fabricante' => intval(sanitize(post('id_fabricante'))),
				'grade' => (post('grade')) ? sanitize(post('grade')) : 0,
				'kit' => (post('kit')) ? sanitize(post('kit')) : 0,
				'peso' => (post('peso')) ? converteDecimal3(post('peso')) : 0,
				'estoque_minimo' => (post('estoquemin')) ? sanitize(post('estoquemin')) : 0,
				'ecommerce' => (!empty(post('ecommerce')) ? 1 : 0),
				'valida_estoque' => (post('valida_estoque')) ? sanitize(post('valida_estoque')) : 0,
				'monofasico' => (post('monofasico')) ? sanitize(post('monofasico')) : 0,
				'produto_balanca' => (post('produto_balanca')) ? sanitize(post('produto_balanca')) : 0,
				'inativo' => '0',
				'usuario_alteracao' => session('nomeusuario'),
				'data_alteracao' => "NOW()",
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			$atualizar_valor_produto = getValue('atualizar_valor_produto', 'empresa', 'inativo=0');

			if (!Filter::$id) {
				$estoque = (post('estoque')) ? converteMoeda(post('estoque')) : 1;
				$estoque_minimo = post('estoquemin') ?? 0;
				$data['estoque_minimo'] = $estoque_minimo;
				$data['ecommerce'] = (!empty(post('ecommerce')) ? 1 : 0);
				$data['estoque'] = $estoque;
				$id_produto = self::$db->insert(self::uTable, $data);

				$data_codigo_interno['codigo_interno'] = '#' . $id_produto;
				self::$db->update(self::uTable, $data_codigo_interno, "id=" . $id_produto);

				$data_estoque = array(
					'id_empresa' => session('idempresa'),
					'id_produto' => $id_produto,
					'quantidade' => $estoque,
					'tipo' => 1,
					'motivo' => 1,
					'observacao' => 'AJUSTE DE ESTOQUE INICIAL',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("produto_estoque", $data_estoque);
				$retorno_row = $this->getTabelaPrecos();
				$valor_venda = (post('valor_venda')) ? converteMoeda(post('valor_venda')) : 0;
				$valor_custo = (post('valor_custo')) ? converteMoeda(post('valor_custo')) : 0;
				$valor_custo = ($valor_custo > 0) ? $valor_custo : 1;
				$percentual = ($valor_venda / $valor_custo) - 1;
				$percentual = ($percentual > 0) ? $percentual : 1;
				if ($retorno_row) {
					foreach ($retorno_row as $exrow) {
						$valor_venda = (empty($_POST['valor_venda'])) ? ((1 + ($exrow->percentual / 100)) * $valor_custo) : converteMoeda(post('valor_venda'));
						$data_tabela = array(
							'id_tabela' => $exrow->id,
							'id_produto' => $id_produto,
							'percentual' => ($percentual == 1) ? $exrow->percentual : $percentual * 100,
							'valor_venda' => round($valor_venda, 2),
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->insert("produto_tabela", $data_tabela);
					}
				} else {
					$data_tabela_preco = array(
						'tabela' => 'PADRAO',
						'percentual' => $percentual * 100,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$id_tabela = self::$db->insert("tabela_precos", $data_tabela_preco);
					$data_tabela = array(
						'id_tabela' => $id_tabela,
						'id_produto' => $id_produto,
						'percentual' => $percentual * 100,
						'valor_venda' => converteMoeda(post('valor_venda')),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("produto_tabela", $data_tabela);
				}
			} else {
				$id_produto = Filter::$id;
				$novo_valor_custo = (post('valor_custo')) ? converteMoeda(post('valor_custo')) : 1;

				//Verificar se este produto é kit de outro.
				$checkKit = $this->verificaSeProdutoVirouKit($id_produto);
				if ($checkKit) {
					foreach ($checkKit as $key) {
						$id_produto_compoe_kit = $key->id_produto ? $key->id_produto : 0;
						if ($id_produto_compoe_kit) {
							if ($id_produto == $id_produto_compoe_kit) {

								$valor_custo_total = round($this->getValorCustoProdutokit($key->id_produto_kit), 2);
								$valor_custo_atualizado = $valor_custo_total - $key->valor_custo + $novo_valor_custo;

								$data_produto = array(
									'valor_custo' => $valor_custo_atualizado,
									'usuario_alteracao' => session('nomeusuario'),
									'data_alteracao' => "NOW()",
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->update(self::uTable, $data_produto, "id=" . $key->id_produto_kit);

								$retorno_row = $this->getTabelaPrecos();
								if ($retorno_row) {
									foreach ($retorno_row as $exrow) {
										$percentual = getValue("percentual", "produto_tabela", "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
										$novo_valor_venda = ((1 + ((float)$percentual / 100)) * $valor_custo_atualizado);
										$valor_venda_atual = getValue("valor_venda", "produto_tabela", "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
										$novo_percentual = ((float)$valor_venda_atual / $valor_custo_atualizado) - 1;

										if ($atualizar_valor_produto) {
											if ($novo_valor_venda > $valor_venda_atual) {
												$data_tabela_novo = array(
													'percentual' => $percentual,
													'valor_venda' => round($novo_valor_venda, 2),
													'usuario' => session('nomeusuario'),
													'data' => "NOW()"
												);
											} else {
												$data_tabela_novo = array(
													'percentual' => $novo_percentual * 100,
													'usuario' => session('nomeusuario'),
													'data' => "NOW()"
												);
											}
											self::$db->update("produto_tabela", $data_tabela_novo, "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
										} else {
											$data_tabela_novo = array(
												'percentual' => $novo_percentual * 100,
												'usuario' => session('nomeusuario'),
												'data' => "NOW()"
											);
											self::$db->update("produto_tabela", $data_tabela_novo, "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
										}
									}
								}
							}
						}
					}
				}

				$retorno_row = $this->getTabelaPrecos();
				if ($retorno_row) {
					foreach ($retorno_row as $exrow) {
						$percentual = getValue("percentual", "produto_tabela", "id_tabela=" . $exrow->id . " AND id_produto=" . $id_produto);
						$novo_valor_venda = ((1 + ((float)$percentual / 100)) * $novo_valor_custo);
						$valor_venda_atual = getValue("valor_venda", "produto_tabela", "id_tabela=" . $exrow->id . " AND id_produto=" . $id_produto);
						$novo_percentual = ((float)$valor_venda_atual / $novo_valor_custo) - 1;
						if ($atualizar_valor_produto) {
							if ($novo_valor_venda > $valor_venda_atual) {
								$data_tabela_novo = array(
									'percentual' => $percentual,
									'valor_venda' => round($novo_valor_venda, 2),
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
							} else {
								$data_tabela_novo = array(
									'percentual' => $novo_percentual * 100,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
							}
							self::$db->update("produto_tabela", $data_tabela_novo, "id_tabela=" . $exrow->id . " AND id_produto=" . $id_produto);
						} else {
							$data_tabela_novo = array(
								'percentual' => $novo_percentual * 100,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);

							self::$db->update("produto_tabela", $data_tabela_novo, "id_tabela=" . $exrow->id . " AND id_produto=" . $id_produto);
						}
					}
				}
				$data['codigo_interno'] = post('codigo_interno') ? post('codigo_interno') : '';

				self::$db->update(self::uTable, $data, "id=" . Filter::$id);
			}

			$message = (Filter::$id) ? lang('PRODUTO_AlTERADO_OK') : lang('PRODUTO_ADICIONADO_OK');

			$o_grupo = post('o_grupo');
			$o_categoria = post('o_categoria');
			$ogrupo = '&id_grupo=' . $o_grupo;
			$ocategoria = '&id_categoria=' . $o_categoria;
			if (isset($_POST["novo"])) {
				$redirecionar = "index.php?do=produto&acao=adicionar";
			} else {
				$redirecionar = "index.php?do=produto&acao=editar&id=" . $id_produto;
			}

			if (self::$db->affected()) {
				Filter::msgOk($message, $redirecionar);
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::obterProdutosSemCodigoDeBarras()
	 *
	 * @return
	 */
	public function obterProdutosSemCodigoDeBarras()
	{
		$sql = "SELECT * FROM produto WHERE codigobarras=''";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarAtualizarCodigosDeBarras()
	 *
	 * @return
	 */
	public function processarAtualizarCodigosDeBarras()
	{
		$produtos_sem_codigo = $this->obterProdutosSemCodigoDeBarras();
		if ($produtos_sem_codigo) {
			$qtde_produtos = 0;
			$id_empresa = session('idempresa');
			$cnpj_empresa = getValue("cnpj", "empresa", "id=" . $id_empresa);
			if ($cnpj_empresa[0] == 2)
				$cnpj_empresa[0] = rand(3, 9);
			foreach ($produtos_sem_codigo as $produto_sem_codigo) {
				$codBarAuto = gerarCodigoBarrasAutomatico($cnpj_empresa, self::$db);
				$array_produto = array(
					'codigo' => substr(strval($codBarAuto), -4),
					'codigobarras' => $codBarAuto,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update(self::uTable, $array_produto, "id=" . $produto_sem_codigo->id);
				if (self::$db->affected()) {
					$qtde_produtos++;
				}
			}
			$mensagem = str_replace("[V2]", count($produtos_sem_codigo), (str_replace("[V1]", $qtde_produtos, lang('PRODUTO_ATUALIZAR_CODBARRAS_OK'))));
			$redirecionar = "index.php?do=produto&acao=listar";
			Filter::msgOk($mensagem, $redirecionar);
		} else {
			Filter::msgAlert(lang('PRODUTO_ATUALIZAR_CODBARRAS_FALHA'));
		}
	}

	/**
	 * Produto::getNotaPossuiReceita()
	 *
	 * @return
	 */
	public function getNotaPossuiReceita($id_nota)
	{
		$sql = "SELECT id FROM receita WHERE id_nota = $id_nota";
		$row = self::$db->first($sql);
		return ($row) ? true : false;
	}

	/**
	 * Produto::getQuantParcelaReceitaNota()
	 *
	 * @return
	 */
	public function getQuantParcelaReceitaNota($id_nota)
	{
		$sql = "SELECT SUM(prc) AS parcelas FROM (
					SELECT COUNT(*) AS prc from receita WHERE id_nota = $id_nota AND inativo = 0
					UNION ALL
					SELECT SUM(cf.total_parcelas) AS prc from cadastro_financeiro as cf left join tipo_pagamento as tp on cf.tipo=tp.id WHERE tp.id_categoria=1 AND cf.id_nota = $id_nota AND cf.inativo = 0
				) AS parcelas";
		$row = self::$db->first($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getNotaVendaPossuiReceita()
	 *
	 * @return
	 */
	public function getNotaVendaPossuiReceita($id_venda)
	{
		$sql = "SELECT id FROM receita WHERE id_venda = $id_venda";
		$row = self::$db->first($sql);
		return ($row) ? true : false;
	}

	/**
	 * Produto::getTipoPagamentoDinheiro()
	 *
	 * @return
	 */
	public function getTipoPagamentoDinheiro()
	{
		$sql = "SELECT id FROM tipo_pagamento WHERE id_categoria = 1 AND inativo=0";
		$row = self::$db->first($sql);
		return ($row) ? $row->id : 0;
	}

	/**
	 * Produto::getTiposPagamentoDinheiro()
	 *
	 * @return
	 */
	public function getTiposPagamentoDinheiro()
	{
		$sql = "SELECT id FROM tipo_pagamento WHERE id_categoria = 1 AND inativo=0";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getBancoPagamentoDinheiro()
	 *
	 * @return
	 */
	public function getBancoPagamentoDinheiro()
	{
		$sql = "SELECT id_banco FROM tipo_pagamento WHERE id_categoria = 1";
		$row = self::$db->first($sql);
		return ($row) ? $row->id_banco : 0;
	}

	/**
	 * Cadastro::obterFreteItensNota()
	 *
	 * @return
	 */
	public function obterFreteItensNota($id_nota)
	{
		$sql = "SELECT SUM(valor_frete) as vlr_frete, id, valor_total, valor_frete
				FROM nota_fiscal_itens
				where id_nota = $id_nota AND inativo = 0";
		$row = self::$db->first($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Cadastro::obterSeguroItensNota()
	 *
	 * @return
	 */
	public function obterSeguroItensNota($id_nota)
	{
		$sql = "SELECT SUM(valor_seguro) as vlr_seguro, id, valor_total, valor_seguro
				FROM nota_fiscal_itens
				where id_nota = $id_nota AND inativo = 0";
		$row = self::$db->first($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarNotaFiscal()
	 *
	 * @return
	 */
	public function processarNotaFiscal()
	{
		$cont_un = 0;

		if (!empty($_POST['nf_exportacao'])) {
			if (empty($_POST['cfop']))
				Filter::$msgs['cfop_exportacao'] = lang('MSG_ERRO_CFOP_EXPORTACAO');
			else if ($_POST['cfop'][0] != '7')
				Filter::$msgs['cfop_exportacao'] = lang('MSG_ERRO_CFOP_EXPORTACAO');

			if (empty($_POST['pais_exportacao']))
				Filter::$msgs['pais_exportacao'] = lang('MSG_ERRO_CFOP_EXPORTACAO_PAIS');
		}

		if (empty($_POST['id_produto']))
			Filter::$msgs['id_produto'] = lang('MSG_ERRO_PRODUTO');
		else
			$cont_un = count(post('id_produto'));

		if (empty($_POST['id_empresa']))
			Filter::$msgs['id_empresa'] = lang('MSG_ERRO_EMPRESA');

		if (empty($_POST['id_cadastro']))
			Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_NOME');

		if (empty($_POST['cfop']))
			Filter::$msgs['cfop'] = lang('MSG_ERRO_CFOP');

		if (!empty($_POST['modalidade'])) {
			$tipofrete = post('modalidade');

			if ($tipofrete != "SemOcorrenciaDeTransporte" and $tipofrete != "SemFrete") {

				if (empty($_POST['tipopessoadestinatario']) or empty($_POST['cpfcnpjdestinatario']) or empty($_POST['logradouro']) or empty($_POST['numero']) or empty($_POST['bairro']) or empty($_POST['cidade']) or empty($_POST['uf']) or empty($_POST['cep']))
					Filter::$msgs['transporte_destinatario'] = lang('MSG_ERRO_TRANSPORTE_DESTINATARIO');

				if (empty($_POST['trans_nome']) or empty($_POST['trans_tipopessoa']) or empty($_POST['trans_cpfcnpj']) or empty($_POST['trans_endereco']) or empty($_POST['trans_cidade']) or empty($_POST['trans_uf']))
					Filter::$msgs['transporte_transportadora'] = lang('MSG_ERRO_TRANSPORTE_TRANSPORTADORA');
			}
		}

		$item_pedido_compra = post('item_pedido_compra');
		$itens_sem_pedido = 0;

		for ($j = 0; $j < $cont_un; $j++) {
			if (empty($item_pedido_compra[$j]) || $item_pedido_compra[$j] == 0)
				$itens_sem_pedido++;
		}

		if ($itens_sem_pedido != 0 && !empty($_POST['numero_pedido_compra']))
			Filter::$msgs['itempedido'] = lang('MSG_ERRO_ITEM_PEDIDO_COMPRA');

		if ($itens_sem_pedido != $cont_un && empty($_POST['numero_pedido_compra']))
			Filter::$msgs['numeropedido'] = lang('MSG_ERRO_NUMERO_PEDIDO_COMPRA');

		if (empty(Filter::$msgs)) {
			$data_vencimento = (empty($_POST['data_vencimento'])) ? "NOW()" : dataMySQL(post('data_vencimento'));
			$cpf_cnpj = getValue('cpf_cnpj', 'cadastro', 'id=' . post('id_cadastro'));
			$id_produto = post('id_produto');
			$produto_tipo_item = post('produto_tipo_item');
			$quantidade = post('quantidade_produto');
			$valor_unitario = post('valor_unitario_produto');
			$valor_desconto = empty($_POST['valor_desconto_produto']) ? 0.0 : post('valor_desconto_produto');
			$valor_despesas = (empty($_POST['valor_despesas_produto']) || $_POST['valor_despesas_produto'] == 0) ? 0.0 : post('valor_despesas_produto');
			$cfop_produto = limparNumero(post('cfop_produto'));
			$icms_cst = limparNumero(post('icms_cst'));
			$ncm = limparNumero(post('ncm'));
			$cest = limparNumero(post('cest'));
			$base_icms = post('valor_base_icms');
			$icms_percentual = post('icms_percentual');
			$icms_st_base = post('icms_st_base');
			$icms_st_percentual = post('icms_st_percentual');
			$pis_cst = post('pis_cst');
			$valor_base_pis = post('valor_base_pis');
			$pis_percentual = post('pis_percentual');
			$cofins_cst = post('cofins_cst');
			$valor_base_cofins = post('valor_base_cofins');
			$cofins_percentual = post('cofins_percentual');
			$ipi_cst = post('ipi_cst');
			$valor_base_ipi = post('valor_base_ipi');
			$ipi_percentual = post('ipi_percentual');
			$mva_produto = post('mva_produto');
			$cfop = sanitize(post('cfop'));
			$natureza_operacao_nfe = sanitize(post('natureza_operacao_nfe'));
			$nf_exportacao = sanitize(post('nf_exportacao'));
			$pais_exportacao = sanitize(post('pais_exportacao'));
			$mva = converteMoeda(post('mva'));
			$icms_st_aliquota = converteMoeda(post('icms_st_aliquota'));
			$icms_normal_aliquota = converteMoeda(post('icms_normal_aliquota'));
			$valor_seguro = empty($_POST['valor_seguro']) ? 0.0 : converteMoeda(post('valor_seguro'));
			$valor_frete = empty($_POST['valor_frete']) ? 0.0 : converteMoeda(post('valor_frete'));

			$valor_nota = 0;
			$total_produto = 0;
			$total_produto_nota = 0;
			$total_desconto = 0;
			$total_despesas = 0;
			$valor_base_st = 0;
			$valor_st = 0;
			$total_valor_ipi = 0;

			for ($j = 0; $j < $cont_un; $j++) {
				$qt = floatval(converteMoeda($quantidade[$j]));
				$vl = floatval(converteMoeda($valor_unitario[$j]));
				$vd = floatval(converteMoeda($valor_desconto[$j]));
				$vda = floatval(converteMoeda($valor_despesas[$j])); // Valor das Despesas Acessórias.
				$total_produto = round((($qt * $vl) + $vda) - $vd, 2);
				$total_produto_nota += $total_produto;
				$valor_nota += $total_produto;
				$total_desconto += $vd;
				$total_despesas += $vda;

				/// Calcular o IPI do Produto para atualizar a Nota Fiscal
				$row_produto = Core::getRowById("produto", $id_produto[$j]);
				$ipi_produto_base = (!empty($valor_base_ipi[$j]) && $valor_base_ipi[$j] > 0) ? $valor_base_ipi[$j] : floatval($row_produto->valor_custo) + floatval($row_produto->valor_frete);
				$ipi_produto_valor = floatval($ipi_produto_base) * (floatval(converteMoeda($ipi_percentual[$j])) / 100);
				$total_valor_ipi += $ipi_produto_valor;
			}

			if ($mva > 0) {
				$valor_base_st = $valor_nota + $total_valor_ipi + $valor_frete + $valor_seguro + (($valor_nota / 100) * $mva);

				if ($icms_st_aliquota > 0) {
					$valor_st = ($valor_base_st / 100) * $icms_st_aliquota;
					$valor_nota += $valor_st;
				}
			}

 			$valor_nota += $valor_seguro;
			$valor_nota += $valor_frete;

			$descriminacao = (empty($_POST['descriminacao'])) ? getValue("descricao", "cfop", "cfop='$cfop'") : post('descriminacao');
			$inf_adicionais = sanitize(post('inf_adicionais'));
			$inf_adicionais = str_replace('=', ':', $inf_adicionais);
			$operacao_e_s = getValue("operacao_e_s", "cfop", "cfop='$cfop'");
			$operacao = ($operacao_e_s == "s") ? 2 : 1;

			$apresentar_duplicatas = sanitize(post('apresentar_duplicatas'));

			$data = array(
				'id_empresa' => sanitize(post('id_empresa')),
				'modelo' => '2',
				'operacao' => $operacao,
				'id_cadastro' => sanitize(post('id_cadastro')),
				'cpf_cnpj' => $cpf_cnpj,
				'numero_nota' => "NULL",
				'chaveacesso' => sanitize(post('chaveacesso')),
				'nfe_referenciada' => str_replace(' ', '', sanitize(post('nfe_referenciada'))),
				'data_emissao' => "NOW()",
				'dataSaidaEntrada' => dataMySQL(post('dataSaidaEntrada')),
				'cfop' => $cfop,
				'natureza_operacao' => $natureza_operacao_nfe,
				'nota_exportacao' => intval($nf_exportacao),
				'pais_exportacao' => $pais_exportacao,
				'mva' => $mva,
				'icms_st_aliquota' => $icms_st_aliquota,
				'icms_normal_aliquota' => $icms_normal_aliquota,
				'valor_base_st' => $valor_base_st,
				'valor_st' => $valor_st,
				'valor_frete' => $valor_frete,
				'valor_seguro' => $valor_seguro,
				'valor_desconto' => $total_desconto,
				'descriminacao' => $descriminacao,
				'inf_adicionais' => $inf_adicionais,
				'apresentar_duplicatas' => intval($apresentar_duplicatas),
				'valor_produto' => $total_produto_nota,
				'valor_nota' => $valor_nota,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			$id_nota = self::$db->insert("nota_fiscal", $data);

			$message = lang('NOTA_ADICIONADO_OK');

			if (!empty($_POST['modalidade'])) {
				$modalidade = post('modalidade');

				if ($modalidade != "SemOcorrenciaDeTransporte" and $modalidade != "SemFrete") {
					$data_transporte = array(
						'id_nota' => $id_nota,
						'modalidade' => $modalidade,
						'tipopessoadestinatario' => post('tipopessoadestinatario'),
						'cpfcnpjdestinatario' => limparNumero(post('cpfcnpjdestinatario')),
						'uf' => sanitize(post('uf')),
						'cidade' => sanitize(post('cidade')),
						'logradouro' => sanitize(post('logradouro')),
						'numero' => sanitize(post('numero')),
						'complemento' => sanitize(post('complemento')),
						'bairro' => sanitize(post('bairro')),
						'cep' => sanitize(post('cep')),
						'trans_tipopessoa' => post('trans_tipopessoa'),
						'trans_cpfcnpj' => limparNumero(post('trans_cpfcnpj')),
						'trans_nome' => sanitize(post('trans_nome')),
						'trans_inscricaoestadual' => sanitize(post('trans_inscricaoestadual')),
						'trans_endereco' => sanitize(post('trans_endereco')),
						'trans_cidade' => sanitize(post('trans_cidade')),
						'trans_uf' => sanitize(post('trans_uf')),
						'veiculo_placa' => sanitize(post('veiculo_placa')),
						'veiculo_uf' => sanitize(post('veiculo_uf')),
						'especie' => sanitize(post('especie')),
						'quantidade' => converteMoeda(post('quantidade')),
						'pesoliquido' => converteMoeda(post('pesoliquido')),
						'pesobruto' => converteMoeda(post('pesobruto'))
					);
					self::$db->insert("nota_fiscal_transporte", $data_transporte);
				}
			}

			$tipo_pagamento = $this->getTipoPagamentoDinheiro();

			if ($operacao == 2) {
				$descricao = 'RECEITA AUTOMATICA DE NOTA FISCAL';
				$nomecliente = (sanitize(post('id_cadastro'))) ? getValue("nome", "cadastro", "id=" . sanitize(post('id_cadastro'))) : "";
				$data_receita = array(
					'id_empresa' => sanitize(post('id_empresa')),
					'id_cadastro' => sanitize(post('id_cadastro')),
					'id_nota' => $id_nota,
					'id_conta' => 19,
					'descricao' => $descricao,
					'tipo' => $tipo_pagamento,
					'pago' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$vencimentos = stripTags('[', ']', $inf_adicionais);

				$total_produto = 0;
				$total_base_icms = 0;
				$total_icms = 0;
				$total_base_icms_st = 0;
				$total_icms_st_valor_produto = 0;
				$total_valor_pis = 0;
				$total_valor_cofins = 0;
				$total_valor_ipi = 0;
				$numero_pedido_compra = post('numero_pedido_compra');

				for ($j = 0; $j < $cont_un; $j++) {
					$valor_frete_produto = ($valor_frete > 0) ? round($valor_frete / $cont_un, 2) : 0;
					$valor_seguro_produto = ($valor_seguro > 0) ? round($valor_seguro / $cont_un, 2) : 0;

					$qt = floatval(converteMoeda($quantidade[$j]));
					$vl = floatval(converteMoeda($valor_unitario[$j]));
					$vd = floatval(converteMoeda($valor_desconto[$j]));
					$vDespesas = floatval(converteMoeda($valor_despesas[$j]));
					$total_produto = round(($qt * $vl) - $vd + $vDespesas + $valor_frete_produto + $valor_seguro_produto, 2);
					$valor_item_pedido_compra = ($numero_pedido_compra) ? intval($item_pedido_compra[$j]) : 0;
					$icms = (!empty($icms_percentual[$j]) && $icms_percentual[$j] > 0) ? converteMoeda($icms_percentual[$j]) : converteMoeda($icms_normal_aliquota);
					$valor_base_icms = (!empty($base_icms[$j]) && $base_icms[$j] > 0) ? converteMoeda($base_icms[$j]) : $total_produto;
					$mva_aliquota = (!empty($mva_produto[$j]) && $mva_produto[$j] > 0) ? converteMoeda($mva_produto[$j]) : $mva;
					$st_base = (!empty($icms_st_base[$j]) && $icms_st_base[$j] > 0) ? converteMoeda($icms_st_base[$j]) : $total_produto + $ipi_produto_valor + ((($total_produto + $ipi_produto_valor) / 100) * $mva_aliquota);;
					$st_percentual = (!empty($icms_st_percentual[$j]) && $icms_st_percentual[$j] > 0) ? converteMoeda($icms_st_percentual[$j]) : converteMoeda($icms_st_aliquota);
					$id_cadastro = post('id_cadastro');
					$id_produto_fornecedor = getValue('id', 'produto_fornecedor', 'id_produto=' . $id_produto[$j] . ' AND id_cadastro=' . $id_cadastro);

					/// Calcular o IPI do Produto para atualizar a Nota Fiscal
					$row_produto = Core::getRowById("produto", $id_produto[$j]);
					$ipi_produto_base = $valor_base_ipi[$j];
					$ipi_produto_valor = floatval($ipi_produto_base) * (floatval(converteMoeda($ipi_percentual[$j])) / 100);

					$total_valor_ipi += $ipi_produto_valor;

					/// Calcula o VALOR ST PRODUTO para atualizar a Nota Fiscal
					/*
					$icms_st_valor_produto = 0;
					if ($mva_aliquota > 0) {
						$valor_total_produto = ($qt * $vl);
						$valor_base_st_produto = (!empty($icms_st_base[$j]) && $icms_st_base[$j] > 0) ? converteMoeda($icms_st_base[$j]) : converteMoeda($valor_total_produto + (($valor_total_produto / 100) * $mva_aliquota));
						if ($st_percentual > 0) {
							$icms_st_valor_produto = ($valor_base_st_produto / 100) * $st_percentual;
						}
						if ($valor_base_st_produto > 0)
						$st_base = converteMoeda($valor_base_st_produto);
						$total_icms_st_valor_produto += $icms_st_valor_produto;
					}
					*/

					$valor_icms = round((floatval($valor_base_icms) / 100) * floatval($icms), 2);
					$valor_mva = ($st_base / 100) * $st_percentual;
					$valor_icms_st = ((int) $mva_aliquota) ? round($valor_mva - $valor_icms, 2) : 0;
					$pis_valor = round((floatval($valor_base_pis[$j]) / 100) * floatval(converteMoeda($pis_percentual[$j])), 2);
					$cofins_valor = round((floatval($valor_base_cofins[$j]) / 100) * floatval(converteMoeda($cofins_percentual[$j])), 2);

					$total_base_icms += $valor_base_icms;
					$total_icms += $valor_icms;
					$total_base_icms_st += $st_base ;
					$total_icms_st_valor_produto += $valor_icms_st;
					$total_valor_pis += $pis_valor;
					$total_valor_cofins += $cofins_valor;

					$vtrib = $valor_icms + $valor_icms_st + ((floatval($valor_base_pis[$j]) / 100) * floatval($pis_percentual[$j])) + ((floatval($valor_base_cofins[$j]) / 100) * floatval($cofins_percentual[$j])) + $ipi_produto_valor;

					$data_nota = array(
						'id_empresa' => sanitize(post('id_empresa')),
						'id_nota' => $id_nota,
						'id_cadastro' => sanitize(post('id_cadastro')),
						'id_produto' => $id_produto[$j],
						'produto_tipo_item' => $produto_tipo_item[$j],
						'id_produto_fornecedor' => ($id_produto_fornecedor != '') ? $id_produto_fornecedor : 0,
						'quantidade' => $qt,
						'cfop' => $cfop_produto[$j],
						'ncm' => $ncm[$j],
						'cest' => $cest[$j],
						'valor_unitario' => $vl,
						'valor_desconto' => $vd,
						'valor_total' => $total_produto,
						'valor_frete' => round($valor_frete_produto, 2),
						'valor_seguro' => round($valor_seguro_produto, 2),
						'valor_total_trib' => round($vtrib, 2),
						'outrasDespesasAcessorias' => $vDespesas,
						'numero_pedido_compra' => $numero_pedido_compra,
						'item_pedido_compra' => $valor_item_pedido_compra,
						'icms_cst' => (!empty($icms_cst[$j]) && $icms_cst[$j] > 0) ? $icms_cst[$j] : getValue("icms_cst", "produto", "id=" . $id_produto[$j]),
						'icms_percentual' => $icms,
						'icms_base' =>  $icms == 0 || $icms == '' ? 0 : $valor_base_icms,
						'icms_valor' => $valor_icms,
						'icms_percentual_mva_st' => $mva_aliquota,
						'icms_st_base' => $st_percentual == 0 || $st_percentual == '' ? 0 : round($st_base, 2),
						'icms_st_percentual' => $st_percentual,
						'icms_st_valor' => $valor_icms_st,
						'pis_cst' => $pis_cst[$j] ? $pis_cst[$j] : $row_produto->pis_cst,
						'pis_base' => $pis_percentual[$j] == 0 || $pis_percentual[$j] == '' ? 0 : converteMoeda($valor_base_pis[$j]),
						'pis_percentual' => converteMoeda($pis_percentual[$j]),
						'pis_valor' => $pis_valor,
						'cofins_cst' => $cofins_cst[$j] ? $cofins_cst[$j] : $row_produto->cofins_cst,
						'cofins_base' => $cofins_percentual[$j] == 0 || $cofins_percentual[$j] == '' ? 0 : converteMoeda($valor_base_cofins[$j]),
						'cofins_percentual' => converteMoeda($cofins_percentual[$j]),
						'cofins_valor' => $cofins_valor,
						'ipi_cst' => $ipi_cst[$j] ? $ipi_cst[$j] : $row_produto->ipi_saida_codigo,
						'ipi_base' => $ipi_percentual[$j] == 0 || $ipi_percentual[$j] == '' ? 0 : converteMoeda($ipi_produto_base),
						'ipi_percentual' => converteMoeda($ipi_percentual[$j]),
						'ipi_valor' => round($ipi_produto_valor, 2),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("nota_fiscal_itens", $data_nota);

					$numero_nota = getValue("numero_nota", "nota_fiscal", "id=" . $id_nota);
					$obs_numero_nota = ($numero_nota > 0) ? 'NÚMERO: ' . $numero_nota : 'ID: ' . $id_nota;
					$obs = "NOTA FISCAL: [ " . $obs_numero_nota . " ]";

					$tipo = 2;
					$motivo = 3;
					$qt = $qt * (-1);

					$data_estoque = array(
						'id_empresa' => sanitize(post('id_empresa')),
						'id_produto' => $id_produto[$j],
						'quantidade' => $qt,
						'tipo' => $tipo,
						'motivo' => $motivo,
						'observacao' => $obs,
						'id_ref' => $id_nota,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("produto_estoque", $data_estoque);

					$totalestoque = $this->getEstoqueTotal($id_produto[$j]);
					$data_update = array(
						'estoque' => $totalestoque,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("produto", $data_update, "id=" . $id_produto[$j]);

					$kit = getValue("kit", "produto", "id=" . $id_produto[$j]);

					if ($kit) {
						$nomekit = getValue("nome", "produto", "id=" . $id_produto[$j]);
						$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade "
							. "\n FROM produto_kit as k"
							. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
							. "\n WHERE k.id_produto_kit = $id_produto[$j] AND k.materia_prima=0"
							. "\n ORDER BY p.nome ";
						$retorno_row = self::$db->fetch_all($sql);

						if ($retorno_row) {
							foreach ($retorno_row as $exrow) {
								$observacao = str_replace("[ID_VENDA]", $id_nota, lang('VENDA_KIT_NF'));
								$observacao = str_replace("[NOME_KIT]", $nomekit, $observacao);
								$observacao = str_replace("[NOME_CLIENTE]", $nomecliente, $observacao);

								$quant_estoque_kit = $qt * $exrow->quantidade;

								$data_estoque = array(
									'id_empresa' => sanitize(post('id_empresa')),
									'id_produto' => $exrow->id_produto,
									'quantidade' => $quant_estoque_kit,
									'tipo' => 2,
									'motivo' => 3,
									'observacao' => $observacao,
									'id_ref' => $id_nota,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->insert("produto_estoque", $data_estoque);
								$totalestoque = $this->getEstoqueTotal($exrow->id_produto);
								$data_update = array(
									'estoque' => $totalestoque,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->update("produto", $data_update, "id=" . $exrow->id_produto);
							}
						}
					}
				}

				//Este trecho de código serve para ajustar o valor do frete quando o mesmo for quebrado e gerar diferença no total.
				$novo_valor_frete = $this->obterFreteItensNota($id_nota);
				$frete_nota = round(getValue("valor_frete", "nota_fiscal", "id=" . $id_nota), 2);
				if ($novo_valor_frete->vlr_frete != $frete_nota) {
					$novo_frete = ($valor_frete - $novo_valor_frete->vlr_frete) + $novo_valor_frete->valor_frete;
					$novo_total_produto = ($novo_valor_frete->valor_total - $novo_valor_frete->valor_frete) + $novo_frete;
					$data_frete = array(
						'valor_total' => round($novo_total_produto, 2),
						'valor_frete' => round($novo_frete, 2),
					);
					self::$db->update("nota_fiscal_itens", $data_frete, "id=" . $novo_valor_frete->id);
				}

				//Este trecho de código serve para ajustar o valor do seguro quando o mesmo for quebrado e gerar diferença no total.
				$novo_valor_seguro = $this->obterSeguroItensNota($id_nota);
				$seguro_nota = round(getValue("valor_seguro", "nota_fiscal", "id=" . $id_nota), 2);
				if ($novo_valor_seguro->vlr_seguro != $seguro_nota) {
					$novo_seguro = ($valor_seguro - $novo_valor_seguro->vlr_seguro) + $novo_valor_seguro->valor_seguro;
					$novo_total_produto = ($novo_valor_seguro->valor_total - $novo_valor_seguro->valor_seguro) + $novo_seguro;
					$data_seguro = array(
						'valor_total' => round($novo_total_produto, 2),
						'valor_seguro' => round($novo_seguro, 2)
					);
					self::$db->update("nota_fiscal_itens", $data_seguro, "id=" . $novo_valor_seguro->id);
				}

				/// ATUALIZAÇÃO DO CÁLCULO DE IPI NA NOTA FISCAL
				$data_ipi_nota = array(
					'valor_base_icms' => $total_base_icms,
					'valor_icms' => $total_icms,
					'valor_base_st' => $total_base_icms_st,
					'valor_ipi' => round($total_valor_ipi, 2),
					'valor_st' => $total_icms_st_valor_produto,
					'valor_pis' => $total_valor_pis,
					'valor_cofins' => $total_valor_cofins,
					'valor_nota' =>  round($valor_nota + $total_valor_ipi + $total_icms_st_valor_produto, 2)
				);
				self::$db->update("nota_fiscal", $data_ipi_nota, "id=" . $id_nota);

				if ($vencimentos) {
					$vencimento_array = explode(';', $vencimentos);
					$count = count($vencimento_array);
					$i = 0;
					if ($count > 1) {
						while ($i < $count) {
							$v = $i + 1;
							$data_receita['valor'] = converteMoeda(trim($vencimento_array[$v]));
							$data_receita['valor_pago'] = converteMoeda(trim($vencimento_array[$v]));
							$data_receita['data_pagamento'] = dataMySQL(trim($vencimento_array[$i]));
							self::$db->insert("receita", $data_receita);
							$i += 2;
						}
					} else {
						$vencimento_array = explode('/', $vencimentos);
						$count = count($vencimento_array);
						if ($count == 3)
							$data_vencimento = dataMySQL(trim($vencimentos));

						$data_receita['valor'] = round($valor_nota + $total_valor_ipi + $total_icms_st_valor_produto, 2);
						$data_receita['valor_pago'] = round($valor_nota + $total_valor_ipi + $total_icms_st_valor_produto, 2);
						$data_receita['data_pagamento'] = $data_vencimento;
						self::$db->insert("receita", $data_receita);
					}
				} else {
					$data_receita['valor'] = round($valor_nota + $total_valor_ipi + $total_icms_st_valor_produto, 2);
					$data_receita['valor_pago'] = round($valor_nota + $total_valor_ipi + $total_icms_st_valor_produto, 2);
					$data_receita['data_pagamento'] = $data_vencimento;
					self::$db->insert("receita", $data_receita);
				}
			} else {
				$descricao = 'DESPESA AUTOMATICA DE NOTA FISCAL';
				$data_despesa = array(
					'id_empresa' => sanitize(post('id_empresa')),
					'id_cadastro' => sanitize(post('id_cadastro')),
					'id_nota' => $id_nota,
					'id_conta' => 30,
					'descricao' => $descricao,
					'tipo' => $tipo_pagamento,
					'pago' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$vencimentos = stripTags('[', ']', $inf_adicionais);

				if ($vencimentos) {
					$vencimento_array = explode(';', $vencimentos);
					$count = count($vencimento_array);
					$i = 0;

					if ($count > 1) {
						while ($i < $count) {
							$v = $i + 1;
							$data_despesa['valor'] = converteMoeda(trim($vencimento_array[$v]));
							$data_despesa['valor_pago'] = converteMoeda(trim($vencimento_array[$v]));
							$data_despesa['data_vencimento'] = dataMySQL(trim($vencimento_array[$i]));
							self::$db->insert("despesa", $data_despesa);
							$i += 2;
						}
					} else {
						$vencimento_array = explode('/', $vencimentos);
						$count = count($vencimento_array);
						if ($count == 3)
							$data_vencimento = dataMySQL(trim($vencimentos));
						$data_despesa['valor'] = $valor_nota;
						$data_despesa['valor_pago'] = $valor_nota;
						$data_despesa['data_vencimento'] = $data_vencimento;
						self::$db->insert("despesa", $data_despesa);
					}
				} else {
					$data_despesa['valor'] = $valor_nota;
					$data_despesa['valor_pago'] = $valor_nota;
					$data_despesa['data_vencimento'] = $data_vencimento;
					self::$db->insert("despesa", $data_despesa);
				}

				$total_produto = 0;

				for ($j = 0; $j < $cont_un; $j++) {
					$qt = converteMoeda($quantidade[$j]);
					$vl = converteMoeda($valor_unitario[$j]);
					$vd = converteMoeda($valor_desconto[$j]);
					$vDespesas = converteMoeda($valor_despesas[$j]);
					$icms = (!empty($icms_percentual[$j]) && $icms_percentual[$j] > 0) ? converteMoeda($icms_percentual[$j]) : converteMoeda($icms_normal_aliquota);
					$st_base = converteMoeda($icms_st_base[$j]);
					$st_percentual = (!empty($icms_st_percentual[$j]) && $icms_st_percentual[$j] > 0) ? converteMoeda($icms_st_percentual[$j]) : converteMoeda($icms_st_aliquota);
					$total_produto = round(($qt * $vl) - $vd + $vDespesas, 2);
					$id_cadastro = post('id_cadastro');
					$id_produto_fornecedor = getValue('id', 'produto_fornecedor', 'id_produto=' . $id_produto[$j] . ' AND id_cadastro=' . $id_cadastro);
					$mva_aliquota = (!empty($mva_produto[$j]) && $mva_produto[$j] > 0) ? $mva_produto[$j] : $mva;

					$data_nota = array(
						'id_empresa' => sanitize(post('id_empresa')),
						'id_nota' => $id_nota,
						'id_cadastro' => sanitize(post('id_cadastro')),
						'id_produto' => $id_produto[$j],
						'produto_tipo_item' => $produto_tipo_item[$j],
						'quantidade' => $qt,
						'cfop' => $cfop_produto[$j],
						'ncm' => $ncm[$j],
						'cest' => $cest[$j],
						'valor_unitario' => $vl,
						'valor_desconto' => $vd,
						'valor_total' => $total_produto,
						'outrasDespesasAcessorias' => $vDespesas,
						'icms_cst' => (!empty($icms_cst[$j]) && $icms_cst[$j] > 0) ? $icms_cst[$j] : getValue("icms_cst", "produto", "id=" . $id_produto[$j]),
						'icms_percentual' => $icms,
						'icms_percentual_mva_st' => $mva_aliquota,
						'icms_st_base' => $st_base ? round($st_base, 2) : round($valor_base_st, 2),
						'icms_st_percentual' => $st_percentual,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("nota_fiscal_itens", $data_nota);

					$numero_nota = getValue("numero_nota", "nota_fiscal", "id=" . $id_nota);
					$obs_numero_nota = ($numero_nota > 0) ? 'NÚMERO: ' . $numero_nota : 'ID: ' . $id_nota;
					$obs = "NOTA FISCAL: [ " . $obs_numero_nota . " ]";

					$tipo = 1;
					$motivo = 1;

					$data_estoque = array(
						'id_empresa' => sanitize(post('id_empresa')),
						'id_produto' => $id_produto[$j],
						'quantidade' => $qt,
						'tipo' => $tipo,
						'motivo' => $motivo,
						'observacao' => $obs,
						'id_ref' => $id_nota,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("produto_estoque", $data_estoque);

					$totalestoque = $this->getEstoqueTotal($id_produto[$j]);
					$data_update = array(
						'estoque' => $totalestoque,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("produto", $data_update, "id=" . $id_produto[$j]);
				}
			}
			if ($id_nota) {
				Filter::msgOk($message, "index.php?do=notafiscal&acao=visualizar&id=" . $id_nota);
			} else
				Filter::msgOk(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::editarNotaFiscal()
	 *
	 * @return
	 */
	public function editarNotaFiscal()
	{
		$cont_un = 0;
		$id_nota = Filter::$id;
		$id_venda = getValue('id_venda', 'nota_fiscal', 'id=' . $id_nota);
		$modelo = getValue('modelo', 'nota_fiscal', 'id=' . $id_nota);
		$valor_nota_original = getValue('valor_nota', 'nota_fiscal', 'id=' . $id_nota);

		if (!empty($_POST['nf_exportacao'])) {
			if (empty($_POST['cfop']))
				Filter::$msgs['cfop_exportacao'] = lang('MSG_ERRO_CFOP_EXPORTACAO');
			else if ($_POST['cfop'][0] != '7')
				Filter::$msgs['cfop_exportacao'] = lang('MSG_ERRO_CFOP_EXPORTACAO');

			if (empty($_POST['pais_exportacao']))
				Filter::$msgs['pais_exportacao'] = lang('MSG_ERRO_CFOP_EXPORTACAO_PAIS');
		}

		if (!$id_venda) {
			if (empty($_POST['id_produto']) && $modelo!=3)
				Filter::$msgs['id_produto'] = lang('MSG_ERRO_PRODUTO');
			else
				$cont_un = (empty($_POST['id_produto'])) ? 0 : count(post('id_produto'));
		}

		if (empty($_POST['id_empresa']))
			Filter::$msgs['id_empresa'] = lang('MSG_ERRO_EMPRESA');

		if (empty($_POST['id_cadastro']))
			Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_NOME');

		
		if (empty($_POST['cfop']) && $modelo!=3)
			Filter::$msgs['cfop'] = lang('MSG_ERRO_CFOP');

		if (!empty($_POST['modalidade'])) {
			$tipofrete = post('modalidade');
			if ($tipofrete != "SemOcorrenciaDeTransporte" and $tipofrete != "SemFrete") {

				if (empty($_POST['tipopessoadestinatario']) or empty($_POST['cpfcnpjdestinatario']) or empty($_POST['logradouro']) or empty($_POST['numero']) or empty($_POST['bairro']) or empty($_POST['cidade']) or empty($_POST['uf']) or empty($_POST['cep']))
					Filter::$msgs['transporte_destinatario'] = lang('MSG_ERRO_TRANSPORTE_DESTINATARIO');

				if (empty($_POST['trans_nome']) or empty($_POST['trans_tipopessoa']) or empty($_POST['trans_cpfcnpj']) or empty($_POST['trans_endereco']) or empty($_POST['trans_cidade']) or empty($_POST['trans_uf']))
					Filter::$msgs['transporte_transportadora'] = lang('MSG_ERRO_TRANSPORTE_TRANSPORTADORA');
			}
		}

		$item_pedido_compra = post('item_pedido_compra');
		$itens_sem_pedido = 0;
		for ($j = 0; $j < $cont_un; $j++) {
			if (empty($item_pedido_compra[$j]) || $item_pedido_compra[$j] == 0)
				$itens_sem_pedido++;
		}

		if ($itens_sem_pedido != 0 && !empty($_POST['numero_pedido_compra']))
			Filter::$msgs['itempedido'] = lang('MSG_ERRO_ITEM_PEDIDO_COMPRA');

		if ($itens_sem_pedido != $cont_un && empty($_POST['numero_pedido_compra']))
			Filter::$msgs['numeropedido'] = lang('MSG_ERRO_NUMERO_PEDIDO_COMPRA');

		if (empty(Filter::$msgs)) {
			$data_limpar = array(
				'inativo' => '1',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			$data_vencimento = (empty($_POST['data_vencimento'])) ? "NOW()" : dataMySQL(post('data_vencimento'));
			$cpf_cnpj = getValue('cpf_cnpj', 'cadastro', 'id=' . post('id_cadastro'));

			$cont_un = (empty($_POST['id_produto'])) ? 0 : count(post('id_produto'));			
			$id_produto = post('id_produto');
			$produto_tipo_item = post('produto_tipo_item');
			$quantidade = post('quantidade_produto');
			$valor_unitario = post('valor_unitario_produto');
			$valor_desconto = empty($_POST['valor_desconto_produto']) ? 0.0 : post('valor_desconto_produto');
			$valor_despesas = (empty($_POST['valor_despesas_produto']) || $_POST['valor_despesas_produto'] == 0) ? 0.0 : post('valor_despesas_produto');
			$cfop_produto = limparNumero(post('cfop_produto'));
			$icms_cst = limparNumero(post('icms_cst'));
			$ncm = limparNumero(post('ncm'));
			$cest = limparNumero(post('cest'));
			$base_icms = post('valor_base_icms');
			$icms_percentual = post('icms_percentual');
			$icms_st_base = post('icms_st_base');
			$icms_st_percentual = post('icms_st_percentual');
			$pis_cst = post('pis_cst');
			$valor_base_pis = post('valor_base_pis');
			$pis_percentual = post('pis_percentual');
			$cofins_cst = post('cofins_cst');
			$valor_base_cofins = post('valor_base_cofins');
			$cofins_percentual = post('cofins_percentual');
			$ipi_cst = post('ipi_cst');
			$valor_base_ipi = post('valor_base_ipi');
			$ipi_percentual = post('ipi_percentual');
			$mva_produto = post('mva_produto');
			$cfop = sanitize(post('cfop'));
			$natureza_operacao_nfe = sanitize(post('natureza_operacao_nfe'));
			$mva = converteMoeda(post('mva'));
			$icms_st_aliquota = converteMoeda(post('icms_st_aliquota'));
			$icms_normal_aliquota = converteMoeda(post('icms_normal_aliquota'));

			$valor_seguro = empty($_POST['valor_seguro']) ? 0.0 : converteMoeda(post('valor_seguro'));
			$valor_frete = empty($_POST['valor_frete']) ? 0.0 : converteMoeda(post('valor_frete'));

			$nf_exportacao = sanitize(post('nf_exportacao'));
			$pais_exportacao = sanitize(post('pais_exportacao'));
			$item_pedido_compra_editar = post('item_pedido_compra');

			$valor_nota = 0;
			$total_produto = 0;
			$total_desconto = 0;
			$total_despesas = 0;
			$valor_base_st = 0;
			$valor_st = 0;
			$total_valor_ipi = 0;

			for ($j = 0; $j < $cont_un; $j++) {
				$qt = floatval(converteMoeda($quantidade[$j]));
				$vl = floatval(converteMoeda($valor_unitario[$j]));
				$vd = floatval(converteMoeda($valor_desconto[$j]));
				$dp = floatval(converteMoeda($valor_despesas[$j]));
				$total_produto += round((($qt * $vl) + $dp) - $vd, 2);
				$total_desconto += $vd;
				$total_despesas += $dp;

				/// Calcular o IPI do Produto para atualizar a Nota Fiscal
				$row_produto = Core::getRowById("produto", $id_produto[$j]);
				$ipi_produto_base = (!empty($valor_base_ipi[$j]) && $valor_base_ipi[$j] > 0) ? $valor_base_ipi[$j] : 0;
				$ipi_produto_valor = floatval($ipi_produto_base) * (floatval(converteMoeda($ipi_percentual[$j])) / 100);
				$total_valor_ipi += $ipi_produto_valor;
			}

			$valor_nota = $total_produto;

			if ($mva > 0) {
				$valor_base_st = $valor_nota + $total_valor_ipi + $valor_frete + $valor_seguro + (($valor_nota / 100) * $mva);

				if ($icms_st_aliquota > 0) {
					$valor_st = ($valor_base_st / 100) * $icms_st_aliquota;
					$valor_nota += $valor_st;
				}
			}

			$valor_nota = ($modelo==3) ? $valor_nota_original : $valor_nota;
			$valor_nota += $valor_seguro;
			$valor_nota += $valor_frete;

			$descriminacao = (empty($_POST['descriminacao'])) ? getValue("descricao", "cfop", "cfop='$cfop'") : post('descriminacao');
			$inf_adicionais = sanitize(post('inf_adicionais'));
			$inf_adicionais = str_replace('=', ':', $inf_adicionais);
			$operacao_e_s = getValue("operacao_e_s", "cfop", "cfop='$cfop'");
			$operacao = ($operacao_e_s == "s") ? 2 : 1;
			$operacao = ($modelo==3) ? 2 : $operacao;

			$apresentar_duplicatas = sanitize(post('apresentar_duplicatas'));
			$nf_exportacao = sanitize(post('nf_exportacao'));
			$pais_exportacao = sanitize(post('pais_exportacao'));

			$data = array(
				'id_empresa' => sanitize(post('id_empresa')),
				'operacao' => $operacao,
				'id_cadastro' => sanitize(post('id_cadastro')),
				'nfe_referenciada' => str_replace(' ', '', sanitize(post('nfe_referenciada'))),
				'cpf_cnpj' => $cpf_cnpj,
				'numero_nota' => "NULL",
				'data_emissao' => "NOW()",
				'dataSaidaEntrada' => (post('dataSaidaEntrada')) ? dataMySQL(post('dataSaidaEntrada')) : '0000-00-00',
				'cfop' => $cfop,
				'natureza_operacao' => $natureza_operacao_nfe,
				'nota_exportacao' => intval($nf_exportacao),
				'pais_exportacao' => $pais_exportacao,
				'mva' => $mva,
				'icms_st_aliquota' => $icms_st_aliquota,
				'icms_normal_aliquota' => $icms_normal_aliquota,
				'valor_base_st' => $valor_base_st,
				'valor_st' => $valor_st,
				'valor_frete' => $valor_frete,
				'valor_seguro' => $valor_seguro,
				'valor_desconto' => $total_desconto,
				'descriminacao' => $descriminacao,
				'inf_adicionais' => $inf_adicionais,
				'apresentar_duplicatas' => intval($apresentar_duplicatas),
				'valor_produto' => $total_produto,
				'valor_nota' => $valor_nota,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->update("nota_fiscal", $data, "id=" . $id_nota);

			$message = lang('NOTA_ALTERADO_OK');

			if (!empty($_POST['modalidade'])) {
				$data_transporte = array(
					'id_nota' => $id_nota,
					'modalidade' => post('modalidade'),
					'tipopessoadestinatario' => post('tipopessoadestinatario'),
					'cpfcnpjdestinatario' => limparNumero(post('cpfcnpjdestinatario')),
					'uf' => post('uf'),
					'cidade' => post('cidade'),
					'logradouro' => post('logradouro'),
					'numero' => post('numero'),
					'complemento' => post('complemento'),
					'bairro' => post('bairro'),
					'cep' => post('cep'),
					'trans_tipopessoa' => post('trans_tipopessoa'),
					'trans_cpfcnpj' => limparNumero(post('trans_cpfcnpj')),
					'trans_nome' => post('trans_nome'),
					'trans_inscricaoestadual' => post('trans_inscricaoestadual'),
					'trans_endereco' => post('trans_endereco'),
					'trans_cidade' => post('trans_cidade'),
					'trans_uf' => post('trans_uf'),
					'veiculo_placa' => post('veiculo_placa'),
					'veiculo_uf' => post('veiculo_uf'),
					'quantidade' => converteMoeda(post('quantidade')),
					'especie' => sanitize(post('especie')),
					'pesoliquido' => converteMoeda(post('pesoliquido')),
					'pesobruto' => converteMoeda(post('pesobruto'))
				);

				$id_transporte = getValue("id", "nota_fiscal_transporte", "id_nota=" . $id_nota);
				if ($id_transporte)
					self::$db->update("nota_fiscal_transporte", $data_transporte, "id_nota=" . $id_nota);
				else
					self::$db->insert("nota_fiscal_transporte", $data_transporte);
			}

			$tipo_pagamento = $this->getTipoPagamentoDinheiro();
			$banco_pagamento = $this->getBancoPagamentoDinheiro();

			if ($operacao == 2) {
				$valida_receita = $this->getNotaPossuiReceita($id_nota);

				if (!$valida_receita && !($id_venda > 0)) {
					$descricao = 'RECEITA AUTOMATICA DE NOTA FISCAL';
					$nomecliente = (sanitize(post('id_cadastro'))) ? getValue("nome", "cadastro", "id=" . sanitize(post('id_cadastro'))) : "";

					$data_receita = array(
						'id_empresa' => sanitize(post('id_empresa')),
						'id_cadastro' => sanitize(post('id_cadastro')),
						'id_nota' => $id_nota,
						'id_conta' => 19,
						'descricao' => $descricao,
						'tipo' => $tipo_pagamento,
						'id_banco' => $banco_pagamento,
						'pago' => '0',
						'usuario' => session('nomeusuario'),
						'inativo' => 0,
						'data' => "NOW()"
					);

					$data_receita['valor'] = $valor_nota;
					$data_receita['valor_pago'] = $valor_nota;
					$data_receita['data_pagamento'] = $data_vencimento;
					self::$db->insert("receita", $data_receita);
				}
				$valida_receita_venda = $this->getNotaVendaPossuiReceita($id_venda);

				if (!$valida_receita && $valida_receita_venda && $id_venda > 0) {
					$data_receita = array(
						'id_nota' => $id_nota,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("receita", $data_receita, "id_venda=" . $id_venda);
				}
				self::$db->update("nota_fiscal_itens", $data_limpar, "id_nota=" . $id_nota);

				$total_produto = 0;
				$total_base_icms = 0;
				$total_icms = 0;
				$total_base_icms_st = 0;
				$total_icms_st_valor_produto = 0;
				$total_valor_pis = 0;
				$total_valor_cofins = 0;
				$total_valor_ipi = 0;
				$numero_pedido_compra = post('numero_pedido_compra');

				for ($j = 0; $j < $cont_un; $j++) {
					$valor_frete_produto = ($valor_frete > 0) ? round($valor_frete / $cont_un, 2) : 0;
					$valor_seguro_produto = ($valor_seguro > 0) ? round($valor_seguro / $cont_un, 2) : 0;

					$qt = floatval(converteMoeda($quantidade[$j]));
					$vl = floatval(converteMoeda($valor_unitario[$j]));
					$vd = floatval(converteMoeda($valor_desconto[$j]));
					$vDespesas = floatval(converteMoeda($valor_despesas[$j]));
					$total_produto = round(($qt * $vl) - $vd + $vDespesas + $valor_frete_produto + $valor_seguro_produto, 2);
					$valor_item_pedido_compra = ($numero_pedido_compra) ? intval(($item_pedido_compra[$j]) ? intval($item_pedido_compra[$j]) : $item_pedido_compra_editar[$j]) : 0;
					$icms = (!empty($icms_percentual[$j]) && $icms_percentual[$j] > 0) ? converteMoeda($icms_percentual[$j]) : converteMoeda($icms_normal_aliquota);
					$valor_base_icms = (!empty($base_icms[$j]) && $base_icms[$j] > 0) ? converteMoeda($base_icms[$j]) : $total_produto;
					$mva_aliquota = (!empty($mva_produto[$j]) && $mva_produto[$j] > 0) ? $mva_produto[$j] : $mva;
					$st_base = converteMoeda($icms_st_base[$j]);
					$st_base = (!empty($icms_st_base[$j]) && $icms_st_base[$j] > 0) ? converteMoeda($icms_st_base[$j]) : $total_produto + $ipi_produto_valor + ((($total_produto + $ipi_produto_valor) / 100) * $mva_aliquota);
					$st_percentual = (!empty($icms_st_percentual[$j]) && $icms_st_percentual[$j] > 0) ? converteMoeda($icms_st_percentual[$j]) : converteMoeda($icms_st_aliquota);
					$id_cadastro = post('id_cadastro');
					$id_produto_fornecedor = getValue('id', 'produto_fornecedor', 'id_produto=' . $id_produto[$j] . ' AND id_cadastro=' . $id_cadastro);

					/// Calcular o IPI do Produto para atualizar a Nota Fiscal
					$row_produto = Core::getRowById("produto", $id_produto[$j]);
					$ipi_produto_base = $valor_base_ipi[$j];
					$ipi_produto_valor = floatval($ipi_produto_base) * (floatval(converteMoeda($ipi_percentual[$j])) / 100);

					$total_valor_ipi += $ipi_produto_valor;

					$valor_icms = round((floatval($valor_base_icms) / 100) * floatval($icms), 2);
					$valor_mva = ($st_base / 100) * $st_percentual;
					$valor_icms_st = ((int) $mva_aliquota) ? round($valor_mva - $valor_icms, 2) : 0;
					$pis_valor = round((floatval($valor_base_pis[$j]) / 100) * floatval(converteMoeda($pis_percentual[$j])), 2);
					$cofins_valor = round((floatval($valor_base_cofins[$j]) / 100) * floatval(converteMoeda($cofins_percentual[$j])), 2);

					$total_base_icms += $valor_base_icms;
					$total_icms += $valor_icms;
					$total_base_icms_st += $st_base ;
					$total_icms_st_valor_produto += $valor_icms_st;
					$total_valor_pis += $pis_valor;
					$total_valor_cofins += $cofins_valor;

					$vtrib = $valor_icms + $valor_icms_st + ((floatval($valor_base_pis[$j]) / 100) * floatval($pis_percentual[$j])) + ((floatval($valor_base_cofins[$j]) / 100) * floatval($cofins_percentual[$j])) + $ipi_produto_valor;

					$data_nota = array(
						'id_empresa' => sanitize(post('id_empresa')),
						'id_nota' => $id_nota,
						'id_cadastro' => sanitize(post('id_cadastro')),
						'id_produto' => $id_produto[$j],
						'produto_tipo_item' => $produto_tipo_item[$j],
						'id_produto_fornecedor' => $id_produto_fornecedor,
						'quantidade' => $qt,
						'cfop' => $cfop_produto[$j],
						'ncm' => $ncm[$j],
						'cest' => $cest[$j],
						'valor_unitario' => $vl,
						'valor_desconto' => $vd,
						'valor_total' => $total_produto,
						'valor_frete' => round($valor_frete_produto, 2),
						'valor_seguro' => round($valor_seguro_produto,2 ),
						'valor_total_trib' => round($vtrib, 2),
						'outrasDespesasAcessorias' => $vDespesas,
						'numero_pedido_compra' => $numero_pedido_compra,
						'item_pedido_compra' => $valor_item_pedido_compra,
						'icms_cst' => (!empty($icms_cst[$j]) && $icms_cst[$j] > 0) ? $icms_cst[$j] : getValue("icms_cst", "produto", "id=" . $id_produto[$j]),
						'icms_percentual' => $icms,
						'icms_base' => $icms == 0 || $icms == '' ? 0 : $valor_base_icms,
						'icms_valor' => round((floatval($valor_base_icms) / 100) * floatval($icms), 2),
						'icms_percentual_mva_st' => converteMoeda($mva_aliquota),
						'icms_st_base' => $st_percentual == 0 || $st_percentual == '' ? 0 : round($st_base, 2),
						'icms_st_percentual' => $st_percentual,
						'icms_st_valor' => $valor_icms_st,
						'pis_cst' => $pis_cst[$j] ? $pis_cst[$j] : $row_produto->pis_cst,
						'pis_base' => $pis_percentual[$j] == 0 || $pis_percentual[$j] == '' ? 0 : converteMoeda($valor_base_pis[$j]),
						'pis_percentual' => converteMoeda($pis_percentual[$j]),
						'pis_valor' => $pis_valor,
						'cofins_cst' => $cofins_cst[$j] ? $cofins_cst[$j] : $row_produto->cofins_cst,
						'cofins_base' => $cofins_percentual[$j] == 0 || $cofins_percentual[$j] == '' ? 0 : converteMoeda($valor_base_cofins[$j]),
						'cofins_percentual' => converteMoeda($cofins_percentual[$j]),
						'cofins_valor' => $cofins_valor,
						'ipi_cst' => $ipi_cst[$j] ? $ipi_cst[$j] : $row_produto->ipi_saida_codigo,
						'ipi_base' => $ipi_percentual[$j] == 0 || $ipi_percentual[$j] == '' ? 0 : converteMoeda($ipi_produto_base),
						'ipi_percentual' => converteMoeda($ipi_percentual[$j]),
						'ipi_valor' => round($ipi_produto_valor, 2),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("nota_fiscal_itens", $data_nota);

					if (!$id_venda || $id_venda == 0) {

						self::$db->update("produto_estoque", $data_limpar, "id_ref=" . $id_nota);

						$numero_nota = getValue("numero_nota", "nota_fiscal", "id=" . $id_nota);
						$obs_numero_nota = ($numero_nota > 0) ? 'NÚMERO: ' . $numero_nota : 'ID: ' . $id_nota;
						$obs = "NOTA FISCAL: [ " . $obs_numero_nota . " ]";

						$tipo = 2;
						$motivo = 3;
						$qt = $qt * (-1);

						$data_estoque = array(
							'id_empresa' => sanitize(post('id_empresa')),
							'id_produto' => $id_produto[$j],
							'quantidade' => $qt,
							'tipo' => $tipo,
							'motivo' => $motivo,
							'observacao' => $obs,
							'id_ref' => $id_nota,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->insert("produto_estoque", $data_estoque);

						$totalestoque = $this->getEstoqueTotal($id_produto[$j]);
						$data_update = array(
							'estoque' => $totalestoque,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("produto", $data_update, "id=" . $id_produto[$j]);

						$kit = getValue("kit", "produto", "id=" . $id_produto[$j]);

						if ($kit) {
							$nomekit = getValue("nome", "produto", "id=" . $id_produto[$j]);
							$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade "
								. "\n FROM produto_kit as k"
								. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
								. "\n WHERE k.id_produto_kit = $id_produto[$j] AND k.materia_prima=0"
								. "\n ORDER BY p.nome ";

							$retorno_row = self::$db->fetch_all($sql);

							if ($retorno_row) {
								foreach ($retorno_row as $exrow) {
									$observacao = str_replace("[ID_VENDA]", $id_nota, lang('VENDA_KIT_NF'));
									$observacao = str_replace("[NOME_KIT]", $nomekit, $observacao);
									$observacao = str_replace("[NOME_CLIENTE]", $nomecliente, $observacao);

									$quant_estoque_kit = $qt * $exrow->quantidade;

									$data_estoque = array(
										'id_empresa' => sanitize(post('id_empresa')),
										'id_produto' => $exrow->id_produto,
										'quantidade' => $quant_estoque_kit,
										'tipo' => 2,
										'motivo' => 3,
										'observacao' => $observacao,
										'id_ref' => $id_nota,
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									self::$db->insert("produto_estoque", $data_estoque);
									$totalestoque = $this->getEstoqueTotal($exrow->id_produto);
									$data_update = array(
										'estoque' => $totalestoque,
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									self::$db->update("produto", $data_update, "id=" . $exrow->id_produto);
								}
							}
						}
					}
				}

				//Este trecho de código serve para ajustar o valor do frete quando o mesmo for quebrado e gerar diferença no total.
				$novo_valor_frete = $this->obterFreteItensNota($id_nota);
				$frete_nota = round(getValue("valor_frete", "nota_fiscal", "id=" . $id_nota), 2);
				if ($novo_valor_frete->vlr_frete != $frete_nota) {
					$novo_frete = ($valor_frete - $novo_valor_frete->vlr_frete) + $novo_valor_frete->valor_frete;
					$novo_total_produto = ($novo_valor_frete->valor_total - $novo_valor_frete->valor_frete) + $novo_frete;
					$data_frete = array(
						'valor_total' => round($novo_total_produto, 2),
						'valor_frete' => round($novo_frete, 2),
					);
					self::$db->update("nota_fiscal_itens", $data_frete, "id=" . $novo_valor_frete->id);
				}

				//Este trecho de código serve para ajustar o valor do frete quando o mesmo for quebrado e gerar diferença no total.
				$novo_valor_seguro = $this->obterSeguroItensNota($id_nota);
				$seguro_nota = round(getValue("valor_seguro", "nota_fiscal", "id=" . $id_nota), 2);
				if ($novo_valor_seguro->vlr_seguro != $seguro_nota) {
					$novo_seguro = ($valor_seguro - $novo_valor_seguro->vlr_seguro) + $novo_valor_seguro->valor_seguro;
					$novo_total_produto = ($novo_valor_seguro->valor_total - $novo_valor_seguro->valor_seguro) + $novo_seguro;
					$data_seguro = array(
						'valor_total' => round($novo_total_produto, 2),
						'valor_seguro' => round($novo_seguro, 2)
					);
					self::$db->update("nota_fiscal_itens", $data_seguro, "id=" . $novo_valor_seguro->id);
				}

				/// ATUALIZAÇÃO DO CÁLCULO DE IPI NA NOTA FISCAL
				$data_ipi_nota = array(
					'valor_base_icms' => $total_base_icms,
					'valor_icms' => $total_icms,
					'valor_base_st' => $total_base_icms_st,
					'valor_ipi' => round($total_valor_ipi, 2),
					'valor_st' => $total_icms_st_valor_produto,
					'valor_pis' => $total_valor_pis,
					'valor_cofins' => $total_valor_cofins,
					'valor_nota' => round($valor_nota + $total_valor_ipi + $total_icms_st_valor_produto, 2)
				);
				self::$db->update("nota_fiscal", $data_ipi_nota, "id=" . $id_nota);

				$valor_imposto = round($total_valor_ipi + $total_icms_st_valor_produto, 2);
				$valor_nota = round($valor_nota + $total_valor_ipi + $total_icms_st_valor_produto, 2);
				$parcelas_receita_nota = $this->getQuantParcelaReceitaNota($id_nota);

				$quant_parcelas = floatval($parcelas_receita_nota->parcelas);
				$total_parcela = 0;
				$valor_parcela = 0;

				$sql_receita = "SELECT 'r' as tabela, id, valor, row_number() over (order by id) as parcela from receita WHERE id_nota = $id_nota AND inativo = 0
								UNION
								SELECT 'c' as tabela, id, valor_pago as valor, row_number() over (order by id) as parcela from cadastro_financeiro WHERE id_nota = $id_nota AND inativo = 0";
				$row_receita = self::$db->fetch_all($sql_receita);

				if ($valor_imposto > 0) {
					foreach ($row_receita as $rrow) {
						$valor_parcela = round($valor_nota / $quant_parcelas, 2);
						$id_parcela = $rrow->id;
						$total_parcela += $valor_parcela;

						$data_receita_nota = array(
							'valor' =>  $valor_parcela,
							'valor_pago' =>  $valor_parcela,
						);
						$data_financeiro_nota = array(
							'valor_pago' =>  $valor_parcela,
						);

						if ($rrow->tabela=='r')
							self::$db->update("receita", $data_receita_nota, "id = " . $id_parcela);
						else
							self::$db->update("cadastro_financeiro", $data_financeiro_nota, "id = " . $id_parcela);
					}

					if ($valor_nota != $total_parcela) {
						$diferança = $valor_nota - $total_parcela;
						$valor_parcela = round($valor_parcela + $diferança, 2);
						$data_receita_nota = array(
							'valor' =>  $valor_parcela,
							'valor_pago' =>  $valor_parcela,
						);
						self::$db->update("receita", $data_receita_nota, "id = " . $id_parcela);
					}
				}
			} else {
				self::$db->update("despesa", $data_limpar, "id_nota=" . $id_nota);

				$descricao = 'DESPESA AUTOMATICA DE NOTA FISCAL';
				$data_despesa = array(
					'id_empresa' => sanitize(post('id_empresa')),
					'id_cadastro' => sanitize(post('id_cadastro')),
					'id_nota' => $id_nota,
					'id_conta' => 30,
					'descricao' => $descricao,
					'tipo' => $tipo_pagamento,
					'pago' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);

				$vencimentos = stripTags('[', ']', $inf_adicionais);

				if ($vencimentos) {
					$vencimento_array = explode(';', $vencimentos);
					$count = count($vencimento_array);
					$i = 0;
					if ($count > 1) {
						while ($i < $count) {
							$v = $i + 1;
							$data_despesa['valor'] = converteMoeda(trim($vencimento_array[$v]));
							$data_despesa['valor_pago'] = converteMoeda(trim($vencimento_array[$v]));
							$data_despesa['data_vencimento'] = dataMySQL(trim($vencimento_array[$i]));
							self::$db->insert("despesa", $data_despesa);
							$i += 2;
						}
					} else {
						$vencimento_array = explode('/', $vencimentos);
						$count = count($vencimento_array);
						if ($count == 3)
							$data_vencimento = dataMySQL(trim($vencimentos));

						$data_despesa['valor'] = $valor_nota;
						$data_despesa['valor_pago'] = $valor_nota;
						$data_despesa['data_vencimento'] = $data_vencimento;
						self::$db->insert("despesa", $data_despesa);
					}
				} else {
					$data_despesa['valor'] = $valor_nota;
					$data_despesa['valor_pago'] = $valor_nota;
					$data_despesa['data_vencimento'] = $data_vencimento;
					self::$db->insert("despesa", $data_despesa);
				}

				$total_produto = 0;

				self::$db->update("nota_fiscal_itens", $data_limpar, "id_nota=" . $id_nota);

				for ($j = 0; $j < $cont_un; $j++) {
					$qt = converteMoeda($quantidade[$j]);
					$vl = converteMoeda($valor_unitario[$j]);
					$vd = converteMoeda($valor_desconto[$j]);
					$vDespesas = converteMoeda($valor_despesas[$j]);
					$icms = (!empty($icms_percentual[$j]) && $icms_percentual[$j] > 0) ? converteMoeda($icms_percentual[$j]) : converteMoeda($icms_normal_aliquota);
					$st_base = converteMoeda($icms_st_base[$j]);
					$st_percentual = (!empty($icms_st_percentual[$j]) && $icms_st_percentual[$j] > 0) ? converteMoeda($icms_st_percentual[$j]) : converteMoeda($icms_st_aliquota);
					$total_produto = round(($qt * $vl) - $vd + $vDespesas, 2);
					$id_cadastro = post('id_cadastro');
					$id_produto_fornecedor = getValue('id', 'produto_fornecedor', 'id_produto=' . $id_produto[$j] . ' AND id_cadastro=' . $id_cadastro);
					$mva_aliquota = (!empty($mva_produto[$j]) && $mva_produto[$j] > 0) ? $mva_produto[$j] : $mva;

					$data_nota = array(
						'id_empresa' => sanitize(post('id_empresa')),
						'id_nota' => $id_nota,
						'id_cadastro' => sanitize(post('id_cadastro')),
						'id_produto' => $id_produto[$j],
						'produto_tipo_item' => $produto_tipo_item[$j],
						'quantidade' => $qt,
						'cfop' => $cfop_produto[$j],
						'ncm' => $ncm[$j],
						'cest' => $cest[$j],
						'valor_unitario' => $vl,
						'valor_desconto' => $vd,
						'valor_total' => $total_produto,
						'outrasDespesasAcessorias' => $vDespesas,
						'icms_cst' => (!empty($icms_cst[$j]) && $icms_cst[$j] > 0) ? $icms_cst[$j] : getValue("icms_cst", "produto", "id=" . $id_produto[$j]),
						'icms_percentual' => $icms,
						'icms_percentual_mva_st' => $mva_aliquota,
						'icms_st_base' => $st_base ? round($st_base, 2) : round($valor_base_st, 2),
						'icms_st_percentual' => $st_percentual,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("nota_fiscal_itens", $data_nota);

					if (!$id_venda || $id_venda == 0) {

						self::$db->update("produto_estoque", $data_limpar, "id_ref=" . $id_nota);

						$numero_nota = getValue("numero_nota", "nota_fiscal", "id=" . $id_nota);
						$obs_numero_nota = ($numero_nota > 0) ? 'NÚMERO: ' . $numero_nota : 'ID: ' . $id_nota;
						$obs = "NOTA FISCAL: [ " . $obs_numero_nota . " ]";

						$tipo = 1;
						$motivo = 1;

						$data_estoque = array(
							'id_empresa' => sanitize(post('id_empresa')),
							'id_produto' => $id_produto[$j],
							'quantidade' => $qt,
							'tipo' => $tipo,
							'motivo' => $motivo,
							'observacao' => $obs,
							'id_ref' => $id_nota,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->insert("produto_estoque", $data_estoque);

						$totalestoque = $this->getEstoqueTotal($id_produto[$j]);
						$data_update = array(
							'estoque' => $totalestoque,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("produto", $data_update, "id=" . $id_produto[$j]);
					}
				}
			}
			if ($id_nota) {
				Filter::msgOk($message, "index.php?do=notafiscal&acao=visualizar&id=" . $id_nota);
			} else
				Filter::msgOk(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getNotaFiscal()
	 *
	 * @return
	 */
	public function getNotaFiscal($mes_ano = false, $id_empresa = false, $modelo = false, $operacao = false, $numero_nota = false, $ano = false)
	{
		$where = ($numero_nota) ? " WHERE numero_nota like '%$numero_nota%' " : " WHERE DATE_FORMAT(data_emissao, '%m/%Y') = '$mes_ano' ";
		$where = ($ano) ? " WHERE DATE_FORMAT(data_emissao, '%Y') = '$ano'" : $where;
		$wid_empresa = ($id_empresa) ? " AND n.id_empresa = $id_empresa " : "";
		$wmodelo = ($modelo) ? " AND n.modelo = $modelo " : "";
		$woperacao = ($operacao) ? " AND n.operacao = $operacao " : "";
		$sql = "SELECT n.*, c.nome, c.razao_social, e.nome as empresa, DATE_FORMAT(n.data_emissao, '%Y%m%d') as controle"
			. "\n FROM nota_fiscal as n"
			. "\n LEFT JOIN cadastro as c ON c.id = n.id_cadastro "
			. "\n LEFT JOIN empresa as e ON e.id = n.id_empresa "
			. "\n $where "
			. "\n $wid_empresa "
			. "\n $wmodelo "
			. "\n $woperacao ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getNotaFiscalChaveAcesso()
	 *
	 * @return
	 */
	public function getNotaFiscalChaveAcesso($mes_ano = false, $id_empresa = false, $operacao = false)
	{
		$wnota_id_empresa = ($id_empresa) ? " AND n.id_empresa = $id_empresa " : "";
		$wvenda_id_empresa = ($id_empresa) ? " AND v.id_empresa = $id_empresa " : "";
		$wnota_operacao = ($operacao) ? " AND n.operacao = $operacao " : "";
		$wvenda_operacao = ($operacao == '1') ? " AND v.id = -1 " : ""; //Não mostrar as vendas quando selecionar a operação de ENTRADA
		$sql = "SELECT n.operacao, n.numero, n.serie, n.id, n.status_enotas, n.inativo as inativo, n.numero_nota, n.id_cadastro, n.data_emissao as data_emissao, n.chaveacesso, n.link_danfe, n.link_download_xml, n.valor_nota as valor, n.usuario, n.fiscal, n.nome_arquivo, n.modelo, c.nome, e.nome as empresa, e.enotas, DATE_FORMAT(n.data_entrada, '%Y%m') as controle"
			. "\n FROM nota_fiscal as n"
			. "\n LEFT JOIN cadastro as c ON c.id = n.id_cadastro "
			. "\n LEFT JOIN empresa as e ON e.id = n.id_empresa "
			. "\n WHERE (n.inativo=0 OR n.fiscal>0) AND DATE_FORMAT(n.data_emissao, '%m/%Y') = '$mes_ano' "
			. "\n $wnota_id_empresa $wnota_operacao "
			. "\n UNION "
			. "\n SELECT '2' AS operacao, v.numero, v.serie, v.id, v.status_enotas, v.inativo as inativo, v.numero_nota, v.id_cadastro, v.data_emissao, v.chaveacesso, v.link_danfe, v.link_download_xml, (v.valor_total+v.valor_despesa_acessoria-v.valor_desconto) as valor, v.usuario, v.fiscal, '' AS nome_arquivo, '6' AS modelo, c.nome, e.nome as empresa, e.enotas, DATE_FORMAT(v.data_emissao, '%Y%m') as controle"
			. "\n FROM vendas as v"
			. "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro "
			. "\n LEFT JOIN empresa as e ON e.id = v.id_empresa "
			. "\n WHERE v.fiscal > 0 AND DATE_FORMAT(v.data_emissao, '%m/%Y') = '$mes_ano' "
			. "\n $wvenda_id_empresa $wvenda_operacao "
			. "\n ORDER BY 1, 2 ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getNFCInutilizadas()
	 *
	 * @return
	 */
	public function getNFCeInutilizadas()
	{
		$sql = "SELECT '2' AS operacao, v.numero, v.serie, v.id, v.numero_nota, v.id_cadastro, v.data_emissao, v.chaveacesso, v.valor_pago as valor, v.usuario, v.fiscal, '6' AS modelo, c.nome, e.nome as empresa, e.enotas, DATE_FORMAT(v.data_emissao, '%Y%m') as controle"
			. "\n FROM vendas as v"
			. "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro "
			. "\n LEFT JOIN empresa as e ON e.id = v.id_empresa "
			. "\n WHERE v.fiscal = 2 "
			. "\n ORDER BY 1, 2 ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getNFeInutilizadas()
	 *
	 * @return
	 */
	public function getNFeInutilizadas()
	{
		$sql = "SELECT '2' AS operacao, n.numero, n.serie, n.id, n.numero_nota, n.id_cadastro, n.data_emissao, n.chaveacesso, n.valor_nota as valor, n.usuario, n.fiscal, '2' AS modelo, c.nome, e.nome as empresa, e.enotas, DATE_FORMAT(n.data_emissao, '%Y%m') as controle"
			. "\n FROM nota_fiscal as n"
			. "\n LEFT JOIN cadastro as c ON c.id = n.id_cadastro "
			. "\n LEFT JOIN empresa as e ON e.id = n.id_empresa "
			. "\n WHERE n.fiscal = 2 "
			. "\n ORDER BY 1, 2 ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getListaMesNF()
	 *
	 * @return
	 */
	public function getListaMesNF()
	{
		$sql = "SELECT mes_ano, controle FROM "
			. "\n (SELECT DATE_FORMAT(n.data_emissao, '%m/%Y') as mes_ano, DATE_FORMAT(n.data_emissao, '%Y%m') as controle"
			. "\n FROM nota_fiscal as n"
			. "\n WHERE n.inativo = 0 "
			. "\n UNION "
			. "\n SELECT DATE_FORMAT(v.data_emissao, '%m/%Y') as mes_ano, DATE_FORMAT(v.data_emissao, '%Y%m') as controle"
			. "\n FROM vendas as v"
			. "\n WHERE v.fiscal = 1 AND v.inativo = 0) AS nf"
			. "\n GROUP BY mes_ano"
			. "\n ORDER BY controle DESC ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getNotaFiscalCadastro()
	 *
	 * @return
	 */
	public function getNotaFiscalCadastro($id_cadastro)
	{
		$sql = "SELECT n.*, c.nome, c.razao_social, e.nome as empresa, DATE_FORMAT(n.data_emissao, '%Y%m%d') as controle"
			. "\n FROM nota_fiscal as n"
			. "\n LEFT JOIN cadastro as c ON c.id = n.id_cadastro "
			. "\n LEFT JOIN empresa as e ON e.id = n.id_empresa "
			. "\n WHERE n.id_cadastro = $id_cadastro ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getNotaFiscalDetalhes()
	 *
	 * @return
	 */
	public function getNotaFiscalDetalhes($id_nota)
	{
		$sql = "SELECT n.*, c.nome, c.razao_social, e.nome as empresa, DATE_FORMAT(n.data_emissao, '%Y%m%d') as controle"
			. "\n FROM nota_fiscal as n"
			. "\n LEFT JOIN cadastro as c ON c.id = n.id_cadastro "
			. "\n LEFT JOIN empresa as e ON e.id = n.id_empresa "
			. "\n WHERE n.id = $id_nota ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarNotaFiscalCarta()
	 *
	 * @return
	 */
	public function processarNotaFiscalCarta()
	{
		if (empty($_POST['id_nota']) and empty($_POST['chaveacesso']))
			Filter::$msgs['chaveacesso'] = lang('MSG_ERRO_CHAVEACESSO');

		if (empty($_POST['id_nota']) and empty($_POST['id_empresa']))
			Filter::$msgs['id_empresa'] = lang('MSG_ERRO_EMPRESA');

		if (empty($_POST['numero']))
			Filter::$msgs['numero'] = lang('MSG_ERRO_NUMERO');

		if (empty(Filter::$msgs)) {

			$data = array(
				'id_nota' => sanitize(post('id_nota')),
				'id_empresa' => sanitize(post('id_empresa')),
				'chaveacesso' => sanitize(post('chaveacesso')),
				'numero' => sanitize(post('numero')),
				'correcao' => sanitize(post('correcao')),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->insert("nota_fiscal_carta", $data);

			if (self::$db->affected()) {
				Filter::msgOk(lang('NOTA_CARTA_ADICIONAR_OK'), "index.php?do=notafiscal&acao=carta&id_nota=" . post('id_nota'));
			} else
				Filter::msgOk(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getCartaCorrecao()
	 *
	 * @return
	 */
	public function getCartaCorrecao()
	{
		$sql = "SELECT c.*, e.nome as empresa, n.numero_nota, DATE_FORMAT(c.data, '%Y%m%d') as controle"
			. "\n FROM nota_fiscal_carta as c"
			. "\n LEFT JOIN nota_fiscal as n ON n.id = c.id_nota "
			. "\n LEFT JOIN empresa as e ON e.id = c.id_empresa ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::obterNumeroCartaCorrecao()
	 *
	 * @return
	 */
	public function obterNumeroCartaCorrecao($id_nota)
	{
		$sql = "SELECT count(id) as numero_carta FROM nota_fiscal_carta WHERE id_nota = $id_nota";
		$row = self::$db->first($sql);
		return ($row) ? $row->numero_carta + 1 : 1;
	}

	/**
	 * Produto::getDuplicatas()
	 *
	 * @return
	 */
	public function getDuplicatas($id_nota = false)
	{
		$where = ($id_nota) ? "AND id_nota = $id_nota " : "";
		$sql = "SELECT d.*, c.nome, c.razao_social, n.numero_nota, n.data_emissao, DATE_FORMAT(d.data_vencimento, '%Y%m%d') as controle"
			. "\n FROM despesa as d"
			. "\n LEFT JOIN cadastro as c ON c.id = d.id_cadastro "
			. "\n LEFT JOIN nota_fiscal as n ON n.id = d.id_nota "
			. "\n WHERE d.id_nota > 0 AND d.inativo = 0 $where "
			. "\n ORDER BY d.data_vencimento ASC ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getNumeroNota()
	 *
	 * @return
	 */
	public function getNumeroNota($id_cadastro)
	{
		$sql = "SELECT n.id, n.numero_nota "
			. "\n FROM nota_fiscal as n"
			. "\n WHERE n.id_cadastro = '$id_cadastro' "
			. "\n ORDER BY n.numero_nota ASC ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutosNota()
	 *
	 * @return
	 */
	public function getProdutosNota($id_nota = 0)
	{
		$sql = "SELECT n.*, p.anp, p.valor_partida, p.nome, p.codigo, p.cest as cest_produto, p.unidade_tributavel, p.ncm as ncm_produto, p.ipi_cst as ipi_produto, p.icms_cst as cst_produto, p.ipi_saida_codigo, nf.cfop as cfop_produto, p.cfop as cfop_produto_original, p.unidade as unidade_produto, f.nome as nome_fornecedor, f.quantidade_compra "
			. "\n FROM nota_fiscal_itens as n"
			. "\n LEFT JOIN produto_fornecedor as f ON f.id = n.id_produto_fornecedor"
			. "\n LEFT JOIN produto as p ON p.id = n.id_produto"
			. "\n LEFT JOIN nota_fiscal as nf ON nf.id = n.id_nota"
			. "\n WHERE n.inativo = 0 AND n.id_nota = $id_nota";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutosNotaDevolucao()
	 *
	 * @return
	 */
	public function getProdutosNotaDevolucao($id_nota = 0)
	{
		$sql = "SELECT n.*, f.nome as nome_fornecedor "
			. "\n FROM nota_fiscal_itens as n"
			. "\n LEFT JOIN produto_fornecedor as f ON f.id = n.id_produto_fornecedor"
			. "\n LEFT JOIN nota_fiscal as nf ON nf.id = n.id_nota"
			. "\n WHERE n.inativo = 0 AND n.id_nota = $id_nota";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getTipoItemProduto()
	 *
	 * @return
	 */
	public function getTipoItemProduto()
	{
		$sql = "SELECT  * FROM produto_tipo_item";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getTransporteNota()
	 *
	 * @return
	 */
	public function getTransporteNota($id_nota = 0)
	{
		$sql = "SELECT n.* "
			. "\n FROM nota_fiscal_transporte as n"
			. "\n WHERE n.id_nota = $id_nota";
		$row = self::$db->first($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getComprasProuto()
	 *
	 * @return
	 */
	public function totalProdutosNota($id_nota = 0)
	{
		$sql = "SELECT SUM(n.valor_total) as total"
			. "\n FROM nota_fiscal_itens as n"
			. "\n WHERE n.id_nota = $id_nota";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Produto::atualizarProdutoTabela($id_produto,$novo_valor_custo);
	 *
	 * @return
	 */
	public function atualizarProdutoTabela($id_produto, $novo_valor_custo)
	{
		$sql_produto_tabela = "SELECT * FROM produto_tabela WHERE id_produto=$id_produto";
		$row_produto_tabela = self::$db->fetch_all($sql_produto_tabela);

		if ($row_produto_tabela) {
			foreach ($row_produto_tabela as $rowpt) {
				$percentual_tabela = getValue("percentual", "tabela_precos", "id=" . $rowpt->id_tabela);
				$percentual_produto = getValue("percentual", "produto_tabela", "id=" . $rowpt->id);
				$percentual = ($percentual_tabela > 0) ? $percentual_tabela : $percentual_produto;
				$valor_atual_venda_produto = getValue("valor_venda", "produto_tabela", "id=" . $rowpt->id);
				$valor_novo_venda_produto = round(($novo_valor_custo + ($novo_valor_custo * ($percentual / 100))), 2);
				$atualizar_valor_produto = getValue('atualizar_valor_produto', 'empresa', "'inativo'=0");

				if ($valor_novo_venda_produto > $valor_atual_venda_produto) {
					$data_produto_tabela = array(
						'valor_venda' => $valor_novo_venda_produto,
						'percentual' => $percentual
					);
				} else {
					$percentual_novo = (($valor_atual_venda_produto / $novo_valor_custo) - 1) * 100;
					$data_produto_tabela = array(
						'valor_venda' => $valor_atual_venda_produto,
						'percentual' => $percentual_novo
					);
				}

				if (!$atualizar_valor_produto)
					unset($data_produto_tabela['valor_venda']);

				self::$db->update("produto_tabela", $data_produto_tabela, "id=" . $rowpt->id);
			}
		}
	}

	/**
	 * Produto::processarEstoque()
	 * MOTIVO
	 * COMPRA: 1
	 * TRANSFERENCIA: 2
	 * VENDA: 3
	 * CONSUMO: 4
	 * PERDA: 5
	 * AJUSTE: 6
	 * CANCELAMENTO/DEVOLUÇÃO DE VENDA: 7
	 * PRODUCAO: 8
	 * ECOMMERCE: 9
	 * MARKETING/PROPAGANDA/DEMONSTRACAO: 10
	 *
	 * TIPO
	 * ENTRADA: 1
	 * SAIDA: 2
	 *
	 * @return
	 */
	public function processarEstoque()
	{
		$id_produto = 0;
		$quantidade = 0;
		$kit = 0;
		$tipo = sanitize($_POST['tipo']);
		$estoque_gerar_despesa = (isset($_POST['estoque_gerar_despesa'])) ? sanitize($_POST['estoque_gerar_despesa']) : 0;

		if ($estoque_gerar_despesa) {
			if (empty($_POST['id_conta']))
				Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');

			if (empty($_POST['id_cadastro']))
				Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_CLIENTE');

			if (empty($_POST['tipo_pagamento']))
				Filter::$msgs['tipo_pagamento'] = lang('MSG_ERRO_TIPO_PAGAMENTO');

			if (empty($_POST['valor']))
				Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');
		}

		if (empty($_POST['id_produto']))
			Filter::$msgs['id_produto'] = lang('MSG_ERRO_PRODUTO');
		else
			$id_produto = post('id_produto');

		if (empty($_POST['quantidade']))
			Filter::$msgs['quantidade'] = lang('MSG_ERRO_QUANTIDADE');
		else
			$quantidade = converteMoeda($_POST['quantidade']);

		if ($id_produto && $quantidade && $tipo == "1") {
			$kit = getValue("kit", "produto", "id=" . $id_produto);
			if ($kit) {
				$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade, p.valida_estoque "
					. "\n FROM produto_kit as k"
					. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
					. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=1"
					. "\n ORDER BY p.nome ";
				$retorno_row = self::$db->fetch_all($sql);
				if ($retorno_row) {
					$i = 1;
					foreach ($retorno_row as $exrow) {
						if ($exrow->valida_estoque && ($quantidade * $exrow->quantidade) > $exrow->estoque) {
							$msg_erro_estoque = str_replace("[PRODUTO]", $exrow->nome, lang('MSG_ERRO_ESTOQUE_KIT'));
							$msg_erro_estoque = str_replace("[ESTOQUE]", $exrow->estoque, $msg_erro_estoque);
							Filter::$msgs[$i++ . 'estoque' . $exrow->id] = $msg_erro_estoque;
						}
					}
				}
			}
		}

		if (empty($_POST['motivo']))
			Filter::$msgs['motivo'] = lang('MSG_ERRO_MOTIVO');

		if (empty($_POST['observacao']))
			Filter::$msgs['observacao'] = lang('MSG_ERRO_OBSERVACAO');

		$novo_valor_custo = (empty($_POST['novo_valor_custo'])) ? 0.000 : converteMoeda($_POST['novo_valor_custo']);
		$novo_valor_custo = ($novo_valor_custo < 0) ? 0.000 : $novo_valor_custo;

		if (empty(Filter::$msgs)) {

			if ($tipo == "2") {
				$quantidade = $quantidade * (-1);
			}

			$data = array(
				'id_empresa' => session('idempresa'),
				'id_produto' => sanitize(post('id_produto')),
				'quantidade' => $quantidade,
				'tipo' => sanitize(post('tipo')),
				'motivo' => sanitize(post('motivo')),
				'observacao' => sanitize(post('observacao')),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			self::$db->insert("produto_estoque", $data);

			$totalestoque = $this->getEstoqueTotal(post('id_produto'));
			$data_update = array(
				'estoque' => $totalestoque,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			if ($novo_valor_custo > 0) {
				$data_update['valor_custo'] = $novo_valor_custo;
				$this->atualizarProdutoTabela(post('id_produto'), $novo_valor_custo);
			}

			self::$db->update("produto", $data_update, "id=" . post('id_produto'));

			if (self::$db->affected()) {

				if ($estoque_gerar_despesa) {

					$data_despesa = array(
						'id_empresa' => session('idempresa'),
						'id_conta' => sanitize(post('id_conta')),
						'id_custo' => sanitize(post('id_custo')),
						'id_banco' => sanitize(post('id_banco')),
						'id_cadastro' => sanitize(post('id_cadastro')),
						'tipo' => sanitize(post('tipo_pagamento')),
						'descricao' => motivo(sanitize(post('motivo'))).' - '.sanitize(post('observacao')),
						'valor' => converteMoeda($_POST['valor']),
						'valor_pago' => converteMoeda($_POST['valor']),
						'data_vencimento' => "NOW()",
						'pago' => 1,
						'data_pagamento' => "NOW()",
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("despesa", $data_despesa);
				}

				$message = lang('ESTOQUE_MOVIMENTO_OK');

				if ($tipo == "1") {
					if ($kit) {
						$nomekit = getValue("nome", "produto", "id=" . $id_produto);
						$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade "
							. "\n FROM produto_kit as k"
							. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
							. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=1"
							. "\n ORDER BY p.nome ";
						$retorno_row = self::$db->fetch_all($sql);
						if ($retorno_row) {
							foreach ($retorno_row as $exrow) {
								$observacao = lang('PRODUTO_KIT_ESTOQUE') . $nomekit;
								$quant_estoque_kit = $quantidade * $exrow->quantidade * (-1);

								$data_estoque = array(
									'id_empresa' => session('idempresa'),
									'id_produto' => $exrow->id_produto,
									'quantidade' => $quant_estoque_kit,
									'tipo' => 2,
									'motivo' => sanitize(post('motivo')),
									'observacao' => $observacao,
									'id_ref' => 0,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->insert("produto_estoque", $data_estoque);
								$totalestoque = $this->getEstoqueTotal($exrow->id_produto);

								$data_update = array(
									'estoque' => $totalestoque,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->update("produto", $data_update, "id=" . $exrow->id_produto);
							}
						}
					}
					Filter::msgOk($message, "index.php?do=estoque&acao=adicionar");
				} else {
					Filter::msgOk($message, "index.php?do=estoque&acao=retirada");
				}
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::processarTransferenciaEstoque()
	 *
	 * @return
	 */
	public function processarTransferenciaEstoque()
	{
		if (empty($_POST['id_produto']))
			Filter::$msgs['id_produto'] = lang('MSG_ERRO_PRODUTO');

		if (empty($_POST['quantidade']))
			Filter::$msgs['quantidade'] = lang('MSG_ERRO_QUANTIDADE');

		if (empty($_POST['id_empresa_origem']))
			Filter::$msgs['id_empresa_origem'] = lang('MSG_ERRO_EMPRESA_ORIGEM');

		if (empty($_POST['id_empresa_destino']))
			Filter::$msgs['id_empresa_destino'] = lang('MSG_ERRO_EMPRESA_DESTINO');

		if (post('id_empresa_origem') == post('id_empresa_destino'))
			Filter::$msgs['id_empresa_destino'] = lang('MSG_ERRO_EMPRESA_DESTINO');

		if (empty(Filter::$msgs)) {

			$empresa_origem = getValue("nome", "empresa", "id = " . $_POST['id_empresa_origem']);
			$empresa_destino = getValue("nome", "empresa", "id = " . $_POST['id_empresa_destino']);
			$entrada = converteMoeda($_POST['quantidade']);
			$saida = $entrada * (-1);
			$obs_entrada = "ENTRADA PARA: [" . $empresa_destino . "] DE: [" . $empresa_origem . "]";
			$obs_saida = "SAIDA DE: [" . $empresa_origem . "] PARA: [" . $empresa_destino . "]";

			$data_saida = array(
				'id_empresa' => sanitize($_POST['id_empresa_origem']),
				'id_produto' => sanitize($_POST['id_produto']),
				'quantidade' => $saida,
				'tipo' => 2,
				'motivo' => 2,
				'observacao' => $obs_saida,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			$data_entrada = array(
				'id_empresa' => sanitize($_POST['id_empresa_destino']),
				'id_produto' => sanitize($_POST['id_produto']),
				'quantidade' => $entrada,
				'tipo' => 1,
				'motivo' => 2,
				'observacao' => $obs_entrada,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			self::$db->insert("produto_estoque", $data_saida);
			self::$db->insert("produto_estoque", $data_entrada);
			$message = lang('ESTOQUE_MOVIMENTO_OK');

			if (self::$db->affected()) {

				Filter::msgOk($message, "index.php?do=estoque&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getTodosEstoque()
	 *
	 * @return
	 */
	public function getTodosEstoque($id_grupo = false, $id_categoria = false, $id_fabricante = false, $estoque_minimo = false)
	{
		$minimo = ($estoque_minimo) ? ' AND (p.estoque_minimo >= p.estoque AND p.estoque_minimo>0) ' : '';
		$wgrupo = ($id_grupo) ? "AND p.id_grupo = " . $id_grupo : "";
		$wcategoria = ($id_categoria) ? "AND p.id_categoria = " . $id_categoria : "";
		$wfabricante = ($id_fabricante) ? "AND p.id_fabricante = " . $id_fabricante : "";
		$sql = "SELECT p.id, p.nome, p.codigo, p.estoque, p.estoque_minimo, p.codigobarras, g.grupo "
			. "\n FROM produto as p"
			. "\n LEFT JOIN grupo as g ON g.id = p.id_grupo"
			. "\n WHERE p.inativo = 0 "
			. "\n $wgrupo "
			. "\n $wcategoria "
			. "\n $wfabricante "
			. "\n $minimo ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getHistoricoEstoque()
	 *
	 * @return
	 */
	public function getHistoricoEstoque($id_produto = false, $dataini = false, $datafim = false)
	{
		$wdata = ($dataini) ? " AND e.data BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') " : "";
		$wproduto = ($id_produto) ? " AND e.id_produto = $id_produto " : "";
		$sql = "SELECT e.*, p.codigo, p.nome as produto, em.nome as empresa, e.inativo_por_substituicao, p.estoque "
			. "\n FROM produto_estoque as e"
			. "\n LEFT JOIN produto as p ON p.id = e.id_produto"
			. "\n LEFT JOIN empresa as em ON em.id = e.id_empresa"
			. "\n WHERE (e.inativo = 0 OR e.inativo_por_substituicao = 1) $wdata $wproduto "
			. "\n ORDER BY e.id DESC ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getMovimentacaoEstoque()
	 *
	 * @return
	 */
	public function getMovimentacaoEstoque($dataini = false, $datafim = false)
	{
		$wdata = ($dataini) ? " AND e.data BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') " : "";

		$sql = "SELECT	e.id_produto,
				    	p.nome AS produto,
				    	SUM(CASE WHEN e.motivo = 1 THEN e.quantidade ELSE 0 END) AS compra,
				    	SUM(CASE WHEN e.motivo = 2 THEN e.quantidade ELSE 0 END) AS transferencia,
				    	SUM(CASE WHEN e.motivo = 3 THEN e.quantidade ELSE 0 END) AS venda,
				    	SUM(CASE WHEN e.motivo = 4 THEN e.quantidade ELSE 0 END) AS consumo,
				    	SUM(CASE WHEN e.motivo = 5 THEN e.quantidade ELSE 0 END) AS perda,
				    	SUM(CASE WHEN e.motivo = 6 THEN e.quantidade ELSE 0 END) AS ajuste,
				    	SUM(CASE WHEN e.motivo = 7 THEN e.quantidade ELSE 0 END) AS cancelamento,
				    	SUM(e.quantidade) AS quantidade_total,
    					SUM(p.estoque) AS estoque

				FROM		produto_estoque AS e
				LEFT JOIN 	produto 		AS p ON p.id = e.id_produto

				WHERE	(e.inativo = 0 OR e.inativo_por_substituicao = 1)
				$wdata

				GROUP BY	e.id_produto
				ORDER BY	p.nome ASC";

		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::customedio()
	 *
	 * @return
	 */
	public function customedio($id_produto)
	{
		$sql = "SELECT SUM(custo_produto) as total, COUNT(1) as quant"
			. "\n FROM produto_fornecedor"
			. "\n WHERE id_produto = " . $id_produto
			. "\n GROUP BY id_produto";
		$row = self::$db->first($sql);

		return ($row) ? $row->total / $row->quant : 0;
	}

	/**
	 * Produto::statusEstoque()
	 *
	 * @return
	 */
	public function statusEstoque($curvaabc, $temporeposicao, $consumo_medio, $quantidade, $custo_medio)
	{
		$retorno = array();
		$consumodiario = $consumo_medio / 90;
		$lotecompra = $temporeposicao * $consumodiario; //O lote de compra pode ser alterado manualmente.
		if ($curvaabc == "A")
			$estoquesegurancadias = Registry::get("Core")->produto_a;
		elseif ($curvaabc == "B")
			$estoquesegurancadias = Registry::get("Core")->produto_b;
		else
			$estoquesegurancadias = Registry::get("Core")->produto_c;
		$estoquesegurancaun = $estoquesegurancadias * $consumodiario;
		$estoquemaximo = $lotecompra + ($estoquesegurancadias * $consumodiario);
		$estoqueminimo = $estoquesegurancaun + ($temporeposicao * $consumodiario);
		$estoquereposicao = $lotecompra + $estoquesegurancaun;
		if ($quantidade > $estoquemaximo) {
			$status = lang('ESTOQUE_EXCESSO');
		} elseif ($quantidade > $estoqueminimo * 0.98 and $quantidade < $estoqueminimo * 1.02)
			$status = lang('ESTOQUE_IDEAL');
		elseif ($quantidade > $estoqueminimo)
			$status = lang('ESTOQUE_NORMAL');
		elseif ($quantidade < $estoqueminimo and $quantidade > $estoquesegurancaun)
			$status = lang('ESTOQUE_TRABALHO');
		elseif ($quantidade > 0)
			$status = lang('ESTOQUE_CRITICO');
		else
			$status = lang('ESTOQUE_ZERADO');
		$status = ($consumodiario) ? $status : lang('ESTOQUE_SEM_SAIDA');

		$retorno['status'] = $status;
		$retorno['custo_medio'] = $custo_medio;
		$retorno['quantcompra'] = $estoquereposicao;
		$retorno['valorcompra'] = $estoquereposicao * $custo_medio;
		$retorno['estoqueatual'] = $quantidade;
		$retorno['estoqueminimo'] = $estoqueminimo;
		$retorno['valorexcesso'] = ($quantidade > $estoquemaximo) ? ($quantidade - $estoquemaximo) * $custo_medio : 0;
		$retorno['valorideal'] = $estoqueminimo * $custo_medio;
		$retorno['valoratual'] = $quantidade * $custo_medio;

		return $retorno;
	}

	/**
	 * Produto::getDataCompra()
	 *
	 * @return
	 */
	public function getDataCompra($id_produto = 0)
	{
		$sql = "SELECT  MAX(n.data_emissao) as data_nota"
			. "\n FROM nota_fiscal_itens as p "
			. "\n LEFT JOIN nota_fiscal as n ON n.id = p.id_nota"
			. "\n WHERE p.id_produto = $id_produto ";
		$row = self::$db->first($sql);

		return ($row) ? $row->data_nota : 0;
	}

	/**
	 * Produto::getTotalProdutoNFST()
	 *
	 * @return
	 */
	public function getTotalProdutoNFST($id_nota = 0)
	{
		$sql = "SELECT SUM(n.valor_total) as total"
			. "\n FROM nota_fiscal_itens as n "
			. "\n LEFT JOIN produto as p ON p.id = n.id_produto "
			. "\n LEFT JOIN cfop as c ON c.cfop = p.cfop"
			. "\n WHERE c.st = 1 AND n.id_nota = '$id_nota' ";
		$row = self::$db->first($sql);
		return ($row) ? $row->total : 0;
	}

	/**
	 * Produto::getTotalProdutoNFEST()
	 *
	 * @return
	 */
	public function getTotalProdutoNFEST($id_venda = 0)
	{
		$sql = "SELECT SUM(cv.valor_total) as total"
			. "\n FROM cadastro_vendas as cv "
			. "\n LEFT JOIN produto as p ON p.id = cv.id_produto "
			. "\n LEFT JOIN cfop as c ON c.cfop = p.cfop"
			. "\n WHERE c.st = 1 AND cv.id_venda = '$id_venda' ";
		$row = self::$db->first($sql);
		return ($row) ? $row->total : 0;
	}

	/**
	 * Produto::getTotalProdutoNFTributado()
	 *
	 * @return
	 */
	public function getTotalProdutoNFTributado($id_nota = 0)
	{
		$sql = "SELECT SUM(n.valor_total) as total"
			. "\n FROM nota_fiscal_itens as n "
			. "\n LEFT JOIN produto as p ON p.id = n.id_produto "
			. "\n LEFT JOIN cfop as c ON c.cfop = p.cfop"
			. "\n WHERE c.tributado = 1 AND n.id_nota = '$id_nota' ";
		$row = self::$db->first($sql);
		return ($row) ? $row->total : 0;
	}

	/**
	 * Produto::getTotalProdutoNFETributado()
	 *
	 * @return
	 */
	public function getTotalProdutoNFETributado($id_venda = 0)
	{
		$sql = "SELECT SUM(cv.valor_total) as total"
			. "\n FROM cadastro_vendas as cv "
			. "\n LEFT JOIN produto as p ON p.id = cv.id_produto "
			. "\n LEFT JOIN cfop as c ON c.cfop = p.cfop"
			. "\n WHERE c.tributado = 1 AND cv.id_venda = '$id_venda' ";
		$row = self::$db->first($sql);
		return ($row) ? $row->total : 0;
	}

	/**
	 * Produto::getPrimeiraTabelaPrecosCadastrada()
	 *
	 * @return
	 */
	public function getPrimeiraTabelaPrecosCadastrada()
	{
		$sql = "SELECT id FROM tabela_precos WHERE inativo=0";
		$row = self::$db->first($sql);
		return ($row) ? $row->id : 0;
	}

	/**
	 * Produto::getTabela()
	 *
	 * @return
	 */
	public function getTabela($id, $id_grupo = false, $id_categoria = false, $id_fabricante = false)
	{
		$wgrupo = ($id_grupo) ? "AND p.id_grupo = " . $id_grupo : "";
		$wcategoria = ($id_categoria) ? "AND p.id_categoria = " . $id_categoria : "";
		$wfabricante = ($id_fabricante) ? "AND p.id_fabricante = " . $id_fabricante : "";
		$sql = "SELECT t.*, p.nome, p.valida_estoque, p.imagem, p.codigo, p.codigobarras, p.prazo_troca, p.valor_custo, p.estoque, p.detalhamento, p.unidade, p.descricao_unidade, p.inativo, c.categoria,c.id as id_categoria, g.grupo, g.id as id_grupo, fa.fabricante, fa.id as id_fabricante "
			. "\n FROM produto_tabela as t"
			. "\n LEFT JOIN produto AS p ON p.id = t.id_produto"
			. "\n LEFT JOIN categoria AS c ON c.id = p.id_categoria"
			. "\n LEFT JOIN grupo AS g ON g.id = p.id_grupo"
			. "\n LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante"
			. "\n WHERE t.id_tabela = $id AND p.inativo = 0 AND p.grade = 1 "
			. "\n $wgrupo "
			. "\n $wcategoria "
			. "\n $wfabricante "
			. "\n ORDER BY p.nome ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getTabelaAppVendas()
	 *
	 * @return
	 */
	public function getTabelaAppVendas($id, $per_page, $page, $busca = "", $codigobarras = "", $id_grupo = false, $id_categoria = false, $id_fabricante = false)
	{
		$wgrupo = ($id_grupo) ? "AND p.id_grupo = " . $id_grupo : "";
		$wcategoria = ($id_categoria) ? "AND p.id_categoria = " . $id_categoria : "";
		$wfabricante = ($id_fabricante) ? "AND p.id_fabricante = " . $id_fabricante : "";
		$wbusca = ($busca) ? "AND p.nome LIKE '%$busca%' " : "";
		$wcodigobarras = ($codigobarras) ? "AND p.codigobarras = '$codigobarras' " : "";
		$sql = "SELECT t.*, p.nome, p.valida_estoque, p.imagem, p.codigo, p.codigobarras, p.prazo_troca, p.valor_custo, p.estoque, p.detalhamento, p.unidade, p.descricao_unidade, p.inativo, c.categoria,c.id as id_categoria, g.grupo, g.id as id_grupo, fa.fabricante, fa.id as id_fabricante "
			. "\n FROM produto_tabela as t"
			. "\n LEFT JOIN produto AS p ON p.id = t.id_produto"
			. "\n LEFT JOIN categoria AS c ON c.id = p.id_categoria"
			. "\n LEFT JOIN grupo AS g ON g.id = p.id_grupo"
			. "\n LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante"
			. "\n WHERE t.id_tabela = $id AND p.inativo = 0 AND p.grade = 1 "
			. "\n AND (p.valida_estoque = 0 || estoque > 0) "
			. "\n $wgrupo "
			. "\n $wcategoria "
			. "\n $wfabricante "
			. "\n $wbusca "
			. "\n $wcodigobarras "
			. "\n ORDER BY p.nome LIMIT $per_page OFFSET $page";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getTabelaProdutosPromo()
	 *
	 * @return
	 */
	public function getTabelaProdutosPromo($id)
	{
		$sql = "SELECT t.*, p.nome, p.valida_estoque, p.imagem, p.codigo, p.codigobarras, p.prazo_troca, p.valor_custo, p.estoque, p.detalhamento, p.unidade, p.descricao_unidade, p.inativo, c.categoria,c.id as id_categoria, g.grupo, g.id as id_grupo, fa.fabricante, fa.id as id_fabricante "
			. "\n FROM produto_tabela as t"
			. "\n LEFT JOIN produto AS p ON p.id = t.id_produto"
			. "\n LEFT JOIN categoria AS c ON c.id = p.id_categoria"
			. "\n LEFT JOIN grupo AS g ON g.id = p.id_grupo"
			. "\n LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante"
			. "\n WHERE t.id_tabela = $id AND p.inativo = 0 AND p.grade = 1 "
			. "\n AND (p.valida_estoque = 0 || estoque > 0) "
			. "\n ORDER BY p.nome ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}


	/**
	 * Produto::getTabelaLista()
	 *
	 * @return
	 */
	public function getTabelaLista($id, $lista)
	{
		$sql = "SELECT t.*, p.nome, p.codigo, p.codigobarras, p.prazo_troca, p.valor_custo, p.estoque, p.detalhamento, c.categoria, g.grupo, fa.fabricante "
			. "\n FROM produto_tabela as t"
			. "\n LEFT JOIN produto AS p ON p.id = t.id_produto"
			. "\n LEFT JOIN categoria AS c ON c.id = p.id_categoria"
			. "\n LEFT JOIN grupo AS g ON g.id = p.id_grupo"
			. "\n LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante"
			. "\n WHERE t.id_tabela = $id AND p.inativo = 0 AND p.grade = 1 AND t.id_produto in ($lista) "
			. "\n ORDER BY p.nome";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getTabelaPrecos()
	 *
	 * @return
	 */
	public function getTabelaPrecos()
	{
		$sql = "SELECT t.* "
			. "\n FROM tabela_precos as t"
			. "\n WHERE t.inativo = 0 "
			. "\n ORDER BY t.tabela";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getTabelaNivel()
	 *
	 * @return
	 */
	public function getTabelaNivel($nivel)
	{
		$sql = "SELECT t.*"
			. "\n FROM tabela_precos as t"
			. "\n WHERE t.inativo = 0 AND t.nivel <= $nivel"
			. "\n ORDER BY t.principal_pdv DESC";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}


	/**
	 * Produto::processarTabelaPreco()
	 *
	 * @return
	 */
	public function processarTabelaPreco()
	{
		if (empty($_POST['tabela']))
			Filter::$msgs['tabela'] = lang('MSG_ERRO_NOME');

		if (empty(Filter::$msgs)) {
			$principal_pdv = sanitize(post('principal_pdv'));
			if ($principal_pdv && Filter::$id) {
				$data_tabela_pdv = array(
					'principal_pdv' => 0
				);
				self::$db->update("tabela_precos", $data_tabela_pdv, "principal_pdv=1");
			}

			$exibir_app = sanitize(post('exibir_app'));
			if ($exibir_app && Filter::$id) {
				$data_tabela_app = array(
					'aplicativo' => 0
				);
				self::$db->update("tabela_precos", $data_tabela_app, "aplicativo=1");
			}

			$data = array(
				'tabela' => sanitize(post('tabela')),
				'quantidade' => sanitize(post('quantidade')),
				'desconto' => converteMoeda(post('desconto')),
				'percentual' => converteMoeda(post('percentual')),
				'nivel' => sanitize(post('nivel')),
				'principal_pdv' => ($principal_pdv) ? 1 : 0,
				'appvendas' => sanitize(post('appvendas')),
				'aplicativo' => ($exibir_app) ? 1 : 0,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			(Filter::$id) ? self::$db->update("tabela_precos", $data, "id=" . Filter::$id) : self::$db->insert("tabela_precos", $data);
			$message = (Filter::$id) ? lang('TABELA_PRECO_AlTERADO_OK') : lang('TABELA_PRECO_ADICIONADO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=tabela&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::adicionarGrade()
	 *
	 * @return
	 */
	public function adicionarGrade()
	{
		$id_produto = post('id_produto');
		$total = 0;
		$quant = count($id_produto);

		if ($quant == 0)
			Filter::$msgs['quantidade'] = "Quantidade de produtos igual a 0.";

		if (empty(Filter::$msgs)) {

			$data = array(
				'grade' => 1,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			for ($i = 0; $i < $quant; $i++) {
				self::$db->update("produto", $data, "id=" . $id_produto[$i]);
			}
			if (self::$db->affected()) {
				Filter::msgOk(lang('PRODUTO_AlTERADO_OK'), "index.php?do=produto&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::limparGradeVendas()
	 *
	 * @return
	 */
	public function limparGradeVendas()
	{
		$id_produto = post('id_produto');
		$total = 0;
		$quant = count($id_produto);

		if ($quant == 0)
			Filter::$msgs['quantidade'] = "Quantidade de produtos igual a 0.";

		if (empty(Filter::$msgs)) {

			$data = array(
				'grade' => 0,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			for ($i = 0; $i < $quant; $i++) {
				self::$db->update("produto", $data, "id=" . $id_produto[$i]);
			}
			if (self::$db->affected()) {
				Filter::msgOk(lang('PRODUTO_AlTERADO_OK'), "index.php?do=produto&acao=listar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getProdutosGrade()
	 *
	 * @return
	 */
	public function getProdutosGrade($id_grupo = false, $id_categoria = false, $id_fabricante = false)
	{
		$wgrupo = ($id_grupo) ? "AND p.id_grupo = " . $id_grupo : "";
		$wcategoria = ($id_categoria) ? "AND p.id_categoria = " . $id_categoria : "";
		$wfabricante = ($id_fabricante) ? "AND p.id_fabricante = " . $id_fabricante : "";
		$sql = "SELECT p.*, c.categoria, g.grupo, fa.fabricante "
			. "\n FROM produto as p"
			. "\n LEFT JOIN categoria AS c ON c.id = p.id_categoria"
			. "\n LEFT JOIN grupo AS g ON g.id = p.id_grupo"
			. "\n LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante"
			. "\n WHERE p.inativo = 0 AND p.grade = 1 "
			. "\n $wgrupo "
			. "\n $wcategoria "
			. "\n $wfabricante "
			. "\n ORDER BY p.nome";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutosAnaliseEstoque()
	 *
	 * @return
	 */
	public function getProdutosAnaliseEstoque()
	{

		$sql = "SELECT
            p.id,
            p.nome,
            p.codigobarras,
			p.unidade,
			p.estoque,
            p.valor_custo,
			p.grade,
			g.grupo,
			c.categoria,
            COALESCE(SUM(e.quantidade), 0) AS estoque_total
        FROM produto p
        LEFT JOIN produto_estoque e ON e.id_produto = p.id
		LEFT JOIN grupo AS g ON g.id = p.id_grupo
		LEFT JOIN categoria AS c ON c.id = p.id_categoria
		WHERE p.inativo = 0
        GROUP BY p.id, p.nome, p.codigobarras, p.valor_custo
        ORDER BY p.nome";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutosEntradaRetirada()
	 *
	 * @return
	 */
	public function getProdutosEntradaRetirada($id_grupo = false, $id_categoria = false, $id_fabricante = false)
	{
		$wgrupo = ($id_grupo) ? "AND p.id_grupo = " . $id_grupo : "";
		$wcategoria = ($id_categoria) ? "AND p.id_categoria = " . $id_categoria : "";
		$wfabricante = ($id_fabricante) ? "AND p.id_fabricante = " . $id_fabricante : "";
		$sql = "SELECT p.*, c.categoria, g.grupo, fa.fabricante
				FROM produto as p
				LEFT JOIN categoria AS c ON c.id = p.id_categoria
				LEFT JOIN grupo AS g ON g.id = p.id_grupo
				LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante
				WHERE p.inativo = 0
				$wgrupo
				$wcategoria
				$wfabricante
				ORDER BY p.nome";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getGradeNotaFiscal()
	 *
	 * @return
	 */
	public function getGradeNotaFiscal()
	{
		$sql = "SELECT n.id, n.numero_nota, n.data_emissao"
			. "\n FROM nota_fiscal as n"
			. "\n ORDER BY n.data_emissao DESC ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::buscarProdutos()
	 *
	 * @return
	 */
	public function buscarProdutos()
	{
		$id = post('id');
		$percentual = getValue('percentual', 'tabela_precos', 'id=' . $id);
		$percentual_venda = 1 + ($percentual / 100);
		$wproduto = (empty($_POST['id_produto'])) ? "" : " AND id = " . post('id_produto');
		$wgrupo = (empty($_POST['id_grupo'])) ? "" : " AND id_grupo = " . post('id_grupo');
		$wcategoria = (empty($_POST['id_categoria'])) ? "" : " AND id_categoria = " . post('id_categoria');
		$wfabricante = (empty($_POST['id_fabricante'])) ? "" : " AND id_fabricante = " . post('id_fabricante');
		$nomeusuario = session('nomeusuario');
		$sql = "INSERT IGNORE INTO produto_tabela SELECT 0, $id, id, (valor_custo*$percentual_venda), $percentual, '$nomeusuario', NOW() FROM produto WHERE inativo = 0 AND grade = 1 $wproduto $wgrupo $wcategoria $wfabricante;";
		self::$db->query($sql);
		if (self::$db->affected()) {
			print Filter::msgOk(lang('TABELA_PRECO_PRODUTOS_OK'), "index.php?do=tabela&acao=tabela&id=" . $id);
		} else {
			print Filter::msgAlert(lang('TABELA_PRECO_PRODUTOS_NAO_REGISTROS'));
		}
	}

	/**
	 * Produto::processarAlterarTodos()
	 *
	 * @return
	 */
	public function processarAlterarTodos()
	{

		$id_tabela = intval($_POST['id_tabela']);
		$wgrupo = (empty($_POST['id_grupo'])) ? "" : " AND p.id_grupo = " . post('id_grupo');
		$wcategoria = (empty($_POST['id_categoria'])) ? "" : " AND p.id_categoria = " . post('id_categoria');
		$wfabricante = (empty($_POST['id_fabricante'])) ? "" : " AND p.id_fabricante = " . post('id_fabricante');
		$percentual = converteMoeda(post('percentual'));
		$percentual_venda = 1 + ($percentual / 100);
		$sql = "UPDATE produto_tabela as t, produto as p SET t.percentual = $percentual, t.valor_venda = (p.valor_custo*$percentual_venda) WHERE p.id = t.id_produto AND t.id_tabela = $id_tabela $wgrupo $wcategoria $wfabricante";
		self::$db->query($sql);
		if (self::$db->affected()) {
			print Filter::msgOk(lang('PRODUTO_AlTERADO_OK'), "index.php?do=tabela&acao=tabela&id=" . $id_tabela);
		} else {
			print Filter::msgAlert(lang('NAOPROCESSADO'));
		}
	}

	/**
	 * Produto::getProdutokit()
	 *
	 * @return
	 */
	public function getProdutokit($id_produto)
	{
		$sql = "SELECT k.id, k.id_produto, k.id_produto_kit, k.quantidade, k.materia_prima, p.nome, (p.valor_custo*k.quantidade) as valor_custo, p.estoque "
			. "\n FROM produto_kit as k"
			. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
			. "\n WHERE k.id_produto_kit = $id_produto "
			. "\n ORDER BY p.nome ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getValorCustoProdutokit()
	 *
	 * @return
	 */
	public function getValorCustoProdutokit($id_produto)
	{
		$sql = "SELECT SUM(p.valor_custo*k.quantidade) AS valor_custo "
			. "\n FROM produto_kit as k"
			. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
			. "\n WHERE k.id_produto_kit = $id_produto "
			. "\n ORDER BY p.nome ";
		$row = self::$db->first($sql);

		return ($row) ? $row->valor_custo : 0;
	}

	/**
	 * Produto::processarProdutoKit()
	 *
	 * @return
	 */
	public function processarProdutoKit()
	{
		$materia_prima = post('materia_prima');

		if (empty($_POST['id_produto']))
			Filter::$msgs['id_produto'] = lang('MSG_ERRO_NOME');

		if (empty(Filter::$msgs)) {

			$quantidade = (empty($_POST['quantidade'])) ? 1 : converteMoeda(post('quantidade'));

			$id_produto_kit = post('id_produto_kit');

			$data = array(
				'id_produto_kit' => $id_produto_kit,
				'id_produto' => sanitize(post('id_produto')),
				'quantidade' => $quantidade,
				'materia_prima' => $materia_prima,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->insert("produto_kit", $data);

			$valor_custo = $this->getValorCustoProdutokit($id_produto_kit);

			$data_produto = array(
				'valor_custo' => $valor_custo,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->update(self::uTable, $data_produto, "id=" . $id_produto_kit);

			$message = lang('PRODUTO_ADICIONADO_OK');

			if (self::$db->affected()) {
				print Filter::msgOk($message, "index.php?do=produto&acao=editar&id=" . $id_produto_kit);
			} else
				print Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getProdutoVenda()
	 *
	 * @return
	 */
	public function getProdutoVenda($id = 0)
	{
		$sql = "SELECT v.id, v.id_produto, p.nome as produto, p.codigo, p.estoque, e.id_empresa, e.quantidade, em.nome as empresa "
			. "\n FROM cadastro_vendas as v"
			. "\n LEFT JOIN produto as p ON p.id = v.id_produto "
			. "\n LEFT JOIN produto_estoque as e ON e.id_ref = v.id "
			. "\n LEFT JOIN empresa as em ON em.id = e.id_empresa "
			. "\n WHERE v.id = $id AND v.inativo = 0 "
			. "\n ORDER BY p.nome ASC";
		$row = self::$db->first($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getEstoqueVenda()
	 *
	 * @return
	 */
	public function getEstoqueVenda($id_venda = 0)
	{
		$sql = "SELECT v.id, v.id_produto, p.nome as produto, p.codigo, p.estoque, e.id_empresa, e.quantidade, em.nome as empresa "
			. "\n FROM cadastro_vendas as v"
			. "\n LEFT JOIN produto as p ON p.id = v.id_produto "
			. "\n LEFT JOIN produto_estoque as e ON e.id_ref = v.id "
			. "\n LEFT JOIN empresa as em ON em.id = e.id_empresa "
			. "\n WHERE v.id_venda = $id_venda AND v.inativo = 0 "
			. "\n ORDER BY p.nome ASC";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getEstoqueProduto()
	 *
	 * @return
	 */
	public function getEstoqueProduto($id_produto = 0)
	{
		$sql = "SELECT p.id_empresa, em.nome as empresa, SUM(p.quantidade) AS estoque "
			. "\n FROM produto_estoque as p"
			. "\n LEFT JOIN empresa as em ON em.id = p.id_empresa "
			. "\n WHERE p.id_produto = $id_produto AND p.inativo = 0 "
			. "\n GROUP BY p.id_empresa"
			. "\n ORDER BY p.id_empresa ASC";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getEstoqueInventario()
	 *
	 * @return
	 */
	public function getEstoqueInventario($id_empresa = false)
	{
		$wempresa = ($id_empresa) ? "AND e.id_empresa = " . $id_empresa : "";
		$sql = "SELECT p.nome as produto, p.codigo, e.id_produto, SUM(e.quantidade) AS estoque "
			. "\n FROM produto_estoque as e"
			. "\n LEFT JOIN produto as p ON p.id = e.id_produto "
			. "\n WHERE e.inativo = 0 "
			. "\n $wempresa "
			. "\n GROUP BY e.id_produto"
			. "\n ORDER BY p.nome ASC";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getInventarioFiscal()
	 * PARA UM PRODUTO ESTAR NO INVENTÁRIO ELE DEVE ESTAR CLASSIFICADO EM UM GRUPO.
	 * id_grupo = 0 NÃO ENTRA NO INVENTARIO.
	 * @return
	 */
	public function getInventarioFiscal($id_empresa, $mes_ano)
	{
		$sql = "SELECT i.id, i.nome, i.ncm, i.codigonota, i.unidade, SUM(i.quantidade) AS quantidade, i.valor_unitario "
			. "\n FROM inventario as i"
			. "\n WHERE i.id_empresa = $id_empresa AND i.ano > 0 AND i.ano <= $mes_ano "
			. "\n GROUP BY i.id "
			. "\n ORDER BY i.nome ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutoAtributo()
	 *
	 * @return
	 */
	public function getProdutoAtributo($id_produto)
	{
		$sql = "SELECT p.id, p.id_produto, p.id_atributo, p.descricao, p.inativo, a.atributo, a.exibir_romaneio "
			. "\n FROM produto_atributo as p"
			. "\n LEFT JOIN atributo as a ON a.id = p.id_atributo "
			. "\n WHERE p.id_produto = $id_produto "
			. "\n ORDER BY a.atributo ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarProdutoAtributo()
	 *
	 * @return
	 */
	public function processarProdutoAtributo()
	{
		if (empty($_POST['id_atributo']))
			Filter::$msgs['id_atributo'] = lang('MSG_ERRO_ATRIBUTO');

		if (empty($_POST['descricao']))
			Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');

		if (empty(Filter::$msgs)) {

			$data = array(
				'id_produto' => sanitize(post('id_produto')),
				'id_atributo' => sanitize(post('id_atributo')),
				'descricao' => sanitize(post('descricao')),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->insert("produto_atributo", $data);

			$message = lang('PRODUTO_ATRIBUTO_ADICIONADO_OK');

			if (self::$db->affected()) {
				print Filter::msgOk($message, "index.php?do=produto&acao=editar&id=" . post('id_produto'));
			} else
				print Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}
	/**
	 * Produto::processarProdutoImagem()
	 *
	 * @return
	 */
	public function processarProdutoImagem()
	{
		if (empty(Filter::$msgs)) {
			$id = post('id');
			foreach ($_POST as $nome_campo => $valor) {
				$valida = strpos($nome_campo, "tmpname");
				if ($valida) {
					$nome = $valor;
					$data = array(
						'imagem' => $nome,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("produto", $data, "id=" . $id);
				}
			}
			if (self::$db->affected()) {
				$message = lang('PRODUTO_IMAGEM_ADICIONADO_OK');
				Filter::msgOk($message, "index.php?do=produto&acao=editar&id=" . $id);
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getFornecedoresProduto()
	 *
	 * @return
	 */
	public function getFornecedoresProduto($id_produto)
	{
		$sql = "SELECT pp.id, pp.nome as produto, pp.id_produto, pp.id_cadastro, pp.id_nota, pp.codigonota, pp.ncm, pp.codigobarras, pp.valor_unitario, pp.quantidade_compra, pp.unidade_compra, pp.prazo, pp.usuario, pp.data, c.nome as fornecedor, c.celular, c.telefone, c.email, n.data_emissao, n.numero_nota "
			. "\n FROM produto_fornecedor as pp"
			. "\n LEFT JOIN cadastro as c ON c.id = pp.id_cadastro "
			. "\n LEFT JOIN nota_fiscal as n ON n.id = pp.id_nota "
			. "\n WHERE n.inativo = 0 AND pp.id_produto = " . $id_produto;

		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarProdutoFornecedor()
	 *
	 * @return
	 */
	public function processarProdutoFornecedor()
	{
		if (empty($_POST['id_cadastro']))
			Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_FORNECEDOR');

		if (empty(Filter::$msgs)) {

			$data = array(
				'id_produto' => sanitize(post('id_produto')),
				'id_cadastro' => sanitize(post('id_cadastro')),
				'codigonota' => sanitize(post('codigonota')),
				'ncm' => sanitize(post('ncm')),
				'codigobarras' => sanitize(post('codigobarras')),
				'valor_unitario' => converteMoeda(post('valor_unitario')),
				'quantidade_compra' => converteMoeda(post('quantidade_compra')),
				'unidade_compra' => sanitize(post('unidade_compra')),
				'prazo' => sanitize(post('prazo')),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			(Filter::$id) ? self::$db->update("produto_fornecedor", $data, "id=" . Filter::$id) : self::$db->insert("produto_fornecedor", $data);
			$message = (Filter::$id) ? lang('FORNECEDOR_AlTERADO_OK') : lang('FORNECEDOR_ADICIONADO_OK');

			if (self::$db->affected()) {

				Filter::msgOk($message, "index.php?do=produto&acao=editar&id=" . post('id_produto'));
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::novoProdutoNota()
	 *
	 * @return
	 */
	public function novoProdutoNota()
	{
		$id = post('id');
		$id_nota = post('id_nota');
		$id_nota = ($id_nota > 0) ? $id_nota : getValue('id_nota', 'produto_fornecedor', 'id=' . $id);
		$id_empresa = getValue('id_empresa', 'nota_fiscal', 'id=' . $id_nota);
		$cfop = getValue('cfop', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);

		if (!empty($_POST['codigobarras'])) {
			$codigobarras = sanitize(post('codigobarras'));
			$where = (Filter::$id) ? "AND id <> " . Filter::$id : "";
			$sql = "SELECT nome FROM produto WHERE inativo = 0 AND codigobarras = '$codigobarras' $where ";
			$row = self::$db->first($sql);
			if ($row)
				Filter::$msgs['codigobarras'] = str_replace("[PRODUTO]", $row->nome, lang('MSG_ERRO_CODBARRAS_CADASTRADO'));
		}

		if (empty(Filter::$msgs)) {
			if (empty($_POST['cfop'])) {
				$cfop_produto = "5102";
				$csosn_cst = "102";
				switch ($cfop) {
					case '5101':
						$cfop_produto = "5102";
						$csosn_cst = "102";
						break;
					case '5102':
						$cfop_produto = "5102";
						$csosn_cst = "102";
						break;
					case '6101':
						$cfop_produto = "5102";
						$csosn_cst = "102";
						break;
					case '6102':
						$cfop_produto = "5102";
						$csosn_cst = "102";
						break;
					case '5403':
						$cfop_produto = "5405";
						$csosn_cst = "500";
						break;
					case '5401':
						$cfop_produto = "5405";
						$csosn_cst = "500";
						break;
					case '6401':
						$cfop_produto = "5405";
						$csosn_cst = "500";
						break;
					case '6403':
						$cfop_produto = "5405";
						$csosn_cst = "500";
						break;
				}
			} else {
				$cfop_produto = post('cfop');
				$csosn_cst = post('csosn_cst');
			}
			$cfop_entrada = post('cfop_entrada');
			$produto_tipo_item = post('produto_tipo_item');
			$ncm = post('ncm_nf');
			$cest = post('cest_nf');
			$cod_anp = post('cod_anp');
			$valor_partida = converteMoeda(post('valor_partida'));

			$row = Core::getRowById("produto_fornecedor", $id);
			$quant_nota = $this->getQuantNota($id_nota, $id);

			$quant_unidades = (empty($_POST['quant_unidades'])) ? 1 : post('quant_unidades');

			$quant_unidades = str_replace('.', '', $quant_unidades);
			$quant_unidades = str_replace(',', '.', $quant_unidades);

			$quantidade = $quant_unidades * $quant_nota;

			$valor_unitario = getValue('valor_unitario', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
			$valor_desconto = getValue('valor_desconto', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
			$outrasDespesasAcessorias = getValue('outrasDespesasAcessorias', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
			$icms_st_valor = getValue('icms_st_valor', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
			$ipi_valor = getValue('ipi_valor', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
			$valor_frete = getValue('valor_frete', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);

			$valor_uni_produto = $valor_unitario - ($valor_desconto / $quant_nota) + ($outrasDespesasAcessorias / $quant_nota) + ($valor_frete / $quant_nota) + ($icms_st_valor / $quant_nota) + ($ipi_valor / $quant_nota);
			$valor_unitario = round($valor_uni_produto / $quant_unidades, 2);

			$data = array(
				'nome' => strtoupper($row->nome),
				'codigo' => $row->codigonota,
				'codigobarras' => post('codigobarras'),
				'ncm' => $ncm,
				'cfop' => $cfop_produto,
				'cfop_entrada' => $cfop_entrada,
				'cest' => $cest,
				'icms_cst' => $csosn_cst,
				'valor_custo' => $valor_unitario,
				'unidade' => post('unidade'),
				'estoque' => $quantidade,
				'id_grupo' => post('id_grupo'),
				'id_categoria' => post('id_categoria'),
				'id_fabricante' => post('id_fabricante'),
				'anp' => $cod_anp,
				'valor_partida' => $valor_partida,
				'grade' => '1',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			$id_produto = self::$db->insert("produto", $data);
			$retorno_row = $this->getTabelaPrecos();
			$valor_venda = str_replace('R$ ', '', $_POST['valor_venda']);
			$valor_custo = ($valor_unitario > 0) ? $valor_unitario : 1;
			$percentual = (floatval($valor_venda) / floatval($valor_custo)) - 1;
			$percentual = ($percentual > 0) ? $percentual : 1;

			if ($retorno_row) {
				foreach ($retorno_row as $exrow) {
					$valor_venda = (empty($_POST['valor_venda'])) ? ((1 + ($exrow->percentual / 100)) * $valor_unitario) : converteMoeda(post('valor_venda'));
					$data_tabela = array(
						'id_tabela' => $exrow->id,
						'id_produto' => $id_produto,
						'percentual' => ($percentual == 1) ? $exrow->percentual : $percentual * 100,
						'valor_venda' => round($valor_venda, 2),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("produto_tabela", $data_tabela);
				}
			} else {
				$valor_venda = (empty($_POST['valor_venda'])) ? $valor_unitario : converteMoeda(post('valor_venda'));
				$data_tabela_preco = array(
					'tabela' => 'PADRAO',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_tabela = self::$db->insert("tabela_precos", $data_tabela_preco);

				$data_tabela = array(
					'id_tabela' => $id_tabela,
					'id_produto' => $id_produto,
					'valor_venda' => round($valor_venda, 2),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("produto_tabela", $data_tabela);

				$retorno_row = $this->getTabelaPrecos();
				$valor_venda = converteMoeda(post('valor_venda'));
				$valor_custo = converteMoeda(post('valor_custo'));
				$valor_custo = ($valor_custo > 0) ? $valor_custo : 1;
				$percentual = ($valor_venda / $valor_custo) - 1;
				$percentual = ($percentual > 0) ? $percentual : 1;
				if ($retorno_row) {
					foreach ($retorno_row as $exrow) {
						$data_tabela = array(
							'id_tabela' => $exrow->id,
							'id_produto' => $id_produto,
							'percentual' => $percentual * 100,
							'valor_venda' => converteMoeda(post('valor_venda')),
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->insert("produto_tabela", $data_tabela);
					}
				} else {
					$data_tabela_preco = array(
						'tabela' => 'PADRAO',
						'percentual' => $percentual * 100,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$id_tabela = self::$db->insert("tabela_precos", $data_tabela_preco);
					$data_tabela = array(
						'id_tabela' => $id_tabela,
						'id_produto' => $id_produto,
						'percentual' => $percentual * 100,
						'valor_venda' => converteMoeda(post('valor_venda')),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("produto_tabela", $data_tabela);
				}
			}

			$data_nota = array(
				'id_produto' => $id_produto,
				'cfop_entrada' => $cfop_entrada,
				'produto_tipo_item' => $produto_tipo_item
			);
			self::$db->update("nota_fiscal_itens", $data_nota, "id_produto_fornecedor=" . $id);
			$data_fornecedor = array(
				'id_produto' => $id_produto,
				'quantidade_compra' => $quant_unidades,
			);
			self::$db->update("produto_fornecedor", $data_fornecedor, "id=" . $id);
			$numero_nota = getValue("numero_nota", "nota_fiscal", "id=" . $id_nota);
			$obs_numero_nota = ($numero_nota > 0) ? 'NÚMERO: ' . $numero_nota : 'ID: ' . $id_nota;
			$observacao = str_replace("[NUMERO_NOTA]", $obs_numero_nota, lang('NOTA_ENTRADA_NOVO_PRODUTO'));
			$data_estoque = array(
				'id_empresa' => $id_empresa,
				'id_produto' => $id_produto,
				'quantidade' => $quantidade,
				'tipo' => 1,
				'motivo' => 1,
				'observacao' => $observacao,
				'id_ref' => $id_nota,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->insert("produto_estoque", $data_estoque);

			$redirecionar = ((empty($_POST['pendente'])) and ($id_nota > 0)) ? "index.php?do=notafiscal&acao=visualizar&id=" . $id_nota : "index.php?do=produto&acao=pendentes";
			print Filter::msgOk(lang('PRODUTO_ADICIONADO_OK'), $redirecionar);
		} else {
			print Filter::msgStatus();
		}
	}

	/**
	 * Produto::editarProdutoNotaEntrada()
	 *
	 * @return
	 */
	public function editarProdutoNotaEntrada()
	{
		$id = post('id');
		$row_nf_item = Core::getRowById("nota_fiscal_itens", $id);

		$codigo_barras = (empty(post('codigobarras_editar')) or post('codigobarras_editar') == 0) ? $row_nf_item->codigobarras : post('codigobarras_editar');
		$cfop_entrada = (empty(post('cfop_entrada_editar')) or post('cfop_entrada_editar') == '0000') ? $row_nf_item->cfop_entrada : post('cfop_entrada_editar');
		$cfop_saida = (empty(post('cfop_editar')) or post('cfop_editar') == '0000') ? $row_nf_item->cfop : post('cfop_editar');

		$data_nf_item = array(
			'codigobarras' => $codigo_barras,
			'cfop' => $cfop_saida,
			'cfop_entrada' => $cfop_entrada
		);
		self::$db->update("nota_fiscal_itens", $data_nf_item, "id=" . $id);

		$redirecionar = "index.php?do=notafiscal&acao=visualizar&id=" . $row_nf_item->id_nota;
		if (self::$db->affected()) {
			print Filter::msgOk(lang('EDITAR_PRODUTO_NF_OK'), $redirecionar);
		} else
			Filter::msgAlert(lang('NAOPROCESSADO'));
	}

	/**
	 * Produto::combinarProdutoNota()
	 *
	 * @return
	 */
	public function combinarProdutoNota()
	{
		$id = post('id');
		$id_nota = post('id_nota');
		$id_nota = ($id_nota > 0) ? $id_nota : getValue('id_nota', 'produto_fornecedor', 'id=' . $id);
		$id_produto = post('id_produto');
		$valor_unitario = getValue('valor_unitario', 'produto_fornecedor', 'id=' . $id);
		$valor_custo = getValue('valor_custo', 'produto', 'id=' . $id_produto);
		$id_empresa = getValue('id_empresa', 'nota_fiscal', 'id=' . $id_nota);
		$quant_nota = $this->getQuantNota($id_nota, $id);

		$quant_unidades = (empty($_POST['quant_unidades'])) ? 1 : post('quant_unidades');
		$quant_unidades = str_replace('.', '', $quant_unidades);
		$quant_unidades = str_replace(',', '.', $quant_unidades);

		$valor_unitario = getValue('valor_unitario', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
		$valor_desconto = getValue('valor_desconto', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
		$outrasDespesasAcessorias = getValue('outrasDespesasAcessorias', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
		$icms_st_valor = getValue('icms_st_valor', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
		$ipi_valor = getValue('ipi_valor', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
		$valor_frete = getValue('valor_frete', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);

		$valor_uni_produto = $valor_unitario - ($valor_desconto / $quant_nota) + ($outrasDespesasAcessorias / $quant_nota) + ($icms_st_valor / $quant_nota) + ($ipi_valor / $quant_nota) + ($valor_frete / $quant_nota);

		$novo_valor_custo = round(($valor_uni_produto / $quant_unidades), 2);

		$quantidade = $quant_unidades * $quant_nota;
		$cfop_entrada = post('cfop_entrada') ? post('cfop_entrada') : getValue('cfop_entrada', 'produto', 'id=' . $id_produto);

		$data_fornecedor = array(
			'id_produto' => $id_produto,
			'quantidade_compra' => $quant_unidades,
		);
		self::$db->update("produto_fornecedor", $data_fornecedor, "id=" . $id);

		$data_nf_item = array(
			'id_produto' => $id_produto,
			'cfop_entrada' => $cfop_entrada
		);
		self::$db->update("nota_fiscal_itens", $data_nf_item, "id_produto_fornecedor=" . $id);

		$numero_nota = getValue("numero_nota", "nota_fiscal", "id=" . $id_nota);
		$obs_numero_nota = ($numero_nota > 0) ? 'NÚMERO: ' . $numero_nota : 'ID: ' . $id_nota;
		$observacao = str_replace("[NUMERO_NOTA]", $obs_numero_nota, lang('NOTA_ENTRADA_PRODUTO_COMBINADO'));

		$data_estoque = array(
			'id_empresa' => $id_empresa,
			'id_produto' => $id_produto,
			'quantidade' => $quantidade,
			'tipo' => 1,
			'motivo' => 1,
			'observacao' => $observacao,
			'id_ref' => $id_nota,
			'usuario' => session('nomeusuario'),
			'data' => "NOW()"
		);
		self::$db->insert("produto_estoque", $data_estoque);
		$totalestoque = $this->getEstoqueTotal($id_produto);
		$data_update = array(
			'valor_custo' => $novo_valor_custo,
			'estoque' => $totalestoque,
			'usuario' => session('nomeusuario'),
			'data' => "NOW()"
		);
		self::$db->update("produto", $data_update, "id=" . $id_produto);
		$atualizar_valor_produto = getValue('atualizar_valor_produto', 'empresa', "'inativo'=0");

		//Verificar se este produto é kit de outro.
		$checkKit = $this->verificaSeProdutoVirouKit($id_produto);
		if ($checkKit) {
			foreach ($checkKit as $key) {
				$id_produto_compoe_kit = $key->id_produto ? $key->id_produto : 0;
				if ($id_produto_compoe_kit) {
					if ($id_produto == $id_produto_compoe_kit) {
						$valor_custo_total = round($this->getValorCustoProdutokit($key->id_produto_kit), 2);
						$valor_custo_atualizado = $valor_custo_total - $key->valor_custo + $novo_valor_custo;

						$data_produto = array(
							'valor_custo' => $valor_custo_atualizado,
							'usuario_alteracao' => session('nomeusuario'),
							'data_alteracao' => "NOW()",
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update(self::uTable, $data_produto, "id=" . $key->id_produto_kit);

						$retorno_row = $this->getTabelaPrecos();
						if ($retorno_row) {
							foreach ($retorno_row as $exrow) {
								$percentual = getValue("percentual", "produto_tabela", "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
								$novo_valor_venda = ((1 + ((float)$percentual / 100)) * $valor_custo_atualizado);
								$valor_venda_atual = getValue("valor_venda", "produto_tabela", "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
								$novo_percentual = ((float)$valor_venda_atual / $valor_custo_atualizado) - 1;

								if ($atualizar_valor_produto) {
									if ($novo_valor_venda > $valor_venda_atual) {
										$data_tabela_novo = array(
											'percentual' => $percentual,
											'valor_venda' => round($novo_valor_venda, 2),
											'usuario' => session('nomeusuario'),
											'data' => "NOW()"
										);
									} else {
										$data_tabela_novo = array(
											'percentual' => $novo_percentual * 100,
											'usuario' => session('nomeusuario'),
											'data' => "NOW()"
										);
									}
									self::$db->update("produto_tabela", $data_tabela_novo, "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
								} else {
									$data_tabela_novo = array(
										'percentual' => $novo_percentual * 100,
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									self::$db->update("produto_tabela", $data_tabela_novo, "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
								}
							}
						}
					}
				}
			}
		}

		$sql_produto_tabela = "SELECT * FROM produto_tabela WHERE id_produto = $id_produto";
		$row_produto_tabela = self::$db->fetch_all($sql_produto_tabela);

		if ($row_produto_tabela) {
			foreach ($row_produto_tabela as $rowpt) {
				$valor_atual_venda_produto = getValue("valor_venda", "produto_tabela", "id=" . $rowpt->id);
				if ($atualizar_valor_produto) {
					$percentual_tabela = getValue("percentual", "tabela_precos", "id=" . $rowpt->id_tabela);
					$percentual_produto = getValue("percentual", "produto_tabela", "id=" . $rowpt->id);
					$percentual = ($percentual_tabela > 0) ? $percentual_tabela : $percentual_produto;
					$valor_novo_venda_produto = round(($novo_valor_custo + ($novo_valor_custo * ($percentual / 100))), 2);
					if ($valor_novo_venda_produto > $valor_atual_venda_produto) {
						$data_produto_tabela = array(
							'valor_venda' => $valor_novo_venda_produto,
							'percentual' => $percentual
						);
					} else {
						$percentual_novo = (($valor_atual_venda_produto / $novo_valor_custo) - 1) * 100;
						$data_produto_tabela = array(
							'valor_venda' => $valor_atual_venda_produto,
							'percentual' => $percentual_novo
						);
					}
					self::$db->update("produto_tabela", $data_produto_tabela, "id=" . $rowpt->id);
				} else {
					$percentual_novo = (($valor_atual_venda_produto / $novo_valor_custo) - 1) * 100;
					$data_produto_tabela = array(
						'percentual' => $percentual_novo
					);
					self::$db->update("produto_tabela", $data_produto_tabela, "id=" . $rowpt->id);
				}
			}
		}
		$redirecionar = ((empty($_POST['id_cadastro'])) and ($id_nota > 0)) ? "index.php?do=notafiscal&acao=visualizar&id=" . $id_nota : "index.php?do=produto&acao=pendentes";
		print Filter::msgOk(lang('PRODUTO_ADICIONADO_OK'), $redirecionar);
	}

	/**
	 * Produto::todosProdutoNota()
	 *
	 * @return
	 */
	public function todosProdutoNota()
	{
		$sql = "SELECT f.* "
			. "\n FROM produto_fornecedor as f"
			. "\n WHERE f.id_produto = 0";
		$retorno_row = self::$db->fetch_all($sql);
		if ($retorno_row) {
			foreach ($retorno_row as $row) {
				$id = $row->id;
				$id_nota = $row->id_nota;
				$id_empresa = ($id_nota) ? getValue('id_empresa', 'nota_fiscal', 'id=' . $id_nota) : 0;
				$quantidade = $this->getQuantNota($id_nota, $id);
				$cfop = getValue('cfop', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
				$cest = getValue('cest', 'nota_fiscal_itens', 'id_produto_fornecedor=' . $id);
				$id_produto = getValue('id_produto', 'produto_fornecedor', 'id_produto <> 0 AND codigonota="' . $row->codigonota . '" and ncm="' . $row->ncm . '"');
				if (!$id_produto) {
					$cfop_produto = "5102";
					$csosn_cst = "102";
					switch ($cfop) {
						case '5101':
							$cfop_produto = "5102";
							$csosn_cst = "102";
							break;
						case '5102':
							$cfop_produto = "5102";
							$csosn_cst = "102";
							break;
						case '6101':
							$cfop_produto = "5102";
							$csosn_cst = "102";
							break;
						case '6102':
							$cfop_produto = "5102";
							$csosn_cst = "102";
							break;
						case '5403':
							$cfop_produto = "5405";
							$csosn_cst = "500";
							break;
						case '5401':
							$cfop_produto = "5405";
							$csosn_cst = "500";
							break;
						case '6401':
							$cfop_produto = "5405";
							$csosn_cst = "500";
							break;
						case '6403':
							$cfop_produto = "5405";
							$csosn_cst = "500";
							break;
					}
					$data = array(
						'nome' => strtoupper($row->nome),
						'codigo' => $row->codigonota,
						'codigobarras' => $row->codigobarras,
						'ncm' => $row->ncm,
						'cfop' => $cfop_produto,
						'cest' => $cest,
						'icms_cst' => $csosn_cst,
						'valor_custo' => $row->valor_unitario,
						'unidade' => post('unidade'),
						'estoque' => $quantidade,
						'grade' => '1',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$id_produto = self::$db->insert("produto", $data);
					$retorno_row = $this->getTabelaPrecos();
					if ($retorno_row) {
						foreach ($retorno_row as $exrow) {
							$valor_venda = ((1 + ($exrow->percentual / 100)) * $row->valor_unitario);
							$data_tabela = array(
								'id_tabela' => $exrow->id,
								'id_produto' => $id_produto,
								'valor_venda' => $valor_venda,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->insert("produto_tabela", $data_tabela);
						}
					} else {
						$data_tabela_preco = array(
							'tabela' => 'PADRAO',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$id_tabela = self::$db->insert("tabela_precos", $data_tabela_preco);
						$data_tabela = array(
							'id_tabela' => $id_tabela,
							'id_produto' => $id_produto,
							'valor_venda' => $row->valor_unitario,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->insert("produto_tabela", $data_tabela);
					}
				}
				$data_fornecedor = array(
					'id_produto' => $id_produto,
					'quantidade_compra' => $quantidade,
				);
				self::$db->update("produto_fornecedor", $data_fornecedor, "id=" . $id);
				$data_produto = array(
					'id_produto' => $id_produto
				);
				self::$db->update("nota_fiscal_itens", $data_produto, "id_produto_fornecedor=" . $id);

				$numero_nota = getValue("numero_nota", "nota_fiscal", "id=" . $id_nota);
				$obs_numero_nota = ($numero_nota > 0) ? 'NÚMERO: ' . $numero_nota : 'ID: ' . $id_nota;
				$observacao = str_replace("[NUMERO_NOTA]", $obs_numero_nota, lang('NOTA_ENTRADA_NOVO_PRODUTO'));

				$data_estoque = array(
					'id_empresa' => $id_empresa,
					'id_produto' => $id_produto,
					'quantidade' => $quantidade,
					'tipo' => 1,
					'motivo' => 1,
					'observacao' => $observacao,
					'id_ref' => $id_nota,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("produto_estoque", $data_estoque);
			}
		}
		print Filter::msgOk(lang('PRODUTO_ADICIONADO_OK'), "index.php?do=produto&acao=pendentes");
	}

	/**
	 * Produto::getEstoqueTotal()
	 *
	 * @return
	 */
	public function getEstoqueTotal($id_produto, $tipo = false)
	{
		$wtipo = ($tipo) ? " AND e.tipo = $tipo " : "";
		$sql = "SELECT SUM(e.quantidade) AS total "
			. "\n FROM produto_estoque as e"
			. "\n WHERE e.inativo = 0 AND e.id_produto = $id_produto " . $wtipo;
		$row = self::$db->first($sql);
		return ($row) ? $row->total : 0;
	}

	/**
	 * Produto::getProdutosPendente()
	 *
	 * @return
	 */
	public function getProdutosPendentes()
	{
		$sql = "SELECT 	ni.*,
						n.numero_nota,
						n.data_emissao,
						f.nome as nome_fornecedor

				FROM 		nota_fiscal_itens 	AS ni
				LEFT JOIN 	nota_fiscal 		AS n 	ON n.id = ni.id_nota
				LEFT JOIN 	produto_fornecedor 	AS f 	ON f.id = ni.id_produto_fornecedor

				WHERE 	ni.inativo = 0
				AND 	ni.id_produto = 0";

		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getQuantNota()
	 *
	 * @return
	 */
	public function getQuantNota($id_nota, $id_produto_fornecedor)
	{
		$sql = "SELECT SUM(n.quantidade) AS total "
			. "\n FROM nota_fiscal_itens as n"
			. "\n WHERE n.inativo = 0 AND n.id_produto_fornecedor = $id_produto_fornecedor AND n.id_nota = $id_nota";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Produto::getQuantNotaFornecedor()
	 *
	 * @return
	 */
	public function getQuantNotaFornecedor($id)
	{
		$sql = "SELECT SUM(f.quantidade_compra) AS total "
			. "\n FROM produto_fornecedor as f"
			. "\n WHERE f.id = $id ";
		$row = self::$db->first($sql);

		return ($row) ? $row->total : 0;
	}

	/**
	 * Produto::getProximaNumeroNota()
	 *
	 * @return
	 */
	public function getProximaNumeroNota($id_empresa)
	{

		$sql = "SELECT MAX(numero)+1 as numero "
			. "\n FROM nota_fiscal"
			. "\n WHERE fiscal = 1 AND modelo = 2 AND id_empresa = $id_empresa ";
		//echo $sql;
		$row = self::$db->first($sql);

		return ($row and $row->numero) ? $row->numero : 1;
	}

	/*
	* $status = Cancelada ou Autorizada
	*/
	public function getNotasDaEmpresa($empresaId, $status, $pagina = 0)
	{
		$enotas_apikey = 'MDFiMzJlN2ItMjkwYS00ODcyLTkwOGYtNjVlMTUwYjYwNTAw';
		$curl = curl_init();

		$url = "https://api.enotasgw.com.br/v1/empresas/$empresaId/nfes?pageNumber=$pagina&pageSize=150&filter=status%20eq%20'$status'&sortBy=datacriacao&sortDirection=asc";

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Authorization: Basic " . $enotas_apikey,
				"accept: application/json"
			),
		));
		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response, true);
	}

	public function getInfoNota($empresaId, $notaId, $tipoNota)
	{
		$enotas_apikey = 'MDFiMzJlN2ItMjkwYS00ODcyLTkwOGYtNjVlMTUwYjYwNTAw';
		$curl = curl_init();
		$tipo = strtolower($tipoNota);
		$url = "https://api.enotasgw.com.br/v2/empresas/$empresaId/$tipo/$notaId";

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Authorization: Basic $enotas_apikey",
				"accept: application/json"
			),
		));
		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response, true);
	}

	public function getNotasBKP()
	{
		$sql = "SELECT * FROM nota_fiscal_bkp";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	public function existeNotaBKP($id_externo, $chave_acesso)
	{
		$sql = "SELECT * FROM nota_fiscal_bkp WHERE id_externo = '$id_externo' AND chave_acesso = '$chave_acesso'";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	public function gravarInfoNotas($enotas, $status)
	{
		$total = 0;
		$retorno_row = $this->getNotasDaEmpresa($enotas, $status, 0);
		if ($retorno_row):
			$total_notas = $retorno_row['totalRecords'];
			$qtde_paginas = intval(($total_notas - 1) / 150);
			for ($i = 0; $i <= $qtde_paginas; $i++):
				$notas = $this->getNotasDaEmpresa($enotas, $status, $i);
				$exrow_total = $notas['totalRecords'];
				foreach ($notas['data'] as $exrow):
					$info_nota = $this->getInfoNota($enotas, $exrow['idExterno'], $exrow['tipo']);
					$data_nota = array(
						'total_notas' => $exrow_total,
						'id_nota' => $exrow['id'],
						'tipo' => $exrow['tipo'],
						'id_externo' => $exrow['idExterno'],
						'status_nota' => $exrow['status'],
						'data_criacao' => $info_nota['dataCriacao'],
						'data_ultima_alteracao' => $info_nota['dataUltimaAlteracao'],
						'chave_acesso' => $info_nota['chaveAcesso'],
						'link_danfe' => $info_nota['linkDanfe'],
						'link_download_xml' => $info_nota['linkDownloadXml'],
						'valor_total' => $info_nota['valorTotal']
					);

					$existeNota = $this->existeNotaBKP($exrow['idExterno'], $info_nota['chaveAcesso']);
					if (!$existeNota)
						self::$db->insert("nota_fiscal_bkp", $data_nota);

				endforeach;
			endfor;
			Filter::msgOk("Lista adicionada/atualizada", "index.php?do=notafiscal_bkp&acao=listar");
		endif;
	}

	public function getPaisesIbge()
	{
		$sql = "SELECT * FROM paises_ibge";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	public function getImpostosNotaFiscal($id_nota)
	{
		$sql = "SELECT SUM(valor_total) as valor_total, SUM(icms_base) as icms_base, SUM(icms_valor) as icms_valor, SUM(pis_base) as pis_base, SUM(pis_valor) as pis_valor, SUM(cofins_base) as cofins_base, SUM(cofins_valor) as cofins_valor, SUM(ipi_base) as ipi_base, SUM(ipi_valor) as ipi_valor, SUM(icms_st_base) as icms_st_base, SUM(icms_st_valor) as icms_st_valor, SUM(outrasDespesasAcessorias) as outrasDespesasAcessorias, SUM(valor_total_trib) as valor_total_trib"
			. "\n FROM nota_fiscal_itens "
			. "\n WHERE id_nota = $id_nota AND inativo = 0";
		$row = self::$db->first($sql);
		return ($row) ? $row : 0;
	}

	public function getNumeroPedidoCompraNota($id_nota)
	{
		$sql = "SELECT numero_pedido_compra FROM nota_fiscal_itens WHERE id_nota = $id_nota AND inativo = 0";
		$row = self::$db->first($sql);
		return ($row) ? $row->numero_pedido_compra : 0;
	}

	public function editarCfopTransporte()
	{
		$id_nota = post('id_nota');
		$cfop = post('cfop_transporte');

		if ($id_nota && $cfop) {
			$data_transporte = array(
				'cfop' => $cfop,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->update("nota_fiscal", $data_transporte, "id=" . $id_nota);
			if (self::$db->affected()) {
				Filter::msgOk(lang('NOTA_EDITAR_CFOP_OK'), "index.php?do=notafiscal&acao=notafiscal");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else {
			Filter::msgAlert(lang('NAOPROCESSADO'));
		}
	}

	/**
	 * Produto::getReceitasNota()
	 *
	 * @return
	 */
	public function getReceitasNota($id_nota)
	{
		if ($id_nota) {
			$sql = "SELECT * FROM receita WHERE inativo = 0 AND id_nota = $id_nota ";
			$row = self::$db->fetch_all($sql);
			return ($row) ? $row : 0;
		} else {
			return 0;
		}
	}

	/**
	 * Produto::getDespesasNota()
	 *
	 * @return
	 */
	public function getDespesasNota($id_nota)
	{
		if ($id_nota) {
			$sql = "SELECT * FROM despesa WHERE inativo = 0 AND id_nota = $id_nota ";
			$row = self::$db->fetch_all($sql);
			return ($row) ? $row : 0;
		} else {
			return 0;
		}
	}

	public function duplicarNotaFiscal($id_nota_original)
	{
		if ($id_nota_original > 0) {

			$row_nota_fiscal = Core::getRowById("nota_fiscal", $id_nota_original);

			$data_nf = array(
				'id_empresa' => $row_nota_fiscal->id_empresa,
				'data_emissao' => "NOW()",
				'dataSaidaEntrada' => $row_nota_fiscal->dataSaidaEntrada,
				'cfop' => $row_nota_fiscal->cfop,
				'nota_exportacao' => $row_nota_fiscal->nota_exportacao,
				'pais_exportacao' => $row_nota_fiscal->pais_exportacao,
				'descriminacao' => $row_nota_fiscal->descriminacao,
				'duplicatas' => $row_nota_fiscal->duplicatas,
				'apresentar_duplicatas' => $row_nota_fiscal->apresentar_duplicatas,
				'iss_retido' => $row_nota_fiscal->iss_retido,
				'iss_aliquota' => $row_nota_fiscal->iss_aliquota,
				'valor_base_icms' => $row_nota_fiscal->valor_base_icms,
				'valor_icms' => $row_nota_fiscal->valor_icms,
				'valor_base_st' => $row_nota_fiscal->valor_base_st,
				'valor_st' => $row_nota_fiscal->valor_st,
				'mva' => $row_nota_fiscal->mva,
				'icms_st_aliquota' => $row_nota_fiscal->icms_st_aliquota,
				'icms_normal_aliquota' => $row_nota_fiscal->icms_normal_aliquota,
				'valor_produto' => $row_nota_fiscal->valor_produto,
				'valor_frete' => $row_nota_fiscal->valor_frete,
				'valor_seguro' => $row_nota_fiscal->valor_seguro,
				'valor_desconto' => $row_nota_fiscal->valor_desconto,
				'valor_ipi' => $row_nota_fiscal->valor_ipi,
				'valor_pis' => $row_nota_fiscal->valor_pis,
				'valor_pis_st' => $row_nota_fiscal->valor_pis_st,
				'valor_cofins' => $row_nota_fiscal->valor_cofins,
				'valor_cofins_st' => $row_nota_fiscal->valor_cofins_st,
				'valor_outro' => $row_nota_fiscal->valor_outro,
				'valor_nota' => $row_nota_fiscal->valor_nota,
				'valor_negociado' => $row_nota_fiscal->valor_negociado,
				'valor_total_trib' => $row_nota_fiscal->valor_total_trib,
				'inf_adicionais' => $row_nota_fiscal->inf_adicionais,
				'nome_arquivo' => $row_nota_fiscal->nome_arquivo,
				'modelo' => $row_nota_fiscal->modelo,
				'operacao' => $row_nota_fiscal->operacao,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			$id_nota_nova = self::$db->insert("nota_fiscal", $data_nf);
			$message = lang('NOTA_ADICIONADO_OK');

			$row_transporte = $this->getTransporteNota($id_nota_original);
			if ($row_transporte) {
				$data_transporte = array(
					'id_nota' => $id_nota_nova,
					'modalidade' => $row_transporte->modalidade,
					'valor' => $row_transporte->valor,
					'tipopessoadestinatario' => $row_transporte->tipopessoadestinatario,
					'cpfcnpjdestinatario' => $row_transporte->cpfcnpjdestinatario,
					'uf' => $row_transporte->uf,
					'cidade' => $row_transporte->cidade,
					'logradouro' => $row_transporte->logradouro,
					'numero' => $row_transporte->numero,
					'complemento' => $row_transporte->complemento,
					'bairro' => $row_transporte->bairro,
					'cep' => $row_transporte->cep,
					'trans_tipopessoa' => $row_transporte->trans_tipopessoa,
					'trans_cpfcnpj' => $row_transporte->trans_cpfcnpj,
					'trans_nome' => $row_transporte->trans_nome,
					'trans_inscricaoestadual' => $row_transporte->trans_inscricaoestadual,
					'trans_endereco' => $row_transporte->trans_endereco,
					'trans_cidade' => $row_transporte->trans_cidade,
					'trans_uf' => $row_transporte->trans_uf,
					'veiculo_placa' => $row_transporte->veiculo_placa,
					'veiculo_uf' => $row_transporte->veiculo_uf,
					'rntc' => $row_transporte->rntc,
					'quantidade' => $row_transporte->quantidade,
					'especie' => $row_transporte->especie,
					'marca' => $row_transporte->marca,
					'numeracao' => $row_transporte->numeracao,
					'pesoliquido' => $row_transporte->pesoliquido,
					'pesobruto' => $row_transporte->pesobruto
				);
				self::$db->insert("nota_fiscal_transporte", $data_transporte);
			}

			$row_receitas = $this->getReceitasNota($id_nota_original);
			if ($row_receitas) {
				foreach ($row_receitas as $rrow) {
					$data_receita = array(
						'id_empresa' => $rrow->id_empresa,
						'id_nota' => $id_nota_nova,
						'id_conta' => $rrow->id_conta,
						'id_banco' => $rrow->id_banco,
						'id_caixa' => $rrow->id_caixa,
						'duplicata' => $rrow->duplicata,
						'descricao' => 'RECEITA AUTOMATICA DE NOTA FISCAL',
						'valor' => $rrow->valor,
						'valor_pago' => $rrow->valor_pago,
						'data_pagamento' => "NOW()",
						'parcela' => $rrow->parcela,
						'tipo' => $rrow->tipo,
						'pago' => '0',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("receita", $data_receita);
				}
			}

			$row_nota_fiscal_itens = $this->getProdutosNota($id_nota_original);
			if ($row_nota_fiscal_itens) {
				foreach ($row_nota_fiscal_itens as $nfirow) {
					$data_nfi = array(
						'id_empresa' => $nfirow->id_empresa,
						'id_nota' => $id_nota_nova,
						'id_produto' => $nfirow->id_produto,
						'produto_tipo_item' => $nfirow->produto_tipo_item,
						'id_produto_fornecedor' => $nfirow->id_produto_fornecedor,
						'codigonota' => $nfirow->codigonota,
						'codigobarras' => $nfirow->codigobarras,
						'ncm' => $nfirow->ncm,
						'cson' => $nfirow->cson,
						'cest' => $nfirow->cest,
						'cfop' => $nfirow->cfop,
						'cfop_entrada' => $nfirow->cfop_entrada,
						'unidade' => $nfirow->unidade,
						'quantidade' => $nfirow->quantidade,
						'valor_unitario' => $nfirow->valor_unitario,
						'valor_desconto' => $nfirow->valor_desconto,
						'outrasDespesasAcessorias' => $nfirow->outrasDespesasAcessorias,
						'valor_total' => $nfirow->valor_total,
						'valor_frete' => $nfirow->valor_frete,
						'valor_seguro' => $nfirow->valor_seguro,
						'origem' => $nfirow->origem,
						'icms_cst' => $nfirow->icms_cst,
						'icms_base' => $nfirow->icms_base,
						'icms_percentual' => $nfirow->icms_percentual,
						'icms_valor' => $nfirow->icms_valor,
						'icms_mod_st' => $nfirow->icms_mod_st,
						'icms_percentual_mva_st' => $nfirow->icms_percentual_mva_st,
						'icms_st_base' => $nfirow->icms_st_base,
						'icms_st_valor' => $nfirow->icms_st_valor,
						'icms_st_percentual' => $nfirow->icms_st_percentual,
						'pis_cst' => $nfirow->pis_cst,
						'pis_base' => $nfirow->pis_base,
						'pis_percentual' => $nfirow->pis_percentual,
						'pis_valor' => $nfirow->pis_valor,
						'cofins_cst' => $nfirow->cofins_cst,
						'cofins_base' => $nfirow->cofins_base,
						'cofins_percentual' => $nfirow->cofins_percentual,
						'cofins_valor' => $nfirow->cofins_valor,
						'ipi_cst' => $nfirow->ipi_cst,
						'ipi_base' => $nfirow->ipi_base,
						'ipi_percentual' => $nfirow->ipi_percentual,
						'ipi_valor' => $nfirow->ipi_valor,
						'f_pobreza_percentual' => $nfirow->f_pobreza_percentual,
						'f_pobreza_valor' => $nfirow->f_pobreza_valor,
						'valor_total_trib' => $nfirow->valor_total_trib,
						'valor_negociado_unitario' => $nfirow->valor_negociado_unitario,
						'valor_negociado_total' => $nfirow->valor_negociado_total,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("nota_fiscal_itens", $data_nfi);

					$numero_nota = getValue("numero_nota", "nota_fiscal", "id=" . $id_nota_nova);
					$obs_numero_nota = ($numero_nota > 0) ? 'Número: ' . $numero_nota : 'Id: ' . $id_nota_nova;
					$obs = "NOTA FISCAL: [ " . $obs_numero_nota . " ]";

					$tipo = ($row_nota_fiscal->operacao == 2) ? 2 : 1;
					$motivo = ($row_nota_fiscal->operacao == 2) ? 3 : 1;
					$qt = ($row_nota_fiscal->operacao == 2) ? $nfirow->quantidade * (-1) : $nfirow->quantidade;

					$data_estoque = array(
						'id_empresa' => $nfirow->id_empresa,
						'id_produto' => $nfirow->id_produto,
						'quantidade' => $qt,
						'tipo' => 2,
						'motivo' => 3,
						'observacao' => $obs,
						'id_ref' => $id_nota_nova,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("produto_estoque", $data_estoque);

					$totalestoque = $this->getEstoqueTotal($nfirow->id_produto);
					$data_update = array(
						'estoque' => $totalestoque,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("produto", $data_update, "id=" . $nfirow->id_produto);
				}
			}

			$row_despesas = $this->getDespesasNota($id_nota_original);
			if ($row_despesas) {
				$descricao = 'DESPESA AUTOMATICA DE NOTA FISCAL';
				foreach ($row_despesas as $drow) {
					$data_despesa = array(
						'id_empresa' => $drow->id_empresa,
						'id_nota' => $id_nota_nova,
						'id_conta' => $drow->id_conta,
						'id_custo' => $drow->id_custo,
						'duplicata' => $drow->duplicata,
						'descricao' => $descricao,
						'valor' => $drow->valor,
						'valor_pago' => $drow->valor_pago,
						'data_vencimento' => "NOW()",
						'tipo' => $drow->tipo,
						'cheque' => $drow->cheque,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("receita", $data_receita);
				}
			}
			Filter::msgOk(lang('NOTA_DUPLICAR_OK'), "index.php?do=notafiscal&acao=editar&id=" . $id_nota_nova);
		} else {
			Filter::msgAlert(lang('NAOPROCESSADO'));
		}
	}

	/**
	 * Produto::getItemVenda()
	 *
	 * @return
	 */
	public function getItemVenda($id_venda = 0)
	{
		$sql = "SELECT v.*, p.nome as produto, p.cfop, p.ncm, p.unidade, p.cest, p.icms_cst, p.anp, p.valor_partida,
			p.codigo, p.codigobarras, p.icms_percentual, p.icms_percentual_st, p.mva_percentual, p.pis_cst, p.pis_aliquota,
			p.cofins_cst, p.cofins_aliquota, p.ipi_cst, p.ipi_saida_codigo
			FROM cadastro_vendas as v
			LEFT JOIN produto as p ON p.id = v.id_produto
			WHERE v.id_venda = $id_venda AND v.inativo = 0
			ORDER BY v.id DESC ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getItensOrdemServico()
	 *
	 * @return
	 */
	public function getItensOrdemServico($id_os = 0)
	{
		$sql = "SELECT oi.*, p.nome as produto, p.cfop, p.ncm, p.unidade, p.cest, p.icms_percentual, p.icms_cst, p.anp, p.codigo, p.codigobarras "
			. "\n FROM ordem_itens as oi"
			. "\n LEFT JOIN produto as p ON p.id = oi.id_produto "
			. "\n WHERE oi.id_ordem = $id_os AND oi.inativo = 0"
			. "\n ORDER BY oi.id DESC";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::gerarNFEvenda(id_venda)
	 *
	 * @return
	 */
	public function gerarNFEvenda($id_venda)
	{
		if ($id_venda > 0) {
			$id_nota = getValue("id_nota_fiscal","vendas","id=".$id_venda);
			if ($id_nota && $id_nota>0)
				Filter::$msgs['nota_duplicada'] = lang('MSG_ERRO_NOTA_VENDA');

			if (empty(Filter::$msgs)) {
				$row_venda = Core::getRowById("vendas", $id_venda);
				$cpf_cnpj = getValue('cpf_cnpj', 'cadastro', 'id=' . $row_venda->id_cadastro);

				$valor_nota = $row_venda->valor_pago;
				$total_produto_nota = $row_venda->valor_pago;

				$valor_base_st = 0;
				$valor_st = 0;
				$descriminacao = "";
				$inf_adicionais = "";

				$data = array(
					'id_empresa' => $row_venda->id_empresa,
					'id_venda' => $id_venda,
					'modelo' => '2',
					'operacao' => '2',
					'id_cadastro' => $row_venda->id_cadastro,
					'cpf_cnpj' => $cpf_cnpj,
					'numero_nota' => "NULL",
					'data_emissao' => "NOW()",
					'valor_base_st' => $valor_base_st,
					'valor_st' => $valor_st,
					'descriminacao' => $descriminacao,
					'inf_adicionais' => $inf_adicionais,
					'apresentar_duplicatas' => 0,
					'valor_produto' => $total_produto_nota,
					'valor_nota' => $valor_nota,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);

				$id_nota = self::$db->insert("nota_fiscal", $data);
				$message = lang('NOTA_ADICIONADO_OK');

				$vincula_id_nota = array(
					'id_nota' => $id_nota
				);

				self::$db->update('cadastro_financeiro', $vincula_id_nota, "id_venda=". $id_venda);

				$data_venda = array(
					'id_nota_fiscal' => $id_nota
				);
				self::$db->update("vendas", $data_venda, "id=" . $id_venda);

				$retorno_row = $this->getItemVenda($id_venda);
				$total_valor_ipi = 0;
				$total_icms_st_valor = 0;

				foreach ($retorno_row as $prow) {
					$id_produto_fornecedor = getValue('id', 'produto_fornecedor', 'id_produto=' . $prow->id_produto . ' AND id_cadastro=' . $prow->id_cadastro);

					// Calcular o ICMS do Produto
					$icms_valor = round(($prow->valor_total / 100) * $prow->icms_percentual, 2);

					// Calcular o ICMS do Produto para atualizar total da Nota Fiscal
					$icms_st_base = round($prow->valor_total + (($prow->valor_total / 100) * $prow->mva_percentual), 2);
					$icms_st_valor = round((($icms_st_base / 100) * $prow->icms_percentual_st) - $icms_valor, 2);
					$total_icms_st_valor += $icms_st_valor;

					// Calcular o IPI do Produto para atualizar total da Nota Fiscal
					$ipi_base = $prow->valor_total;
					$ipi_valor = round(($ipi_base / 100) * $prow->ipi_cst, 2);
					$total_valor_ipi += $ipi_valor;

					// Calcular o PIS do Produto
					$pis_base = round($prow->valor_total - $icms_valor, 2);
					$pis_valor = round(($pis_base / 100) * $prow->pis_aliquota, 2);

					// Calcular o COFINS do Produto
					$cofins_base = round($prow->valor_total - $icms_valor, 2);
					$cofins_valor = round(($cofins_base / 100) * $prow->cofins_aliquota, 2);

					$data_nota = array(
						'id_empresa' => $prow->id_empresa,
						'id_nota' => $id_nota,
						'id_cadastro' => $prow->id_cadastro,
						'id_produto' => $prow->id_produto,
						'produto_tipo_item' => '00',
						'id_produto_fornecedor' => ($id_produto_fornecedor && $id_produto_fornecedor > 0) ? intval($id_produto_fornecedor) : 0,
						'unidade' => $prow->unidade,
						'quantidade' => $prow->quantidade,
						'cfop' => $prow->cfop,
						'ncm' => $prow->ncm,
						'cest' => $prow->cest,
						'valor_unitario' => $prow->valor,
						'valor_desconto' => $prow->valor_desconto,
						'valor_total' => $prow->valor_total,
						'outrasDespesasAcessorias' => $prow->valor_despesa_acessoria,
						'icms_cst' => $prow->icms_cst,
						'icms_percentual' => $prow->icms_percentual,
						'icms_base' => $prow->valor_total,
						'icms_valor' => $icms_valor,
						'icms_percentual_mva_st'=> $prow->mva_percentual,
						'icms_st_percentual' => $prow->icms_percentual_st,
						'icms_st_base' => $icms_st_base,
						'icms_st_valor' => $icms_st_valor,
						'pis_cst' => $prow->pis_cst,
						'pis_percentual' => $pis_valor > 0 ? $prow->pis_aliquota : 0,
						'pis_base' => $pis_valor > 0 ? $pis_base : 0,
						'pis_valor' => $pis_valor > 0 ? $pis_valor : 0,
						'cofins_cst' => $prow->cofins_cst,
						'cofins_percentual' => $cofins_valor > 0 ? $prow->cofins_aliquota : 0,
						'cofins_base' => $cofins_valor > 0 ? $cofins_base : 0,
						'cofins_valor' => $cofins_valor > 0 ? $cofins_valor : 0,
						'ipi_cst' => $prow->ipi_saida_codigo,
						'ipi_percentual' => $ipi_valor > 0 ? $prow->ipi_cst : 0,
						'ipi_base' => $ipi_valor > 0 ? $ipi_base : 0,
						'ipi_valor' => $ipi_valor > 0 ? $ipi_valor : 0,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("nota_fiscal_itens", $data_nota);
				}

				$data_total_nota = array(
					'valor_nota' => $valor_nota + $total_icms_st_valor + $total_valor_ipi
				);
				self::$db->update("nota_fiscal", $data_total_nota, "id=" . $id_nota);

				$redirecionar = "index.php?do=notafiscal&acao=editar&id=" . $id_nota;

				if ($id_nota) {
					Filter::msgOk($message, $redirecionar);
				} else {
					Filter::msgOk(lang('NAOPROCESSADO'));
				}
			} else {
				Filter::msgStatus();
			}
		} else {
			Filter::msgOk(lang('NAOPROCESSADO'));
		}
	}

	/**
	 * Produto::gerarNFeOS(id_os)
	 *
	 * @return
	 */
	public function gerarNFeOS($id_os)
	{
		if ($id_os > 0) {

			$row_os = Core::getRowById("ordem_servico", $id_os);

			if (!$row_os->id_nota_produto && !$row_os->id_fatura) {

				$cpf_cnpj = getValue('cpf_cnpj', 'cadastro', 'id=' . $row_os->id_cadastro);

				$valor_nota = $row_os->valor_produto;
				$total_produto_nota = $row_os->valor_produto;

				$valor_base_st = 0;
				$valor_st = 0;
				$descriminacao = "";
				$inf_adicionais = "";

				$data = array(
					'id_empresa' => $row_os->id_empresa,
					'id_ordem_servico' => $id_os,
					'modelo' => '2',
					'operacao' => '2',
					'id_cadastro' => $row_os->id_cadastro,
					'cpf_cnpj' => $cpf_cnpj,
					'numero_nota' => "NULL",
					'data_emissao' => "NOW()",
					'valor_base_st' => $valor_base_st,
					'valor_st' => $valor_st,
					'descriminacao' => $descriminacao,
					'inf_adicionais' => $inf_adicionais,
					'apresentar_duplicatas' => 0,
					'valor_produto' => $total_produto_nota,
					'valor_nota' => $valor_nota,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_nota = self::$db->insert("nota_fiscal", $data);
				$message = lang('NOTA_ADICIONADO_OK');

				$data_os = array(
					'id_nota_produto' => $id_nota
				);
				self::$db->update("ordem_servico", $data_os, "id=" . $id_os);

				$retorno_row = $this->getItensOrdemServico($id_os);
				$total_valor_ipi = 0;

				foreach ($retorno_row as $prow) {
					$id_produto_fornecedor = getValue('id', 'produto_fornecedor', 'id_produto=' . $prow->id_produto . ' AND id_cadastro=' . $prow->id_cadastro);
					$data_nota = array(
						'id_empresa' => $row_os->id_empresa,
						'id_nota' => $id_nota,
						'id_cadastro' => $prow->id_cadastro,
						'id_produto' => $prow->id_produto,
						'produto_tipo_item' => '00',
						'id_produto_fornecedor' => ($id_produto_fornecedor && $id_produto_fornecedor > 0) ? intval($id_produto_fornecedor) : 0,
						'quantidade' => $prow->quantidade,
						'cfop' => $prow->cfop,
						'valor_unitario' => $prow->valor,
						'valor_desconto' => 0,
						'valor_total' => $prow->valor_total,
						'outrasDespesasAcessorias' => 0,
						'icms_cst' => $prow->icms_cst,
						'icms_percentual' => $prow->icms_percentual,
						'icms_base' => $prow->valor_total,
						'icms_valor' => round(($prow->valor_total / 100) * $prow->icms_percentual, 2),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("nota_fiscal_itens", $data_nota);

					// Calcular o IPI do Produto para atualizar a Nota Fiscal
					$row_produto = Core::getRowById("produto", $prow->id_produto);
					$ipi_produto_base = floatval($row_produto->valor_custo) + floatval($row_produto->valor_frete);
					$ipi_produto_valor = floatval($ipi_produto_base) * (floatval($row_produto->ipi_cst) / 100);
					$total_valor_ipi += $ipi_produto_valor;
				}

				// ATUALIZAÇÃO DO CÁLCULO DE IPI NA NOTA FISCAL
				$data_ipi_nota = array(
					'valor_ipi' => $total_valor_ipi,
					'valor_nota' => $valor_nota + $total_valor_ipi
				);
				self::$db->update("nota_fiscal", $data_ipi_nota, "id=" . $id_nota);

				$redirecionar = "index.php?do=notafiscal&acao=editar&id=" . $id_nota;

				if ($id_nota) {
					Filter::msgOk($message, $redirecionar);
				} else
					Filter::msgAlert(lang('NAOPROCESSADO'));
			} else {
				Filter::msgAlert(lang('NOTA_FISCAL_OS_NAO'));
			}
		} else {
			Filter::msgAlert(lang('NAOPROCESSADO'));
		}
	}

	/**
	 * Produto::gerarFaturaOS(id_os)
	 *
	 * @return
	 */
	public function gerarFaturaOS($id_os)
	{
		if ($id_os > 0) {

			$row_os = Core::getRowById("ordem_servico", $id_os);

			if (!$row_os->id_fatura && (!$row_os->id_nota_servico || !$row_os->id_nota_produto)) {

				$cpf_cnpj = getValue('cpf_cnpj', 'cadastro', 'id=' . $row_os->id_cadastro);

				$valor_produtos = $row_os->valor_produto;
				$valor_servicos = $row_os->valor_total-$row_os->valor_produto;
				$valor_faturar  = ($row_os->id_nota_produto==0) ? $valor_produtos : 0;
				$valor_faturar += ($row_os->id_nota_servico==0) ? $valor_servicos : 0;

				$valor_nota = $valor_faturar;
				$total_produto_nota = 0;

				$valor_base_st = 0;
				$valor_st = 0;
				$descriminacao = "Fatura de Ordem de Serviço número ".$id_os;
				$inf_adicionais = "";

				$data = array(
					'id_empresa' => $row_os->id_empresa,
					'id_ordem_servico' => $id_os,
					'modelo' => '3',
					'operacao' => '2',
					'id_cadastro' => $row_os->id_cadastro,
					'cpf_cnpj' => $cpf_cnpj,
					'numero_nota' => "NULL",
					'data_emissao' => "NOW()",
					'valor_base_st' => $valor_base_st,
					'valor_st' => $valor_st,
					'descriminacao' => $descriminacao,
					'inf_adicionais' => $inf_adicionais,
					'apresentar_duplicatas' => 0,
					'valor_produto' => $total_produto_nota,
					'valor_nota' => $valor_nota,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_nota = self::$db->insert("nota_fiscal", $data);
				$message = lang('NOTA_ADICIONADO_OK');

				$data_os = array(
					'id_fatura' => $id_nota
				);
				self::$db->update("ordem_servico", $data_os, "id=" . $id_os);

				$redirecionar = "index.php?do=notafiscal&acao=editar&id=" . $id_nota;

				if ($id_nota) {
					Filter::msgOk($message, $redirecionar);
				} else
					Filter::msgAlert(lang('NAOPROCESSADO'));
			} else {
				Filter::msgAlert(lang('NOTA_FATURA_OS_NAO'));
			}
		} else {
			Filter::msgAlert(lang('NAOPROCESSADO'));
		}
	}

	/**
	 * Cadastro::processarTrocaProduto()
	 *
	 * @return
	 */
	public function processarTrocaProduto($id_caixa = 0)
	{
		$id_venda_troca = sanitize(post('id_venda'));

		if ($id_venda_troca)
			$row_venda_troca = Core::getRowById("vendas", $id_venda_troca);

		$valor_venda_troca = post('valor_venda_troca');

		$valor_venda_troca = round($valor_venda_troca, 2);
		$valor_produto_troca = post('valor_produto_troca');
		$valor_produto_troca = round($valor_produto_troca, 2);
		$id_venda = 0;
		$kit = 0;
		$valor = post('valor');
		$valor = round($valor, 2);

		$id_empresa = (empty($_POST['id_empresa'])) ? session('idempresa') : post('id_empresa');
		$desconto = round(converteMoeda(post('valor_desconto_troca')), 2);

		if (!$desconto)
			$desconto = 0;
		if (!$valor)
			$valor = 0;

		$desconto += converteMoeda($valor_venda_troca);

		$valor_pagar = $valor - $desconto;
		$id_cadastro = sanitize(post('id_cadastro'));
		$id_tabela = sanitize(post('id_tabela'));
		$produtos = (!empty($_POST['id_produto'])) ? $_POST['id_produto'] : null;
		$valores = (!empty($_POST['valor_venda'])) ? $_POST['valor_venda'] : 0;
		$quantidades = (!empty($_POST['quantidade'])) ? $_POST['quantidade'] : 0;
		$pagamentos = (!empty($_POST['id_pagamento'])) ? $_POST['id_pagamento'] : null;
		$pagos = (!empty($_POST['valor_pago'])) ? $_POST['valor_pago'] : 0;
		$parcelas = (!empty($_POST['parcela'])) ? $_POST['parcela'] : 0;
		$contar_produtos = (is_array($produtos)) ? count($produtos) : 0;
		$contar_pagamentos = (is_array($pagamentos)) ? count($pagamentos) : 0;

		if ($contar_produtos == 0)
			Filter::$msgs['contar_produtos'] = lang('MSG_ERRO_PRODUTO_VENDA');

		if (($contar_pagamentos == 0) && ($valor_pagar > 0))
			Filter::$msgs['contar_pagamentos'] = lang('MSG_ERRO_PAGAMENTO_VENDA');

		$soma_dinheiro = 0;
		$pago = 0;
		$troco = 0;
		$percentual_dinheiro = 0;

		for ($i = 0; $i < $contar_pagamentos; $i++) {
			$total_parcelas = ($parcelas[$i] == '' or $parcelas[$i] == 0) ? 1 : $parcelas[$i];
			$id_pagamento = $pagamentos[$i];
			$row_pagamento = Core::getRowById("tipo_pagamento", $id_pagamento);
			if ($total_parcelas > $row_pagamento->parcelas)
				Filter::$msgs[$i . 'total_parcelas'] = lang('MSG_ERRO_PARCELAS') . ": " . $row_pagamento->tipo;

			$pago += $pagos[$i];
			$pago = round($pago, 2);

			$id_tipo_categoria = getValue("id_categoria", "tipo_pagamento", "id=" . $id_pagamento);

			if ($id_tipo_categoria == '1')
				$soma_dinheiro += $pagos[$i];
		}

		if ($soma_dinheiro > 0) {
			$soma_restante = $pago - $soma_dinheiro;
			$total_pagar_dinheiro = $valor_pagar - $soma_restante;
			$troco = $soma_dinheiro - $total_pagar_dinheiro;
			$percentual_dinheiro = ($soma_dinheiro - $troco) / $soma_dinheiro;
			$pago = ($troco < 0) ? $pago : $pago - $troco;
		}

		if (empty($_POST['id_produto'])) {
			Filter::$msgs['contar_produtos'] = lang('MSG_ERRO_PRODUTO_VENDA');
		} elseif ($troco < $soma_dinheiro) {
			$qtdtotal = 0;
			for ($i = 0; $i < $contar_produtos; $i++) {

				$quantidade = ($quantidades[$i]) ? $quantidades[$i] : 1;
				$qtdtotal += $quantidade;
				$id_produto = $produtos[$i];
				$kit = getValue("kit", "produto", "id=" . $id_produto);
				$valida_estoque = getValue("valida_estoque", "produto", "id=" . $id_produto);

				if ($valida_estoque) {
					if ($kit) {
						$nomekit = getValue("nome", "produto", "id=" . $id_produto);
						$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque "
							. "\n FROM produto_kit as k"
							. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
							. "\n WHERE k.id_produto_kit = $id_produto "
							. "\n ORDER BY p.nome ";
						$retorno_row = self::$db->fetch_all($sql);
						if ($retorno_row) {
							foreach ($retorno_row as $exrow) {
								if ($quantidade > $exrow->estoque)
									Filter::$msgs[$i . 'estoque' . $exrow->id] = str_replace("[ESTOQUE]", $exrow->estoque, lang('MSG_ERRO_ESTOQUE') . " >> PRODUTO DO KIT = " . $exrow->nome);
							}
						}
					} else {
						$estoque = getValue("estoque", "produto", "id=" . $id_produto);
						if ($quantidade > $estoque)
							Filter::$msgs[$i . 'estoque'] = str_replace("[ESTOQUE]", $estoque, lang('MSG_ERRO_ESTOQUE'));
					}
				}
			}

			$sql = "SELECT (quantidade)"
				. "\n FROM tabela_precos"
				. "\n WHERE id = $id_tabela";
			$retorno = self::$db->first($sql);

			if ($qtdtotal < $retorno->quantidade) {
				Filter::$msgs['quantidade'] = str_replace("[QUANTIDADE]", $quantidade, lang('MSG_ERRO_QTDMINIMA'));
			}
		} else {
			($soma_dinheiro > 0) ? Filter::$msgs['total_pagamento'] = 'O valor do TROCO deve ser MENOR que o total pago em DINHEIRO.' : null;
		}

		$valor_pagar = round($valor_pagar, 2);
		$pago = round($pago, 2);

		if ($valor_pagar != $pago) {
			$erro_pagamento = str_replace("[VALOR_VENDA]", moeda($valor_pagar), lang('MSG_ERRO_VENDAS_PAGAMENTO_VALOR_DIFERENTE'));
			$erro_pagamento = str_replace("[VALOR_PAGO]", moeda($pago), $erro_pagamento);
			Filter::$msgs['valor_pagar'] = $erro_pagamento;
		}

		if (empty(Filter::$msgs)) {
			if (empty($_POST['id_cadastro']) and !empty($_POST['cadastro'])) {
				$nome = cleanSanitize(post('cadastro'));

				$data_cadastro = array(
					'nome' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
					'celular' => sanitize(post('celular')),
					'cliente' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_cadastro = self::$db->insert("cadastro", $data_cadastro);
			}

			$entrega = (empty($_POST['prazo_entrega'])) ? 0 : 1;
			$status_entrega = ($entrega == 1) ? 1 : 3;

			$data_venda = array(
				'id_empresa' => $id_empresa,
				'id_cadastro' => $id_cadastro,
				'id_caixa' => $id_caixa,
				'id_vendedor' => sanitize(post('id_vendedor')),
				'id_troca' => $id_venda_troca,
				'valor_troca' => converteMoeda($valor_venda_troca),
				'valor_total' => $valor,
				'valor_desconto' => $desconto,
				'valor_pago' => $pago + $troco,
				'entrega' => $entrega,
				'status_entrega' => $status_entrega,
				'troco' => $troco,
				'data_venda' => "NOW()",
				'prazo_entrega' => dataMySQL(post('prazo_entrega')),
				'observacao' => sanitize(post('observacao')),
				'usuario_venda' => session('nomeusuario'),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			if (empty($_POST['salvar'])) {
				$data_venda['pago'] = 1;
				$data_venda['usuario_pagamento'] = session('nomeusuario');
			} else {
				$data_venda['pago'] = 2;
			}

			$id_venda = self::$db->insert("vendas", $data_venda);

			$nomecliente = ($id_cadastro) ? getValue("nome", "cadastro", "id=" . $id_cadastro) : "";
			$porcentagem_desconto = ($desconto * 100) / $valor;

			for ($i = 0; $i < $contar_produtos; $i++) {
				$quantidade = ($quantidades[$i]) ? $quantidades[$i] : 1;
				$id_produto = $produtos[$i];
				// $valor_venda = $valores[$i];
				$valor_custo = getValue("valor_custo", "produto", "id=" . $id_produto);
				$valor_venda = converteMoeda($valores[$i]);

				$valor_total = $valor_venda * $quantidade;
				$quant_estoque = $quantidade * (-1);

				$valor_desconto = ($porcentagem_desconto * $valor_total) / 100;
				$valor_desconto = round($valor_desconto, 2);

				$data_cadastro_venda = array(
					'id_empresa' => $id_empresa,
					'id_cadastro' => $id_cadastro,
					'id_caixa' => $id_caixa,
					'id_venda' => $id_venda,
					'id_produto' => $id_produto,
					'id_tabela' => $id_tabela,
					'valor_custo' => $valor_custo,
					'valor' => $valor_venda,
					'quantidade' => $quantidade,
					'valor_desconto' => $valor_desconto,
					'valor_total' => $valor_total,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				if (empty($_POST['salvar'])) {
					$data_cadastro_venda['pago'] = 1;
				} else {
					$data_cadastro_venda['pago'] = 2;
				}
				$id_cadastro_venda = self::$db->insert("cadastro_vendas", $data_cadastro_venda);

				$kit = getValue("kit", "produto", "id=" . $id_produto);
				if ($kit) {
					$nomekit = getValue("nome", "produto", "id=" . $id_produto);
					$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade  "
						. "\n FROM produto_kit as k"
						. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
						. "\n WHERE k.id_produto_kit = $id_produto "
						. "\n ORDER BY p.nome ";
					$retorno_row = self::$db->fetch_all($sql);
					if ($retorno_row) {
						foreach ($retorno_row as $exrow) {
							$observacao = "VENDA DE KIT [$nomekit] PARA CLIENTE: " . $nomecliente;
							$quant_estoque_kit = $quantidade * $exrow->quantidade * (-1);
							$quant_produto_kit = $quantidade * (-1);

							$data_estoque = array(
								'id_empresa' => $id_empresa,
								'id_produto' => $exrow->id_produto,
								'quantidade' => $quant_estoque_kit,
								'tipo' => 2,
								'motivo' => 3,
								'observacao' => $observacao,
								'id_ref' => $id_cadastro_venda,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->insert("produto_estoque", $data_estoque);
							$totalestoque = $this->getEstoqueTotal($exrow->id_produto);
							$data_update = array(
								'estoque' => $totalestoque,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->update("produto", $data_update, "id=" . $exrow->id_produto);
						}

						$observacao = "VENDA DE PRODUTO PARA CLIENTE MEDIANTE TROCA: " . $nomecliente;

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

						$totalestoque = $this->getEstoqueTotal($id_produto);
						$data_update = array(
							'estoque' => $totalestoque,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("produto", $data_update, "id=" . $id_produto);
					}
				} else {
					$observacao = str_replace("[ID_VENDA]", $id_venda, lang('VENDA_PRODUTO_CLIENTE_TROCA'));
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
					$totalestoque = $this->getEstoqueTotal($id_produto);
					$data_update = array(
						'estoque' => $totalestoque,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("produto", $data_update, "id=" . $id_produto);
				}
			}

			///////////////////////////////////
			//Este trecho de código serve para ajustar o valor do desconto quando o mesmo for quebrado e gerar diferença no total.
			$novo_valor_desconto = $this->obterDescontosVenda($id_venda, $id_caixa, $id_empresa, $id_cadastro);
			if (round($novo_valor_desconto->vlr_desc, 2) != round($valor_desconto, 2)) {
				$novo_desconto = ($desconto - round($novo_valor_desconto->vlr_desc, 2)) + $novo_valor_desconto->valor_desconto;
				$data_desconto = array('valor_desconto' => $novo_desconto);
				self::$db->update("cadastro_vendas", $data_desconto, "id=" . $novo_valor_desconto->id);
			}
			///////////////////////////////////

			$data_vencimento = "NOW()";
			$valor_total_venda = $valor - $desconto;

			for ($j = 0; $j < $contar_pagamentos; $j++) {
				$total_parcelas = ($parcelas[$j] == '' or $parcelas[$j] == 0) ? 1 : $parcelas[$j];

				$tipo = $pagamentos[$j];
				$id_tipo_categoria = getValue("id_categoria", "tipo_pagamento", "id=" . $tipo);
				$valor_pago = $pagos[$j];

				$data = array(
					'id_empresa' => $id_empresa,
					'id_cadastro' => $id_cadastro,
					'id_caixa' => $id_caixa,
					'id_venda' => $id_venda,
					'tipo' => $tipo,
					'valor_total_venda' => $valor_total_venda,
					'total_parcelas' => $total_parcelas,
					'data_pagamento' => "NOW()",
					'usuario' => session('nomeusuario'),
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
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);

				$row_cartoes = Core::getRowById("tipo_pagamento", $tipo);
				$dias = $row_cartoes->dias;
				$taxa = $row_cartoes->taxa;
				$id_banco = (empty($_POST['id_banco'])) ? $row_cartoes->id_banco : post('id_banco');

				if ($id_tipo_categoria == '1') {
					if (empty($_POST['salvar'])) {
						$data['pago'] = 1;
						$data['data_pagamento'] = "NOW()";
					} else {
						$data['pago'] = 2;
					}
					$valor_pago = $valor_pago * $percentual_dinheiro;
					$data['valor_pago'] = $valor_pago;
					$data['data_vencimento'] = $data_vencimento;

					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
				} elseif ($id_tipo_categoria == '2' or $id_tipo_categoria == '4') {
					if (empty($_POST['salvar'])) {
						$data['pago'] = 1;
						$data['data_pagamento'] = "NOW()";
						$data_receita['pago'] = 0;
					} else {
						$data['pago'] = 2;
						$data_receita['pago'] = 2;
					}
					$descricao = ($id_tipo_categoria == '2') ? "CHEQUE" : "BOLETO";
					$data['valor_pago'] = $valor_pago;
					$data['id_banco'] = $id_banco;
					$data['nome_cheque'] = sanitize(post('nome_cheque'));
					$data['banco_cheque'] = sanitize(post('banco_cheque'));
					$data['numero_cheque'] = sanitize(post('numero_cheque'));
					$data['data_vencimento'] = $data_vencimento;

					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);

					if ($id_tipo_categoria == 4) {
						$data_temp = (empty($_POST['data_boleto'])) ? somarData(date('d/m/Y'), 3, 0, 0) : sanitize($_POST['data_boleto']);
					} else {
						$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
					}

					$data_parcela = explode('/', $data_temp);
					$valor_cheque = round($valor_pago / $total_parcelas, 2);
					$diferenca = $valor_pago - ($valor_cheque * $total_parcelas);

					for ($i = 0; $i < $total_parcelas; $i++) {
						$newData = novadata($data_parcela[1] + $i, $data_parcela[0], $data_parcela[2]);
						$parc = ($i + 1);
						$p = $parc . "/" . $total_parcelas;
						$data_receita['id_banco'] = $id_banco;
						$data_receita['descricao'] = "$descricao - $p - " . $nomecliente;
						$data_receita['valor'] = $valor_cheque;
						$data_receita['valor_pago'] = $valor_cheque;
						$data_receita['id_pagamento'] = $id_pagamento;
						$data_receita['data_pagamento'] = $newData;
						$data_receita['parcela'] = $parc;
						if ($i == 0) {
							$data_receita['valor'] = $valor_cheque + $diferenca;
							$data_receita['valor_pago'] = $valor_cheque + $diferenca;
						}
						self::$db->insert("receita", $data_receita);
					}
				} elseif ($id_tipo_categoria == '3' or $id_tipo_categoria == '6') {
					if (empty($_POST['salvar'])) {
						$data['pago'] = 1;
						$data['data_pagamento'] = "NOW()";
						$data_receita['pago'] = 1;
					} else {
						$data['pago'] = 2;
						$data_receita['pago'] = 2;
					}
					$data['id_banco'] = $id_banco;
					$data['valor_pago'] = $valor_pago;
					$data['data_vencimento'] = $data_vencimento;

					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);

					$data_receita['id_banco'] = $id_banco;
					$data_receita['descricao'] = "BANCO - " . $nomecliente;
					$data_receita['valor'] = $valor_pago;
					$data_receita['valor_pago'] = $valor_pago;
					$data_receita['id_pagamento'] = $id_pagamento;
					$data_receita['data_recebido'] = $data_vencimento;
					$data_receita['parcela'] = 1;
					self::$db->insert("receita", $data_receita);
				} else {
					if (empty($_POST['salvar'])) {
						$data['pago'] = 1;
						$data['data_pagamento'] = "NOW()";
						$data_receita['pago'] = 1;
					} else {
						$data['pago'] = 2;
						$data_receita['pago'] = 2;
					}

					$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
					$data_parcela = explode('/', $data_temp);
					$valor_taxa = $valor_pago * $taxa / 100;
					$valor_cartao = $valor_pago - $valor_taxa;
					$valor_parcelas_pago = round($valor_pago / $total_parcelas, 2);
					$valor_parcelas_cartao = $valor_cartao / $total_parcelas;
					$diferenca = $valor_pago - ($valor_parcelas_pago * $total_parcelas);
					$diferenca_parcela = $valor_cartao - ($valor_parcelas_cartao * $total_parcelas);
					$data['id_banco'] = $id_banco;
					$data['valor_pago'] = $valor_pago;
					$data['valor_total_cartao'] = $valor_cartao;
					$data['valor_parcelas_cartao'] = $valor_parcelas_cartao;
					$data['parcelas_cartao'] = $total_parcelas;
					$data['data_vencimento'] = $data_vencimento;
					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
					for ($i = 1; $i < $total_parcelas + 1; $i++) {
						if ($dias == 30) {
							$m = $i - 1;
							$newData = novadata($data_parcela[1] + $m, $data_parcela[0], $data_parcela[2]);
						} else {
							$newData = novadata($data_parcela[1], $data_parcela[0] + ($i * $dias), $data_parcela[2]);
						}
						$p = $i . "/" . $total_parcelas;
						$data_receita['id_banco'] = $id_banco;
						$data_receita['descricao'] = $row_cartoes->tipo . " - $p - " . $nomecliente;
						$data_receita['valor'] = $valor_parcelas_pago;
						$data_receita['valor_pago'] = $valor_parcelas_cartao;
						$data_receita['data_recebido'] = $newData;
						$data_receita['parcela'] = $i;
						if ($i == 1) {
							$data_receita['valor'] = $valor_parcelas_pago + $diferenca_parcela;
							$data_receita['valor_pago'] = $valor_parcelas_cartao + $diferenca_parcela;
						}
						$data_receita['id_pagamento'] = $id_pagamento;
						self::$db->insert("receita", $data_receita);
					}
				}
			}

			///////////////////////////////////////////////////////////////////////////////////////////
			///DEVOLVE O PRODUTO TROCADO PARA O ESTOQUE////////////////////////////////////////////////

			$qtde_produto = post('qtde_produto');

			if ($id_venda_troca) {
				$observacao = str_replace("[ID_VENDA_TROCA]", $id_venda_troca, lang('PRODUTO_TROCA_OBSERVACAO'));
				$observacao = str_replace("[ID_VENDA]", $id_venda, $observacao);
			} else
				$observacao = str_replace("[ID_VENDA]", $id_venda, lang('PRODUTO_TROCA_OBSERVACAO_AVULSO'));

			///////////////////////////////////////////////////////////////////////////////////////////
			///LANÇA A NOTA FISCAL DE DEVOLUÇÃO CASO A NOTA DE TROCA TAMBÉM SEJA FISCAL////////////////
			if ($id_venda_troca) {
				if ($row_venda_troca->fiscal) {
					$data_nota = array(
						'id_empresa' => $id_empresa,
						'id_venda_devolucao' => $id_venda_troca,
						'modelo' => '2', //PRODUTO
						'operacao' => 1, //ENTRADA
						'id_cadastro' => $id_cadastro,
						'cpf_cnpj' => ($id_cadastro) ? getValue("cpf_cnpj", "cadastro", "id=" . $id_cadastro) : "",
						'numero_nota' => "NULL",
						'nfe_referenciada' => $row_venda_troca->chaveacesso,
						'data_emissao' => "NOW()",
						'valor_produto' => $valor_produto_troca,
						'valor_nota' => $valor_produto_troca,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$id_nota = self::$db->insert("nota_fiscal", $data_nota);
				}
			}
			///////////////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////
			if ($id_venda_troca) {
				foreach ($qtde_produto as $qtde) {
					if ($qtde) {

						$array_qtde = explode(",", $qtde);
						//$array_produtos[$array_qtde[0]] = $array_qtde[1];
						$row_cadastro_venda = Core::getRowById("cadastro_vendas", $array_qtde[0]);
						$data_estoque = array(
							'id_empresa' => $id_empresa,
							'id_produto' => $row_cadastro_venda->id_produto,
							'quantidade' => converteMoeda($array_qtde[1]),
							'tipo' => 2,
							'motivo' => 3,
							'observacao' => $observacao,
							'id_ref' => $array_qtde[0],
							'id_venda_troca' => $id_venda,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->insert("produto_estoque", $data_estoque);

						$data_qtde_trocado = array(
							'quantidade_trocada' => $row_cadastro_venda->quantidade_trocada + converteMoeda($array_qtde[1]),
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("cadastro_vendas", $data_qtde_trocado, "id=" . $array_qtde[0]);

						$totalestoque = $this->getEstoqueTotal($row_cadastro_venda->id_produto);
						$data_update = array(
							'estoque' => $totalestoque,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("produto", $data_update, "id=" . $row_cadastro_venda->id_produto);

						if ($id_venda_troca) {
							if ($row_venda_troca->fiscal) {
								$id_produto_fornecedor = getValue('id', 'produto_fornecedor', 'id_produto=' . $row_cadastro_venda->id_produto . ' AND id_cadastro=' . $id_cadastro);
								$data_nota = array(
									'id_empresa' => $id_empresa,
									'id_nota' => $id_nota,
									'id_cadastro' => $id_cadastro,
									'id_produto' => $row_cadastro_venda->id_produto,
									'id_produto_fornecedor' => $id_produto_fornecedor,
									'quantidade' => converteMoeda($array_qtde[1]),
									'valor_unitario' => $row_cadastro_venda->valor,
									'valor_desconto' => ($row_cadastro_venda->valor_desconto / $row_cadastro_venda->quantidade) * converteMoeda($array_qtde[1]),
									'valor_total' => (($row_cadastro_venda->valor_total - $row_cadastro_venda->valor_desconto) / $row_cadastro_venda->quantidade) * converteMoeda($array_qtde[1]),
									'icms_cst' => getValue("icms_cst", "produto", "id=" . $row_cadastro_venda->id_produto),
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->insert("nota_fiscal_itens", $data_nota);
							}
						}
					}
				}
			} else {
				foreach ($qtde_produto as $vlr_produto_troca) {
					if ($vlr_produto_troca) {
						$array_prod = explode(",", $vlr_produto_troca);
						$data_estoque = array(
							'id_empresa' => $id_empresa,
							'id_produto' => $array_prod[0],
							'quantidade' => converteMoeda($array_prod[1]),
							'tipo' => 2,
							'motivo' => 3,
							'observacao' => $observacao,
							'id_ref' => 0,
							'id_venda_troca' => $id_venda,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->insert("produto_estoque", $data_estoque);

						$totalestoque = $this->getEstoqueTotal($array_prod[0]);
						$data_update = array(
							'estoque' => $totalestoque,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("produto", $data_update, "id=" . $array_prod[0]);
					}
				}
			}

			///////////////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////

			if (self::$db->affected()) {
				$message = lang('CADASTRO_VENDA_FINALIZADA');
				$redirecionar = (isset($id_nota) && $id_nota > 0) ? "index.php?do=notafiscal&acao=editar&id=$id_nota" : "index.php?do=vendas_do_dia";
				Filter::msgOkRecibo($message, $redirecionar, $id_venda);
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::obterDescontosVenda()
	 *
	 * @return
	 */
	public function obterDescontosVenda($id_venda, $id_caixa = 0, $id_empresa = 0, $id_cadastro = 0)
	{
		$sql = "SELECT SUM(valor_desconto) as vlr_desc, id, valor_desconto "
			. "\n FROM cadastro_vendas "
			. "\n where id_venda = '$id_venda' AND id_caixa = '$id_caixa' AND id_empresa = '$id_empresa' AND id_cadastro = '$id_cadastro' AND inativo = 0";
		$row = self::$db->first($sql);
		return ($row) ? $row : 0;
	}


	/**
	 * Produto::ObterListaProdutosTroca()
	 *
	 * @return
	 */
	public function ObterListaProdutosTroca($array_produtos)
	{
		//Array qtde_produto( [0] => 11592#1 [1] => 11593#2 [2] => 11594#3 )
		$array_qtde = array();
		foreach ($array_produtos as $row_produto) {
			$venda_qtde = explode(",", $row_produto);
			array_push($array_qtde, $venda_qtde[0]);
		}
		$str_produto = implode(',', $array_qtde);
		$sql = "SELECT * FROM cadastro_vendas WHERE id IN ($str_produto)";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	public function getComparacaoComBD($id_universal = false, $codigobarras = false, $nome = false)
	{

		$where_cb = ($codigobarras) ? "AND p.codigobarras = " . $codigobarras : "";
		$where_iduui = ($id_universal) ? "AND p.id_universal LIKE '%" . $id_universal . "%' " : "";
		$where_nome = ($nome) ? "AND p.nome LIKE '%" . $nome . "%' " : "";

		$sql = " SELECT p.id, p.nome, p.id_universal, p.codigobarras "
			. "\n FROM produto as p "
			. "\n WHERE p.inativo = 0 "
			. "\n $where_cb "
			. "\n $where_iduui "
			. "\n $where_nome ";

		$row = self::$db->first($sql);
		return ($row) ? $row : '';
	}

	public function processarPlanilhaProdutosImportacao()
	{
		require_once "PHPExcel/Classes/PHPExcel.php";
		$redirecionar = "index.php?do=produto&acao=importarprodutos";

		try {
			self::$db->query("START TRANSACTION");

			$arquivo = null;
			foreach ($_POST as $nome_campo => $valor) {
				if (strpos($nome_campo, "tmpname") !== false) {
					$arquivo = UPLOADS . $valor;
					break;
				}
			}

			if (!$arquivo || !file_exists($arquivo)) {
				throw new Exception("Arquivo não foi encontrado.");
			}

			$reader = PHPExcel_IOFactory::createReaderForFile($arquivo);
			$excel_Obj = $reader->load($arquivo);
			$worksheet = $excel_Obj->getActiveSheet();
			$lastRow = $worksheet->getHighestRow();

			$nomes_planilha = [];
			$codigos_barras_planilha = [];
			$produtos_validos = [];
			$erros = [];

			for ($row = 2; $row <= $lastRow; $row++) {
				// Verifica se a linha está totalmente vazia (colunas A a O = 1 a 14)
				$linha_vazia = true;
				for ($col = 1; $col <= 14; $col++) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$valorCelula = trim((string) $cell->getValue());

					if ($valorCelula !== '') {
						$linha_vazia = false;
						break;
					}
				}
				if ($linha_vazia) {
					continue;
				}

				$nome = (string) trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
				$ncm = (string) trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
				$cfop_saida = (string) trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
				$icms_cst = (string) trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());
				$codigobarras = (string) trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());

				if (empty($nome)) {
					$erros[] = "<b>Linha $row</b>:do arquivo: Nome do produto é obrigatório.";
					continue; // OU BREAK
				}

				if (empty($ncm)) {
					$erros[] = "<b>Linha $row</b>: NCM do produto <b>'$nome'</b> é obrigatório.";
					continue;
				}

				if (empty($cfop_saida)) {
					$erros[] = "<b>Linha $row</b>: CFOP do produto <b>'$nome'</b> é obrigatório.";
					continue;
				}

				if (empty($icms_cst)) {
					$erros[] = "<b>Linha $row</b>: ICMS CST do produto <b>'$nome'</b> é obrigatório.";
					continue;
				}

				if (in_array(strtoupper($nome), $nomes_planilha)) {
					$erros[] = "<b>Linha $row</b>: Nome do produto <b>'$nome'</b> duplicado na planilha Excel.";
					continue;
				}

				if (!empty($codigobarras) && in_array($codigobarras, $codigos_barras_planilha)) {
					$erros[] = "<b>Linha $row</b>: Código de barras do produto <b>'$nome'</b> duplicado na planilha Excel.";
					continue;
				}

				if (!empty($codigobarras)) {
					$sql = "SELECT id FROM produto WHERE inativo = 0 AND codigobarras = '" . sanitize($codigobarras) . "'";

					if (self::$db->first($sql)) {
						$erros[] = "<b>Linha $row</b>: Código de barras do produto <b>'$nome'</b> já existe no cadastro.";
						continue;
					}
				}

				$sql = "SELECT id FROM produto WHERE inativo = 0 AND codigobarras= '" . sanitize($codigobarras) . "'";

				if (self::$db->first($sql)) {
					$erros[] = "<b>Linha $row</b>: Produto <b>'$nome'</b> já existe no cadastro.";
					continue;
				}

				$nomes_planilha[] = strtoupper($nome);
				if (!empty($codigobarras)) {
					$codigos_barras_planilha[] = $codigobarras;
				}

				$produto = [
					'nome' => strtoupper(sanitize($nome)),
					'codigo' => (string) trim($worksheet->getCellByColumnAndRow(3, $row)->getValue()),
					'codigobarras' => limparNumero($codigobarras),
					'ncm' => limparNumero($ncm),
					'cfop' => limparNumero($cfop_saida),
					'cest' => limparNumero((string) trim($worksheet->getCellByColumnAndRow(7, $row)->getValue())),
					'icms_cst' => limparNumero($icms_cst),
					'unidade' => strtoupper(sanitize((string) trim($worksheet->getCellByColumnAndRow(9, $row)->getValue()))),
					'valor_custo' => converteMoeda((float) trim($worksheet->getCellByColumnAndRow(10, $row)->getValue())),
					'valor_venda' => converteMoeda((float) trim($worksheet->getCellByColumnAndRow(11, $row)->getValue())),
					'estoque' => converteMoeda((float) $worksheet->getCellByColumnAndRow(12, $row)->getValue() <= 0 ? 1 : (float) $worksheet->getCellByColumnAndRow(12, $row)->getValue()),
					'valida_estoque' => (int) trim($worksheet->getCellByColumnAndRow(13, $row)->getValue()),
					'monofasico' => (int) trim($worksheet->getCellByColumnAndRow(14, $row)->getValue()),
					'grade' => 1,
					'inativo' => 0,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				];
				$produtos_validos[] = $produto;
			}

			if (!empty($erros)) {
			    $_SESSION['erros_importacao'] = $erros;
				throw new Exception("Erros foram detectados na planilha Excel. A página será recarregada com informações a respeito.");
			}
			if (empty($produtos_validos)) {
				$erros = ["Nenhum produto válido foi encontrado na planilha Excel! Por favor, tente novamente."];
				$_SESSION['erros_importacao'] = $erros;

				throw new Exception("Nenhum produto válido encontrado na planilha Excel.");
			}

			$contador = 0;
			foreach ($produtos_validos as $produto) {
				if (empty($produto['id_universal'])) {
					$produto['id_universal'] = strtolower(str_replace('-', '', sanitize(gerarIdUniversal())));
				}
				$valor_venda = isset($produto['valor_venda']) ? (float)$produto['valor_venda'] : 0;
				unset($produto['valor_venda']);
				$id_produto = self::$db->insert(self::uTable, $produto);

				$data_estoque = [
					'id_empresa' => session('idempresa'),
					'id_produto' => $id_produto,
					'valor_custo' => $produto['valor_custo'],
					'quantidade' => $produto['estoque'],
					'tipo' => 1,
					'motivo' => 1,
					'observacao' => 'AJUSTE DE ESTOQUE INICIAL - IMPORTAÇÃO',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				];
				self::$db->insert("produto_estoque", $data_estoque);

				self::$db->update(self::uTable, ['codigo_interno' => '#' . $id_produto], "id=" . $id_produto);

				$valor_custo = $produto['valor_custo'] > 0 ? $produto['valor_custo'] : 1;
				$percentual = (($valor_venda / $valor_custo) - 1) > 0 ? ($valor_venda / $valor_custo) - 1 : 1;

				$retorno_row = $this->getTabelaPrecos();
				if ($retorno_row) {
					foreach ($retorno_row as $exrow) {
						$data_tabela = [
							'id_tabela' => $exrow->id,
							'id_produto' => $id_produto,
							'percentual' => $percentual * 100,
							'valor_venda' => round($valor_venda, 2),
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						];
						self::$db->insert("produto_tabela", $data_tabela);
					}
				} else {
					$data_tabela_preco = [
						'tabela' => 'PADRAO',
						'percentual' => $percentual * 100,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					];
					$id_tabela = self::$db->insert("tabela_precos", $data_tabela_preco);
					self::$db->insert("produto_tabela", [
						'id_tabela' => $id_tabela,
						'id_produto' => $id_produto,
						'percentual' => $percentual * 100,
						'valor_venda' => round($valor_venda, 2),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					]);
				}
				$contador++;
			}

			self::$db->query("COMMIT");
			Filter::msgOk("Sucesso! Total de $contador produtos adicionados.", $redirecionar);

		} catch (Exception $e) {
			if (!isset($_SESSION['erros_importacao'])) {
					$_SESSION['erros_importacao'] = explode("\n", $e->getMessage());
				}
			self::$db->query("ROLLBACK");
			Filter::msgError("ERRO: " . $e->getMessage(), $redirecionar);
		}
	}

	/**
	 * Produto::getBuscarVendaProdutoTroca()
	 *
	 * @return
	 */
	public function getBuscarVendaProdutoTroca()
	{
		$row = false;
		$id_venda = post('id_venda');
		$numero_nota = post('numero_nota');
		//$cpf_cliente = post('cpf_cliente');
		if ($id_venda) {
			$sql = "SELECT v.id as id_venda, c.nome as nome_cliente, cv.valor, cv.valor_desconto, cv.valor_total, cv.quantidade, cv.quantidade_trocada, p.nome as nome_produto, "
				. "\n p.codigo as codigo_produto, p.estoque, p.id as id_produto, cv.id as cadastro_venda, v.orcamento"
				. "\n FROM vendas as v "
				. "\n LEFT JOIN cadastro_vendas as cv ON cv.id_venda = v.id "
				. "\n LEFT JOIN produto as p ON p.id = cv.id_produto "
				. "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro "
				. "\n WHERE v.inativo = 0 AND cv.inativo = 0 AND v.id = $id_venda AND v.orcamento <> 1";
			$row = self::$db->fetch_all($sql);
		} elseif ($numero_nota) {
			$sql = "SELECT v.id as id_venda, c.nome as nome_cliente, cv.valor, cv.valor_desconto, cv.valor_total, cv.quantidade, p.nome as nome_produto, "
				. "\n p.codigo as codigo_produto, p.estoque, p.id as id_produto, cv.id as cadastro_venda, v.orcamento"
				. "\n FROM vendas as v "
				. "\n LEFT JOIN cadastro_vendas as cv ON cv.id_venda = v.id "
				. "\n LEFT JOIN produto as p ON p.id = cv.id_produto "
				. "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro "
				. "\n WHERE v.inativo = 0 AND cv.inativo = 0 AND v.numero_nota = '$numero_nota' AND v.orcamento <> 1";
			$row = self::$db->fetch_all($sql);
		}

		/*elseif($cpf_cliente) {
																	  $cpf_cnpj = limparCPF_CNPJ(post('cpf_cliente'));
																	  $sqlCliente = "SELECT id FROM cadastro WHERE cpf_cnpj=$cpf_cnpj";
																	  $row_cliente = self::$db->first($sqlCliente);
																	  if ($row_cliente) {
																		  $sql = "SELECT v.id as id_venda, c.nome as nome_cliente, cv.valor, cv.valor_desconto, cv.valor_total, p.nome as nome_produto, "
																	  . "\n p.codigo as codigo_produto, p.estoque, p.id as id_produto, cv.id as cadastro_venda "
																	  . "\n FROM vendas as v "
																	  . "\n LEFT JOIN cadastro_vendas as cv ON cv.id_venda = v.id "
																	  . "\n LEFT JOIN produto as p ON p.id = cv.id_produto "
																	  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro "
																	  . "\n where v.inativo = 0 AND cv.inativo = 0 AND v.id_cadastro = $row_cliente->id";
																	  $row = self::$db->fetch_all($sql);
																	  }
																  }*/

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarTaxa()
	 *
	 * @return
	 */
	public function processarTaxa()
	{
		if (empty($_POST['id_bairro']))
			Filter::$msgs['id_bairro'] = lang('MSG_ERRO_BAIRRO');

		if (empty(Filter::$msgs)) {

			$data = array(
				'id_bairro' => sanitize(post('id_bairro')),
				'valor_taxa' => converteMoeda(post('valor_taxa')),
				'tempo_aproximado' => sanitize(post('tempo_aproximado')),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			(Filter::$id) ? self::$db->update("taxas", $data, "id=" . Filter::$id) : self::$db->insert("taxas", $data);
			$message = (Filter::$id) ? lang('TAXAS_AlTERADO_OK') : lang('TAXAS_ADICIONADO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=taxas&acao=adicionar");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getTaxas()
	 *
	 * @return
	 */
	public function getTaxas()
	{
		$sql = " SELECT t.id, b.bairro, b.cidade, t.valor_taxa, t.tempo_aproximado
			FROM taxas as t
			LEFT JOIN bairros as b on b.id = t.id_bairro
			WHERE t.inativo = 0
			ORDER BY b.bairro ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::processarBairro()
	 *
	 * @return
	 */
	public function processarBairro()
	{
		if (empty($_POST['bairro']))
			Filter::$msgs['bairro'] = lang('MSG_ERRO_BAIRRO');

		if (empty($_POST['cidade']))
			Filter::$msgs['cidade'] = lang('MSG_ERRO_CIDADE');

		if (empty(Filter::$msgs)) {

			$data = array(
				'bairro' => sanitize(post('bairro')),
				'cidade' => sanitize(post('cidade')),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			(Filter::$id) ? self::$db->update("bairros", $data, "id=" . Filter::$id) : self::$db->insert("bairros", $data);
			$message = (Filter::$id) ? lang('BAIRROS_AlTERADO_OK') : lang('BAIRROS_ADICIONADO_OK');

			if (self::$db->affected()) {
				Filter::msgOk($message, 'index.php?do=bairros&acao=adicionar');
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Produto::getBairros()
	 *
	 * @return
	 */
	public function getBairros()
	{
		$sql = " SELECT id, bairro, cidade
			FROM bairros
			WHERE inativo = 0
			ORDER BY bairro ";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutosAppEstoque()
	 *
	 * @return
	 */
	public function getProdutosAppEstoque()
	{
		$sql = "SELECT p.id, p.nome, p.codigo, p.codigobarras, p.ncm, p.cfop, p.icms_cst, p.estoque, p.cest, p.valor_custo,
			p.monofasico, p.unidade, p.unidade_tributavel, pt.valor_venda, pt.id_tabela
			FROM produto as p
			LEFT JOIN produto_tabela as pt ON pt.id_produto = p.id
			LEFT JOIN tabela_precos as tp ON tp.id = pt.id_tabela
			WHERE p.inativo = 0 AND tp.inativo = 0
			GROUP BY p.id";

		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::getProdutosAppEstoqueComparacao()
	 *
	 * @return
	 */
	public function getProdutosAppEstoqueComparacao()
	{
		$sql = "SELECT e.id, p.nome, e.id_produto, e.estoque_atual as estoque_atual_analise, p.estoque as estoque_atual, e.estoque_fisico, status, e.observacao, e.data, e.usuario, e.valor_custo
			FROM estoque_analise as e
			LEFT JOIN produto as p ON p.id = e.id_produto
			WHERE p.inativo = 0 AND e.status = 2 ";

		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Produto::acaoAnaliseEstoque()
	 * Ao selecionar o produto ele será alterado
	 * @return
	 */

	public function acaoAnaliseEstoque($id_produto, $estoque_atual, $estoque_fisico, $valor_custo, $somar)
	{
		// Motivo 	=> Compra: 1 / Transferencia: 2 / Venda: 3 / Consumo: 4 / Perda: 5 / Ajuste: 6 / Cancelamento/Devolução de venda: 7 / MARKETING/PROPAGANDA/DEMONSTRACAO: 8
		// Tipo 	=> Entrada: 1 (Compra) / Saida: 2 (Venda)
		// Somar 	=> 1 soma / 0 substiui / -1 nega atualização

		if ($id_produto > 0) {
			if ($somar == 0 || $somar == 1) {
				$totalestoque_antes = $this->getEstoqueTotal($id_produto);

				$estoque_atualizado = $estoque_fisico + $estoque_atual;

				$substituicao_estoque = ($totalestoque_antes - $estoque_fisico) * -1;

				$data_update = [
					'id_empresa' => session('idempresa'),
					'id_produto' => $id_produto,
					'valor_custo' => converteMoeda($valor_custo),
					'quantidade' => ($somar == 0) ? converteMoeda($substituicao_estoque) : converteMoeda($estoque_fisico),
					'quantidade_antiga' => $totalestoque_antes,
					'quantidade_atual' => ($somar == 0) ? $estoque_fisico : $estoque_atualizado,
					'tipo' => 1,
					'motivo' => 6,
					'observacao' => ($somar == 0) ? "SUBSTITUIÇÃO DE ESTOQUE ATUAL (" . converteMoeda($totalestoque_antes) . ") PELO FISICO (" . converteMoeda($estoque_fisico) . ") - ALTERADO POR: " . session('nomeusuario') : "AJUSTE DE ESTOQUE ADICIONADO (" . converteMoeda($estoque_fisico) . ") - ALTERADO POR: " . session('nomeusuario'),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				];
				self::$db->insert("produto_estoque", $data_update);

				$totalestoque = $this->getEstoqueTotal($id_produto);
				$data_update = array(
					'valor_custo' => converteMoeda($valor_custo),
					'estoque' => converteMoeda($totalestoque),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update("produto", $data_update, "id=" . $id_produto);

				self::$db->delete("estoque_analise", "id_produto=" . $id_produto);

				$msg = ($somar == 0) ? lang('MSG_OK_PRODUTO_SUBSTITUIDO') : lang('MSG_OK_PRODUTO_ATUALIZADO');
				if (self::$db->affected()) {
					Filter::msgOk($msg, "index.php?do=estoque&acao=comparacaoestoquefisico");
				} else
					Filter::msgAlert(lang('NAOPROCESSADO'));
			} else {
				self::$db->delete("estoque_analise", "id_produto=" . $id_produto);

				if (self::$db->affected())
					Filter::msgOk(lang('MSG_PRODUTO_NADA_ALTERADO'), "index.php?do=estoque&acao=comparacaoestoquefisico");
				else
					Filter::msgAlert(lang('NAOPROCESSADO'));
			}
		} else
			Filter::msgError("Este produto não existe.");
	}

	/**
	 * Produto::somarEstoqueTodosProdutos()
	 * @return
	 */

	public function somarEstoqueTodosProdutos()
	{
		if (empty($_POST['id_produto']))
			Filter::$msgs['id_produto'] = lang('MSG_ERRO_PRODUTO_SELECIONADO');
		else
			$id_produto = post('id_produto');

		if (!empty($id_produto)) {
			if (empty(Filter::$msgs)) {
				$quant = ($id_produto) ? count($id_produto) : 0;
				$progress = 0;

				for ($i = 0; $i < $quant; $i++) {
					$estoque_fisico = getValue("estoque_fisico", "estoque_analise", "id_produto=" . $id_produto[$i] . " AND status = 2");
					$estoque_atual = getValue("estoque", "produto", "id=" . $id_produto[$i]);
					$valor_custo = getValue("valor_custo", "estoque_analise", "id_produto=" . $id_produto[$i] . " AND status = 2");
					$estoque_atualizado = $estoque_fisico + $estoque_atual;

					$data_update = [
						'id_empresa' => session('idempresa'),
						'id_produto' => $id_produto[$i],
						'valor_custo' => converteMoeda($valor_custo),
						'quantidade' => converteMoeda($estoque_fisico),
						'quantidade_antiga' => $estoque_atual,
						'quantidade_atual' => $estoque_atualizado,
						'tipo' => 1,
						'motivo' => 6,
						'observacao' => "AJUSTE DE ESTOQUE ADICIONADO (" . converteMoeda($estoque_fisico) . ") - ALTERADO POR: " . session('nomeusuario'),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					];
					self::$db->insert("produto_estoque", $data_update);

					$data_update = array(
						'valor_custo' => converteMoeda($valor_custo),
						'estoque' => converteMoeda($estoque_atualizado),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("produto", $data_update, "id=" . $id_produto[$i]);

					self::$db->delete("estoque_analise", "id_produto=" . $id_produto[$i]);
				}

				if (self::$db->affected())
					Filter::msgOk(lang('MSG_OK_PRODUTO_ATUALIZADO'), "index.php?do=estoque&acao=comparacaoestoquefisico");
				else
					Filter::msgAlert(lang('NAOPROCESSADO'));
			} else {
				print Filter::msgStatus();
				return false;
			}
		} else {
			Filter::msgError("Nenhum produto foi selecionado.", "index.php?do=estoque&acao=comparacaoestoquefisico");
			return false;
		}
	}

	/**
	 * Produto::substituirEstoqueTodosProdutos()
	 * @return
	 */

	public function substituirEstoqueTodosProdutos()
	{
		if (empty($_POST['id_produto']))
			Filter::$msgs['id_produto'] = lang('MSG_ERRO_PRODUTO_SELECIONADO');
		else
			$id_produto = post('id_produto');

		if (!empty($id_produto)) {
			if (empty(Filter::$msgs)) {
				$quant = ($id_produto) ? count($id_produto) : 0;
				$progress = 0;

				for ($i = 0; $i < $quant; $i++) {
					$totalestoque_antigo = $this->getEstoqueTotal($id_produto[$i]);
					$estoque_fisico = getValue("estoque_fisico", "estoque_analise", "id_produto=" . $id_produto[$i] . " AND status = 2");
					$valor_custo = getValue("valor_custo", "estoque_analise", "id_produto=" . $id_produto[$i] . " AND status = 2");

					$estoque_atualizado = ($totalestoque_antigo - $estoque_fisico) * -1;

					$data_update = [
						'id_empresa' => session('idempresa'),
						'id_produto' => $id_produto[$i],
						'quantidade' => $estoque_atualizado,
						'quantidade_antiga' => $totalestoque_antigo,
						'quantidade_atual' => $estoque_fisico,
						'tipo' => 1,
						'motivo' => 6,
						'observacao' => "SUBSTITUIÇÃO DE ESTOQUE ATUAL (" . converteMoeda($totalestoque_antigo) . ") PELO FISICO (" . converteMoeda($estoque_fisico) . ") - ALTERADO POR: " . session('nomeusuario'),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					];
					self::$db->insert("produto_estoque", $data_update);

					$data_update = array(
						'valor_custo' => converteMoeda($valor_custo),
						'estoque' => converteMoeda($estoque_fisico),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("produto", $data_update, "id=" . $id_produto[$i]);

					self::$db->delete("estoque_analise", "id_produto=" . $id_produto[$i]);
				}

				if (self::$db->affected())
					Filter::msgOk(lang('PRODUTO_AlTERADO_OK'), "index.php?do=estoque&acao=comparacaoestoquefisico");
				else
					Filter::msgAlert(lang('NAOPROCESSADO'));
			} else {
				print Filter::msgStatus();
				return false;
			}
		} else {
			Filter::msgError("Nenhum produto foi selecionado.", "index.php?do=estoque&acao=comparacaoestoquefisico");
			return false;
		}
	}

	public function processarExportacaoProduto()
	{
		require_once "PHPExcel/Classes/PHPExcel.php";
		require_once 'PHPExcel/Classes/PHPExcel/Style/Border.php';
		require_once 'PHPExcel/Classes/PHPExcel/Style/Alignment.php';
		require_once 'PHPExcel/Classes/PHPExcel/Style/NumberFormat.php';

		try {
			$id_produto = (post('id_produto'));
			$quant = ($id_produto) ? count($id_produto) : 0;

			if ($quant >= 1) {

				$arquivo = "Exportacao_Produtos.xlsx";

				$objPHPExcel = new PHPExcel();
				$sheet = $objPHPExcel->getActiveSheet();

				$styles = [
					'font' => [
						'bold' => true,
					],
					'borders' => [
						'allBorders' => [
							'borderStyle' => PHPExcel_Style_Border::BORDER_THIN,
						],
					],
				];

				$columns = range('A', 'O'); // Array com as letras das colunas de A até Y
				foreach ($columns as $column) {
					$sheet->getColumnDimension($column)->setWidth(30); // Definindo largura das colunas de A até Z
					$sheet->getStyle($column . '1')->getAlignment()->setWrapText(true);
					$sheet->getStyle($column . '1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheet->getStyle($column . '1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				}
				$sheet->getStyle('A1:Z1')->applyFromArray($styles);

				$sheet->getStyle('B2:B' . $quant)->getAlignment()->setWrapText(true); //NOME
				$sheet->getStyle('E2:E' . $quant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER); //CODIGO DE BARRAS
				$sheet->getStyle('K2:K' . $quant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00); //VALOR CUSTO
				$sheet->getStyle('L2:L' . $quant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00); //VALOR VENDA
				$sheet->getStyle('M2:M' . $quant)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00); //ESTOQUE

				$cell = [
					[
						'ID_PRODUTO (Não preencher)',
						'NOME (Obrigatório) (Não pode conter caracter especial: !@"´`#$%¨&*()§ºª)',
						'ID_UNIVERSAL (Não preencher)',
						'CÓDIGO DO PRODUTO',
						'CÓDIGO DE BARRAS (Não pode ser repetido) (No máximo 13 dígitos) (Não pode iniciar com letra)',
						'NCM (Obrigatório) (Somente 8 digitos)',
						'CFOP SAIDA (Obrigatório) (Somente 4 digitos)',
						'CEST (Obrigatório em todos os produtos que incidem ou incidiram Substituição Tributária) (Somente 15 digitos)',
						'CSOSN/CST (Obrigatório) (Somente 4 digitos)',
						'UNIDADE (Obrigatório) (Somente 6 letras. Ex: UNID, KG, PCT)',
						'VALOR DE CUSTO (R$)',
						'VALOR DE VENDA (R$)',
						'ESTOQUE',
						'VALIDA ESTOQUE (1- SIM / 0- NÃO)',
						'MONOFÁSICO (1- SIM / 0- NÃO)'
					],
				];

				for ($i = 0; $i < $quant; $i++) {
					$row_produto = Core::getRowById("produto", $id_produto[$i]);
					$valor_venda = getValue("valor_venda", "produto_tabela", "id_produto=" . $id_produto[$i]);

					if ($row_produto) {
						$cell[] = [
							$row_produto->id,
							$row_produto->nome,
							$row_produto->id_universal,
							$row_produto->codigo,
							$row_produto->codigobarras,
							$row_produto->ncm,
							$row_produto->cfop,
							$row_produto->cest,
							$row_produto->icms_cst,
							$row_produto->unidade,
							$row_produto->valor_custo,
							$valor_venda,
							$row_produto->estoque,
							$row_produto->valida_estoque,
							$row_produto->monofasico
						];
					}

					$sheet->fromArray($cell, null, 'A1');
				}

				$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$writer->save('./excel/export/' . $arquivo);

				Filter::msgOk(lang('PRODUTO_EXPORTACAO_OK'), "index.php?do=produto&acao=listar");
			} else {
				Filter::msgError("Erro! Selecione pelo menos um produto para exportar.", 'index.php?do=produto&acao=listar');
			}
		} catch (PDOException $e) {
			echo "ERROR: " . $e->getMessage();
		}
	}

	public function getAtributosProduto($id_produto)
	{
		$sql = " SELECT a.atributo, pa.descricao, a.exibir_romaneio
			FROM produto_atributo as pa
			LEFT JOIN atributo as a on a.id = pa.id_atributo
			WHERE pa.id_produto = $id_produto
			AND pa.inativo = 0 ";
		$row = self::$db->fetch_all($sql);

		return $row ? $row : 0;
	}

	/**
	 * Produto::ObterListaProdutosTroca2()
	 *
	 * @return
	 */
	public function ObterListaProdutosTroca2($array_produtos)
	{
		$array_qtde = array();
		foreach ((array) $array_produtos as $id => $row_produto) {
			if ($row_produto) {
				array_push($array_qtde, $id);
			}
		}

		if ($array_qtde) {
			$str_produto = implode(',', $array_qtde);
			$sql = "SELECT * FROM cadastro_vendas WHERE id IN ($str_produto)";
			$row = self::$db->fetch_all($sql);
			return ($row) ? $row : 0;
		} else {
			return 0;
		}
	}

	/**
	 * Produto::salvarValorUtilizarCrediario()
	 *
	 * @return
	 */
	public function salvarValorUtilizarCrediario($id_caixa = 0)
	{

		$id_venda_troca = sanitize(post('id_venda'));

		if ($id_venda_troca)
			$row_venda_troca = Core::getRowById("vendas", $id_venda_troca);

		$valor_venda_troca = post('valor_venda_troca');

		$valor_venda_troca = round($valor_venda_troca, 2);
		$valor_produto_troca = post('valor_produto_troca');
		$valor_produto_troca = round($valor_produto_troca, 2);
		$valor_produto_troca = round($valor_produto_troca, 2);
		$id_venda = 0;

		$kit = 0;
		$valor = post('valor');
		$valor = round($valor, 2);

		$id_empresa = (empty($_POST['id_empresa'])) ? session('idempresa') : post('id_empresa');
		$desconto = converteMoeda(post('valor_desconto_troca'));

		if (!$desconto)
			$desconto = 0;
		if (!$valor)
			$valor = 0;

		$desconto += converteMoeda($valor_venda_troca);

		$valor_pagar = $valor - $desconto;
		$id_cadastro = sanitize(post('id_cadastro'));
		$id_tabela = sanitize(post('id_tabela'));
		$produtos = (!empty($_POST['id_produto'])) ? $_POST['id_produto'] : null;
		$valores = (!empty($_POST['valor_venda'])) ? $_POST['valor_venda'] : 0;
		$quantidades = (!empty($_POST['quantidade'])) ? $_POST['quantidade'] : 0;
		$pagamentos = (!empty($_POST['id_pagamento'])) ? $_POST['id_pagamento'] : null;
		$pagos = (!empty($_POST['valor_pago'])) ? $_POST['valor_pago'] : 0;
		$parcelas = (!empty($_POST['parcela'])) ? $_POST['parcela'] : 0;
		$contar_produtos = (is_array($produtos)) ? count($produtos) : 0;
		$contar_pagamentos = (is_array($pagamentos)) ? count($pagamentos) : 0;

		$voucher = converteMoeda(getValue('voucher_troca_produto', 'cadastro', 'id=' . $id_cadastro));
		$voucher_crediario = converteMoeda(post('voucher_crediario'));

		if ($contar_produtos == 0)
			Filter::$msgs['contar_produtos'] = lang('MSG_ERRO_PRODUTO_VENDA');

		if (($contar_pagamentos == 0) && ($valor_pagar > 0))
			Filter::$msgs['contar_pagamentos'] = lang('MSG_ERRO_PAGAMENTO_VENDA');

		$soma_dinheiro = 0;
		$pago = 0;
		$troco = 0;
		$percentual_dinheiro = 0;

		for ($i = 0; $i < $contar_pagamentos; $i++) {
			$total_parcelas = ($parcelas[$i] == '' or $parcelas[$i] == 0) ? 1 : $parcelas[$i];
			$id_pagamento = $pagamentos[$i];
			$row_pagamento = Core::getRowById("tipo_pagamento", $id_pagamento);
			if ($total_parcelas > $row_pagamento->parcelas)
				Filter::$msgs[$i . 'total_parcelas'] = lang('MSG_ERRO_PARCELAS') . ": " . $row_pagamento->tipo;

			$pago += $pagos[$i];
			if ($id_pagamento == '1')
				$soma_dinheiro += $pagos[$i];
		}

		if ($soma_dinheiro > 0) {
			$soma_restante = $pago - $soma_dinheiro;
			$total_pagar_dinheiro = $valor_pagar - $soma_restante;
			$troco = $soma_dinheiro - $total_pagar_dinheiro;
			$percentual_dinheiro = ($soma_dinheiro - $troco) / $soma_dinheiro;
			$pago = ($troco < 0) ? $pago : $pago - $troco;
		}

		if (empty($_POST['id_produto'])) {
			Filter::$msgs['contar_produtos'] = lang('MSG_ERRO_PRODUTO_VENDA');
		} elseif ($troco < $soma_dinheiro) {
			$qtdtotal = 0;
			for ($i = 0; $i < $contar_produtos; $i++) {

				$quantidade = ($quantidades[$i]) ? $quantidades[$i] : 1;
				$qtdtotal += $quantidade;
				$id_produto = $produtos[$i];
				$kit = getValue("kit", "produto", "id=" . $id_produto);
				$valida_estoque = getValue("valida_estoque", "produto", "id=" . $id_produto);

				if ($valida_estoque) {
					if ($kit) {
						$nomekit = getValue("nome", "produto", "id=" . $id_produto);
						$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque "
							. "\n FROM produto_kit as k"
							. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
							. "\n WHERE k.id_produto_kit = $id_produto "
							. "\n ORDER BY p.nome ";
						$retorno_row = self::$db->fetch_all($sql);
						if ($retorno_row) {
							foreach ($retorno_row as $exrow) {
								if ($quantidade > $exrow->estoque)
									Filter::$msgs[$i . 'estoque' . $exrow->id] = str_replace("[ESTOQUE]", $exrow->estoque, lang('MSG_ERRO_ESTOQUE') . " >> PRODUTO DO KIT = " . $exrow->nome);
							}
						}
					} else {
						$estoque = getValue("estoque", "produto", "id=" . $id_produto);
						if ($quantidade > $estoque)
							Filter::$msgs[$i . 'estoque'] = str_replace("[ESTOQUE]", $estoque, lang('MSG_ERRO_ESTOQUE'));
					}
				}
			}

			$sql = "SELECT (quantidade)"
				. "\n FROM tabela_precos"
				. "\n WHERE id = $id_tabela";
			$retorno = self::$db->first($sql);

			if ($qtdtotal < $retorno->quantidade) {
				Filter::$msgs['quantidade'] = str_replace("[QUANTIDADE]", $quantidade, lang('MSG_ERRO_QTDMINIMA'));
			}
		}

		$valor_pagar = round($valor_pagar, 2);
		$pago = round($pago, 2);

		$checkCrediarioCliente = (float) converteMoeda(getValue("crediario", "cadastro", "id=" . post('id_cadastro')));
		if ($checkCrediarioCliente <= 0) {
			Filter::$msgs['crediario'] = lang('MSG_INFO_SEM_CREDIARIO_CLIENTE');
		}

		if (empty(Filter::$msgs)) {
			$nome = cleanSanitize(post('cadastro'));

			if (empty($_POST['id_cadastro']) && !empty($_POST['cadastro'])) {
				$data_cadastro = array(
					'nome' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
					'celular' => sanitize(post('celular')),
					'cliente' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_cadastro = self::$db->insert("cadastro", $data_cadastro);
			} else if (!empty($_POST['id_cadastro'])) {
				$data_cadastro = array(
					'voucher_troca_produto' => $voucher_crediario + $voucher,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update("cadastro", $data_cadastro, "id=" . $id_cadastro);
			}

			$entrega = (empty($_POST['prazo_entrega'])) ? 0 : 1;
			$status_entrega = ($entrega == 1) ? 1 : 3;

			$data_venda = array(
				'id_empresa' => $id_empresa,
				'id_cadastro' => $id_cadastro,
				'id_caixa' => $id_caixa,
				'id_vendedor' => sanitize(post('id_vendedor')),
				'id_troca' => $id_venda_troca,
				'voucher_crediario' => $voucher_crediario,
				'valor_troca' => converteMoeda($valor_venda_troca),
				'valor_total' => $valor,
				'valor_desconto' => $desconto - ($voucher_crediario),
				'valor_pago' => $pago + $troco,
				'crediario' => 1,
				'entrega' => $entrega,
				'status_entrega' => $status_entrega,
				'troco' => $troco,
				'data_venda' => "NOW()",
				'prazo_entrega' => dataMySQL(post('prazo_entrega')),
				'observacao' => sanitize(post('observacao')),
				'usuario_venda' => session('nomeusuario'),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);

			if (empty($_POST['salvar'])) {
				$data_venda['pago'] = 1;
				$data_venda['usuario_pagamento'] = session('nomeusuario');
			} else {
				$data_venda['pago'] = 2;
			}

			$id_venda = self::$db->insert("vendas", $data_venda);

			$nomecliente = ($id_cadastro) ? getValue("nome", "cadastro", "id=" . $id_cadastro) : "";
			$porcentagem_desconto = ($desconto * 100) / $valor;

			for ($i = 0; $i < $contar_produtos; $i++) {
				$quantidade = ($quantidades[$i]) ? $quantidades[$i] : 1;
				$id_produto = $produtos[$i];
				// $valor_venda = $valores[$i];
				$valor_venda = converteMoeda($valores[$i]);

				$valor_total = $valor_venda * $quantidade;
				$quant_estoque = $quantidade * (-1);

				$valor_desconto = ($porcentagem_desconto * $valor_total) / 100;
				$valor_desconto = round($valor_desconto, 2);

				$desconto_novo = $valor_desconto - $voucher_crediario;

				$data_cadastro_venda = array(
					'id_empresa' => $id_empresa,
					'id_cadastro' => $id_cadastro,
					'id_caixa' => $id_caixa,
					'id_venda' => $id_venda,
					'id_produto' => $id_produto,
					'id_tabela' => $id_tabela,
					'valor' => $valor_venda,
					'quantidade' => $quantidade,
					'valor_desconto' => $desconto_novo,
					'valor_total' => $valor_total,
					'crediario' => 1,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);

				if (empty($_POST['salvar'])) {
					$data_cadastro_venda['pago'] = 1;
				} else {
					$data_cadastro_venda['pago'] = 2;
				}
				$id_cadastro_venda = self::$db->insert("cadastro_vendas", $data_cadastro_venda);

				$kit = getValue("kit", "produto", "id=" . $id_produto);
				if ($kit) {
					$nomekit = getValue("nome", "produto", "id=" . $id_produto);
					$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade  "
						. "\n FROM produto_kit as k"
						. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
						. "\n WHERE k.id_produto_kit = $id_produto "
						. "\n ORDER BY p.nome ";
					$retorno_row = self::$db->fetch_all($sql);
					if ($retorno_row) {
						foreach ($retorno_row as $exrow) {
							$observacao = "VENDA DE KIT [$nomekit] PARA CLIENTE: " . $nomecliente;
							$quant_estoque_kit = $quantidade * $exrow->quantidade * (-1);
							$quant_produto_kit = $quantidade * (-1);

							$data_estoque = array(
								'id_empresa' => $id_empresa,
								'id_produto' => $exrow->id_produto,
								'quantidade' => $quant_estoque_kit,
								'tipo' => 2,
								'motivo' => 3,
								'observacao' => $observacao,
								'id_ref' => $id_cadastro_venda,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->insert("produto_estoque", $data_estoque);
							$totalestoque = $this->getEstoqueTotal($exrow->id_produto);
							$data_update = array(
								'estoque' => $totalestoque,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->update("produto", $data_update, "id=" . $exrow->id_produto);
						}

						$observacao = "VENDA DE PRODUTO PARA CLIENTE MEDIANTE TROCA: " . $nomecliente;

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

						$totalestoque = $this->getEstoqueTotal($id_produto);
						$data_update = array(
							'estoque' => $totalestoque,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("produto", $data_update, "id=" . $id_produto);
					}
				} else {
					$observacao = str_replace("[ID_VENDA]", $id_venda, lang('VENDA_PRODUTO_CLIENTE_TROCA'));
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
					$totalestoque = $this->getEstoqueTotal($id_produto);
					$data_update = array(
						'estoque' => $totalestoque,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("produto", $data_update, "id=" . $id_produto);
				}
			}

			///////////////////////////////////
			//Este trecho de código serve para ajustar o valor do desconto quando o mesmo for quebrado e gerar diferença no total.
			$novo_valor_desconto = $this->obterDescontosVenda($id_venda, $id_caixa, $id_empresa, $id_cadastro);
			if (round($novo_valor_desconto->vlr_desc, 2) != round($valor_desconto, 2)) {
				$novo_desconto = ($desconto - round($novo_valor_desconto->vlr_desc, 2)) + $novo_valor_desconto->valor_desconto;
				$data_desconto = array('valor_desconto' => $novo_desconto - $voucher_crediario);
				self::$db->update("cadastro_vendas", $data_desconto, "id=" . $novo_valor_desconto->id);
			}
			///////////////////////////////////

			$data_vencimento = "NOW()";
			$valor_total_venda = $valor - $desconto;

			///////////////////////////////////////////////////////////////////////////////////////////
			///DEVOLVE O PRODUTO TROCADO PARA O ESTOQUE////////////////////////////////////////////////

			$qtde_produto = post('qtde_produto');

			if ($id_venda_troca) {
				$observacao = str_replace("[ID_VENDA_TROCA]", $id_venda_troca, lang('PRODUTO_TROCA_OBSERVACAO'));
				$observacao = str_replace("[ID_VENDA]", $id_venda, $observacao);
			} else
				$observacao = str_replace("[ID_VENDA]", $id_venda, lang('PRODUTO_TROCA_OBSERVACAO_AVULSO'));

			///////////////////////////////////////////////////////////////////////////////////////////
			///LANÇA A NOTA FISCAL DE DEVOLUÇÃO CASO A NOTA DE TROCA TAMBÉM SEJA FISCAL////////////////
			if ($id_venda_troca) {
				if ($row_venda_troca->fiscal) {
					$data_nota = array(
						'id_empresa' => $id_empresa,
						'id_venda_devolucao' => $id_venda_troca,
						'modelo' => '2', //PRODUTO
						'operacao' => 1, //ENTRADA
						'id_cadastro' => $id_cadastro,
						'cpf_cnpj' => ($id_cadastro) ? getValue("cpf_cnpj", "cadastro", "id=" . $id_cadastro) : "",
						'numero_nota' => "NULL",
						'nfe_referenciada' => $row_venda_troca->chaveacesso,
						'data_emissao' => "NOW()",
						'valor_produto' => $valor_produto_troca,
						'valor_nota' => $valor_produto_troca,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$id_nota = self::$db->insert("nota_fiscal", $data_nota);
				}
			}
			///////////////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////
			if ($id_venda_troca) {
				foreach ($qtde_produto as $qtde) {
					if ($qtde) {

						$array_qtde = explode(",", $qtde);
						//$array_produtos[$array_qtde[0]] = $array_qtde[1];
						$row_cadastro_venda = Core::getRowById("cadastro_vendas", $array_qtde[0]);
						$data_estoque = array(
							'id_empresa' => $id_empresa,
							'id_produto' => $row_cadastro_venda->id_produto,
							'quantidade' => converteMoeda($array_qtde[1]),
							'tipo' => 2,
							'motivo' => 3,
							'observacao' => $observacao,
							'id_ref' => $array_qtde[0],
							'id_venda_troca' => $id_venda,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->insert("produto_estoque", $data_estoque);

						$data_qtde_trocado = array(
							'quantidade_trocada' => $row_cadastro_venda->quantidade_trocada + converteMoeda($array_qtde[1]),
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("cadastro_vendas", $data_qtde_trocado, "id=" . $array_qtde[0]);

						$totalestoque = $this->getEstoqueTotal($row_cadastro_venda->id_produto);
						$data_update = array(
							'estoque' => $totalestoque,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("produto", $data_update, "id=" . $row_cadastro_venda->id_produto);

						if ($id_venda_troca) {
							if ($row_venda_troca->fiscal) {
								$id_produto_fornecedor = getValue('id', 'produto_fornecedor', 'id_produto=' . $row_cadastro_venda->id_produto . ' AND id_cadastro=' . $id_cadastro);
								$data_nota = array(
									'id_empresa' => $id_empresa,
									'id_nota' => $id_nota,
									'id_cadastro' => $id_cadastro,
									'id_produto' => $row_cadastro_venda->id_produto,
									'id_produto_fornecedor' => $id_produto_fornecedor,
									'quantidade' => converteMoeda($array_qtde[1]),
									'valor_unitario' => $row_cadastro_venda->valor,
									'valor_desconto' => ($row_cadastro_venda->valor_desconto / $row_cadastro_venda->quantidade) * converteMoeda($array_qtde[1]),
									'valor_total' => (($row_cadastro_venda->valor_total - $row_cadastro_venda->valor_desconto) / $row_cadastro_venda->quantidade) * converteMoeda($array_qtde[1]),
									'icms_cst' => getValue("icms_cst", "produto", "id=" . $row_cadastro_venda->id_produto),
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->insert("nota_fiscal_itens", $data_nota);
							}
						}
					}
				}
			} else {
				foreach ($qtde_produto as $vlr_produto_troca) {
					if ($vlr_produto_troca) {
						$array_prod = explode(",", $vlr_produto_troca);
						$data_estoque = array(
							'id_empresa' => $id_empresa,
							'id_produto' => $array_prod[0],
							'quantidade' => converteMoeda($array_prod[1]),
							'tipo' => 2,
							'motivo' => 3,
							'observacao' => $observacao,
							'id_ref' => 0,
							'id_venda_troca' => $id_venda,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->insert("produto_estoque", $data_estoque);

						$totalestoque = $this->getEstoqueTotal($array_prod[0]);
						$data_update = array(
							'estoque' => $totalestoque,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("produto", $data_update, "id=" . $array_prod[0]);
					}
				}
			}

			///////////////////////////////////////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////

			if (self::$db->affected()) {
				$message = lang('CADASTRO_VENDA_FINALIZADA');
				$redirecionar = "index.php?do=vendas_do_dia";
				Filter::msgOkRecibo($message, $redirecionar, $id_venda, '1');
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
	}

	/**
	 * Retorna se esse produto é kit de outro.
	 * Produto::verificaSeProdutoVirouKit()
	 *
	 * @return
	 */
	public function verificaSeProdutoVirouKit($id_produto)
	{
		$sql = "SELECT k.id, k.id_produto_kit, k.id_produto, p.valor_custo
			FROM produto_kit AS k
			LEFT JOIN produto as p on p.id = k.id_produto
			WHERE id_produto = $id_produto";
		$row = self::$db->fetch_all($sql);

		return ($row) ? $row : 0;
	}

	public function getProdutoBalanca()
	{
		$sql = "SELECT p.id, p.nome, p.codigo, p.codigobarras, p.produto_balanca
			FROM produto as p
			WHERE p.inativo = 0 AND p.produto_balanca = 1";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	public function getPrecoProduto($id_produto)
	{
		$sql = "SELECT valor_venda FROM produto_tabela WHERE id_tabela = 1 AND id_produto = $id_produto ";
		$row = self::$db->first($sql);
		return ($row) ? $row->valor_venda : 0;
	}

	/**
	 * processarExportacaoBalanca()
	 *
	 * @return
	 */
	public function processarExportacaoBalanca($tipo)
	{

		/**
		 * Senha padrão para acessar o software MGV5: adming/adming
		 * Link para suporte MGV5 (descontinuado): https://infokaw.com.br/jkawflex/SNAPSHOT/lib/jkaw-4.00-SNAPSHOT/balancas/layoutToledo/Arquivo%20de%20cadastro%20Toledo%20MGV5%20-%20Padr%C3%A3o%20de%20cadastro.pdf
		 * Link para suporte MGV6 (descontinuado): https://help.toledobrasil.com/mgv6/v1_6_/Html_Pages/arquivos_de_cadastro.html
		 * Link para suporte MGV7: https://help.toledobrasil.com/mgv7/v7_0_/HTML_PAGES/arquivos_de_cadastro.html
		 *
		 **/

		try {

			$arquivo = "./balanca_toledo/ITENSMGV.txt";

			$open = fopen($arquivo, "w");

			$dd_codigo_departamento = "01"; 			//2 bytes - Código do departamento
			$t_tipo_produto = "0"; 						//1 bytes - Tipo de produto
			$cccccc_codigo_item = ""; 					//6 bytes - Código do Item
			$pppppp_preco_kg_unid = "";					//6 bytes - Preço/kg ou Preço/Unid. do item
			$vvv_dias_validade = "000";					//3 bytes - Dias de validade do produto
			$d1_descritivo_item_1linha = "";			//25 bytes - Descritivo do Item – Primeira Linha
			$d2_descritivo_item_2linha = "";			//25 bytes - Descritivo do Item – Segunda Linha
			$rrrrrr_cod_info_extra = "000000";			//6 bytes - Código da Informação Extra do item
			$ffff_cod_imagem_item = "0000";				//4 bytes - Código da Imagem do Item
			$iiiiii_cod_info_nutri = "000000";			//6 bytes - Código da Informação Nutricional
			$dv_impressao_dt_validade = "1";			//1 bytes - Impressão da Data de Validade
			$de_impressao_dt_embalagem = "1";			//1 bytes - Impressão da Data de Embalagem
			$cf_codigo_fornecedor = "0000";				//4 bytes - Código do Fornecedor
			$l_lote = "000000000000";					//12 bytes - Lote
			$g_codigo_ean13_especial = "00000000000"; 	//11 bytes - Código EAN-13 Especial
			$z_versao_preco = "0";						//1 bytes - Versão do preço
			$cs_codigo_som = "0000";					//4 bytes - Código do Som
			$ct_cod_tara_predeterminada = "0000"; 		//4 bytes - Código de Tara Pré-determinada
			$fr_cod_fracionador = "0000";				//4 bytes - Código do Fracionador
			$ce1_cod_campo_extra1 = "0000";				//4 bytes - Código do Campo Extra 1
			$ce2_cod_campo_extra2 = "0000";				//4 bytes - Código do Campo Extra 2
			$con_cod_conservacao = "0000";				//4 bytes - Código da Conservação
			$ean_ean13_fornecedor = "000000000000";		//12 bytes - EAN-13 de Fornecedor
			$gl_percentual_glaciamento = "000000";		//6 bytes - Percentual de Glaciamento
			$da_sequencia_dep_associados = "01";		//2 bytes - Sequencia de departamentos associados - Ex: Para associar departamentos 2 e 5: |0205|
			$d3_descritivo_item_3linha = "";			//35 bytes - Descritivo do Item – Terceira Linha
			$d4_descritivo_item_4linha = "";			//35 bytes - Descritivo do Item – Quarta Linha
			$ce3_cod_campo_extra3 = "000000";			//6 bytes - Código do Campo Extra 3
			$ce4_cod_campo_extra4 = "000000";			//6 bytes - Código do Campo Extra 4
			$midia_cod = "000000";						//6 bytes - Código da mídia (Prix 6 Touch)
			$pppppp_preco_promocional = "000000"; 		//6 bytes - Preço Promocional - Preço/kg ou Preço/Unid. do item
			$sf_solicita_fornecedor = "0";				//1 bytes - [0] = Utiliza o fornecedor associado || [1] = Balança solicita fornecedor após chamada do PLU
			$ffff_cod_fornec_associado = "0001"; 		//4 bytes - Código de Fornecedor Associado, de no máximo 4 bytes, utilizado no cadastro de fornecedores do MGV
			$st_solicita_tara_balanca = "0";			//1 bytes - [0] = Não solicita tara na balança || [1] = Solicita Tara na Balança
			$bna_seq_balanca_item_inativo = "01"; 		//2 bytes - Sequência de balanças onde o item não estará ativo. Ex: Para associar balanças 2 e 5 com itens inativos: |0205|
			$g1_codigo_ean13_especial = "000000000000"; //12 bytes - Código EAN-13 Especial
			$pg_percentual_glaciamento = "0000"; 		//4 bytes - Percentual de Glaciamento
			$pppppp_preco3 = "000000"; 					// 6 bytes - Preço 3

			$g_reservado = "00000000000"; 				// 11 bytes
			$r_bytes_reservados = "0"; 					// 2 bytes

			$res = $this->getProdutoBalanca();
			if ($res) {
				$preco = ""; //exemplo a ser seguido para o arquivo ::: 24,24 => 002424
				$count = 0;
				foreach ($res as $key) {
					$count++;

					$valor_venda = floatval(round($this->getPrecoProduto($key->id), 2));
					$valor_venda = number_format($valor_venda, 2, '.', '');

					if (strpos($valor_venda, '.') !== false) {
						$valor_venda = str_replace('.', '', $valor_venda);
					}

					if (strlen($valor_venda) < 6) {
						$preco = str_pad($valor_venda, 6, '0', STR_PAD_LEFT);
					} else if (strlen($valor_venda) > 6) {
						$preco = substr($valor_venda, -6);
					}

					$nome = $key->nome;
					$d1_descritivo_item_1linha = substr($nome, 0, 25);
					$cccccc_codigo_item = !empty($key->codigo) ? $key->codigo : "000000";

					$pppppp_preco_kg_unid = $preco;
					$d2_descritivo_item_2linha = "";
					$descritivo_1_2 = $d1_descritivo_item_1linha . " " . $d2_descritivo_item_2linha;
					$descritivo_1_2 = str_pad($descritivo_1_2, 50, ' ', STR_PAD_RIGHT);

					if (strlen($cccccc_codigo_item) < 6) {
						$cccccc_codigo_item = str_pad($cccccc_codigo_item, 6, '0', STR_PAD_LEFT);
					} else if (strlen($cccccc_codigo_item) > 6) {
						$cccccc_codigo_item = substr($cccccc_codigo_item, -6);
					}

					if ($tipo === "mgv5v1") {
						$linha = $dd_codigo_departamento
							. $t_tipo_produto
							. $cccccc_codigo_item
							. $pppppp_preco_kg_unid
							. $vvv_dias_validade
							. $descritivo_1_2
							. $rrrrrr_cod_info_extra
							. $ffff_cod_imagem_item
							. $iiiiii_cod_info_nutri
							. $dv_impressao_dt_validade
							. $de_impressao_dt_embalagem
							. $cf_codigo_fornecedor
							. $l_lote
							. $g_reservado
							. $z_versao_preco
							. $r_bytes_reservados;
					} elseif ($tipo === "mgv5v2") {
						$linha = $dd_codigo_departamento
							. $t_tipo_produto
							. $cccccc_codigo_item
							. $pppppp_preco_kg_unid
							. $vvv_dias_validade
							. $descritivo_1_2
							. $rrrrrr_cod_info_extra
							. $ffff_cod_imagem_item
							. $iiiiii_cod_info_nutri
							. $dv_impressao_dt_validade
							. $de_impressao_dt_embalagem
							. $cf_codigo_fornecedor
							. $l_lote
							. $g_reservado
							. $z_versao_preco
							. $cs_codigo_som
							. $ct_cod_tara_predeterminada
							. $fr_cod_fracionador
							. $ce1_cod_campo_extra1
							. $ce2_cod_campo_extra2
							. $con_cod_conservacao
							. "000000000000";
					} elseif ($tipo === "mgv5v3") {
						$linha = $dd_codigo_departamento
							. $t_tipo_produto
							. $cccccc_codigo_item
							. $pppppp_preco_kg_unid
							. $vvv_dias_validade
							. $descritivo_1_2
							. $rrrrrr_cod_info_extra
							. $ffff_cod_imagem_item
							. $iiiiii_cod_info_nutri
							. $dv_impressao_dt_validade
							. $de_impressao_dt_embalagem
							. $cf_codigo_fornecedor
							. $l_lote
							. $g_reservado
							. $z_versao_preco
							. $cs_codigo_som
							. $ct_cod_tara_predeterminada
							. $fr_cod_fracionador
							. $ce1_cod_campo_extra1
							. $ce2_cod_campo_extra2
							. $con_cod_conservacao
							. "000000000000"
							. $gl_percentual_glaciamento;
					} elseif ($tipo === "mgv6") {
						$linha = $dd_codigo_departamento
							. $t_tipo_produto
							. $cccccc_codigo_item
							. $pppppp_preco_kg_unid
							. $vvv_dias_validade
							. $descritivo_1_2
							. $rrrrrr_cod_info_extra
							. $ffff_cod_imagem_item
							. $iiiiii_cod_info_nutri
							. $dv_impressao_dt_validade
							. $de_impressao_dt_embalagem
							. $cf_codigo_fornecedor
							. $l_lote
							. $g_codigo_ean13_especial
							. $z_versao_preco
							. $cs_codigo_som
							. $ct_cod_tara_predeterminada
							. $fr_cod_fracionador
							. $ce1_cod_campo_extra1
							. $ce2_cod_campo_extra2
							. $con_cod_conservacao
							. $ean_ean13_fornecedor
							. $gl_percentual_glaciamento
							. $da_sequencia_dep_associados
							. $d3_descritivo_item_3linha
							. $d4_descritivo_item_4linha
							. $ce3_cod_campo_extra3
							. $ce4_cod_campo_extra4
							. $midia_cod
							. $pppppp_preco_promocional
							. $sf_solicita_fornecedor
							. $ffff_cod_fornec_associado
							. $st_solicita_tara_balanca
							. $bna_seq_balanca_item_inativo
							. $g1_codigo_ean13_especial
							. $pg_percentual_glaciamento;
					} elseif ($tipo === "mgv7") {
						$linha = $dd_codigo_departamento
							. $t_tipo_produto
							. $cccccc_codigo_item
							. $pppppp_preco_kg_unid
							. $vvv_dias_validade
							. $descritivo_1_2
							. $rrrrrr_cod_info_extra
							. $ffff_cod_imagem_item
							. $iiiiii_cod_info_nutri
							. $dv_impressao_dt_validade
							. $de_impressao_dt_embalagem
							. $cf_codigo_fornecedor
							. $l_lote
							. $g_codigo_ean13_especial
							. $z_versao_preco
							. $cs_codigo_som
							. $ct_cod_tara_predeterminada
							. $fr_cod_fracionador
							. $ce1_cod_campo_extra1
							. $ce2_cod_campo_extra2
							. $con_cod_conservacao
							. $ean_ean13_fornecedor
							. $gl_percentual_glaciamento
							. $da_sequencia_dep_associados
							. $d3_descritivo_item_3linha
							. $d4_descritivo_item_4linha
							. $ce3_cod_campo_extra3
							. $ce4_cod_campo_extra4
							. $midia_cod
							. $pppppp_preco_promocional
							. $sf_solicita_fornecedor
							. $ffff_cod_fornec_associado
							. $st_solicita_tara_balanca
							. $bna_seq_balanca_item_inativo
							. $g1_codigo_ean13_especial
							. $pg_percentual_glaciamento
							. $pppppp_preco3;
					}

					fwrite($open, "$linha \n");
				}

				$close = fclose($open);

				if ($close)
					Filter::msgOk("Sucesso! Os dados foram gravados no arquivo.");
				else
					Filter::msgError("Ocorreu um erro!");
			} else {
				Filter::msgError("Não há produtos para a remessa da balança.", "index.php?do=produto&acao=listar");
			}
		} catch (PDOException $e) {
			echo "ERROR: " . $e->getMessage();
		}
	}
}
