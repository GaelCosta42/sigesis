<?php
    /**
    * Completar informações fiscais do produto na nota fiscal através do codigo de barras
    * webservices/listar_cadastro_cnpj.php
    *
    **/
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);

    define("_VALID_PHP", true);
    require('../init.php');

	$cpf_cnpj = "";
	if( isset($_GET['cpfcnpjdestinatario']) ) $cpf_cnpj = $_GET['cpfcnpjdestinatario'];
	if( isset($_POST['cpfcnpjdestinatario']) ) $cpf_cnpj = $_POST['cpfcnpjdestinatario'];

	if ($cpf_cnpj) {

        $sql = "SELECT * FROM cadastro
		WHERE cpf_cnpj = $cpf_cnpj ";
        $resposta = $db->query($sql);

        if($resposta) {
            while ($r = $resposta->fetch_object()) {
                $retorno = array(
                    "retorno" => 1,
                    "id" => $r->id,
                    "tipo" => $r->tipo,
                    "cep" => $r->cep,
                    "endereco" => $r->endereco,
                    "numero" => $r->numero,
                    "complemento" => $r->complemento,
                    "bairro" => $r->bairro,
                    "cidade" => $r->cidade,
                    "estado" => $r->estado
                );
            }
        }
        echo json_encode($retorno);

	} else echo 'CPF ou CNPJ não encontrado.';

	unset($r);

?>