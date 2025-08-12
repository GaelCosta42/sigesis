<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterVendasIdUnicoTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('vendas');
        $check_id_unico = $table->hasColumn('id_unico');
        
        if (!$check_id_unico) {
            $table->addColumn('id_unico', 'string', ['limit' => 50, 'after' => 'id']);
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('vendas');
        $table->removeColumn('id_unico');
        $table->save();
    }
}
