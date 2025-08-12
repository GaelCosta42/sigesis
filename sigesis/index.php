<?php
  /**
  * Index
  *
  */

  define("_VALID_PHP", true);
  require_once("init.php");
  if (!isset($_SESSION['nomeusuario']) or $_SESSION['nomeusuario'] == "Convidado" or !$usuario->is_Todos()) {
    $usuario->logout();
    redirect_to("login.php");
  }

  $bloqueioSistema = $empresa->verificarSituacaoCadastro(); //Obter via webservice informação de liberação de acesso ou não do controle.

  if (!$bloqueioSistema) {

    (Filter::$acao == "novavenda") ? include("header2.php") : include("header.php") ;
    (Filter::$do && file_exists(Filter::$do.".php")) ? include(Filter::$do.".php") : include("home.php");

?>
    <input type="hidden" name="tipo_sistema" id="tipo_sistema" value="<?php echo $core->tipo_sistema; ?>">
<?php 

    if ($core->tipo_sistema==4) :
    ?>   
      <div class="div-time-notification hidden notification-aberto">
        <div class="div-large-button button-time-notification noselect">
          <span><i class="fa fa-send"></i></span>
          <div class="button-time-notification-quantidade noselect"></div>
        </div>
      </div>
    <?php
    endif;

    include("footer.php") ;

  } else {
    
    echo "<h1>Prezado cliente, gentileza entrar em contato com o nosso setor Administrativo no telefone (31) 3829-1960</h1>";

  }

?>