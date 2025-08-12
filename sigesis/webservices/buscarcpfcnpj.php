<?php
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 600);
    ini_set('fastcgi_read_timeout', 600);
    define("_VALID_PHP", true);
    require('../init.php');
    $results = array();
    $query = $_REQUEST["query"];
    $retorno_row = $cadastro->getListaCadastrosCpfcnpj($query);
    if($retorno_row) {
        foreach ($retorno_row as $exrow){
            $results[] = array(
                "id_cadastro" => $exrow->id,
                "nome" => $exrow->nome,
                "telefone" => $exrow->telefone,
                "celular" => $exrow->celular,
                "cpf_cnpj" => formatar_cpf_cnpj($exrow->cpf_cnpj),
                "endereco" => capitalize($exrow->endereco.', '.$exrow->numero.' - '.$exrow->bairro.' - '.$exrow->cidade),
                "status" => $exrow->inativo
            );
        }
        unset($exrow);
    }
    echo json_encode($results);
?>