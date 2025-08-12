<?php
ini_set("display_errors", true);
define("_VALID_PHP", true);
require_once("../../../init.php");

$pesquisa = $_POST['pesquisa'] ?? '';
$palavras = explode(' ', $pesquisa);
!empty($_POST['data']) ? $data = '"' . $_POST['data'] . '"' : $data = ' CURRENT_DATE() ';

$countQuery = " SELECT  tp.tipo,
                	    SUM( v.valor_pago - v.troco ) AS pagamento_total 
                FROM        vendas              AS v
                LEFT JOIN   cadastro            AS c    ON c.id = v.id_cadastro
                LEFT JOIN   usuario             AS u    ON u.id = v.id_vendedor
                LEFT JOIN   cadastro_financeiro AS cf   ON cf.id_venda = v.id
                LEFT JOIN   tipo_pagamento      AS tp   ON tp.id = cf.tipo 

                WHERE   ( v.pago = 1 OR v.fiscal = 2 ) 
                AND     cf.inativo = 0 
                AND     DATE( v.data_venda ) = $data  
                GROUP BY tp.id";
                
$resultado = $db->fetch_all($countQuery);

$dados = [];
if (count($resultado) > 0) {
    foreach ($resultado as $key => $value) {
        $dados[] = array(
            $value->tipo,
            moedap($value->pagamento_total)
        );
    }

    echo json_encode([
        'msg' => 'Dados carregados com sucesso.',
        'status' => 'success',
        'dados' => $dados,
        'numpaginas' => 1,
        'linhasPorPagina' => count($resultado),
        'pagina' => 1
    ]);
} else {
    echo json_encode([
        'msg' => 'Nenhum dado foi encontrado.',
        'status' => 'success',
        'dados' => null,
        'numpaginas' => 1,
        'pagina' => 1
    ]);
}