<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class AlterTableFabricante extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('fabricante');

        $exibirromaneioCheck = $table->hasColumn('exibir_romaneio');

        if (!$exibirromaneioCheck) {
            $table->addColumn('exibir_romaneio', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'fabricante']);
        }

        if (!$exibirromaneioCheck) {
            $table->save();
        }
        
    }

    public function down(): void
    {
        $table = $this->table('fabricante');
        $table->removeColumn('exibir_romaneio');
        $table->save();
    }
}
