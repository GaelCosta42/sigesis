<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class AlterTipoPagamentoTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('tipo_pagamento');

        $contigenciaCheck = $table->hasColumn('exibir_crediario');

        if (!$contigenciaCheck) {
            $table->addColumn('exibir_crediario', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'exibir_nfe']);
        }

        if (!$contigenciaCheck) {
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('tipo_pagamento');

        $table->removeColumn('exibir_crediario');

        $table->save();
    }
}
