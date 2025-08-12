<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterTableEmpresaT2879x011123 extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('empresa');
        $check_cancelar_prouto_venda = $table->hasColumn('modal_cancelar_produto_venda');
        $check_alterar_valor_produto_venda = $table->hasColumn('modal_alterar_valor_produto_venda');

        if (!$check_cancelar_prouto_venda) {
            $table->addColumn('modal_cancelar_produto_venda', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'mostrar_vendas_dia_vendedor']);
            $table->save();
        }
        if (!$check_alterar_valor_produto_venda) {
            $table->addColumn('modal_alterar_valor_produto_venda', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'modal_cancelar_produto_venda']);
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('empresa');
        $table->removeColumn('modal_cancelar_produto_venda');
        $table->removeColumn('modal_alterar_valor_produto_venda');
        $table->save();
    }
}
