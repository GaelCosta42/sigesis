<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class MigrationsIddespesaIdReceita extends AbstractMigration
{
    
    public function up(): void
    {
		$table = $this->table('despesa');
		$receitaNaDespesa = $table->hasColumn('id_receita');
		if (!$receitaNaDespesa) {
			$table->addColumn('id_receita', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'after' => 'id_conta']);
			$table->save();
		}

		$table = $this->table('receita');
		$despesaNaReceita = $table->hasColumn('id_despesa');
		if (!$despesaNaReceita) {
			$table->addColumn('id_despesa', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'after' => 'id_conta']);
			$table->save();			
		}		
	}

	public function down(): void
    {
        $table = $this->table('despesa');
        $table->removeColumn('id_receita');
        $table->save();

		$table = $this->table('receita');
        $table->removeColumn('id_despesa');
        $table->save();
    }
}
