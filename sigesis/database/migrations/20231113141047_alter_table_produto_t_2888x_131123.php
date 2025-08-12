<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterTableProdutoT2888x131123 extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('produto');
        $check_produto_balanca = $table->hasColumn('produto_balanca');
        
        if (!$check_produto_balanca) {
            $table->addColumn('produto_balanca', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'codigobarras']);
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('produto');
        $table->removeColumn('produto_balanca');
        $table->save();
    }
}
