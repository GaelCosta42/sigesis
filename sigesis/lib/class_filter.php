<?php
  /**
   * Classe Filter
   *
   */

  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  final class Filter
  {
	  public static $id = null;
      public static $get = array();
      public static $post = array();
      public static $cookie = array();
      public static $files = array();
      public static $server = array();
      private static $marker = array();
	  public static $msgs = array();
	  public static $showMsg;
	  public static $acao = null;
	  public static $do = null;

      /**
       * Filter::__construct()
       *
       * @return
       */
      public function __construct()
      {

          $_GET = self::clean($_GET);
          $_POST = self::clean($_POST);
          $_COOKIE = self::clean($_COOKIE);
          $_FILES = self::clean($_FILES);
          $_SERVER = self::clean($_SERVER);

          self::$get = $_GET;
          self::$post = $_POST;
          self::$cookie = $_COOKIE;
          self::$files = $_FILES;
          self::$server = $_SERVER;

		  self::getAcao();
		  self::getDo();
		  self::$id = self::getId();
      }

	  /**
	   * Filter::getId()
	   *
	   * @return
	   */
	  private static function getId()
	  {
		  if (isset($_REQUEST['id'])) {
			  self::$id = (is_numeric($_REQUEST['id']) && $_REQUEST['id'] > -1) ? intval($_REQUEST['id']) : false;
			  self::$id = sanitize(self::$id);

			  if (self::$id == false) {
				  DEBUG == true ? self::error("O codigo invalido", "Filter::getId()") : self::ooops();
			  } else
				  return self::$id;
		  }
	  }

      /**
       * Filter::clean()
       *
       * @param mixed $data
       * @return
       */
      public static function clean($data)
      {
          if (is_array($data)) {
              foreach ($data as $key => $value) {
                  unset($data[$key]);

                  $data[self::clean($key)] = self::clean($value);
              }
          } else {
			  if (ini_get('magic_quotes_gpc')) {
				  $data = stripslashes($data);
			  } else {
				  $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
			  }
		  }

          return $data;
      }

      /**
       * Core::msgAlert()
       *
	   * @param mixed $msg
	   * @param bool $fader
	   * @param bool $altholder
       * @return
       */
	  public static function msgAlert($msg, $fader = true, $altholder = false)
	  {
		self::$showMsg = $msg;

		print self::$showMsg;
	  }

      /**
       * Core::msgOk()
       *
	   * @param mixed $msg
	   * @param bool $fader
	   * @param bool $altholder
       * @return
       */
	  public static function msgOk($msg, $redirecionar = false)
	  {
		$type = "success";
		self::$showMsg = ($redirecionar) ? $msg."#".$type."#1#".$redirecionar."#0" : $msg."#".$type."#0";

		print self::$showMsg;
	  }

	  /**
       * Core::msgOkRecibo()
       *
	   * @param mixed $msg
	   * @param bool $fader
	   * @param bool $altholder
       * @return
       */
	  public static function msgOkRecibo($msg, $redirecionar, $id_venda, $crediario=0)
	  {
		$type = "success";
		self::$showMsg = $msg."#".$type."#1#".$redirecionar."#".$id_venda."#".$crediario;

		print self::$showMsg;
	  }

	  /**
       * Core::msgOkReciboOrcamento()
       *
	   * @param mixed $msg
	   * @param bool $fader
	   * @param bool $altholder
       * @return
       */
	  public static function msgOkReciboOrcamento($msg, $redirecionar, $id_venda)
	  {
		$type = "success";
		self::$showMsg = $msg."#".$type."#2#".$redirecionar."#".$id_venda;

		print self::$showMsg;
	  }

      /**
       * Core::msgError()
       *
	   * @param mixed $msg
	   * @param bool $fader
	   * @param bool $altholder
       * @return
       */
	  public static function msgError($msg, $redirecionar = false)
	  {
		$type = "danger";
		self::$showMsg = ($redirecionar) ? $msg."#".$type."#1#".$redirecionar."#0" : $msg."#".$type."#0";

		  print self::$showMsg;
	  }


	  /**
	   * msgInfo()
	   *
	   * @param mixed $msg
	   * @param bool $fader
	   * @param bool $altholder
	   * @return
	   */
	  public static function msgInfo($msg, $redirecionar = false)
	  {
		$type = "info";
		self::$showMsg = ($redirecionar) ? $msg."#".$type."#1#".$redirecionar."#0" : $msg."#".$type."#0";

		  print self::$showMsg;
	  }

      /**
       * Filter::msgStatus()
       *
       * @return
       */
	  public static function msgStatus()
	  {
		  self::$showMsg = "<div>Ocorreu um erro ao processar a solicitação.<ul>";
		  foreach (self::$msgs as $msg) {
			  self::$showMsg .= "<li>" . $msg . "</li>\n";
		  }
		  self::$showMsg .= "</ul></div>";

		  $type = "info";
		  self::$showMsg = self::$showMsg."#".$type;

		  return self::$showMsg;
	  }

      /**
       * Filter::warning()
       *
	   * @param mixed $msg
	   * @param mixed $source
       * @return
       */
      public static function warning($msg)
      {
		  $the_error = '<br/><div class="note note-warning">';
		  $the_error .= '<h4 class="block">Alerta!!!</h4>';
		  $the_error .= '<h5>Mensagem: '.$msg.'</h5>';
		  $the_error .= '<button type="button" id="voltar" class="btn default">'.lang('VOLTAR').'</button>';
		  $the_error .= '</div>';
		  $the_error .= '<script type="text/javascript">
							$(document).ready(function() {
								$("#voltar").on("click", function(){
									$(this).fadeOut();
									window.history.go(-1);
								});
							});
						</script>';
          echo $the_error;
          die();
      }

      /**
       * Filter::error()
       *
	   * @param mixed $msg
	   * @param mixed $source
       * @return
       */
      public static function error($msg, $source)
      {
		  $the_error = '<br/>
		  				<div class="note note-danger">
		  					<h4 class="block">Erro!!!</h4>
		  					<p>Você tentou acessar uma página que não existe mais.</p>
		  					<p>Erro: '.$msg.'</p>
		  					<p>Objeto: '.$source.'</p>
		  					<button type="button" id="voltar" class="btn default">Voltar</button>
		  				</div>
		  				<script type="text/javascript">
							$(document).ready(function() {
								$("#voltar").on("click", function(){
									$(this).fadeOut();
									window.history.go(-1);
								});
							});
						</script>';
          echo $the_error;
          die();
      }

      /**
       * Filter::ooops()
       *
       * @return
       */
      public static function ooops()
      {
		  $the_error = '<br/><div class="note note-danger">';
		  $the_error .= '<h4 class="block">Oooops!!!</h4>';
		  $the_error .= '<p>Aconteceu um erro, repita a ação que você tentou executar. <br/>Se o erro persistir entre em contato com o administrador do sistema</p>';
		  $the_error .= '</div>';
		  $the_error .= '<script type="text/javascript">
							$(document).ready(function() {
								$("#voltar").on("click", function(){
									$(this).fadeOut();
									window.history.go(-1);
								});
							});
						</script>';
          echo $the_error;
          die();
      }

      /**
       * Filter::getAcao()
       *
       * @return
       */
	  private static function getAcao()
	  {
		  if (isset(self::$get['acao'])) {
			  $acao = ((string)self::$get['acao']) ? (string)self::$get['acao'] : false;
			  $acao = sanitize($acao);

			  if ($acao == false) {
				  self::error("A acao escolhida invalida","Filter::getAcao()");
			  } else
				  return self::$acao = $acao;
		  }
	  }

      /**
       * Filter::getDo()
       *
       * @return
       */
	  private static function getDo()
	  {
		  if (isset(self::$get['do'])) {
			  $do = ((string)self::$get['do']) ? (string)self::$get['do'] : false;
			  $do = sanitize($do);

			  if ($do == false) {
				  self::error("Pagina nao encontrada","Filter::getDo()");
			  } else {
				  $do = BASEPATH.$do;
				  return self::$do = $do;
			  }
		  }
	  }

	  /**
	   * Filter::dodate()
	   *
	   * @param mixed $format
	   * @param mixed $date
	   * @return
	   */
	  public static function dodate($format, $date) {

		return strftime($format, strtotime($date));
	  }

	  /**
	   * Filter::readFile()
	   *
	   * @param mixed $filename
	   * @param boll $retbytes
	   * @return
	   */
	  public static function readFile($filename,$retbytes=true) {
		 $chunksize = 1*(1024*1024);
		 $buffer = '';
		 $cnt =0;

		 $handle = fopen($filename, 'rb');
		 if ($handle === false) {
			 return false;
		 }
		 while (!feof($handle)) {
			 $buffer = fread($handle, $chunksize);
			 echo $buffer;
			 ob_flush();
			 flush();
			 if ($retbytes) {
				 $cnt += strlen($buffer);
			 }
		 }
			 $status = fclose($handle);
		 if ($retbytes && $status) {
			 return $cnt;
		 }
		 return $status;

	  }

	  /**
	   * Filter::fetchFile()
	   *
	   * @param mixed $dirname
	   * @param mixed $nome
	   * @param mixed $file_path
	   * @return
	   */
	  public function fetchFile($dirname, $nome, &$file_path)
	  {
		  $dir = opendir($dirname);

		  while ($file = readdir($dir)) {
			  if (empty($file_path) && $file != '.' && $file != '..') {
				  if (is_dir($dirname . '/' . $file)) {
					  self::fetchFile($dirname . '/' . $file, $nome, $file_path);
				  } else {
					  if (file_exists($dirname . '/' . $nome)) {
						  $file_path = $dirname . '/' . $nome;
						  return;
					  }
				  }
			  }
		  }
	  }

      /**
       * Filter::mark()
       *
       * @param mixed $name
       * @return
       */
      public static function mark($name)
      {
          self::$marker[$name] = microtime();
      }


      /**
       * Filter::elapsed()
       *
       * @param string $point1
       * @param string $point2
       * @param integer $decimals
       * @return
       */
      public static function elapsed($point1 = '', $point2 = '', $decimals = 4)
      {

          if (!isset(self::$marker[$point1])) {
              return '';
          }

          if (!isset(self::$marker[$point2])) {
              self::$marker[$point2] = microtime();
          }

          list($sm, $ss) = explode(' ', self::$marker[$point1]);
          list($em, $es) = explode(' ', self::$marker[$point2]);

          return number_format(($em + $es) - ($sm + $ss), $decimals);
      }
  }
?>