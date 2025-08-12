<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterEmpresaOrcamentoTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('empresa');
        $orcamentoCheck = $table->hasColumn('orcamento');

        if (!$orcamentoCheck) {
            $table->addColumn('orcamento', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'venda_aberto']);
        }

        if (!$orcamentoCheck) {
            $table->save();
        }

        if (!$orcamentoCheck) {
            $this->execute("UPDATE empresa SET orcamento = 1 WHERE 1");
        }
    }

    public function down(): void
    {
        $table = $this->table('empresa');
        $table->removeColumn('orcamento');
        $table->save();
    }
}