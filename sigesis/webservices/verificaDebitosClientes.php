<?php
	
     /**
     * Traz todos os produtos ao apertar o atalho F1 em Vendas > Nova Venda.
     * 
     */

    set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
		
    define("_VALID_PHP", true);
    require('../init.php');
    
    if( isset($_GET['id_cliente']) ) 
        $id_cliente = $_GET['id_cliente'];
    else
        $id_cliente=0;

    $clienteEstaDevendo = 0;
    $tolerancia_crediario = getValue("tolerancia_crediario","empresa","id=1");
    $devendoFicha = $cadastro->clienteDevendoFicha($id_cliente,$tolerancia_crediario);
    $devendoCrediario = $cadastro->clienteDevendoCrediario($id_cliente,$tolerancia_crediario);
    $clienteEstaDevendo = ($devendoFicha || $devendoCrediario) ? 1 : 0;
    echo $clienteEstaDevendo;  
?>