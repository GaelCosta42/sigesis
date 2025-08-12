<?php
  /**
   * Classe Cotacao
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Cotacao
  {
      const uTable = "cotacao";
      public $did = 0;
      public $cotacaoid = 0;
      private static $db;

      /**
       * Cotacao::__construct()
       * 
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

      /**
       * Cotacao::getCotacoes()
       * 0 - STATUS_SEMCOTACAO
       * 1 - STATUS_ABERTA
       * 2 - STATUS_FORNECEDOR
       * 3 - STATUS_VALIDACAO
       * 4 - STATUS_APROVACAO
       * 5 - STATUS_ENTREGA
       * 6 - STATUS_FINALIZADA
       * 7 - STATUS_CANCELADA
       * 
       * @return
       */
      public function getCotacoes()
      {
		  $sql = "SELECT id, quantidade, valor_total, id_status, usuario_abertura, data_abertura, usuario_fechamento, data_fechamento, usuario_aprovacao, data_aprovacao, usuario_finalizado, data_finalizado, usuario, data FROM cotacao ORDER BY id ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getCotacoesAberto()
       * 
       * @return
       */
      public function getCotacoesAberto()
      {
		  $sql = "SELECT id, quantidade, valor_total, id_status, usuario_abertura, data_abertura, usuario_fechamento, data_fechamento, usuario_aprovacao, data_aprovacao, usuario_finalizado, data_finalizado, usuario, data FROM cotacao WHERE id_status < 6 ORDER BY id ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getCotacaoItens()
       * 
       * @return
       */
      public function getCotacaoItens($id_cotacao, $id_cadastro = false, $id_produto = false)
      {
		  $wfornecedor = ($id_cadastro) ? " AND i.id_cadastro = $id_cadastro " : "";
		  $wproduto = ($id_produto) ? " AND i.id_produto = $id_produto " : "";
		  $sql = "SELECT i.*, p.nome as produto, pf.unidade_compra as unidade, p.valor_custo as valor_produto, p.data_nota as data_produto, f.nome as fornecedor "
			. " FROM cotacao_itens AS i"
			. " LEFT JOIN produto as p ON p.id = i.id_produto"
			. " LEFT JOIN produto_fornecedor AS pf ON pf.id_produto = i.id_produto AND pf.id_cadastro = i.id_cadastro"
			. " LEFT JOIN cadastro as f ON f.id = i.id_cadastro"
			. " WHERE i.inativo = 0 AND i.id_cotacao = $id_cotacao "
			. " $wfornecedor "
			. " $wproduto "
			. " ORDER BY f.nome, p.nome";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getQuantItensFornecedor()
       * 
       * @return
       */
      public function getQuantItensFornecedor($id_cotacao, $id_cadastro)
      {
		  $sql = "SELECT COUNT(1) AS quant "
			. " FROM (SELECT i.id_produto"
			. " FROM cotacao_itens AS i"
			. " WHERE i.inativo = 0 AND i.id_cotacao = $id_cotacao "
			. " AND i.id_cadastro = $id_cadastro "
			. " GROUP BY i.id_produto ) AS quantidade ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quant : 0;
      }

      /**
       * Cotacao::getQuantItensCentroCusto()
       * MELHORIA PARA MUDAR ESTA FUNÇÃO PARA UTILIZAR array_key_exists e array_column
       * @return
       */
      public function getQuantItensCentroCusto($id_cotacao, $id_produto, $id_custo)
      {
		  $sql = "SELECT SUM(i.	quantidade_pedido) AS quant "
			. " FROM pedido_itens AS i"
			. " LEFT JOIN pedido AS p ON p.id = i.id_pedido"
			. " WHERE i.inativo = 0 AND p.inativo = 0 AND i.id_cotacao = $id_cotacao "
			. " AND i.id_produto = $id_produto "
			. " AND p.id_custo = $id_custo ";
          $row = self::$db->first($sql);

          return ($row) ? $row->quant : 0;
      }

      /**
       * Cotacao::getPrazoEntrega()
       * 
       * @return
       */
      public function getPrazoEntrega($id_cotacao, $id_cadastro)
      {
		  $sql = "SELECT MAX(i.data_entrega) AS entrega "
			. " FROM cotacao_itens AS i"
			. " WHERE i.inativo = 0 AND i.id_cotacao = $id_cotacao "
			. " AND i.id_cadastro = $id_cadastro ";
          $row = self::$db->first($sql);

          return ($row) ? $row->entrega : 0;
      }

      /**
       * Cotacao::getCotacaoFornecedores()
       * 
       * @return
       */
      public function getCotacaoFornecedores($id_cotacao, $valido = false, $todos = false)
      {
		  $wvalido = ($valido) ? " AND i.valido = 1 " : "";
		  $wtodos = ($todos) ? "" : " AND i.inativo = 0";
		  $sql = "SELECT i.id_cadastro, COUNT(1) AS quant, SUM(i.quantidade_cotacao*i.valor_unitario) AS valor, f.id, f.nome as fornecedor, f.cpf_cnpj, f.celular, f.celular2, f.email, f.email2, f.contato, i.inativo "
			. " FROM cotacao_itens AS i"
			. " LEFT JOIN cadastro as f ON f.id = i.id_cadastro"
			. " WHERE i.id_cotacao = $id_cotacao $wvalido $wtodos  "
			. " GROUP BY i.id_cadastro"
			. " ORDER BY f.nome";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getCotacaoProdutos()
       * 
       * @return
       */
      public function getCotacaoProdutos($id_cotacao)
      {
		  $sql = "SELECT i.id_produto, p.nome as produto, pf.unidade_compra as unidade "
			. " FROM cotacao_itens AS i"
			. " LEFT JOIN produto as p ON p.id = i.id_produto"
			. " LEFT JOIN produto_fornecedor AS pf ON pf.id_produto = i.id_produto AND pf.id_cadastro = i.id_cadastro"
			. " WHERE i.inativo = 0 AND p.id is not NULL AND i.id_cotacao = $id_cotacao "
			. " GROUP BY i.id_produto"
			. " ORDER BY p.nome";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getPedidosCentroCusto()
       * 
       * @return
       */
      public function getPedidosCentroCusto($id_cotacao, $id_cadastro = false)
      {	
		  $wfornecedor = ($id_cadastro) ? " AND c.id_cadastro = $id_cadastro " : "";
		  $sql = "SELECT cc.id, cc.centro_custo, u.nome as responsavel, u.celular, u.email, COUNT(1) AS quant, SUM(c.valor_unitario*i.quantidade_pedido) AS valor, p.id_custo "
			. " FROM pedido_itens AS i"
			. " LEFT JOIN pedido AS p ON p.id = i.id_pedido"
			. " LEFT JOIN cotacao_itens AS c ON c.id_cotacao = i.id_cotacao AND c.id_produto = i.id_produto"
			. " LEFT JOIN centro_custo as cc ON cc.id = p.id_custo"
			. " LEFT JOIN usuario as u ON u.id = cc.id_responsavel"
			. " WHERE i.inativo = 0 AND i.id_cotacao = $id_cotacao "
			. " AND c.valido = 1 "
			. " $wfornecedor "
			. " GROUP BY p.id_custo"
			. " ORDER BY p.id_custo";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getPedidosFornecedores()
       * 
       * @return
       */
      public function getPedidosFornecedores($id_cotacao, $id_custo = false)
      {
		  $wcusto= ($id_custo) ? " AND p.id_custo = $id_custo " : "";
		  $sql = "SELECT f.id, f.nome as fornecedor, f.cpf_cnpj, f.codigo, f.celular, f.celular2, f.email, f.email2, f.contato, COUNT(1) AS quant, SUM(c.valor_unitario*i.quantidade_pedido) AS valor, c.id_cadastro "
			. " FROM pedido_itens AS i"
			. " LEFT JOIN pedido AS p ON p.id = i.id_pedido"
			. " LEFT JOIN cotacao_itens AS c ON c.id_cotacao = i.id_cotacao AND c.id_produto = i.id_produto "
			. " LEFT JOIN cadastro as f ON f.id = c.id_cadastro"
			. " WHERE i.inativo = 0 AND i.id_cotacao = $id_cotacao "
			. " AND c.valido = 1 AND c.inativo = 0 "
			. " $wcusto "
			. " GROUP BY c.id_cadastro"
			. " ORDER BY f.nome";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getPedidos()
       * 
       * @return
       */
      public function getPedidos()
      {
		  $sql = "SELECT p.*, cc.centro_custo, c.id_status as id_status_cotacao, c.data_abertura "
			. " FROM pedido AS p"
			. " LEFT JOIN centro_custo as cc ON cc.id = p.id_custo"
			. " LEFT JOIN cotacao as c ON c.id = p.id_cotacao"
			. " WHERE p.inativo = 0 AND p.id_status > 0 "
			. " ORDER BY p.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getPedidosAberto()
       * 
       * @return
       */
      public function getPedidosAberto()
      {
		  $sql = "SELECT p.*, cc.centro_custo, c.id_status as id_status_cotacao, c.data_abertura "
			. " FROM pedido AS p"
			. " LEFT JOIN centro_custo as cc ON cc.id = p.id_custo"
			. " LEFT JOIN cotacao as c ON c.id = p.id_cotacao"
			. " WHERE p.inativo = 0 AND p.id_status = 1 AND p.id_cotacao = 0 "
			. " ORDER BY p.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getPedidosPendentes()
       * 
       * @return
       */
      public function getPedidosPendentes()
      {
		  $sql = "SELECT p.*, cc.centro_custo, c.id_status as id_status_cotacao, c.data_abertura "
			. " FROM pedido AS p"
			. " LEFT JOIN centro_custo as cc ON cc.id = p.id_custo"
			. " LEFT JOIN cotacao as c ON c.id = p.id_cotacao"
			. " WHERE p.inativo = 0 AND p.id_status = 0"
			. " ORDER BY p.id DESC";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getPedidosItens()
       * 
       * @return
       */
      public function getPedidosItens($id_pedido)
      {
		  $sql = "SELECT i.*, p.nome as produto, p.unidade "
			. " FROM pedido_itens AS i"
			. " LEFT JOIN produto as p ON p.id = i.id_produto"
			. " WHERE i.id_pedido = '$id_pedido'"
			. " ORDER BY i.id";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getPedidosItensCotacao()
       * 
       * @return
       */
      public function getPedidosItensCotacao($id_cotacao, $id_custo)
      {
		  $sql = "SELECT i.*, p.nome as produto, p.unidade, c.categoria "
			. " FROM pedido_itens AS i"
			. " LEFT JOIN pedido AS pe ON pe.id = i.id_pedido"
			. " LEFT JOIN produto as p ON p.id = i.id_produto"
			. " LEFT JOIN categoria as c ON c.id = p.id_categoria"
			. " WHERE i.id_cotacao = $id_cotacao "
			. " AND pe.id_custo = $id_custo "
			. " ORDER BY pe.id, i.numero_item";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getCentroCustoPedido()
       * 
       * @return
       */
      public function getCentroCustoPedido($id_pedido)
      {
		  $sql = "SELECT c.centro_custo "
			. " FROM pedido_itens AS i"
			. " LEFT JOIN centro_custo as c ON c.id = p.id_custo"
			. " WHERE i.inativo = 0 AND i.id_pedido = '$id_pedido'"
			. " GROUP BY c.centro_custo"
			. " ORDER BY c.centro_custo";
          $row = self::$db->fetch_all($sql);
		  $centro_custos = "";
		  if($row) {
			  foreach ($row as $exrow) {
				$centro_custos .= $exrow->centro_custo." | ";
			  }
		  }
          return $centro_custos;
      }

      /**
       * Cotacao::getCategoriaPedido()
       * 
       * @return
       */
      public function getCategoriaPedido($id_pedido)
      {
		  $sql = "SELECT c.categoria "
			. " FROM pedido_itens AS i"
			. " LEFT JOIN produto as p ON p.id = i.id_produto"
			. " LEFT JOIN categoria as c ON c.id = p.id_categoria"
			. " WHERE i.inativo = 0 AND i.id_pedido = '$id_pedido'"
			. " GROUP BY c.categoria"
			. " ORDER BY c.categoria";
          $row = self::$db->fetch_all($sql);
		  $categorias = "";
		  if($row) {
			  foreach ($row as $exrow) {
				$categorias .= $exrow->categoria." | ";
			  }
		  }
          return $categorias;
      }

      /**
       * Cotacao::getCotacaoAberta()
       * 
       * @return
       */
      public function getCotacaoAberta()
      {
		  $sql = "SELECT c.id "
			. " FROM cotacao AS c"
			. " WHERE c.id_status = 1";
          $row = self::$db->first($sql);

          return ($row) ? $row->id : 0;
      }

      /**
       * Cotacao::getValorFrete()
       * 
       * @return
       */
      public function getValorFrete($id_cotacao = 0, $id_cadastro = 0)
      {
		  $sql = "SELECT f.percentual "
			. " FROM cotacao_frete AS f"
			. " WHERE f.id_cotacao = $id_cotacao "
			. " AND f.id_cadastro = $id_cadastro ";
          $row = self::$db->first($sql);

          return ($row) ? $row->percentual : 0;
      }
	  
      /**
       * Cotacao::salvarCotacao()
       * 0 - STATUS_SEMCOTACAO
       * 1 - STATUS_ABERTA
       * 2 - STATUS_FORNECEDOR
       * 3 - STATUS_VALIDACAO
       * 4 - STATUS_APROVACAO
       * 5 - STATUS_ENTREGA
       * 6 - STATUS_FINALIZADA
       * 7 - STATUS_CANCELADA
       * 
       * @return
       */
      public function salvarCotacao()
      {
		  $id_pedido = post('id_pedido');
		  $total = 0;
          $quant = count($id_pedido);
		  
		  if ($quant == 0)
              Filter::$msgs['quantidade'] = "Quantidade de pedidos igual a 0.";
		
		if (empty(Filter::$msgs)) {
		  
		  if (empty($_POST['id_cotacao'])) {
			  $data_cotacao = array(
				'codigo' => codigo(40),
				'id_status' => 1,
				'usuario_abertura' => session('nomeusuario'),
				'data_abertura' => "NOW()",
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  $id_cotacao = $this->getCotacaoAberta();
			  if(!$id_cotacao)
					$id_cotacao = self::$db->insert("cotacao", $data_cotacao);			  
		  } else {
			  $id_cotacao = post('id_cotacao');
		  }
		  
		  $sql = "SELECT i.id_produto, p.id_cadastro, SUM(i.quantidade_pedido) AS quantidade, DATE_ADD(CURDATE(), INTERVAL p.prazo DAY) AS data_entrega "
				. " FROM pedido_itens AS i, produto_fornecedor AS p, cadastro as f"
				. " WHERE p.id_produto = i.id_produto AND p.id_cadastro = f.id AND i.entrega = 0 AND i.inativo = 0 AND i.id_cotacao = ".$id_cotacao
				. " GROUP BY i.id_produto, p.id_cadastro"
				. " ORDER BY i.id_produto, p.id_cadastro";
		  $retorno_row = self::$db->fetch_all($sql);
		  
		  if($retorno_row) {
			  self::$db->delete("cotacao_itens", "id_cotacao=".$id_cotacao);
			  foreach ($retorno_row as $exrow) {
				$data_itens = array(
					'id_cotacao' => $id_cotacao,
					'id_produto' => $exrow->id_produto,
					'id_cadastro' => $exrow->id_cadastro,
					'quantidade_pedido' => $exrow->quantidade,
					'quantidade_cotacao' => $exrow->quantidade,
					'data_entrega' => $exrow->data_entrega,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
				self::$db->insert("cotacao_itens", $data_itens);				
			  }
			  unset($exrow);
		  }
		  
		  
		  $data_pedido = array(
			'id_cotacao' => $id_cotacao,
			'id_status' => 5,
			'usuario' => session('nomeusuario'),
			'data' => "NOW()"
		  );
		  
		  $data_pedido_item = array(
			'id_cotacao' => $id_cotacao,
			'usuario' => session('nomeusuario'),
			'data' => "NOW()"
		  );
		  
		  for ($i=0; $i<$quant; $i++) 
		  {
			  self::$db->update("pedido", $data_pedido, "id=" . $id_pedido[$i]);
			  self::$db->update("pedido_itens", $data_pedido_item, "entrega = 0 AND inativo = 0 AND id_pedido=" . $id_pedido[$i]);
		  }
		  if (self::$db->affected()) {	                 
			Filter::msgOk(lang('COTACAO_ADICIONADO_OK'), "index.php?do=cotacao&acao=adicionar");
          } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
              print Filter::msgStatus();
      }

      /**
       * Cotacao::getPedidosCotacao()
	   *
       * @return
       */
      public function getPedidosCotacao($id_cotacao)
      {
		  $sql = "SELECT p.*, cc.centro_custo, c.id_status, c.data_abertura "
			. " FROM pedido AS p"
			. " LEFT JOIN centro_custo AS cc ON cc.id = p.id_custo"
			. " LEFT JOIN cotacao as c ON c.id = p.id_cotacao"
			. " WHERE p.inativo = 0 AND p.id_cotacao = $id_cotacao "
			. " ORDER BY p.id";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Cotacao::enviarCotacao()
       * 
       * @return
       */
      public function enviarCotacao()
      {
		$id_cotacao = post('id_cotacao');
		  
		if (empty($_POST['data_fechamento']))
              Filter::$msgs['data_fechamento'] = lang('MSG_ERRO_DATA_FECHAMENTO');	
		
		if (empty(Filter::$msgs)) {
			
		  $data_fechamento = dataMySQL(post('data_fechamento'));
		  
		  $data_cotacao = array(
			'id_status' => 2,
			'observacao' => sanitize(post('observacao')),
			'usuario_fechamento' => session('nomeusuario'),
			'data_fechamento' => $data_fechamento,
			'usuario' => session('nomeusuario'),
			'data' => "NOW()"
		  );
		  self::$db->update("cotacao", $data_cotacao, "id=".$id_cotacao);
		  
		  if (self::$db->affected()) {	
		  
				// COTAÇÃO NÃO DEVERÁ SER ENVIADO POR E-MAIL
				
				// $row_cotacao = Core::getRowById("cotacao", $id_cotacao);
				// $cc = $row_cotacao->codigo;
				// $data_fechamento = exibedataHora($row_cotacao->data_fechamento);
				// $row_empresa = Core::getRowById("empresa", 1);
				// $dataenvio = date('d/m/Y');
				// $row_config = Core::configuracoes();
				// $site_url = $row_config->site_url;
				// $site_sistema = $row_config->site_sistema;
				// $site_email = $row_config->site_email;
				// $site_empresa = $row_config->empresa;
				// $mailer = $row_config->mailer;
				// $smtp_host = $row_config->smtp_host;
				// $smtp_port = $row_config->smtp_port;
				// $smtp_user = $row_config->smtp_user;
				// $smtp_pass = $row_config->smtp_pass;
				
				// require_once(BASEPATH ."lib/phpmailer/PHPMailerAutoload.php");

				// $mail             = new PHPMailer();
				// $mail->setLanguage('pt_br', BASEPATH .'lib/phpmailer/language');
				// $mail->CharSet = 'utf-8';
				
				// $mail->IsSMTP();
				// $mail->SMTPAuth   = true;       
				// $mail->SMTPSecure = "ssl";  
				// $mail->Host       = $smtp_host;  
				// $mail->Port       = $smtp_port;         
				// $mail->Username   = $smtp_user;         
				// $mail->Password   = $smtp_pass; 
				
				// $retorno_row = $this->getCotacaoFornecedores($id_cotacao);
				$retorno_row = null;
				if($retorno_row) {
					foreach ($retorno_row as $exrow) {
						$co = $exrow->codigo;
						$titulo = $exrow->fornecedor;
						$celular = ($exrow->celular) ? telefoneSMS($exrow->celular) : telefoneSMS($exrow->celular2);
						$celular2 = ($exrow->celular) ? telefoneSMS($exrow->celular2) : "";
						$email = ($exrow->email) ? $exrow->email : $exrow->email2;
						$email2 = ($exrow->email) ? $exrow->email2 : "";
						$contato = ($exrow->contato) ? $exrow->contato : "Prezado(a)";
						$link = urlCurta("http://".$site_sistema."/cotacao/index.php?cc=".$cc."&co=".$co);
						$row_produtos = $this->getCotacaoItens($id_cotacao, $exrow->id_cadastro);
						
						if($celular)
						{
							$msg_cel = str_replace("[DATA]", $data_fechamento, lang('COTACAO_CELULAR'));
							$msg_cel = str_replace("[LINK]", $link, $msg_cel);
							$retorno_sms = ($celular) ? enviarSMS($msg_cel, $celular) : "";
							$retorno_sms .= ($celular2) ? enviarSMS($msg_cel, $celular2) : "";
							$datamensagem = array(
								'id_cotacao' => $id_cotacao, 
								'id_cadastro' => $exrow->id_cadastro, 
								'celular' => $celular." ".$celular2, 
								'mensagem' => $msg_cel, 
								'retorno' => $retorno_sms,
								'data' => "NOW()"
							);
							self::$db->insert("sms", $datamensagem);
						}
						
						if($email)
						{
							$msg_body = str_replace("[EMPRESA]", $row_empresa->nome, lang('COTACAO_MSG'));
							$msg_body = str_replace("[CONTATO]", $contato, $msg_body);
							$msg_body = str_replace("[DATA]", $data_fechamento, $msg_body);
							$msg_body = str_replace("[LINK]", $link, $msg_body);
							
							ob_start();
							require(BASEPATH . 'email/cotacao.tpl.php');
							$html_message = ob_get_contents();
							ob_end_clean();   

							$mail->AddAddress($email,$contato);
							$mail->addCC($email2,$contato);
							$mail->addReplyTo($row_empresa->email, $row_empresa->nome);
							$mail->From       = $row_empresa->email;
							$mail->FromName   = $row_empresa->nome;
							$mail->Subject    = lang('COTACAO_ONLINE')." - ".$titulo;
							$mail->Body		  = $msg_body;
							$mail->AltBody    = $msg_body;
							$mail->WordWrap   = 50; // set word wrap

							$mail->IsHTML(true); // send as HTML

							$mail->MsgHTML($html_message); //Colocar a função de utf8_decode faz aparecer uma "?" na mensagem enviada.
							if(!$mail->Send()) {
							  Filter::msgAlert(str_replace("[ERRO]", $mail->ErrorInfo, lang('COTACAO_NOEMAIL')));
							}
							$mail->clearAddresses();
						}
					}
					unset($exrow);
				}
				Filter::msgOk(lang('COTACAO_ENVIAR_OK'), "index.php?do=cotacao&acao=visualizar&id=".$id_cotacao);
          } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
              print Filter::msgStatus();
      }	  

      /**
       * Cotacao::validaCotacao()
       * 
       * @return
       */
      public function validaCotacao($id_cotacao)
      {
		  $sql = "SELECT c.id "
			. " FROM cotacao AS c"
			. " WHERE c.id_status = 2 AND c.data_fechamento > CURDATE() AND c.id = $id_cotacao ";
          $row = self::$db->first($sql);

          return ($row and $row->id > 0) ? 1 : 0;
      } 

      /**
       * Cotacao::validaMenorValor()
       * 
       * @return
       */
      public function validaMenorValor($id_cotacao, $id_produto, $valor_unitario, $id)
      {
		  $sql = "SELECT i.id "
			. " FROM cotacao_itens AS i"
			. " WHERE i.inativo = 0 AND i.id_cotacao = $id_cotacao AND i.id_produto = $id_produto AND i.valido = 1 ";
          $row = self::$db->first($sql);
		  
		  if($row) {
			  return ($row->id == $id) ? true : false;
		  } else {	
		  
			  $sql = "SELECT MIN(i.valor_unitario) AS menor"
				. " FROM cotacao_itens AS i"
				. " WHERE i.inativo = 0 AND i.id_cotacao = $id_cotacao AND i.id_produto = $id_produto AND i.valor_unitario > 0 ";
			  $row = self::$db->first($sql);

			  return ($row) ? ($row->menor == $valor_unitario) : false;
		  }
      }
	  
      /**
       * Cotacao::validarCotacao()
       * 
       * @return
       */
      public function validarCotacao()
      {
		$id_cotacao = post('id_cotacao');
		
		if (empty(Filter::$msgs)) {
			  
			  $data_cotacao = array(
				'id_status' => 4,
				'usuario_fechamento' => session('nomeusuario'),
				'data_fechamento' => "NOW()",
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  self::$db->update("cotacao", $data_cotacao, "id=".$id_cotacao);
			  
			  
		  if (self::$db->affected()) {	                 
			Filter::msgOk(lang('COTACAO_VALIDADA_OK'), "index.php?do=cotacao&acao=visualizar&id=".$id_cotacao);
          } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
              print Filter::msgStatus();
      }
	  
      /**
       * Cotacao::reabrirCotacao()
       * 
       * @return
       */
      public function reabrirCotacao()
      {
		$id_cotacao = post('id_cotacao');
		  
		if (empty($_POST['data_fechamento']))
              Filter::$msgs['data_fechamento'] = lang('MSG_ERRO_DATA_FECHAMENTO');	
		
		if (empty(Filter::$msgs)) {
			
		  $data_fechamento = dataMySQL(post('data_fechamento'));
		  
		  $data_cotacao = array(
			'id_status' => 2,
			'observacao' => sanitize(post('observacao')),
			'usuario_fechamento' => session('nomeusuario'),
			'data_fechamento' => $data_fechamento,
			'usuario' => session('nomeusuario'),
			'data' => "NOW()"
		  );
		  self::$db->update("cotacao", $data_cotacao, "id=".$id_cotacao);
			  
			  
		  if (self::$db->affected()) {	                 
			Filter::msgOk(lang('COTACAO_ADICIONADO_OK'), "index.php?do=cotacao&acao=visualizar&id=".$id_cotacao);
          } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
              print Filter::msgStatus();
      }
	  
      /**
       * Cotacao::aprovarCotacao()
       * 
       * @return
       */
      public function aprovarCotacao()
      {
		$id_cotacao = post('id_cotacao');
		
		if (empty(Filter::$msgs)) {
			$where_id = '';
			$where_id_produto = '';
			$vl_total = 0;
			$quant_total = 0;
			$retorno_row = $this->getCotacaoItens($id_cotacao);
			if($retorno_row) {
				foreach ($retorno_row as $exrow) {
					if($exrow->valor_unitario > 0 or $exrow->valido == 1) {
						if($this->validaMenorValor($id_cotacao, $exrow->id_produto, $exrow->valor_unitario, $exrow->id))
						{
							$quant_total++;
							$vl_total += $exrow->quantidade_cotacao*$exrow->valor_unitario;
							$where_id_produto .= $exrow->id_produto.',';							
							$data_itens = array(
								'valido' => 1
							);
							self::$db->update("cotacao_itens", $data_itens, "id=".$exrow->id);
						}
					}
				}
				$where_id = substr($where_id, 0, -1);
				$where_id_produto = substr($where_id_produto, 0, -1);
				unset($exrow);
				$data_itens = array(
					'valido' => 2
				);
				self::$db->update("cotacao_itens", $data_itens, "id_cotacao = $id_cotacao AND valido = 0 AND id_produto in (".$where_id_produto.")");
			}
		  
			$data_cotacao = array(
				'quantidade' => $quant_total,
				'valor_total' => $vl_total,
				'id_status' => 5,
				'usuario_aprovacao' => session('nomeusuario'),
				'data_aprovacao' => "NOW()",
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			);
			self::$db->update("cotacao", $data_cotacao, "id=".$id_cotacao);
			
			if (self::$db->affected()) {	
		  
		  
				// COTAÇÃO NÃO DEVERÁ SER ENVIADO POR E-MAIL
				// $row_cotacao = Core::getRowById("cotacao", $id_cotacao);
				// $cc = $row_cotacao->codigo;
				// $data_aprovacao = exibedataHora($row_cotacao->data_aprovacao);
				// $row_empresa = Core::getRowById("empresa", 1);
				// $dataenvio = date('d/m/Y');
				// $row_config = Core::configuracoes();
				// $site_url = $row_config->site_url;
				// $site_sistema = $row_config->site_sistema;
				// $site_email = $row_config->site_email;
				// $site_empresa = $row_config->empresa;
				// $mailer = $row_config->mailer;
				// $smtp_host = $row_config->smtp_host;
				// $smtp_port = $row_config->smtp_port;
				// $smtp_user = $row_config->smtp_user;
				// $smtp_pass = $row_config->smtp_pass;
				
				// require_once(BASEPATH ."lib/phpmailer/PHPMailerAutoload.php");

				// $mail             = new PHPMailer();
				// $mail->setLanguage('pt_br', BASEPATH .'lib/phpmailer/language');
				// $mail->CharSet = 'utf-8';
				
				// $mail->IsSMTP();
				// $mail->SMTPAuth   = true;       
				// $mail->SMTPSecure = "ssl";  
				// $mail->Host       = $smtp_host;  
				// $mail->Port       = $smtp_port;         
				// $mail->Username   = $smtp_user;         
				// $mail->Password   = $smtp_pass; 
				
				// $retorno_row = $this->getCotacaoFornecedores($id_cotacao);
				$retorno_row = null;
				if($retorno_row) {
					foreach ($retorno_row as $exrow) {
						$co = $exrow->codigo;
						$titulo = $exrow->fornecedor;
						$celular = ($exrow->celular) ? telefoneSMS($exrow->celular) : telefoneSMS($exrow->celular2);
						$celular2 = ($exrow->celular) ? telefoneSMS($exrow->celular2) : "";
						$email = ($exrow->email) ? $exrow->email : $exrow->email2;
						$email2 = ($exrow->email) ? $exrow->email2 : "";
						$contato = ($exrow->contato) ? $exrow->contato : "Prezado(a)";
						$link = urlCurta("http://".$site_sistema."/fornecedor/index.php?cc=".$cc."&co=".$co);
						$row_pedidos = $this->getPedidosCentroCusto($id_cotacao, $exrow->id_cadastro);
						
						if($celular)
						{
							$msg_cel = str_replace("[DATA]", $data_aprovacao, lang('COTACAO_CELULAR_APROVADA'));
							$msg_cel = str_replace("[LINK]", $link, $msg_cel);
							$retorno_sms = ($celular) ? enviarSMS($msg_cel, $celular) : "";
							$retorno_sms .= ($celular2) ? enviarSMS($msg_cel, $celular2) : "";
							$datamensagem = array(
								'id_cotacao' => $id_cotacao, 
								'id_cadastro' => $exrow->id_cadastro, 
								'celular' => $celular." ".$celular2, 
								'mensagem' => $msg_cel, 
								'retorno' => $retorno_sms,
								'data' => "NOW()"
							);
							self::$db->insert("sms", $datamensagem);
						}
						
						if($email)
						{
							$msg_body = str_replace("[EMPRESA]", $row_empresa->nome, lang('COTACAO_MSG_APROVADA'));
							$msg_body = str_replace("[CONTATO]", $contato, $msg_body);
							$msg_body = str_replace("[DATA]", $data_aprovacao, $msg_body);
							$msg_body = str_replace("[LINK]", $link, $msg_body);
							
							ob_start();
							require(BASEPATH . 'email/fornecedor.tpl.php');
							$html_message = ob_get_contents();
							ob_end_clean();   

							$mail->AddAddress($email,$contato);
							$mail->addCC($email2,$contato);
							$mail->addReplyTo($row_empresa->email, $row_empresa->nome);
							$mail->From       = $row_empresa->email;
							$mail->FromName   = $row_empresa->nome;
							$mail->Subject    = lang('COTACAO_ONLINE_APROVADA')." - ".$titulo;
							$mail->Body		  = $msg_body;
							$mail->AltBody    = $msg_body;
							$mail->WordWrap   = 50; // set word wrap

							$mail->IsHTML(true); // send as HTML

							$mail->MsgHTML($html_message); //Colocar a função de utf8_decode faz aparecer uma "?" na mensagem enviada.
							if(!$mail->Send()) {
							  Filter::msgAlert(str_replace("[ERRO]", $mail->ErrorInfo, lang('COTACAO_NOEMAIL')));
							}
							$mail->clearAddresses();
						}
					}
					unset($exrow);
				}
				
				// $retorno_row = $this->getPedidosCentroCusto($id_cotacao);
				if($retorno_row) {
					foreach ($retorno_row as $exrow) {
						$lo = $exrow->id_custo;
						$titulo = $exrow->centro_custo;
						$celular = telefoneSMS($exrow->celular);
						$email = $exrow->email;
						$contato = ($exrow->responsavel) ? $exrow->responsavel : "Prezado(a)";
						$link = urlCurta("http://".$site_sistema."/centro_custo/index.php?lo=".$lo."&cc=".$cc);
						$row_pedidos = $this->getPedidosFornecedores($id_cotacao, $lo);
						
						if($celular)
						{
							$msg_cel = str_replace("[DATA]", $data_aprovacao, lang('COTACAO_CELULAR_APROVADA'));
							$msg_cel = str_replace("[LINK]", $link, $msg_cel);
							$retorno_sms = ($celular) ? enviarSMS($msg_cel, $celular) : "";
							$datamensagem = array(
								'id_cotacao' => $id_cotacao, 
								'id_cadastro' => $exrow->id_cadastro, 
								'celular' => $celular." ".$celular2, 
								'mensagem' => $msg_cel, 
								'retorno' => $retorno_sms,
								'data' => "NOW()"
							);
							self::$db->insert("sms", $datamensagem);
						}
						
						if($email)
						{
							$msg_body = str_replace("[EMPRESA]", $row_empresa->nome, lang('COTACAO_MSG_APROVADA'));
							$msg_body = str_replace("[CONTATO]", $contato, $msg_body);
							$msg_body = str_replace("[DATA]", $data_aprovacao, $msg_body);
							$msg_body = str_replace("[LINK]", $link, $msg_body);
							
							ob_start();
							require(BASEPATH . 'email/pedido.tpl.php');
							$html_message = ob_get_contents();
							ob_end_clean();   

							$mail->AddAddress($email,$contato);
							$mail->addReplyTo($row_empresa->email, $row_empresa->nome);
							$mail->From       = $row_empresa->email;
							$mail->FromName   = $row_empresa->nome;
							$mail->Subject    = lang('COTACAO_ONLINE_APROVADA')." - ".$titulo;
							$mail->Body		  = $msg_body;
							$mail->AltBody    = $msg_body;
							$mail->WordWrap   = 50; // set word wrap

							$mail->IsHTML(true); // send as HTML

							$mail->MsgHTML($html_message); //Colocar a função de utf8_decode faz aparecer uma "?" na mensagem enviada.
							if(!$mail->Send()) {
							  Filter::msgAlert(str_replace("[ERRO]", $mail->ErrorInfo, lang('COTACAO_NOEMAIL')));
							}
							$mail->clearAddresses();
						}
					}
					unset($exrow);
				}
				Filter::msgOk(lang('COTACAO_APROVADA_OK'), "index.php?do=cotacao&acao=visualizar&id=".$id_cotacao);
			} else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
              print Filter::msgStatus();
	  }  
	  
      /**
       * Cotacao::enviarEmailAprovacao()
       * 
       * @return
       */
      public function enviarEmailAprovacao()
      {
		$id_cotacao = post('enviarEmailAprovacao');
		
		if (empty(Filter::$msgs)) {		  
				$row_cotacao = Core::getRowById("cotacao", $id_cotacao);
				$cc = $row_cotacao->codigo;
				$data_aprovacao = exibedataHora($row_cotacao->data_aprovacao);
				$row_empresa = Core::getRowById("empresa", 1);
				$dataenvio = date('d/m/Y');
				$row_config = Core::configuracoes();
				$site_url = $row_config->site_url;
				$site_sistema = $row_config->site_sistema;
				$site_email = $row_config->site_email;
				$site_empresa = $row_config->empresa;
				$mailer = $row_config->mailer;
				$smtp_host = $row_config->smtp_host;
				$smtp_port = $row_config->smtp_port;
				$smtp_user = $row_config->smtp_user;
				$smtp_pass = $row_config->smtp_pass;
				
				require_once(BASEPATH ."lib/phpmailer/PHPMailerAutoload.php");

				$mail             = new PHPMailer();
				$mail->setLanguage('pt_br', BASEPATH .'lib/phpmailer/language');
				$mail->CharSet = 'utf-8';
				
				$mail->IsSMTP();
				$mail->SMTPAuth   = true;       
				$mail->SMTPSecure = "ssl";  
				$mail->Host       = $smtp_host;  
				$mail->Port       = $smtp_port;         
				$mail->Username   = $smtp_user;         
				$mail->Password   = $smtp_pass; 
				
				$retorno_row = $this->getCotacaoFornecedores($id_cotacao);
				if($retorno_row) {
					foreach ($retorno_row as $exrow) {
						$co = $exrow->codigo;
						$titulo = $exrow->fornecedor;
						$celular = ($exrow->celular) ? telefoneSMS($exrow->celular) : telefoneSMS($exrow->celular2);
						$celular2 = ($exrow->celular) ? telefoneSMS($exrow->celular2) : "";
						$email = ($exrow->email) ? $exrow->email : $exrow->email2;
						$email2 = ($exrow->email) ? $exrow->email2 : "";
						$contato = ($exrow->contato) ? $exrow->contato : "Prezado(a)";
						$link = urlCurta("http://".$site_sistema."/fornecedor/index.php?cc=".$cc."&co=".$co);
						$row_pedidos = $this->getPedidosCentroCusto($id_cotacao, $exrow->id_cadastro);
						
						if($celular)
						{
							$msg_cel = str_replace("[DATA]", $data_aprovacao, lang('COTACAO_CELULAR_APROVADA'));
							$msg_cel = str_replace("[LINK]", $link, $msg_cel);
							$retorno_sms = ($celular) ? enviarSMS($msg_cel, $celular) : "";
							$retorno_sms .= ($celular2) ? enviarSMS($msg_cel, $celular2) : "";
							$datamensagem = array(
								'id_cotacao' => $id_cotacao, 
								'id_cadastro' => $exrow->id_cadastro, 
								'celular' => $celular." ".$celular2, 
								'mensagem' => $msg_cel, 
								'retorno' => $retorno_sms,
								'data' => "NOW()"
							);
							self::$db->insert("sms", $datamensagem);
						}
						
						if($email)
						{
							$msg_body = str_replace("[EMPRESA]", $row_empresa->nome, lang('COTACAO_MSG_APROVADA'));
							$msg_body = str_replace("[CONTATO]", $contato, $msg_body);
							$msg_body = str_replace("[DATA]", $data_aprovacao, $msg_body);
							$msg_body = str_replace("[LINK]", $link, $msg_body);
							
							ob_start();
							require(BASEPATH . 'email/fornecedor.tpl.php');
							$html_message = ob_get_contents();
							ob_end_clean();   

							$mail->AddAddress($email,$contato);
							$mail->addCC($email2,$contato);
							$mail->addReplyTo($row_empresa->email, $row_empresa->nome);
							$mail->From       = $row_empresa->email;
							$mail->FromName   = $row_empresa->nome;
							$mail->Subject    = lang('COTACAO_ONLINE_APROVADA')." - ".$titulo;
							$mail->Body		  = $msg_body;
							$mail->AltBody    = $msg_body;
							$mail->WordWrap   = 50; // set word wrap

							$mail->IsHTML(true); // send as HTML

							$mail->MsgHTML($html_message); //Colocar a função de utf8_decode faz aparecer uma "?" na mensagem enviada.
							if(!$mail->Send()) {
							  Filter::msgAlert(str_replace("[ERRO]", $mail->ErrorInfo, lang('COTACAO_NOEMAIL')));
							}
							$mail->clearAddresses();
						}
					}
					unset($exrow);
				}
				
				$retorno_row = $this->getPedidosCentroCusto($id_cotacao);
				if($retorno_row) {
					foreach ($retorno_row as $exrow) {
						$lo = $exrow->id_custo;
						$titulo = $exrow->centro_custo;
						$celular = telefoneSMS($exrow->celular);
						$email = $exrow->email;
						$contato = ($exrow->responsavel) ? $exrow->responsavel : "Prezado(a)";
						$link = urlCurta("http://".$site_sistema."/centro_custo/index.php?lo=".$lo."&cc=".$cc);
						$row_pedidos = $this->getPedidosFornecedores($id_cotacao, $lo);
						
						if($celular)
						{
							$msg_cel = str_replace("[DATA]", $data_aprovacao, lang('COTACAO_CELULAR_APROVADA'));
							$msg_cel = str_replace("[LINK]", $link, $msg_cel);
							$retorno_sms = ($celular) ? enviarSMS($msg_cel, $celular) : "";
							$datamensagem = array(
								'id_cotacao' => $id_cotacao, 
								'id_cadastro' => $exrow->id_cadastro, 
								'celular' => $celular." ".$celular2, 
								'mensagem' => $msg_cel, 
								'retorno' => $retorno_sms,
								'data' => "NOW()"
							);
							self::$db->insert("sms", $datamensagem);
						}
						
						if($email)
						{
							$msg_body = str_replace("[EMPRESA]", $row_empresa->nome, lang('COTACAO_MSG_APROVADA'));
							$msg_body = str_replace("[CONTATO]", $contato, $msg_body);
							$msg_body = str_replace("[DATA]", $data_aprovacao, $msg_body);
							$msg_body = str_replace("[LINK]", $link, $msg_body);
							
							ob_start();
							require(BASEPATH . 'email/pedido.tpl.php');
							$html_message = ob_get_contents();
							ob_end_clean();   

							$mail->AddAddress($email,$contato);
							$mail->addReplyTo($row_empresa->email, $row_empresa->nome);
							$mail->From       = $row_empresa->email;
							$mail->FromName   = $row_empresa->nome;
							$mail->Subject    = lang('COTACAO_ONLINE_APROVADA')." - ".$titulo;
							$mail->Body		  = $msg_body;
							$mail->AltBody    = $msg_body;
							$mail->WordWrap   = 50; // set word wrap

							$mail->IsHTML(true); // send as HTML

							$mail->MsgHTML($html_message); //Colocar a função de utf8_decode faz aparecer uma "?" na mensagem enviada.
							if(!$mail->Send()) {
							  Filter::msgAlert(str_replace("[ERRO]", $mail->ErrorInfo, lang('COTACAO_NOEMAIL')));
							}
							$mail->clearAddresses();
						}
					}
					unset($exrow);
				}
			} else
              print Filter::msgStatus();
	  }   
	  
      /**
       * Cotacao::enviarEmailPedido()
       * 
       * @return
       */
      public function enviarEmailFornecedor()
      {
		$id_cotacao = post('enviarEmailFornecedor');
		$id = post('id');
		
		if (empty(Filter::$msgs)) {		  
				$row_cotacao = Core::getRowById("cotacao", $id_cotacao);
				$cc = $row_cotacao->codigo;
				$data_aprovacao = exibedataHora($row_cotacao->data_aprovacao);
				$row_empresa = Core::getRowById("empresa", 1);
				$dataenvio = date('d/m/Y');
				$row_config = Core::configuracoes();
				$site_url = $row_config->site_url;
				$site_sistema = $row_config->site_sistema;
				$site_email = $row_config->site_email;
				$site_empresa = $row_config->empresa;
				$mailer = $row_config->mailer;
				$smtp_host = $row_config->smtp_host;
				$smtp_port = $row_config->smtp_port;
				$smtp_user = $row_config->smtp_user;
				$smtp_pass = $row_config->smtp_pass;
				
				require_once(BASEPATH ."lib/phpmailer/PHPMailerAutoload.php");

				$mail             = new PHPMailer();
				$mail->setLanguage('pt_br', BASEPATH .'lib/phpmailer/language');
				$mail->CharSet = 'utf-8';
				
				$mail->IsSMTP();
				$mail->SMTPAuth   = true;       
				$mail->SMTPSecure = "ssl";  
				$mail->Host       = $smtp_host;  
				$mail->Port       = $smtp_port;         
				$mail->Username   = $smtp_user;         
				$mail->Password   = $smtp_pass; 
							
				$exrow = Core::getRowById("cadastro", $id);
				$co = $exrow->codigo;
				$titulo = $exrow->nome;
				$celular = ($exrow->celular) ? telefoneSMS($exrow->celular) : telefoneSMS($exrow->celular2);
				$celular2 = ($exrow->celular) ? telefoneSMS($exrow->celular2) : "";
				$email = ($exrow->email) ? $exrow->email : $exrow->email2;
				$email2 = ($exrow->email) ? $exrow->email2 : "";
				$contato = ($exrow->contato) ? $exrow->contato : "Prezado(a)";
				$link = urlCurta("http://".$site_sistema."/fornecedor/index.php?cc=".$cc."&co=".$co);
				$row_pedidos = $this->getPedidosCentroCusto($id_cotacao, $exrow->id_cadastro);
						
				if($celular)
				{
					$msg_cel = str_replace("[DATA]", $data_aprovacao, lang('COTACAO_CELULAR_APROVADA'));
					$msg_cel = str_replace("[LINK]", $link, $msg_cel);
					$retorno_sms = ($celular) ? enviarSMS($msg_cel, $celular) : "";
					$retorno_sms .= ($celular2) ? enviarSMS($msg_cel, $celular2) : "";
					$datamensagem = array(
						'id_cotacao' => $id_cotacao, 
						'id_cadastro' => $exrow->id_cadastro, 
						'celular' => $celular." ".$celular2, 
						'mensagem' => $msg_cel, 
						'retorno' => $retorno_sms,
						'data' => "NOW()"
					);
					self::$db->insert("sms", $datamensagem);
				}
				
				if($email)
				{
					$msg_body = str_replace("[EMPRESA]", $row_empresa->nome, lang('COTACAO_MSG_APROVADA'));
					$msg_body = str_replace("[CONTATO]", $contato, $msg_body);
					$msg_body = str_replace("[DATA]", $data_aprovacao, $msg_body);
					$msg_body = str_replace("[LINK]", $link, $msg_body);
					
					ob_start();
					require(BASEPATH . 'email/fornecedor.tpl.php');
					$html_message = ob_get_contents();
					ob_end_clean();   
					$mail->AddAddress($email,$contato);
					$mail->addCC($email2,$contato);
					$mail->addReplyTo($row_empresa->email, $row_empresa->nome);
					$mail->From       = $row_empresa->email;
					$mail->FromName   = $row_empresa->nome;
							$mail->Subject    = lang('COTACAO_ONLINE_APROVADA')." - ".$titulo;
					$mail->Body		  = $msg_body;
					$mail->AltBody    = $msg_body;
					$mail->WordWrap   = 50; // set word wrap

					$mail->IsHTML(true); // send as HTML

					$mail->MsgHTML($html_message); //Colocar a função de utf8_decode faz aparecer uma "?" na mensagem enviada.
					if(!$mail->Send()) {
					  Filter::msgAlert(str_replace("[ERRO]", $mail->ErrorInfo, lang('COTACAO_NOEMAIL')));
					}
					$mail->clearAddresses();
				}
			} else
              print Filter::msgStatus();
	  }	     
	  
      /**
       * Cotacao::enviarEmailCentroCusto()
       * 
       * @return
       */
      public function enviarEmailCentroCusto()
      {
		$id_cotacao = post('enviarEmailCentroCusto');
		$id = post('id');
		
		if (empty(Filter::$msgs)) {		  
				$row_cotacao = Core::getRowById("cotacao", $id_cotacao);
				$cc = $row_cotacao->codigo;
				$data_aprovacao = exibedataHora($row_cotacao->data_aprovacao);
				$row_empresa = Core::getRowById("empresa", 1);
				$dataenvio = date('d/m/Y');
				$row_config = Core::configuracoes();
				$site_url = $row_config->site_url;
				$site_sistema = $row_config->site_sistema;
				$site_email = $row_config->site_email;
				$site_empresa = $row_config->empresa;
				$mailer = $row_config->mailer;
				$smtp_host = $row_config->smtp_host;
				$smtp_port = $row_config->smtp_port;
				$smtp_user = $row_config->smtp_user;
				$smtp_pass = $row_config->smtp_pass;
				
				require_once(BASEPATH ."lib/phpmailer/PHPMailerAutoload.php");

				$mail             = new PHPMailer();
				$mail->setLanguage('pt_br', BASEPATH .'lib/phpmailer/language');
				$mail->CharSet = 'utf-8';
				
				$mail->IsSMTP();
				$mail->SMTPAuth   = true;       
				$mail->SMTPSecure = "ssl";  
				$mail->Host       = $smtp_host;  
				$mail->Port       = $smtp_port;         
				$mail->Username   = $smtp_user;         
				$mail->Password   = $smtp_pass; 
							
				$exrow = Core::getRowById("centro_custo", $id);
				$exrow = $this->getDetalhesCentroCusto($id);
				$lo = $exrow->id;
				$titulo = $exrow->centro_custo;
				$celular = telefoneSMS($exrow->celular);
				$email = $exrow->email;
				$contato = ($exrow->responsavel) ? $exrow->responsavel : "Prezado(a)";
				$link = urlCurta("http://".$site_sistema."/loja/index.php?lo=".$lo."&cc=".$cc);
				$row_pedidos = $this->getPedidosFornecedores($id_cotacao, $lo);
				
				if($celular)
				{
					$msg_cel = str_replace("[DATA]", $data_aprovacao, lang('COTACAO_CELULAR_APROVADA'));
					$msg_cel = str_replace("[LINK]", $link, $msg_cel);
					$retorno_sms = ($celular) ? enviarSMS($msg_cel, $celular) : "";
					$datamensagem = array(
						'id_cotacao' => $id_cotacao, 
						'id_cadastro' => $exrow->id_cadastro, 
						'celular' => $celular, 
						'mensagem' => $msg_cel, 
						'retorno' => $retorno_sms,
						'data' => "NOW()"
					);
					self::$db->insert("sms", $datamensagem);
				}	
				if($email)
				{
					$msg_body = str_replace("[EMPRESA]", $row_empresa->nome, lang('COTACAO_MSG_APROVADA'));
					$msg_body = str_replace("[CONTATO]", $contato, $msg_body);
					$msg_body = str_replace("[DATA]", $data_aprovacao, $msg_body);
					$msg_body = str_replace("[LINK]", $link, $msg_body);
						
					ob_start();
					require(BASEPATH . 'email/pedido.tpl.php');
					$html_message = ob_get_contents();
					ob_end_clean();   
					$mail->AddAddress($email,$contato);
					$mail->addReplyTo($row_empresa->email, $row_empresa->nome);
					$mail->From       = $row_empresa->email;
					$mail->FromName   = $row_empresa->nome;
					$mail->Subject    = lang('COTACAO_ONLINE_APROVADA')." - ".$titulo;
					$mail->Body		  = $msg_body;
					$mail->AltBody    = $msg_body;
					$mail->WordWrap   = 50; // set word wrap

					$mail->IsHTML(true); // send as HTML
					$mail->MsgHTML($html_message); //Colocar a função de utf8_decode faz aparecer uma "?" na mensagem enviada.
					if(!$mail->Send()) {
					  Filter::msgAlert(str_replace("[ERRO]", $mail->ErrorInfo, lang('COTACAO_NOEMAIL')));
					} else {
						Filter::msgOk(lang('ENVIADO_EMAIL'));
					}
					$mail->clearAddresses();
				}
			} else
              print Filter::msgStatus();
	  }	  
	  
      /**
       * Cotacao::finalizarCotacao()
       * 
       * @return
       */
      public function finalizarCotacao()
      {
		$id_cotacao = post('id_cotacao');
		
		if (empty(Filter::$msgs)) {
			  
			  $data_cotacao = array(
				'id_status' => 6,
				'usuario_finalizado' => session('nomeusuario'),
				'data_finalizado' => "NOW()",
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  self::$db->update("cotacao", $data_cotacao, "id=".$id_cotacao);
			  
			  
		  if (self::$db->affected()) {	                 
			Filter::msgOk(lang('COTACAO_FINALIZADA_OK'), "index.php?do=cotacao&acao=visualizar&id=".$id_cotacao);
          } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
              print Filter::msgStatus();
      }
	  
      /**
       * Cotacao::cancelarCotacao()
       * 
       * @return
       */
      public function cancelarCotacao()
      {
		$id_cotacao = post('id_cotacao');
		
		if (empty(Filter::$msgs)) {
			  
			  $data = array(
				'id_cotacao' => 0,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  self::$db->update("pedido", $data, "id_cotacao=".$id_cotacao);
			  self::$db->update("pedido_itens", $data, "id_cotacao=".$id_cotacao);			  
			  
			  $data_cotacao = array(
				'id_status' => 7,
				'usuario' => session('nomeusuario'),
				'data' => "NOW()"
			  );
			  self::$db->update("cotacao", $data_cotacao, "id=".$id_cotacao);
			  
			  
		  if (self::$db->affected()) {	                 
			Filter::msgOk(lang('COTACAO_CANCELADA_OK'), "index.php?do=cotacao&acao=visualizar&id=".$id_cotacao);
          } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
              print Filter::msgStatus();
      }

      /**
       * Cotacao::getItensFornecedor()
       * 
       * @return
       */
      public function getItensFornecedor($id_cotacao, $id_cadastro, $id_custo)
      {
		  $sql = "SELECT i.id, cc.centro_custo, pr.id_produto, pr.descricao as produto, pf.unidade_compra as unidade, pr.codigo_barras, c.id_cadastro, c.valor_unitario, i.id_cotacao, i.id_pedido, i.id_produto, i.quantidade_pedido, p.id_custo "
			. " FROM pedido_itens AS i"
			. " LEFT JOIN pedido AS p ON p.id = i.id_pedido"
			. " LEFT JOIN cotacao_itens AS c ON c.id_cotacao = i.id_cotacao AND c.id_produto = i.id_produto"
			. " LEFT JOIN produto AS pr ON pr.id = i.id_produto"
			. " LEFT JOIN produto_fornecedor AS pf ON pf.id_produto = c.id_produto AND pf.id_cadastro = c.id_cadastro"
			. " LEFT JOIN centro_custo AS cc ON cc.id = p.id_custo"
			. " WHERE i.inativo = 0 AND i.id_cotacao = $id_cotacao "
			. " AND c.id_cadastro = $id_cadastro "
			. " AND p.id_custo = $id_custo "
			. " AND c.valido = 1 "
			. " ORDER BY c.id_cadastro, p.id_custo, pr.descricao";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getItensFornecedorTodos()
       * 
       * @return
       */
      public function getItensFornecedorTodos($id_cotacao, $id_cadastro)
      {
		  $sql = "SELECT pr.id_produto, pr.descricao as produto, pf.unidade_compra as unidade, pr.codigo_barras, c.valor_unitario, SUM(c.quantidade_cotacao) AS  quantidade_cotacao"
			. " FROM cotacao_itens AS c"
			. " LEFT JOIN produto AS pr ON pr.id = c.id_produto"
			. " LEFT JOIN produto_fornecedor AS pf ON pf.id_produto = c.id_produto AND pf.id_cadastro = c.id_cadastro"
			. " WHERE c.inativo = 0 AND c.id_cotacao = $id_cotacao "
			. " AND c.id_cadastro = $id_cadastro "
			. " AND c.valido = 1 "
			. " GROUP BY pr.id_produto"
			. " ORDER BY pr.descricao";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getItensNaoCotados()
       * Incluir na resposta inclusive os ITENS APAGADOS no sistema de COTAÇÃO pela equipe de COMPRAS, por isso, não tem a clausula INATIVO = 0;
       * @return
       */
      public function getItensNaoCotados($id_cotacao, $id_custo)
      {
		  $sql = "SELECT c.categoria, pr.descricao as produto, pf.unidade_compra as unidade, pr.codigo_barras, i.id_pedido, i.id_produto, i.quantidade_pedido "
			. " FROM pedido_itens AS i"
			. " LEFT JOIN pedido AS p ON p.id = i.id_pedido"
			. " LEFT JOIN cotacao_itens AS c ON c.id_cotacao = i.id_cotacao AND c.id_produto = i.id_produto"
			. " LEFT JOIN produto AS pr ON pr.id = i.id_produto"
			. " LEFT JOIN produto_fornecedor AS pf ON pf.id_produto = c.id_produto AND pf.id_cadastro = c.id_cadastro"
			. " LEFT JOIN categoria as c ON c.id = pr.id_categoria"
			. " WHERE i.id_cotacao = $id_cotacao "
			. " AND p.id_custo = $id_custo "
			. " AND c.valido = 0 "
			. " GROUP BY i.id_produto"
			. " ORDER BY d.centro_custo, pr.descricao";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::validaEntrega()
       * 
       * @return
       */
      public function validaEntrega($id_cotacao, $id_cadastro = false, $id_custo = false)
      {
		  $wfornecedor = ($id_cadastro) ? " AND c.id_cadastro = $id_cadastro " : "";
		  $wcusto= ($id_custo) ? " AND p.id_custo = $id_custo " : "";
		  $sql = "SELECT i.id "
			. " FROM pedido_itens AS i"
			. " LEFT JOIN pedido AS p ON p.id = i.id_pedido"
			. " LEFT JOIN cotacao_itens AS c ON c.id_cotacao = i.id_cotacao AND c.id_produto = i.id_produto"
			. " WHERE i.inativo = 0 AND c.valido = 1 AND i.entrega = 0 AND i.id_cotacao = $id_cotacao "
			. " $wfornecedor "
			. " $wcusto ";
          $row = self::$db->first($sql);

          return ($row) ? 0 : 1;
      }
	  
      /**
       * Cotacao::confirmaEntrega()
       * 
       * @return
       */
      public function confirmaEntrega()
      {
		$id = post('id');
		$id_cadastro = post('id_cadastro');
		$id_custo = post('id_custo');
		
		if (empty(Filter::$msgs)) {
			$where_id = '';
			$retorno_row = $this->getItensFornecedor($id, $id_cadastro, $id_custo);
			if($retorno_row) {
				foreach ($retorno_row as $exrow) {
					$where_id .= $exrow->id.',';
				}
				$where_id = substr($where_id, 0, -1);
				unset($exrow);
				$data_itens = array(
					'entrega' => 1
				);
				self::$db->update("pedido_itens", $data_itens, "id in (".$where_id.")");
			}
			
			if (self::$db->affected()) {
			Filter::msgOk(lang('PEDIDO_CONFIRMAR_OK'));
			} else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
              print Filter::msgStatus();
	  }	  
	  
      /**
       * Cotacao::cancelaEntrega()
       * 
       * @return
       */
      public function cancelaEntrega()
      {
		$id = post('id');
		$id_cadastro = post('id_cadastro');
		$id_custo = post('id_custo');
		
		// Filter::$msgs['teste'] = "Teste de função";
		
		if (empty(Filter::$msgs)) {
			$where_id = '';
			$retorno_row = $this->getItensFornecedor($id, $id_cadastro, $id_custo);
			if($retorno_row) {
				foreach ($retorno_row as $exrow) {
					$where_id .= $exrow->id.',';
				}
				$where_id = substr($where_id, 0, -1);
				unset($exrow);
				$data_itens = array(
					'inativo' => 2
				);
				self::$db->update("pedido_itens", $data_itens, "id in (".$where_id.")");
			}
			
			if (self::$db->affected()) {
			Filter::msgAlert(lang('PEDIDO_CANCELAR_OK'));
			} else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
		} else
              print Filter::msgStatus();
	  }

      /**
       * Cotacao::getDetalhesCentroCusto()
       * 
       * @return
       */
      public function getDetalhesCentroCusto($id)
      {	
		  $sql = "SELECT cc.*, u.nome as responsavel, u.celular, u.email "
			. " FROM centro_custo as cc"
			. " LEFT JOIN usuario as u ON u.id = cc.id_responsavel"
			. " WHERE cc.id = $id ";
          $row = self::$db->first($sql);

          return ($row) ? $row : 0;
      }

      /**
       * Cotacao::getCentroCustoCotacao()
       * 
       * @return
       */
      public function getCentroCustoCotacao($id_cotacao)
      {
		  $sql = "SELECT cc.id, cc.centro_custo"
			. " FROM pedido AS p"
			. " LEFT JOIN centro_custo AS cc ON cc.id = p.id_custo"
			. " LEFT JOIN cotacao as c ON c.id = p.id_cotacao"
			. " WHERE p.inativo = 0 AND p.id_cotacao = $id_cotacao "
			. " GROUP BY cc.id"
			. " ORDER BY cc.centro_custo";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Cotacao::processarPedido()
       * 0 - STATUS_SEMCOTACAO
       * 1 - STATUS_ABERTA
       * 2 - STATUS_FORNECEDOR
       * 3 - STATUS_VALIDACAO
       * 4 - STATUS_APROVACAO
       * 5 - STATUS_ENTREGA
       * 6 - STATUS_FINALIZADA
       * 7 - STATUS_CANCELADA
       * 
       * @return
       */
      public function processarPedido()
      {
		  if (empty($_POST['id_custo']))
              Filter::$msgs['id_custo'] = lang('MSG_ERRO_CENTROCUSTO');	

          if (empty(Filter::$msgs)) {

              $data = array(
					'id_custo' => sanitize(post('id_custo')), 
					'descricao' => sanitize(post('descricao')), 
					'usuario_pedido' => session('nomeusuario'),
					'data_pedido' => "NOW()",
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
              $id_pedido = self::$db->insert("pedido", $data);
              $message = lang('PEDIDO_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=pedido&acao=editar&id=".$id_pedido);  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
      /**
       * Cotacao::processarFinalizarPedido()
       * 
       * @return
       */
      public function processarFinalizarPedido()
      {	
          if (empty(Filter::$msgs)) {
			  $id_pedido = post('id');

              $data = array(
					'id_status' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->update("pedido", $data, "id=".$id_pedido);
              $message = lang('PEDIDO_AlTERADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=pedido&acao=visualizar&id=".$id_pedido);  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
      /**
       * Cotacao::processarProdutoPedido()
       * 
       * @return
       */
      public function processarProdutoPedido()
      {
		  if (empty($_POST['id_produto']))
              Filter::$msgs['id_produto'] = lang('MSG_ERRO_PRODUTO');
			  
		  if (empty($_POST['quantidade']))
              Filter::$msgs['quantidade'] = lang('MSG_ERRO_QUANTIDADE');	

          if (empty(Filter::$msgs)) {
			  $id_pedido = post('id_pedido');

              $data = array(
					'id_pedido' => $id_pedido, 
					'id_produto' => sanitize(post('id_produto')), 
					'quantidade_inicial' => converteMoeda(post('quantidade')), 
					'quantidade_pedido' => converteMoeda(post('quantidade')),
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->insert("pedido_itens", $data);
              $message = lang('PRODUTO_ADICIONADO_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=pedido&acao=editar&id=".$id_pedido);  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
      /**
       * Cotacao::processarEntregaPedido()
       * 
       * @return
       */
      public function processarEntregaPedido()
      {	
          if (empty(Filter::$msgs)) {

			  $id_pedido = post('id');
			  
              $data = array(
					'id_status' => '6',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->update("pedido", $data, "id=".$id_pedido);

              $data = array(
					'entrega' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->update("pedido_itens", $data, "id_pedido=".$id_pedido);
              $message = lang('PEDIDO_ENTREGUE_OK');

              if (self::$db->affected()) {
			  
                  Filter::msgOk($message, "index.php?do=pedido&acao=visualizar&id=".$id_pedido);  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }
	  
      /**
       * Cotacao::processarEntregaPedidoItem()
       * 
       * @return
       */
      public function processarEntregaPedidoItem()
      {	
          if (empty(Filter::$msgs)) {
			  
			  $id_pedido = post('id_pedido');
			  $id_pedido_item = post('id');

              $data = array(
					'entrega' => '1',
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
			  );
			  self::$db->update("pedido_itens", $data, "id=".$id_pedido_item);
              $message = lang('PEDIDO_ENTREGUE_ITEM_OK');

              if (self::$db->affected()) {
				  if(!$this->verificaPedidoEntregue($id_pedido)) {
					  $datap = array(
							'id_status' => '6',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
					  );
					  self::$db->update("pedido", $datap, "id=".$id_pedido);
				  }			  
                  Filter::msgOk($message, "index.php?do=pedido&acao=visualizar&id=".$id_pedido);  
              } else
                  Filter::msgAlert(lang('NAOPROCESSADO'));
          } else
              print Filter::msgStatus();
      }

      /**
       * Cotacao::verificaPedidoEntregue()
       * 
       * @return
       */
      public function verificaPedidoEntregue($id_pedido)
      {	
		  $sql = "SELECT p.id "
			. " FROM pedido_itens as p"
			. " WHERE p.id_pedido = $id_pedido AND p.inativo = 0 AND p.entrega = 0 ";
          $row = self::$db->first($sql);

          return ($row) ? $row->id : 0;
      }
  }
?>