<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterTableCadVendas110823 extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('cadastro_vendas');
        $table->changeColumn('quantidade_trocada', 'decimal', ['precision' => 11, 'scale' => 3]);
        $table->save();
    }
    public function down(): void
    {

    }
}
