<?php
  /**
   * Classe Grupo
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Grupo
  {
      const uTable = "grupo";
      public $did = 0;
      public $grupoid = 0;
      private static $db;

      /**
       * Grupo::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
       * Grupo::getGrupos()
       * 
       * @return
       */
      public function getGrupos()
      {
		  $sql = "SELECT id, grupo FROM grupo WHERE inativo = 0 ORDER BY grupo";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Grupo::processarGrupo()
       * 
       * @return
       */
      public function processarGrupo()
      {
		  if (empty($_POST['nome']))
              Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');	  

          if (empty(Filter::$msgs)) {

              $data = array(
					'grupo' => sanitize($_POST['nome'])
			  );
              
              (Filter::$id) ? self::$db->update(self::uTable, $data, "id=" . Filter::$id) : self::$db->insert(self::uTable, $data);
              $message = (Filter::$id) ? lang('GRUPO_ALTERADO_OK') : lang('GRUPO_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=grupo&acao=listar");   
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
  }
?>