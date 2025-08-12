<?php
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 6000);
    ini_set('fastcgi_read_timeout', 6000);
    ini_set('default_socket_timeout', 9000);
    define('_VALID_PHP', true);
    require('../../init.php');

    $data = json_decode(file_get_contents('php://input'), true);

    header('Content-Type: application/json');

    function error($mensagem){
        echo json_encode(['code' => 400, 'error' => $mensagem]);
        exit();
    }

    function success($results = []) {
        echo json_encode(['code' => 200, 'results' => $results]);
        exit();
    }

    if(isset($_GET['token'])) {
        if(empty($data['email'])) error("Campo [email] não enviado");

        $email = strtoupper(sanitize($data['email']));

        $sql_usuario = "SELECT id FROM cadastro WHERE email = '$email' AND inativo = 0";
        $usuario = $db->first($sql_usuario);

        if ($usuario) {
            $data_usuario = [
                'codigo_recuperacao' => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'data_recuperacao' => 'NOW()'
            ];
            $db->update('cadastro', $data_usuario, 'id='.$usuario->id);

            $subdominio = explode('.', $_SERVER['HTTP_HOST'])[0];
            $titulo = $subdominio.' - Recuperar Senha';
            $mensagem = 'O seu código para recuperar a senha é '.$data_usuario['codigo_recuperacao'];
            
            if(enviarEmail($email, $titulo, $mensagem, $db))
                success('E-mail enviado com sucesso!');
            else
                error("Falha a o enviar o E-mail");
        } else
            error("E-mail não existente");

    } else if(isset($_GET['senha'])) {
        if(empty($data['email'])) error("Campo [email] não enviado");
        if(empty($data['token'])) error("Campo [token] não enviado");
        if(empty($data['senha'])) error("Campo [senha] não enviado");

        $email = sanitize($data['email']);
        $token = sanitize($data['token']);
        $senha = sanitize($data['senha']);

        $sql_usuario = "SELECT id FROM cadastro WHERE email = '{$email}' AND codigo_recuperacao = '{$token}' AND inativo = 0";
        $usuario = $db->first($sql_usuario);

        if ($usuario) {
            $data_usuario = [
                'codigo_recuperacao' => '',
                'senha_app' => sha1($data['senha']),
                'data_recuperacao' => 'NOW()'

            ];
            $db->update('cadastro', $data_usuario, 'id='.$usuario->id);

            success('Senha atualizada com sucesso!');
        } else
            error("Token inválido");
    }

    error("Nenhuma ação encontrado");

    
