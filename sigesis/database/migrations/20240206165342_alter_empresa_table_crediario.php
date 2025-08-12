<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterEmpresaTableCrediario extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('empresa');
        $check_alterar_valor_crediario = $table->hasColumn('alterar_valor_crediario');

        if (!$check_alterar_valor_crediario) {
            $table->addColumn('alterar_valor_crediario', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'after' => 'modal_alterar_valor_produto_venda']);
            $table->save();
        }
    }

    public function down(): void
    {
        $table = $this->table('empresa');
        $table->removeColumn('alterar_valor_crediario');
        $table->save();
    }
}