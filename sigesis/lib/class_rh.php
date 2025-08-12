<?php
  /**
   * Classe RH
   *
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class RH
  {
	  private static $db;

      /**
       * RH::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
	  }
	  
	  /**
       * RH::getListaMes()
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
       * RH::getListaMes()
       * 
       * @return
       */
      public function getMes_Ano($tabela)
      {		  
		  $sql = "SELECT mes_ano" 
		  . "\n FROM $tabela"
		  . "\n GROUP BY mes_ano"
		  . "\n ORDER BY STR_TO_DATE(mes_ano, '%m/%Y') DESC ";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::getFeriados()
       * 
       * @return
       */
      public function getFeriados($mes_ano = false)
      {
         $where = ($mes_ano) ? "WHERE DATE_FORMAT(f.data, '%m/%Y') = '$mes_ano'" : "";
		 $sql = "SELECT f.id, f.feriado, f.data, DATE_FORMAT(f.data, '%Y%m%d') as controle " 
		  . "\n FROM feriados as f"
		  . "\n  $where";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::contarFeriados()
       * 
       * @return
       */
      public function contarFeriados($mes_ano = 0)
      {
		 $sql = "SELECT count(1) as quant" 
		  . "\n FROM feriados"
		  . "\n WHERE DAYOFWEEK(data) > 1 AND DATE_FORMAT(data, '%m/%Y') = '$mes_ano' LIMIT 1 ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quant : 0;
      }
	  
      /**
       * RH::processarFeriado()
       * 
       * @return
       */
      public function processarFeriado()
      {
		  if (empty($_POST['feriado']))
              Filter::$msgs['feriado'] = lang('MSG_PADRAO');	  
			  
		  if (empty($_POST['data']))
              Filter::$msgs['data'] = lang('MSG_ERRO_DATA');	

          if (empty(Filter::$msgs)) {

              $data = array(
					'feriado' => sanitize($_POST['feriado']), 
					'data' => dataMySQL($_POST['data'])
			  );

              self::$db->insert("feriados", $data);
              $message = lang('FERIADO_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=rh&acao=feriados");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
      /**
       * RH::processarDescontos()
       * id_status: 0 -> ABERTO
       * id_status: 1 -> PAGO
       * id_status: 2 -> EXCLUIDO
       * 
       * @return
       */
      public function processarDescontos()
      {
		  if (empty($_POST['id_usuario']))
              Filter::$msgs['id_usuario'] = lang('MSG_ERRO_NOME');	  
			  
		  if (empty($_POST['mes_ano']))
              Filter::$msgs['mes_ano'] = lang('MSG_ERRO_DATA');	
			  
		  if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');	

          if (empty(Filter::$msgs)) {

              $data = array(
					'id_usuario' => sanitize($_POST['id_usuario']), 
					'id_descricao' => sanitize($_POST['id_descricao']), 
					'mes_ano' => sanitize($_POST['mes_ano']), 
					'observacao' => sanitize($_POST['observacao']), 
					'valor' => converteMoeda($_POST['valor']),
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
			  );

              self::$db->insert("descontos", $data);
              $message = lang('DESCONTOS_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=rh&acao=descontos");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * RH::getDescontos()
	   *
       * @return
       */
      public function getDescontos($id_usuario = false, $mes_ano = false)
      {
         $wusuario = ($id_usuario) ? " AND d.id_usuario = '$id_usuario' " : "";
         $wmes = ($mes_ano) ? " AND d.mes_ano = '$mes_ano' " : "";
		 $sql = "SELECT d.id, d.id_usuario, d.mes_ano, d.observacao, d.valor, d.id_status, d.usuario, d.data, u.nome, s.descricao " 
		  . "\n FROM descontos AS d"
		  . "\n LEFT JOIN usuario AS u ON u.id = d.id_usuario "
		  . "\n LEFT JOIN salario_descricao AS s ON s.id = d.id_descricao "
		  . "\n  WHERE d.id_status < 2 "
		  . "\n  $wusuario"
		  . "\n  $wmes";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::getTodosDescontos()
	   *
       * @return
       */
      public function getTodosDescontos($mes_ano = false)
      {
         $where = ($mes_ano) ? " AND d.mes_ano = '$mes_ano' " : "";
		 $sql = "SELECT d.id, d.id_usuario, d.mes_ano, d.observacao, d.valor, d.id_status, d.usuario, d.data, u.nome, s.descricao " 
		  . "\n FROM descontos AS d"
		  . "\n LEFT JOIN usuario AS u ON u.id = d.id_usuario "
		  . "\n LEFT JOIN salario_descricao AS s ON s.id = d.id_descricao "
		  . "\n  WHERE d.id_status < 1 "
		  . "\n $where";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::getDescontosAbertos()
	   *
       * @return
       */
      public function getDescontosAbertos($mes_ano, $id_usuario)
      {
		 $sql = "SELECT d.id, d.id_usuario, d.mes_ano, d.observacao, d.valor, d.id_status, d.usuario, d.data, u.nome, s.descricao " 
		  . "\n FROM descontos AS d"
		  . "\n LEFT JOIN usuario AS u ON u.id = d.id_usuario "
		  . "\n LEFT JOIN salario_descricao AS s ON s.id = d.id_descricao "
		  . "\n  WHERE (d.id_status = 0 OR (d.id_status = 1 AND d.mes_ano = '$mes_ano')) AND d.id_usuario = '$id_usuario' ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::totalDescontos()
       * 
       * @return
       */
      public function totalDescontos($mes_ano, $id_usuario)
      {
		 $sql = "SELECT SUM(valor) as total" 
		  . "\n FROM descontos "
		  . "\n  WHERE (id_status = 0 OR (id_status = 1 AND mes_ano = '$mes_ano')) AND id_usuario = '$id_usuario' ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }	

      /**
       * RH::totalDescontosINSS()
	   *
       * @return
       */
      public function totalDescontosINSS($mes_ano, $id_usuario)
      {
		 $sql = "SELECT SUM(d.valor) as total" 
		  . "\n FROM descontos AS d"
		  . "\n LEFT JOIN salario_descricao AS s ON s.id = d.id_descricao "
		  . "\n WHERE (d.id_status = 0 OR (d.id_status = 1 AND d.mes_ano = '$mes_ano')) AND s.inss = 1 AND d.id_usuario = '$id_usuario' ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }
	  
      /**
       * RH::processarBonus()
       * id_status: 0 -> ABERTO
       * id_status: 1 -> PAGO
       * id_status: 2 -> EXCLUIDO
       * 
       * @return
       */
      public function processarBonus()
      {
		  if (empty($_POST['id_usuario']))
              Filter::$msgs['id_usuario'] = lang('MSG_ERRO_NOME');	  
			  
		  if (empty($_POST['mes_ano']))
              Filter::$msgs['mes_ano'] = lang('MSG_ERRO_DATA');	 
			  
		  if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');	

          if (empty(Filter::$msgs)) {

              $data = array(
					'id_usuario' => sanitize($_POST['id_usuario']), 
					'mes_ano' => sanitize($_POST['mes_ano']), 
					'id_descricao' => sanitize($_POST['id_descricao']), 
					'observacao' => sanitize($_POST['observacao']), 
					'valor' => converteMoeda($_POST['valor']),
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
			  );

              self::$db->insert("bonus", $data);
              $message = lang('BONUS_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=rh&acao=bonus"); 
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * RH::getBonus()
	   *
       * @return
       */
      public function getBonus($id_usuario = false, $mes_ano = false)
      {
         $wusuario = ($id_usuario) ? " AND b.id_usuario = '$id_usuario' " : "";
         $wmes = ($mes_ano) ? " AND b.mes_ano = '$mes_ano' " : "";
		 $sql = "SELECT b.id, b.id_usuario, b.mes_ano, b.observacao, b.valor, b.id_status, b.usuario, b.data, u.nome, s.descricao " 
		  . "\n FROM bonus AS b "
		  . "\n LEFT JOIN usuario AS u ON u.id = b.id_usuario "
		  . "\n LEFT JOIN salario_descricao AS s ON s.id = b.id_descricao "
		  . "\n  WHERE b.id_status < 2 "
		  . "\n  $wusuario"
		  . "\n  $wmes";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::getTodosBonus()
	   *
       * @return
       */
      public function getTodosBonus($mes_ano = false)
      {
         $where = ($mes_ano) ? " AND b.mes_ano = '$mes_ano' " : "";
		 $sql = "SELECT b.id, b.id_usuario, b.mes_ano, b.observacao, b.valor, b.id_status, b.usuario, b.data, u.nome, s.descricao " 
		  . "\n FROM bonus AS b "
		  . "\n LEFT JOIN usuario AS u ON u.id = b.id_usuario "
		  . "\n LEFT JOIN salario_descricao AS s ON s.id = b.id_descricao "
		  . "\n  WHERE b.id_status < 2 "
		  . "\n $where";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::getBonusAbertos()
	   *
       * @return
       */
      public function getBonusAbertos($mes_ano, $id_usuario)
      {
		 $sql = "SELECT b.id, b.id_usuario, b.mes_ano, b.observacao, b.valor, b.id_status, b.usuario, b.data, u.nome, b.descricao " 
		  . "\n FROM bonus AS b "
		  . "\n LEFT JOIN usuario AS u ON u.id = b.id_usuario "
		  . "\n LEFT JOIN salario_descricao AS s ON s.id = b.id_descricao "
		  . "\n  WHERE (b.id_status = 0 OR (b.id_status = 1 AND b.mes_ano = '$mes_ano')) AND b.id_usuario = '$id_usuario' ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::totalBonus()
       * 
       * @return
       */
      public function totalBonus($mes_ano, $id_usuario)
      {
		 $sql = "SELECT SUM(valor) as total" 
		  . "\n FROM bonus "
		  . "\n  WHERE (id_status = 0 OR (id_status = 1 AND mes_ano = '$mes_ano')) AND id_usuario = '$id_usuario' ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }	

      /**
       * RH::totalBonusINSS()
	   *
       * @return
       */
      public function totalBonusINSS($mes_ano, $id_usuario)
      {
		 $sql = "SELECT SUM(b.valor) as total" 
		  . "\n FROM bonus AS b "
		  . "\n LEFT JOIN salario_descricao AS s ON s.id = b.id_descricao "
		  . "\n  WHERE (b.id_status = 0 OR (b.id_status = 1 AND b.mes_ano = '$mes_ano')) AND s.inss = 1 AND b.id_usuario = '$id_usuario' ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }

      /**
       * RH::totalAdiantamentos()
       * 
       * @return
       */
      public function totalAdiantamentos($mes_ano, $id_usuario = false)
      {	
		 $where = ($id_usuario) ? " AND id_usuario = '$id_usuario' " : "";
		 $sql = "SELECT SUM(valor) as total " 
		  . "\n FROM adiantamentos as u"
		  . "\n WHERE mes_ano = '$mes_ano' $where ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }

      /**
       * RH::totalTransporte()
       * 
       * @return
       */
      public function totalTransporte($id_usuario = false)
      {
		  $sql = "SELECT SUM(o.valor) as total " 
		  . "\n FROM usuario_onibus as u, onibus as o"
		  . "\n WHERE u.id_onibus = o.id AND u.id_usuario = '$id_usuario' ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }	
	  
      /**
       * RH::processarINSS()
       * 
       * @return
       */
      public function processarINSS()
      {			  
		  if (empty($_POST['salario']))
              Filter::$msgs['salario'] = lang('MSG_ERRO_SALARIO');	
			  
		  if (empty($_POST['percentual']))
              Filter::$msgs['percentual'] = lang('MSG_ERRO_PERCENTUAL');	
		  
		  if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR_MAXIMO');	

          if (empty(Filter::$msgs)) {

              $data = array(
					'salario' => converteMoeda($_POST['salario']),
					'valor' => converteMoeda($_POST['valor']),
					'percentual' => converteMoeda($_POST['percentual'])
			  );

              self::$db->insert("inss", $data);
              $message = lang('INSS_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=rh&acao=inss");        
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * RH::getINSS()
       * @return
       */
      public function getINSS()
      {
		 $sql = "SELECT id, salario, valor, percentual " 
		  . "\n FROM inss "
		  . "\n  ORDER BY valor ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::getPercentualINSS()
       * 
       * @return
       */
      public function getPercentualINSS($salario)
      {
		 $sql = "SELECT percentual " 
		  . "\n FROM inss "
		  . "\n  WHERE salario >= $salario "
		  . "\n  ORDER BY salario ASC ";
          $row = self::$db->first($sql);

          return ($row) ? $row->percentual : (int) 0;
      }	 

      /**
       * RH::getValorINSS()
       * 
       * @return
       */
      public function getValorINSS()
      {
		 $sql = "SELECT valor " 
		  . "\n FROM inss "
		  . "\n  WHERE percentual = 11 ";
          $row = self::$db->first($sql);

          return ($row) ? $row->valor : (int) 0;
      }	 
	  
	  
      /**
       * RH::processarIRRF()
       * 
       * @return
       */
      public function processarIRRF()
      {			  
		  if (empty($_POST['salario']))
              Filter::$msgs['salario'] = lang('MSG_ERRO_SALARIO');	
			  
		  if (empty($_POST['percentual']))
              Filter::$msgs['percentual'] = lang('MSG_ERRO_PERCENTUAL');	
		  
		  if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR_MAXIMO');	

          if (empty(Filter::$msgs)) {

              $data = array(
					'salario' => converteMoeda($_POST['salario']),
					'valor' => converteMoeda($_POST['valor']),
					'percentual' => converteMoeda($_POST['percentual'])
			  );

              self::$db->insert("irrf", $data);
              $message = lang('IRRF_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=rh&acao=irrf");        
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * RH::getIRRF()
       * @return
       */
      public function getIRRF()
      {
		 $sql = "SELECT id, salario, valor, percentual " 
		  . "\n FROM irrf "
		  . "\n  ORDER BY valor ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::getPercentualIRRF()
       * 
       * @return
       */
      public function getPercentualIRRF($salario)
      {
		 $sql = "SELECT percentual " 
		  . "\n FROM irrf "
		  . "\n  WHERE salario >= $salario "
		  . "\n  ORDER BY salario ASC ";
          $row = self::$db->first($sql);

          return ($row) ? $row->percentual : (int) 0;
      }	 

      /**
       * RH::getPercentualIRRF()
       * 
       * @return
       */
      public function getValorIRRF($salario)
      {
		 $sql = "SELECT valor " 
		  . "\n FROM irrf "
		  . "\n  WHERE salario >= $salario "
		  . "\n  ORDER BY salario ASC ";
          $row = self::$db->first($sql);

          return ($row) ? $row->valor : (int) 0;
      }	 
	  
      /**
       * RH::processarDependenteIRRF()
       * 
       * @return
       */
      public function processarDependenteIRRF()
      {			  
		  if (empty($_POST['ano']))
              Filter::$msgs['ano'] = lang('MSG_ERRO_DATA');	
			  
		  if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');	

          if (empty(Filter::$msgs)) {

              $data = array(
					'ano' => sanitize($_POST['ano']),
					'valor' => converteMoeda($_POST['valor'])
			  );

              self::$db->insert("dependente", $data);
              $message = lang('IRRF_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=rh&acao=dependente");        
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * RH::getTodosDependentesIRRF()
       * @return
       */
      public function getTodosDependentesIRRF()
      {
		 $sql = "SELECT id, valor, ano " 
		  . "\n FROM dependente ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::getDependentesIRRF()
       * 
       * @return
       */
      public function getDependentesIRRF($ano)
      {
		 $sql = "SELECT valor " 
		  . "\n FROM dependente "
		  . "\n  WHERE ano = $ano ";
          $row = self::$db->first($sql);

          return ($row) ? $row->valor : (int) 0;
      }	
	  
      /**
       * RH::processarFilhos()
       * 
       * @return
       */
      public function processarFilhos()
      {			  
		  if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_FILHOS');	
			  
		  if (empty($_POST['salario']))
              Filter::$msgs['salario'] = lang('MSG_ERRO_VALOR');	

          if (empty(Filter::$msgs)) {

              $data = array(
					'valor' => converteMoeda($_POST['valor']),
					'salario' => converteMoeda($_POST['salario'])
			  );

              self::$db->insert("filhos", $data);
              $message = lang('FILHOS_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=rh&acao=filhos");        
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * RH::getTodosFilhos()
       * @return
       */
      public function getTodosFilhos()
      {
		 $sql = "SELECT id, valor, salario " 
		  . "\n FROM filhos "
		  . "\n  ORDER BY salario ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::getFilhos()
       * 
       * @return
       */
      public function getFilhos($salario)
      {
		 $sql = "SELECT valor " 
		  . "\n FROM filhos "
		  . "\n  WHERE salario >= $salario "
		  . "\n  ORDER BY salario ASC ";
          $row = self::$db->first($sql);

          return ($row) ? $row->valor : (int) 0;
      }	

      /**
       * RH::getDependentes()
       * 
       * @return
       */
      public function getDependentes($id_usuario, $todos = false)
      {
		 $where = ($todos) ? "" : "AND (DATEDIFF(curdate(), data_nasc)/365) < 14 ";
		 $sql = "SELECT COUNT(1) AS quant " 
		  . "\n FROM usuario_dependente "
		  . "\n  WHERE inativo = 0 AND id_usuario = $id_usuario "
		  . "\n  $where ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quant : (int) 0;
      }	

      /**
       * RH::getAdiantamentos()
       * 
       * @return
       */
      public function getAdiantamentos()
      {		  
		 $sql = "SELECT u.id, u.nome, u.salario, u.nivel, u.cargo, e.nome as empresa " 
		  . "\n FROM usuario as u"
		  . "\n LEFT JOIN empresa as e ON e.id = u.id_empresa" 
		  . "\n WHERE u.active = 'y' AND u.adiantamento = 1 ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * RH::processarAdiantamentos()
       * 
       * @return
       */
      public function processarAdiantamentos()
      {		  
		 $sql = "SELECT u.id, u.nome, u.salario, u.nivel " 
		  . "\n FROM usuario as u"
		  . "\n WHERE u.active = 'y' AND u.adiantamento = 1 ";
          $retorno_row = self::$db->fetch_all($sql);
		  if($retorno_row) {
			foreach($retorno_row as $row) {
				$valor = $row->salario * 0.4;
				if($row->nivel == 2) {
					$valor = $row->salario;
					$horas = $this->getHorasInstrutor($row->nome, $_POST['data']);
					$valor = $valor * $horas;
				}
				$data = array(
					'id_usuario' => $row->id,
					'nivel' => $row->nivel,
					'valor' => $valor,
					'mes_ano' => $_POST['data'],
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
				);
				self::$db->insert("adiantamentos", $data);
			}
			unset($row);
			Filter::msgOk(lang('ADIANTAMENTO_ADICIONADO_OK'), "index.php?do=rh&acao=adiantamentos&datafiltro=".$_POST['data']);
		  } else {
			Filter::msgAlert(lang('NAOPROCESSADO'));
		  }
      }
	  
	  /**
       * RH::contarUsuarios()
	   *
       * @return
       */
	  public function contarUsuarios($nivel)
      {
		  $sql = "SELECT COUNT(1) as quant " 
		  . "\n FROM usuario as u" 
		  . "\n WHERE u.active = 'y' AND nivel = $nivel ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quant : 0;
      }

      /**
       * RH::getResumoColaboradores()
       * 
       * @return
       */
      public function getResumoColaboradores()
      {
		  $sql = "SELECT u.id, u.nome, u.salario, u.transporte, u.planodesaude, u.bonus, u.sabado, u.nivel, u.lanche, u.filhos, u.carteira " 
		  . "\n FROM usuario as u"
		  . "\n WHERE u.active = 'y' AND u.nivel not in (9)"
		  . "\n ORDER BY u.nome";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	  }
	  

      /**
       * RH::getColaboradores()
       * 
       * @return
       */
      public function getColaboradores($mes_ano = 0, $id_empresa = false)
      {
         $wempresa = ($id_empresa) ? " AND u.id_empresa = $id_empresa " : "";
		 $retorno = array();
		 
		 $newData = explode('/', $mes_ano);
		 $proximo_mes = date('m/Y', mktime(0, 0, 0, $newData[0] + 1, 1, $newData[1]) );
		 
		$sql = "SELECT u.id, u.nome, e.nome as empresa, u.salario, u.insalubridade, u.abono, u.transporte, u.planodesaude, u.planodependente, u.planoextra, u.bonus, u.sabado, u.nivel, u.lanche, u.prolabore, u.carteira, u.cpf, u.banco, u.codigo, u.agencia, u.conta, u.observacao " 
		  . "\n FROM usuario as u"
		  . "\n LEFT JOIN empresa as e ON e.id = u.id_empresa" 
		  . "\n WHERE u.active = 'y' AND u.nivel not in (9) AND u.salario > 0 "
		  . "\n $wempresa "
		  . "\n ORDER BY e.nome, u.nome";
		  
          $retorno_row = self::$db->fetch_all($sql);
		  if($retorno_row) {
			foreach($retorno_row as $urow) {
				$id = ($urow) ? $urow->id : (int) 0;
				$nome = ($urow) ? $urow->nome : "";
				$nomeempresa = ($urow) ? $urow->empresa : "";
				$nivel = ($urow) ? nivel($urow->nivel) : "";
				$salario = ($urow) ? $urow->salario : (int) 0;
				$insalubridade = ($urow) ? $urow->insalubridade : (int) 0;
				$abono = ($urow) ? $urow->abono : (int) 0;
				$transporte = ($urow) ? $urow->transporte : (int) 0;
				$planodesaude = ($urow) ? $urow->planodesaude : (int) 0;
				$planodependente = ($urow) ? $urow->planodependente : (int) 0;
				$planoextra = ($urow) ? $urow->planoextra : (int) 0;
				$bonus = ($urow) ? $urow->bonus : (int) 0;				
				$sabado = ($urow) ? $urow->sabado : (int) 0;
				$lanche = ($urow) ? $urow->lanche : (int) 0;
				$carteira = ($urow) ? $urow->carteira : (int) 0;
				$prolabore = ($urow) ? $urow->prolabore : (int) 0;
				
				$dias = contarDiasUteis($proximo_mes, $sabado);
				$filhos = $this->getDependentes($id);
				$dependentes = $this->getDependentes($id, true);
				$salariominimo = $this->getSalarioMinimo($newData[1]);
				$salariominimo = ($salariominimo) ? $salariominimo : $salario;
				$onibus = $this->totalTransporte($id);
				$onibus = $onibus*$dias*2;
				$lanche = $lanche*$dias;
				$insalubridade = $salariominimo * $insalubridade / 100; 
				$transporte = $salario * $transporte / 100; 
		  	    $totaldescontos = $this->totalDescontos($mes_ano, $id);	
		  	    $totaldescontosINSS = $this->totalDescontosINSS($mes_ano, $id);	
				$totalbonus = $this->totalBonus($mes_ano, $id);	
				$totalbonusINSS = $this->totalBonusINSS($mes_ano, $id);
				$adiantamentos = $this->totalAdiantamentos($mes_ano, $id);			

				$totalbonus += $bonus;			
				$salario_base = $salario + $insalubridade + $bonus + $totalbonusINSS - $totaldescontosINSS;			
				
				$valor_filhos = $this->getFilhos($salario_base);
				$valor_filhos = $valor_filhos*$filhos;
				$percentual_inss = $this->getPercentualINSS($salario_base);
				$inss = $salario_base * $percentual_inss / 100;
				$dependente_irrf = $this->getDependentesIRRF($newData[1]);
				$salario_irrf = $salario_base - $inss - ($dependente_irrf*$dependentes);
				$percentual_irrf = $this->getPercentualIRRF($salario_irrf);
				$valor_irrf = $this->getValorIRRF($salario_irrf);
				$irrf = ($salario_irrf * $percentual_irrf / 100) - $valor_irrf;
				$irrf = ($irrf > 10) ? $irrf : 0;
				if($carteira == 1) {
					$inss = 0;
					$irrf = 0;
				}
				if($prolabore == 1) {
					$inss = $salario_base * 0.11;
					$valor_inss = $this->getValorINSS();
					$inss = ($inss > $valor_inss and $valor_inss > 0) ? $valor_inss : $inss;
				}
				
				$valor_pagar = $salario + $insalubridade + $totalbonus + $abono + $valor_filhos - $totaldescontos - $transporte - $planodesaude - $planodependente - $planoextra - $adiantamentos - $inss - $irrf;
				
				$colaborador = array(
					'id' => $id,
					'nome' => $nome,
					'cpf' => $urow->cpf,
					'banco' => $urow->banco,
					'codigo' => $urow->codigo,
					'agencia' => $urow->agencia,
					'conta' => $urow->conta,
					'observacao' => $urow->observacao,
					'empresa' => $nomeempresa,
					'prolabore' => $prolabore,
					'dias' => $dias,
					'nivel' => $nivel,
					'id_nivel' => $urow->nivel,
					'inss' => $inss,
					'irrf' => $irrf,
					'lanche' => $lanche,
					'transporte' => $transporte,
					'insalubridade' => $insalubridade,
					'abono' => $abono,
					'salario' => $salario,
					'salario_base' => $salario_base,
					'onibus' => $onibus,
					'bonus' => $bonus,
					'filhos' => $valor_filhos,
					'valor_pagar' => $valor_pagar,
					'planodesaude' => $planodesaude,
					'planodependente' => $planodependente,
					'planoextra' => $planoextra,
					'totaldescontos' => $totaldescontos,
					'totalbonus' => $totalbonus,
					'adiantamentos' => $adiantamentos,
					'mes_ano' => $mes_ano
				);
				$retorno[] = $colaborador;
			}
			unset($urow);
		  }		  
          return $retorno;
      }
	  
	  /**
       * RH::validaPagamentoColaborador()
	   *
       * @return
       */
	  public function validaPagamentoColaborador($mes_ano)
      {
		  $sql = "SELECT COUNT(1) as quant " 
		  . "\n FROM salarios as s" 
		  . "\n WHERE nivel <> 2 AND mes_ano = '$mes_ano' ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * RH::getSalarioMinimo($ano)
	   *
       * @return
       */
	  public function getSalarioMinimo($ano)
      {
		  $sql = "SELECT s.salario " 
		  . "\n FROM salario_minimo as s" 
		  . "\n WHERE s.ano = '$ano' ";
          $row = self::$db->first($sql);

          return ($row) ? $row->salario : 0;
      }

      /**
       * RH::getSalarioColaborador()
       * 
       * @return
       */
      public function getSalarioColaborador($mes_ano, $id)
      {
        $retorno = array();
		$retorno['id'] = 0;
		$retorno['nome'] = '';
		$retorno['cpf'] = '';
		$retorno['banco'] = '';
		$retorno['codigo'] = '';
		$retorno['agencia'] = '';
		$retorno['conta'] = '';
		$retorno['observacao'] = '';
		$retorno['empresa'] = '';
		$retorno['prolabore'] = 0;
		$retorno['dias'] = 0;
		$retorno['nivel'] = 0;
		$retorno['id_nivel'] = 0;
		$retorno['inss'] = 0;
		$retorno['irrf'] = 0;
		$retorno['lanche'] = 0;
		$retorno['transporte'] = 0;
		$retorno['insalubridade'] = 0;
		$retorno['abono'] = 0;
		$retorno['salario'] = 0;
		$retorno['salario_base'] = 0;
		$retorno['onibus'] = 0;
		$retorno['bonus'] = 0;
		$retorno['filhos'] = 0;
		$retorno['valor_pagar'] = 0;
		$retorno['planodesaude'] = 0;
		$retorno['planodependente'] = 0;
		$retorno['planoextra'] = 0;
		$retorno['totaldescontos'] = 0;
		$retorno['totalbonus'] = 0;
		$retorno['adiantamentos'] = 0;
		$retorno['mes_ano'] = '';
		 
		$newData = explode('/', $mes_ano);
		$proximo_mes = date('m/Y', mktime(0, 0, 0, $newData[0] + 1, 1, $newData[1]) );
		 
		$sql = "SELECT u.id, u.nome, e.nome as empresa, u.salario, u.insalubridade, u.abono, u.transporte, u.planodesaude, u.planodependente, u.planoextra, u.bonus, u.sabado, u.nivel, u.lanche, u.prolabore, u.carteira, u.cpf, u.banco, u.codigo, u.agencia, u.conta, u.observacao " 
		  . "\n FROM usuario as u"
		  . "\n LEFT JOIN empresa as e ON e.id = u.id_empresa" 
		  . "\n WHERE u.id = $id ";
		  
          $urow = self::$db->first($sql);
		  if($urow) {
				$id = ($urow) ? $urow->id : (int) 0;
				$nome = ($urow) ? $urow->nome : "";
				$nomeempresa = ($urow) ? $urow->empresa : "";
				$nivel = ($urow) ? nivel($urow->nivel) : "";
				$salario = ($urow) ? $urow->salario : (int) 0;
				$insalubridade = ($urow) ? $urow->insalubridade : (int) 0;
				$abono = ($urow) ? $urow->abono : (int) 0;
				$transporte = ($urow) ? $urow->transporte : (int) 0;
				$planodesaude = ($urow) ? $urow->planodesaude : (int) 0;
				$planodependente = ($urow) ? $urow->planodependente : (int) 0;
				$planoextra = ($urow) ? $urow->planoextra : (int) 0;
				$bonus = ($urow) ? $urow->bonus : (int) 0;				
				$sabado = ($urow) ? $urow->sabado : (int) 0;
				$lanche = ($urow) ? $urow->lanche : (int) 0;
				$carteira = ($urow) ? $urow->carteira : (int) 0;
				$prolabore = ($urow) ? $urow->prolabore : (int) 0;
				
				$dias = contarDiasUteis($proximo_mes, $sabado);
				$filhos = $this->getDependentes($id);
				$dependentes = $this->getDependentes($id, true);
				$salariominimo = $this->getSalarioMinimo($newData[1]);
				$salariominimo = ($salariominimo) ? $salariominimo : $salario;
				$onibus = $this->totalTransporte($id);
				$onibus = $onibus*$dias*2;
				$lanche = $lanche*$dias;
				$insalubridade = $salariominimo * $insalubridade / 100; 
				$transporte = $salario * $transporte / 100; 
		  	    $totaldescontos = $this->totalDescontos($mes_ano, $id);	
		  	    $totaldescontosINSS = $this->totalDescontosINSS($mes_ano, $id);	
				$totalbonus = $this->totalBonus($mes_ano, $id);	
				$totalbonusINSS = $this->totalBonusINSS($mes_ano, $id);
				$adiantamentos = $this->totalAdiantamentos($mes_ano, $id);			

				$totalbonus += $bonus;			
				$salario_base = $salario + $insalubridade + $bonus + $totalbonusINSS - $totaldescontosINSS;			
				
				$valor_filhos = $this->getFilhos($salario_base);
				$valor_filhos = $valor_filhos*$filhos;
				$percentual_inss = $this->getPercentualINSS($salario_base);
				$inss = $salario_base * $percentual_inss / 100;
				$dependente_irrf = $this->getDependentesIRRF($newData[1]);
				$salario_irrf = $salario_base - $inss - ($dependente_irrf*$dependentes);
				$percentual_irrf = $this->getPercentualIRRF($salario_irrf);
				$valor_irrf = $this->getValorIRRF($salario_irrf);
				$irrf = ($salario_irrf * $percentual_irrf / 100) - $valor_irrf;		
				$irrf = ($irrf > 10) ? $irrf : 0;		
				if($carteira == 1) {
					$inss = 0;
					$irrf = 0;
				}
				if($prolabore == 1) {
					$inss = $salario_base * 0.11;
					$valor_inss = $this->getValorINSS();
					$inss = ($inss > $valor_inss and $valor_inss > 0) ? $valor_inss : $inss;
				}
				
				$valor_pagar = $salario + $insalubridade + $totalbonus + $abono + $valor_filhos - $totaldescontos - $transporte - $planodesaude - $planodependente - $planoextra - $adiantamentos - $inss - $irrf;
				
				$retorno['id'] = $id;
				$retorno['nome'] = $nome;
				$retorno['cpf'] = $urow->cpf;
				$retorno['banco'] = $urow->banco;
				$retorno['codigo'] = $urow->codigo;
				$retorno['agencia'] = $urow->agencia;
				$retorno['conta'] = $urow->conta;
				$retorno['observacao'] = $urow->observacao;
				$retorno['empresa'] = $nomeempresa;
				$retorno['prolabore'] = $prolabore;
				$retorno['dias'] = $dias;
				$retorno['nivel'] = $nivel;
				$retorno['id_nivel'] = $urow->nivel;
				$retorno['inss'] = $inss;
				$retorno['irrf'] = $irrf;
				$retorno['lanche'] = $lanche;
				$retorno['transporte'] = $transporte;
				$retorno['insalubridade'] = $insalubridade;
				$retorno['abono'] = $abono;
				$retorno['salario'] = $salario;
				$retorno['salario_base'] = $salario_base;
				$retorno['onibus'] = $onibus;
				$retorno['bonus'] = $bonus;
				$retorno['filhos'] = $valor_filhos;
				$retorno['valor_pagar'] = $valor_pagar;
				$retorno['planodesaude'] = $planodesaude;
				$retorno['planodependente'] = $planodependente;
				$retorno['planoextra'] = $planoextra;
				$retorno['totaldescontos'] = $totaldescontos;
				$retorno['totalbonus'] = $totalbonus;
				$retorno['adiantamentos'] = $adiantamentos;
				$retorno['mes_ano'] = $mes_ano;
		  }		  
          return $retorno;
      }

      /**
       * RH::PagarSalarioColaboradores()
       * 
       * @return
       */
      public function PagarSalarioColaboradores($mes_ano)
      {		  
		 $retorno_row = $this->getColaboradores($mes_ano);
		  if($retorno_row) {
			foreach($retorno_row as $exrow) {
				$data = array(
					'id_funcionario' => $exrow['id'],
					'nivel' => $exrow['id_nivel'],
					'mes_ano' => $mes_ano,
					'salario' => $exrow['salario'],
					'bonus' => $exrow['bonus'],
					'inss' => $exrow['inss'],
					'transporte' => $exrow['onibus'],
					'salario_total' => $exrow['salario'],
					'outros_bonus' => $exrow['totalbonus'],
					'plano_saude' => $exrow['planodesaude'],
					'descontos' => $exrow['totaldescontos'],
					'adiantamento' => $exrow['adiantamentos'],
					'adiantamento' => $exrow['abono'],
					'insalubridade' => $exrow['insalubridade'],
					'salario_pagar' => $exrow['valor_pagar'],
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
				);
				self::$db->insert("salarios", $data);
				
				$data_bonus = array(
					'id_status' => 1,
					'data' => 'NOW()'
				);
				self::$db->update("bonus", $data_bonus, "id_status = 0 AND mes_ano = '$mes_ano' AND id_usuario = ".$exrow['id']);
				
				$data_descontos = array(
					'id_status' => 1,
					'data' => 'NOW()'
				);
				self::$db->update("descontos", $data_descontos, "id_status = 0 AND mes_ano = '$mes_ano' AND id_usuario = ".$exrow['id']);
			}
			unset($exrow);
			Filter::msgOk(lang('SALARIO_PAGO_OK'), "index.php?do=rh&acao=colaboradores&datafiltro=".$mes_ano);
		  } else {
			Filter::msgAlert(lang('NAOPROCESSADO'));
		  }
      } 

      /**
       * RH::getPontoEletronicoApp()
	   *
       * @return
       */
      public function getPontoEletronicoApp($id_usuario)
      {
		 $sql = "SELECT p.id, u.nome, u.usuario, p.operacao, p.data_operacao, p.horas " 
		  . "\n FROM ponto_eletronico AS p"
		  . "\n LEFT JOIN usuario AS u ON u.id = p.id_usuario "
		  . "\n WHERE p.id_usuario = '$id_usuario' "
		  . "\n ORDER BY p.data_operacao DESC LIMIT 0,10 ";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
      
  }
?>