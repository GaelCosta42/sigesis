<?php
	
	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	define("_VALID_PHP", true);
	require('../init.php');	
	echo ' >> INICIO - '.date('d/m/Y H:i:s').'<br>';
	
	$sql = "SELECT c.TABLE_NAME, c.COLUMN_NAME, c.DATA_TYPE "
	  . "\n FROM INFORMATION_SCHEMA.COLUMNS AS c, INFORMATION_SCHEMA.TABLES AS t "
	  . "\n WHERE t.TABLE_NAME = c.TABLE_NAME AND t.TABLE_TYPE = 'BASE TABLE' AND c.TABLE_SCHEMA = '".DB_DATABASE."' "
	  . "\n AND c.TABLE_NAME <> 'extrato_view' AND c.TABLE_NAME <> 'configuracao' AND c.COLUMN_NAME <> 'usuario' AND c.TABLE_NAME <> 'usuario' AND c.DATA_TYPE = 'varchar';";
    $retorno_row = $db->fetch_all($sql);
	$tabela = '';
	$update = '';
	$t = 0; 
	$i = 0; 
	echo '<br><strong> >> TOTAL DE COLUNAS: '.count($retorno_row).'</strong><br><br>';
	if($retorno_row) {
		foreach ($retorno_row as $exrow){
			$table_name = $exrow->TABLE_NAME;
			$column_name = $exrow->COLUMN_NAME;
			if($tabela <> $table_name) {
				$update = rtrim($update, ', ') . ' WHERE 1;';
				if (isset($_GET['debug']))					
					echo '<br/>'.$update.'<br>';
				if($t > 0)
					$db->query($update);
					
				$tabela = $table_name;
				$update = "UPDATE IGNORE `" . $tabela . "` SET ";
				$t++;
				$i = 0;
			}
			$update .= "`$column_name` = remove_acento(`$column_name`), ";
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
<meta name="keywords" content="Vale Telecom, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis, telecom" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Vale Telecom"/>

<!-- Title -->
<title><?php echo $core->empresa;?></title>

<!-- Favicons -->
<link rel="shortcut icon" href="../assets/img/favicon.png">
<link rel="apple-touch-icon" href="../assets/img/favicon_60x60.png">
<link rel="apple-touch-icon" sizes="76x76" href="../assets/img/favicon_76x76.png">
<link rel="apple-touch-icon" sizes="120x120" href="../assets/img/favicon_120x120.png">
<link rel="apple-touch-icon" sizes="152x152" href="../assets/img/favicon_152x152.png">
</head>
<body>
</body>
</html>