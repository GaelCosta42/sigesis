<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterTableEmpresa extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('empresa');
        $mostrarVenda = $table->hasColumn('mostrar_vendas_dia_vendedor');

        if (!$mostrarVenda) {
            $table->addColumn('mostrar_vendas_dia_vendedor', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'logomarca_pdv']);
        }

        if (!$mostrarVenda) {
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('empresa');
        $table->removeColumn('mostrar_vendas_dia_vendedor');
        $table->save();
    }
}
