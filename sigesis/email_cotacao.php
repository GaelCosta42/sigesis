<?php
  /**
   * Enviar cotação por e-mail
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  define("_VALID_PHP", true);
  
	require_once("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
  
	$id_cotacao = get('id');
	$id_cadastro = get('id_cadastro');
	$co = getValue("codigo", "fornecedor", "id_cadastro='".$id_cadastro."'");
	$row_cotacao = Core::getRowById("cotacao", $id_cotacao);
	$cc = $row_cotacao->codigo;
	$row_empresa = Core::getRowById("empresa", 1);
	$row_produtos = $cotacao->getCotacaoItens($id_cotacao, $id_cadastro);
	$dataenvio = date('d/m/Y');
	$row_config = Core::configuracoes();
	$site_url = $row_config->site_url;
	$site_sistema = $row_config->site_sistema;
	$site_email = $row_config->site_email;
	$site_empresa = $row_config->empresa;
	ob_start();
	require_once (BASEPATH . 'email/cotacao.tpl.php');
	$html_message = ob_get_contents();
	ob_end_clean();
	echo $html_message;
	
?>