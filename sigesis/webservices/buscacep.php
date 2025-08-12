<?php

  /**
   * Buscar CEP - Banco SIGE
   *
   */
	
	$db = new mysqli('162.241.104.111', 'sigesisc_basecep' ,'j3,=VL4R{wJ!', 'sigesisc_basecep');
	$c = '';
	$naoencontrado = true;
	if(!$db) {
		echo 'ERRO: Nao foi possível conectar no banco de dados.';
	} else {
		if(isset($_POST['cep'])) {
			$c = $_POST['cep'];
		} elseif(isset($_GET['cep'])) {
			$c = $_GET['cep'];
		}
		if($c) {
			$db->query('SET NAMES utf8');
			$sql = "SELECT e.logradouro, e.endereco, e.bairro, e.cidade, e.uf, e.cep " 
			. "\n FROM endereco_completo as e"
			. "\n WHERE e.cep = '$c' ";
			$row = $db->query($sql);
			if($row) {
				while ($row_cep = $row ->fetch_object()) {
					$naoencontrado = false;
					$retorno = array(
						"retorno" => 1,
						"logradouro" => sanitize($row_cep->logradouro),
						"endereco" => sanitize($row_cep->endereco),
						"bairro" => sanitize($row_cep->bairro),
						"cidade" => sanitize($row_cep->cidade),
						"uf" => sanitize($row_cep->uf),
						"cep" => sanitize($row_cep->cep)
					);
				}
			}
			if($naoencontrado) {
				$retorno = array(
					"retorno" => 0,
					"endereco" => "NAO ENCONTRADO"
				);
			}
		} else {
			$retorno = array(
				"retorno" => 0,
				"endereco" => "CEP NAO INFORMADO"
			);
		}
		echo json_encode($retorno);
	}
	$db->close();	
	
	function sanitize($string, $trim = false, $int = false, $str = false)
	{
		$string = filter_var($string, FILTER_SANITIZE_STRING);		
		$string = str_replace('"', '', $string);
		$string = trim($string);
		$string = stripslashes($string);
		$string = strip_tags($string);
      
		if ($trim)
          $string = substr($string, 0, $trim);
		if ($int)
		  $string = preg_replace("/[^0-9\s]/", "", $string);
		if ($str)
		  $string = preg_replace("/[^a-zA-Z\s]/", "", $string);
		  
		$array1 = array( "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" ); 
		$array2 = array( "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c", "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" ); 
	
		$string = str_replace( $array1, $array2, $string);
	  
		return strtoupper ($string); 
	}
?>