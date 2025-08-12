<?php
  /**
   * Classe Usuario
   *
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Usuario
  {
      const uTable = "usuario";
      public $logged_in = null;
      public $uid = 0;
      public $idempresa = 0;
      public $nomeusuario;
      public $nomeempresa;
      public $sesid;
      public $nome;
      public $nivel;
      public $fiscal;
      public $nfc;
      public $crediario;
      public $venda_aberto;
      public $orcamento;
      private $lastlogin = "NOW()";
      private static $db;

      /**
       * Usuario::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");

          $this->getID();
          $this->startSession();
      }
	  
	  /**
       * Usuario::getUsuarios()
	   *
       * @return
       */
	  public function getUsuarios()
      {
		 $nivel = $this->nivel;
	     $sql = "SELECT u.id, u.nome, u.usuario, u.cpf, u.telefone, u.lastlogin as login, c.nome as empresa, u.nivel, u.salario, u.cargo, u.active, u.salario" 
		  . "\n FROM usuario as u" 
		  . "\n LEFT JOIN empresa as c ON c.id = u.id_empresa" 
		  . "\n WHERE active = 'y' AND nivel <= $nivel"
		  . "\n ORDER BY u.cargo, u.nome";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

	 /**
       * Usuario::getUsuariosPonto()
	   *
       * @return
       */
	  public function getUsuariosPonto()
      {
		 $nivel = $this->nivel;
	     $sql = "SELECT u.id, u.nome, u.cpf, u.usuario, u.telefone, u.lastlogin as login, u.nivel, u.salario, u.cargo, u.active, u.salario" 
		  . "\n FROM usuario as u" 
		  . "\n WHERE active = 'y' AND nivel <= $nivel AND id_tabela_ponto>0"
		  . "\n ORDER BY u.cargo, u.nome";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Usuario::getBloqueadosUsuarios()
	   *
       * @return
       */
	  public function getBloqueadosUsuarios()
      {
		 $nivel = $this->nivel;
	     $sql = "SELECT u.id, u.nome, u.usuario, u.telefone, u.lastlogin as login, c.nome as empresa, u.nivel, u.salario, u.cargo, u.active, u.salario" 
		  . "\n FROM usuario as u" 
		  . "\n LEFT JOIN empresa as c ON c.id = u.id_empresa" 
		  . "\n WHERE active = 'n' "
		  . "\n ORDER BY u.cargo, u.nome";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Usuario::getTodosUsuarios()
	   *
       * @return
       */
	  public function getTodosUsuarios()
      {
		  $sql = "SELECT u.id, u.nome, u.usuario, u.telefone, u.lastlogin as login, c.nome as empresa, u.active, nivel, u.cargo, u.salario " 
		  . "\n FROM usuario as u" 
		  . "\n LEFT JOIN empresa as c ON c.id = u.id_empresa" 
		  . "\n WHERE nivel < 9"
		  . "\n ORDER BY u.cargo, u.nome";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Usuario::processarUsuarioDependente()
       * 
       * @return
       */
      public function processarUsuarioDependente()
      {
		  if (empty($_POST['dependente']))
              Filter::$msgs['dependente'] = lang('MSG_ERRO_NOME');	
		  
          if (empty($_POST['data_nasc']))
              Filter::$msgs['data_nasc'] = lang('MSG_ERRO_DATA_NASCIMENTO');  

          if (empty(Filter::$msgs)) {

              $data = array(
					'id_usuario' => sanitize(post('id_usuario')),
					'dependente' => sanitize(post('dependente')),
					'data_nasc' => dataMySQL(post('data_nasc')),
					'documento' => sanitize(post('documento')),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
              self::$db->insert("usuario_dependente", $data);
              $message = lang('DEPENDENTE_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=usuario&acao=editar&id=".post('id_usuario'));  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * Usuario::getDependentes()
       * 
       * @return
       */
      public function getDependentes($id_usuario)
      {
		  $sql = "SELECT id, id_usuario, dependente, documento, data_nasc, inativo, (DATEDIFF(curdate(), data_nasc)/365) as idade "
				 ." FROM usuario_dependente "
				 ." WHERE inativo = 0 AND id_usuario = $id_usuario ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Usuario::contarUsuarios()
	   *
       * @return
       */
	  public function getUsuariosNivel($nivel)
      {
		 $sql = "SELECT u.id, u.nome, u.cargo " 
		  . "\n FROM usuario as u" 
		  . "\n WHERE u.nivel = $nivel ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
	  /**
       * Usuario::contarUsuarios()
	   *
       * @return
       */
	  public function contarUsuarios($nivel)
      {
		  $sql = "SELECT COUNT(1) as quant " 
		  . "\n FROM usuario as u" 
		  . "\n WHERE nivel = $nivel ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quant : 0;
      }
	  
	  /**
       * Usuario::validaUsuario()
	   *
       * @return
       */
	  public function validaUsuario($nome, $nivel)
      {
		  $sql = "SELECT id " 
		  . "\n FROM usuario as u" 
		  . "\n WHERE nome = '$nome' AND nivel = $nivel";
          $row = self::$db->first($sql);

          return ($row) ? true : false;
      }

      /**
       * Usuario::getID()
       * 
       * @return
       */
      private function getID()
      {
          if (isset($_GET['uid'])) {
              $uid = (is_numeric($_GET['uid']) && $_GET['uid'] > -1) ? intval($_GET['uid']) : false;
              $uid = sanitize($uid);

              if ($uid == false) {
                  Filter::error("O usuário não esta cadastrado.", "Usuario::getID()");
              } else
                  return $this->uid = $uid;
          }
      }


      /**
       * Usuario::startSession()
       * 
       * @return
       */
      private function startSession()
      {
          session_start();
          $this->logged_in = $this->loginCheck();

          if (!$this->logged_in) {
              $this->nomeusuario = $_SESSION['nomeusuario'] = "Convidado";
              $this->sesid = sha1(session_id());
              $this->nivel = 0;
          }
      }

      /**
       * Usuario::loginCheck()
       * 
       * @return
       */
      private function loginCheck()
      {
          if (isset($_SESSION['nomeusuario']) && $_SESSION['nomeusuario'] != "Convidado") {

            $row = $this->getUserInfo($_SESSION['nomeusuario']);
			  if($row) {
				  $this->uid = $row->id;
				  $this->nomeusuario = $row->usuario;
				  $this->idempresa = $row->id_empresa;
				  $this->nome = $row->nome;
				  $this->nivel = $row->nivel;
				  if($row->id_empresa) {
					$empresa_row = Registry::get("Core")->getRowById("empresa", $row->id_empresa);
					$this->nomeempresa = $empresa_row->nome;
					$this->fiscal = $empresa_row->fiscal;
					$this->nfc = $empresa_row->nfc;
					$this->crediario = $empresa_row->crediario;
					$this->venda_aberto = $empresa_row->venda_aberto;
                    $this->orcamento = $empresa_row->orcamento;
				  }				  
			  }
              $this->sesid = sha1(session_id());
              return true;
          } else {
              return false;
          }
      }

      /**
       * Usuario::is_fiscal()
       * 
       * @return
       */
      public function is_fiscal()
      {
          return $this->fiscal;

      }

      /**
       * Usuario::is_nfc()
       * 
       * @return
       */
      public function is_nfc()
      {
          return $this->nfc;

      }

      /**
       * Usuario::is_crediario()
       * 
       * @return
       */
      public function is_crediario()
      {
          return $this->crediario;

      }

      /**
       * Usuario::is_VendaAberto()
       * 
       * @return
       */
      public function is_VendaAberto()
      {
          return $this->venda_aberto;

      }

      /**
       * Usuario::is_Orcamento()
       * 
       * @return
       */
      public function is_Orcamento()
      {
          return $this->orcamento;
      }

      /**
       * Usuario::is_Todos()
       * 
       * @return
       */
      public function is_Todos()
      {
          return ($this->nivel >= 1);

      }
	  
	  /**
       * Usuario::is_Vendas()
       * 
       * @return
       */
      public function is_Vendas()
      {
          return ($this->nivel >= 3);

      }
	  
	  /**
       * Usuario::is_Comercial()
       * 
       * @return
       */
      public function is_Comercial()
      {
          return ($this->nivel >= 5);

      }
	  
	    /**
       * Usuario::is_Administrativo()
       * 
       * @return
       */
      public function is_Administrativo()
      {
          return ($this->nivel >= 6 or $this->nivel == 1);

      }
	  
	        /**
       * Usuario::is_Gerencia()
       * 
       * @return
       */
      public function is_Gerencia()
      {
          return ($this->nivel >= 7);

      }
	  
	  /**
       * Usuario::is_Master()
       * 
       * @return
       */
      public function is_Master()
      {
          return ($this->nivel >= 8);

      }
	  
	  /**
       * Usuario::is_Controller()
       * 
       * @return
       */
      public function is_Controller()
      {
          return ($this->nivel >= 9);

      }

      /**
       * Usuario::is_Contador()
       * 
       * @return
       */
      public function is_Contador()
      {
          return ($this->nivel == 1 or $this->nivel == 9);

      }

      /**
       * Usuario::igual_Contador()
       * 
       * @return
       */
      public function igual_Contador()
      {
          return ($this->nivel == 1);

      }
	  
	  /**
       * Usuario::igual_Vendas()
       * 
       * @return
       */
      public function igual_Vendas()
      {
          return ($this->nivel == 3);

      }
	  
	  /**
       * Usuario::igual_Comercial()
       * 
       * @return
       */
      public function igual_Comercial()
      {
          return ($this->nivel == 4);

      }
	  
	        /**
       * Usuario::igual_Administrativo()
       * 
       * @return
       */
      public function igual_Administrativo()
      {
          return ($this->nivel == 6);

      }
	  
	        /**
       * Usuario::igual_Gerencia()
       * 
       * @return
       */
      public function igual_Gerencia()
      {
          return ($this->nivel == 7);

      }

      /**
       * Usuario::login()
       * 
       * @param mixed $nomeusuario
       * @param mixed $senha
       * @return
       */
      public function login($nomeusuario, $senha, $pin = false)
      {
		  if ($nomeusuario == "" && $senha == "" && $pin == false) {
              Filter::$msgs['usuario'] = lang('LOGIN_R5');
          } else {
              $status = $this->checkStatus($nomeusuario, $senha, $pin);

              switch ($status) {
                  case 0:
                      Filter::$msgs['usuario'] = lang('LOGIN_R1');
                      break;

                  case 1:
                      Filter::$msgs['usuario'] = lang('LOGIN_R2');
                      break;

                  case 2:
                      Filter::$msgs['usuario'] = lang('LOGIN_R3');
                      break;

                  case 3:
                      Filter::$msgs['usuario'] = lang('LOGIN_R4');
                      break;

                  case 4:
                      Filter::$msgs['usuario'] = lang('LOGIN_R4');
                      break;
              }
          }
          if (empty(Filter::$msgs) && $status == 5) {
              $row = ($pin) ? $this->getPINInfo($pin) : $this->getUserInfo($nomeusuario);
              $this->uid = $_SESSION['uid'] = $row->id;
              $_SESSION['id_unico'] = date("YmdHis");
              $this->nomeusuario = $_SESSION['nomeusuario'] = $row->usuario;
              $this->nome = $_SESSION['nome'] = $row->nome;
              $this->idempresa = $_SESSION['idempresa'] = $row->id_empresa;
              $this->nivel = $_SESSION['nivel'] = $row->nivel;
			  if($row->id_empresa) {
				$empresa_row = Registry::get("Core")->getRowById("empresa", $row->id_empresa);
				$this->nomeempresa = $_SESSION['nomeempresa'] =  $empresa_row->nome;
				$this->fiscal = $_SESSION['fiscal'] =  $empresa_row->fiscal;
				$this->nfc = $_SESSION['nfc'] =  $empresa_row->nfc;
				$this->crediario = $_SESSION['crediario'] =  $empresa_row->crediario;
				$this->venda_aberto = $_SESSION['venda_aberto'] =  $empresa_row->venda_aberto;
                $this->orcamento = $_SESSION['orcamento'] =  $empresa_row->orcamento;
			  }	

              $data = array('lastlogin' => $this->lastlogin, 'lastip' => sanitize($_SERVER['REMOTE_ADDR']));
              self::$db->update(self::uTable, $data, "usuario='" . $this->nomeusuario . "'");

              return true;
          } else
              echo Filter::$msgs['usuario'];
      }

      /**
       * Usuario::logout()
       * 
       * @return
       */
      public function logout()
      {
          unset($_SESSION['nomeusuario']);
          unset($_SESSION['nome']);
          unset($_SESSION['uid']);
          unset($_SESSION['id_unico']);
          unset($_SESSION['idempresa']);
          unset($_SESSION['nomeempresa']);
          unset($_SESSION['fiscal']);
          unset($_SESSION['nfc']);
          unset($_SESSION['crediario']);
          unset($_SESSION['venda_aberto']);
          unset($_SESSION['orcamento']);
          session_destroy();
          session_regenerate_id();

          $this->logged_in = false;
          $this->nomeusuario = "Convidado";
          $this->nivel = 0;
      }

      /**
       * Usuario::getUserInfo()
       * 
       * @param mixed $nomeusuario
       * @return
       */
      public function getUserInfo($nomeusuario)
      {
          $nomeusuario = sanitize($nomeusuario);
          $nomeusuario = self::$db->escape($nomeusuario);

          $sql = "SELECT * FROM " . self::uTable . " WHERE usuario = '" . $nomeusuario . "'";
          $row = self::$db->first($sql);
          if (!$nomeusuario)
              return false;

          return ($row) ? $row : 0;
      }

      /**
       * Usuario::getPINInfo()
       * 
       * @param mixed $pin
       * @return
       */
      public function getPINInfo($pin)
      {
		  $entered_pin = sha1($pin);

          $sql = "SELECT * FROM " . self::uTable . " WHERE pin = '" . $entered_pin . "'";
          $row = self::$db->first($sql);
          if (!$pin)
              return false;

          return ($row) ? $row : 0;
      }

      /**
       * Usuario::checkStatus()
       * 
       * @param mixed $nomeusuario
       * @param mixed $senha
       * @return
       */
      public function checkStatus($nomeusuario, $senha, $pin = false)
      {

          //echo "<script> alert('2');</script>";
		  $nomeusuario = sanitize(strtolower($nomeusuario));
          $nomeusuario = self::$db->escape($nomeusuario);
          $senha = sanitize(strtolower($senha));
		  if($pin) {			  
			  $entered_pin = sha1($pin);
			  $sql = "SELECT active FROM " . self::uTable . " WHERE pin = '" . $entered_pin . "'";
			  $result = self::$db->query($sql);

			  if (self::$db->numrows($result) == 0)
				  return 0;

			  $row = self::$db->fetch($result);

			  switch ($row->active) {
				  case "b":
					  return 1;
					  break;

				  case "n":
					  return 2;
					  break;

				  case "t":
					  return 3;
					  break;

				  case "y":
					  return 5;
					  break;
			  }
		  } else {
			  $sql = "SELECT senha, active FROM " . self::uTable . " WHERE usuario = '" . $nomeusuario . "'";
			  $result = self::$db->query($sql);

			  if (self::$db->numrows($result) == 0)
				  return 0;

			  $row = self::$db->fetch($result);
			  $entered_pass = sha1($senha);

			  switch ($row->active) {
				  case "b":
					  return 1;
					  break;

				  case "n":
					  return 2;
					  break;

				  case "t":
					  return 3;
					  break;

				  case "y" && $entered_pass == $row->senha:
					  return 5;
					  break;
			  }
		  }
      }
	  
      /**
       * Usuario::processarUsuario()
       * 
       * @return
       */
      public function processarUsuario()
      {
          if (!Filter::$id) {
              if (empty($_POST['usuario']))
                  Filter::$msgs['usuario'] = lang('USERNAME_R1');

              if ($value = $this->usuarioExists($_POST['usuario'])) {
                  if ($value == 1)
                      Filter::$msgs['usuario'] = lang('USERNAME_R2');
                  if ($value == 2)
                      Filter::$msgs['usuario'] = lang('USERNAME_R3');
                  if ($value == 3)
                      Filter::$msgs['usuario'] = lang('USERNAME_R4');
              }
          }

          if (empty($_POST['nome']))			  
              Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');

          if (!Filter::$id) {
			  if (empty($_POST['senha']))
                  Filter::$msgs['senha'] = lang('PASSWORD_R1');
			  if (post('senha') != post('confirma'))
                  Filter::$msgs['senha'] = lang('PASSWORD_R3');
          }
			  
		  if (empty($_POST['id_empresa']))
              Filter::$msgs['id_empresa'] = lang('MSG_ERRO_EMPRESA');  
			  
          if (empty(Filter::$msgs)) {
			  
			  $u = sanitize(strtolower(post('usuario')));
			  $u = str_replace(" ",".",$u);

              $data = array(
					'usuario' => $u, 
					'id_empresa' => intval(sanitize(post('id_empresa'))), 
					'id_tabela_ponto' => intval(sanitize(post('id_tabela_ponto'))), 
					'email' => sanitize(post('email')), 
					'nome' => sanitize(post('nome')), 
					'telefone' => sanitize(post('telefone')), 
					'cep' => sanitize(post('cep')), 
					'endereco' => sanitize(post('endereco')), 
					'numero' => sanitize(post('numero')), 
					'complemento' => sanitize(post('complemento')), 
					'bairro' => sanitize(post('bairro')), 
					'cidade' => sanitize(post('cidade')), 
					'estado' => sanitize(post('estado')), 
					'cpf' => sanitize(post('cpf')), 
					'identidade' => sanitize(post('identidade')), 
					'aniversario' => (!empty(post('aniversario')) && post('aniversario')!='0000-00-00') ? dataMySQL(post('aniversario')) : '0000-00-00', 
					'cargo' => sanitize(post('cargo')), 
					'vendedor' => intval(post('vendedor')), 
					'percentual' => converteMoeda(post('percentual')), 
					'observacao' => sanitize(post('observacao')), 
					'salario' => converteMoeda(post('salario')), 
					'transporte' => converteMoeda(post('transporte')), 
					'insalubridade' => converteMoeda(post('insalubridade')), 
					'abono' => converteMoeda(post('abono')), 
					'planodesaude' => converteMoeda(post('planodesaude')),
					'planodependente' => converteMoeda(post('planodependente')),
					'planoextra' => converteMoeda(post('planoextra')),
					'bonus' => converteMoeda(post('bonus')), 
					'lanche' => converteMoeda(post('lanche')), 
					'adiantamento' => intval(sanitize(post('adiantamento'))), 
					'prolabore' => intval(sanitize(post('prolabore'))), 
					'sabado' => intval(sanitize(post('sabado'))), 
					'meta' => intval(sanitize(post('meta'))), 
					'carteira' => intval(post('carteira')), 
					'data_admissao' => (!empty(post('data_admissao')) && post('data_admissao')!='0000-00-00') ? dataMySQL(post('data_admissao')) : '0000-00-00', 
                    'nivel' => (empty(post('nivel'))) ? 0 : intval(post('nivel')), 
                    'banco' => sanitize(post('banco')), 
					'codigo' => sanitize(post('codigo')), 
					'agencia' => sanitize(post('agencia')), 
					'conta' => sanitize(post('conta')), 
					'nome_mae' => sanitize(post('nome_mae')), 
					'escolaridade' => sanitize(post('escolaridade')), 
					'ctps' => sanitize(post('ctps')), 
					'pis' => sanitize(post('pis')), 
					'eleitor' => sanitize(post('eleitor')), 
					'active' => 'y'
			  );

              if (!Filter::$id)
                  $data['created'] = "NOW()";

              if (Filter::$id)
                  $userrow = Registry::get("Core")->getRowById(self::uTable, Filter::$id);

              if (post('senha') != "") {
                  $data['senha'] = sha1(strtolower(post('senha')));
              } else
                  $data['senha'] = $userrow->senha;
              
              $message = (Filter::$id) ? lang('USUARIO_AlTERADO_OK') : lang('USUARIO_ADICIONADO_OK');
              (Filter::$id) ? self::$db->update(self::uTable, $data, "id=" . Filter::$id) : self::$db->insert(self::uTable, $data);
              if (self::$db->affected()) {
                  Filter::msgOk($message, "index.php?do=usuario&acao=listar");
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
      /**
       * Usuario::editarUsuario()
       * 
       * @return
       */
      public function editarUsuario()
      {
          if (empty($_POST['nome']))
              Filter::$msgs['nome'] = lang('MSG_ERRO_NOME');
		  
		  if ($_POST['senha'] != $_POST['confirma'])
               Filter::$msgs['senha'] = lang('PASSWORD_R3');
		   
		  if ($_POST['pin'] != $_POST['confirma_pin'])
               Filter::$msgs['pin'] = lang('PASSWORD_R4');
		   
		  if (!empty($_POST['pin'])) {
			$pin = post('pin');
			if(strlen($pin) == 6) {
				$pin_sha = sha1($pin);
				$sql = "SELECT id FROM usuario WHERE pin = '$pin_sha' ";
				  $row = self::$db->first($sql);			  
				  if ($row)
					  Filter::$msgs['confirma_pin'] = lang('PASSWORD_R5');
			} else {
				Filter::$msgs['confirma_pin'] = lang('PASSWORD_R6');
			}
			$senhas = array('012345', '123456', '234567', '345678', '456789', '567890', '098765', '987654', '876543', '765432', '654321', '543210', '111111', '222222', '333333', '444444', '555555', '666666', '777777', '888888', '999999', '000000', '101010', '202020', '303030', '404040', '505050', '606060', '707070', '808080', '909090', '010101', '020202', '030303', '040404', '050505', '060606', '070707', '080808', '090909', '000001', '000002', '000003', '000004', '000005', '000006', '000007', '000008', '000009', '100000', '200000', '300000', '400000', '500000', '600000', '700000', '800000', '900000');
			if (in_array($pin, $senhas)) { 
				Filter::$msgs['confirma_pin'] = lang('PASSWORD_R7');
			}
		  }

          if (empty(Filter::$msgs)) {

              $data = array(
					'id_empresa' => sanitize(post('id_empresa')), 
					'nome' => sanitize(post('nome')), 
					'cpf' => sanitize(post('cpf')), 
					'identidade' => sanitize(post('identidade')), 
					'telefone' => sanitize(post('telefone')), 
					'aniversario' => dataMySQL(post('aniversario')), 
					'cep' => sanitize(post('cep')), 
					'endereco' => sanitize(post('endereco')), 
					'numero' => sanitize(post('numero')), 
					'complemento' => sanitize(post('complemento')), 
					'bairro' => sanitize(post('bairro')), 
					'cidade' => sanitize(post('cidade')), 
					'estado' => sanitize(post('estado')), 
			  );

              if ($_POST['senha'] != "") {
                  $data['senha'] = sha1(strtolower($_POST['senha']));
              }
			  
              if (!empty($_POST['pin'])) {
                  $data['pin'] = $pin_sha;
              }
			  
			  self::$db->update(self::uTable, $data, "id='" . Filter::$id . "'");
			  
			  $message = lang('USUARIO_AlTERADO_OK');

              if (self::$db->affected()) {
                  Filter::msgOk($message, "logout.php");
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * Usuario::usuarioExists()
       * 
       * @param mixed $usuario
       * @return
       */
      private function usuarioExists($usuario)
      {
          $usuario = sanitize(strtolower($usuario));
          if (strlen(self::$db->escape($usuario)) < 4)
              return 1;

          $sql = self::$db->query("SELECT usuario FROM usuario WHERE usuario = '" . $usuario . "' LIMIT 1");

          $count = self::$db->numrows($sql);

          return ($count > 0) ? 3 : false;
      }

      /**
       * Usuario::emailExists()
       * 
       * @param mixed $email
       * @return
       */
      private function emailExists($email)
      {
		  $email = self::$db->escape($email);
          $sql = self::$db->query("SELECT email FROM usuario WHERE email = '" . sanitize($email) . "' LIMIT 1");

          if (self::$db->numrows($sql) == 1) {
              return true;
          } else
              return false;
      }

      /**
       * Usuario::isValidEmail()
       * 
       * @param mixed $email
       * @return
       */
      private function isValidEmail($email)
      {
          if (function_exists('filter_var')) {
              if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  return true;
              } else
                  return false;
          } else
              return preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $email);
      }

      /**
       * Usuario::getUniqueCode()
       * 
       * @param string $length
       * @return
       */
      private function getUniqueCode($length = "")
      {
          $code = md5(uniqid(rand(), true));
          if ($length != "") {
              return substr($code, 0, $length);
          } else
              return $code;
      }

      /**
       * Usuario::generateRandID()
       * 
       * @return
       */
      private function generateRandID()
      {
          return sha1($this->getUniqueCode(24));
      }
	  
	  /**
       * Usuario::getVendedor()
	   *
       * @return
       */
	  public function getVendedor()
      {
		  $sql = "SELECT u.id, u.nome, u.usuario, u.telefone " 
		  . "\n FROM usuario as u" 
		  . "\n WHERE u.active = 'y' AND u.vendedor = 1 "
		  . "\n ORDER BY u.nome";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Usuario::getUsuariosNivelMaior()
	   *
       * @return
       */
	  public function getUsuariosNivelMaior($pin)
      {
		  $sql = "SELECT u.id, u.nome, u.usuario " 
		  . "\n FROM usuario as u" 
		  . "\n WHERE u.active = 'y' AND nivel >= 7 AND pin = '$pin' " ;
          $row = self::$db->first($sql);

          return ($row) ? 1 : 0;
      }


  }
?>