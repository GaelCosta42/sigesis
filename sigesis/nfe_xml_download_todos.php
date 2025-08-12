<script src="./assets/plugins/jquery.min.js" type="text/javascript"></script>
<?php
 /**
   * PDF - Nota Fiscal Todos - Download
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */ 
	define('_VALID_PHP', true);
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	
	require('enotas/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;
	
	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));	
	
	$mes_ano = (get('mes_ano')) ? get('mes_ano') : date('m/Y'); 
	$id_empresa = get('id_empresa'); 
	$id_enotas = getValue('enotas', 'empresa', 'id="'.$id_empresa.'"');
	
	try
	{
		$retorno_row = $produto->getNotaFiscalChaveAcesso($mes_ano, $id_empresa);
		if($retorno_row) {
			foreach ($retorno_row as $exrow) {
				$id_nota = $exrow->id;
				if($exrow->modelo == 6):
				echo "NFC: ".$exrow->numero_nota."<br/>";
?>
<script type="text/javascript"> 
	  
	$(document).ready(function () {
		window.open("nfc_xml.php?id=<?php echo $id_nota;?>","_blank");
	});
	
</script>
<?php 			else: 
				echo "NF-e: ".$exrow->numero_nota."<br/>";
?>
<script type="text/javascript"> 
	  
	$(document).ready(function () {
		window.open("nfe_xml.php?id=<?php echo $id_nota;?>","_blank");
	});
	
</script>
<?php 			endif;
			}
			$contar = count($retorno_row);
			echo "Quantidade de notas fiscais encontradas para o mês $mes_ano: $contar</br></br>";
		} else {
			echo "Nenhuma nota fiscal encontrada para o mês: $mes_ano</br></br>";
		}
	}
	catch(Exception $e) {
		echo 'Erro na requisição do código: </br></br>';
		echo 'Message: ' . $e->getMessage();
	}
?>