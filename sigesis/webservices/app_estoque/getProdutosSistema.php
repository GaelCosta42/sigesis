<?php
  /**
   * Webservices: Mostra produtos no app estoque. (import)
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../../init.php');
		
    try {        
        $status = "";
        $row_produto = $produto->getProdutosAppEstoque();
        if($row_produto) {		
            foreach ($row_produto as $exrow){
                $array_produto[] = array(
                    "id_produto"            => (int) $exrow->id,
                    "id_tabela"             => (int) $exrow->id_tabela,
                    "nome"                  => (string) $exrow->nome,
                    "codigo"                => (string) $exrow->codigo,
                    "codigobarras"          => (string) $exrow->codigobarras,
                    "ncm"                   => (string) $exrow->ncm,
                    "cfop"                  => (int) $exrow->cfop,
                    "icms_cst"              => (string) $exrow->icms_cst,
                    "cest"                  => (string) $exrow->cest,
                    "monofasico"            => (int) $exrow->monofasico,
                    "estoque"               => (float) 0,
                    "unidade"               => (string) $exrow->unidade,
                    "valor_venda"           => (float) $exrow->valor_venda ? (float) $exrow->valor_venda : 1,
                    "valor_custo"           => (float) $exrow->valor_custo > 0 ? (float) $exrow->valor_custo : 0
                );
            }

            $jsonRetorno = array(
                "status" => 200,
                "produtos" => $array_produto
            );    
            $retorno =  json_encode($jsonRetorno);
            echo $retorno;

        } else {
            $array_produto = "";
            $status = "Array vazio.";

            $jsonRetorno = array(
                "status" => 200,
                "retorno" => $status,
                "produtos" => array()
            );
            $retorno =  json_encode($jsonRetorno);
            echo $retorno;
        }

    } catch (Exception $e) {
        $status = "Erro - EXCEÇÃO: ".$e->getMessage();
        $array_produto = "";
        $jsonRetorno = array(
            "status" => 400,
            "retorno" => $status,
        );
        $retorno =  json_encode($jsonRetorno);
        echo $retorno;
    }
   

?>