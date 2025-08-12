<?php
	
set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	define("_VALID_PHP", true);
	require('../init.php');	
	echo ' >> INICIO - '.date('d/m/Y H:i:s').'<br>';
	$ponteiro = fopen ("cliente-fornecedor.ttx", "r");
	if ($ponteiro == false) die("Erro no arquivo.");
	$i = 0;
	while (!feof ($ponteiro)) {
		$i++;
		$linha = fgets($ponteiro, 4096);
		$linha = str_replace("\"","",$linha);
		$campos = explode(";",$linha);
		$nome = explode('-', $campos[0]);
		$cont = count($nome);
		if ($cont == 2) {
			$numero = $nome[0];
			$razao = $nome[1];
			$tipo = $campos[1];
			$pessoa = $campos[2];
			$sql = "SELECT id FROM cadastro WHERE cpf_cnpj = '".$numero."'";
			$row = $db->first($sql);
			if($row) {
				$nome = sanitize($razao);
				$data = array(
					'nome' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
					'razao_social' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
					'telefone' => $campos[3],
					'usuario' => "admin", 
					'data' => "NOW()"
				);	
				if($tipo == "Cli") {
					$data['cliente'] = '1'; 
				} else {
					$data['fornecedor'] = '1'; 				
				}	
				if(substr_count($pessoa, "Jur")) {
					$data['tipo'] = '1'; 
				} else {
					$data['tipo'] = '2'; 				
				}
				$db->update("cadastro", $data, "id=".$row->id);
				if($db->affected()) {
					echo $i.' >> OK.<br>';
				} else {
					echo 'ERRO: Existe um problema no select.<br>';
				}
			}
		}
	}	
	fclose ($ponteiro);
	echo ' >> FIM - '.date('d/m/Y H:i:s').'<br>';
	echo 'PROCESSAMENTO ENCERRADO.';
	echo '<br> >> FINAL - '.date('d/m/Y H:i:s').'<br>';
?>