<?php

  /**
   * Completar informacoes fiscais do produto na nota fiscal
   *
   */

	define("_VALID_PHP", true);
    require('../init.php');

	if (isset($_POST['id'])) {
		$id = $_POST['id'];
		$retorno_row = Core::getRowById("nota_fiscal_itens", $id);
		echo "$retorno_row->quantidade@$retorno_row->valor_unitario@$retorno_row->valor_desconto@$retorno_row->outrasDespesasAcessorias@$retorno_row->cfop@$retorno_row->icms_cst@$retorno_row->ncm@$retorno_row->cest@$retorno_row->icms_percentual@$retorno_row->icms_st_percentual@$retorno_row->pis_cst@$retorno_row->pis_percentual@$retorno_row->cofins_cst@$retorno_row->cofins_percentual@$retorno_row->ipi_cst@$retorno_row->ipi_percentual@$retorno_row->icms_percentual_mva_st";
	} else {
		echo "O produto nao foi encontrado.";
	}

?>