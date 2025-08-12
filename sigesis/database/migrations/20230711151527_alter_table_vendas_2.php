<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterTableVendas2 extends AbstractMigration
{
    
    public function up(): void
    {
        $table = $this->table('vendas');

        $columnTipo = $table->hasColumn('orcamento');

        if(!$columnTipo) {
            $table->addColumn('orcamento', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'status_entrega']);
        }
        
        if(!$columnTipo) {
            $table->save();
        }
        
        $table->save();

    }

    public function down(): void
    {
        $table = $this->table('vendas');
        $table->removeColumn('orcamento');
        $table->save();
    }


}
