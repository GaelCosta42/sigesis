<?php
  /**
   * Classe Ponto Eletrônico
   *
   * Operacao 1 = Entrada
   * Operacao 2 = Saída
   *
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class PontoEletronico
  {
      public $pid = 0;
      public $pontoid = 0;
      private static $db;

      /**
       * PontoEletronico::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
       * PontoEletronico::getHorariosPonto()
       * 
       * @return
       */
      public function getHorariosPonto()
      {
		  $sql = "SELECT * FROM ponto_horario WHERE inativo = 0 ORDER BY numero_dia";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * PontoEletronico::getDiasSemana()
       * 
       * @return
       */
      public function getDiasSemana()
      {
		  $diasSemana = array(
			array('numero'=> 1, 'dia' => 'Segunda-Feira'),
			array('numero'=> 2, 'dia' => 'Terça-Feira'),
			array('numero'=> 3, 'dia' => 'Quarta-Feira'),
			array('numero'=> 4, 'dia' => 'Quinta-Feira'),
			array('numero'=> 5, 'dia' => 'Sexta-Feira'),
			array('numero'=> 6, 'dia' => 'Sábado'),
			array('numero'=> 7, 'dia' => 'Domingo')
		  );
          return $diasSemana;
      }
	  
	  /**
       * PontoEletronico::getDiaDaSemana()
       * 
       * @return
       */
      public function getDiaDaSemana($dia=0)
      {
		  $dia = ($dia<0 || $dia>7) ? 0 : $dia;
		  $diasSemana = array('--indefinido--','Segunda-Feira','Terça-Feira','Quarta-Feira','Quinta-Feira','Sexta-Feira','Sábado','Domingo');
          return $diasSemana[$dia];
      }
	  
      /**
       * PontoEletronico::processarHorarioPonto()
       * 
       * @return
       */
      public function processarHorarioPonto()
      {
		  $entrada1 = 0;
		  $entrada2 = 0;
		  $saida1 = 0;
		  $saida2 = 0;
		  
		  if (empty($_POST['dia_semana']))
              Filter::$msgs['dia_semana'] = lang('MSG_ERRO_DIA_SEMANA');	 
		  else	
		  if (empty($_POST['horario_entrada1']))
              Filter::$msgs['horario_entrada1'] = lang('MSG_ERRO_ENTRADA1');
		  else
		  if (empty($_POST['horario_saida1']))
              Filter::$msgs['horario_saida1'] = lang('MSG_ERRO_SAIDA1');
		  else
		  if (empty($_POST['horario_entrada2']) AND !empty($_POST['horario_saida2']))
              Filter::$msgs['horario_entrada2'] = lang('MSG_ERRO_ENTRADA2');
		  else {
			  $entrada1 = strtotime(post('horario_entrada1'));
			  $saida1 = strtotime(post('horario_saida1'));
			  $entrada2 = strtotime(post('horario_entrada2'));
			  $saida2 = strtotime(post('horario_saida2'));
			  $virada_turno = strtotime(post('virada_turno'));
			  
			  if ($saida1 <= $entrada1 && $saida1 > $virada_turno)
				  Filter::$msgs['horario_errado1'] = lang('MSG_ERRO_HORARIO1');
		  }
		
          if (empty(Filter::$msgs)) {
			  $dia_semana = explode('#', post('dia_semana'));
			  
			  $entrada1 = DateTime::createFromFormat('H:i:s', date('H:i:s',strtotime(post('horario_entrada1'))));
			  $entrada2 = DateTime::createFromFormat('H:i:s', date('H:i:s',strtotime(post('horario_entrada2'))));
			  $saida1 = DateTime::createFromFormat('H:i:s', date('H:i:s',strtotime(post('horario_saida1'))));
			  $saida2 = DateTime::createFromFormat('H:i:s', date('H:i:s',strtotime(post('horario_saida2'))));
			  
			  if ($entrada1 <= $saida1) {
			      $hora1 = $saida1->diff($entrada1);
				  $segundos_hora1 = hora_para_segundos($hora1->h.':'.$hora1->i.':'.$hora1->s);
			  } else {
				  $hora_virada = DateTime::createFromFormat('H:i:s', date('H:i:s', strtotime('23:59:00')));
				  $hora_virada = $entrada1->diff($hora_virada);
				  $hora_virada = DateTime::createFromFormat('H:i:s', date('H:i:s', strtotime($hora_virada->h.':'.$hora_virada->i.':'.$hora_virada->s)));
				  $hora_virada = $hora_virada->modify('+1 minutes')->format('H:i:s');
				  $hora_virada2 = DateTime::createFromFormat('H:i:s', date('H:i:s', strtotime('00:00:00')));
				  $hora_virada2 = $saida1->diff($hora_virada2);
				  $hora_virada2 = DateTime::createFromFormat('H:i:s', date('H:i:s', strtotime($hora_virada2->h.':'.$hora_virada2->i.':'.$hora_virada2->s)));
				  $hora_virada2 = $hora_virada2->format('H:i:s');
				  $segundos_hora1 = hora_para_segundos($hora_virada) + hora_para_segundos($hora_virada2);
			  }
			  	
			  if ($entrada2 <= $saida2) {
				  $hora2 = $saida2->diff($entrada2);
				  $segundos_hora2 = hora_para_segundos($hora2->h.':'.$hora2->i.':'.$hora2->s);
			  } else {
				  $hora_virada = DateTime::createFromFormat('H:i:s', date('H:i:s', strtotime('23:59:00')));
				  $hora_virada = $entrada2->diff($hora_virada);
				  $hora_virada = DateTime::createFromFormat('H:i:s', date('H:i:s', strtotime($hora_virada->h.':'.$hora_virada->i.':'.$hora_virada->s)));
				  $hora_virada = $hora_virada->modify('+1 minutes')->format('H:i:s');
				  $hora_virada2 = DateTime::createFromFormat('H:i:s', date('H:i:s', strtotime('00:00:00')));
				  $hora_virada2 = $saida2->diff($hora_virada2);
				  $hora_virada2 = DateTime::createFromFormat('H:i:s', date('H:i:s', strtotime($hora_virada2->h.':'.$hora_virada2->i.':'.$hora_virada2->s)));
				  $hora_virada2 = $hora_virada2->format('H:i:s');
				  $segundos_hora2 = hora_para_segundos($hora_virada) + hora_para_segundos($hora_virada2);
			  }
			
			  $total_horas = segundos_para_hora($segundos_hora1+$segundos_hora2);
			  
              $data_horario = array(
					'dia' => $dia_semana[1],
					'numero_dia' => $dia_semana[0],
					'entrada1' => post('horario_entrada1'),
					'saida1' => post('horario_saida1'),
					'entrada2' => post('horario_entrada2'),
					'saida2' => post('horario_saida2'),
					'virada_turno' => (empty(post('virada_turno'))) ? '00:00:00' : post('virada_turno'),
					'total_horas' => $total_horas,
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			 
              (Filter::$id) ? self::$db->update("ponto_horario", $data_horario, "id=" . Filter::$id) : self::$db->insert("ponto_horario", $data_horario);
              $message = (Filter::$id) ? lang('PONTO_HORARIO_ALTERAR_OK') : lang('PONTO_HORARIO_ADICIONAR_OK');

              if (self::$db->affected()) {
                  Filter::msgOk($message, "index.php?do=ponto_eletronico&acao=horariolistar");   
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * PontoEletronico::getTabelasDePonto()
       * 
       * @return
       */
      public function getTabelasDePonto()
      {
		  $sql = "SELECT * FROM ponto_descricao WHERE inativo = 0";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * PontoEletronico::processarTabelaPonto()
       * 
       * @return
       */
      public function processarTabelaPonto()
      {
		  if (empty($_POST['titulo_tabela']))
              Filter::$msgs['titulo_tabela'] = lang('MSG_ERRO_TITULO');	 

		  if (empty($_POST['descricao_tabela']))
              Filter::$msgs['descricao_tabela'] = lang('MSG_ERRO_DESCRICAO');

		  if (empty($_POST['escala_tabela']))
              Filter::$msgs['escala_tabela'] = lang('MSG_ERRO_ESCALA');
		
          if (empty(Filter::$msgs)) {
			  
              $data_tabela = array(
					'titulo' => sanitize(post('titulo_tabela')),
					'descricao' => sanitize(post('descricao_tabela')),
					'escala' => sanitize(post('escala_tabela')),
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  
			  if (Filter::$id) {
				self::$db->update("ponto_descricao", $data_tabela, "id=" . Filter::$id);
				$id_tabela = Filter::$id;
				$message = lang('PONTO_TABELA_ALTERAR_OK');
			  } else {
				$id_tabela = self::$db->insert("ponto_descricao", $data_tabela);
				$message = lang('PONTO_TABELA_ADICIONAR_OK');
			  }
              
              if (self::$db->affected()) {
                  Filter::msgOk($message, "index.php?do=ponto_eletronico&acao=tabelaPontoHorarios&id=".$id_tabela);   
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * PontoEletronico::getHorariosTabela()
       * 
       * @return
       */
      public function getHorariosTabela($id_tabela)
      {
		  $sql = "SELECT * "
		    . "\n FROM ponto_horario "
			. "\n WHERE inativo = 0 AND id NOT IN (SELECT id_ponto_horario FROM ponto_descricao_horario WHERE inativo = 0 AND id_ponto_descricao = $id_tabela) ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * PontoEletronico::getHorariosPontoTabela()
       * 
       * @return
       */
      public function getHorariosPontoTabela($id_tabela)
      {
		  $sql = "SELECT * "
		    . "\n FROM ponto_horario "
			. "\n WHERE inativo = 0 AND id IN (SELECT id_ponto_horario FROM ponto_descricao_horario WHERE inativo = 0 AND id_ponto_descricao = $id_tabela) ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * PontoEletronico::processarHorarioTabelaPonto()
       * 
       * @return
       */
      public function processarHorarioTabelaPonto()
      {
		  if (empty($_POST['pontotabelahorario']))
              Filter::$msgs['pontotabelahorario'] = lang('MSG_ERRO_PONTOTABELAHORARIO');	 
		
          if (empty(Filter::$msgs)) {
			  
              $data_tabela = array(
					'id_ponto_descricao' => sanitize(post('id_ponto_descricao')),
					'id_ponto_horario' => sanitize(post('pontotabelahorario')),
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->insert("ponto_descricao_horario", $data_tabela);
			  
			  $hora_atual = getValue("hora_total","ponto_descricao","id=".post('id_ponto_descricao'));
			  $hora_nova = getValue("total_horas","ponto_horario","id=".post('pontotabelahorario'));
			  $segundos_atual = hora_para_segundos($hora_atual);
			  $segundos_nova = hora_para_segundos($hora_nova);
			  $totalHoras = segundos_para_hora($segundos_atual+$segundos_nova);
			  
			  $data_descricao = array(
				'hora_total' => $totalHoras
			  );
			  self::$db->update("ponto_descricao", $data_descricao, "id=" . post('id_ponto_descricao'));
              
			  $message = lang('PONTO_TABELA_HORARIOS_ADICIONAR_OK');
              if (self::$db->affected()) {
                  Filter::msgOk($message, "index.php?do=ponto_eletronico&acao=tabelaPontoHorarios&id=".post('id_ponto_descricao'));   
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

	  /**
       * PontoEletronico::getRelatorioPontoHorarios()
       * 
       * @return
       */
      public function getRelatorioPontoHorarios($id_funcionario)
      {
		$sql_ponto_horarios = "SELECT ph.dia, ph.entrada1, ph.saida1, ph.entrada2,
		ph.saida2, ph.virada_turno, ph.total_horas
		FROM usuario u
		LEFT JOIN ponto_descricao pd ON pd.id = u.id_tabela_ponto
		LEFT JOIN ponto_descricao_horario pdh ON pdh.id_ponto_descricao = pd.id
		LEFT JOIN ponto_horario ph ON ph.id = pdh.id_ponto_horario
		WHERE u.id = {$id_funcionario} AND pd.inativo = 0 AND pdh.inativo = 0
		AND ph.inativo = 0";
		return self::$db->fetch_all($sql_ponto_horarios);
	  }

	  /**
       * PontoEletronico::getRelatorioPontoEletronicos()
       * 
       * @return
       */
      public function getRelatorioPontoEletronicos($ano, $mes, $id_funcionario)
      {
		$sql_ponto_eletronicos = "SELECT pe.id, pe.operacao, pe.data_operacao, pe.usuario, pe.lat, pe.lng
			FROM ponto_eletronico pe
			WHERE pe.id_usuario = {$id_funcionario}
			AND YEAR(pe.data_operacao) = {$ano}
			AND MONTH(pe.data_operacao) = {$mes}
			AND pe.inativo = 0 ORDER BY data_operacao ASC";
		return self::$db->fetch_all($sql_ponto_eletronicos);
	  }

	  /**
       * PontoEletronico::getRelatorioPontoAbonos()
       * 
       * @return
       */
      public function getRelatorioPontoAbonos($ano, $mes, $id_funcionario)
      {
		$sql_ponto_abonos = "SELECT po.id, po.data_abono, po.tempo, po.usuario
			FROM ponto_abono po
			WHERE po.id_usuario = {$id_funcionario}
			AND YEAR(po.data_abono) = {$ano}
			AND MONTH(po.data_abono) = {$mes}
			AND po.inativo = 0 ORDER BY po.data_abono ASC";
		return self::$db->fetch_all($sql_ponto_abonos);
	  }

	  /**
       * PontoEletronico::getRelatorioPontoDescricao()
       * 
       * @return
       */
      public function getRelatorioPontoDescricao($id_funcionario)
      {
		$sql_ponto_descricao = "SELECT pd.titulo, pd.descricao, pd.hora_total
			FROM ponto_descricao pd
			LEFT JOIN usuario u ON u.id_tabela_ponto = pd.id
			WHERE u.id = {$id_funcionario}";
		return self::$db->first($sql_ponto_descricao);
	  }
	  
	   /**
       * PontoEletronico::getRelatorioPonto()
       * 
       * @return
       */
      public function getRelatorioPonto($ano, $mes, $id_funcionario)
      {
		$qtdDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
		$relatorio = [];

		$ponto_descricao = $this->getRelatorioPontoDescricao($id_funcionario);
		$ponto_horarios = $this->getRelatorioPontoHorarios($id_funcionario);
		$ponto_eletronicos = $this->getRelatorioPontoEletronicos($ano, $mes, $id_funcionario);
		$ponto_abonos = $this->getRelatorioPontoAbonos($ano, $mes, $id_funcionario);
		
		for ($i = 1; $i <= $qtdDias; $i++) {
			$dataDia = $ano . '-' . $mes . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

			$relatorio[$dataDia] = [
				'data' => $dataDia,
				'dia_semana' => diasemana($dataDia),
				'operacoes' => 	[],
				'horas_trabalhadas' => '00:00:00',
				'horas_abono' => '00:00:00',
				'horas_dia' => '00:00:00',
				'saldo_dia' => '00:00:00',
				'status_saldo' => ''
			];

			if (strtotime($dataDia) <= strtotime(date('Y-m-d'))) {
				// HORAS DO DIA
				foreach ($ponto_horarios as $ponto_horario) {
					if (strtolower($ponto_horario->dia) == strtolower(diasemana($dataDia))) {
						$relatorio[$dataDia]['horas_dia'] = $ponto_horario->total_horas;
						$relatorio[$dataDia]['virada_turno'] = $ponto_horario->virada_turno;
						$relatorio[$dataDia]['ponto_horario'] = $ponto_horario;
					}
				}

				// VIRADA DE TURNO
				$dataDiaSeguinte = date('Y-m-d', strtotime($dataDia . ' +1 day'));
				$dataHoraViradaTurno = (!empty($relatorio[$dataDia]['virada_turno']))
					? $dataDiaSeguinte . ' ' . $relatorio[$dataDia]['virada_turno']
					: $dataDiaSeguinte . ' 00:00:00';
				
				$dataDiaAnterior = date('Y-m-d', strtotime($dataDia . ' -1 day'));
				$dataHoraViradaTurnoAnterior = (!empty($relatorio[$dataDiaAnterior]['virada_turno'])) 
					? $dataDia . ' ' . $relatorio[$dataDiaAnterior]['virada_turno']
					: $dataDia . ' 00:00:00';

				// MARCA OS PONTOS ELETRONICO NO RELATORIO
				$entrada = 1;
				$saida = 1;
				foreach ($ponto_eletronicos as $ponto_eletronico) {
					$dataPonto = date('Y-m-d', strtotime($ponto_eletronico->data_operacao));
					$horaPonto = date('H:i:s', strtotime($ponto_eletronico->data_operacao));
					$dataHoraPonto = $ponto_eletronico->data_operacao;
					
					if (
						($dataPonto == $dataDia && strtotime($dataHoraPonto) >= strtotime($dataHoraViradaTurnoAnterior)) || 
						($dataPonto == $dataDiaSeguinte && strtotime($dataHoraPonto) < strtotime($dataHoraViradaTurno))
					) {
						if ($ponto_eletronico->operacao === '1') {
							$arrayEntrada = 'entrada' . $entrada++;
							$relatorio[$dataDia]['operacoes'][$arrayEntrada]['id'] = $ponto_eletronico->id;
							$relatorio[$dataDia]['operacoes'][$arrayEntrada]['horario'] = $horaPonto;
							$relatorio[$dataDia]['operacoes'][$arrayEntrada]['lat'] = $ponto_eletronico->lat;
							$relatorio[$dataDia]['operacoes'][$arrayEntrada]['lng'] = $ponto_eletronico->lng;
							$relatorio[$dataDia]['operacoes'][$arrayEntrada]['usuario'] = $ponto_eletronico->usuario;
						} else {
							$arraySaida = 'saida' . $saida++;
							$relatorio[$dataDia]['operacoes'][$arraySaida]['id'] = $ponto_eletronico->id;
							$relatorio[$dataDia]['operacoes'][$arraySaida]['horario'] = $horaPonto;
							$relatorio[$dataDia]['operacoes'][$arraySaida]['lat'] = $ponto_eletronico->lat;
							$relatorio[$dataDia]['operacoes'][$arraySaida]['lng'] = $ponto_eletronico->lng;
							$relatorio[$dataDia]['operacoes'][$arraySaida]['usuario'] = $ponto_eletronico->usuario;
						}
					}
				}
				
				if (!empty($relatorio[$dataDia]['operacoes'])) {

					// CALCULA HORAS TRABALHADAS
					for ($j = 1; $j <= 4; $j++) {
						$arrayEntrada = 'entrada' . $j;
						$arraySaida = 'saida' . $j;

						if (
							(empty($relatorio[$dataDia]['operacoes'][$arrayEntrada]) &&
							!empty($relatorio[$dataDia]['operacoes'][$arraySaida])) ||
							(!empty($relatorio[$dataDia]['operacoes'][$arrayEntrada]) &&
							empty($relatorio[$dataDia]['operacoes'][$arraySaida]))
						) {
							$relatorio[$dataDia]['status_saldo'] = 'alerta';
						}

						if (!empty($relatorio[$dataDia]['operacoes'][$arrayEntrada])) {
							if (!empty($relatorio[$dataDia]['operacoes'][$arraySaida])) {
								$entrada = hora_para_segundos($relatorio[$dataDia]['operacoes'][$arrayEntrada]['horario']);
								$saida = hora_para_segundos($relatorio[$dataDia]['operacoes'][$arraySaida]['horario']);

								if ($entrada <= $saida) {
									$horasTrabalhadas = $saida - $entrada;
								} else {
									$horasTrabalhadas = 86400 - $entrada;
									$horasTrabalhadas += $saida;
								}

								$horasTrabalhadas += hora_para_segundos($relatorio[$dataDia]['horas_trabalhadas']);
								$horasTrabalhadas = segundos_para_hora($horasTrabalhadas);
								$relatorio[$dataDia]['horas_trabalhadas'] = $horasTrabalhadas;
							}
						}
					}
				}

				// TOLERANCIA DE 5 MIN DO PONTO ELETRONICO
				$tolerancia = 0;

				if (
					!empty($relatorio[$dataDia]['operacoes']['entrada1']) &&
					!empty($relatorio[$dataDia]['operacoes']['saida1'])
				) {
					$segundosEntrada1 = hora_para_segundos($relatorio[$dataDia]['operacoes']['entrada1']['horario']);
					$segundosSaida1 = hora_para_segundos($relatorio[$dataDia]['operacoes']['saida1']['horario']);
					$segundosPontoEntrada1 = (isset($relatorio[$dataDia]['ponto_horario'])) ? hora_para_segundos($relatorio[$dataDia]['ponto_horario']->entrada1) : null;
					$segundosPontoSaida1 = (isset($relatorio[$dataDia]['ponto_horario'])) ? hora_para_segundos($relatorio[$dataDia]['ponto_horario']->saida1) : null;

					if ( // 17:00 > 16:57 e 16:55 <= 16:57 -> 17:00 - 16:57 = 00:03
						$segundosPontoEntrada1 > $segundosEntrada1 &&
						($segundosPontoEntrada1 - 300) <= $segundosEntrada1
					) {
						$tolerancia -= ($segundosPontoEntrada1 - $segundosEntrada1);
					} elseif ( // 17:00 < 17:03 e 17:05 >= 17:03 -> 17:03 - 17:00 = 00:03
						$segundosPontoEntrada1 < $segundosEntrada1 &&
						($segundosPontoEntrada1 + 300) >= $segundosEntrada1
					) {
						$tolerancia += ($segundosEntrada1 - $segundosPontoEntrada1);
					}

					if ( // 17:00 < 17:03 e 17:05 >= 17:03 -> 17:00 - 17:03 = 00:03
						$segundosPontoSaida1 < $segundosSaida1 &&
						($segundosPontoSaida1 + 300) >= $segundosSaida1
					) {
						$tolerancia -= ($segundosSaida1 - $segundosPontoSaida1);
					} elseif ( // 17:00 > 16:57 e 16:55 <= 16:57 -> 17:00 - 16:57 = 00:03
						$segundosPontoSaida1 > $segundosSaida1 &&
						($segundosPontoSaida1 - 300) <= $segundosSaida1
					) {
						$tolerancia += ($segundosPontoSaida1 - $segundosSaida1);
					}
				}

				if (
					!empty($relatorio[$dataDia]['operacoes']['entrada2']) &&
					!empty($relatorio[$dataDia]['operacoes']['saida2'])
				) {
					$segundosEntrada2 = hora_para_segundos($relatorio[$dataDia]['operacoes']['entrada2']['horario']);
					$segundosSaida2 = hora_para_segundos($relatorio[$dataDia]['operacoes']['saida2']['horario']);
					$segundosPontoEntrada2 = (isset($relatorio[$dataDia]['ponto_horario'])) ? hora_para_segundos($relatorio[$dataDia]['ponto_horario']->entrada2) : null;
					$segundosPontoSaida2 = (isset($relatorio[$dataDia]['ponto_horario'])) ? hora_para_segundos($relatorio[$dataDia]['ponto_horario']->saida2): null;

					if ( // 17:00 > 16:57 e 16:55 <= 16:57 -> 17:00 - 16:57 = 00:03
						$segundosPontoEntrada2 > $segundosEntrada2 &&
						($segundosPontoEntrada2 - 300) <= $segundosEntrada2
					) {
						$tolerancia -= ($segundosPontoEntrada2 - $segundosEntrada2);
					} elseif ( // 17:00 < 17:03 e 17:05 >= 17:03 -> 17:03 - 17:00 = 00:03
						$segundosPontoEntrada2 < $segundosEntrada2 &&
						($segundosPontoEntrada2 + 300) >= $segundosEntrada2
					) {
						$tolerancia += ($segundosEntrada2 - $segundosPontoEntrada2);
					}

					if ( // 17:00 < 17:03 e 17:05 >= 17:03 -> 17:00 - 17:03 = 00:03
						$segundosPontoSaida2 < $segundosSaida2 &&
						($segundosPontoSaida2 + 300) >= $segundosSaida2
					) {
						$tolerancia -= ($segundosSaida2 - $segundosPontoSaida2);
					} elseif ( // 17:00 > 16:57 e 16:55 <= 16:57 -> 17:00 - 16:57 = 00:03
						$segundosPontoSaida2 > $segundosSaida2 &&
						($segundosPontoSaida2 - 300) <= $segundosSaida2
					) {
						$tolerancia += ($segundosPontoSaida2 - $segundosSaida2);
					}
				}

				// HORAS ABONO

				if (!empty($ponto_abonos)) {
					foreach($ponto_abonos as $ponto_abono) {
						if ($ponto_abono->data_abono == $dataDia) {
							$relatorio[$dataDia]['horas_abono'] = $ponto_abono->tempo;
						}
					}
				}

				// REMOVE DO SALDO O VALOR DE TOLERANCIA
				$horasDia = hora_para_segundos($relatorio[$dataDia]['horas_dia']);
				$horasTrabalhadas = hora_para_segundos($relatorio[$dataDia]['horas_trabalhadas']);
				$relatorio[$dataDia]['saldo_dia'] = $horasTrabalhadas - $horasDia;
				$relatorio[$dataDia]['saldo_dia'] += $tolerancia;
				$relatorio[$dataDia]['saldo_dia'] += hora_para_segundos($relatorio[$dataDia]['horas_abono']);

				if (empty($relatorio[$dataDia]['status_saldo'])) {
					if ($relatorio[$dataDia]['saldo_dia'] > 0) {
						$relatorio[$dataDia]['status_saldo'] = 'positivo';
					} else if ($relatorio[$dataDia]['saldo_dia'] < 0) {
						$relatorio[$dataDia]['status_saldo'] = 'negativo';
					}
				}
				
				$relatorio[$dataDia]['saldo_dia'] = segundos_para_hora($relatorio[$dataDia]['saldo_dia']);

			}
		}

		return ['relatorio' => $relatorio, 'ponto' => ['descricao' => $ponto_descricao, 'horarios' => $ponto_horarios]];
	  }
	  
	  /**
       * PontoEletronico::obterPontosDiaFuncionario()
       * 
       * @return
       */
      public function obterPontosDiaFuncionario($dataAtual,$id_funcionario)
      {
		  $sql = "SELECT * "
		    . "\n FROM ponto_eletronico "
			. "\n WHERE DATE_FORMAT(data_operacao, '%Y-%m-%d')='$dataAtual' AND id_usuario = $id_funcionario AND inativo = 0 "
			. "\n ORDER BY DATE_FORMAT(data_operacao, '%H:%i:%s') ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }

	  /**
       * PontoEletronico::obterUsuariosPonto()
       * 
       * @return
       */
      public function obterUsuariosPonto($id_tabela_ponto)
      {
		  $sql = "SELECT count(id) as usuarios "
		    . "\n FROM usuario "
			. "\n WHERE id_tabela_ponto=$id_tabela_ponto";
          $row = self::$db->first($sql);
          return ($row) ? $row->usuarios : 0;
      }

	  /**
       * PontoEletronico::processarPontoAbono()
       * 
       * @return
       */
      public function processarPontoAbono()
      {
		if (empty($_POST['tempo']))
			Filter::$msgs['tempo'] = lang('MSG_ERRO_PONTOABONOTEMPO');
		if (empty($_POST['datas']))
			Filter::$msgs['datas'] = lang('MSG_ERRO_PONTOABONODIA');

		if (empty(Filter::$msgs)) {
			$id_usuario = $_POST['id_usuario'];
			$tempo = str_replace(' ', '', $_POST['tempo']) . ':00';
			$datas = explode(',', $_POST['datas']);

			foreach ($datas as $data) {
				$id = getValue('id', 'ponto_abono', "data_abono = '{$data}'");

				$data_abono = [
					'id_usuario' => $id_usuario,
					'data_abono' => $data,
					'tempo' => $tempo,
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				];

				if (empty($id)) {
					self::$db->insert("ponto_abono", $data_abono);
				} else {
					self::$db->update("ponto_abono", $data_abono, 'id=' . $id);
				}

				$message = lang('PONTO_HORARIO_EDITAR_OK');

				if (self::$db->affected()){
					Filter::msgOk($message);
				} else
					Filter::msgAlert(lang('NAOPROCESSADO'));
			}
		} else
			print Filter::msgStatus();
      }
	  
	  /**
       * PontoEletronico::getFeriados()
       * 
       * @return
       */
      public function getFeriados()
      {
		  $sql = "SELECT * FROM feriados";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * PontoEletronico::e_feriado()
       * 
       * @return
       */
      public function e_feriado($data)
      {
		  $sql = "SELECT id FROM feriados WHERE data = '$data'";
          $row = self::$db->first($sql);
          return ($row) ? $row->id : 0;
      }
	  
	  /**
       * PontoEletronico::processarFeriado()
       * 
       * @return
       */
	   public function processarFeriado()
	   {
			if (empty($_POST['feriado']))
				Filter::$msgs['feriado'] = lang('MSG_ERRO_FERIADO');
		   
			if (empty($_POST['data']))
				Filter::$msgs['data'] = lang('MSG_ERRO_DATA');
		   
			if (empty(Filter::$msgs)) {
				$data = array(
					'feriado' => sanitize($_POST['feriado']),
					'data' => dataMySQL($_POST['data'])
				);
				(Filter::$id) ? self::$db->update("feriados", $data, "id=" . Filter::$id) : self::$db->insert("feriados", $data);
				
				$message = (Filter::$id) ? lang('FERIADO_EDITADO_OK') : lang('FERIADO_ADICIONADO_OK');
				if (self::$db->affected()){
					Filter::msgOk($message, "index.php?do=ponto_eletronico&acao=feriadolistar");
				} else
					Filter::msgAlert(lang('NAOPROCESSADO'));
			} else 
				print Filter::msgStatus();
	   }

  }
?>