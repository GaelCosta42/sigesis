<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateTableBairros extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('bairros');
        $table->addColumn('bairro',             'string',       ['limit'    => 45]);
        $table->addColumn('cidade',             'string',       ['limit'    => 45]);
        $table->addColumn('numero',             'integer',      ['limit'    => MysqlAdapter::INT_REGULAR]);
        $table->addColumn('whatsapp',           'integer',      ['limit'    => MysqlAdapter::INT_REGULAR]);
        $table->addColumn('inativo',            'integer',      ['limit'    => MysqlAdapter::INT_TINY]);
        $table->addColumn('usuario',            'string',       ['limit'    => 20]);
        $table->addColumn('data',               'datetime',     ['null'     => false]);
        $table->create();
    }

    public function down(): void
    {
        $this->table('bairros')->drop()->save();
    }
}
