<?php

  /**
   * Buscar Ultimo login
   *
   */
	define("_VALID_PHP", true);
	require('../init.php');		
	
	$sql = "SELECT u.nome, u.usuario, u.lastlogin FROM usuario AS u, (SELECT MAX(lastlogin) AS lastlogin FROM usuario WHERE nivel <> 9) AS l WHERE l.lastlogin = u.lastlogin";
	$retorno_row = $db->first($sql);
	echo ($retorno_row) ? strtoupper($retorno_row->usuario)."#".exibedata($retorno_row->lastlogin) : "0#0";
?>