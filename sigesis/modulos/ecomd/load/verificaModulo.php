<?php define("_VALID_PHP", true);
include_once("../../../init.php");

$VerificaModulo = "SELECT 
    modulo_integracao_ecommerce
    FROM empresa";

$result = $db->fetch_all($VerificaModulo);

if($result[0]->modulo_integracao_ecommerce != 1){
    echo json_encode(['msg' => 'Módulo não habilitado.', 'status' => 'error']);
}else{
    echo json_encode(['msg' => 'Módulo pronto para uso.', 'status' => 'success']);
}