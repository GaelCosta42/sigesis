<?php
	
	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
	define("_VALID_PHP", true);
	require('../init.php');	
	echo ' >> INICIO - '.date('d/m/Y H:i:s').'<br>';
		$sql = "SELECT p.nome " 
		  . "\n FROM produto as p"
		  . "\n GROUP BY p.nome "
		  . "\n HAVING COUNT(1) > 1 "
		  . "\n ORDER BY p.nome LIMIT 0, 100";
          $retorno_row = $db->fetch_all($sql);
		  $i = 0;		  
		echo '<br><strong> >> QUANT: '.count($retorno_row).'</strong><br><br>';
		if($retorno_row) {
			foreach ($retorno_row as $exrow){
				$i++;
				$primeiro = true;
				$id = 0;
				echo ' >> '.$exrow->nome.'<br>';	
				$sql = "SELECT p.id, p.nome " 
				  . "\n FROM produto as p"
				  . "\n WHERE p.nome = '".$exrow->nome."'"
				  . "\n ORDER BY p.id DESC ";
				  $nome_row = $db->fetch_all($sql);	
				if($nome_row) {
					foreach ($nome_row as $nrow){	
						$id = ($primeiro)  ? $nrow->id : $id;
						echo ' ---- >> ID: '.$nrow->id.' - ['.$nrow->nome.']<br>';
						if(!$primeiro) {
							$sql = "UPDATE nota_fiscal_itens SET id_produto = ".$id." WHERE id_produto = ".$nrow->id."; ";
							$row = $db->query($sql);
							$sql = "UPDATE produto_fornecedor SET id_produto = ".$id." WHERE id_produto = ".$nrow->id."; ";
							$row = $db->query($sql);
							$sql = "UPDATE cadastro_vendas SET id_produto = ".$id." WHERE id_produto = ".$nrow->id."; ";
							$row = $db->query($sql);
							$sql = "DELETE FROM produto_estoque WHERE id_produto = ".$nrow->id."; ";
							$row = $db->query($sql);
							$sql = "DELETE FROM produto_atributo WHERE id_produto = ".$nrow->id."; ";
							$row = $db->query($sql);
							$sql = "DELETE FROM produto_kit WHERE id_produto = ".$nrow->id."; ";
							$row = $db->query($sql);
							$sql = "DELETE FROM produto_tabela WHERE id_produto = ".$nrow->id."; ";
							$row = $db->query($sql);
							$sql = "DELETE FROM produto WHERE id = ".$nrow->id."; ";
							$row = $db->query($sql);
						}
						$primeiro = false;
					}
				}					
			}
			unset($exrow);
		}
		echo '<br><strong> >> TOTAL: '.$i.'</strong><br><br>';
		echo ' >> FINAL - '.date('d/m/Y H:i:s').'<br>';
 
 


?>