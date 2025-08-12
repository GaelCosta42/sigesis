<?php
  /**
   * Classe OrdemServico
   *
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe nao e permitido.');

  class OrdemServico
  {
      const uTable = "ordem_servico";
      public $did = 0;
      public $empresaid = 0;
      private static $db;

      /**
       * OrdemServico::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
       * OrdemServico::getOrdemServico()
       * 
       * @return
       */
      public function getOrdemServico()
      {
		  $sql = "SELECT os.*, eq.equipamento, eq.etiqueta, cad.nome, cad.cidade, cad.celular, s.status, s.descricao as descricaoStatus "
		  ."\n FROM ordem_servico AS os"
		  ."\n LEFT JOIN equipamento AS eq ON eq.id = os.id_equipamento"
		  ."\n LEFT JOIN cadastro as cad on cad.id = os.id_cadastro"
		  ."\n LEFT JOIN ordem_status as s on s.id = os.id_status"
		  ."\n WHERE os.inativo = 0 AND os.id_status <= 4";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * OrdemServico::getOrdensServicoCadastro(id_cadasdtro)
       * 
       * @return
       */
      public function getOrdensServicoCadastro($id_cadastro)
      {
		  $sql = "SELECT os.*, eq.equipamento, eq.etiqueta, cad.nome, cad.cidade, cad.celular, s.status, s.descricao as descricaoStatus "
		  ."\n FROM ordem_servico AS os"
		  ."\n LEFT JOIN equipamento AS eq ON eq.id = os.id_equipamento"
		  ."\n LEFT JOIN cadastro as cad on cad.id = os.id_cadastro"
		  ."\n LEFT JOIN ordem_status as s on s.id = os.id_status"
		  ."\n WHERE os.inativo = 0 AND os.id_cadastro = $id_cadastro";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * OrdemServico::getOrdemServicoExecucao()
       * 
       * @return
       */
      public function getOrdemServicoExecucao()
      {
		  $sql = "SELECT os.*, eq.equipamento, eq.etiqueta, cad.nome, cad.cidade, s.status, s.descricao as descricaoStatus "
		  ."\n FROM ordem_servico AS os"
		  ."\n LEFT JOIN equipamento AS eq ON eq.id = os.id_equipamento"
		  ."\n LEFT JOIN cadastro as cad on cad.id = os.id_cadastro"
		  ."\n LEFT JOIN ordem_status as s on s.id = os.id_status"
		  ."\n WHERE os.inativo = 0 AND os.id_status >= 5 AND os.id_status <= 6";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * OrdemServico::getOrdemServicoFinalizada()
       * 
       * @return
       */
      public function getOrdemServicoFinalizada($datainicial, $datafinal)
      {
		  $datainicial = dataMySQL($datainicial);
		  $datafinal = dataMySQL($datafinal);
		  
		  $sql = "SELECT os.*, eq.equipamento, eq.etiqueta, cad.nome, cad.cidade, s.status, s.descricao as descricaoStatus "
		  ."\n FROM ordem_servico AS os"
		  ."\n LEFT JOIN equipamento AS eq ON eq.id = os.id_equipamento"
		  ."\n LEFT JOIN cadastro as cad on cad.id = os.id_cadastro"
		  ."\n LEFT JOIN ordem_status as s on s.id = os.id_status"
		  ."\n WHERE os.inativo = 0 AND os.id_status >= 7 AND os.id_status <= 8 AND DATE_FORMAT(os.data_execucao, '%Y-%m-%d') >= '$datainicial' AND DATE_FORMAT(os.data_execucao, '%Y-%m-%d') <= '$datafinal'";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	 /**
       * OrdemServico::getOrdemServicoImpressao($id_ordem_servico)
       * 
       * @return
       */
      public function getOrdemServicoImpressao($id_ordem_servico)
      {
		  $sql = "SELECT os.*, eq.equipamento, eq.etiqueta, cad.nome AS cliente, cad.cidade AS cidadecliente, cad.celular AS celularcliente, cad.telefone as fixocliente, cad.email as emailcliente, s.status, em.nome as nomeempresa, em.telefone as telefoneempresa, em.celular as celularempresa, em.email as emailempresa "
		  ."\n FROM ordem_servico AS os"
		  ."\n LEFT JOIN equipamento AS eq ON eq.id = os.id_equipamento"
		  ."\n LEFT JOIN cadastro as cad on cad.id = os.id_cadastro"
		  ."\n LEFT JOIN ordem_status as s on s.id = os.id_status"
		  ."\n LEFT JOIN empresa as em on em.id = os.id_empresa"
		  ."\n WHERE os.inativo = 0 AND os.id = $id_ordem_servico";
          $row = self::$db->first($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * OrdemServico::getServicoRealizadoOS($id_ordem_servico)
       * 
       * @return
       */
      public function getServicoRealizadoOS($id_ordem_servico)
      {
		  $sql = "SELECT * "
		  ."\n FROM ordem_atendimento "
		  ."\n WHERE inativo = 0 AND id_ordem = $id_ordem_servico";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * OrdemServico::getDetalhamentoImpressao($id_ordem_servico)
       * 
       * @return
       */
      public function getDetalhamentoImpressao($id_ordem_servico)
      {
		  $sql = "SELECT os.*, eq.equipamento, eq.codigo_referencia, eq.etiqueta "
		  ."\n FROM ordem_servico AS os "
		  ."\n LEFT JOIN equipamento AS eq ON eq.id = os.id_equipamento "
		  ."\n WHERE os.inativo = 0 AND os.id = $id_ordem_servico ";
          $row = self::$db->first($sql);
          return ($row) ? $row : 0;
      }

      /**
       * OrdemServico::getOrdemServicoAbertas()
       * 
       * @return
       */
      public function getOrdemServicoAbertas()
      {
		  $sql = "SELECT o.*, cad.nome, s.status, b.banco, e.equipamento, f.id as id_financeiro, f.fiscal, f.id_nota, DATE_FORMAT(o.data_vencimento, '%Y%m%d') as controle, o.data_vencimento < CURDATE() as atrasado, o.data_vencimento = CURDATE() as hoje "
		  ."\n FROM ordem_servico as o"
		  ."\n LEFT JOIN cadastro_financeiro as f on o.id = f.id_ordem"
		  ."\n LEFT JOIN cadastro as cad on cad.id = o.id_cadastro"
		  ."\n LEFT JOIN banco as b on b.id = o.id_banco"
		  ."\n LEFT JOIN equipamento as e on e.id = o.id_equipamento"
		  ."\n LEFT JOIN ordem_status as s on s.id = o.id_status"
		  ."\n WHERE o.inativo = 0 AND o.id_status = 1";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * OrdemServico::getOrdemServicoResponsavel()
       * 
       * @return
       */
      public function getOrdemServicoResponsavel($id_responsavel = 0)
      {
		  $sql = "SELECT o.*, cad.nome, s.status, b.banco, e.equipamento, f.id as id_financeiro, f.fiscal, f.id_nota, DATE_FORMAT(o.data_vencimento, '%Y%m%d') as controle, o.data_vencimento < CURDATE() as atrasado, o.data_vencimento = CURDATE() as hoje "
		  ."\n FROM ordem_servico as o"
		  ."\n LEFT JOIN cadastro_financeiro as f on o.id = f.id_ordem"
		  ."\n LEFT JOIN cadastro as cad on cad.id = o.id_cadastro"
		  ."\n LEFT JOIN banco as b on b.id = o.id_banco"
		  ."\n LEFT JOIN equipamento as e on e.id = o.id_equipamento"
		  ."\n LEFT JOIN ordem_status as s on s.id = o.id_status"
		  ."\n WHERE o.inativo = 0 AND o.id_status < 9 AND o.id_responsavel = '$id_responsavel'";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  	  
      /**
       * OrdemServico::processarOrdemServico()
       * 
       * @return
       */
      public function processarOrdemServico()
      {
		  if (empty($_POST['id_empresa']))
              Filter::$msgs['id_empresa'] = lang('MSG_ERRO_EMPRESA');
		  
		  if (empty($_POST['id_cadastro_os']))
              Filter::$msgs['id_cadastro_os'] = lang('MSG_ERRO_CLIENTE');
		  
		  if (empty($_POST['id_tabela_os']))
              Filter::$msgs['id_tabela_os'] = lang('MSG_ERRO_TABELA_PRECO');
		  
		  if (empty($_POST['id_equipamento_os']) AND empty($_POST['nome_equipamento']))
              Filter::$msgs['id_equipamento_os'] = lang('MSG_ERRO_EQUIPAMENTO');

          if (empty(Filter::$msgs)) {
			  
              $data = array(
					'id_empresa' => sanitize(post('id_empresa')), 
					'id_cadastro' => sanitize(post('id_cadastro_os')),
					'id_tabela' => sanitize(post('id_tabela_os')),
					'id_equipamento' => sanitize(post('id_equipamento_os')),
					'equipamento_digitado' => sanitize(post('nome_equipamento')),
					'responsavel' => sanitize(post('responsavel_cliente')),
					'criticidade' => sanitize(post('criticidade')),
					'prioridade' => sanitize(post('prioridade')),
					'descricao_equipamento' => sanitize(post('descricao_equipamento')), 
					'descricao_problema' => sanitize(post('descricao_problema')), 
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  
			  if(Filter::$id) {
				$id_ordem = Filter::$id;
				self::$db->update("ordem_servico", $data, "id=" . Filter::$id);
				$message = lang('ORCAMENTO_AlTERADO_OK');
			  } else {
				$data['data_abertura'] = "NOW()";
				$data['usuario_abertura'] = session('nomeusuario');
				$data['id_status'] = '1';
				$id_ordem = self::$db->insert("ordem_servico", $data);
				$message = lang('ORCAMENTO_ADICIONADO_OK');
			  }
			  
              if (self::$db->affected()) {			  
                  Filter::msgOk($message, "index.php?do=ordem_servico&acao=orcamentos"); 
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * OrdemServico::processarOrcamento_OrdemServico()
       * 
       * @return
       */
      public function processarOrcamento_OrdemServico()
      {
          if (empty(Filter::$msgs)) {
			  $id_orcamento = sanitize(post('id'));
              $data = array(
					'descricao_orcamento' => sanitize(post('descricao_orcamento')), 
					'tempo_servico' => sanitize(post('tempo_servico')), 
					'data_orcamento' => "NOW()", 
					'usuario_orcamento' => session('nomeusuario'), 
					'id_status' => '2',
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->update("ordem_servico", $data, "id=" .$id_orcamento);
			  $message = lang('ORCAMENTO_DESCRICAO_OK');
			  
              if (self::$db->affected()) {			  
                  Filter::msgOk($message, "index.php?do=ordem_servico&acao=visualizarorcamento&id=".$id_orcamento); 
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }	  
	  
	  /**
       * OrdemServico::processarOS_Atendimento()
       * 
       * @return
       */
      public function processarOS_Atendimento()
      {
		  if (empty($_POST['servico_realizado']))
              Filter::$msgs['servico_realizado'] = lang('MSG_ERRO_DESCRICAO_OS_REALIZADA');
		  
		  if (empty($_POST['hora_inicio']))
              Filter::$msgs['hora_inicio'] = lang('MSG_ERRO_HORA_INICIO_OS');
		  
		  if (empty($_POST['hora_fim']))
              Filter::$msgs['hora_fim'] = lang('MSG_ERRO_HORA_TERMINO_OS');

          if (empty(Filter::$msgs)) {
			  
			  $id_os = sanitize(post('id'));
			  $hora_ini = sanitize(post('hora_inicio'));
			  $hora_fim = sanitize(post('hora_fim'));
			  $descricao_atendimento = sanitize(post('servico_realizado'));
			  $dataAtual = date('Y-m-d');
			  $data_hora_ini = $dataAtual.' '.$hora_ini.':00';
			  $data_hora_fim = $dataAtual.' '.$hora_fim.':00';
			  
              $data_atendimento = array(
					'id_ordem' => $id_os, 
					'id_responsavel' => sanitize(post('id_usuario')), 
					'descricao_solucao' => $descricao_atendimento, 
					'data_inicio' => $data_hora_ini,
					'data_fim' => $data_hora_fim,
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->insert("ordem_atendimento", $data_atendimento);
			  
			  $data = array(
					'data_execucao' => "NOW()", 
					'usuario_execucao' => session('nomeusuario'), 
					'id_status' => '6',
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->update("ordem_servico", $data, "id=" .$id_os);
			  
			  $message = lang('ORDEM_SERVICO_EXECUTAR_DESCRICAO_OK');
			  if (self::$db->affected()) {			  
                  Filter::msgOk($message, "index.php?do=ordem_servico&acao=listar"); 
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));

          } else
              print Filter::msgStatus();
      }	  	  
	  
	  /**
       * OrdemServico::processarValorOrcamento()
       * 
       * @return
       */
      public function processarValorOrcamento()
      {
			$id_orcamento = sanitize(post('id'));
			$valor_servico = converteMoeda(post('valor_servico'));
			$valor_adicional = converteMoeda(post('valor_adicional'));
			$valor_desconto = converteMoeda(post('valor_desconto'));
			$valor_produto = getValue("valor_produto","ordem_servico","id=".$id_orcamento);

			if ($valor_desconto > ($valor_servico + $valor_produto + $valor_adicional))  
				$valor_total = 0;
			else
				$valor_total = ($valor_servico + $valor_produto + $valor_adicional) - $valor_desconto;
			
            $data = array(
				'valor_servico' => $valor_servico, 
				'valor_desconto' => $valor_desconto, 
				'valor_total' => $valor_total, 
				'prazo_entrega' => sanitize(post('prazo_entrega')), 
				'condicao_pagamento' => sanitize(post('condicao_pagamento')), 
				'garantia' => sanitize(post('garantia')), 
				'id_status' => '4',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->update("ordem_servico", $data, "id=" .$id_orcamento);
			$message = lang('ORCAMENTO_VALOR_OK');
			  
            if (self::$db->affected()) {			  
                Filter::msgOk($message, "index.php?do=ordem_servico&acao=orcamentos"); 
            } else
                Filter::msgAlert(lang('NAOPROCESSADO'));
      }	  
	  
	  /**
       * OrdemServico::getOrdemServicoFaturar()
       * 
       * @return
       */
      public function getOrdemServicoFaturar()
      {
		  $sql = "SELECT os.*, eq.equipamento, eq.etiqueta, cad.nome, cad.cidade, s.status, s.descricao as descricaoStatus "
		  ."\n FROM ordem_servico AS os"
		  ."\n LEFT JOIN equipamento AS eq ON eq.id = os.id_equipamento"
		  ."\n LEFT JOIN cadastro as cad on cad.id = os.id_cadastro"
		  ."\n LEFT JOIN ordem_status as s on s.id = os.id_status"
		  ."\n WHERE os.inativo = 0 AND os.id_status = 7 ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * OrdemServico::processarItemOrdemServico()
       * 
       * @return
       */
      public function processarItemOrdemServico()
      {	
			if (empty($_POST['id_produto']))
              Filter::$msgs['id_produto'] = lang('MSG_ERRO_PRODUTO');
		  		  
          if (empty(Filter::$msgs)) {
			  $tabela = post('id_tabela');
			  $produto = post('id_produto');
			  $valor = getValue("valor_venda","produto_tabela","id_tabela=".$tabela." AND id_produto=".$produto);
			  $quantidade = (empty($_POST['quantidade'])) ? 1 : converteMoeda(post('quantidade'));
			  $valor_total = floatval($valor)*floatval($quantidade);
			  
			  $data = array(
				'id_ordem' => post('id_ordem'),
				'id_cadastro' => post('id_cadastro'),
				'descricao' => sanitize(post('descricao')),
				'id_produto' => $produto,
				'valor' => $valor, 
				'quantidade' => $quantidade, 
				'valor_total' => $valor_total, 
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  self::$db->insert("ordem_itens", $data);
			  $message = lang('ORCAMENTO_ADICIONADO_ITEM_OK');
              if (self::$db->affected()) {	
				  $produto_orcamento = getValue("valor_produto","ordem_servico","id=".post('id_ordem'));
				  $valor_produto_orcamento = $produto_orcamento + $valor_total;
				  $data_orcamento = array(
					'valor_produto' => $valor_produto_orcamento,
					'valor_total' => $valor_produto_orcamento,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				  );
				  self::$db->update("ordem_servico", $data_orcamento, "id=" .post('id_ordem'));
                  Filter::msgOk($message, "index.php?do=ordem_servico&acao=visualizarorcamento&id=".post('id_ordem'));  
              } else
                  Filter::msgOk(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }	
	  
	  /**
       * OrdemServico::processarValorAdicionalOrdemServico()
       * 
       * @return
       */
      public function processarValorAdicionalOrdemServico()
      {	
			if (empty($_POST['descricao_adicional']))
              Filter::$msgs['descricao_adicional'] = lang('MSG_ERRO_DESCRICAO');
			  
			if (empty($_POST['valor_adicional']))
              Filter::$msgs['valor_adicional'] = lang('MSG_ERRO_VALOR');  
		  		  
          if (empty(Filter::$msgs)) {
			  $id_ordem_servico = post('id_ordem');
			  $descricao_adicional = sanitize(post('descricao_adicional'));
			  $valor_adicional = converteMoeda(post('valor_adicional'));

			  $data_adicional = array(
				'id_ordem_servico' => $id_ordem_servico,
				'descricao' => $descricao_adicional,
				'valor_adicional' => $valor_adicional,
				'inativo' => '0',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  self::$db->insert("ordem_valor_adicional", $data_adicional);

			  $message = lang('ORCAMENTO_VALOR_ADICIONAL_TITULO_OK');
              if (self::$db->affected()) {	
				  $valor_adicional_os = getValue("valor_adicional","ordem_servico","id=".$id_ordem_servico);
				  $valor_total_os = getValue("valor_total","ordem_servico","id=".$id_ordem_servico);
				  
				  $novo_adicional = $valor_adicional_os + $valor_adicional;
				  $novo_total = $valor_total_os + $valor_adicional;

				  $data_os = array(
					'valor_adicional' => $novo_adicional,
					'valor_total' => $novo_total,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				  );
				  self::$db->update("ordem_servico", $data_os, "id=" .$id_ordem_servico);
                  Filter::msgOk($message, "index.php?do=ordem_servico&acao=gerenciarvalororcamento&id=".$id_ordem_servico);  
              } else
                  Filter::msgOk(lang('NAOPROCESSADO'));
				  
          } else
              print Filter::msgStatus();
      }	

      /**
       * OrdemServico::getItensOrdemServico($id_ordem_servico)
       * 
       * @return
       */
      public function getItensOrdemServico($id_ordem_servico)
      {
		  $sql = "SELECT o.*, p.nome as produto "
		  ."\n FROM ordem_itens as o"
		  ."\n LEFT JOIN produto as p on p.id = o.id_produto"
		  ."\n WHERE o.inativo = 0 AND o.id_ordem = $id_ordem_servico";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * OrdemServico::getValoresAdicionaisOrdemServico($id_ordem_servico)
       * 
       * @return
       */
      public function getValoresAdicionaisOrdemServico($id_ordem_servico)
      {
		  $sql = "SELECT * FROM ordem_valor_adicional WHERE inativo = 0 AND id_ordem_servico = $id_ordem_servico";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }

    /**
     * OrdemServico::processarNovoValorProdutoOrdemServico()
     * 
     * @return
     */
    public function processarNovoValorProdutoOrdemServico()
    {
        if (empty($_POST['id_ordem']))
            Filter::$msgs['id_ordem'] = lang('MSG_ERRO_ORCAMENTO');
        else
            $id_ordem_servico = post('id_ordem');

        if (empty($_POST['id_ordem_itens']))
            Filter::$msgs['id_ordem_itens'] = lang('MSG_ERRO_ORDEM_ITEM');
        else
            $id_ordem_itens = post('id_ordem_itens');

        if (empty($_POST['novo_valor_produto_os']))
            Filter::$msgs['novo_valor_produto_os'] = lang('MSG_ERRO_VALOR_PRODUTO');
        else
            $novo_valor_produto_os = converteMoeda(post('novo_valor_produto_os'));

        if (empty($_POST['nova_qtde_produto_os']))
            Filter::$msgs['nova_qtde_produto_os'] = lang('MSG_ERRO_QUANTIDADE_PRODUTO');
        else
            $nova_qtde_produto_os = converteMoeda(post('nova_qtde_produto_os'));

        if (empty(Filter::$msgs)) {
            $row_os = Core::getRowById("ordem_servico", $id_ordem_servico);

            $data_ordem_item = array(
                'quantidade' => $nova_qtde_produto_os,
                'valor' => $novo_valor_produto_os,
                'valor_total' => $nova_qtde_produto_os * $novo_valor_produto_os,
                'usuario' => session('nomeusuario'),
				'data' => "NOW()"
            );
            self::$db->update("ordem_itens", $data_ordem_item, "id=" .$id_ordem_itens);
            if (self::$db->affected()) {
                $valor_servico = $row_os->valor_servico;
                $valor_adicional = $row_os->valor_adicional;
                $valor_desconto = $row_os->valor_desconto;

                $novo_valor_produtos = $this->ObterValorProdutosOrdemServico($id_ordem_servico);

                $novo_valor_total = $novo_valor_produtos + $valor_servico + $valor_adicional - $valor_desconto;

                $data_ordem_servico = array(
                    'valor_produto' => $novo_valor_produtos,
                    'valor_total' => $novo_valor_total,
                    'usuario' => session('nomeusuario'),
				    'data' => "NOW()"
                );
                self::$db->update("ordem_servico", $data_ordem_servico, "id=" .$id_ordem_servico);
                $message = lang('ORCAMENTO_ALTERAR_VALOR_PRODUTO');
                Filter::msgOk($message, "index.php?do=ordem_servico&acao=gerenciarvalororcamento&id=".$id_ordem_servico);

            } else {
                Filter::msgAlert(lang('MSG_ERRO_ALTERAR_VALOR_PRODUTO'));
            }

        } else
            print Filter::msgStatus();
    }

    /**
     * OrdemServico::ObterValorProdutosOrdemServico($id_ordem_servico)
     * 
     * @return
     */
    public function ObterValorProdutosOrdemServico($id_ordem_servico)
    {
        $sql = " SELECT SUM(valor_total) AS valor_total  FROM ordem_itens WHERE id_ordem=$id_ordem_servico AND inativo=0 ";
        $row = self::$db->first($sql);
        return ($row) ? $row->valor_total : 0;
    }
	  
	  /**
       * OrdemServico::getAtendimentosOrdemServico($id_ordem_servico)
       * 
       * @return
       */
      public function getAtendimentosOrdemServico($id_ordem_servico)
      {
		  $sql = "SELECT oa.*, us.nome "
		  ."\n FROM ordem_atendimento AS oa"
		  ."\n LEFT JOIN usuario AS us ON us.id = oa.id_responsavel "
		  ."\n WHERE oa.inativo = 0 AND oa.id_ordem = $id_ordem_servico";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  	  
	  /**
       * OrdemServico::getReceitasOrdemServico()
       * 
       * @return
       */
      public function getReceitasOrdemServico($id_ordem = 0)
      {		  
		  $sql = "SELECT f.id, c.conta, f.id_conta, f.id_banco, b.banco, f.id_nota, f.descricao, f.tipo, f.id_cadastro, f.valor, f.valor_pago, f.conciliado, f.data_recebido, f.data_pagamento, f.pago, f.fiscal, f.enviado, f.remessa, t.tipo as pagamento, DATE_FORMAT(f.data_pagamento, '%Y%m%d') as controle, f.data_pagamento < CURDATE() as atrasado, e.nome as empresa, f.duplicata, f.id_empresa" 
		  . "\n FROM receita as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta" 
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo" 
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco" 
		  . "\n LEFT JOIN empresa as e ON e.id = f.id_empresa" 
		  . "\n LEFT JOIN cadastro_financeiro as cf ON cf.id = f.id_pagamento" 
		  . "\n WHERE f.inativo = 0 AND cf.inativo = 0 AND cf.id_ordem = '$id_ordem' "
		  . "\n ORDER BY f.data_pagamento, f.data_recebido";
		  //echo $sql;
		  $row = ($id_ordem) ? self::$db->fetch_all($sql) : 0;

          return ($row) ? $row : 0;
	} 
	  
      /**
       * OrdemServico::processarNotaFiscalServico()
       * 
       * @return
       */
      public function processarNotaFiscalServico()
      {		  		  
		  if (empty($_POST['id_empresa']))
              Filter::$msgs['id_empresa'] = lang('MSG_ERRO_EMPRESA');
		  
		  if (empty($_POST['id_cadastro']))
              Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_NOME');

          if (empty(Filter::$msgs)) {
			  
			  $cpf_cnpj = getValue('cpf_cnpj', 'cadastro', 'id='.post('id_cadastro'));
              $cfop=0;
			  $descriminacao = (empty($_POST['descriminacao'])) ? getValue("descricao", "cfop", "cfop='$cfop'") : sanitize(post('descriminacao'));
			  $valor_servico = converteMoeda(post('valor_servico'));
			  $id_financeiro = post('id_financeiro');
			  $iss_retido = post('iss_retido');
			  $iss_aliquota = post('iss_aliquota');
			  $valor_iss = $valor_servico*($iss_aliquota/100);
			  $valor_nota = ($iss_retido) ? $valor_servico-$valor_iss : $valor_servico;
			  
			  $data = array(
				'id_empresa' => sanitize(post('id_empresa')),
				'modelo' => '1',
				'operacao' => '2',
				'id_cadastro' => sanitize(post('id_cadastro')),
				'cpf_cnpj' => $cpf_cnpj, 
				'numero_nota' => "NULL", 
				'data_emissao' => "NOW()",
				'iss_retido' => $iss_retido, 
				'iss_aliquota' => $iss_aliquota, 
				'valor_iss' => $valor_iss, 
				'descriminacao' => $descriminacao, 
				'valor_servico' => $valor_servico, 
				'valor_nota' => $valor_nota, 
				'id_financeiro' => $id_financeiro, 
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );			  
			  $id_nota = self::$db->insert("nota_fiscal", $data);
			  $data_financeiro = array(
				'fiscal' => '1', 
				'id_nota' => $id_nota, 
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  self::$db->update("cadastro_financeiro", $data_financeiro, 'id='.$id_financeiro);
			  self::$db->update("receita", $data_financeiro, 'id_pagamento='.$id_financeiro);
			  $message = lang('NOTA_ADICIONADO_OK');
              if ($id_nota) {		  
                  Filter::msgOk($message, "index.php?do=notafiscal&acao=visualizar_servico&id=".$id_nota);  
              } else
                  Filter::msgOk(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }	  	  
	  
      /**
       * OrdemServico::editarNotaFiscalServico()
       * 
       * @return
       */
      public function editarNotaFiscalServico()
      {	
          $cfop = 0;
          $valor_servico = 0;
          $iss_retido = 0;
          $id_nota = Filter::$id;

          if (empty(Filter::$msgs)) {
			  
			  $descriminacao = (empty($_POST['descriminacao'])) ? getValue("descricao", "cfop", "cfop='$cfop'") : post('descriminacao');
			  
			  $iss_aliquota = post('iss_aliquota');
			  $valor_iss = $valor_servico*($iss_aliquota/100);
			  $valor_nota = ($iss_retido) ? $valor_servico-$valor_iss : $valor_servico;
			  
			  $data = array(
				'iss_retido' => sanitize(post('iss_retido')),
				'iss_aliquota' => $iss_aliquota, 
				'valor_iss' => $valor_iss, 
				'descriminacao' => $descriminacao, 
				'valor_nota' => $valor_nota, 
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  self::$db->update("nota_fiscal", $data, 'id='.Filter::$id);
			  $message = lang('NOTA_ALTERADO_OK');
              if ($id_nota) {		  
                  Filter::msgOk($message, "index.php?do=notafiscal&acao=visualizar_servico&id=".Filter::$id);  
              } else
                  Filter::msgOk(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }	

      /**
	 * OrdemServico::getEquipamentos($id_cliente)
       * 
       * @return
       */
      public function getEquipamentos($id_cliente=0)
      {
		  $sql = "SELECT e.*, c.categoria "
		  ."\n FROM equipamento as e"
		  ."\n LEFT JOIN categoria as c on c.id = e.id_categoria"
		  ."\n WHERE e.inativo = 0 AND e.id_cliente = $id_cliente";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
	 * OrdemServico::getEquipamentosListagem()
       * 
       * @return
       */
      public function getEquipamentosListagem()
      {
		  $id_cliente = (post('id_cadastro')) ? post('id_cadastro') : 0;
		  $sql = "SELECT e.*, c.categoria, cad.nome as cliente "
		  ."\n FROM equipamento as e"
		  ."\n LEFT JOIN categoria as c on c.id = e.id_categoria"
		  ."\n LEFT JOIN cadastro as cad on cad.id = $id_cliente"
		  ."\n WHERE e.inativo = 0 AND e.id_cliente = $id_cliente";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
      /**
       * OrdemServico::processarEquipamento()
       * 
       * @return
       */
      public function processarEquipamento()
      {
		  if (empty($_POST['equipamento']))
              Filter::$msgs['equipamento'] = lang('MSG_ERRO_EQUIPAMENTO');
		  
		  if (empty($_POST['id_cadastro']))
              Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_CLIENTE');
		  
		  if (empty($_POST['codigo_referencia']))
              Filter::$msgs['codigo_referencia'] = lang('MSG_ERRO_REFERENCIA');

		  if (empty($_POST['etiqueta']))
              Filter::$msgs['etiqueta'] = lang('MSG_ERRO_ETIQUETA');

          if (empty(Filter::$msgs)) {

			$etiqueta_anterior = "";
			if (Filter::$id){
				$row_equipamento = Core::getRowById("equipamento", Filter::$id);
				$etiqueta_anterior = $row_equipamento->etiqueta_anterior;
				if (sanitize(post('etiqueta')) != $row_equipamento->etiqueta)
					$etiqueta_anterior .= $row_equipamento->etiqueta.';';
			}

            $data = array(
				'id_categoria' => sanitize(post('id_categoria')),
				'id_cliente' => sanitize(post('id_cadastro')),
				'equipamento' => sanitize(post('equipamento')),
				'codigo_referencia' => sanitize(post('codigo_referencia')),
				'etiqueta' => sanitize(post('etiqueta')),
				'etiqueta_anterior' => $etiqueta_anterior,
				'inativo' => 0,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			(Filter::$id) ? self::$db->update("equipamento", $data, "id=" . Filter::$id) : self::$db->insert("equipamento", $data);
            $message = (Filter::$id) ? lang('EQUIPAMENTO_ALTERADO_OK') : lang('EQUIPAMENTO_ADICIONADO_OK');

            if (self::$db->affected()) {
			    Filter::msgOk($message, "index.php?do=equipamento&acao=listar");   
            } else
                Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
	 * OrdemServico::getStatus()
       * 
       * @return
       */
      public function getStatus()
      {
		  $sql = "SELECT s.id, s.status "
		  ."\n FROM ordem_status as s";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
	 * OrdemServico::getCheckList()
       * 
       * @return
       */
      public function getCheckList()
      {
		  $sql = "SELECT c.id, c.item, c.id_grupo, g.grupo "
		  ."\n FROM ordem_checklist as c"
		  ."\n LEFT JOIN grupo as g on g.id = c.id_grupo"
		  ."\n WHERE c.inativo = 0 ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Grupo::processarCheckList()
       * 
       * @return
       */
      public function processarCheckList()
      {
		  if (empty($_POST['item']))
              Filter::$msgs['item'] = lang('MSG_ERRO_ITEM');	  

          if (empty(Filter::$msgs)) {

              $data = array(
				'item' => sanitize(post('item')),
				'id_grupo' => sanitize(post('id_grupo'))
			  );
              
              (Filter::$id) ? self::$db->update("ordem_checklist", $data, "id=" . Filter::$id) : self::$db->insert("ordem_checklist", $data);
              $message = (Filter::$id) ? lang('CHECKLIST_ALTERADO_OK') : lang('CHECKLIST_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=checklist&acao=listar");   
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
     
      /**
       * Ordem_Servico::cancelarOrcamento($id_orcamento)
       * 
       * @return
       */
      public function cancelarOrcamento($id_orcamento)
      {
		  if (empty($_POST['cancelar_motivo']))
              Filter::$msgs['cancelar_motivo'] = lang('MSG_ERRO_MOTIVO_CANCELAMENTO');
		  
		  if (empty($_POST['cancelar_equipamento']))
              Filter::$msgs['cancelar_equipamento'] = lang('MSG_ERRO_MOTIVO_EQUIPAMENTO');			  

          if (empty(Filter::$msgs)) {

				$data = array(
					'id_status' => '9',
					'motivo_cancelamento' => sanitize(post('cancelar_motivo')),
					'cancelamento_equipamento' => sanitize(post('cancelar_equipamento')),
					'inativo' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
              
              self::$db->update("ordem_servico", $data, "id=" . $id_orcamento);
              if (self::$db->affected()) {
                  Filter::msgOk(lang('ORCAMENTO_CANCELAR_OK'), "index.php?do=ordem_servico&acao=orcamentos");   
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
  }
?>