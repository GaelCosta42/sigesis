<?php

define("_VALID_PHP", true);

require_once("init.php");
if (!$usuario->is_Todos())
    redirect_to("login.php");

$mes_ano = $_GET['mes_ano'];
$entrada = $_GET['entrada'];
$inventario_fiscal = empty($_GET['inventario_fiscal']) ? 0 : 1;
$registros = $sintegra->getSintegraDownload($mes_ano, $inventario_fiscal, $entrada);
$filename = 'sintegra_'.str_replace('/', '', $mes_ano).'.txt';
header("Content-Type: text/plain");
header('Content-Disposition: attachment; filename="'.$filename.'"');
header("Content-Length: " . strlen($registros));
echo $registros;
exit;
?>