<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateTableTaxas extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('taxas');
        $table->addColumn('id_bairro',          'integer',      ['limit'    => MysqlAdapter::INT_REGULAR]);
        $table->addColumn('valor_taxa',         'decimal',      ['precision'=> 9, 'scale'=> 2]);
        $table->addColumn('tempo_aproximado',   'time',         ['null' => 'false']);
        $table->addColumn('inativo',            'integer',      ['limit'    => MysqlAdapter::INT_TINY]);
        $table->addColumn('usuario',            'string',       ['limit'    => 20]);
        $table->addColumn('data',               'datetime',     ['null'     => false]);
        $table->create();
    }

    public function down(): void
    {
        $this->table('taxas')->drop()->save();
    }

}
