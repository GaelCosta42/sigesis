<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterTableEmpresaBoletos extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('empresa');
		
		$boleto1 = $table->hasColumn('boleto_instrucoes1');
		$boleto2 = $table->hasColumn('boleto_instrucoes2');
		$boleto3 = $table->hasColumn('boleto_instrucoes3');
		$boleto4 = $table->hasColumn('boleto_instrucoes4');
		
		if (!$boleto1) $table->addColumn('boleto_instrucoes1', 'string', ['limit'=> 300, 'after' => 'boleto_convenio']);
        if (!$boleto2) $table->addColumn('boleto_instrucoes2', 'string', ['limit'=> 300, 'after' => 'boleto_instrucoes1']);
        if (!$boleto3) $table->addColumn('boleto_instrucoes3', 'string', ['limit'=> 300, 'after' => 'boleto_instrucoes2']);
        if (!$boleto4) $table->addColumn('boleto_instrucoes4', 'string', ['limit'=> 300, 'after' => 'boleto_instrucoes3']);
		
        $table->save();
    }

    public function down(): void
    {
        $table = $this->table('empresa');
        $table->removeColumn('boleto_instrucoes1');
        $table->removeColumn('boleto_instrucoes2');
        $table->removeColumn('boleto_instrucoes3');
        $table->removeColumn('boleto_instrucoes4');
        $table->save();
    }
}