<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AlterCadastroCrediarioPagamentosv2Table extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('cadastro_crediario_pagamentos');
        $crediarioCheck = $table->hasColumn('id_receita');

        if (!$crediarioCheck) {
            $table->addColumn('id_receita', 'integer', ['limit' => MysqlAdapter::INT_REGULAR, 'after' => 'id_cadastro_crediario']);
        }

        if (!$crediarioCheck) {
            $table->save();
        }

    }

    public function down(): void
    {
        $table = $this->table('empresa');
        $table->removeColumn('orcamento');
        $table->save();
    }
}