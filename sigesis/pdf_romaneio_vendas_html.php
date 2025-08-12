<style>
    .tbdados td {
        border: 1px solid black
    }

    .title {
        font-size: 18px;
    }
</style>

<?php

ini_set("display_errors", 0);

// DECLARACAO DA CONEXAO COM O BANCO
$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

// VARIAVEIS DE FILTRAGEM DE DADOS
$dadosString    = isset($_POST['json']) ? $_POST['json'] : '';
$dtini          = $_POST['dtini'] ?? '';
$dtend          = $_POST['dtend'] ?? '';
$tpvenda        = $_POST['tpvenda'] ?? 0;
$pesquisa       = $_POST['pesquisa'] ?? null;
$condicoes      = "";

// VARIAVEIS DE CALCULO DE TOTAIS
$quantidadeTotal = 0;
$valorTotal = 0;
$pesoTotal = 0;
$margemLucroTotal = 0;

// VARIAVEIS DE EXIBICAO
$vendas = "";
$ids = [];

if (strlen($dadosString) > 0) {
    $filter = "cv.id_venda IN ($dadosString) AND cv.inativo = 0";
} else {
    // FILTRA POR PES
    if (isset($pesquisa)) {
        if (is_numeric($pesquisa)) {
            $condicoes .= "AND (v.id = $pesquisa or v.valor_total LIKE '%$pesquisa%') ";
        } else {
            $condicoes .= "AND (DATE_FORMAT(v.data_venda,'%d/%m/%Y') LIKE '%$pesquisa%') ";
        }
    }

    // BUSCA POR VENDAS ABERTAS/FINALIZADAS/TODAS
    if (isset($tpvenda) && $tpvenda != 0) {
        $status = " AND v.pago = $tpvenda";
    } else {
        $status = "";
    }

    // FILTRO COM OS MESMOS PARAMETROS DA LISTAGEM DE VENDAS
    $filter = " v.orcamento <> 1
                AND v.inativo = 0
                AND v.venda_agrupamento =   0
                AND v.data_venda BETWEEN '$dtini 00:00:00' AND '$dtend 23:59:59'
                AND cv.inativo = 0
                $condicoes
                $status ";

    // BUSCA TODOS OS ID'S DE VENDAS GERADOS EM GERAR TODOS
    $qry = "SELECT  v.id

            FROM        cadastro_vendas cv
            INNER JOIN  vendas          v   ON v.id = cv.id_venda
            INNER JOIN  produto         p   ON p.id = cv.id_produto

            WHERE $filter

            GROUP BY    v.id";

    $resultado = $db->query($qry);

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            if (!in_array($row['id'], $ids)) {
                $ids[] = $row['id'];
            }
        }
    }

    $vendas = count($ids) > 0 ? implode(", ", $ids) : '';
}

// DADOS DOS PRODUTOS DAS VENDAS
$qry = "SELECT  p.nome,
                SUM(cv.quantidade) AS 'quantidade',
                SUM(cv.valor * cv.quantidade) AS 'valor',
                SUM(p.peso * cv.quantidade) AS 'peso',
                SUM((cv.valor - p.valor_custo) * cv.quantidade) AS 'margemLucroTotal'

        FROM        cadastro_vendas cv
        INNER JOIN  vendas          v   ON v.id = cv.id_venda
        INNER JOIN  produto         p   ON p.id = cv.id_produto

        WHERE $filter
        AND cv.inativo = 0

        GROUP BY    cv.id_produto";

$resultado = $db->query($qry);
?>

<table class="tbdados">
    <thead>
        <tr>
            <th style="font-size: 11px; width: 59%" align="left">Produto(s)</th>
            <th style="font-size: 11px; width: 2%"></th>
            <th style="font-size: 11px; width: 2%"></th>
            <th style="font-size: 11px; width: 5%" align="right">Qtde</th>
            <th style="font-size: 11px; width: 12.5%" align="right">Valor</th>
            <th style="font-size: 11px; width: 7%" align="right">Peso</th>
            <th style="font-size: 11px; width: 12.5%" align="right"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) { ?>
                <tr>
                    <td style="font-size: 11px;width: 55%" align="left"><?php echo $row['nome']; ?></td>
                    <td style="width: 2%"></td>
                    <td style="width: 2%"></td>
                    <td style="font-size: 11px;width: 9%" align="right"><?php echo (number_format($row['quantidade'], 2)); ?></td>
                    <td style="font-size: 11px;width: 12.5%" align="right"><?php echo 'R$ ' . (number_format($row['valor'], 2)); ?></td>
                    <td style="font-size: 11px;width: 7%" align="right"><?php echo (number_format($row['peso'], 2)); ?></td>
                    <td style="font-size: 11px;width: 12.5%" align="right"><?php echo 'R$ ' . (number_format($row['margemLucroTotal'], 2)); ?></td>
                </tr>
            <?php
                $quantidadeTotal += $row['quantidade'];
                $valorTotal += $row['valor'];
                $pesoTotal += $row['peso'];
                $margemLucroTotal += $row['margemLucroTotal'];
            }
        } else { ?>
            <tr>
                <td colspan="5" align="center">Nenhum resultado encontrado</td>
            </tr>
        <?php
        } ?>
    </tbody>
    <tfoot>
        <tr>
            <td style="font-size: 11px; font-weight: bold; width: 59%" align="right">Sub. Total</td>
            <td style="font-size: 11px; font-weight: bold; width: 9%" align="right"><?php echo (number_format($quantidadeTotal, 2)); ?></td>
            <td style="font-size: 11px; font-weight: bold; width: 12.5%" align="right"><?php echo 'R$ ' . (number_format($valorTotal, 2)); ?></td>
            <td style="font-size: 11px; font-weight: bold; width: 7%" align="right"><?php echo (number_format($pesoTotal, 2)); ?></td>
            <td style="font-size: 11px; font-weight: bold; width: 12.5%" align="right"><?php echo 'R$ ' . (number_format(($margemLucroTotal), 2)); ?></td>
        </tr>
    </tfoot>
</table>

<p>Pedido(s): <?php echo (strlen($dadosString) > 0) ? $dadosString : $vendas; ?></p>