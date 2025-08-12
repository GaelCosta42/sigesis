<?php
  /**
   * Classe Categoria
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Categoria
  {
      const uTable = "categoria";
      public $did = 0;
      public $categoriaid = 0;
      private static $db;

      /**
       * Categoria::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
	 * Categoria::getCategorias()
       * 
       * @return
       */
      public function getCategorias()
      {
		  $sql = "SELECT id, categoria FROM categoria WHERE inativo = 0 ORDER BY categoria";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Categoria::processarCategoria()
       * 
       * @return
       */
      public function processarCategoria()
      {
		  $id_categoria_pai = 0;

		  if (empty($_POST['nome']))
              Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');

          if (empty(Filter::$msgs)) {

              $data = array(
					'categoria' => sanitize($_POST['nome'])
			  );

              (Filter::$id) ? self::$db->update(self::uTable, $data, "id=" . Filter::$id) : self::$db->insert(self::uTable, $data);
              $message = (Filter::$id) ? lang('CATEGORIA_ALTERADO_OK') : lang('CATEGORIA_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=categoria&acao=listar");   
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
  }
?>