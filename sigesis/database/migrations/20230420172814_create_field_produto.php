<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateFieldProduto extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('empresa');

        $atualizar_vlr_produto_check = $table->hasColumn('atualizar_valor_produto');

        if (!$atualizar_vlr_produto_check) {
            $table->addColumn('atualizar_valor_produto', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'versao_emissao']);
        }
        
        if (!$atualizar_vlr_produto_check) {
            $table->save();
        }
   
    }

    public function down(): void
    {
        $table = $this->table('empresa');
        $table->removeColumn('atualizar_valor_produto');
        $table->save();
    }
}
