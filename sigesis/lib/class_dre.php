<?php
  /**
   * Classe DRE
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class DRE
  {
	  private static $db;

      /**
       * DRE::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }
	  
	  /**
       * DRE::getListaAno()
       * 
       * @return
       */
      public function getListaAno($tabela = "dre", $campo = "pagamento")
      {		  
		  $sql = "SELECT DATE_FORMAT($campo, '%Y') as mes_ano" 
		  . "\n FROM $tabela" 
		  . "\n WHERE DATE_FORMAT($campo, '%Y') <> '0000'"
		  . "\n GROUP BY mes_ano"
		  . "\n ORDER BY $campo ASC";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * DRE::getListaMes()
       * 
       * @return
       */
      public function getListaMes($tabela = "dre", $campo = "pagamento")
      {		  
		  $sql = "SELECT DATE_FORMAT($campo, '%m/%Y') as mes_ano" 
		  . "\n FROM $tabela" 
		  . "\n WHERE DATE_FORMAT($campo, '%m/%Y') <> '00/0000'"
		  . "\n GROUP BY mes_ano"
		  . "\n ORDER BY $campo DESC";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * DRE::getDespesas()
       * 
       * @return
       */
      public function getDespesas($mes_ano, $tipo = false)
      {		  
		  $wtipo = ($tipo) ? "AND plano_conta = '$tipo'" : "";
		  $sql = "SELECT sum(valor) as valor" 
		  . "\n FROM dre" 
		  . "\n WHERE DATE_FORMAT(pagamento, '%m/%Y') = '$mes_ano' AND tipo = 'Despesa' $wtipo ";
		  $row = self::$db->first($sql);
		  
          return ($row) ? $row->valor : 0;
      }
	  
	  /**
       * DRE::tipoDespesas()
       * 
       * @return
       */
      public function tipoDespesas_nivel3($ano = false, $mes = false)
      {		  
		  $wano = ($ano) ? "AND year(pagamento) = '$ano'" : "";
		  $wmes = ($mes) ? "AND DATE_FORMAT(pagamento, '%m/%Y') = '$mes'" : "";
		  $sql = "SELECT plano_conta, sum(valor) as valor" 
		  . "\n FROM dre"
		  . "\n WHERE tipo = 'Despesa' $wano $wmes "
		  . "\n GROUP BY plano_conta";
		  $row = self::$db->fetch_all($sql);
		  
          return ($row) ? $row : 0;
      }
	  
	  /**
       * DRE::tipoDespesas()
       * 
       * @return
       */
      public function tipoDespesas($ano = false, $mes = false)
      {		  
		  $wano = ($ano) ? "AND year(d.pagamento) = '$ano'" : "";
		  $wmes = ($mes) ? "AND DATE_FORMAT(d.pagamento, '%m/%Y') = '$mes'" : "";
		  $sql = "SELECT n.descricao as plano_conta, sum(d.valor) as valor" 
		  . "\n FROM dre as d"
		  . "\n LEFT JOIN plano_contas as p ON p.nivel3 = d.id_plano"
		  . "\n LEFT JOIN plano_contas as n ON p.nivel1 = n.nivel1 AND n.nivel2 = p.nivel2 AND n.nivel3 = 0"
		  . "\n WHERE d.tipo = 'Despesa' $wano $wmes "
		  . "\n GROUP BY n.descricao";
		  $row = self::$db->fetch_all($sql);
		  
          return ($row) ? $row : 0;
      }
	  
	  /**
       * DRE::getReceitas()
       * 
       * @return
       */
      public function getReceitas($mes_ano, $tipo = false)
      {		  
		  $wtipo = ($tipo) ? "AND plano_conta = '$tipo'" : "";
		  $sql = "SELECT sum(valor) as valor" 
		  . "\n FROM dre" 
		  . "\n WHERE DATE_FORMAT(pagamento, '%m/%Y') = '$mes_ano' AND tipo = 'Receita' $wtipo ";
		  $row = self::$db->first($sql);
		  
          return ($row) ? $row->valor : 0;
      }
	  
	  /**
       * DRE::tipoReceitas()
       * 
       * @return
       */
      public function tipoReceitas($ano = false, $mes = false)
      {		  
		  $wano = ($ano) ? "AND year(pagamento) = '$ano'" : "";
		  $wmes = ($mes) ? "AND DATE_FORMAT(pagamento, '%m/%Y') = '$mes'" : "";
		  $sql = "SELECT plano_conta, sum(valor) as valor" 
		  . "\n FROM dre"
		  . "\n WHERE tipo = 'Receita' $wano $wmes "
		  . "\n GROUP BY plano_conta";
		  $row = self::$db->fetch_all($sql);
		  
          return ($row) ? $row : 0;
      }
	  
	  /**
       * DRE::selReceita()
       * 
       * @return
       */
      public function selReceita()
      {	
		  $sql = "SELECT plano_conta" 
		  . "\n FROM dre"
		  . "\n WHERE tipo = 'Receita' "
		  . "\n GROUP BY plano_conta"
		  . "\n ORDER BY plano_conta";
		  $row = self::$db->fetch_all($sql);
		  
          return ($row) ? $row : 0;
      }
	  
	  /**
       * DRE::selReceita()
       * 
       * @return
       */
      public function selDespesa()
      {	
		  $sql = "SELECT plano_conta" 
		  . "\n FROM dre"
		  . "\n WHERE tipo = 'Despesa' "
		  . "\n GROUP BY plano_conta"
		  . "\n ORDER BY plano_conta";
		  $row = self::$db->fetch_all($sql);
		  
          return ($row) ? $row : 0;
      }
	  
	  /**
       * DRE::getDespesas()
       * 
       * @return
       */
      public function getFluxoCaixa($mes_ano)
      {
		  $sql = "SELECT sum(valor) as valor" 
		  . "\n FROM dre" 
		  . "\n WHERE DATE_FORMAT(pagamento, '%m/%Y') = '$mes_ano' AND tipo = 'Despesa' ";
		  $row = self::$db->first($sql);
		  $despesa = ($row) ? $row->valor : 0;
		  
		  $sql = "SELECT sum(valor) as valor" 
		  . "\n FROM dre" 
		  . "\n WHERE DATE_FORMAT(pagamento, '%m/%Y') = '$mes_ano' AND tipo = 'Receita' ";
		  $row = self::$db->first($sql);
		  $receita = ($row) ? $row->valor : 0;
		  
          return ($receita-$despesa);
      }
	  
  }
?>