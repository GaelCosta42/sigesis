<?php
ini_set('display_errors', 1);
define("_VALID_PHP", true);

include 'produtos.php';

$content = json_decode(file_get_contents("php://input"), true);

switch ($content['acao']) {
    case 'adicionar-produto':
        criarProduto(obterDadosProduto($content['id']));
        break;
    case 'atualizar-produto':
        atualizarProduto(obterDadosProduto($content['id']));
        break;
    case 'excluir-produto':
        deletarProduto(obterDadosProduto($content['id']));
        break;
    case 'adicionar-produtos':

        $produtos = obterDadosProdutos();
        $resultados = [];

        if (is_array($produtos) && count($produtos) > 0) {
            foreach ($produtos as $produto) {
                $resultado = criarProduto($produto, true);
                $resultados[] = $resultado;
                usleep(350000);
            }
        }

        echo json_encode(['status' => 'completed', 'results' => $resultados]);
        break;

    case 'atualizar-produtos':
        $produtos = obterDadosProdutos();
        $resultados = [];

        if ($produtos == 0) {
            echo json_encode(['status' => 'completed', 'results' => $resultados]);
        } else if (is_array($produtos)) {
            foreach ($produtos as $produto) {
                $resultado = atualizarProduto($produto, true);
                $resultados[] = $resultado;
                usleep(350000);
            }

            echo json_encode(['status' => 'completed', 'results' => $resultados]);
        } else {
            echo json_encode(['status' => 'error', 'results' => null]);
        }
        break;
    case 'compra-ecomd':
        if (verificarChaveApi(getallheaders())) {
            $valor_total = 0;
            foreach ($content['dados']['line_items'] as $item) {
                $produto = verificarEstoque($item['product_id']); //provavelmente esse id vai ser a primary key do ecommerce, devemos usar o sku que é nossa primary key
                if ($produto == null) {
                    http_response_code(404);
                    echo json_encode([
                        'status' => 'error',
                        'message' => "Produto n° {$item['product_id']} - {$item['name']} não encontrado."
                    ]);
                    exit();
                } elseif ($produto['valida'] == 1 && !($produto['total'] > 0)) {
                    http_response_code(409);
                    echo json_encode([
                        'status' => 'error',
                        'message' => "Pedido inválido, produto n° {$item['product_id']} - {$item['name']} está indisponível no estoque."
                    ]);
                    exit();
                }
                $valor_total += $item['subtotal'] * $item['quantity'];
            }

            $id_unico = uniqid() . '_' . 'ecomd' . '_' . date("YmdHis");

            $data = array(
                'id_unico' => $id_unico,
                'id_cadastro' => 0,
                'id_empresa' => 1,
                'id_caixa' => 0,
                'id_vendedor' => 0,
                'status_entrega' => 1,
                'valor_total' => $valor_total,
                'valor_pago' => ($content['dados']['payment_status'] == 'paid' ? $valor_total : 0),
                'pago' => ($content['dados']['payment_status'] == 'paid' ? 1 : 2),
                'data_venda' => "NOW()",
                'observacao' => $content['dados']['customer_note'],
                'usuario_venda' => 'ecomd',
                'usuario_pagamento' => 'ecomd',
                'usuario' => 'ecomd',
                'data' => "NOW()"
            );
            $id_venda = $db->insert("vendas", $data);
            $id_ecomd = $content['dados']['id'];

            foreach ($content['dados']['line_items'] as $item) {

                $dados = obterDadosProduto($item['product_id']);

                $custo = $dados['custo'];
                $valor = $dados['valor'];

                $cadastro_vendas_data = array(
                    'id_empresa' => 1,
                    'id_venda' => $id_venda,
                    'id_caixa' => 0,
                    'id_produto' => $item['product_id'],
                    'id_tabela' => 1,
                    'quantidade' => $item['quantity'],
                    'valor_custo' => $custo,
                    'valor_original' => ($valor > 0 ? $valor : $custo),
                    'valor' => ($valor > 0 ? $valor : $custo),
                    'valor_total' => $item['total'],
                    'pago' => ($content['dados']['payment_status'] == 'paid' ? 1 : 2),
                    'usuario' => 'ecomd',
                    'data' => "NOW()"
                );

                $cadastro_vendas = $db->insert("cadastro_vendas", $cadastro_vendas_data);

                $dados_atualizacao = ['id_produto' => $item['product_id'], 'quantidade' => $item['quantity'], 'id_ecomd' => $id_ecomd, 'id_venda' => $id_venda];

                $retorno = atualizarEstoque($dados_atualizacao, 2);

                if ($retorno['status'] == 'success') {
                    echo json_encode('Compra inserida com sucesso.');
                } else {
                    echo json_encode('Houve um erro ao tentar realizar a atualização de estoque.');
                }
            }
            http_response_code(200);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'A chave da API está incorreta ou ausente.'
            ]);
            http_response_code(401);
        }
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'A opção que tentou acessar não existe.'
        ]);
        http_response_code(401);
        break;
}
