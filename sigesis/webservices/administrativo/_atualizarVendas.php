<?php

  /**
   * Retorna o total de NF-e e NFC-e emitidas em um determinado mês
   *
   * @package Sigesis - Sistemas
   */
	define("_VALID_PHP", true);
	require('../../init.php');
	
	$sql_vendas = "SELECT * FROM vendas where inativo = 0 AND fiscal = 0 AND id > 2100";
	$row_vendas = $db->fetch_all($sql_vendas);
	if ($row_vendas) {
		foreach($row_vendas as $rvendas) {
			$sql_geral = "SELECT v.id as venda, cv.id as cadastro_vendas, SUM(cv.valor_total), " 
			         ."\n SUM(round((cv.quantidade*cv.valor),2)) as valor_real "
                     ."\n FROM vendas as v "
                     ."\n LEFT JOIN cadastro_vendas as cv ON cv.id_venda = v.id "
                     ."\n WHERE v.id = $rvendas->id AND cv.inativo = 0"
					 ."\n GROUP BY v.id";
			$row_geral = $db->fetch_all($sql_geral);		 
			
			if ($row_geral){
				foreach($row_geral as $vendas) {
					if (($vendas->quantidade*$vendas->valor_unitario) != $vendas->valor_real) {
						echo "[ $vendas->venda ][ ".$vendas->quantidade*$vendas->valor_unitario." ][ $vendas->valor_real ]<br>";
					}
				}
			}
		}
	}
?>