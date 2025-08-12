<?php	
	define("_VALID_PHP", true);
	require('../init.php');	
	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	$cont = 0;
	$atualizado = 0;
	echo ' >> INICIO - '.date('d/m/Y H:i:s').'<br>';
	
	$retorno_row = $produto->getProdutosGrade();
		if($retorno_row) {
			foreach ($retorno_row as $exrow){
				$cont++;
				$estoque = $produto->getEstoqueTotal($exrow->id);
				$data = array(
					'estoque' => $estoque,
					'atualizado' => 1
				);
				$db->update("produto", $data, "id= '" . $exrow->id."'");
				if ($db->affected()) {        
					$atualizado++;       
					echo ">> Produto: ".$exrow->nome." - Estoque atualizado =".$estoque."<br>";
				} else {
					echo "<span style='color: red'>>> Produto: ".$exrow->nome." - Estoque atualizado =".$estoque."<br>";
				}
			}
			unset($exrow);
		}
	echo ' >> TOTAL - ['.$cont.']<br>';
	echo ' >> ATUALIZADO - ['.$atualizado.']<br>';
	echo ' >> FINAL - '.date('d/m/Y H:i:s').'<br>';
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-BR" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="pt-BR" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-BR">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>

<!-- Meta -->
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="SIGESIS - Sistemas - VOCÊ NO CONTROLE DA SUA EMPRESA, em qualquer lugar... a qualquer momento!"/>
<meta name="keywords" content="vale telecom, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis, telecom" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Vale Telecom"/>

<!-- Title -->
<title><?php echo $core->empresa;?></title>

<!-- Favicons -->
<link rel="shortcut icon" href="../assets/img/favicon.png">
<link rel="apple-touch-icon" href="../assets/img//favicon_60x60.png">
<link rel="apple-touch-icon" sizes="76x76" href="../assets/img//favicon_76x76.png">
<link rel="apple-touch-icon" sizes="120x120" href="../assets/img//favicon_120x120.png">
<link rel="apple-touch-icon" sizes="152x152" href="../assets/img//favicon_152x152.png">
</head>
<body>
</body>
</html>