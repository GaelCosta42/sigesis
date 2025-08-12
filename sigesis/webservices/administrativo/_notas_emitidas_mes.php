<?php

  /**
   * Retorna o total de NF-e e NFC-e emitidas em um determinado mês
   *
   * @package Sigesis - Sistemas
   */
	define("_VALID_PHP", true);
	require('../../init.php');
	
			$datainicial = $_GET['di'];
			$datafinal = $_GET['df'];
			
			if(!empty($datainicial) AND $datainicial!='0000-00-00' AND !empty($datafinal) AND $datafinal!='0000-00-00'  ) {
				$datainicial .= ' 00:00:00';
				$datafinal .= ' 23:59:59';
				$sql_nfc = "SELECT count(id) as total FROM vendas WHERE data_emissao>='$datainicial' and data_emissao<='$datafinal' AND fiscal = 1";
				$retorno_nfc = $db->first($sql_nfc);
				$sql_nfe = "SELECT count(id) as total FROM nota_fiscal WHERE data_emissao>='$datainicial' and data_emissao<='$datafinal' AND fiscal = 1 AND modelo=2 AND operacao=2";
				$retorno_nfe = $db->first($sql_nfe);
				
				$qtde_nfc = ($retorno_nfc) ? intval($retorno_nfc->total) : 0;
				$qtde_nfe = ($retorno_nfe) ? intval($retorno_nfe->total) : 0;
				
				echo ($qtde_nfc + $qtde_nfe);
			} else {
				echo "00";
			}
?>