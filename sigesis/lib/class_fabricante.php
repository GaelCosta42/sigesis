<?php
  /**
   * Classe Fabricante
   *
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

class Fabricante
{
      const uTable = "fabricante";
      public $did = 0;
      public $fabricanteid = 0;
      private static $db;

      /**
       * Fabricante::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
       * Fabricante::getFabricantees()
       * 
       * @return
       */
      public function getFabricantes()
      {
		  $sql = "SELECT id, fabricante, exibir_romaneio FROM fabricante WHERE inativo = 0 ORDER BY fabricante";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
    /**
     * Fabricante::processUser()
     * 
     * @return
     */
    public function processarFabricante()
    {
        if (empty($_POST['nome']))
            Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');	  

        if (empty(Filter::$msgs)) {
            $exibir = isset($_POST['exibir_romaneio']) ? 1 : 0;

            $data = array(
                'fabricante' => sanitize($_POST['nome']),
                'exibir_romaneio' => $exibir
            );

            (Filter::$id) ? self::$db->update(self::uTable, $data, "id=" . Filter::$id) : self::$db->insert(self::uTable, $data);
            $message = (Filter::$id) ? lang('FABRICANTE_ALTERADO_OK') : lang('FABRICANTE_ADICIONADO_OK');

            if (self::$db->affected()) {
                Filter::msgOk($message, "index.php?do=fabricante&acao=listar");  
            } else
                Filter::msgAlert(lang('NAOPROCESSADO'));
        } else
            print Filter::msgStatus();
    }
}
?>