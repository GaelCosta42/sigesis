<?php

set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 6000);
ini_set('fastcgi_read_timeout', 6000);
ini_set('default_socket_timeout', 9000);
define('_VALID_PHP', true);
require('../../init.php');

cors();

header('Content-Type: application/json');
$body = json_decode(file_get_contents('php://input'), true);

function error($mensagem){
    echo json_encode(['code' => 400, 'error' => $mensagem]);
    exit();
}

function success($results = []) {
    echo json_encode(['code' => 200, 'results' => $results]);
    exit();
}

$id_usuario = $body['usuario']['id_usuario'] ?? '';
$telefone = (!empty($body['usuario']['telefone'])) ? $body['usuario']['telefone'] : '';

if(!$id_usuario) error('Campo [usuario][id_usuario] não enviado');
else if(!$telefone) error("Campo [usuario][telefone] não enviado");
else {
    if ($id_usuario) {
        $sql_usuario = "SELECT id, celular FROM cadastro WHERE id = {$id_usuario} AND inativo = 0";
        $usuario = $db->first($sql_usuario);
        if(!$usuario) error("Usuário não encontrado");
    }
    $telefone = formatar_telefone($telefone);
    $count_telefone = $db->first("SELECT count(*) AS count FROM cadastro WHERE celular = '".$telefone."' AND inativo = 0")->count;
    if ($count_telefone)
		error("Celular já cadastrado.");

	$db->update('cadastro', [
		'celular' => $telefone,
		'data' => "NOW()"
	], 'id='.$usuario->id);
   
    echo json_encode(['code' => 200]);
} 