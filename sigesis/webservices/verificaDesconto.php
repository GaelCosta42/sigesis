<?php

    /**
    *   Verifica se o desconto que a pessoa der é maior do que o permitido. 
    *   Verificação feita em Vendas > Nova venda. 
    *   webservices/verificaDesconto.php
    */

    set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
		
    define("_VALID_PHP", true);
    require('../init.php');

    $id_tabela = $_GET['id_tabela'] ?? $_POST['id_tabela'];

    if($id_tabela){

        $sql = "SELECT id, tabela, desconto, percentual, inativo
        FROM tabela_precos 
        WHERE inativo = 0 AND id = {$id_tabela} ";
        $retorno = $db->first($sql);
        
        echo json_encode($retorno, JSON_PRETTY_PRINT); //Retorna o json formatado
    } else 
?>

