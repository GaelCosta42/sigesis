<?php

  /**
   * Buscar produto
   *
   */
    define("_VALID_PHP", true);
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');
    require('../../init.php');

    $data = (!empty($_GET['nome'])) ? "AND (p.nome LIKE '%" . $_GET['nome'] . "%' OR p.codigo LIKE '%" . $_GET['nome'] . "%') " : '';

	$sql = "SELECT p.id, p.nome, p.codigo, pt.id_tabela"
      . "\n FROM produto as p"
      . "\n INNER JOIN produto_tabela AS pt ON p.id = pt.id_produto"
      . "\n INNER JOIN tabela_precos AS t ON t.id = pt.id_tabela"
      . "\n WHERE p.inativo = 0 AND p.grade = 1 AND t.inativo = 0 $data"
      . "\n ORDER BY p.nome LIMIT 50";
    $rows = $db->fetch_all($sql);

    echo json_encode($rows);
?>