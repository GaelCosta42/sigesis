<?php
  /**
   * Funcoes
   *
   */

  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

	use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

	if (substr(PHP_OS, 0, 3) == "WIN") {
		$BASEPATHFUNCTIONS = str_replace("functions.php", "", realpath(__FILE__));
	} else {
		$BASEPATHFUNCTIONS = str_replace("functions.php", "", realpath(__FILE__));
	}
	define("BASEPATHFUNCTIONS", $BASEPATHFUNCTIONS);

	require_once($BASEPATHFUNCTIONS . "phpmailer/src/Exception.php");
	require_once($BASEPATHFUNCTIONS . "phpmailer/src/PHPMailer.php");
	require_once($BASEPATHFUNCTIONS . "phpmailer/src/SMTP.php");

	/**
       * indicador()
       *
       * @param $condicao
       * @return
       */
      function indicador($condicao)
      {
          return ($condicao) ? "<i class='fa fa-thumbs-up indicador font-green'></i>" : "<i class='fa fa-thumbs-down indicador font-red'></i>";
      }
	/**
       * nocaixa()
       *
       * @param $nocaixa
       * @return
       */
      function nocaixa($banco)
      {
          return ($banco) ? $banco : "-";
      }

   /**
   * percentual()
   *
   * @param $parte, $total
   * @return
   */
  function percentual($parte, $total)
  {
    return ($parte/$total);
  }

  /**
   * valida_cpf_cnpj()
   *
   * @param $cpf
   * @return
   */
	function valida_cpf_cnpj($cpf_cnpj)
	{

		// Extrai somente os números
		$cpf_cnpj = preg_replace( '/[^0-9]/is', '', $cpf_cnpj );

		if (strlen($cpf_cnpj) == 14) {

            return isCnpjValid($cpf_cnpj);

            /*
            // Verifica se todos os digitos são iguais
			if (preg_match('/(\d)\1[13]/', $cpf_cnpj))
				return false;

                // Valida primeiro dígito verificador
			for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
			{
				$soma += $cpf_cnpj[$i] * $j;
				$j = ($j == 2) ? 9 : $j - 1;
			}

            $resto = $soma % 11;
			if ($cpf_cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
				return false;

                // Valida segundo dígito verificador
			for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
			{
				$soma += $cpf_cnpj[$i] * $j;
				$j = ($j == 2) ? 9 : $j - 1;
			}
			$resto = $soma % 11;

            echo "[ $cpf_cnpj ]<br>";
            echo "[ RETORNO = ".($cpf_cnpj[13] == ($resto < 2 ? 0 : 11 - $resto))."]<br><br>";

			return $cpf_cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);

            */

		} elseif (strlen($cpf_cnpj) == 11) {
			// Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
			if (preg_match('/(\d)\1{10}/', $cpf_cnpj)) {
				return false;
			}
			// Faz o calculo para validar o CPF
			for ($t = 9; $t < 11; $t++) {
				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf_cnpj[$c] * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($cpf_cnpj[$c] != $d) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

    function isCnpjValid($cnpj)
    {
        $j=0;
        for($i=0; $i<(strlen($cnpj)); $i++)
            {
                if(is_numeric($cnpj[$i]))
                    {
                        $num[$j]=$cnpj[$i];
                        $j++;
                    }
            }

        if(count($num)!=14)
        {
            $isCnpjValid=false;
        }

        if ($num[0]==0 && $num[1]==0 && $num[2]==0 && $num[3]==0 && $num[4]==0 && $num[5]==0 && $num[6]==0 && $num[7]==0 && $num[8]==0 && $num[9]==0 && $num[10]==0 && $num[11]==0)
        {
            $isCnpjValid=false;
        }
        else
        {
            $j=5;
            for($i=0; $i<4; $i++)
            {
                $multiplica[$i]=$num[$i]*$j;
                $j--;
            }

            $soma = array_sum($multiplica);
            $j=9;
            for($i=4; $i<12; $i++)
            {
                $multiplica[$i]=$num[$i]*$j;
                $j--;
            }

            $soma = array_sum($multiplica);
            $resto = $soma%11;
            if($resto<2)
            {
                $dg=0;
            }
            else
            {
                $dg=11-$resto;
            }

            if($dg!=$num[12])
            {
                $isCnpjValid=false;
            }
        }

        if(!isset($isCnpjValid))
        {
            $j=6;
            for($i=0; $i<5; $i++)
            {
                $multiplica[$i]=$num[$i]*$j;
                $j--;
            }

            $soma = array_sum($multiplica);
            $j=9;
            for($i=5; $i<13; $i++)
            {
                $multiplica[$i]=$num[$i]*$j;
                $j--;
            }

            $soma = array_sum($multiplica);
            $resto = $soma%11;
            if($resto<2)
            {
                $dg=0;
            }
            else
            {
                $dg=11-$resto;
            }

            if($dg!=$num[13])
            {
                $isCnpjValid=false;
            }
            else
            {
                $isCnpjValid=true;
            }
        }
        return $isCnpjValid;
    }









  /**
   * percentual()
   *
   * @param $parte, $total
   * @return
   */
  function fpercentual($parte, $total)
  {
    if($total > 0 )
		return number_format(($parte/$total*100), 2, ',', '.')." %";
	else
		return "0%";
  }

  /**
   * margem()
   *
   * @param $parte, $total
   * @return
   */
  function margem($receita, $despesa)
  {
    if($receita > 0 )
		return number_format((($receita-$despesa)/$receita*100), 2, ',', '.')." %";
	else
		return "0%";
  }

  /**
   * porcentagem()
   *
   * @param $parte, $total
   * @return
   */
  function porcentagem($percentual)
  {
    return number_format(($percentual*100), 2, ',', '.')." %";
  }

  /**
       * percent()
       *
       * @param $amount
       * @return
       */
      function percent($percentual)
      {
          return number_format($percentual, 2, ',', '.')." %";
      }

  /**
       * moeda()
       *
       * @param $amount
       * @return
       */
      function moeda($amount)
      {
          return "R$ " . number_format($amount, 2, ',', '.');
      }

  /**
       * nodecimal()
       *
       * @param $amount
       * @return
       */
      function nodecimal($amount)
      {
          return number_format($amount, 0, ',', '.');
      }

	  /**
       * nodecimalp()
       *
       * @param $amount
       * @return
       */
      function nodecimalp($amount)
      {
          return number_format($amount, 0, ',', '.');
      }

  /**
       * decimal()
       *
       * @param $amount
       * @return
       */
      function decimal($amount)
      {
          return number_format($amount, 2, ',', '.');
      }

    /**
	 * casasdecimais($amount)
	 *
	 * @param $amount
	 * @return
	 */
	  function casasdecimais($amount)
      {
        return "R$ " . number_format($amount, 4, ',', '.');
      }

  /**
       * moedap()
       *
       * @param $amount
       * @return
       */
      function moedap($amount)
      {
          return "R$ " . number_format($amount, 2, ',', '.');
      }

  /**
       * decimalp()
       *
       * @param $amount
       * @return
       */
      function decimalp($amount)
      {
          return number_format($amount, 3, ',', '.');
      }

	  /**
       * capitalize()
       *
       * @param $texto
       * @return
       */
      function capitalize($texto)
      {
          return ucwords(strtolower($texto));
      }

  /**
       * horas()
       *
       * @param $amount
       * @return
       */
      function horas($amount)
      {
          return number_format($amount, 0, ',', '.');
      }

   /**
   * converteMoeda()
   *
   * @param $moeda
   * @return
   */
    function converteMoeda($moeda = '')
    {
        if (substr_count($moeda, "R$"))
        {
            $moeda = str_replace("R$","",$moeda);
            $moeda = str_replace(".","",$moeda);
            $moeda = str_replace(",",".",$moeda);
        } elseif (substr_count($moeda, "$")) {
            $moeda = str_replace("$","",$moeda);
            $moeda = str_replace(",","",$moeda);
        } elseif (substr_count($moeda, ",")) {
            $moeda = str_replace(".","",$moeda);
            $moeda = str_replace(",",".",$moeda);
        }
        $moeda = str_replace(" ","",$moeda);
        return (float)$moeda;
    }

    /**
   * converteMoeda()
   *
   * @param $moeda
   * @return
   */
  function converteDecimal3($valor)
  {
    $valor = str_replace(".","",$valor);
    $valor = str_replace(",",".",$valor);
    return floatval(round($valor,3));
  }

   /**
   * limparNumero()
   *
   * @param mixed $limparNumero
   * @return
   */
  function limparNumero($numero)
  {
      $numero = str_replace(" ","",$numero);
      $numero = str_replace("-","",$numero);
      $numero = str_replace(".","",$numero);
      $numero = str_replace(",","",$numero);
      $numero = str_replace("(","",$numero);
      $numero = str_replace(")","",$numero);
      $numero = str_replace("/","",$numero);
      return $numero;
  }

	/**
   * telefoneSMS()
   *
   * @param mixed $tel
   * @return
   */
  function telefoneSMS($tel)
  {
      $tel = str_replace("(","",$tel);
      $tel = str_replace(")","",$tel);
      $tel = str_replace("-","",$tel);
      $tel = str_replace(" ","",$tel);
	  if(strlen($tel) == 10) {
		  $ddd = substr($tel, 0,2);
		  $telefone = substr($tel, 2);
		  $tel = $ddd."9".$telefone;
	  }
	  $tel = "55".$tel;
      return (strlen($tel) == 13) ? $tel : 0;
  }

	/**
   * validaTelefone()
   *
   * @param mixed $tel
   * @return
   */
  function validaTelefone($tel)
  {
      $tel = str_replace("(","",$tel);
      $tel = str_replace(")","",$tel);
      $tel = str_replace("-","",$tel);
      $tel = str_replace(" ","",$tel);

	  if(strlen($tel) > 7 and strlen($tel) < 10) {
		  $tel = "00".$tel;
	  } elseif(strlen($tel) >= 10 AND strlen($tel) <= 11) {
		  $tel = $tel;
	  } else {
		  $tel = "";
	  }
      return $tel;
  }

	/**
   * contarMeses()
   *
   * @param mixed $data
   * @return
   */
  function contarMeses($dataini, $datafim)
  {
	  $retorno = "";
	  date_default_timezone_set('America/Sao_Paulo');
	  $data_string = date('Y-m-d H:i:s');
	  $ini = new DateTime( $dataini );
	  $fim = new DateTime( $datafim );
	  $intervalo = $ini->diff( $fim );
	  $meses = $intervalo->y*12 + $intervalo->m;
	  return $meses;
  }

	/**
   * dataAgendaSMS()
   *
   * @param mixed $data
   * @return
   */
  function dataAgendaSMS($data)
  {
	  $retorno = "";
	  date_default_timezone_set('America/Sao_Paulo');
	  $data_string = date('Y-m-d H:i:s');
	  $hoje = new DateTime( $data_string );
	  $data_agenda = new DateTime( $data );
	  $intervalo = $hoje->diff( $data_agenda );
	  if($intervalo->invert){
		  $retorno = "ERRO";
	  } else {
		  $dias = $intervalo->days;
		  if($dias > 1) {
			  $dias = $dias - 1;
			  $horas = $intervalo->h;
			  $minutos = $intervalo->i;
			  $segundos = $intervalo->s;
			  $retorno = "&dataagenda=".$dias."d".$horas."h".$minutos."m".$segundos."s";
		  }
	  }
	  return $retorno;
  }

	/**
   * limpardata()
   *
   * @param $data
   * @return
   */
  function limpardata($data)
  {
      return str_replace("-","",$data);
  }

  /**
   * diasemana()
   *
   * @param $data no formata Mysql (DD/MM/YYYY) para true
   * @param $data no formata Mysql (YYYY-MM-DD) para false
   * @return
   */
  function diasemana($data, $format = false, $numero = false) {
	if ($format)	{
		$d = explode("/", $data);
		$ano =  $d[2];
		$mes =  $d[1];
		$dia =  $d[0];
	} else {
		$d = explode("-", $data);
		$ano =  $d[0];
		$mes =  $d[1];
		$dia =  $d[2];
	}
	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

	if($numero) {
		return $diasemana;
	}

	switch($diasemana) {
		case 0: $diasemana = lang('DOMINGOF');  break;
		case 1: $diasemana = lang('SEGUNDAF'); break;
		case 2: $diasemana = lang('TERCAF');   break;
		case 3: $diasemana = lang('QUARTAF');  break;
		case 4: $diasemana = lang('QUINTAF');  break;
		case 5: $diasemana = lang('SEXTAF');   break;
		case 6: $diasemana = lang('SABADOF');   break;
	}

	return $diasemana;
}

  /**
   * redirect_to()
   *
   * @param $location
   * @return
   */
  function redirect_to($location)
  {
      if (!headers_sent()) {
          header('Location: ' . $location);
		  exit;
	  } else
          echo '<script type="text/javascript">';
          echo 'window.location.href="' . $location . '";';
          echo '</script>';
          echo '<noscript>';
          echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
          echo '</noscript>';
  }

  /**
   * redirecionar()
   *
   * @param $location
   * @return
   */
  function redirecionar($location)
  {
    echo '<script type="text/javascript">';
    echo 'window.location.href="' . $location . '";';
    echo '</script>';
  }

  /**
   * fecharjanela()
   *
   * @return
   */
  function fecharjanela()
  {
    echo '<script type="text/javascript">';
    echo 'window.opener.location.reload();';
    echo 'window.close();';
    echo '</script>';
  }

  /**
   * countEntries()
   *
   * @param $table
   * @param string $where
   * @param string $what
   * @return
   */
  function countEntries($table, $where = '', $what = '')
  {
      if (!empty($where) && isset($what)) {
          $q = "SELECT COUNT(*) FROM " . $table . "  WHERE " . $where . " = '" . $what . "' LIMIT 1";
      } else
          $q = "SELECT COUNT(*) FROM " . $table . " LIMIT 1";

      $record = Registry::get("Database")->query($q);
      $total = Registry::get("Database")->fetchrow($record);
      return $total[0];
  }

  /**
   * countEntries()
   *
   * @param $table
   * @param string $where
   * @param string $what
   * @return
   */
  function contarEntradas($table, $where1 = '')
  {
      $q = "SELECT COUNT(*) FROM " . $table . "  WHERE " . $where1 . " LIMIT 1";

      $record = Registry::get("Database")->query($q);
      $total = Registry::get("Database")->fetchrow($record);
      return $total[0];
  }

  /**
   * getChecked()
   *
   * @param $row
   * @param $status
   * @return
   */
  function getChecked($row, $status)
  {
      if ($row == $status) {
          echo "checked=\"checked\"";
      }
  }

  /**
   * session()
   *
   * @param $var
   * @return
   */
  function session($var)
  {
      if (isset($_SESSION[$var]))
          return $_SESSION[$var];
  }

  /**
   * post()
   *
   * @param $var
   * @return
   */
  function post($var)
  {
      if (isset($_POST[$var]))
          return $_POST[$var];
  }

  /**
   * get()
   *
   * @param $var
   * @return
   */
  function get($var)
  {
      if (isset($_GET[$var]))
          return $_GET[$var];
  }

  /**
   * sanitize()
   *
   * @param $string
   * @param bool $trim
   * @return
   */
  function sanitize($string, $trim = false, $int = false, $str = false)
  {
	  $string = retira_acentos($string);
	  $encoding = mb_detect_encoding($string, 'UTF-8, ISO-8859-1', true);
      if ($encoding !== 'UTF-8') {
          $string = mb_convert_encoding($string, 'UTF-8', $encoding);
      }
      $string = filter_var($string, FILTER_SANITIZE_STRING);
      $string = trim($string);
      $string = stripslashes($string);
      $string = strip_tags($string);
      $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);

	  if ($trim)
          $string = substr($string, 0, $trim);
      if ($int)
		  $string = preg_replace("/[^0-9\s]/", "", $string);
      if ($str)
		  $string = preg_replace("/[^a-zA-Z\s]/", "", $string);

      return retira_acentos($string);
  }

  /**
   * cleanSanitize()
   *
   * @param $string
   * @param bool $trim
   * @return
   */
  function cleanSanitize($string, $trim = false)
  {
      require_once('URLify.php');
	  $string = URLify::filter($string);
	  $string = strtoupper($string);
	  $string = utf8_encode($string);
      $string = filter_var($string, FILTER_SANITIZE_STRING);
      $string = trim($string);
      $string = stripslashes($string);
      $string = strip_tags($string);
      $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);

	  if ($trim)
          $string = substr($string, 0, $trim);

      return retira_acentos($string);
  }

  /**
   * getValue()
   *
   * @param $stwhatring
   * @param $table
   * @param $where
   * @return
   */
  function getValue($what, $table, $where)
  {
      $sql = "SELECT $what FROM $table WHERE $where";
      $row = Registry::get("Database")->first($sql);
      return ($row) ? $row->$what : '';
  }


  /**
   * tooltip()
   *
   * @param $tip
   * @return
   */
  function tooltip($tip)
  {
      return '<img src="' . SITEURL . '/images/tip.png" alt="" class="tooltip" title="' . $tip . '" style="margin-left:5px"/>';
  }

  /**
   * required()
   *
   * @return
   */
  function required()
  {
      return '<img src="' . SITEURL . '/images/required.png" alt="Required Field" class="tooltip" title="Required Field" />';
  }

   /**
   * somarData()
   * @param data, dias, meses e ano
   * @return a soma na data
   */
  function somarData($data, $dias = 0, $meses = 0, $ano = 0)
  {
	//A data deve estar no formato dd/mm/yyyy
	$data = explode('/', $data);
	$newData = date('d/m/Y', mktime(0, 0, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $ano) );
	return $newData;
  }

  /**
   * quantidadeHoras()
   * @param horas
   * @return a hora convertida em decimal
   */
  function quantidadeHoras($horas)
  {
	//A data deve estar no formato dd/mm/yyyy
	$temp = explode(':', $horas);
	$total = $temp[0]+($temp[1]/60)+($temp[2]/3600);
	return $total;
  }

  /**
   * converterDecimalParaHoras()
   * @param horas
   * @return o decimal convertido em horas (utlizado para exibição na view contrato/faturar)
   */
  function converterDecimalParaHoras($decimal)
  {
	$horas = sprintf('%02d:%02d', (int) $decimal, fmod($decimal, 1) * 60);
	return $horas;
  }

  /**
   * somarData()
   * @param data, dias, meses e ano
   * @return a soma na data
   */
  function somarDataMySQL($data, $dias = 0, $meses = 0, $ano = 0)
  {
	//A data deve estar no formato dd/mm/yyyy
	$data = explode('-', $data);
	$newData = date('Y-m-d', mktime(0, 0, 0, $data[1] + $meses, $data[2] + $dias, $data[0] + $ano) );
	return $newData;
  }

  /**
   * somarMinutos()
   * @param data, dias, meses e ano
   * @return a soma na data
   */
  function somarMinutos($data, $minutos)
  {
	//A data deve estar no formato d/m/Y H:i

	$hora = substr($data,11,16);
	$hora = explode(":", $hora);
	$data = explode('/', $data);
	$newData = date("d/m/Y H:i", mktime($hora[0],($hora[1]+$minutos),0, $data[1], $data[0], $data[2]));
	return $newData;
  }

  /**
   * contarDias()
   * @param $us_data - DATA INICIAL
   * @param $us_atual - DATA FINAL
   * @return (int)quantidade de dias
   */
  function contarDias($us_data = "-", $us_atual = "-")
  {
	  $data_dias = ($us_data <> "-") ? $us_data : date('d/m/Y');
	  $novadata = ($us_atual <> "-") ? $us_atual : date('d/m/Y');
	  $ini = explode('/', $data_dias);
	  $fim = explode('/', $novadata);
	  $data_ini = mktime(0, 0, 0, $ini[1], $ini[0], $ini[2]);
	  $data_fim = mktime(0, 0, 0, $fim[1], $fim[0], $fim[2]);

	  $diferenca = $data_fim - $data_ini; // 19522800 segundos
	  $dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
	  return $dias;
  }

  /**
   * gerarCodigoBarrasAutomatico($cnpj_empresa)
   * Gera Codigo de barras automaticamente utilizando 7 digitos do cnpj e 6 digitos aleatoriamente
   * @return codigo de barras valido para o sistema.
   */
  function gerarCodigoBarrasAutomatico($cnpj_empresa,$db)
  {
    if ($cnpj_empresa[0]==2) $cnpj_empresa[0] = rand(3,9);
    do {
        $valorCodigo = rand(1,999999);
        $codigoNormatizado = str_pad($valorCodigo,6,0,STR_PAD_LEFT);
        $cnpjNormatizado = substr($cnpj_empresa, 0, 7);
        $codBarAuto = $cnpjNormatizado.$codigoNormatizado;
        $sql = "SELECT id FROM produto WHERE codigobarras like '%$codBarAuto%'";
        $row_codBarras = $db->first($sql);
    } while ($row_codBarras);
    return $codBarAuto;
  }

  /**
   * novadata()
   * Retorna a nova data considerando final de semana.
   * @return
   */
  function novadata($mes,$dia,$ano) {

	  $diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
	  if($diasemana == 0)
		  $dia = $dia + 1;
	  if($diasemana == 6)
		  $dia = $dia + 2;

	  $newData = date("Y-m-d", mktime(0,0,0,$mes,$dia,$ano));

	  return $newData;
  }

  /**
   * validaData()
   * @param $us_data - DATA INICIAL
   * @param $us_atual - DATA FINAL
   * @return "mes" para datas no mesmo mês
   * @return "atrasado" para data inicial no mês posterior a data final
   * @return "adiantado" para data inicial no mês anterior a data final
   */
  function validaData($data_inicial = "-", $data_final = "-")
  {
	  if(($data_inicial == "-") or ($data_final == "-")) {
		return "mes";
	  }
	  $ini = explode('/', $data_inicial);
	  $fim = explode('/', $data_final);
	  $data_ini = mktime(0, 0, 0, $ini[1], 1, $ini[2]);
	  $data_fim = mktime(0, 0, 0, $fim[1], 1, $fim[2]);
	  if ($data_ini > $data_fim) {
		  return "atrasado";
	  } elseif($data_ini < $data_fim) {
		  return "adiantado";
	  } else {
		return "mes";
	  }
  }

  /**
   * exibedata()
   * @param data no formato americano $us_data
   * @return data no formato brasileiro
   */
  function exibedata($us_data)
  {
	  $us_data = ($us_data) ? $us_data : "0000-00-00 00:00:00";
	  return (($us_data == "0000-00-00 00:00:00") or ($us_data == "0000-00-00")) ? "-" : date('d/m/Y',strtotime($us_data));
  }

  /**
   * hoje()
   * @param $us_data
   * @return verdadeiro para o dia de hoje
   */
  function hoje($us_data)
  {
	  $us_data = ($us_data) ? $us_data : "0000-00-00 00:00:00";
	  return ( date('d/m/Y') == date('d/m/Y',strtotime($us_data)));
  }

  /**
   * exibedataHora()
   * @param data no formato americano $us_data
   * @return data no formato brasileiro
   */
  function exibedataHora($us_data)
  {
	  $us_data = ($us_data) ? $us_data : "0000-00-00 00:00:00";
	  return ($us_data == "0000-00-00 00:00:00") ? "-" : date('d/m/Y H:i',strtotime($us_data));
  }

  /**
   * exibedataSegundo()
   * @param data no formato americano $us_data
   * @return data no formato brasileiro
   */
  function exibedataSMS($us_data)
  {
	  $us_data = ($us_data) ? $us_data : "0000-00-00 00:00:00";
	  return ($us_data == "0000-00-00 00:00:00") ? "-" : date('d-m-Y H:i:s',strtotime($us_data));
  }

  /**
   * exibeHora()
   * @param data no formato americano $us_data
   * @return data no formato brasileiro
   */
  function exibeHora($us_data)
  {
	  return ($us_data == "0000-00-00 00:00:00") ? "-" : date('H:i',strtotime($us_data));
  }
    /**
   * dataMySQL()
   * @param data no formato brasileiro $us_data (10/01/2017 - 13:35)
   * @return data no formato americano
   */
  function dataMySQL($us_data)
  {
	  return (strlen($us_data) > 11) ? substr($us_data,6,4)."-".substr($us_data,3,2)."-".substr($us_data,0,2)." ".substr($us_data,13) : substr($us_data,6,4)."-".substr($us_data,3,2)."-".substr($us_data,0,2);
  }

  /**
   * dataMySQL()
   * @param data no formato brasileiro $us_data 27072017
   * @return data no formato americano
   */
  function dataMySQL2($us_data)
  {
	  return substr($us_data,6,4)."-".substr($us_data,3,2)."-".substr($us_data,0,2);
  }

  /**
   * dataMySQL()
   * @param data no formato americano $us_data - 20170727
   * @return data no formato brasileiro
   */
  function dataMySQL3($us_data)
  {
	  return substr($us_data,0,4)."-".substr($us_data,4,2)."-".substr($us_data,6,2);
  }

  /**
   * dataExtrato()
   * @param data no formato americano $us_data
   * @return data no formato brasileiro
   */
  function dataExtrato($us_data)
  {
	  return substr($us_data,0,4)."-".substr($us_data,4,2)."-".substr($us_data,6,2);
  }

  /**
   * datavencimento()
   * @param data no formato americano $us_data
   * @return data no formato brasileiro
   */
  function datavencimento($datavencimento, $meses)
  {
	  $anos = round($meses / 12);
	  $us_data = ($datavencimento) ? $datavencimento : date("d/m/Y");
	  $ano = substr($us_data,6,4);
	  $mes = substr($us_data,3,2);
	  $dia = substr($us_data,0,2);
	  $mes = $mes + $meses;
	  if ($mes > 12)
	  {
		$mes = $mes - 12;
		$ano = $ano + 1;
	  }
	  return $ano."-".$mes."-".$dia;
  }


  /**
   * getSize()
   *
   * @param $size
   * @param integer $precision
   * @param bool $long_name
   * @param bool $real_size
   * @return
   */
  function getSize($size, $precision = 2, $long_name = false, $real_size = true)
  {
      $base = $real_size ? 1024 : 1000;
      $pos = 0;
      while ($size > $base) {
          $size /= $base;
          $pos++;
      }
      $prefix = _getSizePrefix($pos);
      $size_name = $long_name ? $prefix . "bytes" : $prefix[0] . 'B';
      return round($size, $precision) . ' ' . ucfirst($size_name);
  }

  /**
   * _getSizePrefix()
   *
   * @param $pos
   * @return
   */
  function _getSizePrefix($pos)
  {
      switch ($pos) {
          case 00:
              return "";
          case 01:
              return "kilo";
          case 02:
              return "mega";
          case 03:
              return "giga";
          default:
              return "?-";
      }
  }

  /**
   * stripTags()
   *
   * @param $start
   * @param $end
   * @param $string
   * @return
   */
  function stripTags($start, $end, $string)
  {
	  $string = stristr($string, $start);
	  $doend = stristr($string, $end);
	  return substr($string, strlen($start), -strlen($doend));
  }

  /**
   * cleanOut()
   *
   * @param $text
   * @return
   */
  function cleanOut($text) {
	 $text =  strtr($text, array('\r\n' => "", '\r' => "", '\n' => ""));
	 $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
	 $text = str_replace('<br>', '<br />', $text);
	 return stripslashes($text);
  }

  /**
   * randName()
   *
   * @return
   */
  function randName() {
	  $code = '';
	  for($x = 0; $x<6; $x++) {
		  $code .= '-'.substr(strtoupper(sha1(rand(0,999999999999999))),2,6);
	  }
	  $code = substr($code,1);
	  return $code;
  }

  function retira_acentos($texto) {
	$array1 = array( "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
	, "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç", "'" );
	$array2 = array( "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
	, "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C", " " );
	return str_replace( $array1, $array2, $texto);
  }

  /**
   * exibeMesAno()
   * @param data no formato false yyyy-mm-dd
   * @return data no formato true mm/yyyy
   */
  function exibeMesAno($mes_ano, $completo = false, $formato = false)
  {
	  if($formato)
	  {
		$d = explode("/", $mes_ano);
		$mes =  $d[0];
		$ano =  $d[1];
	  } else {
		$d = explode("-", $mes_ano);
		$mes =  $d[1];
		$ano =  $d[0];
	  }
	  switch ($mes) {
          case "01":
			  return ($completo) ? "JAN/".$ano : "JAN";
          case "02":
			  return ($completo) ? "FEV/".$ano : "FEV";
          case "03":
			  return ($completo) ? "MAR/".$ano : "MAR";
          case "04":
			  return ($completo) ? "ABR/".$ano : "ABR";
          case "05":
			  return ($completo) ? "MAI/".$ano : "MAI";
          case "06":
			  return ($completo) ? "JUN/".$ano : "JUN";
          case "07":
			  return ($completo) ? "JUL/".$ano : "JUL";
          case "08":
			  return ($completo) ? "AGO/".$ano : "AGO";
          case "09":
			  return ($completo) ? "SET/".$ano : "SET";
          case "10":
			  return ($completo) ? "OUT/".$ano : "OUT";
          case "11":
			  return ($completo) ? "NOV/".$ano : "NOV";
          case "12":
			  return ($completo) ? "DEZ/".$ano : "DEZ";
          default:
              return $mes."/".$ano;
      }
	  return ;
  }

    /**
   * menu()
   * @param pagina
   * @return class='active'
   */
  function menu($ativo)
  {
	  return ($ativo == Filter::$do) ? "class='active'" : "";
  }

    /**
   * menuCadastro()
   * @return class='active'
   */
  function menuCadastro()
  {
	  return ("usuario" == Filter::$do or "conta" == Filter::$do or "fornecedor" == Filter::$do or "banco" == Filter::$do or "empresa" == Filter::$do) ? "class='active'" : "";
  }

    /**
   * submenu()
   * @param acao
   * @return class='active'
   */
  function submenu($ativo)
  {
	  return ($ativo == Filter::$acao) ? "class='active'" : "";
  }

  /**
   * getCorDias()
   *
   */
  function getCorDias($dias)
  {
	if($dias > 90)
		return "e51400";
	elseif($dias > 30)
		return "f8a31f";
	else
		return "393";
  }

  /**
   * getCorMes()
   *
   */
  function getCorMes($dias)
  {
	if($dias > 210)
		return "e51400";
	elseif($dias > 150)
		return "f8a31f";
	else
		return "393";
  }

   /**
   * getClasse()
   *
   */
  function getClasse($status)
  {
      switch ($status) {
          case 10:
              return "orange";
          case 11:
              return "pink";
          default:
              return "lightgrey";
      }
  }

   /**
   * getRestricao()
   *
   */
  function getRestricao($restricao)
  {
      return ($restricao) ? "style='color:#f8a31f';" : "";
  }

   /**
   * tipoConta()
   *
   */
  function tipoConta($tipo)
  {
      switch ($tipo) {
          case "C":
              return lang('RECEITA');
          case "D":
              return lang('DESPESA');
          default:
              return lang('NAOINFORMADO');
      }
  }

   /**
   * tipoVenda()
   *
   */
  function tipoVenda($tipo)
  {
      switch ($tipo) {
          case 1:
              return lang('PRODUTO');
          case 2:
              return lang('SERVICO');
          case 3:
              return lang('PARCELA');
          case 4:
              return lang('CLIENTE_EMBELLEZE');
          default:
              return lang('NAOINFORMADO');
      }
  }

   /**
   * mesCompetencia()
   *
   */
  function mesCompetencia()
  {
      $data_competencia = explode("/",date("m/Y"));
	  $mes = $data_competencia[0];
	  $ano = $data_competencia[1];
	  if($mes == 1)
	  {
		$mes = 12;
		$ano = $data_competencia[1] - 1;
	  } else {
		$mes = $data_competencia[0] - 1;
	  }
	  return $mes."/".$ano;
  }

   /**
   * getMeses()
   *
   */
  function getMeses($periodo)
  {
      switch ($periodo) {
          case "MENSAL":
              return 1;
          case "TRIMESTRAL":
              return 3;
          case "SEMESTRAL":
              return 6;
          case "ANUAL":
              return 12;
      }
  }

   /**
   * validaTexto()
   *
   * @param mixed $texto, $palavra
   * @return
   */
  function validaTexto($texto, $palavra)
  {
		$texto = sanitize(strtolower($texto));
		$palavra = sanitize(strtolower($palavra));
		$valida =  substr_count($texto, $palavra);
		return $valida;
  }

  /**
   * getCorCurva()
   *
   */
  function getCorCurva($curva)
  {
	if($curva == "A")
		return "class='bg-green'";
	elseif($curva == "B")
		return "class='bg-yellow'";
	else
		return "class='bg-red'";
  }

  /**
   * getCorStatus()
   *
   */
  function getCorStatus($status)
  {
	if($status == 1)
		return "ffffff";
	elseif($status == 5)
		return "ff7e7e";
	elseif($status == 4)
		return "ffdaa0";
	elseif($status == 2)
		return "368ee0";
	elseif($status == 3)
		return "bbf9bb";
	elseif($status == 6)
		return "f9bbf2";
  }

	/**
       * unidades()
       *
       * @param mixed $amount
       * @return
       */
      function unidades($amount, $un)
      {
          return number_format($amount, 2, ',', '.')." ".$un;
      }

   /**
   * getCorStatus()
   *
   */
  function nivel($nivel)
  {
      switch ($nivel) {
          case 9:
              return lang('CONTROLLER');
          case 8:
              return lang('MASTER');
          case 7:
              return lang('GERENCIA_FINANCEIRO');
          case 6:
              return lang('ADMINISTRATIVO');
          case 5:
              return lang('COMERCIAL');
          case 3:
              return lang('ATENDIMENTO');
          case 2:
              return lang('COLABORADOR');
          case 1:
              return lang('CONTABILIDADE');
          case 0:
              return lang('SEM_ACESSO');
          default:
              return '-';
      }
  }

  /**
   * dataextenso()
   * @return data no formato extenso
   */
  function dataextenso($data = false)
  {
	$data = ($data) ? $data : date("d/m/Y");
	$d = explode("/", $data);
	$dia =  $d[0];
	$mes =  $d[1];
	  $ano =  $d[2];
	  $diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
	  switch($diasemana) {
		case 0: $semana = "Domingo, ";  		break;
		case 1: $semana = "Segunda-feira, "; 	break;
		case 2: $semana = "Terça-feira, ";   	break;
		case 3: $semana = "Quarta-feira, ";  	break;
		case 4: $semana = "Quinta-feira, ";  	break;
		case 5: $semana = "Sexta-feira, ";   	break;
		case 6: $semana = "Sábado, ";   		break;
	  }

	  switch ($mes) {
          case 1:  $mes = " de janeiro de ";  	break;
          case 2:  $mes = " de fevereiro de ";  break;
          case 3:  $mes = " de março de ";  	break;
          case 4:  $mes = " de abril de "; 	 	break;
          case 5:  $mes = " de maio de ";  		break;
          case 6:  $mes = " de junho de ";  	break;
          case 7:  $mes = " de julho de ";  	break;
          case 8:  $mes = " de agosto de ";  	break;
          case 9:  $mes = " de setembro de ";  	break;
          case 10: $mes = " de outubro de ";  	break;
          case 11: $mes = " de novembro de ";  	break;
          case 12: $mes = " de dezembro de ";  	break;
      }
	  return $semana.$dia.$mes.$ano;
  }

    /**
     * dataextenso2()
     * @return data no formato extenso
     */
    function dataextenso2($data = false)
    {
	    $data = ($data) ? $data : date("d/m/Y");
	    $d = explode("/", $data);
	    $dia =  $d[0];
	    $mes =  $d[1];
	    $ano =  $d[2];

	    switch ($mes) {
            case 1:  $mes = " de Janeiro de ";  	break;
            case 2:  $mes = " de Fevereiro de ";  break;
            case 3:  $mes = " de Março de ";  	break;
            case 4:  $mes = " de Abril de "; 	 	break;
            case 5:  $mes = " de Maio de ";  		break;
            case 6:  $mes = " de Junho de ";  	break;
            case 7:  $mes = " de Julho de ";  	break;
            case 8:  $mes = " de Agosto de ";  	break;
            case 9:  $mes = " de Setembro de ";  	break;
            case 10: $mes = " de Outubro de ";  	break;
            case 11: $mes = " de Novembro de ";  	break;
            case 12: $mes = " de Dezembro de ";  	break;
        }
	    return $dia.$mes.$ano;
    }

  /**
   * retorna_mes_por_extenso()
   * @return mes no formato extenso
   */
  function retorna_mes_por_extenso($data = false)
  {
	$data = ($data) ? $data : date("d/m/Y");
	$d = explode("/", $data);
	$mes =  $d[1];
	switch ($mes) {
          case 1:  $mes = "Janeiro";  	break;
          case 2:  $mes = "Fevereiro";  break;
          case 3:  $mes = "Março";  	break;
          case 4:  $mes = "Abril"; 	 	break;
          case 5:  $mes = "Maio";  		break;
          case 6:  $mes = "Junho";  	break;
          case 7:  $mes = "Julho";  	break;
          case 8:  $mes = "Agosto";  	break;
          case 9:  $mes = "Setembro";  	break;
          case 10: $mes = "Outubro";  	break;
          case 11: $mes = "Novembro";  	break;
          case 12: $mes = "Dezembro";  	break;
      }
	  return $mes;
  }

    /**
     * retorna_data_por_extenso()
     * @return data no formato extenso personalizado
     */
    function retorna_data_por_extenso($data = false)
    {
	    $data = ($data) ? $data : date("d/m/Y");
	    $d = explode("/", $data);
	    $dia =  $d[0];
	    $mes =  $d[1];
	    $ano =  $d[2];

	    switch ($mes) {
            case 1:  $mes = " do mês de janeiro do ano de ";  	break;
            case 2:  $mes = " do mês de fevereiro do ano de ";  break;
            case 3:  $mes = " do mês de março do ano de ";  	break;
            case 4:  $mes = " do mês de abril do ano de "; 	 	break;
            case 5:  $mes = " do mês de maio do ano de ";  		break;
            case 6:  $mes = " do mês de junho do ano de ";  	break;
            case 7:  $mes = " do mês de julho do ano de ";  	break;
            case 8:  $mes = " do mês de agosto do ano de ";  	break;
            case 9:  $mes = " do mês de setembro do ano de ";  	break;
            case 10: $mes = " do mês de outubro do ano de ";  	break;
            case 11: $mes = " do mês de novembro do ano de ";  	break;
            case 12: $mes = " do mês de dezembro do ano de ";  	break;
        }
        $diaExtenso = ($dia==1) ? "primeiro" : retorna_valor_por_extenso($dia,false,false);
        $anoExtenso = retorna_valor_por_extenso($ano,false,false);
        $infoDia = ($dia==1) ? "dia" : "dias";
	    return $diaExtenso.' '.$infoDia.$mes.$anoExtenso;
    }

    function retorna_valor_por_extenso( $valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false )
    {
        $valor = removerFormatacaoNumero($valor);
        $singular = null;
        $plural = null;
        if ( $bolExibirMoeda ) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        } else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezessete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

        if ( $bolPalavraFeminina ) {
            if ($valor == 1) {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            } else {
                $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }
            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }

        $z = 0;
        $valor = number_format( $valor, 2, ".", "." );
        $inteiro = explode( ".", $valor );

        for ( $i = 0; $i < count( $inteiro ); $i++ ) {
            for ( $ii = mb_strlen( $inteiro[$i] ); $ii < 3; $ii++ ) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count( $inteiro ) - ($inteiro[count( $inteiro ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $inteiro ); $i++ ) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $inteiro ) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ( $valor == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;

            if ( ($t == 1) && ($z > 0) && ($inteiro[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];

            if ( $r )
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }
        $rt = mb_substr( $rt, 1 );
        return($rt ? trim( $rt ) : "zero");
    }

    function removerFormatacaoNumero( $strNumero )
    {
        $strNumero = trim( str_replace( "R$", "", $strNumero ) );
        $vetVirgula = explode( ",", $strNumero );
        if ( count( $vetVirgula ) == 1 ) {
            $acentos = array(".");
            $resultado = str_replace( $acentos, "", $strNumero );
            return $resultado;
        } elseif ( count( $vetVirgula ) != 2 ) {
            return $strNumero;
        }

        $strNumero = $vetVirgula[0];
        $strDecimal = mb_substr( $vetVirgula[1], 0, 2 );
        $acentos = array(".");
        $resultado = str_replace( $acentos, "", $strNumero );
        $resultado = $resultado . "." . $strDecimal;
        return $resultado;
    }

  /**
   * contarDomingos()
   *
   * @param $mes_ano no formato (MM/YYYY)
   * @return
   */
  function contarDomingos($mes_ano) {
	$cont = 0;
	$datames = explode("/", $mes_ano);
	$ultimo = cal_days_in_month(CAL_GREGORIAN, $datames[0], $datames[1]);
	for($i=1;$i<=$ultimo;$i++) {
		$diasemana = date("N", mktime(0,0,0,$datames[0],$i,$datames[1]) );
		if($diasemana == 7) {
		 $cont++;
		}
	}
	return $cont;
  }

  /**
   * contarSabados()
   *
   * @param $mes_ano no formato (MM/YYYY)
   * @return
   */
  function contarSabados($mes_ano) {
	$cont = 0;
	$datames = explode("/", $mes_ano);
	$ultimo = cal_days_in_month(CAL_GREGORIAN, $datames[0], $datames[1]);
	for($i=1;$i<=$ultimo;$i++) {
		$diasemana = date("N", mktime(0,0,0,$datames[0],$i,$datames[1]) );
		if($diasemana == 6) {
		 $cont++;
		}
	}
	return $cont;
  }

  /**
   * contarDiasUteis()
   *
   * @param $mes_ano no formato (MM/YYYY)
   * @return
   */
  function contarDiasUteis($mes_ano, $sabado = 0) {
	$cont = 0;
	$sabados = 0;
	$datames = explode("/", $mes_ano);
	$ultimo = cal_days_in_month(CAL_GREGORIAN, $datames[0], $datames[1]);
	for($i=1;$i<=$ultimo;$i++) {
		$diasemana = date("N", mktime(0,0,0,$datames[0],$i,$datames[1]) );
		if($diasemana < 7) {
		 $cont++;
		}
	}
	if($sabado == 0)
	{
		$sql = "SELECT COUNT(1) as quant FROM feriados WHERE DAYOFWEEK(data) > 1 AND DATE_FORMAT(data, '%m/%Y') = '$mes_ano' ";
	} else {
		$sabados = contarSabados($mes_ano);
		$sql = "SELECT COUNT(1) as quant FROM feriados WHERE DAYOFWEEK(data) BETWEEN 2 AND 6 AND DATE_FORMAT(data, '%m/%Y') = '$mes_ano' ";
	}
    $row = Registry::get("Database")->first($sql);
    $feriados = ($row) ? $row->quant : 0;
	$cont = $cont - $feriados - $sabados;
	return $cont;
  }

  /**
   * contarDiasUteisData()
   *
   * @param $data no formato (YYYY-MM-DD)
   * @return
   */
  function contarDiasUteisData($data) {
	$hoje = date('Y-m-d');
	$data_ini = strtotime($hoje);
    $data_fim   = strtotime($data);
	$cont  = 0;
	$dias  = 0;
    $fimsemana = 0;
    while ($data_ini <= $data_fim) {
		$dias++;
		$dia_semana = date("N", $data_ini);
		if ($dia_semana > 6) { // Excluindo os domingos(7) e contando os sábados(6)
			$fimsemana++;
		};
		$data_ini += 86400; // +1 dia
    };
	$sql = "SELECT COUNT(1) as quant FROM feriados WHERE DAYOFWEEK(data) > 1 AND data BETWEEN CURDATE() AND STR_TO_DATE('$data','%Y-%m-%d') ";
    $row = Registry::get("Database")->first($sql);
    $feriados = ($row) ? $row->quant : 0;
	$cont = $dias - $fimsemana - $feriados - 2; //Retirar dois dias, o primeiro e o último.
	return $cont;
  }

  /**
   * contarSemanas()
   *
   * @param
   * @return
   */
  function contarSemanas($diasemana, $numero_dias) {
	$semanas = 0;
	$diacorrente = 0;
	for( $linha = 0; $linha < 6; $linha++ )	{
		if($diacorrente == $numero_dias){
			break;
		}
		if($diasemana > 3 and $linha == 0){
			$semanas;
		} else {
			++$semanas;
		}

		for( $coluna = 0; $coluna < 7; $coluna++ ) {
			if( $diacorrente + 1 <= $numero_dias ) {
				if( $coluna < $diasemana && $linha == 0) {
					$diacorrente;
				} else {
					++$diacorrente;
				}
			} else {
				break;
			}
		}
	}
	return $semanas;
  }

  /**
   * diaatual()
   * @param $data
   * @return verdadeiro para o dia de hoje no formato d/m/Y'
   */
  function diaatual($data)
  {
	  $d = explode("/", $data);
	  $dia =  $d[0];
	  $mes =  $d[1];
	  $ano =  $d[2];
	  return ( date('d/m/Y') == date('d/m/Y', mktime(0,0,0,$mes,$dia,$ano) ));
  }

   /**
   * motivo()
   *
   */
  function motivo($motivo)
  {
      switch ($motivo) {
          case 1:
              return lang('ESTOQUE_MOTIVO_COMPRA');
          case 2:
              return lang('ESTOQUE_MOTIVO_TRANSFERENCIA');
          case 3:
              return lang('ESTOQUE_MOTIVO_VENDA');
          case 4:
              return lang('ESTOQUE_MOTIVO_CONSUMO');
          case 5:
              return lang('ESTOQUE_MOTIVO_PERDA');
          case 6:
              return lang('ESTOQUE_MOTIVO_AJUSTE');
          case 7:
              return lang('ESTOQUE_MOTIVO_CANCELAMENTO');
          case 8:
              return lang('ESTOQUE_MOTIVO_PRODUCAO');
          case 9:
               return lang('ESTOQUE_MOV_ECOMD');
          case 10:
               return lang('ESTOQUE_MOTIVO_MARKETING');
          default:
              return "-";
      }
  }

  /**
  * pagamento()
  *
  * @param $pagamento
  * @return
  */
  function pagamento($tipo)
  {
     return ($tipo) ? $tipo : "FICHA(crediário)";
  }

   /**
   * pagamentoNFC()
   *
   * @param mixed $tipo
   * @return
   */
  function pagamentoNFC($tipo)
  {
	  $retorno = 'Outros';
	  if(substr_count($tipo, 'DINHEIRO'))
		  $retorno = 'Dinheiro';
	  elseif(substr_count($tipo, 'CHEQUE'))
		  $retorno = 'Cheque';
	  elseif(substr_count($tipo, 'CREDITO'))
		  $retorno = 'CartaoDeCredito';
	  elseif(substr_count($tipo, 'DEBITO'))
		  $retorno = 'CartaoDeDebito';
	  elseif(substr_count($tipo, 'CREDIARIO'))
		  $retorno = 'CreditoLoja';
	  elseif(substr_count($tipo, 'ALIMENTACAO'))
		  $retorno = 'ValeAlimentacao';
	  elseif(substr_count($tipo, 'REFEICAO'))
		  $retorno = 'ValeRefeicao';
	  elseif(substr_count($tipo, 'PRESENTE'))
		  $retorno = 'ValePresente';
	  elseif(substr_count($tipo, 'COMBUSTIVEL'))
		  $retorno = 'ValeCombustivel';

	  return $retorno;
  }

   /**
   * statusCaixa()
   *
   */
  function statusCaixa($status)
  {
      switch ($status) {
          case 1:
              return lang('CAIXA_ABERTO');
          case 2:
              return lang('CAIXA_FECHADO');
          case 3:
              return lang('CAIXA_VALIDADO');
          default:
              return "-";
      }
  }

   /**
   * statusCaixaImpressao()
   *
   */
  function statusCaixaImpressao($status)
  {
      switch ($status) {
          case 1:
              return lang('ABERTO');
          case 2:
              return lang('FECHADO');
          case 3:
              return lang('VALIDADO');
          default:
              return "-";
      }
  }

   /**
   * statusContrato()
   *
   */
  function statusContrato($status)
  {
      switch ($status) {
          case 1:
              return "<span class='label label-sm bg-green'>".lang('ATIVO')."</span>";
          case 2:
              return "<span class='label label-sm bg-yellow'>".lang('BLOQUEADO_MANUAL')."</span>";
          case 3:
              return "<span class='label label-sm bg-red'>".lang('CANCELADO')."</span>";
          case 9:
              return "<span class='label label-sm bg-yellow-gold'>".lang('BLOQUEADO_FINANCEIRO')."</span>";
          default:
              return "-";
      }
  }

   /**
   * statusFinanceiro()
   *
   */
  function statusFinanceiro($status)
  {
      switch ($status) {
          case 1:
              return "<span class='label label-sm bg-green'>".lang('PAGO')."</span>";
          case 2:
              return "<span class='label label-sm bg-red'>".lang('ATRASADO')."</span>";
          default:
              return "-";
      }
  }

  /**
   * virgulas()
   *
   * @param $text
   * @return
   */
  function virgulas($text) {
	 $text = str_replace(',', ' ', $text);
	 return stripslashes($text);
  }


  /**
   * lerNodes()
   * Função recursiva para ler todos os nodes de um XML
   * @param $childNodes
   * @return
   */
  function lerNodes($childNodes, &$array_nodes, $pref = false) {
        foreach($childNodes as $nodename)
        {
			if($nodename->hasChildNodes())
			{
				$childNodes2 = $nodename->childNodes;
                $name = $nodename->nodeName;
				$name = str_replace('ii:', '', $name);
				$name = str_replace('ns3:', '', $name);
				$chave = ($pref) ? $pref."_".$name : $name;
				lerNodes($childNodes2, $array_nodes, $chave);
			} else {
                $name = $nodename->nodeName;
				$name = str_replace('ii:', '', $name);
				$name = str_replace('ns3:', '', $name);
				$chave = ($pref) ? $pref : $name;
				$array_nodes[$chave] = $nodename->nodeValue;
				// echo $nodename->nodeName." - ".$nodename->nodeValue."<br/>";
			}
		}
	}

  /**
   * checkRegistro()
   *
   * @param $campo
   * @param $tabela
   * @param $valor
   * @return
   */
  function checkRegistro($campo, $tabela, $valor)
  {
      $sql = "SELECT id FROM $tabela WHERE $campo = '$valor' ";
      $row = Registry::get("Database")->first($sql);
      return ($row) ? $row->id : 0;
  }

  /**
   * checkEmpresa()
   *
   * @param $cnpj
   * @return
   */
  function checkEmpresa($cnpj)
  {
       $sql = "SELECT id FROM empresa WHERE cnpj = '$cnpj' ";
      $row = Registry::get("Database")->first($sql);
      return ($row) ? $row->id : 0;
  }

  /**
   * checkCondicao()
   *
   * @param $tabela
   * @param $condicao
   * @return
   */
  function checkCondicao($tabela, $condicao)
  {
      $sql = "SELECT id FROM $tabela WHERE $condicao ";
      $row = Registry::get("Database")->first($sql);
      return ($row) ? $row->id : 0;
  }

   /**
   * validarCNPJ()
   *
   * @param $cnpj
   * @return
   */
	function validarCPF_CNPJ($cpf_cnpj)
	{
		$cpf_cnpj = preg_replace('/[^0-9]/', '', (string) $cpf_cnpj);

		// Valida tamanho
		if (strlen($cpf_cnpj) == 14) {
			// Verifica se todos os digitos são iguais
			if (preg_match('/(\d)\1[13]/', $cpf_cnpj))
				return false;
			// Valida primeiro dígito verificador
			for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
			{
				$soma += $cpf_cnpj[$i] * $j;
				$j = ($j == 2) ? 9 : $j - 1;
			}
			$resto = $soma % 11;
			if ($cpf_cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
				return false;
			// Valida segundo dígito verificador
			for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
			{
				$soma += $cpf_cnpj[$i] * $j;
				$j = ($j == 2) ? 9 : $j - 1;
			}
			$resto = $soma % 11;
			return $cpf_cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
		} elseif(strlen($cpf_cnpj) == 11) {
			// Extrai somente os números
			$cpf_cnpj = preg_replace( '/[^0-9]/is', '', $cpf_cnpj );

			// Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
			if (preg_match('/(\d)\1{10}/', $cpf_cnpj)) {
				return false;
			}
			// Faz o calculo para validar o CPF
			for ($t = 9; $t < 11; $t++) {
				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf_cnpj[$c] * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($cpf_cnpj[$c] != $d) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
   * limparCPF_CNPJ()
   *
   * @param $tel
   * @return
   */
  function limparCPF_CNPJ($numero)
  {
      $numero = str_replace(".","",$numero);
      $numero = str_replace("/","",$numero);
      $numero = str_replace("-","",$numero);
      $numero = str_replace(" ","",$numero);
      return $numero;
  }

  /**
   * formatar_cpf_cnpj()
   * @param cpf_cnpj sem formato
   * @return campo formatado
   */
  function formatar_cpf_cnpj($cpf_cnpj)
  {
	  return (strlen($cpf_cnpj) > 12) ? substr($cpf_cnpj,0,2).".".substr($cpf_cnpj,2,3).".".substr($cpf_cnpj,5,3)."/".substr($cpf_cnpj,8,4)."-".substr($cpf_cnpj,12,2) : substr($cpf_cnpj,0,3).".".substr($cpf_cnpj,3,3).".".substr($cpf_cnpj,6,3)."-".substr($cpf_cnpj,9,2);
  }

  /**
   * formatar_telefone()
   * @param telefone sem formato
   * @return campo formatado 3197699900
   */
  function formatar_telefone($telefone)
  {
	  if(strlen($telefone) == 11) {
		  return "(".substr($telefone,0,2).") ".substr($telefone,2,1)." ".substr($telefone,3,4)."-".substr($telefone,7,4);
	  } elseif(strlen($telefone) == 10) {
		  return "(".substr($telefone,0,2).") ".substr($telefone,2,4)."-".substr($telefone,6,4);
	  } elseif(strlen($telefone) == 9) {
		  return substr($telefone,0,1)." ".substr($telefone,1,4)."-".substr($telefone,5,4);
	  } elseif(strlen($telefone) == 8) {
		  return substr($telefone,0,4)."-".substr($telefone,4,4);
	  } else {
		  return $telefone;
	  }
  }

	/**
   * hora()
   *
   * @param $h
   * @param $m
   * @return
   */
  function hora($h, $m)
  {
      return str_pad($h,2,'0',STR_PAD_LEFT).":".str_pad($m,2,'0',STR_PAD_LEFT);
  }

   /**
   * operacao()
   *
   */
  function operacao($operacao, $tipo = false)
  {
      switch ($operacao) {
          case 1:
              return ($tipo) ? 'ENTRADA' : 'COMPRA';
          case 2:
              return ($tipo) ? 'SAIDA' : 'VENDA';
          default:
              return "-";
      }
  }

   /**
   * modelo()
   *
   */
  function modelo($modelo)
  {
      switch ($modelo) {
          case 1:
              return lang('SERVICO');
          case 2:
              return lang('PRODUTO');
          case 3:
              return lang('FATURA');
          case 4:
              return lang('TRANSPORTE');
          case 5:
              return lang('CUPOM');
          case 6:
              return lang('CONSUMIDOR');
          default:
              return "-";
      }
  }

   /**
   * converteMoedaRemessa()
   *
   * @param mixed $moeda
   * @return
   */
  function converteMoedaRemessa($moeda)
  {
	  $moeda = number_format($moeda, 2, '.', '');
	  $moeda = str_replace(".","",$moeda);
      return (string) $moeda;
  }

  /**
   * exibedataRemessaJuros()
   * @param data no formato americano $us_data
   * @return data no formato brasileiro
   */
  function exibedataRemessaJuros($us_data)
  {
	  $us_data = ($us_data) ? $us_data : "0000-00-00";
	  return (($us_data == "0000-00-00 00:00:00") or ($us_data == "0000-00-00")) ? date('dmY') : date('dmY',strtotime($us_data.' +1 day'));
  }

  /**
   * exibedataRemessa()
   * @param data no formato americano $us_data
   * @return data no formato brasileiro
   */
  function exibedataRemessa($us_data)
  {
	  $us_data = ($us_data) ? $us_data : "0000-00-00";
	  return (($us_data == "0000-00-00 00:00:00") or ($us_data == "0000-00-00")) ? date('dmY') : date('dmY',strtotime($us_data));
  }

  /**
   * limpaNossoNumero()
   */
  function limpaNossoNumero($numero)
  {
	  if(strlen($numero) > 10) {
		  $numero = substr($numero, 4);
		  $numero = ltrim($numero, '0');
	  }
	  return $numero;
  }


  if(!function_exists('formata_numdoc'))
	{
		function formata_numdoc($num,$tamanho)
		{
			while(strlen($num)<$tamanho)
			{
				$num="0".$num;
			}
		return $num;
		}
	}

   /**
   * statusPedido()
   *
   */
  function statusPedido($status)
  {
      switch ($status) {
          case 1:
              return lang('STATUS_PEDIDO_ABERTO');
          case 5:
              return lang('STATUS_ENTREGA');
          case 6:
              return lang('STATUS_PEDIDO_FINALIZADO');
          case 7:
              return lang('STATUS_PEDIDO_CANCELADO');
          default:
              return '-';
      }
  }

   /**
   * statusCotacao()
   *
   */
  function statusCotacao($status)
  {
      switch ($status) {
          case 1:
              return lang('STATUS_ABERTA');
          case 2:
              return lang('STATUS_FORNECEDOR');
          case 3:
              return lang('STATUS_VALIDACAO');
          case 4:
              return lang('STATUS_APROVACAO');
          case 5:
              return lang('STATUS_ENTREGA');
          case 6:
              return lang('STATUS_FINALIZADA');
          case 7:
              return lang('STATUS_CANCELADA');
          default:
              return lang('STATUS_SEMCOTACAO');
      }
  }

   /**
   * cotacao()
   *
   */
  function cotacao($status)
  {
      switch ($status) {
          case 1:
              return lang('COTACAO_ABERTA');
          case 2:
              return lang('COTACAO_FORNECEDOR');
          case 3:
              return lang('COTACAO_VALIDACAO');
          case 4:
              return lang('COTACAO_APROVACAO');
          case 5:
              return lang('COTACAO_ENTREGA');
          case 6:
              return lang('COTACAO_FINALIZADA');
          case 7:
              return lang('COTACAO_CANCELADA');
          default:
              return lang('COTACAO_SEMCOTACAO');
      }
  }

  function get_file_get_contents( $site_url ){
		$ch = curl_init();
		$timeout = 0; // set to zero for no timeout
		curl_setopt ($ch, CURLOPT_URL, $site_url);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		ob_start();
		curl_exec($ch);
		curl_close($ch);
		$file_contents = ob_get_contents();
		ob_end_clean();
		return $file_contents;
  }

  /**
  * codigo()
  *
  * @param string $length
  * @return
  */
  function codigo($length = "")
  {
	  $code = md5(uniqid(rand(), true));
	  if ($length != "") {
		  return substr($code, 0, $length);
	  } else
		  return $code;
  }

   /**
   * enviarSMS()
   *
   * @param mixed $texto
   * @param mixed $tel
   * @param mixed $data_agenda
   * @return
   */
  function enviarSMS($texto, $tel, $data_agenda = '')
  {
	  $retorno = '';
	  $texto = sanitize($texto);
      $texto = str_replace(" ","+",$texto);
	  $script = ($data_agenda) ? "sona_shortcode_sched.php" : "sona_shortcode.php";
	  $url = 'http://sip.sonavoip.com.br/'.$script.'?smstext='.$texto.'&numorigem='.SMS_ORIGEM.'&numdestino='.$tel.$data_agenda.'&usuario='.SMS_USUARIO.'&senha='.SMS_SENHA;
	  // echo "[$url]";
	  $retorno = get_file_get_contents($url);
      return $url." [".$retorno."] ";
  }

  /**
   * urlCurta()
   *
   * @param $url
   * @return
   */
  function urlCurta($url)
  {
      require_once('bitly/bitly.php');
	  $urlcurta = '';
	  $user_access_token = 'b0e2fb888963b287637222a69e7e100240f67a8b';
	  $params = array();
	  $params['access_token'] = $user_access_token;
	  $params['longUrl'] = $url;
	  $results = bitly_get('shorten', $params);
	  if($results['status_code'] == '200'){
		  $urlcurta = $results['data']['url'];
	  }
	  return $urlcurta;
  }

	/**
   * idade()
   *
   * @param $date
   * @format Y-m-d
   * @return
   */
	function idade($data)
	{
		// Separa em dia, mês e ano
		list($ano, $mes, $dia) = explode('-', $data);

		if($ano == '0000') return '-';

		// Descobre que dia é hoje e retorna a unix timestamp
		$hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

		// Descobre a unix timestamp da data de nascimento
		$nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);

		// Depois apenas fazemos o cálculo já citado :)
		$idade = ((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

		$idade = number_format($idade, 1, ',', '');

		list($anos, $meses) = explode(',', $idade);

		return $anos." ano(s) e ".number_format($meses*1.2, 0, '', '')." mes(es)";
	}

   /**
   * validaCPF_CNPJ()
   *
   * @param $cpf
   * @return
   */
	function validaCPF_CNPJ($cpf_cnpj)
	{

		// Extrai somente os números
		$cpf_cnpj = preg_replace( '/[^0-9]/is', '', $cpf_cnpj );

		if (strlen($cpf_cnpj) == 14) {
			// Verifica se todos os digitos são iguais
			if (preg_match('/(\d)\1[13]/', $cpf_cnpj))
				return false;
			// Valida primeiro dígito verificador
			for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
			{
				$soma += $cpf_cnpj[$i] * $j;
				$j = ($j == 2) ? 9 : $j - 1;
			}
			$resto = $soma % 11;
			if ($cpf_cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
				return false;
			// Valida segundo dígito verificador
			for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
			{
				$soma += $cpf_cnpj[$i] * $j;
				$j = ($j == 2) ? 9 : $j - 1;
			}
			$resto = $soma % 11;
			return $cpf_cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
		} elseif (strlen($cpf_cnpj) == 11) {
			// Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
			if (preg_match('/(\d)\1{10}/', $cpf_cnpj)) {
				return false;
			}
			// Faz o calculo para validar o CPF
			for ($t = 9; $t < 11; $t++) {
				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf_cnpj[$c] * (($t + 1) - $c);
				}
				$d = ((10 * $d) % 11) % 10;
				if ($cpf_cnpj[$c] != $d) {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	function hora_para_segundos($hora)
	{
		if (!empty($hora))
		{
			list($horas,$minutos,$segundos) = explode(':',$hora);
			$retorno = $horas * 3600 + $minutos * 60 + $segundos;
			return $retorno;
		}
		else
		{
			return 0;
		}
	}

	function segundos_para_hora($valor_segundos)
	{
		$negativo = ($valor_segundos<0);

		$valor_segundos = abs($valor_segundos);
		$horas = floor($valor_segundos / 3600);
		$minutos = floor(($valor_segundos % 3600) / 60);
		$segundos = $valor_segundos % 60;

		$horas = ($horas<10) ? '0'.$horas : strval($horas);
		$minutos = ($minutos<10) ? '0'.$minutos : strval($minutos);
		$segundos = ($segundos<10) ? '0'.$segundos : strval($segundos);

		return ($negativo) ? "-$horas:$minutos:$segundos" : "$horas:$minutos:$segundos";
	}

	function dataInicioMes($mes_ano)
	{
		$mes = explode("/", $mes_ano)[0];
		$ano = explode("/", $mes_ano)[1];
		$primeiroDia = "01/$mes/$ano";
		return $primeiroDia;
	}

	function dataFinalMes($mes_ano)
	{
		$mes = explode("/", $mes_ano)[0];
		$ano = explode("/", $mes_ano)[1];
		$diasMes = cal_days_in_month(CAL_GREGORIAN,$mes,$ano);
		$ultimoDia = "$diasMes/$mes/$ano";
		return $ultimoDia;
	}

    function enviarEmail($destinatario, $titulo, $corpo, $db)
    {
		$sql_email = "SELECT site_email, mailer, smtp_host, smtp_user, smtp_pass, smtp_port FROM configuracao";
		$row_email = $db->first($sql_email);

        $mail = new PHPMailer(true);
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = $row_email->smtp_host;
        $mail->Port = $row_email->smtp_port; // or 587
        $mail->Username = $row_email->smtp_user;
        $mail->Password = $row_email->smtp_pass;
        $mail->SetFrom($row_email->smtp_user);
        $mail->AddAddress($destinatario);
        $mail->IsHTML(true);
        $mail->Subject = $titulo;
        $mail->Body    = $corpo;
        $mail->CharSet = 'UTF-8';

        if ($mail->send()) return true;
        else return false;
    }

	function cors() {

        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
            // whitelist of safe domains
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        }
    }

    function tratar_decode_cidade($cidade) {
        return html_entity_decode($cidade);
    }

    function obterCodigoIbgeCidade($cidade,$estado) {
        $novaCidade = trim($cidade);
        $novaCidade = str_replace(" ","%20",$novaCidade);
		$novaCidade = str_replace("'","",$novaCidade);
		$novaCidade = str_replace("&#039;","",$novaCidade);
		$uri_cidade = "https://controle.sigesis.com.br/webservices/buscarIdMunicipioIbge.php?municipio=".$novaCidade."&uf=".$estado;
        $curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_URL => $uri_cidade,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
		));
        $response = curl_exec($curl);
        return strval($response);
    }


    function gerarIdUniversal () {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double) microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
            return $uuid;
        }

    }

    /**
    * checkActive()
    *
    * @param $table
    * @param $where
    * @return
    */
    function checkActive($table, $where) {
        $sql = "SELECT id FROM $table WHERE $where AND inativo = 0";
        $row = Registry::get("Database")->first($sql);
        return ($row) ? true : false;
    }

?>