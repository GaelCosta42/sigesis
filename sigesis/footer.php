<?php
  /**
   * Footer
   *
   */
  
  if (!defined("_VALID_PHP"))
      die('O acesso direto a está página não é permitido');
?>

<?php if(Filter::$acao != "novavenda"): ?>
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="container">
		<div class="pull-right">
			<img src="https://controle.sigesis.com.br/assets/img/sigesis.png" alt="">&bull; <?php echo date('Y');?> &bull; Desenvolvido por <a href="https://sigesistema.com.br/" target="_blank">SIGESIS</a>
		</div>
	</div>
</div>
<div class="scroll-to-top">
	<i class="icon-arrow-up"></i>
</div>
<?php endif; ?>
<!-- END FOOTER -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
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