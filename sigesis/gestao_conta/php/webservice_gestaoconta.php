<?php
define("_VALID_PHP", true);

if (file_exists("../../init.php")) {
    require_once("../../init.php");
} else if (file_exists("././init.php")) {
    require_once("././init.php");
}

function conectarBanco()
{
    $host = DB_SERVER;
    $usuario = DB_USER;
    $senha = DB_PASS;
    $banco = DB_DATABASE;

    $conexao = new mysqli($host, $usuario, $senha, $banco);
    if ($conexao->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
    }
    return $conexao;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $conexao = conectarBanco();

    if (isset($data->login) && isset($data->senha)) {

        $login = $data->login;
        $senha = sha1(strtolower($data->senha));

        $query = "SELECT * FROM usuario 
                  WHERE (email = '$login' OR usuario = '$login' OR telefone = '$login') 
                  AND senha = '$senha'";
        $resultado = $conexao->query($query);

        if ($resultado->num_rows == 1) {
            /*
            $query = "SELECT * 
            FROM usuario
            WHERE (email = '$login' OR usuario = '$login' OR telefone = '$login') 
            AND senha = '$senha'";
            $resultado = $conexao->query($query);
            */

            $dadosUsuario = $resultado->fetch_assoc();

            unset($dadosUsuario['senha']);

            $conexao->close();
            
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($dadosUsuario);
            exit;
        } else {
            echo ($login);
            http_response_code(401);
            echo json_encode(array('erro' => 'Credenciais inválidas.'));
            exit;
        }


    } else {
        http_response_code(400);
        echo json_encode(array('erro' => 'Login e senha são obrigatórios.'));
        exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);
    $conexao = conectarBanco();

    if ($data->action == 'mudar_senha') {
        $id = $data->id;
        $password = sha1(strtolower($data->password));

        $query = "UPDATE usuario 
                    SET senha = '$password'
                    WHERE id = '$id'";

        echo($query);

        if ($conexao->query($query) === TRUE) {
            http_response_code(200);
            echo json_encode(array('mensagem' => 'Dados atualizados com sucesso.'));
        } else {
            http_response_code(500);
            echo json_encode(array('erro' => 'Erro ao atualizar dados.'));
        }

    } else if (isset($data->id)) {
        $id = $data->id;
        $email = $data->email;
        $nome = $data->nome;
        $telefone = $data->celular;
        $cep = $data->cep;
        $logradouro = $data->logradouro;
        $bairro = $data->bairro;
        $numero = $data->numero;
        $complemento = $data->complemento;

        $queryUsuarioApp = "UPDATE usuario
                            SET email = '$email', 
                                telefone = '$telefone', 
                                nome = '$nome',
                                cep = '$cep', 
                                endereco = '$logradouro',
                                bairro = '$bairro',
                                numero = '$numero',
                                complemento = '$complemento'
                            WHERE id = '$id'";

        if ($conexao->query($queryUsuarioApp) === TRUE) {
            http_response_code(200);
            echo json_encode(array('mensagem' => 'Dados atualizados com sucesso.'));
        } else {
            http_response_code(500);
            echo json_encode(array('erro' => 'Erro ao atualizar dados.'));
        }

        $conexao->close();

    } else {
        http_response_code(400);
        echo json_encode(array('erro' => 'Todos os campos são obrigatórios.'));
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    if (isset($data->id)) {
        $conexao = conectarBanco();
        $idUsuarioApp = $conexao->real_escape_string($data->id);

        //$queryUsuarioApp = "DELETE usuario_app WHERE id = '$idUsuarioApp'";
        $queryUsuarioApp = "UPDATE usuario SET active='n', nivel=0 WHERE id = '$idUsuarioApp'";
        
        if ($conexao->query($queryUsuarioApp) === TRUE) {
            http_response_code(200);
            echo json_encode(array('mensagem' => 'Registro excluído com sucesso.'));
        } else {
            http_response_code(500);
            echo json_encode(array('erro' => 'Erro ao excluir registro.'));
        }
    } else {
        http_response_code(400);
        echo json_encode(array('erro' => 'Usuário não encontrado.'));
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode(array('erro' => 'Método não permitido.'));
    exit;
}
