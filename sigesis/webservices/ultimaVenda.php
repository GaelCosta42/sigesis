<?php

    /**
     * Traz o ultimo ID inserido na venda, ou seja, a ultima venda feita.
     * 
     */

    set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
		
    define("_VALID_PHP", true);
    require('../init.php');

    $sql = " SELECT max(id) as id FROM vendas WHERE inativo=0 ";
    $retorno = $db->first($sql);

    //echo json_encode($retorno, JSON_PRETTY_PRINT); //Retorna o json formatado
    echo $retorno->id;

?>

