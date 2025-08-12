<?php
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 6000);
    ini_set('fastcgi_read_timeout', 6000);
    ini_set('default_socket_timeout', 9000);
    define('_VALID_PHP', true);
    require('../../init.php');

    cors();

    $data = json_decode(file_get_contents('php://input'), true);

    header('Content-Type: application/json');

    function error($mensagem){
        echo json_encode(['code' => 400, 'error' => $mensagem]);
        exit();
    }

    if(empty($data['nome'])) error("Campo [nome] não enviado");
    if(empty($data['email']) && empty($data['telefone'])) error("Campo [email] e [telefone] vazio ou não enviado");
    if(empty($data['senha'])) error("Campo [senha] não enviado");

    $nome = (!empty($data['nome'])) ? mb_strtoupper($data['nome']) : '';
    $email = (!empty($data['email'])) ? mb_strtoupper($data['email']) : '';
    $telefone = (!empty($data['telefone'])) ? limparTelefone($data['telefone']) : '';
    $senha = (!empty($data['senha'])) ? sha1($data['senha']) : '';
	
	$sql_empresa = "SELECT id FROM empresa WHERE inativo = 0";
	$row_empresa = $db->first($sql_empresa);
	$id_empresa = ($row_empresa) ? $row_empresa->id : 0;

    if(!empty($email)) {
        $count_email = $db->first("SELECT count(*) AS count FROM cadastro WHERE email = '".$email."' AND inativo = 0")->count;
        if ($count_email)
            error("E-mail já existente.");
    }

    if(!empty($telefone)) {
        $count_telefone = $db->first("SELECT count(*) AS count FROM cadastro WHERE telefone = '".$telefone."' AND inativo = 0")->count;
        if ($count_telefone)
            error("Telefone já existente.");
    }

    $nome = cleanSanitize($nome);
    $data_cadastro = [
        'id_empresa' => $id_empresa,
        'nome' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
        'razao_social' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
		'email' => $email,
        'telefone' => $telefone,
        'senha_app' => $senha,
        'observacao' => "CLIENTE CADASTRADO PELO APLICATIVO",
        'cliente' => 1,
        'data_cadastro' => "NOW()",
        'inativo' => 0,
        'usuario' => "app",
        'data' => "NOW()"
    ];
    $id_cadastro = $db->insert("cadastro", $data_cadastro);

    if ($db->affected()) {
        echo json_encode(['code' => 200, 'result' => ['id' => $id_cadastro]]);
    } else
        echo json_encode(["code" => 400,"error" => "Dados já existentes"]);