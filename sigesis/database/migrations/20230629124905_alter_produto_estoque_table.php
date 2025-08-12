<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterProdutoEstoqueTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('produto_estoque');
        $id_venda_troca = $table->hasColumn('id_venda_troca');

        if (!$id_venda_troca) {
            $table->addColumn('id_venda_troca', 'integer', ['after' => 'id_ref']);;
        }

        if (!$id_venda_troca) {
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('produto_estoque');
        $table->removeColumn('id_venda_troca');
        $table->save();
    }
}
