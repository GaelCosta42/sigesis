<?php
  /**
   * Webservices: Login: SIGESIS VENDAS
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
    $json_string = file_get_contents('php://input');
    $json_array = json_decode($json_string,true);
    
    $id_usuario = 0;
    $nivel = 0;
    $retorno = 0;
    $nome_usuario = "";
    $usuario = "";
    $senha = "";
    $pin = "";
    $status = "";
    $resposta = "";
    
    if ($json_string) {

        foreach($json_array as $nome_campo => $valor) {
            if($nome_campo == "usuario")
                $usuario = $valor;	
            if($nome_campo == "senha")
                $senha = $valor;
            if($nome_campo == "pin")
                $pin = $valor;
        }
        
        $nomeusuario = sanitize(strtolower($usuario));
        $nomeusuario = $db->escape($nomeusuario);
        $senha = sanitize(strtolower($senha));

        if ($pin != "") {
            
            $entered_pin = sha1($pin);
            $sql = "SELECT id, id_empresa, usuario, nome, nivel, active FROM usuario WHERE pin = '" . $entered_pin . "'";
            $result = $db->query($sql);
            
            if ($db->numrows($result) == 0) {
                $retorno = 0;
                $resposta = http_response_code(401);
            } else {
                $row = $db->fetch($result);
                if($row->active == "y") {

                    $id_usuario = $row->id;
                    $id_empresa = $row->id_empresa;
                    $usuario = $row->usuario;
                    $nome_usuario = $row->nome;
                    $nivel = $row->nivel;
                    
                    $sql_empresa = "SELECT id, nome, endereco, numero, bairro, cidade, estado, telefone, cnpj FROM empresa WHERE id = '$id_empresa' ";
                    $res_empresa = $db->query($sql_empresa);
                    if ($db->numrows($res_empresa) == 0) {
                        $array_empresa = [];
                    } else {
                        $row_empresa = $db->fetch($res_empresa);
                        $array_empresa[] = array(
                            "id" => $row_empresa->id,
                            "nome" => $row_empresa->nome,
                            "endereco" => $row_empresa->endereco,
                            "numero" => $row_empresa->numero,
                            "bairro" => $row_empresa->bairro,
                            "cidade" => $row_empresa->cidade,
                            "estado" => $row_empresa->estado,
                            "telefone" => $row_empresa->telefone,
                            "cnpj" => formatar_cpf_cnpj($row_empresa->cnpj)
                        );
                    }
                    $retorno = 1;
                    $status = "Sucesso.";
                    $resposta = http_response_code(200);
                } else {
                    $retorno = 0;
                    $status = "Erro! Usuario desativado.";
                    $resposta = http_response_code(401);
                }
            }
        } elseif($usuario != ""){
            $sql = "SELECT id, id_empresa, usuario, nome, senha, nivel, active FROM usuario WHERE active = 'y' AND usuario = '" . $nomeusuario . "'";
            $result = $db->query($sql);
            if ($db->numrows($result) == 0) {
                $retorno = 0;
                $resposta = http_response_code(401);
            } else {
                $row = $db->fetch($result);
                $entered_pass = sha1($senha);
                if($row->active == "y" && $entered_pass == $row->senha) {

                    $id_usuario = $row->id;
                    $id_empresa = $row->id_empresa;
                    $usuario = $row->usuario;
                    $nome_usuario = $row->nome;
                    $nivel = $row->nivel;
                    
                    $sql_empresa = "SELECT id, nome, endereco, numero, bairro, cidade, estado, telefone, cnpj FROM empresa WHERE id = '$id_empresa' ";
                    $res_empresa = $db->query($sql_empresa);
                    if ($db->numrows($res_empresa) == 0) {
                        $array_empresa = [];
                    } else {
                        $row_empresa = $db->fetch($res_empresa);
                        $array_empresa[] = array(
                            "id" => $row_empresa->id,
                            "nome" => $row_empresa->nome,
                            "endereco" => $row_empresa->endereco,
                            "numero" => $row_empresa->numero,
                            "bairro" => $row_empresa->bairro,
                            "cidade" => $row_empresa->cidade,
                            "estado" => $row_empresa->estado,
                            "telefone" => $row_empresa->telefone,
                            "cnpj" => formatar_cpf_cnpj($row_empresa->cnpj)
                        );
                    }
                    $retorno = 1;
                    $status = "Sucesso";
                    $resposta = http_response_code(200);
                    
                } else {
                    $retorno = 0;
                    $status = "Erro! Usuario ou senha invalidos.";
                    $resposta = http_response_code(401);
                }
            }
        } else {
            $status .= "Erro - USUARIO ou PIN nao informado.";
            $resposta = http_response_code(400);
        }
    } else {
        $status .= "Erro - JSON vazio. ";
        $resposta = http_response_code(400);
    }
   
    $id_usuario = intval($id_usuario);
    $jsonRetorno = array(
        "id_usuario" => $id_usuario,
        "nome_usuario" => $nome_usuario,
        "nivel" => $nivel,
        "array_empresa" => $array_empresa,
        "retorno" => $retorno,
        "status" => $status,
        "json" => json_decode($json_string, true),
        "resposta" => $id_usuario < 1 ? http_response_code(401) : http_response_code(200)
    );
    $retorno =  json_encode($jsonRetorno, JSON_PRETTY_PRINT);
    echo $retorno;

} catch (Exception $e) {
    $status .= "Erro - ".$e->getMessage();
    $json_success = array(
        "status" => 400,
        "retorno" => $status,
    );
    $res = json_encode($json_success, JSON_PRETTY_PRINT);
    echo $res;
}

	
?>