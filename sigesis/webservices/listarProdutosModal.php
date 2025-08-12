<?php
	
     /**
     * Traz todos os produtos ao apertar o atalho F1 em Vendas > Nova Venda.
     * 
     */

    set_time_limit(0);
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
	ini_set('fastcgi_read_timeout', 600);
		
    define("_VALID_PHP", true);
    require('../init.php');
    
    $nome_produto = '';
    $id_tabela = '';

    if( isset($_GET['nome_produto']) ) 
        $nome_produto = get('nome_produto');
    elseif( isset($_POST['nome_produto']) ) 
        $nome_produto = post('nome_produto');

    if( isset($_GET['id_tabela']) ) 
        $id_tabela = $_GET['id_tabela'];
    elseif( isset($_POST['id_tabela']) ) 
        $id_tabela = $_POST['id_tabela'];

    $nome_produto = str_replace("!hashtag!","#",$nome_produto);
    $nome_produto = str_replace("!arroba!","@",$nome_produto);
    $nome_produto = str_replace("!dollar!","$",$nome_produto);
    
        $quebra_nome = explode(' ',$nome_produto);
        $whereNome='(';
        foreach ($quebra_nome as $nome) {
            $whereNome .= "p.nome like '%$nome%' AND ";
        }
        $whereNome = substr($whereNome,0,-5);
        $whereNome .= ')';

        $sql = " SELECT p.id, p.nome, p.codigo_interno, p.codigo, p.codigobarras, p.valor_avista, pt.id_tabela as id_tabela, pt.valor_venda, p.estoque, p.unidade, p.descricao_unidade
        FROM produto as p
        LEFT JOIN produto_tabela as pt on pt.id_produto = p.id
        LEFT JOIN tabela_precos as tp on tp.id = pt.id_tabela
        WHERE p.inativo = 0 
        AND p.grade = 1 AND tp.id = {$id_tabela}
        AND ($whereNome or p.codigobarras like '%$nome_produto%' or p.codigo LIKE '%$nome_produto%' or p.codigo_interno like '$nome_produto') 
        ORDER BY p.nome LIMIT 50";
    
        $retorno = $db->fetch_all($sql);
    
        echo json_encode($retorno, JSON_PRETTY_PRINT); //Retorna o json formatado
?>