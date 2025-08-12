<?php
/**
*   Opção para o cliente excluir dados no app Pedidos;
* 
**/
set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 6000);
ini_set('fastcgi_read_timeout', 6000);
ini_set('default_socket_timeout', 9000);
define('_VALID_PHP', true);
require('../../init.php');

function sendLogToFirebase($logBody) {
    $lambdaUrl = "https://southamerica-east1-error-logger-5e474.cloudfunctions.net/main";
    $body = array_merge($logBody, ["firestoreCollection" => "logs"]);
    $curl = curl_init($lambdaUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($body));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
try {
    $json_string = file_get_contents('php://input');
    $json_array = json_decode($json_string,true);

        $nome = "";
        $email = "";
        $id_cadastro = 0;

        if($json_string) {
            foreach($json_array as $nome_campo => $valor) {
                if($nome_campo == "nome")
                    $nome = $valor;	
                if($nome_campo == "email")
                    $email = $valor;
                if($nome_campo == "id_cadastro")
                    $id_cadastro = $valor;
            }

            $nome = sanitize(strtoupper($nome));

            if (!$id_cadastro) {
                $code = 0;
                $status = "ERRO!Cadastrado não encontrado";
            } else {
                $data_cadastro = array(
                    'inativo' => '1',
                    'usuario' => 'APP_CANCELAMENTO',
                    'data' => "NOW()"
                );
                $db->update("cadastro", $data_cadastro, "id=". $id_cadastro);

                if ($db->affected()){
                    $code = 200;
                    $status = "Cadastro excluído com sucesso";
                } else {
                    $code = 0;
                    $status = "ERRO! Cadastro não excluído";
                }
            }
        }  else {
            $status = "Erro! JSON vazio.";
            $json_success = array(
                "status" => 400,
                "retorno" => $status
            );
            $retorno = json_encode($json_success);
            echo $retorno;
        }
    } catch (Exception $e) {
        $status = "Erro - JSON[".$json_string."] EXCEÇÃO: ".$e->getMessage();
        $json_erro = array(
            "status" => 400,
            "retorno" => 'Error: '.$status
        );
        $retorno =  json_encode($json_erro);
        echo $retorno;
    }
?>