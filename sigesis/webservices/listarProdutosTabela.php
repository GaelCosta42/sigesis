<?php
	
     /**
     * Traz todos os produtos de uma tabela de preços.
     * 
     */

    set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
		
    define("_VALID_PHP", true);
    require('../init.php');
    
    $id_tabela = '';
    
    if( isset($_GET['id_tabela']) ) 
        $id_tabela = $_GET['id_tabela'];
    elseif( isset($_POST['id_tabela']) ) 
        $id_tabela = $_POST['id_tabela'];

        $sql = " SELECT p.id, p.nome, p.codigo, p.codigobarras, pt.id_tabela as id_tabela, pt.valor_venda, p.estoque
        FROM produto as p
        LEFT JOIN produto_tabela as pt on pt.id_produto = p.id
        LEFT JOIN tabela_precos as tp on tp.id = pt.id_tabela
        WHERE p.inativo = 0 
        AND p.grade = 1 AND tp.id = {$id_tabela}
        ORDER BY p.nome ";
        $retorno = $db->fetch_all($sql);
        echo json_encode($retorno, JSON_PRETTY_PRINT); //Retorna o json formatado
  
?>