<?php
  /**
   * Footer
   *
   */
  
  if (!defined("_VALID_PHP"))
      die('O acesso direto a está página não é permitido');
?>

<script src="./assets/scripts/metronic.js" type="text/javascript"></script>
<script src="./assets/scripts/layout.js" type="text/javascript"></script>
<script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
});
</script>
</body>
<!-- END BODY -->
</html>