<?php
  /**
   * Classe Empresa
   *
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe nao e permitido.');

  class Empresa
  {
      const uTable = "empresa";
      public $did = 0;
      public $empresaid = 0;
      
      private static $db;

      /**
       * Empresa::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
       * Empresa::getEmpresas()
       * 
       * @return
       */
      public function getEmpresas()
      {
		  $where = "";
		  if (Registry::get("Usuario")->nivel < 1) {
              $where = "AND id = ".session('idempresa');
          }
		  $sql = "SELECT * FROM empresa WHERE inativo = 0 $where ";
		  $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Empresa::getEmpresasTodas()
       * 
       * @return
       */
      public function getEmpresasTodas()
      {
		 $sql = "SELECT id, nome, razao_social, telefone, email, endereco, numero, cidade, fiscal, enotas, iss_aliquota, nfc, crediario  FROM empresa WHERE inativo = 0 ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Empresa::processarEmpresa()
       * 
       * @return
       */
      public function processarEmpresa()
      {
          $nome = sanitize(post('nome'));
          $razao_social = sanitize(post('razao_social'));

          $contabilidade_nome = sanitize(post('contabilidade_nome')); 
          $contabilidade_razao_social = sanitize(post('contabilidade_razao_social'));

		  if (empty($_POST['nome']))
              Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');	  

		  if (empty($_POST['perfil_empresa']))
              Filter::$msgs['perfil_empresa'] = lang('MSG_ERRO_PERFIL_EMPRESA');

          if (empty(Filter::$msgs)) {

			  $fiscal =  (empty($_POST['enotas'])) ? '0': '1';
			  $nfc =  (empty($_POST['enotas'])) ? '0': post('nfc');

              $data = array(
					'nome' => html_entity_decode($nome, ENT_QUOTES, 'UTF-8'),
					'razao_social' => html_entity_decode($razao_social, ENT_QUOTES, 'UTF-8'),
					'sigla' => sanitize(post('sigla')), 
					'cnpj' => limparCPF_CNPJ(post('cnpj')), 
					'endereco' => sanitize(post('endereco')), 
					'numero' => sanitize(post('numero')), 
					'complemento' => sanitize(post('complemento')),  
					'bairro' => sanitize(post('bairro')),  
					'cidade' => sanitize(post('cidade')),  
					'estado' => sanitize(post('estado')),  
					'perfil_empresa' => sanitize(post('perfil_empresa')),
					'cep' => sanitize(post('cep')), 
					'responsavel' => sanitize(post('responsavel')), 
					'telefone' => sanitize(post('telefone')),  
					'celular' => sanitize(post('celular')),   
					'email' => sanitize(post('email')),  
					'fiscal' => $fiscal,
					'iss_aliquota' => (post('iss_aliquota')) ? converteMoeda(post('iss_aliquota')) : 0.00, 
					'cest' => sanitize(post('cest')), 
					'mva' => converteMoeda(post('mva')), 
					'icms_st_aliquota' => converteMoeda(post('icms_st_aliquota')), 
					'icms_normal_aliquota' => converteMoeda(post('icms_normal_aliquota')), 
					'crediario' => (post('crediario')) ? sanitize(post('crediario')) : 0.00,
                    'multa_crediario' => converteMoeda(post('multa_crediario')),
                    'juros_crediario' => converteMoeda(post('juros_crediario')),
                    'tolerancia_crediario' => sanitize(post('tolerancia_crediario')),
					'atualizar_valor_produto' => intval(sanitize(post('atualizar_valor_produto'))),
                    'cnpj_contador' => limparCPF_CNPJ(post('cnpj_contador')),
					'contabilidade_nome' => html_entity_decode($contabilidade_nome, ENT_QUOTES, 'UTF-8'), 
					'contabilidade_razao_social' => html_entity_decode($contabilidade_razao_social, ENT_QUOTES, 'UTF-8'),
					'contabilidade_nome_contato' => sanitize(post('contabilidade_nome_contato')), 
					'contabilidade_cnpj' => limparCPF_CNPJ(post('contabilidade_cnpj')), 
					'contabilidade_cpf_contador' => limparCPF_CNPJ(post('contabilidade_cpf_contador')), 
					'contabilidade_crc_contador' => sanitize(post('contabilidade_crc_contador')), 
					'contabilidade_email' => sanitize(post('contabilidade_email')), 
					'contabilidade_endereco' => sanitize(post('contabilidade_endereco')), 
					'contabilidade_numero' => sanitize(post('contabilidade_numero')), 
					'contabilidade_complemento' => sanitize(post('contabilidade_complemento')),  
					'contabilidade_bairro' => sanitize(post('contabilidade_bairro')),  
					'contabilidade_cidade' => sanitize(post('contabilidade_cidade')),  
					'contabilidade_estado' => sanitize(post('contabilidade_estado')),  
					'contabilidade_cep' => sanitize(post('contabilidade_cep')), 
					'contabilidade_telefone' => sanitize(post('contabilidade_telefone')),
                    'mostrar_vendas_dia_vendedor' => intval(sanitize(post('mostrar_vendas_dia_vendedor'))),
                    'modal_cancelar_produto_venda' => intval(sanitize(post('modal_cancelar_produto_venda'))),
                    'modal_alterar_valor_produto_venda' => intval(sanitize(post('modal_alterar_valor_produto_venda'))),
                    'alterar_valor_crediario' => intval(sanitize(post('alterar_valor_crediario'))),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );

              if (!empty($_POST['serie'])) {
				  $data['serie'] = post('serie');
			  }
			  if (!empty($_POST['enotas'])) {
				  $data['enotas'] = post('enotas');
			  }
			  if ($_POST['usuario_controle']) {
                    $data['emissor_producao'] = intval(sanitize(post('perfil_emissao')));
                    $data['calibragem_balanca'] = intval(sanitize(post('calibragem_balanca')));
                    $data['versao_emissao'] = intval(sanitize(post('versao_emissao')));
					$data['nfc'] = $nfc;
					$data['venda_aberto'] = intval(sanitize(post('venda_aberto')));
                    $data['orcamento'] = intval(sanitize(post('orcamento')));
					$data['tipo_sistema'] = intval(post('tipo_sistema'));
					$data['modulo_impressao'] = intval(post('modulo_impressao'));
					$data['app_vendas'] = intval(post('app_vendas'));
                    $data['cadastro_app'] = intval(post('cadastro_app'));
                    $data['crediario_app'] = intval(post('crediario_app'));
                    $data['desconto_app'] = intval(post('desconto_app'));
                    $data['ordernar_valor_app'] = intval(post('ordernar_valor_app'));
                    $data['modulo_integracao'] = intval(post('modulo_integracao'));
                    $data['modulo_ponto'] = intval(post('modulo_ponto'));
                    $data['aplicativo_estoque'] = intval(post('aplicativo_estoque'));
                    $data['modulo_emissao_boleto'] = intval(post('modulo_emissao_boleto'));
                    $data['modulo_integracao_ecommerce'] = intval(post('modulo_integracao_ecommerce'));
			  }

              if($data['modulo_emissao_boleto'] === 0) {
                $data['boleto_banco'] = "";
                $data['boleto_codigo_banco'] = "";
                $data['boleto_agencia'] = "";
                $data['boleto_conta'] = "";
                $data['boleto_convenio'] = "";
                $data['boleto_instrucoes1'] = "";
                $data['boleto_instrucoes2'] = "";
                $data['boleto_instrucoes3'] = "";
                $data['boleto_instrucoes4'] = "";
                $data['codigo_juros'] = 0;
                $data['dias_juros'] = 0;
                $data['valor_juros'] = 0;
                $data['codigo_multa'] = 0;
                $data['dias_multa'] = 0;
                $data['valor_multa'] = 0;
                $data['codigo_protesto'] = 0;
                $data['dias_protesto'] = 0;
              }

              (Filter::$id) ? self::$db->update(self::uTable, $data, "id=" . Filter::$id) : self::$db->insert(self::uTable, $data);
              $message = (Filter::$id) ? lang('EMPRESA_ALTERADO_OK') : lang('EMPRESA_ADICIONADO_OK');

              if (self::$db->affected()) {
				  Filter::msgOk($message, "index.php?do=empresa&acao=listar");
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * Empresa::processarBoletoEmpresa()
       * 
       * @return
       */
    public function processarBoletoEmpresa()
    {
        $id_empresa = (Filter::$id>0) ? Filter::$id : 1;
        $row_empresa = Core::getRowById("empresa", $id_empresa);

        if (!$row_empresa->modulo_emissao_boleto) {
            Filter::$msgs['modulo_emissao_boleto'] = lang('BOLETOS_CONFIGURAR_ATENCAO_TEXTO');
        }
        else
        {
            if (empty($_POST['boleto_banco']))
            {
                Filter::$msgs['boleto_banco'] = lang('MSG_ERRO_BOLETO_CONFIG_BANCO');
            } 
            else
            {
                if (empty($_POST['boleto_agencia']))
                    Filter::$msgs['boleto_agencia'] = lang('MSG_ERRO_BOLETO_CONFIG_AGENCIA');

                if (empty($_POST['boleto_conta']))
                    Filter::$msgs['boleto_conta'] = lang('MSG_ERRO_BOLETO_CONFIG_CONTA');

                if (empty($_POST['boleto_convenio']))
                    Filter::$msgs['boleto_convenio'] = lang('MSG_ERRO_BOLETO_CONFIG_CONVENIO');

                if (!(empty($_POST['boleto_cod_juros'])) && ($_POST['boleto_cod_juros']==1 || $_POST['boleto_cod_juros']==2)) //se codigo juros for diferente de isento, verificar dias e valor.
                {
                    if (empty($_POST['boleto_data_juros']))
                        Filter::$msgs['boleto_data_juros'] = lang('MSG_ERRO_BOLETO_CONFIG_DTJUROS');
                    if (empty($_POST['boleto_valor_juros']))
                        Filter::$msgs['boleto_valor_juros'] = lang('MSG_ERRO_BOLETO_CONFIG_VLRJUROS');
                }

                if (!(empty($_POST['boleto_cod_multa'])) && ($_POST['boleto_cod_multa']=='1' || $_POST['boleto_cod_multa']=='2')) //se codigo multa for diferente de isento, verificar dias e valor.
                {
                    if (empty($_POST['boleto_data_multa']))
                        Filter::$msgs['boleto_data_multa'] = lang('MSG_ERRO_BOLETO_CONFIG_DTMULTA');
                    if (empty($_POST['boleto_valor_multa']))
                        Filter::$msgs['boleto_valor_multa'] = lang('MSG_ERRO_BOLETO_CONFIG_VLRMULTA');
                }

                if (!(empty($_POST['boleto_cod_protesto'])) && $_POST['boleto_cod_protesto']=='1') //se codigo protesto for diferente de isento, verificar dias.
                {
                    if (empty($_POST['boleto_data_protesto']))
                        Filter::$msgs['boleto_data_protesto'] = lang('MSG_ERRO_BOLETO_CONFIG_PROTESTO');
                }
            }            
        }

        if (empty(Filter::$msgs)) {

			$data_boleto_empresa = array(
                    'boleto_banco' => sanitize(post('boleto_banco')),
                    'boleto_codigo_banco' => sanitize(post('boleto_codigo_banco')),
                    'boleto_agencia' => sanitize(post('boleto_agencia')),
                    'boleto_conta' => sanitize(post('boleto_conta')),
                    'boleto_convenio' => sanitize(post('boleto_convenio')),
                    'boleto_instrucoes1' => sanitize(post('boleto_instrucoes1')),
                    'boleto_instrucoes2' => sanitize(post('boleto_instrucoes2')),
                    'boleto_instrucoes3' => sanitize(post('boleto_instrucoes3')),
                    'boleto_instrucoes4' => sanitize(post('boleto_instrucoes4')),

                    'codigo_juros' => sanitize(post('boleto_cod_juros')),
                    'dias_juros' => sanitize(post('boleto_data_juros')),
                    'valor_juros' => converteMoeda(post('boleto_valor_juros')),
                    'codigo_multa' => sanitize(post('boleto_cod_multa')),
                    'dias_multa' => sanitize(post('boleto_data_multa')),
                    'valor_multa' => converteMoeda(post('boleto_valor_multa')),
                    'codigo_protesto' => sanitize(post('boleto_cod_protesto')),
                    'dias_protesto' => sanitize(post('boleto_data_protesto')),
                    'usuario_edicao_boleto' => session('nomeusuario'),
					'data_edicao_boleto' => "NOW()",

			  );

              self::$db->update(self::uTable, $data_boleto_empresa, "id=" . $id_empresa);
              $message = lang('BOLETO_ATUALIZAR_OK');

              if (self::$db->affected()) {
				  Filter::msgOk($message, "index.php?do=empresa&acao=boletos&id=".$id_empresa);
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
        } else
            print Filter::msgStatus();
      }

      /**
       * Empresa::getEmpresasDistancia()
       * 
       * @return
       */
      public function getEmpresasDistancia()
      {
		  $sql = "SELECT e.id, e.id_empresa_origem, e.id_empresa_destino, o.nome as origem, d.nome as destino "
		  . "\n FROM empresa_distancia as e"
		  . "\n LEFT JOIN empresa as o on o.id = e.id_empresa_origem "
		  . "\n LEFT JOIN empresa as d on d.id = e.id_empresa_destino ";
		  
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Empresa::processarEmpresaDistancia()
       * 
       * @return
       */
      public function processarEmpresaDistancia()
      {
          if (empty(Filter::$msgs)) {

              $data = array(
					'id_empresa_origem' => sanitize($_POST['id_empresa_origem']), 
					'id_empresa_destino' => sanitize($_POST['id_empresa_destino'])
			  );

              self::$db->insert("empresa_distancia", $data);
              $message = lang('EMPRESA_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message);                  
				  redirecionar("index.php?do=empresa&acao=distancia");
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
       * Empresa::processarEmpresaLogo()
       * 
       * @return
       */
      public function processarEmpresaLogo()
      {			  
			if (empty(Filter::$msgs)) {
				$id_empresa = post('id_empresa');
				$nome = "";
				foreach($_POST as $nome_campo => $valor){
					$valida = strpos($nome_campo, "tmpname");
					if($valida) {
						$nome = $valor;
						$caminho = UPLOADS.$valor;
						$tamanho = filesize($caminho);
					}
				}
				
				$message = lang('EMPRESA_LOGO_OK');
				$redirecionar = "index.php?do=empresa&acao=listar";
				Filter::msgOk($message, $redirecionar);
				
			} else
              print Filter::msgStatus();
      }
	  
	  /**
       * Empresa::is_N1()
       * 
       * @return
       */
      public function is_N1()
      {
		  //$
          //return ($this->nivel >= 3);

      }

      /**
       * Empresa::verificarSituacaoCadastro()
       * 
       */
      public function verificarSituacaoCadastro()
      {
        $sql_empresa = "SELECT * FROM empresa WHERE inativo = 0";
        $row_empresa = Registry::get("Database")->first($sql_empresa);
        $cnpjCadastro = $row_empresa->cnpj;
        $url_verificacao = "https://controle.sigesis.com.br/webservices/administrativo/_verifica_cadastro.php?cnpj=$cnpjCadastro";

            $curl = curl_init();
			curl_setopt_array($curl, array(
		        CURLOPT_URL => $url_verificacao,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
			));

		$response = curl_exec($curl);
		curl_close($curl);

        return $response;
      }
	  
	  public function processarConfigAplicativo () {

            $id = self::$db->first("SELECT id FROM configuracao LIMIT 1")->id;

            $tema_escuro = $_POST['tema_escuro'] ?? $_GET['tema_escuro']; 
            $cor_destaque = $_POST['cor_destaque'] ?? $_GET['cor_destaque'];

            $data_config = [
                'tema_escuro' => $tema_escuro,
                'cor_destaque' => $cor_destaque
            ];
            self::$db->update("configuracao", $data_config, "id=".$id);

            $msg = "Configuração atualizada!";
            if (self::$db->affected()) {
                redirecionar("index.php?do=empresa&acao=listar");
                Filter::msgOk($msg);           
            } else Filter::msgAlert(lang('NAOPROCESSADO'));

        }

        public function getConfigAppEmpresa(){
            $sql = "SELECT id, cor_destaque, tema_escuro, imagem_logo, imagem_popup FROM configuracao " ;
            $row = self::$db->first($sql);

            return ($row) ? $row : "";
        }

        public function getConfigsAppEmpresa(){
            $sql = "SELECT id, cor_destaque, tema_escuro, imagem_logo, imagem_popup FROM configuracao " ;
            $row = self::$db->fetch_all($sql);

            return ($row) ? $row : "";
        }

        public function processarImagemLogoAplicativo() {

            $id = self::$db->first("SELECT id FROM configuracao LIMIT 1")->id;
	
            foreach($_POST as $nome_campo => $valor){
                
                $valida = strpos($nome_campo, "tmpname");
                if($valida){

                    $imagem = "uploads/data/".$valor;
                    $dados = [
                        'imagem_logo' => $imagem
                    ];
                    self::$db->update("configuracao", $dados, "id=" . $id);

                    if (self::$db->affected()) {			  
                        redirecionar("index.php?do=empresa&acao=listar");
                        Filter::msgOk("Imagem LOGOMARCA foi atualizada.");           
                    } else
                        Filter::msgOk(lang('NAOPROCESSADO'));
                }
            }
        }

        public function processarImagemPopupAplicativo() {
            
            $id = self::$db->first("SELECT id FROM configuracao LIMIT 1")->id;
	
            foreach($_POST as $nome_campo => $valor){
                
                $valida = strpos($nome_campo, "tmpname");
                if($valida){

                    $imagem = "uploads/data/".$valor;
                    $dados = [
                        'imagem_popup' => $imagem
                    ];
                    self::$db->update("configuracao", $dados, "id=" . $id);

                    if (self::$db->affected()) {			  
                        redirecionar("index.php?do=empresa&acao=listar");
                        Filter::msgOk("Imagem POP-UP foi atualizada.");           
                    } else Filter::msgOk(lang('NAOPROCESSADO'));
                }
            }
        }

        public function getEmpresa () {
            $sql = "SELECT * FROM empresa WHERE inativo = 0 " ;
            $row = self::$db->first($sql);

            return ($row) ? $row : "";
        }
		
		/**
		 * Empresa::verificaAtualizacao()
		 * 
		 */
		public function verificaAtualizacao(){
			 
			$url_verifica = "https://controle.sigesis.com.br/webservices/verificaAtualizacao.php";
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url_verifica,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
			));

			$response = curl_exec($curl);
			curl_close($curl);

			return $response;
		   
		}

         /**
         * Empresa::processarLogoPdv()
         * 
         * @return
         */
        public function processarLogoPdv(){	
            $id = post('id_empresa');		  
            foreach($_POST as $nome_campo => $valor){
                
                $valida = strpos($nome_campo, "tmpname");
                if($valida)
                {
                    $imagem = $valor;
                    $dados = [
                        'logomarca_pdv' => $imagem,
                        'usuario' => session('nomeusuario'),
                        'data' => "NOW()"
                    ];
                    self::$db->update("empresa", $dados, "id=" . $id);

                    if (self::$db->affected()) {			  
                        redirecionar("index.php?do=empresa&acao=editar&id=".$id);
                        Filter::msgOk(lang('EMPRESA_LOGO_OK'));           
                    } else
                        Filter::msgOk(lang('NAOPROCESSADO'));
                }
            }
        }

        /**
        * Empresa::verificarVencimentoCertificado()
        * 
        */
        public function verificarVencimentoCertificado()
        {
            $sql_empresa = "SELECT * FROM empresa WHERE inativo = 0";
            $row_empresa = Registry::get("Database")->first($sql_empresa);
            $cnpjCadastro = $row_empresa->cnpj;

            $url_verificacao = "https://controle.sigesis.com.br/webservices/administrativo/verificarVencimentoCertificado.php?cnpj=$cnpjCadastro";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url_verificacao,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            return $response;
        }

        public function processarInfoAtualizarCertificado () {

            if (empty($_POST['empresa']))
				Filter::$msgs['empresa'] = lang('MSG_ERRO_EMPRESA');  
            
            if (empty($_POST['razao_social']))
				Filter::$msgs['razao_social'] = lang('MSG_ERRO_NOME');  
            
            if (empty($_POST['email']))
				Filter::$msgs['email'] = lang('MSG_ERRO_EMAIL');  
        
            if (empty($_POST['telefone_celular']))
				Filter::$msgs['telefone_celular'] = lang('MSG_ERRO_TELEFONE');
    
            if (empty($_POST['cpf_cnpj']))
				Filter::$msgs['cpf_cnpj'] = lang('MSG_ERRO_CPF_CNPJ');

            if (empty(Filter::$msgs)) {

                $empresa = sanitize($_POST['empresa']);
                $razao_social = sanitize($_POST['razao_social']);
                $telefone_celular = sanitize($_POST['telefone_celular']);
                $email = sanitize($_POST['email']);
                $cpf_cnpj = limparCPF_CNPJ(post('cpf_cnpj'));
                $contato = sanitize(post('contato'));
                $usuario = session('nomeusuario');

                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                $host = $_SERVER['HTTP_HOST'];
                $url_cliente = $protocol . "://" . $host;
                
                $data = [
                    "nome"              => $empresa,
                    "razao_social"      => $razao_social,
                    "telefone_celular"  => $telefone_celular,
                    "email"             => $email,
                    "cpf_cnpj"          => $cpf_cnpj, 
                    "contato"           => $contato, 
                    "url_cliente"       => $url_cliente,
                    "usuario"           => $usuario
                ];
                $data = json_encode($data);
                
                // Definir a URL de destino
                $url = "https://controle.sigesis.com.br/webservices/administrativo/recebeInfoClienteCertificado.php";
                
                $ch = curl_init();
                                
                curl_setopt($ch, CURLOPT_URL, $url); // URL de destino
                curl_setopt($ch, CURLOPT_POST, 1); // Usar o método POST
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retornar o resultado como string
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecionamentos, se houver
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($data)); // Dados a serem enviados no corpo da requisição
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Accept-Encoding: gzip, deflate',
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Accept: */*',
                    'X-Requested-With: XMLHttpRequest',
                    'Connection: keep-alive',
                ));
                                
                $response = curl_exec($ch);
                               
                if(curl_errno($ch)) {
                    $error_msg = curl_error($ch);
                    echo 'Erro ao enviar a solicitação: ' . curl_error($ch);
                }
                
                $data_array = json_decode($response, true);
                
                // Verificar se houve algum erro durante o parse do JSON
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo "Erro ao parsear a resposta JSON.";
                } else {
                    // Acessar o primeiro dado do array
                    if (!empty($data_array)) {
                        $primeiro_dado = (string) reset($data_array); // Retorna o primeiro elemento do array
                        if($primeiro_dado === "201") {
                            Filter::msgOk("Sucesso! Informação adicionada no sistema.", "index.php");
                        } else {
                            Filter::msgError("Erro! Registro vazio.<br>$response");
                        }
                    } else {
                        echo "Nenhum dado encontrado na resposta.";
                    }
                }


                curl_close($ch);

            } else print Filter::msgStatus();
            
        }

         /**
        * Empresa::verificaRegistroDuplicadoDadosCertificados()
        * 
        */
        public function verificaRegistroDuplicadoCertificadosVencidos()
        {
            $sql_empresa = "SELECT * FROM empresa WHERE inativo = 0";
            $row_empresa = Registry::get("Database")->first($sql_empresa);
            $cnpjCadastro = $row_empresa->cnpj;

            $url_verificacao = "https://controle.sigesis.com.br/webservices/administrativo/verificaRegistroDuplicadoCertificadosVencidos.php?cnpj=$cnpjCadastro";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url_verificacao,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            return $response;
        }

    }
?>