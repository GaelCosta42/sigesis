<?php
  /**
   * Classe SalarioMinimo
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class SalarioMinimo
  {
      const uTable = "salario_minimo";
      public $did = 0;
      private static $db;

      /**
       * SalarioMinimo::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
       * SalarioMinimo::getSalarioMinimos()
       * 
       * @return
       */
      public function getSalarioMinimo()
      {
		  $sql = "SELECT * FROM " . self::uTable;
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * SalarioMinimo::processarSalarioMinimo()
       * 
       * @return
       */
      public function processarSalarioMinimo()
      {
		  if (empty($_POST['salario']))
              Filter::$msgs['salario'] = lang('MSG_ERRO_VALOR');

		  if (empty($_POST['ano']))
              Filter::$msgs['ano'] = lang('MSG_ERRO_ANO');		  

          if (empty(Filter::$msgs)) {

              $data = array(
					'salario' => converteMoeda($_POST['salario']), 
					'ano' => sanitize($_POST['ano']), 
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
			  );

              (Filter::$id) ? self::$db->update(self::uTable, $data, "id=" . Filter::$id) : self::$db->insert(self::uTable, $data);
              $message = (Filter::$id) ? lang('SALARIO_ALTERADO_OK') : lang('SALARIO_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=salario&acao=listar");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
  }
?>