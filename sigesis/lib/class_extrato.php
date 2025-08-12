<?php
  /**
   * Classe Extrato
   *
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Extrato
  {
	  private static $db;

      /**
       * Extrato::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }
	  
	  /**
       * Extrato::processarArquivoBanco()
       * 
       * @return
       */
      public function processarArquivoBanco()
      {	

		  if (empty($_POST['id_banco']))
              Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');
		  
			if (empty(Filter::$msgs)) {
				$id_banco = post('id_banco');
				$codigo_banco = getValue("codigo","banco","id = ".$id_banco);
				self::$db->delete("extrato_bancos", "id_banco=" . $id_banco);
				foreach($_POST as $nome_campo => $valor){
					$valida = strpos($nome_campo, "tmpname");
					if($valida) {
						$nomearquivo = explode(".",$valor);
						$extensao = $nomearquivo[1];
						if(strtolower($extensao) == 'ofx') { // ARQUIVO OFX
							$ponteiro = fopen (UPLOADS.$valor, "r");
							if ($ponteiro == false) die(lang('FORM_ERROR13'));
							while (!feof ($ponteiro)) {
								$linha = fgets($ponteiro, 4096);
								if (strstr($linha, "<STMTTRN>") !== false) {
									$txn = array();
								}
								elseif (strstr($linha, "<TRNTYPE>") !== false) {
									$txn['tipo'] = sanitize($linha);
								}
								elseif (strstr($linha, "<DTPOSTED>") !== false) {
									$txn['data'] = sanitize($linha);
								}
								elseif (strstr($linha, "<TRNAMT>") !== false) {
									$txn['valor'] = sanitize($linha);
								}
								elseif (strstr($linha, "<NAME>") !== false) {
									$txn['nome'] = sanitize($linha);
								}
								elseif (strstr($linha, "<MEMO>") !== false) {
									$txn['historico'] = sanitize($linha);
								}
								elseif (strstr($linha, "<FITID>") !== false) {
									$txn['id'] = sanitize($linha);
								}
								elseif (strstr($linha, "<REFNUM>") !== false) {
									$txn['refnum'] = sanitize($linha);
								}
								elseif (strstr($linha, "<CHECKNUM>") !== false) {
									$txn['checknum'] = sanitize($linha);
								}
								// End of transaction.
								elseif (strstr($linha, "</STMTTRN>") !== false) {
									$txns[]= $txn;
								}
							}
							fclose ($ponteiro);
							if($txns) {
								foreach($txns as $txrow) {
									$valor = $txrow['valor'];
									$valor = str_replace(",",".",$valor);
									$data_extrato = dataExtrato($txrow['data']);
									$valor = floatval($valor);
									if($txrow['tipo'] == 'OTHER') {
										$tipo = ($valor < 0) ? 'D' : 'C';
										// echo "OTHER = [".$valor."]"."][".$tipo."]<br/>";
									} elseif($txrow['tipo'] == 'DEBIT') {
										$tipo = 'D';
										// echo "DEBIT = [".$valor."]"."][".$tipo."]<br/>";
									} else {
										$tipo = 'C';
										// echo $txrow['tipo']."= OUTRO = [".$valor."]"."][".$tipo."]<br/>";
									}
									$valor = abs($valor);
									$documento = (array_key_exists('checknum',$txrow)) ? $txrow['checknum'] : '';
									$documento = (array_key_exists('refnum',$txrow)) ? $txrow['refnum'] : $documento;
									$data = array(
										'id_banco' => $id_banco, 
										'data' => $data_extrato,
										'historico' => $txrow['historico'],
										'documento' => $documento, 
										'valor' => $valor,
										'tipo' => $tipo,
										'conciliado' => 0,
										'id_ref' => 0
									);
									self::$db->insert("extrato_bancos", $data);
								}
							}
						} elseif($codigo_banco == '033') { // SANTANDER
							$ponteiro = fopen (UPLOADS.$valor, "r");
							if ($ponteiro == false) die(lang('FORM_ERROR13'));
							while (!feof ($ponteiro)) {
								$linha = fgets($ponteiro, 4096);
								$linha = str_replace("\"","",$linha);
								$campos = explode(";",$linha);
								$isData = substr_count($campos[0], "/20");
								if(strlen(trim($campos[4])) > 0) {
									$valor = converteMoeda($campos[4]);
									$tipo = 'C';
								} else {
									$valor = converteMoeda($campos[5]);
									$tipo = 'D';
								}
								$isExtrato = is_numeric($valor);
								if($isData and $isExtrato) {
									$isData = false;
									$isExtrato = false;		
									$data = array(
										'id_banco' => $id_banco, 
										'data' => dataMySQL($campos[0]),
										'historico' => sanitize($campos[2]),
										'documento' => $campos[3], 
										'valor' => $valor,
										'tipo' => $tipo,
										'conciliado' => 0,
										'id_ref' => 0
									);
									self::$db->insert("extrato_bancos", $data);
								}
							}
							fclose ($ponteiro);
						} elseif($codigo_banco == '104') { //CAIXA ECONOMICA FEDERAL
							$ponteiro = fopen (UPLOADS.$valor, "r");
							if ($ponteiro == false) die(lang('FORM_ERROR13'));
							while (!feof ($ponteiro)) {
								$linha = fgets($ponteiro, 4096);
								$linha = str_replace("\"","",$linha);
								$campos = explode(";",$linha);
								if(is_numeric($campos[0])) {	
									$data = array(
										'id_banco' => $id_banco, 
										'data' => dataExtrato($campos[1]),
										'historico' => sanitize($campos[3]),
										'documento' => $campos[2], 
										'valor' => $campos[4],
										'tipo' => $campos[5],
										'conciliado' => 0,
										'id_ref' => 0
									);
									self::$db->insert("extrato_bancos", $data);
								}
							}
							fclose ($ponteiro);		
						} elseif($codigo_banco == '237') {  //BRADESCO
							$ponteiro = fopen (UPLOADS.$valor, "r");
							if ($ponteiro == false) die(lang('FORM_ERROR13'));
							while (!feof ($ponteiro)) {
								$linha = fgets($ponteiro, 4096);
								$linha = str_replace("\"","",$linha);
								$campos = explode(";",$linha);
								$isData = substr_count($campos[0], "/20");
								$valor = 0;
								$tipo = 'C';
								if(strlen(trim($campos[3])) > 0) {
									$valor = converteMoeda($campos[4]);
								} else {
									$tipo = 'D';
									$valor = converteMoeda($campos[4]);
									if($valor < 0) {
										$valor = $valor * (-1);
									}
								}
								if(isData) {	
									$data = array(
										'id_banco' => $id_banco, 
										'data' => dataMySQL($campos[0]),
										'historico' => sanitize($campos[1]),
										'documento' => $campos[2], 
										'valor' => $valor,
										'tipo' => $tipo,
										'conciliado' => 0,
										'id_ref' => 0
									);
									self::$db->insert("extrato_bancos", $data);
								}
							}
							fclose ($ponteiro);		
						} elseif($codigo_banco == '001') { // BANCO DO BRASIL
							$ponteiro = fopen (UPLOADS.$valor, "r");
							if ($ponteiro == false) die(lang('FORM_ERROR13'));
							while (!feof ($ponteiro)) {
								$linha = fgets($ponteiro, 4096);
								$linha = str_replace("\"","",$linha);
								$campos = explode(";",$linha);
								$isData = substr_count($campos[3], "20");
								$doc = $campos[7];
								$isExtrato = is_numeric($doc);
								if($isData and $isExtrato and $doc > 0) {
									$isData = false;
									$isExtrato = false;
									$dataSQL = substr($campos[3],4,4)."-".substr($campos[3],2,2)."-".substr($campos[3],0,2);
									$valor = substr($campos[10],0,-2).".".substr($campos[10],-2);
									$data = array(
										'id_banco' => $id_banco, 
										'data' => $dataSQL,
										'historico' => sanitize($campos[9]),
										'documento' => $campos[7], 
										'valor' => $valor,
										'tipo' => $campos[11],
										'conciliado' => 0,
										'id_ref' => 0
									);
									self::$db->insert("extrato_bancos", $data);
								}
							}
							fclose ($ponteiro);
						} else {
							Filter::msgAlert(lang('MSG_ERRO_CODDIGO_BANCO'));
						}
					}
				}
				(self::$db->affected()) ? Filter::msgOk(lang('ARQUIVO_PROCESSADO'), "index.php?do=extrato&acao=arquivobanco") : Filter::msgAlert(lang('NAOPROCESSADO'));
			} else
              print Filter::msgStatus();
      }	  
	  
	  /**
       * Extrato::getExtrato_view()
       * 
       * @return
       */
      public function getExtrato_view($dataini = false, $datafim = false, $id_banco = 0)
      {
         $dataini = ($dataini) ? $dataini : date('d/m/Y', strtotime('-15 days')); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
		 $wview_data = "AND v.data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')";
	
		 $sql = "SELECT v.data_pagamento, DATE_FORMAT(v.data, '%Y%m%d%H%i%s') AS controle, v.ti_ch, v.descricao, v.tipo, v.id, v.id_conta, v.id_banco, v.id_empresa, v.cli_for, v.valor, v.data_vencimento, v.pago, cad.nome as cadastro, c.conta, t.tipo as pagamento " 
		  . "\n FROM extrato_view as v" 
		  . "\n LEFT JOIN conta as c ON c.id = v.id_conta" 
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = v.ti_ch " 
		  . "\n LEFT JOIN cadastro as cad ON cad.id = v.cli_for " 
		  . "\n WHERE v.id_banco = '$id_banco' AND v.inativo = 0 AND v.pago = 1 "
		  . "\n $wview_data"
		  . "\n ORDER BY 1,2,3 ASC";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Extrato::conciliarBanco()
       * 
       * @return
       */
      public function conciliarBanco()
      {	
          if (empty(Filter::$msgs)) {
			  $id_banco = post('id_banco');			  
			  $alterado = false;
			  $sql = "SELECT id " 
			  . "\n FROM extrato_bancos "
			  . "\n WHERE conciliado = 1 AND id_banco = '$id_banco' ";
			  $row = self::$db->first($sql);
			  if(!$row) {
				  $ini = $this->getDataInicial($id_banco);
				  $fim = $this->getDataFinal($id_banco);			
				  $data_limpar = array(
					'extrato_data' => '',
					'extrato_doc' => '',
					'conciliado' => 0
				  );
				  self::$db->update("despesa", $data_limpar, "id_banco = $id_banco AND conciliado = 1 AND data_pagamento BETWEEN STR_TO_DATE('$ini','%Y-%m-%d') AND STR_TO_DATE('$fim','%Y-%m-%d')");
			  }	
			  foreach ( $_POST as $name => $value ) {
				  //echo "<br/>[$name]>>[$value]<br/>";
				if(strpos($value, "#")) {
					$campos = explode("#", $value);					
					$data_limpar = array(
						'extrato_data' => '',
						'extrato_doc' => '',
						'conciliado' => 0
					);
					self::$db->update("despesa", $data_limpar, "agrupar = '".$campos[2]."'");
					$data_despesa = array(
						'extrato_data' => $campos[1],
						'extrato_doc' => $campos[3],
						'conciliado' => 1
					);
					self::$db->update("despesa", $data_despesa, "conciliado = 0 AND agrupar = '".$campos[2]."'");
					$alterado = self::$db->affected();
					$data_limpar_extrado = array(
						'id_ref' => '',
						'conciliado' => 0
					);
					self::$db->update("extrato_bancos", $data_limpar_extrado, "id_ref = '".$campos[2]."'");
					$data_extrato = array(
						'id_ref' => $campos[2],
						'conciliado' => 1
					);					
					self::$db->update("extrato_bancos", $data_extrato, "conciliado = 0 AND id = '".$campos[3]."'");
				}
			  }			  
			  if ($alterado) {
				Filter::msgOk(lang('EXTRATO_CONCILIADO'), "index.php?do=extrato&acao=conciliardespesas&id_banco=".$id_banco);
			  } else
				Filter::msgAlert(lang('NAOPROCESSADO'));
			
			} else
              print Filter::msgStatus();
      }
	  
	  /**
       * Extrato::cancelarConciliacao()
       * 
       * @return
       */
      public function cancelarConciliacao()
      {	
			$id = post('id');			  
			$alterado = false;
			$data_limpar = array(
				'extrato_data' => '',
				'extrato_doc' => '',
				'conciliado' => 0
			);
			self::$db->update("despesa", $data_limpar, "extrato_doc = '".$id."'");
			$alterado = self::$db->affected();
			$data_limpar_extrado = array(
				'id_ref' => '',
				'conciliado' => 0
			);
			self::$db->update("extrato_bancos", $data_limpar_extrado, "id = '".$id."'");			  
			if ($alterado) {
				Filter::msgOk(lang('CANCELADO'));
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
      }
	  
	  
	  /**
       * Extrato::getTotalBanco()
       * 
       * @return
       */
	  public function getTotalBanco($data = '', $id_banco)
      {
			  $sql = "SELECT SUM(valor) as total " 
			  . "\n FROM extrato_bancos "
			  . "\n WHERE data = '$data' AND tipo = 'C' AND historico like 'LANCAMENTO A CREDITO%' AND id_banco = '$id_banco' ";
			  $row = self::$db->first($sql);
          return ($row->total) ? $row->total : 0;
      }
	  
	  
	  /**
       * Extrato::getDatasExtrato()
       * 
       * @return
       */
	  public function getDatasExtrato($id_banco)
      {          
			$sql = "SELECT DISTINCT data " 
			  . "\n FROM extrato_bancos "
			  . "\n ORDER BY 1 ASC";
		  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  
	  /**
       * Extrato::getExtratoData()
       * 
       * @return
       */
	  public function getExtratoData($data = '', $condicao = '', $id_banco)
      {
		  $sql = "SELECT * " 
		  . "\n FROM extrato_bancos "
		  . "\n WHERE data = '$data' $condicao AND id_banco = '$id_banco'"
		  . "\n ORDER BY tipo, historico ";
		  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  
	  /**
       * Extrato::getDataInicial()
       * 
       * @return
       */
	  public function getDataInicial($id_banco)
      {          
			$sql = "select min(data) as inicial from extrato_bancos where id_banco = '$id_banco'";
			$row = self::$db->first($sql);
			$inicial = $row->inicial;
          return $inicial;
      }
	  
	  
	  /**
       * Extrato::getDataFinal()
       * 
       * @return
       */
	  public function getDataFinal($id_banco)
      {          
			$sql = "select max(data) as fim from extrato_bancos where id_banco = '$id_banco'";
			$row = self::$db->first($sql);
			$fim = $row->fim;
          return $fim;
      }
	  
	  /**
       * Extrato::totalExtratoDinheiro()
       * 
       * @return
       */
	  public function totalExtratoDinheiro($data = '', $id_banco)
      {
		  $sql = "SELECT sum(e.valor) as total" 
			  . "\n FROM extrato_bancos as e "
			  . "\n WHERE e.historico like 'DEPOSITO EM DINHEIRO%' AND data = '$data' AND e.id_banco = '$id_banco'";
          $row = self::$db->first($sql);
          return ($row->total) ? $row->total : 0;
      }	  
	  
	  /**
       * Extrato::totalExtratoDespesa()
       * 
       * @return
       */
	  public function totalExtratoDespesa($data = '', $id_banco)
      {
		  $sql = "SELECT sum(e.valor) as total" 
			  . "\n FROM extrato_bancos as e "
			  . "\n WHERE e.tipo = 'D' AND data = '$data' AND e.id_banco = '$id_banco'";
          $row = self::$db->first($sql);
          return ($row->total) ? $row->total : 0;
      }  
	  
	  /**
       * Extrato::totalExtratoCredito()
       * 
       * @return
       */
	  public function totalExtratoCredito($data = '', $id_banco)
      {
		  $sql = "SELECT SUM(e.valor) as total" 
			  . "\n FROM extrato_bancos as e "
			  . "\n WHERE e.tipo = 'C' AND e.data = '$data' AND e.id_banco = '$id_banco'";
          $row = self::$db->first($sql);
          return ($row->total) ? $row->total : 0;
      }	  
	  
	  /**
       * Extrato::getListaClinicaCredito()
       * 
       * @return
       */
	  public function getListaClinicaCredito($data = '', $id_banco)
      {
		  $sql = "SELECT c.historico, c.plano_contas, c.dt_vencimento, c.valor_credito " 
			  . "\n FROM financeiro_clinica_parcelas as c " 
			  . "\n WHERE c.tipo_movimento = 'C' AND c.id_banco = '$id_banco'  AND c.dt_vencimento = '$data' ";
			  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  	  
	  /**
       * Extrato::totalCartoesRecebidoBanco()
       * 
       * @return
       */
	  public function totalCartoesRecebidoBanco($data = '', $id_banco)
      {
		 $sql = "SELECT SUM(valor) as total " 
		  . "\n FROM extrato_bancos "
		  . "\n WHERE data = '$data' AND tipo = 'C' AND historico like 'LANCAMENTO A CREDITO%' AND id_banco = '$id_banco' ";
			  $row = self::$db->first($sql);
          return ($row->total) ? $row->total : 0;
      }
	  
	  /**
       * Extrato::getListaCartoesRecebidoBanco()
       * 
       * @return
       */		  
	  public function getListaCartoesRecebidoBanco($data = '', $id_banco)
      {
		 $sql = "SELECT * " 
		  . "\n FROM extrato_bancos "
		  . "\n WHERE data = '$data' AND tipo = 'C' AND historico like 'LANCAMENTO A CREDITO%' AND id_banco = '$id_banco' ";
		  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Extrato::getExtratoDespesas()
       * 
       * @return
       */	
	  public function getExtratoDespesas($id_banco)
      {
		  $order = "e.data, e.historico, e.valor, e.id ASC";
		  $ordenar = get('ordernar');
		  if($ordenar == "datadesc") {
			  $order = "e.data, e.historico, e.valor, e.id DESC";
		  } elseif($ordenar == "valorasc") {
			  $order = "e.valor, e.historico, e.data, e.id ASC";			  
		  } elseif($ordenar == "valordesc") {
			  $order = "e.valor, e.historico, e.data, e.id DESC";			  
		  }
		  $sql = "SELECT * " 
			  . "\n FROM extrato_bancos as e "
			  . "\n WHERE e.tipo = 'D' AND e.id_banco = '$id_banco'"
			  . "\n ORDER BY $order";
		  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Extrato::pesquisarDespesas()
       * 
       * @return
       */	
	  public function pesquisarDespesas($data, $valor, $id_banco)
      {
		 $sql = "SELECT a.id, a.valor, d.id_conta, d.id_banco, d.descricao, d.cheque, d.data_vencimento, d.data_pagamento, d.nro_documento, d.conciliado, d.extrato_data, d.extrato_doc " 
		  . "\n FROM despesa_agrupar as a" 
		  . "\n LEFT JOIN despesa as d ON d.id = a.id" 
		  . "\n WHERE DATE_FORMAT(a.data_pagamento, '%Y-%m-%d') = '$data' "
		  . "\n AND a.id_banco = '$id_banco' AND a.valor = '$valor' "
		  . "\n ORDER BY a.data_pagamento, d.id";
		  $row = self::$db->fetch_all($sql);
		  
		  
          return ($row) ? $row : 0;
      }	  
	  
	  /**
       * Extrato::pesquisarExtrato()
       * 
       * @return
       */	
	  public function pesquisarExtrato($data, $valor, $id_banco)
      {
		  $sql = "SELECT * " 
			  . "\n FROM extrato_bancos as e "
			  . "\n WHERE e.conciliado = 0 AND e.tipo = 'D' AND e.id_banco = '$id_banco'"
			  . "\n AND e.data BETWEEN DATE_SUB(STR_TO_DATE('$data','%Y-%m-%d'), INTERVAL 5 DAY) AND DATE_ADD(STR_TO_DATE('$data','%Y-%m-%d'), INTERVAL 5 DAY) "
			  . "\n AND e.valor = '$valor' ";
		  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Extrato::getDespesasConciliadas()
       * 
       * @return
       */	
	  public function getDespesasConciliadas($id_banco)
      {
		 $order = "c.dt_vencimento ASC";
		  $ordenar = get('ordernar');
		  if($ordenar == "datadesc") {
			  $order = "c.dt_vencimento DESC";
		  } elseif($ordenar == "valorasc") {
			  $order = "c.valor_parcela ASC";			  
		  } elseif($ordenar == "valordesc") {
			  $order = "c.valor_parcela DESC";			  
		  }
		  
		 $ini = (get('dataini')) ? get('dataini') : date('d/m/Y', strtotime('-15 days'));
		 $fim = (get('datafim')) ? get('datafim') : date('d/m/Y');
		 
		 $conciliado = get('conciliado');
		 $conciliar = "";
		 if($conciliado == "sim") {
			  $conciliar = "AND c.conciliado = 1 ";
		  } elseif($conciliado == "nao") {
			  $conciliar = "AND c.conciliado = 0 ";
		  }
		  
		$sql = "SELECT c.chave, p.nm_categoria_plano_conta, c.historico, c.plano_contas, c.dt_vencimento, c.valor_parcela, c.extrato_data, c.extrato_doc, c.conciliado " 
			  . "\n FROM financeiro_clinica_parcelas as c " 
			  . "\n LEFT JOIN categoria_plano_conta as p ON p.chave = c.plano_contas " 
			  . "\n WHERE c.tipo_movimento = 'D' $conciliar "
			  . "\n AND c.id_banco = '$id_banco' AND c.dt_vencimento BETWEEN STR_TO_DATE('$ini','%d/%m/%Y') AND STR_TO_DATE('$fim','%d/%m/%Y') "
			  . "\n ORDER BY $order";
			  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Extrato::getExtratoReceitas()
       * 
       * @return
       */	
	  public function getExtratoReceitas($id_banco)
      {
		  $sql = "SELECT e.data, e.historico, SUM(e.valor) as total " 
			  . "\n FROM extrato_bancos as e "
			  . "\n WHERE e.conciliado = 0 AND e.tipo = 'C' AND e.id_banco = '$id_banco'"
			  . "\n GROUP BY e.data , e.historico "
			  . "\n ORDER BY e.data , e.historico ";
		  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Extrato::getReceitaSistema()
       * 
       * @return
       */	
	  public function getReceitaSistema($id_banco = 0, $data = '', $historico = '')
      {
		  $where = "AND r.pago = 1 AND r.id_banco = '$id_banco' ";	
		  if (substr_count($historico, "DEPOSITO EM DINHEIRO")) {
			  $where = "AND r.pago = 1 AND r.id_banco = '$id_banco' AND b.id = r.id_banco AND r.tipo = 0 AND r.id_conta = 114 ";	
		  } elseif (substr_count($historico, "TRANSFERENCIA ENTRE CONTAS")) {
			  $where = "AND r.pago = 1 AND r.id_banco = '$id_banco' AND b.id = r.id_banco AND r.tipo = 0 AND r.id_conta = 114 ";			  
		  } elseif (substr_count($historico, "DEPOSITO EM CHEQUE")) {
			  $where = "AND r.pago = 1 AND r.id_banco = '$id_banco' AND b.id = r.id_banco  AND r.tipo = 2 ";			  
		  } elseif (substr_count($historico, "CR COB")) {
			  $where = "AND r.pago = 1 AND r.id_banco = '$id_banco' AND b.id = r.id_banco AND r.tipo = 3 ";			  
		  } elseif (substr_count($historico, "CIELO CARTAO DE CREDITO BAND ELO")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 1 ";			  
		  } elseif (substr_count($historico, "CIELO CARTAO DE CREDITO BAND MASTER")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 5 ";				  
		  } elseif (substr_count($historico, "CIELO CARTAO DE CREDITO BAND VISA")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 3 ";				  
		  } elseif (substr_count($historico, "CIELO CARTAO DE DEBITO BAND ELO")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 2 ";				  
		  } elseif (substr_count($historico, "CIELO CARTAO DE DEBITO BAND MASTER")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 6 ";				  
		  } elseif (substr_count($historico, "CIELO CARTAO DE DEBITO BAND VISA")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 4 ";				  
		  } elseif (substr_count($historico, "SANTANDER ADQ CARTAO DE CREDITO BAND MASTER")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 9 ";				  
		  } elseif (substr_count($historico, "SANTANDER ADQ CARTAO DE CREDITO BAND VISA")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 7 ";				  
		  } elseif (substr_count($historico, "SANTANDER ADQ CARTAO DE DEBITO BAND MASTER")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 10 ";				  
		  } elseif (substr_count($historico, "SANTANDER ADQ CARTAO DE DEBITO BAND VISA")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 8 ";				  
		  }
		  
		  $sql = "SELECT SUM(r.valor_pago) as total " 
			  . "\n FROM receita as r "
			  . "\n LEFT JOIN tipo_pagamento as p on p.id = r.tipo "
			  . "\n LEFT JOIN banco as b on b.id_empresa = r.id_empresa "
			  . "\n WHERE r.inativo = 0 AND r.conciliado = 0 AND b.id = r.id_banco AND r.data_recebido = '$data' $where ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Extrato::getExtratoBoletos()
       * 
       * @return
       */	
	  public function getExtratoBoletos($id_banco = 0, $data = '')
      {		  
		  $sql = "SELECT SUM(e.valor) as total " 
			  . "\n FROM extrato_bancos as e "
			  . "\n WHERE e.id_banco = '$id_banco' AND e.data = '$data' AND e.historico like '%CR COB%'";
		  $row = self::$db->first($sql);
          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Extrato::getVerBoletos()
       * 
       * @return
       */	
	  public function getVerBoletos($id_banco = 0, $data = '')
      {		  
		  $sql = "SELECT e.historico, e.valor, e.data " 
			  . "\n FROM extrato_bancos as e "
			  . "\n WHERE e.tipo = 'C' AND e.id_banco = '$id_banco' AND e.data = '$data' AND e.historico like '%CR COB%'"
			  . "\n ORDER BY e.data , e.historico ";
		  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Extrato::getVerSistema()
       * 
       * @return
       */	
	  public function getVerSistema($id_banco = 0, $data = '', $historico = '')
      {		  
		  $where = "AND r.pago = 1 AND r.id_banco = '$id_banco' ";
		  if (substr_count($historico, "DEPOSITO EM DINHEIRO")) {
			  $where = "AND r.pago = 1 AND r.id_banco = '$id_banco' AND b.id = r.id_banco AND r.tipo = 0 AND r.id_conta = 114 ";	
		  } elseif (substr_count($historico, "TRANSFERENCIA ENTRE CONTAS")) {
			  $where = "AND r.pago = 1 AND r.id_banco = '$id_banco' AND b.id = r.id_banco AND r.tipo = 0 AND r.id_conta = 114 ";			  
		  } elseif (substr_count($historico, "DEPOSITO EM CHEQUE")) {
			  $where = "AND r.pago = 1 AND r.id_banco = '$id_banco' AND b.id = r.id_banco  AND r.tipo = 2 ";			  
		  } elseif (substr_count($historico, "CR COB")) {
			  $where = "AND r.pago = 1 AND r.id_banco = '$id_banco' AND b.id = r.id_banco AND r.tipo = 3 ";			  
		  } elseif (substr_count($historico, "CIELO CARTAO DE CREDITO BAND ELO")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 1 ";			  
		  } elseif (substr_count($historico, "CIELO CARTAO DE CREDITO BAND MASTER")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 5 ";				  
		  } elseif (substr_count($historico, "CIELO CARTAO DE CREDITO BAND VISA")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 3 ";				  
		  } elseif (substr_count($historico, "CIELO CARTAO DE DEBITO BAND ELO")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 2 ";				  
		  } elseif (substr_count($historico, "CIELO CARTAO DE DEBITO BAND MASTER")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 6 ";				  
		  } elseif (substr_count($historico, "CIELO CARTAO DE DEBITO BAND VISA")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 4 ";				  
		  } elseif (substr_count($historico, "SANTANDER ADQ CARTAO DE CREDITO BAND MASTER")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 9 ";				  
		  } elseif (substr_count($historico, "SANTANDER ADQ CARTAO DE CREDITO BAND VISA")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 7 ";				  
		  } elseif (substr_count($historico, "SANTANDER ADQ CARTAO DE DEBITO BAND MASTER")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 10 ";				  
		  } elseif (substr_count($historico, "SANTANDER ADQ CARTAO DE DEBITO BAND VISA")) {
			  $where = "AND r.pago = 1 AND b.id = '$id_banco' AND p.id_cartoes = 8 ";				  
		  }      
		  
		  $sql = "SELECT r.id, r.id_cliente, f.numero_cartao, r.nossonumero, c.nome as cliente, b.banco, r.id_caixa, p.tipo, r.data_pagamento, r.data_recebido, r.valor_pago " 
			  . "\n FROM receita as r "
			  . "\n LEFT JOIN cliente as c on c.id = r.id_cliente "
			  . "\n LEFT JOIN cliente_financeiro as f on f.id = r.id_pagamento "
			  . "\n LEFT JOIN tipo_pagamento as p on p.id = r.tipo "
			  . "\n LEFT JOIN banco as b on b.id_empresa = r.id_empresa "
			  . "\n WHERE r.inativo = 0 AND r.conciliado = 0 AND b.id = r.id_banco AND r.data_recebido = '$data' $where "
			  . "\n ORDER BY c.nome ";
		  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Extrato::getVerBanco()
       * 
       * @return
       */	
	  public function getVerBanco($id_banco = 0, $data = '', $historico = '')
      {
		  $sql = "SELECT e.historico, e.valor, e.data " 
			  . "\n FROM extrato_bancos as e "
			  . "\n WHERE e.conciliado = 0 AND e.tipo = 'C' AND e.data = '$data' AND e.id_banco = '$id_banco' AND e.historico = '".$historico."'"
			  . "\n ORDER BY e.data , e.historico ";
		  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Extrato::getDataExtrato()
       * 
       * @return
       */	
	  public function getDataExtrato($id_banco = 0)
      {
		  $sql = "SELECT MAX(e.data) as data " 
			  . "\n FROM extrato_bancos as e "
			  . "\n WHERE e.tipo = 'C' AND e.id_banco = '$id_banco' ";
		  $row = self::$db->first($sql);
          return ($row) ? $row->data : 0;
      }

  }
?>
