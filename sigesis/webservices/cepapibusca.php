<?php
	
	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	define("_VALID_PHP", true);
	require('../init.php');	
	
	$cep = post('cep');
	$geocode = file_get_contents('http://cep.republicavirtual.com.br/web_cep.php?formato=json&cep='.$cep);				
	$output= json_decode($geocode);

	$tipo_logradouro = (isset($output->tipo_logradouro)) ? $output->tipo_logradouro : "";
    $logradouro = (isset($output->logradouro)) ? $output->logradouro : "";
	$bairro = (isset($output->bairro) && !empty($output->bairro)) ? $output->bairro : "";
    $cidade = (isset($output->cidade)) ? $output->cidade : "";
    $estado = (isset($output->uf)) ? $output->uf : "";

	$data = array(
		'retorno' => 1,
		'endereco' => ($output->resultado == 1) ? sanitize($tipo_logradouro. " " .$logradouro) : "",
		'bairro' => ($output->resultado == 1) ? sanitize($bairro) : "",
		'cidade' => sanitize($cidade), 
		'estado' => sanitize($estado),
		'cep' => sanitize($cep),
	);
	if(strlen($cidade) > 1) {
		echo json_encode($data);
	} else {
		echo "Nao encontrado!<br>";
	}

?>