<?php ini_set('display_errors', 1);

include_once("../../../init.php");

function chavesEcommerce()
{
    global $db;
    $qry = "SELECT e.ecomd_url, e.ecomd_api_key, e.ecomd_api_secret FROM empresa e WHERE e.inativo = 0 ";
    $q = $db->fetch_all($qry);

    $dados = ['ecomd_url' => $q[0]->ecomd_url, 'ecomd_api_key' => $q[0]->ecomd_api_key, 'ecomd_api_secret' => $q[0]->ecomd_api_secret];
    return $dados;
}

function verificarChaveApi($headers)
{
    global $db;

    $headers = getallheaders();

    if (isset($headers['Authorization'])) {

        $apiKey = $headers['Authorization'];

        $qry = "SELECT e.ecomd_api_recebe FROM empresa e WHERE e.inativo = 0 ";

        $api = $db->fetch_all($qry);

        $validApiKey = $api[0]->ecomd_api_recebe;

        if ((string)$apiKey === 'Basic ' . (string)$validApiKey) {
            return true;
        } else {
            http_response_code(401);
            return false;
        }
    } else {
        http_response_code(400);
        return false;
    }
}

function obterDadosProduto($id)
{
    global $db;

    $qry = "SELECT DISTINCT p.id, p.nome, p.descricao_unidade, p.valida_estoque, p.estoque, p.valor_custo, p.valor_avista, p.id_categoria, p.`data`, p.ecommerce_id, c.categoria
    FROM produto p
    LEFT JOIN categoria c ON c.id = p.id_categoria
    WHERE p.id = '{$id}' ";

    $produto = $db->fetch_all($qry);

    $retorno = ['ecommerce_id' => (int)$produto[0]->ecommerce_id, 'custo' => $produto[0]->valor_custo, 'valor' => $produto[0]->valor_avista, 'dados' => ['id' => (int)$produto[0]->id, 'name' => $produto[0]->nome, 'slug' => '', 'created_at' => $produto[0]->data, 'type' => '', 'status' => 'published', 'featured' => '', 'description' => '', 'short_description' => '', 'sku' => $produto[0]->id, 'regular_price' => $produto[0]->valor_custo, 'sale_price' => '', 'sale_type' => '', 'date_sale_start' => '', 'date_sale_end' => '', 'manage_stock' => ($produto[0]->valida_estoque == 1 ? "true" : "false"), 'stock_quantity' => $produto[0]->estoque, 'stock_status' => '', 'weight' => '', 'categories' => [['name' => $produto[0]->categoria ?? 'Indefinida', 'slug' => '']], 'title_meta' => '', 'desc_meta' => '']];

    return $retorno;
}

function obterDadosProdutos()
{
    global $db;

    $qry = "SELECT DISTINCT p.id, p.nome, p.descricao_unidade, p.valida_estoque, p.estoque, p.valor_custo, p.valor_avista, p.id_categoria, p.`data`, p.ecommerce_id, c.categoria
    FROM produto p
    LEFT JOIN categoria c ON c.id = p.id_categoria
    WHERE p.inativo = 0 AND p.ecommerce = 1 AND (p.ecommerce_id = 0 OR p.ecommerce_id IS NULL) ";

    $produto = $db->fetch_all($qry);

    if (count($produto) == 0) {
        return 0;
    } else if (count($produto) > 0) {
        foreach ($produto as $p) {
            $retorno[] = ['ecommerce_id' => (int)$p->ecommerce_id, 'custo' => $produto[0]->valor_custo, 'valor' => $produto[0]->valor_avista, 'dados' => ['id' => (int)$p->id, 'name' => $p->nome, 'slug' => '', 'created_at' => $p->data, 'type' => '', 'status' => 'published', 'featured' => '', 'description' => '', 'short_description' => '', 'sku' => $p->id, 'regular_price' => $p->valor_custo, 'sale_price' => '', 'sale_type' => '', 'date_sale_start' => '', 'date_sale_end' => '', 'manage_stock' => ($p->valida_estoque == 1 ? "true" : "false"), 'stock_quantity' => $p->estoque, 'stock_status' => '', 'weight' => '', 'categories' => [['name' => "{$p->categoria}", 'slug' => '']], 'title_meta' => '', 'desc_meta' => '']];
        }

        return $retorno;
    } else {
        return null;
    }
}

function criarProduto($dados, $multiplos = false)
{
    $content = $dados['dados'];

    if ($dados['ecommerce_id'] > 0) {
        http_response_code(400);
        if ($multiplos == false) {
            echo json_encode(["status" => "error", "msg" => "O item já se encontra cadastrado no sistema."]);
        } else {
            return json_encode(["status" => "error", "msg" => "O item já se encontra cadastrado no sistema."]);
        }
        exit();
    }

    if (empty($content['name']) || empty($content['sku'])) {
        http_response_code(400);
        if ($multiplos == false) {
            echo json_encode(["status" => "error", "msg" => "Campos obrigatórios não foram fornecidos: 'name' e 'sku'."]);
        } else {
            return json_encode(["status" => "error", "msg" => "Campos obrigatórios não foram fornecidos: 'name' e 'sku'."]);
        }
        exit();
    }

    $dadosCliente = chavesEcommerce();

    $ch = curl_init("https://{$dadosCliente['ecomd_url']}/api/product");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Content-Type: application/json",
        "Authorization: Basic " . base64_encode("{$dadosCliente['ecomd_api_key']}:{$dadosCliente['ecomd_api_secret']}")
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));

    $response = curl_exec($ch);

    if ($response === false) {
        echo json_encode(['status' => 'error', 'msg' => 'Erro cURL: ' . curl_error($ch)]);
    } else {
        global $db;

        $conteudo = json_decode($response, true);

        $data = array(
            'ecommerce' => 1,
            'ecommerce_id' => $conteudo['id']
        );

        $db->update("produto", $data, " id = " . $content['id']);

        $retorno = ['status' => 'success', 'msg' => "Inserido no e-commerce com sucesso.", 'data' => $response, 'conteudo' => $conteudo, 'content' => $content];

        if ($multiplos == false) {
            echo json_encode($retorno);
        } else {
            return $retorno;
        }
    }

    curl_close($ch);
}

