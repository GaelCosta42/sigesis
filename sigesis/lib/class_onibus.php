<?php
  /**
   * Classe Onibus
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Onibus
  {
      const uTable = "onibus";
      public $did = 0;
      public $onibusid = 0;
      private static $db;

      /**
       * Onibus::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
       * Onibus::getOnibuss()
       * 
       * @return
       */
      public function getOnibus()
      {
		  $sql = "SELECT * FROM " . self::uTable;
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Onibus::processarOnibus()
       * 
       * @return
       */
      public function processarOnibus()
      {
		  if (empty($_POST['linha']))
              Filter::$msgs['linha'] = lang('MSG_ERRO_NOME');	  

          if (empty(Filter::$msgs)) {

              $data = array(
					'linha' => sanitize($_POST['linha']), 
					'valor' => converteMoeda($_POST['valor']),
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
			  );

              (Filter::$id) ? self::$db->update(self::uTable, $data, "id=" . Filter::$id) : self::$db->insert(self::uTable, $data);
              $message = (Filter::$id) ? lang('ONIBUS_ALTERADO_OK') : lang('ONIBUS_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=onibus&acao=listar");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * Onibus::getUusarioOnibus()
       * 
       * @return
       */
      public function getUusarioOnibus($id_usuario = false)
      {
		  $where = ($id_usuario) ? " WHERE id_usuario = $id_usuario " : "";
		  $sql = "SELECT o.linha, o.valor, u.id_usuario, u.id, u.id_onibus FROM usuario_onibus AS u LEFT JOIN onibus AS o ON o.id = u.id_onibus $where";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Onibus::processarUsuarioOnibus()
       * 
       * @return
       */
      public function processarUsuarioOnibus()
      {
		  if (empty($_POST['id_onibus']))
              Filter::$msgs['id_onibus'] = lang('MSG_PADRAO');	 
			  
          if (empty(Filter::$msgs)) {

              $data = array(
					'id_usuario' => sanitize($_POST['id_usuario']),
					'id_onibus' => sanitize($_POST['id_onibus'])
			  );

              self::$db->insert("usuario_onibus", $data);
              $message = lang('ONIBUS_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=usuario&acao=editar&id=".$_POST['id_usuario']);         
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
  }
?>