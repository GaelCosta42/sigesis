<?php
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 600);
    ini_set('fastcgi_read_timeout', 600);
    define("_VALID_PHP", true);
    require('../init.php');
    $results = array();
    $query = $_REQUEST["query"];
    $retorno_row = $cadastro->getListaCadastros($query);
    if($retorno_row) {
        foreach ($retorno_row as $exrow){
            $results[] = array(
                "id" => $exrow->id,
                "nome" => html_entity_decode(capitalize($exrow->nome)),
                "razao_social" => capitalize($exrow->razao_social),
                "cpf_cnpj" => formatar_cpf_cnpj($exrow->cpf_cnpj),
                "endereco_completo" => capitalize($exrow->endereco.', '.$exrow->numero.' - '.$exrow->bairro.' - '.$exrow->cidade),
                "telefone" => $exrow->telefone,
                "telefone2" => $exrow->telefone2,
                "celular" => $exrow->celular,
                "celular2" => $exrow->celular2,
                "email" => $exrow->email,
                "crediario" => $exrow->crediario,
                "crediarioSistema" => ($core->tipo_sistema!=1 && $core->tipo_sistema!=3),
                "tokens" => array($query, $exrow->nome)
            );
        }
        unset($exrow);
    }
    echo json_encode($results);
?>