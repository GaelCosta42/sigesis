<?php
  /**
   * Webservices: Login
   *
   */

	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 6000);
	ini_set('fastcgi_read_timeout', 6000);
	ini_set('default_socket_timeout', 9000);	
	define('_VALID_PHP', true);
	require('../init.php');

	$pin = (get('pin')) ? get('pin') : post('pin');
	if ($pin != "") {
		$result = $usuario->login(null, null, $pin);
		if($result) {
			echo 1;
		} else {
			echo "";
		}
	} else {
		echo "";
	}

?>