<?php
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 600);
    ini_set('fastcgi_read_timeout', 600);
    define("_VALID_PHP", true);
    require('../init.php');
    $id_cadastro = $_GET["id_cadastro"];

    $data = array(
        'inativo' => 0,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    echo($db->update("cadastro", $data, "id=".$id_cadastro));

    echo json_encode([
        "mesage" => "Cadastro reativado com sucesso!"
    ]);
?>