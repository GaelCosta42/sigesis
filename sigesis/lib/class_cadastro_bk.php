<?php
  /**
   * Classe Cadastro
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Cadastro
  {
      const uTable = "cadastro";
      public $did = 0;
      public $cadastroid = 0;
      private static $db;

      /**
       * Cadastro::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
       * Cadastro::getCadastroes()
       * 
       * @return
       */
      public function getCadastros($tipo = false)
      {
		  $where = "";
		  if($tipo == 'FORNECEDOR') {
			  $where = " AND c.fornecedor = 1 ";
		  } elseif($tipo == 'CLIENTE') {
			  $where = " AND c.cliente = 1 ";
		  }
		  $sql = "SELECT c.*, u.usuario AS vendedor FROM cadastro AS c LEFT JOIN usuario AS u ON u.id = c.id_vendedor WHERE c.oportunidade = 0 AND c.inativo = 0 $where ORDER BY c.nome, c.razao_social ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getTodosClienteAtivos()
       * 
       * @return
       */
      public function getTodosClienteAtivos()
      {
		  $sql = "SELECT c.*, u.usuario AS vendedor "
			. "\n FROM cadastro AS c "
			. "\n LEFT JOIN usuario AS u ON u.id = c.id_vendedor "
			. "\n WHERE c.inativo = 0 AND c.cliente = 1 "
		    . "\n ORDER BY c.nome, c.razao_social ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getEnderecosCadastro()
       * 
       * @return
       */
      public function getEnderecosCadastro($id_cadastro = 0)
      {
		  $sql = "SELECT id, id_cadastro, cep, endereco, numero, complemento, bairro, cidade, estado, referencia FROM cadastro_endereco WHERE inativo = 0 AND id_cadastro = $id_cadastro ORDER BY cep, endereco, numero ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      } 
	  
      /**
       * Cadastro::getEnderecos()
       * 
       * @return
       */
      public function getEnderecos()
      {
         $sql = "SELECT e.id, e.id_cadastro, e.cep, e.endereco, e.numero, e.bairro, e.cidade, e.referencia, c.nome  " 
		  . "\n FROM cadastro_endereco as e"
		  . "\n LEFT JOIN cadastro as c on c.id = e.id_cadastro"
		  . "\n WHERE e.inativo = 0 AND c.inativo = 0 " 
		  . "\n ORDER BY c.nome ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	  /**
	   * Cadastro::getListaClientes()
	   *
	   * @return
	   */
	   public function getListaClientes($nome = '')
      {
		$sql = "SELECT c.id, c.nome, c.telefone, c.cpf_cnpj "
		. "\n FROM cadastro as c"
		. "\n WHERE c.inativo = 0 AND c.nome LIKE '%$nome%'"
		. "\n ORDER BY c.nome ";
		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
      }
	  
	  /**
	   * Cadastro::getListaCadastros()
	   *
       * @return
       */
	  public function getListaCadastros($nome = '', $limite = 30)
      {
		$sql = "SELECT c.id, c.nome, c.razao_social, c.crediario, c.endereco, c.numero, c.bairro, c.cidade, c.cpf_cnpj, c.telefone, c.telefone2, c.celular, c.celular2, c.email, c.email2 " 
		 . "\n FROM cadastro as c"
		 . "\n WHERE c.inativo = 0 AND (c.nome LIKE '%$nome%' OR c.cpf_cnpj LIKE '%$nome%' OR c.celular LIKE '%$nome%' OR c.telefone LIKE '%$nome%' OR c.celular2 LIKE '%$nome%' OR c.telefone2 LIKE '%$nome%' OR c.endereco LIKE '%$nome%')" 
		 . "\n ORDER BY c.nome LIMIT $limite ";
		 $row = self::$db->fetch_all($sql);

		 return ($row) ? $row : 0;
	 }
	  
	  /**
	   * Cadastro::getEnderecoFaturamento()
	   *
       * @return
       */
	  public function getEnderecoFaturamento($id_cadastro)
      {
         $sql = "SELECT e.*" 
		  . "\n FROM cadastro_endereco as e"
		  . "\n WHERE e.inativo = 0 AND e.faturamento = 1 AND e.id_cadastro = $id_cadastro" ;
          $row = self::$db->first($sql);
          return ($row) ? $row : 0;
      }
	  
      /**
       * Cadastro::processarCadastro()
       * 
       * @return
       */
      public function processarCadastro()
      {
		  $cpf_cnpj = limparCPF_CNPJ(post('cpf_cnpj'));
		  
		  $Wcadastro = (Filter::$id) ? " AND c.id <> ".Filter::$id : "";
		  if (!empty($_POST['cpf_cnpj'])) {
		  $sql = "SELECT c.nome, c.senha_app" 
			. "\n FROM cadastro as c "
			. "\n WHERE c.cpf_cnpj = '$cpf_cnpj' $Wcadastro";
			$row = self::$db->first($sql);
            ($row) ? Filter::$msgs['cpf_cnpj'] = str_replace("[NOME]", $row->nome, lang('MSG_ERRO_CPF_CNPJ_CADASTRADO')) : null;
		  }
		  
		  if (!empty(post('email'))) {
			  $validarEmail = sanitize(post('email'));
			  $wEmail = (Filter::$id) ? " AND id!=".Filter::$id : "";
			  $sql_email = "SELECT nome FROM cadastro WHERE email = '$validarEmail' AND inativo = 0 $wEmail";
			  $row_email = self::$db->first($sql_email);
			  ($row_email) ? Filter::$msgs['email_cadastro'] = str_replace("[NOME]", $row_email->nome, lang('MSG_ERRO_EMAIL_CADASTRADO')) : null;
		  }
		  		  
		  if (empty($_POST['nome']))
              Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');			  
		  
		  if (empty($_POST['tipo']))
              Filter::$msgs['tipo'] = lang('MSG_ERRO_TIPO'); 
		  
		  if (empty($_POST['telefone']) and empty($_POST['celular']) and empty($_POST['telefone2']) and empty($_POST['celular2']))
              Filter::$msgs['telefone'] = lang('MSG_ERRO_TELEFONE'); 
		  
		  if (empty($_POST['endereco']))
              Filter::$msgs['endereco'] = lang('MSG_ERRO_ENDERECO');	
		  
		  if (empty($_POST['cidade']))
              Filter::$msgs['cidade'] = lang('MSG_ERRO_CIDADE');	

		  if (empty($_POST['cliente']) and empty($_POST['fornecedor']))
              Filter::$msgs['cliente'] = lang('MSG_ERRO_TIPO_CADASTRO');		  

          if (empty(Filter::$msgs)) {
			  
              $data = array(
					'id_empresa' => session('idempresa'),
					'nome' => sanitize(post('nome')), 
					'restricao' => intval(sanitize(post('restricao'))), 
					'razao_social' => sanitize(post('razao_social')), 
					'id_vendedor' => intval(sanitize(post('id_vendedor'))),
					'contato' => sanitize(post('contato')),
					'tipo' => sanitize(post('tipo')), 
					'email' => sanitize(post('email')),
					'email2' => sanitize(post('email2')),
					'cep' => sanitize(post('cep')),
					'endereco' => sanitize(post('endereco')),
					'numero' => sanitize(post('numero')), 
					'complemento' => sanitize(post('complemento')),  
					'bairro' => sanitize(post('bairro')),
					'cidade' => post('cidade'), 
					'estado' => sanitize(post('estado')), 
					'ie' => sanitize(post('ie')), 
					'im' => sanitize(post('im')), 
					'telefone' => sanitize(post('telefone')), 
					'telefone2' => sanitize(post('telefone2')), 
					'celular' => sanitize(post('celular')), 
					'celular2' => sanitize(post('celular2')), 
					'titular' => sanitize(post('titular')), 
					'banco' => sanitize(post('banco')),
					'agencia' => sanitize(post('agencia')),
					'conta' => sanitize(post('conta')),
					'observacao' => sanitize(post('observacao')),
					'id_origem' => intval(sanitize(post('id_origem'))),
					'crediario' => converteMoeda(post('valor_crediario')),
					'cliente' => post('cliente'),
					'fornecedor' => intval(post('fornecedor')),
					'oportunidade' => 0,
					'inativo' => 0,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );

			  if (!empty($_POST['senha_app'])) {
				$data['senha_app'] = sha1(strtolower($_POST['senha_app']));
			  }

			  if (!empty($cpf_cnpj))
			    $data['cpf_cnpj'] = $cpf_cnpj;

              if(Filter::$id) {
				self::$db->update(self::uTable, $data, "id=" . Filter::$id);
				$row_endereco = $this->getEnderecoFaturamento(Filter::$id);
				if ($row_endereco){
					$data_endereco = array(
					'id_cadastro' => Filter::$id,
					'cep' => sanitize(post('cep')),
					'endereco' => sanitize(post('endereco')),
					'numero' => sanitize(post('numero')), 
					'complemento' => sanitize(post('complemento')),  
					'bairro' => sanitize(post('bairro')),
					'cidade' => sanitize(post('cidade')), 
					'estado' => sanitize(post('estado')), 
					'referencia' => 'ENDERECO FATURAMENTO', 
					'faturamento' => '1',
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
					);
					self::$db->update("cadastro_endereco", $data_endereco, "id=" . $row_endereco->id);
				} else {
					$data_endereco = array(
					'id_cadastro' => Filter::$id,
					'cep' => sanitize(post('cep')),
					'endereco' => sanitize(post('endereco')),
					'numero' => sanitize(post('numero')), 
					'complemento' => sanitize(post('complemento')),  
					'bairro' => sanitize(post('bairro')),
					'cidade' => sanitize(post('cidade')), 
					'estado' => sanitize(post('estado')), 
					'referencia' => 'ENDERECO FATURAMENTO', 
					'faturamento' => '1',
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
					);
					self::$db->insert("cadastro_endereco", $data_endereco);
				}			
			  } else {
				$data['data_cadastro'] = "NOW()";
				Filter::$id = self::$db->insert(self::uTable, $data);
				$data_endereco = array(
					'id_cadastro' => Filter::$id,
					'cep' => sanitize(post('cep')),
					'endereco' => sanitize(post('endereco')),
					'numero' => sanitize(post('numero')), 
					'complemento' => sanitize(post('complemento')),  
					'bairro' => sanitize(post('bairro')),
					'cidade' => sanitize(post('cidade')), 
					'estado' => sanitize(post('estado')), 
					'referencia' => 'ENDERECO FATURAMENTO', 
					'faturamento' => '1',
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("cadastro_endereco", $data_endereco);
			  }
			  
              $message = (Filter::$id) ? lang('CADASTRO_AlTERADO_OK') : lang('CADASTRO_ADICIONADO_OK');

              if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=cadastro&acao=editar&id=".Filter::$id);  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
      /**
       * Cadastro::processarEndereco()
       * 
       * @return
       */
      public function processarEndereco()
      { 
		  if (empty($_POST['cep']))
              Filter::$msgs['cep'] = lang('MSG_ERRO_CEP');	
		  
		  if (empty($_POST['endereco']))
              Filter::$msgs['endereco'] = lang('MSG_ERRO_ENDERECO');	
		  
		  if (empty($_POST['cidade']))
              Filter::$msgs['cidade'] = lang('MSG_ERRO_CIDADE');		  

          if (empty(Filter::$msgs)) {			  
             
				$data_endereco = array(
					'id_cadastro' => sanitize(post('id_cadastro')),
					'cep' => sanitize(post('cep')),
					'endereco' => sanitize(post('endereco')),
					'numero' => sanitize(post('numero')), 
					'complemento' => sanitize(post('complemento')),  
					'bairro' => sanitize(post('bairro')),
					'cidade' => sanitize(post('cidade')), 
					'estado' => sanitize(post('estado')), 
					'referencia' => sanitize(post('referencia')), 
					'inativo' => '0',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("cadastro_endereco", $data_endereco);
			  
              $message = (Filter::$id) ? lang('CADASTRO_AlTERADO_OK') : lang('CADASTRO_ADICIONADO_OK');

              if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=cadastro&acao=editar&id=".post('id_cadastro'));  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
      /**
       * Cadastro::processarCNPJReceita()
       * 
       * @return
       */
      public function processarCNPJReceita()
      {
		  if (empty($_POST['cnpj']))
              Filter::$msgs['cnpj'] = lang('MSG_ERRO_CPF_CNPJ');		  

          if (empty(Filter::$msgs)) {
			$cnpj = limparCPF_CNPJ(post('cnpj'));
			$id_cadastro = checkRegistro('cpf_cnpj', 'cadastro', $cnpj);
			if($id_cadastro) {
				redirecionar("index.php?do=cadastro&acao=editar&id=".$id_cadastro);
			} else {
				$captcha_cnpj = post('captcha_cnpj');
				$getHtmlCNPJ = getHtmlCNPJ($cnpj, $captcha_cnpj);
				$campos = parseHtmlCNPJ($getHtmlCNPJ);
				$retorno = $campos['status'];
				if(strlen($campos[0]) > 0) {
					$data = array(
						'id_empresa' => session('idempresa'),
						'nome' => sanitize($campos[3]), 
						'razao_social' => sanitize($campos[2]), 
						'tipo' => 1, 
						'cpf_cnpj' => $cnpj,  
						'cep' => sanitize(post('cep')),
						'cep' => sanitize($campos[10]), 
						'endereco' => sanitize($campos[7]), 
						'numero' => sanitize($campos[8]), 
						'complemento' => sanitize($campos[9]), 
						'bairro' => sanitize($campos[11]), 
						'cidade' => sanitize($campos[12]), 
						'estado' => sanitize($campos[13]), 
						'telefone' => sanitize($campos[15]), 
						'email' => sanitize($campos[14]), 
						'observacao' => 'ADICIONADO PELO SITE DA RECEITA FEDERAL',
						'cliente' => 1,
						'inativo' => '0',
						'data_cadastro' => "NOW()",
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					$id_cadastro = self::$db->insert(self::uTable, $data);
					if (self::$db->affected()) {
						Filter::msgOk(lang('CADASTRO_ADICIONADO_OK'), "index.php?do=cadastro&acao=editar&id=".$id_cadastro);  
					} else
						Filter::msgAlert(lang('NAOPROCESSADO'));
				} else {
					Filter::msgInfo($retorno); 
				}
			}
			
		} else
              print Filter::msgStatus();
      }
	  
      /**
       * Cadastro::processarContato()
       * 
       * @return
       */
      public function processarContato()
      {
		  $cpf_cnpj = limparCPF_CNPJ(post('cpf_cnpj'));
		  
		  if (empty($_POST['cpf_cnpj'])) {
			  $cpf_cnpj = "NULL";
		  } else {
			  $cpf_cnpj = limparCPF_CNPJ(post('cpf_cnpj'));
		  }		  	  
		  
		  if (empty($_POST['nome']))
              Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');
		  
		  if (empty($_POST['telefone']))
              Filter::$msgs['telefone'] = lang('MSG_ERRO_TELEFONE'); 	  

          if (empty(Filter::$msgs)) {
			  
              $data = array(
					'id_empresa' => session('idempresa'),
					'nome' => sanitize(post('nome')), 
					'contato' => sanitize(post('contato')),
					'cpf_cnpj' => $cpf_cnpj,   
					'telefone' => sanitize(post('telefone')), 
					'celular' => sanitize(post('celular')), 
					'observacao' => sanitize(post('observacao')),
					'id_status' => sanitize(post('id_status')),
					'id_categoria' => sanitize(post('id_categoria')),
					'oportunidade' => '1',
					'inativo' => '0',
					'data_cadastro' => "NOW()",
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->insert(self::uTable, $data);
			  
              $message = lang('CADASTRO_ADICIONADO_OK');

              if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=cadastro&acao=relacionamento");  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

	  /**
       * Cadastro::processarDefinirEntregador()
       * 
       * @return
       */
      public function processarDefinirEntregador()
      { 
		  if (empty($_POST['id_entregador']))
              Filter::$msgs['id_entregador'] = lang('MSG_ERRO_ENTREGADOR');	
		  
		  if (empty($_POST['id_venda']))
              Filter::$msgs['id_venda'] = lang('MSG_ERRO_VENDA');	
		 
          if (empty(Filter::$msgs)) {
				$id_venda = sanitize(post('id_venda'));
				$id_entregador = sanitize(post('id_entregador'));

				$data_entregador = array(
					'id_entregador' => $id_entregador,
					'status_entrega' => 2,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update("vendas", $data_entregador, "id=" . post('id_venda'));
			  
              if (self::$db->affected()) {
				Filter::msgOk(lang('SELECIONE_ENTREGADOR_OK'), "index.php?do=vendas&acao=vendaspedidosentrega");
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * Cadastro::getBuscarCadastro()
       * 
       * @return
       */
      public function getBuscarCadastro()
      {
		  $row = false;
		  $nome = post('nome');
		  $cpf = limparCPF_CNPJ(post('cpf'));	
		  $telefone = post('telefone');
		  if($nome) {
			$cli = explode(" ", $nome);
			$wherecli = "";
			$count = count($cli);
			for ($i = 0; $i < $count; $i++) {
				$wherecli .= " AND (c.nome LIKE '%".$cli[$i]."%' OR c.razao_social LIKE '%".$cli[$i]."%' OR c.contato LIKE '%".$cli[$i]."%' OR c.bairro LIKE '%".$cli[$i]."%' OR c.cidade LIKE '%".$cli[$i]."%')";
			}
		  }
		  
		  if($cpf) {
			$sql = "SELECT c.id, c.nome, c.razao_social, c.cpf_cnpj, c.celular, c.email, c.telefone, c.bairro, c.cidade, c.usuario, c.data_cadastro, c.inativo " 
		  . "\n FROM cadastro as c"
		  . "\n WHERE cpf_cnpj LIKE '%".$cpf."%' " 
		  . "\n ORDER BY c.inativo, c.nome, c.cidade, c.cpf_cnpj ";
			$row = self::$db->fetch_all($sql);
		  } elseif($nome) {
			$sql = "SELECT c.id, c.nome, c.razao_social, c.cpf_cnpj, c.celular, c.email, c.telefone, c.bairro, c.cidade, c.usuario, c.data_cadastro, c.inativo " 
		  . "\n FROM cadastro as c"
		  . "\n WHERE c.inativo < 2 $wherecli " 
		  . "\n ORDER BY c.inativo, c.nome, c.cidade, c.cpf_cnpj ";
			$row = self::$db->fetch_all($sql);
		  } elseif($telefone){
			$sql = "SELECT c.id, c.nome, c.razao_social, c.cpf_cnpj, c.celular, c.email, c.telefone, c.bairro, c.cidade, c.usuario, c.data_cadastro, c.inativo " 
		  . "\n FROM cadastro as c"
		  . "\n WHERE c.inativo < 2 AND ( c.telefone LIKE '%".$telefone."%' OR c.celular LIKE '%".$telefone."%' OR c.telefone2 LIKE '%".$telefone."%' OR c.celular2 LIKE '%".$telefone."%' ) " 
		  . "\n ORDER BY c.inativo, c.nome, c.cidade, c.cpf_cnpj ";
			$row = self::$db->fetch_all($sql);  
		  }

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getBuscarCadastroCliente()
       * 
       * @return
       */
      public function getBuscarCadastroCliente()
      {
		  $row = false;
		  $nome = post('nome');
		  $cpf = limparCPF_CNPJ(post('cpf'));	
		  $telefone = post('telefone');
		  if($nome) {
			$cli = explode(" ", $nome);
			$wherecli = "";
			$count = count($cli);
			for ($i = 0; $i < $count; $i++) {
				$wherecli .= " AND (c.nome LIKE '%".$cli[$i]."%' OR c.razao_social LIKE '%".$cli[$i]."%')";
			}
		  }
		  
		  if($cpf) {
			$sql = "SELECT c.id, c.nome, c.razao_social, c.cpf_cnpj, c.celular, c.email, c.telefone, c.bairro, c.cidade, c.usuario, c.data_cadastro, c.inativo " 
		  . "\n FROM cadastro as c"
		  . "\n WHERE cliente = 1 AND cpf_cnpj LIKE '%".$cpf."%' " 
		  . "\n ORDER BY c.inativo, c.nome, c.cidade, c.cpf_cnpj ";
			$row = self::$db->fetch_all($sql);
		  } elseif($nome) {
			$sql = "SELECT c.id, c.nome, c.razao_social, c.cpf_cnpj, c.celular, c.email, c.telefone, c.bairro, c.cidade, c.usuario, c.data_cadastro, c.inativo " 
		  . "\n FROM cadastro as c"
		  . "\n WHERE c.cliente = 1 AND c.inativo < 2 $wherecli " 
		  . "\n ORDER BY c.inativo, c.nome, c.cidade, c.cpf_cnpj ";
			$row = self::$db->fetch_all($sql);
		  } elseif($telefone){
			$sql = "SELECT c.id, c.nome, c.razao_social, c.cpf_cnpj, c.celular, c.email, c.telefone, c.bairro, c.cidade, c.usuario, c.data_cadastro, c.inativo " 
		  . "\n FROM cadastro as c"
		  . "\n WHERE c.cliente = 1 AND c.inativo < 2 AND ( c.telefone LIKE '%".$telefone."%' OR c.celular LIKE '%".$telefone."%' OR c.telefone2 LIKE '%".$telefone."%' OR c.celular2 LIKE '%".$telefone."%' ) " 
		  . "\n ORDER BY c.inativo, c.nome, c.cidade, c.cpf_cnpj ";
			$row = self::$db->fetch_all($sql);  
		  }

          return ($row) ? $row : 0;
      }
	  
      /**
       * Cadastro::processarCadastroRetorno()
       * 
       * @return
       */
      public function processarCadastroRetorno()
      {
		  if (empty($_POST['id_status']))
              Filter::$msgs['id_status'] = lang('MSG_ERRO_STATUS');	
		  
		   if (!empty($_POST['data_retorno'])) {
			   $hoje = date('d/m/Y');
			   $data_retorno = post('data_retorno');
			   $dias = contarDias($hoje, $data_retorno);
			   if($dias < 0 or $dias > 180) {
				 Filter::$msgs['data_retorno'] = lang('MSG_ERRO_DATA_RETORNO'); 
			   }			  
		   }

          if (empty(Filter::$msgs)) {	
		  
              $data_ativo = array(
					'ativo' => "0"
			  );
              self::$db->update("cadastro_retorno", $data_ativo, "id_cadastro=" . post('id_cadastro'));
			  
              $data = array(
					'id_empresa' => session('idempresa'),
					'id_cadastro' => sanitize(post('id_cadastro')), 
					'id_status' => sanitize(post('id_status')), 
					'id_categoria' => sanitize(post('id_categoria')), 
					'observacao' => sanitize(post('observacao')),
					'data_retorno' => dataMySQL(post('data_retorno')),
					'interesse' => sanitize(post('interesse')),
					'ativo' => "1",
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
              self::$db->insert("cadastro_retorno", $data);
			  
              $data_cadastro = array(
					'observacao' => sanitize(post('observacao')),
					'id_status' => sanitize(post('id_status')),
					'id_categoria' => sanitize(post('id_categoria')), 
					'interesse' => sanitize(post('interesse')),
					'data_retorno' => dataMySQL(post('data_retorno')),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );

              self::$db->update("cadastro", $data_cadastro, "id=" . post('id_cadastro'));
			  
              $message = lang('CONTATO_RETORNO_ADICIONADO_OK');
			  if (empty($_POST['relacionamento'])) {
				  $redirecionar = "index.php?do=cadastro&acao=contato&id=".post('id_cadastro');
			  } else {
				  $redirecionar = "index.php?do=cadastro&acao=relacionamento";
			  }	 
              if (self::$db->affected()) {		
					Filter::msgOk($message, $redirecionar);
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

	  /**
       * Cadastro::getEntregadorVendasApp()
       * 
       * @return
       */
      public function getEntregadorVendasApp($id_entregador)
      {
          $sql = "SELECT  v.id, v.id_cadastro, v.data_venda, v.prazo_entrega, v.observacao, v.entrega, v.id_entregador, v.valor_pago, c.nome, c.cep, c.endereco, c.numero, c.complemento, c.bairro, c.cidade, c.telefone, c.celular" 
		  . "\n FROM vendas AS v"
		  . "\n LEFT JOIN cadastro AS c ON c.id = v.id_cadastro "
		  . "\n WHERE v.inativo = 0 AND v.entrega = 1 AND (v.status_entrega = 1 OR v.status_entrega = 2) ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }

		/**
         * Cadastro::getSaldoCrediario()
         * 
         * @return
         */
      public function getSaldoCrediario($id_cadastro = 0)
      {	  
		  $sql = "SELECT SUM(c.valor) AS valor, SUM(c.valor_pago) AS valor_pago FROM cadastro_crediario AS c "
		  . "\n WHERE c.inativo = 0 AND c.id_cadastro = $id_cadastro";	
		  $row = self::$db->first($sql);		  
		  $crediario = ($row) ? $row->valor-$row->valor_pago : 0;
          return $crediario;
      }

	/**
	 * Cliente::getValoresCrediario()
	 * 
	 * @return
	 */
	public function getValoresCrediario($id_cliente = 0)
	{
		$sql = "SELECT SUM(c.valor) AS valor, SUM(c.valor_pago) AS valor_pago, SUM(c.juros) as juros, SUM(c.multa) as multa "
		. "\n FROM cadastro_crediario as c "
		. "\n WHERE c.inativo = 0 AND c.id_cadastro = $id_cliente ";
		$row = self::$db->first($sql);
		return ($row) ? $row : 0;
	}

    /**
     * Cadastro::processarPagamentoCrediario()
     * 
     * @return
     */
    public function processarPagamentoCrediario()
    {
		$id_cliente = post('id_cliente');
		$valor_pagamento_crediario = 0;
		$total_pagar = (empty($_POST['total_pagar'])) ? 0 : converteMoeda(post('total_pagar'));
		
		if (empty($_POST['valor_pagamento_crediario'])) {
            Filter::$msgs['valor_pagamento_crediario'] = lang('MSG_ERRO_VALOR');
		} else {
			$valor_pagamento_crediario = converteMoeda(post('valor_pagamento_crediario'));
		}

		if (empty($_POST['tipopagamento'])) {
            Filter::$msgs['tipopagamento'] = lang('MSG_ERRO_TIPO_PAGAMENTO');
		} else {
			$tipo = post('tipopagamento');
			$id_tipo_categoria = getValue("id_categoria","tipo_pagamento","id=" . $tipo);
		}

		$data_pagamento = (empty($_POST['data_pagamento'])) ? "NOW()" : dataMySQL(post('data_pagamento'));

		if(($valor_pagamento_crediario > $total_pagar) && ($id_tipo_categoria <> 1))
            Filter::$msgs['total_pagar'] = lang('MSG_ERRO_CREDIARIO_PGTO_NAO_ESPECIE');
		
        if (empty(Filter::$msgs)) {
			$nomecliente = getValue("nome", "cadastro", "id=" . $id_cliente);
			$data_update = array(
				'data_crediario' => $data_pagamento
			);
			self::$db->update("cadastro", $data_update, "id=".$id_cliente);

			$crediarios_cliente = $this->getClienteCrediario($id_cliente, 0);

			if ($crediarios_cliente) {
				$valor_restante_pagamento = $valor_pagamento_crediario;
				foreach($crediarios_cliente as $crediario) {
					$valor_devido_crediario = $crediario->valor-$crediario->valor_pago;
					if ($valor_restante_pagamento>0 && $valor_devido_crediario>0) {
						//Se o valor pago é maior que o valor do crediário da vez:
						if ($valor_restante_pagamento>=$valor_devido_crediario) {

							$valor_restante_pagamento -= $valor_devido_crediario;
							$valor_pago_crediario = $valor_devido_crediario + $crediario->valor_pago;
							$valor_pagando = $valor_devido_crediario;

						} 
						//Se o valor pago for menor que o valor do crediário da vez:
						else {

							$valor_pago_crediario = $valor_restante_pagamento + $crediario->valor_pago;
							$valor_pagando = $valor_restante_pagamento;
							$valor_restante_pagamento = 0;

						}

						$data_crediario = array(
							'valor_pago' => $valor_pago_crediario,
							'pago' => ($valor_pago_crediario>=$crediario->valor) ? 1 : 2,
							'data_pagamento' => $data_pagamento,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->update("cadastro_crediario", $data_crediario, "id=" . $crediario->id);

						$data_crediario_pagamento = array(
							'id_cadastro' => $id_cliente,
							'id_cadastro_crediario' => $crediario->id,
							'id_caixa' => $crediario->id_caixa,
							'valor_pago' => $valor_pagando,
							'tipo_pagamento' => $tipo,
							'data_pagamento' => $data_pagamento,
							'inativo' => 0,
							'usuario' => session('nomeusuario'),
							'data' => "NOW()" 
						);
						self::$db->insert("cadastro_crediario_pagamentos", $data_crediario_pagamento);

					}
				}

				$id_banco = getValue("id_banco","tipo_pagamento","id=" . $tipo);

				$data_receita = array(
					'id_empresa' => session('idempresa'),
					'id_cadastro' => $id_cliente, 
					'id_pagamento' => 0, 
					'id_conta' => 18, 
					'id_banco' => $id_banco, 
					'tipo' => $tipo, 
					'pago' => 1, 
					'data_pagamento' => $data_pagamento,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);

				$descricao_tipo = getValue("categoria","tipo_pagamento_categoria","id=" . $id_tipo_categoria);

				if($id_tipo_categoria == '1') {
					
					$data_receita['descricao'] = "PAGAMENTO CREDIARIO/FICHA - ".$descricao_tipo." - ".$nomecliente;
					$data_receita['valor'] = $valor_pagamento_crediario;
					$data_receita['valor_pago'] = $valor_pagamento_crediario;
					$data_receita['data_recebido'] = $data_pagamento;
					self::$db->insert("receita", $data_receita);
	
				} elseif($id_tipo_categoria == '2' or $id_tipo_categoria == '4') {

					$data_receita['descricao'] = "PAGAMENTO CREDIARIO/FICHA - ".$descricao_tipo." - ".$nomecliente;
					$data_receita['valor'] = $valor_pagamento_crediario;
					$data_receita['valor_pago'] = $valor_pagamento_crediario;
					$data_receita['data_recebido'] = $data_pagamento;
					self::$db->insert("receita", $data_receita);
					
				} elseif($id_tipo_categoria == '3' or $id_tipo_categoria == '6') {

					$data_receita['descricao'] = "PAGAMENTO CREDIARIO/FICHA - ".$descricao_tipo." - ".$nomecliente;
					$data_receita['valor'] = $valor_pagamento_crediario;
					$data_receita['valor_pago'] = $valor_pagamento_crediario;
					$data_receita['data_recebido'] = $data_pagamento;
					self::$db->insert("receita", $data_receita);
					
				} else {

					$data_temp = (empty($_POST['data_pagamento'])) ? date('d/m/Y') : post('data_pagamento');
					$data_parcela = explode('/', $data_temp);
					$row_cartoes = Core::getRowById("tipo_pagamento", $tipo);				
					$dias = $row_cartoes->dias;
					$taxa = $row_cartoes->taxa;
					$id_banco = $row_cartoes->id_banco;
					$data_receita['descricao'] = "PAGAMENTO CREDIARIO/FICHA - ".$row_cartoes->tipo." - ".$nomecliente;
					$data_receita['id_banco'] = $id_banco;			
					$valor_taxa = $valor_pagamento_crediario*$taxa/100;
					$valor_cartao = $valor_pagamento_crediario - $valor_taxa;
					$newData = novadata($data_parcela[1], $data_parcela[0] + $dias, $data_parcela[2]);
					$data_receita['valor'] = $valor_pagamento_crediario;
					$data_receita['valor_pago'] = $valor_cartao;
					$data_receita['data_recebido'] = $newData;
					self::$db->insert("receita", $data_receita);

				}

				$message = lang('PAGAMENTO_REALIZADO_OK');
				$redirecionar = "index.php?do=cadastro&acao=crediario&opcao=0&id=".$id_cliente;
				if (self::$db->affected()) {
					Filter::msgOk($message, $redirecionar);
				} else Filter::msgAlert(lang('NAOPROCESSADO'));

			}

        } else
            print Filter::msgStatus();

      }

	/**
	 * Cadastro::clienteDevendoFicha(id_cliente,tolerancia_crediario)
	 */
	public function clienteDevendoFicha($id_cliente,$tolerancia_crediario) 
	{
		$sql = "SELECT count(id) as quantidade "
		  . "\n FROM cadastro_crediario "
		  . "\n WHERE inativo=0 AND id_cadastro=$id_cliente AND pago<>1 "
		  . "\n AND data_operacao < DATE_SUB(CURRENT_DATE(), INTERVAL $tolerancia_crediario DAY)";
		$row = self::$db->first($sql);
		return ($row && $row->quantidade>0) ? true : false;
	}

	
	/**
	 * Cadastro::clienteDevendoCrediario(id_cliente,tolerancia_crediario)
	 */
	public function clienteDevendoCrediario($id_cliente,$tolerancia_crediario) 
	{
		return false;
	}

	/**
	 * Cadastro::atualizar_crediario()
	 * 
	 * @return
	 */
	public function atualizar_crediario()
	{
		$sql_crediario = "SELECT * FROM cadastro_crediario WHERE inativo = 0 AND operacao = 1 AND pago <> 1 ";
		$row_crediario = self::$db->fetch_all($sql_crediario);
		$total_clientes = 0;
		$total_atualizados = 0;
		if ($row_crediario) {
			$id_empresa = session('idempresa');
			$row_empresa = Core::getRowById("empresa", $id_empresa);
			foreach($row_crediario as $crow) {
				$total_clientes++;
				$dias = contarDias(exibedata($crow->data_operacao));
				if ($dias > $row_empresa->tolerancia_crediario) {
					$valor_crediario = $crow->valor - $crow->valor_pago;
					$valor_juros_dia = round(($valor_crediario/100),2)*round(($row_empresa->juros_crediario/30),2);
					$valor_multa = round(($valor_crediario/100),2)* $row_empresa->multa_crediario;
					$novo_valor = $crow->valor_venda + round(($valor_juros_dia * $dias),2);
					$novo_valor += $valor_multa;
					
					$data_crediario = array(
						'multa' => round($valor_multa,3),
						'juros' => round(($valor_juros_dia*$dias),2),
						'valor' => $novo_valor
					);
					self::$db->update("cadastro_crediario", $data_crediario, "id='" . $crow->id . "'");
					
					if (self::$db->affected()) {
						$total_atualizados++;
					}
				}
			}
		}
		
		$message = "Clientes encontrados: [$total_clientes] | Clientes em atraso: [$total_atualizados]";
		Filter::msgOk($message, "index.php?do=vendas&acao=vendascrediario");
	}

      /**
       * Cadastro::getCadastroRetorno()
       * 
       * @return
       */
      public function getCadastroRetorno($id_cadastro)
      {
          $sql = "SELECT c.*, s.status, c.data_retorno < CURDATE() as atrasado, c.data_retorno > CURDATE() as agendado, c.data_retorno = CURDATE() as hoje " 
		  . "\n FROM cadastro_retorno AS c"
		  . "\n LEFT JOIN cadastro_status AS s ON s.id = c.id_status "
		  . "\n WHERE c.id_cadastro = ".$id_cadastro;
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getCadastroAberto()
       * 
       * @return
       */
      public function getCadastroAberto($id_origem, $id_cidade, $id_bairro)
      {
		  $wOrigem = ($id_origem) ? "AND id_origem = $id_origem" : "";
		  $wCidade = ($id_cidade) ? "AND cidade = '$id_cidade'" : "";
		  $wBairro = ($id_bairro) ? "AND bairro = '$id_bairro'" : "";
          $sql = "SELECT c.id " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 AND c.id_status = 98 AND c.oportunidade = 1 AND c.usuario = '".session('nomeusuario')."' "
		  . "\n $wOrigem "
		  . "\n $wCidade "
		  . "\n $wBairro "
		  . "\n ORDER BY c.id LIMIT 1";
          $row = self::$db->first($sql);
		  $id_cadastro = ($row) ? $row->id : 0;
		  if($id_cadastro == 0) {
				$sql = "SELECT c.id " 
				. "\n FROM cadastro AS c"
				. "\n WHERE c.inativo = 0 AND c.oportunidade = 1 AND c.id_status = 0 "
				. "\n $wOrigem "
				. "\n $wCidade "
				. "\n $wBairro "
				. "\n ORDER BY RAND() LIMIT 1";
				$row = self::$db->first($sql);
				$id_cadastro = ($row) ? $row->id : 0;
				if($id_cadastro) {
					$data = array(
						'id_status' => 98,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("cadastro", $data, "id=" . $id_cadastro);
				}
		  }
          return $id_cadastro;
      }
	  
	  /**
       * Cadastro::getCadastroSelecionado()
       * 
       * @return
       */
      public function getCadastroSelecionado($id_origem, $id_cidade, $id_bairro)
      {
		  $wOrigem = ($id_origem) ? "AND id_origem = $id_origem" : "";
		  $wCidade = ($id_cidade) ? "AND cidade = '$id_cidade'" : "";
		  $wBairro = ($id_bairro) ? "AND bairro = '$id_bairro'" : "";
          $sql = "SELECT c.id " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 AND c.id_status = 99 AND c.oportunidade = 1 AND c.usuario = '".session('nomeusuario')."' "
		  . "\n $wOrigem "
		  . "\n $wCidade "
		  . "\n $wBairro "
		  . "\n ORDER BY c.id LIMIT 1";
          $row = self::$db->first($sql);
		  $id_cadastro = ($row) ? $row->id : 0;
		  if($id_cadastro == 0) {
				$sql = "SELECT c.id " 
				. "\n FROM cadastro AS c"
				. "\n WHERE c.inativo = 0 AND c.oportunidade = 1 AND c.id_status = 0 "
				. "\n $wOrigem "
				. "\n $wCidade "
				. "\n $wBairro "
				. "\n ORDER BY RAND() LIMIT 1";
				$row = self::$db->first($sql);
				$id_cadastro = ($row) ? $row->id : 0;
				if($id_cadastro) {
					$data = array(
						'id_status' => 99,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("cadastro", $data, "id=" . $id_cadastro);
				}
		  }
          return $id_cadastro;
      }

      /**
       * Cadastro::getStatus()
       * 
       * @return
       */
      public function getStatus()
      {
		  $sql = "SELECT * FROM cadastro_status ORDER BY status ASC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getMeusCadastros()
       * 
       * @return
       */
      public function getMeusCadastros()
      {
         $sql = "SELECT c.id, c.nome, c.cpf_cnpj, c.telefone, c.celular, c.telefone2, c.celular2, c.cidade, c.bairro, c.observacao, c.interesse, c.data_retorno, o.origem, IF(s.status IS NULL, 'RESERVADO', s.status) AS status, a.categoria, c.data_retorno < CURDATE() as atrasado, c.data_retorno > CURDATE() as agendado, c.data_retorno = CURDATE() as hoje, DATE_FORMAT(c.data_retorno, '%Y%m%d') as controle " 
		  . "\n FROM cadastro AS c"
		  . "\n LEFT JOIN cadastro_status AS s ON s.id = c.id_status "
		  . "\n LEFT JOIN origem AS o ON o.id = c.id_origem "
		  . "\n LEFT JOIN categoria AS a ON a.id = c.id_categoria "
		  . "\n WHERE c.inativo = 0 AND c.id_status > 0 AND c.id_status < 98 AND (s.tipo is null or s.tipo = 0) AND c.usuario = '".session('nomeusuario')."' ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getCadastroCategorias()
       * 
       * @return
       */
      public function getCadastroCategorias($id_categoria, $id_cidade, $id_bairro)
      {
		  $wCategoria = ($id_categoria) ? "AND c.id_categoria = '$id_categoria'" : "";
		  $wCidade = ($id_cidade) ? "AND c.cidade = '$id_cidade'" : "";
		  $wBairro = ($id_bairro) ? "AND c.bairro = '$id_bairro'" : "";
		  
         $sql = "SELECT c.id, c.nome, c.cpf_cnpj, c.telefone, c.celular, c.telefone2, c.celular2, c.cidade, c.bairro, c.observacao, c.interesse, c.data_retorno, c.usuario, o.origem, IF(s.status IS NULL, '-', s.status) AS status, a.categoria, c.data_retorno < CURDATE() as atrasado, c.data_retorno > CURDATE() as agendado, c.data_retorno = CURDATE() as hoje, DATE_FORMAT(c.data_retorno, '%Y%m%d') as controle " 
		  . "\n FROM cadastro AS c"
		  . "\n LEFT JOIN cadastro_status AS s ON s.id = c.id_status "
		  . "\n LEFT JOIN origem AS o ON o.id = c.id_origem "
		  . "\n LEFT JOIN categoria AS a ON a.id = c.id_categoria "
		  . "\n WHERE c.inativo = 0 AND c.id_categoria > 0 AND (s.tipo is null or s.tipo <> 1) "
		  . "\n $wCategoria "
		  . "\n $wCidade "
		  . "\n $wBairro "
		  . "\n ORDER BY c.nome ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	  /**
       * Cadastro::getAniversariantes()
	   *
       * @return
       */
	  public function getAniversariantes($dataini = 0, $datafim = 0)
      {
         $sql = "SELECT c.id, c.nome, c.celular, c.cpf, c.email, c.telefone, c.bairro, c.cidade, c.usuario, c.data_cadastro, c.sexo, c.data_nasc " 
		  . "\n FROM cadastro as c"
		  . "\n WHERE inativo = 0 AND DATE_FORMAT(c.data_nasc, '%m%d') "
		  . "\n BETWEEN DATE_FORMAT(STR_TO_DATE('$dataini', '%d/%m'), '%m%d') "
		  . "\n AND DATE_FORMAT(STR_TO_DATE('$datafim', '%d/%m'), '%m%d') "
		  . "\n ORDER BY c.nome ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getTotalCadastroRetorno()
       * 
       * @return
       */
      public function getTotalCadastroRetorno($dataini = 0, $datafim = 0)
      {
          $sql = "SELECT c.id_status, s.status, COUNT(1) AS quantidade " 
		  . "\n FROM cadastro_retorno AS c"
		  . "\n LEFT JOIN cadastro_status AS s ON s.id = c.id_status "
		  . "\n WHERE data BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n GROUP BY c.id_status "
		  . "\n ORDER BY s.status ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getConsultorRetorno()
       * 
       * @return
       */
      public function getConsultorRetorno($dataini = 0, $datafim = 0)
      {
          $sql = "SELECT c.usuario AS consultor, c.id_status, s.status, COUNT(1) AS quantidade " 
		  . "\n FROM cadastro_retorno AS c"
		  . "\n LEFT JOIN cadastro_status AS s ON s.id = c.id_status "
		  . "\n WHERE data BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n GROUP BY c.usuario, c.id_status "
		  . "\n ORDER BY c.usuario, s.status ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getConsultorCadastro()
       * 
       * @return
       */
      public function getConsultorCadastro($dataini = 0, $datafim = 0)
      {
          $sql = "SELECT c.usuario AS consultor, COUNT(1) AS quantidade " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 ANDdata_cadastro BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n GROUP BY c.usuario "
		  . "\n ORDER BY c.usuario ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getCadastrosConsultor()
       * 
       * @return
       */
      public function getCadastrosConsultor()
      {
         $sql = "SELECT c.usuario AS consultor, COUNT(1) AS quantidade " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 AND c.id_status > 0 AND c.id_status < 20 "
		  . "\n GROUP BY c.usuario "
		  . "\n ORDER BY c.usuario ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getCadastrosAberto()
       * 
       * @return
       */
      public function getCadastrosAberto($id_origem = 0)
      {
         $sql = "SELECT COUNT(1) AS quantidade " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 AND c.id_status = 0  AND c.id_origem = $id_origem ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quantidade : 0;
      }

      /**
       * Cadastro::getCadastrosAberto()
       * 
       * @return
       */
      public function getCadastrosAtendimento($id_origem = 0)
      {
         $sql = "SELECT COUNT(1) AS quantidade " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 AND c.id_status > 0 AND c.id_status < 20 AND c.id_origem = $id_origem ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quantidade : 0;
      }

      /**
       * Cadastro::getCadastrosAberto()
       * 
       * @return
       */
      public function getCadastrosQualificado($id_origem = 0)
      {
         $sql = "SELECT COUNT(1) AS quantidade " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 AND c.id_status > 20 AND c.id_origem = $id_origem ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quantidade : 0;
      }

      /**
       * Cadastro::getCadastroAbertoOrigem()
       * 
       * @return
       */
      public function getCadastroAbertoOrigem($id_origem = 0)
      {
         $sql = "SELECT COUNT(1) AS quantidade " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 AND c.id_status = 0  AND c.id_origem = $id_origem ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quantidade : 0;
      }

      /**
       * Cadastro::getCadastroSemOrigem()
       * 
       * @return
       */
      public function getCadastroSemOrigem()
      {
         $sql = "SELECT COUNT(1) AS quantidade " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 AND c.id_origem = 0 ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quantidade : 0;
      }

      /**
       * Cadastro::getTodosConsultores()
       * 
       * @return
       */
      public function getTodosConsultores()
      {
          $sql = "SELECT c.usuario AS consultor " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 AND c.id_status > 0 AND c.id_status < 20 "
		  . "\n GROUP BY c.usuario "
		  . "\n ORDER BY c.usuario ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getTodosConsultoresRetorno()
       * 
       * @return
       */
      public function getTodosConsultoresRetorno($mes_ano = false)
      {
		  $where = ($mes_ano) ? " WHERE DATE_FORMAT(c.data, '%m/%Y') = '$mes_ano' " : "";
          $sql = "SELECT c.usuario AS consultor " 
		  . "\n FROM cadastro_retorno AS c"
		  . "\n  $where "
		  . "\n GROUP BY c.usuario "
		  . "\n ORDER BY c.usuario ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getListaMesConsultor()
       * 
       * @return
       */
      public function getListaMesConsultor()
      {		  
          $sql = "SELECT mes_ano FROM " 
		  . "\n (SELECT DATE_FORMAT(c.data, '%m/%Y') as mes_ano "
		  . "\n FROM cadastro_retorno AS c "
		  . "\n UNION "
		  . "\n SELECT DATE_FORMAT(c.data_retorno, '%m/%Y') as mes_ano "
		  . "\n FROM cadastro_retorno AS c "
		  . "\n WHERE DATE_FORMAT(c.data_retorno, '%m/%Y') <> '00/0000') AS t "
		  . "\n GROUP BY mes_ano "
		  . "\n ORDER BY mes_ano DESC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * CRM::getContatoConsultor()
       * 
       * @return
       */
      public function getContatoConsultor($consultor = 0)
      {
         $sql = "SELECT c.id, c.nome, c.telefone, c.celular, c.observacao, c.data_retorno, o.origem, IF(s.status IS NULL, 'RESERVADO', s.status) AS status, a.categoria, c.usuario, c.data_retorno < CURDATE() as atrasado, c.data_retorno > CURDATE() as agendado, c.data_retorno = CURDATE() as hoje, DATE_FORMAT(c.data_retorno, '%Y%m%d') as controle " 
		  . "\n FROM cadastro AS c"
		  . "\n LEFT JOIN cadastro_status AS s ON s.id = c.id_status "
		  . "\n LEFT JOIN origem AS o ON o.id = c.id_origem "
		  . "\n LEFT JOIN categoria AS a ON a.id = c.id_categoria "
		  . "\n WHERE c.inativo = 0 AND (s.tipo is null or s.tipo = 0) AND c.usuario = '$consultor' "
		  . "\n ORDER BY c.data_retorno DESC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * CRM::getContatoPendentes()
       * 
       * @return
       */
      public function getContatoPendentes($consultor = false)
      {
		 $where = ($consultor) ? " AND c.usuario = '$consultor' " : "";
         $sql = "SELECT c.id, c.nome, c.telefone, c.celular, c.observacao, c.data_retorno, o.origem, IF(s.status IS NULL, 'RESERVADO', s.status) AS status, a.categoria, c.usuario, c.data_retorno < CURDATE() as atrasado, c.data_retorno > CURDATE() as agendado, c.data_retorno = CURDATE() as hoje, DATE_FORMAT(c.data_retorno, '%Y%m%d') as controle " 
		  . "\n FROM cadastro AS c"
		  . "\n LEFT JOIN cadastro_status AS s ON s.id = c.id_status "
		  . "\n LEFT JOIN origem AS o ON o.id = c.id_origem "
		  . "\n LEFT JOIN categoria AS a ON a.id = c.id_categoria "
		  . "\n WHERE c.inativo = 0 AND s.tipo = 2 $where "
		  . "\n ORDER BY c.data_retorno DESC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * CRM::removerContatos()
       * 
       * @return
       */
      public function removerContatos()
      {		  
		  $consultor = post('consultor');
		  $contatos = post('contatos');
		  $total = 0;
          $quant = count($contatos);
		  
		  if ($quant == 0)
              Filter::$msgs['quantidade'] = "Quantidade de contatos igual a 0.";
		
		if (empty(Filter::$msgs)) {
			  
		  $data = array(
				'oportunidade' => 1,
				'id_status' => 0,
				'data' => "NOW()"
			);
		  
		  for ($i=0; $i<$quant; $i++) 
		  {
			self::$db->update("cadastro", $data, "(id_status < 20 OR id_status = 99) AND usuario = '$consultor' AND id=" . $contatos[$i]);
		  }
		  if (self::$db->affected()) {	                 
			Filter::msgOk(lang('CONTATO_REMOVIDOS_OK'), "index.php?do=painel&acao=contatosconsultor&consultor=".$consultor);
          } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
              print Filter::msgStatus();
      }

      /**
       * CRM::getOportunidades()
       * 
       * @return
       */
      public function getOportunidades()
      {
         
		$sql = "SELECT id, nome, razao_social, contato, cpf_cnpj, telefone, celular, email, endereco, numero, complemento, bairro, cidade, cliente, fornecedor " 
		. "\n FROM cadastro AS c"
		. "\n WHERE c.inativo = 0 AND c.oportunidade = 1 AND c.id_status = 0 "
		. "\n ORDER BY c.nome ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * CRM::getUltimaOrigem()
       * 
       * @return
       */
      public function getUltimaOrigem()
      {
         $sql = "SELECT c.id_origem " 
		  . "\n FROM cadastro AS c"
		  . "\n WHERE c.inativo = 0 AND c.usuario = '".session('nomeusuario')."' "
		  . "\n ORDER BY c.data DESC "
		  . "\n LIMIT 1 ";
          $row = self::$db->first($sql);

          return ($row) ? $row->id_origem : 0;
      }
	  
	  /**
       * CRM::getOrigem()
	   *
       * @return
       */
	  public function getOrigem()
      {
          $sql = "SELECT id, origem, inativo  " 
		  . "\n FROM origem "
		  . "\n WHERE inativo = 0 "
		  . "\n ORDER BY origem ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getClienteOrigem()
	   *
       * @return
       */
	  public function getClienteOrigem($id_origem = 0, $mes_ano = false)
      {
		  $where = ($id_origem) ? " AND c.id_origem = $id_origem " : "";
          $sql = "SELECT c.*, o.origem " 
		  . "\n FROM cadastro as c "
		  . "\n LEFT JOIN origem AS o ON o.id = c.id_origem "
		  . "\n WHERE c.inativo = 0 "
		  . "\n $where "
		  . "\n ORDER BY c.nome ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadstro::getClienteCrediario()
	   * 
	   * PAGO -> 0 nenhum pagamento
	   *         1 totalmente pago
	   *         2 pagamento parcial
	   *
       * @return
       */
	  public function getClienteCrediario($id_cliente = 0, $opcao = false)
      {
          $where = ($opcao == "") ? "" : (($opcao == 0) ? " AND (c.pago=0 OR c.pago=2) " : " AND c.pago=1");
		  $sql = "SELECT c.* " 
		  . "\n FROM cadastro_crediario as c "
		  . "\n WHERE c.inativo = 0 AND c.id_cadastro = $id_cliente "
		  . "\n $where "
		  . "\n ORDER BY c.id ASC ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getPagarCrediario()
	   *
       * @return
       */
	  public function getPagarCrediario($id_cadastro = 0)
      {		  
		  $sql = "SELECT SUM(c.valor) AS valor, SUM(c.valor_pago) AS valor_pago " 
		  . "\n FROM cadastro_crediario as c "
		  . "\n WHERE c.inativo = 0 AND c.pago <> 1 AND c.id_cadastro = $id_cadastro ";
          $row = self::$db->first($sql);
          return ($row) ? $row->valor-$row->valor_pago : 0;
      }
	  
	  /**
       * CRM::getOrigemOportunidade()
	   *
       * @return
       */
	  public function getOrigemOportunidade()
      {
          $sql = "SELECT o.id, o.origem, c.id_origem " 
		  . "\n FROM cadastro as c "
		  . "\n left join origem as o ON o.id=c.id_origem"
		  . "\n WHERE c.inativo = 0 AND c.oportunidade = 1 AND c.id_status = 0 "
		  . "\n GROUP BY c.id_origem "
		  . "\n ORDER BY o.origem ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * CRM::getCidade()
	   *
       * @return
       */
	  public function getCidade()
      {
          $sql = "SELECT distinct(cidade)  " 
		  . "\n FROM cadastro as c "
		  . "\n WHERE c.inativo = 0 AND c.oportunidade = 1 AND c.id_status = 0 "
		  . "\n ORDER BY cidade ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * CRM::getBairro()
	   *
       * @return
       */
	  public function getBairro()
      {
          $sql = "SELECT distinct(bairro)  " 
		  . "\n FROM cadastro as c "
		  . "\n WHERE c.inativo = 0 AND c.oportunidade = 1 AND c.id_status = 0 "
		  . "\n ORDER BY bairro ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * CRM::getTodasOrigem()
	   *
       * @return
       */
	  public function getTodasOrigem()
      {
          $sql = "SELECT id, origem, inativo  " 
		  . "\n FROM origem "
		  . "\n ORDER BY origem ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * CRM::processarOrigem()
       * 
       * @return
       */
      public function processarOrigem()
      {
		  if (empty($_POST['origem']))
              Filter::$msgs['origem'] = lang('MSG_ERRO_ORIGEM'); 

          if (empty(Filter::$msgs)) {

              $data = array(
					'origem' => sanitize(post('origem'))
			  );

              self::$db->insert("origem", $data);
			  
              $message = lang('ADICIONAR_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=cadastro&acao=origem");     
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
      /**
       * CRM::definirOrigem()
       * 
       * @return
       */
      public function definirOrigem()
      {

			$data = array(
				'id_origem' => sanitize(post('id_origem'))
			);
			self::$db->update("configuracao", $data, "id=1");
			$message = lang('EDITAR_OK');
			if (self::$db->affected()) {
				Filter::msgOk($message, "index.php?do=cadastro&acao=origem");
			} else
				Filter::msgAlert(lang('NAOPROCESSADO'));
      }
	  
	  /**
       * CRM::getTotalOrigem()
	   *
       * @return
       */
	  public function getTotalOrigem($id_origem = 0)
      {
          $sql = "SELECT COUNT(1) AS quant  " 
		  . "\n FROM cadastro "
		  . "\n WHERE inativo = 0 AND id_origem = $id_origem ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quant : 0;
      }

      /**
       * Cadastro::processarVendaProduto()
       * 
       * @return
       */
      public function processarVendaProduto()
      {
		  if (empty($_POST['id_produto']))
              Filter::$msgs['id_produto'] = lang('MSG_ERRO_PRODUTO');
		  
		  if (empty($_POST['id_tabela'])) {
              Filter::$msgs['id_tabela'] = lang('MSG_ERRO_TABELA');
		  } else {
			$id_tabela = sanitize(post('id_tabela'));  
          	$quant_produto = (empty($_POST['quantidade'])) ? 1 : post('quantidade');
		  	$quantidade = str_replace (',', '.', str_replace ('.', '', $quant_produto));
			$quant_tabela = getValue("quantidade","tabela_precos","id=".$id_tabela);
			if ($quantidade<$quant_tabela){
				Filter::$msgs['quantidade_tabela'] = lang('MSG_ERRO_QTDMINIMA');
			}
		  }

		  
		  $id_venda = sanitize(post('id_venda'));
		  $id_cadastro = sanitize(post('id_cadastro'));
          $id_produto = sanitize(post('id_produto'));
          $id_tabela = sanitize(post('id_tabela'));  
          $quant_produto = (empty($_POST['quantidade'])) ? 1 : post('quantidade');
		  $quantidade = str_replace (',', '.', str_replace ('.', '', $quant_produto));
		  $kit = getValue("kit", "produto", "id=" . $id_produto);
		  $valida_estoque = getValue("valida_estoque", "produto", "id=" . $id_produto);

		$i=1;
		if($kit) {
			$nomekit = getValue("nome", "produto", "id=" . $id_produto);
			$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade, p.valida_estoque " 
			. "\n FROM produto_kit as k"
			. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
			. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
			. "\n ORDER BY p.nome ";
			$retorno_row = self::$db->fetch_all($sql);
			if($retorno_row) {
				foreach ($retorno_row as $exrow) {
					if($exrow->valida_estoque && ($quantidade*$exrow->quantidade) > $exrow->estoque)
						Filter::$msgs[$i++.'estoque'.$exrow->id] = str_replace("[ESTOQUE]", $exrow->estoque, lang('MSG_ERRO_ESTOQUE')." >> PRODUTO DO KIT = ".$exrow->nome);
				}
			}
		}

		if($valida_estoque) {
			$estoque = getValue("estoque", "produto", "id=" . $id_produto);
			if($quantidade > $estoque)
				Filter::$msgs[$i++.'estoque'] = str_replace("[ESTOQUE]", $estoque, lang('MSG_ERRO_ESTOQUE'));
		}
		  
		  $valor = post('valor');

           if (empty(Filter::$msgs)) {
			  $nomecliente = ($id_cadastro) ? getValue("nome", "cadastro", "id=" . $id_cadastro) : "";
			  $valor_total = $valor * $quantidade;
			  $quant_estoque = $quantidade * (-1);

              $data = array(
			    'id_empresa' => session('idempresa'),
                'id_venda' => $id_venda, 
                'id_cadastro' => $id_cadastro, 
                'id_produto' => $id_produto,
                'id_tabela' => $id_tabela,
                'valor' => $valor,
                'quantidade' => $quantidade,
                'valor_total' => $valor_total,
                'pago' => 2,
                'usuario' => session('nomeusuario'),
                'data' => "NOW()"
              );
			  $id_cadastro_venda = self::$db->insert("cadastro_vendas", $data);
			  
			  $kit = getValue("kit", "produto", "id=" . $id_produto);
			  if($kit) {
				  $nomekit = getValue("nome", "produto", "id=" . $id_produto);
				  $sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade " 
						. "\n FROM produto_kit as k"
						. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
						. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
						. "\n ORDER BY p.nome ";
				  $retorno_row = self::$db->fetch_all($sql);
				  if($retorno_row) {
					  foreach ($retorno_row as $exrow) {
						  $observacao = str_replace("[ID_VENDA]",$id_venda,lang('VENDA_KIT'));
						  $observacao = str_replace("[NOME_KIT]",$nomekit,$observacao);
						  $observacao = str_replace("[NOME_CLIENTE]",$nomecliente,$observacao);
						  
						  $data_estoque = array(
							'id_produto' => $exrow->id_produto, 
							'quantidade' => $quant_estoque,
							'tipo' => 2, 
							'motivo' => 3,
							'observacao' => $observacao, 
							'id_ref' => $id_cadastro_venda, 
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						  );
						  self::$db->insert("produto_estoque", $data_estoque);
						  $totalestoque = $this->getEstoqueTotal($exrow->id_produto);
						  $data_update = array(
							'estoque' => $totalestoque, 
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						  );
						  self::$db->update("produto", $data_update, "id=".$exrow->id_produto);
					  }
				  }
			  } else {
				  $observacao = str_replace("[ID_VENDA]",$id_venda,lang('VENDA_PRODUTO_CLIENTE'));
				  $observacao = str_replace("[NOME_CLIENTE]",$nomecliente,$observacao);
				  
				  $data_estoque = array(
					'id_produto' => $id_produto, 
					'quantidade' => $quant_estoque,
					'tipo' => 2, 
					'motivo' => 3,
					'observacao' => $observacao, 
					'id_ref' => $id_cadastro_venda, 
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				  );
				  self::$db->insert("produto_estoque", $data_estoque);
				  $totalestoque = $this->getEstoqueTotal($id_produto);
				  $data_update = array(
					'estoque' => $totalestoque, 
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				  );
				  self::$db->update("produto", $data_update, "id=".$id_produto);
			  }
			  
			  $valor_produtos_venda = $this->getTotalProdutosVenda($id_venda);
			  $data_update = array(
					'valor_total' => $valor_produtos_venda->valor_total, 
					'valor_pago' => $valor_produtos_venda->valor_total+$valor_produtos_venda->valor_acrescimo-$valor_produtos_venda->valor_desconto, 
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->update("vendas", $data_update, "id=".$id_venda);

			  if ($this->vendaPossuiPagamento($id_venda)==1){
				  $data_update_financeiro = array(
						'valor_total_venda' => $valor_produtos_venda->valor_total+$valor_produtos_venda->valor_acrescimo-$valor_produtos_venda->valor_desconto,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
				  );
				  self::$db->update("cadastro_financeiro", $data_update_financeiro, "id_venda=".$id_venda." AND inativo = 0");
			  }
			  
			  $message = lang('CADASTRO_ITEM_ADICIONADO_OK');
			  $redirecionar = "index.php?do=vendas&acao=finalizarvenda&id=".$id_venda;
			  if (self::$db->affected()) {
					Filter::msgOk($message, $redirecionar);
			  } else Filter::msgAlert(lang('NAOPROCESSADO'));
              
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Cadastro::vendaPossuiPagamento($id_venda)
	   *
       * @return
       */
	  public function vendaPossuiPagamento($id_venda)
      {
          $sql = "SELECT count(id) as vendas FROM cadastro_financeiro where id_venda = $id_venda AND inativo = 0";
          $row = self::$db->first($sql);
          return ($row->vendas);
      }
	  
	  /**
       * Cadastro::getEstoqueTotal()
	   *
       * @return
       */
	  public function getEstoqueTotal($id_produto)
      {
          $sql = "SELECT SUM(e.quantidade) AS total " 
		  . "\n FROM produto_estoque as e"
		  . "\n WHERE e.inativo = 0 AND e.id_produto = $id_produto ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Cadastro::getVendaCadastro()
	   *
       * @return
       */
	  public function getVendaCadastro($id_cadastro = 0, $id_venda = 0)
      {
		  $wvenda = ($id_venda) ? " AND v.id_venda = $id_venda" : "";
          $sql = "SELECT v.*, p.nome as produto, p.codigo, t.tabela " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n LEFT JOIN produto as p ON p.id = v.id_produto "
		  . "\n LEFT JOIN tabela_precos as t ON t.id = v.id_tabela "
		  . "\n WHERE v.id_cadastro = $id_cadastro AND v.pago = 0 AND v.inativo = 0 "
		  . "\n $wvenda "
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
	   * Cadastro::getTotalVendaCadastro()
	   *
       * @return
       */
	  public function getTotalVendaCadastro($id_cadastro = 0, $id_venda = 0)
      {
		  $wvenda = ($id_venda) ? " AND v.id_venda = $id_venda" : "";
          $sql = "SELECT SUM(v.quantidade) as quantidade, SUM(v.valor) as valor, SUM(v.valor * v.quantidade) as soma_total, SUM(v.valor_desconto) as valor_desconto, SUM(v.valor_total) as valor_total " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n WHERE v.id_cadastro = $id_cadastro AND v.pago = 0 AND v.inativo = 0 "
		  . "\n $wvenda "
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getProdutosAtivosDaVenda()
	   *
       * @return
       */
	  public function getProdutosAtivosDaVenda($id_venda)
      {
          $sql = "SELECT cv.* " 
		  . "\n FROM cadastro_vendas as cv"
		  . "\n WHERE cv.id_venda = $id_venda AND cv.inativo = 0 ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getProdutosVendaEntrega()
	   *
       * @return
       */
	  public function getProdutosVendaEntrega($id_venda)
      {
          $sql = "SELECT cv.id_produto, pro.nome, cv.quantidade, pro.codigobarras" 
		    . "\n FROM cadastro_vendas as cv "
		    . "\n LEFT JOIN produto as pro ON pro.id = cv.id_produto "
		    . "\n WHERE cv.id_venda = $id_venda AND cv.inativo = 0 ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendaProdutos()
	   *
       * @return
       */
	  public function getVendaProdutos($id_venda = 0)
      {
          $sql = "SELECT v.*, p.nome as produto, p.codigo, p.imagem, t.tabela " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n LEFT JOIN produto as p ON p.id = v.id_produto "
		  . "\n LEFT JOIN tabela_precos as t ON t.id = v.id_tabela "
		  . "\n WHERE v.id_venda = $id_venda AND v.inativo = 0 "
		  . "\n ORDER BY p.nome ASC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
	   * Cadastro::getTotalVenda()
	   *
       * @return
       */
	  public function getTotalVenda($id_venda = 0)
      {
          $sql = "SELECT SUM(v.quantidade) as quantidade, SUM(v.valor) as valor, SUM(round(v.valor*v.quantidade,2)) as soma_total, round(SUM(v.valor_desconto),2) as valor_desconto, SUM(v.valor_total) as valor_total, (round(SUM(v.valor_total),2)+round(SUM(v.valor_despesa_acessoria),2)-round(SUM(v.valor_desconto),2)) as valor_final, SUM(v.valor_despesa_acessoria) as valor_despesa_acessoria, vd.troco " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n LEFT JOIN vendas as vd ON vd.id = v.id_venda "
		  . "\n WHERE v.id_venda = $id_venda AND v.inativo = 0 ";
          $row = self::$db->first($sql);
          return ($row) ? $row : 0;
      }

	  /**
	   * Cadastro::getValoresProdutosVenda()
	   *
       * @return
       */
	  public function getValoresProdutosVenda($id_venda=0, $id_tabela=0)
      {
          $sql = "SELECT p.id, p.nome, cv.id_tabela, cv.quantidade, cv.valor, p.valor_avista, pt.valor_venda as valor_tabela "
		    . "\n FROM produto as p "
			. "\n LEFT JOIN cadastro_vendas as cv ON cv.id_produto=p.id "
			. "\n LEFT JOIN produto_tabela as pt ON pt.id_produto=p.id "
			. "\n WHERE cv.id_venda=$id_venda AND pt.id_tabela=$id_tabela AND cv.inativo=0";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }

	 /**
	   * Cadastro::processarAtualizarDescontoVenda()
	   *
	   */
	   public function processarAtualizarDescontoVenda()
	   {

			if (empty($_POST['id_venda']))
              Filter::$msgs['id_venda'] = lang('MSG_ERRO_VENDAS_VAZIO');

			if (empty(Filter::$msgs)) {

				$id_venda = post('id_venda');
				$desconto = converteMoeda(post('valor_desconto'));
				$desconto = round($desconto,2);
				$row_venda = Core::getRowById("vendas", $id_venda);

				$data_venda = array(
					'valor_desconto' => $desconto,
					'valor_pago' => $row_venda->valor_total+$row_venda->valor_despesa_acessoria-$desconto,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update("vendas", $data_venda, "id=".$id_venda);

				if ($this->vendaPossuiPagamento($id_venda)==1){
				  $data_update_financeiro = array(
						'valor_total_venda' => $row_venda->valor_total+$row_venda->valor_despesa_acessoria-$desconto,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
				  );
				  self::$db->update("cadastro_financeiro", $data_update_financeiro, "id_venda=".$id_venda." AND inativo = 0");
			  	}

				$produtos_venda = $this->getProdutosAtivosDaVenda($id_venda);
				if ($produtos_venda){
					$valor_venda = getValue("valor_total","vendas","id=" . $id_venda);
					$valor_venda = round($valor_venda,2);
					$porcentagem_desconto = ($desconto*100)/$valor_venda;
					foreach($produtos_venda as $produto_venda){
						$desconto_produto = ($porcentagem_desconto*$produto_venda->valor_total)/100;
						$desconto_produto = round($desconto_produto,2);
						$data_desconto = array(
							'valor_desconto' => $desconto_produto,
							'valor_total' => ($produto_venda->quantidade*$produto_venda->valor)
						);
						self::$db->update("cadastro_vendas", $data_desconto, "id=".$produto_venda->id);
					}
				}

				$novo_valor_desconto = $this->obterDescontosVenda($id_venda, $row_venda->id_caixa, $row_venda->id_empresa, $row_venda->id_cadastro);

				if (round($novo_valor_desconto->vlr_desc,2) != $desconto){
					$novo_desconto = ($desconto - round($novo_valor_desconto->vlr_desc,2)) + $novo_valor_desconto->valor_desconto;
					$data_desconto = array( 'valor_desconto' => $novo_desconto );
					self::$db->update("cadastro_vendas", $data_desconto, "id=".$novo_valor_desconto->id);
				}	
				$message = lang('CADASTRO_DESCONTO_ALTERAR');
				Filter::msgOk($message, "index.php?do=vendas&acao=finalizarvenda&id=".$id_venda);
			} else
              print Filter::msgStatus();
	   }

	   /**
	   * Cadastro::processarAtualizarAcrescimoVenda()
	   *
	   */
	  public function processarAtualizarAcrescimoVenda()
	  {
		   if (empty($_POST['id_venda']))
			 Filter::$msgs['id_venda'] = lang('MSG_ERRO_VENDAS_VAZIO');		  

		   if (empty(Filter::$msgs)) {

			   $id_venda = post('id_venda');
			   $acrescimo = converteMoeda(post('valor_despesa_acessoria'));
			   $acrescimo = round($acrescimo,2);
			   $row_venda = Core::getRowById("vendas", $id_venda);

			   $data_venda = array(
				   'valor_despesa_acessoria' => $acrescimo,
				   'valor_pago' => $row_venda->valor_total - $row_venda->valor_desconto + $acrescimo,
				   'usuario' => session('nomeusuario'),
				   'data' => "NOW()"
			   );
			   self::$db->update("vendas", $data_venda, "id=".$id_venda);

			   if ($this->vendaPossuiPagamento($id_venda)==1){
				   $data_update_financeiro = array(
					   'valor_total_venda' => $row_venda->valor_total - $row_venda->valor_desconto + $acrescimo,
					   'usuario' => session('nomeusuario'),
					   'data' => "NOW()"
					);
					self::$db->update("cadastro_financeiro", $data_update_financeiro, "id_venda=".$id_venda." AND inativo = 0");
				}

				$produtos_venda = $this->getProdutosAtivosDaVenda($id_venda);
				if ($produtos_venda){
					$valor_venda = getValue("valor_total","vendas","id=" . $id_venda);
					$valor_venda = round($valor_venda,2);
					$valor_acrescimo_produto = round(($acrescimo / count($produtos_venda)),2);
					foreach($produtos_venda as $produto_venda){
						$data_acrescimo_produto = array(
							'valor_despesa_acessoria' => $valor_acrescimo_produto,
							'valor_total' => ($produto_venda->quantidade*$produto_venda->valor)
						);
						self::$db->update("cadastro_vendas", $data_acrescimo_produto, "id=".$produto_venda->id);
					}
				}

				$novo_valor_acrescimo = $this->obterAcrescimoVenda($id_venda, $row_venda->id_caixa, $row_venda->id_empresa, $row_venda->id_cadastro);
				if (round($novo_valor_acrescimo->vlr_acrescimo,2) != round($acrescimo,2)){
					$novo_acrescimo = ($acrescimo - round($novo_valor_acrescimo->vlr_acrescimo,2)) + $novo_valor_acrescimo->valor_despesa_acessoria;
					$data_acrescimo = array( 'valor_despesa_acessoria' => $novo_acrescimo );
					self::$db->update("cadastro_vendas", $data_acrescimo, "id=".$novo_valor_acrescimo->id);
				}
			   	$message = lang('CADASTRO_ACRESCIMO_ALTERAR');
			   	Filter::msgOk($message, "index.php?do=vendas&acao=finalizarvenda&id=".$id_venda);
		   } else
			 print Filter::msgStatus();
	  }

      /**
       * Cadastro::processarFinanceiro()
       * @return
       */
      public function processarFinanceiro()
      {	
		  $pagamentoProcessado = false;	  
		  $total_parcelas = (is_numeric(post('total_parcelas')) and post('total_parcelas') > 0) ? post('total_parcelas') : 1;

		  if (empty($_POST['tipo_pagamento'])) {
              Filter::$msgs['tipo_pagamento'] = lang('MSG_ERRO_TIPO');			  
		  } else {
			  $num_parcelas = getValue("parcelas","tipo_pagamento","id = ".$_POST['tipo_pagamento']);
			  if($total_parcelas > $num_parcelas)
				  Filter::$msgs['total_parcelas'] = lang('MSG_ERRO_PARCELAS');
		  }

		  if (empty($_POST['valor_pago']))
              Filter::$msgs['valor_pago'] = lang('MSG_ERRO_VALOR');

          if (empty(Filter::$msgs)) {
              $id_pagamento = 0;
              $id_cadastro = post('id_cadastro');
              $id_venda = post('id_venda');
              $tipo = post('tipo_pagamento');
			  $id_tipo_categoria = getValue("id_categoria","tipo_pagamento","id=" . $tipo);
              $valor_pago = converteMoeda(post('valor_pago'));
              $valor_total_venda = converteMoeda(post('valor_final_venda'));
			  $nomecliente = getValue("nome", "cadastro", "id=" .$id_cadastro);
			  $valor_total = getValue("valor_total", "vendas", "id=" .$id_venda);
			  $desconto = getValue("valor_desconto", "vendas", "id=" .$id_venda);
			  $acrescimo = getValue("valor_despesa_acessoria", "vendas", "id=" .$id_venda);
			  $data_vencimento = (empty($_POST['data_vencimento'])) ? "NOW()" : dataMySQL(post('data_vencimento')); 

              $data = array(
                'id_cadastro' => $id_cadastro, 
                'id_venda' => $id_venda, 
                'tipo' => $tipo, 
                'valor_total_venda' => $valor_total_venda,
                'total_parcelas' => $total_parcelas,
                'pago' => 2, 
                'data_pagamento' => "NOW()",
                'usuario' => session('nomeusuario'),
                'data' => "NOW()"
              );

              $data_receita = array(
                'id_cadastro' => $id_cadastro, 
                'id_venda' => $id_venda, 
                'id_conta' => 12, 
                'tipo' => $tipo, 
                'pago' => 2, 
                'data_pagamento' => "NOW()",
                'usuario' => session('nomeusuario'),
                'data' => "NOW()"
              );

			  $row_cartoes = Core::getRowById("tipo_pagamento", $tipo);
			  $dias = $row_cartoes->dias;
			  $taxa = $row_cartoes->taxa;
			  $id_banco = (empty($_POST['id_banco'])) ? $row_cartoes->id_banco : post('id_banco');

              if($id_tipo_categoria == '1') {				

				$data['valor_pago'] = $valor_pago;
				$data['data_vencimento'] = $data_vencimento;

				$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
				if (self::$db->affected()) $pagamentoProcessado = true;

			  } elseif($id_tipo_categoria == '2' or $id_tipo_categoria == '4') {

					$descricao = ($id_tipo_categoria == '2') ? "CHEQUE" : $row_cartoes->tipo;
					$data['valor_pago'] = $valor_pago;
					$data['id_banco'] = $id_banco;
					$data['nome_cheque'] = sanitize(post('nome_cheque'));
					$data['banco_cheque'] = sanitize(post('banco_cheque'));
					$data['numero_cheque'] = sanitize(post('numero_cheque'));
					$data['data_vencimento'] = $data_vencimento;

					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
					if (self::$db->affected()) $pagamentoProcessado = true;
					$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
					$data_parcela = explode('/', $data_temp);
					$valor_cheque = round($valor_pago/$total_parcelas, 2);
					$diferenca = $valor_pago - ($valor_cheque*$total_parcelas);
					for($i=0;$i<$total_parcelas;$i++)
					{
						$newData = novadata($data_parcela[1] + $i, $data_parcela[0], $data_parcela[2]);		
						$parc = ($i+1);
						$p = $parc."/".$total_parcelas;		
						$data_receita['id_banco'] = $id_banco;	
						$data_receita['descricao'] = "$descricao - $p - ".$nomecliente;
						$data_receita['valor'] = $valor_cheque;
						$data_receita['valor_pago'] = $valor_cheque;
						$data_receita['id_pagamento'] = $id_pagamento;
						$data_receita['data_pagamento'] = $newData;
						$data_receita['parcela'] = $parc;
						$data_receita['pago'] = 2;
						if($i == 0) {
							$data_receita['valor'] = $valor_cheque + $diferenca;
							$data_receita['valor_pago'] = $valor_cheque + $diferenca;
						}
						self::$db->insert("receita", $data_receita);
					}	
			  } elseif($id_tipo_categoria == '3' or $id_tipo_categoria == '6') {

				$data['valor_pago'] = $valor_pago;
				$data['data_vencimento'] = $data_vencimento;
				$data['id_banco'] = $id_banco;

				$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
				if (self::$db->affected()) $pagamentoProcessado = true;
				$data_receita['id_banco'] = $id_banco;
				$data_receita['descricao'] = $row_cartoes->tipo." - ".$nomecliente;
				$data_receita['valor'] = $valor_pago;
				$data_receita['valor_pago'] = $valor_pago;
				$data_receita['id_pagamento'] = $id_pagamento;
				$data_receita['data_recebido'] = $data_vencimento;
				$data_receita['parcela'] = 1;
				$data_receita['pago'] = 2;
				self::$db->insert("receita", $data_receita);

			  } else {	 
				$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
				$data_parcela = explode('/', $data_temp);
				$data_receita['id_banco'] = $id_banco;
				$data_receita['pago'] = 2;		
				$valor_taxa = $valor_pago*$taxa/100;
				$valor_cartao = $valor_pago - $valor_taxa;
				$valor_parcelas_pago = round($valor_pago/$total_parcelas, 2);
				$valor_parcelas_cartao = $valor_cartao/$total_parcelas;
				$diferenca = $valor_pago - ($valor_parcelas_pago * $total_parcelas);
				$diferenca_parcela = $valor_cartao - ($valor_parcelas_cartao * $total_parcelas);
				$data['id_banco'] = $id_banco;
				$data['valor_pago'] = $valor_pago;
				$data['valor_total_cartao'] = $valor_cartao;
				$data['valor_parcelas_cartao'] = $valor_parcelas_cartao;
				$data['parcelas_cartao'] = $total_parcelas;
				$data['data_vencimento'] = $data_vencimento;
				$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
				if (self::$db->affected()) $pagamentoProcessado = true;
				for($i=1;$i<$total_parcelas+1;$i++)
				{
					if($dias == 30) {
						$m = $i - 1;
						$newData = novadata($data_parcela[1] + $m, $data_parcela[0], $data_parcela[2]);	
					} else {
						$newData = novadata($data_parcela[1], $data_parcela[0] + ($i*$dias), $data_parcela[2]);	
					}			
					$p = $i."/".$total_parcelas;	
					$data_receita['descricao'] = $row_cartoes->tipo." - $p - ".$nomecliente;
					$data_receita['valor'] = $valor_parcelas_pago;
					$data_receita['valor_pago'] = $valor_parcelas_cartao;
					$data_receita['data_recebido'] = $newData;
					$data_receita['parcela'] = $i;		
					if($i == 1) {
						$data_receita['valor'] = $valor_parcelas_pago + $diferenca_parcela;
						$data_receita['valor_pago'] = $valor_parcelas_cartao + $diferenca_parcela;
					}					
					$data_receita['id_pagamento'] = $id_pagamento;
					self::$db->insert("receita", $data_receita);
				}
			  }

			  $total_financeiro = $this->getTotalFinanceiro($id_venda);
              $data_vendas = array(
                'valor_pago' => $total_financeiro,
				'troco' => $total_financeiro - ($valor_total +  $acrescimo - $desconto)
              );
			  self::$db->update("vendas", $data_vendas, "id=".$id_venda);
			  if (self::$db->affected()) $pagamentoProcessado = true;

			  $message = lang('CADASTRO_PAGAMENTO_OK');
			  if ($pagamentoProcessado) {
				Filter::msgOk($message, "index.php?do=vendas&acao=finalizarvenda&id=".$id_venda);
			  } else Filter::msgAlert(lang('NAOPROCESSADO'));

          } else
              print Filter::msgStatus();
      }

	/**
     * CADASTRO::ObterFormasPagamentoVenda($id_venda)
	 *
     * @return
     */
	public function ObterFormasPagamentoVenda($id_venda)
    {
        $sql = "SELECT tp.avista, tp.id_categoria, cf.* "
		. "\n FROM cadastro_financeiro as cf "
		. "\n LEFT JOIN tipo_pagamento as tp ON tp.id=cf.tipo "
		. "\n WHERE cf.inativo = 0 AND cf.id_venda = $id_venda ";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

	/**
     * Cadastro::getTodosProdutosDeUmaVenda($id_venda)
	 *
     * @return
     */
	public function getTodosProdutosDeUmaVenda($id_venda)
    {
        $sql = "SELECT * FROM cadastro_vendas WHERE id_venda = $id_venda AND inativo = 0 ";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }

	/**
     * Cadastro::calcularValorNormalEValorAVistaVenda($id_venda,$id_tabela)
	 *
     * @return
     */
	public function calcularValorNormalEValorAVistaVenda($id_venda,$id_tabela)
    {

		$valorTotalAVista = 0;
		$valorTotalNormal = 0;
		$produtosVenda = $this->getValoresProdutosVenda($id_venda,$id_tabela);
		if ($produtosVenda) {
			foreach($produtosVenda as $produtoVenda) {
				if (round($produtoVenda->valor,2)!=round($produtoVenda->valor_avista,2) && round($produtoVenda->valor,2)!=round($produtoVenda->valor_tabela,2)) {
					$valorTotalAVista += round($produtoVenda->valor,2)*round($produtoVenda->quantidade,2);
					$valorTotalNormal += round($produtoVenda->valor,2)*round($produtoVenda->quantidade,2);
				} else {
					$valorTotalNormal += round($produtoVenda->valor_tabela,2)*round($produtoVenda->quantidade,2);
					if (round($produtoVenda->valor_avista,2)>0) {
						$valorTotalAVista += round($produtoVenda->valor_avista,2)*round($produtoVenda->quantidade,2);
					} else {
						$valorTotalAVista += round($produtoVenda->valor_tabela,2)*round($produtoVenda->quantidade,2);
					}
				}
			}
		}

		$array_valores_venda = array(
			'valorNormal' => $valorTotalNormal,
			'valorAVista' => $valorTotalAVista
		);

		return $array_valores_venda;
	}

	/**
     * Cadastro::vendaComPagamentosAVista($id_venda)
     * @return
     */
    public function vendaComPagamentosAVista($id_venda)
    {
		if ($id_venda > 0) {
			$formas_pagamento_row = $this->ObterFormasPagamentoVenda($id_venda);
			$pgto_avista = 1;
			if ($formas_pagamento_row){
				foreach ($formas_pagamento_row as $pgto_row) {
					$pgto_avista *= intval($pgto_row->avista);
				}
			} else {
				$pgto_avista = 0;			
			}
			return ($pgto_avista==1) ? true : false;
		} else {
			return false;
		}
	}

	/**
     * Cadastro::atualizarValoresVendaAberto($id_venda)
     * @return
     */
    public function atualizarValoresVendaAberto($id_venda)
    {
		$valor_avista = 0;
		$valor_aprazo = 0;
		$soma_dinheiro = 0;
		$venda_row = Core::getRowById("vendas", $id_venda);
		$id_tabela = getValue('id_tabela','cadastro_vendas','id_venda='.$id_venda);

		//valores da venda para normal e para aVista, sem descontos e sem acrescimos.
		//[valorNormal] => 939.65 [valorAVista] => 183.51
		$valores_venda = $this->calcularValorNormalEValorAVistaVenda($id_venda,$id_tabela);

		$formas_pagamento_row = $this->ObterFormasPagamentoVenda($id_venda);
		$pgto_avista = 1;
		if ($formas_pagamento_row){
			foreach ($formas_pagamento_row as $pgto_row) {
				$pgto_avista *= intval($pgto_row->avista);
				if ($pgto_row->avista) {
					$soma_dinheiro += $pgto_row->valor_pago;
				}
			}
		} else {
			$pgto_avista = 0;			
		}

		$desconto = $venda_row->valor_desconto;
		$acrescimo = $venda_row->valor_despesa_acessoria;
		//valor da venda sem o desconto e sem o acrescimo
		$valor_total_venda = ($pgto_avista) ? $valores_venda['valorAVista'] : $valores_venda['valorNormal'];

		$produtos_row = $this->getTodosProdutosDeUmaVenda($id_venda);
		$porcentagem_desconto = ($desconto>0) ? ($desconto*100)/$valor_total_venda : 0;
		$porcentagem_desconto = round($porcentagem_desconto,2);
		if ($produtos_row) {
			foreach($produtos_row as $prow) {
				$valor_produto_aprazo = getValue('valor_venda','produto_tabela','id_tabela = '.$prow->id_tabela.' AND id_produto = '.$prow->id_produto);
				$valor_produto_avista = getValue('valor_avista','produto','id='.$prow->id_produto);

				if (round($prow->valor,2)!=round($valor_produto_aprazo,2) && round($prow->valor,2)!=round($valor_produto_avista,2)) {
					$valor_venda_prazo = $prow->valor*$prow->quantidade;
					$avista = $prow->valor*$prow->quantidade;
					$valor_produto_aprazo = $valor_produto_avista = $prow->valor;
				} else {
					$valor_venda_prazo = $valor_produto_aprazo*$prow->quantidade;
					$avista = $valor_produto_avista*$prow->quantidade;						
				}					
				$valor_aprazo += $valor_venda_prazo;
				$valor_avista += (($avista>0) ? $avista : $valor_venda_prazo);
					  
				$data_produto_venda = 0;
				$valor_cv = 0;
				$valor_total_cv = 0;
				$valor_desconto_cv = 0;
				$valor_acrescimo_cv = $acrescimo / (count($produtos_row));
				if ($pgto_avista) {
					$valor_cv = ($valor_produto_avista>0) ? $valor_produto_avista : $valor_produto_aprazo;
					$valor_total_cv = ($avista>0) ? $avista : $valor_venda_prazo;
				} else {
					$valor_cv = $valor_produto_aprazo;
					$valor_total_cv = $valor_venda_prazo;
				}
				$valor_desconto_cv = ($desconto>0) ? ($porcentagem_desconto*$valor_total_cv)/100 : 0;

				$data_produto_venda = array(
					'valor' => $valor_cv,
					'valor_despesa_acessoria' => $valor_acrescimo_cv,
					'valor_desconto' => round($valor_desconto_cv,2),
					'valor_total' => $valor_total_cv,
				);
				self::$db->update("cadastro_vendas", $data_produto_venda, "id=".$prow->id);
			}

			///////////////////////////////////
			//Este trecho de código serve para ajustar o valor do desconto quando o mesmo for quebrado e gerar diferença no total.
			$novo_valor_desconto = $this->obterDescontosVenda($id_venda, $venda_row->id_caixa, $venda_row->id_empresa, $venda_row->id_cadastro);
			$desconto_venda = round(getValue("valor_desconto","vendas","id=".$id_venda),2);
			if (round($novo_valor_desconto->vlr_desc,2) != $desconto_venda){
				$novo_desconto = ($desconto - round($novo_valor_desconto->vlr_desc,2)) + $novo_valor_desconto->valor_desconto;
				$data_desconto = array( 'valor_desconto' => $novo_desconto );
				self::$db->update("cadastro_vendas", $data_desconto, "id=".$novo_valor_desconto->id);
			}
			///////////////////////////////////
			//Este trecho de código serve para ajustar o valor do acréscimo quando o mesmo for quebrado e gerar diferença no total.
			$novo_valor_acrescimo = $this->obterAcrescimoVenda($id_venda, $venda_row->id_caixa, $venda_row->id_empresa, $venda_row->id_cadastro);
			if (round($novo_valor_acrescimo->vlr_acrescimo,2) != round($acrescimo,2)){
				$novo_acrescimo = ($acrescimo - round($novo_valor_acrescimo->vlr_acrescimo,2)) + $novo_valor_acrescimo->valor_despesa_acessoria;
				$data_acrescimo = array( 'valor_despesa_acessoria' => $novo_acrescimo );
				self::$db->update("cadastro_vendas", $data_acrescimo, "id=".$novo_valor_acrescimo->id);
			}
			///////////////////////////////////

		}

		$valor_total = ($pgto_avista) ? $valor_avista : $valor_aprazo;
		$soma_restante = $valor_total - $soma_dinheiro;
		$total_pagar_dinheiro = $valor_total+$acrescimo-$desconto-$soma_restante;
		$troco = $soma_dinheiro - $total_pagar_dinheiro;
		$troco = ($troco<0) ? 0 : $troco;

		$total_financeiro = $this->getTotalFinanceiro($id_venda);
        $data_vendas = array(
			'valor_total' => $valor_total,
            'valor_pago' => $total_financeiro,
			'troco' => $troco
        );
		self::$db->update("vendas", $data_vendas, "id=".$id_venda);
	}

	/**
     * Cadastro::apagarCadastroFinanceiroVendaAberta($id_cadastro_financeiro)
     * @return
     */
    public function apagarCadastroFinanceiroVendaAberta($id_cadastro_financeiro)
    {
		if ($id_cadastro_financeiro>0) {
			$id_venda = getValue("id_venda","cadastro_financeiro","id = ".$id_cadastro_financeiro);
			if ($id_venda>0) {
				$data = array(
					'inativo' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);		
				self::$db->update("receita", $data, "id_pagamento=" . $id_cadastro_financeiro);
				self::$db->update("cadastro_financeiro", $data, "id=" . $id_cadastro_financeiro);
				if (self::$db->affected())
				{
					$this->atualizarValoresVendaAberto($id_venda);
					print Filter::msgOk(lang('CADASTRO_APAGAR_PAGAMENTO_OK'), "index.php?do=vendas&acao=finalizarvenda&id=".$id_venda);
				} else {
					print Filter::msgAlert(lang('NAOPROCESSADO'));
				}
			} else {
				print Filter::msgAlert(lang('NAOPROCESSADO'));
			}
		} else {
			print Filter::msgAlert(lang('NAOPROCESSADO'));
		}
	}

	/**
     * Cadastro::processarFinanceiroVendaAberto()
     * @return
     */
    public function processarFinanceiroVendaAberto()
    {
		$pagamentoProcessado = false;	  
		$total_parcelas = (is_numeric(post('total_parcelas_aberto')) && post('total_parcelas_aberto') > 0) ? post('total_parcelas_aberto') : 1;

		if (empty($_POST['tipo_pagamento_finalizarvenda'])) {
            Filter::$msgs['tipo_pagamento_finalizarvenda'] = lang('MSG_ERRO_TIPO');			  
		} else {
			$num_parcelas = getValue("parcelas","tipo_pagamento","id = ".$_POST['tipo_pagamento_finalizarvenda']);
			if($total_parcelas > $num_parcelas)
				Filter::$msgs['total_parcelas'] = lang('MSG_ERRO_PARCELAS');
		}

		if (empty($_POST['valor_pago_aberto']))
            Filter::$msgs['valor_pago'] = lang('MSG_ERRO_VALOR');

        if (empty(Filter::$msgs)) {

            $id_pagamento = 0;
            $id_cadastro = post('id_cadastro');
            $id_venda = post('id_venda');
            $tipo = post('tipo_pagamento_finalizarvenda');
			$id_tipo_categoria = getValue("id_categoria","tipo_pagamento","id=" . $tipo);
			$nomecliente = getValue("nome", "cadastro", "id=" .$id_cadastro);
			$data_vencimento = (empty($_POST['data_pagamento'])) ? "NOW()" : dataMySQL(post('data_pagamento')); 
			$valor_pago = converteMoeda(post('valor_pago_aberto'));
			$venda_row = Core::getRowById("vendas", $id_venda);
			$id_tabela = getValue('id_tabela','cadastro_vendas','id_venda='.$id_venda);			
			$valor_total = $venda_row->valor_total;
			$desconto = $venda_row->valor_desconto;
			$acrescimo = $venda_row->valor_despesa_acessoria;			

			$valor_avista = 0;
			$valor_aprazo = 0;
			$soma_dinheiro = 0;

			$formas_pagamento_row = $this->ObterFormasPagamentoVenda($id_venda);
			$pgto_avista = 1;
			if ($formas_pagamento_row){
				foreach ($formas_pagamento_row as $pgto_row) {
					$pgto_avista *= intval($pgto_row->avista);
					if ($pgto_row->avista) {
						$soma_dinheiro += $pgto_row->valor_pago;
					}
				}
			}
			$pgto_avista *= intval(getValue('avista', 'tipo_pagamento', 'id='.$tipo));

			//valores da venda para normal e para aVista, sem descontos e sem acrescimos.
			//[valorNormal] => 939.65 [valorAVista] => 183.51
			$valores_venda = $this->calcularValorNormalEValorAVistaVenda($id_venda,$id_tabela);
			//valor da venda sem o desconto e sem o acrescimo
			$valor_total_venda = ($pgto_avista) ? $valores_venda['valorAVista'] : $valores_venda['valorNormal'];

			$produtos_row = $this->getTodosProdutosDeUmaVenda($id_venda);
			$porcentagem_desconto = ($desconto>0) ? ($desconto*100)/$valor_total_venda : 0;
			$porcentagem_desconto = round($porcentagem_desconto,2);
			if ($produtos_row) {
				foreach($produtos_row as $prow) {
					$valor_produto_aprazo = getValue('valor_venda','produto_tabela','id_tabela = '.$prow->id_tabela.' AND id_produto = '.$prow->id_produto);
					$valor_produto_avista = getValue('valor_avista','produto','id='.$prow->id_produto);

					if (round($prow->valor,2)!=round($valor_produto_aprazo,2) && round($prow->valor,2)!=round($valor_produto_avista,2)) {
						$valor_venda_prazo = $prow->valor*$prow->quantidade;
						$avista = $prow->valor*$prow->quantidade;
						$valor_produto_aprazo = $valor_produto_avista = $prow->valor;
					} else {
						$valor_venda_prazo = $valor_produto_aprazo*$prow->quantidade;
						$avista = $valor_produto_avista*$prow->quantidade;						
					}					
					$valor_aprazo += $valor_venda_prazo;
					$valor_avista += (($avista>0) ? $avista : $valor_venda_prazo);
					  
					$data_produto_venda = 0;
					$valor_cv = 0;
					$valor_total_cv = 0;
					$valor_desconto_cv = 0;
					$valor_acrescimo_cv = $acrescimo / (count($produtos_row));
					if ($pgto_avista) {
						$valor_cv = ($valor_produto_avista>0) ? $valor_produto_avista : $valor_produto_aprazo;
						$valor_total_cv = ($avista>0) ? $avista : $valor_venda_prazo;
					} else {
						$valor_cv = $valor_produto_aprazo;
						$valor_total_cv = $valor_venda_prazo;
					}
					$valor_desconto_cv = ($desconto>0) ? ($porcentagem_desconto*$valor_total_cv)/100 : 0;
	
					$data_produto_venda = array(
						'valor' => $valor_cv,
						'valor_despesa_acessoria' => $valor_acrescimo_cv,
						'valor_desconto' => round($valor_desconto_cv,2),
						'valor_total' => $valor_total_cv,
					);
					self::$db->update("cadastro_vendas", $data_produto_venda, "id=".$prow->id);
				}
			}

			///////////////////////////////////
			//Este trecho de código serve para ajustar o valor do desconto quando o mesmo for quebrado e gerar diferença no total.
			$novo_valor_desconto = $this->obterDescontosVenda($id_venda, $venda_row->id_caixa, $venda_row->id_empresa, $venda_row->id_cadastro);
			$desconto_venda = round(getValue("valor_desconto","vendas","id=".$id_venda),2);
			if (round($novo_valor_desconto->vlr_desc,2) != $desconto_venda){
				$novo_desconto = ($desconto - round($novo_valor_desconto->vlr_desc,2)) + $novo_valor_desconto->valor_desconto;
				$data_desconto = array( 'valor_desconto' => $novo_desconto );
				self::$db->update("cadastro_vendas", $data_desconto, "id=".$novo_valor_desconto->id);
			}
			///////////////////////////////////
			//Este trecho de código serve para ajustar o valor do acréscimo quando o mesmo for quebrado e gerar diferença no total.
			$novo_valor_acrescimo = $this->obterAcrescimoVenda($id_venda, $venda_row->id_caixa, $venda_row->id_empresa, $venda_row->id_cadastro);
			if (round($novo_valor_acrescimo->vlr_acrescimo,2) != round($acrescimo,2)){
				$novo_acrescimo = ($acrescimo - round($novo_valor_acrescimo->vlr_acrescimo,2)) + $novo_valor_acrescimo->valor_despesa_acessoria;
				$data_acrescimo = array( 'valor_despesa_acessoria' => $novo_acrescimo );
				self::$db->update("cadastro_vendas", $data_acrescimo, "id=".$novo_valor_acrescimo->id);
			}
			///////////////////////////////////

			$valor_total_venda = ($pgto_avista) ? $valor_avista : $valor_aprazo;
			$valor_total_venda += $acrescimo;
			$valor_total_venda -= $desconto;

            $data = array(
            	'id_cadastro' => $id_cadastro, 
                'id_venda' => $id_venda, 
                'tipo' => $tipo, 
                'valor_total_venda' => $valor_total_venda,
                'total_parcelas' => $total_parcelas,
                'pago' => 2, 
                'data_pagamento' => "NOW()",
                'usuario' => session('nomeusuario'),
                'data' => "NOW()"
            );

			$data_update_financeiro = array(
				'valor_total_venda' => $valor_total_venda,
				'usuario' => session('nomeusuario'),
                'data' => "NOW()"
			);
			self::$db->update("cadastro_financeiro", $data_update_financeiro, "inativo = 0 AND id_venda=".$id_venda);

			$data_receita = array(
            	'id_cadastro' => $id_cadastro, 
                'id_venda' => $id_venda, 
                'id_conta' => 12, 
                'tipo' => $tipo, 
                'pago' => 2, 
                'data_pagamento' => "NOW()",
                'usuario' => session('nomeusuario'),
                'data' => "NOW()"
            );

			$row_cartoes = Core::getRowById("tipo_pagamento", $tipo);
			$dias = $row_cartoes->dias;
			$taxa = $row_cartoes->taxa;
			$id_banco = (empty($_POST['id_banco'])) ? $row_cartoes->id_banco : post('id_banco');
			$descricao_pagamento = $row_cartoes->tipo;

			if($id_tipo_categoria == '1') {				

				$data['valor_pago'] = $valor_pago;
				$data['data_vencimento'] = $data_vencimento;

				$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
				if (self::$db->affected()) $pagamentoProcessado = true;

			} elseif($id_tipo_categoria == '2' or $id_tipo_categoria == '4') {

				$descricao = ($id_tipo_categoria == '2') ? "CHEQUE" : $row_cartoes->tipo;
				$data['valor_pago'] = $valor_pago;
				$data['id_banco'] = $id_banco;
				$data['data_vencimento'] = ($id_tipo_categoria == '2') ? $data_vencimento : dataMySQL(post('data_parcela_boleto'));

				$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
				if (self::$db->affected()) $pagamentoProcessado = true;
				$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
				$data_parcela = explode('/', $data_temp);
				$valor_cheque = round($valor_pago/$total_parcelas, 2);
				$diferenca = $valor_pago - ($valor_cheque*$total_parcelas);
				for($i=0;$i<$total_parcelas;$i++)
				{
					$newData = novadata($data_parcela[1] + $i, $data_parcela[0], $data_parcela[2]);		
					$parc = ($i+1);
					$p = $parc."/".$total_parcelas;		
					$data_receita['id_banco'] = $id_banco;	
					$data_receita['descricao'] = "$descricao - $p - ".$nomecliente;
					$data_receita['valor'] = $valor_cheque;
					$data_receita['valor_pago'] = $valor_cheque;
					$data_receita['id_pagamento'] = $id_pagamento;
					$data_receita['data_pagamento'] = $newData;
					$data_receita['parcela'] = $parc;
					$data_receita['pago'] = 2;
					if($i == 0) {
						$data_receita['valor'] = $valor_cheque + $diferenca;
						$data_receita['valor_pago'] = $valor_cheque + $diferenca;
					}
					self::$db->insert("receita", $data_receita);
				}	
			} elseif($id_tipo_categoria == '3' or $id_tipo_categoria == '6') {

				$data['valor_pago'] = $valor_pago;
				$data['data_vencimento'] = $data_vencimento;
				$data['id_banco'] = $id_banco;

				$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
				if (self::$db->affected()) $pagamentoProcessado = true;
				$data_receita['id_banco'] = $id_banco;
				$data_receita['descricao'] = $row_cartoes->tipo." - ".$nomecliente;
				$data_receita['valor'] = $valor_pago;
				$data_receita['valor_pago'] = $valor_pago;
				$data_receita['id_pagamento'] = $id_pagamento;
				$data_receita['data_recebido'] = $data_vencimento;
				$data_receita['parcela'] = 1;
				$data_receita['pago'] = 2;
				self::$db->insert("receita", $data_receita);

			} elseif ($id_tipo_categoria =='9') { //Pagamento no Crediário
					
				$data['pago'] = 2;
				$data_receita['pago'] = 2;
				$descricao = $descricao_pagamento;
				$data['valor_pago'] = $valor_pago;
				$data['id_banco'] = $id_banco;
				$data['nome_cheque'] = sanitize(post('nome_cheque'));
				$data['banco_cheque'] = sanitize(post('banco_cheque'));
				$data['numero_cheque'] = sanitize(post('numero_cheque'));
				$data['data_vencimento'] = $data_vencimento;
				$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
				
				$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
				$data_parcela = explode('/', $data_temp);
				$valor_cheque = round($valor_pago/$total_parcelas, 2);
				$diferenca = $valor_pago - ($valor_cheque*$total_parcelas);
				  
				for($i=0;$i<$total_parcelas;$i++)
				{
					if($dias==30 || $dias==0 || empty($dias)) {
						$newData = novadata($data_parcela[1] + ($i+1), $data_parcela[0], $data_parcela[2]);	
					} else {
						$newData = novadata($data_parcela[1], $data_parcela[0] + (($i+1)*$dias), $data_parcela[2]);	
					}	

					$parc = ($i+1);
					$p = $parc."/".$total_parcelas;		
					$data_receita['id_banco'] = $id_banco;	
					$data_receita['descricao'] = "$descricao - $p - ".$nomecliente;
					$data_receita['valor'] = $valor_cheque;
					$data_receita['valor_pago'] = $valor_cheque;
					$data_receita['id_pagamento'] = $id_pagamento;
					$data_receita['data_pagamento'] = $newData;
					$data_receita['parcela'] = $parc;
					$data_receita['promissoria'] = 1;
					if($i == 0) {
						$data_receita['valor'] = $valor_cheque + $diferenca;
						$data_receita['valor_pago'] = $valor_cheque + $diferenca;
					}
					self::$db->insert("receita", $data_receita);
				}

			} else {	 

				$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
				$data_parcela = explode('/', $data_temp);
				$data_receita['id_banco'] = $id_banco;
				$data_receita['pago'] = 2;		
				$valor_taxa = $valor_pago*$taxa/100;
				$valor_cartao = $valor_pago - $valor_taxa;
				$valor_parcelas_pago = round($valor_pago/$total_parcelas, 2);
				$valor_parcelas_cartao = $valor_cartao/$total_parcelas;
				$diferenca = $valor_pago - ($valor_parcelas_pago * $total_parcelas);
				$diferenca_parcela = $valor_cartao - ($valor_parcelas_cartao * $total_parcelas);
				$data['id_banco'] = $id_banco;
				$data['valor_pago'] = $valor_pago;
				$data['valor_total_cartao'] = $valor_cartao;
				$data['valor_parcelas_cartao'] = $valor_parcelas_cartao;
				$data['parcelas_cartao'] = $total_parcelas;
				$data['data_vencimento'] = $data_vencimento;
				$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
				if (self::$db->affected()) $pagamentoProcessado = true;
				for($i=1;$i<$total_parcelas+1;$i++)
				{
					if($dias == 30) {
						$m = $i - 1;
						$newData = novadata($data_parcela[1] + $m, $data_parcela[0], $data_parcela[2]);	
					} else {
						$newData = novadata($data_parcela[1], $data_parcela[0] + ($i*$dias), $data_parcela[2]);	
					}			
					$p = $i."/".$total_parcelas;	
					$data_receita['descricao'] = $row_cartoes->tipo." - $p - ".$nomecliente;
					$data_receita['valor'] = $valor_parcelas_pago;
					$data_receita['valor_pago'] = $valor_parcelas_cartao;
					$data_receita['data_recebido'] = $newData;
					$data_receita['parcela'] = $i;		
					if($i == 1) {
						$data_receita['valor'] = $valor_parcelas_pago + $diferenca_parcela;
						$data_receita['valor_pago'] = $valor_parcelas_cartao + $diferenca_parcela;
					}					
					$data_receita['id_pagamento'] = $id_pagamento;
					self::$db->insert("receita", $data_receita);
				}
			}

			$valor_total = ($pgto_avista) ? $valor_avista : $valor_aprazo;
			$soma_restante = $valor_total - $soma_dinheiro;
			$total_pagar_dinheiro = $valor_total+$acrescimo-$desconto-$soma_restante;
			$troco = $soma_dinheiro - $total_pagar_dinheiro;
			$troco = ($troco<0) ? 0 : $troco;

			$total_financeiro = $this->getTotalFinanceiro($id_venda);
			$data_vendas = array(
				'valor_total' => $valor_total,
				'valor_pago' => $total_financeiro,
				'troco' => $troco
			);
			self::$db->update("vendas", $data_vendas, "id=".$id_venda);
			if (self::$db->affected()) $pagamentoProcessado = true;

			$message = lang('CADASTRO_PAGAMENTO_OK');
			if ($pagamentoProcessado) {
				Filter::msgOk($message, "index.php?do=vendas&acao=finalizarvenda&id=".$id_venda);
			} else Filter::msgAlert(lang('NAOPROCESSADO'));

        } else
        	print Filter::msgStatus();

      }

	  /**
       * Cadastro::processarVendaRapida()
       * 
       * @return
       */
      public function processarVendaRapida()
      {		  		  
		  $kit = 0;
		  $valor = post('valor');	
		  $desconto = converteMoeda(post('valor_desconto'));

		  if (!$desconto)
			$desconto = 0;

		  $valor_pagar = $valor-$desconto;
		  $id_cadastro = sanitize(post('id_cadastro'));
		  $id_tabela = sanitize(post('id_tabela'));  
		  $produtos = (!empty($_POST['id_produto'])) ? $_POST['id_produto'] : null;
		  //$valores = (!empty($_POST['valor_venda'])) ? $_POST['valor_venda'] : 0;
		  $quantidades = (!empty($_POST['quantidade'])) ? $_POST['quantidade'] : 0;
		  $pagamentos = (!empty($_POST['id_pagamento'])) ? $_POST['id_pagamento'] : null;
		  $pagos = (!empty($_POST['valor_pago'])) ? $_POST['valor_pago'] : 0;
		  $parcelas = (!empty($_POST['parcela'])) ? $_POST['parcela'] : 0;
		  $contar_produtos = count($produtos);
		  $contar_pagamentos = count($pagamentos);

		$valor_venda_tabela = (!empty($_POST['valor_venda_tabela'])) ? $_POST['valor_venda_tabela'] : 0;

		  $soma_dinheiro = 0;
		  $soma_sem_dinheiro = 0;
		  $pago = 0;
		  $troco = 0;
		  $percentual_dinheiro = 0;
		  for ($i=0; $i<$contar_pagamentos; $i++) 
		  {
			  $total_parcelas = ($parcelas[$i] == '' or $parcelas[$i] == 0) ? 1 : $parcelas[$i];
			  $id_pagamento = $pagamentos[$i];
			  $row_pagamento = Core::getRowById("tipo_pagamento", $id_pagamento);
			  if($total_parcelas > $row_pagamento->parcelas)
				  Filter::$msgs[$i.'total_parcelas'] = lang('MSG_ERRO_PARCELAS').": ".$row_pagamento->tipo;

			  $pago += $pagos[$i];
			  if($id_pagamento == '1')
				$soma_dinheiro += $pagos[$i];
		  }
		  if($soma_dinheiro > 0) {
			  $soma_restante = $pago - $soma_dinheiro;
			  $total_pagar_dinheiro = $valor_pagar-$soma_restante;
			  $troco = $soma_dinheiro-$total_pagar_dinheiro;
			  $percentual_dinheiro = ($soma_dinheiro-$troco)/$soma_dinheiro;
			  $pago = ($troco < 0 ) ? $pago : $pago-$troco;
		  }

		  if(empty($_POST['id_produto'])) {
			  Filter::$msgs['contar_produtos'] = lang('MSG_ERRO_PRODUTO_VENDA');
		  } elseif ($troco < $soma_dinheiro) {
			  for ($i=0; $i<$contar_produtos; $i++) 
			  {					

				  $quantidade = ($quantidades[$i]) ?  $quantidades[$i] : 1;
				  $quantidade = str_replace(',','',$quantidade);
				  $quantidade = floatval($quantidade);
				  $id_produto = $produtos[$i];
				  $kit = getValue("kit", "produto", "id=" . $id_produto);
				  $valida_estoque = getValue("valida_estoque", "produto", "id=" . $id_produto);

				if($kit) {
					$nomekit = getValue("nome", "produto", "id=" . $id_produto);
					$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade, p.valida_estoque " 
					. "\n FROM produto_kit as k"
					. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
					. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
					. "\n ORDER BY p.nome ";
					$retorno_row = self::$db->fetch_all($sql);
					if($retorno_row) {
						foreach ($retorno_row as $exrow) {
							if($exrow->valida_estoque && ($quantidade*$exrow->quantidade) > $exrow->estoque)
								Filter::$msgs[$i.'estoque'.$exrow->id] = str_replace("[ESTOQUE]", $exrow->estoque, lang('MSG_ERRO_ESTOQUE')." >> PRODUTO DO KIT = ".$exrow->nome);
						}
					}
				}

				if($valida_estoque) {
					  $estoque = getValue("estoque", "produto", "id=" . $id_produto);
					if($quantidade > $estoque)
						Filter::$msgs[$i.'estoque'] = str_replace("[ESTOQUE]", $estoque, lang('MSG_ERRO_ESTOQUE'));
				}

			  }

			  $taxa_desconto = getValue("desconto", "tabela_precos", "id=" . $id_tabela);
			  $desconto_calculado = $valor*($taxa_desconto/100);
			  if($desconto > $desconto_calculado)
				  Filter::$msgs['desconto'] = str_replace("[DESCONTO]", moeda($desconto_calculado), lang('MSG_ERRO_DESCONTO_MAXIMO'));
		  } else {
			  ($soma_dinheiro > 0) ? Filter::$msgs['total_pagamento'] = 'O valor do TROCO deve ser MENOR que o total pago em DINHEIRO.' :  null;
		  }
		  $valor_pagar = round($valor_pagar, 2);
		  $pago = round($pago, 2);

		  // echo "troco[$troco]soma_dinheiro[$soma_dinheiro]valor_pagar[$valor_pagar]pago[$pago]";
		  if($valor_pagar != $pago) {			  
			  $erro_pagamento = str_replace("[VALOR_VENDA]", moeda($valor_pagar), lang('MSG_ERRO_VENDAS_PAGAMENTO_VALOR_DIFERENTE'));
			  $erro_pagamento = str_replace("[VALOR_PAGO]", moeda($pago), $erro_pagamento);
			  Filter::$msgs['valor_pagar'] = $erro_pagamento;
		  }

          if (empty(Filter::$msgs)) {
			  $entrega = (empty($_POST['prazo_entrega'])) ? 0 : 1;
			  $data_venda = array(
				'id_empresa' => session('idempresa'),
				'id_cadastro' => $id_cadastro,
				'id_vendedor' => sanitize(post('id_vendedor')),
				'valor_total' => $valor,
				'valor_desconto' => $desconto,
				'valor_pago' => $pago,
				'data_venda' => "NOW()",
				'pago' => 2,
				'entrega' => $entrega,
				'prazo_entrega' => dataMySQL(post('prazo_entrega')),
				'observacao' => sanitize(post('observacao')),
				'usuario_venda' => session('nomeusuario'),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  $id_venda = self::$db->insert("vendas", $data_venda);

			  $nomecliente = ($id_cadastro) ? getValue("nome", "cadastro", "id=" . $id_cadastro) : "";	
			  $valor_desconto = $desconto/$contar_produtos;
			  for ($i=0; $i<$contar_produtos; $i++) 
			  {					  
				  $quantidade = ($quantidades[$i]) ?  $quantidades[$i] : 1;
				  $quantidade = str_replace(',','',$quantidade);
				  $quantidade = floatval($quantidade);
				  $id_produto = $produtos[$i];
				  $valor_venda = converteMoeda($valor_venda_tabela[$i]);

				  $valor_total = $valor_venda * $quantidade;
				  // $valor_total = $valor_total - $valor_desconto;  -- Retirado o valor do desconto do total do produto. Para não impactar no recibo.
				  $quant_estoque = $quantidade * (-1);

				  $data = array(
					'id_empresa' => session('idempresa'),
					'id_cadastro' => $id_cadastro, 
					'id_venda' => $id_venda, 
					'id_produto' => $id_produto,
					'id_tabela' => $id_tabela,
					'valor' => $valor_venda,
					'quantidade' => $quantidade,
					'valor_desconto' => $valor_desconto,
					'valor_total' => $valor_total,
					'pago' => 2,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				  );
				  $id_cadastro_venda = self::$db->insert("cadastro_vendas", $data);

				  $kit = getValue("kit", "produto", "id=" . $id_produto);
				  if($kit) {
					$nomekit = getValue("nome", "produto", "id=" . $id_produto);
					$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade " 
					. "\n FROM produto_kit as k"
					. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
					. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
					. "\n ORDER BY p.nome ";
					  $retorno_row = self::$db->fetch_all($sql);
					  if($retorno_row) {
						  foreach ($retorno_row as $exrow) {
							  $observacao = str_replace("[ID_VENDA]",$id_venda,lang('VENDA_KIT'));
							  $observacao = str_replace("[NOME_KIT]",$nomekit,$observacao);
							  $observacao = str_replace("[NOME_CLIENTE]",$nomecliente,$observacao);

							  $data_estoque = array(
								'id_produto' => $exrow->id_produto, 
								'quantidade' => $quant_estoque,
								'tipo' => 2, 
								'motivo' => 3,
								'observacao' => $observacao, 
								'id_ref' => $id_cadastro_venda, 
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							  );
							  self::$db->insert("produto_estoque", $data_estoque);
							  $totalestoque = $this->getEstoqueTotal($exrow->id_produto);
							  $data_update = array(
								'estoque' => $totalestoque, 
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							  );
							  self::$db->update("produto", $data_update, "id=".$exrow->id_produto);
						  }
					  }
				  } else {			  
					  $observacao = str_replace("[ID_VENDA]",$id_venda,lang('VENDA_PRODUTO_CLIENTE'));
					  $observacao = str_replace("[NOME_CLIENTE]",$nomecliente,$observacao);

					  $data_estoque = array(
						'id_produto' => $id_produto, 
						'quantidade' => $quant_estoque,
						'tipo' => 2, 
						'motivo' => 3,
						'observacao' => $observacao, 
						'id_ref' => $id_cadastro_venda, 
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					  );
					  self::$db->insert("produto_estoque", $data_estoque);
					  $totalestoque = $this->getEstoqueTotal($id_produto);
					  $data_update = array(
						'estoque' => $totalestoque, 
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					  );
					  self::$db->update("produto", $data_update, "id=".$id_produto);
				  }
			  }
			  $data_vencimento = "NOW()";
			  $valor_total_venda = $valor-$desconto;	  
			  for ($j=0; $j<$contar_pagamentos; $j++) 
			  {
				  $total_parcelas = ($parcelas[$j] == '' or $parcelas[$j] == 0) ? 1 : $parcelas[$j];

				  $tipo = $pagamentos[$j];
				  $id_tipo_categoria = getValue("id_categoria","tipo_pagamento","id=" . $tipo);
				  $valor_pago = $pagos[$j]; 

				  $data = array(
					'id_empresa' => session('idempresa'),
					'id_cadastro' => $id_cadastro, 
					'id_venda' => $id_venda,							  
					'tipo' => $tipo, 
					'valor_total_venda' => $valor_total_venda,
					'total_parcelas' => $total_parcelas,
					'data_pagamento' => "NOW()",
					'pago' => 2,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				  );

				  $data_receita = array(
					'id_empresa' => session('idempresa'),
					'id_cadastro' => $id_cadastro, 
					'id_venda' => $id_venda,
					'id_conta' => 19, 
					'tipo' => $tipo, 
					'data_pagamento' => "NOW()",
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				  );

				  $row_cartoes = Core::getRowById("tipo_pagamento", $tipo);
				  $dias = $row_cartoes->dias;
				  $taxa = $row_cartoes->taxa;
				  $id_banco = (empty($_POST['id_banco'])) ? $row_cartoes->id_banco : post('id_banco');

				  if($id_tipo_categoria == '1') {				
					$valor_pago = $valor_pago*$percentual_dinheiro;
					$data['valor_pago'] = $valor_pago;
					$data['data_vencimento'] = $data_vencimento;

					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);

				  } elseif($id_tipo_categoria == '2' or $id_tipo_categoria == '4') {

					$descricao = ($id_tipo_categoria == '2') ? "CHEQUE" : $row_cartoes->tipo;
					$data['valor_pago'] = $valor_pago;
					$data['id_banco'] = $id_banco;
					$data['nome_cheque'] = sanitize(post('nome_cheque'));
					$data['banco_cheque'] = sanitize(post('banco_cheque'));
					$data['numero_cheque'] = sanitize(post('numero_cheque'));
					$data['data_vencimento'] = $data_vencimento;

					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
					$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
					$data_parcela = explode('/', $data_temp);
					$valor_cheque = round($valor_pago/$total_parcelas, 2);
					$diferenca = $valor_pago - ($valor_cheque*$total_parcelas);
					for($i=0;$i<$total_parcelas;$i++)
					{
						$newData = novadata($data_parcela[1] + $i, $data_parcela[0], $data_parcela[2]);		
						$parc = ($i+1);
						$p = $parc."/".$total_parcelas;		
						$data_receita['id_banco'] = $id_banco;	
						$data_receita['descricao'] = "$descricao - $p - ".$nomecliente;
						$data_receita['valor'] = $valor_cheque;
						$data_receita['valor_pago'] = $valor_cheque;
						$data_receita['id_pagamento'] = $id_pagamento;
						$data_receita['data_pagamento'] = $newData;
						$data_receita['parcela'] = $parc;
						$data_receita['pago'] = 2;
						if($i == 0) {
							$data_receita['valor'] = $valor_cheque + $diferenca;
							$data_receita['valor_pago'] = $valor_cheque + $diferenca;
						}
						self::$db->insert("receita", $data_receita);
					}	
				  } elseif($id_tipo_categoria == '3' or $id_tipo_categoria == '6') {

					$data['id_banco'] = $id_banco;
					$data['valor_pago'] = $valor_pago;
					$data['data_vencimento'] = $data_vencimento;

					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);

					$data_receita['id_banco'] = $id_banco;	
					$data_receita['descricao'] = $row_cartoes->tipo." - ".$nomecliente;
					$data_receita['valor'] = $valor_pago;
					$data_receita['valor_pago'] = $valor_pago;
					$data_receita['id_pagamento'] = $id_pagamento;
					$data_receita['data_recebido'] = $data_vencimento;
					$data_receita['parcela'] = 1;
					$data_receita['pago'] = 2;
					self::$db->insert("receita", $data_receita);

				  } elseif ($id_tipo_categoria =='9') { //Pagamento no Crediário
					
				$data['pago'] = 2;
				$data_receita['pago'] = 2;
				$descricao = $descricao_pagamento;
				$data['valor_pago'] = $valor_pago;
				$data['id_banco'] = $id_banco;
				$data['nome_cheque'] = sanitize(post('nome_cheque'));
				$data['banco_cheque'] = sanitize(post('banco_cheque'));
				$data['numero_cheque'] = sanitize(post('numero_cheque'));
				$data['data_vencimento'] = $data_vencimento;
				$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
				
				$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
				$data_parcela = explode('/', $data_temp);
				$valor_cheque = round($valor_pago/$total_parcelas, 2);
				$diferenca = $valor_pago - ($valor_cheque*$total_parcelas);
				  
				for($i=0;$i<$total_parcelas;$i++)
				{
					if($dias==30 || $dias==0 || empty($dias)) {
						$newData = novadata($data_parcela[1] + ($i+1), $data_parcela[0], $data_parcela[2]);	
					} else {
						$newData = novadata($data_parcela[1], $data_parcela[0] + (($i+1)*$dias), $data_parcela[2]);	
					}	

					$parc = ($i+1);
					$p = $parc."/".$total_parcelas;		
					$data_receita['id_banco'] = $id_banco;	
					$data_receita['descricao'] = "$descricao - $p - ".$nomecliente;
					$data_receita['valor'] = $valor_cheque;
					$data_receita['valor_pago'] = $valor_cheque;
					$data_receita['id_pagamento'] = $id_pagamento;
					$data_receita['data_pagamento'] = $newData;
					$data_receita['parcela'] = $parc;
					$data_receita['promissoria'] = 1;
					if($i == 0) {
						$data_receita['valor'] = $valor_cheque + $diferenca;
						$data_receita['valor_pago'] = $valor_cheque + $diferenca;
					}
					self::$db->insert("receita", $data_receita);
				}

			} else {

					$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
					$data_parcela = explode('/', $data_temp);
					$data_receita['pago'] = 2;		
					$valor_taxa = $valor_pago*$taxa/100;
					$valor_cartao = $valor_pago - $valor_taxa;
					$valor_parcelas_pago = round($valor_pago/$total_parcelas, 2);
					$valor_parcelas_cartao = $valor_cartao/$total_parcelas;
					$diferenca = $valor_pago - ($valor_parcelas_pago * $total_parcelas);
					$diferenca_parcela = $valor_cartao - ($valor_parcelas_cartao * $total_parcelas);
					$data['id_banco'] = $id_banco;
					$data['valor_pago'] = $valor_pago;
					$data['valor_total_cartao'] = $valor_cartao;
					$data['valor_parcelas_cartao'] = $valor_parcelas_cartao;
					$data['parcelas_cartao'] = $total_parcelas;
					$data['data_vencimento'] = $data_vencimento;
					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
					for($i=1;$i<$total_parcelas+1;$i++)
					{
						if($dias == 30) {
							$m = $i - 1;
							$newData = novadata($data_parcela[1] + $m, $data_parcela[0], $data_parcela[2]);	
						} else {
							$newData = novadata($data_parcela[1], $data_parcela[0] + ($i*$dias), $data_parcela[2]);	
						}			
						$p = $i."/".$total_parcelas;	
						$data_receita['id_banco'] = $id_banco;	
						$data_receita['descricao'] = $row_cartoes->tipo." - $p - ".$nomecliente;
						$data_receita['valor'] = $valor_parcelas_pago;
						$data_receita['valor_pago'] = $valor_parcelas_cartao;
						$data_receita['data_recebido'] = $newData;
						$data_receita['parcela'] = $i;		
						if($i == 1) {
							$data_receita['valor'] = $valor_parcelas_pago + $diferenca_parcela;
							$data_receita['valor_pago'] = $valor_parcelas_cartao + $diferenca_parcela;
						}					
						$data_receita['id_pagamento'] = $id_pagamento;
						self::$db->insert("receita", $data_receita);
					}
				  }
			  }

			  $message = lang('CADASTRO_VENDA_OK');
			  $redirecionar = "index.php?do=cadastro&acao=vendarapida&id_venda=".$id_venda;
			  if (self::$db->affected()) {
					Filter::msgOkRecibo($message, $redirecionar, $id_venda);
			  } else Filter::msgAlert(lang('NAOPROCESSADO'));
              
          } else
              print Filter::msgStatus();
      }

	  /**
	   * Cadastro::obterDescontosVenda()
	   *
       * @return
       */
	  public function obterDescontosVenda($id_venda, $id_caixa = 0, $id_empresa = 0, $id_cadastro = 0)
      {
          $sql = "SELECT SUM(valor_desconto) as vlr_desc, id, valor_desconto "
		    . "\n FROM cadastro_vendas "
			. "\n where id_venda = '$id_venda' AND id_empresa = '$id_empresa' AND id_cadastro = '$id_cadastro' AND inativo = 0";
          $row = self::$db->first($sql);
          return ($row) ? $row : 0;
      }

	  /**
	   * Cadastro::obterAcrescimoVenda()
	   *
       * @return
       */
	  public function obterAcrescimoVenda($id_venda, $id_caixa = 0, $id_empresa = 0, $id_cadastro = 0)
      {
          $sql = "SELECT SUM(valor_despesa_acessoria) as vlr_acrescimo, id, valor_despesa_acessoria "
		    . "\n FROM cadastro_vendas "
			. "\n where id_venda = '$id_venda' AND id_empresa = '$id_empresa' AND id_cadastro = '$id_cadastro' AND inativo = 0";
          $row = self::$db->first($sql);
          return ($row) ? $row : 0;
      }

	  /**
       * Cadastro::processarNovaVenda()
       * 
       * @return
       */
      public function processarNovaVenda($id_caixa = 0)
      {
		  $kit = 0;
		  $valor = post('valor');	
		  
		  $id_empresa = (empty($_POST['id_empresa'])) ? session('idempresa') : post('id_empresa');	
		  $venda_fiscal = (empty($_POST['venda_fiscal'])) ? false : true;
		  $desconto = converteMoeda(post('valor_desconto'));
		  $acrescimo = converteMoeda(post('valor_acrescimo'));

		  if (!$desconto)
			$desconto = 0;
		  if (!$acrescimo)
			$acrescimo = 0;	
		  if (!$valor)
			$valor = 0;

		  $valor_pagar = round($valor,2)+round($acrescimo,2)-round($desconto,2);	

		  $id_cadastro = sanitize(post('id_cadastro'));
		  $id_tabela = sanitize(post('id_tabela'));  
		  $produtos = (!empty($_POST['id_produto'])) ? $_POST['id_produto'] : null;
		  //$valores = (!empty($_POST['valor_venda'])) ? $_POST['valor_venda'] : 0;
		  $quantidades = (!empty($_POST['quantidade'])) ? $_POST['quantidade'] : 0;
		  $pagamentos = (!empty($_POST['id_pagamento'])) ? $_POST['id_pagamento'] : null;
		  $pagos = (!empty($_POST['valor_pago'])) ? $_POST['valor_pago'] : 0;
		  $parcelas = (!empty($_POST['parcela'])) ? $_POST['parcela'] : 0;
		  $contar_produtos = (is_array($produtos)) ? count($produtos) : 0;
		  $contar_pagamentos = (is_array($pagamentos)) ? count($pagamentos) : 0;

		$pgto_avista = 1;
		for ($j=0; $j<$contar_pagamentos; $j++) 
		{
			$tipo = $pagamentos[$j];
			$tipo_pagamento_avista = getValue("avista","tipo_pagamento","id=".$tipo);
			$pgto_avista *= intval($tipo_pagamento_avista);
		}	

		$valor_venda_tabela = (!empty($_POST['valor_venda_tabela'])) ? $_POST['valor_venda_tabela'] : 0;

		  if ($contar_produtos==0)
			  Filter::$msgs['contar_produtos'] = lang('MSG_ERRO_PRODUTO_VENDA');

		  if ($contar_pagamentos==0)
			Filter::$msgs['contar_pagamentos'] = lang('MSG_ERRO_PAGAMENTO_VENDA');

			if ($contar_produtos>0) {
				for ($i=0; $i<$contar_produtos; $i++) 
				{	
					$quantidade = $quantidades[$i];
					$quantidade = str_replace(',','',$quantidade);
					$quantidade = floatval($quantidade);
					if ($quantidade && $quantidade>0) {
						$valor_venda = converteMoeda($valor_venda_tabela[$i]);	  
						$valor_total = round($valor_venda*$quantidade,2);
						if ($valor_total<=0) {
							$nome_produto = getValue("nome","produto","id=".$produtos[$i]);
							Filter::$msgs['qtde_produtos'.$i] = str_replace("[nome_prod]", $nome_produto, lang('MSG_ERRO_PRODUTO_VALOR'));
						}
					} else {
						$nome_produto = getValue("nome","produto","id=".$produtos[$i]);
						Filter::$msgs['qtde_produtos'.$i] = str_replace("[nome_prod]", $nome_produto, lang('MSG_ERRO_PRODUTO_QUANTIDADE'));
					}			
				}
			}

		  $soma_dinheiro = 0;
		  $soma_crediario = 0;
		  $pago = 0;
		  $troco = 0;
		  $percentual_dinheiro = 0;
		  $existe_pagamento_crediario = false;

		  for ($i=0; $i<$contar_pagamentos; $i++)
		  {
			  $total_parcelas = ($parcelas[$i] == '' or $parcelas[$i] == 0) ? 1 : $parcelas[$i];
			  $id_pagamento = $pagamentos[$i];
			  $row_pagamento = Core::getRowById("tipo_pagamento", $id_pagamento);
			  $id_pagamento_categoria = $row_pagamento->id_categoria;

			  if ($id_pagamento_categoria==9) {
			  	$existe_pagamento_crediario = true;
				$soma_crediario += $pagos[$i];
			  }

			  if($total_parcelas > $row_pagamento->parcelas)
				  Filter::$msgs[$i.'total_parcelas'] = lang('MSG_ERRO_PARCELAS').": ".$row_pagamento->tipo;

			  $pago += $pagos[$i];
			  if($id_pagamento_categoria == '1')
				$soma_dinheiro += $pagos[$i];
		  }

		if ($existe_pagamento_crediario) {
			if ($id_cadastro) {
				$valor_crediario = $this->getTotalCrediario($id_cadastro);
				if ($soma_crediario > $valor_crediario) {
					Filter::$msgs['pagamento_crediario_sem_limite'] = lang('MSG_ERRO_CREDIARIO');	
				}
			} else {
				Filter::$msgs['pagamento_crediario_sem_cadastro'] = lang('MSG_ERRO_CREDIARIO_CLIENTE');			
			}
		}

		  if($soma_dinheiro > 0) {
			  $soma_restante = $pago - $soma_dinheiro;
			  $total_pagar_dinheiro = $valor_pagar-$soma_restante;
			  $troco = $soma_dinheiro-$total_pagar_dinheiro;
			  $percentual_dinheiro = ($soma_dinheiro-$troco)/$soma_dinheiro;
			  $pago = ($troco < 0 ) ? $pago : $pago-$troco;
		  }

		  if(empty($_POST['id_produto'])) {
			  Filter::$msgs['contar_produtos'] = lang('MSG_ERRO_PRODUTO_VENDA');
		  } elseif ($troco < $soma_dinheiro) {
			$qtdtotal = 0;
			for ($i=0; $i<$contar_produtos; $i++) 
			{
				  $quantidade = ($quantidades[$i]) ?  $quantidades[$i] : 1;
				  $quantidade = str_replace(',','',$quantidade);
				  $quantidade = floatval($quantidade);
				  $qtdtotal += $quantidade;
				  $id_produto = $produtos[$i];
				  $kit = getValue("kit", "produto", "id=" . $id_produto);
				  $valida_estoque = getValue("valida_estoque", "produto", "id=" . $id_produto);

				if($kit) {
					$nomekit = getValue("nome", "produto", "id=" . $id_produto);
					$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade, p.valida_estoque " 
					. "\n FROM produto_kit as k"
					. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
					. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
					. "\n ORDER BY p.nome ";
					$retorno_row = self::$db->fetch_all($sql);
					if($retorno_row) {
						foreach ($retorno_row as $exrow) {
							if($exrow->valida_estoque && ($quantidade*$exrow->quantidade) > $exrow->estoque)
								Filter::$msgs[$i.'estoque'.$exrow->id] = str_replace("[ESTOQUE]", $exrow->estoque, lang('MSG_ERRO_ESTOQUE')." >> PRODUTO DO KIT = ".$exrow->nome);
						}
					}
				}

				if($valida_estoque) {
			  		$estoque = getValue("estoque", "produto", "id=" . $id_produto);
					if($quantidade > $estoque)
						Filter::$msgs[$i.'estoque'] = str_replace("[ESTOQUE]", $estoque, lang('MSG_ERRO_ESTOQUE'));
				}
			}

			  $sql = "SELECT (quantidade)"
			  . "\n FROM tabela_precos"
			  . "\n WHERE id = $id_tabela";
			  $retorno = self::$db->first($sql);

			  if($qtdtotal < $retorno->quantidade ) {
				Filter::$msgs['quantidade'] = str_replace("[QUANTIDADE]", $quantidade, lang('MSG_ERRO_QTDMINIMA'));
			  }

			  $taxa_desconto = getValue("desconto", "tabela_precos", "id=" . $id_tabela);
			  $desconto_calculado = $valor*($taxa_desconto/100);
			  if($desconto > $desconto_calculado)
				  Filter::$msgs['desconto'] = str_replace("[DESCONTO]", moeda($desconto_calculado), lang('MSG_ERRO_DESCONTO_MAXIMO'));
		  } else {
			  ($soma_dinheiro > 0) ? Filter::$msgs['total_pagamento'] = 'O valor do TROCO deve ser MENOR que o total pago em DINHEIRO.' :  null;
		  }

		  $qtdtotal = 0;
		  for ($i=0; $i<$contar_produtos; $i++) 
		  {					
			  $quantidade = ($quantidades[$i]) ?  $quantidades[$i] : 1;
			  $quantidade = str_replace(',','',$quantidade);
			  $quantidade = floatval($quantidade);
			  $qtdtotal += $quantidade;
			  $id_produto = $produtos[$i];
			  $kit = getValue("kit", "produto", "id=" . $id_produto);
			  $valida_estoque = getValue("valida_estoque", "produto", "id=" . $id_produto);

			if($kit) {
				$nomekit = getValue("nome", "produto", "id=" . $id_produto);
				$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade, p.valida_estoque " 
				. "\n FROM produto_kit as k"
				. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
				. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
				. "\n ORDER BY p.nome ";
				$retorno_row = self::$db->fetch_all($sql);
				if($retorno_row) {
					foreach ($retorno_row as $exrow) {
						if($exrow->valida_estoque && ($quantidade*$exrow->quantidade) > $exrow->estoque)
							Filter::$msgs[$i.'estoque'.$exrow->id] = str_replace("[ESTOQUE]", $exrow->estoque, lang('MSG_ERRO_ESTOQUE')." >> PRODUTO DO KIT = ".$exrow->nome);
					}
				}
			}

			if($valida_estoque) {
				  $estoque = getValue("estoque", "produto", "id=" . $id_produto);
				if($quantidade > $estoque)
					Filter::$msgs[$i.'estoque'] = str_replace("[ESTOQUE]", $estoque, lang('MSG_ERRO_ESTOQUE'));
			}
		  }

		  $sql = "SELECT (quantidade)"
			  . "\n FROM tabela_precos"
			  . "\n WHERE id = $id_tabela";
			  $retorno = self::$db->first($sql);

			  if($qtdtotal < $retorno->quantidade ) {
				Filter::$msgs['quantidade'] = str_replace("[QUANTIDADE]", $quantidade, lang('MSG_ERRO_QTDMINIMA'));
			  }

		  $taxa_desconto = getValue("desconto", "tabela_precos", "id=" . $id_tabela);
		  $desconto_calculado = $valor*($taxa_desconto/100);			
		  if($desconto > $desconto_calculado)
				Filter::$msgs['desconto'] = str_replace("[DESCONTO]", moeda($desconto_calculado), lang('MSG_ERRO_DESCONTO_MAXIMO'));

		  $valor_pagar = round($valor_pagar, 2);
		  $pago = round($pago, 2);

		  if($valor_pagar != $pago) {			  
			  $erro_pagamento = str_replace("[VALOR_VENDA]", moeda($valor_pagar), lang('MSG_ERRO_VENDAS_PAGAMENTO_VALOR_DIFERENTE'));
			  $erro_pagamento = str_replace("[VALOR_PAGO]", moeda($pago), $erro_pagamento);
			  Filter::$msgs['valor_pagar'] = $erro_pagamento;
		  }

          if (empty(Filter::$msgs)) {
			  if(empty($_POST['id_cadastro']) and !empty($_POST['cadastro'])) {
				  $data_cadastro = array(
					'nome' => cleanSanitize(post('cadastro')),
					'celular' => sanitize(post('celular')),
					'cliente' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				  );
				  $id_cadastro = self::$db->insert("cadastro", $data_cadastro);
			  } else if (!empty($_POST['id_cadastro']) && !empty($_POST['celular'])) {
				$data_cadastro = array(
					'celular' => sanitize(post('celular')),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update("cadastro", $data_cadastro, "id=".$id_cadastro);
			  }

			  $entrega = (empty($_POST['prazo_entrega'])) ? 0 : 1;
			  $status_entrega = ($entrega==1) ? 1 : 9 ;

			  $data_venda = array(
				'id_empresa' => $id_empresa,
				'id_cadastro' => $id_cadastro,
				'id_caixa' => $id_caixa,
				'id_vendedor' => sanitize(post('id_vendedor')),
				'valor_total' => $valor,
				'valor_desconto' => round($desconto, 2),
				'valor_despesa_acessoria' => $acrescimo,
				'valor_pago' => $pago + $troco,
				'entrega' => $entrega,
				'status_entrega' => $status_entrega,
				'troco' => $troco,
				'data_venda' => "NOW()",
				'prazo_entrega' => dataMySQL(post('prazo_entrega')),
				'observacao' => sanitize(post('observacao')),
				'usuario_venda' => session('nomeusuario'),
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  if (empty($_POST['salvar'])) {
				  $data_venda['pago'] = 1;
				  $data_venda['usuario_pagamento'] = session('nomeusuario');
			  } else {
				  $data_venda['pago'] = 2;
			  }
			  $id_venda = self::$db->insert("vendas", $data_venda);
	 
			  $nomecliente = ($id_cadastro) ? getValue("nome", "cadastro", "id=" . $id_cadastro) : "";	
			  
			  $porcentagem_desconto = ($desconto*100)/$valor;
			  
			  $soma_valor_acrescimo = 0;
			  $soma_valor_desconto = 0;
			  $valor_acrescimo_produto = round(($acrescimo/$contar_produtos),2);
			  
			  for ($i=0; $i<$contar_produtos; $i++) 
			  {					  
				  $quantidade = ($quantidades[$i]) ?  $quantidades[$i] : 1;
				  $quantidade = str_replace(',','',$quantidade);
				  $quantidade = floatval($quantidade);
				  $id_produto = $produtos[$i];
				  $valor_venda = converteMoeda($valor_venda_tabela[$i]);
	  
				  $valor_total = $valor_venda * $quantidade;
				  $valor_total = round($valor_total,2);
				  $quant_estoque = $quantidade * (-1);

				  $valor_desconto = ($porcentagem_desconto*$valor_total)/100;
				  $valor_desconto = round($valor_desconto,2);
				  
				  if (($soma_valor_acrescimo+$valor_acrescimo_produto) > $acrescimo) {
					$valor_acrescimo_produto = $acrescimo - $soma_valor_acrescimo;
				  }
				  $soma_valor_acrescimo += $valor_acrescimo_produto;
				  
				  if (($soma_valor_desconto+$valor_desconto) > $desconto) {
					$valor_desconto = $desconto - $soma_valor_desconto;
				  }
				  $soma_valor_desconto += $valor_desconto;

				  $valor_avista = getValue("valor_avista","produto","id=".$id_produto);
				  $pagamentoAVista = ($pgto_avista==1) ? true : false;

				  if ($pagamentoAVista && $valor_avista>0) {
					$valor_original = $valor_avista;
				  } else {
				  	$valor_original = getValue("valor_venda","produto_tabela","id_tabela=".$id_tabela." AND id_produto=".$id_produto);
				  }
				  
				  $data_cadastro_venda = array(
					'id_empresa' => $id_empresa,
					'id_cadastro' => $id_cadastro, 
					'id_caixa' => $id_caixa,
					'id_venda' => $id_venda, 
					'id_produto' => $id_produto,
					'id_tabela' => $id_tabela,
					'valor_original' => $valor_original,
					'valor' => $valor_venda,
					'quantidade' => $quantidade,
					'valor_despesa_acessoria' => $valor_acrescimo_produto,
					'valor_desconto' => round($valor_desconto, 2),
					'valor_total' => $valor_total,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				  );
				  if (empty($_POST['salvar'])) {
					  $data_cadastro_venda['pago'] = 1;
				  } else {
					  $data_cadastro_venda['pago'] = 2;
				  }
				  $id_cadastro_venda = self::$db->insert("cadastro_vendas", $data_cadastro_venda);
	  
				  $kit = getValue("kit", "produto", "id=" . $id_produto);
				  if($kit) {
					  $nomekit = getValue("nome", "produto", "id=" . $id_produto);

					  $sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade " 
						. "\n FROM produto_kit as k"
						. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
						. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
						. "\n ORDER BY p.nome ";

					  $retorno_row = self::$db->fetch_all($sql);
					  if($retorno_row) {
						  foreach ($retorno_row as $exrow) {
							  $observacao = str_replace("[ID_VENDA]",$id_venda,lang('VENDA_KIT'));
							  $observacao = str_replace("[NOME_KIT]",$nomekit,$observacao);
							  $observacao = str_replace("[NOME_CLIENTE]",$nomecliente,$observacao);
							  
							  $quant_estoque_kit = $quantidade * $exrow->quantidade * (-1);
							  
							  $data_estoque = array(
								'id_empresa' => $id_empresa,
								'id_produto' => $exrow->id_produto, 
								'quantidade' => $quant_estoque_kit,
								'tipo' => 2, 
								'motivo' => 3,
								'observacao' => $observacao, 
								'id_ref' => $id_cadastro_venda, 
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							  );
							  self::$db->insert("produto_estoque", $data_estoque);
							  $totalestoque = $this->getEstoqueTotal($exrow->id_produto);
							  $data_update = array(
								'estoque' => $totalestoque, 
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							  );
							  self::$db->update("produto", $data_update, "id=".$exrow->id_produto);
						  }
					  }  

						   $observacao = str_replace("[ID_VENDA]",$id_venda,lang('VENDA_PRODUTO_CLIENTE'));
						   $observacao = str_replace("[NOME_CLIENTE]",$nomecliente,$observacao);
                      
						   $data_estoque = array(
							   'id_empresa' => $id_empresa,
							   'id_produto' => $id_produto, 
							   'quantidade' => $quant_estoque,
							   'tipo' => 2, 
							   'motivo' => 3,
							   'observacao' => $observacao, 
							   'id_ref' => $id_cadastro_venda, 
							   'usuario' => session('nomeusuario'),
							   'data' => "NOW()"
							);
							self::$db->insert("produto_estoque", $data_estoque);
							$totalestoque = $this->getEstoqueTotal($id_produto);
							$data_update = array(
							   'estoque' => $totalestoque, 
							   'usuario' => session('nomeusuario'),
							   'data' => "NOW()"
							);
						   self::$db->update("produto", $data_update, "id=".$id_produto);
						  
					  
				  } else {			  
					  $observacao = str_replace("[ID_VENDA]",$id_venda,lang('VENDA_PRODUTO_CLIENTE'));
					  $observacao = str_replace("[NOME_CLIENTE]",$nomecliente,$observacao);
					  
					  $data_estoque = array(
						'id_empresa' => $id_empresa,
						'id_produto' => $id_produto, 
						'quantidade' => $quant_estoque,
						'tipo' => 2, 
						'motivo' => 3,
						'observacao' => $observacao, 
						'id_ref' => $id_cadastro_venda, 
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					  );
					  self::$db->insert("produto_estoque", $data_estoque);
					  $totalestoque = $this->getEstoqueTotal($id_produto);
					  $data_update = array(
						'estoque' => $totalestoque, 
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					  );
					  self::$db->update("produto", $data_update, "id=".$id_produto);
				  }
			  }

			  ///////////////////////////////////
			  //Este trecho de código serve para ajustar o valor do desconto quando o mesmo for quebrado e gerar diferença no total.
			  $novo_valor_desconto = $this->obterDescontosVenda($id_venda, $id_caixa, $id_empresa, $id_cadastro);
			  $desconto_venda = round(getValue("valor_desconto","vendas","id=".$id_venda),2);

			  if (round($novo_valor_desconto->vlr_desc,2) != $desconto_venda){
				  $novo_desconto = ($desconto - round($novo_valor_desconto->vlr_desc,2)) + $novo_valor_desconto->valor_desconto;
				  $data_desconto = array( 'valor_desconto' => $novo_desconto );
				  self::$db->update("cadastro_vendas", $data_desconto, "id=".$novo_valor_desconto->id);
			  }
			  ///////////////////////////////////
			  ///////////////////////////////////
			  //Este trecho de código serve para ajustar o valor do acréscimo quando o mesmo for quebrado e gerar diferença no total.
			  
			  $novo_valor_acrescimo = $this->obterAcrescimoVenda($id_venda, $id_caixa, $id_empresa, $id_cadastro);
			  if (round($novo_valor_acrescimo->vlr_acrescimo,2) != round($acrescimo,2)){
				  $novo_acrescimo = ($acrescimo - round($novo_valor_acrescimo->vlr_acrescimo,2)) + $novo_valor_acrescimo->valor_despesa_acessoria;
				  $data_acrescimo = array( 'valor_despesa_acessoria' => $novo_acrescimo );
				  self::$db->update("cadastro_vendas", $data_acrescimo, "id=".$novo_valor_acrescimo->id);
			  }
			  ///////////////////////////////////
			  
			  $data_vencimento = "NOW()";
			  $valor_total_venda = $valor+$acrescimo-$desconto;
			  
			for ($j=0; $j<$contar_pagamentos; $j++) 
			{
				$total_parcelas = ($parcelas[$j] == '' or $parcelas[$j] == 0) ? 1 : $parcelas[$j];
				  
				$tipo = $pagamentos[$j];
				$id_tipo_categoria = getValue("id_categoria","tipo_pagamento","id=" . $tipo);
				$valor_pago = $pagos[$j]; 

				$data = array(
					'id_empresa' => $id_empresa,
					'id_cadastro' => $id_cadastro, 
					'id_caixa' => $id_caixa,
					'id_venda' => $id_venda,							  
					'tipo' => $tipo, 
					'valor_total_venda' => $valor_total_venda,
					'total_parcelas' => $total_parcelas,
					'data_pagamento' => "NOW()",
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				  
				$data_receita = array(
					'id_empresa' => $id_empresa,
					'id_cadastro' => $id_cadastro, 
					'id_caixa' => $id_caixa,
					'id_venda' => $id_venda,
					'id_conta' => 19, 
					'tipo' => $tipo, 
					'data_pagamento' => "NOW()",
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);

				$row_cartoes = Core::getRowById("tipo_pagamento", $tipo);
				$dias = $row_cartoes->dias;
				$taxa = $row_cartoes->taxa;
				$descricao_pagamento = $row_cartoes->tipo;
				$id_banco = (empty($_POST['id_banco'])) ? $row_cartoes->id_banco : post('id_banco');
				  
				if($id_tipo_categoria == '1') {

					if (empty($_POST['salvar'])) {
						$data['pago'] = 1;
						$data['data_pagamento'] = "NOW()";
					} else {
						$data['pago'] = 2;
					}					
					$valor_pago = $valor_pago*$percentual_dinheiro;
					$data['valor_pago'] = $valor_pago;
					$data['data_vencimento'] = $data_vencimento;					 
					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
	 
				} elseif($id_tipo_categoria == '2' or $id_tipo_categoria == '4') {	 					
					if (empty($_POST['salvar'])) {
						$data['pago'] = 1;
						$data['data_pagamento'] = "NOW()";
						$data_receita['pago'] = 0;
					} else {
						$data['pago'] = 2;
						$data_receita['pago'] = 2;
					}					
					$descricao = $descricao_pagamento;
					$data['valor_pago'] = $valor_pago;
					$data['id_banco'] = $id_banco;
					$data['nome_cheque'] = sanitize(post('nome_cheque'));
					$data['banco_cheque'] = sanitize(post('banco_cheque'));
					$data['numero_cheque'] = sanitize(post('numero_cheque'));
					$data['data_vencimento'] = $data_vencimento;
						 
					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
					
					if ($id_tipo_categoria==4){
						$data_temp = (empty($_POST['data_boleto'])) ? somarData(date('d/m/Y'), 3, 0, 0) : sanitize($_POST['data_boleto']);
					}
					else {
						$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
					}
					
					$data_parcela = explode('/', $data_temp);
					$valor_cheque = round($valor_pago/$total_parcelas, 2);
					$diferenca = $valor_pago - ($valor_cheque*$total_parcelas);
					
					for($i=0;$i<$total_parcelas;$i++)
					{
						$newData = novadata($data_parcela[1] + $i, $data_parcela[0], $data_parcela[2]);		
						$parc = ($i+1);
						$p = $parc."/".$total_parcelas;		
						$data_receita['id_banco'] = $id_banco;	
						$data_receita['descricao'] = "$descricao - $p - ".$nomecliente;
						$data_receita['valor'] = $valor_cheque;
						$data_receita['valor_pago'] = $valor_cheque;
						$data_receita['id_pagamento'] = $id_pagamento;
						$data_receita['data_pagamento'] = $newData;
						$data_receita['parcela'] = $parc;
						if($i == 0) {
							$data_receita['valor'] = $valor_cheque + $diferenca;
							$data_receita['valor_pago'] = $valor_cheque + $diferenca;
						}
						self::$db->insert("receita", $data_receita);
					}	
				} elseif($id_tipo_categoria == '3' or $id_tipo_categoria == '6') {
					if (empty($_POST['salvar'])) {
					  	$data['pago'] = 1;
					  	$data['data_pagamento'] = "NOW()";
						$data_receita['pago'] = 1;
					} else {
						$data['pago'] = 2;
						$data_receita['pago'] = 2;
					}			
					$data['id_banco'] = $id_banco;			
					$data['valor_pago'] = $valor_pago;
					$data['data_vencimento'] = $data_vencimento;
	 
					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
	 
					$data_receita['id_banco'] = $id_banco;	
					$data_receita['descricao'] = $descricao_pagamento." - ".$nomecliente;
					$data_receita['valor'] = $valor_pago;
					$data_receita['valor_pago'] = $valor_pago;
					$data_receita['id_pagamento'] = $id_pagamento;
					$data_receita['data_recebido'] = $data_vencimento;
					$data_receita['parcela'] = 1;
					self::$db->insert("receita", $data_receita);
	 				  
				} elseif ($id_tipo_categoria=='9') { //Pagamento no Crediário
					
					$data['pago'] = 2;
					$data_receita['pago'] = 2;
					  			
					$descricao = $descricao_pagamento;
					$data['valor_pago'] = $valor_pago;
					$data['id_banco'] = $id_banco;
					$data['nome_cheque'] = sanitize(post('nome_cheque'));
					$data['banco_cheque'] = sanitize(post('banco_cheque'));
					$data['numero_cheque'] = sanitize(post('numero_cheque'));
					$data['data_vencimento'] = $data_vencimento;
					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
					
					$data_temp = (empty($_POST['data_boleto'])) ? date('d/m/Y') : sanitize($_POST['data_boleto']);
					$data_parcela = explode('/', $data_temp);
					$valor_cheque = round($valor_pago/$total_parcelas, 2);
					$diferenca = $valor_pago - ($valor_cheque*$total_parcelas);
					$somador_data = 1; 
					for($i=0;$i<$total_parcelas;$i++)
					{
						if (!empty($_POST['data_boleto']) && $i==0) {
							$newData = novadata($data_parcela[1], $data_parcela[0], $data_parcela[2]);
							$somador_data = 0;
						} else if($dias==30 || $dias==0 || empty($dias)) {
							$newData = novadata($data_parcela[1] + ($i+$somador_data), $data_parcela[0], $data_parcela[2]);	
						} else {
							$newData = novadata($data_parcela[1], $data_parcela[0] + (($i+$somador_data)*$dias), $data_parcela[2]);	
						}	

						$parc = ($i+1);
						$p = $parc."/".$total_parcelas;		
						$data_receita['id_banco'] = $id_banco;	
						$data_receita['descricao'] = "$descricao - $p - ".$nomecliente;
						$data_receita['valor'] = $valor_cheque;
						$data_receita['valor_pago'] = $valor_cheque;
						$data_receita['id_pagamento'] = $id_pagamento;
						$data_receita['data_pagamento'] = $newData;
						$data_receita['parcela'] = $parc;
						$data_receita['promissoria'] = 1;
						if($i == 0) {
							$data_receita['valor'] = $valor_cheque + $diferenca;
							$data_receita['valor_pago'] = $valor_cheque + $diferenca;
						}
						self::$db->insert("receita", $data_receita);
					}

				} else {

					if (empty($_POST['salvar'])) {
						$data['pago'] = 1;
						$data['data_pagamento'] = "NOW()";
						$data_receita['pago'] = 1;
					} else {
						$data['pago'] = 2;
						$data_receita['pago'] = 2;
					}
	 
					$data_temp = (empty($_POST['data_vencimento'])) ? date('d/m/Y') : sanitize($_POST['data_vencimento']);
					$data_parcela = explode('/', $data_temp);
					$valor_taxa = $valor_pago*$taxa/100;
					$valor_cartao = $valor_pago - $valor_taxa;
					$valor_parcelas_pago = round($valor_pago/$total_parcelas, 2);
					$valor_parcelas_cartao = $valor_cartao/$total_parcelas;
					$diferenca = $valor_pago - ($valor_parcelas_pago * $total_parcelas);
					$diferenca_parcela = $valor_cartao - ($valor_parcelas_cartao * $total_parcelas);
					$data['id_banco'] = $id_banco;
					$data['valor_pago'] = $valor_pago;
					$data['valor_total_cartao'] = $valor_cartao;
					$data['valor_parcelas_cartao'] = $valor_parcelas_cartao;
					$data['parcelas_cartao'] = $total_parcelas;
					$data['data_vencimento'] = $data_vencimento;
					$id_pagamento = self::$db->insert("cadastro_financeiro", $data);
					for($i=1;$i<$total_parcelas+1;$i++)
					{
						if($dias == 30) {
							$m = $i - 1;
							$newData = novadata($data_parcela[1] + $m, $data_parcela[0], $data_parcela[2]);	
						} else {
							$newData = novadata($data_parcela[1], $data_parcela[0] + ($i*$dias), $data_parcela[2]);	
						}			
						$p = $i."/".$total_parcelas;
						$data_receita['id_banco'] = $id_banco;
						$data_receita['descricao'] = $descricao_pagamento." - $p - ".$nomecliente;
						$data_receita['valor'] = $valor_parcelas_pago;
						$data_receita['valor_pago'] = $valor_parcelas_cartao;
						$data_receita['data_recebido'] = $newData;
						$data_receita['parcela'] = $i;		
						if($i == 1) {
							$data_receita['valor'] = $valor_parcelas_pago + $diferenca_parcela;
							$data_receita['valor_pago'] = $valor_parcelas_cartao + $diferenca_parcela;
						}					
						$data_receita['id_pagamento'] = $id_pagamento;
						self::$db->insert("receita", $data_receita);
					}
				}
			}

			  if (self::$db->affected()) {
				  
				  if (empty($_POST['salvar'])) {
					$message = lang('CADASTRO_VENDA_FINALIZADA');
					$redirecionar = "index.php?do=vendas&acao=novavenda";
					if ($venda_fiscal) {
						Filter::msgOk($message, $redirecionar);
					} else {
						Filter::msgOkRecibo($message, $redirecionar, $id_venda);
					}
				  } else {
					$message = lang('CADASTRO_VENDA_OK');
					$redirecionar = "index.php?do=vendas&acao=finalizarvenda&id=".$id_venda;
					Filter::msgOk($message, $redirecionar);
				  }
			  } else Filter::msgAlert(lang('NAOPROCESSADO'));

          } else
              print Filter::msgStatus();
			  
      }
	
	/**
	 * Cadastro::getTotalCrediario()
	 *
	 * @return
	 */
	public function getTotalCrediario($id_cadastro = 0)
	{		  
		$valor_crediario = getValue("crediario", "cadastro", "id=".$id_cadastro);
		$sql = "SELECT SUM(c.valor) AS valor, SUM(c.valor_pago) AS valor_pago " 
		  . "\n FROM cadastro_crediario as c "
		  . "\n WHERE c.inativo = 0 AND c.id_cadastro = $id_cadastro ";
		$row = self::$db->first($sql);
		$valor_operacao = ($row) ? $row->valor-$row->valor_pago : 0;
		return $valor_crediario - $valor_operacao;
	}

	/**
	 * Cadastro::processarVendaCrediario()
	 * 
	 * @return
	 */
	public function processarVendaCrediario($id_caixa,$id_cadastro)
	{
		$kit = 0;
		if (!$id_cadastro) {
			Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_CREDIARIO_CLIENTE');
		} else {
			$id_empresa = (empty($_POST['id_empresa'])) ? session('idempresa') : post('id_empresa');
			$valor = post('valor');
			$credito = $this->getTotalCrediario($id_cadastro);

			if (!$valor)
				$valor = 0;			
			if (!$credito)
				$credito = 0;
			
			$id_tabela = sanitize(post('id_tabela'));
			$produtos = (!empty($_POST['id_produto'])) ? $_POST['id_produto'] : null;
			$quantidades = (!empty($_POST['quantidade'])) ? $_POST['quantidade'] : 0;
			$contar_produtos = (is_array($produtos)) ? count($produtos) : 0;
			$valor_venda_tabela = (!empty($_POST['valor_venda_tabela'])) ? $_POST['valor_venda_tabela'] : 0;
		  
			if(empty($_POST['id_produto']) || $contar_produtos==0) {
				Filter::$msgs['contar_produtos'] = lang('MSG_ERRO_PRODUTO_VENDA');
			} else {
				$qtdtotal = 0;
				for ($i=0; $i<$contar_produtos; $i++) {
					$quantidade = ($quantidades[$i]) ?  $quantidades[$i] : 1;
					$quantidade = str_replace(',','',$quantidade);
				    $quantidade = floatval($quantidade);
					$qtdtotal += $quantidade;
					$id_produto = $produtos[$i];
					$kit = getValue("kit", "produto", "id=" . $id_produto);
					$valida_estoque = getValue("valida_estoque", "produto", "id=" . $id_produto);
					
					if($valida_estoque) {
						if($kit) {
							$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque "
							  . "\n FROM produto_kit as k"
							  . "\n LEFT JOIN produto as p ON p.id = k.id_produto "
							  . "\n WHERE k.id_produto_kit = $id_produto "
							  . "\n ORDER BY p.nome ";
							$retorno_row = self::$db->fetch_all($sql);
							if($retorno_row) {
								foreach ($retorno_row as $exrow) {
									if($quantidade >$exrow->estoque)
										Filter::$msgs[$i.'estoque'.$exrow->id] = str_replace("[ESTOQUE]", $exrow->estoque, lang('MSG_ERRO_ESTOQUE')." >> PRODUTO DO KIT = ".$exrow->nome);
								}
							}
						} else {
							$estoque = getValue("estoque", "produto", "id=" . $id_produto);
							if($quantidade > $estoque)
								Filter::$msgs[$i.'estoque'] = str_replace("[ESTOQUE]", $estoque, lang('MSG_ERRO_ESTOQUE'));
						}
					}
				}
				
				$sql = "SELECT (quantidade)"
				  . "\n FROM tabela_precos"
				  . "\n WHERE id = $id_tabela";
				$retorno = self::$db->first($sql);
				  
				if($qtdtotal < $retorno->quantidade ) {
					Filter::$msgs['quantidade'] = str_replace("[QUANTIDADE]", $quantidade, lang('MSG_ERRO_QTDMINIMA'));
				}
			}
			
			$qtdtotal = 0;
			$saldo = $credito - $valor;
			
			if($saldo < 0)
				Filter::$msgs['saldo'] = lang('MSG_ERRO_CREDIARIO_PAGAR').moeda($credito);
			
		}
		
		if (empty(Filter::$msgs)) {
			
			$entrega = (empty($_POST['prazo_entrega'])) ? 0 : 1;
			$status_entrega = ($entrega==1) ? 1 : 3 ;
			  
			$data_venda = array(
				'id_empresa' => session('idempresa'),
				'id_cadastro' => $id_cadastro,
				'id_caixa' => $id_caixa,
				'id_vendedor' => sanitize(post('id_vendedor')),
				'valor_total' => $valor,
				'valor_despesa_acessoria' => 0,
				'valor_desconto' => 0,
				'valor_pago' => $valor,
				'entrega' => $entrega,
				'status_entrega' => $status_entrega,
				'data_venda' => "NOW()",
				'crediario' => 1,
				'prazo_entrega' => dataMySQL(post('prazo_entrega')),
				'observacao' => sanitize(post('observacao')),
				'usuario_venda' => session('nomeusuario'),
				'usuario_pagamento' => session('nomeusuario'),
				'pago' => 1,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			$id_venda = self::$db->insert("vendas", $data_venda);

			$nomecliente = getValue("nome", "cadastro", "id=" . $id_cadastro);
			
			for ($i=0; $i<$contar_produtos; $i++) {
				$quantidade = ($quantidades[$i]) ?  $quantidades[$i] : 1;
				$quantidade = str_replace(',','',$quantidade);
				$quantidade = floatval($quantidade);
				$id_produto = $produtos[$i];
				$valor_venda = converteMoeda($valor_venda_tabela[$i]); //Pega os valores que foram alterados de cada produto na venda
				
				$valor_total = $valor_venda * $quantidade;
				$quant_estoque = $quantidade * (-1);
				
				$data_cadastro_venda = array(
					'id_empresa' => session('idempresa'),
					'id_cadastro' => $id_cadastro,
					'id_caixa' => $id_caixa,
					'id_venda' => $id_venda,
					'id_produto' => $id_produto,
					'id_tabela' => $id_tabela,
					'valor' => $valor_venda,
					'quantidade' => $quantidade,
					'valor_despesa_acessoria' => 0,
					'valor_desconto' => 0,
					'valor_total' => $valor_total,
					'crediario' => 1,
					'pago' => 1,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				$id_cadastro_venda = self::$db->insert("cadastro_vendas", $data_cadastro_venda);
				
				$kit = getValue("kit", "produto", "id=" . $id_produto);
				if($kit) {
						$nomekit = getValue("nome", "produto", "id=" . $id_produto);
						$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade " 
						. "\n FROM produto_kit as k"
						. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
						. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
						. "\n ORDER BY p.nome ";
						$retorno_row = self::$db->fetch_all($sql);
						if ($retorno_row) {
							foreach ($retorno_row as $exrow) {
								$observacao = str_replace("[ID_VENDA]",$id_venda,lang('VENDA_KIT'));
								$observacao = str_replace("[NOME_KIT]",$nomekit,$observacao);
								$observacao = str_replace("[NOME_CLIENTE]",$nomecliente,$observacao);
								
								$data_estoque = array(
									'id_empresa' => session('idempresa'),
									'id_produto' => $exrow->id_produto,
									'quantidade' => $quant_estoque,
									'tipo' => 2,
									'motivo' => 3,
									'observacao' => $observacao,
									'id_ref' => $id_cadastro_venda,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->insert("produto_estoque", $data_estoque);
								
								$totalestoque = $this->getEstoqueTotal($exrow->id_produto);
								
								$data_update = array(
									'estoque' => $totalestoque,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->update("produto", $data_update, "id=".$exrow->id_produto);
							}
						}
						$observacao = str_replace("[ID_VENDA]",$id_venda,lang('VENDA_PRODUTO_CLIENTE'));
						$observacao = str_replace("[NOME_CLIENTE]",$nomecliente,$observacao);
						$data_estoque = array(
							'id_empresa' => $id_empresa,
							'id_produto' => $id_produto, 
							'quantidade' => $quant_estoque,
							'tipo' => 2, 
							'motivo' => 3,
							'observacao' => $observacao, 
							'id_ref' => $id_cadastro_venda, 
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						self::$db->insert("produto_estoque", $data_estoque);
						$totalestoque = $this->getEstoqueTotal($id_produto);
						$data_update = array(
						   'estoque' => $totalestoque, 
						   'usuario' => session('nomeusuario'),
						   'data' => "NOW()"
						);
						self::$db->update("produto", $data_update, "id=".$id_produto);
				} else {
					$observacao = str_replace("[ID_VENDA]",$id_venda,lang('VENDA_PRODUTO_CLIENTE'));
					$observacao = str_replace("[NOME_CLIENTE]",$nomecliente,$observacao);

					$data_estoque = array(
						'id_empresa' => $id_empresa,
						'id_produto' => $id_produto,
						'quantidade' => $quant_estoque,
						'tipo' => 2,
						'motivo' => 3,
						'observacao' => $observacao,
						'id_ref' => $id_cadastro_venda,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("produto_estoque", $data_estoque);
					
					$totalestoque = $this->getEstoqueTotal($id_produto);
					
					$data_update = array(
						'estoque' => $totalestoque,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("produto", $data_update, "id=".$id_produto);
				}
			}
			
			$data_financeiro = array(
				'id_empresa' => $id_empresa,
				'id_cadastro' => $id_cadastro,
				'id_venda' => $id_venda,
				'id_caixa' => $id_caixa,
				'valor_pago' => $valor,
				'valor_total_venda' => $valor,
				'data_vencimento' => "NOW()",
				'data_pagamento' => "NOW()",
				'pago' => 1,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->insert("cadastro_financeiro", $data_financeiro);
			
			$valor_operacao = $valor;
			
			$data_crediario = array(
				'id_empresa' => session('idempresa'),
				'id_cadastro' => $id_cadastro,
				'id_venda' => $id_venda,
				'id_caixa' => $id_caixa,
				'valor_venda' => $valor,
				'operacao' => '1',
				'valor' => $valor_operacao,
				'valor_pago' => 0,
				'data_operacao' => "NOW()",
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->insert("cadastro_crediario", $data_crediario);

			if (self::$db->affected()) {
				$message = lang('CADASTRO_VENDA_FINALIZADA');
				$redirecionar = "index.php?do=vendas&acao=novavenda";
				Filter::msgOkRecibo($message, $redirecionar, $id_venda);
			} else Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
			print Filter::msgStatus();

	}

    /**
     * Cadastro::processarFinalizarVenda()
     * 
     * @return
     */
    public function processarFinalizarVenda($id_caixa)
    {

		$id_venda = intval(post('id_venda'));
		$total_vendas = $this->getTotalVenda($id_venda);
		$total_vendas->valor_final = round($total_vendas->valor_final,2);
		$valor_pago = $this->getTotalFinanceiro($id_venda);
		$valor_pago = round($valor_pago, 2);
		$tipo_sistema = post('tipo_sistema');
		$sql_tipo = "SELECT id FROM tipo_pagamento WHERE id_categoria IN (2,4)";
		$row_tipo = self::$db->fetch_all($sql_tipo);
		$tipo = "";
		if ($row_tipo) {
			$count = 0;
			foreach($row_tipo as $rtipo){
				if ($count++)
					$tipo .= ','.$rtipo->id;
				else
					$tipo .= $rtipo->id;
			}
		}
		if ($id_caixa > 0) {
			if( $total_vendas->valor_final == $valor_pago ) {
				if ($row_tipo) {
					$data_receita = array(
						'id_caixa' => $id_caixa,
						'pago' => 0,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("receita", $data_receita, "inativo = 0 AND tipo IN (".$tipo.") AND id_venda=".$id_venda);
					$data_receita = array(
						'id_caixa' => $id_caixa,
						'pago' => 1,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("receita", $data_receita, "inativo = 0 AND tipo NOT IN (".$tipo.") AND id_venda=".$id_venda);
				} else {
					$data_receita = array(
						'id_caixa' => $id_caixa,
						'pago' => 1,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("receita", $data_receita, "inativo = 0 AND id_venda=".$id_venda);
				}
				$data_cadastrovenda = array(
					'id_caixa' => $id_caixa, 
					'pago' => 1,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update("cadastro_vendas", $data_cadastrovenda, "inativo = 0 AND id_venda=".$id_venda);
				$data_financeiro = array(
					'id_caixa' => $id_caixa, 
					'pago' => 1,
					'data_pagamento' => "NOW()",
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update("cadastro_financeiro", $data_financeiro, "inativo = 0 AND id_venda=".$id_venda);

				$data_venda = array(
					'id_caixa' => $id_caixa,
					'id_vendedor' => (post('id_vendedor')) ? sanitize(post('id_vendedor')) : 0,
					'pago' => 1,
					'usuario_pagamento' => session('nomeusuario'),
					'usuario' => session('nomeusuario'),
					'data_venda' => "NOW()",
					'data' => "NOW()"
				);
				self::$db->update("vendas", $data_venda, "id=".$id_venda);	

				$message = lang('CADASTRO_VENDA_FINALIZADA');
				$redirecionar = ($tipo_sistema==4) ? "index.php?do=vendas&acao=vendaspedidosentrega" : "index.php?do=vendas&acao=vendasaberto";
				if (self::$db->affected()) {
					Filter::msgOkRecibo($message, $redirecionar, $id_venda);
				} else Filter::msgAlert(lang('NAOPROCESSADO'));
					
			} else Filter::msgAlert(lang('CADASTRO_FINALIZAR_VENDA_NOK'));
		} else Filter::msgAlert(lang('CAIXA_VENDA_ERRO'));


    }

	  /**
       * Cadastro::getFinanceiro()
	   *
       * @return
       */
	  public function getFinanceiro($id_venda = 0)
      {
          $sql = "SELECT f.*, t.tipo as pagamento, t.avista " 
		  . "\n FROM cadastro_financeiro as f "
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo "
		  . "\n WHERE f.id_venda = $id_venda AND f.inativo = 0 "
		  . "\n ORDER BY f.tipo ASC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	/**
     * Cadastro::getTotalFinanceiro()
	 *
     * @return
     */
	public function getTotalFinanceiro($id_venda = 0)
    {
        $sql = "SELECT SUM(f.valor_pago) as total " 
		. "\n FROM cadastro_financeiro as f"
		. "\n WHERE f.id_venda = $id_venda AND f.inativo = 0 ";
        $row = self::$db->first($sql);
        return ($row) ? floatval($row->total) : 0;
    }

	/**
     * Cadastro::existePagamentoCrediario()
	 *
     * @return
     */
	public function existePagamentoCrediario($id_venda)
    {
        $sql = "SELECT count(id) as crediarios FROM receita WHERE inativo=0 AND id_venda=$id_venda AND promissoria=1";
        $row = self::$db->first($sql);
        return ($row) ? $row->crediarios : 0;
    }

	/**
	 * Cadastro::getPromissoriasVenda(id_venda)
	 * 
	 * @return Todas as receitas/promissorias de uma venda
	 */
	public function getPromissoriasVenda($id_venda)
	{
		$sql = "SELECT re.id, re.id_venda, re.data_pagamento AS data_vencimento, re.valor_pago AS valor, ca.nome AS cliente, "
		  . "\n        ca.cpf_cnpj AS cliente_documento, ve.data_venda, re.pago, "
		  . "\n        CONCAT(ca.endereco,', ',ca.numero,', ',ca.bairro,', ',ca.cidade,'-',ca.estado) AS cliente_endereco, "
		  . "\n        em.razao_social AS empresa, em.cnpj AS empresa_documento, em.cidade AS empresa_cidade, em.estado AS "
		  . "\n        empresa_uf, em.nome AS fantasia, em.endereco, em.numero, em.bairro "
		  . "\n FROM receita AS re "
		  . "\n LEFT JOIN cadastro AS ca ON ca.id=re.id_cadastro "
		  . "\n LEFT JOIN empresa AS em ON em.id=re.id_empresa "
		  . "\n LEFT JOIN vendas AS ve ON ve.id=re.id_venda "
		  . "\n WHERE re.inativo=0 AND re.id_venda=$id_venda";

		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}

	/**
	 * Cadastro::getPromissoriaReceita(id_receita)
	 * 
	 * @return uma receita/promissoria especifica
	 */
	public function getPromissoriaReceita($id_receita)
	{
		$sql = "SELECT re.id, re.id_venda, re.data_pagamento AS data_vencimento, re.valor_pago AS valor, ca.nome AS cliente, "
		  . "\n        ca.cpf_cnpj AS cliente_documento, ve.data_venda, re.pago, "
		  . "\n        CONCAT(ca.endereco,', ',ca.numero,', ',ca.bairro,', ',ca.cidade,'-',ca.estado) AS cliente_endereco, "
		  . "\n        em.razao_social AS empresa, em.cnpj AS empresa_documento, em.cidade AS empresa_cidade, em.estado AS "
		  . "\n        empresa_uf, em.nome AS fantasia, em.endereco, em.numero, em.bairro "
		  . "\n FROM receita AS re "
		  . "\n LEFT JOIN cadastro AS ca ON ca.id=re.id_cadastro "
		  . "\n LEFT JOIN empresa AS em ON em.id=re.id_empresa "
		  . "\n LEFT JOIN vendas AS ve ON ve.id=re.id_venda "
		  . "\n WHERE re.inativo=0 AND re.id=$id_receita";

		$row = self::$db->fetch_all($sql);
		return ($row) ? $row : 0;
	}
	  
      /**
       * Cadastro::processarCancelarVenda()
       * 
       * @return
       */
      public function processarCancelarVenda()
      {		
   
	    $id_venda = intval(post('id_venda'));
		$venda_inativa = getValue("inativo","vendas","id=" . $id_venda);
	    $id_caixa = intval(post('id_caixa'));
		$historico = intval(post('historico'));
		$pagina = intval(post('pagina'));

	    $total_dinheiro = post('total_dinheiro');
		
		if ($venda_inativa) {
			Filter::$msgs['venda_inativa'] = lang('CADASTRO_APAGAR_VENDA_ERRO');
		}

		if ($id_caixa == 0 and $total_dinheiro > 0) {
			if (empty($_POST['id_banco']))	
              Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');		  
		}

		if (empty(Filter::$msgs)) {	
			$sql = "SELECT v.* " 
				  . "\n FROM cadastro_vendas as v"
				  . "\n WHERE v.id_venda = $id_venda"
				  . "\n ORDER BY v.id DESC";
			$retorno_row = self::$db->fetch_all($sql);
			$caixa_venda = 0;
			foreach ($retorno_row as $prow) {
				$caixa_venda = $prow->id_caixa;		  
				$id_produto = $prow->id_produto;
				$quantidade = $prow->quantidade;
				$kit = getValue("kit", "produto", "id=" . $id_produto);				
				if($kit) {
				  $nomekit = getValue("nome", "produto", "id=" . $id_produto);
				  $sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade " 
						. "\n FROM produto_kit as k"
						. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
						. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
						. "\n ORDER BY p.nome ";
				  $retorno_krow = self::$db->fetch_all($sql);
				  if($retorno_krow) {
					  foreach ($retorno_krow as $exrow) {
							$observacao = str_replace("[ID_VENDA]",$id_venda,lang('CANCELAMENTO_VENDA_KIT'));
							$observacao = str_replace("[NOME_KIT]",$nomekit,$observacao);
							$quantidade_kit = $quantidade * $exrow->quantidade;
							$data_estoque = array(
								'id_empresa' => session('idempresa'),
								'id_produto' => $exrow->id_produto, 
								'quantidade' => $quantidade_kit,
								'tipo' => 1, 
								'motivo' => 7,
								'observacao' => $observacao, 
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->insert("produto_estoque", $data_estoque);	
							$totalestoque = $this->getEstoqueTotal($exrow->id_produto);
							$data_update = array(
								'estoque' => $totalestoque, 
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->update("produto", $data_update, "id=".$exrow->id_produto);
					  }					
				  }
			  }
				$observacao = str_replace("[ID_VENDA]",$id_venda,lang('CANCELAMENTO_VENDA_PRODUTO'));
				$data_estoque = array(
					'id_empresa' => session('idempresa'),
					'id_produto' => $id_produto, 
					'quantidade' => $quantidade,
					'tipo' => 1, 
					'motivo' => 7,
					'observacao' => $observacao, 
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("produto_estoque", $data_estoque);	
				$totalestoque = $this->getEstoqueTotal($id_produto);
				$data_update = array(
					'estoque' => $totalestoque, 
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update("produto", $data_update, "id=".$id_produto);
			
			}
			$wheretipo = " AND tipo <> 1 ";
			if($caixa_venda == $id_caixa) {
				$wheretipo = " ";
			} else {
				if($total_dinheiro > 0){
					$descricao = str_replace("[ID_VENDA]",$id_venda,lang('CANCELAMENTO_VENDA'));
					$data_despesa = array(
						'id_empresa' => session('idempresa'),
						'id_conta' => '23',		
						'descricao' => $descricao,				
						'valor' => $total_dinheiro,				
						'data_vencimento' => "NOW()",
						'data_pagamento' => "NOW()",
						'pago' => '1',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					if($id_caixa) {
						$data_despesa['id_caixa'] = $id_caixa;
											   
					} else {
						$data_despesa['id_banco'] = post('id_banco');				
					}
					$id_despesa = self::$db->insert("despesa", $data_despesa);
					$data_update = array(
						'agrupar' => $id_despesa
					);
					self::$db->update("despesa", $data_update, "id=".$id_despesa);
				}	
			}	
			$data = array(
				'inativo' => '1',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			
			$vendaCrediario = getValue("crediario","vendas","id=".$id_venda);
			if ($vendaCrediario) {
				$data_crediario = array(
						'inativo' => '1',
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"				
				);
				$sql_crediario = "SELECT * FROM cadastro_crediario WHERE id_venda = $id_venda";
				$row_crediario = self::$db->fetch_all($sql_crediario);
				if ($row_crediario) {
					foreach($row_crediario as $crow) {
						if ($crow->pago) {
							$valor_pagamento_total = getValue("valor","cadastro_crediario","id=".$crow->id_pagamento);
							$novo_valor_pagamento = $valor_pagamento_total - abs($crow->valor);
							$data_pago = array(
								'valor' => $novo_valor_pagamento,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->update("cadastro_crediario", $data_pago, "id=" . $crow->id_pagamento);	
							
							$data_receita = array(
								'valor' => $novo_valor_pagamento,
								'valor_pago' => $novo_valor_pagamento,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->update("receita", $data_receita, "id_pagamento=$crow->id_pagamento AND id_cadastro=$crow->id_cadastro AND descricao like 'PAGAMENTO CREDIARIO%'");	
						}
					}
					self::$db->update("cadastro_crediario", $data_crediario, "id_venda =" . $id_venda);	
				}
			}
			
			self::$db->update("receita", $data, "id_venda =" . $id_venda);	
			self::$db->update("cadastro_financeiro", $data, "id_venda =" . $id_venda.$wheretipo);
			self::$db->update("cadastro_vendas", $data, "id_venda =" . $id_venda);			
			self::$db->update("vendas", $data, "id =" . $id_venda);		
						
			if (self::$db->affected()){
				$redirecionar = ($pagina == 1) ? "index.php?do=vendas&acao=vendasdia" : "index.php?do=vendas&acao=vendasperiodo";
				print Filter::msgOk(lang('CADASTRO_APAGAR_VENDA_OK'), $redirecionar);
			} else {
				print Filter::msgAlert(lang('NAOPROCESSADO'));
			}
  
		
		} else
              print Filter::msgStatus();
      }

	  public function atualizarCupom($id_venda, $id_enotas, $id_externo, $contingencia = 0)
	  {
		  $cupom = eNotasGW::$NFeConsumidorApi->consultar($id_enotas, $id_externo);
  
		  $data_cupom = [
			  'status_enotas' => $cupom->status,
			  'motivo_status' => $cupom->motivoStatus,
			  'numero' => $cupom->numero,
			  'serie' => $cupom->serie,
			  'chaveacesso' => $cupom->chaveAcesso,
			  'link_danfe' => $cupom->linkDanfe,
			  'link_download_xml' => $cupom->linkDownloadXml,
			  'contingencia' => $contingencia,
			  'usuario' => session('nomeusuario'),
			  'data' => "NOW()"
		  ];
		  self::$db->update("vendas", $data_cupom, 'id='. $id_venda);
		  return self::$db->affected();
	  }

	  /**
       * Cadastro::processarCancelarVendaFiscal()
       * 
       * @return
       */
      public function processarCancelarVendaFiscal()
      {		
		
	    $id_venda = intval(post('id_venda'));
		$row_vendas = Core::getRowById("vendas", $id_venda);

	    $id_caixa = intval(post('id_caixa'));
		$historico = intval(post('historico'));

	    $total_dinheiro = post('total_dinheiro');
		
		$dentroPrazoCancelamento = !(strtotime($row_vendas->data_emissao.' +30 minutes') < strtotime(date('Y-m-d H:i:s')));;
		if (!$dentroPrazoCancelamento){
			Filter::$msgs['prazo_cancelamento'] = lang('MSG_ERRO_CANCELAR_NFCE');
		}

		if ($id_caixa == 0 and $total_dinheiro > 0) {
			if (empty($_POST['id_banco']))	
              Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');		  
		}

		if (empty(Filter::$msgs)) {
			
			$NFCe_cancelado = false;

			if (Core::emissoaEmProducao()) {
				$id_externo = 'nfc-'.$id_venda;
			} else {
				$id_externo = 'Hnfc-'.$id_venda;
			}

			$row_empresa = Core::getRowById("empresa", $row_vendas->id_empresa);
			$id_enotas = $row_empresa->enotas;
			try {
				$cupom_cancelado = eNotasGW::$NFeConsumidorApi->cancelar($id_enotas, $id_externo);
				if ($cupom_cancelado === null) {
					//Filter::msgOk(lang('NOTA_FISCAL_CONSUMIDOR_CANCELAR_SUCESSO'));
					$NFCe_cancelado = true;
					$this->atualizarCupom($id_venda, $id_enotas, $id_externo);
				}
				else{
					Filter::msgAlert(lang('CADASTRO_APAGAR_VENDA_FISCAL_NOK'));
				}	
				// echo json_encode($cupom_cancelado);
			} catch (Exception $e) {
				// NF0001 - Código de cupom já status cancelado
				if ($e->errors[0]->codigo == 'NF0001') {
					Filter::msgOk(lang('CADASTRO_APAGAR_VENDA_FISCAL_OK'));
					$this->atualizarCupom($id_venda, $id_enotas, $id_externo);
				} else
					Filter::msgAlert($e->getMessage());
					// echo $e->getMessage();
			}


			if ($NFCe_cancelado) {

				$sql = "SELECT v.* " 
					. "\n FROM cadastro_vendas as v"
					. "\n WHERE v.id_venda = $id_venda"
					. "\n ORDER BY v.id DESC";
				$retorno_row = self::$db->fetch_all($sql);
				$caixa_venda = 0;
				foreach ($retorno_row as $prow) {
					$caixa_venda = $prow->id_caixa;		  
					$id_produto = $prow->id_produto;
					$quantidade = $prow->quantidade;
					$kit = getValue("kit", "produto", "id=" . $id_produto);				
					if($kit) {
					$nomekit = getValue("nome", "produto", "id=" . $id_produto);
					$sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade " 
						. "\n FROM produto_kit as k"
						. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
						. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
						. "\n ORDER BY p.nome ";
					$retorno_row = self::$db->fetch_all($sql);
					if($retorno_row) {
						foreach ($retorno_row as $exrow) {
								$observacao = "CANCELAMENTO DE VENDA DE KIT [$nomekit] ";	
								$quantidade_kit = $quantidade*$exrow->quantidade;
								$data_estoque = array(
									'id_empresa' => session('idempresa'),
									'id_produto' => $exrow->id_produto, 
									'quantidade' => $quantidade_kit,
									'tipo' => 1, 
									'motivo' => 7,
									'observacao' => $observacao, 
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->insert("produto_estoque", $data_estoque);	
								$totalestoque = $this->getEstoqueTotal($exrow->id_produto);
								$data_update = array(
									'estoque' => $totalestoque, 
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->update("produto", $data_update, "id=".$exrow->id_produto);
						}
					}
				} else {
					$observacao = "CANCELAMENTO DE VENDA DE PRODUTO";			
					$data_estoque = array(
						'id_empresa' => session('idempresa'),
						'id_produto' => $id_produto, 
						'quantidade' => $quantidade,
						'tipo' => 1, 
						'motivo' => 7,
						'observacao' => $observacao, 
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->insert("produto_estoque", $data_estoque);	
					$totalestoque = $this->getEstoqueTotal($id_produto);
					$data_update = array(
						'estoque' => $totalestoque, 
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					self::$db->update("produto", $data_update, "id=".$id_produto);
				}
				}
				$wheretipo = " AND tipo <> 1 ";
				if($caixa_venda == $id_caixa) {
					$wheretipo = " ";
				} else {
					if($total_dinheiro > 0){
						$descricao = "CANCELAMENTO DA VENDA [".$id_venda."]";
						$data_despesa = array(
							'id_empresa' => session('idempresa'),
							'id_conta' => '23',		
							'descricao' => $descricao,				
							'valor' => $total_dinheiro,				
							'data_vencimento' => "NOW()",
							'data_pagamento' => "NOW()",
							'pago' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						if($id_caixa) {
							$data_despesa['id_caixa'] = $id_caixa;
												
						} else {
							$data_despesa['id_banco'] = post('id_banco');				
						}
						$id_despesa = self::$db->insert("despesa", $data_despesa);
						$data_update = array(
							'agrupar' => $id_despesa
						);
						self::$db->update("despesa", $data_update, "id=".$id_despesa);
					}	
				}	
				$data = array(
					'inativo' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"	
				);
				
				$vendaCrediario = getValue("crediario","vendas","id=".$id_venda);
				if ($vendaCrediario) {
					$data_crediario = array(
							'inativo' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"				
					);
					$sql_crediario = "SELECT * FROM cadastro_crediario WHERE id_venda = $id_venda";
					$row_crediario = self::$db->fetch_all($sql_crediario);
					if ($row_crediario) {
						foreach($row_crediario as $crow) {
							if ($crow->pago) {
								$valor_pagamento_total = getValue("valor","cadastro_crediario","id=".$crow->id_pagamento);
								$novo_valor_pagamento = $valor_pagamento_total - abs($crow->valor);
								$data_pago = array(
									'valor' => $novo_valor_pagamento,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->update("cadastro_crediario", $data_pago, "id=" . $crow->id_pagamento);	
								
								$data_receita = array(
									'valor' => $novo_valor_pagamento,
									'valor_pago' => $novo_valor_pagamento,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->update("receita", $data_receita, "id_pagamento=$crow->id_pagamento AND id_cadastro=$crow->id_cadastro AND descricao like 'PAGAMENTO CREDIARIO%'");	
							}
						}
						self::$db->update("cadastro_crediario", $data_crediario, "id_venda =" . $id_venda);	
					}
				}
				
				self::$db->update("receita", $data, "id_venda =" . $id_venda);	
				self::$db->update("cadastro_financeiro", $data, "id_venda =" . $id_venda.$wheretipo);
				self::$db->update("cadastro_vendas", $data, "id_venda =" . $id_venda);			
				self::$db->update("vendas", $data, "id =" . $id_venda);		
				
				if (self::$db->affected()){
					$redirecionar = ($historico) ? "index.php?do=cadastro&acao=historico&id=".$historico : "index.php?do=vendas&acao=vendasaberto" ;
					print Filter::msgOk(lang('CADASTRO_APAGAR_VENDA_FISCAL_OK'), $redirecionar);
				} else {
					print Filter::msgAlert(lang('NAOPROCESSADO'));
				}
			}
		
		} else
              print Filter::msgStatus();
      }

      /**
       * Cadastro::processarCancelarVendaAberto()
       * 
       * @return
       */
      public function processarCancelarVendaAberto($id_venda)
      {
		$venda_inativa = getValue("inativo","vendas","id=" . $id_venda);
		if ($venda_inativa) {
			Filter::$msgs['venda_inativa'] = lang('CADASTRO_APAGAR_VENDA_ERRO');
		}

		if (empty(Filter::$msgs)) {		
			$sql = "SELECT v.* " 
				  . "\n FROM cadastro_vendas as v"
				  . "\n WHERE v.id_venda = $id_venda"
				  . "\n ORDER BY v.id DESC";
			$retorno_row = self::$db->fetch_all($sql);
			foreach ($retorno_row as $prow) {	  
				$id_produto = $prow->id_produto;
				$quantidade = $prow->quantidade;
				$kit = getValue("kit", "produto", "id=" . $id_produto);				
				if($kit){ 
				  $nomekit = getValue("nome", "produto", "id=" . $id_produto);
				  $sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade " 
						. "\n FROM produto_kit as k"
						. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
						. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
						. "\n ORDER BY p.nome ";
				  $retorno_krow = self::$db->fetch_all($sql);
				  if($retorno_krow) {
					  foreach ($retorno_krow as $exrow) {
							$observacao = str_replace("[ID_VENDA]",$id_venda,lang('CANCELAMENTO_VENDA_KIT'));
							$observacao = str_replace("[NOME_KIT]",$nomekit,$observacao);
							$quantidade_kit = $quantidade * $exrow->quantidade;

							$data_estoque = array(
								'id_empresa' => session('idempresa'),
								'id_produto' => $exrow->id_produto, 
								'quantidade' => $quantidade_kit,
								'tipo' => 1, 
								'motivo' => 7,
								'observacao' => $observacao, 
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->insert("produto_estoque", $data_estoque);	
							$totalestoque = $this->getEstoqueTotal($exrow->id_produto);
							$data_update = array(
								'estoque' => $totalestoque, 
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							self::$db->update("produto", $data_update, "id=".$exrow->id_produto);
					  }
				  }
				}

				$observacao = str_replace("[ID_VENDA]",$id_venda,lang('CANCELAMENTO_VENDA_PRODUTO'));
				$data_estoque = array(
					'id_empresa' => session('idempresa'),
					'id_produto' => $prow->id_produto, 
					'quantidade' => $prow->quantidade,
					'tipo' => 1, 
					'motivo' => 7,
					'observacao' => $observacao, 
					'id_ref' => $id_venda, 
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("produto_estoque", $data_estoque);					
				$totalestoque = $this->getEstoqueTotal($prow->id_produto);
				$data_update = array(
					'estoque' => $totalestoque, 
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->update("produto", $data_update, "id=".$prow->id_produto);
			}
			$data = array(
				'inativo' => '1',
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->update("receita", $data, "id_venda =" . $id_venda);	
			self::$db->update("cadastro_financeiro", $data, "id_venda =" . $id_venda);
			self::$db->update("cadastro_vendas", $data, "id_venda =" . $id_venda);			
			self::$db->update("vendas", $data, "id =" . $id_venda);		
			
			if (self::$db->affected()){
				print Filter::msgOk(lang('CADASTRO_APAGAR_VENDA_OK'), "index.php?do=vendas&acao=vendasaberto");
			} else {
				print Filter::msgAlert(lang('NAOPROCESSADO'));
			}
		
		} else
              print Filter::msgStatus();
      }
	  
	  /**
       * Cadastro::getVendas()
	   *
       * @return
       */
	  public function getVendas($id_cadastro = 0)
      {
          $sql = "SELECT v.*, vse.status, vse.cor" 
		  . "\n FROM vendas as v "
		  . "\n LEFT JOIN vendas_status_entrega as vse ON vse.id = v.status_entrega "
		  . "\n WHERE v.id_cadastro = $id_cadastro "
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getItemVenda()
	   *
       * @return
       */
	  public function getItemVenda($id_venda = 0)
      {
          $sql = "SELECT v.*, p.nome as produto, p.cfop, p.ncm, p.unidade, p.cest, p.icms_percentual, p.icms_cst, p.anp, p.codigo, p.codigobarras " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n LEFT JOIN produto as p ON p.id = v.id_produto "
		  . "\n WHERE v.id_venda = $id_venda AND v.inativo = 0"
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getMovimentoCaixa()
	   *
       * @return
       */
	  public function getMovimentoVenda($id_venda)
      {
         $sql = "SELECT f.id, f.inativo, t.tipo as pagamento, f.id_cadastro, c.nome as cadastro, f.id_venda, f.id_banco, b.banco, f.tipo, f.valor_total_venda, f.valor_pago, f.numero_cartao, f.parcelas_cartao, f.banco_cheque, f.numero_cheque, f.data_vencimento, f.data_pagamento " 
		  . "\n FROM cadastro_financeiro as f "
		  . "\n LEFT JOIN cadastro as c ON c.id = f.id_cadastro "
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco "
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo "
		  . "\n WHERE f.id_venda = $id_venda "
		  . "\n ORDER BY f.id ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getProdutosVenda()
	   *
       * @return
       */
	  public function getProdutosVenda($id_venda = 0)
      {
          $sql = "SELECT v.*, p.nome as produto, p.codigo, t.tabela, p.codigobarras, p.cfop, p.ncm, p.icms_cst" 
		  . "\n FROM cadastro_vendas as v"
		  . "\n LEFT JOIN produto as p ON p.id = v.id_produto "
		  . "\n LEFT JOIN tabela_precos as t ON t.id = v.id_tabela "
		  . "\n WHERE v.id_venda = $id_venda AND v.inativo = 0 "
		  . "\n ORDER BY v.id ASC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	/**
      * Cadastro::getPagamentoVenda()
	  *
      * @return
      */
	public function getPagamentosVenda($id_venda = 0)
    {
		$sql = "SELECT f.*, t.tipo as pagamento "
		. "\n FROM cadastro_financeiro as f "
		. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo "
		. "\n WHERE f.inativo = 0 AND f.id_venda = $id_venda"
		. "\n ORDER BY f.id ";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }
	  
	  /**
       * Cadastro::getFinanceiroVenda()
	   *
       * @return
       */
	  public function getFinanceiroVenda($id_venda = 0)
      {
          $sql = "SELECT t.id, t.tipo as pagamento, f.id_caixa, f.id as id_financeiro, SUM(f.valor_pago) AS valor_pago, f.total_parcelas" 
		  . "\n FROM cadastro_financeiro as f "
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo "
		  . "\n WHERE f.inativo = 0 AND f.id_venda = $id_venda"
		  . "\n GROUP BY t.id"
		  . "\n ORDER BY t.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getFinanceiroVendaParcelas()
	   *
       * @return
       */
	  public function getFinanceiroVendaParcelas($id_venda = 0)
      {
          $sql = "SELECT * FROM receita where id_venda = $id_venda ORDER BY parcela ASC";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }

	  /**
       * Cadastro::getFinanceiroPagamentoVendaParcelas()
	   *
       * @return
       */
	  public function getFinanceiroPagamentoVendaParcelas($id_pagamento = 0)
      {
          $sql = "SELECT r.*, t.id_categoria "
		  . "\n FROM receita as r "
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = r.tipo "
		  . "\n WHERE id_pagamento = $id_pagamento AND r.inativo=0 ORDER BY r.parcela ASC ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
     
	 /**
       * Cadastro::getFinanceiroVendaBoleto()
	   *
       * @return
       */
	  public function getFinanceiroVendaBoleto($id_venda)
      {
          $sql = "SELECT f.* " 
		  . "\n FROM cadastro_financeiro as f "
		  . "\n WHERE f.inativo = 0 AND f.id_venda = $id_venda";
          $row = self::$db->first($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getTotalProdutosVenda()
	   *
       * @return
       */
	  public function getTotalProdutosVenda($id_venda = 0)
      {
          $sql = "SELECT SUM(v.valor_total) as valor_total, SUM(v.valor_desconto) as valor_desconto, SUM(v.valor_despesa_acessoria) as valor_acrescimo   " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n WHERE v.id_venda = $id_venda AND v.inativo = 0 ";
          $row = self::$db->first($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getTotalPagamentosVenda()
	   *
       * @return
       */
	  public function getTotalPagamentosVenda($id_venda = 0)
      {
          $sql = "SELECT SUM(f.valor_pago) as total " 
		  . "\n FROM cadastro_financeiro as f"
		  . "\n WHERE f.id_venda = $id_venda AND f.inativo = 0 ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }
	  
	  /**
       * Cadastro::getTabelaVenda()
	   *
       * @return
       */
	  public function getTabelaVenda($id_venda = 0)
      {
          $sql = "SELECT v.id_tabela " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n WHERE v.id_venda = $id_venda AND v.inativo = 0 "
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->first($sql);

          return ($row) ? $row->id_tabela : 0;
      }
 
	  /**
       * Cadastro::getVendasDia()
	   *
       * @return
       */
	  public function getVendasDia($data = false)
      {
         $sql = "SELECT v.*, c.nome as cadastro, u.nome as vendedor " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n LEFT JOIN usuario as u ON u.id = v.id_vendedor"
		  . "\n WHERE (v.pago = 1 OR v.fiscal = 2) AND DATE_FORMAT(v.data_venda, '%d/%m/%Y') = '$data' "
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
 
	  /**
       * Cadastro::getVendasPeriodo()
	   *
       * @return
       */
	  public function getVendasPeriodo($dataini = false, $datafim = false)
      {
		 $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
         $sql = "SELECT v.*, c.nome as cadastro " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n WHERE v.pago = 1 "
		  . "\n AND v.data_venda BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n ORDER BY v.id ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	/**
     * Cadastro::getVendasValorProdutoAlterado()
	 *
     * @return
     */
	public function getVendasValorProdutoAlterado($dataini = false, $datafim = false)
    {
		$dataini = ($dataini) ? $dataini : date('d/m/Y'); 
        $datafim = ($datafim) ? $datafim : date('d/m/Y');
        $sql = "SELECT cv.*, v.data_venda, c.nome as cadastro, p.nome as produto"
		. "\n FROM cadastro_vendas as cv"
		. "\n LEFT JOIN vendas as v ON v.id = cv.id_venda"
		. "\n LEFT JOIN cadastro as c ON c.id = cv.id_cadastro "
		. "\n LEFT JOIN produto as p ON p.id = cv.id_produto "
		. "\n WHERE cv.inativo=0 AND cv.pago = 1 AND v.inativo=0 "
		. "\n AND v.data_venda BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		. "\n AND cv.valor_original>0 AND cv.valor_original<>cv.valor"
		. "\n ORDER BY cv.id_venda DESC ";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
    }
	  
	/**
      * Cadastro::getFinanceiroDia()
	  *
      * @return
      */
	public function getFinanceiroDia($data = false)
    {
    	$sql = "SELECT t.id, t.tipo as pagamento, SUM(f.valor_pago) AS valor_pago " 
		. "\n FROM cadastro_financeiro as f "
		. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo "
		. "\n WHERE DATE_FORMAT(f.data_pagamento, '%d/%m/%Y') = '$data' AND f.pago = 1 AND f.inativo = 0  " 
		. "\n GROUP BY t.id "
		. "\n ORDER BY t.id DESC";
        $row = self::$db->fetch_all($sql);
        return ($row) ? $row : 0;
	}
	  
	  /**
       * Cadastro::getVendaAberto()
	   *
       * @return
       */
	  public function getVendaAberto()
      {
          $sql = "SELECT v.*, c.nome as cadastro " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n WHERE v.pago = 2 AND v.inativo = 0 "
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendaAberto_Novas()
	   *
       * @return
       */
	  public function getVendaAberto_Novas()
      {
          $sql = "SELECT v.*, c.nome as cadastro " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n WHERE v.inativo = 0 AND (v.status_entrega = 1 OR v.status_entrega = 9)  AND (v.pago = 2 OR v.crediario = 1) AND v.venda_crediario_finalizada = 0"
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendaAberto_Entrega()
	   *
       * @return
       */
	  public function getVendaAberto_Entrega()
      {
          $sql = "SELECT v.*, c.nome as cadastro " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n WHERE v.pago = 2 AND v.inativo = 0 AND v.status_entrega = 2"
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendaAberto_Entregue()
	   *
       * @return
       */
	  public function getVendaAberto_Entregue()
      {
          $sql = "SELECT v.*, c.nome as cadastro " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n WHERE v.pago = 2 AND v.inativo = 0 AND v.status_entrega = 3"
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendaAberto_Problema()
	   *
       * @return
       */
	  public function getVendaAberto_Problema()
      {
          $sql = "SELECT v.*, c.nome as cadastro, vse.status " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n LEFT JOIN vendas_status_entrega as vse ON vse.id = v.status_entrega "
		  . "\n WHERE v.pago = 2 AND v.inativo = 0 AND v.status_entrega > 3 AND v.status_entrega <> 9"
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendaCanceladas()
	   *
       * @return
       */
	  public function getVendaCanceladas($mes_ano)
      {
          $sql = "SELECT v.*, c.nome as cadastro " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n WHERE v.inativo = 1 AND DATE_FORMAT(v.data, '%m/%Y') = '$mes_ano' "
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendaAbertoUsuario()
	   *
       * @return
       */
	  public function getVendaAbertoUsuario($usuario = '')
      {
          $sql = "SELECT v.*, c.nome as cadastro" 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n WHERE v.usuario_venda = '$usuario' AND v.pago = 2 AND v.inativo = 0"
		  . "\n ORDER BY v.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::geNomesProdutosDaVenda()
	   *
       * @return
       */
	  public function geNomesProdutosDaVenda($id_venda)
      {
          $sql = "SELECT p.nome as produto" 
		  . "\n FROM cadastro_vendas as v"
		  . "\n LEFT JOIN produto as p ON p.id = v.id_produto "
		  . "\n WHERE v.id_venda = $id_venda AND v.inativo = 0 "
		  . "\n ORDER BY p.nome";
          $row = self::$db->fetch_all($sql);

		  $produtos = "";
		  if ($row){
			  foreach($row as $row_produto) {
				  $produtos = '- '.$row_produto->produto.'<br>';
			  }
		  }
		  
          return $produtos;
      }
	  
	  /**
       * Cadastro::getVendasTipo()
	   *
       * @return
       */
	  public function getVendasTipo($id_produto = 0, $dataini = false, $datafim = false)
      {
		 $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
         $sql = "SELECT v.id, c.nome as cadastro, v.id_cadastro, v.valor_total, v.valor_desconto, v.valor*v.quantidade AS valor, v.usuario, v.data " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n WHERE v.id_produto = $id_produto AND v.pago = 1 AND v.inativo = 0 "
		  . "\n AND v.data BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n ORDER BY v.id ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Produto::getVendasProdutos()
	   *
       * @return
       */
	  public function getVendasProdutos($id_categoria = false, $id_fabricante = false, $id_grupo = false, $dataini = false, $datafim = false)
      {
          $wfabricante = ($id_fabricante) ? " AND p.id_fabricante = $id_fabricante " : "";
          $wgrupo = ($id_grupo) ? " AND p.id_grupo = $id_grupo " : "";
          $wcategoria = ($id_categoria) ? " AND p.id_categoria = $id_categoria " : "";
		  $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
          $datafim = ($datafim) ? $datafim : date('d/m/Y');
		  
          $sql = "SELECT c.id_produto as id_ref, SUM(c.quantidade) as quantidade, SUM(c.valor_total) as valor_total, p.id, p.nome " 
		  . "\n FROM cadastro_vendas as c"
		  . "\n LEFT JOIN produto as p ON p.id = c.id_produto "
		  . "\n WHERE c.pago = 1 AND c.inativo = 0  "
		  . "\n AND c.data BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n $where "
		  . "\n $wfabricante "
		  . "\n $wgrupo "
		  . "\n $wcategoria "
		  . "\n GROUP BY c.id_produto "
		  . "\n ORDER BY p.nome ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
   
	  /**
       * Cadastro::getUsuariosVenda()
	   *
       * @return
       */
	  public function getUsuariosVenda($dataini = false, $datafim = false)
      {
		 $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
         $sql = "SELECT v.usuario_venda AS usuario" 
		  . "\n FROM vendas as v"
		  . "\n WHERE v.pago = 1 AND v.inativo = 0 "
		  . "\n AND v.data BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n GROUP BY v.usuario_venda "
		  . "\n ORDER BY v.usuario_venda ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendasProdutoPeriodo()
	   *
       * @return
       */
	  public function getVendasProdutoPeriodo($dataini = false, $datafim = false, $vendedor = false)
      {
		 $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
         $wvendedor = ($vendedor) ? "AND v.usuario = '$vendedor' " : "";
         $sql = "SELECT v.id, c.nome as cadastro, v.id_venda, v.id_caixa, v.id_produto, v.id_cadastro, v.valor_total, v.valor_desconto, v.valor*v.quantidade AS valor, v.quantidade, v.usuario, v.data, p.nome as produto " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n LEFT JOIN produto as p ON p.id = v.id_produto "
		  . "\n WHERE v.pago = 1 AND v.inativo = 0 $wvendedor "
		  . "\n AND v.data BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n ORDER BY v.id ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendasConsolidadoVendedor()
	   *
       * @return
       */
	  public function getVendasConsolidadoVendedor($dataini = false, $datafim = false)
      {
		 $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
         $sql = "SELECT v.id_vendedor, u.nome, u.percentual, SUM(v.valor_total) AS valor, SUM(v.valor_despesa_acessoria) AS valor_despesa_acessoria, SUM(v.valor_desconto) AS valor_desconto, SUM(v.valor_pago) AS valor_total, SUM(v.troco) AS valor_troco, COUNT(1) AS quant " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN usuario AS u ON u.id = v.id_vendedor "
		  . "\n WHERE v.inativo = 0 AND v.pago = 1 "
		  . "\n AND v.data_venda BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n GROUP BY v.id_vendedor "
		  . "\n ORDER BY u.nome ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendasConsolidadoVendedorPago()
	   *
       * @return
       */
	  public function getVendasConsolidadoVendedorPago($dataini = false, $datafim = false)
      {
		 $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
         $sql = "SELECT v.id_vendedor, u.nome, u.percentual, SUM(r.valor) AS valor, SUM(r.valor_pago) AS valor_pago, SUM(v.troco) AS valor_troco, COUNT(1) AS quant " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN usuario AS u ON u.id = v.id_vendedor "
		  . "\n LEFT JOIN receita as r ON r.id_venda = v.id "
		  . "\n WHERE v.inativo = 0 AND v.pago = 1 AND r.pago = 1"
		  . "\n AND r.data_recebido BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n GROUP BY v.id_vendedor "
		  . "\n ORDER BY u.nome ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendasConsolidado()
	   *
       * @return
       */
	  public function getVendasConsolidado($dataini = false, $datafim = false)
      {
		 $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
         $sql = "SELECT p.nome as produto, v.id_produto as id_ref, SUM(v.quantidade) AS quant, SUM(v.valor*v.quantidade) AS valor, SUM(v.valor_desconto) AS valor_desconto, (SUM(v.valor*v.quantidade)-SUM(v.valor_desconto)) AS valor_total, SUM(v.valor_despesa_acessoria) as despesa_acessoria, SUM(p.valor_custo) AS valor_custo " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n LEFT JOIN produto AS p ON p.id = v.id_produto"
		  . "\n LEFT JOIN vendas AS ve ON ve.id = v.id_venda"
		  . "\n WHERE v.inativo = 0 AND ve.pago = 1"
		  . "\n AND ve.data_venda BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n GROUP BY v.id_produto "
		  . "\n ORDER BY v.id_produto ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }

	  /**
       * Cadastro::getVendasConsolidadoFiscal()
	   *
       * @return
       */
	  public function getVendasConsolidadoFiscal($dataini = false, $datafim = false)
      {
		 $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
         $sql = "SELECT produto, id_ref, SUM(quant) AS quant, SUM(valor) AS valor, SUM(valor_desconto) AS valor_desconto, SUM(despesa_acessoria) AS despesa_acessoria, SUM(valor_total) AS valor_total, SUM(valor_custo) AS valor_custo"
		 . "\n FROM ("
		 . "\n SELECT p.nome as produto, v.id_produto as id_ref, SUM(v.quantidade) AS quant, SUM(v.valor*v.quantidade) AS valor, SUM(v.valor_desconto) AS valor_desconto, SUM(v.valor_despesa_acessoria) as despesa_acessoria, (SUM(v.valor*v.quantidade)-SUM(v.valor_desconto)+SUM(v.valor_despesa_acessoria)) AS valor_total, SUM(p.valor_custo) AS valor_custo " 
		 . "\n FROM cadastro_vendas as v"
		 . "\n LEFT JOIN produto AS p ON p.id = v.id_produto"
		 . "\n LEFT JOIN vendas AS ve ON ve.id = v.id_venda"
		 . "\n WHERE v.inativo = 0 AND ve.pago = 1 AND ve.fiscal = 1"
		 . "\n AND ve.data_emissao BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		 . "\n GROUP BY v.id_produto "
		 . "\n UNION "
		 . "\n SELECT p.nome as produto, v.id_produto as id_ref, SUM(v.quantidade) AS quant, SUM(v.valor*v.quantidade) AS valor, SUM(v.valor_desconto) AS valor_desconto, SUM(v.valor_despesa_acessoria) as despesa_acessoria, (SUM(v.valor*v.quantidade)-SUM(v.valor_desconto)+SUM(v.valor_despesa_acessoria)) AS valor_total, SUM(p.valor_custo) AS valor_custo " 
		 . "\n FROM cadastro_vendas as v"
		 . "\n LEFT JOIN produto AS p ON p.id = v.id_produto"
		 . "\n LEFT JOIN vendas AS ve ON ve.id = v.id_venda"
		 . "\n LEFT JOIN nota_fiscal as nf ON nf.id = ve.id_nota_fiscal "
		 . "\n WHERE v.inativo = 0 AND ve.pago = 1 AND ve.id_nota_fiscal > 0 AND nf.status_enotas = 'Autorizada' AND nf.inativo = 0"
		 . "\n AND nf.data_emissao BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		 . "\n GROUP BY v.id_produto"
		 . "\n ) AS RESULTADO"
		 . "\n GROUP BY id_ref";

          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendasConsolidado()
	   *
       * @return
       */
	  public function getVendasConsolidadoProduto($dataini = false, $datafim = false, $id_grupo = false, $monofasico = false)
      {
		 $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
		 $wGrupo = ($id_grupo) ? " AND p.id_grupo = $id_grupo" : "";
		 $wMonofasico = ($monofasico) ? " AND p.monofasico = 1" : "";
		 
         $sql = "SELECT p.nome as produto, v.id_produto as id_ref, SUM(v.quantidade) AS quant, SUM(v.valor*v.quantidade) AS valor, SUM(v.valor_desconto) AS valor_desconto, (SUM(v.valor*v.quantidade)-SUM(v.valor_desconto)) AS valor_total, SUM(v.valor_despesa_acessoria) as despesa_acessoria, SUM(p.valor_custo) AS valor_custo " 
		  . "\n FROM cadastro_vendas as v"
		  . "\n LEFT JOIN produto AS p ON p.id = v.id_produto"
		  . "\n LEFT JOIN vendas AS ve ON ve.id = v.id_venda"
		  . "\n WHERE v.inativo = 0 AND ve.pago = 1"
		  . "\n AND ve.data_venda BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  .	"\n $wGrupo $wMonofasico"
		  . "\n GROUP BY v.id_produto "
		  . "\n ORDER BY v.id_produto ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }

	  /**
       * Cadastro::getVendasPagamento()
	   *
       * @return
       */
	  public function getVendasPagamento($tipo = 0, $dataini = false, $datafim = false)
      {		  
		  $sql = "SELECT v.*, c.nome as cadastro, sum(f.valor_pago) as valor_pagamento, u.nome as vendedor, f.inativo " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n LEFT JOIN cadastro_financeiro as f ON f.id_venda = v.id"
		  . "\n LEFT JOIN usuario as u ON u.id = v.id_vendedor"
		  . "\n WHERE f.tipo = '$tipo' AND v.pago = 1 AND f.data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') AND f.inativo = 0"
		  . "\n GROUP BY v.id"
		  . "\n ORDER BY v.id";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getVendasPagamentoFiscal()
	   *
       * @return
       */
	  public function getVendasPagamentoFiscal($tipo = 0, $dataini = false, $datafim = false)
      {	
		  $wTipo = ($tipo) ? "AND f.tipo = '$tipo'" : '';
		  $sql = "SELECT v.*, c.nome as cadastro, sum(f.valor_pago) as valor_pagamento, u.nome as vendedor, f.inativo " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n LEFT JOIN cadastro_financeiro as f ON f.id_venda = v.id"
		  . "\n LEFT JOIN usuario as u ON u.id = v.id_vendedor"
		  . "\n WHERE v.pago = 1 AND v.fiscal = 1 AND v.data_emissao BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') AND f.inativo = 0 $wTipo"
		  . "\n GROUP BY v.id"
		  . "\n UNION "
		  . "\n SELECT v.*, c.nome as cadastro, sum(f.valor_pago) as valor_pagamento, u.nome as vendedor, f.inativo " 
		  . "\n FROM vendas as v"
		  . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro"
		  . "\n LEFT JOIN cadastro_financeiro as f ON f.id_venda = v.id"
		  . "\n LEFT JOIN usuario as u ON u.id = v.id_vendedor"
		  . "\n LEFT JOIN nota_fiscal as nf ON nf.id = v.id_nota_fiscal "
		  . "\n WHERE v.pago = 1 AND v.inativo = 0 AND v.id_nota_fiscal > 0 AND nf.status_enotas = 'Autorizada' AND nf.inativo = 0 $wTipo"
		  . "\n AND nf.data_emissao BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') AND f.inativo = 0"
		  . "\n GROUP BY v.id";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getFinanceiroPeriodoFiscal()
	   *
       * @return
       */
	  public function getFinanceiroPeriodoFiscal($dataini = false, $datafim = false)
      {
          $sql = "SELECT id, pagamento, id_categoria, SUM(valor_pago) AS valor_pago, SUM(quant) AS quant"
		  . "\n FROM ("
			. "\n SELECT t.id, t.tipo as pagamento, t.id_categoria as id_categoria, SUM(f.valor_pago) AS valor_pago, COUNT(1) AS quant"
			. "\n FROM cadastro_financeiro as f"
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo"
			. "\n LEFT JOIN vendas as v ON v.id = f.id_venda"
			. "\n WHERE f.pago = 1 AND f.inativo = 0 AND v.fiscal = 1"
			. "\n AND v.data_emissao BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')"
			. "\n GROUP BY t.id"
			. "\n UNION"
			. "\n SELECT t.id, t.tipo as pagamento, t.id_categoria as id_categoria, SUM(f.valor_pago) AS valor_pago, COUNT(1) AS quant"
			. "\n FROM cadastro_financeiro as f"
			. "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo"
			. "\n LEFT JOIN vendas as v ON v.id = f.id_venda"
			. "\n LEFT JOIN nota_fiscal as nf ON nf.id = v.id_nota_fiscal"
			. "\n WHERE f.pago = 1 AND f.inativo = 0 AND v.inativo = 0 AND v.id_nota_fiscal > 0 AND nf.status_enotas = 'Autorizada' AND nf.inativo = 0"
			. "\n AND nf.data_emissao BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s')"
			. "\n GROUP BY id"
			. "\n ) AS RESULTADO"
			. "\n GROUP BY id";

          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	  /**
       * Cadastro::getFinanceiroPeriodo()
	   *
       * @return
       */
	  public function getFinanceiroPeriodo($dataini = false, $datafim = false)
      {
          $sql = "SELECT t.id, t.tipo as pagamento, t.id_categoria as id_categoria, SUM(f.valor_pago) AS valor_pago, COUNT(1) AS quant " 
		  . "\n FROM cadastro_financeiro as f "
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo "
		  . "\n WHERE f.pago = 1 AND f.inativo = 0  " 
		  . "\n AND f.data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n GROUP BY t.id"
		  . "\n ORDER BY t.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getFinanceiroPeriodo()
	   *
       * @return
       */
	  public function getFinanceiroPeriodoAPagar($dataini = false, $datafim = false)
      {
          $sql = "SELECT t.id, t.tipo as pagamento, SUM(f.valor_pago) AS valor_pago, COUNT(1) AS quant " 
		  . "\n FROM cadastro_financeiro as f "
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo "
		  . "\n WHERE f.pago = 0 AND f.inativo = 0  " 
		  . "\n AND f.data_pagamento BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n GROUP BY t.id"
		  . "\n ORDER BY t.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getCadastroProdutos()
	   *
       * @return
       */
	  public function getCadastroProdutos($id_cadastro = 0)
      {
          $sql = "SELECT p.nome as produto, p.codigo, v.valor_total, v.id_venda, v.data, v.usuario " 
		  . "\n FROM cadastro_vendas as v "
		  . "\n LEFT JOIN produto AS p ON p.id = v.id_produto "
		  . "\n WHERE v.pago = 1 AND v.inativo = 0 AND v.id_cadastro = $id_cadastro "
		  . "\n ORDER BY v.data ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getCadastroProdutosGestao()
	   *
       * @return
       */
	  public function getCadastroProdutosGestao($id_cadastro, $dataini, $datafim)
      {
          $sql = "SELECT v.id_produto, v.valor, p.nome as produto, p.codigo, SUM(v.valor_total) AS valor_total, SUM(v.quantidade) AS quant " 
		  . "\n FROM cadastro_vendas as v "
		  . "\n LEFT JOIN produto AS p ON p.id = v.id_produto "
		  . "\n WHERE v.inativo = 0 AND v.id_cadastro = $id_cadastro "
		  . "\n AND v.data BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
		  . "\n GROUP BY v.id_produto, v.valor "
		  . "\n ORDER BY p.nome, v.valor ASC ";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getCadastroConsignados()
	   *
       * @return
       */
	  public function getCadastroConsignados($id_cadastro = 0)
      {
          $sql = "SELECT p.nome as produto, p.codigo, v.valor_total, v.id_venda, v.data, v.usuario " 
		  . "\n FROM cadastro_vendas as v "
		  . "\n LEFT JOIN produto AS p ON p.id = v.id_produto "
		  . "\n WHERE v.pago = 2 AND v.inativo = 0 AND v.id_cadastro = $id_cadastro "
		  . "\n ORDER BY v.data ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getInfoVendas()
	   *
       * @return
       */
	  public function getInfoVendas($id_cadastro = 0)
      {
          $sql = "SELECT SUM(v.quantidade) as quant, SUM(v.valor_total) AS valor " 
		  . "\n FROM cadastro_vendas as v "
		  . "\n WHERE v.pago = 1 AND v.inativo = 0 AND v.id_cadastro = $id_cadastro ";
          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::getInfoProdutos()
	   *
       * @return
       */
	  public function getInfoProdutos($id_cadastro = 0)
      {
          $sql = "SELECT p.nome as produto, v.id_produto, SUM(v.valor_total) AS valor, SUM(v.quantidade) AS quantidade " 
		  . "\n FROM cadastro_vendas as v "
		  . "\n LEFT JOIN produto AS p ON p.id = v.id_produto"
		  . "\n WHERE v.pago = 1 AND v.inativo = 0 AND v.id_cadastro = $id_cadastro "
		  . "\n GROUP BY v.id_produto ASC "
		  . "\n ORDER BY p.nome ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::processarArquivoContato()
       * 
       * @return
       */
      public function processarArquivoContato()
      {
		  $realizado = false;
          if (empty(Filter::$msgs)) {
				foreach($_POST as $nome_campo => $valor){
					$valida = strpos($nome_campo, "tmpname");
					if($valida) {
						$ponteiro = fopen (UPLOADS.$valor, "r");
						if ($ponteiro == false) die(lang('FORM_ERROR13'));
						while (!feof ($ponteiro)) {
							$linha = fgets($ponteiro, 4096);
							$linha = str_replace("\"","",$linha);
							$campos = explode(";",$linha);
							$isCNPJ = substr_count($campos[0], "CNPJ");
							$isVazio = trim($campos[0]);
							$count = count($campos);
							if(!$isCNPJ and $isVazio and 19 == $count) {
								$realizado = true;
								$isCNPJ = false;
								$isVazio = false;
								$nome = (cleanSanitize($campos[2])) ? cleanSanitize($campos[2]) : cleanSanitize($campos[1]);
								$telefones = $campos[11]." ".$campos[12]." ".$campos[13]." ".$campos[14]." ".$campos[15]." ".$campos[16];
								$telefones = str_replace("  ", " ", $telefones);
								$telefones = trim($telefones);
								$cpf_cnpj = limparCPF_CNPJ($campos[0]);
								$cep = limparCPF_CNPJ($campos[9]);
								$tipo = (strlen($cpf_cnpj) > 12) ? '1' : '2';
								$id_consultor = intval($campos[18]);
								if($id_consultor) {
									$nomeusuario = getValue('usuario', 'usuario', 'id='.$id_consultor);
									$id_status = 99;
								} else {
									$nomeusuario = session('nomeusuario');
									$id_status = 0;
								}
								$data = array(
									'id_empresa' => session('idempresa'),
									'cpf_cnpj' => $cpf_cnpj,
									'razao_social' => cleanSanitize($campos[1]),
									'nome' => $nome,
									'tipo' => $tipo,
									'cep' => $cep,
									'endereco' => cleanSanitize($campos[5]),
									'numero' => cleanSanitize($campos[6]),
									'complemento' => cleanSanitize($campos[7]),
									'bairro' => cleanSanitize($campos[8]),
									'cidade' => cleanSanitize($campos[4]),
									'estado' => cleanSanitize($campos[3]),
									'contato' => cleanSanitize($campos[10]),
									'telefone' => $telefones, 
									'observacao' => cleanSanitize($campos[17]), 
									'oportunidade' => "1",
									'data_cadastro' => "NOW()",
									'id_origem' => '1', 
									'id_status' => $id_status, 
									'usuario' => $nomeusuario,
									'data' => "NOW()"
								);
							self::$db->insert(self::uTable, $data);
							}
						}
						fclose ($ponteiro);
					}
				}
			  ($realizado) ? Filter::msgOk(lang('CONTATO_ARQUIVOS_OK'), "index.php?do=cadastro&acao=oportunidades") : Filter::msgAlert(lang('NAOPROCESSADO'));
		  } else
              print Filter::msgStatus();
		}
	  
	  /**
       * Cadastro::getUsuariosComercial()
	   *
       * @return
       */
	  public function getUsuariosComercial()
      {
          $sql = "SELECT u.id, u.nome, u.usuario, u.lastlogin, u.lastip " 
		  . "\n FROM usuario as u "
		  . "\n WHERE u.active = 'y' AND u.nivel in (3,5)"
		  . "\n ORDER BY u.nome ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	 /**
       * Cadastro::getEntregadores()
	   *
       * @return
       */
	  public function getEntregadores()
      {
          $sql = "SELECT u.* " 
		  . "\n FROM usuario as u "
		  . "\n WHERE u.active = 'y' AND u.nivel = 0"
		  . "\n ORDER BY u.nome ASC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cadastro::getGruposEconomico()
       * 
       * @return
       */
      public function getGruposEconomico()
      {
		  $sql = "SELECT id, economico FROM economico WHERE inativo = 0 ORDER BY economico";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Cadastro::processarEconomico()
       * 
       * @return
       */
      public function processarEconomico()
      {
		  if (empty($_POST['nome']))
              Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');	  

          if (empty(Filter::$msgs)) {

              $data = array(
					'economico' => sanitize(post('nome')),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
              
              (Filter::$id) ? self::$db->update("economico", $data, "id=" . Filter::$id) : self::$db->insert("economico", $data);
              $message = (Filter::$id) ? lang('ECONOMICO_ALTERADO_OK') : lang('ECONOMICO_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=economico&acao=listar");   
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Cadastro::getReceitasCadastro()
       * 
       * @return
       */
      public function getReceitasCadastro($id_cadastro = false)
      {
          $id_cadastro = ($id_cadastro) ? $id_cadastro : Filter::$id;
		  
		  $sql = "SELECT f.id, c.conta, f.id_conta, f.id_banco, b.banco, f.descricao, f.id_caixa, f.tipo, f.id_cadastro, f.valor_pago, f.conciliado, f.data_recebido, f.data_pagamento, f.pago, t.tipo as pagamento, DATE_FORMAT(f.data_pagamento, '%Y%m%d') as controle" 
		  . "\n FROM receita as f" 
		  . "\n LEFT JOIN conta as c ON c.id = f.id_conta" 
		  . "\n LEFT JOIN tipo_pagamento as t ON t.id = f.tipo" 
		  . "\n LEFT JOIN banco as b ON b.id = f.id_banco" 
		  . "\n WHERE f.inativo = 0 AND f.id_cadastro = '$id_cadastro'"
		  . "\n ORDER BY f.data_pagamento";
		  //echo $sql;
		  $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
	} 
	
	/**
       * Cadastro::getVendasProdutoFornecedor()
	   *
       * @return
       */
	  public function getVendasProdutoFornecedor($dataini = false, $datafim = false, $id_produto = 0, $id_fornecedor = 0)
      {
		 $dataini = ($dataini) ? $dataini : date('d/m/Y'); 
         $datafim = ($datafim) ? $datafim : date('d/m/Y');
		 $whereProduto = ($id_produto) ? " AND pro.id = $id_produto" : "" ;
		 $whereForncedor = ($id_fornecedor) ? " AND fab.id = $id_fornecedor" : "" ;
         
		  $sql = "SELECT fab.fabricante, pro.nome AS produto, cav.quantidade, cad.nome AS cliente, ven.data_venda, ven.id_vendedor, u.usuario " 
            . "\n FROM vendas AS ven"
            . "\n LEFT JOIN cadastro_vendas AS cav ON cav.id_venda = ven.id "
            . "\n LEFT JOIN cadastro AS cad ON cad.id = ven.id_cadastro "
            . "\n LEFT JOIN produto AS pro ON pro.id = cav.id_produto "
            . "\n LEFT JOIN fabricante AS fab ON fab.id = pro.id_fabricante "
            . "\n LEFT JOIN usuario as u on u.id = ven.id_vendedor "
            . "\n WHERE ven.inativo = 0 AND ven.pago = 1 AND cav.inativo = 0 "
            . "\n AND ven.data_venda BETWEEN STR_TO_DATE('$dataini 00:00:00','%d/%m/%Y %H:%i:%s') AND STR_TO_DATE('$datafim 23:59:59','%d/%m/%Y %H:%i:%s') "
            . "\n $whereProduto $whereForncedor "
            . "\n ORDER BY fab.fabricante, pro.nome, cad.nome";
          $row = self::$db->fetch_all($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
       * Cadastro::processarVincularClienteVenda()
       * 
       * @return
       */
      public function processarVincularClienteVenda()
      {
		  if (empty($_POST['id_venda']))
              Filter::$msgs['id_venda'] = lang('MSG_ERRO_VENDA');	
		  
		  if (empty($_POST['id_cadastro']))
              Filter::$msgs['id_cadastro'] = lang('MSG_ERRO_CLIENTE');	
		  
		  if (empty(Filter::$msgs)) {
			
			$id_venda = intval(post('id_venda'));
			$id_cadastro = intval(post('id_cadastro'));
			$pagina = intval(post('pagina'));
			
			$data_venda = array(
				'id_cadastro' => $id_cadastro,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->update("vendas", $data_venda, "id=" . $id_venda);
			
			$redirecionar = ($pagina == 1) ? "index.php?do=vendas&acao=vendasdia" : "index.php?do=vendas&acao=vendasaberto";
			if (self::$db->affected()) {
				Filter::msgOk(lang('CADASTRO_CLIENTE_VENDA_OK'), $redirecionar);
              } else
				Filter::msgAlert(lang('NAOPROCESSADO'));
			
		  } else
              print Filter::msgStatus();
	  }
	  
	  /**
	   * Cadastro::getCadastro($id_cadastro)
	   *
       * @return
       */
	  public function getCadastro($id_cadastro)
      {
         $sql = "SELECT * FROM cadastro WHERE id = $id_cadastro" ;
          $row = self::$db->first($sql);
          return ($row) ? $row : 0;
      }
	  
	  /**
	   * Cadastro::getCadastrocpfcnpj($cpf_cnpj)
	   *
       * @return
       */
	  public function getCadastrocpfcnpj($cpf_cnpj)
      {
         $sql = "SELECT * FROM cadastro WHERE cpf_cnpj = $cpf_cnpj" ;
          $row = self::$db->first($sql);
          return ($row) ? $row : 0;
      }

	  public function processarPlanilhaClienteFornecedorImportacao () {

		$redirecionar = "index.php?do=cadastro&acao=importar_cliente_fornecedor";
            
		foreach($_POST as $nome_campo => $valor){
			$valida = strpos($nome_campo, "tmpname");
			if($valida) {
				$arquivo = UPLOADS.$valor;
				if (file_exists($arquivo)){
					
					require_once "PHPExcel/Classes/PHPExcel.php";

					$reader             = PHPExcel_IOFactory::createReaderForFile($arquivo) ;
					$excel_Obj          = $reader->load($arquivo);
					$worksheet          = $excel_Obj->getActiveSheet();//->toArray();
					// $worksheet = $excel_Obj->getSheet('0');
					$lastRow            = $worksheet->getHighestRow(); //Retorna o numero da ultima linha.
					$columnCount        = $worksheet->getHighestDataColumn(); //Retorna a ultima letra por coluna.
					$columnCountNumber  = PHPExcel_Cell::columnIndexFromString($columnCount); //Numero de colunas que tem na planilha.
					
					$col = 0;
					for ($row = 2; $row <= $lastRow ; $row++) {
						
						$nome = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()) ;
						if(!$nome) {
							$worksheet->removeRow($row,1); //Remove linha com defeito
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue() ;
							Filter::msgInfo('Erro! Campo [NOME] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar) ;
						}
						
						$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue() ;
						if(!$tipo){
							$worksheet->removeRow($row,1);
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							Filter::msgInfo('Erro! Campo [TIPO] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar);
						}
						
						$email  = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
						if(!$email){
							$worksheet->removeRow($row, 1);
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue() ;
							$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue() ;
							$email = $worksheet->getCellByColumnAndRow(4, $row)->getValue() ;
							Filter::msgInfo('Erro! Campo [EMAIL] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar);
						}
						
						$cep = $worksheet->getCellByColumnAndRow(5, $row)->getValue() ;
						if(!$cep){
							$worksheet->removeRow($row, 1);
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							$email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
							$cep = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
							Filter::msgInfo('Erro! Campo [CEP] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar);
						}
						
						$endereco = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
						if(!$endereco){
							$worksheet->removeRow($row, 1);
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							$email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
							$cep = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
							$endereco = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
							Filter::msgInfo('Erro! Campo [ENDEREÇO] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar);
						}

						$numero = $worksheet->getCellByColumnAndRow(7, $row)->getValue() ;
						if(!$numero){
							$worksheet->removeRow($row, 1);
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							$email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
							$cep = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
							$endereco = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
							$numero = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
							Filter::msgInfo('Erro! Campo [NÚMERO] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar);
						}

						$bairro = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue()) ;
						if(!$bairro) {
							$worksheet->removeRow($row, 1);
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							$email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
							$cep = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
							$endereco = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
							$numero = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
							$bairro = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
							Filter::msgInfo('Erro! Campo [BAIRRO] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar);
						}

						$cidade = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue()) ;
						if(!$cidade) {
							$worksheet->removeRow($row, 1);
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							$email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
							$cep = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
							$endereco = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
							$numero = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
							$bairro = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
							$cidade = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
							Filter::msgInfo('Erro! Campo [CIDADE] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar);
						}

						$estado = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue()) ;
						if(!$estado) {
							$worksheet->removeRow($row, 1);
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							$email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
							$cep = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
							$endereco = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
							$numero = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
							$bairro = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
							$cidade = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
							$estado = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
							Filter::msgInfo('Erro! Campo [ESTADO] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar);
						}

						$telefone = $worksheet->getCellByColumnAndRow(12, $row)->getValue() ?? exit ;
						if(!$telefone) {
							$worksheet->removeRow($row, 1);
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							$email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
							$cep = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
							$endereco = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
							$numero = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
							$bairro = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
							$cidade = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
							$estado = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
							$telefone = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
							Filter::msgInfo('Erro! Campo [TELEFONE] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar);
						}

						$celular = $worksheet->getCellByColumnAndRow(13, $row)->getValue() ?? exit ;
						if(!$celular) {
							$worksheet->removeRow($row, 1);
							$nome = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							$tipo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							$email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
							$cep = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
							$endereco = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
							$numero = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
							$bairro = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
							$cidade = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
							$estado = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
							$telefone = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
							$celular = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
							Filter::msgInfo('Erro! Campo [CELULAR] está vazio na linha '.$row.' da planilha, 
							contudo este cliente/fornecedor não entrou no sistema. <br> Este campo é obrigatório, favor preencher. Em seguida entre novamente com a planilha no sistema.',$redirecionar);
						}

						$razao_social   = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
						$cpf_cnpj       = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
						$ie             = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
						$observacao     = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());
						$cliente        = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
						$fornecedor     = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
						
						//Verifica se há nome igual
						$sql_nome = "SELECT nome FROM cadastro WHERE nome = '$nome' ";
						$row_nome = self::$db->first($sql_nome);

						if($nome){
							if($nome == $row_nome->nome){
								Filter::msgInfo('Há um cliente/fornecedor na planilha com o mesmo [NOME] cadastrado no sistema. 
								<br>Este registro não entrou no sistema devido a duplicidade.',$redirecionar);
								exit ;
							}
						}
						
						$data = [
							'nome' => cleanSanitize($nome),
							'razao_social' => cleanSanitize($razao_social),
							'tipo' => $tipo,
							'cpf_cnpj' => limparCPF_CNPJ($cpf_cnpj), //PrimayKey _ Limpa o cpf/cnpj se vier com caracter especial;
							'email' => $email,
							'cep' => $cep,
							'endereco' => cleanSanitize($endereco),
							'numero' => cleanSanitize($numero),
							'bairro' => cleanSanitize($bairro),
							'cidade' => cleanSanitize($cidade),
							'estado' => cleanSanitize($estado),
							'ie' => cleanSanitize($ie),
							'telefone' => formatar_telefone($telefone),
							'celular' => formatar_telefone($celular),
							'observacao' => cleanSanitize($observacao),
							'cliente' => cleanSanitize($cliente),
							'fornecedor' => cleanSanitize($fornecedor),
							'data_cadastro' => 'NOW()',
							'inativo' => '0',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						];
						self::$db->insert(self::uTable, $data);
					}
				} else Filter::msgError("Arquivo não foi encontrado.",$redirecionar);
			}
		}
		if (self::$db->affected()) {
			$msg = "Planilha de cliente/fornecedor anexada ao sistema.";
			Filter::msgOk($msg, $redirecionar);   
		} else Filter::msgError(lang('NAOPROCESSADO_IMPORTACAO'),$redirecionar);

	}
	  
  }
?>