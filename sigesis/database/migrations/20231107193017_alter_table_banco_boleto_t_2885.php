<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterTableBancoBoletoT2885 extends AbstractMigration
{
    public function up(): void
    {
        $tableBancoBoleto = $this->table('banco_boleto');
        $check_codigo_banco = $tableBancoBoleto->hasColumn('codigo_banco');
        if (!$check_codigo_banco) {
            $tableBancoBoleto->addColumn('codigo_banco', 'string', ['limit' => 5, 'after' => 'nome_banco']);
            $tableBancoBoleto->save();

            $tableBancoBoleto->insert(
                [
                    'id'=> 3, 
                    'nome_banco' => 'CAIXA', 
                    'codigo_banco' => 104, 
                    'arquivo_boleto' => 'caixa', 
                    'inativo' => 0, 
                    'usuario' => 'automatico', 
                    'data' => date('Y-m-d') 
                ])->saveData();

            $this->execute("UPDATE banco_boleto SET codigo_banco = '756' WHERE id = 1");
            $this->execute("UPDATE banco_boleto SET codigo_banco = '001' WHERE id = 2");

        }
       
        $tableEmpresa = $this->table('empresa');
        $check_emissao_boleto = $tableEmpresa->hasColumn('modulo_emissao_boleto');
        if (!$check_emissao_boleto) {
            $tableEmpresa->addColumn('modulo_emissao_boleto', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'aplicativo_estoque']);
            $tableEmpresa->save();
        }
      
    }

    public function down(): void
    {
        $tableBancoBoleto = $this->table('banco_boleto');
        $tableBancoBoleto->removeColumn('codigo_banco');
        $tableBancoBoleto->save();
                
        $tableEmpresa = $this->table('empresa');
        $tableEmpresa->removeColumn('modulo_emissao_boleto');
        $tableEmpresa->save();
    }
}
