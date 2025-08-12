<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterTableProdutoEstoque extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('produto_estoque');
        $quantidade_antiga = $table->hasColumn("quantidade_antiga");
        $quantidade_atual = $table->hasColumn("quantidade_atual");

        if(!$quantidade_antiga) {
            $table->addColumn('quantidade_antiga', 'decimal', ['precision' => 11, 'scale'=> 3, 'after' => 'id_produto']);
            $table->save();
        }
       
        if(!$quantidade_atual) {
            $table->addColumn('quantidade_atual', 'decimal', ['precision' => 11, 'scale'=> 3, 'after' => 'quantidade_antiga']);
        }
        
        if(!$quantidade_antiga) {
            $table->save();
        }
        
        if(!$quantidade_atual) {
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('produto_estoque');
        $table->removeColumn('quantidade_antiga');
        $table->removeColumn('quantidade_atual');
        $table->save();
    }
}
