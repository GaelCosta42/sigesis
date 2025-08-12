<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterCadastroCrediarioPagamentosTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('cadastro_crediario_pagamentos');
        
        $table->addColumn('id_caixa', 'integer', ['after' => 'id']);

        $table->save();
    }

    public function down(): void
    {
        $this->table('cadastro_crediario_pagamentos')->removeColumn('id_caixa')->save();
    }
}
