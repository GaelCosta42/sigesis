<?php
  /**
   * Logout
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  define("_VALID_PHP", true);
  
  require_once("init.php");
?>
<?php
  if ($usuario->logged_in)
      $usuario->logout();
	  
  redirect_to("login.php");
?>