<?php
  /**
   * Classe Despesa
   *
   */

  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Despesa
  {
      const uTable = "despesa";
	  private static $db;

      /**
       * Despesa::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
	  }
	  
	  
	  /**
       * Despesa::getCentroCusto()
	   *
       * @return
       */
	  public function getCentroCusto()
      {
          $sql = "SELECT c.* " 
		  . "\n FROM centro_custo as c"
		  . "\n WHERE c.inativo = 0 "
		  . "\n ORDER BY c.centro_custo ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Despesa::processarCentroCusto()
       * 
       * @return
       */
      public function processarCentroCusto()
      {
		if (empty($_POST['centro_custo']))
			Filter::$msgs['centro_custo'] = lang('MSG_ERRO_NOME');	

		if (empty(Filter::$msgs)) {

			$data = array(
				'centro_custo' => sanitize(post('centro_custo')),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );

              (Filter::$id) ? self::$db->update("centro_custo", $data, "id=" . Filter::$id) : self::$db->insert("centro_custo", $data);
              $message = (Filter::$id) ? lang("CENTRO_CUSTO_AlTERADO_OK") : lang("CENTRO_CUSTO_ADICIONADO_OK");

              if (self::$db->affected()) {			  
                  Filter::msgOk($message, "index.php?do=centrocusto&acao=listar");    
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::processarDespesas()
       * id_banco = 0 : RETIRADA EM DINHEIRO DO CAIXA
       * cheque = 1 : PAGAMENTO EM CHEQUE DO BANCO
       * 
       * @return
       */
      public function processarDespesas()
      {
			if (empty($_POST['id_conta']))
				Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');	
				
			if (empty($_POST['id_cadastro']) and empty($_POST['cadastro']))
              Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_FORNECEDOR');
				
			if (empty($_POST['descricao']))
              Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');
				
			if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');
				
			if (empty(Filter::$msgs)) {
				
				$id_cadastro = post('id_cadastro');
				if (empty($_POST['id_cadastro'])) {
					$nome = cleanSanitize(post('cadastro'));
					$data_cadastro = array(
						'nome' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
						'fornecedor' => '1',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$id_cadastro = self::$db->insert("cadastro", $data_cadastro);					
				}
				
				$dias = (empty($_POST['dias'])) ? 30 : $_POST['dias'];
				
				$data_venc = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : post('data_vencimento');
				
				$data_repeticoes = explode('/', $data_venc);	

				$repeticoes = (is_numeric($_POST['repeticoes']) and $_POST['repeticoes'] > 0) ? post('repeticoes') : 1;
				
				for($i=0;$i<$repeticoes;$i++)
				{
					$mes = ($dias == 30) ? $i : 0;
					$dia = ($dias != 30) ? $dias*$i : 0;
					$newData = date('Y-m-d', mktime(0, 0, 0, $data_repeticoes[1] + $mes, $data_repeticoes[0] + $dia, $data_repeticoes[2]) );
					
					$parc = $i+1;
					
					$descricao = sanitize(post('descricao'))." - PARCELA [". $parc . "/".$repeticoes."]";
					
					$data = array(
						'id_empresa' => sanitize(post('id_empresa')),
						'id_conta' => sanitize(post('id_conta')),
						'id_custo' => sanitize(post('id_custo')),
						'id_banco' => sanitize(post('id_banco')),
						'id_cadastro' => $id_cadastro,
						'tipo' => sanitize(post('tipo')),
						'duplicata' => sanitize(post('duplicata')),
						'descricao' => $descricao,
						'valor' => converteMoeda(post('valor')),
						'valor_pago' => converteMoeda(post('valor')),
						'data_vencimento' => $newData,
						'nro_documento' => sanitize(post('nro_documento')),
						'cheque' => sanitize(post('cheque')),
						'fiscal' => sanitize(post('fiscal')),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					  );					  
					  if ($i == 0) {
						if (post('pago') == 1) {
							$data['pago'] = 1;
							$data_pagamento = (empty($_POST['data_pagamento'])) ? $newData : dataMySQL(post('data_pagamento'));
							$data['data_pagamento'] = $data_pagamento;
						}
					  }
					  $id_despesa = self::$db->insert(self::uTable, $data);
					  $data_update = array(
						'agrupar' => $id_despesa
					  );
					  self::$db->update(self::uTable, $data_update, "id=".$id_despesa);
				}
				  
			  $message = lang('FINANCEIRO_DESPESAS_ADICIONADO_OK');
			  if (isset($_POST["novo"])) {
				$redirecionar = "index.php?do=despesa&acao=adicionar&id_despesa=".$id_despesa;
			  } else {				
				$redirecionar = "index.php?do=despesa&acao=despesas&id_despesa=".$id_despesa;
			  }

              if (self::$db->affected()) {
                  Filter::msgOk($message,$redirecionar);
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::processarNotaFiscalDespesas()
       * id_banco = 0 : RETIRADA EM DINHEIRO DO CAIXA
       * cheque = 1 : PAGAMENTO EM CHEQUE DO BANCO
       * 
       * @return
       */
      public function processarNotaFiscalDespesas()
      {				
			if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');
				
			if (empty(Filter::$msgs)) {
				
				$dias = (empty($_POST['dias'])) ? 30 : post('dias');
				
				$id_nota = post('id_nota');
				
				$data_venc = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize(post('data_vencimento'));
				
				$data_repeticoes = explode('/', $data_venc);	

				$repeticoes = (is_numeric(post('repeticoes')) and post('repeticoes') > 0) ? post('repeticoes') : 1;				
				
				for($i=0;$i<$repeticoes;$i++)
				{
					$newData = date('Y-m-d', mktime(0, 0, 0, $data_repeticoes[1] + $i, $data_repeticoes[0], $data_repeticoes[2]) );					
					$parc = $i+1;
					
					$descricao = (post('modelo') == 1) ? 'DESPESA AUTOMATICA DE NOTA FISCAL DE SERVICO - '.post('numero_nota') : 'DESPESA AUTOMATICA DE NOTA FISCAL DE PRODUTO - '.post('numero_nota');
					
					$descricao .= " - PARCELA [". $parc . "/".$repeticoes.")";
					
					$data = array(
						'id_empresa' => sanitize(post('id_empresa')),
						'id_nota' => $id_nota,
						'id_conta' => 30,	
						'id_banco' => sanitize(post('id_banco')),
						'id_cadastro' => sanitize(post('id_cadastro')),
						'tipo' => sanitize(post('tipo')),
						'duplicata' => sanitize(post('duplicata')),
						'descricao' => $descricao,
						'valor' => converteMoeda((post('valor'))),
						'valor_pago' => converteMoeda((post('valor'))),
						'data_vencimento' => $newData,	
						'nro_documento' => sanitize(post('nro_documento')),
						'fiscal' => post('fiscal'),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					  );
					  $id_despesa = self::$db->insert(self::uTable, $data);
					  $data_update = array(
						'agrupar' => $id_despesa
					  );
					  self::$db->update(self::uTable, $data_update, "id=".$id_despesa);
				}
				  
			  $message = lang('FINANCEIRO_DESPESAS_ADICIONADO_OK');
			  $redirecionar = "index.php?do=notafiscal&acao=visualizar&id=".$id_nota;

              if (self::$db->affected()) {
                  Filter::msgOk($message, $redirecionar);
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::processarNovaDespesas()
       * id_banco = 0 : RETIRADA EM DINHEIRO DO CAIXA
       * cheque = 1 : PAGAMENTO EM CHEQUE DO BANCO
       * 
       * @return
       */
      public function processarNovaDespesas()
      {
			if (empty($_POST['id_empresa']))
				Filter::$msgs['id_empresa'] = lang('MSG_ERRO_EMPRESA');	
			
			if (empty($_POST['id_conta']))
				Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');	
				
			if (empty($_POST['id_cadastro']))
              Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_FORNECEDOR');
				
			if (empty($_POST['descricao']))
              Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');
		  
			if (empty($_POST['id_banco']))
              Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');
		  
			if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');

			if (empty(Filter::$msgs)) {
				
				$dias = (empty($_POST['dias'])) ? 30 : $_POST['dias'];
				
				$data_venc = (empty($_POST['data_vencimento'])) ? date('Y-m-d') : dataMySQL(post('data_vencimento'));
				$data_pag = (empty($_POST['data_pagamento'])) ? date('Y-m-d') : dataMySQL(post('data_pagamento'));
				
				$data = array(
						'id_empresa' => sanitize(post('id_empresa')),
						'id_conta' => sanitize(post('id_conta')),
						'id_custo' => sanitize(post('id_custo')),
						'id_banco' => sanitize(post('id_banco')),
						'id_cadastro' => sanitize(post('id_cadastro')),
						'tipo' => sanitize(post('tipo')),
						'descricao' => sanitize(post('descricao')),
						'duplicata' => sanitize(post('duplicata')),
						'valor' => converteMoeda(post('valor')),
						'valor_pago' => converteMoeda(post('valor')),
						'data_vencimento' => $data_venc,
						'data_pagamento' => $data_pag,
						'nro_documento' => $nro_documento,
						'pago' => 1,
						'cheque' => sanitize(post('cheque')),
						'fiscal' => post('fiscal'),
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					  );	
					  
					  $id_despesa = self::$db->insert(self::uTable, $data);
					  $data_update = array(
						'agrupar' => $id_despesa
					  );
					  self::$db->update(self::uTable, $data_update, "id=".$id_despesa);
				
				  
			  $message = lang('FINANCEIRO_DESPESAS_ADICIONADO_OK');

              if (self::$db->affected()) {
				  fecharjanela();
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::editarDespesasPagas()
       * 
       * @return
       */
      public function editarDespesasPagas()
      {
			if (empty($_POST['id_conta']))
				Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');	
				
			if (empty($_POST['descricao']))
              Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');
								
			if (empty(Filter::$msgs)) {			
				
				$data = array(
					'id_empresa' => sanitize(post('id_empresa')),
					'id_cadastro' => sanitize(post('id_cadastro')),
					'tipo' => sanitize(post('tipo')),
					'id_conta' => sanitize(post('id_conta')),
					'id_custo' => sanitize(post('id_custo')),
					'id_banco' => sanitize(post('id_banco')),
					'descricao' => sanitize(post('descricao')),
					'duplicata' => sanitize(post('duplicata')),
					'nro_documento' => sanitize(post('nro_documento')),
					'data_vencimento' => dataMySQL(post('data_vencimento')),
					'data_pagamento' => dataMySQL(post('data_pagamento')),
					'pago' => post('pago'),
					'fiscal' => post('fiscal'),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update(self::uTable, $data, "id=".post('id'));
				  
			  $message = lang('FINANCEIRO_DESPESAS_AlTERADO_OK');
			  
			  $redirecionar = "index.php?do=despesa&acao=despesaspagas";

              if (self::$db->affected()) {
                  Filter::msgOk($message, $redirecionar);
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::agruparDespesas()
       * 
       * @return
       */
      public function agruparDespesas()
      {
			if (empty($_POST['agrupar']))
				Filter::$msgs['agrupar'] = lang('MSG_ERRO_DESPESA');

			if (empty(Filter::$msgs)) {			
				
			  $agrupar = $_POST['agrupar'];
			  $contar = count($agrupar);
			  
			  for ($i=0; $i<$contar; $i++) 
			  {
				$data = array(
					'agrupar' => $agrupar[0],
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update(self::uTable, $data, "id=".$agrupar[$i]);
			  }
				  
			  $message = lang('FINANCEIRO_DESPESAS_AGRUPAR_OK');
			  
			  $redirecionar = "index.php?do=despesa&acao=agrupar";

              if (self::$db->affected()) {
                  Filter::msgOk($message, $redirecionar);
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::agruparPagarDespesasCartoes()
       * 
       * @return
       */
      public function agruparPagarDespesasCartoes()
      {
			if (empty($_POST['agrupar']))
				Filter::$msgs['agrupar'] = lang('MSG_ERRO_DESPESA');
			
			if (empty($_POST['id_banco']))
				Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');				

			if (empty($_POST['data_pagamento']))
				Filter::$msgs['data_pagamento'] = lang('MSG_ERRO_DATA');

			if (empty(Filter::$msgs)) {			
				
			  $agrupar = $_POST['agrupar'];
			  $contar = count($agrupar);
			  
			  for ($i=0; $i<$contar; $i++) 
			  {
				$data = array(				
					'id_banco' => sanitize(post('id_banco')),
					'data_pagamento' => dataMySQL(post('data_pagamento')),
					'pago' => '1',
					'agrupar' => $agrupar[0],
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update(self::uTable, $data, "id=".$agrupar[$i]);
			  }
				  
			  $message = lang('FINANCEIRO_DESPESAS_AGRUPAR_OK');
			  
			  $redirecionar = "index.php?do=despesa&acao=despesaspagas";

              if (self::$db->affected()) {
                  Filter::msgOk($message, $redirecionar);
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::editarDespesas()
       * 
       * @return
       */
      public function editarDespesas()
      {
			if (empty($_POST['id_conta']))
				Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');	
				
			if (empty($_POST['descricao']))
              Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');

			if (empty($_POST['data_vencimento']))
				Filter::$msgs['data_vencimento'] = lang('MSG_ERRO_DATA');

			if (empty(Filter::$msgs)) {			
				
				$data = array(
					'id_empresa' => sanitize(post('id_empresa')),
					'id_cadastro' => sanitize(post('id_cadastro')),
					'tipo' => sanitize(post('tipo')),
					'id_conta' => sanitize(post('id_conta')),
					'id_custo' => sanitize(post('id_custo')),
					'id_banco' => sanitize(post('id_banco')),
					'descricao' => sanitize(post('descricao')),	
					'duplicata' => sanitize(post('duplicata')),	
					'valor' => converteMoeda(post('valor')),			
					'valor_pago' => converteMoeda(post('valor')),			
					'cheque' => sanitize(post('cheque')),
					'fiscal' => post('fiscal'),		
					'nro_documento' => sanitize(post('nro_documento')),
					'data_vencimento' => dataMySQL(post('data_vencimento')),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update(self::uTable, $data, "id=".post('id'));
				  
			  $message = lang('FINANCEIRO_DESPESAS_AlTERADO_OK');
			  
			  $id_nota = getValue("id_nota", "despesa", "id = ".post('id'));
			  if($id_nota) {
				$redirecionar = "index.php?do=notafiscal&acao=visualizar&id=".$id_nota;				  
			  } else {
				$redirecionar = "index.php?do=despesa&acao=despesas";				  
			  }

              if (self::$db->affected()) {
                  Filter::msgOk($message, $redirecionar);
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::duplicarDespesas()
       * 
       * @return
       */
      public function duplicarDespesas()
      {
			if (empty($_POST['id_conta']))
				Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');	
				
			if (empty($_POST['descricao']))
              Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');

			if (empty($_POST['data_vencimento']))
				Filter::$msgs['data_vencimento'] = lang('MSG_ERRO_DATA');

			if (empty(Filter::$msgs)) {			
				
				$data = array(
					'id_empresa' => sanitize(post('id_empresa')),
					'id_cadastro' => sanitize(post('id_cadastro')),
					'tipo' => sanitize(post('tipo')),
					'id_conta' => sanitize(post('id_conta')),
					'id_custo' => sanitize(post('id_custo')),
					'id_banco' => sanitize(post('id_banco')),
					'descricao' => sanitize(post('descricao')),	
					'duplicata' => sanitize(post('duplicata')),	
					'valor' => converteMoeda(post('valor')),			
					'valor_pago' => converteMoeda(post('valor')),			
					'cheque' => sanitize(post('cheque')),				
					'nro_documento' => sanitize(post('nro_documento')),
					'data_vencimento' => dataMySQL(post('data_vencimento')),
					'fiscal' => post('fiscal'),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_despesa = self::$db->insert(self::uTable, $data);
				$data_update = array(
					'agrupar' => $id_despesa
				);
				 self::$db->update(self::uTable, $data_update, "id=".$id_despesa);
				  
			  $message = lang('FINANCEIRO_DESPESAS_DUPLICADA_OK');
			$redirecionar = "index.php?do=despesa&acao=despesas";		

              if (self::$db->affected()) {
                  Filter::msgOk($message, $redirecionar);
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::processarPagamentoDespesas()
       * 
       * @return
       */
      public function processarPagamentoDespesas($pag)
      {
		if (empty($_POST['id_banco']))
			Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');	

		if (empty(Filter::$msgs)) {	
			
			$novo = false;
			$data_pagamento = (empty($_POST['data_pagamento'])) ? "NOW()" : dataMySQL(post('data_pagamento'));
			$valor_pago = converteMoeda($_POST['valor_pago']);
			$valor = getValue("valor", "despesa", "id=".$pag);
			if($valor_pago) {
				if($valor_pago < $valor) {
					$valor = $valor - $valor_pago;
					$novo = true;
				}
			} else {
				$valor_pago = $valor;
			}

			$data = array(
					'id_banco' => sanitize(post('id_banco')),				
					'nro_documento' => sanitize(post('nro_documento')),				
					'cheque' => sanitize(post('cheque')),				
					'data_pagamento' => $data_pagamento,
					'valor_pago' => $valor_pago,
					'pago' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );

              self::$db->update(self::uTable, $data, "id=".$pag);
              $message = lang('FINANCEIRO_DESPESAS_PAGAMENTO_OK');

              if (self::$db->affected()) {
				  if($novo) {
                  Filter::msgOk($message, "index.php?do=despesa&acao=adicionar&novo=1&id_despesa=".$pag."&valor=".$valor);
				  } else {
                  Filter::msgOk($message, "index.php?do=despesa&acao=despesas");
				  }				  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::getContasDespesas()
       * 
       * @return
       */
      public function getContasDespesas($mes_ano)
      {		  
		  $sql = "SELECT pai.id, pai.conta " 
		  . "\n FROM despesa as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta"
		  . "\n LEFT JOIN conta as pai ON pai.id = c.id_pai" 
		  . "\n WHERE f.inativo = 0 AND DATE_FORMAT(f.data_vencimento, '%m/%Y') = '$mes_ano' "
		  . "\n GROUP BY pai.conta"
		  . "\n ORDER BY pai.conta";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Despesa::getDespesas()
       * 
       * @return
       */
      public function getDespesas($id_empresa = false, $dataini = false, $datafim = false, $id_banco = false, $id_centro_custo = false, $id_conta = false , $id_cadastro = false, $valor = false)
	  {
          $dataini = ($dataini) ? $dataini : date('d/m/Y', strtotime('-5 days')); 
          $datafim = ($datafim) ? $datafim : date('d/m/Y', strtotime('35 days'));
		  $where = "AND f.data_vencimento BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')";
		  $whereempresa = ($id_empresa) ? "AND f.id_empresa = $id_empresa " : "";
		  $wherebanco = ($id_banco) ? "AND f.id_banco = $id_banco" : "";
		  $wherecentro = ($id_centro_custo) ? "AND f.id_custo = $id_centro_custo" : "";
		  $wherecadastro = ($id_cadastro) ? "AND f.id_cadastro = $id_cadastro" : "";
		  $where = ($id_cadastro) ? "" : $where;
		  $whereconta = ($id_conta) ? "AND (c.id = $id_conta OR c.id_pai = $id_conta)" : "";
		  $wherevalor = ($valor) ? "AND f.valor = ".converteMoeda($valor) : "";
		  $where = ($valor) ? "" : $where;
		  		  
		  $sql = "SELECT f.id, f.id_conta, f.id_custo, u.centro_custo, f.id_banco, c.conta, b.banco, fr.nome as cadastro, f.descricao, f.valor, f.cheque, f.fiscal, t.tipo, f.id_cadastro, f.data_vencimento, f.nro_documento, f.data_vencimento < CURDATE() as atrasado, f.data_vencimento = CURDATE() as hoje, DATE_FORMAT(f.data_vencimento, '%Y%m%d') as controle, e.nome as empresa" 
		  . "\n FROM despesa as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta" 
		  . "\n LEFT JOIN centro_custo as u ON u.id = f.id_custo" 
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco" 
		  . "\n LEFT JOIN empresa as e ON e.id = f.id_empresa" 
		  . "\n LEFT JOIN cadastro as fr ON fr.id = f.id_cadastro" 
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo" 
		  . "\n WHERE f.inativo = 0 AND f.pago = 0 "
		  . "\n $where"
		  . "\n $whereempresa"
		  . "\n $wherebanco"
		  . "\n $wherecentro"
		  . "\n $wherecadastro"
		  . "\n $whereconta"
		  . "\n $wherevalor"
		  . "\n ORDER BY f.data_vencimento";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Despesa::getListaDespesasDRE()
       * 
       * @return
       */
      public function getListaDespesasDRE($id_conta, $mes, $ano, $id_empresa = 0)
      {		  
		 $wempresa = ($id_empresa) ? "AND f.id_empresa = $id_empresa " : "";
		 $sql = "SELECT f.id, c.conta, f.id_custo, u.centro_custo, b.banco, fr.nome as fornecedor, f.agrupar, f.descricao, f.valor, f.cheque, f.data_vencimento, f.data_pagamento, f.conciliado, f.nro_documento, e.nome as empresa, DATE_FORMAT(f.data_pagamento, '%Y%m%d') as controle" 
		  . "\n FROM despesa as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta" 
		  . "\n LEFT JOIN centro_custo as u ON u.id = f.id_custo" 
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco" 
		  . "\n LEFT JOIN cadastro as fr ON fr.id = f.id_cadastro" 
		  . "\n LEFT JOIN empresa as e ON e.id = f.id_empresa" 
		  . "\n WHERE f.inativo = 0 AND f.pago = 1 $wempresa "
		  . "\n AND f.id_conta = $id_conta "
		  . "\n AND MONTH(f.data_pagamento) = $mes "
		  . "\n AND YEAR(f.data_pagamento) = $ano "
		  . "\n ORDER BY f.data_pagamento";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	} 
	  
	  /**
       * Despesa::getDespesasNotas()
       * 
       * @return
       */
      public function getDespesasNota($id_nota = false)
      {
          if($id_nota){			  
			  $sql = "SELECT f.id, f.id_conta, f.id_banco, c.conta, b.banco, fr.nome as cadastro, f.duplicata, f.descricao, f.valor, f.id_cadastro, f.cheque, f.fiscal, f.data_pagamento, f.data_vencimento, f.nro_documento, f.data_vencimento < CURDATE() as atrasado, f.data_vencimento = CURDATE() as hoje, DATE_FORMAT(f.data_vencimento, '%Y%m%d') as controle, e.nome as empresa, f.pago, f.inativo " 
			  . "\n FROM despesa as f" 
			  . "\n LEFT JOIN conta as c ON c.id = f.id_conta" 
			  . "\n LEFT JOIN banco as b ON b.id = f.id_banco" 
			  . "\n LEFT JOIN empresa as e ON e.id = f.id_empresa" 
			  . "\n LEFT JOIN cadastro as fr ON fr.id = f.id_cadastro" 
			  . "\n WHERE f.inativo = 0 AND f.id_nota = $id_nota "
			  . "\n ORDER BY f.data_vencimento ";
			  $row = self::$db->fetch_all($sql);

			  return ($row) ? $row : 0;
			  
		  } else {
			  return 0;
		  }	
      }
	  
	  /**
       * Despesa::getDetalhesDespesa()
       * 
       * @return
       */
      public function getDetalhesDespesa($id_despesa = 0)
      {		  
		  $sql = "SELECT f.id, c.conta, f.id_custo, u.centro_custo, b.banco, fr.nome as cadastro, f.agrupar, f.descricao, f.valor, f.data, f.valor_pago, f.cheque, f.fiscal, f.data_vencimento, f.data_pagamento, f.conciliado, f.nro_documento, f.duplicata, e.nome as empresa, f.id_nota, f.pago, f.usuario, f.id_cadastro, f.id_empresa, f.tipo, t.tipo as pagamento " 
		  . "\n FROM despesa as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta" 
		  . "\n LEFT JOIN centro_custo as u ON u.id = f.id_custo" 
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco" 
		  . "\n LEFT JOIN empresa as e ON e.id = f.id_empresa" 
		  . "\n LEFT JOIN cadastro as fr ON fr.id = f.id_cadastro" 
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo " 
		  . "\n WHERE f.id = '$id_despesa' ";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row : 0;
	} 
	  
	  /**
       * Despesa::getDespesasPagas()
       * 
       * @return
       */
      public function getDespesasPagas($id_empresa = false, $dataini = false, $datafim = false, $id_banco = false, $id_centro_custo = false, $id_conta = false , $id_cadastro = false, $valor = false, $numero = false)
      {
          $dataini = ($dataini) ? $dataini : date('d/m/Y', strtotime('-15 days')); 
          $datafim = ($datafim) ? $datafim : date("d/m/Y");
		  $where = "AND f.data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')";
		  $whereempresa = ($id_empresa) ? "AND f.id_empresa = $id_empresa " : "";
		  $wherebanco = ($id_banco) ? "AND f.id_banco = $id_banco" : "";
		  $wherecentro = ($id_centro_custo) ? "AND f.id_custo = $id_centro_custo" : "";
		  //PARA NÃO APARECER AS TRANSFERENCIAS BANCARIAS
		  //$wherecadastro = ($id_cadastro) ? "AND f.id_cadastro = $id_cadastro" : "AND f.id_cadastro > 0 ";
		  $wherecadastro = ($id_cadastro) ? "AND f.id_cadastro = $id_cadastro" : "";
		  $where = ($id_cadastro) ? "" : $where;
		  $whereconta = ($id_conta) ? "AND (c.id = $id_conta OR c.id_pai = $id_conta)" : "";
		  $wherevalor = ($valor) ? "AND f.valor = ".converteMoeda($valor) : "";
		  $where = ($valor) ? "" : $where;
		  $wherenumero = ($numero) ? "AND f.nro_documento like '%$numero%'" : "";
		  $where = ($numero) ? "" : $where;
		  
		  $sql = "SELECT f.id, f.id_receita, c.conta, f.id_custo, u.centro_custo, b.banco, fr.nome as cadastro, f.agrupar, f.descricao, f.valor, f.valor_pago, f.cheque, f.fiscal, f.data_vencimento, f.data_pagamento, f.conciliado, f.nro_documento, DATE_FORMAT(f.data_pagamento, '%Y%m%d') as controle, e.nome as empresa, f.id_cadastro " 
		  . "\n FROM despesa as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta" 
		  . "\n LEFT JOIN centro_custo as u ON u.id = f.id_custo" 
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco" 
		  . "\n LEFT JOIN empresa as e ON e.id = f.id_empresa" 
		  . "\n LEFT JOIN cadastro as fr ON fr.id = f.id_cadastro" 
		  . "\n WHERE f.inativo = 0 AND f.pago = 1 "
		  . "\n $where"
		  . "\n $whereempresa"
		  . "\n $wherebanco"
		  . "\n $wherecentro"
		  . "\n $wherecadastro"
		  . "\n $whereconta"
		  . "\n $wherevalor"
		  . "\n $wherenumero"
		  . "\n ORDER BY f.data_pagamento";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	} 
	  
	  /**
       * Despesa::getDespesasCartoes()
       * 
       * @return
       */
      public function getDespesasCartoes($numero = false)
      {
		  
		  $sql = "SELECT f.id, c.conta, b.banco, fr.nome as cadastro, f.agrupar, f.descricao, f.valor, f.valor_pago, f.cheque, f.fiscal, f.data_vencimento, f.data_pagamento, f.conciliado, f.nro_documento, DATE_FORMAT(f.data_vencimento, '%Y%m%d') as controle, e.nome as empresa, f.id_cadastro " 
		  . "\n FROM despesa as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta" 
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco" 
		  . "\n LEFT JOIN empresa as e ON e.id = f.id_empresa" 
		  . "\n LEFT JOIN cadastro as fr ON fr.id = f.id_cadastro" 
		  . "\n WHERE f.inativo = 0 AND f.pago = 0 "
		  . "\n AND f.nro_documento = '$numero' "
		  . "\n ORDER BY f.data_vencimento";
		  //echo $sql;
		  $row = ($numero) ? self::$db->fetch_all($sql) : null;

          return ($row) ? $row : 0;
	} 
	  
	  /**
       * Despesa::getDespesasCadastro()
       * 
       * @return
       */
      public function getDespesasCadastro($id_cadastro = 0)
      {		  
		  $sql = "SELECT f.id, c.conta, f.id_custo, u.centro_custo, b.banco, fr.nome as cadastro, f.agrupar, f.descricao, f.valor, f.valor_pago, f.cheque, f.fiscal, f.data_vencimento, f.id_cadastro, f.data_pagamento, f.conciliado, f.nro_documento, f.pago, f.inativo, DATE_FORMAT(f.data_pagamento, '%Y%m%d') as controle, e.nome as empresa" 
		  . "\n FROM despesa as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta" 
		  . "\n LEFT JOIN centro_custo as u ON u.id = f.id_custo" 
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco" 
		  . "\n LEFT JOIN empresa as e ON e.id = f.id_empresa" 
		  . "\n LEFT JOIN cadastro as fr ON fr.id = f.id_cadastro" 
		  . "\n WHERE f.inativo = 0 AND f.id_cadastro = '$id_cadastro' "
		  . "\n ORDER BY f.data_pagamento";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	} 
	
	  /**
       * Despesa::getDespesasCaixa()
       * 
       * @return
       */
      public function getDespesasCaixa($dataini = false, $datafim = false, $isGerencia)
      {
          $dataini = ($dataini) ? $dataini : date('d/m/Y', strtotime('-15 days')); 
          $datafim = ($datafim) ? $datafim : date("d/m/Y");
		  $where = "AND f.data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')";
		  $wcaixa = (!$isGerencia) ? "AND cx.id_abrir = ".$_SESSION['uid'] : "";
		  
		 $sql = "SELECT f.id, c.conta, fr.nome as fornecedor, f.id_caixa, f.id_conta, f.descricao, f.valor, f.cheque, f.fiscal, f.data_vencimento, f.data_pagamento, f.nro_documento, DATE_FORMAT(f.data_pagamento, '%Y%m%d') as controle"
		  . "\n FROM despesa as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta"
		  . "\n LEFT JOIN cadastro as fr ON fr.id = f.id_cadastro" 
		  . "\n LEFT JOIN caixa as cx ON cx.id = f.id_caixa "
		  . "\n WHERE f.inativo = 0 AND f.id_caixa > 0 "
		  . "\n $where $wcaixa"
		  . "\n ORDER BY f.data_pagamento, f.id_conta";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Despesa::getDespesasDia()
       * 
       * @return
       */
      public function getDespesasDia($consdata = false)
      {
          $data = ($consdata) ? $consdata : date("d/m/Y");	  
		  
		  $sql = "SELECT f.id, c.conta, f.id_custo, u.centro_custo, b.banco, fr.nome, f.descricao, f.valor, f.data_pagamento" 
		  . "\n FROM despesa as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta"
		  . "\n LEFT JOIN centro_custo as u ON u.id = f.id_custo" 
		  . "\n LEFT JOIN cadastro as fr ON fr.id = f.id_cadastro" 
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco"
		  . "\n WHERE f.inativo = 0  AND f.id_cadastro > 0 "
		  . "\n AND DATE_FORMAT(f.data_pagamento, '%d/%m/%Y') = '$data'"
		  . "\n ORDER BY f.data_pagamento";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Despesa::totalDespesasPagas()
       * 
       * @return
       */
      public function totalDespesasPagas($consdata = false)
      {		  
          $data = ($consdata) ? $consdata : date("d/m/Y");
		  
		  $sql = "SELECT SUM(valor) as total FROM despesa"
		  . "\n WHERE pago = 1 AND inativo = 0  AND id_cadastro = 0 AND DATE_FORMAT(data_pagamento, '%d/%m/%Y') = '$data'";
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Despesa::totalDespesasMes()
       * 
       * @return
       */
      public function totalDespesasMes($consdata = false, $id_conta = false, $pago = 1)
      {		  
          $data = ($consdata) ? $consdata : date("m/Y");
          $conta = ($id_conta) ? "AND id_conta = '$id_conta'" : "";
		  
		  $sql = "SELECT SUM(valor) as total FROM despesa"
		  . "\n WHERE inativo = 0 AND pago = $pago  AND id_cadastro = 0 AND DATE_FORMAT(data_vencimento, '%m/%Y') = '$data' $conta";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Despesa::totalDespesasPaiMes()
       * 
       * @return
       */
      public function totalDespesasPaiMes($id_conta, $consdata = false, $pago = 1)
      {		  
          $data = ($consdata) ? $consdata : date("m/Y");
		  
		  $sql = "SELECT SUM(valor) as total FROM despesa as f, conta as c"
		  . "\n WHERE f.id_conta = c.id AND c.id_pai = $id_conta AND inativo = 0 AND pago = $pago AND DATE_FORMAT(data_vencimento, '%m/%Y') = '$data'";
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }  
	  
	  /**
       * Despesa::getDespesasDRE()
       * 
       * @return
       */
      public function getDespesasDRE($dataini = false, $datafim = false, $id_conta = false)
      {
          $dataini = ($dataini) ? $dataini : date('01/m/Y'); 
          $datafim = ($datafim) ? $datafim : date("31/m/Y");
		  $where = "AND f.data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')";
		  $whereconta = ($id_conta) ? "AND (c.id = $id_conta OR c.id_pai = $id_conta)" : "";
		  
		  
		  $sql = "SELECT f.id, c.conta, b.banco, fr.nome as cadastro, f.agrupar, f.descricao, f.valor, f.cheque, f.fiscal, f.data_vencimento, f.data_pagamento, f.nro_documento, DATE_FORMAT(f.data_pagamento, '%Y%m%d') as controle" 
		  . "\n FROM despesa as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta" 
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco" 
		  . "\n LEFT JOIN cadastro as fr ON fr.id = f.id_cadastro" 
		  . "\n WHERE f.inativo = 0 AND f.pago = 1 AND c.dre = 1 AND f.id_cadastro > 0 " 
		  . "\n $where" 
		  . "\n $whereconta" 
		  . "\n ORDER BY f.data_pagamento ASC ";
		  
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	} 
	  
	  /**
       * Despesa::getCaixaDinheiro()
	   *
       * @return
       */
	  public function getCaixaDinheiro($id_caixa)
      {
         $sql = "SELECT SUM(f.valor_pago) AS valor_pago " 
		  . "\n FROM cadastro_financeiro as f "
		  . "\n WHERE f.id_caixa = $id_caixa AND f.inativo = 0 AND f.tipo IN (SELECT id FROM tipo_pagamento WHERE inativo=0 AND id_categoria=1) ";
          $row = self::$db->first($sql);
		  
		  $sql_crediario = "SELECT SUM(c.valor_pago) AS valor_pago" 
		  . "\n FROM cadastro_crediario_pagamentos AS c "
		  . "\n where c.id_caixa = $id_caixa AND c.inativo=0 AND c.tipo_pagamento IN (SELECT id FROM tipo_pagamento WHERE inativo=0 AND id_categoria=1)";
          $row_crediario = self::$db->first($sql_crediario);

		  $v1 = ($row->valor_pago) ? floatval($row->valor_pago) : 0;
		  $v2 = ($row_crediario->valor_pago) ? floatval($row_crediario->valor_pago) : 0;
		  
          return ($v1+$v2);
      }
	  
	  /**
       * Despesa::processarRetirarCaixa()
       * 
       * @return
       */
      public function processarRetirarCaixa()
      {
			if (empty($_POST['id_conta']) and empty($_POST['sangria']))
				Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');	
				
			if (empty($_POST['id_cadastro']) and empty($_POST['sangria']))
              Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_FORNECEDOR');
				
			if (empty($_POST['descricao']) and empty($_POST['sangria']))
              Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');
		  
			if ($_POST['id_banco'] and empty($_POST['sangria']))
              Filter::$msgs['id_banco'] = lang('MSG_ERRO_SAGRIA'); 										   
				
			if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');
			
			$id_caixa = intval(post('id_caixa'));
			
			$sql = "SELECT SUM(d.valor) AS total " 
			. "\n FROM despesa as d "
			. "\n WHERE d.id_caixa = '$id_caixa' AND d.inativo = 0 AND d.id_cadastro > 0 AND d.pago = 1 ";
			$row = self::$db->first($sql);
			$valor_retirada = ($row) ? $row->total : 0;
			
			$valor_dinheiro = $this->getCaixaDinheiro($id_caixa);
						
			$totaldinheiro = $valor_dinheiro - $valor_retirada;
			
			$valor = converteMoeda(post('valor'));
			$resultado = ($valor > $totaldinheiro);
	
			if ($resultado)
              Filter::$msgs['saldo'] = lang('MSG_ERRO_RETIRADA');

		if (empty(Filter::$msgs)) {	

			$id_cadastro = (empty($_POST['sangria'])) ? post('id_cadastro') : 0;
			$descricao = (empty($_POST['descricao'])) ? 'RETIRADA PARA SANGRIA' : sanitize(post('descricao'));
			
			$data = array(		
				'id_conta' => sanitize(post('id_conta')),		
				'id_caixa' => $id_caixa,
				'id_cadastro' => $id_cadastro,	
				'tipo' => 1,
				'descricao' => $descricao,					
				'valor' => converteMoeda(post('valor')),			
				'valor_pago' => converteMoeda(post('valor')),		
				'data_vencimento' => "NOW()",
				'data_pagamento' => "NOW()",
				'pago' => '1',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
            $id_despesa = self::$db->insert(self::uTable, $data);
			$data_update = array(
				'agrupar' => $id_despesa
			);
			self::$db->update(self::uTable, $data_update, "id=".$id_despesa);
            $message = lang('FINANCEIRO_DESPESAS_PAGAMENTO_OK');
			
			$sucesso = self::$db->affected();
			
			$id_banco = sanitize(post('id_banco'));
			if ($id_banco) {
				$nome_banco = getValue("banco","banco","id = ".$id_banco);	
				$descricao = "TRANSFERENCIA DE SANGRIA DO CAIXA [".$id_caixa."] PARA O BANCO ".$nome_banco;
				
				$data_receita = array(
					'id_empresa' => session('idempresa'),	
					'id_conta' => 19,
					'id_banco' => $id_banco,
					'id_caixa' => $id_caixa,
					'descricao' => $descricao,
					'valor' => converteMoeda(post('valor')),
					'valor_pago' => converteMoeda(post('valor')),
					'data_pagamento' => ($_POST['data_transferencia']) ? dataMySQL(post('data_transferencia')) : "NOW()",
					'data_recebido' => ($_POST['data_transferencia']) ? dataMySQL(post('data_transferencia')) : "NOW()",
					'tipo' => '1', 
					'pago' => '1', 
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("receita", $data_receita);
			}

            if ($sucesso) {
                Filter::msgOk($message, "index.php?do=caixa&acao=listar");	
            } else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::getCaixaRetirada()
	   *
       * @return
       */
	  public function getCaixaRetirada($id_caixa)
      {
         $sql = "SELECT SUM(d.valor) AS valor " 
		  . "\n FROM despesa as d "
		  . "\n WHERE d.id_caixa = $id_caixa AND d.inativo = 0 AND d.pago = 1 ";
          $row = self::$db->first($sql);

          return ($row) ? $row->valor : 0;
      }
	  
	  /**
       * Despesa::getCaixaListaRetirada()
       * 
       * @return
       */
      public function getCaixaListaRetirada($id_caixa)
      {		  
		  $sql = "SELECT d.id_caixa, d.id_conta, d.id_cadastro, d.descricao, d.valor, f.nome as cadastro, c.conta  " 
		  . "\n FROM despesa as d" 
		  . "\n LEFT JOIN conta as c ON c.id = d.id_conta"
		  . "\n LEFT JOIN cadastro as f ON f.id = d.id_cadastro" 
		  . "\n WHERE d.id_caixa = $id_caixa AND d.inativo = 0 AND d.pago = 1 "
		  . "\n ORDER BY f.nome ";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Despesa::processarDebitoBanco()
       * 
       * @return
       */
      public function processarDebitoBanco()
      {
			if (empty($_POST['id_conta']))
				Filter::$msgs['id_conta'] = lang('MSG_ERRO_CONTA');	
				
			if (empty($_POST['id_banco']))
              Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');
				
			if (empty($_POST['id_cadastro']))
              Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_FORNECEDOR');
				
			if (empty($_POST['descricao']))
              Filter::$msgs['descricao'] = lang('MSG_ERRO_DESCRICAO');
				
			if (empty($_POST['valor']))
              Filter::$msgs['valor'] = lang('MSG_ERRO_VALOR');

		if (empty(Filter::$msgs)) {	
		
			$data = array(					
				'id_banco' => sanitize(post('id_banco')),				
				'id_conta' => sanitize(post('id_conta')),				
				'id_custo' => sanitize(post('id_custo')),				
				'id_cadastro' => sanitize(post('id_cadastro')),			
				'tipo' => sanitize(post('tipo')),			
				'descricao' => sanitize(post('descricao')),				
				'valor' => converteMoeda(post('valor')),				
				'valor_pago' => converteMoeda(post('valor')),				
				'data_vencimento' => "NOW()",
				'data_pagamento' => "NOW()",
				'pago' => '1',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
            $id_despesa = self::$db->insert(self::uTable, $data);	
			$data_update = array(
				'agrupar' => $id_despesa
			);
			self::$db->update(self::uTable, $data_update, "id=".$id_despesa);
            $message = lang('BANCO_TRANSACAO_OK');

            if (self::$db->affected()) {
                Filter::msgOk($message, "index.php?do=banco&acao=listar");	
            } else
				Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();
      }
	  
	  /**
       * Despesa::totalDebitoBanco()
       * 
       * @return
       */
      public function totalDebitoBanco($id_banco)
      {		  
	  
		  $sql = "SELECT SUM(valor) as total FROM despesa "
		  . "\n WHERE id_banco = '$id_banco' AND pago = 1 AND inativo = 0";
		  $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Despesa::getDREMes()
       * 
       * @return
       */
      public function getDREMes($mes_ano = 0, $id_empresa = 0)
      {
		  $wempresa = ($id_empresa) ? "AND d.id_empresa = $id_empresa" : "";
		  $sql = "SELECT c.id, c.id_pai, p.conta AS conta_pai, c.conta, SUM(d.valor) as total, SUM(m.meta) as meta " 
		  . "\n FROM conta AS c" 
		  . "\n LEFT JOIN conta AS p ON p.id = c.id_pai" 
		  . "\n LEFT JOIN despesa AS d ON c.id = d.id_conta AND d.inativo = 0 " 
		  . "\n 	$wempresa " 
		  . "\n 	AND d.pago = 1 " 
		  . "\n 	AND DATE_FORMAT(d.data_pagamento, '%m/%Y') = '$mes_ano' " 
		//  . "\n 	AND d.id_cadastro > 0 " 
		  . "\n LEFT JOIN conta_meta AS m ON c.id = m.id_conta  "
		  . "\n 	AND DATE_FORMAT(m.mes_ano, '%m/%Y') = '$mes_ano' " 
		  . "\n WHERE  c.id_pai IS NOT NULL AND c.tipo = 'D' AND p.dre = 1 AND c.dre = 1 " 
		  . "\n GROUP BY c.id_pai , c.id " 
		  . "\n ORDER BY p.ordem, c.ordem, p.conta , c.conta ASC ";
		  
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	  } 
	  
	  /**
       * Despesa::getDREPrevisaoMes()
       * 
       * @return
       */
      public function getDREPrevisaoMes($mes_ano = 0)
      {
		  $sql = "SELECT c.id, c.id_pai, p.conta AS conta_pai, c.conta, SUM(d.valor) as total, SUM(m.meta) as meta " 
		  . "\n FROM conta AS c" 
		  . "\n LEFT JOIN conta AS p ON p.id = c.id_pai" 
		  . "\n LEFT JOIN despesa AS d ON c.id = d.id_conta AND d.inativo = 0 "
		  . "\n 	AND DATE_FORMAT(d.data_vencimento, '%m/%Y') = '$mes_ano' " 
		  //. "\n 	AND d.id_cadastro > 0 " 
		  . "\n LEFT JOIN conta_meta AS m ON c.id = m.id_conta  "
		  . "\n 	AND DATE_FORMAT(m.mes_ano, '%m/%Y') = '$mes_ano' " 
		  . "\n WHERE  c.id_pai IS NOT NULL AND c.tipo = 'D' AND p.dre = 1 AND c.dre = 1 " 
		  . "\n GROUP BY c.id_pai , c.id " 
		  . "\n ORDER BY p.ordem, c.ordem, p.conta , c.conta ASC ";
		  
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	  } 
	  
	  /**
       * Despesa::getContas()
       * 
       * @return
       */
      public function getContas()
      {		  
		  $sql = "SELECT c.id, c.id_pai, p.conta AS conta_pai, c.conta " 
		  . "\n FROM conta AS c" 
		  . "\n LEFT JOIN conta AS p ON p.id = c.id_pai" 
		  . "\n WHERE c.exibir = 1 AND c.id_pai IS NOT NULL AND c.tipo = 'D' AND p.dre = 1 AND c.dre = 1 " 
		  . "\n ORDER BY p.ordem, c.ordem, p.conta , c.conta ASC ";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
	}
	  
	  /**
       * Despesa::getDespesaExtrato()
       * 
       * @return
       */
      public function getDespesaExtrato($descricao, $id_banco)
      {		  
		  $sql = "SELECT d.id, d.id_empresa, d.id_conta, d.id_cadastro, d.tipo " 
		  . "\n FROM despesa AS d" 
		  . "\n WHERE d.inativo = 0 AND d.descricao = '$descricao' AND d.id_banco = '$id_banco' "
		  . "\n ORDER BY id DESC " ;
		  //echo $sql;
		  $row = self::$db->first($sql);

          return ($row) ? $row : 0;
	}
  }
?>