function atualizarProduto($dados, $multiplos = false)
{
    $content = $dados['dados'];

    if (empty($content['name']) || empty($content['sku'])) {
        echo json_encode(["status" => "error", "msg" => "Campos obrigatórios não foram fornecidos: 'name' e 'sku'."]);
        http_response_code(400);
        exit();
    }

    $dadosCliente = chavesEcommerce();

    $ch = curl_init("https://{$dadosCliente['ecomd_url']}/api/product/{$dados['ecommerce_id']}");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Content-Type: application/json",
        "Authorization: Basic " . base64_encode("{$dadosCliente['ecomd_api_key']}:{$dadosCliente['ecomd_api_secret']}")
    ]);

    unset($content['categories']);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));

    $response = curl_exec($ch);

    if ($response === false) {
        echo json_encode(['status' => 'error', 'msg' => 'Erro cURL: ' . curl_error($ch)]);
    } else {
        $retorno = ['status' => 'success', 'msg' => "Produto atualizado no e-commerce com sucesso.", 'data' => json_decode($response, true)];
        if ($multiplos == false) {
            echo json_encode($retorno);
        } else {
            return $retorno;
        }
    }

    curl_close($ch);
}

function deletarProduto($dados)
{
    $content = $dados['dados'];

    if (empty($dados['ecommerce_id'])) {
        echo json_encode(["status" => "error", "msg" => "O ID do produto na plataforma ecomd não foi fornecido."]);
        http_response_code(400);
        exit();
    }

    $dadosCliente = chavesEcommerce();

    $url = "https://{$dadosCliente['ecomd_url']}/api/product/{$dados['ecommerce_id']}";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Content-Type: application/json",
        "Authorization: Basic " . base64_encode("{$dadosCliente['ecomd_api_key']}:{$dadosCliente['ecomd_api_secret']}")
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        echo json_encode(['status' => 'error', 'msg' => 'Erro cURL: ' . curl_error($ch)]);
    } else {
        $decodedResponse = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {

            global $db;

            $data = array(
                'ecommerce_id' => null
            );

            $db->update("produto", $data, " id = " . $content['id']);

            echo json_encode(['status' => 'success', 'msg' => "Produto deletado com sucesso.", 'data' => $decodedResponse]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => "A resposta não é um JSON válido.", 'data' => $response]);
        }
    }

    curl_close($ch);
}

function verificarEstoque($id)
{
    global $db;

    $qry = "SELECT 
            SUM(e.quantidade) AS total, p.valida_estoque as valida
            FROM produto_estoque as e
	        LEFT JOIN produto p ON e.id_produto = p.id
            WHERE e.inativo = 0 AND e.id_produto = {$id}";

    $produto = $db->fetch_all($qry);

    if (is_array($produto) && count($produto) > 0) {
        return ['total' => $produto[0]->total, 'valida' => $produto[0]->valida];
    } else {
        return null;
    }
}

/**
 * Atualiza o estoque de um produto baseado no tipo de movimentação
 * 
 * @param $dados se refere ao array com os dados necessários para a atualização do estoque
 * @param $movimentacao se refere ao campo tipo na tabela produto_estoque, onde 1 se trata da entrada de itens e 2 da retirada
 */
function atualizarEstoque($dados = [], int $movimentacao)
{
    global $db;
    //adicionar registro a tabela produto_estoque

    if (!in_array($movimentacao, [1, 2], true)) {
        throw new InvalidArgumentException('O parâmetro $movimentacao deve ser 1 ou 2.');
    }

    $data = array(
        'id_empresa' => 1,
        'id_produto' => $dados['id_produto'],
        'quantidade' => ($movimentacao == 2 ? ($dados['quantidade'] * -1) : $dados['quantidade']),
        'tipo' => $movimentacao,
        'motivo' => 9,
        'observacao' => ($movimentacao == 2 ? "COMPRA VIA ECOMMERCE (ID ECOMD: {$dados['id_ecomd']}, ID SIGE: {$dados['id_venda']})" : "RETORNO DE ITEM ECOMMERCE"),
        'usuario' => 'ecomd',
        'data' => "NOW()",
        'id_ecomd' => $dados['id_ecomd'] ?? 0
    );
    $insert_id = $db->insert("produto_estoque", $data);

    $estoque = verificarEstoque($dados['id_produto']);

    $data = array(
        'estoque' => $estoque['total']

    );
    $estoque = $db->update("produto", $data, "id=" . $dados['id_produto']);

    if ($db->affected() > 0) {
        return ['status' => 'success', 'msg' => 'O estoque foi alterado com sucesso'];
    } else {
        return ['status' => 'error', 'msg' => 'Erro ao atualizar o estoque.'];
    }
}
