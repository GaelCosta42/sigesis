<!doctype html>
<html lang="pt-BR">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/img/favicon.ico"/>

  <title>Teste</title>
</head>
<body>
<?php
	
	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	define("_VALID_PHP", true);
	// require('../init.php');	
	echo ' >> INICIO - '.date('d/m/Y H:i:s').'<br>';
	
	$db = new mysqli('162.144.158.160', 'adminerp' ,'ex0Tr8!8', 'admin_erp');
	if(!$db) {
		echo 'ERRO: Não foi possível conectar no banco de dados.';
	} else {
		$db->query('SET NAMES utf8');
		$query = $db->query("SELECT * FROM usuario");
		if($query) {
			while ($result = $query ->fetch_object()) {
				$usuario = $result->usuario;	
				echo 'USUARIO >> '.$usuario;				
				echo '<br>';				
			}
		} else {
			echo 'ERRO: Existe um problema no select.';
		}		
	}
	echo ' >> FIM - '.date('d/m/Y H:i:s').'<br>';
	echo 'PROCESSAMENTO ENCERRADO.';
?>
</body>
</html>