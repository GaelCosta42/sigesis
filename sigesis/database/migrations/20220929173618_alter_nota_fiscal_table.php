<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class AlterNotaFiscalTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('nota_fiscal');

        $motivoStatusCheck = $table->hasColumn('motivo_status');
        $contigenciaCheck = $table->hasColumn('contigencia');

        if (!$motivoStatusCheck) {
            $table->addColumn('motivo_status', 'string', ['limit' => 200, 'after' => 'status_enotas']);
        }

        if (!$contigenciaCheck) {
            $table->addColumn('contigencia', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'motivo_status']);
        }

        if (! $motivoStatusCheck || ! $contigenciaCheck) {
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('nota_fiscal');

        $table->removeColumn('motivo_status');
        $table->removeColumn('contigencia');

        $table->save();
    }
}
