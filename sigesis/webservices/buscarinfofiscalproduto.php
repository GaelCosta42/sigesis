<?php

  /**
   * Completar informacoes fiscais do produto na nota fiscal
   *
   */

	define("_VALID_PHP", true);
    require('../init.php');

	if (isset($_POST['id_produto'])) {
		$id_produto = $_POST['id_produto'];
		$retorno_row = Core::getRowById("produto", $id_produto);
		echo "$retorno_row->cfop@$retorno_row->icms_cst@$retorno_row->ncm@$retorno_row->cest@$retorno_row->icms_percentual@$retorno_row->icms_percentual_st@$retorno_row->mva_percentual@$retorno_row->pis_cst@$retorno_row->pis_aliquota@$retorno_row->cofins_cst@$retorno_row->cofins_aliquota@$retorno_row->ipi_saida_codigo@$retorno_row->ipi_cst";
	} else {
		echo "O produto nao foi encontrado.";
	}

?>