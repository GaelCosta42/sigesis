<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterProdutoTableObs extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('produto');
        $check_produto_obs = $table->hasColumn('observacao');
        
        if (!$check_produto_obs) {
            $table->addColumn('observacao', 'string', ['limit' => 100, 'after' => 'detalhamento']);
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('produto');
        $table->removeColumn('observacao');
        $table->save();
    }
}
