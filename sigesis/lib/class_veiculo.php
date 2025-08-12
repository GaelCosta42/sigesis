<?php
  /**
   * Classe Veiculo
   *
  * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Veiculo
  {
      const uTable = "veiculo";
      private static $db;

      /**
       * Veiculo::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }
	  
	  /**
       * Veiculo::getVeiculos()
	   *
       * @return
       */
	  public function getVeiculos()
      {
          $sql = "SELECT v.* " 
		  . "\n FROM veiculo as v"
		  . "\n WHERE v.inativo = 0 "
		  . "\n ORDER BY v.placa";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Veiculo::getVeiculos()
	   *
       * @return
       */
	  public function getVeiculosAtivos()
      {
		  $usuario = Registry::get("Usuario");
		  if($usuario->is_Tecnico()) {
				  $sql = "SELECT v.* " 
				  . "\n FROM veiculo as v"
				  . "\n WHERE v.inativo = 0 AND v.status = 'ativo' "
				  . "\n ORDER BY v.placa";
				$row = self::$db->fetch_all($sql);
				return ($row) ? $row : 0;
		  } else {			  
			  $sql = "SELECT v.* " 
			  . "\n FROM veiculo as v"
			  . "\n WHERE v.inativo = 0 AND v.status = 'ativo' AND v.uso = 1 AND v.id_motorista = ".session('userid')
			  . "\n ORDER BY v.placa";
			  $row = self::$db->fetch_all($sql);
			  
			  if($row) {
				  return $row;
			  } else {
				  $sql = "SELECT v.* " 
				  . "\n FROM veiculo as v"
				  . "\n WHERE v.inativo = 0 AND v.status = 'ativo' AND v.uso = 0 "
				  . "\n ORDER BY v.placa";
				$row = self::$db->fetch_all($sql);
				return ($row) ? $row : 0;
			  }
		  }		  
      }
	  
      /**
       * Veiculo::processarVeiculo()
       * 
       * @return
       */
      public function processarVeiculo()
      {
		  if (empty($_POST['placa']))
              Filter::$msgs['placa'] = lang('MSG_ERRO_PLACA');	  

          if (empty(Filter::$msgs)) {

              $data = array(
					'placa' => sanitize($_POST['placa']), 
					'modelo' => sanitize($_POST['modelo']), 
					'marca' => sanitize($_POST['marca']), 
					'cor' => sanitize($_POST['cor']), 
					'ano_fab' => sanitize(post('ano_fab')), 
					'ano_modelo' => sanitize($_POST['ano_modelo']), 
					'renavam' => sanitize($_POST['renavam']), 
					'chassi' => sanitize($_POST['chassi']), 
					'cpf_cnpj' => sanitize($_POST['cpf_cnpj']), 
					'data_compra' => dataMySQL($_POST['data_compra']), 
					'data_venda' => dataMySQL($_POST['data_venda']), 
					'valor_compra' => converteMoeda(post('valor_compra')),
					'valor_venda' => converteMoeda(post('valor_venda')),
					'status' => sanitize($_POST['status']), 
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
			  );

              (Filter::$id) ? self::$db->update(self::uTable, $data, "id=" . Filter::$id) : self::$db->insert(self::uTable, $data);
              $message = (Filter::$id) ? lang('VEICULO_ALTERADO_OK') : lang('VEICULO_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=veiculo&acao=listar");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }	 
	  
	  /**
       * Veiculo::getEventos()
	   *
       * @return
       */
	  public function getEventos()
      {
          $sql = "SELECT d.id, d.descricao, c.conta " 
		  . "\n FROM veiculo_descricao as d"
		  . "\n LEFT JOIN conta as c ON c.id = d.id_conta " 
		  . "\n WHERE d.inativo = 0 "
		  . "\n ORDER BY d.descricao ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Veiculo::processarEvento()
       * 
       * @return
       */
      public function processarEvento()
      {
		  if (empty($_POST['descricao']))
              Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');	  

          if (empty(Filter::$msgs)) {

              $data = array(
					'descricao' => sanitize(post('descricao')), 
					'id_conta' => sanitize(post('id_conta')), 
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
			  );

              (Filter::$id) ? self::$db->update("veiculo_descricao", $data, "id=" . Filter::$id) : self::$db->insert("veiculo_descricao", $data);
              $message = (Filter::$id) ? lang('EVENTO_ALTERADO_OK') : lang('EVENTO_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=evento&acao=listar");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      } 
	  
	  /**
       * Veiculo::getEventosVeiculos()
	   *
       * @return
       */
	  public function getEventosVeiculos($id_descricao = false, $id_veiculo = false, $realizado = false)
      {
		  $wdescricao = ($id_descricao) ? " AND e.id_descricao = $id_descricao " : "";
		  $wveiculo = ($id_veiculo) ? " AND e.id_veiculo = $id_veiculo " : "";
		  $wrealizado = ($realizado) ? " AND e.realizado = 1 " : " AND e.realizado = 0 ";
          $sql = "SELECT e.*, d.descricao, v.placa, v.modelo, u.nome as motorista, c.nome as fornecedor " 
		  . "\n FROM veiculo_eventos as e"
		  . "\n LEFT JOIN veiculo_descricao as d ON d.id = e.id_descricao " 
		  . "\n LEFT JOIN veiculo as v ON v.id = e.id_veiculo " 
		  . "\n LEFT JOIN usuario as u ON u.id = e.id_motorista " 
		  . "\n LEFT JOIN cadastro as c ON c.id = e.id_cadastro " 
		  . "\n WHERE e.inativo = 0 "
		  . "\n $wrealizado "
		  . "\n $wdescricao "
		  . "\n $wveiculo "
		  . "\n ORDER BY e.id ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Veiculo::processarEventosVeiculo()
       * 
       * @return
       */
      public function processarEventosVeiculo()
      {
		  if (empty($_POST['id_descricao']))
              Filter::$msgs['id_descricao'] = lang('MSG_ERRO_DESCRICAO');	

		  if (empty($_POST['id_veiculo']))
              Filter::$msgs['id_veiculo'] = lang('MSG_ERRO_VEICULO');		

		  if (empty($_POST['id_cadastro']))
              Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_FORNECEDOR');	

		  if (empty($_POST['km']))
              Filter::$msgs['km'] = lang('MSG_ERRO_KM');	

		  if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');			  

          if (empty(Filter::$msgs)) {
			  
			  $data_evento = (empty($_POST['data_evento'])) ? "NOW()" : dataMySQL($_POST['data_evento']);
			  $id_descricao = post('id_descricao');
			  if(Filter::$id) {
				  $data = array(
						'id_descricao' => sanitize($_POST['id_descricao']), 
						'id_veiculo' => sanitize($_POST['id_veiculo']), 
						'id_motorista' => sanitize($_POST['id_motorista']), 
						'id_cadastro' => sanitize($_POST['id_cadastro']), 
						'id_despesa' => sanitize($_POST['id_despesa']), 
						'km' => sanitize($_POST['km']),
						'valor' => converteMoeda($_POST['valor']),  
						'data_evento' => $data_evento, 
						'observacao' => sanitize($_POST['observacao']), 
						'realizado' => sanitize(post('realizado')), 
						'usuario' => $_SESSION['nomeusuario'],
						'data' => "NOW()"
				  );
				  self::$db->update("veiculo_eventos", $data, "id=" . Filter::$id);
			  } else {
				  $d = post('despesa');
				  $id_despesa = 0;
				  if($d) {
					  $pago = ($d == 1) ? 0 : 1;
					  $data_vencimento = (empty($_POST['data_vencimento'])) ? "NOW()" : dataMySQL($_POST['data_vencimento']);
					  $data_pagamento = (empty($_POST['data_pagamento'])) ? "NOW()" : dataMySQL($_POST['data_pagamento']);
					  $data_pagamento = ($d == 1) ? "" : $data_pagamento;
					  $descricao = getValue("descricao", "veiculo_descricao", "id='$id_descricao'");
					  $id_conta = getValue("id_conta", "veiculo_descricao", "id='$id_descricao'");
					  $data_despesa = array(
						'id_empresa' => sanitize($_POST['id_empresa']),
						'id_conta' => $id_conta,
						'id_banco' => sanitize($_POST['id_banco']),
						'id_cadastro' => sanitize($_POST['id_cadastro']),
						'tipo' => sanitize($_POST['tipo']),
						'nro_documento' => sanitize($_POST['nro_documento']),
						'descricao' => 'DESPESA AUTOMATICA DE VEICULO '.$descricao,
						'valor' => converteMoeda(($_POST['valor'])),
						'valor_pago' => converteMoeda(($_POST['valor'])),
						'pago' => $pago,	
						'fiscal' => '1',	
						'data_vencimento' => $data_vencimento,	
						'data_pagamento' => $data_pagamento,
						'usuario' => $_SESSION['nomeusuario'],
						'data' => "NOW()"
					  );
					  $id_despesa = self::$db->insert("despesa", $data_despesa);
				  }
				  $data = array(
						'id_descricao' => sanitize($_POST['id_descricao']), 
						'id_veiculo' => sanitize($_POST['id_veiculo']), 
						'id_motorista' => sanitize($_POST['id_motorista']), 
						'id_cadastro' => sanitize($_POST['id_cadastro']), 
						'km' => sanitize($_POST['km']),
						'valor' => converteMoeda($_POST['valor']),  
						'data_evento' => $data_evento, 
						'id_despesa' => $id_despesa, 
						'observacao' => sanitize($_POST['observacao']), 
						'realizado' => sanitize(post('realizado')), 
						'usuario' => $_SESSION['nomeusuario'],
						'data' => "NOW()"
				  );
				  self::$db->insert("veiculo_eventos", $data);
			  }
			  
              $message = (Filter::$id) ? lang('EVENTOSVEICULO_ALTERADO_OK') : lang('EVENTOSVEICULO_ADICIONADO_OK');

              if (self::$db->affected()) {			  
                  Filter::msgOk($message, "index.php?do=eventos_veiculo&acao=listar");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      } 
	  
	  /**
       * Veiculo::getVeiculoDiarias()
	   *
       * @return
       */
	  public function getVeiculoDiarias($id_veiculo = false, $id_origem = false, $id_destino = false, $tipo = false, $particular = false, $id_motorista = false, $dataini = false, $datafim = false)
      {
		  $wveiculo = ($id_veiculo) ? " AND d.id_veiculo = $id_veiculo " : "";
		  $worigem = ($id_origem) ? " AND d.id_origem = $id_origem " : "";
		  $wdestino = ($id_destino) ? " AND d.id_destino = $id_destino " : "";
		  $wtipo = ($tipo) ? " AND d.tipo = $tipo " : "";
		  $wparticular = ($particular) ? " AND d.particular = $particular " : "";
		  $wmotorista = ($id_motorista) ? " AND d.id_motorista = $id_motorista " : "";
		  $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
          $datafim = ($datafim) ? $datafim : date("d/m/Y");
          $sql = "SELECT d.*, v.placa, v.modelo, u.nome as motorista, o.endereco, o.numero, o.cep, co.nome, de.endereco as end_de, de.numero as num_de, de.cep as cep_de, cd.nome as nome_de " 
		  . "\n FROM veiculo_diaria as d"
		  . "\n LEFT JOIN veiculo as v ON v.id = d.id_veiculo " 
		  . "\n LEFT JOIN usuario as u ON u.id = d.id_motorista " 
		  . "\n LEFT JOIN cadastro_endereco as o ON o.id = d.id_origem " 
		  . "\n LEFT JOIN cadastro as co ON co.id = o.id_cadastro " 
		  . "\n LEFT JOIN cadastro_endereco as de ON de.id = d.id_destino " 
		  . "\n LEFT JOIN cadastro as cd ON cd.id = de.id_cadastro " 
		  . "\n WHERE d.inativo = 0 AND d.data_diaria BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n $wmotorista "
		  . "\n $wparticular "
		  . "\n $wtipo "
		  . "\n $wveiculo "
		  . "\n $worigem "
		  . "\n $wdestino "
		  . "\n ORDER BY d.data_diaria ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Veiculo::processarVeiculoDiaria()
       * TIPO = 1: SAIDA
       * TIPO = 2: CHEGADA
       * PARTICULAR = 1: EMPRESA
       * PARTICULAR = 2: PARTICULAR
       * @return
       */
      public function processarVeiculoDiaria()
      {
		  if (empty($_POST['id_veiculo']))
              Filter::$msgs['id_veiculo'] = lang('MSG_ERRO_VEICULO');		

		  if (empty($_POST['id_motorista']))
              Filter::$msgs['id_motorista'] = lang('MSG_ERRO_MOTORISTA');	

		  if (empty($_POST['id_origem']) and !empty($_POST['particular']))
              Filter::$msgs['id_origem'] = lang('MSG_ERRO_ORIGEM');	

		  if (empty($_POST['id_destino']) and !empty($_POST['particular']))
              Filter::$msgs['id_destino'] = lang('MSG_ERRO_DESTINO');

		  if (empty($_POST['data_diaria']))
              Filter::$msgs['data_diaria'] = lang('MSG_ERRO_DATA');		

		  if (empty($_POST['km_atual']))
              Filter::$msgs['km_atual'] = lang('MSG_ERRO_KM');
		  
		  $id_veiculo = post('id_veiculo');
		  $km_atual = post('km_atual');
		  $km_inicial = $this->getKM($id_veiculo);
		  
		  if($km_atual < $km_inicial and !(Filter::$id)) 
              Filter::$msgs['km_inicial'] = str_replace("[KM_INICIAL]", $km_inicial, lang('MSG_ERRO_KM_INICIAL'));
			
		  if (empty(Filter::$msgs)) {
			  
			  $id_motorista = post('id_motorista');
			  $tipo = (post('tipo'))?post('tipo'):"2";
			  $particular = (post('particular')) ? '1' : '2';
			  $km_diaria = ($km_inicial and  !(Filter::$id)) ? $km_atual - $km_inicial : 0;

			  $uso = ($tipo == '1') ? '1' : '0';

              $data = array(
					'id_veiculo' => $id_veiculo, 
					'id_motorista' => $id_motorista, 
					'id_origem' => sanitize($_POST['id_origem']), 
					'id_destino' => sanitize($_POST['id_destino']), 
					'tipo' => $tipo,
					'particular' => $particular,
					'km_atual' => sanitize(post('km_atual')), 
					'data_diaria' => dataMySQL($_POST['data_diaria']),
					'observacao' => sanitize($_POST['observacao']), 
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
			  );
			  if(Filter::$id) {
				  self::$db->update("veiculo_diaria", $data, "id=" . Filter::$id);
			  } else {
				  $data['km_diaria'] = $km_diaria;
				  Filter::$id = self::$db->insert("veiculo_diaria", $data);
			  }
              $message = (Filter::$id) ? lang('VEICULODIARIA_ALTERADO_OK') : lang('VEICULODIARIA_ADICIONADO_OK');

              if (self::$db->affected()) {				  
				  $data_veiculo = array(
						'id_motorista' => $id_motorista, 
						'id_diaria' => Filter::$id, 
						'uso' => $uso
				  );
				  self::$db->update("veiculo", $data_veiculo, "id=".$id_veiculo);
                  Filter::msgOk($message, "index.php?do=veiculo_diaria&acao=listar");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      } 
	  
      /**
       * Veiculo::processarAjusteDiaria()
       * TIPO = 2: CHEGADA
       * @return
       */
      public function processarAjusteDiaria()
      {
		  if (empty($_POST['id_veiculo']))
              Filter::$msgs['id_veiculo'] = lang('MSG_ERRO_VEICULO');		

		  if (empty($_POST['km_atual']))
              Filter::$msgs['km_atual'] = lang('MSG_ERRO_KM');

		  if (empty($_POST['observacao']))
              Filter::$msgs['observacao'] = lang('MSG_ERRO_OBSERVACAO');
		  
		  $id_veiculo = post('id_veiculo');
		  
          if (empty(Filter::$msgs)) {

              $data = array(
					'id_veiculo' => $id_veiculo, 
					'tipo' => '2',
					'km_atual' => sanitize(post('km_atual')), 
					'data_diaria' => dataMySQL($_POST['data_diaria']),
					'observacao' => sanitize($_POST['observacao']), 
					'usuario' => $_SESSION['nomeusuario'],
					'data' => "NOW()"
			  );
			  $id_diaria = self::$db->insert("veiculo_diaria", $data);
              $message = lang('VEICULODIARIA_AJUSTE_OK');

              if (self::$db->affected()) {				  
				  $data_veiculo = array(
						'id_diaria' => $id_diaria, 
						'uso' => '0'
				  );
				  self::$db->insert("veiculo", $data_veiculo);
                  Filter::msgOk($message, "index.php?do=veiculo_diaria&acao=listar");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      } 
	  
	  /**
       * Veiculo::getKM()
	   *
       * @return
       */
	  public function getKM($id_veiculo)
      {
          $sql = "SELECT MAX(d.km_atual) as km " 
		  . "\n FROM veiculo_diaria as d"
		  . "\n WHERE d.inativo = 0  AND d.id_veiculo = '$id_veiculo' ";
          $row = self::$db->first($sql);

          return ($row) ? $row->km : 0;
      }
	  
	  /**
       * Veiculo::getUltimaDiaria()
	   *
       * @return
       */
	  public function getUltimaDiaria($id_veiculo)
      {
          $sql = "SELECT d.* " 
		  . "\n FROM veiculo_diaria as d"
		  . "\n WHERE d.inativo = 0 AND d.id_veiculo = '$id_veiculo' "
		  . "\n ORDER BY d.data_diaria DESC ";
          $row = self::$db->first($sql);
		  
		  return ($row) ? $row : 0;
      }
  }
?>