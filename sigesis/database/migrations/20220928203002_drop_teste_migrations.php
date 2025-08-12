<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DropTesteMigrations extends AbstractMigration
{
    public function up(): void
    {
        $this->table('teste_migrations')->drop()->save();
    }

    public function down(): void
    {
        $table = $this->table('teste_migrations');
        
        $table->addColumn('nome', 'string');
        $table->addColumn('idade', 'integer', ['signed' => false]);
        $table->addColumn('saldo', 'decimal', ['precision' => 8, 'scale' => 3, 'signed' => true]);
        $table->addColumn('data_nascimento', 'datetime');

        $table->create();
    }
}
