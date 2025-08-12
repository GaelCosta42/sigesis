<?php 
  /**
   * Configuracao
   *
   * @package Grupo Vale Telecom
   * @copyright 2020
   * @version 2
   */
 
	 if (!defined("_VALID_PHP")) 
     die('Acesso direto a esta classe nao e permitido.');
 
	/** 
	* Database Constants - these constants refer to 
	* the database configuration settings. 
	*/
	 
	 define('DB_SERVER', 'locahost'); 
	 define('DB_USER', 'root');
	 define('DB_PASS', ''); 
	 define('DB_DATABASE', 'sigesis');
 
	/** 
	* Show MySql Errors. 
	* Not recomended for live site. true/false 
	*/
	 define('DEBUG', true);
?>