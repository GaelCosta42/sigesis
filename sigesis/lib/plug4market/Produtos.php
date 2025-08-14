
<?php

/**
 * Classe Produtos
 *
 * @package Sigesis N1
 * @author Vale Telecom
 * @version 1
 */

if (!defined("_VALID_PHP"))
    die('Acesso direto a esta classe não é permitido.');

use Plug4Market\PostProdutoDTO;

class Produtos
{
    const uTable = "produto";
    private static $db;
    private $apiToken;

    function __construct(string $apiToken = '')
    {
        self::$db = Registry::get("Database");
        $this->apiToken = $apiToken; // Token da API Plug4Market
    }

    /**
     * Produtos::getProdutos()
     * Retorna todos os produtos ativos
     */
    public function getProdutos()
    {
        $sql = "SELECT * FROM " . self::uTable . " WHERE inativo = 0 ORDER BY nome";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : [];
    }

    /**
     * Produtos::postProdutoPlug4Market()
     * Envia um produto para a API Plug4Market
     */
    public function postProdutoPlug4Market(array $dados)
    {
        require_once BASEPATH . 'lib/service/ProdutosCanalVendaService.php';

        $produtoDTO = new PostProdutoDTO(
            $dados['id'] ?? null,
            $dados['nome'] ?? '',
            $dados['preco'] ?? 0,
            $dados['quantidade'] ?? 0
        );

        $p4m = new ProdutosCanalVendaService($this->apiToken);
        return $p4m->postProduto($produtoDTO);
    }

    /**
     * Produtos::processarProduto()
     * Processa criação/edição via POST do front
     */
    public function processarProduto()
    {
        if (empty($_POST['nome'])) {
            Filter::$msgs['nome'] = 'O nome do produto é obrigatório';
        }

        if (empty(Filter::$msgs)) {
            $data = [
                'nome' => sanitize($_POST['nome']),
                'preco' => floatval(str_replace(',', '.', $_POST['preco'])),
                'quantidade' => intval($_POST['quantidade'])
            ];

            (Filter::$id)
                ? self::$db->update(self::uTable, $data, "id=" . Filter::$id)
                : self::$db->insert(self::uTable, $data);

            if (self::$db->affected()) {
                Filter::msgOk('Produto salvo com sucesso!', "index.php?do=produto&acao=listar");
            } else {
                Filter::msgAlert('Nada foi processado.');
            }
        } else {
            print Filter::msgStatus();
        }
    }
}
?>
