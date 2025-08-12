<?php
  /**
   * Classe Descricao
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Descricao
  {
      const uTable = "salario_descricao";
      public $did = 0;
      public $salario_descricaoid = 0;
      private static $db;

      /**
       * Descricao::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
       * Descricao::getDescricaos()
       * 
       * @return
       */
      public function getDescricao($tipo = false)
      {
		  $where = ($tipo) ? "WHERE tipo = $tipo " : "";
		  $sql = "SELECT * FROM " . self::uTable
				 ." $where ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Descricao::processarDescricao()
       * 
       * @return
       */
      public function processarDescricao()
      {
		  if (empty($_POST['descricao']))
              Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');	  

          if (empty(Filter::$msgs)) {

              $data = array(
					'descricao' => sanitize($_POST['descricao']), 
					'codigo' => sanitize($_POST['codigo']), 
					'tipo' => sanitize($_POST['tipo']), 
					'inss' => post('inss'), 
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
			  );

              (Filter::$id) ? self::$db->update(self::uTable, $data, "id=" . Filter::$id) : self::$db->insert(self::uTable, $data);
              $message = (Filter::$id) ? lang('DESCRICAO_ALTERADO_OK') : lang('DESCRICAO_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=descricao&acao=listar");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
  }
?>