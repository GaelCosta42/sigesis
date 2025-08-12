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

if(empty($data['tipo'])) error("Campo [tipo] não enviado");

$nome = (!empty($data['nome'])) ? strtoupper($data['nome']) : '';
$usuario = (!empty($data['usuario'])) ? $data['usuario'] : '';
$senha = (!empty($data['senha'])) ? sha1(strtolower($data['senha'])) : '';
$email = (!empty($data['email'])) ? strtoupper($data['email']) : '';
$id_google = (!empty($data['id_google'])) ? $data['id_google'] : '';
$id_facebook = (!empty($data['id_facebook'])) ? $data['id_facebook'] : '';


$tipo = strtoupper($data['tipo']);

$usuario = sanitize(strtolower($usuario));
$usuario = str_replace(" ",".",$usuario);

$sql_empresa = "SELECT id FROM empresa WHERE inativo = 0";
$row_empresa = $db->first($sql_empresa);
$id_empresa = ($row_empresa) ? $row_empresa->id : 0;

if($tipo === "VENDEDOR") {
    $sql = "SELECT id FROM usuario WHERE usuario = '".$usuario."' AND senha = '".$senha."' AND active = 'y'";
    $usuario = $db->first($sql);
}
else if($tipo === "USUARIO") {
    if (!empty($id_google) && !empty($email) && !empty($nome)) {
        $sql_usuario_google = "SELECT id FROM cadastro WHERE id_google = '".$id_google."' AND inativo = 0";
        $usuario_google = $db->first($sql_usuario_google);

        if (empty($usuario_google)) {
            $sql_usuario_email = "SELECT id FROM cadastro WHERE email = '".$email."' AND inativo = 0";
            $usuario_email = $db->first($sql_usuario_email);

            if (empty($usuario_email)) {
                /* ADICIONA UM CADASTRO NOVO COM AS INFORMAÇÕES DO GOOGLE */
                $nome = cleanSanitize($nome);
                $data_cadastro = array(
					'id_empresa' => $id_empresa,
					'nome' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
					'razao_social' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
					'email' => $email,
					'id_google' => $id_google,
					'observacao' => "CLIENTE CADASTRADO PELA CONTA GOOGLE",
					'cliente' => 1,
					'data_cadastro' => "NOW()",
					'inativo' => 0,
					'usuario' => "app",
					'data' => "NOW()"
				);
				$id_usuario = $db->insert("cadastro", $data_cadastro);
				$sql_usuario = "SELECT * FROM cadastro WHERE id =".$id_usuario;
				$usuario = $db->first($sql_usuario);
            } else {
				$data_cadastro = array(
					'id_google' => $id_google
				);
                $db->update('cadastro', $data_cadastro, 'id='.$usuario_email->id);
                $usuario = $usuario_email;
            }
        } else
            $usuario = $usuario_google;

    } else if (!empty($id_facebook) && !empty($email) && !empty($nome)) {
        $sql_usuario_facebook = "SELECT id FROM cadastro WHERE id_facebook = '".$id_facebook."' AND inativo = 0";
        $usuario_facebook = $db->first($sql_usuario_facebook);

        if (empty($usuario_facebook)) {
            $sql_usuario_email = "SELECT id FROM cadastro WHERE email = '".$email."' AND inativo = 0";
            $usuario_email = $db->first($sql_usuario_email);

			if (empty($usuario_email)) {
                /* ADICIONA UM CADASTRO NOVO COM AS INFORMAÇÕES DO FACEBOOK */
                $nome = cleanSanitize($nome);
                $data_cadastro = array(
					'id_empresa' => $id_empresa,
					'nome' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
					'razao_social' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
					'email' => $email,
					'id_facebook' => $id_facebook,
					'observacao' => "CLIENTE CADASTRADO PELA CONTA GOOGLE",
					'cliente' => 1,
					'data_cadastro' => "NOW()",
					'inativo' => 0,
					'usuario' => "app",
					'data' => "NOW()"
				);
				$id_usuario = $db->insert("cadastro", $data_cadastro);
				$sql_usuario = "SELECT * FROM cadastro WHERE id =".$id_usuario;
				$usuario = $db->first($sql_usuario);
            } else {
				$data_cadastro = array(
					'id_facebook' => $id_facebook
				);
                $db->update('cadastro', $data_cadastro, 'id='.$usuario_email->id);
                $usuario = $usuario_email;
            }            
        } else
            $usuario = $usuario_facebook;

    } else
        error("Campo ([id_google ou id_facebook] e [email] e [nome] vazio ou não enviado");
} else
    error("Campo [tipo] valor errado");

if(!empty($usuario)) {
    echo json_encode(['code'=>200, 'result' => ['id'=> (integer) $usuario->id]]);
} else {
    error('Usuário não encontrado');
}