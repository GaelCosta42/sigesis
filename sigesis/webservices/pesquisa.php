<?php
	
	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	define("_VALID_PHP", true);
	$BASEPATH = str_replace("pesquisa.php", "", realpath(__FILE__));
	define("BASEPATH", $BASEPATH);
	$configFile = BASEPATH . "../lib/config.ini.isaac.php";
	require_once($configFile);
	require_once(BASEPATH . "../lib/class_db.php");  
	require_once(BASEPATH . "../lib/class_registry.php");
	Registry::set('Database',new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE));
	$db = Registry::get("Database");
	$db->connect();
	require_once(BASEPATH . "../lib/functions.php");
  
	$busca = (!empty($_GET['busca'])) ? $_GET['busca'] : null;
	echo "Buscar por: [$busca]<br>";
	echo ' >> INICIO - '.date('d/m/Y H:i:s').'<br>';
	
	$sql = "SELECT TABLE_NAME, COLUMN_NAME "
	  . "\n FROM INFORMATION_SCHEMA.COLUMNS "
	  . "\n WHERE TABLE_SCHEMA = '".DB_DATABASE."'";
    $retorno_row = $db->fetch_all($sql);
	$tabela = '';
	$update = '';
	$t = 0; 
	$i = 0; 
	echo '<br><strong> >> TOTAL DE COLUNAS: '.count($retorno_row).'</strong><br><br>';
	if($retorno_row and $busca) {
		foreach ($retorno_row as $exrow){
			$table_name = $exrow->TABLE_NAME;
			$column_name = $exrow->COLUMN_NAME;
			$sql_busca = "SELECT $column_name AS coluna FROM $table_name WHERE $column_name LIKE '%$busca%'; ";
			$retorno_busca = $db->first($sql_busca);
			if($retorno_busca) {
				echo "<br/>Tabela: $table_name - Coluna: $column_name - Resultado: ".$retorno_busca->coluna;
			}
		}
		unset($exrow);
	}
	echo '<br><strong> >> TOTAL DE TABELAS: '.$t.'</strong><br><br>';
	echo ' >> FINAL - '.date('d/m/Y H:i:s').'<br>';
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-BR" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="pt-BR" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-BR">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>

<!-- Meta -->
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="SIGESIS - Sistemas - VOCÊ NO CONTROLE DA SUA EMPRESA, em qualquer lugar... a qualquer momento!"/>
<meta name="keywords" content="vale telecom, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis, telecom" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Vale Telecom"/>

<!-- Title -->
<title>Pesquisar</title>

<!-- Favicons -->
<link rel="shortcut icon" href="../assets/img/favicon.png">
<link rel="apple-touch-icon" href="../assets/img//favicon_60x60.png">
<link rel="apple-touch-icon" sizes="76x76" href="../assets/img//favicon_76x76.png">
<link rel="apple-touch-icon" sizes="120x120" href="../assets/img//favicon_120x120.png">
<link rel="apple-touch-icon" sizes="152x152" href="../assets/img//favicon_152x152.png">
</head>
<body>
</body>
</html>