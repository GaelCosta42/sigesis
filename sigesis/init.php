<?php

  /**
   * Init
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
?>
<?php
	date_default_timezone_set("America/Argentina/Buenos_Aires");
	set_time_limit(0);
	ini_set('memory_limit', -1);
	ini_set('max_execution_time', -1);
	ini_set('fastcgi_read_timeout', -1);
  
  if (substr(PHP_OS, 0, 3) == "WIN") {
      $BASEPATH = str_replace("init.php", "", realpath(__FILE__));
  } else {
      $BASEPATH = str_replace("init.php", "", realpath(__FILE__));
  }
  define("BASEPATH", $BASEPATH);
  
  $configFile = BASEPATH . "lib/config.ini.php";
  $cookies = BASEPATH . "cookies/";
  
  define("COOKIELOCAL", $cookies);
  
  if (file_exists($configFile)) {
      require_once($configFile);
  } else {
      header("Location: _error/");
  }
  
  $enotas_apikey = 'MDFiMzJlN2ItMjkwYS00ODcyLTkwOGYtNjVlMTUwYjYwNTAw';
  require_once('enotas/eNotasGW.php');
  
  require_once(BASEPATH . "lib/class_db.php");
  
  require_once(BASEPATH . "lib/class_registry.php");
  Registry::set('Database',new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE));
  $db = Registry::get("Database");
  $db->connect();

  //Include Functions
  require_once(BASEPATH . "lib/functions.php");
  require_once(BASEPATH . "lib/functions_receita.php");
  include(BASEPATH . "lib/headerRefresh.php");
  
  require_once(BASEPATH . "lib/class_filter.php");
  $request = new Filter();  
  
  //Start Core Class 
  require_once(BASEPATH . "lib/class_core.php");
  Registry::set('Core',new Core());
  $core = Registry::get("Core");
  
  //StartUser Class 
  require_once(BASEPATH . "lib/class_usuario.php");
  Registry::set('Usuario',new Usuario());
  $usuario = Registry::get("Usuario");

  //Load Gestao
  require_once(BASEPATH . "lib/class_gestao.php");
  Registry::set('Gestao',new Gestao());
  $gestao = Registry::get("Gestao");
  
  //Load Onibus
  require_once(BASEPATH . "lib/class_onibus.php");
  Registry::set('Onibus',new Onibus());
  $onibus = Registry::get("Onibus");
  
  //Load Despesa
  require_once(BASEPATH . "lib/class_despesa.php");
  Registry::set('Despesa',new Despesa());
  $despesa = Registry::get("Despesa");
  
  //Load Faturamento
  require_once(BASEPATH . "lib/class_faturamento.php");
  Registry::set('Faturamento',new Faturamento());
  $faturamento = Registry::get("Faturamento");
  
  //Load Produto
  require_once(BASEPATH . "lib/class_produto.php");
  Registry::set('Produto',new Produto());
  $produto = Registry::get("Produto");

  //Load Sintegra
  require_once(BASEPATH . "lib/class_sintegra.php");
  Registry::set('Sintegra',new Sintegra());
  $sintegra = Registry::get("Sintegra");

  //Load Cadastro
  require_once(BASEPATH . "lib/class_cadastro.php");
  Registry::set('Cadastro',new Cadastro());
  $cadastro = Registry::get("Cadastro");

  //Load Categoria
  require_once(BASEPATH . "lib/class_categoria.php");
  Registry::set('Categoria',new Categoria());
  $categoria = Registry::get("Categoria");

  //Load Grupo
  require_once(BASEPATH . "lib/class_grupo.php");
  Registry::set('Grupo',new Grupo());
  $grupo = Registry::get("Grupo");
  
  //Load Fabricante
  require_once(BASEPATH . "lib/class_fabricante.php");
  Registry::set('Fabricante',new Fabricante());
  $fabricante = Registry::get("Fabricante");

  //Load Extrato
  require_once(BASEPATH . "lib/class_extrato.php");
  Registry::set('Extrato',new Extrato());
  $extrato = Registry::get("Extrato");
  
  //Load Arquivo
  require_once(BASEPATH . "lib/class_arquivo.php");
  Registry::set('Arquivo',new Arquivo());
  $arquivo = Registry::get("Arquivo");
  
  //Load Empresa
  require_once(BASEPATH . "lib/class_empresa.php");
  Registry::set('Empresa',new Empresa());
  $empresa = Registry::get("Empresa");
  
  //Load Veiculo
  require_once(BASEPATH . "lib/class_veiculo.php");
  Registry::set('Veiculo',new Veiculo());
  $veiculo = Registry::get("Veiculo");

  //Load Boleto
  require_once(BASEPATH . "lib/class_boleto.php");
  Registry::set('Boleto',new Boleto());
  $boleto = Registry::get("Boleto");
  
  //Load RH
  require_once(BASEPATH . "lib/class_rh.php");
  Registry::set('RH',new RH());
  $rh = Registry::get("RH");
  
  //Load Descricao
  require_once(BASEPATH . "lib/class_descricao.php");
  Registry::set('Descricao',new Descricao());
  $salario_descricao = Registry::get("Descricao");
  
  //Load SalarioMinimo
  require_once(BASEPATH . "lib/class_salario.php");
  Registry::set('SalarioMinimo',new SalarioMinimo());
  $salario_minimo = Registry::get("SalarioMinimo");
  
  //Load Cotacao
  require_once(BASEPATH . "lib/class_cotacao.php");
  Registry::set('Cotacao',new Cotacao());
  $cotacao = Registry::get("Cotacao");
  
  //Load PONTO ELETRONICO
  require_once(BASEPATH . "lib/class_pontoeletronico.php");
  Registry::set('PontoEletronico',new PontoEletronico());
  $pontoeletronico = Registry::get("PontoEletronico");
  
  //Load Fiscal
  require_once(BASEPATH . "lib/class_fiscal.php");
  Registry::set('Fiscal',new Fiscal());
  $fiscal = Registry::get("Fiscal");

  //Load ORDEM DE SERVICO
  require_once(BASEPATH . "lib/class_ordem_servico.php");
  Registry::set('OrdemServico',new OrdemServico());
  $ordem_servico = Registry::get("OrdemServico");

  define("SITEURL", $core->site_url);
  define("ADMINURL", $core->site_url);
  define("UPLOADS", BASEPATH."uploads/data/");
  define("UPLOADURL", SITEURL."/uploads/");
  define("UPLOADSHTML", "./uploads/data/");
  define("UPLOADNOTAS", BASEPATH."/uploads/enotas/");
?>