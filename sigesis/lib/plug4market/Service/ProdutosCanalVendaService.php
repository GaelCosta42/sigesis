<?php

use Plug4Market\PostProdutoDTO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ProdutosCanalVendaService 
{
    const uTable = "produto";
    private static $db;
    private $apiBaseUrl = 'https://api.sandbox.plug4market.com.br';
    private string $apiToken; 

    public function __construct(string $apiToken) {

        self::$db = Registry::get("Database");
        $this->apiToken = $apiToken; 
    }

    public function buscarPorId(int $id): ?ProdutoDTO {

        $sql = "SELECT * FROM ordem_servico WHERE id = ?";
        $dados = $this->db->fetch($sql, [$id]);

        if (!$dados) {
            return null;
        }

        return new OrdemServicoDTO(
            $dados['id'],
            $dados['id_empresa'],
            $dados['id_cadastro'],
            $dados['id_tabela'],
            $dados['id_equipamento'],
            $dados['equipamento_digitado'],
            $dados['responsavel'],
            $dados['criticidade'],
            $dados['prioridade'],
            $dados['descricao_equipamento'],
            $dados['descricao_problema'],
            $dados['usuario'],
            new \DateTime($dados['data']),
            $dados['id_status']
        );
    }

    public function postProduto(PostProdutoDTO $produto) {

        $client = new Client(
            [
            'base_uri' => $this->apiBaseUrl,
            'timeout'  => 30.0,
            ]
        );

        try 
        {
            $response = $client->post('/products', [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiToken, 
                    'Accept' => 'application/json'],
                'json'    => $produto->toArray(),
            ]);

            $statusCode = $response->getStatusCode(); // 200 OK, 201 Created, etc.
            $body       = (string) $response->getBody(); // Corpo da resposta

            return [
                'success' => true,
                'status'  => $statusCode,
                'data'    => $body
            ];

        } 
        catch (RequestException $e) 
        {
            return [
                'success' => false,
                'status'  => $e->hasResponse() ? $e->getResponse()->getStatusCode() : 0,
                'response' => $e->hasResponse() 
                    ? json_decode($e->getResponse()->getBody()->getContents(), true) 
                    : null
            ];
        }
    }
}
