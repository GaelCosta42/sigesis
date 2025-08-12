<?php
  /**
   * Classe Gestao
   *
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Gestao
  {
	  private static $db;

      /**
       * Gestao::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }
	  
	  /**
       * Gestao::getListaAno()
       * 
       * @return
       */
      public function getListaAno($tabela, $campo = "data", $ordem = "ASC", $todos = false)
      {		  
		  $wtodos = ($todos) ? "" : " AND $campo < CURDATE() ";
		 $sql = "SELECT DATE_FORMAT($campo, '%Y') as mes_ano" 
		  . "\n FROM $tabela" 
		  . "\n WHERE DATE_FORMAT($campo, '%Y') <> '0000' $wtodos "
		  . "\n GROUP BY mes_ano"
		  . "\n ORDER BY $campo $ordem ";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Gestao::getListaAnoTodos()
       * 
       * @return
       */
      public function getListaAnoTodos($tabela, $campo = "data", $ordem = "ASC")
      {	
		 $sql = "SELECT DATE_FORMAT($campo, '%Y') as mes_ano" 
		  . "\n FROM $tabela" 
		  . "\n WHERE $campo < DATE_ADD(CURDATE(), INTERVAL 10 DAY) AND DATE_FORMAT($campo, '%Y') <> '0000' "
		  . "\n GROUP BY mes_ano"
		  . "\n ORDER BY $campo $ordem ";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Gestao::getListaMes()
       * 
       * @return
       */
      public function getListaMes($tabela, $campo = "data", $where = "", $ordem = "ASC")
      {		  
		  $sql = "SELECT DATE_FORMAT($campo, '%m/%Y') as mes_ano" 
		  . "\n FROM $tabela" 
		  . "\n WHERE $campo < DATE_ADD(CURDATE(), INTERVAL 10 DAY) AND DATE_FORMAT($campo, '%m/%Y') <> '00/0000' $where "
		  . "\n GROUP BY mes_ano"
		  . "\n ORDER BY $campo $ordem ";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Gestao::getEnderecos()
       * 
       * @return
       */
      public function getEnderecos()
      {	
		 $sql = "SELECT id, endereco, bairro, cidade, estado, cep " 
		  . "\n FROM cadastro" 
		  . "\n WHERE mapa = 0 limit 500";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Gestao::getCidades()
       * 
       * @return
       */
      public function getCidades()
      {	
		 $sql = "SELECT id, endereco, bairro, cidade, estado, cep " 
		  . "\n FROM cadastro " 
		  . "\n WHERE inativo = 0 AND cidade = '' and cep <> '' ";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Gestao::getMapa()
       * 
       * @return
       */
      public function getMapa()
      {	
		 $sql = "SELECT id, nome, lat, lng " 
		  . "\n FROM cadastro" 
		  . "\n WHERE mapa = 1";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Gestao::getMeta()
       * 
       * @return
       */
      public function getMeta($mes_ano)
      {	
		 $sql = "SELECT m.faturamento" 
		  . "\n FROM meta m" 
		  . "\n WHERE DATE_FORMAT(m.mes_ano, '%m/%Y') = '$mes_ano' "
		  . "\n ORDER BY m.mes_ano DESC ";
		  $row = self::$db->first($sql);

          return ($row) ? $row->faturamento : 0;
      }
	  	  
	  /**
       * Gestao::getArquivos()
       * 
       * @return
       */
      public function getArquivos($tabela)
      {	
		 $sql = "SELECT turma, usuario, data, count(1) as quant  " 
		  . "\n FROM $tabela " 
		  . "\n ORDER BY id DESC";
		  $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Gestao::getTabelas()
       * 
       * @return
       */
      public function getTabelas($acao, $parametro = false)
      {	
		 $wparametro = ($parametro) ? " AND parametro = '$parametro'" : "";	
		 $sql = "SELECT acao, tabela, categoria, serie1, serie2, serie3, serie4, serie5, serie6 " 
		  . "\n FROM tabelas " 
		  . "\n WHERE acao = '$acao' $wparametro " ;
		  $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }
	  /**
       * Gestao::getFaturado()
	   *
       * @return
       */
	  public function getFaturado($mes_ano = 0)
      {
		 $sql = "SELECT SUM(r.valor_pago) AS total " 
		  . "\n FROM receita as r " 
		  . "\n LEFT JOIN conta as c ON c.id = r.id_conta " 
		  . "\n WHERE inativo = 0 AND pago = 1 AND c.exibir = 1 AND c.dre = 1 AND r.tipo <> 3 AND DATE_FORMAT(r.data_pagamento, '%m/%Y') = '$mes_ano' ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Gestao::getRecebido()
	   *
       * @return
       */
	  public function getRecebido($mes_ano = 0)
      {
		 $sql = "SELECT SUM(r.valor_pago) AS total " 
		  . "\n FROM receita as r " 
		  . "\n LEFT JOIN conta as c ON c.id = r.id_conta " 
		  . "\n WHERE inativo = 0 AND pago = 1 AND c.exibir = 1 AND c.dre = 1 AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' ";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Gestao::getRecebido()
	   *
       * @return
       */
	  public function getReceber($mes_ano = 0)
      {
		 $sql = "SELECT SUM(r.valor_pago) AS total " 
		  . "\n FROM receita as r " 
		  . "\n LEFT JOIN conta as c ON c.id = r.id_conta " 
		  . "\n WHERE inativo = 0 AND pago = 0 AND c.exibir = 1 AND c.dre = 1 AND DATE_FORMAT(r.data_pagamento, '%m/%Y') = '$mes_ano' ";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Gestao::getReceitas()
       * 
       * @return
       */
      public function getReceitas($mes_ano = 0)
      {
		 $sql = "SELECT SUM(r.valor_pago) AS total " 
		  . "\n FROM receita as r " 
		  . "\n LEFT JOIN conta as c ON c.id = r.id_conta " 
		  . "\n WHERE inativo = 0 AND pago = 1 AND c.exibir = 1 AND c.dre = 1 AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' ";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      } 
	  
	  /**
       * Gestao::getDespesasPagas()
       * 
       * @return
       */
      public function getDespesasPagas($mes_ano = 0)
	  {
		 $sql = "SELECT SUM(d.valor) AS total " 
		  . "\n FROM despesa as d" 
		  . "\n LEFT JOIN conta as c ON c.id = d.id_conta " 
		  . "\n WHERE d.inativo = 0 AND d.pago = 1 AND c.exibir = 1 AND c.dre = 1 AND DATE_FORMAT(d.data_pagamento, '%m/%Y') = '$mes_ano' ";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      } 
	  /**
       * Gestao::getFaturadoDia()
	   *
       * @return
       */
	  public function getFaturadoDia($dia = 0)
      {
		 $sql = "SELECT SUM(r.valor) AS total " 
		  . "\n FROM receita as r " 
		  . "\n LEFT JOIN conta as c ON c.id = r.id_conta " 
		  . "\n WHERE inativo = 0 AND pago = 1 AND c.exibir = 1 AND c.dre = 1 AND DATE_FORMAT(r.data_pagamento, '%d/%m/%Y') = '$dia' ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Gestao::getReceitasDia()
       * 
       * @return
       */
      public function getReceitasDia($dia = 0)
      {
		 $sql = "SELECT SUM(r.valor_pago) AS total " 
		  . "\n FROM receita as r " 
		  . "\n LEFT JOIN conta as c ON c.id = r.id_conta " 
		  . "\n WHERE r.inativo = 0 AND r.pago = 1 AND c.exibir = 1 AND c.dre = 1 AND DATE_FORMAT(r.data_recebido, '%d/%m/%Y') = '$dia' ";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      } 
	  
	  /**
       * Gestao::getReceberDia()
       * 
       * @return
       */
      public function getReceberDia($dia = 0)
      {
		 $sql = "SELECT SUM(r.valor_pago) AS total " 
		  . "\n FROM receita as r " 
		  . "\n LEFT JOIN conta as c ON c.id = r.id_conta " 
		  . "\n WHERE r.inativo = 0 AND r.pago = 0 AND c.exibir = 1 AND c.dre = 1 AND DATE_FORMAT(r.data_pagamento, '%d/%m/%Y') = '$dia' ";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      } 
	  
	  /**
       * Gestao::getDespesasPagas()
       * 
       * @return
       */
      public function getDespesasPagasDia($dia = 0)
	  {
		 $sql = "SELECT SUM(valor) AS total " 
		  . "\n FROM despesa as d" 
		  . "\n LEFT JOIN conta as c ON c.id = d.id_conta " 
		  . "\n WHERE d.inativo = 0 AND d.pago = 1 AND c.exibir = 1 AND c.dre = 1 AND DATE_FORMAT(d.data_pagamento, '%d/%m/%Y') = '$dia' ";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      } 
	  
	  /**
       * Gestao::getDespesas()
       * 
       * @return
       */
      public function getDespesas($mes_ano = 0)
      {		  
		 $sql = "SELECT SUM(d.valor) AS total " 
		  . "\n FROM despesa as d" 
		  . "\n LEFT JOIN conta as c ON c.id = d.id_conta " 
		  . "\n WHERE d.inativo = 0 AND c.exibir = 1 AND c.dre = 1 AND DATE_FORMAT(d.data_vencimento, '%m/%Y') = '$mes_ano'";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      } 
	  
	  /**
       * Gestao::tipoDespesas()
       * 
       * @return
       */
      public function tipoDespesas($ano = false, $mes = false)
      {		  
		  $wano = ($ano) ? "AND year(data_pagamento) = '$ano'" : "";
		  $wmes = ($mes) ? "AND DATE_FORMAT(data_pagamento, '%m/%Y') = '$mes'" : "";
		  $sql = "SELECT pai.conta, SUM(valor_pago) as valor" 
		  . "\n FROM despesa as d" 
		  . "\n LEFT JOIN conta as c on c.id = d.id_conta" 
		  . "\n LEFT JOIN conta as pai on pai.id = c.id_pai" 
		  . "\n WHERE pago = 1 AND inativo = 0  $wano $wmes"
		  . "\n GROUP BY pai.conta";
		  $row = self::$db->fetch_all($sql);
		  
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Gestao::tipoReceitas()
       * 
       * @return
       */
      public function tipoReceitas($ano = false, $mes = false)
      {		  
		 $wano = ($ano) ? "AND year(data_recebido) = '$ano'" : "";
		 $wmes = ($mes) ? "AND DATE_FORMAT(data_recebido, '%m/%Y') = '$mes'" : "";
		 $sql = "SELECT t.tipo, SUM(r.valor_pago) AS valor " 
		  . "\n FROM receita as r " 
		  . "\n LEFT JOIN tipo_pagamento as t on t.id = r.tipo" 
		  . "\n WHERE r.inativo = 0 AND r.pago = 1 $wano $wmes"
		  . "\n GROUP BY t.tipo";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      } 
	  
	  /**
       * Gestao::tipoFaturamento()
       * 
       * @return
       */
      public function tipoFaturamento($ano = false, $mes = false)
      {		  
		 $wano = ($ano) ? "AND year(r.data_pagamento) = '$ano'" : "";
		 $wmes = ($mes) ? "AND DATE_FORMAT(r.data_pagamento, '%m/%Y') = '$mes'" : "";
		 $sql = "SELECT t.tipo, SUM(r.valor) AS valor " 
		  . "\n FROM receita as r " 
		  . "\n LEFT JOIN tipo_pagamento as t on t.id = r.tipo" 
		  . "\n WHERE r.inativo = 0 AND r.tipo > 0 AND r.pago = 1 $wano $wmes"
		  . "\n GROUP BY r.tipo";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      } 
	  
	  /**
       * Gestao::getPMV()
       * 
       * @return
       */
      public function getPMV()
      {
		$sql = "SELECT valor_pago, data_recebido, DATEDIFF(data_recebido, CURDATE()) as dias " 
			. "\n FROM receita "
			. "\n WHERE inativo = 0 AND data_recebido > CURDATE()";
		$row = self::$db->fetch_all($sql);
		$total = 0;
		$acumulado = 0;
		$dias = 0;
		if($row) {
			foreach ($row as $exrow) {
				$total += $exrow->valor_pago;
				$acumulado += $exrow->valor_pago * $exrow->dias;
			}
			unset($exrow);
		}
		//echo "[$total][$acumulado]";
		if($total > 0){
			$dias = round($acumulado / $total);
		}
		return $dias;
      }
	  
	  /**
       * Gestao::getAMR()
       * 
       * @return
       */
      public function getAMR()
      {
		$sql = "SELECT SUM(valor_pago) as total " 
			. "\n FROM receita "
			. "\n WHERE inativo = 0 AND data_recebido < CURDATE()";
		$row = self::$db->first($sql);
		$total = ($row) ? $row->total : 0;
		$sql = "SELECT valor_pago, data_pagamento, data_recebido, DATEDIFF(data_recebido, data_pagamento) as dias " 
			. "\n FROM receita "
			. "\n WHERE pago = 1 AND inativo = 0 AND data_recebido < CURDATE() AND data_pagamento < data_recebido";
		$row = self::$db->fetch_all($sql);
		$acumulado = 0;
		$dias = 0;
		if($row) {
			foreach ($row as $exrow) {
				$acumulado += $exrow->valor_pago * $exrow->dias;
			}
			unset($exrow);
		}
		if($total > 0){
			$dias = round($acumulado / $total);
		}
		return $dias;
      }
	  
	  /**
       * Gestao::getInfoDespesas()
       * @return
       */
      public function getInfoDespesas($dataini = 0, $datafim = 0)
      {	
		 $sql = "SELECT SUM(valor) AS valor" 
		  . "\n FROM despesa" 
		  . "\n WHERE pago = 1 AND inativo = 0 AND data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00','%d/%m/%Y %H:%i') AND STR_TO_DATE('$datafim 23:59','%d/%m/%Y %H:%i') ";
		  $row = self::$db->first($sql);

          return ($row) ? $row->valor : 0;
      }
	  
	  /**
       * Gestao::getInfoDespesasPagarTotal()
       * @return
       */
      public function getInfoDespesasPagarTotal($dataini = 0, $datafim = 0)
      {	
		 $sql = "SELECT SUM(valor) AS valor" 
		  . "\n FROM despesa" 
		  . "\n WHERE pago = 0 AND inativo = 0  ";
		  $row = self::$db->first($sql);

          return ($row) ? $row->valor : 0;
      }
	  
	  /**
       * Gestao::getInfoDespesasPagar()
       * @return
       */
      public function getInfoDespesasPagar($dataini = 0, $datafim = 0)
      {	
		 $sql = "SELECT SUM(valor) AS valor" 
		  . "\n FROM despesa" 
		  . "\n WHERE pago = 0 AND inativo = 0 AND data_vencimento BETWEEN STR_TO_DATE('$dataini 00:00','%d/%m/%Y %H:%i') AND STR_TO_DATE('$datafim 23:59','%d/%m/%Y %H:%i') ";
		  $row = self::$db->first($sql);

          return ($row) ? $row->valor : 0;
      }
	  
	  /**
       * Gestao::getInfoReceberTotal()
       * @return
       */
      public function getInfoReceberTotal($dataini = 0, $datafim = 0)
      {	
		 $sql = "SELECT SUM(valor) AS valor" 
		  . "\n FROM receita" 
		  . "\n WHERE pago = 0 AND inativo = 0 ";
		  $row = self::$db->first($sql);

          return ($row) ? $row->valor : 0;
      }
	  
	  /**
       * Gestao::getInfoReceber()
       * @return
       */
      public function getInfoReceber($dataini = 0, $datafim = 0)
      {	
		 $sql = "SELECT SUM(valor) AS valor" 
		  . "\n FROM receita" 
		  . "\n WHERE pago = 0 AND inativo = 0 AND data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00','%d/%m/%Y %H:%i') AND STR_TO_DATE('$datafim 23:59','%d/%m/%Y %H:%i') ";
		  $row = self::$db->first($sql);

          return ($row) ? $row->valor : 0;
      }
	  
	  /**
       * Gestao::getInfoRecebido()
       * @return
       */
      public function getInfoRecebido($dataini = 0, $datafim = 0)
      {	
		 $sql = "SELECT SUM(valor_pago) AS valor" 
		  . "\n FROM receita" 
		  . "\n WHERE pago = 1 AND inativo = 0 AND data_recebido BETWEEN STR_TO_DATE('$dataini 00:00','%d/%m/%Y %H:%i') AND STR_TO_DATE('$datafim 23:59','%d/%m/%Y %H:%i') ";
		  $row = self::$db->first($sql);

          return ($row) ? $row->valor : 0;
      }
	  
	  /**
       * Gestao::getInfoLigacaoes()
       * 
       * @return
       */
      public function getInfoLigacaoes($dataini = 0, $datafim = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE data BETWEEN STR_TO_DATE('$dataini 00:00','%d/%m/%Y %H:%i') AND STR_TO_DATE('$datafim 23:59','%d/%m/%Y %H:%i') ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoLigacaoesConsultor()
       * 
       * @return
       */
      public function getInfoLigacaoesConsultor($consultor = 0, $mes_ano = 0)
      {	
		 $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE usuario = '$consultor' AND DATE_FORMAT(data, '%d/%m/%Y') = '$mes_ano' ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoVisitas()
       * 
       * @return
       */
      public function getInfoVisitas($dataini = 0, $datafim = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE id_status = 5 AND data BETWEEN STR_TO_DATE('$dataini 00:00','%d/%m/%Y %H:%i') AND STR_TO_DATE('$datafim 23:59','%d/%m/%Y %H:%i') ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoVisitasConsultor()
       * 
       * @return
       */
      public function getInfoVisitasConsultor($consultor = 0, $mes_ano = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE id_status = 5 AND usuario = '$consultor' AND DATE_FORMAT(data, '%d/%m/%Y') = '$mes_ano' ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoAgendas()
       * 
       * @return
       */
      public function getInfoAgendas($dataini = 0, $datafim = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE id_status = 4 AND data BETWEEN STR_TO_DATE('$dataini 00:00','%d/%m/%Y %H:%i') AND STR_TO_DATE('$datafim 23:59','%d/%m/%Y %H:%i') ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoAgendasConsultor()
       * 
       * @return
       */
      public function getInfoAgendasConsultor($consultor = 0, $mes_ano = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE id_status = 4 AND usuario = '$consultor' AND DATE_FORMAT(data, '%d/%m/%Y') = '$mes_ano' ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoAgendasConsultor()
       * 
       * @return
       */
      public function getInfoAgendasProgramadaConsultor($consultor = 0, $mes_ano = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE id_status = 4 AND usuario = '$consultor' AND DATE_FORMAT(data_retorno, '%d/%m/%Y') = '$mes_ano' ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoModelos()
       * 
       * @return
       */
      public function getInfoModelos($dataini = 0, $datafim = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE id_status = 6 AND data BETWEEN STR_TO_DATE('$dataini 00:00','%d/%m/%Y %H:%i') AND STR_TO_DATE('$datafim 23:59','%d/%m/%Y %H:%i') ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoModelosConsultor()
       * 
       * @return
       */
      public function getInfoModelosConsultor($consultor = 0, $mes_ano = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE id_status = 6 AND usuario = '$consultor' AND DATE_FORMAT(data, '%d/%m/%Y') = '$mes_ano' ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoRetornoProgramado()
       * 
       * @return
       */
      public function getInfoRetornoProgramado($consultor = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE ativo = 1 AND usuario = '$consultor' AND data_retorno > CURDATE()";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoRetornoVazio()
       * 
       * @return
       */
      public function getInfoRetornoVazio($consultor = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE ativo = 1 AND usuario = '$consultor' AND DATE_FORMAT(data_retorno, '%m/%Y') = '00/0000' ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getInfoRetornoVazio()
       * 
       * @return
       */
      public function getInfoRetornoVencido($consultor = 0)
      {	
		  $sql = "SELECT COUNT(1) as quant" 
		  . "\n FROM cadastro_retorno" 
		  . "\n WHERE ativo = 1 AND usuario = '$consultor' AND DATE_FORMAT(data_retorno, '%m/%Y') <> '00/0000' AND data_retorno < CURDATE()";
		  $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }

      /**
       * Gestao::getInfoNovoContato()
       * 
       * @return
       */
      public function getInfoNovoContato($consultor = 0)
      {
         $sql = "SELECT COUNT(1) as quant"  
		  . "\n FROM cadastro"
		  . "\n WHERE usuario = '$consultor' AND id_status = 1 ";
          $row = self::$db->first($sql);
          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Gestao::getDREReceitaMensal()
       * 
       * @return
       */
      public function getDREReceitaMensal($mes_ano = 0, $id_empresa = 0)
      {
		  $wempresa = ($id_empresa) ? "AND r.id_empresa = $id_empresa" : "";
		  $sql = "SELECT c.id_pai, p.conta AS conta_pai, SUM(r.valor_pago) as total " 
		  . "\n FROM conta AS c" 
		  . "\n LEFT JOIN conta AS p ON p.id = c.id_pai" 
		  . "\n LEFT JOIN receita AS r ON c.id = r.id_conta AND r.inativo = 0 " 
		  . "\n 	$wempresa " 
		  . "\n 	AND r.pago = 1 " 
		  . "\n 	AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' "
		  . "\n WHERE  c.id_pai IS NOT NULL AND c.tipo = 'C' AND p.dre = 1 AND c.dre = 1 " 
		  . "\n GROUP BY c.id_pai " 
		  . "\n ORDER BY p.ordem, p.conta ASC ";
		  
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	  }
	  
	  /**
       * Gestao::getDREReceitaMensalTotal()
       * 
       * @return
       */
      public function getDREReceitaMensalTotal($mes_ano = 0, $id_empresa = 0)
      {
		  $wempresa = ($id_empresa) ? "AND r.id_empresa = $id_empresa" : "";
		  $sql = "SELECT SUM(r.valor_pago) as total " 
		  . "\n FROM receita AS r" 
		  . "\n WHERE r.inativo = 0 " 
		  . "\n 	$wempresa " 
		  . "\n 	AND r.pago = 1 " 
		  . "\n 	AND DATE_FORMAT(r.data_recebido, '%m/%Y') = '$mes_ano' ";
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
	  }
	  
	  /**
       * Gestao::getDREDespesaMensal()
       * 
       * @return
       */
      public function getDREDespesaMensal($mes_ano = 0, $id_empresa = 0)
      {
		  $wempresa = ($id_empresa) ? "AND d.id_empresa = $id_empresa" : "";
		  $sql = "SELECT c.id_pai, p.conta AS conta_pai, SUM(d.valor_pago) as total " 
		  . "\n FROM conta AS c" 
		  . "\n LEFT JOIN conta AS p ON p.id = c.id_pai" 
		  . "\n LEFT JOIN despesa AS d ON c.id = d.id_conta AND d.inativo = 0 " 
		  . "\n 	$wempresa " 
		  . "\n 	AND d.pago = 1 " 
		  . "\n 	AND DATE_FORMAT(d.data_pagamento, '%m/%Y') = '$mes_ano' " 
		  . "\n WHERE  c.id_pai IS NOT NULL AND c.tipo = 'D' AND p.dre = 1 AND c.dre = 1 " 
		  . "\n GROUP BY c.id_pai " 
		  . "\n ORDER BY p.ordem, c.ordem ASC ";
		  
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	  } 
	  
	  /**
       * Gestao::getDREDespesaMensal()
       * 
       * @return
       */
      public function getDREDespesaMensalTotal($mes_ano = 0, $id_empresa = 0)
      {
		  $wempresa = ($id_empresa) ? "AND d.id_empresa = $id_empresa" : "";
		  $sql = "SELECT SUM(d.valor_pago) as total " 
		  . "\n FROM despesa AS d "
		  . "\n WHERE d.inativo = 0 " 
		  . "\n 	$wempresa " 
		  . "\n 	AND d.pago = 1 " 
		  . "\n 	AND DATE_FORMAT(d.data_pagamento, '%m/%Y') = '$mes_ano' ";
		  
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
	  }
	  
	  /**
       * Gestao::getDREDespesaAPagar()
       * 
       * @return
       */
      public function getDREDespesaAPagar($id_empresa = 0)
      {
		  $wempresa = ($id_empresa) ? "AND d.id_empresa = $id_empresa" : "";
		  $sql = "SELECT DATE_FORMAT(d.data_vencimento, '%m/%Y') AS mes_ano, SUM(d.valor) as total " 
		  . "\n FROM despesa AS d "
		  . "\n WHERE d.inativo = 0 " 
		  . "\n 	$wempresa " 
		  . "\n 	AND d.pago = 0 " 
		  . "\n 	AND d.data_vencimento < DATE_ADD(CURDATE(), INTERVAL 2 MONTH) " 
		  . "\n GROUP BY mes_ano "
		  . "\n ORDER BY mes_ano ";
		  
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	  }
	  
  }
?>